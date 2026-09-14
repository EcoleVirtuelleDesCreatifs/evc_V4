<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SessionTimeTracking;
use App\Models\Student;
use App\Models\Seance;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class SessionTrackingAdminController extends Controller
{
    /**
     * Admin dashboard for session tracking statistics.
     */
    public function index(Request $request): View
    {
        $period = $request->get('period', 'week'); // today, week, month, all
        $formation = $request->get('formation', '');
        $studentId = $request->get('student_id', '');

        $query = SessionTimeTracking::with(['student', 'student.user', 'seance']);

        // Apply period filter
        switch ($period) {
            case 'today':
                $query->whereDate('session_start', today());
                break;
            case 'week':
                $query->where('session_start', '>=', now()->startOfWeek());
                break;
            case 'month':
                $query->where('session_start', '>=', now()->startOfMonth());
                break;
        }

        // Apply formation filter
        if ($formation) {
            $query->whereHas('student', function ($q) use ($formation) {
                $q->where('program', $formation);
            });
        }

        // Apply student filter
        if ($studentId) {
            $query->where('student_id', $studentId);
        }

        $sessions = $query->orderByDesc('session_start')->paginate(50);

        // Calculate statistics
        $totalSessions = $query->count();
        $totalSeconds = $query->sum('duration_seconds');
        $uniqueStudents = $query->distinct('student_id')->count('student_id');

        // Statistics by page type
        $byPageType = SessionTimeTracking::select('page_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(duration_seconds) as total_seconds'))
            ->where('session_start', '>=', now()->startOfWeek())
            ->groupBy('page_type')
            ->get()
            ->keyBy('page_type');

        // Top students by time
        $topStudents = SessionTimeTracking::select('student_id', DB::raw('SUM(duration_seconds) as total_seconds'))
            ->where('session_start', '>=', now()->startOfWeek())
            ->groupBy('student_id')
            ->orderByDesc('total_seconds')
            ->limit(10)
            ->with('student.user')
            ->get();

        // Available formations
        $formations = Student::select('program')->distinct()->pluck('program');

        return view('admin.session-tracking.index', [
            'sessions' => $sessions,
            'stats' => [
                'total_sessions' => $totalSessions,
                'total_seconds' => $totalSeconds,
                'total_hours' => floor($totalSeconds / 3600),
                'total_minutes' => floor(($totalSeconds % 3600) / 60),
                'unique_students' => $uniqueStudents,
                'by_page_type' => $byPageType,
                'top_students' => $topStudents,
            ],
            'filters' => [
                'period' => $period,
                'formation' => $formation,
                'student_id' => $studentId,
            ],
            'formations' => $formations,
        ]);
    }

    /**
     * Detailed view for a specific student.
     */
    public function studentDetail(Request $request, int $studentId): View
    {
        $student = Student::with('user')->findOrFail($studentId);
        $period = $request->get('period', 'week');

        $query = SessionTimeTracking::forStudent($studentId)->with('seance');

        switch ($period) {
            case 'today':
                $query->whereDate('session_start', today());
                break;
            case 'week':
                $query->where('session_start', '>=', now()->startOfWeek());
                break;
            case 'month':
                $query->where('session_start', '>=', now()->startOfMonth());
                break;
        }

        $sessions = $query->orderByDesc('session_start')->paginate(30);

        // Calculate student statistics
        $totalSeconds = $query->sum('duration_seconds');
        $totalSessions = $query->count();

        // Time by seance
        $bySeance = SessionTimeTracking::forStudent($studentId)
            ->select('seance_id', DB::raw('COUNT(*) as count'), DB::raw('SUM(duration_seconds) as total_seconds'))
            ->where('session_start', '>=', now()->startOfWeek())
            ->whereNotNull('seance_id')
            ->groupBy('seance_id')
            ->with('seance')
            ->orderByDesc('total_seconds')
            ->get();

        // Time by page type
        $byPageType = SessionTimeTracking::forStudent($studentId)
            ->select('page_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(duration_seconds) as total_seconds'))
            ->where('session_start', '>=', now()->startOfWeek())
            ->groupBy('page_type')
            ->get()
            ->keyBy('page_type');

        return view('admin.session-tracking.student-detail', [
            'student' => $student,
            'sessions' => $sessions,
            'stats' => [
                'total_seconds' => $totalSeconds,
                'total_hours' => floor($totalSeconds / 3600),
                'total_minutes' => floor(($totalSeconds % 3600) / 60),
                'total_sessions' => $totalSessions,
                'by_seance' => $bySeance,
                'by_page_type' => $byPageType,
            ],
            'period' => $period,
        ]);
    }

    /**
     * Export session tracking data.
     */
    public function export(Request $request)
    {
        $period = $request->get('period', 'week');
        $formation = $request->get('formation', '');

        $query = SessionTimeTracking::with(['student', 'student.user', 'seance']);

        switch ($period) {
            case 'today':
                $query->whereDate('session_start', today());
                break;
            case 'week':
                $query->where('session_start', '>=', now()->startOfWeek());
                break;
            case 'month':
                $query->where('session_start', '>=', now()->startOfMonth());
                break;
        }

        if ($formation) {
            $query->whereHas('student', function ($q) use ($formation) {
                $q->where('program', $formation);
            });
        }

        $sessions = $query->orderByDesc('session_start')->get();

        $csv = fopen('php://temp', 'r+');
        fputcsv($csv, ['ID', 'Étudiant', 'Email', 'Formation', 'Page Type', 'Séance', 'Début', 'Fin', 'Durée (secondes)', 'Durée (formatée)', 'IP Address']);

        foreach ($sessions as $session) {
            fputcsv($csv, [
                $session->id,
                $session->student->first_name . ' ' . $session->student->last_name,
                $session->student->user->email ?? '',
                $session->student->program,
                $session->page_type,
                $session->seance?->title ?? 'N/A',
                $session->session_start?->format('Y-m-d H:i:s'),
                $session->session_end?->format('Y-m-d H:i:s'),
                $session->duration_seconds,
                gmdate('H:i:s', $session->duration_seconds),
                $session->ip_address,
            ]);
        }

        rewind($csv);
        $csvContent = stream_get_contents($csv);
        fclose($csv);

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="session-tracking-' . $period . '.csv"');
    }
}
