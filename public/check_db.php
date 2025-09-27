<?php
// Script pour vérifier et créer la base de données si nécessaire
try {
    // Connexion sans spécifier de base de données
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connexion MySQL réussie<br>";
    
    // Lister les bases de données
    $stmt = $pdo->query("SHOW DATABASES");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Bases de données disponibles:<br>";
    foreach ($databases as $db) {
        echo "- " . $db . "<br>";
    }
    
    // Vérifier si v4_evc existe
    if (in_array('v4_evc', $databases)) {
        echo "<br>Base de données v4_evc trouvée<br>";
        
        // Se connecter à v4_evc et lister les tables
        $pdo_evc = new PDO('mysql:host=127.0.0.1;port=3306;dbname=v4_evc;charset=utf8', 'root', '');
        $stmt = $pdo_evc->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "Tables dans v4_evc:<br>";
        foreach ($tables as $table) {
            echo "- " . $table . "<br>";
        }
        
        // Vérifier si la table utilisateurs existe
        if (in_array('utilisateurs', $tables)) {
            echo "<br>✅ Table utilisateurs trouvée<br>";
            
            // Compter les utilisateurs
            $stmt = $pdo_evc->query("SELECT COUNT(*) FROM utilisateurs");
            $count = $stmt->fetchColumn();
            echo "Nombre d'utilisateurs: " . $count . "<br>";
        } else {
            echo "<br>❌ Table utilisateurs non trouvée<br>";
            echo "Création de la table utilisateurs...<br>";
            
            // Créer la table utilisateurs
            $sql = "
            CREATE TABLE utilisateurs (
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
            
            $pdo_evc->exec($sql);
            echo "✅ Table utilisateurs créée<br>";
            
            // Créer la table activites_utilisateur
            $sql_activites = "
            CREATE TABLE activites_utilisateur (
                id INT AUTO_INCREMENT PRIMARY KEY,
                utilisateur_id INT NOT NULL,
                type_activite VARCHAR(50) NOT NULL,
                description TEXT,
                date_activite DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
            )";
            
            $pdo_evc->exec($sql_activites);
            echo "✅ Table activites_utilisateur créée<br>";
            
            // Créer la table statistiques_utilisateur
            $sql_stats = "
            CREATE TABLE statistiques_utilisateur (
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
            
            $pdo_evc->exec($sql_stats);
            echo "✅ Table statistiques_utilisateur créée<br>";
        }
        
    } else {
        echo "<br>❌ Base de données v4_evc non trouvée<br>";
        echo "Création de la base de données v4_evc...<br>";
        
        // Créer la base de données
        $pdo->exec("CREATE DATABASE v4_evc CHARACTER SET utf8 COLLATE utf8_general_ci");
        echo "✅ Base de données v4_evc créée<br>";
        
        // Maintenant créer les tables (répéter le code ci-dessus)
        $pdo_evc = new PDO('mysql:host=127.0.0.1;port=3306;dbname=v4_evc;charset=utf8', 'root', '');
        
        // Créer la table utilisateurs
        $sql = "
        CREATE TABLE utilisateurs (
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
        
        $pdo_evc->exec($sql);
        echo "✅ Table utilisateurs créée<br>";
        
        // Créer la table activites_utilisateur
        $sql_activites = "
        CREATE TABLE activites_utilisateur (
            id INT AUTO_INCREMENT PRIMARY KEY,
            utilisateur_id INT NOT NULL,
            type_activite VARCHAR(50) NOT NULL,
            description TEXT,
            date_activite DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
        )";
        
        $pdo_evc->exec($sql_activites);
        echo "✅ Table activites_utilisateur créée<br>";
        
        // Créer la table statistiques_utilisateur
        $sql_stats = "
        CREATE TABLE statistiques_utilisateur (
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
        
        $pdo_evc->exec($sql_stats);
        echo "✅ Table statistiques_utilisateur créée<br>";
    }
    
    echo "<br>🎉 Vérification et configuration de la base de données terminée !<br>";
    
} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "<br>";
}
?>
