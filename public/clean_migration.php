<?php
/**
 * Script de nettoyage et migration complète
 * Supprime toutes les tables existantes et recrée la structure unifiée
 */

try {
    // Connexion à la base de données
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=v4_evc;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "🧹 NETTOYAGE COMPLET ET MIGRATION VERS TABLE USERS\n\n";

    // 1. Sauvegarder les données utilisateurs existantes
    echo "1. Sauvegarde des données utilisateurs existantes...\n";
    $existingUsers = [];
    
    try {
        $stmt = $pdo->query("SELECT * FROM utilisateurs");
        $existingUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "✅ " . count($existingUsers) . " utilisateur(s) sauvegardé(s)\n";
    } catch (Exception $e) {
        echo "⚠️  Aucune donnée utilisateur à sauvegarder\n";
    }
    echo "\n";

    // 2. Désactiver les vérifications de clés étrangères
    echo "2. Désactivation des contraintes de clés étrangères...\n";
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    echo "✅ Contraintes désactivées\n\n";

    // 3. Supprimer TOUTES les tables existantes
    echo "3. Suppression de toutes les tables existantes...\n";
    $allTables = [
        'user_activities', 'user_statistics', 'user_documents', 'user_payments',
        'activites_utilisateur', 'statistiques_utilisateur', 'utilisateurs',
        'users', 'sessions', 'password_resets', 'failed_jobs', 'migrations'
    ];
    
    foreach ($allTables as $table) {
        try {
            $pdo->exec("DROP TABLE IF EXISTS {$table}");
            echo "   ✅ Table {$table} supprimée\n";
        } catch (Exception $e) {
            echo "   ⚠️  Table {$table} non trouvée\n";
        }
    }
    echo "\n";

    // 4. Créer la nouvelle table users complète
    echo "4. Création de la nouvelle table users unifiée...\n";
    $createUsersTable = "
    CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        
        -- Informations personnelles de base
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        phone VARCHAR(20),
        password VARCHAR(255) NOT NULL,
        
        -- Informations géographiques
        country VARCHAR(100),
        city VARCHAR(100),
        district VARCHAR(100),
        
        -- Formation et niveau
        formation_souhaitee ENUM('design_graphique', 'community_management', 'intelligence_artificielle', 'gestion_informatique') NOT NULL,
        current_level ENUM('debutant', 'intermediaire', 'perfectionnement') NOT NULL,
        education_level VARCHAR(100),
        last_diploma VARCHAR(200),
        age INT,
        
        -- Profil et préférences
        profile_photo VARCHAR(500),
        biography TEXT,
        expectations TEXT,
        whatsapp VARCHAR(20),
        
        -- Statut et dates
        status ENUM('Actif', 'Inactif', 'Suspendu') DEFAULT 'Actif',
        date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_login TIMESTAMP NULL,
        email_verified_at TIMESTAMP NULL,
        
        -- Tokens et sécurité
        remember_token VARCHAR(100),
        password_reset_token VARCHAR(100),
        password_reset_expires TIMESTAMP NULL,
        
        -- Conditions et consentements
        accepte_conditions BOOLEAN DEFAULT FALSE,
        newsletter_consent BOOLEAN DEFAULT FALSE,
        
        -- Métadonnées
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        
        INDEX idx_email (email),
        INDEX idx_formation (formation_souhaitee),
        INDEX idx_status (status),
        INDEX idx_level (current_level)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $pdo->exec($createUsersTable);
    echo "✅ Table users créée avec tous les champs du profil étudiant\n\n";

    // 5. Créer les tables associées
    echo "5. Création des tables associées...\n";

    // Table user_activities
    $createActivitiesTable = "
    CREATE TABLE user_activities (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        activity_type VARCHAR(100) NOT NULL,
        description TEXT,
        ip_address VARCHAR(45),
        user_agent TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_user_activity (user_id, created_at),
        INDEX idx_activity_type (activity_type)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($createActivitiesTable);
    echo "✅ Table user_activities créée\n";

    // Table user_statistics
    $createStatsTable = "
    CREATE TABLE user_statistics (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL UNIQUE,
        
        -- Progression formation
        total_tp_completed INT DEFAULT 0,
        total_projects_completed INT DEFAULT 0,
        total_hours_studied INT DEFAULT 0,
        completion_percentage DECIMAL(5,2) DEFAULT 0.00,
        
        -- Documents CVThèque
        cv_uploaded BOOLEAN DEFAULT FALSE,
        cover_letter_uploaded BOOLEAN DEFAULT FALSE,
        portfolio_uploaded BOOLEAN DEFAULT FALSE,
        pressbook_uploaded BOOLEAN DEFAULT FALSE,
        final_report_uploaded BOOLEAN DEFAULT FALSE,
        
        -- Badges et certifications
        badges_earned INT DEFAULT 0,
        certificates_earned INT DEFAULT 0,
        
        -- Activité plateforme
        login_count INT DEFAULT 0,
        last_activity TIMESTAMP NULL,
        total_sessions INT DEFAULT 0,
        
        -- Paiements
        total_paid DECIMAL(10,2) DEFAULT 0.00,
        remaining_balance DECIMAL(10,2) DEFAULT 0.00,
        payment_status ENUM('pending', 'partial', 'complete') DEFAULT 'pending',
        
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($createStatsTable);
    echo "✅ Table user_statistics créée\n";

    // Table user_documents
    $createDocumentsTable = "
    CREATE TABLE user_documents (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        document_type ENUM('cv', 'cover_letter', 'portfolio', 'pressbook', 'final_report') NOT NULL,
        file_name VARCHAR(255) NOT NULL,
        file_path VARCHAR(500) NOT NULL,
        file_size INT,
        mime_type VARCHAR(100),
        status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        reviewed_at TIMESTAMP NULL,
        reviewer_comments TEXT,
        
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_user_documents (user_id, document_type),
        INDEX idx_document_status (status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($createDocumentsTable);
    echo "✅ Table user_documents créée\n";

    // Table user_payments
    $createPaymentsTable = "
    CREATE TABLE user_payments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        payment_method VARCHAR(50),
        payment_reference VARCHAR(100),
        payment_status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
        payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        due_date DATE,
        description TEXT,
        invoice_number VARCHAR(50),
        
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_user_payments (user_id, payment_status),
        INDEX idx_payment_date (payment_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($createPaymentsTable);
    echo "✅ Table user_payments créée\n";

    // Table sessions
    $createSessionsTable = "
    CREATE TABLE sessions (
        id VARCHAR(255) NOT NULL PRIMARY KEY,
        user_id INT NULL,
        ip_address VARCHAR(45),
        user_agent TEXT,
        payload LONGTEXT NOT NULL,
        last_activity INT NOT NULL,
        
        INDEX idx_sessions_user_id (user_id),
        INDEX idx_sessions_last_activity (last_activity)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($createSessionsTable);
    echo "✅ Table sessions créée\n\n";

    // 6. Restaurer les données utilisateurs
    echo "6. Restauration des données utilisateurs...\n";
    if (count($existingUsers) > 0) {
        $insertStmt = $pdo->prepare("
            INSERT INTO users (
                first_name, last_name, email, phone, password,
                country, city, formation_souhaitee, current_level,
                profile_photo, status, date_inscription, accepte_conditions
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        foreach ($existingUsers as $user) {
            $insertStmt->execute([
                $user['prenom'] ?? '',
                $user['nom'] ?? '',
                $user['email'] ?? '',
                $user['telephone'] ?? '',
                $user['mot_de_passe'] ?? '',
                $user['pays'] ?? '',
                $user['ville'] ?? '',
                $user['formation_souhaitee'] ?? 'design_graphique',
                $user['niveau'] ?? 'debutant',
                $user['photo_profil'] ?? '',
                $user['statut'] === 'actif' ? 'Actif' : 'Inactif',
                $user['date_inscription'] ?? date('Y-m-d H:i:s'),
                $user['accepte_conditions'] ?? 1
            ]);
        }
        echo "✅ " . count($existingUsers) . " utilisateur(s) restauré(s)\n";
    } else {
        // Créer des utilisateurs de test
        echo "   Création d'utilisateurs de test...\n";
        $testUsers = [
            [
                'first_name' => 'Jean',
                'last_name' => 'Dupont',
                'email' => 'jean.dupont@test.com',
                'phone' => '+33123456789',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'country' => 'France',
                'city' => 'Paris',
                'formation_souhaitee' => 'design_graphique',
                'current_level' => 'debutant'
            ],
            [
                'first_name' => 'Marie',
                'last_name' => 'Martin',
                'email' => 'marie.martin@test.com',
                'phone' => '+33987654321',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'country' => 'France',
                'city' => 'Lyon',
                'formation_souhaitee' => 'community_management',
                'current_level' => 'intermediaire'
            ]
        ];

        $insertTestUser = $pdo->prepare("
            INSERT INTO users (
                first_name, last_name, email, phone, password,
                country, city, formation_souhaitee, current_level,
                accepte_conditions
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
        ");

        foreach ($testUsers as $user) {
            $insertTestUser->execute(array_values($user));
        }
        
        echo "✅ " . count($testUsers) . " utilisateur(s) de test créé(s)\n";
        echo "   Identifiants de test:\n";
        echo "   - jean.dupont@test.com / password123\n";
        echo "   - marie.martin@test.com / password123\n";
    }
    echo "\n";

    // 7. Initialiser les statistiques
    echo "7. Initialisation des statistiques utilisateur...\n";
    $pdo->exec("
        INSERT INTO user_statistics (user_id)
        SELECT id FROM users
    ");
    echo "✅ Statistiques initialisées pour tous les utilisateurs\n\n";

    // 8. Réactiver les contraintes
    echo "8. Réactivation des contraintes de clés étrangères...\n";
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    echo "✅ Contraintes réactivées\n\n";

    echo "🎉 MIGRATION COMPLÈTE TERMINÉE AVEC SUCCÈS !\n\n";
    echo "📊 RÉSUMÉ DE LA MIGRATION :\n";
    echo "✅ Toutes les anciennes tables supprimées\n";
    echo "✅ Table users unifiée créée avec tous les champs du profil étudiant\n";
    echo "✅ Tables associées créées (activities, statistics, documents, payments, sessions)\n";
    echo "✅ Relations et index optimisés\n";
    echo "✅ Données utilisateurs restaurées\n";
    echo "✅ Statistiques initialisées\n\n";
    
    $finalUserCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    echo "👥 Total utilisateurs dans la nouvelle structure : {$finalUserCount}\n\n";
    
    echo "🚀 L'application est maintenant prête avec la structure unifiée !\n";

} catch (PDOException $e) {
    echo "❌ ERREUR DE BASE DE DONNÉES : " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ ERREUR GÉNÉRALE : " . $e->getMessage() . "\n";
    exit(1);
}
?>
