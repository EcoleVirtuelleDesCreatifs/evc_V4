<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n=== LISTE DES ÉTUDIANTS ===\n\n";

$students = DB::table('students')
    ->select('id', 'first_name', 'last_name', 'email', 'program', 'user_id')
    ->orderBy('id', 'asc')
    ->limit(10)
    ->get();

if ($students->isEmpty()) {
    echo "❌ Aucun étudiant trouvé dans la table students\n";
} else {
    echo "Total étudiants : " . DB::table('students')->count() . "\n\n";
    echo "Premiers 10 étudiants :\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    foreach ($students as $student) {
        echo "ID: {$student->id} | {$student->first_name} {$student->last_name}\n";
        echo "   Email: {$student->email}\n";
        echo "   Formation: " . ($student->program ?? 'N/A') . "\n";
        echo "   User ID: " . ($student->user_id ?? 'N/A') . "\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    }
}

echo "\n";
