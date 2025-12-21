<?php

return [
    /*
    |--------------------------------------------------------------------------
    | CinetPay Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration pour l'intégration de paiement CinetPay
    |
    */

    'api_key' => env('CINETPAY_API_KEY', '10668199396890d4fd224ef9.31505780'),

    'sites' => [
        'design_graphique' => [
            'site_id' => env('CINETPAY_DESIGN_SITE_ID', '105904453'),
            'secret_key' => env('CINETPAY_DESIGN_SECRET', '4669483526890d54be91ca0.12056423'),
        ],
        'community_management' => [
            'site_id' => env('CINETPAY_CM_SITE_ID', '105904453'),
            'secret_key' => env('CINETPAY_CM_SECRET', '4669483526890d54be91ca0.12056423'),
        ],
        'gestion_informatique' => [
            'site_id' => env('CINETPAY_INFO_SITE_ID', '105904453'),
            'secret_key' => env('CINETPAY_INFO_SECRET', '4669483526890d54be91ca0.12056423'),
        ],
        'intelligence_artificielle' => [
            'site_id' => env('CINETPAY_IA_SITE_ID', '105904453'),
            'secret_key' => env('CINETPAY_IA_SECRET', '4669483526890d54be91ca0.12056423'),
        ],
    ],

    // URLs
    'api_url' => env('CINETPAY_API_URL', 'https://api-checkout.cinetpay.com/v2/payment'),
    'check_url' => env('CINETPAY_CHECK_URL', 'https://api-checkout.cinetpay.com/v2/payment/check'),

    // URLs de retour
    'return_url' => env('CINETPAY_RETURN_URL', 'http://127.0.0.1:8000/evc/payment/return'),
    'notify_url' => env('CINETPAY_NOTIFY_URL', 'http://127.0.0.1:8000/evc/payment/webhook'),
    'cancel_url' => env('CINETPAY_CANCEL_URL', 'http://127.0.0.1:8000/evc/payment/cancel'),

    // Tarifs par formation (en XOF)
    'prices' => [
        'Design Graphique' => 80000,
        'Community Management' => 107000,
        'Design Graphique & Community Management' => 165000,
        'Gestion Informatique' => 152000,
        'Intelligence Artificielle' => 57000,
    ],

    // Liens de paiement externes (Chariow)
    'external_payment_links' => [
        'Design Graphique & Community Management' => 'https://ecolevirtuelle.mychariow.shop/prd_091376/checkout',
    ],

    // Modes de paiement (valeurs acceptées : ALL, MOBILE_MONEY, WALLET, CREDIT_CARD, INTERNATIONAL_CARD)
    'channels' => ['MOBILE_MONEY', 'CREDIT_CARD', 'WALLET'],

    // Configuration
    'currency' => 'XOF',
    'lang' => 'fr',
    'mode' => env('CINETPAY_MODE', 'TEST'), // PRODUCTION ou TEST - Mettre TEST pour déboguer
];
