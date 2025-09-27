-- Script de création des tables TP pour la base de données v4_evc
-- Date: 2025-07-29

USE v4_evc;

-- Table pour les TP (Travaux Pratiques)
CREATE TABLE IF NOT EXISTS tp (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    link VARCHAR(500),
    status ENUM('en_cours', 'termine', 'valide', 'rejete') DEFAULT 'en_cours',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);

-- Table pour les fichiers associés aux TP
CREATE TABLE IF NOT EXISTS tp_files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tp_id INT NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    stored_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_size INT NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tp_id) REFERENCES tp(id) ON DELETE CASCADE,
    INDEX idx_tp_id (tp_id)
);

-- Insertion de quelques données de test pour démonstration
INSERT INTO tp (user_id, title, description, status) VALUES
(1, 'TP Photoshop - Retouche Photo', 'Création d\'un montage photo professionnel avec techniques avancées de retouche', 'en_cours'),
(1, 'TP Illustrator - Logo Design', 'Conception d\'un logo vectoriel pour une entreprise fictive', 'termine'),
(1, 'TP InDesign - Mise en page', 'Création d\'une brochure commerciale avec mise en page professionnelle', 'valide'),
(1, 'TP After Effects - Animation', 'Réalisation d\'une animation motion design de 30 secondes', 'en_cours');

-- Affichage des tables créées
SHOW TABLES LIKE '%tp%';

-- Vérification de la structure des tables
DESCRIBE tp;
DESCRIBE tp_files;

SELECT 'Tables TP créées avec succès dans la base v4_evc!' as message;
