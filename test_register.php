<?php
// Test simple pour diagnostiquer le problème d'inscription
echo "<h2>🔍 Diagnostic du problème d'inscription</h2>";

// 1. Tester la connexion à la base de données
try {
    $pdo = new PDO(
        'mysql:host=127.0.0.1;dbname=V4_EVC;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "<p>✅ Connexion à la base de données V4_EVC réussie</p>";
    
    // Vérifier la table sessions
    $stmt = $pdo->query("SHOW TABLES LIKE 'sessions'");
    if ($stmt->rowCount() > 0) {
        echo "<p>✅ Table 'sessions' existe</p>";
    } else {
        echo "<p>❌ Table 'sessions' manquante</p>";
    }
    
    // Vérifier la table users
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "<p>✅ Table 'users' existe</p>";
    } else {
        echo "<p>❌ Table 'users' manquante</p>";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Erreur de connexion à la base : " . $e->getMessage() . "</p>";
}

// 2. Tester l'accès aux routes
echo "<h3>🔗 Test des routes</h3>";

$routes = [
    'GET /auth/register' => 'http://localhost:8000/auth/register',
    'GET /auth/login' => 'http://localhost:8000/auth/login'
];

foreach ($routes as $route => $url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        echo "<p>✅ $route : OK ($httpCode)</p>";
    } else {
        echo "<p>❌ $route : Erreur ($httpCode)</p>";
    }
}

// 3. Tester la génération de token CSRF
echo "<h3>🔐 Test du token CSRF</h3>";
session_start();
if (function_exists('csrf_token')) {
    echo "<p>✅ Fonction csrf_token() disponible</p>";
} else {
    echo "<p>❌ Fonction csrf_token() non disponible</p>";
}

// 4. Vérifier les permissions de dossier
echo "<h3>📁 Test des permissions</h3>";
$uploadDir = __DIR__ . '/public/uploads/photos';
if (!file_exists($uploadDir)) {
    if (mkdir($uploadDir, 0777, true)) {
        echo "<p>✅ Dossier uploads/photos créé</p>";
    } else {
        echo "<p>❌ Impossible de créer le dossier uploads/photos</p>";
    }
} else {
    echo "<p>✅ Dossier uploads/photos existe</p>";
}

if (is_writable($uploadDir)) {
    echo "<p>✅ Dossier uploads/photos accessible en écriture</p>";
} else {
    echo "<p>❌ Dossier uploads/photos non accessible en écriture</p>";
}

echo "<hr>";
echo "<p><strong>Instructions :</strong></p>";
echo "<ol>";
echo "<li>Vérifiez que tous les tests ci-dessus sont ✅</li>";
echo "<li>Si des erreurs persistent, essayez de remplir le formulaire à nouveau</li>";
echo "<li>Ouvrez la console du navigateur (F12) pour voir les erreurs JavaScript</li>";
echo "<li>Supprimez ce fichier après diagnostic</li>";
echo "</ol>";

echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 40px; background: #f8f9fa; }";
echo "h2, h3 { color: #333; }";
echo "p { margin: 10px 0; }";
echo "</style>";
?>
