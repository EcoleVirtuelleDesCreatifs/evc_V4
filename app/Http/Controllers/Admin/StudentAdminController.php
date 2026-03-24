<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\ProfilePhotoHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class StudentAdminController extends Controller
{
    /**
     * Lister les étudiants avec paramètre de requête formation
     * Redirige vers listByFormation avec le bon format de slug
     */
    public function index(Request $request)
    {
        // Récupérer le paramètre formation depuis la query string
        $formation = $request->query('formation');

        // Si pas de formation spécifiée, afficher tous les étudiants
        if (!$formation) {
            // Récupérer tous les étudiants
            $students = DB::table('students')
                ->orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")
                ->orderBy('id', 'desc')
                ->get();

            // Récupérer les user_id depuis users via email
            $studentEmails = $students->pluck('email')->toArray();
            $userIds = [];
            if (!empty($studentEmails)) {
                $userIds = DB::table('users')
                    ->whereIn('email', $studentEmails)
                    ->pluck('id', 'email')
                    ->toArray();
            }

            // Récupérer les compteurs de TP
            $tpCounts = [];
            if (!empty($userIds) && Schema::hasTable('tp')) {
                $tpCounts = DB::table('tp')
                    ->select('user_id', DB::raw('COUNT(*) as total'))
                    ->whereIn('user_id', array_values($userIds))
                    ->groupBy('user_id')
                    ->pluck('total', 'user_id')
                    ->toArray();
            }

            // Préparer les données des étudiants
            $studentsData = [];
            $totalProgression = 0;
            $activeCount = 0;

            foreach ($students as $student) {
                $userId = $userIds[$student->email] ?? null;
                $tpCount = $tpCounts[$userId] ?? 0;

                // Calculer la progression (basée sur TP/20 comme référence)
                $progression = min(($tpCount / 20) * 100, 100);
                $totalProgression += $progression;

                if ($student->status === 'active') {
                    $activeCount++;
                }

                // Photo de profil - multiples chemins possibles
                $photoUrl = null;
                if (!empty($student->profile_photo)) {
                    $photoUrl = ProfilePhotoHelper::getUrl($student->profile_photo);
                }

                // Calculer les jours restants avant expiration
                $daysRemaining = null;
                $isExpired = false;
                $expirationDate = null;

                // Essayer d'abord avec expiration_date de la table students
                if (!empty($student->expiration_date)) {
                    try {
                        $expirationDate = \Carbon\Carbon::parse($student->expiration_date);
                    } catch (\Exception $e) {
                        $expirationDate = null;
                    }
                }

                // Fallback : calculer depuis created_at + 4 mois
                if (!$expirationDate && !empty($student->created_at)) {
                    try {
                        $createdAt = \Carbon\Carbon::parse($student->created_at);
                        $expirationDate = $createdAt->copy()->addMonths(4);
                    } catch (\Exception $e) {
                        $expirationDate = null;
                    }
                }

                // Calculer les jours restants
                if ($expirationDate) {
                    $now = \Carbon\Carbon::now();

                    if ($expirationDate->isFuture()) {
                        $daysRemaining = (int) $now->diffInDays($expirationDate);
                        $isExpired = false;
                    } else {
                        $daysRemaining = 0;
                        $isExpired = true;
                    }
                }

                // Déterminer le statut : si expiré, le compte doit être inactif, sinon actif
                $status = $student->status;

                // Si le compte est expiré ET actuellement actif → mettre en inactif
                if ($isExpired && $status === 'active') {
                    DB::table('students')
                        ->where('id', $student->id)
                        ->update([
                            'status' => 'inactive',
                            'updated_at' => now()
                            // Pas de deactivation_reason ni deactivated_at
                        ]);
                    $status = 'inactive';
                }

                // Si le compte n'est PAS expiré ET actuellement inactif (sans raison) → réactiver
                if (!$isExpired && $status === 'inactive' && empty($student->deactivation_reason)) {
                    DB::table('students')
                        ->where('id', $student->id)
                        ->update([
                            'status' => 'active',
                            'updated_at' => now()
                        ]);
                    $status = 'active';
                }

                $studentsData[] = [
                    'id' => $student->id,
                    'student_id' => $student->student_id,
                    'prenom' => $student->first_name,
                    'nom' => $student->last_name,
                    'email' => $student->email,
                    'pays' => $student->country ?? 'N/A',
                    'photo_url' => $photoUrl,
                    'created_at' => $student->created_at,
                    'tp_realises' => $tpCount,
                    'tp_count' => $tpCount,
                    'progression' => round($progression),
                    'status' => $status,
                    'days_remaining' => $daysRemaining,
                    'is_expired' => $isExpired,
                    'expiration_date' => $expirationDate,
                ];
            }

            $avgProgression = count($studentsData) > 0 ? round($totalProgression / count($studentsData)) : 0;

            return view('admin.students.by-formation', [
                'data' => [
                    'formation_name' => 'Toutes les formations',
                    'formation_slug' => 'all',
                    'students' => $studentsData,
                    'stats' => [
                        'total' => count($studentsData),
                        'active' => $activeCount,
                        'avg_progression' => $avgProgression
                    ]
                ]
            ]);
        }

        // Map des formations query string vers slug URL
        $formationMap = [
            'design_graphique' => 'design-graphique',
            'community_management' => 'community-manager',
            'intelligence_artificielle' => 'intelligence-artificielle',
            'gestion_informatique' => 'gestion-informatique',
        ];

        // Vérifier que la formation existe
        if (!isset($formationMap[$formation])) {
            abort(404, 'Formation non trouvée');
        }

        // Rediriger vers la route existante avec le bon format
        return redirect()->route('admin.students.by-formation', ['formation' => $formationMap[$formation]]);
    }

    /**
     * Lister les étudiants d'une formation (slug): design-graphique, community-manager, intelligence-artificielle, gestion-informatique
     */
    public function listByFormation(Request $request, string $formation)
    {
        // Map slug -> label et clés internes possibles
        $formationMap = [
            'design-graphique' => ['label' => 'Design Graphique', 'keys' => ['Design Graphique', 'design_graphique', 'infographie', 'design-graphique', 'Infographie et Design Graphique', 'Infographie & Design Graphique', 'design graphique']],
            'community-manager' => ['label' => 'Community Management', 'keys' => ['Community Management', 'Community Manager', 'community_management', 'community-manager', 'community manager']],
            'community-management' => ['label' => 'Community Management', 'keys' => ['Community Management', 'Community Manager', 'community_management', 'community-manager', 'community-management', 'community manager']],
            'design-graphique-community-manager' => ['label' => 'Design Graphique & Community Manager', 'keys' => ['Design Graphique & Community Manager', 'Design Graphique & Community Management', 'design_graphique_community_manager', 'design_graphique_community_management', 'design-graphique-community-manager']],
            'intelligence-artificielle' => ['label' => 'Intelligence Artificielle', 'keys' => ['Intelligence Artificielle', 'intelligence_artificielle', 'intelligence-artificielle']],
            'gestion-informatique' => ['label' => 'Gestion Informatique', 'keys' => ['Gestion Informatique', 'gestion_informatique', 'informatique', 'gestion-informatique']],
        ];
        abort_unless(isset($formationMap[$formation]), 404);

        $label = $formationMap[$formation]['label'];
        $keys = $formationMap[$formation]['keys'];

        // Requêter directement depuis la table students (TOUS les statuts)
        $students = collect();
        if (Schema::hasTable('students')) {
            $query = DB::table('students')
                ->select('students.*');
            // NE PAS filtrer par statut - afficher actifs ET inactifs

            // Filtrer par formation
            // - Cas standard : program OU specialization correspond à la formation
            // - Cas DG+CM : certains profils peuvent avoir program=DG et specialization=CM (ou inversement)
            if ($formation === 'design-graphique-community-manager') {
                $dgcmKeys = $formationMap['design-graphique-community-manager']['keys'];

                // DEMANDE MÉTIER: la liste DG+CM doit refléter le choix de formation fait à la pré-inscription.
                // Ce choix est sauvegardé dans students.program.
                $dgcmKeysNormalized = array_values(array_unique(array_map(function ($v) {
                    return strtolower(trim((string) $v));
                }, $dgcmKeys)));

                $query->where(function ($q) use ($dgcmKeysNormalized) {
                    $q->whereIn(DB::raw('LOWER(TRIM(program))'), $dgcmKeysNormalized);
                });
            } else {
                // Cas spécial (DEMANDE MÉTIER): la page Design Graphique doit refléter le choix de formation
                // fait à la pré-inscription. Or ce choix est sauvegardé dans students.program.
                if ($formation === 'design-graphique') {
                    $dgKeysNormalized = array_values(array_unique(array_map(function ($v) {
                        return strtolower(trim((string) $v));
                    }, $formationMap['design-graphique']['keys'])));

                    $query->where(function ($q) use ($dgKeysNormalized) {
                        $q->whereIn(DB::raw('LOWER(TRIM(program))'), $dgKeysNormalized)
                            ->orWhere(function ($q2) use ($dgKeysNormalized) {
                                $q2->where(function ($q3) {
                                    $q3->whereNull('program')
                                        ->orWhereRaw("TRIM(program) = ''");
                                })->whereIn(DB::raw('LOWER(TRIM(specialization))'), $dgKeysNormalized);
                            });
                    });
                } else {
                    $query->where(function ($q) use ($keys) {
                        $q->whereIn('program', $keys)
                            ->orWhereIn('specialization', $keys);
                    });
                }
            }

            // Cas particulier: /admin/etudiants/design-graphique doit exclure DG+CM
            // (certains profils DG+CM ont une specialization "Design Graphique" et remontent sinon)
            if ($formation === 'design-graphique') {
                $dgcmKeys = $formationMap['design-graphique-community-manager']['keys'];
                $dgKeys = $formationMap['design-graphique']['keys'];
                $cmKeys = $formationMap['community-management']['keys'];

                $dgcmKeysNormalized = array_values(array_unique(array_map(function ($v) {
                    return strtolower(trim((string) $v));
                }, $dgcmKeys)));
                $dgKeysNormalized = array_values(array_unique(array_map(function ($v) {
                    return strtolower(trim((string) $v));
                }, $dgKeys)));
                $cmKeysNormalized = array_values(array_unique(array_map(function ($v) {
                    return strtolower(trim((string) $v));
                }, $cmKeys)));

                $query->where(function ($q) use ($dgcmKeysNormalized) {
                    $q->whereNull('program')
                        ->orWhereNotIn(DB::raw('LOWER(TRIM(program))'), $dgcmKeysNormalized);
                });
                $query->where(function ($q) use ($dgcmKeysNormalized) {
                    $q->whereNull('specialization')
                        ->orWhereNotIn(DB::raw('LOWER(TRIM(specialization))'), $dgcmKeysNormalized);
                });

                // Exclure aussi les profils Community Management (évite qu'un CM avec un champ DG remonte ici)
                $query->where(function ($q) use ($cmKeysNormalized) {
                    $q->whereNull('program')
                        ->orWhereNotIn(DB::raw('LOWER(TRIM(program))'), $cmKeysNormalized);
                });
                $query->where(function ($q) use ($cmKeysNormalized) {
                    $q->whereNull('specialization')
                        ->orWhereNotIn(DB::raw('LOWER(TRIM(specialization))'), $cmKeysNormalized);
                });

                // Exclure aussi les profils DG+CM stockés sous forme de combinaison
                $query->where(function ($q) use ($dgKeysNormalized, $cmKeysNormalized) {
                    $q->whereNot(function ($q2) use ($dgKeysNormalized, $cmKeysNormalized) {
                        $q2->whereIn(DB::raw('LOWER(TRIM(program))'), $dgKeysNormalized)
                            ->whereIn(DB::raw('LOWER(TRIM(specialization))'), $cmKeysNormalized);
                    })->whereNot(function ($q2) use ($dgKeysNormalized, $cmKeysNormalized) {
                        $q2->whereIn(DB::raw('LOWER(TRIM(program))'), $cmKeysNormalized)
                            ->whereIn(DB::raw('LOWER(TRIM(specialization))'), $dgKeysNormalized);
                    });
                });
            }

            // Trier par statut (actifs d'abord) puis par id
            $students = $query->orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")
                ->orderBy('id', 'desc')
                ->get();
        }

        // Récupérer les user_id depuis users via email pour les TPs
        $studentEmails = $students->pluck('email')->toArray();
        $userIds = [];
        if (!empty($studentEmails)) {
            $userIds = DB::table('users')
                ->whereIn('email', $studentEmails)
                ->pluck('id', 'email')
                ->toArray();
        }

        // Récupérer les compteurs de TP pour tous les étudiants
        $tpCounts = [];
        if (!empty($userIds) && Schema::hasTable('tp')) {
            $tpCounts = DB::table('tp')
                ->select('user_id', DB::raw('COUNT(*) as total'))
                ->whereIn('user_id', array_values($userIds))
                ->groupBy('user_id')
                ->pluck('total', 'user_id')
                ->toArray();
        }

        // Formatter pour la vue existante
        $rows = $students->map(function ($s) use ($tpCounts, $userIds) {
            // Récupérer le user_id correspondant
            $userId = $userIds[$s->email] ?? null;
            $tpCount = $userId ? ($tpCounts[$userId] ?? 0) : 0;

            $userRecord = null;
            if ($userId) {
                $userRecord = DB::table('users')->where('id', $userId)->first();
            }

            // Déterminer une date d'inscription fiable (cohérente avec la page edit)
            // Règle: prendre la plus ancienne date disponible (évite un students.created_at récent qui fausse le compteur)
            $registrationCandidates = [];
            if (Schema::hasColumn('students', 'registration_date') && !empty($s->registration_date)) {
                $registrationCandidates[] = $s->registration_date;
            }
            if ($userRecord && !empty($userRecord->created_at)) {
                $registrationCandidates[] = $userRecord->created_at;
            }
            if (!empty($s->created_at)) {
                $registrationCandidates[] = $s->created_at;
            }

            $registrationDate = null;
            if (!empty($registrationCandidates)) {
                $registrationDate = collect($registrationCandidates)
                    ->map(function ($d) {
                        try {
                            return \Carbon\Carbon::parse($d);
                        } catch (\Exception $e) {
                            return null;
                        }
                    })
                    ->filter()
                    ->sort()
                    ->first();
            }

            // Calculer la progression
            $totalTpRequis = 20;
            $progression = $tpCount > 0 ? min(100, round(($tpCount / $totalTpRequis) * 100)) : 0;

            // Photo de profil - Même logique que profile()
            $photoUrl = null;
            if (!empty($s->profile_photo)) {
                // Vérifier si c'est un chemin absolu
                if (str_starts_with($s->profile_photo, 'http://') || str_starts_with($s->profile_photo, 'https://')) {
                    $photoUrl = $s->profile_photo;
                }
                // Vérifier si c'est un chemin relatif
                elseif (
                    str_starts_with($s->profile_photo, 'uploads/') ||
                    str_starts_with($s->profile_photo, 'photos_preregistrations/') ||
                    str_starts_with($s->profile_photo, 'photos/')
                ) {
                    // Essayer d'abord storage/app/public/
                    $storagePath = storage_path('app/public/' . $s->profile_photo);
                    if (file_exists($storagePath)) {
                        $photoUrl = ProfilePhotoHelper::getUrl($s->profile_photo);
                    } else {
                        // Sinon essayer directement public/
                        $photoUrl = ProfilePhotoHelper::getUrl($s->profile_photo);
                    }
                }
                // Sinon, considérer que c'est juste le nom de fichier
                else {
                    $photoUrl = ProfilePhotoHelper::getUrl($s->profile_photo);
                }
            }

            // Calculer les jours restants avant expiration
            $daysRemaining = null;
            $isExpired = false;
            $expirationDate = null;

            // Durée d'accès par formation (fallback quand expiration_date n'est pas stockée)
            $durationMonths = 4;
            $sevenMonthsPrograms = [
                'design_graphique_community_management',
                'design_graphique_community_manager',
                'design-graphique-community-manager',
            ];
            if (in_array(($s->program ?? null), $sevenMonthsPrograms, true) || in_array(($s->specialization ?? null), $sevenMonthsPrograms, true)) {
                $durationMonths = 7;
            }

            // Calculer l'expiration depuis inscription + durée (4/7 mois)
            $registrationCarbon = null;
            $computedExpiration = null;
            if (!empty($registrationDate)) {
                try {
                    $registrationCarbon = \Carbon\Carbon::parse($registrationDate);
                    $computedExpiration = $registrationCarbon->copy()->addMonths($durationMonths);
                } catch (\Exception $e) {
                    $registrationCarbon = null;
                    $computedExpiration = null;
                }
            }

            // Lire l'expiration stockée (potentiellement prolongations)
            $storedExpiration = null;
            if (!empty($s->expiration_date)) {
                try {
                    $storedExpiration = \Carbon\Carbon::parse($s->expiration_date);
                } catch (\Exception $e) {
                    $storedExpiration = null;
                }
            }

            // Détecter une expiration auto obsolète basée sur users.created_at (date de création du compte)
            $userCreatedAtCarbon = null;
            if ($userRecord && !empty($userRecord->created_at)) {
                try {
                    $userCreatedAtCarbon = \Carbon\Carbon::parse($userRecord->created_at);
                } catch (\Exception $e) {
                    $userCreatedAtCarbon = null;
                }
            }

            $userBasedExpiration = null;
            if ($userCreatedAtCarbon) {
                $userBasedExpiration = $userCreatedAtCarbon->copy()->addMonths($durationMonths);
            }

            $shouldIgnoreStored = false;
            if ($storedExpiration && $userBasedExpiration && $registrationCarbon) {
                // Si l'expiration stockée correspond au calcul basé sur users.created_at
                // et que la vraie date d'inscription est différente, alors expiration_date est obsolète.
                if ($storedExpiration->isSameDay($userBasedExpiration) && !$registrationCarbon->isSameDay($userCreatedAtCarbon)) {
                    $shouldIgnoreStored = true;
                }
            }

            // Détecter une expiration auto erronée basée sur "maintenant + durée" (affiche 120 jours identiques)
            if (!$shouldIgnoreStored && $storedExpiration && $computedExpiration) {
                $nowBasedExpiration = \Carbon\Carbon::now()->addMonths($durationMonths);
                if ($storedExpiration->isSameDay($nowBasedExpiration) && !$storedExpiration->isSameDay($computedExpiration)) {
                    $shouldIgnoreStored = true;
                }
            }

            if ($computedExpiration && $storedExpiration) {
                if ($shouldIgnoreStored) {
                    $expirationDate = $computedExpiration;
                } else {
                    // Prolongation manuelle = garder la plus tardive
                    $expirationDate = $storedExpiration->greaterThan($computedExpiration) ? $storedExpiration : $computedExpiration;
                }
            } else {
                $expirationDate = $storedExpiration ?: $computedExpiration;
            }

            // Calculer les jours restants
            if ($expirationDate) {
                $now = \Carbon\Carbon::now();

                $daysRemaining = (int) $now->diffInDays($expirationDate, false);
                $isExpired = $daysRemaining < 0;
            }

            // Déterminer le statut : si expiré, le compte doit être inactif, sinon actif
            $status = $s->status ?? 'active';

            // Si le compte est expiré ET actuellement actif → mettre en inactif
            if ($isExpired && $status === 'active') {
                DB::table('students')
                    ->where('id', $s->id)
                    ->update([
                        'status' => 'inactive',
                        'updated_at' => now()
                        // Pas de deactivation_reason ni deactivated_at
                    ]);
                $status = 'inactive';
            }

            // Si le compte n'est PAS expiré ET actuellement inactif (sans raison) → réactiver
            if (!$isExpired && $status === 'inactive') {
                // Vérifier qu'il n'y a pas de raison de désactivation manuelle
                $hasManualDeactivation = DB::table('students')
                    ->where('id', $s->id)
                    ->whereNotNull('deactivation_reason')
                    ->exists();

                if (!$hasManualDeactivation) {
                    DB::table('students')
                        ->where('id', $s->id)
                        ->update([
                            'status' => 'active',
                            'updated_at' => now()
                        ]);
                    $status = 'active';
                }
            }

            return [
                'id' => $s->id,
                'student_id' => $s->id,
                'user_id' => $userId,
                'email' => $s->email,
                'prenom' => $s->first_name ?? '',
                'nom' => $s->last_name ?? '',
                'phone' => $s->phone ?? '',
                'pays' => $s->country ?? '',
                'created_at' => $registrationDate,
                'duration_months' => $durationMonths,
                'tp_count' => $tpCount,
                'progression' => $progression,
                'photo_url' => $photoUrl,
                'status' => $status,
                'days_remaining' => $daysRemaining,
                'is_expired' => $isExpired,
                'expiration_date' => $expirationDate,
            ];
        })->values();

        // Calculer les statistiques
        $avgProgression = $rows->count() > 0 ? round($rows->avg('progression')) : 0;
        $activeCount = $rows->where('status', 'active')->count();
        $inactiveCount = $rows->where('status', 'inactive')->count();

        $data = [
            'formation' => $formation,
            'formation_name' => $label,
            'students' => $rows,
            'stats' => [
                'total' => $rows->count(),
                'active' => $activeCount,
                'inactive' => $inactiveCount,
                'avg_progression' => $avgProgression,
            ],
        ];

        return view('admin.students.by-formation', compact('data'));
    }

    /**
     * Profil étudiant complet (admin) - Affiche toutes les infos depuis students, tp, design_projects, paiement
     */
    public function profile(int $id)
    {
        // Certains écrans admin passent un user_id (ex: depuis un projet).
        // Dans ce cas, forcer la résolution par users.id pour éviter les collisions avec students.id.
        if (request()->get('source') === 'user') {
            $user = DB::table('users')->where('id', $id)->first();
            $student = $user ? DB::table('students')->where('user_id', $user->id)->first() : null;
        } else {
            // Essayer d'abord comme student_id
            $student = DB::table('students')->where('id', $id)->first();

            if ($student) {
                // Récupérer l'utilisateur via user_id
                $user = DB::table('users')->where('id', $student->user_id)->first();
            } else {
                // Sinon essayer comme user_id
                $user = DB::table('users')->where('id', $id)->first();
                if ($user) {
                    $student = DB::table('students')->where('user_id', $user->id)->first();
                }
            }
        }

        abort_unless($user, 404, 'Utilisateur non trouvé');

        if (!$student) {
            // Fallback: utiliser les données de users si pas dans students
            $student = (object) [
                'id' => null,
                'first_name' => $user->first_name ?? '',
                'last_name' => $user->last_name ?? '',
                'email' => $user->email,
                'phone' => $user->phone ?? '',
                'country' => $user->country ?? '',
                'city' => $user->city ?? '',
                'profile_photo' => $user->profile_photo ?? null,
                'status' => 'active',
                'program' => $user->formation_souhaitee ?? '',
                'level' => '',
                'student_id' => '',
                'created_at' => $user->created_at,
            ];
        }

        // Photo de profil - gestion de tous les chemins possibles
        $photoUrl = ProfilePhotoHelper::getUrlOrDefault($student->profile_photo ?? null);

        // Récupérer les TPs de l'étudiant
        $tps = [];
        if (Schema::hasTable('tp')) {
            $tps = DB::table('tp')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Charger les fichiers TP (si dispo) pour galerie images
            if ($tps->isNotEmpty() && Schema::hasTable('tp_files')) {
                $tpIds = $tps->pluck('id')->map(fn ($v) => (int) $v)->filter()->values()->all();
                if (!empty($tpIds)) {
                    $filesByTp = DB::table('tp_files')
                        ->whereIn('tp_id', $tpIds)
                        ->orderBy('created_at', 'desc')
                        ->get()
                        ->groupBy('tp_id');

                    foreach ($tps as $tp) {
                        $tp->tp_files = $filesByTp[$tp->id] ?? collect();
                    }
                }
            }
        }

        // Statistiques des TPs
        $totalTp = $tps->count();
        $tpValides = $tps->where('status', 'validated')->count();
        $tpEnCours = $tps->where('status', 'pending')->count();
        $tpRejetes = $tps->where('status', 'rejected')->count();

        // Récupérer les projets design
        $designProjects = [];
        if (Schema::hasTable('design_projects')) {
            $designProjects = DB::table('design_projects')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Charger les fichiers pour chaque projet depuis design_project_files
            if (Schema::hasTable('design_project_files')) {
                foreach ($designProjects as $project) {
                    $files = DB::table('design_project_files')
                        ->where('project_id', $project->id)
                        ->orderBy('created_at', 'desc')
                        ->get();
                    // Ajouter les fichiers au projet
                    $project->project_files = $files;
                }
            }
        }

        // Récupérer les paiements depuis la nouvelle table payments
        $paiements = collect();
        $factures = collect();
        $totalPaye = 0;
        // Total formation par défaut (système par tranche): 50 000 (1ère tranche) + 27 000 (2ème tranche)
        $totalFactures = 77000;
        // Cas spécial: Design Graphique & Community Management = 165 000 FCFA
        // Supporte différentes variantes (program, specialization, slug)
        $formationKey = (string) ($student->program ?? ($student->specialization ?? ''));
        $formationNormalized = strtolower(str_replace([' ', '_', '-', '&'], '', $formationKey));
        $containsDesign = str_contains($formationNormalized, 'design');
        $containsCommunity = str_contains($formationNormalized, 'community');
        if (($containsDesign && $containsCommunity) || $formationNormalized === 'designgraphiquecommunitymanagement') {
            $totalFactures = 165000;
        }
        if ($containsCommunity && !$containsDesign) {
            $totalFactures = 107000;
        }
        if ($containsDesign && !$containsCommunity) {
            $totalFactures = 80000;
        }
        $soldeRestant = 0;

        // Récupérer la pré-inscription de l'étudiant pour ses paiements
        $preRegistration = null;
        if (Schema::hasTable('pre_registrations')) {
            $preRegistration = DB::table('pre_registrations')
                ->where('email', $student->email)
                ->first();
        }

        if ($preRegistration && Schema::hasTable('payments')) {
            $paiements = DB::table('payments')
                ->where('pre_registration_id', $preRegistration->id)
                ->orderBy('installment_number', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();

            // Calculer le total payé (uniquement les paiements completed)
            $totalPaye = $paiements->where('status', 'completed')->sum('amount');

            // Calculer le solde restant
            $soldeRestant = max(0, $totalFactures - $totalPaye);
        }

        // Anciens paiements (table paiements - pour compatibilité)
        if ($paiements->isEmpty() && Schema::hasTable('paiements')) {
            $anciensPaiements = DB::table('paiements')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($anciensPaiements->isNotEmpty()) {
                $paiements = $anciensPaiements;
                $totalPaye = $anciensPaiements->where('statut', 'validé')->sum('montant');

                // Récupérer les factures si la table existe
                if (Schema::hasTable('factures')) {
                    $factures = DB::table('factures')
                        ->where('user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->get();
                    $totalFactures = $factures->sum('montant');
                    $soldeRestant = max(0, $totalFactures - $totalPaye);
                }
            }
        }

        // Récupérer les données du profil CVthèque
        $cvthequeProfile = null;
        if (Schema::hasTable('cvtheque_profiles')) {
            $cvthequeProfile = DB::table('cvtheque_profiles')
                ->where('user_id', $user->id)
                ->first();
        }

        // Calculer la progression (basée sur les TPs validés)
        $totalTpRequis = 20; // Nombre de TPs requis pour 100%
        $progression = $totalTp > 0 ? min(100, round(($tpValides / $totalTpRequis) * 100)) : 0;

        // Préparer les données pour la vue
        $data = [
            'student' => [
                'id' => $user->id,
                'student_id' => $student->student_id ?? '',
                'email' => $student->email,
                'prenom' => $student->first_name ?? '—',
                'nom' => $student->last_name ?? '—',
                'phone' => $student->phone ?? '',
                'whatsapp' => $student->whatsapp ?? '',
                'ville' => $student->city ?? '—',
                'pays' => $student->country ?? '—',
                'quartier' => $student->quartier ?? '',
                'date_of_birth' => $student->date_of_birth ?? '',
                'gender' => $student->gender ?? '',
                'created_at' => $student->created_at,
                'formation' => $student->program ?? '—',
                'level' => $student->level ?? '',
                'specialization' => $student->specialization ?? '',
                'status' => $student->status ?? 'active',
                'photo_url' => $photoUrl,
                'gpa' => $student->gpa ?? null,
                'credits_earned' => $student->credits_earned ?? 0,
                'years_experience' => $student->years_experience ?? null,
                'industry_sector' => $student->industry_sector ?? '',
            ],
            'cvtheque' => $cvthequeProfile ? [
                'professional_title' => $cvthequeProfile->professional_title ?? '',
                'bio' => $cvthequeProfile->bio ?? '',
                'professional_summary' => $cvthequeProfile->professional_summary ?? '',
                'skills' => $cvthequeProfile->skills ?? '',
                'software_skills' => $cvthequeProfile->software_skills ?? '',
                'technical_skills' => $cvthequeProfile->technical_skills ?? '',
                'languages' => $cvthequeProfile->languages ?? '',
                'experience_years' => $cvthequeProfile->experience_years ?? 0,
                'years_experience' => $cvthequeProfile->years_experience ?? 0,
                'current_position' => $cvthequeProfile->current_position ?? '',
                'current_company' => $cvthequeProfile->current_company ?? '',
                'linkedin_url' => $cvthequeProfile->linkedin_url ?? '',
                'linkedin_profile' => $cvthequeProfile->linkedin_profile ?? '',
                'portfolio_url' => $cvthequeProfile->portfolio_url ?? '',
                'website' => $cvthequeProfile->website ?? '',
                'github_url' => $cvthequeProfile->github_url ?? '',
                'behance_url' => $cvthequeProfile->behance_url ?? '',
                'behance_profile' => $cvthequeProfile->behance_profile ?? '',
                'dribbble_profile' => $cvthequeProfile->dribbble_profile ?? '',
                'professional_email' => $cvthequeProfile->professional_email ?? '',
                'phone' => $cvthequeProfile->phone ?? '',
                'availability' => $cvthequeProfile->availability ?? '',
                'job_type' => $cvthequeProfile->job_type ?? '',
                'salary_expectation' => $cvthequeProfile->salary_expectation ?? '',
                'remote_work' => $cvthequeProfile->remote_work ?? false,
                'willing_to_relocate' => $cvthequeProfile->willing_to_relocate ?? false,
                'profile_visible' => $cvthequeProfile->profile_visible ?? false,
                'allow_contact' => $cvthequeProfile->allow_contact ?? false,
                'cv_file_path' => $cvthequeProfile->cv_file_path ?? '',
                'motivation_letter_path' => $cvthequeProfile->motivation_letter_path ?? '',
                'portfolio_files' => $cvthequeProfile->portfolio_files ?? '',
                'pressbook_file_path' => $cvthequeProfile->pressbook_file_path ?? '',
                'report_file_path' => $cvthequeProfile->report_file_path ?? '',
                'rapport_file_path' => $cvthequeProfile->rapport_file_path ?? '',
                'profile_completion_score' => $cvthequeProfile->profile_completion_score ?? 0,
            ] : null,
            'stats' => [
                'total_tp' => $totalTp,
                'tp_valides' => $tpValides,
                'tp_en_cours' => $tpEnCours,
                'tp_rejetes' => $tpRejetes,
                'progression' => $progression,
                'total_projects' => $designProjects->count(),
                'total_paye' => $totalPaye,
                'total_factures' => $totalFactures,
                'solde_restant' => $soldeRestant,
                'total_files_size' => 0, // Taille totale des fichiers (à calculer si nécessaire)
            ],
            'tps' => $tps,
            'projects' => $designProjects,
            'paiements' => $paiements,
            'factures' => $factures,
        ];

        return view('admin.students.profile-new', compact('data'));
    }

    /**
     * Éditer un étudiant (admin)
     */
    public function edit(int $id)
    {
        $cols = Schema::getColumnListing('users');
        $select = ['id', 'email', 'created_at'];
        foreach (['first_name', 'last_name', 'name', 'phone', 'city', 'country', 'ville', 'pays', 'formation_souhaitee', 'choix_formation', 'profile_photo'] as $c) {
            if (in_array($c, $cols, true)) $select[] = $c;
        }
        $u = DB::table('users')->select($select)->where('id', $id)->first();
        abort_unless($u, 404);

        // Récupérer l'enregistrement student une seule fois
        $studentRecord = null;
        if (Schema::hasTable('students')) {
            $studentRecord = DB::table('students')->where('user_id', $id)->first();
        }

        // Hydrater prenom/nom - Prioriser students puis users
        $prenom = '';
        $nom = '';

        if ($studentRecord && !empty($studentRecord->first_name)) {
            $prenom = $studentRecord->first_name;
        } elseif (property_exists($u, 'first_name') && !empty($u->first_name)) {
            $prenom = $u->first_name;
        }

        if ($studentRecord && !empty($studentRecord->last_name)) {
            $nom = $studentRecord->last_name;
        } elseif (property_exists($u, 'last_name') && !empty($u->last_name)) {
            $nom = $u->last_name;
        }

        // Si toujours vide, essayer de split le name
        if ((!$prenom || !$nom) && property_exists($u, 'name') && !empty($u->name)) {
            $parts = preg_split('/\s+/', (string)$u->name, 2);
            $prenom = $prenom ?: ($parts[0] ?? '');
            $nom = $nom ?: ($parts[1] ?? '');
        }

        // Ville - Prioriser students puis users
        $ville = '';
        if ($studentRecord && !empty($studentRecord->city)) {
            $ville = $studentRecord->city;
        } elseif (property_exists($u, 'city') && !empty($u->city)) {
            $ville = $u->city;
        } elseif (property_exists($u, 'ville') && !empty($u->ville)) {
            $ville = $u->ville;
        }

        // Pays - Prioriser students puis users
        $pays = '';
        if ($studentRecord && !empty($studentRecord->country)) {
            $pays = $studentRecord->country;
        } elseif (property_exists($u, 'country') && !empty($u->country)) {
            $pays = $u->country;
        } elseif (property_exists($u, 'pays') && !empty($u->pays)) {
            $pays = $u->pays;
        }

        // Téléphone - Prioriser students puis users
        $phone = '';
        if ($studentRecord && !empty($studentRecord->phone)) {
            $phone = $studentRecord->phone;
        } elseif (property_exists($u, 'phone') && !empty($u->phone)) {
            $phone = $u->phone;
        }

        // Récupérer la formation depuis users ou students
        $formationKey = '';
        if (property_exists($u, 'formation_souhaitee') && !empty($u->formation_souhaitee)) {
            $formationKey = (string)$u->formation_souhaitee;
        } elseif (property_exists($u, 'choix_formation') && !empty($u->choix_formation)) {
            $formationKey = (string)$u->choix_formation;
        } elseif ($studentRecord && !empty($studentRecord->program)) {
            $formationKey = $studentRecord->program;
        } elseif ($studentRecord && !empty($studentRecord->specialization)) {
            $formationKey = $studentRecord->specialization;
        }

        // Normaliser la clé de formation pour correspondre aux valeurs du select
        $formationNormalizeMap = [
            'design-graphique' => 'design_graphique',
            'design-graphique-cm' => 'design_graphique_community_management',
            'design-graphique-community-manager' => 'design_graphique_community_management',
            'design_graphique_community_management' => 'design_graphique_community_management',
            'community-manager' => 'community_management',
            'community-management' => 'community_management',
            'intelligence-artificielle' => 'intelligence_artificielle',
            'gestion-informatique' => 'gestion_informatique',
            'infographie' => 'design_graphique',
            'informatique' => 'gestion_informatique',
            'Design Graphique' => 'design_graphique',
            'Design Graphique & Community Management' => 'design_graphique_community_management',
            'Design Graphique & Community Manager' => 'design_graphique_community_management',
            'Community Management' => 'community_management',
            'Intelligence Artificielle' => 'intelligence_artificielle',
            'Gestion Informatique' => 'gestion_informatique',
        ];

        // Normaliser la clé si nécessaire
        $normalizedFormation = $formationNormalizeMap[$formationKey] ?? $formationKey;

        $photoUrl = null;
        if (in_array('profile_photo', $cols, true) && !empty($u->profile_photo)) {
            $photoUrl = ProfilePhotoHelper::getUrl($u->profile_photo);
        }

        // Récupérer la date d'expiration depuis la table students
        $expirationDate = null;
        if ($studentRecord && !empty($studentRecord->expiration_date)) {
            $expirationDate = $studentRecord->expiration_date;
        }

        $registrationDate = null;
        if ($studentRecord && Schema::hasColumn('students', 'registration_date') && !empty($studentRecord->registration_date)) {
            $registrationDate = $studentRecord->registration_date;
        } elseif ($studentRecord && !empty($studentRecord->created_at)) {
            $registrationDate = $studentRecord->created_at;
        } elseif (!empty($u->created_at)) {
            $registrationDate = $u->created_at;
        }

        // Email - Prioriser students puis users
        $email = '';
        if ($studentRecord && !empty($studentRecord->email)) {
            $email = $studentRecord->email;
        } elseif (!empty($u->email)) {
            $email = $u->email;
        }

        // Tableau attendu par la vue
        $student = [
            'id' => $u->id,
            'email' => $email,
            'prenom' => $prenom ?: '',
            'nom' => $nom ?: '',
            'phone' => $phone ?: '',
            'ville' => $ville ?: '',
            'pays' => $pays ?: '',
            'created_at' => $u->created_at,
            'registration_date' => $registrationDate,
            'formation_souhaitee' => $normalizedFormation ?: '',
            'photo_url' => $photoUrl,
            'expiration_date' => $expirationDate,
        ];

        return view('admin.students.edit', compact('student'));
    }

    /**
     * Désactiver/Activer le compte d'un étudiant
     */
    public function toggleStatus(Request $request, int $id)
    {
        try {
            // Vérifier si la table students existe
            if (!Schema::hasTable('students')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Table students introuvable'
                ], 404);
            }

            // Récupérer l'étudiant
            $student = DB::table('students')->where('id', $id)->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Étudiant introuvable'
                ], 404);
            }

            // Basculer le statut
            $newStatus = $student->status === 'active' ? 'inactive' : 'active';
            $reason = $request->input('reason', '');

            $updateData = [
                'status' => $newStatus,
                'updated_at' => now()
            ];

            // Si on désactive, enregistrer la raison et la date
            if ($newStatus === 'inactive') {
                $updateData['deactivation_reason'] = $reason;
                $updateData['deactivated_at'] = now();
            } else {
                // Si on réactive, effacer la raison
                $updateData['deactivation_reason'] = null;
                $updateData['deactivated_at'] = null;
            }

            DB::table('students')
                ->where('id', $id)
                ->update($updateData);

            // Envoyer un email à l'étudiant selon le statut
            if ($newStatus === 'inactive' && !empty($student->email)) {
                // Email de désactivation
                try {
                    Mail::send('emails.account-deactivated', [
                        'studentName' => $student->first_name . ' ' . $student->last_name,
                        'reason' => $reason,
                        'date' => now()->format('d/m/Y à H:i')
                    ], function ($message) use ($student) {
                        $message->to($student->email)
                            ->subject('⚠️ Votre compte EVC a été désactivé');
                    });
                } catch (\Exception $e) {
                    Log::error('Erreur envoi email désactivation: ' . $e->getMessage());
                    // On continue même si l'email échoue
                }
            } elseif ($newStatus === 'active' && !empty($student->email)) {
                // Email de réactivation
                try {
                    Mail::send('emails.account-reactivated', [
                        'studentName' => $student->first_name . ' ' . $student->last_name,
                        'date' => now()->format('d/m/Y à H:i')
                    ], function ($message) use ($student) {
                        $message->to($student->email)
                            ->subject('✅ Votre compte EVC a été réactivé');
                    });
                } catch (\Exception $e) {
                    Log::error('Erreur envoi email réactivation: ' . $e->getMessage());
                    // On continue même si l'email échoue
                }
            }

            $message = $newStatus === 'inactive'
                ? 'Le compte de l\'étudiant a été désactivé avec succès. Un email a été envoyé à l\'étudiant.'
                : 'Le compte de l\'étudiant a été réactivé avec succès. Un email de confirmation a été envoyé à l\'étudiant.';

            return response()->json([
                'success' => true,
                'message' => $message,
                'new_status' => $newStatus
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification du statut: ' . $e->getMessage()
            ], 500);
        }
    }

    public function extendExpiration(Request $request, int $studentId)
    {
        $validated = $request->validate([
            'months' => 'required|integer|in:1,3,6',
        ]);

        if (!Schema::hasTable('students')) {
            return response()->json([
                'success' => false,
                'message' => 'Table students introuvable',
            ], 404);
        }

        $student = DB::table('students')->where('id', $studentId)->first();
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Étudiant introuvable',
            ], 404);
        }

        $monthsToAdd = (int) $validated['months'];

        $userId = null;
        $userCreatedAt = null;
        if (!empty($student->email)) {
            $userId = DB::table('users')->where('email', $student->email)->value('id');
            if ($userId) {
                $userCreatedAt = DB::table('users')->where('id', $userId)->value('created_at');
            }
        }

        $registrationDate = null;
        if (Schema::hasColumn('students', 'registration_date') && !empty($student->registration_date)) {
            $registrationDate = $student->registration_date;
        }
        if (!$registrationDate && !empty($student->created_at)) {
            $registrationDate = $student->created_at;
        }
        if (!$registrationDate && $userCreatedAt) {
            $registrationDate = $userCreatedAt;
        }

        $durationMonths = 4;
        $sevenMonthsPrograms = [
            'design_graphique_community_management',
            'design_graphique_community_manager',
            'design-graphique-community-manager',
        ];
        if (in_array(($student->program ?? null), $sevenMonthsPrograms, true) || in_array(($student->specialization ?? null), $sevenMonthsPrograms, true)) {
            $durationMonths = 7;
        }

        // Calculer expiration depuis inscription
        $computedExpiration = null;
        $registrationCarbon = null;
        if ($registrationDate) {
            try {
                $registrationCarbon = Carbon::parse($registrationDate);
                $computedExpiration = $registrationCarbon->copy()->addMonths($durationMonths);
            } catch (\Exception $e) {
                $computedExpiration = null;
                $registrationCarbon = null;
            }
        }

        // Expiration stockée (potentiellement obsolète)
        $storedExpiration = null;
        if (!empty($student->expiration_date)) {
            try {
                $storedExpiration = Carbon::parse($student->expiration_date);
            } catch (\Exception $e) {
                $storedExpiration = null;
            }
        }

        // Expiration auto basée sur users.created_at (si dispo)
        $userBasedExpiration = null;
        $userCreatedAtCarbon = null;
        if ($userCreatedAt) {
            try {
                $userCreatedAtCarbon = Carbon::parse($userCreatedAt);
                $userBasedExpiration = $userCreatedAtCarbon->copy()->addMonths($durationMonths);
            } catch (\Exception $e) {
                $userBasedExpiration = null;
                $userCreatedAtCarbon = null;
            }
        }

        $shouldIgnoreStored = false;
        if ($storedExpiration && $userBasedExpiration && $registrationCarbon) {
            if ($storedExpiration->isSameDay($userBasedExpiration) && !$registrationCarbon->isSameDay($userCreatedAtCarbon)) {
                $shouldIgnoreStored = true;
            }
        }

        // Détecter une expiration auto erronée basée sur "maintenant + durée"
        if (!$shouldIgnoreStored && $storedExpiration && $computedExpiration) {
            $nowBasedExpiration = Carbon::now()->addMonths($durationMonths);
            if ($storedExpiration->isSameDay($nowBasedExpiration) && !$storedExpiration->isSameDay($computedExpiration)) {
                $shouldIgnoreStored = true;
            }
        }

        // Déterminer la base d'expiration (celle qu'on va prolonger)
        $baseExpiration = null;
        if ($computedExpiration && $storedExpiration) {
            $baseExpiration = $shouldIgnoreStored
                ? $computedExpiration
                : ($storedExpiration->greaterThan($computedExpiration) ? $storedExpiration : $computedExpiration);
        } else {
            $baseExpiration = $storedExpiration ?: $computedExpiration;
        }

        if (!$baseExpiration) {
            $durationMonths = 4;
            if (($student->program ?? null) === 'design_graphique_community_management' || ($student->specialization ?? null) === 'design_graphique_community_management') {
                $durationMonths = 7;
            }
            return response()->json([
                'success' => false,
                'message' => 'Impossible de déterminer la date d\'expiration actuelle',
            ], 422);
        }

        $newExpiration = $baseExpiration->copy()->addMonths($monthsToAdd);

        DB::table('students')
            ->where('id', $studentId)
            ->update([
                'expiration_date' => $newExpiration->format('Y-m-d'),
                'updated_at' => now(),
            ]);

        $daysRemaining = (int) Carbon::now()->diffInDays($newExpiration, false);

        return response()->json([
            'success' => true,
            'message' => 'Expiration prolongée avec succès',
            'expiration_iso' => $newExpiration->toIso8601String(),
            'expiration_date' => $newExpiration->format('Y-m-d'),
            'days_remaining' => $daysRemaining,
        ]);
    }

    /**
     * Afficher les détails d'un projet design (admin)
     *
     * @param int $projectId
     * @return \Illuminate\Http\JsonResponse
     */
    public function showProject(int $projectId)
    {
        try {
            // Récupérer le projet
            $project = DB::table('design_projects')
                ->where('id', $projectId)
                ->first();

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Projet non trouvé'
                ], 404);
            }

            // Récupérer les fichiers associés depuis design_project_files
            $files = [];
            if (Schema::hasTable('design_project_files')) {
                $projectFiles = DB::table('design_project_files')
                    ->where('project_id', $projectId)
                    ->orderBy('created_at', 'desc')
                    ->get();

                $files = $projectFiles->map(function ($file) {
                    return [
                        'id' => $file->id,
                        'name' => $file->original_name,
                        'url' => asset($file->file_path),
                        'size' => $file->file_size,
                        'type' => $file->file_type,
                        'mime_type' => $file->mime_type
                    ];
                })->toArray();
            }

            // Formater les données du projet
            $projectData = [
                'id' => $project->id,
                'title' => $project->title ?? 'Sans titre',
                'description' => $project->description ?? 'Aucune description',
                'status' => $project->status ?? 'en_cours',
                'created_at' => isset($project->created_at) ? date('d/m/Y H:i', strtotime($project->created_at)) : '-',
                'files' => $files
            ];

            return response()->json([
                'success' => true,
                'project' => $projectData
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du projet: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des détails du projet'
            ], 500);
        }
    }

    /**
     * Valider un projet design (admin)
     *
     * @param int $projectId
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateProject(int $projectId)
    {
        try {
            // Vérifier que le projet existe
            $project = DB::table('design_projects')
                ->where('id', $projectId)
                ->first();

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Projet non trouvé'
                ], 404);
            }

            // Récupérer les informations de l'étudiant
            $student = DB::table('users')
                ->where('id', $project->user_id)
                ->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Étudiant non trouvé'
                ], 404);
            }

            if ((string) ($project->status ?? '') === 'validated') {
                return response()->json([
                    'success' => true,
                    'message' => 'Projet déjà validé.'
                ]);
            }

            // Mettre à jour le statut du projet
            DB::table('design_projects')
                ->where('id', $projectId)
                ->update([
                    'status' => 'validated',
                    'validated_at' => now(),
                    'updated_at' => now()
                ]);

            try {
                $adminId = (int) session('admin_id');
                if ($adminId > 0 && Schema::hasTable('admin_task_logs') && Schema::hasTable('admin_task_types')) {
                    $q = DB::table('admin_task_types')->where('is_active', 1);

                    if (Schema::hasColumn('admin_task_types', 'kpi_catalog_key')) {
                        $q->where('kpi_catalog_key', 'validate_projects');
                    }

                    if (Schema::hasColumn('admin_task_types', 'job_profile_id') && Schema::hasTable('admin_admin_job_profiles')) {
                        $profileIds = DB::table('admin_admin_job_profiles')
                            ->where('admin_id', $adminId)
                            ->where(function ($q2) {
                                $q2->whereNull('starts_at')->orWhere('starts_at', '<=', now()->toDateString());
                            })
                            ->where(function ($q2) {
                                $q2->whereNull('ends_at')->orWhere('ends_at', '>=', now()->toDateString());
                            })
                            ->pluck('job_profile_id')
                            ->map(fn ($v) => (int) $v)
                            ->unique()
                            ->values()
                            ->all();

                        if (!empty($profileIds)) {
                            $q->whereIn('job_profile_id', $profileIds);
                        }
                    }

                    $taskTypeId = (int) ($q->orderBy('id')->value('id') ?? 0);

                    if ($taskTypeId > 0) {
                        DB::table('admin_task_logs')->insert([
                            'admin_id' => $adminId,
                            'task_type_id' => $taskTypeId,
                            'quantity' => 1,
                            'performed_at' => now(),
                            'meta' => json_encode([
                                'design_project_id' => (int) $projectId,
                                'event' => 'validated',
                            ]),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Unable to create admin task log for project validation (StudentAdminController)', [
                    'design_project_id' => $projectId,
                    'error' => $e->getMessage(),
                ]);
            }

            // Envoyer un email de validation à l'étudiant
            try {
                $studentName = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
                if (empty($studentName)) {
                    $studentName = $student->name ?? $student->email ?? 'Étudiant';
                }

                Mail::send('emails.design-project-validated', [
                    'studentName' => $studentName,
                    'projectTitle' => $project->title,
                    'projectType' => ucfirst($project->project_type ?? 'Design'),
                    'validatedAt' => now()->format('d/m/Y à H:i')
                ], function ($message) use ($student, $project) {
                    $message->to($student->email)
                        ->subject('🎉 Félicitations ! Votre projet "' . $project->title . '" a été validé - EVC');
                });

                Log::info('Email de validation envoyé à l\'étudiant', [
                    'project_id' => $projectId,
                    'student_email' => $student->email
                ]);
            } catch (\Exception $e) {
                // Ne pas bloquer la validation si l'email échoue
                Log::warning('Erreur lors de l\'envoi de l\'email de validation: ' . $e->getMessage());
            }

            // Logger l'action
            Log::info('Projet validé par admin', [
                'project_id' => $projectId,
                'admin_session' => session()->all()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Projet validé avec succès !'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la validation du projet: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la validation du projet'
            ], 500);
        }
    }

    /**
     * Télécharger un projet design (admin)
     *
     * @param int $projectId
     * @return \Illuminate\Http\Response
     */
    public function downloadProject(int $projectId)
    {
        try {
            // Récupérer le projet
            $project = DB::table('design_projects')
                ->where('id', $projectId)
                ->first();

            if (!$project) {
                abort(404, 'Projet non trouvé');
            }

            // Récupérer les fichiers depuis design_project_files
            $projectFiles = [];
            if (Schema::hasTable('design_project_files')) {
                $projectFiles = DB::table('design_project_files')
                    ->where('project_id', $projectId)
                    ->get();
            }

            if ($projectFiles->isEmpty()) {
                return redirect()->back()->with('error', 'Aucun fichier à télécharger pour ce projet');
            }

            // Si un seul fichier, télécharger directement
            if ($projectFiles->count() === 1) {
                $file = $projectFiles->first();
                $filePath = public_path($file->file_path);
                if (file_exists($filePath)) {
                    return response()->download($filePath, $file->original_name);
                } else {
                    return redirect()->back()->with('error', 'Fichier introuvable: ' . $file->original_name);
                }
            }

            // Si plusieurs fichiers, créer une archive ZIP
            $zip = new \ZipArchive();
            $zipFileName = 'projet_' . $projectId . '_' . time() . '.zip';
            $zipPath = storage_path('app/temp/' . $zipFileName);

            // Créer le répertoire temp s'il n'existe pas
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
                foreach ($projectFiles as $file) {
                    $filePath = public_path($file->file_path);
                    if (file_exists($filePath)) {
                        $zip->addFile($filePath, $file->original_name);
                    }
                }
                $zip->close();

                return response()->download($zipPath)->deleteFileAfterSend(true);
            } else {
                return redirect()->back()->with('error', 'Impossible de créer l\'archive ZIP');
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors du téléchargement du projet: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors du téléchargement du projet');
        }
    }

    /**
     * Supprimer un projet design (admin)
     *
     * @param int $projectId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProject(int $projectId)
    {
        try {
            // Récupérer le projet
            $project = DB::table('design_projects')
                ->where('id', $projectId)
                ->first();

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Projet non trouvé'
                ], 404);
            }

            // Supprimer les fichiers associés depuis design_project_files
            if (Schema::hasTable('design_project_files')) {
                $projectFiles = DB::table('design_project_files')
                    ->where('project_id', $projectId)
                    ->get();

                // Supprimer les fichiers physiques
                foreach ($projectFiles as $file) {
                    $filePath = public_path($file->file_path);
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                        Log::info('Fichier supprimé', ['file' => $file->file_path]);
                    }
                }

                // Supprimer les entrées de la table design_project_files
                DB::table('design_project_files')
                    ->where('project_id', $projectId)
                    ->delete();
            }

            // Supprimer le projet de la base de données
            DB::table('design_projects')
                ->where('id', $projectId)
                ->delete();

            // Logger l'action
            Log::info('Projet supprimé par admin', [
                'project_id' => $projectId,
                'project_title' => $project->title ?? 'Sans titre'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Projet supprimé avec succès !'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du projet: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du projet'
            ], 500);
        }
    }

    /**
     * Mettre à jour les informations d'un étudiant (admin)
     */
    public function update(Request $request, int $id)
    {
        try {
            // Validation des données
            $validated = $request->validate([
                'prenom' => 'required|string|max:255',
                'nom' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'formation_souhaitee' => 'required|string',
                'ville' => 'nullable|string|max:255',
                'pays' => 'nullable|string|max:255',
                'registration_date' => 'nullable|date',
                'expiration_date' => 'nullable|date',
            ]);

            // Vérifier que l'utilisateur existe
            $user = DB::table('users')->where('id', $id)->first();
            if (!$user) {
                return redirect()->back()->with('error', '❌ Utilisateur introuvable.');
            }

            // Préparer les données à mettre à jour
            $updateData = [
                'email' => $validated['email'],
                'updated_at' => now(),
            ];

            // Ajouter les champs si ils existent dans la table
            $cols = Schema::getColumnListing('users');

            if (in_array('first_name', $cols)) {
                $updateData['first_name'] = $validated['prenom'];
            }
            if (in_array('last_name', $cols)) {
                $updateData['last_name'] = $validated['nom'];
            }
            if (in_array('phone', $cols)) {
                $updateData['phone'] = $validated['phone'];
            }
            if (in_array('city', $cols)) {
                $updateData['city'] = $validated['ville'];
            }
            if (in_array('country', $cols)) {
                $updateData['country'] = $validated['pays'];
            }
            if (in_array('formation_souhaitee', $cols)) {
                $updateData['formation_souhaitee'] = $validated['formation_souhaitee'];
            }

            // Mettre à jour dans la table users
            DB::table('users')
                ->where('id', $id)
                ->update($updateData);

            // Mettre à jour aussi dans la table students si l'entrée existe
            if (Schema::hasTable('students')) {
                $student = DB::table('students')->where('user_id', $id)->first();
                if ($student) {
                    $studentUpdateData = [
                        'first_name' => $validated['prenom'],
                        'last_name' => $validated['nom'],
                        'email' => $validated['email'],
                        'phone' => $validated['phone'] ?: null,
                        'city' => $validated['ville'] ?: null,
                        'country' => $validated['pays'] ?: 'Non spécifié',
                        'updated_at' => now(),
                    ];

                    // Map formation_souhaitee vers program
                    $formationMap = [
                        'design_graphique' => 'Design Graphique',
                        'design_graphique_community_management' => 'Design Graphique & Community Management',
                        'community_management' => 'Community Management',
                        'intelligence_artificielle' => 'Intelligence Artificielle',
                        'gestion_informatique' => 'Gestion Informatique',
                    ];

                    if (isset($formationMap[$validated['formation_souhaitee']])) {
                        $studentUpdateData['program'] = $formationMap[$validated['formation_souhaitee']];
                    }

                    $durationMonths = 4;
                    if (in_array($validated['formation_souhaitee'], ['design_graphique_community_management', 'design_graphique_community_manager', 'design-graphique-community-manager'], true)) {
                        $durationMonths = 7;
                    }

                    // Gérer la date d'inscription
                    if (!empty($validated['registration_date'])) {
                        if (Schema::hasColumn('students', 'registration_date')) {
                            $studentUpdateData['registration_date'] = $validated['registration_date'];
                        } else {
                            DB::table('users')
                                ->where('id', $id)
                                ->update([
                                    'created_at' => $validated['registration_date'],
                                    'updated_at' => now(),
                                ]);

                            $studentUpdateData['created_at'] = $validated['registration_date'];
                        }
                    }

                    // Gérer la date d'expiration
                    if (!empty($validated['expiration_date'])) {
                        $studentUpdateData['expiration_date'] = $validated['expiration_date'];
                    } elseif (!empty($validated['registration_date'])) {
                        $studentUpdateData['expiration_date'] = \Carbon\Carbon::parse($validated['registration_date'])->addMonths($durationMonths)->format('Y-m-d');
                    }

                    DB::table('students')
                        ->where('user_id', $id)
                        ->update($studentUpdateData);
                }
            }

            // Logger l'action
            Log::info('Étudiant modifié par admin', [
                'user_id' => $id,
                'updated_fields' => array_keys($updateData)
            ]);

            $studentIdForProfile = $id;
            if (Schema::hasTable('students')) {
                $studentRecord = DB::table('students')
                    ->where('user_id', $id)
                    ->orWhere('email', $validated['email'])
                    ->first();
                if ($studentRecord && !empty($studentRecord->id)) {
                    $studentIdForProfile = (int) $studentRecord->id;
                } else {
                    return redirect()->route('admin.students.edit', $id)
                        ->with('success', '✅ Les informations de l\'étudiant ont été mises à jour avec succès.')
                        ->with('warning', '⚠️ Redirection vers le profil impossible (enregistrement étudiant introuvable dans la table students).');
                }
            }

            return redirect()->route('admin.students.profile', $studentIdForProfile)
                ->with('success', '✅ Les informations de l\'étudiant ont été mises à jour avec succès.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de l\'étudiant: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', '❌ Erreur lors de la mise à jour : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Supprimer définitivement un étudiant et toutes ses données
     */
    public function destroy($id)
    {
        try {
            // Récupérer l'étudiant
            $student = DB::table('students')->where('id', $id)->first();

            if (!$student) {
                return redirect()->back()->with('error', '❌ Étudiant introuvable.');
            }

            $studentName = $student->first_name . ' ' . $student->last_name;
            $userId = $student->user_id;

            // Supprimer les TP de l'étudiant
            if (Schema::hasTable('tp')) {
                $tps = DB::table('tp')->where('user_id', $userId)->get();
                foreach ($tps as $tp) {
                    // Supprimer les fichiers associés aux TP
                    if (Schema::hasTable('tp_files')) {
                        DB::table('tp_files')->where('tp_id', $tp->id)->delete();
                    }
                }
                DB::table('tp')->where('user_id', $userId)->delete();
            }

            // Supprimer les projets de l'étudiant
            if (Schema::hasTable('design_projects')) {
                $projects = DB::table('design_projects')->where('user_id', $userId)->get();
                foreach ($projects as $project) {
                    // Supprimer les fichiers associés aux projets
                    if (Schema::hasTable('design_project_files')) {
                        DB::table('design_project_files')->where('project_id', $project->id)->delete();
                    }
                }
                DB::table('design_projects')->where('user_id', $userId)->delete();
            }

            // Supprimer les documents de l'étudiant
            if (Schema::hasTable('student_documents')) {
                DB::table('student_documents')->where('student_id', $id)->delete();
            }

            // Supprimer le profil étudiant
            DB::table('students')->where('id', $id)->delete();

            // Supprimer l'utilisateur associé
            if ($userId) {
                DB::table('users')->where('id', $userId)->delete();
            }

            // Logger l'action
            Log::info('Étudiant supprimé par admin', [
                'student_id' => $id,
                'student_name' => $studentName,
                'user_id' => $userId
            ]);

            return redirect()->back()->with('success', "✅ L'étudiant {$studentName} a été supprimé définitivement avec toutes ses données.");
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de l\'étudiant: ' . $e->getMessage());
            return redirect()->back()->with('error', '❌ Erreur lors de la suppression de l\'étudiant : ' . $e->getMessage());
        }
    }
}
