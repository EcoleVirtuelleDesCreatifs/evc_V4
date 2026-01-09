@extends('layouts.admin')

@section('title', 'Gestion des Salaires')

@section('content')
<div class="container-fluid py-4">
    <div class="payroll-hero mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <div class="payroll-hero-kicker">Pilotage & performance</div>
                <h1 class="payroll-hero-title">Gestion des Salaires</h1>
                <div class="payroll-hero-subtitle">
                    Période: <span class="text-white">{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}/{{ $year }}</span>
                    <span class="mx-2">•</span>
                    Commission Commercial: <span class="text-white">{{ number_format(((int)($commercial_rate_bp ?? 0)) / 100, 2, ',', ' ') }}%</span>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.payroll.settings.index') }}" class="btn btn-outline-light">
                    <i class="fas fa-sliders-h me-2"></i>Paramètres
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Dashboard
                </a>
            </div>
        </div>

        <div class="payroll-hero-divider"></div>

        <div class="row g-3">
            <div class="col-md-3">
                <div class="kpi-card kpi-card-indigo">
                    <div class="kpi-icon"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="kpi-label">Admins suivis</div>
                        <div class="kpi-value">{{ (int)($totalAdmins ?? 0) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="kpi-card kpi-card-cyan">
                    <div class="kpi-icon"><i class="fas fa-chart-line"></i></div>
                    <div>
                        <div class="kpi-label">KPI moyen</div>
                        <div class="kpi-value">{{ (int)($avgKpi ?? 0) }}%</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="kpi-card kpi-card-emerald">
                    <div class="kpi-icon"><i class="fas fa-wallet"></i></div>
                    <div>
                        <div class="kpi-label">Gagné (mois)</div>
                        <div class="kpi-value">{{ number_format((int)($totalEarned ?? 0), 0, ',', ' ') }} <span class="kpi-unit">FCFA</span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="kpi-card kpi-card-amber">
                    <div class="kpi-icon"><i class="fas fa-hand-holding-usd"></i></div>
                    <div>
                        <div class="kpi-label">Commission (mois)</div>
                        <div class="kpi-value">{{ number_format((int)($totalCommercialCommission ?? 0), 0, ',', ' ') }} <span class="kpi-unit">FCFA</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px; overflow: hidden;">
                <div class="card-header" style="background: linear-gradient(135deg, rgba(59,130,246,0.25), rgba(14,165,233,0.10)); border-bottom: 1px solid #334155;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="fw-bold text-white"><i class="fas fa-trophy me-2"></i>Top KPI</div>
                        <div class="text-white-50" style="font-size: 0.9rem;">Top 5</div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach(($topKpi ?? []) as $i => $t)
                        <a class="top-row" href="{{ route('admin.payroll.admin.show', ['adminId' => (int)($t['id'] ?? 0)]) }}">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rank-pill">{{ (int)$i + 1 }}</div>
                                <div class="top-row-name">{{ $t['name'] ?? '' }}</div>
                            </div>
                            <div class="badge bg-info">{{ (int)($t['kpi_avg'] ?? 0) }}%</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px; overflow: hidden;">
                <div class="card-header" style="background: linear-gradient(135deg, rgba(16,185,129,0.22), rgba(34,197,94,0.10)); border-bottom: 1px solid #334155;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="fw-bold text-white"><i class="fas fa-bolt me-2"></i>Top Gagné</div>
                        <div class="text-white-50" style="font-size: 0.9rem;">Top 5</div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach(($topEarned ?? []) as $i => $t)
                        <a class="top-row" href="{{ route('admin.payroll.admin.show', ['adminId' => (int)($t['id'] ?? 0)]) }}">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rank-pill">{{ (int)$i + 1 }}</div>
                                <div class="top-row-name">{{ $t['name'] ?? '' }}</div>
                            </div>
                            <div class="fw-bold text-white">{{ number_format((int)($t['earned_total'] ?? 0), 0, ',', ' ') }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px; overflow: hidden;">
                <div class="card-header" style="background: linear-gradient(135deg, rgba(245,158,11,0.25), rgba(217,119,6,0.10)); border-bottom: 1px solid #334155;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="fw-bold text-white"><i class="fas fa-coins me-2"></i>Top Commission</div>
                        <div class="text-white-50" style="font-size: 0.9rem;">Top 5</div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach(($topCommission ?? []) as $i => $t)
                        <a class="top-row" href="{{ route('admin.payroll.admin.show', ['adminId' => (int)($t['id'] ?? 0)]) }}">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rank-pill">{{ (int)$i + 1 }}</div>
                                <div class="top-row-name">{{ $t['name'] ?? '' }}</div>
                            </div>
                            <div class="fw-bold text-white">{{ number_format((int)($t['commercial_commission_month'] ?? 0), 0, ',', ' ') }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px; overflow: hidden;">
        <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div class="fw-bold text-white">Salariés & performances</div>
                <div class="text-white-50" style="font-size: 0.9rem;">Forfait total: {{ number_format((int)($totalBase ?? 0), 0, ',', ' ') }} FCFA — Ventes (mois): {{ number_format((int)($totalCommercialSales ?? 0), 0, ',', ' ') }} FCFA</div>
            </div>
            <div class="insight-strip">
                <div class="insight-item">
                    <div class="insight-label">Objectif</div>
                    <div class="insight-value">Accélérer la performance KPI</div>
                </div>
                <div class="insight-item">
                    <div class="insight-label">Action rapide</div>
                    <div class="insight-value">Assigner profils & KPI</div>
                </div>
                <div class="insight-item">
                    <div class="insight-label">Contrôle</div>
                    <div class="insight-value">Visibilité salaires par admin</div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Admin</th>
                            <th>Profils</th>
                            <th style="width: 180px;">KPI</th>
                            <th class="text-end">Gagné</th>
                            <th class="text-end">Commission</th>
                            <th class="text-center">Visibilité</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($rows ?? []) as $row)
                            @php
                                $labels = (array)($row['profile_labels'] ?? []);
                                $kpi = (int)($row['kpi_avg'] ?? 0);
                                $canSee = (bool)($row['can_view_salary_amount'] ?? false);
                                $initial = strtoupper(substr((string)($row['name'] ?? 'A'), 0, 1));
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle">{{ $initial }}</div>
                                        <div>
                                            <div class="fw-bold">{{ $row['name'] ?? '' }}</div>
                                            <div class="text-muted" style="font-size: 0.9rem;">{{ $row['email'] ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if(count($labels) === 0)
                                        <span class="text-muted">Aucun</span>
                                    @else
                                        @foreach($labels as $lab)
                                            <span class="badge bg-secondary me-1">{{ $lab }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-between" style="font-size: 0.9rem;">
                                        <span class="text-muted">{{ $kpi }}%</span>
                                        <span class="text-muted">100%</span>
                                    </div>
                                    <div class="progress" style="height: 8px; border-radius: 999px;">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ max(0, min(100, $kpi)) }}%"></div>
                                    </div>
                                </td>
                                <td class="text-end fw-bold">{{ number_format((int)($row['earned_total'] ?? 0), 0, ',', ' ') }} FCFA</td>
                                <td class="text-end fw-bold">{{ number_format((int)($row['commercial_commission_month'] ?? 0), 0, ',', ' ') }} FCFA</td>
                                <td class="text-center">
                                    <form method="POST" action="{{ route('admin.payroll.admin.visibility', ['adminId' => (int)$row['id']]) }}" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="can_view_salary_amount" value="{{ $canSee ? 0 : 1 }}">
                                        <button type="submit" class="btn btn-sm {{ $canSee ? 'btn-success' : 'btn-outline-secondary' }}">
                                            {{ $canSee ? 'ON' : 'OFF' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.payroll.admin.show', ['adminId' => (int)$row['id']]) }}">
                                        <i class="fas fa-eye me-1"></i>Détails
                                    </a>
                                    <a class="btn btn-sm btn-primary" href="{{ route('admin.payroll.admin.profiles.edit', ['adminId' => (int)$row['id']]) }}">
                                        <i class="fas fa-user-tag me-1"></i>Profils
                                    </a>
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
    .top-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 12px;
        border-radius: 12px;
        text-decoration: none;
        color: inherit;
        border: 1px solid rgba(255,255,255,0.10);
        margin-top: 10px;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .top-row:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 26px rgba(0,0,0,0.08);
    }
    .top-row-name {
        font-weight: 600;
        color: #fff;
    }
    .rank-pill {
        min-width: 26px;
        height: 22px;
        padding: 0 8px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .78rem;
        font-weight: 800;
        color: rgba(226, 232, 240, 0.92);
        background: rgba(148, 163, 184, 0.14);
        border: 1px solid rgba(148, 163, 184, 0.22);
    }
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #fff;
        background: rgba(255, 255, 255, 0.20);
        border: 1px solid rgba(255, 255, 255, 0.18);
    }
    .insight-strip {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
        padding: 12px;
        border-radius: 14px;
        border: 1px solid rgba(148, 163, 184, 0.18);
        background: rgba(30, 41, 59, 0.55);
    }
    .insight-item {
        padding: 10px 12px;
        border-radius: 12px;
        background: rgba(15, 23, 42, 0.50);
        border: 1px solid rgba(148, 163, 184, 0.14);
    }
    .insight-label {
        color: rgba(226, 232, 240, 0.70);
        font-size: .78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        margin-bottom: 2px;
    }
    .insight-value {
        color: #fff;
        font-weight: 700;
        font-size: .95rem;
        line-height: 1.2;
    }
    @media (max-width: 992px) {
        .insight-strip { grid-template-columns: 1fr; }
    }
    .table { color: #e2e8f0; }
    .table thead th { color: #e2e8f0; }
    .table>:not(caption)>*>* {
        background-color: transparent;
        color: inherit;
        box-shadow: none;
    }
    .table thead th {
        background-color: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.18);
    }
    .table tbody td {
        background-color: rgba(15, 23, 42, 0.55);
        border-color: rgba(148, 163, 184, 0.12);
    }
    .table tbody tr:hover td {
        background-color: rgba(30, 41, 59, 0.70);
    }
</style>
@endpush
