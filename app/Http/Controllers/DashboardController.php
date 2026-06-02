<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;
use App\Models\Student;
use App\Models\DesignProject;
use App\Models\TP;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\StudentProfileRequest;
use App\Services\StudentProfileService;
use App\Helpers\AccountExpirationHelper;

class DashboardController extends Controller
{
    private function userHasAnyProject(?int $userId): bool
    {
        if (empty($userId)) {
            return false;
        }

        try {
            if (Schema::hasTable('projects')) {
                return DB::table('projects')->where('user_id', $userId)->exists();
            }
        } catch (\Throwable $e) {
            Log::warning('userHasAnyProject check failed', ['user_id' => $userId, 'error' => $e->getMessage()]);
        }

        return false;
    }

    private function normalizePublicDiskPath(?string $path): string
    {
        $path = ltrim((string) ($path ?? ''), '/');

        if (Str::startsWith($path, 'storage/app/public/')) {
            $path = Str::after($path, 'storage/app/public/');
        }

        if (Str::startsWith($path, 'public/storage/')) {
            $path = Str::after($path, 'public/storage/');
        }

        if (Str::startsWith($path, 'storage/')) {
            $path = Str::after($path, 'storage/');
        }

        return ltrim($path, '/');
    }

    use AuthorizesRequests;
    /**
     * Redirect to the login page to ensure stability.
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('login');
    }

    /**
     * Rediriger l'utilisateur vers son dashboard en fonction de sa formation
     */
    public function redirectBasedOnFormation(): RedirectResponse
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $student = DB::table('students')->where('user_id', $user->id)->first();

        // Utiliser la méthode helper pour obtenir le slug
        $slug = $this->getFormationSlug($student);

        // Construire le nom de la route
        $routeName = 'dashboard.' . $slug;

        // Vérifier si la route existe, sinon fallback
        if (!\Illuminate\Support\Facades\Route::has($routeName)) {
            \Log::warning("Route dashboard '$routeName' n'existe pas. Redirection vers défaut.");
            $routeName = 'dashboard.design-graphique';
        }

