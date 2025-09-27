@extends('layouts.ki-admin')

@section('title', 'TP - EVC 2024')
@section('page-title', 'TP (Travaux Pratiques)')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Debug Info (à supprimer après résolution) -->

        <!-- Informations Profil Utilisateur -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-gradient-primary text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                @if(isset($userProfile) && $userProfile->profile_photo)
                                    <img src="{{ asset('uploads/photos/' . basename($userProfile->profile_photo)) }}" alt="Photo de profil" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover; border: 3px solid rgba(255,255,255,0.3);">
                                @else
                                    <div class="rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <i class="fas fa-user fa-2x text-white"></i>
                                    </div>
                                @endif
            </div>
                            <div class="col">
                                <h4 class="mb-1">
                                    @if(isset($userProfile))
                                        {{ $userProfile->first_name }} {{ $userProfile->last_name }}
                                    @else
                                        {{ session('user_prenom', 'Prénom') }} {{ session('user_nom', 'Nom') }}
                                    @endif
                                </h4>
                                <p class="mb-0 opacity-75">
                                    <i class="fas fa-graduation-cap me-2"></i>
                                    Formation Design Graphique - EVC 2024
                                </p>
                            </div>
                            <div class="col-auto text-end">
                                <div class="badge bg-white text-primary fs-6 px-3 py-2">
                                    <i class="fas fa-tasks me-1"></i>
                                    {{ isset($statistiques) ? $statistiques['tp_realises'] : 0 }} TP réalisés
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques TP Dynamiques -->
        <div class="row mb-4">
            <!-- TP Réalisés -->
            <div class="col-md-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-tasks fa-2x mb-2" style="color: var(--primary-color);"></i>
                        <h3 style="color: var(--primary-color);">{{ isset($statistiques) ? $statistiques['tp_realises'] : 0 }}</h3>
                        <small class="text-muted mb-3">TP Réalisés</small>
                        <div class="mt-auto">
                            <a href="{{ route('design-graphique.tp.tous') }}" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-eye me-1"></i>
                                Voir tous
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- TP En Validation -->
            <div class="col-md-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-clock fa-2x mb-2" style="color: var(--warning-color);"></i>
                        <h3 style="color: var(--warning-color);">
                            @if(isset($validationStats) && is_array($validationStats))
                                {{ $validationStats['tp_en_validation'] ?? 0 }}
                            @else
                                0
                            @endif
                        </h3>
                        <small class="text-muted mb-3">TP En Validation</small>
                        <div class="mt-auto">
                            <button class="btn btn-warning btn-sm w-100">
                                <i class="fas fa-hourglass-half me-1"></i>
                                En attente
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- TP Validés -->
            <div class="col-md-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-medal fa-2x mb-2" style="color: var(--success-color);"></i>
                        <h3 style="color: var(--success-color);">
                            @if(isset($validationStats) && is_array($validationStats))
                                {{ $validationStats['tp_valides'] ?? 0 }}
                            @else
                                0
                            @endif
                        </h3>
                        <small class="text-muted mb-3">TP Validés</small>
                        <div class="mt-auto">
                            <button class="btn btn-success btn-sm w-100">
                                <i class="fas fa-check me-1"></i>
                                Validés
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- TP À Faire -->
            <div class="col-md-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-list fa-2x mb-2" style="color: var(--accent-color);"></i>
                        <h3 style="color: var(--accent-color);">{{ isset($statistiques) ? $statistiques['tp_a_faire'] : 20 }}</h3>
                        <small class="text-muted mb-3">TP À Faire</small>
                        <div class="mt-auto">
                            <button class="btn btn-secondary btn-sm w-100">
                                <i class="fas fa-list-ul me-1"></i>
                                Planifier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barre de progression -->
        @if(isset($statistiques))
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Progression Formation</h6>
                            <span class="badge bg-primary">{{ $statistiques['progression_pourcentage'] }}%</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-gradient-primary" role="progressbar"
                                 style="width: {{ $statistiques['progression_pourcentage'] }}%"
                                 aria-valuenow="{{ $statistiques['progression_pourcentage'] }}"
                                 aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <small class="text-muted mt-1">
                            {{ $statistiques['tp_realises'] }} TP sur {{ $statistiques['tp_total'] }} requis pour la certification
                        </small>
                    </div>
                </div>
            </div>
        </div>
        @endif

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

        <!-- Liste des TP à faire -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>
                    TP à faire
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>TP</th>
                                <th>Catégorie</th>
                                <th>Échéance</th>
                                <th>Priorité</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div>
                                        <h6 class="mb-1">TP Photoshop - Retouche Portrait</h6>
                                        <small class="text-muted">Retouche professionnelle d'un portrait avec techniques avancées</small>
                                    </div>
                                </td>
                                <td><span class="badge" style="background-color: var(--primary-color); color: white;">Photoshop</span></td>
                                <td>
                                    <small class="text-danger">
                                        <i class="fas fa-calendar me-1"></i>
                                        30 Juillet 2024
                                    </small>
                                </td>
                                <td><span class="badge" style="background-color: var(--accent-color); color: white;">Urgent</span></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-primary" title="Commencer">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary" title="Détails">
                                            <i class="fas fa-info"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <h6 class="mb-1">TP InDesign - Magazine Layout</h6>
                                        <small class="text-muted">Création d'une mise en page de magazine avec grilles et typographie</small>
                                    </div>
                                </td>
                                <td><span class="badge" style="background-color: var(--accent-color); color: white;">InDesign</span></td>
                                <td>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        5 Août 2024
                                    </small>
                                </td>
                                <td><span class="badge" style="background-color: var(--primary-color); color: white;">Normal</span></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-primary" title="Commencer">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary" title="Détails">
                                            <i class="fas fa-info"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <h6 class="mb-1">TP Illustrator - Logo Design</h6>
                                        <small class="text-muted">Création d'un logo vectoriel avec techniques avancées</small>
                                    </div>
                                </td>
                                <td><span class="badge" style="background-color: var(--secondary-color); color: white;">Illustrator</span></td>
                                <td>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        10 Août 2024
                                    </small>
                                </td>
                                <td><span class="badge" style="background-color: var(--warning-color); color: white;">Normal</span></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-primary" title="Commencer">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary" title="Détails">
                                            <i class="fas fa-info"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <h6 class="mb-1">TP Strategy Business - Plan d'affaires</h6>
                                        <small class="text-muted">Création d'un plan d'affaires pour une entreprise de création graphique</small>
                                    </div>
                                </td>
                                <td><span class="badge" style="background-color: var(--warning-color); color: white;">Master Class</span></td>
                                <td>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        15 Août 2024
                                    </small>
                                </td>
                                <td><span class="badge" style="background-color: var(--success-color); color: white;">Normal</span></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-primary" title="Commencer">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary" title="Détails">
                                            <i class="fas fa-info"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
