<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n=== STATISTIQUES DASHBOARD ADMIN ===\n\n";

// Statistiques Paiements (table payments)
echo "📊 PAIEMENTS (Table 'payments') :\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$totalPayments = DB::table('payments')->sum('amount');
$completedPayments = DB::table('payments')->where('status', 'completed')->sum('amount');
$pendingPayments = DB::table('payments')->where('status', 'pending')->sum('amount');
$cancelledPayments = DB::table('payments')->where('status', 'cancelled')->sum('amount');

$paymentsCount = DB::table('payments')->count();
$completedPaymentsCount = DB::table('payments')->where('status', 'completed')->count();
$pendingPaymentsCount = DB::table('payments')->where('status', 'pending')->count();
$cancelledPaymentsCount = DB::table('payments')->where('status', 'cancelled')->count();

echo "💰 Total des paiements : " . number_format($totalPayments, 0, ',', ' ') . " FCFA\n";
echo "   └─ Total paiements créés : {$paymentsCount}\n\n";

echo "✅ Paiements reçus (completed) : " . number_format($completedPayments, 0, ',', ' ') . " FCFA\n";
echo "   └─ Nombre : {$completedPaymentsCount} paiement(s)\n\n";

echo "⏳ Paiements en attente (pending) : " . number_format($pendingPayments, 0, ',', ' ') . " FCFA\n";
echo "   └─ Nombre : {$pendingPaymentsCount} paiement(s)\n\n";

if ($cancelledPaymentsCount > 0) {
    echo "❌ Paiements annulés (cancelled) : " . number_format($cancelledPayments, 0, ',', ' ') . " FCFA\n";
    echo "   └─ Nombre : {$cancelledPaymentsCount} paiement(s)\n\n";
}

// Paiements ce mois
$currentMonthStart = now()->startOfMonth();
$paymentsThisMonth = DB::table('payments')
    ->where('created_at', '>=', $currentMonthStart)
    ->where('status', 'completed')
    ->sum('amount');

$paymentsThisMonthCount = DB::table('payments')
    ->where('created_at', '>=', $currentMonthStart)
    ->where('status', 'completed')
    ->count();

echo "📅 Paiements ce mois (" . now()->format('F Y') . ") : " . number_format($paymentsThisMonth, 0, ',', ' ') . " FCFA\n";
echo "   └─ Nombre : {$paymentsThisMonthCount} paiement(s)\n\n";

// Détail par tranche
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📋 RÉPARTITION PAR TRANCHE :\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Tranche 1
$tranche1Total = DB::table('payments')->where('installment_number', 1)->sum('amount');
$tranche1Completed = DB::table('payments')->where('installment_number', 1)->where('status', 'completed')->sum('amount');
$tranche1CompletedCount = DB::table('payments')->where('installment_number', 1)->where('status', 'completed')->count();
$tranche1Pending = DB::table('payments')->where('installment_number', 1)->where('status', 'pending')->sum('amount');
$tranche1PendingCount = DB::table('payments')->where('installment_number', 1)->where('status', 'pending')->count();

echo "🔹 Tranche 1 (50 000 FCFA) :\n";
echo "   ✅ Payées : " . number_format($tranche1Completed, 0, ',', ' ') . " FCFA ({$tranche1CompletedCount} paiement(s))\n";
echo "   ⏳ En attente : " . number_format($tranche1Pending, 0, ',', ' ') . " FCFA ({$tranche1PendingCount} paiement(s))\n";
echo "   💰 Total : " . number_format($tranche1Total, 0, ',', ' ') . " FCFA\n\n";

// Tranche 2
$tranche2Total = DB::table('payments')->where('installment_number', 2)->sum('amount');
$tranche2Completed = DB::table('payments')->where('installment_number', 2)->where('status', 'completed')->sum('amount');
$tranche2CompletedCount = DB::table('payments')->where('installment_number', 2)->where('status', 'completed')->count();
$tranche2Pending = DB::table('payments')->where('installment_number', 2)->where('status', 'pending')->sum('amount');
$tranche2PendingCount = DB::table('payments')->where('installment_number', 2)->where('status', 'pending')->count();

echo "🔹 Tranche 2 (27 000 FCFA) :\n";
echo "   ✅ Payées : " . number_format($tranche2Completed, 0, ',', ' ') . " FCFA ({$tranche2CompletedCount} paiement(s))\n";
echo "   ⏳ En attente : " . number_format($tranche2Pending, 0, ',', ' ') . " FCFA ({$tranche2PendingCount} paiement(s))\n";
echo "   💰 Total : " . number_format($tranche2Total, 0, ',', ' ') . " FCFA\n\n";

// Calcul de la progression
$totalExpected = ($tranche1CompletedCount + $tranche1PendingCount + $tranche2CompletedCount + $tranche2PendingCount) * 77000;
$pourcentageRecouvrement = $totalExpected > 0 ? round(($completedPayments / $totalExpected) * 100, 2) : 0;

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📈 ANALYSE :\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Montant attendu total : " . number_format($totalExpected, 0, ',', ' ') . " FCFA\n";
echo "Montant reçu : " . number_format($completedPayments, 0, ',', ' ') . " FCFA\n";
echo "Taux de recouvrement : {$pourcentageRecouvrement}%\n\n";

echo "🔗 URL Dashboard : http://127.0.0.1:8000/evc/app/admin/dashboard\n\n";
