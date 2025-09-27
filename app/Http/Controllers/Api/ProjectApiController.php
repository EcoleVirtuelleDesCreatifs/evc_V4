<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * API Controller pour la gestion des projets
 * Approche développeur senior avec logging et gestion d'erreurs robuste
 */
class ProjectApiController extends Controller
{
    /**
     * Récupérer les détails d'un projet
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            Log::info("API: Récupération du projet ID: {$id}", [
                'user_agent' => request()->userAgent(),
                'ip' => request()->ip(),
                'timestamp' => now()
            ]);

            // Vérification de l'authentification admin
            if (!$this->isAdminAuthenticated()) {
                Log::warning("API: Tentative d'accès non autorisée au projet {$id}");
                return response()->json([
                    'success' => false,
                    'error' => 'Accès non autorisé',
                    'code' => 'UNAUTHORIZED'
                ], 401);
            }

            // Récupération du projet avec relations
            $project = Project::with(['user', 'images'])
                ->where('id', $id)
                ->first();

            if (!$project) {
                Log::warning("API: Projet non trouvé - ID: {$id}");
                return response()->json([
                    'success' => false,
                    'error' => 'Projet non trouvé',
                    'code' => 'NOT_FOUND'
                ], 404);
            }

            // Formatage des données pour l'API
            $projectData = $this->formatProjectData($project);

            Log::info("API: Projet {$id} récupéré avec succès");

            return response()->json([
                'success' => true,
                'data' => $projectData,
                'meta' => [
                    'timestamp' => now()->toISOString(),
                    'version' => '1.0'
                ]
            ]);

        } catch (Exception $e) {
            Log::error("API: Erreur lors de la récupération du projet {$id}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur interne du serveur',
                'code' => 'INTERNAL_ERROR',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Formater les données du projet pour l'API
     * 
     * @param Project $project
     * @return array
     */
    private function formatProjectData(Project $project): array
    {
        return [
            'id' => $project->id,
            'title' => $project->title,
            'description' => $project->description,
            'category' => $project->category,
            'status' => $project->status,
            'status_label' => $this->getStatusLabel($project->status),
            'status_color' => $this->getStatusColor($project->status),
            'software_used' => $this->formatSoftwareUsed($project->software_used),
            'link' => $project->link,
            'tags' => $project->tags_array ?? [],
            'created_at' => $project->created_at->format('d/m/Y à H:i'),
            'updated_at' => $project->updated_at->format('d/m/Y à H:i'),
            'user' => [
                'id' => $project->user->id,
                'name' => $project->user->name ?? 'Utilisateur',
                'email' => $project->user->email
            ],
            'images' => $project->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'filename' => $image->filename,
                    'original_name' => $image->original_name,
                    'file_path' => $image->file_path,
                    'file_size' => $this->formatFileSize($image->file_size ?? 0),
                    'mime_type' => $image->mime_type,
                    'is_thumbnail' => $image->is_thumbnail ?? false,
                    'created_at' => $image->created_at->format('d/m/Y à H:i')
                ];
            })->toArray(),
            'stats' => [
                'images_count' => $project->images->count(),
                'total_size' => $this->formatFileSize($project->images->sum('file_size'))
            ]
        ];
    }

    /**
     * Formater les logiciels utilisés
     * 
     * @param mixed $softwareUsed
     * @return array
     */
    private function formatSoftwareUsed($softwareUsed): array
    {
        if (is_array($softwareUsed)) {
            return $softwareUsed;
        }
        
        if (is_string($softwareUsed) && !empty($softwareUsed)) {
            return array_map('trim', explode(',', $softwareUsed));
        }
        
        return [];
    }

    /**
     * Obtenir le libellé du statut
     * 
     * @param string $status
     * @return string
     */
    private function getStatusLabel(string $status): string
    {
        $labels = [
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'valide' => 'Validé',
            'rejete' => 'Rejeté'
        ];

        return $labels[$status] ?? 'Inconnu';
    }

    /**
     * Obtenir la couleur du statut
     * 
     * @param string $status
     * @return string
     */
    private function getStatusColor(string $status): string
    {
        $colors = [
            'en_cours' => 'warning',
            'termine' => 'info',
            'valide' => 'success',
            'rejete' => 'danger'
        ];

        return $colors[$status] ?? 'secondary';
    }

    /**
     * Formater la taille de fichier
     * 
     * @param int $bytes
     * @return string
     */
    private function formatFileSize(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        
        return $bytes . ' bytes';
    }

    /**
     * Vérifier l'authentification admin
     * 
     * @return bool
     */
    private function isAdminAuthenticated(): bool
    {
        // Implémentation de la vérification d'authentification admin
        // À adapter selon votre système d'authentification
        return session()->has('admin_logged_in') && session('admin_logged_in') === true;
    }
}
