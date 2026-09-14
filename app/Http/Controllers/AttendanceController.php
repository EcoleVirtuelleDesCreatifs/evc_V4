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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Services\AttendanceService;

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
     * Intègre le tracking de temps de session.
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

        // Create meeting click record
        $meetingClick = MeetingClick::create([
            'seance_id' => $seance->id,
            'student_id' => $student->id,
            'clicked_at' => now(),
            'duration_seconds' => 0,
        ]);

        // Create or update session tracking for this seance (only if table exists)
        $sessionId = null;
        try {
            $existingSession = \App\Models\SessionTimeTracking::forStudent($student->id)
                ->forSeance($seance->id)
                ->active()
                ->first();

            if ($existingSession) {
                // End any existing session
                $existingSession->endSession();
            }

            // Create new session for meeting participation
            $session = \App\Models\SessionTimeTracking::create([
                'student_id' => $student->id,
                'seance_id' => $seance->id,
                'user_id' => $user->id,
                'page_type' => 'meeting',
                'session_start' => now(),
                'is_active' => true,
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'metadata' => [
                    'meeting_click_id' => $meetingClick->id,
                    'meet_link' => $seance->meet_link,
                    'source' => 'meet_click',
                ],
            ]);
            $sessionId = $session->id;
        } catch (\Illuminate\Database\QueryException $e) {
            // Log error if table doesn't exist yet, but don't block the redirect
            Log::warning('Session tracking table not available', [
                'error' => $e->getMessage(),
                'student_id' => $student->id,
                'seance_id' => $seance->id,
            ]);
        }

        // Rediriger vers la page de salle de réunion intégrée
        return redirect()->route($request->route()->getPrefix() . '.meet-room', [
            'seance' => $seance->id,
        ]);
    }

    /**
     * Affiche la page de salle de réunion intégrée avec Jitsi Meet.
     */
    public function meetRoom(Request $request, Seance $seance): View
    {
        $user = Auth::user();
        $student = $user ? $user->student : null;

        if (!$student || $seance->formation !== $student->program) {
            abort(403);
        }

        $routePrefix = explode('.', \Illuminate\Support\Facades\Route::currentRouteName())[0];

        // Générer un lien Jitsi Meet si meet_link n'existe pas ou est un lien Google Meet
        $meetLink = $seance->meet_link;
        if (empty($meetLink) || stripos($meetLink, 'meet.google.com') !== false) {
            // Générer un lien Jitsi Meet unique pour cette séance
            $roomName = 'evc-' . $seance->id . '-' . str_replace(' ', '-', strtolower($seance->title));
            $meetLink = 'https://meet.jit.si/' . $roomName;
        }

        // Passer le lien modifié à la vue
        $seance->meet_link = $meetLink;

        return view('student.meet-room', [
            'seance' => $seance,
            'student' => $student,
            'user' => $user,
            'routePrefix' => $routePrefix,
        ]);
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
    public function assiduiteIndex(Request $request, AttendanceService $service): View
    {
        $user = Auth::user();
        $student = $user ? $user->student : null;

        if ($student) {
            $data = $service->getStudentStats($student);
        } else {
            $data = [
                'seances' => collect([]),
                'attendances' => collect([]),
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

        return view('assiduite.index', [
            'seances' => $data['seances'],
            'attendances' => $data['attendances'],
            'stats' => $data,
            'student' => $student,
            'user' => $user,
        ]);
    }
}
