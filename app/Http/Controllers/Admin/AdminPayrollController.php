<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminPayrollController extends Controller
{
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

        $commercialSalesByAdmin = collect();
        if (!empty($adminIds) && Schema::hasTable('payments') && Schema::hasColumn('payments', 'commercial_admin_id')) {
            $commercialSalesByAdmin = DB::table('payments')
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

        $rows = $admins->map(function ($a) use ($profilesByAdmin, $profilesById, $commercialSalesByAdmin, $commercialRateBp) {
            $assigned = $profilesByAdmin[$a->id] ?? collect();
            $assignedLabels = $assigned->map(function ($pid) use ($profilesById) {
                return $profilesById[$pid]->label ?? null;
            })->filter()->values();

            $commercialSales = (float) ($commercialSalesByAdmin[$a->id] ?? 0);
            $commission = 0;
            if ($commercialRateBp > 0) {
                $commission = (int) round($commercialSales * ($commercialRateBp / 10000));
            }

            return [
                'id' => (int) $a->id,
                'name' => $a->name,
                'email' => $a->email,
                'role' => $a->role,
                'can_view_salary_amount' => (bool) ($a->can_view_salary_amount ?? false),
                'profile_ids' => $assigned,
                'profile_labels' => $assignedLabels,
                'commercial_sales_month' => (int) round($commercialSales),
                'commercial_commission_month' => $commission,
            ];
        })->values();

        return view('admin.payroll.index', [
            'year' => $year,
            'month' => $month,
            'profiles' => $profiles,
            'rows' => $rows,
            'commercial_rate_bp' => $commercialRateBp,
            'monthStart' => $monthStart,
            'monthEnd' => $monthEnd,
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
        ]);
    }
}
