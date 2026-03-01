<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AccountExpirationHelper;
use Illuminate\Support\Facades\Schema;
use App\Models\Partnership;

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
            $activePartnership = null;
            $activePartnerships = collect();
            try {
                if (Schema::hasTable('partnerships')) {
                    $activePartnerships = Partnership::query()
                        ->where('is_active', true)
                        ->orderBy('name')
                        ->get();

                    $activePartnership = $activePartnerships->first();
                }
            } catch (\Throwable $e) {
                $activePartnership = null;
                $activePartnerships = collect();
            }

            $view->with('activePartnership', $activePartnership);
            $view->with('activePartnerships', $activePartnerships);

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
