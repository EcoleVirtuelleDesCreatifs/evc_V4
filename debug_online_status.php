<?php
require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

// Configuration de la base de données
$capsule = new DB;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'v4_evc',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "🔍 DIAGNOSTIC SYSTÈME STATUT EN LIGNE\n";
echo "=====================================\n\n";

try {
    // Test de connexion
    $users = DB::table('users')->count();
    echo "✅ Connexion DB: OK ($users utilisateurs)\n";
    
    // Vérifier la table sessions
    $sessions = DB::table('sessions')->count();
    echo "📊 Sessions totales: $sessions\n";
    
    // Sessions avec user_id
    $userSessions = DB::table('sessions')->whereNotNull('user_id')->count();
    echo "👤 Sessions avec user_id: $userSessions\n";
    
    // Sessions récentes (2 minutes)
    $recentSessions = DB::table('sessions')
        ->where('last_activity', '>=', time() - 120)
        ->count();
    echo "⏰ Sessions récentes (2min): $recentSessions\n";
    
    // Utilisateurs avec sessions actives
    $activeUsers = DB::table('users as u')
        ->join('sessions as s', 'u.id', '=', 's.user_id')
        ->where('s.last_activity', '>=', time() - 120)
        ->select('u.email', 's.last_activity', DB::raw('FROM_UNIXTIME(s.last_activity) as last_time'))
        ->get();
    
    echo "\n👥 Utilisateurs actuellement en ligne:\n";
    if ($activeUsers->count() > 0) {
        foreach($activeUsers as $user) {
            echo "   - {$user->email} (dernière activité: {$user->last_time})\n";
        }
    } else {
        echo "   Aucun utilisateur en ligne détecté\n";
    }
    
    // Test de la requête du contrôleur
    echo "\n🔍 Test de la requête contrôleur:\n";
    $students = DB::table('users as u')
        ->leftJoin(DB::raw('(SELECT user_id, MAX(created_at) as last_real_login FROM user_activities WHERE activity_type = "login" GROUP BY user_id) as ua'), 'u.id', '=', 'ua.user_id')
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
                        AND s.last_activity >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 2 MINUTE))
                    ) THEN 'online'
                    WHEN u.last_login IS NOT NULL 
                        AND u.last_login >= DATE_SUB(NOW(), INTERVAL 2 MINUTE) THEN 'online'
                    ELSE 'offline'
                END as online_status
            ")
        ])
        ->limit(5)
        ->get();
    
    foreach($students as $student) {
        echo "   - {$student->first_name} {$student->last_name} ({$student->email}): {$student->online_status}\n";
    }
    
    // Vérifier les activités de connexion récentes
    echo "\n📈 Activités de connexion récentes (user_activities):\n";
    $recentLogins = DB::table('user_activities')
        ->where('activity_type', 'login')
        ->where('created_at', '>=', now()->subHours(24))
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    if ($recentLogins->count() > 0) {
        foreach($recentLogins as $login) {
            echo "   - User ID {$login->user_id}: {$login->created_at} ({$login->description})\n";
        }
    } else {
        echo "   Aucune activité de connexion récente\n";
    }
    
} catch(Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n🛠️ RECOMMANDATIONS:\n";
echo "- Vérifier que le middleware TrackOnlineStatus est actif\n";
echo "- Vérifier que les sessions utilisateur sont correctement créées\n";
echo "- Tester une connexion utilisateur pour voir si elle est détectée\n";
