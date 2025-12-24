@extends('layouts.ki-admin')

@section('title', 'Espace Étudiant - Design Graphique')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);

        --glass-bg: rgba(255, 255, 255, 0.05);
        --glass-border: rgba(255, 255, 255, 0.1);
        --glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);

        --text-primary: #ffffff;
        --text-secondary: rgba(255, 255, 255, 0.8);
        --text-muted: rgba(255, 255, 255, 0.6);

        --spacing-xs: 0.5rem;
        --spacing-sm: 1rem;
        --spacing-md: 1.5rem;
        --spacing-lg: 2rem;
        --spacing-xl: 3rem;

        --radius-sm: 12px;
        --radius-md: 16px;
        --radius-lg: 24px;
        --radius-xl: 32px;

        --transition-fast: 0.2s ease;
        --transition-normal: 0.3s ease;
        --transition-slow: 0.5s ease;
    }

    body {
        background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* Glassmorphism Card */
    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        box-shadow: var(--glass-shadow);
        transition: all var(--transition-normal);
        position: relative;
        overflow: hidden;
    }

    .glass-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transition: left 0.7s ease;
    }

    .glass-card:hover::before {
        left: 100%;
    }

    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 48px 0 rgba(31, 38, 135, 0.5);
        border-color: rgba(255, 255, 255, 0.2);
    }

    /* Hero Section */
    .hero-section {
        background: var(--primary-gradient);
        border-radius: var(--radius-xl);
        padding: var(--spacing-xl);
        position: relative;
        overflow: hidden;
        margin-bottom: var(--spacing-lg);
    }

    .hero-section::before {
        content: '';
        position: absolute;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        top: -250px;
        right: -250px;
        animation: pulse 4s ease-in-out infinite;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
        bottom: -150px;
        left: -150px;
        animation: pulse 5s ease-in-out infinite 1s;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.2);
            opacity: 0.5;
        }
    }

    .profile-avatar {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        object-fit: cover;
        border: 6px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        position: relative;
        z-index: 10;
        transition: all var(--transition-normal);
    }

    .profile-avatar:hover {
        transform: scale(1.05);
        border-color: rgba(255, 255, 255, 0.5);
    }

    /* Stats Card */
    .stat-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-md);
        padding: var(--spacing-md);
        transition: all var(--transition-normal);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
        transform: scaleX(0);
        transition: transform var(--transition-normal);
    }

    .stat-card:hover::before {
        transform: scaleX(1);
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    }

    .stat-icon {
        width: 64px;
        height: 64px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: var(--spacing-sm);
        position: relative;
        overflow: hidden;
    }

    .stat-icon::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }

    .stat-card:hover .stat-icon::before {
        transform: translateX(100%);
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: var(--spacing-xs);
        background: linear-gradient(135deg, #fff 0%, rgba(255, 255, 255, 0.7) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    /* Countdown Widget */
    .countdown-widget {
        background: var(--primary-gradient);
        border-radius: var(--radius-xl);
        padding: var(--spacing-xl);
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(102, 126, 234, 0.4);
    }

    .countdown-widget::before {
        content: '';
        position: absolute;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        top: -200px;
        right: -200px;
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    .countdown-box {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.25);
        border-radius: var(--radius-lg);
        padding: var(--spacing-lg);
        position: relative;
        z-index: 1;
    }

    .countdown-number {
        font-size: 5rem;
        font-weight: 900;
        line-height: 1;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        background: linear-gradient(135deg, #fff 0%, #ffd700 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: glow 2s ease-in-out infinite;
    }

    @keyframes glow {
        0%, 100% {
            filter: drop-shadow(0 0 20px rgba(255, 215, 0, 0.5));
        }
        50% {
            filter: drop-shadow(0 0 40px rgba(255, 215, 0, 0.8));
        }
    }

    .progress-bar-modern {
        height: 12px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 100px;
        overflow: hidden;
        position: relative;
    }

    .progress-fill-modern {
        height: 100%;
        background: linear-gradient(90deg, #fff 0%, #ffd700 50%, #fff 100%);
        background-size: 200% 100%;
        border-radius: 100px;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
        animation: shimmer 2s linear infinite;
        position: relative;
        overflow: hidden;
    }

    .progress-fill-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
        animation: slide 1.5s ease-in-out infinite;
    }

    @keyframes shimmer {
        0% {
            background-position: 0% 50%;
        }
        100% {
            background-position: 200% 50%;
        }
    }

    @keyframes slide {
        0% {
            transform: translateX(-100%);
        }
        100% {
            transform: translateX(100%);
        }
    }

    /* Icon Animation */
    .icon-rocket {
        animation: rocket-bounce 2s ease-in-out infinite;
    }

    @keyframes rocket-bounce {
        0%, 100% {
            transform: translateY(0) rotate(-5deg);
        }
        50% {
            transform: translateY(-15px) rotate(5deg);
        }
    }

    /* Floating Particles */
    .particle {
        position: absolute;
        width: 10px;
        height: 10px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        animation: float-particle 8s ease-in-out infinite;
    }

    .particle:nth-child(1) {
        top: 20%;
        left: 10%;
        animation-delay: 0s;
        animation-duration: 8s;
    }

    .particle:nth-child(2) {
        top: 60%;
        left: 80%;
        animation-delay: 2s;
        animation-duration: 10s;
    }

    .particle:nth-child(3) {
        top: 40%;
        left: 50%;
        animation-delay: 4s;
        animation-duration: 12s;
    }

    @keyframes float-particle {
        0%, 100% {
            transform: translate(0, 0);
            opacity: 0;
        }
        10%, 90% {
            opacity: 1;
        }
        50% {
            transform: translate(50px, -50px);
        }
    }

    /* Badge */
    .badge-modern {
        padding: 0.5rem 1rem;
        border-radius: 100px;
        font-weight: 600;
        font-size: 0.875rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all var(--transition-fast);
    }

    .badge-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    /* Button Modern */
    .btn-modern {
        padding: 0.875rem 2rem;
        border-radius: 100px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.875rem;
        border: none;
        background: var(--primary-gradient);
        color: white;
        transition: all var(--transition-normal);
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-modern::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn-modern:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
    }

    .btn-modern span {
        position: relative;
        z-index: 1;
    }

    /* Quick Action Card */
    .quick-action-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        padding: var(--spacing-lg);
        text-align: center;
        transition: all var(--transition-normal);
        cursor: pointer;
        text-decoration: none;
        display: block;
        color: inherit;
    }

    .quick-action-card:hover {
        transform: translateY(-8px) scale(1.02);
        border-color: rgba(255, 255, 255, 0.3);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    }

    .quick-action-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto var(--spacing-md);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        transition: all var(--transition-normal);
    }

    .quick-action-card:hover .quick-action-icon {
        transform: rotateY(360deg);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-avatar {
            width: 120px;
            height: 120px;
        }

        .stat-value {
            font-size: 2rem;
        }

        .countdown-number {
            font-size: 3rem;
        }

        .hero-section {
            padding: var(--spacing-lg);
        }
    }

    /* Animations */
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

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .animate-fadeInUp {
        animation: fadeInUp 0.8s ease-out forwards;
    }

    .animate-fadeInDown {
        animation: fadeInDown 0.8s ease-out forwards;
    }

    .animate-fadeIn {
        animation: fadeIn 1s ease-out forwards;
    }

    .delay-1 { animation-delay: 0.1s; opacity: 0; }
    .delay-2 { animation-delay: 0.2s; opacity: 0; }
    .delay-3 { animation-delay: 0.3s; opacity: 0; }
    .delay-4 { animation-delay: 0.4s; opacity: 0; }
    .delay-5 { animation-delay: 0.5s; opacity: 0; }
    .delay-6 { animation-delay: 0.6s; opacity: 0; }
</style>

@php
    $sf = optional($student ?? null);
    $pr = optional($preReg ?? null);
    $userObj = isset($user) ? $user : (auth()->check() ? auth()->user() : null);

    // Photo
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

<div class="container-fluid py-4">

    {{-- Hero Section --}}
    <div class="hero-section animate-fadeInDown">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>

        <div class="row align-items-center position-relative" style="z-index: 10;">
            <div class="col-lg-3 text-center mb-4 mb-lg-0">
                <img src="{{ $photoUrl }}" alt="{{ $fullName }}"
                     class="profile-avatar"
                     onerror="this.src='{{ asset('assets/img/avatar.png') }}'">
            </div>
            <div class="col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold text-white mb-3" style="text-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                    {{ $fullName ?: 'Étudiant EVC' }}
                </h1>
                <p class="lead text-white mb-4" style="font-size: 1.25rem; opacity: 0.9;">
                    <i class="fas fa-graduation-cap me-2"></i>{{ $program ?: 'Design Graphique' }}
                    @if($level)
                        <span class="mx-2">•</span>{{ $level }}
                    @endif
                </p>
                <div class="d-flex gap-3 flex-wrap justify-content-center justify-content-lg-start">
                    @if($email)
                    <a href="mailto:{{ $email }}" class="badge-modern" style="background: rgba(255, 255, 255, 0.15); color: white; text-decoration: none;">
                        <i class="fas fa-envelope me-2"></i>{{ $email }}
                    </a>
                    @endif
                    @if($phone)
                    <a href="tel:{{ $phone }}" class="badge-modern" style="background: rgba(255, 255, 255, 0.15); color: white; text-decoration: none;">
                        <i class="fas fa-phone me-2"></i>{{ $phone }}
                    </a>
                    @endif
                </div>
            </div>
            <div class="col-lg-3 text-center">
                <a href="{{ route('design-graphique.profil.editer') }}" class="btn-modern">
                    <span><i class="fas fa-edit me-2"></i>Modifier Profil</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Statistics Grid --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card animate-fadeInUp delay-1">
                <div class="stat-icon" style="background: var(--primary-gradient);">
                    <i class="fas fa-graduation-cap text-white"></i>
                </div>
                <div class="stat-value">{{ $stats['formations_disponibles'] ?? 0 }}</div>
                <div class="stat-label">Formations Disponibles</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card animate-fadeInUp delay-2">
                <div class="stat-icon" style="background: var(--secondary-gradient);">
                    <i class="fas fa-tasks text-white"></i>
                </div>
                <div class="stat-value">{{ $stats['tp_realises'] ?? 0 }}/{{ $stats['tp_total'] ?? 0 }}</div>
                <div class="stat-label">TP Réalisés</div>
                @if(($stats['tp_total'] ?? 0) > 0)
                <div class="mt-2">
                    <div class="progress-bar-modern">
                        <div class="progress-fill-modern" style="width: {{ round(($stats['tp_realises'] / $stats['tp_total']) * 100) }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card animate-fadeInUp delay-3">
                <div class="stat-icon" style="background: var(--success-gradient);">
                    <i class="fas fa-project-diagram text-white"></i>
                </div>
                <div class="stat-value">{{ $stats['projets_realises'] ?? 0 }}/{{ $stats['projets_total'] ?? 0 }}</div>
                <div class="stat-label">Projets Complétés</div>
                @if(($stats['projets_total'] ?? 0) > 0)
                <div class="mt-2">
                    <div class="progress-bar-modern">
                        <div class="progress-fill-modern" style="width: {{ round(($stats['projets_realises'] / $stats['projets_total']) * 100) }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card animate-fadeInUp delay-4">
                <div class="stat-icon" style="background: var(--warning-gradient);">
                    <i class="fas fa-calendar-alt text-white"></i>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-value" style="font-size: 1.75rem;">{{ $stats['webinaires_en_cours'] ?? 0 }}</div>
                        <div class="stat-label" style="font-size: 0.75rem;">Webinaires</div>
                    </div>
                    <div class="vr mx-2" style="opacity: 0.3;"></div>
                    <div>
                        <div class="stat-value" style="font-size: 1.75rem;">{{ $stats['actualites_en_cours'] ?? 0 }}</div>
                        <div class="stat-label" style="font-size: 0.75rem;">Actualités</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Countdown Widget --}}
    @if(!$isExpired)
    <div class="countdown-widget animate-fadeInUp delay-5 mb-4">
        <div class="row align-items-center position-relative" style="z-index: 10;">
            <div class="col-lg-2 text-center mb-4 mb-lg-0">
                <div class="icon-rocket" style="font-size: 5rem; filter: drop-shadow(0 10px 30px rgba(0,0,0,0.3));">
                    🚀
                </div>
            </div>
            <div class="col-lg-10">
                <h3 class="text-white fw-bold mb-3" style="font-size: 2rem; text-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                    <i class="fas fa-fire me-2" style="color: #ff6b6b;"></i>
                    Profitez de Chaque Instant !
                </h3>
                <p class="text-white mb-4" style="font-size: 1.1rem; opacity: 0.9;">
                    Votre accès est valable pendant <strong>4 mois</strong>. Maximisez votre apprentissage !
                </p>

                <div class="countdown-box">
                    <div class="row align-items-center g-4">
                        <div class="col-md-4 text-center">
                            <div class="small text-white mb-2" style="opacity: 0.8; text-transform: uppercase; letter-spacing: 1px;">
                                <i class="fas fa-hourglass-half me-2"></i>Temps Restant
                            </div>
                            <div class="countdown-number">{{ $daysRemaining }}</div>
                            <div class="text-white" style="font-size: 1.25rem; font-weight: 600;">jours</div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="small text-white mb-2" style="opacity: 0.8; text-transform: uppercase; letter-spacing: 1px;">
                                <i class="fas fa-calendar-alt me-2"></i>Expire le
                            </div>
                            <div class="text-white" style="font-size: 2rem; font-weight: 700;">
                                {{ $expirationDate->format('d/m/Y') }}
                            </div>
                            <div class="text-white" style="opacity: 0.8;">
                                {{ $expirationDate->format('H:i') }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            @php
                                $totalDays = 120;
                                $progress = ($daysRemaining / $totalDays) * 100;
                            @endphp
                            <div class="mb-3">
                                <div class="d-flex justify-content-between text-white mb-2">
                                    <span style="font-size: 0.875rem; opacity: 0.8;">Progression</span>
                                    <span style="font-weight: 700;">{{ round($progress) }}%</span>
                                </div>
                                <div class="progress-bar-modern">
                                    <div class="progress-fill-modern" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                            <div class="text-white" style="font-size: 0.875rem; opacity: 0.8;">
                                <i class="fas fa-bolt me-1"></i>{{ round($progress) }}% de votre formation restant
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-card mt-4 p-4">
                    <div class="d-flex align-items-start gap-3">
                        <div style="font-size: 2.5rem;">💡</div>
                        <div class="text-white">
                            <strong style="font-size: 1.1rem;">Conseil Pro :</strong>
                            <p class="mb-0 mt-2" style="opacity: 0.9; line-height: 1.6;">
                                Maximisez votre apprentissage ! Complétez vos TP et projets avant l'expiration pour valider vos compétences.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Quick Actions --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <h4 class="text-white mb-4 fw-bold" style="font-size: 1.5rem;">
                <i class="fas fa-bolt me-2" style="color: #ffd700;"></i>
                Actions Rapides
            </h4>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('design-graphique.profil.editer') }}" class="quick-action-card animate-fadeInUp delay-1">
                <div class="quick-action-icon" style="background: var(--primary-gradient);">
                    <i class="fas fa-user-edit text-white"></i>
                </div>
                <h6 class="text-white fw-bold mb-2">Modifier Profil</h6>
                <p class="text-white-50 small mb-0">Mettre à jour mes informations</p>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('design-graphique.documents.index') }}" class="quick-action-card animate-fadeInUp delay-2">
                <div class="quick-action-icon" style="background: var(--secondary-gradient);">
                    <i class="fas fa-folder-open text-white"></i>
                </div>
                <h6 class="text-white fw-bold mb-2">Mes Documents</h6>
                <p class="text-white-50 small mb-0">CV, lettres, réalisations</p>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('design-graphique.parametres.index') }}" class="quick-action-card animate-fadeInUp delay-3">
                <div class="quick-action-icon" style="background: var(--success-gradient);">
                    <i class="fas fa-cog text-white"></i>
                </div>
                <h6 class="text-white fw-bold mb-2">Paramètres</h6>
                <p class="text-white-50 small mb-0">Configuration du compte</p>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="#" class="quick-action-card animate-fadeInUp delay-4">
                <div class="quick-action-icon" style="background: var(--warning-gradient);">
                    <i class="fas fa-chart-line text-white"></i>
                </div>
                <h6 class="text-white fw-bold mb-2">Statistiques</h6>
                <p class="text-white-50 small mb-0">Suivi de progression</p>
            </a>
        </div>
    </div>

    {{-- Additional Info Grid --}}
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="glass-card p-4 animate-fadeInUp delay-5">
                <h5 class="text-white fw-bold mb-4">
                    <i class="fas fa-chart-bar me-2"></i>
                    Aperçu de ma Formation
                </h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3 p-3" style="background: rgba(255,255,255,0.05); border-radius: 12px;">
                            <div style="width: 48px; height: 48px; background: var(--primary-gradient); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-book text-white"></i>
                            </div>
                            <div>
                                <div class="text-white-50 small">Programme</div>
                                <div class="text-white fw-bold">{{ $program ?: 'Design Graphique' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3 p-3" style="background: rgba(255,255,255,0.05); border-radius: 12px;">
                            <div style="width: 48px; height: 48px; background: var(--secondary-gradient); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-layer-group text-white"></i>
                            </div>
                            <div>
                                <div class="text-white-50 small">Niveau</div>
                                <div class="text-white fw-bold">{{ $level ?: 'Débutant' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3 p-3" style="background: rgba(255,255,255,0.05); border-radius: 12px;">
                            <div style="width: 48px; height: 48px; background: var(--success-gradient); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-id-card text-white"></i>
                            </div>
                            <div>
                                <div class="text-white-50 small">Matricule</div>
                                <div class="text-white fw-bold">{{ $studentId ?: 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3 p-3" style="background: rgba(255,255,255,0.05); border-radius: 12px;">
                            <div style="width: 48px; height: 48px; background: var(--warning-gradient); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                            <div>
                                <div class="text-white-50 small">Statut</div>
                                <div class="text-white fw-bold">Actif</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="glass-card p-4 animate-fadeInUp delay-6 h-100">
                <h5 class="text-white fw-bold mb-4">
                    <i class="fas fa-trophy me-2" style="color: #ffd700;"></i>
                    Progression Globale
                </h5>
                <div class="text-center mb-4">
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
                    <div style="font-size: 4rem; font-weight: 900; background: linear-gradient(135deg, #fff 0%, #ffd700 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        {{ round($globalProgress) }}%
                    </div>
                    <div class="text-white-50">De votre formation complétée</div>
                </div>
                <div class="progress-bar-modern mb-3">
                    <div class="progress-fill-modern" style="width: {{ $globalProgress }}%"></div>
                </div>
                <div class="text-white-50 text-center small">
                    <i class="fas fa-info-circle me-1"></i>
                    Continuez comme ça, vous êtes sur la bonne voie !
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
