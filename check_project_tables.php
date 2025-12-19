<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "\n=== VÉRIFICATION TABLES PROJETS ===\n\n";

$studentId = 15;
$student = DB::table('students')->where('id', $studentId)->first();
$user = DB::table('users')->where('id', $student->user_id)->first();

echo "Étudiant : {$student->first_name} {$student->last_name}\n";
echo "User ID : {$user->id}\n";
echo "Formation : {$student->program}\n\n";

// Table projects
if (Schema::hasTable('projects')) {
    $projectsCount = DB::table('projects')->where('user_id', $user->id)->count();
    echo "Table 'projects' : {$projectsCount} projet(s)\n";

    if ($projectsCount > 0) {
        $projects = DB::table('projects')->where('user_id', $user->id)->get();
        foreach ($projects as $p) {
            echo "  • ID {$p->id} : {$p->titre} (Status: {$p->status})\n";
        }
    }
} else {
    echo "❌ Table 'projects' n'existe pas\n";
}

echo "\n";

// Table design_projects
if (Schema::hasTable('design_projects')) {
    $designProjectsCount = DB::table('design_projects')->where('user_id', $user->id)->count();
    echo "Table 'design_projects' : {$designProjectsCount} projet(s)\n";

    if ($designProjectsCount > 0) {
        $projects = DB::table('design_projects')->where('user_id', $user->id)->get();
        foreach ($projects as $p) {
            echo "  • ID {$p->id} : {$p->titre} (Status: {$p->status})\n";
        }
    }
} else {
    echo "❌ Table 'design_projects' n'existe pas\n";
}

echo "\n";
