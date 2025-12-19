<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n=== STRUCTURE TABLE PAYMENTS ===\n\n";

$columns = DB::select("DESCRIBE payments");

echo "Colonnes de la table 'payments' :\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
foreach ($columns as $column) {
    echo "• {$column->Field} ({$column->Type})\n";
}

echo "\n=== EXEMPLE DE PAIEMENT ===\n\n";

$payment = DB::table('payments')->first();

if ($payment) {
    echo "Paiement ID {$payment->id} :\n";
    foreach ((array)$payment as $key => $value) {
        $displayValue = $value !== null ? (is_string($value) && strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value) : 'NULL';
        echo "  {$key}: {$displayValue}\n";
    }
} else {
    echo "❌ Aucun paiement dans la table\n";
}
