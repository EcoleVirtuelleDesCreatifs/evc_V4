<?php

namespace App\Services;

use App\Models\CVThequeProfile;
use App\Models\DocumentValidation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/**
 * Service Laravel pour la gestion de l'historique des documents CVThèque
 * Architecture propre et structurée
 */
class DocumentHistoryService
{
    /**
     * Types de documents supportés avec leurs configurations
     */
    private const DOCUMENT_TYPES = [
        'cv' => [
            'label' => 'CV',
            'icon' => 'fas fa-file-pdf',
            'color' => 'text-danger',
            'badge' => 'bg-danger',
            'path_field' => 'cv_file_path',
            'name_field' => 'cv_file_name'
        ],
        'motivation_letter' => [
            'label' => 'Lettre de motivation',
            'icon' => 'fas fa-file-alt',
            'color' => 'text-primary',
            'badge' => 'bg-primary',
            'path_field' => 'motivation_letter_path',
            'name_field' => 'motivation_letter_name'
        ],
        'pressbook' => [
            'label' => 'Pressbook',
            'icon' => 'fas fa-book',
            'color' => 'text-success',
            'badge' => 'bg-success',
            'path_field' => 'pressbook_file_path',
            'name_field' => 'pressbook_file_name'
        ],
        'rapport' => [
            'label' => 'Rapport',
            'icon' => 'fas fa-file-contract',
            'color' => 'text-warning',
            'badge' => 'bg-warning',
            'path_field' => 'report_file_path',
            'name_field' => 'report_file_name'
        ],
        'realisations' => [
            'label' => 'Réalisation',
            'icon' => 'fas fa-images',
            'color' => 'text-info',
            'badge' => 'bg-info',
            'path_field' => 'portfolio_files',
            'name_field' => 'portfolio_files'
        ]
    ];

    /**
     * Récupérer l'historique complet des documents d'un utilisateur avec statuts de validation
     */
    public function getUserDocumentHistory(int $userId): array
    {
        try {
            $profile = CVThequeProfile::where('user_id', $userId)->first();
            
            if (!$profile) {
                Log::info('Aucun profil trouvé pour l\'historique', ['user_id' => $userId]);
                return [];
            }

            // Récupérer tous les enregistrements de validation pour cet utilisateur
            $validations = DocumentValidation::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->keyBy(function ($item) {
                    return $item->document_type . '_' . $item->document_name;
                });

            $documents = [];

            // Traiter chaque type de document
            foreach (self::DOCUMENT_TYPES as $type => $config) {
                if ($type === 'realisations') {
                    $documents = array_merge($documents, $this->processPortfolioFiles($profile, $config, $validations));
                } else {
                    $document = $this->processSingleFile($profile, $type, $config, $validations);
                    if ($document) {
                        $documents[] = $document;
                    }
                }
            }

            // Trier par date de modification (plus récent en premier)
            usort($documents, function($a, $b) {
                return strtotime($b['uploaded_at']) - strtotime($a['uploaded_at']);
            });

            Log::info('Historique des documents récupéré avec statuts de validation', [
                'user_id' => $userId,
                'documents_count' => count($documents),
                'validations_count' => $validations->count()
            ]);

            return $documents;

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de l\'historique des documents', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [];
        }
    }

    /**
     * Traiter un fichier unique avec données de validation
     */
    private function processSingleFile(CVThequeProfile $profile, string $type, array $config, $validations = null): ?array
    {
        $pathField = $config['path_field'];
        $nameField = $config['name_field'];

        $filePath = $profile->$pathField;
        $fileName = $profile->$nameField;

        if (!$filePath || !$fileName) {
            return null;
        }

        // Vérifier l'existence du fichier
        $exists = Storage::disk('public')->exists($filePath);
        $fileSize = $exists ? $this->getFileSize($filePath) : 0;

        // Récupérer les données de validation
        $validationKey = $this->getValidationKey($type, $fileName);
        $validation = $validations ? $validations->get($validationKey) : null;
        $validationData = $this->getValidationData($validation);

        return [
            'type' => $config['label'],
            'name' => $fileName,
            'path' => $filePath,
            'size' => $this->formatFileSize($fileSize),
            'size_bytes' => $fileSize,
            'uploaded_at' => $profile->updated_at->format('Y-m-d H:i:s'),
            'exists' => $exists,
            'icon' => $config['icon'],
            'color' => $config['color'],
            'badge' => $config['badge'],
            'download_url' => $exists ? Storage::url($filePath) : null,
            'can_delete' => true,
            'document_type_key' => $type,
            // Données de validation
            'validation_status' => $validationData['status'],
            'validation_badge' => $validationData['badge'],
            'validation_comment' => $validationData['comment'],
            'validated_at' => $validationData['validated_at'],
            'validated_by' => $validationData['validated_by']
        ];
    }

