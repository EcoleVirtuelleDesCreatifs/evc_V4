<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n=== CRÉATION PAIEMENT TEST ===\n\n";

$studentId = 15;
$student = DB::table('students')->where('id', $studentId)->first();
$preReg = DB::table('pre_registrations')->where('email', $student->email)->first();

if (!$preReg) {
    echo "❌ Pas de pré-inscription trouvée\n";
    exit;
}

echo "✅ Étudiant : {$student->first_name} {$student->last_name}\n";
echo "✅ Pré-inscription ID : {$preReg->id}\n\n";

// Vérifier si des paiements existent déjà
$existingPayments = DB::table('payments')->where('pre_registration_id', $preReg->id)->get();

if ($existingPayments->count() > 0) {
    echo "⚠️  Paiements existants détectés ({$existingPayments->count()}). Suppression...\n";
    DB::table('payments')->where('pre_registration_id', $preReg->id)->delete();
    echo "✅ Paiements supprimés\n\n";
}

// Créer 2 paiements (tranches)
echo "📝 Création des 2 tranches de paiement...\n\n";

// Tranche 1 : 50 000 FCFA - Payé
$payment1Id = DB::table('payments')->insertGetId([
    'pre_registration_id' => $preReg->id,
    'installment_number' => 1,
    'amount' => 50000,
    'status' => 'completed',
    'payment_method' => 'cinetpay',
    'transaction_id' => 'TEST_' . time() . '_1',
    'deadline' => now()->addDays(7),
    'created_at' => now()->subDays(30),
    'updated_at' => now()->subDays(30),
]);

echo "✅ Tranche 1 créée (ID: {$payment1Id}) - 50 000 FCFA - Payé\n";

// Tranche 2 : 27 000 FCFA - En attente
$payment2Id = DB::table('payments')->insertGetId([
    'pre_registration_id' => $preReg->id,
    'installment_number' => 2,
    'amount' => 27000,
    'status' => 'pending',
    'payment_method' => null,
    'transaction_id' => null,
    'deadline' => now()->addDays(30),
    'created_at' => now()->subDays(25),
    'updated_at' => now()->subDays(25),
]);

echo "✅ Tranche 2 créée (ID: {$payment2Id}) - 27 000 FCFA - En attente\n\n";

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 RÉSUMÉ :\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Total Formation : 77 000 FCFA\n";
echo "Total Payé      : 50 000 FCFA (Tranche 1)\n";
echo "Reste à payer   : 27 000 FCFA (Tranche 2)\n";
echo "Progression     : 65%\n\n";

echo "🔗 URL à tester : http://127.0.0.1:8000/evc/app/admin/students/{$studentId}/profile\n\n";
