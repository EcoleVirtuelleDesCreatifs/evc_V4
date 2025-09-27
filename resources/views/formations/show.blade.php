@extends('layouts.ki-admin')

@section('title', 'Formation - EVC 2024')
@section('page-title', 'Retouche Photo Avancée')

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Vidéo de la formation -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="ratio ratio-16x9 mb-3">
                    <div class="bg-dark d-flex align-items-center justify-content-center rounded">
                        <div class="text-center text-white">
                            <i class="fas fa-play-circle fa-5x mb-3" style="color: var(--primary-color);"></i>
                            <h5>Retouche Photo Avancée</h5>
                            <p class="mb-3">Durée: 45 minutes</p>
                            <button class="btn btn-primary btn-lg">
                                <i class="fas fa-play me-2"></i>
                                Lire la vidéo
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Contrôles de lecture -->
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-backward"></i>
                        </button>
                        <button class="btn btn-primary btn-sm">
                            <i class="fas fa-play"></i>
                        </button>
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-forward"></i>
                        </button>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-volume-up"></i>
                        </button>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description de la formation -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Description
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <span class="badge" style="background-color: var(--primary-color); color: white;">Photoshop</span>
                    <span class="badge bg-secondary ms-1">Niveau Avancé</span>
                </div>
                <p class="mb-3">
                    Apprenez les techniques professionnelles de retouche photo avec Photoshop. Cette formation couvre les outils avancés de correction colorimétrique, les masques de fusion, les filtres professionnels et les techniques de montage créatif utilisées par les professionnels de l'image.
                </p>
                <h6>Ce que vous allez apprendre :</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Correction colorimétrique avancée
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Masques de fusion et calques de réglage
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Filtres et effets professionnels
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Techniques de montage créatif
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Optimisation pour l'impression et le web
                    </li>
                </ul>
            </div>
        </div>

        <!-- Chapitres -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Chapitres
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-play-circle text-primary me-3"></i>
                            <div>
                                <h6 class="mb-1">1. Introduction aux outils avancés</h6>
                                <small class="text-muted">8 minutes</small>
                            </div>
                        </div>
                        <span class="badge" style="background-color: var(--success-color); color: white;">Terminé</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-play-circle text-primary me-3"></i>
                            <div>
                                <h6 class="mb-1">2. Correction colorimétrique</h6>
                                <small class="text-muted">12 minutes</small>
                            </div>
                        </div>
                        <span class="badge" style="background-color: var(--warning-color); color: white;">En cours</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-lock text-muted me-3"></i>
                            <div>
                                <h6 class="mb-1">3. Masques et calques de réglage</h6>
                                <small class="text-muted">15 minutes</small>
                            </div>
                        </div>
                        <span class="badge bg-secondary">Verrouillé</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-lock text-muted me-3"></i>
                            <div>
                                <h6 class="mb-1">4. Filtres professionnels</h6>
                                <small class="text-muted">10 minutes</small>
                            </div>
                        </div>
                        <span class="badge bg-secondary">Verrouillé</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Progression -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Votre progression
                </h6>
            </div>
            <div class="card-body text-center">
                <div class="progress mb-3" style="height: 15px;">
                    <div class="progress-bar" style="width: 35%; background-color: var(--primary-color);" role="progressbar">35%</div>
                </div>
                <p class="mb-0 small text-muted">2 chapitres sur 4 complétés</p>
            </div>
        </div>

        <!-- Ressources -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-download me-2"></i>
                    Ressources
                </h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file-pdf text-danger me-2"></i>
                            <small>Support de cours PDF</small>
                        </div>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file-image text-primary me-2"></i>
                            <small>Images d'exercice</small>
                        </div>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file-code text-success me-2"></i>
                            <small>Actions Photoshop</small>
                        </div>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info me-2"></i>
                    Informations
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <small class="text-muted">Durée totale:</small>
                    <strong class="d-block">45 minutes</strong>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Niveau:</small>
                    <strong class="d-block">Avancé</strong>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Langue:</small>
                    <strong class="d-block">Français</strong>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Dernière mise à jour:</small>
                    <strong class="d-block">26 Juillet 2024</strong>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-cog me-2"></i>
                    Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-bookmark me-1"></i>
                        Ajouter aux favoris
                    </button>

                    <button class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-flag me-1"></i>
                        Signaler un problème
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Navigation entre formations -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Formation précédente
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('design-graphique.formations.index') }}" class="btn btn-secondary">
                            <i class="fas fa-list me-1"></i>
                            Toutes les formations
                        </a>
                    </div>
                    <div>
                        <a href="#" class="btn btn-outline-primary">
                            Formation suivante
                            <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
