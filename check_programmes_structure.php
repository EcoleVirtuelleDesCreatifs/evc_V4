<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "\n=== STRUCTURE TABLE PROGRAMMES ===\n\n";

// Récupérer la structure de la table
$columns = DB::select("DESCRIBE programmes");

echo "Colonnes de la table 'programmes' :\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
foreach ($columns as $column) {
    echo "• {$column->Field} ({$column->Type})\n";
}

// Afficher un exemple de données
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Exemple de données :\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$programmes = DB::table('programmes')->limit(3)->get();

if ($programmes->count() > 0) {
    foreach ($programmes as $prog) {
        echo "\nProgramme ID {$prog->id} :\n";
        foreach ((array)$prog as $key => $value) {
            if ($value !== null) {
                $displayValue = is_string($value) && strlen($value) > 100 ? substr($value, 0, 100) . '...' : $value;
                echo "  {$key}: {$displayValue}\n";
            }
        }
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    }
} else {
    echo "Aucun programme dans la table\n";
}
