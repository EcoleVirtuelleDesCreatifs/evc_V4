<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class FormationStatisticsService
{
    /**
     * Configuration des formations avec leurs durées d'abonnement
     */
    private const FORMATIONS = [
        'design-graphique' => [
            'display_name' => 'Design graphique',
            'duration_months' => 4,
            'icon' => 'fas fa-palette',
            'variations' => ['design-graphique', 'design_graphique', 'Design Graphique', 'Design graphique']
        ],
        'community-management' => [
            'display_name' => 'Community manager',
            'duration_months' => 3,
            'icon' => 'fas fa-share-alt',
            'variations' => ['community-management', 'community_management', 'Community Management', 'Community Manager']
        ],
        'intelligence-artificielle' => [
            'display_name' => 'Intelligence Artificielle',
            'duration_months' => 1,
            'icon' => 'fas fa-robot',
            'variations' => ['intelligence-artificielle', 'intelligence_artificielle', 'Intelligence Artificielle', 'Intelligence artificielle']
        ],
        'gestion-informatique' => [
            'display_name' => 'Gestion informatique',
            'duration_months' => 2,
            'icon' => 'fas fa-server',
            'variations' => ['gestion-informatique', 'gestion_informatique', 'Gestion Informatique', 'Gestion informatique']
        ]
    ];

    /**
     * Obtenir les statistiques complètes par formation
     */
    public function getFormationStatistics(): Collection
    {
        $statistics = collect();

        foreach (self::FORMATIONS as $key => $config) {
            $stats = $this->calculateFormationStats($key, $config);
            $statistics->push((object)[
                'nom_formation' => $config['display_name'],
                'total_etudiants' => $stats['total'],
                'actifs' => $stats['active'],
                'nouveaux' => $stats['new_this_month'],
                'icon' => $config['icon'],
                'key' => $key
            ]);
        }

        return $statistics;
    }

    /**
     * Calculer les statistiques pour une formation spécifique
     */
    private function calculateFormationStats(string $formationKey, array $config): array
    {
        $variations = $config['variations'];
        $durationMonths = $config['duration_months'];

        // Total d'étudiants pour cette formation (toutes variations)
        $total = DB::table('users')
            ->whereIn('formation_souhaitee', $variations)
            ->count();

        // Étudiants avec abonnement actif (non expiré)
        $active = DB::table('users')
            ->whereIn('formation_souhaitee', $variations)
            ->whereRaw("DATE_ADD(created_at, INTERVAL {$durationMonths} MONTH) > NOW()")
            ->count();

        // Nouveaux étudiants ce mois
        $newThisMonth = DB::table('users')
            ->whereIn('formation_souhaitee', $variations)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->count();

        return [
            'total' => $total,
            'active' => $active,
            'new_this_month' => $newThisMonth,
            'active_percentage' => $total > 0 ? round(($active / $total) * 100) : 0
        ];
    }

    /**
     * Obtenir toutes les variations de noms de formation existantes dans la base
     */
    public function getExistingFormationNames(): Collection
    {
        return DB::table('users')
            ->select('formation_souhaitee')
            ->distinct()
            ->whereNotNull('formation_souhaitee')
            ->pluck('formation_souhaitee');
    }

    /**
     * Obtenir les statistiques globales
     */
    public function getGlobalStatistics(): array
    {
        $allVariations = collect(self::FORMATIONS)
            ->flatMap(fn($config) => $config['variations'])
            ->toArray();

        $totalStudents = DB::table('users')
            ->whereIn('formation_souhaitee', $allVariations)
            ->count();

        $activeStudents = 0;
        foreach (self::FORMATIONS as $key => $config) {
            $activeStudents += DB::table('users')
                ->whereIn('formation_souhaitee', $config['variations'])
                ->whereRaw("DATE_ADD(created_at, INTERVAL {$config['duration_months']} MONTH) > NOW()")
                ->count();
        }

        return [
            'total_students' => $totalStudents,
            'active_students' => $activeStudents,
            'inactive_students' => $totalStudents - $activeStudents,
            'active_percentage' => $totalStudents > 0 ? round(($activeStudents / $totalStudents) * 100) : 0
        ];
    }

    /**
     * Obtenir les icônes des formations
     */
    public function getFormationIcons(): array
    {
        return collect(self::FORMATIONS)
            ->mapWithKeys(fn($config, $key) => [$config['display_name'] => $config['icon']])
            ->toArray();
    }

    /**
     * Debug: Obtenir des informations de diagnostic
     */
    public function getDebugInfo(): array
    {
        $existingFormations = $this->getExistingFormationNames();
        $totalUsers = DB::table('users')->count();

        return [
            'total_users_in_db' => $totalUsers,
            'existing_formation_names' => $existingFormations->toArray(),
            'configured_formations' => array_keys(self::FORMATIONS),
            'formation_variations' => collect(self::FORMATIONS)
                ->mapWithKeys(fn($config, $key) => [$key => $config['variations']])
                ->toArray()
        ];
    }
}
