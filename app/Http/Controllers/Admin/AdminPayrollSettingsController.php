<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminPayrollSettingsController extends Controller
{
    private const KPI_TASK_CATALOG = [
        [
            'key' => 'validate_projects',
            'label' => 'Valider les projets',
            'default_recurrence' => 'monthly',
            'default_expected_per_month' => 0,
            'default_deadline_hours' => 48,
            'default_weight' => 10,
            'default_is_critical' => true,
        ],
        [
            'key' => 'followup_student_payments',
            'label' => 'Relancer les paiements des étudiants',
            'default_recurrence' => 'weekly',
            'default_expected_per_month' => 0,
            'default_deadline_hours' => 24,
            'default_weight' => 10,
            'default_is_critical' => true,
        ],
        [
            'key' => 'followup_profile_completion',
            'label' => 'Relancer les étudiants pour compléter leur profil',
            'default_recurrence' => 'weekly',
            'default_expected_per_month' => 0,
            'default_deadline_hours' => 72,
            'default_weight' => 8,
            'default_is_critical' => false,
        ],
        [
            'key' => 'followup_training_reports',
            'label' => 'Relancer les étudiants pour déposer leur rapport',
            'default_recurrence' => 'weekly',
            'default_expected_per_month' => 0,
            'default_deadline_hours' => 72,
            'default_weight' => 8,
            'default_is_critical' => false,
        ],
    ];

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

        $catalog = collect(self::KPI_TASK_CATALOG);
        $selectedCatalogKeys = $taskTypes
            ->pluck('kpi_catalog_key')
            ->filter(fn ($v) => is_string($v) && $v !== '')
            ->unique()
            ->values()
            ->all();

        return view('admin.payroll_settings.profile_tasks', [
            'profile' => $profile,
            'taskTypes' => $taskTypes,
            'kpiCatalog' => $catalog,
            'selectedCatalogKeys' => $selectedCatalogKeys,
        ]);
    }

    public function storeTaskCatalog(Request $request, int $profileId)
    {
        if (session('admin_role') !== 'super_admin') {
            abort(403);
        }

        if (!Schema::hasTable('admin_job_profiles')) {
            return redirect()->route('admin.payroll.settings.index')->with('error', 'Module profils indisponible');
        }
        if (!Schema::hasTable('admin_task_types')) {
            return redirect()->back()->with('error', 'Module tâches indisponible');
        }
        if (!Schema::hasColumn('admin_task_types', 'job_profile_id')) {
            return redirect()->back()->with('error', 'La colonne job_profile_id est manquante. Lance les migrations KPI.');
        }

        $profile = DB::table('admin_job_profiles')->where('id', $profileId)->first(['id', 'code']);
        if (!$profile) {
            return redirect()->route('admin.payroll.settings.index')->with('error', 'Profil introuvable');
        }

        $catalogByKey = collect(self::KPI_TASK_CATALOG)->keyBy('key');

        $validated = $request->validate([
            'task_keys' => 'required|array|min:1',
            'task_keys.*' => 'string',
        ]);

        $taskKeys = collect($validated['task_keys'] ?? [])
            ->filter(fn ($k) => is_string($k) && $catalogByKey->has($k))
            ->unique()
            ->values()
            ->all();

        if (count($taskKeys) === 0) {
            return redirect()->back()->with('error', 'Aucune tâche valide sélectionnée');
        }

        if (!Schema::hasColumn('admin_task_types', 'kpi_catalog_key')) {
            return redirect()->back()->with('error', 'La colonne kpi_catalog_key est manquante. Lance les migrations KPI.');
        }

        $now = now();
        $created = 0;
        foreach ($taskKeys as $key) {
            $task = (array) $catalogByKey->get($key);
            $code = $key . '_p' . (int) $profileId;

            $existsForProfile = DB::table('admin_task_types')
                ->where('job_profile_id', $profileId)
                ->where('kpi_catalog_key', $key)
                ->exists();
            if ($existsForProfile) {
                continue;
            }

            $codeExists = DB::table('admin_task_types')->where('code', $code)->exists();
            if ($codeExists) {
                continue;
            }

            $payload = [
                'code' => $code,
                'label' => (string) ($task['label'] ?? $key),
                'role' => 'profile',
                'recurrence' => (string) ($task['default_recurrence'] ?? 'monthly'),
                'expected_per_month' => (int) ($task['default_expected_per_month'] ?? 0),
                'is_active' => true,
                'job_profile_id' => (int) $profileId,
                'kpi_catalog_key' => $key,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (Schema::hasColumn('admin_task_types', 'weight')) {
                $payload['weight'] = (int) ($task['default_weight'] ?? 10);
            }
            if (Schema::hasColumn('admin_task_types', 'deadline_hours')) {
                $payload['deadline_hours'] = (int) ($task['default_deadline_hours'] ?? 0);
            }
            if (Schema::hasColumn('admin_task_types', 'is_critical')) {
                $payload['is_critical'] = (bool) ($task['default_is_critical'] ?? false);
            }

            DB::table('admin_task_types')->insert($payload);
            $created++;
        }

        if ($created === 0) {
            return redirect()->back()->with('success', 'Aucune nouvelle tâche à ajouter');
        }

        return redirect()->route('admin.payroll.settings.profile.tasks', ['profileId' => (int) $profileId])->with('success', $created . ' tâche(s) ajoutée(s)');
    }

    public function storeTaskType(Request $request, int $profileId)
    {
        if (session('admin_role') !== 'super_admin') {
            abort(403);
        }

        if (!Schema::hasTable('admin_job_profiles')) {
            return redirect()->route('admin.payroll.settings.index')->with('error', 'Module profils indisponible');
        }
        if (!Schema::hasTable('admin_task_types')) {
            return redirect()->back()->with('error', 'Module tâches indisponible');
        }
        if (!Schema::hasColumn('admin_task_types', 'job_profile_id')) {
            return redirect()->back()->with('error', 'La colonne job_profile_id est manquante. Lance les migrations KPI.');
        }

        $profile = DB::table('admin_job_profiles')->where('id', $profileId)->first(['id', 'label', 'code']);
        if (!$profile) {
            return redirect()->route('admin.payroll.settings.index')->with('error', 'Profil introuvable');
        }

        $validated = $request->validate([
            'code' => 'required|string|max:255|regex:/^[a-z0-9_]+$/',
            'label' => 'required|string|max:255',
            'expected_per_month' => 'nullable|integer|min:0|max:1000000',
            'weight' => 'nullable|integer|min:0|max:1000',
            'deadline_hours' => 'nullable|integer|min:0|max:100000',
            'is_critical' => 'required|boolean',
            'is_active' => 'required|boolean',
        ]);

        $exists = DB::table('admin_task_types')->where('code', $validated['code'])->exists();
        if ($exists) {
            return redirect()->back()->withInput()->withErrors([
                'code' => 'Ce code existe déjà. Utilise un code unique (ex: kpi_support_ticket).',
            ]);
        }

        $payload = [
            'code' => $validated['code'],
            'label' => $validated['label'],
            'role' => 'profile',
            'recurrence' => 'monthly',
            'expected_per_month' => (int) ($validated['expected_per_month'] ?? 0),
            'is_active' => (bool) $validated['is_active'],
            'job_profile_id' => (int) $profileId,
            'created_at' => now(),
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

        DB::table('admin_task_types')->insert($payload);

        return redirect()->route('admin.payroll.settings.profile.tasks', ['profileId' => (int) $profileId])->with('success', 'Condition KPI ajoutée');
    }

    public function updateTaskType(Request $request, int $taskTypeId)
    {
        if (session('admin_role') !== 'super_admin') {
            abort(403);
        }

        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'recurrence' => 'nullable|string|in:daily,weekly,monthly',
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

        if (Schema::hasColumn('admin_task_types', 'recurrence') && !empty($validated['recurrence'])) {
            $payload['recurrence'] = (string) $validated['recurrence'];
        }

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
