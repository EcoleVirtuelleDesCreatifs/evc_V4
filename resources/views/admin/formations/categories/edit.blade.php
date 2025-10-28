@extends('layouts.admin')

@section('title', 'Modifier une Catégorie')

@push('styles')
<style>
    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 20px 0;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
    }

    .btn-primary {
        background: linear-gradient(45deg, #667eea, #764ba2);
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.5);
    }

    .form-text {
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }
    }
</style>
@endpush

@section('content')
<div class="gradient-bg">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 12px; padding: 15px 20px;">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none">
                        <i class="fas fa-home me-1"></i>Tableau de bord
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.formations.categories.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-layer-group me-1"></i>Catégories
                    </a>
                </li>
                <li class="breadcrumb-item active text-white" aria-current="page">
                    <i class="fas fa-edit me-1"></i>Modifier
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h1 class="text-white fw-bold mb-2">
                            <i class="fas fa-edit me-3" style="color: #ffd700;"></i>
                            Modifier la Catégorie
                        </h1>
                        <p class="text-white-50 mb-0">Modifiez les informations de la catégorie "{{ $category->name }}"</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.formations.categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire d'édition -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg" style="border-radius: 20px; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95);">
                    <div class="card-header border-0 text-center py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px 20px 0 0;">
                        <h4 class="text-white fw-bold mb-0">
                            <i class="fas fa-edit me-2"></i>Formulaire de Modification
                        </h4>
                    </div>
                    <div class="card-body p-5">
                        @if ($errors->any())
                            <div class="alert alert-danger border-0 rounded-3 mb-4">
                                <h6 class="alert-heading fw-bold">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Erreurs de validation
                                </h6>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.formations.categories.update', $category->id) }}" method="POST" class="needs-validation" novalidate id="editCategoryForm">
                            @csrf
                            @method('PUT')

                            <!-- Nom de la catégorie et Slug sur la même ligne -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-tag me-2 text-primary"></i>Nom de la catégorie
                                    </label>
                                    <input type="text"
                                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $category->name) }}"
                                           placeholder="Ex: Développement Web, Design Graphique..."
                                           required
                                           style="border-radius: 12px; border: 2px solid #e9ecef; padding: 15px 20px;">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i>Le nom sera utilisé pour identifier la catégorie
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="slug" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-link me-2 text-primary"></i>Slug (URL)
                                    </label>
                                    <input type="text"
                                           class="form-control form-control-lg @error('slug') is-invalid @enderror"
                                           id="slug"
                                           name="slug"
                                           value="{{ old('slug', $category->slug) }}"
                                           placeholder="Ex: developpement-web, design-graphique..."
                                           readonly
                                           style="border-radius: 12px; border: 2px solid #e9ecef; padding: 15px 20px; background-color: #f8f9fa;">
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted">
                                        <i class="fas fa-magic me-1"></i>Généré automatiquement à partir du nom
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold text-dark">
                                    <i class="fas fa-align-left me-2 text-primary"></i>Description
                                </label>
                                <textarea class="form-control form-control-lg @error('description') is-invalid @enderror"
                                          id="description"
                                          name="description"
                                          rows="4"
                                          placeholder="Décrivez cette catégorie et son contenu..."
                                          style="border-radius: 12px; border: 2px solid #e9ecef; padding: 15px 20px; resize: vertical;">{{ old('description', $category->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Description optionnelle pour expliquer le contenu de cette catégorie
                                </div>
                            </div>

                            <!-- Module de formation -->
                            <div class="mb-4">
                                <label for="module" class="form-label fw-semibold text-dark">
                                    <i class="fas fa-graduation-cap me-2 text-primary"></i>Module de Formation
                                </label>
                                <select class="form-select form-select-lg @error('module') is-invalid @enderror"
                                        id="module"
                                        name="module"
                                        required
                                        style="border-radius: 12px; border: 2px solid #e9ecef; padding: 15px 20px;">
                                    <option value="">-- Sélectionnez un module --</option>
                                    <option value="design-graphique" {{ old('module', $category->module) == 'design-graphique' ? 'selected' : '' }}>
                                        <i class="fas fa-palette"></i> Design Graphique
                                    </option>
                                    <option value="community-management" {{ old('module', $category->module) == 'community-management' ? 'selected' : '' }}>
                                        <i class="fas fa-users"></i> Community Management
                                    </option>
                                    <option value="gestion-informatique" {{ old('module', $category->module) == 'gestion-informatique' ? 'selected' : '' }}>
                                        <i class="fas fa-laptop-code"></i> Gestion Informatique
                                    </option>
                                    <option value="intelligence-artificielle" {{ old('module', $category->module) == 'intelligence-artificielle' ? 'selected' : '' }}>
                                        <i class="fas fa-brain"></i> Intelligence Artificielle
                                    </option>
                                </select>
                                @error('module')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Chaque catégorie doit être associée à un module de formation
                                </div>
                            </div>

                            <!-- Statut -->
                            <div class="mb-4">
                                <label for="status" class="form-label fw-semibold text-dark">
                                    <i class="fas fa-toggle-on me-2 text-primary"></i>Statut
                                </label>
                                <select class="form-select form-select-lg @error('status') is-invalid @enderror"
                                        id="status"
                                        name="status"
                                        required
                                        style="border-radius: 12px; border: 2px solid #e9ecef; padding: 15px 20px;">
                                    <option value="">Sélectionnez un statut</option>
                                    <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>
                                        <i class="fas fa-check-circle"></i> Actif
                                    </option>
                                    <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>
                                        <i class="fas fa-pause-circle"></i> Inactif
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Seules les catégories actives seront visibles sur le site
                                </div>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="d-flex justify-content-between align-items-center pt-3">
                                <a href="{{ route('admin.formations.categories.index') }}" class="btn btn-outline-secondary btn-lg" style="border-radius: 12px; padding: 12px 30px;">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg" style="border-radius: 12px; padding: 12px 40px; background: linear-gradient(45deg, #667eea, #764ba2); border: none;">
                                    <i class="fas fa-save me-2"></i>Mettre à jour la catégorie
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Génération automatique du slug
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');

    nameInput.addEventListener('input', function() {
        const slug = this.value
            .toLowerCase()
            .trim()
            .replace(/[àáâãäå]/g, 'a')
            .replace(/[èéêë]/g, 'e')
            .replace(/[ìíîï]/g, 'i')
            .replace(/[òóôõö]/g, 'o')
            .replace(/[ùúûü]/g, 'u')
            .replace(/[ç]/g, 'c')
            .replace(/[ñ]/g, 'n')
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        
        slugInput.value = slug;
    });

    // Animation du formulaire
    const form = document.getElementById('editCategoryForm');
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mise à jour en cours...';
        submitBtn.disabled = true;
    });

    // Animation d'entrée des champs
    const formGroups = document.querySelectorAll('.mb-4');
    formGroups.forEach((group, index) => {
        group.style.opacity = '0';
        group.style.transform = 'translateY(20px)';
        group.style.transition = 'all 0.6s ease';

        setTimeout(() => {
            group.style.opacity = '1';
            group.style.transform = 'translateY(0)';
        }, 100 * (index + 1));
    });
});
</script>
@endpush
