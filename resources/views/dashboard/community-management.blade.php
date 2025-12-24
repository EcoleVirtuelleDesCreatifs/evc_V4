@extends('layouts.ki-admin')

@section('title', 'Espace Étudiant - Community Management')

@section('content')
<style>
    /* Style from formations page */
    .stat-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        color: white;
        position: relative;
    }

    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    }

    .stat-card.formations {
        background: linear-gradient(135deg, #833AB4 0%, #FD1D1D 50%, #FCAF45 100%);
    }

    .stat-card.tp {
        background: linear-gradient(135deg, #833AB4 0%, #E1306C 55%, #FCAF45 100%);
    }

    .stat-card.projets {
        background: linear-gradient(135deg, #515BD4 0%, #C13584 55%, #FCAF45 100%);
    }

    .stat-card.evenements {
        background: linear-gradient(135deg, #833AB4 0%, #FD1D1D 45%, #F77737 100%);
    }

    .stat-card.paiements {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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

    @keyframes pulse-glow {
        0%, 100% {
            box-shadow: 0 20px 60px rgba(131, 58, 180, 0.4);
            transform: scale(1);
        }
        50% {
            box-shadow: 0 25px 80px rgba(131, 58, 180, 0.6), 0 0 30px rgba(193, 53, 132, 0.4);
            transform: scale(1.02);
        }
    }

    .formation-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    .formation-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }

    .formation-header {
        background: linear-gradient(135deg, #833AB4 0%, #FD1D1D 50%, #FCAF45 100%);
        color: white;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .formation-header::before {
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

    .info-item {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #fff1f7 0%, #fff7ed 100%);
        position: relative;
    }

    .info-item:hover {
        transform: scale(1.02);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .info-icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: linear-gradient(135deg, #833AB4 0%, #E1306C 55%, #FCAF45 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 5px 15px rgba(225, 48, 108, 0.35);
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: #fff;
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
        background: linear-gradient(135deg, #833AB4 0%, #E1306C 55%, #FCAF45 100%);
        border-radius: 2px;
    }

    .profile-header {
        background: linear-gradient(135deg, #833AB4 0%, #C13584 40%, #E1306C 70%, #FCAF45 100%);
        border-radius: 20px;
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 3s ease-in-out infinite;
    }

    .avatar-pro {
        width: 100px;
        height: 100px;
        border-radius: 15px;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    .badge-contact {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 25px;
        font-size: 0.875rem;
        font-weight: 500;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s;
        text-decoration: none;
        color: white;
    }

    .badge-contact:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
        color: white;
    }

    .hero-subcard {
        background: rgba(255,255,255,0.14);
        border: 1px solid rgba(255,255,255,0.18);
        border-radius: 16px;
        padding: 1.25rem;
        backdrop-filter: blur(10px);
    }

    .hero-kpi {
        font-size: 2.2rem;
        font-weight: 900;
        line-height: 1;
        color: #fff;
    }

    .hero-kpi-label {
        text-transform: uppercase;
        letter-spacing: 0.12em;
        font-size: 0.72rem;
        font-weight: 800;
        opacity: 0.95;
        color: rgba(255,255,255,0.92);
    }

    .hero-cta {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.85rem 1.1rem;
        border-radius: 14px;
        font-weight: 800;
        text-decoration: none;
        transition: all 0.25s ease;
        border: 1px solid rgba(255,255,255,0.22);
        background: rgba(255,255,255,0.16);
        color: #fff;
    }

    .hero-cta:hover {
        transform: translateY(-2px);
        background: rgba(255,255,255,0.22);
        color: #fff;
    }

    .hero-cta.primary {
        background: rgba(255,255,255,0.92);
        color: #7c3aed;
        border-color: rgba(255,255,255,0.45);
    }

    .hero-cta.primary:hover {
        background: #fff;
        color: #7c3aed;
    }

    .btn-edit {
        background: white;
        color: #833AB4;
        border: none;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        transition: all 0.3s;
    }

    .btn-edit:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(255,255,255,0.3);
        color: #833AB4;
    }

    .action-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        position: relative;
        cursor: pointer;
        text-decoration: none;
        display: block;
        color: inherit;
    }

    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .action-icon-wrapper {
        width: 60px;
        height: 60px;
        margin: 0 auto 1rem;
        border-radius: 12px;
        background: linear-gradient(135deg, #833AB4 0%, #E1306C 55%, #FCAF45 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 5px 15px rgba(225, 48, 108, 0.35);
        transition: all 0.3s;
    }

    .action-card:hover .action-icon-wrapper {
        transform: scale(1.1) rotate(5deg);
    }

    .action-title {
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .action-subtitle {
        font-size: 0.875rem;
        color: #718096;
    }

    .progress-bar-custom {
        height: 8px;
        border-radius: 10px;
        background: rgba(255,255,255,0.3);
    }

    .progress-bar-fill {
        height: 100%;
        border-radius: 10px;
        background: white;
        transition: width 0.6s ease;
    }

    .countdown-card {
        background: linear-gradient(135deg, #833AB4 0%, #C13584 40%, #E1306C 70%, #FCAF45 100%);
        border-radius: 20px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        margin-bottom: 2rem;
    }

    .countdown-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
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

    @media (max-width: 768px) {
        .stat-number {
            font-size: 2.5rem;
        }

        .profile-header {
            padding: 1.5rem;
        }

        .avatar-pro {
            width: 80px;
            height: 80px;
        }
    }
</style>

@php
    $sf = optional($student ?? null);
    $pr = optional($preReg ?? null);
    $userObj = isset($user) ? $user : (auth()->check() ? auth()->user() : null);

    $studentPhoto = $sf->profile_photo;
    $prePhoto = $pr->profile_photo ?? $pr->photo ?? $pr->image ?? $pr->image_url ?? $pr->avatar;
    $rawPhoto = $studentPhoto ?: $prePhoto;

    if ($rawPhoto) {
        if (preg_match('/^https?:\/\//', $rawPhoto)) {
            $photoUrl = $rawPhoto;
        } elseif (str_starts_with($rawPhoto, 'photos_preregistrations/')) {
            $photoUrl = \App\Models\MediaUrl::fromPath($rawPhoto);
        } elseif (str_starts_with($rawPhoto, 'uploads/')) {
            $photoUrl = asset($rawPhoto);
        } else {
            $photoUrl = \App\Models\MediaUrl::fromPath($rawPhoto);
        }
    } else {
        $photoUrl = asset('assets/img/avatar.png');
    }

    $fullName = trim(($sf->first_name ?? '') . ' ' . ($sf->last_name ?? ''));
    if ($fullName === '') {
        $fullName = ($userObj->name ?? '') ?: trim(($pr->first_name ?? '') . ' ' . ($pr->last_name ?? ''));
    }

    $email = ($sf->email ?? '') ?: (($userObj->email ?? '') ?: ($pr->email ?? ''));
    $phone = ($sf->phone ?? '') ?: ($pr->phone ?? '');
    $program = ($sf->program ?? '') ?: ($pr->program ?? '');
    $level = ($sf->level ?? '') ?: ($pr->level ?? '');
    $studentId = $sf->student_id ?? '';
@endphp

<div class="container-fluid px-lg-4">

    {{-- Profile Header --}}
    <div class="profile-header fade-in-up">
        <div class="row align-items-center position-relative" style="z-index: 1;">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-4 flex-column flex-lg-row text-center text-lg-start">
                    <img src="{{ $photoUrl }}" alt="{{ $fullName }}" class="avatar-pro"
                         onerror="this.src='{{ asset('assets/img/avatar.png') }}'">
                    <div class="flex-grow-1">
                        <div class="d-flex gap-2 flex-wrap justify-content-center justify-content-lg-start mb-2">
                            <a class="badge-contact" href="{{ route('community-management.programme.index') }}">
                                <i class="fas fa-bolt"></i>
                                <span>Objectif : publier chaque semaine</span>
                            </a>
                        </div>

                        <h1 class="text-white mb-2" style="font-size: 2.05rem; font-weight: 900; letter-spacing: -0.02em;">
                            {{ $fullName ?: 'Étudiant EVC' }}
                        </h1>
                        <p class="text-white mb-3" style="opacity: 0.9; font-size: 1.05rem;">
                            {{ $program ?: 'Community Management' }}
                            @if($level)
                                <span class="mx-2">•</span>{{ $level }}
                            @endif
                        </p>

                        <div class="d-flex gap-2 flex-wrap justify-content-center justify-content-lg-start mb-3">
                            @if($email)
                            <a href="mailto:{{ $email }}" class="badge-contact">
                                <i class="fas fa-envelope"></i>
                                <span>{{ $email }}</span>
                            </a>
                            @endif
                            @if($phone)
                            <a href="tel:{{ $phone }}" class="badge-contact">
                                <i class="fas fa-phone"></i>
                                <span>{{ $phone }}</span>
                            </a>
                            @endif
                        </div>

                        <div class="d-flex gap-2 flex-wrap justify-content-center justify-content-lg-start">
                            <a href="{{ route('community-management.tp.index') }}" class="hero-cta primary">
                                <i class="fas fa-play"></i>
                                Continuer maintenant
                            </a>
                            <a href="{{ route('community-management.projets.index') }}" class="hero-cta">
                                <i class="fas fa-project-diagram"></i>
                                Mes projets
                            </a>
                            <a href="{{ route('community-management.paiements.index') }}" class="hero-cta">
                                <i class="fas fa-wallet"></i>
                                Paiements
                            </a>
                            <a href="{{ route('community-management.cvtheque.mon-profil') }}" class="hero-cta">
                                <i class="fas fa-briefcase"></i>
                                CVthèque
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 mt-4 mt-lg-0">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="hero-subcard">
                            <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                                <div>
                                    <div class="hero-kpi-label mb-1">Progression globale</div>
                                    <div class="hero-kpi"><span id="cm_progression_globale">{{ (int)($stats['progression_globale'] ?? 0) }}</span>%</div>
                                    <div class="text-white" style="opacity: 0.9; font-weight: 700;">
                                        TP : <span id="cm_tp_realises">{{ $stats['tp_realises'] ?? 0 }}</span>/<span id="cm_tp_total">{{ $stats['tp_total'] ?? 0 }}</span>
                                        <span class="mx-2">•</span>
                                        Projets : <span id="cm_projets_realises">{{ $stats['projets_realises'] ?? 0 }}</span>/<span id="cm_projets_total">{{ $stats['projets_total'] ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="hero-kpi-label mb-1">Reste à payer</div>
                                    <div class="hero-kpi" style="font-size: 1.6rem;">
                                        <span id="cm_montant_restant">{{ number_format((float)($stats['montant_restant'] ?? 0), 0, ',', ' ') }}</span> FCFA
                                    </div>
                                    <div class="text-white" style="opacity: 0.9; font-weight: 700;">
                                        @if(((float)($stats['montant_restant'] ?? 0)) > 0)
                                            Finalisez pour sécuriser votre accès
                                        @else
                                            Paiement soldé
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3" style="background: rgba(255,255,255,0.12); border-radius: 999px; height: 10px; overflow: hidden;">
                                <div id="cm_progression_globale_fill" style="height: 100%; width: {{ (int)($stats['progression_globale'] ?? 0) }}%; background: rgba(255,255,255,0.92);"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <a href="{{ route('community-management.todo.index') }}" class="hero-subcard" style="display:block; text-decoration:none;">
                            <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
                                <div>
                                    <div class="hero-kpi-label mb-1">Priorité du jour</div>
                                    <div class="text-white" style="font-weight: 900;">
                                        Traiter un projet assigné
                                    </div>
                                    <div class="text-white" style="opacity: 0.9; font-weight: 700;">
                                        Accélérez votre progression en validant vos livrables
                                    </div>
                                </div>
                                <span class="hero-cta primary" style="margin:0;">
                                    <i class="fas fa-arrow-right"></i>
                                    Ouvrir
                                </span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="section-title">
                <i class="fas fa-chart-bar me-2"></i>
                Votre Tableau de Bord
            </h2>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.1s;">
            <div class="stat-card formations h-100">
                <i class="fas fa-graduation-cap stat-icon"></i>
                <div class="card-body p-4">
                    <div class="stat-number mb-2" id="cm_formations_disponibles">{{ $stats['formations_disponibles'] ?? 0 }}</div>
                    <div class="stat-label mb-3">Formations</div>
                    <a href="{{ route('community-management.formations.index') }}" class="btn stat-btn w-100">
                        <i class="fas fa-arrow-right me-2"></i>
                        Explorer
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 mb-4 fade-in-up" style="animation-delay: 0.15s;">
            <div class="stat-card projets h-100" style="box-shadow: 0 20px 60px rgba(131, 58, 180, 0.4); border: 3px solid rgba(255, 255, 255, 0.3); animation: pulse-glow 2s ease-in-out infinite;">
                <i class="fas fa-folder-open" style="position: absolute; right: 30px; top: 30px; font-size: 4rem; opacity: 0.2;"></i>
                <div class="card-body p-5">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-badge" style="width: 80px; height: 80px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-right: 1.5rem;">
                            <i class="fas fa-folder-open" style="font-size: 2.5rem; color: white;"></i>
                        </div>
                        <div>
                            <div class="stat-number" style="font-size: 4rem; line-height: 1;" id="cm_projets_a_faire">{{ $stats['projets_a_faire'] ?? 0 }}</div>
                            <div class="stat-label" style="font-size: 1.2rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">Projets À Faire</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 mt-3">
                        <i class="fas fa-info-circle" style="font-size: 1.2rem;"></i>
                        <span style="font-size: 1rem; opacity: 0.95;">Projets assignés par le formateur principal</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.2s;">
            <div class="stat-card tp h-100">
                <i class="fas fa-tasks stat-icon"></i>
                <div class="card-body p-4">
                    <div class="stat-number mb-2">
                        <span id="cm_tp_realises_card">{{ $stats['tp_realises'] ?? 0 }}</span><span style="font-size: 1.5rem; opacity: 0.7;">/<span id="cm_tp_total_card">{{ $stats['tp_total'] ?? 0 }}</span></span>
                    </div>
                    <div class="stat-label mb-2">Travaux Pratiques</div>
                    @if(($stats['tp_total'] ?? 0) > 0)
                    <div class="progress-bar-custom mb-3">
                        <div class="progress-bar-fill" id="cm_tp_progress_fill" style="width: {{ round(($stats['tp_realises'] / $stats['tp_total']) * 100) }}%;"></div>
                    </div>
                    <small class="text-white" id="cm_tp_progress_text" style="opacity: 0.8;">{{ round(($stats['tp_realises'] / $stats['tp_total']) * 100) }}% complétés</small>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.3s;">
            <div class="stat-card projets h-100">
                <i class="fas fa-project-diagram stat-icon"></i>
                <div class="card-body p-4">
                    <div class="stat-number mb-2">
                        <span id="cm_projets_realises_card">{{ $stats['projets_realises'] ?? 0 }}</span><span style="font-size: 1.5rem; opacity: 0.7;">/<span id="cm_projets_total_card">{{ $stats['projets_total'] ?? 0 }}</span></span>
                    </div>
                    <div class="stat-label mb-2">Projets Réalisés</div>
                    @if(($stats['projets_total'] ?? 0) > 0)
                    <div class="progress-bar-custom mb-3">
                        <div class="progress-bar-fill" id="cm_projets_progress_fill" style="width: {{ round(($stats['projets_realises'] / $stats['projets_total']) * 100) }}%;"></div>
                    </div>
                    <small class="text-white" id="cm_projets_progress_text" style="opacity: 0.8;">{{ round(($stats['projets_realises'] / $stats['projets_total']) * 100) }}% réalisés</small>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.4s;">
            <div class="stat-card evenements h-100">
                <i class="fas fa-calendar-alt stat-icon"></i>
                <div class="card-body p-4">
                    <div class="stat-label mb-3">Événements</div>
                    <div class="row g-0 text-center">
                        <div class="col-6">
                            <div class="stat-number" id="cm_evenements" style="font-size: 2rem;">{{ $stats['evenements'] ?? 0 }}</div>
                            <small style="opacity: 0.8;">Événements</small>
                        </div>
                        <div class="col-6" style="border-left: 1px solid rgba(255,255,255,0.3);">
                            <div class="stat-number" id="cm_actualites" style="font-size: 2rem;">{{ $stats['actualites'] ?? 0 }}</div>
                            <small style="opacity: 0.8;">Actualités</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.5s;">
            <div class="stat-card paiements h-100">
                <i class="fas fa-wallet stat-icon"></i>
                <div class="card-body p-4">
                    <div class="stat-number mb-2" style="font-size: 2.2rem;">
                        <span id="cm_montant_restant_card">{{ number_format((float)($stats['montant_restant'] ?? 0), 0, ',', ' ') }}</span>
                    </div>
                    <div class="stat-label mb-3">Montant restant (FCFA)</div>
                    <a href="{{ route('community-management.paiements.index') }}" class="btn stat-btn w-100">
                        <i class="fas fa-receipt me-2"></i>
                        Mes paiements
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Countdown Section --}}
    @if(!$isExpired)
    <div class="countdown-card fade-in-up mb-5">
        <div class="row align-items-center position-relative" style="z-index: 1;">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span style="font-size: 2.5rem;">⏰</span>
                    <div>
                        <h3 class="text-white mb-1" style="font-size: 1.75rem; font-weight: 700;">
                            Votre formation expire bientôt
                        </h3>
                        <p class="text-white mb-0" style="opacity: 0.9;">
                            Maximisez votre apprentissage avant la fin de votre accès
                        </p>
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-md-4 text-center text-lg-start">
                        <div style="font-size: 3rem; font-weight: 900; color: white;">{{ $daysRemaining }}</div>
                        <div style="color: white; opacity: 0.9; font-weight: 500;">jours restants</div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="text-white small" style="opacity: 0.8; text-transform: uppercase; font-weight: 600;">Expire le</div>
                        <div class="text-white" style="font-size: 1.5rem; font-weight: 700;">{{ $expirationDate->format('d/m/Y') }}</div>
                        <div class="text-white" style="opacity: 0.8;">{{ $expirationDate->format('H:i') }}</div>
                    </div>
                    <div class="col-md-4">
                        <?php $totalDays = 120; $progress = ($daysRemaining / $totalDays) * 100; ?>
                        <div class="text-white small mb-2" style="opacity: 0.8; text-transform: uppercase; font-weight: 600;">Progression</div>
                        <div class="progress-bar-custom">
                            <div class="progress-bar-fill" style="width: {{ $progress }}%;"></div>
                        </div>
                        <div class="text-white mt-2" style="font-weight: 600;">{{ round($progress) }}% de votre formation</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center mt-4 mt-lg-0">
                <div class="info-item p-3">
                    <div class="d-flex align-items-center gap-3">
                        <span style="font-size: 2rem;">💡</span>
                        <div class="text-start">
                            <strong style="color: #2d3748;">Conseil Pro</strong>
                            <p class="mb-0 small" style="color: #4a5568;">Complétez vos TP et projets pour valider vos compétences</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Bannière Compte Expiré --}}
    @if($isExpired)
    <div class="alert alert-danger border-0 shadow-lg mb-5 fade-in-up" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 16px;">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span style="font-size: 3rem;">⚠️</span>
                    <div>
                        <h3 class="text-white mb-1" style="font-size: 1.75rem; font-weight: 700;">
                            Votre compte a expiré
                        </h3>
                        <p class="text-white mb-0" style="opacity: 0.95; font-size: 1.1rem;">
                            Votre période de formation est terminée
                        </p>
                    </div>
                </div>

                <div class="alert alert-light mb-0" style="background: rgba(255,255,255,0.95); border-radius: 12px;">
                    <h5 class="mb-3" style="color: #dc2626; font-weight: 600;">
                        <i class="fas fa-info-circle me-2"></i>Restrictions d'accès
                    </h5>
                    <ul class="mb-0" style="color: #1f2937;">
                        <li class="mb-2"><strong>✅ Vous pouvez :</strong> Consulter vos cours, TP et projets déjà réalisés</li>
                        <li class="mb-2"><strong>❌ Vous ne pouvez plus :</strong>
                            <ul class="mt-2">
                                <li>Accéder à de nouvelles formations</li>
                                <li>Soumettre de nouveaux TP ou projets</li>
                                <li>Publier des rapports</li>
                                <li>Accéder à la bibliothèque de ressources</li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 text-center mt-4 mt-lg-0">
                <div class="p-4" style="background: rgba(255,255,255,0.15); border-radius: 12px; backdrop-filter: blur(10px);">
                    <div style="font-size: 2.5rem; color: white; margin-bottom: 1rem;">📞</div>
                    <h5 class="text-white mb-3" style="font-weight: 600;">Renouveler votre accès</h5>
                    <p class="text-white mb-3" style="opacity: 0.9; font-size: 0.95rem;">
                        Contactez l'administration pour prolonger votre formation
                    </p>
                    <a href="mailto:info@ecolevirtuelledescreatifs.com" class="btn btn-light btn-lg" style="font-weight: 600; border-radius: 10px;">
                        <i class="fas fa-envelope me-2"></i>Nous contacter
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Quick Actions --}}
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="section-title">
                <i class="fas fa-bolt me-2"></i>
                Actions Rapides
            </h2>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6 fade-in-up" style="animation-delay: 0.1s;">
            <a href="{{ route('community-management.profil.editer') }}" class="action-card text-decoration-none">
                <div class="card-body p-4 text-center">
                    <div class="action-icon-wrapper">
                        <i class="fas fa-user-edit fa-2x text-white"></i>
                    </div>
                    <h5 class="action-title">Modifier Profil</h5>
                    <p class="action-subtitle mb-0">Mettre à jour vos informations</p>
                </div>
            </a>
        </div>

        @if(!$isExpired)
        <div class="col-lg-3 col-md-6 fade-in-up" style="animation-delay: 0.2s;">
            <a href="{{ route('community-management.documents.index') }}" class="action-card text-decoration-none">
                <div class="card-body p-4 text-center">
                    <div class="action-icon-wrapper">
                        <i class="fas fa-folder-open fa-2x text-white"></i>
                    </div>
                    <h5 class="action-title">Documents</h5>
                    <p class="action-subtitle mb-0">CV, lettres, réalisations</p>
                </div>
            </a>
        </div>
        @else
        <div class="col-lg-3 col-md-6 fade-in-up" style="animation-delay: 0.2s;">
            <div class="action-card text-decoration-none" style="opacity: 0.6; cursor: not-allowed; position: relative;">
                <div class="card-body p-4 text-center">
                    <div class="action-icon-wrapper" style="background: #6b7280;">
                        <i class="fas fa-lock fa-2x text-white"></i>
                    </div>
                    <h5 class="action-title">Documents</h5>
                    <p class="action-subtitle mb-0 text-muted">Compte expiré</p>
                    <span class="badge bg-danger position-absolute top-0 end-0 m-2" style="font-size: 0.7rem;">
                        <i class="fas fa-ban me-1"></i>Bloqué
                    </span>
                </div>
            </div>
        </div>
        @endif

        <div class="col-lg-3 col-md-6 fade-in-up" style="animation-delay: 0.3s;">
            <a href="{{ route('community-management.parametres.index') }}" class="action-card text-decoration-none">
                <div class="card-body p-4 text-center">
                    <div class="action-icon-wrapper">
                        <i class="fas fa-cog fa-2x text-white"></i>
                    </div>
                    <h5 class="action-title">Paramètres</h5>
                    <p class="action-subtitle mb-0">Configuration du compte</p>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 fade-in-up" style="animation-delay: 0.4s;">
            <a href="#" class="action-card text-decoration-none">
                <div class="card-body p-4 text-center">
                    <div class="action-icon-wrapper">
                        <i class="fas fa-chart-line fa-2x text-white"></i>
                    </div>
                    <h5 class="action-title">Statistiques</h5>
                    <p class="action-subtitle mb-0">Suivi de progression</p>
                </div>
            </a>
        </div>
    </div>

    {{-- Profile Info --}}
    <div class="row">
        <div class="col-12">
            <div class="formation-card fade-in-up">
                <div class="formation-header">
                    <h3 class="mb-0 position-relative">
                        <i class="fas fa-info-circle me-3"></i>
                        Informations de Formation
                    </h3>
                    <p class="mb-0 mt-2 opacity-75">Détails de votre parcours académique</p>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-lg-3 col-md-6">
                            <div class="info-item h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="info-icon-wrapper flex-shrink-0">
                                            <i class="fas fa-book text-white"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted" style="text-transform: uppercase; font-weight: 600; font-size: 0.75rem;">Programme</div>
                                            <div style="font-weight: 700; color: #2d3748;">{{ $program ?: 'Design Graphique' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="info-item h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="info-icon-wrapper flex-shrink-0">
                                            <i class="fas fa-layer-group text-white"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted" style="text-transform: uppercase; font-weight: 600; font-size: 0.75rem;">Niveau</div>
                                            <div style="font-weight: 700; color: #2d3748;">{{ $level ?: 'Débutant' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="info-item h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="info-icon-wrapper flex-shrink-0">
                                            <i class="fas fa-id-card text-white"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted" style="text-transform: uppercase; font-weight: 600; font-size: 0.75rem;">Matricule</div>
                                            <div style="font-weight: 700; color: #2d3748;">{{ $studentId ?: 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="info-item h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="info-icon-wrapper flex-shrink-0">
                                            <i class="fas fa-check-circle text-white"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted" style="text-transform: uppercase; font-weight: 600; font-size: 0.75rem;">Statut</div>
                                            <div style="font-weight: 700; color: #10b981;">Actif</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <div class="info-item text-center">
                                <div class="card-body p-4">
                                    <i class="fas fa-trophy mb-3" style="font-size: 2.5rem; color: #f97316;"></i>
                                    @php
                                        $globalProgress = 0;
                                        $tpTotal = $stats['tp_total'] ?? 0;
                                        $projetsTotal = $stats['projets_total'] ?? 0;

                                        if($tpTotal > 0) {
                                            $globalProgress += (($stats['tp_realises'] ?? 0) / $tpTotal) * 50;
                                        }
                                        if($projetsTotal > 0) {
                                            $globalProgress += (($stats['projets_realises'] ?? 0) / $projetsTotal) * 50;
                                        }
                                    @endphp
                                    <div style="font-size: 3.5rem; font-weight: 900; background: linear-gradient(135deg, #833AB4 0%, #E1306C 55%, #FCAF45 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1;">
                                        {{ round($globalProgress) }}%
                                    </div>
                                    <div class="text-muted mb-3">De votre formation complétée</div>
                                    <div class="progress-bar-custom mx-auto" style="max-width: 400px; background: #e5e7eb;">
                                        <div style="height: 100%; border-radius: 10px; background: linear-gradient(135deg, #833AB4 0%, #E1306C 55%, #FCAF45 100%); width: {{ $globalProgress }}%; transition: width 0.6s ease;"></div>
                                    </div>
                                    <p class="mt-3 mb-0">
                                        <i class="fas fa-fire" style="color: #ef4444;"></i>
                                        Continuez comme ça, vous êtes sur la bonne voie !
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
    (function() {
        const statsUrl = @json(route('dashboard.community-management.stats'));

        const el = {
            progression: document.getElementById('cm_progression_globale'),
            progressionFill: document.getElementById('cm_progression_globale_fill'),
            tpRealises: document.getElementById('cm_tp_realises'),
            tpTotal: document.getElementById('cm_tp_total'),
            tpRealisesCard: document.getElementById('cm_tp_realises_card'),
            tpTotalCard: document.getElementById('cm_tp_total_card'),
            tpFill: document.getElementById('cm_tp_progress_fill'),
            tpText: document.getElementById('cm_tp_progress_text'),
            projetsRealises: document.getElementById('cm_projets_realises'),
            projetsTotal: document.getElementById('cm_projets_total'),
            projetsRealisesCard: document.getElementById('cm_projets_realises_card'),
            projetsTotalCard: document.getElementById('cm_projets_total_card'),
            projetsFill: document.getElementById('cm_projets_progress_fill'),
            projetsText: document.getElementById('cm_projets_progress_text'),
            projetsAFaire: document.getElementById('cm_projets_a_faire'),
            evenements: document.getElementById('cm_evenements'),
            actualites: document.getElementById('cm_actualites'),
            montantRestantHero: document.getElementById('cm_montant_restant'),
            montantRestantCard: document.getElementById('cm_montant_restant_card'),
        };

        function safeInt(v) {
            const n = parseInt(v, 10);
            return Number.isFinite(n) ? n : 0;
        }

        function clampPercent(v) {
            const n = Number(v);
            if (!Number.isFinite(n)) return 0;
            return Math.max(0, Math.min(100, n));
        }

        function setText(node, value) {
            if (!node) return;
            node.textContent = String(value);
        }

        function setWidth(node, percent) {
            if (!node) return;
            node.style.width = clampPercent(percent) + '%';
        }

        function formatFcfa(n) {
            const num = Number(n);
            if (!Number.isFinite(num)) return '0';
            return Math.round(num).toLocaleString('fr-FR');
        }

        async function refreshStats() {
            try {
                const res = await fetch(statsUrl, {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin'
                });
                if (!res.ok) return;
                const data = await res.json();
                if (!data || !data.stats) return;

                const s = data.stats;
                const tpRealises = safeInt(s.tp_realises);
                const tpTotal = safeInt(s.tp_total);
                const projetsRealises = safeInt(s.projets_realises);
                const projetsTotal = safeInt(s.projets_total);

                setText(el.progression, Math.round(clampPercent(s.progression_globale)));
                setWidth(el.progressionFill, s.progression_globale);

                setText(el.tpRealises, tpRealises);
                setText(el.tpTotal, tpTotal);
                setText(el.tpRealisesCard, tpRealises);
                setText(el.tpTotalCard, tpTotal);

                if (tpTotal > 0) {
                    const pct = Math.round((tpRealises / tpTotal) * 100);
                    setWidth(el.tpFill, pct);
                    setText(el.tpText, pct + '% complétés');
                }

                setText(el.projetsRealises, projetsRealises);
                setText(el.projetsTotal, projetsTotal);
                setText(el.projetsRealisesCard, projetsRealises);
                setText(el.projetsTotalCard, projetsTotal);

                if (projetsTotal > 0) {
                    const pct = Math.round((projetsRealises / projetsTotal) * 100);
                    setWidth(el.projetsFill, pct);
                    setText(el.projetsText, pct + '% réalisés');
                }

                setText(el.projetsAFaire, safeInt(s.projets_a_faire));
                setText(el.evenements, safeInt(s.evenements));
                setText(el.actualites, safeInt(s.actualites));

                if (typeof s.montant_restant !== 'undefined') {
                    const mr = Number(s.montant_restant);
                    setText(el.montantRestantHero, formatFcfa(mr));
                    setText(el.montantRestantCard, formatFcfa(mr));
                }
            } catch (e) {
                // silent
            }
        }

        refreshStats();
        window.setInterval(refreshStats, 30000);
    })();
</script>
@endsection
