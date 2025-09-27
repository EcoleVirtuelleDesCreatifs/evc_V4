<?php
// Script pour migrer la base de données V4_EVC vers v4_evc (minuscules)
try {
    // Connexion sans spécifier de base de données
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🔄 Migration de la base de données V4_EVC vers v4_evc<br><br>";
    
    // Vérifier si V4_EVC existe
    $stmt = $pdo->query("SHOW DATABASES LIKE 'V4_EVC'");
    $v4_evc_exists = $stmt->fetch();
    
    // Vérifier si v4_evc existe déjà
    $stmt = $pdo->query("SHOW DATABASES LIKE 'v4_evc'");
    $v4_evc_lower_exists = $stmt->fetch();
    
    if ($v4_evc_exists) {
        echo "✅ Base de données V4_EVC trouvée<br>";
        
        if ($v4_evc_lower_exists) {
            echo "⚠️  Base de données v4_evc existe déjà, suppression...<br>";
            $pdo->exec("DROP DATABASE v4_evc");
            echo "✅ Ancienne base v4_evc supprimée<br>";
        }
        
        // Créer la nouvelle base de données en minuscules
        echo "📝 Création de la base de données v4_evc...<br>";
        $pdo->exec("CREATE DATABASE v4_evc CHARACTER SET utf8 COLLATE utf8_general_ci");
        echo "✅ Base de données v4_evc créée<br>";
        
        // Se connecter à V4_EVC pour lister les tables
        $pdo_source = new PDO('mysql:host=127.0.0.1;port=3306;dbname=V4_EVC;charset=utf8', 'root', '');
        $stmt = $pdo_source->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "📋 Tables à migrer: " . count($tables) . "<br>";
        foreach ($tables as $table) {
            echo "- " . $table . "<br>";
        }
        
        // Se connecter à la nouvelle base
        $pdo_dest = new PDO('mysql:host=127.0.0.1;port=3306;dbname=v4_evc;charset=utf8', 'root', '');
        
        // Migrer chaque table
        echo "<br>🔄 Migration des tables...<br>";
        foreach ($tables as $table) {
            // Obtenir la structure de la table
            $stmt = $pdo_source->query("SHOW CREATE TABLE `$table`");
            $create_table = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Créer la table dans la nouvelle base
            $pdo_dest->exec($create_table['Create Table']);
            echo "✅ Structure de $table créée<br>";
            
            // Copier les données
            $stmt = $pdo_source->query("SELECT COUNT(*) FROM `$table`");
            $count = $stmt->fetchColumn();
            
            if ($count > 0) {
                // Obtenir toutes les données
                $stmt = $pdo_source->query("SELECT * FROM `$table`");
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (!empty($data)) {
                    // Préparer l'insertion
                    $columns = array_keys($data[0]);
                    $placeholders = ':' . implode(', :', $columns);
                    $columns_str = '`' . implode('`, `', $columns) . '`';
                    
                    $insert_sql = "INSERT INTO `$table` ($columns_str) VALUES ($placeholders)";
                    $stmt_insert = $pdo_dest->prepare($insert_sql);
                    
                    // Insérer chaque ligne
                    foreach ($data as $row) {
                        $stmt_insert->execute($row);
                    }
                    
                    echo "✅ $count enregistrements migrés pour $table<br>";
                } else {
                    echo "ℹ️  Table $table vide<br>";
                }
            } else {
                echo "ℹ️  Table $table vide<br>";
            }
        }
        
        echo "<br>🎉 Migration terminée avec succès !<br>";
        echo "📊 Vérification des données migrées:<br>";
        
        // Vérifier les données migrées
        foreach ($tables as $table) {
            $stmt = $pdo_dest->query("SELECT COUNT(*) FROM `$table`");
            $count = $stmt->fetchColumn();
            echo "- $table: $count enregistrements<br>";
        }
        
        echo "<br>⚠️  Vous pouvez maintenant supprimer l'ancienne base V4_EVC si tout fonctionne correctement.<br>";
        echo "💡 Pour supprimer l'ancienne base, ajoutez ?delete_old=1 à l'URL<br>";
        
        // Option pour supprimer l'ancienne base
        if (isset($_GET['delete_old']) && $_GET['delete_old'] == '1') {
            echo "<br>🗑️  Suppression de l'ancienne base V4_EVC...<br>";
            $pdo->exec("DROP DATABASE V4_EVC");
            echo "✅ Ancienne base V4_EVC supprimée<br>";
        }
        
    } else {
        echo "❌ Base de données V4_EVC non trouvée<br>";
        
        // Créer directement v4_evc avec les tables nécessaires
        echo "📝 Création directe de la base v4_evc...<br>";
        
        if ($v4_evc_lower_exists) {
            echo "ℹ️  Base v4_evc existe déjà<br>";
        } else {
            $pdo->exec("CREATE DATABASE v4_evc CHARACTER SET utf8 COLLATE utf8_general_ci");
            echo "✅ Base de données v4_evc créée<br>";
        }
        
        // Se connecter à v4_evc et créer les tables essentielles
        $pdo_evc = new PDO('mysql:host=127.0.0.1;port=3306;dbname=v4_evc;charset=utf8', 'root', '');
        
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
        
        $pdo_evc->exec($sql_users);
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
        
        $pdo_evc->exec($sql_activites);
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
        
        $pdo_evc->exec($sql_stats);
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
        
        $pdo_evc->exec($sql_sessions);
        echo "✅ Table sessions créée<br>";
    }
    
    echo "<br>🎊 Configuration terminée ! La base de données v4_evc est prête à l'emploi.<br>";
    
} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "<br>";
}
?>
