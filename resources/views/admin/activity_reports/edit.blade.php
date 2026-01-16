@extends('layouts.admin')

@section('title', "Modifier rapport d'activité")

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-white mb-1">Modifier le rapport</h1>
            <div class="text-muted">Mets à jour les infos et/ou remplace le PDF.</div>
        </div>
        <a href="{{ route('admin.activity-reports.index') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="card" style="border-radius: 16px;">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.activity-reports.update', $report) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Titre</label>
                    <input type="text" name="title" value="{{ old('title', $report->title) }}" class="form-control @error('title') is-invalid @enderror" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Année (optionnel)</label>
                    <input type="number" name="year" value="{{ old('year', $report->year) }}" class="form-control @error('year') is-invalid @enderror" min="2000" max="2100">
                    @error('year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Description (optionnel)</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $report->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Remplacer le PDF (optionnel)</label>
                    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept="application/pdf">
                    @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" value="1" id="is_published" name="is_published" {{ old('is_published', $report->is_published) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_published">Publié</label>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-2"></i>Enregistrer
                </button>
                <a href="{{ route('admin.activity-reports.download', $report) }}" class="btn btn-outline-primary ms-2">
                    <i class="fas fa-download me-2"></i>Télécharger le PDF
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
