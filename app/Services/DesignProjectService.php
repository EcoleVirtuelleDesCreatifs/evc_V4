<?php

namespace App\Services;

use Exception;
use PDO;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Service pour la gestion des projets de design graphique
 *
 * Cette classe centralise toute la logique métier liée aux projets de design,
 * incluant la création, validation, gestion des fichiers et statistiques.
 */
class DesignProjectService
{
    private PDO $pdo;

    // Configuration des types de projets autorisés
    const ALLOWED_PROJECT_TYPES = [
        'logo',
        'web',
        'print',
        'packaging',
        'illustration',
        'motion',
        'strategy',
        'autre'
    ];

    // Configuration des logiciels autorisés
    const ALLOWED_SOFTWARE = [
        'photoshop',
        'illustrator',
        'indesign',
        'after_effects',
        'premiere_pro',
        'xd',
        'figma',
        'sketch',
        'canva',
        'other'
    ];

    // Configuration des modes de projet
    const ALLOWED_PROJECT_MODES = ['solo', 'groupe'];

    // Configuration des statuts
    const ALLOWED_STATUSES = ['draft', 'active', 'completed', 'validated', 'cancelled'];

    // Configuration des fichiers
    const MAX_FILE_SIZE = 10240; // 10MB en KB
    const UPLOAD_PATH = 'uploads/design_projects';

    /**
     * Constructeur - Initialise la connexion à la base de données
     */
    public function __construct()
    {
        $this->initializeDatabase();
    }

    /**
     * Initialise la connexion PDO à la base de données
     *
     * @throws Exception Si la connexion échoue
     */
    private function initializeDatabase(): void
    {
        try {
            $this->pdo = DB::connection()->getPdo();
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            Log::error('Erreur connexion base de données: ' . $e->getMessage());
            throw new Exception('Impossible de se connecter à la base de données');
        }
    }

    /**
     * Crée un nouveau projet de design graphique
     *
     * @param Request $request Données de la requête
     * @param int $userId ID de l'utilisateur
     * @return array Résultat de l'opération
     */
    public function createProject(Request $request, int $userId): array
    {
        try {
            // Validation des données
            $validatedData = $this->validateProjectData($request);

            // Démarrer une transaction
            $this->pdo->beginTransaction();

            // Créer le projet
            $projectId = $this->insertProject($validatedData, $userId);

            // Traiter les fichiers uploadés
            $uploadedFiles = $this->handleFileUploads($request, $projectId);

            // Valider la transaction
            $this->pdo->commit();

            // Envoyer une notification email à l'admin si c'est un projet réel (status = 'active')
            if ($validatedData['status'] === 'active') {
                $this->sendAdminNotification($projectId, $userId, count($uploadedFiles));
            }

            return [
                'success' => true,
                'project_id' => $projectId,
                'uploaded_files' => $uploadedFiles,
                'message' => $this->getSuccessMessage($validatedData['status'])
            ];
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            Log::error('Erreur création projet: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Valide les données du projet pour une mise à jour
     *
     * @param Request $request
     * @param int $projectId
     * @param int $userId
     * @return array Données validées
     * @throws Exception Si la validation échoue
     */
    private function validateProjectUpdateData(Request $request, int $projectId, int $userId): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'project_type' => 'required|string|in:' . implode(',', self::ALLOWED_PROJECT_TYPES),
            'description' => 'nullable|string|max:2000',
            'software_used' => 'nullable|array',
            'software_used.*' => 'string|in:' . implode(',', self::ALLOWED_SOFTWARE),
            'category' => 'required|string|in:' . implode(',', self::ALLOWED_PROJECT_MODES),
            'reference_url' => 'nullable|url|max:500',
            'save_as_draft' => 'nullable|boolean',
            'files.*' => 'nullable|file|max:' . self::MAX_FILE_SIZE
        ];

        try {
            $validatedData = $request->validate($rules);

            // Pour les mises à jour, récupérer le statut actuel du projet
            $stmt = $this->pdo->prepare("SELECT status FROM design_projects WHERE id = ? AND user_id = ?");
            $stmt->execute([$projectId, $userId]);
            $currentProject = $stmt->fetch();

            if (!$currentProject) {
                throw new Exception('Projet non trouvé.');
            }

            // Préserver le statut actuel (ne pas permettre la modification via le formulaire)
            $validatedData['status'] = $currentProject['status'];
            $isDraft = $currentProject['status'] === 'draft';

            // Validation spéciale pour les mises à jour : vérifier les fichiers existants
            if (!$isDraft) {
                // Compter les fichiers existants
                $stmt = $this->pdo->prepare("
                    SELECT COUNT(*) as file_count
                    FROM design_project_files dpf
                    JOIN design_projects dp ON dpf.project_id = dp.id
                    WHERE dpf.project_id = ? AND dp.user_id = ?
                ");
                $stmt->execute([$projectId, $userId]);
                $existingFileCount = $stmt->fetchColumn();

                // Compter les nouveaux fichiers valides
                $newValidFiles = 0;
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        if ($file && $file->isValid()) {
                            $newValidFiles++;
                        }
                    }
                }

                // Au moins un fichier requis (existant ou nouveau)
                if ($existingFileCount == 0 && $newValidFiles == 0) {
                    throw new Exception('Au moins une image est requise pour publier un projet. Vous pouvez sauvegarder en brouillon sans image.');
                }
            }

            return $validatedData;
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw new Exception('Données invalides: ' . json_encode($e->errors()));
        }
    }

