@extends('layouts.admin')

@section('title', 'Modifier la Formation')

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

<form id="creationForm" action="{{ route('admin.formations.update', $formation) }}" method="POST" enctype="multipart/form-data" class="interactive-dashboard-form">
    @csrf
    @method('PUT')
    <input type="hidden" id="slug" name="slug" value="{{ $formation->slug }}">

    <div class="form-header">
        <div>
            <h1 class="text-white fs-2qx">Modifier la Formation</h1>
            <p class="text-white-50">Mettez à jour les informations de la formation.</p>
        </div>
    </div>

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
                                <input type="text" class="form-control" id="name" name="name" placeholder="Ex: Maîtriser Photoshop de A à Z" required value="{{ old('name', $formation->name) }}">
                            </div>
                            <div class="form-group">
                                <label for="category_id">Catégorie thématique</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="" disabled>Choisir une catégorie...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $formation->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                                <div class="image-upload-zone {{ $formation->image_url ? 'd-none' : '' }}">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Glissez-déposez une image ou cliquez</p>
                                </div>
                                <input type="file" id="image" name="image" class="d-none" accept="image/*">
                                <div class="image-preview-container {{ $formation->image_url ? '' : 'd-none' }}">
                                    <img id="image-preview" src="{{ $formation->image_url ? \App\Models\MediaUrl::fromPath($formation->image_url) : '#' }}" alt="Aperçu" />
                                    <button type="button" id="remove-image-btn" class="btn btn-danger btn-sm">&times;</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description Row -->
        <div class="col-12">
            <div class="form-card description-card">
                 <div class="form-card-header">
                    <i class="fas fa-paragraph"></i>
                    <h3>Description Complète</h3>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <input type="hidden" name="description" id="description-input" value="{{ old('description', $formation->description) }}">
                        <div id="quill-editor">{!! old('description', $formation->description) !!}</div>
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
                        <input type="text" class="form-control" id="vimeo_code" name="vimeo_code" placeholder="URL YouTube ou Code <iframe>" value="{{ old('vimeo_code', $formation->vimeo_code ?? '') }}">
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
                        <select class="form-select" id="module" name="modules[]" multiple="multiple" required>
                            @php
                                $selectedModules = old('modules', $formation->modules ?? []);
                                if (!is_array($selectedModules)) { $selectedModules = []; }
                                $legacyMap = [
                                    'design-graphique-cm' => 'design-graphique-community-manager',
                                    'community-manager' => 'community-management',
                                    'informatique' => 'gestion-informatique',
                                ];
                                $selectedModules = array_map(function ($m) use ($legacyMap) {
                                    return $legacyMap[$m] ?? $m;
                                }, $selectedModules);
                            @endphp
                            <option value="design-graphique" {{ in_array('design-graphique', $selectedModules) ? 'selected' : '' }}>Design Graphique</option>
                            <option value="design-graphique-community-manager" {{ in_array('design-graphique-community-manager', $selectedModules) ? 'selected' : '' }}>Design Graphique &amp; Community Management</option>
                            <option value="community-management" {{ in_array('community-management', $selectedModules) ? 'selected' : '' }}>Community Management</option>
                            <option value="gestion-informatique" {{ in_array('gestion-informatique', $selectedModules) ? 'selected' : '' }}>Gestion Informatique</option>
                            <option value="intelligence-artificielle" {{ in_array('intelligence-artificielle', $selectedModules) ? 'selected' : '' }}>Intelligence Artificielle</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="type">Type de formation</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="en_ligne" {{ (old('type', $formation->format) == 'online') ? 'selected' : '' }}>En ligne</option>
                            <option value="presentiel" {{ (old('type', $formation->format) == 'offline') ? 'selected' : '' }}>Présentiel</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="destinataire">Destinataires</label>
                        <select class="form-select" id="destinataire" name="destinataire" required>
                            <option value="etudiants-actifs" {{ (old('destinataire', $formation->student_restriction) == 'active_only') ? 'selected' : '' }}>Étudiants actifs</option>
                            <option value="etudiants-specifiques" {{ (old('destinataire', $formation->student_restriction) == 'all') ? 'selected' : '' }}>Étudiants spécifiques</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3 d-none" id="students-select-container">
                    <div class="col-12 form-group">
                        <label for="student_ids">Sélectionner les étudiants <span class="text-danger">*</span></label>
                        <select class="form-select" id="student_ids" name="student_ids[]" multiple="multiple">
                            @php
                                $assignedStudentIds = $formation->students->pluck('id')->toArray();
                            @endphp
                            @foreach($students as $student)
                                <option value="{{ $student->id }}"
                                    {{ in_array($student->id, old('student_ids', $assignedStudentIds)) ? 'selected' : '' }}>
                                    {{ $student->name }}
                                </option>
                            @endforeach
                        </select>
                        <div id="assigned-students-summary" class="mt-2 small text-muted"></div>
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
                            <input type="radio" name="status" id="status-draft" value="draft" class="d-none" {{ (old('status', $formation->status) == 'draft') ? 'checked' : '' }}>
                            <label for="status-draft" class="publication-status-option {{ (old('status', $formation->status) == 'draft') ? 'selected' : '' }}">
                                <i class="fas fa-edit"></i>
                                <span>Brouillon</span>
                                <small>Enregistrer sans publier</small>
                            </label>

                            <input type="radio" name="status" id="status-pending" value="pending" class="d-none" {{ (old('status', $formation->status) == 'inactive') ? 'checked' : '' }}>
                            <label for="status-pending" class="publication-status-option {{ (old('status', $formation->status) == 'inactive') ? 'selected' : '' }}">
                                <i class="fas fa-hourglass-half"></i>
                                <span>En attente</span>
                                <small>En attente de validation</small>
                            </label>

                            <input type="radio" name="status" id="status-published" value="published" class="d-none" {{ (old('status', $formation->status) == 'active') ? 'checked' : '' }}>
                            <label for="status-published" class="publication-status-option {{ (old('status', $formation->status) == 'active') ? 'selected' : '' }}">
                                <i class="fas fa-globe"></i>
                                <span>Publié</span>
                                <small>Visible par tous</small>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="is_featured">Formation à la UNE</label>
                            <select class="form-select" id="is_featured" name="is_featured">
                                <option value="0" {{ !$formation->is_featured ? 'selected' : '' }}>Non</option>
                                <option value="1" {{ $formation->is_featured ? 'selected' : '' }}>Oui</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="form-footer mt-4 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.formations.index') }}" class="btn btn-light"><i class="fas fa-arrow-left me-2"></i>Retour à la liste</a>
        <div>
            <button type="submit" name="action" value="draft" class="btn btn-secondary"><i class="fas fa-save me-2"></i>Enregistrer les modifications</button>
            <button type="submit" name="action" value="published" class="btn btn-success"><i class="fas fa-rocket me-2"></i>Mettre à jour et Publier</button>
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
    function loadStudentsByModule(modules, selectedIds = []) {
        if (!modules || (Array.isArray(modules) && modules.length === 0)) {
            $('#student_ids').empty().trigger('change');
            return;
        }

        // Afficher un loader
        $('#student_ids').empty().append('<option value="">Chargement...</option>').trigger('change');

        // Requête AJAX pour récupérer les étudiants du module
        $.ajax({
            url: '{{ route("admin.api.students-by-module") }}',
            method: 'GET',
            data: { modules: modules, formation_id: {{ $formation->id }} },
            success: function(response) {
                if (response.success) {
                    // Vider le select
                    $('#student_ids').empty();

                    // Trier : inscrits en premier, puis non inscrits, alphabétiquement
                    response.students.sort(function(a, b) {
                        if (a.is_assigned && !b.is_assigned) return -1;
                        if (!a.is_assigned && b.is_assigned) return 1;
                        return a.name.localeCompare(b.name);
                    });

                    // Ajouter les étudiants du module
                    let assignedCount = 0;
                    let availableCount = 0;
                    if (response.students.length > 0) {
                        response.students.forEach(function(student) {
                            const optionText = student.name + (student.is_assigned ? ' (déjà inscrit)' : '');
                            const option = $('<option></option>')
                                .attr('value', student.id)
                                .text(optionText)
                                .attr('data-is-assigned', student.is_assigned ? '1' : '0');

                            // Sélectionner si déjà inscrit ou dans les IDs pré-sélectionnés
                            if (student.is_assigned || selectedIds.includes(student.id)) {
                                option.attr('selected', 'selected');
                                assignedCount++;
                            } else {
                                availableCount++;
                            }

                            $('#student_ids').append(option);
                        });
                    } else {
                        $('#student_ids').append('<option value="">Aucun étudiant dans ce module</option>');
                    }

                    // Mettre à jour le résumé
                    const summary = $('#assigned-students-summary');
                    if (assignedCount > 0 || availableCount > 0) {
                        summary.html(
                            '<span class="text-info"><i class="fas fa-check-circle me-1"></i>' + assignedCount + ' étudiant(s) inscrit(s)</span>' +
                            ' &middot; ' +
                            '<span class="text-warning"><i class="fas fa-plus-circle me-1"></i>' + availableCount + ' étudiant(s) disponible(s)</span>'
                        );
                    } else {
                        summary.html('');
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
        const modules = $('#module').val();

        if (destinataire === 'etudiants-specifiques') {
            studentsContainer.removeClass('d-none').addClass('animate__animated animate__fadeIn');
            $('#student_ids').prop('required', true);
            // Charger les étudiants du module sélectionné
            loadStudentsByModule(modules);
        } else {
            studentsContainer.addClass('d-none').removeClass('animate__animated animate__fadeIn');
            $('#student_ids').prop('required', false);
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

    // Précharger les étudiants selon les modules déjà sélectionnés (édition)
    @php
        $initialSelectedStudentIds = old('student_ids', $formation->students->pluck('id')->toArray());
        if (!is_array($initialSelectedStudentIds)) { $initialSelectedStudentIds = []; }
    @endphp
    const initialSelectedStudentIds = @json($initialSelectedStudentIds);
    const initialSelectedModules = @json(old('modules', $formation->modules ?? []));
    if (Array.isArray(initialSelectedModules) && initialSelectedModules.length > 0 && $('#destinataire').val() === 'etudiants-specifiques') {
        loadStudentsByModule(initialSelectedModules, initialSelectedStudentIds);
    }

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
        const ytRegExp = /(?:[?&]v=|\/v\/|\/embed\/|\/shorts\/|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
        const ytMatch = input.match(ytRegExp);
        if (ytMatch) return { type: 'youtube', id: ytMatch[1] };

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
                <input type="hidden" name="chapters[${chapterCount}][id]" value="">
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

    // Charger les chapitres existants
    @if(isset($chapters) && $chapters->count() > 0)
        @foreach($chapters as $chapter)
            chapterCount++;
            const container{{ $loop->index }} = document.getElementById('chapters-container');
            const noChaptersMsg{{ $loop->index }} = document.getElementById('no-chapters-message');

            if (noChaptersMsg{{ $loop->index }}) {
                noChaptersMsg{{ $loop->index }}.remove();
            }

            const chapterHtml{{ $loop->index }} = `
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
                                   value="{{ addslashes($chapter->title) }}"
                                   placeholder="Ex: Introduction au Design Graphique"
                                   required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="chapter_order_${chapterCount}">Ordre <span class="text-danger">*</span></label>
                            <input type="number"
                                   class="form-control"
                                   id="chapter_order_${chapterCount}"
                                   name="chapters[${chapterCount}][order]"
                                   value="{{ $chapter->order }}"
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
                                  placeholder="Décrivez brièvement ce qui sera couvert dans ce chapitre...">{{ $chapter->description }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="chapter_duration_${chapterCount}">Durée (minutes)</label>
                            <input type="number"
                                   class="form-control"
                                   id="chapter_duration_${chapterCount}"
                                   name="chapters[${chapterCount}][duration]"
                                   value="{{ $chapter->duration }}"
                                   placeholder="Ex: 45"
                                   min="1">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="chapter_video_url_${chapterCount}">Lien vidéo (optionnel)</label>
                            <input type="text"
                                   class="form-control"
                                   id="chapter_video_url_${chapterCount}"
                                   name="chapters[${chapterCount}][video_url]"
                                   value="{{ $chapter->video_url }}"
                                   placeholder="URL YouTube ou Vimeo">
                        </div>
                    </div>
                    <input type="hidden" name="chapters[${chapterCount}][id]" value="{{ $chapter->id }}">
                </div>
            `;

            container{{ $loop->index }}.insertAdjacentHTML('beforeend', chapterHtml{{ $loop->index }});
        @endforeach
    @endif
});
</script>
@endpush
