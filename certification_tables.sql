-- ============================================
-- SYSTÈME ÉVALUATION CERTIFICATION - SQL COMPLET
-- Exécuter dans phpMyAdmin : onglet SQL
-- ============================================

-- Table 1 : Certifications
CREATE TABLE certifications (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  description TEXT NULL,
  formation VARCHAR(255) NULL,
  duration_minutes INT NOT NULL DEFAULT 60,
  passing_score DECIMAL(5,2) NOT NULL DEFAULT 50.00,
  total_points DECIMAL(8,2) NOT NULL DEFAULT 100.00,
  is_active TINYINT(1) NOT NULL DEFAULT 0,
  shuffle_questions TINYINT(1) NOT NULL DEFAULT 0,
  instructions TEXT NULL,
  status ENUM('draft','published','scheduled') NOT NULL DEFAULT 'draft',
  scheduled_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 2 : Questions
CREATE TABLE certification_questions (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  certification_id BIGINT UNSIGNED NOT NULL,
  type ENUM('qcm','redaction') NOT NULL DEFAULT 'qcm',
  question_text TEXT NOT NULL,
  media_url VARCHAR(255) NULL,
  points DECIMAL(5,2) NOT NULL DEFAULT 1.00,
  order_index INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_cq_cert FOREIGN KEY (certification_id) REFERENCES certifications(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 3 : Options QCM
CREATE TABLE certification_options (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  question_id BIGINT UNSIGNED NOT NULL,
  option_text TEXT NOT NULL,
  is_correct TINYINT(1) NOT NULL DEFAULT 0,
  order_index INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_co_question FOREIGN KEY (question_id) REFERENCES certification_questions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 4 : Tentatives etudiants
CREATE TABLE certification_attempts (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  certification_id BIGINT UNSIGNED NOT NULL,
  student_id BIGINT UNSIGNED NOT NULL,
  started_at TIMESTAMP NULL,
  finished_at TIMESTAMP NULL,
  submitted_at TIMESTAMP NULL,
  score DECIMAL(8,2) NULL,
  score_percentage DECIMAL(5,2) NULL,
  status ENUM('not_started','in_progress','submitted','graded') NOT NULL DEFAULT 'not_started',
  is_auto_submitted TINYINT(1) NOT NULL DEFAULT 0,
  passed TINYINT(1) NULL,
  admin_feedback TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_cert_student (certification_id, student_id),
  CONSTRAINT fk_ca_cert FOREIGN KEY (certification_id) REFERENCES certifications(id) ON DELETE CASCADE,
  CONSTRAINT fk_ca_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 5 : Reponses etudiants
CREATE TABLE certification_answers (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  attempt_id BIGINT UNSIGNED NOT NULL,
  question_id BIGINT UNSIGNED NOT NULL,
  selected_option_id BIGINT UNSIGNED NULL,
  answer_text TEXT NULL,
  is_correct TINYINT(1) NULL,
  score DECIMAL(5,2) NULL,
  admin_comment TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_attempt_question (attempt_id, question_id),
  CONSTRAINT fk_ans_attempt FOREIGN KEY (attempt_id) REFERENCES certification_attempts(id) ON DELETE CASCADE,
  CONSTRAINT fk_ans_question FOREIGN KEY (question_id) REFERENCES certification_questions(id) ON DELETE CASCADE,
  CONSTRAINT fk_ans_option FOREIGN KEY (selected_option_id) REFERENCES certification_options(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 6 : Assignation certification-etudiant (pivot)
CREATE TABLE certification_student (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  certification_id BIGINT UNSIGNED NOT NULL,
  student_id BIGINT UNSIGNED NOT NULL,
  notified_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_cert_stud (certification_id, student_id),
  CONSTRAINT fk_cs_cert FOREIGN KEY (certification_id) REFERENCES certifications(id) ON DELETE CASCADE,
  CONSTRAINT fk_cs_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
