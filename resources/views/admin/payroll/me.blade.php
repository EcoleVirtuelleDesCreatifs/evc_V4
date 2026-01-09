@extends('layouts.admin')

@section('title', 'Gestion des Salaires')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gestion des Salaires</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Retour Dashboard</a>
    </div>

    @php
        $canSee = (bool)($admin->can_view_salary_amount ?? false);
    @endphp

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <div class="text-muted">Période</div>
                    <div class="fw-bold">{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}/{{ $year }}</div>
                </div>
                <div class="text-muted">
                    {{ $admin->name ?? '' }} — {{ $admin->email ?? '' }}
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="text-muted mb-2">Profils attribués</div>
            @if(($profiles ?? collect())->count() === 0)
                <div class="text-muted">Aucun profil attribué</div>
            @else
                @foreach($profiles as $p)
                    <span class="badge bg-secondary me-1">{{ $p->label }}</span>
                @endforeach
            @endif
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">Commission Commercial (mois)</div>
                    @if($canSee)
                        <div class="fs-3 fw-bold">{{ number_format((int)($commercialCommissionMonth ?? 0), 0, ',', ' ') }} FCFA</div>
                        <div class="text-muted">Ventes: {{ number_format((int)($commercialSalesMonth ?? 0), 0, ',', ' ') }} FCFA</div>
                    @else
                        <div class="text-muted">Montant masqué (autorisation requise)</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">Forfaits (profils)</div>
                    @if(!$canSee)
                        <div class="text-muted">Montant masqué (autorisation requise)</div>
                    @else
                        <div class="fs-3 fw-bold">{{ number_format((int)($baseTotal ?? 0), 0, ',', ' ') }} FCFA</div>
                        <div class="text-muted">Somme des forfaits mensuels attribués</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <div class="text-muted mb-2">Performance (KPI)</div>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <span class="text-muted">KPI moyen:</span>
                    <span class="badge bg-info">{{ (int)($kpiAvg ?? 0) }}%</span>
                </div>
                <div>
                    <span class="text-muted">Gagné (mois):</span>
                    @if($canSee)
                        <span class="fw-bold">{{ number_format((int)($earnedTotal ?? 0), 0, ',', ' ') }} FCFA</span>
                    @else
                        <span class="text-muted">Masqué</span>
                    @endif
                </div>
            </div>

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
                        @foreach((array)($breakdown ?? []) as $b)
                            <tr>
                                <td>{{ $b['label'] ?? '' }}</td>
                                <td class="text-center"><span class="badge bg-info">{{ (int)($b['kpi'] ?? 0) }}%</span></td>
                                <td class="text-center"><span class="badge bg-warning text-dark">-{{ (int)($b['penalty'] ?? 0) }}</span></td>
                                <td class="text-center"><span class="badge bg-success">{{ (int)($b['final_score'] ?? 0) }}%</span></td>
                                <td class="text-end">
                                    @if($canSee)
                                        {{ number_format((int)($b['base_monthly_amount'] ?? 0), 0, ',', ' ') }} FCFA
                                    @else
                                        <span class="text-muted">Masqué</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold">
                                    @if($canSee)
                                        {{ number_format((int)($b['earned'] ?? 0), 0, ',', ' ') }} FCFA
                                    @else
                                        <span class="text-muted">Masqué</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
