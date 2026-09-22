<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentSlot;
use App\Notifications\AppointmentNotification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AppointmentAdminController extends Controller
{
    /**
     * Gestion des créneaux de disponibilité + liste des rendez-vous.
     */
    public function index(Request $request): View
    {
        $slots = $this->upcomingSlots()->map(fn ($s) => $this->serializeSlot($s))->values();
        $appointments = $this->allAppointments()->map(fn ($a) => $this->serializeAppointment($a))->values();

        return view('admin.appointments.index', [
            'slotsJson' => $slots,
            'appointmentsJson' => $appointments,
        ]);
    }

    /**
     * Créer un ou plusieurs créneaux (récurrence hebdomadaire optionnelle).
     */
    public function storeSlot(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'mode' => 'required|in:en_ligne,presentiel',
            'lieu' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1|max:10',
            'repeat_weeks' => 'nullable|integer|min:0|max:8',
        ]);

        $adminId = session('admin_id') ?? auth()->id();
        $repeat = (int) ($validated['repeat_weeks'] ?? 0);
        $created = 0;
        $skipped = 0;

        for ($i = 0; $i <= $repeat; $i++) {
            $date = Carbon::parse($validated['date'])->addWeeks($i);

            $exists = AppointmentSlot::where('date', $date->toDateString())
                ->where('start_time', $validated['start_time'])
                ->where('end_time', $validated['end_time'])
                ->where('is_active', true)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            AppointmentSlot::create([
                'admin_id' => $adminId,
                'date' => $date->toDateString(),
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'mode' => $validated['mode'],
                'lieu' => $validated['lieu'] ?? null,
                'capacity' => $validated['capacity'],
                'is_active' => true,
            ]);
            $created++;
        }

        $msg = $created . ' créneau(x) créé(s).' . ($skipped > 0 ? ' ' . $skipped . ' ignoré(s) (doublon).' : '');

        if ($request->expectsJson()) {
            if ($created === 0) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return response()->json([
                'success' => true,
                'message' => $msg,
                'slots' => $this->upcomingSlots()->map(fn ($s) => $this->serializeSlot($s))->values(),
            ]);
        }

        return back()->with($created > 0 ? 'success' : 'error', $msg);
    }

    /**
     * Supprimer/désactiver un créneau.
     */
    public function destroySlot(Request $request, $id)
    {
        $slot = AppointmentSlot::withCount(['appointments as active_bookings' => function ($q) {
            $q->whereIn('status', ['pending', 'confirmed']);
        }])->findOrFail($id);

        $deactivated = false;
        if ($slot->active_bookings > 0) {
            $slot->update(['is_active' => false]);
            $deactivated = true;
            $msg = 'Créneau désactivé (des rendez-vous y sont encore rattachés).';
        } else {
            $slot->delete();
            $msg = 'Créneau supprimé.';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'deactivated' => $deactivated,
                'slots' => $this->upcomingSlots()->map(fn ($s) => $this->serializeSlot($s))->values(),
            ]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Confirmer / annuler / terminer un rendez-vous.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:confirmed,cancelled,completed',
            'meet_link' => 'nullable|url|max:500',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $appointment = Appointment::with(['slot', 'user'])->findOrFail($id);
        $newStatus = $validated['status'];

        if ($appointment->status === $newStatus) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Ce rendez-vous a déjà ce statut.'], 422);
            }
            return back()->with('error', 'Ce rendez-vous a déjà ce statut.');
        }

        $data = [
            'status' => $newStatus,
            'admin_note' => $validated['admin_note'] ?? $appointment->admin_note,
        ];

        // Lien Jitsi automatique si confirmation en ligne sans lien fourni
        if ($newStatus === 'confirmed' && $appointment->slot?->mode === 'en_ligne') {
            $meetLink = $validated['meet_link'] ?? $appointment->meet_link;
            if (empty($meetLink)) {
                $roomName = 'evc-rdv-' . $appointment->id . '-' . strtolower(\Illuminate\Support\Str::random(6));
                $meetLink = 'https://meet.jit.si/' . $roomName
                    . '#config.prejoinPageEnabled=false&config.startWithAudioMuted=true&config.startWithVideoMuted=true';
            }
            $data['meet_link'] = $meetLink;
        } elseif (!empty($validated['meet_link'])) {
            $data['meet_link'] = $validated['meet_link'];
        }

        $appointment->update($data);
        $this->notifyStudent($appointment->fresh(['slot', 'user']), $newStatus);

        $labels = ['confirmed' => 'confirmé', 'cancelled' => 'annulé', 'completed' => 'marqué comme terminé'];
        $msg = 'Rendez-vous ' . ($labels[$newStatus] ?? $newStatus) . '.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'appointment' => $this->serializeAppointment($appointment->fresh(['slot', 'user', 'student'])),
            ]);
        }

        return back()->with('success', $msg);
    }

    /* ──────────────────────────────────────────────── */

    private function upcomingSlots()
    {
        return AppointmentSlot::where('date', '>=', Carbon::today())
            ->withCount(['appointments as booked_count' => function ($q) {
                $q->whereIn('status', ['pending', 'confirmed']);
            }])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
    }

    private function allAppointments()
    {
        return Appointment::with(['slot', 'user', 'student'])
            ->join('appointment_slots', 'appointments.slot_id', '=', 'appointment_slots.id')
            ->select('appointments.*')
            ->orderBy('appointment_slots.date')
            ->orderBy('appointment_slots.start_time')
            ->get();
    }

    private function serializeSlot(AppointmentSlot $s): array
    {
        return [
            'id' => $s->id,
            'day' => $s->date->format('d'),
            'month' => $s->date->translatedFormat('M'),
            'date_label' => $s->date->translatedFormat('D j M'),
            'date_full' => $s->date->format('d/m/Y'),
            'start' => Carbon::parse($s->start_time)->format('H:i'),
            'end' => Carbon::parse($s->end_time)->format('H:i'),
            'mode' => $s->mode,
            'lieu' => $s->lieu,
            'capacity' => (int) $s->capacity,
            'booked' => (int) $s->booked_count,
            'active' => (bool) $s->is_active,
        ];
    }

    private function serializeAppointment(Appointment $a): array
    {
        $slot = $a->slot;
        return [
            'id' => $a->id,
            'slot_id' => $a->slot_id,
            'student_name' => $a->user->name ?? ($a->student ? trim(($a->student->first_name ?? '') . ' ' . ($a->student->last_name ?? '')) : '—'),
            'student_email' => $a->user->email ?? '',
            'formation' => $a->student->program ?? '',
            'motif' => $a->motif,
            'message' => $a->message,
            'status' => $a->status,
            'meet_link' => $a->meet_link,
            'admin_note' => $a->admin_note,
            'slot_date' => $slot ? $slot->date->format('d/m/Y') : '',
            'slot_date_raw' => $slot ? $slot->date->toDateString() : '',
            'slot_time' => $slot ? Carbon::parse($slot->start_time)->format('H:i') . '–' . Carbon::parse($slot->end_time)->format('H:i') : '',
            'slot_mode' => $slot->mode ?? '',
            'slot_past' => $slot ? ($slot->date->isPast() && !$slot->date->isToday()) : false,
        ];
    }

    private function notifyStudent(Appointment $appointment, string $status): void
    {
        $user = $appointment->user;
        if (!$user) {
            return;
        }

        $slotLabel = $this->formatSlot($appointment->slot);
        $titles = [
            'confirmed' => 'Rendez-vous confirmé',
            'cancelled' => 'Rendez-vous annulé',
            'completed' => 'Rendez-vous terminé',
        ];
        $messages = [
            'confirmed' => 'Votre rendez-vous du ' . $slotLabel . ' est confirmé.',
            'cancelled' => 'Votre rendez-vous du ' . $slotLabel . ' a été annulé par EVC.',
            'completed' => 'Votre rendez-vous du ' . $slotLabel . ' est terminé.',
        ];

        try {
            $user->notify(new AppointmentNotification([
                'category' => 'appointment',
                'event' => $status,
                'title' => $titles[$status] ?? 'Rendez-vous',
                'message' => $messages[$status] ?? '',
                'appointment_id' => $appointment->id,
                'url' => route('student.appointments.index'),
                'created_at' => now()->toIso8601String(),
            ]));
        } catch (\Throwable $e) {
            \Log::warning('Appointment in-app notification failed: ' . $e->getMessage());
        }

        try {
            if (!empty($user->email)) {
                Mail::send('emails.appointment_status', [
                    'user' => $user,
                    'student' => $user->student ?? $user,
                    'appointment' => $appointment,
                    'status' => $status,
                    'slotLabel' => $slotLabel,
                    'appointmentsUrl' => route('student.appointments.index'),
                ], function ($m) use ($user, $status, $slotLabel) {
                    $subject = match ($status) {
                        'confirmed' => 'Rendez-vous confirmé — ' . $slotLabel,
                        'cancelled' => 'Rendez-vous annulé — ' . $slotLabel,
                        default => 'Votre rendez-vous EVC',
                    };
                    $m->to($user->email, $user->name ?? null)->subject($subject);
                });
            }
        } catch (\Throwable $e) {
            \Log::warning('Appointment email failed: ' . $e->getMessage());
        }
    }

    private function formatSlot(?AppointmentSlot $slot): string
    {
        if (!$slot) {
            return '';
        }
        return $slot->date->format('d/m/Y') . ' de ' . Carbon::parse($slot->start_time)->format('H:i')
            . ' à ' . Carbon::parse($slot->end_time)->format('H:i');
    }
}
