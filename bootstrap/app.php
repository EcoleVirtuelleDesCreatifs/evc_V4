<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.errors' => \App\Http\Middleware\AdminErrorHandler::class,
            'admin.guest' => \App\Http\Middleware\RedirectIfAdminAuthenticated::class,
            'admin.auth' => \App\Http\Middleware\AdminAuthRequired::class,
            'track.online' => \App\Http\Middleware\TrackOnlineStatus::class,
        ]);
        
        // Appliquer le middleware de suivi du statut en ligne sur toutes les routes web
        $middleware->web(append: [
            \App\Http\Middleware\TrackOnlineStatus::class,
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
