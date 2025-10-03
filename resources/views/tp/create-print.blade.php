@extends('layouts.ki-admin')

@section('title', 'Ajouter un Projet Print (PDF)')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-header bg-light">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-file-pdf fa-3x text-danger"></i>
                        </div>
                        <div>
                            <h2 class="mb-1">Nouveau Projet Print</h2>
                            <p class="mb-0 opacity-75">Ajoutez vos documents (PDF, Word, PowerPoint) d'impression (cartes de visite, plaquettes, catalogues...)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="printProjectForm" method="POST" action="{{ route('design-graphique.tp.ajouter') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="type" value="print">

        <div class="row">
            <!-- Formulaire Principal -->
            <div class="col-lg-8">
                <!-- Informations du Projet -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-info-circle text-primary me-2"></i>Informations du Projet Print</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label"><strong>Titre du Projet</strong><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" required maxlength="255" placeholder="Ex: Catalogue produits 2024">
                                <div class="form-text">Maximum 255 caractères</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label"><strong>Type de Document</strong><span class="text-danger">*</span></label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Choisir un type</option>
                                    <option value="carte_visite">Carte de Visite</option>
                                    <option value="plaquette">Plaquette</option>
                                    <option value="catalogue">Catalogue</option>
                                    <option value="livre">Livre</option>
                                    <option value="depliant">Dépliant</option>
                                    <option value="brochure">Brochure</option>
                                    <option value="flyer">Flyer</option>
                                    <option value="affiche">Affiche</option>
                                    <option value="menu">Menu</option>
                                    <option value="rapport">Rapport</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="link" class="form-label">Lien du Projet</label>
                                <input type="url" class="form-control" id="link" name="link" placeholder="https://...">
                                <div class="form-text">Lien vers le projet en ligne (optionnel)</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tags" class="form-label">Tags</label>
                                <input type="text" class="form-control" id="tags" name="tags" placeholder="print, catalogue, design">
                                <div class="form-text">Séparez les tags par des virgules</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Décrivez votre projet d'impression..."></textarea>
                            <div class="form-text">Description optionnelle du projet</div>
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                <div class="card mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-file-alt text-primary me-2"></i>Documents (PDF, Word, PowerPoint)</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addPdfBtn">
                            <i class="fas fa-plus me-1"></i>Ajouter un Document
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning alert-sm mb-3" id="pdfAlert" style="display: none;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Veuillez ajouter au moins un document avant de soumettre le formulaire.
                        </div>
                        
                        <div class="row" id="pdfContainer">
                            <!-- Les champs PDF seront ajoutés ici dynamiquement -->
                        </div>

                        <!-- Zone de drag & drop globale pour documents -->
                        <div id="globalPdfDropZone" class="border-2 border-dashed border-primary rounded p-5 text-center mt-4" style="min-height: 200px; background: #f0f7ff;">
                            <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                            <h5>Glissez-déposez vos documents ici</h5>
                            <p class="text-muted mb-3">ou cliquez sur "Ajouter un Document" ci-dessus</p>
                            <div class="d-flex justify-content-center gap-3 flex-wrap">
                                <div class="text-muted">
                                    <i class="fas fa-file-pdf text-danger me-1"></i>PDF
                                </div>
                                <div class="text-muted">
                                    <i class="fas fa-file-word text-primary me-1"></i>Word (.doc, .docx)
                                </div>
                                <div class="text-muted">
                                    <i class="fas fa-file-powerpoint text-warning me-1"></i>PowerPoint (.ppt, .pptx)
                                </div>
                                <div class="text-muted">
                                    <i class="fas fa-weight-hanging text-info me-1"></i>Max 50MB
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
                                    <div class="h4 mb-1 text-danger">
                                        <span id="pdfCount">0</span>
                                    </div>
                                    <small class="text-muted">PDF Ajoutés</small>
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
                                <i class="fas fa-file-pdf fa-2x mb-2"></i>
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
                                    <input class="form-check-input" type="checkbox" name="software_used[]" value="indesign" id="sw_indesign">
                                    <label class="form-check-label" for="sw_indesign">
                                        <i class="fab fa-adobe text-danger me-1"></i>InDesign
                                    </label>
                                </div>
                            </div>
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
                                    <input class="form-check-input" type="checkbox" name="software_used[]" value="acrobat" id="sw_acrobat">
                                    <label class="form-check-label" for="sw_acrobat">
                                        <i class="fab fa-adobe text-danger me-1"></i>Acrobat
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="software_used[]" value="scribus" id="sw_scribus">
                                    <label class="form-check-label" for="sw_scribus">
                                        <i class="fas fa-file-alt text-success me-1"></i>Scribus
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="software_used[]" value="canva" id="sw_canva">
                                    <label class="form-check-label" for="sw_canva">
                                        <i class="fas fa-palette text-info me-1"></i>Canva
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
                            <button type="submit" class="btn btn-danger btn-lg" id="submitBtn" disabled>
                                <i class="fas fa-save me-2"></i>Créer le Projet Print
                            </button>
                            <a href="{{ route('design-graphique.tp.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                        </div>

                        <div class="mt-3">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 0%" id="formProgress"></div>
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

<!-- Template pour les champs PDF -->
<template id="pdfFieldTemplate">
    <div class="col-md-6 pdf-field" data-index="">
        <div class="card border-2 border-dashed border-danger">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="mb-0">Document <span class="pdf-number"></span></h6>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-pdf" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                <div class="pdf-upload-zone border rounded p-3 text-center position-relative">
                    <input type="file" class="form-control pdf-input" name="files[]" accept=".pdf,.doc,.docx,.ppt,.pptx" style="display: none;">
                    <div class="upload-placeholder">
                        <i class="fas fa-file-alt fa-2x text-primary mb-2"></i>
                        <p class="mb-1">Cliquez ou glissez un document</p>
                        <small class="text-muted">PDF, Word, PowerPoint - Max 50MB</small>
                    </div>
                    <div class="upload-preview" style="display: none;">
                        <i class="fas fa-file-alt fa-2x text-primary mb-2 doc-icon"></i>
                        <p class="mb-1 fw-bold pdf-name"></p>
                        <small class="text-muted pdf-size"></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script src="{{ asset('assets/js/tp-print.js') }}"></script>
@endsection
