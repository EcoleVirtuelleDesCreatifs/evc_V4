@extends('layouts.admin')

@section('title', 'Créer un Actualité')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="text-white mb-1">
                <i class="fas fa-calendar-plus me-2"></i>Créer un Actualité
            </h1>
            <p class="text-muted mb-0">Ajoutez un nouvel actualité à votre plateforme</p>
        </div>
        <a href="{{ route('admin.articles.actualites') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <form action="{{ route('admin.articles.actualites.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
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
                            <label for="title" class="form-label">Titre de l'actualité <span class="text-danger">*</span></label>
                            <input type="text" class="form-control modern-input @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required 
                                   placeholder="Ex: Conférence sur le Design Thinking">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Le titre apparaîtra dans les résultats de recherche</small>
                        </div>

                        <!-- Slug (généré automatiquement) - Masqué par défaut -->
                        <div class="mb-4">
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="toggle-slug-btn">
                                <i class="fas fa-link me-1"></i>Personnaliser l'URL
                            </button>
                            <div id="slug-field" style="display: none;" class="mt-3">
                                <label for="slug" class="form-label">URL (Slug)</label>
                                <input type="text" class="form-control modern-input" id="slug" name="slug" 
                                       value="{{ old('slug') }}" placeholder="conference-design-thinking">
                                <small class="text-muted">Généré automatiquement à partir du titre. Laissez vide pour génération automatique.</small>
                            </div>
                        </div>

                        <!-- Description courte -->
                        <div class="mb-4">
                            <label for="excerpt" class="form-label">Description courte <span class="text-danger">*</span></label>
                            <textarea class="form-control modern-input @error('excerpt') is-invalid @enderror" 
                                      id="excerpt" name="excerpt" rows="3" required 
                                      placeholder="Résumé de l'actualité (150-200 caractères)">{{ old('excerpt') }}</textarea>
                            @error('excerpt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><span id="excerpt-count">0</span>/200 caractères</small>
                        </div>

                        <!-- Contenu complet -->
                        <div class="mb-4">
                            <label for="content" class="form-label">Contenu complet <span class="text-danger">*</span></label>
                            <input type="hidden" name="content" id="content-input">
                            <div id="quill-editor" style="min-height: 300px; background-color: #0f172a; color: #e2e8f0; border: 2px solid #334155; border-radius: 12px;"></div>
                            @error('content')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Utilisez l'éditeur pour formater votre contenu (gras, italique, listes, liens, etc.)</small>
                        </div>
                    </div>
                </div>

                <!-- Catégorie de l'actualité -->
                <div class="card modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-tag me-2"></i>Catégorie</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Catégorie -->
                            <div class="col-md-12 mb-4">
                                <label for="category" class="form-label">Catégorie de l'actualité <span class="text-danger">*</span></label>
                                <select class="form-select modern-input @error('category') is-invalid @enderror" id="category" name="category" required>
                                    <option value="">Sélectionner une catégorie</option>
                                    <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>Général</option>
                                    <option value="formation" {{ old('category') == 'formation' ? 'selected' : '' }}>Formation</option>
                                    <option value="evenement" {{ old('category') == 'evenement' ? 'selected' : '' }}>Événement</option>
                                    <option value="partenariat" {{ old('category') == 'partenariat' ? 'selected' : '' }}>Partenariat</option>
                                    <option value="succes" {{ old('category') == 'succes' ? 'selected' : '' }}>Succès</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Choisissez la catégorie qui correspond le mieux à cette actualité</small>
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
                                   id="meta_title" name="meta_title" value="{{ old('meta_title') }}" 
                                   placeholder="Titre optimisé pour les moteurs de recherche">
                            <small class="text-muted"><span id="meta-title-count">0</span>/60 caractères recommandés</small>
                        </div>

                        <!-- Meta Description -->
                        <div class="mb-4">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control modern-input" 
                                      id="meta_description" name="meta_description" rows="3" 
                                      placeholder="Description pour les résultats de recherche">{{ old('meta_description') }}</textarea>
                            <small class="text-muted"><span id="meta-desc-count">0</span>/160 caractères recommandés</small>
                        </div>

                        <!-- Mots-clés -->
                        <div class="mb-4">
                            <label for="keywords" class="form-label">Mots-clés (séparés par des virgules)</label>
                            <input type="text" class="form-control modern-input" 
                                   id="keywords" name="keywords" value="{{ old('keywords') }}" 
                                   placeholder="actualité, design, conférence, abidjan">
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
                            <label for="status" class="form-label">Statut</label>
                            <select class="form-select modern-input" id="status" name="status">
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Publié</option>
                                <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Programmé</option>
                            </select>
                        </div>

                        <!-- Date de publication -->
                        <div class="mb-4">
                            <label for="published_at" class="form-label">Date de publication</label>
                            <input type="datetime-local" class="form-control modern-input" 
                                   id="published_at" name="published_at" value="{{ old('published_at') }}">
                            <small class="text-muted">Laisser vide pour publier immédiatement</small>
                        </div>

                        <!-- À la une -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    <i class="fas fa-star text-warning me-1"></i>Mettre à la UNE
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2">L'actualité sera mis en avant sur la page d'accueil</small>
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
                            <label for="cover_image" class="form-label">Image principale <span class="text-danger">*</span></label>
                            <input type="file" class="form-control modern-input @error('cover_image') is-invalid @enderror" 
                                   id="cover_image" name="cover_image" accept="image/*" required>
                            @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-2">Format recommandé : 1200x630px (JPG, PNG, WebP) - Max 2MB</small>
                        </div>

                        <!-- Prévisualisation -->
                        <div id="image-preview" class="mt-3" style="display: none;">
                            <img id="preview-img" src="" alt="Aperçu" class="img-fluid rounded" style="max-height: 200px;">
                            <button type="button" class="btn btn-sm btn-outline-danger mt-2" id="remove-image">
                                <i class="fas fa-times me-1"></i>Supprimer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Destinataires -->
                <div class="card modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-users me-2"></i>Destinataires</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Destinataires <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="visibility" id="visibility_public" value="public" {{ old('visibility', 'public') == 'public' ? 'checked' : '' }}>
                                <label class="form-check-label" for="visibility_public">
                                    <i class="fas fa-globe me-1"></i>Visiteurs (Public)
                                </label>
                                <small class="text-muted d-block ms-4">Visible par tous les visiteurs du site</small>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="visibility" id="visibility_all" value="all" {{ old('visibility') == 'all' ? 'checked' : '' }}>
                                <label class="form-check-label" for="visibility_all">
                                    <i class="fas fa-users me-1"></i>Toutes les formations
                                </label>
                                <small class="text-muted d-block ms-4">Visible par tous les étudiants inscrits</small>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="visibility" id="visibility_specific" value="specific" {{ old('visibility') == 'specific' ? 'checked' : '' }}>
                                <label class="form-check-label" for="visibility_specific">
                                    <i class="fas fa-user-graduate me-1"></i>Formations spécifiques
                                </label>
                                <small class="text-muted d-block ms-4">Visible uniquement par certaines formations</small>
                            </div>
                        </div>

                        <!-- Sélection des formations -->
                        <div id="formations-select" style="display: none;">
                            <label for="formations" class="form-label">Sélectionnez les formations</label>
                            <select class="form-select modern-input" id="formations" name="formations[]" multiple size="5">
                                @foreach($formations as $formation)
                                    <option value="{{ $formation->id }}" {{ in_array($formation->id, old('formations', [])) ? 'selected' : '' }}>
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

        <!-- Boutons d'action en bas du formulaire -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card modern-card">
                    <div class="card-body">
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="{{ route('admin.articles.actualites') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" name="status" value="draft" class="btn btn-outline-light">
                                <i class="fas fa-save me-2"></i>Enregistrer comme brouillon
                            </button>
                            <button type="submit" name="status" value="published" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Publier l'actualité
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .modern-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        overflow: hidden;
    }

    .modern-card .card-header {
        background: rgba(255, 255, 255, 0.05);
        border-bottom: 1px solid #334155;
        padding: 1.25rem 1.5rem;
    }

    .modern-card .card-header h5 {
        color: #4fc3f7;
        font-weight: 600;
        margin: 0;
    }

    .modern-card .card-body {
        padding: 1.5rem;
    }

    .modern-input {
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid #334155;
        border-radius: 12px;
        color: white;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .modern-input:focus {
        background: rgba(255, 255, 255, 0.08);
        border-color: #4fc3f7;
        box-shadow: 0 0 0 0.2rem rgba(79, 195, 247, 0.25);
        color: white;
    }

    .modern-input::placeholder {
        color: #64748b;
    }

    .form-label {
        color: #cbd5e1;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .text-danger {
        color: #ef4444 !important;
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

    /* Quill Editor Custom Styles */
    .ql-toolbar.ql-snow {
        background-color: #1e293b;
        border: 2px solid #334155;
        border-bottom: none;
        border-radius: 12px 12px 0 0;
    }

    .ql-container.ql-snow {
        background-color: #0f172a;
        border: 2px solid #334155;
        border-top: none;
        border-radius: 0 0 12px 12px;
    }

    .ql-editor {
        color: #e2e8f0;
        min-height: 300px;
    }

    .ql-editor.ql-blank::before {
        color: #64748b;
    }

    .ql-snow .ql-stroke {
        stroke: #cbd5e1;
    }

    .ql-snow .ql-fill {
        fill: #cbd5e1;
    }

    .ql-snow .ql-picker-label {
        color: #cbd5e1;
    }

    .ql-toolbar.ql-snow .ql-picker-label:hover,
    .ql-toolbar.ql-snow .ql-picker-label.ql-active,
    .ql-toolbar.ql-snow button:hover,
    .ql-toolbar.ql-snow button.ql-active {
        color: #4fc3f7;
    }

    .ql-toolbar.ql-snow .ql-picker-label:hover .ql-stroke,
    .ql-toolbar.ql-snow .ql-picker-label.ql-active .ql-stroke,
    .ql-toolbar.ql-snow button:hover .ql-stroke,
    .ql-toolbar.ql-snow button.ql-active .ql-stroke {
        stroke: #4fc3f7;
    }

    .ql-toolbar.ql-snow .ql-picker-label:hover .ql-fill,
    .ql-toolbar.ql-snow .ql-picker-label.ql-active .ql-fill,
    .ql-toolbar.ql-snow button:hover .ql-fill,
    .ql-toolbar.ql-snow button.ql-active .ql-fill {
        fill: #4fc3f7;
    }

    .ql-snow .ql-picker-options {
        background-color: #1e293b;
        border: 1px solid #334155;
    }

    .ql-snow .ql-picker-options .ql-picker-item:hover {
        background-color: #334155;
        color: #4fc3f7;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser Quill Editor
    const quill = new Quill('#quill-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'color': [] }, { 'background': [] }],
                ['link', 'blockquote', 'code-block'],
                ['clean']
            ]
        },
        placeholder: 'Décrivez l\'actualité en détail : programme, intervenants, objectifs, public cible...',
    });

    // Restaurer le contenu depuis old() en cas d'erreur de validation
    @if(old('content'))
        quill.root.innerHTML = {!! json_encode(old('content')) !!};
    @endif

    // Synchroniser le contenu de Quill avec le champ hidden
    const contentInput = document.getElementById('content-input');
    quill.on('text-change', function() {
        contentInput.value = quill.root.innerHTML;
    });
    
    // Initialiser le champ hidden avec le contenu existant si présent
    if (quill.root.innerHTML) {
        contentInput.value = quill.root.innerHTML;
    }

    // Toggle slug field visibility
    const toggleSlugBtn = document.getElementById('toggle-slug-btn');
    const slugField = document.getElementById('slug-field');
    
    toggleSlugBtn.addEventListener('click', function() {
        if (slugField.style.display === 'none') {
            slugField.style.display = 'block';
            this.innerHTML = '<i class="fas fa-link me-1"></i>Masquer l\'URL';
        } else {
            slugField.style.display = 'none';
            this.innerHTML = '<i class="fas fa-link me-1"></i>Personnaliser l\'URL';
        }
    });

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

    // Vérifier l'état initial
    const checkedRadio = document.querySelector('input[name="visibility"]:checked');
    if (checkedRadio && checkedRadio.value === 'specific') {
        formationsSelect.style.display = 'block';
    }
});
</script>
@endpush
@endsection
