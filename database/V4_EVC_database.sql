-- ====================================================================
-- BASE DE DONNÉES V4_EVC - PLATEFORME DE FORMATION INFOGRAPHIE
-- ====================================================================
-- Création de la base de données et de toutes les tables nécessaires
-- pour le fonctionnement complet du profil étudiant EVC 2024
-- ====================================================================

-- Création de la base de données
CREATE DATABASE IF NOT EXISTS V4_EVC CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE V4_EVC;

-- ====================================================================
-- TABLE UTILISATEURS (ÉTUDIANTS)
-- ====================================================================
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    whatsapp VARCHAR(20),
    age INT,
    country VARCHAR(100),
    city VARCHAR(100),
    district VARCHAR(100),
    profile_photo VARCHAR(255),
    education_level ENUM('BAC', 'BAC+2', 'BAC+3', 'BAC+5', 'Autre') DEFAULT 'BAC',
    last_diploma VARCHAR(255),
    biography TEXT,
    expectations TEXT,
    current_level ENUM('Débutant', 'Intermédiaire', 'Avancé') DEFAULT 'Débutant',
    status ENUM('Actif', 'Suspendu', 'Diplômé', 'Abandonné') DEFAULT 'Actif',
    enrollment_date DATE NOT NULL,
    graduation_date DATE NULL,
    profile_completion_percentage INT DEFAULT 0,
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_email (email),
    INDEX idx_status (status),
    INDEX idx_enrollment_date (enrollment_date)
);

-- ====================================================================
-- TABLE FORMATIONS/MODULES
-- ====================================================================
CREATE TABLE formations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category ENUM('Photoshop', 'Illustrator', 'InDesign', 'Strategy Business') NOT NULL,
    duration_weeks INT DEFAULT 4,
    total_lessons INT DEFAULT 14,
    difficulty_level ENUM('Débutant', 'Intermédiaire', 'Avancé') DEFAULT 'Débutant',
    is_active BOOLEAN DEFAULT TRUE,
    order_index INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_category (category),
    INDEX idx_active (is_active)
);

-- ====================================================================
-- TABLE PROGRESSION DES FORMATIONS
-- ====================================================================
CREATE TABLE user_formation_progress (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    formation_id INT NOT NULL,
    lessons_completed INT DEFAULT 0,
    total_lessons INT DEFAULT 14,
    progress_percentage DECIMAL(5,2) DEFAULT 0.00,
    start_date DATE,
    completion_date DATE NULL,
    status ENUM('Non commencé', 'En cours', 'Terminé', 'Abandonné') DEFAULT 'Non commencé',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (formation_id) REFERENCES formations(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_formation (user_id, formation_id),
    INDEX idx_user_progress (user_id, status)
);

-- ====================================================================
-- TABLE TRAVAUX PRATIQUES (TP)
-- ====================================================================
CREATE TABLE tp_exercises (
    id INT PRIMARY KEY AUTO_INCREMENT,
    formation_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    instructions TEXT,
    difficulty ENUM('Facile', 'Moyen', 'Difficile') DEFAULT 'Moyen',
    max_points INT DEFAULT 20,
    time_limit_hours INT DEFAULT 48,
    is_active BOOLEAN DEFAULT TRUE,
    order_index INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (formation_id) REFERENCES formations(id) ON DELETE CASCADE,
    INDEX idx_formation_tp (formation_id),
    INDEX idx_active_tp (is_active)
);

-- ====================================================================
-- TABLE SOUMISSIONS DE TP
-- ====================================================================
CREATE TABLE tp_submissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    tp_id INT NOT NULL,
    submission_file VARCHAR(255),
    submission_notes TEXT,
    grade DECIMAL(4,2) NULL,
    feedback TEXT,
    status ENUM('Soumis', 'En correction', 'Corrigé', 'À refaire') DEFAULT 'Soumis',
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    graded_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tp_id) REFERENCES tp_exercises(id) ON DELETE CASCADE,
    INDEX idx_user_submissions (user_id),
    INDEX idx_tp_submissions (tp_id),
    INDEX idx_status_submissions (status)
);

-- ====================================================================
-- TABLE PROJETS
-- ====================================================================
CREATE TABLE projects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    formation_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    requirements TEXT,
    max_points INT DEFAULT 100,
    deadline DATE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (formation_id) REFERENCES formations(id) ON DELETE CASCADE,
    INDEX idx_formation_projects (formation_id),
    INDEX idx_deadline (deadline)
);

