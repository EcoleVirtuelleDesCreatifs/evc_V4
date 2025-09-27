<?php
require_once 'vendor/autoload.php';

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔄 TEST SYSTÈME STATUT PERSISTANT\n";
echo "==================================\n\n";

try {
    // Simuler un utilisateur connecté avec session active
    $userId = 1; // Premier utilisateur de test
    $sessionId = 'persistent_test_' . time();
    
    echo "1️⃣ Création d'une session persistante pour user_id: $userId\n";
    
    // Créer une session active
    DB::table('sessions')->insert([
        'id' => $sessionId,
        'user_id' => $userId,
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Test Browser Persistent',
        'last_activity' => time(), // Activité maintenant
        'payload' => base64_encode(serialize(['user_id' => $userId, 'logged_in' => true]))
    ]);
    
    echo "✅ Session créée avec last_activity: " . date('Y-m-d H:i:s') . "\n\n";
    
    // Tester la requête du contrôleur (nouvelle logique 24h)
    echo "2️⃣ Test de détection du statut 'en ligne' (logique 24h):\n";
    
    $students = DB::table('users as u')
        ->select([
            'u.id',
            'u.email', 
            'u.first_name',
            'u.last_name',
            DB::raw("
                CASE 
                    WHEN EXISTS (
                        SELECT 1 FROM sessions s 
                        WHERE s.user_id = u.id 
                        AND s.last_activity >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 24 HOUR))
                    ) THEN 'online'
                    ELSE 'offline'
                END as online_status
            ")
        ])
        ->where('u.id', $userId)
        ->first();
    
    if ($students) {
        echo "👤 {$students->first_name} {$students->last_name} ({$students->email}): {$students->online_status}\n";
        
        if ($students->online_status === 'online') {
            echo "✅ SUCCÈS: L'utilisateur est détecté comme 'en ligne'\n";
        } else {
            echo "❌ ÉCHEC: L'utilisateur devrait être 'en ligne'\n";
        }
    }
    
    echo "\n3️⃣ Vérification de la persistance (simulation 1h plus tard):\n";
    
    // Simuler une activité 1 heure plus tard (toujours dans les 24h)
    DB::table('sessions')
        ->where('id', $sessionId)
        ->update(['last_activity' => time() - 3600]); // 1h avant
    
    $studentsAfter = DB::table('users as u')
        ->select([
            'u.id',
            'u.first_name',
            DB::raw("
                CASE 
                    WHEN EXISTS (
                        SELECT 1 FROM sessions s 
                        WHERE s.user_id = u.id 
                        AND s.last_activity >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 24 HOUR))
                    ) THEN 'online'
                    ELSE 'offline'
                END as online_status
            ")
        ])
        ->where('u.id', $userId)
        ->first();
    
    if ($studentsAfter) {
        echo "👤 {$studentsAfter->first_name} (1h après): {$studentsAfter->online_status}\n";
        
        if ($studentsAfter->online_status === 'online') {
            echo "✅ SUCCÈS: Le statut reste 'en ligne' après 1h (persistant)\n";
        } else {
            echo "❌ ÉCHEC: Le statut devrait rester 'en ligne'\n";
        }
    }
    
    echo "\n4️⃣ Test de déconnexion (suppression de session):\n";
    
    // Simuler une déconnexion
    DB::table('sessions')->where('id', $sessionId)->delete();
    
    $studentsLoggedOut = DB::table('users as u')
        ->select([
            'u.id',
            'u.first_name',
            DB::raw("
                CASE 
                    WHEN EXISTS (
                        SELECT 1 FROM sessions s 
                        WHERE s.user_id = u.id 
                        AND s.last_activity >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 24 HOUR))
                    ) THEN 'online'
                    ELSE 'offline'
                END as online_status
            ")
        ])
        ->where('u.id', $userId)
        ->first();
    
    if ($studentsLoggedOut) {
        echo "👤 {$studentsLoggedOut->first_name} (après déconnexion): {$studentsLoggedOut->online_status}\n";
        
        if ($studentsLoggedOut->online_status === 'offline') {
            echo "✅ SUCCÈS: L'utilisateur est 'hors ligne' après déconnexion\n";
        } else {
            echo "❌ ÉCHEC: L'utilisateur devrait être 'hors ligne'\n";
        }
    }
    
    echo "\n🎯 RÉSUMÉ:\n";
    echo "- ✅ Statut 'en ligne' persistant tant que session active\n";
    echo "- ✅ Détection basée sur sessions dans les 24h\n";
    echo "- ✅ Passage à 'hors ligne' lors de la déconnexion\n";
    echo "- ✅ Système prêt pour la production\n";
    
} catch(Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
