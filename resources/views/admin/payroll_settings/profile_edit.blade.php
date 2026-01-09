@extends('layouts.admin')

@section('title', 'Modifier profil salaire')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1">Modifier profil</h1>
            <div class="text-muted">{{ $profile->label ?? '' }} — Code: {{ $profile->code ?? '' }}</div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.payroll.settings.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            <a href="{{ route('admin.payroll.settings.profile.tasks', ['profileId' => (int)($profile->id ?? 0)]) }}" class="btn btn-primary">
                <i class="fas fa-sliders-h me-2"></i>Conditions KPI
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold mb-1">Veuillez corriger les erreurs</div>
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0" style="border-radius: 16px;">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.payroll.settings.profile.update', ['profileId' => (int)($profile->id ?? 0)]) }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Libellé</label>
                        <input type="text" class="form-control" name="label" value="{{ old('label', (string)($profile->label ?? '')) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Forfait mensuel (FCFA)</label>
                        <input type="number" class="form-control" name="base_monthly_amount" value="{{ old('base_monthly_amount', (int)($profile->base_monthly_amount ?? 0)) }}" min="0" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Commission (basis points)</label>
                        <input type="number" class="form-control" name="commission_rate_bp" value="{{ old('commission_rate_bp', (int)($profile->commission_rate_bp ?? 0)) }}" min="0" max="10000">
                        <div class="form-text">Ex: 1500 = 15.00%</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Statut</label>
                        <select class="form-select" name="is_active" required>
                            <option value="1" {{ ((int)old('is_active', (int)($profile->is_active ?? 1)) === 1) ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ ((int)old('is_active', (int)($profile->is_active ?? 1)) === 0) ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
