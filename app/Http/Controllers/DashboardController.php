<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;
use App\Models\Student;
use App\Models\DesignProject;
use App\Models\TP;
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
     * Dashboard Design Graphique avec statistiques complètes
     */
    public function designGraphique(StudentProfileService $service): View
    {
        $user = Auth::user();
        $student = $service->loadStudent($user, null);
        $preReg = $service->loadPreRegistration($student, $user);
        
        // Statistiques pour le dashboard
        $stats = [
            // Nombre de formations disponibles (à adapter selon votre table)
            'formations_disponibles' => 12, // Valeur par défaut
            
            // Nombre de TP réalisés
            'tp_realises' => DB::table('tp')
                ->where('user_id', $user->id)
                ->whereIn('status', ['validated', 'completed'])
                ->count(),
            
            // Nombre total de TP
            'tp_total' => DB::table('tp')
                ->where('user_id', $user->id)
                ->count(),
            
            // Nombre de projets réalisés (DesignProject)
            'projets_realises' => DB::table('design_projects')
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->count(),
            
            // Nombre total de projets
            'projets_total' => DB::table('design_projects')
                ->where('user_id', $user->id)
                ->count(),
            
            // Webinaires en cours (valeur par défaut, à adapter)
            'webinaires_en_cours' => 4,
            
            // Actualités en cours (valeur par défaut, à adapter)
            'actualites_en_cours' => 5,
            
            // Montant restant à solder (depuis preReg ou student)
            'montant_restant' => 0, // À adapter selon votre logique métier
        ];
        
        // Si vous avez des informations de paiement dans preReg ou student
        if ($preReg && isset($preReg->montant_total) && isset($preReg->montant_paye)) {
            $stats['montant_restant'] = $preReg->montant_total - $preReg->montant_paye;
        }
        
        // Récupérer les formations en vedette filtrées par module "Design Graphique"
        $featured_formations = DB::table('formations')
            ->where('status', 'active')
            ->where('is_featured', 1)
            ->where(function($query) {
                $query->whereJsonContains('modules', 'design-graphique')
                      ->orWhereJsonContains('modules', 'Design Graphique')
                      ->orWhereJsonContains('modules', 'design_graphique');
            })
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
        
        return view('dashboard.design-graphique', [
            'user' => $user,
            'student' => $student,
            'preReg' => $preReg,
            'stats' => $stats,
            'featured_formations' => $featured_formations,
        ]);
    }

    /**
     * Afficher tous les TP de l'utilisateur (Design Graphique)
     */
    public function showAllTP(): View
    {
        $user = Auth::user();
        
        // Récupérer tous les TP de l'utilisateur avec leurs fichiers
        $projects = TP::where('user_id', $user->id)
            ->with(['files'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculer les statistiques
        $stats = [
            'total' => $projects->count(),
            'validated' => $projects->where('status', 'validated')->count(),
            'pending' => $projects->where('status', 'pending')->count(),
            'rejected' => $projects->where('status', 'rejected')->count(),
        ];
        
        return view('tp.all', [
            'projects' => $projects,
            'stats' => $stats,
            'userProfile' => $user
        ]);
    }

    /**
     * Afficher un TP spécifique
     */
    public function viewTP($id): View
    {
        $user = Auth::user();
        
        // Récupérer le TP avec ses fichiers
        $project = TP::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['files'])
            ->firstOrFail();
        
        return view('tp.view', [
            'project' => $project
        ]);
    }

    /**
     * Afficher le formulaire de modification d'un TP
     */
    public function editTP($id): View|RedirectResponse
    {
        $user = Auth::user();
        
        // Récupérer le TP avec ses fichiers
        $project = TP::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['files'])
            ->firstOrFail();
        
        // Vérifier que le TP n'est pas déjà validé
        if ($project->status === 'validated') {
            return redirect()->route('design-graphique.tp.voir', $id)
                ->with('error', 'Vous ne pouvez pas modifier un TP déjà validé.');
        }
        
        return view('tp.edit', [
            'project' => $project
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
        $totalTpRequis = 20;
        
        // Compter directement les TP depuis la base de données
        $tpsPending = 0;
        $tpsValidated = 0;
        $tpsTotal = 0;
        
        try {
            // Compter TOUS les TP créés
            $tpsTotal = DB::table('tp')->count();
            
            // Compter les TP par statut
            $tpsPending = DB::table('tp')->where('status', 'pending')->count();
            $tpsValidated = DB::table('tp')->where('status', 'validated')->count();
        } catch (\Exception $e) {
            Log::error('Erreur comptage TP', ['error' => $e->getMessage()]);
        }
        
        // Calculer les statistiques
        $statistiques = [
            'tp_realises' => $tpsTotal, // Nombre total de TP créés
            'tp_a_faire' => max(0, $totalTpRequis - $tpsTotal),
            'tp_total' => $totalTpRequis,
            'progression_pourcentage' => $totalTpRequis > 0 ? min(100, round(($tpsTotal / $totalTpRequis) * 100)) : 0
        ];
        
        $validationStats = [
            'tp_en_validation' => $tpsPending,
            'tp_valides' => $tpsValidated
        ];
        
        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            return view('tp.index', compact('tps', 'statistiques', 'validationStats'));
        }
        
        try {
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
            Log::info('Colonnes de la table tp', ['cols' => $cols]);
            
            if (in_array('status', $cols)) {
                Log::info('Colonne status trouvée, calcul des statistiques...');
                
                // Compter TOUS les TP par statut (pas seulement ceux de l'utilisateur)
                $tpsPending = DB::table('tp')
                    ->where('status', 'pending')
                    ->count();
                
                Log::info('TP Pending comptés', ['count' => $tpsPending]);
                
                $tpsValidated = DB::table('tp')
                    ->where('status', 'validated')
                    ->count();
                
                Log::info('TP Validated comptés', ['count' => $tpsValidated]);
                
                $tpsRejected = DB::table('tp')
                    ->where('status', 'rejected')
                    ->count();
                
                // Compter aussi les fichiers TP
                $tpFilesCount = 0;
                if (Schema::hasTable('tp_files')) {
                    $tpFilesCount = DB::table('tp_files')->count();
                }
                
                // Mettre à jour les statistiques
                $validationStats['tp_en_validation'] = $tpsPending;
                $validationStats['tp_valides'] = $tpsValidated;
                $statistiques['tp_realises'] = $tpsValidated;
                $statistiques['tp_a_faire'] = max(0, $totalTpRequis - $tpsValidated);
                $statistiques['progression_pourcentage'] = $totalTpRequis > 0 ? min(100, round(($tpsValidated / $totalTpRequis) * 100)) : 0;
                
                // Log pour débogage
                Log::info('=== STATISTIQUES TP FINALES ===', [
                    'user_id' => $user->id,
                    'tp_pending' => $tpsPending,
                    'tp_validated' => $tpsValidated,
                    'tp_realises' => $statistiques['tp_realises'],
                    'progression' => $statistiques['progression_pourcentage'],
                    'validationStats' => $validationStats,
                    'statistiques' => $statistiques
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
     * MÉTHODE HELPER: Sauvegarder UN SEUL fichier
     */
    private function saveOneFile($file, int $tpId, int $fileIndex): array
    {
        try {
            // ÉTAPE 1: Valider le fichier
            if (!$file->isValid()) {
                return ['success' => false, 'error' => 'Fichier invalide'];
            }
            
            // ÉTAPE 2: Extraire les informations
            $originalName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();
            $extension = $file->getClientOriginalExtension();
            
            Log::info("📄 Fichier $fileIndex: $originalName ($mimeType, $fileSize bytes)");
            
            // ÉTAPE 3: Créer le dossier si nécessaire
            $uploadPath = public_path('uploads/tp');
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
                Log::info("📁 Dossier créé: $uploadPath");
            }
            
            // ÉTAPE 4: Générer nom unique
            $fileName = time() . '_' . $fileIndex . '_' . uniqid() . '.' . $extension;
            $relativePath = 'uploads/tp/' . $fileName;
            $fullPath = $uploadPath . '/' . $fileName;
            
            // ÉTAPE 5: Déplacer le fichier
            $file->move($uploadPath, $fileName);
            
            // ÉTAPE 6: Vérifier que le fichier existe
            if (!file_exists($fullPath)) {
                return ['success' => false, 'error' => 'Fichier non trouvé après déplacement'];
            }
            
            Log::info("✅ Fichier $fileIndex déplacé: $fullPath");
            
            // ÉTAPE 7: Insérer en base de données
            $insertId = DB::table('tp_files')->insertGetId([
                'tp_id' => $tpId,
                'original_name' => $originalName,
                'file_path' => $relativePath,
                'file_size' => $fileSize,
                'mime_type' => $mimeType,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            Log::info("✅ Fichier $fileIndex enregistré en base (ID: $insertId)");
            
            return [
                'success' => true,
                'file_id' => $insertId,
                'file_name' => $originalName
            ];
            
        } catch (\Exception $e) {
            Log::error("❌ Erreur fichier $fileIndex: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Enregistrer un nouveau TP - VERSION COMPLÈTEMENT RÉÉCRITE ÉTAPE PAR ÉTAPE
     */
    public function storeTP(Request $request): RedirectResponse
    {
        // ========================================
        // ÉTAPE 1: VÉRIFIER L'AUTHENTIFICATION
        // ========================================
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté.');
        }
        
        Log::info('🎯 DÉBUT CRÉATION TP', ['user_id' => $user->id]);
        
        try {
            // ========================================
            // ÉTAPE 2: VALIDER LES DONNÉES
            // ========================================
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:2000',
                'link' => 'nullable|url|max:500',
                'images.*' => 'nullable|file|max:20480|mimes:jpg,jpeg,png,gif,webp,svg,pdf,psd,ai,doc,docx,zip,rar'
            ], [
                'title.required' => 'Le titre du TP est obligatoire.',
                'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
                'description.max' => 'La description ne peut pas dépasser 2000 caractères.',
                'link.url' => 'Le lien doit être une URL valide.',
                'link.max' => 'Le lien ne peut pas dépasser 500 caractères.',
                'images.*.max' => 'Chaque fichier ne peut pas dépasser 20MB.',
                'images.*.mimes' => 'Types de fichiers autorisés: JPG, JPEG, PNG, GIF, WEBP, SVG, PDF, PSD, AI, DOC, DOCX, ZIP, RAR.'
            ]);
            
            Log::info('✅ ÉTAPE 2 OK: Validation réussie');
            
            // ========================================
            // ÉTAPE 3: CRÉER LE TP DANS LA BASE
            // ========================================
            if (!Schema::hasTable('tp')) {
                Log::error('❌ Table tp inexistante');
                return redirect()->back()->withInput()->with('error', 'Erreur système.');
            }
            
            $tpId = DB::table('tp')->insertGetId([
                'user_id' => $user->id,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'link' => $validated['link'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            Log::info("✅ ÉTAPE 3 OK: TP créé (ID: $tpId)");
            
            // ========================================
            // ÉTAPE 4: TRAITER LES FICHIERS UN PAR UN
            // ========================================
            $filesSuccess = 0;
            $filesErrors = [];
            
            if (!Schema::hasTable('tp_files')) {
                Log::warning('⚠️ Table tp_files inexistante - fichiers ignorés');
            } elseif ($request->hasFile('images')) {
                $files = $request->file('images');
                $totalFiles = count($files);
                
                Log::info("📂 ÉTAPE 4: Traitement de $totalFiles fichier(s)");
                
                foreach ($files as $index => $file) {
                    $fileNumber = $index + 1;
                    $result = $this->saveOneFile($file, $tpId, $fileNumber);
                    
                    if ($result['success']) {
                        $filesSuccess++;
                        Log::info("✅ Fichier $fileNumber/{$totalFiles} OK");
                    } else {
                        $filesErrors[] = "Fichier $fileNumber: " . $result['error'];
                        Log::error("❌ Fichier $fileNumber/{$totalFiles} ÉCHOUÉ: " . $result['error']);
                    }
                }
                
                Log::info("📊 RÉSUMÉ UPLOAD", [
                    'total_fichiers' => $totalFiles,
                    'succès' => $filesSuccess,
                    'erreurs' => count($filesErrors),
                    'détails_erreurs' => $filesErrors
                ]);
            } else {
                Log::info('ℹ️ Aucun fichier à traiter');
            }
            
            // ========================================
            // ÉTAPE 5: NOTIFIER LES ADMINS ET RETOURNER
            // ========================================
            $filesCount = Schema::hasTable('tp_files') 
                ? DB::table('tp_files')->where('tp_id', $tpId)->count() 
                : 0;
            
            Log::info("✅ ÉTAPE 5: Notification admins ($filesCount fichier(s) stocké(s))");
            
            // Récupérer le TP pour l'email
            $createdTp = DB::table('tp')->where('id', $tpId)->first();
            
            try {
                // Récupérer tous les administrateurs
                $admins = DB::table('admins')->get();
                
                if ($admins && $admins->count() > 0) {
                    // Générer l'URL de consultation du TP
                    $viewUrl = route('admin.tp.view', ['id' => $tpId]);
                    
                    foreach ($admins as $admin) {
                        Mail::send('emails.admin-new-tp-notification', [
                            'student' => $user,
                            'tp' => $createdTp,
                            'filesCount' => $filesCount,
                            'viewUrl' => $viewUrl
                        ], function ($message) use ($admin) {
                            $message->to($admin->email)
                                    ->subject('🔔 Nouveau TP soumis - Action requise - EVC');
                        });
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Erreur lors de l\'envoi de l\'email de notification admin: ' . $e->getMessage());
                // Continue même si l'email échoue
            }
            
            // Message de succès avec détails
            $successMessage = "TP créé avec succès!";
            if ($filesSuccess > 0) {
                $successMessage .= " ($filesSuccess fichier(s) uploadé(s))";
            }
            if (count($filesErrors) > 0) {
                $successMessage .= " Attention: " . count($filesErrors) . " fichier(s) en erreur.";
            }
            
            Log::info("✅ TP CRÉÉ AVEC SUCCÈS", [
                'tp_id' => $tpId,
                'fichiers_ok' => $filesSuccess,
                'fichiers_erreur' => count($filesErrors)
            ]);
            
            return redirect()->route('design-graphique.tp.index')
                ->with('success', $successMessage);
                
        } catch (\Exception $e) {
            Log::error('❌ ERREUR FATALE CRÉATION TP: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'ajout du TP: ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour un projet/TP
     */
    public function updateProject(Request $request, int $id): \Illuminate\Http\JsonResponse|RedirectResponse
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
            
            // Si c'est une requête AJAX, retourner du JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'TP mis à jour avec succès!'
                ]);
            }
            
            return redirect()->route('design-graphique.tp.index')
                ->with('success', 'TP mis à jour avec succès!');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du TP: ' . $e->getMessage());
            
            // Si c'est une requête AJAX, retourner du JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du TP.',
                    'error' => $e->getMessage()
                ], 500);
            }
            
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
    public function deleteProject(Request $request, int $id)
    {
        $user = Auth::user();
        
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous devez être connecté pour effectuer cette action.'
                ], 401);
            }
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour effectuer cette action.');
        }
        
        try {
            if (!Schema::hasTable('tp')) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La table des TPs n\'existe pas.'
                    ], 500);
                }
                return redirect()->back()->with('error', 'La table des TPs n\'existe pas.');
            }
            
            // Vérifier que le TP appartient à l'utilisateur
            $tp = DB::table('tp')->where('id', $id)->where('user_id', $user->id)->first();
            
            if (!$tp) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'TP introuvable ou accès non autorisé.'
                    ], 404);
                }
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
            
            // Retourner JSON pour les requêtes AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'TP supprimé avec succès !'
                ]);
            }
            
            return redirect()->route('design-graphique.tp.index')
                ->with('success', 'TP supprimé avec succès!');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du TP: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du TP.'
                ], 500);
            }
            
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
     * Page d'index des formations (tous modules)
     */
    public function formationsIndex(): View
    {
        // Détecter le module actuel depuis l'URL
        $currentPath = request()->path();
        $moduleSlug = 'design-graphique'; // Par défaut
        
        if (str_contains($currentPath, 'community-manager')) {
            $moduleSlug = 'community-management';
        } elseif (str_contains($currentPath, 'intelligence-artificielle')) {
            $moduleSlug = 'intelligence-artificielle';
        } elseif (str_contains($currentPath, 'gestion-informatique')) {
            $moduleSlug = 'gestion-informatique';
        }
        
        // Récupérer uniquement les formations du module actuel
        $formationsPubliees = DB::table('formations')
            ->where('status', 'active')
            ->where(function($query) use ($moduleSlug) {
                // Rechercher différentes variantes du nom du module
                $query->whereJsonContains('modules', $moduleSlug)
                      ->orWhereJsonContains('modules', str_replace('-', '_', $moduleSlug))
                      ->orWhereJsonContains('modules', ucwords(str_replace('-', ' ', $moduleSlug)));
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Données minimales pour compatibilité
        $formations = [];
        
        // Modules principaux publiés pour l'étudiant (tolérant au schéma)
        $modulesPrincipaux = [];
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

            // Note: $formationsPubliees est déjà correctement filtré par module dans le bloc précédent (lignes 845-854)
        } catch (\Throwable $e) {
            Log::warning('formationsIndex modulesPrincipaux load failed', ['error' => $e->getMessage()]);
        }

        // Calculer les totaux par catégorie
        $totaux = [
            'photoshop' => 0,
            'illustrator' => 0,
            'indesign' => 0,
            'masterclass' => 0,
        ];

        try {
            // Compter les formations par catégorie en utilisant la jointure
            if (\Illuminate\Support\Facades\Schema::hasTable('formations') && \Illuminate\Support\Facades\Schema::hasTable('categories')) {
                $counts = \Illuminate\Support\Facades\DB::table('formations')
                    ->join('categories', 'formations.category_id', '=', 'categories.id')
                    ->where('formations.status', 'active')
                    ->select('categories.slug', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                    ->groupBy('categories.slug')
                    ->get();
                
                foreach ($counts as $count) {
                    $slug = strtolower($count->slug ?? '');
                    if ($slug === 'photoshop' || str_contains($slug, 'photoshop')) {
                        $totaux['photoshop'] = $count->total;
                    } elseif ($slug === 'illustrator' || str_contains($slug, 'illustrator')) {
                        $totaux['illustrator'] = $count->total;
                    } elseif ($slug === 'indesign' || str_contains($slug, 'indesign')) {
                        $totaux['indesign'] = $count->total;
                    } elseif ($slug === 'masterclass' || $slug === 'master-class' || str_contains($slug, 'master')) {
                        $totaux['masterclass'] = $count->total;
                    }
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Erreur calcul totaux formations', ['error' => $e->getMessage()]);
        }

        return view('formations.index', [
            'title' => 'Formations - Design Graphique',
            'formations' => $formations,
            'modules_principaux' => $modulesPrincipaux,
            'formations_publiees' => $formationsPubliees,
            'totaux' => $totaux,
        ]);
    }

    /**
     * Liste des formations par catégorie
     */
    public function formationsCategory(string $category): View
    {
        // Essayer de charger la catégorie depuis la base de données par son slug
        $categoryModel = \Illuminate\Support\Facades\DB::table('categories')
            ->where('slug', $category)
            ->first();

        // Si pas trouvé par slug, essayer par nom (case-insensitive)
        if (!$categoryModel) {
            $categoryModel = \Illuminate\Support\Facades\DB::table('categories')
                ->whereRaw('LOWER(name) = ?', [strtolower($category)])
                ->first();
        }

        // Si toujours pas trouvé, essayer par slug contenant le terme
        if (!$categoryModel) {
            $categoryModel = \Illuminate\Support\Facades\DB::table('categories')
                ->where('slug', 'like', '%' . $category . '%')
                ->first();
        }

        // Si la catégorie n'existe pas, retourner une collection vide
        if (!$categoryModel) {
            \Log::warning('Catégorie non trouvée', ['category_slug' => $category]);
            $formations = collect();
            $formationsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
                [],
                0,
                9,
                1
            );
        } else {
            // Charger toutes les formations de cette catégorie pour les stats
            $allFormations = \Illuminate\Support\Facades\DB::table('formations')
                ->where('category_id', $categoryModel->id)
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Charger les formations avec pagination (9 par page)
            $formationsPaginated = \Illuminate\Support\Facades\DB::table('formations')
                ->where('category_id', $categoryModel->id)
                ->orderBy('created_at', 'desc')
                ->paginate(9);
            
            $formations = $allFormations; // Pour les stats
            
            \Log::info('Formations trouvées', [
                'category_id' => $categoryModel->id,
                'category_name' => $categoryModel->name ?? 'N/A',
                'formations_count' => $formations->count()
            ]);
        }

        // Calculer les statistiques de la catégorie
        $stats = [
            'total' => $formations->count(),
            'duration' => $formations->sum('duration_weeks') ?? 0,
            'completion_rate' => $formations->avg('completion_rate') ?? 0,
            'new_this_week' => $formations->where('created_at', '>=', now()->subWeek())->count(),
        ];

        return view('formations.category', [
            'category' => $category,
            'categoryModel' => $categoryModel,
            'formations' => $formationsPaginated,
            'stats' => $stats,
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
            $select[] = in_array('vimeo_code',$cols,true)?'vimeo_code':\Illuminate\Support\Facades\DB::raw("'' as vimeo_code");
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

        // Récupérer les fichiers PDF associés à cette formation
        $files = \App\Models\FormationFile::where('formation_id', $id)->get();

        return view('formations.show', [
            'formation' => $formation,
            'related_formations' => $related ?? collect(),
            'files' => $files,
        ]);
    }

    /**
     * Télécharger la vidéo d'une formation
     */
    public function formationsDownload(int $id)
    {
        // Récupérer la formation
        $formation = \Illuminate\Support\Facades\DB::table('formations')->where('id', $id)->first();
        
        if (!$formation) {
            return redirect()->back()->with('error', 'Formation non trouvée');
        }
        
        $videoUrl = null;
        
        // Essayer d'abord video_url
        if (!empty($formation->video_url)) {
            $videoUrl = $formation->video_url;
        }
        // Sinon, extraire l'URL depuis vimeo_code
        elseif (!empty($formation->vimeo_code)) {
            // Si vimeo_code contient un iframe, extraire l'URL src
            if (str_contains($formation->vimeo_code, '<iframe')) {
                preg_match('/src="([^"]+)"/', $formation->vimeo_code, $matches);
                if (isset($matches[1])) {
                    $videoUrl = $matches[1];
                }
            } else {
                // Si c'est juste un code, construire l'URL
                $videoUrl = 'https://player.vimeo.com/video/' . $formation->vimeo_code;
            }
        }
        
        if ($videoUrl) {
            // Rediriger vers l'URL de la vidéo dans un nouvel onglet
            return redirect()->away($videoUrl);
        }
        
        return redirect()->back()->with('error', 'Vidéo non disponible pour le téléchargement');
    }

    /**
     * Télécharger tous les fichiers d'une formation (vidéo + PDFs)
     */
    public function formationsDownloadAll(int $id)
    {
        try {
            // Récupérer la formation
            $formation = \Illuminate\Support\Facades\DB::table('formations')->where('id', $id)->first();
            
            if (!$formation) {
                return redirect()->back()->with('error', 'Formation non trouvée');
            }

            // Récupérer les fichiers PDF
            $files = \App\Models\FormationFile::where('formation_id', $id)->get();

            if ($files->isEmpty()) {
                return redirect()->back()->with('error', 'Aucun fichier à télécharger pour cette formation');
            }

            // Créer une archive ZIP
            $zip = new \ZipArchive();
            $zipFileName = 'formation_' . $id . '_' . time() . '.zip';
            $zipPath = storage_path('app/temp/' . $zipFileName);

            // Créer le répertoire temp s'il n'existe pas
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
                // Ajouter tous les fichiers PDF
                foreach ($files as $file) {
                    $filePath = public_path($file->file_path);
                    if (file_exists($filePath)) {
                        $zip->addFile($filePath, $file->original_name);
                    }
                }

                // Ajouter un fichier README avec les informations de la formation
                $readme = "Formation: {$formation->name}\n";
                $readme .= "Date de téléchargement: " . now()->format('d/m/Y H:i') . "\n\n";
                $readme .= "Fichiers inclus:\n";
                foreach ($files as $file) {
                    $readme .= "- {$file->original_name} ({$file->formatted_size})\n";
                }
                
                if (!empty($formation->video_url) || !empty($formation->vimeo_code)) {
                    $readme .= "\nVidéo disponible en ligne sur la plateforme.\n";
                }

                $zip->addFromString('README.txt', $readme);
                $zip->close();

                // Télécharger le fichier ZIP
                return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
            }

            return redirect()->back()->with('error', 'Erreur lors de la création de l\'archive');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur téléchargement formation: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors du téléchargement');
        }
    }

    /**
     * Liste des formations par catégorie d'édition du profil étudiant.
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

    /**
     * Afficher la page des événements
     */
    public function eventsIndex(): View
    {
        $user = Auth::user();
        
        return view('events.index', [
            'user' => $user,
            'events' => []
        ]);
    }

    /**
     * Afficher la page des actualités EVC
     */
    public function actualitesIndex(): View
    {
        $user = Auth::user();
        
        // Pour le moment, des actualités fictives
        // À remplacer par une requête réelle vers votre table actualités
        $actualites = [
            [
                'id' => 1,
                'titre' => 'Nouvelle formation en Design UX/UI',
                'description' => 'L\'EVC lance une nouvelle formation complète en Design UX/UI pour répondre aux besoins du marché.',
                'image' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=800',
                'categorie' => 'Formation',
                'auteur' => 'Direction EVC',
                'date' => '2024-03-15',
                'vues' => 1250
            ],
            [
                'id' => 2,
                'titre' => 'Partenariat avec Adobe Creative Cloud',
                'description' => 'L\'EVC annonce un partenariat stratégique avec Adobe pour offrir des licences gratuites.',
                'image' => 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=800',
                'categorie' => 'Partenariat',
                'auteur' => 'Service Communication',
                'date' => '2024-03-12',
                'vues' => 2340
            ],
            [
                'id' => 3,
                'titre' => 'Gagnants du concours Design 2024',
                'description' => 'Découvrez les projets lauréats du concours annuel de design graphique.',
                'image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=800',
                'categorie' => 'Événement',
                'auteur' => 'Jury EVC',
                'date' => '2024-03-10',
                'vues' => 3150
            ],
            [
                'id' => 4,
                'titre' => 'Webinaire gratuit : Tendances Design 2024',
                'description' => 'Inscrivez-vous à notre prochain webinaire sur les tendances du design.',
                'image' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800',
                'categorie' => 'Webinaire',
                'auteur' => 'Équipe Pédagogique',
                'date' => '2024-03-08',
                'vues' => 890
            ],
            [
                'id' => 5,
                'titre' => 'Nouvelle plateforme e-learning lancée',
                'description' => 'L\'EVC dévoile sa nouvelle plateforme d\'apprentissage en ligne.',
                'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800',
                'categorie' => 'Technologie',
                'auteur' => 'Direction Technique',
                'date' => '2024-03-05',
                'vues' => 1560
            ]
        ];
        
        return view('actualites.index', [
            'user' => $user,
            'actualites' => $actualites
        ]);
    }

    /**
     * Afficher la bibliothèque digitale de documents filtrée par module
     */
    public function documentsIndex(): View
    {
        $user = Auth::user();
        
        // Récupérer le module actuel depuis l'URL (ex: design-graphique)
        $currentModule = request()->segment(3);
        
        // Récupérer les documents de la bibliothèque filtrés par module
        $libraryItems = \App\Models\Library::where('status', 'active')
            ->whereJsonContains('recipients', $currentModule)
            ->with('libraryCategory')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Transformer les données pour la vue
        $documents = $libraryItems->map(function($item) {
            return [
                'id' => $item->id,
                'titre' => $item->title,
                'description' => $item->libraryCategory->name ?? 'Document',
                'categorie' => $item->libraryCategory->name ?? 'Autres',
                'type' => strtoupper($item->file_type),
                'taille' => number_format($item->size / 1024, 2) . ' KB',
                'format' => '.' . $item->file_type,
                'telechargements' => $item->downloads_count ?? 0,
                'date_ajout' => $item->created_at->format('Y-m-d'),
                'image' => $item->path ? asset('storage/' . $item->path) : null,
                'lien' => $item->pdf_path ? asset('storage/' . $item->pdf_path) : ($item->download_url ?? '#'),
            ];
        })->toArray();
        
        // Récupérer les catégories de bibliothèque et les trier pour mettre "Ebooks" en premier
        $categories = \App\Models\LibraryCategory::all()->sortBy(function($category) {
            // Ebooks en premier (priorité 0), les autres après (priorité 1)
            return stripos($category->name, 'ebook') !== false ? 0 : 1;
        })->values();
        
        // Organiser les documents par catégorie de bibliothèque
        $documentsParCategorie = [];
        
        foreach ($categories as $category) {
            $documentsParCategorie[$category->name] = array_filter($documents, function($doc) use ($category) {
                return stripos($doc['categorie'], $category->name) !== false;
            });
        }
        
        // Ajouter une catégorie "Autres" pour les documents sans catégorie
        $documentsParCategorie['Autres'] = array_filter($documents, function($doc) use ($categories) {
            foreach ($categories as $category) {
                if (stripos($doc['categorie'], $category->name) !== false) {
                    return false;
                }
            }
            return true;
        });
        
        // Statistiques dynamiques par catégorie
        $stats = [
            'total' => count($documents)
        ];
        
        // Ajouter les stats pour chaque catégorie
        foreach ($categories as $category) {
            $key = strtolower(str_replace(' ', '_', $category->name));
            $stats[$key] = count($documentsParCategorie[$category->name]);
        }
        $stats['autres'] = count($documentsParCategorie['Autres']);
        
        return view('documents.index', [
            'user' => $user,
            'documents' => $documents,
            'documentsParCategorie' => $documentsParCategorie,
            'categories' => $categories,
            'stats' => $stats,
            'currentModule' => $currentModule
        ]);
    }

    /**
     * Télécharger un document et incrémenter le compteur
     */
    public function downloadDocument($id)
    {
        $document = \App\Models\Library::findOrFail($id);
        
        // Incrémenter le compteur de téléchargements
        $document->increment('downloads_count');
        
        // Récupérer le chemin du fichier PDF
        if ($document->pdf_path) {
            $filePath = storage_path('app/public/' . $document->pdf_path);
            
            if (file_exists($filePath)) {
                return response()->download($filePath, $document->title . '.pdf');
            }
        }
        
        // Si le fichier n'existe pas, retourner avec erreur
        return redirect()->back()->with('error', 'Fichier non disponible');
    }

    // Reste du code (méthode communautéIndex existe déjà plus bas)
    
    private function getDemoDocuments() {
        return [
            // Catégorie Logiciels
            [
                'id' => 1,
                'titre' => 'Adobe Photoshop 2024',
                'description' => 'Logiciel professionnel de retouche photo et design graphique',
                'categorie' => 'Logiciels',
                'type' => 'Logiciel',
                'taille' => '2.5 GB',
                'format' => '.exe',
                'telechargements' => 1250,
                'date_ajout' => '2024-03-15',
                'lien' => '#'
            ],
            [
                'id' => 2,
                'titre' => 'Adobe Illustrator 2024',
                'description' => 'Logiciel de création vectorielle professionnel',
                'categorie' => 'Logiciels',
                'type' => 'Logiciel',
                'taille' => '1.8 GB',
                'format' => '.exe',
                'telechargements' => 980,
                'date_ajout' => '2024-03-14',
                'lien' => '#'
            ],
            [
                'id' => 3,
                'titre' => 'Adobe InDesign 2024',
                'description' => 'Logiciel de mise en page et publication assistée par ordinateur',
                'categorie' => 'Logiciels',
                'type' => 'Logiciel',
                'taille' => '1.2 GB',
                'format' => '.exe',
                'telechargements' => 750,
                'date_ajout' => '2024-03-13',
                'lien' => '#'
            ],
            
            // Catégorie Ebook
            [
                'id' => 4,
                'titre' => 'Guide Complet du Design Graphique',
                'description' => 'Manuel complet couvrant tous les aspects du design graphique moderne',
                'categorie' => 'Ebook',
                'type' => 'PDF',
                'taille' => '45 MB',
                'format' => '.pdf',
                'telechargements' => 2340,
                'date_ajout' => '2024-03-12',
                'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400',
                'lien' => '#'
            ],
            [
                'id' => 5,
                'titre' => 'Théorie des Couleurs pour Designers',
                'description' => 'Tout ce que vous devez savoir sur la psychologie et l\'harmonie des couleurs',
                'categorie' => 'Ebook',
                'type' => 'PDF',
                'taille' => '28 MB',
                'format' => '.pdf',
                'telechargements' => 1890,
                'date_ajout' => '2024-03-10',
                'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400',
                'lien' => '#'
            ],
            [
                'id' => 6,
                'titre' => 'Typographie : L\'art de la mise en page',
                'description' => 'Maîtrisez l\'art de la typographie pour des designs professionnels',
                'categorie' => 'Ebook',
                'type' => 'PDF',
                'taille' => '32 MB',
                'format' => '.pdf',
                'telechargements' => 1560,
                'date_ajout' => '2024-03-08',
                'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400',
                'lien' => '#'
            ],
            [
                'id' => 7,
                'titre' => 'Design UI/UX : Guide pratique',
                'description' => 'Créez des interfaces utilisateur intuitives et attrayantes',
                'categorie' => 'Ebook',
                'type' => 'PDF',
                'taille' => '38 MB',
                'format' => '.pdf',
                'telechargements' => 2100,
                'date_ajout' => '2024-03-05',
                'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400',
                'lien' => '#'
            ],
            
            // Catégorie Autres
            [
                'id' => 8,
                'titre' => 'Pack de Brushes Photoshop',
                'description' => 'Collection de 500+ brushes pour Photoshop',
                'categorie' => 'Autres',
                'type' => 'Ressource',
                'taille' => '250 MB',
                'format' => '.abr',
                'telechargements' => 3200,
                'date_ajout' => '2024-03-03',
                'lien' => '#'
            ],
            [
                'id' => 9,
                'titre' => 'Templates InDesign Magazine',
                'description' => 'Modèles professionnels de magazines prêt à l\'emploi',
                'categorie' => 'Autres',
                'type' => 'Template',
                'taille' => '180 MB',
                'format' => '.indd',
                'telechargements' => 1450,
                'date_ajout' => '2024-03-01',
                'lien' => '#'
            ],
            [
                'id' => 10,
                'titre' => 'Pack de Fonts Premium',
                'description' => 'Collection de 100 polices premium pour vos projets',
                'categorie' => 'Autres',
                'type' => 'Police',
                'taille' => '120 MB',
                'format' => '.ttf/.otf',
                'telechargements' => 2890,
                'date_ajout' => '2024-02-28',
                'lien' => '#'
            ]
        ];
        
        // Organiser les documents par catégorie
        $documentsParCategorie = [
            'Logiciels' => array_filter($documents, fn($doc) => $doc['categorie'] === 'Logiciels'),
            'Ebook' => array_filter($documents, fn($doc) => $doc['categorie'] === 'Ebook'),
            'Autres' => array_filter($documents, fn($doc) => $doc['categorie'] === 'Autres')
        ];
        
        // Statistiques
        $stats = [
            'total' => count($documents),
            'logiciels' => count($documentsParCategorie['Logiciels']),
            'ebooks' => count($documentsParCategorie['Ebook']),
            'autres' => count($documentsParCategorie['Autres'])
        ];
        
        return view('documents.index', [
            'user' => $user,
            'documents' => $documents,
            'documentsParCategorie' => $documentsParCategorie,
            'stats' => $stats
        ]);
    }

    /**
     * Afficher la page communauté
     */
    public function communauteIndex(): View
    {
        $user = Auth::user();
        
        // Statistiques de la communauté (avec les clés attendues par la vue)
        $communityStats = [
            'active_members' => 350,
            'total_messages' => 1250,
            'shared_projects' => 45,
            'graduates' => 128
        ];
        
        // Statistiques des réseaux sociaux
        $socialMediaStats = [
            'facebook' => [
                'formatted' => '12.5K',
                'trend' => 'up'
            ],
            'instagram' => [
                'formatted' => '8.3K',
                'trend' => 'up'
            ],
            'tiktok' => [
                'formatted' => '15.2K',
                'trend' => 'up'
            ],
            'youtube' => [
                'formatted' => '5.8K',
                'trend' => 'up'
            ],
            'linkedin' => [
                'formatted' => '3.4K',
                'trend' => 'up'
            ]
        ];
        
        return view('communaute.index', [
            'user' => $user,
            'communityStats' => $communityStats,
            'socialMediaStats' => $socialMediaStats
        ]);
    }

    /**
     * Afficher la page du programme de formation
     */
    public function programmeIndex(): View
    {
        $user = Auth::user();
        
        return view('programme.index', [
            'user' => $user
        ]);
    }

    /**
     * Afficher la page des paiements
     */
    public function paiementsIndex(): View
    {
        $user = Auth::user();
        
        return view('paiements.index', [
            'user' => $user
        ]);
    }

    /**
     * Afficher la page de fin de formation
     */
    public function finFormationIndex(): View
    {
        $user = Auth::user();
        
        return view('fin-formation.index', [
            'user' => $user
        ]);
    }
}
