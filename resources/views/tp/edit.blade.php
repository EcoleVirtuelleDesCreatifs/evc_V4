@extends('layouts.ki-admin')

@section('title', 'Modifier le Projet - ' . $project->title)

@section('content')
@php
    // Définir formationSlug par défaut si non défini
    if (!isset($formationSlug)) {
        $formationSlug = 'community-management';
    }
@endphp
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-bullhorn fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="mb-1 fw-bold">Modifier le projet</h2>
                            <p class="mb-0 text-muted">Modifiez votre projet</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <form id="projectForm" method="POST" action="{{ route($formationSlug . '.tp.update', $project->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{ $project->id }}">

        @php
            $isReport = false;
            if (!empty($project->files)) {
                foreach ($project->files as $f) {
                    $ext = strtolower(pathinfo($f->original_name ?? '', PATHINFO_EXTENSION));
                    if ($ext === 'pdf') {
                        $isReport = true;
                        break;
                    }
                }
            }
        @endphp
        @if($isReport)
            <input type="hidden" name="redirect_to" value="documents">
        @endif

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Informations du Projet -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Informations du Projet
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Titre du Projet -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">
                                Titre du Projet <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                class="form-control form-control-lg"
                                id="title"
                                name="title"
                                required
                                maxlength="255"
                                value="{{ $project->title }}"
                                placeholder="Ex: Campagne Instagram pour marque de mode"
                            >
                            <div class="form-text">Maximum 255 caractères</div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                Description
                            </label>
                            <textarea
                                id="description"
                                name="description"
                                placeholder="Décrivez votre projet de Community Management en détail : objectifs, cibles, stratégies, résultats attendus..."
                            >{!! $project->description ?? '' !!}</textarea>
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
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-file-upload text-primary me-2"></i>
                            Images et Documents
                        </h5>
                        <button type="button" class="btn btn-primary btn-sm" id="addFileBtn">
                            <i class="fas fa-plus me-1"></i>Ajouter un fichier
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <!-- Fichiers existants -->
                        @if($project->files && count($project->files) > 0)
                            @php
                                // Fonction pour obtenir l'URL correcte du fichier
                                $getFileUrl = function($filePath) {
                                    $path = str_replace(['public/', 'storage/'], '', $filePath);
                                    $path = ltrim($path, '/');

                                    // Cas spécifique pour le dossier uploads
                                    if (str_starts_with($path, 'uploads/')) {
                                        return asset($path);
                                    }

                                    // Fallback vers storage
                                    return \App\Models\MediaUrl::fromPath($path);
                                };

                                $isImage = function($file) {
                                    if (isset($file->mime_type) && str_starts_with($file->mime_type, 'image/')) {
                                        return true;
                                    }
                                    $ext = strtolower(pathinfo($file->original_name ?? '', PATHINFO_EXTENSION));
                                    return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg']);
                                };
                            @endphp
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-paperclip me-2"></i>
                                Fichiers actuels ({{ count($project->files) }})
                            </h6>
                            <div class="row g-3">
                                @foreach($project->files as $file)
                                    @php
                                        $fileUrl = $getFileUrl($file->file_path);
                                    @endphp
                                <div class="col-md-6">
                                    <div class="card border shadow-sm">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    @if($isImage($file))
                                                        <div class="mb-2">
                                                            <img src="{{ $fileUrl }}"
                                                                 class="img-fluid rounded shadow-sm"
                                                                 style="max-height: 200px; width: 100%; object-fit: cover; border: 2px solid #e9ecef;"
                                                                 alt="{{ $file->original_name }}"
                                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                            <div class="d-none w-100 align-items-center justify-content-center flex-column" style="height: 200px; background: #f8f9fa;">
                                                                <i class="fas fa-image text-muted fa-3x mb-2"></i>
                                                                <small class="text-muted">Image non disponible</small>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="text-center mb-2 p-3" style="background: #f8f9fa; border-radius: 8px;">
                                                            <i class="fas fa-file fa-3x text-primary"></i>
                                                        </div>
                                                    @endif
                                                    <p class="mb-1 fw-bold text-truncate" style="font-size: 0.9rem;">{{ $file->original_name }}</p>
                                                    <p class="mb-0 text-muted" style="font-size: 0.85rem;">{{ number_format($file->file_size / 1024, 2) }} KB</p>
                                                </div>
                                                <button type="button" class="btn btn-sm text-white ms-2"
                                                        style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: none; border-radius: 8px; padding: 0.4rem 0.6rem; transition: all 0.3s ease;"
                                                        onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 4px 12px rgba(240, 147, 251, 0.4)';"
                                                        onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';"
                                                        onclick="deleteFile({{ $file->id }}, '{{ addslashes($file->original_name) }}')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <hr class="my-4">
                        </div>
                        @endif

                        <div class="alert alert-info border-0 mb-4">
                            <div class="d-flex">
                                <i class="fas fa-info-circle me-2 mt-1"></i>
                                <div>
                                    <strong>Fichiers acceptés :</strong>
                                    <ul class="mb-0 mt-2">
                                        <li><strong>Images :</strong> JPG, PNG, GIF, WEBP, SVG</li>
                                        <li><strong>Documents :</strong> PDF, DOC, DOCX, ZIP, RAR</li>
                                        <li><strong>Taille max :</strong> 20MB par fichier</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Container pour les nouveaux fichiers -->
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-plus-circle me-2"></i>
                            Ajouter de nouveaux fichiers
                        </h6>
                        <div id="filesContainer" class="row g-3">
                            <!-- Les nouveaux fichiers seront ajoutés ici dynamiquement -->
                        </div>

                        <!-- Zone de drag & drop globale -->
                        <div
                            id="globalDropZone"
                            class="border-2 border-dashed rounded p-5 text-center mt-4"
                            style="border-color: #dee2e6; background: #f8f9fa; min-height: 200px; cursor: pointer;"
                        >
                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Glissez vos fichiers ici</h5>
                            <p class="text-muted mb-3">ou cliquez pour sélectionner</p>
                            <button type="button" class="btn btn-outline-primary" id="selectFilesBtn">
                                <i class="fas fa-folder-open me-2"></i>Parcourir les fichiers
                            </button>
                            <input type="file" id="globalFileInput" name="files[]" multiple accept="image/*,.pdf,.doc,.docx,.zip,.rar" style="display: none;">
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route(request()->segment(3) . '.documents.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <span class="btn-text">
                                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
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
        <div class="card h-100 border">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="file-icon me-2">
                        <i class="fas fa-file fa-2x text-primary"></i>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-file">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="file-preview mb-2" style="display: none;">
                    <img class="img-fluid rounded" style="max-height: 150px; width: 100%; object-fit: cover;">
                </div>
                <div class="file-info">
                    <p class="mb-1 small fw-semibold file-name text-truncate"></p>
                    <p class="mb-0 small text-muted file-size"></p>
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
    const selectFilesBtn = document.getElementById('selectFilesBtn');
    const globalFileInput = document.getElementById('globalFileInput');
    const fileTemplate = document.getElementById('fileItemTemplate');

    // Ajouter un fichier via le bouton
    addFileBtn.addEventListener('click', function() {
        addFileItem();
    });

    // Sélectionner des fichiers via le bouton
    selectFilesBtn.addEventListener('click', function() {
        globalFileInput.click();
    });

    // Clic sur la zone de drop
    globalDropZone.addEventListener('click', function(e) {
        if (e.target !== selectFilesBtn && !selectFilesBtn.contains(e.target)) {
            globalFileInput.click();
        }
    });

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
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);

        // Créer un DataTransfer pour assigner le fichier à l'input
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;

        // Icône selon le type
        if (file.type.startsWith('image/')) {
            fileIcon.className = 'fas fa-image fa-2x text-success';
            // Prévisualisation de l'image
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                filePreview.style.display = 'block';
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

// ========== Fonction de suppression de fichier ==========
function deleteFile(fileId, fileName) {
    // Créer un modal de confirmation avec style Instagram
    const modalHtml = `
        <div class="modal fade" id="deleteFileModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
                    <div class="modal-header text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: none;">
                        <h5 class="modal-title" style="font-weight: 700;">
                            <i class="fas fa-exclamation-triangle me-2"></i>Supprimer le fichier
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="mb-3" style="font-size: 1.05rem;">
                            Êtes-vous sûr de vouloir supprimer ce fichier ?
                        </p>
                        <div class="alert alert-warning" style="border-radius: 12px; border: none; background: linear-gradient(135deg, #FFF3CD 0%, #FFE69C 100%);">
                            <i class="fas fa-file me-2"></i>
                            <strong>${fileName}</strong>
                        </div>
                        <p class="text-danger mb-0" style="font-size: 0.95rem;">
                            <i class="fas fa-info-circle me-2"></i>
                            Cette action est irréversible.
                        </p>
                    </div>
                    <div class="modal-footer" style="border: none; padding: 1.5rem;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 12px; padding: 0.6rem 1.5rem;">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                        <button type="button" class="btn text-white" onclick="confirmDeleteFile(${fileId})" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: none; border-radius: 12px; padding: 0.6rem 1.5rem; font-weight: 600;">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Ajouter le modal au DOM
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Afficher le modal
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteFileModal'));
    deleteModal.show();

    // Nettoyer le modal après fermeture
    document.getElementById('deleteFileModal').addEventListener('hidden.bs.modal', function () {
        this.remove();
    });
}

// Confirmer la suppression du fichier
function confirmDeleteFile(fileId) {
    // Créer un formulaire pour envoyer la requête DELETE
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ url('/') }}/evc/compte/{{ request()->segment(3) }}/tp/{{ $project->id }}/fichier/${fileId}`;

    // Token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
    }

    // Méthode DELETE
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    form.appendChild(methodInput);

    // Ajouter au DOM et soumettre
    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection
