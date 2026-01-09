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
                    <div class="fw-bold text-white"><i class="fas fa-plus me-2"></i>Ajouter une condition KPI</div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.payroll.settings.profile.tasks.store', ['profileId' => (int)($profile->id ?? 0)]) }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Code (unique)</label>
                                <input class="form-control" name="code" value="{{ old('code') }}" placeholder="ex: kpi_ticket" required>
                                <div class="form-text">Minuscules + chiffres + underscore uniquement.</div>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Libellé</label>
                                <input class="form-control" name="label" value="{{ old('label') }}" placeholder="ex: Tickets support traités" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Objectif/mois</label>
                                <input type="number" class="form-control" name="expected_per_month" value="{{ old('expected_per_month', 0) }}" min="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Poids</label>
                                <input type="number" class="form-control" name="weight" value="{{ old('weight', 10) }}" min="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Deadline (h)</label>
                                <input type="number" class="form-control" name="deadline_hours" value="{{ old('deadline_hours', 0) }}" min="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Critique</label>
                                <select class="form-select" name="is_critical" required>
                                    <option value="0" {{ ((int)old('is_critical', 0) === 0) ? 'selected' : '' }}>Non</option>
                                    <option value="1" {{ ((int)old('is_critical', 0) === 1) ? 'selected' : '' }}>Oui</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Actif</label>
                                <select class="form-select" name="is_active" required>
                                    <option value="1" {{ ((int)old('is_active', 1) === 1) ? 'selected' : '' }}>Oui</option>
                                    <option value="0" {{ ((int)old('is_active', 1) === 0) ? 'selected' : '' }}>Non</option>
                                </select>
                            </div>
                            <div class="col-md-9 d-flex align-items-end justify-content-end">
                                <button class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Ajouter
                                </button>
                            </div>
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
