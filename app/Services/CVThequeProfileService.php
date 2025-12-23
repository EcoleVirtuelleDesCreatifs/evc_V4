<?php

namespace App\Services;

use App\Models\CVThequeProfile;
use App\Models\DocumentValidation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;

/**
 * Service Laravel pour la gestion des profils CVThèque
 * Utilise Eloquent ORM et les bonnes pratiques Laravel
 */
class CVThequeProfileService
{
    /**
     * Créer ou mettre à jour un profil CVThèque avec gestion des fichiers
     */
    public function createOrUpdateProfile(int $userId, array $data, array $files = []): array
    {
        try {
            // Debug: Log des données reçues
            Log::info('CVThequeProfileService - Données reçues:', [
                'user_id' => $userId,
                'data_keys' => array_keys($data),
                'data' => $data
            ]);

            // Préparer les données
            $preparedData = $this->prepareProfileData($data);

            // Debug: Log des données préparées
            Log::info('CVThequeProfileService - Données préparées:', [
                'prepared_data' => $preparedData
            ]);

            // Rechercher le profil existant ou créer un nouveau
            $profile = CVThequeProfile::updateOrCreate(
                ['user_id' => $userId],
                $preparedData
            );

            // Debug: Log du profil après sauvegarde
            Log::info('CVThequeProfileService - Profil après sauvegarde:', [
                'profile_id' => $profile->id,
                'professional_title' => $profile->professional_title,
                'summary' => $profile->summary,
                'experience_years' => $profile->experience_years
            ]);

            // Gérer les fichiers joints si fournis
            if (!empty($files)) {
                $this->handleFileUploads($profile, $files);
            }

            // Calculer et mettre à jour le score de complétion
            $completionScore = $this->calculateCompletionScore($profile);
            $profile->update(['profile_completion_score' => $completionScore]);

            // Recharger le modèle pour obtenir les données fraîches
            $profile->refresh();

            return [
                'success' => true,
                'message' => 'Profil mis à jour avec succès',
                'completion_score' => $completionScore,
                'profile' => $profile
            ];

        } catch (\Exception $e) {
            Log::error('Erreur lors de la sauvegarde du profil CVThèque', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Erreur lors de la sauvegarde du profil',
                'completion_score' => 0
            ];
        }
    }

