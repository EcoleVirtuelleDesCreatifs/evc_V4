{{--
    Vue Dashboard Design Graphique - Laravel Blade

    Cette vue affiche l'espace étudiant personnalisé pour la formation Design Graphique.
    Elle utilise uniquement les directives Blade Laravel sans PHP natif.

    Variables attendues du contrôleur :
    - $userProfile : Informations de l'utilisateur connecté
    - $userStats : Statistiques de progression de l'utilisateur
    - $tpStats : Statistiques des travaux pratiques
    - $validationStats : Statistiques de validation des TP
    - $recentTPs : Liste des derniers TP ajoutés
    - $stats : Statistiques générales
--}}

@extends('layouts.ki-admin')

@section('title', 'Espace Étudiant - Design Graphique')

@section('content')
<div class="container-fluid">

    {{-- En-tête spécialisé Design Graphique avec gradient orange --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #FF6B35 0%, #F7931E 100%);">
                <div class="card-body text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="mb-2 fw-bold">
                                <i class="fas fa-palette me-3"></i>
                                Espace Étudiant - Design Graphique
                            </h1>
                            <p class="mb-0 opacity-90">
                                Formation complète en infographie : Photoshop, Illustrator, InDesign & Strategy Business
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <span class="badge bg-light text-dark px-3 py-2 fs-6">
                                    <i class="fab fa-adobe me-1"></i>
                                    Adobe Creative Suite
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistiques principales - Données dynamiques depuis la base --}}
    <div class="row g-4 mb-4">

        {{-- Carte TP Validés --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-primary bg-opacity-10 text-primary rounded">
                                <i class="fas fa-file-alt fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-5">
                                @isset($validationStats['tp_valides'])
                                    {{ $validationStats['tp_valides'] }}
                                @else
                                    0
                                @endisset
                            </div>
                            <div class="text-muted small">TP Validés</div>
                        </div>
                    </div>

                    {{-- Message de félicitation si TP validés --}}
                    @if(isset($validationStats['tp_valides']) && $validationStats['tp_valides'] > 0)
                        <div class="mt-2">
                            <small class="text-success">
                                <i class="fas fa-check-circle me-1"></i>
                                Excellent travail !
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Carte Projets/Fichiers --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-warning bg-opacity-10 text-warning rounded">
                                <i class="fas fa-folder-open fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-5">
                                @isset($tpStats['total_files'])
                                    {{ $tpStats['total_files'] }}
                                @else
                                    0
                                @endisset
                            </div>
                            <div class="text-muted small">Fichiers Uploadés</div>
                        </div>
                    </div>

                    {{-- Affichage de l'espace utilisé --}}
                    @if(isset($tpStats['total_size_mb']) && $tpStats['total_size_mb'] > 0)
                        <div class="mt-2">
                            <small class="text-info">
                                <i class="fas fa-hdd me-1"></i>
                                {{ number_format($tpStats['total_size_mb'], 1) }} MB utilisés
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Carte TP En Validation - Diagnostic amélioré --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-warning bg-opacity-10 text-warning rounded">
                                <i class="fas fa-clock fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-5">
                                {{-- Affichage sécurisé avec debug --}}
                                @if(isset($validationStats) && is_array($validationStats) && array_key_exists('tp_en_validation', $validationStats))
                                    {{ $validationStats['tp_en_validation'] }}
                                @else
                                    0
                                @endif
                            </div>
                            <div class="text-muted small">En cours de validation</div>
                        </div>
                    </div>

                    {{-- Message selon l'état de validation --}}
                    @if(isset($validationStats['tp_en_validation']) && $validationStats['tp_en_validation'] > 0)

                    @else
                        <div class="mt-2">
                            <small class="text-success">
                                <i class="fas fa-check-circle me-1"></i>
                                Aucun TP en attente
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Carte Documents PDF --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-danger bg-opacity-10 text-danger rounded">
                                <i class="fas fa-file-pdf fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-5">
                                @isset($tpStats['total_pdf'])
                                    {{ $tpStats['total_pdf'] }}
                                @else
                                    0
                                @endisset
                            </div>
                            <div class="text-muted small">Documents PDF</div>
                        </div>
                    </div>

                    {{-- Message si des PDF sont disponibles --}}
                    @if(isset($tpStats['total_pdf']) && $tpStats['total_pdf'] > 0)
                        <div class="mt-2">
                            <small class="text-success">
                                <i class="fas fa-download me-1"></i>
                                Ressources disponibles
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Section Formation de la Semaine --}}
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
                    {{-- En-tête de la formation --}}
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-lg bg-primary bg-opacity-10 text-primary rounded me-3">
                            <i class="fab fa-adobe"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">
                                @isset($stats['formation_semaine'])
                                    {{ $stats['formation_semaine'] }}
                                @else
                                    Formation Design Graphique - Semaine {{ date('W') }}
                                @endisset
                            </h6>
                            <small class="text-muted">Module Photoshop - Techniques avancées</small>
                        </div>
                    </div>

                    {{-- Cours de la semaine --}}
                    <div class="row g-3">
                        {{-- Cours théorique --}}
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-play-circle text-primary me-2"></i>
                                    <strong>Cours théorique</strong>
                                </div>
                                <p class="mb-2 small">Maîtrise des calques et masques de fusion</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-primary bg-opacity-10 text-primary">2h30</span>
                                    <small class="text-muted">Lundi 9h00 - 11h30</small>
                                </div>
                            </div>
                        </div>

                        {{-- TP Pratique --}}
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-laptop-code text-success me-2"></i>
                                    <strong>TP Pratique</strong>
                                </div>
                                <p class="mb-2 small">Création d'un montage photo complexe</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success bg-opacity-10 text-success">3h00</span>
                                    <small class="text-muted">Mercredi 14h00 - 17h00</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar Achievements - Récompenses et badges --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-trophy text-warning me-2"></i>
                        Mes Récompenses
                    </h5>
                </div>
                <div class="card-body">
                    {{-- Badge Maître Photoshop --}}
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-sm bg-warning bg-opacity-10 text-warning rounded me-2">
                            <i class="fas fa-medal"></i>
                        </div>
                        <span class="small">Maître Photoshop</span>
                    </div>

                    {{-- Badge Premier Projet --}}
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-sm bg-success bg-opacity-10 text-success rounded me-2">
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="small">Premier Projet Validé</span>
                    </div>

                    {{-- Badge Assiduité --}}
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm bg-info bg-opacity-10 text-info rounded me-2">
                            <i class="fas fa-clock"></i>
                        </div>
                        <span class="small">Assidu (100%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Section Profil Étudiant -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-circle me-2 text-primary"></i>
                        Mon Profil Étudiant
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Photo et informations de base -->
                        <div class="col-lg-4">
                            <div class="text-center">
                                @if(isset($userProfile) && $userProfile->profile_photo)
                                    <img src="{{ asset('uploads/photos/' . basename($userProfile->profile_photo)) }}"
                                         alt="Photo de profil"
                                         class="rounded-circle mb-3"
                                         style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #FF6B35;">
                                @elseif(session('user_photo'))
                                    <img src="{{ asset('uploads/photos/' . basename(session('user_photo'))) }}"
                                         alt="Photo de profil"
                                         class="rounded-circle mb-3"
                                         style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #FF6B35;">
                                @else
                                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mb-3 mx-auto"
                                         style="width: 120px; height: 120px; border: 4px solid #FF6B35;">
                                        <i class="fas fa-user fs-1 text-primary"></i>
                                    </div>
                                @endif

                                <h4 class="fw-bold text-dark mb-1">
                                    @if(isset($userProfile) && $userProfile->first_name && $userProfile->last_name)
                                        {{ $userProfile->first_name }} {{ $userProfile->last_name }}
                                    @elseif(session('user_prenom') && session('user_nom'))
                                        {{ session('user_prenom') }} {{ session('user_nom') }}
                                    @else
                                        Utilisateur
                                    @endif
                                </h4>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-envelope me-1"></i>
                                    @isset($userProfile->email)
                                        {{ $userProfile->email }}
                                    @else
                                        {{ session('user_email', 'Email non disponible') }}
                                    @endisset
                                </p>
                                <span class="badge bg-success px-3 py-2">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Étudiant Actif
                                </span>
                            </div>
                        </div>



                        <!-- Informations détaillées -->
                        <div class="col-lg-8">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-graduation-cap text-primary fs-5"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="fw-bold text-dark">Formation</div>
                                            <div class="text-muted small">
                                                {{-- Affichage sécurisé de la formation avec fallback --}}
                                                @if(isset($userProfile) && property_exists($userProfile, 'formation_souhaitee') && $userProfile->formation_souhaitee)
                                                    {{ ucfirst(str_replace('_', ' ', $userProfile->formation_souhaitee)) }}
                                                @elseif(session('user_formation'))
                                                    {{ ucfirst(str_replace(['_', '-'], ' ', session('user_formation'))) }}
                                                @else
                                                    Design Graphique
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-level-up-alt text-warning fs-5"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="fw-bold text-dark">Niveau (Au debut)</div>
                                            <div class="text-muted small">
                                                @if(isset($userProfile) && property_exists($userProfile, 'current_level') && $userProfile->current_level)
                                                    {{ ucfirst($userProfile->current_level) }}
                                                @else
                                                    {{ session('user_niveau', 'Non spécifié') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-globe text-info fs-5"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="fw-bold text-dark">Localisation</div>
                                            <div class="text-muted small">
                                                @if(isset($userProfile) && $userProfile->city && $userProfile->country)
                                                    {{ $userProfile->city }}, {{ $userProfile->country }}
                                                @else
                                                    {{ session('user_ville', 'Ville') }}, {{ session('user_pays', 'Pays') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-phone text-success fs-5"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="fw-bold text-dark">Téléphone</div>
                                            <div class="text-muted small">
                                                @isset($userProfile->phone)
                                    {{ $userProfile->phone }}
                                @else
                                    {{ session('user_telephone', 'Non spécifié') }}
                                @endisset
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="flex-shrink-0">
                                            <i class="fab fa-whatsapp text-success fs-5"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="fw-bold text-dark">WhatsApp</div>
                                            <div class="text-muted small">
                                                @if(isset($userProfile) && $userProfile->whatsapp)
                                                    {{ $userProfile->whatsapp }}
                                                @else
                                                    {{ session('user_whatsapp', 'Non spécifié') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-calendar-alt text-secondary fs-5"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="fw-bold text-dark">Inscription</div>
                                            <div class="text-muted small">
                                                @if(isset($userProfile) && $userProfile->created_at)
                                                    {{ date('d/m/Y', strtotime($userProfile->created_at)) }}
                                                @else
                                                    {{ date('d/m/Y') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-chart-line text-primary fs-5"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="fw-bold text-dark">Progression</div>
                                            <div class="text-muted small">
                                                @if(isset($userStats) && $userStats->completion_percentage)
                                                    {{ number_format($userStats->completion_percentage, 1) }}%
                                                @else
                                                    0%
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-tasks text-warning fs-5"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="fw-bold text-dark">TP Validés</div>
                                            <div class="text-muted small">
                                                @if(isset($stats) && isset($stats['tp_realises']))
                                                    {{ $stats['tp_realises'] }} / {{ $stats['tp_total_requis'] ?? 20 }} TP
                                                @else
                                                    0 / 20 TP
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions rapides -->
                            <div class="mt-4">
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('design-graphique.profil.editer') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit me-1"></i>
                                        Modifier le profil
                                    </a>
                                    <a href="#" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>
                                        Voir mon CV
                                    </a>
                                    <a href="#" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-download me-1"></i>
                                        Télécharger certificat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Section des derniers TP ajoutés -->
    @if(!empty($recentTPs))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-history me-2 text-primary"></i>
                            Mes Derniers TP
                        </h5>
                        <a href="{{ route('design-graphique.tp.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i>
                            Voir tous
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($recentTPs as $tp)
                        <div class="col-md-6 col-lg-4">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="d-flex align-items-start mb-2">
                                    <div class="flex-shrink-0">
                                        <div class="avatar avatar-sm bg-primary bg-opacity-10 text-primary rounded">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h6 class="mb-1 fw-semibold">{{ $tp['title'] }}</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ date('d/m/Y', strtotime($tp['created_at'])) }}
                                        </small>
                                    </div>
                                </div>
                                @if($tp['description'])
                                    <p class="small text-muted mb-2">{{ Str::limit($tp['description'], 80) }}</p>
                                @endif
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-paperclip me-1"></i>
                                        {{ $tp['files_count'] }} fichier(s)
                                    </span>
                                    @if($tp['link'])
                                        <a href="{{ $tp['link'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif


    <!-- Reste du contenu existant -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        Informations Importantes
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0 mb-0">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-lightbulb fs-4 text-info"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="alert-heading mb-2">Conseils pour réussir</h6>
                                <ul class="mb-0 ps-3">
                                    <li>Il faut participer toutes les activités et formations</li>
                                    <li>Publier régulièrement vos TP  validés pour suivre votre progression</li>
                                    <li>Utilisez des formats d'image de qualité (PNG, JPG)</li>
                                    <li>N'hésitez pas à joindre des documents PDF explicatifs</li>
                                    <li>Consultez le programme pour ne manquer aucun cours</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <!-- Section d'actions rapides -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2 text-warning"></i>
                        Actions Rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('design-graphique.tp.ajouter') }}" class="btn btn-primary w-100 h-100 d-flex flex-column justify-content-center align-items-center py-3">
                                <i class="fas fa-plus-circle fs-3 mb-2"></i>
                                <span class="fw-semibold">Ajouter un TP</span>
                                <small class="opacity-75">Nouveau travail pratique</small>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('design-graphique.tp.index') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column justify-content-center align-items-center py-3">
                                <i class="fas fa-list fs-3 mb-2"></i>
                                <span class="fw-semibold">Mes TP</span>
                                <small class="opacity-75">Consulter mes travaux</small>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('design-graphique.profil.editer') }}" class="btn btn-outline-secondary w-100 h-100 d-flex flex-column justify-content-center align-items-center py-3">
                                <i class="fas fa-user-edit fs-3 mb-2"></i>
                                <span class="fw-semibold">Mon Profil</span>
                                <small class="opacity-75">Modifier mes informations</small>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('design-graphique.programme.index') }}" class="btn btn-outline-info w-100 h-100 d-flex flex-column justify-content-center align-items-center py-3">
                                <i class="fas fa-calendar-alt fs-3 mb-2"></i>
                                <span class="fw-semibold">Programme</span>
                                <small class="opacity-75">Planning des cours</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Section certification (si applicable) -->
    @if(($tpStats['total_tp'] ?? 0) >= 10)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-trophy fs-1 text-success"></i>
                    </div>
                    <h5 class="text-success mb-2">Félicitations !</h5>
                    <p class="mb-3">Vous avez réalisé {{ $tpStats['total_tp'] }} TP. Vous êtes éligible pour la certification !</p>
                    <a href="{{ route('design-graphique.fin-formation.index') }}" class="btn btn-success">
                        <i class="fas fa-certificate me-2"></i>
                        Voir les critères de certification
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Ancienne section statistiques remplacée -->
    <div class="d-none">
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-info bg-opacity-10 text-info rounded">
                                <i class="fas fa-certificate fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-5">
                                @if(isset($stats['eligible_certificat']) && $stats['eligible_certificat'])
                                    <span class="text-success">Éligible</span>
                                @else
                                    <span class="text-warning">En cours</span>
                                @endif
                            </div>
                            <div class="text-muted small">Certification</div>
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
                        <i class="fab fa-adobe text-danger me-2"></i>
                        Modules de la formation
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #001E36 0%, #31A8FF 100%);">
                                <i class="fab fa-adobe fs-1 text-white mb-2"></i>
                                <h6 class="text-white mb-1">Photoshop</h6>
                                <small class="text-white opacity-75">Retouche & Montage</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-light" style="width: 85%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #330000 0%, #FF9A00 100%);">
                                <i class="fab fa-adobe fs-1 text-white mb-2"></i>
                                <h6 class="text-white mb-1">Illustrator</h6>
                                <small class="text-white opacity-75">Création Vectorielle</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-light" style="width: 72%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #49021F 0%, #FF3366 100%);">
                                <i class="fab fa-adobe fs-1 text-white mb-2"></i>
                                <h6 class="text-white mb-1">InDesign</h6>
                                <small class="text-white opacity-75">Mise en Page</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-light" style="width: 60%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #003366 0%, #3399ff 100%);">
                                <i class="fas fa-chart-line fs-1 text-white mb-2"></i>
                                <h6 class="text-white mb-1">Strategy Business</h6>
                                <small class="text-white opacity-75">Marketing Digital</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-light" style="width: 45%"></div>
                                </div>
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
                        <i class="fas fa-bolt text-primary me-2"></i>
                        Actions Rapides - Design Graphique
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-primary w-100 py-3">
                                <i class="fas fa-download d-block mb-2"></i>
                                <small>Logiciels Adobe</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-success w-100 py-3">
                                <i class="fas fa-palette d-block mb-2"></i>
                                <small>Mes Réalisations</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-warning w-100 py-3">
                                <i class="fas fa-video d-block mb-2"></i>
                                <small>Mes formations</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-info w-100 py-3">
                                <i class="fas fa-users d-block mb-2"></i>
                                <small>Mes classes</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-danger w-100 py-3">
                                <i class="fas fa-certificate d-block mb-2"></i>
                                <small>Mon Portfolio</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-secondary w-100 py-3">
                                <i class="fas fa-calendar d-block mb-2"></i>
                                <small>Planning</small>
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
