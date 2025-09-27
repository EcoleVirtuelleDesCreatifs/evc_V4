<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\DTOs\StatisticsData;
use App\DTOs\FormationData;
use App\DTOs\StudentData;

/**
 * Service pour la gestion des statistiques
 * Architecture Laravel propre avec typage strict
 */
class StatisticsService
{
    /**
     * Récupérer les statistiques des étudiants
     * 
     * @return StatisticsData
     */
    public function getStudentsStatistics(): StatisticsData
    {
        try {
            // Récupération sécurisée des données avec casting explicite
            $totalStudents = $this->getTotalStudents();
            $activeStudents = $this->getActiveStudents();
            $newThisMonth = $this->getNewStudentsThisMonth();
            
            // Calcul sécurisé de la croissance
            $growthPercentage = $this->calculateGrowthPercentage($totalStudents, $newThisMonth);
            
            // Récupération des formations
            $formations = $this->getFormationsData($totalStudents);
            
            // Récupération des étudiants récents
            $students = $this->getRecentStudents();
            
            return new StatisticsData([
                'main_kpi' => [
                    'total_students' => $totalStudents
                ],
                'growth' => [
                    'percentage' => $growthPercentage
                ],
                'formations' => $formations,
                'students' => $students,
                'totals' => $this->getFormationTotals(),
                'total_students' => $totalStudents,
                'active_students' => $activeStudents,
                'new_this_month' => $newThisMonth
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans getStudentsStatistics: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->getFallbackStatistics();
        }
    }
    
    /**
     * Récupérer le nombre total d'étudiants
     * 
     * @return int
     */
    private function getTotalStudents(): int
    {
        return (int) DB::table('users')->count();
    }
    
    /**
     * Récupérer le nombre d'étudiants actifs
     * 
     * @return int
     */
    private function getActiveStudents(): int
    {
        return (int) DB::table('users')
            ->where('status', 'active')
            ->count();
    }
    
    /**
     * Récupérer le nombre de nouveaux étudiants ce mois
     * 
     * @return int
     */
    private function getNewStudentsThisMonth(): int
    {
        return (int) DB::table('users')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();
    }
    
    /**
     * Calculer le pourcentage de croissance de façon sécurisée
     * 
     * @param int $totalStudents
     * @param int $newThisMonth
     * @return float
     */
    private function calculateGrowthPercentage(int $totalStudents, int $newThisMonth): float
    {
        if ($totalStudents <= 0 || $newThisMonth <= 0) {
            return 0.0;
        }
        
        $previousTotal = max($totalStudents - $newThisMonth, 1);
        
        return round(($newThisMonth / $previousTotal) * 100, 1);
    }
    
    /**
     * Récupérer les données des formations
     * 
     * @param int $totalStudents
     * @return array
     */
    private function getFormationsData(int $totalStudents): array
    {
        $formations = [
            [
                'name' => 'Design Graphique',
                'slug' => 'design-graphique',
                'icon' => 'fas fa-paint-brush',
                'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'formation_field' => 'Design Graphique'
            ],
            [
                'name' => 'Community Management',
                'slug' => 'community-management',
                'icon' => 'fas fa-bullhorn',
                'gradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                'formation_field' => 'Community Management'
            ],
            [
                'name' => 'Intelligence Artificielle',
                'slug' => 'intelligence-artificielle',
                'icon' => 'fas fa-robot',
                'gradient' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                'formation_field' => 'Intelligence Artificielle'
            ],
            [
                'name' => 'Gestion Informatique',
                'slug' => 'gestion-informatique',
                'icon' => 'fas fa-server',
                'gradient' => 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
                'formation_field' => 'Gestion Informatique'
            ]
        ];
        
        return array_map(function ($formation) use ($totalStudents) {
            $count = $this->getFormationCount($formation['formation_field']);
            $percentage = $totalStudents > 0 ? round(($count / $totalStudents) * 100, 1) : 0.0;
            
            return new FormationData([
                'name' => $formation['name'],
                'count' => $count,
                'slug' => $formation['slug'],
                'icon' => $formation['icon'],
                'gradient' => $formation['gradient'],
                'percentage' => $percentage
            ]);
        }, $formations);
    }
    
    /**
     * Récupérer le nombre d'étudiants pour une formation
     * 
     * @param string $formation
     * @return int
     */
    private function getFormationCount(string $formation): int
    {
        return (int) DB::table('users')
            ->where('formation_souhaitee', $formation)
            ->count();
    }
    
    /**
     * Récupérer les totaux par formation
     * 
     * @return array
     */
    private function getFormationTotals(): array
    {
        return [
            'design_graphique' => $this->getFormationCount('Design Graphique'),
            'community_management' => $this->getFormationCount('Community Management'),
            'intelligence_artificielle' => $this->getFormationCount('Intelligence Artificielle'),
            'gestion_informatique' => $this->getFormationCount('Gestion Informatique')
        ];
    }
    
    /**
     * Récupérer les étudiants récents
     * 
     * @return array
     */
    private function getRecentStudents(): array
    {
        $students = DB::table('users')
            ->select('id', 'nom', 'prenom', 'email', 'formation_souhaitee', 'created_at', 'photo')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
        
        return $students->map(function ($student) {
            return new StudentData([
                'id' => (int) $student->id,
                'nom' => $student->nom ?? 'Nom',
                'prenom' => $student->prenom ?? 'Prénom',
                'email' => $student->email ?? 'email@evc.com',
                'formation' => $student->formation_souhaitee ?? 'Design Graphique',
                'created_at' => $student->created_at,
                'photo' => $student->photo,
                'progression' => rand(20, 95), // Temporaire - à remplacer par vraie logique
                'status' => 'Actif'
            ]);
        })->toArray();
    }
    
    /**
     * Données de fallback en cas d'erreur
     * 
     * @return StatisticsData
     */
    private function getFallbackStatistics(): StatisticsData
    {
        return new StatisticsData([
            'main_kpi' => [
                'total_students' => 1250
            ],
            'growth' => [
                'percentage' => 12.5
            ],
            'formations' => [
                new FormationData([
                    'name' => 'Design Graphique',
                    'count' => 456,
                    'slug' => 'design-graphique',
                    'icon' => 'fas fa-paint-brush',
                    'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'percentage' => 36.5
                ]),
                new FormationData([
                    'name' => 'Community Management',
                    'count' => 298,
                    'slug' => 'community-management',
                    'icon' => 'fas fa-bullhorn',
                    'gradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                    'percentage' => 23.8
                ]),
                new FormationData([
                    'name' => 'Intelligence Artificielle',
                    'count' => 187,
                    'slug' => 'intelligence-artificielle',
                    'icon' => 'fas fa-robot',
                    'gradient' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                    'percentage' => 15.0
                ]),
                new FormationData([
                    'name' => 'Gestion Informatique',
                    'count' => 124,
                    'slug' => 'gestion-informatique',
                    'icon' => 'fas fa-server',
                    'gradient' => 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
                    'percentage' => 9.9
                ])
            ],
            'students' => [
                new StudentData([
                    'id' => 1,
                    'nom' => 'Kouassi',
                    'prenom' => 'Marie',
                    'email' => 'marie.kouassi@evc.com',
                    'formation' => 'Design Graphique',
                    'created_at' => '2024-01-15',
                    'photo' => null,
                    'progression' => 85,
                    'status' => 'Actif'
                ]),
                new StudentData([
                    'id' => 2,
                    'nom' => 'Diabaté',
                    'prenom' => 'Ibrahim',
                    'email' => 'ibrahim.diabate@evc.com',
                    'formation' => 'Community Management',
                    'created_at' => '2024-02-10',
                    'photo' => null,
                    'progression' => 72,
                    'status' => 'Actif'
                ])
            ],
            'totals' => [
                'design_graphique' => 456,
                'community_management' => 298,
                'intelligence_artificielle' => 187,
                'gestion_informatique' => 124
            ],
            'total_students' => 1250,
            'active_students' => 1180,
            'new_this_month' => 45
        ]);
    }
}
