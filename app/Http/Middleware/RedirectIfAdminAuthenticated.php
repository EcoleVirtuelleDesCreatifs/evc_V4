<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAdminAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if admin is already authenticated
        if ($this->isAdminAuthenticated()) {
            // Redirect to admin dashboard with a message
            return redirect()->route('admin.dashboard')->with('info', 'Vous êtes déjà connecté en tant qu\'administrateur.');
        }

        return $next($request);
    }

    /**
     * Check if admin is authenticated
     */
    private function isAdminAuthenticated(): bool
    {
        return session('admin_logged_in', false) && 
               session('admin_id') && 
               session('admin_email');
    }
}
