<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AccountExpirationHelper;

class CheckAccountExpiration
{
    /**
     * Handle an incoming request.
     * Bloque les actions de création/soumission si le compte est expiré
     * L'étudiant peut toujours consulter ses données
     */
    public function handle(Request $request, Closure $next)
    {
        // Ne pas vérifier sur les routes admin
        if ($request->is('evc/app/admin/*') || $request->is('admin/*')) {
            return $next($request);
        }

        // Ne pas vérifier sur les routes publiques
        if ($request->is('login') || $request->is('logout') || $request->is('compte-desactive')) {
            return $next($request);
        }

        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        // Ne pas vérifier si l'utilisateur a un rôle admin
        if (property_exists($user, 'role') && in_array($user->role, ['admin', 'super_admin', 'moderator'])) {
            return $next($request);
        }

        // Vérifier si le compte est expiré
        $isExpired = AccountExpirationHelper::isAccountExpired($user);

        if ($isExpired) {
            // Désactiver automatiquement le compte
            AccountExpirationHelper::checkAndDeactivateIfExpired($user);

            // Routes bloquées pour les comptes expirés (création/soumission)
            $blockedRoutes = [
                // TP - Toutes les actions de création/modification
                '*/tp/create',
                '*/tp/store',
                '*/tp/*/edit',
                '*/tp/*/update',
                '*/tp/*/submit',
                '*/tp/submit',

                // Projets - Toutes les actions de création/modification
                '*/projets/create',
                '*/projets/store',
                '*/projets/*/edit',
                '*/projets/*/update',
                '*/projets/*/submit',
                '*/projets/submit',

                // TODO/Tâches
                '*/todo/create',
                '*/todo/store',
                '*/todo/*/edit',
                '*/todo/*/update',
                '*/todo/*/complete',

                // Rapports/Documents
                '*/rapports/create',
                '*/rapports/store',
                '*/rapports/*/edit',
                '*/rapports/*/update',
                '*/documents/create',
                '*/documents/store',
                '*/documents/upload',
                '*/documents/*/edit',
                '*/documents/*/update',

                // CVthèque
                '*/cvtheque/create',
                '*/cvtheque/store',
                '*/cvtheque/*/edit',
                '*/cvtheque/*/update',
                '*/cvtheque/upload',

                // Fin de formation
                '*/fin-formation/create',
                '*/fin-formation/store',
                '*/fin-formation/*/edit',
                '*/fin-formation/*/update',
                '*/fin-formation/submit',

                // Bibliothèque
                '*/bibliotheque/*',

                // Formations (nouvelles inscriptions)
                '*/formations/*/enroll',
                '*/formations/*/subscribe',
                '*/formations/*/join',

                // Communauté
                '*/communaute/*',
            ];

            $currentPath = $request->path();

            foreach ($blockedRoutes as $pattern) {
                // Remplacer * par une regex
                $regex = str_replace('*', '[^/]+', $pattern);
                $regex = str_replace('/', '\/', $regex);

                if (preg_match('/^' . $regex . '$/i', $currentPath)) {
                    $dashboardRoute = 'dashboard.design-graphique';
                    if (strpos($currentPath, 'evc/compte/community-management/') !== false || strpos($currentPath, 'evc/compte/community-manager/') !== false) {
                        $dashboardRoute = 'dashboard.community-management';
                    } elseif (strpos($currentPath, 'evc/compte/design-graphique-cm/') !== false) {
                        $dashboardRoute = 'dashboard.design-graphique-cm';
                    } elseif (strpos($currentPath, 'evc/compte/intelligence-artificielle/') !== false) {
                        $dashboardRoute = 'dashboard.intelligence-artificielle';
                    } elseif (strpos($currentPath, 'evc/compte/gestion-informatique/') !== false) {
                        $dashboardRoute = 'dashboard.gestion-informatique';
                    }

                    return redirect()
                        ->route($dashboardRoute)
                        ->with('error', '⚠️ Votre compte a expiré. Cette section n\'est plus accessible. Veuillez contacter l\'administration pour renouveler votre accès.');
                }
            }

            // Bloquer les requêtes POST/PUT/DELETE (créations/modifications)
            if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                // Permettre seulement les routes de consultation
                $allowedPostRoutes = [
                    'logout',
                    'profile/update', // Mise à jour du profil
                ];

                $isAllowed = false;
                foreach ($allowedPostRoutes as $allowedRoute) {
                    if (strpos($currentPath, $allowedRoute) !== false) {
                        $isAllowed = true;
                        break;
                    }
                }

                if (!$isAllowed) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Votre compte a expiré. Vous ne pouvez plus soumettre de nouveaux contenus.'
                        ], 403);
                    }

                    return redirect()
                        ->back()
                        ->with('error', '⚠️ Votre compte a expiré. Vous ne pouvez plus soumettre de nouveaux contenus. Veuillez contacter l\'administration pour renouveler votre accès.');
                }
            }
        }

        return $next($request);
    }
}
