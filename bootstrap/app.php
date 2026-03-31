<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*', headers: Request::HEADER_X_FORWARDED_FOR
            | Request::HEADER_X_FORWARDED_HOST
            | Request::HEADER_X_FORWARDED_PORT
            | Request::HEADER_X_FORWARDED_PROTO
            | Request::HEADER_X_FORWARDED_PREFIX
            | Request::HEADER_X_FORWARDED_AWS_ELB);

        $middleware->alias([
            'admin.errors' => \App\Http\Middleware\AdminErrorHandler::class,
            'admin.guest' => \App\Http\Middleware\RedirectIfAdminAuthenticated::class,
            'admin.auth' => \App\Http\Middleware\AdminAuthRequired::class,
            'track.online' => \App\Http\Middleware\TrackOnlineStatus::class,
            'student.active' => \App\Http\Middleware\CheckStudentActive::class,
            'formation.access' => \App\Http\Middleware\CheckFormationAccess::class,
            'check.expiration' => \App\Http\Middleware\CheckAccountExpiration::class,
        ]);

        $middleware->web(prepend: [
            \App\Http\Middleware\CanonicalHost::class,
            \App\Http\Middleware\ForceSessionDomain::class,
        ]);

        // Appliquer le middleware de suivi du statut en ligne et vérification d'expiration sur toutes les routes web
        $middleware->web(append: [
            \App\Http\Middleware\TrackOnlineStatus::class,
            \App\Http\Middleware\CheckAccountExpiration::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            if (str_starts_with($request->path(), 'evc/app/admin/')) {
                return response()->view('admin.errors.404', [], 404);
            }
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, $request) {
            if (str_starts_with($request->path(), 'evc/app/admin/')) {
                return response()->view('admin.errors.403', [], 403);
            }
        });

        $exceptions->render(function (\Throwable $e, $request) {
            if (str_starts_with($request->path(), 'evc/app/admin/') && !app()->hasDebugModeEnabled()) {
                return response()->view('admin.errors.500', [], 500);
            }
        });
    })->create();
