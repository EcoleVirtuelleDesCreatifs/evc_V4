<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\MeetingClick;
use App\Models\Seance;
use App\Models\SeanceQrToken;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                ->with('qrToken')
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
    public function qrScan(Request $request): RedirectResponse
    {
        $token = $request->get('token');

        if (!$token) {
            return redirect('/')->with('error', 'QR code invalide.');
        }

        $qrToken = SeanceQrToken::with('seance')
            ->where('token', $token)
            ->first();

        if (!$qrToken || !$qrToken->seance) {
            return redirect('/')->with('error', 'QR code invalide.');
        }

        $seance = $qrToken->seance;

        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Veuillez vous connecter pour pointer.');
        }

        $user = Auth::user();
        $student = $user ? $user->student : null;

        if (!$student || $seance->formation !== $student->program) {
            return redirect('/')->with('error', 'Vous n\'êtes pas inscrit à cette formation.');
        }

        if (!in_array($seance->status, ['scheduled', 'ongoing'])) {
            return redirect('/')->with('error', 'La séance n\'est pas ouverte.');
        }

        if (!$qrToken->isValid()) {
            return redirect('/')->with('error', 'Le QR code a expiré ou est fermé.');
        }

        if (Attendance::where('seance_id', $seance->id)
            ->where('student_id', $student->id)
            ->whereNotNull('check_in_at')
            ->exists()) {
            return redirect('/evc/compte/' . $student->program . '/assiduite')
                ->with('error', 'Vous avez déjà pointé pour cette séance.');
        }

        $now = now();
        $status = $now->lte($seance->scheduled_at->copy()->addMinutes(15)) ? 'present' : 'late';

        Attendance::updateOrCreate(
            [
                'seance_id' => $seance->id,
                'student_id' => $student->id,
            ],
            [
                'user_id' => $student->user?->id,
                'status' => $status,
                'check_method' => 'qrcode',
                'recorded_by' => $user->id,
                'recorded_at' => $now,
                'check_in_at' => $now,
                'notes' => 'Présence par QR code',
            ]
        );

        return redirect('/evc/compte/' . $student->program . '/assiduite')
            ->with('success', 'Votre présence a été enregistrée.');
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

            $participationMinutes = $seances
                ->where('status', 'completed')
                ->filter(fn (Seance $s) =>
                    in_array($attendances[$s->id]->status ?? '', ['present', 'late'])
                )
                ->sum('duration_minutes');

            $stats = [
                'total' => $seances->count(),
                'completed' => $completedSeances,
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'excused' => $excused,
                'rate' => $rate,
                'participation_minutes' => $participationMinutes,
            ];
        }

        return view('assiduite.index', [
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
            'participation_minutes' => 0,
        ];
    }
}
