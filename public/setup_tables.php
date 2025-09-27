<?php
// Script pour créer les tables nécessaires dans v4_evc
try {
    // Connexion à v4_evc
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=v4_evc;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🔧 Configuration des tables dans v4_evc<br><br>";
    
    // Créer la table utilisateurs
    $sql_users = "
    CREATE TABLE IF NOT EXISTS utilisateurs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        prenom VARCHAR(50) NOT NULL,
        nom VARCHAR(50) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        telephone VARCHAR(20),
        pays VARCHAR(50),
        ville VARCHAR(100),
        niveau ENUM('debutant', 'intermediaire', 'perfectionnement'),
        formation_souhaitee ENUM('design_graphique', 'community_management', 'intelligence_artificielle', 'gestion_informatique'),
        mot_de_passe VARCHAR(255) NOT NULL,
        photo_profil VARCHAR(255),
        date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
        statut ENUM('actif', 'inactif', 'suspendu') DEFAULT 'actif',
        accepte_conditions BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql_users);
    echo "✅ Table utilisateurs créée<br>";
    
    // Créer la table activites_utilisateur
    $sql_activites = "
    CREATE TABLE IF NOT EXISTS activites_utilisateur (
        id INT AUTO_INCREMENT PRIMARY KEY,
        utilisateur_id INT NOT NULL,
        type_activite VARCHAR(50) NOT NULL,
        description TEXT,
        date_activite DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
    )";
    
    $pdo->exec($sql_activites);
    echo "✅ Table activites_utilisateur créée<br>";
    
    // Créer la table statistiques_utilisateur
    $sql_stats = "
    CREATE TABLE IF NOT EXISTS statistiques_utilisateur (
        id INT AUTO_INCREMENT PRIMARY KEY,
        utilisateur_id INT NOT NULL,
        tp_realises INT DEFAULT 0,
        projets_realises INT DEFAULT 0,
        heures_formation INT DEFAULT 0,
        badges_obtenus INT DEFAULT 0,
        documents_cvtheque INT DEFAULT 0,
        notes_moyenne DECIMAL(3,2) DEFAULT 0.00,
        progression_globale INT DEFAULT 0,
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
    )";
    
    $pdo->exec($sql_stats);
    echo "✅ Table statistiques_utilisateur créée<br>";
    
    // Créer la table sessions pour Laravel
    $sql_sessions = "
    CREATE TABLE IF NOT EXISTS sessions (
        id VARCHAR(255) NOT NULL,
        user_id BIGINT UNSIGNED NULL,
        ip_address VARCHAR(45) NULL,
        user_agent TEXT NULL,
        payload LONGTEXT NOT NULL,
        last_activity INT NOT NULL,
        PRIMARY KEY (id),
        INDEX sessions_user_id_index (user_id),
        INDEX sessions_last_activity_index (last_activity)
    )";
    
    $pdo->exec($sql_sessions);
    echo "✅ Table sessions créée<br>";
    
    // Vérifier les tables créées
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<br>📋 Tables créées dans v4_evc:<br>";
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
        $count = $stmt->fetchColumn();
        echo "- $table: $count enregistrements<br>";
    }
    
    echo "<br>🎉 Configuration terminée ! La base v4_evc est prête pour l'inscription.<br>";
    
} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "<br>";
}
?>
