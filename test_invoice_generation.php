<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Services\InvoiceGenerator;

echo "\n=== TEST GÉNÉRATION DE FACTURE PDF ===\n\n";

// Récupérer un paiement completed
$payment = DB::table('payments')
    ->where('status', 'completed')
    ->first();

if (!$payment) {
    echo "❌ Aucun paiement complété trouvé dans la base de données\n\n";
    exit;
}

echo "✅ Paiement trouvé (ID: {$payment->id})\n";
echo "   Référence : {$payment->payment_reference}\n";
echo "   Montant : " . number_format($payment->amount, 0, ',', ' ') . " FCFA\n";
echo "   Statut : {$payment->status}\n";
echo "   Date paiement : {$payment->paid_at}\n\n";

// Récupérer la pré-inscription
$preReg = DB::table('pre_registrations')
    ->where('id', $payment->pre_registration_id)
    ->first();

if ($preReg) {
    echo "✅ Pré-inscription trouvée\n";
    echo "   Nom : {$preReg->nom} {$preReg->prenom}\n";
    echo "   Email : {$preReg->email}\n\n";
} else {
    echo "⚠️  Pré-inscription non trouvée\n\n";
}

// Récupérer l'étudiant
$student = null;
if ($preReg) {
    $user = DB::table('users')->where('email', $preReg->email)->first();
    if ($user) {
        $student = DB::table('students')->where('user_id', $user->id)->first();
        if ($student) {
            echo "✅ Étudiant trouvé\n";
            echo "   Nom : {$student->first_name} {$student->last_name}\n";
            echo "   Formation : {$student->program}\n\n";
        }
    }
}

// Générer la facture
echo "🔄 Génération de la facture PDF...\n\n";

try {
    $invoiceGenerator = new InvoiceGenerator();
    $invoiceGenerator->generateInvoice($payment, $student, $preReg);

    // Sauvegarder dans le dossier temporaire
    $filename = 'test_facture_' . $payment->payment_reference . '.pdf';
    $filepath = storage_path('app/temp/' . $filename);

    // Créer le dossier temp s'il n'existe pas
    if (!file_exists(storage_path('app/temp'))) {
        mkdir(storage_path('app/temp'), 0755, true);
    }

    $invoiceGenerator->save($filepath);

    if (file_exists($filepath)) {
        $filesize = filesize($filepath);
        echo "✅ Facture générée avec succès !\n";
        echo "   Fichier : {$filename}\n";
        echo "   Chemin : {$filepath}\n";
        echo "   Taille : " . round($filesize / 1024, 2) . " Ko\n\n";

        echo "📄 Contenu de la facture :\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "• En-tête : ECOLE VIRTUELLE DES CREATEURS\n";
        echo "• Référence : {$payment->payment_reference}\n";
        echo "• Date : " . ($payment->paid_at ? date('d/m/Y', strtotime($payment->paid_at)) : date('d/m/Y')) . "\n";
        echo "• Transaction : {$payment->transaction_id}\n";
        echo "• Client : " . ($student ? "{$student->first_name} {$student->last_name}" : "{$preReg->prenom} {$preReg->nom}") . "\n";
        echo "• Formation : " . ($student->program ?? $preReg->programme ?? 'Non spécifié') . "\n";
        echo "• Montant : " . number_format($payment->amount, 0, ',', ' ') . " FCFA\n";
        echo "• Tranche : {$payment->installment_number} / {$payment->total_installments}\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

        echo "💡 Vous pouvez ouvrir le fichier PDF pour vérifier son contenu.\n";
        echo "🔗 URL de test : http://127.0.0.1:8000/evc/compte/design-graphique/paiements/invoice/{$payment->id}\n\n";
    } else {
        echo "❌ Erreur : Le fichier PDF n'a pas été créé\n\n";
    }

} catch (\Exception $e) {
    echo "❌ ERREUR lors de la génération : " . $e->getMessage() . "\n";
    echo "   Fichier : " . $e->getFile() . "\n";
    echo "   Ligne : " . $e->getLine() . "\n\n";
}
