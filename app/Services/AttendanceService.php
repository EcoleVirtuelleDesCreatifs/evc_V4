<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Seance;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AttendanceService
{
    /**
     * Statistiques d'assiduité d'un étudiant donné.
     */
    public function getStudentStats(Student $student): array
    {
        $seances = Seance::forFormation($student->program)
            ->orderByDesc('scheduled_at')
            ->get();

        $attendances = Attendance::where('student_id', $student->id)
            ->whereIn('seance_id', $seances->pluck('id'))
            ->get()
            ->keyBy('seance_id');

        $completedSeances = $seances->where('status', 'completed')->count();
        $present = $attendances->where('status', 'present')->count();
        $absent = $attendances->where('status', 'absent')->count();
        $late = $attendances->where('status', 'late')->count();
        $excused = $attendances->where('status', 'excused')->count();

        $rate = $this->calculateRate($present, $late, $completedSeances);

        $participationMinutes = $seances
            ->where('status', 'completed')
            ->filter(fn (Seance $s) =>
                in_array($attendances[$s->id]->status ?? '', ['present', 'late'])
            )
            ->sum('duration_minutes');

        return [
            'seances' => $seances,
            'attendances' => $attendances,
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

    /**
     * Dashboard admin de présences avec filtres.
     */
    public function getAdminDashboard(array $filters = []): array
    {
        $today = now()->startOfDay();

        $seanceQuery = Seance::query();

        if (!empty($filters['formation'])) {
            $seanceQuery->where('formation', $filters['formation']);
        }
        if (!empty($filters['session_id'])) {
            $seanceQuery->where('id', $filters['session_id']);
        }
        if (!empty($filters['formateur'])) {
            $seanceQuery->where('formateur', 'like', '%' . $filters['formateur'] . '%');
        }
        if (!empty($filters['date'])) {
            $seanceQuery->whereDate('scheduled_at', $filters['date']);
        }
        if (!empty($filters['from']) && !empty($filters['to'])) {
            $seanceQuery->whereBetween('scheduled_at', [$filters['from'], $filters['to']]);
        }
        if (!empty($filters['mode'])) {
            $seanceQuery->where('type', $filters['mode']);
        }
        if (!empty($filters['status'])) {
            $seanceQuery->where('status', $filters['status']);
        }

        $seancesToday = (clone $seanceQuery)
            ->whereDate('scheduled_at', $today)
            ->get();

        $seances = $seanceQuery->orderByDesc('scheduled_at')->get();

        $seanceIds = $seances->pluck('id')->all();
        $attendanceQuery = Attendance::query()->whereIn('seance_id', $seanceIds);

        if (!empty($filters['student_id'])) {
            $attendanceQuery->where('student_id', $filters['student_id']);
        }

        $attendances = $attendanceQuery->with(['student', 'student.user'])->get();

        $expectedStudents = Student::whereIn('program', $seances->pluck('formation')->unique())
            ->where('status', 'active')
            ->count();

        $present = $attendances->where('status', 'present')->count();
        $late = $attendances->where('status', 'late')->count();
        $absent = $attendances->where('status', 'absent')->count();

        $rate = $this->calculateRate($present, $late, $expectedStudents);

        return [
            'seances_today' => $seancesToday,
            'seances' => $seances,
            'attendances' => $attendances,
            'expected' => $expectedStudents,
            'present' => $present,
            'late' => $late,
            'absent' => $absent,
            'rate' => $rate,
            'filters' => $filters,
        ];
    }

    /**
     * Taux d'assiduité simple : (présents + retards) / attendus * 100.
     *
     * Peut être remplacé plus tard par :
     * durée de présence / durée totale des cours * 100
     */
    public function calculateRate(int $present, int $late, int $expected): float
    {
        return $expected > 0 ? round((($present + $late) / $expected) * 100, 1) : 0.0;
    }
}
