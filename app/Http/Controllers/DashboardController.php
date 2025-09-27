<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Redirect to the login page to ensure stability.
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('login'); 
    }

    /**
     * Placeholder for the design graphique dashboard.
     */
    public function designGraphique(): View
    {
        return view('dashboard.design-graphique', ['data' => 'placeholder']);
    }

    /**
     * A placeholder for the showAllTP method to prevent crashes.
     */
    public function showAllTP(): View
    {
        return view('tp.view', [
            'projects' => [],
            'stats' => [],
            'userProfile' => (object)[]
        ]);
    }
}
