@extends('layouts.ki-admin')

@section('title', 'Mes Travaux Pratiques')
@section('page-title', 'Travaux Pratiques')

@section('content')
@php
    // Détection automatique de la formation depuis l'URL
    $currentModule = request()->segment(3); // design-graphique, community-management, etc.
    $routePrefix = $currentModule;
@endphp

<style>
    /* Simple Modern UI/UX Design */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: #f5f7fa;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: #1a202c;
        line-height: 1.6;
    }

    .container-fluid {
        background: #f5f7fa;
        padding: 2rem 1rem;
    }

    /* Page Header */
    .page-header {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e2e8f0;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1a202c;
        margin: 0;
    }

    .page-subtitle {
        font-size: 0.875rem;
        color: #718096;
        margin-top: 0.25rem;
    }

    /* Simple Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        position: relative;
    }

    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    }

    .stat-card.total-tp {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    }

    .stat-card.en-attente {
        background: linear-gradient(135deg, #ea580c 0%, #fb923c 100%);
    }

    .stat-card.valides {
        background: linear-gradient(135deg, #0ea5e9 0%, #7dd3fc 100%);
    }

    .stat-card.taux-reussite {
        background: linear-gradient(135deg, #2563eb 0%, #f97316 100%);
    }

    .stat-number {
        font-size: 3.5rem;
        font-weight: 800;
        line-height: 1;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .stat-icon {
        font-size: 3rem;
        opacity: 0.3;
        position: absolute;
        right: 20px;
        top: 20px;
    }

    .stat-label {
        font-size: 0.9rem;
        font-weight: 500;
        opacity: 0.95;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .stat-btn {
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.3);
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .stat-btn:hover {
        background: rgba(255,255,255,0.3);
        border-color: white;
        color: white;
        transform: scale(1.05);
    }

    /* Buttons - Simple */
    .btn {
        display: inline-block;
        padding: 0.625rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-align: center;
        border-radius: 6px;
        border: 1px solid transparent;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-primary {
        background: #2b6cb0;
        color: white;
        border-color: #2b6cb0;
    }

    .btn-primary:hover {
        background: #2c5282;
        color: white;
    }

    .btn-outline {
        background: white;
        color: #4a5568;
        border-color: #cbd5e0;
    }

    .btn-outline:hover {
        background: #f7fafc;
    }

    /* Section Headers */
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        padding-bottom: 1rem;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(135deg, #2563eb 0%, #f97316 100%);
        border-radius: 2px;
    }

    /* TP Cards - Simple & Clean */
    .tp-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    .tp-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }

    .tp-header {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .tp-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 3s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .badge-custom {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .btn-view-tp {
        background: linear-gradient(135deg, #2563eb 0%, #f97316 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.6rem 1.5rem;
        border-radius: 25px;
        transition: all 0.3s ease;
    }

    .btn-view-tp:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(37, 99, 235, 0.4);
        color: white;
    }


    .tp-item {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        position: relative;
        margin-bottom: 1rem;
    }

    .tp-item:hover {
        transform: scale(1.02);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .tp-icon-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 15px;
        background: linear-gradient(135deg, #2563eb 0%, #f97316 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 5px 15px rgba(37, 99, 235, 0.4);
    }

    .tp-title {
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    /* Badges - Simple */
    .badge {
        display: inline-block;
        padding: 0.25rem 0.625rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 4px;
    }

    .badge-success {
        background: #c6f6d5;
        color: #22543d;
    }

    .badge-danger {
        background: #fed7d7;
        color: #742a2a;
    }

    .badge-warning {
        background: #feebc8;
        color: #7c2d12;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.8125rem;
    }

    /* Progress Bar - Simple */
    .progress {
        height: 6px;
        background: #e2e8f0;
        border-radius: 3px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        background: #2b6cb0;
        border-radius: 3px;
    }



    .text-muted {
        color: #718096;
        font-size: 0.875rem;
    }

    /* Spacing Utilities */
    .mb-1 { margin-bottom: 0.5rem; }
    .mb-2 { margin-bottom: 1rem; }
    .mb-3 { margin-bottom: 1.5rem; }
    .mt-2 { margin-top: 1rem; }
    .mt-3 { margin-top: 1.5rem; }


    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        background: white;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .empty-state-text {
        color: #718096;
        font-size: 0.9375rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .page-header {
            padding: 1rem;
        }

        .stat-value {
            font-size: 1.75rem;
        }
    }

    /* Table Simple */
    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .table th {
        font-weight: 600;
        font-size: 0.8125rem;
        color: #4a5568;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .table td {
        color: #2d3748;
        font-size: 0.9375rem;
    }

    /* Divider */
    .divider {
        height: 1px;
        background: #e2e8f0;
        margin: 1.5rem 0;
    }

    /* Flex Utilities */
    .d-flex {
        display: flex;
    }

    .align-items-center {
        align-items: center;
    }

    .justify-between {
        justify-content: space-between;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    .gap-3 {
        gap: 1rem;
    }

    /* Animation fadeInUp from Formations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Mobile Optimizations */
    @media (max-width: 768px) {
        .section-title {
            font-size: 1.2rem;
            line-height: 1.2;
            margin-bottom: 1rem !important;
        }
        .tp-header {
            padding: 1.2rem !important;
        }
        .tp-header h3 {
            font-size: 1.1rem !important;
            line-height: 1.2;
            margin-bottom: 0 !important;
        }
        .tp-header i {
            font-size: 1.2rem !important;
            margin-right: 0.5rem !important;
        }
        .stat-number {
            font-size: 1.8rem;
        }
        .card-body h2, .card-body h3, .card-body h4, .card-body h5 {
            font-size: 1.1rem !important;
            line-height: 1.2;
        }
        .card-body i.fa-3x, .card-body i.fa-4x, .card-body i.fa-5x {
            font-size: 1.5rem !important;
        }

        .d-inline-flex,
        .icon-circle {
            width: 40px !important;
            height: 40px !important;
            min-width: 40px !important;
        }

        .d-inline-flex i {
            font-size: 1rem !important;
        }

        .card-body {
            padding: 1rem !important;
        }
    }
</style>

<!-- Section Statistiques -->
<div class="row mb-5 mt-4">
    <div class="col-12">
        <h2 class="section-title">Statistiques de vos TP</h2>
        <p style="color: white; opacity: 0.75;">Suivez votre progression et vos performances</p>
    </div>
</div>

<div class="row mb-4 g-3">
    <div class="col-lg-3 col-md-6 mb-3 fade-in-up" style="animation-delay: 0.1s;">
        <div class="stat-card total-tp h-100">
            <i class="fas fa-tasks stat-icon"></i>
            <div class="card-body p-4">
                <div class="stat-label">Total TP</div>
                <div class="stat-number">{{ isset($statistiques) ? $statistiques['tp_realises'] : 0 }}</div>
                <p class="text-muted-custom mb-3">Projets soumis</p>
                <a href="{{ route($routePrefix . '.tp.tous') }}" class="btn stat-btn w-100">
                    <i class="fas fa-arrow-right me-2"></i>
                    Voir Tous
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3 fade-in-up" style="animation-delay: 0.2s;">
        <div class="stat-card en-attente h-100">
            <i class="fas fa-clock stat-icon"></i>
            <div class="card-body p-4">
                <div class="stat-label">En Attente</div>
                <div class="stat-number">
                    @if(isset($validationStats) && is_array($validationStats))
                        {{ $validationStats['tp_en_validation'] ?? 0 }}
                    @else
                        0
                    @endif
                </div>
                <p class="text-muted-custom mb-3">En validation</p>
                <button class="btn stat-btn w-100">
                    <i class="fas fa-hourglass-half me-2"></i>
                    En Cours
                </button>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3 fade-in-up" style="animation-delay: 0.3s;">
        <div class="stat-card valides h-100">
            <i class="fas fa-check-circle stat-icon"></i>
            <div class="card-body p-4">
                <div class="stat-label mb-2">Validés</div>
                <div class="stat-number">
                    @if(isset($validationStats) && is_array($validationStats))
                        {{ $validationStats['tp_valides'] ?? 0 }}
                    @else
                        0
                    @endif
                </div>
                <p class="mb-3 opacity-75">Projets approuvés</p>
                <button class="btn stat-btn w-100">
                    <i class="fas fa-medal me-2"></i>
                    Succès
                </button>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3 fade-in-up" style="animation-delay: 0.4s;">
        <div class="stat-card taux-reussite h-100">
            <i class="fas fa-trophy stat-icon"></i>
            <div class="card-body p-4">
                <div class="stat-label mb-2">Taux Réussite</div>
                <div class="stat-number">
                    @php
                        $total = isset($statistiques) ? $statistiques['tp_realises'] : 0;
                        $valides = isset($validationStats) && is_array($validationStats) ? ($validationStats['tp_valides'] ?? 0) : 0;
                        $taux = $total > 0 ? round(($valides / $total) * 100) : 0;
                    @endphp
                    {{ $taux }}%
                </div>
                <p class="mb-3 opacity-75">Performance</p>
                <button class="btn stat-btn w-100">
                    <i class="fas fa-star me-2"></i>
                    Excellence
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Section Ajouter des Projets -->
<div class="row mt-5 mb-4">
    <div class="col-12">
        <h2 class="section-title">
            <i class="fas fa-plus-circle me-2"></i>
            Ajouter un Nouveau Projet
        </h2>
        <p class="text-white opacity-75 mb-0">Créez et soumettez vos travaux pratiques pour validation</p>
    </div>
</div>

<div class="row mb-5 g-4">
    <!-- Ajouter Projet Digital -->
    <div class="col-lg-6 mb-3 fade-in-up" style="animation-delay: 0.1s;">
        <a href="{{ route($routePrefix . '.tp.ajouter') }}?type=digital" class="text-decoration-none">
            <div class="stat-card h-100" style="min-height: 280px; cursor: pointer; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #4fc3f7 100%);">
                <i class="fas fa-laptop stat-icon" style="font-size: 5rem; opacity: 0.15;"></i>
                <div class="card-body p-5 d-flex flex-column justify-content-center text-center">
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: rgba(255,255,255,0.2); border-radius: 50%; backdrop-filter: blur(10px);">
                            <i class="fas fa-laptop" style="font-size: 3rem; opacity: 1;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-3" style="font-size: 1.6rem; letter-spacing: 0.5px;">Projet Digital</h3>
                    <p class="mb-4 opacity-90" style="font-size: 0.95rem; line-height: 1.6;">
                        Réseaux sociaux, campagnes digitales, content marketing, stratégies web et community management
                    </p>
                    <div class="btn stat-btn mx-auto" style="padding: 0.8rem 2rem; font-size: 1rem;">
                        <i class="fas fa-plus-circle me-2"></i>
                        Créer un Projet
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Carte Info / Aide -->
    <div class="col-lg-6 mb-3 fade-in-up" style="animation-delay: 0.2s;">
        <div class="tp-card h-100" style="min-height: 280px; border: 3px solid transparent; background: linear-gradient(white, white) padding-box, linear-gradient(135deg, #1e3c72, #4fc3f7, #29b6f6) border-box;">
            <div class="card-body p-5 d-flex flex-column justify-content-center">
                <div class="mb-4 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, #1e3c72 0%, #4fc3f7 100%); border-radius: 50%;">
                        <i class="fas fa-info-circle text-white" style="font-size: 3rem;"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-3 text-center" style="font-size: 1.6rem; background: linear-gradient(135deg, #1e3c72 0%, #4fc3f7 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    Guide de Soumission
                </h3>
                <ul class="list-unstyled mb-0" style="color: #4a5568;">
                    <li class="mb-3 d-flex align-items-start">
                        <i class="fas fa-check-circle me-3 mt-1" style="color: #1e3c72; font-size: 1.2rem;"></i>
                        <span><strong>Qualité :</strong> Assurez-vous que votre travail est complet et professionnel</span>
                    </li>
                    <li class="mb-3 d-flex align-items-start">
                        <i class="fas fa-check-circle me-3 mt-1" style="color: #2a5298; font-size: 1.2rem;"></i>
                        <span><strong>Format :</strong> Respectez les consignes et le format demandé</span>
                    </li>
                    <li class="mb-3 d-flex align-items-start">
                        <i class="fas fa-check-circle me-3 mt-1" style="color: #4fc3f7; font-size: 1.2rem;"></i>
                        <span><strong>Délai :</strong> Soumettez vos projets dans les temps impartis</span>
                    </li>
                    <li class="d-flex align-items-start">
                        <i class="fas fa-check-circle me-3 mt-1" style="color: #29b6f6; font-size: 1.2rem;"></i>
                        <span><strong>Validation :</strong> Attendez la validation de votre formateur</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Progression Alternative - Design Moderne -->
@if(isset($statistiques))
<div class="row mt-5 mb-4">
    <div class="col-12 mb-2">
        <h2 class="section-title">
            <i class="fas fa-chart-line me-2"></i>
            Progression Formation Community Management
        </h2>
        <p class="text-white opacity-75 mb-0">Visualisez votre avancement dans la formation</p>
    </div>
</div>

<div class="row mb-4 g-3">
    <!-- Carte Progression Principale -->
    <div class="col-lg-6 mb-3 fade-in-up" style="animation-delay: 0.1s;">
        <div class="stat-card valides h-100" style="min-height: 280px;">
            <i class="fas fa-graduation-cap stat-icon" style="font-size: 4rem;"></i>
            <div class="card-body p-4 d-flex flex-column justify-content-center">
                <div class="stat-label mb-3">Progression Globale</div>
                <div class="stat-number mb-3">{{ $statistiques['progression_pourcentage'] }}%</div>
                <div class="progress-bar-custom mb-3">
                    <div class="progress-fill" style="width: {{ $statistiques['progression_pourcentage'] }}%;">
                    </div>
                </div>
                <p class="mb-0 opacity-90" style="font-size: 1rem; font-weight: 600;">
                    <i class="fas fa-trophy me-2"></i>
                    {{ $statistiques['tp_realises'] }} sur {{ $statistiques['tp_total'] }} TP complétés
                </p>
            </div>
        </div>
    </div>

    <!-- Mini Cartes Statistiques -->
    <div class="col-lg-6">
        <div class="row">
            <!-- TP Réalisés -->
            <div class="col-md-6 mb-3 fade-in-up" style="animation-delay: 0.2s;">
                <div class="stat-card total-tp h-100">
                    <i class="fas fa-check-circle stat-icon"></i>
                    <div class="card-body p-3 text-center">
                        <div class="stat-label mb-2" style="font-size: 0.8rem;">TP Réalisés</div>
                        <div class="stat-number" style="font-size: 2.5rem;">{{ $statistiques['tp_realises'] }}</div>
                        <p class="mb-0 opacity-75" style="font-size: 0.85rem;">Complétés</p>
                    </div>
                </div>
            </div>

            <!-- TP Requis -->
            <div class="col-md-6 mb-3 fade-in-up" style="animation-delay: 0.3s;">
                <div class="stat-card taux-reussite h-100">
                    <i class="fas fa-list-check stat-icon"></i>
                    <div class="card-body p-3 text-center">
                        <div class="stat-label mb-2" style="font-size: 0.8rem;">TP Requis</div>
                        <div class="stat-number" style="font-size: 2.5rem;">{{ $statistiques['tp_total'] }}</div>
                        <p class="mb-0 opacity-75" style="font-size: 0.85rem;">Au total</p>
                    </div>
                </div>
            </div>

            <!-- TP Restants -->
            <div class="col-md-12 fade-in-up" style="animation-delay: 0.4s;">
                <div class="stat-card en-attente h-100">
                    <i class="fas fa-hourglass-half stat-icon"></i>
                    <div class="card-body p-3 text-center">
                        <div class="stat-label mb-2" style="font-size: 0.8rem;">TP Restants</div>
                        <div class="stat-number" style="font-size: 2.5rem;">{{ $statistiques['tp_a_faire'] }}</div>
                        <p class="mb-0 opacity-75" style="font-size: 0.85rem;">À compléter pour la certification</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if(isset($tps) && count($tps) > 0)
<!-- Section TP de la Semaine -->
<style>
    @media (max-width: 768px) {
        /* Force font size reduction */
        .tp-header h3,
        .section-title,
        .card-body h1,
        .card-body h2,
        .card-body h3,
        .card-body h4,
        .card-body h5,
        .stat-card h3 {
            font-size: 1.1rem !important;
            line-height: 1.2 !important;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        /* Force icon size reduction */
        .tp-header i,
        .stat-card i,
        .icon-circle i,
        .d-inline-flex i,
        i.fa-3x, i.fa-4x, i.fa-5x {
            font-size: 1.2rem !important;
        }

        /* Container adjustments */
        .d-inline-flex,
        .icon-circle {
            width: 40px !important;
            height: 40px !important;
            min-width: 40px !important;
        }

        .card-body {
            padding: 1rem !important;
        }

        .stat-number {
            font-size: 1.5rem !important;
        }
    }
</style>
<div class="row mt-5 mb-4">
    <div class="col-12">
        <h2 class="section-title">
            <i class="fas fa-fire me-2"></i>
            Vos Travaux Pratiques en Cours
        </h2>
        <p class="text-white opacity-75 mb-0">Découvrez vos projets récents et en attente de validation</p>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="tp-card fade-in-up">
            <div class="tp-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-2 position-relative fw-bold" style="font-size: 1.8rem;">
                            <i class="fas fa-folder-open me-3"></i>
                            Mes Projets
                        </h3>
                        <p class="mb-0 opacity-90" style="font-size: 0.95rem;">{{ count($tps) }} projet(s) au total</p>
                    </div>
                    <div class="d-none d-md-block">
                        <div class="d-inline-flex align-items-center justify-content-center" style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 50%; backdrop-filter: blur(10px);">
                            <i class="fas fa-briefcase" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="row g-4">
                    @foreach($tps as $index => $tp)
                    <div class="col-lg-4 col-md-6 fade-in-up" style="animation-delay: {{ 0.1 * (($index % 6) + 1) }}s;">
                        <div class="tp-item h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="tp-icon-wrapper flex-shrink-0">
                                        <i class="fas fa-{{ ($tp->type ?? 'digital') == 'digital' ? 'laptop' : 'print' }} fa-2x text-white"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="tp-title">{{ $tp->title ?? 'Sans titre' }}</h5>
                                        @if(isset($tp->status))
                                            <span class="badge badge-custom {{ $tp->status == 'validated' ? 'badge-validated' : ($tp->status == 'rejected' ? 'badge-rejected' : 'badge-pending') }}" style="color: white;">
                                                <i class="fas fa-{{ $tp->status == 'validated' ? 'check-circle' : ($tp->status == 'rejected' ? 'times-circle' : 'clock') }} me-1"></i>
                                                {{ $tp->status == 'validated' ? 'Validé' : ($tp->status == 'rejected' ? 'Rejeté' : 'En attente') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @if(!empty($tp->created_at))
                                    <p class="text-muted small mb-3">
                                        <i class="fas fa-calendar-plus me-1"></i>
                                        Ajouté le {{ \Carbon\Carbon::parse($tp->created_at)->format('d/m/Y') }}
                                    </p>
                                @endif

                                <div class="mt-auto">
                                    @if(!empty($tp->id))
                                        <a href="{{ route($routePrefix . '.tp.voir', $tp->id) }}" class="btn btn-view-tp w-100 no-fancybox">
                                            <i class="fas fa-eye me-2"></i>
                                            Voir le TP
                                        </a>
                                    @else
                                        <button class="btn btn-secondary w-100" disabled>
                                            <i class="fas fa-lock me-2"></i>
                                            Indisponible
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($tps->hasPages())
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="d-flex justify-content-center">
                            <nav aria-label="Navigation des TP">
                                <ul class="pagination pagination-instagram">
                                    {{-- Bouton Précédent --}}
                                    @if ($tps->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="fas fa-chevron-left"></i>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $tps->previousPageUrl() }}" rel="prev">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Numéros de page --}}
                                    @foreach ($tps->getUrlRange(1, $tps->lastPage()) as $page => $url)
                                        @if ($page == $tps->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    {{-- Bouton Suivant --}}
                                    @if ($tps->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $tps->nextPageUrl() }}" rel="next">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="fas fa-chevron-right"></i>
                                            </span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>

                        {{-- Info pagination --}}
                        <div class="text-center mt-3">
                            <p class="text-white opacity-75">
                                Affichage de {{ $tps->firstItem() ?? 0 }} à {{ $tps->lastItem() ?? 0 }} sur {{ $tps->total() }} TP
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

@endsection
