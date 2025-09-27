<?php
/**
 * Script simple pour vérifier la structure de la table tp_files
 */

try {
    $pdo = new PDO("mysql:host=localhost;dbname=v4_evc;charset=utf8mb4", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Structure de la table tp_files:\n";
    echo "==============================\n";
    
    $columns = $pdo->query("DESCRIBE tp_files")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        echo $column['Field'] . " (" . $column['Type'] . ")\n";
    }
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
?>
