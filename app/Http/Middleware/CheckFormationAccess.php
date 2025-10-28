<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckFormationAccess
{
    /**
     * Handle an incoming request.
     * Vérifie que l'utilisateur accède uniquement aux routes de son module de formation
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est authentifié via le système de session personnalisé
        if (!session('logged_in') || !session('user_id')) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Récupérer l'ID de l'utilisateur depuis la session
        $userId = session('user_id');
        
        // Récupérer la formation normalisée de l'utilisateur depuis la session
        $userFormation = session('user_formation');
        
        // Si la formation n'est pas en session, la récupérer depuis la base de données
        if (!$userFormation) {
            $student = DB::table('students')->where('user_id', $userId)->first();
            
            if (!$student || empty($student->program)) {
                // Si pas de formation trouvée, rediriger vers le dashboard par défaut
                return redirect()->route('dashboard.design-graphique')
                    ->with('error', 'Votre formation n\'a pas été trouvée. Veuillez contacter l\'administration.');
            }

            // Mapper les valeurs de program vers les préfixes de route
            $programMapping = [
                'Design Graphique' => 'design-graphique',
                'Community Management' => 'community-management',
                'Intelligence Artificielle' => 'intelligence-artificielle',
                'Gestion Informatique' => 'gestion-informatique',
                // Variantes en minuscules
                'design graphique' => 'design-graphique',
                'community management' => 'community-management',
                'intelligence artificielle' => 'intelligence-artificielle',
                'gestion informatique' => 'gestion-informatique',
            ];

            $userFormation = $programMapping[$student->program] ?? str_replace(['_', ' '], '-', strtolower($student->program));
        }

        // Récupérer le chemin de la requête
        $path = $request->path();

        // Extraire le module de formation depuis l'URL (ex: /evc/compte/community-management/...)
        if (preg_match('#^evc/compte/([^/]+)#', $path, $matches)) {
            $requestedFormation = $matches[1];

            // Vérifier si l'utilisateur essaie d'accéder à un autre module
            if ($requestedFormation !== $userFormation) {
                // Rediriger vers son propre module avec un message d'erreur
                $dashboardRoute = 'dashboard.' . $userFormation;
                
                return redirect()->route($dashboardRoute)
                    ->with('error', 'Vous n\'avez pas accès à ce module. Vous avez été redirigé vers votre espace.');
            }
        }

        // Si tout est OK, continuer la requête
        return $next($request);
    }
}