        return redirect()->route($routeName);
    }

    public function notificationsFeed(Request $request): JsonResponse
    {
        $user = Auth::user();

        $limit = (int)($request->query('limit', 8));
        if ($limit < 1) {
            $limit = 1;
        }
        if ($limit > 20) {
            $limit = 20;
        }

        $student = DB::table('students')->where('user_id', $user->id)->first();
        $formationSlug = $this->getFormationSlug($student);
        $unreadCount = $user->unreadNotifications()->count();
        $items = $user->notifications()
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(function ($n) use ($formationSlug) {
                $data = is_array($n->data) ? $n->data : [];

                if (($data['category'] ?? null) === 'project' && !empty($data['project_id'])) {
                    $data['url'] = url('/evc/compte/' . $formationSlug . '/todo/traiter/' . $data['project_id']);
                }

                return [
                    'id' => $n->id,
                    'read_at' => optional($n->read_at)->toIso8601String(),
                    'created_at' => optional($n->created_at)->toIso8601String(),
                    'data' => $data,
                ];
            });

        return response()->json([
            'unread_count' => $unreadCount,
            'items' => $items,
        ]);
    }

    public function notificationsMarkRead(Request $request): JsonResponse
    {
        $user = Auth::user();
        $ids = $request->input('ids');

        if (is_array($ids) && count($ids) > 0) {
            $user->unreadNotifications()->whereIn('id', $ids)->update(['read_at' => now()]);
        } else {
            $user->unreadNotifications()->update(['read_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    public function notificationsIndex(Request $request): View
    {
        $user = Auth::user();

        $notifications = $user->notifications()
            ->orderByDesc('created_at')
            ->paginate(15);

        $unreadCount = $user->unreadNotifications()->count();

        return view('dashboard.notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Dashboard Design Graphique avec statistiques complètes
     */
    public function designGraphique(StudentProfileService $service): View
    {
        $user = Auth::user();
        $student = $service->loadStudent($user, null);
        $preReg = $service->loadPreRegistration($student, $user);

        // Calculer l'expiration d'abord pour filtrer les stats
        $accountCreatedAt = \Carbon\Carbon::parse($user->created_at);
        $expirationDate = AccountExpirationHelper::getExpirationDate($user);
        $now = \Carbon\Carbon::now();
        $isExpiredNow = $now->greaterThan($expirationDate);

        // Statistiques pour le dashboard
        $tpQuery = DB::table('tp')->where('user_id', $user->id);
        $projectsQuery = DB::table('design_projects')->where('user_id', $user->id);

        // Si le compte est expiré, ne compter que les TP/projets créés avant l'expiration
        if ($isExpiredNow) {
            $tpQuery->where('created_at', '<=', $expirationDate);
            $projectsQuery->where('created_at', '<=', $expirationDate);
        }

        // Compter les formations disponibles (table formations, modules Design Graphique)
        $formationsDisponibles = 0;
        try {
            if (Schema::hasTable('formations') && Schema::hasColumn('formations', 'modules')) {
                $formationsQuery = DB::table('formations')
                    ->where('status', 'active')
                    ->where(function ($query) {
                        $query->whereJsonContains('modules', 'design-graphique')
                            ->orWhereJsonContains('modules', 'Design Graphique')
                            ->orWhereJsonContains('modules', 'design_graphique')
                            ->orWhere('modules', 'like', '%design-graphique%')
                            ->orWhere('modules', 'like', '%Design Graphique%')
                            ->orWhere('modules', 'like', '%design_graphique%');
                    });

                if ($isExpiredNow) {
                    $formationsQuery->where('created_at', '<=', $expirationDate);
                }

                $formationsDisponibles = $formationsQuery->count();
            }
        } catch (\Exception $e) {
            $formationsDisponibles = 0;
        }

        // Compter les TP depuis tp_assignments (table principale)
        $tpRealises = 0;
        $tpTotal = 0;

        if ($student && isset($student->id)) {
            $tpAssignmentsQuery = DB::table('tp_assignments')
                ->where('student_id', $student->id);

            if ($isExpiredNow) {
                $tpAssignmentsQuery->where('created_at', '<=', $expirationDate);
            }

            $tpRealises = (clone $tpAssignmentsQuery)
                ->where('status', 'validated')
                ->count();

            $tpTotal = $tpAssignmentsQuery->count();
        }

        // Si aucun TP dans tp_assignments, fallback sur la table tp
        if ($tpTotal === 0) {
            $tpRealises = (clone $tpQuery)
                ->whereIn('status', ['validated', 'completed'])
                ->count();
            $tpTotal = $tpQuery->count();
        }

        // Compter les événements (webinaires) publiés et à venir
        $evenementsQuery = DB::table('evenements')
            ->where('status', 'published')
            ->where('event_date', '>=', now()->toDateString())
            ->where(function ($query) {
                $query->where('visibility', 'all')
                    ->orWhere(function ($q) {
                        $q->where('visibility', 'specific')
                            ->where(function ($subq) {
                                $subq->whereJsonContains('formations', 'Design Graphique')
                                    ->orWhereJsonContains('formations', 'design_graphique')
                                    ->orWhereJsonContains('formations', 'Toutes')
                                    ->orWhere('formations', 'like', '%Design Graphique%')
                                    ->orWhere('formations', 'like', '%design_graphique%')
                                    ->orWhere('formations', 'like', '%Toutes%');
                            });
                    });
            });

        if ($isExpiredNow) {
            $evenementsQuery->where('created_at', '<=', $expirationDate);
        }

        $webinairesEnCours = $evenementsQuery->count();

        // Compter les actualités publiées visibles pour Design Graphique
        $actualitesEnCours = 0;

        try {
            // Vérifier si la table actualites existe
            if (Schema::hasTable('actualites')) {
                $actualitesQuery = DB::table('actualites')
                    ->where('status', 'published')
                    ->where(function ($query) {
                        $query->where('visibility', 'public')
                            ->orWhere('visibility', 'all')
                            ->orWhere(function ($q) {
                                $q->where('visibility', 'specific')
                                    ->where(function ($subq) {
                                        $subq->whereJsonContains('formations', 'Design Graphique')
                                            ->orWhereJsonContains('formations', 'design_graphique')
                                            ->orWhereJsonContains('formations', 'design-graphique')
                                            ->orWhereJsonContains('formations', 'Design Graphique & Community Manager')
                                            ->orWhereJsonContains('formations', 'Toutes')
                                            ->orWhere('formations', 'like', '%Design Graphique%')
                                            ->orWhere('formations', 'like', '%design_graphique%')
                                            ->orWhere('formations', 'like', '%design-graphique%')
                                            ->orWhere('formations', 'like', '%Design Graphique & Community Manager%')
                                            ->orWhere('formations', 'like', '%Toutes%');
                                    });
                            });
                    });

                if ($isExpiredNow) {
                    $actualitesQuery->where('created_at', '<=', $expirationDate);
                }

                $actualitesEnCours = $actualitesQuery->count();
            }
        } catch (\Exception $e) {
            // Si erreur, on garde actualitesEnCours à 0
            $actualitesEnCours = 0;
        }

        // Calculer le montant restant à payer
        $montantRestant = 0;
        $payments = DB::table('payments')
            ->where('user_id', $user->id)
            ->get();

        $montantTotal = $payments->sum('amount');
        $montantPaye = $payments->where('status', 'completed')->sum('amount');
        $montantRestant = max(0, $montantTotal - $montantPaye);

        $stats = [
            // Nombre de formations/programmes disponibles (dynamique)
            'formations_disponibles' => $formationsDisponibles,

            // Nombre de TP réalisés (dynamique depuis tp_assignments)
            'tp_realises' => $tpRealises,

            // Nombre total de TP (dynamique)
            'tp_total' => $tpTotal,

            // Nombre de projets réalisés (dynamique depuis design_projects)
            'projets_realises' => (clone $projectsQuery)
                ->whereIn('status', ['completed', 'validated'])
                ->count(),

            // Nombre total de projets (dynamique)
            'projets_total' => $projectsQuery->count(),

            // Événements/Webinaires en cours (dynamique depuis evenements)
            'webinaires_en_cours' => $webinairesEnCours,
            'evenements_en_cours' => $webinairesEnCours,

            // Actualités en cours (dynamique depuis actualites)
            'actualites_en_cours' => $actualitesEnCours,

            // Montant restant à solder (dynamique depuis payments)
            'montant_restant' => $montantRestant,
        ];

        $globalProgress = 0;
        if (($stats['tp_total'] ?? 0) > 0) {
            $globalProgress += ($stats['tp_realises'] / $stats['tp_total']) * 50;
        }
        if (($stats['projets_total'] ?? 0) > 0) {
            $globalProgress += ($stats['projets_realises'] / $stats['projets_total']) * 50;
        }
        $stats['global_progress'] = $globalProgress;

        // Si vous avez des informations de paiement dans preReg ou student
        if ($preReg && isset($preReg->montant_total) && isset($preReg->montant_paye)) {
            $stats['montant_restant'] = $preReg->montant_total - $preReg->montant_paye;
        }

        $accountCreatedAt = \Carbon\Carbon::parse($user->created_at);
        $expirationDate = AccountExpirationHelper::getExpirationDate($user);
        $now = \Carbon\Carbon::now();
        $daysRemaining = (int) $now->diffInDays($expirationDate, false);
        $isExpired = $daysRemaining <= 0;
        if ($daysRemaining < 0) {
            $daysRemaining = 0;
        }

        $isExpiringSoon = !$isExpired && $daysRemaining <= 30; // Alerte si moins de 30 jours

        // Récupérer les formations en vedette filtrées par module "Design Graphique"
        $formationsQuery = DB::table('formations')
            ->where('status', 'active')
            ->where('is_featured', 1)
            ->where(function ($query) {
                $query->whereJsonContains('modules', 'design-graphique')
                    ->orWhereJsonContains('modules', 'Design Graphique')
                    ->orWhereJsonContains('modules', 'design_graphique');
            });

        // Si le compte est expiré, ne montrer que les formations créées avant l'expiration
        if ($isExpired) {
            $formationsQuery->where('created_at', '<=', $expirationDate);
        }

        $featured_formations = $formationsQuery->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        $pendingAssignments = collect();
        $pendingAssignmentsQuery = DB::table('tp_assignments')
            ->where(function ($q) use ($student, $user) {
                if ($student && isset($student->id)) {
                    $q->where('student_id', $student->id)
                        ->orWhere('student_id', $user->id);
                } else {
                    $q->where('student_id', $user->id);
                }
            })
            ->whereIn('status', ['pending', 'submitted'])
            ->orderByRaw('deadline is null asc')
            ->orderBy('deadline', 'asc')
            ->orderBy('created_at', 'desc');

        if ($isExpiredNow) {
            $pendingAssignmentsQuery->where('created_at', '<=', $expirationDate);
        }

        if (Schema::hasColumn('tp_assignments', 'admin_hidden')) {
            $pendingAssignmentsQuery->where('admin_hidden', 0);
        }

        $pendingAssignments = $pendingAssignmentsQuery->get();

        $assignedProjects = collect();
        if (Schema::hasTable('projects')) {
            $assignedProjectsQuery = DB::table('projects')
                ->where('user_id', $user->id)
                ->whereIn('status', ['en_cours', 'termine'])
                ->orderByRaw('deadline is null asc')
                ->orderBy('deadline', 'asc')
                ->orderBy('created_at', 'desc');

            if ($isExpiredNow) {
                $assignedProjectsQuery->where('created_at', '<=', $expirationDate);
            }

            if (Schema::hasColumn('projects', 'admin_hidden')) {
                $assignedProjectsQuery->where('admin_hidden', 0);
            }

            $statusMap = [
                'en_cours' => 'assigned',
                'termine' => 'submitted',
            ];

            $assignedProjects = $assignedProjectsQuery->get()->map(function ($project) use ($statusMap) {
                $project->status = $statusMap[$project->status] ?? 'assigned';
                $project->source_table = 'projects';
                return $project;
            });
        }

        $pendingAssignments = $pendingAssignments->map(function ($assignment) {
            $assignment->source_table = 'tp_assignments';
            return $assignment;
        });

        $pendingWorks = $pendingAssignments
            ->concat($assignedProjects)
            ->sortBy(function ($item) {
                return $item->deadline ?? $item->created_at;
            })
            ->values();

        return view('dashboard.design-graphique', [
            'user' => $user,
            'student' => $student,
            'preReg' => $preReg,
            'stats' => $stats,
            'featured_formations' => $featured_formations,
            'pendingAssignments' => $pendingWorks,
            'accountCreatedAt' => $accountCreatedAt,
            'expirationDate' => $expirationDate,
            'daysRemaining' => $daysRemaining, // Déjà un entier positif
            'isExpired' => $isExpired,
            'isExpiringSoon' => $isExpiringSoon,
        ]);
    }

    /**
     * Dashboard Design Graphique & Community Management avec statistiques complètes
     */
    public function designCm(StudentProfileService $service): View
    {
        $user = Auth::user();
        $student = $service->loadStudent($user, null);
        $preReg = $service->loadPreRegistration($student, $user);

        // Calculer l'expiration d'abord pour filtrer les stats
        $accountCreatedAt = \Carbon\Carbon::parse($user->created_at);
        $expirationDate = AccountExpirationHelper::getExpirationDate($user);
        $now = \Carbon\Carbon::now();
        $isExpiredNow = $now->greaterThan($expirationDate);

        // Compter les programmes disponibles pour Design & CM
        $formationsDisponibles = 0;
        if (Schema::hasTable('formations') && Schema::hasColumn('formations', 'modules')) {
            $formationsQuery = DB::table('formations')
                ->where('status', 'active')
                ->where(function ($query) {
                    $query->whereJsonContains('modules', 'design-graphique')
                        ->orWhereJsonContains('modules', 'community-management')
                        ->orWhereJsonContains('modules', 'Design Graphique')
                        ->orWhereJsonContains('modules', 'Community Management');
                });

            if ($isExpiredNow) {
                $formationsQuery->where('created_at', '<=', $expirationDate);
            }

            $formationsDisponibles = $formationsQuery->count();
        }

        // Compter les TP depuis tp_assignments
        $tpRealises = 0;
        $tpTotal = 0;

        if ($student && isset($student->id)) {
            $tpAssignmentsQuery = DB::table('tp_assignments')
                ->where('student_id', $student->id);

            if ($isExpiredNow) {
                $tpAssignmentsQuery->where('created_at', '<=', $expirationDate);
            }

            $tpRealises = (clone $tpAssignmentsQuery)
                ->where('status', 'validated')
                ->count();

            $tpTotal = $tpAssignmentsQuery->count();
        }

        // Compter les projets (Community Management et Design combinés)
        $projetsQuery = DB::table('projects')
            ->where('user_id', $user->id);

        if ($isExpiredNow) {
            $projetsQuery->where('created_at', '<=', $expirationDate);
        }

        $projetsRealises = (clone $projetsQuery)
            ->whereIn('status', ['valide', 'completed', 'validated'])
            ->count();

        $projetsTotal = $projetsQuery->count();

        // Compter les événements (webinaires) pour Design & CM
        $evenementsQuery = DB::table('evenements')
            ->where('status', 'published')
            ->where('event_date', '>=', now()->toDateString())
            ->where(function ($query) {
                $query->where('visibility', 'all')
                    ->orWhere(function ($q) {
                        $q->where('visibility', 'specific')
                            ->where(function ($subq) {
                                $subq->whereJsonContains('formations', 'Design Graphique')
                                    ->orWhereJsonContains('formations', 'Community Management')
                                    ->orWhereJsonContains('formations', 'Design Graphique & Community Management')
                                    ->orWhereJsonContains('formations', 'Toutes');
                            });
                    });
            });

        if ($isExpiredNow) {
            $evenementsQuery->where('created_at', '<=', $expirationDate);
        }

        $webinairesEnCours = $evenementsQuery->count();

        // Compter les actualités
        $actualitesEnCours = 0;
        try {
            if (Schema::hasTable('actualites')) {
                $actualitesQuery = DB::table('actualites')
                    ->where('status', 'published')
                    ->where(function ($query) {
                        $query->where('visibility', 'public')
                            ->orWhere('visibility', 'all')
                            ->orWhere(function ($q) {
                                $q->where('visibility', 'specific')
                                    ->where(function ($subq) {
                                        $subq->whereJsonContains('formations', 'Design Graphique')
                                            ->orWhereJsonContains('formations', 'Community Management')
                                            ->orWhereJsonContains('formations', 'Design Graphique & Community Management')
                                            ->orWhereJsonContains('formations', 'Toutes');
                                    });
                            });
                    });

                if ($isExpiredNow) {
                    $actualitesQuery->where('created_at', '<=', $expirationDate);
                }

                $actualitesEnCours = $actualitesQuery->count();
            }
        } catch (\Exception $e) {
            $actualitesEnCours = 0;
        }

        // Calculer le montant restant à payer (cohérent avec paiementsIndex)
        $montantRestant = 0;
        try {
            if ($preReg && isset($preReg->montant_total) && isset($preReg->montant_paye)) {
                $montantRestant = max(0, (float) $preReg->montant_total - (float) $preReg->montant_paye);
            } else {
                $paymentsQuery = DB::table('payments')
                    ->where(function ($q) use ($user, $preReg) {
                        $q->where('user_id', $user->id);
                        if ($preReg && isset($preReg->id)) {
                            $q->orWhere('pre_registration_id', $preReg->id);
                        }
                    });

                $payments = $paymentsQuery->get();

                $formationLabel = $preReg ? (new \App\Http\Controllers\Admin\PreRegistrationAdminController())->getFormationLabel($preReg->choix_formation ?? null) : null;
                $firstPaymentDate = optional($payments->sortBy('created_at')->first())->created_at;
                $pricingDate = $firstPaymentDate ?: ($preReg->created_at ?? null);
                $grossPaymentAmount = $formationLabel ? (float) \App\Services\CinetPayService::getFormationPrice($formationLabel, $pricingDate) : 0;
                $discountAmount = min((float) ($preReg->discount_amount ?? 0), $grossPaymentAmount);
                $expectedAmount = max(0, $grossPaymentAmount - $discountAmount);
                $paymentsTotal = (float) ($payments->max('total_amount') ?? 0);
                $sumAmounts = (float) $payments->sum('amount');
                $paymentAmount = $discountAmount > 0 ? max($expectedAmount, $sumAmounts) : max($paymentsTotal, $expectedAmount, $sumAmounts);

                $paymentPaid = (float) $payments->where('status', 'completed')->sum('amount');
                $montantRestant = max(0, $paymentAmount - $paymentPaid);
            }
        } catch (\Exception $e) {
            $montantRestant = 0;
        }

        $stats = [
            'formations_disponibles' => $formationsDisponibles,
            'tp_realises' => $tpRealises,
            'tp_total' => $tpTotal,
            'projets_realises' => $projetsRealises,
            'projets_total' => $projetsTotal,
            'webinaires_en_cours' => $webinairesEnCours,
            'actualites_en_cours' => $actualitesEnCours,
            'montant_restant' => $montantRestant,
        ];

        if ($preReg && isset($preReg->montant_total) && isset($preReg->montant_paye)) {
            $stats['montant_restant'] = $preReg->montant_total - $preReg->montant_paye;
        }

        $expirationDate = AccountExpirationHelper::getExpirationDate($user);
        $daysRemaining = (int) $now->diffInDays($expirationDate, false);
        $isExpired = $daysRemaining <= 0;
        if ($daysRemaining < 0) {
            $daysRemaining = 0;
        }

        $isExpiringSoon = !$isExpired && $daysRemaining <= 30;

        // Formations en vedette pour Design & CM
        $formationsQuery = DB::table('formations')
            ->where('status', 'active')
            ->where('is_featured', 1)
            ->where(function ($query) {
                $query->whereJsonContains('modules', 'design-graphique')
                    ->orWhereJsonContains('modules', 'community-management')
                    ->orWhereJsonContains('modules', 'Design Graphique')
                    ->orWhereJsonContains('modules', 'Community Management');
            });

        if ($isExpired) {
            $formationsQuery->where('created_at', '<=', $expirationDate);
        }

        $featured_formations = $formationsQuery->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Récupérer les projets à faire (non validés)
        $projetsAFaireQuery = DB::table('projects')
            ->where('user_id', $user->id)
            ->whereNotIn('status', ['valide', 'completed', 'validated']);

        if ($isExpired) {
            $projetsAFaireQuery->where('created_at', '<=', $expirationDate);
        }

        $projetsAFaire = $projetsAFaireQuery
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('dashboard.design-cm', [
            'user' => $user,
            'student' => $student,
            'preReg' => $preReg,
            'stats' => $stats,
            'featured_formations' => $featured_formations,
            'projetsAFaire' => $projetsAFaire,
            'accountCreatedAt' => $accountCreatedAt,
            'expirationDate' => $expirationDate,
            'daysRemaining' => $daysRemaining,
            'isExpired' => $isExpired,
            'isExpiringSoon' => $isExpiringSoon,
        ]);
    }

    public function designCmStats(StudentProfileService $service): JsonResponse
    {
        $user = Auth::user();
        $student = $service->loadStudent($user, null);
        $preReg = $service->loadPreRegistration($student, $user);

        $accountCreatedAt = \Carbon\Carbon::parse($user->created_at);
        $expirationDate = AccountExpirationHelper::getExpirationDate($user);
        $now = \Carbon\Carbon::now();
        $isExpiredNow = $now->greaterThan($expirationDate);

        $formationsDisponibles = 0;
        try {
            if (Schema::hasTable('formations') && Schema::hasColumn('formations', 'modules')) {
                $formationsQuery = DB::table('formations')
                    ->where('status', 'active')
                    ->where(function ($query) {
                        $query->whereJsonContains('modules', 'design-graphique')
                            ->orWhereJsonContains('modules', 'community-management')
                            ->orWhereJsonContains('modules', 'Design Graphique')
                            ->orWhereJsonContains('modules', 'Community Management');
                    });

                if ($isExpiredNow) {
                    $formationsQuery->where('created_at', '<=', $expirationDate);
                }

                $formationsDisponibles = $formationsQuery->count();
            }
        } catch (\Exception $e) {
            $formationsDisponibles = 0;
        }

        $tpRealises = 0;
        $tpTotal = 0;

        if ($student && isset($student->id)) {
            $tpAssignmentsQuery = DB::table('tp_assignments')
                ->where(function ($q) use ($student, $user) {
                    $q->where('student_id', $student->id)
                        ->orWhere('student_id', $user->id);
                });

            if ($isExpiredNow) {
                $tpAssignmentsQuery->where('created_at', '<=', $expirationDate);
            }

            $tpRealises = (clone $tpAssignmentsQuery)
                ->where('status', 'validated')
                ->count();

            $tpTotal = $tpAssignmentsQuery->count();
        }

        $projetsQuery = DB::table('projects')->where('user_id', $user->id);

        if ($isExpiredNow) {
            $projetsQuery->where('created_at', '<=', $expirationDate);
        }

        // Compter les événements (webinaires)
        $evenementsQuery = DB::table('evenements')
            ->where('status', 'published')
            ->where('event_date', '>=', now()->toDateString())
            ->where(function ($query) {
                $query->where('visibility', 'all')
                    ->orWhere(function ($q) {
                        $q->where('visibility', 'specific')
                            ->where(function ($subq) {
                                $subq->whereJsonContains('formations', 'Design Graphique')
                                    ->orWhereJsonContains('formations', 'Community Management')
                                    ->orWhereJsonContains('formations', 'Design Graphique & Community Management')
                                    ->orWhereJsonContains('formations', 'Toutes');
                            });
                    });
            });

        if ($isExpiredNow) {
            $evenementsQuery->where('created_at', '<=', $expirationDate);
        }

        $webinairesEnCours = $evenementsQuery->count();

        // Compter les actualités
        $actualitesEnCours = 0;
        try {
            if (Schema::hasTable('actualites')) {
                $actualitesQuery = DB::table('actualites')
                    ->where('status', 'published')
                    ->where(function ($query) {
                        $query->where('visibility', 'public')
                            ->orWhere('visibility', 'all')
                            ->orWhere(function ($q) {
                                $q->where('visibility', 'specific')
                                    ->where(function ($subq) {
                                        $subq->whereJsonContains('formations', 'Design Graphique')
                                            ->orWhereJsonContains('formations', 'Community Management')
                                            ->orWhereJsonContains('formations', 'Design Graphique & Community Management')
                                            ->orWhereJsonContains('formations', 'Toutes');
                                    });
                            });
                    });

                if ($isExpiredNow) {
                    $actualitesQuery->where('created_at', '<=', $expirationDate);
                }

                $actualitesEnCours = $actualitesQuery->count();
            }
        } catch (\Exception $e) {
            $actualitesEnCours = 0;
        }

        // Calculer le montant restant à payer (cohérent avec paiementsIndex)
        $montantRestant = 0;
        try {
            if ($preReg && isset($preReg->montant_total) && isset($preReg->montant_paye)) {
                $montantRestant = max(0, (float) $preReg->montant_total - (float) $preReg->montant_paye);
            } else {
                $paymentsQuery = DB::table('payments')
                    ->where(function ($q) use ($user, $preReg) {
                        $q->where('user_id', $user->id);
                        if ($preReg && isset($preReg->id)) {
                            $q->orWhere('pre_registration_id', $preReg->id);
                        }
                    });

                $payments = $paymentsQuery->get();

                $formationLabel = $preReg ? (new \App\Http\Controllers\Admin\PreRegistrationAdminController())->getFormationLabel($preReg->choix_formation ?? null) : null;
                $firstPaymentDate = optional($payments->sortBy('created_at')->first())->created_at;
                $pricingDate = $firstPaymentDate ?: ($preReg->created_at ?? null);
                $grossPaymentAmount = $formationLabel ? (float) \App\Services\CinetPayService::getFormationPrice($formationLabel, $pricingDate) : 0;
                $discountAmount = min((float) ($preReg->discount_amount ?? 0), $grossPaymentAmount);
                $expectedAmount = max(0, $grossPaymentAmount - $discountAmount);
                $paymentsTotal = (float) ($payments->max('total_amount') ?? 0);
                $sumAmounts = (float) $payments->sum('amount');
                $paymentAmount = $discountAmount > 0 ? max($expectedAmount, $sumAmounts) : max($paymentsTotal, $expectedAmount, $sumAmounts);

                $paymentPaid = (float) $payments->where('status', 'completed')->sum('amount');
                $montantRestant = max(0, $paymentAmount - $paymentPaid);
            }
        } catch (\Exception $e) {
            $montantRestant = 0;
        }

        $stats = [
            'formations_disponibles' => $formationsDisponibles,
            'tp_realises' => $tpRealises,
            'tp_total' => $tpTotal,
            'projets_realises' => (clone $projetsQuery)
                ->whereIn('status', ['valide', 'completed', 'validated'])
                ->count(),
            'projets_total' => $projetsQuery->count(),
            'webinaires_en_cours' => $webinairesEnCours,
            'actualites_en_cours' => $actualitesEnCours,
        ];

        $globalProgress = 0;
        if ($stats['tp_total'] > 0) {
            $globalProgress += ($stats['tp_realises'] / $stats['tp_total']) * 50;
        }
        if ($stats['projets_total'] > 0) {
            $globalProgress += ($stats['projets_realises'] / $stats['projets_total']) * 50;
        }

        return response()->json([
            'stats' => $stats,
            'global_progress' => $globalProgress,
            'generated_at' => now()->toIso8601String(),
        ]);
    }

    public function designGraphiqueStats(StudentProfileService $service): JsonResponse
    {
        $user = Auth::user();
        $student = $service->loadStudent($user, null);
        $preReg = $service->loadPreRegistration($student, $user);

        $accountCreatedAt = \Carbon\Carbon::parse($user->created_at);
        $expirationDate = AccountExpirationHelper::getExpirationDate($user);
        $now = \Carbon\Carbon::now();
        $isExpiredNow = $now->greaterThan($expirationDate);

        $tpQuery = DB::table('tp')->where('user_id', $user->id);
        $projectsQuery = DB::table('design_projects')->where('user_id', $user->id);

        if ($isExpiredNow) {
            $tpQuery->where('created_at', '<=', $expirationDate);
            $projectsQuery->where('created_at', '<=', $expirationDate);
        }

        $formationsDisponibles = 0;
        try {
            if (Schema::hasTable('formations') && Schema::hasColumn('formations', 'modules')) {
                $formationsQuery = DB::table('formations')
                    ->where('status', 'active')
                    ->where(function ($query) {
                        $query->whereJsonContains('modules', 'design-graphique')
                            ->orWhereJsonContains('modules', 'Design Graphique')
                            ->orWhereJsonContains('modules', 'design_graphique')
                            ->orWhere('modules', 'like', '%design-graphique%')
                            ->orWhere('modules', 'like', '%Design Graphique%')
                            ->orWhere('modules', 'like', '%design_graphique%');
                    });

                if ($isExpiredNow) {
                    $formationsQuery->where('created_at', '<=', $expirationDate);
                }

                $formationsDisponibles = $formationsQuery->count();
            }
        } catch (\Exception $e) {
            $formationsDisponibles = 0;
        }

        $tpRealises = 0;
        $tpTotal = 0;

        if ($student && isset($student->id)) {
            $tpAssignmentsQuery = DB::table('tp_assignments')
                ->where(function ($q) use ($student, $user) {
                    $q->where('student_id', $student->id)
                        ->orWhere('student_id', $user->id);
                });

            if ($isExpiredNow) {
                $tpAssignmentsQuery->where('created_at', '<=', $expirationDate);
            }

            $tpRealises = (clone $tpAssignmentsQuery)
                ->where('status', 'validated')
                ->count();

            $tpTotal = $tpAssignmentsQuery->count();
        }

        if ($tpTotal === 0) {
            $tpRealises = (clone $tpQuery)
                ->whereIn('status', ['validated', 'completed'])
                ->count();
            $tpTotal = $tpQuery->count();
        }

        $evenementsQuery = DB::table('evenements')
            ->where('status', 'published')
            ->where('event_date', '>=', now()->toDateString())
            ->where(function ($query) {
                $query->where('visibility', 'all')
                    ->orWhere(function ($q) {
                        $q->where('visibility', 'specific')
                            ->where(function ($subq) {
                                $subq->whereJsonContains('formations', 'Design Graphique')
                                    ->orWhereJsonContains('formations', 'design_graphique')
                                    ->orWhereJsonContains('formations', 'Toutes')
                                    ->orWhere('formations', 'like', '%Design Graphique%')
                                    ->orWhere('formations', 'like', '%design_graphique%')
                                    ->orWhere('formations', 'like', '%Toutes%');
                            });
                    });
            });

        if ($isExpiredNow) {
            $evenementsQuery->where('created_at', '<=', $expirationDate);
        }

        $webinairesEnCours = $evenementsQuery->count();

        $actualitesEnCours = 0;
        try {
            if (Schema::hasTable('actualites')) {
                $actualitesQuery = DB::table('actualites')
                    ->where('status', 'published')
                    ->where(function ($query) {
                        $query->where('visibility', 'public')
                            ->orWhere('visibility', 'all')
                            ->orWhere(function ($q) {
                                $q->where('visibility', 'specific')
                                    ->where(function ($subq) {
                                        $subq->whereJsonContains('formations', 'Design Graphique')
                                            ->orWhereJsonContains('formations', 'design_graphique')
                                            ->orWhereJsonContains('formations', 'design-graphique')
                                            ->orWhereJsonContains('formations', 'Design Graphique & Community Manager')
                                            ->orWhereJsonContains('formations', 'Toutes')
                                            ->orWhere('formations', 'like', '%Design Graphique%')
                                            ->orWhere('formations', 'like', '%design_graphique%')
                                            ->orWhere('formations', 'like', '%design-graphique%')
                                            ->orWhere('formations', 'like', '%Design Graphique & Community Manager%')
                                            ->orWhere('formations', 'like', '%Toutes%');
                                    });
                            });
                    });

                if ($isExpiredNow) {
                    $actualitesQuery->where('created_at', '<=', $expirationDate);
                }

                $actualitesEnCours = $actualitesQuery->count();
            }
        } catch (\Exception $e) {
            $actualitesEnCours = 0;
        }

        // Calculer le montant restant à payer (cohérent avec paiementsIndex)
        $montantRestant = 0;
        try {
            if ($preReg && isset($preReg->montant_total) && isset($preReg->montant_paye)) {
                $montantRestant = max(0, (float) $preReg->montant_total - (float) $preReg->montant_paye);
            } else {
                $paymentsQuery = DB::table('payments')
                    ->where(function ($q) use ($user, $preReg) {
                        $q->where('user_id', $user->id);
                        if ($preReg && isset($preReg->id)) {
                            $q->orWhere('pre_registration_id', $preReg->id);
                        }
                    });

                $payments = $paymentsQuery->get();

                $formationLabel = $preReg ? (new \App\Http\Controllers\Admin\PreRegistrationAdminController())->getFormationLabel($preReg->choix_formation ?? null) : null;
                $firstPaymentDate = optional($payments->sortBy('created_at')->first())->created_at;
                $pricingDate = $firstPaymentDate ?: ($preReg->created_at ?? null);
                $grossPaymentAmount = $formationLabel ? (float) \App\Services\CinetPayService::getFormationPrice($formationLabel, $pricingDate) : 0;
                $discountAmount = min((float) ($preReg->discount_amount ?? 0), $grossPaymentAmount);
                $expectedAmount = max(0, $grossPaymentAmount - $discountAmount);
                $paymentsTotal = (float) ($payments->max('total_amount') ?? 0);
                $sumAmounts = (float) $payments->sum('amount');
                $paymentAmount = $discountAmount > 0 ? max($expectedAmount, $sumAmounts) : max($paymentsTotal, $expectedAmount, $sumAmounts);

                $paymentPaid = (float) $payments->where('status', 'completed')->sum('amount');
                $montantRestant = max(0, $paymentAmount - $paymentPaid);
            }
        } catch (\Exception $e) {
            $montantRestant = 0;
        }

        $stats = [
            'formations_disponibles' => $formationsDisponibles,
            'tp_realises' => $tpRealises,
            'tp_total' => $tpTotal,
            'projets_realises' => (clone $projectsQuery)
                ->whereIn('status', ['completed', 'validated'])
                ->count(),
            'projets_total' => $projectsQuery->count(),
            'webinaires_en_cours' => $webinairesEnCours,
            'evenements_en_cours' => $webinairesEnCours,
            'actualites_en_cours' => $actualitesEnCours,
            'montant_restant' => $montantRestant,
        ];

        $globalProgress = 0;
        if ($stats['tp_total'] > 0) {
            $globalProgress += ($stats['tp_realises'] / $stats['tp_total']) * 50;
        }
        if ($stats['projets_total'] > 0) {
            $globalProgress += ($stats['projets_realises'] / $stats['projets_total']) * 50;
        }

        return response()->json([
            'stats' => $stats,
            'global_progress' => $globalProgress,
            'generated_at' => now()->toIso8601String(),
        ]);
    }

    /**
     * Afficher la liste des TP (index) avec statistiques
     * Pour le profil combiné "Design Graphique & Community Management", affiche les TP des deux formations
     */
    public function listTP(Request $request): View
    {
        $user = Auth::user();
        $student = DB::table('students')->where('user_id', $user->id)->first();

        $projects = collect([]);

        // Détecter si c'est le profil combiné Design Graphique & Community Management
        $isCombinedProfile = false;
        if ($student) {
            $program = strtolower($student->program ?? '');
            $isCombinedProfile = str_contains($program, 'design') && str_contains($program, 'community');
        }

        // 1. Récupérer les TP créés par l'étudiant (table tp - Design Graphique legacy)
        // On normalise les données pour qu'elles aient la même structure que tp_assignments
        $tpCreated = TP::where('user_id', $user->id)
            ->where('title', 'NOT LIKE', '%rapport%')
            ->with(['files'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($tp) {
                return (object) [
                    'id' => $tp->id,
                    'title' => $tp->title,
                    'description' => $tp->description,
                    'link' => $tp->link,
                    'status' => $tp->status,
                    'created_at' => $tp->created_at,
                    'updated_at' => $tp->updated_at,
                    'files_count' => $tp->files->count(),
                    'source_table' => 'tp',
                    'type' => 'digital',
                    'formation' => $tp->formation ?? 'Design Graphique'
                ];
            });

        $projects = $projects->merge($tpCreated);

        // 2. Récupérer les TP assignés (table tp_assignments)
        // Pour le profil combiné, on récupère les TP de Design Graphique ET Community Management
        if ($student) {
            // Sous-requête pour compter les fichiers de soumission
            $fileCounts = DB::table('tp_submission_files')
                ->select('tp_assignment_id', DB::raw('COUNT(*) as files_count'))
                ->groupBy('tp_assignment_id');

            $tpAssignmentsQuery = DB::table('tp_assignments')
                ->leftJoinSub($fileCounts, 'files', function ($join) {
                    $join->on('tp_assignments.id', '=', 'files.tp_assignment_id');
                })
                ->where('tp_assignments.student_id', $student->id);

            // Pour le profil combiné, on filtre pour inclure les deux formations
            if ($isCombinedProfile) {
                $tpAssignmentsQuery->where(function ($query) {
                    $query->where('tp_assignments.formation', 'LIKE', '%Design%')
                        ->orWhere('tp_assignments.formation', 'LIKE', '%Graphique%')
                        ->orWhere('tp_assignments.formation', 'LIKE', '%Community%')
                        ->orWhere('tp_assignments.formation', 'LIKE', '%CM%');
                });
            }

            $tpAssignments = $tpAssignmentsQuery
                ->select(
                    'tp_assignments.id',
                    'tp_assignments.title',
                    'tp_assignments.description',
                    'tp_assignments.submission_link as link',
                    'tp_assignments.status',
                    'tp_assignments.created_at',
                    'tp_assignments.updated_at',
                    'tp_assignments.formation',
                    DB::raw('COALESCE(files.files_count, 0) as files_count'),
                    DB::raw("'tp_assignments' as source_table"),
                    DB::raw("'digital' as type")
                )
                ->orderByDesc('tp_assignments.created_at')
                ->get();

            $projects = $projects->merge($tpAssignments);
        }

        // Normaliser les noms de formation
        $projects = $projects->map(function ($p) {
            $f = strtolower($p->formation ?? '');
            if (str_contains($f, 'design') || str_contains($f, 'graphique') || str_contains($f, 'infographie')) {
                $p->formation = 'Design Graphique';
            } elseif (str_contains($f, 'community') || str_contains($f, 'cm')) {
                $p->formation = 'Community Management';
            } elseif (str_contains($f, 'informatique') || str_contains($f, 'dev')) {
                $p->formation = 'Gestion Informatique';
            } elseif (str_contains($f, 'intelligence') || str_contains($f, 'ia')) {
                $p->formation = 'Intelligence Artificielle';
            }
            return $p;
        });

        // Trier par date de création décroissante
        $projects = $projects->sortByDesc('created_at');

        // Pagination manuelle
        $page = $request->get('page', 1);
        $perPage = 9;
        $tpsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $projects->forPage($page, $perPage),
            $projects->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Calcul des statistiques
        $total = $projects->count();
        $validated = $projects->where('status', 'validated')->count();
        $rejected = $projects->where('status', 'rejected')->count();
        // Pour les TP assignés, 'assigned' est un état en attente d'action de l'étudiant
        // 'submitted' et 'pending' sont en attente de correction
        $submitted = $projects->whereIn('status', ['submitted', 'pending'])->count();
        $assigned = $projects->where('status', 'assigned')->count();

        // Stats pour la vue
        $statistiques = [
            'tp_realises' => $validated + $submitted + $rejected, // Travaux soumis (y compris traités)
            'tp_total' => $total,
            'tp_a_faire' => $assigned,
            'progression_pourcentage' => $total > 0 ? round(($validated / $total) * 100) : 0,
        ];

        $validationStats = [
            'tp_valides' => $validated,
            'tp_en_validation' => $submitted, // En attente de correction/validation
        ];

        return view('tp.index', [
            'tps' => $tpsPaginated,
            'statistiques' => $statistiques,
            'validationStats' => $validationStats,
            'user' => $user
        ]);
    }

    /**
     * Afficher tous les TP de l'utilisateur (Design Graphique)
     */
    public function showAllTP(): View
    {
        $user = Auth::user();

        $currentModule = request()->segment(3);
        if ($currentModule === 'community-manager') {
            $currentModule = 'community-management';
        }

        // Pour Community Management ET Design Graphique & CM : récupérer les deux sources de TP
        if ($currentModule === 'community-management' || $currentModule === 'design-graphique-cm') {
            $student = DB::table('students')
                ->where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();

            $projects = collect([]);

            // Récupérer les TP créés par l'étudiant (table tp), exclure les rapports
            $tpCreated = TP::where('user_id', $user->id)
                ->where('title', 'NOT LIKE', '%rapport%')
                ->with(['files'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($tp) {
                    return (object) [
                        'id' => $tp->id,
                        'title' => $tp->title,
                        'description' => $tp->description,
                        'link' => $tp->link,
                        'status' => $tp->status,
                        'created_at' => $tp->created_at,
                        'updated_at' => $tp->updated_at,
                        'files_count' => $tp->files->count(),
                        'source_table' => 'tp',
                        'formation' => 'Design Graphique' // Défaut pour la table legacy
                    ];
                });

            $projects = $projects->merge($tpCreated);

            // Récupérer les TP assignés (table tp_assignments)
            if ($student) {
                $fileCounts = DB::table('tp_submission_files')
                    ->select('tp_assignment_id', DB::raw('COUNT(*) as files_count'))
                    ->groupBy('tp_assignment_id');

                $tpAssignments = DB::table('tp_assignments')
                    ->leftJoinSub($fileCounts, 'files', function ($join) {
                        $join->on('tp_assignments.id', '=', 'files.tp_assignment_id');
                    })
                    ->where('tp_assignments.student_id', $student->id)
                    ->select(
                        'tp_assignments.id',
                        'tp_assignments.title',
                        'tp_assignments.description',
                        'tp_assignments.submission_link as link',
                        'tp_assignments.status',
                        'tp_assignments.created_at',
                        'tp_assignments.updated_at',
                        'tp_assignments.formation', // Inclure la formation
                        DB::raw('COALESCE(files.files_count, 0) as files_count'),
                        DB::raw("'tp_assignments' as source_table")
                    )
                    ->orderByDesc('tp_assignments.created_at')
                    ->get();

                $projects = $projects->merge($tpAssignments);
            }

            // Trier tous les projets par date de création
            $projects = $projects->sortByDesc('created_at');

            $stats = [
                'total' => $projects->count(),
                'validated' => $projects->where('status', 'validated')->count(),
                'pending' => $projects->whereIn('status', ['pending', 'submitted', 'assigned'])->count(),
                'rejected' => $projects->where('status', 'rejected')->count(),
            ];

            $formationSlug = $this->getFormationSlug($student);

            return view('tp.all', [
                'projects' => $projects,
                'stats' => $stats,
                'userProfile' => $user,
                'formationSlug' => $formationSlug
            ]);
        }

        // Récupérer tous les TP de l'utilisateur avec leurs fichiers (Fallback pour Design Graphique seul)
        $projects = TP::where('user_id', $user->id)
            ->with(['files'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($tp) {
                $tp->formation = 'Design Graphique';
                return $tp;
            });

        // Calculer les statistiques
        $stats = [
            'total' => $projects->count(),
            'validated' => $projects->where('status', 'validated')->count(),
            'pending' => $projects->where('status', 'pending')->count(),
            'rejected' => $projects->where('status', 'rejected')->count(),
        ];

        return view('tp.all', [
            'projects' => $projects,
            'stats' => $stats,
            'userProfile' => $user
        ]);
    }

    /**
     * Afficher un TP spécifique
     */
    public function viewTP(Request $request, $id): View
    {
        $user = Auth::user();
        $source = $request->query('source');

        // Récupérer l'étudiant
        $student = DB::table('students')->where('user_id', $user->id)->first();
        $project = null;

        // Si source spécifiée, on cherche uniquement dans cette table
        if ($source === 'tp') {
            $project = TP::where('id', $id)
                ->where('user_id', $user->id)
                ->with(['files'])
                ->first();

            if ($project) {
                $project->source_table = 'tp';
                $project->type = 'digital';
            }
        } elseif ($source === 'tp_assignments') {
            if ($student) {
                $project = $this->getTpAssignmentProject($id, $student->id);
            }
        } else {
            // Comportement par défaut (rétrocompatibilité)
            // 1. Chercher dans tp (Design Graphique)
            $project = TP::where('id', $id)
                ->where('user_id', $user->id)
                ->with(['files'])
                ->first();

            if ($project) {
                $project->source_table = 'tp';
                $project->type = 'digital';
            }

            // 2. Si pas trouvé, chercher dans tp_assignments
            if (!$project && $student) {
                $project = $this->getTpAssignmentProject($id, $student->id);
            }
        }

        // Si toujours pas trouvé, retourner 404
        if (!$project) {
            abort(404, 'TP introuvable');
        }

        // Déterminer la formation pour les routes dynamiques
        $formationSlug = $this->getFormationSlug($student);

        return view('tp.view', [
            'project' => $project,
            'formationSlug' => $formationSlug
        ]);
    }

    /**
     * Helper pour récupérer un projet depuis tp_assignments
     */
    private function getTpAssignmentProject($id, $studentId)
    {
        $tpAssignment = DB::table('tp_assignments')
            ->where('id', $id)
            ->where('student_id', $studentId)
            ->first();

        if (!$tpAssignment) {
            return null;
        }

        // Récupérer les fichiers de soumission
        $submissionFiles = DB::table('tp_submission_files')
            ->where('tp_assignment_id', $id)
            ->get();

        // Mapper les fichiers
        $files = $submissionFiles->map(function ($file) {
            $path = ltrim((string) ($file->file_path ?? ''), '/');
            if (str_starts_with($path, 'storage/app/public/')) {
                $path = substr($path, strlen('storage/app/public/'));
            }
            return (object) [
                'id' => $file->id,
                'original_name' => $file->file_name ?? 'Fichier',
                'file_path' => $path,
                'file_size' => $file->file_size ?? 0,
                'mime_type' => $file->mime_type ?? 'application/octet-stream',
                'created_at' => $file->created_at,
                'updated_at' => $file->updated_at
            ];
        });

        // Convertir en objet
        return (object) [
            'id' => $tpAssignment->id,
            'title' => $tpAssignment->title,
            'description' => $tpAssignment->description,
            'link' => $tpAssignment->submission_link,
            'status' => $tpAssignment->status,
            'admin_comment' => $tpAssignment->admin_comment,
            'rejection_reason' => $tpAssignment->admin_comment,
            'validated_at' => $tpAssignment->validated_at,
            'created_at' => $tpAssignment->created_at,
            'updated_at' => $tpAssignment->updated_at,
            'files' => $files,
            'source_table' => 'tp_assignments',
            'tags' => null,
            'software_used' => null,
            'type' => 'digital',
            'deadline' => $tpAssignment->deadline ?? null,
            'formation' => $tpAssignment->formation ?? null
        ];
    }

    /**
     * Afficher le formulaire de modification d'un TP
     */
    public function editTP($id): View|RedirectResponse
    {
        $user = Auth::user();
        $student = DB::table('students')->where('user_id', $user->id)->first();

        // D'abord, essayer de trouver le TP dans la table tp (créé par l'étudiant lui-même)
        $project = TP::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['files'])
            ->first();

        // Si trouvé dans la table tp, permettre la modification même si étudiant en Community Management
        if ($project) {
            // Vérifier que le TP n'est pas déjà validé
            if ($project->status === 'validated') {
                $formationSlug = $this->getFormationSlug($student);
                return redirect()->route($formationSlug . '.tp.voir', $id)
                    ->with('error', 'Vous ne pouvez pas modifier un TP déjà validé.');
            }

            // Déterminer la formation pour les routes dynamiques
            $formationSlug = $this->getFormationSlug($student);

            return view('tp.edit', [
                'project' => $project,
                'formationSlug' => $formationSlug
            ]);
        }

        // Si pas trouvé dans tp, chercher dans tp_assignments (Community Management)
        if ($student) {
            $tpAssignment = DB::table('tp_assignments')
                ->where('id', $id)
                ->where('student_id', $student->id)
                ->first();

            if ($tpAssignment) {
                // Pour Community Management, les TP assignés ne peuvent pas être modifiés
                $formationSlug = $this->getFormationSlug($student);
                return redirect()->route($formationSlug . '.tp.voir', $id)
                    ->with('error', 'Les TP assignés par l\'administration ne peuvent pas être modifiés. Vous pouvez seulement soumettre votre travail.');
            }
        }

        // Si toujours pas trouvé, retourner 404
        abort(404, 'TP introuvable');
    }

    /**
     * Afficher le formulaire de création d'un TP
     */
    public function createTP(): View|RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        // Vérifier le type de projet (print ou digital)
        $type = request()->query('type', 'digital');

        // Charger la vue appropriée selon le type
        if ($type === 'print') {
            return view('tp.create-print');
        }

        return view('tp.create');
    }

    /**
     * MÉTHODE HELPER: Sauvegarder UN SEUL fichier
     */
    private function saveOneFile($file, int $tpId, int $fileIndex): array
    {
        try {
            // ÉTAPE 1: Valider le fichier
            if (!$file->isValid()) {
                return ['success' => false, 'error' => 'Fichier invalide'];
            }

            // ÉTAPE 2: Extraire les informations
            $originalName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();
            $extension = $file->getClientOriginalExtension();

            // ÉTAPE 4: Générer nom unique
            $fileName = time() . '_' . $fileIndex . '_' . uniqid() . '.' . $extension;

            $directory = 'tp/' . $tpId . '/files';
            $relativePath = $file->storeAs($directory, $fileName, 'public');


            // ÉTAPE 7: Insérer en base de données
            $insertId = DB::table('tp_files')->insertGetId([
                'tp_id' => $tpId,
                'original_name' => $originalName,
                'file_path' => $relativePath,
                'file_size' => $fileSize,
                'mime_type' => $mimeType,
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            return [
                'success' => true,
                'file_id' => $insertId,
                'file_name' => $originalName
            ];
        } catch (\Exception $e) {
            Log::error("❌ Erreur fichier $fileIndex: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function traiterAssignedProject($projectId): View
    {
        $user = Auth::user();

        // Récupérer l'étudiant pour déterminer la formation
        $student = DB::table('students')->where('user_id', $user->id)->first();
        $formationPrefix = $this->getFormationSlug($student);

        // Chercher d'abord dans projects
        $assignedProject = DB::table('projects')
            ->where('id', $projectId)
            ->where('user_id', $user->id)
            ->first();

        $briefFiles = collect([]);
        $submittedFiles = collect([]);
        $isTpAssignment = false;

        // Si pas trouvé dans projects, chercher dans tp_assignments
        if (!$assignedProject && $student) {
            $assignedProject = DB::table('tp_assignments')
                ->where('id', $projectId)
                ->where('student_id', $student->id)
                ->first();

            if ($assignedProject) {
                $isTpAssignment = true;
                // Récupérer les fichiers du brief depuis tp_assignment_files
                $briefFiles = DB::table('tp_assignment_files')
                    ->where('tp_assignment_id', $assignedProject->id)
                    ->get()
                    ->map(function ($file) {
                        $path = ltrim((string) $file->file_path, '/');
                        if (str_starts_with($path, 'storage/app/public/')) {
                            $path = substr($path, strlen('storage/app/public/'));
                        }
                        $file->url = \App\Models\MediaUrl::fromPath($path);
                        $file->name = $file->file_name ?? 'fichier';
                        return $file;
                    });

                if (Schema::hasTable('tp_submission_files')) {
                    $submittedFiles = DB::table('tp_submission_files')
                        ->where('tp_assignment_id', $assignedProject->id)
                        ->orderBy('created_at', 'desc')
                        ->get()
                        ->map(function ($file) {
                            $path = ltrim((string) $file->file_path, '/');
                            if (str_starts_with($path, 'storage/app/public/')) {
                                $path = substr($path, strlen('storage/app/public/'));
                            }
                            $file->url = \App\Models\MediaUrl::fromPath($path);
                            $file->name = $file->file_name ?? 'fichier';
                            return $file;
                        });
                }
            }
        } else if ($assignedProject) {
            $projectFiles = DB::table('project_images')
                ->where('project_id', $assignedProject->id)
                ->orderBy('order_index', 'asc')
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($file) {
                    $path = ltrim((string) $file->file_path, '/');
                    if (str_starts_with($path, 'storage/app/public/')) {
                        $path = substr($path, strlen('storage/app/public/'));
                    }
                    $file->url = \App\Models\MediaUrl::fromPath($path);
                    $file->name = $file->original_name ?? $file->filename ?? 'fichier';
                    return $file;
                });

            $submittedFiles = $projectFiles->filter(function ($file) use ($assignedProject) {
                return str_contains((string) ($file->file_path ?? ''), 'project_submissions/' . $assignedProject->id . '/');
            })->values();

            $briefFiles = $projectFiles->filter(function ($file) use ($assignedProject) {
                return !str_contains((string) ($file->file_path ?? ''), 'project_submissions/' . $assignedProject->id . '/');
            })->values();

            if ($submittedFiles->isEmpty() && Schema::hasTable('design_projects') && Schema::hasTable('design_project_files')) {
                $designProject = DB::table('design_projects')
                    ->where('user_id', $user->id)
                    ->where('title', $assignedProject->title)
                    ->orderByDesc('created_at')
                    ->first();

                if ($designProject) {
                    $submittedFiles = DB::table('design_project_files')
                        ->where('project_id', $designProject->id)
                        ->orderBy('created_at', 'asc')
                        ->get()
                        ->map(function ($file) {
                            $path = ltrim((string) $file->file_path, '/');
                            if (str_starts_with($path, 'storage/app/public/')) {
                                $path = substr($path, strlen('storage/app/public/'));
                            }
                            $file->url = \App\Models\MediaUrl::fromPath($path);
                            $file->name = $file->original_name ?? 'fichier';
                            return $file;
                        });
                }
            }
        }

        if (!$assignedProject) {
            return view('todo.traiter', [
                'assignedProject' => null,
                'briefFiles' => collect([]),
                'submittedFiles' => collect([]),
                'error' => 'Projet non trouvé ou non autorisé.',
                'formationPrefix' => $formationPrefix,
            ]);
        }

        return view('todo.traiter', [
            'assignedProject' => $assignedProject,
            'briefFiles' => $briefFiles,
            'submittedFiles' => $submittedFiles,
            'formationPrefix' => $formationPrefix,
            'isTpAssignment' => $isTpAssignment,
        ]);
    }

    public function storeTreatedAssignedProject(Request $request, $projectId): RedirectResponse
    {
        $user = Auth::user();
        $student = DB::table('students')->where('user_id', $user->id)->first();
        $formationPrefix = $this->getFormationSlug($student);

        // Essayer d'abord dans projects (Design Graphique)
        $assignedProject = DB::table('projects')
            ->where('id', $projectId)
            ->where('user_id', $user->id)
            ->first();

        // Si pas trouvé, chercher dans tp_assignments (Community Management)
        $isTpAssignment = false;
        if (!$assignedProject && $student) {
            $assignedProject = DB::table('tp_assignments')
                ->where('id', $projectId)
                ->where('student_id', $student->id)
                ->first();
            $isTpAssignment = true;
        }

        if (!$assignedProject) {
            return redirect()->back()->with('error', 'Projet non trouvé ou non autorisé.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_link' => 'nullable|url|max:2000',
            'links.*' => 'nullable|url|max:2000',
            'files' => 'nullable|array',
            'files.*' => 'file|max:10240|mimetypes:image/jpeg,image/jpg,image/png,image/gif,image/webp,application/pdf',
            'remove_file_ids' => 'nullable|array',
            'remove_file_ids.*' => 'integer',
        ]);

        $projectLink = trim((string) ($validated['project_link'] ?? ''));

        if ($projectLink === '') {
            $links = $request->input('links', []);
            if (is_array($links)) {
                $links = array_values(array_filter($links, fn($l) => is_string($l) && trim($l) !== ''));
                $projectLink = trim((string) ($links[0] ?? ''));
            }
        }
        $hasFiles = $request->hasFile('files') && is_array($request->file('files')) && count($request->file('files')) > 0;
        $removeFileIds = collect($request->input('remove_file_ids', []))
            ->map(fn($id) => (int) $id)
            ->filter(fn($id) => $id > 0)
            ->unique()
            ->values();

        $tpLinkColumn = null;
        try {
            if (Schema::hasTable('tp_assignments')) {
                if (Schema::hasColumn('tp_assignments', 'submission_link')) {
                    $tpLinkColumn = 'submission_link';
                } elseif (Schema::hasColumn('tp_assignments', 'link')) {
                    $tpLinkColumn = 'link';
                }
            }
        } catch (\Throwable $e) {
            $tpLinkColumn = null;
        }

        $projectLinkColumn = null;
        try {
            if (Schema::hasTable('projects')) {
                if (Schema::hasColumn('projects', 'link')) {
                    $projectLinkColumn = 'link';
                } elseif (Schema::hasColumn('projects', 'submission_link')) {
                    $projectLinkColumn = 'submission_link';
                }
            }
        } catch (\Throwable $e) {
            $projectLinkColumn = null;
        }

        $existingFilesCount = 0;
        if ($isTpAssignment && Schema::hasTable('tp_submission_files')) {
            $existingFilesCount = DB::table('tp_submission_files')
                ->where('tp_assignment_id', $projectId)
                ->whereNotIn('id', $removeFileIds->all())
                ->count();
        } elseif (!$isTpAssignment && Schema::hasTable('project_images')) {
            $existingFilesCount = DB::table('project_images')
                ->where('project_id', $assignedProject->id)
                ->whereNotIn('id', $removeFileIds->all())
                ->count();
        }

        if (!$hasFiles && $projectLink === '' && $existingFilesCount <= 0) {
            return redirect()->back()
                ->withErrors(['files' => 'Veuillez conserver ou ajouter au moins un fichier OU un lien du projet.'])
                ->withInput();
        }


        DB::beginTransaction();
        try {
            $uploadedCount = 0;

            if ($isTpAssignment) {
                if ($removeFileIds->isNotEmpty() && Schema::hasTable('tp_submission_files')) {
                    $filesToDelete = DB::table('tp_submission_files')
                        ->where('tp_assignment_id', $projectId)
                        ->whereIn('id', $removeFileIds->all())
                        ->get();

                    foreach ($filesToDelete as $fileToDelete) {
                        Storage::disk('public')->delete(ltrim((string) $fileToDelete->file_path, '/'));
                    }

                    DB::table('tp_submission_files')
                        ->where('tp_assignment_id', $projectId)
                        ->whereIn('id', $removeFileIds->all())
                        ->delete();
                }

                // Community Management: Mettre à jour tp_assignments
                $tpUpdateData = [
                    'title' => $validated['title'],
                    'description' => $validated['description'] ?? '',
                    'status' => 'submitted',
                    'updated_at' => now(),
                ];

                if (!empty($tpLinkColumn)) {
                    $tpUpdateData[$tpLinkColumn] = $projectLink !== ''
                        ? $projectLink
                        : ($assignedProject->{$tpLinkColumn} ?? null);
                }

                DB::table('tp_assignments')
                    ->where('id', $projectId)
                    ->where('student_id', $student->id)
                    ->update($tpUpdateData);

                // Uploader les fichiers dans tp_submission_files
                if ($request->hasFile('files')) {
                    if (!Schema::hasTable('tp_submission_files')) {
                        Log::error('Table tp_submission_files inexistante: fichiers non enregistrés', [
                            'tp_assignment_id' => $projectId,
                            'student_id' => $student->id,
                        ]);
                    } else {
                        foreach ($request->file('files') as $file) {
                            if (!$file || !$file->isValid()) {
                                continue;
                            }

                            try {
                                $uploadedCount++;
                                $originalName = $file->getClientOriginalName();
                                $extension = $file->getClientOriginalExtension();
                                $fileSize = $file->getSize();
                                $mimeType = $file->getMimeType();
                                $storedName = time() . '_' . Str::random(10) . '.' . $extension;

                                $directory = 'tp_submissions/' . $projectId . '/' . $student->id;
                                $filePath = $file->storeAs($directory, $storedName, 'public');

                                DB::table('tp_submission_files')->insert([
                                    'tp_assignment_id' => $projectId,
                                    'file_name' => $originalName,
                                    'file_path' => $filePath,
                                    'file_size' => $fileSize,
                                    'mime_type' => $mimeType,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            } catch (\Throwable $uploadError) {
                                Log::error('Erreur upload fichier TP', [
                                    'tp_assignment_id' => $projectId,
                                    'student_id' => $student->id,
                                    'file_name' => method_exists($file, 'getClientOriginalName') ? $file->getClientOriginalName() : null,
                                    'error' => $uploadError->getMessage(),
                                ]);
                            }
                        }
                    }
                }

                // Notification email aux admins pour TP assignments
                try {
                    $admins = DB::table('admins')
                        ->whereIn('role', ['super_admin', 'assistant'])
                        ->get();

                    $tpForEmail = (object) [
                        'id' => $projectId,
                        'title' => $validated['title'],
                        'description' => $validated['description'] ?? '',
                    ];

                    $studentForEmail = DB::table('users')->where('id', $user->id)->first();

                    // Déterminer la bonne page pending admin selon la formation
                    $tpAdminSlug = 'design-cm';
                    if ($student && !empty($student->program)) {
                        $tpProg = strtolower((string) $student->program);
                        $tpHasDesign = str_contains($tpProg, 'design');
                        $tpHasCommunity = str_contains($tpProg, 'community');
                        if ($tpHasDesign && $tpHasCommunity) {
                            $tpAdminSlug = 'design-cm';
                        } elseif ($tpHasCommunity) {
                            $tpAdminSlug = 'cm-smm';
                        } elseif ($tpHasDesign) {
                            $tpAdminSlug = 'design-graphique';
                        }
                    }
                    $viewUrl = url('/evc/app/admin/projets/' . $tpAdminSlug . '/pending');

                    foreach ($admins as $admin) {
                        if (empty($admin->email)) {
                            continue;
                        }

                        try {
                            Mail::send('emails.admin-new-project-notification', [
                                'student' => $studentForEmail,
                                'project' => $tpForEmail,
                                'filesCount' => $uploadedCount,
                                'viewUrl' => $viewUrl,
                            ], function ($message) use ($admin, $tpForEmail) {
                                $message->to($admin->email)
                                    ->subject('🚀 Nouveau TP soumis : ' . $tpForEmail->title);
                            });
                        } catch (\Exception $emailError) {
                            Log::error('Erreur envoi email admin (TP soumis): ' . $emailError->getMessage());
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Erreur globale notification admins (TP soumis): ' . $e->getMessage());
                }
            } else {
                if ($removeFileIds->isNotEmpty() && Schema::hasTable('project_images')) {
                    $filesToDelete = DB::table('project_images')
                        ->where('project_id', $assignedProject->id)
                        ->whereIn('id', $removeFileIds->all())
                        ->get();

                    foreach ($filesToDelete as $fileToDelete) {
                        Storage::disk('public')->delete(ltrim((string) $fileToDelete->file_path, '/'));
                    }

                    DB::table('project_images')
                        ->where('project_id', $assignedProject->id)
                        ->whereIn('id', $removeFileIds->all())
                        ->delete();
                }

                // Design Graphique: soumission sur un projet assigné
                $designProjectId = null;

                if (!app()->environment('production') && Schema::hasTable('design_projects') && Schema::hasTable('design_project_files')) {
                    // Environnements où les tables de soumission existent
                    $designProjectData = [
                        'user_id' => $user->id,
                        'title' => $validated['title'],
                        'description' => $validated['description'] ?? null,
                        'category' => 'solo',
                        'project_type' => null,
                        'software_used' => null,
                        'status' => 'pending',
                        'admin_comment' => null,
                        'validated_at' => null,
                        'completed_at' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    if ($projectLink !== '') {
                        if (Schema::hasColumn('design_projects', 'reference_url')) {
                            $designProjectData['reference_url'] = $projectLink;
                        } elseif (Schema::hasColumn('design_projects', 'reference_link')) {
                            $designProjectData['reference_link'] = $projectLink;
                        } elseif (Schema::hasColumn('design_projects', 'link')) {
                            $designProjectData['link'] = $projectLink;
                        }
                    }

                    $designProjectId = DB::table('design_projects')->insertGetId($designProjectData);

                    if ($request->hasFile('files')) {
                        $thumbSet = false;

                        foreach ($request->file('files') as $file) {
                            if (!$file || !$file->isValid()) {
                                continue;
                            }

                            try {

                                $uploadedCount++;
                                $originalName = $file->getClientOriginalName();
                                $extension = $file->getClientOriginalExtension();
                                $fileSize = $file->getSize();
                                $mimeType = $file->getMimeType();
                                $storedName = time() . '_' . Str::random(10) . '.' . $extension;

                                $directory = 'design_projects/' . $designProjectId . '/other';
                                $filePath = $file->storeAs($directory, $storedName, 'public');

                                $fileType = (is_string($mimeType) && str_starts_with($mimeType, 'image/')) ? 'image' : 'document';
                                $isThumbnail = false;
                                if (!$thumbSet && $fileType === 'image') {
                                    $isThumbnail = true;
                                    $thumbSet = true;
                                }

                                DB::table('design_project_files')->insert([
                                    'project_id' => $designProjectId,
                                    'original_name' => $originalName,
                                    'file_path' => $filePath,
                                    'file_size' => $fileSize,
                                    'mime_type' => $mimeType,
                                    'file_type' => $fileType,
                                    'is_thumbnail' => $isThumbnail,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            } catch (\Throwable $uploadError) {
                                Log::error('Erreur upload fichier projet assigné (design_project_files)', [
                                    'project_id' => $assignedProject->id,
                                    'design_project_id' => $designProjectId,
                                    'user_id' => $user->id,
                                    'file_name' => method_exists($file, 'getClientOriginalName') ? $file->getClientOriginalName() : null,
                                    'error' => $uploadError->getMessage(),
                                ]);
                            }
                        }
                    }
                } else {
                    // Fallback (prod) : stocker les fichiers sur project_images du projet assigné
                    if ($request->hasFile('files')) {
                        if (!Schema::hasTable('project_images')) {
                            Log::error('Table project_images inexistante: fichiers non enregistrés', [
                                'project_id' => $assignedProject->id,
                                'user_id' => $user->id,
                            ]);
                        } else {
                            $thumbSet = false;
                            $order = 0;

                            foreach ($request->file('files') as $file) {
                                if (!$file || !$file->isValid()) {
                                    continue;
                                }

                                try {
                                    $uploadedCount++;
                                    $originalName = $file->getClientOriginalName();
                                    $extension = $file->getClientOriginalExtension();
                                    $fileSize = $file->getSize();
                                    $mimeType = $file->getMimeType();
                                    $storedName = time() . '_' . Str::random(10) . '.' . $extension;

                                    $directory = 'project_submissions/' . $assignedProject->id . '/' . $user->id;
                                    $filePath = $file->storeAs($directory, $storedName, 'public');

                                    $isThumbnail = false;
                                    if (!$thumbSet && is_string($mimeType) && str_starts_with($mimeType, 'image/')) {
                                        $isThumbnail = true;
                                        $thumbSet = true;
                                    }

                                    DB::table('project_images')->insert([
                                        'project_id' => $assignedProject->id,
                                        'filename' => $storedName,
                                        'original_name' => $originalName,
                                        'mime_type' => (string) $mimeType,
                                        'file_size' => $fileSize,
                                        'file_path' => $filePath,
                                        'is_thumbnail' => $isThumbnail,
                                        'order_index' => $order,
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                    ]);
                                } catch (\Throwable $uploadError) {
                                    Log::error('Erreur upload fichier projet assigné (project_images fallback)', [
                                        'project_id' => $assignedProject->id,
                                        'user_id' => $user->id,
                                        'file_name' => method_exists($file, 'getClientOriginalName') ? $file->getClientOriginalName() : null,
                                        'error' => $uploadError->getMessage(),
                                    ]);
                                }

                                $order++;
                            }
                        }
                    }
                }

                $projectUpdateData = [
                    'title' => $validated['title'],
                    'description' => $validated['description'] ?? ($assignedProject->description ?? ''),
                    'status' => 'termine',
                    'updated_at' => now(),
                ];

                if (!empty($projectLinkColumn)) {
                    $projectUpdateData[$projectLinkColumn] = $projectLink !== ''
                        ? $projectLink
                        : ($assignedProject->{$projectLinkColumn} ?? null);
                }

                DB::table('projects')
                    ->where('id', $assignedProject->id)
                    ->where('user_id', $user->id)
                    ->update($projectUpdateData);
            }

            DB::commit();

            // Notification email aux admins (optionnel, ne doit pas bloquer la publication)
            if (!$isTpAssignment) {
                try {
                    $admins = DB::table('admins')
                        ->whereIn('role', ['super_admin', 'assistant'])
                        ->get();

                    $emailProjectId = $designProjectId ?? ($assignedProject->id ?? null);

                    $projectForEmail = (object) [
                        'id' => $emailProjectId,
                        'title' => $validated['title'],
                        'description' => $validated['description'] ?? null,
                        'reference_url' => null,
                    ];

                    $studentForEmail = DB::table('users')->where('id', $user->id)->first();

                    // Déterminer la bonne page pending admin selon la formation
                    $adminPendingSlug = 'design-graphique';
                    if ($student && !empty($student->program)) {
                        $prog = strtolower((string) $student->program);
                        $hasDesign = str_contains($prog, 'design');
                        $hasCommunity = str_contains($prog, 'community');
                        if ($hasDesign && $hasCommunity) {
                            $adminPendingSlug = 'design-cm';
                        } elseif ($hasCommunity) {
                            $adminPendingSlug = 'cm-smm';
                        }
                    }
                    $viewUrl = url('/evc/app/admin/projets/' . $adminPendingSlug . '/pending');

                    foreach ($admins as $admin) {
                        if (empty($admin->email)) {
                            continue;
                        }

                        try {
                            Mail::send('emails.admin-new-project-notification', [
                                'student' => $studentForEmail,
                                'project' => $projectForEmail,
                                'filesCount' => $uploadedCount,
                                'viewUrl' => $viewUrl,
                            ], function ($message) use ($admin, $projectForEmail) {
                                $message->to($admin->email)
                                    ->subject('🚀 Nouveau projet soumis : ' . $projectForEmail->title);
                            });
                        } catch (\Exception $emailError) {
                            Log::error('Erreur envoi email admin (projet publié): ' . $emailError->getMessage());
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Erreur globale notification admins (projet publié): ' . $e->getMessage());
                }
            }

            return redirect()->route($formationPrefix . '.todo.index')
                ->with('success', 'Projet soumis avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur publication projet assigné: ' . $e->getMessage(), [
                'project_id' => $projectId,
                'is_tp_assignment' => $isTpAssignment,
                'user_id' => $user->id ?? null,
                'student_id' => $student->id ?? null,
            ]);
            Log::error('Erreur publication projet assigné trace', [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Erreur lors de la publication.')->withInput();
        }
    }

    public function retirerAssignedProject($projectId): RedirectResponse
    {
        $userId = Auth::id();
        $student = DB::table('students')->where('user_id', $userId)->first();
        $formationPrefix = $this->getFormationSlug($student);

        $updated = DB::table('projects')
            ->where('id', $projectId)
            ->where('user_id', $userId)
            ->whereNotIn('status', ['valide', 'validated'])
            ->update(['status' => 'en_cours', 'updated_at' => now()]);

        if ($updated <= 0 && $student) {
            $updated = DB::table('tp_assignments')
                ->where('id', $projectId)
                ->where('student_id', $student->id)
                ->whereNotIn('status', ['validated'])
                ->update(['status' => 'assigned', 'updated_at' => now()]);
        }

        return $updated > 0
            ? redirect()->route($formationPrefix . '.projets.historique')->with('success', 'Soumission retirée avec succès.')
            : redirect()->back()->with('error', 'Projet non trouvé, déjà validé, ou non autorisé.');
    }

    /**
     * Enregistrer un nouveau TP - VERSION COMPLÈTEMENT RÉÉCRITE ÉTAPE PAR ÉTAPE
     */
    public function storeTP(Request $request): RedirectResponse
    {
        // ========================================
        // ÉTAPE 1: VÉRIFIER L'AUTHENTIFICATION
        // ========================================
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté.');
        }

        // Récupérer le module actuel depuis l'URL
        $currentModule = $request->segment(3); // ex: community-management, design-graphique, etc.


        try {
            // ========================================
            // ÉTAPE 2: VALIDER LES DONNÉES
            // ========================================
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:2000',
                'formation' => 'nullable|string|in:Design Graphique,Community Management',
                'links.*' => 'nullable|url|max:500',
                'files' => 'required|array|min:1',
                'files.*' => 'required|file|max:20480|mimes:jpg,jpeg,png,gif,webp,svg,pdf,psd,ai,doc,docx,zip,rar'
            ], [
                'title.required' => 'Le titre du TP est obligatoire.',
                'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
                'description.max' => 'La description ne peut pas dépasser 2000 caractères.',
                'formation.in' => 'Le type de réalisation doit être Design Graphique ou Community Management.',
                'links.*.url' => 'Le lien doit être une URL valide.',
                'links.*.max' => 'Le lien ne peut pas dépasser 500 caractères.',
                'files.required' => '⚠️ Vous devez ajouter au moins une image ou un fichier pour publier votre TP.',
                'files.min' => '⚠️ Vous devez ajouter au moins une image ou un fichier pour publier votre TP.',
                'files.*.required' => 'Chaque fichier est obligatoire.',
                'files.*.max' => 'Chaque fichier ne peut pas dépasser 20MB.',
                'files.*.mimes' => 'Types de fichiers autorisés: JPG, JPEG, PNG, GIF, WEBP, SVG, PDF, PSD, AI, DOC, DOCX, ZIP, RAR.'
            ]);

            // Récupérer le premier lien non vide
            $link = null;
            if ($request->has('links')) {
                $links = array_filter($request->input('links'), function ($l) {
                    return !empty($l);
                });
                $link = !empty($links) ? $links[0] : null;
            }


            // ========================================
            // ÉTAPE 3: CRÉER LE TP DANS LA BASE
            // ========================================
            if (!Schema::hasTable('tp')) {
                Log::error('❌ Table tp inexistante');
                return redirect()->back()->withInput()->with('error', 'Erreur système.');
            }

            $isReport = $request->input('redirect_to') === 'documents';

            $tpId = DB::table('tp')->insertGetId([
                'user_id' => $user->id,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'formation' => $validated['formation'] ?? null,
                'link' => $link,
                'is_report' => $isReport,
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            // ========================================
            // ÉTAPE 4: TRAITER LES FICHIERS UN PAR UN
            // ========================================
            $filesSuccess = 0;
            $filesErrors = [];

            if (!Schema::hasTable('tp_files')) {
                Log::warning('⚠️ Table tp_files inexistante - fichiers ignorés');
            } elseif ($request->hasFile('files')) {
                $files = $request->file('files');
                $totalFiles = count($files);


                foreach ($files as $index => $file) {
                    $fileNumber = $index + 1;
                    $result = $this->saveOneFile($file, $tpId, $fileNumber);

                    if ($result['success']) {
                        $filesSuccess++;
                    } else {
                        $filesErrors[] = "Fichier $fileNumber: " . $result['error'];
                        Log::error("❌ Fichier $fileNumber/{$totalFiles} ÉCHOUÉ: " . $result['error']);
                    }
                }
            } else {
            }

            // ========================================
            // ÉTAPE 5: NOTIFIER LES ADMINS ET RETOURNER
            // ========================================
            $filesCount = Schema::hasTable('tp_files')
                ? DB::table('tp_files')->where('tp_id', $tpId)->count()
                : 0;


            // Récupérer le TP pour l'email
            $createdTp = DB::table('tp')->where('id', $tpId)->first();

            try {
                // Récupérer tous les administrateurs
                $admins = DB::table('admins')->get();

                if ($admins && $admins->count() > 0) {
                    // Générer l'URL de consultation du TP
                    $viewUrl = route('admin.tp.view', ['id' => $tpId]);

                    // Récupérer les informations de l'étudiant
                    $student = DB::table('students')->where('user_id', $user->id)->first();

                    foreach ($admins as $admin) {
                        Mail::send('emails.admin-new-tp-notification', [
                            'student' => (object)array_merge((array)$student, [
                                'email' => $user->email,
                                'name' => $user->name
                            ]),
                            'tp' => $createdTp,
                            'filesCount' => $filesCount,
                            'viewUrl' => $viewUrl,
                            'module' => ucfirst(str_replace('-', ' ', $currentModule)), // Ajout du module
                            'is_report' => $isReport,
                        ], function ($message) use ($admin, $currentModule, $isReport) {
                            $message->to($admin->email)
                                ->subject(
                                    ($isReport ? '📄 Nouveau Rapport soumis' : '🔔 Nouveau TP soumis')
                                        . ' en ' . ucfirst(str_replace('-', ' ', $currentModule))
                                        . ' - Action requise - EVC'
                                );
                        });
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Erreur lors de l\'envoi de l\'email de notification admin: ' . $e->getMessage());
                // Continue même si l'email échoue
            }

            // Message de succès avec détails
            $successMessage = "Rapport publié avec succès!";
            if ($filesSuccess > 0) {
                $successMessage .= " ($filesSuccess fichier(s) uploadé(s))";
            }
            if (count($filesErrors) > 0) {
                $successMessage .= " Attention: " . count($filesErrors) . " fichier(s) en erreur.";
            }

            Log::info("✅ RAPPORT PUBLIÉ AVEC SUCCÈS", [
                'tp_id' => $tpId,
                'fichiers_ok' => $filesSuccess,
                'fichiers_erreur' => count($filesErrors)
            ]);

            // Redirection post-création (selon contexte)
            if ($request->input('redirect_to') === 'documents') {
                return redirect()->route($currentModule . '.documents.index')
                    ->with('success', $successMessage);
            }

            // Rediriger vers la page des TP avec un message de succès
            return redirect()->to('/evc/compte/' . $currentModule . '/tp/index')
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            Log::error('❌ ERREUR FATALE CRÉATION TP: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'ajout du TP: ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour un projet/TP
     */
    public function updateProject(Request $request, int $id): \Illuminate\Http\JsonResponse|RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour effectuer cette action.');
        }

        // Récupérer le module actuel depuis l'URL
        $currentModule = $request->segment(3); // ex: community-management, design-graphique, etc.

        try {
            if (!Schema::hasTable('tp')) {
                return redirect()->back()->with('error', 'La table des TPs n\'existe pas.');
            }

            // Vérifier que le TP appartient à l'utilisateur
            $tp = DB::table('tp')->where('id', $id)->where('user_id', $user->id)->first();

            if (!$tp) {
                return redirect()->route($currentModule . '.documents.index')
                    ->with('error', 'TP introuvable ou accès non autorisé.');
            }

            // Validation
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:2000',
                'links.*' => 'nullable|url|max:500',
                'files.*' => 'nullable|file|max:51200|mimes:jpg,jpeg,png,gif,webp,svg,pdf,psd,ai,doc,docx,zip,rar'
            ]);

            // Récupérer le premier lien non vide
            $link = null;
            if ($request->has('links')) {
                $links = array_filter($request->input('links'), function ($l) {
                    return !empty($l);
                });
                $link = !empty($links) ? $links[0] : null;
            }

            // Mettre à jour le TP
            DB::table('tp')->where('id', $id)->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'link' => $link,
                'updated_at' => now(),
            ]);

            // Traiter les nouveaux fichiers
            $filesSuccess = 0;
            $filesErrors = [];

            if ($request->hasFile('files') && Schema::hasTable('tp_files')) {
                $files = $request->file('files');
                $totalFiles = count($files);

                Log::info("📂 Traitement de $totalFiles nouveau(x) fichier(s) pour TP #$id");

                foreach ($files as $index => $file) {
                    $fileNumber = $index + 1;
                    $result = $this->saveOneFile($file, $id, $fileNumber);

                    if ($result['success']) {
                        $filesSuccess++;
                        Log::info("✅ Fichier $fileNumber/$totalFiles OK");
                    } else {
                        $filesErrors[] = "Fichier $fileNumber: " . $result['error'];
                        Log::error("❌ Fichier $fileNumber/$totalFiles ÉCHOUÉ: " . $result['error']);
                    }
                }
            }

            // Message de succès
            $successMessage = "Rapport mis à jour avec succès!";
            if ($filesSuccess > 0) {
                $successMessage .= " ($filesSuccess nouveau(x) fichier(s) ajouté(s))";
            }
            if (count($filesErrors) > 0) {
                $successMessage .= " Attention: " . count($filesErrors) . " fichier(s) en erreur.";
            }

            // Si c'est une requête AJAX, retourner du JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage
                ]);
            }

            // Rediriger vers documents/index si c'est un rapport
            $isRapport = stripos($validated['title'], 'rapport') !== false;

            if ($request->input('redirect_to') === 'documents' || $isRapport) {
                return redirect()->route($currentModule . '.documents.index')
                    ->with('success', $successMessage);
            }

            return redirect()->route($currentModule . '.tp.index')
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du TP: ' . $e->getMessage());

            // Si c'est une requête AJAX, retourner du JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du TP.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du TP.');
        }
    }

    /**
     * Mettre à jour un projet/TP avec images
     */
    public function updateProjectWithImages(Request $request, int $id): RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour effectuer cette action.');
        }

        // Récupérer le module actuel depuis l'URL
        $currentModule = $request->segment(3);

        try {
            if (!Schema::hasTable('tp')) {
                return redirect()->back()->with('error', 'La table des TPs n\'existe pas.');
            }

            // Vérifier que le TP appartient à l'utilisateur
            $tp = DB::table('tp')->where('id', $id)->where('user_id', $user->id)->first();

            if (!$tp) {
                return redirect()->route($currentModule . '.documents.index')
                    ->with('error', 'TP introuvable ou accès non autorisé.');
            }

            // Traiter les nouveaux fichiers
            if ($request->hasFile('files') && Schema::hasTable('tp_files')) {
                foreach ($request->file('files') as $file) {
                    if ($file->isValid()) {
                        $originalName = $file->getClientOriginalName();
                        $fileSize = $file->getSize();
                        $mimeType = $file->getMimeType();
                        $extension = $file->getClientOriginalExtension();
                        $fileName = time() . '_' . uniqid() . '.' . $extension;

                        $directory = 'tp/' . $id . '/files';
                        $filePath = $file->storeAs($directory, $fileName, 'public');

                        DB::table('tp_files')->insert([
                            'tp_id' => $id,
                            'original_name' => $originalName,
                            'file_path' => $filePath,
                            'file_size' => $fileSize,
                            'mime_type' => $mimeType,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            return redirect()->route($currentModule . '.documents.index')
                ->with('success', 'Images ajoutées avec succès!');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout des images: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l\'ajout des images.');
        }
    }

    /**
     * Supprimer un projet/TP
     */
    public function deleteProject(Request $request, int $id)
    {
        $user = Auth::user();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous devez être connecté pour effectuer cette action.'
                ], 401);
            }
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour effectuer cette action.');
        }

        // Récupérer le module actuel depuis l'URL
        $currentModule = $request->segment(3);

        try {
            if (!Schema::hasTable('tp')) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La table des TPs n\'existe pas.'
                    ], 500);
                }
                return redirect()->back()->with('error', 'La table des TPs n\'existe pas.');
            }

            // Vérifier que le TP appartient à l'utilisateur
            $tp = DB::table('tp')->where('id', $id)->where('user_id', $user->id)->first();

            if (!$tp) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'TP introuvable ou accès non autorisé.'
                    ], 404);
                }
                return redirect()->route($currentModule . '.documents.index')
                    ->with('error', 'TP introuvable ou accès non autorisé.');
            }

            // Supprimer les fichiers associés
            if (Schema::hasTable('tp_files')) {
                $files = DB::table('tp_files')->where('tp_id', $id)->get();

                foreach ($files as $file) {
                    $path = ltrim((string) ($file->file_path ?? ''), '/');

                    // Ancien stockage legacy (public/uploads/..)
                    if (str_starts_with($path, 'uploads/')) {
                        $fullPath = public_path($path);
                        if (file_exists($fullPath)) {
                            unlink($fullPath);
                        }
                    } else {
                        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                        }
                    }
                }

                DB::table('tp_files')->where('tp_id', $id)->delete();
            }

            // Supprimer le TP
            DB::table('tp')->where('id', $id)->delete();

            // Retourner JSON pour les requêtes AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'TP supprimé avec succès !'
                ]);
            }

            return redirect()->route($currentModule . '.documents.index')
                ->with('success', 'TP supprimé avec succès!');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du TP: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du TP.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de la suppression du TP.');
        }
    }

    /**
     * Supprimer un fichier d'un TP
     */
    public function deleteTPFile(Request $request, int $tpId, int $fileId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être connecté.'
            ], 401);
        }

        try {
            if (!Schema::hasTable('tp') || !Schema::hasTable('tp_files')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tables introuvables.'
                ], 500);
            }

            // Vérifier que le TP appartient à l'utilisateur
            $tp = DB::table('tp')->where('id', $tpId)->where('user_id', $user->id)->first();

            if (!$tp) {
                return response()->json([
                    'success' => false,
                    'message' => 'TP introuvable ou accès non autorisé.'
                ], 404);
            }

            // Récupérer le fichier
            $file = DB::table('tp_files')->where('id', $fileId)->where('tp_id', $tpId)->first();

            if (!$file) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fichier introuvable.'
                ], 404);
            }

            // Supprimer le fichier physique
            $path = ltrim((string) ($file->file_path ?? ''), '/');
            if (str_starts_with($path, 'uploads/')) {
                $fullPath = public_path($path);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                    Log::info("✅ Fichier physique supprimé: $fullPath");
                }
            } else {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                    Log::info("✅ Fichier supprimé du storage public: $path");
                }
            }

            // Supprimer l'entrée en base de données
            DB::table('tp_files')->where('id', $fileId)->delete();

            Log::info("✅ Fichier supprimé de la BDD", [
                'file_id' => $fileId,
                'tp_id' => $tpId,
                'file_name' => $file->original_name
            ]);

            // Récupérer le module actuel depuis l'URL
            $currentModule = $request->segment(3);

            // Si c'est une requête AJAX, retourner du JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Fichier supprimé avec succès!'
                ]);
            }

            // Sinon, rediriger vers la page de modification
            return redirect()->route($currentModule . '.tp.modifier', $tpId)
                ->with('success', 'Fichier supprimé avec succès!');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du fichier: ' . $e->getMessage());

            // Récupérer le module actuel depuis l'URL
            $currentModule = $request->segment(3);

            // Si c'est une requête AJAX, retourner du JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du fichier.'
                ], 500);
            }

            // Sinon, rediriger vers la page de modification avec erreur
            return redirect()->route($currentModule . '.tp.modifier', $tpId)
                ->with('error', 'Erreur lors de la suppression du fichier.');
        }
    }

    /**
     * Formulaire d'ajout simple de TP
     */
    public function ajouterSimpleTP(): View|RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        return view('tp.ajouter-simple');
    }

    /**
     * Formulaire de test simple de TP
     */
    public function testSimpleTP(): View|RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        return view('tp.test-simple');
    }

    /**
     * Enregistrer un TP de test simple
     */
    public function storeTestSimpleTP(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour effectuer cette action.');
        }

        // Récupérer le module actuel depuis l'URL
        $currentModule = $request->segment(3);

        try {
            // Validation simple
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:2000',
            ]);

            if (!Schema::hasTable('tp')) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'La table des TPs n\'existe pas encore.');
            }

            // Insérer le TP de test
            DB::table('tp')->insert([
                'user_id' => $user->id,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route($currentModule . '.documents.index')
                ->with('success', 'TP de test ajouté avec succès!');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout du TP de test: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'ajout du TP de test.');
        }
    }

    /**
     * Page d'index des formations (tous modules)
     */
    public function formationsIndex(): View
    {
        $user = Auth::user();
        if (false && $user && !$this->userHasAnyProject((int) $user->id)) {
            return view('formations.index', [
                'title' => 'Formations',
                'formations' => [],
                'modules_principaux' => [],
                'formations_publiees' => collect(),
                'categoriesStats' => collect(),
                'groupedCategories' => [],
                'module_slug' => 'design-graphique',
            ]);
        }

        // Détecter le module actuel depuis l'URL
        $currentPath = request()->path();
        $moduleSlug = 'design-graphique'; // Par défaut

        if (str_contains($currentPath, 'design-graphique-cm')) {
            $moduleSlug = 'design-graphique-cm';
        } elseif (str_contains($currentPath, 'community-management') || str_contains($currentPath, 'community-manager')) {
            $moduleSlug = 'community-management';
        } elseif (str_contains($currentPath, 'intelligence-artificielle')) {
            $moduleSlug = 'intelligence-artificielle';
        } elseif (str_contains($currentPath, 'gestion-informatique')) {
            $moduleSlug = 'gestion-informatique';
        }

        // Récupérer uniquement les formations du module actuel avec leurs catégories
        $formationsPubliees = \Illuminate\Support\Facades\DB::table('formations')
            ->leftJoin('categories', 'formations.category_id', '=', 'categories.id')
            ->select('formations.*', 'categories.name as category_name', 'categories.slug as category_slug')
            ->where('formations.status', 'active')
            ->where(function ($query) use ($moduleSlug) {
                // Rechercher différentes variantes du nom du module
                $query->whereJsonContains('formations.modules', $moduleSlug)
                    ->orWhereJsonContains('formations.modules', str_replace('-', '_', $moduleSlug))
                    ->orWhereJsonContains('formations.modules', ucwords(str_replace('-', ' ', $moduleSlug)));

                // Si on est sur le module combiné Design Graphique + CM, on inclut les deux
                if ($moduleSlug === 'design-graphique-cm') {
                    $query->orWhereJsonContains('formations.modules', 'design-graphique')
                        ->orWhereJsonContains('formations.modules', 'community-management');
                }

                // Toujours inclure la variante community-manager (legacy)
                $query->orWhereJsonContains('formations.modules', 'community-manager');
            })
            ->orderBy('formations.created_at', 'desc')
            ->get();

        // Données minimales pour compatibilité
        $formations = [];

        // Modules principaux publiés pour l'étudiant (tolérant au schéma)
        $modulesPrincipaux = [];
        try {
            $user = \Illuminate\Support\Facades\Auth::user();
            $formationKeys = ['design_graphique', 'design-graphique', 'infographie'];
            if ($user) {
                $uCols = \Illuminate\Support\Facades\Schema::getColumnListing('users');
                if (in_array('formation_souhaitee', $uCols, true) && !empty($user->formation_souhaitee)) {
                    $formationKeys = [$user->formation_souhaitee];
                } elseif (in_array('choix_formation', $uCols, true) && !empty($user->choix_formation)) {
                    $formationKeys = [$user->choix_formation];
                }
            }

            // Détecter une table plausible de modules
            $modulesTable = null;
            foreach (['modules', 'formation_modules', 'cours_modules'] as $t) {
                if (\Illuminate\Support\Facades\Schema::hasTable($t)) {
                    $modulesTable = $t;
                    break;
                }
            }
            if ($modulesTable) {
                $cols = \Illuminate\Support\Facades\Schema::getColumnListing($modulesTable);
                $q = \Illuminate\Support\Facades\DB::table($modulesTable);
                // Publication
                if (in_array('published', $cols, true)) {
                    $q->where('published', 1);
                } elseif (in_array('is_published', $cols, true)) {
                    $q->where('is_published', 1);
                }
                // Type principal
                if (in_array('type', $cols, true)) {
                    $q->where('type', 'principal');
                } elseif (in_array('is_main', $cols, true)) {
                    $q->where('is_main', 1);
                }
                // Filtre formation si colonne présente
                foreach (['formation', 'formation_slug', 'formation_key', 'programme', 'filiere'] as $fk) {
                    if (in_array($fk, $cols, true)) {
                        $q->whereIn($fk, $formationKeys);
                        break;
                    }
                }
                // Colonnes à sélectionner au mieux
                $select = [];
                $select[] = in_array('id', $cols, true) ? 'id' : \Illuminate\Support\Facades\DB::raw('NULL as id');
                $select[] = in_array('title', $cols, true) ? 'title' : (in_array('name', $cols, true) ? 'name' : \Illuminate\Support\Facades\DB::raw("'' as title"));
                $select[] = in_array('module_number', $cols, true) ? 'module_number' : (in_array('numero', $cols, true) ? 'numero' : \Illuminate\Support\Facades\DB::raw('NULL as module_number'));
                $select[] = in_array('published_at', $cols, true) ? 'published_at' : (in_array('created_at', $cols, true) ? 'created_at' : \Illuminate\Support\Facades\DB::raw('NULL as published_at'));
                $modulesPrincipaux = $q->orderByDesc(in_array('published_at', $cols, true) ? 'published_at' : 'id')->limit(12)->get($select);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('formationsIndex modulesPrincipaux load failed', ['error' => $e->getMessage()]);
        }

        // Récupérer les catégories dynamiquement avec le compte des formations actives pour ce module
        // On utilise les données de $formationsPubliees pour éviter une nouvelle requête et pour respecter le "choix admin" (champ modules)
        $groupedCategories = [];
        $categoriesStats = []; // Pour compatibilité fallback

        // Helper pour formater le titre du module
        $formatModuleTitle = function ($slug) {
            $slug = strtolower($slug);
            if (in_array($slug, ['cm', 'community-manager', 'community-management'])) return 'Community Management';
            if (in_array($slug, ['design', 'design-graphique', 'infographie'])) return 'Design Graphique';
            if (in_array($slug, ['dev', 'informatique', 'gestion-informatique'])) return 'Gestion Informatique';
            if (in_array($slug, ['ia', 'intelligence-artificielle'])) return 'Intelligence Artificielle';
            return ucwords(str_replace(['-', '_'], ' ', $slug));
        };

        // On parcourt les formations déjà chargées
        foreach ($formationsPubliees as $formation) {
            if (empty($formation->category_id)) continue;

            $modules = json_decode($formation->modules ?? '[]', true);
            if (!is_array($modules)) $modules = [];

            // Si le tableau modules est vide, on ignore
            if (empty($modules)) continue;

            foreach ($modules as $modSlug) {
                $title = $formatModuleTitle($modSlug);

                if (!isset($groupedCategories[$title])) {
                    $groupedCategories[$title] = [];
                }

                $catSlug = $formation->category_slug ?? \Illuminate\Support\Str::slug($formation->category_name);

                if (!isset($groupedCategories[$title][$catSlug])) {
                    $groupedCategories[$title][$catSlug] = (object)[
                        'id' => $formation->category_id,
                        'name' => $formation->category_name,
                        'slug' => $catSlug,
                        'total' => 0
                    ];
                }

                $groupedCategories[$title][$catSlug]->total++;
            }
        }

        // Aplatir les tableaux de catégories et remplir categoriesStats
        foreach ($groupedCategories as $title => $cats) {
            // Trier les catégories par nom pour faire propre
            uasort($cats, function ($a, $b) {
                return strcmp($a->name, $b->name);
            });

            $groupedCategories[$title] = array_values($cats);

            foreach ($cats as $cat) {
                $categoriesStats[] = $cat;
            }
        }

        // Retirer les doublons de categoriesStats (au cas où une catégorie est dans plusieurs groupes)
        $categoriesStats = collect($categoriesStats)->unique('slug')->values();

        // Déterminer le titre selon le module
        $moduleTitle = match ($moduleSlug) {
            'design-graphique-cm' => 'Design Graphique & Community Management',
            'community-management' => 'Community Management',
            'intelligence-artificielle' => 'Intelligence Artificielle',
            'gestion-informatique' => 'Gestion Informatique',
            default => 'Design Graphique',
        };

        return view('formations.index', [
            'title' => 'Formations - ' . $moduleTitle,
            'formations' => $formations,
            'modules_principaux' => $modulesPrincipaux,
            'formations_publiees' => $formationsPubliees,
            'categoriesStats' => $categoriesStats,
            'groupedCategories' => $groupedCategories,
            'module_slug' => $moduleSlug,
        ]);
    }

    /**
     * Liste des formations par catégorie
     */
    public function formationsCategory(string $category): View
    {
        $user = Auth::user();
        if (false && $user && !$this->userHasAnyProject((int) $user->id)) {
            return view('formations.category', [
                'category' => $category,
                'categoryModel' => null,
                'formations' => collect(),
                'stats' => [
                    'total' => 0,
                    'duration' => 0,
                    'completion_rate' => 0,
                    'new_this_week' => 0,
                ],
            ]);
        }

        // Essayer de charger la catégorie depuis la base de données par son slug
        $categoryModel = \Illuminate\Support\Facades\DB::table('categories')
            ->where('slug', $category)
            ->first();

        // Si pas trouvé par slug, essayer par nom (case-insensitive)
        if (!$categoryModel) {
            $categoryModel = \Illuminate\Support\Facades\DB::table('categories')
                ->whereRaw('LOWER(name) = ?', [strtolower($category)])
                ->first();
        }

        // Si toujours pas trouvé, essayer par slug contenant le terme
        if (!$categoryModel) {
            $categoryModel = \Illuminate\Support\Facades\DB::table('categories')
                ->where('slug', 'like', '%' . $category . '%')
                ->first();
        }

        // Si la catégorie n'existe pas, retourner une collection vide
        if (!$categoryModel) {
            \Log::warning('Catégorie non trouvée', ['category_slug' => $category]);
            $formations = collect();
            $formationsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
                [],
                0,
                9,
                1
            );
        } else {
            // Charger toutes les formations de cette catégorie pour les stats
            $allFormations = \Illuminate\Support\Facades\DB::table('formations')
                ->where('category_id', $categoryModel->id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Charger les formations avec pagination (9 par page)
            $formationsPaginated = \Illuminate\Support\Facades\DB::table('formations')
                ->where('category_id', $categoryModel->id)
                ->orderBy('created_at', 'desc')
                ->paginate(9);

            $formations = $allFormations; // Pour les stats

            \Log::info('Formations trouvées', [
                'category_id' => $categoryModel->id,
                'category_name' => $categoryModel->name ?? 'N/A',
                'formations_count' => $formations->count()
            ]);
        }

        // Calculer les statistiques de la catégorie
        $stats = [
            'total' => $formations->count(),
            'duration' => $formations->sum('duration_weeks') ?? 0,
            'completion_rate' => $formations->avg('completion_rate') ?? 0,
            'new_this_week' => $formations->where('created_at', '>=', now()->subWeek())->count(),
        ];

        return view('formations.category', [
            'category' => $category,
            'categoryModel' => $categoryModel,
            'formations' => $formationsPaginated,
            'stats' => $stats,
        ]);
    }

    /**
     * Détail d'une formation
     */
    public function formationsShow(int $id): View
    {
        $user = Auth::user();
        if (false && $user && !$this->userHasAnyProject((int) $user->id)) {
            abort(403);
        }

        // Chargement tolérant au schéma depuis une table plausible
        $formationsTable = null;
        foreach (['formations', 'courses', 'programmes', 'formation_courses'] as $t) {
            if (\Illuminate\Support\Facades\Schema::hasTable($t)) {
                $formationsTable = $t;
                break;
            }
        }

        $formation = null;
        if ($formationsTable) {
            $cols = \Illuminate\Support\Facades\Schema::getColumnListing($formationsTable);
            $q = \Illuminate\Support\Facades\DB::table($formationsTable)->where('id', $id);
            // Publié uniquement si colonne présente
            if (in_array('published', $cols, true)) {
                $q->where('published', 1);
            } elseif (in_array('is_published', $cols, true)) {
                $q->where('is_published', 1);
            }

            $select = [];
            $select[] = in_array('id', $cols, true) ? 'id' : \Illuminate\Support\Facades\DB::raw('NULL as id');
            $select[] = in_array('title', $cols, true) ? 'title' : (in_array('name', $cols, true) ? 'name' : \Illuminate\Support\Facades\DB::raw("'' as title"));
            $select[] = in_array('category', $cols, true) ? 'category' : (in_array('categorie', $cols, true) ? 'categorie' : \Illuminate\Support\Facades\DB::raw("'' as category"));
            $select[] = in_array('level', $cols, true) ? 'level' : (in_array('niveau', $cols, true) ? 'niveau' : \Illuminate\Support\Facades\DB::raw("'' as level"));
            $select[] = in_array('duration', $cols, true) ? 'duration' : (in_array('duree', $cols, true) ? 'duree' : \Illuminate\Support\Facades\DB::raw("'' as duration"));
            $select[] = in_array('description', $cols, true) ? 'description' : (in_array('content', $cols, true) ? 'content' : \Illuminate\Support\Facades\DB::raw("'' as description"));
            $select[] = in_array('video_url', $cols, true) ? 'video_url' : (in_array('video', $cols, true) ? 'video' : \Illuminate\Support\Facades\DB::raw("'' as video_url"));
            $select[] = in_array('vimeo_code', $cols, true) ? 'vimeo_code' : \Illuminate\Support\Facades\DB::raw("'' as vimeo_code");
            // Ajout du champ image pour le poster/cover
            $select[] = in_array('image', $cols, true) ? 'image' : (in_array('cover', $cols, true) ? 'cover' : (in_array('thumbnail', $cols, true) ? 'thumbnail' : \Illuminate\Support\Facades\DB::raw("'' as image")));
            $select[] = in_array('created_at', $cols, true) ? 'created_at' : \Illuminate\Support\Facades\DB::raw('NULL as created_at');
            $formation = $q->first($select);

            // Related formations for sidebar
            $rq = \Illuminate\Support\Facades\DB::table($formationsTable);
            // Only published if column exists
            if (in_array('published', $cols, true)) {
                $rq->where('published', 1);
            } elseif (in_array('is_published', $cols, true)) {
                $rq->where('is_published', 1);
            }
            // Exclude current id
            if (in_array('id', $cols, true)) {
                $rq->where('id', '<>', $id);
            }
            // Same category if possible
            $catCol = in_array('category', $cols, true) ? 'category' : (in_array('categorie', $cols, true) ? 'categorie' : null);
            if ($catCol && $formation && !empty($formation->category)) {
                $rq->where($catCol, $formation->category);
            }
            $rselect = [];
            $rselect[] = in_array('id', $cols, true) ? 'id' : \Illuminate\Support\Facades\DB::raw('NULL as id');
            $rselect[] = in_array('title', $cols, true) ? 'title' : (in_array('name', $cols, true) ? 'name' : \Illuminate\Support\Facades\DB::raw("'' as title"));
            $rselect[] = $catCol ? $catCol . ' as category' : \Illuminate\Support\Facades\DB::raw("'' as category");
            $rselect[] = in_array('created_at', $cols, true) ? 'created_at' : \Illuminate\Support\Facades\DB::raw('NULL as created_at');
            $related = $rq->orderByDesc(in_array('created_at', $cols, true) ? 'created_at' : 'id')->limit(6)->get($rselect);
        } else {
            $related = collect();
        }

        // Fallback minimal si rien en base
        if (!$formation) {
            $formation = (object) [
                'id' => $id,
                'title' => 'Formation #' . $id,
                'category' => '',
                'level' => '',
                'duration' => '',
                'description' => 'Description à venir',
                'video_url' => '',
                'created_at' => null,
            ];
        }

        // Récupérer les fichiers PDF associés à cette formation
        $files = \App\Models\FormationFile::where('formation_id', $id)->get();

        // Récupérer les chapitres de la formation
        $chapters = \App\Models\FormationChapter::where('formation_id', $id)->orderBy('order')->get();

        return view('formations.show', [
            'formation' => $formation,
            'related_formations' => $related ?? collect(),
            'files' => $files,
            'chapters' => $chapters,
        ]);
    }

    /**
     * Télécharger la vidéo d'une formation
     */
    public function formationsDownload(int $id)
    {
        $user = Auth::user();
        if (false && $user && !$this->userHasAnyProject((int) $user->id)) {
            abort(403);
        }

        // Récupérer la formation
        $formation = \Illuminate\Support\Facades\DB::table('formations')->where('id', $id)->first();

        if (!$formation) {
            return redirect()->back()->with('error', 'Formation non trouvée');
        }

        $videoUrl = null;

        // Essayer d'abord video_url
        if (!empty($formation->video_url)) {
            $videoUrl = $formation->video_url;
        }
        // Sinon, extraire l'URL depuis vimeo_code
        elseif (!empty($formation->vimeo_code)) {
            $code = $formation->vimeo_code;

            // Si c'est déjà une URL (YouTube, Vimeo, etc.)
            if (filter_var($code, FILTER_VALIDATE_URL)) {
                $videoUrl = $code;
            }
            // Si vimeo_code contient un iframe, extraire l'URL src
            elseif (str_contains($code, '<iframe')) {
                preg_match('/src="([^"]+)"/', $code, $matches);
                if (isset($matches[1])) {
                    $videoUrl = $matches[1];
                }
            }
            // Si c'est juste un code numérique (ID Vimeo), construire l'URL
            elseif (is_numeric($code)) {
                $videoUrl = 'https://player.vimeo.com/video/' . $code;
            }
            // Fallback: on suppose que c'est une URL
            else {
                $videoUrl = $code;
            }
        }

        if ($videoUrl) {
            // Rediriger vers l'URL de la vidéo dans un nouvel onglet
            return redirect()->away($videoUrl);
        }

        return redirect()->back()->with('error', 'Vidéo non disponible pour le téléchargement');
    }

    /**
     * Télécharger tous les fichiers d'une formation (vidéo + PDFs)
     */
    public function formationsDownloadAll(int $id)
    {
        $user = Auth::user();
        if (false && $user && !$this->userHasAnyProject((int) $user->id)) {
            abort(403);
        }

        try {
            // Récupérer la formation
            $formation = \Illuminate\Support\Facades\DB::table('formations')->where('id', $id)->first();

            if (!$formation) {
                return redirect()->back()->with('error', 'Formation non trouvée');
            }

            // Récupérer les fichiers PDF
            $files = \App\Models\FormationFile::where('formation_id', $id)->get();

            if ($files->isEmpty()) {
                return redirect()->back()->with('error', 'Aucun fichier à télécharger pour cette formation');
            }

            // Créer une archive ZIP
            $zip = new \ZipArchive();
            $zipFileName = 'formation_' . $id . '_' . time() . '.zip';
            $zipPath = storage_path('app/temp/' . $zipFileName);

            // Créer le répertoire temp s'il n'existe pas
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
                $addedFiles = 0;

                // Ajouter tous les fichiers PDF
                foreach ($files as $file) {
                    // Le fichier est dans storage/app/public/uploads/formations/pdf/
                    $filePath = storage_path('app/public/uploads/formations/pdf/' . $file->stored_name);

                    if (file_exists($filePath)) {
                        $zip->addFile($filePath, $file->original_name);
                        $addedFiles++;
                    } else {
                        \Illuminate\Support\Facades\Log::warning("Fichier introuvable: {$filePath}");
                    }
                }

                if ($addedFiles === 0) {
                    $zip->close();
                    @unlink($zipPath);
                    return redirect()->back()->with('error', 'Aucun fichier physique trouvé sur le serveur');
                }

                // Ajouter un fichier README avec les informations de la formation
                $readme = "Formation: {$formation->name}\n";
                $readme .= "Date de téléchargement: " . now()->format('d/m/Y H:i') . "\n\n";
                $readme .= "Fichiers inclus:\n";
                foreach ($files as $file) {
                    $readme .= "- {$file->original_name}\n";
                }

                if (!empty($formation->video_url) || !empty($formation->vimeo_code)) {
                    $readme .= "\nVidéo disponible en ligne sur la plateforme.\n";
                }

                $zip->addFromString('README.txt', $readme);
                $zip->close();

                // Télécharger le fichier ZIP
                return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
            }

            return redirect()->back()->with('error', 'Erreur lors de la création de l\'archive');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur téléchargement formation: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors du téléchargement');
        }
    }

    /**
     * Liste des formations par catégorie d'édition du profil étudiant.
     */
    /** @return View */
    public function editProfile(Request $request, StudentProfileService $service, ?int $id = null): View
    {
        /** @var User|null $authUser */
        $authUser = Auth::user();
        if (!$authUser) {
            return redirect()->route('login');
        }

        $ownStudent = DB::table('students')->where('user_id', $authUser->id)->first();
        if ($id !== null && $ownStudent && (int) $id !== (int) $ownStudent->id) {
            $path = $request->path();
            $prefix = 'design-graphique';
            if (preg_match('#^evc/compte/([^/]+)#', $path, $matches)) {
                $prefix = $matches[1];
            }
            return redirect()->route($prefix . '.profil.editer')
                ->with('error', 'Accès refusé : vous ne pouvez modifier que votre propre profil.');
        }

        $student = $service->loadStudent($authUser, $id);
        // Autorisation désactivée temporairement pour éviter les 403 pendant l'intégration de la policy

        $preReg = $service->loadPreRegistration($student, $authUser);
        // Construire des valeurs par défaut (pré-remplissage)
        $sf = optional($student);
        $pr = optional($preReg);
        $defaults = [
            'first_name'       => $sf->first_name ?: ($authUser->name ?? ($pr->first_name ?? '')),
            'last_name'        => $sf->last_name ?: ($pr->last_name ?? ''),
            'email'            => $sf->email ?: (($authUser->email ?? '') ?: ($pr->email ?? '')),
            'phone'            => $sf->phone ?: ($pr->phone ?? ''),
            'whatsapp'         => $sf->whatsapp ?: ($pr->whatsapp ?? ''),
            'date_of_birth'    => $sf->date_of_birth ? $sf->date_of_birth->format('Y-m-d') : ($pr->date_of_birth ?? ''),
            'gender'           => $sf->gender ?: ($pr->gender ?? ''),
            'level'            => $sf->level ?: ($pr->level ?? ''),
            'specialization'   => $sf->specialization ?: ($pr->specialization ?? ''),
            'quartier'         => $sf->quartier ?: ($pr->quartier ?? ''),
            'city'             => $sf->city ?: ($pr->city ?? ''),
            'country'          => $sf->country ?: ($pr->country ?? ''),
            'years_experience' => ($sf->years_experience !== null ? $sf->years_experience : ($pr->years_experience ?? '')),
            'industry_sector'  => $sf->industry_sector ?: ($pr->industry_sector ?? ''),
        ];

        return view('dashboard.profil.editer', [
            'student' => $student,
            'user' => $authUser,
            'preReg' => $preReg,
            'defaults' => $defaults,
        ]);
    }

    /**
     * Mise à jour du profil étudiant (nom, email, photo, ...)
     */
    /** @return RedirectResponse */
    public function updateProfile(StudentProfileRequest $request, StudentProfileService $service, ?int $id = null): RedirectResponse
    {
        /** @var User|null $authUser */
        $authUser = Auth::user();
        if (!$authUser) {
            return redirect()->route('login');
        }

        $ownStudent = DB::table('students')->where('user_id', $authUser->id)->first();
        if ($id !== null && $ownStudent && (int) $id !== (int) $ownStudent->id) {
            $path = $request->path();
            $prefix = 'design-graphique';
            if (preg_match('#^evc/compte/([^/]+)#', $path, $matches)) {
                $prefix = $matches[1];
            }
            return redirect()->route($prefix . '.profil.editer')
                ->with('error', 'Accès refusé : vous ne pouvez modifier que votre propre profil.');
        }

        $student = $service->loadStudent($authUser, $id);
        // Autorisation désactivée temporairement pour éviter les 403 pendant l'intégration de la policy
        $service->save($student, $request->validated(), $request->file('profile_photo'));
        $redirectParams = $id ? ['id' => $student->id] : [];
        $path = request()->path();
        $prefix = 'design-graphique';
        if (preg_match('#^evc/compte/([^/]+)#', $path, $matches)) {
            $prefix = $matches[1];
        }
        return redirect()->route($prefix . '.profil.editer', $redirectParams)
            ->with('success', 'Profil mis à jour avec succès!');
    }

    /**
     * Afficher la page des projets de design graphique
     */
    public function projets(): View
    {
        $user = Auth::user();

        // Initialiser toutes les variables avec des valeurs par défaut
        $projets = collect([]);
        $soloProjects = [];
        $groupProjects = [];
        $stats = [
            'solo_projects' => 0,
            'group_projects' => 0,
            'total_projects' => 0
        ];
        $statistiques = [
            'total' => 0,
            'en_cours' => 0,
            'termines' => 0,
            'en_attente' => 0
        ];
        $soloPagination = [
            'current_page' => 1,
            'per_page' => 10,
            'total_items' => 0,
            'total_pages' => 1,
            'has_prev' => false,
            'has_next' => false
        ];
        $groupPagination = [
            'current_page' => 1,
            'per_page' => 10,
            'total_items' => 0,
            'total_pages' => 1,
            'has_prev' => false,
            'has_next' => false
        ];

        if (!$user) {
            return view('projets.index', compact('projets', 'soloProjects', 'groupProjects', 'stats', 'statistiques', 'soloPagination', 'groupPagination'));
        }

        try {
            // Vérifier si la table design_projects existe
            if (!Schema::hasTable('design_projects')) {
                Log::warning('Table design_projects n\'existe pas');
                return view('projets.index', compact('projets', 'soloProjects', 'groupProjects', 'stats', 'statistiques', 'soloPagination', 'groupPagination'));
            }

            // Récupérer tous les projets de l'utilisateur
            $allProjects = DB::table('design_projects')
                ->where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->get();

            if ($allProjects && $allProjects->count() > 0) {
                // Convertir en tableau pour faciliter la manipulation
                $projets = $allProjects->map(function ($project) {
                    $projectArray = (array) $project;
                    // Ajouter files_count si nécessaire
                    if (!isset($projectArray['files_count'])) {
                        $projectArray['files_count'] = 0;
                    }
                    // Parser software_used si c'est une chaîne JSON
                    if (isset($projectArray['software_used']) && is_string($projectArray['software_used'])) {
                        $projectArray['software_used_array'] = json_decode($projectArray['software_used'], true) ?: [];
                    } else {
                        $projectArray['software_used_array'] = [];
                    }
                    return $projectArray;
                });

                // Séparer les projets solo et groupe
                $soloProjects = $projets->where('category', 'solo')->values()->toArray();
                $groupProjects = $projets->where('category', 'groupe')->values()->toArray();

                // Calculer les statistiques
                $stats['solo_projects'] = count($soloProjects);
                $stats['group_projects'] = count($groupProjects);
                $stats['total_projects'] = $projets->count();

                $statistiques['total'] = $projets->count();
                $statistiques['en_cours'] = $projets->where('status', 'in_progress')->count();
                $statistiques['termines'] = $projets->where('status', 'completed')->count();
                $statistiques['en_attente'] = $projets->where('status', 'pending')->count();

                // Calculer la pagination pour solo
                $soloPagination['total_items'] = count($soloProjects);
                $soloPagination['total_pages'] = max(1, ceil($soloPagination['total_items'] / $soloPagination['per_page']));
                $soloPagination['has_prev'] = $soloPagination['current_page'] > 1;
                $soloPagination['has_next'] = $soloPagination['current_page'] < $soloPagination['total_pages'];

                // Calculer la pagination pour groupe
                $groupPagination['total_items'] = count($groupProjects);
                $groupPagination['total_pages'] = max(1, ceil($groupPagination['total_items'] / $groupPagination['per_page']));
                $groupPagination['has_prev'] = $groupPagination['current_page'] > 1;
                $groupPagination['has_next'] = $groupPagination['current_page'] < $groupPagination['total_pages'];
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des projets', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id
            ]);
        }

        return view('projets.index', compact('projets', 'soloProjects', 'groupProjects', 'stats', 'statistiques', 'soloPagination', 'groupPagination'));
    }

    /**
     * Afficher la page des événements
     */
    public function eventsIndex(): View
    {
        $user = Auth::user();

        // Récupérer tous les événements publiés en fonction de la visibilité
        $allEvents = \App\Models\Evenement::where('status', 'published')
            ->where(function ($query) use ($user) {
                // Événements publics (visibles par tous)
                $query->where('visibility', 'public')
                    // OU événements pour toutes les formations
                    ->orWhere('visibility', 'all')
                    // OU événements pour la formation spécifique de l'étudiant
                    ->orWhere(function ($q) use ($user) {
                        $q->where('visibility', 'specific')
                            ->whereJsonContains('formations', $user->formation_id);
                    });
            })
            ->orderBy('event_date', 'desc')
            ->get();

        // Calculer les statistiques
        $now = \Carbon\Carbon::now();

        $stats = [
            'total' => $allEvents->count(),
            'a_venir' => $allEvents->filter(function ($event) use ($now) {
                return \Carbon\Carbon::parse($event->event_date)->isFuture();
            })->count(),
            'passes' => $allEvents->filter(function ($event) use ($now) {
                return \Carbon\Carbon::parse($event->event_date)->isPast();
            })->count(),
            'en_ligne' => $allEvents->where('event_type', 'online')->count(),
            'presentiel' => $allEvents->where('event_type', 'physical')->count(),
            'hybride' => $allEvents->where('event_type', 'hybrid')->count(),
            'a_la_une' => $allEvents->where('is_featured', true)->count(),
        ];

        // Séparer les événements à venir et passés
        $eventsAvenir = $allEvents->filter(function ($event) use ($now) {
            return \Carbon\Carbon::parse($event->event_date)->isFuture();
        });

        $eventsPasses = $allEvents->filter(function ($event) use ($now) {
            return \Carbon\Carbon::parse($event->event_date)->isPast();
        }); // Afficher tous les événements passés (historique complet)

        // Récupérer les événements à la une (featured)
        $eventsFeatured = $eventsAvenir->filter(function ($event) {
            return $event->is_featured == true;
        })->take(3); // Limiter à 3 événements à la une

        return view('events.index', [
            'user' => $user,
            'events' => $eventsAvenir,
            'eventsPasses' => $eventsPasses,
            'eventsFeatured' => $eventsFeatured,
            'stats' => $stats
        ]);
    }

    /**
     * Afficher les détails d'un événement
     */
    public function eventsShow($id): View
    {
        $user = Auth::user();

        // Récupérer l'événement
        $event = \App\Models\Evenement::findOrFail($id);

        // Vérifier que l'événement est publié
        if ($event->status !== 'published') {
            abort(404);
        }

        // Vérifier la visibilité
        $hasAccess = false;

        if ($event->visibility === 'public') {
            $hasAccess = true;
        } elseif ($event->visibility === 'all') {
            $hasAccess = true;
        } elseif ($event->visibility === 'specific') {
            $formations = json_decode($event->formations, true) ?? [];
            $hasAccess = in_array($user->formation_id, $formations);
        }

        if (!$hasAccess) {
            abort(403, 'Vous n\'avez pas accès à cet événement.');
        }

        // Incrémenter le compteur de vues
        $event->increment('views_count');

        return view('events.show', [
            'user' => $user,
            'event' => $event
        ]);
    }

    /**
     * Afficher la page des actualités EVC
     */
    public function actualitesIndex(): View
    {
        $user = Auth::user();
        $formationId = $user->formation_id;

        // Récupérer les actualités publiées et visibles pour l'étudiant
        $actualites = \App\Models\Actualite::with('author')
            ->where('status', 'published')
            ->where(function ($query) use ($formationId) {
                $query->where('visibility', 'public')
                    ->orWhere('visibility', 'all')
                    ->orWhere(function ($q) use ($formationId) {
                        $q->where('visibility', 'specific')
                            ->whereJsonContains('formations', $formationId);
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistiques
        $stats = [
            'total' => $actualites->count(),
            'categories' => $actualites->groupBy('category')->map->count(),
            'vues_total' => $actualites->sum('views_count'),
        ];

        // Actualité à la une
        $featured = $actualites->where('is_featured', true)->first();

        return view('actualites.index', [
            'user' => $user,
            'actualites' => $actualites,
            'stats' => $stats,
            'featured' => $featured,
        ]);
    }

    /**
     * Afficher les détails d'une actualité
     */
    public function actualitesShow($id): View
    {
        $user = Auth::user();
        $formationId = $user->formation_id;

        // Récupérer l'actualité avec contrôle d'accès
        $actualite = \App\Models\Actualite::with('author')
            ->where('id', $id)
            ->where('status', 'published')
            ->where(function ($query) use ($formationId) {
                $query->where('visibility', 'public')
                    ->orWhere('visibility', 'all')
                    ->orWhere(function ($q) use ($formationId) {
                        $q->where('visibility', 'specific')
                            ->whereJsonContains('formations', $formationId);
                    });
            })
            ->firstOrFail();

        // Incrémenter le compteur de vues
        $actualite->increment('views_count');

        // Actualités similaires (même catégorie, exclure l'actuelle)
        $similaires = \App\Models\Actualite::where('category', $actualite->category)
            ->where('id', '!=', $actualite->id)
            ->where('status', 'published')
            ->where(function ($query) use ($formationId) {
                $query->where('visibility', 'public')
                    ->orWhere('visibility', 'all')
                    ->orWhere(function ($q) use ($formationId) {
                        $q->where('visibility', 'specific')
                            ->whereJsonContains('formations', $formationId);
                    });
            })
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('actualites.show', [
            'user' => $user,
            'actualite' => $actualite,
            'similaires' => $similaires,
        ]);
    }

    /**
     * Afficher les rapports/travaux personnels de l'étudiant
     */
    public function documentsIndex(): View
    {
        $user = Auth::user();
        $student = DB::table('students')->where('user_id', $user->id)->first();

        // Récupérer le module actuel depuis l'URL (ex: design-graphique)
        $currentModule = request()->segment(3);

        $documents = collect([]);

        // 1. Récupérer les rapports depuis la table tp (legacy)
        $tps = \App\Models\TP::where('user_id', $user->id)
            ->where(function ($query) {
                if (\Illuminate\Support\Facades\Schema::hasColumn('tp', 'is_report')) {
                    $query->where('is_report', 1);
                }

                $query->orWhere('title', 'LIKE', '%rapport%')
                    ->orWhere('title', 'LIKE', '%Rapport%')
                    ->orWhere('title', 'LIKE', '%RAPPORT%');
            })
            ->with(['files'])
            ->orderBy('created_at', 'desc')
            ->get();

        \Log::info('Documents - TP trouvés', ['count' => $tps->count(), 'user_id' => $user->id]);

        $documentsFromTp = $tps->map(function ($tp) {
            // Récupérer le premier fichier PDF s'il existe
            $pdfFile = $tp->files->first(function ($file) {
                return strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION)) === 'pdf';
            });

            // Déterminer la catégorie selon le statut
            $categorie = match ($tp->status) {
                'validated' => 'Validés',
                'pending' => 'En attente',
                'rejected' => 'Rejetés',
                default => 'Autres'
            };

            // Afficher tous les rapports, même sans PDF
            // Construire le bon chemin pour le fichier
            $filePath = '#';
            if ($pdfFile) {
                $path = ltrim((string) $pdfFile->file_path, '/');
                if (str_starts_with($path, 'storage/app/public/')) {
                    $path = substr($path, strlen('storage/app/public/'));
                }
                $filePath = \App\Models\MediaUrl::fromPath($path);
            }

            return [
                'id' => $tp->id,
                'titre' => $tp->title,
                'description' => $tp->description ?? 'Rapport de travail pratique',
                'categorie' => $categorie,
                'type' => $pdfFile ? 'PDF' : 'Aucun fichier',
                'taille' => $pdfFile ? number_format($pdfFile->file_size / 1024, 2) . ' KB' : 'N/A',
                'format' => $pdfFile ? '.pdf' : '',
                'telechargements' => 0,
                'date_ajout' => $tp->created_at->format('Y-m-d'),
                'image' => null,
                'lien' => $filePath,
                'status' => $tp->status,
                'files_count' => $tp->files->count(),
                'source' => 'tp'
            ];
        });

        $documents = $documents->merge($documentsFromTp);

        // 2. Récupérer les rapports depuis tp_assignments
        if ($student) {
            $tpAssignments = DB::table('tp_assignments')
                ->where('student_id', $student->id)
                ->where(function ($query) {
                    $query->where('title', 'LIKE', '%rapport%')
                        ->orWhere('title', 'LIKE', '%Rapport%')
                        ->orWhere('title', 'LIKE', '%RAPPORT%');
                })
                ->orderBy('created_at', 'desc')
                ->get();

            \Log::info('Documents - TP Assignments trouvés', ['count' => $tpAssignments->count(), 'student_id' => $student->id]);

            $documentsFromAssignments = $tpAssignments->map(function ($assignment) {
                // Récupérer les fichiers de soumission
                $files = DB::table('tp_submission_files')
                    ->where('tp_assignment_id', $assignment->id)
                    ->get();

                $pdfFile = $files->first(function ($file) {
                    return strtolower(pathinfo($file->file_name ?? '', PATHINFO_EXTENSION)) === 'pdf';
                });

                // Si pas de PDF soumis, chercher dans les fichiers d'assignation
                if (!$pdfFile) {
                    $assignmentFiles = DB::table('tp_assignment_files')
                        ->where('tp_assignment_id', $assignment->id)
                        ->get();

                    $pdfFile = $assignmentFiles->first(function ($file) {
                        return strtolower(pathinfo($file->file_name ?? '', PATHINFO_EXTENSION)) === 'pdf';
                    });
                }

                // Mapper les statuts
                $categorie = match ($assignment->status) {
                    'validated' => 'Validés',
                    'submitted', 'pending' => 'En attente',
                    'rejected' => 'Rejetés',
                    default => 'Autres'
                };

                // Construire le bon chemin pour le fichier
                $filePath = '#';
                if ($pdfFile && isset($pdfFile->file_path)) {
                    $path = ltrim((string) $pdfFile->file_path, '/');
                    if (str_starts_with($path, 'storage/app/public/')) {
                        $path = substr($path, strlen('storage/app/public/'));
                    }
                    $filePath = \App\Models\MediaUrl::fromPath($path);
                }

                // Afficher tous les rapports, même sans PDF
                return [
                    'id' => $assignment->id,
                    'titre' => $assignment->title,
                    'description' => $assignment->description ?? 'Rapport assigné',
                    'categorie' => $categorie,
                    'type' => $pdfFile ? 'PDF' : 'Aucun fichier',
                    'taille' => ($pdfFile && isset($pdfFile->file_size)) ? number_format($pdfFile->file_size / 1024, 2) . ' KB' : 'N/A',
                    'format' => $pdfFile ? '.pdf' : '',
                    'telechargements' => 0,
                    'date_ajout' => \Carbon\Carbon::parse($assignment->created_at)->format('Y-m-d'),
                    'image' => null,
                    'lien' => $filePath,
                    'status' => $assignment->status,
                    'files_count' => $files->count(),
                    'source' => 'tp_assignments'
                ];
            })->filter();

            $documents = $documents->merge($documentsFromAssignments);
        }

        // Convertir en array et trier par date
        $documents = $documents->sortByDesc('date_ajout')->values()->toArray();

        \Log::info('Documents - Total fusionné', ['count' => count($documents)]);

        // Créer des catégories basées sur le statut
        $categories = collect([
            (object)['name' => 'Validés'],
            (object)['name' => 'En attente'],
            (object)['name' => 'Rejetés'],
        ]);

        // Organiser les documents par catégorie (statut)
        $documentsParCategorie = [];

        foreach ($categories as $category) {
            $documentsParCategorie[$category->name] = array_filter($documents, function ($doc) use ($category) {
                return $doc['categorie'] === $category->name;
            });
        }

        // Statistiques dynamiques par catégorie
        $stats = [
            'total' => count($documents),
            'validés' => count($documentsParCategorie['Validés']),
            'en_attente' => count($documentsParCategorie['En attente']),
            'rejetés' => count($documentsParCategorie['Rejetés']),
        ];

        return view('documents.index', [
            'user' => $user,
            'documents' => $documents,
            'documentsParCategorie' => $documentsParCategorie,
            'categories' => $categories,
            'stats' => $stats,
            'currentModule' => $currentModule
        ]);
    }

    /**
     * Télécharger un document et incrémenter le compteur
     */
    public function downloadDocument($id)
    {
        $document = \App\Models\Library::findOrFail($id);

        \Log::info('📥 Téléchargement de document', [
            'document_id' => $id,
            'title' => $document->title,
            'downloads_count_before' => $document->downloads_count
        ]);

        // Incrémenter le compteur de téléchargements
        $document->increment('downloads_count');

        \Log::info('✅ Compteur incrémenté', [
            'downloads_count_after' => $document->fresh()->downloads_count
        ]);

        // Si un lien externe existe, rediriger vers ce lien
        if ($document->external_link) {
            return redirect($document->external_link);
        }

        // Priorité: télécharger le PDF si présent
        if ($document->pdf_path) {
            $pdfRelativePath = $this->normalizePublicDiskPath($document->pdf_path);

            if ($pdfRelativePath !== '' && Storage::disk('public')->exists($pdfRelativePath)) {
                $filePath = storage_path('app/public/' . $pdfRelativePath);
                $safeTitle = trim((string) $document->title);
                $extension = strtolower((string) pathinfo($pdfRelativePath, PATHINFO_EXTENSION));
                $mime = (string) (Storage::disk('public')->mimeType($pdfRelativePath) ?? 'application/octet-stream');
                $fileName = ($safeTitle !== '' ? $safeTitle : 'document') . ($extension !== '' ? ('.' . $extension) : '');

                if ($extension === 'pdf' && request()->boolean('inline')) {
                    return response()->file($filePath, [
                        'Content-Type' => $mime,
                        'Content-Disposition' => 'inline; filename="' . addslashes($fileName) . '"',
                    ]);
                }

                return response()->download($filePath, $fileName, [
                    'Content-Type' => $mime,
                ]);
            }
        }

        // Sinon, fallback sur le fichier principal (peut être une image pour l'ancien système)
        if ($document->path) {
            $relativePath = $this->normalizePublicDiskPath($document->path);
            $filePath = storage_path('app/public/' . $relativePath);

            if ($relativePath !== '' && Storage::disk('public')->exists($relativePath)) {
                $extension = $document->file_type ?: pathinfo($document->path, PATHINFO_EXTENSION);
                $fileName = $extension ? ($document->title . '.' . $extension) : $document->title;
                return response()->download($filePath, $fileName);
            }
        }

        // Si aucun fichier n'existe, retourner avec erreur
        return redirect()->back()->with('error', 'Fichier non disponible');
    }

    // Reste du code (méthode communautéIndex existe déjà plus bas)

    /**
     * Helper: Obtenir le slug de la formation de l'étudiant pour les routes
     */
    private function getFormationSlug($student): string
    {
        if (!$student) {
            return 'design-graphique'; // Valeur par défaut
        }

        // Mapping avec les deux formats possibles (avec espace ET avec underscore)
        $formationMap = [
            'Design Graphique' => 'design-graphique',
            'design_graphique' => 'design-graphique',
            'Community Management' => 'community-management',
            'community_management' => 'community-management',
            'Community Manager' => 'community-management',
            'community-manager' => 'community-management',
            'community_manager' => 'community-management',
            'community manager' => 'community-management',
            'Design Graphique & Community Manager' => 'design-graphique-cm',
            'Design Graphique & Community Management' => 'design-graphique-cm',
            'design-graphique-cm' => 'design-graphique-cm',
            'design_graphique_cm' => 'design-graphique-cm',
            'design-graphique-community-manager' => 'design-graphique-cm',
            'design_graphique_community_manager' => 'design-graphique-cm',
            'design_graphique_community_management' => 'design-graphique-cm',
            'Intelligence Artificielle' => 'intelligence-artificielle',
            'intelligence_artificielle' => 'intelligence-artificielle',
            'Gestion Informatique' => 'gestion-informatique',
            'gestion_informatique' => 'gestion-informatique',
        ];

        $program = $student->program ?? '';
        $slug = $formationMap[$program] ?? 'design-graphique';

        \Log::info('getFormationSlug: program=' . $program . ', slug=' . $slug);

        return $slug;
    }

    private function getDemoDocuments()
    {
        return [
            // Catégorie Logiciels
            [
                'id' => 1,
                'titre' => 'Adobe Photoshop 2024',
                'description' => 'Logiciel professionnel de retouche photo et design graphique',
                'categorie' => 'Logiciels',
                'type' => 'Logiciel',
                'taille' => '2.5 GB',
                'format' => '.exe',
                'telechargements' => 1250,
                'date_ajout' => '2024-03-15',
                'lien' => '#'
            ],
            [
                'id' => 2,
                'titre' => 'Adobe Illustrator 2024',
                'description' => 'Logiciel de création vectorielle professionnel',
                'categorie' => 'Logiciels',
                'type' => 'Logiciel',
                'taille' => '1.8 GB',
                'format' => '.exe',
                'telechargements' => 980,
                'date_ajout' => '2024-03-14',
                'lien' => '#'
            ],
            [
                'id' => 3,
                'titre' => 'Adobe InDesign 2024',
                'description' => 'Logiciel de mise en page et publication assistée par ordinateur',
                'categorie' => 'Logiciels',
                'type' => 'Logiciel',
                'taille' => '1.2 GB',
                'format' => '.exe',
                'telechargements' => 750,
                'date_ajout' => '2024-03-13',
                'lien' => '#'
            ],

            // Catégorie Ebook
            [
                'id' => 4,
                'titre' => 'Guide Complet du Design Graphique',
                'description' => 'Manuel complet couvrant tous les aspects du design graphique moderne',
                'categorie' => 'Ebook',
                'type' => 'PDF',
                'taille' => '45 MB',
                'format' => '.pdf',
                'telechargements' => 2340,
                'date_ajout' => '2024-03-12',
                'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400',
                'lien' => '#'
            ],
            [
                'id' => 5,
                'titre' => 'Théorie des Couleurs pour Designers',
                'description' => 'Tout ce que vous devez savoir sur la psychologie et l\'harmonie des couleurs',
                'categorie' => 'Ebook',
                'type' => 'PDF',
                'taille' => '28 MB',
                'format' => '.pdf',
                'telechargements' => 1890,
                'date_ajout' => '2024-03-10',
                'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400',
                'lien' => '#'
            ],
            [
                'id' => 6,
                'titre' => 'Typographie : L\'art de la mise en page',
                'description' => 'Maîtrisez l\'art de la typographie pour des designs professionnels',
                'categorie' => 'Ebook',
                'type' => 'PDF',
                'taille' => '32 MB',
                'format' => '.pdf',
                'telechargements' => 1560,
                'date_ajout' => '2024-03-08',
                'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400',
                'lien' => '#'
            ],
            [
                'id' => 7,
                'titre' => 'Design UI/UX : Guide pratique',
                'description' => 'Créez des interfaces utilisateur intuitives et attrayantes',
                'categorie' => 'Ebook',
                'type' => 'PDF',
                'taille' => '38 MB',
                'format' => '.pdf',
                'telechargements' => 2100,
                'date_ajout' => '2024-03-05',
                'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400',
                'lien' => '#'
            ],

            // Catégorie Autres
            [
                'id' => 8,
                'titre' => 'Pack de Brushes Photoshop',
                'description' => 'Collection de 500+ brushes pour Photoshop',
                'categorie' => 'Autres',
                'type' => 'Ressource',
                'taille' => '250 MB',
                'format' => '.abr',
                'telechargements' => 3200,
                'date_ajout' => '2024-03-03',
                'lien' => '#'
            ],
            [
                'id' => 9,
                'titre' => 'Templates InDesign Magazine',
                'description' => 'Modèles professionnels de magazines prêt à l\'emploi',
                'categorie' => 'Autres',
                'type' => 'Template',
                'taille' => '180 MB',
                'format' => '.indd',
                'telechargements' => 1450,
                'date_ajout' => '2024-03-01',
                'lien' => '#'
            ],
            [
                'id' => 10,
                'titre' => 'Pack de Fonts Premium',
                'description' => 'Collection de 100 polices premium pour vos projets',
                'categorie' => 'Autres',
                'type' => 'Police',
                'taille' => '120 MB',
                'format' => '.ttf/.otf',
                'telechargements' => 2890,
                'date_ajout' => '2024-02-28',
                'lien' => '#'
            ]
        ];

        // Organiser les documents par catégorie
        $documentsParCategorie = [
            'Logiciels' => array_filter($documents, fn($doc) => $doc['categorie'] === 'Logiciels'),
            'Ebook' => array_filter($documents, fn($doc) => $doc['categorie'] === 'Ebook'),
            'Autres' => array_filter($documents, fn($doc) => $doc['categorie'] === 'Autres')
        ];

        // Statistiques
        $stats = [
            'total' => count($documents),
            'logiciels' => count($documentsParCategorie['Logiciels']),
            'ebooks' => count($documentsParCategorie['Ebook']),
            'autres' => count($documentsParCategorie['Autres'])
        ];

        return view('documents.index', [
            'user' => $user,
            'documents' => $documents,
            'documentsParCategorie' => $documentsParCategorie,
            'stats' => $stats
        ]);
    }

    /**
     * Afficher la page communauté
     */
    public function communauteIndex(): View
    {
        $user = Auth::user();

        // Statistiques de la communauté (avec les clés attendues par la vue)
        $communityStats = [
            'active_members' => 350,
            'total_messages' => 1250,
            'shared_projects' => 45,
            'graduates' => 128
        ];

        // Statistiques des réseaux sociaux
        $socialMediaStats = [
            'facebook' => [
                'formatted' => '12.5K',
                'trend' => 'up'
            ],
            'instagram' => [
                'formatted' => '8.3K',
                'trend' => 'up'
            ],
            'tiktok' => [
                'formatted' => '15.2K',
                'trend' => 'up'
            ],
            'youtube' => [
                'formatted' => '5.8K',
                'trend' => 'up'
            ],
            'linkedin' => [
                'formatted' => '3.4K',
                'trend' => 'up'
            ]
        ];

        return view('communaute.index', [
            'user' => $user,
            'communityStats' => $communityStats,
            'socialMediaStats' => $socialMediaStats,
        ]);
    }

    /**
     * Afficher la page du programme de formation
     */
    public function programmeIndex(): View
    {
        $user = Auth::user();

        // Récupérer les informations de l'étudiant
        $student = DB::table('students')->where('user_id', $user->id)->first();
        $formationPrefix = $this->getFormationSlug($student);

        // Mapping des formations pour gérer les différentes variantes
        $formationMapping = [
            'Design Graphique' => ['Design Graphique', 'Infographie', 'design-graphique', 'design_graphique', 'infographie', 'Design graphique'],
            'Community Management' => ['Community Management', 'Community Manager', 'community-management', 'community-manager', 'community_management', 'community_manager', 'community manager', 'Community management', 'CM'],
            'Gestion Informatique' => ['Gestion Informatique', 'gestion-informatique', 'gestion_informatique', 'Gestion informatique', 'GI'],
            'Intelligence Artificielle' => ['Intelligence Artificielle', 'intelligence-artificielle', 'intelligence_artificielle', 'Intelligence artificielle', 'IA'],
            'Design Graphique & Community Management' => [
                'Design Graphique & Community Management',
                'Design Graphique & Community Manager',
                'design-graphique-community-manager',
                'design_graphique_community_management',
                'design-graphique-cm',
                'design_cm',
            ],
        ];

        $studentFormation = $student->program ?? null;
        $studentFormationVariants = $studentFormation ? [$studentFormation] : [];
        $isCombinedStudent = false;

        if ($studentFormation) {
            // Vérifier si c'est la formation combinée
            $isCombined = false;
            if (isset($formationMapping['Design Graphique & Community Management'])) {
                if (in_array($studentFormation, $formationMapping['Design Graphique & Community Management'])) {
                    $isCombined = true;
                }
            }

            if ($isCombined) {
                $isCombinedStudent = true;
                // Si formation combinée, on inclut les programmes des deux formations + la combinée
                $studentFormationVariants = array_merge(
                    $formationMapping['Design Graphique & Community Management'],
                    $formationMapping['Design Graphique'],
                    $formationMapping['Community Management']
                );
            } else {
                // Sinon comportement standard
                foreach ($formationMapping as $variants) {
                    if (in_array($studentFormation, $variants, true)) {
                        $studentFormationVariants = $variants;
                        break;
                    }
                }
            }
        }

        // Toujours inclure "Toutes"
        $allowedFormations = array_values(array_unique(array_merge(['Toutes'], $studentFormationVariants)));

        $allowedFormationsNormalized = array_values(array_unique(array_map(function ($v) {
            return strtolower(trim((string) $v));
        }, array_merge($allowedFormations, ['toutes', 'toutes les formations', 'toute']))));

        // Récupérer les programmes publiés par l'admin
        // Visible si :
        // - formation = Toutes
        // - formation correspond à la formation de l'étudiant (mapping)
        // - OU ciblage spécifique via student_ids
        $programmes = DB::table('programmes')
            ->where(function ($query) use ($allowedFormations, $allowedFormationsNormalized, $student) {
                $query->whereIn('formation', $allowedFormations);

                if (!empty($allowedFormationsNormalized)) {
                    $query->orWhereIn(DB::raw('LOWER(TRIM(formation))'), $allowedFormationsNormalized);
                }

                if (!empty($student?->id) && Schema::hasColumn('programmes', 'student_ids')) {
                    $query->orWhereJsonContains('student_ids', (int) $student->id);
                }
            })
            ->orderBy('month_start', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $programmeIds = $programmes->pluck('id')->filter()->values();

        $itemsByProgramme = collect();
        if ($programmeIds->isNotEmpty() && Schema::hasTable('programme_items')) {
            $items = DB::table('programme_items')
                ->whereIn('programme_id', $programmeIds)
                ->orderBy('session_date', 'asc')
                ->orderBy('session_time', 'asc')
                ->get();

            $itemsByProgramme = $items->groupBy('programme_id');
        }

        $nowForNextItem = now();
        $today = now()->startOfDay();
        $programmes = $programmes->map(function ($programme) use ($itemsByProgramme, $formationMapping, $today, $nowForNextItem) {
            $programme->items = $itemsByProgramme->get($programme->id, collect());
            $programme->items_count = $programme->items->count();
            $formation = $programme->formation ?? null;
            $canonical = $formation;

            if (is_string($formation)) {
                $f = trim($formation);
                $canonicalMap = [
                    'Design Graphique & Community Management' => $formationMapping['Design Graphique & Community Management'] ?? [],
                    'Design Graphique' => $formationMapping['Design Graphique'] ?? [],
                    'Community Management' => $formationMapping['Community Management'] ?? [],
                    'Gestion Informatique' => $formationMapping['Gestion Informatique'] ?? [],
                    'Intelligence Artificielle' => $formationMapping['Intelligence Artificielle'] ?? [],
                ];

                foreach ($canonicalMap as $label => $variants) {
                    if (in_array($f, $variants, true)) {
                        $canonical = $label;
                        break;
                    }
                }
            }

            $programme->canonical_formation = $canonical ?: 'Toutes';

            $items = $programme->items ?? collect();
            if (!($items instanceof \Illuminate\Support\Collection)) {
                $items = collect($items);
            }

            $programme->next_item = $items->first(function ($it) use ($nowForNextItem) {
                try {
                    if (empty($it->session_date)) {
                        return false;
                    }
                    $time = !empty($it->session_time) ? $it->session_time : '00:00';
                    $dt = \Carbon\Carbon::parse($it->session_date . ' ' . $time);
                    return $dt->greaterThanOrEqualTo($nowForNextItem);
                } catch (\Throwable $e) {
                    return false;
                }
            });

            $dates = collect();
            foreach ($items as $it) {
                if (empty($it->session_date)) {
                    continue;
                }
                try {
                    $dates->push(\Carbon\Carbon::parse($it->session_date)->startOfDay());
                } catch (\Throwable $e) {
                }
            }

            if ($dates->isEmpty()) {
                $programme->status = 'a_venir';
            } else {
                $hasPast = $dates->contains(function ($d) use ($today) {
                    return $d->lessThan($today);
                });
                $hasTodayOrFuture = $dates->contains(function ($d) use ($today) {
                    return $d->greaterThanOrEqualTo($today);
                });

                if ($hasTodayOrFuture && $hasPast) {
                    $programme->status = 'en_cours';
                } elseif ($hasTodayOrFuture) {
                    $programme->status = 'a_venir';
                } else {
                    $programme->status = 'terminee';
                }
            }

            $programme->status_rank = $programme->status === 'en_cours' ? 0 : ($programme->status === 'a_venir' ? 1 : 2);
            return $programme;
        });

        if ($isCombinedStudent) {
            $programmes = $programmes
                ->sortBy([
                    ['status_rank', 'asc'],
                    ['month_start', 'desc'],
                    ['created_at', 'desc'],
                ])
                ->values();
        }

        $formationStatuses = collect(['Design Graphique', 'Community Management'])->mapWithKeys(function ($label) use ($programmes, $today) {
            $dates = collect();

            foreach ($programmes as $programme) {
                if (($programme->canonical_formation ?? null) !== $label) {
                    continue;
                }

                $items = $programme->items ?? collect();
                if (!($items instanceof \Illuminate\Support\Collection)) {
                    $items = collect($items);
                }

                foreach ($items as $it) {
                    if (empty($it->session_date)) {
                        continue;
                    }
                    try {
                        $dates->push(\Carbon\Carbon::parse($it->session_date)->startOfDay());
                    } catch (\Throwable $e) {
                    }
                }
            }

            if ($dates->isEmpty()) {
                return [$label => 'a_venir'];
            }

            $hasFutureOrToday = $dates->contains(function ($d) use ($today) {
                return $d->greaterThanOrEqualTo($today);
            });

            return [$label => $hasFutureOrToday ? 'en_cours' : 'terminee'];
        })->all();

        $now = now();
        $currentMonthSessions = collect();
        foreach ($programmes as $programme) {
            $items = $programme->items ?? collect();
            if (!($items instanceof \Illuminate\Support\Collection)) {
                $items = collect($items);
            }

            foreach ($items as $it) {
                try {
                    if (!empty($it->session_date) && \Carbon\Carbon::parse($it->session_date)->isSameMonth($now)) {
                        $it->programme_title = $programme->titre ?? null;
                        $it->programme_id = $programme->id ?? null;
                        $it->canonical_formation = $programme->canonical_formation ?? ($programme->formation ?? null);
                        $currentMonthSessions->push($it);
                    }
                } catch (\Throwable $e) {
                }
            }
        }

        $currentMonthSessions = $currentMonthSessions
            ->sortBy(function ($it) {
                try {
                    return \Carbon\Carbon::parse(($it->session_date ?? '') . ' ' . ($it->session_time ?? '00:00'))->timestamp;
                } catch (\Throwable $e) {
                    return PHP_INT_MAX;
                }
            })
            ->values();

        return view('programme.index', [
            'user' => $user,
            'programmes' => $programmes,
            'student' => $student,
            'formationPrefix' => $formationPrefix,
            'currentMonthSessions' => $currentMonthSessions,
            'formationStatuses' => $formationStatuses,
            'isCombinedStudent' => $isCombinedStudent,
        ]);
    }

    public function programmeShow(int $id): View
    {
        $user = Auth::user();

        $student = DB::table('students')->where('user_id', $user->id)->first();
        $formationPrefix = $this->getFormationSlug($student);

        $formationMapping = [
            'Design Graphique' => ['Design Graphique', 'Infographie', 'design-graphique', 'design_graphique', 'infographie', 'Design graphique'],
            'Community Management' => ['Community Management', 'Community Manager', 'community-management', 'community-manager', 'community_management', 'community_manager', 'community manager', 'Community management', 'CM'],
            'Gestion Informatique' => ['Gestion Informatique', 'gestion-informatique', 'gestion_informatique', 'Gestion informatique', 'GI'],
            'Intelligence Artificielle' => ['Intelligence Artificielle', 'intelligence-artificielle', 'intelligence_artificielle', 'Intelligence artificielle', 'IA'],
            'Design Graphique & Community Management' => [
                'Design Graphique & Community Management',
                'Design Graphique & Community Manager',
                'design-graphique-community-manager',
                'design_graphique_community_management',
                'design-graphique-cm',
                'design_cm',
            ],
        ];

        $studentFormation = $student->program ?? null;
        $studentFormationVariants = $studentFormation ? [$studentFormation] : [];
        $isCombinedStudent = false;

        if ($studentFormation) {
            $isCombined = false;
            if (isset($formationMapping['Design Graphique & Community Management'])) {
                if (in_array($studentFormation, $formationMapping['Design Graphique & Community Management'], true)) {
                    $isCombined = true;
                }
            }

            if ($isCombined) {
                $isCombinedStudent = true;
                $studentFormationVariants = array_merge(
                    $formationMapping['Design Graphique & Community Management'],
                    $formationMapping['Design Graphique'],
                    $formationMapping['Community Management']
                );
            } else {
                foreach ($formationMapping as $variants) {
                    if (in_array($studentFormation, $variants, true)) {
                        $studentFormationVariants = $variants;
                        break;
                    }
                }
            }
        }

        $allowedFormations = array_values(array_unique(array_merge(['Toutes'], $studentFormationVariants)));

        $studentId = $student?->id;

        $programme = DB::table('programmes')
            ->where('id', $id)
            ->where(function ($query) use ($allowedFormations, $studentId) {
                $query->whereIn('formation', $allowedFormations);

                if (!empty($studentId) && Schema::hasColumn('programmes', 'student_ids')) {
                    $query->orWhereJsonContains('student_ids', (int) $studentId);
                }
            })
            ->first();

        if (!$programme) {
            abort(404);
        }

        $items = collect();
        if (Schema::hasTable('programme_items')) {
            $items = DB::table('programme_items')
                ->where('programme_id', $id)
                ->orderBy('session_date', 'asc')
                ->orderBy('session_time', 'asc')
                ->get();
        }

        $programme->items = $items;
        $programme->items_count = $items->count();

        return view('programme.show', [
            'user' => $user,
            'student' => $student,
            'formationPrefix' => $formationPrefix,
            'programme' => $programme,
            'items' => $items,
            'isCombinedStudent' => $isCombinedStudent,
        ]);
    }

    public function programmeFormation(string $slug): View
    {
        $user = Auth::user();
        $student = DB::table('students')->where('user_id', $user->id)->first();
        $formationPrefix = $this->getFormationSlug($student);

        $formationMapping = [
            'Design Graphique' => ['Design Graphique', 'Infographie', 'design-graphique', 'design_graphique', 'infographie', 'Design graphique'],
            'Community Management' => ['Community Management', 'Community Manager', 'community-management', 'community-manager', 'community_management', 'community_manager', 'community manager', 'Community management', 'CM'],
            'Gestion Informatique' => ['Gestion Informatique', 'gestion-informatique', 'gestion_informatique', 'Gestion informatique', 'GI'],
            'Intelligence Artificielle' => ['Intelligence Artificielle', 'intelligence-artificielle', 'intelligence_artificielle', 'Intelligence artificielle', 'IA'],
            'Design Graphique & Community Management' => [
                'Design Graphique & Community Management',
                'Design Graphique & Community Manager',
                'design-graphique-community-manager',
                'design_graphique_community_management',
                'design-graphique-cm',
                'design_cm',
            ],
        ];

        $slugNormalized = strtolower(trim($slug));
        $targetCanonical = null;
        if (in_array($slugNormalized, ['design-graphique', 'design_graphique', 'design'], true)) {
            $targetCanonical = 'Design Graphique';
        }
        if (in_array($slugNormalized, ['community-management', 'community-manager', 'community_management', 'community_manager', 'community', 'cm'], true)) {
            $targetCanonical = 'Community Management';
        }

        if (!$targetCanonical) {
            abort(404);
        }

        $studentFormation = $student->program ?? null;
        $isCombinedStudent = false;
        if ($studentFormation && isset($formationMapping['Design Graphique & Community Management'])) {
            if (in_array($studentFormation, $formationMapping['Design Graphique & Community Management'], true)) {
                $isCombinedStudent = true;
            }
        }

        if (!$isCombinedStudent && $studentFormation) {
            $allowedCanonical = null;
            foreach (['Design Graphique', 'Community Management', 'Gestion Informatique', 'Intelligence Artificielle'] as $label) {
                if (isset($formationMapping[$label]) && in_array($studentFormation, $formationMapping[$label], true)) {
                    $allowedCanonical = $label;
                    break;
                }
            }

            if ($allowedCanonical && $allowedCanonical !== $targetCanonical) {
                abort(403);
            }
        }

        $allowedFormations = array_values(array_unique(array_merge(
            ['Toutes'],
            $formationMapping[$targetCanonical] ?? []
        )));

        $allowedFormationsNormalized = array_values(array_unique(array_map(function ($v) {
            return strtolower(trim((string) $v));
        }, array_merge($allowedFormations, ['toutes', 'toutes les formations', 'toute']))));

        $studentId = $student?->id;
        $programmes = DB::table('programmes')
            ->where(function ($query) use ($allowedFormations, $allowedFormationsNormalized, $studentId) {
                $query->whereIn('formation', $allowedFormations);

                if (!empty($allowedFormationsNormalized)) {
                    $query->orWhereIn(DB::raw('LOWER(TRIM(formation))'), $allowedFormationsNormalized);
                }

                if (!empty($studentId) && Schema::hasColumn('programmes', 'student_ids')) {
                    $query->orWhereJsonContains('student_ids', (int) $studentId);
                }
            })
            ->orderBy('month_start', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $programmeIds = $programmes->pluck('id')->filter()->values();
        $itemsByProgramme = collect();
        if ($programmeIds->isNotEmpty() && Schema::hasTable('programme_items')) {
            $items = DB::table('programme_items')
                ->whereIn('programme_id', $programmeIds)
                ->orderBy('session_date', 'asc')
                ->orderBy('session_time', 'asc')
                ->get();
            $itemsByProgramme = $items->groupBy('programme_id');
        }

        $programmes = $programmes->map(function ($programme) use ($itemsByProgramme, $formationMapping) {
            $programme->items = $itemsByProgramme->get($programme->id, collect());
            $programme->items_count = $programme->items->count();

            $formation = $programme->formation ?? null;
            $canonical = $formation;

            if (is_string($formation)) {
                $f = trim($formation);
                $canonicalMap = [
                    'Design Graphique & Community Management' => $formationMapping['Design Graphique & Community Management'] ?? [],
                    'Design Graphique' => $formationMapping['Design Graphique'] ?? [],
                    'Community Management' => $formationMapping['Community Management'] ?? [],
                    'Gestion Informatique' => $formationMapping['Gestion Informatique'] ?? [],
                    'Intelligence Artificielle' => $formationMapping['Intelligence Artificielle'] ?? [],
                ];

                foreach ($canonicalMap as $label => $variants) {
                    if (in_array($f, $variants, true)) {
                        $canonical = $label;
                        break;
                    }
                }
            }

            $programme->canonical_formation = $canonical ?: 'Toutes';
            return $programme;
        });

        $now = now();
        $sessions = collect();
        foreach ($programmes as $programme) {
            $items = $programme->items ?? collect();
            if (!($items instanceof \Illuminate\Support\Collection)) {
                $items = collect($items);
            }

            foreach ($items as $it) {
                $it->programme_title = $programme->titre ?? null;
                $it->programme_id = $programme->id ?? null;
                $it->canonical_formation = $programme->canonical_formation ?? ($programme->formation ?? null);
                $sessions->push($it);
            }
        }

        $sessions = $sessions
            ->sortBy(function ($it) {
                try {
                    return \Carbon\Carbon::parse(($it->session_date ?? '') . ' ' . ($it->session_time ?? '00:00'))->timestamp;
                } catch (\Throwable $e) {
                    return PHP_INT_MAX;
                }
            })
            ->values();

        $currentMonthSessions = $sessions->filter(function ($it) use ($now) {
            try {
                return !empty($it->session_date) && \Carbon\Carbon::parse($it->session_date)->isSameMonth($now);
            } catch (\Throwable $e) {
                return false;
            }
        })->values();

        $today = now()->startOfDay();
        $sessionDates = $sessions->map(function ($it) {
            if (empty($it->session_date)) {
                return null;
            }
            try {
                return \Carbon\Carbon::parse($it->session_date)->startOfDay();
            } catch (\Throwable $e) {
                return null;
            }
        })->filter()->values();

        if ($sessionDates->isEmpty()) {
            $formationStatus = 'a_venir';
        } else {
            $hasFutureOrToday = $sessionDates->contains(function ($d) use ($today) {
                return $d->greaterThanOrEqualTo($today);
            });
            $formationStatus = $hasFutureOrToday ? 'en_cours' : 'terminee';
        }

        return view('programme.formation', [
            'user' => $user,
            'student' => $student,
            'formationPrefix' => $formationPrefix,
            'slug' => $slugNormalized,
            'targetCanonical' => $targetCanonical,
            'programmes' => $programmes,
            'sessions' => $sessions,
            'currentMonthSessions' => $currentMonthSessions,
            'formationStatus' => $formationStatus,
        ]);
    }

    /**
     * Afficher la page Bibliothèque CM_SMM
     */
    public function bibliothequeIndex(): View
    {
        $user = Auth::user();

        // Récupérer les informations de l'étudiant
        $student = DB::table('students')->where('user_id', $user->id)->first();

        // Récupérer le module actuel depuis l'URL (ex: community-management, design-graphique-cm)
        $currentModule = request()->segment(3);

        // Pour Design Graphique & CM, on doit inclure les ressources des deux formations
        $modulesToInclude = [$currentModule];
        if (in_array($currentModule, ['design-graphique-cm', 'design-graphique-cm-legacy', 'design-graphique-community-manager'], true)) {
            $modulesToInclude = [
                $currentModule,
                'design-graphique-cm',
                'design-graphique-cm-legacy',
                'design-graphique-community-manager',
                'design-graphique',
                'community-management',
            ];
        }
        $modulesToInclude = array_values(array_unique(array_filter($modulesToInclude)));

        if (in_array('community-management', $modulesToInclude, true) && !in_array('community-manager', $modulesToInclude, true)) {
            $modulesToInclude[] = 'community-manager';
        }

        $baseQuery = \App\Models\Library::where(function ($query) {
            $query->where('status', 'active')
                ->orWhereNull('status');
        });

        // Pour design-graphique-cm : afficher toutes les ressources actives (sans filtre recipients)
        $baseQuery->where(function ($query) use ($modulesToInclude) {
            $query->where(function ($q) use ($modulesToInclude) {
                $q->whereRaw('0 = 1');
                foreach ($modulesToInclude as $module) {
                    $q->orWhereJsonContains('recipients', $module);
                }
                $q->orWhereJsonContains('recipients', 'tous')
                    ->orWhereNull('recipients')
                    ->orWhereRaw('JSON_LENGTH(recipients) = 0');
            });
        });

        // Stats calculées sur l'ensemble des items (non paginé)
        $allItems = (clone $baseQuery)
            ->with('libraryCategory')
            ->orderBy('created_at', 'desc')
            ->get();

        // Items paginés (12 par page)
        $items = (clone $baseQuery)
            ->with('libraryCategory')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Calculer les statistiques
        $stats = [
            'total_documents' => $allItems->count(),
            'par_categorie' => $allItems->groupBy('library_category_id')->map(function ($group) {
                return (object)[
                    'name' => $group->first()->libraryCategory->name ?? 'Sans catégorie',
                    'count' => $group->count(),
                    'slug' => $group->first()->libraryCategory->slug ?? 'autres'
                ];
            })->values()
        ];

        // Déterminer le préfixe de formation pour les routes
        $formationPrefix = $currentModule;

        return view('bibliotheque.index', [
            'user' => $user,
            'student' => $student,
            'items' => $allItems,
            'stats' => $stats,
            'currentModule' => $currentModule,
            'formationPrefix' => $formationPrefix
        ]);
    }

    /**
     * Afficher la page To Do List avec les TP assignés
     */
    public function todoIndex(): View
    {
        $user = Auth::user();

        try {
            // Récupérer les informations de l'étudiant
            $student = DB::table('students')
                ->where('user_id', $user->id)
                ->first();

            if (!$student) {
                Log::warning('Étudiant non trouvé pour user_id: ' . $user->id);
                return view('todo.index', [
                    'user' => $user,
                    'tpAssignments' => collect([]),
                    'stats' => [
                        'total' => 0,
                        'assigned' => 0,
                        'submitted' => 0,
                        'validated' => 0,
                        'rejected' => 0,
                    ],
                    'student' => null,
                    'formationPrefix' => 'community-management' // Valeur par défaut
                ]);
            }

            // Mapping des formations pour gérer les différentes variantes
            $formationMapping = [
                'Design Graphique' => ['Design Graphique', 'Infographie', 'design_graphique', 'infographie', 'Design graphique'],
                'Community Management' => ['Community Management', 'Community Manager', 'community_management', 'community_manager', 'community manager', 'Community management', 'CM'],
                'Gestion Informatique' => ['Gestion Informatique', 'gestion_informatique', 'Gestion informatique', 'GI'],
                'Intelligence Artificielle' => ['Intelligence Artificielle', 'intelligence_artificielle', 'Intelligence artificielle', 'IA']
            ];

            // Trouver les variantes de la formation de l'étudiant
            $studentFormationVariants = [$student->program];
            foreach ($formationMapping as $key => $variants) {
                if (in_array($student->program, $variants)) {
                    $studentFormationVariants = $variants;
                    break;
                }
            }

            Log::info('Recherche TP pour étudiant', [
                'student_id' => $student->id,
                'program' => $student->program,
                'variants' => $studentFormationVariants
            ]);

            // 1. Récupérer les TP assignments (table tp_assignments)
            $tpAssignmentsQuery = DB::table('tp_assignments')
                ->where('student_id', $student->id)
                ->where('status', 'assigned')
                ->orderByDesc('created_at');

            if (Schema::hasColumn('tp_assignments', 'admin_hidden')) {
                $tpAssignmentsQuery->where('admin_hidden', 0);
            }

            $tpAssignments = $tpAssignmentsQuery->get();

            // 2. Récupérer les projets assignés à l'étudiant (table projects)
            $projectsQuery = DB::table('projects')
                ->where('user_id', $user->id)
                ->whereIn('status', ['en_cours', 'assigned'])
                ->orderByDesc('created_at');

            if (Schema::hasColumn('projects', 'admin_hidden')) {
                $projectsQuery->where('admin_hidden', 0);
            }

            $projects = $projectsQuery->get();

            $statusMap = [
                'en_cours' => 'assigned',
                'termine' => 'submitted',
                'valide' => 'validated',
                'rejete' => 'rejected',
            ];

            Log::info('TP et Projets trouvés', [
                'student_id' => $student->id,
                'tp_assignments_count' => $tpAssignments->count(),
                'projects_count' => $projects->count()
            ]);

            // Mapper les TP assignments
            $tpAssignmentsWithFiles = $tpAssignments->map(function ($tp) {
                $files = DB::table('tp_assignment_files')
                    ->where('tp_assignment_id', $tp->id)
                    ->get()
                    ->map(function ($file) {
                        $path = ltrim((string) $file->file_path, '/');
                        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                            $file->file_path = $path;
                        } else {
                            if (str_starts_with($path, 'storage/app/public/')) {
                                $path = substr($path, strlen('storage/app/public/'));
                            }
                            $file->file_path = \App\Models\MediaUrl::fromPath($path);
                        }
                        $file->file_name = $file->file_name ?? 'fichier';
                        return $file;
                    });

                $tp->files = $files;
                $tp->source_table = 'tp_assignments';
                return $tp;
            });

            // Mapper les projets
            $projectsWithFiles = $projects->map(function ($project) use ($statusMap) {
                $files = DB::table('project_images')
                    ->where('project_id', $project->id)
                    ->orderBy('order_index', 'asc')
                    ->get()
                    ->map(function ($file) {
                        $path = ltrim((string) $file->file_path, '/');
                        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                            $file->file_path = $path;
                        } else {
                            if (str_starts_with($path, 'storage/app/public/')) {
                                $path = substr($path, strlen('storage/app/public/'));
                            }
                            $file->file_path = \App\Models\MediaUrl::fromPath($path);
                        }
                        $file->file_name = $file->original_name ?? $file->filename ?? 'fichier';
                        return $file;
                    });

                $project->files = $files;
                $project->status = $statusMap[$project->status] ?? 'assigned';
                $project->formation = $project->category ?? null;
                $project->source_table = 'projects';

                return $project;
            });

            // Fusionner les deux collections
            $tpWithFiles = $tpAssignmentsWithFiles
                ->concat($projectsWithFiles)
                ->where('status', 'assigned')
                ->sortByDesc('created_at')
                ->values();

            $stats = [
                'total' => $tpWithFiles->count(),
                'assigned' => $tpWithFiles->count(),
                'submitted' => 0,
                'validated' => 0,
                'rejected' => 0,
            ];

            // Déterminer le préfixe de formation pour les routes (utiliser getFormationSlug)
            $formationPrefix = $this->getFormationSlug($student);

            Log::info('TP assignés chargés', [
                'student_id' => $student->id,
                'formation' => $student->program,
                'total_tp' => $stats['total']
            ]);

            return view('todo.index', [
                'user' => $user,
                'student' => $student,
                'tpAssignments' => $tpWithFiles,
                'stats' => $stats,
                'formationPrefix' => $formationPrefix
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des TP assignés: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return view('todo.index', [
                'user' => $user,
                'tpAssignments' => collect([]),
                'stats' => [
                    'total' => 0,
                    'assigned' => 0,
                    'submitted' => 0,
                    'validated' => 0,
                    'rejected' => 0,
                ],
                'student' => null,
                'error' => 'Erreur lors du chargement des travaux pratiques.',
                'formationPrefix' => 'community-management' // Valeur par défaut
            ]);
        }
    }

    /**
     * Afficher la page de soumission d'un TP
     */
    public function showSubmitPage($id)
    {
        try {
            $user = Auth::user();

            // Récupérer l'étudiant
            $student = DB::table('students')
                ->where('user_id', $user->id)
                ->first();

            if (!$student) {
                return redirect()->back()->with('error', 'Étudiant non trouvé');
            }

            // Récupérer le TP
            $tp = DB::table('tp_assignments')
                ->where('id', $id)
                ->where('student_id', $student->id)
                ->first();

            if (!$tp) {
                return redirect()->back()->with('error', 'TP non trouvé ou non autorisé');
            }

            // Vérifier que le TP peut être soumis
            if ($tp->status !== 'assigned') {
                return redirect()->back()->with('error', 'Ce TP a déjà été soumis');
            }

            // Déterminer le préfixe de formation
            $formationPrefix = strtolower(str_replace(' ', '-', $student->program));

            return view('tp.submit', [
                'tp' => $tp,
                'user' => $user,
                'student' => $student,
                'formationPrefix' => $formationPrefix
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affichage de la page de soumission: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors du chargement de la page');
        }
    }

    /**
     * Soumettre un TP
     */
    public function submitTP(Request $request, $id)
    {
        try {
            Log::info('🚀 === DÉBUT SOUMISSION TP ===', [
                'tp_id' => $id,
                'user_id' => Auth::id(),
                'has_files' => $request->hasFile('files'),
                'files_count' => $request->hasFile('files') ? count($request->file('files')) : 0,
                'submission_link' => $request->submission_link
            ]);

            $user = Auth::user();

            // Valider les données - Les fichiers sont obligatoires
            if (!$request->hasFile('files') || count($request->file('files')) === 0) {
                Log::warning('❌ Soumission TP échouée - Aucun fichier', [
                    'tp_id' => $id,
                    'user_id' => $user->id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Vous devez uploader au moins un fichier pour soumettre votre TP.'
                ], 400);
            }

            $request->validate([
                'submission_link' => 'nullable|url',
                'files.*' => 'required|file|max:10240', // 10 Mo max par fichier
            ]);

            // Récupérer l'étudiant
            $student = DB::table('students')
                ->where('user_id', $user->id)
                ->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Étudiant non trouvé'
                ], 404);
            }

            // Vérifier que le TP existe et appartient à l'étudiant
            $tp = DB::table('tp_assignments')
                ->where('id', $id)
                ->where('student_id', $student->id)
                ->first();

            if (!$tp) {
                return response()->json([
                    'success' => false,
                    'message' => 'TP non trouvé ou non autorisé'
                ], 404);
            }

            // Vérifier que le TP n'a pas déjà été soumis
            if ($tp->status !== 'assigned') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce TP a déjà été soumis'
                ], 400);
            }

            // Mettre à jour le TP
            DB::table('tp_assignments')
                ->where('id', $id)
                ->update([
                    'status' => 'submitted',
                    'submission_link' => $request->submission_link,
                    'updated_at' => now()
                ]);

            // Gérer l'upload de fichiers si présents
            $uploadedFiles = [];
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    // Valider chaque fichier
                    if ($file->isValid() && $file->getSize() <= 10485760) { // 10 Mo max
                        // Générer un nom unique
                        $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();

                        // Stocker le fichier
                        $directory = 'tp_submissions/' . $id . '/' . $student->id;
                        $path = $file->storeAs($directory, $fileName, 'public');

                        // Enregistrer dans la base de données
                        DB::table('tp_submission_files')->insert([
                            'tp_assignment_id' => $id,
                            'file_name' => $file->getClientOriginalName(),
                            'file_path' => $path,
                            'file_size' => $file->getSize(),
                            'mime_type' => $file->getMimeType(),
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);

                        $uploadedFiles[] = $file->getClientOriginalName();
                    }
                }
            }

            // Envoyer une notification email aux administrateurs
            try {
                // Récupérer tous les admins (Super Admin et Assistant qui gèrent les TP)
                $admins = DB::table('admins')
                    ->whereIn('role', ['super_admin', 'assistant'])
                    ->get();

                Log::info('🔍 Recherche admins pour notification TP', [
                    'admins_trouvés' => $admins->count(),
                    'tp_id' => $id,
                    'tp_title' => $tp->title,
                    'student_email' => $student->email ?? 'N/A'
                ]);

                if ($admins->count() > 0) {
                    foreach ($admins as $admin) {
                        if ($admin->email) {
                            try {
                                \Mail::to($admin->email)->send(
                                    new \App\Mail\TpSubmissionNotification(
                                        $student,
                                        $tp->title,
                                        $tp->description,
                                        $tp->formation,
                                        $request->submission_link,
                                        count($uploadedFiles)
                                    )
                                );
                                Log::info('✅ Email de notification TP envoyé avec succès', [
                                    'admin_email' => $admin->email,
                                    'admin_name' => $admin->name ?? 'N/A',
                                    'admin_role' => $admin->role,
                                    'tp_title' => $tp->title,
                                    'fichiers_count' => count($uploadedFiles)
                                ]);
                            } catch (\Exception $emailError) {
                                Log::error('❌ Erreur envoi email à admin individuel', [
                                    'admin_email' => $admin->email,
                                    'error' => $emailError->getMessage(),
                                    'trace' => $emailError->getTraceAsString()
                                ]);
                            }
                        } else {
                            Log::warning('⚠️ Admin sans email', [
                                'admin_id' => $admin->id,
                                'admin_name' => $admin->name ?? 'N/A',
                                'admin_role' => $admin->role
                            ]);
                        }
                    }
                } else {
                    Log::warning('⚠️ Aucun admin actif trouvé pour recevoir la notification TP', [
                        'roles_recherchés' => ['super_admin', 'assistant'],
                        'status_requis' => 'active'
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('❌ Erreur globale lors de l\'envoi des emails admin', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Ne pas bloquer la soumission si l'email échoue
            }

            Log::info('TP soumis avec succès', [
                'tp_id' => $id,
                'student_id' => $student->id,
                'submission_link' => $request->submission_link,
                'files_uploaded' => count($uploadedFiles)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'TP soumis avec succès',
                'files_uploaded' => count($uploadedFiles)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la soumission du TP: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la soumission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Télécharger la facture d'un paiement
     */
    public function downloadInvoice($paymentId)
    {
        $user = Auth::user();

        // Récupérer le paiement
        $payment = DB::table('payments')->where('id', $paymentId)->first();

        if (!$payment) {
            return redirect()->back()->with('error', 'Paiement introuvable');
        }

        // Vérifier que le paiement appartient bien à l'utilisateur
        $preReg = DB::table('pre_registrations')
            ->where('id', $payment->pre_registration_id)
            ->where('email', $user->email)
            ->first();

        if (!$preReg) {
            return redirect()->back()->with('error', 'Accès non autorisé');
        }

        // Vérifier que le paiement est complété
        if ($payment->status !== 'completed') {
            return redirect()->back()->with('error', 'Ce paiement n\'est pas encore complété');
        }

        // Récupérer les informations de l'étudiant
        $student = DB::table('students')->where('user_id', $user->id)->first();

        // Générer un reçu PDF complet (même design que l'admin) en agrégeant les paiements liés
        $payments = DB::table('payments')
            ->where('pre_registration_id', (int) $payment->pre_registration_id)
            ->orderByRaw("CASE WHEN paid_at IS NULL THEN 1 ELSE 0 END")
            ->orderBy('paid_at', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        $formationLabel = (new \App\Http\Controllers\Admin\PreRegistrationAdminController())->getFormationLabel($preReg->choix_formation ?? null);
        $firstPaymentDate = optional($payments->sortBy('created_at')->first())->created_at;
        $pricingDate = $firstPaymentDate ?: ($preReg->created_at ?? null);
        $grossTotalAmount = (int) \App\Services\CinetPayService::getFormationPrice($formationLabel, $pricingDate);
        $paymentsTotal = (int) round((float) ($payments->max('total_amount') ?? 0));
        $storedDiscountAmount = min((int) ($preReg->discount_amount ?? 0), $grossTotalAmount);
        $inferredDiscountAmount = ($storedDiscountAmount <= 0 && $paymentsTotal > 0 && $paymentsTotal < $grossTotalAmount)
            ? ($grossTotalAmount - $paymentsTotal)
            : 0;
        $discountAmount = max($storedDiscountAmount, $inferredDiscountAmount);
        $expectedTotal = max(0, $grossTotalAmount - $discountAmount);
        $totalAmount = $discountAmount > 0 ? $expectedTotal : max($paymentsTotal, $expectedTotal);

        $amountPaid = (int) round((float) $payments->where('status', 'completed')->sum('amount'));
        $remaining = max(0, $totalAmount - $amountPaid);

        $studentName = trim((($student->first_name ?? null) ?: ($preReg->prenom ?? '')) . ' ' . (($student->last_name ?? null) ?: ($preReg->nom ?? '')));
        $studentEmail = ($student->email ?? null) ?: ($preReg->email ?? '');
        $formation = $preReg->choix_formation ?? (($student->program ?? null) ?: 'Formation');

        $studentIdLabel = '';
        if (!empty($student->student_id)) {
            $studentIdLabel = (string) $student->student_id;
        } elseif (!empty($student->id)) {
            $studentIdLabel = (string) $student->id;
        }

        $registrationDate = '';
        if (!empty($student->created_at)) {
            try {
                $registrationDate = \Carbon\Carbon::parse($student->created_at)->format('d/m/Y');
            } catch (\Throwable $e) {
                $registrationDate = '';
            }
        }
        if ($registrationDate === '' && !empty($preReg->created_at)) {
            try {
                $registrationDate = \Carbon\Carbon::parse($preReg->created_at)->format('d/m/Y');
            } catch (\Throwable $e) {
                $registrationDate = '';
            }
        }

        $receiptNumber = 'EVC-RC-' . str_pad((string) $preReg->id, 6, '0', STR_PAD_LEFT) . '-' . now()->format('Ymd');

        $paymentsForPdf = $payments->map(function ($p) {
            $installmentLabel = '';
            if (($p->payment_type ?? null) === 'installment' && !empty($p->installment_number) && !empty($p->total_installments)) {
                $installmentLabel = 'Tranche ' . $p->installment_number . '/' . $p->total_installments;
            } elseif (($p->payment_type ?? null) === 'installment' && !empty($p->installment_number)) {
                $installmentLabel = 'Tranche ' . $p->installment_number;
            } else {
                $installmentLabel = 'Paiement';
            }

            $statusLabel = match ($p->status) {
                'completed' => 'Payé',
                'pending' => 'En attente',
                'failed' => 'Échoué',
                'cancelled' => 'Annulé',
                'refunded' => 'Remboursé',
                default => (string) ($p->status ?? ''),
            };

            $paidAt = $p->paid_at ? \Carbon\Carbon::parse($p->paid_at)->format('d/m/Y') : '';
            $createdAt = $p->created_at ? \Carbon\Carbon::parse($p->created_at)->format('d/m/Y') : '';

            return [
                'amount' => (float) ($p->amount ?? 0),
                'status' => $p->status,
                'status_label' => $statusLabel,
                'payment_reference' => (string) ($p->payment_reference ?? ''),
                'installment_label' => $installmentLabel,
                'paid_at' => $paidAt,
                'created_at' => $createdAt,
            ];
        })->toArray();

        $primaryRef = '';
        $primaryPayment = $payments->firstWhere('payment_reference', '!=', null);
        if ($primaryPayment && !empty($primaryPayment->payment_reference)) {
            $primaryRef = (string) $primaryPayment->payment_reference;
        }

        $generator = new \App\Services\PaymentReceiptGenerator();
        $result = $generator->generate([
            'receipt_number' => $receiptNumber,
            'issued_at' => now()->format('d/m/Y H:i'),
            'student_name' => $studentName,
            'student_email' => $studentEmail,
            'formation' => $formation,
            'student_id' => $studentIdLabel,
            'registration_date' => $registrationDate,
            'payment_reference' => $primaryRef,
            'gross_total_amount' => $grossTotalAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'amount_paid' => $amountPaid,
            'remaining' => $remaining,
            'payments' => $paymentsForPdf,
        ]);

        $filename = 'Recu_EVC_' . ($payment->payment_reference ?? $preReg->id) . '_' . now()->format('Ymd_His') . '.pdf';
        return response()->download($result['path'], $filename)->deleteFileAfterSend(true);
    }

    /**
     * Afficher la page des paiements
     */
    public function paiementsIndex(): View
    {
        $user = Auth::user();
        $student = DB::table('students')->where('user_id', $user->id)->first();

        // Récupérer la pré-inscription de l'étudiant
        $preReg = DB::table('pre_registrations')->where('email', $user->email)->orderByDesc('id')->first();

        // Initialiser les valeurs par défaut
        $formationLabel = $preReg
            ? (new \App\Http\Controllers\Admin\PreRegistrationAdminController())->getFormationLabel($preReg->choix_formation ?? null)
            : null;

        // Paiements : priorité au user_id (compte créé), fallback sur pre_registration_id si dispo
        $paymentsQuery = DB::table('payments')
            ->where(function ($q) use ($user, $preReg) {
                $q->where('user_id', $user->id);
                if ($preReg) {
                    $q->orWhere('pre_registration_id', $preReg->id);
                }
            });

        // Tri : tranches d'abord (1,2), puis le reste
        $payments = $paymentsQuery
            ->orderByRaw('installment_number is null asc')
            ->orderBy('installment_number', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $firstPaymentDate = optional($payments->sortBy('created_at')->first())->created_at;
        $pricingDate = $firstPaymentDate ?: ($preReg->created_at ?? null);
        $grossPaymentAmount = $formationLabel
            ? (float) \App\Services\CinetPayService::getFormationPrice($formationLabel, $pricingDate)
            : 0;
        $discountAmount = min((float) ($preReg->discount_amount ?? 0), $grossPaymentAmount);
        $expectedAmount = max(0, $grossPaymentAmount - $discountAmount);
        $paymentsTotal = (float) ($payments->max('total_amount') ?? 0);
        $sumAmounts = (float) $payments->sum('amount');
        $paymentAmount = $discountAmount > 0 ? max($expectedAmount, $sumAmounts) : max($paymentsTotal, $expectedAmount, $sumAmounts);

        $paymentPaid = (float) $payments->where('status', 'completed')->sum('amount');
        $paymentRemaining = max(0, (float) $paymentAmount - (float) $paymentPaid);
        $paymentProgress = $paymentAmount > 0 ? ($paymentPaid / $paymentAmount) * 100 : 0;

        $nextPayment = $payments
            ->where('status', 'pending')
            ->sortBy(function ($p) {
                return $p->installment_number ?? 999;
            })
            ->first();

        return view('paiements.index', [
            'user' => $user,
            'student' => $student,
            'paymentAmount' => $paymentAmount,
            'grossPaymentAmount' => $grossPaymentAmount,
            'discountAmount' => $discountAmount,
            'paymentPaid' => $paymentPaid,
            'paymentRemaining' => $paymentRemaining,
            'paymentProgress' => round($paymentProgress, 2),
            'payments' => $payments,
            'preRegistration' => $preReg,
            'nextPayment' => $nextPayment,
        ]);
    }

    /**
     * Afficher la page de fin de formation
     */
    public function finFormationIndex(): View
    {
        $user = Auth::user();
        $currentModule = request()->segment(3);
        $student = DB::table('students')->where('user_id', $user->id)->first();

        if (!$student) {
            abort(404, 'Profil étudiant non trouvé');
        }

        // Récupérer les TP de l'étudiant
        $tpAssignments = DB::table('tp_assignments')
            ->where('student_id', $student->id)
            ->get();

        // Statistiques TP
        $totalTP = $tpAssignments->count();
        $tpValidated = $tpAssignments->where('status', 'validated')->count();
        $tpPending = $tpAssignments->whereIn('status', ['assigned', 'submitted'])->count();
        $tpRejected = $tpAssignments->where('status', 'rejected')->count();

        // Récupérer les projets de l'étudiant
        $projects = DB::table('projects')
            ->where('user_id', $user->id)
            ->get();

        // Statistiques Projets
        $totalProjects = $projects->count();
        $projectsCompleted = $projects->where('status', 'valide')->count();
        $projectsInProgress = $projects->whereIn('status', ['en_cours', 'termine'])->count();

        // Calculer progression globale (moyenne pondérée)
        // TP: 50%, Projets: 30%, Rapport: 20%
        $tpProgress = $totalTP > 0 ? ($tpValidated / $totalTP) * 100 : 0;
        $projectProgress = $totalProjects > 0 ? ($projectsCompleted / $totalProjects) * 100 : 0;
        $reportProgress = 0;

        // Critères d'éligibilité (valeurs minimales requises)
        $minTPRequired = match (true) {
            $currentModule === 'design-graphique-cm' => 50,
            $currentModule === 'design-graphique' => 35,
            in_array($currentModule, ['community-management', 'community-manager'], true) => 15,
            default => 15,
        };
        $minProjectsRequired = 4;
        $minGrade = 12; // /20

        // Vérifier les paiements depuis la nouvelle table payments
        $preReg = DB::table('pre_registrations')->where('email', $user->email)->first();

        if ($preReg) {
            // Récupérer tous les paiements liés à cette pré-inscription
            $payments = DB::table('payments')
                ->where('pre_registration_id', $preReg->id)
                ->get();

            $formationLabel = (new \App\Http\Controllers\Admin\PreRegistrationAdminController())->getFormationLabel($preReg->choix_formation ?? null);
            $firstPaymentDate = optional($payments->sortBy('created_at')->first())->created_at;
            $pricingDate = $firstPaymentDate ?: ($preReg->created_at ?? null);
            $grossPaymentAmount = (int) \App\Services\CinetPayService::getFormationPrice($formationLabel, $pricingDate);
            $discountAmount = min((int) ($preReg->discount_amount ?? 0), $grossPaymentAmount);
            $expectedAmount = max(0, $grossPaymentAmount - $discountAmount);
            $paymentsTotal = (int) round((float) ($payments->max('total_amount') ?? 0));
            $paymentAmount = $discountAmount > 0 ? $expectedAmount : max($paymentsTotal, $expectedAmount);

            $paymentPaid = $payments->where('status', 'completed')->sum('amount');
            $paymentRemaining = $paymentAmount - $paymentPaid;
            $paymentProgress = $paymentAmount > 0 ? ($paymentPaid / $paymentAmount) * 100 : 0;
            $paymentComplete = $paymentRemaining <= 0;
        } else {
            // Pas de pré-inscription trouvée, valeurs par défaut
            $paymentAmount = 0;
            $grossPaymentAmount = 0;
            $discountAmount = 0;
            $paymentPaid = 0;
            $paymentRemaining = $paymentAmount;
            $paymentProgress = 0;
            $paymentComplete = false;
        }

        $tpEligible = $tpValidated >= $minTPRequired;
        $projectsEligible = $projectsCompleted >= $minProjectsRequired;

        // Vérifier si un rapport a été uploadé
        $report = null;
        $reportTpId = null;

        if (Schema::hasTable('end_of_training_reports')) {
            $report = DB::table('end_of_training_reports')
                ->where('student_id', $student->id)
                ->first();
        }

        // Fallback : si aucun rapport "fin de formation" dans end_of_training_reports,
        // on le recherche dans les TPs (module Documents) via une pièce jointe PDF.
        if (!$report && Schema::hasTable('tp') && Schema::hasTable('tp_files')) {
            $tpReport = DB::table('tp')
                ->where('tp.user_id', $user->id)
                ->leftJoin('tp_files', 'tp.id', '=', 'tp_files.tp_id')
                ->select(
                    'tp.id as tp_id',
                    'tp.title',
                    'tp.status',
                    'tp.admin_comment',
                    'tp.created_at',
                    'tp.updated_at',
                    'tp_files.original_name as original_filename'
                )
                ->whereNotNull('tp_files.original_name')
                ->whereRaw("LOWER(RIGHT(tp_files.original_name, 4)) = '.pdf'")
                ->orderByDesc('tp.id')
                ->first();

            if ($tpReport) {
                $reportTpId = $tpReport->tp_id;

                // Normaliser pour la vue fin-formation (mêmes clés que end_of_training_reports)
                $report = (object)[
                    'id' => $tpReport->tp_id,
                    'original_filename' => $tpReport->original_filename ?? ($tpReport->title ?? 'Rapport'),
                    'submitted_at' => $tpReport->created_at,
                    'status' => $tpReport->status,
                    'admin_comment' => $tpReport->admin_comment,
                ];
            }
        }

        $reportUploaded = !empty($report);

        $reportProgress = $reportUploaded ? 100 : 0;

        $globalProgress = ($tpProgress * 0.5) + ($projectProgress * 0.3) + ($reportProgress * 0.2);

        // Éligibilité sans paiement pour le moment
        $isEligible = $tpEligible && $projectsEligible && $reportUploaded;

        // Grouper les TP par statut
        $tpValidatedList = $tpAssignments->where('status', 'validated');
        $tpPendingList = $tpAssignments->whereIn('status', ['assigned', 'submitted']);

        return view('fin-formation.index', [
            'user' => $user,
            'student' => $student,
            'globalProgress' => round($globalProgress, 0),
            'currentModule' => $currentModule,

            // Statistiques TP
            'totalTP' => $totalTP,
            'tpValidated' => $tpValidated,
            'tpPending' => $tpPending,
            'tpRejected' => $tpRejected,
            'tpProgress' => round($tpProgress, 0),
            'tpValidatedList' => $tpValidatedList,
            'tpPendingList' => $tpPendingList,

            // Statistiques Projets
            'totalProjects' => $totalProjects,
            'projectsCompleted' => $projectsCompleted,
            'projectsInProgress' => $projectsInProgress,
            'projectProgress' => round($projectProgress, 0),
            'projects' => $projects,

            // Critères d'éligibilité
            'minTPRequired' => $minTPRequired,
            'minProjectsRequired' => $minProjectsRequired,
            'paymentAmount' => $paymentAmount,
            'grossPaymentAmount' => $grossPaymentAmount,
            'discountAmount' => $discountAmount,
            'paymentPaid' => $paymentPaid,
            'paymentRemaining' => $paymentRemaining,
            'paymentProgress' => round($paymentProgress, 0),
            'paymentComplete' => $paymentComplete,
            'tpEligible' => $tpEligible,
            'projectsEligible' => $projectsEligible,
            'reportUploaded' => $reportUploaded,
            'report' => $report,
            'reportTpId' => $reportTpId,
            'isEligible' => $isEligible,
        ]);
    }

    /**
     * Upload du rapport de fin de formation
     */
    public function uploadReport(Request $request)
    {
        $request->validate([
            'report_file' => 'required|file|mimes:pdf|max:10240', // Max 10MB
        ]);

        $user = Auth::user();
        $student = DB::table('students')->where('user_id', $user->id)->first();

        if (!$student) {
            return back()->with('error', 'Profil étudiant non trouvé');
        }

        // Vérifier si un rapport existe déjà
        $existingReport = DB::table('end_of_training_reports')
            ->where('user_id', $user->id)
            ->first();

        // Supprimer l'ancien fichier si un rapport existe déjà
        if ($existingReport && $existingReport->file_path) {
            $oldFilePath = storage_path('app/public/' . $existingReport->file_path);
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }

        // Stocker le nouveau fichier
        $file = $request->file('report_file');
        $originalFilename = $file->getClientOriginalName();
        $filename = time() . '_' . $user->id . '_' . $originalFilename;
        $filePath = $file->storeAs('reports/end-of-training', $filename, 'public');

        // Données du rapport
        $reportData = [
            'user_id' => $user->id,
            'student_id' => $student->id,
            'formation' => $student->program ?? 'Non spécifié',
            'file_path' => $filePath,
            'original_filename' => $originalFilename,
            'file_size' => $file->getSize(),
            'status' => 'pending',
            'submitted_at' => now(),
            'updated_at' => now(),
        ];

        if ($existingReport) {
            // Mettre à jour le rapport existant
            DB::table('end_of_training_reports')
                ->where('id', $existingReport->id)
                ->update($reportData);
        } else {
            // Créer un nouveau rapport
            $reportData['created_at'] = now();
            DB::table('end_of_training_reports')->insert($reportData);
        }

        return back()->with('success', 'Rapport uploadé avec succès ! Il sera vérifié par un administrateur.');
    }

    /**
     * Télécharger le rapport de fin de formation
     */
    public function downloadReport($id)
    {
        $user = Auth::user();

        $report = DB::table('end_of_training_reports')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$report) {
            abort(404, 'Rapport non trouvé');
        }

        $filePath = storage_path('app/public/' . $report->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'Fichier non trouvé');
        }

        return response()->download($filePath, $report->original_filename);
    }

    /**
     * Afficher une actualité en détail
     */
    public function showActualite($id)
    {
        $actualite = DB::table('actualites')->where('id', $id)->first();

        if (!$actualite || $actualite->status !== 'published') {
            abort(404, 'Actualité non trouvée');
        }

        // Incrémenter le compteur de vues
        DB::table('actualites')->where('id', $id)->increment('views');

        $user = Auth::user();
        $student = DB::table('students')->where('user_id', $user->id)->first();

        return view('actualites.student-show', [
            'actualite' => $actualite,
            'user' => $user,
            'student' => $student,
        ]);
    }

    /**
     * Dashboard Community Management avec statistiques complètes
     */
    public function communityManagement(): View
    {
        $user = Auth::user();

        // Récupérer les données de l'étudiant via user_id
        $student = DB::table('students')->where('user_id', $user->id)->first();

        // Préinscription (si la table existe)
        $preReg = null;
        try {
            if (Schema::hasTable('pre_registrations')) {
                $email = $student->email ?? $user->email;
                if (!empty($email)) {
                    $preReg = DB::table('pre_registrations')
                        ->where('email', $email)
                        ->orderByDesc('id')
                        ->first();
                }
            }
        } catch (\Exception $e) {
            $preReg = null;
        }

        // Récupérer les actualités publiées et visibles pour Community Management
        $actualites = DB::table('actualites')
            ->where('status', 'published')
            ->where(function ($query) {
                $query->where('visibility', 'public')
                    ->orWhere('visibility', 'all_formations')
                    ->orWhere('visibility', 'like', '%Community Management%');
            })
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        // Calculer les statistiques dynamiques pour Community Management
        // MÊME LOGIQUE QUE fin-formation/index
        $studentId = $student->id ?? null;

        // Initialisation
        $tpAFaire = 0;
        $tpValides = 0;
        $tpEnAttente = 0;
        $totalTp = 0;
        $projetsActifs = 0;
        $projetsEnCours = 0;
        $tpRejected = 0;

        if ($studentId) {
            // TP depuis tp_assignments (comme fin-formation)
            $tpAssignments = DB::table('tp_assignments')
                ->where('student_id', $studentId)
                ->get();

            $totalTp = $tpAssignments->count();
            $tpValides = $tpAssignments->where('status', 'validated')->count();
            $tpEnAttente = $tpAssignments->whereIn('status', ['assigned', 'submitted'])->count();
            $tpRejected = $tpAssignments->where('status', 'rejected')->count();

            // Projets depuis table projects (comme fin-formation)
            $projects = DB::table('projects')
                ->where('user_id', $user->id)
                ->get();

            $totalProjects = $projects->count();
            $projetsEnCours = $projects->where('status', 'en_cours')->count();
            $projetsCompletes = $projects->where('status', 'valide')->count();
            $projetsTermines = $projects->where('status', 'termine')->count();

            $projetsActifs = $projetsEnCours + $projetsTermines;
            $tpAFaire = $tpEnAttente; // TP à traiter

            // Projets à faire = projets assignés par l'admin (status en_cours)
            $projetsAFaire = $projetsEnCours;
        }

        // Compter les événements depuis la table events
        try {
            $evenements = DB::table('events')->count();
        } catch (\Exception $e) {
            $evenements = 0;
        }

        // Calculer la progression globale
        $progressionGlobale = $totalTp > 0 ? round(($tpValides / $totalTp) * 100) : 0;

        // Déterminer si éligible au certificat (80% de TP validés)
        $eligibleCertificat = $totalTp > 0 && ($tpValides / $totalTp) >= 0.8;

        $stats = [
            // Progression globale (dynamique)
            'progression_globale' => $progressionGlobale,

            // Stats pour les cartes de la vue
            'formations_disponibles' => 0, // Pas utilisé pour CM
            'tp_realises' => $tpValides,
            'tp_total' => $totalTp,
            'projets_realises' => $projetsCompletes ?? $tpValides,
            'projets_total' => $totalProjects ?? $totalTp,
            'projets_a_faire' => $projetsAFaire ?? 0,

            // Campagnes à créer (dynamique)
            'tp_a_faire' => $tpAFaire,

            // Projets actifs (dynamique)
            'projets_actifs' => $projetsActifs,

            // Projets en cours (dynamique)
            'projets_en_cours' => $projetsEnCours,

            // TP validés (dynamique)
            'tp_valides' => $tpValides,

            // TP en attente (dynamique)
            'tp_en_attente' => $tpEnAttente,

            // TP rejetés
            'tp_rejetes' => $tpRejected,

            // Certification
            'certification' => $eligibleCertificat ? 'Validé' : 'En cours',

            // Éligible au certificat (dynamique)
            'eligible_certificat' => $eligibleCertificat,

            // Formation de la semaine
            'formation_semaine' => 'Stratégie Social Media',

            // Total TP (dynamique)
            'total_tp' => $totalTp,

            // Événements et Actualités (dynamiques)
            'evenements' => $evenements,
            'actualites' => $actualites->count(),
        ];

        // Calculer le montant restant à payer (cohérent avec paiementsIndex)
        $montantRestant = 0;
        try {
            if ($preReg && isset($preReg->montant_total) && isset($preReg->montant_paye)) {
                $montantRestant = max(0, (float) $preReg->montant_total - (float) $preReg->montant_paye);
            } else {
                $paymentsQuery = DB::table('payments')
                    ->where(function ($q) use ($user, $preReg) {
                        $q->where('user_id', $user->id);
                        if ($preReg && isset($preReg->id)) {
                            $q->orWhere('pre_registration_id', $preReg->id);
                        }
                    });

                $payments = $paymentsQuery->get();

                $formationLabel = $preReg ? (new \App\Http\Controllers\Admin\PreRegistrationAdminController())->getFormationLabel($preReg->choix_formation ?? null) : null;
                $firstPaymentDate = optional($payments->sortBy('created_at')->first())->created_at;
                $pricingDate = $firstPaymentDate ?: ($preReg->created_at ?? null);
                $grossPaymentAmount = $formationLabel ? (float) \App\Services\CinetPayService::getFormationPrice($formationLabel, $pricingDate) : 0;
                $discountAmount = min((float) ($preReg->discount_amount ?? 0), $grossPaymentAmount);
                $expectedAmount = max(0, $grossPaymentAmount - $discountAmount);
                $paymentsTotal = (float) ($payments->max('total_amount') ?? 0);
                $sumAmounts = (float) $payments->sum('amount');
                $paymentAmount = $discountAmount > 0 ? max($expectedAmount, $sumAmounts) : max($paymentsTotal, $expectedAmount, $sumAmounts);

                $paymentPaid = (float) $payments->where('status', 'completed')->sum('amount');
                $montantRestant = max(0, $paymentAmount - $paymentPaid);
            }
        } catch (\Exception $e) {
            $montantRestant = 0;
        }

        $stats['montant_restant'] = $montantRestant;

        $accountCreatedAt = \Carbon\Carbon::parse($user->created_at);
        $expirationDate = AccountExpirationHelper::getExpirationDate($user);
        $now = \Carbon\Carbon::now();
        $daysRemaining = (int) $now->diffInDays($expirationDate, false);
        $isExpired = $daysRemaining <= 0;
        if ($daysRemaining < 0) {
            $daysRemaining = 0;
        }

        $isExpiringSoon = !$isExpired && $daysRemaining <= 30;

        return view('dashboard.community-management', [
            'user' => $user,
            'student' => $student,
            'preReg' => $preReg,
            'stats' => $stats,
            'actualites' => $actualites,
            'accountCreatedAt' => $accountCreatedAt,
            'expirationDate' => $expirationDate,
            'daysRemaining' => $daysRemaining,
            'isExpired' => $isExpired,
            'isExpiringSoon' => $isExpiringSoon,
            'program' => 'Community Management',
            'level' => $student->level ?? 'Débutant',
        ]);
    }

    public function communityManagementStats(): JsonResponse
    {
        $user = Auth::user();
        $student = DB::table('students')->where('user_id', $user->id)->first();

        $preReg = null;
        try {
            if (Schema::hasTable('pre_registrations')) {
                $email = $student->email ?? $user->email;
                if (!empty($email)) {
                    $preReg = DB::table('pre_registrations')
                        ->where('email', $email)
                        ->orderByDesc('id')
                        ->first();
                }
            }
        } catch (\Exception $e) {
            $preReg = null;
        }

        $studentId = $student->id ?? null;

        $totalTp = 0;
        $tpValides = 0;
        $tpEnAttente = 0;
        $tpRejected = 0;
        $totalProjects = 0;
        $projetsEnCours = 0;
        $projetsCompletes = 0;
        $projetsTermines = 0;
        $projetsAFaire = 0;

        if ($studentId) {
            $tpAssignments = DB::table('tp_assignments')
                ->where(function ($q) use ($studentId, $user) {
                    $q->where('student_id', $studentId)
                        ->orWhere('student_id', $user->id);
                })
                ->get();

            $totalTp = $tpAssignments->count();
            $tpValides = $tpAssignments->where('status', 'validated')->count();
            $tpEnAttente = $tpAssignments->whereIn('status', ['assigned', 'submitted', 'pending'])->count();
            $tpRejected = $tpAssignments->where('status', 'rejected')->count();

            $projects = DB::table('projects')
                ->where('user_id', $user->id)
                ->get();

            $totalProjects = $projects->count();
            $projetsEnCours = $projects->whereIn('status', ['en_cours', 'in_progress'])->count();
            $projetsCompletes = $projects->whereIn('status', ['valide', 'completed', 'validated'])->count();
            $projetsTermines = $projects->where('status', 'termine')->count();
            $projetsAFaire = $projetsEnCours;
        }

        try {
            $evenements = DB::table('events')->count();
        } catch (\Exception $e) {
            $evenements = 0;
        }

        $actualitesCount = 0;
        try {
            if (Schema::hasTable('actualites')) {
                $actualitesCount = DB::table('actualites')
                    ->where('status', 'published')
                    ->where(function ($query) {
                        $query->where('visibility', 'public')
                            ->orWhere('visibility', 'all_formations')
                            ->orWhere('visibility', 'like', '%Community Management%');
                    })
                    ->count();
            }
        } catch (\Exception $e) {
            $actualitesCount = 0;
        }

        $progressionGlobale = $totalTp > 0 ? round(($tpValides / $totalTp) * 100) : 0;

        $montantRestant = 0;
        try {
            if ($preReg && isset($preReg->montant_total) && isset($preReg->montant_paye)) {
                $montantRestant = max(0, (float) $preReg->montant_total - (float) $preReg->montant_paye);
            } else {
                $paymentsQuery = DB::table('payments')
                    ->where(function ($q) use ($user, $preReg) {
                        $q->where('user_id', $user->id);
                        if ($preReg && isset($preReg->id)) {
                            $q->orWhere('pre_registration_id', $preReg->id);
                        }
                    });

                $payments = $paymentsQuery->get();
                $formationLabel = $preReg ? (new \App\Http\Controllers\Admin\PreRegistrationAdminController())->getFormationLabel($preReg->choix_formation ?? null) : null;
                $firstPaymentDate = optional($payments->sortBy('created_at')->first())->created_at;
                $pricingDate = $firstPaymentDate ?: ($preReg->created_at ?? null);
                $grossPaymentAmount = $formationLabel ? (float) \App\Services\CinetPayService::getFormationPrice($formationLabel, $pricingDate) : 0;
                $discountAmount = min((float) ($preReg->discount_amount ?? 0), $grossPaymentAmount);
                $expectedAmount = max(0, $grossPaymentAmount - $discountAmount);
                $paymentsTotal = (float) ($payments->max('total_amount') ?? 0);
                $sumAmounts = (float) $payments->sum('amount');
                $paymentAmount = $discountAmount > 0 ? max($expectedAmount, $sumAmounts) : max($paymentsTotal, $expectedAmount, $sumAmounts);
                $paymentPaid = (float) $payments->where('status', 'completed')->sum('amount');
                $montantRestant = max(0, $paymentAmount - $paymentPaid);
            }
        } catch (\Exception $e) {
            $montantRestant = 0;
        }

        return response()->json([
            'stats' => [
                'progression_globale' => $progressionGlobale,
                'tp_realises' => $tpValides,
                'tp_total' => $totalTp,
                'tp_en_attente' => $tpEnAttente,
                'tp_rejetes' => $tpRejected,
                'projets_realises' => $projetsCompletes,
                'projets_total' => $totalProjects,
                'projets_a_faire' => $projetsAFaire,
                'evenements' => $evenements,
                'actualites' => $actualitesCount,
                'montant_restant' => $montantRestant,
            ],
            'generated_at' => now()->toIso8601String(),
        ]);
    }

    /**
     * Dashboard Intelligence Artificielle
     */
    public function intelligenceArtificielle(): View
    {
        $user = Auth::user();

        $stats = [
            'progression_globale' => 45,
            'tp_a_faire' => 12,
            'projets_actifs' => 3,
        ];

        return view('dashboard.intelligence-artificielle', [
            'user' => $user,
            'stats' => $stats,
        ]);
    }

    /**
     * Dashboard Gestion Informatique
     */
    public function gestionInformatique(): View
    {
        $user = Auth::user();

        $stats = [
            'progression_globale' => 50,
            'tp_a_faire' => 10,
            'projets_actifs' => 4,
            'projets_en_cours' => 4,
            'eligible_certificat' => false,
            'formation_semaine' => 'Administration Système',
        ];

        return view('dashboard.gestion-informatique', [
            'user' => $user,
            'stats' => $stats,
        ]);
    }

    /**
     * Prévisualiser le certificat dans le navigateur (Étudiant)
     */
    public function previewCertificateStudent()
    {
        $user = Auth::user();
        $student = DB::table('students')->where('user_id', $user->id)->first();

        $currentModule = request()->segment(3);

        if (!$student) {
            return back()->with('error', 'Profil étudiant non trouvé');
        }

        // Vérifier l'éligibilité
        $tpValidated = DB::table('tp_assignments')
            ->where('student_id', $student->id)
            ->where('status', 'validated')
            ->count();

        $projectsCompleted = DB::table('projects')
            ->where('user_id', $user->id)
            ->where('status', 'valide')
            ->count();

        $report = DB::table('end_of_training_reports')
            ->where('student_id', $student->id)
            ->first();

        // Critères minimums
        $minTPRequired = match (true) {
            $currentModule === 'design-graphique-cm' => 50,
            $currentModule === 'design-graphique' => 35,
            in_array($currentModule, ['community-management', 'community-manager'], true) => 15,
            default => 15,
        };
        $minProjectsRequired = 4;

        $tpEligible = $tpValidated >= $minTPRequired;
        $projectsEligible = $projectsCompleted >= $minProjectsRequired;
        $reportUploaded = $report ? true : false;

        // Vérifier l'éligibilité (sans paiement pour le moment)
        $isEligible = $tpEligible && $projectsEligible && $reportUploaded;

        if (!$isEligible) {
            return back()->with('error', 'Vous ne remplissez pas encore tous les critères d\'éligibilité pour voir votre certificat.');
        }

        // Générer le certificat PDF avec le template personnalisé
        try {
            $certificateGenerator = new \App\Services\CertificateGenerator();

            // Données à insérer dans le certificat
            $data = [
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'formation' => $student->program,
                'date' => now()->format('d/m/Y'),
                'student_id' => $student->student_id,
            ];

            // Générer le certificat selon la formation
            if ($student->program == 'Community Management' || $student->program == 'Social Media Marketing') {
                $certificatePath = $certificateGenerator->generateCommunityManagement($data);
            } else {
                // Pour les autres formations, utiliser le template par défaut
                $certificatePath = $certificateGenerator->generateCommunityManagement($data);
            }

            // Afficher le PDF dans le navigateur (inline)
            return response()->file($certificatePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Certificat_Preview.pdf"'
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Erreur prévisualisation certificat étudiant: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Une erreur est survenue lors de la prévisualisation du certificat.');
        }
    }

    /**
     * Télécharger le certificat de fin de formation
     */
    public function downloadCertificate()
    {
        $user = Auth::user();
        $student = DB::table('students')->where('user_id', $user->id)->first();

        $currentModule = request()->segment(3);

        if (!$student) {
            return back()->with('error', 'Profil étudiant non trouvé');
        }

        // Vérifier l'éligibilité
        $tpValidated = DB::table('tp_assignments')
            ->where('student_id', $student->id)
            ->where('status', 'validated')
            ->count();

        $projectsCompleted = DB::table('projects')
            ->where('user_id', $user->id)
            ->where('status', 'valide')
            ->count();

        $report = DB::table('end_of_training_reports')
            ->where('student_id', $student->id)
            ->first();

        // Critères minimums
        $minTPRequired = match (true) {
            $currentModule === 'design-graphique-cm' => 50,
            $currentModule === 'design-graphique' => 35,
            in_array($currentModule, ['community-management', 'community-manager'], true) => 15,
            default => 15,
        };
        $minProjectsRequired = 4;

        $tpEligible = $tpValidated >= $minTPRequired;
        $projectsEligible = $projectsCompleted >= $minProjectsRequired;
        $reportUploaded = $report ? true : false;

        // Vérifier l'éligibilité (sans paiement pour le moment)
        $isEligible = $tpEligible && $projectsEligible && $reportUploaded;

        if (!$isEligible) {
            return back()->with('error', 'Vous ne remplissez pas encore tous les critères d\'éligibilité pour télécharger votre certificat.');
        }

        // Générer le certificat PDF avec le template personnalisé
        try {
            $certificateGenerator = new \App\Services\CertificateGenerator();

            // Données à insérer dans le certificat
            $data = [
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'formation' => $student->program,
                'date' => now()->format('d/m/Y'),
                'student_id' => $student->student_id,
            ];

            // Générer le certificat selon la formation
            if ($student->program == 'Community Management' || $student->program == 'Social Media Marketing') {
                $certificatePath = $certificateGenerator->generateCommunityManagement($data);
            } else {
                // Pour les autres formations, utiliser le template par défaut
                $certificatePath = $certificateGenerator->generateCommunityManagement($data);
            }

            $filename = 'Certificat_' . str_replace(' ', '_', $student->first_name . '_' . $student->last_name) . '_' . now()->format('Y') . '.pdf';

            // Enregistrer dans la base de données qu'un certificat a été généré (si pas déjà fait)
            $existingCertificate = DB::table('certificates')
                ->where('student_id', $student->id)
                ->first();

            if (!$existingCertificate) {
                DB::table('certificates')->insert([
                    'student_id' => $student->id,
                    'user_id' => $student->user_id,
                    'formation' => $student->program,
                    'generated_by' => null, // Généré par l'étudiant lui-même
                    'generated_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Télécharger le certificat
            return $certificateGenerator->download($certificatePath, $filename);
        } catch (\Exception $e) {
            Log::error('Erreur génération certificat étudiant: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Une erreur est survenue lors de la génération du certificat. Veuillez contacter l\'administration.');
        }
    }
}
