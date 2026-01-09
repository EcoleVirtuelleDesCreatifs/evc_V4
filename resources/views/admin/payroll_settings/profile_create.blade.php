@extends('layouts.admin')

@section('title', 'Créer profil salaire')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1 text-white">Créer un profil</h1>
            <div class="text-white-50">Forfait mensuel, commission et statut</div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.payroll.settings.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
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

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px; overflow: hidden;">
        <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <div class="fw-bold text-white"><i class="fas fa-plus me-2"></i>Nouveau profil salaire</div>
        </div>
        <div class="card-body text-white">
            <form method="POST" action="{{ route('admin.payroll.settings.profile.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Code (unique)</label>
                        <input type="text" class="form-control" name="code" value="{{ old('code') }}" placeholder="ex: assistant, commercial" required>
                        <div class="form-text">Minuscules + chiffres + underscore uniquement.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Libellé</label>
                        <input type="text" class="form-control" name="label" value="{{ old('label') }}" placeholder="ex: Assistant" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Forfait mensuel (FCFA)</label>
                        <input type="number" class="form-control" name="base_monthly_amount" value="{{ old('base_monthly_amount', 0) }}" min="0" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Commission (basis points)</label>
                        <input type="number" class="form-control" name="commission_rate_bp" value="{{ old('commission_rate_bp', 0) }}" min="0" max="10000">
                        <div class="form-text">Ex: 1500 = 15.00%</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Statut</label>
                        <select class="form-select" name="is_active" required>
                            <option value="1" {{ ((int)old('is_active', 1) === 1) ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ ((int)old('is_active', 1) === 0) ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Créer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    body { background-color: #0f172a; }
    .form-label, .form-text { color: #e2e8f0; }
+</style>
@endpush
