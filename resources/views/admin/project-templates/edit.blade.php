@extends('layouts.admin')

@section('title', 'Modifier le Modèle de Projet')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Modifier le Modèle de Projet</h2>
        <a href="{{ route('admin.project-templates.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.project-templates.update', $projectTemplate) }}">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Titre *</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $projectTemplate->title) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="category" class="form-label">Catégorie *</label>
                        <input type="text" class="form-control" id="category" name="category" value="{{ old('category', $projectTemplate->category) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $projectTemplate->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="link" class="form-label">Lien</label>
                    <input type="url" class="form-control" id="link" name="link" value="{{ old('link', $projectTemplate->link) }}">
                </div>

                <div class="mb-3">
                    <label for="tags" class="form-label">Tags</label>
                    <input type="text" class="form-control" id="tags" name="tags" value="{{ old('tags', $projectTemplate->tags) }}" placeholder="Séparés par des virgules">
                </div>

                <div class="mb-3">
                    <label for="software_used" class="form-label">Logiciels utilisés</label>
                    <input type="text" class="form-control" id="software_used" name="software_used[]" value="{{ is_array($projectTemplate->software_used) ? implode(', ', $projectTemplate->software_used) : $projectTemplate->software_used }}" placeholder="Séparés par des virgules">
                </div>

                <div class="mb-3">
                    <label for="thumbnail_image" class="form-label">Image miniature</label>
                    <input type="text" class="form-control" id="thumbnail_image" name="thumbnail_image" value="{{ old('thumbnail_image', $projectTemplate->thumbnail_image) }}">
                </div>

                <div class="mb-3">
                    <label for="brief_content" class="form-label">Contenu du brief</label>
                    <textarea class="form-control" id="brief_content" name="brief_content" rows="5">{{ old('brief_content', $projectTemplate->brief_content) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="default_deadline_days" class="form-label">Délai par défaut (jours)</label>
                    <input type="number" class="form-control" id="default_deadline_days" name="default_deadline_days" value="{{ old('default_deadline_days', $projectTemplate->default_deadline_days) }}" min="1" max="365">
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" {{ $projectTemplate->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Actif</label>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.project-templates.index') }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection