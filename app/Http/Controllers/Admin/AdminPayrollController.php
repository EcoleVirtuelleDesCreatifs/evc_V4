<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminPayrollController extends Controller
{
    private function computeCommercialSalesForAdmins(array $adminIds, $monthStart)
    {
        if (empty($adminIds) || !Schema::hasTable('payments') || !Schema::hasColumn('payments', 'commercial_admin_id')) {
            return collect();
        }

        return DB::table('payments')
            ->select('commercial_admin_id', DB::raw('SUM(amount) as total_amount'))
            ->whereIn('commercial_admin_id', $adminIds)
            ->where('status', 'completed')
            ->where(function ($q) use ($monthStart) {
                $q->where('paid_at', '>=', $monthStart)->orWhere(function ($q2) use ($monthStart) {
                    $q2->whereNull('paid_at')->where('created_at', '>=', $monthStart);
                });
            })
            ->groupBy('commercial_admin_id')
            ->pluck('total_amount', 'commercial_admin_id');
    }

    private function computeLogsByAdminByType(array $adminIds, $monthStart, $monthEnd)
    {
        if (empty($adminIds) || !Schema::hasTable('admin_task_logs')) {
            return collect();
        }

        $logs = DB::table('admin_task_logs')
            ->select('admin_id', 'task_type_id', DB::raw('SUM(quantity) as qty'))
            ->whereIn('admin_id', $adminIds)
            ->whereBetween('performed_at', [$monthStart, $monthEnd])
            ->groupBy('admin_id', 'task_type_id')
            ->get();

        return $logs->groupBy('admin_id')->map(function ($rows) {
            return collect($rows)->pluck('qty', 'task_type_id');
        });
    }

    private function buildRowForAdmin(
        object $admin,
        \Illuminate\Support\Collection $profilesById,
        \Illuminate\Support\Collection $profilesByAdmin,
        \Illuminate\Support\Collection $logsByAdminByType,
        \Illuminate\Support\Collection $commercialSalesByAdmin,
        int $commercialRateBp,
        int $daysInMonth,
        int $dayOfMonth
    ): array {
        $assigned = $profilesByAdmin[$admin->id] ?? collect();
        $assignedProfiles = $assigned->map(function ($pid) use ($profilesById) {
            return $profilesById[$pid] ?? null;
        })->filter()->values();

        $assignedLabels = $assignedProfiles->pluck('label')->values();
        $logsByType = $logsByAdminByType[$admin->id] ?? collect();

        $breakdown = $assignedProfiles->map(function ($p) use ($admin, $logsByType, $daysInMonth, $dayOfMonth) {
            return $this->computeProfileKpiForAdmin((int) $admin->id, $p, $logsByType, $daysInMonth, $dayOfMonth);
        })->values();

        $baseTotal = (int) $breakdown->sum('base_monthly_amount');
        $earnedTotal = (int) $breakdown->sum('earned');
        $kpiAvg = $breakdown->count() > 0 ? (int) round($breakdown->avg('final_score')) : 0;

        $commercialSales = (float) ($commercialSalesByAdmin[$admin->id] ?? 0);
        $commission = $commercialRateBp > 0 ? (int) round($commercialSales * ($commercialRateBp / 10000)) : 0;

        return [
            'id' => (int) $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => $admin->role,
            'can_view_salary_amount' => (bool) ($admin->can_view_salary_amount ?? false),
            'profile_ids' => $assigned->map(fn ($v) => (int) $v)->values()->all(),
            'profile_labels' => $assignedLabels->values()->all(),
            'kpi_avg' => $kpiAvg,
            'base_total' => $baseTotal,
            'earned_total' => $earnedTotal,
            'breakdown' => $breakdown->all(),
            'commercial_sales_month' => (int) round($commercialSales),
            'commercial_commission_month' => $commission,
        ];
    }

    private function resolveProfileTaskTypes(int $jobProfileId, string $jobProfileCode)
    {
        if (!Schema::hasTable('admin_task_types')) {
            return collect();
        }

        $q = DB::table('admin_task_types')
            ->where('is_active', 1);

        if (Schema::hasColumn('admin_task_types', 'job_profile_id')) {
            $q->where(function ($q2) use ($jobProfileId, $jobProfileCode) {
                $q2->where('job_profile_id', $jobProfileId);
                if ($jobProfileCode === 'assistant') {
                    $q2->orWhere(function ($q3) {
                        $q3->whereNull('job_profile_id')->where('role', 'assistant');
                    });
                }
            });
        } else {
            if ($jobProfileCode === 'assistant') {
                $q->where('role', 'assistant');
            } else {
                $q->whereRaw('1 = 0');
            }
        }

        return $q->orderBy('id')->get([
            'id',
            'label',
            'expected_per_month',
            'role',
            Schema::hasColumn('admin_task_types', 'weight') ? 'weight' : DB::raw('10 as weight'),
            Schema::hasColumn('admin_task_types', 'is_critical') ? 'is_critical' : DB::raw('0 as is_critical'),
        ]);
    }

    private function computeProfileKpiForAdmin(
        int $adminId,
        object $profile,
        \Illuminate\Support\Collection $logsByType,
        int $daysInMonth,
        int $dayOfMonth
    ): array {
        $taskTypes = $this->resolveProfileTaskTypes((int) $profile->id, (string) $profile->code);

        $weightSum = 0;
        $scoreSum = 0;
        $penaltyPoints = 0.0;

        foreach ($taskTypes as $t) {
            $expected = (int) ($t->expected_per_month ?? 0);
            $weight = (int) ($t->weight ?? 10);
            $done = (int) ($logsByType[$t->id] ?? 0);

            if ($expected <= 0) {
                continue;
            }

            $weightSum += $weight;

            $ratio = min($done / $expected, 1);
            $scoreSum += $ratio * $weight;

            $expectedToDate = ($expected * $dayOfMonth) / max($daysInMonth, 1);
            $lateUnits = max(0, $expectedToDate - $done);
            $lateRatio = $expected > 0 ? ($lateUnits / $expected) : 0;
            $severity = (bool) ($t->is_critical ?? false) ? 20 : 8;
            $penaltyPoints += $lateRatio * $severity;
        }

        $kpi = $weightSum > 0 ? ($scoreSum / $weightSum) * 100 : 0;
        $penalty = min(40, (int) round($penaltyPoints));
        $finalScore = (int) max(0, min(100, round($kpi - $penalty)));

        $base = (int) ($profile->base_monthly_amount ?? 0);
        $earned = (int) round($base * ($finalScore / 100));

        return [
            'job_profile_id' => (int) $profile->id,
            'code' => (string) $profile->code,
            'label' => (string) $profile->label,
            'base_monthly_amount' => $base,
            'kpi' => (int) round($kpi),
            'penalty' => $penalty,
            'final_score' => $finalScore,
            'earned' => $earned,
        ];
    }

    public function index(): View
    {
        if (session('admin_role') !== 'super_admin') {
            abort(403);
        }

        $year = (int) now()->year;
        $month = (int) now()->month;
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $profiles = collect();
        if (Schema::hasTable('admin_job_profiles')) {
            $profiles = DB::table('admin_job_profiles')
                ->where('is_active', 1)
                ->orderBy('label')
                ->get(['id', 'code', 'label', 'base_monthly_amount', 'commission_rate_bp']);
        }

        $profilesById = $profiles->keyBy('id');
        $commercialProfile = $profiles->firstWhere('code', 'commercial');
        $commercialRateBp = (int) ($commercialProfile->commission_rate_bp ?? 0);

        $admins = collect();
        if (Schema::hasTable('admins')) {
            $admins = DB::table('admins')
                ->where('is_active', 1)
                ->orderBy('name')
                ->get(['id', 'name', 'email', 'role', 'can_view_salary_amount']);
        }

        $adminIds = $admins->pluck('id')->all();

        $assignedProfiles = collect();
        if (!empty($adminIds) && Schema::hasTable('admin_admin_job_profiles')) {
            $assignedProfiles = DB::table('admin_admin_job_profiles')
                ->whereIn('admin_id', $adminIds)
                ->where(function ($q) {
                    $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()->toDateString());
                })
                ->where(function ($q) {
                    $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()->toDateString());
                })
                ->get(['admin_id', 'job_profile_id']);
        }

        $profilesByAdmin = $assignedProfiles->groupBy('admin_id')->map(function ($rows) {
            return $rows->pluck('job_profile_id')->unique()->values();
        });

        $daysInMonth = (int) $monthEnd->day;
        $dayOfMonth = (int) now()->day;

        $logsByAdminByType = $this->computeLogsByAdminByType($adminIds, $monthStart, $monthEnd);
        $commercialSalesByAdmin = $this->computeCommercialSalesForAdmins($adminIds, $monthStart);

        $rows = $admins->map(function ($a) use (
            $profilesById,
            $profilesByAdmin,
            $logsByAdminByType,
            $commercialSalesByAdmin,
            $commercialRateBp,
            $daysInMonth,
            $dayOfMonth
        ) {
            return $this->buildRowForAdmin(
                $a,
                $profilesById,
                $profilesByAdmin,
                $logsByAdminByType,
                $commercialSalesByAdmin,
                $commercialRateBp,
                $daysInMonth,
                $dayOfMonth
            );
        })->values();

        $totalAdmins = (int) $rows->count();
        $totalBase = (int) $rows->sum('base_total');
        $totalEarned = (int) $rows->sum('earned_total');
        $totalCommercialSales = (int) $rows->sum('commercial_sales_month');
        $totalCommercialCommission = (int) $rows->sum('commercial_commission_month');
        $avgKpi = $totalAdmins > 0 ? (int) round($rows->avg('kpi_avg')) : 0;

        $topKpi = $rows->sortByDesc('kpi_avg')->take(5)->values()->all();
        $topEarned = $rows->sortByDesc('earned_total')->take(5)->values()->all();
        $topCommission = $rows->sortByDesc('commercial_commission_month')->take(5)->values()->all();

        return view('admin.payroll.index', [
            'year' => $year,
            'month' => $month,
            'profiles' => $profiles,
            'rows' => $rows,
            'totalAdmins' => $totalAdmins,
            'totalBase' => $totalBase,
            'totalEarned' => $totalEarned,
            'avgKpi' => $avgKpi,
            'totalCommercialSales' => $totalCommercialSales,
            'totalCommercialCommission' => $totalCommercialCommission,
            'topKpi' => $topKpi,
            'topEarned' => $topEarned,
            'topCommission' => $topCommission,
            'commercial_rate_bp' => $commercialRateBp,
            'monthStart' => $monthStart,
            'monthEnd' => $monthEnd,
        ]);
    }

    public function showAdmin(int $adminId): View
    {
        if (session('admin_role') !== 'super_admin') {
            abort(403);
        }

        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $profiles = collect();
        if (Schema::hasTable('admin_job_profiles')) {
            $profiles = DB::table('admin_job_profiles')
                ->where('is_active', 1)
                ->orderBy('label')
                ->get(['id', 'code', 'label', 'base_monthly_amount', 'commission_rate_bp']);
        }
        $profilesById = $profiles->keyBy('id');
        $commercialProfile = $profiles->firstWhere('code', 'commercial');
        $commercialRateBp = (int) ($commercialProfile->commission_rate_bp ?? 0);

        $admin = null;
        if (Schema::hasTable('admins')) {
            $admin = DB::table('admins')->where('id', $adminId)->first(['id', 'name', 'email', 'role', 'is_active', 'can_view_salary_amount']);
        }
        if (!$admin) {
            abort(404);
        }

        $assignedProfiles = collect();
        if (Schema::hasTable('admin_admin_job_profiles')) {
            $assignedProfiles = DB::table('admin_admin_job_profiles')
                ->where('admin_id', $adminId)
                ->where(function ($q) {
                    $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()->toDateString());
                })
                ->where(function ($q) {
                    $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()->toDateString());
                })
                ->pluck('job_profile_id');
        }

        $profilesByAdmin = collect([
            $adminId => collect($assignedProfiles)->unique()->values(),
        ]);

        $daysInMonth = (int) $monthEnd->day;
        $dayOfMonth = (int) now()->day;

        $logsByAdminByType = $this->computeLogsByAdminByType([$adminId], $monthStart, $monthEnd);
        $commercialSalesByAdmin = $this->computeCommercialSalesForAdmins([$adminId], $monthStart);

        $row = $this->buildRowForAdmin(
            $admin,
            $profilesById,
            $profilesByAdmin,
            $logsByAdminByType,
            $commercialSalesByAdmin,
            $commercialRateBp,
            $daysInMonth,
            $dayOfMonth
        );

        return view('admin.payroll.admin_show', [
            'row' => $row,
            'month' => (int) now()->month,
            'year' => (int) now()->year,
            'commercial_rate_bp' => $commercialRateBp,
        ]);
    }

    public function editAdminProfiles(int $adminId): View
    {
        if (session('admin_role') !== 'super_admin') {
            abort(403);
        }

        $admin = null;
        if (Schema::hasTable('admins')) {
            $admin = DB::table('admins')->where('id', $adminId)->first(['id', 'name', 'email', 'role', 'is_active', 'can_view_salary_amount']);
        }
        if (!$admin) {
            abort(404);
        }

        $profiles = collect();
        if (Schema::hasTable('admin_job_profiles')) {
            $profiles = DB::table('admin_job_profiles')
                ->where('is_active', 1)
                ->orderBy('label')
                ->get(['id', 'code', 'label', 'base_monthly_amount', 'commission_rate_bp']);
        }

        $assigned = collect();
        if (Schema::hasTable('admin_admin_job_profiles')) {
            $assigned = DB::table('admin_admin_job_profiles')
                ->where('admin_id', $adminId)
                ->where(function ($q) {
                    $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()->toDateString());
                })
                ->where(function ($q) {
                    $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()->toDateString());
                })
                ->pluck('job_profile_id')
                ->map(fn ($v) => (int) $v)
                ->unique()
                ->values()
                ->all();
        }

        return view('admin.payroll.admin_profiles', [
            'admin' => $admin,
            'profiles' => $profiles,
            'assigned' => $assigned,
        ]);
    }

    public function updateAdminProfiles(Request $request, int $adminId)
    {
        if (session('admin_role') !== 'super_admin') {
            abort(403);
        }

        $validated = $request->validate([
            'job_profile_ids' => 'nullable|array',
            'job_profile_ids.*' => 'integer',
        ]);

        if (!Schema::hasTable('admin_admin_job_profiles')) {
            return redirect()->back()->with('error', 'Module RH non disponible');
        }

        $profileIds = collect($validated['job_profile_ids'] ?? [])->unique()->values()->all();

        DB::beginTransaction();
        try {
            DB::table('admin_admin_job_profiles')
                ->where('admin_id', $adminId)
                ->delete();

            $now = now();
            foreach ($profileIds as $pid) {
                DB::table('admin_admin_job_profiles')->insert([
                    'admin_id' => $adminId,
                    'job_profile_id' => (int) $pid,
                    'starts_at' => null,
                    'ends_at' => null,
                    'allocation_percent' => 100,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Profils mis à jour');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur mise à jour profils');
        }
    }

    public function updateSalaryVisibility(Request $request, int $adminId)
    {
        if (session('admin_role') !== 'super_admin') {
            abort(403);
        }

        $validated = $request->validate([
            'can_view_salary_amount' => 'required|boolean',
        ]);

        if (!Schema::hasTable('admins') || !Schema::hasColumn('admins', 'can_view_salary_amount')) {
            return redirect()->back()->with('error', 'Module RH non disponible');
        }

        DB::table('admins')
            ->where('id', $adminId)
            ->update([
                'can_view_salary_amount' => (bool) $validated['can_view_salary_amount'],
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Visibilité salaire mise à jour');
    }

    public function me(): View
    {
        $adminId = (int) session('admin_id');

        if ($adminId <= 0) {
            abort(403);
        }

        $year = (int) now()->year;
        $month = (int) now()->month;
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $admin = null;
        if (Schema::hasTable('admins')) {
            $admin = DB::table('admins')->where('id', $adminId)->first(['id', 'name', 'email', 'role', 'can_view_salary_amount']);
        }

        $profiles = collect();
        if (Schema::hasTable('admin_job_profiles') && Schema::hasTable('admin_admin_job_profiles')) {
            $profiles = DB::table('admin_admin_job_profiles')
                ->join('admin_job_profiles', 'admin_job_profiles.id', '=', 'admin_admin_job_profiles.job_profile_id')
                ->where('admin_admin_job_profiles.admin_id', $adminId)
                ->where('admin_job_profiles.is_active', 1)
                ->orderBy('admin_job_profiles.label')
                ->get([
                    'admin_job_profiles.id',
                    'admin_job_profiles.code',
                    'admin_job_profiles.label',
                    'admin_job_profiles.base_monthly_amount',
                    'admin_job_profiles.commission_rate_bp',
                ]);
        }

        $daysInMonth = (int) $monthEnd->day;
        $dayOfMonth = (int) now()->day;

        $logsByType = collect();
        if (Schema::hasTable('admin_task_logs')) {
            $logsByType = DB::table('admin_task_logs')
                ->select('task_type_id', DB::raw('SUM(quantity) as qty'))
                ->where('admin_id', $adminId)
                ->whereBetween('performed_at', [$monthStart, $monthEnd])
                ->groupBy('task_type_id')
                ->pluck('qty', 'task_type_id');
        }

        $breakdown = $profiles->map(function ($p) use ($adminId, $logsByType, $daysInMonth, $dayOfMonth) {
            return $this->computeProfileKpiForAdmin($adminId, $p, $logsByType, $daysInMonth, $dayOfMonth);
        })->values();

        $baseTotal = (int) $breakdown->sum('base_monthly_amount');
        $earnedTotal = (int) $breakdown->sum('earned');
        $kpiAvg = $breakdown->count() > 0 ? (int) round($breakdown->avg('final_score')) : 0;

        $commercial = $profiles->firstWhere('code', 'commercial');
        $commercialSales = 0;
        $commercialCommission = 0;

        if ($commercial && Schema::hasTable('payments') && Schema::hasColumn('payments', 'commercial_admin_id')) {
            $commercialSales = (float) DB::table('payments')
                ->where('commercial_admin_id', $adminId)
                ->where('status', 'completed')
                ->where(function ($q) use ($monthStart) {
                    $q->where('paid_at', '>=', $monthStart)->orWhere(function ($q2) use ($monthStart) {
                        $q2->whereNull('paid_at')->where('created_at', '>=', $monthStart);
                    });
                })
                ->sum('amount');

            $rateBp = (int) ($commercial->commission_rate_bp ?? 0);
            $commercialCommission = $rateBp > 0 ? (int) round($commercialSales * ($rateBp / 10000)) : 0;
        }

        return view('admin.payroll.me', [
            'admin' => $admin,
            'profiles' => $profiles,
            'year' => $year,
            'month' => $month,
            'commercialSalesMonth' => (int) round($commercialSales),
            'commercialCommissionMonth' => $commercialCommission,
            'kpiAvg' => $kpiAvg,
            'baseTotal' => $baseTotal,
            'earnedTotal' => $earnedTotal,
            'breakdown' => $breakdown->all(),
        ]);
    }
}
