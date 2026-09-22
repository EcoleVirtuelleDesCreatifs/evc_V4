@extends('layouts.admin')

@section('title', 'Créer une Formation')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
<style>
    .fc-sidebar { position: sticky; top: 90px; }
    @media (max-width: 991px) { .fc-sidebar { position: static; } }

    .fc-step {
        display: inline-flex; align-items: center; justify-content: center;
        width: 22px; height: 22px; border-radius: 50%;
        background: rgba(139,92,246,0.25); color: #c4b5fd;
        font-size: 0.72rem; font-weight: 800; margin-right: 0.5rem;
    }

    .publication-status-options { display: flex; flex-direction: column; gap: 0.6rem; }
    .publication-status-option {
        display: flex; align-items: center; gap: 0.75rem; cursor: pointer;
        border: 1px solid rgba(255,255,255,0.12); border-radius: 12px;
        padding: 0.7rem 0.9rem; transition: all 0.15s ease;
        background: rgba(255,255,255,0.03);
    }
    .publication-status-option:hover { border-color: rgba(139,92,246,0.5); }
    .publication-status-option.selected { border-color: #8b5cf6; background: rgba(139,92,246,0.12); }
    .publication-status-option i { font-size: 1.1rem; width: 22px; text-align: center; }
    .publication-status-option span { color: #fff; font-weight: 700; font-size: 0.88rem; display: block; }
    .publication-status-option small { color: rgba(255,255,255,0.5); font-size: 0.72rem; }

    #submit-button {
        background: linear-gradient(135deg, #8b5cf6, #6366f1); border: none;
        color: #fff; font-weight: 800; border-radius: 12px; padding: 0.8rem 1.5rem;
        width: 100%; font-size: 0.95rem; transition: filter 0.15s ease;
    }
    #submit-button:hover { filter: brightness(1.12); color: #fff; }

    .chapter-item { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1) !important; border-radius: 14px; }
    .chapter-num {
        display: inline-flex; align-items: center; justify-content: center;
        width: 26px; height: 26px; border-radius: 8px; font-weight: 800; font-size: 0.8rem;
        background: rgba(59,130,246,0.2); color: #93c5fd; margin-right: 0.5rem;
    }

    .select2-container--default .select2-selection--multiple,
    .select2-container--default .select2-selection--single {
        background: rgba(255,255,255,0.06) !important;
        border: 1px solid rgba(255,255,255,0.15) !important;
        border-radius: 10px !important; min-height: 42px; color: #fff;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: rgba(139,92,246,0.25) !important; border-color: rgba(139,92,246,0.5) !important; color: #e9d5ff !important;
    }
    .select2-dropdown { background: #0f172a !important; border-color: rgba(255,255,255,0.15) !important; }
    .select2-results__option { color: #e2e8f0 !important; }
    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable { background: rgba(139,92,246,0.35) !important; }
    .select2-search__field { color: #fff !important; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { color: #fff !important; line-height: 40px !important; }

    .pdf-item {
        display: flex; align-items: center; gap: 0.7rem;
        background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px; padding: 0.6rem 0.85rem; margin-bottom: 0.5rem;
        color: #e2e8f0; font-size: 0.85rem;
    }
    .pdf-item i { color: #f87171; }
    .pdf-item .sz { color: rgba(255,255,255,0.45); font-size: 0.75rem; margin-left: auto; }
</style>
@endpush

@section('content')

<form id="creationForm" action="{{ route('admin.formations.store') }}" method="POST" enctype="multipart/form-data" class="interactive-dashboard-form">
    @csrf
    <input type="hidden" id="slug" name="slug">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="h4 mb-0 text-white fw-bold"><i class="fas fa-plus-circle me-2" style="color:#a78bfa;"></i>Nouvelle Formation</h1>
        <a href="{{ route('admin.formations.index') }}" class="btn btn-sm btn-outline-light" style="border-radius:999px;">
            <i class="fas fa-arrow-left me-1"></i>Retour à la liste
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong><i class="fas fa-exclamation-triangle me-1"></i>Veuillez corriger les erreurs :</strong>
            <ul class="mb-0 mt-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="row g-4">

        {{-- ═══════════ Colonne principale ═══════════ --}}
        <div class="col-lg-8">

            {{-- 1. Informations --}}
            <div class="form-card mb-4">
                <div class="form-card-header">
                    <i class="fas fa-info-circle"></i>
                    <h3><span class="fc-step">1</span>Informations principales</h3>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label for="name">Titre de la formation <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name"
                               value="{{ old('name') }}" placeholder="Ex: Maîtriser Photoshop de A à Z" required>
                    </div>
                    <div class="form-group mb-0">
                        <label for="category_id">Catégorie thématique <span class="text-danger">*</span></label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="" disabled selected>Choisir une catégorie…</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- 2. Description --}}
            <div class="form-card description-card mb-4">
                <div class="form-card-header">
                    <i class="fas fa-paragraph"></i>
                    <h3><span class="fc-step">2</span>Description <span class="text-danger ms-1">*</span></h3>
                </div>
                <div class="form-card-body">
                    <div class="form-group mb-0" style="min-height: 240px;">
                        <input type="hidden" name="description" id="description-input" value="">
                        <div id="quill-editor"></div>
                    </div>
                </div>
            </div>

            {{-- 3. Contenus --}}
            <div class="form-card mb-4">
                <div class="form-card-header">
                    <i class="fas fa-photo-video"></i>
                    <h3><span class="fc-step">3</span>Contenus &amp; médias</h3>
                </div>
                <div class="form-card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="d-block mb-2 fw-bold" style="color:rgba(255,255,255,0.85); font-size:0.85rem;">
                                <i class="fas fa-image me-1" style="color:#60a5fa;"></i>Image de couverture
                            </label>
                            <div id="image-upload-container">
                                <div class="image-upload-zone">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p class="mb-0">Glissez une image ou cliquez</p>
                                </div>
                                <input type="file" id="image" name="image" class="d-none" accept="image/*">
                                <div class="image-preview-container d-none">
                                    <img id="image-preview" src="#" alt="Aperçu" />
                                    <button type="button" id="remove-image-btn" class="btn btn-danger btn-sm">&times;</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="pdf_files" class="d-block mb-2 fw-bold" style="color:rgba(255,255,255,0.85); font-size:0.85rem;">
                                <i class="fas fa-file-pdf me-1" style="color:#f87171;"></i>Documents PDF
                            </label>
                            <input type="file" class="form-control" id="pdf_files" name="pdf_files[]" accept=".pdf" multiple>
                            <small class="form-text text-muted d-block mt-1" style="color:#94a3b8 !important;">
                                Supports de cours, exercices… 10 Mo max / fichier
                            </small>
                            <div id="pdf-preview-list" class="mt-2"></div>
                        </div>
                    </div>

                    <hr style="border-color: rgba(255,255,255,0.08);">

                    <div class="form-group mb-0">
                        <label for="vimeo_code"><i class="fab fa-youtube me-1" style="color:#f87171;"></i>Vidéo de présentation (YouTube / iframe)</label>
                        <input type="text" class="form-control" id="vimeo_code" name="vimeo_code"
                               value="{{ old('vimeo_code') }}" placeholder="URL YouTube ou code d'intégration <iframe>">
                        <div class="invalid-feedback" id="vimeo-validation-message">Le lien ou le code n'est pas valide.</div>
                    </div>
                    <div id="video-preview-container" class="mt-3 d-none">
                        <div class="ratio ratio-16x9 rounded overflow-hidden" style="max-width: 420px; border: 1px solid rgba(255,255,255,0.12);">
                            <iframe id="video-preview-iframe" src="" title="Aperçu vidéo" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Chapitres --}}
            <div class="form-card mb-4">
                <div class="form-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-book-open"></i>
                        <h3><span class="fc-step">4</span>Chapitres de la formation</h3>
                    </div>
                    <button type="button" class="btn btn-sm btn-success" onclick="addChapter()" style="border-radius:999px;">
                        <i class="fas fa-plus me-1"></i>Ajouter
                    </button>
                </div>
                <div class="form-card-body">
                    <div id="chapters-container">
                        <div class="text-center py-4" id="no-chapters-message" style="color: #9ca3af;">
                            <i class="fas fa-book-open fa-2x mb-2" style="opacity: 0.25;"></i>
                            <p class="mb-0" style="color: #6b7280; font-size: 0.9rem;">Aucun chapitre — optionnel, ajoutez-en pour structurer la formation.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════ Sidebar ═══════════ --}}
        <div class="col-lg-4">
            <div class="fc-sidebar">

                {{-- Ciblage --}}
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <i class="fas fa-bullseye"></i>
                        <h3>Ciblage &amp; diffusion</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="form-group">
                            <label for="module">Module(s) concerné(s) <span class="text-danger">*</span></label>
                            <select class="form-select" id="module" name="modules[]" multiple="multiple" required>
                                @php
                                    $selectedModules = old('modules', []);
                                    if (!is_array($selectedModules)) { $selectedModules = []; }
                                @endphp
                                <option value="design-graphique" {{ in_array('design-graphique', $selectedModules) ? 'selected' : '' }}>🎨 Design Graphique</option>
                                <option value="design-graphique-community-manager" {{ in_array('design-graphique-community-manager', $selectedModules) ? 'selected' : '' }}>🎨📱 Design &amp; Community</option>
                                <option value="community-management" {{ in_array('community-management', $selectedModules) ? 'selected' : '' }}>📱 Community Management</option>
                                <option value="gestion-informatique" {{ in_array('gestion-informatique', $selectedModules) ? 'selected' : '' }}>💻 Gestion Informatique</option>
                                <option value="intelligence-artificielle" {{ in_array('intelligence-artificielle', $selectedModules) ? 'selected' : '' }}>🤖 Intelligence Artificielle</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="type">Type de formation <span class="text-danger">*</span></label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="en_ligne" {{ old('type', 'en_ligne') == 'en_ligne' ? 'selected' : '' }}>🎥 En ligne</option>
                                <option value="presentiel" {{ old('type') == 'presentiel' ? 'selected' : '' }}>📍 Présentiel</option>
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label for="destinataire">Destinataires <span class="text-danger">*</span></label>
                            <select class="form-select" id="destinataire" name="destinataire" required>
                                <option value="etudiants-actifs" {{ old('destinataire', 'etudiants-actifs') == 'etudiants-actifs' ? 'selected' : '' }}>Étudiants actifs</option>
                                <option value="etudiants-specifiques" {{ old('destinataire') == 'etudiants-specifiques' ? 'selected' : '' }}>Étudiants spécifiques</option>
                            </select>
                        </div>

                        <div class="mt-3 d-none" id="students-select-container">
                            <label for="student_ids">Étudiants <span class="text-danger">*</span></label>
                            <select class="form-select" id="student_ids" name="student_ids[]" multiple="multiple">
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ in_array($student->id, old('student_ids', [])) ? 'selected' : '' }}>{{ $student->name }}@if($student->email) ({{ $student->email }})@endif</option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1" style="color:#94a3b8 !important;">
                                <i class="fas fa-info-circle me-1"></i>La liste se recharge selon le(s) module(s) choisi(s) — comptes actifs uniquement.
                            </small>
                        </div>
                    </div>
                </div>

                {{-- Publication --}}
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <i class="fas fa-rocket"></i>
                        <h3>Publication</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="form-group">
                            <div class="publication-status-options">
                                <input type="radio" name="action" id="status-draft" value="draft" class="d-none" {{ old('action', 'draft') == 'draft' ? 'checked' : '' }}>
                                <label for="status-draft" class="publication-status-option {{ old('action', 'draft') == 'draft' ? 'selected' : '' }}">
                                    <i class="fas fa-edit" style="color:#94a3b8;"></i>
                                    <div><span>Brouillon</span><small>Enregistrer sans publier</small></div>
                                </label>

                                <input type="radio" name="action" id="status-pending" value="pending" class="d-none" {{ old('action') == 'pending' ? 'checked' : '' }}>
                                <label for="status-pending" class="publication-status-option {{ old('action') == 'pending' ? 'selected' : '' }}">
                                    <i class="fas fa-hourglass-half" style="color:#fbbf24;"></i>
                                    <div><span>En attente</span><small>En attente de validation</small></div>
                                </label>

                                <input type="radio" name="action" id="status-published" value="published" class="d-none" {{ old('action') == 'published' ? 'checked' : '' }}>
                                <label for="status-published" class="publication-status-option {{ old('action') == 'published' ? 'selected' : '' }}">
                                    <i class="fas fa-globe" style="color:#4ade80;"></i>
                                    <div><span>Publié</span><small>Visible par les étudiants</small></div>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="published_at">Date de publication</label>
                            <input type="datetime-local" class="form-control" id="published_at" name="published_at" value="{{ old('published_at') }}">
                        </div>
                        <div class="form-group mb-0">
                            <label for="is_featured">Formation à la UNE</label>
                            <select class="form-select" id="is_featured" name="is_featured">
                                <option value="0" {{ old('is_featured', '0') == '0' ? 'selected' : '' }}>Non</option>
                                <option value="1" {{ old('is_featured') == '1' ? 'selected' : '' }}>Oui</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="form-card">
                    <div class="form-card-body">
                        <button type="submit" id="submit-button">
                            <i class="fas fa-save me-2" id="submitIcon"></i><span id="submitLabel">Enregistrer le brouillon</span>
                        </button>
                        <div class="text-center mt-2">
                            <a href="{{ route('admin.formations.index') }}" class="text-decoration-none" style="color:rgba(255,255,255,0.5); font-size:0.8rem;">
                                <i class="fas fa-times me-1"></i>Annuler
                            </a>
                        </div>
                    </div>
                </div>

            </div>
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
// Libellé du bouton submit selon le statut choisi
(function () {
    const labels = {
        draft: { text: 'Enregistrer le brouillon', icon: 'fa-save' },
        pending: { text: 'Marquer en attente', icon: 'fa-hourglass-half' },
        published: { text: 'Publier la formation', icon: 'fa-rocket' },
    };
    function syncSubmit() {
        const checked = document.querySelector('input[name="action"]:checked');
        const cfg = labels[checked ? checked.value : 'draft'] || labels.draft;
        document.getElementById('submitLabel').textContent = cfg.text;
        document.getElementById('submitIcon').className = 'fas ' + cfg.icon + ' me-2';
    }
    document.querySelectorAll('.publication-status-option').forEach(l => l.addEventListener('click', syncSubmit));
    syncSubmit();

    // Validation avant envoi : description non vide + lien vidéo valide
    document.getElementById('creationForm').addEventListener('submit', function (e) {
        const vimeo = document.getElementById('vimeo_code');
        if (vimeo.value && !/youtube\.com|youtu\.be|vimeo\.com|<iframe/i.test(vimeo.value)) {
            e.preventDefault();
            vimeo.classList.add('is-invalid');
            vimeo.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        const qlEditor = document.querySelector('#quill-editor .ql-editor');
        const text = qlEditor ? qlEditor.innerText.trim() : '';
        if (text.length === 0) {
            e.preventDefault();
            alert('Veuillez remplir la description de la formation.');
            return;
        }
        document.getElementById('description-input').value = qlEditor.innerHTML;
    });
})();

// Aperçu des fichiers PDF
document.getElementById('pdf_files').addEventListener('change', function(e) {
    const files = e.target.files;
    const previewList = document.getElementById('pdf-preview-list');
    previewList.innerHTML = '';

    Array.from(files).forEach((file) => {
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        const item = document.createElement('div');
        item.className = 'pdf-item';
        item.innerHTML = `<i class="fas fa-file-pdf"></i><span class="text-truncate">${file.name}</span><span class="sz">${fileSize} Mo</span>`;
        previewList.appendChild(item);
    });

    const totalSize = Array.from(files).reduce((sum, f) => sum + f.size, 0) / 1024 / 1024;
    if (totalSize > 50) {
        const warning = document.createElement('div');
        warning.className = 'alert alert-warning mt-2 mb-0';
        warning.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>La taille totale dépasse 50 Mo.';
        previewList.appendChild(warning);
    }
});

$(document).ready(function() {
    // Select2 pour le picker d'étudiants
    $('#student_ids').select2({
        placeholder: 'Rechercher et sélectionner des étudiants…',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() { return 'Aucun étudiant trouvé'; },
            searching: function() { return 'Recherche en cours…'; }
        }
    });

    function loadStudentsByModule(modules) {
        if (!modules || (Array.isArray(modules) && modules.length === 0)) {
            $('#student_ids').empty().trigger('change');
            return;
        }

        $('#student_ids').empty().append('<option value="">Chargement…</option>').trigger('change');

        $.ajax({
            url: '{{ route("admin.api.students-by-module") }}',
            method: 'GET',
            data: { modules: modules },
            success: function(response) {
                if (response.success) {
                    $('#student_ids').empty();

                    const studentsWithout = Array.isArray(response.students_without_projects) ? response.students_without_projects : [];
                    const studentsWith = Array.isArray(response.students_with_projects) ? response.students_with_projects : [];
                    const allStudents = Array.isArray(response.students) ? response.students : [];

                    const renderOptGroup = function(label, items) {
                        if (!items || items.length === 0) return;
                        const $group = $('<optgroup></optgroup>').attr('label', label);
                        items.forEach(function(student) {
                            $group.append($('<option></option>').attr('value', student.id).text(student.name));
                        });
                        $('#student_ids').append($group);
                    };

                    if (studentsWithout.length > 0 || studentsWith.length > 0) {
                        renderOptGroup('Nouveaux inscrits (0 projet)', studentsWithout);
                        renderOptGroup('Déjà avec projets', studentsWith);
                    } else if (allStudents.length > 0) {
                        allStudents.forEach(function(student) {
                            $('#student_ids').append($('<option></option>').attr('value', student.id).text(student.name));
                        });
                    } else {
                        $('#student_ids').append('<option value="">Aucun étudiant actif dans ce module</option>');
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

    function toggleStudentsSelect() {
        const destinataire = $('#destinataire').val();
        const studentsContainer = $('#students-select-container');

        if (destinataire === 'etudiants-specifiques') {
            studentsContainer.removeClass('d-none');
            $('#student_ids').prop('required', true);
            loadStudentsByModule($('#module').val());
        } else {
            studentsContainer.addClass('d-none');
            $('#student_ids').prop('required', false);
            $('#student_ids').val(null).trigger('change');
        }
    }

    $('#module').on('change', function() {
        if ($('#destinataire').val() === 'etudiants-specifiques') {
            loadStudentsByModule($(this).val());
        }
    });

    $('#destinataire').on('change', toggleStudentsSelect);
    toggleStudentsSelect();

    // Aperçu vidéo YouTube / Vimeo / iframe
    const vimeoInput = document.getElementById('vimeo_code');
    const previewContainer = document.getElementById('video-preview-container');
    const previewIframe = document.getElementById('video-preview-iframe');

    function extractVideoSource(input) {
        if (!input) return null;
        if (input.includes('<iframe')) {
            try {
                const div = document.createElement('div');
                div.innerHTML = input;
                const iframe = div.querySelector('iframe');
                if (iframe && iframe.src) return { type: 'iframe', src: iframe.src };
            } catch (e) {}
        }
        const yt = input.match(/(?:[?&]v=|\/v\/|\/embed\/|\/shorts\/|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
        if (yt) return { type: 'youtube', id: yt[1] };
        const vm = input.match(/(?:vimeo\.com\/|player\.vimeo\.com\/video\/)([0-9]+)/);
        if (vm) return { type: 'vimeo', id: vm[1] };
        return null;
    }

    function updateVideoPreview() {
        const source = extractVideoSource(vimeoInput.value);
        if (source) {
            let src = '';
            if (source.type === 'iframe') {
                src = source.src;
                if (src.includes('youtube.com')) {
                    src = src.replace('youtube.com', 'youtube-nocookie.com')
                             .replace(/[?&](si|feature)=[^&]*/g, '')
                             .replace(/\?&/, '?').replace(/&&/, '&');
                }
            } else if (source.type === 'youtube') {
                src = 'https://www.youtube-nocookie.com/embed/' + source.id + '?rel=0&modestbranding=1';
            } else if (source.type === 'vimeo') {
                src = 'https://player.vimeo.com/video/' + source.id;
            }
            previewIframe.src = src;
            previewContainer.classList.remove('d-none');
        } else {
            previewContainer.classList.add('d-none');
            previewIframe.src = '';
        }
    }

    if (vimeoInput) {
        vimeoInput.addEventListener('input', updateVideoPreview);
        if (vimeoInput.value) updateVideoPreview();
    }

    // Chapitres dynamiques
    let chapterCount = 0;

    window.addChapter = function() {
        chapterCount++;
        const container = document.getElementById('chapters-container');
        const noChaptersMsg = document.getElementById('no-chapters-message');
        if (noChaptersMsg) noChaptersMsg.remove();

        const html = `
            <div class="chapter-item p-3 mb-3" id="chapter-${chapterCount}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 text-white"><span class="chapter-num">${chapterCount}</span>Chapitre ${chapterCount}</h6>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeChapter(${chapterCount})" style="border-radius:999px;">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-8 form-group">
                        <label>Titre du chapitre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="chapters[${chapterCount}][title]"
                               placeholder="Ex: Introduction au Design Graphique" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Ordre <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="chapters[${chapterCount}][order]" value="${chapterCount}" min="1" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="chapters[${chapterCount}][description]" rows="2"
                              placeholder="Contenu couvert dans ce chapitre…"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Durée (minutes)</label>
                        <input type="number" class="form-control" name="chapters[${chapterCount}][duration]" placeholder="Ex: 45" min="1">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Lien vidéo (optionnel)</label>
                        <input type="text" class="form-control" name="chapters[${chapterCount}][video_url]" placeholder="URL YouTube ou Vimeo">
                    </div>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', html);
    };

    window.removeChapter = function(id) {
        const chapter = document.getElementById('chapter-' + id);
        if (chapter) {
            chapter.remove();
            const container = document.getElementById('chapters-container');
            if (container.children.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-4" id="no-chapters-message" style="color: #9ca3af;">
                        <i class="fas fa-book-open fa-2x mb-2" style="opacity: 0.25;"></i>
                        <p class="mb-0" style="color: #6b7280; font-size: 0.9rem;">Aucun chapitre — optionnel, ajoutez-en pour structurer la formation.</p>
                    </div>`;
            }
        }
    };

    // Restaurer la description Quill en cas d'erreur de validation
    @if(old('description'))
        setTimeout(function() {
            if (window.quill || document.querySelector('#quill-editor .ql-editor')) {
                const oldDescription = {!! json_encode(old('description')) !!};
                const editor = document.querySelector('#quill-editor .ql-editor');
                if (editor) editor.innerHTML = oldDescription;
                document.getElementById('description-input').value = oldDescription;
            }
        }, 400);
    @endif

    // Restaurer les chapitres en cas d'erreur
    @if(old('chapters'))
        const oldChapters = {!! json_encode(old('chapters')) !!};
        if (oldChapters && Object.keys(oldChapters).length > 0) {
            Object.keys(oldChapters).forEach(function(key) {
                const chapter = oldChapters[key];
                chapterCount++;
                const container = document.getElementById('chapters-container');
                const msg = document.getElementById('no-chapters-message');
                if (msg) msg.remove();
                const esc = s => String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
                container.insertAdjacentHTML('beforeend', `
                    <div class="chapter-item p-3 mb-3" id="chapter-${chapterCount}">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 text-white"><span class="chapter-num">${chapterCount}</span>Chapitre ${chapterCount}</h6>
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeChapter(${chapterCount})" style="border-radius:999px;"><i class="fas fa-trash"></i></button>
                        </div>
                        <div class="row">
                            <div class="col-md-8 form-group"><label>Titre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="chapters[${chapterCount}][title]" value="${esc(chapter.title)}" required></div>
                            <div class="col-md-4 form-group"><label>Ordre <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="chapters[${chapterCount}][order]" value="${esc(chapter.order || chapterCount)}" min="1" required></div>
                        </div>
                        <div class="form-group"><label>Description</label>
                            <textarea class="form-control" name="chapters[${chapterCount}][description]" rows="2">${esc(chapter.description)}</textarea></div>
                        <div class="row">
                            <div class="col-md-6 form-group"><label>Durée (minutes)</label>
                                <input type="number" class="form-control" name="chapters[${chapterCount}][duration]" value="${esc(chapter.duration)}" min="1"></div>
                            <div class="col-md-6 form-group"><label>Lien vidéo</label>
                                <input type="text" class="form-control" name="chapters[${chapterCount}][video_url]" value="${esc(chapter.video_url)}"></div>
                        </div>
                    </div>`);
            });
        }
    @endif

    @if(old('destinataire') == 'etudiants-specifiques')
        document.getElementById('students-select-container').classList.remove('d-none');
        const oldModules = @json(old('modules', []));
        if (Array.isArray(oldModules) && oldModules.length > 0) {
            loadStudentsByModule(oldModules);
        }
    @endif
});
</script>
@endpush
