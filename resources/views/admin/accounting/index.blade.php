@extends('layouts.admin')

@section('title', 'Comptabilité - Tableau de bord')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-5">

            <p class="text-muted mb-0">Vue d'ensemble de la santé financière.</p>
        </div>
        <div class="col-md-7 text-md-end mt-3 mt-md-0 d-flex justify-content-md-end align-items-center gap-2">
            <!-- Exercice Selector -->
            <form action="{{ route('admin.accounting.index') }}" method="GET" class="d-inline-block">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-calendar-alt text-muted"></i></span>
                    <select name="year" class="form-select border-start-0 ps-0" onchange="this.form.submit()" style="min-width: 120px; cursor: pointer;">
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>Exercice {{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <div class="btn-group shadow-sm" role="group">
                <a href="{{ route('admin.accounting.sales.create') }}" class="btn btn-success text-white">
                    <i class="fas fa-plus-circle me-2"></i>Vente
                </a>
                <a href="{{ route('admin.accounting.expenses.create') }}" class="btn btn-danger text-white">
                    <i class="fas fa-minus-circle me-2"></i>Dépense
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions Bar -->
    <div class="card border-0 shadow-sm mb-4 bg-white">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                <span class="text-uppercase text-xs fw-bold text-muted ls-1">Outils de Gestion</span>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.accounting.report') }}" class="btn btn-sm btn-outline-info">
                        <i class="fas fa-chart-line me-2"></i>Rapports & Analytique
                    </a>
                    <a href="{{ route('admin.accounting.general-ledger') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-book me-2"></i>Grand Livre
                    </a>
                    <a href="{{ route('admin.accounting.budgets') }}" class="btn btn-sm btn-outline-warning text-dark">
                        <i class="fas fa-calculator me-2"></i>Budgets Prévisionnels
                    </a>
                    <div class="dropdown d-inline-block">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-file-export me-2"></i>Export Données
                        </button>
                        <ul class="dropdown-menu shadow" aria-labelledby="exportDropdown">
                            <li><h6 class="dropdown-header">Format CSV</h6></li>
                            <li><a class="dropdown-item" href="{{ route('admin.accounting.export', ['type' => 'all']) }}">Tout exporter (Global)</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('admin.accounting.export', ['type' => 'income']) }}"><i class="fas fa-arrow-up text-success me-2"></i>Recettes uniquement</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.accounting.export', ['type' => 'expense']) }}"><i class="fas fa-arrow-down text-danger me-2"></i>Dépenses uniquement</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Health Overview -->
    <div class="row g-4 mb-4">
        <!-- Recettes -->
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-uppercase fw-bold text-muted text-xs mb-1 ls-1">Recettes {{ $selectedYear }}</p>
                            <h3 class="display-6 fw-bold text-success mb-0">{{ number_format($yearIncome, 0, ',', ' ') }} <span class="fs-6 text-muted">FCFA</span></h3>
                        </div>
                        <div class="icon-shape bg-success bg-opacity-10 text-success rounded-circle p-3">
                            <i class="fas fa-wallet fa-2x"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success bg-opacity-10 text-success me-2">
                            <i class="fas fa-arrow-up me-1"></i>{{ number_format($incomeThisMonth, 0, ',', ' ') }}
                        </span>
                        <span class="text-muted text-sm">ce mois-ci</span>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100" style="height: 4px; background: linear-gradient(90deg, #198754 0%, #a3cfbb 100%);"></div>
            </div>
        </div>

        <!-- Dépenses -->
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-uppercase fw-bold text-muted text-xs mb-1 ls-1">Dépenses {{ $selectedYear }}</p>
                            <h3 class="display-6 fw-bold text-danger mb-0">{{ number_format($yearExpenses, 0, ',', ' ') }} <span class="fs-6 text-muted">FCFA</span></h3>
                        </div>
                        <div class="icon-shape bg-danger bg-opacity-10 text-danger rounded-circle p-3">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-danger bg-opacity-10 text-danger me-2">
                            <i class="fas fa-arrow-up me-1"></i>{{ number_format($expensesThisMonth, 0, ',', ' ') }}
                        </span>
                        <span class="text-muted text-sm">ce mois-ci</span>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100" style="height: 4px; background: linear-gradient(90deg, #dc3545 0%, #f1aeb5 100%);"></div>
            </div>
        </div>

        <!-- Solde -->
        <div class="col-lg-4 col-md-12">
            <div class="card border-0 shadow-sm h-100 overflow-hidden position-relative bg-dark text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-uppercase fw-bold text-white-50 text-xs mb-1 ls-1">CAISSE (GLOBAL)</p>
                            <h3 class="display-6 fw-bold text-white mb-0">{{ number_format($globalBalance, 0, ',', ' ') }} <span class="fs-6 text-white-50">FCFA</span></h3>
                        </div>
                        <div class="icon-shape bg-white bg-opacity-25 text-white rounded-circle p-3">
                            <i class="fas fa-university fa-2x"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-white-50 text-sm mb-2">
                            Site: {{ number_format($balance, 0, ',', ' ') }} FCFA
                            <span class="mx-2">|</span>
                            Excel: {{ number_format($legacyBalance, 0, ',', ' ') }} FCFA
                        </div>
                        @if($globalBalance >= 0)
                            <div class="d-flex align-items-center text-success">
                                <i class="fas fa-check-circle me-2"></i>
                                <span class="fw-medium">Situation saine</span>
                            </div>
                        @else
                            <div class="d-flex align-items-center text-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <span class="fw-medium">Attention requise</span>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Background Decoration -->
                <div class="position-absolute top-0 end-0 p-3 opacity-10">
                    <i class="fas fa-chart-pie fa-5x text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold"><i class="fas fa-layer-group me-2 text-muted"></i>Détail des cumuls</h5>
                    <span class="text-muted text-sm">Montants en FCFA</span>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-lg-4">
                            <div class="p-3 border rounded-3 h-100">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="fw-bold text-dark">Cumul Site (jusqu'à {{ $selectedYear }})</div>
                                    <span class="badge bg-primary bg-opacity-10 text-primary">Site</span>
                                </div>
                                <div class="d-flex justify-content-between"><span class="text-muted">Entrées</span><span class="fw-bold text-success">{{ number_format($totalIncome, 0, ',', ' ') }}</span></div>
                                <div class="d-flex justify-content-between"><span class="text-muted">Sorties</span><span class="fw-bold text-danger">{{ number_format($totalExpenses, 0, ',', ' ') }}</span></div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between"><span class="text-muted">Solde</span><span class="fw-bold">{{ number_format($balance, 0, ',', ' ') }}</span></div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="p-3 border rounded-3 h-100" style="background: rgba(255, 193, 7, 0.06);">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="fw-bold text-dark">Historique (Excel)</div>
                                    <span class="badge bg-warning bg-opacity-10 text-dark">Avant site</span>
                                </div>
                                <div class="d-flex justify-content-between"><span class="text-muted">Entrées</span><span class="fw-bold text-success">{{ number_format($legacyIncome, 0, ',', ' ') }}</span></div>
                                <div class="d-flex justify-content-between"><span class="text-muted">Sorties</span><span class="fw-bold text-danger">{{ number_format($legacyExpenses, 0, ',', ' ') }}</span></div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between"><span class="text-muted">Solde</span><span class="fw-bold">{{ number_format($legacyBalance, 0, ',', ' ') }}</span></div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="p-3 border rounded-3 h-100 bg-dark text-white">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="fw-bold">Total Global</div>
                                    <span class="badge bg-light bg-opacity-10 text-white">Global</span>
                                </div>
                                <div class="d-flex justify-content-between"><span class="text-white-50">Entrées</span><span class="fw-bold text-success">{{ number_format($globalTotalIncome, 0, ',', ' ') }}</span></div>
                                <div class="d-flex justify-content-between"><span class="text-white-50">Sorties</span><span class="fw-bold text-danger">{{ number_format($globalTotalExpenses, 0, ',', ' ') }}</span></div>
                                <hr class="my-2 border-white border-opacity-25">
                                <div class="d-flex justify-content-between"><span class="text-white-50">Solde</span><span class="fw-bold">{{ number_format($globalBalance, 0, ',', ' ') }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- Recent Transactions -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
                    <h5 class="card-title mb-0 fw-bold"><i class="fas fa-history me-2 text-muted"></i>Dernières Transactions</h5>
                    <a href="{{ route('admin.accounting.general-ledger') }}" class="btn btn-sm btn-light">Voir tout</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted text-uppercase text-xs">
                            <tr>
                                <th class="ps-4">Date / Titre</th>
                                <th>Catégorie</th>
                                <th class="text-end">Montant</th>
                                <th class="text-center">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $transaction)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark">{{ $transaction->title }}</span>
                                        <span class="text-muted text-xs">{{ $transaction->date->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border rounded-pill px-3">{{ $transaction->category }}</span>
                                </td>
                                <td class="text-end fw-bold {{ $transaction->type === 'income' ? 'text-success' : 'text-danger' }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }} {{ number_format($transaction->amount, 0, ',', ' ') }}
                                </td>
                                <td class="text-center">
                                    @if($transaction->type === 'income')
                                        <div class="d-inline-flex align-items-center text-success bg-success bg-opacity-10 px-2 py-1 rounded-2 text-xs fw-bold">
                                            <i class="fas fa-arrow-down me-1"></i> Entrée
                                        </div>
                                    @else
                                        <div class="d-inline-flex align-items-center text-danger bg-danger bg-opacity-10 px-2 py-1 rounded-2 text-xs fw-bold">
                                            <i class="fas fa-arrow-up me-1"></i> Sortie
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted mb-2"><i class="fas fa-inbox fa-2x opacity-25"></i></div>
                                    <p class="text-muted mb-0">Aucune transaction récente.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Links / Shortcuts -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="card-title mb-0 fw-bold"><i class="fas fa-bolt me-2 text-warning"></i>Accès Rapide</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.accounting.sales') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape icon-sm bg-success bg-opacity-10 text-success rounded-circle me-3 p-2">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">Journal des Ventes</h6>
                                <small class="text-muted">Gérer les factures et reçus</small>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-muted opacity-50"></i>
                    </a>
                    <a href="{{ route('admin.accounting.expenses') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape icon-sm bg-danger bg-opacity-10 text-danger rounded-circle me-3 p-2">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">Journal des Dépenses</h6>
                                <small class="text-muted">Suivi des coûts et achats</small>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-muted opacity-50"></i>
                    </a>
                    <a href="{{ route('admin.accounting.budgets') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape icon-sm bg-primary bg-opacity-10 text-primary rounded-circle me-3 p-2">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">Objectifs Budgétaires</h6>
                                <small class="text-muted">Planification annuelle</small>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-muted opacity-50"></i>
                    </a>
                </div>
            </div>

            <!-- Mini Tips / Info -->
            <div class="card border-0 shadow-sm bg-info bg-opacity-10">
                <div class="card-body p-4">
                    <div class="d-flex">
                        <i class="fas fa-lightbulb text-info fa-2x me-3"></i>
                        <div>
                            <h6 class="fw-bold text-dark">Le saviez-vous ?</h6>
                            <p class="text-sm text-dark mb-0 opacity-75">
                                Un suivi régulier du <strong>Grand Livre</strong> permet d'identifier rapidement les écarts de trésorerie. Pensez à exporter vos données chaque fin de mois.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .icon-sm {
        width: 36px;
        height: 36px;
    }
    .text-xs {
        font-size: 0.75rem;
    }
    .ls-1 {
        letter-spacing: 1px;
    }
</style>
@endsection
