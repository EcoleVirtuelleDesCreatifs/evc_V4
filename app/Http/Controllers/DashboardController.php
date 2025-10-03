<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Http\Requests\StudentProfileRequest;
use App\Services\StudentProfileService;

class DashboardController extends Controller
{
    use AuthorizesRequests;
    /**
     * Redirect to the login page to ensure stability.
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('login'); 
    }

    /**
     * Placeholder for the design graphique dashboard.
     */
    public function designGraphique(StudentProfileService $service): View
    {
        $user = Auth::user();
        $student = $service->loadStudent($user, null);
        $preReg = $service->loadPreRegistration($student, $user);
        return view('dashboard.design-graphique', [
            'user' => $user,
            'student' => $student,
            'preReg' => $preReg,
        ]);
    }

    /**
     * A placeholder for the showAllTP method to prevent crashes.
     */
    public function showAllTP(): View
    {
        return view('tp.view', [
            'projects' => [],
            'stats' => [],
            'userProfile' => (object)[]
        ]);
    }

    /**
     * Lister tous les TP de l'utilisateur (Design Graphique)
     */
    public function listTP(): View
    {
        $user = Auth::user();
        
        // Initialiser les valeurs par défaut
        $tps = [];
        $totalTpRequis = 20; // Nombre total de TP requis pour la certification
        
        // Statistiques par défaut
        $statistiques = [
            'tp_realises' => 0,
            'tp_a_faire' => $totalTpRequis,
            'tp_total' => $totalTpRequis,
            'progression_pourcentage' => 0
        ];
        
        $validationStats = [
            'tp_en_validation' => 0,
            'tp_valides' => 0
        ];
        
        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            return view('tp.index', compact('tps', 'statistiques', 'validationStats'));
        }
        
        try {
            // Vérifier si la table tp existe
            if (!Schema::hasTable('tp')) {
                Log::warning('Table tp n\'existe pas');
                return view('tp.index', compact('tps', 'statistiques', 'validationStats'));
            }
            
            // Récupérer tous les TP de l'utilisateur
            $tpsQuery = DB::table('tp')->where('user_id', $user->id);
            
            // Joindre les fichiers si la table existe
            if (Schema::hasTable('tp_files')) {
                $tpsQuery->leftJoin('tp_files', 'tp.id', '=', 'tp_files.tp_id')
                         ->select('tp.*', DB::raw('COUNT(tp_files.id) as files_count'))
                         ->groupBy('tp.id');
            }
            
            $tps = $tpsQuery->orderByDesc('created_at')->get();
            
            // Calculer les statistiques
            $cols = Schema::getColumnListing('tp');
            
            if (in_array('status', $cols)) {
                // Compter les TP par statut
                $tpsPending = DB::table('tp')
                    ->where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->count();
                
                $tpsValidated = DB::table('tp')
                    ->where('user_id', $user->id)
                    ->where('status', 'validated')
                    ->count();
                
                // Mettre à jour les statistiques
                $validationStats['tp_en_validation'] = $tpsPending;
                $validationStats['tp_valides'] = $tpsValidated;
                $statistiques['tp_realises'] = $tpsValidated;
                $statistiques['tp_a_faire'] = max(0, $totalTpRequis - $tpsValidated);
                $statistiques['progression_pourcentage'] = $totalTpRequis > 0 ? min(100, round(($tpsValidated / $totalTpRequis) * 100)) : 0;
                
                // Log pour débogage
                Log::info('Statistiques TP calculées', [
                    'user_id' => $user->id,
                    'tp_pending' => $tpsPending,
                    'tp_validated' => $tpsValidated,
                    'tp_realises' => $statistiques['tp_realises'],
                    'progression' => $statistiques['progression_pourcentage']
                ]);
            } else {
                Log::warning('Colonne status n\'existe pas dans la table tp');
            }
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des TP', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        
        return view('tp.index', compact('tps', 'statistiques', 'validationStats'));
    }

