<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Prendre le premier étudiant avec une formation
$studentId = 15; // Paul Marie - Community Management

echo "\n=== TEST PAIEMENTS ÉTUDIANT ID {$studentId} ===\n\n";

// 1. Vérifier l'étudiant
$student = DB::table('students')->where('id', $studentId)->first();

if (!$student) {
    echo "❌ Étudiant ID {$studentId} non trouvé\n";
    exit;
}

echo "✅ Étudiant trouvé :\n";
echo "   Nom : {$student->first_name} {$student->last_name}\n";
echo "   Email : {$student->email}\n";
echo "   Formation : {$student->program}\n\n";

// 2. Chercher la pré-inscription
$preReg = DB::table('pre_registrations')->where('email', $student->email)->first();

if ($preReg) {
    echo "✅ Pré-inscription trouvée (ID: {$preReg->id})\n\n";

    // 3. Chercher les paiements
    $payments = DB::table('payments')
        ->where('pre_registration_id', $preReg->id)
        ->orderBy('installment_number', 'asc')
        ->get();

    echo "💰 PAIEMENTS :\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

    if ($payments->count() > 0) {
        $totalPaye = 0;
        foreach ($payments as $payment) {
            $status = $payment->status === 'completed' ? '✅ Payé' :
                     ($payment->status === 'pending' ? '⏳ En attente' : '❌ Annulé');
            echo "Tranche {$payment->installment_number} : " . number_format($payment->amount, 0, ',', ' ') . " FCFA - {$status}\n";

            if ($payment->status === 'completed') {
                $totalPaye += $payment->amount;
            }
        }

        echo "\n📊 RÉSUMÉ :\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Total Formation : 77 000 FCFA\n";
        echo "Total Payé      : " . number_format($totalPaye, 0, ',', ' ') . " FCFA\n";
        echo "Reste à payer   : " . number_format(77000 - $totalPaye, 0, ',', ' ') . " FCFA\n";
        echo "Progression     : " . round(($totalPaye / 77000) * 100) . "%\n";
    } else {
        echo "❌ Aucun paiement trouvé pour cette pré-inscription\n";
    }
} else {
    echo "❌ Aucune pré-inscription trouvée avec cet email\n";
    echo "   L'étudiant n'a peut-être pas été créé via le système de pré-inscription\n";

    // Vérifier ancienne table paiements
    if (Schema::hasTable('paiements')) {
        $user = DB::table('users')->where('email', $student->email)->first();
        if ($user) {
            $oldPayments = DB::table('paiements')->where('user_id', $user->id)->get();
            if ($oldPayments->count() > 0) {
                echo "\n⚠️  Paiements trouvés dans ancienne table 'paiements' :\n";
                foreach ($oldPayments as $p) {
                    echo "   - " . number_format($p->montant, 0, ',', ' ') . " FCFA ({$p->statut})\n";
                }
            }
        }
    }
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "URL de test : http://127.0.0.1:8000/evc/app/admin/students/{$studentId}/profile\n\n";
