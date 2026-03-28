@extends('layouts.ki-admin')

@section('title', 'Espace Étudiant - Design & Community Management')

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
        background: linear-gradient(135deg, #2563eb 0%, #f97316 100%);
    }

    .stat-card.tp {
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
    }

    .stat-card.projets {
        background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%);
    }

    .stat-card.evenements {
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
        background: linear-gradient(135deg, #2563eb 0%, #f97316 100%);
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
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
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
        background: linear-gradient(135deg, #2563eb 0%, #f97316 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 5px 15px rgba(37, 99, 235, 0.4);
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
        background: linear-gradient(135deg, #2563eb 0%, #f97316 100%);
        border-radius: 2px;
    }

    .profile-header {
        background: linear-gradient(135deg, #2563eb 0%, #f97316 100%);
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

    .btn-edit {
        background: white;
        color: #1e40af;
        border: none;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        transition: all 0.3s;
    }

    .btn-edit:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(255,255,255,0.3);
        color: #1e40af;
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
        background: linear-gradient(135deg, #2563eb 0%, #f97316 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 5px 15px rgba(37, 99, 235, 0.4);
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
        background: linear-gradient(135deg, #2563eb 0%, #f97316 100%);
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

    .remaining-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.9rem;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.25);
        background: rgba(255,255,255,0.16);
        color: #fff;
        font-weight: 900;
        letter-spacing: -0.01em;
        box-shadow: 0 10px 22px rgba(0,0,0,0.14);
        backdrop-filter: blur(10px);
    }

    .remaining-pill--orange {
        background: linear-gradient(135deg, rgba(255,149,0,0.95) 0%, rgba(255,94,0,0.95) 100%);
        border-color: rgba(255,255,255,0.45);
        box-shadow: 0 14px 30px rgba(255, 149, 0, 0.25);
    }

    .remaining-pill--pulse {
        animation: remainingPulse 1.2s ease-in-out infinite;
    }

    .remaining-pill--pulse-urgent {
        animation: remainingPulseUrgent 0.85s ease-in-out infinite;
    }

    @keyframes remainingPulse {
        0%, 100% {
            transform: scale(1);
            filter: brightness(1);
            box-shadow: 0 14px 30px rgba(255, 149, 0, 0.25);
        }
        50% {
            transform: scale(1.05);
            filter: brightness(1.08);
            box-shadow: 0 20px 40px rgba(255, 149, 0, 0.38);
        }
    }

    @keyframes remainingPulseUrgent {
        0%, 100% {
            transform: scale(1);
            filter: brightness(1.02);
            box-shadow: 0 18px 42px rgba(255, 94, 0, 0.42);
        }
        50% {
            transform: scale(1.08);
            filter: brightness(1.15);
            box-shadow: 0 26px 60px rgba(255, 94, 0, 0.55);
        }
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

    $expirationDateSafe = $expirationDate ?? null;
    if (!($expirationDateSafe instanceof \Carbon\Carbon)) {
        try {
            $expirationDateSafe = \Carbon\Carbon::parse($expirationDateSafe);
        } catch (\Exception $e) {
            $expirationDateSafe = \Carbon\Carbon::now();
        }
    }

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

    // Normalisation du nom du programme pour l'affichage
    $programKey = strtolower(trim((string) $program));
    if (in_array($programKey, [
        'design_graphique_community_management',
        'design_graphique_community_manager',
        'design-graphique-community-manager',
        'design graphique & community management',
        'design graphique & community manager',
    ], true)) {
        $program = 'Design Graphique & Community Management';
    }

    $level = ($sf->level ?? '') ?: ($pr->level ?? '');
    $studentId = $sf->student_id ?? '';
@endphp

<div class="container-fluid px-lg-4">

    {{-- Profile Header --}}
    <div class="profile-header fade-in-up">
        <div class="row align-items-center position-relative" style="z-index: 1;">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-4 flex-column flex-lg-row text-center text-lg-start">
                    <img src="{{ $photoUrl }}" alt="{{ $fullName }}" class="avatar-pro"
                         onerror="this.src='{{ asset('assets/img/avatar.png') }}'">
                    <div>
                        <h1 class="text-white mb-2" style="font-size: 2rem; font-weight: 700;">
                            {{ $fullName ?: 'Étudiant EVC' }}
                        </h1>
                        <p class="text-white mb-3" style="opacity: 0.9; font-size: 1.125rem;">
                            {{ $program ?: 'Design Graphique & Community Management' }}
                            @if($level)
                                <span class="mx-2">•</span>{{ $level }}
                            @endif
                        </p>
                        @if(!$isExpired)
                            <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start">
                                <span class="remaining-pill" id="js-remaining-pill">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $daysRemaining }} jours restants</span>
                                </span>
                            </div>
                        @endif
                        <div class="d-flex gap-2 flex-wrap justify-content-center justify-content-lg-start">
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
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center text-lg-end mt-4 mt-lg-0">
                <a href="{{ route('design-graphique-cm.profil.editer') }}" class="btn btn-edit">
                    <i class="fas fa-edit me-2"></i>
                    Modifier mon profil
                </a>
            </div>
        </div>
    </div>

    @include('dashboard._communique-design')

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
                    <div class="stat-number mb-2" id="dg_formations_disponibles">{{ $stats['formations_disponibles'] ?? 0 }}</div>
                    <div class="stat-label mb-3">Formations Disponibles</div>
                    <a href="#" class="btn stat-btn w-100">
                        <i class="fas fa-arrow-right me-2"></i>
                        Explorer
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.2s;">
            <div class="stat-card tp h-100">
                <i class="fas fa-tasks stat-icon"></i>
                <div class="card-body p-4">
                    <div class="stat-number mb-2">
                        <span id="dg_tp_realises">{{ $stats['tp_realises'] ?? 0 }}</span><span style="font-size: 1.5rem; opacity: 0.7;">/<span id="dg_tp_total">{{ $stats['tp_total'] ?? 0 }}</span></span>
                    </div>
                    <div class="stat-label mb-2">Travaux Pratiques</div>
                    @if(($stats['tp_total'] ?? 0) > 0)
                    <div class="progress-bar-custom mb-3">
                        <div class="progress-bar-fill" id="dg_tp_progress_fill" style="width: {{ round(($stats['tp_realises'] / $stats['tp_total']) * 100) }}%;"></div>
                    </div>
                    <small class="text-white" id="dg_tp_progress_text" style="opacity: 0.8;">{{ round(($stats['tp_realises'] / $stats['tp_total']) * 100) }}% complétés</small>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.3s;">
            <div class="stat-card projets h-100">
                <i class="fas fa-project-diagram stat-icon"></i>
                <div class="card-body p-4">
                    <div class="stat-number mb-2">
                        <span id="dg_projets_realises">{{ $stats['projets_realises'] ?? 0 }}</span><span style="font-size: 1.5rem; opacity: 0.7;">/<span id="dg_projets_total">{{ $stats['projets_total'] ?? 0 }}</span></span>
                    </div>
                    <div class="stat-label mb-2">Projets Réalisés</div>
                    @if(($stats['projets_total'] ?? 0) > 0)
                    <div class="progress-bar-custom mb-3">
                        <div class="progress-bar-fill" id="dg_projets_progress_fill" style="width: {{ round(($stats['projets_realises'] / $stats['projets_total']) * 100) }}%;"></div>
                    </div>
                    <small class="text-white" id="dg_projets_progress_text" style="opacity: 0.8;">{{ round(($stats['projets_realises'] / $stats['projets_total']) * 100) }}% réalisés</small>
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
                            <div class="stat-number" id="dg_webinaires_en_cours" style="font-size: 2rem;">{{ $stats['webinaires_en_cours'] ?? 0 }}</div>
                            <small style="opacity: 0.8;">Webinaires</small>
                        </div>
                        <div class="col-6" style="border-left: 1px solid rgba(255,255,255,0.3);">
                            <div class="stat-number" id="dg_actualites_en_cours" style="font-size: 2rem;">{{ $stats['actualites_en_cours'] ?? 0 }}</div>
                            <small style="opacity: 0.8;">Actualités</small>
                        </div>
                    </div>
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
                        <div style="font-size: 3rem; font-weight: 900; color: white;"><span id="js-remaining-days">{{ $daysRemaining }}</span>j</div>
                        <div style="color: white; opacity: 0.95; font-weight: 800;" id="js-remaining-hms">--h --m --s</div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="text-white small" style="opacity: 0.8; text-transform: uppercase; font-weight: 600;">Expire le</div>
                        <div class="text-white" style="font-size: 1.5rem; font-weight: 700;">{{ $expirationDateSafe->format('d/m/Y') }}</div>
                        <div class="text-white" style="opacity: 0.8;">{{ $expirationDateSafe->format('H:i') }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-white small mb-2" style="opacity: 0.85; text-transform: uppercase; font-weight: 700;">Temps restant</div>
                        <div class="text-white" style="font-weight: 800; font-size: 1.15rem;" id="js-remaining-full">{{ $daysRemaining }} jours</div>
                        <div class="text-white" style="opacity: 0.85; font-weight: 600;" id="js-remaining-message">Accélérez maintenant pour finir avant la deadline</div>
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
            <a href="{{ route('design-graphique-cm.profil.editer') }}" class="action-card text-decoration-none">
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
            <a href="{{ route('design-graphique-cm.documents.index') }}" class="action-card text-decoration-none">
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
            <a href="{{ route('design-graphique-cm.parametres.index') }}" class="action-card text-decoration-none">
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

    {{-- Projets à faire --}}
    @if(!$isExpired && count($projetsAFaire ?? []) > 0)
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="section-title">
                <i class="fas fa-tasks me-2"></i>
                Projets à Faire
            </h2>
        </div>
    </div>

    <div class="row g-4 mb-5">
        @foreach($projetsAFaire as $projet)
        <div class="col-lg-4 col-md-6 fade-in-up">
            <div class="card h-100" style="border: none; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.08); transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div style="width: 50px; height: 50px; border-radius: 10px; background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-project-diagram text-white fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1" style="color: #2d3748; font-weight: 700; font-size: 1.1rem;">{{ $projet->title }}</h5>
                            @if($projet->category)
                            <span class="badge" style="background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); font-size: 0.75rem;">
                                {{ $projet->category }}
                            </span>
                            @endif
                        </div>
                    </div>

                    @if($projet->description)
                    <p class="text-muted small mb-3" style="line-height: 1.6;">
                        @php
                            $desc = html_entity_decode((string) $projet->description);
                            $desc = preg_replace('/\s+/', ' ', strip_tags($desc));
                        @endphp
                        {{ Str::limit(trim((string) $desc), 100) }}
                    </p>
                    @endif

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            @if($projet->status === 'en_cours')
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-spinner me-1"></i>En cours
                            </span>
                            @elseif($projet->status === 'termine')
                            <span class="badge bg-info">
                                <i class="fas fa-check-circle me-1"></i>Terminé
                            </span>
                            @else
                            <span class="badge bg-secondary">
                                <i class="fas fa-clock me-1"></i>À faire
                            </span>
                            @endif
                        </div>
                        @if($projet->created_at)
                        <small class="text-muted">
                            <i class="fas fa-calendar-alt me-1"></i>
                            {{ \Carbon\Carbon::parse($projet->created_at)->format('d/m/Y') }}
                        </small>
                        @endif
                    </div>

                    <a href="{{ route('design-graphique-cm.todo.traiter', $projet->id) }}" class="btn btn-sm w-100" style="background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); border: none; color: white; font-weight: 600; transition: all 0.3s;">
                        <i class="fas fa-arrow-right me-2"></i>Travailler sur ce projet
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

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
                                            <div style="font-weight: 700; color: #2d3748;">{{ $program ?: 'Design Graphique & Community Management' }}</div>
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
                                    <div style="font-size: 3.5rem; font-weight: 900; background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1;">
                                        <span id="dg_global_progress">{{ round($globalProgress) }}</span>%
                                    </div>
                                    <div class="text-muted mb-3">De votre formation complétée</div>
                                    <div class="progress-bar-custom mx-auto" style="max-width: 400px; background: #e5e7eb;">
                                        <div id="dg_global_progress_fill" style="height: 100%; border-radius: 10px; background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); width: {{ $globalProgress }}%; transition: width 0.6s ease;"></div>
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

@section('scripts')
<script>
    (function() {
        const statsUrl = @json(route('dashboard.design-graphique-cm.stats'));
        const expirationIso = @json($expirationDateSafe->toIso8601String());

        const el = {
            formations: document.getElementById('dg_formations_disponibles'),
            tpRealises: document.getElementById('dg_tp_realises'),
            tpTotal: document.getElementById('dg_tp_total'),
            tpProgressFill: document.getElementById('dg_tp_progress_fill'),
            tpProgressText: document.getElementById('dg_tp_progress_text'),
            projetsRealises: document.getElementById('dg_projets_realises'),
            projetsTotal: document.getElementById('dg_projets_total'),
            projetsProgressFill: document.getElementById('dg_projets_progress_fill'),
            projetsProgressText: document.getElementById('dg_projets_progress_text'),
            webinaires: document.getElementById('dg_webinaires_en_cours'),
            actualites: document.getElementById('dg_actualites_en_cours'),
            globalProgress: document.getElementById('dg_global_progress'),
            globalProgressFill: document.getElementById('dg_global_progress_fill'),
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

        async function refreshStats() {
            try {
                const res = await fetch(statsUrl, {
                    headers: {
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                if (!res.ok) return;

                const data = await res.json();
                if (!data || !data.stats) return;

                const stats = data.stats;
                const tpRealises = safeInt(stats.tp_realises);
                const tpTotal = safeInt(stats.tp_total);
                const projetsRealises = safeInt(stats.projets_realises);
                const projetsTotal = safeInt(stats.projets_total);

                setText(el.formations, safeInt(stats.formations_disponibles));
                setText(el.tpRealises, tpRealises);
                setText(el.tpTotal, tpTotal);
                setText(el.projetsRealises, projetsRealises);
                setText(el.projetsTotal, projetsTotal);
                setText(el.webinaires, safeInt(stats.webinaires_en_cours));
                setText(el.actualites, safeInt(stats.actualites_en_cours));

                if (tpTotal > 0) {
                    const tpPct = Math.round((tpRealises / tpTotal) * 100);
                    setWidth(el.tpProgressFill, tpPct);
                    setText(el.tpProgressText, tpPct + '% complétés');
                }

                if (projetsTotal > 0) {
                    const projetsPct = Math.round((projetsRealises / projetsTotal) * 100);
                    setWidth(el.projetsProgressFill, projetsPct);
                    setText(el.projetsProgressText, projetsPct + '% réalisés');
                }

                if (typeof data.global_progress !== 'undefined') {
                    const gp = clampPercent(data.global_progress);
                    setText(el.globalProgress, Math.round(gp));
                    setWidth(el.globalProgressFill, gp);
                }
            } catch (e) {
                // silent
            }
        }

        refreshStats();
        window.setInterval(refreshStats, 30000);

        function pad2(n) {
            return String(n).padStart(2, '0');
        }

        function updateRemaining() {
            const exp = new Date(expirationIso);
            if (Number.isNaN(exp.getTime())) return;

            const now = new Date();
            let diff = exp.getTime() - now.getTime();
            if (diff < 0) diff = 0;

            const totalSeconds = Math.floor(diff / 1000);
            const days = Math.floor(totalSeconds / 86400);
            const hours = Math.floor((totalSeconds % 86400) / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;

            const pillEl = document.getElementById('js-remaining-pill');
            const daysEl = document.getElementById('js-remaining-days');
            const hmsEl = document.getElementById('js-remaining-hms');
            const fullEl = document.getElementById('js-remaining-full');
            const msgEl = document.getElementById('js-remaining-message');

            if (pillEl) {
                const pillTextNode = pillEl.querySelector('span');
                if (pillTextNode) {
                    pillTextNode.textContent = `${days} jours restants`;
                }
                pillEl.classList.remove('remaining-pill--orange', 'remaining-pill--pulse', 'remaining-pill--pulse-urgent');
                if (totalSeconds > 0) {
                    pillEl.classList.add('remaining-pill--orange');
                    if (days <= 7) {
                        pillEl.classList.add('remaining-pill--pulse-urgent');
                    } else {
                        pillEl.classList.add('remaining-pill--pulse');
                    }
                }
            }
            if (daysEl) daysEl.textContent = `${days}`;
            if (hmsEl) hmsEl.textContent = `${pad2(hours)}h ${pad2(minutes)}m ${pad2(seconds)}s`;
            if (fullEl) fullEl.textContent = `${days} jours • ${pad2(hours)}:${pad2(minutes)}:${pad2(seconds)}`;
            if (msgEl) {
                if (days <= 7) {
                    msgEl.textContent = `Dernière ligne droite: terminez vos TP aujourd'hui`;
                } else if (days <= 30) {
                    msgEl.textContent = `Objectif: valider 2 TP/semaine jusqu'à la fin`;
                } else {
                    msgEl.textContent = `Accélérez maintenant pour finir avant la deadline`;
                }
            }
        }

        updateRemaining();
        window.setInterval(updateRemaining, 1000);
    })();
</script>
@endsection

@endsection
