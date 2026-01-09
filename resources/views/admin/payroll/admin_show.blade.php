@extends('layouts.admin')

@section('title', 'Détail Salaire')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1">{{ $row['name'] ?? '' }}</h1>
            <div class="text-muted">{{ $row['email'] ?? '' }} — {{ str_pad($month, 2, '0', STR_PAD_LEFT) }}/{{ $year }}</div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.payroll.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            <a href="{{ route('admin.payroll.admin.profiles.edit', ['adminId' => (int)($row['id'] ?? 0)]) }}" class="btn btn-primary">
                <i class="fas fa-user-tag me-2"></i>Attribuer profils
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-body">
                    <div class="text-muted">KPI moyen</div>
                    <div class="fs-3 fw-bold">{{ (int)($row['kpi_avg'] ?? 0) }}%</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-body">
                    <div class="text-muted">Forfait total</div>
                    <div class="fs-3 fw-bold">{{ number_format((int)($row['base_total'] ?? 0), 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-body">
                    <div class="text-muted">Gagné (mois)</div>
                    <div class="fs-3 fw-bold">{{ number_format((int)($row['earned_total'] ?? 0), 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-body">
                    <div class="text-muted">Commission (mois)</div>
                    <div class="fs-3 fw-bold">{{ number_format((int)($row['commercial_commission_month'] ?? 0), 0, ',', ' ') }} FCFA</div>
                    <div class="text-muted" style="font-size: 0.9rem;">Ventes: {{ number_format((int)($row['commercial_sales_month'] ?? 0), 0, ',', ' ') }} FCFA — Taux: {{ number_format(((int)($commercial_rate_bp ?? 0)) / 100, 2, ',', ' ') }}%</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
        <div class="card-body">
            <div class="fw-bold mb-3">Détail KPI par profil</div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Profil</th>
                            <th class="text-center">KPI</th>
                            <th class="text-center">Pénalité</th>
                            <th class="text-center">Score</th>
                            <th class="text-end">Forfait</th>
                            <th class="text-end">Gagné</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach((array)($row['breakdown'] ?? []) as $b)
                            <tr>
                                <td class="fw-bold">{{ $b['label'] ?? '' }}</td>
                                <td class="text-center"><span class="badge bg-info">{{ (int)($b['kpi'] ?? 0) }}%</span></td>
                                <td class="text-center"><span class="badge bg-warning text-dark">-{{ (int)($b['penalty'] ?? 0) }}</span></td>
                                <td class="text-center"><span class="badge bg-success">{{ (int)($b['final_score'] ?? 0) }}%</span></td>
                                <td class="text-end">{{ number_format((int)($b['base_monthly_amount'] ?? 0), 0, ',', ' ') }} FCFA</td>
                                <td class="text-end fw-bold">{{ number_format((int)($b['earned'] ?? 0), 0, ',', ' ') }} FCFA</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
