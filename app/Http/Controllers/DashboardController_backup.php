<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
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
}
?>
