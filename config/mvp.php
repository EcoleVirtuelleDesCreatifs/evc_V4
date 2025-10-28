<?php

return [

    /*
    |--------------------------------------------------------------------------
    | MVP Mode
    |--------------------------------------------------------------------------
    |
    | Active ou désactive le mode MVP. Quand activé, certaines fonctionnalités
    | non essentielles seront désactivées pour simplifier l'application.
    |
    */

    'enabled' => env('MVP_MODE', true),

    /*
    |--------------------------------------------------------------------------
    | Features Flags
    |--------------------------------------------------------------------------
    |
    | Contrôle quelles fonctionnalités sont activées dans le MVP.
    | true = activé, false = désactivé
    |
    */

    'features' => [
        // ✅ CORE FEATURES (toujours activées)
        'auth' => true,
        'formations' => true,
        'students' => true,
        'tp' => true,
        'paiements' => true,
        'dashboard' => true,

        // 🟡 SECONDARY FEATURES (activables selon besoin)
        'bibliotheque' => true,
        'programmes' => true,
        'projets' => true,

        // ❌ DISABLED FOR MVP (désactivées temporairement)
        'actualites' => false,
        'evenements' => false,
        'cvtheque_complete' => false,
        'certificats' => false,
        'statistiques_avancees' => false,
        'galerie_publique' => false,
        'notifications_in_app' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Contrôle quels items de menu sont visibles selon le mode MVP.
    | Les menus désactivés ne seront pas affichés dans la navigation.
    |
    */

    'menus' => [
        'admin' => [
            'dashboard' => true,
            'formations' => true,
            'students' => true,
            'tp' => true,
            'projets' => true,
            'paiements' => true,
            'bibliotheque' => true,
            'programmes' => true,
            'actualites' => false,      // Désactivé pour MVP
            'evenements' => false,      // Désactivé pour MVP
            'cvtheque' => false,        // Désactivé pour MVP
            'certificats' => false,     // Désactivé pour MVP
            'statistiques' => true,     // Statistiques basiques uniquement
            'admins' => true,
        ],

        'student' => [
            'dashboard' => true,
            'formations' => true,
            'tp' => true,
            'projets' => true,
            'bibliotheque' => true,
            'programmes' => true,
            'documents' => true,
            'profil' => true,
            'actualites' => false,      // Désactivé pour MVP
            'evenements' => false,      // Désactivé pour MVP
            'cvtheque' => false,        // Désactivé pour MVP
            'communaute' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    |
    | Contrôle quelles routes sont accessibles en mode MVP.
    | Les routes désactivées retourneront une erreur 404 ou redirection.
    |
    */

    'routes' => [
        'enabled' => [
            'admin.dashboard',
            'admin.formations.*',
            'admin.students.*',
            'admin.tp.*',
            'admin.projets.*',
            'admin.paiements.*',
            'admin.bibliotheque.*',
            'admin.programmes.*',
            'admin.statistics.*',
            'admin.admins.*',
            
            'student.dashboard',
            'student.formations.*',
            'student.tp.*',
            'student.projets.*',
            'student.bibliotheque.*',
            'student.programmes.*',
            'student.documents.*',
            'student.profil.*',
        ],

        'disabled' => [
            'admin.articles.actualites.*',
            'admin.articles.evenements.*',
            'admin.cvtheque.*',
            'admin.certificats.*',
            
            'actualites.*',
            'evenements.*',
            'cvtheque.*',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance
    |--------------------------------------------------------------------------
    |
    | Paramètres d'optimisation des performances pour le MVP.
    |
    */

    'performance' => [
        'cache_enabled' => true,
        'cache_duration' => 3600, // 1 heure
        'lazy_loading' => true,
        'image_compression' => true,
        'minify_assets' => env('APP_ENV') === 'production',
    ],

    /*
    |--------------------------------------------------------------------------
    | Limits
    |--------------------------------------------------------------------------
    |
    | Limites pour éviter la surcharge en phase MVP.
    |
    */

    'limits' => [
        'max_upload_size' => 2048, // 2MB en KB
        'max_files_per_tp' => 5,
        'max_students_per_formation' => 100,
        'pagination_per_page' => 20,
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    |
    | Configuration des notifications pour le MVP.
    |
    */

    'notifications' => [
        'email_enabled' => true,
        'in_app_enabled' => false,  // Désactivé pour MVP
        'push_enabled' => false,    // Désactivé pour MVP
        
        'channels' => [
            'tp_assignment' => ['email'],
            'tp_submission' => ['email'],
            'tp_validation' => ['email'],
            'tp_rejection' => ['email'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | UI/UX
    |--------------------------------------------------------------------------
    |
    | Paramètres d'interface utilisateur pour le MVP.
    |
    */

    'ui' => [
        'animations_enabled' => true,
        'advanced_animations' => false,  // Animations complexes désactivées
        'theme_switcher' => false,       // Thème unique pour MVP
        'language_switcher' => false,    // Français uniquement pour MVP
        'show_beta_badge' => true,       // Afficher badge "Beta" ou "MVP"
    ],

    /*
    |--------------------------------------------------------------------------
    | Debug & Monitoring
    |--------------------------------------------------------------------------
    |
    | Paramètres de débogage et monitoring pour le MVP.
    |
    */

    'debug' => [
        'log_level' => env('APP_DEBUG') ? 'debug' : 'error',
        'track_errors' => true,
        'show_debug_bar' => env('APP_DEBUG', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance
    |--------------------------------------------------------------------------
    |
    | Messages et paramètres pour les fonctionnalités désactivées.
    |
    */

    'messages' => [
        'feature_disabled' => 'Cette fonctionnalité sera bientôt disponible.',
        'coming_soon' => 'Prochainement disponible dans une future mise à jour.',
        'beta_notice' => 'Cette application est en version Beta. Merci de votre patience.',
    ],

];
