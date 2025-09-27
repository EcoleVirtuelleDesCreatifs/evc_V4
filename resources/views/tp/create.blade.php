@extends('layouts.ki-admin')

@section('title', 'Ajouter un Projet avec Images')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-header bg-light">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-images fa-3x opacity-75"></i>
                        </div>
                        <div>
                            <h2 class="mb-1">Nouveau Projet Design</h2>
                            <p class="mb-0 opacity-75">Ajoutez plusieurs images pour votre projet de design graphique</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="projectForm" method="POST" action="{{ route('design-graphique.tp.ajouter') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="type" value="digital">

        <div class="row">
            <!-- Formulaire Principal -->
            <div class="col-lg-8">
                <!-- Informations du Projet -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-info-circle text-primary me-2"></i>Informations du Projet</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label"><strong>Titre du Projet</strong><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" required maxlength="255" placeholder="Ex: Logo pour entreprise tech">
                                <div class="form-text">Maximum 255 caractères</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label"><strong>Catégorie</strong><span class="text-danger">*</span></label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Choisir une catégorie</option>
                                    <option value="photoshop">Photoshop</option>
                                    <option value="illustrator">Illustrator</option>
                                    <option value="indesign">InDesign</option>
                                    <option value="web-design">Web Design</option>
                                    <option value="ui-ux">UI/UX Design</option>
                                    <option value="branding">Branding</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label"><strong>Description</strong><span class="text-muted">(optionnel)</span></label>
                            <textarea class="form-control" id="description" name="description" rows="4" maxlength="2000" placeholder="Décrivez votre projet en détail..."></textarea>
                            <div class="form-text">
                                <span id="charCount">0</span>/2000 caractères
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="link" class="form-label"> <strong>Lien du Projet</strong>(optionnel)</label>
                            <input type="url" class="form-control" id="link" name="link" placeholder="https://example.com">
                            <div class="form-text">URL vers votre projet en ligne (Behance, Dribbble, etc.)</div>
                        </div>

                        <div class="mb-3">
                            <label for="tags" class="form-label"><strong>Tags</strong>(optionnel)</label>
                            <input type="text" class="form-control" id="tags" name="tags" maxlength="500" placeholder="logo, moderne, tech, bleu">
                            <div class="form-text">Mots-clés séparés par des virgules</div>
                        </div>
                    </div>
                </div>

                <!-- Section Images -->
                <div class="card mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-images text-success me-2"></i>Images du Projet <span class="text-danger">*</span></h5>
                        <div>
                            <span class="badge bg-info" id="imageCount">0 image(s)</span>
                            <button type="button" class="btn btn-sm btn-success ms-2" id="addImageBtn">
                                <i class="fas fa-plus me-1"></i>Ajouter une image
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Obligatoire :</strong> Ajoutez au moins 1 image (maximum 15). Formats acceptés : JPG, PNG, GIF, WEBP, SVG, PSD, AI. Taille max : 20MB par image.
                        </div>

                        <!-- Zone d'ajout d'images -->
                        <div id="imagesContainer" class="row g-3">
                            <!-- Les champs d'images seront ajoutés ici dynamiquement -->
                        </div>

                        <!-- Zone de drag & drop globale -->
                        <div id="globalDropZone" class="border-2 border-dashed border-primary rounded p-5 text-center mt-4" style="min-height: 200px; background: #f8f9ff;">
                            <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                <h5 class="text-primary mb-2">Glissez-déposez vos images ici</h5>
                                <p class="text-muted mb-3">ou cliquez sur "Ajouter une image" ci-dessus</p>
                                <div class="text-muted small">
                                    <i class="fas fa-check-circle text-success me-1"></i>JPG, PNG, GIF, WEBP, SVG, PSD, AI
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-weight-hanging text-info me-1"></i>Max 20MB par image
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Statistiques -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-chart-line text-primary me-2"></i>Vos Statistiques</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="text-center p-2 bg-light rounded">
                                    <div class="h4 mb-1 text-primary">
                                        @if(isset($stats))
                                            {{ $stats['tp_total'] + $stats['projets_total'] }}
                                        @else
                                            0
                                        @endif
                                    </div>
                                    <small class="text-muted">TP/Projets Total</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-2 bg-light rounded">
                                    <div class="h4 mb-1 text-success">
                                        @if(isset($stats))
                                            {{ $stats['tp_valides'] }}
                                        @else
                                            0
                                        @endif
                                    </div>
                                    <small class="text-muted">TP Validés</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-2 bg-light rounded">
                                    <div class="h4 mb-1 text-info">
                                        @if(isset($stats))
                                            {{ $stats['images_total'] }}
                                        @else
                                            0
                                        @endif
                                    </div>
                                    <small class="text-muted">Images Total</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-2 bg-light rounded">
                                    <div class="h4 mb-1 text-warning">
                                        @if(isset($stats))
                                            {{ $stats['taille_totale_mb'] }}
                                        @else
                                            0
                                        @endif
                                        <small>MB</small>
                                    </div>
                                    <small class="text-muted">Taille Totale</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Progression vers certification -->
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small text-muted">Progression Certification</span>
                                <span class="small fw-bold">
                                    @if(isset($stats))
                                        {{ min(100, $stats['progression_globale']) }}%
                                    @else
                                        0%
                                    @endif
                                </span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-gradient" role="progressbar" 
                                     style="width: @if(isset($stats)){{ min(100, $stats['progression_globale']) }}@else 0 @endif%"></div>
                            </div>
                            <div class="text-center mt-2">
                                <small class="text-muted">
                                    @if(isset($stats))
                                        {{ $stats['tp_total'] + $stats['projets_total'] }}/{{ $stats['objectif_certification'] }}
                                    @else
                                        0/20
                                    @endif
                                    TP/Projets requis
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aperçu -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-eye text-info me-2"></i>Aperçu du Projet</h6>
                    </div>
                    <div class="card-body">
                        <div id="projectPreview">
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-image fa-2x mb-2"></i>
                                <p class="mb-0">Remplissez les informations pour voir l'aperçu</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logiciels Utilisés -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-tools text-warning me-2"></i>Logiciels Utilisés <span class="text-danger">*</span></h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning alert-sm mb-3" id="softwareAlert" style="display: none;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <small><strong>Obligatoire :</strong> Sélectionnez au moins un logiciel utilisé pour ce projet.</small>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="software_used[]" value="photoshop" id="sw_photoshop">
                                    <label class="form-check-label" for="sw_photoshop">
                                        <i class="fab fa-adobe text-primary me-1"></i>Photoshop
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="software_used[]" value="illustrator" id="sw_illustrator">
                                    <label class="form-check-label" for="sw_illustrator">
                                        <i class="fab fa-adobe text-warning me-1"></i>Illustrator
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="software_used[]" value="indesign" id="sw_indesign">
                                    <label class="form-check-label" for="sw_indesign">
                                        <i class="fab fa-adobe text-danger me-1"></i>InDesign
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="software_used[]" value="figma" id="sw_figma">
                                    <label class="form-check-label" for="sw_figma">
                                        <i class="fas fa-vector-square text-success me-1"></i>Figma
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="software_used[]" value="sketch" id="sw_sketch">
                                    <label class="form-check-label" for="sw_sketch">
                                        <i class="fas fa-pencil-ruler text-info me-1"></i>Sketch
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="software_used[]" value="xd" id="sw_xd">
                                    <label class="form-check-label" for="sw_xd">
                                        <i class="fab fa-adobe text-purple me-1"></i>Adobe XD
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                                <i class="fas fa-save me-2"></i>Créer le Projet
                            </button>
                            <a href="{{ route('design-graphique.tp.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                        </div>

                        <div class="mt-3">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" role="progressbar" style="width: 0%" id="formProgress"></div>
                            </div>
                            <div class="text-center mt-2">
                                <small class="text-muted" id="progressText">Remplissez le formulaire</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Template pour les champs d'images -->
