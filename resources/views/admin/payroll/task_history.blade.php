@extends('layouts.admin')

@section('title', 'Historiques')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="payroll-hero mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <div class="payroll-hero-kicker">Pilotage & traçabilité</div>
                <h1 class="payroll-hero-title">Historiques</h1>
                <div class="payroll-hero-subtitle">
                    Liste des tâches enregistrées pour les salariés
                    <span class="mx-2">•</span>
                    Période:
                    <span class="text-white">{{ !empty($filters['from'] ?? null) ? $filters['from'] : '—' }}</span>
                    <span class="text-white-50">→</span>
                    <span class="text-white">{{ !empty($filters['to'] ?? null) ? $filters['to'] : '—' }}</span>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.payroll.index') }}" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-2"></i>Salaires
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
                    <div class="kpi-icon"><i class="fas fa-list"></i></div>
                    <div>
                        <div class="kpi-label">Total entrées</div>
                        <div class="kpi-value">{{ ($logs ?? collect())->count() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="kpi-card kpi-card-cyan">
                    <div class="kpi-icon"><i class="fas fa-layer-group"></i></div>
                    <div>
                        <div class="kpi-label">Total quantité</div>
                        <div class="kpi-value">{{ number_format((int)($totalQuantity ?? 0), 0, ',', ' ') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="kpi-card kpi-card-emerald">
                    <div class="kpi-icon"><i class="fas fa-clock"></i></div>
                    <div>
                        <div class="kpi-label">Heures estimées</div>
                        <div class="kpi-value">{{ number_format((int)($totalEstimatedHours ?? 0), 0, ',', ' ') }} <span class="kpi-unit">h</span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="kpi-card kpi-card-amber">
                    <div class="kpi-icon"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="kpi-label">Salariés</div>
                        <div class="kpi-value">{{ ($admins ?? collect())->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px; overflow: hidden;">
        <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <div class="fw-bold text-white"><i class="fas fa-filter me-2"></i>Filtres</div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.payroll.task-history.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label text-white">Salarié</label>
                    <select name="admin_id" class="form-select" style="background-color:#0f172a; border: 1px solid #334155; color:#e2e8f0;">
                        <option value="">Tous</option>
                        @foreach(($admins ?? collect()) as $a)
                            <option value="{{ (int)$a->id }}" {{ ((string)($filters['admin_id'] ?? '') === (string)$a->id) ? 'selected' : '' }}>
                                {{ $a->name }} ({{ $a->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label text-white">Type de tâche</label>
                    <select name="task_type_id" class="form-select" style="background-color:#0f172a; border: 1px solid #334155; color:#e2e8f0;">
                        <option value="">Tous</option>
                        @foreach(($taskTypes ?? collect()) as $t)
                            <option value="{{ (int)$t->id }}" {{ ((string)($filters['task_type_id'] ?? '') === (string)$t->id) ? 'selected' : '' }}>
                                {{ $t->label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label text-white">Du</label>
                    <input type="datetime-local" name="from" value="{{ (string)($filters['from'] ?? '') }}" class="form-control" style="background-color:#0f172a; border: 1px solid #334155; color:#e2e8f0;">
                </div>

                <div class="col-md-2">
                    <label class="form-label text-white">Au</label>
                    <input type="datetime-local" name="to" value="{{ (string)($filters['to'] ?? '') }}" class="form-control" style="background-color:#0f172a; border: 1px solid #334155; color:#e2e8f0;">
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.payroll.task-history.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                    <button class="btn btn-primary"><i class="fas fa-search me-2"></i>Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px; overflow: hidden;">
        <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="fw-bold text-white"><i class="fas fa-list me-2"></i>Historique des tâches</div>
                <div class="text-white-50" style="font-size: 0.9rem;">Tri: plus récent → plus ancien</div>
            </div>
        </div>
        <div class="card-body">
            @if(($logs ?? collect())->count() === 0)
                <div class="text-white-50">Aucune tâche trouvée pour les filtres sélectionnés.</div>
            @else
                <div class="table-responsive">
                    <table id="taskHistoryTable" class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Salarié</th>
                                <th>Tâche</th>
                                <th class="text-end">Quantité</th>
                                <th class="text-end">Heures (estim.)</th>
                                <th>Date / Heure</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                @php
                                    $qty = (int)($log->quantity ?? 0);
                                    $dh = (int)($log->deadline_hours ?? 0);
                                    $estimated = ($dh > 0) ? ($qty * $dh) : null;
                                @endphp
                                <tr>
                                    <td>{{ (int)$log->id }}</td>
                                    <td>
                                        <div class="fw-bold text-white">{{ $log->admin_name ?? '—' }}</div>
                                        <div class="text-muted" style="font-size: 0.9rem;">{{ $log->admin_email ?? '' }}</div>
                                    </td>
                                    <td class="fw-semibold">{{ $log->task_label ?? '—' }}</td>
                                    <td class="text-end fw-bold">{{ number_format($qty, 0, ',', ' ') }}</td>
                                    <td class="text-end">
                                        @if($estimated === null)
                                            <span class="text-muted">—</span>
                                        @else
                                            <span class="fw-bold text-white">{{ number_format($estimated, 0, ',', ' ') }} h</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->performed_at ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script>
    $(function () {
        const $table = $('#taskHistoryTable');
        if (!$table.length) return;

        $table.DataTable({
            responsive: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Tous']],
            order: [[5, 'desc']],
            language: {
                search: 'Rechercher :',
                lengthMenu: 'Afficher _MENU_ lignes',
                info: 'Affichage de _START_ à _END_ sur _TOTAL_ entrées',
                infoEmpty: 'Aucune entrée',
                infoFiltered: '(filtré de _MAX_ entrées au total)',
                zeroRecords: 'Aucun résultat trouvé',
                paginate: {
                    first: 'Premier',
                    last: 'Dernier',
                    next: 'Suivant',
                    previous: 'Précédent'
                }
            }
        });
    });
</script>
@endpush

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
