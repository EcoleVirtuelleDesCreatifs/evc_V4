<?php
/**
 * Script de diagnostic pour identifier le problème lors de la création de TP
 */

echo "🔍 DIAGNOSTIC CRÉATION TP\n";
echo "========================\n\n";

try {
    // 1. Test de connexion à la base de données
    echo "1. Test de connexion à la base de données...\n";
    $pdo = new PDO("mysql:host=localhost;dbname=v4_evc;charset=utf8mb4", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion réussie\n\n";

    // 2. Vérifier la structure de la table tp
    echo "2. Structure de la table 'tp':\n";
    $tpColumns = $pdo->query("DESCRIBE tp")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($tpColumns as $column) {
        echo "   - {$column['Field']} ({$column['Type']}) {$column['Null']} {$column['Key']}\n";
    }
    echo "\n";

    // 3. Vérifier la structure de la table tp_files
    echo "3. Structure de la table 'tp_files':\n";
    $tpFilesExists = $pdo->query("SHOW TABLES LIKE 'tp_files'")->fetch();
    if ($tpFilesExists) {
        $tpFilesColumns = $pdo->query("DESCRIBE tp_files")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($tpFilesColumns as $column) {
            echo "   - {$column['Field']} ({$column['Type']}) {$column['Null']} {$column['Key']}\n";
        }
    } else {
        echo "   ❌ Table tp_files n'existe pas!\n";
    }
    echo "\n";

    // 4. Test d'insertion dans la table tp
    echo "4. Test d'insertion dans la table 'tp'...\n";
    $testTpData = [
        'user_id' => 1,
        'title' => 'Test TP Diagnostic',
        'description' => 'Test de diagnostic',
        'url_link' => null,
        'status' => 'pending',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];

    // Construire la requête d'insertion
    $columns = implode(', ', array_keys($testTpData));
    $placeholders = ':' . implode(', :', array_keys($testTpData));
    $sql = "INSERT INTO tp ($columns) VALUES ($placeholders)";
    
    echo "   SQL: $sql\n";
    echo "   Données: " . json_encode($testTpData) . "\n";
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute($testTpData);
    
    if ($result) {
        $testTpId = $pdo->lastInsertId();
        echo "✅ Insertion réussie, ID: $testTpId\n";
        
        // Nettoyer le test
        $pdo->exec("DELETE FROM tp WHERE id = $testTpId");
        echo "✅ Test nettoyé\n";
    } else {
        echo "❌ Échec de l'insertion\n";
    }
    echo "\n";

    // 5. Test d'insertion dans tp_files (si la table existe)
    if ($tpFilesExists) {
        echo "5. Test d'insertion dans la table 'tp_files'...\n";
        $testFileData = [
            'tp_id' => 999, // ID fictif
            'file_name' => 'test.jpg',
            'file_path' => 'tp_files/test.jpg',
            'file_type' => 'image/jpeg',
            'file_size' => 12345,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $fileColumns = implode(', ', array_keys($testFileData));
        $filePlaceholders = ':' . implode(', :', array_keys($testFileData));
        $fileSql = "INSERT INTO tp_files ($fileColumns) VALUES ($filePlaceholders)";
        
        echo "   SQL: $fileSql\n";
        echo "   Données: " . json_encode($testFileData) . "\n";
        
        try {
            $fileStmt = $pdo->prepare($fileSql);
            $fileResult = $fileStmt->execute($testFileData);
            
            if ($fileResult) {
                $testFileId = $pdo->lastInsertId();
                echo "✅ Insertion fichier réussie, ID: $testFileId\n";
                
                // Nettoyer le test
                $pdo->exec("DELETE FROM tp_files WHERE id = $testFileId");
                echo "✅ Test fichier nettoyé\n";
            }
        } catch (Exception $e) {
            echo "❌ Erreur insertion fichier: " . $e->getMessage() . "\n";
            echo "💡 Colonnes disponibles dans tp_files:\n";
            foreach ($tpFilesColumns as $column) {
                echo "     - {$column['Field']}\n";
            }
        }
    }
    echo "\n";

    // 6. Vérifier les permissions du dossier storage
    echo "6. Vérification des permissions de stockage...\n";
    $storagePath = __DIR__ . '/storage/app/public/tp_files';
    if (!is_dir($storagePath)) {
        echo "❌ Dossier $storagePath n'existe pas\n";
        echo "💡 Création du dossier...\n";
        if (mkdir($storagePath, 0755, true)) {
            echo "✅ Dossier créé avec succès\n";
        } else {
            echo "❌ Impossible de créer le dossier\n";
        }
    } else {
        echo "✅ Dossier de stockage existe\n";
        if (is_writable($storagePath)) {
            echo "✅ Dossier accessible en écriture\n";
        } else {
            echo "❌ Dossier non accessible en écriture\n";
        }
    }

    echo "\n🎯 RÉSUMÉ DU DIAGNOSTIC:\n";
    echo "========================\n";
    echo "- Connexion DB: ✅\n";
    echo "- Table tp: ✅\n";
    echo "- Table tp_files: " . ($tpFilesExists ? "✅" : "❌") . "\n";
    echo "- Test insertion tp: ✅\n";
    echo "- Dossier storage: " . (is_dir($storagePath) ? "✅" : "❌") . "\n";

} catch (PDOException $e) {
    echo "❌ Erreur de base de données: " . $e->getMessage() . "\n";
    echo "💡 Vérifiez que XAMPP est démarré et que la base 'v4_evc' existe.\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
?>
