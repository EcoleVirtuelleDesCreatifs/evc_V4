@extends('layouts.ki-admin')

@section('title', 'Espace Étudiant - Intelligence Artificielle')

@section('content')
<div class="container-fluid">
    <!-- Header spécialisé Intelligence Artificielle -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #00D4AA 0%, #00BFA5 100%);">
                <div class="card-body text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="mb-2 fw-bold">
                                <i class="fas fa-robot me-3"></i>
                                Espace Étudiant - Intelligence Artificielle
                            </h1>
                            <p class="mb-0 opacity-90">
                                Formation complète en IA : Python, Machine Learning, Deep Learning & Data Science
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <span class="badge bg-light text-dark px-3 py-2 fs-6">
                                    <i class="fab fa-python me-1"></i>
                                    AI Developer
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-primary bg-opacity-10 text-primary rounded">
                                <i class="fas fa-brain fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-5">{{ $stats['progression_globale'] }}%</div>
                            <div class="text-muted small">Progression Globale</div>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar" style="width: {{ $stats['progression_globale'] }}%; background: #00D4AA;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-warning bg-opacity-10 text-warning rounded">
                                <i class="fas fa-code fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-5">{{ $stats['tp_a_faire'] }}</div>
                            <div class="text-muted small">Algorithmes à Coder</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-success bg-opacity-10 text-success rounded">
                                <i class="fas fa-project-diagram fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-5">{{ $stats['projets_en_cours'] }}</div>
                            <div class="text-muted small">Modèles IA</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-info bg-opacity-10 text-info rounded">
                                <i class="fas fa-medal fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-5">
                                @if($stats['eligible_certificat'])
                                    <span class="text-success">Éligible</span>
                                @else
                                    <span class="text-warning">En cours</span>
                                @endif
                            </div>
                            <div class="text-muted small">Certification IA</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modules de formation spécialisés -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="fab fa-python text-warning me-2"></i>
                        Modules Intelligence Artificielle
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #3776AB 0%, #FFD43B 100%);">
                                <i class="fab fa-python fs-1 text-white mb-2"></i>
                                <h6 class="text-white mb-1">Python</h6>
                                <small class="text-white opacity-75">Programmation & Libs</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-light" style="width: 70%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #FF6B35 0%, #F7931E 100%);">
                                <i class="fas fa-cogs fs-1 text-white mb-2"></i>
                                <h6 class="text-white mb-1">Machine Learning</h6>
                                <small class="text-white opacity-75">Algorithmes ML</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-light" style="width: 45%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #6C5CE7 0%, #A29BFE 100%);">
                                <i class="fas fa-network-wired fs-1 text-white mb-2"></i>
                                <h6 class="text-white mb-1">Deep Learning</h6>
                                <small class="text-white opacity-75">Réseaux de Neurones</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-light" style="width: 25%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #00D4AA 0%, #00BFA5 100%);">
                                <i class="fas fa-database fs-1 text-white mb-2"></i>
                                <h6 class="text-white mb-1">Data Science</h6>
                                <small class="text-white opacity-75">Analyse de Données</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-light" style="width: 35%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formation de la semaine -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-week text-primary me-2"></i>
                        Formation de la Semaine
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-lg bg-primary bg-opacity-10 text-primary rounded me-3">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $stats['formation_semaine'] }}</h6>
                            <small class="text-muted">Introduction aux algorithmes d'apprentissage supervisé</small>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-laptop-code text-success me-2"></i>
                                    <span class="fw-semibold">Coding Session</span>
                                </div>
                                <small class="text-muted">Lundi 14h00 - 17h00</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-chalkboard-teacher text-info me-2"></i>
                                    <span class="fw-semibold">Théorie ML</span>
                                </div>
                                <small class="text-muted">Mercredi 9h00 - 12h00</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-flask text-warning me-2"></i>
                        Projets Lab
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-sm bg-success bg-opacity-10 text-success rounded me-2">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="small">Classification d'Images</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-sm bg-warning bg-opacity-10 text-warning rounded me-2">
                            <i class="fas fa-clock"></i>
                        </div>
                        <span class="small">Prédiction de Prix</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm bg-info bg-opacity-10 text-info rounded me-2">
                            <i class="fas fa-play"></i>
                        </div>
                        <span class="small">Chatbot NLP</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Outils et frameworks -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tools text-primary me-2"></i>
                        Outils & Frameworks IA
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="text-center p-3 border rounded-3 h-100">
                                <i class="fab fa-python text-warning fs-2 mb-2"></i>
                                <h6 class="mb-1">Python</h6>
                                <small class="text-muted">Langage Principal</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="text-center p-3 border rounded-3 h-100">
                                <i class="fas fa-chart-line text-success fs-2 mb-2"></i>
                                <h6 class="mb-1">TensorFlow</h6>
                                <small class="text-muted">Deep Learning</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="text-center p-3 border rounded-3 h-100">
                                <i class="fas fa-fire text-danger fs-2 mb-2"></i>
                                <h6 class="mb-1">PyTorch</h6>
                                <small class="text-muted">Neural Networks</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="text-center p-3 border rounded-3 h-100">
                                <i class="fas fa-leaf text-success fs-2 mb-2"></i>
                                <h6 class="mb-1">Scikit-learn</h6>
                                <small class="text-muted">Machine Learning</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="text-center p-3 border rounded-3 h-100">
                                <i class="fas fa-table text-info fs-2 mb-2"></i>
                                <h6 class="mb-1">Pandas</h6>
                                <small class="text-muted">Data Analysis</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="text-center p-3 border rounded-3 h-100">
                                <i class="fas fa-chart-bar text-primary fs-2 mb-2"></i>
                                <h6 class="mb-1">Jupyter</h6>
                                <small class="text-muted">Notebooks</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides spécialisées -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-rocket text-primary me-2"></i>
                        Actions Rapides - Intelligence Artificielle
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-primary w-100 py-3">
                                <i class="fas fa-code d-block mb-2"></i>
                                <small>Jupyter Lab</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-success w-100 py-3">
                                <i class="fas fa-database d-block mb-2"></i>
                                <small>Datasets</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-warning w-100 py-3">
                                <i class="fas fa-book d-block mb-2"></i>
                                <small>Documentation</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-info w-100 py-3">
                                <i class="fas fa-users d-block mb-2"></i>
                                <small>Forum IA</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-danger w-100 py-3">
                                <i class="fas fa-flask d-block mb-2"></i>
                                <small>Mes Modèles</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-secondary w-100 py-3">
                                <i class="fas fa-graduation-cap d-block mb-2"></i>
                                <small>Cours</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-lg {
    width: 56px;
    height: 56px;
}

.avatar-sm {
    width: 32px;
    height: 32px;
}

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.btn-outline-primary:hover,
.btn-outline-success:hover,
.btn-outline-warning:hover,
.btn-outline-info:hover,
.btn-outline-danger:hover,
.btn-outline-secondary:hover {
    transform: translateY(-1px);
}
</style>
@endsection
