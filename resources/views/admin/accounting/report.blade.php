@extends('layouts.admin')

@section('title', 'Rapport Financier')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Rapport Financier - {{ $year }}</h1>
        <div class="d-flex align-items-center gap-3">
            <form action="{{ route('admin.accounting.report') }}" method="GET" class="d-flex align-items-center gap-2">
                <select name="year" class="form-select" onchange="this.form.submit()">
                    @for($y = date('Y'); $y >= 2023; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
            <a href="{{ route('admin.accounting.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <!-- Monthly Chart -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">Évolution Mensuelle</h5>
        </div>
        <div class="card-body">
            <canvas id="monthlyChart" height="100"></canvas>
        </div>
    </div>

    <div class="row g-4">
        <!-- Expenses by Category -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-danger">Dépenses par Catégorie</h5>
                    <span class="badge bg-danger rounded-pill">{{ $expensesByCategory->count() }} catégories</span>
                </div>
                <div class="card-body">
                    @if($expensesByCategory->count() > 0)
                        <canvas id="expensesChart" height="200"></canvas>
                        <div class="mt-4">
                            <ul class="list-group list-group-flush">
                                @foreach($expensesByCategory as $expense)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>
                                        <span class="badge bg-light text-dark border me-2">{{ $expense->category }}</span>
                                    </span>
                                    <span class="fw-bold text-danger">{{ number_format($expense->total, 0, ',', ' ') }} FCFA</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            Aucune donnée de dépense pour cette année.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Income by Category -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-success">Recettes par Catégorie</h5>
                    <span class="badge bg-success rounded-pill">{{ $incomeByCategory->count() }} catégories</span>
                </div>
                <div class="card-body">
                    @if($incomeByCategory->count() > 0)
                        <canvas id="incomeChart" height="200"></canvas>
                        <div class="mt-4">
                            <ul class="list-group list-group-flush">
                                @foreach($incomeByCategory as $income)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>
                                        <span class="badge bg-light text-dark border me-2">{{ $income->category }}</span>
                                    </span>
                                    <span class="fw-bold text-success">{{ number_format($income->total, 0, ',', ' ') }} FCFA</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            Aucune donnée de recette pour cette année.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Chart
    const ctxMonthly = document.getElementById('monthlyChart').getContext('2d');
    const monthlyStats = @json($monthlyStats);

    new Chart(ctxMonthly, {
        type: 'bar',
        data: {
            labels: monthlyStats.map(stat => stat.month),
            datasets: [
                {
                    label: 'Recettes',
                    data: monthlyStats.map(stat => stat.income),
                    backgroundColor: 'rgba(25, 135, 84, 0.7)',
                    borderColor: 'rgba(25, 135, 84, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Dépenses',
                    data: monthlyStats.map(stat => stat.expense),
                    backgroundColor: 'rgba(220, 53, 69, 0.7)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Expenses Chart
    @if($expensesByCategory->count() > 0)
    const ctxExpenses = document.getElementById('expensesChart').getContext('2d');
    new Chart(ctxExpenses, {
        type: 'doughnut',
        data: {
            labels: @json($expensesByCategory->pluck('category')),
            datasets: [{
                data: @json($expensesByCategory->pluck('total')),
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                ],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
    @endif

    // Income Chart
    @if($incomeByCategory->count() > 0)
    const ctxIncome = document.getElementById('incomeChart').getContext('2d');
    new Chart(ctxIncome, {
        type: 'doughnut',
        data: {
            labels: @json($incomeByCategory->pluck('category')),
            datasets: [{
                data: @json($incomeByCategory->pluck('total')),
                backgroundColor: [
                    '#4BC0C0', '#FF6384', '#36A2EB', '#FFCE56', '#9966FF', '#FF9F40'
                ],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
    @endif
</script>
@endpush
@endsection
