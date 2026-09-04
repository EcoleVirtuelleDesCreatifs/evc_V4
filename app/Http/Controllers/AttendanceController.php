<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Seance;
use Illuminate\Contracts\View\View;
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

        $seances = collect([]);
        $attendances = collect([]);

        if ($formation) {
            $seances = Seance::forFormation($formation)
                ->visible()
                ->orderByDesc('scheduled_at')
                ->get();

            $attendances = Attendance::where('student_id', $student->id)
                ->whereIn('seance_id', $seances->pluck('id'))
                ->get()
                ->keyBy('seance_id');
        }

        return view('seances.index', [
            'seances' => $seances,
            'attendances' => $attendances,
            'student' => $student,
            'user' => $user,
        ]);
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
