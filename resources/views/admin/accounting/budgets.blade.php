@extends('layouts.admin')

@section('title', 'Budget Prévisionnel')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Budget Prévisionnel - {{ $year }}</h1>
        <div class="d-flex align-items-center gap-3">
            <form action="{{ route('admin.accounting.budgets') }}" method="GET" class="d-flex align-items-center gap-2">
                <select name="year" class="form-select" onchange="this.form.submit()">
                    @for($y = date('Y'); $y >= 2023; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
            <a href="{{ route('admin.accounting.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            <a href="{{ route('admin.accounting.budgets.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Définir un budget
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Budget Card Summary -->
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Suivi Budgétaire (Dépenses)</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Catégorie</th>
                                <th class="text-end">Budget Alloué</th>
                                <th class="text-end">Dépenses Réelles</th>
                                <th>Progression</th>
                                <th class="text-end">Reste</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $categories = [
                                    'Loyer', 'Salaires', 'Matériel', 'Logiciels',
                                    'Marketing', 'Impôts', 'Divers'
                                ];
                                $totalBudget = 0;
                                $totalActual = 0;
                            @endphp

                            @foreach($categories as $category)
                                @php
                                    $budget = $budgets->where('category', $category)->where('type', 'expense')->first();
                                    $budgetAmount = $budget ? $budget->amount : 0;
                                    $actual = $actualExpenses[$category] ?? 0;

                                    $totalBudget += $budgetAmount;
                                    $totalActual += $actual;

                                    $percentage = $budgetAmount > 0 ? ($actual / $budgetAmount) * 100 : 0;
                                    $color = $percentage > 100 ? 'danger' : ($percentage > 80 ? 'warning' : 'success');
                                @endphp
                                <tr>
                                    <td class="fw-medium">{{ $category }}</td>
                                    <td class="text-end font-monospace">{{ number_format($budgetAmount, 0, ',', ' ') }}</td>
                                    <td class="text-end font-monospace">{{ number_format($actual, 0, ',', ' ') }}</td>
                                    <td style="width: 30%;">
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-{{ $color }}" role="progressbar"
                                                style="width: {{ min($percentage, 100) }}%;"
                                                aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <div class="small text-muted text-end mt-1">{{ number_format($percentage, 1) }}%</div>
                                    </td>
                                    <td class="text-end font-monospace {{ $budgetAmount - $actual < 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($budgetAmount - $actual, 0, ',', ' ') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td>TOTAL</td>
                                <td class="text-end">{{ number_format($totalBudget, 0, ',', ' ') }}</td>
                                <td class="text-end">{{ number_format($totalActual, 0, ',', ' ') }}</td>
                                <td></td>
                                <td class="text-end">{{ number_format($totalBudget - $totalActual, 0, ',', ' ') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
