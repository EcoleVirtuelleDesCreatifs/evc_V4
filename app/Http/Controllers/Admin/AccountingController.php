<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountingTransaction;
use App\Models\AccountingBudget;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    /**
     * Affiche le tableau de bord de la comptabilité.
     */
    public function index(Request $request): View
    {
        // Années disponibles
        $years = AccountingTransaction::selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $selectedYear = $request->input('year', date('Y'));

        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }

        // Caisse (Solde cumulé jusqu'à la fin de l'année sélectionnée)
        $totalIncome = AccountingTransaction::where('type', 'income')
            ->whereYear('date', '<=', $selectedYear)
            ->sum('amount');

        $totalExpenses = AccountingTransaction::where('type', 'expense')
            ->whereYear('date', '<=', $selectedYear)
            ->sum('amount');

        $balance = $totalIncome - $totalExpenses;

        // Stats de l'exercice (Année sélectionnée uniquement)
        $yearIncome = AccountingTransaction::where('type', 'income')
            ->whereYear('date', $selectedYear)
            ->sum('amount');

        $yearExpenses = AccountingTransaction::where('type', 'expense')
            ->whereYear('date', $selectedYear)
            ->sum('amount');

        // Stats mensuelles (Mois en cours de l'année sélectionnée ou décembre si année passée ?)
        // On garde le "ce mois-ci" réel pour l'année en cours, sinon on affiche 0 ou moyenne
        if ($selectedYear == date('Y')) {
            $currentMonthStart = now()->startOfMonth();
        } else {
            $currentMonthStart = date($selectedYear . '-12-01'); // Décembre de l'année passée pour info
        }

        $incomeThisMonth = AccountingTransaction::where('type', 'income')
            ->whereYear('date', $selectedYear)
            ->whereMonth('date', date('m', strtotime($currentMonthStart)))
            ->sum('amount');

        $expensesThisMonth = AccountingTransaction::where('type', 'expense')
            ->whereYear('date', $selectedYear)
            ->whereMonth('date', date('m', strtotime($currentMonthStart)))
            ->sum('amount');

        // Dernières transactions de l'année sélectionnée
        $recentTransactions = AccountingTransaction::whereYear('date', $selectedYear)
            ->latest('date')
            ->take(5)
            ->get();

        return view('admin.accounting.index', compact(
            'totalIncome', 'totalExpenses', 'balance',
            'yearIncome', 'yearExpenses',
            'incomeThisMonth', 'expensesThisMonth', 'recentTransactions',
            'selectedYear', 'years'
        ));
    }

    /**
     * Affiche la page de gestion des dépenses.
     */
    public function expenses(): View
    {
        $stats = [
            'total' => 0,
            'this_month' => 0,
            'last_7d' => 0,
            'this_year' => 0,
        ];

        $stats['total'] = AccountingTransaction::where('type', 'expense')->count();
        $stats['this_month'] = (float) AccountingTransaction::where('type', 'expense')
            ->where('date', '>=', now()->startOfMonth())
            ->sum('amount');
        $stats['last_7d'] = (float) AccountingTransaction::where('type', 'expense')
            ->where('date', '>=', now()->subDays(7))
            ->sum('amount');
        $stats['this_year'] = (float) AccountingTransaction::where('type', 'expense')
            ->whereYear('date', now()->year)
            ->sum('amount');

        $expenses = AccountingTransaction::where('type', 'expense')
            ->latest('date')
            ->paginate(15);

        return view('admin.accounting.expenses', compact('expenses', 'stats'));
    }

    /**
     * Affiche le formulaire de création d'une dépense.
     */
    public function createExpense(): View
    {
        return view('admin.accounting.create-expense');
    }

    /**
     * Affiche la page de gestion des ventes.
     */
    public function sales(): View
    {
        $stats = [
            'total' => 0,
            'this_month' => 0,
            'last_7d' => 0,
            'this_year' => 0,
        ];

        $stats['total'] = AccountingTransaction::where('type', 'income')->count();
        $stats['this_month'] = (float) AccountingTransaction::where('type', 'income')
            ->where('date', '>=', now()->startOfMonth())
            ->sum('amount');
        $stats['last_7d'] = (float) AccountingTransaction::where('type', 'income')
            ->where('date', '>=', now()->subDays(7))
            ->sum('amount');
        $stats['this_year'] = (float) AccountingTransaction::where('type', 'income')
            ->whereYear('date', now()->year)
            ->sum('amount');

        $sales = AccountingTransaction::where('type', 'income')
            ->latest('date')
            ->paginate(15);

        return view('admin.accounting.sales', compact('sales', 'stats'));
    }

    /**
     * Affiche le formulaire de création d'une vente.
     */
    public function createSale(): View
    {
        return view('admin.accounting.create-sale');
    }

    /**
     * Affiche le rapport financier.
     */
    public function report(Request $request): View
    {
        $year = $request->input('year', date('Y'));

        // Monthly data for the chart
        $monthlyStats = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthStart = date('Y-m-d', mktime(0, 0, 0, $m, 1, $year));
            $monthEnd = date('Y-m-t', mktime(0, 0, 0, $m, 1, $year));

            $income = AccountingTransaction::where('type', 'income')
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('amount');

            $expense = AccountingTransaction::where('type', 'expense')
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('amount');

            $monthlyStats[] = [
                'month' => date('M', mktime(0, 0, 0, $m, 1, $year)),
                'income' => $income,
                'expense' => $expense,
            ];
        }

        // Expenses by Category
        $expensesByCategory = AccountingTransaction::where('type', 'expense')
            ->whereYear('date', $year)
            ->select('category', DB::raw('sum(amount) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        // Income by Category
        $incomeByCategory = AccountingTransaction::where('type', 'income')
            ->whereYear('date', $year)
            ->select('category', DB::raw('sum(amount) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        return view('admin.accounting.report', compact('year', 'monthlyStats', 'expensesByCategory', 'incomeByCategory'));
    }

    /**
     * Enregistre une nouvelle transaction (dépense ou vente).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'student_name' => 'nullable|string|max:255',
            'training_module' => 'nullable|string|max:255',
            'reference' => 'nullable|string',
            'proof' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $transaction = new AccountingTransaction($validated);

        if ($request->hasFile('proof')) {
            $path = $request->file('proof')->store('accounting/proofs', 'public');
            $transaction->proof_path = $path;
        }

        $transaction->save();

        $message = $validated['type'] === 'income' ? 'Vente enregistrée avec succès.' : 'Dépense enregistrée avec succès.';
        $route = $validated['type'] === 'income' ? 'accounting.sales' : 'accounting.expenses';

        return redirect()->route('admin.'.$route)->with('success', $message);
    }

    /**
     * Supprime une transaction.
     */
    public function destroy($id): RedirectResponse
    {
        $transaction = AccountingTransaction::findOrFail($id);

        if ($transaction->proof_path) {
            Storage::disk('public')->delete($transaction->proof_path);
        }

        $type = $transaction->type;
        $transaction->delete();

        $route = $type === 'income' ? 'accounting.sales' : 'accounting.expenses';

        return redirect()->route('admin.'.$route)->with('success', 'Transaction supprimée avec succès.');
    }

    /**
     * Exporte les transactions au format CSV.
     */
    public function export(Request $request)
    {
        $type = $request->input('type', 'all'); // all, income, expense
        $year = $request->input('year');

        $query = AccountingTransaction::query();

        if ($type !== 'all') {
            $query->where('type', $type);
        }

        if ($year) {
            $query->whereYear('date', $year);
        }

        $transactions = $query->orderBy('date', 'desc')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=transactions_" . ($type === 'all' ? 'global' : $type) . "_" . date('Y-m-d') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');

            // Add BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header row
            fputcsv($file, ['Date', 'Type', 'Titre', 'Catégorie', 'Montant (FCFA)', 'Mode de paiement', 'Référence', 'Description']);

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->date->format('d/m/Y'),
                    $transaction->type === 'income' ? 'Recette' : 'Dépense',
                    $transaction->title,
                    $transaction->category,
                    $transaction->amount,
                    $transaction->payment_method,
                    $transaction->reference,
                    $transaction->description
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Affiche le Grand Livre (General Ledger).
     */
    public function generalLedger(Request $request): View
    {
        $startDate = $request->input('start_date', date('Y-01-01'));
        $endDate = $request->input('end_date', date('Y-12-31'));

        $transactions = AccountingTransaction::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.accounting.general-ledger', compact('transactions', 'startDate', 'endDate'));
    }

    /**
     * Affiche la gestion des budgets.
     */
    public function budgets(Request $request): View
    {
        $year = $request->input('year', date('Y'));

        // Récupérer les budgets existants
        $budgets = AccountingBudget::where('year', $year)->get();

        // Récupérer les dépenses réelles par catégorie pour l'année
        $actualExpenses = AccountingTransaction::where('type', 'expense')
            ->whereYear('date', $year)
            ->select('category', DB::raw('sum(amount) as total'))
            ->groupBy('category')
            ->pluck('total', 'category');

        return view('admin.accounting.budgets', compact('year', 'budgets', 'actualExpenses'));
    }

    /**
     * Affiche le formulaire de définition d'un budget.
     */
    public function createBudget(): View
    {
        return view('admin.accounting.create-budget');
    }

    /**
     * Enregistre ou met à jour un budget.
     */
    public function storeBudget(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2020|max:2030',
            'category' => 'required|string',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
        ]);

        AccountingBudget::updateOrCreate(
            [
                'year' => $validated['year'],
                'category' => $validated['category'],
                'type' => $validated['type'],
            ],
            ['amount' => $validated['amount']]
        );

        return redirect()->route('admin.accounting.budgets', ['year' => $validated['year']])
            ->with('success', 'Budget mis à jour avec succès.');
    }
}
