-- Table pour les informations de profil CVThèque
CREATE TABLE cvtheque_profiles (
    id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT(20) UNSIGNED NOT NULL,
    
    -- Informations professionnelles
    professional_title VARCHAR(255),
    professional_summary TEXT,
    years_experience INT DEFAULT 0,
    current_position VARCHAR(255),
    current_company VARCHAR(255),
    
    -- Compétences techniques
    software_skills TEXT, -- JSON: ["photoshop", "illustrator", "indesign", etc.]
    technical_skills TEXT, -- JSON: ["web_design", "print_design", "branding", etc.]
    languages TEXT, -- JSON: [{"language": "français", "level": "natif"}, etc.]
    
    -- Informations de contact professionnelles
    professional_email VARCHAR(255),
    professional_phone VARCHAR(20),
    professional_website VARCHAR(255),
    linkedin_profile VARCHAR(255),
    behance_profile VARCHAR(255),
    dribbble_profile VARCHAR(255),
    instagram_profile VARCHAR(255),
    
    -- Préférences de carrière
    job_type ENUM('CDI', 'CDD', 'Freelance', 'Stage', 'Alternance', 'Tout') DEFAULT 'Tout',
    salary_expectation VARCHAR(100),
    availability_date DATE,
    remote_work BOOLEAN DEFAULT FALSE,
    willing_to_relocate BOOLEAN DEFAULT FALSE,
    preferred_locations TEXT, -- JSON: ["Paris", "Lyon", "Remote", etc.]
    
    -- Informations académiques complémentaires
    certifications TEXT, -- JSON: [{"name": "Adobe Certified", "date": "2024", etc.}]
    formations_completed TEXT, -- JSON: formations EVC et autres
    
    -- Statut de visibilité
    profile_visible BOOLEAN DEFAULT TRUE,
    profile_public BOOLEAN DEFAULT FALSE,
    allow_contact BOOLEAN DEFAULT TRUE,
    
    -- Métadonnées
    profile_completion_score INT DEFAULT 0,
    last_updated_by_user TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_profile (user_id)
);

-- Index pour améliorer les performances
CREATE INDEX idx_cvtheque_user_id ON cvtheque_profiles(user_id);
CREATE INDEX idx_cvtheque_visible ON cvtheque_profiles(profile_visible);
CREATE INDEX idx_cvtheque_public ON cvtheque_profiles(profile_public);