-- ====================================================================
-- TABLE SOUMISSIONS DE PROJETS
-- ====================================================================
CREATE TABLE project_submissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    project_id INT NOT NULL,
    title VARCHAR(255),
    description TEXT,
    files JSON, -- Stockage des fichiers multiples en JSON
    grade DECIMAL(4,2) NULL,
    feedback TEXT,
    status ENUM('En cours', 'Soumis', 'En évaluation', 'Validé', 'Rejeté') DEFAULT 'En cours',
    submitted_at TIMESTAMP NULL,
    graded_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    INDEX idx_user_project_submissions (user_id),
    INDEX idx_project_submissions (project_id),
    INDEX idx_status_project_submissions (status)
);

-- ====================================================================
-- TABLE BADGES/RÉCOMPENSES
-- ====================================================================
CREATE TABLE badges (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    icon VARCHAR(100), -- Classe FontAwesome
    color VARCHAR(7) DEFAULT '#FFD700', -- Code couleur hex
    criteria JSON, -- Critères d'obtention en JSON
    points_required INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_active_badges (is_active)
);

-- ====================================================================
-- TABLE BADGES OBTENUS PAR LES UTILISATEURS
-- ====================================================================
CREATE TABLE user_badges (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    badge_id INT NOT NULL,
    earned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (badge_id) REFERENCES badges(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_badge (user_id, badge_id),
    INDEX idx_user_badges (user_id),
    INDEX idx_earned_date (earned_at)
);

-- ====================================================================
-- TABLE PAIEMENTS
-- ====================================================================
CREATE TABLE payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('Carte bancaire', 'Virement', 'PayPal', 'Espèces') NOT NULL,
    transaction_id VARCHAR(255),
    description TEXT,
    status ENUM('En attente', 'Payé', 'Échoué', 'Remboursé') DEFAULT 'En attente',
    due_date DATE,
    paid_at TIMESTAMP NULL,
    invoice_number VARCHAR(50),
    invoice_file VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_payments (user_id),
    INDEX idx_status_payments (status),
    INDEX idx_due_date (due_date)
);

-- ====================================================================
-- TABLE CVTHÈQUE - DOCUMENTS
-- ====================================================================
CREATE TABLE cvtheque_documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    document_type ENUM('CV', 'Lettre de motivation', 'Réalisations visuelles', 'Pressbook', 'Rapport de fin de formation') NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    stored_filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_size INT NOT NULL, -- en bytes
    mime_type VARCHAR(100),
    status ENUM('En cours de validation', 'Validé', 'Rejeté') DEFAULT 'En cours de validation',
    validation_notes TEXT,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    validated_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_documents (user_id),
    INDEX idx_document_type (document_type),
    INDEX idx_status_documents (status)
);

-- ====================================================================
-- TABLE ACTIVITÉS/LOGS
-- ====================================================================
CREATE TABLE user_activities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    activity_type ENUM('Connexion', 'TP complété', 'Projet soumis', 'Paiement effectué', 'Badge obtenu', 'Document uploadé', 'Formation commencée') NOT NULL,
    description TEXT,
    related_id INT NULL, -- ID de l'élément lié (TP, projet, etc.)
    related_type VARCHAR(50) NULL, -- Type de l'élément lié
    metadata JSON, -- Données supplémentaires en JSON
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_activities (user_id),
    INDEX idx_activity_type (activity_type),
    INDEX idx_created_at (created_at)
);

-- ====================================================================
-- TABLE STATISTIQUES UTILISATEUR
-- ====================================================================
CREATE TABLE user_statistics (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total_study_hours INT DEFAULT 0,
    active_days_count INT DEFAULT 0,
    average_grade DECIMAL(4,2) DEFAULT 0.00,
    tp_completed INT DEFAULT 0,
    projects_completed INT DEFAULT 0,
    badges_earned INT DEFAULT 0,
    documents_uploaded INT DEFAULT 0,
    total_payments DECIMAL(10,2) DEFAULT 0.00,
    last_activity_date DATE,
    global_progress_percentage DECIMAL(5,2) DEFAULT 0.00,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_stats (user_id)
);

