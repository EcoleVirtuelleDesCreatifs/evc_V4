<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuration des templates de certificats
    |--------------------------------------------------------------------------
    |
    | Configuration des positions et styles pour chaque type de certificat
    |
    */

    'community_management' => [
        'template_path' => 'assets/certificats/cm_smm/certificat_cm_smm.pdf',
        'positions' => [
            'name' => [
                'y' => 120, // Position verticale du nom (en mm depuis le haut)
                'font' => 'Helvetica',
                'font_style' => 'B', // B = Bold, I = Italic, '' = Regular
                'font_size' => 24,
                'color' => [0, 0, 0], // RGB
            ],
            'formation' => [
                'y' => 145, // Position verticale de la formation
                'font' => 'Helvetica',
                'font_style' => '',
                'font_size' => 16,
                'color' => [0, 0, 0],
            ],
            'date' => [
                'y' => 250, // Position verticale de la date (en mm depuis le haut)
                'font' => 'Helvetica',
                'font_style' => '',
                'font_size' => 12,
                'color' => [0, 0, 0],
            ],
            'student_id' => [
                'y' => 270, // Position verticale du numéro étudiant
                'font' => 'Helvetica',
                'font_style' => '',
                'font_size' => 10,
                'color' => [100, 100, 100],
            ],
        ],
    ],

    'design_graphique' => [
        'template_path' => 'assets/certificats/design/certificat_design.pdf',
        'positions' => [
            'name' => [
                'y' => 120,
                'font' => 'Helvetica',
                'font_style' => 'B',
                'font_size' => 24,
                'color' => [0, 0, 0],
            ],
            'formation' => [
                'y' => 145,
                'font' => 'Helvetica',
                'font_style' => '',
                'font_size' => 16,
                'color' => [0, 0, 0],
            ],
            'date' => [
                'y' => 250,
                'font' => 'Helvetica',
                'font_style' => '',
                'font_size' => 12,
                'color' => [0, 0, 0],
            ],
            'student_id' => [
                'y' => 270,
                'font' => 'Helvetica',
                'font_style' => '',
                'font_size' => 10,
                'color' => [100, 100, 100],
            ],
        ],
    ],

];
