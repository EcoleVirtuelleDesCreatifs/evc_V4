<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminSalaryController extends Controller
{
    public function toggleAssistantMonthCompliance(Request $request, int $adminId)
    {
        if (session('admin_role') !== 'super_admin') {
            abort(403);
        }

        $validated = $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'required|integer|min:1|max:12',
            'is_compliant' => 'required|boolean',
        ]);

        if (!Schema::hasTable('admin_salary_months')) {
            return redirect()->back()->with('error', 'Module salaires non disponible');
        }

        DB::table('admin_salary_months')->updateOrInsert(
            [
                'admin_id' => $adminId,
                'year' => (int) $validated['year'],
                'month' => (int) $validated['month'],
            ],
            [
                'is_compliant' => (bool) $validated['is_compliant'],
                'base_amount' => 25000,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return redirect()->back()->with('success', 'Statut du mois mis à jour');
    }
}
