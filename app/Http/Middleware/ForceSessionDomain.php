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
            config(['session.domain' => '.ecolevirtuelledescreatifs.com']);
            config(['session.secure' => true]);
            config(['session.same_site' => 'lax']);
        }

        return $next($request);
    }
}