    /**
     * Obtenir le profil CVThèque d'un utilisateur
     */
    public function getUserProfile(int $userId): ?CVThequeProfile
    {
        try {
            return CVThequeProfile::where('user_id', $userId)->first();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du profil CVThèque', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Obtenir ou créer un profil CVThèque avec des valeurs par défaut
     */
    public function getOrCreateUserProfile(int $userId): CVThequeProfile
    {
        $profile = $this->getUserProfile($userId);

        if (!$profile) {
            $profile = CVThequeProfile::create([
                'user_id' => $userId,
                'profile_completion_score' => 0
            ]);
        }

        return $profile;
    }

    /**
     * Calculer le score de complétion du profil
     */
    public function calculateCompletionScore(CVThequeProfile $profile): int
    {
        $fields = [
            'professional_title' => 15,        // 15 points
            'professional_summary' => 20,      // 20 points
            'years_experience' => 10,          // 10 points
            'software_skills' => 15,           // 15 points
            'professional_email' => 10,        // 10 points
            'linkedin_profile' => 10,          // 10 points
            'job_type' => 5,                   // 5 points
            'remote_work' => 5,                // 5 points (défini ou non)
            'willing_to_relocate' => 5,        // 5 points (défini ou non)
            'current_position' => 5            // 5 points
        ];

        $score = 0;

        foreach ($fields as $field => $points) {
            switch ($field) {
                case 'software_skills':
                    if (!empty($profile->software_skills) && count($profile->software_skills) > 0) {
                        $score += $points;
                    }
                    break;

                case 'remote_work':
                case 'willing_to_relocate':
                    // Ces champs booléens comptent toujours (même si false)
                    $score += $points;
                    break;

                case 'years_experience':
                    if ($profile->years_experience >= 0) {
                        $score += $points;
                    }
                    break;

                default:
                    if (!empty($profile->$field)) {
                        $score += $points;
                    }
                    break;
            }
        }

        return min(100, $score);
    }

    /**
     * Obtenir les statistiques du profil
     */
    public function getProfileStats(int $userId): array
    {
        $profile = $this->getUserProfile($userId);

        if (!$profile) {
            return [
                'completion_score' => 0,
                'last_updated' => null,
                'profile_visible' => false,
                'profile_public' => false,
                'allow_contact' => true,
                'is_complete' => false
            ];
        }

        return [
            'completion_score' => $profile->profile_completion_score,
            'last_updated' => $profile->last_updated_by_user,
            'profile_visible' => $profile->profile_visible,
            'profile_public' => $profile->profile_public,
            'allow_contact' => $profile->allow_contact,
            'is_complete' => $profile->isComplete()
        ];
    }

    /**
     * Supprimer un profil CVThèque
     */
    public function deleteProfile(int $userId): bool
    {
        try {
            $profile = CVThequeProfile::where('user_id', $userId)->first();

            if ($profile) {
                $profile->delete();
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du profil CVThèque', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Obtenir les profils publics pour la recherche
     */
    public function getPublicProfiles(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = CVThequeProfile::public()
            ->visible()
            ->contactable()
            ->with('user');

        // Appliquer les filtres
        if (!empty($filters['job_type'])) {
            $query->where('job_type', $filters['job_type']);
        }

        if (!empty($filters['years_experience_min'])) {
            $query->where('years_experience', '>=', $filters['years_experience_min']);
        }

        if (!empty($filters['years_experience_max'])) {
            $query->where('years_experience', '<=', $filters['years_experience_max']);
        }

        if (!empty($filters['software_skills'])) {
            $query->whereJsonContains('software_skills', $filters['software_skills']);
        }

        if (!empty($filters['remote_work'])) {
            $query->where('remote_work', true);
        }

        return $query->orderBy('profile_completion_score', 'desc')
                    ->orderBy('updated_at', 'desc')
                    ->get();
    }

    /**
     * Obtenir les options pour les logiciels
     */
    public function getSoftwareOptions(): array
    {
        return [
            'photoshop' => 'Adobe Photoshop',
            'illustrator' => 'Adobe Illustrator',
            'indesign' => 'Adobe InDesign',
            'figma' => 'Figma',
            'canva' => 'Canva',
            'after_effects' => 'Adobe After Effects',
            'premiere_pro' => 'Adobe Premiere Pro',
            'sketch' => 'Sketch',
            'xd' => 'Adobe XD',
            'blender' => 'Blender'
        ];
    }

    /**
     * Obtenir les options pour les compétences techniques
     */
    public function getSkillsOptions(): array
    {
        return [
            'ui_design' => 'UI Design',
            'ux_design' => 'UX Design',
            'web_design' => 'Web Design',
            'print_design' => 'Print Design',
            'branding' => 'Branding',
            'illustration' => 'Illustration',
            'animation' => 'Animation',
            'video_editing' => 'Montage Vidéo',
            'photography' => 'Photographie',
            'typography' => 'Typographie'
        ];
    }

    /**
     * Obtenir les options pour les langues
     */
    public function getLanguageOptions(): array
    {
        return [
            'francais' => 'Français',
            'anglais' => 'Anglais',
            'espagnol' => 'Espagnol',
            'allemand' => 'Allemand',
            'italien' => 'Italien',
            'portugais' => 'Portugais',
            'arabe' => 'Arabe',
            'chinois' => 'Chinois',
            'japonais' => 'Japonais'
        ];
    }

    /**
     * Préparer les données du profil pour la sauvegarde
     * Adapté à la structure réelle de la table cvtheque_profiles
     */
    private function prepareProfileData(array $data): array
    {
        // Nettoyer et valider les données
        $cleanData = [];

        // Champs texte simples (colonnes réelles de la table)
        if (isset($data['professional_title'])) {
            $cleanData['professional_title'] = trim($data['professional_title']) ?: null;
        }

        // Mapper professional_summary vers summary (nom réel de la colonne)
        if (isset($data['professional_summary'])) {
            $cleanData['summary'] = trim($data['professional_summary']) ?: null;
        } elseif (isset($data['summary'])) {
            $cleanData['summary'] = trim($data['summary']) ?: null;
        }

        // Mapper years_experience vers experience_years (nom réel de la colonne)
        if (isset($data['years_experience'])) {
            $cleanData['experience_years'] = max(0, (int) $data['years_experience']);
        } elseif (isset($data['experience_years'])) {
            $cleanData['experience_years'] = max(0, (int) $data['experience_years']);
        }

        // Champ availability
        if (isset($data['availability'])) {
            $cleanData['availability'] = trim($data['availability']) ?: null;
        }

        // Champ portfolio_url
        if (isset($data['portfolio_url'])) {
            $cleanData['portfolio_url'] = trim($data['portfolio_url']) ?: null;
        }

        // Champs array - skills (colonne réelle de la table)
        // Accepter software_skills, technical_skills, ou skills
        $skillsData = [];
        if (isset($data['software_skills']) && is_array($data['software_skills'])) {
            $skillsData = array_merge($skillsData, $data['software_skills']);
        }
        if (isset($data['technical_skills']) && is_array($data['technical_skills'])) {
            $skillsData = array_merge($skillsData, $data['technical_skills']);
        }
        if (isset($data['skills']) && is_array($data['skills'])) {
            $skillsData = array_merge($skillsData, $data['skills']);
        }

        if (!empty($skillsData)) {
            $cleanData['skills'] = array_values(array_unique(array_filter($skillsData)));
        }

        return $cleanData;
    }

    /**
     * Valider les données du profil
     */
    public function validateProfileData(array $data): array
    {
        $errors = [];

        // Validation de l'email professionnel
        if (!empty($data['professional_email']) && !filter_var($data['professional_email'], FILTER_VALIDATE_EMAIL)) {
            $errors['professional_email'] = 'L\'email professionnel n\'est pas valide';
        }

        // Validation des URLs
        $urlFields = ['professional_website', 'linkedin_profile', 'behance_profile', 'dribbble_profile'];
        foreach ($urlFields as $field) {
            if (!empty($data[$field]) && !filter_var($data[$field], FILTER_VALIDATE_URL)) {
                $errors[$field] = 'L\'URL n\'est pas valide';
            }
        }

        // Validation du numéro de téléphone
        if (!empty($data['professional_phone']) && !preg_match('/^[\d\s\+\-\(\)\.]{8,20}$/', $data['professional_phone'])) {
            $errors['professional_phone'] = 'Le numéro de téléphone n\'est pas valide';
        }

        return $errors;
    }

    /**
     * Gérer l'upload des fichiers joints avec système de validation
     */
    private function handleFileUploads(CVThequeProfile $profile, array $files): void
    {
        $userId = $profile->user_id;
        $updateData = [];

        // Gérer le CV
        if (isset($files['cv']) && $files['cv'] instanceof UploadedFile) {
            $cvData = $this->uploadSingleFile($files['cv'], $userId, 'cv');
            if ($cvData) {
                $updateData['cv_file_path'] = $cvData['path'];
                $updateData['cv_file_name'] = $cvData['name'];

                // Créer l'enregistrement de validation
                $this->createDocumentValidation($userId, 'cv', $cvData, $files['cv']);
            }
        }

        // Gérer la lettre de motivation
        if (isset($files['motivation_letter']) && $files['motivation_letter'] instanceof UploadedFile) {
            $motivationData = $this->uploadSingleFile($files['motivation_letter'], $userId, 'motivation');
            if ($motivationData) {
                $updateData['motivation_letter_path'] = $motivationData['path'];
                $updateData['motivation_letter_name'] = $motivationData['name'];

                // Créer l'enregistrement de validation
                $this->createDocumentValidation($userId, 'motivation', $motivationData, $files['motivation_letter']);
            }
        }

        // Gérer le pressbook
        if (isset($files['pressbook']) && $files['pressbook'] instanceof UploadedFile) {
            $pressbookData = $this->uploadSingleFile($files['pressbook'], $userId, 'pressbook');
            if ($pressbookData) {
                $updateData['pressbook_file_path'] = $pressbookData['path'];
                $updateData['pressbook_file_name'] = $pressbookData['name'];

                // Créer l'enregistrement de validation
                $this->createDocumentValidation($userId, 'pressbook', $pressbookData, $files['pressbook']);
            }
        }

        // Gérer le rapport
        if (isset($files['rapport']) && $files['rapport'] instanceof UploadedFile) {
            $reportData = $this->uploadSingleFile($files['rapport'], $userId, 'rapport');
            if ($reportData) {
                $updateData['report_file_path'] = $reportData['path'];
                $updateData['report_file_name'] = $reportData['name'];

                // Créer l'enregistrement de validation
                $this->createDocumentValidation($userId, 'rapport', $reportData, $files['rapport']);
            }
        }

        // Gérer les réalisations (fichiers multiples)
        if (isset($files['realisations']) && is_array($files['realisations'])) {
            Log::info("Processing realisations upload", [
                'user_id' => $userId,
                'files_count' => count($files['realisations'])
            ]);

            $portfolioFiles = $this->uploadMultipleFiles($files['realisations'], $userId, 'realisations');

            Log::info("Portfolio files processed", [
                'user_id' => $userId,
                'uploaded_files_count' => count($portfolioFiles),
                'files_data' => $portfolioFiles
            ]);

            if (!empty($portfolioFiles)) {
                $existingFiles = $profile->portfolio_files ?? [];
                $mergedFiles = array_merge($existingFiles, $portfolioFiles);
                $updateData['portfolio_files'] = $mergedFiles;

                Log::info("Updating portfolio files in database", [
                    'user_id' => $userId,
                    'existing_files_count' => count($existingFiles),
                    'new_files_count' => count($portfolioFiles),
                    'total_files_count' => count($mergedFiles)
                ]);

                // Créer les enregistrements de validation pour chaque réalisation
                foreach ($files['realisations'] as $index => $realisationFile) {
                    if (isset($portfolioFiles[$index])) {
                        $this->createDocumentValidation($userId, 'realisation', $portfolioFiles[$index], $realisationFile);
                    }
                }
            } else {
                Log::warning("No portfolio files were uploaded successfully", [
                    'user_id' => $userId,
                    'files_received' => count($files['realisations'])
                ]);
            }
        } else {
            Log::info("No realisations files to process", [
                'user_id' => $userId,
                'files_isset' => isset($files['realisations']),
                'files_is_array' => isset($files['realisations']) ? is_array($files['realisations']) : false
            ]);
        }

        // Mettre à jour le profil avec les nouveaux fichiers
        if (!empty($updateData)) {
            $profile->update($updateData);
        }
    }

    /**
     * Créer un enregistrement de validation pour un document uploadé
     */
    private function createDocumentValidation(int $userId, string $documentType, array $fileData, UploadedFile $uploadedFile): void
    {
        try {
            // Supprimer l'ancien enregistrement s'il existe (pour éviter les doublons)
            DocumentValidation::where('user_id', $userId)
                ->where('document_type', $documentType)
                ->where('document_name', $fileData['name'])
                ->delete();

            // Créer le nouvel enregistrement
            DocumentValidation::create([
                'user_id' => $userId,
                'document_type' => $documentType,
                'document_name' => $fileData['name'],
                'document_path' => $fileData['path'],
                'status' => DocumentValidation::STATUS_EN_COURS,
                'file_size' => $uploadedFile->getSize(),
                'mime_type' => $uploadedFile->getMimeType()
            ]);

            Log::info("Document validation record created", [
                'user_id' => $userId,
                'document_type' => $documentType,
                'document_name' => $fileData['name'],
                'status' => DocumentValidation::STATUS_EN_COURS
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to create document validation record", [
                'user_id' => $userId,
                'document_type' => $documentType,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Upload d'un fichier unique avec type de validation séparé
     */
    private function uploadSingleFileWithValidationType(UploadedFile $file, int $userId, string $storageType, string $validationType): ?array
    {
        try {
            Log::info("Starting single file upload with validation type", [
                'user_id' => $userId,
                'storage_type' => $storageType,
                'validation_type' => $validationType,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);

            // Valider le fichier avec le type de validation correct
            $isValid = $this->validateFile($file, $validationType);
            Log::info("File validation result", [
                'user_id' => $userId,
                'validation_type' => $validationType,
                'is_valid' => $isValid,
                'file_name' => $file->getClientOriginalName()
            ]);

            if (!$isValid) {
                Log::warning("File validation failed", [
                    'user_id' => $userId,
                    'validation_type' => $validationType,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'extension' => $file->getClientOriginalExtension()
                ]);
                return null;
            }

            // Générer un nom unique pour le stockage
            $extension = $file->getClientOriginalExtension();
            $fileName = $storageType . '_' . $userId . '_' . time() . '.' . $extension;

            // Définir le chemin de stockage
            $directory = 'cvtheque/' . $userId . '/' . $validationType;

            Log::info("Attempting to store file", [
                'user_id' => $userId,
                'storage_type' => $storageType,
                'validation_type' => $validationType,
                'directory' => $directory,
                'file_name' => $fileName
            ]);

            // Stocker le fichier
            $path = $file->storeAs($directory, $fileName, 'public');

            Log::info("File stored successfully", [
                'user_id' => $userId,
                'storage_type' => $storageType,
                'validation_type' => $validationType,
                'path' => $path,
                'original_name' => $file->getClientOriginalName()
            ]);

            return [
                'path' => $path,
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
                'url' => asset('storage/' . $path)
            ];

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'upload du fichier avec type de validation', [
                'user_id' => $userId,
                'storage_type' => $storageType,
                'validation_type' => $validationType,
                'file_name' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Upload d'un fichier unique
     */
    private function uploadSingleFile(UploadedFile $file, int $userId, string $type): ?array
    {
        try {
            Log::info("Starting single file upload", [
                'user_id' => $userId,
                'type' => $type,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);

            // Valider le fichier
            $isValid = $this->validateFile($file, $type);
            Log::info("File validation result", [
                'user_id' => $userId,
                'type' => $type,
                'is_valid' => $isValid,
                'file_name' => $file->getClientOriginalName()
            ]);

            if (!$isValid) {
                Log::warning("File validation failed", [
                    'user_id' => $userId,
                    'type' => $type,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'extension' => $file->getClientOriginalExtension()
                ]);
                return null;
            }

            // Supprimer l'ancien fichier s'il existe
            $this->deleteOldFile($userId, $type);

            $extension = $file->getClientOriginalExtension();

            if ($type === 'cv') {
                $directory = 'cv/' . $userId;
                $fileName = 'cv.' . $extension;
            } elseif ($type === 'rapport') {
                $directory = 'reports/' . $userId;
                $fileName = 'report.' . $extension;
            } else {
                // Par défaut: conserver l'existant (historique) pour les autres types
                $fileName = $type . '_' . $userId . '_' . time() . '.' . $extension;
                $directory = 'cvtheque/' . $userId . '/' . $type;
            }

            Log::info("Attempting to store file", [
                'user_id' => $userId,
                'type' => $type,
                'directory' => $directory,
                'file_name' => $fileName
            ]);

            // Stocker le fichier
            $path = $file->storeAs($directory, $fileName, 'public');

            Log::info("File stored successfully", [
                'user_id' => $userId,
                'type' => $type,
                'path' => $path,
                'original_name' => $file->getClientOriginalName()
            ]);

            return [
                'path' => $path,
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'type' => $file->getMimeType()
            ];

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'upload du fichier', [
                'user_id' => $userId,
                'type' => $type,
                'file_name' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Upload de fichiers multiples (pour les réalisations)
     */
    private function uploadMultipleFiles(array $files, int $userId, string $type): array
    {
        $uploadedFiles = [];

        Log::info("Starting multiple files upload", [
            'user_id' => $userId,
            'type' => $type,
            'files_count' => count($files)
        ]);

        foreach ($files as $index => $file) {
            Log::info("Processing file", [
                'user_id' => $userId,
                'index' => $index,
                'is_uploaded_file' => $file instanceof UploadedFile,
                'file_name' => $file instanceof UploadedFile ? $file->getClientOriginalName() : 'N/A',
                'file_size' => $file instanceof UploadedFile ? $file->getSize() : 'N/A'
            ]);

            if ($file instanceof UploadedFile) {
                // Utiliser un nom unique pour le stockage mais garder le type original pour la validation
                $uniqueType = $type . '_' . uniqid();
                $fileData = $this->uploadSingleFileWithValidationType($file, $userId, $uniqueType, $type);

                Log::info("Single file upload result", [
                    'user_id' => $userId,
                    'index' => $index,
                    'success' => $fileData !== null,
                    'file_data' => $fileData
                ]);

                if ($fileData) {
                    $uploadedFiles[] = $fileData;
                } else {
                    Log::warning("Failed to upload file", [
                        'user_id' => $userId,
                        'index' => $index,
                        'file_name' => $file->getClientOriginalName()
                    ]);
                }
            } else {
                Log::warning("File is not an UploadedFile instance", [
                    'user_id' => $userId,
                    'index' => $index,
                    'file_type' => gettype($file)
                ]);
            }
        }

        Log::info("Multiple files upload completed", [
            'user_id' => $userId,
            'type' => $type,
            'total_files' => count($files),
            'successful_uploads' => count($uploadedFiles)
        ]);

        return $uploadedFiles;
    }

    /**
     * Valider un fichier selon son type
     */
    private function validateFile(UploadedFile $file, string $type): bool
    {
        $maxSizes = [
            'cv' => 5 * 1024 * 1024,           // 5MB
            'motivation' => 5 * 1024 * 1024,   // 5MB
            'pressbook' => 20 * 1024 * 1024,   // 20MB
            'rapport' => 10 * 1024 * 1024,     // 10MB
            'realisations' => 10 * 1024 * 1024 // 10MB par fichier
        ];

        $allowedExtensions = [
            'cv' => ['pdf', 'doc', 'docx'],
            'motivation' => ['pdf', 'doc', 'docx'],
            'pressbook' => ['pdf'],
            'rapport' => ['pdf', 'doc', 'docx'],
            'realisations' => ['jpg', 'jpeg', 'png', 'pdf', 'ai', 'psd']
        ];

        // Vérifier la taille
        $maxSize = $maxSizes[$type] ?? 5 * 1024 * 1024;
        if ($file->getSize() > $maxSize) {
            return false;
        }

        // Vérifier l'extension
        $extension = strtolower($file->getClientOriginalExtension());
        $allowed = $allowedExtensions[$type] ?? [];

        return in_array($extension, $allowed);
    }

    /**
     * Supprimer l'ancien fichier
     */
    private function deleteOldFile(int $userId, string $type): void
    {
        try {
            $profile = $this->getUserProfile($userId);
            if (!$profile) return;

            $pathField = $type . '_file_path';
            if ($type === 'motivation') {
                $pathField = 'motivation_letter_path';
            } elseif ($type === 'rapport') {
                $pathField = 'report_file_path';
            }

            // Pour CV et Rapport en mode écrasable: supprimer l'ancien chemin stocké en DB
            $oldPath = $profile->$pathField;
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de l\'ancien fichier', [
                'user_id' => $userId,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Supprimer un fichier de réalisation spécifique
     */
    public function deletePortfolioFile(int $userId, string $fileName): bool
    {
        try {
            $profile = $this->getUserProfile($userId);
            if (!$profile) return false;

            $portfolioFiles = $profile->portfolio_files ?? [];
            $updatedFiles = [];
            $fileDeleted = false;

            foreach ($portfolioFiles as $file) {
                if ($file['name'] !== $fileName) {
                    $updatedFiles[] = $file;
                } else {
                    // Supprimer le fichier du stockage
                    if (isset($file['path']) && Storage::disk('public')->exists($file['path'])) {
                        Storage::disk('public')->delete($file['path']);
                    }
                    $fileDeleted = true;
                }
            }

            if ($fileDeleted) {
                $profile->update(['portfolio_files' => $updatedFiles]);
            }

            return $fileDeleted;

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du fichier de portfolio', [
                'user_id' => $userId,
                'file_name' => $fileName,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Obtenir les informations des fichiers d'un profil
     */
    public function getProfileFiles(int $userId): array
    {
        try {
            $profile = $this->getUserProfile($userId);
            if (!$profile) return [];

            return [
                'cv' => [
                    'path' => $profile->cv_file_path,
                    'name' => $profile->cv_file_name,
                    'exists' => $profile->cv_file_path && Storage::disk('public')->exists($profile->cv_file_path)
                ],
                'motivation_letter' => [
                    'path' => $profile->motivation_letter_path,
                    'name' => $profile->motivation_letter_name,
                    'exists' => $profile->motivation_letter_path && Storage::disk('public')->exists($profile->motivation_letter_path)
                ],
                'pressbook' => [
                    'path' => $profile->pressbook_file_path,
                    'name' => $profile->pressbook_file_name,
                    'exists' => $profile->pressbook_file_path && Storage::disk('public')->exists($profile->pressbook_file_path)
                ],
                'rapport' => [
                    'path' => $profile->report_file_path,
                    'name' => $profile->report_file_name,
                    'exists' => $profile->report_file_path && Storage::disk('public')->exists($profile->report_file_path)
                ],
                'realisations' => $profile->portfolio_files ?? []
            ];

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des fichiers du profil', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }
}
