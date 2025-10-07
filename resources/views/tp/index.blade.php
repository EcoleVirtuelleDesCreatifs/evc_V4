@extends('layouts.ki-admin')

@section('title', 'TP - EVC 2024')
@section('page-title', 'TP (Travaux Pratiques)')

@section('content')
<style>
    .tp-stat-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        background: white;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    
    .tp-stat-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .tp-stat-card.realises {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white;
    }
    
    .tp-stat-card.validation {
        background: linear-gradient(135deg, #ea580c 0%, #fb923c 100%);
        color: white;
    }
    
    .tp-stat-card.valides {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        color: white;
    }
    
    .tp-stat-card.a-faire {
        background: linear-gradient(135deg, #6b7280 0%, #9ca3af 100%);
        color: white;
    }
    
    .tp-stat-number {
        font-size: 4rem;
        font-weight: 900;
        line-height: 1;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.2);
        margin: 1rem 0;
    }
    
    .tp-stat-label {
        font-size: 0.95rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        opacity: 0.95;
    }
    
    .tp-stat-icon {
        font-size: 3.5rem;
        opacity: 0.2;
        position: absolute;
        right: 15px;
        top: 15px;
    }
    
    .tp-stat-btn {
        background: rgba(255,255,255,0.25);
        border: 2px solid rgba(255,255,255,0.4);
        color: white;
        font-weight: 700;
        padding: 0.6rem 1.5rem;
        border-radius: 30px;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    
    .tp-stat-btn:hover {
        background: rgba(255,255,255,0.4);
        border-color: white;
        color: white;
        transform: scale(1.05);
    }
    
    .progress-card {
        border: none;
        border-radius: 20px;
        background: white;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .progress-bar-custom {
        height: 30px;
        border-radius: 15px;
        background: linear-gradient(90deg, #1e40af 0%, #3b82f6 50%, #ea580c 100%);
        background-size: 200% 100%;
        animation: progressGlow 3s ease-in-out infinite;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
    }
    
    @keyframes progressGlow {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    .tp-list-card {
        border: none;
        border-radius: 20px;
        background: white;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .tp-list-header {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white;
        padding: 1.5rem 2rem;
        border: none;
    }
    
    .tp-item-row {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .tp-item-row:hover {
        background: linear-gradient(90deg, rgba(59, 130, 246, 0.05) 0%, transparent 100%);
        border-left-color: #3b82f6;
        transform: translateX(5px);
    }
    
    .tp-title {
        font-weight: 700;
        color: #1e293b;
        font-size: 1.1rem;
    }
    
    .tp-description {
        color: #64748b;
        font-size: 0.9rem;
    }
    
    .badge-category {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .badge-photoshop {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white;
    }
    
    .badge-indesign {
        background: linear-gradient(135deg, #ea580c 0%, #fb923c 100%);
        color: white;
    }
    
    .badge-illustrator {
        background: linear-gradient(135deg, #0ea5e9 0%, #7dd3fc 100%);
        color: white;
    }
    
    .badge-masterclass {
        background: linear-gradient(135deg, #2563eb 0%, #f97316 100%);
        color: white;
    }
    
    .badge-priority {
        padding: 0.4rem 0.9rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.8rem;
    }
    
    .badge-urgent {
        background: #ef4444;
        color: white;
    }
    
    .badge-normal {
        background: #f59e0b;
        color: white;
    }
    
    .tp-action-btn {
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .tp-action-btn.btn-start {
        background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
        color: white;
    }
    
    .tp-action-btn.btn-start:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(37, 99, 235, 0.4);
    }
    
    .tp-action-btn.btn-info {
        background: #e5e7eb;
        color: #374151;
    }
    
    .tp-action-btn.btn-info:hover {
        background: #d1d5db;
    }
    
    .section-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: #1e293b;
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
</style>

<div class="row">
    <div class="col-12">
        <!-- Section Titre -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="section-title">
                    <i class="fas fa-chart-line me-2"></i>
                    Tableau de Bord TP
                </h2>
            </div>
        </div>

        <!-- Statistiques TP Modernes -->
        <div class="row mb-5">
            <!-- TP Réalisés -->
            <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.1s;">
                <div class="tp-stat-card realises h-100">
                    <i class="fas fa-tasks tp-stat-icon"></i>
                    <div class="card-body p-4 text-center">
                        <div class="tp-stat-label mb-2">TP Réalisés</div>
                        <div class="tp-stat-number">{{ isset($statistiques) ? $statistiques['tp_realises'] : 0 }}</div>
                        <a href="{{ route('design-graphique.tp.tous') }}" class="btn tp-stat-btn w-100 mt-3">
                            <i class="fas fa-eye me-2"></i>
                            Voir Tous
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- TP En Validation -->
            <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.2s;">
                <div class="tp-stat-card validation h-100">
                    <i class="fas fa-clock tp-stat-icon"></i>
                    <div class="card-body p-4 text-center">
                        <div class="tp-stat-label mb-2">En Validation</div>
                        <div class="tp-stat-number">
                            @if(isset($validationStats) && is_array($validationStats))
                                {{ $validationStats['tp_en_validation'] ?? 0 }}
                            @else
                                0
                            @endif
                        </div>
                        <button class="btn tp-stat-btn w-100 mt-3">
                            <i class="fas fa-hourglass-half me-2"></i>
                            En Attente
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- TP Validés -->
            <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.3s;">
                <div class="tp-stat-card valides h-100">
                    <i class="fas fa-medal tp-stat-icon"></i>
                    <div class="card-body p-4 text-center">
                        <div class="tp-stat-label mb-2">TP Validés</div>
                        <div class="tp-stat-number">
                            @if(isset($validationStats) && is_array($validationStats))
                                {{ $validationStats['tp_valides'] ?? 0 }}
                            @else
                                0
                            @endif
                        </div>
                        <button class="btn tp-stat-btn w-100 mt-3">
                            <i class="fas fa-check-circle me-2"></i>
                            Validés
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- TP À Faire -->
            <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.4s;">
                <div class="tp-stat-card a-faire h-100">
                    <i class="fas fa-list tp-stat-icon"></i>
                    <div class="card-body p-4 text-center">
                        <div class="tp-stat-label mb-2">TP À Faire</div>
                        <div class="tp-stat-number">{{ isset($statistiques) ? $statistiques['tp_a_faire'] : 20 }}</div>
                        <button class="btn tp-stat-btn w-100 mt-3">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Planifier
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barre de progression -->
        @if(isset($statistiques))
        <div class="row mb-5 fade-in-up" style="animation-delay: 0.5s;">
            <div class="col-12">
                <div class="progress-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-chart-line me-2 text-primary"></i>
                                Progression Formation
                            </h5>
                            <span class="badge" style="background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); color: white; padding: 0.6rem 1.2rem; border-radius: 20px; font-size: 1.1rem; font-weight: 700;">
                                {{ $statistiques['progression_pourcentage'] }}%
                            </span>
                        </div>
                        <div class="progress" style="height: 30px; border-radius: 15px; background: #e5e7eb;">
                            <div class="progress-bar-custom" role="progressbar"
                                 style="width: {{ $statistiques['progression_pourcentage'] }}%"
                                 aria-valuenow="{{ $statistiques['progression_pourcentage'] }}"
                                 aria-valuemin="0" aria-valuemax="100">
                                <span class="fw-bold px-3">{{ $statistiques['progression_pourcentage'] }}%</span>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0">
                            <i class="fas fa-check-circle me-2 text-success"></i>
                            <strong>{{ $statistiques['tp_realises'] }} TP</strong> sur <strong>{{ $statistiques['tp_total'] }}</strong> requis pour la certification
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Liste des TP à faire -->
        <div class="tp-list-card mb-5 fade-in-up" style="animation-delay: 0.6s;">
            <div class="tp-list-header">
                <h4 class="mb-0 fw-bold">
                    <i class="fas fa-tasks me-3"></i>
                    Travaux Pratiques à Réaliser
                </h4>
                <p class="mb-0 mt-2 opacity-75">Complétez vos TP pour progresser dans votre formation</p>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <tr>
                                <th class="py-3 px-4 fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px; color: #64748b;">TP</th>
                                <th class="py-3 px-4 fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px; color: #64748b;">Catégorie</th>
                                <th class="py-3 px-4 fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px; color: #64748b;">Échéance</th>
                                <th class="py-3 px-4 fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px; color: #64748b;">Priorité</th>
                                <th class="py-3 px-4 fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px; color: #64748b;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="tp-item-row">
                                <td class="py-4 px-4">
                                    <div>
                                        <h6 class="tp-title mb-1">TP Photoshop - Retouche Portrait</h6>
                                        <p class="tp-description mb-0">Retouche professionnelle d'un portrait avec techniques avancées</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="badge badge-category badge-photoshop">
                                        <i class="fas fa-image me-1"></i>
                                        Photoshop
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="d-flex align-items-center text-danger">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        <span class="fw-semibold">30 Juillet 2024</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="badge badge-priority badge-urgent">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        Urgent
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="d-flex gap-2">
                                        <button class="tp-action-btn btn-start" title="Commencer">
                                            <i class="fas fa-play me-1"></i>
                                            Commencer
                                        </button>
                                        <button class="tp-action-btn btn-info" title="Détails">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr class="tp-item-row">
                                <td class="py-4 px-4">
                                    <div>
                                        <h6 class="tp-title mb-1">TP InDesign - Magazine Layout</h6>
                                        <p class="tp-description mb-0">Création d'une mise en page de magazine avec grilles et typographie</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="badge badge-category badge-indesign">
                                        <i class="fas fa-file-alt me-1"></i>
                                        InDesign
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        <span class="fw-semibold">5 Août 2024</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="badge badge-priority badge-normal">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Normal
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="d-flex gap-2">
                                        <button class="tp-action-btn btn-start" title="Commencer">
                                            <i class="fas fa-play me-1"></i>
                                            Commencer
                                        </button>
                                        <button class="tp-action-btn btn-info" title="Détails">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr class="tp-item-row">
                                <td class="py-4 px-4">
                                    <div>
                                        <h6 class="tp-title mb-1">TP Illustrator - Logo Design</h6>
                                        <p class="tp-description mb-0">Création d'un logo vectoriel avec techniques avancées</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="badge badge-category badge-illustrator">
                                        <i class="fas fa-vector-square me-1"></i>
                                        Illustrator
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        <span class="fw-semibold">10 Août 2024</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="badge badge-priority badge-normal">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Normal
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="d-flex gap-2">
                                        <button class="tp-action-btn btn-start" title="Commencer">
                                            <i class="fas fa-play me-1"></i>
                                            Commencer
                                        </button>
                                        <button class="tp-action-btn btn-info" title="Détails">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr class="tp-item-row">
                                <td class="py-4 px-4">
                                    <div>
                                        <h6 class="tp-title mb-1">TP Strategy Business - Plan d'affaires</h6>
                                        <p class="tp-description mb-0">Création d'un plan d'affaires pour une entreprise de création graphique</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="badge badge-category badge-masterclass">
                                        <i class="fas fa-crown me-1"></i>
                                        Master Class
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        <span class="fw-semibold">15 Août 2024</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="badge badge-priority badge-normal">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Normal
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="d-flex gap-2">
                                        <button class="tp-action-btn btn-start" title="Commencer">
                                            <i class="fas fa-play me-1"></i>
                                            Commencer
                                        </button>
                                        <button class="tp-action-btn btn-info" title="Détails">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Boutons Ajouter Projets -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="row justify-content-center">
                    <!-- Bouton Projet Digital -->
                    <div class="col-md-5 col-12 mb-3 text-center">
                        <div class="add-tp-container position-relative">
                            <a href="{{ route('design-graphique.tp.ajouter') }}?type=digital" class="btn btn-add-tp btn-digital btn-lg px-4 py-3 position-relative overflow-hidden text-decoration-none w-100">
                                <span class="btn-content position-relative z-index-2">
                                    <i class="fas fa-laptop me-2 pulse-icon"></i>
                                    Ajouter un Projet Digital
                                </span>
                                <div class="wave-effect"></div>
                                <div class="wave-effect wave-2"></div>
                                <div class="wave-effect wave-3"></div>
                            </a>
                        </div>
                    </div>

                    <!-- Bouton Projet Print -->
                    <div class="col-md-5 col-12 mb-3 text-center">
                        <div class="add-tp-container position-relative">
                            <a href="{{ route('design-graphique.tp.ajouter') }}?type=print" class="btn btn-add-tp btn-print btn-lg px-4 py-3 position-relative overflow-hidden text-decoration-none w-100">
                                <span class="btn-content position-relative z-index-2">
                                    <i class="fas fa-print me-2 pulse-icon"></i>
                                    Ajouter un Projet Print
                                </span>
                                <div class="wave-effect"></div>
                                <div class="wave-effect wave-2"></div>
                                <div class="wave-effect wave-3"></div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .add-tp-container {
            display: inline-block;
        }

        .btn-add-tp {
            border: none;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 50px;
            transition: all 0.3s ease;
            animation: gradientShift 3s ease-in-out infinite, pulse 2s infinite;
        }

        .btn-digital {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            background-size: 300% 300%;
            box-shadow: 0 8px 25px rgba(0, 51, 102, 0.3);
        }

        .btn-print {
            background: linear-gradient(45deg, var(--accent-color), #FF9900);
            background-size: 300% 300%;
            box-shadow: 0 8px 25px rgba(255, 102, 51, 0.3);
        }

        .btn-add-tp:hover {
            transform: translateY(-3px);
            color: white;
        }

        .btn-digital:hover {
            box-shadow: 0 12px 35px rgba(0, 51, 102, 0.4);
        }

        .btn-print:hover {
            box-shadow: 0 12px 35px rgba(255, 102, 51, 0.4);
        }

        .btn-add-tp:active {
            transform: translateY(-1px);
        }

        .btn-content {
            z-index: 2;
        }

        .pulse-icon {
            animation: iconPulse 1.5s ease-in-out infinite;
        }

        .wave-effect {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            animation: waveRipple 2s infinite;
        }

        .wave-2 {
            animation-delay: 0.7s;
        }

        .wave-3 {
            animation-delay: 1.4s;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes pulse {
            0% { box-shadow: 0 8px 25px rgba(0, 51, 102, 0.3); }
            50% { box-shadow: 0 8px 25px rgba(51, 153, 255, 0.4), 0 0 20px rgba(255, 102, 51, 0.3); }
            100% { box-shadow: 0 8px 25px rgba(0, 51, 102, 0.3); }
        }

        @keyframes iconPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        @keyframes waveRipple {
            0% {
                width: 0;
                height: 0;
                opacity: 1;
            }
            50% {
                width: 100px;
                height: 100px;
                opacity: 0.5;
            }
            100% {
                width: 200px;
                height: 200px;
                opacity: 0;
            }
        }

        /* Effet de clignotement subtil */
        @keyframes blink {
            0%, 50% { opacity: 1; }
            25% { opacity: 0.8; }
            75% { opacity: 0.9; }
        }

        .btn-add-tp::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-add-tp:hover::before {
            left: 100%;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .btn-add-tp {
                padding: 15px 30px;
                font-size: 16px;
            }
        }
        </style>


    </div>
</div>
@endsection
