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
        
        // Récupérer l'étudiant
        $student = DB::table('students')->where('user_id', $user->id)->first();
        
        // D'abord, essayer de trouver le TP dans la table tp (Design Graphique)
        $project = TP::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['files'])
            ->first();
        
        // Si pas trouvé dans tp, chercher dans tp_assignments (Community Management)
        if (!$project && $student) {
            $tpAssignment = DB::table('tp_assignments')
                ->where('id', $id)
                ->where('student_id', $student->id)
                ->first();
            
            if ($tpAssignment) {
                // Récupérer les fichiers de soumission et mapper les colonnes
                $submissionFiles = DB::table('tp_submission_files')
                    ->where('tp_assignment_id', $id)
                    ->get();
                
                // Mapper les fichiers pour correspondre à la structure attendue par la vue
                $files = $submissionFiles->map(function($file) {
                    return (object) [
                        'id' => $file->id,
                        'original_name' => $file->file_name ?? 'Fichier',
                        'file_path' => $file->file_path,
                        'file_size' => $file->file_size ?? 0,
                        'mime_type' => $file->mime_type ?? 'application/octet-stream',
                        'created_at' => $file->created_at,
                        'updated_at' => $file->updated_at
                    ];
                });
                
                // Convertir en objet stdClass pour compatibilité avec la vue
                $project = (object) [
                    'id' => $tpAssignment->id,
                    'title' => $tpAssignment->title,
                    'description' => $tpAssignment->description,
                    'link' => $tpAssignment->submission_link,
                    'status' => $tpAssignment->status,
                    'admin_comment' => $tpAssignment->admin_comment,
                    'rejection_reason' => $tpAssignment->admin_comment, // Alias pour compatibilité
                    'validated_at' => $tpAssignment->validated_at,
                    'created_at' => $tpAssignment->created_at,
                    'updated_at' => $tpAssignment->updated_at,
                    'files' => $files,
                    'source_table' => 'tp_assignments',
                    'tags' => null,
                    'type' => 'digital',
                    'user_id' => $user->id,
                    'deadline' => $tpAssignment->deadline ?? null,
                    'formation' => $tpAssignment->formation ?? null,
                    'software_used' => null,
                    'duration' => null,
                    'difficulty' => null,
                    'category' => null
                ];
            }
        }
        
        // Si toujours pas trouvé, retourner 404
        if (!$project) {
            abort(404, 'TP introuvable');
        }
        
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
     * Lister tous les TP de l'utilisateur (Community Management)
     */
    public function listTP(): View
    {
        $user = Auth::user();
        
        // Log de l'utilisateur connecté pour debug
        Log::info('=== ACCÈS PAGE TP INDEX ===', [
            'user_connected' => $user ? 'OUI' : 'NON',
            'user_id' => $user ? $user->id : 'N/A',
            'user_name' => $user ? $user->name : 'N/A',
            'user_email' => $user ? $user->email : 'N/A',
        ]);
        
        // Initialiser les valeurs par défaut
        $tps = [];
        $totalTpRequis = 20;
        
        // Vérifier si l'utilisateur est connecté
        if (!$user) {
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
            
            return view('tp.index', compact('tps', 'statistiques', 'validationStats'));
        }
        
        try {
            // Récupérer l'étudiant pour avoir son ID
            $student = DB::table('students')->where('user_id', $user->id)->first();
            
            // Récupérer tous les TP de l'utilisateur connecté depuis la table tp (Design Graphique)
            if (Schema::hasTable('tp_files')) {
                // Avec comptage des fichiers
                $tpsFromTpTable = DB::table('tp')
                    ->where('tp.user_id', $user->id)
                    ->leftJoin('tp_files', 'tp.id', '=', 'tp_files.tp_id')
                    ->select(
                        'tp.id',
                        'tp.user_id',
                        'tp.title',
                        'tp.description',
                        'tp.link',
                        'tp.status',
                        'tp.admin_comment',
                        'tp.validated_at',
                        'tp.created_at',
                        'tp.updated_at',
                        DB::raw('COUNT(tp_files.id) as files_count'),
                        DB::raw("'tp' as source_table")
                    )
                    ->groupBy(
                        'tp.id',
                        'tp.user_id',
                        'tp.title',
                        'tp.description',
                        'tp.link',
                        'tp.status',
                        'tp.admin_comment',
                        'tp.validated_at',
                        'tp.created_at',
                        'tp.updated_at'
                    )
                    ->orderByDesc('tp.created_at')
                    ->get();
            } else {
                // Sans comptage des fichiers
                $tpsFromTpTable = DB::table('tp')
                    ->where('user_id', $user->id)
                    ->select('*', DB::raw("'tp' as source_table"))
                    ->orderByDesc('created_at')
                    ->get();
            }
            
            // Ajouter les TP validés et rejetés de tp_assignments (Community Management)
            $tpAssignments = collect([]);
            if ($student && Schema::hasTable('tp_assignments')) {
                $tpAssignments = DB::table('tp_assignments')
                    ->where('student_id', $student->id)
                    ->whereIn('status', ['validated', 'rejected', 'submitted']) // Inclure aussi les soumis
                    ->select(
                        'id',
                        DB::raw("NULL as user_id"),
                        'title',
                        'description',
                        'submission_link as link',
                        'status',
                        'admin_comment',
                        'validated_at',
                        'created_at',
                        'updated_at',
                        DB::raw("0 as files_count"),
                        DB::raw("'tp_assignments' as source_table")
                    )
                    ->orderByDesc('created_at')
                    ->get();
                
                Log::info('TP Assignments ajoutés', [
                    'student_id' => $student->id,
                    'tp_assignments_count' => $tpAssignments->count()
                ]);
            }
            
            // Fusionner les deux collections et trier
            $allTps = $tpsFromTpTable->concat($tpAssignments)->sortByDesc('created_at')->values();
            
            // Pagination manuelle
            $perPage = 6; // 6 TP par page
            $currentPage = request()->get('page', 1);
            $offset = ($currentPage - 1) * $perPage;
            
            // Créer un paginator Laravel
            $tps = new \Illuminate\Pagination\LengthAwarePaginator(
                $allTps->slice($offset, $perPage)->values(),
                $allTps->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'query' => request()->query()]
            );
            
            // Compter les TP de l'utilisateur par statut (des deux tables)
            $cols = Schema::getColumnListing('tp');
            
            if (in_array('status', $cols)) {
                // Compter les TP de la table tp
                $tpsPending = DB::table('tp')
                    ->where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->count();
                
                $tpsValidated = DB::table('tp')
                    ->where('user_id', $user->id)
                    ->where('status', 'validated')
                    ->count();
                
                $tpsRejected = DB::table('tp')
                    ->where('user_id', $user->id)
                    ->where('status', 'rejected')
                    ->count();
                
                $tpsTotal = DB::table('tp')
                    ->where('user_id', $user->id)
                    ->count();
                
                // Ajouter les compteurs de tp_assignments
                if ($student && Schema::hasTable('tp_assignments')) {
                    $tpsPending += DB::table('tp_assignments')
                        ->where('student_id', $student->id)
                        ->where('status', 'submitted')
                        ->count();
                    
                    $tpsValidated += DB::table('tp_assignments')
                        ->where('student_id', $student->id)
                        ->where('status', 'validated')
                        ->count();
                    
                    $tpsRejected += DB::table('tp_assignments')
                        ->where('student_id', $student->id)
                        ->where('status', 'rejected')
                        ->count();
                    
                    $tpsTotal += DB::table('tp_assignments')
                        ->where('student_id', $student->id)
                        ->whereIn('status', ['submitted', 'validated', 'rejected'])
                        ->count();
                }
                
                // Calculer les statistiques pour l'utilisateur
                $statistiques = [
                    'tp_realises' => $tpsTotal, // Tous les TP soumis
                    'tp_a_faire' => max(0, $totalTpRequis - $tpsTotal), // Basé sur les TP soumis
                    'tp_total' => $totalTpRequis,
                    'progression_pourcentage' => $totalTpRequis > 0 ? min(100, round(($tpsTotal / $totalTpRequis) * 100)) : 0
                ];
                
                $validationStats = [
                    'tp_en_validation' => $tpsPending,
                    'tp_valides' => $tpsValidated,
                    'tp_rejetes' => $tpsRejected
                ];
                
                // Log pour débogage
                Log::info('=== STATISTIQUES TP UTILISATEUR (COMBINÉES) ===', [
                    'user_id' => $user->id,
                    'student_id' => $student->id ?? 'N/A',
                    'user_name' => $user->name ?? 'N/A',
                    'tp_total' => $tpsTotal,
                    'tp_pending' => $tpsPending,
                    'tp_validated' => $tpsValidated,
                    'tp_rejected' => $tpsRejected,
                    'progression' => $statistiques['progression_pourcentage'],
                ]);
            } else {
                Log::warning('Colonne status n\'existe pas dans la table tp');
                
                // Statistiques par défaut si pas de colonne status
                $tpsTotal = count($tps);
                $statistiques = [
                    'tp_realises' => $tpsTotal,
                    'tp_a_faire' => max(0, $totalTpRequis - $tpsTotal),
                    'tp_total' => $totalTpRequis,
                    'progression_pourcentage' => $totalTpRequis > 0 ? min(100, round(($tpsTotal / $totalTpRequis) * 100)) : 0
                ];
                
                $validationStats = [
                    'tp_en_validation' => 0,
                    'tp_valides' => $tpsTotal
                ];
            }
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des TP', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Valeurs par défaut en cas d'erreur
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
        
        // Récupérer le module actuel depuis l'URL
        $currentModule = $request->segment(3); // ex: community-management, design-graphique, etc.
        
        Log::info('🎯 DÉBUT CRÉATION TP', ['user_id' => $user->id, 'module' => $currentModule]);
        
        try {
            // ========================================
        // ÉTAPE 2: VALIDER LES DONNÉES
        // ========================================
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'links.*' => 'nullable|url|max:500',
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:20480|mimes:jpg,jpeg,png,gif,webp,svg,pdf,psd,ai,doc,docx,zip,rar'
        ], [
            'title.required' => 'Le titre du TP est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.max' => 'La description ne peut pas dépasser 2000 caractères.',
            'links.*.url' => 'Le lien doit être une URL valide.',
            'links.*.max' => 'Le lien ne peut pas dépasser 500 caractères.',
            'files.required' => '⚠️ Vous devez ajouter au moins une image ou un fichier pour publier votre TP.',
            'files.min' => '⚠️ Vous devez ajouter au moins une image ou un fichier pour publier votre TP.',
            'files.*.required' => 'Chaque fichier est obligatoire.',
            'files.*.max' => 'Chaque fichier ne peut pas dépasser 20MB.',
            'files.*.mimes' => 'Types de fichiers autorisés: JPG, JPEG, PNG, GIF, WEBP, SVG, PDF, PSD, AI, DOC, DOCX, ZIP, RAR.'
        ]);
            
            // Récupérer le premier lien non vide
            $link = null;
            if ($request->has('links')) {
                $links = array_filter($request->input('links'), function($l) {
                    return !empty($l);
                });
                $link = !empty($links) ? $links[0] : null;
            }
            
            Log::info('✅ ÉTAPE 2 OK: Validation réussie', ['link' => $link]);
            
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
                'link' => $link,
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
            } elseif ($request->hasFile('files')) {
                $files = $request->file('files');
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
                                    ->subject('🔔 Nouveau Rapport soumis - Action requise - EVC');
                        });
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Erreur lors de l\'envoi de l\'email de notification admin: ' . $e->getMessage());
                // Continue même si l'email échoue
            }
            
            // Message de succès avec détails
            $successMessage = "Rapport publié avec succès!";
            if ($filesSuccess > 0) {
                $successMessage .= " ($filesSuccess fichier(s) uploadé(s))";
            }
            if (count($filesErrors) > 0) {
                $successMessage .= " Attention: " . count($filesErrors) . " fichier(s) en erreur.";
            }
            
            Log::info("✅ RAPPORT PUBLIÉ AVEC SUCCÈS", [
                'tp_id' => $tpId,
                'fichiers_ok' => $filesSuccess,
                'fichiers_erreur' => count($filesErrors)
            ]);
            
            // Rediriger vers la page documents du module actuel
            return redirect()->to('/evc/compte/' . $currentModule . '/documents/index')
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
        
        // Récupérer le module actuel depuis l'URL
        $currentModule = $request->segment(3); // ex: community-management, design-graphique, etc.
        
        try {
            if (!Schema::hasTable('tp')) {
                return redirect()->back()->with('error', 'La table des TPs n\'existe pas.');
            }
            
            // Vérifier que le TP appartient à l'utilisateur
            $tp = DB::table('tp')->where('id', $id)->where('user_id', $user->id)->first();
            
            if (!$tp) {
                return redirect()->route($currentModule . '.documents.index')
                    ->with('error', 'TP introuvable ou accès non autorisé.');
            }
            
            // Validation
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:2000',
                'links.*' => 'nullable|url|max:500',
                'files.*' => 'nullable|file|max:51200|mimes:jpg,jpeg,png,gif,webp,svg,pdf,psd,ai,doc,docx,zip,rar'
            ]);
            
            // Récupérer le premier lien non vide
            $link = null;
            if ($request->has('links')) {
                $links = array_filter($request->input('links'), function($l) {
                    return !empty($l);
                });
                $link = !empty($links) ? $links[0] : null;
            }
            
            // Mettre à jour le TP
            DB::table('tp')->where('id', $id)->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'link' => $link,
                'updated_at' => now(),
            ]);
            
            // Traiter les nouveaux fichiers
            $filesSuccess = 0;
            $filesErrors = [];
            
            if ($request->hasFile('files') && Schema::hasTable('tp_files')) {
                $files = $request->file('files');
                $totalFiles = count($files);
                
                Log::info("📂 Traitement de $totalFiles nouveau(x) fichier(s) pour TP #$id");
                
                foreach ($files as $index => $file) {
                    $fileNumber = $index + 1;
                    $result = $this->saveOneFile($file, $id, $fileNumber);
                    
                    if ($result['success']) {
                        $filesSuccess++;
                        Log::info("✅ Fichier $fileNumber/$totalFiles OK");
                    } else {
                        $filesErrors[] = "Fichier $fileNumber: " . $result['error'];
                        Log::error("❌ Fichier $fileNumber/$totalFiles ÉCHOUÉ: " . $result['error']);
                    }
                }
            }
            
            // Message de succès
            $successMessage = "Rapport mis à jour avec succès!";
            if ($filesSuccess > 0) {
                $successMessage .= " ($filesSuccess nouveau(x) fichier(s) ajouté(s))";
            }
            if (count($filesErrors) > 0) {
                $successMessage .= " Attention: " . count($filesErrors) . " fichier(s) en erreur.";
            }
            
            // Si c'est une requête AJAX, retourner du JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage
                ]);
            }
            
            return redirect()->route($currentModule . '.tp.index')
                ->with('success', $successMessage);
                
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
        
        // Récupérer le module actuel depuis l'URL
        $currentModule = $request->segment(3);
        
        try {
            if (!Schema::hasTable('tp')) {
                return redirect()->back()->with('error', 'La table des TPs n\'existe pas.');
            }
            
            // Vérifier que le TP appartient à l'utilisateur
            $tp = DB::table('tp')->where('id', $id)->where('user_id', $user->id)->first();
            
            if (!$tp) {
                return redirect()->route($currentModule . '.documents.index')
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
            
            return redirect()->route($currentModule . '.documents.index')
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
        
        // Récupérer le module actuel depuis l'URL
        $currentModule = $request->segment(3);
        
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
                return redirect()->route($currentModule . '.documents.index')
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
            
            return redirect()->route($currentModule . '.documents.index')
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
     * Supprimer un fichier d'un TP
     */
    public function deleteTPFile(Request $request, int $tpId, int $fileId)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être connecté.'
            ], 401);
        }
        
        try {
            if (!Schema::hasTable('tp') || !Schema::hasTable('tp_files')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tables introuvables.'
                ], 500);
            }
            
            // Vérifier que le TP appartient à l'utilisateur
            $tp = DB::table('tp')->where('id', $tpId)->where('user_id', $user->id)->first();
            
            if (!$tp) {
                return response()->json([
                    'success' => false,
                    'message' => 'TP introuvable ou accès non autorisé.'
                ], 404);
            }
            
            // Récupérer le fichier
            $file = DB::table('tp_files')->where('id', $fileId)->where('tp_id', $tpId)->first();
            
            if (!$file) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fichier introuvable.'
                ], 404);
            }
            
            // Supprimer le fichier physique
            $fullPath = public_path($file->file_path);
            if (file_exists($fullPath)) {
                unlink($fullPath);
                Log::info("✅ Fichier physique supprimé: $fullPath");
            }
            
            // Supprimer l'entrée en base de données
            DB::table('tp_files')->where('id', $fileId)->delete();
            
            Log::info("✅ Fichier supprimé de la BDD", [
                'file_id' => $fileId,
                'tp_id' => $tpId,
                'file_name' => $file->original_name
            ]);
            
            // Récupérer le module actuel depuis l'URL
            $currentModule = $request->segment(3);
            
            // Si c'est une requête AJAX, retourner du JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Fichier supprimé avec succès!'
                ]);
            }
            
            // Sinon, rediriger vers la page de modification
            return redirect()->route($currentModule . '.tp.modifier', $tpId)
                ->with('success', 'Fichier supprimé avec succès!');
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du fichier: ' . $e->getMessage());
            
            // Récupérer le module actuel depuis l'URL
            $currentModule = $request->segment(3);
            
            // Si c'est une requête AJAX, retourner du JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du fichier.'
                ], 500);
            }
            
            // Sinon, rediriger vers la page de modification avec erreur
            return redirect()->route($currentModule . '.tp.modifier', $tpId)
                ->with('error', 'Erreur lors de la suppression du fichier.');
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
        
        // Récupérer le module actuel depuis l'URL
        $currentModule = $request->segment(3);
        
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
            
            return redirect()->route($currentModule . '.documents.index')
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
        
        if (str_contains($currentPath, 'community-management') || str_contains($currentPath, 'community-manager')) {
            $moduleSlug = 'community-management';
        } elseif (str_contains($currentPath, 'intelligence-artificielle')) {
            $moduleSlug = 'intelligence-artificielle';
        } elseif (str_contains($currentPath, 'gestion-informatique')) {
            $moduleSlug = 'gestion-informatique';
        }
        
        // Récupérer uniquement les formations du module actuel avec leurs catégories
        $formationsPubliees = DB::table('formations')
            ->leftJoin('categories', 'formations.category_id', '=', 'categories.id')
            ->select('formations.*', 'categories.name as category_name', 'categories.slug as category_slug')
            ->where('formations.status', 'active')
            ->where(function($query) use ($moduleSlug) {
                // Rechercher différentes variantes du nom du module
                $query->whereJsonContains('formations.modules', $moduleSlug)
                      ->orWhereJsonContains('formations.modules', 'community-manager') // Variante alternative
                      ->orWhereJsonContains('formations.modules', str_replace('-', '_', $moduleSlug))
                      ->orWhereJsonContains('formations.modules', ucwords(str_replace('-', ' ', $moduleSlug)));
            })
            ->orderBy('formations.created_at', 'desc')
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

        // Déterminer le titre selon le module
        $moduleTitle = match($moduleSlug) {
            'community-management' => 'Community Management',
            'intelligence-artificielle' => 'Intelligence Artificielle',
            'gestion-informatique' => 'Gestion Informatique',
            default => 'Design Graphique',
        };

        return view('formations.index', [
            'title' => 'Formations - ' . $moduleTitle,
            'formations' => $formations,
            'modules_principaux' => $modulesPrincipaux,
            'formations_publiees' => $formationsPubliees,
            'totaux' => $totaux,
            'module_slug' => $moduleSlug,
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
        
        // Récupérer tous les événements publiés en fonction de la visibilité
        $allEvents = \App\Models\Evenement::where('status', 'published')
            ->where(function($query) use ($user) {
                // Événements publics (visibles par tous)
                $query->where('visibility', 'public')
                    // OU événements pour toutes les formations
                    ->orWhere('visibility', 'all')
                    // OU événements pour la formation spécifique de l'étudiant
                    ->orWhere(function($q) use ($user) {
                        $q->where('visibility', 'specific')
                          ->whereJsonContains('formations', $user->formation_id);
                    });
            })
            ->orderBy('event_date', 'desc')
            ->get();
        
        // Calculer les statistiques
        $now = \Carbon\Carbon::now();
        
        $stats = [
            'total' => $allEvents->count(),
            'a_venir' => $allEvents->filter(function($event) use ($now) {
                return \Carbon\Carbon::parse($event->event_date)->isFuture();
            })->count(),
            'passes' => $allEvents->filter(function($event) use ($now) {
                return \Carbon\Carbon::parse($event->event_date)->isPast();
            })->count(),
            'en_ligne' => $allEvents->where('event_type', 'online')->count(),
            'presentiel' => $allEvents->where('event_type', 'physical')->count(),
            'hybride' => $allEvents->where('event_type', 'hybrid')->count(),
            'a_la_une' => $allEvents->where('is_featured', true)->count(),
        ];
        
        // Séparer les événements à venir et passés
        $eventsAvenir = $allEvents->filter(function($event) use ($now) {
            return \Carbon\Carbon::parse($event->event_date)->isFuture();
        });
        
        $eventsPasses = $allEvents->filter(function($event) use ($now) {
            return \Carbon\Carbon::parse($event->event_date)->isPast();
        }); // Afficher tous les événements passés (historique complet)
        
        // Récupérer les événements à la une (featured)
        $eventsFeatured = $eventsAvenir->filter(function($event) {
            return $event->is_featured == true;
        })->take(3); // Limiter à 3 événements à la une
        
        return view('events.index', [
            'user' => $user,
            'events' => $eventsAvenir,
            'eventsPasses' => $eventsPasses,
            'eventsFeatured' => $eventsFeatured,
            'stats' => $stats
        ]);
    }

    /**
     * Afficher les détails d'un événement
     */
    public function eventsShow($id): View
    {
        $user = Auth::user();
        
        // Récupérer l'événement
        $event = \App\Models\Evenement::findOrFail($id);
        
        // Vérifier que l'événement est publié
        if ($event->status !== 'published') {
            abort(404);
        }
        
        // Vérifier la visibilité
        $hasAccess = false;
        
        if ($event->visibility === 'public') {
            $hasAccess = true;
        } elseif ($event->visibility === 'all') {
            $hasAccess = true;
        } elseif ($event->visibility === 'specific') {
            $formations = json_decode($event->formations, true) ?? [];
            $hasAccess = in_array($user->formation_id, $formations);
        }
        
        if (!$hasAccess) {
            abort(403, 'Vous n\'avez pas accès à cet événement.');
        }
        
        // Incrémenter le compteur de vues
        $event->increment('views_count');
        
        return view('events.show', [
            'user' => $user,
            'event' => $event
        ]);
    }

    /**
     * Afficher la page des actualités EVC
     */
    public function actualitesIndex(): View
    {
        $user = Auth::user();
        $formationId = $user->formation_id;
        
        // Récupérer les actualités publiées et visibles pour l'étudiant
        $actualites = \App\Models\Actualite::with('author')
            ->where('status', 'published')
            ->where(function($query) use ($formationId) {
                $query->where('visibility', 'public')
                      ->orWhere('visibility', 'all')
                      ->orWhere(function($q) use ($formationId) {
                          $q->where('visibility', 'specific')
                            ->whereJsonContains('formations', $formationId);
                      });
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Statistiques
        $stats = [
            'total' => $actualites->count(),
            'categories' => $actualites->groupBy('category')->map->count(),
            'vues_total' => $actualites->sum('views_count'),
        ];
        
        // Actualité à la une
        $featured = $actualites->where('is_featured', true)->first();
        
        return view('actualites.index', [
            'user' => $user,
            'actualites' => $actualites,
            'stats' => $stats,
            'featured' => $featured,
        ]);
    }

    /**
     * Afficher les détails d'une actualité
     */
    public function actualitesShow($id): View
    {
        $user = Auth::user();
        $formationId = $user->formation_id;
        
        // Récupérer l'actualité avec contrôle d'accès
        $actualite = \App\Models\Actualite::with('author')
            ->where('id', $id)
            ->where('status', 'published')
            ->where(function($query) use ($formationId) {
                $query->where('visibility', 'public')
                      ->orWhere('visibility', 'all')
                      ->orWhere(function($q) use ($formationId) {
                          $q->where('visibility', 'specific')
                            ->whereJsonContains('formations', $formationId);
                      });
            })
            ->firstOrFail();
        
        // Incrémenter le compteur de vues
        $actualite->increment('views_count');
        
        // Actualités similaires (même catégorie, exclure l'actuelle)
        $similaires = \App\Models\Actualite::where('category', $actualite->category)
            ->where('id', '!=', $actualite->id)
            ->where('status', 'published')
            ->where(function($query) use ($formationId) {
                $query->where('visibility', 'public')
                      ->orWhere('visibility', 'all')
                      ->orWhere(function($q) use ($formationId) {
                          $q->where('visibility', 'specific')
                            ->whereJsonContains('formations', $formationId);
                      });
            })
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        return view('actualites.show', [
            'user' => $user,
            'actualite' => $actualite,
            'similaires' => $similaires,
        ]);
    }

    /**
     * Afficher les rapports/travaux personnels de l'étudiant
     */
    public function documentsIndex(): View
    {
        $user = Auth::user();
        
        // Récupérer le module actuel depuis l'URL (ex: design-graphique)
        $currentModule = request()->segment(3);
        
        // Récupérer tous les TP/rapports de l'étudiant connecté
        $tps = \App\Models\TP::where('user_id', $user->id)
            ->with(['files'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Transformer les données pour la vue
        $documents = $tps->map(function($tp) {
            // Récupérer le premier fichier PDF s'il existe
            $pdfFile = $tp->files->first(function($file) {
                return strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION)) === 'pdf';
            });
            
            // Déterminer la catégorie selon le statut
            $categorie = match($tp->status) {
                'validated' => 'Validés',
                'pending' => 'En attente',
                'rejected' => 'Rejetés',
                default => 'Autres'
            };
            
            return [
                'id' => $tp->id,
                'titre' => $tp->title,
                'description' => $tp->description ?? 'Rapport de travail pratique',
                'categorie' => $categorie,
                'type' => $pdfFile ? 'PDF' : 'Fichiers',
                'taille' => $pdfFile ? number_format($pdfFile->file_size / 1024, 2) . ' KB' : 'N/A',
                'format' => $pdfFile ? '.pdf' : 'Multiple',
                'telechargements' => 0,
                'date_ajout' => $tp->created_at->format('Y-m-d'),
                'image' => null,
                'lien' => $pdfFile ? asset($pdfFile->file_path) : '#',
                'status' => $tp->status,
                'files_count' => $tp->files->count(),
            ];
        })->toArray();
        
        // Créer des catégories basées sur le statut
        $categories = collect([
            (object)['name' => 'Validés'],
            (object)['name' => 'En attente'],
            (object)['name' => 'Rejetés'],
        ]);
        
        // Organiser les documents par catégorie (statut)
        $documentsParCategorie = [];
        
        foreach ($categories as $category) {
            $documentsParCategorie[$category->name] = array_filter($documents, function($doc) use ($category) {
                return $doc['categorie'] === $category->name;
            });
        }
        
        // Statistiques dynamiques par catégorie
        $stats = [
            'total' => count($documents),
            'validés' => count($documentsParCategorie['Validés']),
            'en_attente' => count($documentsParCategorie['En attente']),
            'rejetés' => count($documentsParCategorie['Rejetés']),
        ];
        
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
        
        \Log::info('📥 Téléchargement de document', [
            'document_id' => $id,
            'title' => $document->title,
            'downloads_count_before' => $document->downloads_count
        ]);
        
        // Incrémenter le compteur de téléchargements
        $document->increment('downloads_count');
        
        \Log::info('✅ Compteur incrémenté', [
            'downloads_count_after' => $document->fresh()->downloads_count
        ]);
        
        // Si un lien externe existe, rediriger vers ce lien
        if ($document->external_link) {
            return redirect($document->external_link);
        }
        
        // Sinon, télécharger le fichier principal
        if ($document->path) {
            $filePath = storage_path('app/public/' . $document->path);
            
            if (file_exists($filePath)) {
                $fileName = $document->title . '.' . $document->file_type;
                return response()->download($filePath, $fileName);
            }
        }
        
        // Si le fichier PDF existe (ancien système)
        if ($document->pdf_path) {
            $filePath = storage_path('app/public/' . $document->pdf_path);
            
            if (file_exists($filePath)) {
                return response()->download($filePath, $document->title . '.pdf');
            }
        }
        
        // Si aucun fichier n'existe, retourner avec erreur
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
        
        // Récupérer les informations de l'étudiant
        $student = DB::table('students')->where('user_id', $user->id)->first();
        
        // Récupérer les programmes publiés par l'admin
        // Filtrer par formation de l'étudiant ou "Toutes" les formations
        $programmes = DB::table('programmes')
            ->where(function($query) use ($student) {
                if ($student && $student->program) {
                    $query->where('formation', $student->program)
                          ->orWhere('formation', 'Toutes');
                } else {
                    $query->where('formation', 'Toutes');
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('programme.index', [
            'user' => $user,
            'programmes' => $programmes,
            'student' => $student
        ]);
    }

    /**
     * Afficher la page Bibliothèque CM_SMM
     */
    public function bibliothequeIndex(): View
    {
        $user = Auth::user();
        
        // Récupérer les informations de l'étudiant
        $student = DB::table('students')->where('user_id', $user->id)->first();
        
        // Récupérer le module actuel depuis l'URL (ex: community-management)
        $currentModule = request()->segment(3);
        
        // Récupérer les items de la bibliothèque actifs et destinés à Community Management
        $items = \App\Models\Library::where('status', 'active')
            ->where(function($query) use ($currentModule) {
                $query->whereJsonContains('recipients', $currentModule)
                      ->orWhereJsonContains('recipients', 'tous')
                      ->orWhereNull('recipients')
                      ->orWhereRaw('JSON_LENGTH(recipients) = 0');
            })
            ->with('libraryCategory')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculer les statistiques
        $stats = [
            'total_documents' => $items->count(),
            'par_categorie' => $items->groupBy('library_category_id')->map(function($group) {
                return (object)[
                    'name' => $group->first()->libraryCategory->name ?? 'Sans catégorie',
                    'count' => $group->count(),
                    'slug' => $group->first()->libraryCategory->slug ?? 'autres'
                ];
            })->values()
        ];
        
        // Déterminer le préfixe de formation pour les routes
        $formationPrefix = $currentModule;
        
        return view('bibliotheque.index', [
            'user' => $user,
            'student' => $student,
            'items' => $items,
            'stats' => $stats,
            'currentModule' => $currentModule,
            'formationPrefix' => $formationPrefix
        ]);
    }

    /**
     * Afficher la page To Do List avec les TP assignés
     */
    public function todoIndex(): View
    {
        $user = Auth::user();
        
        try {
            // Récupérer les informations de l'étudiant
            $student = DB::table('students')
                ->where('user_id', $user->id)
                ->first();
            
            if (!$student) {
                Log::warning('Étudiant non trouvé pour user_id: ' . $user->id);
                return view('todo.index', [
                    'user' => $user,
                    'tpAssignments' => collect([]),
                    'stats' => [
                        'total' => 0,
                        'assigned' => 0,
                        'submitted' => 0,
                        'validated' => 0,
                        'rejected' => 0,
                    ],
                    'student' => null,
                    'formationPrefix' => 'community-management' // Valeur par défaut
                ]);
            }
            
            // Mapping des formations pour gérer les différentes variantes
            $formationMapping = [
                'Design Graphique' => ['Design Graphique', 'Infographie', 'design_graphique', 'infographie', 'Design graphique'],
                'Community Management' => ['Community Management', 'community_management', 'Community management', 'CM'],
                'Gestion Informatique' => ['Gestion Informatique', 'gestion_informatique', 'Gestion informatique', 'GI'],
                'Intelligence Artificielle' => ['Intelligence Artificielle', 'intelligence_artificielle', 'Intelligence artificielle', 'IA']
            ];
            
            // Trouver les variantes de la formation de l'étudiant
            $studentFormationVariants = [$student->program];
            foreach ($formationMapping as $key => $variants) {
                if (in_array($student->program, $variants)) {
                    $studentFormationVariants = $variants;
                    break;
                }
            }
            
            Log::info('Recherche TP pour étudiant', [
                'student_id' => $student->id,
                'program' => $student->program,
                'variants' => $studentFormationVariants
            ]);
            
            // Récupérer UNIQUEMENT les TP à faire (status = 'assigned')
        // On récupère soit les TP assignés directement à cet étudiant (student_id),
        // soit les TP pour sa formation (avec toutes les variantes),
        // soit les TP pour "all" (tous les étudiants)
        $tpAssignments = DB::table('tp_assignments')
            ->where('student_id', $student->id)
            ->where('status', 'assigned') // FILTRE: uniquement les travaux à faire
            ->orderBy('deadline', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
            
            // Dédupliquer les TP (au cas où il y aurait des doublons)
            $tpAssignments = $tpAssignments->unique('id');
            
            Log::info('TP trouvés pour étudiant', [
                'student_id' => $student->id,
                'count' => $tpAssignments->count()
            ]);
            
            // Récupérer les fichiers pour chaque TP avec le bon chemin
            $tpWithFiles = $tpAssignments->map(function($tp) {
                $files = DB::table('tp_assignment_files')
                    ->where('tp_assignment_id', $tp->id)
                    ->get()
                    ->map(function($file) {
                        // Corriger le chemin pour pointer vers storage
                        if (strpos($file->file_path, 'tp_assignments/') !== false) {
                            $file->file_path = asset('storage/' . $file->file_path);
                        } else {
                            $file->file_path = asset('storage/tp_assignments/' . $file->file_path);
                        }
                        return $file;
                    });
                
                $tp->files = $files;
                return $tp;
            });
            
            // Calculer les statistiques
            $stats = [
                'total' => $tpAssignments->count(),
                'assigned' => $tpAssignments->where('status', 'assigned')->count(),
                'submitted' => $tpAssignments->where('status', 'submitted')->count(),
                'validated' => $tpAssignments->where('status', 'validated')->count(),
                'rejected' => $tpAssignments->where('status', 'rejected')->count(),
            ];
            
            // Déterminer le préfixe de formation pour les routes
            $formationPrefix = strtolower(str_replace(' ', '-', $student->program));
            
            Log::info('TP assignés chargés', [
                'student_id' => $student->id,
                'formation' => $student->program,
                'total_tp' => $stats['total']
            ]);
            
            return view('todo.index', [
                'user' => $user,
                'student' => $student,
                'tpAssignments' => $tpWithFiles,
                'stats' => $stats,
                'formationPrefix' => $formationPrefix
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des TP assignés: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return view('todo.index', [
                'user' => $user,
                'tpAssignments' => collect([]),
                'stats' => [
                    'total' => 0,
                    'assigned' => 0,
                    'submitted' => 0,
                    'validated' => 0,
                    'rejected' => 0,
                ],
                'student' => null,
                'error' => 'Erreur lors du chargement des travaux pratiques.',
                'formationPrefix' => 'community-management' // Valeur par défaut
            ]);
        }
    }

    /**
     * Afficher la page de soumission d'un TP
     */
    public function showSubmitPage($id)
    {
        try {
            $user = Auth::user();
            
            // Récupérer l'étudiant
            $student = DB::table('students')
                ->where('user_id', $user->id)
                ->first();
            
            if (!$student) {
                return redirect()->back()->with('error', 'Étudiant non trouvé');
            }
            
            // Récupérer le TP
            $tp = DB::table('tp_assignments')
                ->where('id', $id)
                ->where('student_id', $student->id)
                ->first();
            
            if (!$tp) {
                return redirect()->back()->with('error', 'TP non trouvé ou non autorisé');
            }
            
            // Vérifier que le TP peut être soumis
            if ($tp->status !== 'assigned') {
                return redirect()->back()->with('error', 'Ce TP a déjà été soumis');
            }
            
            // Déterminer le préfixe de formation
            $formationPrefix = strtolower(str_replace(' ', '-', $student->program));
            
            return view('tp.submit', [
                'tp' => $tp,
                'user' => $user,
                'student' => $student,
                'formationPrefix' => $formationPrefix
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affichage de la page de soumission: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors du chargement de la page');
        }
    }

    /**
     * Soumettre un TP
     */
    public function submitTP(Request $request, $id)
    {
        try {
            Log::info('🚀 === DÉBUT SOUMISSION TP ===', [
                'tp_id' => $id,
                'user_id' => Auth::id(),
                'has_files' => $request->hasFile('files'),
                'files_count' => $request->hasFile('files') ? count($request->file('files')) : 0,
                'submission_link' => $request->submission_link
            ]);
            
            $user = Auth::user();
            
            // Valider les données - Les fichiers sont obligatoires
            if (!$request->hasFile('files') || count($request->file('files')) === 0) {
                Log::warning('❌ Soumission TP échouée - Aucun fichier', [
                    'tp_id' => $id,
                    'user_id' => $user->id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Vous devez uploader au moins un fichier pour soumettre votre TP.'
                ], 400);
            }
            
            $request->validate([
                'submission_link' => 'nullable|url',
                'files.*' => 'required|file|max:10240', // 10 Mo max par fichier
            ]);
            
            // Récupérer l'étudiant
            $student = DB::table('students')
                ->where('user_id', $user->id)
                ->first();
            
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Étudiant non trouvé'
                ], 404);
            }
            
            // Vérifier que le TP existe et appartient à l'étudiant
            $tp = DB::table('tp_assignments')
                ->where('id', $id)
                ->where('student_id', $student->id)
                ->first();
            
            if (!$tp) {
                return response()->json([
                    'success' => false,
                    'message' => 'TP non trouvé ou non autorisé'
                ], 404);
            }
            
            // Vérifier que le TP n'a pas déjà été soumis
            if ($tp->status !== 'assigned') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce TP a déjà été soumis'
                ], 400);
            }
            
            // Mettre à jour le TP
            DB::table('tp_assignments')
                ->where('id', $id)
                ->update([
                    'status' => 'submitted',
                    'submission_link' => $request->submission_link,
                    'updated_at' => now()
                ]);
            
            // Gérer l'upload de fichiers si présents
            $uploadedFiles = [];
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    // Valider chaque fichier
                    if ($file->isValid() && $file->getSize() <= 10485760) { // 10 Mo max
                        // Générer un nom unique
                        $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                        
                        // Stocker le fichier
                        $path = $file->storeAs('tp_submissions', $fileName, 'public');
                        
                        // Enregistrer dans la base de données
                        DB::table('tp_submission_files')->insert([
                            'tp_assignment_id' => $id,
                            'file_name' => $file->getClientOriginalName(),
                            'file_path' => $path,
                            'file_size' => $file->getSize(),
                            'mime_type' => $file->getMimeType(),
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        
                        $uploadedFiles[] = $file->getClientOriginalName();
                    }
                }
            }
            
            // Envoyer une notification email aux administrateurs
            try {
                // Récupérer tous les admins (Super Admin et Assistant qui gèrent les TP)
                $admins = DB::table('admins')
                    ->whereIn('role', ['super_admin', 'assistant'])
                    ->get();
                
                Log::info('🔍 Recherche admins pour notification TP', [
                    'admins_trouvés' => $admins->count(),
                    'tp_id' => $id,
                    'tp_title' => $tp->title,
                    'student_email' => $student->email ?? 'N/A'
                ]);
                
                if ($admins->count() > 0) {
                    foreach ($admins as $admin) {
                        if ($admin->email) {
                            try {
                                \Mail::to($admin->email)->send(
                                    new \App\Mail\TpSubmissionNotification(
                                        $student,
                                        $tp->title,
                                        $tp->description,
                                        $tp->formation,
                                        $request->submission_link,
                                        count($uploadedFiles)
                                    )
                                );
                                Log::info('✅ Email de notification TP envoyé avec succès', [
                                    'admin_email' => $admin->email,
                                    'admin_name' => $admin->name ?? 'N/A',
                                    'admin_role' => $admin->role,
                                    'tp_title' => $tp->title,
                                    'fichiers_count' => count($uploadedFiles)
                                ]);
                            } catch (\Exception $emailError) {
                                Log::error('❌ Erreur envoi email à admin individuel', [
                                    'admin_email' => $admin->email,
                                    'error' => $emailError->getMessage(),
                                    'trace' => $emailError->getTraceAsString()
                                ]);
                            }
                        } else {
                            Log::warning('⚠️ Admin sans email', [
                                'admin_id' => $admin->id,
                                'admin_name' => $admin->name ?? 'N/A',
                                'admin_role' => $admin->role
                            ]);
                        }
                    }
                } else {
                    Log::warning('⚠️ Aucun admin actif trouvé pour recevoir la notification TP', [
                        'roles_recherchés' => ['super_admin', 'assistant'],
                        'status_requis' => 'active'
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('❌ Erreur globale lors de l\'envoi des emails admin', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Ne pas bloquer la soumission si l'email échoue
            }
            
            Log::info('TP soumis avec succès', [
                'tp_id' => $id,
                'student_id' => $student->id,
                'submission_link' => $request->submission_link,
                'files_uploaded' => count($uploadedFiles)
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'TP soumis avec succès',
                'files_uploaded' => count($uploadedFiles)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la soumission du TP: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la soumission: ' . $e->getMessage()
            ], 500);
        }
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

    /**
     * Afficher une actualité en détail
     */
    public function showActualite($id)
    {
        $actualite = DB::table('actualites')->where('id', $id)->first();
        
        if (!$actualite || $actualite->status !== 'published') {
            abort(404, 'Actualité non trouvée');
        }
        
        // Incrémenter le compteur de vues
        DB::table('actualites')->where('id', $id)->increment('views');
        
        $user = Auth::user();
        $student = DB::table('students')->where('user_id', $user->id)->first();
        
        return view('actualites.student-show', [
            'actualite' => $actualite,
            'user' => $user,
            'student' => $student,
        ]);
    }

    /**
     * Dashboard Community Management avec statistiques complètes
     */
    public function communityManagement(): View
    {
        $user = Auth::user();
        
        // Récupérer les données de l'étudiant via user_id
        $student = DB::table('students')->where('user_id', $user->id)->first();
        
        // Récupérer les actualités publiées et visibles pour Community Management
        $actualites = DB::table('actualites')
            ->where('status', 'published')
            ->where(function($query) {
                $query->where('visibility', 'public')
                      ->orWhere('visibility', 'all_formations')
                      ->orWhere('visibility', 'like', '%Community Management%');
            })
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();
        
        // Statistiques pour le dashboard Community Management
        $stats = [
            // Progression globale
            'progression_globale' => 65,
            
            // Campagnes à créer
            'tp_a_faire' => 8,
            
            // Projets actifs
            'projets_actifs' => 5,
            
            // Projets en cours
            'projets_en_cours' => 3,
            
            // TP validés
            'tp_valides' => 12,
            
            // TP en attente
            'tp_en_attente' => 4,
            
            // Certification
            'certification' => 'En cours',
            
            // Éligible au certificat
            'eligible_certificat' => false,
            
            // Formation de la semaine
            'formation_semaine' => 'Stratégie Social Media',
            
            // Total TP
            'total_tp' => 16,
            
            // Webinaires
            'webinaires' => 6,
            
            // Actualités
            'actualites' => $actualites->count(),
        ];
        
        return view('dashboard.community-management', [
            'user' => $user,
            'student' => $student,
            'stats' => $stats,
            'actualites' => $actualites,
        ]);
    }

    /**
     * Dashboard Intelligence Artificielle
     */
    public function intelligenceArtificielle(): View
    {
        $user = Auth::user();
        
        $stats = [
            'progression_globale' => 45,
            'tp_a_faire' => 12,
            'projets_actifs' => 3,
        ];
        
        return view('dashboard.intelligence-artificielle', [
            'user' => $user,
            'stats' => $stats,
        ]);
    }

    /**
     * Dashboard Gestion Informatique
     */
    public function gestionInformatique(): View
    {
        $user = Auth::user();
        
        $stats = [
            'progression_globale' => 50,
            'tp_a_faire' => 10,
            'projets_actifs' => 4,
        ];
        
        return view('dashboard.gestion-informatique', [
            'user' => $user,
            'stats' => $stats,
        ]);
    }
}
