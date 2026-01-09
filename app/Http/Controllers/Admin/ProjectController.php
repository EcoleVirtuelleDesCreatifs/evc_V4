<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TP;
use App\Models\DesignProject;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectController extends Controller
{
    // Méthodes pour les projets Design Graphique
    public function pendingDesignGraphique()
    {
        $designQuery = DesignProject::query()
            ->whereHas('user.student', function ($query) {
                $query->whereRaw('LOWER(program) LIKE ?', ['%design%graph%'])
                    ->whereRaw('LOWER(program) NOT LIKE ?', ['%community%']);
            });

        $tpQuery = TP::query()
            ->whereHas('user.student', function ($query) {
                $query->whereRaw('LOWER(program) LIKE ?', ['%design%graph%'])
                    ->whereRaw('LOWER(program) NOT LIKE ?', ['%community%']);
            });

        $stats = [
            'total' => (clone $designQuery)->count() + (clone $tpQuery)->count(),
            'pending' => (clone $designQuery)->where('status', 'pending')->count() + (clone $tpQuery)->where('status', 'pending')->count(),
            // Compat UI (vue admin.projects.index affiche aussi une card "À envoyer")
            'to_send' => (clone $tpQuery)->where('status', 'to_send')->count(),
            'validated' => (clone $designQuery)->where('status', 'validated')->count() + (clone $tpQuery)->where('status', 'validated')->count(),
            // Compat
            'rejected' => (clone $designQuery)->where('status', 'rejected')->count() + (clone $tpQuery)->where('status', 'rejected')->count(),
        ];

        // Fusionner les deux sources (DesignProject + TP), trier par date et paginer manuellement
        $designPending = (clone $designQuery)
            ->where('status', 'pending')
            ->with(['user', 'user.student', 'files'])
            ->get();

        $tpPending = (clone $tpQuery)
            ->where('status', 'pending')
            ->with(['user', 'user.student', 'files'])
            ->get();

        $allPending = $tpPending
            ->concat($designPending)
            ->sortByDesc(function ($item) {
                return $item->created_at;
            })
            ->values();

        $perPage = 15;
        $currentPage = (int) request()->get('page', 1);
        $currentItems = $allPending->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $projects = new LengthAwarePaginator(
            $currentItems,
            $allPending->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.projects.index', [
            'projects' => $projects,
            'title' => 'Projets Design Graphique - En cours de validation',
            'type' => 'design-graphique',
            'status' => 'pending',
            'stats' => $stats,
        ]);
    }

    public function toSendDesignGraphique()
    {
        // Formulaire d'attribution: réutilise le flow admin.projets.to-send / admin.projets.send
        $students = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('students.status', 'active')
            ->select('students.*', 'users.email')
            ->get();

        // Normaliser les formations pour cohérence
        $students = $students->map(function ($student) {
            if ($student->program) {
                $programNormalizedKey = strtolower(str_replace([' ', '_', '-'], '', $student->program));
                $containsDesign = str_contains($programNormalizedKey, 'design');
                $containsCommunity = str_contains($programNormalizedKey, 'community');

                if ($containsDesign && $containsCommunity) {
                    $normalized = 'Design Graphique & Community Management';
                } else {
                    $normalized = match ($programNormalizedKey) {
                        'designgraphique' => 'Design Graphique',
                        'communitymanagement' => 'Community Management',
                        'gestioninformatique' => 'Gestion Informatique',
                        'intelligenceartificielle' => 'Intelligence Artificielle',
                        default => $student->program
                    };
                }
                $student->program_normalized = $normalized;
            } else {
                $student->program_normalized = 'Sans formation';
            }
            return $student;
        });

        // Stats globales (pour les cards en haut du formulaire)
        $stats = [
            'total_students' => $students->count(),
            'design_graphique' => $students->where('program_normalized', 'Design Graphique')->count(),
            'design_graphique_cm' => $students->where('program_normalized', 'Design Graphique & Community Management')->count(),
            'community_management' => $students->where('program_normalized', 'Community Management')->count(),
            'gestion_informatique' => $students->where('program_normalized', 'Gestion Informatique')->count(),
            'intelligence_artificielle' => $students->where('program_normalized', 'Intelligence Artificielle')->count(),
            'sans_formation' => $students->where('program_normalized', 'Sans formation')->count(),
        ];

        // Filtrer la liste affichée: Design Graphique + double cursus Design & Community
        $filteredStudents = $students
            ->whereIn('program_normalized', ['Design Graphique', 'Design Graphique & Community Management'])
            ->values();

        return view('admin.projets.to-send', [
            'students' => $filteredStudents,
            'all_students' => $filteredStudents,
            'stats' => $stats,
            'defaultFormation' => 'Design Graphique',
        ]);
    }

    public function assignedDesignGraphique()
    {
        $baseQuery = DB::table('projects')
            ->leftJoin('users', 'projects.user_id', '=', 'users.id')
            ->leftJoin('students', 'students.user_id', '=', 'users.id')
            ->where(function ($q) {
                $q->where(function ($qq) {
                    $qq->whereRaw('LOWER(students.program) LIKE ?', ['%design%'])
                        ->whereRaw('LOWER(students.program) LIKE ?', ['%graph%']);
                })->orWhereRaw('LOWER(students.program) LIKE ?', ['%community%']);
            });

        $doneStatuses = ['termine', 'valide', 'rejete'];

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'en_cours' => (clone $baseQuery)->whereNotIn('projects.status', $doneStatuses)->count(),
            'termine' => (clone $baseQuery)->where('projects.status', 'termine')->count(),
            'valide' => (clone $baseQuery)->where('projects.status', 'valide')->count(),
            'rejete' => (clone $baseQuery)->where('projects.status', 'rejete')->count(),
        ];

        $assignments = (clone $baseQuery)
            ->select(
                'projects.id',
                'projects.title',
                'projects.category',
                'projects.deadline',
                'projects.status',
                'projects.created_at',
                'students.first_name',
                'students.last_name',
                'students.profile_photo',
                'students.program as formation',
                'users.email as student_email'
            )
            ->orderByDesc('projects.created_at')
            ->get();

        $normalized = $assignments->map(function ($work) {
            $program = mb_strtolower((string) ($work->formation ?? ''));
            $isDesignGraph = str_contains($program, 'design') && str_contains($program, 'graph');
            $isCommunity = str_contains($program, 'community');

            if ($isDesignGraph && $isCommunity) {
                $work->formation_group = 'Design Graphique et Community Management';
            } elseif ($isDesignGraph) {
                $work->formation_group = 'Design Graphique';
            } elseif ($isCommunity) {
                $work->formation_group = 'Community Management';
            } else {
                $work->formation_group = 'Autres';
            }

            // Regrouper par "projet" (même titre/catégorie/deadline)
            $work->project_group_key = implode('|', [
                (string) ($work->title ?? ''),
                (string) ($work->category ?? ''),
                (string) ($work->deadline ?? ''),
            ]);

            return $work;
        });

        $formationOrder = [
            'Design Graphique',
            'Community Management',
            'Design Graphique et Community Management',
        ];

        $grouped = $normalized
            ->whereIn('formation_group', $formationOrder)
            ->groupBy('formation_group')
            ->map(function ($items) {
                return $items
                    ->groupBy('project_group_key')
                    ->map(function ($projectItems) {
                        $first = $projectItems->first();
                        return [
                            'title' => $first->title,
                            'category' => $first->category,
                            'deadline' => $first->deadline,
                            'created_at' => $first->created_at,
                            'status' => $first->status,
                            'students' => $projectItems->values(),
                        ];
                    })
                    ->sortByDesc(function ($project) {
                        return $project['created_at'];
                    })
                    ->values();
            });

        // Assurer l'ordre des formations dans l'affichage
        $grouped = collect($formationOrder)
            ->mapWithKeys(function ($name) use ($grouped) {
                return [$name => $grouped->get($name, collect())];
            });

        $groupedAssignmentsDone = $grouped->map(function ($projects) use ($doneStatuses) {
            return $projects
                ->map(function ($project) use ($doneStatuses) {
                    $students = collect($project['students'] ?? [])->filter(function ($studentWork) use ($doneStatuses) {
                        return in_array($studentWork->status ?? null, $doneStatuses, true);
                    })->values();

                    if ($students->isEmpty()) {
                        return null;
                    }

                    $project['students'] = $students;
                    return $project;
                })
                ->filter()
                ->values();
        });

        $groupedAssignmentsTodo = $grouped->map(function ($projects) use ($doneStatuses) {
            return $projects
                ->map(function ($project) use ($doneStatuses) {
                    $students = collect($project['students'] ?? [])->filter(function ($studentWork) use ($doneStatuses) {
                        return !in_array($studentWork->status ?? null, $doneStatuses, true);
                    })->values();

                    if ($students->isEmpty()) {
                        return null;
                    }

                    $project['students'] = $students;
                    return $project;
                })
                ->filter()
                ->values();
        });

        return view('admin.projects.assigned', [
            'assignments' => $assignments,
            'stats' => $stats,
            'groupedAssignments' => $grouped,
            'groupedAssignmentsDone' => $groupedAssignmentsDone,
            'groupedAssignmentsTodo' => $groupedAssignmentsTodo,
        ]);
    }

    public function allDesignGraphique()
    {
        $selectedUserId = request()->query('user_id');

        $designQuery = DesignProject::query()
            ->whereHas('user.student', function ($query) {
                $query->whereRaw('LOWER(program) LIKE ?', ['%design%graph%'])
                    ->whereRaw('LOWER(program) NOT LIKE ?', ['%community%']);
            });

        $tpQuery = TP::query()
            ->whereHas('user.student', function ($query) {
                $query->whereRaw('LOWER(program) LIKE ?', ['%design%graph%'])
                    ->whereRaw('LOWER(program) NOT LIKE ?', ['%community%']);
            });

        if ($selectedUserId) {
            $designQuery->where('user_id', $selectedUserId);
            $tpQuery->where('user_id', $selectedUserId);
        }

        $stats = [
            'total' => (clone $designQuery)->count() + (clone $tpQuery)->count(),
            'pending' => (clone $designQuery)->where('status', 'pending')->count() + (clone $tpQuery)->where('status', 'pending')->count(),
            'to_send' => (clone $tpQuery)->where('status', 'to_send')->count(),
            'validated' => (clone $designQuery)->where('status', 'validated')->count() + (clone $tpQuery)->where('status', 'validated')->count(),
            'rejected' => (clone $designQuery)->where('status', 'rejected')->count() + (clone $tpQuery)->where('status', 'rejected')->count(),
        ];

        if (!$selectedUserId) {
            $profiles = DB::table('students')
                ->join('users', 'users.id', '=', 'students.user_id')
                ->whereRaw('LOWER(students.program) LIKE ?', ['%design%graph%'])
                ->whereRaw('LOWER(students.program) NOT LIKE ?', ['%community%'])
                ->select(
                    'users.id as user_id',
                    'users.name as user_name',
                    'users.email as user_email',
                    'students.first_name',
                    'students.last_name',
                    'students.profile_photo',
                    'students.program',
                    'students.country'
                )
                ->get()
                ->map(function ($student) {
                    $tpCount = DB::table('tp')->where('user_id', $student->user_id)->count();
                    $designCount = DB::table('design_projects')->where('user_id', $student->user_id)->count();
                    $student->projects_count = $tpCount + $designCount;

                    $tpPendingCount = DB::table('tp')
                        ->where('user_id', $student->user_id)
                        ->where('status', 'pending')
                        ->count();
                    $designPendingCount = DB::table('design_projects')
                        ->where('user_id', $student->user_id)
                        ->where('status', 'pending')
                        ->count();
                    $student->pending_count = $tpPendingCount + $designPendingCount;
                    return $student;
                })
                ->sortByDesc(function ($student) {
                    return (int) ($student->projects_count ?? 0);
                })
                ->values();

            return view('admin.projects.index', [
                'projects' => new LengthAwarePaginator([], 0, 15, 1, ['path' => request()->url(), 'query' => request()->query()]),
                'profiles' => $profiles,
                'selectedUserId' => null,
                'title' => 'Tous les projets Design Graphique',
                'type' => 'design-graphique',
                'status' => 'all',
                'stats' => $stats,
            ]);
        }

        // Fusionner les deux sources (DesignProject + TP), trier par date et paginer manuellement
        $designProjects = (clone $designQuery)
            ->with(['user', 'user.student', 'files'])
            ->get();

        $tpProjects = (clone $tpQuery)
            ->with(['user', 'user.student', 'files'])
            ->get();

        $allProjects = $tpProjects
            ->concat($designProjects)
            ->sortByDesc('created_at')
            ->values();

        $perPage = 15;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $items = $allProjects->slice(($page - 1) * $perPage, $perPage)->values();

        $projects = new LengthAwarePaginator(
            $items,
            $allProjects->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        return view('admin.projects.index', [
            'projects' => $projects,
            'profiles' => null,
            'selectedUserId' => $selectedUserId,
            'title' => 'Tous les projets Design Graphique',
            'type' => 'design-graphique',
            'status' => 'all',
            'stats' => $stats,
        ]);
    }

    // Méthodes pour les projets CM/SMM
    public function pendingCmSmm()
    {
        // Les TP CM sont dans la table 'projects' (pas design_projects ni tp_assignments)
        $baseQuery = DB::table('projects')
            ->join('users', 'projects.user_id', '=', 'users.id')
            ->join('students', 'users.id', '=', 'students.user_id')
            ->where(function ($query) {
                $query->whereRaw('LOWER(students.program) LIKE ?', ['%community%'])
                    ->whereRaw('LOWER(students.program) NOT LIKE ?', ['%design%']);
            });

        // Mapping des statuts: en_cours=assigned, termine=submitted, valide=validated, rejete=rejected
        $allProjects = (clone $baseQuery)
            ->select('projects.*', 'students.program')
            ->get();

        $stats = [
            'total' => $allProjects->count(),
            'pending' => $allProjects->where('status', 'termine')->count(),
            'to_send' => 0,
            'validated' => $allProjects->where('status', 'valide')->count(),
            'rejected' => $allProjects->where('status', 'rejete')->count(),
        ];

        // Filtrer uniquement les projets 'termine' (submitted)
        $projects = (clone $baseQuery)
            ->where('projects.status', 'termine')
            ->select(
                'projects.id',
                'projects.title',
                'projects.description',
                'projects.status',
                'projects.created_at',
                'projects.updated_at',
                'projects.deadline',
                'projects.category',
                'students.first_name as prenom',
                'students.last_name as nom',
                'students.profile_photo',
                'students.program as formation',
                'users.email as user_email'
            )
            ->orderByDesc('projects.created_at')
            ->paginate(15);

        // Mapper les statuts pour la vue
        $projects->getCollection()->transform(function ($project) {
            $project->status = 'submitted'; // termine devient submitted pour la vue
            return $project;
        });

        return view('admin.projects.index', [
            'projects' => $projects,
            'title' => 'Projets CM/SMM - En attente de validation',
            'type' => 'cm-smm',
            'status' => 'pending',
            'stats' => $stats,
        ]);
    }

    public function toSendCmSmm()
    {
        // Formulaire d'attribution: réutilise le flow admin.projets.to-send / admin.projets.send
        $students = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('students.status', 'active')
            ->select('students.*', 'users.email')
            ->get();

        // Normaliser les formations pour cohérence
        $students = $students->map(function ($student) {
            if ($student->program) {
                $programNormalizedKey = strtolower(str_replace([' ', '_', '-'], '', $student->program));
                $containsDesign = str_contains($programNormalizedKey, 'design');
                $containsCommunity = str_contains($programNormalizedKey, 'community');

                if ($containsDesign && $containsCommunity) {
                    $normalized = 'Design Graphique & Community Management';
                } else {
                    $normalized = match ($programNormalizedKey) {
                        'designgraphique' => 'Design Graphique',
                        'communitymanagement' => 'Community Management',
                        'gestioninformatique' => 'Gestion Informatique',
                        'intelligenceartificielle' => 'Intelligence Artificielle',
                        default => $student->program
                    };
                }
                $student->program_normalized = $normalized;
            } else {
                $student->program_normalized = 'Sans formation';
            }
            return $student;
        });

        // Stats globales (pour les cards en haut du formulaire)
        $stats = [
            'total_students' => $students->count(),
            'design_graphique' => $students->where('program_normalized', 'Design Graphique')->count(),
            'design_graphique_cm' => $students->where('program_normalized', 'Design Graphique & Community Management')->count(),
            'community_management' => $students->where('program_normalized', 'Community Management')->count(),
            'gestion_informatique' => $students->where('program_normalized', 'Gestion Informatique')->count(),
            'intelligence_artificielle' => $students->where('program_normalized', 'Intelligence Artificielle')->count(),
            'sans_formation' => $students->where('program_normalized', 'Sans formation')->count(),
        ];

        // Filtrer la liste affichée: Community Management uniquement (sans Design Graphique)
        $filteredStudents = $students
            ->where('program_normalized', 'Community Management')
            ->values();

        return view('admin.projets.to-send', [
            'students' => $filteredStudents,
            'all_students' => $filteredStudents,
            'stats' => $stats,
            'defaultFormation' => 'Community Management',
        ]);
    }

    public function allCmSmm()
    {
        // Utiliser la table projects pour les projets CM
        $baseQuery = DB::table('projects')
            ->join('users', 'projects.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->where(function ($q) {
                $q->where('students.program', 'like', '%community_management%')
                    ->orWhere('students.program', 'like', '%Community Management%');
            });

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'assigned' => (clone $baseQuery)->where('projects.status', 'en_cours')->count(),
            'submitted' => (clone $baseQuery)->where('projects.status', 'termine')->count(),
            'validated' => (clone $baseQuery)->where('projects.status', 'valide')->count(),
            'rejected' => (clone $baseQuery)->where('projects.status', 'rejete')->count(),
        ];

        // Récupérer tous les projets avec infos étudiants
        $allProjects = (clone $baseQuery)
            ->select(
                'projects.*',
                'students.id as student_id',
                'students.first_name as prenom',
                'students.last_name as nom',
                'students.profile_photo',
                'students.program as formation',
                'users.email as user_email',
                'users.id as user_id'
            )
            ->orderByDesc('projects.created_at')
            ->get();

        // Grouper par étudiant (user_id)
        $studentProjects = $allProjects->groupBy('user_id');

        // Créer la structure des profils
        $profiles = $studentProjects->map(function ($projects, $userId) {
            $firstProject = $projects->first();

            return [
                'user_id' => $userId,
                'student_id' => $firstProject->student_id,
                'prenom' => $firstProject->prenom,
                'nom' => $firstProject->nom,
                'email' => $firstProject->user_email,
                'profile_photo' => $firstProject->profile_photo,
                'formation' => $firstProject->formation,
                'total_projets' => $projects->count(),
                'projets_en_cours' => $projects->where('status', 'en_cours')->count(),
                'projets_soumis' => $projects->where('status', 'termine')->count(),
                'projets_valides' => $projects->where('status', 'valide')->count(),
                'projets_rejetes' => $projects->where('status', 'rejete')->count(),
                'projects' => $projects->groupBy('status'),
            ];
        })->values();

        return view('admin.projects.all-profiles', [
            'profiles' => $profiles,
            'title' => 'Tous les projets CM/SMM',
            'type' => 'cm-smm',
            'status' => 'all',
            'stats' => $stats,
        ]);
    }

    // Méthodes pour les projets Design & CM
    public function pendingDesignCm()
    {
        // 1. Récupérer les tp_assignments en attente
        $tpAssignments = DB::table('tp_assignments')
            ->leftJoin('students', 'tp_assignments.student_id', '=', 'students.id')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where(function ($q) {
                $q->where('students.program', 'like', '%Design Graphique%')
                    ->orWhere('students.program', 'like', '%design_graphique%');
            })
            ->where(function ($q) {
                $q->where('students.program', 'like', '%Community%')
                    ->orWhere('students.program', 'like', '%community%');
            })
            ->whereIn('tp_assignments.status', ['pending', 'submitted'])
            ->select(
                'tp_assignments.id',
                'tp_assignments.title',
                'tp_assignments.description',
                'tp_assignments.formation',
                'tp_assignments.status',
                'tp_assignments.created_at',
                'tp_assignments.updated_at',
                'students.first_name',
                'students.last_name',
                'students.first_name as prenom',
                'students.last_name as nom',
                'students.profile_photo',
                'students.program as student_program',
                'students.program as formation',
                'users.email as student_email',
                'users.email as user_email',
                DB::raw("'tp_assignments' as source_table")
            )
            ->get();

        // 2. Récupérer les TP de la table tp en attente pour le profil combiné
        $tpLegacy = DB::table('tp')
            ->leftJoin('users', 'tp.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->where(function ($q) {
                $q->where('students.program', 'like', '%Design Graphique%')
                    ->orWhere('students.program', 'like', '%design_graphique%');
            })
            ->where(function ($q) {
                $q->where('students.program', 'like', '%Community%')
                    ->orWhere('students.program', 'like', '%community%');
            })
            ->where('tp.status', 'pending')
            ->select(
                'tp.id',
                'tp.title',
                'tp.description',
                'tp.formation',
                'tp.status',
                'tp.created_at',
                'tp.updated_at',
                'students.first_name',
                'students.last_name',
                'students.first_name as prenom',
                'students.last_name as nom',
                'students.profile_photo',
                'students.program as student_program',
                'students.program as formation',
                'users.email as student_email',
                'users.email as user_email',
                DB::raw("'tp' as source_table")
            )
            ->get();

        // 3. Fusionner les deux collections
        $allProjects = $tpAssignments->concat($tpLegacy)->sortByDesc('created_at');

        // 4. Calculer les stats
        $baseQueryAssignments = DB::table('tp_assignments')
            ->leftJoin('students', 'tp_assignments.student_id', '=', 'students.id')
            ->where(function ($q) {
                $q->where('students.program', 'like', '%Design Graphique%')
                    ->orWhere('students.program', 'like', '%design_graphique%');
            })
            ->where(function ($q) {
                $q->where('students.program', 'like', '%Community%')
                    ->orWhere('students.program', 'like', '%community%');
            });

        $baseQueryTp = DB::table('tp')
            ->leftJoin('users', 'tp.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->where(function ($q) {
                $q->where('students.program', 'like', '%Design Graphique%')
                    ->orWhere('students.program', 'like', '%design_graphique%');
            })
            ->where(function ($q) {
                $q->where('students.program', 'like', '%Community%')
                    ->orWhere('students.program', 'like', '%community%');
            });

        $stats = [
            'total' => (clone $baseQueryAssignments)->count() + (clone $baseQueryTp)->count(),
            'pending' => (clone $baseQueryAssignments)->whereIn('tp_assignments.status', ['pending', 'submitted'])->count()
                       + (clone $baseQueryTp)->where('tp.status', 'pending')->count(),
            'validated' => (clone $baseQueryAssignments)->where('tp_assignments.status', 'validated')->count()
                         + (clone $baseQueryTp)->where('tp.status', 'validated')->count(),
            'rejected' => (clone $baseQueryAssignments)->where('tp_assignments.status', 'rejected')->count()
                        + (clone $baseQueryTp)->where('tp.status', 'rejected')->count(),
        ];

        // 5. Paginer manuellement
        $perPage = 15;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        $paginatedProjects = $allProjects->slice($offset, $perPage)->values();

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedProjects,
            $allProjects->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.projects.index', [
            'projects' => $paginator,
            'title' => 'Projets Design & CM - En attente de validation',
            'type' => 'design-cm',
            'status' => 'pending',
            'stats' => $stats,
        ]);
    }

    public function toSendDesignCm()
    {
        $students = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('students.status', 'active')
            ->where('students.program', 'design_graphique_community_management')
            ->select('students.*', 'users.email')
            ->get();

        // Mapper avec alias compatibles avec la vue
        $students = $students->map(function ($student) {
            $student->prenom = $student->first_name;
            $student->nom = $student->last_name;
            $student->user_email = $student->email;
            $student->program_normalized = 'Design Graphique & Community Management';
            $student->formation_normalized = 'Design Graphique & Community Management';
            $student->formation = 'Design Graphique & Community Management';
            return $student;
        });

        // Stats
        $stats = [
            'total_students' => $students->count(),
            'design_graphique' => 0,
            'design_graphique_cm' => $students->count(),
            'community_management' => 0,
            'gestion_informatique' => 0,
            'intelligence_artificielle' => 0,
            'sans_formation' => 0,
        ];

        return view('admin.travaux.to-send', [
            'students' => $students,
            'all_students' => $students,
            'stats' => $stats,
            'defaultFormation' => 'Design Graphique & Community Management',
        ]);
    }

    public function allDesignCm()
    {
        // Filtre flexible pour Design Graphique & Community Management
        $programFilter = function ($query) {
            $query->where(function ($q) {
                $q->where('students.program', 'design_graphique_community_management')
                    ->orWhere('students.program', 'LIKE', '%design%graphique%community%')
                    ->orWhere('students.program', 'LIKE', '%Design Graphique%Community%')
                    ->orWhere(function ($subQ) {
                        $subQ->where('students.program', 'LIKE', '%design%')
                            ->where('students.program', 'LIKE', '%community%');
                    });
            });
        };

        // 1. Récupérer les TP assignments
        $tpAssignments = DB::table('tp_assignments')
            ->leftJoin('students', 'tp_assignments.student_id', '=', 'students.id')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where($programFilter)
            ->select(
                'tp_assignments.id',
                'tp_assignments.title',
                'tp_assignments.description',
                'tp_assignments.status',
                'tp_assignments.created_at',
                'tp_assignments.updated_at',
                'students.id as student_id',
                'students.first_name as prenom',
                'students.last_name as nom',
                'students.profile_photo',
                'students.program as formation',
                'users.email as user_email',
                'users.id as user_id',
                DB::raw("'tp_assignments' as source_table")
            )
            ->get();

        // 2. Récupérer les projets soumis par les étudiants (table projects avec statut français)
        $projects = DB::table('projects')
            ->leftJoin('users', 'projects.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->where($programFilter)
            ->select(
                'projects.id',
                'projects.title',
                'projects.description',
                'projects.status',
                'projects.created_at',
                'projects.updated_at',
                'students.id as student_id',
                'students.first_name as prenom',
                'students.last_name as nom',
                'students.profile_photo',
                'students.program as formation',
                'users.email as user_email',
                'users.id as user_id',
                DB::raw("'projects' as source_table")
            )
            ->get();

        // Mapper les statuts français vers anglais pour homogénéité
        $projects = $projects->map(function ($project) {
            $statusMap = [
                'en_cours' => 'assigned',
                'termine' => 'submitted',
                'valide' => 'validated',
                'rejete' => 'rejected',
            ];
            $project->status = $statusMap[$project->status] ?? $project->status;
            return $project;
        });

        // 3. Récupérer les design_projects
        $designProjects = DB::table('design_projects')
            ->leftJoin('users', 'design_projects.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->where($programFilter)
            ->select(
                'design_projects.id',
                'design_projects.title',
                'design_projects.description',
                'design_projects.status',
                'design_projects.created_at',
                'design_projects.updated_at',
                'students.id as student_id',
                'students.first_name as prenom',
                'students.last_name as nom',
                'students.profile_photo',
                'students.program as formation',
                'users.email as user_email',
                'users.id as user_id',
                DB::raw("'design_projects' as source_table")
            )
            ->get();

        // Mapper les statuts de design_projects
        $designProjects = $designProjects->map(function ($project) {
            $statusMap = [
                'pending' => 'submitted',
                'validated' => 'validated',
                'rejected' => 'rejected',
                'draft' => 'assigned',
            ];
            $project->status = $statusMap[$project->status] ?? $project->status;
            return $project;
        });

        // 4. Récupérer les TP créés par les étudiants (table tp)
        $tpLegacy = DB::table('tp')
            ->leftJoin('users', 'tp.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->where($programFilter)
            ->where('tp.title', 'NOT LIKE', '%rapport%')
            ->select(
                'tp.id',
                'tp.title',
                'tp.description',
                'tp.status',
                'tp.created_at',
                'tp.updated_at',
                'students.id as student_id',
                'students.first_name as prenom',
                'students.last_name as nom',
                'students.profile_photo',
                'students.program as formation',
                'users.email as user_email',
                'users.id as user_id',
                DB::raw("'tp' as source_table")
            )
            ->get();

        // Fusionner tous les projets
        $allProjects = $tpAssignments->concat($projects)->concat($designProjects)->concat($tpLegacy);

        // Calculer les statistiques
        $stats = [
            'total' => $allProjects->count(),
            'assigned' => $allProjects->where('status', 'assigned')->count(),
            'submitted' => $allProjects->where('status', 'submitted')->count(),
            'validated' => $allProjects->where('status', 'validated')->count(),
            'rejected' => $allProjects->where('status', 'rejected')->count(),
        ];

        // Grouper par étudiant (user_id)
        $studentProjects = $allProjects->groupBy('user_id');

        // Créer la structure des profils
        $profiles = $studentProjects->map(function ($projects, $userId) {
            $firstProject = $projects->first();

            return [
                'user_id' => $userId,
                'student_id' => $firstProject->student_id,
                'prenom' => $firstProject->prenom,
                'nom' => $firstProject->nom,
                'email' => $firstProject->user_email,
                'profile_photo' => $firstProject->profile_photo,
                'formation' => $firstProject->formation,
                'total_projets' => $projects->count(),
                'projets_en_cours' => $projects->where('status', 'assigned')->count(),
                'projets_soumis' => $projects->where('status', 'submitted')->count(),
                'projets_valides' => $projects->where('status', 'validated')->count(),
                'projets_rejetes' => $projects->where('status', 'rejected')->count(),
                'projects' => $projects->groupBy('status'),
            ];
        })->values();

        return view('admin.projects.all-profiles-tp', [
            'profiles' => $profiles,
            'title' => 'Tous les projets Design & CM',
            'type' => 'design-cm',
            'status' => 'all',
            'stats' => $stats,
        ]);
    }
}
