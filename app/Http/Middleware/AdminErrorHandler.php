<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class AdminErrorHandler
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
        // TEMPORAIREMENT DÉSACTIVÉ POUR DEBUG - Laisser Laravel afficher les vraies erreurs
        return $next($request);
    }
    
    /**
     * Check if the current request is for an admin route
     */
    private function isAdminRoute(Request $request): bool
    {
        return str_starts_with($request->path(), 'evc/app/admin/') || 
               str_starts_with($request->route()?->getName() ?? '', 'admin.');
    }
}
