<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "\n=== TEST COMPTAGE FORMATIONS/PROGRAMMES ===\n\n";

// Vérifier si la table programmes existe
if (!Schema::hasTable('programmes')) {
    echo "❌ La table 'programmes' n'existe pas\n";
    exit;
}

echo "✅ Table 'programmes' trouvée\n\n";

// Compter tous les programmes
$totalProgrammes = DB::table('programmes')->count();
echo "📊 Total programmes : {$totalProgrammes}\n\n";

echo "Note : La table 'programmes' n'a pas de colonne 'status'\n";
echo "Tous les programmes sont considérés comme disponibles\n\n";

// Compter par formation
echo "📚 Par formation :\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$formations = ['Design Graphique', 'Community Management', 'Gestion Informatique', 'Intelligence Artificielle', 'Toutes'];

foreach ($formations as $formation) {
    $count = DB::table('programmes')
        ->where('formation', $formation)
        ->count();
    echo "   {$formation} : {$count} programme(s)\n";
}

// Compter spécifiquement pour Design Graphique (comme dans le code)
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🎨 DESIGN GRAPHIQUE (avec variantes) :\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$designGraphiqueCount = DB::table('programmes')
    ->where(function($query) {
        $query->where('formation', 'Design Graphique')
              ->orWhere('formation', 'design_graphique')
              ->orWhere('formation', 'Toutes');
    })
    ->count();

echo "Total disponible pour Design Graphique : {$designGraphiqueCount}\n";

// Détail des programmes Design Graphique
$programmes = DB::table('programmes')
    ->where(function($query) {
        $query->where('formation', 'Design Graphique')
              ->orWhere('formation', 'design_graphique')
              ->orWhere('formation', 'Toutes');
    })
    ->select('id', 'titre', 'formation', 'created_at')
    ->orderBy('created_at', 'desc')
    ->get();

if ($programmes->count() > 0) {
    echo "\nListe des programmes :\n";
    foreach ($programmes as $prog) {
        echo "   • {$prog->titre} (Formation: {$prog->formation})\n";
        echo "     ID: {$prog->id} | Créé: " . \Carbon\Carbon::parse($prog->created_at)->format('d/m/Y') . "\n";
    }
} else {
    echo "\n⚠️  Aucun programme publié trouvé pour Design Graphique\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔗 URL à tester : http://127.0.0.1:8000/evc/compte/design-graphique/espace-etudiant\n\n";
