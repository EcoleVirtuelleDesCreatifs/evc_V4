<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\CVThequeProfileService;
use App\Models\CVThequeProfile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CVThequeController extends Controller
{
    /**
     * Afficher la page principale de la CVThèque
     */
    public function index(): View|RedirectResponse
    {
        if (!$this->isAuthenticated()) {
            return redirect()->route('login');
        }

        try {
            $userId = (int) session('user_id');
            $profileService = new CVThequeProfileService();

            // Récupérer les informations utilisateur et profil CVThèque
            $userInfo = $this->getUserInfo($userId);
            $cvthequeProfile = $profileService->getOrCreateUserProfile($userId);
            $completionScore = $profileService->calculateCompletionScore($cvthequeProfile);

            return view('cvtheque.index', [
                'userInfo' => $userInfo,
                'cvthequeProfile' => $cvthequeProfile,
                'completionScore' => $completionScore,
                'softwareOptions' => $profileService->getSoftwareOptions(),
                'skillsOptions' => $profileService->getSkillsOptions(),
                'languageOptions' => $profileService->getLanguageOptions()
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur CVThèque index: ' . $e->getMessage());
            return view('cvtheque.index', [
                'userInfo' => $this->getFallbackUserInfo(),
                'cvthequeProfile' => $this->getFallbackCVThequeProfile(),
                'completionScore' => 0,
                'softwareOptions' => [],
                'skillsOptions' => [],
                'languageOptions' => []
            ]);
        }
    }

    /**
     * Mettre à jour le profil CVThèque avec gestion des fichiers
     */
    public function updateProfile(Request $request): JsonResponse
    {
        if (!$this->isAuthenticated()) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        try {
            $userId = (int) session('user_id');

            // Validation des données du formulaire
            $validatedData = $request->validate([
                'professional_title' => 'nullable|string|max:255',
                'professional_summary' => 'nullable|string|max:1000',
                'years_experience' => 'nullable|integer|min:0|max:50',
                'current_position' => 'nullable|string|max:255',
                'current_company' => 'nullable|string|max:255',
                'software_skills' => 'nullable|array',
                'technical_skills' => 'nullable|array',
                'languages' => 'nullable|array',
                'professional_email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'website' => 'nullable|url|max:255',
                'linkedin_profile' => 'nullable|url|max:255',
                'behance_profile' => 'nullable|url|max:255',
                'dribbble_profile' => 'nullable|url|max:255',
                'job_type' => 'nullable|in:freelance,cdi,cdd,stage,alternance',
                'salary_expectation' => 'nullable|string|max:100',
                'availability' => 'nullable|string|max:100',
                'remote_work' => 'boolean',
                'willing_to_relocate' => 'boolean',
                'profile_visible' => 'boolean',
                'allow_contact' => 'boolean'
            ]);

            // Validation des fichiers
            $fileValidation = $request->validate([
                'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB
                'motivation_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB
                'pressbook_file' => 'nullable|file|mimes:pdf|max:20480', // 20MB
                'rapport_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB
                'realisations_files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,ai,psd|max:10240' // 10MB par fichier
            ]);

            // Debug: Log des fichiers reçus
            Log::info('Fichiers reçus dans la requête:', [
                'cv_file' => $request->hasFile('cv_file'),
                'motivation_file' => $request->hasFile('motivation_file'),
                'pressbook_file' => $request->hasFile('pressbook_file'),
                'rapport_file' => $request->hasFile('rapport_file'),
                'realisations_files' => $request->hasFile('realisations_files'),
                'all_files' => $request->allFiles()
            ]);

            // Préparer les fichiers pour le service
            $files = [];
            if ($request->hasFile('cv_file')) {
                $files['cv'] = $request->file('cv_file');
                Log::info('CV file détecté:', ['name' => $request->file('cv_file')->getClientOriginalName()]);
            }
            if ($request->hasFile('motivation_file')) {
                $files['motivation_letter'] = $request->file('motivation_file');
                Log::info('Motivation file détecté:', ['name' => $request->file('motivation_file')->getClientOriginalName()]);
            }
            if ($request->hasFile('pressbook_file')) {
                $files['pressbook'] = $request->file('pressbook_file');
                Log::info('Pressbook file détecté:', ['name' => $request->file('pressbook_file')->getClientOriginalName()]);
            }
            if ($request->hasFile('rapport_file')) {
                $files['rapport'] = $request->file('rapport_file');
                Log::info('Rapport file détecté:', ['name' => $request->file('rapport_file')->getClientOriginalName()]);
            }
            if ($request->hasFile('realisations_files')) {
                $files['realisations'] = $request->file('realisations_files');
                Log::info('Realisations files détectés:', ['count' => count($request->file('realisations_files'))]);
            }

            // Debug: Log des données validées
            Log::info('Données validées reçues:', [
                'user_id' => $userId,
                'validated_data' => $validatedData,
                'has_professional_title' => isset($validatedData['professional_title']),
                'has_professional_summary' => isset($validatedData['professional_summary']),
                'has_years_experience' => isset($validatedData['years_experience'])
            ]);

            // Utiliser le Service Laravel pour enregistrer les données et fichiers
            try {
                $profileService = new CVThequeProfileService();
                Log::info('Appel du service avec fichiers:', ['files_count' => count($files)]);

                $result = $profileService->createOrUpdateProfile($userId, $validatedData, $files);

                Log::info('Résultat du service:', ['success' => $result['success'], 'message' => $result['message'] ?? 'N/A']);

                if ($result['success']) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Profil mis à jour avec succès',
                        'completion_score' => $result['completion_score'],
                        'files' => $result['files'] ?? []
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => $result['message'] ?? 'Erreur lors de la mise à jour du profil'
                    ], 422);
                }
            } catch (\Exception $e) {
                Log::error('Erreur dans updateProfile:', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Erreur serveur lors de la mise à jour du profil: ' . $e->getMessage()
                ], 500);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur de validation dans updateProfile:', [
                'errors' => $e->errors(),
                'input' => $request->except(['cv_file', 'motivation_file', 'pressbook_file', 'rapport_file', 'realisations_files']),
                'files' => array_keys($request->allFiles())
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation: ' . implode(', ', array_map(function($errors) {
                    return implode(', ', $errors);
                }, $e->errors())),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erreur updateProfile CVThèque: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du profil'
            ], 500);
        }
    }

    /**
     * Upload CV
     */
    public function uploadCV(Request $request): JsonResponse
    {
        if (!$this->isAuthenticated()) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        try {
            $request->validate([
                'cv_file' => 'required|file|mimes:pdf,doc,docx|max:5120' // 5MB
            ]);

            $userId = (int) session('user_id');
            $profileService = new CVThequeProfileService();

            $files = ['cv' => $request->file('cv_file')];
            $result = $profileService->createOrUpdateProfile($userId, [], $files);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'CV uploadé avec succès',
                    'file_info' => $result['files']['cv'] ?? null
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Erreur lors de l\'upload du CV'
                ], 422);
            }
        } catch (\Exception $e) {
            Log::error('Erreur upload CV: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload du CV: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload lettre de motivation
     */
    public function uploadMotivation(Request $request): JsonResponse
    {
        if (!$this->isAuthenticated()) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        try {
            $request->validate([
                'motivation_file' => 'required|file|mimes:pdf,doc,docx|max:5120' // 5MB
            ]);

            $userId = (int) session('user_id');
            $profileService = new CVThequeProfileService();

            $files = ['motivation_letter' => $request->file('motivation_file')];
            $result = $profileService->createOrUpdateProfile($userId, [], $files);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Lettre de motivation uploadée avec succès',
                    'file_info' => $result['files']['motivation_letter'] ?? null
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Erreur lors de l\'upload de la lettre de motivation'
                ], 422);
            }
        } catch (\Exception $e) {
            Log::error('Erreur upload motivation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload de la lettre de motivation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload réalisations
     */
    public function uploadRealisations(Request $request): JsonResponse
    {
        if (!$this->isAuthenticated()) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        try {
            $request->validate([
                'realisations_files' => 'required|array',
                'realisations_files.*' => 'file|mimes:jpg,jpeg,png,pdf,ai,psd|max:10240' // 10MB par fichier
            ]);

            $userId = (int) session('user_id');
            $profileService = new CVThequeProfileService();

            $files = ['realisations' => $request->file('realisations_files')];
            $result = $profileService->createOrUpdateProfile($userId, [], $files);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Réalisations uploadées avec succès',
                    'file_info' => $result['files']['realisations'] ?? null
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Erreur lors de l\'upload des réalisations'
                ], 422);
            }
        } catch (\Exception $e) {
            Log::error('Erreur upload réalisations: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload des réalisations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload pressbook
     */
    public function uploadPressbook(Request $request): JsonResponse
    {
        if (!$this->isAuthenticated()) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        try {
            $request->validate([
                'pressbook_file' => 'required|file|mimes:pdf|max:20480' // 20MB
            ]);

            $userId = (int) session('user_id');
            $profileService = new CVThequeProfileService();

            $files = ['pressbook' => $request->file('pressbook_file')];
            $result = $profileService->createOrUpdateProfile($userId, [], $files);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pressbook uploadé avec succès',
                    'file_info' => $result['files']['pressbook'] ?? null
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Erreur lors de l\'upload du pressbook'
                ], 422);
            }
        } catch (\Exception $e) {
            Log::error('Erreur upload pressbook: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload du pressbook: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload rapport de fin de formation
     */
    public function uploadRapport(Request $request): JsonResponse
    {
        if (!$this->isAuthenticated()) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        try {
            $request->validate([
                'rapport_file' => 'required|file|mimes:pdf,doc,docx|max:10240' // 10MB
            ]);

            $userId = (int) session('user_id');
            $profileService = new CVThequeProfileService();

            $files = ['rapport' => $request->file('rapport_file')];
            $result = $profileService->createOrUpdateProfile($userId, [], $files);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Rapport de fin de formation uploadé avec succès',
                    'file_info' => $result['files']['rapport'] ?? null
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Erreur lors de l\'upload du rapport'
                ], 422);
            }
        } catch (\Exception $e) {
            Log::error('Erreur upload rapport: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload du rapport: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher le profil CVThèque - Nouvelle page interactive
     */
    public function monProfil(): View|RedirectResponse
    {
        if (!$this->isAuthenticated()) {
            return redirect()->route('login');
        }

        try {
            $userId = (int) session('user_id');
            $profileService = new CVThequeProfileService();

            // Récupérer les informations utilisateur et profil CVThèque
            $userInfo = $this->getUserInfo($userId);
            $cvthequeProfile = $profileService->getOrCreateUserProfile($userId);
            $completionScore = $profileService->calculateCompletionScore($cvthequeProfile);

            // Préparer la liste des documents
            $documents = [
                [
                    'name' => 'CV',
                    'available' => !empty($cvthequeProfile->cv_file_path),
                    'url' => $cvthequeProfile->cv_file_path ? \App\Models\MediaUrl::fromPath($cvthequeProfile->cv_file_path) : null
                ],
                [
                    'name' => 'Lettre de motivation',
                    'available' => !empty($cvthequeProfile->motivation_letter_path),
                    'url' => $cvthequeProfile->motivation_letter_path ? \App\Models\MediaUrl::fromPath($cvthequeProfile->motivation_letter_path) : null
                ],
                [
                    'name' => 'Pressbook',
                    'available' => !empty($cvthequeProfile->pressbook_file_path),
                    'url' => $cvthequeProfile->pressbook_file_path ? \App\Models\MediaUrl::fromPath($cvthequeProfile->pressbook_file_path) : null
                ],
                [
                    'name' => 'Rapport de formation',
                    'available' => !empty($cvthequeProfile->report_file_path),
                    'url' => $cvthequeProfile->report_file_path ? \App\Models\MediaUrl::fromPath($cvthequeProfile->report_file_path) : null
                ]
            ];

            return view('cvtheque.mon-profil', [
                'userInfo' => $userInfo,
                'cvthequeProfile' => $cvthequeProfile,
                'completionScore' => $completionScore,
                'documents' => $documents,
                'softwareOptions' => $profileService->getSoftwareOptions(),
                'skillsOptions' => $profileService->getSkillsOptions(),
                'languageOptions' => $profileService->getLanguageOptions()
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur CVThèque monProfil: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            // Déterminer la route de retour selon la formation
            $formation = session('user_formation_raw', 'design-graphique');
            $returnRoute = $formation . '.cvtheque.index';

            return redirect()->route($returnRoute)
                ->with('error', 'Erreur lors de l\'affichage du profil: ' . $e->getMessage());
        }
    }

    /**
     * Afficher le profil CVThèque de manière structurée
     */
    public function profileDisplay(): View|RedirectResponse
    {
        if (!$this->isAuthenticated()) {
            return redirect()->route('login');
        }

        try {
            $userId = (int) session('user_id');
            $profileService = new CVThequeProfileService();

            // Récupérer les informations utilisateur et profil CVThèque
            $userInfo = $this->getUserInfo($userId);
            $cvthequeProfile = $profileService->getOrCreateUserProfile($userId);
            $completionScore = $profileService->calculateCompletionScore($cvthequeProfile);

            // Préparer la liste des documents
            $documents = [
                [
                    'name' => 'CV',
                    'available' => !empty($cvthequeProfile->cv_file_path),
                    'url' => $cvthequeProfile->cv_file_path ? \App\Models\MediaUrl::fromPath($cvthequeProfile->cv_file_path) : null
                ],
                [
                    'name' => 'Lettre de motivation',
                    'available' => !empty($cvthequeProfile->motivation_letter_path),
                    'url' => $cvthequeProfile->motivation_letter_path ? \App\Models\MediaUrl::fromPath($cvthequeProfile->motivation_letter_path) : null
                ],
                [
                    'name' => 'Pressbook',
                    'available' => !empty($cvthequeProfile->pressbook_file_path),
                    'url' => $cvthequeProfile->pressbook_file_path ? \App\Models\MediaUrl::fromPath($cvthequeProfile->pressbook_file_path) : null
                ],
                [
                    'name' => 'Rapport de formation',
                    'available' => !empty($cvthequeProfile->report_file_path),
                    'url' => $cvthequeProfile->report_file_path ? \App\Models\MediaUrl::fromPath($cvthequeProfile->report_file_path) : null
                ]
            ];

            return view('cvtheque.profile-display', [
                'userInfo' => $userInfo,
                'cvthequeProfile' => $cvthequeProfile,
                'completionScore' => $completionScore,
                'documents' => $documents,
                'softwareOptions' => $profileService->getSoftwareOptions(),
                'skillsOptions' => $profileService->getSkillsOptions(),
                'languageOptions' => $profileService->getLanguageOptions()
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur CVThèque profileDisplay: ' . $e->getMessage());
            return redirect()->route('design-graphique.cvtheque.index')
                ->with('error', 'Erreur lors de l\'affichage du profil');
        }
    }

    /**
     * Prévisualiser le profil CVThèque complet
     */
    public function preview(): View|RedirectResponse
    {
        if (!$this->isAuthenticated()) {
            return redirect()->route('login');
        }

        try {
            $userId = (int) session('user_id');
            $userInfo = $this->getUserInfo($userId);

            // Récupérer le profil CVThèque complet
            $profileService = new CVThequeProfileService();
            $profile = $profileService->getUserProfile($userId);

            // Récupérer l'historique des documents avec validation
            $documentHistoryService = new \App\Services\DocumentHistoryService();
            $documentsHistory = $documentHistoryService->getUserDocumentHistory($userId);
            $documentStats = $documentHistoryService->getDocumentStatistics($userId);

            // Si pas de profil, créer un profil vide pour l'affichage
            if (!$profile) {
                $profile = (object) [
                    'professional_title' => null,
                    'professional_summary' => null,
                    'years_experience' => 0,
                    'current_position' => null,
                    'current_company' => null,
                    'software_skills' => [],
                    'technical_skills' => [],
                    'languages' => [],
                    'professional_email' => null,
                    'professional_phone' => null,
                    'professional_website' => null,
                    'linkedin_profile' => null,
                    'behance_profile' => null,
                    'dribbble_profile' => null,
                    'instagram_profile' => null,
                    'job_type' => 'Tout',
                    'salary_expectation' => null,
                    'availability_date' => null,
                    'remote_work' => false,
                    'willing_to_relocate' => false,
                    'preferred_locations' => [],
                    'certifications' => [],
                    'formations_completed' => [],
                    'profile_completion_score' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            return view('cvtheque.preview', compact(
                'userInfo',
                'profile',
                'documentsHistory',
                'documentStats'
            ));

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du profil pour prévisualisation', [
                'user_id' => session('user_id'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Retourner une vue avec des données vides en cas d'erreur
            return view('cvtheque.preview', [
                'userInfo' => (object) ['name' => 'Utilisateur', 'email' => ''],
                'profile' => (object) ['profile_completion_score' => 0],
                'documentsHistory' => [],
                'documentStats' => ['total_documents' => 0]
            ]);
        }
    }

    /**
     * Afficher l'historique des documents uploadés
     * Architecture propre avec service dédié
     */
    public function historique(): View|RedirectResponse
    {
        if (!$this->isAuthenticated()) {
            return redirect()->route('login');
        }

        try {
            $userId = (int) session('user_id');
            $userInfo = $this->getUserInfo($userId);

            // Utiliser le service dédié pour l'historique
            $documentHistoryService = new \App\Services\DocumentHistoryService();
            $documentsHistory = $documentHistoryService->getUserDocumentHistory($userId);
            $documentStats = $documentHistoryService->getDocumentStatistics($userId);

            // Récupérer le profil pour les informations générales
            $profileService = new CVThequeProfileService();
            $profile = $profileService->getUserProfile($userId) ?? (object) [
                'profile_completion_score' => 0,
                'updated_at' => now()
            ];

            return view('cvtheque.historique', compact(
                'userInfo',
                'documentsHistory',
                'profile',
                'documentStats'
            ));

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de l\'historique des documents', [
                'user_id' => session('user_id'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Retourner une vue avec des données vides en cas d'erreur
            return view('cvtheque.historique', [
                'userInfo' => (object) ['name' => 'Utilisateur', 'email' => ''],
                'documentsHistory' => [],
                'profile' => (object) ['profile_completion_score' => 0, 'updated_at' => now()],
                'documentStats' => ['total_documents' => 0, 'recent_uploads' => 0, 'types_count' => []]
            ]);
        }
    }

    /**
     * Supprimer un fichier spécifique du profil
     */
    public function deleteFile(Request $request): JsonResponse
    {
        if (!$this->isAuthenticated()) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        try {
            $userId = (int) session('user_id');
            $fileType = $request->input('file_type');
            $fileName = $request->input('file_name');

            $profileService = new CVThequeProfileService();

            if ($fileType === 'portfolio' && $fileName) {
                $result = $profileService->deletePortfolioFile($userId, $fileName);
            } else {
                // Pour les autres types de fichiers, on peut étendre la logique
                $result = false;
            }

            return response()->json([
                'success' => $result,
                'message' => $result ? 'Fichier supprimé avec succès' : 'Erreur lors de la suppression'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur suppression fichier CVThèque: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du fichier'
            ], 500);
        }
    }

    /**
     * Supprimer un document de l'historique
     * API structurée avec service dédié
     */
    public function deleteDocument(Request $request): JsonResponse
    {
        if (!$this->isAuthenticated()) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        try {
            $userId = (int) session('user_id');
            $documentType = $request->input('type');
            $documentName = $request->input('name');

            if (!$documentType || !$documentName) {
                return response()->json([
                    'success' => false,
                    'message' => 'Type de document et nom requis'
                ], 400);
            }

            $documentHistoryService = new \App\Services\DocumentHistoryService();
            $success = $documentHistoryService->deleteDocument($userId, $documentType, $documentName);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Document supprimé avec succès'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer le document'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du document', [
                'user_id' => session('user_id'),
                'type' => $request->input('type'),
                'name' => $request->input('name'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur lors de la suppression'
            ], 500);
        }
    }

    /**
     * Exporter l'historique des documents en CSV
     * API structurée
     */
    public function exportDocuments(): \Symfony\Component\HttpFoundation\Response
    {
        if (!$this->isAuthenticated()) {
            return redirect()->route('login');
        }

        try {
            $userId = (int) session('user_id');
            $documentHistoryService = new \App\Services\DocumentHistoryService();
            $csvContent = $documentHistoryService->exportToCSV($userId);

            $fileName = 'historique_documents_' . date('Y-m-d_H-i-s') . '.csv';

            return response($csvContent)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'export des documents', [
                'user_id' => session('user_id'),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Erreur lors de l\'export des documents');
        }
    }

    /**
     * Récupérer les informations de base de l'utilisateur
     */
    private function getUserInfo(int $userId): object
    {
        // Récupérer depuis la table students car c'est là que sont stockées les infos complètes
        $user = DB::table('students')
            ->select('id', 'user_id', 'first_name', 'last_name', 'email', 'phone', 'whatsapp',
                    'country', 'city', 'quartier as district', 'profile_photo', 'Level_education as education_level',
                    'degree as last_diploma', 'biography', 'level as current_level', 'program')
            ->where('user_id', $userId)
            ->first();

        return $user ?: $this->getFallbackUserInfo();
    }

    /**
     * Récupérer le profil CVThèque de l'utilisateur
     */
    private function getCVThequeProfile(int $userId): object
    {
        $profile = DB::table('cvtheque_profiles')
            ->where('user_id', $userId)
            ->first();

        if ($profile) {
            // Décoder les champs JSON
            $jsonFields = ['software_skills', 'technical_skills', 'languages', 'preferred_locations'];
            foreach ($jsonFields as $field) {
                if ($profile->$field) {
                    $profile->$field = json_decode($profile->$field, true);
                }
            }
        }

        return $profile ?: $this->getFallbackCVThequeProfile();
    }

    /**
     * Calculer le score de completion du profil
     */
    private function calculateProfileCompletion(object $userInfo, object $cvthequeProfile): int
    {
        $totalFields = 0;
        $completedFields = 0;

        // Champs de base utilisateur (poids: 40%)
        $userFields = [
            'first_name', 'last_name', 'email', 'phone', 'country',
            'city', 'profile_photo', 'education_level', 'biography'
        ];

        foreach ($userFields as $field) {
            $totalFields++;
            if (!empty($userInfo->$field)) {
                $completedFields++;
            }
        }

        // Champs CVThèque (poids: 60%)
        $cvthequeFields = [
            'professional_title', 'professional_summary', 'years_experience',
            'software_skills', 'technical_skills', 'professional_email',
            'job_type', 'availability_date'
        ];

        foreach ($cvthequeFields as $field) {
            $totalFields++;
            if (!empty($cvthequeProfile->$field)) {
                $completedFields++;
            }
        }

        return $totalFields > 0 ? round(($completedFields / $totalFields) * 100) : 0;
    }

    /**
     * Options pour les logiciels
     */
    private function getSoftwareOptions(): array
    {
        return [
            'photoshop' => 'Adobe Photoshop',
            'illustrator' => 'Adobe Illustrator',
            'indesign' => 'Adobe InDesign',
            'after_effects' => 'Adobe After Effects',
            'premiere_pro' => 'Adobe Premiere Pro',
            'xd' => 'Adobe XD',
            'figma' => 'Figma',
            'sketch' => 'Sketch',
            'canva' => 'Canva',
            'procreate' => 'Procreate',
            'blender' => 'Blender',
            'cinema4d' => 'Cinema 4D'
        ];
    }

    /**
     * Options pour les compétences techniques
     */
    private function getSkillsOptions(): array
    {
        return [
            'web_design' => 'Web Design',
            'print_design' => 'Print Design',
            'branding' => 'Branding',
            'logo_design' => 'Logo Design',
            'packaging' => 'Packaging',
            'illustration' => 'Illustration',
            'motion_graphics' => 'Motion Graphics',
            'ui_ux' => 'UI/UX Design',
            'photography' => 'Photographie',
            'video_editing' => 'Montage Vidéo',
            'social_media' => 'Social Media Design',
            'typography' => 'Typographie'
        ];
    }

    /**
     * Options pour les langues
     */
    private function getLanguageOptions(): array
    {
        return [
            'français' => 'Français',
            'english' => 'Anglais',
            'español' => 'Espagnol',
            'deutsch' => 'Allemand',
            'italiano' => 'Italien',
            'português' => 'Portugais',
            'العربية' => 'Arabe'
        ];
    }

    /**
     * Données de fallback pour l'utilisateur
     */
    private function getFallbackUserInfo(): object
    {
        return (object) [
            'id' => session('user_id', 0),
            'first_name' => session('user_prenom', ''),
            'last_name' => session('user_nom', 'Utilisateur'),
            'email' => session('user_email', ''),
            'phone' => session('user_telephone', ''),
            'whatsapp' => session('user_whatsapp', ''),
            'country' => session('user_pays', ''),
            'city' => session('user_ville', ''),
            'district' => '',
            'profile_photo' => session('user_photo', ''),
            'education_level' => session('user_niveau', ''),
            'last_diploma' => '',
            'biography' => '',
            'current_level' => 'Débutant'
        ];
    }

    /**
     * Données de fallback pour le profil CVThèque
     */
    private function getFallbackCVThequeProfile(): object
    {
        return (object) [
            'professional_title' => '',
            'professional_summary' => '',
            'years_experience' => 0,
            'current_position' => '',
            'current_company' => '',
            'software_skills' => [],
            'technical_skills' => [],
            'languages' => [],
            'professional_email' => '',
            'professional_phone' => '',
            'professional_website' => '',
            'linkedin_profile' => '',
            'behance_profile' => '',
            'dribbble_profile' => '',
            'instagram_profile' => '',
            'job_type' => 'Tout',
            'salary_expectation' => '',
            'availability_date' => '',
            'remote_work' => false,
            'willing_to_relocate' => false,
            'preferred_locations' => [],
            'allow_contact' => true,
            'profile_completion_score' => 0
        ];
    }

    /**
     * Vérifier si l'utilisateur est authentifié
     */
    private function isAuthenticated(): bool
    {
        return session('logged_in') && session('user_id');
    }

    /**
     * Rediriger vers la page de connexion
     */
    private function redirectToLogin(string $message = 'Vous devez être connecté pour accéder à cette page.'): RedirectResponse
    {
        return redirect()->route('login')->with('error', $message);
    }
}
