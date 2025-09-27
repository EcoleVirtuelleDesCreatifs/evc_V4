-- =====================================================
-- TABLE SPÉCIALE POUR GESTION DES PROJETS DESIGN GRAPHIQUE
-- (Différente de la table 'projects' utilisée pour les TP)
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

-- =====================================================
-- VUES POUR FACILITER LES REQUÊTES
-- =====================================================

-- Vue pour les projets de design avec statistiques
CREATE OR REPLACE VIEW `v_design_projects_stats` AS
SELECT 
    dp.*,
    COUNT(DISTINCT dpf.id) as files_count,
    COUNT(DISTINCT dpc.id) as collaborators_count,
    COUNT(DISTINCT dpcom.id) as comments_count,
    COUNT(DISTINCT dpm.id) as milestones_count,
    COUNT(DISTINCT CASE WHEN dpm.completed_at IS NOT NULL THEN dpm.id END) as completed_milestones_count,
    ROUND(
        CASE 
            WHEN COUNT(DISTINCT dpm.id) > 0 
            THEN (COUNT(DISTINCT CASE WHEN dpm.completed_at IS NOT NULL THEN dpm.id END) * 100.0 / COUNT(DISTINCT dpm.id))
            ELSE dp.progress_percentage 
        END, 1
    ) as calculated_progress,
    COALESCE(SUM(dpf.file_size), 0) as total_file_size
FROM design_projects dp
LEFT JOIN design_project_files dpf ON dp.id = dpf.design_project_id
LEFT JOIN design_project_collaborators dpc ON dp.id = dpc.design_project_id AND dpc.status = 'accepted'
LEFT JOIN design_project_comments dpcom ON dp.id = dpcom.design_project_id
LEFT JOIN design_project_milestones dpm ON dp.id = dpm.design_project_id
GROUP BY dp.id;

-- =====================================================
-- DONNÉES DE DÉMONSTRATION
-- =====================================================

-- Insertion de projets de design de démonstration
INSERT IGNORE INTO `design_projects` (`id`, `user_id`, `title`, `description`, `project_type`, `project_mode`, `software_used`, `reference_url`, `status`, `progress_percentage`) VALUES
(1, 1, 'Logo Entreprise Tech', 'Création d\'un logo moderne pour une startup technologique. Le logo doit refléter l\'innovation et la modernité avec des couleurs vives.', 'logo', 'solo', '["photoshop", "illustrator"]', 'https://dribbble.com/shots/tech-logos', 'active', 75),
(2, 1, 'Site Web Portfolio', 'Design d\'un site web portfolio pour un photographe professionnel. Interface moderne et épurée pour mettre en valeur les œuvres.', 'web', 'groupe', '["figma", "photoshop", "xd"]', 'https://behance.net/portfolio-examples', 'active', 45),
(3, 1, 'Packaging Produit Bio', 'Conception d\'un packaging écologique pour une gamme de produits biologiques. Design naturel et authentique.', 'packaging', 'solo', '["illustrator", "indesign", "photoshop"]', NULL, 'completed', 100),
(4, 1, 'Affiche Événement', 'Création d\'une affiche pour un festival de musique électronique. Style moderne et énergique.', 'print', 'solo', '["photoshop", "illustrator"]', 'https://pinterest.com/music-posters', 'draft', 25);

-- Insertion de fichiers de démonstration
INSERT IGNORE INTO `design_project_files` (`design_project_id`, `original_name`, `stored_name`, `file_path`, `file_size`, `mime_type`, `file_category`) VALUES
(1, 'logo_sketch_v1.ai', '1704067200_abc123.ai', 'uploads/design_projects/1704067200_abc123.ai', 2048000, 'application/postscript', 'source'),
(1, 'logo_final.png', '1704067300_def456.png', 'uploads/design_projects/1704067300_def456.png', 512000, 'image/png', 'final'),
(2, 'wireframe.fig', '1704067400_ghi789.fig', 'uploads/design_projects/1704067400_ghi789.fig', 1024000, 'application/octet-stream', 'source'),
(3, 'packaging_mockup.psd', '1704067500_jkl012.psd', 'uploads/design_projects/1704067500_jkl012.psd', 15728640, 'image/vnd.adobe.photoshop', 'preview');

-- Insertion de jalons de démonstration
INSERT IGNORE INTO `design_project_milestones` (`design_project_id`, `title`, `description`, `due_date`, `completed_at`, `order_index`) VALUES
(1, 'Recherche et inspiration', 'Collecte des références et définition du style', '2024-01-15', '2024-01-14 14:30:00', 1),
(1, 'Esquisses initiales', 'Création des premières esquisses du logo', '2024-01-20', '2024-01-19 16:45:00', 2),
(1, 'Versions finales', 'Finalisation des 3 versions du logo', '2024-01-25', NULL, 3),
(2, 'Analyse des besoins', 'Définition des besoins du client', '2024-01-10', '2024-01-09 10:00:00', 1),
(2, 'Wireframes', 'Création des wireframes des pages principales', '2024-01-18', NULL, 2);

-- =====================================================
-- FONCTIONS UTILITAIRES
-- =====================================================

-- Fonction pour calculer les statistiques utilisateur
DELIMITER //
CREATE OR REPLACE FUNCTION GetUserDesignProjectStats(userId INT)
RETURNS JSON
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE result JSON;
    
    SELECT JSON_OBJECT(
        'total_projects', COUNT(*),
        'solo_projects', SUM(CASE WHEN project_mode = 'solo' THEN 1 ELSE 0 END),
        'group_projects', SUM(CASE WHEN project_mode = 'groupe' THEN 1 ELSE 0 END),
        'completed_projects', SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END),
        'active_projects', SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END),
        'draft_projects', SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END),
        'avg_progress', ROUND(AVG(progress_percentage), 1)
    ) INTO result
    FROM design_projects 
    WHERE user_id = userId;
    
    RETURN result;
END//
DELIMITER ;

-- =====================================================
-- INDEX POUR OPTIMISATION DES PERFORMANCES
-- =====================================================

-- Index composites pour les requêtes fréquentes
CREATE INDEX idx_user_status_type ON design_projects(user_id, status, project_type);
CREATE INDEX idx_user_mode_created ON design_projects(user_id, project_mode, created_at DESC);
CREATE INDEX idx_status_progress ON design_projects(status, progress_percentage);

-- =====================================================
-- COMMENTAIRES ET DOCUMENTATION
-- =====================================================

-- Cette structure de base de données est spécialement conçue pour :
-- 1. Gérer les projets de design graphique (différents des TP)
-- 2. Supporter les projets solo et en groupe
-- 3. Suivre la progression avec des jalons
-- 4. Gérer les fichiers multiples par projet
-- 5. Permettre la collaboration et les commentaires
-- 6. Optimiser les performances avec des index appropriés
-- 7. Fournir des vues et fonctions pour les statistiques
