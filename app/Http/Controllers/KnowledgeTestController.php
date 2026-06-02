<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class KnowledgeTestController extends Controller
{
    public function index(): View
    {
        $student = DB::table('students')->where('user_id', session('user_id'))->first();
        $tests = collect();

        return view('knowledge-tests.index', compact('student', 'tests'));
    }
}
