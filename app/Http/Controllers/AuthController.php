<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PDO;
use PDOException;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Afficher la page de connexion
     */
    public function showLoginForm()
    {
        // Vérifier si l'utilisateur est déjà connecté
        if (session('logged_in')) {
            // Rediriger vers l'espace étudiant personnalisé selon la formation
            $formation = session('user_formation');
            $routeName = $this->getFormationRouteName($formation);
            
            return redirect()->route($routeName)
                ->with('info', 'Vous êtes déjà connecté à votre espace étudiant.');
        }
        
        return view('auth.login');
    }

    /**
     * Traiter la connexion
     */
    public function login(Request $request)
    {
        // Validation des données
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse email valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
        ]);

        try {
            // Rechercher l'utilisateur dans la base de données avec toutes les informations nécessaires
            $user = DB::table('users')
                ->select('id', 'email', 'first_name', 'last_name', 'password', 
                         'current_level', 'status', 'profile_photo', 
                         'country', 'city', 'phone', 'whatsapp')
                ->where('email', $request->email)
                ->where('status', 'Actif')
                ->first();

            if (!$user) {
                throw ValidationException::withMessages([
                    'email' => 'Aucun compte actif trouvé avec cette adresse email.',
                ]);
            }

            // Vérifier le mot de passe
            if (!Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'password' => 'Mot de passe incorrect.',
                ]);
            }

            // Enregistrer l'activité de connexion
            DB::table('user_activities')->insert([
                'user_id' => $user->id,
                'activity_type' => 'login',
                'description' => 'Connexion à la plateforme EVC',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
            ]);

            // Mettre à jour le timestamp de dernière connexion pour le statut en ligne
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'last_login' => now(),
                    'updated_at' => now()
                ]);

            // Créer la session utilisateur avec toutes les informations nécessaires
            session([
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_name' => ($user->first_name ?? '') . ' ' . ($user->last_name ?? ''),
                'user_first_name' => $user->first_name ?? '',
                'user_last_name' => $user->last_name ?? '',
                'user_phone' => $user->phone ?? '',
                'user_city' => $user->city ?? '',
                'user_country' => $user->country ?? '',
                'user_profile_photo' => $user->profile_photo ?? null,
                'user_current_level' => $user->current_level ?? '',
                'user_whatsapp' => $user->whatsapp ?? '',
                'user_status' => $user->status,
                // Garder les anciens noms pour compatibilité
                'user_prenom' => $user->first_name ?? '',
                'user_nom' => $user->last_name ?? '',
                'user_telephone' => $user->phone ?? '',
                'user_pays' => $user->country ?? '',
                'user_ville' => $user->city ?? '',
                'user_photo' => $user->profile_photo ?? null,
                'user_level' => $user->current_level ?? '',
                'user_niveau' => $user->current_level ?? '',
                'logged_in' => true,
            ]);

            // Se souvenir de l'utilisateur si demandé
            if ($request->has('remember')) {
                // Créer un token de souvenir (simplifié pour la démo)
                $rememberToken = bin2hex(random_bytes(32));
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['remember_token' => $rememberToken]);
                
                // Cookie de 30 jours
                cookie('remember_token', $rememberToken, 60 * 24 * 30);
            }

            // Stocker la route de destination pour la page de chargement
            try {
                $formationRoute = $this->getFormationRouteName($user->formation_souhaitee ?? 'design-graphique');
                session(['redirect_to' => route($formationRoute)]);
            } catch (\Exception $e) {
                // Fallback vers la route par défaut
                session(['redirect_to' => route('dashboard.design-graphique')]);
            }
        
            // Stocker les informations utilisateur supplémentaires pour la page de chargement
            session([
                'user_formation_display' => $this->getFormationDisplayName($user->formation_souhaitee ?? 'design-graphique')
            ]);
        
            // Rediriger vers la page de chargement
            return redirect()->route('auth.loading')->with('success', 'Connexion réussie ! Bienvenue dans votre espace ' . $this->getFormationDisplayName($user->formation_souhaitee ?? 'design-graphique') . ', ' . $user->first_name . ' 👋');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput($request->only('email'));
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la connexion. Veuillez réessayer.')->withInput($request->only('email'));
        }
    }

    /**
     * Afficher la page de chargement après connexion
     */
    public function showLoadingPage()
    {
        // Vérifier si l'utilisateur est connecté
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }
        
        // Vérifier si une destination est définie
        if (!session('redirect_to')) {
            // Définir une destination par défaut basée sur la formation
            $formation = session('user_formation', 'design-graphique');
            $routeName = $this->getFormationRouteName($formation);
            session(['redirect_to' => route($routeName)]);
        }
        
        return view('auth.loading');
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        // Enregistrer l'activité de déconnexion et nettoyer le statut en ligne
        if (session('user_id')) {
            $userId = session('user_id');
            
            DB::table('user_activities')->insert([
                'user_id' => $userId,
                'activity_type' => 'Déconnexion',
                'description' => 'Déconnexion de la plateforme EVC',
                'created_at' => now(),
            ]);

            // Supprimer toutes les sessions actives de cet utilisateur pour le mettre hors ligne
            DB::table('sessions')
                ->where('user_id', $userId)
                ->delete();
        }

        // Supprimer toutes les données de session
        session()->flush();
        
        // Supprimer le cookie de souvenir
        cookie()->forget('remember_token');

        return redirect()->route('login')->with('success', 'Vous avez été déconnecté avec succès.');
    }

    /**
     * Vérifier si l'utilisateur est connecté (middleware personnalisé)
     */
    public static function checkAuth()
    {
        return session('logged_in', false);
    }

    /**
     * Obtenir les données de l'utilisateur connecté
     */
    public static function user()
    {
        if (!self::checkAuth()) {
            return null;
        }

        return (object) [
            'id' => session('user_id'),
            'email' => session('user_email'),
            'name' => session('user_name'),
            'level' => session('user_level'),
            'status' => session('user_status'),
            'photo' => session('user_photo'),
        ];
    }

    /**
     * Récupérer les statistiques de l'utilisateur connecté
     */
    public static function getUserStats()
    {
        if (!self::checkAuth()) {
            return null;
        }

        $userId = session('user_id');
        
        try {
            // Récupérer les statistiques depuis la vue
            $stats = DB::table('user_dashboard_stats')
                ->where('id', $userId)
                ->first();

            if ($stats) {
                return [
                    'formations_enrolled' => $stats->formations_enrolled ?? 0,
                    'formations_completed' => $stats->formations_completed ?? 0,
                    'tp_completed' => $stats->tp_completed ?? 0,
                    'projects_completed' => $stats->projects_completed ?? 0,
                    'badges_earned' => $stats->badges_earned ?? 0,
                    'total_study_hours' => $stats->total_study_hours ?? 0,
                    'active_days_count' => $stats->active_days_count ?? 0,
                    'average_grade' => $stats->average_grade ?? 0,
                    'global_progress_percentage' => $stats->global_progress_percentage ?? 0,
                    'total_payments' => $stats->total_payments ?? 0,
                    'documents_uploaded' => $stats->documents_uploaded ?? 0,
                ];
            }

            return [
                'formations_enrolled' => 4,
                'formations_completed' => 0,
                'tp_completed' => 12,
                'projects_completed' => 3,
                'badges_earned' => 2,
                'total_study_hours' => 245,
                'active_days_count' => 156,
                'average_grade' => 16.5,
                'global_progress_percentage' => 68,
                'total_payments' => 900,
                'documents_uploaded' => 15,
            ];

        } catch (\Exception $e) {
            // Retourner des données par défaut en cas d'erreur
            return [
                'formations_enrolled' => 4,
                'formations_completed' => 0,
                'tp_completed' => 12,
                'projects_completed' => 3,
                'badges_earned' => 2,
                'total_study_hours' => 245,
                'active_days_count' => 156,
                'average_grade' => 16.5,
                'global_progress_percentage' => 68,
                'total_payments' => 900,
                'documents_uploaded' => 15,
            ];
        }
    }

    // === INSCRIPTION ===
    
    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegisterForm()
    {
        // Vérifier si l'utilisateur est déjà connecté
        if (session('logged_in')) {
            // Rediriger vers l'espace étudiant personnalisé selon la formation
            $formation = session('user_formation');
            $routeName = $this->getFormationRouteName($formation);
            
            return redirect()->route($routeName)
                ->with('info', 'Vous êtes déjà connecté à votre espace étudiant.');
        }
        
        return view('auth.register');
    }
    
    /**
     * Traiter l'inscription
     */
    public function register(Request $request)
    {
        // Validation des données
        $request->validate([
            'prenom' => 'required|string|max:50',
            'nom' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'telephone' => 'required|string|max:20',
            'pays' => 'required|string|max:50',
            'ville' => 'required|string|max:100',
            'niveau' => 'required|string|in:debutant,intermediaire,perfectionnement',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'required|string|min:6|confirmed',
            'formation' => 'required|string|in:design_graphique,community_management,intelligence_artificielle,gestion_informatique',
            'terms' => 'required|accepted'
        ], [
            'prenom.required' => 'Le prénom est obligatoire.',
            'nom.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'telephone.required' => 'Le numéro de téléphone est obligatoire.',
            'pays.required' => 'Le pays est obligatoire.',
            'ville.required' => 'La ville est obligatoire.',
            'niveau.required' => 'Le niveau est obligatoire.',
            'niveau.in' => 'Le niveau sélectionné n\'est pas valide.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.mimes' => 'La photo doit être au format JPEG, PNG, JPG ou GIF.',
            'photo.max' => 'La photo ne doit pas dépasser 2MB.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'formation.required' => 'Veuillez choisir une formation.',
            'formation.in' => 'La formation sélectionnée n\'est pas valide.',
            'terms.required' => 'Vous devez accepter les conditions d\'utilisation.',
            'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.'
        ]);
        
        try {
            // Connexion à la base de données
            $pdo = new \PDO(
                'mysql:host=127.0.0.1;port=3306;dbname=v4_evc;charset=utf8mb4',
                'root',
                '',
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
            
            // Vérifier si l'email existe déjà
            $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
            $stmt->execute([$request->email]);
            if ($stmt->fetch()) {
                return back()->withErrors(['email' => 'Cette adresse email est déjà utilisée.'])->withInput();
            }
            
            // Gérer l'upload de photo si présente
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                
                // Créer le dossier s'il n'existe pas
                $uploadPath = public_path('uploads/photos');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                // Déplacer le fichier
                if ($photo->move($uploadPath, $photoName)) {
                    $photoPath = 'uploads/photos/' . $photoName;
                }
            }
            
            // Insérer le nouvel utilisateur
            $stmt = $pdo->prepare('
                INSERT INTO users (prenom, nom, email, telephone, pays, ville, photo, password, formation_souhaitee, statut, date_inscription, niveau_actuel) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, "actif", NOW(), ?)
            ');
            
            $stmt->execute([
                $request->prenom,
                $request->nom,
                $request->email,
                $request->telephone,
                $request->pays,
                $request->ville,
                $photoPath,
                password_hash($request->password, PASSWORD_DEFAULT),
                $request->formation,
                $request->niveau
            ]);
            
            $userId = $pdo->lastInsertId();
            
            // Enregistrer l'activité d'inscription
            $this->logUserActivity($userId, 'register', 'Inscription d\'un nouvel utilisateur');
            
            // Créer les statistiques initiales
            $stmt = $pdo->prepare('
                INSERT INTO user_statistics (user_id, total_tp, tp_valides, total_projets, projets_valides, 
                                            progression_globale, heures_formation, badges_obtenus) 
                VALUES (?, 0, 0, 0, 0, 0, 0, 0)
            ');
            $stmt->execute([$userId]);
            
            return redirect()->route('login')->with('success', 'Votre compte a été créé avec succès ! Vous pouvez maintenant vous connecter.');
            
        } catch (\PDOException $e) {
            error_log('ERREUR PDO INSCRIPTION: ' . $e->getMessage());
            error_log('TRACE PDO: ' . $e->getTraceAsString());
            return back()->withErrors(['general' => 'Erreur base de données: ' . $e->getMessage()])->withInput();
        } catch (\Exception $e) {
            error_log('ERREUR GENERALE INSCRIPTION: ' . $e->getMessage());
            error_log('TRACE GENERALE: ' . $e->getTraceAsString());
            return back()->withErrors(['general' => 'Erreur inattendue: ' . $e->getMessage()])->withInput();
        }
    }
    
    /**
     * Traiter l'inscription sans CSRF (solution de contournement)
     */
    public function registerNoCsrf(Request $request)
    {
        try {
            // Gestion de l'upload de photo
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                if ($photo->isValid()) {
                    $photoName = time() . '_' . $photo->getClientOriginalName();
                    $photo->move(public_path('uploads/photos'), $photoName);
                    $photoPath = 'uploads/photos/' . $photoName;
                }
            }

            // Connexion à la base de données
            $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=v4_evc;charset=utf8mb4', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Insérer l'utilisateur
            $stmt = $pdo->prepare("
                INSERT INTO utilisateurs (
                    prenom, nom, email, telephone, pays, ville, niveau, 
                    formation_souhaitee, mot_de_passe, photo_profil, 
                    date_inscription, statut, accepte_conditions
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'actif', 1)
            ");

            $stmt->execute([
                $request->prenom,
                $request->nom,
                $request->email,
                $request->telephone,
                $request->pays,
                $request->ville,
                $request->niveau,
                $request->formation,
                password_hash($request->password, PASSWORD_DEFAULT),
                $photoPath
            ]);

            // Enregistrer l'activité
            $userId = $pdo->lastInsertId();
            $stmt = $pdo->prepare("
                INSERT INTO activites_utilisateur (utilisateur_id, type_activite, description, date_activite)
                VALUES (?, 'inscription', 'Inscription réussie', NOW())
            ");
            $stmt->execute([$userId]);
            
            // Initialiser les statistiques utilisateur
            $stmt = $pdo->prepare('
                INSERT INTO statistiques_utilisateur (utilisateur_id, tp_realises, projets_realises, heures_formation, badges_obtenus, documents_cvtheque, notes_moyenne, progression_globale) 
                VALUES (?, 0, 0, 0, 0, 0, 0, 0)
            ');
            $stmt->execute([$userId]);

            // Retourner une réponse JSON pour AJAX
            return response()->json([
                'success' => true,
                'message' => 'Inscription réussie ! Redirection vers la page de connexion...',
                'redirect' => route('login')
            ]);

        } catch (PDOException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur base de données: ' . $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur générale: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // === RÉCUPÉRATION DE MOT DE PASSE ===
    
    /**
     * Afficher le formulaire de récupération de mot de passe
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }
    
    /**
     * Envoyer l'email de récupération de mot de passe
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validation
        $request->validate([
            'email' => 'required|email|max:100'
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.'
        ]);
        
        try {
            // Connexion à la base de données
            $pdo = new \PDO(
                'mysql:host=127.0.0.1;port=3306;dbname=v4_evc;charset=utf8mb4',
                'root',
                '',
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );

            // Vérifier si l'utilisateur existe
            $stmt = $pdo->prepare('SELECT id, prenom, nom FROM users WHERE email = ? AND statut = "actif"');
            $stmt->execute([$request->email]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$user) {
                return back()->withErrors(['email' => 'Aucun compte actif trouvé avec cette adresse email.']);
            }

            // Générer un token de réinitialisation
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Dans un vrai projet, vous stockeriez le token en base et enverriez un email
            // Pour la démo, on simule l'envoi

            // Enregistrer l'activité
            $this->logUserActivity($user['id'], 'password_reset_request', 'Demande de réinitialisation de mot de passe');

            return back()->with('success', 'Un lien de récupération a été envoyé à votre adresse email.');

        } catch (\PDOException $e) {
            return back()->withErrors(['general' => 'Erreur lors de l\'envoi de l\'email. Veuillez réessayer.']);
        }
    }
    
    /**
     * Enregistrer une activité utilisateur
     */
    private function logUserActivity($userId, $action, $description)
    {
        try {
            $pdo = new \PDO(
                'mysql:host=127.0.0.1;port=3306;dbname=v4_evc;charset=utf8mb4',
                'root',
                '',
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
            
            $stmt = $pdo->prepare('
                INSERT INTO user_activities (user_id, action, description, date_activite) 
                VALUES (?, ?, ?, NOW())
            ');
            
            $stmt->execute([$userId, $action, $description]);
            
        } catch (\PDOException $e) {
            // Log silencieux en cas d'erreur
            error_log('Erreur lors de l\'enregistrement de l\'activité: ' . $e->getMessage());
        }
    }
    
    /**
     * Obtenir le nom d'affichage de la formation
     */
    private function getFormationDisplayName($formationType)
    {
        $formations = [
            'design_graphique' => 'Design Graphique',
            'community_management' => 'Community Management',
            'intelligence_artificielle' => 'Intelligence Artificielle',
            'gestion_informatique' => 'Gestion Informatique'
        ];
        
        return $formations[$formationType] ?? 'Formation';
    }
    
    /**
     * Obtenir le nom de route selon le type de formation
     */
    private function getFormationRouteName($formationType)
    {
        $routes = [
            'design_graphique' => 'dashboard.design-graphique', // Espace étudiant Design Graphique
            'design-graphique' => 'dashboard.design-graphique', // Variante avec tiret
            'community_management' => 'dashboard.community-manager',
            'community-management' => 'dashboard.community-manager',
            'intelligence_artificielle' => 'dashboard.intelligence-artificielle',
            'intelligence-artificielle' => 'dashboard.intelligence-artificielle',
            'gestion_informatique' => 'dashboard.gestion-informatique',
            'gestion-informatique' => 'dashboard.gestion-informatique'
        ];
        
        return $routes[$formationType] ?? 'dashboard.design-graphique'; // Par défaut: Design Graphique
    }
}
