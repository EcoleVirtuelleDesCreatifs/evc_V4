@extends('layouts.ki-admin')

@section('title', 'Espace Étudiant - Design Graphique')

@section('content')
<style>
    /* ========== EVC Dashboard - Institutional theme ========== */
    .dashboard-page {
        background: #f8fafc;
        min-height: 100vh;
        padding-top: 2rem;
        padding-bottom: 3rem;
    }

    .section-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 1.5rem;
        position: relative;
        padding-bottom: 0.75rem;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 4px;
        background: linear-gradient(135deg, #ff6b00 0%, #ff9d4d 100%);
        border-radius: 2px;
    }

    .profile-header {
        background: linear-gradient(135deg, #0a1628 0%, #162536 100%);
        border-radius: 24px;
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 20px 50px rgba(10,22,40,0.25);
        border: 1px solid rgba(255,255,255,0.08);
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #ff6b00, #ff9d4d, #ff6b00);
    }

    .profile-header::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,107,0,0.12) 0%, transparent 60%);
        animation: pulse 4s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.6; }
        50% { transform: scale(1.08); opacity: 1; }
    }

    .avatar-pro {
        width: 110px;
        height: 110px;
        border-radius: 18px;
        object-fit: cover;
        border: 4px solid rgba(255,107,0,0.45);
        box-shadow: 0 12px 30px rgba(0,0,0,0.25);
    }

    .micro-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.4rem 0.9rem;
        border-radius: 999px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.18);
        color: #fff;
        font-weight: 700;
        font-size: 0.8rem;
    }

    .remaining-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.45rem 0.95rem;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.25);
        background: rgba(255,255,255,0.12);
        color: #fff;
        font-weight: 800;
        letter-spacing: -0.01em;
        box-shadow: 0 8px 20px rgba(0,0,0,0.14);
        backdrop-filter: blur(10px);
    }

    .remaining-pill--orange {
        background: linear-gradient(135deg, rgba(255,149,0,0.95) 0%, rgba(255,94,0,0.95) 100%);
        border-color: rgba(255,255,255,0.45);
        box-shadow: 0 14px 30px rgba(255, 149, 0, 0.25);
    }

    .remaining-pill--pulse {
        animation: remainingPulse 1.4s ease-in-out infinite;
    }

    .remaining-pill--pulse-urgent {
        animation: remainingPulseUrgent 0.9s ease-in-out infinite;
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

    .badge-contact {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(255,255,255,0.12);
        border-radius: 25px;
        font-size: 0.875rem;
        font-weight: 500;
        border: 1px solid rgba(255,255,255,0.2);
        transition: all 0.3s;
        text-decoration: none;
        color: white;
    }

    .badge-contact:hover {
        background: rgba(255,255,255,0.22);
        transform: translateY(-2px);
        color: white;
    }

    .hero-cta {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.85rem 1.25rem;
        border-radius: 14px;
        font-weight: 800;
        border: 0;
        transition: all 0.25s ease;
        text-decoration: none;
        justify-content: center;
        white-space: nowrap;
    }

    .hero-cta.primary {
        background: linear-gradient(135deg, #ff6b00 0%, #ff8533 100%);
        color: #fff;
        box-shadow: 0 12px 25px rgba(255,107,0,0.35);
    }

    .hero-cta.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 35px rgba(255,107,0,0.45);
        color: #fff;
    }

    .hero-cta.secondary {
        background: rgba(255,255,255,0.1);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.25);
    }

    .hero-cta.secondary:hover {
        background: rgba(255,255,255,0.18);
        color: #fff;
    }

    .hero-subcard {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.14);
        border-radius: 18px;
        padding: 1.4rem;
        backdrop-filter: blur(10px);
    }

    .hero-kpi {
        font-size: 1.5rem;
        font-weight: 900;
        line-height: 1;
        color: #fff;
    }

    .hero-kpi-label {
        font-size: 0.75rem;
        opacity: 0.85;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.9);
        font-weight: 700;
    }

    .stat-card {
        background: #ffffff;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        color: #0f172a;
        position: relative;
        border: 1px solid rgba(0,0,0,0.04);
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 18px 40px rgba(0,0,0,0.12);
    }

    .stat-card.formations { border-top: 4px solid #1e3c72; }
    .stat-card.tp { border-top: 4px solid #0ea5e9; }
    .stat-card.projets { border-top: 4px solid #06b6d4; }
    .stat-card.evenements { border-top: 4px solid #10b981; }
    .stat-card.paiements { border-top: 4px solid #8b5cf6; }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.45rem;
        color: #fff;
        position: absolute;
        right: 20px;
        top: 20px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .stat-card.formations .stat-icon { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); }
    .stat-card.tp .stat-icon { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); }
    .stat-card.projets .stat-icon { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); }
    .stat-card.evenements .stat-icon { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .stat-card.paiements .stat-icon { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }

    .stat-number {
        font-size: 2.9rem;
        font-weight: 800;
        line-height: 1;
        color: #0f172a;
        margin-bottom: 0.4rem;
    }

    .stat-label {
        font-size: 0.82rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .stat-btn {
        background: transparent;
        border: 2px solid #ff6b00;
        color: #ff6b00;
        font-weight: 700;
        transition: all 0.3s ease;
        border-radius: 12px;
        padding: 0.6rem 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        width: 100%;
    }

    .stat-btn:hover {
        background: #ff6b00;
        border-color: #ff6b00;
        color: #fff;
        transform: scale(1.03);
    }

    .progress-bar-custom {
        height: 8px;
        border-radius: 999px;
        background: rgba(0,0,0,0.07);
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(135deg, #ff6b00 0%, #ff9d4d 100%);
        transition: width 0.6s ease;
    }

    #dg_global_progress_fill,
    #dg_global_progress_fill_hero {
        background: linear-gradient(135deg, #ff6b00 0%, #ff9d4d 100%) !important;
    }

    .formation-card {
        border: none;
        border-radius: 22px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: #ffffff;
        box-shadow: 0 5px 18px rgba(0,0,0,0.07);
        border: 1px solid rgba(0,0,0,0.04);
    }

    .formation-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 18px 40px rgba(0,0,0,0.12);
    }

    .formation-header {
        background: linear-gradient(135deg, #0a1628 0%, #162536 100%);
        color: white;
        padding: 1.8rem 2rem;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }

    .formation-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #ff6b00, #ff9d4d, #ff6b00);
    }

    .info-item {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: #ffffff;
        border-left: 4px solid #ff6b00;
        box-shadow: 0 4px 16px rgba(0,0,0,0.05);
        position: relative;
    }

    .info-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.1);
    }

    .info-icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: linear-gradient(135deg, #0a1628 0%, #162536 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 18px rgba(10,22,40,0.25);
        color: #fff;
    }

    .countdown-card {
        background: linear-gradient(135deg, #0a1628 0%, #162536 100%);
        border-radius: 22px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 35px rgba(10,22,40,0.22);
        border: 1px solid rgba(255,255,255,0.08);
        margin-bottom: 2rem;
    }

    .countdown-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #ff6b00, #ff9d4d, #ff6b00);
    }

    .countdown-card .info-item {
        border-left: 0;
    }

    .btn-edit {
        background: #ff6b00;
        color: white;
        border: none;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        transition: all 0.3s;
    }

    .btn-edit:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(255,107,0,0.3);
        color: white;
    }

    .action-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: #ffffff;
        position: relative;
        cursor: pointer;
        text-decoration: none;
        display: block;
        color: inherit;
        box-shadow: 0 4px 16px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.04);
    }

    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.1);
    }

    .action-icon-wrapper {
        width: 60px;
        height: 60px;
        margin: 0 auto 1rem;
        border-radius: 12px;
        background: linear-gradient(135deg, #ff6b00 0%, #ff9d4d 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 5px 15px rgba(255,107,0,0.35);
        transition: all 0.3s;
        color: #fff;
    }

    .action-card:hover .action-icon-wrapper {
        transform: scale(1.1) rotate(5deg);
    }

    .action-title {
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }

    .action-subtitle {
        font-size: 0.875rem;
        color: #64748b;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    @media (max-width: 768px) {
        .stat-number { font-size: 2.4rem; }
        .profile-header { padding: 1.5rem; }
        .avatar-pro { width: 90px; height: 90px; }
    }
</style>

@php
    $sf = optional($student ?? null);
    $pr = optional($preReg ?? null);
    $userObj = isset($user) ? $user : (auth()->check() ? auth()->user() : null);

    $studentPhoto = $sf->profile_photo;
    $prePhoto = $pr->profile_photo ?? $pr->photo ?? $pr->image ?? $pr->image_url ?? $pr->avatar;
    $rawPhoto = $studentPhoto ?: $prePhoto;

    $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($rawPhoto ?? null);

    $fullName = trim(($sf->first_name ?? '') . ' ' . ($sf->last_name ?? ''));
    if ($fullName === '') {
        $fullName = ($userObj->name ?? '') ?: trim(($pr->first_name ?? '') . ' ' . ($pr->last_name ?? ''));
    }

    $email = ($sf->email ?? '') ?: (($userObj->email ?? '') ?: ($pr->email ?? ''));
    $phone = ($sf->phone ?? '') ?: ($pr->phone ?? '');
    $program = ($sf->program ?? '') ?: ($pr->program ?? '');
    $level = ($sf->level ?? '') ?: ($pr->level ?? '');
    $studentId = $sf->student_id ?? '';

    $tpTotalHero = (int) ($stats['tp_total'] ?? 0);
    $tpDoneHero = (int) ($stats['tp_realises'] ?? 0);
    $projTotalHero = (int) ($stats['projets_total'] ?? 0);
    $projDoneHero = (int) ($stats['projets_realises'] ?? 0);
    $globalProgressHero = 0;
    if ($tpTotalHero > 0) {
        $globalProgressHero += ($tpDoneHero / $tpTotalHero) * 50;
    }
    if ($projTotalHero > 0) {
        $globalProgressHero += ($projDoneHero / $projTotalHero) * 50;
    }
    $globalProgressHero = max(0, min(100, $globalProgressHero));

    $pendingForHero = $pendingAssignments ?? collect();
    $pending = $pendingForHero;
    $nextAssignmentHero = $pendingForHero->first();
    $montantRestantHero = (float) ($stats['montant_restant'] ?? 0);
@endphp

<div class="container-fluid px-lg-4 dashboard-page">

    {{-- Profile Header --}}
    <div class="profile-header fade-in-up">
        <div class="row align-items-center g-4 position-relative" style="z-index: 1;">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-4 flex-column flex-lg-row text-center text-lg-start">
                    <img src="{{ $photoUrl }}" alt="{{ $fullName }}" class="avatar-pro"
                         onerror="this.src='{{ asset('assets/img/avatar.png') }}'">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center justify-content-center justify-content-lg-start gap-2 flex-wrap mb-3">
                            <span class="micro-pill">
                                <i class="fas fa-bolt"></i>
                                Objectif : avancer chaque jour
                            </span>
                            @if(!$isExpired)
                                <span class="remaining-pill" id="js-remaining-pill">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $daysRemaining }} jours restants</span>
                                </span>
                            @else
                                <span class="micro-pill" style="background: rgba(239,68,68,0.25); border-color: rgba(239,68,68,0.35);">
                                    <i class="fas fa-ban"></i>
                                    Compte expiré
                                </span>
                            @endif
                        </div>

                        <h1 class="text-white mb-2" style="font-size: 2.6rem; font-weight: 900; letter-spacing: -0.03em;">
                            {{ $fullName ?: 'Étudiant EVC' }}
                        </h1>

                        <p class="text-white mb-4" style="opacity: 0.85; font-size: 1.1rem; font-weight: 500;">
                            {{ $program ?: 'Design Graphique' }}
                            @if($level)
                                <span class="mx-2">•</span>{{ $level }}
                            @endif
                            @if($studentId)
                                <span class="mx-2">•</span>{{ $studentId }}
                            @endif
                        </p>

                        <div class="d-flex gap-2 flex-wrap justify-content-center justify-content-lg-start align-items-center">
                            @if(!$isExpired)
                                @if($nextAssignmentHero)
                                    <a href="{{ route('design-graphique.tp.voir', ['id' => $nextAssignmentHero->id]) }}" class="hero-cta primary">
                                        <i class="fas fa-play"></i>
                                        Continuer
                                    </a>
                                @else
                                    <a href="{{ route('design-graphique.tp.index') }}" class="hero-cta primary">
                                        <i class="fas fa-tasks"></i>
                                        Voir mes TP
                                    </a>
                                @endif

                                <a href="{{ route('design-graphique.paiements.index') }}" class="hero-cta secondary" style="width: 44px; height: 44px; padding: 0; border-radius: 50%; justify-content: center;" title="Paiements">
                                    <i class="fas fa-wallet"></i>
                                </a>
                                <a href="{{ route('design-graphique.cvtheque.mon-profil') }}" class="hero-cta secondary" style="width: 44px; height: 44px; padding: 0; border-radius: 50%; justify-content: center;" title="CVthèque">
                                    <i class="fas fa-briefcase"></i>
                                </a>
                            @endif

                            @if($email)
                                <a href="mailto:{{ $email }}" class="hero-cta secondary" style="width: 44px; height: 44px; padding: 0; border-radius: 50%; justify-content: center;" title="{{ $email }}">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            @endif
                            @if($phone)
                                <a href="tel:{{ $phone }}" class="hero-cta secondary" style="width: 44px; height: 44px; padding: 0; border-radius: 50%; justify-content: center;" title="{{ $phone }}">
                                    <i class="fas fa-phone"></i>
                                </a>
                            @endif

                            @if($isExpired)
                                <a href="mailto:info@ecolevirtuelledescreatifs.com" class="hero-cta secondary">
                                    <i class="fas fa-envelope"></i>
                                    Renouveler
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="hero-subcard">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="hero-kpi-label">Progression globale</div>
                        <div class="hero-kpi" style="font-size: 2rem;"><span id="dg_global_progress_hero">{{ round($globalProgressHero) }}</span>%</div>
                    </div>
                    <div class="progress-bar-custom mb-3" style="background: rgba(255,255,255,0.15); height: 10px;">
                        <div id="dg_global_progress_fill_hero" class="progress-bar-fill" style="width: {{ $globalProgressHero }}%; height: 100%;"></div>
                    </div>
                    <div class="d-flex justify-content-between text-white mb-4" style="font-size: 0.85rem; font-weight: 600; opacity: 0.9;">
                        <span>TP <span id="dg_tp_realises_hero">{{ $stats['tp_realises'] ?? 0 }}</span>/<span id="dg_tp_total_hero">{{ $stats['tp_total'] ?? 0 }}</span></span>
                        <span>Projets <span id="dg_projets_realises_hero">{{ $stats['projets_realises'] ?? 0 }}</span>/<span id="dg_projets_total_hero">{{ $stats['projets_total'] ?? 0 }}</span></span>
                        <span><span id="dg_montant_restant_hero">{{ number_format($montantRestantHero, 0, ',', ' ') }}</span> FCFA</span>
                    </div>
                    @if(!$isExpired)
                        @if($nextAssignmentHero)
                            <a href="{{ route('design-graphique.tp.voir', ['id' => $nextAssignmentHero->id]) }}" class="hero-cta primary w-100" style="justify-content: center;">
                                <i class="fas fa-arrow-right"></i>
                                {{ \Illuminate\Support\Str::limit($nextAssignmentHero->title ?? 'Travail à faire', 28) }}
                            </a>
                        @else
                            <a href="{{ route('design-graphique.cvtheque.mon-profil') }}" class="hero-cta primary w-100" style="justify-content: center;">
                                <i class="fas fa-user-check"></i>
                                Compléter mon profil
                            </a>
                        @endif
                    @endif
                </div>
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
                    <a href="{{ route('design-graphique.formations.index') }}" class="btn stat-btn w-100">
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

                    <div class="mt-3">
                        <a href="{{ route('design-graphique.tp.index') }}" class="btn stat-btn w-100">
                            <i class="fas fa-arrow-right me-2"></i>
                            Voir mes TP
                        </a>
                    </div>
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

                    <div class="mt-3">
                        <a href="{{ route('design-graphique.projets.index') }}" class="btn stat-btn w-100">
                            <i class="fas fa-arrow-right me-2"></i>
                            Mes projets
                        </a>
                    </div>
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
                            <div class="stat-number" id="dg_webinaires_en_cours" style="font-size: 2rem;">{{ $stats['evenements_en_cours'] ?? ($stats['webinaires_en_cours'] ?? 0) }}</div>
                            <small style="opacity: 0.8;">Événements (en cours)</small>
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

    <div class="row mb-5">
        <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.5s;">
            <div class="stat-card paiements h-100">
                <i class="fas fa-wallet stat-icon"></i>
                <div class="card-body p-4">
                    <div class="stat-number mb-2" style="font-size: 2.2rem;">
                        <span id="dg_montant_restant_card">{{ number_format((float)($stats['montant_restant'] ?? 0), 0, ',', ' ') }}</span>
                    </div>
                    <div class="stat-label mb-3">Montant restant à payer (FCFA)</div>
                    <a href="{{ route('design-graphique.paiements.index') }}" class="btn stat-btn w-100">
                        <i class="fas fa-receipt me-2"></i>
                        Voir mes paiements
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.6s;">
            <div class="info-item h-100" style="background: linear-gradient(135deg, #ffffff 0%, #eef2ff 100%);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                        <div>
                            <div class="small" style="text-transform: uppercase; font-weight: 800; letter-spacing: 0.12em; color: #1e40af;">
                                Votre prochain levier de réussite
                            </div>
                            <h4 class="mb-1" style="font-weight: 900; color: #0f172a;">
                                Complétez votre profil CVthèque + portfolio
                            </h4>
                            <div style="color: #334155; font-weight: 600;">
                                Un profil complet augmente votre visibilité auprès de l'administration.
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('design-graphique.cvtheque.mon-profil') }}" class="btn btn-primary" style="border-radius: 12px; font-weight: 800;">
                                <i class="fas fa-briefcase me-2"></i>
                                Mettre à jour
                            </a>
                            <a href="{{ route('design-graphique.documents.index') }}" class="btn btn-outline-primary" style="border-radius: 12px; font-weight: 800;">
                                <i class="fas fa-folder-open me-2"></i>
                                Mes documents
                            </a>
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
                        <div class="text-white" style="font-size: 1.5rem; font-weight: 700;">{{ $expirationDate->format('d/m/Y') }}</div>
                        <div class="text-white" style="opacity: 0.8;">{{ $expirationDate->format('H:i') }}</div>
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



