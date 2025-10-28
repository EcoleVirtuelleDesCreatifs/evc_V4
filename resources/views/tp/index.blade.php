@extends('layouts.ki-admin')

@section('title', 'Mes Travaux Pratiques - Community Management')
@section('page-title', 'Travaux Pratiques')

@section('content')
<style>
    /* Instagram Color Palette */
    :root {
        --instagram-purple: #833AB4;
        --instagram-pink: #C13584;
        --instagram-red: #E1306C;
        --instagram-orange: #F56040;
        --instagram-yellow: #FCAF45;
    }
    
    /* Dégradé principal Instagram */
    .instagram-gradient {
        background: linear-gradient(135deg, #833AB4 0%, #C13584 25%, #E1306C 50%, #F56040 75%, #FCAF45 100%);
    }
    
    .stat-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        color: white;
        position: relative;
        box-shadow: 0 8px 24px rgba(131, 58, 180, 0.15);
    }
    
    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 16px 40px rgba(131, 58, 180, 0.25);
    }
    
    /* Cartes avec dégradés Instagram */
    .stat-card.total-tp {
        background: linear-gradient(135deg, #833AB4 0%, #C13584 100%);
    }
    
    .stat-card.en-attente {
        background: linear-gradient(135deg, #F56040 0%, #FCAF45 100%);
    }
    
    .stat-card.valides {
        background: linear-gradient(135deg, #C13584 0%, #E1306C 100%);
    }
    
    .stat-card.taux-reussite {
        background: linear-gradient(135deg, #833AB4 0%, #E1306C 50%, #FCAF45 100%);
    }
    
    .stat-number {
        font-size: 4rem;
        font-weight: 900;
        line-height: 1;
        text-shadow: 2px 4px 8px rgba(0,0,0,0.2);
        background: linear-gradient(180deg, #ffffff 0%, rgba(255,255,255,0.8) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stat-icon {
        font-size: 3.5rem;
        opacity: 0.15;
        position: absolute;
        right: 15px;
        top: 15px;
    }
    
    .stat-label {
        font-size: 0.85rem;
        font-weight: 600;
        opacity: 0.95;
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }

    .stat-btn {
        background: rgba(255,255,255,0.25);
        border: 2px solid rgba(255,255,255,0.4);
        color: white;
        font-weight: 700;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 0.7rem 1.2rem;
    }
    
    .stat-btn:hover {
        background: rgba(255,255,255,0.35);
        border-color: white;
        color: white;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(255,255,255,0.3);
    }
    
    .section-title {
        font-size: 2rem;
        font-weight: 900;
        background: linear-gradient(135deg, #833AB4 0%, #E1306C 50%, #FCAF45 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
        position: relative;
        padding-bottom: 0.75rem;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100px;
        height: 5px;
        background: linear-gradient(90deg, #833AB4 0%, #E1306C 50%, #FCAF45 100%);
        border-radius: 3px;
    }

    .tp-card {
        border: none;
        border-radius: 24px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
        box-shadow: 0 4px 20px rgba(131, 58, 180, 0.1);
        border: 2px solid transparent;
    }
    
    .tp-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(131, 58, 180, 0.2);
        border-color: rgba(131, 58, 180, 0.3);
    }
    
    .tp-header {
        background: linear-gradient(135deg, #833AB4 0%, #C13584 50%, #E1306C 100%);
        color: white;
        padding: 2.5rem;
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
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1) rotate(0deg); }
        50% { transform: scale(1.1) rotate(5deg); }
    }

    .tp-item {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: white;
        position: relative;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        border: 2px solid rgba(131, 58, 180, 0.1);
    }
    
    .tp-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 28px rgba(131, 58, 180, 0.15);
        border-color: rgba(131, 58, 180, 0.3);
    }
    
    .tp-icon-wrapper {
        width: 75px;
        height: 75px;
        border-radius: 18px;
        background: linear-gradient(135deg, #833AB4 0%, #E1306C 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 20px rgba(131, 58, 180, 0.3);
    }
    
    .tp-title {
        font-weight: 800;
        color: #2d3748;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }
    
    .badge-custom {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 700;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    /* Badges avec couleurs Instagram */
    .badge-validated {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    }
    
    .badge-rejected {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
    }
    
    .badge-pending {
        background: linear-gradient(135deg, #F56040 0%, #FCAF45 100%) !important;
    }
    
    .btn-view-tp {
        background: linear-gradient(135deg, #833AB4 0%, #E1306C 100%);
        border: none;
        color: white;
        font-weight: 700;
        padding: 0.75rem 1.5rem;
        border-radius: 30px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(131, 58, 180, 0.3);
    }
    
    .btn-view-tp:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(131, 58, 180, 0.4);
        color: white;
        background: linear-gradient(135deg, #C13584 0%, #F56040 100%);
    }
    
    .progress-bar-custom {
        height: 28px;
        border-radius: 20px;
        background: rgba(255,255,255,0.25);
        overflow: hidden;
        position: relative;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,1) 100%);
        border-radius: 20px;
        transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(255,255,255,0.3);
    }
    
    .progress-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        animation: shimmer 2.5s infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .fade-in-up {
        animation: fadeInUp 0.7s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Amélioration des espacements pour une meilleure UX */
    .card-body {
        padding: 1.75rem !important;
    }
    
    /* Effet de brillance sur les cartes */
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .stat-card:hover::before {
        left: 100%;
    }
    
    /* Responsive improvements */
    @media (max-width: 768px) {
        .stat-number {
            font-size: 3rem;
        }
        
        .section-title {
            font-size: 1.5rem;
        }
        
        .tp-icon-wrapper {
            width: 60px;
            height: 60px;
        }
    }

    /* Pagination Instagram */
    .pagination-instagram {
        display: flex;
        gap: 0.5rem;
        padding: 0;
        margin: 0;
    }

    .pagination-instagram .page-item {
        list-style: none;
    }

    .pagination-instagram .page-link {
        background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-pink));
        backdrop-filter: blur(10px);
        border: 2px solid var(--instagram-pink);
        color: white !important;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 45px;
        height: 45px;
        box-shadow: 0 4px 15px rgba(131, 58, 180, 0.3);
    }

    .pagination-instagram .page-link:hover {
        background: linear-gradient(135deg, var(--instagram-pink), var(--instagram-red));
        border-color: var(--instagram-red);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(193, 53, 132, 0.5);
        color: white !important;
    }

    .pagination-instagram .page-item.active .page-link {
        background: linear-gradient(135deg, var(--instagram-red), var(--instagram-orange));
        border-color: var(--instagram-orange);
        box-shadow: 0 6px 20px rgba(225, 48, 108, 0.6);
        transform: scale(1.1);
    }

    .pagination-instagram .page-item.disabled .page-link {
        background: rgba(131, 58, 180, 0.3);
        border-color: rgba(131, 58, 180, 0.5);
        color: rgba(255, 255, 255, 0.5) !important;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .pagination-instagram .page-item.disabled .page-link:hover {
        transform: none;
        box-shadow: 0 4px 15px rgba(131, 58, 180, 0.3);
        background: rgba(131, 58, 180, 0.3);
    }

    /* Animation pour la pagination */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .pagination-instagram {
        animation: fadeIn 0.5s ease;
    }

</style>

<!-- Section Statistiques -->
<div class="row mb-4 mt-3">
    <div class="col-12">
        <h2 class="section-title">
            <i class="fas fa-chart-bar me-2"></i>
            Statistiques de vos TP
        </h2>
        <p class="text-white opacity-75 mb-0">Suivez votre progression et vos performances</p>
    </div>
</div>

<div class="row mb-4 g-3">
    <div class="col-lg-3 col-md-6 mb-3 fade-in-up" style="animation-delay: 0.1s;">
        <div class="stat-card total-tp h-100">
            <i class="fas fa-tasks stat-icon"></i>
            <div class="card-body p-4">
                <div class="stat-label mb-2">Total TP</div>
                <div class="stat-number">{{ isset($statistiques) ? $statistiques['tp_realises'] : 0 }}</div>
                <p class="mb-3 opacity-75">Projets soumis</p>
                <a href="{{ route('community-management.tp.tous') }}" class="btn stat-btn w-100">
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
                <div class="stat-label mb-2">En Attente</div>
                <div class="stat-number">
                    @if(isset($validationStats) && is_array($validationStats))
                        {{ $validationStats['tp_en_validation'] ?? 0 }}
                    @else
                        0
                    @endif
                </div>
                <p class="mb-3 opacity-75">En validation</p>
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
        <a href="{{ route('community-management.tp.ajouter') }}?type=digital" class="text-decoration-none">
            <div class="stat-card h-100" style="min-height: 280px; cursor: pointer; background: linear-gradient(135deg, #833AB4 0%, #C13584 50%, #E1306C 100%);">
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
        <div class="tp-card h-100" style="min-height: 280px; border: 3px solid transparent; background: linear-gradient(white, white) padding-box, linear-gradient(135deg, #833AB4, #E1306C, #FCAF45) border-box;">
            <div class="card-body p-5 d-flex flex-column justify-content-center">
                <div class="mb-4 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, #833AB4 0%, #E1306C 100%); border-radius: 50%;">
                        <i class="fas fa-info-circle text-white" style="font-size: 3rem;"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-3 text-center" style="font-size: 1.6rem; background: linear-gradient(135deg, #833AB4 0%, #E1306C 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    Guide de Soumission
                </h3>
                <ul class="list-unstyled mb-0" style="color: #4a5568;">
                    <li class="mb-3 d-flex align-items-start">
                        <i class="fas fa-check-circle me-3 mt-1" style="color: #C13584; font-size: 1.2rem;"></i>
                        <span><strong>Qualité :</strong> Assurez-vous que votre travail est complet et professionnel</span>
                    </li>
                    <li class="mb-3 d-flex align-items-start">
                        <i class="fas fa-check-circle me-3 mt-1" style="color: #E1306C; font-size: 1.2rem;"></i>
                        <span><strong>Format :</strong> Respectez les consignes et le format demandé</span>
                    </li>
                    <li class="mb-3 d-flex align-items-start">
                        <i class="fas fa-check-circle me-3 mt-1" style="color: #F56040; font-size: 1.2rem;"></i>
                        <span><strong>Délai :</strong> Soumettez vos projets dans les temps impartis</span>
                    </li>
                    <li class="d-flex align-items-start">
                        <i class="fas fa-check-circle me-3 mt-1" style="color: #FCAF45; font-size: 1.2rem;"></i>
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
                                        <a href="{{ route('community-management.tp.voir', $tp->id) }}" class="btn btn-view-tp w-100">
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
