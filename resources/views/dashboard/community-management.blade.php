@extends('layouts.ki-admin')

@section('title', 'Espace Étudiant - Community Management')

@section('content')
<div class="container-fluid">
    <!-- Header spécialisé Community Management -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #4267B2 0%, #1877F2 100%);">
                <div class="card-body text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="mb-2 fw-bold">
                                <i class="fas fa-users me-3"></i>
                                Espace Étudiant - Community Management
                            </h1>
                            <p class="mb-0 opacity-90">
                                Formation complète en gestion de communautés : Social Media, Content Marketing & Analytics
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <span class="badge bg-light text-dark px-3 py-2 fs-6">
                                    <i class="fas fa-hashtag me-1"></i>
                                    Social Media Expert
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
                                <i class="fas fa-chart-line fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-5">{{ $stats['progression_globale'] }}%</div>
                            <div class="text-muted small">Progression Globale</div>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar" style="width: {{ $stats['progression_globale'] }}%; background: #4267B2;"></div>
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
                                <i class="fas fa-clipboard-list fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-5">{{ $stats['tp_a_faire'] }}</div>
                            <div class="text-muted small">Campagnes à Créer</div>
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
                                <i class="fas fa-bullhorn fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-5">{{ $stats['projets_en_cours'] }}</div>
                            <div class="text-muted small">Projets Actifs</div>
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
                                <i class="fas fa-award fs-4"></i>
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
                            <div class="text-muted small">Certification CM</div>
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
                        <i class="fas fa-share-alt text-primary me-2"></i>
                        Modules Social Media & Marketing
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #1877F2 0%, #42A5F5 100%);">
                                <i class="fab fa-facebook fs-1 text-white mb-2"></i>
                                <h6 class="text-white mb-1">Social Media</h6>
                                <small class="text-white opacity-75">Stratégie & Engagement</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-light" style="width: 90%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #FF6B35 0%, #F7931E 100%);">
                                <i class="fas fa-pen-fancy fs-1 text-white mb-2"></i>
                                <h6 class="text-white mb-1">Content Marketing</h6>
                                <small class="text-white opacity-75">Création de Contenu</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-light" style="width: 75%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #00D4AA 0%, #00BFA5 100%);">
                                <i class="fas fa-chart-bar fs-1 text-white mb-2"></i>
                                <h6 class="text-white mb-1">Analytics</h6>
                                <small class="text-white opacity-75">Mesure & Performance</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-light" style="width: 65%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #6C5CE7 0%, #A29BFE 100%);">
                                <i class="fas fa-bullseye fs-1 text-white mb-2"></i>
                                <h6 class="text-white mb-1">Brand Strategy</h6>
                                <small class="text-white opacity-75">Stratégie de Marque</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-light" style="width: 55%"></div>
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
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $stats['formation_semaine'] }}</h6>
                            <small class="text-muted">Planification de contenu et engagement communautaire</small>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-video text-danger me-2"></i>
                                    <span class="fw-semibold">Webinaire Live</span>
                                </div>
                                <small class="text-muted">Mardi 15h00 - 17h00</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-users text-info me-2"></i>
                                    <span class="fw-semibold">Atelier Pratique</span>
                                </div>
                                <small class="text-muted">Jeudi 10h00 - 12h00</small>
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
                        <i class="fas fa-fire text-danger me-2"></i>
                        Trending Topics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-danger me-2">#1</span>
                        <span class="small">Instagram Reels Strategy</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-warning me-2">#2</span>
                        <span class="small">TikTok for Business</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-info me-2">#3</span>
                        <span class="small">LinkedIn B2B Content</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Réseaux sociaux et outils -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tools text-primary me-2"></i>
                        Outils & Plateformes
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="text-center p-3 border rounded-3 h-100">
                                <i class="fab fa-facebook text-primary fs-2 mb-2"></i>
                                <h6 class="mb-1">Facebook</h6>
                                <small class="text-muted">Business Manager</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="text-center p-3 border rounded-3 h-100">
                                <i class="fab fa-instagram text-danger fs-2 mb-2"></i>
                                <h6 class="mb-1">Instagram</h6>
                                <small class="text-muted">Creator Studio</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="text-center p-3 border rounded-3 h-100">
                                <i class="fab fa-linkedin text-info fs-2 mb-2"></i>
                                <h6 class="mb-1">LinkedIn</h6>
                                <small class="text-muted">Campaign Manager</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="text-center p-3 border rounded-3 h-100">
                                <i class="fab fa-tiktok text-dark fs-2 mb-2"></i>
                                <h6 class="mb-1">TikTok</h6>
                                <small class="text-muted">Ads Manager</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="text-center p-3 border rounded-3 h-100">
                                <i class="fab fa-google text-warning fs-2 mb-2"></i>
                                <h6 class="mb-1">Analytics</h6>
                                <small class="text-muted">Google Analytics</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="text-center p-3 border rounded-3 h-100">
                                <i class="fas fa-chart-pie text-success fs-2 mb-2"></i>
                                <h6 class="mb-1">Hootsuite</h6>
                                <small class="text-muted">Social Management</small>
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
                        Actions Rapides - Community Management
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-primary w-100 py-3">
                                <i class="fas fa-calendar-plus d-block mb-2"></i>
                                <small>Planifier Post</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-success w-100 py-3">
                                <i class="fas fa-chart-line d-block mb-2"></i>
                                <small>Analytics</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-warning w-100 py-3">
                                <i class="fas fa-lightbulb d-block mb-2"></i>
                                <small>Idées Contenu</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-info w-100 py-3">
                                <i class="fas fa-comments d-block mb-2"></i>
                                <small>Communauté</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-danger w-100 py-3">
                                <i class="fas fa-bullhorn d-block mb-2"></i>
                                <small>Campagnes</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-secondary w-100 py-3">
                                <i class="fas fa-book d-block mb-2"></i>
                                <small>Ressources</small>
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

.badge {
    font-size: 0.75rem;
}
</style>
@endsection
