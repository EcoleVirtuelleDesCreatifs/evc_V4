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
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.dashboard'); 
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
            $statsByCategory = $formations->groupBy(function($formation) {
                return $formation->category->module ?? 'Autre';
            })->map(function($moduleFormations, $module) {
                return $moduleFormations->groupBy(function($formation) {
                    return $formation->category->name ?? 'Sans catégorie';
                })->map(function($categoryFormations, $categoryName) {
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
                $studentIds = array_filter($validatedData['student_ids'], function($id) {
                    return !empty($id) && is_numeric($id);
                });
                $formation->students()->sync($studentIds);
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
        return view('admin.formations.edit', compact('formation', 'categories', 'students'));
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
                $studentIds = array_filter($validatedData['student_ids'], function($id) {
                    return !empty($id) && is_numeric($id);
                });
                $formation->students()->sync($studentIds);
            } else {
                $formation->students()->detach();
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
        // Charger les catégories avec leurs statistiques
        $categoriesData = Category::withCount('formations')->get();
        
        $categories = $categoriesData->map(function($category) {
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
            $count = $items->filter(function($item) use ($espace) {
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
        // Récupérer tous les TP en attente de validation
        $pendingTps = DB::table('tp_assignments')
            ->where('tp_assignments.status', 'pending')
            ->join('users', 'tp_assignments.student_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->select(
                'tp_assignments.*',
                'users.name as student_name',
                'users.email as student_email',
                'students.program as formation',
                'students.profile_photo'
            )
            ->orderBy('tp_assignments.created_at', 'desc')
            ->get();
        
        // Grouper les TP par étudiant et formation
        $studentsByFormation = $pendingTps->groupBy('formation')->map(function($formationTps) {
            return $formationTps->groupBy('student_id')->map(function($studentTps) {
                $firstTp = $studentTps->first();
                return [
                    'student_id' => $firstTp->student_id,
                    'student_name' => $firstTp->student_name,
                    'student_email' => $firstTp->student_email,
                    'profile_photo' => $firstTp->profile_photo,
                    'formation' => $firstTp->formation,
                    'tps' => $studentTps->map(function($tp) {
                        return [
                            'id' => $tp->id,
                            'tp_title' => $tp->tp_title,
                            'submitted_at' => $tp->submitted_at,
                            'file_path' => $tp->file_path,
                            'comments' => $tp->comments,
                        ];
                    })->toArray(),
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
            ->select(
                'students.*',
                'students.program as formation',  // Alias pour compatibilité avec la vue
                'users.name as user_name',
                'users.name as prenom',  // Alias temporaire depuis name
                'users.name as nom',      // Alias temporaire depuis name
                'users.email as user_email'
            )
            ->get();
        
        // Traiter les noms pour séparer prénom et nom si possible
        $students = $students->map(function($student) {
            $nameParts = explode(' ', $student->user_name, 2);
            $student->prenom = $nameParts[0] ?? $student->user_name;
            $student->nom = $nameParts[1] ?? '';
            return $student;
        });
        
        // Calculer les statistiques
        $stats = [
            'total_formations' => $formations->count(),
            'total_students' => $students->count(),
            'design_graphique' => $students->filter(function($student) {
                return str_contains(strtolower($student->program ?? ''), 'design');
            })->count(),
            'community_management' => $students->filter(function($student) {
                return str_contains(strtolower($student->program ?? ''), 'community');
            })->count(),
            'gestion_informatique' => $students->filter(function($student) {
                return str_contains(strtolower($student->program ?? ''), 'informatique');
            })->count(),
            'intelligence_artificielle' => $students->filter(function($student) {
                return str_contains(strtolower($student->program ?? ''), 'intelligence') || str_contains(strtolower($student->program ?? ''), 'ia');
            })->count(),
        ];
        
        return view('admin.travaux.to-send', [
            'formations' => $formations,
            'students' => $students,
            'all_students' => $students,  // Alias pour compatibilité avec la vue
            'stats' => $stats
        ]);
    }

    public function travauxAssigned()
    {
        // Récupérer tous les TP assignés
        $assignments = DB::table('tp_assignments')
            ->join('users', 'tp_assignments.student_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->select(
                'tp_assignments.*',
                'users.name as student_name',
                'users.email as student_email',
                'students.program as formation',
                'students.profile_photo'
            )
            ->orderBy('tp_assignments.created_at', 'desc')
            ->get();
        
        // Ajouter student_first_name et student_last_name en parsant student_name
        $assignments = $assignments->map(function($assignment) {
            $nameParts = explode(' ', $assignment->student_name ?? '', 2);
            $assignment->student_first_name = $nameParts[0] ?? '';
            $assignment->student_last_name = $nameParts[1] ?? '';
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
            ->join('users', 'tp_assignments.student_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->select(
                'tp_assignments.*',
                'users.name as student_name',
                'users.email as student_email',
                DB::raw('COALESCE(students.program, "Formation non définie") as formation'),
                'students.profile_photo'
            )
            ->orderBy('tp_assignments.created_at', 'desc')
            ->get();
        
        // Ajouter student_first_name et student_last_name
        $allTravaux = $allTravaux->map(function($travail) {
            $nameParts = explode(' ', $travail->student_name ?? '', 2);
            $travail->student_first_name = $nameParts[0] ?? '';
            $travail->student_last_name = $nameParts[1] ?? '';
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
        $studentsTps = $allTravaux->groupBy('student_id')->map(function($studentTravaux) {
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
            'today' => $rapports->filter(function($rapport) {
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
            ->join('users', 'tp_assignments.student_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->where('tp_assignments.id', $id)
            ->select(
                'tp_assignments.*',
                'users.name as student_name',
                'users.email as student_email',
                'students.program as formation'
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
        $nameParts = explode(' ', $tp->student_name ?? '', 2);
        $student = (object)[
            'first_name' => $nameParts[0] ?? '',
            'last_name' => $nameParts[1] ?? '',
            'email' => $tp->student_email,
            'program' => $tp->formation,
        ];
        
        // Ajouter les propriétés manquantes au TP pour compatibilité avec la vue
        $tp->title = $tp->tp_title ?? 'TP sans titre';
        $tp->description = $tp->tp_description ?? $tp->comments ?? '';
        $tp->link = $tp->file_path ?? null;
        $tp->files = collect([]);  // Pas de fichiers multiples dans tp_assignments
        
        // Créer la variable files séparée pour la vue
        $files = collect([]);
        
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
            $tp = DB::table('tp')->where('id', $id)->first();
            
            if (!$tp) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'TP introuvable'
                    ], 404);
                }
                return redirect()->back()->with('error', 'TP introuvable');
            }
            
            // Récupérer l'utilisateur
            $user = DB::table('users')->where('id', $tp->user_id)->first();
            
            if (!$user) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Utilisateur introuvable'
                    ], 404);
                }
                return redirect()->back()->with('error', 'Utilisateur introuvable');
            }
            
            // Mettre à jour le TP
            DB::table('tp')->where('id', $id)->update([
                'status' => 'validated',
                'validated_at' => now(),
                'updated_at' => now()
            ]);
            
            // Envoyer l'email de notification
            try {
                Mail::send('emails.tp-validated', [
                    'user' => $user,
                    'tp' => $tp
                ], function ($message) use ($user) {
                    $message->to($user->email)
                            ->subject('✅ Votre TP a été validé - EVC');
                });
            } catch (\Exception $e) {
                Log::warning('Erreur lors de l\'envoi de l\'email de validation TP: ' . $e->getMessage());
                // Continue même si l'email échoue
            }
            
            // Si c'est une requête AJAX, retourner JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'TP validé avec succès !'
                ]);
            }
            
            // Sinon, rediriger vers la page précédente avec un message de succès
            return redirect()->back()->with('success', 'TP validé avec succès !');
            
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la validation: ' . $e->getMessage()
                ], 500);
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
            $tp = DB::table('tp')->where('id', $id)->first();
            
            if (!$tp) {
                return response()->json([
                    'success' => false,
                    'message' => 'TP introuvable'
                ], 404);
            }
            
            // Récupérer l'utilisateur
            $user = DB::table('users')->where('id', $tp->user_id)->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur introuvable'
                ], 404);
            }
            
            // Valider que la raison est fournie
            $request->validate([
                'reason' => 'required|string|min:10'
            ]);
            
            $reason = $request->input('reason');
            
            // Mettre à jour le TP
            DB::table('tp')->where('id', $id)->update([
                'status' => 'rejected',
                'admin_comment' => $reason,
                'updated_at' => now()
            ]);
            
            // Envoyer l'email de notification avec la raison du rejet
            try {
                Mail::send('emails.tp-rejected', [
                    'user' => $user,
                    'tp' => $tp,
                    'rejectionReason' => $reason
                ], function ($message) use ($user) {
                    $message->to($user->email)
                            ->subject('📝 Votre TP nécessite des améliorations - EVC');
                });
            } catch (\Exception $e) {
                Log::warning('Erreur lors de l\'envoi de l\'email de rejet TP: ' . $e->getMessage());
                // Continue même si l'email échoue
            }
            
            return redirect()->route('admin.tp.view', $id)
            ->with('success', '✅ TP rejeté avec succès ! Un email a été envoyé à l\'étudiant.');
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
}
