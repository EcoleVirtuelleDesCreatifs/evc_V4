<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventPwaCache
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // On évite que Safari en PWA garde une page HTML en cache avec un token périmé
        $contentType = (string) $response->headers->get('Content-Type', '');

        if (str_contains($contentType, 'text/html')) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
            $response->headers->remove('ETag');
            $response->headers->remove('Last-Modified');
        }

        return $response;
    }
}
