@extends('layouts.admin')

@section('title', 'Modifier code promo / réduction')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-white">
            <i class="fas fa-tag me-2"></i>Modifier code promo / réduction
        </h1>
        <a href="{{ route('admin.boutique.promos') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <h5 class="mb-0 text-white"><i class="fas fa-edit me-2"></i>Modifier</h5>
        </div>
        <div class="card-body text-white">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.boutique.promos.update', $promo) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Code promo</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $promo->code) }}" placeholder="Ex: PROMO20">
                        <div class="form-text">Obligatoire si aucun ID étudiant n'est renseigné.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">ID étudiant EVC</label>
                        <input type="text" name="student_id" class="form-control" value="{{ old('student_id', $promo->student_id) }}" placeholder="Ex: EVC-2024-001">
                        <div class="form-text">L'étudiant doit exister.</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-control" required>
                            <option value="percent" @selected(old('type', $promo->type) === 'percent')>Pourcentage (%)</option>
                            <option value="fixed" @selected(old('type', $promo->type) === 'fixed')>Montant fixe (FCFA)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Valeur</label>
                        <input type="number" name="value" class="form-control" value="{{ old('value', $promo->value) }}" required min="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Utilisations max (optionnel)</label>
                        <input type="number" name="max_uses" class="form-control" value="{{ old('max_uses', $promo->max_uses) }}" min="1">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date d'expiration (optionnel)</label>
                        <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at', $promo->expires_at?->format('Y-m-d\TH:i')) }}">
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', $promo->is_active))>
                            <label class="form-check-label" for="is_active">Actif</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
