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
            return view('admin.formations.index', compact('formations'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des formations: ' . $e->getMessage());
            return view('admin.formations.index', ['formations' => collect()])->with('error', 'Impossible de charger la liste des formations.');
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
        $categories = Category::withCount('formations')->get()->map(function($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'status' => $category->status ?? 'active',
                'formations_count' => $category->formations_count ?? 0,
                'students_count' => 0, // À calculer si nécessaire
                'created_at' => $category->created_at,
            ];
        })->toArray();
        
        return view('admin.formations.categories', compact('categories'));
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
        return view('admin.bibliotheque.index', compact('items'));
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

    public function documentsAll(): View
    {
        // Récupère tous les médias de la bibliothèque, en chargeant les relations
        // avec la catégorie et l'utilisateur pour un affichage complet.
        $documents = Library::with(['libraryCategory', 'user'])->latest()->get();
        
        // Retourne la vue en passant la collection de documents
        return view('admin.documents.all', compact('documents'));
    }

    /**
     * Voir un TP (admin)
     */
    public function viewTp(int $id)
    {
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
}