-- ====================================================================
-- TABLE SESSIONS D'ÉTUDE
-- ====================================================================
CREATE TABLE study_sessions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    formation_id INT,
    start_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    end_time TIMESTAMP NULL,
    duration_minutes INT DEFAULT 0,
    activity_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (formation_id) REFERENCES formations(id) ON DELETE SET NULL,
    INDEX idx_user_sessions (user_id),
    INDEX idx_session_date (start_time)
);

-- ====================================================================
-- TABLE NOTIFICATIONS
-- ====================================================================
CREATE TABLE notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('Info', 'Succès', 'Avertissement', 'Erreur') DEFAULT 'Info',
    is_read BOOLEAN DEFAULT FALSE,
    action_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_notifications (user_id),
    INDEX idx_unread (is_read),
    INDEX idx_created_notifications (created_at)
);

-- ====================================================================
-- INSERTION DES DONNÉES DE BASE
-- ====================================================================

-- Insertion des formations de base
INSERT INTO formations (name, description, category, duration_weeks, total_lessons, difficulty_level, order_index) VALUES
('Adobe Photoshop - Fondamentaux', 'Maîtrise des outils de base de Photoshop pour la retouche photo et la création graphique', 'Photoshop', 4, 14, 'Débutant', 1),
('Adobe Illustrator - Création vectorielle', 'Apprentissage du dessin vectoriel et création de logos avec Illustrator', 'Illustrator', 4, 14, 'Débutant', 2),
('Adobe InDesign - Mise en page', 'Techniques de mise en page professionnelle pour supports print et digital', 'InDesign', 4, 14, 'Intermédiaire', 3),
('Strategy Business - Marketing digital', 'Stratégies marketing et communication pour graphistes entrepreneurs', 'Strategy Business', 3, 12, 'Avancé', 4);

-- Insertion des badges de base
INSERT INTO badges (name, description, icon, color, criteria, points_required) VALUES
('Première connexion', 'Badge obtenu lors de la première connexion à la plateforme', 'fas fa-sign-in-alt', '#28a745', '{"type": "first_login"}', 0),
('Maître Photoshop', 'Badge obtenu après avoir complété 10 TP Photoshop', 'fab fa-adobe', '#FF0000', '{"type": "tp_completed", "formation": "Photoshop", "count": 10}', 200),
('Assidu', 'Badge obtenu après 30 jours consécutifs de connexion', 'fas fa-calendar-check', '#3399ff', '{"type": "consecutive_days", "count": 30}', 300),
('Perfectionniste', 'Badge obtenu avec une moyenne générale supérieure à 18/20', 'fas fa-star', '#FFD700', '{"type": "average_grade", "minimum": 18}', 500),
('Créatif', 'Badge obtenu après avoir soumis 5 projets créatifs', 'fas fa-palette', '#ff6633', '{"type": "projects_submitted", "count": 5}', 250);

-- Insertion d'un utilisateur de test
INSERT INTO users (email, password, first_name, last_name, phone, whatsapp, age, country, city, district, education_level, last_diploma, biography, expectations, current_level, status, enrollment_date, profile_completion_percentage) VALUES
('jean.dupont@evc.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jean', 'Dupont', '+33 6 12 34 56 78', '+33 6 12 34 56 78', 25, 'France', 'Paris', '15ème arrondissement', 'BAC+2', 'BTS Communication Visuelle', 'Passionné de design graphique depuis toujours, je souhaite me perfectionner dans les outils Adobe pour devenir graphiste professionnel.', 'Acquérir une maîtrise complète des logiciels Adobe et développer ma créativité pour travailler en agence ou en freelance.', 'Débutant', 'Actif', '2024-04-15', 85);

-- Insertion des statistiques pour l'utilisateur de test
INSERT INTO user_statistics (user_id, total_study_hours, active_days_count, average_grade, tp_completed, projects_completed, badges_earned, documents_uploaded, total_payments, last_activity_date, global_progress_percentage) VALUES
(1, 245, 156, 16.50, 12, 3, 2, 15, 900.00, CURDATE(), 68.00);