    /**
     * Valide les données du projet
     *
     * @param Request $request
     * @return array Données validées
     * @throws Exception Si la validation échoue
     */
    private function validateProjectData(Request $request): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'project_type' => 'required|string|in:' . implode(',', self::ALLOWED_PROJECT_TYPES),
            'description' => 'nullable|string|max:2000',
            'software_used' => 'nullable|array',
            'software_used.*' => 'string|in:' . implode(',', self::ALLOWED_SOFTWARE),
            'category' => 'required|string|in:' . implode(',', self::ALLOWED_PROJECT_MODES),
            'reference_url' => 'nullable|url|max:500',
            'save_as_draft' => 'nullable|in:on,true,false,1,0',
            'files.*' => 'nullable|file|max:' . self::MAX_FILE_SIZE
        ];

        try {
            $validatedData = $request->validate($rules);

            // Déterminer le statut - accepter plusieurs formats de booléen (y compris "on" des checkboxes HTML)
            $isDraft = $request->has('save_as_draft') &&
                in_array($request->save_as_draft, ['on', 'true', '1', 1, true], true);
            $validatedData['status'] = $isDraft ? 'draft' : 'active';

            // Validation spéciale : si ce n'est pas un brouillon, au moins une image est requise
            if (!$isDraft) {
                $hasFiles = $request->hasFile('files') && count($request->file('files')) > 0;
                $hasValidFiles = false;

                if ($hasFiles) {
                    foreach ($request->file('files') as $file) {
                        if ($file && $file->isValid()) {
                            $hasValidFiles = true;
                            break;
                        }
                    }
                }

                if (!$hasValidFiles) {
                    throw new Exception('Au moins une image est requise pour publier un projet. Vous pouvez sauvegarder en brouillon sans image.');
                }
            }

            return $validatedData;
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw new Exception('Données invalides: ' . json_encode($e->errors()));
        }
    }

    /**
     * Insère le projet dans la base de données
     *
     * @param array $data Données validées
     * @param int $userId ID utilisateur
     * @return int ID du projet créé
     */
    private function insertProject(array $data, int $userId): int
    {
        $softwareUsed = !empty($data['software_used'])
            ? json_encode($data['software_used'])
            : null;

        $stmt = $this->pdo->prepare("
            INSERT INTO design_projects (
                user_id, title, description, project_type,
                software_used, category, status,
                created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");

        $stmt->execute([
            $userId,
            $data['title'],
            $data['description'] ?? null,
            $data['project_type'],
            $softwareUsed,
            $data['category'],
            $data['status']
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Gère l'upload des fichiers
     *
     * @param Request $request
     * @param int $projectId
     * @return array Liste des fichiers uploadés
     */
    private function handleFileUploads(Request $request, int $projectId): array
    {
        $uploadedFiles = [];

        if (!$request->hasFile('files')) {
            return $uploadedFiles;
        }

        $uploadPath = public_path(self::UPLOAD_PATH);

        // Créer le dossier s'il n'existe pas
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        foreach ($request->file('files') as $file) {
            if ($file->isValid()) {
                $fileInfo = $this->processFile($file, $uploadPath, $projectId);
                $uploadedFiles[] = $fileInfo;
            }
        }

        return $uploadedFiles;
    }

    /**
     * Traite un fichier individuel
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $uploadPath
     * @param int $projectId
     * @return array Informations du fichier
     */
    private function processFile($file, string $uploadPath, int $projectId): array
    {
        // Récupérer les informations du fichier AVANT de le déplacer
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();

        // Générer un nom unique
        $storedName = time() . '_' . Str::random(10) . '.' . $extension;
        $filePath = self::UPLOAD_PATH . '/' . $storedName;

        // Déplacer le fichier
        $file->move($uploadPath, $storedName);

        // Enregistrer en base avec les informations récupérées avant le déplacement
        $stmt = $this->pdo->prepare("
            INSERT INTO design_project_files (
                project_id, original_name, file_path,
                file_size, mime_type, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, NOW(), NOW())
        ");

        $stmt->execute([
            $projectId,
            $originalName,
            $filePath,
            $fileSize,
            $mimeType
        ]);

        return [
            'id' => $this->pdo->lastInsertId(),
            'original_name' => $originalName,
            'stored_name' => $storedName,
            'file_path' => $filePath,
            'file_size' => $fileSize,
            'mime_type' => $mimeType
        ];
    }

    /**
     * Récupère les projets d'un utilisateur
     *
     * @param int $userId
     * @param array $filters Filtres optionnels
     * @return array Liste des projets
     */
    public function getUserProjects(int $userId, array $filters = []): array
    {
        if (!Schema::hasTable('design_projects')) {
            return [];
        }

        $hasFilesTable = Schema::hasTable('design_project_files');

        $sql = "
            SELECT dp.*";

        if ($hasFilesTable) {
            $sql .= ",\n                   COUNT(DISTINCT dpf.id) as files_count\n            FROM design_projects dp\n            LEFT JOIN design_project_files dpf ON dp.id = dpf.project_id\n            WHERE dp.user_id = ?";
        } else {
            $sql .= ",\n                   0 as files_count\n            FROM design_projects dp\n            WHERE dp.user_id = ?";
        }

        $sql .= "
        ";

        $params = [$userId];

        // Appliquer les filtres
        if (!empty($filters['status'])) {
            $sql .= " AND dp.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['project_type'])) {
            $sql .= " AND dp.project_type = ?";
            $params[] = $filters['project_type'];
        }

        if (!empty($filters['category'])) {
            $sql .= " AND dp.category = ?";
            $params[] = $filters['category'];
        }

        $sql .= " GROUP BY dp.id ORDER BY dp.created_at DESC";

        if (!empty($filters['limit'])) {
            $sql .= " LIMIT " . (int) $filters['limit'];
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $projects = $stmt->fetchAll();

        // Récupérer les fichiers pour chaque projet
        foreach ($projects as &$project) {
            $project['files'] = $hasFilesTable ? $this->getProjectFiles((int) $project['id']) : [];

            // Traiter les logiciels utilisés (JSON vers array)
            if (!empty($project['software_used'])) {
                $project['software_used_array'] = json_decode($project['software_used'], true) ?: [];
            } else {
                $project['software_used_array'] = [];
            }
        }

        return $projects;
    }

    public function getUserProjectById(int $userId, int $projectId): ?array
    {
        if (!Schema::hasTable('design_projects')) {
            return null;
        }

        $hasFilesTable = Schema::hasTable('design_project_files');

        $stmt = $this->pdo->prepare("SELECT * FROM design_projects WHERE id = ? AND user_id = ? LIMIT 1");
        $stmt->execute([$projectId, $userId]);
        $project = $stmt->fetch();

        if (!$project) {
            return null;
        }

        $project['files'] = $hasFilesTable ? $this->getProjectFiles($projectId) : [];

        if (!empty($project['software_used'])) {
            $project['software_used_array'] = json_decode($project['software_used'], true) ?: [];
        } else {
            $project['software_used_array'] = [];
        }

        return $project;
    }

    /**
     * Récupère les fichiers d'un projet spécifique
     *
     * @param int $projectId
     * @return array
     */
    private function getProjectFiles(int $projectId): array
    {
        if (!Schema::hasTable('design_project_files')) {
            return [];
        }

        $stmt = $this->pdo->prepare("
            SELECT
                id,
                original_name as name,
                file_path as path,
                file_size,
                mime_type,
                created_at
            FROM design_project_files
            WHERE project_id = ?
            ORDER BY created_at ASC
        ");

        $stmt->execute([$projectId]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère les statistiques d'un utilisateur
     *
     * @param int $userId
     * @return array Statistiques
     */
    public function getUserStats(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                COUNT(*) as total_projects,
                SUM(CASE WHEN category = 'solo' THEN 1 ELSE 0 END) as solo_projects,
                SUM(CASE WHEN category = 'groupe' THEN 1 ELSE 0 END) as group_projects,
                SUM(CASE WHEN status = 'validated' THEN 1 ELSE 0 END) as completed_projects,
                SUM(CASE WHEN status = 'validated' THEN 1 ELSE 0 END) as validated_projects,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_projects,
                SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft_projects
            FROM design_projects
            WHERE user_id = ?
        ");

        $stmt->execute([$userId]);
        $stats = $stmt->fetch();

        return $stats ?: [
            'total_projects' => 0,
            'solo_projects' => 0,
            'group_projects' => 0,
            'completed_projects' => 0,
            'validated_projects' => 0,
            'active_projects' => 0,
            'draft_projects' => 0,
            'avg_progress' => 0
        ];
    }

    /**
     * Met à jour un projet complet
     *
     * @param Request $request
     * @param int $projectId
     * @param int $userId
     * @return array
     */
    public function updateProject(Request $request, int $projectId, int $userId): array
    {
        try {
            // Validation des données pour la mise à jour
            $validatedData = $this->validateProjectUpdateData($request, $projectId, $userId);

            // Démarrer une transaction
            $this->pdo->beginTransaction();

            // Vérifier que le projet appartient à l'utilisateur
            $stmt = $this->pdo->prepare("SELECT id FROM design_projects WHERE id = ? AND user_id = ?");
            $stmt->execute([$projectId, $userId]);

            if (!$stmt->fetch()) {
                throw new Exception('Projet non trouvé ou accès non autorisé.');
            }

            // Mettre à jour le projet
            $stmt = $this->pdo->prepare("
                UPDATE design_projects
                SET title = ?,
                    description = ?,
                    project_type = ?,
                    category = ?,
                    status = ?,
                    software_used = ?,
                    updated_at = NOW()
                WHERE id = ? AND user_id = ?
            ");

            $result = $stmt->execute([
                $validatedData['title'],
                $validatedData['description'],
                $validatedData['project_type'],
                $validatedData['category'],
                $validatedData['status'],
                json_encode($validatedData['software_used']),
                $projectId,
                $userId
            ]);

            if (!$result) {
                throw new Exception('Erreur lors de la mise à jour du projet.');
            }

            // Traiter les nouveaux fichiers s'il y en a
            $uploadedFiles = [];
            if ($request->hasFile('files')) {
                $uploadedFiles = $this->handleFileUploads($request, $projectId);
            }

            // Valider la transaction
            $this->pdo->commit();

            return [
                'success' => true,
                'project_id' => $projectId,
                'uploaded_files' => $uploadedFiles,
                'message' => 'Projet mis à jour avec succès !'
            ];
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            Log::error('Erreur mise à jour projet: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Met à jour le statut d'un projet
     *
     * @param int $projectId
     * @param string $status
     * @param int $userId
     * @return bool
     */
    public function updateProjectStatus(int $projectId, string $status, int $userId): bool
    {
        if (!in_array($status, self::ALLOWED_STATUSES)) {
            return false;
        }

        $stmt = $this->pdo->prepare("
            UPDATE design_projects
            SET status = ?, updated_at = NOW()
            WHERE id = ? AND user_id = ?
        ");

        return $stmt->execute([$status, $projectId, $userId]);
    }

    /**
     * Supprime un fichier spécifique d'un projet
     *
     * @param int $fileId
     * @param int $projectId
     * @param int $userId
     * @return bool
     */
    public function removeProjectFile(int $fileId, int $projectId, int $userId): bool
    {
        try {
            $this->pdo->beginTransaction();

            // Vérifier que le fichier appartient au projet de l'utilisateur
            $stmt = $this->pdo->prepare("
                SELECT dpf.file_path
                FROM design_project_files dpf
                JOIN design_projects dp ON dpf.project_id = dp.id
                WHERE dpf.id = ? AND dpf.project_id = ? AND dp.user_id = ?
            ");
            $stmt->execute([$fileId, $projectId, $userId]);
            $file = $stmt->fetch();

            if (!$file) {
                $this->pdo->rollBack();
                return false;
            }

            // Supprimer le fichier de la base de données
            $stmt = $this->pdo->prepare("DELETE FROM design_project_files WHERE id = ?");
            $result = $stmt->execute([$fileId]);

            if ($result && $stmt->rowCount() > 0) {
                // Supprimer le fichier physique
                $filePath = public_path($file['file_path']);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                $this->pdo->commit();
                return true;
            }

            $this->pdo->rollBack();
            return false;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            Log::error('Erreur suppression fichier: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime un projet et ses fichiers associés
     *
     * @param int $projectId
     * @param int $userId
     * @return bool
     */
    public function deleteProject(int $projectId, int $userId): bool
    {
        try {
            $this->pdo->beginTransaction();

            // Récupérer les fichiers pour les supprimer physiquement
            $stmt = $this->pdo->prepare("
                SELECT file_path FROM design_project_files
                WHERE project_id = ?
            ");
            $stmt->execute([$projectId]);
            $files = $stmt->fetchAll();

            // Supprimer le projet (cascade supprimera les fichiers en base)
            $stmt = $this->pdo->prepare("
                DELETE FROM design_projects
                WHERE id = ? AND user_id = ?
            ");
            $result = $stmt->execute([$projectId, $userId]);

            if ($result && $stmt->rowCount() > 0) {
                // Supprimer les fichiers physiques
                foreach ($files as $file) {
                    $filePath = public_path($file['file_path']);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }

                $this->pdo->commit();
                return true;
            }

            $this->pdo->rollBack();
            return false;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            Log::error('Erreur suppression projet: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoie une notification email à l'admin lors de la création d'un projet réel
     *
     * @param int $projectId
     * @param int $userId
     * @param int $filesCount
     * @return void
     */
    private function sendAdminNotification(int $projectId, int $userId, int $filesCount): void
    {
        try {
            // Récupérer les informations du projet
            $project = DB::table('design_projects')->where('id', $projectId)->first();

            if (!$project) {
                Log::warning('Projet introuvable pour notification admin: ' . $projectId);
                return;
            }

            // Récupérer les informations de l'étudiant
            $student = DB::table('users')->where('id', $userId)->first();

            if (!$student) {
                Log::warning('Utilisateur introuvable pour notification admin: ' . $userId);
                return;
            }

            // Récupérer l'email de l'admin principal
            $adminEmail = env('ADMIN_EMAIL', 'admin@evc.com');

            // URL pour consulter le projet
            $viewUrl = url('/evc/app/admin/projects/real/' . $projectId);

            // Envoyer l'email à l'admin
            Mail::send('emails.admin-new-project-notification', [
                'project' => $project,
                'student' => $student,
                'filesCount' => $filesCount,
                'viewUrl' => $viewUrl
            ], function ($message) use ($adminEmail, $student) {
                $message->to($adminEmail)
                    ->subject('🚀 Nouveau Projet Réel soumis - ' . ($student->first_name ?? 'Étudiant') . ' ' . ($student->last_name ?? ''));
            });

            Log::info('Email de notification envoyé à l\'admin pour le projet: ' . $projectId);
        } catch (Exception $e) {
            // Ne pas bloquer la création du projet si l'email échoue
            Log::warning('Erreur lors de l\'envoi de l\'email de notification admin: ' . $e->getMessage());
        }
    }

    /**
     * Génère le message de succès approprié
     *
     * @param string $status
     * @return string
     */
    private function getSuccessMessage(string $status): string
    {
        return $status === 'draft'
            ? 'Projet sauvegardé en brouillon avec succès !'
            : 'Projet créé avec succès !';
    }

    /**
     * Récupère les options disponibles pour les formulaires
     *
     * @return array
     */
    public static function getFormOptions(): array
    {
        return [
            'project_types' => self::ALLOWED_PROJECT_TYPES,
            'software_options' => self::ALLOWED_SOFTWARE,
            'project_modes' => self::ALLOWED_PROJECT_MODES,
            'statuses' => self::ALLOWED_STATUSES
        ];
    }
}
