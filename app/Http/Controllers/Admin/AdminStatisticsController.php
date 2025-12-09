<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\StatisticsService;

/**
 * Contrôleur pour les statistiques d'administration
 * Architecture Laravel propre avec injection de dépendance
 */
class AdminStatisticsController extends Controller
{
    /**
     * Service de statistiques
     */
    private StatisticsService $statisticsService;

    /**
     * Constructeur avec injection de dépendance
     */
    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    /**
     * Afficher les statistiques des étudiants
     * Route: /evc/app/admin/statistiques/total-students
     *
     * @return \Illuminate\View\View
     */
    public function totalStudents()
    {
        try {
            // Récupérer les données dynamiques de la base de données
            $data = $this->getStudentsStatistics();

            Log::info('Statistiques étudiants chargées (dynamiques)', [
                'total_students' => $data['main_kpi']['total_students'] ?? 0,
                'formations_count' => count($data['formations'] ?? [])
            ]);

            return view('admin.statistics.total-students', compact('data'));

        } catch (\Exception $e) {
            Log::error('Erreur dans totalStudents: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            // Utiliser fallback en cas d'erreur
            $data = $this->getFallbackStudentsData();

            return view('admin.statistics.total-students', compact('data'))
                ->with('error', 'Données de démonstration affichées.');
        }
    }

    /**
     * Récupérer les statistiques des étudiants depuis la base de données
     *
     * @return array
     */
    private function getStudentsStatistics(): array
    {
        // Total étudiants
        $totalStudents = DB::table('students')->count();

        // Étudiants actifs
        $activeStudents = DB::table('students')->where('status', 'active')->count();

        // Nouveaux étudiants ce mois
        $newThisMonth = DB::table('students')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Croissance par rapport au mois dernier
        $lastMonthStudents = DB::table('students')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $growthPercentage = $lastMonthStudents > 0
            ? round((($newThisMonth - $lastMonthStudents) / $lastMonthStudents) * 100, 1)
            : 0;

        // Statistiques par formation
        $formations = DB::table('students')
            ->select('program as name', DB::raw('COUNT(*) as count'))
            ->whereNotNull('program')
            ->groupBy('program')
            ->orderBy('count', 'desc')
            ->get();

        $formationsData = [];
        $formationIcons = [
            'Design Graphique' => ['icon' => 'fas fa-paint-brush', 'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'],
            'Community Management' => ['icon' => 'fas fa-bullhorn', 'gradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)'],
            'Intelligence Artificielle' => ['icon' => 'fas fa-robot', 'gradient' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)'],
            'Gestion Informatique' => ['icon' => 'fas fa-server', 'gradient' => 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)'],
        ];

        foreach ($formations as $formation) {
            $icons = $formationIcons[$formation->name] ?? ['icon' => 'fas fa-graduation-cap', 'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'];

            $formationsData[] = [
                'name' => $formation->name,
                'count' => $formation->count,
                'slug' => Str::slug($formation->name),
                'icon' => $icons['icon'],
                'gradient' => $icons['gradient'],
                'percentage' => $totalStudents > 0 ? round(($formation->count / $totalStudents) * 100, 1) : 0
            ];
        }

        // Liste des étudiants récents avec progression
        $students = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->leftJoin('tp_assignments', function($join) {
                $join->on('students.id', '=', 'tp_assignments.student_id');
            })
            ->select(
                'students.id',
                'students.first_name as prenom',
                'students.last_name as nom',
                'users.email',
                'students.program as formation',
                'students.created_at',
                'students.profile_photo as photo',
                'students.status',
                DB::raw('COUNT(CASE WHEN tp_assignments.status = "validated" THEN 1 END) as validated_tps'),
                DB::raw('COUNT(tp_assignments.id) as total_tps')
            )
            ->groupBy('students.id', 'students.first_name', 'students.last_name', 'users.email', 'students.program', 'students.created_at', 'students.profile_photo', 'students.status')
            ->orderBy('students.created_at', 'desc')
            ->limit(50)
            ->get();

        $studentsData = [];
        foreach ($students as $student) {
            $progression = $student->total_tps > 0
                ? round(($student->validated_tps / $student->total_tps) * 100)
                : 0;

            $studentsData[] = [
                'id' => $student->id,
                'nom' => $student->nom ?? '',
                'prenom' => $student->prenom ?? '',
                'email' => $student->email ?? '',
                'formation' => $student->formation ?? 'Non défini',
                'created_at' => $student->created_at,
                'photo' => $student->photo,
                'progression' => $progression,
                'status' => $student->status === 'active' ? 'Actif' : 'Inactif'
            ];
        }

        return [
            'main_kpi' => [
                'total_students' => $totalStudents
            ],
            'growth' => [
                'percentage' => $growthPercentage
            ],
            'formations' => $formationsData,
            'students' => $studentsData,
            'total_students' => $totalStudents,
            'active_students' => $activeStudents,
            'new_this_month' => $newThisMonth
        ];
    }

    /**
     * Afficher les statistiques des formations
     * Route: /evc/app/admin/statistiques/total-formations
     *
     * @return \Illuminate\View\View
     */
    public function totalFormations()
    {
        try {
            $data = $this->getFormationsStatistics();
            return view('admin.statistics.total-formations', compact('data'));

        } catch (\Exception $e) {
            Log::error('Erreur formations: ' . $e->getMessage());
            $data = $this->getFallbackFormationsData();
            return view('admin.statistics.total-formations', compact('data'));
        }
    }

    /**
     * Afficher les statistiques des projets
     * Route: /evc/app/admin/statistiques/total-projects
     *
     * @return \Illuminate\View\View
     */
    public function totalProjects()
    {
        try {
            $data = $this->getProjectsStatistics();
            return view('admin.statistics.total-projects', compact('data'));

        } catch (\Exception $e) {
            Log::error('Erreur projets: ' . $e->getMessage());
            $data = $this->getFallbackProjectsData();
            return view('admin.statistics.total-projects', compact('data'));
        }
    }

    /**
     * Données de fallback pour les étudiants - STRUCTURE CORRIGÉE
     *
     * @return array
     */
    private function getFallbackStudentsData(): array
    {
        Log::info('Génération des données de fallback pour les étudiants');

        $data = [
            'main_kpi' => [
                'total_students' => 156
            ],
            'growth' => [
                'percentage' => 12.5
            ],
            'formations' => [
                [
                    'name' => 'Design Graphique',
                    'count' => 67,
                    'slug' => 'design-graphique',
                    'icon' => 'fas fa-paint-brush',
                    'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'percentage' => 42.9
                ],
                [
                    'name' => 'Community Management',
                    'count' => 43,
                    'slug' => 'community-management',
                    'icon' => 'fas fa-bullhorn',
                    'gradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                    'percentage' => 27.6
                ],
                [
                    'name' => 'Intelligence Artificielle',
                    'count' => 28,
                    'slug' => 'intelligence-artificielle',
                    'icon' => 'fas fa-robot',
                    'gradient' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                    'percentage' => 17.9
                ],
                [
                    'name' => 'Gestion Informatique',
                    'count' => 18,
                    'slug' => 'gestion-informatique',
                    'icon' => 'fas fa-server',
                    'gradient' => 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
                    'percentage' => 11.5
                ]
            ],
            'students' => [
                [
                    'id' => 1,
                    'nom' => 'Kouassi',
                    'prenom' => 'Aya',
                    'email' => 'aya.kouassi@evc.com',
                    'formation' => 'Design Graphique',
                    'created_at' => '2024-01-15',
                    'photo' => null,
                    'progression' => 85,
                    'status' => 'Actif'
                ],
                [
                    'id' => 2,
                    'nom' => 'Traoré',
                    'prenom' => 'Mamadou',
                    'email' => 'mamadou.traore@evc.com',
                    'formation' => 'Community Management',
                    'created_at' => '2024-02-03',
                    'photo' => null,
                    'progression' => 72,
                    'status' => 'Actif'
                ],
                [
                    'id' => 3,
                    'nom' => 'Diallo',
                    'prenom' => 'Fatou',
                    'email' => 'fatou.diallo@evc.com',
                    'formation' => 'Intelligence Artificielle',
                    'created_at' => '2024-01-28',
                    'photo' => null,
                    'progression' => 91,
                    'status' => 'Actif'
                ],
                [
                    'id' => 4,
                    'nom' => 'Bamba',
                    'prenom' => 'Seydou',
                    'email' => 'seydou.bamba@evc.com',
                    'formation' => 'Gestion Informatique',
                    'created_at' => '2024-02-20',
                    'photo' => null,
                    'progression' => 68,
                    'status' => 'Actif'
                ],
                [
                    'id' => 5,
                    'nom' => 'Koné',
                    'prenom' => 'Aminata',
                    'email' => 'aminata.kone@evc.com',
                    'formation' => 'Design Graphique',
                    'created_at' => '2024-01-10',
                    'photo' => null,
                    'progression' => 92,
                    'status' => 'Actif'
                ]
            ],
            'totals' => [
                'design_graphique' => 67,
                'community_management' => 43,
                'intelligence_artificielle' => 28,
                'gestion_informatique' => 18
            ],
            'total_students' => 156,
            'active_students' => 142,
            'new_this_month' => 18
        ];

        Log::info('Données de fallback générées', [
            'total_students' => $data['total_students'],
            'formations_count' => count($data['formations']),
            'students_count' => count($data['students'])
        ]);

        return $data;
    }

    /**
     * Récupérer les statistiques des formations (temporaire)
     *
     * @return array
     */
    private function getFormationsStatistics(): array
    {
        return [
            'main_kpi' => 4,
            'growth' => 0.0,
            'total_formations' => 4,
            'active_formations' => 4
        ];
    }

    /**
     * Récupérer les statistiques des projets (temporaire)
     *
     * @return array
     */
    private function getProjectsStatistics(): array
    {
        return [
            'main_kpi' => 89,
            'growth' => 15.2,
            'total_projects' => 89,
            'active_projects' => 67
        ];
    }

    /**
     * Fallback formations
     *
     * @return array
     */
    private function getFallbackFormationsData(): array
    {
        return $this->getFormationsStatistics();
    }

    /**
     * Fallback projets
     *
     * @return array
     */
    private function getFallbackProjectsData(): array
    {
        return $this->getProjectsStatistics();
    }
}