-- Insertion de quelques activités récentes
INSERT INTO user_activities (user_id, activity_type, description, created_at) VALUES
(1, 'TP complété', 'TP Photoshop - Retouche photo complété avec une note de 18/20', NOW()),
(1, 'Connexion', 'Session d\'étude de 2h30', DATE_SUB(NOW(), INTERVAL 3 HOUR)),
(1, 'Projet soumis', 'Projet Illustrator - Création d\'un logo d\'entreprise soumis', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(1, 'Paiement effectué', 'Mensualité 3/4 - 300€ payée par carte bancaire', DATE_SUB(NOW(), INTERVAL 2 DAY)),
(1, 'Badge obtenu', 'Badge "Maître Photoshop" obtenu', DATE_SUB(NOW(), INTERVAL 3 DAY));

-- ====================================================================
-- VUES UTILES POUR LES STATISTIQUES
-- ====================================================================

-- Vue pour le dashboard utilisateur
CREATE VIEW user_dashboard_stats AS
SELECT 
    u.id,
    u.first_name,
    u.last_name,
    u.email,
    u.profile_photo,
    u.current_level,
    u.status,
    u.enrollment_date,
    u.profile_completion_percentage,
    us.total_study_hours,
    us.active_days_count,
    us.average_grade,
    us.tp_completed,
    us.projects_completed,
    us.badges_earned,
    us.documents_uploaded,
    us.total_payments,
    us.global_progress_percentage,
    COUNT(DISTINCT ufp.id) as formations_enrolled,
    COUNT(DISTINCT CASE WHEN ufp.status = 'Terminé' THEN ufp.id END) as formations_completed
FROM users u
LEFT JOIN user_statistics us ON u.id = us.user_id
LEFT JOIN user_formation_progress ufp ON u.id = ufp.user_id
GROUP BY u.id;

-- Vue pour la progression par formation
CREATE VIEW user_formation_detailed_progress AS
SELECT 
    ufp.user_id,
    f.name as formation_name,
    f.category,
    ufp.lessons_completed,
    ufp.total_lessons,
    ufp.progress_percentage,
    ufp.status,
    ufp.start_date,
    ufp.completion_date,
    COUNT(DISTINCT ts.id) as tp_submitted,
    COUNT(DISTINCT CASE WHEN ts.status = 'Corrigé' THEN ts.id END) as tp_graded,
    AVG(ts.grade) as average_tp_grade
FROM user_formation_progress ufp
JOIN formations f ON ufp.formation_id = f.id
LEFT JOIN tp_exercises te ON f.id = te.formation_id
LEFT JOIN tp_submissions ts ON te.id = ts.tp_id AND ts.user_id = ufp.user_id
GROUP BY ufp.id;

-- ====================================================================
-- INDEX SUPPLÉMENTAIRES POUR LES PERFORMANCES
-- ====================================================================

-- Index composites pour les requêtes fréquentes
CREATE INDEX idx_user_formation_status ON user_formation_progress(user_id, status, progress_percentage);
CREATE INDEX idx_tp_user_grade ON tp_submissions(user_id, grade, status);
CREATE INDEX idx_project_user_status ON project_submissions(user_id, status, submitted_at);
CREATE INDEX idx_payment_user_status ON payments(user_id, status, due_date);
CREATE INDEX idx_activity_user_date ON user_activities(user_id, created_at DESC);

-- ====================================================================
-- PROCÉDURES STOCKÉES UTILES
-- ====================================================================

DELIMITER //

-- Procédure pour mettre à jour les statistiques utilisateur
CREATE PROCEDURE UpdateUserStatistics(IN userId INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    INSERT INTO user_statistics (user_id) VALUES (userId) 
    ON DUPLICATE KEY UPDATE user_id = userId;
    
    UPDATE user_statistics us SET
        tp_completed = (SELECT COUNT(*) FROM tp_submissions WHERE user_id = userId AND status = 'Corrigé'),
        projects_completed = (SELECT COUNT(*) FROM project_submissions WHERE user_id = userId AND status = 'Validé'),
        badges_earned = (SELECT COUNT(*) FROM user_badges WHERE user_id = userId),
        documents_uploaded = (SELECT COUNT(*) FROM cvtheque_documents WHERE user_id = userId),
        total_payments = (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE user_id = userId AND status = 'Payé'),
        average_grade = (SELECT COALESCE(AVG(grade), 0) FROM tp_submissions WHERE user_id = userId AND grade IS NOT NULL),
        global_progress_percentage = (SELECT COALESCE(AVG(progress_percentage), 0) FROM user_formation_progress WHERE user_id = userId),
        last_activity_date = (SELECT DATE(MAX(created_at)) FROM user_activities WHERE user_id = userId),
        updated_at = CURRENT_TIMESTAMP
    WHERE user_id = userId;
    
    COMMIT;
END //

-- Procédure pour calculer la progression d'une formation
CREATE PROCEDURE UpdateFormationProgress(IN userId INT, IN formationId INT)
BEGIN
    DECLARE totalLessons INT DEFAULT 14;
    DECLARE completedLessons INT DEFAULT 0;
    DECLARE progressPercent DECIMAL(5,2) DEFAULT 0.00;
    
    -- Calculer les leçons complétées (basé sur les TP corrigés)
    SELECT COUNT(*) INTO completedLessons
    FROM tp_submissions ts
    JOIN tp_exercises te ON ts.tp_id = te.id
    WHERE ts.user_id = userId 
    AND te.formation_id = formationId 
    AND ts.status = 'Corrigé';
    
    -- Calculer le pourcentage
    SET progressPercent = (completedLessons / totalLessons) * 100;
    
    -- Mettre à jour la progression
    INSERT INTO user_formation_progress (user_id, formation_id, lessons_completed, total_lessons, progress_percentage)
    VALUES (userId, formationId, completedLessons, totalLessons, progressPercent)
    ON DUPLICATE KEY UPDATE
        lessons_completed = completedLessons,
        progress_percentage = progressPercent,
        status = CASE 
            WHEN progressPercent >= 100 THEN 'Terminé'
            WHEN progressPercent > 0 THEN 'En cours'
            ELSE 'Non commencé'
        END,
        completion_date = CASE WHEN progressPercent >= 100 THEN CURDATE() ELSE completion_date END,
        updated_at = CURRENT_TIMESTAMP;
END //

DELIMITER ;

-- ====================================================================
-- TRIGGERS POUR AUTOMATISER LES MISES À JOUR
-- ====================================================================

DELIMITER //

-- Trigger pour mettre à jour les statistiques après un TP
CREATE TRIGGER after_tp_submission_update
AFTER UPDATE ON tp_submissions
FOR EACH ROW
BEGIN
    IF NEW.status = 'Corrigé' AND OLD.status != 'Corrigé' THEN
        CALL UpdateUserStatistics(NEW.user_id);
        CALL UpdateFormationProgress(NEW.user_id, (SELECT formation_id FROM tp_exercises WHERE id = NEW.tp_id));
    END IF;
END //

-- Trigger pour mettre à jour les statistiques après un badge
CREATE TRIGGER after_badge_earned
AFTER INSERT ON user_badges
FOR EACH ROW
BEGIN
    CALL UpdateUserStatistics(NEW.user_id);
    
    INSERT INTO user_activities (user_id, activity_type, description, related_id, related_type)
    VALUES (NEW.user_id, 'Badge obtenu', 
           CONCAT('Badge "', (SELECT name FROM badges WHERE id = NEW.badge_id), '" obtenu'),
           NEW.badge_id, 'badge');
END //

-- Trigger pour mettre à jour les statistiques après un paiement
CREATE TRIGGER after_payment_update
AFTER UPDATE ON payments
FOR EACH ROW
BEGIN
    IF NEW.status = 'Payé' AND OLD.status != 'Payé' THEN
        CALL UpdateUserStatistics(NEW.user_id);
        
        INSERT INTO user_activities (user_id, activity_type, description, related_id, related_type)
        VALUES (NEW.user_id, 'Paiement effectué', 
               CONCAT('Paiement de ', NEW.amount, '€ effectué'),
               NEW.id, 'payment');
    END IF;
END //

DELIMITER ;

-- ====================================================================
-- FINALISATION
-- ====================================================================

-- Message de confirmation
SELECT 'Base de données V4_EVC créée avec succès!' as message,
       COUNT(*) as total_tables 
FROM information_schema.tables 
WHERE table_schema = 'V4_EVC';

-- Affichage des tables créées
SELECT table_name as 'Tables créées', 
       table_rows as 'Lignes',
       ROUND(((data_length + index_length) / 1024 / 1024), 2) as 'Taille (MB)'
FROM information_schema.tables 
WHERE table_schema = 'V4_EVC' 
ORDER BY table_name;
