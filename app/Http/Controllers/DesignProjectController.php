<?php

namespace App\Http\Controllers;

use App\Services\DesignProjectService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Exception;

/**
 * Contrôleur pour la gestion des projets de design graphique
 *
 * Ce contrôleur utilise le service DesignProjectService pour toute la logique métier
 * et se concentre uniquement sur la gestion des requêtes HTTP et des réponses.
 */
class DesignProjectController extends Controller
{
    private DesignProjectService $designProjectService;

    /**
     * Constructeur - Injecte le service de gestion des projets
     */
    public function __construct(DesignProjectService $designProjectService)
    {
        $this->designProjectService = $designProjectService;
    }

    /**
     * Affiche la liste des projets de design graphique
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function index(Request $request)
    {
        // Vérifier l'authentification
        if (!$this->isAuthenticated()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $userId = (int) session('user_id');

        // Récupérer les filtres de la requête
        $filters = [
            'status' => $request->get('status'),
            'project_type' => $request->get('project_type'),
            'category' => $request->get('category'),
            'limit' => $request->get('limit', 50)
        ];

        // Nettoyer les filtres vides
        $filters = array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });

        try {
            // Récupérer les projets et statistiques
            $projects = $this->designProjectService->getUserProjects($userId, $filters);
            $stats = $this->designProjectService->getUserStats($userId);
            $formOptions = DesignProjectService::getFormOptions();

            return view('projets.index', compact('projects', 'stats', 'formOptions', 'filters'));

        } catch (\Exception $e) {
            Log::error('Erreur récupération projets: ' . $e->getMessage());

            return view('projets.index', [
                'projects' => [],
                'stats' => [],
                'formOptions' => DesignProjectService::getFormOptions(),
                'filters' => $filters,
                'error' => 'Erreur lors du chargement des projets.'
            ]);
        }
    }

    public function historique()
    {
        if (!$this->isAuthenticated()) {
            return $this->redirectToLogin('Vous devez être connecté pour accéder à cette page.');
        }

        $userId = (int) session('user_id');
        $userFormation = session('user_formation', 'design-graphique');

        try {
            $formationKey = strtolower((string) $userFormation);
            $isCommunityFormation = in_array($formationKey, ['community-management', 'community-manager'], true);
            $isDesignGraphiqueCmFormation = ($formationKey === 'design-graphique-cm');

            $allowedStatuses = ['pending', 'validated', 'rejected'];

            $designProjects = [];
            if (!$isCommunityFormation) {
                $projects = $this->designProjectService->getUserProjects($userId, [
                    'limit' => 500,
                ]);

                $stats = $this->designProjectService->getUserStats($userId);

                $designProjects = collect($projects)
                    ->filter(function ($project) use ($allowedStatuses) {
                        return in_array(($project['status'] ?? null), $allowedStatuses, true);
                    })
                    ->values()
                    ->all();

                $projects = $designProjects;
            }

            if (($isCommunityFormation || $isDesignGraphiqueCmFormation) && Schema::hasTable('projects')) {
                $rows = DB::table('projects')
                    ->where('user_id', $userId)
                    ->whereIn('status', ['en_cours', 'termine', 'valide', 'rejete'])
                    ->orderByDesc('created_at')
                    ->limit(500)
                    ->get();

                $statusMap = [
                    'en_cours' => 'pending',
                    'termine' => 'pending',
                    'valide' => 'validated',
                    'rejete' => 'rejected',
                ];

                $laravelProjects = $rows->map(function ($p) use ($statusMap) {
                    return [
                        'id' => (int) ($p->id ?? 0),
                        'title' => (string) ($p->title ?? ''),
                        'description' => (string) ($p->description ?? ''),
                        'category' => (string) (($p->category ?? '') ?: 'solo'),
                        'project_type' => (string) (($p->category ?? '') ?: '-'),
                        'status' => $statusMap[$p->status ?? 'en_cours'] ?? 'pending',
                        'created_at' => $p->created_at ?? null,
                        'files' => [],
                    ];
                })
                ->filter(function ($project) use ($allowedStatuses) {
                    return in_array(($project['status'] ?? null), $allowedStatuses, true);
                })
                ->values()
                ->all();

                if ($isDesignGraphiqueCmFormation) {
                    $projects = array_merge($designProjects, $laravelProjects);
                } else {
                    $projects = $laravelProjects;
                }

                usort($projects, function ($a, $b) {
                    $aTs = !empty($a['created_at']) ? strtotime((string) $a['created_at']) : 0;
                    $bTs = !empty($b['created_at']) ? strtotime((string) $b['created_at']) : 0;
                    return $bTs <=> $aTs;
                });

                $stats = $stats ?? [];
                $stats['total_projects'] = count($projects);
            }

            return view('projets.historique', [
                'projects' => $projects,
                'stats' => $stats,
                'userFormation' => $userFormation,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur chargement historique projets: ' . $e->getMessage());

            return redirect()->route($userFormation . '.projets.index')
                ->with('error', 'Erreur lors du chargement de l\'historique des projets.');
        }
    }

    /**
     * Traite la création d'un nouveau projet
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Vérifier l'authentification
        if (!$this->isAuthenticated()) {
            return $this->redirectToLogin('Vous devez être connecté pour effectuer cette action.');
        }

        $userId = (int) session('user_id');
        $userFormation = session('user_formation', 'design-graphique');

        try {
            // Créer le projet via le service
            $result = $this->designProjectService->createProject($request, $userId);

            if ($result['success']) {
                return redirect()->route($userFormation . '.projets.index')
                    ->with('success', $result['message'])
                    ->with('project_id', $result['project_id']);
            } else {
                return redirect()->back()
                    ->with('error', $result['error'])
                    ->withInput();
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            Log::error('Erreur création projet: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la création du projet. Veuillez réessayer.')
                ->withInput();
        }
    }

    /**
     * Affiche les détails d'un projet
     *
     * @param string $id
     * @return View|RedirectResponse
     */
    public function show(string $id)
    {
        if (!$this->isAuthenticated()) {
            return $this->redirectToLogin('Vous devez être connecté pour accéder à cette page.');
        }

        $userId = (int) session('user_id');
        $userFormation = session('user_formation', 'design-graphique');

        try {
            $project = $this->designProjectService->getUserProjectById($userId, (int) $id);

            if (!$project) {
                return redirect()->route($userFormation . '.projets.index')
                    ->with('error', 'Projet non trouvé.');
            }

            return view('projets.show', compact('project'));

        } catch (\Exception $e) {
            Log::error('Erreur affichage projet', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $userId,
                'project_id' => $id,
                'route' => request()->path(),
            ]);

            return redirect()->route($userFormation . '.projets.index')
                ->with('error', 'Erreur lors du chargement du projet.');
        }
    }

    /**
     * Affiche le formulaire d'édition d'un projet
     *
     * @param string $id
     * @return View|RedirectResponse
     */
    public function edit(string $id)
    {
        if (!$this->isAuthenticated()) {
            return $this->redirectToLogin('Vous devez être connecté pour accéder à cette page.');
        }

        $userId = (int) session('user_id');
        $userFormation = session('user_formation', 'design-graphique');

        try {
            // Récupérer tous les projets de l'utilisateur
            $projects = $this->designProjectService->getUserProjects($userId);

            // Trouver le projet spécifique
            $project = collect($projects)->firstWhere('id', (int) $id);

            if (!$project) {
                return redirect()->route($userFormation . '.projets.index')
                    ->with('error', 'Projet non trouvé.');
            }

            // Vérifier si le projet est validé (non modifiable)
            if ($project['status'] === 'validated' || $project['status'] === 'completed') {
                return redirect()->route($userFormation . '.projets.show', $id)
                    ->with('error', 'Ce projet est validé et ne peut plus être modifié.');
            }

            // Récupérer les options pour le formulaire
            $formOptions = DesignProjectService::getFormOptions();

            return view('projets.edit', compact('project', 'formOptions', 'userFormation'));

        } catch (\Exception $e) {
            Log::error('Erreur chargement formulaire édition: ' . $e->getMessage());

            return redirect()->route($userFormation . '.projets.index')
                ->with('error', 'Erreur lors du chargement du formulaire d\'édition.');
        }
    }

    /**
     * Met à jour un projet
     *
     * @param Request $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        if (!$this->isAuthenticated()) {
            return $this->redirectToLogin('Vous devez être connecté pour effectuer cette action.');
        }

        $userId = (int) session('user_id');
        $userFormation = session('user_formation', 'design-graphique');

        try {
            // Vérifier d'abord si le projet est validé (non modifiable)
            $projects = $this->designProjectService->getUserProjects($userId);
            $project = collect($projects)->firstWhere('id', (int) $id);

            if ($project && $project['status'] === 'validated') {
                return redirect()->route($userFormation . '.projets.show', $id)
                    ->with('error', 'Ce projet est validé et ne peut plus être modifié.');
            }

            // Utiliser le service pour mettre à jour le projet
            $result = $this->designProjectService->updateProject($request, (int) $id, $userId);

            if ($result['success']) {
                return redirect()->route($userFormation . '.projets.show', $id)
                    ->with('success', $result['message']);
            } else {
                return redirect()->back()
                    ->with('error', $result['error'])
                    ->withInput();
            }

        } catch (\Exception $e) {
            Log::error('Erreur mise à jour projet: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour du projet.')
                ->withInput();
        }
    }

    /**
     * Supprime un fichier d'un projet
     *
     * @param string $projectId
     * @param string $fileId
     * @return JsonResponse
     */
    public function removeFile(string $projectId, string $fileId): JsonResponse
    {
        if (!$this->isAuthenticated()) {
            return response()->json(['success' => false, 'error' => 'Non authentifié'], 401);
        }

        $userId = (int) session('user_id');

        try {
            $result = $this->designProjectService->removeProjectFile(
                (int) $fileId,
                (int) $projectId,
                $userId
            );

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Fichier supprimé avec succès.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Impossible de supprimer le fichier.'
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Erreur suppression fichier: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la suppression du fichier.'
            ], 500);
        }
    }

    /**
     * Met à jour le statut d'un projet
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function updateStatus(Request $request, string $id): JsonResponse
    {
        if (!$this->isAuthenticated()) {
            return response()->json(['success' => false, 'error' => 'Non authentifié'], 401);
        }

        $userId = (int) session('user_id');
        $status = $request->get('status');

        try {
            $success = $this->designProjectService->updateProjectStatus((int) $id, $status, $userId);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Statut du projet mis à jour avec succès.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Impossible de mettre à jour le statut du projet.'
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Erreur mise à jour statut: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la mise à jour du statut.'
            ], 500);
        }
    }

    /**
     * Supprime un projet
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        if (!$this->isAuthenticated()) {
            return $this->redirectToLogin('Vous devez être connecté pour effectuer cette action.');
        }

        $userId = (int) session('user_id');
        $userFormation = session('user_formation', 'design-graphique');

        try {
            // Vérifier d'abord si le projet est validé (non supprimable)
            $projects = $this->designProjectService->getUserProjects($userId);
            $project = collect($projects)->firstWhere('id', (int) $id);

            if ($project && $project['status'] === 'validated') {
                return redirect()->route($userFormation . '.projets.index')
                    ->with('error', 'Ce projet est validé et ne peut pas être supprimé.');
            }

            $success = $this->designProjectService->deleteProject((int) $id, $userId);

            if ($success) {
                return redirect()->route($userFormation . '.projets.index')
                    ->with('success', 'Projet supprimé avec succès.');
            } else {
                return redirect()->back()
                    ->with('error', 'Impossible de supprimer le projet.');
            }

        } catch (\Exception $e) {
            Log::error('Erreur suppression projet: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression du projet.');
        }
    }

    /**
     * Retourne les statistiques au format JSON (pour AJAX)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats()
    {
        if (!$this->isAuthenticated()) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $userId = (int) session('user_id');

        try {
            $stats = $this->designProjectService->getUserStats($userId);
            return response()->json($stats);

        } catch (\Exception $e) {
            Log::error('Erreur récupération stats: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }

    /**
     * Vérifie si l'utilisateur est authentifié
     *
     * @return bool
     */
    private function isAuthenticated(): bool
    {
        return session()->has('user_id') && !empty(session('user_id'));
    }

    /**
     * Redirige vers la page de connexion avec un message
     *
     * @param string $message
     * @return RedirectResponse
     */
    private function redirectToLogin(string $message): RedirectResponse
    {
        return redirect('/auth/evc/login')->with('error', $message);
    }

    /**
     * Affiche la liste des projets solo
     */
    public function soloProjects()
    {
        try {
            $userId = session('user_id');
            $userFormation = session('user_formation', 'design-graphique');
            Log::info('=== DEBUT soloProjects ===', ['user_id' => $userId]);

            if (!$userId) {
                Log::warning('Pas d\'user_id dans la session');
                return redirect()->route('login');
            }

            // Récupérer les projets solo
            Log::info('Appel getUserProjects avec category=solo');
            $projects = $this->designProjectService->getUserProjects($userId, ['category' => 'solo']);
            Log::info('Projets récupérés', ['count' => count($projects)]);

            Log::info('Appel getUserStats');
            $stats = $this->designProjectService->getUserStats($userId);
            Log::info('Stats récupérées', ['stats' => $stats]);

            return view('projets.solo', compact('projects', 'stats'));
        } catch (Exception $e) {
            Log::error('❌ ERREUR dans soloProjects', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route($userFormation . '.projets.index')
                ->with('error', 'Erreur lors du chargement des projets solo: ' . $e->getMessage());
        }
    }

    /**
     * Affiche la liste des projets groupe
     */
    public function groupProjects()
    {
        try {
            $userId = session('user_id');
            $userFormation = session('user_formation', 'design-graphique');
            if (!$userId) {
                return redirect()->route('login');
            }

            // Récupérer les projets groupe
            $projects = $this->designProjectService->getUserProjects($userId, ['category' => 'groupe']);
            $stats = $this->designProjectService->getUserStats($userId);

            return view('projets.groupe', compact('projects', 'stats'));
        } catch (Exception $e) {
            Log::error('Erreur dans groupProjects', ['error' => $e->getMessage()]);
            return redirect()->route($userFormation . '.projets.index')
                ->with('error', 'Erreur lors du chargement des projets groupe: ' . $e->getMessage());
        }
    }

    /**
     * Affiche la liste de tous les projets
     */
    public function allProjects()
    {
        try {
            $userId = session('user_id');
            $userFormation = session('user_formation', 'design-graphique');
            if (!$userId) {
                return redirect()->route('login');
            }

            // Récupérer tous les projets
            $projects = $this->designProjectService->getUserProjects($userId);
            $stats = $this->designProjectService->getUserStats($userId);

            return view('projets.tous', compact('projects', 'stats'));
        } catch (Exception $e) {
            return redirect()->route($userFormation . '.projets.index')
                ->with('error', 'Erreur lors du chargement de tous les projets.');
        }
    }
}
