<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n=== STRUCTURE TABLE USERS ===\n\n";

$columns = DB::select("DESCRIBE users");

echo "Colonnes de la table 'users' :\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
foreach ($columns as $column) {
    echo "• {$column->Field} ({$column->Type})\n";
}

echo "\n=== EXEMPLES D'UTILISATEURS ===\n\n";

$users = DB::table('users')->limit(5)->get();

foreach ($users as $user) {
    echo "\nUser ID {$user->id} :\n";
    foreach ((array)$user as $key => $value) {
        if ($value !== null && $key !== 'password') {
            $displayValue = is_string($value) && strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value;
            echo "  {$key}: {$displayValue}\n";
        }
    }
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
}

echo "\n=== VÉRIFICATION TABLE ADMINS ===\n\n";

$admins = DB::select("SHOW TABLES LIKE 'admins'");

if (count($admins) > 0) {
    echo "✅ Table 'admins' existe\n";
    $adminColumns = DB::select("DESCRIBE admins");
    echo "\nColonnes de la table 'admins' :\n";
    foreach ($adminColumns as $column) {
        echo "• {$column->Field} ({$column->Type})\n";
    }

    echo "\nAdmins dans la table :\n";
    $adminsList = DB::table('admins')->get();
    foreach ($adminsList as $admin) {
        echo "• ID {$admin->id} : {$admin->name} ({$admin->email})\n";
    }
} else {
    echo "❌ Table 'admins' n'existe pas\n";
}
