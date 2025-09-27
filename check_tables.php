<?php
// Script de diagnostic pour vérifier l'existence des tables
try {
    $pdo = new PDO('mysql:host=localhost;dbname=v4_evc', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Diagnostic des Tables</h2>";
    
    // Vérifier table projects
    try {
        $stmt = $pdo->query("DESCRIBE projects");
        echo "<h3>✅ Table 'projects' existe</h3>";
        echo "<table border='1'><tr><th>Colonne</th><th>Type</th><th>Null</th><th>Key</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Null']}</td><td>{$row['Key']}</td></tr>";
        }
        echo "</table>";
    } catch (Exception $e) {
        echo "<h3>❌ Table 'projects' n'existe pas</h3>";
        echo "<p>Erreur: " . $e->getMessage() . "</p>";
    }
    
    // Vérifier table project_images
    try {
        $stmt = $pdo->query("DESCRIBE project_images");
        echo "<h3>✅ Table 'project_images' existe</h3>";
        echo "<table border='1'><tr><th>Colonne</th><th>Type</th><th>Null</th><th>Key</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Null']}</td><td>{$row['Key']}</td></tr>";
        }
        echo "</table>";
    } catch (Exception $e) {
        echo "<h3>❌ Table 'project_images' n'existe pas</h3>";
        echo "<p>Erreur: " . $e->getMessage() . "</p>";
    }
    
    // Lister toutes les tables
    echo "<h3>Tables existantes dans v4_evc:</h3>";
    $stmt = $pdo->query("SHOW TABLES");
    echo "<ul>";
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<h2>❌ Erreur de connexion à la base de données</h2>";
    echo "<p>Erreur: " . $e->getMessage() . "</p>";
    echo "<p><strong>Vérifiez que:</strong></p>";
    echo "<ul>";
    echo "<li>XAMPP est démarré (Apache + MySQL)</li>";
    echo "<li>La base de données 'v4_evc' existe</li>";
    echo "<li>Les paramètres de connexion sont corrects</li>";
    echo "</ul>";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
table { border-collapse: collapse; margin: 10px 0; }
th, td { padding: 8px; text-align: left; }
th { background-color: #f2f2f2; }
h2 { color: #333; }
h3 { color: #666; }
.error { color: red; }
.success { color: green; }
</style>
