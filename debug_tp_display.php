<?php
// Script de debug complet pour les problèmes d'affichage TP
session_start();

try {
    $pdo = new PDO('mysql:host=localhost;dbname=v4_evc', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>🔍 Debug Complet - Affichage TP</h1>";
    echo "<style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f8f9fa; }
        .card { background: white; padding: 20px; margin: 15px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        .info { color: #007bff; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 12px; text-align: left; border: 1px solid #dee2e6; }
        th { background-color: #e9ecef; font-weight: bold; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
        .fix-btn { background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin: 5px; }
    </style>";
    
    // Test 1: Session Laravel simulée
    echo "<div class='card'>";
    echo "<h2>🔐 Test 1: Session Utilisateur</h2>";
    
    // Simuler différents user_id pour test
    $testUserIds = [1, 2, 3, 4, 5];
    
    echo "<p><strong>Test avec différents user_id :</strong></p>";
    echo "<table><tr><th>User ID</th><th>Projets</th><th>Nom Utilisateur</th><th>Action</th></tr>";
    
    foreach ($testUserIds as $userId) {
        $stmt = $pdo->prepare("
            SELECT COUNT(p.id) as project_count
            FROM projects p 
            WHERE p.user_id = ?
        ");
        $stmt->execute([$userId]);
        $projectCount = $stmt->fetchColumn();
        
        $stmt = $pdo->prepare("
            SELECT u.prenom, u.nom, u.email 
            FROM users u 
            WHERE u.id = ?
        ");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $userName = $user ? $user['prenom'] . ' ' . $user['nom'] : 'Utilisateur non trouvé';
        $email = $user ? $user['email'] : 'N/A';
        
        echo "<tr>";
        echo "<td><strong>{$userId}</strong></td>";
        echo "<td><span class='" . ($projectCount > 0 ? 'success' : 'error') . "'>{$projectCount}</span></td>";
        echo "<td>{$userName}<br><small>{$email}</small></td>";
        echo "<td><a href='?test_user={$userId}' class='fix-btn'>Tester</a></td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
    
    // Test avec un user_id spécifique
    $testUserId = isset($_GET['test_user']) ? (int)$_GET['test_user'] : 1;
    
    // Test 2: Simulation exacte du service TpStatisticsService
    echo "<div class='card'>";
    echo "<h2>📊 Test 2: Service TpStatisticsService (User ID: {$testUserId})</h2>";
    
    // getUserTpStatistics simulation
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(DISTINCT p.id) as total_tp,
            COUNT(DISTINCT CASE WHEN p.status = 'valide' THEN p.id END) as tp_valides,
            COUNT(DISTINCT CASE WHEN p.status = 'en_cours' THEN p.id END) as tp_en_cours,
            COUNT(pi.id) as total_files,
            SUM(CASE WHEN pi.mime_type LIKE 'image/%' THEN 1 ELSE 0 END) as total_images,
            SUM(CASE WHEN pi.mime_type = 'application/pdf' THEN 1 ELSE 0 END) as total_pdf,
            ROUND(COALESCE(SUM(pi.file_size), 0) / 1024 / 1024, 2) as total_size_mb
        FROM projects p
        LEFT JOIN project_images pi ON p.id = pi.project_id
        WHERE p.user_id = ?
    ");
    
    $stmt->execute([$testUserId]);
    $tpStats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h4>Statistiques TP :</h4>";
    echo "<table>";
    echo "<tr><th>Métrique</th><th>Valeur</th></tr>";
    foreach ($tpStats as $key => $value) {
        echo "<tr><td>{$key}</td><td><strong>{$value}</strong></td></tr>";
    }
    echo "</table>";
    
    // getRecentTps simulation
    $stmt = $pdo->prepare("
        SELECT 
            p.id,
            p.title,
            p.status,
            p.created_at,
            COUNT(pi.id) as files_count
        FROM projects p
        LEFT JOIN project_images pi ON p.id = pi.project_id
        WHERE p.user_id = ?
        GROUP BY p.id, p.title, p.status, p.created_at
        ORDER BY p.created_at DESC
        LIMIT 10
    ");
    
    $stmt->execute([$testUserId]);
    $recentTps = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h4>TP Récents (getRecentTps) :</h4>";
    if (count($recentTps) > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Titre</th><th>Statut</th><th>Fichiers</th><th>Date</th></tr>";
        foreach ($recentTps as $tp) {
            echo "<tr>";
            echo "<td>{$tp['id']}</td>";
            echo "<td>" . htmlspecialchars($tp['title']) . "</td>";
            echo "<td><strong>{$tp['status']}</strong></td>";
            echo "<td>{$tp['files_count']}</td>";
            echo "<td>" . date('d/m/Y H:i', strtotime($tp['created_at'])) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='error'>❌ Aucun TP récent trouvé</p>";
    }
    
    // getAllUserProjects simulation
    $stmt = $pdo->prepare("
        SELECT 
            p.id,
            p.title,
            p.description,
            p.category,
            p.thumbnail_image,
            p.link,
            p.tags,
            p.software_used,
            p.status,
            p.created_at,
            p.updated_at,

            COUNT(pi.id) as files_count,
            SUM(CASE WHEN pi.mime_type LIKE 'image/%' THEN 1 ELSE 0 END) as images_count,
            SUM(CASE WHEN pi.mime_type = 'application/pdf' THEN 1 ELSE 0 END) as pdf_count,
            ROUND(COALESCE(SUM(pi.file_size), 0) / 1024 / 1024, 2) as total_size_mb
        FROM projects p
        LEFT JOIN project_images pi ON p.id = pi.project_id
        WHERE p.user_id = ?
        GROUP BY p.id, p.title, p.description, p.category, p.status, p.created_at, p.updated_at, p.thumbnail_image, p.link, p.tags, p.software_used
        ORDER BY p.created_at DESC
    ");
    
    $stmt->execute([$testUserId]);
    $allProjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h4>Tous les Projets (getAllUserProjects) :</h4>";
    if (count($allProjects) > 0) {
        echo "<p class='success'>✅ " . count($allProjects) . " projets trouvés</p>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Titre</th><th>Type</th><th>Statut</th><th>Fichiers</th><th>Date</th></tr>";
        foreach (array_slice($allProjects, 0, 5) as $project) {
            echo "<tr>";
            echo "<td>{$project['id']}</td>";
            echo "<td>" . htmlspecialchars($project['title']) . "</td>";
            echo "<td>{$project['type']}</td>";
            echo "<td><strong>{$project['status']}</strong></td>";
            echo "<td>{$project['files_count']}</td>";
            echo "<td>" . date('d/m/Y', strtotime($project['created_at'])) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        if (count($allProjects) > 5) {
            echo "<p><em>... et " . (count($allProjects) - 5) . " autres projets</em></p>";
        }
    } else {
        echo "<p class='error'>❌ Aucun projet trouvé</p>";
    }
    
    echo "</div>";
    
    // Test 3: Simulation UserProfileService
    echo "<div class='card'>";
    echo "<h2>👤 Test 3: Service UserProfileService</h2>";
    
    $stmt = $pdo->prepare("
        SELECT 
            id,
            prenom as first_name,
            nom as last_name,
            email,
            CONCAT(prenom, ' ', nom) as full_name,
            profile_photo,
            created_at
        FROM users 
        WHERE id = ?
    ");
    
    $stmt->execute([$testUserId]);
    $userProfile = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($userProfile) {
        echo "<p class='success'>✅ Profil utilisateur trouvé</p>";
        echo "<table>";
        foreach ($userProfile as $key => $value) {
            echo "<tr><td><strong>{$key}</strong></td><td>" . htmlspecialchars($value) . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='error'>❌ Profil utilisateur non trouvé</p>";
    }
    
    echo "</div>";
    
    // Test 4: Génération des données pour les vues
    echo "<div class='card'>";
    echo "<h2>🎨 Test 4: Données pour les Vues Laravel</h2>";
    
    // Simulation formatStatisticsForView
    $statistiques = [
        'tp_realises' => $tpStats['total_tp'],
        'tp_a_faire' => max(0, 20 - $tpStats['total_tp']),
        'tp_valides' => $tpStats['tp_valides'],
        'tp_total' => 20,
        'progression_pourcentage' => min(100, ($tpStats['total_tp'] / 20) * 100),
        'total_files' => $tpStats['total_files'],
        'total_images' => $tpStats['total_images'],
        'total_pdf' => $tpStats['total_pdf'],
        'total_size_mb' => $tpStats['total_size_mb']
    ];
    
    echo "<h4>Statistiques formatées pour les vues :</h4>";
    echo "<pre>" . json_encode($statistiques, JSON_PRETTY_PRINT) . "</pre>";
    
    // Simulation des variables de vue
    echo "<h4>Variables passées aux vues :</h4>";
    echo "<ul>";
    echo "<li><strong>\$projects</strong> : " . count($allProjects) . " projets</li>";
    echo "<li><strong>\$tps</strong> : " . count($recentTps) . " TP récents</li>";
    echo "<li><strong>\$statistiques</strong> : Array avec " . count($statistiques) . " métriques</li>";
    echo "<li><strong>\$userProfile</strong> : " . ($userProfile ? 'Objet utilisateur valide' : 'NULL') . "</li>";
    echo "</ul>";
    
    echo "</div>";
    
    // Test 5: Fix automatique
    echo "<div class='card'>";
    echo "<h2>🛠️ Test 5: Solutions et Corrections</h2>";
    
    if (count($allProjects) > 0) {
        echo "<p class='success'>✅ <strong>DONNÉES TROUVÉES !</strong> Il y a " . count($allProjects) . " projets dans la base.</p>";
        echo "<p><strong>Le problème est probablement :</strong></p>";
        echo "<ul>";
        echo "<li>🔐 <strong>Session utilisateur</strong> : Vérifiez que session('user_id') = {$testUserId}</li>";
        echo "<li>🔄 <strong>Cache Laravel</strong> : Videz le cache avec <code>php artisan cache:clear</code></li>";
        echo "<li>📝 <strong>Logs Laravel</strong> : Consultez storage/logs/laravel.log</li>";
        echo "<li>🐛 <strong>Mode Debug</strong> : Activez APP_DEBUG=true dans .env</li>";
        echo "</ul>";
        
        echo "<h4>🎯 Actions à faire :</h4>";
        echo "<ol>";
        echo "<li>Connectez-vous avec l'utilisateur ID {$testUserId}</li>";
        echo "<li>Accédez à <a href='http://localhost/web/evc2024/V4_EVC/public/evc/compte/design-graphique/tp/tous' target='_blank'>/tp/tous</a></li>";
        echo "<li>Vérifiez le debug info en haut de la page</li>";
        echo "<li>Si toujours vide, consultez les logs Laravel</li>";
        echo "</ol>";
        
    } else {
        echo "<p class='error'>❌ <strong>AUCUNE DONNÉE</strong> pour l'utilisateur {$testUserId}</p>";
        echo "<p>Testez avec un autre user_id qui a des projets (voir tableau ci-dessus)</p>";
    }
    
    echo "</div>";
    
    // Test 6: Création de données de test si nécessaire
    if (isset($_GET['create_test']) && count($allProjects) == 0) {
        echo "<div class='card'>";
        echo "<h2>➕ Création de Données de Test</h2>";
        
        try {
            // Créer un projet de test
            $stmt = $pdo->prepare("
                INSERT INTO projects (user_id, title, description, category, type, status, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
            ");
            
            $stmt->execute([
                $testUserId,
                'Projet de Test - Logo Design',
                'Création d\'un logo pour une entreprise fictive',
                'Logo',
                'digital',
                'valide'
            ]);
            
            $projectId = $pdo->lastInsertId();
            
            // Ajouter une image de test
            $stmt = $pdo->prepare("
                INSERT INTO project_images (project_id, file_path, file_name, mime_type, file_size, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $projectId,
                'test/logo-test.jpg',
                'logo-test.jpg',
                'image/jpeg',
                1024000
            ]);
            
            echo "<p class='success'>✅ Projet de test créé avec succès !</p>";
            echo "<p><a href='?test_user={$testUserId}' class='fix-btn'>Recharger le test</a></p>";
            
        } catch (Exception $e) {
            echo "<p class='error'>❌ Erreur création test : " . $e->getMessage() . "</p>";
        }
        
        echo "</div>";
    }
    
    if (count($allProjects) == 0) {
        echo "<div class='card'>";
        echo "<p><a href='?test_user={$testUserId}&create_test=1' class='fix-btn'>Créer des données de test</a></p>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='color: red; padding: 20px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px;'>";
    echo "<h2>❌ Erreur de Connexion</h2>";
    echo "<p><strong>Message:</strong> " . $e->getMessage() . "</p>";
    echo "</div>";
}
?>
