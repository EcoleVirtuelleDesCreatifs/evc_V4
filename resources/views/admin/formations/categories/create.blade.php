@extends('layouts.admin')

@section('title', 'Créer une Catégorie')

@push('styles')
<style>
  /* Page Catégories - Mode sombre */
  body {
    background-color: #0b1020;
  }
  .container-fluid.px-4 {
    background: linear-gradient(135deg, #0b1020 0%, #111b2c 50%, #15233a 100%) !important;
    min-height: 100vh;
  }
  .card {
    background: rgba(17, 24, 39, 0.92) !important; /* gray-900 */
    border: 1px solid rgba(255,255,255,0.12) !important;
    color: #e5e7eb;
  }
  .card-body { color: #e5e7eb; }
  .text-dark { color: #f3f4f6 !important; }
  .text-muted, .text-white-50 { color: rgba(229, 231, 235, 0.65) !important; }
  .form-label { color: #f3f4f6 !important; }
  .form-control, .form-select, textarea {
    background: rgba(31, 41, 55, 0.85) !important; /* gray-800 */
    border: 1px solid rgba(255,255,255,0.14) !important;
    color: #f9fafb !important;
  }
  .form-control::placeholder, textarea::placeholder { color: rgba(229,231,235,0.5) !important; }
  .form-text { color: rgba(229,231,235,0.6) !important; }
  .invalid-feedback { color: #fecaca !important; }
  .breadcrumb .text-white, .breadcrumb a { color: #e5e7eb !important; }
  .btn-outline-light { border-color: rgba(255,255,255,0.4); color: #e5e7eb; }
  .btn-outline-light:hover { background: rgba(255,255,255,0.1); color: #fff; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">

    <!-- Header avec breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2" style="background: transparent;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none">
                                    <i class="fas fa-home me-1"></i>Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.formations.categories.index') }}" class="text-white text-decoration-none">
                                    <i class="fas fa-layer-group me-1"></i>Catégories
                                </a>
                            </li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Créer</li>
                        </ol>
                    </nav>
                    <h1 class="text-white fw-bold mb-0 d-flex align-items-center">
                        <i class="fas fa-plus-circle me-3" style="color: #64b5f6;"></i>
                        Créer une Nouvelle Catégorie
                    </h1>
                    <p class="text-white-50 mb-0">Ajoutez une nouvelle catégorie de formation</p>
                </div>
                <div>
                    <a href="{{ route('admin.formations.categories.index') }}" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de création -->
    <div class="row">
        <div class="col-lg-12 col-xl-12 mx-auto">
            <div class="card border-0" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border-radius: 20px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);">

                <div class="card-body pt-4">
                    <form action="{{ route('admin.formations.categories.store') }}" method="POST" class="needs-validation" novalidate id="categoryForm">
                        @csrf

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
                                       value="{{ old('name') }}"
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
                                       value="{{ old('slug') }}"
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
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      placeholder="Décrivez cette catégorie de formation..."
                                      style="border-radius: 12px; border: 2px solid #e9ecef; padding: 15px 20px;">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>Description optionnelle de la catégorie
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
                                <option value="design-graphique" {{ old('module') == 'design-graphique' ? 'selected' : '' }}>
                                    <i class="fas fa-palette"></i> Design Graphique
                                </option>
                                <option value="community-management" {{ old('module') == 'community-management' ? 'selected' : '' }}>
                                    <i class="fas fa-users"></i> Community Management
                                </option>
                                <option value="gestion-informatique" {{ old('module') == 'gestion-informatique' ? 'selected' : '' }}>
                                    <i class="fas fa-laptop-code"></i> Gestion Informatique
                                </option>
                                <option value="intelligence-artificielle" {{ old('module') == 'intelligence-artificielle' ? 'selected' : '' }}>
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
                                    style="border-radius: 12px; border: 2px solid #e9ecef; padding: 15px 20px;">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                    <i class="fas fa-check-circle"></i> Actif
                                </option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                    <i class="fas fa-pause-circle"></i> Inactif
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>Une catégorie inactive ne sera pas visible publiquement
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex gap-3 pt-4 border-top">
                            <button type="submit" class="btn btn-primary btn-lg flex-fill" style="border-radius: 12px; padding: 15px;">
                                <i class="fas fa-save me-2"></i>Créer la Catégorie
                            </button>
                            <a href="{{ route('admin.formations.categories.index') }}" class="btn btn-outline-secondary btn-lg" style="border-radius: 12px; padding: 15px 30px;">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<
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
    const form = document.getElementById('categoryForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
              submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Création en cours...';
              submitBtn.disabled = true;
            }
        });
    }

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

@push('styles')
<style>
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

    .card-body {
        padding: 20px;
    }

    .btn-lg {
        padding: 12px 20px;
    }
}
</style>
@endpush
