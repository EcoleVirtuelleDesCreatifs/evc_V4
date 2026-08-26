<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceSessionDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = strtolower((string) $request->getHost());

        if ($host === 'www.ecolevirtuelledescreatifs.com' || $host === 'ecolevirtuelledescreatifs.com') {
            config(['session.domain' => 'ecolevirtuelledescreatifs.com']);
            config(['session.same_site' => 'lax']);

            // Le cookie ne doit être marqué Secure que si la connexion est HTTPS,
            // sinon Safari/Chrome refusent de l'envoyer et la session est recréée à chaque requête.
            if ($request->secure()) {
                config(['session.secure' => true]);
            }
        }

        return $next($request);
    }
}
