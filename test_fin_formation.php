<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n=== TEST PAGE FIN DE FORMATION ===\n\n";

// Prendre l'étudiant ID 15 (Paul Marie)
$studentId = 15;
$student = DB::table('students')->where('id', $studentId)->first();

if (!$student) {
    echo "❌ Étudiant non trouvé\n";
    exit;
}

$user = DB::table('users')->where('id', $student->user_id)->first();

echo "✅ Étudiant : {$student->first_name} {$student->last_name}\n";
echo "   Email : {$user->email}\n";
echo "   Formation : {$student->program}\n\n";

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "💰 CRITÈRE 1 : PAIEMENT INTÉGRAL\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$preReg = DB::table('pre_registrations')->where('email', $user->email)->first();

if ($preReg) {
    echo "✅ Pré-inscription trouvée (ID: {$preReg->id})\n\n";

    $payments = DB::table('payments')
        ->where('pre_registration_id', $preReg->id)
        ->get();

    $paymentAmount = 77000;
    $paymentPaid = $payments->where('status', 'completed')->sum('amount');
    $paymentRemaining = $paymentAmount - $paymentPaid;
    $paymentProgress = $paymentAmount > 0 ? ($paymentPaid / $paymentAmount) * 100 : 0;
    $paymentComplete = $paymentRemaining <= 0;

    echo "Montant total : " . number_format($paymentAmount, 0, ',', ' ') . " FCFA\n";
    echo "Montant payé : " . number_format($paymentPaid, 0, ',', ' ') . " FCFA\n";
    echo "Reste à payer : " . number_format($paymentRemaining, 0, ',', ' ') . " FCFA\n";
    echo "Progression : " . round($paymentProgress, 2) . "%\n";
    echo "Statut : " . ($paymentComplete ? "✅ Complet" : "⏳ En attente") . "\n\n";

    if ($payments->count() > 0) {
        echo "Détail des paiements :\n";
        foreach ($payments as $payment) {
            $statusIcon = $payment->status === 'completed' ? '✅' : '⏳';
            echo "  {$statusIcon} Tranche {$payment->installment_number} : " . number_format($payment->amount, 0, ',', ' ') . " FCFA ({$payment->status})\n";
        }
    }
} else {
    echo "❌ Pas de pré-inscription trouvée\n";
    echo "Montant total : 77 000 FCFA\n";
    echo "Montant payé : 0 FCFA\n";
    echo "Reste à payer : 77 000 FCFA\n";
    echo "Statut : ⏳ En attente\n";
}

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📝 CRITÈRE 2 : 15 TP OBLIGATOIRES\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$tpAssignments = DB::table('tp_assignments')
    ->where('student_id', $student->id)
    ->get();

$totalTP = $tpAssignments->count();
$tpValidated = $tpAssignments->where('status', 'validated')->count();
$tpPending = $tpAssignments->whereIn('status', ['assigned', 'submitted'])->count();
$minTPRequired = 15;
$tpRemaining = max(0, $minTPRequired - $tpValidated);
$tpEligible = $tpValidated >= $minTPRequired;

echo "TP validés : {$tpValidated} / {$minTPRequired}\n";
echo "TP en attente : {$tpPending}\n";
echo "TP restants : {$tpRemaining}\n";
echo "Statut : " . ($tpEligible ? "✅ Éligible" : "❌ Non éligible") . "\n";

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🎨 CRITÈRE 3 : 4 PROJETS OBLIGATOIRES\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$projects = DB::table('design_projects')
    ->where('user_id', $user->id)
    ->get();

$totalProjects = $projects->count();
$projectsCompleted = $projects->where('status', 'valide')->count();
$minProjectsRequired = 4;
$projectsRemaining = max(0, $minProjectsRequired - $projectsCompleted);
$projectsEligible = $projectsCompleted >= $minProjectsRequired;

echo "Projets validés : {$projectsCompleted} / {$minProjectsRequired}\n";
echo "Projets en cours : " . $projects->whereIn('status', ['en_cours', 'termine'])->count() . "\n";
echo "Projets restants : {$projectsRemaining}\n";
echo "Statut : " . ($projectsEligible ? "✅ Éligible" : "❌ Non éligible") . "\n";

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📄 CRITÈRE 4 : RAPPORT DE FIN DE FORMATION\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$report = DB::table('end_of_training_reports')
    ->where('student_id', $student->id)
    ->first();

$reportUploaded = $report ? true : false;

if ($report) {
    echo "✅ Rapport uploadé\n";
    echo "   Fichier : {$report->original_filename}\n";
    echo "   Taille : " . round($report->file_size / 1024, 2) . " Ko\n";
    echo "   Statut : {$report->status}\n";
} else {
    echo "❌ Rapport non uploadé\n";
    echo "   Action requise : Rédiger et soumettre le rapport\n";
}

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🎓 ÉLIGIBILITÉ GLOBALE\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$isEligible = $tpEligible && $projectsEligible && $reportUploaded && $paymentComplete;

echo "Paiement : " . ($paymentComplete ? "✅" : "❌") . "\n";
echo "TP : " . ($tpEligible ? "✅" : "❌") . "\n";
echo "Projets : " . ($projectsEligible ? "✅" : "❌") . "\n";
echo "Rapport : " . ($reportUploaded ? "✅" : "❌") . "\n\n";

echo "RÉSULTAT : " . ($isEligible ? "✅ ÉLIGIBLE À LA CERTIFICATION" : "❌ NON ÉLIGIBLE") . "\n\n";

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔗 URL à tester : http://127.0.0.1:8000/evc/compte/design-graphique/fin-formation/index\n\n";
