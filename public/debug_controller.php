<?php
// Script de debug détaillé - Simulation du contrôleur showAllTP

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DesignProject;

echo "<h1>Debug Détaillé - Contrôleur showAllTP</h1>";
echo "<style>
    body { font-family: Arial; padding: 20px; background: #f5f5f5; }
    .step { background: white; padding: 20px; margin: 10px 0; border-left: 4px solid #4CAF50; }
    .error { border-left-color: #f44336; background: #ffebee; }
    .warning { border-left-color: #ff9800; background: #fff3e0; }
    .success { border-left-color: #4CAF50; background: #e8f5e9; }
    pre { background: #263238; color: #aed581; padding: 15px; border-radius: 5px; overflow-x: auto; }
    table { border-collapse: collapse; width: 100%; margin: 20px 0; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #4CAF50; color: white; }
</style>";

try {
    // Démarrer la session Laravel
    if (!session_status() === PHP_SESSION_ACTIVE) {
        session_start();
    }

    echo "<div class='step'>";
    echo "<h2>ÉTAPE 1 : Récupérer l'utilisateur connecté</h2>";
    
    // Simuler Auth::user() comme dans le contrôleur
    if (Auth::check()) {
        $user = Auth::user();
        echo "<div class='success'>";
        echo "<p>✅ Utilisateur connecté trouvé</p>";
        echo "<pre>";
        echo "ID: " . $user->id . "\n";
        echo "Nom: " . $user->name . "\n";
        echo "Email: " . $user->email . "\n";
        echo "</pre>";
        echo "</div>";
    } else {
        echo "<div class='error'>";
        echo "<p>❌ ERREUR : Aucun utilisateur connecté via Auth::check()</p>";
        echo "</div>";
        $user = null;
    }
    echo "</div>";

    if (!$user) {
        echo "<div class='step error'>";
        echo "<h2>⛔ ARRÊT : Impossible de continuer sans utilisateur</h2>";
        echo "</div>";
        exit;
    }

    echo "<div class='step'>";
    echo "<h2>ÉTAPE 2 : Requête SQL - Récupération des projets</h2>";
    
    // Construire la requête exactement comme dans le contrôleur
    echo "<p>Construction de la requête SQL...</p>";
    echo "<pre>SELECT * FROM design_projects WHERE user_id = {$user->id} ORDER BY created_at DESC</pre>";
    
    $query = DesignProject::where('user_id', $user->id)
        ->with(['files', 'previewFiles'])
        ->orderBy('created_at', 'desc');
    
    // Afficher la requête SQL exacte
    echo "<p><strong>Requête SQL générée :</strong></p>";
    echo "<pre>" . $query->toSql() . "</pre>";
    echo "<p><strong>Bindings :</strong></p>";
    echo "<pre>" . json_encode($query->getBindings(), JSON_PRETTY_PRINT) . "</pre>";
    
    $projects = $query->get();
    
    echo "<div class='success'>";
    echo "<p>✅ Requête exécutée avec succès</p>";
    echo "<p><strong>Nombre de projets trouvés : " . $projects->count() . "</strong></p>";
    echo "</div>";
    echo "</div>";

    echo "<div class='step'>";
    echo "<h2>ÉTAPE 3 : Détails des projets récupérés</h2>";
    
    if ($projects->count() > 0) {
        echo "<table>";
        echo "<tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Catégorie</th>
            <th>Type</th>
            <th>Statut</th>
            <th>Fichiers</th>
            <th>Preview Files</th>
            <th>Créé le</th>
        </tr>";
        
        foreach ($projects as $project) {
            echo "<tr>";
            echo "<td>{$project->id}</td>";
            echo "<td>{$project->title}</td>";
            echo "<td>{$project->category}</td>";
            echo "<td>" . ($project->project_type ?? 'N/A') . "</td>";
            echo "<td>{$project->status}</td>";
            echo "<td>" . $project->files->count() . "</td>";
            echo "<td>" . $project->previewFiles->count() . "</td>";
            echo "<td>{$project->created_at}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<div class='success'>";
        echo "<p>✅ Les projets ont été chargés avec leurs relations</p>";
        echo "</div>";
    } else {
        echo "<div class='warning'>";
        echo "<p>⚠️ Aucun projet trouvé pour l'utilisateur ID: {$user->id}</p>";
        echo "</div>";
    }
    echo "</div>";

    echo "<div class='step'>";
    echo "<h2>ÉTAPE 4 : Calcul des statistiques</h2>";
    
    $stats = [
        'total' => $projects->count(),
        'validated' => $projects->where('status', 'validated')->count(),
        'active' => $projects->where('status', 'active')->count(),
        'draft' => $projects->where('status', 'draft')->count(),
    ];
    
    echo "<table>";
    echo "<tr><th>Statistique</th><th>Valeur</th></tr>";
    foreach ($stats as $key => $value) {
        echo "<tr><td>" . ucfirst($key) . "</td><td><strong>$value</strong></td></tr>";
    }
    echo "</table>";
    echo "</div>";

    echo "<div class='step'>";
    echo "<h2>ÉTAPE 5 : Données passées à la vue</h2>";
    
    $viewData = [
        'projects' => $projects,
        'stats' => $stats,
        'userProfile' => $user
    ];
    
    echo "<pre>";
    echo "projects: Collection avec " . $projects->count() . " élément(s)\n";
    echo "stats: " . json_encode($stats, JSON_PRETTY_PRINT) . "\n";
    echo "userProfile: User ID " . $user->id . " - " . $user->name . "\n";
    echo "</pre>";
    
    echo "<div class='success'>";
    echo "<p>✅ Données prêtes pour la vue tp.all</p>";
    echo "</div>";
    echo "</div>";

    echo "<div class='step'>";
    echo "<h2>ÉTAPE 6 : Vérification de la vue</h2>";
    
    $viewPath = resource_path('views/tp/all.blade.php');
    if (file_exists($viewPath)) {
        echo "<div class='success'>";
        echo "<p>✅ Fichier de vue trouvé : $viewPath</p>";
        echo "<p>Taille : " . filesize($viewPath) . " bytes</p>";
        echo "</div>";
    } else {
        echo "<div class='error'>";
        echo "<p>❌ ERREUR : Fichier de vue introuvable : $viewPath</p>";
        echo "</div>";
    }
    echo "</div>";

    echo "<div class='step'>";
    echo "<h2>ÉTAPE 7 : Test de la condition d'affichage de la vue</h2>";
    
    echo "<p>La vue vérifie : <code>@if(isset(\$projects) && count(\$projects) > 0)</code></p>";
    
    echo "<ul>";
    echo "<li>isset(\$projects) : " . (isset($projects) ? '✅ TRUE' : '❌ FALSE') . "</li>";
    echo "<li>count(\$projects) : <strong>" . count($projects) . "</strong></li>";
    echo "<li>count(\$projects) > 0 : " . (count($projects) > 0 ? '✅ TRUE' : '❌ FALSE') . "</li>";
    echo "</ul>";
    
    if (isset($projects) && count($projects) > 0) {
        echo "<div class='success'>";
        echo "<p>✅ La condition est VRAIE : Les projets DEVRAIENT s'afficher</p>";
        echo "</div>";
    } else {
        echo "<div class='error'>";
        echo "<p>❌ La condition est FAUSSE : Le message 'Aucun projet' sera affiché</p>";
        echo "</div>";
    }
    echo "</div>";

    echo "<div class='step'>";
    echo "<h2>🔍 DIAGNOSTIC FINAL</h2>";
    
    if ($projects->count() > 0) {
        echo "<div class='success'>";
        echo "<h3>✅ Le contrôleur fonctionne correctement</h3>";
        echo "<p>Les projets sont bien récupérés ({$projects->count()} projet(s))</p>";
        echo "<p><strong>Le problème doit être ailleurs :</strong></p>";
        echo "<ul>";
        echo "<li>Vérifiez les logs Laravel : <code>storage/logs/laravel.log</code></li>";
        echo "<li>Vérifiez la console du navigateur (F12) pour des erreurs JavaScript</li>";
        echo "<li>Le cache de vues pourrait être corrompu</li>";
        echo "</ul>";
        
        echo "<h3>🔧 Solutions à essayer :</h3>";
        echo "<ol>";
        echo "<li>Vider le cache : <code>php artisan cache:clear</code></li>";
        echo "<li>Vider le cache de vues : <code>php artisan view:clear</code></li>";
        echo "<li>Vider le cache de config : <code>php artisan config:clear</code></li>";
        echo "<li>Recompiler les vues : <code>php artisan view:cache</code></li>";
        echo "</ol>";
        echo "</div>";
    } else {
        echo "<div class='error'>";
        echo "<h3>❌ Aucun projet trouvé dans la base pour l'utilisateur</h3>";
        echo "<p>User ID: {$user->id}</p>";
        echo "<p>Vérifiez que les projets ont bien le bon user_id dans la base</p>";
        echo "</div>";
    }
    echo "</div>";

} catch (Exception $e) {
    echo "<div class='step error'>";
    echo "<h2>❌ ERREUR FATALE</h2>";
    echo "<p><strong>Message :</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Fichier :</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Ligne :</strong> " . $e->getLine() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}

echo "<hr>";
echo "<p><a href='/evc/compte/design-graphique/tp/tous'>→ Aller à la page TP Tous</a></p>";
echo "<p><a href='/debug_tp_tous.php'>→ Voir le diagnostic de la base de données</a></p>";
