<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n=== TEST RÉCUPÉRATION ÉTUDIANTS PAR MODULE ===\n\n";

$modules = [
    'design_graphique' => 'Design Graphique',
    'community_management' => 'Community Management',
    'intelligence_artificielle' => 'Intelligence Artificielle',
    'gestion_informatique' => 'Gestion Informatique',
];

foreach ($modules as $moduleKey => $moduleName) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📚 MODULE: {$moduleName}\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

    // Mapper les variantes du module
    $moduleMapping = [
        'design_graphique' => ['Design Graphique', 'design_graphique'],
        'community_management' => ['Community Management', 'community_management'],
        'intelligence_artificielle' => ['Intelligence Artificielle', 'intelligence_artificielle'],
        'gestion_informatique' => ['Gestion Informatique', 'gestion_informatique'],
    ];

    $moduleVariants = $moduleMapping[$moduleKey] ?? [$moduleKey];

    // Récupérer les étudiants
    $students = DB::table('students')
        ->join('users', 'students.user_id', '=', 'users.id')
        ->where(function($query) use ($moduleVariants) {
            foreach ($moduleVariants as $variant) {
                $query->orWhere('students.program', $variant)
                      ->orWhere('students.specialization', $variant);
            }
        })
        ->select(
            'users.id',
            DB::raw("CONCAT(students.first_name, ' ', students.last_name) as name"),
            'users.email',
            'students.program',
            'students.specialization'
        )
        ->orderBy('students.last_name')
        ->orderBy('students.first_name')
        ->get();

    if ($students->count() > 0) {
        echo "✅ {$students->count()} étudiant(s) trouvé(s) :\n\n";
        foreach ($students as $student) {
            echo "  • {$student->name}\n";
            echo "    Email: {$student->email}\n";
            echo "    Formation: {$student->program}\n";
            if ($student->specialization) {
                echo "    Spécialisation: {$student->specialization}\n";
            }
            echo "\n";
        }
    } else {
        echo "⚠️  Aucun étudiant trouvé pour ce module\n\n";
    }
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 RÉSUMÉ GLOBAL\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$totalStudents = DB::table('students')->count();
echo "Total étudiants dans la base : {$totalStudents}\n";

$studentsWithUsers = DB::table('students')
    ->join('users', 'students.user_id', '=', 'users.id')
    ->count();
echo "Étudiants avec compte utilisateur : {$studentsWithUsers}\n";

echo "\n🔗 URL à tester : http://127.0.0.1:8000/evc/app/admin/formations/9/edit\n\n";
