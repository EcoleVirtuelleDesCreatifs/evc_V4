<?php
/**
 * Script de création de la base de données V4_EVC
 * À exécuter via navigateur : http://localhost/web/evc2024/V4_EVC/database/create_database.php
 */

// Configuration de la base de données
$host = 'localhost';
$username = 'root';
$password = '';

// Connexion à MySQL
try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>🚀 Création de la base de données V4_EVC</h1>";
    echo "<div style='font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px;'>";
    
    // Lire le fichier SQL
    $sqlFile = __DIR__ . '/V4_EVC_database.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("Fichier SQL non trouvé : $sqlFile");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Diviser le SQL en requêtes individuelles
    $queries = array_filter(array_map('trim', explode(';', $sql)));
    
    $successCount = 0;
    $errorCount = 0;
    
    echo "<h2>📋 Exécution des requêtes SQL...</h2>";
    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
    
    foreach ($queries as $index => $query) {
        if (empty($query) || strpos($query, '--') === 0) {
            continue;
        }
        
        try {
            $pdo->exec($query);
            $successCount++;
            
            // Afficher les requêtes importantes
            if (stripos($query, 'CREATE DATABASE') !== false) {
                echo "✅ <strong>Base de données V4_EVC créée</strong><br>";
            } elseif (stripos($query, 'CREATE TABLE') !== false) {
                preg_match('/CREATE TABLE\s+(\w+)/i', $query, $matches);
                $tableName = $matches[1] ?? 'table';
                echo "✅ Table <code>$tableName</code> créée<br>";
            } elseif (stripos($query, 'INSERT INTO') !== false) {
                preg_match('/INSERT INTO\s+(\w+)/i', $query, $matches);
                $tableName = $matches[1] ?? 'table';
                echo "✅ Données insérées dans <code>$tableName</code><br>";
            } elseif (stripos($query, 'CREATE VIEW') !== false) {
                preg_match('/CREATE VIEW\s+(\w+)/i', $query, $matches);
                $viewName = $matches[1] ?? 'view';
                echo "✅ Vue <code>$viewName</code> créée<br>";
            } elseif (stripos($query, 'CREATE PROCEDURE') !== false) {
                preg_match('/CREATE PROCEDURE\s+(\w+)/i', $query, $matches);
                $procName = $matches[1] ?? 'procedure';
                echo "✅ Procédure <code>$procName</code> créée<br>";
            } elseif (stripos($query, 'CREATE TRIGGER') !== false) {
                preg_match('/CREATE TRIGGER\s+(\w+)/i', $query, $matches);
                $triggerName = $matches[1] ?? 'trigger';
                echo "✅ Trigger <code>$triggerName</code> créé<br>";
            }
            
        } catch (PDOException $e) {
            $errorCount++;
            echo "❌ <span style='color: red;'>Erreur : " . htmlspecialchars($e->getMessage()) . "</span><br>";
        }
    }
    
    echo "</div>";
    
    // Récapitulatif
    echo "<h2>📊 Récapitulatif</h2>";
    echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
    echo "<strong>✅ Requêtes réussies :</strong> $successCount<br>";
    if ($errorCount > 0) {
        echo "<strong>❌ Erreurs :</strong> $errorCount<br>";
    }
    echo "</div>";
    
    // Vérification des tables créées
    echo "<h2>🗃️ Tables créées dans V4_EVC</h2>";
    
    try {
        $pdo->exec("USE V4_EVC");
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
        echo "<strong>📋 " . count($tables) . " tables créées :</strong><br><br>";
        
        foreach ($tables as $table) {
            // Compter les lignes dans chaque table
            try {
                $countStmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
                $count = $countStmt->fetchColumn();
                echo "🗂️ <code>$table</code> ($count lignes)<br>";
            } catch (Exception $e) {
                echo "🗂️ <code>$table</code><br>";
            }
        }
        echo "</div>";
        
    } catch (Exception $e) {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
        echo "❌ Erreur lors de la vérification des tables : " . htmlspecialchars($e->getMessage());
        echo "</div>";
    }
    
    // Test de connexion avec l'utilisateur de test
    echo "<h2>👤 Test de l'utilisateur de démonstration</h2>";
    try {
        $stmt = $pdo->query("SELECT id, first_name, last_name, email, status FROM users LIMIT 1");
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo "<div style='background: #d1ecf1; border: 1px solid #bee5eb; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
            echo "<strong>✅ Utilisateur de test créé :</strong><br>";
            echo "🆔 ID: {$user['id']}<br>";
            echo "👤 Nom: {$user['first_name']} {$user['last_name']}<br>";
            echo "📧 Email: {$user['email']}<br>";
            echo "📊 Statut: {$user['status']}<br>";
            echo "</div>";
        }
    } catch (Exception $e) {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
        echo "❌ Erreur lors du test utilisateur : " . htmlspecialchars($e->getMessage());
        echo "</div>";
    }
    
    echo "<h2>🎉 Base de données V4_EVC créée avec succès !</h2>";
    echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center;'>";
    echo "<strong>🚀 La base de données est maintenant prête pour l'application Laravel EVC !</strong><br><br>";
    echo "📝 <strong>Prochaines étapes :</strong><br>";
    echo "1. Configurer le fichier .env avec les paramètres de base de données<br>";
    echo "2. Tester la connexion depuis l'application Laravel<br>";
    echo "3. Implémenter les modèles Eloquent correspondants<br>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<h1>❌ Erreur de création de la base de données</h1>";
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
    echo "<strong>Erreur :</strong> " . htmlspecialchars($e->getMessage());
    echo "</div>";
}

echo "</div>";
?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    margin: 0;
    padding: 20px;
    color: #333;
}

h1, h2 {
    color: #2c3e50;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}

code {
    background: #f1f3f4;
    padding: 2px 6px;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 0.9em;
}

div {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>