<template id="imageFieldTemplate">
    <div class="col-md-6 image-field" data-index="">
        <div class="card border-2 border-dashed">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="mb-0">Image <span class="image-number"></span></h6>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary btn-sm set-thumbnail" title="Définir comme image principale">
                            <i class="fas fa-star"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm remove-image" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                <div class="image-upload-zone border rounded p-3 text-center position-relative">
                    <input type="file" class="form-control image-input" name="images[]" accept="image/*,.psd,.ai" style="display: none;">
                    <div class="upload-placeholder">
                        <i class="fas fa-image fa-2x text-muted mb-2"></i>
                        <p class="mb-1">Cliquez ou glissez une image</p>
                        <small class="text-muted">JPG, PNG, GIF, WEBP, SVG, PSD, AI</small>
                    </div>
                    <div class="image-preview" style="display: none;">
                        <img class="img-fluid rounded" style="max-height: 150px;">
                        <div class="image-info mt-2">
                            <small class="text-muted d-block image-name"></small>
                            <small class="text-muted image-size"></small>
                        </div>
                    </div>
                </div>

                <div class="mt-2">
                    <input type="text" class="form-control form-control-sm image-description" placeholder="Description de l'image (optionnel)">
                </div>
            </div>
        </div>
    </div>
</template>

@endsection

@section('styles')
<style>
.image-upload-zone {
    cursor: pointer;
    transition: all 0.3s ease;
    min-height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.image-upload-zone:hover {
    border-color: #007bff !important;
    background-color: #f8f9ff;
}

.image-upload-zone.dragover {
    border-color: #28a745 !important;
    background-color: #f8fff8;
    transform: scale(1.02);
}

.image-field.thumbnail {
    order: -1;
}

.image-field.thumbnail .card {
    border-color: #ffc107 !important;
    box-shadow: 0 0 0 2px rgba(255, 193, 7, 0.25);
}

.image-field.thumbnail .set-thumbnail {
    color: #ffc107;
    border-color: #ffc107;
}

.image-field.thumbnail .set-thumbnail i {
    color: #ffc107;
}

#globalDropZone.dragover {
    border-color: #28a745 !important;
    background-color: #f8fff8 !important;
    transform: scale(1.01);
}

.fade-in {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.text-purple {
    color: #6f42c1 !important;
}
</style>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/tp-images.js') }}"></script>
@endsection
