-- Recréer la table tp_files
DROP TABLE IF EXISTS `tp_files`;

CREATE TABLE `tp_files` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `tp_id` BIGINT UNSIGNED NOT NULL,
    `original_name` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(500) NOT NULL,
    `file_size` BIGINT NULL,
    `mime_type` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    INDEX `idx_tp_id` (`tp_id`),
    CONSTRAINT `fk_tp_files_tp_id` FOREIGN KEY (`tp_id`) REFERENCES `tp`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
