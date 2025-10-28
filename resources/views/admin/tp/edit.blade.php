@extends('layouts.admin')

@section('title', 'Modifier le TP')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                <div class="card-body text-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="mb-2">
                                <i class="fas fa-edit me-3"></i>
                                Modifier le TP
                            </h1>
                            <p class="mb-0 opacity-90">
                                Modifiez les informations du travail pratique
                            </p>
                        </div>
                        <a href="{{ route('admin.travaux.all') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i>
                            Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire d'édition -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2 text-primary"></i>
                        Informations du TP
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.tp.update', $tp->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Titre -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold">
                                <i class="fas fa-heading me-2 text-primary"></i>
                                Titre du TP <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $tp->title) }}" 
                                   required
                                   placeholder="Entrez le titre du TP">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">
                                <i class="fas fa-align-left me-2 text-primary"></i>
                                Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="5"
                                      placeholder="Entrez une description détaillée du TP">{{ old('description', strip_tags($tp->description)) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Décrivez le contenu et les objectifs du TP
                            </small>
                        </div>

                        <!-- Lien -->
                        <div class="mb-4">
                            <label for="link" class="form-label fw-bold">
                                <i class="fas fa-link me-2 text-primary"></i>
                                Lien (URL)
                            </label>
                            <input type="url" 
                                   class="form-control @error('link') is-invalid @enderror" 
                                   id="link" 
                                   name="link" 
                                   value="{{ old('link', $tp->link) }}"
                                   placeholder="https://exemple.com">
                            @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Lien vers le projet en ligne (optionnel)
                            </small>
                        </div>

                        <!-- Informations supplémentaires -->
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note :</strong> Le statut et les fichiers du TP ne peuvent pas être modifiés ici. 
                            Pour changer le statut, utilisez les boutons "Valider" ou "Rejeter" sur la page de détails.
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.tp.view', $tp->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informations du TP -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light py-3">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2 text-muted"></i>
                        Informations supplémentaires
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong class="text-muted">Statut :</strong>
                            @if($tp->status === 'pending')
                                <span class="badge bg-warning text-dark ms-2">
                                    <i class="fas fa-clock me-1"></i>En attente
                                </span>
                            @elseif($tp->status === 'validated')
                                <span class="badge bg-success ms-2">
                                    <i class="fas fa-check me-1"></i>Validé
                                </span>
                            @elseif($tp->status === 'rejected')
                                <span class="badge bg-danger ms-2">
                                    <i class="fas fa-times me-1"></i>Rejeté
                                </span>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-muted">Date de création :</strong>
                            <span class="ms-2">{{ \Carbon\Carbon::parse($tp->created_at)->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-muted">Dernière modification :</strong>
                            <span class="ms-2">{{ \Carbon\Carbon::parse($tp->updated_at)->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($tp->validated_at)
                        <div class="col-md-6 mb-3">
                            <strong class="text-muted">Date de validation :</strong>
                            <span class="ms-2">{{ \Carbon\Carbon::parse($tp->validated_at)->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-control:focus {
    border-color: #2a5298;
    box-shadow: 0 0 0 0.2rem rgba(42, 82, 152, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(30, 60, 114, 0.3);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
}
</style>
@endsection
