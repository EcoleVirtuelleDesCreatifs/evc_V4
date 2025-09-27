<?php
/**
 * Script simple pour créer les tables des projets de design
 */

try {
    $pdo = new PDO(
        'mysql:host=127.0.0.1;port=3306;dbname=v4_evc;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "✅ Connexion réussie\n";

    // Table principale
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `design_projects` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) NOT NULL,
            `title` varchar(255) NOT NULL,
            `description` text DEFAULT NULL,
            `project_type` enum('logo','web','print','packaging','illustration','motion','strategy','autre') NOT NULL,
            `project_mode` enum('solo','groupe') NOT NULL DEFAULT 'solo',
            `software_used` json DEFAULT NULL,
            `reference_url` varchar(500) DEFAULT NULL,
            `status` enum('draft','active','completed','cancelled') NOT NULL DEFAULT 'active',
            `progress_percentage` tinyint(3) DEFAULT 0,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            `completed_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `idx_user_id` (`user_id`),
            KEY `idx_project_type` (`project_type`),
            KEY `idx_project_mode` (`project_mode`),
            KEY `idx_status` (`status`),
            KEY `idx_created_at` (`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ Table design_projects créée\n";

    // Table des fichiers
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `design_project_files` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `design_project_id` int(11) NOT NULL,
            `original_name` varchar(255) NOT NULL,
            `stored_name` varchar(255) NOT NULL,
            `file_path` varchar(500) NOT NULL,
            `file_size` bigint(20) NOT NULL,
            `mime_type` varchar(100) NOT NULL,
            `file_category` enum('source','preview','final','reference') DEFAULT 'source',
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`),
            KEY `idx_design_project_id` (`design_project_id`),
            KEY `idx_file_category` (`file_category`),
            KEY `idx_created_at` (`created_at`),
            CONSTRAINT `fk_design_project_files_project` FOREIGN KEY (`design_project_id`) REFERENCES `design_projects` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ Table design_project_files créée\n";

    echo "🎉 Tables créées avec succès !\n";
    echo "Vous pouvez maintenant tester le formulaire.\n";

} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
}
?>
