<?php
// Script de diagnostic pour vérifier les TP dans la base de données

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

echo "<h1>Diagnostic - TP Tous</h1>";
echo "<style>body { font-family: Arial; padding: 20px; } table { border-collapse: collapse; width: 100%; margin: 20px 0; } th, td { border: 1px solid #ddd; padding: 8px; text-align: left; } th { background-color: #4CAF50; color: white; } .highlight { background-color: #ffeb3b; }</style>";

try {
    // Démarrer la session pour obtenir l'utilisateur connecté
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Récupérer l'utilisateur connecté
    $userId = session('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d') ?? null;
    
    echo "<h2>Utilisateur connecté</h2>";
    if ($userId) {
        $user = DB::table('users')->where('id', $userId)->first();
        if ($user) {
            echo "<p><strong>ID:</strong> {$user->id}</p>";
            echo "<p><strong>Nom:</strong> {$user->name}</p>";
            echo "<p><strong>Email:</strong> {$user->email}</p>";
        } else {
            echo "<p style='color: red;'>❌ Utilisateur introuvable avec ID: $userId</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Aucun utilisateur connecté !</p>";
    }
    
    // Vérifier la connexion à la base de données
    echo "<h2>✅ Connexion à la base de données : OK</h2>";
    
    // Compter le nombre total de projets
    $totalProjects = DB::table('design_projects')->count();
    echo "<h3>Total de projets dans la base : $totalProjects</h3>";
    
    if ($userId) {
        $userProjects = DB::table('design_projects')->where('user_id', $userId)->count();
        echo "<h3>Projets de l'utilisateur connecté (ID: $userId) : $userProjects</h3>";
    }
    
    if ($totalProjects > 0) {
        // Afficher TOUS les projets
        $projects = DB::table('design_projects')
            ->orderBy('created_at', 'desc')
            ->get();
        
        echo "<h3>Tous les projets dans la base :</h3>";
        echo "<table>";
        echo "<tr><th>ID</th><th>User ID</th><th>Titre</th><th>Catégorie</th><th>Type</th><th>Statut</th><th>Créé le</th></tr>";
        
        foreach ($projects as $project) {
            $isCurrentUser = ($userId && $project->user_id == $userId) ? 'class="highlight"' : '';
            echo "<tr $isCurrentUser>";
            echo "<td>{$project->id}</td>";
            echo "<td>{$project->user_id}</td>";
            echo "<td>{$project->title}</td>";
            echo "<td>{$project->category}</td>";
            echo "<td>" . ($project->project_type ?? 'N/A') . "</td>";
            echo "<td>{$project->status}</td>";
            echo "<td>{$project->created_at}</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<p><em>Note: Les lignes surlignées en jaune sont les projets de l'utilisateur actuellement connecté.</em></p>";
        
        // Statistiques par utilisateur
        $userStats = DB::table('design_projects')
            ->select('user_id', DB::raw('count(*) as count'))
            ->groupBy('user_id')
            ->get();
        
        echo "<h3>Statistiques par utilisateur :</h3>";
        echo "<table>";
        echo "<tr><th>User ID</th><th>Nom utilisateur</th><th>Nombre de projets</th></tr>";
        foreach ($userStats as $stat) {
            $user = DB::table('users')->where('id', $stat->user_id)->first();
            $userName = $user ? $user->name : 'Utilisateur introuvable';
            $isCurrentUser = ($userId && $stat->user_id == $userId) ? 'class="highlight"' : '';
            echo "<tr $isCurrentUser>";
            echo "<td>{$stat->user_id}</td>";
            echo "<td>$userName</td>";
            echo "<td>{$stat->count}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Statistiques par statut
        $stats = DB::table('design_projects')
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
        
        echo "<h3>Statistiques par statut :</h3>";
        echo "<table>";
        echo "<tr><th>Statut</th><th>Nombre</th></tr>";
        foreach ($stats as $stat) {
            echo "<tr><td>{$stat->status}</td><td>{$stat->count}</td></tr>";
        }
        echo "</table>";
        
        // Vérifier les fichiers associés
        $filesCount = DB::table('design_project_files')->count();
        echo "<h3>Total de fichiers : $filesCount</h3>";
        
        if ($filesCount > 0) {
            $filesByProject = DB::table('design_project_files')
                ->select('design_project_id', DB::raw('count(*) as count'))
                ->groupBy('design_project_id')
                ->get();
            
            echo "<h4>Fichiers par projet :</h4>";
            echo "<table>";
            echo "<tr><th>Projet ID</th><th>Nombre de fichiers</th></tr>";
            foreach ($filesByProject as $file) {
                echo "<tr><td>{$file->design_project_id}</td><td>{$file->count}</td></tr>";
            }
            echo "</table>";
        }
        
    } else {
        echo "<p style='color: red; font-weight: bold;'>❌ Aucun projet trouvé dans la base de données !</p>";
        echo "<p>La table 'design_projects' est vide.</p>";
    }
    
    // Solution proposée
    echo "<hr>";
    echo "<h2>🔧 Solution</h2>";
    if ($userId && $totalProjects > 0) {
        $userProjects = DB::table('design_projects')->where('user_id', $userId)->count();
        if ($userProjects == 0) {
            echo "<p style='color: orange; font-weight: bold;'>⚠️ Problème identifié : Les $totalProjects projets dans la base n'appartiennent pas à l'utilisateur connecté (ID: $userId).</p>";
            echo "<p><strong>Options :</strong></p>";
            echo "<ol>";
            echo "<li>Créer de nouveaux projets en tant qu'utilisateur connecté</li>";
            echo "<li>Modifier le user_id des projets existants pour qu'ils appartiennent à l'utilisateur connecté</li>";
            echo "<li>Se connecter avec l'utilisateur qui possède ces projets</li>";
            echo "</ol>";
        } else {
            echo "<p style='color: green;'>✅ L'utilisateur connecté a $userProjects projet(s). Ils devraient s'afficher.</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur : " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<p><a href='/evc/compte/design-graphique/tp/tous'>← Retour à la page TP Tous</a></p>";
