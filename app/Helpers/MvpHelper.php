<?php

namespace App\Helpers;

class MvpHelper
{
    /**
     * Vérifie si le mode MVP est activé
     *
     * @return bool
     */
    public static function isEnabled(): bool
    {
        return config('mvp.enabled', true);
    }

    /**
     * Vérifie si une fonctionnalité est activée
     *
     * @param string $feature
     * @return bool
     */
    public static function isFeatureEnabled(string $feature): bool
    {
        if (!self::isEnabled()) {
            return true; // Si MVP désactivé, toutes les features sont activées
        }

        return config("mvp.features.{$feature}", false);
    }

    /**
     * Vérifie si un menu est visible
     *
     * @param string $type (admin|student)
     * @param string $menu
     * @return bool
     */
    public static function isMenuVisible(string $type, string $menu): bool
    {
        if (!self::isEnabled()) {
            return true;
        }

        return config("mvp.menus.{$type}.{$menu}", false);
    }

    /**
     * Vérifie si une route est accessible
     *
     * @param string $routeName
     * @return bool
     */
    public static function isRouteEnabled(string $routeName): bool
    {
        if (!self::isEnabled()) {
            return true;
        }

        $enabledRoutes = config('mvp.routes.enabled', []);
        $disabledRoutes = config('mvp.routes.disabled', []);

        // Vérifier si la route est explicitement désactivée
        foreach ($disabledRoutes as $pattern) {
            if (self::matchesPattern($routeName, $pattern)) {
                return false;
            }
        }

        // Vérifier si la route est dans la liste des activées
        foreach ($enabledRoutes as $pattern) {
            if (self::matchesPattern($routeName, $pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Vérifie si un nom de route correspond à un pattern
     *
     * @param string $routeName
     * @param string $pattern
     * @return bool
     */
    private static function matchesPattern(string $routeName, string $pattern): bool
    {
        // Convertir le pattern en regex
        $regex = str_replace(['*', '.'], ['.*', '\.'], $pattern);
        return preg_match("/^{$regex}$/", $routeName) === 1;
    }

    /**
     * Obtient le message pour une fonctionnalité désactivée
     *
     * @param string $type (feature_disabled|coming_soon|beta_notice)
     * @return string
     */
    public static function getMessage(string $type = 'feature_disabled'): string
    {
        return config("mvp.messages.{$type}", 'Cette fonctionnalité n\'est pas disponible.');
    }

    /**
     * Redirige vers une page d'information si la fonctionnalité est désactivée
     *
     * @param string $feature
     * @param string $redirectRoute
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public static function redirectIfDisabled(string $feature, string $redirectRoute = 'admin.dashboard')
    {
        if (!self::isFeatureEnabled($feature)) {
            return redirect()->route($redirectRoute)
                ->with('warning', self::getMessage('feature_disabled'));
        }

        return null;
    }

    /**
     * Obtient la limite configurée
     *
     * @param string $limit
     * @param mixed $default
     * @return mixed
     */
    public static function getLimit(string $limit, $default = null)
    {
        return config("mvp.limits.{$limit}", $default);
    }

    /**
     * Vérifie si les notifications sont activées pour un canal
     *
     * @param string $channel
     * @return bool
     */
    public static function isNotificationEnabled(string $channel): bool
    {
        return config("mvp.notifications.{$channel}_enabled", false);
    }

    /**
     * Obtient les canaux de notification pour un type d'événement
     *
     * @param string $event
     * @return array
     */
    public static function getNotificationChannels(string $event): array
    {
        if (!self::isEnabled()) {
            return ['email', 'database']; // Tous les canaux si MVP désactivé
        }

        return config("mvp.notifications.channels.{$event}", ['email']);
    }

    /**
     * Vérifie si le cache est activé
     *
     * @return bool
     */
    public static function isCacheEnabled(): bool
    {
        return config('mvp.performance.cache_enabled', true);
    }

    /**
     * Obtient la durée du cache
     *
     * @return int
     */
    public static function getCacheDuration(): int
    {
        return config('mvp.performance.cache_duration', 3600);
    }

    /**
     * Vérifie si une option UI est activée
     *
     * @param string $option
     * @return bool
     */
    public static function isUiOptionEnabled(string $option): bool
    {
        return config("mvp.ui.{$option}", false);
    }

    /**
     * Génère un badge MVP/Beta si activé
     *
     * @return string
     */
    public static function getBetaBadge(): string
    {
        if (!self::isEnabled() || !self::isUiOptionEnabled('show_beta_badge')) {
            return '';
        }

        return '<span class="badge badge-warning ml-2" style="font-size: 0.7rem;">BETA</span>';
    }

    /**
     * Liste toutes les fonctionnalités désactivées
     *
     * @return array
     */
    public static function getDisabledFeatures(): array
    {
        if (!self::isEnabled()) {
            return [];
        }

        $features = config('mvp.features', []);
        return array_keys(array_filter($features, function ($enabled) {
            return !$enabled;
        }));
    }

    /**
     * Liste toutes les fonctionnalités activées
     *
     * @return array
     */
    public static function getEnabledFeatures(): array
    {
        if (!self::isEnabled()) {
            return array_keys(config('mvp.features', []));
        }

        $features = config('mvp.features', []);
        return array_keys(array_filter($features, function ($enabled) {
            return $enabled;
        }));
    }
}
