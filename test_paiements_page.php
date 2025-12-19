<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n=== TEST PAGE PAIEMENTS ÉTUDIANT ===\n\n";

// Simuler un étudiant (email de test)
$testEmail = "test@evc.ci";

echo "Recherche d'un étudiant avec email: {$testEmail}\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$user = DB::table('users')->where('email', $testEmail)->first();

if (!$user) {
    echo "❌ Aucun utilisateur trouvé avec cet email\n";
    echo "Utilisons le premier utilisateur disponible...\n\n";

    $user = DB::table('users')->first();

    if (!$user) {
        echo "❌ Aucun utilisateur dans la base de données\n";
        exit;
    }
}

echo "✅ Utilisateur trouvé :\n";
echo "   ID: {$user->id}\n";
echo "   Nom: {$user->name}\n";
echo "   Email: {$user->email}\n\n";

// Récupérer le profil étudiant
$student = DB::table('students')->where('user_id', $user->id)->first();

if ($student) {
    echo "✅ Profil étudiant :\n";
    echo "   Student ID: {$student->id}\n";
    echo "   Prénom: {$student->first_name}\n";
    echo "   Nom: {$student->last_name}\n";
    echo "   Programme: {$student->program}\n\n";
} else {
    echo "⚠️  Pas de profil étudiant associé\n\n";
}

// Récupérer la pré-inscription
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📋 PRÉ-INSCRIPTION\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$preReg = DB::table('pre_registrations')->where('email', $user->email)->first();

if ($preReg) {
    echo "✅ Pré-inscription trouvée (ID: {$preReg->id})\n";
    echo "   Nom: {$preReg->nom} {$preReg->prenom}\n";
    echo "   Formation: " . ($preReg->programme ?? 'Non spécifié') . "\n";
    echo "   Date candidature: {$preReg->created_at}\n\n";
} else {
    echo "❌ Aucune pré-inscription trouvée\n\n";
}

// Récupérer les paiements
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "💰 PAIEMENTS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$paymentAmount = 77000;
$paymentPaid = 0;
$paymentRemaining = $paymentAmount;
$paymentProgress = 0;
$payments = collect([]);

if ($preReg) {
    $payments = DB::table('payments')
        ->where('pre_registration_id', $preReg->id)
        ->orderBy('installment_number', 'asc')
        ->get();

    $paymentPaid = $payments->where('status', 'completed')->sum('amount');
    $paymentRemaining = $paymentAmount - $paymentPaid;
    $paymentProgress = $paymentAmount > 0 ? ($paymentPaid / $paymentAmount) * 100 : 0;

    echo "Montant total formation : " . number_format($paymentAmount, 0, ',', ' ') . " FCFA\n";
    echo "Montant payé : " . number_format($paymentPaid, 0, ',', ' ') . " FCFA\n";
    echo "Montant restant : " . number_format($paymentRemaining, 0, ',', ' ') . " FCFA\n";
    echo "Progression : " . round($paymentProgress, 2) . "%\n\n";

    if ($payments->count() > 0) {
        echo "Détail des paiements ({$payments->count()} tranche(s)) :\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        foreach ($payments as $payment) {
            $statusIcon = $payment->status === 'completed' ? '✅' : ($payment->status === 'pending' ? '⏳' : '❌');
            $statusLabel = $payment->status === 'completed' ? 'Payé' : ($payment->status === 'pending' ? 'En attente' : 'Annulé');

            echo "\n{$statusIcon} Tranche {$payment->installment_number} :\n";
            echo "   Montant : " . number_format($payment->amount, 0, ',', ' ') . " FCFA\n";
            echo "   Statut : {$statusLabel}\n";
            if ($payment->expires_at) {
                echo "   Date d'expiration : {$payment->expires_at}\n";
            }
            if ($payment->paid_at) {
                echo "   Payé le : {$payment->paid_at}\n";
            }
            if ($payment->payment_method) {
                echo "   Méthode : {$payment->payment_method}\n";
            }
            if ($payment->transaction_id) {
                echo "   Transaction : {$payment->transaction_id}\n";
            }
        }
    } else {
        echo "⚠️  Aucun paiement enregistré\n";
    }
} else {
    echo "❌ Pas de pré-inscription, donc pas de paiements\n";
    echo "Montant total formation : " . number_format($paymentAmount, 0, ',', ' ') . " FCFA\n";
    echo "Montant payé : 0 FCFA\n";
    echo "Montant restant : " . number_format($paymentAmount, 0, ',', ' ') . " FCFA\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔗 URL à tester : http://127.0.0.1:8000/evc/compte/design-graphique/paiements/index\n\n";
