<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * DashboardController - 100% Laravel
 * 
 * Contrôleur principal pour la gestion des espaces étudiants
 * Utilise exclusivement Laravel DB facade et les bonnes pratiques
 * Statuts TP: 'pending', 'validate', 'rejected'
 */
class DashboardController extends Controller
{
    // Configuration
    private const TOTAL_TP_REQUIRED = 20;
    private const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB
    private const ALLOWED_EXTENSIONS = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'doc', 'docx'];

    /**
     * Afficher le dashboard principal selon le type de formation
     */
    public function index(): RedirectResponse
    {
        if (!$this->isAuthenticated()) {
            return $this->redirectToLogin('Vous devez être connecté pour accéder à cette page.');
        }

        $formation = session('user_formation', 'design-graphique');
        $routeMap = [
            'design-graphique' => 'dashboard.design-graphique',
            'design_graphique' => 'dashboard.design-graphique',
            'community-management' => 'dashboard.community-manager',
            'community_management' => 'dashboard.community-manager',
            'intelligence-artificielle' => 'dashboard.intelligence-artificielle',
            'intelligence_artificielle' => 'dashboard.intelligence-artificielle',
            'gestion-informatique' => 'dashboard.gestion-informatique',
            'gestion_informatique' => 'dashboard.gestion-informatique'
        ];

        $route = $routeMap[$formation] ?? 'dashboard.design-graphique';
        return redirect()->route($route);
    }

    /**
     * Afficher l'espace étudiant Design Graphique
     */
    public function designGraphique(): View|RedirectResponse
    {
        if (!$this->isAuthenticated()) {
            return $this->redirectToLogin('Vous devez être connecté pour accéder à cette page.');
        }

        try {
            $userId = (int) session('user_id');
            $dashboardData = $this->getDashboardData($userId);
            
            return view('dashboard.design-graphique', $dashboardData);
        } catch (\Exception $e) {
            Log::error('Erreur dashboard Design Graphique: ' . $e->getMessage());
            return view('dashboard.design-graphique', $this->getFallbackData());
        }
    }

    /**
     * Afficher le formulaire d'édition du profil
     */
    public function editProfile(): View|RedirectResponse
    {
        if (!$this->isAuthenticated()) {
            return $this->redirectToLogin('Vous devez être connecté pour accéder à cette page.');
        }

        try {
            $userId = (int) session('user_id');
            $user = $this->getUserProfile($userId);
            $validationStats = $this->getValidationStats($userId);

            return view('profile.edit', compact('user', 'validationStats'));
        } catch (\Exception $e) {
            Log::error('Erreur édition profil: ' . $e->getMessage());
            return $this->redirectToLogin('Erreur lors du chargement du profil.');
        }
    }

    /**
     * Mettre à jour le profil utilisateur
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        if (!$this->isAuthenticated()) {
            return $this->redirectToLogin('Vous devez être connecté pour effectuer cette action.');
        }

        try {
            $validatedData = $request->validate([
                'last_name' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'country' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'district' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'whatsapp' => 'nullable|string|max:20',
                'education_level' => 'nullable|string|max:255',
                'last_diploma' => 'nullable|string|max:255',
                'age' => 'nullable|integer|min:16|max:100',
                'biography' => 'nullable|string|max:2000',
                'expectations' => 'nullable|string|max:2000',
                'current_level' => 'nullable|in:debutant,intermediaire,perfectionnement',
                'password' => 'nullable|string|min:6|confirmed',
                'profile_photo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048'
            ]);

            $userId = session('user_id');
            $updateData = [
                'last_name' => $validatedData['last_name'],
                'first_name' => $validatedData['first_name'],
                'email' => $validatedData['email'],
                'country' => $validatedData['country'],
                'city' => $validatedData['city'],
                'district' => $validatedData['district'],
                'phone' => $validatedData['phone'],
                'whatsapp' => $validatedData['whatsapp'],
                'education_level' => $validatedData['education_level'],
                'last_diploma' => $validatedData['last_diploma'],
                'age' => $validatedData['age'],
                'biography' => $validatedData['biography'],
                'expectations' => $validatedData['expectations'],
                'current_level' => $validatedData['current_level'],
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Gestion de l'upload de photo
            if ($request->hasFile('profile_photo')) {
                $photo = $request->file('profile_photo');
                if ($photo->isValid()) {
                    $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    $photo->move(public_path('uploads/profiles'), $photoName);
                    $updateData['profile_photo'] = 'uploads/profiles/' . $photoName;
                }
            }

            // Mise à jour du mot de passe si fourni
            if (!empty($validatedData['password'])) {
                $updateData['password'] = password_hash($validatedData['password'], PASSWORD_DEFAULT);
            }

            // Mise à jour en base de données
            DB::table('users')
                ->where('id', $userId)
                ->update($updateData);

            // Mettre à jour les données de session
            session([
                'user_first_name' => $validatedData['first_name'],
                'user_last_name' => $validatedData['last_name'],
                'user_name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
                'user_email' => $validatedData['email'],
                'user_phone' => $validatedData['phone'],
                'user_city' => $validatedData['city'],
                'user_country' => $validatedData['country'],
                'user_current_level' => $validatedData['current_level'],
                'user_whatsapp' => $validatedData['whatsapp'],
                // Compatibilité anciens noms
                'user_prenom' => $validatedData['first_name'],
                'user_nom' => $validatedData['last_name'],
                'user_telephone' => $validatedData['phone'],
                'user_ville' => $validatedData['city'],
                'user_pays' => $validatedData['country'],
                'user_niveau' => $validatedData['current_level'],
            ]);

            return redirect()->route('design-graphique.profil.editer')
                ->with('success', 'Profil mis à jour avec succès!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du profil: ' . $e->getMessage());
        }
    }

    /**
     * Afficher le formulaire de création d'un TP
     */
    public function createTP()
    {
        // Vérifier que l'utilisateur est connecté
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        return view('tp.create');
    }

    /**
     * Enregistrer un nouveau TP - VERSION CORRIGÉE AVEC LARAVEL DB
     */
    public function storeTP(Request $request)
    {
        // Vérifier que l'utilisateur est connecté
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour effectuer cette action.');
        }

        try {
            // Validation des données
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:2000',
                'link' => 'nullable|url|max:500',
                'files' => 'required|array|min:1', // Au moins un fichier requis
                'files.*' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,gif,zip,rar,txt,ppt,pptx,xls,xlsx'
            ], [
                'title.required' => 'Le titre du TP est obligatoire.',
                'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
                'description.max' => 'La description ne peut pas dépasser 2000 caractères.',
                'link.url' => 'Le lien doit être une URL valide.',
                'link.max' => 'Le lien ne peut pas dépasser 500 caractères.',
                'files.required' => 'Vous devez ajouter au moins une image à votre TP.',
                'files.min' => 'Vous devez ajouter au moins une image à votre TP.',
                'files.*.required' => 'Chaque fichier est obligatoire.',
                'files.*.max' => 'Chaque fichier ne peut pas dépasser 10MB.',
                'files.*.mimes' => 'Types de fichiers autorisés: PDF, DOC, DOCX, JPG, JPEG, PNG, GIF, ZIP, RAR, TXT, PPT, PPTX, XLS, XLSX.'
            ]);

            // Validation spécifique : au moins une image requise
            $hasImage = false;
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    if ($file->isValid()) {
                        $mimeType = $file->getMimeType();
                        if (strpos($mimeType, 'image/') === 0) {
                            $hasImage = true;
                            break;
                        }
                    }
                }
            }

            if (!$hasImage) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Vous devez ajouter au moins une image (JPG, PNG, GIF) à votre TP.');
            }

            // Insérer le TP avec statut 'pending' car l'ajout = soumission en attente de validation
            $tpId = DB::table('tp')->insertGetId([
                'user_id' => session('user_id'),
                'title' => $request->title,
                'description' => $request->description,
                'link' => $request->link,
                'status' => 'pending' // TP en attente de validation
            ]);

            // Traiter les fichiers uploadés
            if ($request->hasFile('files')) {
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

                        // Enregistrer les informations du fichier en base avec Laravel DB
                        DB::table('tp_files')->insert([
                            'tp_id' => $tpId,
                            'original_name' => $originalName,
                            'stored_name' => $fileName,
                            'file_path' => $filePath,
                            'file_size' => $fileSize,
                            'mime_type' => $mimeType
                        ]);
                    }
                }
            }

            return redirect()->route('dashboard.design-graphique')
                ->with('success', 'TP ajouté avec succès!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'ajout du TP: ' . $e->getMessage());
        }
    }

    /**
     * Diagnostic complet des TP et statistiques - 100% Laravel
     * Route: /evc/compte/design-graphique/diagnostic
     */
    public function diagnosticTP()
    {
        // Vérifier l'authentification
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté.');
        }

        $userId = session('user_id');
        $diagnostic = [];

        try {
            // 1. Informations session
            $diagnostic['session'] = [
                'user_id' => $userId,
                'logged_in' => session('logged_in'),
                'user_name' => session('user_name'),
                'user_email' => session('user_email'),
                'user_prenom' => session('user_prenom'),
                'user_nom' => session('user_nom')
            ];

            // 2. Tous les TP de l'utilisateur
            $allTps = DB::table('tp')
                ->select('id', 'title', 'status', 'created_at', 'updated_at')
                ->where('user_id', $userId)
                ->orderByDesc('created_at')
                ->get();

            $diagnostic['tps'] = [
                'total' => $allTps->count(),
                'list' => $allTps->toArray()
            ];

            // 3. Statistiques par statut
            $statusStats = DB::table('tp')
                ->select('status', DB::raw('COUNT(*) as count'))
                ->where('user_id', $userId)
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();

            $diagnostic['status_stats'] = $statusStats;

            // 4. Test des requêtes du contrôleur
            $tpPending = DB::table('tp')
                ->where('user_id', $userId)
                ->where('status', 'pending')
                ->get();

            $tpValidate = DB::table('tp')
                ->where('user_id', $userId)
                ->where('status', 'validate')
                ->get();

            $diagnostic['controller_queries'] = [
                'pending_count' => $tpPending->count(),
                'pending_list' => $tpPending->toArray(),
                'validate_count' => $tpValidate->count(),
                'validate_list' => $tpValidate->toArray()
            ];

            // 5. Statistiques calculées comme dans le contrôleur
            $validationStats = $this->getValidationStats($userId);
            $diagnostic['validation_stats'] = $validationStats;

            // 6. Statistiques TP générales
            $tpStats = $this->getTpStatistics($userId);
            $diagnostic['tp_stats'] = $tpStats;

            // 7. Structure de la table TP
            $tableStructure = DB::select('DESCRIBE tp');
            $diagnostic['table_structure'] = $tableStructure;

        } catch (\Exception $e) {
            $diagnostic['error'] = $e->getMessage();
        }

        return view('diagnostic.tp', compact('diagnostic'));
    }

    /**
     * Lister tous les TP de l'utilisateur avec statistiques dynamiques
     */
    public function listTP()
    {
        // Vérifier que l'utilisateur est connecté
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        try {
            // Connexion à la base de données
            $pdo = new \PDO(
                'mysql:host=127.0.0.1;port=3306;dbname=v4_evc;charset=utf8mb4',
                'root',
                '',
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );

            // Récupérer les statistiques TP dynamiques
            $stmt = $pdo->prepare("
                SELECT
                    COUNT(DISTINCT tp.id) as total_tp,
                    COUNT(tp_files.id) as total_files,
                    SUM(CASE WHEN tp_files.file_type LIKE 'image/%' THEN 1 ELSE 0 END) as total_images,
                    SUM(CASE WHEN tp_files.file_type = 'application/pdf' THEN 1 ELSE 0 END) as total_pdf,
                    ROUND(SUM(tp_files.file_size) / 1024 / 1024, 2) as total_size_mb
                FROM tp
                LEFT JOIN tp_files ON tp.id = tp_files.tp_id
                WHERE tp.user_id = ?
            ");

            $stmt->execute([session('user_id')]);
            $tpStats = $stmt->fetch(\PDO::FETCH_ASSOC);

            // Récupérer tous les TP de l'utilisateur avec leurs fichiers
            $stmt = $pdo->prepare("
                SELECT tp.*,
                       COUNT(tp_files.id) as files_count
                FROM tp
                LEFT JOIN tp_files ON tp.id = tp_files.tp_id
                WHERE tp.user_id = ?
                GROUP BY tp.id
                ORDER BY tp.created_at DESC
            ");

            $stmt->execute([session('user_id')]);
            $tps = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Récupérer les informations utilisateur pour le profil
            $stmt = $pdo->prepare("SELECT nom, prenom, photo FROM users WHERE id = ?");
            $stmt->execute([session('user_id')]);
            $userProfile = $stmt->fetch(\PDO::FETCH_OBJ);

            // Calculer les statistiques pour l'affichage
            $totalTPRequired = 20;
            $tpRealises = $tpStats['total_tp'] ?? 0;
            $tpAFaire = max(0, $totalTPRequired - $tpRealises);
            $tpValides = min($tpRealises, $totalTPRequired); // Supposons que tous les TP réalisés sont validés
            $progressionPourcentage = round(($tpRealises / $totalTPRequired) * 100, 1);

            // Préparer les statistiques pour la vue
            $statistiques = [
                'tp_a_faire' => $tpAFaire,
                'tp_realises' => $tpRealises,
                'tp_valides' => $tpValides,
                'tp_total' => $totalTPRequired,
                'progression_pourcentage' => $progressionPourcentage,
                'total_files' => $tpStats['total_files'] ?? 0,
                'total_images' => $tpStats['total_images'] ?? 0,
                'total_pdf' => $tpStats['total_pdf'] ?? 0,
                'total_size_mb' => $tpStats['total_size_mb'] ?? 0
            ];

            return view('tp.index', compact('tps', 'statistiques', 'userProfile'));
        } catch (\Exception $e) {
            // En cas d'erreur, afficher avec des statistiques par défaut
            $statistiques = [
                'tp_a_faire' => 20,
                'tp_realises' => 0,
                'tp_valides' => 0,
                'tp_total' => 20,
                'progression_pourcentage' => 0,
                'total_files' => 0,
                'total_images' => 0,
                'total_pdf' => 0,
                'total_size_mb' => 0
            ];

            $userProfile = (object) [
                'nom' => session('user_nom', 'Utilisateur'),
                'prenom' => session('user_prenom', ''),
                'photo' => session('user_photo')
            ];

            return view('tp.index', compact('statistiques', 'userProfile'))
                ->with('error', 'Erreur lors du chargement des TP: ' . $e->getMessage());
        }
    }

    /**
     * Afficher la page CVThèque
     */
    public function cvtheque()
    {
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        return view('cvtheque.index');
    }

    /**
     * Afficher la page Programme
     */
    public function programme()
    {
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        return view('programme.index');
    }

    /**
     * Afficher la page Paiements
     */
    public function paiements()
    {
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        return view('paiements.index');
    }

    /**
     * Afficher la page Fin de formation
     */
    public function finFormation()
    {
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        return view('fin-formation.index');
    }

    /**
     * Afficher la page Paramètres
     */
    public function parametres()
    {
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        return view('parametres.index');
    }

    /**
     * Afficher la page Communauté
     */
    public function communaute()
    {
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        return view('communaute.index');
    }

    /**
     * Afficher l'espace étudiant Design Graphique avec statistiques dynamiques
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function designGraphique()
    {
        // Vérification de l'authentification
        if (!$this->isUserAuthenticated()) {
            return $this->redirectToLogin('Vous devez être connecté pour accéder à cette page.');
        }

        try {
            $userId = session('user_id');

            // Récupération des données depuis la base de données
            $dashboardData = $this->getDashboardData($userId);

            // Calcul des statistiques de progression
            $progressStats = $this->calculateProgressionStats($dashboardData['tpStats']);

            // Préparation des données pour la vue
            $viewData = $this->prepareDashboardViewData($dashboardData, $progressStats);

            return view('dashboard.design-graphique', $viewData);
        } catch (\Exception $e) {
            // Gestion d'erreur avec données par défaut
            $fallbackData = $this->getFallbackDashboardData();

            return view('dashboard.design-graphique', $fallbackData)
                ->with('error', 'Erreur lors du chargement des statistiques: ' . $e->getMessage());
        }
    }

    /**
     * Vérifier si l'utilisateur est authentifié
     *
     * @return bool
     */
    private function isUserAuthenticated(): bool
    {
        return session('logged_in', false);
    }

    /**
     * Rediriger vers la page de connexion avec un message d'erreur
     *
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectToLogin(string $message): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('login')->with('error', $message);
    }

    /**
     * Récupérer toutes les données nécessaires pour le dashboard
     *
     * @param int $userId
     * @return array
     * @throws \Exception
     */
    private function getDashboardData(int $userId): array
    {
        return [
            'tpStats' => $this->getTpStatistics($userId),
            'recentTPs' => $this->getRecentTPs($userId),
            'validationStats' => $this->getValidationStats($userId),
            'userProfile' => $this->getUserProfile($userId)
        ];
    }

    /**
     * Récupérer les statistiques des TP de l'utilisateur avec Laravel DB
     *
     * @param int $userId
     * @return array
     */
    private function getTpStatistics(int $userId): array
    {
        $result = DB::selectOne("
            SELECT
                COUNT(DISTINCT tp.id) as total_tp,
                COUNT(tp_files.id) as total_files,
                SUM(CASE WHEN tp_files.file_type LIKE 'image/%' THEN 1 ELSE 0 END) as total_images,
                SUM(CASE WHEN tp_files.file_type = 'application/pdf' THEN 1 ELSE 0 END) as total_pdf,
                ROUND(SUM(tp_files.file_size) / 1024 / 1024, 2) as total_size_mb
            FROM tp
            LEFT JOIN tp_files ON tp.id = tp_files.tp_id
            WHERE tp.user_id = ?
        ", [$userId]);

        // Convertir l'objet stdClass en array et assurer les types
        return [
            'total_tp' => (int) ($result->total_tp ?? 0),
            'total_files' => (int) ($result->total_files ?? 0),
            'total_images' => (int) ($result->total_images ?? 0),
            'total_pdf' => (int) ($result->total_pdf ?? 0),
            'total_size_mb' => (float) ($result->total_size_mb ?? 0)
        ];
    }

    /**
     * Récupérer les derniers TP de l'utilisateur avec Laravel DB
     *
     * @param int $userId
     * @return array
     */
    private function getRecentTPs(int $userId): array
    {
        return DB::select("
            SELECT tp.*, COUNT(tp_files.id) as files_count
            FROM tp
            LEFT JOIN tp_files ON tp.id = tp_files.tp_id
            WHERE tp.user_id = ?
            GROUP BY tp.id
            ORDER BY tp.created_at DESC
            LIMIT 5
        ", [$userId]);
    }

    /**
     * Récupérer le profil utilisateur avec Laravel DB
     *
     * @param int $userId
     * @return object
     */
    private function getUserProfile(int $userId): object
    {
        $userProfile = DB::selectOne("
            SELECT
                id, first_name, last_name, email, phone,
                country, city, profile_photo,
                current_level, whatsapp, created_at, status, formation_souhaitee
            FROM users
            WHERE id = ?
        ", [$userId]);

        // Si l'utilisateur n'est pas trouvé en base, utiliser les données de session
        if (!$userProfile) {
            $userProfile = $this->getUserProfileFromSession();
        }

        return $userProfile;
    }

    /**
     * Récupérer les statistiques de validation avec Laravel DB
     *
     * @param int $userId
     * @return array
     */
    private function getValidationStats(int $userId): array
    {
        try {
            // TP en attente de validation
            $tpEnValidation = DB::table('tp')
                ->select('id', 'title', 'created_at', 'status')
                ->where('user_id', $userId)
                ->whereRaw("LOWER(TRIM(status)) = 'pending'")
                ->orderByDesc('created_at')
                ->get()
                ->toArray();

            // TP validés
            $tpValides = DB::table('tp')
                ->select('id', 'title', 'created_at')
                ->where('user_id', $userId)
                ->where('status', 'validate')
                ->orderByDesc('created_at')
                ->get()
                ->toArray();

            // TP rejetés
            $tpRejetes = DB::table('tp')
                ->select('id', 'title', 'created_at')
                ->where('user_id', $userId)
                ->where('status', 'rejected')
                ->orderByDesc('created_at')
                ->get()
                ->toArray();

            // Projets en cours de validation
            $projectCount = DB::table('project_submissions')
                ->where('user_id', $userId)
                ->whereIn('status', ['Soumis', 'En évaluation'])
                ->count();

            return [
                'tp_en_validation'     => count($tpEnValidation),
                'tp_en_validation_list' => $tpEnValidation,
                'tp_valides'           => count($tpValides),
                'tp_valides_list'      => $tpValides,
                'tp_rejetes'           => count($tpRejetes),
                'tp_rejetes_list'      => $tpRejetes,
                'projets_en_validation' => $projectCount,
                'total_en_validation'  => count($tpEnValidation) + $projectCount
            ];
        } catch (\Throwable $e) {
            // Log de l'erreur (optionnel)
            \Log::error('Erreur getValidationStats : ' . $e->getMessage());

            return [
                'tp_en_validation'     => 0,
                'tp_en_validation_list' => [],
                'tp_valides'           => 0,
                'tp_valides_list'      => [],
                'tp_rejetes'           => 0,
                'tp_rejetes_list'      => [],
                'projets_en_validation' => 0,
                'total_en_validation'  => 0
            ];
        }
    }


    /**
     * Calculer les statistiques de progression
     *
     * @param array $tpStats
     * @return array
     */
    private function calculateProgressionStats(array $tpStats): array
    {
        $totalTPRequired = 50; // Configuration : nombre de TP requis
        $completedTPs = $tpStats['total_tp'];
        $completionPercentage = $completedTPs > 0
            ? min(100, ($completedTPs / $totalTPRequired) * 100)
            : 0;

        return [
            'total_required' => $totalTPRequired,
            'completed' => $completedTPs,
            'remaining' => max(0, $totalTPRequired - $completedTPs),
            'completion_percentage' => round($completionPercentage, 1),
            'progression_level' => $this->calculateProgressionLevel($completedTPs),
            'eligible_certificate' => $completedTPs >= $totalTPRequired
        ];
    }

    /**
     * Préparer toutes les données pour la vue
     *
     * @param array $dashboardData
     * @param array $progressStats
     * @return array
     */
    private function prepareDashboardViewData(array $dashboardData, array $progressStats): array
    {
        // Statistiques utilisateur
        $userStats = (object) [
            'completion_percentage' => $progressStats['completion_percentage'],
            'tp_realises' => $progressStats['completed'],
            'tp_restants' => $progressStats['remaining'],
            'progression_niveau' => $progressStats['progression_level']
        ];

        // Statistiques générales
        $stats = [
            'eligible_certificat' => $progressStats['eligible_certificate'],
            'formation_semaine' => 'Formation Design Graphique - Semaine ' . date('W'),
            'tp_realises' => $progressStats['completed'],
            'tp_total_requis' => $progressStats['total_required'],
            'progression_globale' => $progressStats['completion_percentage'],
            'fichiers_total' => $dashboardData['tpStats']['total_files'],
            'images_total' => $dashboardData['tpStats']['total_images'],
            'pdf_total' => $dashboardData['tpStats']['total_pdf'],
            'taille_totale_mb' => $dashboardData['tpStats']['total_size_mb']
        ];

        return [
            'tpStats' => $dashboardData['tpStats'],
            'recentTPs' => $dashboardData['recentTPs'],
            'userProfile' => $dashboardData['userProfile'],
            'validationStats' => $dashboardData['validationStats'],
            'userStats' => $userStats,
            'stats' => $stats
        ];
    }


    /**
     * Créer un profil utilisateur à partir des données de session
     *
     * @return object
     */
    private function getUserProfileFromSession(): object
    {
        return (object) [
            'first_name' => session('user_prenom', ''),
            'last_name' => session('user_nom', 'Utilisateur'),
            'email' => session('user_email', 'Email non disponible'),
            'phone' => session('user_telephone', 'Non spécifié'),
            'whatsapp' => session('user_whatsapp', 'Non spécifié'),
            'city' => session('user_ville', 'Non spécifiée'),
            'country' => session('user_pays', ''),
            'current_level' => session('user_niveau', 'Non spécifié'),
            'profile_photo' => session('user_photo'),
            'created_at' => date('Y-m-d'),
            'status' => 'Étudiant actif',
            'formation_souhaitee' => session('user_formation', 'design-graphique')
        ];
    }



    /**
     * Obtenir les données par défaut en cas d'erreur
     *
     * @return array
     */
    private function getFallbackDashboardData(): array
    {
        $totalTPRequired = 20;

        return [
            'tpStats' => [
                'total_tp' => 0,
                'total_files' => 0,
                'total_images' => 0,
                'total_pdf' => 0,
                'total_size_mb' => 0
            ],
            'recentTPs' => [],
            'userProfile' => $this->getUserProfileFromSession(),
            'validationStats' => [
                'tp_en_validation' => 0,
                'projets_en_validation' => 0,
                'total_en_validation' => 0
            ],
            'userStats' => (object) [
                'completion_percentage' => 0,
                'tp_realises' => 0,
                'tp_restants' => $totalTPRequired,
                'progression_niveau' => 'Nouveau'
            ],
            'stats' => [
                'eligible_certificat' => false,
                'formation_semaine' => 'Formation Design Graphique - Semaine ' . date('W'),
                'tp_realises' => 0,
                'tp_total_requis' => $totalTPRequired,
                'progression_globale' => 0,
                'fichiers_total' => 0,
                'images_total' => 0,
                'pdf_total' => 0,
                'taille_totale_mb' => 0
            ]
        ];
    }

    /**
     * Calculer le niveau de progression basé sur le nombre de TP
     */
    private function calculateProgressionLevel($totalTP)
    {
        if ($totalTP >= 20) {
            return 'Expert';
        } elseif ($totalTP >= 15) {
            return 'Avancé';
        } elseif ($totalTP >= 10) {
            return 'Intermédiaire';
        } elseif ($totalTP >= 5) {
            return 'Débutant+';
        } elseif ($totalTP >= 1) {
            return 'Débutant';
        } else {
            return 'Nouveau';
        }
    }

    /**
     * Afficher l'espace étudiant Community Management
     */
    public function communityManagement()
    {
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        return view('dashboard.community-management');
    }

    /**
     * Afficher l'espace étudiant Intelligence Artificielle
     */
    public function intelligenceArtificielle()
    {
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        return view('dashboard.intelligence-artificielle');
    }

    /**
     * Afficher l'espace étudiant Gestion Informatique
     */
    public function gestionInformatique()
    {
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        return view('dashboard.gestion-informatique');
    }
}
