<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

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
    }
}
