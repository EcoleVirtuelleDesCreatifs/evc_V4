<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminPayrollSettingsController extends Controller
{
    public function index(): View
    {
        if (session('admin_role') !== 'super_admin') {
            abort(403);
        }

        $moduleAvailable = Schema::hasTable('admin_job_profiles');

        $profiles = collect();
        if ($moduleAvailable) {
            $profiles = DB::table('admin_job_profiles')
                ->orderBy('label')
                ->get(['id', 'code', 'label', 'base_monthly_amount', 'commission_rate_bp', 'is_active']);
        }

        $taskCountsByProfile = collect();
        if (Schema::hasTable('admin_task_types') && Schema::hasColumn('admin_task_types', 'job_profile_id')) {
            $taskCountsByProfile = DB::table('admin_task_types')
                ->select('job_profile_id', DB::raw('COUNT(*) as cnt'))
                ->groupBy('job_profile_id')
                ->pluck('cnt', 'job_profile_id');
        }

        return view('admin.payroll_settings.index', [
            'moduleAvailable' => $moduleAvailable,
            'profiles' => $profiles,
            'taskCountsByProfile' => $taskCountsByProfile,
        ]);
    }

    public function createProfile(): View
    {
        if (session('admin_role') !== 'super_admin') {
            abort(403);
        }

        if (!Schema::hasTable('admin_job_profiles')) {
            abort(404);
        }

        return view('admin.payroll_settings.profile_create');
    }

    public function storeProfile(Request $request)
    {
        if (session('admin_role') !== 'super_admin') {
            abort(403);
        }

        if (!Schema::hasTable('admin_job_profiles')) {
            return redirect()->route('admin.payroll.settings.index')->with('error', 'Module profils indisponible');
        }

        $validated = $request->validate([
            'code' => 'required|string|max:255|regex:/^[a-z0-9_]+$/',
            'label' => 'required|string|max:255',
            'base_monthly_amount' => 'required|integer|min:0|max:999999999',
            'commission_rate_bp' => 'nullable|integer|min:0|max:10000',
            'is_active' => 'required|boolean',
        ]);

        $exists = DB::table('admin_job_profiles')->where('code', $validated['code'])->exists();
        if ($exists) {
            return redirect()->back()->withInput()->withErrors([
                'code' => 'Ce code existe déjà. Utilise un code unique (ex: assistant, commercial).',
            ]);
        }

        DB::table('admin_job_profiles')->insert([
            'code' => $validated['code'],
            'label' => $validated['label'],
            'base_monthly_amount' => (int) $validated['base_monthly_amount'],
            'commission_rate_bp' => (int) ($validated['commission_rate_bp'] ?? 0),
            'is_active' => (bool) $validated['is_active'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.payroll.settings.index')->with('success', 'Profil créé');
    }

    public function editProfile(int $profileId): View
    {
        if (session('admin_role') !== 'super_admin') {
            abort(403);
        }

        if (!Schema::hasTable('admin_job_profiles')) {
            abort(404);
        }

        $profile = DB::table('admin_job_profiles')->where('id', $profileId)->first();
        if (!$profile) {
            abort(404);
        }

        return view('admin.payroll_settings.profile_edit', [
            'profile' => $profile,
        ]);
    }

    public function updateProfile(Request $request, int $profileId)
    {
        if (session('admin_role') !== 'super_admin') {
            abort(403);
        }

        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'base_monthly_amount' => 'required|integer|min:0|max:999999999',
            'commission_rate_bp' => 'nullable|integer|min:0|max:10000',
            'is_active' => 'required|boolean',
        ]);

        if (!Schema::hasTable('admin_job_profiles')) {
            return redirect()->back()->with('error', 'Module profils indisponible');
        }

        DB::table('admin_job_profiles')
            ->where('id', $profileId)
            ->update([
                'label' => $validated['label'],
                'base_monthly_amount' => (int) $validated['base_monthly_amount'],
                'commission_rate_bp' => (int) ($validated['commission_rate_bp'] ?? 0),
                'is_active' => (bool) $validated['is_active'],
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.payroll.settings.index')->with('success', 'Profil mis à jour');
    }

    public function profileTasks(int $profileId): View
    {
        if (session('admin_role') !== 'super_admin') {
            abort(403);
        }

        if (!Schema::hasTable('admin_job_profiles')) {
            abort(404);
        }

        $profile = DB::table('admin_job_profiles')->where('id', $profileId)->first();
        if (!$profile) {
            abort(404);
        }

        $taskTypes = collect();
        if (Schema::hasTable('admin_task_types')) {
            $q = DB::table('admin_task_types')->orderBy('id');

            if (Schema::hasColumn('admin_task_types', 'job_profile_id')) {
                $q->where('job_profile_id', $profileId);
            } else {
                $q->whereRaw('1 = 0');
            }

            $taskTypes = $q->get();
        }

        return view('admin.payroll_settings.profile_tasks', [
            'profile' => $profile,
            'taskTypes' => $taskTypes,
        ]);
    }

    public function updateTaskType(Request $request, int $taskTypeId)
    {
        if (session('admin_role') !== 'super_admin') {
            abort(403);
        }

        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'expected_per_month' => 'nullable|integer|min:0|max:1000000',
            'weight' => 'nullable|integer|min:0|max:1000',
            'deadline_hours' => 'nullable|integer|min:0|max:100000',
            'is_critical' => 'required|boolean',
            'is_active' => 'required|boolean',
        ]);

        if (!Schema::hasTable('admin_task_types')) {
            return redirect()->back()->with('error', 'Module tâches indisponible');
        }

        $task = DB::table('admin_task_types')->where('id', $taskTypeId)->first(['id', 'job_profile_id']);
        if (!$task) {
            return redirect()->back()->with('error', 'Tâche introuvable');
        }

        $payload = [
            'label' => $validated['label'],
            'expected_per_month' => (int) ($validated['expected_per_month'] ?? 0),
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('admin_task_types', 'weight')) {
            $payload['weight'] = (int) ($validated['weight'] ?? 10);
        }
        if (Schema::hasColumn('admin_task_types', 'deadline_hours')) {
            $payload['deadline_hours'] = (int) ($validated['deadline_hours'] ?? 0);
        }
        if (Schema::hasColumn('admin_task_types', 'is_critical')) {
            $payload['is_critical'] = (bool) $validated['is_critical'];
        }
        if (Schema::hasColumn('admin_task_types', 'is_active')) {
            $payload['is_active'] = (bool) $validated['is_active'];
        }

        DB::table('admin_task_types')->where('id', $taskTypeId)->update($payload);

        $profileId = (int) ($task->job_profile_id ?? 0);
        if ($profileId > 0) {
            return redirect()->route('admin.payroll.settings.profile.tasks', ['profileId' => $profileId])->with('success', 'Conditions mises à jour');
        }

        return redirect()->back()->with('success', 'Conditions mises à jour');
    }
}
