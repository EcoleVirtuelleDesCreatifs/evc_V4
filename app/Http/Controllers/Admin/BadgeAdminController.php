<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ProfilePhotoHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use setasign\Fpdf\Fpdf;

class BadgeAdminController extends Controller
{
    public function active(Request $request)
    {
        $sort = (string) $request->query('sort', 'date');
        $dir = strtolower((string) $request->query('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $hasRegistrationDate = Schema::hasColumn('students', 'registration_date');
        $registrationDateExpr = $hasRegistrationDate
            ? 'students.registration_date'
            : 'NULL';
        $orderDateExpr = "COALESCE($registrationDateExpr, students.created_at)";

        $studentsQuery = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('students.status', 'active')
            ->select(
                'students.id',
                'students.user_id',
                'students.student_id',
                'students.first_name',
                'students.last_name',
                'students.country',
                'students.program',
                'students.specialization',
                'students.profile_photo',
                'students.created_at',
                'users.email',
                DB::raw("$orderDateExpr as registration_sort_date")
            )
            ->selectSub(function ($q) {
                $q->from('projects')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('projects.user_id', 'students.user_id');
            }, 'projects_count');

        if ($sort === 'projects') {
            $studentsQuery->orderBy('projects_count', $dir);
            $studentsQuery->orderByRaw("$orderDateExpr desc");
        } else {
            $studentsQuery->orderByRaw("$orderDateExpr $dir");
        }

        $students = $studentsQuery
            ->paginate(24)
            ->withQueryString();

        // Stats globales (tous les étudiants actifs)
        $stats = [
            'total' => (int) DB::table('students')->where('status', 'active')->count(),
            'design_graphique' => (int) DB::table('students')
                ->where('status', 'active')
                ->whereRaw("LOWER(COALESCE(program,'')) LIKE ?", ['%design%'])
                ->whereRaw("LOWER(COALESCE(program,'')) NOT LIKE ?", ['%community%'])
                ->count(),
            'community_management' => (int) DB::table('students')
                ->where('status', 'active')
                ->whereRaw("LOWER(COALESCE(program,'')) LIKE ?", ['%community%'])
                ->whereRaw("LOWER(COALESCE(program,'')) NOT LIKE ?", ['%design%'])
                ->count(),
            'design_graphique_cm' => (int) DB::table('students')
                ->where('status', 'active')
                ->whereRaw("LOWER(COALESCE(program,'')) LIKE ?", ['%design%'])
                ->whereRaw("LOWER(COALESCE(program,'')) LIKE ?", ['%community%'])
                ->count(),
            'total_projects' => (int) DB::table('projects')
                ->join('students', 'students.user_id', '=', 'projects.user_id')
                ->where('students.status', 'active')
                ->count(),
        ];

        // Nouveaux inscrits
        $hasRegistrationDateForStats = Schema::hasColumn('students', 'registration_date');
        $registrationDateField = $hasRegistrationDateForStats ? 'registration_date' : 'created_at';
        $todayStart = Carbon::now()->startOfDay();
        $monthStart = Carbon::now()->startOfMonth();
        $sinceSaturday = Carbon::now()->startOfWeek(Carbon::SATURDAY);

        $stats['new_today'] = (int) DB::table('students')
            ->where('status', 'active')
            ->where($registrationDateField, '>=', $todayStart)
            ->count();

        $stats['new_since_saturday'] = (int) DB::table('students')
            ->where('status', 'active')
            ->where($registrationDateField, '>=', $sinceSaturday)
            ->count();

        $stats['new_month'] = (int) DB::table('students')
            ->where('status', 'active')
            ->where($registrationDateField, '>=', $monthStart)
            ->count();

        $buildTopPerformers = function (Carbon $from) {
            $projectsSub = DB::table('projects')
                ->select('user_id', DB::raw('COUNT(*) as projects_validated'))
                ->where('updated_at', '>=', $from)
                ->where('status', 'valide')
                ->groupBy('user_id');

            $tpSub = DB::table('tp_assignments')
                ->select('user_id', DB::raw('COUNT(*) as tp_validated'))
                ->where('validated_at', '>=', $from)
                ->where('status', 'validated')
                ->groupBy('user_id');

            return DB::table('students')
                ->where('students.status', 'active')
                ->leftJoinSub($projectsSub, 'p', function ($join) {
                    $join->on('p.user_id', '=', 'students.user_id');
                })
                ->leftJoinSub($tpSub, 't', function ($join) {
                    $join->on('t.user_id', '=', 'students.user_id');
                })
                ->select(
                    'students.id',
                    'students.user_id',
                    'students.student_id',
                    'students.first_name',
                    'students.last_name',
                    'students.country',
                    'students.profile_photo',
                    'students.program',
                    'students.specialization',
                    DB::raw('COALESCE(p.projects_validated, 0) as projects_validated'),
                    DB::raw('COALESCE(t.tp_validated, 0) as tp_validated'),
                    DB::raw('(COALESCE(p.projects_validated, 0) + COALESCE(t.tp_validated, 0)) as total_score')
                )
                ->orderByDesc('total_score')
                ->orderByDesc('projects_validated')
                ->limit(5)
                ->get();
        };

        $buildTopPerformersByFormation = function (Carbon $from, string $formationKey) {
            $projectsSub = DB::table('projects')
                ->select('user_id', DB::raw('COUNT(*) as projects_validated'))
                ->where('updated_at', '>=', $from)
                ->where('status', 'valide')
                ->groupBy('user_id');

            $tpSub = DB::table('tp_assignments')
                ->select('user_id', DB::raw('COUNT(*) as tp_validated'))
                ->where('validated_at', '>=', $from)
                ->where('status', 'validated')
                ->groupBy('user_id');

            $formationExpr = "LOWER(CONCAT(COALESCE(students.program,''), ' ', COALESCE(students.specialization,'')))";

            $q = DB::table('students')
                ->where('students.status', 'active')
                ->leftJoinSub($projectsSub, 'p', function ($join) {
                    $join->on('p.user_id', '=', 'students.user_id');
                })
                ->leftJoinSub($tpSub, 't', function ($join) {
                    $join->on('t.user_id', '=', 'students.user_id');
                })
                ->select(
                    'students.id',
                    'students.user_id',
                    'students.student_id',
                    'students.first_name',
                    'students.last_name',
                    'students.country',
                    'students.profile_photo',
                    'students.program',
                    'students.specialization',
                    DB::raw('COALESCE(p.projects_validated, 0) as projects_validated'),
                    DB::raw('COALESCE(t.tp_validated, 0) as tp_validated'),
                    DB::raw('(COALESCE(p.projects_validated, 0) + COALESCE(t.tp_validated, 0)) as total_score')
                );

            if ($formationKey === 'dg') {
                $q->whereRaw("$formationExpr LIKE ?", ['%design%'])
                    ->whereRaw("$formationExpr NOT LIKE ?", ['%community%']);
            } elseif ($formationKey === 'cm') {
                $q->whereRaw("$formationExpr LIKE ?", ['%community%'])
                    ->whereRaw("$formationExpr NOT LIKE ?", ['%design%']);
            } elseif ($formationKey === 'dgcm') {
                $q->whereRaw("$formationExpr LIKE ?", ['%design%'])
                    ->whereRaw("$formationExpr LIKE ?", ['%community%']);
            }

            return $q
                ->orderByDesc('total_score')
                ->orderByDesc('projects_validated')
                ->limit(5)
                ->get();
        };

        $topPerformers = [
            'week' => $buildTopPerformers(Carbon::now()->startOfWeek()),
            'month' => $buildTopPerformers(Carbon::now()->startOfMonth()),
            'quarter' => $buildTopPerformers(Carbon::now()->firstOfQuarter()),
            'year' => $buildTopPerformers(Carbon::now()->startOfYear()),
        ];

        $topPerformersByFormation = [
            'dg' => [
                'week' => $buildTopPerformersByFormation(Carbon::now()->startOfWeek(), 'dg'),
                'month' => $buildTopPerformersByFormation(Carbon::now()->startOfMonth(), 'dg'),
                'quarter' => $buildTopPerformersByFormation(Carbon::now()->firstOfQuarter(), 'dg'),
                'year' => $buildTopPerformersByFormation(Carbon::now()->startOfYear(), 'dg'),
            ],
            'cm' => [
                'week' => $buildTopPerformersByFormation(Carbon::now()->startOfWeek(), 'cm'),
                'month' => $buildTopPerformersByFormation(Carbon::now()->startOfMonth(), 'cm'),
                'quarter' => $buildTopPerformersByFormation(Carbon::now()->firstOfQuarter(), 'cm'),
                'year' => $buildTopPerformersByFormation(Carbon::now()->startOfYear(), 'cm'),
            ],
            'dgcm' => [
                'week' => $buildTopPerformersByFormation(Carbon::now()->startOfWeek(), 'dgcm'),
                'month' => $buildTopPerformersByFormation(Carbon::now()->startOfMonth(), 'dgcm'),
                'quarter' => $buildTopPerformersByFormation(Carbon::now()->firstOfQuarter(), 'dgcm'),
                'year' => $buildTopPerformersByFormation(Carbon::now()->startOfYear(), 'dgcm'),
            ],
        ];

        return view('admin.badges.students', [
            'title' => 'Étudiants Actifs',
            'status' => 'active',
            'students' => $students,
            'stats' => $stats,
            'topPerformers' => $topPerformers,
            'topPerformersByFormation' => $topPerformersByFormation,
            'sort' => $sort,
            'dir' => $dir,
        ]);
    }

    public function topPerformers(Request $request)
    {
        $period = (string) $request->query('period', 'month');
        $allowedPeriods = ['week', 'month', 'quarter', 'year'];
        if (!in_array($period, $allowedPeriods, true)) {
            $period = 'month';
        }

        $from = match ($period) {
            'week' => Carbon::now()->startOfWeek(),
            'quarter' => Carbon::now()->firstOfQuarter(),
            'year' => Carbon::now()->startOfYear(),
            default => Carbon::now()->startOfMonth(),
        };

        $buildTopPerformers = function (Carbon $from) {
            $projectsSub = DB::table('projects')
                ->select('user_id', DB::raw('COUNT(*) as projects_validated'))
                ->where('updated_at', '>=', $from)
                ->where('status', 'valide')
                ->groupBy('user_id');

            $tpSub = DB::table('tp_assignments')
                ->select('user_id', DB::raw('COUNT(*) as tp_validated'))
                ->where('validated_at', '>=', $from)
                ->where('status', 'validated')
                ->groupBy('user_id');

            return DB::table('students')
                ->where('students.status', 'active')
                ->leftJoinSub($projectsSub, 'p', function ($join) {
                    $join->on('p.user_id', '=', 'students.user_id');
                })
                ->leftJoinSub($tpSub, 't', function ($join) {
                    $join->on('t.user_id', '=', 'students.user_id');
                })
                ->select(
                    'students.id',
                    'students.user_id',
                    'students.student_id',
                    'students.first_name',
                    'students.last_name',
                    'students.country',
                    'students.profile_photo',
                    'students.program',
                    'students.specialization',
                    DB::raw('COALESCE(p.projects_validated, 0) as projects_validated'),
                    DB::raw('COALESCE(t.tp_validated, 0) as tp_validated'),
                    DB::raw('(COALESCE(p.projects_validated, 0) + COALESCE(t.tp_validated, 0)) as total_score')
                )
                ->orderByDesc('total_score')
                ->orderByDesc('projects_validated')
                ->limit(5)
                ->get();
        };

        $buildTopPerformersByFormation = function (Carbon $from, string $formationKey) {
            $projectsSub = DB::table('projects')
                ->select('user_id', DB::raw('COUNT(*) as projects_validated'))
                ->where('updated_at', '>=', $from)
                ->where('status', 'valide')
                ->groupBy('user_id');

            $tpSub = DB::table('tp_assignments')
                ->select('user_id', DB::raw('COUNT(*) as tp_validated'))
                ->where('validated_at', '>=', $from)
                ->where('status', 'validated')
                ->groupBy('user_id');

            $formationExpr = "LOWER(CONCAT(COALESCE(students.program,''), ' ', COALESCE(students.specialization,'')))";

            $q = DB::table('students')
                ->where('students.status', 'active')
                ->leftJoinSub($projectsSub, 'p', function ($join) {
                    $join->on('p.user_id', '=', 'students.user_id');
                })
                ->leftJoinSub($tpSub, 't', function ($join) {
                    $join->on('t.user_id', '=', 'students.user_id');
                })
                ->select(
                    'students.id',
                    'students.user_id',
                    'students.student_id',
                    'students.first_name',
                    'students.last_name',
                    'students.country',
                    'students.profile_photo',
                    'students.program',
                    'students.specialization',
                    DB::raw('COALESCE(p.projects_validated, 0) as projects_validated'),
                    DB::raw('COALESCE(t.tp_validated, 0) as tp_validated'),
                    DB::raw('(COALESCE(p.projects_validated, 0) + COALESCE(t.tp_validated, 0)) as total_score')
                );

            if ($formationKey === 'dg') {
                $q->whereRaw("$formationExpr LIKE ?", ['%design%'])
                    ->whereRaw("$formationExpr NOT LIKE ?", ['%community%']);
            } elseif ($formationKey === 'cm') {
                $q->whereRaw("$formationExpr LIKE ?", ['%community%'])
                    ->whereRaw("$formationExpr NOT LIKE ?", ['%design%']);
            } elseif ($formationKey === 'dgcm') {
                $q->whereRaw("$formationExpr LIKE ?", ['%design%'])
                    ->whereRaw("$formationExpr LIKE ?", ['%community%']);
            }

            return $q
                ->orderByDesc('total_score')
                ->orderByDesc('projects_validated')
                ->limit(5)
                ->get();
        };

        $topByFormation = [
            'dg' => $buildTopPerformersByFormation($from, 'dg'),
            'cm' => $buildTopPerformersByFormation($from, 'cm'),
            'dgcm' => $buildTopPerformersByFormation($from, 'dgcm'),
        ];

        $topGlobal = $buildTopPerformers($from);

        foreach ($topGlobal as $p) {
            $p->photo_url = !empty($p->profile_photo)
                ? ProfilePhotoHelper::getUrl($p->profile_photo)
                : null;
        }

        foreach ($topByFormation as $k => $list) {
            foreach ($list as $p) {
                $p->photo_url = !empty($p->profile_photo)
                    ? ProfilePhotoHelper::getUrl($p->profile_photo)
                    : null;
            }
        }

        return view('admin.badges.top-performers', [
            'title' => 'Top 5 performers',
            'period' => $period,
            'topGlobal' => $topGlobal,
            'topByFormation' => $topByFormation,
        ]);
    }

    public function studentsList(Request $request)
    {
        $sort = (string) $request->query('sort', 'date');
        $dir = strtolower((string) $request->query('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $hasRegistrationDate = Schema::hasColumn('students', 'registration_date');
        $registrationDateExpr = $hasRegistrationDate
            ? 'students.registration_date'
            : 'NULL';
        $orderDateExpr = "COALESCE($registrationDateExpr, students.created_at)";

        $studentsQuery = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('students.status', 'active')
            ->select(
                'students.id',
                'students.user_id',
                'students.student_id',
                'students.first_name',
                'students.last_name',
                'students.country',
                'students.program',
                'students.specialization',
                'students.profile_photo',
                'students.created_at',
                'users.email',
                DB::raw("$orderDateExpr as registration_sort_date")
            )
            ->selectSub(function ($q) {
                $q->from('projects')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('projects.user_id', 'students.user_id');
            }, 'projects_count');

        if ($sort === 'projects') {
            $studentsQuery->orderBy('projects_count', $dir);
            $studentsQuery->orderByRaw("$orderDateExpr desc");
        } else {
            $studentsQuery->orderByRaw("$orderDateExpr $dir");
        }

        $students = $studentsQuery
            ->paginate(24)
            ->withQueryString();

        return view('admin.badges.students-list', [
            'title' => 'Liste des Étudiants',
            'students' => $students,
            'sort' => $sort,
            'dir' => $dir,
        ]);
    }

    public function inactive(Request $request)
    {
        $hasRegistrationDate = Schema::hasColumn('students', 'registration_date');
        $registrationDateExpr = $hasRegistrationDate
            ? 'students.registration_date'
            : 'NULL';

        // Date de base (inscription) : registration_date > students.created_at > users.created_at
        $baseDateExpr = "COALESCE($registrationDateExpr, students.created_at, users.created_at)";

        // Durée par défaut selon le programme (mois)
        $durationMonthsExpr = "CASE
            WHEN students.program IN ('Design Graphique','design_graphique','design-graphique') THEN 4
            WHEN students.program IN ('Community Management','community_management','community-manager','community-management') THEN 4
            WHEN students.program IN ('Design Graphique & Community Management','Design Graphique & Community Manager','design_graphique_community_management','design_graphique_community_manager','design-graphique-community-manager') THEN 7
            WHEN students.program IN ('Intelligence Artificielle','intelligence_artificielle','intelligence-artificielle') THEN 4
            WHEN students.program IN ('Gestion Informatique','gestion_informatique','gestion-informatique') THEN 4
            ELSE 4
        END";

        // Expiration calculée : base + durée
        $computedExpirationExpr = "DATE_ADD($baseDateExpr, INTERVAL ($durationMonthsExpr) MONTH)";

        // Expiration finale : max(expiration_date, computedExpiration)
        // (si expiration_date est null => computedExpiration)
        $finalExpirationExpr = "GREATEST(COALESCE(students.expiration_date, $computedExpirationExpr), $computedExpirationExpr)";

        $students = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('students.status', 'inactive')
            ->whereRaw("$finalExpirationExpr < NOW()")
            ->select(
                'students.id',
                'students.user_id',
                'students.first_name',
                'students.last_name',
                'students.country',
                'students.program',
                'students.profile_photo',
                'students.expiration_date',
                'users.email',
                DB::raw("$finalExpirationExpr as computed_expiration_date")
            )
            ->orderBy('students.updated_at', 'desc')
            ->paginate(24)
            ->withQueryString();

        return view('admin.badges.students', [
            'title' => 'Comptes expirés',
            'status' => 'inactive',
            'students' => $students,
        ]);
    }

    public function generate(int $id)
    {
        $student = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('students.id', $id)
            ->select(
                'students.id',
                'students.student_id',
                'students.first_name',
                'students.last_name',
                'students.country',
                'students.program',
                'students.profile_photo',
                'users.email'
            )
            ->first();

        abort_unless($student, 404);

        $fullName = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
        $formation = (string) ($student->program ?? '');
        $country = (string) ($student->country ?? '');
        $studentNumber = (string) ($student->student_id ?? '');

        $pdf = new Fpdf('P', 'mm', 'A4');
        $pdf->AddPage();

        $pdf->SetFont('Helvetica', 'B', 18);
        $pdf->Cell(0, 12, $this->toLatin('Badge Étudiant'), 0, 1, 'C');

        $pdf->Ln(4);
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->Cell(0, 8, $this->toLatin('Nom : ' . $fullName), 0, 1);

        if ($studentNumber !== '') {
            $pdf->Cell(0, 8, $this->toLatin('ID : ' . $studentNumber), 0, 1);
        }

        if ($formation !== '') {
            $pdf->Cell(0, 8, $this->toLatin('Formation : ' . $formation), 0, 1);
        }

        if ($country !== '') {
            $pdf->Cell(0, 8, $this->toLatin('Pays : ' . $country), 0, 1);
        }

        if (!empty($student->email)) {
            $pdf->Cell(0, 8, $this->toLatin('Email : ' . $student->email), 0, 1);
        }

        $photoUrl = null;
        if (!empty($student->profile_photo)) {
            $photoUrl = ProfilePhotoHelper::getUrl($student->profile_photo);
        }

        if ($photoUrl) {
            $localPath = $this->downloadTempFile($photoUrl);
            if ($localPath) {
                try {
                    $pdf->Image($localPath, 160, 40, 35, 35);
                } catch (\Throwable $e) {
                }
            }
        }

        $filename = 'badge_etudiant_' . ($student->id ?? 'unknown') . '.pdf';

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function toLatin(string $text): string
    {
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $text);
    }

    private function downloadTempFile(string $url): ?string
    {
        try {
            $contents = @file_get_contents($url);
            if ($contents === false) {
                return null;
            }

            $tmp = tempnam(sys_get_temp_dir(), 'evc_badge_');
            if (!$tmp) {
                return null;
            }

            file_put_contents($tmp, $contents);
            return $tmp;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
