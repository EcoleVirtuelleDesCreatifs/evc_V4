<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CategoriesController extends Controller
{
    /**
     * Afficher la page des catégories avec statistiques
     */
    public function index(): View
    {
        try {
            // Récupérer les statistiques globales
            $globalStats = $this->getGlobalStats();
            
            // Récupérer les catégories avec leurs statistiques
            $categories = $this->getCategoriesWithStats();
            
            // Récupérer les données pour les graphiques
            $chartData = $this->getChartData();
            
            return view('admin.formations.categories', compact(
                'globalStats',
                'categories', 
                'chartData'
            ));
            
        } catch (\Exception $e) {
            Log::error('Categories page error: ' . $e->getMessage());
            
            // Données de fallback
            return view('admin.formations.categories', [
                'globalStats' => $this->getDefaultGlobalStats(),
                'categories' => $this->getDefaultCategories(),
                'chartData' => $this->getDefaultChartData()
            ]);
        }
    }

    /**
     * Afficher le formulaire de création d'une nouvelle catégorie
     */
    public function create(): View
    {
        return view('admin.formations.categories.create');
    }

    /**
     * Afficher les détails d'une catégorie
     */
    public function show($id)
    {
        try {
            $category = DB::table('categories')->where('id', $id)->first();
            
            if (!$category) {
                return redirect()->route('admin.formations.categories')
                    ->with('error', 'Catégorie non trouvée.');
            }

            return view('admin.formations.categories.show', compact('category'));

        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des détails de la catégorie: ' . $e->getMessage());
            return redirect()->route('admin.formations.categories')
                ->with('error', 'Erreur lors du chargement de la catégorie.');
        }
    }

    /**
     * Enregistrer une nouvelle catégorie
     */
    public function store(Request $request)
    {
        try {
            // Validation des données
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'slug' => 'required|string|max:255|unique:categories,slug',
                'description' => 'nullable|string|max:1000',
                'status' => 'required|in:active,inactive'
            ], [
                'name.required' => 'Le nom de la catégorie est obligatoire.',
                'name.unique' => 'Une catégorie avec ce nom existe déjà.',
                'slug.required' => 'Le slug est obligatoire.',
                'slug.unique' => 'Un slug avec cette valeur existe déjà.',
                'status.required' => 'Le statut est obligatoire.',
                'status.in' => 'Le statut doit être "active" ou "inactive".'
            ]);

            // Créer la catégorie dans la base de données
            $categoryId = DB::table('categories')->insertGetId([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'],
                'status' => $validated['status'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Log de l'action
            Log::info('Nouvelle catégorie créée', [
                'category_id' => $categoryId,
                'name' => $validated['name'],
                'admin_user' => session('admin_user.email', 'unknown')
            ]);

            // Redirection avec message de succès
            return redirect()->route('admin.formations.categories')
                ->with('success', 'La catégorie "' . $validated['name'] . '" a été créée avec succès.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Erreurs de validation
            return back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la catégorie: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'admin_user' => session('admin_user.email', 'unknown')
            ]);

            return back()
                ->with('error', 'Une erreur est survenue lors de la création de la catégorie. Veuillez réessayer.')
                ->withInput();
        }
    }

    /**
     * Afficher le formulaire d'édition d'une catégorie
     */
    public function edit($id)
    {
        try {
            $category = DB::table('categories')->where('id', $id)->first();
            
            if (!$category) {
                return redirect()->route('admin.formations.categories')
                    ->with('error', 'Catégorie non trouvée.');
            }

            return view('admin.formations.categories.edit', compact('category'));

        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement de l\'édition de catégorie: ' . $e->getMessage());
            return redirect()->route('admin.formations.categories')
                ->with('error', 'Erreur lors du chargement de la catégorie.');
        }
    }

    /**
     * Mettre à jour une catégorie
     */
    public function update(Request $request, $id)
    {
        try {
            // Vérifier que la catégorie existe
            $category = DB::table('categories')->where('id', $id)->first();
            if (!$category) {
                return redirect()->route('admin.formations.categories')
                    ->with('error', 'Catégorie non trouvée.');
            }

            // Validation des données
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $id,
                'slug' => 'required|string|max:255|unique:categories,slug,' . $id,
                'description' => 'nullable|string|max:1000',
                'status' => 'required|in:active,inactive'
            ]);

            // Mettre à jour la catégorie
            DB::table('categories')
                ->where('id', $id)
                ->update([
                    'name' => $validated['name'],
                    'slug' => $validated['slug'],
                    'description' => $validated['description'],
                    'status' => $validated['status'],
                    'updated_at' => now()
                ]);

            Log::info('Catégorie mise à jour', [
                'category_id' => $id,
                'name' => $validated['name'],
                'admin_user' => session('admin_user.email', 'unknown')
            ]);

            return redirect()->route('admin.formations.categories')
                ->with('success', 'La catégorie "' . $validated['name'] . '" a été mise à jour avec succès.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la catégorie: ' . $e->getMessage());
            return back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour.')
                ->withInput();
        }
    }

    /**
     * Supprimer une catégorie
     */
    public function destroy($id)
    {
        try {
            // Vérifier que la catégorie existe
            $category = DB::table('categories')->where('id', $id)->first();
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Catégorie non trouvée.'
                ], 404);
            }

            // Note: La table formations n'a pas de colonne de liaison avec les catégories
            // donc nous pouvons supprimer directement la catégorie sans vérifier les dépendances

            // Supprimer la catégorie
            DB::table('categories')->where('id', $id)->delete();

            Log::info('Catégorie supprimée', [
                'category_id' => $id,
                'category_name' => $category->name,
                'admin_user' => session('admin_user.email', 'unknown')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'La catégorie "' . $category->name . '" a été supprimée avec succès.'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la catégorie: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression.'
            ], 500);
        }
    }

    /**
     * Récupérer les statistiques globales
     */
    private function getGlobalStats(): array
    {
        try {
            $totalCategories = 4; // Nombre fixe pour l'instant
            
            // Compter les formations
            $totalFormations = DB::table('users')
                ->whereNotNull('formation_souhaitee')
                ->distinct('formation_souhaitee')
                ->count();
            
            // Compter les étudiants actifs
            $totalStudents = DB::table('users')
                ->where('role', 'user')
                ->count();
            
            // Calculer les étudiants actifs (abonnement non expiré)
            $activeStudents = DB::table('users')
                ->where('role', 'user')
                ->where(function($query) {
                    $query->where(function($subQuery) {
                        $subQuery->where('formation_souhaitee', 'Design Graphique')
                                 ->whereRaw('DATE_ADD(created_at, INTERVAL 4 MONTH) > NOW()');
                    })->orWhere(function($subQuery) {
                        $subQuery->where('formation_souhaitee', 'Community manager')
                                 ->whereRaw('DATE_ADD(created_at, INTERVAL 3 MONTH) > NOW()');
                    })->orWhere(function($subQuery) {
                        $subQuery->where('formation_souhaitee', 'Gestion informatique')
                                 ->whereRaw('DATE_ADD(created_at, INTERVAL 2 MONTH) > NOW()');
                    })->orWhere(function($subQuery) {
                        $subQuery->where('formation_souhaitee', 'Intelligence artificielle')
                                 ->whereRaw('DATE_ADD(created_at, INTERVAL 1 MONTH) > NOW()');
                    });
                })
                ->count();
            
            // Calculer le taux de satisfaction moyen
            $satisfactionRate = 94; // Valeur simulée pour l'instant
            
            return [
                'total_categories' => $totalCategories,
                'total_formations' => max($totalFormations, 4), // Minimum 4 formations
                'total_students' => $totalStudents,
                'active_students' => $activeStudents,
                'satisfaction_rate' => $satisfactionRate,
                'growth_rate' => 12.5 // Croissance simulée
            ];
            
        } catch (\Exception $e) {
            Log::error('Error getting global stats: ' . $e->getMessage());
            return $this->getDefaultGlobalStats();
        }
    }

    /**
     * Récupérer les catégories avec leurs statistiques
     */
    private function getCategoriesWithStats(): array
    {
        try {
            // Récupérer les catégories depuis la base de données
            $categoriesFromDB = DB::table('categories')
                ->select('id', 'name', 'slug', 'description', 'status', 'created_at', 'updated_at')
                ->orderBy('created_at', 'desc')
                ->get();

            $categories = [];
            foreach ($categoriesFromDB as $index => $category) {
                $categories[] = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'icon' => 'fas fa-layer-group', // Icône par défaut
                    'color' => '#667eea', // Couleur par défaut
                    'status' => $category->status,
                    'formations_count' => 0, // À implémenter selon la logique métier
                    'students_count' => 0, // À implémenter selon la logique métier
                    'satisfaction_rate' => 95,
                    'revenue' => 0,
                    'growth_rate' => 0,
                    'created_at' => $category->created_at
                ];
            }

            return $categories;
            
        } catch (\Exception $e) {
            Log::error('Error getting categories with stats: ' . $e->getMessage());
            return $this->getDefaultCategories();
        }
    }

    /**
     * Compter les formations par type
     */
    private function getFormationsCount(string $formationType): int
    {
        try {
            return DB::table('users')
                ->where('formation_souhaitee', $formationType)
                ->distinct('formation_souhaitee')
                ->count() > 0 ? 1 : 0; // Au moins 1 si des étudiants sont inscrits
        } catch (\Exception $e) {
            return 1; // Valeur par défaut
        }
    }

    /**
     * Compter les étudiants par formation
     */
    private function getStudentsCount(string $formationType): int
    {
        try {
            return DB::table('users')
                ->where('role', 'user')
                ->where('formation_souhaitee', $formationType)
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Récupérer les données pour les graphiques
     */
    private function getChartData(): array
    {
        try {
            // Données d'évolution mensuelle
            $monthlyData = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthlyData[] = [
                    'month' => $date->format('M Y'),
                    'students' => rand(20, 50) + ($i * 5), // Simulation croissance
                    'revenue' => rand(5000, 15000) + ($i * 2000)
                ];
            }

            // Répartition par catégorie
            $categoryDistribution = [
                ['name' => 'Design & Créativité', 'value' => $this->getStudentsCount('Design Graphique')],
                ['name' => 'Marketing Digital', 'value' => $this->getStudentsCount('Community manager')],
                ['name' => 'Technologie & IT', 'value' => $this->getStudentsCount('Gestion informatique')],
                ['name' => 'Intelligence Artificielle', 'value' => $this->getStudentsCount('Intelligence artificielle')]
            ];

            return [
                'monthly_evolution' => $monthlyData,
                'category_distribution' => $categoryDistribution
            ];
            
        } catch (\Exception $e) {
            Log::error('Error getting chart data: ' . $e->getMessage());
            return $this->getDefaultChartData();
        }
    }

    /**
     * Données par défaut en cas d'erreur
     */
    private function getDefaultGlobalStats(): array
    {
        return [
            'total_categories' => 4,
            'total_formations' => 4,
            'total_students' => 0,
            'active_students' => 0,
            'satisfaction_rate' => 94,
            'growth_rate' => 12.5
        ];
    }

    private function getDefaultCategories(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Design & Créativité',
                'slug' => 'design-creativite',
                'description' => 'Formations en design graphique, UX/UI et créativité digitale.',
                'icon' => 'fas fa-palette',
                'color' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'status' => 'active',
                'formations_count' => 1,
                'students_count' => 0,
                'satisfaction_rate' => 95,
                'revenue' => 0,
                'growth_rate' => 0
            ]
        ];
    }

    private function getDefaultChartData(): array
    {
        return [
            'monthly_evolution' => [],
            'category_distribution' => []
        ];
    }
}
