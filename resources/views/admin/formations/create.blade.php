@extends('layouts.admin')

@section('title', 'Créer une Formation')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
<style>
    .form-footer .btn-secondary {
        background-color: #4A5568 !important;
        border-color: #4A5568 !important;
        color: white !important;
    }
    .form-footer .btn-warning {
        background-color: #FBBF24 !important;
        border-color: #FBBF24 !important;
        color: #1F2937 !important;
    }
    .form-footer .btn-success {
        background-color: #10B981 !important;
        border-color: #10B981 !important;
        color: white !important;
    }
</style>
@endpush

@section('content')

<form id="creationForm" action="{{ route('admin.formations.store') }}" method="POST" enctype="multipart/form-data" class="interactive-dashboard-form">
    @csrf
    <input type="hidden" id="slug" name="slug">


    <div class="row g-4">
        <!-- Main Info & Media Row -->
        <div class="col-12">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="form-card h-100">
                        <div class="form-card-header">
                            <i class="fas fa-info-circle"></i>
                            <h3>Informations Principales</h3>
                        </div>
                        <div class="form-card-body">
                            <div class="form-group">
                                <label for="name">Titre de la formation</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Ex: Maîtriser Photoshop de A à Z" required>
                            </div>
                            <div class="form-group">
                                <label for="category_id">Catégorie thématique</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="" disabled selected>Choisir une catégorie...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-card media-card h-100">
                        <div class="form-card-header">
                            <i class="fas fa-photo-video"></i>
                            <h3>Média</h3>
                        </div>
                        <div class="form-card-body">
                            <div id="image-upload-container">
                                <div class="image-upload-zone">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Glissez-déposez une image ou cliquez</p>
                                </div>
                                <input type="file" id="image" name="image" class="d-none" accept="image/*">
                                <div class="image-preview-container d-none">
                                    <img id="image-preview" src="#" alt="Aperçu" />
                                    <button type="button" id="remove-image-btn" class="btn btn-danger btn-sm">&times;</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PDF Documents Row -->
        <div class="col-12">
            <div class="form-card">
                <div class="form-card-header">
                    <i class="fas fa-file-pdf"></i>
                    <h3>Documents PDF</h3>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label for="pdf_files">Joindre des fichiers PDF (optionnel)</label>
                        <input type="file" class="form-control" id="pdf_files" name="pdf_files[]" accept=".pdf" multiple>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Vous pouvez sélectionner plusieurs fichiers PDF (supports de cours, exercices, etc.). Taille maximale : 10 Mo par fichier.
                        </small>
                    </div>
                    <div id="pdf-preview-list" class="mt-3"></div>
                </div>
            </div>
        </div>

        <!-- Description Row -->
        <div class="col-12">
            <div class="form-card description-card">
                 <div class="form-card-header">
                    <i class="fas fa-paragraph"></i>
                    <h3>Description</h3>
                </div>
                <div class="form-card-body">
                    <div class="form-group" style="min-height: 250px;">
                        <input type="hidden" name="description" id="description-input" value="">
                        <div id="quill-editor"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- YouTube Integration Row -->
        <div class="col-12">
            <div class="form-card youtube-card">
                <div class="form-card-header">
                    <i class="fab fa-youtube"></i>
                    <h3>Configuration vidéo YouTube</h3>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label for="vimeo_code">Lien vidéo ou Code d'intégration</label>
                        <input type="text" class="form-control" id="vimeo_code" name="vimeo_code" value="{{ old('vimeo_code') }}" placeholder="URL YouTube ou Code <iframe>">
                        <div class="invalid-feedback" id="vimeo-validation-message">
                            Le lien ou le code n'est pas valide.
                        </div>
                    </div>

                    <!-- Video Preview Container -->
                    <div id="video-preview-container" class="mt-3 d-none">
                        <label class="form-label">Aperçu de la vidéo :</label>
                        <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm" style="max-width: 400px;">
                            <iframe id="video-preview-iframe" src="" title="Video preview" allowfullscreen></iframe>
                        </div>
                        <!-- Debug Info -->
                        <div class="mt-2 p-2 bg-dark rounded small text-monospace text-white-50">
                            <i class="fas fa-bug me-1"></i> <span id="debug-video-id">Aucune source détectée</span>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i> Accepte les liens YouTube (watch, embed, shorts) et les codes d'intégration (iframe).
                    </div>
                </div>
            </div>
        </div>

        <!-- Targeting Row -->


        <div class="col-12">
            <div class="form-card">
                <div class="form-card-header">
                    <i class="fas fa-bullseye"></i>
                    <h3>Ciblage et Diffusion</h3>
                </div>
                <div class="form-card-body row">
                    <div class="col-md-4 form-group">
                        <label for="module">Module Principal</label>
                        <select class="form-select" id="module" name="module" required>
                            <option value="" disabled {{ old('module') ? '' : 'selected' }}>Choisir un module...</option>
                            <option value="design-graphique" {{ old('module') == 'design-graphique' ? 'selected' : '' }}>Design Graphique</option>
                            <option value="design-graphique-cm" {{ old('module') == 'design-graphique-cm' ? 'selected' : '' }}>Design Graphique &amp; Community Management</option>
                            <option value="community-manager" {{ old('module') == 'community-manager' ? 'selected' : '' }}>Community Manager</option>
                            <option value="informatique" {{ old('module') == 'informatique' ? 'selected' : '' }}>Informatique</option>
                            <option value="intelligence-artificielle" {{ old('module') == 'intelligence-artificielle' ? 'selected' : '' }}>Intelligence Artificielle</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="type">Type de formation</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="en_ligne" {{ old('type', 'en_ligne') == 'en_ligne' ? 'selected' : '' }}>En ligne</option>
                            <option value="presentiel" {{ old('type') == 'presentiel' ? 'selected' : '' }}>Présentiel</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="destinataire">Destinataires</label>
                        <select class="form-select" id="destinataire" name="destinataire" required>
                            <option value="etudiants-actifs" {{ old('destinataire', 'etudiants-actifs') == 'etudiants-actifs' ? 'selected' : '' }}>Étudiants actifs</option>
                            <option value="etudiants-specifiques" {{ old('destinataire') == 'etudiants-specifiques' ? 'selected' : '' }}>Étudiants spécifiques</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3 d-none" id="students-select-container">
                    <div class="col-12 form-group">
                        <label for="student_ids">Sélectionner les étudiants <span class="text-danger">*</span></label>
                        <select class="form-select" id="student_ids" name="student_ids[]" multiple="multiple">
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ in_array($student->id, old('student_ids', [])) ? 'selected' : '' }}>{{ $student->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Maintenez Ctrl (Windows) ou Cmd (Mac) pour sélectionner plusieurs étudiants</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chapitres Section -->
        <div class="col-12">
            <div class="form-card">
                <div class="form-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-book-open"></i>
                        <h3>Chapitres de la Formation</h3>
                    </div>
                    <button type="button" class="btn btn-sm btn-success" onclick="addChapter()">
                        <i class="fas fa-plus me-1"></i> Ajouter un chapitre
                    </button>
                </div>
                <div class="form-card-body">
                    <div id="chapters-container">
                        <!-- Les chapitres seront ajoutés ici dynamiquement -->
                        <div class="text-center py-5" id="no-chapters-message" style="color: #9ca3af;">
                            <i class="fas fa-book-open fa-3x mb-3" style="opacity: 0.2;"></i>
                            <p class="mb-0" style="color: #6b7280; font-size: 0.95rem;">Aucun chapitre ajouté. Cliquez sur "Ajouter un chapitre" pour commencer.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Publication Options -->
        <div class="col-12">
            <div class="form-card">
                <div class="form-card-header">
                    <i class="fas fa-rocket"></i>
                    <h3>Options de Publication</h3>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label>Statut de publication <span class="text-danger">*</span></label>
                        <div class="publication-status-options">
                            <input type="radio" name="action" id="status-draft" value="draft" class="d-none" {{ old('action', 'draft') == 'draft' ? 'checked' : '' }}>
                            <label for="status-draft" class="publication-status-option {{ old('action', 'draft') == 'draft' ? 'selected' : '' }}">
                                <i class="fas fa-edit"></i>
                                <span>Brouillon</span>
                                <small>Enregistrer sans publier</small>
                            </label>

                            <input type="radio" name="action" id="status-pending" value="pending" class="d-none" {{ old('action') == 'pending' ? 'checked' : '' }}>
                            <label for="status-pending" class="publication-status-option {{ old('action') == 'pending' ? 'selected' : '' }}">
                                <i class="fas fa-hourglass-half"></i>
                                <span>En attente</span>
                                <small>En attente de validation</small>
                            </label>

                            <input type="radio" name="action" id="status-published" value="published" class="d-none" {{ old('action') == 'published' ? 'checked' : '' }}>
                            <label for="status-published" class="publication-status-option {{ old('action') == 'published' ? 'selected' : '' }}">
                                <i class="fas fa-globe"></i>
                                <span>Publié</span>
                                <small>Visible par tous</small>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="published_at">Date de publication</label>
                            <input type="datetime-local" class="form-control" id="published_at" name="published_at" value="{{ old('published_at') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="is_featured">Formation à la UNE</label>
                            <select class="form-select" id="is_featured" name="is_featured">
                                <option value="0" {{ old('is_featured', '0') == '0' ? 'selected' : '' }}>Non</option>
                                <option value="1" {{ old('is_featured') == '1' ? 'selected' : '' }}>Oui</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="form-footer mt-4 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.formations.index') }}" class="btn btn-light"><i class="fas fa-arrow-left me-2"></i>Retour à la liste</a>
        <div>
            <button type="submit" name="action" value="draft" class="btn btn-secondary"><i class="fas fa-save me-2"></i>Enregistrer le brouillon</button>
            <button type="submit" name="action" value="pending" class="btn btn-warning"><i class="fas fa-hourglass-half me-2"></i>Marquer en attente</button>
            <button type="submit" name="action" value="published" class="btn btn-success"><i class="fas fa-rocket me-2"></i>Publier la formation</button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script src="{{ asset('js/admin/formation-create.js') }}"></script>
<script>
// Gestion de l'aperçu des fichiers PDF
document.getElementById('pdf_files').addEventListener('change', function(e) {
    const files = e.target.files;
    const previewList = document.getElementById('pdf-preview-list');
    previewList.innerHTML = '';

    if (files.length > 0) {
        const listGroup = document.createElement('div');
        listGroup.className = 'list-group';

        Array.from(files).forEach((file, index) => {
            const fileSize = (file.size / 1024 / 1024).toFixed(2); // Taille en Mo
            const item = document.createElement('div');
            item.className = 'list-group-item d-flex justify-content-between align-items-center';
            item.innerHTML = `
                <div>
                    <i class="fas fa-file-pdf text-danger me-2"></i>
                    <strong>${file.name}</strong>
                    <small class="text-muted ms-2">(${fileSize} Mo)</small>
                </div>
                <span class="badge bg-success">
                    <i class="fas fa-check me-1"></i>Prêt
                </span>
            `;
            listGroup.appendChild(item);
        });

        previewList.appendChild(listGroup);

        // Afficher un message si trop de fichiers
        const totalSize = Array.from(files).reduce((sum, file) => sum + file.size, 0) / 1024 / 1024;
        if (totalSize > 50) {
            const warning = document.createElement('div');
            warning.className = 'alert alert-warning mt-2';
            warning.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Attention : La taille totale des fichiers dépasse 50 Mo.';
            previewList.appendChild(warning);
        }
    }
});

// Gestion de l'affichage dynamique du champ de sélection d'étudiants
$(document).ready(function() {
    // Initialiser Select2 pour le champ de sélection des étudiants
    $('#student_ids').select2({
        placeholder: 'Rechercher et sélectionner des étudiants...',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return 'Aucun étudiant trouvé';
            },
            searching: function() {
                return 'Recherche en cours...';
            }
        }
    });

    // Fonction pour charger les étudiants selon le module sélectionné
    function loadStudentsByModule(module) {
        if (!module) {
            $('#student_ids').empty().trigger('change');
            return;
        }

        // Afficher un loader
        $('#student_ids').empty().append('<option value="">Chargement...</option>').trigger('change');

        // Requête AJAX pour récupérer les étudiants du module
        $.ajax({
            url: '{{ route("admin.api.students-by-module") }}',
            method: 'GET',
            data: { module: module },
            success: function(response) {
                if (response.success) {
                    // Vider le select
                    $('#student_ids').empty();

                    // Ajouter les étudiants du module
                    if (response.students.length > 0) {
                        response.students.forEach(function(student) {
                            $('#student_ids').append(
                                $('<option></option>').attr('value', student.id).text(student.name)
                            );
                        });
                    } else {
                        $('#student_ids').append('<option value="">Aucun étudiant dans ce module</option>');
                    }

                    $('#student_ids').trigger('change');
                } else {
                    alert('Erreur lors du chargement des étudiants');
                }
            },
            error: function() {
                alert('Erreur lors du chargement des étudiants');
                $('#student_ids').empty().append('<option value="">Erreur de chargement</option>').trigger('change');
            }
        });
    }

    // Fonction pour afficher/masquer le champ de sélection des étudiants
    function toggleStudentsSelect() {
        const destinataire = $('#destinataire').val();
        const studentsContainer = $('#students-select-container');
        const module = $('#module').val();

        if (destinataire === 'etudiants-specifiques') {
            studentsContainer.removeClass('d-none').addClass('animate__animated animate__fadeIn');
            $('#student_ids').prop('required', true);
            // Charger les étudiants du module sélectionné
            loadStudentsByModule(module);
        } else {
            studentsContainer.addClass('d-none').removeClass('animate__animated animate__fadeIn');
            $('#student_ids').prop('required', false);
            $('#student_ids').val(null).trigger('change');
        }
    }

    // Écouter les changements du champ Module
    $('#module').on('change', function() {
        const destinataire = $('#destinataire').val();
        if (destinataire === 'etudiants-specifiques') {
            loadStudentsByModule($(this).val());
        }
    });

    // Écouter les changements du champ Destinataires
    $('#destinataire').on('change', toggleStudentsSelect);

    // Vérifier l'état initial au chargement de la page
    toggleStudentsSelect();

    // Gestion de l'aperçu vidéo YouTube
    const vimeoInput = document.getElementById('vimeo_code');
    const previewContainer = document.getElementById('video-preview-container');
    const previewIframe = document.getElementById('video-preview-iframe');
    const debugSpan = document.getElementById('debug-video-id');

    function extractVideoSource(input) {
        if (!input) return null;

        // 1. DOM Parser for Iframe (Most Robust)
        // This handles attributes in any order, spaces, and quotes correctly
        if (input.includes('<iframe')) {
            try {
                const div = document.createElement('div');
                div.innerHTML = input;
                const iframe = div.querySelector('iframe');
                if (iframe && iframe.src) {
                    return { type: 'iframe', src: iframe.src };
                }
            } catch (e) {
                console.error('Erreur parsing iframe:', e);
            }
        }

        // 2. Check for YouTube URL (Refined Regex)
        const regExp = /(?:[?&]v=|\/v\/|\/embed\/|\/shorts\/|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
        const match = input.match(regExp);
        if (match) return { type: 'youtube', id: match[1] };

        // 3. Check for Vimeo URL
        const vimeoRegExp = /(?:vimeo\.com\/|player\.vimeo\.com\/video\/)([0-9]+)/;
        const vimeoMatch = input.match(vimeoRegExp);
        if (vimeoMatch) return { type: 'vimeo', id: vimeoMatch[1] };

        return null;
    }

    function updateVideoPreview() {
        const url = vimeoInput.value;
        const source = extractVideoSource(url);

        if (source) {
            let src = '';
            let debugText = '';

            if (source.type === 'iframe') {
                src = source.src;
                // Clean YouTube URLs: use nocookie and remove tracking params
                if (src.includes('youtube.com')) {
                    src = src.replace('youtube.com', 'youtube-nocookie.com');
                    // Remove si= and feature= tracking parameters
                    src = src.replace(/[?&](si|feature)=[^&]*/g, '');
                    // Clean up double ? or &
                    src = src.replace(/\?&/, '?').replace(/&&/, '&');
                }
                debugText = 'Source Iframe détectée : ' + src.substring(0, 50) + '...';
            } else if (source.type === 'youtube') {
                // Use youtube-nocookie.com for better compatibility
                src = 'https://www.youtube-nocookie.com/embed/' + source.id + '?rel=0&modestbranding=1';
                debugText = 'ID YouTube détecté : ' + source.id;
            } else if (source.type === 'vimeo') {
                src = 'https://player.vimeo.com/video/' + source.id;
                debugText = 'ID Vimeo détecté : ' + source.id;
            }

            previewIframe.src = src;
            if (debugSpan) debugSpan.textContent = debugText;

            previewContainer.classList.remove('d-none');
            previewContainer.classList.add('animate__animated', 'animate__fadeIn');
        } else {
            previewContainer.classList.add('d-none');
            previewIframe.src = '';
            if (debugSpan) debugSpan.textContent = 'Format non reconnu';
        }
    }

    if (vimeoInput) {
        vimeoInput.addEventListener('input', updateVideoPreview);
        // Check on load in case of old input
        if (vimeoInput.value) {
            updateVideoPreview();
        }
    }

    // Gestion des chapitres
    let chapterCount = 0;

    window.addChapter = function() {
        chapterCount++;
        const container = document.getElementById('chapters-container');
        const noChaptersMsg = document.getElementById('no-chapters-message');

        if (noChaptersMsg) {
            noChaptersMsg.remove();
        }

        const chapterHtml = `
            <div class="chapter-item border rounded p-3 mb-3" style="background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.1) !important;" id="chapter-${chapterCount}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        <i class="fas fa-bookmark text-primary me-2"></i>
                        Chapitre ${chapterCount}
                    </h5>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeChapter(${chapterCount})">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </div>

                <div class="row">
                    <div class="col-md-8 form-group">
                        <label for="chapter_title_${chapterCount}">Titre du chapitre <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control"
                               id="chapter_title_${chapterCount}"
                               name="chapters[${chapterCount}][title]"
                               placeholder="Ex: Introduction au Design Graphique"
                               required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="chapter_order_${chapterCount}">Ordre <span class="text-danger">*</span></label>
                        <input type="number"
                               class="form-control"
                               id="chapter_order_${chapterCount}"
                               name="chapters[${chapterCount}][order]"
                               value="${chapterCount}"
                               min="1"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="chapter_description_${chapterCount}">Description</label>
                    <textarea class="form-control"
                              id="chapter_description_${chapterCount}"
                              name="chapters[${chapterCount}][description]"
                              rows="3"
                              placeholder="Décrivez brièvement ce qui sera couvert dans ce chapitre..."></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="chapter_duration_${chapterCount}">Durée (minutes)</label>
                        <input type="number"
                               class="form-control"
                               id="chapter_duration_${chapterCount}"
                               name="chapters[${chapterCount}][duration]"
                               placeholder="Ex: 45"
                               min="1">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="chapter_video_url_${chapterCount}">Lien vidéo (optionnel)</label>
                        <input type="text"
                               class="form-control"
                               id="chapter_video_url_${chapterCount}"
                               name="chapters[${chapterCount}][video_url]"
                               placeholder="URL YouTube ou Vimeo">
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', chapterHtml);
    }

    window.removeChapter = function(id) {
        const chapter = document.getElementById('chapter-' + id);
        if (chapter && confirm('Êtes-vous sûr de vouloir supprimer ce chapitre ?')) {
            chapter.remove();

            // Si plus aucun chapitre, afficher le message
            const container = document.getElementById('chapters-container');
            if (container.children.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5" id="no-chapters-message" style="color: #9ca3af;">
                        <i class="fas fa-book-open fa-3x mb-3" style="opacity: 0.2;"></i>
                        <p class="mb-0" style="color: #6b7280; font-size: 0.95rem;">Aucun chapitre ajouté. Cliquez sur "Ajouter un chapitre" pour commencer.</p>
                    </div>
                `;
            }
        }
    }

    // Restaurer la description Quill et les chapitres en cas d'erreur
    @if(old('description'))
        // Restaurer Quill
        setTimeout(function() {
            if (window.quill) {
                const oldDescription = {!! json_encode(old('description')) !!};
                quill.root.innerHTML = oldDescription;
                document.getElementById('description-input').value = oldDescription;
            }
        }, 500);
    @endif

    @if(old('chapters'))
        // Restaurer les chapitres
        const oldChapters = {!! json_encode(old('chapters')) !!};
        if (oldChapters && Object.keys(oldChapters).length > 0) {
            Object.keys(oldChapters).forEach(function(key) {
                const chapter = oldChapters[key];
                chapterCount++;

                const container = document.getElementById('chapters-container');
                const noChaptersMsg = document.getElementById('no-chapters-message');

                if (noChaptersMsg) {
                    noChaptersMsg.remove();
                }

                const chapterHtml = `
                    <div class="chapter-item border rounded p-3 mb-3" style="background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.1) !important;" id="chapter-${chapterCount}">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">
                                <i class="fas fa-bookmark text-primary me-2"></i>
                                Chapitre ${chapterCount}
                            </h5>
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeChapter(${chapterCount})">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-md-8 form-group">
                                <label for="chapter_title_${chapterCount}">Titre du chapitre <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control"
                                       id="chapter_title_${chapterCount}"
                                       name="chapters[${chapterCount}][title]"
                                       value="${chapter.title || ''}"
                                       placeholder="Ex: Introduction au Design Graphique"
                                       required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="chapter_order_${chapterCount}">Ordre <span class="text-danger">*</span></label>
                                <input type="number"
                                       class="form-control"
                                       id="chapter_order_${chapterCount}"
                                       name="chapters[${chapterCount}][order]"
                                       value="${chapter.order || chapterCount}"
                                       min="1"
                                       required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="chapter_description_${chapterCount}">Description</label>
                            <textarea class="form-control"
                                      id="chapter_description_${chapterCount}"
                                      name="chapters[${chapterCount}][description]"
                                      rows="3"
                                      placeholder="Décrivez brièvement ce qui sera couvert dans ce chapitre...">${chapter.description || ''}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="chapter_duration_${chapterCount}">Durée (minutes)</label>
                                <input type="number"
                                       class="form-control"
                                       id="chapter_duration_${chapterCount}"
                                       name="chapters[${chapterCount}][duration]"
                                       value="${chapter.duration || ''}"
                                       placeholder="Ex: 45"
                                       min="1">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="chapter_video_url_${chapterCount}">Lien vidéo (optionnel)</label>
                                <input type="text"
                                       class="form-control"
                                       id="chapter_video_url_${chapterCount}"
                                       name="chapters[${chapterCount}][video_url]"
                                       value="${chapter.video_url || ''}"
                                       placeholder="URL YouTube ou Vimeo">
                            </div>
                        </div>
                    </div>
                `;

                container.insertAdjacentHTML('beforeend', chapterHtml);
            });
        }
    @endif

    // Afficher le conteneur d'étudiants si "étudiants-specifiques" était sélectionné
    @if(old('destinataire') == 'etudiants-specifiques')
        document.getElementById('students-select-container').classList.remove('d-none');
        const oldModule = '{{ old("module") }}';
        if (oldModule) {
            loadStudentsByModule(oldModule);
        }
    @endif
});
</script>
@endpush
