@extends('layouts.admin')

@section('title', 'Conditions KPI')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1 text-white">Conditions KPI</h1>
            <div class="text-white-50">Profil: {{ $profile->label ?? '' }} — Code: {{ $profile->code ?? '' }}</div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.payroll.settings.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            <a href="{{ route('admin.payroll.settings.profile.edit', ['profileId' => (int)($profile->id ?? 0)]) }}" class="btn btn-outline-primary">
                <i class="fas fa-pen me-2"></i>Forfait & commission
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px; overflow: hidden;">
        <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <div class="fw-bold text-white"><i class="fas fa-sliders-h me-2"></i>Tâches & objectifs</div>
        </div>
        <div class="card-body">
            <div class="card mb-3" style="background-color: #0f172a; border: 1px solid #334155; border-radius: 14px; overflow: hidden;">
                <div class="card-header" style="background-color: #0b1220; border-bottom: 1px solid #334155;">
                    <div class="fw-bold text-white"><i class="fas fa-list-check me-2"></i>Catalogue des tâches KPI</div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.payroll.settings.profile.tasks.catalog.store', ['profileId' => (int)($profile->id ?? 0)]) }}">
                        @csrf

                        @php
                            $selected = collect($selectedCatalogKeys ?? [])->map(fn ($v) => (string)$v)->all();
                        @endphp

                        <div class="row g-2">
                            @foreach(($kpiCatalog ?? collect()) as $c)
                                @php
                                    $k = (string)($c['key'] ?? '');
                                    $isChecked = in_array($k, $selected, true);
                                @endphp
                                <div class="col-md-6">
                                    <label class="d-flex align-items-start gap-2 p-3" style="border: 1px solid #334155; border-radius: 12px; background-color: rgba(15, 23, 42, 0.35);">
                                        <input type="checkbox" class="form-check-input mt-1" name="task_keys[]" value="{{ $k }}" {{ $isChecked ? 'checked disabled' : '' }}>
                                        <div>
                                            <div class="fw-bold text-white">{{ $c['label'] ?? '' }}</div>
                                            <div class="text-white-50" style="font-size: 0.9rem;">Fréquence: {{ $c['default_recurrence'] ?? 'monthly' }} — Deadline: {{ (int)($c['default_deadline_hours'] ?? 0) }}h</div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Ajouter les tâches sélectionnées
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if(($taskTypes ?? collect())->count() === 0)
                <div class="text-white-50">Aucune tâche configurée pour ce profil.</div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Tâche</th>
                                <th class="text-center">Actif</th>
                                <th class="text-center">Fréquence</th>
                                <th class="text-end">Objectif/mois</th>
                                <th class="text-end">Poids</th>
                                <th class="text-end">Deadline (h)</th>
                                <th class="text-center">Critique</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($taskTypes as $t)
                                <tr>
                                    <form method="POST" action="{{ route('admin.payroll.settings.task.update', ['taskTypeId' => (int)($t->id ?? 0)]) }}">
                                        @csrf
                                        <td style="min-width: 260px;">
                                            <input class="form-control" name="label" value="{{ (string)($t->label ?? '') }}" required>
                                        </td>
                                        <td class="text-center">
                                            <select class="form-select" name="is_active" style="min-width: 110px;">
                                                <option value="1" {{ ((int)($t->is_active ?? 1) === 1) ? 'selected' : '' }}>Oui</option>
                                                <option value="0" {{ ((int)($t->is_active ?? 1) === 0) ? 'selected' : '' }}>Non</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <select class="form-select" name="recurrence" style="min-width: 140px;">
                                                @php $r = (string)($t->recurrence ?? 'monthly'); @endphp
                                                <option value="daily" {{ $r === 'daily' ? 'selected' : '' }}>Daily</option>
                                                <option value="weekly" {{ $r === 'weekly' ? 'selected' : '' }}>Weekly</option>
                                                <option value="monthly" {{ $r === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                            </select>
                                        </td>
                                        <td class="text-end">
                                            <input type="number" class="form-control" name="expected_per_month" value="{{ (int)($t->expected_per_month ?? 0) }}" min="0" style="min-width: 140px;">
                                        </td>
                                        <td class="text-end">
                                            <input type="number" class="form-control" name="weight" value="{{ (int)($t->weight ?? 10) }}" min="0" style="min-width: 120px;">
                                        </td>
                                        <td class="text-end">
                                            <input type="number" class="form-control" name="deadline_hours" value="{{ (int)($t->deadline_hours ?? 0) }}" min="0" style="min-width: 140px;">
                                        </td>
                                        <td class="text-center">
                                            <select class="form-select" name="is_critical" style="min-width: 120px;">
                                                <option value="0" {{ ((int)($t->is_critical ?? 0) === 0) ? 'selected' : '' }}>Non</option>
                                                <option value="1" {{ ((int)($t->is_critical ?? 0) === 1) ? 'selected' : '' }}>Oui</option>
                                            </select>
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-success">
                                                <i class="fas fa-save me-1"></i>Enregistrer
                                            </button>
                                        </td>
                                    </form>
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

@push('styles')
<style>
    body { background-color: #0f172a; }
    .form-label, .form-text { color: #e2e8f0; }
    .table { color: #e2e8f0; }
    .table thead th { color: #e2e8f0; }
</style>
@endpush
