<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use App\Models\Category;
use App\Models\LibraryCategory;
use App\Models\Formation;
use App\Models\Library;
use App\Models\AccountingTransaction;
use App\Models\Donation;
use App\Models\Project;
use App\Services\PaymentReceiptGenerator;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\DesignProject;
use App\Notifications\TpAssignedNotification;
use App\Notifications\TpStatusChangedNotification;
use App\Notifications\DesignProjectStatusChangedNotification;
use App\Notifications\ProjectAssignedNotification;

class AdminDashboardController extends Controller
{
    public function dashboard(): View
    {
        $currentWeekStart = now()->startOfWeek();
        $currentDayStart = now()->startOfDay();
        $currentMonthStart = now()->startOfMonth();

        // Récupérer les historiques de connexion récents
        $recentLogins = DB::table('user_activities')
            ->where('activity_type', 'login')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->unique('user_id')
            ->take(8);

        $studentHistory = [];

        foreach ($recentLogins as $login) {
            $student = DB::table('students')
                ->where('user_id', $login->user_id)
                ->select('first_name', 'last_name', 'profile_photo', 'program')
                ->first();

            // Si c'est un étudiant (et pas un admin par exemple)
            if ($student) {
                // Récupérer la toute dernière activité (peut être déconnexion ou autre)
                $lastActivity = DB::table('user_activities')
                    ->where('user_id', $login->user_id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                $studentHistory[] = (object) [
                    'full_name' => $student->first_name . ' ' . $student->last_name,
                    'profile_photo' => $student->profile_photo,
                    'module' => $student->program,
                    'last_login' => $login->created_at,
                    'last_activity' => $lastActivity ? $lastActivity->created_at : null,
                    'last_activity_type' => $lastActivity ? $lastActivity->activity_type : null,
                ];
            }
        }

        // --- Statistiques Comptables ---
        $totalIncome = AccountingTransaction::where('type', 'income')->sum('amount');
        $totalExpenses = AccountingTransaction::where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpenses;

        $incomeThisMonth = AccountingTransaction::where('type', 'income')
            ->where('date', '>=', $currentMonthStart)
            ->sum('amount');
        $expensesThisMonth = AccountingTransaction::where('type', 'expense')
            ->where('date', '>=', $currentMonthStart)
            ->sum('amount');

        // --- Statistiques Paiements ---
        $totalPayments = DB::table('payments')->sum('amount');
        $completedPayments = DB::table('payments')->where('status', 'completed')->sum('amount');
        $pendingPayments = DB::table('payments')->where('status', 'pending')->sum('amount');
        $paymentsCount = DB::table('payments')->count();
        $completedPaymentsCount = DB::table('payments')->where('status', 'completed')->count();
        $pendingPaymentsCount = DB::table('payments')->where('status', 'pending')->count();

        // --- Statistiques Visiteurs (sessions) ---
        // Approximation: un visiteur = combinaison IP + User-Agent
        $nowTs = time();
        $onlineCutoff = $nowTs - 300; // 5 minutes
        $todayCutoff = $currentDayStart->timestamp;
        $weekCutoff = $currentWeekStart->timestamp;
        $monthCutoff = $currentMonthStart->timestamp;

        $visitorKeyExpr = DB::raw("CONCAT(COALESCE(ip_address,''),'|',COALESCE(user_agent,''))");

        $visitorsOnline = (int) DB::table('sessions')
            ->where('last_activity', '>=', $onlineCutoff)
            ->distinct($visitorKeyExpr)
            ->count($visitorKeyExpr);

        $visitorsToday = (int) DB::table('sessions')
            ->where('last_activity', '>=', $todayCutoff)
            ->distinct($visitorKeyExpr)
            ->count($visitorKeyExpr);

        $visitorsThisWeek = (int) DB::table('sessions')
            ->where('last_activity', '>=', $weekCutoff)
            ->distinct($visitorKeyExpr)
            ->count($visitorKeyExpr);

        $visitorsThisMonth = (int) DB::table('sessions')
            ->where('last_activity', '>=', $monthCutoff)
            ->distinct($visitorKeyExpr)
            ->count($visitorKeyExpr);

        // Paiements ce mois
        $paymentsThisMonth = DB::table('payments')
            ->where('created_at', '>=', $currentMonthStart)
            ->where('status', 'completed')
            ->sum('amount');

        // Paiements cette semaine
        $paymentsThisWeek = DB::table('payments')
            ->where('status', 'completed')
            ->where(function ($q) use ($currentWeekStart) {
                $q->where('paid_at', '>=', $currentWeekStart)
                    ->orWhere(function ($q2) use ($currentWeekStart) {
                        $q2->whereNull('paid_at')->where('created_at', '>=', $currentWeekStart);
                    });
            })
            ->sum('amount');

        // Inscriptions cette semaine
        $registrationsThisWeek = DB::table('students')
            ->where('created_at', '>=', $currentWeekStart)
            ->count();

        // --- Statistiques Dons (formulaire public) ---
        $donationsCount = 0;
        $donationsTotalAmount = 0;
        try {
            if (Schema::hasTable('donations')) {
                $donationsCount = Donation::count();
                $donationsTotalAmount = Donation::whereNotNull('amount')->sum('amount');
            }
        } catch (\Throwable $e) {
            Log::error('Erreur stats dons dashboard: ' . $e->getMessage());
        }

        $assistantSalarySummary = null;
        if (
            session('admin_role') === 'super_admin'
            && Schema::hasTable('admins')
            && Schema::hasTable('admin_task_types')
            && Schema::hasTable('admin_task_logs')
            && Schema::hasTable('admin_salary_months')
        ) {

            $year = (int) now()->year;
            $month = (int) now()->month;
            $monthEnd = now()->endOfMonth();
            $weekStart = $currentWeekStart;
            $dayStart = $currentDayStart;

            $taskTypes = DB::table('admin_task_types')
                ->where('role', 'assistant')
                ->where('is_active', 1)
                ->orderBy('id')
                ->get();

            $totalUnits = (int) $taskTypes->sum(function ($t) {
                return (int) ($t->expected_per_month ?? 0);
            });

            $assistants = DB::table('admins')
                ->where('role', 'assistant')
                ->where('is_active', 1)
                ->orderBy('name')
                ->get(['id', 'name', 'email']);

            $assistantIds = $assistants->pluck('id')->all();

            $salaryMonths = DB::table('admin_salary_months')
                ->whereIn('admin_id', $assistantIds)
                ->where('year', $year)
                ->where('month', $month)
                ->get()
                ->keyBy('admin_id');

            $monthLogs = DB::table('admin_task_logs')
                ->select('admin_id', 'task_type_id', DB::raw('SUM(quantity) as qty'))
                ->whereIn('admin_id', $assistantIds)
                ->whereBetween('performed_at', [$currentMonthStart, $monthEnd])
                ->groupBy('admin_id', 'task_type_id')
                ->get();

            $weekLogs = DB::table('admin_task_logs')
                ->select('admin_id', 'task_type_id', DB::raw('SUM(quantity) as qty'))
                ->whereIn('admin_id', $assistantIds)
                ->whereBetween('performed_at', [$weekStart, $monthEnd])
                ->groupBy('admin_id', 'task_type_id')
                ->get();

            $dayLogs = DB::table('admin_task_logs')
                ->select('admin_id', 'task_type_id', DB::raw('SUM(quantity) as qty'))
                ->whereIn('admin_id', $assistantIds)
                ->whereBetween('performed_at', [$dayStart, $monthEnd])
                ->groupBy('admin_id', 'task_type_id')
                ->get();

            $monthLogsByAdmin = $monthLogs->groupBy('admin_id');
            $weekLogsByAdmin = $weekLogs->groupBy('admin_id');
            $dayLogsByAdmin = $dayLogs->groupBy('admin_id');

            $assistantsComputed = $assistants->map(function ($a) use (
                $salaryMonths,
                $taskTypes,
                $totalUnits,
                $monthLogsByAdmin,
                $weekLogsByAdmin,
                $dayLogsByAdmin,
                $year,
                $month
            ) {
                $salaryMonth = $salaryMonths[$a->id] ?? null;
                $isCompliant = (bool) ($salaryMonth->is_compliant ?? true);
                $baseAmount = (int) ($salaryMonth->base_amount ?? 25000);
                $effectiveBase = $isCompliant ? $baseAmount : (int) floor($baseAmount / 2);
                $unitValue = $totalUnits > 0 ? ($effectiveBase / $totalUnits) : 0;

                $monthDoneByType = collect($monthLogsByAdmin[$a->id] ?? [])->pluck('qty', 'task_type_id');
                $weekDoneByType = collect($weekLogsByAdmin[$a->id] ?? [])->pluck('qty', 'task_type_id');
                $dayDoneByType = collect($dayLogsByAdmin[$a->id] ?? [])->pluck('qty', 'task_type_id');

                $creditedUnitsMonth = 0;
                $creditedUnitsWeek = 0;
                $creditedUnitsDay = 0;

                foreach ($taskTypes as $t) {
                    $expected = (int) ($t->expected_per_month ?? 0);

                    $doneMonth = (int) ($monthDoneByType[$t->id] ?? 0);
                    $doneWeek = (int) ($weekDoneByType[$t->id] ?? 0);
                    $doneDay = (int) ($dayDoneByType[$t->id] ?? 0);

                    $creditedUnitsMonth += min($doneMonth, $expected);
                    $creditedUnitsWeek += min($doneWeek, $expected);
                    $creditedUnitsDay += min($doneDay, $expected);
                }

                $earnedMonth = (int) round($creditedUnitsMonth * $unitValue);
                $earnedWeek = min($earnedMonth, (int) round($creditedUnitsWeek * $unitValue));
                $earnedDay = min($earnedMonth, (int) round($creditedUnitsDay * $unitValue));

                return [
                    'admin_id' => (int) $a->id,
                    'name' => $a->name,
                    'email' => $a->email,
                    'year' => $year,
                    'month' => $month,
                    'is_compliant' => $isCompliant,
                    'base_amount' => $baseAmount,
                    'effective_base' => $effectiveBase,
                    'total_units' => $totalUnits,
                    'credited_units_month' => $creditedUnitsMonth,
                    'earned_day' => $earnedDay,
                    'earned_week' => $earnedWeek,
                    'earned_month' => $earnedMonth,
                ];
            })->values();

            $assistantSalarySummary = [
                'year' => $year,
                'month' => $month,
                'total_units' => $totalUnits,
                'task_types' => $taskTypes,
                'assistants' => $assistantsComputed,
            ];
        }

        return view('admin.dashboard', compact(
            'studentHistory',
            'totalIncome',
            'totalExpenses',
            'balance',
            'incomeThisMonth',
            'expensesThisMonth',
            'totalPayments',
            'completedPayments',
            'pendingPayments',
            'paymentsCount',
            'completedPaymentsCount',
            'pendingPaymentsCount',
            'paymentsThisMonth',
            'paymentsThisWeek',
            'registrationsThisWeek',
            'visitorsOnline',
            'visitorsToday',
            'visitorsThisWeek',
            'visitorsThisMonth',
            'donationsCount',
            'donationsTotalAmount',
            'assistantSalarySummary'
        ));
    }

    public function connexions(): View
    {
        $stats = [
            'total' => 0,
            'last_24h' => 0,
            'last_7d' => 0,
            'this_month' => 0,
        ];

        $stats['total'] = DB::table('user_activities')
            ->where('activity_type', 'login')
            ->distinct('user_id')
            ->count('user_id');

        $stats['last_24h'] = DB::table('user_activities')
            ->where('activity_type', 'login')
            ->where('created_at', '>=', now()->subDay())
            ->distinct('user_id')
            ->count('user_id');

        $stats['last_7d'] = DB::table('user_activities')
            ->where('activity_type', 'login')
            ->where('created_at', '>=', now()->subDays(7))
            ->distinct('user_id')
            ->count('user_id');

        $stats['this_month'] = DB::table('user_activities')
            ->where('activity_type', 'login')
            ->where('created_at', '>=', now()->startOfMonth())
            ->distinct('user_id')
            ->count('user_id');

        $lastLoginsSub = DB::table('user_activities')
            ->select('user_id', DB::raw('MAX(created_at) as last_login'))
            ->where('activity_type', 'login')
            ->groupBy('user_id');

        $lastActivitiesSub = DB::table('user_activities')
            ->select('user_id', DB::raw('MAX(created_at) as last_activity'))
            ->groupBy('user_id');

        $connections = DB::table(DB::raw('(' . $lastLoginsSub->toSql() . ') as ul'))
            ->mergeBindings($lastLoginsSub)
            ->join('students', 'students.user_id', '=', 'ul.user_id')
            ->leftJoin(DB::raw('(' . $lastActivitiesSub->toSql() . ') as ua'), 'ua.user_id', '=', 'ul.user_id')
            ->mergeBindings($lastActivitiesSub)
            ->select(
                'students.user_id',
                'students.first_name',
                'students.last_name',
                'students.profile_photo',
                'students.program',
                'ul.last_login',
                'ua.last_activity'
            )
            ->orderByDesc('ul.last_login')
            ->paginate(20);

        return view('admin.connexions.index', compact('connections', 'stats'));
    }

    public function studioCreative(): View
    {
        $projects = collect();
        $stats = [
            'total' => 0,
            'solo' => 0,
            'groupe' => 0,
            'in_progress' => 0,
            'completed' => 0,
            'pending' => 0,
        ];

        try {
            if (Schema::hasTable('design_projects')) {
                $projects = DesignProject::with(['user', 'user.student', 'files'])
                    ->whereHas('user.student', function ($query) {
                        $query->whereRaw('LOWER(program) LIKE ?', ['%design%graph%'])
                            ->whereRaw('LOWER(program) NOT LIKE ?', ['%community%']);
                    })
                    ->orderByDesc('created_at')
                    ->limit(200)
                    ->get();

                $stats['total'] = $projects->count();
                $stats['solo'] = $projects->filter(function ($p) {
                    $raw = $p->project_mode ?? ($p->category ?? null);
                    return is_string($raw) && strtolower(trim($raw)) === 'solo';
                })->count();
                $stats['groupe'] = $projects->filter(function ($p) {
                    $raw = $p->project_mode ?? ($p->category ?? null);
                    return is_string($raw) && strtolower(trim($raw)) === 'groupe';
                })->count();
                $stats['in_progress'] = $projects->whereIn('status', ['active', 'in_progress'])->count();
                $stats['completed'] = $projects->whereIn('status', ['completed', 'validated'])->count();
                $stats['pending'] = $projects->where('status', 'pending')->count();
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement Studio Creative (admin)', [
                'error' => $e->getMessage(),
            ]);
        }

        return view('admin.studio-creative', [
            'projects' => $projects,
            'stats' => $stats,
        ]);
    }

    public function viewProject(Request $request, $id)
    {
        $project = Project::with(['user', 'user.student', 'images'])->findOrFail($id);

        if ($request->expectsJson() || $request->ajax()) {
            $images = $project->images->map(function ($img) {
                return [
                    'id' => $img->id,
                    'original_name' => $img->original_name,
                    'mime_type' => $img->mime_type,
                    'file_size' => $img->file_size,
                    'url' => \App\Models\MediaUrl::fromPath($img->file_path),
                ];
            })->values();

            return response()->json([
                'success' => true,
                'project' => [
                    'id' => $project->id,
                    'title' => $project->title,
                    'description' => $project->description,
                    'category' => $project->category,
                    'status' => $project->status,
                    'created_at' => optional($project->created_at)->format('d/m/Y H:i'),
                    'software_used' => $project->software_used,
                    'user' => [
                        'id' => $project->user?->id,
                        'name' => $project->user?->name,
                        'email' => $project->user?->email,
                    ],
                    'images' => $images,
                ],
            ]);
        }

        return view('admin.projects.view', [
            'project' => $project,
        ]);
    }

    public function viewDesignProject($id)
    {
        $project = DesignProject::with(['user', 'user.student', 'files'])->findOrFail($id);

        return view('admin.projects.design.show', [
            'project' => $project,
        ]);
    }

