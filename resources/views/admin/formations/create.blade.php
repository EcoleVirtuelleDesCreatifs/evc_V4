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
                                <input type="text" class="form-control" id="name" name="name" placeholder="Ex: Maîtriser Photoshop de A à Z" required>
                            </div>
                            <div class="form-group">
                                <label for="category_id">Catégorie thématique</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="" disabled selected>Choisir une catégorie...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
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

        <!-- Description Row -->
        <div class="col-12">
            <div class="form-card description-card">
                 <div class="form-card-header">
                    <i class="fas fa-paragraph"></i>
                    <h3>Description</h3>
                </div>
                <div class="form-card-body">
                    <div class="form-group" style="min-height: 250px;">
                        <input type="hidden" name="description" id="description-input">
                        <div id="quill-editor"></div>
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
                        <textarea class="form-control" id="vimeo_code" name="vimeo_code" rows="3" placeholder="<iframe src=&quot;https://player.vimeo.com/video/...&quot;></iframe>"></textarea>
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
                            <option value="" disabled selected>Choisir un module...</option>
                            <option value="design-graphique">Design Graphique</option>
                            <option value="community-manager">Community Manager</option>
                            <option value="informatique">Informatique</option>
                            <option value="intelligence-artificielle">Intelligence Artificielle</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="type">Type de formation</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="en_ligne">En ligne</option>
                            <option value="presentiel">Présentiel</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="destinataire">Destinataires</label>
                        <select class="form-select" id="destinataire" name="destinataire" required>
                            <option value="etudiants-actifs">Étudiants actifs</option>
                            <option value="etudiants-specifiques">Étudiants spécifiques</option>
                        </select>
                    </div>
                </div>
                {{-- <div class="row mt-3 d-none" id="students-select-container">
                    <div class="col-12 form-group">
                        <label for="student_ids">Sélectionner les étudiants</label>
                        <select class="form-select" id="student_ids" name="student_ids[]" multiple="multiple">
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div> --}}
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
                            <input type="radio" name="status" id="status-draft" value="draft" class="d-none" checked>
                            <label for="status-draft" class="publication-status-option selected">
                                <i class="fas fa-edit"></i>
                                <span>Brouillon</span>
                                <small>Enregistrer sans publier</small>
                            </label>

                            <input type="radio" name="status" id="status-pending" value="pending" class="d-none">
                            <label for="status-pending" class="publication-status-option">
                                <i class="fas fa-hourglass-half"></i>
                                <span>En attente</span>
                                <small>En attente de validation</small>
                            </label>

                            <input type="radio" name="status" id="status-published" value="published" class="d-none">
                            <label for="status-published" class="publication-status-option">
                                <i class="fas fa-globe"></i>
                                <span>Publié</span>
                                <small>Visible par tous</small>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="published_at">Date de publication</label>
                            <input type="datetime-local" class="form-control" id="published_at" name="published_at">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="is_featured">Formation à la UNE</label>
                            <select class="form-select" id="is_featured" name="is_featured">
                                <option value="0">Non</option>
                                <option value="1">Oui</option>
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
@endpush
