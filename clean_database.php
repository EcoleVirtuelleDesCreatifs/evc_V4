<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "\n⚠️  NETTOYAGE COMPLET DE LA BASE DE DONNÉES ⚠️\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Ce script va SUPPRIMER :\n";
echo "  • Tous les étudiants (table students)\n";
echo "  • Tous les utilisateurs (table users)\n";
echo "  • Tous les TP (table tp)\n";
echo "  • Tous les assignments de TP (table tp_assignments)\n";
echo "  • Tous les fichiers de TP (table tp_assignment_files)\n";
echo "  • Tous les projets (tables projects, design_projects)\n";
echo "  • Tous les paiements (table payments)\n";
echo "  • Toutes les pré-inscriptions (table pre_registrations)\n";
echo "  • Tous les rapports de fin de formation (table end_of_training_reports)\n";
echo "  • Toutes les activités utilisateurs (table user_activities)\n\n";
echo "Les ADMINS (table admins) seront CONSERVÉS !\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Demander confirmation
echo "Voulez-vous vraiment continuer ? (tapez 'OUI' en majuscules) : ";
$handle = fopen("php://stdin", "r");
$confirmation = trim(fgets($handle));
fclose($handle);

if ($confirmation !== 'OUI') {
    echo "\n❌ Opération annulée.\n\n";
    exit;
}

echo "\n🔄 Début du nettoyage...\n\n";

try {
    // Désactiver les contraintes de clés étrangères temporairement
    DB::statement('SET FOREIGN_KEY_CHECKS=0');

    // Compter avant suppression
    $counts = [
        'students' => DB::table('students')->count(),
        'users' => DB::table('users')->count(),
        'tp' => Schema::hasTable('tp') ? DB::table('tp')->count() : 0,
        'tp_assignments' => Schema::hasTable('tp_assignments') ? DB::table('tp_assignments')->count() : 0,
        'tp_assignment_files' => Schema::hasTable('tp_assignment_files') ? DB::table('tp_assignment_files')->count() : 0,
        'projects' => Schema::hasTable('projects') ? DB::table('projects')->count() : 0,
        'design_projects' => Schema::hasTable('design_projects') ? DB::table('design_projects')->count() : 0,
        'payments' => Schema::hasTable('payments') ? DB::table('payments')->count() : 0,
        'pre_registrations' => Schema::hasTable('pre_registrations') ? DB::table('pre_registrations')->count() : 0,
        'end_of_training_reports' => Schema::hasTable('end_of_training_reports') ? DB::table('end_of_training_reports')->count() : 0,
        'user_activities' => Schema::hasTable('user_activities') ? DB::table('user_activities')->count() : 0,
    ];

    echo "📊 Comptage avant suppression :\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    foreach ($counts as $table => $count) {
        echo "  • {$table} : {$count} enregistrement(s)\n";
    }
    echo "\n";

    // 1. Supprimer les fichiers de TP assignments
    if (Schema::hasTable('tp_assignment_files')) {
        $deleted = DB::table('tp_assignment_files')->delete();
        echo "✅ Fichiers TP assignments supprimés : {$deleted}\n";
    }

    // 2. Supprimer les TP assignments
    if (Schema::hasTable('tp_assignments')) {
        $deleted = DB::table('tp_assignments')->delete();
        echo "✅ TP assignments supprimés : {$deleted}\n";
    }

    // 3. Supprimer les TP
    if (Schema::hasTable('tp')) {
        $deleted = DB::table('tp')->delete();
        echo "✅ TP supprimés : {$deleted}\n";
    }

    // 4. Supprimer les projets
    if (Schema::hasTable('projects')) {
        $deleted = DB::table('projects')->delete();
        echo "✅ Projets (projects) supprimés : {$deleted}\n";
    }

    if (Schema::hasTable('design_projects')) {
        $deleted = DB::table('design_projects')->delete();
        echo "✅ Projets design supprimés : {$deleted}\n";
    }

    // 5. Supprimer les rapports de fin de formation
    if (Schema::hasTable('end_of_training_reports')) {
        $deleted = DB::table('end_of_training_reports')->delete();
        echo "✅ Rapports de fin de formation supprimés : {$deleted}\n";
    }

    // 6. Supprimer les paiements
    if (Schema::hasTable('payments')) {
        $deleted = DB::table('payments')->delete();
        echo "✅ Paiements supprimés : {$deleted}\n";
    }

    // 7. Supprimer les pré-inscriptions
    if (Schema::hasTable('pre_registrations')) {
        $deleted = DB::table('pre_registrations')->delete();
        echo "✅ Pré-inscriptions supprimées : {$deleted}\n";
    }

    // 8. Supprimer les activités utilisateurs
    if (Schema::hasTable('user_activities')) {
        $deleted = DB::table('user_activities')->delete();
        echo "✅ Activités utilisateurs supprimées : {$deleted}\n";
    }

    // 9. Supprimer les étudiants
    $deleted = DB::table('students')->delete();
    echo "✅ Étudiants supprimés : {$deleted}\n";

    // 10. Supprimer TOUS les users (les admins sont dans une autre table)
    $deleted = DB::table('users')->delete();
    echo "✅ Utilisateurs supprimés : {$deleted}\n";

    // Réactiver les contraintes de clés étrangères
    DB::statement('SET FOREIGN_KEY_CHECKS=1');

    echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "✅ Nettoyage terminé avec succès !\n\n";

    // Afficher ce qui reste (admins dans la table admins)
    if (Schema::hasTable('admins')) {
        $admins = DB::table('admins')->get();
        echo "👤 Admins conservés ({$admins->count()}) :\n";
        foreach ($admins as $admin) {
            echo "   • {$admin->name} ({$admin->email}) - Role: {$admin->role}\n";
        }
    } else {
        echo "⚠️  Aucune table 'admins' trouvée\n";
    }

    echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

} catch (\Exception $e) {
    // Réactiver les contraintes en cas d'erreur
    DB::statement('SET FOREIGN_KEY_CHECKS=1');

    echo "\n❌ ERREUR : " . $e->getMessage() . "\n";
    echo "Ligne : " . $e->getLine() . "\n";
    echo "Fichier : " . $e->getFile() . "\n\n";
    exit(1);
}
