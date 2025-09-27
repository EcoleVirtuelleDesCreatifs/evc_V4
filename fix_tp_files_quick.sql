-- Script de correction rapide pour la table tp_files
-- À exécuter dans phpMyAdmin

-- Vérifier si la table existe et la supprimer si nécessaire
DROP TABLE IF EXISTS `tp_files`;

-- Créer la table tp_files avec toutes les colonnes requises
CREATE TABLE `tp_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tp_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL COMMENT 'Nom du fichier stocké avec timestamp',
  `original_name` varchar(255) NOT NULL COMMENT 'Nom original du fichier uploadé',
  `file_path` varchar(500) NOT NULL COMMENT 'Chemin de stockage du fichier',
  `mime_type` varchar(100) NOT NULL COMMENT 'Type MIME du fichier',
  `file_size` bigint(20) NOT NULL COMMENT 'Taille du fichier en bytes',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_tp_id` (`tp_id`),
  KEY `idx_mime_type` (`mime_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Table des fichiers associés aux TP';

-- Vérifier la structure créée
DESCRIBE `tp_files`;

-- Afficher un message de confirmation
SELECT 'Table tp_files créée avec succès! La colonne filename est maintenant disponible.' AS message;
