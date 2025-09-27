<?php
/**
 * Script de création des tables TP pour la base de données v4_evc
 * Date: 2025-07-29
 */

try {
    // Connexion à la base de données
    $pdo = new PDO(
        'mysql:host=127.0.0.1;port=3306;dbname=v4_evc;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "✅ Connexion à la base de données v4_evc réussie!\n\n";
    
    // Table pour les TP (Travaux Pratiques)
    $createTPTable = "
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $pdo->exec($createTPTable);
    echo "✅ Table 'tp' créée avec succès!\n";
    
    // Table pour les fichiers associés aux TP
    $createTPFilesTable = "
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $pdo->exec($createTPFilesTable);
    echo "✅ Table 'tp_files' créée avec succès!\n\n";
    
    // Vérifier si des données de test existent déjà
    $stmt = $pdo->query("SELECT COUNT(*) FROM tp");
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
        // Insertion de quelques données de test pour démonstration
        $insertTestData = "
            INSERT INTO tp (user_id, title, description, status) VALUES
            (1, 'TP Photoshop - Retouche Photo', 'Création d\'un montage photo professionnel avec techniques avancées de retouche', 'en_cours'),
            (1, 'TP Illustrator - Logo Design', 'Conception d\'un logo vectoriel pour une entreprise fictive', 'termine'),
            (1, 'TP InDesign - Mise en page', 'Création d\'une brochure commerciale avec mise en page professionnelle', 'valide'),
            (1, 'TP After Effects - Animation', 'Réalisation d\'une animation motion design de 30 secondes', 'en_cours')
        ";
        
        $pdo->exec($insertTestData);
        echo "✅ Données de test insérées avec succès!\n\n";
    } else {
        echo "ℹ️  Des données TP existent déjà ($count enregistrements)\n\n";
    }
    
    // Affichage des tables créées
    echo "📋 Tables TP dans la base v4_evc:\n";
    $stmt = $pdo->query("SHOW TABLES LIKE '%tp%'");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "   - " . $row[0] . "\n";
    }
    
    echo "\n📊 Statistiques des tables:\n";
    
    // Statistiques table tp
    $stmt = $pdo->query("SELECT COUNT(*) FROM tp");
    $tpCount = $stmt->fetchColumn();
    echo "   - Table 'tp': $tpCount enregistrements\n";
    
    // Statistiques table tp_files
    $stmt = $pdo->query("SELECT COUNT(*) FROM tp_files");
    $filesCount = $stmt->fetchColumn();
    echo "   - Table 'tp_files': $filesCount enregistrements\n";
    
    echo "\n🎉 Tables TP créées avec succès dans la base v4_evc!\n";
    echo "🔗 Vous pouvez maintenant accéder au menu TP sans erreur.\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur lors de la création des tables: " . $e->getMessage() . "\n";
    exit(1);
}
?>
