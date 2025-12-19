@extends('layouts.ki-admin')

@section('title', 'Publier un Travail Pratique')

@section('content')
@php
    // Détection automatique de la formation depuis l'URL
    $currentModule = request()->segment(3); // design-graphique, community-management, etc.
    $routePrefix = $currentModule;
@endphp

<style>
    /* Blue-Orange Color Palette (Design & CM) */
    .instagram-gradient {
        background: linear-gradient(135deg, #2563eb 0%, #f97316 100%);
    }

    .instagram-header {
        background: linear-gradient(135deg, #2563eb 0%, #3b82f6 50%, #f97316 100%);
        border-radius: 24px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(131, 58, 180, 0.3);
    }

    .instagram-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1) rotate(0deg); }
        50% { transform: scale(1.1) rotate(5deg); }
    }

    .instagram-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(37, 99, 235, 0.1);
        transition: all 0.3s ease;
        border: 2px solid rgba(37, 99, 235, 0.1);
    }

    .instagram-card:hover {
        box-shadow: 0 8px 32px rgba(37, 99, 235, 0.2);
        border-color: rgba(37, 99, 235, 0.3);
    }

    .instagram-card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-bottom: 2px solid transparent;
        border-image: linear-gradient(90deg, #2563eb, #3b82f6, #f97316) 1;
        padding: 1.5rem;
    }

    .instagram-btn {
        background: linear-gradient(135deg, #2563eb 0%, #f97316 100%);
        border: none;
        color: white;
        font-weight: 700;
        padding: 0.75rem 2rem;
        border-radius: 30px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(37, 99, 235, 0.3);
    }

    .instagram-btn:hover {
        background: linear-gradient(135deg, #3b82f6 0%, #fb923c 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(37, 99, 235, 0.4);
        color: white;
    }

    .form-control:focus, .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.15);
    }

    .icon-circle {
        width: 80px;
        height: 80px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
    }

    #globalDropZone:hover {
        border-color: #2563eb !important;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(249, 115, 22, 0.1) 100%) !important;
        transform: scale(1.01);
    }

    #globalDropZone.drag-over {
        border-color: #f97316 !important;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.15) 0%, rgba(249, 115, 22, 0.15) 100%) !important;
        transform: scale(1.02);
    }

    .btn-outline-secondary {
        border: 2px solid #dee2e6;
        color: #6c757d;
        font-weight: 600;
        border-radius: 30px;
        padding: 0.75rem 2rem;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background: #6c757d;
        border-color: #6c757d;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
    }

    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:hover, .form-select:hover {
        border-color: rgba(37, 99, 235, 0.3);
    }

    /* Mobile Responsive Adjustments */
    @media (max-width: 768px) {
        .icon-circle {
            width: 50px;
            height: 50px;
        }
        .icon-circle i {
            font-size: 1.2rem !important; /* Reduire la taille de l'icone */
        }
        .instagram-header h2 {
            font-size: 1.3rem !important; /* Reduire le titre */
        }
        .instagram-header p {
            font-size: 0.85rem !important;
        }
        .instagram-header .card-body {
            padding: 1.5rem !important;
        }
        .me-4 {
            margin-right: 1rem !important;
        }
        .instagram-btn, .btn-outline-secondary {
            padding: 0.6rem 0.8rem !important;
            font-size: 0.75rem !important; /* Police plus petite */
            white-space: nowrap !important;
        }
        /* Cible spécifique pour "Ajouter un fichier" dans le header */
        .card-header .instagram-btn {
            padding: 0.3rem 0.6rem !important;
            font-size: 0.7rem !important;
            width: auto !important;
        }
        /* Cible spécifique pour "Parcourir" */
        #globalBrowseBtn {
            font-size: 0.75rem !important;
            padding: 0.5rem 1rem !important;
        }
        #submitBtn {
            font-size: 1rem !important;
            padding: 0.8rem 1rem !important;
        }
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="instagram-header">
                <div class="card-body p-5 position-relative">
                    <div class="d-flex align-items-center">
                        <div class="me-4">
                            <div class="icon-circle">
                                <i class="fas fa-bullhorn fa-3x"></i>
                            </div>
                        </div>
                        <div>
                            @php
                                $isCombinedProfile = $currentModule === 'design-graphique-cm';
                            @endphp
                            <h2 class="mb-2 fw-bold" style="font-size: 2rem;">
                                @if($isCombinedProfile)
                                    Publier un Travail Pratique
                                @else
                                    Nouveau projet Design Graphique
                                @endif
                            </h2>
                            <p class="mb-0 opacity-90" style="font-size: 1.05rem;">Publiez votre TP pour constituer votre Pressbook</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <form id="projectForm" method="POST" action="{{ route($routePrefix . '.tp.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="type" value="digital">

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Informations du Projet -->
                <div class="card instagram-card mb-4">
                    <div class="card-header instagram-card-header">
                        <h5 class="mb-0 fw-bold" style="background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            <i class="fas fa-info-circle me-2" style="background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                            Informations du Projet
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @if($isCombinedProfile)
                        <!-- Type de réalisation (pour profil combiné uniquement) -->
                        <div class="mb-4">
                            <label for="formation" class="form-label fw-semibold">
                                Type de réalisation <span class="text-danger">*</span>
                            </label>
                            <select
                                id="formation"
                                name="formation"
                                class="form-select form-select-lg @error('formation') is-invalid @enderror"
                                required
                            >
                                <option value="">Sélectionnez le type de réalisation</option>
                                <option value="Design Graphique" {{ old('formation') == 'Design Graphique' ? 'selected' : '' }}>
                                    <i class="fas fa-pen-nib"></i> Design Graphique
                                </option>
                                <option value="Community Management" {{ old('formation') == 'Community Management' ? 'selected' : '' }}>
                                    <i class="fas fa-comments"></i> Community Management
                                </option>
                            </select>
                            @error('formation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Indiquez si ce TP concerne le Design Graphique ou le Community Management</div>
                        </div>
                        @else
                        <input type="hidden" name="formation" value="Design Graphique">
                        @endif

                        <!-- Titre du Projet -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">
                                Titre du Projet <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                class="form-control form-control-lg @error('title') is-invalid @enderror"
                                id="title"
                                name="title"
                                value="{{ old('title') }}"
                                required
                                maxlength="255"
                                placeholder="Ex: Campagne Instagram pour marque de mode"
                            >
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 255 caractères</div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                Description <span class="text-danger">*</span>
                            </label>
                            <textarea
                                id="description"
                                name="description"
                                class="@error('description') is-invalid @enderror"
                                required
                                placeholder="Décrivez votre projet de Community Management en détail : objectifs, cibles, stratégies, résultats attendus..."
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text d-flex justify-content-between mt-2">
                                <span>Décrivez votre projet en détail</span>
                                <span><span id="charCount">0</span>/2000 caractères</span>
                            </div>
                        </div>

                        <!-- Liens (multiples) -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Liens du Projet <span class="text-muted">(optionnel)</span>
                            </label>
                            <div id="linksContainer">
                                <div class="input-group mb-2">
                                    <span class="input-group-text">
                                        <i class="fas fa-link"></i>
                                    </span>
                                    <input
                                        type="url"
                                        class="form-control"
                                        name="links[]"
                                        placeholder="https://example.com"
                                    >
                                    <button type="button" class="btn btn-outline-danger remove-link" style="display: none;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addLinkBtn">
                                <i class="fas fa-plus me-1"></i>Ajouter un autre lien
                            </button>
                            <div class="form-text mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Ajoutez des liens vers vos réseaux sociaux, campagnes, articles, etc.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Fichiers -->
                <div class="card instagram-card mb-4">
                    <div class="card-header instagram-card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold" style="background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            <i class="fas fa-paperclip me-2" style="background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                            Fichiers du Projet
                        </h5>
                        <button type="button" class="btn instagram-btn btn-sm" id="addFileBtn" style="padding: 0.5rem 1.2rem; font-size: 0.875rem;">
                            <i class="fas fa-plus me-1"></i>Ajouter un fichier
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert border-0 mb-4" style="background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(249, 115, 22, 0.1) 100%); border-left: 4px solid #2563eb !important;">
                            <div class="d-flex">
                                <i class="fas fa-info-circle me-3 mt-1" style="color: #2563eb; font-size: 1.2rem;"></i>
                                <div style="color: #4a5568;">
                                    <strong style="color: #2563eb;">Formats acceptés :</strong> Images (JPG, PNG, GIF), PDF, Documents Word, Archives (ZIP, RAR)<br>
                                    <strong style="color: #2563eb;">Taille maximale :</strong> 10 Mo par fichier
                                </div>
                            </div>
                        </div>
                        <div
                            id="globalDropZone"
                            class="border-2 border-dashed rounded-4 p-5 text-center mt-4"
                            style="border-color: rgba(37, 99, 235, 0.3); background: linear-gradient(135deg, rgba(37, 99, 235, 0.05) 0%, rgba(249, 115, 22, 0.05) 100%); min-height: 200px; cursor: pointer; transition: all 0.3s ease;"
                        >
                            <i class="fas fa-cloud-upload-alt fa-3x mb-3" style="background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                            <h5 class="fw-bold" style="background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Glissez vos fichiers ici</h5>
                            <p class="text-muted mb-3">ou</p>
                            <button type="button" class="btn instagram-btn" id="globalBrowseBtn">
                                <i class="fas fa-folder-open me-2"></i>Parcourir les fichiers
                            </button>
                            <input type="file" id="globalFileInput" name="files[]" multiple accept="image/*,.pdf,.doc,.docx,.zip,.rar" style="display: none;">
                        </div>

                        <!-- Conteneur pour les fichiers ajoutés -->
                        <div id="filesContainer" class="row g-3 mt-4"></div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column-reverse flex-md-row gap-2 justify-content-end">
                            <a href="{{ route($routePrefix . '.tp.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn instagram-btn btn-lg w-100 w-md-auto" id="submitBtn" style="font-size: 1.1rem; padding: 1rem 2rem;">
                                <span class="btn-text">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Soumettre le Projet
                                </span>
                                <span class="btn-loading" style="display: none;">
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    Envoi en cours...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Template pour les fichiers -->
<template id="fileItemTemplate">
    <div class="col-md-6 file-item">
        <div class="card h-100 border shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="file-icon me-2">
                        <i class="fas fa-file fa-2x text-primary"></i>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger remove-file">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!-- Aperçu de l'image (caché par défaut, affiché pour les images) -->
                <div class="file-preview mb-3" style="display: none;">
                    <img class="img-fluid rounded shadow-sm" style="max-height: 200px; width: 100%; object-fit: cover; border: 2px solid #e9ecef;">
                </div>
                <div class="file-info">
                    <p class="mb-1 fw-bold file-name text-truncate" style="font-size: 0.9rem;"></p>
                    <p class="mb-0 text-muted file-size" style="font-size: 0.85rem;"></p>
                </div>
                <input type="file" class="file-input" name="files[]" accept="image/*,.pdf,.doc,.docx,.zip,.rar" style="display: none;">
            </div>
        </div>
    </div>
