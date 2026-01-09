@extends('layouts.admin')

@section('title', 'Historiques')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1 text-white">Historiques</h1>
            <div class="text-white-50">Liste des tâches enregistrées pour les salariés</div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Dashboard
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px;">
                <div class="card-body">
                    <div class="text-white-50">Total entrées</div>
                    <div class="fs-3 fw-bold text-white">{{ ($logs ?? collect())->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px;">
                <div class="card-body">
                    <div class="text-white-50">Total quantité</div>
                    <div class="fs-3 fw-bold text-white">{{ number_format((int)($totalQuantity ?? 0), 0, ',', ' ') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px;">
                <div class="card-body">
                    <div class="text-white-50">Heures estimées</div>
                    <div class="fs-3 fw-bold text-white">{{ number_format((int)($totalEstimatedHours ?? 0), 0, ',', ' ') }} h</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px;">
                <div class="card-body">
                    <div class="text-white-50">Période</div>
                    <div class="fw-semibold text-white" style="font-size: 0.95rem;">
                        {{ !empty($filters['from'] ?? null) ? $filters['from'] : '—' }}
                        <span class="text-white-50">→</span>
                        {{ !empty($filters['to'] ?? null) ? $filters['to'] : '—' }}
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
                    <a href="{{ route('admin.payroll.task-history.index') }}" class="btn btn-outline-secondary">
                        Réinitialiser
                    </a>
                    <button class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px; overflow: hidden;">
        <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <div class="fw-bold text-white"><i class="fas fa-list me-2"></i>Historique des tâches</div>
        </div>
        <div class="card-body">
            @if(($logs ?? collect())->count() === 0)
                <div class="text-white-50">Aucune tâche trouvée pour les filtres sélectionnés.</div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle" style="color: #e2e8f0;">
                        <thead>
                            <tr style="color:#94a3b8;">
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
                                        <div class="fw-semibold">{{ $log->admin_name ?? '—' }}</div>
                                        <div class="text-white-50" style="font-size: 0.9rem;">{{ $log->admin_email ?? '' }}</div>
                                    </td>
                                    <td>{{ $log->task_label ?? '—' }}</td>
                                    <td class="text-end">{{ number_format($qty, 0, ',', ' ') }}</td>
                                    <td class="text-end">
                                        @if($estimated === null)
                                            <span class="text-white-50">—</span>
                                        @else
                                            {{ number_format($estimated, 0, ',', ' ') }} h
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
