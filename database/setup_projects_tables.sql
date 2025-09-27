-- Script SQL pour créer les tables de projets avec images multiples
-- À exécuter dans phpMyAdmin sur la base de données v4_evc

-- Table des projets
CREATE TABLE IF NOT EXISTS `projects` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `link` varchar(500) DEFAULT NULL,
  `tags` text DEFAULT NULL,
  `software_used` json NOT NULL,
  `thumbnail_image` varchar(500) DEFAULT NULL,
  `status` enum('en_cours','termine','valide','rejete') NOT NULL DEFAULT 'en_cours',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `projects_user_id_status_index` (`user_id`,`status`),
  KEY `projects_category_index` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des images de projets
CREATE TABLE IF NOT EXISTS `project_images` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `mime_type` varchar(100) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `is_thumbnail` tinyint(1) NOT NULL DEFAULT 0,
  `order_index` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_images_project_id_foreign` (`project_id`),
  KEY `project_images_project_id_order_index_index` (`project_id`,`order_index`),
  KEY `project_images_project_id_is_thumbnail_index` (`project_id`,`is_thumbnail`),
  CONSTRAINT `project_images_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Créer le répertoire de stockage (à faire manuellement)
-- mkdir -p /Applications/XAMPP/xamppfiles/htdocs/web/evc2024/V4_EVC/storage/app/public/projects

-- Instructions d'installation :
-- 1. Ouvrir phpMyAdmin (http://localhost/phpmyadmin)
-- 2. Sélectionner la base de données 'v4_evc'
-- 3. Aller dans l'onglet SQL
-- 4. Copier-coller ce script et l'exécuter
-- 5. Vérifier que les tables 'projects' et 'project_images' sont créées
-- 6. Créer le répertoire de stockage manuellement si nécessaire
