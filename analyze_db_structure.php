<?php
/**
 * Script d'analyse de la structure de base de données
 * Analyse les tables de projets et leur contenu
 */

try {
    // Configuration de la base de données
    $host = 'localhost';
    $dbname = 'v4_evc';
    $username = 'root';
    $password = '';

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== ANALYSE DE LA STRUCTURE DE BASE DE DONNÉES ===\n\n";

    // 1. Lister toutes les tables contenant 'project'
    echo "1. TABLES CONTENANT 'project':\n";
    echo "================================\n";
    $stmt = $pdo->query("SHOW TABLES LIKE '%project%'");
    $projectTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($projectTables)) {
        echo "❌ Aucune table contenant 'project' trouvée\n\n";
        
        // Chercher des tables similaires
        echo "2. RECHERCHE DE TABLES SIMILAIRES:\n";
        echo "==================================\n";
        $stmt = $pdo->query("SHOW TABLES");
        $allTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $similarTables = array_filter($allTables, function($table) {
            return stripos($table, 'design') !== false || 
                   stripos($table, 'tp') !== false || 
                   stripos($table, 'travaux') !== false ||
                   stripos($table, 'work') !== false;
        });
        
        foreach ($similarTables as $table) {
            echo "📋 Table trouvée: $table\n";
        }
    } else {
        foreach ($projectTables as $table) {
            echo "📋 Table trouvée: $table\n";
        }
    }

    echo "\n";

    // 2. Analyser la structure de chaque table de projet
    $tablesToAnalyze = !empty($projectTables) ? $projectTables : $similarTables ?? [];
    
    foreach ($tablesToAnalyze as $table) {
        echo "3. STRUCTURE DE LA TABLE '$table':\n";
        echo str_repeat("=", 50) . "\n";
        
        try {
            // Structure de la table
            $stmt = $pdo->query("DESCRIBE $table");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "Colonnes:\n";
            foreach ($columns as $column) {
                echo sprintf("  %-20s %-15s %s\n", 
                    $column['Field'], 
                    $column['Type'], 
                    $column['Null'] === 'YES' ? 'NULL' : 'NOT NULL'
                );
            }
            
            // Compter les enregistrements
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM $table");
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            echo "\n📊 Nombre d'enregistrements: $count\n";
            
            // Si il y a des données, montrer quelques exemples
            if ($count > 0) {
                echo "\n📝 Échantillon de données (5 premiers):\n";
                $stmt = $pdo->query("SELECT * FROM $table LIMIT 5");
                $samples = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (!empty($samples)) {
                    $firstRow = $samples[0];
                    echo "Colonnes disponibles: " . implode(', ', array_keys($firstRow)) . "\n";
                    
                    foreach ($samples as $i => $row) {
                        echo "Ligne " . ($i + 1) . ":\n";
                        foreach ($row as $key => $value) {
                            if (strlen($value) > 50) {
                                $value = substr($value, 0, 47) . '...';
                            }
                            echo "  $key: $value\n";
                        }
                        echo "\n";
                    }
                }
            }
            
        } catch (Exception $e) {
            echo "❌ Erreur lors de l'analyse de $table: " . $e->getMessage() . "\n";
        }
        
        echo "\n" . str_repeat("-", 50) . "\n\n";
    }

    // 3. Vérifier la table users pour les user_id
    echo "4. VÉRIFICATION DES UTILISATEURS:\n";
    echo "=================================\n";
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
        $userCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        echo "📊 Nombre d'utilisateurs: $userCount\n";
        
        if ($userCount > 0) {
            echo "\n📝 Quelques utilisateurs:\n";
            $stmt = $pdo->query("SELECT id, first_name, last_name, email FROM users LIMIT 5");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($users as $user) {
                echo "  ID: {$user['id']} - {$user['first_name']} {$user['last_name']} ({$user['email']})\n";
            }
        }
    } catch (Exception $e) {
        echo "❌ Erreur lors de l'analyse des utilisateurs: " . $e->getMessage() . "\n";
    }

    // 4. Recherche de relations entre tables
    echo "\n5. RECHERCHE DE RELATIONS:\n";
    echo "==========================\n";
    
    foreach ($tablesToAnalyze as $table) {
        try {
            // Chercher des colonnes user_id
            $stmt = $pdo->query("DESCRIBE $table");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $hasUserId = false;
            foreach ($columns as $column) {
                if (stripos($column['Field'], 'user') !== false) {
                    echo "🔗 Table $table a une colonne: {$column['Field']}\n";
                    $hasUserId = true;
                    
                    // Compter les enregistrements par user_id
                    if ($column['Field'] === 'user_id') {
                        $stmt = $pdo->query("SELECT user_id, COUNT(*) as count FROM $table GROUP BY user_id LIMIT 5");
                        $userCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        if (!empty($userCounts)) {
                            echo "  Répartition par utilisateur:\n";
                            foreach ($userCounts as $userCount) {
                                echo "    User ID {$userCount['user_id']}: {$userCount['count']} enregistrement(s)\n";
                            }
                        }
                    }
                }
            }
            
            if (!$hasUserId) {
                echo "⚠️  Table $table n'a pas de colonne user_id évidente\n";
            }
            
        } catch (Exception $e) {
            echo "❌ Erreur lors de l'analyse des relations pour $table: " . $e->getMessage() . "\n";
        }
    }

} catch (Exception $e) {
    echo "❌ ERREUR DE CONNEXION À LA BASE DE DONNÉES:\n";
    echo $e->getMessage() . "\n";
    echo "\nVérifiez:\n";
    echo "- XAMPP est démarré\n";
    echo "- MySQL est en cours d'exécution\n";
    echo "- La base de données 'v4_evc' existe\n";
    echo "- Les identifiants de connexion sont corrects\n";
}
