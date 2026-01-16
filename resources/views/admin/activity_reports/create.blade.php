@extends('layouts.admin')

@section('title', "Nouveau rapport d'activité")

@push('styles')
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
<style>
    .form-footer .btn-secondary {
        background-color: #4A5568 !important;
        border-color: #4A5568 !important;
        color: white !important;
    }
    .form-footer .btn-success {
        background-color: #10B981 !important;
        border-color: #10B981 !important;
        color: white !important;
    }
</style>
@endpush

@section('content')
<form method="POST" action="{{ route('admin.activity-reports.store') }}" enctype="multipart/form-data" class="interactive-dashboard-form">
    @csrf

    <div class="form-header">
        <div>
            <h1 class="h3 mb-1 text-white">Nouveau rapport d'activité</h1>
            <div class="text-muted">Ajoute un PDF et publie-le si besoin.</div>
        </div>
        <a href="{{ route('admin.activity-reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="form-card h-100">
                <div class="form-card-header">
                    <i class="fas fa-info-circle"></i>
                    <h3>Informations</h3>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label for="title">Titre</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="year">Année (optionnel)</label>
                        <input type="number" id="year" name="year" value="{{ old('year') }}" class="form-control @error('year') is-invalid @enderror" min="2000" max="2100">
                        @error('year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description (optionnel)</label>
                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="6">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-card h-100">
                <div class="form-card-header">
                    <i class="fas fa-file-pdf"></i>
                    <h3>Fichier</h3>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label for="file">Fichier PDF</label>
                        <input type="file" id="file" name="file" class="form-control @error('file') is-invalid @enderror" accept="application/pdf" required>
                        @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group mb-0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="is_published" name="is_published" {{ old('is_published') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">Publier immédiatement</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="form-card">
                <div class="form-card-body form-footer d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        <i class="fas fa-shield-alt me-2"></i>Le rapport apparaîtra sur le site public uniquement s'il est publié.
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.activity-reports.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Enregistrer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
