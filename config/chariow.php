<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Liens de Paiement Chariow
    |--------------------------------------------------------------------------
    |
    | Configurez les liens de paiement Chariow pour chaque formation et tranche
    |
    */

    'payment_links' => [

        // Design Graphique
        'Design Graphique' => [
            'tranche_1' => 'https://ecolevirtuelle.mychariow.shop/prd_ngqtqy/checkout',
            'tranche_2' => 'https://ecolevirtuelle.mychariow.shop/prd_ngqtqy/checkout',
        ],

        // Community Management
        'Community Management' => [
            'tranche_1' => 'https://ecolevirtuelle.mychariow.shop/prd_fgcdnb/checkout',
            'tranche_2' => 'https://ecolevirtuelle.mychariow.shop/prd_fgcdnb/checkout',
        ],

        // Design Graphique & Community Management
        'Design Graphique & Community Management' => [
            'tranche_1' => 'https://ecolevirtuelle.mychariow.shop/prd_091376/checkout',
            'tranche_2' => 'https://ecolevirtuelle.mychariow.shop/prd_091376/checkout',
        ],

        // Design Graphique & Community Manager (legacy label)
        'Design Graphique & Community Manager' => [
            'tranche_1' => 'https://ecolevirtuelle.mychariow.shop/prd_091376/checkout',
            'tranche_2' => 'https://ecolevirtuelle.mychariow.shop/prd_091376/checkout',
        ],

        // Gestion Informatique
        'Gestion Informatique' => [
            'tranche_1' => 'https://ecolevirtuelle.mychariow.shop/prd_ngqtqy/checkout',
            'tranche_2' => 'https://ecolevirtuelle.mychariow.shop/prd_ngqtqy/checkout',
        ],

        // Intelligence Artificielle
        'Intelligence Artificielle' => [
            'tranche_1' => 'https://ecolevirtuelle.mychariow.shop/prd_ngqtqy/checkout',
            'tranche_2' => 'https://ecolevirtuelle.mychariow.shop/prd_ngqtqy/checkout',
        ],

        // design_graphique (alias)
        'design_graphique' => [
            'tranche_1' => 'https://ecolevirtuelle.mychariow.shop/prd_ngqtqy/checkout',
            'tranche_2' => 'https://ecolevirtuelle.mychariow.shop/prd_ngqtqy/checkout',
        ],

        // community_management (alias)
        'community_management' => [
            'tranche_1' => 'https://ecolevirtuelle.mychariow.shop/prd_fgcdnb/checkout',
            'tranche_2' => 'https://ecolevirtuelle.mychariow.shop/prd_fgcdnb/checkout',
        ],

        // design_cm (alias)
        'design_cm' => [
            'tranche_1' => 'https://ecolevirtuelle.mychariow.shop/prd_ngqtqy/checkout',
            'tranche_2' => 'https://ecolevirtuelle.mychariow.shop/prd_ngqtqy/checkout',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | URLs de Retour
    |--------------------------------------------------------------------------
    */

    'return_url' => env('CHARIOW_RETURN_URL', env('APP_URL') . '/evc/payment/chariow/return'),
    'cancel_url' => env('CHARIOW_CANCEL_URL', env('APP_URL') . '/evc/payment/chariow/cancel'),
    'webhook_url' => env('CHARIOW_WEBHOOK_URL', env('APP_URL') . '/evc/payment/chariow/webhook'),

    /*
    |--------------------------------------------------------------------------
    | Mode de Paiement
    |--------------------------------------------------------------------------
    |
    | direct_link: Redirection directe vers Chariow (recommandé pour liens simples)
    | api: Utilisation de l'API Chariow (si disponible)
    |
    */

    'mode' => env('CHARIOW_MODE', 'direct_link'),

    /*
    |--------------------------------------------------------------------------
    | Paramètres de Paiement
    |--------------------------------------------------------------------------
    */

    'amounts' => [
        'tranche_1' => 50000, // 50 000 FCFA
        'tranche_2' => 27000, // 27 000 FCFA
    ],

    'formation_amounts' => [
        'Design Graphique' => [
            'total' => 77000,
            'tranche_1' => 50000,
            'tranche_2' => 27000,
        ],
        'Community Management' => [
            'total' => 107000,
            'tranche_1' => 53500,
            'tranche_2' => 53500,
        ],
        'Design Graphique & Community Management' => [
            'total' => 165000,
            'tranche_1' => 100000,
            'tranche_2' => 65000,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Activer Chariow
    |--------------------------------------------------------------------------
    |
    | Définit si Chariow est utilisé au lieu de CinetPay
    |
    */

    'enabled' => env('CHARIOW_ENABLED', true),

];
