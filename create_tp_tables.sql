-- Script SQL pour créer les tables TP manquantes
-- À exécuter dans phpMyAdmin ou MySQL

-- Utiliser la base de données v4_evc
USE v4_evc;

-- Table principale des TP
CREATE TABLE IF NOT EXISTS `tp` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `category` varchar(100) DEFAULT NULL,
    `link` varchar(500) DEFAULT NULL,
    `tags` text DEFAULT NULL,
    `software_used` text DEFAULT NULL,
    `status` enum('en_cours','valide') DEFAULT 'en_cours',
    `thumbnail_path` varchar(500) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_status` (`status`),
    KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des fichiers associés aux TP
CREATE TABLE IF NOT EXISTS `tp_files` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `tp_id` int(11) NOT NULL,
    `original_name` varchar(255) NOT NULL,
    `file_path` varchar(500) NOT NULL,
    `mime_type` varchar(100) NOT NULL,
    `file_size` bigint(20) NOT NULL DEFAULT 0,
    `is_thumbnail` tinyint(1) DEFAULT 0,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_tp_id` (`tp_id`),
    KEY `idx_mime_type` (`mime_type`),
    KEY `idx_is_thumbnail` (`is_thumbnail`),
    FOREIGN KEY (`tp_id`) REFERENCES `tp`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des projets (si elle n'existe pas déjà)
CREATE TABLE IF NOT EXISTS `projects` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `category` varchar(100) DEFAULT NULL,
    `type` enum('digital','print') DEFAULT 'digital',
    `link` varchar(500) DEFAULT NULL,
    `tags` text DEFAULT NULL,
    `software_used` text DEFAULT NULL,
    `status` enum('en_cours','valide') DEFAULT 'en_cours',
    `thumbnail_path` varchar(500) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_status` (`status`),
    KEY `idx_type` (`type`),
    KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des images/documents associés aux projets
CREATE TABLE IF NOT EXISTS `project_images` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_id` int(11) NOT NULL,
    `original_name` varchar(255) NOT NULL,
    `file_path` varchar(500) NOT NULL,
    `mime_type` varchar(100) NOT NULL,
    `file_size` bigint(20) NOT NULL DEFAULT 0,
    `is_thumbnail` tinyint(1) DEFAULT 0,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_project_id` (`project_id`),
    KEY `idx_mime_type` (`mime_type`),
    KEY `idx_is_thumbnail` (`is_thumbnail`),
    FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Vérifier que la table users existe (sinon la créer)
CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `prenom` varchar(100) DEFAULT NULL,
    `nom` varchar(100) DEFAULT NULL,
    `email` varchar(255) NOT NULL UNIQUE,
    `password` varchar(255) NOT NULL,
    `photo` varchar(500) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insérer des données de test pour vérifier le fonctionnement
INSERT INTO `users` (`id`, `prenom`, `nom`, `email`, `password`) VALUES 
(1, 'Test', 'Utilisateur', 'test@example.com', 'password_hash')
ON DUPLICATE KEY UPDATE `prenom` = VALUES(`prenom`);

-- Insérer quelques TP de test
INSERT INTO `tp` (`id`, `user_id`, `title`, `description`, `category`, `status`) VALUES 
(1, 1, 'Logo Design - Entreprise ABC', 'Création d\'un logo moderne pour une entreprise', 'Logo', 'en_cours'),
(2, 1, 'Affiche Publicitaire', 'Design d\'une affiche pour un événement', 'Affiche', 'valide'),
(3, 1, 'Carte de Visite', 'Design de carte de visite professionnelle', 'Print', 'en_cours'),
(4, 1, 'Site Web - Portfolio', 'Maquette de site web portfolio', 'Web Design', 'valide'),
(5, 1, 'Brochure Commerciale', 'Design d\'une brochure 3 volets', 'Print', 'en_cours')
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

-- Insérer quelques fichiers de test
INSERT INTO `tp_files` (`tp_id`, `original_name`, `file_path`, `mime_type`, `file_size`, `is_thumbnail`) VALUES 
(1, 'logo_abc.png', 'storage/tp/1/logo_abc.png', 'image/png', 245760, 1),
(2, 'affiche_event.jpg', 'storage/tp/2/affiche_event.jpg', 'image/jpeg', 1048576, 1),
(3, 'carte_visite.pdf', 'storage/tp/3/carte_visite.pdf', 'application/pdf', 512000, 1),
(4, 'portfolio_mockup.png', 'storage/tp/4/portfolio_mockup.png', 'image/png', 2097152, 1),
(5, 'brochure.pdf', 'storage/tp/5/brochure.pdf', 'application/pdf', 3145728, 1)
ON DUPLICATE KEY UPDATE `original_name` = VALUES(`original_name`);

-- Afficher un résumé des données créées
SELECT 'Tables créées avec succès !' as message;

SELECT 
    'tp' as table_name,
    COUNT(*) as count,
    COUNT(CASE WHEN status = 'en_cours' THEN 1 END) as en_cours,
    COUNT(CASE WHEN status = 'valide' THEN 1 END) as valide
FROM tp
UNION ALL
SELECT 
    'tp_files' as table_name,
    COUNT(*) as count,
    COUNT(CASE WHEN mime_type LIKE 'image/%' THEN 1 END) as images,
    COUNT(CASE WHEN mime_type = 'application/pdf' THEN 1 END) as pdf
FROM tp_files;
