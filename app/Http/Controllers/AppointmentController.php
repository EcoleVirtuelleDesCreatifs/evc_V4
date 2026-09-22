<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Appointment;
use App\Models\AppointmentSlot;
use App\Models\Student;
use App\Notifications\AppointmentNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    public const MOTIFS = [
        'Assistance technique',
        'Question sur un cours',
        'Aide sur un projet / TP',
        'Problème de paiement',
        'Orientation & suivi pédagogique',
        'Autre demande',
    ];

    public function index()
    {
        $user = auth()->user();

        $slots = AppointmentSlot::where('is_active', true)
            ->where('date', '>=', Carbon::today())
            ->withCount(['appointments as booked_count' => function ($q) {
                $q->whereIn('status', ['pending', 'confirmed']);
            }])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->filter(function ($slot) {
                if ($slot->booked_count >= $slot->capacity) {
                    return false;
                }
                // Masquer les créneaux du jour déjà commencés
                if ($slot->date->isToday() && Carbon::parse($slot->start_time)->lt(Carbon::now())) {
                    return false;
                }
                return true;
            })
            ->groupBy(function ($slot) {
                return $slot->date->toDateString();
            });

        $myAppointments = Appointment::where('user_id', $user->id)
            ->with('slot')
            ->orderByDesc('created_at')
            ->get()
            ->partition(function ($a) {
                return in_array($a->status, ['pending', 'confirmed'])
                    && $a->slot && !$a->slot->isPast();
            });

        return view('appointments.index', [
            'slotsByDate' => $slots,
            'upcomingAppointments' => $myAppointments[0],
            'pastAppointments' => $myAppointments[1],
            'motifs' => self::MOTIFS,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'slot_id' => 'required|exists:appointment_slots,id',
            'motif' => 'required|string|max:150',
            'message' => 'nullable|string|max:2000',
        ]);

        $user = auth()->user();

        $appointment = DB::transaction(function () use ($validated, $user) {
            $slot = AppointmentSlot::where('id', $validated['slot_id'])
                ->lockForUpdate()
                ->first();

            if (!$slot || !$slot->is_active) {
                return ['error' => 'Ce créneau n\'est plus disponible.'];
            }

            if ($slot->date->isPast() || ($slot->date->isToday() && Carbon::parse($slot->start_time)->lt(Carbon::now()))) {
                return ['error' => 'Ce créneau est déjà passé.'];
            }

            $booked = $slot->activeAppointments()->count();
            if ($booked >= $slot->capacity) {
                return ['error' => 'Ce créneau vient d\'être réservé par un autre étudiant.'];
            }

            $already = Appointment::where('user_id', $user->id)
                ->where('slot_id', $slot->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->exists();

            if ($already) {
                return ['error' => 'Vous avez déjà un rendez-vous sur ce créneau.'];
            }

            $student = Student::where('user_id', $user->id)->first();

            return Appointment::create([
                'slot_id' => $slot->id,
                'user_id' => $user->id,
                'student_id' => $student->id ?? null,
                'motif' => $validated['motif'],
                'message' => $validated['message'] ?? null,
                'status' => 'pending',
            ]);
        });

        if (is_array($appointment) && isset($appointment['error'])) {
            return back()->with('error', $appointment['error']);
        }

        $this->notifyAdmins($appointment, $user);
        $user->notify(new AppointmentNotification([
            'category' => 'appointment',
            'event' => 'booked',
            'title' => 'Rendez-vous demandé',
            'message' => 'Votre demande de rendez-vous du ' . $this->formatSlot($appointment->slot) . ' a été envoyée à EVC.',
            'appointment_id' => $appointment->id,
            'url' => route('student.appointments.index'),
            'created_at' => now()->toIso8601String(),
        ]));

        return back()->with('success', 'Votre demande de rendez-vous a été envoyée. Vous serez notifié dès sa confirmation.');
    }

    public function cancel($id)
    {
        $appointment = Appointment::with('slot')->where('user_id', auth()->id())->findOrFail($id);

        if (!$appointment->isCancellable()) {
            return back()->with('error', $appointment->status === 'confirmed'
                ? 'Ce rendez-vous est déjà confirmé. Contactez EVC pour toute modification.'
                : 'Ce rendez-vous ne peut plus être annulé.');
        }

        $appointment->update(['status' => 'cancelled']);
        $this->notifyAdmins($appointment->fresh('slot'), auth()->user(), true);

        return back()->with('success', 'Votre rendez-vous a été annulé.');
    }

    private function notifyAdmins(Appointment $appointment, $user, bool $cancelled = false): void
    {
        try {
            $slot = $appointment->slot;
            $payload = [
                'category' => 'appointment',
                'event' => $cancelled ? 'cancelled_by_student' : 'booked',
                'title' => $cancelled ? 'Rendez-vous annulé' : 'Nouvelle demande de rendez-vous',
                'message' => ($user->name ?? 'Un étudiant') . ($cancelled
                    ? ' a annulé le rendez-vous du ' . $this->formatSlot($slot)
                    : ' demande un rendez-vous le ' . $this->formatSlot($slot) . ' — ' . $appointment->motif),
                'appointment_id' => $appointment->id,
                'url' => route('admin.appointments.index'),
                'created_at' => now()->toIso8601String(),
            ];

            Admin::each(function ($admin) use ($payload) {
                $admin->notify(new AppointmentNotification($payload));
            });
        } catch (\Throwable $e) {
            \Log::warning('Appointment admin notification failed: ' . $e->getMessage());
        }

        // Email aux admins (nouvelle demande ou annulation)
        try {
            $slotLabel = $this->formatSlot($appointment->slot);
            Admin::whereNotNull('email')->each(function ($admin) use ($appointment, $user, $slotLabel, $cancelled) {
                if (empty($admin->email)) {
                    return;
                }
                try {
                    Mail::send('emails.appointment_request', [
                        'admin' => $admin,
                        'student' => $user,
                        'appointment' => $appointment,
                        'slotLabel' => $slotLabel,
                        'cancelled' => $cancelled,
                        'adminUrl' => route('admin.appointments.index'),
                    ], function ($m) use ($admin, $user, $slotLabel, $cancelled) {
                        $subject = $cancelled
                            ? 'Rendez-vous annulé — ' . ($user->name ?? 'Étudiant')
                            : 'Nouvelle demande de RDV — ' . ($user->name ?? 'Étudiant') . ' (' . $slotLabel . ')';
                        $m->to($admin->email, $admin->name ?? null)->subject($subject);
                    });
                } catch (\Throwable $e) {
                    \Log::warning('Appointment admin email failed: ' . $e->getMessage());
                }
            });
        } catch (\Throwable $e) {
            \Log::warning('Appointment admin emails failed: ' . $e->getMessage());
        }
    }

    public function formatSlot(?AppointmentSlot $slot): string
    {
        if (!$slot) {
            return '';
        }
        return $slot->date->format('d/m/Y') . ' de ' . Carbon::parse($slot->start_time)->format('H:i')
            . ' à ' . Carbon::parse($slot->end_time)->format('H:i');
    }
}
