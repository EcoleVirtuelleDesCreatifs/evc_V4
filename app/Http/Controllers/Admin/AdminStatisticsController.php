<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            // Utiliser directement les données de fallback du contrôleur pour le debug
            $data = $this->getFallbackStudentsData();
            
            Log::info('Statistiques étudiants chargées (fallback)', [
                'total_students' => $data['main_kpi']['total_students'] ?? 0,
                'formations_count' => count($data['formations'] ?? [])
            ]);
            
            return view('admin.statistics.total-students', compact('data'));
            
        } catch (\Exception $e) {
            Log::error('Erreur critique dans totalStudents: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Fallback minimal en cas d'erreur critique
            $data = [
                'main_kpi' => ['total_students' => 0],
                'growth' => ['percentage' => 0],
                'formations' => [],
                'students' => [],
                'total_students' => 0,
                'active_students' => 0,
                'new_this_month' => 0
            ];
            
            return view('admin.statistics.total-students', compact('data'))
                ->with('error', 'Erreur critique. Données minimales affichées.');
        }
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
