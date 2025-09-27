<?php
/**
 * Script pour créer les tables des projets de design graphique
 * À exécuter une seule fois pour initialiser la base de données
 */

try {
    // Configuration de la base de données
    $host = '127.0.0.1';
    $port = '3306';
    $dbname = 'v4_evc';
    $username = 'root';
    $password = '';

    // Connexion à la base de données avec port explicite
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    echo "✅ Connexion à la base de données réussie\n";

    // Lire le fichier SQL
    $sqlFile = __DIR__ . '/create_design_projects_simple.sql';
    
    if (!file_exists($sqlFile)) {
        throw new Exception("Le fichier SQL n'existe pas : $sqlFile");
    }

    $sql = file_get_contents($sqlFile);
    
    if ($sql === false) {
        throw new Exception("Impossible de lire le fichier SQL");
    }

    echo "📖 Lecture du fichier SQL réussie\n";

    // Diviser le contenu en requêtes individuelles
    $queries = array_filter(
        array_map('trim', explode(';', $sql)),
        function($query) {
            return !empty($query) && 
                   !preg_match('/^--/', $query) && 
                   !preg_match('/^\/\*/', $query) &&
                   !preg_match('/^DELIMITER/', $query);
        }
    );

    echo "🔍 " . count($queries) . " requêtes SQL trouvées\n";

    // Exécuter chaque requête
    $successCount = 0;
    $errorCount = 0;

    foreach ($queries as $index => $query) {
        try {
            // Nettoyer la requête
            $cleanQuery = trim($query);
            
            if (empty($cleanQuery)) {
                continue;
            }

            // Exécuter la requête
            $pdo->exec($cleanQuery);
            $successCount++;
            
            // Identifier le type de requête pour l'affichage
            if (preg_match('/^CREATE TABLE.*`(\w+)`/i', $cleanQuery, $matches)) {
                echo "✅ Table créée : {$matches[1]}\n";
            } elseif (preg_match('/^CREATE.*VIEW.*`(\w+)`/i', $cleanQuery, $matches)) {
                echo "✅ Vue créée : {$matches[1]}\n";
            } elseif (preg_match('/^CREATE.*FUNCTION.*(\w+)/i', $cleanQuery, $matches)) {
                echo "✅ Fonction créée : {$matches[1]}\n";
            } elseif (preg_match('/^CREATE.*INDEX.*(\w+)/i', $cleanQuery, $matches)) {
                echo "✅ Index créé : {$matches[1]}\n";
            } elseif (preg_match('/^INSERT/i', $cleanQuery)) {
                echo "✅ Données d'exemple insérées\n";
            } else {
                echo "✅ Requête exécutée avec succès\n";
            }

        } catch (PDOException $e) {
            $errorCount++;
            echo "❌ Erreur dans la requête " . ($index + 1) . " : " . $e->getMessage() . "\n";
            
            // Afficher un extrait de la requête en cas d'erreur
            $queryPreview = substr($cleanQuery, 0, 100) . (strlen($cleanQuery) > 100 ? '...' : '');
            echo "   Requête : $queryPreview\n";
        }
    }

    // Résumé final
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "📊 RÉSUMÉ DE L'INSTALLATION\n";
    echo str_repeat("=", 50) . "\n";
    echo "✅ Requêtes réussies : $successCount\n";
    echo "❌ Requêtes échouées : $errorCount\n";
    
    if ($errorCount === 0) {
        echo "🎉 Installation terminée avec succès !\n";
        echo "\n📋 Tables créées :\n";
        echo "   - design_projects (table principale)\n";
        echo "   - design_project_files (fichiers)\n";
        echo "   - design_project_collaborators (collaborateurs)\n";
        echo "   - design_project_comments (commentaires)\n";
        echo "   - design_project_milestones (jalons)\n";
        echo "\n📊 Vues créées :\n";
        echo "   - v_design_projects_stats (statistiques)\n";
        echo "\n🔧 Fonctions créées :\n";
        echo "   - GetUserDesignProjectStats (statistiques utilisateur)\n";
    } else {
        echo "⚠️  Installation terminée avec des erreurs\n";
        echo "   Vérifiez les erreurs ci-dessus et corrigez si nécessaire\n";
    }

    echo "\n🚀 Vous pouvez maintenant utiliser le formulaire de création de projets !\n";

} catch (Exception $e) {
    echo "💥 ERREUR CRITIQUE : " . $e->getMessage() . "\n";
    echo "❌ L'installation a échoué\n";
    exit(1);
}
?>