    public function validateDesignProject(Request $request, $id)
    {
        $project = DesignProject::findOrFail($id);

        if ((string) ($project->status ?? '') === 'validated') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Projet déjà validé.'
                ]);
            }

            return redirect()->back()->with('success', 'Projet déjà validé.');
        }

        $project->status = 'validated';
        if (Schema::hasColumn('design_projects', 'validated_at')) {
            $project->validated_at = now();
        }
        $project->save();

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
                        ->map(fn($v) => (int) $v)
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
                            'design_project_id' => (int) $project->id,
                            'event' => 'validated',
                        ]),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Unable to create admin task log for project validation', [
                'design_project_id' => $project->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Notification in-app (database)
        try {
            $user = \App\Models\User::find($project->user_id);
            if ($user) {
                // Récupérer la formation de l'étudiant
                $student = DB::table('students')->where('user_id', $user->id)->first();
                $formation = $student && $student->program ? $student->program : 'Design Graphique';
                $formationSlug = $this->getFormationSlug($formation);

                $user->notify(new DesignProjectStatusChangedNotification([
                    'category' => 'project',
                    'event' => 'validated',
                    'title' => 'Projet validé',
                    'message' => 'Votre projet "' . ($project->title ?? 'Projet') . '" a été validé.',
                    'project_id' => $project->id,
                    'project_title' => $project->title ?? null,
                    'created_at' => now()->toIso8601String(),
                    'url' => url('/evc/compte/' . $formationSlug . '/projets/index'),
                ]));
            }
        } catch (\Exception $e) {
            Log::warning('Notification in-app projet validé échouée', [
                'design_project_id' => $id,
                'user_id' => $project->user_id ?? null,
                'error' => $e->getMessage(),
            ]);
        }

        // Email étudiant (ne doit pas bloquer la validation)
        try {
            $user = \App\Models\User::find($project->user_id);
            if ($user && !empty($user->email)) {
                $student = DB::table('students')->where('user_id', $user->id)->first();
                $studentName = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
                $studentName = $studentName !== '' ? $studentName : ($user->name ?? '');
                $projectTitle = $project->title ?? 'Projet';
                $projectType = $project->project_type ?? 'Design';
                $validatedAt = now()->format('d/m/Y H:i');

                Mail::send('emails.design-project-validated', [
                    'studentName' => $studentName,
                    'projectTitle' => $projectTitle,
                    'projectType' => $projectType,
                    'validatedAt' => $validatedAt,
                ], function ($message) use ($user, $projectTitle) {
                    $message->to($user->email)
                        ->subject('PROJET DU STUDIO CREATIVE ACCEPTÉ : ' . $projectTitle);
                });
            }
        } catch (\Throwable $e) {
            Log::warning('Email validation projet Studio Creative non envoyé', [
                'design_project_id' => $id,
                'user_id' => $project->user_id ?? null,
                'error' => $e->getMessage(),
            ]);
        }

        // Synchroniser le statut du projet assigné (todo list étudiant) si on le retrouve.
        // On n'a pas de FK directe, donc on rapproche via user_id + title.
        try {
            DB::table('projects')
                ->where('user_id', $project->user_id)
                ->where('title', $project->title)
                ->where('status', 'termine')
                ->update([
                    'status' => 'valide',
                    'updated_at' => now(),
                ]);
        } catch (\Exception $e) {
            Log::warning('Unable to sync assigned project status after design project validation', [
                'design_project_id' => $project->id,
                'user_id' => $project->user_id,
                'title' => $project->title,
                'error' => $e->getMessage(),
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Projet validé avec succès.'
            ]);
        }

        return redirect()->back()->with('success', 'Projet validé avec succès.');
    }

    public function rejectDesignProject(Request $request, $id)
    {
        $project = DesignProject::findOrFail($id);

        $project->status = 'rejected';
        $project->save();

        // Notification in-app (database)
        try {
            $user = \App\Models\User::find($project->user_id);
            if ($user) {
                // Récupérer la formation de l'étudiant
                $student = DB::table('students')->where('user_id', $user->id)->first();
                $formation = $student && $student->program ? $student->program : 'Design Graphique';
                $formationSlug = $this->getFormationSlug($formation);

                $user->notify(new DesignProjectStatusChangedNotification([
                    'category' => 'project',
                    'event' => 'rejected',
                    'title' => 'Projet rejeté',
                    'message' => 'Votre projet "' . ($project->title ?? 'Projet') . '" a été rejeté.',
                    'project_id' => $project->id,
                    'project_title' => $project->title ?? null,
                    'created_at' => now()->toIso8601String(),
                    'url' => url('/evc/compte/' . $formationSlug . '/projets/index'),
                ]));
            }
        } catch (\Exception $e) {
            Log::warning('Notification in-app projet rejeté échouée', [
                'design_project_id' => $id,
                'user_id' => $project->user_id ?? null,
                'error' => $e->getMessage(),
            ]);
        }

        // Synchroniser le statut du projet assigné (todo list étudiant) si on le retrouve.
        try {
            DB::table('projects')
                ->where('user_id', $project->user_id)
                ->where('title', $project->title)
                ->whereIn('status', ['termine', 'valide'])
                ->update([
                    'status' => 'rejete',
                    'updated_at' => now(),
                ]);
        } catch (\Exception $e) {
            Log::warning('Unable to sync assigned project status after design project rejection', [
                'design_project_id' => $project->id,
                'user_id' => $project->user_id,
                'title' => $project->title,
                'error' => $e->getMessage(),
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Projet rejeté avec succès.'
            ]);
        }

        return redirect()->back()->with('success', 'Projet rejeté avec succès.');
    }

    public function editDesignProject(Request $request, $id)
    {
        $project = DesignProject::with(['user', 'user.student', 'files'])->findOrFail($id);

        if ($request->isMethod('get')) {
            return view('admin.projects.design.edit', [
                'project' => $project,
            ]);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_type' => 'required|string|max:50',
            'project_mode' => 'required|string|max:50',
            'status' => 'required|string|max:50',
        ]);

        if (!empty($bulkProjectIds)) {
            Project::query()->whereIn('id', $bulkProjectIds)->update($validated);
        } else {
            $project->fill($validated);
            $project->save();
        }

        return redirect()->route('admin.design-projects.view', $project->id)
            ->with('success', 'Projet mis à jour avec succès.');
    }

    public function deleteDesignProject(Request $request, $id)
    {
        $project = DesignProject::with(['files'])->findOrFail($id);

        // Supprimer aussi les fichiers physiques si possible
        foreach ($project->files as $file) {
            try {
                if (!empty($file->file_path) && Storage::disk('public')->exists($file->file_path)) {
                    Storage::disk('public')->delete($file->file_path);
                }
            } catch (\Exception $e) {
                Log::warning('Unable to delete design project file from storage', [
                    'design_project_id' => $project->id,
                    'file_id' => $file->id,
                    'file_path' => $file->file_path ?? null,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $project->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Projet supprimé avec succès.'
            ]);
        }

        return redirect()->route('admin.design-projects.index')->with('success', 'Projet supprimé avec succès.');
    }

    
    private function relatedProjectsQueryFor(Project $project): \Illuminate\Database\Eloquent\Builder
    {
        $relatedProjectsQuery = Project::query()
            ->where('title', $project->title)
            ->where('category', $project->category);

        if (!is_null($project->deadline)) {
            $relatedProjectsQuery->whereDate('deadline', $project->deadline);
        } else {
            $relatedProjectsQuery->whereNull('deadline');
        }

        if (!is_null($project->created_at)) {
            $relatedProjectsQuery->whereBetween('created_at', [
                $project->created_at->copy()->subMinutes(10),
                $project->created_at->copy()->addMinutes(10),
            ]);
        }

        return $relatedProjectsQuery;
    }

    public function editProject($id)
    {
        $project = Project::with(['user', 'user.student', 'images'])->findOrFail($id);

        $relatedProjectsQuery = Project::query()
            ->with(['user', 'user.student'])
            ->where('title', $project->title)
            ->where('category', $project->category);

        if (!is_null($project->deadline)) {
            $relatedProjectsQuery->whereDate('deadline', $project->deadline);
        } else {
            $relatedProjectsQuery->whereNull('deadline');
        }

        if (!is_null($project->created_at)) {
            $relatedProjectsQuery->whereBetween('created_at', [
                $project->created_at->copy()->subMinutes(10),
                $project->created_at->copy()->addMinutes(10),
            ]);
        }

        $relatedProjects = $relatedProjectsQuery
            ->orderBy('created_at', 'asc')
            ->get();

        $alreadyAssignedUserIds = $relatedProjects
            ->pluck('user_id')
            ->filter()
            ->unique()
            ->values();

        $studentsList = User::query()
            ->leftJoin('students', 'students.user_id', '=', 'users.id')
            ->where('students.status', 'active')
            ->when($alreadyAssignedUserIds->isNotEmpty(), function ($query) use ($alreadyAssignedUserIds) {
                $query->whereNotIn('users.id', $alreadyAssignedUserIds->all());
            })
            ->select('users.*')
            ->with('student')
            ->orderByRaw("LOWER(COALESCE(NULLIF(TRIM(students.last_name), ''), NULLIF(TRIM(users.name), ''), users.email)) asc")
            ->orderByRaw("LOWER(COALESCE(NULLIF(TRIM(students.first_name), ''), users.email)) asc")
            ->get();

        return view('admin.projects.edit', [
            'project' => $project,
            'relatedProjects' => $relatedProjects,
            'studentsList' => $studentsList,
        ]);
    }

    public function updateProject(Request $request, $id)
    {
        $project = Project::with(['user', 'user.student'])->findOrFail($id);

        $isBulk = (bool) $request->boolean('bulk');
        $bulkProjectIds = [];
        if ($isBulk) {
            $bulkProjectIds = $this->relatedProjectsQueryFor($project)
                ->pluck('id')
                ->map(fn ($v) => (int) $v)
                ->filter()
                ->values()
                ->all();
        }


        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'software_used' => 'nullable|string',
            'status' => 'required|in:en_cours,termine,valide,rejete',
            'link' => 'nullable|url',
            'deadline' => 'nullable|date',
        ]);

        if (array_key_exists('software_used', $validated)) {
            $validated['software_used'] = $validated['software_used']
                ? array_map('trim', explode(',', $validated['software_used']))
                : [];
        }

        if (!empty($bulkProjectIds)) {
            Project::query()->whereIn('id', $bulkProjectIds)->update($validated);
        } else {
            $project->fill($validated);
            $project->save();
        }

        return redirect()->route('admin.projets.design-graphique.assigned')
            ->with('success', 'Projet mis à jour avec succès.');
    }

    public function deleteProject($id)
    {
        $project = Project::findOrFail($id);

        $isBulk = (bool) request()->boolean('bulk');
        if ($isBulk) {
            $ids = $this->relatedProjectsQueryFor($project)
                ->pluck('id')
                ->map(fn ($v) => (int) $v)
                ->filter()
                ->values()
                ->all();

            if (!empty($ids)) {
                Project::query()->whereIn('id', $ids)->delete();

                return redirect()->route('admin.projets.design-graphique.assigned')
                    ->with('success', 'Projets supprimés avec succès.');
            }
        }

        $project->delete();

        return redirect()->route('admin.projets.design-graphique.assigned')
            ->with('success', 'Projet supprimé avec succès.');
    }

    public function addStudentToProject(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'student_user_id' => 'required|exists:users,id',
        ]);

        $targetUserId = (int) $validated['student_user_id'];

        $dupQuery = Project::query()
            ->where('user_id', $targetUserId)
            ->where('title', $project->title)
            ->where('category', $project->category);

        if (!is_null($project->deadline)) {
            $dupQuery->whereDate('deadline', $project->deadline);
        } else {
            $dupQuery->whereNull('deadline');
        }

        if ($dupQuery->exists()) {
            $existing = (clone $dupQuery)->first();
            if ($existing) {
                $existing->status = 'en_cours';
                if (Schema::hasColumn('projects', 'thumbnail_image')) {
                    $existing->thumbnail_image = null;
                }
                $existing->updated_at = now();
                $existing->save();

                return redirect()->route('admin.projects.edit', $project->id)
                    ->with('success', "Projet réinitialisé pour cet étudiant.");
            }

            return redirect()->back()->with('error', "Cet étudiant a déjà ce projet.");
        }

        $newProject = $project->replicate([
            'created_at',
            'updated_at',
        ]);
        $newProject->user_id = $targetUserId;
        $newProject->status = 'en_cours';
        if (Schema::hasColumn('projects', 'thumbnail_image')) {
            $newProject->thumbnail_image = null;
        }
        $newProject->created_at = now();
        $newProject->updated_at = now();
        $newProject->save();

        // Notification in-app (database)
        try {
            $user = \App\Models\User::find($targetUserId);
            if ($user) {
                $formationSlug = 'design-graphique';
                $studentProgram = $user->student->program ?? null;
                if (is_string($studentProgram) && trim($studentProgram) !== '') {
                    $prog = strtolower((string) $studentProgram);
                    if (str_contains($prog, 'community')) {
                        $formationSlug = 'community-management';
                    } elseif (str_contains($prog, 'informatique')) {
                        $formationSlug = 'gestion-informatique';
                    } elseif (str_contains($prog, 'intelligence')) {
                        $formationSlug = 'intelligence-artificielle';
                    }
                }

                $user->notify(new ProjectAssignedNotification([
                    'category' => 'project',
                    'event' => 'assigned',
                    'title' => 'Nouveau projet assigné',
                    'message' => 'Un nouveau projet a été assigné : ' . ($newProject->title ?? 'Projet'),
                    'project_id' => $newProject->id,
                    'project_title' => $newProject->title ?? null,
                    'created_at' => now()->toIso8601String(),
                    'url' => url("/evc/compte/{$formationSlug}/projets"),
                ]));
            }
        } catch (\Exception $e) {
            Log::warning('Notification in-app projet assigné échouée (addStudentToProject)', [
                'project_id' => $newProject->id ?? null,
                'user_id' => $targetUserId,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()->route('admin.projects.edit', $project->id)
            ->with('success', "Étudiant ajouté au projet.");
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $students = User::orderBy('name')->get();

        $userIds = $students
            ->pluck('id')
            ->filter(fn($id) => !empty($id))
            ->unique()
            ->values();

        $userIdsWithProjects = DB::table('projects')
            ->whereIn('user_id', $userIds)
            ->distinct()
            ->pluck('user_id');

        $userIdsWithProjects = $userIdsWithProjects
            ->map(fn($id) => (int) $id)
            ->flip();

        $students->transform(function ($student) use ($userIdsWithProjects) {
            $studentId = (int) ($student->id ?? 0);
            $student->has_projects = $studentId > 0 && $userIdsWithProjects->has($studentId);
            return $student;
        });

        return view('admin.formations.create', compact('categories', 'students'));
    }

    /**
     * Récupérer les étudiants d'un module spécifique
     */
    public function getStudentsByModule(Request $request)
    {
        $modules = $request->input('modules');
        $module = $request->input('module');

        $modulesToUse = [];
        if (is_array($modules)) {
            $modulesToUse = array_values(array_filter($modules, function ($m) {
                return is_string($m) && trim($m) !== '';
            }));
        } elseif (is_string($module) && trim($module) !== '') {
            $modulesToUse = [trim($module)];
        }

        if (empty($modulesToUse)) {
            return response()->json([
                'success' => false,
                'message' => 'Module non spécifié'
            ], 400);
        }

        try {
            $moduleMapping = [
                'design_graphique' => ['Design Graphique', 'design_graphique'],
                'community_management' => ['Community Management', 'community_management'],
                'intelligence_artificielle' => ['Intelligence Artificielle', 'intelligence_artificielle'],
                'gestion_informatique' => ['Gestion Informatique', 'gestion_informatique'],
                'design_graphique_community_manager' => ['Design Graphique & Community Manager', 'design_graphique_community_manager'],
            ];

            $allVariants = [];
            foreach ($modulesToUse as $m) {
                $moduleNormalized = str_replace('-', '_', $m);
                $variants = $moduleMapping[$moduleNormalized] ?? [$moduleNormalized];
                $allVariants = array_merge($allVariants, $variants);
            }

            $allVariants = array_values(array_unique(array_filter($allVariants, function ($v) {
                return is_string($v) && trim($v) !== '';
            })));

            // Récupérer les étudiants du module depuis la table students
            $students = DB::table('students')
                ->join('users', 'students.user_id', '=', 'users.id')
                ->where(function ($query) use ($allVariants) {
                    foreach ($allVariants as $variant) {
                        $query->orWhere('students.program', $variant)
                            ->orWhere('students.specialization', $variant);
                    }
                })
                ->select(
                    'users.id',
                    DB::raw("CONCAT(students.first_name, ' ', students.last_name) as name"),
                    'users.email',
                    'students.program',
                    'students.specialization'
                )
                ->orderBy('students.last_name')
                ->orderBy('students.first_name')
                ->get();

            $userIds = $students
                ->pluck('id')
                ->filter(fn($id) => !empty($id))
                ->unique()
                ->values();

            $userIdsWithProjects = DB::table('projects')
                ->whereIn('user_id', $userIds)
                ->distinct()
                ->pluck('user_id');

            $userIdsWithProjects = $userIdsWithProjects
                ->map(fn($id) => (int) $id)
                ->flip();

            $students = $students->map(function ($student) use ($userIdsWithProjects) {
                $uid = (int) ($student->id ?? 0);
                $student->has_projects = $uid > 0 && $userIdsWithProjects->has($uid);
                return $student;
            });

            $studentsWithoutProjects = $students
                ->filter(fn($s) => empty($s->has_projects))
                ->values();

            $studentsWithProjects = $students
                ->filter(fn($s) => !empty($s->has_projects))
                ->values();

            return response()->json([
                'success' => true,
                'students' => $students,
                'students_without_projects' => $studentsWithoutProjects,
                'students_with_projects' => $studentsWithProjects,
                'count' => $students->count(),
                'count_without_projects' => $studentsWithoutProjects->count(),
                'count_with_projects' => $studentsWithProjects->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des étudiants: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des étudiants'
            ], 500);
        }
    }

    public function index(): View
    {
        try {
            $formations = \App\Models\Formation::with('category')->latest()->get();

            // Calculer les statistiques globales
            $stats = [
                'total' => $formations->count(),
                'active' => $formations->where('status', 'active')->count(),
                'draft' => $formations->where('status', 'draft')->count(),
                'archived' => $formations->where('status', 'archived')->count(),
                'ce_mois' => $formations->where('created_at', '>=', now()->startOfMonth())->count(),
            ];

            // Calculer les statistiques par module principal de la formation
            $statsByModule = [
                'design-graphique' => 0,
                'community-management' => 0,
                'gestion-informatique' => 0,
                'intelligence-artificielle' => 0,
            ];

            foreach ($formations as $formation) {
                // Utiliser le module principal de la formation si disponible
                $module = $formation->module ?? $formation->category->module ?? null;

                if ($module) {
                    $moduleKey = strtolower(trim($module));

                    // Correspondance exacte ou partielle
                    if ($moduleKey === 'design-graphique' || str_contains($moduleKey, 'design')) {
                        $statsByModule['design-graphique']++;
                    } elseif ($moduleKey === 'community-management' || str_contains($moduleKey, 'community')) {
                        $statsByModule['community-management']++;
                    } elseif ($moduleKey === 'gestion-informatique' || str_contains($moduleKey, 'informatique') || str_contains($moduleKey, 'gestion')) {
                        $statsByModule['gestion-informatique']++;
                    } elseif ($moduleKey === 'intelligence-artificielle' || str_contains($moduleKey, 'intelligence') || str_contains($moduleKey, 'ia')) {
                        $statsByModule['intelligence-artificielle']++;
                    }
                }
            }

            // Calculer les statistiques par catégorie et module
            $statsByCategory = $formations->groupBy(function ($formation) {
                return $formation->category->module ?? 'Autre';
            })->map(function ($moduleFormations, $module) {
                return $moduleFormations->groupBy(function ($formation) {
                    return $formation->category->name ?? 'Sans catégorie';
                })->map(function ($categoryFormations, $categoryName) {
                    return (object)[
                        'category_name' => $categoryName,
                        'total' => $categoryFormations->count(),
                        'formations' => $categoryFormations
                    ];
                })->values();
            });

            return view('admin.formations.index', compact('formations', 'stats', 'statsByModule', 'statsByCategory'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des formations: ' . $e->getMessage());
            return view('admin.formations.index', [
                'formations' => collect(),
                'stats' => [
                    'total' => 0,
                    'active' => 0,
                    'draft' => 0,
                    'archived' => 0,
                    'ce_mois' => 0,
                ],
                'statsByModule' => [
                    'design-graphique' => 0,
                    'community-management' => 0,
                    'gestion-informatique' => 0,
                    'intelligence-artificielle' => 0,
                ],
                'statsByCategory' => collect()
            ])->with('error', 'Impossible de charger la liste des formations.');
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'vimeo_code' => 'nullable|string',
            'modules' => 'required|array|min:1',
            'modules.*' => 'required|string',
            'type' => 'required|in:en_ligne,presentiel',
            'destinataire' => 'required|in:etudiants-actifs,etudiants-specifiques',
            'is_featured' => 'required|boolean',
            'action' => 'required|in:draft,pending,published',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'nullable|integer|exists:users,id',
            'pdf_files' => 'nullable|array',
            'pdf_files.*' => 'file|mimes:pdf|max:10240', // 10 Mo max par fichier
            'chapters' => 'nullable|array',
            'chapters.*.title' => 'required|string|max:255',
            'chapters.*.description' => 'nullable|string',
            'chapters.*.order' => 'required|integer|min:1',
            'chapters.*.duration' => 'nullable|integer|min:1',
            'chapters.*.video_url' => 'nullable|string',
        ]);

        try {
            $formation = new Formation();

            $formation->name = $validatedData['name'];
            $formation->slug = Str::slug($validatedData['name']);
            $formation->category_id = $validatedData['category_id'];
            $formation->description = $validatedData['description'];
            $formation->is_featured = $validatedData['is_featured'];

            $statusMap = [
                'draft' => 'draft',
                'pending' => 'inactive',
                'published' => 'active',
            ];
            $formation->status = $statusMap[$validatedData['action']];

            $formation->modules = array_values(array_unique(array_filter($validatedData['modules'], function ($m) {
                return is_string($m) && trim($m) !== '';
            })));

            $formation->format = ($validatedData['type'] === 'en_ligne') ? 'online' : 'offline';
            $formation->student_restriction = ($validatedData['destinataire'] === 'etudiants-actifs') ? 'active_only' : 'all';

            if ($request->filled('vimeo_code')) {
                $formation->vimeo_code = $validatedData['vimeo_code'];
            }

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('formations', 'public');
                $formation->image_url = $path;
            }

            $formation->save();

            if ($request->filled('student_ids')) {
                // Filtrer les valeurs vides et invalides avant la synchronisation
                $studentIds = array_filter($validatedData['student_ids'], function ($id) {
                    return !empty($id) && is_numeric($id);
                });
                $formation->students()->sync($studentIds);
            }

            // Gérer les chapitres
            if ($request->filled('chapters')) {
                foreach ($request->input('chapters') as $chapterData) {
                    \App\Models\FormationChapter::create([
                        'formation_id' => $formation->id,
                        'title' => $chapterData['title'],
                        'description' => $chapterData['description'] ?? null,
                        'order' => $chapterData['order'],
                        'duration' => $chapterData['duration'] ?? null,
                        'video_url' => $chapterData['video_url'] ?? null,
                    ]);
                }
            }

            // Gérer l'upload des fichiers PDF
            if ($request->hasFile('pdf_files')) {
                $uploadedFiles = [];
                foreach ($request->file('pdf_files') as $file) {
                    if ($file->isValid()) {
                        // Générer un nom unique pour le fichier
                        $originalName = $file->getClientOriginalName();
                        $storedName = time() . '_' . Str::random(10) . '.pdf';

                        // Stocker le fichier dans public/uploads/formations/pdf
                        $path = $file->storeAs('uploads/formations/pdf', $storedName, 'public');

                        // Enregistrer les informations du fichier dans la base de données
                        $formationFile = new \App\Models\FormationFile();
                        $formationFile->formation_id = $formation->id;
                        $formationFile->original_name = $originalName;
                        $formationFile->stored_name = $storedName;
                        $formationFile->file_path = 'storage/' . $path;
                        $formationFile->file_size = $file->getSize();
                        $formationFile->mime_type = $file->getMimeType();
                        $formationFile->file_type = 'pdf';
                        $formationFile->save();

                        $uploadedFiles[] = $originalName;
                    }
                }
            }

            // Envoyer un email aux étudiants ciblés + à l'admin (ne doit pas bloquer la création)
            $emailsSent = 0;
            $emailsFailures = [];
            $adminEmailSent = false;
            $adminEmailFailure = false;

            try {
                $modulesToUse = array_values(array_unique(array_filter($validatedData['modules'] ?? [], function ($m) {
                    return is_string($m) && trim($m) !== '';
                })));

                $moduleMapping = [
                    'design_graphique' => ['Design Graphique', 'design_graphique'],
                    'community_management' => ['Community Management', 'community_management'],
                    'intelligence_artificielle' => ['Intelligence Artificielle', 'intelligence_artificielle'],
                    'gestion_informatique' => ['Gestion Informatique', 'gestion_informatique'],
                    'design_graphique_community_manager' => ['Design Graphique & Community Manager', 'design_graphique_community_manager'],
                ];

                $allVariants = [];
                foreach ($modulesToUse as $m) {
                    $moduleNormalized = str_replace('-', '_', $m);
                    $variants = $moduleMapping[$moduleNormalized] ?? [$moduleNormalized];
                    $allVariants = array_merge($allVariants, $variants);
                }

                $allVariants = array_values(array_unique(array_filter($allVariants, function ($v) {
                    return is_string($v) && trim($v) !== '';
                })));

                $studentsQuery = DB::table('students')
                    ->join('users', 'students.user_id', '=', 'users.id')
                    ->where(function ($query) use ($allVariants) {
                        foreach ($allVariants as $variant) {
                            $query->orWhere('students.program', $variant)
                                ->orWhere('students.specialization', $variant);
                        }
                    })
                    ->select(
                        'users.id as user_id',
                        'users.name as user_name',
                        'users.email as email',
                        'students.first_name as first_name',
                        'students.last_name as last_name',
                        'students.program as program'
                    );

                if (($validatedData['destinataire'] ?? null) === 'etudiants-actifs') {
                    $studentsQuery->where('students.status', 'active');
                } elseif (($validatedData['destinataire'] ?? null) === 'etudiants-specifiques') {
                    $selectedUserIds = array_values(array_unique(array_filter($validatedData['student_ids'] ?? [], function ($id) {
                        return !empty($id) && is_numeric($id);
                    })));
                    if (!empty($selectedUserIds)) {
                        $studentsQuery->whereIn('users.id', $selectedUserIds);
                    } else {
                        $studentsQuery->whereRaw('1 = 0');
                    }
                }

                $studentsToNotify = $studentsQuery->get();

                $firstStudent = $studentsToNotify->first();
                $formationSlug = $this->getFormationSlug(($firstStudent->program ?? null) ?: ($allVariants[0] ?? ''));
                $formationsUrl = url('/evc/compte/' . $formationSlug . '/formations/index');
                $categoryName = null;
                try {
                    $categoryName = Category::where('id', $formation->category_id)->value('name');
                } catch (\Throwable $e) {
                    $categoryName = null;
                }

                foreach ($studentsToNotify as $student) {
                    if (empty($student->email) || !filter_var($student->email, FILTER_VALIDATE_EMAIL)) {
                        continue;
                    }

                    $recipientName = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
                    if ($recipientName === '') {
                        $recipientName = trim((string) ($student->user_name ?? ''));
                    }
                    $recipientName = $recipientName !== '' ? $recipientName : 'Cher(e) étudiant(e)';

                    try {
                        Mail::send('emails.formation_available', [
                            'recipientName' => $recipientName,
                            'formationName' => $formation->name,
                            'categoryName' => $categoryName,
                            'formationsUrl' => $formationsUrl,
                        ], function ($message) use ($student, $formation) {
                            $message->to($student->email)
                                ->subject('Nouvelle formation disponible : ' . $formation->name);
                        });
                        $emailsSent++;
                    } catch (\Throwable $e) {
                        $emailsFailures[] = $student->email;
                        Log::warning('Email formation non envoyé à un étudiant', [
                            'email' => $student->email,
                            'formation_id' => $formation->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }

                $adminEmail = env('MAIL_ADMIN_ADDRESS') ?: env('MAIL_FROM_ADDRESS');
                if (!empty($adminEmail) && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                    try {
                        Mail::send('emails.formation_available', [
                            'recipientName' => 'Admin',
                            'formationName' => $formation->name,
                            'categoryName' => $categoryName,
                            'formationsUrl' => $formationsUrl,
                        ], function ($message) use ($adminEmail, $formation) {
                            $message->to($adminEmail)
                                ->subject('[ADMIN] Nouvelle formation créée : ' . $formation->name);
                        });
                        $adminEmailSent = true;
                    } catch (\Throwable $e) {
                        $adminEmailFailure = true;
                        Log::warning('Email formation non envoyé à l\'admin', [
                            'email' => $adminEmail,
                            'formation_id' => $formation->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Erreur préparation/envoi emails formation', [
                    'formation_id' => $formation->id ?? null,
                    'error' => $e->getMessage(),
                ]);
            }

            // Message de succès final (inclut PDFs + statut emails)
            $successMessage = 'Formation créée avec succès';
            $fileCount = 0;
            if (isset($uploadedFiles) && is_array($uploadedFiles)) {
                $fileCount = count($uploadedFiles);
                if ($fileCount > 0) {
                    $successMessage .= ' avec ' . $fileCount . ' fichier(s) PDF joint(s).';
                }
            }

            if ($emailsSent > 0) {
                $successMessage .= ' ' . $emailsSent . ' email(s) envoyé(s) aux étudiants.';
            }
            if (!empty($emailsFailures)) {
                $successMessage .= ' Attention : ' . count($emailsFailures) . ' email(s) étudiant(s) non envoyé(s).';
            }
            if ($adminEmailSent) {
                $successMessage .= ' Email admin envoyé.';
            } elseif ($adminEmailFailure) {
                $successMessage .= ' Attention : email admin non envoyé.';
            }

            return redirect()->route('admin.formations.index')->with('success', $successMessage);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la formation: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la création de la formation: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Formation $formation)
    {
        try {
            $formation->delete();
            return redirect()->route('admin.formations.index')->with('success', 'Formation supprimée avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la formation: ' . $e->getMessage());
            return redirect()->route('admin.formations.index')->with('error', 'Une erreur est survenue lors de la suppression.');
        }
    }

    public function edit(Formation $formation)
    {
        $categories = Category::orderBy('name')->get();

        // Récupérer les vrais étudiants depuis la table students avec leurs informations utilisateur
        $students = DB::table('students')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'students.first_name',
                'students.last_name',
                'students.program',
                'students.specialization'
            )
            ->orderBy('students.last_name')
            ->orderBy('students.first_name')
            ->get()
            ->map(function ($student) {
                // Créer un nom complet formaté
                $student->name = $student->first_name . ' ' . $student->last_name;
                return $student;
            });

        $chapters = \App\Models\FormationChapter::where('formation_id', $formation->id)->orderBy('order')->get();
        return view('admin.formations.edit', compact('formation', 'categories', 'students', 'chapters'));
    }

    public function update(Request $request, Formation $formation)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'vimeo_code' => 'nullable|string',
            'modules' => 'required|array|min:1',
            'modules.*' => 'required|string',
            'type' => 'required|in:en_ligne,presentiel',
            'destinataire' => 'required|in:etudiants-actifs,etudiants-specifiques',
            'is_featured' => 'required|boolean',
            'action' => 'required|in:draft,pending,published',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'nullable|integer|exists:users,id',
            'chapters' => 'nullable|array',
            'chapters.*.id' => 'nullable|integer|exists:formation_chapters,id',
            'chapters.*.title' => 'required|string|max:255',
            'chapters.*.description' => 'nullable|string',
            'chapters.*.order' => 'required|integer|min:1',
            'chapters.*.duration' => 'nullable|integer|min:1',
            'chapters.*.video_url' => 'nullable|string',
        ]);

        try {
            $formation->name = $validatedData['name'];
            $formation->slug = Str::slug($validatedData['name']);
            $formation->category_id = $validatedData['category_id'];
            $formation->description = $validatedData['description'];
            $formation->is_featured = $validatedData['is_featured'];

            $statusMap = [
                'draft' => 'draft',
                'pending' => 'inactive',
                'published' => 'active',
            ];
            $formation->status = $statusMap[$validatedData['action']];

            $formation->modules = array_values(array_unique(array_filter($validatedData['modules'], function ($m) {
                return is_string($m) && trim($m) !== '';
            })));

            $formation->format = ($validatedData['type'] === 'en_ligne') ? 'online' : 'offline';
            $formation->student_restriction = ($validatedData['destinataire'] === 'etudiants-actifs') ? 'active_only' : 'all';

            if ($request->filled('vimeo_code')) {
                $formation->vimeo_code = $validatedData['vimeo_code'];
            }

            if ($request->hasFile('image')) {
                if ($formation->image_url) {
                    Storage::disk('public')->delete($formation->image_url);
                }
                $path = $request->file('image')->store('formations', 'public');
                $formation->image_url = $path;
            }

            $formation->save();

            if ($request->filled('student_ids')) {
                // Filtrer les valeurs vides et invalides avant la synchronisation
                $studentIds = array_filter($validatedData['student_ids'], function ($id) {
                    return !empty($id) && is_numeric($id);
                });
                $formation->students()->sync($studentIds);
            } else {
                $formation->students()->detach();
            }

            // Gérer les chapitres
            if ($request->filled('chapters')) {
                $submittedChapterIds = [];

                foreach ($request->input('chapters') as $chapterData) {
                    if (!empty($chapterData['id'])) {
                        // Mise à jour d'un chapitre existant
                        $chapter = \App\Models\FormationChapter::find($chapterData['id']);
                        if ($chapter && $chapter->formation_id == $formation->id) {
                            $chapter->update([
                                'title' => $chapterData['title'],
                                'description' => $chapterData['description'] ?? null,
                                'order' => $chapterData['order'],
                                'duration' => $chapterData['duration'] ?? null,
                                'video_url' => $chapterData['video_url'] ?? null,
                            ]);
                            $submittedChapterIds[] = $chapter->id;
                        }
                    } else {
                        // Création d'un nouveau chapitre
                        $newChapter = \App\Models\FormationChapter::create([
                            'formation_id' => $formation->id,
                            'title' => $chapterData['title'],
                            'description' => $chapterData['description'] ?? null,
                            'order' => $chapterData['order'],
                            'duration' => $chapterData['duration'] ?? null,
                            'video_url' => $chapterData['video_url'] ?? null,
                        ]);
                        $submittedChapterIds[] = $newChapter->id;
                    }
                }

                // Supprimer les chapitres qui ne sont plus dans le formulaire
                \App\Models\FormationChapter::where('formation_id', $formation->id)
                    ->whereNotIn('id', $submittedChapterIds)
                    ->delete();
            } else {
                // Si aucun chapitre n'est soumis, supprimer tous les chapitres existants
                \App\Models\FormationChapter::where('formation_id', $formation->id)->delete();
            }

            return redirect()->route('admin.formations.index')->with('success', 'Formation mise à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la formation: ' . $e->getMessage());
            return back()->with('error', 'Erreur de mise à jour: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Formation $formation)
    {
        // Récupérer les fichiers PDF associés à cette formation
        $files = \App\Models\FormationFile::where('formation_id', $formation->id)->get();

        return view('admin.formations.show', compact('formation', 'files'));
    }

    public function toggleStatus(Formation $formation)
    {
        try {
            $formation->status = ($formation->status === 'active') ? 'inactive' : 'active';
            $formation->save();
            return redirect()->route('admin.formations.index')->with('success', 'Statut de la formation mis à jour.');
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut: ' . $e->getMessage());
            return redirect()->route('admin.formations.index')->with('error', 'Une erreur est survenue.');
        }
    }

    public function categoriesIndex()
    {
        try {
            // Vérifier si la table categories existe
            if (!Schema::hasTable('categories')) {
                // Utiliser des données de fallback
                return $this->categoriesIndexFallback();
            }

            // Charger les catégories avec leurs statistiques
            $categoriesData = Category::withCount('formations')->get();

            $categories = $categoriesData->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'module' => $category->module,
                    'status' => $category->status ?? 'active',
                    'formations_count' => $category->formations_count ?? 0,
                    'students_count' => 0, // À calculer si nécessaire
                    'created_at' => $category->created_at,
                ];
            })->toArray();

            // Calculer les statistiques globales
            $stats = [
                'total_categories' => $categoriesData->count(),
                'total_formations' => $categoriesData->sum('formations_count'),
                'categories_actives' => $categoriesData->where('status', 'active')->count(),
                'categories_sans_formation' => $categoriesData->where('formations_count', 0)->count(),
            ];

            // Calculer les statistiques par module
            $statsByModule = [
                'design-graphique' => 0,
                'community-management' => 0,
                'gestion-informatique' => 0,
                'intelligence-artificielle' => 0,
            ];

            foreach ($categoriesData as $category) {
                if ($category->module) {
                    $module = strtolower(trim($category->module));

                    if ($module === 'design-graphique' || str_contains($module, 'design')) {
                        $statsByModule['design-graphique'] += $category->formations_count;
                    } elseif ($module === 'community-management' || str_contains($module, 'community')) {
                        $statsByModule['community-management'] += $category->formations_count;
                    } elseif ($module === 'gestion-informatique' || str_contains($module, 'informatique') || str_contains($module, 'gestion')) {
                        $statsByModule['gestion-informatique'] += $category->formations_count;
                    } elseif ($module === 'intelligence-artificielle' || str_contains($module, 'intelligence') || str_contains($module, 'ia')) {
                        $statsByModule['intelligence-artificielle'] += $category->formations_count;
                    }
                }
            }

            return view('admin.formations.categories', compact('categories', 'stats', 'statsByModule'));
        } catch (\Exception $e) {
            Log::error('Erreur dans categoriesIndex: ' . $e->getMessage());
            return $this->categoriesIndexFallback();
        }
    }

    /**
     * Version fallback avec données de démonstration
     */
    private function categoriesIndexFallback()
    {
        $categories = [
            [
                'id' => 1,
                'name' => 'Design & Création Visuelle',
                'slug' => 'design-creation-visuelle',
                'description' => 'Catégories liées au design graphique, création visuelle et identité de marque',
                'module' => 'design-graphique',
                'status' => 'active',
                'formations_count' => 8,
                'students_count' => 45,
                'created_at' => now()->subMonths(6),
            ],
            [
                'id' => 2,
                'name' => 'Communication Digitale',
                'slug' => 'communication-digitale',
                'description' => 'Catégories liées au community management et stratégie digitale',
                'module' => 'community-management',
                'status' => 'active',
                'formations_count' => 6,
                'students_count' => 32,
                'created_at' => now()->subMonths(5),
            ],
            [
                'id' => 3,
                'name' => 'Technologies de l\'Information',
                'slug' => 'technologies-information',
                'description' => 'Catégories liées à la gestion informatique et systèmes d\'information',
                'module' => 'gestion-informatique',
                'status' => 'active',
                'formations_count' => 5,
                'students_count' => 28,
                'created_at' => now()->subMonths(4),
            ],
            [
                'id' => 4,
                'name' => 'Intelligence Artificielle & Data',
                'slug' => 'ia-data',
                'description' => 'Catégories liées à l\'IA, machine learning et analyse de données',
                'module' => 'intelligence-artificielle',
                'status' => 'active',
                'formations_count' => 4,
                'students_count' => 18,
                'created_at' => now()->subMonths(3),
            ],
        ];

        $stats = [
            'total_categories' => 4,
            'total_formations' => 23,
            'categories_actives' => 4,
            'categories_sans_formation' => 0,
        ];

        $statsByModule = [
            'design-graphique' => 8,
            'community-management' => 6,
            'gestion-informatique' => 5,
            'intelligence-artificielle' => 4,
        ];

        return view('admin.formations.categories', compact('categories', 'stats', 'statsByModule'))
            ->with('info', 'Données de démonstration affichées.');
    }

    public function createCategory()
    {
        return view('admin.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        $validatedData['slug'] = Str::slug($validatedData['name']);
        $validatedData['status'] = 'active';

        Category::create($validatedData);

        return redirect()->route('admin.formations.categories.index')->with('success', 'Catégorie créée avec succès.');
    }

    public function editCategory($id): View
    {
        $category = Category::findOrFail($id);
        return view('admin.formations.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, $id): RedirectResponse
    {
        $category = Category::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        $validatedData['slug'] = Str::slug($validatedData['name']);

        $category->update($validatedData);

        return redirect()->route('admin.formations.categories.index')->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function deleteCategory($id)
    {
        try {
            $category = Category::findOrFail($id);

            // Vérifier si la catégorie a des formations associées
            $formationsCount = $category->formations()->count();

            if ($formationsCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Impossible de supprimer cette catégorie car elle contient {$formationsCount} formation(s)."
                ], 400);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Catégorie supprimée avec succès.'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la catégorie: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression.'
            ], 500);
        }
    }

    public function studentsDesignGraphique()
    {
        return redirect()->route('admin.students.by-formation', ['formation' => 'design-graphique']);
    }

    public function studentsCommunityManagement()
    {
        return redirect()->route('admin.students.by-formation', ['formation' => 'community-management']);
    }

    public function studentsDesignGraphiqueCommunityManager()
    {
        // Utiliser la liste standard (même logique/affichage que les autres formations)
        return redirect()->route('admin.students.by-formation', [
            'formation' => 'design-graphique-community-manager'
        ]);
    }

    public function bibliothequeCategories()
    {
        $categories = LibraryCategory::all();
        return view('admin.bibliotheque.categories.index', compact('categories'));
    }

    public function bibliotheque()
    {
        $items = Library::with('libraryCategory')->latest()->get();

        // Définir les espaces avec leurs informations complètes
        $espacesInfo = [
            [
                'slug' => 'design-graphique',
                'name' => 'Design Graphique',
                'icon' => 'fa-palette',
                'color' => 'primary'
            ],
            [
                'slug' => 'community-management',
                'name' => 'Community Management',
                'icon' => 'fa-bullhorn',
                'color' => 'info'
            ],
            [
                'slug' => 'gestion-informatique',
                'name' => 'Gestion Informatique',
                'icon' => 'fa-laptop-code',
                'color' => 'warning'
            ],
            [
                'slug' => 'intelligence-artificielle',
                'name' => 'Intelligence Artificielle',
                'icon' => 'fa-brain',
                'color' => 'success'
            ]
        ];

        // Calculer les statistiques par espace
        $parEspace = [];
        foreach ($espacesInfo as $espaceInfo) {
            $espace = $espaceInfo['slug'];
            $count = $items->filter(function ($item) use ($espace) {
                $itemSpaces = is_string($item->space) ? json_decode($item->space, true) : $item->space;
                if (is_array($itemSpaces)) {
                    return in_array($espace, $itemSpaces) || in_array('tous', $itemSpaces);
                }
                return $item->space === $espace || $item->space === 'tous';
            })->count();

            $parEspace[] = array_merge($espaceInfo, ['count' => $count]);
        }

        // Calculer les statistiques par catégorie
        $parCategorie = [];
        $categoriesGrouped = $items->groupBy('library_category_id');

        foreach ($categoriesGrouped as $categoryId => $categoryItems) {
            $category = $categoryItems->first()->libraryCategory ?? null;
            if ($category) {
                $parCategorie[] = [
                    'id' => $categoryId,
                    'name' => $category->name ?? 'Sans catégorie',
                    'slug' => $category->slug ?? 'sans-categorie',
                    'count' => $categoryItems->count()
                ];
            }
        }

        // Calculer les statistiques
        $stats = [
            'total_documents' => $items->count(),
            'documents_actifs' => $items->where('status', 'active')->count(),
            'total_downloads' => $items->sum('downloads') ?? 0,
            'documents_ce_mois' => $items->where('created_at', '>=', now()->startOfMonth())->count(),
            'par_espace' => $parEspace,
            'par_categorie' => collect($parCategorie)
        ];

        return view('admin.bibliotheque.index', compact('items', 'stats'));
    }

    public function programmes()
    {
        $itemsCountSub = DB::table('programme_items')
            ->select('programme_id', DB::raw('COUNT(*) as items_count'))
            ->groupBy('programme_id');

        $programmes = DB::table('programmes')
            ->leftJoinSub($itemsCountSub, 'pi', function ($join) {
                $join->on('programmes.id', '=', 'pi.programme_id');
            })
            ->select('programmes.*', DB::raw('COALESCE(pi.items_count, 0) as items_count'))
            ->orderBy('programmes.created_at', 'desc')
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

        $programmes = $programmes->map(function ($programme) use ($itemsByProgramme) {
            $items = $itemsByProgramme->get($programme->id, collect());
            $programme->items = $items;

            // Prochaine séance (si une date/heure est future)
            $now = now();
            $next = $items->first(function ($it) use ($now) {
                try {
                    $dt = \Carbon\Carbon::parse($it->session_date . ' ' . $it->session_time);
                    return $dt->greaterThanOrEqualTo($now);
                } catch (\Exception $e) {
                    return false;
                }
            });
            $programme->next_item = $next;

            return $programme;
        });

        $now = now();
        $programmesCurrentMonth = $programmes->filter(function ($programme) use ($now) {
            try {
                if (!empty($programme->month_start)) {
                    return \Carbon\Carbon::parse($programme->month_start)->isSameMonth($now);
                }
            } catch (\Throwable $e) {
                // ignore
            }

            $items = $programme->items ?? collect();
            if (!($items instanceof \Illuminate\Support\Collection)) {
                $items = collect($items);
            }

            return $items->contains(function ($it) use ($now) {
                try {
                    return !empty($it->session_date) && \Carbon\Carbon::parse($it->session_date)->isSameMonth($now);
                } catch (\Throwable $e) {
                    return false;
                }
            });
        })->values();

        // Calculer les statistiques
        $stats = [
            'total' => $programmes->count(),
            'design_graphique' => $programmes->where('formation', 'Design Graphique')->count(),
            'community_management' => $programmes->where('formation', 'Community Management')->count(),
            'gestion_informatique' => $programmes->where('formation', 'Gestion Informatique')->count(),
            'intelligence_artificielle' => $programmes->where('formation', 'Intelligence Artificielle')->count(),
            'tous' => $programmes->where('formation', 'Toutes')->count(),
            'ce_mois' => $programmes->where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        return view('admin.programmes.index', compact('programmes', 'programmesCurrentMonth', 'stats'));
    }

    public function createProgramme()
    {
        // Liste des étudiants actifs (pour ciblage spécifique)
        $students = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('students.status', 'active')
            ->select(
                'students.*',
                'users.email'
            )
            ->orderBy('students.first_name')
            ->orderBy('students.last_name')
            ->get();

        return view('admin.programmes.create', [
            'students' => $students,
        ]);
    }

    public function storeProgramme(Request $request)
    {
        $validatedData = $request->validate([
            'titre' => 'required|string|max:255',
            'month_start' => 'required|date_format:Y-m',
            'recipients_mode' => 'required|in:formation,students',
            'formation' => 'required_if:recipients_mode,formation|array|min:1',
            'formation.*' => 'string',
            'description' => 'nullable|string',
            'students' => 'required_if:recipients_mode,students|array|min:1',
            'students.*' => 'integer|exists:students,id',

            'items' => 'required|array|min:1',
            'items.*.thematique' => 'required|string|max:255',
            'items.*.session_date' => 'required|date',
            'items.*.session_time' => 'required|date_format:H:i',
            'items.*.type_formation' => 'required|in:en_ligne,presentielle',
            'items.*.lieu' => 'required_if:items.*.type_formation,presentielle|nullable|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.piece_jointe' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,ppt,pptx,xls,xlsx|max:51200', // Max 50MB
        ]);

        $monthStart = \Carbon\Carbon::createFromFormat('Y-m', $validatedData['month_start'])->startOfMonth()->toDateString();

        $programmesCreated = 0;
        $emailsSent = 0;
        $emailsFailures = [];

        $items = array_values($validatedData['items'] ?? []);

        if (($validatedData['recipients_mode'] ?? 'formation') === 'students') {
            // Mode: étudiants spécifiques -> 1 programme ciblé
            $studentIds = array_values($validatedData['students'] ?? []);

            $programmeId = DB::table('programmes')->insertGetId([
                'titre' => $validatedData['titre'],
                'month_start' => $monthStart,
                'formation' => 'Ciblage',
                'description' => $validatedData['description'] ?? null,
                'fichier_pdf' => '',
                'piece_jointe' => null,
                'student_ids' => json_encode($studentIds),
                'created_by' => session('admin_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $programmesCreated++;

            foreach ($items as $index => $item) {
                $filePath = null;
                $fileMime = null;
                if ($request->hasFile("items.$index.piece_jointe")) {
                    $file = $request->file("items.$index.piece_jointe");
                    $filePath = $file->store('programmes/items', 'public');
                    $fileMime = $file->getMimeType();
                }

                DB::table('programme_items')->insert([
                    'programme_id' => $programmeId,
                    'thematique' => $item['thematique'],
                    'session_date' => $item['session_date'],
                    'session_time' => $item['session_time'],
                    'type_formation' => $item['type_formation'],
                    'lieu' => ($item['type_formation'] ?? null) === 'presentielle' ? ($item['lieu'] ?? null) : null,
                    'description' => $item['description'] ?? null,
                    'piece_jointe' => $filePath,
                    'piece_jointe_mime' => $fileMime,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $students = DB::table('students')
                ->leftJoin('users', 'students.user_id', '=', 'users.id')
                ->where('students.status', 'active')
                ->whereIn('students.id', $studentIds)
                ->select('students.*', 'users.email')
                ->get();

            foreach ($students as $student) {
                if (empty($student->email)) {
                    continue;
                }

                try {
                    // Déterminer l'URL du programme selon la formation de l'étudiant
                    $formationSlug = 'design-graphique'; // Par défaut
                    if ($student->program) {
                        if (str_contains(strtolower($student->program), 'community')) {
                            $formationSlug = 'community-management';
                        } elseif (str_contains(strtolower($student->program), 'informatique')) {
                            $formationSlug = 'gestion-informatique';
                        } elseif (str_contains(strtolower($student->program), 'intelligence')) {
                            $formationSlug = 'intelligence-artificielle';
                        }
                    }

                    $programmeUrl = url("/evc/compte/{$formationSlug}/programme/index");

                    Mail::send('emails.programme_published', [
                        'student' => $student,
                        'programme' => [
                            'titre' => $validatedData['titre'],
                            'formation' => 'Ciblage',
                            'description' => $validatedData['description'] ?? null,
                        ],
                        'programmeUrl' => $programmeUrl,
                    ], function ($message) use ($student, $validatedData) {
                        $message->to($student->email)
                            ->subject('📚 Nouveau Programme : ' . $validatedData['titre']);
                    });

                    $emailsSent++;
                } catch (\Exception $e) {
                    $emailsFailures[] = $student->email;
                    Log::error('Erreur envoi email programme à ' . $student->email . ': ' . $e->getMessage());
                }
            }
        } else {
            // Mode: formation(s)
            $formations = array_values($validatedData['formation'] ?? []);
            if (in_array('Toutes', $formations, true)) {
                $formations = ['Toutes'];
            }

            foreach ($formations as $formation) {
                $programmeId = DB::table('programmes')->insertGetId([
                    'titre' => $validatedData['titre'],
                    'month_start' => $monthStart,
                    'formation' => $formation,
                    'description' => $validatedData['description'] ?? null,
                    'fichier_pdf' => '',
                    'piece_jointe' => null,
                    'student_ids' => null,
                    'created_by' => session('admin_id'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $programmesCreated++;

                foreach ($items as $index => $item) {
                    $filePath = null;
                    $fileMime = null;
                    if ($request->hasFile("items.$index.piece_jointe")) {
                        $file = $request->file("items.$index.piece_jointe");
                        $filePath = $file->store('programmes/items', 'public');
                        $fileMime = $file->getMimeType();
                    }

                    DB::table('programme_items')->insert([
                        'programme_id' => $programmeId,
                        'thematique' => $item['thematique'],
                        'session_date' => $item['session_date'],
                        'session_time' => $item['session_time'],
                        'type_formation' => $item['type_formation'],
                        'lieu' => ($item['type_formation'] ?? null) === 'presentielle' ? ($item['lieu'] ?? null) : null,
                        'description' => $item['description'] ?? null,
                        'piece_jointe' => $filePath,
                        'piece_jointe_mime' => $fileMime,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Récupérer les étudiants concernés par cette formation
                $studentsQuery = DB::table('students')
                    ->leftJoin('users', 'students.user_id', '=', 'users.id')
                    ->where('students.status', 'active')
                    ->select('students.*', 'users.email');

                if ($formation !== 'Toutes') {
                    $studentsQuery->where(function ($query) use ($formation) {
                        // Accepter les variantes de la formation sélectionnée
                        $query->where('students.program', $formation);

                        $formationLower = strtolower($formation);
                        if (str_contains($formationLower, 'design') && str_contains($formationLower, 'community')) {
                            $query->orWhereIn('students.program', [
                                'Design Graphique & Community Management',
                                'Design Graphique & Community Manager',
                                'design-graphique-community-manager',
                                'design_graphique_community_management',
                                'design-graphique-cm',
                                'design_cm',
                            ]);
                        } elseif (str_contains($formationLower, 'design')) {
                            $query->orWhereIn('students.program', [
                                'Design Graphique',
                                'Infographie',
                                'design-graphique',
                                'design_graphique',
                                'infographie',
                                'Design graphique',
                            ]);
                        } elseif (str_contains($formationLower, 'community')) {
                            $query->orWhereIn('students.program', [
                                'Community Management',
                                'community-management',
                                'community_management',
                                'Community management',
                                'CM',
                            ]);
                        } elseif (str_contains($formationLower, 'informatique')) {
                            $query->orWhereIn('students.program', [
                                'Gestion Informatique',
                                'gestion-informatique',
                                'gestion_informatique',
                                'Gestion informatique',
                                'GI',
                            ]);
                        } elseif (str_contains($formationLower, 'intelligence')) {
                            $query->orWhereIn('students.program', [
                                'Intelligence Artificielle',
                                'intelligence-artificielle',
                                'intelligence_artificielle',
                                'Intelligence artificielle',
                                'IA',
                            ]);
                        }
                    });
                }

                $students = $studentsQuery->get();

                foreach ($students as $student) {
                    if (empty($student->email)) {
                        continue;
                    }

                    try {
                        // Déterminer l'URL du programme selon la formation de l'étudiant
                        $formationSlug = 'design-graphique'; // Par défaut
                        if ($student->program) {
                            if (str_contains(strtolower($student->program), 'community')) {
                                $formationSlug = 'community-management';
                            } elseif (str_contains(strtolower($student->program), 'informatique')) {
                                $formationSlug = 'gestion-informatique';
                            } elseif (str_contains(strtolower($student->program), 'intelligence')) {
                                $formationSlug = 'intelligence-artificielle';
                            }
                        }

                        $programmeUrl = url("/evc/compte/{$formationSlug}/programme/index");

                        Mail::send('emails.programme_published', [
                            'student' => $student,
                            'programme' => [
                                'titre' => $validatedData['titre'],
                                'formation' => $formation,
                                'description' => $validatedData['description'] ?? null,
                            ],
                            'programmeUrl' => $programmeUrl,
                        ], function ($message) use ($student, $validatedData) {
                            $message->to($student->email)
                                ->subject('📚 Nouveau Programme : ' . $validatedData['titre']);
                        });

                        $emailsSent++;
                    } catch (\Exception $e) {
                        $emailsFailures[] = $student->email;
                        Log::error('Erreur envoi email programme à ' . $student->email . ': ' . $e->getMessage());
                    }
                }
            }
        }

        // Message de succès avec info sur les emails
        $message = $programmesCreated > 1 ? ($programmesCreated . ' programmes ajoutés avec succès') : 'Programme ajouté avec succès';
        if ($emailsSent > 0) {
            $message .= '. ' . $emailsSent . ' email(s) de notification envoyé(s) aux étudiants';
        }
        if (!empty($emailsFailures)) {
            $message .= '. Attention : ' . count($emailsFailures) . ' email(s) non envoyé(s)';
        }

        return redirect()->route('admin.programmes')->with('success', $message);
    }

    public function destroyProgramme($id)
    {
        $programme = DB::table('programmes')->where('id', $id)->first();

        if ($programme) {
            // Supprimer les fichiers des séances (programme_items)
            $items = DB::table('programme_items')
                ->where('programme_id', $id)
                ->get();

            foreach ($items as $item) {
                if (!empty($item->piece_jointe) && Storage::disk('public')->exists($item->piece_jointe)) {
                    Storage::disk('public')->delete($item->piece_jointe);
                }
            }

            // Supprimer les fichiers
            if (!empty($programme->fichier_pdf) && Storage::disk('public')->exists($programme->fichier_pdf)) {
                Storage::disk('public')->delete($programme->fichier_pdf);
            }
            if (!empty($programme->piece_jointe) && Storage::disk('public')->exists($programme->piece_jointe)) {
                Storage::disk('public')->delete($programme->piece_jointe);
            }

            // Supprimer le programme de la base de données
            DB::table('programmes')->where('id', $id)->delete();

            return redirect()->route('admin.programmes')->with('success', 'Programme supprimé avec succès.');
        }

        return redirect()->route('admin.programmes')->with('error', 'Programme introuvable.');
    }

    public function editProgramme($id)
    {
        $programme = DB::table('programmes')->where('id', (int) $id)->first();
        if (!$programme) {
            return redirect()->route('admin.programmes')->with('error', 'Programme introuvable.');
        }

        $items = collect();
        if (Schema::hasTable('programme_items')) {
            $items = DB::table('programme_items')
                ->where('programme_id', $programme->id)
                ->orderBy('session_date', 'asc')
                ->orderBy('session_time', 'asc')
                ->get();
        }

        // Liste des étudiants actifs (pour affichage en cas de ciblage spécifique)
        $students = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('students.status', 'active')
            ->select('students.*', 'users.email')
            ->orderBy('students.first_name')
            ->orderBy('students.last_name')
            ->get();

        return view('admin.programmes.edit', [
            'programme' => $programme,
            'items' => $items,
            'students' => $students,
        ]);
    }

    public function updateProgramme(Request $request, $id)
    {
        $programme = DB::table('programmes')->where('id', (int) $id)->first();
        if (!$programme) {
            return redirect()->route('admin.programmes')->with('error', 'Programme introuvable.');
        }

        $validatedData = $request->validate([
            'titre' => 'required|string|max:255',
            'month_start' => 'required|date_format:Y-m',
            'fichier_pdf' => 'nullable|file|mimes:pdf|max:51200',
            'recipients_mode' => 'required|in:formation,students',
            'formation' => 'required_if:recipients_mode,formation|array|min:1',
            'formation.*' => 'string',
            'description' => 'nullable|string',
            'students' => 'required_if:recipients_mode,students|array|min:1',
            'students.*' => 'integer|exists:students,id',

            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|integer',
            'items.*.thematique' => 'required|string|max:255',
            'items.*.session_date' => 'required|date',
            'items.*.session_time' => 'required|date_format:H:i',
            'items.*.type_formation' => 'required|in:en_ligne,presentielle',
            'items.*.lieu' => 'required_if:items.*.type_formation,presentielle|nullable|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.piece_jointe' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,ppt,pptx,xls,xlsx|max:51200',
        ]);

        $monthStart = \Carbon\Carbon::createFromFormat('Y-m', $validatedData['month_start'])->startOfMonth()->toDateString();

        $programmePdfPath = $programme->fichier_pdf ?? null;
        if ($request->hasFile('fichier_pdf')) {
            $file = $request->file('fichier_pdf');
            $newPath = $file->store('programmes/pdfs', 'public');

            if (!empty($programmePdfPath) && Storage::disk('public')->exists($programmePdfPath)) {
                Storage::disk('public')->delete($programmePdfPath);
            }

            $programmePdfPath = $newPath;
        }

        $formationValue = 'Ciblage';
        $studentIdsJson = null;

        if (($validatedData['recipients_mode'] ?? 'formation') === 'students') {
            $formationValue = 'Ciblage';
            $studentIdsJson = json_encode(array_values($validatedData['students'] ?? []));
        } else {
            $formations = array_values($validatedData['formation'] ?? []);
            if (in_array('Toutes', $formations, true)) {
                $formations = ['Toutes'];
            }

            // Un programme admin = une formation (si plusieurs formations, il faut créer plusieurs programmes)
            // Ici, en édition, on limite à UNE cible (la première) pour éviter de dupliquer silencieusement.
            // L'admin peut dupliquer via création si besoin.
            $formationValue = $formations[0] ?? 'Toutes';
        }

        DB::table('programmes')->where('id', $programme->id)->update([
            'titre' => $validatedData['titre'],
            'month_start' => $monthStart,
            'formation' => $formationValue,
            'description' => $validatedData['description'] ?? null,
            'fichier_pdf' => $programmePdfPath,
            'student_ids' => $studentIdsJson,
            'updated_at' => now(),
        ]);

        // Mettre à jour les séances
        if (!Schema::hasTable('programme_items')) {
            return redirect()->route('admin.programmes')->with('error', 'Table programme_items introuvable.');
        }

        $existingItemIds = DB::table('programme_items')
            ->where('programme_id', $programme->id)
            ->pluck('id')
            ->map(fn($v) => (int) $v)
            ->values()
            ->all();

        $submittedItems = array_values($validatedData['items'] ?? []);
        $submittedIds = [];

        foreach ($submittedItems as $index => $item) {
            $itemId = !empty($item['id']) ? (int) $item['id'] : null;

            $filePath = null;
            $fileMime = null;
            $hasNewFile = $request->hasFile("items.$index.piece_jointe");
            if ($hasNewFile) {
                $file = $request->file("items.$index.piece_jointe");
                $filePath = $file->store('programmes/items', 'public');
                $fileMime = $file->getMimeType();
            }

            if ($itemId && in_array($itemId, $existingItemIds, true)) {
                $submittedIds[] = $itemId;

                // Si nouvelle pièce jointe: supprimer l'ancienne
                if ($hasNewFile) {
                    $existing = DB::table('programme_items')->where('id', $itemId)->first();
                    if ($existing && !empty($existing->piece_jointe) && Storage::disk('public')->exists($existing->piece_jointe)) {
                        Storage::disk('public')->delete($existing->piece_jointe);
                    }
                }

                $updateData = [
                    'thematique' => $item['thematique'],
                    'session_date' => $item['session_date'],
                    'session_time' => $item['session_time'],
                    'type_formation' => $item['type_formation'],
                    'lieu' => ($item['type_formation'] ?? null) === 'presentielle' ? ($item['lieu'] ?? null) : null,
                    'description' => $item['description'] ?? null,
                    'updated_at' => now(),
                ];
                if ($hasNewFile) {
                    $updateData['piece_jointe'] = $filePath;
                    $updateData['piece_jointe_mime'] = $fileMime;
                }

                DB::table('programme_items')->where('id', $itemId)->update($updateData);
            } else {
                $newId = DB::table('programme_items')->insertGetId([
                    'programme_id' => $programme->id,
                    'thematique' => $item['thematique'],
                    'session_date' => $item['session_date'],
                    'session_time' => $item['session_time'],
                    'type_formation' => $item['type_formation'],
                    'lieu' => ($item['type_formation'] ?? null) === 'presentielle' ? ($item['lieu'] ?? null) : null,
                    'description' => $item['description'] ?? null,
                    'piece_jointe' => $filePath,
                    'piece_jointe_mime' => $fileMime,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $submittedIds[] = (int) $newId;
            }
        }

        // Supprimer les séances retirées du formulaire
        $toDelete = array_values(array_diff($existingItemIds, $submittedIds));
        if (!empty($toDelete)) {
            $itemsToDelete = DB::table('programme_items')->whereIn('id', $toDelete)->get();
            foreach ($itemsToDelete as $it) {
                if (!empty($it->piece_jointe) && Storage::disk('public')->exists($it->piece_jointe)) {
                    Storage::disk('public')->delete($it->piece_jointe);
                }
            }
            DB::table('programme_items')->whereIn('id', $toDelete)->delete();
        }

        return redirect()->route('admin.programmes')->with('success', 'Programme mis à jour avec succès.');
    }

    public function createBibliothequeItem()
    {
        $categories = LibraryCategory::all();
        return view('admin.bibliotheque.create', compact('categories'));
    }

    public function storeBibliothequeItem(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048', // Max 2MB pour l'image
            'pdf_file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx|max:51200', // Max 50MB
            'library_category_id' => 'nullable|exists:library_categories,id',
            'download_url' => 'nullable|url',
            'external_link' => 'nullable|url',
            'recipients' => 'nullable|array',
        ]);

        // Upload de l'image de couverture
        $coverImage = $request->file('cover_image');
        $coverPath = $coverImage->store('library/covers', 'public');

        // Exiger au moins un fichier OU un lien (comme indiqué dans l'UI)
        $hasFile = $request->hasFile('pdf_file');
        $hasLink = !empty($validatedData['external_link'] ?? null) || !empty($validatedData['download_url'] ?? null);
        if (!$hasFile && !$hasLink) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'pdf_file' => 'Veuillez fournir soit un fichier, soit un lien de téléchargement.',
                    'external_link' => 'Veuillez fournir soit un fichier, soit un lien de téléchargement.',
                ]);
        }

        $pdfPath = null;
        $pdfFile = $request->file('pdf_file');
        if ($pdfFile) {
            $pdfPath = $pdfFile->store('library/pdfs', 'public');
        }

        Library::create([
            'title' => $validatedData['title'],
            'name' => $coverImage->getClientOriginalName(),
            'path' => $coverPath,
            'pdf_path' => $pdfPath, // Nouveau champ pour le PDF
            'file_type' => $coverImage->getClientOriginalExtension(),
            'size' => $coverImage->getSize(),
            'library_category_id' => $validatedData['library_category_id'] ?? null,
            'download_url' => $validatedData['download_url'] ?? null,
            'external_link' => $validatedData['external_link'] ?? null,
            'recipients' => $validatedData['recipients'] ?? [],
        ]);

        return redirect()->route('admin.bibliotheque.index')->with('success', 'Média ajouté avec succès.');
    }

    public function showBibliothequeItem(Library $item): View
    {
        return view('admin.bibliotheque.show', compact('item'));
    }

    public function editBibliothequeItem(Library $item): View
    {
        $categories = LibraryCategory::all();
        return view('admin.bibliotheque.edit', compact('item', 'categories'));
    }

    public function updateBibliothequeItem(Request $request, Library $item): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'library_category_id' => 'nullable|exists:library_categories,id',
            'recipients' => 'nullable|array',
            'is_featured' => 'nullable|boolean',
        ]);

        $item->update([
            'title' => $validatedData['title'],
            'library_category_id' => $validatedData['library_category_id'] ?? null,
            'recipients' => $validatedData['recipients'] ?? [],
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.bibliotheque.index')->with('success', 'Média mis à jour avec succès.');
    }

    public function destroyBibliothequeItem(Library $item): RedirectResponse
    {
        $disk = Storage::disk('public');

        $deleteIfPresent = function ($path) use ($disk) {
            $path = (string) ($path ?? '');
            if ($path === '') {
                return;
            }

            // Ne pas tenter de supprimer une URL externe
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return;
            }

            $path = ltrim($path, '/');
            if (str_starts_with($path, 'storage/')) {
                $path = substr($path, strlen('storage/'));
            }

            try {
                if ($disk->exists($path)) {
                    $disk->delete($path);
                }
            } catch (\Throwable $e) {
                Log::warning('Unable to delete bibliotheque file from storage', [
                    'library_id' => $item->id ?? null,
                    'path' => $path,
                    'error' => $e->getMessage(),
                ]);
            }
        };

        $deleteIfPresent($item->path ?? null);
        $deleteIfPresent($item->pdf_path ?? null);
        $deleteIfPresent($item->cover_image ?? null);
        $item->delete();

        return redirect()->route('admin.bibliotheque.index')->with('success', 'Média supprimé avec succès.');
    }

    public function toggleBibliothequeItemStatus(Library $item): RedirectResponse
    {
        $item->status = $item->status == 'active' ? 'inactive' : 'active';
        $item->save();

        return redirect()->route('admin.bibliotheque.index')->with('success', 'Statut du média mis à jour avec succès.');
    }

    public function travauxPending()
    {
        // Récupérer tous les TP en attente de validation (pending) et soumis (submitted)
        $pendingTps = DB::table('tp_assignments')
            ->whereIn('tp_assignments.status', ['pending', 'submitted'])
            ->leftJoin('students', 'tp_assignments.student_id', '=', 'students.id')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->select(
                'tp_assignments.*',
                'students.first_name as student_first_name',
                'students.last_name as student_last_name',
                'users.email as student_email',
                'students.program as formation',
                'students.profile_photo'
            )
            ->orderBy('tp_assignments.created_at', 'desc')
            ->get();

        // Récupérer tous les projets à valider (status = termine)
        $pendingProjects = DB::table('projects')
            ->where('projects.status', 'termine')
            ->leftJoin('users', 'projects.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->select(
                'projects.*',
                'users.email as student_email',
                'students.first_name',
                'students.last_name',
                'students.program as formation',
                'students.profile_photo'
            )
            ->orderBy('projects.updated_at', 'desc')
            ->get();

        $pendingProjects = $pendingProjects->map(function ($project) {
            $files = DB::table('project_images')
                ->where('project_id', $project->id)
                ->select('id', 'image_path', 'created_at')
                ->get();

            $project->files = $files;
            $project->submitted_at = $project->updated_at;

            return $project;
        });

        // Grouper les TP par étudiant et formation
        $studentsByFormation = $pendingTps->groupBy('formation')->map(function ($formationTps) {
            return $formationTps->groupBy('student_id')->map(function ($studentTps) {
                $firstTp = $studentTps->first();
                $latestSubmission = $studentTps->whereNotNull('submitted_at')->sortByDesc('submitted_at')->first();

                return [
                    'student_id' => $firstTp->student_id,
                    'user_id' => $firstTp->user_id,
                    'first_name' => $firstTp->student_first_name,
                    'last_name' => $firstTp->student_last_name,
                    'user_name' => $firstTp->student_first_name . ' ' . $firstTp->student_last_name,
                    'user_email' => $firstTp->student_email,
                    'profile_photo' => $firstTp->profile_photo,
                    'program' => $firstTp->formation,
                    'formation' => $firstTp->formation,
                    'tps_count' => $studentTps->count(),
                    'latest_submission' => $latestSubmission ? $latestSubmission->submitted_at : $firstTp->created_at,
                    'tps' => $studentTps,
                    'pending_count' => $studentTps->count()
                ];
            })->values();
        });

        // Calculer les statistiques
        $totalStudents = $pendingTps->unique('student_id')->count();

        $stats = [
            'total_pending' => $pendingTps->count(),
            'total_students' => $totalStudents,
            'total_tps' => DB::table('tp_assignments')->count(),
            'pending_projects' => $pendingProjects->count(),
            'design_graphique' => $pendingTps->where('formation', 'Design Graphique')->count(),
            'community_management' => $pendingTps->where('formation', 'Community Management')->count(),
            'gestion_informatique' => $pendingTps->where('formation', 'Gestion Informatique')->count(),
            'intelligence_artificielle' => $pendingTps->where('formation', 'Intelligence Artificielle')->count(),
        ];

        // Aplatir tous les étudiants pour la vue principale
        $studentsTps = collect();
        foreach ($studentsByFormation as $formationStudents) {
            $studentsTps = $studentsTps->merge($formationStudents);
        }

        return view('admin.travaux.pending', [
            'students_by_formation' => $studentsByFormation,
            'studentsTps' => $studentsTps,
            'stats' => $stats,
            'pendingProjects' => $pendingProjects,
        ]);
    }

    public function travauxToSend()
    {
        // Récupérer toutes les formations
        $formations = \App\Models\Formation::with('category')->where('status', 'active')->get();

        // Récupérer tous les étudiants depuis la table students avec les infos utilisateur
        $students = DB::table('students')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('students.status', 'active')
            ->select(
                'students.*',
                'students.program',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->get();

        // Traiter les noms et normaliser les formations
        $students = $students->map(function ($student) {
            // Séparer prénom et nom
            $nameParts = explode(' ', $student->user_name, 2);
            $student->prenom = $nameParts[0] ?? $student->user_name;
            $student->nom = $nameParts[1] ?? '';

            // Normaliser la formation (même logique que projets)
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
                $student->formation = $normalized;
                $student->formation_normalized = $normalized;
            } else {
                $student->formation = 'Sans formation';
                $student->formation_normalized = 'Sans formation';
            }

            return $student;
        });

        $userIds = $students
            ->pluck('user_id')
            ->filter(fn($id) => !empty($id))
            ->unique()
            ->values();

        $userIdsWithProjects = DB::table('projects')
            ->whereIn('user_id', $userIds)
            ->distinct()
            ->pluck('user_id');

        $userIdsWithProjects = $userIdsWithProjects
            ->map(fn($id) => (int) $id)
            ->flip();

        $students = $students->map(function ($student) use ($userIdsWithProjects) {
            $userId = (int) ($student->user_id ?? 0);
            $student->has_projects = $userId > 0 && $userIdsWithProjects->has($userId);
            return $student;
        });

        // Calculer les statistiques avec formations normalisées
        $stats = [
            'total_formations' => $formations->count(),
            'total_students' => $students->count(),
            'design_graphique' => $students->where('formation_normalized', 'Design Graphique')->count(),
            'design_graphique_cm' => $students->where('formation_normalized', 'Design Graphique & Community Management')->count(),
            'community_management' => $students->where('formation_normalized', 'Community Management')->count(),
            'gestion_informatique' => $students->where('formation_normalized', 'Gestion Informatique')->count(),
            'intelligence_artificielle' => $students->where('formation_normalized', 'Intelligence Artificielle')->count(),
            'sans_formation' => $students->where('formation_normalized', 'Sans formation')->count(),
        ];

        return view('admin.travaux.to-send', [
            'formations' => $formations,
            'students' => $students,
            'all_students' => $students,  // Alias pour compatibilité avec la vue
            'stats' => $stats
        ]);
    }

    /**
     * Envoyer un TP aux étudiants sélectionnés
     */
    public function sendTravaux(Request $request)
    {
        $request->validate([
            'tp_title' => 'required|string|max:255',
            'tp_description' => 'required|string',
            'tp_deadline' => 'required|date|after:today',
            'formation' => 'required|string',
            'students' => 'required|array',
            'students.*' => 'exists:students,id',
            'tp_files.*' => 'file|mimes:jpg,jpeg,png,gif,pdf|max:5120',
        ]);

        $adminId = session('admin_id');
        $studentsIds = $request->students;
        $assignments = [];
        $emailsSent = 0;
        $emailsFailures = [];

        foreach ($studentsIds as $studentId) {
            // Récupérer les infos de l'étudiant
            $student = DB::table('students')->where('id', $studentId)->first();

            if ($student) {
                $assignment = [
                    'user_id' => $student->user_id,
                    'student_id' => $studentId,
                    'title' => $request->tp_title,
                    'description' => $request->tp_description,
                    'deadline' => $request->tp_deadline,
                    'formation' => $request->formation,
                    'status' => 'assigned',
                    'assigned_by' => $adminId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $assignmentId = DB::table('tp_assignments')->insertGetId($assignment);
                $assignment['id'] = $assignmentId;
                $assignments[] = $assignment;

                // Notification in-app (database) pour l'étudiant
                try {
                    $user = \App\Models\User::find($student->user_id);
                    if ($user) {
                        // Mapper la formation vers le bon slug de route
                        $formationSlug = $this->getFormationSlug($assignment['formation']);

                        $user->notify(new TpAssignedNotification([
                            'category' => 'tp',
                            'event' => 'assigned',
                            'title' => 'Nouveau travail assigné',
                            'message' => 'Un nouveau TP a été assigné : ' . $assignment['title'],
                            'assignment_id' => $assignmentId,
                            'tp_title' => $assignment['title'],
                            'formation' => $assignment['formation'],
                            'created_at' => now()->toIso8601String(),
                            'url' => url('/evc/compte/' . $formationSlug . '/tp/index'),
                        ]));
                    }
                } catch (\Exception $e) {
                    Log::warning('Notification in-app TP assigné échouée', [
                        'student_id' => $studentId,
                        'user_id' => $student->user_id ?? null,
                        'error' => $e->getMessage(),
                    ]);
                }

                // Associer les fichiers à cette assignation
                if ($request->hasFile('tp_files')) {
                    foreach ($request->file('tp_files') as $file) {
                        if (!$file || !$file->isValid()) {
                            continue;
                        }

                        $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                        $directory = 'tp_assignments/' . $assignmentId . '/brief';
                        $filePath = $file->storeAs($directory, $fileName, 'public');

                        DB::table('tp_assignment_files')->insert([
                            'tp_assignment_id' => $assignmentId,
                            'file_name' => $file->getClientOriginalName(),
                            'file_path' => $filePath,
                            'file_type' => $file->getMimeType(),
                            'file_size' => $file->getSize(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                // Envoi de l'email de notification
                try {
                    Mail::send('emails.tp_assigned', [
                        'student' => $student,
                        'assignment' => $assignment
                    ], function ($message) use ($student, $assignment) {
                        $message->to($student->email)
                            ->subject('Nouveau TP : ' . $assignment['title']);
                    });
                    $emailsSent++;
                } catch (\Exception $e) {
                    $emailsFailures[] = $student->email;
                    Log::error('Erreur envoi email TP à ' . $student->email . ': ' . $e->getMessage());
                }
            }
        }

        $message = 'TP envoyé avec succès à ' . count($assignments) . ' étudiant(s)';
        if ($emailsSent > 0) {
            $message .= '. ' . $emailsSent . ' email(s) de notification envoyé(s)';
        }
        if (!empty($emailsFailures)) {
            $message .= '. Attention : ' . count($emailsFailures) . ' email(s) non envoyé(s)';
        }

        return redirect()
            ->route('admin.travaux.assigned')
            ->with('success', $message);
    }

    /**
     * Afficher les détails d'une assignation de TP par titre
     */
    public function assignmentDetail($title)
    {
        // Décoder le titre URL
        $title = urldecode($title);

        // Récupérer toutes les assignations pour ce titre
        $assignments = DB::table('tp_assignments')
            ->leftJoin('students', 'tp_assignments.student_id', '=', 'students.id')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('tp_assignments.title', $title)
            ->select(
                'tp_assignments.*',
                'students.first_name as student_first_name',
                'students.last_name as student_last_name',
                'students.email as student_email',
                'students.program as formation',
                'students.program',
                'students.profile_photo',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->orderBy('tp_assignments.created_at', 'desc')
            ->get();

        if ($assignments->isEmpty()) {
            return redirect()->route('admin.travaux.assigned')
                ->with('error', 'Aucune assignation trouvée pour ce TP');
        }

        // Prendre la première assignation pour obtenir les infos du TP
        $assignment = $assignments->first();

        // Récupérer les fichiers associés aux assignations
        $assignmentIds = $assignments->pluck('id');
        $files = DB::table('tp_assignment_files')
            ->whereIn('tp_assignment_id', $assignmentIds)
            ->get();

        // Statistiques
        $stats = [
            'total' => $assignments->count(),
            'assigned' => $assignments->where('status', 'assigned')->count(),
            'submitted' => $assignments->where('status', 'submitted')->count(),
            'validated' => $assignments->where('status', 'validated')->count(),
            'rejected' => $assignments->where('status', 'rejected')->count(),
            'pending' => $assignments->where('status', 'assigned')->count(), // Alias pour compatibilité
        ];

        return view('admin.travaux.assignment-detail', [
            'title' => $title,
            'assignment' => $assignment,
            'assignments' => $assignments,
            'students' => $assignments, // Alias pour la vue qui attend $students
            'files' => $files,
            'stats' => $stats
        ]);
    }

    /**
     * Afficher le formulaire d'édition d'un TP assigné par titre
     */
    public function editAssignment($title)
    {
        // Décoder le titre URL
        $title = urldecode($title);

        // Récupérer la première assignation pour obtenir les infos du TP
        $assignment = DB::table('tp_assignments')
            ->where('title', $title)
            ->first();

        if (!$assignment) {
            return redirect()->route('admin.travaux.assigned')
                ->with('error', 'Aucune assignation trouvée pour ce TP');
        }

        // Récupérer les IDs des étudiants actuellement assignés
        $assignedStudentIds = DB::table('tp_assignments')
            ->where('title', $title)
            ->pluck('student_id')
            ->toArray();

        // Récupérer tous les étudiants actifs
        $allStudents = DB::table('students')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select(
                'students.id',
                'students.first_name',
                'students.last_name',
                'students.program',
                'users.email'
            )
            ->orderBy('students.first_name')
            ->get();

        // Grouper les étudiants par formation
        $studentsByFormation = $allStudents->groupBy('program');

        return view('admin.travaux.edit-assignment', [
            'assignment' => $assignment,
            'title' => $title,
            'studentsCount' => count($assignedStudentIds),
            'assignedStudentIds' => $assignedStudentIds,
            'studentsByFormation' => $studentsByFormation,
            'allStudents' => $allStudents
        ]);
    }

    /**
     * Mettre à jour un TP assigné par titre (met à jour toutes les assignations avec ce titre)
     */
    public function updateAssignment(Request $request, $title)
    {
        // Décoder le titre URL
        $title = urldecode($title);


        $request->validate([
            'new_title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'students' => 'required|array',
            'students.*' => 'exists:students,id',
        ]);

        // Supprimer toutes les anciennes assignations
        DB::table('tp_assignments')
            ->where('title', $title)
            ->delete();

        // Créer de nouvelles assignations pour les étudiants sélectionnés
        $adminId = session('admin_id');
        $formation = $request->formation ?? 'Non spécifié';

        foreach ($request->students as $studentId) {
            DB::table('tp_assignments')->insert([
                'user_id' => DB::table('students')->where('id', $studentId)->value('user_id'),
                'student_id' => $studentId,
                'title' => $request->new_title,
                'description' => $request->description,
                'deadline' => $request->deadline,
                'formation' => DB::table('students')->where('id', $studentId)->value('program') ?? $formation,
                'status' => 'assigned',
                'assigned_by' => $adminId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()
            ->route('admin.travaux.assigned')
            ->with('success', "TP modifié avec succès pour " . count($request->students) . " étudiant(s)");
    }

    /**
     * Supprimer une assignation de TP par titre (supprime toutes les assignations avec ce titre)
     */
    public function deleteAssignment($title)
    {
        // Décoder le titre URL
        $title = urldecode($title);

        $deleted = DB::table('tp_assignments')
            ->where('title', $title)
            ->delete();

        return redirect()
            ->route('admin.travaux.assigned')
            ->with('success', "$deleted assignation(s) supprimée(s) avec succès");
    }

    public function travauxAssigned()
    {
        // Récupérer tous les TP assignés
        $assignments = DB::table('tp_assignments')
            ->leftJoin('students', 'tp_assignments.student_id', '=', 'students.id')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->select(
                'tp_assignments.*',
                'students.first_name as student_first_name',
                'students.last_name as student_last_name',
                'students.email as student_email',
                'students.program as formation',
                'students.profile_photo',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->orderBy('tp_assignments.created_at', 'desc')
            ->get();

        // Créer student_name en combinant first_name et last_name
        $assignments = $assignments->map(function ($assignment) {
            $assignment->student_name = trim(($assignment->student_first_name ?? '') . ' ' . ($assignment->student_last_name ?? ''));
            return $assignment;
        });

        // Grouper par formation
        $assignmentsByFormation = $assignments->groupBy('formation');

        // Calculer les statistiques
        $submittedCount = $assignments->whereNotNull('submitted_at')->count();

        $stats = [
            'total' => $assignments->count(),  // Alias pour compatibilité
            'total_assignments' => $assignments->count(),
            'assigned' => $assignments->count(),  // Alias pour compatibilité
            'submitted' => $submittedCount,  // TP soumis par les étudiants
            'pending' => $assignments->where('status', 'pending')->count(),
            'validated' => $assignments->where('status', 'validated')->count(),
            'rejected' => $assignments->where('status', 'rejected')->count(),
            'design_graphique' => $assignments->where('formation', 'Design Graphique')->count(),
            'community_management' => $assignments->where('formation', 'Community Management')->count(),
            'gestion_informatique' => $assignments->where('formation', 'Gestion Informatique')->count(),
            'intelligence_artificielle' => $assignments->where('formation', 'Intelligence Artificielle')->count(),
        ];

        return view('admin.travaux.assigned', [
            'assignments' => $assignments,
            'assignmentsByFormation' => $assignmentsByFormation,
            'tpAssignmentsByFormation' => $assignmentsByFormation,  // Alias pour compatibilité
            'stats' => $stats
        ]);
    }

    public function travauxAll()
    {
        // Récupérer tous les TP (toutes catégories)
        $allTravaux = DB::table('tp_assignments')
            ->leftJoin('students', 'tp_assignments.student_id', '=', 'students.id')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->select(
                'tp_assignments.*',
                'students.first_name as student_first_name',
                'students.last_name as student_last_name',
                'students.email as student_email',
                DB::raw('COALESCE(students.program, "Formation non définie") as formation'),
                'students.profile_photo',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->orderBy('tp_assignments.created_at', 'desc')
            ->get();

        // Créer student_name en combinant first_name et last_name
        $allTravaux = $allTravaux->map(function ($travail) {
            $travail->student_name = trim(($travail->student_first_name ?? '') . ' ' . ($travail->student_last_name ?? ''));
            return $travail;
        });

        // Calculer les statistiques globales
        $totalStudents = $allTravaux->unique('student_id')->count();

        $assignedCount = $allTravaux->where('status', 'assigned')->count();
        $submittedCount = $allTravaux->where('status', 'submitted')->count();
        $pendingCount = $allTravaux->where('status', 'pending')->count();
        $validatedCount = $allTravaux->where('status', 'validated')->count();
        $rejectedCount = $allTravaux->where('status', 'rejected')->count();

        $stats = [
            'total' => $allTravaux->count(),
            'total_assignments' => $allTravaux->count(),
            'total_tps' => $allTravaux->count(),  // Alias pour compatibilité
            'total_students' => $totalStudents,  // Nombre d'étudiants uniques
            'assigned' => $assignedCount,
            'submitted' => $submittedCount,
            'pending' => $pendingCount,
            'pending_tps' => $pendingCount,  // Alias pour compatibilité
            'validated' => $validatedCount,
            'validated_tps' => $validatedCount,  // Alias pour compatibilité
            'rejected' => $rejectedCount,
            'rejected_tps' => $rejectedCount,  // Alias pour compatibilité
            'not_submitted' => $allTravaux->whereNull('submitted_at')->count(),
            'design_graphique' => $allTravaux->where('formation', 'Design Graphique')->count(),
            'community_management' => $allTravaux->where('formation', 'Community Management')->count(),
            'gestion_informatique' => $allTravaux->where('formation', 'Gestion Informatique')->count(),
            'intelligence_artificielle' => $allTravaux->where('formation', 'Intelligence Artificielle')->count(),
        ];

        // Créer studentsTps pour compatibilité avec la vue
        // Grouper les travaux par étudiant
        $studentsTps = $allTravaux->groupBy('student_id')->map(function ($studentTravaux) {
            $firstTravail = $studentTravaux->first();
            // Trouver la dernière soumission
            $latestSubmission = $studentTravaux->whereNotNull('submitted_at')->sortByDesc('submitted_at')->first();

            // Déterminer la formation avec fallback
            $formation = $firstTravail->formation ?? 'Formation non définie';

            return [
                'student_id' => $firstTravail->student_id,
                'user_id' => $firstTravail->student_id,             // Alias pour les collapsibles
                'student_name' => $firstTravail->student_name,
                'student_first_name' => $firstTravail->student_first_name,
                'student_last_name' => $firstTravail->student_last_name,
                'first_name' => $firstTravail->student_first_name,  // Alias
                'last_name' => $firstTravail->student_last_name,    // Alias
                'student_email' => $firstTravail->student_email,
                'user_email' => $firstTravail->student_email,       // Alias
                'formation' => $formation,
                'program' => $formation,                            // Alias
                'profile_photo' => $firstTravail->profile_photo,
                'tps_count' => $studentTravaux->count(),
                'assigned_count' => $studentTravaux->where('status', 'assigned')->count(),
                'submitted_count' => $studentTravaux->where('status', 'submitted')->count(),
                'pending_count' => $studentTravaux->where('status', 'pending')->count(),
                'validated_count' => $studentTravaux->where('status', 'validated')->count(),
                'rejected_count' => $studentTravaux->where('status', 'rejected')->count(),
                'latest_submission' => $latestSubmission ? $latestSubmission->submitted_at : null,
                'tps' => $studentTravaux->values(),      // Collection des TP pour le détail
            ];
        })->values();

        return view('admin.travaux.all', [
            'travaux' => $allTravaux,
            'allTravaux' => $allTravaux,  // Alias pour compatibilité
            'studentsTps' => $studentsTps,  // Travaux groupés par étudiant
            'stats' => $stats
        ]);
    }

    public function documentsAll(): View
    {
        // Récupère tous les rapports/travaux publiés par les étudiants
        $rapports = DB::table('tp')
            ->join('users', 'tp.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->when(\Illuminate\Support\Facades\Schema::hasColumn('tp', 'is_report'), function ($q) {
                $q->where(function ($sub) {
                    $sub->where('tp.is_report', 1)
                        ->orWhere('tp.title', 'LIKE', '%rapport%')
                        ->orWhere('tp.title', 'LIKE', '%Rapport%')
                        ->orWhere('tp.title', 'LIKE', '%RAPPORT%');
                });
            }, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('tp.title', 'LIKE', '%rapport%')
                        ->orWhere('tp.title', 'LIKE', '%Rapport%')
                        ->orWhere('tp.title', 'LIKE', '%RAPPORT%');
                });
            })
            ->select(
                'tp.id',
                'tp.title',
                'tp.description',
                'tp.status',
                'tp.created_at',
                'tp.updated_at',
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                'students.profile_photo as user_photo',
                'students.program as formation',
                'students.specialization'
            )
            ->orderBy('tp.created_at', 'desc')
            ->get();

        // Récupérer les fichiers pour chaque TP
        foreach ($rapports as $rapport) {
            $rapport->files = DB::table('tp_files')
                ->where('tp_id', $rapport->id)
                ->get();
        }

        // Statistiques
        $stats = [
            'total' => $rapports->count(),
            'validated' => $rapports->where('status', 'validated')->count(),
            'pending' => $rapports->where('status', 'pending')->count(),
            'rejected' => $rapports->where('status', 'rejected')->count(),
        ];

        // Retourne la vue en passant la collection de rapports
        return view('admin.documents.all', compact('rapports', 'stats'));
    }

    /**
     * Afficher les documents en attente de validation
     */
    public function documentsPending(): View
    {
        // Récupère uniquement les rapports en attente
        $rapports = DB::table('tp')
            ->join('users', 'tp.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->where('tp.status', 'pending')
            ->when(\Illuminate\Support\Facades\Schema::hasColumn('tp', 'is_report'), function ($q) {
                $q->where(function ($sub) {
                    $sub->where('tp.is_report', 1)
                        ->orWhere('tp.title', 'LIKE', '%rapport%')
                        ->orWhere('tp.title', 'LIKE', '%Rapport%')
                        ->orWhere('tp.title', 'LIKE', '%RAPPORT%');
                });
            }, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('tp.title', 'LIKE', '%rapport%')
                        ->orWhere('tp.title', 'LIKE', '%Rapport%')
                        ->orWhere('tp.title', 'LIKE', '%RAPPORT%');
                });
            })
            ->select(
                'tp.id',
                'tp.title',
                'tp.description',
                'tp.status',
                'tp.created_at',
                'tp.updated_at',
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                'students.profile_photo as user_photo',
                'students.program as formation',
                'students.specialization'
            )
            ->orderBy('tp.created_at', 'desc')
            ->get();

        // Récupérer les fichiers pour chaque TP
        foreach ($rapports as $rapport) {
            $rapport->files = DB::table('tp_files')
                ->where('tp_id', $rapport->id)
                ->get();
        }

        // Statistiques
        $stats = [
            'total' => $rapports->count(),
            'pending' => $rapports->count(),
            'today' => $rapports->filter(function ($rapport) {
                return \Carbon\Carbon::parse($rapport->created_at)->isToday();
            })->count(),
        ];

        // Retourne la vue en passant la collection de rapports
        return view('admin.documents.pending', compact('rapports', 'stats'));
    }

    /**
     * Voir un TP (admin)
     */
    public function viewTp(int $id)
    {
        // Si on force l'affichage d'un rapport TP (table `tp`), éviter toute collision d'ID
        // avec `tp_assignments` / `projects`.
        if (request()->get('source') === 'tp_report') {
            $tp = DB::table('tp')->where('id', $id)->first();

            if (!$tp) {
                abort(404, 'TP introuvable');
            }

            // Récupérer l'utilisateur associé
            $user = DB::table('users')->where('id', $tp->user_id)->first();

            // Récupérer les fichiers associés au TP
            $files = collect([]);
            if (Schema::hasTable('tp_files')) {
                $files = DB::table('tp_files')->where('tp_id', $id)->get();
            }

            // Ajouter les propriétés manquantes au TP
            $tp->tags = $tp->tags ?? null;
            $tp->software_used = $tp->software_used ?? null;
            $tp->files = $files;

            return view('tp.view-admin', [
                'project' => $tp,
                'user' => $user
            ]);
        }

        // Chercher d'abord dans tp_assignments (travaux)
        $tp = DB::table('tp_assignments')
            ->leftJoin('students', 'tp_assignments.student_id', '=', 'students.id')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('tp_assignments.id', $id)
            ->select(
                'tp_assignments.*',
                'students.first_name as student_first_name',
                'students.last_name as student_last_name',
                'users.email as student_email',
                'students.program as formation',
                'students.profile_photo'
            )
            ->first();

        // Si pas trouvé, chercher dans projects (TP CM)
        if (!$tp) {
            $project = DB::table('projects')
                ->join('users', 'projects.user_id', '=', 'users.id')
                ->leftJoin('students', 'users.id', '=', 'students.user_id')
                ->where('projects.id', $id)
                ->select(
                    'projects.*',
                    'students.first_name as student_first_name',
                    'students.last_name as student_last_name',
                    'users.email as student_email',
                    'students.program as formation',
                    'students.profile_photo'
                )
                ->first();

            if ($project) {
                // Mapper les statuts français vers anglais pour la vue
                $statusMap = [
                    'en_cours' => 'assigned',
                    'termine' => 'submitted',
                    'valide' => 'validated',
                    'rejete' => 'rejected',
                ];
                $project->status = $statusMap[$project->status] ?? $project->status;

                // Récupérer les fichiers/images associés
                $files = DB::table('project_images')
                    ->where('project_id', $id)
                    ->get();

                // Créer un objet student pour la vue
                $student = (object)[
                    'first_name' => $project->student_first_name,
                    'last_name' => $project->student_last_name,
                    'email' => $project->student_email,
                    'program' => $project->formation,
                    'profile_photo' => $project->profile_photo,
                ];

                return view('admin.travaux.view', [
                    'tp' => $project,
                    'student' => $student,
                    'files' => $files
                ]);
            }
        }

        // Si pas trouvé dans projects, chercher dans tp (rapports)
        if (!$tp) {
            $tp = DB::table('tp')->where('id', $id)->first();

            if (!$tp) {
                abort(404, 'TP introuvable');
            }

            // Récupérer l'utilisateur associé
            $user = DB::table('users')->where('id', $tp->user_id)->first();

            // Récupérer les fichiers associés au TP
            $files = collect([]);
            if (Schema::hasTable('tp_files')) {
                $files = DB::table('tp_files')->where('tp_id', $id)->get();
            }

            // Ajouter les propriétés manquantes au TP
            $tp->tags = $tp->tags ?? null;
            $tp->software_used = $tp->software_used ?? null;
            $tp->files = $files;

            return view('tp.view-admin', [
                'project' => $tp,
                'user' => $user
            ]);
        }

        // Pour tp_assignments, créer un objet student pour la vue
        $student = (object)[
            'first_name' => $tp->student_first_name,
            'last_name' => $tp->student_last_name,
            'email' => $tp->student_email,
            'program' => $tp->formation,
            'profile_photo' => $tp->profile_photo,
        ];

        // Récupérer les fichiers associés à ce TP
        $files = DB::table('tp_assignment_files')
            ->where('tp_assignment_id', $id)
            ->get();

        return view('admin.travaux.view', [
            'tp' => $tp,
            'student' => $student,
            'files' => $files
        ]);
    }

    /**
     * Valider un TP (admin)
     */
    public function validateTp(Request $request, int $id)
    {
        try {
            // Si la demande vient de la page projets CM/SMM (table `projects`),
            // éviter toute collision d'ID avec `tp_assignments`.
            if ($request->input('source') === 'cm_project') {
                $project = DB::table('projects')
                    ->join('users', 'projects.user_id', '=', 'users.id')
                    ->leftJoin('students', 'users.id', '=', 'students.user_id')
                    ->where('projects.id', $id)
                    ->select(
                        'projects.*',
                        'students.first_name as student_first_name',
                        'students.last_name as student_last_name',
                        'users.email as student_email'
                    )
                    ->first();

                if (!$project) {
                    return redirect()->back()->with('error', 'Projet introuvable');
                }

                DB::table('projects')->where('id', $id)->update([
                    'status' => 'valide',
                    'updated_at' => now(),
                ]);

                try {
                    $student = (object)[
                        'first_name' => $project->student_first_name,
                        'last_name' => $project->student_last_name,
                        'email' => $project->student_email,
                    ];

                    Mail::send('emails.tp_validated', [
                        'student' => $student,
                        'tp' => $project,
                    ], function ($message) use ($student, $project) {
                        $message->to($student->email)
                            ->subject('✅ Votre projet "' . $project->title . '" a été validé !');
                    });
                } catch (\Exception $e) {
                    Log::error('Erreur envoi email validation projet CM (source cm_project): ' . $e->getMessage());
                }

                return redirect()->back()->with('success', '✅ Projet validé avec succès !');
            }

            if ($request->input('source') === 'tp_report') {
                $tpReport = DB::table('tp')->where('id', $id)->first();

                if (!$tpReport) {
                    return redirect()->back()->with('error', 'TP introuvable');
                }

                DB::table('tp')->where('id', $id)->update([
                    'status' => 'validated',
                    'validated_at' => now(),
                    'updated_at' => now(),
                ]);

                return redirect()->back()->with('success', 'TP validé avec succès.');
            }

            // Chercher d'abord dans tp_assignments
            $tp = DB::table('tp_assignments')
                ->leftJoin('students', 'tp_assignments.student_id', '=', 'students.id')
                ->leftJoin('users', 'students.user_id', '=', 'users.id')
                ->where('tp_assignments.id', $id)
                ->select(
                    'tp_assignments.*',
                    'students.first_name as student_first_name',
                    'students.last_name as student_last_name',
                    'users.email as student_email'
                )
                ->first();

            // Si pas trouvé, chercher dans projects (TP CM)
            if (!$tp) {
                $project = DB::table('projects')
                    ->join('users', 'projects.user_id', '=', 'users.id')
                    ->leftJoin('students', 'users.id', '=', 'students.user_id')
                    ->where('projects.id', $id)
                    ->select(
                        'projects.*',
                        'students.first_name as student_first_name',
                        'students.last_name as student_last_name',
                        'users.email as student_email'
                    )
                    ->first();

                if ($project) {
                    // Valider le projet CM (statut français: valide)
                    DB::table('projects')->where('id', $id)->update([
                        'status' => 'valide',
                        'updated_at' => now()
                    ]);

                    // Email de validation
                    try {
                        $student = (object)[
                            'first_name' => $project->student_first_name,
                            'last_name' => $project->student_last_name,
                            'email' => $project->student_email,
                        ];

                        Mail::send('emails.tp_validated', [
                            'student' => $student,
                            'tp' => $project
                        ], function ($message) use ($student, $project) {
                            $message->to($student->email)
                                ->subject('✅ Votre projet "' . $project->title . '" a été validé !');
                        });
                    } catch (\Exception $e) {
                        Log::error('Erreur envoi email validation projet CM: ' . $e->getMessage());
                    }

                    // Redirection vers design-cm/pending si c'est un projet Design Graphique & Community Management
                    $redirectUrl = $this->getRedirectUrlBasedOnFormation($project);
                    return redirect($redirectUrl)->with('success', '✅ Projet validé avec succès ! Un email a été envoyé à l\'étudiant.');
                }
            }

            // Si pas trouvé, chercher dans tp (rapports)
            if (!$tp) {
                $tp = DB::table('tp')->where('id', $id)->first();

                if (!$tp) {
                    if ($request->expectsJson()) {
                        return response()->json(['success' => false, 'message' => 'TP introuvable'], 404);
                    }
                    return redirect()->back()->with('error', 'TP introuvable');
                }

                // Pour la table tp (rapports)
                $user = DB::table('users')->where('id', $tp->user_id)->first();

                DB::table('tp')->where('id', $id)->update([
                    'status' => 'validated',
                    'validated_at' => now(),
                    'updated_at' => now()
                ]);

                // Email pour rapport
                try {
                    Mail::send('emails.tp-validated', [
                        'user' => $user,
                        'tp' => $tp
                    ], function ($message) use ($user) {
                        $message->to($user->email)->subject('✅ Votre TP a été validé - EVC');
                    });
                } catch (\Exception $e) {
                    Log::warning('Erreur envoi email validation TP: ' . $e->getMessage());
                }

                return redirect()->back()->with('success', 'TP validé avec succès !');
            }

            // Pour tp_assignments
            DB::table('tp_assignments')->where('id', $id)->update([
                'status' => 'validated',
                'validated_at' => now(),
                'updated_at' => now()
            ]);

            // Notification in-app (database)
            try {
                $user = \App\Models\User::find($tp->user_id);
                if ($user) {
                    // Mapper la formation vers le bon slug de route
                    $formationSlug = $this->getFormationSlug($tp->formation ?? 'Design Graphique');

                    $user->notify(new TpStatusChangedNotification([
                        'category' => 'tp',
                        'event' => 'validated',
                        'title' => 'TP validé',
                        'message' => 'Votre TP "' . ($tp->title ?? 'TP') . '" a été validé.',
                        'assignment_id' => $tp->id,
                        'tp_title' => $tp->title ?? null,
                        'created_at' => now()->toIso8601String(),
                        'url' => url('/evc/compte/' . $formationSlug . '/tp/index'),
                    ]));
                }
            } catch (\Exception $e) {
                Log::warning('Notification in-app TP validé échouée', [
                    'tp_assignment_id' => $id,
                    'user_id' => $tp->user_id ?? null,
                    'error' => $e->getMessage(),
                ]);
            }

            // Créer objet student pour l'email
            $student = (object)[
                'first_name' => $tp->student_first_name,
                'last_name' => $tp->student_last_name,
                'email' => $tp->student_email,
            ];

            // Envoyer email de validation
            try {
                Mail::send('emails.tp_validated', [
                    'student' => $student,
                    'tp' => $tp
                ], function ($message) use ($student, $tp) {
                    $message->to($student->email)
                        ->subject('✅ Votre TP "' . $tp->title . '" a été validé !');
                });
            } catch (\Exception $e) {
                Log::error('Erreur envoi email validation TP: ' . $e->getMessage());
            }

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'TP validé avec succès !']);
            }

            // Redirection vers design-cm/pending si c'est un TP Design Graphique & Community Management
            $redirectUrl = $this->getRedirectUrlBasedOnFormation($tp);
            return redirect($redirectUrl)->with('success', '✅ TP validé avec succès ! Un email a été envoyé à l\'étudiant.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Erreur lors de la validation: ' . $e->getMessage());
        }
    }

    /**
     * Rejeter un TP (admin)
     */
    public function rejectTp(Request $request, int $id)
    {
        try {
            // Valider la raison
            $request->validate([
                'reason' => 'required|string|min:10'
            ]);

            $reason = $request->input('reason');

            // Si la demande vient de la page projets CM/SMM (table `projects`),
            // éviter toute collision d'ID avec `tp_assignments`.
            if ($request->input('source') === 'cm_project') {
                $project = DB::table('projects')
                    ->join('users', 'projects.user_id', '=', 'users.id')
                    ->leftJoin('students', 'users.id', '=', 'students.user_id')
                    ->where('projects.id', $id)
                    ->select(
                        'projects.*',
                        'students.first_name as student_first_name',
                        'students.last_name as student_last_name',
                        'users.email as student_email'
                    )
                    ->first();

                if (!$project) {
                    return redirect()->back()->with('error', 'Projet introuvable');
                }

                DB::table('projects')->where('id', $id)->update([
                    'status' => 'rejete',
                    'updated_at' => now(),
                ]);

                try {
                    $student = (object)[
                        'first_name' => $project->student_first_name,
                        'last_name' => $project->student_last_name,
                        'email' => $project->student_email,
                    ];

                    Mail::send('emails.tp_rejected', [
                        'student' => $student,
                        'tp' => $project,
                        'rejectionReason' => $reason,
                    ], function ($message) use ($student, $project) {
                        $message->to($student->email)
                            ->subject('📝 Votre projet "' . $project->title . '" nécessite des corrections');
                    });
                } catch (\Exception $e) {
                    Log::error('Erreur envoi email rejet projet CM (source cm_project): ' . $e->getMessage());
                }

                return redirect()->back()->with('success', '✅ Projet rejeté avec succès !');
            }

            if ($request->input('source') === 'tp_report') {
                $tpReport = DB::table('tp')->where('id', $id)->first();

                if (!$tpReport) {
                    return redirect()->back()->with('error', 'TP introuvable');
                }

                DB::table('tp')->where('id', $id)->update([
                    'status' => 'rejected',
                    'admin_comment' => $reason,
                    'updated_at' => now(),
                ]);

                return redirect()->back()->with('success', 'TP rejeté avec succès.');
            }

            // Chercher d'abord dans tp_assignments
            $tp = DB::table('tp_assignments')
                ->leftJoin('students', 'tp_assignments.student_id', '=', 'students.id')
                ->leftJoin('users', 'students.user_id', '=', 'users.id')
                ->where('tp_assignments.id', $id)
                ->select(
                    'tp_assignments.*',
                    'students.first_name as student_first_name',
                    'students.last_name as student_last_name',
                    'users.email as student_email'
                )
                ->first();

            // Si pas trouvé, chercher dans projects (TP CM)
            if (!$tp) {
                $project = DB::table('projects')
                    ->join('users', 'projects.user_id', '=', 'users.id')
                    ->leftJoin('students', 'users.id', '=', 'students.user_id')
                    ->where('projects.id', $id)
                    ->select(
                        'projects.*',
                        'students.first_name as student_first_name',
                        'students.last_name as student_last_name',
                        'users.email as student_email'
                    )
                    ->first();

                if ($project) {
                    // Rejeter le projet CM (statut français: rejete)
                    DB::table('projects')->where('id', $id)->update([
                        'status' => 'rejete',
                        'updated_at' => now()
                    ]);

                    // Email de rejet
                    try {
                        $student = (object)[
                            'first_name' => $project->student_first_name,
                            'last_name' => $project->student_last_name,
                            'email' => $project->student_email,
                        ];

                        Mail::send('emails.tp_rejected', [
                            'student' => $student,
                            'tp' => $project,
                            'rejectionReason' => $reason
                        ], function ($message) use ($student, $project) {
                            $message->to($student->email)
                                ->subject('📝 Votre projet "' . $project->title . '" nécessite des corrections');
                        });
                    } catch (\Exception $e) {
                        Log::error('Erreur envoi email rejet projet CM: ' . $e->getMessage());
                    }

                    // Redirection vers design-cm/pending si c'est un projet Design Graphique & Community Management
                    $redirectUrl = $this->getRedirectUrlBasedOnFormation($project);
                    return redirect($redirectUrl)->with('success', '✅ Projet rejeté avec succès ! Un email a été envoyé à l\'étudiant.');
                }
            }

            // Si pas trouvé, chercher dans tp (rapports)
            if (!$tp) {
                $tp = DB::table('tp')->where('id', $id)->first();

                if (!$tp) {
                    return redirect()->back()->with('error', 'TP introuvable');
                }

                // Pour la table tp (rapports)
                $user = DB::table('users')->where('id', $tp->user_id)->first();

                DB::table('tp')->where('id', $id)->update([
                    'status' => 'rejected',
                    'admin_comment' => $reason,
                    'updated_at' => now()
                ]);

                // Email pour rapport
                try {
                    Mail::send('emails.tp-rejected', [
                        'user' => $user,
                        'tp' => $tp,
                        'rejectionReason' => $reason
                    ], function ($message) use ($user) {
                        $message->to($user->email)->subject('📝 Votre TP nécessite des améliorations - EVC');
                    });
                } catch (\Exception $e) {
                    Log::warning('Erreur envoi email rejet TP: ' . $e->getMessage());
                }

                return redirect()->back()->with('success', '✅ TP rejeté avec succès !');
            }

            // Pour tp_assignments
            DB::table('tp_assignments')->where('id', $id)->update([
                'status' => 'rejected',
                'admin_comment' => $reason,
                'updated_at' => now()
            ]);

            // Notification in-app (database)
            try {
                $user = \App\Models\User::find($tp->user_id);
                if ($user) {
                    $user->notify(new TpStatusChangedNotification([
                        'category' => 'tp',
                        'event' => 'rejected',
                        'title' => 'TP à corriger',
                        'message' => 'Votre TP "' . ($tp->title ?? 'TP') . '" a été rejeté.',
                        'assignment_id' => $tp->id,
                        'tp_title' => $tp->title ?? null,
                        'reason' => $reason,
                        'created_at' => now()->toIso8601String(),
                        'url' => url('/evc/compte/' . strtolower(str_replace(' ', '-', ($tp->formation ?? 'design-graphique'))) . '/tp/index'),
                    ]));
                }
            } catch (\Exception $e) {
                Log::warning('Notification in-app TP rejeté échouée', [
                    'tp_assignment_id' => $id,
                    'user_id' => $tp->user_id ?? null,
                    'error' => $e->getMessage(),
                ]);
            }

            // Créer objet student pour l'email
            $student = (object)[
                'first_name' => $tp->student_first_name,
                'last_name' => $tp->student_last_name,
                'email' => $tp->student_email,
            ];

            // Envoyer email de rejet
            try {
                Mail::send('emails.tp_rejected', [
                    'student' => $student,
                    'tp' => $tp,
                    'rejectionReason' => $reason
                ], function ($message) use ($student, $tp) {
                    $message->to($student->email)
                        ->subject('📝 Votre TP "' . $tp->title . '" nécessite des améliorations');
                });
            } catch (\Exception $e) {
                Log::error('Erreur envoi email rejet TP: ' . $e->getMessage());
            }

            // Redirection vers design-cm/pending si c'est un TP Design Graphique & Community Management
            $redirectUrl = $this->getRedirectUrlBasedOnFormation($tp);
            return redirect($redirectUrl)->with('success', '✅ TP rejeté avec succès ! Un email a été envoyé à l\'étudiant.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Erreur lors du rejet: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un rapport/TP
     */
    public function deleteTp(Request $request, $id)
    {
        try {
            // Vérifier d'abord si c'est un projet CM dans la table projects
            $project = DB::table('projects')->where('id', $id)->first();

            if ($project) {
                // Supprimer les fichiers associés (project_images)
                $images = DB::table('project_images')->where('project_id', $id)->get();
                foreach ($images as $image) {
                    if (Storage::exists($image->file_path)) {
                        Storage::delete($image->file_path);
                    }
                }
                DB::table('project_images')->where('project_id', $id)->delete();

                // Supprimer le projet
                DB::table('projects')->where('id', $id)->delete();

                $redirectTo = $request->input('redirect_to', route('admin.projets.cm-smm.pending'));
                return redirect($redirectTo)->with('success', '✅ Projet supprimé avec succès');
            }

            // Vérifier si c'est un tp_assignment et récupérer les infos pour la redirection
            $tpAssignment = DB::table('tp_assignments')->where('id', $id)->first();

            // Supprimer les fichiers associés (tp_files)
            $files = DB::table('tp_files')->where('tp_id', $id)->get();
            foreach ($files as $file) {
                if (Storage::exists($file->file_path)) {
                    Storage::delete($file->file_path);
                }
                DB::table('tp_files')->where('id', $file->id)->delete();
            }

            // Supprimer fichiers de soumission tp_submission_files
            if ($tpAssignment) {
                $submissionFiles = DB::table('tp_submission_files')->where('tp_assignment_id', $id)->get();
                foreach ($submissionFiles as $file) {
                    if (Storage::exists('public/' . $file->file_path)) {
                        Storage::delete('public/' . $file->file_path);
                    }
                }
                DB::table('tp_submission_files')->where('tp_assignment_id', $id)->delete();
            }

            // Supprimer le TP depuis tp_assignments (travaux) ou tp (rapports)
            $deletedFromAssignments = DB::table('tp_assignments')->where('id', $id)->delete();
            if (!$deletedFromAssignments) {
                DB::table('tp')->where('id', $id)->delete();
            }

            // Redirection intelligente basée sur la formation
            if ($tpAssignment) {
                $redirectUrl = $this->getRedirectUrlBasedOnFormation($tpAssignment);
            } else {
                $redirectUrl = $request->input('redirect_to', route('admin.travaux.all'));
            }

            return redirect($redirectUrl)->with('success', '✅ TP supprimé avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du rapport: ' . $e->getMessage());
            $redirectTo = $request->input('redirect_to', route('admin.documents.all'));
            return redirect($redirectTo)->with('error', '❌ Erreur lors de la suppression');
        }
    }

    /**
     * Mettre à jour le statut d'un rapport/TP
     */
    public function updateTpStatus(Request $request, $id)
    {
        try {
            $status = $request->input('status', 'validated');

            // Récupérer les informations du rapport et de l'étudiant
            $rapport = DB::table('tp')
                ->join('users', 'tp.user_id', '=', 'users.id')
                ->leftJoin('students', 'users.id', '=', 'students.user_id')
                ->where('tp.id', $id)
                ->select(
                    'tp.*',
                    'users.name as user_name',
                    'users.email as user_email',
                    'students.program as formation'
                )
                ->first();

            if (!$rapport) {
                return redirect()->route('admin.documents.all')->with('error', '❌ Rapport introuvable');
            }

            // Mettre à jour le statut du TP
            DB::table('tp')->where('id', $id)->update([
                'status' => $status,
                'updated_at' => now()
            ]);

            // Envoyer un email à l'étudiant selon le statut
            if ($rapport->user_email) {
                try {
                    if ($status === 'validated') {
                        // Email de validation
                        Mail::send([], [], function ($message) use ($rapport) {
                            $message->to($rapport->user_email)
                                ->subject('✅ Votre rapport a été validé - École Virtuelle des Créatifs')
                                ->html("
                                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: #f8f9fa;'>
                                        <div style='background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%); padding: 30px; border-radius: 10px 10px 0 0; text-align: center;'>
                                            <h1 style='color: white; margin: 0;'>✅ Rapport Validé !</h1>
                                        </div>
                                        <div style='background: white; padding: 30px; border-radius: 0 0 10px 10px;'>
                                            <p style='font-size: 16px; color: #333;'>Bonjour <strong>{$rapport->user_name}</strong>,</p>
                                            <p style='font-size: 16px; color: #333; line-height: 1.6;'>
                                                Nous avons le plaisir de vous informer que votre rapport <strong>« {$rapport->title} »</strong> a été validé par l'administration.
                                            </p>
                                            <div style='background: #f0f9ff; padding: 20px; border-left: 4px solid #56ab2f; margin: 20px 0; border-radius: 5px;'>
                                                <p style='margin: 0; color: #333;'><strong>📋 Titre :</strong> {$rapport->title}</p>
                                                <p style='margin: 10px 0 0 0; color: #333;'><strong>📅 Date de validation :</strong> " . now()->format('d/m/Y à H:i') . "</p>
                                            </div>
                                            <p style='font-size: 16px; color: #333;'>
                                                Félicitations pour votre travail ! Vous pouvez consulter votre rapport validé dans votre espace étudiant.
                                            </p>
                                            <div style='text-align: center; margin: 30px 0;'>
                                                <a href='" . url('/evc/compte/' . strtolower(str_replace(' ', '-', $rapport->formation ?? 'community-management')) . '/documents/index') . "'
                                                   style='display: inline-block; background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 25px; font-weight: bold;'>
                                                    Voir mes rapports
                                                </a>
                                            </div>
                                            <hr style='border: none; border-top: 1px solid #e0e0e0; margin: 30px 0;'>
                                            <p style='font-size: 14px; color: #666; text-align: center;'>
                                                Cordialement,<br>
                                                <strong>L'équipe de l'École Virtuelle des Créatifs</strong>
                                            </p>
                                        </div>
                                    </div>
                                ");
                        });

                        Log::info('Email de validation envoyé', [
                            'rapport_id' => $id,
                            'student_email' => $rapport->user_email
                        ]);
                    } elseif ($status === 'rejected') {
                        // Email de rejet
                        Mail::send([], [], function ($message) use ($rapport) {
                            $message->to($rapport->user_email)
                                ->subject('❌ Votre rapport nécessite des modifications - École Virtuelle des Créatifs')
                                ->html("
                                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: #f8f9fa;'>
                                        <div style='background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); padding: 30px; border-radius: 10px 10px 0 0; text-align: center;'>
                                            <h1 style='color: white; margin: 0;'>❌ Rapport à Modifier</h1>
                                        </div>
                                        <div style='background: white; padding: 30px; border-radius: 0 0 10px 10px;'>
                                            <p style='font-size: 16px; color: #333;'>Bonjour <strong>{$rapport->user_name}</strong>,</p>
                                            <p style='font-size: 16px; color: #333; line-height: 1.6;'>
                                                Après examen de votre rapport <strong>« {$rapport->title} »</strong>, nous vous demandons d'y apporter des modifications.
                                            </p>
                                            <div style='background: #fff5f5; padding: 20px; border-left: 4px solid #eb3349; margin: 20px 0; border-radius: 5px;'>
                                                <p style='margin: 0; color: #333;'><strong>📋 Titre :</strong> {$rapport->title}</p>
                                                <p style='margin: 10px 0 0 0; color: #333;'><strong>📅 Date d'examen :</strong> " . now()->format('d/m/Y à H:i') . "</p>
                                            </div>
                                            <p style='font-size: 16px; color: #333;'>
                                                Veuillez consulter les commentaires de votre formateur et soumettre une nouvelle version de votre rapport.
                                            </p>
                                            <div style='text-align: center; margin: 30px 0;'>
                                                <a href='" . url('/evc/compte/' . strtolower(str_replace(' ', '-', $rapport->formation ?? 'community-management')) . '/documents/index') . "'
                                                   style='display: inline-block; background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 25px; font-weight: bold;'>
                                                    Voir mes rapports
                                                </a>
                                            </div>
                                            <hr style='border: none; border-top: 1px solid #e0e0e0; margin: 30px 0;'>
                                            <p style='font-size: 14px; color: #666; text-align: center;'>
                                                Cordialement,<br>
                                                <strong>L'équipe de l'École Virtuelle des Créatifs</strong>
                                            </p>
                                        </div>
                                    </div>
                                ");
                        });

                        Log::info('Email de rejet envoyé', [
                            'rapport_id' => $id,
                            'student_email' => $rapport->user_email
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::warning('Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
                    // Continue même si l'email échoue
                }
            }

            // Message selon le statut
            $message = $status === 'validated' ? '✅ Rapport validé avec succès ! Un email a été envoyé à l\'étudiant.' : '❌ Rapport rejeté';

            return redirect()->route('admin.documents.all')->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du statut: ' . $e->getMessage());
            return redirect()->route('admin.documents.all')->with('error', '❌ Erreur lors de la mise à jour du statut');
        }
    }

    /**
     * Afficher la CVthèque (liste des étudiants)
     */
    public function cvtheque(): View
    {
        // Récupérer tous les étudiants actifs
        $students = DB::table('students')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select(
                'students.id',
                'students.user_id',
                'students.first_name',
                'students.last_name',
                'students.profile_photo',
                'students.program as formation',
                'students.specialization',
                'students.phone',
                'students.status',
                'students.created_at',
                'users.email'
            )
            ->where('students.status', 'active')
            ->orderBy('students.created_at', 'desc')
            ->get();

        // Statistiques par formation
        $stats = [
            'total' => $students->count(),
            'design_graphique' => $students->where('formation', 'Design Graphique')->count(),
            'community_management' => $students->where('formation', 'Community Management')->count(),
            'gestion_informatique' => $students->where('formation', 'Gestion Informatique')->count(),
            'intelligence_artificielle' => $students->where('formation', 'Intelligence Artificielle')->count(),
        ];

        return view('admin.cvtheque', compact('students', 'stats'));
    }

    /**
     * Afficher les étudiants éligibles aux certificats
     */
    public function certificatsEligible()
    {
        // Récupérer tous les étudiants actifs avec leurs informations
        $students = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->select(
                'students.*',
                'users.email',
                'users.name as user_name',
                'users.created_at as user_created_at'
            )
            ->where('students.status', 'active')
            ->get();

        // Critères d'éligibilité
        $minTPRequired = 15;
        $minProjectsRequired = 4;

        // Filtrer les étudiants éligibles
        $eligibleStudents = [];

        foreach ($students as $student) {
            // Compter les TP validés
            $tpValidated = DB::table('tp_assignments')
                ->where('student_id', $student->id)
                ->where('status', 'validated')
                ->count();

            // Compter les projets validés
            $projectsCompleted = DB::table('projects')
                ->where('user_id', $student->user_id)
                ->where('status', 'valide')
                ->count();

            // Vérifier si rapport uploadé
            $report = DB::table('end_of_training_reports')
                ->where('student_id', $student->id)
                ->first();

            // Vérifier l'éligibilité
            $tpEligible = $tpValidated >= $minTPRequired;
            $projectsEligible = $projectsCompleted >= $minProjectsRequired;
            $reportUploaded = $report ? true : false;
            $paymentComplete = false; // À implémenter avec système de paiement

            // Si tous les critères sont remplis (sauf paiement pour l'instant)
            $isEligible = $tpEligible && $projectsEligible && $reportUploaded;

            if ($isEligible) {
                $eligibleStudents[] = [
                    'student' => $student,
                    'tp_validated' => $tpValidated,
                    'projects_completed' => $projectsCompleted,
                    'report' => $report,
                    'payment_complete' => $paymentComplete,
                    'is_eligible' => $isEligible,
                ];
            }
        }

        // Statistiques
        $stats = [
            'total_eligible' => count($eligibleStudents),
            'design_graphique' => collect($eligibleStudents)->where('student.program', 'Design Graphique')->count(),
            'community_management' => collect($eligibleStudents)->where('student.program', 'Community Management')->count(),
            'gestion_informatique' => collect($eligibleStudents)->where('student.program', 'Gestion Informatique')->count(),
            'intelligence_artificielle' => collect($eligibleStudents)->where('student.program', 'Intelligence Artificielle')->count(),
        ];

        return view('admin.certificats.eligible', [
            'eligibleStudents' => $eligibleStudents,
            'stats' => $stats,
            'minTPRequired' => $minTPRequired,
            'minProjectsRequired' => $minProjectsRequired,
        ]);
    }

    /**
     * Générer et télécharger le certificat pour un étudiant (Admin)
     */
    public function generateCertificate($id)
    {
        // Récupérer l'étudiant avec ses informations
        $student = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('students.id', $id)
            ->select(
                'students.*',
                'users.email',
                'users.name as user_name'
            )
            ->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Étudiant non trouvé');
        }

        // Vérifier l'éligibilité
        $tpValidated = DB::table('tp_assignments')
            ->where('student_id', $student->id)
            ->where('status', 'validated')
            ->count();

        $projectsCompleted = DB::table('projects')
            ->where('user_id', $student->user_id)
            ->where('status', 'valide')
            ->count();

        $report = DB::table('end_of_training_reports')
            ->where('student_id', $student->id)
            ->first();

        // Critères minimums
        $minTPRequired = 15;
        $minProjectsRequired = 4;

        $tpEligible = $tpValidated >= $minTPRequired;
        $projectsEligible = $projectsCompleted >= $minProjectsRequired;
        $reportUploaded = $report ? true : false;

        $isEligible = $tpEligible && $projectsEligible && $reportUploaded;

        if (!$isEligible) {
            return redirect()->back()->with('error', 'Cet étudiant ne remplit pas encore tous les critères d\'éligibilité.');
        }

        // Générer le certificat PDF à partir du template personnalisé
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

            // Générer le certificat selon la formation (avec gestion des variantes)
            $formation = strtolower($student->program ?? '');

            if (str_contains($formation, 'design') && str_contains($formation, 'graphique')) {
                // Design Graphique
                $certificatePath = $certificateGenerator->generateDesignGraphique($data);
            } elseif (str_contains($formation, 'community') || str_contains($formation, 'social media')) {
                // Community Management / Social Media Marketing
                $certificatePath = $certificateGenerator->generateCommunityManagement($data);
            } elseif (str_contains($formation, 'gestion') && str_contains($formation, 'informatique')) {
                // Gestion Informatique
                $certificatePath = $certificateGenerator->generateGestionInformatique($data);
            } elseif (str_contains($formation, 'intelligence') && str_contains($formation, 'artificielle')) {
                // Intelligence Artificielle
                $certificatePath = $certificateGenerator->generateIntelligenceArtificielle($data);
            } else {
                // Par défaut, utiliser Design Graphique si formation inconnue
                $certificatePath = $certificateGenerator->generateDesignGraphique($data);
            }

            $filename = 'Certificat_' . str_replace(' ', '_', $student->first_name . '_' . $student->last_name) . '_' . now()->format('Y') . '.pdf';

            // Enregistrer dans la base de données qu'un certificat a été généré
            DB::table('certificates')->insert([
                'student_id' => $student->id,
                'user_id' => $student->user_id,
                'formation' => $student->program,
                'generated_by' => auth()->id(),
                'generated_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Télécharger le certificat
            return $certificateGenerator->download($certificatePath, $filename);
        } catch (\Exception $e) {
            Log::error('Erreur génération certificat admin: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la génération du certificat: ' . $e->getMessage());
        }
    }

    /**
     * Prévisualiser le certificat dans le navigateur (Admin)
     */
    public function previewCertificate($id)
    {
        // Récupérer l'étudiant avec ses informations
        $student = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('students.id', $id)
            ->select(
                'students.*',
                'users.email',
                'users.name as user_name'
            )
            ->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Étudiant non trouvé');
        }

        // Pour la prévisualisation admin, on génère le certificat même si non éligible
        // Générer le certificat PDF à partir du template personnalisé
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
                $certificatePath = $certificateGenerator->generateCommunityManagement($data);
            }

            // Afficher le PDF dans le navigateur (inline)
            return response()->file($certificatePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Certificat_Preview.pdf"'
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Erreur prévisualisation certificat admin: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la prévisualisation du certificat: ' . $e->getMessage());
        }
    }

    /**
     * Afficher les étudiants non éligibles aux certificats
     */
    public function certificatsNotEligible()
    {
        // Récupérer tous les étudiants actifs avec leurs informations
        $students = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->select(
                'students.*',
                'users.email',
                'users.name as user_name',
                'users.created_at as user_created_at'
            )
            ->where('students.status', 'active')
            ->get();

        // Critères d'éligibilité
        $minTPRequired = 15;
        $minProjectsRequired = 4;

        // Filtrer les étudiants NON éligibles
        $notEligibleStudents = [];

        foreach ($students as $student) {
            // Compter les TP validés
            $tpValidated = DB::table('tp_assignments')
                ->where('student_id', $student->id)
                ->where('status', 'validated')
                ->count();

            // Compter les projets validés
            $projectsCompleted = DB::table('projects')
                ->where('user_id', $student->user_id)
                ->where('status', 'valide')
                ->count();

            // Vérifier si rapport uploadé
            $report = DB::table('end_of_training_reports')
                ->where('student_id', $student->id)
                ->first();

            // Vérifier l'éligibilité
            $tpEligible = $tpValidated >= $minTPRequired;
            $projectsEligible = $projectsCompleted >= $minProjectsRequired;
            $reportUploaded = $report ? true : false;

            // Si au moins un critère n'est pas rempli
            $isNotEligible = !$tpEligible || !$projectsEligible || !$reportUploaded;

            if ($isNotEligible) {
                // Calculer ce qui manque
                $missing = [];
                if (!$tpEligible) {
                    $missing[] = ($minTPRequired - $tpValidated) . ' TP';
                }
                if (!$projectsEligible) {
                    $missing[] = ($minProjectsRequired - $projectsCompleted) . ' projet(s)';
                }
                if (!$reportUploaded) {
                    $missing[] = 'Rapport';
                }

                $notEligibleStudents[] = [
                    'student' => $student,
                    'tp_validated' => $tpValidated,
                    'tp_required' => $minTPRequired,
                    'tp_eligible' => $tpEligible,
                    'projects_completed' => $projectsCompleted,
                    'projects_required' => $minProjectsRequired,
                    'projects_eligible' => $projectsEligible,
                    'report' => $report,
                    'report_uploaded' => $reportUploaded,
                    'missing' => $missing,
                ];
            }
        }

        // Statistiques
        $stats = [
            'total_not_eligible' => count($notEligibleStudents),
            'missing_tp' => collect($notEligibleStudents)->where('tp_eligible', false)->count(),
            'missing_projects' => collect($notEligibleStudents)->where('projects_eligible', false)->count(),
            'missing_report' => collect($notEligibleStudents)->where('report_uploaded', false)->count(),
        ];

        return view('admin.certificats.not-eligible', [
            'notEligibleStudents' => $notEligibleStudents,
            'stats' => $stats,
            'minTPRequired' => $minTPRequired,
            'minProjectsRequired' => $minProjectsRequired,
        ]);
    }

    /**
     * Afficher la page des rapports et analytics
     */
    public function rapports(): View
    {
        // Récupérer les statistiques réelles
        $totalStudents = DB::table('students')->count();
        $totalFormations = DB::table('formations')->count();

        // Vérifier si la table payments existe
        $totalPayments = 0;
        $monthlyExports = 0;

        if (Schema::hasTable('payments')) {
            $totalPayments = DB::table('payments')->where('status', 'completed')->sum('amount');
            $monthlyExports = DB::table('payments')
                ->whereMonth('created_at', now()->month)
                ->count();
        } elseif (Schema::hasTable('factures')) {
            $totalPayments = DB::table('factures')->sum('montant');
            $monthlyExports = DB::table('factures')
                ->whereMonth('created_at', now()->month)
                ->count();
        }

        $totalTPs = DB::table('tp_assignments')->count();

        $stats = [
            'total_reports' => $totalStudents + $totalFormations,
            'monthly_exports' => $monthlyExports,
            'active_analytics' => 12,
            'scheduled_reports' => 8,
            'total_students' => $totalStudents,
            'total_formations' => $totalFormations,
            'total_payments' => $totalPayments,
            'total_tps' => $totalTPs,
        ];

        return view('admin.rapports.index', compact('stats'));
    }

    /**
     * Afficher les analytics
     */
    public function analytics(): View
    {
        return view('admin.rapports.analytics');
    }

    /**
     * Afficher les exports
     */
    public function exports(): View
    {
        return view('admin.rapports.exports');
    }

    /**
     * Générer un rapport
     */
    public function generateReport(Request $request)
    {
        $type = $request->input('type');

        try {
            $reportData = [];

            switch ($type) {
                case 'students':
                    $reportData = $this->generateStudentsReport();
                    break;

                case 'formations':
                    $reportData = $this->generateFormationsReport();
                    break;

                case 'financial':
                    $reportData = $this->generateFinancialReport();
                    break;

                case 'activities':
                    $reportData = $this->generateActivitiesReport();
                    break;

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Type de rapport non reconnu'
                    ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Rapport ' . $type . ' généré avec succès',
                'data' => $reportData
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur génération rapport: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du rapport'
            ], 500);
        }
    }

    /**
     * Générer le rapport des étudiants
     */
    private function generateStudentsReport()
    {
        $legacyInactiveStudents = 1509;

        $students = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->select(
                'students.*',
                'users.email',
                DB::raw('(SELECT COUNT(*) FROM tp_assignments WHERE student_id = students.id AND status = "validated") as tp_validated'),
                DB::raw('(SELECT COUNT(*) FROM projects WHERE user_id = students.user_id AND status = "valide") as projects_completed')
            )
            ->get();

        $inactiveStudentsDb = $students->filter(function ($s) {
            return ($s->status ?? null) !== 'active';
        })->count();

        $byFormation = $students->groupBy('program')->map(function ($group) {
            return [
                'total' => $group->count(),
                'active' => $group->where('status', 'active')->count(),
            ];
        });

        return [
            'total_students' => $students->count(),
            'inactive_students_db' => $inactiveStudentsDb,
            'legacy_inactive_students' => $legacyInactiveStudents + $inactiveStudentsDb,
            'total_students_including_legacy' => $students->count() + $legacyInactiveStudents,
            'active_students' => $students->where('status', 'active')->count(),
            'by_formation' => $byFormation,
            'avg_tp_validated' => round($students->avg('tp_validated'), 2),
            'avg_projects_completed' => round($students->avg('projects_completed'), 2),
        ];
    }

    /**
     * Générer le rapport des formations
     */
    private function generateFormationsReport()
    {
        $formations = DB::table('formations')
            ->select('*')
            ->get();

        $stats = [];
        foreach ($formations as $formation) {
            $enrolledCount = DB::table('students')
                ->where('program', $formation->module)
                ->count();

            $stats[] = [
                'name' => $formation->title,
                'module' => $formation->module,
                'enrolled' => $enrolledCount,
                'created_at' => $formation->created_at,
            ];
        }

        return [
            'total_formations' => $formations->count(),
            'formations' => $stats,
        ];
    }

    /**
     * Générer le rapport financier
     */
    private function generateFinancialReport()
    {
        $totalRevenue = 0;
        $monthlyRevenue = 0;
        $pendingPayments = 0;
        $totalInvoices = 0;

        // Vérifier les tables disponibles
        if (Schema::hasTable('payments')) {
            $totalRevenue = DB::table('payments')
                ->where('status', 'completed')
                ->sum('amount');

            $monthlyRevenue = DB::table('payments')
                ->where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('amount');

            $pendingPayments = DB::table('payments')
                ->where('status', 'pending')
                ->sum('amount');
        }

        if (Schema::hasTable('factures')) {
            $totalInvoices = DB::table('factures')->sum('montant');
        }

        return [
            'total_revenue' => $totalRevenue,
            'monthly_revenue' => $monthlyRevenue,
            'pending_payments' => $pendingPayments,
            'total_invoices' => $totalInvoices,
            'balance' => $totalInvoices - $totalRevenue,
        ];
    }

    /**
     * Générer le rapport des activités
     */
    private function generateActivitiesReport()
    {
        $totalTPs = DB::table('tp_assignments')->count();
        $validatedTPs = DB::table('tp_assignments')->where('status', 'validated')->count();
        $pendingTPs = DB::table('tp_assignments')->where('status', 'pending')->count();

        $totalProjects = DB::table('projects')->count();
        $completedProjects = DB::table('projects')->where('status', 'valide')->count();

        return [
            'total_tps' => $totalTPs,
            'validated_tps' => $validatedTPs,
            'pending_tps' => $pendingTPs,
            'total_projects' => $totalProjects,
            'completed_projects' => $completedProjects,
            'completion_rate_tps' => $totalTPs > 0 ? round(($validatedTPs / $totalTPs) * 100, 2) : 0,
            'completion_rate_projects' => $totalProjects > 0 ? round(($completedProjects / $totalProjects) * 100, 2) : 0,
        ];
    }

    /**
     * Afficher la page détaillée du rapport financier
     */
    public function rapportFinancier(): View
    {
        // Données financières
        $financial = $this->generateFinancialReport();

        // Si pas de données réelles, utiliser des données de démonstration
        $hasRealData = Schema::hasTable('payments') && DB::table('payments')->count() > 0;

        if (!$hasRealData) {
            // Données de démonstration
            $financial = [
                'total_revenue' => 15750000,
                'monthly_revenue' => 2500000,
                'pending_payments' => 1250000,
                'total_invoices' => 18500000,
                'balance' => 2750000,
            ];
        }

        // Revenus mensuels (12 derniers mois)
        $monthlyRevenues = [];
        if ($hasRealData) {
            for ($i = 11; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $revenue = DB::table('payments')
                    ->where('status', 'completed')
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('amount');
                $monthlyRevenues[] = $revenue;
            }
        } else {
            // Données de démonstration pour les 12 derniers mois
            $monthlyRevenues = [850000, 920000, 1050000, 1150000, 1300000, 1450000, 1250000, 1400000, 1550000, 1650000, 1800000, 2500000];
        }

        // Répartition des paiements
        if ($hasRealData) {
            $completedCount = DB::table('payments')->where('status', 'completed')->count();
            $pendingCount = DB::table('payments')->where('status', 'pending')->count();
            $failedCount = DB::table('payments')->whereIn('status', ['failed', 'cancelled'])->count();
        } else {
            // Données de démonstration
            $completedCount = 45;
            $pendingCount = 8;
            $failedCount = 2;
        }

        $paymentDistribution = [$completedCount, $pendingCount, $failedCount];

        // Dernières transactions
        if ($hasRealData) {
            $transactions = DB::table('payments')
                ->leftJoin('students', 'payments.student_id', '=', 'students.id')
                ->select(
                    'payments.*',
                    DB::raw("CONCAT(students.first_name, ' ', students.last_name) as student_name"),
                    'students.program as formation'
                )
                ->orderBy('payments.created_at', 'desc')
                ->limit(20)
                ->get();
        } else {
            // Données de démonstration
            $transactions = collect([
                (object)[
                    'id' => 1,
                    'created_at' => now()->subDays(2),
                    'student_name' => 'Marie KOUASSI',
                    'formation' => 'Community Management',
                    'amount' => 350000,
                    'status' => 'completed'
                ],
                (object)[
                    'id' => 2,
                    'created_at' => now()->subDays(5),
                    'student_name' => 'Jean Baptiste ENOKOU',
                    'formation' => 'Design Graphique',
                    'amount' => 350000,
                    'status' => 'completed'
                ],
                (object)[
                    'id' => 3,
                    'created_at' => now()->subDays(7),
                    'student_name' => 'Fatou Rebecca ZIRE',
                    'formation' => 'Community Management',
                    'amount' => 350000,
                    'status' => 'pending'
                ],
                (object)[
                    'id' => 4,
                    'created_at' => now()->subDays(10),
                    'student_name' => 'Mathieu TÉYOTONMIN',
                    'formation' => 'Gestion Informatique',
                    'amount' => 350000,
                    'status' => 'completed'
                ],
                (object)[
                    'id' => 5,
                    'created_at' => now()->subDays(12),
                    'student_name' => 'Bianca DEFO',
                    'formation' => 'Design Graphique',
                    'amount' => 350000,
                    'status' => 'completed'
                ],
            ]);
        }

        return view('admin.rapports.financier', compact('financial', 'monthlyRevenues', 'paymentDistribution', 'transactions'));
    }

    /**
     * Afficher la page détaillée du rapport formations
     */
    public function rapportFormations(): View
    {
        // Vue d'ensemble
        $totalFormations = DB::table('formations')->count();
        $totalStudents = DB::table('students')->count();
        $totalModules = 0; // À implémenter selon la structure

        // Taux de réussite moyen
        $avgSuccessRate = DB::table('students')
            ->leftJoin('tp_assignments', 'students.id', '=', 'tp_assignments.student_id')
            ->selectRaw('COUNT(CASE WHEN tp_assignments.status = "validated" THEN 1 END) * 100.0 / NULLIF(COUNT(tp_assignments.id), 0) as success_rate')
            ->value('success_rate') ?? 0;

        $overview = [
            'total_formations' => $totalFormations,
            'total_students' => $totalStudents,
            'avg_success_rate' => $avgSuccessRate,
            'total_modules' => $totalModules,
        ];

        // Détails par formation
        $formations = DB::table('students')
            ->select(
                'program as name',
                DB::raw('COUNT(DISTINCT students.id) as students_count'),
                DB::raw('COUNT(CASE WHEN tp_assignments.status = "validated" THEN 1 END) as completed_tps'),
                DB::raw('COUNT(CASE WHEN tp_assignments.status IN ("pending", "submitted") THEN 1 END) as pending_tps'),
                DB::raw('ROUND(COUNT(CASE WHEN tp_assignments.status = "validated" THEN 1 END) * 100.0 / NULLIF(COUNT(tp_assignments.id), 0), 1) as tp_completion_rate'),
                DB::raw('ROUND(COUNT(CASE WHEN tp_assignments.status = "validated" THEN 1 END) * 100.0 / NULLIF(COUNT(tp_assignments.id), 0), 1) as success_rate'),
                DB::raw('"Intermédiaire" as level'),
                DB::raw('6 as duration'),
                DB::raw('15 as avg_grade')
            )
            ->leftJoin('tp_assignments', 'students.id', '=', 'tp_assignments.student_id')
            ->whereNotNull('program')
            ->groupBy('program')
            ->get();

        // Ajouter des modules fictifs pour démonstration
        foreach ($formations as $formation) {
            $formation->modules = collect([]);
        }

        // Données pour graphiques
        $formationsNames = $formations->pluck('name')->toArray();
        $formationsStudents = $formations->pluck('students_count')->toArray();
        $formationsSuccessRates = $formations->pluck('success_rate')->toArray();

        return view('admin.rapports.formations', compact('overview', 'formations', 'formationsNames', 'formationsStudents', 'formationsSuccessRates'));
    }

    /**
     * Télécharger un rapport
     */
    public function downloadReport($type)
    {
        // TODO: Implémenter la logique de téléchargement de rapports

        return redirect()->back()->with('success', 'Téléchargement du rapport ' . $type);
    }

    /**
     * Afficher les étudiants à jour avec leurs paiements
     */
    public function paiementsAJour(): View
    {
        $formationTotals = (array) config('chariow.formation_amounts', []);

        $studentsBase = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->leftJoin('pre_registrations', 'pre_registrations.email', '=', 'students.email')
            ->select(
                'students.*',
                'users.email as user_email',
                'pre_registrations.id as pre_registration_id',
                'pre_registrations.choix_formation as choix_formation'
            )
            ->where('students.status', 'active')
            ->orderBy('students.created_at', 'desc')
            ->get();

        $preRegIds = $studentsBase->pluck('pre_registration_id')->filter()->unique()->values()->toArray();

        $paymentAgg = collect();
        if (!empty($preRegIds)) {
            $paymentAgg = DB::table('payments')
                ->select(
                    'pre_registration_id',
                    DB::raw("COALESCE(MAX(total_amount), 0) as total_amount"),
                    DB::raw("SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as amount_paid"),
                    DB::raw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_count")
                )
                ->whereIn('pre_registration_id', $preRegIds)
                ->groupBy('pre_registration_id')
                ->get()
                ->keyBy('pre_registration_id');
        }

        $students = $studentsBase->map(function ($s) use ($paymentAgg, $formationTotals) {
            $agg = $s->pre_registration_id ? ($paymentAgg[$s->pre_registration_id] ?? null) : null;

            $totalAmount = (int) round((float) ($agg->total_amount ?? 0));
            $amountPaid = (int) round((float) ($agg->amount_paid ?? 0));

            if ($totalAmount <= 0) {
                $formationLabel = $s->choix_formation ?: ($s->program ?? null);
                $totalAmount = (int) (($formationTotals[$formationLabel]['total'] ?? 0) ?: 0);
            }

            $remaining = max(0, $totalAmount - $amountPaid);

            $s->payment_status = 'À jour';
            $s->amount_paid = $amountPaid;
            $s->total_amount = $totalAmount;
            $s->remaining = $remaining;
            $s->email = $s->user_email ?? $s->email;
            return $s;
        })
            ->filter(fn($s) => ($s->total_amount ?? 0) > 0 && ($s->remaining ?? 0) <= 0)
            ->values();

        $stats = [
            'total' => $students->count(),
            'percentage' => 100,
            'total_amount' => $students->sum('amount_paid'),
            'amount_per_student' => $students->count() > 0 ? (int) round($students->avg('total_amount')) : 0,
        ];

        return view('admin.paiements.a-jour', compact('students', 'stats'));
    }

    /**
     * Afficher les étudiants avec paiements à solder
     */
    public function paiementsASolder(): View
    {
        $formationTotals = (array) config('chariow.formation_amounts', []);

        $studentsBase = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->leftJoin('pre_registrations', 'pre_registrations.email', '=', 'students.email')
            ->select(
                'students.*',
                'users.email as user_email',
                'pre_registrations.id as pre_registration_id',
                'pre_registrations.choix_formation as choix_formation'
            )
            ->where('students.status', 'active')
            ->orderBy('students.created_at', 'desc')
            ->get();

        $preRegIds = $studentsBase->pluck('pre_registration_id')->filter()->unique()->values()->toArray();

        $paymentAgg = collect();
        if (!empty($preRegIds)) {
            $paymentAgg = DB::table('payments')
                ->select(
                    'pre_registration_id',
                    DB::raw("COALESCE(MAX(total_amount), 0) as total_amount"),
                    DB::raw("SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as amount_paid")
                )
                ->whereIn('pre_registration_id', $preRegIds)
                ->groupBy('pre_registration_id')
                ->get()
                ->keyBy('pre_registration_id');
        }

        $students = $studentsBase->map(function ($s) use ($paymentAgg, $formationTotals) {
            $agg = $s->pre_registration_id ? ($paymentAgg[$s->pre_registration_id] ?? null) : null;

            $totalAmount = (int) round((float) ($agg->total_amount ?? 0));
            $amountPaid = (int) round((float) ($agg->amount_paid ?? 0));

            if ($totalAmount <= 0) {
                $formationLabel = $s->choix_formation ?: ($s->program ?? null);
                $totalAmount = (int) (($formationTotals[$formationLabel]['total'] ?? 0) ?: 0);
            }

            $remaining = max(0, $totalAmount - $amountPaid);

            $s->payment_status = 'Partiel';
            $s->amount_paid = $amountPaid;
            $s->total_amount = $totalAmount;
            $s->remaining = $remaining;
            $s->email = $s->user_email ?? $s->email;
            return $s;
        })
            ->filter(fn($s) => ($s->total_amount ?? 0) > 0 && ($s->amount_paid ?? 0) > 0 && ($s->remaining ?? 0) > 0)
            ->values();

        $stats = [
            'total' => $students->count(),
            'total_paid' => $students->sum('amount_paid'),
            'total_remaining' => $students->sum('remaining'),
        ];

        return view('admin.paiements.a-solder', compact('students', 'stats'));
    }

    /**
     * Télécharger un reçu PDF pour une pré-inscription (agrège les paiements liés)
     */
    public function downloadPaymentReceipt($preRegistrationId)
    {
        try {
            $preRegistrationId = (int) $preRegistrationId;

            $preReg = DB::table('pre_registrations')->where('id', $preRegistrationId)->first();
            if (!$preReg) {
                return redirect()->back()->with('error', 'Préinscription introuvable.');
            }

            $student = DB::table('students')
                ->leftJoin('users', 'students.user_id', '=', 'users.id')
                ->where('students.email', $preReg->email)
                ->select('students.*', 'users.email as user_email')
                ->first();

            $payments = DB::table('payments')
                ->where('pre_registration_id', $preRegistrationId)
                ->orderByRaw("CASE WHEN paid_at IS NULL THEN 1 ELSE 0 END")
                ->orderBy('paid_at', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();

            if ($payments->isEmpty()) {
                return redirect()->back()->with('error', 'Aucun paiement trouvé pour cette préinscription.');
            }

            $formationTotals = (array) config('chariow.formation_amounts', []);
            $formationLabel = $preReg->choix_formation ?? (($student->program ?? null) ?: 'Formation');

            $totalAmount = (int) round((float) ($payments->max('total_amount') ?? 0));
            if ($totalAmount <= 0) {
                $totalAmount = (int) (($formationTotals[$formationLabel]['total'] ?? 0) ?: 0);
            }

            $amountPaid = (int) round((float) $payments->where('status', 'completed')->sum('amount'));
            $remaining = max(0, $totalAmount - $amountPaid);

            $studentName = trim((($student->first_name ?? null) ?: ($preReg->prenom ?? '')) . ' ' . (($student->last_name ?? null) ?: ($preReg->nom ?? '')));
            $studentEmail = ($student->user_email ?? null) ?: (($student->email ?? null) ?: $preReg->email);

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

            $receiptNumber = 'EVC-RC-' . str_pad((string) $preRegistrationId, 6, '0', STR_PAD_LEFT) . '-' . now()->format('Ymd');

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

            $downloadName = 'Recu_' . str_replace(' ', '_', $studentName ?: 'Etudiant') . '_' . now()->format('Ymd_His') . '.pdf';

            $primaryRef = '';
            $primaryPayment = $payments->firstWhere('payment_reference', '!=', null);
            if ($primaryPayment && !empty($primaryPayment->payment_reference)) {
                $primaryRef = (string) $primaryPayment->payment_reference;
            }

            $generator = new PaymentReceiptGenerator();
            $result = $generator->generate([
                'receipt_number' => $receiptNumber,
                'issued_at' => now()->format('d/m/Y H:i'),
                'student_name' => $studentName,
                'student_email' => $studentEmail,
                'formation' => $formationLabel,
                'student_id' => $studentIdLabel,
                'registration_date' => $registrationDate,
                'payment_reference' => $primaryRef,
                'total_amount' => $totalAmount,
                'amount_paid' => $amountPaid,
                'remaining' => $remaining,
                'payments' => $paymentsForPdf,
            ]);

            return response()->download($result['path'], $downloadName)->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            Log::error('Erreur génération reçu paiement', [
                'pre_registration_id' => $preRegistrationId,
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Impossible de générer le reçu PDF pour le moment.');
        }
    }

    /**
     * Afficher les étudiants avec reste à payer
     */
    public function paiementsResteAPayer(): View
    {
        $formationTotals = (array) config('chariow.formation_amounts', []);

        $studentsBase = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->leftJoin('pre_registrations', 'pre_registrations.email', '=', 'students.email')
            ->select(
                'students.*',
                'users.email as user_email',
                'pre_registrations.id as pre_registration_id',
                'pre_registrations.choix_formation as choix_formation'
            )
            ->where('students.status', 'active')
            ->orderBy('students.created_at', 'desc')
            ->get();

        $preRegIds = $studentsBase->pluck('pre_registration_id')->filter()->unique()->values()->toArray();

        $paymentAgg = collect();
        if (!empty($preRegIds)) {
            $paymentAgg = DB::table('payments')
                ->select(
                    'pre_registration_id',
                    DB::raw("COALESCE(MAX(total_amount), 0) as total_amount"),
                    DB::raw("SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as amount_paid")
                )
                ->whereIn('pre_registration_id', $preRegIds)
                ->groupBy('pre_registration_id')
                ->get()
                ->keyBy('pre_registration_id');
        }

        $students = $studentsBase->map(function ($s) use ($paymentAgg, $formationTotals) {
            $agg = $s->pre_registration_id ? ($paymentAgg[$s->pre_registration_id] ?? null) : null;

            $totalAmount = (int) round((float) ($agg->total_amount ?? 0));
            $amountPaid = (int) round((float) ($agg->amount_paid ?? 0));

            if ($totalAmount <= 0) {
                $formationLabel = $s->choix_formation ?: ($s->program ?? null);
                $totalAmount = (int) (($formationTotals[$formationLabel]['total'] ?? 0) ?: 0);
            }

            $remaining = max(0, $totalAmount - $amountPaid);

            $s->payment_status = 'Non payé';
            $s->amount_paid = $amountPaid;
            $s->total_amount = $totalAmount;
            $s->remaining = $remaining;
            $s->email = $s->user_email ?? $s->email;
            return $s;
        })
            ->filter(fn($s) => ($s->total_amount ?? 0) > 0 && ($s->remaining ?? 0) > 0 && ($s->amount_paid ?? 0) < ($s->total_amount ?? 0))
            ->values();

        if (Schema::hasTable('payment_reminders')) {
            $studentIds = $students->pluck('id')->filter()->unique()->values()->toArray();

            $reminderCounts = empty($studentIds)
                ? collect()
                : DB::table('payment_reminders')
                ->select('student_id', DB::raw('COUNT(*) as reminders_count'))
                ->whereIn('student_id', $studentIds)
                ->groupBy('student_id')
                ->get()
                ->keyBy('student_id');

            $students = $students->map(function ($s) use ($reminderCounts) {
                $s->reminders_count = (int) (($reminderCounts[$s->id]->reminders_count ?? 0) ?: 0);
                return $s;
            });
        } else {
            $students = $students->map(function ($s) {
                $s->reminders_count = 0;
                return $s;
            });
        }

        $stats = [
            'total' => $students->count(),
            'total_amount_due' => $students->sum('remaining'),
        ];

        return view('admin.paiements.reste-a-payer', compact('students', 'stats'));
    }

    public function editPaiementRestant($preRegistrationId)
    {
        $preRegistrationId = (int) $preRegistrationId;

        $preReg = DB::table('pre_registrations')->where('id', $preRegistrationId)->first();
        if (!$preReg) {
            return redirect()->route('admin.paiements.a-solder')->with('error', 'Préinscription introuvable.');
        }

        $student = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('students.email', $preReg->email)
            ->select(
                'students.*',
                'users.email as user_email'
            )
            ->first();

        $payments = DB::table('payments')
            ->where('pre_registration_id', $preRegistrationId)
            ->get();

        $amountPaid = (int) round((float) $payments->where('status', 'completed')->sum('amount'));
        $totalAmount = (int) round((float) ($payments->max('total_amount') ?? 0));
        $remaining = max(0, $totalAmount - $amountPaid);

        $displayEmail = $student->user_email ?? ($student->email ?? $preReg->email);

        return view('admin.paiements.edit-restant', [
            'preReg' => $preReg,
            'student' => $student,
            'email' => $displayEmail,
            'amountPaid' => $amountPaid,
            'totalAmount' => $totalAmount,
            'remaining' => $remaining,
        ]);
    }

    public function updatePaiementRestant(Request $request, $preRegistrationId)
    {
        $preRegistrationId = (int) $preRegistrationId;

        $validated = $request->validate([
            'remaining' => 'required|integer|min:0',
        ]);

        $preReg = DB::table('pre_registrations')->where('id', $preRegistrationId)->first();
        if (!$preReg) {
            return redirect()->route('admin.paiements.a-solder')->with('error', 'Préinscription introuvable.');
        }

        $payments = DB::table('payments')
            ->where('pre_registration_id', $preRegistrationId)
            ->get();

        if ($payments->isEmpty()) {
            return redirect()->route('admin.paiements.a-solder')->with('error', 'Aucun paiement trouvé pour cette préinscription.');
        }

        $amountPaid = (int) round((float) $payments->where('status', 'completed')->sum('amount'));
        $newRemaining = (int) $validated['remaining'];
        $newTotalAmount = $amountPaid + $newRemaining;

        DB::table('payments')
            ->where('pre_registration_id', $preRegistrationId)
            ->update([
                'total_amount' => $newTotalAmount,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.paiements.a-solder')
            ->with('success', 'Montant restant mis à jour avec succès.');
    }

    /**
     * Envoyer un email de relance de paiement à un étudiant
     */
    public function sendPaymentReminder($id)
    {
        try {
            // Récupérer les informations de l'étudiant depuis la base de données
            $student = DB::table('students')
                ->leftJoin('users', 'students.user_id', '=', 'users.id')
                ->where('students.id', $id)
                ->select(
                    'students.*',
                    'users.email'
                )
                ->first();

            // Si l'étudiant n'existe pas en base, utiliser les données de démonstration
            if (!$student) {
                // Étudiant de démonstration pour les tests
                if ($id == 3) {
                    $studentData = [
                        'first_name' => 'Kofi',
                        'last_name' => 'ASSANE',
                        'formation' => 'Gestion Informatique',
                        'amount_paid' => 0,
                        'remaining' => 350000,
                        'created_at' => now()->subWeeks(2),
                    ];

                    $emailTo = 'mae2pcmk2025@gmail.com';
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Étudiant introuvable'
                    ], 404);
                }
            } else {
                // Étudiant réel trouvé en base
                if (!$student->email) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Aucun email associé à cet étudiant'
                    ], 400);
                }

                // Calculer le restant à payer depuis payments si possible
                $amountPaid = 0;
                $totalAmount = 0;
                $remaining = 0;
                $formationTotals = (array) config('chariow.formation_amounts', []);

                $preReg = DB::table('pre_registrations')
                    ->where('email', $student->email)
                    ->first();

                if ($preReg) {
                    $payments = DB::table('payments')
                        ->where('pre_registration_id', $preReg->id)
                        ->get();

                    $totalAmount = (int) round((float) ($payments->max('total_amount') ?? 0));
                    $amountPaid = (int) round((float) $payments->where('status', 'completed')->sum('amount'));

                    if ($totalAmount <= 0) {
                        $formationLabel = $preReg->choix_formation ?? ($student->program ?? null);
                        $totalAmount = (int) (($formationTotals[$formationLabel]['total'] ?? 0) ?: 0);
                    }
                } else {
                    $formationLabel = $student->program ?? null;
                    $totalAmount = (int) (($formationTotals[$formationLabel]['total'] ?? 0) ?: 0);
                }

                $remaining = max(0, $totalAmount - $amountPaid);

                $studentData = [
                    'first_name' => $student->first_name ?? 'Étudiant',
                    'last_name' => $student->last_name ?? '',
                    'formation' => $student->program ?? ($preReg->choix_formation ?? 'Non défini'),
                    'amount_paid' => $amountPaid,
                    'remaining' => $remaining,
                    'created_at' => $student->created_at,
                ];

                $emailTo = $student->email;
            }

            // Envoyer l'email
            Mail::send('emails.payment_reminder', ['student' => $studentData], function ($message) use ($emailTo) {
                $message->to($emailTo)
                    ->subject('Rappel de Paiement - École Virtuelle des Créatifs');
            });

            if (Schema::hasTable('payment_reminders') && $student) {
                DB::table('payment_reminders')->insert([
                    'student_id' => $student->id,
                    'sent_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Log::info('Email de relance de paiement envoyé', [
                'student_id' => $id,
                'email' => $emailTo,
                'nom' => $studentData['first_name'] . ' ' . $studentData['last_name']
            ]);

            $persisted = false;
            $remindersCount = null;
            if (Schema::hasTable('payment_reminders') && $student) {
                $persisted = true;
                $remindersCount = (int) DB::table('payment_reminders')
                    ->where('student_id', $student->id)
                    ->count();
            }

            return response()->json([
                'success' => true,
                'message' => 'Email de relance envoyé avec succès à ' . $emailTo,
                'reminders_count' => $remindersCount,
                'persisted' => $persisted,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email de relance: ' . $e->getMessage(), [
                'student_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher la page des paramètres
     */
    public function parametres(): View
    {
        $adminData = DB::table('admins')->where('id', session('admin_id'))->first();

        // S'assurer que toutes les propriétés existent avec des valeurs par défaut
        $admin = (object) [
            'id' => $adminData->id,
            'name' => $adminData->name,
            'email' => $adminData->email,
            'role' => $adminData->role ?? 'assistant',
            'phone' => $adminData->phone ?? null,
            'bio' => $adminData->bio ?? null,
            'photo' => $adminData->photo ?? null,
            'is_active' => $adminData->is_active ?? true,
            'created_at' => $adminData->created_at,
            'updated_at' => $adminData->updated_at ?? null,
            'last_login_at' => $adminData->last_login_at ?? null,
        ];

        // Statistiques système
        $systemStats = [
            'total_users' => DB::table('users')->count(),
            'total_students' => DB::table('students')->count(),
            'total_admins' => DB::table('admins')->count(),
            'database_size' => $this->getDatabaseSize(),
            'storage_used' => $this->getStorageSize(),
        ];

        return view('admin.parametres.index', compact('admin', 'systemStats'));
    }

    /**
     * Mettre à jour les paramètres
     */
    public function updateParametres(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
        ]);

        try {
            DB::table('admins')
                ->where('id', session('admin_id'))
                ->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? null,
                    'bio' => $validated['bio'] ?? null,
                    'updated_at' => now(),
                ]);

            return redirect()->back()->with('success', 'Paramètres mis à jour avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur mise à jour paramètres: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour');
        }
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'new_password.required' => 'Le nouveau mot de passe est obligatoire.',
            'new_password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
            'new_password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        try {
            $admin = DB::table('admins')->where('id', session('admin_id'))->first();

            // Vérifier le mot de passe actuel
            if (!Hash::check($validated['current_password'], $admin->password)) {
                return redirect()->back()->with('error', 'Le mot de passe actuel est incorrect.');
            }

            // Mettre à jour le mot de passe
            DB::table('admins')
                ->where('id', session('admin_id'))
                ->update([
                    'password' => Hash::make($validated['new_password']),
                    'updated_at' => now(),
                ]);

            Log::info('Mot de passe mis à jour', [
                'admin_id' => session('admin_id'),
            ]);

            return redirect()->back()->with('success', 'Mot de passe mis à jour avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur mise à jour mot de passe: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour du mot de passe');
        }
    }

    /**
     * Paramètres système
     */
    public function systemSettings(): View
    {
        return view('admin.parametres.system');
    }

    /**
     * Paramètres de sécurité
     */
    public function securitySettings(): View
    {
        return view('admin.parametres.security');
    }

    /**
     * Paramètres de notifications
     */
    public function notificationSettings(): View
    {
        $admin = DB::table('admins')->where('id', session('admin_id'))->first();

        // Récupérer les préférences depuis le champ JSON (si existe)
        $savedPreferences = [];
        if (isset($admin->notification_preferences)) {
            $savedPreferences = json_decode($admin->notification_preferences, true) ?? [];
        }

        // Récupérer les préférences de notifications (par défaut toutes actives)
        $notifications = [
            'new_registrations' => $savedPreferences['new_registrations'] ?? true,
            'new_payments' => $savedPreferences['new_payments'] ?? true,
            'documents_submitted' => $savedPreferences['documents_submitted'] ?? true,
            'projects_completed' => $savedPreferences['projects_completed'] ?? false,
            'system_alerts' => $savedPreferences['system_alerts'] ?? true,
            'backups' => $savedPreferences['backups'] ?? true,
            'weekly_reports' => $savedPreferences['weekly_reports'] ?? false,
            'team_activities' => $savedPreferences['team_activities'] ?? false,
        ];

        return view('admin.parametres.notifications', compact('notifications'));
    }

    /**
     * Mettre à jour les préférences de notifications
     */
    public function updateNotifications(Request $request)
    {
        try {
            $adminId = session('admin_id');

            // Récupérer les préférences actuelles
            $admin = DB::table('admins')->where('id', $adminId)->first();
            $currentPreferences = [];

            if (isset($admin->notification_preferences)) {
                $currentPreferences = json_decode($admin->notification_preferences, true) ?? [];
            }

            // Mettre à jour avec les nouvelles données
            $allData = $request->all();
            foreach ($allData as $key => $value) {
                if ($key !== '_token') {
                    $currentPreferences[$key] = (bool)$value;
                }
            }

            // Sauvegarder en JSON
            DB::table('admins')
                ->where('id', $adminId)
                ->update([
                    'notification_preferences' => json_encode($currentPreferences),
                    'updated_at' => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Préférences enregistrées'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Paramètres de sauvegarde
     */
    public function backupSettings(): View
    {
        return view('admin.parametres.backup');
    }

    /**
     * Créer une sauvegarde
     */
    public function createBackup()
    {
        // TODO: Implémenter la logique de sauvegarde
        return redirect()->back()->with('success', 'Sauvegarde créée avec succès');
    }

    /**
     * Logs système
     */
    public function systemLogs(): View
    {
        return view('admin.parametres.logs');
    }

    /**
     * Déterminer l'URL de redirection basée sur la formation du TP/projet
     */
    private function getRedirectUrlBasedOnFormation($tp)
    {
        $formation = $tp->formation ?? null;

        // Normaliser le nom de la formation pour la comparaison
        $formationNormalized = strtolower(str_replace([' ', '_', '-', '&'], '', $formation ?? ''));

        // Si c'est Design Graphique & Community Management
        if (str_contains($formationNormalized, 'designgraphique') && str_contains($formationNormalized, 'community')) {
            return route('admin.projets.design-cm.pending');
        }

        // Si c'est Community Management seul
        if (str_contains($formationNormalized, 'community')) {
            return route('admin.projets.cm-smm.pending');
        }

        // Si c'est Design Graphique seul
        if (str_contains($formationNormalized, 'design') || str_contains($formationNormalized, 'graphique')) {
            return route('admin.projets.design-graphique.pending');
        }

        // Par défaut, retourner à la page précédente
        return back()->getTargetUrl();
    }

    /**
     * Obtenir la taille de la base de données
     */
    private function getDatabaseSize(): string
    {
        try {
            $dbName = config('database.connections.mysql.database');
            $result = DB::select("
                SELECT
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.TABLES
                WHERE table_schema = ?
            ", [$dbName]);

            return ($result[0]->size_mb ?? 0) . ' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    /**
     * Obtenir la taille du stockage
     */
    private function getStorageSize(): string
    {
        try {
            $storagePath = storage_path('app');
            $size = 0;

            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($storagePath)) as $file) {
                if ($file->isFile()) {
                    $size += $file->getSize();
                }
            }

            return round($size / 1024 / 1024, 2) . ' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    /**
     * Afficher les projets en attente de validation
     */
    public function projetsPending()
    {
        // Récupérer tous les projets avec statut 'termine' (soumis par étudiants, en attente de validation)
        $pendingProjects = DB::table('projects')
            ->where('projects.status', 'termine')
            ->leftJoin('users', 'projects.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->select(
                'projects.*',
                'users.email as student_email',
                'students.first_name',
                'students.last_name',
                'students.program as formation',
                'students.profile_photo'
            )
            ->orderBy('projects.updated_at', 'desc')
            ->get();

        // Pour chaque projet, récupérer les fichiers/images associés
        $tpSubmissions = $pendingProjects->map(function ($project) {
            // Récupérer les images du projet depuis la table project_images
            $files = DB::table('project_images')
                ->where('project_id', $project->id)
                ->select('id', 'image_path', 'created_at')
                ->get();

            // Ajouter les fichiers au projet
            $project->files = $files;
            $project->submitted_at = $project->updated_at; // Date de dernière modification = date soumission

            return $project;
        });

        // Calculer les statistiques
        $stats = [
            'total' => $pendingProjects->count(),
            'design_graphique' => $pendingProjects->where('formation', 'Design Graphique')->count(),
            'community_management' => $pendingProjects->where('formation', 'Community Management')->count(),
            'gestion_informatique' => $pendingProjects->where('formation', 'Gestion Informatique')->count(),
            'intelligence_artificielle' => $pendingProjects->where('formation', 'Intelligence Artificielle')->count(),
        ];

        return view('admin.projets.pending', [
            'tpSubmissions' => $tpSubmissions,
            'stats' => $stats
        ]);
    }

    /**
     * Page pour envoyer/assigner des projets aux étudiants
     */
    public function projetsToSend(Request $request)
    {
        $defaultFormation = $request->query('formation');
        if (!empty($defaultFormation)) {
            $formationKey = strtolower(str_replace([' ', '_', '-'], '', (string) $defaultFormation));
            $containsDesign = str_contains($formationKey, 'design');
            $containsCommunity = str_contains($formationKey, 'community');

            if ($containsDesign && $containsCommunity) {
                $defaultFormation = 'Design Graphique & Community Management';
            } else {
                $defaultFormation = match ($formationKey) {
                    'designgraphique' => 'Design Graphique',
                    'communitymanagement' => 'Community Management',
                    'gestioninformatique' => 'Gestion Informatique',
                    'intelligenceartificielle' => 'Intelligence Artificielle',
                    default => $defaultFormation,
                };
            }
        }
        $defaultStudentIds = [];
        $defaultStudentId = $request->query('student_id');
        if (!empty($defaultStudentId)) {
            $defaultStudentIds = [(int) $defaultStudentId];
        }

        // Récupérer tous les étudiants actifs avec leurs informations
        $students = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where(function ($q) {
                $q->where('students.status', 'active')
                  ->orWhereNull('students.status')
                  ->orWhere('students.status', '');
            })
            ->select(
                'students.*',
                'users.email'
            )
            ->get();

        // Normaliser les formations pour cohérence
        $students = $students->map(function ($student) {
            // Normaliser la formation
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

        // Récupérer tous les étudiants pour la liste
        $all_students = $students;

        // DEBUG: Vérifier l'étudiant ID 19
        $student19 = $students->firstWhere('id', 19);
        if ($student19) {
            Log::info('DEBUG student 19 TROUVÉ dans la liste', [
                'id' => $student19->id,
                'status' => $student19->status ?? 'NULL',
                'user_id' => $student19->user_id ?? 'NULL',
                'program' => $student19->program ?? 'NULL',
                'first_name' => $student19->first_name ?? '',
                'last_name' => $student19->last_name ?? '',
            ]);
        } else {
            // Chercher directement en base
            $raw19 = DB::table('students')->where('id', 19)->first();
            Log::warning('DEBUG student 19 NON TROUVÉ dans la liste filtrée', [
                'exists_in_db' => $raw19 ? true : false,
                'db_status' => $raw19->status ?? 'NULL',
                'db_user_id' => $raw19->user_id ?? 'NULL',
            ]);
        }

        // Compter les TP soumis/validés par student_id (travail réellement effectué)
        $tpCountsByStudentId = DB::table('tp_assignments')
            ->whereIn('status', ['submitted', 'pending', 'validated'])
            ->selectRaw('student_id, COUNT(*) as tp_count')
            ->groupBy('student_id')
            ->pluck('tp_count', 'student_id');

        // Compter les projets traités par user_id (tout statut sauf jamais assigné)
        $projectCountsByUserId = DB::table('projects')
            ->whereIn('status', ['en_cours', 'termine', 'valide', 'soumis'])
            ->selectRaw('user_id, COUNT(*) as project_count')
            ->groupBy('user_id')
            ->pluck('project_count', 'user_id');

        $students = $students->map(function ($student) use ($projectCountsByUserId, $tpCountsByStudentId) {
            $userId = (int) ($student->user_id ?? 0);
            $studentId = (int) ($student->id ?? 0);
            $student->tp_count = $studentId > 0 ? (int) ($tpCountsByStudentId[$studentId] ?? 0) : 0;
            $student->project_count = $userId > 0 ? (int) ($projectCountsByUserId[$userId] ?? 0) : 0;
            $student->has_projects = $student->project_count > 0;
            return $student;
        });

        // DEBUG: Vérifier student 19 après comptage projets
        $s19after = $students->firstWhere('id', 19);
        if ($s19after) {
            Log::info('DEBUG student 19 APRÈS comptage', [
                'tp_count' => $s19after->tp_count,
                'project_count' => $s19after->project_count,
                'has_projects' => $s19after->has_projects,
                'user_id' => $s19after->user_id ?? 'NULL',
            ]);
        }

        $studentsWithoutProjects = $students
            ->filter(fn($s) => empty($s->has_projects))
            ->values();

        $studentsWithProjects = $students
            ->filter(fn($s) => !empty($s->has_projects))
            ->values();

        // Calculer les statistiques par formation — uniquement les étudiants ayant réalisé au moins 1 TP/Projet
        $stats = [
            'total_students' => $studentsWithProjects->count(),
            'design_graphique' => $studentsWithProjects->where('program_normalized', 'Design Graphique')->count(),
            'design_graphique_cm' => $studentsWithProjects->where('program_normalized', 'Design Graphique & Community Management')->count(),
            'community_management' => $studentsWithProjects->where('program_normalized', 'Community Management')->count(),
            'gestion_informatique' => $studentsWithProjects->where('program_normalized', 'Gestion Informatique')->count(),
            'intelligence_artificielle' => $studentsWithProjects->where('program_normalized', 'Intelligence Artificielle')->count(),
            'sans_formation' => $studentsWithProjects->where('program_normalized', 'Sans formation')->count(),
        ];

        $stats['zero_projects'] = $studentsWithoutProjects->count();

        return view('admin.projets.to-send', [
            'students' => $students,
            'all_students' => $all_students,
            'studentsWithoutProjects' => $studentsWithoutProjects,
            'studentsWithProjects' => $studentsWithProjects,
            'stats' => $stats,
            'defaultFormation' => $defaultFormation,
            'defaultStudentIds' => $defaultStudentIds,
        ]);
    }

    /**
     * Page pour voir tous les projets
     */
    public function projetsAll(Request $request)
    {
        $dayStart = now()->startOfDay();
        $weekStart = now()->startOfWeek();
        $monthStart = now()->startOfMonth();

        $baseProjectsQuery = DB::table('projects');

        $q = trim((string) $request->query('q', ''));
        $status = trim((string) $request->query('status', ''));
        $period = trim((string) $request->query('period', ''));

        $applyFilters = function ($query) use ($q, $status, $period, $dayStart, $weekStart, $monthStart) {
            if ($q !== '') {
                $query->where(function ($sub) use ($q) {
                    $sub->where('users.email', 'like', '%' . $q . '%')
                        ->orWhere('students.first_name', 'like', '%' . $q . '%')
                        ->orWhere('students.last_name', 'like', '%' . $q . '%')
                        ->orWhereRaw("CONCAT(COALESCE(students.first_name,''),' ',COALESCE(students.last_name,'')) LIKE ?", ['%' . $q . '%']);
                });
            }

            if ($status !== '') {
                $query->where('projects.status', $status);
            }

            if ($period === 'today') {
                $query->where('projects.created_at', '>=', $dayStart);
            } elseif ($period === 'week') {
                $query->where('projects.created_at', '>=', $weekStart);
            } elseif ($period === 'month') {
                $query->where('projects.created_at', '>=', $monthStart);
            }
        };

        // Récupérer tous les projets avec les informations des étudiants
        $projectsQuery = DB::table('projects')
            ->leftJoin('users', 'projects.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->select(
                'projects.*',
                'users.email as student_email',
                'students.first_name',
                'students.last_name',
                'students.program as formation',
                'students.profile_photo'
            )
            ->selectSub(function ($query) {
                $query->from('project_images')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('project_images.project_id', 'projects.id');
            }, 'images_count');

        $applyFilters($projectsQuery);

        $projects = $projectsQuery
            ->orderBy('projects.created_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        // Calculer les statistiques
        $stats = [
            'total' => (clone $baseProjectsQuery)->count(),
            'en_cours' => (clone $baseProjectsQuery)->where('status', 'en_cours')->count(),
            'termine' => (clone $baseProjectsQuery)->where('status', 'termine')->count(),
            'valide' => (clone $baseProjectsQuery)->where('status', 'valide')->count(),
            'rejete' => (clone $baseProjectsQuery)->where('status', 'rejete')->count(),
            'created_today' => (clone $baseProjectsQuery)
                ->where('created_at', '>=', $dayStart)
                ->count(),
            'created_week' => (clone $baseProjectsQuery)
                ->where('created_at', '>=', $weekStart)
                ->count(),
            'created_month' => (clone $baseProjectsQuery)
                ->where('created_at', '>=', $monthStart)
                ->count(),
        ];

        // Étudiants actifs qui n'ont jamais reçu de projet
        $studentsWithoutProjects = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('students.status', 'active')
            ->whereNotExists(function ($sub) {
                $sub->selectRaw('1')
                    ->from('projects')
                    ->whereColumn('projects.user_id', 'students.user_id');
            })
            ->select(
                'students.id as student_id',
                'students.user_id',
                'students.first_name',
                'students.last_name',
                'students.program as formation',
                'students.profile_photo',
                'users.email'
            )
            ->orderBy('students.created_at', 'desc')
            ->limit(20)
            ->get();

        // Étudiants actifs qui ont terminé/validé un projet et sont en attente d'un nouveau (aucun projet en cours)
        $waitingForNewProjectStudents = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('students.status', 'active')
            ->whereExists(function ($sub) {
                $sub->selectRaw('1')
                    ->from('projects')
                    ->whereColumn('projects.user_id', 'students.user_id')
                    ->whereIn('projects.status', ['termine', 'valide']);
            })
            ->whereNotExists(function ($sub) {
                $sub->selectRaw('1')
                    ->from('projects')
                    ->whereColumn('projects.user_id', 'students.user_id')
                    ->where('projects.status', 'en_cours');
            })
            ->select(
                'students.id as student_id',
                'students.user_id',
                'students.first_name',
                'students.last_name',
                'students.program as formation',
                'students.profile_photo',
                'users.email'
            )
            ->selectSub(function ($sub) {
                $sub->from('projects')
                    ->selectRaw('MAX(projects.updated_at)')
                    ->whereColumn('projects.user_id', 'students.user_id');
            }, 'last_project_at')
            ->orderByDesc('last_project_at')
            ->limit(20)
            ->get();

        return view('admin.projets.all', [
            'projects' => $projects,
            'stats' => $stats,
            'studentsWithoutProjects' => $studentsWithoutProjects,
            'waitingForNewProjectStudents' => $waitingForNewProjectStudents,
        ]);
    }

    /**
     * Afficher les détails d'un projet soumis
     */
    public function showTpDetails($id)
    {
        // Récupérer le projet avec les informations de l'étudiant
        $tp = DB::table('projects')
            ->leftJoin('users', 'projects.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->where('projects.id', $id)
            ->select(
                'projects.*',
                'users.email as student_email',
                'students.first_name',
                'students.last_name',
                'students.program as formation',
                'students.profile_photo',
                'students.phone as student_phone',
                'students.student_id as student_number'
            )
            ->first();

        if (!$tp) {
            return redirect()->route('admin.projets.pending')
                ->with('error', 'Projet non trouvé');
        }

        // Récupérer les images du projet
        $submittedFiles = DB::table('project_images')
            ->where('project_id', $id)
            ->select(
                'id',
                'file_path',
                'mime_type',
                'original_name as file_name',
                'file_size'
            )
            ->get();

        // Pas de fichiers d'assignation pour les projets (contrairement aux TP)
        $assignmentFiles = collect();

        // Pas d'admin assigné pour les projets (créés par les étudiants)
        $assignedBy = null;

        return view('admin.projets.show', [
            'tp' => $tp,
            'submittedFiles' => $submittedFiles,
            'assignmentFiles' => $assignmentFiles,
            'assignedBy' => $assignedBy
        ]);
    }

    /**
     * Traiter l'envoi/assignation de projets aux étudiants
     */
    public function sendProjects(Request $request)
    {
        if (!Schema::hasColumn('projects', 'deadline')) {
            return redirect()->back()->with('error', "La colonne 'deadline' n'existe pas encore dans la table projects. Lancez la migration (php artisan migrate) puis réessayez.");
        }

        // Validation
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'deadline' => 'required|date|after_or_equal:today',
            'formation' => 'required|array',
            'formation.*' => 'required|string',
            'tags' => 'nullable|string',
            'software_used' => 'nullable|string',
            'reference_link' => 'nullable|url',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240|mimetypes:image/jpeg,image/jpg,image/png,image/gif,image/webp,application/pdf',
            'students' => 'nullable|array',
            'students.*' => 'exists:students,id'
        ]);

        $uploadedAttachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if (!$file || !$file->isValid()) {
                    continue;
                }

                $uploadedAttachments[] = $file;
            }
        }

        // Préparer les données du projet
        $projectData = [
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
            'tags' => $request->tags,
            'link' => $request->reference_link,
            'software_used' => json_encode($request->software_used ? array_map('trim', explode(',', $request->software_used)) : []),
            'deadline' => $request->deadline,
            'status' => 'en_cours',
            'created_at' => now(),
            'updated_at' => now()
        ];

        $createdCount = 0;
        $emailsSent = 0;
        $emailsFailures = [];
        $errors = [];

        // Déterminer les étudiants cibles
        $targetStudents = [];

        $formations = $request->input('formation', []);
        if (!is_array($formations)) {
            $formations = [$formations];
        }
        $formations = array_values(array_filter($formations, function ($f) {
            return is_string($f) && trim($f) !== '';
        }));
        $hasAll = in_array('all', $formations, true);

        if ($hasAll) {
            // Tous les étudiants actifs
            $targetStudents = DB::table('students')
                ->where('status', 'active')
                ->pluck('id')
                ->toArray();
        } elseif ($request->has('students') && count($request->students) > 0) {
            // Étudiants spécifiques sélectionnés
            $targetStudents = $request->students;
        } else {
            // Tous les étudiants des formations sélectionnées
            $selectedSpecificFormations = array_values(array_filter($formations, function ($f) {
                return $f !== 'all';
            }));

            $wantsSansFormation = in_array('Sans formation', $selectedSpecificFormations, true);
            $normalizedSearches = collect($selectedSpecificFormations)
                ->reject(fn($f) => $f === 'Sans formation')
                ->map(function ($formation) {
                    return strtolower(str_replace([' ', '_', '-', '&'], '', $formation));
                })
                ->unique()
                ->values();

            $targetStudents = DB::table('students')
                ->where('status', 'active')
                ->get()
                ->filter(function ($student) use ($normalizedSearches, $wantsSansFormation) {
                    $program = $student->program;
                    if (!$program || trim((string) $program) === '') {
                        return $wantsSansFormation;
                    }
                    $studentProgramNormalized = strtolower(str_replace([' ', '_', '-', '&'], '', (string) $program));
                    return $normalizedSearches->contains($studentProgramNormalized);
                })
                ->pluck('id')
                ->toArray();
        }

        // Créer un projet pour chaque étudiant cible
        foreach ($targetStudents as $studentId) {
            try {
                // Récupérer les informations de l'étudiant
                $student = DB::table('students')
                    ->leftJoin('users', 'students.user_id', '=', 'users.id')
                    ->where('students.id', $studentId)
                    ->select('students.*', 'users.email')
                    ->first();

                if (!$student) {
                    continue;
                }

                // Créer le projet pour cet étudiant
                $projectData['user_id'] = $student->user_id;

                $projectId = DB::table('projects')->insertGetId($projectData);
                $createdCount++;

                // Notification in-app (database)
                try {
                    $user = \App\Models\User::find($student->user_id);
                    if ($user) {
                        // Déterminer l'URL étudiant (module selon formation)
                        $formationSlug = 'design-graphique';
                        if (!empty($student->program)) {
                            $prog = strtolower((string) $student->program);
                            $containsDesign = str_contains($prog, 'design');
                            $containsCommunity = str_contains($prog, 'community');
                            if ($containsDesign && $containsCommunity) {
                                $formationSlug = 'design-graphique-community-manager';
                            } elseif ($containsCommunity) {
                                $formationSlug = 'community-management';
                            } elseif (str_contains($prog, 'informatique')) {
                                $formationSlug = 'gestion-informatique';
                            } elseif (str_contains($prog, 'intelligence')) {
                                $formationSlug = 'intelligence-artificielle';
                            }
                        }

                        $studentUrl = url("/evc/compte/{$formationSlug}/projets");

                        $user->notify(new ProjectAssignedNotification([
                            'category' => 'project',
                            'event' => 'assigned',
                            'title' => 'Nouveau projet assigné',
                            'message' => 'Un nouveau projet a été assigné : ' . ($projectData['title'] ?? 'Projet'),
                            'project_id' => $projectId,
                            'project_title' => $projectData['title'] ?? null,
                            'created_at' => now()->toIso8601String(),
                            'url' => $studentUrl,
                        ]));
                    }
                } catch (\Exception $e) {
                    Log::warning('Notification in-app projet assigné échouée (sendProjects)', [
                        'project_id' => $projectId ?? null,
                        'student_id' => $studentId,
                        'user_id' => $student->user_id ?? null,
                        'error' => $e->getMessage(),
                    ]);
                }

                if (!empty($uploadedAttachments)) {
                    $index = 0;
                    foreach ($uploadedAttachments as $file) {
                        if (!$file || !$file->isValid()) {
                            continue;
                        }

                        $extension = $file->getClientOriginalExtension();
                        $storedName = time() . '_' . uniqid() . '.' . $extension;
                        $directory = 'projects/' . $projectId . '/attachments';
                        $storedPath = $file->storeAs($directory, $storedName, 'public');

                        DB::table('project_images')->insert([
                            'project_id' => $projectId,
                            'filename' => basename($storedPath),
                            'original_name' => $file->getClientOriginalName(),
                            'mime_type' => $file->getClientMimeType(),
                            'file_size' => $file->getSize(),
                            'file_path' => $storedPath,
                            'is_thumbnail' => false,
                            'order_index' => $index,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $index++;
                    }
                }

                // Envoyer un email de notification (optionnel)
                try {
                    if (empty($student->email)) {
                        $emailsFailures[] = "student_id={$studentId}: email manquant";
                    } else {
                        // Déterminer l'URL étudiant (module selon formation)
                        $formationSlug = 'design-graphique';
                        if (!empty($student->program)) {
                            $prog = strtolower((string) $student->program);
                            $containsDesign = str_contains($prog, 'design');
                            $containsCommunity = str_contains($prog, 'community');
                            if ($containsDesign && $containsCommunity) {
                                $formationSlug = 'design-graphique-community-manager';
                            } elseif ($containsCommunity) {
                                $formationSlug = 'community-management';
                            } elseif (str_contains($prog, 'informatique')) {
                                $formationSlug = 'gestion-informatique';
                            } elseif (str_contains($prog, 'intelligence')) {
                                $formationSlug = 'intelligence-artificielle';
                            }
                        }

                        $studentUrl = url("/evc/compte/{$formationSlug}/projets");

                        Mail::send('emails.project_assigned', [
                            'student' => $student,
                            'project' => (object) $projectData,
                            'studentUrl' => $studentUrl,
                        ], function ($message) use ($student, $projectData) {
                            $message->to($student->email)
                                ->subject('📌 Nouveau projet disponible : ' . $projectData['title']);
                        });
                        $emailsSent++;
                    }
                } catch (\Exception $e) {
                    Log::error('Erreur envoi email projet: ' . $e->getMessage());
                    $emailsFailures[] = "student_id={$studentId}: " . $e->getMessage();
                }
            } catch (\Exception $e) {
                $errors[] = "Erreur pour l'étudiant ID {$studentId}: " . $e->getMessage();
                Log::error("Erreur création projet pour étudiant {$studentId}: " . $e->getMessage());
            }
        }

        // Message de succès
        $message = "Projet assigné avec succès à {$createdCount} étudiant(s)";
        if ($emailsSent > 0) {
            $message .= ". {$emailsSent} email(s) de notification envoyé(s)";
        }
        if (!empty($emailsFailures)) {
            $message .= ". " . count($emailsFailures) . " email(s) non envoyé(s)";
        }
        if (!empty($errors)) {
            $message .= ". Attention: " . count($errors) . " erreur(s) rencontrée(s)";
        }

        return redirect()->route('admin.projets.to-send')
            ->with('success', $message)
            ->with('errors_list', $errors)
            ->with('emails_failures', $emailsFailures);
    }

    /**
     * Afficher la liste complète des activités récentes (soumissions de TP)
     */
    public function activites(): View
    {
        // Calculer les statistiques sur toutes les activités
        $allActivities = DB::table('tp_assignments')
            ->whereIn('status', ['assigned', 'submitted', 'pending', 'validated', 'rejected'])
            ->get();

        $stats = [
            'total' => $allActivities->count(),
            'en_attente' => $allActivities->where('status', 'assigned')->count() + $allActivities->where('status', 'pending')->count() + $allActivities->where('status', 'submitted')->count(),
            'valides' => $allActivities->where('status', 'validated')->count(),
            'rejetes' => $allActivities->where('status', 'rejected')->count(),
        ];

        // Récupérer uniquement les étudiants qui ont des activités
        $studentsWithActivities = DB::table('students')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('tp_assignments', function ($join) {
                $join->on('students.id', '=', 'tp_assignments.student_id')
                    ->whereIn('tp_assignments.status', ['assigned', 'submitted', 'pending', 'validated', 'rejected']);
            })
            ->select(
                'students.id as student_id',
                'students.first_name',
                'students.last_name',
                'students.profile_photo',
                'students.program as formation',
                'users.email',
                DB::raw('MAX(tp_assignments.updated_at) as last_activity'),
                DB::raw('COUNT(tp_assignments.id) as total_activities'),
                DB::raw('SUM(CASE WHEN tp_assignments.status = "validated" THEN 1 ELSE 0 END) as validated_count'),
                DB::raw('SUM(CASE WHEN tp_assignments.status IN ("assigned", "pending", "submitted") THEN 1 ELSE 0 END) as pending_count'),
                DB::raw('SUM(CASE WHEN tp_assignments.status = "rejected" THEN 1 ELSE 0 END) as rejected_count')
            )
            ->groupBy('students.id', 'students.first_name', 'students.last_name', 'students.profile_photo', 'students.program', 'users.email')
            ->orderByRaw('MAX(tp_assignments.updated_at) desc')
            ->paginate(10);

        // Pour chaque étudiant, récupérer ses 3 dernières activités
        foreach ($studentsWithActivities as $student) {
            $student->recent_activities = DB::table('tp_assignments')
                ->select('id', 'title', 'status', 'submitted_at', 'updated_at')
                ->where('student_id', $student->student_id)
                ->whereIn('status', ['assigned', 'submitted', 'pending', 'validated', 'rejected'])
                ->orderBy('updated_at', 'desc')
                ->limit(3)
                ->get();
        }

        return view('admin.activites.index', compact('studentsWithActivities', 'stats'));
    }

    /**
     * Mapper les noms de formations vers les slugs de routes corrects
     */
    private function getFormationSlug($formation)
    {
        $formationMapping = [
            'Design Graphique' => 'design-graphique',
            'design_graphique' => 'design-graphique',
            'design-graphique' => 'design-graphique',

            'Community Management' => 'community-management',
            'community_management' => 'community-management',
            'community-management' => 'community-management',

            'Design Graphique & Community Management' => 'design-graphique-cm',
            'Design Graphique & Community Manager' => 'design-graphique-cm',
            'design_graphique_community_management' => 'design-graphique-cm',
            'design-graphique-cm' => 'design-graphique-cm',

            'Gestion Informatique' => 'gestion-informatique',
            'gestion_informatique' => 'gestion-informatique',
            'gestion-informatique' => 'gestion-informatique',

            'Intelligence Artificielle' => 'intelligence-artificielle',
            'intelligence_artificielle' => 'intelligence-artificielle',
            'intelligence-artificielle' => 'intelligence-artificielle',
        ];

        // Si la formation est dans le mapping, retourner le slug correspondant
        if (isset($formationMapping[$formation])) {
            return $formationMapping[$formation];
        }

        // Sinon, faire une conversion basique (fallback)
        return strtolower(str_replace(['_', ' ', '&'], ['-', '-', ''], trim($formation)));
    }
}
