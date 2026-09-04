<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceAdminController extends Controller
{
    public function index(Request $request, AttendanceService $service): View
    {
        $filters = $request->only([
            'formation',
            'session_id',
            'student_id',
            'formateur',
            'date',
            'from',
            'to',
            'mode',
            'status',
        ]);

        $stats = $service->getAdminDashboard($filters);

        $formations = Student::select('program')
            ->whereNotNull('program')
            ->distinct()
            ->orderBy('program')
            ->pluck('program');

        $students = Student::with('user')
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get();

        return view('admin.attendance.index', compact('stats', 'formations', 'students', 'filters'));
    }
}