</div>

@section('scripts')
<script>
    (function() {
        const statsUrl = @json(route('dashboard.design-graphique.stats'));
        const expirationIso = @json($expirationDate->toIso8601String());

        const el = {
            formations: document.getElementById('dg_formations_disponibles'),
            tpRealises: document.getElementById('dg_tp_realises'),
            tpTotal: document.getElementById('dg_tp_total'),
            tpRealisesHero: document.getElementById('dg_tp_realises_hero'),
            tpTotalHero: document.getElementById('dg_tp_total_hero'),
            tpProgressFill: document.getElementById('dg_tp_progress_fill'),
            tpProgressText: document.getElementById('dg_tp_progress_text'),
            projetsRealises: document.getElementById('dg_projets_realises'),
            projetsTotal: document.getElementById('dg_projets_total'),
            projetsRealisesHero: document.getElementById('dg_projets_realises_hero'),
            projetsTotalHero: document.getElementById('dg_projets_total_hero'),
            projetsProgressFill: document.getElementById('dg_projets_progress_fill'),
            projetsProgressText: document.getElementById('dg_projets_progress_text'),
            webinaires: document.getElementById('dg_webinaires_en_cours'),
            actualites: document.getElementById('dg_actualites_en_cours'),
            globalProgress: document.getElementById('dg_global_progress'),
            globalProgressFill: document.getElementById('dg_global_progress_fill'),
            globalProgressHero: document.getElementById('dg_global_progress_hero'),
            globalProgressFillHero: document.getElementById('dg_global_progress_fill_hero'),
            montantRestantHero: document.getElementById('dg_montant_restant_hero'),
            montantRestantCard: document.getElementById('dg_montant_restant_card'),
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
                setText(el.tpRealisesHero, tpRealises);
                setText(el.tpTotalHero, tpTotal);
                setText(el.projetsRealises, projetsRealises);
                setText(el.projetsTotal, projetsTotal);
                setText(el.projetsRealisesHero, projetsRealises);
                setText(el.projetsTotalHero, projetsTotal);
                setText(el.webinaires, safeInt((typeof stats.evenements_en_cours !== 'undefined') ? stats.evenements_en_cours : stats.webinaires_en_cours));
                setText(el.actualites, safeInt(stats.actualites_en_cours));

                if (typeof stats.montant_restant !== 'undefined') {
                    const mr = Number(stats.montant_restant);
                    setText(el.montantRestantHero, formatFcfa(mr));
                    setText(el.montantRestantCard, formatFcfa(mr));
                }

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
                    setText(el.globalProgressHero, Math.round(gp));
                    setWidth(el.globalProgressFillHero, gp);
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
                    msgEl.textContent = `Objectif: valider un TP par semaine jusqu'à la fin`;
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
