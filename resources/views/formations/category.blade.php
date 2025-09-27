@extends('layouts.ki-admin')

@section('title', 'Formations {{ ucfirst($category) }} - EVC 2024')
@section('page-title', 'Formations {{ ucfirst($category) }}')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- En-tête de la catégorie -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            @if($category == 'photoshop')
                                <i class="fas fa-image fa-3x me-4" style="color: var(--primary-color);"></i>
                                <div>
                                    <h3 class="mb-1">Formations Photoshop</h3>
                                    <p class="text-muted mb-0">Maîtrisez la retouche photo et le design graphique avec Adobe Photoshop</p>
                                </div>
                            @elseif($category == 'illustrator')
                                <i class="fas fa-vector-square fa-3x me-4" style="color: var(--secondary-color);"></i>
                                <div>
                                    <h3 class="mb-1">Formations Illustrator</h3>
                                    <p class="text-muted mb-0">Créez des illustrations vectorielles professionnelles avec Adobe Illustrator</p>
                                </div>
                            @elseif($category == 'indesign')
                                <i class="fas fa-file-alt fa-3x me-4" style="color: var(--accent-color);"></i>
                                <div>
                                    <h3 class="mb-1">Formations InDesign</h3>
                                    <p class="text-muted mb-0">Concevez des mises en page professionnelles avec Adobe InDesign</p>
                                </div>
                            @else
                                <i class="fas fa-crown fa-3x me-4" style="color: var(--warning-color);"></i>
                                <div>
                                    <h3 class="mb-1">Master Class</h3>
                                    <p class="text-muted mb-0">Formations avancées et stratégie business pour les professionnels</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('design-graphique.formations.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Retour
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-filter me-1"></i>
                                    Filtrer
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Tous les niveaux</a></li>
                                    <li><a class="dropdown-item" href="#">Débutant</a></li>
                                    <li><a class="dropdown-item" href="#">Intermédiaire</a></li>
                                    <li><a class="dropdown-item" href="#">Avancé</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques de la catégorie -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h4 style="color: var(--primary-color);">
                            @if($category == 'photoshop') 12
                            @elseif($category == 'illustrator') 8
                            @elseif($category == 'indesign') 6
                            @else 4
                            @endif
                        </h4>
                        <small class="text-muted">Formations disponibles</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h4 style="color: var(--success-color);">
                            @if($category == 'photoshop') 8h 30min
                            @elseif($category == 'illustrator') 6h 15min
                            @elseif($category == 'indesign') 4h 45min
                            @else 3h 20min
                            @endif
                        </h4>
                        <small class="text-muted">Durée totale</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h4 style="color: var(--warning-color);">
                            @if($category == 'photoshop') 65%
                            @elseif($category == 'illustrator') 40%
                            @elseif($category == 'indesign') 25%
                            @else 15%
                            @endif
                        </h4>
                        <small class="text-muted">Progression</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h4 style="color: var(--accent-color);">
                            @if($category == 'photoshop') 3
                            @elseif($category == 'illustrator') 2
                            @elseif($category == 'indesign') 1
                            @else 1
                            @endif
                        </h4>
                        <small class="text-muted">Nouvelles cette semaine</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des formations -->
        <div class="row">
            @if($category == 'photoshop')
                <!-- Formations Photoshop -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-magic fa-2x me-3" style="color: var(--primary-color);"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Retouche Photo Avancée</h6>
                                    <small class="text-muted">45 minutes • Avancé</small>
                                </div>
                            </div>
                            <p class="text-muted small mb-3">Techniques professionnelles de retouche photo et correction colorimétrique.</p>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar" style="width: 75%; background-color: var(--primary-color);"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge" style="background-color: var(--success-color); color: white;">75% terminé</span>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('formations.show', 1) }}" class="btn btn-primary">
                                        <i class="fas fa-play"></i>
                                    </a>
                                    <button class="btn btn-outline-primary">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-layer-group fa-2x me-3" style="color: var(--primary-color);"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Masques et Calques</h6>
                                    <small class="text-muted">35 minutes • Intermédiaire</small>
                                </div>
                            </div>
                            <p class="text-muted small mb-3">Maîtrisez les masques de fusion et les modes de fusion des calques.</p>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar" style="width: 100%; background-color: var(--success-color);"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge" style="background-color: var(--success-color); color: white;">Terminé</span>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('formations.show', 2) }}" class="btn btn-primary">
                                        <i class="fas fa-play"></i>
                                    </a>
                                    <button class="btn btn-outline-primary">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-palette fa-2x me-3" style="color: var(--primary-color);"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Correction Colorimétrique</h6>
                                    <small class="text-muted">40 minutes • Intermédiaire</small>
                                </div>
                            </div>
                            <p class="text-muted small mb-3">Techniques avancées de correction des couleurs et de l'exposition.</p>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar" style="width: 30%; background-color: var(--warning-color);"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge" style="background-color: var(--warning-color); color: white;">30% terminé</span>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('formations.show', 3) }}" class="btn btn-primary">
                                        <i class="fas fa-play"></i>
                                    </a>
                                    <button class="btn btn-outline-primary">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($category == 'illustrator')
                <!-- Formations Illustrator -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-bezier-curve fa-2x me-3" style="color: var(--secondary-color);"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Création de Logos</h6>
                                    <small class="text-muted">50 minutes • Intermédiaire</small>
                                </div>
                            </div>
                            <p class="text-muted small mb-3">Concevez des logos professionnels avec les outils vectoriels.</p>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar" style="width: 60%; background-color: var(--secondary-color);"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge" style="background-color: var(--warning-color); color: white;">60% terminé</span>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('formations.show', 4) }}" class="btn btn-primary">
                                        <i class="fas fa-play"></i>
                                    </a>
                                    <button class="btn btn-outline-primary">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-shapes fa-2x me-3" style="color: var(--secondary-color);"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Illustrations Vectorielles</h6>
                                    <small class="text-muted">60 minutes • Avancé</small>
                                </div>
                            </div>
                            <p class="text-muted small mb-3">Créez des illustrations complexes avec les outils vectoriels avancés.</p>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar" style="width: 0%; background-color: var(--secondary-color);"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-secondary">Pas commencé</span>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('formations.show', 5) }}" class="btn btn-primary">
                                        <i class="fas fa-play"></i>
                                    </a>
                                    <button class="btn btn-outline-primary">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($category == 'indesign')
                <!-- Formations InDesign -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-file-alt fa-2x me-3" style="color: var(--accent-color);"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Mise en Page Magazine</h6>
                                    <small class="text-muted">55 minutes • Intermédiaire</small>
                                </div>
                            </div>
                            <p class="text-muted small mb-3">Créez des mises en page professionnelles pour magazines et brochures.</p>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar" style="width: 40%; background-color: var(--accent-color);"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge" style="background-color: var(--warning-color); color: white;">40% terminé</span>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('formations.show', 6) }}" class="btn btn-primary">
                                        <i class="fas fa-play"></i>
                                    </a>
                                    <button class="btn btn-outline-primary">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- Master Class -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 border-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-crown fa-2x me-3" style="color: var(--warning-color);"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Strategy Business Design</h6>
                                    <small class="text-muted">90 minutes • Expert</small>
                                </div>
                            </div>
                            <p class="text-muted small mb-3">Stratégies avancées de design thinking et business model canvas.</p>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar" style="width: 20%; background-color: var(--warning-color);"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge" style="background-color: var(--warning-color); color: white;">20% terminé</span>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('formations.show', 7) }}" class="btn btn-primary">
                                        <i class="fas fa-play"></i>
                                    </a>
                                    <button class="btn btn-outline-primary">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="Navigation des formations">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <span class="page-link">Précédent</span>
                        </li>
                        <li class="page-item active">
                            <span class="page-link">1</span>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">3</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">Suivant</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection
