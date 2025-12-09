@extends('layouts.admin')

@section('title', 'Modifier l\'Actualité')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="text-white mb-1">
                <i class="fas fa-edit me-2"></i>Modifier l'Actualité
            </h1>
            <p class="text-muted mb-0">Modifiez les informations de l'actualité</p>
        </div>
        <a href="{{ route('admin.articles.actualites') }}" class="btn btn-outline-secondary">
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

    <form action="{{ route('admin.articles.actualites.update', $actualite->id) }}" method="POST" enctype="multipart/form-data">
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
                            <label for="title" class="form-label">Titre de l'actualité <span class="text-danger">*</span></label>
                            <input type="text" class="form-control modern-input @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title', $actualite->title) }}" required
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
                                   value="{{ old('slug', $actualite->slug) }}" placeholder="conference-design-thinking">
                            <small class="text-muted">Généré automatiquement à partir du titre. Modifiable.</small>
                        </div>

                        <!-- Description courte -->
                        <div class="mb-4">
                            <label for="excerpt" class="form-label">Description courte <span class="text-danger">*</span></label>
                            <textarea class="form-control modern-input @error('excerpt') is-invalid @enderror"
                                      id="excerpt" name="excerpt" rows="3" required
                                      placeholder="Résumé de l'actualité (150-200 caractères)">{{ old('excerpt', $actualite->excerpt) }}</textarea>
                            @error('excerpt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><span id="excerpt-count">{{ strlen($actualite->excerpt) }}</span>/200 caractères</small>
                        </div>

                        <!-- Contenu complet -->
                        <div class="mb-4">
                            <label for="content" class="form-label">Contenu complet <span class="text-danger">*</span></label>
                            <input type="hidden" name="content" id="content-input" value="{{ old('content', $actualite->content) }}">
                            <div id="quill-editor"></div>
                            @error('content')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Utilisez l'éditeur pour formater votre contenu (gras, italique, listes, liens, etc.)</small>
                        </div>

                        <!-- Script Quill -->
                        <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const quill = new Quill('#quill-editor', {
                                    theme: 'snow',
                                    modules: {
                                        toolbar: [
                                            [{ 'header': [1, 2, 3, false] }],
                                            ['bold', 'italic', 'underline'],
                                            [{'list': 'ordered'}, {'list': 'bullet'}],
                                            ['link'],
                                            ['clean']
                                        ]
                                    },
                                    placeholder: 'Décrivez l\'actualité en détail : programme, intervenants, objectifs, public cible...',
                                });

                                const contentInput = document.getElementById('content-input');

                                // Initialiser avec le contenu existant
                                const initialContent = contentInput.value;
                                if (initialContent) {
                                    quill.root.innerHTML = initialContent;
                                }

                                // Synchroniser à chaque changement
                                quill.on('text-change', function() {
                                    contentInput.value = quill.root.innerHTML;
                                });
                            });
                        </script>
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
                                    <option value="general" {{ old('category', $actualite->category) == 'general' ? 'selected' : '' }}>Général</option>
                                    <option value="formation" {{ old('category', $actualite->category) == 'formation' ? 'selected' : '' }}>Formation</option>
                                    <option value="evenement" {{ old('category', $actualite->category) == 'evenement' ? 'selected' : '' }}>Événement</option>
                                    <option value="partenariat" {{ old('category', $actualite->category) == 'partenariat' ? 'selected' : '' }}>Partenariat</option>
                                    <option value="succes" {{ old('category', $actualite->category) == 'succes' ? 'selected' : '' }}>Succès</option>
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
                    <div class="card-header d-flex justify-content-between align-items-center" style="position: relative; padding: 1.5rem;">
                        <h5 class="mb-0"><i class="fas fa-search me-2"></i>Optimisation SEO</h5>

                        <!-- Bouton Génération IA -->
                        <button type="button" id="generate-seo-btn" onclick="generateSEO()" class="btn btn-gradient-ai" style="position: relative; z-index: 10; display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; font-weight: 600; border: none; border-radius: 8px; color: white; background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); box-shadow: 0 4px 6px -1px rgba(124, 58, 237, 0.3);">
                            <i class="fas fa-magic"></i>
                            <span>Générer avec IA</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Statut de génération -->
                        <div id="ai-status" class="alert alert-info" style="display: none;">
                            <div class="d-flex align-items-center">
                                <div class="spinner-border spinner-border-sm me-2" role="status">
                                    <span class="visually-hidden">Génération...</span>
                                </div>
                                <span>L'IA génère votre contenu SEO optimisé...</span>
                            </div>
                        </div>

                        <!-- Meta Title -->
                        <div class="mb-4">
                            <label for="meta_title" class="form-label">
                                Meta Title
                                <span class="badge bg-success ms-2" id="meta-title-ai-badge" style="display: none;">
                                    <i class="fas fa-robot"></i> Généré par IA
                                </span>
                            </label>
                            <input type="text" class="form-control modern-input"
                                   id="meta_title" name="meta_title" value="{{ old('meta_title', $actualite->meta_title) }}"
                                   placeholder="Titre optimisé pour les moteurs de recherche">
                            <small class="text-muted"><span id="meta-title-count">{{ strlen($actualite->meta_title ?? '') }}</span>/60 caractères recommandés</small>
                        </div>

                        <!-- Meta Description -->
                        <div class="mb-4">
                            <label for="meta_description" class="form-label">
                                Meta Description
                                <span class="badge bg-success ms-2" id="meta-desc-ai-badge" style="display: none;">
                                    <i class="fas fa-robot"></i> Généré par IA
                                </span>
                            </label>
                            <textarea class="form-control modern-input"
                                      id="meta_description" name="meta_description" rows="3"
                                      placeholder="Description pour les résultats de recherche">{{ old('meta_description', $actualite->meta_description) }}</textarea>
                            <small class="text-muted"><span id="meta-desc-count">{{ strlen($actualite->meta_description ?? '') }}</span>/160 caractères recommandés</small>
                        </div>

                        <!-- Mots-clés -->
                        <div class="mb-4">
                            <label for="meta_keywords" class="form-label">
                                Mots-clés (séparés par des virgules)
                                <span class="badge bg-success ms-2" id="keywords-ai-badge" style="display: none;">
                                    <i class="fas fa-robot"></i> Généré par IA
                                </span>
                            </label>
                            <input type="text" class="form-control modern-input"
                                   id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $actualite->meta_keywords) }}"
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
                            <label class="form-label">Statut <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status_draft"
                                       value="draft" {{ old('status', $actualite->status) == 'draft' ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_draft">
                                    <i class="fas fa-file-alt me-1"></i>Brouillon
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status_published"
                                       value="published" {{ old('status', $actualite->status) == 'published' ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_published">
                                    <i class="fas fa-check-circle me-1"></i>Publié
                                </label>
                            </div>
                        </div>

                        <!-- À la une -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                       {{ old('is_featured', $actualite->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    <i class="fas fa-star me-1"></i>Mettre à la une
                                </label>
                            </div>
                            <small class="text-muted">L'actualité apparaîtra en priorité sur la page d'accueil</small>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Mettre à jour
                            </button>
                            <a href="{{ route('admin.articles.actualites') }}" class="btn btn-outline-secondary">
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
                        @if($actualite->cover_image)
                        <div class="mb-3">
                            <label class="form-label">Image actuelle</label>
                            <img src="{{ asset('storage/' . $actualite->cover_image) }}"
                                 alt="{{ $actualite->title }}"
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
                                   id="cover_image_alt" name="cover_image_alt" value="{{ old('cover_image_alt', $actualite->cover_image_alt) }}"
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
                                       value="public" {{ old('visibility', $actualite->visibility) == 'public' ? 'checked' : '' }}>
                                <label class="form-check-label" for="visibility_public">
                                    <i class="fas fa-globe me-1"></i>Visiteurs (Public)
                                </label>
                                <small class="text-muted d-block ms-4">Visible par tous les visiteurs du site</small>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="visibility" id="visibility_all"
                                       value="all" {{ old('visibility', $actualite->visibility) == 'all' ? 'checked' : '' }}>
                                <label class="form-check-label" for="visibility_all">
                                    <i class="fas fa-users me-1"></i>Toutes les formations
                                </label>
                                <small class="text-muted d-block ms-4">Visible par tous les étudiants inscrits</small>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="visibility" id="visibility_specific"
                                       value="specific" {{ old('visibility', $actualite->visibility) == 'specific' ? 'checked' : '' }}>
                                <label class="form-check-label" for="visibility_specific">
                                    <i class="fas fa-user-graduate me-1"></i>Formations spécifiques
                                </label>
                                <small class="text-muted d-block ms-4">Visible uniquement par certaines formations</small>
                            </div>
                        </div>

                        <!-- Sélection des formations -->
                        <div id="formations-select" style="display: {{ old('visibility', $actualite->visibility) == 'specific' ? 'block' : 'none' }};">
                            <label for="formations" class="form-label">Sélectionnez les formations</label>
                            <select class="form-select modern-input" id="formations" name="formations[]" multiple size="5">
                                @foreach($formations as $formation)
                                    @php
                                        // Handle both JSON string and array
                                        $formationsData = is_array($actualite->formations)
                                            ? $actualite->formations
                                            : (json_decode($actualite->formations, true) ?? []);
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
<link href="{{ asset('vendor/quill/quill.snow.css') }}" rel="stylesheet">
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

    #image-preview img {
        border: 2px solid #334155;
    }

    /* Quill Editor Custom Styles */
    #quill-editor {
        display: block !important;
        height: auto !important;
        min-height: 300px !important;
        background-color: #0f172a !important;
        border-radius: 0 0 12px 12px !important;
        border: 2px solid #334155 !important;
        border-top: none !important;
    }

    .ql-toolbar.ql-snow {
        background-color: #1e293b !important;
        border: 2px solid #334155 !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
        position: relative;
        z-index: 2;
    }

    .ql-container.ql-snow {
        border: none !important;
        background-color: transparent !important;
    }

    .ql-editor {
        color: #e2e8f0 !important;
        min-height: 300px !important;
        font-size: 1rem;
        line-height: 1.6;
        padding: 1rem !important;
        cursor: text !important;
    }

    .ql-editor.ql-blank::before {
        color: #64748b !important;
        font-style: italic;
        left: 1rem !important;
    }

    /* Alert customization */
    .alert-info {
        background-color: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.3);
        color: #60a5fa;
        border-radius: 12px;
    }

    .alert-success {
        background-color: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #4ade80;
        border-radius: 12px;
    }

    .alert-danger {
        background-color: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #f87171;
        border-radius: 12px;
    }

    /* Badge AI */
    .badge.bg-success {
        background-color: rgba(34, 197, 94, 0.2) !important;
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, 0.4);
        font-weight: 500;
        padding: 0.25rem 0.5rem;
    }

    /* Animation pour le champ rempli */
    @keyframes fillIn {
        0% {
            background-color: rgba(102, 126, 234, 0.1);
        }
        100% {
            background-color: rgba(255, 255, 255, 0.05);
        }
    }

    .ai-filled {
        animation: fillIn 1s ease-out;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    console.log('=== DÉMARRAGE SCRIPTS ===');

    // ============ FONCTION GLOBALE GÉNÉRATION IA ============
    window.generateSEO = async function() {
        console.log('🚀 Démarrage fonction generateSEO()');

        // Récupérer les éléments
        const btn = document.getElementById('generate-seo-btn');
        const aiStatus = document.getElementById('ai-status');
        const titleInput = document.getElementById('title');
        const excerptInput = document.getElementById('excerpt');
        const metaTitleInput = document.getElementById('meta_title');
        const metaDescInput = document.getElementById('meta_description');
        const keywordsInput = document.getElementById('meta_keywords');

        const title = titleInput.value.trim();
        const excerpt = excerptInput.value.trim();

        console.log('Données:', { title, excerpt });

        // Validation
        if (!title || !excerpt) {
            alert('Veuillez d\'abord remplir le "Titre" et la "Description courte" !');
            return;
        }

        // UI Loading
        const originalBtnContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération...';
        aiStatus.style.display = 'block';

        try {
            console.log('Envoi requête API...');
            const response = await fetch('{{ route("admin.api.generate-seo") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ title, excerpt })
            });

            const data = await response.json();
            console.log('Réponse API:', data);

            if (data.success) {
                // Remplir les champs
                metaTitleInput.value = data.data.meta_title;
                metaDescInput.value = data.data.meta_description;
                keywordsInput.value = data.data.keywords;

                // Mettre à jour les compteurs
                if(document.getElementById('meta-title-count'))
                    document.getElementById('meta-title-count').textContent = data.data.meta_title.length;
                if(document.getElementById('meta-desc-count'))
                    document.getElementById('meta-desc-count').textContent = data.data.meta_description.length;

                // Feedback visuel
                alert('✨ SEO généré avec succès !');
            } else {
                throw new Error(data.message || 'Erreur inconnue');
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('❌ Erreur: ' + error.message);
        } finally {
            // Restaurer bouton
            btn.disabled = false;
            btn.innerHTML = originalBtnContent;
            aiStatus.style.display = 'none';
        }
    };

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
