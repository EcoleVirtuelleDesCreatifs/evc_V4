<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Redirection automatique vers l'espace étudiant selon la formation
     */
    public function redirectToFormation()
    {
        // Vérification de l'authentification
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        // Récupérer la formation de l'utilisateur
        $userFormation = session('user_formation');
        
        // Si les informations de formation ne sont pas en session, les récupérer de la base
        if (!$userFormation) {
            try {
                $pdo = new \PDO(
                    'mysql:host=127.0.0.1;port=3306;dbname=v4_evc;charset=utf8',
                    'root',
                    '',
                    [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
                );
                
                $stmt = $pdo->prepare('SELECT formation_souhaitee FROM users WHERE id = ?');
                $stmt->execute([session('user_id')]);
                $user = $stmt->fetch(\PDO::FETCH_ASSOC);
                
                if ($user) {
                    $userFormation = $user['formation_souhaitee'];
                    session(['user_formation' => $userFormation]);
                }
            } catch (\PDOException $e) {
                // En cas d'erreur, utiliser la formation par défaut
                $userFormation = 'design_graphique';
            }
        }
        
        // Rediriger vers l'URL personnalisée selon le type de formation
        switch ($userFormation) {
            case 'design_graphique':
                return redirect()->route('dashboard.design-graphique');
            case 'community_management':
                return redirect()->route('dashboard.community-manager');
            case 'intelligence_artificielle':
                return redirect()->route('dashboard.intelligence-artificielle');
            case 'gestion_informatique':
                return redirect()->route('dashboard.gestion-informatique');
            default:
                return redirect()->route('dashboard.design-graphique'); // Par défaut
        }
    }

    /**
     * Espace étudiant Design Graphique
     */
    public function designGraphique()
    {
        // Vérification de l'authentification
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }
        
        return $this->dashboardDesignGraphique();
    }

    /**
     * Espace étudiant Community Management
     */
    public function communityManagement()
    {
        // Vérification de l'authentification
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }
        
        return $this->dashboardCommunityManagement();
    }

    /**
     * Espace étudiant Intelligence Artificielle
     */
    public function intelligenceArtificielle()
    {
        // Vérification de l'authentification
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }
        
        return $this->dashboardIntelligenceArtificielle();
    }

    /**
     * Espace étudiant Gestion Informatique
     */
    public function gestionInformatique()
    {
        // Vérification de l'authentification
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }
        
        return $this->dashboardGestionInformatique();
    }

    /**
     * Méthode index conservée pour compatibilité
     */
    public function index()
    {
        return $this->redirectToFormation();
    }
    
    /**
     * Dashboard pour la formation Design Graphique
     */
    private function dashboardDesignGraphique()
    {
        // Récupérer les informations complètes du profil utilisateur connecté
        $userId = session('user_id');
        $userProfile = DB::table('users')
            ->select('*')
            ->where('id', $userId)
            ->first();
            
        // Récupérer les statistiques utilisateur
        $userStats = DB::table('user_statistics')
            ->where('user_id', $userId)
            ->first();
            
        // Statistiques générales depuis la table users unifiée
        $totalStudents = DB::table('users')->count();
        $activeStudents = DB::table('users')->where('status', 'Actif')->count();
        $graduatedStudents = DB::table('users')->where('status', 'Diplômé')->count();
        $averageGpa = 0; // À calculer depuis user_statistics si nécessaire
        
        // Données spécifiques au design graphique
        $stats = [
            'total_formations' => 4,
            'formation_semaine' => 'Photoshop Avancé',
            'tp_a_faire' => 2,
            'projets_en_cours' => 3,
            'progression_globale' => 68,
            'eligible_certificat' => true,
            'statut_paiement' => 'À jour',
            'formation_type' => 'Design Graphique',
            'modules' => ['Photoshop', 'Illustrator', 'InDesign', 'Strategy Business'],
            'couleur_principale' => '#FF6B35'
        ];
        
        return view('dashboard.design-graphique', compact(
            'totalStudents', 
            'activeStudents', 
            'graduatedStudents', 
            'averageGpa',
            'stats',
            'userProfile',
            'userStats'
        ));
    }
    
    /**
     * Dashboard pour la formation Community Management
     */
    private function dashboardCommunityManagement()
    {
        // Statistiques générales depuis la table users unifiée
        $totalStudents = DB::table('users')->count();
        $activeStudents = DB::table('users')->where('status', 'Actif')->count();
        $graduatedStudents = DB::table('users')->where('status', 'Diplômé')->count();
        $averageGpa = 0; // À calculer depuis user_statistics si nécessaire
        
        // Données spécifiques au community management
        $stats = [
            'total_formations' => 5,
            'formation_semaine' => 'Stratégie Social Media',
            'tp_a_faire' => 3,
            'projets_en_cours' => 2,
            'progression_globale' => 72,
            'eligible_certificat' => true,
            'statut_paiement' => 'À jour',
            'formation_type' => 'Community Management',
            'modules' => ['Social Media', 'Content Marketing', 'Analytics', 'Brand Strategy'],
            'couleur_principale' => '#4267B2'
        ];
        
        return view('dashboard.community-management', compact(
            'totalStudents', 
            'activeStudents', 
            'graduatedStudents', 
            'averageGpa',
            'stats'
        ));
    }
    
    /**
     * Dashboard pour la formation Intelligence Artificielle
     */
    private function dashboardIntelligenceArtificielle()
    {
        // Statistiques générales depuis la table users unifiée
        $totalStudents = DB::table('users')->count();
        $activeStudents = DB::table('users')->where('status', 'Actif')->count();
        $graduatedStudents = DB::table('users')->where('status', 'Diplomé')->count();
        $averageGpa = 0; // À calculer depuis user_statistics si nécessaire
        
        // Données spécifiques à l'IA
        $stats = [
            'total_formations' => 6,
            'formation_semaine' => 'Machine Learning Basics',
            'tp_a_faire' => 4,
            'projets_en_cours' => 2,
            'progression_globale' => 45,
            'eligible_certificat' => false,
            'statut_paiement' => 'À jour',
            'formation_type' => 'Intelligence Artificielle',
            'modules' => ['Python', 'Machine Learning', 'Deep Learning', 'Data Science'],
            'couleur_principale' => '#00D4AA'
        ];
        
        return view('dashboard.intelligence-artificielle', compact(
            'totalStudents', 
            'activeStudents', 
            'graduatedStudents', 
            'averageGpa',
            'stats'
        ));
    }
    
    /**
     * Dashboard pour la formation Gestion Informatique
     */
    private function dashboardGestionInformatique()
    {
        // Statistiques générales depuis la table users unifiée
        $totalStudents = DB::table('users')->count();
        $activeStudents = DB::table('users')->where('status', 'Actif')->count();
        $graduatedStudents = DB::table('users')->where('status', 'Diplômé')->count();
        $averageGpa = 0; // À calculer depuis user_statistics si nécessaire
        
        // Données spécifiques à la gestion informatique
        $stats = [
            'total_formations' => 7,
            'formation_semaine' => 'Administration Système',
            'tp_a_faire' => 3,
            'projets_en_cours' => 4,
            'progression_globale' => 58,
            'eligible_certificat' => false,
            'statut_paiement' => 'À jour',
            'formation_type' => 'Gestion Informatique',
            'modules' => ['Réseaux', 'Sécurité', 'Administration', 'Cloud Computing'],
            'couleur_principale' => '#6C5CE7'
        ];
        
        return view('dashboard.gestion-informatique', compact(
            'totalStudents', 
            'activeStudents', 
            'graduatedStudents', 
            'averageGpa',
            'stats'
        ));
    }

    /**
     * Afficher le formulaire d'édition du profil utilisateur
     */
    public function editProfile()
    {
        // Vérification de l'authentification
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $userId = session('user_id');
        
        // Récupération des données utilisateur depuis la table users
        $user = DB::table('users')->where('id', $userId)->first();
        
        if (!$user) {
            return redirect()->route('dashboard')->with('error', 'Profil utilisateur introuvable.');
        }

        return view('profile.edit', compact('user'));
    }

    /**
     * Mettre à jour le profil utilisateur
     */
    public function updateProfile(Request $request)
    {
        // Vérification de l'authentification
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $userId = session('user_id');
        
        try {
            // Validation des données
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
                'biography' => 'nullable|string|max:1000',
                'expectations' => 'nullable|string|max:1000',
                'current_level' => 'required|in:debutant,intermediaire,perfectionnement',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'password' => 'nullable|string|min:6|confirmed'
            ]);

            // Gestion de l'upload de photo
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $photo->move(public_path('uploads/photos'), $photoName);
                $photoPath = 'uploads/photos/' . $photoName;
            }

            // Préparation des données à mettre à jour
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

            // Ajout de la photo si uploadée
            if ($photoPath) {
                $updateData['profile_photo'] = $photoPath;
            }

            // Mise à jour du mot de passe si fourni
            if (!empty($validatedData['password'])) {
                $updateData['password'] = password_hash($validatedData['password'], PASSWORD_DEFAULT);
            }

            // Mise à jour en base de données
            DB::table('users')
                ->where('id', $userId)
                ->update($updateData);

            // Mise à jour des données de session
            session([
                'user_name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
                'user_email' => $validatedData['email'],
                'user_pays' => $validatedData['country'],
                'user_ville' => $validatedData['city'],
                'user_level' => $validatedData['current_level']
            ]);

            // Mise à jour de la photo en session si uploadée
            if ($photoPath) {
                session(['user_photo' => $photoPath]);
            }

            return redirect()->route('design-graphique.profil.editer')->with('success', 'Profil mis à jour avec succès!');

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
     * Enregistrer un nouveau TP
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
            
            // Connexion à la base de données
            $pdo = new \PDO(
                'mysql:host=127.0.0.1;port=3306;dbname=v4_evc;charset=utf8mb4',
                'root',
                '',
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
            
            // Créer la table tp si elle n'existe pas
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS tp (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT NOT NULL,
                    title VARCHAR(255) NOT NULL,
                    description TEXT,
                    link VARCHAR(500),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )
            ");
            
            // Créer la table tp_files si elle n'existe pas
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS tp_files (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    tp_id INT NOT NULL,
                    original_name VARCHAR(255) NOT NULL,
                    file_path VARCHAR(500) NOT NULL,
                    file_size INT NOT NULL,
                    mime_type VARCHAR(100) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (tp_id) REFERENCES tp(id) ON DELETE CASCADE
                )
            ");
            
            // Insérer le TP
            $stmt = $pdo->prepare("
                INSERT INTO tp (user_id, title, description, link) 
                VALUES (?, ?, ?, ?)
            ");
            
            $stmt->execute([
                session('user_id'),
                $request->title,
                $request->description,
                $request->link
        }

        // Mise à jour du mot de passe si fourni
        if (!empty($validatedData['password'])) {
            $updateData['password'] = password_hash($validatedData['password'], PASSWORD_DEFAULT);
        }

        // Mise à jour en base de données
        DB::table('users')
            ->where('id', $userId)
            ->update($updateData);

        // Mise à jour des données de session
        session([
            'user_name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
            'user_email' => $validatedData['email'],
            'user_pays' => $validatedData['country'],
            'user_ville' => $validatedData['city'],
            'user_level' => $validatedData['current_level']
        ]);

        // Mise à jour de la photo en session si uploadée
        if ($photoPath) {
            session(['user_photo' => $photoPath]);
        }

        return redirect()->route('design-graphique.profil.editer')->with('success', 'Profil mis à jour avec succès!');

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
 * Enregistrer un nouveau TP
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
            
        // Connexion à la base de données
        $pdo = new \PDO(
            'mysql:host=127.0.0.1;port=3306;dbname=v4_evc;charset=utf8mb4',
            'root',
            '',
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );
            
        // Créer la table tp si elle n'existe pas
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS tp (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                link VARCHAR(500),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");
            
        // Créer la table tp_files si elle n'existe pas
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS tp_files (
                id INT AUTO_INCREMENT PRIMARY KEY,
                tp_id INT NOT NULL,
                original_name VARCHAR(255) NOT NULL,
                file_path VARCHAR(500) NOT NULL,
                file_size INT NOT NULL,
                mime_type VARCHAR(100) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (tp_id) REFERENCES tp(id) ON DELETE CASCADE
            )
        ");
            
        // Insérer le TP
        $stmt = $pdo->prepare("
            INSERT INTO tp (user_id, title, description, link) 
            VALUES (?, ?, ?, ?)
        ");
            
        $stmt->execute([
            session('user_id'),
            $request->title,
            $request->description,
            $request->link
        ]);
            
        $tpId = $pdo->lastInsertId();
            
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
                        
                    // Générer un nom unique pour le fichier
                    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $filePath = 'uploads/tp/' . $fileName;
                        
                    // Déplacer le fichier
                    $file->move($uploadPath, $fileName);
                        
                    // Enregistrer les informations du fichier en base
                    $stmt = $pdo->prepare("
                        INSERT INTO tp_files (tp_id, original_name, file_path, file_size, mime_type) 
                        VALUES (?, ?, ?, ?, ?)
                    ");
                        
                    $stmt->execute([
                        $tpId,
                        $originalName,
                        $filePath,
                        $fileSize,
                        $mimeType
                    ]);
                }
            }
        }
            
        return redirect()->route('design-graphique.tp.ajouter')
            ->with('success', 'TP ajouté avec succès!');
                
    } catch (\Exception $e) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Erreur lors de l\'ajout du TP: ' . $e->getMessage());
    }
}    }
    }
    
    /**
     * Lister tous les TP de l'utilisateur
     */
public function listTP()
{
    // Vérifier que l'utilisateur est connecté
    if (!session('logged_in')) {
        return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
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
            
            return view('tp.index', compact('tps'));
            
        } catch (\Exception $e) {
            return redirect()->route('dashboard.design-graphique')
                ->with('error', 'Erreur lors du chargement des TP: ' . $e->getMessage());
        }
    }
}
