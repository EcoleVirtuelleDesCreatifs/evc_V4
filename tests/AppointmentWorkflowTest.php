<?php

use App\Http\Controllers\Admin\AppointmentAdminController;
use App\Http\Controllers\AppointmentController;
use App\Models\Appointment;
use App\Models\AppointmentSlot;
use App\Models\User;
use App\Notifications\AppointmentNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AppointmentWorkflowTest extends TestCase
{
    public function createApplication()
    {
        $app = require dirname(__DIR__) . '/bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'session.driver' => 'array']);
        DB::purge('sqlite');
        Carbon::setTestNow('2026-09-24 10:00:00');
        Notification::fake();
        Mail::spy();
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
        });
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('program');
            $table->string('status');
            $table->timestamp('expiration_date')->nullable();
            $table->timestamps();
        });
        foreach (['2026_09_22_000001_create_appointment_slots_table.php', '2026_09_22_000002_create_appointments_table.php', '2026_09_24_000001_add_appointment_groups_and_private_slots.php'] as $migration) {
            (require database_path('migrations/' . $migration))->up();
        }
        foreach (range(1, 4) as $id) {
            DB::table('users')->insert(['id' => $id, 'name' => 'Student ' . $id, 'email' => 'student' . $id . '@example.test']);
            DB::table('students')->insert([
                'id' => $id, 'user_id' => $id, 'first_name' => 'Student', 'last_name' => (string) $id,
                'program' => 'Design Graphique', 'status' => 'active',
                'expiration_date' => '2027-01-01 00:00:00', 'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function request(array $data = [], string $method = 'POST'): Request
    {
        return Request::create('/appointments', $method, $data, [], [], ['HTTP_ACCEPT' => 'application/json']);
    }

    private function slot(array $data = []): AppointmentSlot
    {
        return AppointmentSlot::create(array_merge([
            'date' => '2026-09-24', 'start_time' => '14:00', 'end_time' => '15:00',
            'mode' => 'en_ligne', 'capacity' => 4, 'is_active' => true,
        ], $data));
    }

    private function booking(AppointmentSlot $slot, int $user, ?string $group = null): Appointment
    {
        $id = DB::table('appointments')->insertGetId([
            'slot_id' => $slot->id, 'user_id' => $user, 'student_id' => $user,
            'group_id' => $group, 'motif' => 'Assistance', 'status' => 'confirmed',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        return Appointment::findOrFail($id);
    }

    public function test_direct_booking_creates_a_private_group_and_notifies_every_student(): void
    {
        $response = app(AppointmentAdminController::class)->storeAppointment($this->request([
            'booking_type' => 'direct', 'date' => '2026-09-24', 'start_time' => '14:00', 'end_time' => '15:00',
            'mode' => 'en_ligne', 'student_ids' => [1, 2], 'motif' => 'Assistance',
        ]));
        $this->assertSame(200, $response->getStatusCode());
        $rows = Appointment::all();
        $this->assertCount(2, $rows);
        $this->assertNotNull($rows[0]->group_id);
        $this->assertSame($rows[0]->group_id, $rows[1]->group_id);
        $this->assertSame($rows[0]->meet_link, $rows[1]->meet_link);
        $this->assertNotEmpty($rows[0]->meet_link);
        $this->assertTrue($rows[0]->slot->is_private);
        $this->assertSame(2, $rows[0]->slot->capacity);
        $this->assertCount(0, $response->getData(true)['slots']);
        foreach ([1, 2] as $id) {
            Notification::assertSentTo(User::find($id), AppointmentNotification::class);
            $this->actingAs(User::find($id));
            $data = app(AppointmentController::class)->index()->getData();
            $this->assertCount(1, $data['upcomingAppointments']);
            $this->assertCount(0, $data['slotsByDate']);
        }
        Mail::shouldHaveReceived('send')->twice();
    }

    public function test_existing_slot_booking_works_today_and_enforces_capacity(): void
    {
        $slot = $this->slot(['capacity' => 2]);
        $payload = ['slot_id' => $slot->id, 'student_ids' => [1, 2], 'motif' => 'Assistance'];
        $controller = app(AppointmentAdminController::class);
        $this->assertSame(200, $controller->storeAppointment($this->request($payload))->getStatusCode());
        $this->assertNotNull(Appointment::first()->group_id);
        $payload['student_ids'] = [3];
        $this->assertSame(422, $controller->storeAppointment($this->request($payload))->getStatusCode());
        $this->assertSame(2, Appointment::count());
    }

    public function test_today_sixteen_to_twenty_thirty_slot_accepts_three_students_before_start(): void
    {
        $slot = $this->slot(['start_time' => '16:00', 'end_time' => '20:30', 'capacity' => 8]);
        $response = app(AppointmentAdminController::class)->storeAppointment($this->request([
            'slot_id' => $slot->id, 'student_ids' => [1, 2, 3], 'motif' => 'Autre demande',
            'message' => 'Formation d’initiation sur Adobe Photoshop',
        ]));
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(3, $slot->activeAppointments()->count());
        $this->assertSame(5, $slot->remainingCapacity());
        $this->assertSame(['confirmed'], Appointment::pluck('status')->unique()->values()->all());
        Carbon::setTestNow('2026-09-24 16:01:00');
        $lateResponse = app(AppointmentAdminController::class)->storeAppointment($this->request([
            'slot_id' => $slot->id, 'student_ids' => [4], 'motif' => 'Autre demande',
        ]));
        $this->assertSame(422, $lateResponse->getStatusCode());
        $this->assertSame(3, Appointment::count());
    }

    public function test_invalid_direct_booking_does_not_leave_an_orphan_slot(): void
    {
        DB::table('students')->update(['status' => 'inactive']);
        $response = app(AppointmentAdminController::class)->storeAppointment($this->request([
            'booking_type' => 'direct', 'date' => '2026-09-25', 'start_time' => '14:00', 'end_time' => '15:00',
            'mode' => 'presentiel', 'student_ids' => [1], 'motif' => 'Assistance',
        ]));
        $this->assertSame(422, $response->getStatusCode());
        $this->assertSame(0, AppointmentSlot::count());
        Notification::assertNothingSent();
    }

    public function test_private_slot_cannot_be_reused_by_admin_or_booked_by_a_student(): void
    {
        $slot = $this->slot(['is_private' => true]);
        $request = $this->request(['slot_id' => $slot->id, 'student_ids' => [1], 'motif' => 'Assistance']);
        $this->assertSame(422, app(AppointmentAdminController::class)->storeAppointment($request)->getStatusCode());
        $this->actingAs(User::find(1));
        app(AppointmentController::class)->store($request);
        $this->assertSame(0, Appointment::count());
    }

    public function test_group_deletion_removes_all_participants_but_not_other_bookings_on_the_slot(): void
    {
        $slot = $this->slot();
        $group = (string) Str::uuid();
        $first = $this->booking($slot, 1, $group);
        $second = $this->booking($slot, 2, $group);
        $unrelated = $this->booking($slot, 3, (string) Str::uuid());
        $response = app(AppointmentAdminController::class)->destroyAppointment($this->request(['scope' => 'group'], 'DELETE'), $first->id);
        $this->assertEqualsCanonicalizing([$first->id, $second->id], $response->getData(true)['deleted_ids']);
        $this->assertSame([$unrelated->id], Appointment::pluck('id')->all());
        $this->assertSame(1, $slot->activeAppointments()->count());
        foreach ([1, 2] as $id) {
            Notification::assertSentTo(User::find($id), AppointmentNotification::class);
            $this->actingAs(User::find($id));
            $data = app(AppointmentController::class)->index()->getData();
            $this->assertCount(0, $data['upcomingAppointments']);
            $this->assertCount(0, $data['pastAppointments']);
        }
        Notification::assertNotSentTo(User::find(3), AppointmentNotification::class);
        Mail::shouldHaveReceived('send')->twice();
    }

    public function test_participant_removal_preserves_the_other_group_members(): void
    {
        $slot = $this->slot(['is_private' => true]);
        $group = (string) Str::uuid();
        $first = $this->booking($slot, 1, $group);
        $second = $this->booking($slot, 2, $group);
        $controller = app(AppointmentAdminController::class);
        $controller->destroyAppointment($this->request(['scope' => 'participant'], 'DELETE'), $first->id);
        $this->assertSame([$second->id], Appointment::pluck('id')->all());
        $this->assertTrue(AppointmentSlot::whereKey($slot->id)->exists());
        Notification::assertNotSentTo(User::find(2), AppointmentNotification::class);
        $controller->destroyAppointment($this->request(['scope' => 'group'], 'DELETE'), $second->id);
        $this->assertSame(0, AppointmentSlot::count());
    }

    public function test_direct_booking_rejects_past_start_and_invalid_end(): void
    {
        $payload = [
            'booking_type' => 'direct', 'date' => '2026-09-24', 'start_time' => '09:00', 'end_time' => '11:00',
            'mode' => 'presentiel', 'student_ids' => [1], 'motif' => 'Assistance',
        ];
        $controller = app(AppointmentAdminController::class);
        $this->assertSame(422, $controller->storeAppointment($this->request($payload))->getStatusCode());
        $this->assertSame(0, AppointmentSlot::count());
        $payload['start_time'] = '14:00';
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $controller->storeAppointment($this->request($payload));
    }

    public function test_picker_and_creation_exclude_inactive_expired_and_unlinked_students(): void
    {
        DB::table('students')->where('id', 2)->update(['status' => 'inactive']);
        DB::table('students')->where('id', 3)->update(['expiration_date' => '2026-09-24 09:00:00']);
        DB::table('students')->where('id', 4)->update(['user_id' => null]);
        $controller = app(AppointmentAdminController::class);
        $this->assertSame([1], $controller->index($this->request())->getData()['studentsJson']->pluck('id')->all());
        $response = $controller->storeAppointment($this->request([
            'booking_type' => 'direct', 'date' => '2026-09-25', 'start_time' => '14:00', 'end_time' => '15:00',
            'mode' => 'presentiel', 'lieu' => 'Campus EVC', 'student_ids' => [1, 2, 3, 4], 'motif' => 'Assistance',
        ]));
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame([1], Appointment::pluck('student_id')->all());
        $this->assertSame('Campus EVC', AppointmentSlot::first()->lieu);
        $this->assertNull(Appointment::first()->meet_link);
        $this->assertSame(1, AppointmentSlot::first()->capacity);
    }

    public function test_separate_admin_creations_on_the_same_slot_have_distinct_groups(): void
    {
        $slot = $this->slot();
        $controller = app(AppointmentAdminController::class);
        foreach ([[1, 2], [2, 3]] as $ids) {
            $response = $controller->storeAppointment($this->request(['slot_id' => $slot->id, 'student_ids' => $ids, 'motif' => 'Assistance']));
            $this->assertSame(200, $response->getStatusCode());
        }
        $this->assertSame(3, Appointment::count());
        $this->assertSame(2, Appointment::distinct()->count('group_id'));
        $controller->destroyAppointment($this->request([], 'DELETE'), Appointment::where('student_id', 1)->first()->id);
        $this->assertSame([3], Appointment::pluck('student_id')->all());
    }

    public function test_private_appointment_can_be_edited_and_replanned_participant_leaves_the_group(): void
    {
        $private = $this->slot(['is_private' => true]);
        $group = (string) Str::uuid();
        $first = $this->booking($private, 1, $group);
        $second = $this->booking($private, 2, $group);
        $controller = app(AppointmentAdminController::class);
        $payload = ['slot_id' => $private->id, 'motif' => 'Nouveau motif', 'admin_note' => 'Nouvelle note'];
        $this->assertSame(200, $controller->updateAppointment($this->request($payload), $first->id)->getStatusCode());
        $this->assertSame($group, $first->fresh()->group_id);
        $this->assertSame('Nouvelle note', $first->fresh()->admin_note);
        $payload['slot_id'] = $this->slot(['is_private' => true])->id;
        $this->assertSame(422, $controller->updateAppointment($this->request($payload), $first->id)->getStatusCode());
        $payload['slot_id'] = $this->slot()->id;
        $this->assertSame(200, $controller->updateAppointment($this->request($payload), $first->id)->getStatusCode());
        $this->assertNull($first->fresh()->group_id);
        $controller->destroyAppointment($this->request(['scope' => 'group'], 'DELETE'), $second->id);
        $this->assertTrue(Appointment::whereKey($first->id)->exists());
    }

    public function test_slot_deletion_preserves_cancelled_appointment_history(): void
    {
        $slot = $this->slot();
        $appointment = $this->booking($slot, 1);
        $appointment->update(['status' => 'cancelled']);
        app(AppointmentAdminController::class)->destroySlot($this->request([], 'DELETE'), $slot->id);
        $this->assertTrue(Appointment::whereKey($appointment->id)->exists());
        $this->assertFalse($slot->fresh()->is_active);
    }

    public function test_failed_group_deletion_rolls_back_before_any_notifications(): void
    {
        $slot = $this->slot(['is_private' => true]);
        $group = (string) Str::uuid();
        $first = $this->booking($slot, 1, $group);
        $this->booking($slot, 2, $group);
        AppointmentSlot::deleting(function () {
            throw new \RuntimeException('Simulated failure');
        });
        try {
            app(AppointmentAdminController::class)->destroyAppointment($this->request(['scope' => 'group'], 'DELETE'), $first->id);
            $this->fail('Deletion should have failed.');
        } catch (\RuntimeException $e) {
            $this->assertSame('Simulated failure', $e->getMessage());
        } finally {
            AppointmentSlot::flushEventListeners();
        }
        $this->assertSame(2, Appointment::count());
        $this->assertSame(1, AppointmentSlot::count());
        Notification::assertNothingSent();
        Mail::shouldNotHaveReceived('send');
    }

    public function test_invalid_deletion_scope_does_not_delete_anything(): void
    {
        $appointment = $this->booking($this->slot(), 1);
        try {
            app(AppointmentAdminController::class)->destroyAppointment($this->request(['scope' => 'slot'], 'DELETE'), $appointment->id);
            $this->fail('Invalid scope was accepted.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('scope', $e->errors());
            $this->assertSame(1, Appointment::count());
        }
    }

    public function test_admin_mutations_require_admin_authentication(): void
    {
        foreach (['admin.appointments.store', 'admin.appointments.destroy', 'admin.appointments.update'] as $name) {
            $route = app('router')->getRoutes()->getByName($name);
            $this->assertContains('admin.auth', $route->gatherMiddleware());
        }
    }

    public function test_appointment_views_render_and_admin_javascript_parses(): void
    {
        $slot = $this->slot(['mode' => 'presentiel', 'lieu' => 'Campus EVC']);
        $appointment = $this->booking($slot, 1, (string) Str::uuid());
        $controller = app(AppointmentAdminController::class);
        $view = file_get_contents(resource_path('views/admin/appointments/index.blade.php'));
        $view = str_replace("@extends('layouts.admin')", '', $view);
        $view .= "\n@stack('styles')\n@yield('content')\n@stack('modals')\n@stack('scripts')";
        $html = \Illuminate\Support\Facades\Blade::render($view, $controller->index($this->request())->getData());
        $this->assertStringContainsString('Rendez-vous direct', $html);
        $this->assertStringContainsString('bookDirectFields', $html);
        preg_match_all('/<script[^>]*>(.*?)<\/script>/s', $html, $scripts);
        $this->assertNotEmpty($scripts[1]);
        $process = new \Symfony\Component\Process\Process(['node', '--check']);
        $process->setInput(implode("\n", $scripts[1]));
        $process->run();
        $this->assertTrue($process->isSuccessful(), $process->getErrorOutput());
        $this->actingAs(User::find(1));
        $studentView = file_get_contents(resource_path('views/appointments/index.blade.php'));
        $studentView = str_replace("@extends('layouts.ki-admin')", '', $studentView) . "\n@yield('content')";
        $studentHtml = \Illuminate\Support\Facades\Blade::render($studentView, app(AppointmentController::class)->index()->getData());
        $this->assertStringContainsString('Campus EVC', $studentHtml);
        foreach (['confirmed', 'cancelled', 'modified'] as $status) {
            $email = view('emails.appointment_status', [
                'appointment' => $appointment, 'user' => User::find(1), 'student' => User::find(1),
                'status' => $status, 'slotLabel' => '24/09/2026', 'appointmentsUrl' => route('student.appointments.index'),
            ])->render();
            $this->assertStringContainsString('Campus EVC', $email);
        }
    }

    public function test_legacy_bookings_are_never_grouped_by_slot_for_deletion(): void
    {
        $slot = $this->slot();
        $first = $this->booking($slot, 1);
        $second = $this->booking($slot, 2);
        app(AppointmentAdminController::class)->destroyAppointment($this->request(['scope' => 'group'], 'DELETE'), $first->id);
        $this->assertSame([$second->id], Appointment::pluck('id')->all());
    }
}
