@extends('layouts.admin')

@section('title', 'Modifier le Projet - ' . $project->title)

@section('content')
<div class="container-fluid">
    <!-- Header avec retour -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="text-white mb-1">
                        <i class="fas fa-edit me-2"></i>Modifier le Projet
                    </h2>
                    <p class="text-muted mb-0">Modification des informations du projet étudiant</p>
                </div>
                <a href="{{ route('admin.projects.view', $project->id) }}" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Formulaire d'édition -->
    <div class="row">
        <div class="col-lg-8">
            <div class="dashboard-card text-white">
                <div class="card-header border-secondary">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Informations du Projet
                    </h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Erreurs de validation :</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.projects.update', $project->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="title" class="form-label text-light">Titre du projet *</label>
                                <input type="text" 
                                       class="form-control bg-dark text-white border-secondary @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title', $project->title) }}" 
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label text-light">Description</label>
                                <textarea class="form-control bg-dark text-white border-secondary @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4">{{ old('description', $project->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="software_used" class="form-label text-light">Logiciels utilisés</label>
                                <input type="text" 
                                       class="form-control bg-dark text-white border-secondary @error('software_used') is-invalid @enderror" 
                                       id="software_used" 
                                       name="software_used" 
                                       value="{{ old('software_used', is_array($project->software_used) ? implode(', ', $project->software_used) : $project->software_used) }}" 
                                       placeholder="Ex: photoshop, illustrator, indesign">
                                <small class="form-text text-muted">Séparez les logiciels par des virgules</small>
                                @error('software_used')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label text-light">Statut *</label>
                                <select class="form-select bg-dark text-white border-secondary @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="en_cours" {{ old('status', $project->status) == 'en_cours' ? 'selected' : '' }}>
                                        En cours
                                    </option>
                                    <option value="termine" {{ old('status', $project->status) == 'termine' ? 'selected' : '' }}>
                                        Terminé
                                    </option>
                                    <option value="valide" {{ old('status', $project->status) == 'valide' ? 'selected' : '' }}>
                                        Validé
                                    </option>
                                    <option value="rejete" {{ old('status', $project->status) == 'rejete' ? 'selected' : '' }}>
                                        Rejeté
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-4">
                                <label for="link" class="form-label text-light">Lien externe</label>
                                <input type="url" 
                                       class="form-control bg-dark text-white border-secondary @error('link') is-invalid @enderror" 
                                       id="link" 
                                       name="link" 
                                       value="{{ old('link', $project->link) }}" 
                                       placeholder="https://exemple.com">
                                @error('link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                            <a href="{{ route('admin.projects.view', $project->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Informations étudiant -->
            <div class="dashboard-card text-white mb-4">
                <div class="card-header border-secondary">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>Étudiant
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($project->user->profile_photo)
                            <img src="{{ asset('uploads/photos/' . basename($project->user->profile_photo)) }}" 
                                 alt="Photo de {{ $project->user->first_name }}"
                                 class="rounded-circle"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center"
                                 style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold;">
                                {{ strtoupper(substr($project->user->first_name, 0, 1)) }}{{ strtoupper(substr($project->user->last_name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <h6 class="text-center text-white">{{ $project->user->first_name }} {{ $project->user->last_name }}</h6>
                    <p class="text-center text-muted mb-0">{{ $project->user->email }}</p>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.students.show', $project->user->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-user me-1"></i>Voir le profil
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Informations du projet -->
            <div class="dashboard-card text-white">
                <div class="card-header border-secondary">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informations
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Créé le</small>
                        <p class="text-white mb-0">{{ $project->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Dernière modification</small>
                        <p class="text-white mb-0">{{ $project->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    @if($project->images && $project->images->count() > 0)
                        <div class="mb-3">
                            <small class="text-muted">Fichiers associés</small>
                            <p class="text-white mb-0">{{ $project->images->count() }} fichier(s)</p>
                        </div>
                    @endif
                    
                    @php
                        $statusClass = match($project->status) {
                            'valide' => 'bg-success',
                            'en_cours' => 'bg-warning',
                            'termine' => 'bg-info',
                            'rejete' => 'bg-danger',
                            default => 'bg-secondary'
                        };
                        
                        $statusLabel = match($project->status) {
                            'valide' => 'Validé',
                            'en_cours' => 'En cours',
                            'termine' => 'Terminé',
                            'rejete' => 'Rejeté',
                            default => 'Inconnu'
                        };
                    @endphp
                    
                    <div class="mb-0">
                        <small class="text-muted">Statut actuel</small>
                        <br>
                        <span class="badge {{ $statusClass }} mt-1">{{ $statusLabel }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
