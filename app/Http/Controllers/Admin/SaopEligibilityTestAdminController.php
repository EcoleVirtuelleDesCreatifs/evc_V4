<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SaopEligibilityTest;
use Illuminate\Http\Request;

class SaopEligibilityTestAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = SaopEligibilityTest::query()->latest('submitted_at');

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('whatsapp', 'like', "%{$search}%");
            });
        }

        if ($formation = $request->get('formation')) {
            $query->where('formation', $formation);
        }

        $tests = $query->paginate(20)->withQueryString();
        $stats = [
            'total' => SaopEligibilityTest::count(),
            'today' => SaopEligibilityTest::whereDate('submitted_at', today())->count(),
            'this_month' => SaopEligibilityTest::where('submitted_at', '>=', now()->startOfMonth())->count(),
        ];

        return view('admin.eligibilite.index', compact('tests', 'stats'));
    }

    public function show(SaopEligibilityTest $test)
    {
        return view('admin.eligibilite.show', compact('test'));
    }
}
