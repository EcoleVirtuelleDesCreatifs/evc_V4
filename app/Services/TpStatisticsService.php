<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TpStatisticsService
{
    /**
     * Récupère les statistiques complètes des TP pour un utilisateur
     */
    public function getUserTpStatistics(int $userId): array
    {
        try {
            $result = DB::selectOne("
                SELECT 
                    COUNT(DISTINCT p.id) as total_tp,
                    COUNT(DISTINCT CASE WHEN p.status = 'valide' THEN p.id END) as tp_valides,
                    COUNT(DISTINCT CASE WHEN p.status = 'en_cours' THEN p.id END) as tp_en_cours,
                    COUNT(pi.id) as total_files,
                    SUM(CASE WHEN pi.mime_type LIKE 'image/%' THEN 1 ELSE 0 END) as total_images,
                    SUM(CASE WHEN pi.mime_type = 'application/pdf' THEN 1 ELSE 0 END) as total_pdf,
                    ROUND(COALESCE(SUM(pi.file_size), 0) / 1024 / 1024, 2) as total_size_mb
                FROM projects p
                LEFT JOIN project_images pi ON p.id = pi.project_id
                WHERE p.user_id = ?
            ", [$userId]);

            return [
                'total_tp' => (int) ($result->total_tp ?? 0),
                'tp_valides' => (int) ($result->tp_valides ?? 0),
                'tp_en_cours' => (int) ($result->tp_en_cours ?? 0),
                'total_files' => (int) ($result->total_files ?? 0),
                'total_images' => (int) ($result->total_images ?? 0),
                'total_pdf' => (int) ($result->total_pdf ?? 0),
                'total_size_mb' => (float) ($result->total_size_mb ?? 0)
            ];

        } catch (\Exception $e) {
            Log::error('Erreur statistiques TP Service: ' . $e->getMessage(), [
                'user_id' => $userId,
                'file' => __FILE__,
                'line' => __LINE__
            ]);
            
            return $this->getEmptyStatistics();
        }
    }

    /**
     * Formate les statistiques pour l'affichage dans les vues
     */
    public function formatStatisticsForView(array $rawStats): array
    {
        $totalTPRequired = 20;
        $tpRealises = $rawStats['total_tp'];
        $progressionPourcentage = $tpRealises > 0 ? round(($tpRealises / $totalTPRequired) * 100, 1) : 0;

        return [
            'tp_realises' => $tpRealises,
            'tp_a_faire' => max(0, $totalTPRequired - $tpRealises),
            'tp_valides' => $rawStats['tp_valides'],
            'tp_total' => $totalTPRequired,
            'progression_pourcentage' => min(100, $progressionPourcentage),
            'total_files' => $rawStats['total_files'],
            'total_images' => $rawStats['total_images'],
            'total_pdf' => $rawStats['total_pdf'],
            'total_size_mb' => $rawStats['total_size_mb']
        ];
    }

    /**
     * Formate les statistiques de validation pour l'affichage
     */
    public function formatValidationStatsForView(array $rawStats): array
    {
        return [
            'tp_en_validation' => $rawStats['tp_en_cours'], // TP En Validation = statut 'en_cours'
            'tp_valides' => $rawStats['tp_valides'], // TP Validés = statut 'valide'
            'projets_en_validation' => 0, // Pour compatibilité future
            'total_en_validation' => $rawStats['tp_en_cours']
        ];
    }

    /**
     * Récupère tous les projets d'un utilisateur avec détails complets
     */
    public function getAllUserProjects(int $userId): array
    {
        try {
            Log::info('TpStatisticsService: Récupération projets pour user', ['user_id' => $userId]);
            
            $projects = DB::select("
                SELECT 
                    p.id,
                    p.title,
                    p.description,
                    p.category,
                    p.status,
                    p.created_at,
                    p.updated_at,
                    p.thumbnail_image,
                    p.link,
                    p.tags,
                    p.software_used,
                    COUNT(pi.id) as files_count,
                    SUM(CASE WHEN pi.mime_type LIKE 'image/%' THEN 1 ELSE 0 END) as images_count,
                    SUM(CASE WHEN pi.mime_type = 'application/pdf' THEN 1 ELSE 0 END) as pdf_count,
                    ROUND(COALESCE(SUM(pi.file_size), 0) / 1024 / 1024, 2) as total_size_mb
                FROM projects p
                LEFT JOIN project_images pi ON p.id = pi.project_id
                WHERE p.user_id = ?
                GROUP BY p.id, p.title, p.description, p.category, p.status, p.created_at, p.updated_at, p.thumbnail_image, p.link, p.tags, p.software_used
                ORDER BY p.created_at DESC
            ", [$userId]);

            Log::info('TpStatisticsService: Projets récupérés', [
                'user_id' => $userId,
                'count' => count($projects),
                'projects' => array_map(function($p) { return ['id' => $p->id, 'title' => $p->title]; }, $projects)
            ]);

            return $projects;

        } catch (\Exception $e) {
            Log::error('Erreur récupération tous les projets: ' . $e->getMessage(), [
                'user_id' => $userId,
                'file' => __FILE__,
                'line' => __LINE__,
                'trace' => $e->getTraceAsString()
            ]);
            
            return [];
        }
    }

    /**
     * Récupère les derniers TP d'un utilisateur
     */
    public function getRecentTps(int $userId, int $limit = 5): array
    {
        try {
            return DB::select("
                SELECT 
                    p.id,
                    p.title,
                    p.status,
                    p.created_at,
                    COUNT(pi.id) as files_count
                FROM projects p
                LEFT JOIN project_images pi ON p.id = pi.project_id
                WHERE p.user_id = ?
                GROUP BY p.id, p.title, p.status, p.created_at
                ORDER BY p.created_at DESC
                LIMIT ?
            ", [$userId, $limit]);

        } catch (\Exception $e) {
            Log::error('Erreur récupération TP récents: ' . $e->getMessage(), [
                'user_id' => $userId,
                'limit' => $limit
            ]);
            
            return [];
        }
    }

    /**
     * Retourne des statistiques vides en cas d'erreur
     */
    private function getEmptyStatistics(): array
    {
        return [
            'total_tp' => 0,
            'tp_valides' => 0,
            'tp_en_cours' => 0,
            'total_files' => 0,
            'total_images' => 0,
            'total_pdf' => 0,
            'total_size_mb' => 0
        ];
    }

    /**
     * Vérifie si l'utilisateur est éligible au certificat
     */
    public function isEligibleForCertificate(array $stats): bool
    {
        return $stats['tp_valides'] >= 10; // 10 TP validés requis
    }

    /**
     * Calcule le niveau de progression de l'utilisateur
     */
    public function getProgressionLevel(int $totalTp): string
    {
        if ($totalTp >= 20) return 'Expert';
        elseif ($totalTp >= 15) return 'Avancé';
        elseif ($totalTp >= 10) return 'Intermédiaire';
        elseif ($totalTp >= 5) return 'Débutant+';
        elseif ($totalTp >= 1) return 'Débutant';
        else return 'Nouveau';
    }
}
