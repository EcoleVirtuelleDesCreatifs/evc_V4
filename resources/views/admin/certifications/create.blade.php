@extends('layouts.admin')

@section('title', 'Nouvelle Certification')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    .form-card {
        background: linear-gradient(145deg, #1e293b, #334155);
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.1);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .form-control, .form-select {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.15);
        color: #fff;
        border-radius: 10px;
    }
    .form-control:focus, .form-select:focus {
        background: rgba(255,255,255,0.08);
        border-color: #6366f1;
        color: #fff;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.2);
    }
    .form-label { color: #cbd5e1; font-weight: 500; }
    .btn-save {
        background: linear-gradient(45deg, #10b981, #059669);
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        color: #fff;
        font-weight: 600;
    }
    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(16,185,129,0.4); color: #fff; }
    textarea.form-control { min-height: 100px; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="text-white mb-1"><i class="fas fa-plus-circle me-2"></i>Nouvelle Certification</h1>
                <p class="text-white-50 mb-0">Créez un examen de certification</p>
            </div>
            <a href="{{ route('admin.certifications.index') }}" class="btn btn-secondary" style="border-radius: 10px;">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.certifications.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="form-card">
                    <h5 class="text-white mb-3"><i class="fas fa-info-circle me-2"></i>Informations générales</h5>

                    <div class="mb-3">
                        <label for="title" class="form-label">Titre de la certification <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required placeholder="Ex: Certification Design Graphique - Niveau 1">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Description de la certification...">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="instructions" class="form-label">Consignes pour l'étudiant</label>
                        <textarea class="form-control" id="instructions" name="instructions" rows="4" placeholder="Instructions affichées avant le début du test...">{{ old('instructions') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="form-card">
                    <h5 class="text-white mb-3"><i class="fas fa-cogs me-2"></i>Paramètres</h5>

                    <div class="mb-3">
                        <label for="formation" class="form-label">Formation cible</label>
                        <select class="form-select" id="formation" name="formation">
                            <option value="">Toutes les formations</option>
                            @foreach($formations as $f)
                                <option value="{{ $f }}" {{ old('formation') == $f ? 'selected' : '' }}>{{ $f }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="duration_minutes" class="form-label">Durée (minutes) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" min="5" max="480" required>
                        <small class="text-muted">Le décompte démarre dès que l'étudiant clique sur "Commencer"</small>
                    </div>

                    <div class="mb-3">
                        <label for="passing_score" class="form-label">Note de passage (%) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="passing_score" name="passing_score" value="{{ old('passing_score', 50) }}" min="0" max="100" step="0.5" required>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="shuffle_questions" name="shuffle_questions" value="1" {{ old('shuffle_questions') ? 'checked' : '' }}>
                        <label class="form-check-label text-white" for="shuffle_questions">Mélanger les questions</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-save w-100">
                    <i class="fas fa-save me-2"></i>Créer et ajouter les questions
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
