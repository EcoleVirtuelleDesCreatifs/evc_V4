<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackOnlineStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Traitement APRÈS la réponse pour ne pas ralentir l'utilisateur
        register_shutdown_function(function() use ($request) {
            $this->updateUserActivity($request);
        });

        return $response;
    }
    
    /**
     * Mettre à jour l'activité utilisateur
     */
    private function updateUserActivity(Request $request)
    {
        try {
            // Vérifier si l'utilisateur est connecté (plusieurs méthodes)
            $userId = session('user_id') ?? $request->session()->get('user_id') ?? null;
            $isLoggedIn = session('logged_in') ?? $request->session()->get('logged_in') ?? false;
            
            if ($userId && $isLoggedIn) {
                $sessionId = $request->session()->getId();
                
                // Mettre à jour le timestamp de dernière activité utilisateur
                DB::table('users')
                    ->where('id', $userId)
                    ->update([
                        'last_login' => now(),
                        'updated_at' => now()
                    ]);
                
                // Maintenir la session active avec user_id (persistant)
                if ($sessionId) {
                    // Nettoyer les anciennes sessions expirées de cet utilisateur (plus de 24h)
                    DB::table('sessions')
                        ->where('user_id', $userId)
                        ->where('last_activity', '<', time() - 86400) // 24 heures
                        ->delete();
                    
                    // Mettre à jour/créer la session actuelle (persistante)
                    DB::table('sessions')
                        ->updateOrInsert(
                            ['id' => $sessionId],
                            [
                                'user_id' => $userId,
                                'ip_address' => $request->ip(),
                                'user_agent' => $request->userAgent(),
                                'last_activity' => time(),
                                'payload' => base64_encode(serialize($request->session()->all()))
                            ]
                        );
                }
                
                // Log pour debugging (temporaire)
                error_log("TrackOnlineStatus: User $userId session $sessionId maintained as active");
            }
        } catch (\Exception $e) {
            error_log('Erreur TrackOnlineStatus: ' . $e->getMessage());
        }
    }
}
