<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Directive Blade personnalisée pour vérifier si le compte peut soumettre du contenu
        Blade::if('canCreate', function () {
            $sharedData = View::getShared();
            return isset($sharedData['canSubmitContent']) ? $sharedData['canSubmitContent'] : true;
        });

        // Directive pour vérifier si le compte est expiré
        Blade::if('accountExpired', function () {
            $sharedData = View::getShared();
            return isset($sharedData['isAccountExpired']) ? $sharedData['isAccountExpired'] : false;
        });

        // Partager toujours le préfixe de formation pour les vues (notamment ki-admin)
        View::composer('*', function ($view) {
            $currentRoute = request()->route()?->getName() ?? '';
            $formationPrefix = 'design-graphique';

            if (str_contains($currentRoute, 'design-graphique-cm')) {
                $formationPrefix = 'design-graphique-cm';
            } elseif (str_contains($currentRoute, 'community-manager') || str_contains($currentRoute, 'community-management')) {
                $formationPrefix = 'community-management';
            } elseif (str_contains($currentRoute, 'intelligence-artificielle')) {
                $formationPrefix = 'intelligence-artificielle';
            } elseif (str_contains($currentRoute, 'gestion-informatique')) {
                $formationPrefix = 'gestion-informatique';
            }

            $view->with('formationPrefix', $formationPrefix);
        });
    }
}
