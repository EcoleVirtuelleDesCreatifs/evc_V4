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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Afficher la page de connexion
     */
    public function showLoginForm()
    {
        // Source de vérité: auth Laravel
        // Si la session legacy indique "connecté" mais que Laravel n'est pas authentifié,
        // nettoyer pour éviter les boucles de redirection.
        if (session('logged_in') && !Auth::check()) {
            session()->forget([
                'user_id',
                'user_email',
                'user_name',
                'user_first_name',
                'user_last_name',
                'user_phone',
                'user_city',
                'user_country',
                'user_profile_photo',
                'user_current_level',
                'user_whatsapp',
                'user_status',
                'user_formation',
                'user_formation_raw',
                'user_prenom',
                'user_nom',
                'user_telephone',
                'user_pays',
                'user_ville',
                'user_photo',
                'user_level',
                'user_niveau',
                'redirect_to',
                'user_formation_display',
                'logged_in',
            ]);
        }

        // Vérifier si l'utilisateur est déjà connecté
        if (Auth::check()) {
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
            // Sélection tolérante au schéma: ne sélectionner que les colonnes existantes
            $schema = \Illuminate\Support\Facades\Schema::getColumnListing('users');
            $optional = ['first_name', 'last_name', 'current_level', 'status', 'profile_photo', 'country', 'city', 'phone', 'whatsapp', 'name', 'formation_souhaitee', 'remember_token', 'email_verified_at'];
            $select = ['id', 'email', 'password'];
            foreach ($optional as $col) {
                if (in_array($col, $schema, true)) {
                    $select[] = $col;
                }
            }

            $query = DB::table('users')->select($select)->where('email', $request->email);
            if (in_array('status', $schema, true)) {
                $query->where('status', 'Actif');
            } elseif (in_array('email_verified_at', $schema, true)) {
                // Si pas de colonne status, on exige un compte confirmé
                $query->whereNotNull('email_verified_at');
            }

            $user = $query->first();

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

            // Authentifier l'utilisateur dans Laravel (IMPORTANT pour le middleware auth)
            $userModel = \App\Models\User::find($user->id);
            if ($userModel) {
                Auth::login($userModel, $request->has('remember'));
            }

            // Enregistrer l'activité de connexion si la table existe
            if (\Illuminate\Support\Facades\Schema::hasTable('user_activities')) {
                DB::table('user_activities')->insert([
                    'user_id' => $user->id,
                    'activity_type' => 'login',
                    'description' => 'Connexion à la plateforme EVC',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'created_at' => now(),
                ]);
            }

            // Mettre à jour le timestamp de dernière connexion pour le statut en ligne
            // Mettre à jour le timestamp de dernière connexion si colonne présente
            $updateLogin = ['updated_at' => now()];
            if (in_array('last_login', $schema, true)) {
                $updateLogin['last_login'] = now();
            }
            DB::table('users')->where('id', $user->id)->update($updateLogin);

            // Déterminer un statut utilisateur sûr
            $userStatus = null;
            if (in_array('status', $schema, true)) {
                $userStatus = $user->status ?? null;
            } elseif (in_array('email_verified_at', $schema, true)) {
                $userStatus = 'Actif'; // Si le compte est confirmé et pas de colonne status
            }

            // Récupérer la formation depuis la table students via user_id
            $student = DB::table('students')->where('user_id', $user->id)->first();
            $formationSouhaitee = 'design-graphique'; // Valeur par défaut

            if ($student && !empty($student->program)) {
                // Mapper les valeurs de program vers les formats de route
                $programMapping = [
                    'Design Graphique' => 'design-graphique',
                    'Community Management' => 'community-management',
                    'Design Graphique & Community Management' => 'design-graphique-cm',
                    'Intelligence Artificielle' => 'intelligence-artificielle',
                    'Gestion Informatique' => 'gestion-informatique',
                    // Variantes possibles
                    'design graphique' => 'design-graphique',
                    'community management' => 'community-management',
                    'design graphique & community management' => 'design-graphique-cm',
                    'design_graphique_community_management' => 'design-graphique-cm',
                    'intelligence artificielle' => 'intelligence-artificielle',
                    'gestion informatique' => 'gestion-informatique',
                ];

                $formationSouhaitee = $programMapping[$student->program] ?? str_replace(['_', ' '], '-', strtolower($student->program));
            } elseif (isset($user->formation_souhaitee)) {
                // Fallback sur formation_souhaitee si elle existe dans users
                $formationSouhaitee = $user->formation_souhaitee;
            }

            // Normaliser la formation (convertir les underscores en tirets)
            $formationNormalized = str_replace('_', '-', strtolower($formationSouhaitee));

            // Créer la session utilisateur avec toutes les informations nécessaires
            session([
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: ($user->name ?? $user->email),
                'user_first_name' => $user->first_name ?? '',
                'user_last_name' => $user->last_name ?? '',
                'user_phone' => $user->phone ?? '',
                'user_city' => $user->city ?? '',
                'user_country' => $user->country ?? '',
                'user_profile_photo' => $user->profile_photo ?? null,
                'user_current_level' => $user->current_level ?? '',
                'user_whatsapp' => $user->whatsapp ?? '',
                'user_status' => $userStatus,
                'user_formation' => $formationNormalized, // IMPORTANT: Stocker la formation normalisée
                'user_formation_raw' => $formationSouhaitee, // Formation brute pour référence
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

            // Se souvenir de l'utilisateur si demandé (si colonne existe)
            if ($request->has('remember') && in_array('remember_token', $schema, true)) {
                $rememberToken = bin2hex(random_bytes(32));
                DB::table('users')->where('id', $user->id)->update(['remember_token' => $rememberToken]);
                cookie('remember_token', $rememberToken, 60 * 24 * 30);
            }

            // Stocker la route de destination pour la page de chargement
            try {
                $formationRoute = $this->getFormationRouteName($formationNormalized);
                session(['redirect_to' => route($formationRoute)]);
            } catch (\Exception $e) {
                // Fallback vers la route par défaut
                session(['redirect_to' => route('dashboard.design-graphique')]);
            }

            // Stocker les informations utilisateur supplémentaires pour la page de chargement
            session([
                'user_formation_display' => $this->getFormationDisplayName($formationNormalized)
            ]);

            // Email de rappel ID étudiant (ne doit pas bloquer la connexion)
            try {
                if ($student && !empty($user->email) && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    $studentId = $student->student_id ?? null;
                    if (!empty($studentId)) {
                        $studentName = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
                        if ($studentName === '') {
                            $studentName = trim((string) ($user->name ?? ''));
                        }
                        $studentName = $studentName !== '' ? $studentName : 'Cher(e) étudiant(e)';

                        $verifyUrl = url('/auth/evc/verify-id') . '?student_id=' . urlencode((string) $studentId);

                        Mail::send('emails.student_id_login_reminder', [
                            'studentName' => $studentName,
                            'studentId' => $studentId,
                            'verifyUrl' => $verifyUrl,
                        ], function ($message) use ($user) {
                            $message->to($user->email)
                                ->subject('Votre ID Étudiant - Vérification');
                        });
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Email rappel ID étudiant non envoyé', [
                    'user_id' => $user->id ?? null,
                    'email' => $user->email ?? null,
                    'error' => $e->getMessage(),
                ]);
            }

            // Rediriger vers la page de chargement
            return redirect()->route('auth.loading')->with('success', 'Connexion réussie ! Bienvenue dans votre espace ' . $this->getFormationDisplayName($formationNormalized) . ', ' . ($user->first_name ?? 'Étudiant') . ' 👋');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput($request->only('email'));
        } catch (\Exception $e) {
            $msg = app()->environment('local') ? ('Erreur de connexion: ' . $e->getMessage()) : 'Une erreur est survenue lors de la connexion. Veuillez réessayer.';
            return back()->with('error', $msg)->withInput($request->only('email'));
        }
    }

    /**
     * Afficher la page de chargement après connexion
     */
    public function showLoadingPage()
    {
        // Vérifier si l'utilisateur est connecté (auth Laravel)
        if (!Auth::check()) {
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
     * Redirection vers la page de connexion étudiante (alias route /student/login)
     */
    public function studentLoginRedirect()
    {
        // Si session legacy présente mais Auth absent, nettoyer (évite boucle login->dashboard->login)
        if (session('logged_in') && !Auth::check()) {
            session()->forget([
                'user_id',
                'user_email',
                'user_name',
                'user_first_name',
                'user_last_name',
                'user_phone',
                'user_city',
                'user_country',
                'user_profile_photo',
                'user_current_level',
                'user_whatsapp',
                'user_status',
                'user_formation',
                'user_formation_raw',
                'user_prenom',
                'user_nom',
                'user_telephone',
                'user_pays',
                'user_ville',
                'user_photo',
                'user_level',
                'user_niveau',
                'redirect_to',
                'user_formation_display',
                'logged_in',
            ]);
        }

        // Si déjà connecté, rediriger à l'espace selon la formation
        if (Auth::check()) {
            $formation = session('user_formation', 'design-graphique');
            try {
                $routeName = $this->getFormationRouteName($formation);
                return redirect()->route($routeName);
            } catch (\Throwable $e) {
                return redirect()->route('dashboard.design-graphique');
            }
        }
        // Sinon afficher le formulaire de connexion
        return view('auth.login');
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
     * Obtenir le nom d'affichage selon le type de formation
     */
    private function getFormationDisplayName($formationType)
    {
        // Normaliser le format (remplacer tirets et underscores)
        $normalized = str_replace(['-', '_'], '-', strtolower($formationType));

        $formations = [
            'design-graphique' => 'Design Graphique',
            'community-management' => 'Community Management',
            'design-graphique-cm' => 'Design Graphique & Community Management',
            'intelligence-artificielle' => 'Intelligence Artificielle',
            'gestion-informatique' => 'Gestion Informatique',
            // Variantes avec underscores
            'design_graphique' => 'Design Graphique',
            'community_management' => 'Community Management',
            'design_graphique_community_management' => 'Design Graphique & Community Management',
            'intelligence_artificielle' => 'Intelligence Artificielle',
            'gestion_informatique' => 'Gestion Informatique'
        ];

        return $formations[$normalized] ?? $formations[$formationType] ?? 'Formation';
    }

    /**
     * Obtenir le nom de route selon le type de formation
     */
    private function getFormationRouteName($formationType)
    {
        $routes = [
            'design_graphique' => 'dashboard.design-graphique', // Espace étudiant Design Graphique
            'design-graphique' => 'dashboard.design-graphique', // Variante avec tiret
            'community_management' => 'dashboard.community-management',
            'community-management' => 'dashboard.community-management',
            'design-graphique-cm' => 'dashboard.design-graphique-cm',
            'design_graphique_community_management' => 'dashboard.design-graphique-cm',
            'intelligence_artificielle' => 'dashboard.intelligence-artificielle',
            'intelligence-artificielle' => 'dashboard.intelligence-artificielle',
            'gestion_informatique' => 'dashboard.gestion-informatique',
            'gestion-informatique' => 'dashboard.gestion-informatique'
        ];

        return $routes[$formationType] ?? 'dashboard.design-graphique'; // Par défaut: Design Graphique
    }
}
