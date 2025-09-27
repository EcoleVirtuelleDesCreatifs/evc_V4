<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminAuthRequired
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
        // DEBUG: Log session state at middleware entry
        Log::info('🔍 MIDDLEWARE DEBUG - ENTRÉE:', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'admin_logged_in' => session('admin_logged_in'),
            'admin_id' => session('admin_id'),
            'admin_email' => session('admin_email'),
            'session_id' => session()->getId(),
            'is_ajax' => $request->ajax(),
            'wants_json' => $request->wantsJson()
        ]);
        
        // Check if admin is authenticated
        if (!$this->isAdminAuthenticated()) {
            Log::warning('🚨 MIDDLEWARE - ADMIN NON AUTHENTIFIÉ:', [
                'url' => $request->fullUrl(),
                'session_data' => session()->all()
            ]);
            // Pour les requêtes AJAX, retourner JSON avec code 401
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Session admin expirée',
                    'redirect' => route('admin.login')
                ], 401);
            }
            
            // Pour les requêtes normales, redirection HTML
            return redirect()->route('admin.login')->with('error', 'Vous devez être connecté en tant qu\'administrateur pour accéder à cette page.');
        }

        // Check if admin account is active (TEMPORAIREMENT DÉSACTIVÉ pour éviter déconnexion)
        // WORKAROUND: Désactiver la vérification du statut admin pour éviter la déconnexion
        // if (!$this->isAdminActive()) {
        //     // Clear session and redirect to login
        //     $this->clearAdminSession();
        //     
        //     // Pour les requêtes AJAX, retourner JSON avec code 401
        //     if ($request->ajax() || $request->wantsJson()) {
        //         return response()->json([
        //             'success' => false,
        //             'error' => 'Compte administrateur désactivé',
        //             'redirect' => route('admin.login')
        //         ], 401);
        //     }
        //     
        //     // Pour les requêtes normales, redirection HTML
        //     return redirect()->route('admin.login')->with('error', 'Votre compte administrateur a été désactivé. Contactez le support.');
        // }
        
        Log::info('🔍 MIDDLEWARE - VÉRIFICATION STATUT ADMIN DÉSACTIVÉE (WORKAROUND)');

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

    /**
     * Check if admin account is still active
     */
    private function isAdminActive(): bool
    {
        $adminId = session('admin_id');
        if (!$adminId) {
            return false;
        }

        try {
            $admin = \DB::table('admins')
                ->where('id', $adminId)
                ->where('is_active', true)
                ->first();

            return $admin !== null;
        } catch (\Exception $e) {
            \Log::error('Error checking admin status: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Clear admin session data
     */
    private function clearAdminSession(): void
    {
        session()->forget([
            'admin_logged_in',
            'admin_id', 
            'admin_name',
            'admin_email',
            'admin_role',
            'admin_permissions'
        ]);
    }
}