</template>
@endsection

@section('styles')
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .file-item {
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #globalDropZone.dragover {
        border-color: #0d6efd !important;
        background-color: #e7f1ff !important;
        transform: scale(1.01);
        transition: all 0.2s ease;
    }

    .file-item .card {
        transition: all 0.2s ease;
    }

    .file-item .card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    /* Style pour Summernote */
    .note-editor {
        border-radius: 0.375rem;
    }

    .note-toolbar {
        background: #f8f9fa;
    }
</style>
@endsection

@section('scripts')
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========== Initialisation de Summernote ==========
    $('#description').summernote({
        height: 300,
        placeholder: 'Décrivez votre projet de Community Management en détail : objectifs, cibles, stratégies, résultats attendus...',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        disableDragAndDrop: true,
        callbacks: {
            onChange: function(contents, $editable) {
                // Nettoyer les attributs data-* de Summernote
                let cleanedContent = contents;
                cleanedContent = cleanedContent.replace(/\s*data-start="[^"]*"/g, '');
                cleanedContent = cleanedContent.replace(/\s*data-end="[^"]*"/g, '');

                // Mettre à jour le textarea avec le contenu nettoyé
                $('#description').val(cleanedContent);

                // Mettre à jour le compteur de caractères
                const text = $editable.text().trim();
                const charCount = document.getElementById('charCount');
                if (charCount) {
                    charCount.textContent = text.length;
                }
            }
        }
    });

    // ========== Gestion des liens multiples ==========
    const linksContainer = document.getElementById('linksContainer');
    const addLinkBtn = document.getElementById('addLinkBtn');

    addLinkBtn.addEventListener('click', function() {
        const linkGroup = document.createElement('div');
        linkGroup.className = 'input-group mb-2';
        linkGroup.innerHTML = `
            <span class="input-group-text">
                <i class="fas fa-link"></i>
            </span>
            <input type="url" class="form-control" name="links[]" placeholder="https://example.com">
            <button type="button" class="btn btn-outline-danger remove-link">
                <i class="fas fa-times"></i>
            </button>
        `;
        linksContainer.appendChild(linkGroup);
        updateRemoveButtons();
    });

    linksContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-link')) {
            e.target.closest('.input-group').remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const linkGroups = linksContainer.querySelectorAll('.input-group');
        linkGroups.forEach((group) => {
            const removeBtn = group.querySelector('.remove-link');
            if (linkGroups.length > 1) {
                removeBtn.style.display = 'block';
            } else {
                removeBtn.style.display = 'none';
            }
        });
    }

    // ========== Compteur de caractères ==========
    const description = document.getElementById('description');
    const charCount = document.getElementById('charCount');

    if (description && charCount) {
        description.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
    }

    // ========== Gestion des fichiers ==========
    const filesContainer = document.getElementById('filesContainer');
    const addFileBtn = document.getElementById('addFileBtn');
    const globalDropZone = document.getElementById('globalDropZone');
    const globalBrowseBtn = document.getElementById('globalBrowseBtn');
    const globalFileInput = document.getElementById('globalFileInput');
    const fileTemplate = document.getElementById('fileItemTemplate');

    console.log('✅ Éléments fichiers trouvés:', {
        filesContainer: !!filesContainer,
        addFileBtn: !!addFileBtn,
        globalDropZone: !!globalDropZone,
        globalBrowseBtn: !!globalBrowseBtn,
        globalFileInput: !!globalFileInput,
        fileTemplate: !!fileTemplate
    });

    // Ajouter un fichier via le bouton "Ajouter un fichier"
    if (addFileBtn) {
        addFileBtn.addEventListener('click', function() {
            console.log('🔵 Clic sur Ajouter un fichier');
            addFileItem();
        });
    }

    // Sélectionner des fichiers via le bouton "Parcourir"
    if (globalBrowseBtn && globalFileInput) {
        globalBrowseBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('📁 Clic sur Parcourir les fichiers');
            globalFileInput.click();
        });
    }

    // Clic sur la zone de drop
    if (globalDropZone && globalFileInput) {
        globalDropZone.addEventListener('click', function(e) {
            if (e.target !== globalBrowseBtn && !globalBrowseBtn.contains(e.target)) {
                console.log('📦 Clic sur zone de drop');
                globalFileInput.click();
            }
        });
    }

    // Gestion du changement de fichier global
    globalFileInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        files.forEach(file => {
            addFileItem(file);
        });
        // Réinitialiser l'input
        globalFileInput.value = '';
    });

    // Drag & Drop sur la zone globale
    globalDropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.add('dragover');
    });

    globalDropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('dragover');
    });

    globalDropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('dragover');

        const files = Array.from(e.dataTransfer.files);
        files.forEach(file => {
            addFileItem(file);
        });
    });

    // Fonction pour ajouter un item de fichier
    function addFileItem(file = null) {
        const clone = fileTemplate.content.cloneNode(true);
        const fileItem = clone.querySelector('.file-item');
        const fileInput = clone.querySelector('.file-input');
        const fileName = clone.querySelector('.file-name');
        const fileSize = clone.querySelector('.file-size');
        const fileIcon = clone.querySelector('.file-icon i');
        const filePreview = clone.querySelector('.file-preview');
        const previewImg = clone.querySelector('.file-preview img');
        const removeBtn = clone.querySelector('.remove-file');

        // Si un fichier est fourni, l'afficher
        if (file) {
            displayFile(file, fileInput, fileName, fileSize, fileIcon, filePreview, previewImg);
        } else {
            // Sinon, permettre la sélection
            fileInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    displayFile(e.target.files[0], fileInput, fileName, fileSize, fileIcon, filePreview, previewImg);
                }
            });

            // Clic sur la carte pour sélectionner un fichier
            fileItem.addEventListener('click', function(e) {
                if (!e.target.closest('.remove-file')) {
                    fileInput.click();
                }
            });
        }

        // Supprimer le fichier
        removeBtn.addEventListener('click', function() {
            fileItem.remove();
        });

        filesContainer.appendChild(clone);
    }

    // Fonction pour afficher un fichier
    function displayFile(file, fileInput, fileName, fileSize, fileIcon, filePreview, previewImg) {
        console.log('📁 Affichage du fichier:', file.name, 'Type:', file.type);

        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);

        // Créer un DataTransfer pour assigner le fichier à l'input
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;

        // Icône selon le type
        if (file.type.startsWith('image/')) {
            console.log('🖼️ Image détectée, chargement de la prévisualisation...');
            fileIcon.className = 'fas fa-image fa-2x text-success';

            // Prévisualisation de l'image
            const reader = new FileReader();
            reader.onload = function(e) {
                console.log('✅ Image chargée, affichage de la prévisualisation');
                previewImg.src = e.target.result;
                filePreview.style.display = 'block';

                // Masquer l'icône pour laisser place à l'image
                if (fileIcon && fileIcon.parentElement) {
                    fileIcon.parentElement.style.display = 'none';
                }
            };
            reader.onerror = function(error) {
                console.error('❌ Erreur lors du chargement de l\'image:', error);
            };
            reader.readAsDataURL(file);
        } else if (file.type === 'application/pdf') {
            fileIcon.className = 'fas fa-file-pdf fa-2x text-danger';
        } else if (file.type.includes('word') || file.name.endsWith('.doc') || file.name.endsWith('.docx')) {
            fileIcon.className = 'fas fa-file-word fa-2x text-primary';
        } else if (file.type.includes('zip') || file.name.endsWith('.zip') || file.name.endsWith('.rar')) {
            fileIcon.className = 'fas fa-file-archive fa-2x text-warning';
        } else {
            fileIcon.className = 'fas fa-file fa-2x text-secondary';
        }
    }

    // Formater la taille du fichier
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    // ========== Loader de soumission ==========
    const form = document.getElementById('projectForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoading = submitBtn.querySelector('.btn-loading');

    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            if (form.checkValidity()) {
                btnText.style.display = 'none';
                btnLoading.style.display = 'inline-block';
                submitBtn.disabled = true;
            }
        });
    }
});
</script>
@endsection
