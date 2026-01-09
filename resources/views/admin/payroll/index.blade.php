@extends('layouts.admin')

@section('title', 'Gestion des Salaires')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-white">Gestion des Salaires</h1>
            <div class="text-white-50">{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}/{{ $year }} — Commission Commercial: {{ number_format(((int)($commercial_rate_bp ?? 0)) / 100, 2, ',', ' ') }}%</div>
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

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ (int)($totalAdmins ?? 0) }}</h3>
                    <p class="stat-label">Admins suivis</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-cyan">
                <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ (int)($avgKpi ?? 0) }}%</h3>
                    <p class="stat-label">KPI moyen</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-success">
                <div class="stat-icon"><i class="fas fa-wallet"></i></div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ number_format((int)($totalEarned ?? 0), 0, ',', ' ') }}</h3>
                    <p class="stat-label">Gagné (mois) FCFA</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon"><i class="fas fa-hand-holding-usd"></i></div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ number_format((int)($totalCommercialCommission ?? 0), 0, ',', ' ') }}</h3>
                    <p class="stat-label">Commission (mois) FCFA</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px;">
                <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-bold text-white">Top KPI</div>
                        <div class="text-white-50" style="font-size: 0.9rem;">Top 5</div>
                    </div>
                </div>
                <div class="card-body">
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
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px;">
                <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-bold text-white">Top Gagné</div>
                        <div class="text-white-50" style="font-size: 0.9rem;">Top 5</div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach(($topEarned ?? []) as $t)
                        <a class="top-row" href="{{ route('admin.payroll.admin.show', ['adminId' => (int)($t['id'] ?? 0)]) }}">
                            <div class="top-row-name">{{ $t['name'] ?? '' }}</div>
                            <div class="fw-bold text-white">{{ number_format((int)($t['earned_total'] ?? 0), 0, ',', ' ') }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px;">
                <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-bold text-white">Top Commission</div>
                        <div class="text-white-50" style="font-size: 0.9rem;">Top 5</div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach(($topCommission ?? []) as $t)
                        <a class="top-row" href="{{ route('admin.payroll.admin.show', ['adminId' => (int)($t['id'] ?? 0)]) }}">
                            <div class="top-row-name">{{ $t['name'] ?? '' }}</div>
                            <div class="fw-bold text-white">{{ number_format((int)($t['commercial_commission_month'] ?? 0), 0, ',', ' ') }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px;">
        <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div class="fw-bold text-white">Salariés & performances</div>
                <div class="text-white-50" style="font-size: 0.9rem;">Forfait total: {{ number_format((int)($totalBase ?? 0), 0, ',', ' ') }} FCFA — Ventes (mois): {{ number_format((int)($totalCommercialSales ?? 0), 0, ',', ' ') }} FCFA</div>
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
    .stat-card {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(30, 60, 114, 0.3);
    }
    .stat-card-primary { background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%); }
    .stat-card-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .stat-card-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .stat-card-cyan { background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%); }
    .stat-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }
    .stat-content { flex: 1; }
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
    }
    .stat-label {
        margin: 0;
        opacity: 0.9;
        font-size: 0.95rem;
    }
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
    .table { color: #e2e8f0; }
    .table thead th { color: #e2e8f0; }
</style>
@endpush
