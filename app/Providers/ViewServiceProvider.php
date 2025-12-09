<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AccountExpirationHelper;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Partager les variables d'expiration avec toutes les vues
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();

                $view->with('isAccountExpired', AccountExpirationHelper::isAccountExpired($user));
                $view->with('canSubmitContent', AccountExpirationHelper::canSubmitContent($user));
                $view->with('accountDaysRemaining', AccountExpirationHelper::getDaysRemaining($user));
            } else {
                $view->with('isAccountExpired', false);
                $view->with('canSubmitContent', true);
                $view->with('accountDaysRemaining', 0);
            }
        });
    }
}