    /**
     * Afficher le formulaire de création d'un TP
     */
    public function createTP(): View|RedirectResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }
        
        // Vérifier le type de projet (print ou digital)
        $type = request()->query('type', 'digital');
        
        // Charger la vue appropriée selon le type
        if ($type === 'print') {
            return view('tp.create-print');
        }
        
        return view('tp.create');
    }

    /**
     * Enregistrer un nouveau TP
     */
    public function storeTP(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour effectuer cette action.');
        }
        
        try {
            // Validation des données
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:2000',
                'link' => 'nullable|url|max:500',
                'files.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,gif,zip,rar,txt,ppt,pptx,xls,xlsx'
            ], [
                'title.required' => 'Le titre du TP est obligatoire.',
                'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
                'description.max' => 'La description ne peut pas dépasser 2000 caractères.',
                'link.url' => 'Le lien doit être une URL valide.',
                'link.max' => 'Le lien ne peut pas dépasser 500 caractères.',
                'files.*.max' => 'Chaque fichier ne peut pas dépasser 10MB.',
                'files.*.mimes' => 'Types de fichiers autorisés: PDF, DOC, DOCX, JPG, JPEG, PNG, GIF, ZIP, RAR, TXT, PPT, PPTX, XLS, XLSX.'
            ]);
            
            // Vérifier si la table tp existe
            if (!Schema::hasTable('tp')) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'La table des TPs n\'existe pas encore.');
            }
            
            // Insérer le TP
            $tpId = DB::table('tp')->insertGetId([
                'user_id' => $user->id,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'link' => $validated['link'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Traiter les fichiers uploadés
            if ($request->hasFile('files') && Schema::hasTable('tp_files')) {
                $uploadPath = public_path('uploads/tp');
                
                // Créer le dossier s'il n'existe pas
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                foreach ($request->file('files') as $file) {
                    if ($file->isValid()) {
                        // Récupérer les informations du fichier AVANT de le déplacer
                        $originalName = $file->getClientOriginalName();
                        $fileSize = $file->getSize();
                        $mimeType = $file->getMimeType();
                        $extension = $file->getClientOriginalExtension();
                        
                        // Générer un nom unique pour le fichier
                        $fileName = time() . '_' . uniqid() . '.' . $extension;
                        $filePath = 'uploads/tp/' . $fileName;
                        
                        // Déplacer le fichier
                        $file->move($uploadPath, $fileName);
                        
                        // Enregistrer les informations du fichier en base
                        DB::table('tp_files')->insert([
                            'tp_id' => $tpId,
                            'original_name' => $originalName,
                            'file_path' => $filePath,
                            'file_size' => $fileSize,
                            'mime_type' => $mimeType,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
            
            return redirect()->route('design-graphique.tp.index')
                ->with('success', 'TP ajouté avec succès!');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout du TP: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'ajout du TP: ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour un projet/TP
     */
    public function updateProject(Request $request, int $id): RedirectResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour effectuer cette action.');
        }
        
        try {
            if (!Schema::hasTable('tp')) {
                return redirect()->back()->with('error', 'La table des TPs n\'existe pas.');
            }
            
            // Vérifier que le TP appartient à l'utilisateur
            $tp = DB::table('tp')->where('id', $id)->where('user_id', $user->id)->first();
            
            if (!$tp) {
                return redirect()->route('design-graphique.tp.index')
                    ->with('error', 'TP introuvable ou accès non autorisé.');
            }
            
            // Validation
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:2000',
                'link' => 'nullable|url|max:500',
            ]);
            
            // Mettre à jour le TP
            DB::table('tp')->where('id', $id)->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'link' => $validated['link'] ?? null,
                'updated_at' => now(),
            ]);
            
            return redirect()->route('design-graphique.tp.index')
                ->with('success', 'TP mis à jour avec succès!');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du TP: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du TP.');
        }
    }

    /**
     * Mettre à jour un projet/TP avec images
     */
    public function updateProjectWithImages(Request $request, int $id): RedirectResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour effectuer cette action.');
        }
        
        try {
            if (!Schema::hasTable('tp')) {
                return redirect()->back()->with('error', 'La table des TPs n\'existe pas.');
            }
            
            // Vérifier que le TP appartient à l'utilisateur
            $tp = DB::table('tp')->where('id', $id)->where('user_id', $user->id)->first();
            
            if (!$tp) {
                return redirect()->route('design-graphique.tp.index')
                    ->with('error', 'TP introuvable ou accès non autorisé.');
            }
            
            // Traiter les nouveaux fichiers
            if ($request->hasFile('files') && Schema::hasTable('tp_files')) {
                $uploadPath = public_path('uploads/tp');
                
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                foreach ($request->file('files') as $file) {
                    if ($file->isValid()) {
                        $originalName = $file->getClientOriginalName();
                        $fileSize = $file->getSize();
                        $mimeType = $file->getMimeType();
                        $extension = $file->getClientOriginalExtension();
                        $fileName = time() . '_' . uniqid() . '.' . $extension;
                        $filePath = 'uploads/tp/' . $fileName;
                        
                        $file->move($uploadPath, $fileName);
                        
                        DB::table('tp_files')->insert([
                            'tp_id' => $id,
                            'original_name' => $originalName,
                            'file_path' => $filePath,
                            'file_size' => $fileSize,
                            'mime_type' => $mimeType,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
            
            return redirect()->route('design-graphique.tp.index')
                ->with('success', 'Images ajoutées avec succès!');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout des images: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l\'ajout des images.');
        }
    }

    /**
     * Supprimer un projet/TP
     */
    public function deleteProject(int $id): RedirectResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour effectuer cette action.');
        }
        
        try {
            if (!Schema::hasTable('tp')) {
                return redirect()->back()->with('error', 'La table des TPs n\'existe pas.');
            }
            
            // Vérifier que le TP appartient à l'utilisateur
            $tp = DB::table('tp')->where('id', $id)->where('user_id', $user->id)->first();
            
            if (!$tp) {
                return redirect()->route('design-graphique.tp.index')
                    ->with('error', 'TP introuvable ou accès non autorisé.');
            }
            
            // Supprimer les fichiers associés
            if (Schema::hasTable('tp_files')) {
                $files = DB::table('tp_files')->where('tp_id', $id)->get();
                
                foreach ($files as $file) {
                    $fullPath = public_path($file->file_path);
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                }
                
                DB::table('tp_files')->where('tp_id', $id)->delete();
            }
            
            // Supprimer le TP
            DB::table('tp')->where('id', $id)->delete();
            
            return redirect()->route('design-graphique.tp.index')
                ->with('success', 'TP supprimé avec succès!');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du TP: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la suppression du TP.');
        }
    }

    /**
     * Formulaire d'ajout simple de TP
     */
    public function ajouterSimpleTP(): View|RedirectResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }
        
        return view('tp.ajouter-simple');
    }

    /**
     * Formulaire de test simple de TP
     */
    public function testSimpleTP(): View|RedirectResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }
        
        return view('tp.test-simple');
    }

    /**
     * Enregistrer un TP de test simple
     */
    public function storeTestSimpleTP(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour effectuer cette action.');
        }
        
        try {
            // Validation simple
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:2000',
            ]);
            
            if (!Schema::hasTable('tp')) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'La table des TPs n\'existe pas encore.');
            }
            
            // Insérer le TP de test
            DB::table('tp')->insert([
                'user_id' => $user->id,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            return redirect()->route('design-graphique.tp.index')
                ->with('success', 'TP de test ajouté avec succès!');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout du TP de test: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'ajout du TP de test.');
        }
    }

    /**
     * Page d'index des formations (Design Graphique)
     */
    public function formationsIndex(): View
    {
        // Données minimales; à connecter à la base/formations réelles si nécessaire
        $formations = [
            [
                'slug' => 'initiation-graphisme',
                'title' => 'Initiation au Graphisme',
                'level' => 'Débutant',
                'duration' => '4 semaines',
            ],
            [
                'slug' => 'photoshop-avance',
                'title' => 'Photoshop Avancé',
                'level' => 'Intermédiaire',
                'duration' => '6 semaines',
            ],
        ];

        // Modules principaux publiés pour l'étudiant (tolérant au schéma)
        $modulesPrincipaux = [];
        $formationsPubliees = [];
        try {
            $user = Auth::user();
            $formationKeys = ['design_graphique','design-graphique','infographie'];
            if ($user) {
                $uCols = \Illuminate\Support\Facades\Schema::getColumnListing('users');
                if (in_array('formation_souhaitee', $uCols, true) && !empty($user->formation_souhaitee)) {
                    $formationKeys = [$user->formation_souhaitee];
                } elseif (in_array('choix_formation', $uCols, true) && !empty($user->choix_formation)) {
                    $formationKeys = [$user->choix_formation];
                }
            }

            // Détecter une table plausible de modules
            $modulesTable = null;
            foreach (['modules', 'formation_modules', 'cours_modules'] as $t) {
                if (\Illuminate\Support\Facades\Schema::hasTable($t)) { $modulesTable = $t; break; }
            }
            if ($modulesTable) {
                $cols = \Illuminate\Support\Facades\Schema::getColumnListing($modulesTable);
                $q = \Illuminate\Support\Facades\DB::table($modulesTable);
                // Publication
                if (in_array('published', $cols, true)) { $q->where('published', 1); }
                elseif (in_array('is_published', $cols, true)) { $q->where('is_published', 1); }
                // Type principal
                if (in_array('type', $cols, true)) { $q->where('type', 'principal'); }
                elseif (in_array('is_main', $cols, true)) { $q->where('is_main', 1); }
                // Filtre formation si colonne présente
                foreach (['formation','formation_slug','formation_key','programme','filiere'] as $fk) {
                    if (in_array($fk, $cols, true)) { $q->whereIn($fk, $formationKeys); break; }
                }
                // Colonnes à sélectionner au mieux
                $select = [];
                $select[] = in_array('id', $cols, true) ? 'id' : \Illuminate\Support\Facades\DB::raw('NULL as id');
                $select[] = in_array('title', $cols, true) ? 'title' : (in_array('name',$cols,true)?'name':\Illuminate\Support\Facades\DB::raw("'' as title"));
                $select[] = in_array('module_number', $cols, true) ? 'module_number' : (in_array('numero',$cols,true)?'numero':\Illuminate\Support\Facades\DB::raw('NULL as module_number'));
                $select[] = in_array('published_at', $cols, true) ? 'published_at' : (in_array('created_at',$cols,true)?'created_at':\Illuminate\Support\Facades\DB::raw('NULL as published_at'));
                $modulesPrincipaux = $q->orderByDesc(in_array('published_at',$cols,true)?'published_at':'id')->limit(12)->get($select);
            }

            // Formations publiées (tolérant au schéma)
            $formationsTable = null;
            foreach (['formations','courses','programmes','formation_courses'] as $t) {
                if (\Illuminate\Support\Facades\Schema::hasTable($t)) { $formationsTable = $t; break; }
            }
            if ($formationsTable) {
                $fcols = \Illuminate\Support\Facades\Schema::getColumnListing($formationsTable);
                $fq = \Illuminate\Support\Facades\DB::table($formationsTable);
                // Publié
                if (in_array('published', $fcols, true)) { $fq->where('published', 1); }
                elseif (in_array('is_published', $fcols, true)) { $fq->where('is_published', 1); }
                // Filtre formation
                foreach (['formation','formation_slug','formation_key','programme','filiere'] as $fk) {
                    if (in_array($fk, $fcols, true)) { $fq->whereIn($fk, $formationKeys); break; }
                }
                // Sélection
                $fselect = [];
                $fselect[] = in_array('id',$fcols,true)?'id':\Illuminate\Support\Facades\DB::raw('NULL as id');
                $fselect[] = in_array('title',$fcols,true)?'title':(in_array('name',$fcols,true)?'name':\Illuminate\Support\Facades\DB::raw("'' as title"));
                $fselect[] = in_array('category',$fcols,true)?'category':(in_array('categorie',$fcols,true)?'categorie':\Illuminate\Support\Facades\DB::raw("'' as category"));
                $fselect[] = in_array('level',$fcols,true)?'level':(in_array('niveau',$fcols,true)?'niveau':\Illuminate\Support\Facades\DB::raw("'' as level"));
                $fselect[] = in_array('duration',$fcols,true)?'duration':(in_array('duree',$fcols,true)?'duree':\Illuminate\Support\Facades\DB::raw("'' as duration"));
                $fselect[] = in_array('created_at',$fcols,true)?'created_at':\Illuminate\Support\Facades\DB::raw('NULL as created_at');
                $formationsPubliees = $fq->orderByDesc(in_array('created_at',$fcols,true)?'created_at':'id')->limit(12)->get($fselect);
            }
        } catch (\Throwable $e) {
            Log::warning('formationsIndex modulesPrincipaux load failed', ['error' => $e->getMessage()]);
        }

        return view('formations.index', [
            'title' => 'Formations - Design Graphique',
            'formations' => $formations,
            'modules_principaux' => $modulesPrincipaux,
            'formations_publiees' => $formationsPubliees,
        ]);
    }

    /**
     * Liste des formations par catégorie
     */
    public function formationsCategory(string $category): View
    {
        // Placeholder: charger les formations de la catégorie
        $formations = [];
        return view('formations.category', [
            'category' => $category,
            'formations' => $formations,
        ]);
    }

    /**
     * Détail d'une formation
     */
    public function formationsShow(int $id): View
    {
        // Chargement tolérant au schéma depuis une table plausible
        $formationsTable = null;
        foreach (['formations','courses','programmes','formation_courses'] as $t) {
            if (\Illuminate\Support\Facades\Schema::hasTable($t)) { $formationsTable = $t; break; }
        }

        $formation = null;
        if ($formationsTable) {
            $cols = \Illuminate\Support\Facades\Schema::getColumnListing($formationsTable);
            $q = \Illuminate\Support\Facades\DB::table($formationsTable)->where('id', $id);
            // Publié uniquement si colonne présente
            if (in_array('published', $cols, true)) { $q->where('published', 1); }
            elseif (in_array('is_published', $cols, true)) { $q->where('is_published', 1); }

            $select = [];
            $select[] = in_array('id',$cols,true)?'id':\Illuminate\Support\Facades\DB::raw('NULL as id');
            $select[] = in_array('title',$cols,true)?'title':(in_array('name',$cols,true)?'name':\Illuminate\Support\Facades\DB::raw("'' as title"));
            $select[] = in_array('category',$cols,true)?'category':(in_array('categorie',$cols,true)?'categorie':\Illuminate\Support\Facades\DB::raw("'' as category"));
            $select[] = in_array('level',$cols,true)?'level':(in_array('niveau',$cols,true)?'niveau':\Illuminate\Support\Facades\DB::raw("'' as level"));
            $select[] = in_array('duration',$cols,true)?'duration':(in_array('duree',$cols,true)?'duree':\Illuminate\Support\Facades\DB::raw("'' as duration"));
            $select[] = in_array('description',$cols,true)?'description':(in_array('content',$cols,true)?'content':\Illuminate\Support\Facades\DB::raw("'' as description"));
            $select[] = in_array('video_url',$cols,true)?'video_url':(in_array('video',$cols,true)?'video':\Illuminate\Support\Facades\DB::raw("'' as video_url"));
            $select[] = in_array('created_at',$cols,true)?'created_at':\Illuminate\Support\Facades\DB::raw('NULL as created_at');
            $formation = $q->first($select);

            // Related formations for sidebar
            $rq = \Illuminate\Support\Facades\DB::table($formationsTable);
            // Only published if column exists
            if (in_array('published', $cols, true)) { $rq->where('published', 1); }
            elseif (in_array('is_published', $cols, true)) { $rq->where('is_published', 1); }
            // Exclude current id
            if (in_array('id',$cols,true)) { $rq->where('id', '<>', $id); }
            // Same category if possible
            $catCol = in_array('category',$cols,true) ? 'category' : (in_array('categorie',$cols,true) ? 'categorie' : null);
            if ($catCol && $formation && !empty($formation->category)) {
                $rq->where($catCol, $formation->category);
            }
            $rselect = [];
            $rselect[] = in_array('id',$cols,true)?'id':\Illuminate\Support\Facades\DB::raw('NULL as id');
            $rselect[] = in_array('title',$cols,true)?'title':(in_array('name',$cols,true)?'name':\Illuminate\Support\Facades\DB::raw("'' as title"));
            $rselect[] = $catCol ? $catCol.' as category' : \Illuminate\Support\Facades\DB::raw("'' as category");
            $rselect[] = in_array('created_at',$cols,true)?'created_at':\Illuminate\Support\Facades\DB::raw('NULL as created_at');
            $related = $rq->orderByDesc(in_array('created_at',$cols,true)?'created_at':'id')->limit(6)->get($rselect);
        } else {
            $related = collect();
        }

        // Fallback minimal si rien en base
        if (!$formation) {
            $formation = (object) [
                'id' => $id,
                'title' => 'Formation #' . $id,
                'category' => '',
                'level' => '',
                'duration' => '',
                'description' => 'Description à venir',
                'video_url' => '',
                'created_at' => null,
            ];
        }

        return view('formations.show', [
            'formation' => $formation,
            'related_formations' => $related ?? collect(),
        ]);
    }

    /**
     * Affichage du formulaire d'édition du profil étudiant.
     */
    /** @return View */
    public function editProfile(Request $request, StudentProfileService $service, ?int $id = null): View
    {
        /** @var User|null $authUser */
        $authUser = Auth::user();
        $student = $service->loadStudent($authUser, $id);
        // Autorisation désactivée temporairement pour éviter les 403 pendant l'intégration de la policy

        $preReg = $service->loadPreRegistration($student, $authUser);
        // Construire des valeurs par défaut (pré-remplissage)
        $sf = optional($student);
        $pr = optional($preReg);
        $defaults = [
            'first_name'       => $sf->first_name ?: ($authUser->name ?? ($pr->first_name ?? '')),
            'last_name'        => $sf->last_name ?: ($pr->last_name ?? ''),
            'email'            => $sf->email ?: (($authUser->email ?? '') ?: ($pr->email ?? '')),
            'phone'            => $sf->phone ?: ($pr->phone ?? ''),
            'whatsapp'         => $sf->whatsapp ?: ($pr->whatsapp ?? ''),
            'date_of_birth'    => $sf->date_of_birth ? $sf->date_of_birth->format('Y-m-d') : ($pr->date_of_birth ?? ''),
            'gender'           => $sf->gender ?: ($pr->gender ?? ''),
            'level'            => $sf->level ?: ($pr->level ?? ''),
            'specialization'   => $sf->specialization ?: ($pr->specialization ?? ''),
            'quartier'         => $sf->quartier ?: ($pr->quartier ?? ''),
            'city'             => $sf->city ?: ($pr->city ?? ''),
            'country'          => $sf->country ?: ($pr->country ?? ''),
            'years_experience' => ($sf->years_experience !== null ? $sf->years_experience : ($pr->years_experience ?? '')),
            'industry_sector'  => $sf->industry_sector ?: ($pr->industry_sector ?? ''),
        ];

        return view('dashboard.profil.editer', [
            'student' => $student,
            'user' => $authUser,
            'preReg' => $preReg,
            'defaults' => $defaults,
        ]);
    }

    /**
     * Mise à jour du profil étudiant (nom, email, photo, ...)
     */
    /** @return RedirectResponse */
    public function updateProfile(StudentProfileRequest $request, StudentProfileService $service, ?int $id = null): RedirectResponse
    {
        /** @var User|null $authUser */
        $authUser = Auth::user();
        if (!$authUser) {
            return redirect()->route('login');
        }
        $student = $service->loadStudent($authUser, $id);
        // Autorisation désactivée temporairement pour éviter les 403 pendant l'intégration de la policy
        $service->save($student, $request->validated(), $request->file('profile_photo'));
        $redirectParams = $id ? ['id' => $student->id] : [];
        return redirect()->route('design-graphique.profil.editer', $redirectParams)
            ->with('success', 'Profil mis à jour avec succès!');
    }

    /**
     * Afficher la page des projets de design graphique
     */
    public function projets(): View
    {
        $user = Auth::user();
        
        // Initialiser toutes les variables avec des valeurs par défaut
        $projets = collect([]);
        $soloProjects = [];
        $groupProjects = [];
        $stats = [
            'solo_projects' => 0,
            'group_projects' => 0,
            'total_projects' => 0
        ];
        $statistiques = [
            'total' => 0,
            'en_cours' => 0,
            'termines' => 0,
            'en_attente' => 0
        ];
        $soloPagination = [
            'current_page' => 1,
            'per_page' => 10,
            'total_items' => 0,
            'total_pages' => 1,
            'has_prev' => false,
            'has_next' => false
        ];
        $groupPagination = [
            'current_page' => 1,
            'per_page' => 10,
            'total_items' => 0,
            'total_pages' => 1,
            'has_prev' => false,
            'has_next' => false
        ];
        
        if (!$user) {
            return view('projets.index', compact('projets', 'soloProjects', 'groupProjects', 'stats', 'statistiques', 'soloPagination', 'groupPagination'));
        }
        
        try {
            // Vérifier si la table design_projects existe
            if (!Schema::hasTable('design_projects')) {
                Log::warning('Table design_projects n\'existe pas');
                return view('projets.index', compact('projets', 'soloProjects', 'groupProjects', 'stats', 'statistiques', 'soloPagination', 'groupPagination'));
            }
            
            // Récupérer tous les projets de l'utilisateur
            $allProjects = DB::table('design_projects')
                ->where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->get();
            
            if ($allProjects && $allProjects->count() > 0) {
                // Convertir en tableau pour faciliter la manipulation
                $projets = $allProjects->map(function($project) {
                    $projectArray = (array) $project;
                    // Ajouter files_count si nécessaire
                    if (!isset($projectArray['files_count'])) {
                        $projectArray['files_count'] = 0;
                    }
                    // Parser software_used si c'est une chaîne JSON
                    if (isset($projectArray['software_used']) && is_string($projectArray['software_used'])) {
                        $projectArray['software_used_array'] = json_decode($projectArray['software_used'], true) ?: [];
                    } else {
                        $projectArray['software_used_array'] = [];
                    }
                    return $projectArray;
                });
                
                // Séparer les projets solo et groupe
                $soloProjects = $projets->where('category', 'solo')->values()->toArray();
                $groupProjects = $projets->where('category', 'groupe')->values()->toArray();
                
                // Calculer les statistiques
                $stats['solo_projects'] = count($soloProjects);
                $stats['group_projects'] = count($groupProjects);
                $stats['total_projects'] = $projets->count();
                
                $statistiques['total'] = $projets->count();
                $statistiques['en_cours'] = $projets->where('status', 'in_progress')->count();
                $statistiques['termines'] = $projets->where('status', 'completed')->count();
                $statistiques['en_attente'] = $projets->where('status', 'pending')->count();
                
                // Calculer la pagination pour solo
                $soloPagination['total_items'] = count($soloProjects);
                $soloPagination['total_pages'] = max(1, ceil($soloPagination['total_items'] / $soloPagination['per_page']));
                $soloPagination['has_prev'] = $soloPagination['current_page'] > 1;
                $soloPagination['has_next'] = $soloPagination['current_page'] < $soloPagination['total_pages'];
                
                // Calculer la pagination pour groupe
                $groupPagination['total_items'] = count($groupProjects);
                $groupPagination['total_pages'] = max(1, ceil($groupPagination['total_items'] / $groupPagination['per_page']));
                $groupPagination['has_prev'] = $groupPagination['current_page'] > 1;
                $groupPagination['has_next'] = $groupPagination['current_page'] < $groupPagination['total_pages'];
            }
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des projets', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id
            ]);
        }
        
        return view('projets.index', compact('projets', 'soloProjects', 'groupProjects', 'stats', 'statistiques', 'soloPagination', 'groupPagination'));
    }
}
