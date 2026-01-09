@extends('layouts.admin')

@section('title', 'Gestion des Salaires')

@section('content')
<div class="container-fluid">
    <div class="payroll-hero mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <div class="payroll-hero-title">Gestion des Salaires</div>
                <div class="payroll-hero-subtitle">{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}/{{ $year }} — Commission Commercial: {{ number_format(((int)($commercial_rate_bp ?? 0)) / 100, 2, ',', ' ') }}%</div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Dashboard
                </a>
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
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon bg-primary"><i class="fas fa-users"></i></div>
                <div>
                    <div class="stat-card-label">Admins suivis</div>
                    <div class="stat-card-value">{{ (int)($totalAdmins ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon bg-info"><i class="fas fa-chart-line"></i></div>
                <div>
                    <div class="stat-card-label">KPI moyen</div>
                    <div class="stat-card-value">{{ (int)($avgKpi ?? 0) }}%</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon bg-success"><i class="fas fa-wallet"></i></div>
                <div>
                    <div class="stat-card-label">Gagné (mois)</div>
                    <div class="stat-card-value">{{ number_format((int)($totalEarned ?? 0), 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon bg-warning text-dark"><i class="fas fa-hand-holding-usd"></i></div>
                <div>
                    <div class="stat-card-label">Commission (mois)</div>
                    <div class="stat-card-value">{{ number_format((int)($totalCommercialCommission ?? 0), 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-bold">Top KPI</div>
                        <div class="text-muted" style="font-size: 0.9rem;">Top 5</div>
                    </div>
                    @foreach(($topKpi ?? []) as $t)
                        <a class="top-row" href="{{ route('admin.payroll.admin.show', ['adminId' => (int)($t['id'] ?? 0)]) }}">
                            <div class="top-row-name">{{ $t['name'] ?? '' }}</div>
                            <div class="badge bg-info">{{ (int)($t['kpi_avg'] ?? 0) }}%</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-bold">Top Gagné</div>
                        <div class="text-muted" style="font-size: 0.9rem;">Top 5</div>
                    </div>
                    @foreach(($topEarned ?? []) as $t)
                        <a class="top-row" href="{{ route('admin.payroll.admin.show', ['adminId' => (int)($t['id'] ?? 0)]) }}">
                            <div class="top-row-name">{{ $t['name'] ?? '' }}</div>
                            <div class="fw-bold">{{ number_format((int)($t['earned_total'] ?? 0), 0, ',', ' ') }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-bold">Top Commission</div>
                        <div class="text-muted" style="font-size: 0.9rem;">Top 5</div>
                    </div>
                    @foreach(($topCommission ?? []) as $t)
                        <a class="top-row" href="{{ route('admin.payroll.admin.show', ['adminId' => (int)($t['id'] ?? 0)]) }}">
                            <div class="top-row-name">{{ $t['name'] ?? '' }}</div>
                            <div class="fw-bold">{{ number_format((int)($t['commercial_commission_month'] ?? 0), 0, ',', ' ') }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div class="fw-bold">Salariés & performances</div>
                <div class="text-muted" style="font-size: 0.9rem;">Forfait total: {{ number_format((int)($totalBase ?? 0), 0, ',', ' ') }} FCFA — Ventes (mois): {{ number_format((int)($totalCommercialSales ?? 0), 0, ',', ' ') }} FCFA</div>
            </div>
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
    .payroll-hero {
        background: linear-gradient(135deg, #0b5ed7 0%, #6f42c1 50%, #d63384 100%);
        border-radius: 18px;
        padding: 22px 22px;
        color: #fff;
        box-shadow: 0 18px 45px rgba(13, 110, 253, 0.18);
    }
    .payroll-hero-title {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: -0.02em;
    }
    .payroll-hero-subtitle {
        opacity: 0.9;
        font-size: 0.95rem;
    }
    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 16px;
        display: flex;
        gap: 12px;
        align-items: center;
        border: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
    }
    .stat-card-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        flex: 0 0 auto;
    }
    .stat-card-label {
        font-size: 0.9rem;
        color: rgba(0,0,0,0.55);
    }
    .stat-card-value {
        font-weight: 700;
        font-size: 1.1rem;
        color: rgba(0,0,0,0.85);
    }
    .top-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 12px;
        border-radius: 12px;
        text-decoration: none;
        color: inherit;
        border: 1px solid rgba(0,0,0,0.06);
        margin-top: 10px;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .top-row:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 26px rgba(0,0,0,0.08);
    }
    .top-row-name {
        font-weight: 600;
        color: rgba(0,0,0,0.82);
    }
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #0b5ed7;
        background: rgba(13, 110, 253, 0.10);
        border: 1px solid rgba(13, 110, 253, 0.18);
    }
</style>
@endpush
