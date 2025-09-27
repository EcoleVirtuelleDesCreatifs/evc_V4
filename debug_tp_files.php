<?php
/**
 * Script de diagnostic pour la table tp_files
 * Diagnostique l'erreur : Field 'stored_name' doesn't have a default value
 */

try {
    // Connexion à la base de données
    $pdo = new PDO(
        'mysql:host=127.0.0.1;port=3306;dbname=v4_evc;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "🔍 DIAGNOSTIC DE LA TABLE tp_files\n";
    echo "=" . str_repeat("=", 50) . "\n\n";
    
    // 1. Vérifier la structure de la table tp_files
    echo "📋 Structure de la table tp_files :\n";
    echo "-" . str_repeat("-", 40) . "\n";
    
    $stmt = $pdo->query("DESCRIBE tp_files");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        $nullable = $column['Null'] === 'YES' ? 'NULL' : 'NOT NULL';
        $default = $column['Default'] !== null ? "DEFAULT '{$column['Default']}'" : 'NO DEFAULT';
        
        echo sprintf("%-15s %-15s %-10s %s\n", 
            $column['Field'], 
            $column['Type'], 
            $nullable, 
            $default
        );
    }
    
    echo "\n";
    
    // 2. Identifier le problème avec stored_name
    $storedNameColumn = array_filter($columns, function($col) {
        return $col['Field'] === 'stored_name';
    });
    
    if (!empty($storedNameColumn)) {
        $storedNameColumn = array_values($storedNameColumn)[0];
        echo "🎯 PROBLÈME IDENTIFIÉ :\n";
        echo "-" . str_repeat("-", 30) . "\n";
        echo "Le champ 'stored_name' :\n";
        echo "- Type: {$storedNameColumn['Type']}\n";
        echo "- Null: {$storedNameColumn['Null']}\n";
        echo "- Default: " . ($storedNameColumn['Default'] ?? 'AUCUNE') . "\n\n";
        
        if ($storedNameColumn['Null'] === 'NO' && $storedNameColumn['Default'] === null) {
            echo "❌ ERREUR : Le champ 'stored_name' est NOT NULL sans valeur par défaut\n";
            echo "💡 SOLUTION : Ajouter ce champ dans l'insertion ou modifier la table\n\n";
        }
    }
    
    // 3. Vérifier le code d'insertion actuel dans DashboardController
    echo "🔧 CORRECTION NÉCESSAIRE :\n";
    echo "-" . str_repeat("-", 30) . "\n";
    echo "Dans DashboardController::storeTP(), l'insertion actuelle :\n";
    echo "INSERT INTO tp_files (tp_id, original_name, file_path, file_size, mime_type)\n";
    echo "VALUES (?, ?, ?, ?, ?)\n\n";
    
    echo "Doit être corrigée pour inclure 'stored_name' :\n";
    echo "INSERT INTO tp_files (tp_id, original_name, stored_name, file_path, file_size, mime_type)\n";
    echo "VALUES (?, ?, ?, ?, ?, ?)\n\n";
    
    // 4. Proposer une solution
    echo "✅ SOLUTION RECOMMANDÉE :\n";
    echo "-" . str_repeat("-", 30) . "\n";
    echo "1. Modifier l'insertion pour inclure 'stored_name' (nom du fichier stocké)\n";
    echo "2. 'stored_name' = nom unique généré (ex: time() . '_' . uniqid() . '.ext')\n";
    echo "3. 'original_name' = nom original du fichier uploadé\n";
    echo "4. 'file_path' = chemin complet vers le fichier\n\n";
    
    echo "🎯 Le fichier DashboardController.php doit être mis à jour.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur de connexion : " . $e->getMessage() . "\n";
}
?>
