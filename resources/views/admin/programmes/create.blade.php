@extends('layouts.admin')

@section('title', 'Ajouter un Programme')

@push('styles')
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
@endpush

@section('content')

<div class="interactive-dashboard-form">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.programmes') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left me-2"></i>
                Retour à la liste
            </a>
            
            <h1 style="color: var(--form-text); font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">
                <i class="fas fa-plus-circle me-3"></i>
                Ajouter un Programme
            </h1>
            <p style="color: var(--form-text-muted); margin: 0;">
                Ajoutez un nouveau programme de formation avec un fichier PDF
            </p>
        </div>
    </div>

    <!-- Messages d'erreur -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Erreur :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Formulaire -->
    <form action="{{ route('admin.programmes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row g-4">
            <!-- Informations principales -->
            <div class="col-lg-8">
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="fas fa-info-circle"></i>
                        <h3>Informations du Programme</h3>
                    </div>
                    <div class="form-card-body">
                        <!-- Titre -->
                        <div class="form-group mb-4">
                            <label for="titre" class="form-label">
                                Titre du programme <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('titre') is-invalid @enderror" 
                                   id="titre" 
                                   name="titre" 
                                   required
                                   value="{{ old('titre') }}"
                                   placeholder="Ex: Introduction au Design Graphique">
                            @error('titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description" class="form-label">
                                Description (optionnel)
                            </label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="5"
                                      placeholder="Décrivez brièvement le contenu du programme, les objectifs pédagogiques...">{{ old('description') }}</textarea>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Cette description sera visible par les étudiants
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ciblage et fichier -->
            <div class="col-lg-4">
                <!-- Formation destinataire -->
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <i class="fas fa-bullseye"></i>
                        <h3>Ciblage</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="form-group">
                            <label for="formation" class="form-label">
                                Formation destinataire <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('formation') is-invalid @enderror" 
                                    id="formation" 
                                    name="formation" 
                                    required>
                                <option value="">-- Sélectionner --</option>
                                <option value="Design Graphique" {{ old('formation') == 'Design Graphique' ? 'selected' : '' }}>
                                    🎨 Design Graphique
                                </option>
                                <option value="Community Management" {{ old('formation') == 'Community Management' ? 'selected' : '' }}>
                                    📱 Community Management
                                </option>
                                <option value="Gestion Informatique" {{ old('formation') == 'Gestion Informatique' ? 'selected' : '' }}>
                                    💻 Gestion Informatique
                                </option>
                                <option value="Intelligence Artificielle" {{ old('formation') == 'Intelligence Artificielle' ? 'selected' : '' }}>
                                    🤖 Intelligence Artificielle
                                </option>
                                <option value="Toutes" {{ old('formation') == 'Toutes' ? 'selected' : '' }}>
                                    📚 Toutes les formations
                                </option>
                            </select>
                            @error('formation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Fichier PDF -->
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="fas fa-file-pdf"></i>
                        <h3>Fichier PDF</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="form-group">
                            <label for="fichier_pdf" class="form-label">
                                Document PDF <span class="text-danger">*</span>
                            </label>
                            <input type="file" 
                                   class="form-control @error('fichier_pdf') is-invalid @enderror" 
                                   id="fichier_pdf" 
                                   name="fichier_pdf" 
                                   accept=".pdf"
                                   required>
                            <div class="upload-info mt-3">
                                <i class="fas fa-info-circle me-1"></i>
                                <strong>Format :</strong> PDF uniquement
                                <span class="mx-2">•</span>
                                <strong>Taille max :</strong> 10 Mo
                            </div>
                            @error('fichier_pdf')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('admin.programmes') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>
                        Enregistrer le programme
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
// Prévisualisation du nom du fichier
document.getElementById('fichier_pdf').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name;
    if (fileName) {
        console.log('Fichier sélectionné:', fileName);
    }
});
</script>
@endpush
