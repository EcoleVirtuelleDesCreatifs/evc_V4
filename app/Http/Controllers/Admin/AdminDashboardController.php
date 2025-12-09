<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use App\Models\Category;
use App\Models\LibraryCategory;
use App\Models\Formation;
use App\Models\Library;
use App\Models\AccountingTransaction;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    public function dashboard(): View
    {
        // Récupérer les historiques de connexion récents
        $recentLogins = DB::table('user_activities')
            ->where('activity_type', 'login')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->unique('user_id')
            ->take(8);

        $studentHistory = [];

        foreach ($recentLogins as $login) {
            $student = DB::table('students')
                ->where('user_id', $login->user_id)
                ->select('first_name', 'last_name', 'profile_photo', 'program')
                ->first();

            // Si c'est un étudiant (et pas un admin par exemple)
            if ($student) {
                // Récupérer la toute dernière activité (peut être déconnexion ou autre)
                $lastActivity = DB::table('user_activities')
                    ->where('user_id', $login->user_id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                $studentHistory[] = (object) [
                    'full_name' => $student->first_name . ' ' . $student->last_name,
                    'profile_photo' => $student->profile_photo,
                    'module' => $student->program,
                    'last_login' => $login->created_at,
                    'last_activity' => $lastActivity ? $lastActivity->created_at : null,
                    'last_activity_type' => $lastActivity ? $lastActivity->activity_type : null,
                ];
            }
        }

        // --- Statistiques Comptables ---
        $totalIncome = AccountingTransaction::where('type', 'income')->sum('amount');
        $totalExpenses = AccountingTransaction::where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpenses;

        $currentMonthStart = now()->startOfMonth();
        $incomeThisMonth = AccountingTransaction::where('type', 'income')
            ->where('date', '>=', $currentMonthStart)
            ->sum('amount');
        $expensesThisMonth = AccountingTransaction::where('type', 'expense')
            ->where('date', '>=', $currentMonthStart)
            ->sum('amount');

        return view('admin.dashboard', compact(
            'studentHistory',
            'totalIncome',
            'totalExpenses',
            'balance',
            'incomeThisMonth',
            'expensesThisMonth'
        ));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $students = User::orderBy('name')->get();
        return view('admin.formations.create', compact('categories', 'students'));
    }

    /**
     * Récupérer les étudiants d'un module spécifique
     */
    public function getStudentsByModule(Request $request)
    {
        $module = $request->input('module');

        if (!$module) {
            return response()->json([
                'success' => false,
                'message' => 'Module non spécifié'
            ], 400);
        }

        try {
            // Normaliser le nom du module : convertir les tirets en underscores
            // pour correspondre au format de la base de données
            // Ex: 'intelligence-artificielle' -> 'intelligence_artificielle'
            $moduleNormalized = str_replace('-', '_', $module);

            // Récupérer les étudiants du module depuis pre_registrations
            $students = DB::table('pre_registrations as pr')
                ->join('users as u', 'pr.email', '=', 'u.email')
                ->where('pr.choix_formation', $moduleNormalized)
                ->select('u.id', 'u.name', 'u.email')
                ->orderBy('u.name')
                ->get();

            return response()->json([
                'success' => true,
                'students' => $students
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des étudiants: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des étudiants'
            ], 500);
        }
    }

    public function index(): View
    {
        try {
            $formations = \App\Models\Formation::with('category')->latest()->get();

            // Calculer les statistiques globales
            $stats = [
                'total' => $formations->count(),
                'active' => $formations->where('status', 'active')->count(),
                'draft' => $formations->where('status', 'draft')->count(),
                'archived' => $formations->where('status', 'archived')->count(),
                'ce_mois' => $formations->where('created_at', '>=', now()->startOfMonth())->count(),
            ];

            // Calculer les statistiques par module principal de la formation
            $statsByModule = [
                'design-graphique' => 0,
                'community-management' => 0,
                'gestion-informatique' => 0,
                'intelligence-artificielle' => 0,
            ];

            foreach ($formations as $formation) {
                // Utiliser le module principal de la formation si disponible
                $module = $formation->module ?? $formation->category->module ?? null;

                if ($module) {
                    $moduleKey = strtolower(trim($module));

                    // Correspondance exacte ou partielle
                    if ($moduleKey === 'design-graphique' || str_contains($moduleKey, 'design')) {
                        $statsByModule['design-graphique']++;
                    } elseif ($moduleKey === 'community-management' || str_contains($moduleKey, 'community')) {
                        $statsByModule['community-management']++;
                    } elseif ($moduleKey === 'gestion-informatique' || str_contains($moduleKey, 'informatique') || str_contains($moduleKey, 'gestion')) {
                        $statsByModule['gestion-informatique']++;
                    } elseif ($moduleKey === 'intelligence-artificielle' || str_contains($moduleKey, 'intelligence') || str_contains($moduleKey, 'ia')) {
                        $statsByModule['intelligence-artificielle']++;
                    }
                }
            }

            // Calculer les statistiques par catégorie et module
            $statsByCategory = $formations->groupBy(function ($formation) {
                return $formation->category->module ?? 'Autre';
            })->map(function ($moduleFormations, $module) {
                return $moduleFormations->groupBy(function ($formation) {
                    return $formation->category->name ?? 'Sans catégorie';
                })->map(function ($categoryFormations, $categoryName) {
                    return (object)[
                        'category_name' => $categoryName,
                        'total' => $categoryFormations->count(),
                        'formations' => $categoryFormations
                    ];
                })->values();
            });

            return view('admin.formations.index', compact('formations', 'stats', 'statsByModule', 'statsByCategory'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des formations: ' . $e->getMessage());
            return view('admin.formations.index', [
                'formations' => collect(),
                'stats' => [
                    'total' => 0,
                    'active' => 0,
                    'draft' => 0,
                    'archived' => 0,
                    'ce_mois' => 0,
                ],
                'statsByModule' => [
                    'design-graphique' => 0,
                    'community-management' => 0,
                    'gestion-informatique' => 0,
                    'intelligence-artificielle' => 0,
                ],
                'statsByCategory' => collect()
            ])->with('error', 'Impossible de charger la liste des formations.');
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'vimeo_code' => 'nullable|string',
            'module' => 'required|string',
            'type' => 'required|in:en_ligne,presentiel',
            'destinataire' => 'required|in:etudiants-actifs,etudiants-specifiques',
            'is_featured' => 'required|boolean',
            'action' => 'required|in:draft,pending,published',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'nullable|integer|exists:users,id',
            'pdf_files' => 'nullable|array',
            'pdf_files.*' => 'file|mimes:pdf|max:10240', // 10 Mo max par fichier
            'chapters' => 'nullable|array',
            'chapters.*.title' => 'required|string|max:255',
            'chapters.*.description' => 'nullable|string',
            'chapters.*.order' => 'required|integer|min:1',
            'chapters.*.duration' => 'nullable|integer|min:1',
            'chapters.*.video_url' => 'nullable|string',
        ]);

        try {
            $formation = new Formation();

            $formation->name = $validatedData['name'];
            $formation->slug = Str::slug($validatedData['name']);
            $formation->category_id = $validatedData['category_id'];
            $formation->description = $validatedData['description'];
            $formation->is_featured = $validatedData['is_featured'];

            $statusMap = [
                'draft' => 'draft',
                'pending' => 'inactive',
                'published' => 'active',
            ];
            $formation->status = $statusMap[$validatedData['action']];

            $formation->modules = [$validatedData['module']];

            $formation->format = ($validatedData['type'] === 'en_ligne') ? 'online' : 'offline';
            $formation->student_restriction = ($validatedData['destinataire'] === 'etudiants-actifs') ? 'active_only' : 'all';

            if ($request->filled('vimeo_code')) {
                $formation->vimeo_code = $validatedData['vimeo_code'];
            }

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('formations', 'public');
                $formation->image_url = $path;
            }

            $formation->save();

            if ($request->filled('student_ids')) {
                // Filtrer les valeurs vides et invalides avant la synchronisation
                $studentIds = array_filter($validatedData['student_ids'], function ($id) {
                    return !empty($id) && is_numeric($id);
                });
                $formation->students()->sync($studentIds);
            }

            // Gérer les chapitres
            if ($request->filled('chapters')) {
                foreach ($request->input('chapters') as $chapterData) {
                    \App\Models\FormationChapter::create([
                        'formation_id' => $formation->id,
                        'title' => $chapterData['title'],
                        'description' => $chapterData['description'] ?? null,
                        'order' => $chapterData['order'],
                        'duration' => $chapterData['duration'] ?? null,
                        'video_url' => $chapterData['video_url'] ?? null,
                    ]);
                }
            }

            // Gérer l'upload des fichiers PDF
            if ($request->hasFile('pdf_files')) {
                $uploadedFiles = [];
                foreach ($request->file('pdf_files') as $file) {
                    if ($file->isValid()) {
                        // Générer un nom unique pour le fichier
                        $originalName = $file->getClientOriginalName();
                        $storedName = time() . '_' . Str::random(10) . '.pdf';

                        // Stocker le fichier dans public/uploads/formations/pdf
                        $path = $file->storeAs('uploads/formations/pdf', $storedName, 'public');

                        // Enregistrer les informations du fichier dans la base de données
                        $formationFile = new \App\Models\FormationFile();
                        $formationFile->formation_id = $formation->id;
                        $formationFile->original_name = $originalName;
                        $formationFile->stored_name = $storedName;
                        $formationFile->file_path = 'storage/' . $path;
                        $formationFile->file_size = $file->getSize();
                        $formationFile->mime_type = $file->getMimeType();
                        $formationFile->file_type = 'pdf';
                        $formationFile->save();

                        $uploadedFiles[] = $originalName;
                    }
                }

                $fileCount = count($uploadedFiles);
                $successMessage = 'Formation créée avec succès';
                if ($fileCount > 0) {
                    $successMessage .= ' avec ' . $fileCount . ' fichier(s) PDF joint(s).';
                }

                return redirect()->route('admin.formations.index')->with('success', $successMessage);
            }

            return redirect()->route('admin.formations.index')->with('success', 'Formation créée avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la formation: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la création de la formation: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Formation $formation)
    {
        try {
            $formation->delete();
            return redirect()->route('admin.formations.index')->with('success', 'Formation supprimée avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la formation: ' . $e->getMessage());
            return redirect()->route('admin.formations.index')->with('error', 'Une erreur est survenue lors de la suppression.');
        }
    }

    public function edit(Formation $formation)
    {
        $categories = Category::orderBy('name')->get();
        $students = User::orderBy('name')->get();
        $chapters = \App\Models\FormationChapter::where('formation_id', $formation->id)->orderBy('order')->get();
        return view('admin.formations.edit', compact('formation', 'categories', 'students', 'chapters'));
    }

    public function update(Request $request, Formation $formation)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'vimeo_code' => 'nullable|string',
            'module' => 'required|string',
            'type' => 'required|in:en_ligne,presentiel',
            'destinataire' => 'required|in:etudiants-actifs,etudiants-specifiques',
            'is_featured' => 'required|boolean',
            'action' => 'required|in:draft,pending,published',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'nullable|integer|exists:users,id',
            'chapters' => 'nullable|array',
            'chapters.*.id' => 'nullable|integer|exists:formation_chapters,id',
            'chapters.*.title' => 'required|string|max:255',
            'chapters.*.description' => 'nullable|string',
            'chapters.*.order' => 'required|integer|min:1',
            'chapters.*.duration' => 'nullable|integer|min:1',
            'chapters.*.video_url' => 'nullable|string',
        ]);

        try {
            $formation->name = $validatedData['name'];
            $formation->slug = Str::slug($validatedData['name']);
            $formation->category_id = $validatedData['category_id'];
            $formation->description = $validatedData['description'];
            $formation->is_featured = $validatedData['is_featured'];

            $statusMap = [
                'draft' => 'draft',
                'pending' => 'inactive',
                'published' => 'active',
            ];
            $formation->status = $statusMap[$validatedData['action']];

            $formation->modules = [$validatedData['module']];

            $formation->format = ($validatedData['type'] === 'en_ligne') ? 'online' : 'offline';
            $formation->student_restriction = ($validatedData['destinataire'] === 'etudiants-actifs') ? 'active_only' : 'all';

            if ($request->filled('vimeo_code')) {
                $formation->vimeo_code = $validatedData['vimeo_code'];
            }

            if ($request->hasFile('image')) {
                if ($formation->image_url) {
                    Storage::disk('public')->delete($formation->image_url);
                }
                $path = $request->file('image')->store('formations', 'public');
                $formation->image_url = $path;
            }

            $formation->save();

            if ($request->filled('student_ids')) {
                // Filtrer les valeurs vides et invalides avant la synchronisation
                $studentIds = array_filter($validatedData['student_ids'], function ($id) {
                    return !empty($id) && is_numeric($id);
                });
                $formation->students()->sync($studentIds);
            } else {
                $formation->students()->detach();
            }

            // Gérer les chapitres
            if ($request->filled('chapters')) {
                $submittedChapterIds = [];

                foreach ($request->input('chapters') as $chapterData) {
                    if (!empty($chapterData['id'])) {
                        // Mise à jour d'un chapitre existant
                        $chapter = \App\Models\FormationChapter::find($chapterData['id']);
                        if ($chapter && $chapter->formation_id == $formation->id) {
                            $chapter->update([
                                'title' => $chapterData['title'],
                                'description' => $chapterData['description'] ?? null,
                                'order' => $chapterData['order'],
                                'duration' => $chapterData['duration'] ?? null,
                                'video_url' => $chapterData['video_url'] ?? null,
                            ]);
                            $submittedChapterIds[] = $chapter->id;
                        }
                    } else {
                        // Création d'un nouveau chapitre
                        $newChapter = \App\Models\FormationChapter::create([
                            'formation_id' => $formation->id,
                            'title' => $chapterData['title'],
                            'description' => $chapterData['description'] ?? null,
                            'order' => $chapterData['order'],
                            'duration' => $chapterData['duration'] ?? null,
                            'video_url' => $chapterData['video_url'] ?? null,
                        ]);
                        $submittedChapterIds[] = $newChapter->id;
                    }
                }

                // Supprimer les chapitres qui ne sont plus dans le formulaire
                \App\Models\FormationChapter::where('formation_id', $formation->id)
                    ->whereNotIn('id', $submittedChapterIds)
                    ->delete();
            } else {
                // Si aucun chapitre n'est soumis, supprimer tous les chapitres existants
                \App\Models\FormationChapter::where('formation_id', $formation->id)->delete();
            }

            return redirect()->route('admin.formations.index')->with('success', 'Formation mise à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la formation: ' . $e->getMessage());
            return back()->with('error', 'Erreur de mise à jour: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Formation $formation)
    {
        // Récupérer les fichiers PDF associés à cette formation
        $files = \App\Models\FormationFile::where('formation_id', $formation->id)->get();

        return view('admin.formations.show', compact('formation', 'files'));
    }

    public function toggleStatus(Formation $formation)
    {
        try {
            $formation->status = ($formation->status === 'active') ? 'inactive' : 'active';
            $formation->save();
            return redirect()->route('admin.formations.index')->with('success', 'Statut de la formation mis à jour.');
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut: ' . $e->getMessage());
            return redirect()->route('admin.formations.index')->with('error', 'Une erreur est survenue.');
        }
    }

    public function categoriesIndex()
    {
        try {
            // Vérifier si la table categories existe
            if (!Schema::hasTable('categories')) {
                // Utiliser des données de fallback
                return $this->categoriesIndexFallback();
            }

            // Charger les catégories avec leurs statistiques
            $categoriesData = Category::withCount('formations')->get();

            $categories = $categoriesData->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'module' => $category->module,
                    'status' => $category->status ?? 'active',
                    'formations_count' => $category->formations_count ?? 0,
                    'students_count' => 0, // À calculer si nécessaire
                    'created_at' => $category->created_at,
                ];
            })->toArray();

            // Calculer les statistiques globales
            $stats = [
                'total_categories' => $categoriesData->count(),
                'total_formations' => $categoriesData->sum('formations_count'),
                'categories_actives' => $categoriesData->where('status', 'active')->count(),
                'categories_sans_formation' => $categoriesData->where('formations_count', 0)->count(),
            ];

            // Calculer les statistiques par module
            $statsByModule = [
                'design-graphique' => 0,
                'community-management' => 0,
                'gestion-informatique' => 0,
                'intelligence-artificielle' => 0,
            ];

            foreach ($categoriesData as $category) {
                if ($category->module) {
                    $module = strtolower(trim($category->module));

                    if ($module === 'design-graphique' || str_contains($module, 'design')) {
                        $statsByModule['design-graphique'] += $category->formations_count;
                    } elseif ($module === 'community-management' || str_contains($module, 'community')) {
                        $statsByModule['community-management'] += $category->formations_count;
                    } elseif ($module === 'gestion-informatique' || str_contains($module, 'informatique') || str_contains($module, 'gestion')) {
                        $statsByModule['gestion-informatique'] += $category->formations_count;
                    } elseif ($module === 'intelligence-artificielle' || str_contains($module, 'intelligence') || str_contains($module, 'ia')) {
                        $statsByModule['intelligence-artificielle'] += $category->formations_count;
                    }
                }
            }

            return view('admin.formations.categories', compact('categories', 'stats', 'statsByModule'));

        } catch (\Exception $e) {
            Log::error('Erreur dans categoriesIndex: ' . $e->getMessage());
            return $this->categoriesIndexFallback();
        }
    }

    /**
     * Version fallback avec données de démonstration
     */
    private function categoriesIndexFallback()
    {
        $categories = [
            [
                'id' => 1,
                'name' => 'Design & Création Visuelle',
                'slug' => 'design-creation-visuelle',
                'description' => 'Catégories liées au design graphique, création visuelle et identité de marque',
                'module' => 'design-graphique',
                'status' => 'active',
                'formations_count' => 8,
                'students_count' => 45,
                'created_at' => now()->subMonths(6),
            ],
            [
                'id' => 2,
                'name' => 'Communication Digitale',
                'slug' => 'communication-digitale',
                'description' => 'Catégories liées au community management et stratégie digitale',
                'module' => 'community-management',
                'status' => 'active',
                'formations_count' => 6,
                'students_count' => 32,
                'created_at' => now()->subMonths(5),
            ],
            [
                'id' => 3,
                'name' => 'Technologies de l\'Information',
                'slug' => 'technologies-information',
                'description' => 'Catégories liées à la gestion informatique et systèmes d\'information',
                'module' => 'gestion-informatique',
                'status' => 'active',
                'formations_count' => 5,
                'students_count' => 28,
                'created_at' => now()->subMonths(4),
            ],
            [
                'id' => 4,
                'name' => 'Intelligence Artificielle & Data',
                'slug' => 'ia-data',
                'description' => 'Catégories liées à l\'IA, machine learning et analyse de données',
                'module' => 'intelligence-artificielle',
                'status' => 'active',
                'formations_count' => 4,
                'students_count' => 18,
                'created_at' => now()->subMonths(3),
            ],
        ];

        $stats = [
            'total_categories' => 4,
            'total_formations' => 23,
            'categories_actives' => 4,
            'categories_sans_formation' => 0,
        ];

        $statsByModule = [
            'design-graphique' => 8,
            'community-management' => 6,
            'gestion-informatique' => 5,
            'intelligence-artificielle' => 4,
        ];

        return view('admin.formations.categories', compact('categories', 'stats', 'statsByModule'))
            ->with('info', 'Données de démonstration affichées.');
    }

    public function createCategory()
    {
        return view('admin.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        $validatedData['slug'] = Str::slug($validatedData['name']);
        $validatedData['status'] = 'active';

        Category::create($validatedData);

        return redirect()->route('admin.formations.categories.index')->with('success', 'Catégorie créée avec succès.');
    }

    public function editCategory($id): View
    {
        $category = Category::findOrFail($id);
        return view('admin.formations.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, $id): RedirectResponse
    {
        $category = Category::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        $validatedData['slug'] = Str::slug($validatedData['name']);

        $category->update($validatedData);

        return redirect()->route('admin.formations.categories.index')->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function deleteCategory($id)
    {
        try {
            $category = Category::findOrFail($id);

            // Vérifier si la catégorie a des formations associées
            $formationsCount = $category->formations()->count();

            if ($formationsCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Impossible de supprimer cette catégorie car elle contient {$formationsCount} formation(s)."
                ], 400);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Catégorie supprimée avec succès.'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la catégorie: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression.'
            ], 500);
        }
    }

    public function studentsDesignGraphique()
    {
        $students = User::whereHas('formations.category', function ($query) {
            $query->where('name', 'Design Graphique');
        })->get();
        return view('admin.etudiants.design-graphique', compact('students'));
    }

    public function studentsCommunityManagement()
    {
        $students = User::whereHas('formations.category', function ($query) {
            $query->where('name', 'Community Management');
        })->get();
        return view('admin.etudiants.community-management', compact('students'));
    }

    public function studentsDesignGraphiqueCommunityManager()
    {
        $students = User::whereHas('formations.category', function ($query) {
            $query->where('name', 'Design Graphique & Community Manager');
        })->get();
        return view('admin.etudiants.design-graphique-community-manager', compact('students'));
    }

    public function bibliothequeCategories()
    {
        $categories = LibraryCategory::all();
        return view('admin.bibliotheque.categories.index', compact('categories'));
    }

    public function bibliotheque()
    {
        $items = Library::with('libraryCategory')->latest()->get();

        // Définir les espaces avec leurs informations complètes
        $espacesInfo = [
            [
                'slug' => 'design-graphique',
                'name' => 'Design Graphique',
                'icon' => 'fa-palette',
                'color' => 'primary'
            ],
            [
                'slug' => 'community-management',
                'name' => 'Community Management',
                'icon' => 'fa-bullhorn',
                'color' => 'info'
            ],
            [
                'slug' => 'gestion-informatique',
                'name' => 'Gestion Informatique',
                'icon' => 'fa-laptop-code',
                'color' => 'warning'
            ],
            [
                'slug' => 'intelligence-artificielle',
                'name' => 'Intelligence Artificielle',
                'icon' => 'fa-brain',
                'color' => 'success'
            ]
        ];

        // Calculer les statistiques par espace
        $parEspace = [];
        foreach ($espacesInfo as $espaceInfo) {
            $espace = $espaceInfo['slug'];
            $count = $items->filter(function ($item) use ($espace) {
                $itemSpaces = is_string($item->space) ? json_decode($item->space, true) : $item->space;
                if (is_array($itemSpaces)) {
                    return in_array($espace, $itemSpaces) || in_array('tous', $itemSpaces);
                }
                return $item->space === $espace || $item->space === 'tous';
            })->count();

            $parEspace[] = array_merge($espaceInfo, ['count' => $count]);
        }

        // Calculer les statistiques par catégorie
        $parCategorie = [];
        $categoriesGrouped = $items->groupBy('library_category_id');

        foreach ($categoriesGrouped as $categoryId => $categoryItems) {
            $category = $categoryItems->first()->libraryCategory ?? null;
            if ($category) {
                $parCategorie[] = [
                    'id' => $categoryId,
                    'name' => $category->name ?? 'Sans catégorie',
                    'slug' => $category->slug ?? 'sans-categorie',
                    'count' => $categoryItems->count()
                ];
            }
        }

        // Calculer les statistiques
        $stats = [
            'total_documents' => $items->count(),
            'documents_actifs' => $items->where('status', 'active')->count(),
            'total_downloads' => $items->sum('downloads') ?? 0,
            'documents_ce_mois' => $items->where('created_at', '>=', now()->startOfMonth())->count(),
            'par_espace' => $parEspace,
            'par_categorie' => collect($parCategorie)
        ];

        return view('admin.bibliotheque.index', compact('items', 'stats'));
    }

    public function programmes()
    {
        $programmes = DB::table('programmes')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculer les statistiques
        $stats = [
            'total' => $programmes->count(),
            'design_graphique' => $programmes->where('formation', 'Design Graphique')->count(),
            'community_management' => $programmes->where('formation', 'Community Management')->count(),
            'gestion_informatique' => $programmes->where('formation', 'Gestion Informatique')->count(),
            'intelligence_artificielle' => $programmes->where('formation', 'Intelligence Artificielle')->count(),
            'tous' => $programmes->where('formation', 'Toutes')->count(),
            'ce_mois' => $programmes->where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        return view('admin.programmes.index', compact('programmes', 'stats'));
    }

    public function createProgramme()
    {
        return view('admin.programmes.create');
    }

    public function storeProgramme(Request $request)
    {
        $validatedData = $request->validate([
            'titre' => 'required|string|max:255',
            'formation' => 'required|string',
            'description' => 'nullable|string',
            'fichier_pdf' => 'required|file|mimes:pdf|max:51200', // Max 50MB
        ]);

        // Upload du fichier PDF
        $pdfFile = $request->file('fichier_pdf');
        $pdfPath = $pdfFile->store('programmes/pdfs', 'public');

        // Créer le programme
        DB::table('programmes')->insert([
            'titre' => $validatedData['titre'],
            'formation' => $validatedData['formation'],
            'description' => $validatedData['description'],
            'fichier_pdf' => $pdfPath,
            'created_by' => session('admin_id'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.programmes')->with('success', 'Programme ajouté avec succès.');
    }

    public function destroyProgramme($id)
    {
        $programme = DB::table('programmes')->where('id', $id)->first();

        if ($programme) {
            // Supprimer le fichier PDF
            if (Storage::disk('public')->exists($programme->fichier_pdf)) {
                Storage::disk('public')->delete($programme->fichier_pdf);
            }

            // Supprimer le programme de la base de données
            DB::table('programmes')->where('id', $id)->delete();

            return redirect()->route('admin.programmes')->with('success', 'Programme supprimé avec succès.');
        }

        return redirect()->route('admin.programmes')->with('error', 'Programme introuvable.');
    }

    public function createBibliothequeItem()
    {
        $categories = LibraryCategory::all();
        return view('admin.bibliotheque.create', compact('categories'));
    }

    public function storeBibliothequeItem(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048', // Max 2MB pour l'image
            'pdf_file' => 'required|file|mimes:pdf|max:51200', // Max 50MB pour le PDF
            'library_category_id' => 'nullable|exists:library_categories,id',
            'download_url' => 'nullable|url',
            'recipients' => 'nullable|array',
        ]);

        // Upload de l'image de couverture
        $coverImage = $request->file('cover_image');
        $coverPath = $coverImage->store('library/covers', 'public');

        // Upload du fichier PDF
        $pdfFile = $request->file('pdf_file');
        $pdfPath = $pdfFile->store('library/pdfs', 'public');

        Library::create([
            'title' => $validatedData['title'],
            'name' => $coverImage->getClientOriginalName(),
            'path' => $coverPath,
            'pdf_path' => $pdfPath, // Nouveau champ pour le PDF
            'file_type' => $coverImage->getClientOriginalExtension(),
            'size' => $coverImage->getSize(),
            'library_category_id' => $validatedData['library_category_id'] ?? null,
            'download_url' => $validatedData['download_url'] ?? null,
            'recipients' => $validatedData['recipients'] ?? [],
        ]);

        return redirect()->route('admin.bibliotheque.index')->with('success', 'Média ajouté avec succès.');
    }

    public function showBibliothequeItem(Library $item): View
    {
        return view('admin.bibliotheque.show', compact('item'));
    }

    public function editBibliothequeItem(Library $item): View
    {
        $categories = LibraryCategory::all();
        return view('admin.bibliotheque.edit', compact('item', 'categories'));
    }

    public function updateBibliothequeItem(Request $request, Library $item): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'library_category_id' => 'nullable|exists:library_categories,id',
            'recipients' => 'nullable|array',
        ]);

        $item->update([
            'title' => $validatedData['title'],
            'library_category_id' => $validatedData['library_category_id'] ?? null,
            'recipients' => $validatedData['recipients'] ?? [],
        ]);

        return redirect()->route('admin.bibliotheque.index')->with('success', 'Média mis à jour avec succès.');
    }

    public function destroyBibliothequeItem(Library $item): RedirectResponse
    {
        Storage::disk('public')->delete($item->path);
        $item->delete();

        return redirect()->route('admin.bibliotheque.index')->with('success', 'Média supprimé avec succès.');
    }

    public function toggleBibliothequeItemStatus(Library $item): RedirectResponse
    {
        $item->status = $item->status == 'active' ? 'inactive' : 'active';
        $item->save();

        return redirect()->route('admin.bibliotheque.index')->with('success', 'Statut du média mis à jour avec succès.');
    }

    public function travauxPending()
    {
        // Récupérer tous les TP en attente de validation (pending) et soumis (submitted)
        $pendingTps = DB::table('tp_assignments')
            ->whereIn('tp_assignments.status', ['pending', 'submitted'])
            ->leftJoin('students', 'tp_assignments.student_id', '=', 'students.id')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->select(
                'tp_assignments.*',
                'students.first_name as student_first_name',
                'students.last_name as student_last_name',
                'users.email as student_email',
                'students.program as formation',
                'students.profile_photo'
            )
            ->orderBy('tp_assignments.created_at', 'desc')
            ->get();

        // Grouper les TP par étudiant et formation
        $studentsByFormation = $pendingTps->groupBy('formation')->map(function ($formationTps) {
            return $formationTps->groupBy('student_id')->map(function ($studentTps) {
                $firstTp = $studentTps->first();
                $latestSubmission = $studentTps->whereNotNull('submitted_at')->sortByDesc('submitted_at')->first();

                return [
                    'student_id' => $firstTp->student_id,
                    'user_id' => $firstTp->user_id,
                    'first_name' => $firstTp->student_first_name,
                    'last_name' => $firstTp->student_last_name,
                    'user_name' => $firstTp->student_first_name . ' ' . $firstTp->student_last_name,
                    'user_email' => $firstTp->student_email,
                    'profile_photo' => $firstTp->profile_photo,
                    'program' => $firstTp->formation,
                    'formation' => $firstTp->formation,
                    'tps_count' => $studentTps->count(),
                    'latest_submission' => $latestSubmission ? $latestSubmission->submitted_at : $firstTp->created_at,
                    'tps' => $studentTps,
                    'pending_count' => $studentTps->count()
                ];
            })->values();
        });

        // Calculer les statistiques
        $totalStudents = $pendingTps->unique('student_id')->count();

        $stats = [
            'total_pending' => $pendingTps->count(),
            'total_students' => $totalStudents,
            'total_tps' => DB::table('tp_assignments')->count(),
            'design_graphique' => $pendingTps->where('formation', 'Design Graphique')->count(),
            'community_management' => $pendingTps->where('formation', 'Community Management')->count(),
            'gestion_informatique' => $pendingTps->where('formation', 'Gestion Informatique')->count(),
            'intelligence_artificielle' => $pendingTps->where('formation', 'Intelligence Artificielle')->count(),
        ];

        // Aplatir tous les étudiants pour la vue principale
        $studentsTps = collect();
        foreach ($studentsByFormation as $formationStudents) {
            $studentsTps = $studentsTps->merge($formationStudents);
        }

        return view('admin.travaux.pending', [
            'students_by_formation' => $studentsByFormation,
            'studentsTps' => $studentsTps,
            'stats' => $stats
        ]);
    }

    public function travauxToSend()
    {
        // Récupérer toutes les formations
        $formations = \App\Models\Formation::with('category')->where('status', 'active')->get();

        // Récupérer tous les étudiants depuis la table students avec les infos utilisateur
        $students = DB::table('students')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('students.status', 'active')
            ->select(
                'students.*',
                'students.program',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->get();

        // Traiter les noms et normaliser les formations
        $students = $students->map(function ($student) {
            // Séparer prénom et nom
            $nameParts = explode(' ', $student->user_name, 2);
            $student->prenom = $nameParts[0] ?? $student->user_name;
            $student->nom = $nameParts[1] ?? '';

            // Normaliser la formation (même logique que projets)
            if ($student->program) {
                $normalized = match (strtolower(str_replace([' ', '_', '-'], '', $student->program))) {
                    'designgraphique' => 'Design Graphique',
                    'communitymanagement' => 'Community Management',
                    'gestioninformatique' => 'Gestion Informatique',
                    'intelligenceartificielle' => 'Intelligence Artificielle',
                    default => $student->program
                };
                $student->formation = $normalized;
                $student->formation_normalized = $normalized;
            } else {
                $student->formation = 'Sans formation';
                $student->formation_normalized = 'Sans formation';
            }

            return $student;
        });

        // Calculer les statistiques avec formations normalisées
        $stats = [
            'total_formations' => $formations->count(),
            'total_students' => $students->count(),
            'design_graphique' => $students->where('formation_normalized', 'Design Graphique')->count(),
            'community_management' => $students->where('formation_normalized', 'Community Management')->count(),
            'gestion_informatique' => $students->where('formation_normalized', 'Gestion Informatique')->count(),
            'intelligence_artificielle' => $students->where('formation_normalized', 'Intelligence Artificielle')->count(),
            'sans_formation' => $students->where('formation_normalized', 'Sans formation')->count(),
        ];

        return view('admin.travaux.to-send', [
            'formations' => $formations,
            'students' => $students,
            'all_students' => $students,  // Alias pour compatibilité avec la vue
            'stats' => $stats
        ]);
    }

    /**
     * Envoyer un TP aux étudiants sélectionnés
     */
    public function sendTravaux(Request $request)
    {
        $request->validate([
            'tp_title' => 'required|string|max:255',
            'tp_description' => 'required|string',
            'tp_deadline' => 'required|date|after:today',
            'formation' => 'required|string',
            'students' => 'required|array',
            'students.*' => 'exists:students,id',
            'tp_files.*' => 'file|mimes:jpg,jpeg,png,gif,pdf|max:5120',
        ]);

        $adminId = session('admin_id');
        $studentsIds = $request->students;
        $assignments = [];
        $emailsSent = 0;
        $emailsFailures = [];
        $uploadedFiles = [];

        // Traiter les fichiers uploadés
        if ($request->hasFile('tp_files')) {
            foreach ($request->file('tp_files') as $file) {
                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('tp_files', $fileName, 'public');

                $uploadedFiles[] = [
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ];
            }
        }

        foreach ($studentsIds as $studentId) {
            // Récupérer les infos de l'étudiant
            $student = DB::table('students')->where('id', $studentId)->first();

            if ($student) {
                $assignment = [
                    'user_id' => $student->user_id,
                    'student_id' => $studentId,
                    'title' => $request->tp_title,
                    'description' => $request->tp_description,
                    'deadline' => $request->tp_deadline,
                    'formation' => $request->formation,
                    'status' => 'assigned',
                    'assigned_by' => $adminId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $assignmentId = DB::table('tp_assignments')->insertGetId($assignment);
                $assignment['id'] = $assignmentId;
                $assignments[] = $assignment;

                // Associer les fichiers à cette assignation
                foreach ($uploadedFiles as $fileData) {
                    DB::table('tp_assignment_files')->insert([
                        'tp_assignment_id' => $assignmentId,
                        'file_name' => $fileData['file_name'],
                        'file_path' => $fileData['file_path'],
                        'file_type' => $fileData['file_type'],
                        'file_size' => $fileData['file_size'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Envoi de l'email de notification
                try {
                    \Mail::send('emails.tp_assigned', [
                        'student' => $student,
                        'assignment' => $assignment
                    ], function ($message) use ($student, $assignment) {
                        $message->to($student->email)
                            ->subject('Nouveau TP : ' . $assignment['title']);
                    });
                    $emailsSent++;
                } catch (\Exception $e) {
                    $emailsFailures[] = $student->email;
                    \Log::error('Erreur envoi email TP à ' . $student->email . ': ' . $e->getMessage());
                }
            }
        }

        $message = 'TP envoyé avec succès à ' . count($assignments) . ' étudiant(s)';
        if ($emailsSent > 0) {
            $message .= '. ' . $emailsSent . ' email(s) de notification envoyé(s)';
        }
        if (!empty($emailsFailures)) {
            $message .= '. Attention : ' . count($emailsFailures) . ' email(s) non envoyé(s)';
        }

        return redirect()
            ->route('admin.travaux.assigned')
            ->with('success', $message);
    }

    /**
     * Afficher les détails d'une assignation de TP par titre
     */
    public function assignmentDetail($title)
    {
        // Décoder le titre URL
        $title = urldecode($title);

        // Récupérer toutes les assignations pour ce titre
        $assignments = DB::table('tp_assignments')
            ->leftJoin('students', 'tp_assignments.student_id', '=', 'students.id')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('tp_assignments.title', $title)
            ->select(
                'tp_assignments.*',
                'students.first_name as student_first_name',
                'students.last_name as student_last_name',
                'students.email as student_email',
                'students.program as formation',
                'students.program',
                'students.profile_photo',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->orderBy('tp_assignments.created_at', 'desc')
            ->get();

        if ($assignments->isEmpty()) {
            return redirect()->route('admin.travaux.assigned')
                ->with('error', 'Aucune assignation trouvée pour ce TP');
        }

        // Prendre la première assignation pour obtenir les infos du TP
        $assignment = $assignments->first();

        // Récupérer les fichiers associés aux assignations
        $assignmentIds = $assignments->pluck('id');
        $files = DB::table('tp_assignment_files')
            ->whereIn('tp_assignment_id', $assignmentIds)
            ->get();

        // Statistiques
        $stats = [
            'total' => $assignments->count(),
            'assigned' => $assignments->where('status', 'assigned')->count(),
            'submitted' => $assignments->where('status', 'submitted')->count(),
            'validated' => $assignments->where('status', 'validated')->count(),
            'rejected' => $assignments->where('status', 'rejected')->count(),
            'pending' => $assignments->where('status', 'assigned')->count(), // Alias pour compatibilité
        ];

        return view('admin.travaux.assignment-detail', [
            'title' => $title,
            'assignment' => $assignment,
            'assignments' => $assignments,
            'students' => $assignments, // Alias pour la vue qui attend $students
            'files' => $files,
            'stats' => $stats
        ]);
    }

    /**
     * Afficher le formulaire d'édition d'un TP assigné par titre
     */
    public function editAssignment($title)
    {
        // Décoder le titre URL
        $title = urldecode($title);

        // Récupérer la première assignation pour obtenir les infos du TP
        $assignment = DB::table('tp_assignments')
            ->where('title', $title)
            ->first();

        if (!$assignment) {
            return redirect()->route('admin.travaux.assigned')
                ->with('error', 'Aucune assignation trouvée pour ce TP');
        }

        // Récupérer les IDs des étudiants actuellement assignés
        $assignedStudentIds = DB::table('tp_assignments')
            ->where('title', $title)
            ->pluck('student_id')
            ->toArray();

        // Récupérer tous les étudiants actifs
        $allStudents = DB::table('students')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select(
                'students.id',
                'students.first_name',
                'students.last_name',
                'students.program',
                'users.email'
            )
            ->orderBy('students.first_name')
            ->get();

        // Grouper les étudiants par formation
        $studentsByFormation = $allStudents->groupBy('program');

        return view('admin.travaux.edit-assignment', [
            'assignment' => $assignment,
            'title' => $title,
            'studentsCount' => count($assignedStudentIds),
            'assignedStudentIds' => $assignedStudentIds,
            'studentsByFormation' => $studentsByFormation,
            'allStudents' => $allStudents
        ]);
    }

    /**
     * Mettre à jour un TP assigné par titre (met à jour toutes les assignations avec ce titre)
     */
    public function updateAssignment(Request $request, $title)
    {
        // Décoder le titre URL
        $title = urldecode($title);


        $request->validate([
            'new_title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'students' => 'required|array',
            'students.*' => 'exists:students,id',
        ]);

        // Supprimer toutes les anciennes assignations
        DB::table('tp_assignments')
            ->where('title', $title)
            ->delete();

        // Créer de nouvelles assignations pour les étudiants sélectionnés
        $adminId = session('admin_id');
        $formation = $request->formation ?? 'Non spécifié';

        foreach ($request->students as $studentId) {
            DB::table('tp_assignments')->insert([
                'user_id' => DB::table('students')->where('id', $studentId)->value('user_id'),
                'student_id' => $studentId,
                'title' => $request->new_title,
                'description' => $request->description,
                'deadline' => $request->deadline,
                'formation' => DB::table('students')->where('id', $studentId)->value('program') ?? $formation,
                'status' => 'assigned',
                'assigned_by' => $adminId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()
            ->route('admin.travaux.assigned')
            ->with('success', "TP modifié avec succès pour " . count($request->students) . " étudiant(s)");
    }

    /**
     * Supprimer une assignation de TP par titre (supprime toutes les assignations avec ce titre)
     */
    public function deleteAssignment($title)
    {
        // Décoder le titre URL
        $title = urldecode($title);

        $deleted = DB::table('tp_assignments')
            ->where('title', $title)
            ->delete();

        return redirect()
            ->route('admin.travaux.assigned')
            ->with('success', "$deleted assignation(s) supprimée(s) avec succès");
    }

    public function travauxAssigned()
    {
        // Récupérer tous les TP assignés
        $assignments = DB::table('tp_assignments')
            ->leftJoin('students', 'tp_assignments.student_id', '=', 'students.id')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->select(
                'tp_assignments.*',
                'students.first_name as student_first_name',
                'students.last_name as student_last_name',
                'students.email as student_email',
                'students.program as formation',
                'students.profile_photo',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->orderBy('tp_assignments.created_at', 'desc')
            ->get();

        // Créer student_name en combinant first_name et last_name
        $assignments = $assignments->map(function ($assignment) {
            $assignment->student_name = trim(($assignment->student_first_name ?? '') . ' ' . ($assignment->student_last_name ?? ''));
            return $assignment;
        });

        // Grouper par formation
        $assignmentsByFormation = $assignments->groupBy('formation');

        // Calculer les statistiques
        $submittedCount = $assignments->whereNotNull('submitted_at')->count();

        $stats = [
            'total' => $assignments->count(),  // Alias pour compatibilité
            'total_assignments' => $assignments->count(),
            'assigned' => $assignments->count(),  // Alias pour compatibilité
            'submitted' => $submittedCount,  // TP soumis par les étudiants
            'pending' => $assignments->where('status', 'pending')->count(),
            'validated' => $assignments->where('status', 'validated')->count(),
            'rejected' => $assignments->where('status', 'rejected')->count(),
            'design_graphique' => $assignments->where('formation', 'Design Graphique')->count(),
            'community_management' => $assignments->where('formation', 'Community Management')->count(),
            'gestion_informatique' => $assignments->where('formation', 'Gestion Informatique')->count(),
            'intelligence_artificielle' => $assignments->where('formation', 'Intelligence Artificielle')->count(),
        ];

        return view('admin.travaux.assigned', [
            'assignments' => $assignments,
            'assignmentsByFormation' => $assignmentsByFormation,
            'tpAssignmentsByFormation' => $assignmentsByFormation,  // Alias pour compatibilité
            'stats' => $stats
        ]);
    }

    public function travauxAll()
    {
        // Récupérer tous les TP (toutes catégories)
        $allTravaux = DB::table('tp_assignments')
            ->leftJoin('students', 'tp_assignments.student_id', '=', 'students.id')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->select(
                'tp_assignments.*',
                'students.first_name as student_first_name',
                'students.last_name as student_last_name',
                'students.email as student_email',
                DB::raw('COALESCE(students.program, "Formation non définie") as formation'),
                'students.profile_photo',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->orderBy('tp_assignments.created_at', 'desc')
            ->get();

        // Créer student_name en combinant first_name et last_name
        $allTravaux = $allTravaux->map(function ($travail) {
            $travail->student_name = trim(($travail->student_first_name ?? '') . ' ' . ($travail->student_last_name ?? ''));
            return $travail;
        });

        // Calculer les statistiques globales
        $totalStudents = $allTravaux->unique('student_id')->count();

        $pendingCount = $allTravaux->where('status', 'pending')->count();
        $validatedCount = $allTravaux->where('status', 'validated')->count();
        $rejectedCount = $allTravaux->where('status', 'rejected')->count();

        $stats = [
            'total' => $allTravaux->count(),
            'total_assignments' => $allTravaux->count(),
            'total_tps' => $allTravaux->count(),  // Alias pour compatibilité
            'total_students' => $totalStudents,  // Nombre d'étudiants uniques
            'assigned' => $allTravaux->count(),
            'submitted' => $allTravaux->whereNotNull('submitted_at')->count(),
            'pending' => $pendingCount,
            'pending_tps' => $pendingCount,  // Alias pour compatibilité
            'validated' => $validatedCount,
            'validated_tps' => $validatedCount,  // Alias pour compatibilité
            'rejected' => $rejectedCount,
            'rejected_tps' => $rejectedCount,  // Alias pour compatibilité
            'not_submitted' => $allTravaux->whereNull('submitted_at')->count(),
            'design_graphique' => $allTravaux->where('formation', 'Design Graphique')->count(),
            'community_management' => $allTravaux->where('formation', 'Community Management')->count(),
            'gestion_informatique' => $allTravaux->where('formation', 'Gestion Informatique')->count(),
            'intelligence_artificielle' => $allTravaux->where('formation', 'Intelligence Artificielle')->count(),
        ];

        // Créer studentsTps pour compatibilité avec la vue
        // Grouper les travaux par étudiant
        $studentsTps = $allTravaux->groupBy('student_id')->map(function ($studentTravaux) {
            $firstTravail = $studentTravaux->first();
            // Trouver la dernière soumission
            $latestSubmission = $studentTravaux->whereNotNull('submitted_at')->sortByDesc('submitted_at')->first();

            // Déterminer la formation avec fallback
            $formation = $firstTravail->formation ?? 'Formation non définie';

            return [
                'student_id' => $firstTravail->student_id,
                'user_id' => $firstTravail->student_id,             // Alias pour les collapsibles
                'student_name' => $firstTravail->student_name,
                'student_first_name' => $firstTravail->student_first_name,
                'student_last_name' => $firstTravail->student_last_name,
                'first_name' => $firstTravail->student_first_name,  // Alias
                'last_name' => $firstTravail->student_last_name,    // Alias
                'student_email' => $firstTravail->student_email,
                'user_email' => $firstTravail->student_email,       // Alias
                'formation' => $formation,
                'program' => $formation,                            // Alias
                'profile_photo' => $firstTravail->profile_photo,
                'tps_count' => $studentTravaux->count(),
                'pending_count' => $studentTravaux->where('status', 'pending')->count(),
                'validated_count' => $studentTravaux->where('status', 'validated')->count(),
                'rejected_count' => $studentTravaux->where('status', 'rejected')->count(),
                'latest_submission' => $latestSubmission ? $latestSubmission->submitted_at : null,
                'tps' => $studentTravaux->values()->toArray(),      // Tableau des TP pour le détail
            ];
        })->values();

        return view('admin.travaux.all', [
            'travaux' => $allTravaux,
            'allTravaux' => $allTravaux,  // Alias pour compatibilité
            'studentsTps' => $studentsTps,  // Travaux groupés par étudiant
            'stats' => $stats
        ]);
    }

    public function documentsAll(): View
    {
        // Récupère tous les rapports/travaux publiés par les étudiants
        $rapports = DB::table('tp')
            ->join('users', 'tp.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->select(
                'tp.id',
                'tp.title',
                'tp.description',
                'tp.status',
                'tp.created_at',
                'tp.updated_at',
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                'students.profile_photo as user_photo',
                'students.program as formation',
                'students.specialization'
            )
            ->orderBy('tp.created_at', 'desc')
            ->get();

        // Récupérer les fichiers pour chaque TP
        foreach ($rapports as $rapport) {
            $rapport->files = DB::table('tp_files')
                ->where('tp_id', $rapport->id)
                ->get();
        }

        // Statistiques
        $stats = [
            'total' => $rapports->count(),
            'validated' => $rapports->where('status', 'validated')->count(),
            'pending' => $rapports->where('status', 'pending')->count(),
            'rejected' => $rapports->where('status', 'rejected')->count(),
        ];

        // Retourne la vue en passant la collection de rapports
        return view('admin.documents.all', compact('rapports', 'stats'));
    }

    /**
     * Afficher les documents en attente de validation
     */
    public function documentsPending(): View
    {
        // Récupère uniquement les rapports en attente
        $rapports = DB::table('tp')
            ->join('users', 'tp.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->where('tp.status', 'pending')
            ->select(
                'tp.id',
                'tp.title',
                'tp.description',
                'tp.status',
                'tp.created_at',
                'tp.updated_at',
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                'students.profile_photo as user_photo',
                'students.program as formation',
                'students.specialization'
            )
            ->orderBy('tp.created_at', 'desc')
            ->get();

        // Récupérer les fichiers pour chaque TP
        foreach ($rapports as $rapport) {
            $rapport->files = DB::table('tp_files')
                ->where('tp_id', $rapport->id)
                ->get();
        }

        // Statistiques
        $stats = [
            'total' => $rapports->count(),
            'pending' => $rapports->count(),
            'today' => $rapports->filter(function ($rapport) {
                return \Carbon\Carbon::parse($rapport->created_at)->isToday();
            })->count(),
        ];

        // Retourne la vue en passant la collection de rapports
        return view('admin.documents.pending', compact('rapports', 'stats'));
    }

    /**
     * Voir un TP (admin)
     */
    public function viewTp(int $id)
    {
        // Chercher d'abord dans tp_assignments (travaux), puis dans tp (rapports)
        $tp = DB::table('tp_assignments')
            ->leftJoin('students', 'tp_assignments.student_id', '=', 'students.id')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('tp_assignments.id', $id)
            ->select(
                'tp_assignments.*',
                'students.first_name as student_first_name',
                'students.last_name as student_last_name',
                'users.email as student_email',
                'students.program as formation',
                'students.profile_photo'
            )
            ->first();

        // Si pas trouvé dans tp_assignments, chercher dans tp
        if (!$tp) {
            $tp = DB::table('tp')->where('id', $id)->first();

            if (!$tp) {
                abort(404, 'TP introuvable');
            }

            // Récupérer l'utilisateur associé
            $user = DB::table('users')->where('id', $tp->user_id)->first();

            // Récupérer les fichiers associés au TP
            $files = collect([]);
            if (Schema::hasTable('tp_files')) {
                $files = DB::table('tp_files')->where('tp_id', $id)->get();
            }

            // Ajouter les propriétés manquantes au TP
            $tp->tags = $tp->tags ?? null;
            $tp->software_used = $tp->software_used ?? null;
            $tp->files = $files;

            return view('tp.view-admin', [
                'project' => $tp,
                'user' => $user
            ]);
        }

        // Pour tp_assignments, créer un objet student pour la vue
        $student = (object)[
            'first_name' => $tp->student_first_name,
            'last_name' => $tp->student_last_name,
            'email' => $tp->student_email,
            'program' => $tp->formation,
            'profile_photo' => $tp->profile_photo,
        ];

        // Récupérer les fichiers associés à ce TP
        $files = DB::table('tp_assignment_files')
            ->where('tp_assignment_id', $id)
            ->get();

        return view('admin.travaux.view', [
            'tp' => $tp,
            'student' => $student,
            'files' => $files
        ]);
    }

    /**
     * Valider un TP (admin)
     */
    public function validateTp(Request $request, int $id)
    {
        try {
            // Chercher d'abord dans tp_assignments
            $tp = DB::table('tp_assignments')
                ->leftJoin('students', 'tp_assignments.student_id', '=', 'students.id')
                ->leftJoin('users', 'students.user_id', '=', 'users.id')
                ->where('tp_assignments.id', $id)
                ->select(
                    'tp_assignments.*',
                    'students.first_name as student_first_name',
                    'students.last_name as student_last_name',
                    'users.email as student_email'
                )
                ->first();

            // Si pas trouvé, chercher dans tp (rapports)
            if (!$tp) {
                $tp = DB::table('tp')->where('id', $id)->first();

                if (!$tp) {
                    if ($request->expectsJson()) {
                        return response()->json(['success' => false, 'message' => 'TP introuvable'], 404);
                    }
                    return redirect()->back()->with('error', 'TP introuvable');
                }

                // Pour la table tp (rapports)
                $user = DB::table('users')->where('id', $tp->user_id)->first();

                DB::table('tp')->where('id', $id)->update([
                    'status' => 'validated',
                    'validated_at' => now(),
                    'updated_at' => now()
                ]);

                // Email pour rapport
                try {
                    Mail::send('emails.tp-validated', [
                        'user' => $user,
                        'tp' => $tp
                    ], function ($message) use ($user) {
                        $message->to($user->email)->subject('✅ Votre TP a été validé - EVC');
                    });
                } catch (\Exception $e) {
                    Log::warning('Erreur envoi email validation TP: ' . $e->getMessage());
                }

                return redirect()->back()->with('success', 'TP validé avec succès !');
            }

            // Pour tp_assignments
            DB::table('tp_assignments')->where('id', $id)->update([
                'status' => 'validated',
                'validated_at' => now(),
                'updated_at' => now()
            ]);

            // Créer objet student pour l'email
            $student = (object)[
                'first_name' => $tp->student_first_name,
                'last_name' => $tp->student_last_name,
                'email' => $tp->student_email,
            ];

            // Envoyer email de validation
            try {
                Mail::send('emails.tp_validated', [
                    'student' => $student,
                    'tp' => $tp
                ], function ($message) use ($student, $tp) {
                    $message->to($student->email)
                        ->subject('✅ Votre TP "' . $tp->title . '" a été validé !');
                });
            } catch (\Exception $e) {
                Log::error('Erreur envoi email validation TP: ' . $e->getMessage());
            }

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'TP validé avec succès !']);
            }

            return redirect()->back()->with('success', '✅ TP validé avec succès ! Un email a été envoyé à l\'étudiant.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Erreur lors de la validation: ' . $e->getMessage());
        }
    }

    /**
     * Rejeter un TP (admin)
     */
    public function rejectTp(Request $request, int $id)
    {
        try {
            // Valider la raison
            $request->validate([
                'reason' => 'required|string|min:10'
            ]);

            $reason = $request->input('reason');

            // Chercher d'abord dans tp_assignments
            $tp = DB::table('tp_assignments')
                ->leftJoin('students', 'tp_assignments.student_id', '=', 'students.id')
                ->leftJoin('users', 'students.user_id', '=', 'users.id')
                ->where('tp_assignments.id', $id)
                ->select(
                    'tp_assignments.*',
                    'students.first_name as student_first_name',
                    'students.last_name as student_last_name',
                    'users.email as student_email'
                )
                ->first();

            // Si pas trouvé, chercher dans tp (rapports)
            if (!$tp) {
                $tp = DB::table('tp')->where('id', $id)->first();

                if (!$tp) {
                    return redirect()->back()->with('error', 'TP introuvable');
                }

                // Pour la table tp (rapports)
                $user = DB::table('users')->where('id', $tp->user_id)->first();

                DB::table('tp')->where('id', $id)->update([
                    'status' => 'rejected',
                    'admin_comment' => $reason,
                    'updated_at' => now()
                ]);

                // Email pour rapport
                try {
                    Mail::send('emails.tp-rejected', [
                        'user' => $user,
                        'tp' => $tp,
                        'rejectionReason' => $reason
                    ], function ($message) use ($user) {
                        $message->to($user->email)->subject('📝 Votre TP nécessite des améliorations - EVC');
                    });
                } catch (\Exception $e) {
                    Log::warning('Erreur envoi email rejet TP: ' . $e->getMessage());
                }

                return redirect()->back()->with('success', '✅ TP rejeté avec succès !');
            }

            // Pour tp_assignments
            DB::table('tp_assignments')->where('id', $id)->update([
                'status' => 'rejected',
                'admin_comment' => $reason,
                'updated_at' => now()
            ]);

            // Créer objet student pour l'email
            $student = (object)[
                'first_name' => $tp->student_first_name,
                'last_name' => $tp->student_last_name,
                'email' => $tp->student_email,
            ];

            // Envoyer email de rejet
            try {
                Mail::send('emails.tp_rejected', [
                    'student' => $student,
                    'tp' => $tp,
                    'rejectionReason' => $reason
                ], function ($message) use ($student, $tp) {
                    $message->to($student->email)
                        ->subject('📝 Votre TP "' . $tp->title . '" nécessite des améliorations');
                });
            } catch (\Exception $e) {
                Log::error('Erreur envoi email rejet TP: ' . $e->getMessage());
            }

            return redirect()->back()->with('success', '✅ TP rejeté avec succès ! Un email a été envoyé à l\'étudiant.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Erreur lors du rejet: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un rapport/TP
     */
    public function deleteTp(Request $request, $id)
    {
        try {
            // Supprimer les fichiers associés
            $files = DB::table('tp_files')->where('tp_id', $id)->get();
            foreach ($files as $file) {
                if (Storage::exists($file->file_path)) {
                    Storage::delete($file->file_path);
                }
                DB::table('tp_files')->where('id', $file->id)->delete();
            }

            // Supprimer le TP depuis tp_assignments (travaux) ou tp (rapports)
            $deletedFromAssignments = DB::table('tp_assignments')->where('id', $id)->delete();
            if (!$deletedFromAssignments) {
                DB::table('tp')->where('id', $id)->delete();
            }

            // Rediriger vers la page d'origine
            $redirectTo = $request->input('redirect_to', route('admin.travaux.all'));
            return redirect($redirectTo)->with('success', '✅ TP supprimé avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du rapport: ' . $e->getMessage());
            $redirectTo = $request->input('redirect_to', route('admin.documents.all'));
            return redirect($redirectTo)->with('error', '❌ Erreur lors de la suppression');
        }
    }

    /**
     * Mettre à jour le statut d'un rapport/TP
     */
    public function updateTpStatus(Request $request, $id)
    {
        try {
            $status = $request->input('status', 'validated');

            // Récupérer les informations du rapport et de l'étudiant
            $rapport = DB::table('tp')
                ->join('users', 'tp.user_id', '=', 'users.id')
                ->leftJoin('students', 'users.id', '=', 'students.user_id')
                ->where('tp.id', $id)
                ->select(
                    'tp.*',
                    'users.name as user_name',
                    'users.email as user_email',
                    'students.program as formation'
                )
                ->first();

            if (!$rapport) {
                return redirect()->route('admin.documents.all')->with('error', '❌ Rapport introuvable');
            }

            // Mettre à jour le statut du TP
            DB::table('tp')->where('id', $id)->update([
                'status' => $status,
                'updated_at' => now()
            ]);

            // Envoyer un email à l'étudiant selon le statut
            if ($rapport->user_email) {
                try {
                    if ($status === 'validated') {
                        // Email de validation
                        Mail::send([], [], function ($message) use ($rapport) {
                            $message->to($rapport->user_email)
                                ->subject('✅ Votre rapport a été validé - École Virtuelle des Créatifs')
                                ->html("
                                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: #f8f9fa;'>
                                        <div style='background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%); padding: 30px; border-radius: 10px 10px 0 0; text-align: center;'>
                                            <h1 style='color: white; margin: 0;'>✅ Rapport Validé !</h1>
                                        </div>
                                        <div style='background: white; padding: 30px; border-radius: 0 0 10px 10px;'>
                                            <p style='font-size: 16px; color: #333;'>Bonjour <strong>{$rapport->user_name}</strong>,</p>
                                            <p style='font-size: 16px; color: #333; line-height: 1.6;'>
                                                Nous avons le plaisir de vous informer que votre rapport <strong>« {$rapport->title} »</strong> a été validé par l'administration.
                                            </p>
                                            <div style='background: #f0f9ff; padding: 20px; border-left: 4px solid #56ab2f; margin: 20px 0; border-radius: 5px;'>
                                                <p style='margin: 0; color: #333;'><strong>📋 Titre :</strong> {$rapport->title}</p>
                                                <p style='margin: 10px 0 0 0; color: #333;'><strong>📅 Date de validation :</strong> " . now()->format('d/m/Y à H:i') . "</p>
                                            </div>
                                            <p style='font-size: 16px; color: #333;'>
                                                Félicitations pour votre travail ! Vous pouvez consulter votre rapport validé dans votre espace étudiant.
                                            </p>
                                            <div style='text-align: center; margin: 30px 0;'>
                                                <a href='" . url('/evc/compte/' . strtolower(str_replace(' ', '-', $rapport->formation ?? 'community-management')) . '/documents/index') . "'
                                                   style='display: inline-block; background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 25px; font-weight: bold;'>
                                                    Voir mes rapports
                                                </a>
                                            </div>
                                            <hr style='border: none; border-top: 1px solid #e0e0e0; margin: 30px 0;'>
                                            <p style='font-size: 14px; color: #666; text-align: center;'>
                                                Cordialement,<br>
                                                <strong>L'équipe de l'École Virtuelle des Créatifs</strong>
                                            </p>
                                        </div>
                                    </div>
                                ");
                        });

                        Log::info('Email de validation envoyé', [
                            'rapport_id' => $id,
                            'student_email' => $rapport->user_email
                        ]);
                    } elseif ($status === 'rejected') {
                        // Email de rejet
                        Mail::send([], [], function ($message) use ($rapport) {
                            $message->to($rapport->user_email)
                                ->subject('❌ Votre rapport nécessite des modifications - École Virtuelle des Créatifs')
                                ->html("
                                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: #f8f9fa;'>
                                        <div style='background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); padding: 30px; border-radius: 10px 10px 0 0; text-align: center;'>
                                            <h1 style='color: white; margin: 0;'>❌ Rapport à Modifier</h1>
                                        </div>
                                        <div style='background: white; padding: 30px; border-radius: 0 0 10px 10px;'>
                                            <p style='font-size: 16px; color: #333;'>Bonjour <strong>{$rapport->user_name}</strong>,</p>
                                            <p style='font-size: 16px; color: #333; line-height: 1.6;'>
                                                Après examen de votre rapport <strong>« {$rapport->title} »</strong>, nous vous demandons d'y apporter des modifications.
                                            </p>
                                            <div style='background: #fff5f5; padding: 20px; border-left: 4px solid #eb3349; margin: 20px 0; border-radius: 5px;'>
                                                <p style='margin: 0; color: #333;'><strong>📋 Titre :</strong> {$rapport->title}</p>
                                                <p style='margin: 10px 0 0 0; color: #333;'><strong>📅 Date d'examen :</strong> " . now()->format('d/m/Y à H:i') . "</p>
                                            </div>
                                            <p style='font-size: 16px; color: #333;'>
                                                Veuillez consulter les commentaires de votre formateur et soumettre une nouvelle version de votre rapport.
                                            </p>
                                            <div style='text-align: center; margin: 30px 0;'>
                                                <a href='" . url('/evc/compte/' . strtolower(str_replace(' ', '-', $rapport->formation ?? 'community-management')) . '/documents/index') . "'
                                                   style='display: inline-block; background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 25px; font-weight: bold;'>
                                                    Voir mes rapports
                                                </a>
                                            </div>
                                            <hr style='border: none; border-top: 1px solid #e0e0e0; margin: 30px 0;'>
                                            <p style='font-size: 14px; color: #666; text-align: center;'>
                                                Cordialement,<br>
                                                <strong>L'équipe de l'École Virtuelle des Créatifs</strong>
                                            </p>
                                        </div>
                                    </div>
                                ");
                        });

                        Log::info('Email de rejet envoyé', [
                            'rapport_id' => $id,
                            'student_email' => $rapport->user_email
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::warning('Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
                    // Continue même si l'email échoue
                }
            }

            // Message selon le statut
            $message = $status === 'validated' ? '✅ Rapport validé avec succès ! Un email a été envoyé à l\'étudiant.' : '❌ Rapport rejeté';

            return redirect()->route('admin.documents.all')->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du statut: ' . $e->getMessage());
            return redirect()->route('admin.documents.all')->with('error', '❌ Erreur lors de la mise à jour du statut');
        }
    }

    /**
     * Afficher la CVthèque (liste des étudiants)
     */
    public function cvtheque(): View
    {
        // Récupérer tous les étudiants actifs
        $students = DB::table('students')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select(
                'students.id',
                'students.user_id',
                'students.first_name',
                'students.last_name',
                'students.profile_photo',
                'students.program as formation',
                'students.specialization',
                'students.phone',
                'students.status',
                'students.created_at',
                'users.email'
            )
            ->where('students.status', 'active')
            ->orderBy('students.created_at', 'desc')
            ->get();

        // Statistiques par formation
        $stats = [
            'total' => $students->count(),
            'design_graphique' => $students->where('formation', 'Design Graphique')->count(),
            'community_management' => $students->where('formation', 'Community Management')->count(),
            'gestion_informatique' => $students->where('formation', 'Gestion Informatique')->count(),
            'intelligence_artificielle' => $students->where('formation', 'Intelligence Artificielle')->count(),
        ];

        return view('admin.cvtheque', compact('students', 'stats'));
    }

    /**
     * Afficher les étudiants éligibles aux certificats
     */
    public function certificatsEligible()
    {
        // Récupérer tous les étudiants actifs avec leurs informations
        $students = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->select(
                'students.*',
                'users.email',
                'users.name as user_name',
                'users.created_at as user_created_at'
            )
            ->where('students.status', 'active')
            ->get();

        // Critères d'éligibilité
        $minTPRequired = 15;
        $minProjectsRequired = 4;

        // Filtrer les étudiants éligibles
        $eligibleStudents = [];

        foreach ($students as $student) {
            // Compter les TP validés
            $tpValidated = DB::table('tp_assignments')
                ->where('student_id', $student->id)
                ->where('status', 'validated')
                ->count();

            // Compter les projets validés
            $projectsCompleted = DB::table('projects')
                ->where('user_id', $student->user_id)
                ->where('status', 'valide')
                ->count();

            // Vérifier si rapport uploadé
            $report = DB::table('end_of_training_reports')
                ->where('student_id', $student->id)
                ->first();

            // Vérifier l'éligibilité
            $tpEligible = $tpValidated >= $minTPRequired;
            $projectsEligible = $projectsCompleted >= $minProjectsRequired;
            $reportUploaded = $report ? true : false;
            $paymentComplete = false; // À implémenter avec système de paiement

            // Si tous les critères sont remplis (sauf paiement pour l'instant)
            $isEligible = $tpEligible && $projectsEligible && $reportUploaded;

            if ($isEligible) {
                $eligibleStudents[] = [
                    'student' => $student,
                    'tp_validated' => $tpValidated,
                    'projects_completed' => $projectsCompleted,
                    'report' => $report,
                    'payment_complete' => $paymentComplete,
                    'is_eligible' => $isEligible,
                ];
            }
        }

        // Statistiques
        $stats = [
            'total_eligible' => count($eligibleStudents),
            'design_graphique' => collect($eligibleStudents)->where('student.program', 'Design Graphique')->count(),
            'community_management' => collect($eligibleStudents)->where('student.program', 'Community Management')->count(),
            'gestion_informatique' => collect($eligibleStudents)->where('student.program', 'Gestion Informatique')->count(),
            'intelligence_artificielle' => collect($eligibleStudents)->where('student.program', 'Intelligence Artificielle')->count(),
        ];

        return view('admin.certificats.eligible', [
            'eligibleStudents' => $eligibleStudents,
            'stats' => $stats,
            'minTPRequired' => $minTPRequired,
            'minProjectsRequired' => $minProjectsRequired,
        ]);
    }

    /**
     * Générer et télécharger le certificat pour un étudiant (Admin)
     */
    public function generateCertificate($id)
    {
        // Récupérer l'étudiant avec ses informations
        $student = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('students.id', $id)
            ->select(
                'students.*',
                'users.email',
                'users.name as user_name'
            )
            ->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Étudiant non trouvé');
        }

        // Vérifier l'éligibilité
        $tpValidated = DB::table('tp_assignments')
            ->where('student_id', $student->id)
            ->where('status', 'validated')
            ->count();

        $projectsCompleted = DB::table('projects')
            ->where('user_id', $student->user_id)
            ->where('status', 'valide')
            ->count();

        $report = DB::table('end_of_training_reports')
            ->where('student_id', $student->id)
            ->first();

        // Critères minimums
        $minTPRequired = 15;
        $minProjectsRequired = 4;

        $tpEligible = $tpValidated >= $minTPRequired;
        $projectsEligible = $projectsCompleted >= $minProjectsRequired;
        $reportUploaded = $report ? true : false;

        $isEligible = $tpEligible && $projectsEligible && $reportUploaded;

        if (!$isEligible) {
            return redirect()->back()->with('error', 'Cet étudiant ne remplit pas encore tous les critères d\'éligibilité.');
        }

        // Générer le certificat PDF à partir du template personnalisé
        try {
            $certificateGenerator = new \App\Services\CertificateGenerator();

            // Données à insérer dans le certificat
            $data = [
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'formation' => $student->program,
                'date' => now()->format('d/m/Y'),
                'student_id' => $student->student_id,
            ];

            // Générer le certificat selon la formation
            if ($student->program == 'Community Management' || $student->program == 'Social Media Marketing') {
                $certificatePath = $certificateGenerator->generateCommunityManagement($data);
            } else {
                // Pour les autres formations, utiliser le template par défaut (à créer)
                $certificatePath = $certificateGenerator->generateCommunityManagement($data);
            }

            $filename = 'Certificat_' . str_replace(' ', '_', $student->first_name . '_' . $student->last_name) . '_' . now()->format('Y') . '.pdf';

            // Enregistrer dans la base de données qu'un certificat a été généré
            DB::table('certificates')->insert([
                'student_id' => $student->id,
                'user_id' => $student->user_id,
                'formation' => $student->program,
                'generated_by' => auth()->id(),
                'generated_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Télécharger le certificat
            return $certificateGenerator->download($certificatePath, $filename);
        } catch (\Exception $e) {
            \Log::error('Erreur génération certificat admin: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la génération du certificat: ' . $e->getMessage());
        }
    }

    /**
     * Prévisualiser le certificat dans le navigateur (Admin)
     */
    public function previewCertificate($id)
    {
        // Récupérer l'étudiant avec ses informations
        $student = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('students.id', $id)
            ->select(
                'students.*',
                'users.email',
                'users.name as user_name'
            )
            ->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Étudiant non trouvé');
        }

        // Pour la prévisualisation admin, on génère le certificat même si non éligible
        // Générer le certificat PDF à partir du template personnalisé
        try {
            $certificateGenerator = new \App\Services\CertificateGenerator();

            // Données à insérer dans le certificat
            $data = [
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'formation' => $student->program,
                'date' => now()->format('d/m/Y'),
                'student_id' => $student->student_id,
            ];

            // Générer le certificat selon la formation
            if ($student->program == 'Community Management' || $student->program == 'Social Media Marketing') {
                $certificatePath = $certificateGenerator->generateCommunityManagement($data);
            } else {
                $certificatePath = $certificateGenerator->generateCommunityManagement($data);
            }

            // Afficher le PDF dans le navigateur (inline)
            return response()->file($certificatePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Certificat_Preview.pdf"'
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            \Log::error('Erreur prévisualisation certificat admin: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la prévisualisation du certificat: ' . $e->getMessage());
        }
    }

    /**
     * Afficher les étudiants non éligibles aux certificats
     */
    public function certificatsNotEligible()
    {
        // Récupérer tous les étudiants actifs avec leurs informations
        $students = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->select(
                'students.*',
                'users.email',
                'users.name as user_name',
                'users.created_at as user_created_at'
            )
            ->where('students.status', 'active')
            ->get();

        // Critères d'éligibilité
        $minTPRequired = 15;
        $minProjectsRequired = 4;

        // Filtrer les étudiants NON éligibles
        $notEligibleStudents = [];

        foreach ($students as $student) {
            // Compter les TP validés
            $tpValidated = DB::table('tp_assignments')
                ->where('student_id', $student->id)
                ->where('status', 'validated')
                ->count();

            // Compter les projets validés
            $projectsCompleted = DB::table('projects')
                ->where('user_id', $student->user_id)
                ->where('status', 'valide')
                ->count();

            // Vérifier si rapport uploadé
            $report = DB::table('end_of_training_reports')
                ->where('student_id', $student->id)
                ->first();

            // Vérifier l'éligibilité
            $tpEligible = $tpValidated >= $minTPRequired;
            $projectsEligible = $projectsCompleted >= $minProjectsRequired;
            $reportUploaded = $report ? true : false;

            // Si au moins un critère n'est pas rempli
            $isNotEligible = !$tpEligible || !$projectsEligible || !$reportUploaded;

            if ($isNotEligible) {
                // Calculer ce qui manque
                $missing = [];
                if (!$tpEligible) {
                    $missing[] = ($minTPRequired - $tpValidated) . ' TP';
                }
                if (!$projectsEligible) {
                    $missing[] = ($minProjectsRequired - $projectsCompleted) . ' projet(s)';
                }
                if (!$reportUploaded) {
                    $missing[] = 'Rapport';
                }

                $notEligibleStudents[] = [
                    'student' => $student,
                    'tp_validated' => $tpValidated,
                    'tp_required' => $minTPRequired,
                    'tp_eligible' => $tpEligible,
                    'projects_completed' => $projectsCompleted,
                    'projects_required' => $minProjectsRequired,
                    'projects_eligible' => $projectsEligible,
                    'report' => $report,
                    'report_uploaded' => $reportUploaded,
                    'missing' => $missing,
                ];
            }
        }

        // Statistiques
        $stats = [
            'total_not_eligible' => count($notEligibleStudents),
            'missing_tp' => collect($notEligibleStudents)->where('tp_eligible', false)->count(),
            'missing_projects' => collect($notEligibleStudents)->where('projects_eligible', false)->count(),
            'missing_report' => collect($notEligibleStudents)->where('report_uploaded', false)->count(),
        ];

        return view('admin.certificats.not-eligible', [
            'notEligibleStudents' => $notEligibleStudents,
            'stats' => $stats,
            'minTPRequired' => $minTPRequired,
            'minProjectsRequired' => $minProjectsRequired,
        ]);
    }

    /**
     * Afficher la page des rapports et analytics
     */
    public function rapports(): View
    {
        // Récupérer les statistiques réelles
        $totalStudents = DB::table('students')->count();
        $totalFormations = DB::table('formations')->count();

        // Vérifier si la table payments existe
        $totalPayments = 0;
        $monthlyExports = 0;

        if (Schema::hasTable('payments')) {
            $totalPayments = DB::table('payments')->where('status', 'completed')->sum('amount');
            $monthlyExports = DB::table('payments')
                ->whereMonth('created_at', now()->month)
                ->count();
        } elseif (Schema::hasTable('factures')) {
            $totalPayments = DB::table('factures')->sum('montant');
            $monthlyExports = DB::table('factures')
                ->whereMonth('created_at', now()->month)
                ->count();
        }

        $totalTPs = DB::table('tp_assignments')->count();

        $stats = [
            'total_reports' => $totalStudents + $totalFormations,
            'monthly_exports' => $monthlyExports,
            'active_analytics' => 12,
            'scheduled_reports' => 8,
            'total_students' => $totalStudents,
            'total_formations' => $totalFormations,
            'total_payments' => $totalPayments,
            'total_tps' => $totalTPs,
        ];

        return view('admin.rapports.index', compact('stats'));
    }

    /**
     * Afficher les analytics
     */
    public function analytics(): View
    {
        return view('admin.rapports.analytics');
    }

    /**
     * Afficher les exports
     */
    public function exports(): View
    {
        return view('admin.rapports.exports');
    }

    /**
     * Générer un rapport
     */
    public function generateReport(Request $request)
    {
        $type = $request->input('type');

        try {
            $reportData = [];

            switch ($type) {
                case 'students':
                    $reportData = $this->generateStudentsReport();
                    break;

                case 'formations':
                    $reportData = $this->generateFormationsReport();
                    break;

                case 'financial':
                    $reportData = $this->generateFinancialReport();
                    break;

                case 'activities':
                    $reportData = $this->generateActivitiesReport();
                    break;

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Type de rapport non reconnu'
                    ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Rapport ' . $type . ' généré avec succès',
                'data' => $reportData
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur génération rapport: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du rapport'
            ], 500);
        }
    }

    /**
     * Générer le rapport des étudiants
     */
    private function generateStudentsReport()
    {
        $students = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->select(
                'students.*',
                'users.email',
                DB::raw('(SELECT COUNT(*) FROM tp_assignments WHERE student_id = students.id AND status = "validated") as tp_validated'),
                DB::raw('(SELECT COUNT(*) FROM projects WHERE user_id = students.user_id AND status = "valide") as projects_completed')
            )
            ->get();

        $byFormation = $students->groupBy('program')->map(function ($group) {
            return [
                'total' => $group->count(),
                'active' => $group->where('status', 'active')->count(),
            ];
        });

        return [
            'total_students' => $students->count(),
            'active_students' => $students->where('status', 'active')->count(),
            'by_formation' => $byFormation,
            'avg_tp_validated' => round($students->avg('tp_validated'), 2),
            'avg_projects_completed' => round($students->avg('projects_completed'), 2),
        ];
    }

    /**
     * Générer le rapport des formations
     */
    private function generateFormationsReport()
    {
        $formations = DB::table('formations')
            ->select('*')
            ->get();

        $stats = [];
        foreach ($formations as $formation) {
            $enrolledCount = DB::table('students')
                ->where('program', $formation->module)
                ->count();

            $stats[] = [
                'name' => $formation->title,
                'module' => $formation->module,
                'enrolled' => $enrolledCount,
                'created_at' => $formation->created_at,
            ];
        }

        return [
            'total_formations' => $formations->count(),
            'formations' => $stats,
        ];
    }

    /**
     * Générer le rapport financier
     */
    private function generateFinancialReport()
    {
        $totalRevenue = 0;
        $monthlyRevenue = 0;
        $pendingPayments = 0;
        $totalInvoices = 0;

        // Vérifier les tables disponibles
        if (Schema::hasTable('payments')) {
            $totalRevenue = DB::table('payments')
                ->where('status', 'completed')
                ->sum('amount');

            $monthlyRevenue = DB::table('payments')
                ->where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('amount');

            $pendingPayments = DB::table('payments')
                ->where('status', 'pending')
                ->sum('amount');
        }

        if (Schema::hasTable('factures')) {
            $totalInvoices = DB::table('factures')->sum('montant');
        }

        return [
            'total_revenue' => $totalRevenue,
            'monthly_revenue' => $monthlyRevenue,
            'pending_payments' => $pendingPayments,
            'total_invoices' => $totalInvoices,
            'balance' => $totalInvoices - $totalRevenue,
        ];
    }

    /**
     * Générer le rapport des activités
     */
    private function generateActivitiesReport()
    {
        $totalTPs = DB::table('tp_assignments')->count();
        $validatedTPs = DB::table('tp_assignments')->where('status', 'validated')->count();
        $pendingTPs = DB::table('tp_assignments')->where('status', 'pending')->count();

        $totalProjects = DB::table('projects')->count();
        $completedProjects = DB::table('projects')->where('status', 'valide')->count();

        return [
            'total_tps' => $totalTPs,
            'validated_tps' => $validatedTPs,
            'pending_tps' => $pendingTPs,
            'total_projects' => $totalProjects,
            'completed_projects' => $completedProjects,
            'completion_rate_tps' => $totalTPs > 0 ? round(($validatedTPs / $totalTPs) * 100, 2) : 0,
            'completion_rate_projects' => $totalProjects > 0 ? round(($completedProjects / $totalProjects) * 100, 2) : 0,
        ];
    }

    /**
     * Afficher la page détaillée du rapport financier
     */
    public function rapportFinancier(): View
    {
        // Données financières
        $financial = $this->generateFinancialReport();

        // Si pas de données réelles, utiliser des données de démonstration
        $hasRealData = Schema::hasTable('payments') && DB::table('payments')->count() > 0;

        if (!$hasRealData) {
            // Données de démonstration
            $financial = [
                'total_revenue' => 15750000,
                'monthly_revenue' => 2500000,
                'pending_payments' => 1250000,
                'total_invoices' => 18500000,
                'balance' => 2750000,
            ];
        }

        // Revenus mensuels (12 derniers mois)
        $monthlyRevenues = [];
        if ($hasRealData) {
            for ($i = 11; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $revenue = DB::table('payments')
                    ->where('status', 'completed')
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('amount');
                $monthlyRevenues[] = $revenue;
            }
        } else {
            // Données de démonstration pour les 12 derniers mois
            $monthlyRevenues = [850000, 920000, 1050000, 1150000, 1300000, 1450000, 1250000, 1400000, 1550000, 1650000, 1800000, 2500000];
        }

        // Répartition des paiements
        if ($hasRealData) {
            $completedCount = DB::table('payments')->where('status', 'completed')->count();
            $pendingCount = DB::table('payments')->where('status', 'pending')->count();
            $failedCount = DB::table('payments')->whereIn('status', ['failed', 'cancelled'])->count();
        } else {
            // Données de démonstration
            $completedCount = 45;
            $pendingCount = 8;
            $failedCount = 2;
        }

        $paymentDistribution = [$completedCount, $pendingCount, $failedCount];

        // Dernières transactions
        if ($hasRealData) {
            $transactions = DB::table('payments')
                ->leftJoin('students', 'payments.student_id', '=', 'students.id')
                ->select(
                    'payments.*',
                    DB::raw("CONCAT(students.first_name, ' ', students.last_name) as student_name"),
                    'students.program as formation'
                )
                ->orderBy('payments.created_at', 'desc')
                ->limit(20)
                ->get();
        } else {
            // Données de démonstration
            $transactions = collect([
                (object)[
                    'id' => 1,
                    'created_at' => now()->subDays(2),
                    'student_name' => 'Marie KOUASSI',
                    'formation' => 'Community Management',
                    'amount' => 350000,
                    'status' => 'completed'
                ],
                (object)[
                    'id' => 2,
                    'created_at' => now()->subDays(5),
                    'student_name' => 'Jean Baptiste ENOKOU',
                    'formation' => 'Design Graphique',
                    'amount' => 350000,
                    'status' => 'completed'
                ],
                (object)[
                    'id' => 3,
                    'created_at' => now()->subDays(7),
                    'student_name' => 'Fatou Rebecca ZIRE',
                    'formation' => 'Community Management',
                    'amount' => 350000,
                    'status' => 'pending'
                ],
                (object)[
                    'id' => 4,
                    'created_at' => now()->subDays(10),
                    'student_name' => 'Mathieu TÉYOTONMIN',
                    'formation' => 'Gestion Informatique',
                    'amount' => 350000,
                    'status' => 'completed'
                ],
                (object)[
                    'id' => 5,
                    'created_at' => now()->subDays(12),
                    'student_name' => 'Bianca DEFO',
                    'formation' => 'Design Graphique',
                    'amount' => 350000,
                    'status' => 'completed'
                ],
            ]);
        }

        return view('admin.rapports.financier', compact('financial', 'monthlyRevenues', 'paymentDistribution', 'transactions'));
    }

    /**
     * Afficher la page détaillée du rapport formations
     */
    public function rapportFormations(): View
    {
        // Vue d'ensemble
        $totalFormations = DB::table('formations')->count();
        $totalStudents = DB::table('students')->count();
        $totalModules = 0; // À implémenter selon la structure

        // Taux de réussite moyen
        $avgSuccessRate = DB::table('students')
            ->leftJoin('tp_assignments', 'students.id', '=', 'tp_assignments.student_id')
            ->selectRaw('COUNT(CASE WHEN tp_assignments.status = "validated" THEN 1 END) * 100.0 / NULLIF(COUNT(tp_assignments.id), 0) as success_rate')
            ->value('success_rate') ?? 0;

        $overview = [
            'total_formations' => $totalFormations,
            'total_students' => $totalStudents,
            'avg_success_rate' => $avgSuccessRate,
            'total_modules' => $totalModules,
        ];

        // Détails par formation
        $formations = DB::table('students')
            ->select(
                'program as name',
                DB::raw('COUNT(DISTINCT students.id) as students_count'),
                DB::raw('COUNT(CASE WHEN tp_assignments.status = "validated" THEN 1 END) as completed_tps'),
                DB::raw('COUNT(CASE WHEN tp_assignments.status IN ("pending", "submitted") THEN 1 END) as pending_tps'),
                DB::raw('ROUND(COUNT(CASE WHEN tp_assignments.status = "validated" THEN 1 END) * 100.0 / NULLIF(COUNT(tp_assignments.id), 0), 1) as tp_completion_rate'),
                DB::raw('ROUND(COUNT(CASE WHEN tp_assignments.status = "validated" THEN 1 END) * 100.0 / NULLIF(COUNT(tp_assignments.id), 0), 1) as success_rate'),
                DB::raw('"Intermédiaire" as level'),
                DB::raw('6 as duration'),
                DB::raw('15 as avg_grade')
            )
            ->leftJoin('tp_assignments', 'students.id', '=', 'tp_assignments.student_id')
            ->whereNotNull('program')
            ->groupBy('program')
            ->get();

        // Ajouter des modules fictifs pour démonstration
        foreach ($formations as $formation) {
            $formation->modules = collect([]);
        }

        // Données pour graphiques
        $formationsNames = $formations->pluck('name')->toArray();
        $formationsStudents = $formations->pluck('students_count')->toArray();
        $formationsSuccessRates = $formations->pluck('success_rate')->toArray();

        return view('admin.rapports.formations', compact('overview', 'formations', 'formationsNames', 'formationsStudents', 'formationsSuccessRates'));
    }

    /**
     * Télécharger un rapport
     */
    public function downloadReport($type)
    {
        // TODO: Implémenter la logique de téléchargement de rapports

        return redirect()->back()->with('success', 'Téléchargement du rapport ' . $type);
    }

    /**
     * Afficher les étudiants à jour avec leurs paiements
     */
    public function paiementsAJour(): View
    {
        $students = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->select(
                'students.*',
                'users.email',
                DB::raw('"À jour" as payment_status'),
                DB::raw('350000 as amount_paid'),
                DB::raw('350000 as total_amount'),
                DB::raw('0 as remaining')
            )
            ->where('students.status', 'active')
            ->orderBy('students.created_at', 'desc')
            ->limit(50)
            ->get();

        $stats = [
            'total' => $students->count(),
            'percentage' => 100,
            'total_amount' => $students->count() * 350000,
        ];

        return view('admin.paiements.a-jour', compact('students', 'stats'));
    }

    /**
     * Afficher les étudiants avec paiements à solder
     */
    public function paiementsASolder(): View
    {
        // Données de démonstration pour les paiements partiels
        $students = collect([
            (object)[
                'id' => 1,
                'first_name' => 'Jean',
                'last_name' => 'KOUADIO',
                'email' => 'jean.kouadio@example.com',
                'program' => 'Design Graphique',
                'payment_status' => 'Partiel',
                'amount_paid' => 200000,
                'total_amount' => 350000,
                'remaining' => 150000,
                'created_at' => now()->subMonths(2),
            ],
            (object)[
                'id' => 2,
                'first_name' => 'Marie',
                'last_name' => 'BAMBA',
                'email' => 'marie.bamba@example.com',
                'program' => 'Community Management',
                'payment_status' => 'Partiel',
                'amount_paid' => 175000,
                'total_amount' => 350000,
                'remaining' => 175000,
                'created_at' => now()->subMonths(1),
            ],
        ]);

        $stats = [
            'total' => $students->count(),
            'total_paid' => $students->sum('amount_paid'),
            'total_remaining' => $students->sum('remaining'),
        ];

        return view('admin.paiements.a-solder', compact('students', 'stats'));
    }

    /**
     * Afficher les étudiants avec reste à payer
     */
    public function paiementsResteAPayer(): View
    {
        // Données de démonstration pour les paiements non effectués
        $students = collect([
            (object)[
                'id' => 3,
                'first_name' => 'Kofi',
                'last_name' => 'ASSANE',
                'email' => 'mae2pcmk2025@gmail.com',
                'program' => 'Gestion Informatique',
                'payment_status' => 'Non payé',
                'amount_paid' => 0,
                'total_amount' => 350000,
                'remaining' => 350000,
                'created_at' => now()->subWeeks(2),
            ],
        ]);

        $stats = [
            'total' => $students->count(),
            'total_amount_due' => $students->sum('remaining'),
        ];

        return view('admin.paiements.reste-a-payer', compact('students', 'stats'));
    }

    /**
     * Envoyer un email de relance de paiement à un étudiant
     */
    public function sendPaymentReminder($id)
    {
        try {
            // Récupérer les informations de l'étudiant depuis la base de données
            $student = DB::table('students')
                ->leftJoin('users', 'students.user_id', '=', 'users.id')
                ->where('students.id', $id)
                ->select(
                    'students.*',
                    'users.email'
                )
                ->first();

            // Si l'étudiant n'existe pas en base, utiliser les données de démonstration
            if (!$student) {
                // Étudiant de démonstration pour les tests
                if ($id == 3) {
                    $studentData = [
                        'first_name' => 'Kofi',
                        'last_name' => 'ASSANE',
                        'formation' => 'Gestion Informatique',
                        'amount_paid' => 0,
                        'remaining' => 350000,
                        'created_at' => now()->subWeeks(2),
                    ];

                    $emailTo = 'mae2pcmk2025@gmail.com';
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Étudiant introuvable'
                    ], 404);
                }
            } else {
                // Étudiant réel trouvé en base
                if (!$student->email) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Aucun email associé à cet étudiant'
                    ], 400);
                }

                $studentData = [
                    'first_name' => $student->first_name ?? 'Étudiant',
                    'last_name' => $student->last_name ?? '',
                    'formation' => $student->program ?? 'Non défini',
                    'amount_paid' => 0,
                    'remaining' => 350000,
                    'created_at' => $student->created_at,
                ];

                $emailTo = $student->email;
            }

            // Envoyer l'email
            Mail::send('emails.payment_reminder', ['student' => $studentData], function ($message) use ($emailTo) {
                $message->to($emailTo)
                    ->subject('Rappel de Paiement - École Virtuelle des Créatifs');
            });

            Log::info('Email de relance de paiement envoyé', [
                'student_id' => $id,
                'email' => $emailTo,
                'nom' => $studentData['first_name'] . ' ' . $studentData['last_name']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email de relance envoyé avec succès à ' . $emailTo
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email de relance: ' . $e->getMessage(), [
                'student_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher la page des paramètres
     */
    public function parametres(): View
    {
        $adminData = DB::table('admins')->where('id', session('admin_id'))->first();

        // S'assurer que toutes les propriétés existent avec des valeurs par défaut
        $admin = (object) [
            'id' => $adminData->id,
            'name' => $adminData->name,
            'email' => $adminData->email,
            'role' => $adminData->role ?? 'assistant',
            'phone' => $adminData->phone ?? null,
            'bio' => $adminData->bio ?? null,
            'photo' => $adminData->photo ?? null,
            'is_active' => $adminData->is_active ?? true,
            'created_at' => $adminData->created_at,
            'updated_at' => $adminData->updated_at ?? null,
            'last_login_at' => $adminData->last_login_at ?? null,
        ];

        // Statistiques système
        $systemStats = [
            'total_users' => DB::table('users')->count(),
            'total_students' => DB::table('students')->count(),
            'total_admins' => DB::table('admins')->count(),
            'database_size' => $this->getDatabaseSize(),
            'storage_used' => $this->getStorageSize(),
        ];

        return view('admin.parametres.index', compact('admin', 'systemStats'));
    }

    /**
     * Mettre à jour les paramètres
     */
    public function updateParametres(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
        ]);

        try {
            DB::table('admins')
                ->where('id', session('admin_id'))
                ->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? null,
                    'bio' => $validated['bio'] ?? null,
                    'updated_at' => now(),
                ]);

            return redirect()->back()->with('success', 'Paramètres mis à jour avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur mise à jour paramètres: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour');
        }
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'new_password.required' => 'Le nouveau mot de passe est obligatoire.',
            'new_password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
            'new_password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        try {
            $admin = DB::table('admins')->where('id', session('admin_id'))->first();

            // Vérifier le mot de passe actuel
            if (!Hash::check($validated['current_password'], $admin->password)) {
                return redirect()->back()->with('error', 'Le mot de passe actuel est incorrect.');
            }

            // Mettre à jour le mot de passe
            DB::table('admins')
                ->where('id', session('admin_id'))
                ->update([
                    'password' => Hash::make($validated['new_password']),
                    'updated_at' => now(),
                ]);

            Log::info('Mot de passe mis à jour', [
                'admin_id' => session('admin_id'),
            ]);

            return redirect()->back()->with('success', 'Mot de passe mis à jour avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur mise à jour mot de passe: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour du mot de passe');
        }
    }

    /**
     * Paramètres système
     */
    public function systemSettings(): View
    {
        return view('admin.parametres.system');
    }

    /**
     * Paramètres de sécurité
     */
    public function securitySettings(): View
    {
        return view('admin.parametres.security');
    }

    /**
     * Paramètres de notifications
     */
    public function notificationSettings(): View
    {
        $admin = DB::table('admins')->where('id', session('admin_id'))->first();

        // Récupérer les préférences depuis le champ JSON (si existe)
        $savedPreferences = [];
        if (isset($admin->notification_preferences)) {
            $savedPreferences = json_decode($admin->notification_preferences, true) ?? [];
        }

        // Récupérer les préférences de notifications (par défaut toutes actives)
        $notifications = [
            'new_registrations' => $savedPreferences['new_registrations'] ?? true,
            'new_payments' => $savedPreferences['new_payments'] ?? true,
            'documents_submitted' => $savedPreferences['documents_submitted'] ?? true,
            'projects_completed' => $savedPreferences['projects_completed'] ?? false,
            'system_alerts' => $savedPreferences['system_alerts'] ?? true,
            'backups' => $savedPreferences['backups'] ?? true,
            'weekly_reports' => $savedPreferences['weekly_reports'] ?? false,
            'team_activities' => $savedPreferences['team_activities'] ?? false,
        ];

        return view('admin.parametres.notifications', compact('notifications'));
    }

    /**
     * Mettre à jour les préférences de notifications
     */
    public function updateNotifications(Request $request)
    {
        try {
            $adminId = session('admin_id');

            // Récupérer les préférences actuelles
            $admin = DB::table('admins')->where('id', $adminId)->first();
            $currentPreferences = [];

            if (isset($admin->notification_preferences)) {
                $currentPreferences = json_decode($admin->notification_preferences, true) ?? [];
            }

            // Mettre à jour avec les nouvelles données
            $allData = $request->all();
            foreach ($allData as $key => $value) {
                if ($key !== '_token') {
                    $currentPreferences[$key] = (bool)$value;
                }
            }

            // Sauvegarder en JSON
            DB::table('admins')
                ->where('id', $adminId)
                ->update([
                    'notification_preferences' => json_encode($currentPreferences),
                    'updated_at' => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Préférences enregistrées'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Paramètres de sauvegarde
     */
    public function backupSettings(): View
    {
        return view('admin.parametres.backup');
    }

    /**
     * Créer une sauvegarde
     */
    public function createBackup()
    {
        // TODO: Implémenter la logique de sauvegarde
        return redirect()->back()->with('success', 'Sauvegarde créée avec succès');
    }

    /**
     * Logs système
     */
    public function systemLogs(): View
    {
        return view('admin.parametres.logs');
    }

    /**
     * Obtenir la taille de la base de données
     */
    private function getDatabaseSize(): string
    {
        try {
            $dbName = config('database.connections.mysql.database');
            $result = DB::select("
                SELECT
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.TABLES
                WHERE table_schema = ?
            ", [$dbName]);

            return ($result[0]->size_mb ?? 0) . ' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    /**
     * Obtenir la taille du stockage
     */
    private function getStorageSize(): string
    {
        try {
            $storagePath = storage_path('app');
            $size = 0;

            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($storagePath)) as $file) {
                if ($file->isFile()) {
                    $size += $file->getSize();
                }
            }

            return round($size / 1024 / 1024, 2) . ' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    /**
     * Afficher les projets en attente de validation
     */
    public function projetsPending()
    {
        // Récupérer tous les projets avec statut 'termine' (soumis par étudiants, en attente de validation)
        $pendingProjects = DB::table('projects')
            ->where('projects.status', 'termine')
            ->leftJoin('users', 'projects.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->select(
                'projects.*',
                'users.email as student_email',
                'students.first_name',
                'students.last_name',
                'students.program as formation',
                'students.profile_photo'
            )
            ->orderBy('projects.updated_at', 'desc')
            ->get();

        // Pour chaque projet, récupérer les fichiers/images associés
        $tpSubmissions = $pendingProjects->map(function ($project) {
            // Récupérer les images du projet depuis la table project_images
            $files = DB::table('project_images')
                ->where('project_id', $project->id)
                ->select('id', 'image_path', 'created_at')
                ->get();

            // Ajouter les fichiers au projet
            $project->files = $files;
            $project->submitted_at = $project->updated_at; // Date de dernière modification = date soumission

            return $project;
        });

        // Calculer les statistiques
        $stats = [
            'total' => $pendingProjects->count(),
            'design_graphique' => $pendingProjects->where('formation', 'Design Graphique')->count(),
            'community_management' => $pendingProjects->where('formation', 'Community Management')->count(),
            'gestion_informatique' => $pendingProjects->where('formation', 'Gestion Informatique')->count(),
            'intelligence_artificielle' => $pendingProjects->where('formation', 'Intelligence Artificielle')->count(),
        ];

        return view('admin.projets.pending', [
            'tpSubmissions' => $tpSubmissions,
            'stats' => $stats
        ]);
    }

    /**
     * Page pour envoyer/assigner des projets aux étudiants
     */
    public function projetsToSend()
    {
        // Récupérer tous les étudiants actifs avec leurs informations
        $students = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('students.status', 'active')
            ->select(
                'students.*',
                'users.email'
            )
            ->get();

        // Normaliser les formations pour cohérence
        $students = $students->map(function ($student) {
            // Normaliser la formation
            if ($student->program) {
                $normalized = match (strtolower(str_replace([' ', '_', '-'], '', $student->program))) {
                    'designgraphique' => 'Design Graphique',
                    'communitymanagement' => 'Community Management',
                    'gestioninformatique' => 'Gestion Informatique',
                    'intelligenceartificielle' => 'Intelligence Artificielle',
                    default => $student->program
                };
                $student->program_normalized = $normalized;
            } else {
                $student->program_normalized = 'Sans formation';
            }
            return $student;
        });

        // Calculer les statistiques par formation normalisée
        $stats = [
            'total_students' => $students->count(),
            'design_graphique' => $students->where('program_normalized', 'Design Graphique')->count(),
            'community_management' => $students->where('program_normalized', 'Community Management')->count(),
            'gestion_informatique' => $students->where('program_normalized', 'Gestion Informatique')->count(),
            'intelligence_artificielle' => $students->where('program_normalized', 'Intelligence Artificielle')->count(),
            'sans_formation' => $students->where('program_normalized', 'Sans formation')->count(),
        ];

        // Récupérer tous les étudiants pour la liste
        $all_students = $students;

        return view('admin.projets.to-send', [
            'students' => $students,
            'all_students' => $all_students,
            'stats' => $stats
        ]);
    }

    /**
     * Page pour voir tous les projets
     */
    public function projetsAll()
    {
        // Récupérer tous les projets avec les informations des étudiants
        $projects = DB::table('projects')
            ->leftJoin('users', 'projects.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->select(
                'projects.*',
                'users.email as student_email',
                'students.first_name',
                'students.last_name',
                'students.program as formation',
                'students.profile_photo'
            )
            ->orderBy('projects.created_at', 'desc')
            ->get();

        // Pour chaque projet, récupérer le nombre d'images
        $projects = $projects->map(function ($project) {
            $project->images_count = DB::table('project_images')
                ->where('project_id', $project->id)
                ->count();
            return $project;
        });

        // Calculer les statistiques
        $stats = [
            'total' => $projects->count(),
            'en_cours' => $projects->where('status', 'en_cours')->count(),
            'termine' => $projects->where('status', 'termine')->count(),
            'valide' => $projects->where('status', 'valide')->count(),
            'rejete' => $projects->where('status', 'rejete')->count(),
        ];

        return view('admin.projets.all', [
            'projects' => $projects,
            'stats' => $stats
        ]);
    }

    /**
     * Afficher les détails d'un projet soumis
     */
    public function showTpDetails($id)
    {
        // Récupérer le projet avec les informations de l'étudiant
        $tp = DB::table('projects')
            ->leftJoin('users', 'projects.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->where('projects.id', $id)
            ->select(
                'projects.*',
                'users.email as student_email',
                'students.first_name',
                'students.last_name',
                'students.program as formation',
                'students.profile_photo',
                'students.phone as student_phone',
                'students.student_id as student_number'
            )
            ->first();

        if (!$tp) {
            return redirect()->route('admin.projets.pending')
                ->with('error', 'Projet non trouvé');
        }

        // Récupérer les images du projet
        $submittedFiles = DB::table('project_images')
            ->where('project_id', $id)
            ->select(
                'id',
                'file_path',
                'original_name as file_name',
                'file_size'
            )
            ->get();

        // Pas de fichiers d'assignation pour les projets (contrairement aux TP)
        $assignmentFiles = collect();

        // Pas d'admin assigné pour les projets (créés par les étudiants)
        $assignedBy = null;

        return view('admin.projets.show', [
            'tp' => $tp,
            'submittedFiles' => $submittedFiles,
            'assignmentFiles' => $assignmentFiles,
            'assignedBy' => $assignedBy
        ]);
    }

    /**
     * Traiter l'envoi/assignation de projets aux étudiants
     */
    public function sendProjects(Request $request)
    {
        // Validation
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'formation' => 'required|string',
            'tags' => 'nullable|string',
            'software_used' => 'nullable|string',
            'reference_link' => 'nullable|url',
            'students' => 'nullable|array',
            'students.*' => 'exists:students,id'
        ]);

        // Préparer les données du projet
        $projectData = [
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
            'tags' => $request->tags,
            'link' => $request->reference_link,
            'software_used' => json_encode($request->software_used ? array_map('trim', explode(',', $request->software_used)) : []),
            'status' => 'en_cours',
            'created_at' => now(),
            'updated_at' => now()
        ];

        $createdCount = 0;
        $emailsSent = 0;
        $errors = [];

        // Déterminer les étudiants cibles
        $targetStudents = [];

        if ($request->formation === 'all') {
            // Tous les étudiants actifs
            $targetStudents = DB::table('students')
                ->where('status', 'active')
                ->pluck('id')
                ->toArray();
        } elseif ($request->has('students') && count($request->students) > 0) {
            // Étudiants spécifiques sélectionnés
            $targetStudents = $request->students;
        } else {
            // Tous les étudiants de la formation sélectionnée
            // Gérer les différents formats de noms de formation
            $formation = $request->formation;

            if ($formation === 'Sans formation') {
                // Étudiants sans formation (program NULL ou vide)
                $targetStudents = DB::table('students')
                    ->where('status', 'active')
                    ->where(function ($query) {
                        $query->whereNull('program')
                            ->orWhere('program', '');
                    })
                    ->pluck('id')
                    ->toArray();
            } else {
                // Recherche flexible pour gérer les variations (minuscules, underscores, etc.)
                $normalizedSearch = strtolower(str_replace([' ', '_', '-'], '', $formation));

                $targetStudents = DB::table('students')
                    ->where('status', 'active')
                    ->get()
                    ->filter(function ($student) use ($normalizedSearch) {
                        if (!$student->program) return false;
                        $studentProgramNormalized = strtolower(str_replace([' ', '_', '-'], '', $student->program));
                        return $studentProgramNormalized === $normalizedSearch;
                    })
                    ->pluck('id')
                    ->toArray();
            }
        }

        // Créer un projet pour chaque étudiant cible
        foreach ($targetStudents as $studentId) {
            try {
                // Récupérer les informations de l'étudiant
                $student = DB::table('students')
                    ->leftJoin('users', 'students.user_id', '=', 'users.id')
                    ->where('students.id', $studentId)
                    ->select('students.*', 'users.email')
                    ->first();

                if (!$student) {
                    continue;
                }

                // Créer le projet pour cet étudiant
                $projectData['user_id'] = $student->user_id;

                $projectId = DB::table('projects')->insertGetId($projectData);
                $createdCount++;

                // Envoyer un email de notification (optionnel)
                try {
                    Mail::send('emails.project_assigned', [
                        'student' => $student,
                        'project' => (object) $projectData
                    ], function ($message) use ($student, $projectData) {
                        $message->to($student->email)
                            ->subject('Nouveau Projet : ' . $projectData['title']);
                    });
                    $emailsSent++;
                } catch (\Exception $e) {
                    Log::error('Erreur envoi email projet: ' . $e->getMessage());
                }
            } catch (\Exception $e) {
                $errors[] = "Erreur pour l'étudiant ID {$studentId}: " . $e->getMessage();
                Log::error("Erreur création projet pour étudiant {$studentId}: " . $e->getMessage());
            }
        }

        // Message de succès
        $message = "Projet assigné avec succès à {$createdCount} étudiant(s)";
        if ($emailsSent > 0) {
            $message .= ". {$emailsSent} email(s) de notification envoyé(s)";
        }
        if (!empty($errors)) {
            $message .= ". Attention: " . count($errors) . " erreur(s) rencontrée(s)";
        }

        return redirect()->route('admin.projets.to-send')
            ->with('success', $message);
    }

    /**
     * Afficher la liste complète des activités récentes (soumissions de TP)
     */
    public function activites(): View
    {
        // Calculer les statistiques sur toutes les activités
        $allActivities = DB::table('tp_assignments')
            ->whereIn('status', ['submitted', 'pending', 'validated', 'rejected'])
            ->get();

        $stats = [
            'total' => $allActivities->count(),
            'en_attente' => $allActivities->where('status', 'pending')->count() + $allActivities->where('status', 'submitted')->count(),
            'valides' => $allActivities->where('status', 'validated')->count(),
            'rejetes' => $allActivities->where('status', 'rejected')->count(),
        ];

        // Récupérer uniquement les étudiants qui ont des activités
        $studentsWithActivities = DB::table('students')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('tp_assignments', function($join) {
                $join->on('students.id', '=', 'tp_assignments.student_id')
                     ->whereIn('tp_assignments.status', ['submitted', 'pending', 'validated', 'rejected']);
            })
            ->select(
                'students.id as student_id',
                'students.first_name',
                'students.last_name',
                'students.profile_photo',
                'students.program as formation',
                'users.email',
                DB::raw('MAX(tp_assignments.updated_at) as last_activity'),
                DB::raw('COUNT(tp_assignments.id) as total_activities'),
                DB::raw('SUM(CASE WHEN tp_assignments.status = "validated" THEN 1 ELSE 0 END) as validated_count'),
                DB::raw('SUM(CASE WHEN tp_assignments.status IN ("pending", "submitted") THEN 1 ELSE 0 END) as pending_count'),
                DB::raw('SUM(CASE WHEN tp_assignments.status = "rejected" THEN 1 ELSE 0 END) as rejected_count')
            )
            ->groupBy('students.id', 'students.first_name', 'students.last_name', 'students.profile_photo', 'students.program', 'users.email')
            ->orderByRaw('MAX(tp_assignments.updated_at) desc')
            ->paginate(10);

        // Pour chaque étudiant, récupérer ses 3 dernières activités
        foreach ($studentsWithActivities as $student) {
            $student->recent_activities = DB::table('tp_assignments')
                ->select('id', 'title', 'status', 'submitted_at', 'updated_at')
                ->where('student_id', $student->student_id)
                ->whereIn('status', ['submitted', 'pending', 'validated', 'rejected'])
                ->orderBy('updated_at', 'desc')
                ->limit(3)
                ->get();
        }

        return view('admin.activites.index', compact('studentsWithActivities', 'stats'));
    }
}
