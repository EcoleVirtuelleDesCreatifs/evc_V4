-- Script de création de la table tp_files
-- Résout l'erreur: SQLSTATE[42S22]: Column not found: 1054 Unknown column 'filename' in 'field list'

-- Supprimer la table si elle existe déjà (optionnel)
-- DROP TABLE IF EXISTS `tp_files`;

-- Créer la table tp_files avec toutes les colonnes requises
CREATE TABLE `tp_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tp_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL COMMENT 'Nom du fichier stocké avec timestamp',
  `original_name` varchar(255) NOT NULL COMMENT 'Nom original du fichier uploadé',
  `file_path` varchar(500) NOT NULL COMMENT 'Chemin de stockage du fichier',
  `mime_type` varchar(100) NOT NULL COMMENT 'Type MIME du fichier (image/png, application/pdf, etc.)',
  `file_size` bigint(20) NOT NULL COMMENT 'Taille du fichier en bytes',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_tp_id` (`tp_id`),
  KEY `idx_mime_type` (`mime_type`),
  CONSTRAINT `fk_tp_files_tp_id` FOREIGN KEY (`tp_id`) REFERENCES `tp` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Table des fichiers associés aux TP';

-- Vérifier la structure créée
DESCRIBE `tp_files`;
