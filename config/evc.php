<?php

return [
    'programme_map' => [
        // Anciennes valeurs (compatibilité)
        'Infographie' => 'infographie',
        'Community Management' => 'community_management',
        'Informatique' => 'informatique',
        'Infographie + Community Management' => 'infographie_cm',
        'infographie' => 'infographie',
        'community_management' => 'community_management',
        'informatique' => 'informatique',
        'infographie_cm' => 'infographie_cm',
        // Nouvelles valeurs slug depuis le select
        'design-graphique' => 'infographie',
        'community-manager' => 'community_management',
        'intelligence-artificielle' => 'intelligence_artificielle',
        'gestion-informatique' => 'informatique',
    ],

    'choix_formation_map' => [
        'infographie' => 'design_graphique',
        'community_management' => 'community_management',
        'intelligence_artificielle' => 'intelligence_artificielle',
        'informatique' => 'gestion_informatique',
        'infographie_cm' => 'design_graphique', // fallback
    ],

    'niveau_formation_map' => [
        'Aucune notion' => 'aucune_notion',
        'Certaines notions' => 'quelques_notions',
        'Monter en compétence' => 'me_perfectionner',
        'aucune_notion' => 'aucune_notion',
        'quelques_notions' => 'quelques_notions',
        'me_perfectionner' => 'me_perfectionner',
    ],

    'origine_map' => [
        'Réseaux sociaux' => 'reseaux',
        'Ami' => 'ami',
        'Publicité' => 'publicite',
        'Autre' => 'autre',
        'reseaux' => 'reseaux',
        'ami' => 'ami',
        'publicite' => 'publicite',
        'autre' => 'autre',
    ],
];
