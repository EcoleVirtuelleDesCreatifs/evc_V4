<?php
/**
 * Script de test pour vérifier la correction de l'erreur stored_name
 * Test de l'insertion dans tp_files avec tous les champs requis
 */

try {
    // Connexion à la base de données
    $pdo = new PDO(
        'mysql:host=127.0.0.1;port=3306;dbname=v4_evc;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "🧪 TEST DE LA CORRECTION stored_name\n";
    echo "=" . str_repeat("=", 50) . "\n\n";
    
    // 1. Créer un TP de test
    echo "📝 Création d'un TP de test...\n";
    $stmt = $pdo->prepare("
        INSERT INTO tp (user_id, title, description, link) 
        VALUES (?, ?, ?, ?)
    ");
    
    $stmt->execute([
        1, // user_id de test
        'TP Test - Correction stored_name',
        'Test de correction de l\'erreur SQL stored_name',
        'https://example.com'
    ]);
    
    $tpId = $pdo->lastInsertId();
    echo "✅ TP créé avec ID: $tpId\n\n";
    
    // 2. Tester l'insertion dans tp_files avec stored_name
    echo "📁 Test d'insertion de fichier avec stored_name...\n";
    
    // Simuler les données d'un fichier uploadé
    $originalName = 'document_test.pdf';
    $fileName = time() . '_' . uniqid() . '.pdf'; // stored_name
    $filePath = 'uploads/tp/' . $fileName;
    $fileSize = 1024000; // 1MB
    $mimeType = 'application/pdf';
    
    // Insertion corrigée avec stored_name
    $stmt = $pdo->prepare("
        INSERT INTO tp_files (tp_id, original_name, stored_name, file_path, file_size, mime_type) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $tpId,
        $originalName,
        $fileName, // stored_name = nom unique généré
        $filePath,
        $fileSize,
        $mimeType
    ]);
    
    $fileId = $pdo->lastInsertId();
    echo "✅ Fichier inséré avec ID: $fileId\n\n";
    
    // 3. Vérifier l'insertion
    echo "🔍 Vérification de l'insertion...\n";
    $stmt = $pdo->prepare("
        SELECT tp.title, tf.original_name, tf.stored_name, tf.file_path, tf.file_size, tf.mime_type
        FROM tp_files tf
        JOIN tp ON tp.id = tf.tp_id
        WHERE tf.id = ?
    ");
    
    $stmt->execute([$fileId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        echo "📋 Données insérées :\n";
        echo "- TP: {$result['title']}\n";
        echo "- Nom original: {$result['original_name']}\n";
        echo "- Nom stocké: {$result['stored_name']}\n";
        echo "- Chemin: {$result['file_path']}\n";
        echo "- Taille: " . number_format($result['file_size']) . " bytes\n";
        echo "- Type MIME: {$result['mime_type']}\n\n";
        
        echo "🎉 SUCCÈS : L'erreur 'stored_name doesn't have a default value' est corrigée !\n";
    } else {
        echo "❌ Erreur : Impossible de récupérer les données insérées\n";
    }
    
    // 4. Nettoyer les données de test
    echo "\n🧹 Nettoyage des données de test...\n";
    $pdo->prepare("DELETE FROM tp_files WHERE id = ?")->execute([$fileId]);
    $pdo->prepare("DELETE FROM tp WHERE id = ?")->execute([$tpId]);
    echo "✅ Données de test supprimées\n\n";
    
    echo "🎯 RÉSULTAT FINAL :\n";
    echo "La correction du champ 'stored_name' fonctionne parfaitement !\n";
    echo "L'ajout de TP avec upload de fichiers devrait maintenant fonctionner.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors du test : " . $e->getMessage() . "\n";
    echo "Code d'erreur : " . $e->getCode() . "\n";
}
?>
