<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\MeetingClick;
use App\Models\Seance;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Liste des séances de la formation de l'étudiant connecté.
     */
    public function seancesIndex(Request $request): View
    {
        $user = Auth::user();
        $student = $user ? $user->student : null;
        $formation = $student ? $student->program : null;

        $current = null;
        $next = null;
        $upcoming = collect([]);
        $past = collect([]);
        $attendances = collect([]);
        $clicks = collect([]);

        if ($formation) {
            $now = now();
            $seances = Seance::forFormation($formation)
                ->orderBy('scheduled_at')
                ->get();

            $seanceIds = $seances->pluck('id');

            $attendances = Attendance::where('student_id', $student->id)
                ->whereIn('seance_id', $seanceIds)
                ->get()
                ->keyBy('seance_id');

            $clicks = MeetingClick::where('student_id', $student->id)
                ->whereIn('seance_id', $seanceIds)
                ->get()
                ->keyBy('seance_id');

            $all = $seances->sortBy('scheduled_at')->values();

            $current = $all->first(fn (Seance $s) => $s->isOngoing());

            $future = $all->filter(fn (Seance $s) =>
                $s->scheduled_at->greaterThan($now) && !$s->isOngoing()
            )->values();

            $next = $future->first();
            $upcoming = $future->slice(1)->values();

            $past = $all->filter(fn (Seance $s) =>
                $s->scheduled_at->lessThanOrEqualTo($now) && !$s->isOngoing()
            )->values()->sortByDesc('scheduled_at');
        }

        return view('seances.index', [
            'formationPrefix' => $formationPrefix,
            'current' => $current,
            'next' => $next,
            'upcoming' => $upcoming,
            'past' => $past,
            'attendances' => $attendances,
            'clicks' => $clicks,
            'student' => $student,
            'user' => $user,
        ]);
    }

    /**
     * Enregistre un clic sur le lien Google Meet sans valider la présence.
     */
    public function meetClick(Request $request, Seance $seance): RedirectResponse
    {
        $user = Auth::user();
        $student = $user ? $user->student : null;

        if (!$student || $seance->formation !== $student->program) {
            abort(403);
        }

        if (empty($seance->meet_link)) {
            return back()->with('error', 'Lien Google Meet indisponible.');
        }

        MeetingClick::create([
            'seance_id' => $seance->id,
            'student_id' => $student->id,
            'clicked_at' => now(),
        ]);

        return redirect()->away($seance->meet_link);
    }

    /**
     * Marque la présence de l'étudiant via QR code (présentiel / hybride).
     */
    public function qrPresence(Request $request, Seance $seance): RedirectResponse
    {
        $user = Auth::user();
        $student = $user ? $user->student : null;

        if (!$student || $seance->formation !== $student->program) {
            abort(403);
        }

        if (!in_array($seance->type, ['onsite', 'hybrid'])) {
            return back()->with('error', 'Cette séance ne prend pas en charge le QR code.');
        }

        if (!$seance->isOngoing()) {
            return back()->with('error', 'Vous ne pouvez marquer votre présence que pendant la séance.');
        }

        Attendance::updateOrCreate(
            [
                'seance_id' => $seance->id,
                'student_id' => $student->id,
            ],
            [
                'user_id' => $student->user_id,
                'status' => 'present',
                'check_method' => 'qrcode',
                'recorded_by' => $user->id,
                'recorded_at' => now(),
                'notes' => 'Présence scannée via QR code',
            ]
        );

        return back()->with('success', 'Votre présence a été enregistrée.');
    }

    /**
     * Bilan d'assiduité de l'étudiant connecté.
     */
    public function assiduiteIndex(Request $request): View
    {
        $user = Auth::user();
        $student = $user ? $user->student : null;
        $formation = $student ? $student->program : null;

        $seances = collect([]);
        $attendances = collect([]);
        $stats = $this->emptyStats();

        if ($formation) {
            $seances = Seance::forFormation($formation)
                ->visible()
                ->orderByDesc('scheduled_at')
                ->get();

            $seanceIds = $seances->pluck('id');
            $attendances = Attendance::where('student_id', $student->id)
                ->whereIn('seance_id', $seanceIds)
                ->get()
                ->keyBy('seance_id');

            $completedSeances = $seances->where('status', 'completed')->count();
            $present = $attendances->where('status', 'present')->count();
            $absent = $attendances->where('status', 'absent')->count();
            $late = $attendances->where('status', 'late')->count();
            $excused = $attendances->where('status', 'excused')->count();

            $rate = $completedSeances > 0
                ? round((($present + $late) / $completedSeances) * 100, 1)
                : 0;

            $stats = [
                'total' => $seances->count(),
                'completed' => $completedSeances,
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'excused' => $excused,
                'rate' => $rate,
            ];
        }

        return view('assiduite.index', [
            'formationPrefix' => $formationPrefix,
            'seances' => $seances,
            'attendances' => $attendances,
            'stats' => $stats,
            'student' => $student,
            'user' => $user,
        ]);
    }

    private function emptyStats(): array
    {
        return [
            'total' => 0,
            'completed' => 0,
            'present' => 0,
            'absent' => 0,
            'late' => 0,
            'excused' => 0,
            'rate' => 0,
        ];
    }
}
