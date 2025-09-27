<?php
/**
 * Script de diagnostic pour les sessions Laravel
 * À exécuter pour vérifier la configuration des sessions
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Configuration de base de données
$host = '127.0.0.1';
$dbname = 'v4_evc';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DIAGNOSTIC DES SESSIONS LARAVEL ===\n\n";
    
    // 1. Vérifier si la table sessions existe
    echo "1. Vérification de la table 'sessions':\n";
    try {
        $stmt = $pdo->query("DESCRIBE sessions");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "✅ Table 'sessions' trouvée avec colonnes: " . implode(', ', $columns) . "\n\n";
    } catch (Exception $e) {
        echo "❌ Table 'sessions' non trouvée: " . $e->getMessage() . "\n";
        echo "💡 Créer la table avec: php artisan session:table && php artisan migrate\n\n";
    }
    
    // 2. Vérifier le contenu de la table sessions
    echo "2. Contenu actuel de la table 'sessions':\n";
    try {
        $stmt = $pdo->query("SELECT id, user_id, ip_address, user_agent, last_activity, FROM_UNIXTIME(last_activity) as last_activity_readable FROM sessions ORDER BY last_activity DESC LIMIT 10");
        $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($sessions)) {
            echo "⚠️  Aucune session active trouvée\n\n";
        } else {
            echo "Sessions actives:\n";
            foreach ($sessions as $session) {
                $isRecent = (time() - $session['last_activity']) < 120; // 2 minutes
                $status = $isRecent ? "🟢 ACTIVE" : "🔴 INACTIVE";
                echo "- ID: {$session['id']}, User: {$session['user_id']}, Last: {$session['last_activity_readable']} $status\n";
            }
            echo "\n";
        }
    } catch (Exception $e) {
        echo "❌ Erreur lecture sessions: " . $e->getMessage() . "\n\n";
    }
    
    // 3. Vérifier les utilisateurs et leur statut calculé
    echo "3. Test du calcul de statut en ligne:\n";
    try {
        $query = "
            SELECT 
                u.id,
                u.first_name,
                u.last_name,
                u.last_login,
                u.updated_at,
                CASE 
                    WHEN EXISTS (
                        SELECT 1 FROM sessions s 
                        WHERE s.user_id = u.id 
                        AND s.last_activity >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 2 MINUTE))
                    ) THEN 'online'
                    WHEN u.last_login IS NOT NULL 
                        AND u.last_login >= DATE_SUB(NOW(), INTERVAL 2 MINUTE) THEN 'online'
                    ELSE 'offline'
                END as calculated_status
            FROM users u 
            ORDER BY u.updated_at DESC 
            LIMIT 5
        ";
        
        $stmt = $pdo->query($query);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($users as $user) {
            $status_icon = $user['calculated_status'] === 'online' ? '🟢' : '🔴';
            echo "- {$user['first_name']} {$user['last_name']}: {$status_icon} {$user['calculated_status']}\n";
            echo "  Last login: {$user['last_login']}, Updated: {$user['updated_at']}\n";
        }
        echo "\n";
        
    } catch (Exception $e) {
        echo "❌ Erreur calcul statut: " . $e->getMessage() . "\n\n";
    }
    
    // 4. Recommandations
    echo "4. Recommandations:\n";
    echo "- Vérifier SESSION_DRIVER=database dans .env\n";
    echo "- Exécuter: php artisan config:cache\n";
    echo "- Vérifier que le middleware TrackOnlineStatus fonctionne\n";
    echo "- Tester une connexion/déconnexion utilisateur\n\n";
    
} catch (Exception $e) {
    echo "❌ Erreur de connexion à la base: " . $e->getMessage() . "\n";
}
