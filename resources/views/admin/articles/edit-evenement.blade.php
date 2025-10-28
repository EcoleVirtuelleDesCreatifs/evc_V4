@extends('layouts.admin')

@section('title', 'Modifier l\'Événement')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="text-white mb-1">
                <i class="fas fa-edit me-2"></i>Modifier l'Événement
            </h1>
            <p class="text-muted mb-0">Modifiez les informations de l'événement</p>
        </div>
        <a href="{{ route('admin.articles.evenements') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.articles.evenements.update', $evenement->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Informations de base -->
                <div class="card modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations de base</h5>
                    </div>
                    <div class="card-body">
                        <!-- Titre -->
                        <div class="mb-4">
                            <label for="title" class="form-label">Titre de l'événement <span class="text-danger">*</span></label>
                            <input type="text" class="form-control modern-input @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $evenement->title) }}" required 
                                   placeholder="Ex: Conférence sur le Design Thinking">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Le titre apparaîtra dans les résultats de recherche</small>
                        </div>

                        <!-- Slug (généré automatiquement) -->
                        <div class="mb-4">
                            <label for="slug" class="form-label">URL (Slug)</label>
                            <input type="text" class="form-control modern-input" id="slug" name="slug" 
                                   value="{{ old('slug', $evenement->slug) }}" placeholder="conference-design-thinking">
                            <small class="text-muted">Généré automatiquement à partir du titre. Modifiable.</small>
                        </div>

                        <!-- Description courte -->
                        <div class="mb-4">
                            <label for="excerpt" class="form-label">Description courte <span class="text-danger">*</span></label>
                            <textarea class="form-control modern-input @error('excerpt') is-invalid @enderror" 
                                      id="excerpt" name="excerpt" rows="3" required 
                                      placeholder="Résumé de l'événement (150-200 caractères)">{{ old('excerpt', $evenement->excerpt) }}</textarea>
                            @error('excerpt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><span id="excerpt-count">{{ strlen($evenement->excerpt) }}</span>/200 caractères</small>
                        </div>

                        <!-- Contenu complet -->
                        <div class="mb-4">
                            <label for="content" class="form-label">Contenu complet <span class="text-danger">*</span></label>
                            <textarea class="form-control modern-input @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="10" required 
                                      placeholder="Description détaillée de l'événement...">{{ old('content', $evenement->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Détails de l'événement -->
                <div class="card modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Détails de l'événement</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Date de l'événement -->
                            <div class="col-md-6 mb-4">
                                <label for="event_date" class="form-label">Date de début <span class="text-danger">*</span></label>
                                <input type="date" class="form-control modern-input @error('event_date') is-invalid @enderror" 
                                       id="event_date" name="event_date" value="{{ old('event_date', $evenement->event_date->format('Y-m-d')) }}" required>
                                @error('event_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date de fin -->
                            <div class="col-md-6 mb-4">
                                <label for="event_end_date" class="form-label">Date de fin (optionnel)</label>
                                <input type="date" class="form-control modern-input" 
                                       id="event_end_date" name="event_end_date" value="{{ old('event_end_date', $evenement->event_end_date ? $evenement->event_end_date->format('Y-m-d') : '') }}">
                            </div>

                            <!-- Lieu -->
                            <div class="col-md-6 mb-4">
                                <label for="location" class="form-label">Lieu</label>
                                <input type="text" class="form-control modern-input" 
                                       id="location" name="location" value="{{ old('location', $evenement->location) }}" 
                                       placeholder="Ex: Abidjan, Cocody">
                            </div>

                            <!-- Type d'événement -->
                            <div class="col-md-6 mb-4">
                                <label for="event_type" class="form-label">Type d'événement <span class="text-danger">*</span></label>
                                <select class="form-select modern-input @error('event_type') is-invalid @enderror" 
                                        id="event_type" name="event_type" required>
                                    <option value="physical" {{ old('event_type', $evenement->event_type) == 'physical' ? 'selected' : '' }}>Présentiel</option>
                                    <option value="online" {{ old('event_type', $evenement->event_type) == 'online' ? 'selected' : '' }}>En ligne</option>
                                    <option value="hybrid" {{ old('event_type', $evenement->event_type) == 'hybrid' ? 'selected' : '' }}>Hybride</option>
                                </select>
                                @error('event_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Lien d'inscription -->
                            <div class="col-12 mb-4">
                                <label for="registration_link" class="form-label">Lien d'inscription</label>
                                <input type="url" class="form-control modern-input" 
                                       id="registration_link" name="registration_link" value="{{ old('registration_link', $evenement->registration_link) }}" 
                                       placeholder="https://...">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO -->
                <div class="card modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-search me-2"></i>Optimisation SEO</h5>
                    </div>
                    <div class="card-body">
                        <!-- Meta Title -->
                        <div class="mb-4">
                            <label for="meta_title" class="form-label">Meta Title</label>
                            <input type="text" class="form-control modern-input" 
                                   id="meta_title" name="meta_title" value="{{ old('meta_title', $evenement->meta_title) }}" 
                                   placeholder="Titre pour les moteurs de recherche" maxlength="60">
                            <small class="text-muted"><span id="meta-title-count">{{ strlen($evenement->meta_title ?? '') }}</span>/60 caractères</small>
                        </div>

                        <!-- Meta Description -->
                        <div class="mb-4">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control modern-input" 
                                      id="meta_description" name="meta_description" rows="3" 
                                      placeholder="Description pour les moteurs de recherche" maxlength="160">{{ old('meta_description', $evenement->meta_description) }}</textarea>
                            <small class="text-muted"><span id="meta-desc-count">{{ strlen($evenement->meta_description ?? '') }}</span>/160 caractères</small>
                        </div>

                        <!-- Meta Keywords -->
                        <div class="mb-4">
                            <label for="meta_keywords" class="form-label">Mots-clés</label>
                            <input type="text" class="form-control modern-input" 
                                   id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $evenement->meta_keywords) }}" 
                                   placeholder="design, conférence, abidjan">
                            <small class="text-muted">Séparez les mots-clés par des virgules</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne latérale -->
            <div class="col-lg-4">
                <!-- Publication -->
                <div class="card modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Publication</h5>
                    </div>
                    <div class="card-body">
                        <!-- Statut -->
                        <div class="mb-4">
                            <label class="form-label">Statut <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status_draft" 
                                       value="draft" {{ old('status', $evenement->status) == 'draft' ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_draft">
                                    <i class="fas fa-file-alt me-1"></i>Brouillon
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status_published" 
                                       value="published" {{ old('status', $evenement->status) == 'published' ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_published">
                                    <i class="fas fa-check-circle me-1"></i>Publié
                                </label>
                            </div>
                        </div>

                        <!-- À la une -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                       {{ old('is_featured', $evenement->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    <i class="fas fa-star me-1"></i>Mettre à la une
                                </label>
                            </div>
                            <small class="text-muted">L'événement apparaîtra en priorité sur la page d'accueil</small>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Mettre à jour
                            </button>
                            <a href="{{ route('admin.articles.evenements') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Image de couverture -->
                <div class="card modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-image me-2"></i>Image de couverture</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="cover_image" class="form-label">Image</label>
                            <input type="file" class="form-control modern-input" 
                                   id="cover_image" name="cover_image" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG, WEBP (max 2MB)</small>
                        </div>

                        <!-- Image actuelle -->
                        @if($evenement->cover_image)
                        <div class="mb-3">
                            <label class="form-label">Image actuelle</label>
                            <img src="{{ asset('storage/' . $evenement->cover_image) }}" 
                                 alt="{{ $evenement->title }}" 
                                 class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                        </div>
                        @endif

                        <!-- Prévisualisation nouvelle image -->
                        <div id="image-preview" style="display: none;">
                            <label class="form-label">Nouvelle image</label>
                            <div class="position-relative">
                                <img id="preview-img" src="" alt="Preview" class="img-fluid rounded mb-2" style="max-height: 200px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" id="remove-image">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="cover_image_alt" class="form-label">Texte alternatif</label>
                            <input type="text" class="form-control modern-input" 
                                   id="cover_image_alt" name="cover_image_alt" value="{{ old('cover_image_alt', $evenement->cover_image_alt) }}" 
                                   placeholder="Description de l'image">
                        </div>
                    </div>
                </div>

                <!-- Visibilité -->
                <div class="card modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Visibilité</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Destinataires <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="visibility" id="visibility_public" 
                                       value="public" {{ old('visibility', $evenement->visibility) == 'public' ? 'checked' : '' }}>
                                <label class="form-check-label" for="visibility_public">
                                    <i class="fas fa-globe me-1"></i>Visiteurs (Public)
                                </label>
                                <small class="text-muted d-block ms-4">Visible par tous les visiteurs du site</small>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="visibility" id="visibility_all" 
                                       value="all" {{ old('visibility', $evenement->visibility) == 'all' ? 'checked' : '' }}>
                                <label class="form-check-label" for="visibility_all">
                                    <i class="fas fa-users me-1"></i>Toutes les formations
                                </label>
                                <small class="text-muted d-block ms-4">Visible par tous les étudiants inscrits</small>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="visibility" id="visibility_specific" 
                                       value="specific" {{ old('visibility', $evenement->visibility) == 'specific' ? 'checked' : '' }}>
                                <label class="form-check-label" for="visibility_specific">
                                    <i class="fas fa-user-graduate me-1"></i>Formations spécifiques
                                </label>
                                <small class="text-muted d-block ms-4">Visible uniquement par certaines formations</small>
                            </div>
                        </div>

                        <!-- Sélection des formations -->
                        <div id="formations-select" style="display: {{ old('visibility', $evenement->visibility) == 'specific' ? 'block' : 'none' }};">
                            <label for="formations" class="form-label">Sélectionnez les formations</label>
                            <select class="form-select modern-input" id="formations" name="formations[]" multiple size="5">
                                @foreach($formations as $formation)
                                    @php
                                        // Handle both JSON string and array
                                        $formationsData = is_array($evenement->formations) 
                                            ? $evenement->formations 
                                            : (json_decode($evenement->formations, true) ?? []);
                                        $selectedFormations = old('formations', $formationsData);
                                    @endphp
                                    <option value="{{ $formation->id }}" 
                                        {{ in_array($formation->id, $selectedFormations) ? 'selected' : '' }}>
                                        {{ $formation->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Maintenez Ctrl/Cmd pour sélectionner plusieurs formations</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<style>
    .modern-card {
        background-color: #1e293b;
        border: 1px solid #334155;
        border-radius: 16px;
        overflow: hidden;
    }

    .modern-card .card-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-bottom: 2px solid #4fc3f7;
        padding: 1rem 1.5rem;
    }

    .modern-card .card-header h5 {
        color: white;
        font-weight: 600;
    }

    .modern-card .card-body {
        padding: 1.5rem;
    }

    .modern-input {
        background-color: #0f172a;
        border: 2px solid #334155;
        color: #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .modern-input:focus {
        background-color: #1e293b;
        border-color: #4fc3f7;
        color: #e2e8f0;
        box-shadow: 0 0 0 0.2rem rgba(79, 195, 247, 0.25);
    }

    .modern-input::placeholder {
        color: #64748b;
    }

    .form-label {
        color: #cbd5e1;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .form-check-input:checked {
        background-color: #4fc3f7;
        border-color: #4fc3f7;
    }

    .form-check-label {
        color: #cbd5e1;
    }

    .btn-primary {
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
        border: none;
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(79, 195, 247, 0.4);
    }

    .btn-outline-secondary {
        border: 2px solid #334155;
        color: #cbd5e1;
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: #4fc3f7;
        color: #4fc3f7;
    }

    #image-preview img {
        border: 2px solid #334155;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Génération automatique du slug
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    titleInput.addEventListener('input', function() {
        const slug = this.value
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        slugInput.value = slug;
    });

    // Compteur de caractères pour excerpt
    const excerptInput = document.getElementById('excerpt');
    const excerptCount = document.getElementById('excerpt-count');
    
    excerptInput.addEventListener('input', function() {
        excerptCount.textContent = this.value.length;
    });

    // Compteur de caractères pour meta title
    const metaTitleInput = document.getElementById('meta_title');
    const metaTitleCount = document.getElementById('meta-title-count');
    
    metaTitleInput.addEventListener('input', function() {
        metaTitleCount.textContent = this.value.length;
    });

    // Compteur de caractères pour meta description
    const metaDescInput = document.getElementById('meta_description');
    const metaDescCount = document.getElementById('meta-desc-count');
    
    metaDescInput.addEventListener('input', function() {
        metaDescCount.textContent = this.value.length;
    });

    // Prévisualisation de l'image
    const coverImageInput = document.getElementById('cover_image');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    const removeImageBtn = document.getElementById('remove-image');
    
    coverImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
    
    removeImageBtn.addEventListener('click', function() {
        coverImageInput.value = '';
        imagePreview.style.display = 'none';
        previewImg.src = '';
    });

    // Afficher/masquer la sélection des formations
    const visibilityRadios = document.querySelectorAll('input[name="visibility"]');
    const formationsSelect = document.getElementById('formations-select');
    
    visibilityRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'specific') {
                formationsSelect.style.display = 'block';
            } else {
                formationsSelect.style.display = 'none';
            }
        });
    });
});
</script>
@endpush
@endsection
