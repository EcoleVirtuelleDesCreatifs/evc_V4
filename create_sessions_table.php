<?php
/**
 * Script pour créer les tables Laravel manquantes dans la base V4_EVC
 * À exécuter une seule fois pour résoudre l'erreur de table sessions manquante
 */

try {
    // Connexion à la base de données
    $pdo = new PDO(
        'mysql:host=localhost;dbname=V4_EVC;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "✅ Connexion à la base de données V4_EVC réussie!\n\n";

    // Création de la table sessions
    $sessionsSql = "
    CREATE TABLE IF NOT EXISTS `sessions` (
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
    echo "✅ Table 'sessions' créée avec succès!\n";

    // Création de la table cache
    $cacheSql = "
    CREATE TABLE IF NOT EXISTS `cache` (
        `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
        `expiration` int NOT NULL,
        PRIMARY KEY (`key`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    $pdo->exec($cacheSql);
    echo "✅ Table 'cache' créée avec succès!\n";

    // Création de la table cache_locks
    $cacheLocksSql = "
    CREATE TABLE IF NOT EXISTS `cache_locks` (
        `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `expiration` int NOT NULL,
        PRIMARY KEY (`key`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    $pdo->exec($cacheLocksSql);
    echo "✅ Table 'cache_locks' créée avec succès!\n";

    // Création de la table jobs
    $jobsSql = "
    CREATE TABLE IF NOT EXISTS `jobs` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
        `attempts` tinyint unsigned NOT NULL,
        `reserved_at` int unsigned DEFAULT NULL,
        `available_at` int unsigned NOT NULL,
        `created_at` int unsigned NOT NULL,
        PRIMARY KEY (`id`),
        KEY `jobs_queue_index` (`queue`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    $pdo->exec($jobsSql);
    echo "✅ Table 'jobs' créée avec succès!\n";

    // Création de la table failed_jobs
    $failedJobsSql = "
    CREATE TABLE IF NOT EXISTS `failed_jobs` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
        `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
        `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
        `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
        `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    $pdo->exec($failedJobsSql);
    echo "✅ Table 'failed_jobs' créée avec succès!\n";

    // Vérification que la table sessions existe maintenant
    $checkSql = "SHOW TABLES LIKE 'sessions'";
    $stmt = $pdo->query($checkSql);
    $result = $stmt->fetch();

    if ($result) {
        echo "\n🎉 SUCCÈS! La table 'sessions' existe maintenant dans la base V4_EVC.\n";
        echo "🚀 Vous pouvez maintenant utiliser les sessions Laravel sans erreur!\n\n";
        
        // Afficher les tables créées
        echo "📋 Tables Laravel créées :\n";
        echo "   - sessions (gestion des sessions utilisateur)\n";
        echo "   - cache (système de cache)\n";
        echo "   - cache_locks (verrous de cache)\n";
        echo "   - jobs (tâches en arrière-plan)\n";
        echo "   - failed_jobs (tâches échouées)\n\n";
        
        echo "✨ L'erreur SQLSTATE[42S02] est maintenant résolue!\n";
    } else {
        echo "❌ Erreur: La table sessions n'a pas pu être créée.\n";
    }

} catch (PDOException $e) {
    echo "❌ Erreur de connexion à la base de données: " . $e->getMessage() . "\n";
    echo "🔧 Vérifiez que XAMPP est démarré et que la base V4_EVC existe.\n";
} catch (Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
}
?>
