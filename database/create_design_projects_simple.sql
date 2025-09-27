-- =====================================================
-- TABLE SPÉCIALE POUR GESTION DES PROJETS DESIGN GRAPHIQUE
-- Version simplifiée sans fonctions complexes
-- =====================================================

-- Table principale des projets de design graphique
CREATE TABLE IF NOT EXISTS `design_projects` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `project_type` enum('logo','web','print','packaging','illustration','motion','strategy','autre') NOT NULL,
    `project_mode` enum('solo','groupe') NOT NULL DEFAULT 'solo',
    `software_used` json DEFAULT NULL COMMENT 'Logiciels utilisés stockés en JSON',
    `reference_url` varchar(500) DEFAULT NULL,
    `status` enum('draft','active','completed','cancelled') NOT NULL DEFAULT 'active',
    `progress_percentage` tinyint(3) DEFAULT 0 COMMENT 'Pourcentage de progression (0-100)',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `completed_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_project_type` (`project_type`),
    KEY `idx_project_mode` (`project_mode`),
    KEY `idx_status` (`status`),
    KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des fichiers associés aux projets de design
CREATE TABLE IF NOT EXISTS `design_project_files` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `design_project_id` int(11) NOT NULL,
    `original_name` varchar(255) NOT NULL,
    `stored_name` varchar(255) NOT NULL,
    `file_path` varchar(500) NOT NULL,
    `file_size` bigint(20) NOT NULL COMMENT 'Taille en bytes',
    `mime_type` varchar(100) NOT NULL,
    `file_category` enum('source','preview','final','reference') DEFAULT 'source' COMMENT 'Catégorie du fichier',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `idx_design_project_id` (`design_project_id`),
    KEY `idx_file_category` (`file_category`),
    KEY `idx_created_at` (`created_at`),
    CONSTRAINT `fk_design_project_files_project` FOREIGN KEY (`design_project_id`) REFERENCES `design_projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table pour les collaborateurs (projets groupe)
CREATE TABLE IF NOT EXISTS `design_project_collaborators` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `design_project_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `role` enum('owner','collaborator','viewer') NOT NULL DEFAULT 'collaborator',
    `joined_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `status` enum('pending','accepted','declined') NOT NULL DEFAULT 'pending',
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_design_project_user` (`design_project_id`, `user_id`),
    KEY `idx_design_project_id` (`design_project_id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_role` (`role`),
    CONSTRAINT `fk_design_collaborators_project` FOREIGN KEY (`design_project_id`) REFERENCES `design_projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table pour les commentaires/feedback sur les projets de design
CREATE TABLE IF NOT EXISTS `design_project_comments` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `design_project_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `comment` text NOT NULL,
    `comment_type` enum('feedback','question','approval','revision') DEFAULT 'feedback',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `idx_design_project_id` (`design_project_id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_comment_type` (`comment_type`),
    KEY `idx_created_at` (`created_at`),
    CONSTRAINT `fk_design_comments_project` FOREIGN KEY (`design_project_id`) REFERENCES `design_projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table pour les étapes/jalons du projet de design
CREATE TABLE IF NOT EXISTS `design_project_milestones` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `design_project_id` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `due_date` date DEFAULT NULL,
    `completed_at` timestamp NULL DEFAULT NULL,
    `order_index` tinyint(3) NOT NULL DEFAULT 1,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `idx_design_project_id` (`design_project_id`),
    KEY `idx_due_date` (`due_date`),
    KEY `idx_order` (`order_index`),
    CONSTRAINT `fk_design_milestones_project` FOREIGN KEY (`design_project_id`) REFERENCES `design_projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Index composites pour optimisation
CREATE INDEX IF NOT EXISTS idx_user_status_type ON design_projects(user_id, status, project_type);
CREATE INDEX IF NOT EXISTS idx_user_mode_created ON design_projects(user_id, project_mode, created_at DESC);
CREATE INDEX IF NOT EXISTS idx_status_progress ON design_projects(status, progress_percentage);
