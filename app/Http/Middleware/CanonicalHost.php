<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanonicalHost
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethodSafe()) {
            return $next($request);
        }

        $path = ltrim($request->path(), '/');
        if (str_starts_with($path, 'auth/evc/') || str_starts_with($path, 'evc/app/admin/')) {
            return $next($request);
        }

        $appUrl = (string) env('APP_URL', '');
        $canonicalHost = parse_url($appUrl, PHP_URL_HOST);
        $canonicalScheme = parse_url($appUrl, PHP_URL_SCHEME);

        if (!is_string($canonicalHost) || $canonicalHost === '') {
            return $next($request);
        }

        $currentHost = strtolower((string) $request->getHost());
        $targetHost = strtolower($canonicalHost);

        $isHttpsTarget = $canonicalScheme === 'https';
        $isHttpsCurrent = $request->isSecure();

        if ($currentHost !== $targetHost || ($isHttpsTarget && !$isHttpsCurrent)) {
            $scheme = $isHttpsTarget ? 'https' : ($isHttpsCurrent ? 'https' : 'http');
            $uri = $request->getRequestUri();
            $url = $scheme . '://' . $canonicalHost . $uri;

            return redirect()->to($url, 308);
        }

        return $next($request);
    }
}
