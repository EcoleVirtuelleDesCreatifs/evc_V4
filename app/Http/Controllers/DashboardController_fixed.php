<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Afficher le dashboard principal selon le type de formation
     */
    public function index()
    {
        // Vérifier que l'utilisateur est connecté
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        // Récupérer le type de formation de l'utilisateur depuis la session
        $userFormation = session('user_formation', 'design-graphique');
        
        // Rediriger vers la vue appropriée selon le type de formation
        switch ($userFormation) {
            case 'design-graphique':
                return view('dashboard.design-graphique');
            case 'community-management':
                return view('dashboard.community-management');
            case 'intelligence-artificielle':
                return view('dashboard.intelligence-artificielle');
            case 'gestion-informatique':
                return view('dashboard.gestion-informatique');
            default:
                return view('dashboard.design-graphique');
        }
    }

    /**
     * Afficher le formulaire d'édition du profil
     */
    public function editProfile()
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

            // Récupérer les informations de l'utilisateur
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([session('user_id')]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$user) {
                return redirect()->route('login')->with('error', 'Utilisateur introuvable.');
            }

            return view('profile.edit', compact('user'));

        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Erreur lors du chargement du profil: ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour le profil utilisateur
     */
    public function updateProfile(Request $request)
    {
        // Vérifier que l'utilisateur est connecté
        if (!session('logged_in')) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour effectuer cette action.');
        }

        try {
            // Validation des données
            $validatedData = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'pays' => 'required|string|max:255',
                'ville' => 'required|string|max:255',
                'quartier' => 'nullable|string|max:255',
                'numero' => 'nullable|string|max:20',
                'whatsapp' => 'nullable|string|max:20',
                'niveau_etude' => 'nullable|string|max:255',
                'dernier_diplome' => 'nullable|string|max:255',
                'age' => 'nullable|integer|min:16|max:100',
                'biographie' => 'nullable|string|max:2000',
                'attentes' => 'nullable|string|max:2000',
                'niveau_actuel' => 'nullable|in:Débutant,Intermédiaire,Perfectionnement',
                'password' => 'nullable|string|min:6|confirmed',
                'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048'
            ]);

            $userId = session('user_id');
            $updateData = [
                'nom' => $validatedData['nom'],
                'prenom' => $validatedData['prenom'],
                'email' => $validatedData['email'],
                'pays' => $validatedData['pays'],
                'ville' => $validatedData['ville'],
                'quartier' => $validatedData['quartier'],
                'numero' => $validatedData['numero'],
                'whatsapp' => $validatedData['whatsapp'],
                'niveau_etude' => $validatedData['niveau_etude'],
                'dernier_diplome' => $validatedData['dernier_diplome'],
                'age' => $validatedData['age'],
                'biographie' => $validatedData['biographie'],
                'attentes' => $validatedData['attentes'],
                'niveau_actuel' => $validatedData['niveau_actuel'],
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Gestion de l'upload de photo
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                if ($photo->isValid()) {
                    $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    $photo->move(public_path('uploads/profiles'), $photoName);
                    $updateData['photo'] = 'uploads/profiles/' . $photoName;
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
     * Enregistrer un nouveau TP - VERSION CORRIGÉE
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
            
            // Traiter les fichiers uploadés - CORRECTION DE L'ERREUR SplFileInfo::getSize()
            if ($request->hasFile('files')) {
                $uploadPath = public_path('uploads/tp');
                
                // Créer le dossier s'il n'existe pas
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                foreach ($request->file('files') as $file) {
                    if ($file->isValid()) {
                        // CORRECTION: Récupérer les informations du fichier AVANT de le déplacer
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
    }
    
    /**
     * Lister tous les TP de l'utilisateur
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
            return redirect()->route('dashboard')
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
}
