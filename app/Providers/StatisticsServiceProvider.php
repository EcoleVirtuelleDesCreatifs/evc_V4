<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\StatisticsService;
use App\Services\AdminService;

/**
 * Service Provider pour les services de statistiques
 * Architecture Laravel propre avec injection de dépendance
 */
class StatisticsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Enregistrement du service de statistiques
        $this->app->singleton(StatisticsService::class, function ($app) {
            return new StatisticsService();
        });
        
        // Enregistrement du service d'administration
        $this->app->singleton(AdminService::class, function ($app) {
            return new AdminService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
