<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
        // Supporte 2 modes d'auth : session custom (historique) OU Laravel Auth
        $authUser = Auth::user();

        Log::info('CheckFormationAccess: entrée', [
            'path' => $request->path(),
            'auth_check' => Auth::check(),
            'auth_user_id' => $authUser?->id,
            'auth_user_email' => $authUser?->email,
            'session_user_id' => session('user_id'),
            'session_user_formation' => session('user_formation'),
        ]);

        // Si Auth est disponible, le privilégier (évite une session custom résiduelle incohérente)
        $userId = $authUser?->id ?: session('user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Récupérer la formation normalisée de l'utilisateur depuis la session
        // Si Auth est actif, on évite d'utiliser user_formation de session (peut être obsolète)
        $userFormation = $authUser ? null : session('user_formation');

        // Normalisation : gérer les variantes (underscores, espaces, alias)
        if ($userFormation) {
            $userFormation = strtolower(trim((string) $userFormation));
            $userFormation = str_replace(['_', ' '], '-', $userFormation);
            if ($userFormation === 'community-manager') {
                $userFormation = 'community-management';
            }
        }

        // Si la formation n'est pas en session, la récupérer depuis la base de données
        if (!$userFormation) {
            $studentQuery = DB::table('students')->where('user_id', $userId);
            if ($authUser?->email) {
                $studentQuery->orWhere('email', $authUser->email);
            }
            $student = $studentQuery->first();

            Log::info('CheckFormationAccess: student chargé', [
                'user_id' => $userId,
                'student_id' => $student->id ?? null,
                'student_program' => $student->program ?? null,
            ]);

            if (!$student || empty($student->program)) {
                // Si pas de formation trouvée, rediriger vers le dashboard par défaut
                return redirect()->route('dashboard.design-graphique')
                    ->with('error', 'Votre formation n\'a pas été trouvée. Veuillez contacter l\'administration.');
            }

            // Mapper les valeurs de program vers les préfixes de route
            $programMapping = [
                'Design Graphique' => 'design-graphique',
                'Community Management' => 'community-management',
                'Community Manager' => 'community-management',
                'Intelligence Artificielle' => 'intelligence-artificielle',
                'Gestion Informatique' => 'gestion-informatique',
                // Variantes en minuscules
                'design graphique' => 'design-graphique',
                'community management' => 'community-management',
                'community manager' => 'community-management',
                'intelligence artificielle' => 'intelligence-artificielle',
                'gestion informatique' => 'gestion-informatique',
                // Formation hybride
                'Design Graphique & Community Manager' => 'design-graphique-cm',
                'Design Graphique & Community Management' => 'design-graphique-cm',
                'design graphique & community management' => 'design-graphique-cm',
                'Design Graphique Community Manager' => 'design-graphique-cm',
                'design_graphique_community_manager' => 'design-graphique-cm',
            ];

            $userFormation = $programMapping[$student->program] ?? str_replace(['_', ' '], '-', strtolower($student->program));

            // Normalisation finale (évite: community-manager vs community-management)
            $userFormation = strtolower(trim((string) $userFormation));
            $userFormation = str_replace(['_', ' '], '-', $userFormation);
            if ($userFormation === 'community-manager') {
                $userFormation = 'community-management';
            }
            if ($userFormation === 'design-graphique-community-manager' || $userFormation === 'design-graphique-community-management') {
                $userFormation = 'design-graphique-cm';
            }
            if ($userFormation === 'design-graphique-&-community-management' || $userFormation === 'design-graphique-&-community-manager') {
                $userFormation = 'design-graphique-cm';
            }
        }

        // Récupérer le chemin de la requête
        $path = $request->path();

        // Extraire le module de formation depuis l'URL (ex: /evc/compte/community-management/...)
        if (preg_match('#^evc/compte/([^/]+)#', $path, $matches)) {
            $requestedFormation = strtolower(trim((string) $matches[1]));
            $requestedFormation = str_replace(['_', ' '], '-', $requestedFormation);
            if ($requestedFormation === 'community-manager') {
                $requestedFormation = 'community-management';
            }

            // Normaliser la formation demandée
            $formationAliases = [
                'community-management' => ['community-manager', 'community_manager'],
                'design-graphique' => ['design_graphique'],
                'intelligence-artificielle' => ['intelligence_artificielle'],
                'gestion-informatique' => ['gestion_informatique'],
                'design-graphique-cm' => ['design-graphique-community-manager', 'design_graphique_community_manager', 'design-graphique-and-community-manager'],
            ];

            foreach ($formationAliases as $formation => $aliases) {
                if (in_array($requestedFormation, $aliases)) {
                    $requestedFormation = $formation;
                    break;
                }
            }

            // Vérifier si l'utilisateur essaie d'accéder à un autre module
            if ($requestedFormation !== $userFormation) {
                // Rediriger vers son propre module avec un message d'erreur
                $dashboardRoute = 'dashboard.' . $userFormation;

                Log::warning('CheckFormationAccess: accès refusé', [
                    'path' => $path,
                    'requestedFormation' => $requestedFormation,
                    'userFormation' => $userFormation,
                    'auth_user_id' => $authUser?->id,
                    'auth_user_email' => $authUser?->email,
                    'session_user_id' => session('user_id'),
                    'session_user_formation' => session('user_formation'),
                ]);

                return redirect()->route($dashboardRoute)
                    ->with('error', 'Vous n\'avez pas accès à ce module. Vous avez été redirigé vers votre espace.');
            }
        }

        // Si tout est OK, continuer la requête
        return $next($request);
    }
}
