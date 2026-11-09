/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `accounting_budgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `accounting_budgets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `year` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `accounting_budgets_category_type_year_unique` (`category`,`type`,`year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `accounting_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `accounting_transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('income','expense') NOT NULL,
  `category` varchar(255) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `date` date NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `proof_path` varchar(255) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `student_name` varchar(255) DEFAULT NULL,
  `training_module` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `activity_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `year` smallint(5) unsigned DEFAULT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_filename` varchar(255) NOT NULL,
  `file_size` bigint(20) unsigned DEFAULT NULL,
  `download_count` bigint(20) unsigned NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_reports_is_published_year_index` (`is_published`,`year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `actualites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `actualites` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text NOT NULL,
  `content` longtext NOT NULL,
  `cover_image` varchar(255) NOT NULL,
  `cover_image_alt` varchar(255) DEFAULT NULL,
  `category` enum('general','formation','evenement','partenariat','succes') NOT NULL DEFAULT 'general',
  `visibility` enum('public','all','specific') NOT NULL DEFAULT 'all',
  `formations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`formations`)),
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `admin_id` bigint(20) unsigned DEFAULT NULL,
  `views_count` int(11) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `actualites_slug_unique` (`slug`),
  KEY `actualites_admin_id_foreign` (`admin_id`),
  CONSTRAINT `actualites_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `admin_admin_job_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_admin_job_profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` bigint(20) unsigned NOT NULL,
  `job_profile_id` bigint(20) unsigned NOT NULL,
  `starts_at` date DEFAULT NULL,
  `ends_at` date DEFAULT NULL,
  `allocation_percent` tinyint(3) unsigned NOT NULL DEFAULT 100,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_admin_job_profiles` (`admin_id`,`job_profile_id`,`starts_at`),
  KEY `admin_admin_job_profiles_admin_id_index` (`admin_id`),
  KEY `admin_admin_job_profiles_job_profile_id_index` (`job_profile_id`),
  CONSTRAINT `admin_admin_job_profiles_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  CONSTRAINT `admin_admin_job_profiles_job_profile_id_foreign` FOREIGN KEY (`job_profile_id`) REFERENCES `admin_job_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `admin_job_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_job_profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `base_monthly_amount` int(10) unsigned NOT NULL DEFAULT 0,
  `commission_rate_bp` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_job_profiles_code_unique` (`code`),
  KEY `admin_job_profiles_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `admin_salary_months`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_salary_months` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` bigint(20) unsigned NOT NULL,
  `year` smallint(5) unsigned NOT NULL,
  `month` tinyint(3) unsigned NOT NULL,
  `is_compliant` tinyint(1) NOT NULL DEFAULT 1,
  `base_amount` int(10) unsigned NOT NULL DEFAULT 25000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_salary_months_admin_id_year_month_unique` (`admin_id`,`year`,`month`),
  KEY `admin_salary_months_admin_id_index` (`admin_id`),
  KEY `admin_salary_months_year_index` (`year`),
  KEY `admin_salary_months_month_index` (`month`),
  CONSTRAINT `admin_salary_months_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `admin_task_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_task_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` bigint(20) unsigned NOT NULL,
  `task_type_id` bigint(20) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT 1,
  `performed_at` datetime NOT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_task_logs_admin_id_index` (`admin_id`),
  KEY `admin_task_logs_task_type_id_index` (`task_type_id`),
  KEY `admin_task_logs_performed_at_index` (`performed_at`),
  CONSTRAINT `admin_task_logs_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  CONSTRAINT `admin_task_logs_task_type_id_foreign` FOREIGN KEY (`task_type_id`) REFERENCES `admin_task_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `admin_task_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_task_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `job_profile_id` bigint(20) unsigned DEFAULT NULL,
  `kpi_catalog_key` varchar(255) DEFAULT NULL,
  `recurrence` varchar(255) NOT NULL DEFAULT 'monthly',
  `expected_per_month` int(10) unsigned NOT NULL DEFAULT 1,
  `weight` tinyint(3) unsigned NOT NULL DEFAULT 10,
  `deadline_hours` smallint(5) unsigned DEFAULT NULL,
  `is_critical` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_task_types_code_unique` (`code`),
  KEY `admin_task_types_role_index` (`role`),
  KEY `admin_task_types_role_is_active_index` (`role`,`is_active`),
  KEY `admin_task_types_job_profile_id_index` (`job_profile_id`),
  KEY `admin_task_types_kpi_catalog_key_index` (`kpi_catalog_key`),
  CONSTRAINT `admin_task_types_job_profile_id_foreign` FOREIGN KEY (`job_profile_id`) REFERENCES `admin_job_profiles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','manager','assistant','comptable') DEFAULT 'assistant',
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `can_view_salary_amount` tinyint(1) NOT NULL DEFAULT 0,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `notification_preferences` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `appointment_slots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `appointment_slots` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` bigint(20) unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `mode` varchar(20) NOT NULL DEFAULT 'en_ligne',
  `lieu` varchar(255) DEFAULT NULL,
  `capacity` int(10) unsigned NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_private` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `appointment_slots_date_is_active_index` (`date`,`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `appointments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slot_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `student_id` bigint(20) unsigned DEFAULT NULL,
  `motif` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `meet_link` varchar(255) DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `group_id` char(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointments_user_id_status_index` (`user_id`,`status`),
  KEY `appointments_slot_id_status_index` (`slot_id`,`status`),
  KEY `appointments_group_id_index` (`group_id`),
  CONSTRAINT `appointments_slot_id_foreign` FOREIGN KEY (`slot_id`) REFERENCES `appointment_slots` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `seance_id` bigint(20) unsigned NOT NULL,
  `student_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `status` enum('present','absent','late','excused') NOT NULL DEFAULT 'absent',
  `check_method` enum('manual','qrcode','meet','auto') NOT NULL DEFAULT 'manual',
  `recorded_by` bigint(20) unsigned DEFAULT NULL,
  `recorded_at` datetime DEFAULT NULL,
  `check_in_at` datetime DEFAULT NULL,
  `check_out_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendances_seance_id_student_id_unique` (`seance_id`,`student_id`),
  KEY `attendances_user_id_foreign` (`user_id`),
  KEY `attendances_student_id_status_index` (`student_id`,`status`),
  KEY `attendances_recorded_by_index` (`recorded_by`),
  CONSTRAINT `attendances_seance_id_foreign` FOREIGN KEY (`seance_id`) REFERENCES `seances` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `candidatures_collaborateurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `candidatures_collaborateurs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `prenom` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(30) NOT NULL,
  `poste` varchar(255) NOT NULL,
  `experience` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `cv_path` varchar(255) NOT NULL,
  `portfolio` varchar(255) DEFAULT NULL,
  `statut` enum('nouveau','en_cours','accepte','refuse') NOT NULL DEFAULT 'nouveau',
  `notes_admin` text DEFAULT NULL,
  `date_traitement` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `candidatures_collaborateurs_email_index` (`email`),
  KEY `candidatures_collaborateurs_statut_index` (`statut`),
  KEY `candidatures_collaborateurs_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `candidatures_formateurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `candidatures_formateurs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `prenom` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(30) NOT NULL,
  `domaine` varchar(255) NOT NULL,
  `experience` varchar(50) NOT NULL,
  `diplomes` text NOT NULL,
  `motivation` text NOT NULL,
  `cv_path` varchar(255) NOT NULL,
  `portfolio` varchar(255) DEFAULT NULL,
  `statut` enum('nouveau','en_cours','accepte','refuse') NOT NULL DEFAULT 'nouveau',
  `notes_admin` text DEFAULT NULL,
  `date_traitement` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `candidatures_formateurs_email_index` (`email`),
  KEY `candidatures_formateurs_domaine_index` (`domaine`),
  KEY `candidatures_formateurs_statut_index` (`statut`),
  KEY `candidatures_formateurs_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `module` varchar(255) DEFAULT NULL COMMENT 'Module de formation: design-graphique, community-management, intelligence-artificielle, gestion-informatique',
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_module_index` (`module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `certificates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `certificates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `formation` varchar(255) NOT NULL,
  `generated_by` bigint(20) unsigned DEFAULT NULL COMMENT 'Admin qui a généré le certificat',
  `generated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `certificates_student_id_index` (`student_id`),
  KEY `certificates_user_id_index` (`user_id`),
  KEY `certificates_formation_index` (`formation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `certification_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `certification_answers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `attempt_id` bigint(20) unsigned NOT NULL,
  `question_id` bigint(20) unsigned NOT NULL,
  `selected_option_id` bigint(20) unsigned DEFAULT NULL,
  `answer_text` text DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `admin_comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `certification_answers_attempt_id_question_id_unique` (`attempt_id`,`question_id`),
  KEY `certification_answers_question_id_foreign` (`question_id`),
  KEY `certification_answers_selected_option_id_foreign` (`selected_option_id`),
  CONSTRAINT `certification_answers_attempt_id_foreign` FOREIGN KEY (`attempt_id`) REFERENCES `certification_attempts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `certification_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `certification_questions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `certification_answers_selected_option_id_foreign` FOREIGN KEY (`selected_option_id`) REFERENCES `certification_options` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `certification_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `certification_attempts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `certification_id` bigint(20) unsigned NOT NULL,
  `student_id` bigint(20) unsigned NOT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `finished_at` timestamp NULL DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `score` decimal(8,2) DEFAULT NULL,
  `score_percentage` decimal(5,2) DEFAULT NULL,
  `status` enum('not_started','in_progress','submitted','graded') NOT NULL DEFAULT 'not_started',
  `is_auto_submitted` tinyint(1) NOT NULL DEFAULT 0,
  `passed` tinyint(1) DEFAULT NULL,
  `admin_feedback` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `certification_attempts_certification_id_student_id_unique` (`certification_id`,`student_id`),
  KEY `certification_attempts_student_id_foreign` (`student_id`),
  CONSTRAINT `certification_attempts_certification_id_foreign` FOREIGN KEY (`certification_id`) REFERENCES `certifications` (`id`) ON DELETE CASCADE,
  CONSTRAINT `certification_attempts_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `certification_eligibilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `certification_eligibilities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `formation` varchar(100) NOT NULL,
  `system_status` enum('not_eligible','pre_eligible') NOT NULL DEFAULT 'not_eligible',
  `admin_status` enum('pending','reviewing','eligible','rejected') NOT NULL DEFAULT 'pending',
  `studio_creative_status` enum('pending','validated','rejected') NOT NULL DEFAULT 'pending',
  `studio_creative_name` varchar(255) DEFAULT NULL,
  `studio_creative_comment` text DEFAULT NULL,
  `studio_creative_validated_by` bigint(20) unsigned DEFAULT NULL,
  `studio_creative_validated_at` timestamp NULL DEFAULT NULL,
  `validated_by` bigint(20) unsigned DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `admin_comment` text DEFAULT NULL,
  `manual_tp_count` int(10) unsigned DEFAULT NULL,
  `manual_projects_count` int(10) unsigned DEFAULT NULL,
  `last_evaluated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `certification_eligibilities_student_id_formation_unique` (`student_id`,`formation`),
  KEY `certification_eligibilities_formation_system_status_index` (`formation`,`system_status`),
  KEY `certification_eligibilities_formation_admin_status_index` (`formation`,`admin_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `certification_eligibility_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `certification_eligibility_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `certification_eligibility_id` bigint(20) unsigned NOT NULL,
  `from_system_status` varchar(50) DEFAULT NULL,
  `to_system_status` varchar(50) DEFAULT NULL,
  `from_admin_status` varchar(50) DEFAULT NULL,
  `to_admin_status` varchar(50) DEFAULT NULL,
  `from_studio_status` varchar(50) DEFAULT NULL,
  `to_studio_status` varchar(50) DEFAULT NULL,
  `admin_id` bigint(20) unsigned DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cert_elig_hist_eligibility_id_idx` (`certification_eligibility_id`),
  KEY `cert_elig_hist_admin_id_idx` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `certification_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `certification_options` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` bigint(20) unsigned NOT NULL,
  `option_text` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `order_index` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `certification_options_question_id_foreign` (`question_id`),
  CONSTRAINT `certification_options_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `certification_questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `certification_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `certification_questions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `certification_id` bigint(20) unsigned NOT NULL,
  `type` enum('qcm','redaction') NOT NULL DEFAULT 'qcm',
  `question_text` text NOT NULL,
  `media_url` varchar(255) DEFAULT NULL,
  `points` decimal(5,2) NOT NULL DEFAULT 1.00,
  `order_index` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `certification_questions_certification_id_foreign` (`certification_id`),
  CONSTRAINT `certification_questions_certification_id_foreign` FOREIGN KEY (`certification_id`) REFERENCES `certifications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `certification_student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `certification_student` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `certification_id` bigint(20) unsigned NOT NULL,
  `student_id` bigint(20) unsigned NOT NULL,
  `notified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `certification_student_certification_id_student_id_unique` (`certification_id`,`student_id`),
  KEY `certification_student_student_id_foreign` (`student_id`),
  CONSTRAINT `certification_student_certification_id_foreign` FOREIGN KEY (`certification_id`) REFERENCES `certifications` (`id`) ON DELETE CASCADE,
  CONSTRAINT `certification_student_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `certifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `certifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `formation` varchar(255) DEFAULT NULL,
  `duration_minutes` int(11) NOT NULL DEFAULT 60,
  `passing_score` decimal(5,2) NOT NULL DEFAULT 50.00,
  `total_points` decimal(8,2) NOT NULL DEFAULT 100.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `shuffle_questions` tinyint(1) NOT NULL DEFAULT 0,
  `instructions` text DEFAULT NULL,
  `status` enum('draft','published','scheduled') NOT NULL DEFAULT 'draft',
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `communiques`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `communiques` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `target_audience` varchar(255) NOT NULL DEFAULT 'all',
  `actualite_id` bigint(20) unsigned DEFAULT NULL,
  `evenement_id` bigint(20) unsigned DEFAULT NULL,
  `start_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `view_count` bigint(20) unsigned NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `communiques_actualite_id_index` (`actualite_id`),
  KEY `communiques_evenement_id_index` (`evenement_id`),
  CONSTRAINT `communiques_actualite_id_foreign` FOREIGN KEY (`actualite_id`) REFERENCES `actualites` (`id`) ON DELETE SET NULL,
  CONSTRAINT `communiques_evenement_id_foreign` FOREIGN KEY (`evenement_id`) REFERENCES `evenements` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cvtheque_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cvtheque_profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `professional_title` varchar(255) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `availability` varchar(255) DEFAULT NULL,
  `skills` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`skills`)),
  `portfolio_url` varchar(255) DEFAULT NULL,
  `profile_completion_score` int(11) NOT NULL DEFAULT 0,
  `cv_file_path` varchar(255) DEFAULT NULL,
  `cv_file_name` varchar(255) DEFAULT NULL,
  `motivation_letter_path` varchar(255) DEFAULT NULL,
  `motivation_letter_name` varchar(255) DEFAULT NULL,
  `portfolio_files` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`portfolio_files`)),
  `pressbook_file_path` varchar(255) DEFAULT NULL,
  `pressbook_file_name` varchar(255) DEFAULT NULL,
  `report_file_path` varchar(255) DEFAULT NULL,
  `report_file_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cvtheque_profiles_user_id_foreign` (`user_id`),
  CONSTRAINT `cvtheque_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `demandes_partenariat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `demandes_partenariat` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organisation` varchar(255) NOT NULL,
  `nom_contact` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(30) NOT NULL,
  `site_web` varchar(255) DEFAULT NULL,
  `type_partenariat` varchar(255) NOT NULL,
  `secteur` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `statut` enum('nouveau','en_cours','accepte','refuse') NOT NULL DEFAULT 'nouveau',
  `notes_admin` text DEFAULT NULL,
  `date_traitement` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `demandes_partenariat_email_index` (`email`),
  KEY `demandes_partenariat_statut_index` (`statut`),
  KEY `demandes_partenariat_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `design_project_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `design_project_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `is_thumbnail` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `design_project_files_project_id_index` (`project_id`),
  KEY `design_project_files_file_type_index` (`file_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `design_projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `design_projects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'solo',
  `project_type` varchar(100) DEFAULT NULL,
  `software_used` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`software_used`)),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `admin_comment` text DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `design_projects_user_id_index` (`user_id`),
  KEY `design_projects_category_index` (`category`),
  KEY `design_projects_status_index` (`status`),
  KEY `design_projects_project_type_index` (`project_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `document_validations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_validations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `document_path` varchar(255) NOT NULL,
  `status` enum('en_cours','valide','rejete') NOT NULL DEFAULT 'en_cours',
  `admin_comment` text DEFAULT NULL,
  `validated_by` bigint(20) unsigned DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_document` (`user_id`,`document_type`,`document_name`),
  KEY `document_validations_user_id_document_type_index` (`user_id`,`document_type`),
  KEY `document_validations_status_index` (`status`),
  KEY `document_validations_validated_at_index` (`validated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `donations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `donations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'XOF',
  `payment_method` varchar(80) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'new',
  `processed_at` timestamp NULL DEFAULT NULL,
  `processed_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `donations_email_index` (`email`),
  KEY `donations_status_index` (`status`),
  KEY `donations_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `end_of_training_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `end_of_training_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `student_id` bigint(20) unsigned NOT NULL,
  `formation` varchar(100) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_filename` varchar(255) NOT NULL,
  `file_size` int(11) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `admin_comment` text DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `end_of_training_reports_user_id_index` (`user_id`),
  KEY `end_of_training_reports_student_id_index` (`student_id`),
  KEY `end_of_training_reports_status_index` (`status`),
  KEY `end_of_training_reports_formation_index` (`formation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `evenements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `evenements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text NOT NULL,
  `content` longtext NOT NULL,
  `event_date` date NOT NULL,
  `event_end_date` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `event_type` enum('online','physical','hybrid') NOT NULL DEFAULT 'physical',
  `registration_link` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `cover_image_alt` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `visibility` enum('public','all','specific') DEFAULT 'public',
  `formations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`formations`)),
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `author_id` bigint(20) unsigned DEFAULT NULL,
  `views_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `evenements_slug_unique` (`slug`),
  KEY `evenements_author_id_foreign` (`author_id`),
  KEY `evenements_status_index` (`status`),
  KEY `evenements_event_date_index` (`event_date`),
  KEY `evenements_is_featured_index` (`is_featured`),
  KEY `evenements_published_at_index` (`published_at`),
  CONSTRAINT `evenements_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `formation_chapters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `formation_chapters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `formation_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 1,
  `duration` int(11) DEFAULT NULL COMMENT 'Duration in minutes',
  `video_url` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `formation_chapters_formation_id_order_index` (`formation_id`,`order`),
  CONSTRAINT `formation_chapters_formation_id_foreign` FOREIGN KEY (`formation_id`) REFERENCES `formations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `formation_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `formation_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `formation_id` bigint(20) unsigned NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `stored_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `mime_type` varchar(255) NOT NULL,
  `file_type` enum('pdf','document','image','video','other') NOT NULL DEFAULT 'pdf',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `formation_files_formation_id_index` (`formation_id`),
  KEY `formation_files_file_type_index` (`file_type`),
  CONSTRAINT `formation_files_formation_id_foreign` FOREIGN KEY (`formation_id`) REFERENCES `formations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `formation_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `formation_user` (
  `formation_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`formation_id`,`user_id`),
  KEY `formation_user_user_id_foreign` (`user_id`),
  CONSTRAINT `formation_user_formation_id_foreign` FOREIGN KEY (`formation_id`) REFERENCES `formations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `formation_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `formations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `formations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `level` varchar(255) NOT NULL DEFAULT 'beginner',
  `duration_weeks` int(11) NOT NULL DEFAULT 4,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_internal` tinyint(1) NOT NULL DEFAULT 1,
  `target_student_types` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`target_student_types`)),
  `student_restriction` enum('all','active_only','registration_period') NOT NULL DEFAULT 'all',
  `registration_start` date DEFAULT NULL,
  `registration_end` date DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `vimeo_code` text DEFAULT NULL,
  `skills` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`skills`)),
  `prerequisites` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`prerequisites`)),
  `modules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`modules`)),
  `status` enum('draft','active','inactive','archived') NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `max_students` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `instructor_name` varchar(255) DEFAULT NULL,
  `instructor_bio` text DEFAULT NULL,
  `schedule` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`schedule`)),
  `format` enum('online','offline','hybrid') NOT NULL DEFAULT 'online',
  `location` varchar(255) DEFAULT NULL,
  `resources` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`resources`)),
  `satisfaction_rate` decimal(3,1) NOT NULL DEFAULT 0.0,
  `completion_rate` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `formations_slug_unique` (`slug`),
  KEY `formations_category_id_foreign` (`category_id`),
  CONSTRAINT `formations_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jury_evaluation_scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jury_evaluation_scores` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `jury_evaluation_id` bigint(20) unsigned NOT NULL,
  `category_key` varchar(255) NOT NULL,
  `category_label` varchar(255) NOT NULL,
  `criterion_key` varchar(255) NOT NULL,
  `criterion_label` varchar(255) NOT NULL,
  `score` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `max_score` tinyint(3) unsigned NOT NULL DEFAULT 20,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jury_evaluation_scores_jury_evaluation_id_foreign` (`jury_evaluation_id`),
  CONSTRAINT `jury_evaluation_scores_jury_evaluation_id_foreign` FOREIGN KEY (`jury_evaluation_id`) REFERENCES `jury_evaluations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jury_evaluations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jury_evaluations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `jury_member_id` bigint(20) unsigned DEFAULT NULL,
  `jury_name` varchar(255) NOT NULL,
  `jury_function` varchar(255) DEFAULT NULL,
  `jury_email` varchar(255) NOT NULL,
  `evaluation_date` date NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `global_comment` text DEFAULT NULL,
  `total_score` smallint(5) unsigned NOT NULL DEFAULT 0,
  `status` enum('draft','submitted') NOT NULL DEFAULT 'submitted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jury_evaluations_jury_email_group_name_unique` (`jury_email`,`group_name`),
  KEY `jury_evaluations_jury_member_id_foreign` (`jury_member_id`),
  CONSTRAINT `jury_evaluations_jury_member_id_foreign` FOREIGN KEY (`jury_member_id`) REFERENCES `jury_members` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jury_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jury_members` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `unique_identifier` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `flag` varchar(20) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jury_members_unique_identifier_unique` (`unique_identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `libraries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `libraries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  `download_url` varchar(255) DEFAULT NULL,
  `file_type` varchar(255) NOT NULL,
  `size` int(10) unsigned NOT NULL,
  `downloads_count` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `library_category_id` bigint(20) unsigned DEFAULT NULL,
  `external_link` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `recipients` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`recipients`)),
  PRIMARY KEY (`id`),
  KEY `libraries_library_category_id_foreign` (`library_category_id`),
  CONSTRAINT `libraries_library_category_id_foreign` FOREIGN KEY (`library_category_id`) REFERENCES `library_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `library_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `library_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `meeting_clicks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `meeting_clicks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `seance_id` bigint(20) unsigned NOT NULL,
  `student_id` bigint(20) unsigned NOT NULL,
  `clicked_at` datetime NOT NULL,
  `duration_seconds` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `meeting_clicks_student_id_foreign` (`student_id`),
  KEY `meeting_clicks_seance_id_student_id_index` (`seance_id`,`student_id`),
  CONSTRAINT `meeting_clicks_seance_id_foreign` FOREIGN KEY (`seance_id`) REFERENCES `seances` (`id`) ON DELETE CASCADE,
  CONSTRAINT `meeting_clicks_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `partnerships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `partnerships` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `prefix` varchar(255) NOT NULL DEFAULT 'Partenaire à',
  `name` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `partnerships_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `payment_reminders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_reminders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_reminders_student_id_index` (`student_id`),
  CONSTRAINT `payment_reminders_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pre_registration_id` bigint(20) unsigned DEFAULT NULL,
  `student_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `commercial_admin_id` bigint(20) unsigned DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'XOF',
  `payment_method` enum('orange_money','mtn_mobile','wave','moov_money','carte_bancaire','cinetpay') DEFAULT NULL,
  `status` enum('pending','completed','failed','refunded','cancelled') NOT NULL DEFAULT 'pending',
  `payment_type` enum('full','installment') NOT NULL DEFAULT 'full',
  `installment_number` int(11) DEFAULT NULL,
  `total_installments` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `parent_payment_id` bigint(20) unsigned DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_reference` varchar(255) NOT NULL,
  `payment_url` text DEFAULT NULL,
  `cpm_trans_id` varchar(255) DEFAULT NULL COMMENT 'CinetPay transaction ID',
  `cpm_site_id` varchar(255) DEFAULT NULL,
  `cpm_custom` text DEFAULT NULL COMMENT 'Custom data from CinetPay',
  `phone_number` varchar(20) DEFAULT NULL,
  `payer_name` varchar(255) DEFAULT NULL,
  `payer_email` varchar(255) DEFAULT NULL,
  `account_creation_token` varchar(512) DEFAULT NULL,
  `account_created` tinyint(1) NOT NULL DEFAULT 0,
  `account_created_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL COMMENT 'Lien expire après 7 jours',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_payment_reference_unique` (`payment_reference`),
  UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  UNIQUE KEY `payments_account_creation_token_unique` (`account_creation_token`),
  KEY `payments_pre_registration_id_index` (`pre_registration_id`),
  KEY `payments_student_id_index` (`student_id`),
  KEY `payments_user_id_index` (`user_id`),
  KEY `payments_status_index` (`status`),
  KEY `payments_transaction_id_index` (`transaction_id`),
  KEY `payments_cpm_trans_id_index` (`cpm_trans_id`),
  KEY `payments_commercial_admin_id_index` (`commercial_admin_id`),
  CONSTRAINT `payments_commercial_admin_id_foreign` FOREIGN KEY (`commercial_admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `plaquette_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `plaquette_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `plaquette_id` bigint(20) unsigned NOT NULL,
  `nom` varchar(120) NOT NULL,
  `prenoms` varchar(120) NOT NULL,
  `type_formation` varchar(120) NOT NULL,
  `pays` varchar(120) NOT NULL,
  `ville` varchar(120) NOT NULL,
  `whatsapp` varchar(40) NOT NULL,
  `email` varchar(255) NOT NULL,
  `niveau_etude` varchar(120) NOT NULL,
  `motivation` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approved_by_admin_id` bigint(20) unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `admin_comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plaquette_requests_plaquette_id_foreign` (`plaquette_id`),
  KEY `plaquette_requests_status_plaquette_id_index` (`status`,`plaquette_id`),
  KEY `plaquette_requests_email_index` (`email`),
  CONSTRAINT `plaquette_requests_plaquette_id_foreign` FOREIGN KEY (`plaquette_id`) REFERENCES `plaquettes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `plaquettes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `plaquettes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `formation_id` bigint(20) unsigned DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_filename` varchar(255) NOT NULL,
  `file_size` bigint(20) unsigned DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `format` enum('online','offline') NOT NULL DEFAULT 'online',
  `download_count` bigint(20) unsigned NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plaquettes_formation_id_foreign` (`formation_id`),
  KEY `plaquettes_is_published_is_active_formation_id_index` (`is_published`,`is_active`,`formation_id`),
  CONSTRAINT `plaquettes_formation_id_foreign` FOREIGN KEY (`formation_id`) REFERENCES `formations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pre_registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pre_registrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `sexe` varchar(1) DEFAULT NULL,
  `nationalite` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `whatsapp` varchar(255) DEFAULT NULL,
  `pays` varchar(255) NOT NULL,
  `ville` varchar(255) DEFAULT NULL,
  `niveau_etude` varchar(255) NOT NULL,
  `domaine_etude` varchar(255) DEFAULT NULL,
  `competences` text DEFAULT NULL,
  `choix_formation` enum('design_graphique','community_management','gestion_informatique','intelligence_artificielle','design_graphique_community_management','design_graphique_community_manager','design_cm') NOT NULL,
  `niveau_dans_formation` varchar(255) DEFAULT NULL,
  `programme` varchar(255) DEFAULT NULL,
  `how_known` varchar(255) DEFAULT NULL,
  `certify` tinyint(1) NOT NULL DEFAULT 0,
  `consent` tinyint(1) NOT NULL DEFAULT 0,
  `has_computer` tinyint(1) NOT NULL DEFAULT 0,
  `has_smartphone` tinyint(1) NOT NULL DEFAULT 0,
  `disponibilite` varchar(255) NOT NULL,
  `motivation` text NOT NULL,
  `date_inscription_souhaitee` date DEFAULT NULL,
  `eligibility_notified_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `reviewed_by` bigint(20) unsigned DEFAULT NULL,
  `commercial_admin_id` bigint(20) unsigned DEFAULT NULL,
  `discount_amount` int(10) unsigned NOT NULL DEFAULT 0,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pre_registrations_email_unique` (`email`),
  KEY `pre_registrations_commercial_admin_id_index` (`commercial_admin_id`),
  CONSTRAINT `pre_registrations_commercial_admin_id_foreign` FOREIGN KEY (`commercial_admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `product_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `summary` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `seo_geo` text DEFAULT NULL,
  `price` int(11) NOT NULL DEFAULT 0,
  `old_price` int(11) DEFAULT NULL,
  `delivery_mode` enum('deposit','cash_on_delivery') NOT NULL DEFAULT 'cash_on_delivery',
  `delivery_cost` int(11) DEFAULT NULL,
  `delivery_zones` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`delivery_zones`)),
  `email` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `variants` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variants`)),
  `view_count` bigint(20) unsigned NOT NULL DEFAULT 0,
  `stock` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `programme_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `programme_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `programme_id` bigint(20) unsigned NOT NULL,
  `thematique` varchar(255) NOT NULL,
  `session_date` date NOT NULL,
  `session_time` time NOT NULL,
  `type_formation` varchar(50) NOT NULL,
  `lieu` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `piece_jointe` varchar(500) DEFAULT NULL,
  `piece_jointe_mime` varchar(120) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `programme_items_programme_id_index` (`programme_id`),
  CONSTRAINT `programme_items_programme_id_foreign` FOREIGN KEY (`programme_id`) REFERENCES `programmes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `programmes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `programmes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `image` varchar(500) DEFAULT NULL,
  `month_start` date DEFAULT NULL,
  `programme_date` date DEFAULT NULL,
  `programme_time` time DEFAULT NULL,
  `type_formation` varchar(50) DEFAULT NULL,
  `lieu` varchar(255) DEFAULT NULL,
  `fichier_pdf` varchar(500) DEFAULT NULL,
  `formation` varchar(100) NOT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `description` text DEFAULT NULL,
  `piece_jointe` varchar(500) DEFAULT NULL,
  `student_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`student_ids`)),
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `programmes_formation_index` (`formation`),
  KEY `programmes_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `project_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `is_thumbnail` tinyint(1) NOT NULL DEFAULT 0,
  `order_index` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_images_project_id_order_index_index` (`project_id`,`order_index`),
  KEY `project_images_project_id_is_thumbnail_index` (`project_id`,`is_thumbnail`),
  CONSTRAINT `project_images_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `project_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_templates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `tags` text DEFAULT NULL,
  `software_used` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`software_used`)),
  `thumbnail_image` varchar(255) DEFAULT NULL,
  `brief_content` text DEFAULT NULL,
  `default_deadline_days` int(11) NOT NULL DEFAULT 7,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_templates_category_index` (`category`),
  KEY `project_templates_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `projects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `tags` text DEFAULT NULL,
  `software_used` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`software_used`)),
  `deadline` date DEFAULT NULL,
  `thumbnail_image` varchar(255) DEFAULT NULL,
  `status` enum('en_cours','termine','valide','rejete') NOT NULL DEFAULT 'en_cours',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `admin_hidden` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `projects_user_id_status_index` (`user_id`,`status`),
  KEY `projects_category_index` (`category`),
  KEY `projects_deadline_index` (`deadline`),
  KEY `projects_admin_hidden_index` (`admin_hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `promo_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `promo_codes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `student_id` varchar(255) DEFAULT NULL,
  `type` enum('percent','fixed') NOT NULL DEFAULT 'percent',
  `value` int(11) NOT NULL DEFAULT 0,
  `max_uses` int(11) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `promo_codes_code_unique` (`code`),
  UNIQUE KEY `promo_codes_student_id_unique` (`student_id`),
  KEY `promo_codes_code_index` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `reco60_enrollments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `reco60_enrollments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `student_id` bigint(20) unsigned DEFAULT NULL,
  `brand_name` varchar(255) NOT NULL,
  `project_type` varchar(40) NOT NULL DEFAULT 'marque',
  `presentation` text NOT NULL,
  `platforms` varchar(255) DEFAULT NULL,
  `objectives` text DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'in_progress',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reco60_enrollments_user_id_index` (`user_id`),
  KEY `reco60_enrollments_student_id_index` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `saop_eligibility_tests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `saop_eligibility_tests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `whatsapp` varchar(255) DEFAULT NULL,
  `formation` varchar(255) DEFAULT NULL,
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`answers`)),
  `duration_seconds` int(10) unsigned NOT NULL DEFAULT 0,
  `started_at` timestamp NULL DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'submitted',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `saop_eligibility_tests_email_index` (`email`),
  KEY `saop_eligibility_tests_formation_index` (`formation`),
  KEY `saop_eligibility_tests_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `seance_qr_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `seance_qr_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `seance_id` bigint(20) unsigned NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `closed_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seance_qr_tokens_token_unique` (`token`),
  KEY `seance_qr_tokens_seance_id_foreign` (`seance_id`),
  KEY `seance_qr_tokens_token_index` (`token`),
  CONSTRAINT `seance_qr_tokens_seance_id_foreign` FOREIGN KEY (`seance_id`) REFERENCES `seances` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `seances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `seances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `module` varchar(255) DEFAULT NULL,
  `formateur` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `formation` varchar(255) NOT NULL,
  `type` enum('onsite','online','hybrid') NOT NULL DEFAULT 'onsite',
  `location` varchar(255) DEFAULT NULL,
  `meet_link` text DEFAULT NULL,
  `scheduled_at` datetime NOT NULL,
  `duration_minutes` smallint(5) unsigned NOT NULL DEFAULT 60,
  `status` enum('scheduled','ongoing','completed','cancelled') NOT NULL DEFAULT 'scheduled',
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `seances_formation_index` (`formation`),
  KEY `seances_scheduled_at_status_index` (`scheduled_at`,`status`),
  KEY `seances_created_by_index` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `second_installment_reminders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `second_installment_reminders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `payment_id` bigint(20) unsigned NOT NULL,
  `candidate_email` varchar(255) NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `days_remaining` int(11) NOT NULL DEFAULT 7,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `second_installment_reminders_payment_id_index` (`payment_id`),
  KEY `second_installment_reminders_candidate_email_index` (`candidate_email`),
  KEY `second_installment_reminders_sent_at_index` (`sent_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `session_time_tracking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `session_time_tracking` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `seance_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `page_type` varchar(255) NOT NULL DEFAULT 'seance',
  `session_start` timestamp NULL DEFAULT NULL,
  `session_end` timestamp NULL DEFAULT NULL,
  `duration_seconds` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `user_agent` varchar(255) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `session_time_tracking_seance_id_foreign` (`seance_id`),
  KEY `session_time_tracking_user_id_foreign` (`user_id`),
  KEY `session_time_tracking_student_id_seance_id_index` (`student_id`,`seance_id`),
  KEY `session_time_tracking_student_id_session_start_index` (`student_id`,`session_start`),
  KEY `session_time_tracking_is_active_index` (`is_active`),
  CONSTRAINT `session_time_tracking_seance_id_foreign` FOREIGN KEY (`seance_id`) REFERENCES `seances` (`id`) ON DELETE CASCADE,
  CONSTRAINT `session_time_tracking_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `session_time_tracking_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `store_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `store_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `order_number` varchar(50) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `prenoms` varchar(255) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `delivery_location` varchar(255) DEFAULT NULL,
  `delivery_mode` enum('delivery','pickup') NOT NULL DEFAULT 'delivery',
  `payment_method` enum('cash','mobile_money') NOT NULL DEFAULT 'cash',
  `autre` text DEFAULT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`items`)),
  `total` int(11) NOT NULL DEFAULT 0,
  `promo_code` varchar(255) DEFAULT NULL,
  `student_id` varchar(255) DEFAULT NULL,
  `discount` int(11) NOT NULL DEFAULT 0,
  `delivery_cost` int(11) NOT NULL DEFAULT 0,
  `subtotal` int(11) NOT NULL DEFAULT 0,
  `final_total` int(11) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `store_orders_order_number_unique` (`order_number`),
  KEY `store_orders_user_id_index` (`user_id`),
  KEY `store_orders_promo_code_index` (`promo_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `students` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Homme','Femme','Autre') DEFAULT NULL,
  `student_id` varchar(255) NOT NULL,
  `program` varchar(255) DEFAULT NULL,
  `level` varchar(255) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `quartier` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'Côte d''Ivoire',
  `biography` text DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','graduated','suspended') NOT NULL DEFAULT 'active',
  `deactivation_reason` text DEFAULT NULL,
  `deactivated_at` timestamp NULL DEFAULT NULL,
  `gpa` decimal(3,2) DEFAULT NULL,
  `credits_earned` int(11) NOT NULL DEFAULT 0,
  `years_experience` int(11) DEFAULT NULL,
  `industry_sector` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `expiration_date` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_email_unique` (`email`),
  UNIQUE KEY `students_student_id_unique` (`student_id`),
  KEY `students_user_id_index` (`user_id`),
  CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tp` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `link` varchar(500) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `is_report` tinyint(1) NOT NULL DEFAULT 0,
  `admin_comment` text DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tp_user_id_index` (`user_id`),
  KEY `tp_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tp_assignment_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tp_assignment_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tp_assignment_id` bigint(20) unsigned NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tp_assignment_files_tp_assignment_id_index` (`tp_assignment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tp_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tp_assignments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `student_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `deadline` datetime NOT NULL,
  `formation` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'assigned',
  `assigned_by` bigint(20) unsigned NOT NULL,
  `submission_link` text DEFAULT NULL,
  `admin_comment` text DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `admin_hidden` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `tp_assignments_user_id_index` (`user_id`),
  KEY `tp_assignments_student_id_index` (`student_id`),
  KEY `tp_assignments_status_index` (`status`),
  KEY `tp_assignments_formation_index` (`formation`),
  KEY `tp_assignments_assigned_by_index` (`assigned_by`),
  KEY `tp_assignments_admin_hidden_index` (`admin_hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tp_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tp_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tp_id` bigint(20) unsigned NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tp_files_tp_id_index` (`tp_id`),
  CONSTRAINT `tp_files_tp_id_foreign` FOREIGN KEY (`tp_id`) REFERENCES `tp` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tp_submission_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tp_submission_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tp_assignment_id` bigint(20) unsigned NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tp_submission_files_tp_assignment_id_index` (`tp_assignment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_activities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `activity_type` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_activities_user_id_index` (`user_id`),
  KEY `user_activities_activity_type_index` (`activity_type`),
  KEY `user_activities_created_at_index` (`created_at`),
  CONSTRAINT `user_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `formations_inscrites` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`formations_inscrites`)),
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `visits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `visits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `visited_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visits_visited_at_index` (`visited_at`),
  KEY `visits_product_id_visited_at_index` (`product_id`,`visited_at`),
  KEY `visits_session_id_index` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `webtv_subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `webtv_subscribers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `verification_token` varchar(255) DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `last_notified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `webtv_subscribers_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `webtv_videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `webtv_videos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `video_url` varchar(255) NOT NULL,
  `vimeo_playlist_id` varchar(255) DEFAULT NULL,
  `vimeo_showcase_id` varchar(255) DEFAULT NULL,
  `vimeo_user_id` varchar(255) DEFAULT NULL,
  `total_videos` int(11) NOT NULL DEFAULT 0,
  `playlist_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`playlist_data`)),
  `embed_code` text DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `type` enum('normal','live') NOT NULL DEFAULT 'normal',
  `category` enum('design-graphique','community-management','intelligence-artificielle','gestion-informatique') DEFAULT NULL,
  `status` enum('scheduled','active','paused','ended') NOT NULL DEFAULT 'scheduled',
  `scheduled_start` datetime DEFAULT NULL,
  `scheduled_end` datetime DEFAULT NULL,
  `actual_start` datetime DEFAULT NULL,
  `actual_end` datetime DEFAULT NULL,
  `loop_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `autoplay` tinyint(1) NOT NULL DEFAULT 1,
  `autopause` tinyint(1) NOT NULL DEFAULT 1,
  `loop_count` int(11) NOT NULL DEFAULT 0,
  `current_video_index` int(11) NOT NULL DEFAULT 0,
  `view_count` int(11) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000001_create_cache_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'0001_01_01_000002_create_jobs_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2024_01_01_000001_create_tp_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2024_01_01_000002_create_tp_files_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2024_01_01_000003_create_design_projects_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2024_01_01_000004_create_design_project_files_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2024_01_12_000000_create_projects_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2024_01_12_000001_create_project_images_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2024_01_14_000000_create_cvtheque_profiles_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2024_01_15_000000_create_document_validations_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2025_01_07_000001_create_formation_files_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2025_01_15_000001_create_evenements_table',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2025_01_16_000001_create_actualites_table',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2025_01_21_000001_create_tp_submission_files_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2025_07_28_144830_create_students_table',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2025_08_13_005458_create_admins_table',17);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2025_08_18_004144_create_categories_table',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2025_08_19_180000_create_formations_table',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2025_09_25_025122_create_formation_user_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2025_09_25_025954_create_libraries_table',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2025_09_26_165807_create_library_categories_table',22);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2025_09_26_182910_create_pre_registrations_table',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2025_10_02_134100_create_user_activities_table',24);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2025_10_14_000001_create_tp_assignments_table',25);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2025_10_14_000002_create_tp_assignment_files_table',26);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2025_10_14_000003_create_programmes_table',27);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2025_10_31_101917_create_candidatures_collaborateurs_table',28);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2025_10_31_105347_create_demandes_partenariat_table',29);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2025_10_31_111241_create_candidatures_formateurs_table',30);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2025_11_09_154805_create_webtv_subscribers_table',31);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2025_11_30_000001_create_end_of_training_reports_table',32);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2025_11_30_154345_create_certificates_table',33);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2025_12_03_181827_create_webtv_videos_table',34);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2025_12_07_000000_create_accounting_transactions_table',35);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2025_12_07_000001_create_accounting_budgets_table',36);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2025_12_07_123925_create_communiques_table',37);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2025_12_07_223742_create_formation_chapters_table',38);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2025_12_09_000001_create_payments_table',39);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2025_12_09_114631_create_second_installment_reminders_table',40);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2025_12_14_233500_create_notifications_table',41);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2025_12_25_000001_create_donations_table',42);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2025_12_26_030600_create_payment_reminders_table',43);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2026_01_09_020000_create_admin_task_types_table',44);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2026_01_09_020100_create_admin_task_logs_table',45);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2026_01_09_020200_create_admin_salary_months_table',46);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2026_01_09_030100_create_admin_job_profiles_table',47);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2026_01_09_030200_create_admin_admin_job_profiles_table',48);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2026_01_13_000005_create_programme_items_table',49);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2026_01_16_000001_create_activity_reports_table',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (51,'2026_01_20_000001_create_plaquettes_table',51);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2026_01_25_000001_create_plaquette_requests_table',52);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2026_03_01_000001_create_partnerships_table',53);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2026_03_04_100000_create_certifications_table',54);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2026_06_02_000002_create_saop_eligibility_tests_table',55);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (56,'2026_06_03_164000_create_jury_evaluations_table',56);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (57,'2026_06_03_164001_create_jury_evaluation_scores_table',57);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (58,'2026_06_03_180500_create_jury_members_table',58);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (59,'2026_08_18_135337_create_store_orders_table',59);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (60,'2026_08_24_130000_create_product_categories_table',60);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (61,'2026_08_24_130001_create_products_table',61);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (62,'2026_08_25_011335_create_visits_table',62);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (63,'2026_08_25_015505_create_promo_codes_table',63);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (64,'2026_09_04_000001_create_seances_table',64);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (65,'2026_09_04_000002_create_attendances_table',65);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (66,'2026_09_04_000003_create_meeting_clicks_table',66);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (67,'2026_09_04_000007_create_seance_qr_tokens_table',67);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (68,'2026_09_06_150000_create_certification_eligibilities_table',68);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (69,'2026_09_11_000001_fix_attendances_schema_safety',69);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (70,'2026_09_14_073618_create_project_templates_table',70);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (71,'2026_09_14_120102_create_session_time_tracking_table',71);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (72,'2026_09_22_000001_create_appointment_slots_table',72);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (73,'2026_09_22_000002_create_appointments_table',73);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (74,'2026_10_04_000001_create_reco60_enrollments_table',74);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (75,'2024_01_15_000000_add_file_fields_to_cvtheque_profiles_table',75);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (76,'2025_01_15_000002_update_evenements_visibility_enum',76);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (77,'2025_08_14_111514_add_formations_inscrites_to_users_table',77);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (78,'2025_09_25_022856_add_vimeo_code_to_formations_table',78);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (79,'2025_09_25_030521_add_fields_to_libraries_table',79);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (80,'2025_09_26_163214_add_status_to_libraries_table',80);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (81,'2025_09_26_163608_add_recipients_to_libraries_table',81);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (82,'2025_09_26_165940_rename_category_id_in_libraries_table',82);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (83,'2025_09_29_011700_add_niveau_dans_formation_to_pre_registrations_table',83);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (84,'2025_09_29_050000_add_more_fields_to_pre_registrations_table',84);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (85,'2025_10_02_000001_rename_address_to_quartier_in_students_table',85);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (86,'2025_10_02_000002_add_extra_fields_to_students_table',86);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (87,'2025_10_02_132900_add_user_id_to_students_table',87);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (88,'2025_10_04_142753_add_deactivation_reason_to_students_table',88);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (89,'2025_10_07_125737_fix_library_category_foreign_key_and_add_pdf_column',89);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (90,'2025_10_07_141133_add_downloads_count_to_libraries_table',90);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (91,'2025_10_08_000001_add_phone_to_admins_table',91);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (92,'2025_10_08_000002_update_admin_roles_enum',92);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (93,'2025_10_10_163052_add_module_to_categories_table',93);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (94,'2025_10_15_115343_add_is_featured_to_library_table',94);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (95,'2025_10_24_124439_add_cover_image_to_libraries_table',95);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (96,'2025_10_24_134621_add_external_link_to_libraries_table',96);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (97,'2025_10_24_135502_make_path_nullable_in_libraries_table',97);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (98,'2025_11_30_185352_add_notification_preferences_to_admins_table',98);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (99,'2025_12_03_183547_update_webtv_videos_for_vimeo_playlists',99);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (100,'2025_12_03_191730_increase_embed_code_column_size',100);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (101,'2025_12_04_191244_add_category_to_webtv_videos_table',101);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (102,'2025_12_07_130818_add_dates_to_communiques_table',102);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (103,'2025_12_07_131846_add_view_count_to_communiques_table',103);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (104,'2025_12_07_133244_add_target_audience_to_communiques_table',104);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (105,'2025_12_07_200019_add_details_to_accounting_transactions_table',105);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (106,'2025_12_07_225010_make_description_nullable_in_formations_table',106);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (107,'2025_12_07_230403_modify_video_url_in_formation_chapters_table',107);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (108,'2025_12_08_010900_add_expiration_date_to_students_table',108);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (109,'2025_12_09_000002_add_payment_fields_to_pre_registrations',109);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (110,'2025_12_09_095348_add_installment_fields_to_payments_table',110);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (111,'2025_12_09_164631_update_account_creation_token_column_size',111);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (112,'2025_12_10_135638_add_payment_url_to_payments_table',112);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (113,'2025_12_14_000001_add_deadline_to_projects_table',113);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (114,'2025_12_14_213000_add_related_content_to_communiques_table',114);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (115,'2025_12_17_172113_add_design_cm_to_choix_formation_enum',115);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (116,'2025_12_23_000000_add_is_report_to_tp_table',116);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (117,'2026_01_09_020300_seed_admin_task_types_assistant',117);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (118,'2026_01_09_030000_add_salary_visibility_to_admins_table',118);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (119,'2026_01_09_030300_add_commercial_admin_to_pre_registrations_and_payments',119);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (120,'2026_01_09_030400_add_job_profile_to_admin_task_types_table',120);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (121,'2026_01_09_030500_seed_admin_job_profiles',121);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (122,'2026_01_09_030600_update_commercial_commission_rate_to_15_percent',122);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (123,'2026_01_13_000001_add_details_to_programmes_table',123);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (124,'2026_01_13_000002_make_programmes_fichier_pdf_nullable',124);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (125,'2026_01_13_000003_add_student_ids_to_programmes_table',125);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (126,'2026_01_13_000004_add_month_start_to_programmes_table',126);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (127,'2026_01_16_000002_add_download_count_to_activity_reports_table',127);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (128,'2026_02_04_171500_add_date_inscription_souhaitee_to_pre_registrations_table',128);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (129,'2026_03_01_000001_add_eligibility_notified_at_to_pre_registrations_table',129);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (130,'2026_03_02_000001_add_design_graphique_community_manager_to_choix_formation_enum',130);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (131,'2026_03_04_000001_add_manager_to_admin_roles_enum',131);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (132,'2026_03_20_000001_add_image_to_programmes_table',132);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (133,'2026_04_05_101746_add_admin_hidden_to_projects_and_tp_assignments',133);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (134,'2026_06_02_000001_add_discount_amount_to_pre_registrations_table',134);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (135,'2026_06_03_181800_add_unique_identifier_to_jury_members_table',135);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (136,'2026_06_03_183000_add_jury_member_id_to_jury_evaluations_table',136);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (137,'2026_06_04_010000_add_is_visible_to_jury_members_table',137);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (138,'2026_07_11_000001_add_status_to_programmes_table',138);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (139,'2026_08_24_170000_add_images_to_products_table',139);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (140,'2026_08_25_010659_add_store_fields_to_products_table',140);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (141,'2026_08_25_011335_add_view_count_to_products_table',141);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (142,'2026_08_25_012043_add_user_id_and_order_number_to_store_orders_table',142);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (143,'2026_08_25_013620_add_stock_and_old_price_to_products_table',143);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (144,'2026_08_25_014433_add_variants_to_products_table',144);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (145,'2026_08_25_015505_add_pricing_to_store_orders_table',145);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (146,'2026_08_25_020716_add_delivery_and_payment_to_store_orders_table',146);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (147,'2026_08_25_030419_make_variants_nullable_on_products_table',147);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (148,'2026_08_25_031428_make_items_nullable_on_store_orders_table',148);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (149,'2026_08_27_014238_add_student_id_to_promo_codes_table',149);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (150,'2026_08_27_014246_add_student_id_to_store_orders_table',150);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (151,'2026_08_27_015638_make_code_nullable_in_promo_codes_table',151);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (152,'2026_08_27_024010_add_delivery_zones_to_products_table',152);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (153,'2026_08_27_024020_add_delivery_location_to_store_orders_table',153);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (154,'2026_09_04_000004_add_user_id_to_attendances_table',154);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (155,'2026_09_04_000005_add_recorded_at_to_attendances_table',155);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (156,'2026_09_04_000008_add_check_in_at_to_attendances_table',156);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (157,'2026_09_06_150002_add_manual_counts_to_certification_eligibilities_table',157);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (158,'2026_09_14_120228_add_duration_seconds_to_meeting_clicks_table',158);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (159,'2026_09_14_142355_add_check_out_at_to_attendances_table',159);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (160,'2026_09_24_000001_add_appointment_groups_and_private_slots',160);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (161,'2025_01_14_020000_add_missing_profile_fields_to_students',161);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (162,'2026_08_25_025012_add_old_price_to_products_table',162);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (163,'2026_08_25_025812_add_stock_to_products_table',162);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (164,'2026_09_04_000006_update_seances_enums_and_add_fields',162);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (165,'2026_09_06_150001_create_certification_eligibility_histories_table',162);

SET FOREIGN_KEY_CHECKS=0;
INSERT INTO `admin_job_profiles` VALUES (1,'assistant','Assistant',25000,0,1,'2026-11-09 02:34:25','2026-11-09 02:34:25');
INSERT INTO `admin_job_profiles` VALUES (2,'photographer','Photographe',30000,0,1,'2026-11-09 02:34:25','2026-11-09 02:34:25');
INSERT INTO `admin_job_profiles` VALUES (3,'designer_graphic','Designer Graphic',40000,0,1,'2026-11-09 02:34:25','2026-11-09 02:34:25');
INSERT INTO `admin_job_profiles` VALUES (4,'video_editor','Monteur Video',40000,0,1,'2026-11-09 02:34:25','2026-11-09 02:34:25');
INSERT INTO `admin_job_profiles` VALUES (5,'community_manager','Community Manager',30000,0,1,'2026-11-09 02:34:25','2026-11-09 02:34:25');
INSERT INTO `admin_job_profiles` VALUES (6,'commercial','Commercial',25000,1500,1,'2026-11-09 02:34:25','2026-11-09 02:34:25');
INSERT INTO `admin_job_profiles` VALUES (7,'support_tech','Support Tech',25000,0,1,'2026-11-09 02:34:25','2026-11-09 02:34:25');
INSERT INTO `admin_task_types` VALUES (1,'assistant_manage_projects','Gérer les projets (valider/rejeter/supprimer) sous 24h','assistant',NULL,NULL,'monthly',1,10,NULL,0,1,'2026-11-09 02:34:24','2026-11-09 02:34:24');
INSERT INTO `admin_task_types` VALUES (2,'assistant_payment_reminders_second_month','Relancer ceux qui doivent solder (à partir du 2e mois)','assistant',NULL,NULL,'monthly',4,10,NULL,0,1,'2026-11-09 02:34:24','2026-11-09 02:34:24');
INSERT INTO `admin_task_types` VALUES (3,'assistant_publish_actualite','Publier une actualité (au moins 1/mois)','assistant',NULL,NULL,'monthly',1,10,NULL,0,1,'2026-11-09 02:34:24','2026-11-09 02:34:24');
INSERT INTO `admin_task_types` VALUES (4,'assistant_publish_communique','Publier un communiqué (au moins 1 toutes les 2 semaines)','assistant',NULL,NULL,'monthly',2,10,NULL,0,1,'2026-11-09 02:34:24','2026-11-09 02:34:24');
INSERT INTO `admin_task_types` VALUES (5,'assistant_remind_end_of_training_report','Relancer les étudiants pour le rapport de fin de formation (dernier mois)','assistant',NULL,NULL,'monthly',1,10,NULL,0,1,'2026-11-09 02:34:24','2026-11-09 02:34:24');
INSERT INTO `admin_task_types` VALUES (6,'assistant_remind_profile_completion','Relancer pour renseigner tous les profils (toutes les 2 semaines)','assistant',NULL,NULL,'monthly',2,10,NULL,0,1,'2026-11-09 02:34:24','2026-11-09 02:34:24');
INSERT INTO `partnerships` VALUES (1,'onp','Partenaire à','ONP','(Ministère du Plan et du Développement)',NULL,1,'2026-11-09 02:34:11','2026-11-09 02:34:11');
SET FOREIGN_KEY_CHECKS=1;
