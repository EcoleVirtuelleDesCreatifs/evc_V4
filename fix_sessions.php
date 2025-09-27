<?php
/**
 * Script de correction rapide pour créer la table sessions
 * À exécuter via navigateur : http://localhost/web/evc2024/V4_EVC/fix_sessions.php
 */

echo "<h2>🔧 Correction du problème de table sessions</h2>";

try {
    // Connexion à la base de données avec différentes configurations possibles
    $configs = [
        ['host' => 'localhost', 'port' => '3306'],
        ['host' => '127.0.0.1', 'port' => '3306'],
        ['host' => 'localhost', 'port' => '8889'], // MAMP
    ];
    
    $pdo = null;
    $connectedConfig = null;
    
    foreach ($configs as $config) {
        try {
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname=V4_EVC;charset=utf8mb4";
            $pdo = new PDO($dsn, 'root', '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 5
            ]);
            $connectedConfig = $config;
            echo "<p>✅ Connexion réussie avec {$config['host']}:{$config['port']}</p>";
            break;
        } catch (PDOException $e) {
            echo "<p>❌ Échec connexion {$config['host']}:{$config['port']}: " . $e->getMessage() . "</p>";
            continue;
        }
    }
    
    if (!$pdo) {
        throw new Exception("Impossible de se connecter à MySQL. Vérifiez que XAMPP/MAMP est démarré.");
    }

    // Vérifier si la base V4_EVC existe
    $stmt = $pdo->query("SHOW DATABASES LIKE 'V4_EVC'");
    if (!$stmt->fetch()) {
        echo "<p>❌ La base de données V4_EVC n'existe pas. Création...</p>";
        $pdo->exec("CREATE DATABASE IF NOT EXISTS V4_EVC CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE V4_EVC");
        echo "<p>✅ Base de données V4_EVC créée!</p>";
    } else {
        $pdo->exec("USE V4_EVC");
        echo "<p>✅ Base de données V4_EVC trouvée!</p>";
    }

    // Vérifier si la table sessions existe déjà
    $stmt = $pdo->query("SHOW TABLES LIKE 'sessions'");
    if ($stmt->fetch()) {
        echo "<p>ℹ️ La table 'sessions' existe déjà. Suppression pour recréation...</p>";
        $pdo->exec("DROP TABLE sessions");
    }

    // Création de la table sessions
    $sessionsSql = "
    CREATE TABLE `sessions` (
        `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `user_id` bigint unsigned DEFAULT NULL,
        `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `user_agent` text COLLATE utf8mb4_unicode_ci,
        `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
        `last_activity` int NOT NULL,
        PRIMARY KEY (`id`),
        KEY `sessions_user_id_index` (`user_id`),
        KEY `sessions_last_activity_index` (`last_activity`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    $pdo->exec($sessionsSql);
    echo "<p>✅ Table 'sessions' créée avec succès!</p>";

    // Créer aussi les autres tables Laravel utiles
    $tables = [
        'cache' => "
        CREATE TABLE IF NOT EXISTS `cache` (
            `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
            `expiration` int NOT NULL,
            PRIMARY KEY (`key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ",
        'cache_locks' => "
        CREATE TABLE IF NOT EXISTS `cache_locks` (
            `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `expiration` int NOT NULL,
            PRIMARY KEY (`key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        "
    ];

    foreach ($tables as $tableName => $sql) {
        $pdo->exec($sql);
        echo "<p>✅ Table '$tableName' créée!</p>";
    }

    // Vérification finale
    $stmt = $pdo->query("SHOW TABLES LIKE 'sessions'");
    if ($stmt->fetch()) {
        echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
        echo "<h3>🎉 SUCCÈS COMPLET!</h3>";
        echo "<p><strong>La table 'sessions' a été créée avec succès dans la base V4_EVC.</strong></p>";
        echo "<p>Configuration utilisée: {$connectedConfig['host']}:{$connectedConfig['port']}</p>";
        echo "<p>✨ L'erreur SQLSTATE[42S02] est maintenant résolue!</p>";
        echo "<p>🚀 Vous pouvez maintenant utiliser votre système d'authentification sans problème.</p>";
        echo "</div>";
        
        echo "<h3>📋 Prochaines étapes:</h3>";
        echo "<ol>";
        echo "<li>Supprimez ce fichier fix_sessions.php (sécurité)</li>";
        echo "<li>Testez la connexion sur votre site</li>";
        echo "<li>Créez un compte via /register</li>";
        echo "<li>Connectez-vous via /login</li>";
        echo "</ol>";
        
    } else {
        echo "<p>❌ Erreur: La table sessions n'a pas pu être créée.</p>";
    }

} catch (PDOException $e) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
    echo "<h3>❌ Erreur de base de données</h3>";
    echo "<p><strong>Message:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Solutions:</strong></p>";
    echo "<ul>";
    echo "<li>Vérifiez que XAMPP/MAMP est démarré</li>";
    echo "<li>Vérifiez que MySQL fonctionne (voyant vert)</li>";
    echo "<li>Vérifiez que la base V4_EVC existe dans phpMyAdmin</li>";
    echo "</ul>";
    echo "</div>";
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
    echo "<h3>❌ Erreur générale</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}

echo "<hr>";
echo "<p><small>Script créé pour résoudre l'erreur Laravel sessions. Une fois exécuté avec succès, supprimez ce fichier.</small></p>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 40px; background: #f8f9fa; }
h2 { color: #333; }
p { margin: 10px 0; }
</style>
