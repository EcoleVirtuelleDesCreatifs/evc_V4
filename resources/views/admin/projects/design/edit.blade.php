@extends('layouts.admin')

@section('title', 'Modifier le Projet Design')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="text-white mb-2">
                        <i class="fas fa-edit text-warning me-2"></i>Modifier le Projet Design
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}" class="text-info">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.design-projects.index') }}" class="text-info">Projets Design</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.design-projects.view', $project->id) }}" class="text-info">{{ $project->title }}</a>
                            </li>
                            <li class="breadcrumb-item active text-white-50">Modifier</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.design-projects.view', $project->id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire d'édition -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-dark border-secondary">
                <div class="card-header border-secondary">
                    <h5 class="text-white mb-0">
                        <i class="fas fa-project-diagram text-info me-2"></i>Informations du Projet
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.design-projects.edit', $project->id) }}" method="POST" id="editProjectForm">
                        @csrf
                        
                        <div class="row">
                            <!-- Titre du projet -->
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label text-white">
                                    <i class="fas fa-heading me-1"></i>Titre du Projet *
                                </label>
                                <input type="text" 
                                       class="form-control bg-secondary border-dark text-white" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title', $project->title) }}" 
                                       required>
                                @error('title')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Type de projet -->
                            <div class="col-md-6 mb-3">
                                <label for="project_type" class="form-label text-white">
                                    <i class="fas fa-tag me-1"></i>Type de Projet *
                                </label>
                                <select class="form-select bg-secondary border-dark text-white" 
                                        id="project_type" 
                                        name="project_type" 
                                        required>
                                    <option value="">Sélectionner un type</option>
                                    <option value="logo" {{ old('project_type', $project->project_type) == 'logo' ? 'selected' : '' }}>Logo</option>
                                    <option value="flyer" {{ old('project_type', $project->project_type) == 'flyer' ? 'selected' : '' }}>Flyer</option>
                                    <option value="brochure" {{ old('project_type', $project->project_type) == 'brochure' ? 'selected' : '' }}>Brochure</option>
                                    <option value="website" {{ old('project_type', $project->project_type) == 'website' ? 'selected' : '' }}>Site Web</option>
                                    <option value="packaging" {{ old('project_type', $project->project_type) == 'packaging' ? 'selected' : '' }}>Packaging</option>
                                    <option value="illustration" {{ old('project_type', $project->project_type) == 'illustration' ? 'selected' : '' }}>Illustration</option>
                                    <option value="autre" {{ old('project_type', $project->project_type) == 'autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('project_type')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Mode -->
                            <div class="col-md-6 mb-3">
                                <label for="project_mode" class="form-label text-white">
                                    <i class="fas fa-users me-1"></i>Mode de Travail *
                                </label>
                                <select class="form-select bg-secondary border-dark text-white" 
                                        id="project_mode" 
                                        name="project_mode" 
                                        required>
                                    <option value="">Sélectionner un mode</option>
                                    <option value="solo" {{ old('project_mode', $project->project_mode) == 'solo' ? 'selected' : '' }}>Solo</option>
                                    <option value="groupe" {{ old('project_mode', $project->project_mode) == 'groupe' ? 'selected' : '' }}>Groupe</option>
                                </select>
                                @error('project_mode')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Statut -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label text-white">
                                    <i class="fas fa-flag me-1"></i>Statut *
                                </label>
                                <select class="form-select bg-secondary border-dark text-white" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="draft" {{ old('status', $project->status) == 'draft' ? 'selected' : '' }}>Brouillon</option>
                                    <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="validated" {{ old('status', $project->status) == 'validated' ? 'selected' : '' }}>Validé</option>
                                    <option value="cancelled" {{ old('status', $project->status) == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                                </select>
                                @error('status')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label text-white">
                                    <i class="fas fa-align-left me-1"></i>Description
                                </label>
                                <textarea class="form-control bg-secondary border-dark text-white" 
                                          id="description" 
                                          name="description" 
                                          rows="4" 
                                          placeholder="Description détaillée du projet...">{{ old('description', $project->description) }}</textarea>
                                @error('description')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Logiciels utilisés -->
                            <div class="col-12 mb-3">
                                <label class="form-label text-white">
                                    <i class="fas fa-tools me-1"></i>Logiciels Utilisés
                                </label>
                                <div class="row">
                                    @php
                                        $softwareUsed = is_string($project->software_used) ? json_decode($project->software_used, true) : ($project->software_used ?? []);
                                        $availableSoftware = [
                                            'photoshop' => 'Adobe Photoshop',
                                            'illustrator' => 'Adobe Illustrator',
                                            'indesign' => 'Adobe InDesign',
                                            'figma' => 'Figma',
                                            'canva' => 'Canva',
                                            'gimp' => 'GIMP',
                                            'inkscape' => 'Inkscape',
                                            'sketch' => 'Sketch',
                                            'xd' => 'Adobe XD',
                                            'autre' => 'Autre'
                                        ];
                                    @endphp
                                    
                                    @foreach($availableSoftware as $key => $label)
                                        <div class="col-md-3 col-sm-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="software_used[]" 
                                                       value="{{ $key }}" 
                                                       id="software_{{ $key }}"
                                                       {{ in_array($key, $softwareUsed) ? 'checked' : '' }}>
                                                <label class="form-check-label text-white-50" for="software_{{ $key }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('software_used')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- URL de référence -->
                            <div class="col-md-8 mb-3">
                                <label for="reference_url" class="form-label text-white">
                                    <i class="fas fa-link me-1"></i>URL de Référence
                                </label>
                                <input type="url" 
                                       class="form-control bg-secondary border-dark text-white" 
                                       id="reference_url" 
                                       name="reference_url" 
                                       value="{{ old('reference_url', $project->reference_url) }}" 
                                       placeholder="https://exemple.com">
                                @error('reference_url')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Pourcentage de progression -->
                            <div class="col-md-4 mb-4">
                                <label for="progress_percentage" class="form-label text-white">
                                    <i class="fas fa-percentage me-1"></i>Progression (%)
                                </label>
                                <input type="number" 
                                       class="form-control bg-secondary border-dark text-white" 
                                       id="progress_percentage" 
                                       name="progress_percentage" 
                                       value="{{ old('progress_percentage', $project->progress_percentage) }}" 
                                       min="0" 
                                       max="100" 
                                       placeholder="0">
                                @error('progress_percentage')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.design-projects.view', $project->id) }}" 
                                       class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>Annuler
                                    </a>
                                    <button type="submit" class="btn btn-success" id="saveBtn">
                                        <i class="fas fa-save me-1"></i>Enregistrer les Modifications
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations de l'étudiant -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card bg-dark border-secondary">
                <div class="card-header border-secondary">
                    <h5 class="text-white mb-0">
                        <i class="fas fa-user text-info me-2"></i>Informations de l'Étudiant
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-white mb-2">
                                <strong>Nom :</strong> {{ $project->user->first_name ?? 'N/A' }} {{ $project->user->last_name ?? '' }}
                            </p>
                            <p class="text-white mb-2">
                                <strong>Email :</strong> {{ $project->user->email ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-white mb-2">
                                <strong>Formation :</strong> {{ ucfirst(str_replace(['_', '-'], ' ', $project->user->formation_souhaitee ?? 'N/A')) }}
                            </p>
                            <p class="text-white mb-2">
                                <strong>Inscrit le :</strong> {{ $project->user->created_at ? $project->user->created_at->format('d/m/Y') : 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editProjectForm');
    const saveBtn = document.getElementById('saveBtn');
    
    form.addEventListener('submit', function(e) {
        // Show loading state
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Enregistrement...';
        saveBtn.disabled = true;
    });
});
</script>
@endsection
