<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\StudentProjectService;
use App\Models\User;
use App\Models\DesignProject;
use App\Models\DesignProjectFile;

echo "=== TEST AFFICHAGE PROJETS DESIGN (PROJETS RÉEL) ===\n\n";

// Vérifier les données dans la base
echo "=== VÉRIFICATION DES DONNÉES ===\n";
$totalDesignProjects = DesignProject::count();
$totalDesignFiles = DesignProjectFile::count();
echo "📊 Total projets design: {$totalDesignProjects}\n";
echo "📁 Total fichiers design: {$totalDesignFiles}\n\n";

if ($totalDesignProjects == 0) {
    echo "❌ Aucun projet design trouvé dans la base de données\n";
    echo "💡 Créons quelques projets de test...\n\n";
    
    // Créer des projets de test
    $testUser = User::first();
    if (!$testUser) {
        echo "❌ Aucun utilisateur trouvé pour créer des projets de test\n";
        exit;
    }
    
    $testProjects = [
        [
            'title' => 'Logo Entreprise ABC',
            'description' => 'Création d\'un logo moderne pour une startup tech',
            'project_type' => 'logo',
            'project_mode' => 'solo',
            'software_used' => ['Illustrator', 'Photoshop'],
            'status' => 'active',
            'progress_percentage' => 75
        ],
        [
            'title' => 'Site Web Portfolio',
            'description' => 'Design d\'un site portfolio pour un photographe',
            'project_type' => 'web',
            'project_mode' => 'solo',
            'software_used' => ['Figma', 'Adobe XD'],
            'status' => 'validated',
            'progress_percentage' => 100
        ],
        [
            'title' => 'Packaging Produit Bio',
            'description' => 'Conception d\'emballages écologiques',
            'project_type' => 'packaging',
            'project_mode' => 'groupe',
            'software_used' => ['Illustrator', 'InDesign'],
            'status' => 'active',
            'progress_percentage' => 50
        ]
    ];
    
    foreach ($testProjects as $projectData) {
        $projectData['user_id'] = $testUser->id;
        DesignProject::create($projectData);
        echo "✅ Projet créé: {$projectData['title']}\n";
    }
    
    echo "\n📊 Projets de test créés avec succès!\n\n";
    $totalDesignProjects = DesignProject::count();
}

// Récupérer un utilisateur avec des projets design
$userWithProjects = User::whereHas('designProjects')->first();

if (!$userWithProjects) {
    echo "❌ Aucun utilisateur avec des projets design trouvé\n";
    exit;
}

echo "👤 Utilisateur testé: {$userWithProjects->first_name} {$userWithProjects->last_name} (ID: {$userWithProjects->id})\n";
echo "📊 Nombre de projets design: " . $userWithProjects->designProjects()->count() . "\n\n";

// Tester le service de pagination
$service = new StudentProjectService();
$paginatedData = $service->getDesignProjectsByStudent($userWithProjects->id, 1);

echo "=== DONNÉES RETOURNÉES PAR LE SERVICE ===\n";
echo "📄 Total projets: " . $paginatedData['pagination']['total_projects'] . "\n";
echo "📑 Page actuelle: " . $paginatedData['pagination']['current_page'] . "\n";
echo "📋 Projets par page: " . $paginatedData['pagination']['projects_per_page'] . "\n";
echo "📚 Total pages: " . $paginatedData['pagination']['total_pages'] . "\n";
echo "🏷️  Type de projet: " . $paginatedData['project_type_label'] . "\n\n";

echo "=== PROJETS PAR SESSION ===\n";
foreach ($paginatedData['projects_by_session'] as $session => $sessionData) {
    echo "🗓️  {$session}: " . $sessionData['projects_count'] . " projets\n";
    
    foreach ($sessionData['projects'] as $index => $project) {
        echo "   " . ($index + 1) . ". {$project->title}\n";
        echo "      📁 Type: {$project->type_label}\n";
        echo "      👥 Mode: {$project->mode_label}\n";
        echo "      🛠️  Logiciels: {$project->software_list}\n";
        echo "      📅 Date: {$project->formatted_date}\n";
        echo "      🏷️  Status: {$project->status} ({$project->status_label})\n";
        echo "      📊 Progression: {$project->progress_percentage}% ({$project->progress_status})\n";
        echo "      📁 Fichiers: {$project->files_count}\n";
        echo "      🖼️  Aperçu: " . ($project->has_preview ? "✅ Oui ({$project->preview_name})" : "❌ Non") . "\n";
        if ($project->has_preview) {
            echo "      📂 Chemin: {$project->preview_path}\n";
        }
        if (!empty($project->description)) {
            echo "      📝 Description: " . substr($project->description, 0, 50) . "...\n";
        }
        echo "\n";
    }
}

echo "=== STRUCTURE COMPLÈTE DES DONNÉES ===\n";
echo "Clés disponibles dans \$paginatedData:\n";
foreach (array_keys($paginatedData) as $key) {
    echo "- {$key}\n";
}

echo "\n=== ANALYSE DES TYPES DE PROJETS ===\n";
$allDesignProjects = DesignProject::all();
$projectTypes = $allDesignProjects->groupBy('project_type');
foreach ($projectTypes as $type => $projects) {
    $typeLabel = \App\Models\DesignProject::PROJECT_TYPE_LABELS[$type] ?? $type;
    echo "🎨 {$typeLabel}: " . count($projects) . " projets\n";
}

echo "\n=== ANALYSE DES STATUTS ===\n";
$projectStatuses = $allDesignProjects->groupBy('status');
foreach ($projectStatuses as $status => $projects) {
    $statusLabel = \App\Models\DesignProject::STATUS_LABELS[$status] ?? $status;
    echo "📊 {$statusLabel}: " . count($projects) . " projets\n";
}

echo "\n=== TEST TERMINÉ ===\n";
