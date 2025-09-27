<?php
// Script de diagnostic pour vérifier les statuts des TP
try {
    $pdo = new PDO('mysql:host=localhost;dbname=v4_evc', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Diagnostic des Statuts TP</h2>";
    
    // Vérifier si la table tp existe
    try {
        $stmt = $pdo->query("DESCRIBE tp");
        echo "<h3>✅ Table 'tp' existe</h3>";
        
        // Afficher la structure de la colonne status
        echo "<h4>Structure de la colonne 'status':</h4>";
        echo "<table border='1'><tr><th>Colonne</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row['Field'] === 'status') {
                echo "<tr style='background-color: #ffffcc;'>";
                echo "<td><strong>{$row['Field']}</strong></td>";
                echo "<td><strong>{$row['Type']}</strong></td>";
                echo "<td>{$row['Null']}</td>";
                echo "<td>{$row['Key']}</td>";
                echo "<td>{$row['Default']}</td>";
                echo "</tr>";
            } else {
                echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Null']}</td><td>{$row['Key']}</td><td>{$row['Default']}</td></tr>";
            }
        }
        echo "</table>";
        
    } catch (Exception $e) {
        echo "<h3>❌ Table 'tp' n'existe pas</h3>";
        echo "<p>Erreur: " . $e->getMessage() . "</p>";
        exit;
    }
    
    // Compter les TP par statut
    echo "<h3>Répartition des TP par statut:</h3>";
    try {
        $stmt = $pdo->query("
            SELECT 
                status,
                COUNT(*) as count,
                ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM tp)), 1) as percentage
            FROM tp 
            GROUP BY status 
            ORDER BY count DESC
        ");
        
        $totalTP = 0;
        echo "<table border='1'>";
        echo "<tr><th>Statut</th><th>Nombre</th><th>Pourcentage</th></tr>";
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $totalTP += $row['count'];
            $statusColor = '';
            switch($row['status']) {
                case 'en_cours': $statusColor = 'background-color: #fff3cd;'; break;
                case 'termine': $statusColor = 'background-color: #d1ecf1;'; break;
                case 'valide': $statusColor = 'background-color: #d4edda;'; break;
                case 'rejete': $statusColor = 'background-color: #f8d7da;'; break;
                default: $statusColor = 'background-color: #f8f9fa;'; break;
            }
            
            echo "<tr style='{$statusColor}'>";
            echo "<td><strong>{$row['status']}</strong></td>";
            echo "<td>{$row['count']}</td>";
            echo "<td>{$row['percentage']}%</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<p><strong>Total TP: {$totalTP}</strong></p>";
        
    } catch (Exception $e) {
        echo "<p>❌ Erreur lors du comptage: " . $e->getMessage() . "</p>";
    }
    
    // Afficher quelques exemples de TP
    echo "<h3>Exemples de TP (5 derniers):</h3>";
    try {
        $stmt = $pdo->query("
            SELECT id, user_id, title, status, created_at 
            FROM tp 
            ORDER BY created_at DESC 
            LIMIT 5
        ");
        
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>User ID</th><th>Titre</th><th>Statut</th><th>Créé le</th></tr>";
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['user_id']}</td>";
            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
            echo "<td><strong>{$row['status']}</strong></td>";
            echo "<td>{$row['created_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
    } catch (Exception $e) {
        echo "<p>❌ Erreur lors de l'affichage des exemples: " . $e->getMessage() . "</p>";
    }
    
    // Vérifier les statuts possibles selon la définition ENUM
    echo "<h3>Analyse de la définition ENUM:</h3>";
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM tp LIKE 'status'");
        $column = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($column) {
            echo "<p><strong>Type de colonne:</strong> {$column['Type']}</p>";
            
            // Extraire les valeurs ENUM
            if (preg_match("/^enum\((.+)\)$/i", $column['Type'], $matches)) {
                $enumValues = str_getcsv($matches[1], ',', "'");
                echo "<p><strong>Valeurs ENUM autorisées:</strong></p>";
                echo "<ul>";
                foreach ($enumValues as $value) {
                    echo "<li><code>{$value}</code></li>";
                }
                echo "</ul>";
            }
        }
        
    } catch (Exception $e) {
        echo "<p>❌ Erreur lors de l'analyse ENUM: " . $e->getMessage() . "</p>";
    }
    
    // Recommandations
    echo "<h3>🔧 Recommandations:</h3>";
    echo "<div style='background-color: #e7f3ff; padding: 15px; border-left: 4px solid #2196F3;'>";
    echo "<h4>Statuts recommandés pour les statistiques:</h4>";
    echo "<ul>";
    echo "<li><strong>'en_cours'</strong> → TP En Validation (en cours de réalisation)</li>";
    echo "<li><strong>'valide'</strong> → TP Validés (approuvés par formateur)</li>";
    echo "<li><strong>'termine'</strong> → TP Terminés (soumis pour validation)</li>";
    echo "<li><strong>'rejete'</strong> → TP Rejetés (refusés)</li>";
    echo "</ul>";
    echo "<p><strong>Total TP Réalisés</strong> = Tous les statuts confondus</p>";
    echo "</div>";
    
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
table { border-collapse: collapse; margin: 10px 0; width: 100%; }
th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
th { background-color: #f2f2f2; font-weight: bold; }
h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
h3 { color: #666; margin-top: 30px; }
h4 { color: #888; }
code { background-color: #f8f9fa; padding: 2px 4px; border-radius: 3px; }
.error { color: red; }
.success { color: green; }
</style>
