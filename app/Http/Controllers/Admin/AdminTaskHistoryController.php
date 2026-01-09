<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminTaskHistoryController extends Controller
{
    public function index(Request $request): View
    {
        if (session('admin_role') !== 'super_admin') {
            abort(403);
        }

        if (!Schema::hasTable('admin_task_logs')) {
            abort(404);
        }

        $adminId = $request->query('admin_id');
        $taskTypeId = $request->query('task_type_id');
        $from = $request->query('from');
        $to = $request->query('to');

        $q = DB::table('admin_task_logs as l')
            ->leftJoin('admins as a', 'l.admin_id', '=', 'a.id')
            ->leftJoin('admin_task_types as t', 'l.task_type_id', '=', 't.id')
            ->orderByDesc('l.performed_at');

        if (is_numeric($adminId)) {
            $q->where('l.admin_id', (int) $adminId);
        }

        if (is_numeric($taskTypeId)) {
            $q->where('l.task_type_id', (int) $taskTypeId);
        }

        if (is_string($from) && $from !== '') {
            $q->where('l.performed_at', '>=', $from);
        }

        if (is_string($to) && $to !== '') {
            $q->where('l.performed_at', '<=', $to);
        }

        $logs = $q->get([
            'l.id',
            'l.admin_id',
            'l.task_type_id',
            'l.quantity',
            'l.performed_at',
            'a.name as admin_name',
            'a.email as admin_email',
            't.label as task_label',
            Schema::hasColumn('admin_task_types', 'deadline_hours') ? 't.deadline_hours' : DB::raw('NULL as deadline_hours'),
        ]);

        $admins = collect();
        if (Schema::hasTable('admins')) {
            $admins = DB::table('admins')
                ->orderBy('name')
                ->get(['id', 'name', 'email']);
        }

        $taskTypes = collect();
        if (Schema::hasTable('admin_task_types')) {
            $taskTypes = DB::table('admin_task_types')
                ->orderBy('label')
                ->get(['id', 'label']);
        }

        $totalQuantity = (int) $logs->sum(fn ($l) => (int) ($l->quantity ?? 0));
        $totalEstimatedHours = 0;
        foreach ($logs as $l) {
            $dh = (int) ($l->deadline_hours ?? 0);
            if ($dh > 0) {
                $totalEstimatedHours += ((int) ($l->quantity ?? 0)) * $dh;
            }
        }

        return view('admin.payroll.task_history', [
            'logs' => $logs,
            'admins' => $admins,
            'taskTypes' => $taskTypes,
            'filters' => [
                'admin_id' => $adminId,
                'task_type_id' => $taskTypeId,
                'from' => $from,
                'to' => $to,
            ],
            'totalQuantity' => $totalQuantity,
            'totalEstimatedHours' => $totalEstimatedHours,
        ]);
    }
}
