<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AssistantTasksController extends Controller
{
    public function index(): View
    {
        $adminId = (int) session('admin_id');
        $year = (int) now()->year;
        $month = (int) now()->month;
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $taskTypes = collect();
        $logsByType = collect();

        if (Schema::hasTable('admin_task_types')) {
            $taskTypes = DB::table('admin_task_types')
                ->where('role', 'assistant')
                ->where('is_active', 1)
                ->orderBy('id')
                ->get();
        }

        if (Schema::hasTable('admin_task_logs')) {
            $logsByType = DB::table('admin_task_logs')
                ->select('task_type_id', DB::raw('SUM(quantity) as qty'))
                ->where('admin_id', $adminId)
                ->whereBetween('performed_at', [$monthStart, $monthEnd])
                ->groupBy('task_type_id')
                ->pluck('qty', 'task_type_id');
        }

        $salaryMonth = null;
        if (Schema::hasTable('admin_salary_months')) {
            $salaryMonth = DB::table('admin_salary_months')
                ->where('admin_id', $adminId)
                ->where('year', $year)
                ->where('month', $month)
                ->first();
        }

        $isCompliant = (bool) ($salaryMonth->is_compliant ?? true);
        $baseAmount = (int) ($salaryMonth->base_amount ?? 25000);
        $effectiveBase = $isCompliant ? $baseAmount : (int) floor($baseAmount / 2);

        $totalUnits = (int) $taskTypes->sum(function ($t) {
            return (int) ($t->expected_per_month ?? 0);
        });

        $unitValue = $totalUnits > 0 ? ($effectiveBase / $totalUnits) : 0;

        $creditedUnits = 0;
        foreach ($taskTypes as $t) {
            $done = (int) ($logsByType[$t->id] ?? 0);
            $expected = (int) ($t->expected_per_month ?? 0);
            $creditedUnits += min($done, $expected);
        }

        $earnedThisMonth = (int) round($creditedUnits * $unitValue);

        return view('admin.assistant.tasks', [
            'taskTypes' => $taskTypes,
            'logsByType' => $logsByType,
            'isCompliant' => $isCompliant,
            'baseAmount' => $baseAmount,
            'effectiveBase' => $effectiveBase,
            'totalUnits' => $totalUnits,
            'unitValue' => $unitValue,
            'creditedUnits' => $creditedUnits,
            'earnedThisMonth' => $earnedThisMonth,
        ]);
    }

    public function store(Request $request)
    {
        $adminId = (int) session('admin_id');

        $validated = $request->validate([
            'task_type_id' => 'required|integer',
            'quantity' => 'nullable|integer|min:1|max:100',
        ]);

        if (!Schema::hasTable('admin_task_logs')) {
            return redirect()->back()->with('error', 'Module tâches non disponible');
        }

        $taskType = null;
        if (Schema::hasTable('admin_task_types')) {
            $taskType = DB::table('admin_task_types')
                ->where('id', (int) $validated['task_type_id'])
                ->where('role', 'assistant')
                ->where('is_active', 1)
                ->first();
        }

        if (!$taskType) {
            return redirect()->back()->with('error', 'Tâche introuvable');
        }

        DB::table('admin_task_logs')->insert([
            'admin_id' => $adminId,
            'task_type_id' => (int) $validated['task_type_id'],
            'quantity' => (int) ($validated['quantity'] ?? 1),
            'performed_at' => now(),
            'meta' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Tâche enregistrée');
    }
}
