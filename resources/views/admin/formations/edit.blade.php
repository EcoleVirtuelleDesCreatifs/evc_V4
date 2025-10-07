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
                                    <img id="image-preview" src="{{ $formation->image_url ? asset('storage/' . $formation->image_url) : '#' }}" alt="Aperçu" />
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

        <!-- Vimeo Integration Row -->
        <div class="col-12">
            <div class="form-card vimeo-card">
                <div class="form-card-header">
                    <i class="fab fa-vimeo-v"></i>
                    <h3>Configuration video Vimeo</h3>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label for="vimeo_code">Code d'intégration vimeo</label>
                        <textarea class="form-control" id="vimeo_code" name="vimeo_code" rows="3" placeholder="<iframe src=&quot;https://player.vimeo.com/video/...&quot;></iframe>">{{ old('vimeo_code', $formation->vimeo_code ?? '') }}</textarea>
                        <div class="invalid-feedback">
                            Le code d'intégration Vimeo n'est pas valide.
                        </div>
                    </div>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i> Le code doit contenir <strong>"vimeo.com/event/"</strong> pour être valide.
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
                            <option value="" disabled {{ (old('module', $formation->modules[0] ?? '') == '') ? 'selected' : '' }}>Choisir un module...</option>
                            <option value="design-graphique" {{ (old('module', $formation->modules[0] ?? '') == 'design-graphique') ? 'selected' : '' }}>Design Graphique</option>
                            <option value="community-management" {{ (old('module', $formation->modules[0] ?? '') == 'community-management') ? 'selected' : '' }}>Community Management</option>
                            <option value="gestion-informatique" {{ (old('module', $formation->modules[0] ?? '') == 'gestion-informatique') ? 'selected' : '' }}>Gestion Informatique</option>
                            <option value="intelligence-artificielle" {{ (old('module', $formation->modules[0] ?? '') == 'intelligence-artificielle') ? 'selected' : '' }}>Intelligence Artificielle</option>
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
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" 
                                    {{ in_array($student->id, old('student_ids', $formation->target_student_types ?? [])) ? 'selected' : '' }}>
                                    {{ $student->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Maintenez Ctrl (Windows) ou Cmd (Mac) pour sélectionner plusieurs étudiants</small>
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
    function loadStudentsByModule(module, selectedIds = []) {
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
                            const option = $('<option></option>')
                                .attr('value', student.id)
                                .text(student.name);
                            
                            // Sélectionner si dans les IDs pré-sélectionnés
                            if (selectedIds.includes(student.id)) {
                                option.attr('selected', 'selected');
                            }
                            
                            $('#student_ids').append(option);
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
});
</script>
@endpush
