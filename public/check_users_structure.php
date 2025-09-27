<?php
// Script pour vérifier la structure de la table users
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=v4_evc;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Structure de la table users :</h2>";
    
    // Récupérer la structure de la table
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($column['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Default']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Extra']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h2>Exemple d'enregistrement :</h2>";
    
    // Récupérer un exemple d'enregistrement
    $stmt = $pdo->query("SELECT * FROM users LIMIT 1");
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Colonne</th><th>Valeur</th></tr>";
        
        foreach ($user as $key => $value) {
            echo "<tr>";
            echo "<td><strong>" . htmlspecialchars($key) . "</strong></td>";
            echo "<td>" . htmlspecialchars($value) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Aucun utilisateur trouvé dans la table.</p>";
    }
    
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
