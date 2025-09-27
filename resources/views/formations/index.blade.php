@extends('layouts.ki-admin')

@section('title', 'Formations - EVC 2024')
@section('page-title', 'Formation')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Statistiques par catégorie -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column">
                        <i class="fas fa-image fa-2x mb-2" style="color: var(--primary-color);"></i>
                        <h4 style="color: var(--primary-color);">12</h4>
                        <small class="text-muted mb-3">Formations Photoshop</small>
                        <div class="mt-auto">
                            <a href="{{ route('formations.category', 'photoshop') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-list me-1"></i>
                                Voir toutes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column">
                        <i class="fas fa-vector-square fa-2x mb-2" style="color: var(--secondary-color);"></i>
                        <h4 style="color: var(--secondary-color);">8</h4>
                        <small class="text-muted mb-3">Formations Illustrator</small>
                        <div class="mt-auto">
                            <a href="{{ route('formations.category', 'illustrator') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-list me-1"></i>
                                Voir toutes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column">
                        <i class="fas fa-file-alt fa-2x mb-2" style="color: var(--accent-color);"></i>
                        <h4 style="color: var(--accent-color);">6</h4>
                        <small class="text-muted mb-3">Formations InDesign</small>
                        <div class="mt-auto">
                            <a href="{{ route('formations.category', 'indesign') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-list me-1"></i>
                                Voir toutes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column">
                        <i class="fas fa-crown fa-2x mb-2" style="color: var(--warning-color);"></i>
                        <h4 style="color: var(--warning-color);">4</h4>
                        <small class="text-muted mb-3">Master Class</small>
                        <div class="mt-auto">
                            <a href="{{ route('formations.category', 'masterclass') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-list me-1"></i>
                                Voir toutes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

</div>

<!-- Formations récentes - Section en pleine largeur -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>
                    Formations hebdomadaires
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-magic fa-2x" style="color: var(--accent-color);"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Retouche Photo Avancée</h6>
                                        <small class="text-muted">Ajoutée il y a 2 jours</small>
                                        <div class="mt-1">
                                            <span class="badge badge-sm" style="background-color: var(--primary-color); color: white;">Photoshop</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <a href="{{ route('formations.show', 1) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>
                                        Voir la formation
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-bezier-curve fa-2x" style="color: var(--secondary-color);"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Création de Logos</h6>
                                        <small class="text-muted">Ajoutée il y a 5 jours</small>
                                        <div class="mt-1">
                                            <span class="badge badge-sm" style="background-color: var(--secondary-color); color: white;">Illustrator</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <a href="{{ route('formations.show', 1) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>
                                        Voir la formation
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-crown fa-2x" style="color: var(--warning-color);"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Strategy Business Design</h6>
                                        <small class="text-muted">Ajoutée il y a 1 semaine</small>
                                        <div class="mt-1">
                                            <span class="badge badge-sm" style="background-color: var(--warning-color); color: white;">Master Class</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <a href="{{ route('formations.show', 1) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>
                                        Voir la formation
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <button class="btn btn-outline-primary">
                        <i class="fas fa-plus me-1"></i>
                        Voir toutes les formations
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Activation des onglets Bootstrap
document.addEventListener('DOMContentLoaded', function() {
    var triggerTabList = [].slice.call(document.querySelectorAll('#formationTabs button'))
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    })
})
</script>
@endsection
