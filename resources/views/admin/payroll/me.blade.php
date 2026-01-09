@extends('layouts.admin')

@section('title', 'Gestion des Salaires')

@section('content')
<div class="container-fluid py-4">
    <div class="payroll-hero mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <div class="payroll-hero-kicker">Mon espace</div>
                <h1 class="payroll-hero-title">Gestion des Salaires</h1>
                <div class="payroll-hero-subtitle">
                    Période: <span class="text-white">{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}/{{ $year }}</span>
                    <span class="mx-2">•</span>
                    {{ $admin->name ?? '' }} — {{ $admin->email ?? '' }}
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Dashboard
                </a>
            </div>
        </div>

        <div class="payroll-hero-divider"></div>

        @php
            $canSee = (bool)($admin->can_view_salary_amount ?? false);
        @endphp

        <div class="row g-3">
            <div class="col-md-3">
                <div class="kpi-card kpi-card-indigo">
                    <div class="kpi-icon"><i class="fas fa-user-tag"></i></div>
                    <div>
                        <div class="kpi-label">Profils attribués</div>
                        <div class="kpi-value">{{ (int)(($profiles ?? collect())->count()) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="kpi-card kpi-card-cyan">
                    <div class="kpi-icon"><i class="fas fa-chart-line"></i></div>
                    <div>
                        <div class="kpi-label">KPI moyen</div>
                        <div class="kpi-value">{{ (int)($kpiAvg ?? 0) }}%</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="kpi-card kpi-card-emerald">
                    <div class="kpi-icon"><i class="fas fa-wallet"></i></div>
                    <div>
                        <div class="kpi-label">Gagné (mois)</div>
                        <div class="kpi-value">
                            @if($canSee)
                                {{ number_format((int)($earnedTotal ?? 0), 0, ',', ' ') }} <span class="kpi-unit">FCFA</span>
                            @else
                                <span class="kpi-unit">Masqué</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="kpi-card kpi-card-amber">
                    <div class="kpi-icon"><i class="fas fa-coins"></i></div>
                    <div>
                        <div class="kpi-label">Commission (mois)</div>
                        <div class="kpi-value">
                            @if($canSee)
                                {{ number_format((int)($commercialCommissionMonth ?? 0), 0, ',', ' ') }} <span class="kpi-unit">FCFA</span>
                            @else
                                <span class="kpi-unit">Masqué</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px; overflow: hidden;">
                <div class="card-header" style="background: linear-gradient(135deg, rgba(99,102,241,0.25), rgba(59,130,246,0.12)); border-bottom: 1px solid #334155;">
                    <div class="fw-bold text-white"><i class="fas fa-id-badge me-2"></i>Profils attribués</div>
                </div>
                <div class="card-body">
                    @if(($profiles ?? collect())->count() === 0)
                        <div class="text-white-50">Aucun profil attribué</div>
                    @else
                        @foreach($profiles as $p)
                            <span class="badge bg-secondary me-1 mb-1">{{ $p->label }}</span>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px; overflow: hidden;">
                <div class="card-header" style="background: linear-gradient(135deg, rgba(16,185,129,0.22), rgba(34,197,94,0.10)); border-bottom: 1px solid #334155;">
                    <div class="fw-bold text-white"><i class="fas fa-wallet me-2"></i>Forfaits (profils)</div>
                </div>
                <div class="card-body">
                    @if(!$canSee)
                        <div class="text-white-50">Montant masqué (autorisation requise)</div>
                    @else
                        <div class="fs-3 fw-bold text-white">{{ number_format((int)($baseTotal ?? 0), 0, ',', ' ') }} FCFA</div>
                        <div class="text-white-50">Somme des forfaits mensuels attribués</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px; overflow: hidden;">
                <div class="card-header" style="background: linear-gradient(135deg, rgba(245,158,11,0.25), rgba(217,119,6,0.10)); border-bottom: 1px solid #334155;">
                    <div class="fw-bold text-white"><i class="fas fa-hand-holding-usd me-2"></i>Commission Commercial</div>
                </div>
                <div class="card-body">
                    @if($canSee)
                        <div class="fs-3 fw-bold text-white">{{ number_format((int)($commercialCommissionMonth ?? 0), 0, ',', ' ') }} FCFA</div>
                        <div class="text-white-50">Ventes: {{ number_format((int)($commercialSalesMonth ?? 0), 0, ',', ' ') }} FCFA</div>
                    @else
                        <div class="text-white-50">Montant masqué (autorisation requise)</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px; overflow: hidden;">
        <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div class="fw-bold text-white">Performance (KPI)</div>
                <div class="text-white-50" style="font-size: 0.9rem;">
                    KPI moyen: {{ (int)($kpiAvg ?? 0) }}%
                    <span class="mx-2">•</span>
                    Gagné (mois):
                    @if($canSee)
                        {{ number_format((int)($earnedTotal ?? 0), 0, ',', ' ') }} FCFA
                    @else
                        Masqué
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
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
                                <td class="fw-bold text-white">{{ $b['label'] ?? '' }}</td>
                                <td class="text-center"><span class="badge bg-info">{{ (int)($b['kpi'] ?? 0) }}%</span></td>
                                <td class="text-center"><span class="badge bg-warning text-dark">-{{ (int)($b['penalty'] ?? 0) }}</span></td>
                                <td class="text-center"><span class="badge bg-success">{{ (int)($b['final_score'] ?? 0) }}%</span></td>
                                <td class="text-end">
                                    @if($canSee)
                                        <span class="text-white">{{ number_format((int)($b['base_monthly_amount'] ?? 0), 0, ',', ' ') }} FCFA</span>
                                    @else
                                        <span class="text-white-50">Masqué</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold">
                                    @if($canSee)
                                        <span class="text-white">{{ number_format((int)($b['earned'] ?? 0), 0, ',', ' ') }} FCFA</span>
                                    @else
                                        <span class="text-white-50">Masqué</span>
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

@push('styles')
<style>
    body {
        background-color: #0f172a;
    }
    .payroll-hero {
        background: radial-gradient(1200px 500px at 20% -20%, rgba(59, 130, 246, 0.35), transparent 60%),
                    radial-gradient(900px 420px at 90% 0%, rgba(16, 185, 129, 0.22), transparent 55%),
                    linear-gradient(135deg, rgba(30, 41, 59, 0.85), rgba(15, 23, 42, 0.95));
        border: 1px solid rgba(148, 163, 184, 0.22);
        border-radius: 18px;
        padding: 18px;
        overflow: hidden;
    }
    .payroll-hero-kicker {
        color: rgba(226, 232, 240, 0.75);
        letter-spacing: .08em;
        text-transform: uppercase;
        font-size: .78rem;
        font-weight: 700;
    }
    .payroll-hero-title {
        color: #fff;
        margin: 2px 0 4px;
        font-weight: 800;
        letter-spacing: -0.02em;
    }
    .payroll-hero-subtitle {
        color: rgba(226, 232, 240, 0.75);
        font-size: .95rem;
    }
    .payroll-hero-divider {
        height: 1px;
        background: rgba(148, 163, 184, 0.22);
        margin: 14px 0 16px;
    }

    .kpi-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 14px;
        border-radius: 16px;
        border: 1px solid rgba(148, 163, 184, 0.18);
        background: rgba(15, 23, 42, 0.60);
        backdrop-filter: blur(8px);
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 38px rgba(0,0,0,0.24);
    }
    .kpi-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.10);
        font-size: 1.15rem;
    }
    .kpi-label {
        color: rgba(226, 232, 240, 0.75);
        font-size: .88rem;
        font-weight: 600;
        margin-bottom: 2px;
    }
    .kpi-value {
        color: #fff;
        font-size: 1.45rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        line-height: 1.1;
    }
    .kpi-unit {
        font-size: .85rem;
        font-weight: 700;
        color: rgba(226, 232, 240, 0.75);
    }
    .kpi-card-indigo .kpi-icon { background: linear-gradient(135deg, rgba(99,102,241,0.95), rgba(59,130,246,0.90)); }
    .kpi-card-cyan .kpi-icon { background: linear-gradient(135deg, rgba(14,165,233,0.95), rgba(34,211,238,0.80)); }
    .kpi-card-emerald .kpi-icon { background: linear-gradient(135deg, rgba(16,185,129,0.95), rgba(34,197,94,0.80)); }
    .kpi-card-amber .kpi-icon { background: linear-gradient(135deg, rgba(245,158,11,0.95), rgba(217,119,6,0.85)); }

    .table { color: #e2e8f0; }
    .table thead th { color: #e2e8f0; }
</style>
@endpush
