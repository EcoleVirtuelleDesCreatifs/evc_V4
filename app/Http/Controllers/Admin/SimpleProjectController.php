<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SimpleProjectController extends Controller
{
    /**
     * 👁️ VOIR PROJET - Ultra Simple
     */
    public function view($id)
    {
        try {
            $project = Project::with(['user', 'images'])->findOrFail($id);

            $data = [
                'success' => true,
                'project' => [
                    'id' => $project->id,
                    'title' => $project->title ?? 'Sans titre',
                    'description' => $project->description ?? 'Aucune description',
                    'software_used' => $project->software_used ?? 'Non spécifié',
                    'status' => $project->status ?? 'pending',
                    'status_label' => $this->getStatusLabel($project->status),
                    'created_at' => $project->created_at->format('d/m/Y H:i'),
                    'user_name' => $project->user->name ?? 'Utilisateur inconnu',
                    'user_email' => $project->user->email ?? 'Email inconnu',
                    'images_count' => $project->images->count()
                ]
            ];

            Log::info("Project view success for ID: {$id}");
            return response()->json($data);

        } catch (\Exception $e) {
            Log::error("Project view error for ID {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Projet non trouvé'
            ], 404);
        }
    }

    /**
     * ✏️ ÉDITER PROJET - Ultra Simple
     */
    public function edit($id)
    {
        try {
            $project = Project::findOrFail($id);

            $data = [
                'success' => true,
                'project' => [
                    'id' => $project->id,
                    'title' => $project->title ?? '',
                    'description' => $project->description ?? '',
                    'software_used' => $project->software_used ?? '',
                    'status' => $project->status ?? 'pending'
                ]
            ];

            Log::info("Project edit data success for ID: {$id}");
            return response()->json($data);

        } catch (\Exception $e) {
            Log::error("Project edit error for ID {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Projet non trouvé'
            ], 404);
        }
    }

    /**
     * 💾 SAUVEGARDER PROJET - Ultra Simple
     */
    public function update(Request $request, $id)
    {
        try {
            $project = Project::findOrFail($id);

            $project->update([
                'title' => $request->input('title', $project->title),
                'description' => $request->input('description', $project->description),
                'software_used' => $request->input('software_used', $project->software_used),
                'status' => $request->input('status', $project->status)
            ]);

            Log::info("Project updated successfully for ID: {$id}");
            return response()->json([
                'success' => true,
                'message' => 'Projet mis à jour avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error("Project update error for ID {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }

    /**
     * ✅ VALIDER PROJET - Ultra Simple
     */
    public function validate($id)
    {
        try {
            $project = Project::findOrFail($id);
            $project->update(['status' => 'validated']);

            Log::info("Project validated successfully for ID: {$id}");
            return response()->json([
                'success' => true,
                'message' => 'Projet validé avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error("Project validation error for ID {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la validation'
            ], 500);
        }
    }

    /**
     * 🗑️ SUPPRIMER PROJET - Ultra Simple
     */
    public function delete($id)
    {
        try {
            $project = Project::findOrFail($id);
            $project->delete();

            Log::info("Project deleted successfully for ID: {$id}");
            return response()->json([
                'success' => true,
                'message' => 'Projet supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error("Project deletion error for ID {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la suppression'
            ], 500);
        }
    }

    /**
     * Helper pour les labels de statut
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'En cours de validation',
            'validated' => 'Validé',
            'rejected' => 'Rejeté',
            'draft' => 'Brouillon'
        ];

        return $labels[$status] ?? 'Inconnu';
    }
}
