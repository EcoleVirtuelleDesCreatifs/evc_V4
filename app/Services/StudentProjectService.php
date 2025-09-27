<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\ProjectImage;
use App\Models\Project;
use Illuminate\Support\Collection;

/**
 * Service pour la gestion des projets étudiants avec pagination par session
 * 
 * @author Développeur Senior EVC
 * @version 1.0
 */
class StudentProjectService
{
    /**
     * Nombre de projets par page
     */
    const PROJECTS_PER_PAGE = 6;

    /**
     * Types de projets supportés
     */
    const PROJECT_TYPES = [
        'design_projects' => 'Projets Réels',
        'projects' => 'Travaux Pratiques (Document PDF)'
    ];

    /**
     * Récupérer les projets paginés par session pour un étudiant
     *
     * @param int $userId ID de l'étudiant
     * @param string $projectType Type de projet (design_projects|projects)
     * @param int $page Numéro de page (défaut: 1)
     * @return array
     */
    public function getStudentProjectsBySession(int $userId, string $projectType = 'design_projects', int $page = 1): array
    {
        try {
            // Validation des paramètres
            if (!array_key_exists($projectType, self::PROJECT_TYPES)) {
                throw new \InvalidArgumentException("Type de projet non supporté: {$projectType}");
            }

            if ($page < 1) {
                $page = 1;
            }

            // Calcul des offsets pour la pagination
            $offset = ($page - 1) * self::PROJECTS_PER_PAGE;

            // Récupération des projets selon le type
            $projects = $this->fetchProjectsByType($userId, $projectType, $offset);
            $totalProjects = $this->getTotalProjectsCount($userId, $projectType);

            // Calcul des informations de pagination
            $totalPages = ceil($totalProjects / self::PROJECTS_PER_PAGE);
            $hasNextPage = $page < $totalPages;
            $hasPreviousPage = $page > 1;

            // Organisation des projets par session (basée sur la date de création)
            $projectsBySession = $this->organizeProjectsBySession($projects);

            return [
                'projects' => $projects,
                'projects_by_session' => $projectsBySession,
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => $totalPages,
                    'total_projects' => $totalProjects,
                    'projects_per_page' => self::PROJECTS_PER_PAGE,
                    'has_next_page' => $hasNextPage,
                    'has_previous_page' => $hasPreviousPage,
                    'showing_from' => $offset + 1,
                    'showing_to' => min($offset + self::PROJECTS_PER_PAGE, $totalProjects)
                ],
                'project_type' => $projectType,
                'project_type_label' => self::PROJECT_TYPES[$projectType]
            ];

        } catch (\Exception $e) {
            Log::error("Erreur dans getStudentProjectsBySession: " . $e->getMessage(), [
                'user_id' => $userId,
                'project_type' => $projectType,
                'page' => $page
            ]);

            return $this->getEmptyProjectsResponse($projectType, $page);
        }
    }

    /**
     * Récupérer les projets selon le type
     *
     * @param int $userId
     * @param string $projectType
     * @param int $offset
     * @return Collection
     */
    private function fetchProjectsByType(int $userId, string $projectType, int $offset): Collection
    {
        switch ($projectType) {
            case 'design_projects':
                return $this->fetchDesignProjects($userId, $offset);
            
            case 'projects':
                return $this->fetchLaravelProjects($userId, $offset);
            
            default:
                return collect([]);
        }
    }

    /**
     * Récupérer les projets design
     *
     * @param int $userId
     * @param int $offset
     * @return Collection
     */
    private function fetchDesignProjects(int $userId, int $offset): Collection
    {
        return DB::table('design_projects')
            ->where('user_id', $userId)
            ->select([
                'id',
                'title as project_name',
                'description',
                'status',
                'project_type',
                'project_mode',
                'created_at',
                'updated_at'
            ])
            ->orderBy('created_at', 'desc')
            ->offset($offset)
            ->limit(self::PROJECTS_PER_PAGE)
            ->get()
            ->map(function ($project) {
                // Enrichissement des données pour les projets design
                $project->type_label = 'Design';
                $project->status_label = $this->getDesignProjectStatusLabel($project->status);
                $project->session = $this->determineProjectSession($project->created_at);
                $project->files_count = $this->getDesignProjectFilesCount($project->id);
                return $project;
            });
    }

    /**
     * Récupérer les projets Laravel
     *
     * @param int $userId
     * @param int $offset
     * @return Collection
     */
    private function fetchLaravelProjects(int $userId, int $offset): Collection
    {
        return DB::table('projects')
            ->where('user_id', $userId)
            ->select([
                'id',
                'title as project_name',
                'description',
                'status',
                'created_at',
                'updated_at'
            ])
            ->orderBy('created_at', 'desc')
            ->offset($offset)
            ->limit(self::PROJECTS_PER_PAGE)
            ->get()
            ->map(function ($project) {
                // Enrichissement des données pour les projets Laravel
                $project->type_label = 'Laravel';
                $project->project_type = 'laravel';
                $project->project_mode = 'solo'; // Par défaut
                $project->status_label = $this->getLaravelProjectStatusLabel($project->status);
                $project->session = $this->determineProjectSession($project->created_at);
                $project->files_count = $this->getLaravelProjectFilesCount($project->id);
                return $project;
            });
    }

    /**
     * Obtenir le nombre total de projets
     *
     * @param int $userId
     * @param string $projectType
     * @return int
     */
    private function getTotalProjectsCount(int $userId, string $projectType): int
    {
        switch ($projectType) {
            case 'design_projects':
                return DB::table('design_projects')->where('user_id', $userId)->count();
            
            case 'projects':
                return DB::table('projects')->where('user_id', $userId)->count();
            
            default:
                return 0;
        }
    }

    /**
     * Organiser les projets par session
     *
     * @param Collection $projects
     * @return array
     */
    private function organizeProjectsBySession(Collection $projects): array
    {
        return $projects->groupBy('session')->map(function ($sessionProjects, $session) {
            return [
                'session_name' => $session,
                'projects_count' => $sessionProjects->count(),
                'projects' => $sessionProjects->values()
            ];
        })->toArray();
    }

    /**
     * Déterminer la session d'un projet basée sur sa date de création
     *
     * @param string $createdAt
     * @return string
     */
    private function determineProjectSession(string $createdAt): string
    {
        $date = Carbon::parse($createdAt);
        $year = $date->year;
        $month = $date->month;

        // Logique de session basée sur les trimestres
        if ($month >= 1 && $month <= 3) {
            return "Session Q1 {$year}";
        } elseif ($month >= 4 && $month <= 6) {
            return "Session Q2 {$year}";
        } elseif ($month >= 7 && $month <= 9) {
            return "Session Q3 {$year}";
        } else {
            return "Session Q4 {$year}";
        }
    }

    /**
     * Obtenir le label de statut pour les projets design
     *
     * @param string $status
     * @return string
     */
    private function getDesignProjectStatusLabel(string $status): string
    {
        $statusLabels = [
            'active' => 'En cours',
            'completed' => 'Terminé',
            'draft' => 'Brouillon',
            'validated' => 'Validé',
            'rejected' => 'Rejeté'
        ];

        return $statusLabels[$status] ?? ucfirst($status);
    }

    /**
     * Obtenir le label de statut pour les projets Laravel
     *
     * @param string $status
     * @return string
     */
    private function getLaravelProjectStatusLabel(string $status): string
    {
        $statusLabels = [
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'valide' => 'Validé',
            'rejete' => 'Rejeté'
        ];

        return $statusLabels[$status] ?? ucfirst($status);
    }

    /**
     * Obtenir le nombre de fichiers pour un projet design
     *
     * @param int $projectId
     * @return int
     */
    private function getDesignProjectFilesCount(int $projectId): int
    {
        try {
            return DB::table('design_project_files')
                ->where('design_project_id', $projectId)
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Obtenir le nombre de fichiers pour un projet Laravel
     *
     * @param int $projectId
     * @return int
     */
    private function getLaravelProjectFilesCount(int $projectId): int
    {
        try {
            return DB::table('project_images')
                ->where('project_id', $projectId)
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Réponse vide en cas d'erreur
     *
     * @param string $projectType
     * @param int $page
     * @return array
     */
    private function getEmptyProjectsResponse(string $projectType, int $page): array
    {
        return [
            'projects' => collect([]),
            'projects_by_session' => [],
            'pagination' => [
                'current_page' => $page,
                'total_pages' => 0,
                'total_projects' => 0,
                'projects_per_page' => self::PROJECTS_PER_PAGE,
                'has_next_page' => false,
                'has_previous_page' => false,
                'showing_from' => 0,
                'showing_to' => 0
            ],
            'project_type' => $projectType,
            'project_type_label' => self::PROJECT_TYPES[$projectType] ?? 'Projets'
        ];
    }

    /**
     * Get paginated projects by type for a student
     *
     * @param int $userId
     * @param string $projectType
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public function getProjectsByType(int $userId, string $projectType, int $page = 1, int $perPage = 6): array
    {
        try {
            $projects = $this->fetchProjectsByTypeForPagination($userId, $projectType);
            $projectsWithSessions = $this->organizeProjectsBySession($projects);
            
            return $this->paginateProjects($projectsWithSessions, $page, $perPage, $projectType);
        } catch (\Exception $e) {
            Log::error('Error fetching projects by type', [
                'user_id' => $userId,
                'project_type' => $projectType,
                'error' => $e->getMessage()
            ]);
            
            return [
                'projects_by_session' => [],
                'pagination' => [
                    'current_page' => 1,
                    'total_pages' => 1,
                    'per_page' => $perPage,
                    'total_projects' => 0,
                    'has_previous' => false,
                    'has_next' => false
                ],
                'total_projects' => 0,
                'total_files' => 0,
                'project_type_label' => self::PROJECT_TYPES[$projectType] ?? 'Projets'
            ];
        }
    }

    /**
     * Fetch projects by type from database for pagination
     *
     * @param int $userId
     * @param string $projectType
     * @return \Illuminate\Support\Collection
     */
    private function fetchProjectsByTypeForPagination(int $userId, string $projectType): \Illuminate\Support\Collection
    {
        if ($projectType === 'design_projects') {
            try {
                return DB::table('design_projects')
                    ->where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(function ($project) {
                        $project->status_label = $this->getDesignProjectStatusLabel($project->status ?? 'draft');
                        $project->files_count = $this->getDesignProjectFilesCount($project->id);
                        $project->formatted_date = Carbon::parse($project->created_at)->format('d/m/Y');
                        $project->formatted_time = Carbon::parse($project->created_at)->format('H:i');
                        return $project;
                    });
            } catch (\Exception $e) {
                Log::info('Table design_projects non trouvée');
                return collect([]);
            }
        } elseif ($projectType === 'projects') {
            try {
                return DB::table('projects')
                    ->where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(function ($project) {
                        $project->status_label = $this->getLaravelProjectStatusLabel($project->status ?? 'en_cours');
                        $project->files_count = $this->getLaravelProjectFilesCount($project->id);
                        $project->formatted_date = Carbon::parse($project->created_at)->format('d/m/Y');
                        $project->formatted_time = Carbon::parse($project->created_at)->format('H:i');
                        return $project;
                    });
            } catch (\Exception $e) {
                Log::info('Table projects non trouvée');
                return collect([]);
            }
        }
        
        return collect([]);
    }

    /**
     * Paginate projects by session
     *
     * @param array $projectsBySession
     * @param int $page
     * @param int $perPage
     * @param string $projectType
     * @return array
     */
    private function paginateProjects(array $projectsBySession, int $page, int $perPage, string $projectType): array
    {
        $allProjects = [];
        foreach ($projectsBySession as $sessionName => $projects) {
            $allProjects = array_merge($allProjects, $projects);
        }
        
        $totalProjects = count($allProjects);
        $totalPages = max(1, ceil($totalProjects / $perPage));
        $currentPage = max(1, min($page, $totalPages));
        $offset = ($currentPage - 1) * $perPage;
        
        // Get projects for current page
        $paginatedProjects = array_slice($allProjects, $offset, $perPage);
        
        // Reorganize paginated projects by session
        $paginatedBySession = [];
        foreach ($paginatedProjects as $project) {
            $sessionName = $this->getSessionFromDate($project->created_at);
            if (!isset($paginatedBySession[$sessionName])) {
                $paginatedBySession[$sessionName] = [];
            }
            $paginatedBySession[$sessionName][] = $project;
        }
        
        return [
            'projects_by_session' => $paginatedBySession,
            'pagination' => [
                'current_page' => $currentPage,
                'total_pages' => $totalPages,
                'per_page' => $perPage,
                'total_projects' => $totalProjects,
                'has_previous' => $currentPage > 1,
                'has_next' => $currentPage < $totalPages,
                'project_type' => $projectType
            ],
            'total_projects' => $totalProjects,
            'total_files' => array_sum(array_column($allProjects, 'files_count')),
            'project_type_label' => self::PROJECT_TYPES[$projectType] ?? 'Projets'
        ];
    }

    /**
     * Get session from date (helper method for documents and projects)
     *
     * @param string $date
     * @return string
     */
    private function getSessionFromDate(string $date): string
    {
        $carbon = Carbon::parse($date);
        $month = $carbon->month;
        $year = $carbon->year;
        
        if ($month >= 1 && $month <= 3) {
            return "Q1 {$year}";
        } elseif ($month >= 4 && $month <= 6) {
            return "Q2 {$year}";
        } elseif ($month >= 7 && $month <= 9) {
            return "Q3 {$year}";
        } else {
            return "Q4 {$year}";
        }
    }

    /**
     * Get project statistics for a student
     *
     * @param int $userId
     * @return array
     */
    public function getProjectStatistics(int $userId): array
    {
        try {
            // Design projects statistics
            $designStats = $this->getProjectsByType($userId, 'design_projects', 1, 1000);
            
            // Laravel projects statistics  
            $laravelStats = $this->getProjectsByType($userId, 'projects', 1, 1000);
            
            // Vérifier que les données sont valides
            $designTotalProjects = $designStats['total_projects'] ?? 0;
            $designTotalFiles = $designStats['total_files'] ?? 0;
            $designSessionsCount = isset($designStats['projects_by_session']) ? count($designStats['projects_by_session']) : 0;
            
            $laravelTotalProjects = $laravelStats['total_projects'] ?? 0;
            $laravelTotalFiles = $laravelStats['total_files'] ?? 0;
            $laravelSessionsCount = isset($laravelStats['projects_by_session']) ? count($laravelStats['projects_by_session']) : 0;
            
            return [
                'design' => [
                    'total_projects' => $designTotalProjects,
                    'total_files' => $designTotalFiles,
                    'sessions_count' => $designSessionsCount
                ],
                'laravel' => [
                    'total_projects' => $laravelTotalProjects,
                    'total_files' => $laravelTotalFiles,
                    'sessions_count' => $laravelSessionsCount
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error getting project statistics', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'design' => ['total_projects' => 0, 'total_files' => 0, 'sessions_count' => 0],
                'laravel' => ['total_projects' => 0, 'total_files' => 0, 'sessions_count' => 0]
            ];
        }
    }

    /**
     * Get student projects statistics
     *
     * @param int $userId
     * @return array
     */
    public function getStudentProjectsStats(int $userId): array
    {
        try {
            $designStats = [
                'total' => DB::table('design_projects')->where('user_id', $userId)->count(),
                'completed' => DB::table('design_projects')->where('user_id', $userId)->where('status', 'completed')->count(),
                'active' => DB::table('design_projects')->where('user_id', $userId)->where('status', 'active')->count(),
                'draft' => DB::table('design_projects')->where('user_id', $userId)->where('status', 'draft')->count(),
            ];

            $laravelStats = [
                'total' => DB::table('projects')->where('user_id', $userId)->count(),
                'valide' => DB::table('projects')->where('user_id', $userId)->where('status', 'valide')->count(),
                'en_cours' => DB::table('projects')->where('user_id', $userId)->where('status', 'en_cours')->count(),
                'termine' => DB::table('projects')->where('user_id', $userId)->where('status', 'termine')->count(),
            ];

            return [
                'design_projects' => $designStats,
                'projects' => $laravelStats,
                'total_all_projects' => $designStats['total'] + $laravelStats['total']
            ];

        } catch (\Exception $e) {
            Log::error("Erreur dans getStudentProjectsStats: " . $e->getMessage(), ['user_id' => $userId]);
            
            return [
                'design_projects' => ['total' => 0, 'completed' => 0, 'active' => 0, 'draft' => 0],
                'projects' => ['total' => 0, 'valide' => 0, 'en_cours' => 0, 'termine' => 0],
                'total_all_projects' => 0
            ];
        }
    }

    /**
     * Get paginated documents by type for a student
     *
     * @param int $userId
     * @param string $documentType
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public function getDocumentsByType(int $userId, string $documentType, int $page = 1, int $perPage = 6): array
    {
        try {
            $documents = $this->fetchDocumentsByType($userId, $documentType);
            $documentsWithSessions = $this->organizeDocumentsBySession($documents);
            
            return $this->paginateDocuments($documentsWithSessions, $page, $perPage, $documentType);
        } catch (\Exception $e) {
            Log::error('Error fetching documents by type', [
                'user_id' => $userId,
                'document_type' => $documentType,
                'error' => $e->getMessage()
            ]);
            
            return [
                'documents_by_session' => [],
                'pagination' => [
                    'current_page' => 1,
                    'total_pages' => 1,
                    'per_page' => $perPage,
                    'total_documents' => 0,
                    'has_previous' => false,
                    'has_next' => false
                ],
                'total_documents' => 0,
                'total_files' => 0
            ];
        }
    }

    /**
     * Fetch documents by type from database
     *
     * @param int $userId
     * @param string $documentType
     * @return \Illuminate\Support\Collection
     */
    private function fetchDocumentsByType(int $userId, string $documentType): \Illuminate\Support\Collection
    {
        $documents = collect([]);
        
        if ($documentType === 'cvtheque') {
            // CVThèque documents
            try {
                $cvProfile = DB::table('cvtheque_profiles')->where('user_id', $userId)->first();
                if ($cvProfile) {
                    $cvDocuments = collect();
                    
                    // CV
                    if ($cvProfile->cv_file_path) {
                        $cvDocuments->push((object)[
                            'id' => 'cv_' . $cvProfile->id,
                            'original_name' => $cvProfile->cv_file_name ?? 'CV.pdf',
                            'stored_name' => $cvProfile->cv_file_path,
                            'file_size' => 0,
                            'mime_type' => 'application/pdf',
                            'document_type' => 'CV',
                            'source' => 'CVThèque',
                            'source_title' => 'CV',
                            'status' => 'approved',
                            'created_at' => $cvProfile->created_at
                        ]);
                    }
                    
                    // Lettre de motivation
                    if ($cvProfile->motivation_letter_path) {
                        $cvDocuments->push((object)[
                            'id' => 'motivation_' . $cvProfile->id,
                            'original_name' => $cvProfile->motivation_letter_name ?? 'Lettre_motivation.pdf',
                            'stored_name' => $cvProfile->motivation_letter_path,
                            'file_size' => 0,
                            'mime_type' => 'application/pdf',
                            'document_type' => 'Lettre de motivation',
                            'source' => 'CVThèque',
                            'source_title' => 'Lettre de motivation',
                            'status' => 'approved',
                            'created_at' => $cvProfile->created_at
                        ]);
                    }
                    
                    // Pressbook
                    if ($cvProfile->pressbook_file_path) {
                        $cvDocuments->push((object)[
                            'id' => 'pressbook_' . $cvProfile->id,
                            'original_name' => $cvProfile->pressbook_file_name ?? 'Pressbook.pdf',
                            'stored_name' => $cvProfile->pressbook_file_path,
                            'file_size' => 0,
                            'mime_type' => 'application/pdf',
                            'document_type' => 'Pressbook',
                            'source' => 'CVThèque',
                            'source_title' => 'Pressbook',
                            'status' => 'approved',
                            'created_at' => $cvProfile->created_at
                        ]);
                    }
                    
                    // Rapport de fin de formation
                    if ($cvProfile->report_file_path) {
                        $cvDocuments->push((object)[
                            'id' => 'report_' . $cvProfile->id,
                            'original_name' => $cvProfile->report_file_name ?? 'Rapport.pdf',
                            'stored_name' => $cvProfile->report_file_path,
                            'file_size' => 0,
                            'mime_type' => 'application/pdf',
                            'document_type' => 'Rapport',
                            'source' => 'CVThèque',
                            'source_title' => 'Rapport de fin de formation',
                            'status' => 'approved',
                            'created_at' => $cvProfile->created_at
                        ]);
                    }
                    
                    $documents = $documents->concat($cvDocuments);
                }
            } catch (\Exception $e) {
                Log::info('Table cvtheque_profiles non trouvée');
            }
        } elseif ($documentType === 'files') {
            // General files and documents
            
            // 1. Design project files
            try {
                $designFiles = DB::table('design_project_files as dpf')
                    ->join('design_projects as dp', 'dpf.design_project_id', '=', 'dp.id')
                    ->where('dp.user_id', $userId)
                    ->select(
                        'dpf.*',
                        'dp.title as source_title',
                        DB::raw("'Projet Design' as source"),
                        'dp.id as project_id'
                    )
                    ->get();
                $documents = $documents->concat($designFiles);
            } catch (\Exception $e) {
                Log::info('Table design_project_files non trouvée');
            }
            
            // 2. Laravel project images
            try {
                $projectImages = DB::table('project_images as pi')
                    ->join('projects as p', 'pi.project_id', '=', 'p.id')
                    ->where('p.user_id', $userId)
                    ->select(
                        'pi.*',
                        'p.title as source_title',
                        DB::raw("'Projet Laravel' as source"),
                        'pi.original_name',
                        'pi.filename as stored_name',
                        'p.id as project_id'
                    )
                    ->get();
                $documents = $documents->concat($projectImages);
            } catch (\Exception $e) {
                Log::info('Table project_images non trouvée');
            }
            
            // 3. Validation documents
            try {
                $validationDocs = DB::table('document_validations')
                    ->where('user_id', $userId)
                    ->select(
                        '*',
                        'document_name as original_name',
                        'document_path as stored_name',
                        DB::raw("'Validation' as source"),
                        'document_type as source_title'
                    )
                    ->get();
                $documents = $documents->concat($validationDocs);
            } catch (\Exception $e) {
                Log::info('Table document_validations non trouvée');
            }
            
            // 4. User documents
            try {
                $userDocs = DB::table('user_documents')
                    ->where('user_id', $userId)
                    ->select(
                        '*',
                        'file_name as original_name',
                        'file_path as stored_name',
                        DB::raw("'Documents' as source"),
                        'document_type as source_title',
                        'uploaded_at as created_at'
                    )
                    ->get();
                $documents = $documents->concat($userDocs);
            } catch (\Exception $e) {
                Log::info('Table user_documents non trouvée');
            }
        }
        
        return $documents->sortByDesc('created_at');
    }

    /**
     * Organize documents by session based on creation date
     *
     * @param \Illuminate\Support\Collection $documents
     * @return array
     */
    private function organizeDocumentsBySession(\Illuminate\Support\Collection $documents): array
    {
        $documentsBySession = [];
        
        foreach ($documents as $document) {
            $sessionName = $this->getSessionFromDate($document->created_at);
            
            if (!isset($documentsBySession[$sessionName])) {
                $documentsBySession[$sessionName] = [];
            }
            
            $documentsBySession[$sessionName][] = $this->enrichDocumentData($document);
        }
        
        // Sort sessions by name (Q1, Q2, Q3, Q4)
        ksort($documentsBySession);
        
        return $documentsBySession;
    }

    /**
     * Paginate documents by session
     *
     * @param array $documentsBySession
     * @param int $page
     * @param int $perPage
     * @param string $documentType
     * @return array
     */
    private function paginateDocuments(array $documentsBySession, int $page, int $perPage, string $documentType): array
    {
        $allDocuments = [];
        foreach ($documentsBySession as $sessionName => $documents) {
            $allDocuments = array_merge($allDocuments, $documents);
        }
        
        $totalDocuments = count($allDocuments);
        $totalPages = max(1, ceil($totalDocuments / $perPage));
        $currentPage = max(1, min($page, $totalPages));
        $offset = ($currentPage - 1) * $perPage;
        
        // Get documents for current page
        $paginatedDocuments = array_slice($allDocuments, $offset, $perPage);
        
        // Reorganize paginated documents by session
        $paginatedBySession = [];
        foreach ($paginatedDocuments as $document) {
            $sessionName = $this->getSessionFromDate($document->created_at);
            if (!isset($paginatedBySession[$sessionName])) {
                $paginatedBySession[$sessionName] = [];
            }
            $paginatedBySession[$sessionName][] = $document;
        }
        
        return [
            'documents_by_session' => $paginatedBySession,
            'pagination' => [
                'current_page' => $currentPage,
                'total_pages' => $totalPages,
                'per_page' => $perPage,
                'total_documents' => $totalDocuments,
                'has_previous' => $currentPage > 1,
                'has_next' => $currentPage < $totalPages,
                'document_type' => $documentType
            ],
            'total_documents' => $totalDocuments,
            'total_files' => $totalDocuments
        ];
    }

    /**
     * Enrich document data with additional information
     *
     * @param object $document
     * @return object
     */
    private function enrichDocumentData(object $document): object
    {
        // Add status label
        $document->status_label = $this->getDocumentStatusLabel($document->status ?? 'pending');
        
        // Add file size in MB
        $document->file_size_mb = number_format(($document->file_size ?? 0) / 1024 / 1024, 2);
        
        // Add formatted date
        $document->formatted_date = Carbon::parse($document->created_at)->format('d/m/Y');
        $document->formatted_time = Carbon::parse($document->created_at)->format('H:i');
        
        return $document;
    }

    /**
     * Get document status label
     *
     * @param string $status
     * @return string
     */
    private function getDocumentStatusLabel(string $status): string
    {
        switch ($status) {
            case 'approved':
                return 'Approuvé';
            case 'rejected':
                return 'Rejeté';
            case 'pending':
                return 'En attente';
            default:
                return 'Non défini';
        }
    }

    /**
     * Get paginated projects (TP) for a student using Eloquent - Table projects
     *
     * @param int $userId
     * @param int $page
     * @return array
     */
    public function getProjectImagesByStudent(int $userId, int $page = 1): array
    {
        try {
            \Illuminate\Support\Facades\Log::info("getProjectImagesByStudent called", ['user_id' => $userId, 'page' => $page]);
            
            // Utiliser la table projects directement avec Eloquent
            $perPage = 6;
            
            // Récupérer les projets avec pagination via Eloquent et leurs images
            $projects = \App\Models\Project::with(['images' => function($query) {
                    $query->where('mime_type', 'like', 'image/%')->orderBy('created_at', 'desc')->limit(1);
                }])
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'images_page', $page);
                
            \Illuminate\Support\Facades\Log::info("Projects found", ['count' => $projects->count(), 'total' => method_exists($projects, 'total') ? $projects->total() : $projects->count()]);

            // Organiser par session basée sur la date de création (approche Laravel propre)
            $projectsBySession = [];
            foreach ($projects->items() as $project) {
                // Calculer la session basée sur la date de création du projet
                $sessionKey = $this->calculateSessionFromDate($project->created_at);
                
                if (!isset($projectsBySession[$sessionKey])) {
                    $projectsBySession[$sessionKey] = [];
                }

                // Enrichir les données avec des accesseurs Laravel propres
                $firstImage = $project->images->first();
                $enrichedProject = (object) [
                    'id' => $project->id,
                    'title' => $project->title,
                    'category' => $project->category,
                    'description' => $project->description,
                    'link' => $project->link,
                    'tags' => $project->tags,
                    'software_used' => is_string($project->software_used) ? json_decode($project->software_used, true) : $project->software_used,
                    'thumbnail_image' => $project->thumbnail_image,
                    'status' => $project->status,
                    'created_at' => $project->created_at,
                    'updated_at' => $project->updated_at,
                    'status_label' => $this->getProjectStatusLabel($project->status),
                    'formatted_date' => $project->created_at->format('d/m/Y H:i'),
                    'software_list' => $this->formatSoftwareList($project->software_used),
                    // Données d'image pour l'aperçu
                    'has_image' => $firstImage !== null,
                    'image_path' => $firstImage ? $firstImage->file_path : null,
                    'image_name' => $firstImage ? $firstImage->original_name : null,
                    'image_id' => $firstImage ? $firstImage->id : null
                ];

                $projectsBySession[$sessionKey][] = $enrichedProject;
            }

            // Métadonnées de pagination Laravel propres
            $pagination = [
                'current_page' => $projects->currentPage(),
                'total_pages' => $projects->lastPage(),
                'total_projects' => method_exists($projects, 'total') ? $projects->total() : $projects->count(),
                'projects_per_page' => $perPage,
                'has_next_page' => $projects->hasMorePages(),
                'has_previous_page' => $projects->currentPage() > 1,
                'showing_from' => $projects->firstItem() ?? 0,
                'showing_to' => $projects->lastItem() ?? 0
            ];

            return [
                'projects' => $projects->items(),
                'projects_by_session' => $projectsBySession,
                'pagination' => $pagination,
                'project_type' => 'projects',
                'project_type_label' => 'Projets Réels'
            ];

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des projets: ' . $e->getMessage(), [
                'user_id' => $userId,
                'page' => $page,
                'error' => $e->getTraceAsString()
            ]);
            
            return $this->getEmptyProjectImagesResponse($page);
        }
    }

    /**
     * Get paginated design projects for a student with same structure as TP images
     *
     * @param int $userId
     * @param int $page
     * @return array
     */
    public function getDesignProjectImagesData(int $userId, int $page = 1): array
    {
        try {
            \Illuminate\Support\Facades\Log::info("getDesignProjectImagesData called", ['user_id' => $userId, 'page' => $page]);
            
            // Utiliser la table design_projects avec Eloquent et relations
            $perPage = 6;
            
            // Récupérer les projets design avec pagination et leurs fichiers preview
            $projects = \App\Models\DesignProject::with(['firstPreviewImage'])
                ->forUser($userId)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'design_images_page', $page);
                
            \Illuminate\Support\Facades\Log::info("Design projects found", ['count' => $projects->count(), 'total' => method_exists($projects, 'total') ? $projects->total() : $projects->count()]);

            // Organiser par session basée sur la date de création (approche Laravel propre)
            $projectsBySession = [];
            foreach ($projects->items() as $project) {
                // Calculer la session basée sur la date de création du projet
                $sessionKey = $this->calculateSessionFromDate($project->created_at);
                
                if (!isset($projectsBySession[$sessionKey])) {
                    $projectsBySession[$sessionKey] = [];
                }

                // Enrichir les données avec des accesseurs Laravel propres (compatible avec le composant TP images)
                $previewFile = $project->firstPreviewImage;
                
                // Traitement correct du champ software_used (JSON)
                $softwareUsed = [];
                if (!empty($project->software_used)) {
                    try {
                        if (is_string($project->software_used)) {
                            $decoded = json_decode($project->software_used, true);
                            $softwareUsed = is_array($decoded) ? $decoded : [];
                        } elseif (is_array($project->software_used)) {
                            $softwareUsed = $project->software_used;
                        }
                    } catch (\Exception $e) {
                        $softwareUsed = [];
                    }
                }
                
                $enrichedProject = (object) [
                    'id' => $project->id,
                    'title' => $project->title,
                    'category' => $project->project_type ?? 'Design',
                    'description' => $project->description,
                    'link' => $project->reference_url,
                    'tags' => $project->software_used,
                    'software_used' => $softwareUsed, // Correctement formaté pour le composant
                    'project_mode' => $project->project_mode ?? 'solo', // Ajout du mode manquant
                    'project_type' => $project->project_type ?? 'Design',
                    'thumbnail_image' => null,
                    'status' => $project->status,
                    'created_at' => $project->created_at,
                    'updated_at' => $project->updated_at,
                    'formatted_date' => \Carbon\Carbon::parse($project->created_at)->format('d/m/Y'),
                    'status_label' => $this->getProjectStatusLabel($project->status),
                    'user' => (object) [
                        'id' => $userId,
                        'name' => 'Étudiant',
                        'email' => ''
                    ],

                    // Données d'image pour l'aperçu (compatible avec composant TP images)
                    'has_image' => $previewFile !== null,
                    'image_path' => $previewFile ? $previewFile->file_path : null,
                    'image_name' => $previewFile ? $previewFile->original_name : null,
                    'image_id' => $previewFile ? $previewFile->id : null
                ];

                $projectsBySession[$sessionKey][] = $enrichedProject;
            }

            // Métadonnées de pagination Laravel propres
            $pagination = [
                'current_page' => $projects->currentPage(),
                'total_pages' => $projects->lastPage(),
                'total_projects' => method_exists($projects, 'total') ? $projects->total() : $projects->count(),
                'projects_per_page' => $perPage,
                'has_next_page' => $projects->hasMorePages(),
                'has_previous_page' => $projects->currentPage() > 1,
                'showing_from' => $projects->firstItem() ?? 0,
                'showing_to' => $projects->lastItem() ?? 0
            ];

            return [
                'projects' => $projects->items(),
                'projects_by_session' => $projectsBySession,
                'pagination' => $pagination,
                'project_type' => 'design_projects',
                'project_type_label' => 'Projets Réels'
            ];

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des projets design: ' . $e->getMessage(), [
                'user_id' => $userId,
                'page' => $page,
                'error' => $e->getTraceAsString()
            ]);
            
            return $this->getEmptyProjectImagesResponse($page);
        }
    }

    /**
     * Get paginated design projects for a student
     *
     * @param int $userId
     * @param int $page
     * @return array
     */
    public function getDesignProjectsByStudent(int $userId, int $page = 1): array
    {
        try {
            \Illuminate\Support\Facades\Log::info("getDesignProjectsByStudent called", ['user_id' => $userId, 'page' => $page]);
            
            // Utiliser la table design_projects avec Eloquent et relations
            $perPage = 6;
            
            // Récupérer les projets design avec pagination et leurs fichiers preview
            $designProjects = \App\Models\DesignProject::with(['firstPreviewImage'])
                ->forUser($userId)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'design_page', $page);
                
            \Illuminate\Support\Facades\Log::info("Design projects found", ['count' => $designProjects->count(), 'total' => method_exists($designProjects, 'total') ? $designProjects->total() : $designProjects->count()]);

            // Organiser par session basée sur la date de création (approche Laravel propre)
            $projectsBySession = [];
            foreach ($designProjects->items() as $project) {
                // Calculer la session basée sur la date de création du projet
                $sessionKey = $this->calculateSessionFromDate($project->created_at);
                
                if (!isset($projectsBySession[$sessionKey])) {
                    $projectsBySession[$sessionKey] = [
                        'projects' => [],
                        'projects_count' => 0
                    ];
                }

                // Enrichir les données avec des accesseurs Laravel propres (compatible avec le composant existant)
                $previewFile = $project->firstPreviewImage;
                $enrichedProject = (object) [
                    'id' => $project->id,
                    'project_name' => $project->title, // Nom attendu par le composant
                    'title' => $project->title,
                    'description' => $project->description,
                    'project_type' => $project->project_type,
                    'type_label' => $project->project_type_label, // Nom attendu par le composant
                    'project_mode' => $project->project_mode,
                    'mode_label' => $project->mode_label,
                    'software_used' => $project->software_used,
                    'software_list' => $project->formatted_software,
                    'reference_url' => $project->reference_url,
                    'status' => $project->status,
                    'status_label' => $project->status_label,
                    'progress_percentage' => $project->progress_percentage,
                    'progress_status' => $project->progress_status,
                    'created_at' => $project->created_at,
                    'updated_at' => $project->updated_at,
                    'completed_at' => $project->completed_at,
                    'formatted_date' => $project->formatted_date,
                    'files_count' => $project->files()->count(), // Nombre de fichiers
                    // Données d'image pour l'aperçu
                    'has_preview' => $previewFile !== null,
                    'preview_path' => $previewFile ? $previewFile->file_path : null,
                    'preview_name' => $previewFile ? $previewFile->original_name : null,
                    'preview_id' => $previewFile ? $previewFile->id : null
                ];

                $projectsBySession[$sessionKey]['projects'][] = $enrichedProject;
                $projectsBySession[$sessionKey]['projects_count']++;
            }

            // Métadonnées de pagination Laravel propres
            $pagination = [
                'current_page' => $designProjects->currentPage(),
                'total_pages' => $designProjects->lastPage(),
                'total_projects' => method_exists($designProjects, 'total') ? $designProjects->total() : $designProjects->count(),
                'projects_per_page' => $perPage,
                'has_next_page' => $designProjects->hasMorePages(),
                'has_previous_page' => $designProjects->currentPage() > 1,
                'showing_from' => $designProjects->firstItem() ?? 0,
                'showing_to' => $designProjects->lastItem() ?? 0
            ];

            return [
                'projects' => $designProjects->items(),
                'projects_by_session' => $projectsBySession,
                'pagination' => $pagination,
                'project_type' => 'design_projects',
                'project_type_label' => 'Projets Réel'
            ];

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur lors de la récupération des projets design: ' . $e->getMessage(), [
                'user_id' => $userId,
                'page' => $page,
                'error' => $e->getTraceAsString()
            ]);
            
            return $this->getEmptyDesignProjectsResponse($page);
        }
    }

    /**
     * Empty response for design projects
     *
     * @param int $page
     * @return array
     */
    private function getEmptyDesignProjectsResponse(int $page): array
    {
        return [
            'projects' => collect([]),
            'projects_by_session' => [],
            'pagination' => [
                'current_page' => $page,
                'total_pages' => 0,
                'total_projects' => 0,
                'projects_per_page' => 6,
                'has_next_page' => false,
                'has_previous_page' => false,
                'showing_from' => 0,
                'showing_to' => 0
            ],
            'project_type' => 'design_projects',
            'project_type_label' => 'Projets Réel'
        ];
    }

    /**
     * Format software list for display
     *
     * @param string|array $softwareUsed
     * @return string
     */
    private function formatSoftwareList($softwareUsed): string
    {
        if (empty($softwareUsed)) {
            return 'Non spécifié';
        }

        // Si c'est une chaîne JSON, la décoder
        if (is_string($softwareUsed)) {
            $softwareArray = json_decode($softwareUsed, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return $softwareUsed; // Retourner la chaîne originale si ce n'est pas du JSON valide
            }
        } else {
            $softwareArray = $softwareUsed;
        }

        // Si c'est un tableau, le formater
        if (is_array($softwareArray)) {
            return implode(', ', array_filter($softwareArray));
        }

        return 'Non spécifié';
    }

    /**
     * Calculate session quarter from date (Laravel clean approach)
     *
     * @param \Carbon\Carbon|string $date
     * @return string
     */
    private function calculateSessionFromDate($date): string
    {
        $carbonDate = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);
        $month = $carbonDate->month;
        $year = $carbonDate->year;
        
        $quarter = match(true) {
            $month >= 1 && $month <= 3 => 'Q1',
            $month >= 4 && $month <= 6 => 'Q2', 
            $month >= 7 && $month <= 9 => 'Q3',
            $month >= 10 && $month <= 12 => 'Q4',
            default => 'Q1'
        };
        
        return "Session {$quarter} {$year}";
    }

    /**
     * Empty response for project images
     *
     * @param int $page
     * @return array
     */
    private function getEmptyProjectImagesResponse(int $page): array
    {
        return [
            'projects' => collect([]),
            'projects_by_session' => [],
            'pagination' => [
                'current_page' => $page,
                'total_pages' => 0,
                'total_projects' => 0,
                'projects_per_page' => 6,
                'has_next_page' => false,
                'has_previous_page' => false,
                'showing_from' => 0,
                'showing_to' => 0
            ],
            'project_type' => 'projects',
            'project_type_label' => 'Travaux Pratiques ( Images / Print)'
        ];
    }

    /**
     * Get project status label for project_images
     *
     * @param string $status
     * @return string
     */
    private function getProjectStatusLabel(string $status): string
    {
        switch ($status) {
            case 'valide':
                return 'Validé';
            case 'en_cours':
                return 'En cours';
            case 'termine':
                return 'Terminé';
            case 'rejete':
                return 'Rejeté';
            default:
                return 'Non défini';
        }
    }

    /**
     * Get session label from quarter
     *
     * @param string|null $quarter
     * @return string
     */
    private function getSessionLabel(?string $quarter): string
    {
        if (empty($quarter)) {
            return 'Session non définie';
        }

        return match($quarter) {
            'Q1' => 'Session Q1 2024',
            'Q2' => 'Session Q2 2024',
            'Q3' => 'Session Q3 2024',
            'Q4' => 'Session Q4 2024',
            default => 'Session ' . $quarter
        };
    }
}
