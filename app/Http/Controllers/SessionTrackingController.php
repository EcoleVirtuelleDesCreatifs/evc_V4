<?php

namespace App\Http\Controllers;

use App\Models\SessionTimeTracking;
use App\Models\Seance;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SessionTrackingController extends Controller
{
    /**
     * Start a new tracking session.
     */
    public function startSession(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user || !$user->student) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $student = $user->student;
        $seanceId = $request->input('seance_id');
        $pageType = $request->input('page_type', 'seance');

        // Validate seance belongs to student's formation
        if ($seanceId) {
            $seance = Seance::find($seanceId);
            if (!$seance || $seance->formation !== $student->program) {
                return response()->json(['error' => 'Invalid seance'], 403);
            }
        }

        try {
            // End any active sessions for this student
            SessionTimeTracking::forStudent($student->id)
                ->active()
                ->get()
                ->each(fn ($session) => $session->endSession());

            // Create new session
            $session = SessionTimeTracking::create([
                'student_id' => $student->id,
                'seance_id' => $seanceId,
                'user_id' => $user->id,
                'page_type' => $pageType,
                'session_start' => now(),
                'is_active' => true,
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'metadata' => [
                    'url' => $request->input('url'),
                    'referrer' => $request->input('referrer'),
                ],
            ]);

            return response()->json([
                'success' => true,
                'session_id' => $session->id,
                'started_at' => $session->session_start,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Table doesn't exist yet, return success but without session data
            Log::warning('Session tracking table not available', [
                'error' => $e->getMessage(),
                'student_id' => $student->id,
            ]);

            return response()->json([
                'success' => true,
                'session_id' => null,
                'started_at' => now(),
                'warning' => 'Tracking not available',
            ]);
        }
    }

    /**
     * Update session duration (heartbeat).
     */
    public function updateSession(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user || !$user->student) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $sessionId = $request->input('session_id');

        try {
            $session = SessionTimeTracking::find($sessionId);

            if (!$session || $session->student_id !== $user->student->id) {
                return response()->json(['error' => 'Session not found'], 404);
            }

            if (!$session->is_active) {
                return response()->json(['error' => 'Session already ended'], 400);
            }

            // Update duration
            $session->duration_seconds = $session->session_start->diffInSeconds(now());
            $session->save();

            return response()->json([
                'success' => true,
                'duration_seconds' => $session->duration_seconds,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::warning('Session tracking table not available for update', [
                'error' => $e->getMessage(),
                'session_id' => $sessionId,
            ]);

            return response()->json([
                'success' => true,
                'duration_seconds' => 0,
                'warning' => 'Tracking not available',
            ]);
        }
    }

    /**
     * End a tracking session.
     */
    public function endSession(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user || !$user->student) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $sessionId = $request->input('session_id');

        try {
            $session = SessionTimeTracking::find($sessionId);

            if (!$session || $session->student_id !== $user->student->id) {
                return response()->json(['error' => 'Session not found'], 404);
            }

            $session->endSession();

            return response()->json([
                'success' => true,
                'duration_seconds' => $session->duration_seconds,
                'session_end' => $session->session_end,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::warning('Session tracking table not available for end', [
                'error' => $e->getMessage(),
                'session_id' => $sessionId,
            ]);

            return response()->json([
                'success' => true,
                'duration_seconds' => 0,
                'warning' => 'Tracking not available',
            ]);
        }
    }

    /**
     * Get student's session statistics.
     */
    public function getStats(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user || !$user->student) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $student = $user->student;
        $seanceId = $request->input('seance_id');
        $period = $request->input('period', 'all'); // today, week, month, all

        try {
            $query = SessionTimeTracking::forStudent($student->id);

            if ($seanceId) {
                $query->forSeance($seanceId);
            }

            switch ($period) {
                case 'today':
                    $query->today();
                    break;
                case 'week':
                    $query->where('session_start', '>=', now()->startOfWeek());
                    break;
                case 'month':
                    $query->where('session_start', '>=', now()->startOfMonth());
                    break;
            }

            $sessions = $query->get();
            $totalSeconds = $sessions->sum('duration_seconds');

            // Calculate statistics
            $totalHours = floor($totalSeconds / 3600);
            $totalMinutes = floor(($totalSeconds % 3600) / 60);
            $totalSecondsRemainder = $totalSeconds % 60;

            return response()->json([
                'success' => true,
                'stats' => [
                    'total_sessions' => $sessions->count(),
                    'total_seconds' => $totalSeconds,
                    'total_hours' => $totalHours,
                    'total_minutes' => $totalMinutes,
                    'total_seconds_remainder' => $totalSecondsRemainder,
                    'formatted_time' => sprintf('%02d:%02d:%02d', $totalHours, $totalMinutes, $totalSecondsRemainder),
                    'average_session_seconds' => $sessions->count() > 0 ? $totalSeconds / $sessions->count() : 0,
                ],
                'sessions' => $sessions->map(fn ($s) => [
                    'id' => $s->id,
                    'page_type' => $s->page_type,
                    'session_start' => $s->session_start,
                    'session_end' => $s->session_end,
                    'duration_seconds' => $s->duration_seconds,
                    'formatted_duration' => gmdate('H:i:s', $s->duration_seconds),
                ]),
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::warning('Session tracking table not available for stats', [
                'error' => $e->getMessage(),
                'student_id' => $student->id,
            ]);

            return response()->json([
                'success' => true,
                'stats' => [
                    'total_sessions' => 0,
                    'total_seconds' => 0,
                    'total_hours' => 0,
                    'total_minutes' => 0,
                    'total_seconds_remainder' => 0,
                    'formatted_time' => '00:00:00',
                    'average_session_seconds' => 0,
                ],
                'sessions' => [],
                'warning' => 'Tracking not available',
            ]);
        }
    }

    /**
     * Update meeting click with duration tracking.
     */
    public function updateMeetingClick(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user || !$user->student) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $seanceId = $request->input('seance_id');
        $duration = $request->input('duration_seconds', 0);

        try {
            // Update meeting click record
            $meetingClick = \App\Models\MeetingClick::where('student_id', $user->student->id)
                ->where('seance_id', $seanceId)
                ->latest()
                ->first();

            if ($meetingClick) {
                $meetingClick->duration_seconds = $duration;
                $meetingClick->save();
            }

            return response()->json(['success' => true]);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::warning('Meeting click duration update failed', [
                'error' => $e->getMessage(),
                'seance_id' => $seanceId,
            ]);

            return response()->json(['success' => true, 'warning' => 'Tracking not available']);
        }
    }

    /**
     * WebTV API integration - log WebTV connection time.
     */
    public function logWebtvConnection(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user || !$user->student) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'webtv_session_id' => 'required|string',
            'duration_seconds' => 'required|integer',
            'started_at' => 'required|date',
            'ended_at' => 'required|date',
        ]);

        try {
            // Create a WebTV session record
            $session = SessionTimeTracking::create([
                'student_id' => $user->student->id,
                'user_id' => $user->id,
                'page_type' => 'webtv',
                'session_start' => $validated['started_at'],
                'session_end' => $validated['ended_at'],
                'duration_seconds' => $validated['duration_seconds'],
                'is_active' => false,
                'metadata' => [
                    'webtv_session_id' => $validated['webtv_session_id'],
                    'source' => 'webtv_api',
                ],
            ]);

            Log::info('WebTV connection logged', [
                'student_id' => $user->student->id,
                'duration' => $validated['duration_seconds'],
                'session_id' => $session->id,
            ]);

            return response()->json(['success' => true, 'session_id' => $session->id]);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::warning('WebTV connection logging failed - table not available', [
                'error' => $e->getMessage(),
                'student_id' => $user->student->id,
            ]);

            return response()->json(['success' => true, 'warning' => 'Tracking not available']);
        }
    }
}