    /**
     * Traiter les fichiers de portfolio (multiples) avec données de validation
     */
    private function processPortfolioFiles(CVThequeProfile $profile, array $config, $validations = null): array
    {
        $portfolioFiles = $profile->portfolio_files ?? [];
        $documents = [];

        foreach ($portfolioFiles as $file) {
            if (!isset($file['name']) || !isset($file['path'])) {
                continue;
            }

            $exists = Storage::disk('public')->exists($file['path']);
            $fileSize = $exists ? $this->getFileSize($file['path']) : 0;

            // Récupérer les données de validation pour cette réalisation
            $validationKey = $this->getValidationKey('realisations', $file['name']);
            $validation = $validations ? $validations->get($validationKey) : null;
            $validationData = $this->getValidationData($validation);

            $documents[] = [
                'type' => $config['label'],
                'name' => $file['name'],
                'path' => $file['path'],
                'size' => $this->formatFileSize($fileSize),
                'size_bytes' => $fileSize,
                'uploaded_at' => $profile->updated_at->format('Y-m-d H:i:s'),
                'exists' => $exists,
                'icon' => $config['icon'],
                'color' => $config['color'],
                'badge' => $config['badge'],
                'download_url' => $exists ? Storage::url($file['path']) : null,
                'can_delete' => true,
                'document_type_key' => 'realisations',
                // Données de validation
                'validation_status' => $validationData['status'],
                'validation_badge' => $validationData['badge'],
                'validation_comment' => $validationData['comment'],
                'validated_at' => $validationData['validated_at'],
                'validated_by' => $validationData['validated_by']
            ];
        }

        return $documents;
    }

    /**
     * Obtenir la taille d'un fichier en bytes
     */
    private function getFileSize(string $filePath): int
    {
        try {
            if (!Storage::disk('public')->exists($filePath)) {
                return 0;
            }

            return Storage::disk('public')->size($filePath);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de la taille du fichier', [
                'path' => $filePath,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Générer la clé de validation pour un document
     */
    private function getValidationKey(string $documentType, string $fileName): string
    {
        // Mapper les types de documents vers les types de validation
        $typeMapping = [
            'cv' => 'cv',
            'motivation_letter' => 'motivation',
            'pressbook' => 'pressbook',
            'rapport' => 'rapport',
            'realisations' => 'realisation'
        ];

        $validationType = $typeMapping[$documentType] ?? $documentType;
        return $validationType . '_' . $fileName;
    }

    /**
     * Obtenir les données de validation formatées
     */
    private function getValidationData($validation): array
    {
        if (!$validation) {
            return [
                'status' => 'en_cours',
                'badge' => [
                    'text' => 'En cours d\'analyse',
                    'class' => 'bg-warning text-dark',
                    'icon' => 'fas fa-clock'
                ],
                'comment' => null,
                'validated_at' => null,
                'validated_by' => null
            ];
        }

        return [
            'status' => $validation->status,
            'badge' => $validation->status_badge,
            'comment' => $validation->admin_comment,
            'validated_at' => $validation->validated_at ? $validation->validated_at->format('d/m/Y H:i') : null,
            'validated_by' => $validation->validator ? $validation->validator->name : null
        ];
    }

    /**
     * Formater la taille d'un fichier
     */
    private function formatFileSize(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' B';
        }
    }

    /**
     * Obtenir les statistiques de l'historique des documents
     */
    public function getDocumentStatistics(int $userId): array
    {
        $documents = $this->getUserDocumentHistory($userId);
        
        $stats = [
            'total_documents' => count($documents),
            'total_size' => 0,
            'types_count' => [],
            'recent_uploads' => 0
        ];

        $oneWeekAgo = Carbon::now()->subWeek();

        foreach ($documents as $document) {
            // Compter par type
            $type = $document['type'];
            $stats['types_count'][$type] = ($stats['types_count'][$type] ?? 0) + 1;

            // Uploads récents (dernière semaine)
            if (Carbon::parse($document['uploaded_at'])->gt($oneWeekAgo)) {
                $stats['recent_uploads']++;
            }
        }

        return $stats;
    }

    /**
     * Supprimer un document de l'historique
     */
    public function deleteDocument(int $userId, string $documentType, string $documentName): bool
    {
        try {
            $profile = CVThequeProfile::where('user_id', $userId)->first();
            
            if (!$profile) {
                return false;
            }

            // Logique de suppression selon le type
            switch ($documentType) {
                case 'CV':
                    return $this->deleteSingleFile($profile, 'cv_file_path', 'cv_file_name');
                    
                case 'Lettre de motivation':
                    return $this->deleteSingleFile($profile, 'motivation_letter_path', 'motivation_letter_name');
                    
                case 'Pressbook':
                    return $this->deleteSingleFile($profile, 'pressbook_file_path', 'pressbook_file_name');
                    
                case 'Rapport':
                    return $this->deleteSingleFile($profile, 'report_file_path', 'report_file_name');
                    
                case 'Réalisation':
                    return $this->deletePortfolioFile($profile, $documentName);
                    
                default:
                    return false;
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du document', [
                'user_id' => $userId,
                'type' => $documentType,
                'name' => $documentName,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Supprimer un fichier unique
     */
    private function deleteSingleFile(CVThequeProfile $profile, string $pathField, string $nameField): bool
    {
        $filePath = $profile->$pathField;
        
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        $profile->update([
            $pathField => null,
            $nameField => null
        ]);

        return true;
    }

    /**
     * Supprimer un fichier de portfolio
     */
    private function deletePortfolioFile(CVThequeProfile $profile, string $fileName): bool
    {
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
    }

    /**
     * Exporter l'historique des documents en CSV
     */
    public function exportToCSV(int $userId): string
    {
        $documents = $this->getUserDocumentHistory($userId);
        
        $csv = "Type,Nom,Taille,Date d'upload,Statut\n";
        
        foreach ($documents as $document) {
            $csv .= sprintf(
                '"%s","%s","%s","%s","%s"' . "\n",
                $document['type'],
                $document['name'],
                $document['size'],
                Carbon::parse($document['uploaded_at'])->format('d/m/Y H:i'),
                $document['exists'] ? 'Disponible' : 'Manquant'
            );
        }
        
        return $csv;
    }
}
