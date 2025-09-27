<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\StudentProjectService;
use App\Models\User;
use App\Models\Project;

echo "=== TEST AFFICHAGE DONNÉES PROJETS PAGINÉS ===\n\n";

// Récupérer un étudiant avec des projets
$student = User::whereHas('projects')->first();

if (!$student) {
    echo "❌ Aucun étudiant avec des projets trouvé\n";
    exit;
}

echo "👤 Étudiant testé: {$student->first_name} {$student->last_name} (ID: {$student->id})\n";
echo "📊 Nombre total de projets: " . $student->projects()->count() . "\n\n";

// Vérifier les projets directement
echo "=== VÉRIFICATION DIRECTE DES PROJETS ===\n";
$allProjects = Project::all();
echo "📊 Total projets dans la DB: " . $allProjects->count() . "\n";

echo "📋 Projets par user_id:\n";
foreach ($allProjects->groupBy('user_id') as $userId => $userProjects) {
    echo "   User {$userId}: " . count($userProjects) . " projets\n";
}

echo "\n📝 Premiers projets dans la DB:\n";
foreach ($allProjects->take(3) as $project) {
    echo "   ID: {$project->id} | User: {$project->user_id} | Titre: {$project->title}\n";
}

echo "\n";

// Tester le service de pagination
$service = new StudentProjectService();
$paginatedData = $service->getProjectImagesByStudent($student->id, 1);

echo "=== STRUCTURE COMPLÈTE DES DONNÉES RETOURNÉES ===\n";
print_r(array_keys($paginatedData));
echo "\n";

echo "=== DONNÉES RETOURNÉES PAR LE SERVICE ===\n";
echo "📄 Total projets: " . $paginatedData['pagination']['total_projects'] . "\n";
echo "📑 Page actuelle: " . $paginatedData['pagination']['current_page'] . "\n";
echo "📋 Projets par page: " . $paginatedData['pagination']['projects_per_page'] . "\n";
echo "📚 Total pages: " . $paginatedData['pagination']['total_pages'] . "\n\n";

echo "=== PROJETS PAR SESSION ===\n";
foreach ($paginatedData['projects_by_session'] as $session => $projects) {
    echo "🗓️  {$session}: " . count($projects) . " projets\n";
    
    foreach ($projects as $index => $project) {
        echo "   " . ($index + 1) . ". {$project->title}\n";
        echo "      📁 Catégorie: {$project->category}\n";
        echo "      🛠️  Logiciels: {$project->software_list}\n";
        echo "      📅 Date: {$project->formatted_date}\n";
        echo "      🏷️  Status: {$project->status} ({$project->status_label})\n";
        echo "      🖼️  Image: " . ($project->has_image ? "✅ Oui ({$project->image_name})" : "❌ Non") . "\n";
        if ($project->has_image) {
            echo "      📂 Chemin: {$project->image_path}\n";
        }
        echo "\n";
    }
}

echo "=== STRUCTURE COMPLÈTE DES DONNÉES ===\n";
echo "Clés disponibles dans \$paginatedData:\n";
foreach (array_keys($paginatedData) as $key) {
    echo "- {$key}\n";
}

echo "\n=== TEST TERMINÉ ===\n";
