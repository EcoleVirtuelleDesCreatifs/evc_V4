<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Helpers\AccountExpirationHelper;
use Symfony\Component\HttpFoundation\Response;

class CheckStudentActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // NE PAS vérifier si c'est une route admin
        if ($request->is('evc/app/admin/*') || $request->is('admin/*')) {
            return $next($request);
        }

        // Vérifier si l'utilisateur est authentifié
        if (Auth::check()) {
            $user = Auth::user();

            // NE PAS vérifier si l'utilisateur a un rôle admin
            if (property_exists($user, 'role') && in_array($user->role, ['admin', 'super_admin', 'moderator'])) {
                return $next($request);
            }

            // Vérifier si la table students existe et si l'étudiant a un compte désactivé
            if (Schema::hasTable('students')) {
                $student = DB::table('students')
                    ->where('email', $user->email)
                    ->first();

                // Si l'étudiant existe et que son statut est inactive
                if ($student && $student->status === 'inactive') {
                    // Si l'utilisateur tente d'accéder à la page de désactivation, le laisser passer
                    if ($request->is('compte-desactive')) {
                        return $next($request);
                    }

                    // Si le compte est expiré, laisser passer (les restrictions sont gérées ailleurs)
                    // Cela évite de bloquer des pages de consultation comme la communauté.
                    if (AccountExpirationHelper::isAccountExpired($user)) {
                        return $next($request);
                    }

                    // Vérifier s'il s'agit d'une désactivation manuelle (avec raison)
                    // ou d'une expiration automatique (sans raison)
                    $hasDeactivationReason = !empty($student->deactivation_reason);

                    // Si c'est une désactivation manuelle, bloquer complètement l'accès
                    if ($hasDeactivationReason) {
                        return redirect()->route('account.deactivated')
                            ->with('reason', $student->deactivation_reason)
                            ->with('deactivatedAt', $student->deactivated_at ?? null);
                    }

                    // Si c'est juste une expiration (pas de raison), laisser passer
                    // Les restrictions seront gérées par le middleware CheckAccountExpiration
                }
            }
        }

        return $next($request);
    }
}
