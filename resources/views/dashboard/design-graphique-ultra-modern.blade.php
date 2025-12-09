@extends('layouts.ki-admin')

@section('title', 'Espace Étudiant - Design Graphique')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    * {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    :root {
        --color-primary: #6366f1;
        --color-primary-light: #818cf8;
        --color-success: #10b981;
        --color-warning: #f59e0b;
        --color-danger: #ef4444;

        --bg-primary: #0a0a0f;
        --bg-secondary: #13131a;
        --bg-card: rgba(255, 255, 255, 0.03);
        --bg-hover: rgba(255, 255, 255, 0.06);

        --text-primary: #ffffff;
        --text-secondary: rgba(255, 255, 255, 0.7);
        --text-muted: rgba(255, 255, 255, 0.5);

        --border: rgba(255, 255, 255, 0.08);
        --border-hover: rgba(255, 255, 255, 0.15);

        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;

        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
        --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.4), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
    }

    body {
        background: linear-gradient(180deg, #0a0a0f 0%, #13131a 100%);
        min-height: 100vh;
        color: var(--text-primary);
    }

    .container-modern {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1.5rem;
    }

    /* Header Profile Card */
    .profile-header {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        border-radius: var(--radius-lg);
        padding: 3rem 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        pointer-events: none;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: var(--radius-lg);
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.2);
        box-shadow: var(--shadow-lg);
    }

    .profile-name {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.2;
        margin: 0 0 0.5rem 0;
    }

    .profile-role {
        font-size: 1.125rem;
        opacity: 0.9;
        font-weight: 500;
    }

    .profile-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 100px;
        font-size: 0.875rem;
        font-weight: 500;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.2s;
    }

    .profile-badge:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }

    /* Clean Card */
    .card-clean {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        transition: all 0.2s;
        height: 100%;
    }

    .card-clean:hover {
        background: var(--bg-hover);
        border-color: var(--border-hover);
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }

    .card-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .card-title {
        font-size: 0.875rem;
        color: var(--text-muted);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }

    .card-value {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .card-subtitle {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    /* Progress Bar Modern */
    .progress-modern {
        height: 6px;
        background: var(--bg-card);
        border-radius: 100px;
        overflow: hidden;
        margin-top: 0.75rem;
    }

    .progress-fill {
        height: 100%;
        border-radius: 100px;
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Countdown Card */
    .countdown-card {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        border-radius: var(--radius-lg);
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .countdown-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    }

    .countdown-number {
        font-size: 4rem;
        font-weight: 900;
        line-height: 1;
    }

    .countdown-label {
        font-size: 1.125rem;
        opacity: 0.9;
        margin-top: 0.5rem;
        font-weight: 500;
    }

    /* Button Modern */
    .btn-modern {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: white;
        color: var(--color-primary);
        border: none;
        border-radius: var(--radius-md);
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        color: var(--color-primary-dark);
    }

    .btn-ghost {
        background: var(--bg-card);
        color: var(--text-primary);
        border: 1px solid var(--border);
    }

    .btn-ghost:hover {
        background: var(--bg-hover);
        border-color: var(--border-hover);
        color: var(--text-primary);
    }

    /* Action Card */
    .action-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 2rem;
        text-align: center;
        transition: all 0.2s;
        cursor: pointer;
        text-decoration: none;
        display: block;
        color: inherit;
    }

    .action-card:hover {
        background: var(--bg-hover);
        border-color: var(--border-hover);
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .action-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 1rem;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        transition: all 0.3s;
    }

    .action-card:hover .action-icon {
        transform: scale(1.1);
    }

    .action-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    .action-subtitle {
        font-size: 0.875rem;
        color: var(--text-muted);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    /* Info Badge */
    .info-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 1.5rem;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .info-badge:hover {
        background: var(--bg-hover);
        border-color: var(--border-hover);
    }

    .info-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .info-label {
        font-size: 0.75rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-weight: 600;
        color: var(--text-primary);
    }

    /* Section Title */
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: var(--text-primary);
    }

    .section-subtitle {
        font-size: 1rem;
        color: var(--text-secondary);
        margin-top: -1rem;
        margin-bottom: 2rem;
    }

    /* Alert Modern */
    .alert-modern {
        background: rgba(99, 102, 241, 0.1);
        border: 1px solid rgba(99, 102, 241, 0.3);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .alert-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-md);
        background: var(--color-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .alert-content h6 {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .alert-content p {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container-modern {
            padding: 1rem;
        }

        .profile-header {
            padding: 2rem 1.5rem;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
        }

        .profile-name {
            font-size: 1.5rem;
        }

        .profile-role {
            font-size: 1rem;
        }

        .card-value {
            font-size: 2rem;
        }

        .countdown-number {
            font-size: 3rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
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

    $studentPhoto = $sf->profile_photo;
    $prePhoto = $pr->profile_photo ?? $pr->photo ?? $pr->image ?? $pr->image_url ?? $pr->avatar;
    $rawPhoto = $studentPhoto ?: $prePhoto;

    if ($rawPhoto) {
        if (preg_match('/^https?:\/\//', $rawPhoto)) {
            $photoUrl = $rawPhoto;
        } elseif (str_starts_with($rawPhoto, 'photos_preregistrations/')) {
            $photoUrl = asset('storage/' . $rawPhoto);
        } elseif (str_starts_with($rawPhoto, 'uploads/')) {
            $photoUrl = asset($rawPhoto);
        } else {
            $photoUrl = asset('storage/' . $rawPhoto);
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

<div class="container-modern">

    {{-- Profile Header --}}
    <div class="profile-header fade-in-up">
        <div class="row align-items-center position-relative" style="z-index: 1;">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-4 flex-column flex-lg-row text-center text-lg-start">
                    <img src="{{ $photoUrl }}" alt="{{ $fullName }}" class="profile-avatar"
                         onerror="this.src='{{ asset('assets/img/avatar.png') }}'">
                    <div>
                        <h1 class="profile-name text-white">
                            {{ $fullName ?: 'Étudiant EVC' }}
                        </h1>
                        <p class="profile-role text-white mb-3">
                            {{ $program ?: 'Design Graphique' }}
                            @if($level)
                                <span class="mx-2">·</span>{{ $level }}
                            @endif
                        </p>
                        <div class="d-flex gap-2 flex-wrap justify-content-center justify-content-lg-start">
                            @if($email)
                            <a href="mailto:{{ $email }}" class="profile-badge text-white">
                                <i class="fas fa-envelope"></i>
                                <span>{{ $email }}</span>
                            </a>
                            @endif
                            @if($phone)
                            <a href="tel:{{ $phone }}" class="profile-badge text-white">
                                <i class="fas fa-phone"></i>
                                <span>{{ $phone }}</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center text-lg-end mt-4 mt-lg-0">
                <a href="{{ route('design-graphique.profil.editer') }}" class="btn-modern">
                    <i class="fas fa-edit"></i>
                    <span>Modifier mon profil</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Statistics Grid --}}
    <div class="stats-grid">
        <div class="card-clean fade-in-up delay-1">
            <div class="card-icon" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                <i class="fas fa-graduation-cap text-white"></i>
            </div>
            <div class="card-title">Formations</div>
            <div class="card-value">{{ $stats['formations_disponibles'] ?? 0 }}</div>
            <div class="card-subtitle">Programmes disponibles</div>
        </div>

        <div class="card-clean fade-in-up delay-2">
            <div class="card-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <i class="fas fa-tasks text-white"></i>
            </div>
            <div class="card-title">Travaux Pratiques</div>
            <div class="card-value">{{ $stats['tp_realises'] ?? 0 }}<span style="font-size: 1.5rem; color: var(--text-muted);">/{{ $stats['tp_total'] ?? 0 }}</span></div>
            @if(($stats['tp_total'] ?? 0) > 0)
            <div class="progress-modern">
                <div class="progress-fill" style="width: {{ round(($stats['tp_realises'] / $stats['tp_total']) * 100) }}%; background: linear-gradient(90deg, #10b981 0%, #059669 100%);"></div>
            </div>
            <div class="card-subtitle mt-2">{{ round(($stats['tp_realises'] / $stats['tp_total']) * 100) }}% complétés</div>
            @endif
        </div>

        <div class="card-clean fade-in-up delay-3">
            <div class="card-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                <i class="fas fa-project-diagram text-white"></i>
            </div>
            <div class="card-title">Projets</div>
            <div class="card-value">{{ $stats['projets_realises'] ?? 0 }}<span style="font-size: 1.5rem; color: var(--text-muted);">/{{ $stats['projets_total'] ?? 0 }}</span></div>
            @if(($stats['projets_total'] ?? 0) > 0)
            <div class="progress-modern">
                <div class="progress-fill" style="width: {{ round(($stats['projets_realises'] / $stats['projets_total']) * 100) }}%; background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);"></div>
            </div>
            <div class="card-subtitle mt-2">{{ round(($stats['projets_realises'] / $stats['projets_total']) * 100) }}% réalisés</div>
            @endif
        </div>

        <div class="card-clean fade-in-up delay-4">
            <div class="card-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <i class="fas fa-calendar-alt text-white"></i>
            </div>
            <div class="card-title">Événements</div>
            <div class="row g-0 mt-3">
                <div class="col-6 text-center">
                    <div style="font-size: 1.75rem; font-weight: 700;">{{ $stats['webinaires_en_cours'] ?? 0 }}</div>
                    <div class="card-subtitle">Webinaires</div>
                </div>
                <div class="col-6 text-center" style="border-left: 1px solid var(--border);">
                    <div style="font-size: 1.75rem; font-weight: 700;">{{ $stats['actualites_en_cours'] ?? 0 }}</div>
                    <div class="card-subtitle">Actualités</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Countdown Section --}}
    @if(!$isExpired)
    <div class="countdown-card fade-in-up delay-5 mb-4">
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
                        <div class="countdown-number text-white">{{ $daysRemaining }}</div>
                        <div class="countdown-label text-white">jours restants</div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="text-white" style="font-size: 0.875rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Expire le</div>
                        <div class="text-white" style="font-size: 1.5rem; font-weight: 700;">{{ $expirationDate->format('d/m/Y') }}</div>
                        <div class="text-white" style="opacity: 0.8;">{{ $expirationDate->format('H:i') }}</div>
                    </div>
                    <div class="col-md-4">
                        @php
                            $totalDays = 120;
                            $progress = ($daysRemaining / $totalDays) * 100;
                        @endphp
                        <div class="text-white" style="font-size: 0.875rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Progression</div>
                        <div class="progress-modern" style="background: rgba(255, 255, 255, 0.2);">
                            <div class="progress-fill" style="width: {{ $progress }}%; background: white;"></div>
                        </div>
                        <div class="text-white mt-2" style="font-weight: 600;">{{ round($progress) }}% de votre formation</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center mt-4 mt-lg-0">
                <div class="alert-modern" style="background: rgba(255, 255, 255, 0.15); border-color: rgba(255, 255, 255, 0.3);">
                    <div class="alert-icon" style="background: white;">
                        <span style="font-size: 1.5rem;">💡</span>
                    </div>
                    <div class="alert-content">
                        <h6 class="text-white">Conseil Pro</h6>
                        <p class="text-white" style="opacity: 0.9;">Complétez vos TP et projets pour valider vos compétences</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Quick Actions --}}
    <h2 class="section-title fade-in-up delay-6">
        <i class="fas fa-bolt" style="color: var(--color-warning);"></i>
        Actions rapides
    </h2>
    <p class="section-subtitle fade-in-up delay-6">
        Accédez rapidement à vos fonctionnalités essentielles
    </p>

    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6 fade-in-up delay-1">
            <a href="{{ route('design-graphique.profil.editer') }}" class="action-card">
                <div class="action-icon" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                    <i class="fas fa-user-edit text-white"></i>
                </div>
                <div class="action-title">Modifier Profil</div>
                <div class="action-subtitle">Mettre à jour vos informations</div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 fade-in-up delay-2">
            <a href="{{ route('design-graphique.documents.index') }}" class="action-card">
                <div class="action-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <i class="fas fa-folder-open text-white"></i>
                </div>
                <div class="action-title">Documents</div>
                <div class="action-subtitle">CV, lettres, réalisations</div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 fade-in-up delay-3">
            <a href="{{ route('design-graphique.parametres.index') }}" class="action-card">
                <div class="action-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                    <i class="fas fa-cog text-white"></i>
                </div>
                <div class="action-title">Paramètres</div>
                <div class="action-subtitle">Configuration du compte</div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 fade-in-up delay-4">
            <a href="#" class="action-card">
                <div class="action-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <i class="fas fa-chart-line text-white"></i>
                </div>
                <div class="action-title">Statistiques</div>
                <div class="action-subtitle">Suivi de progression</div>
            </a>
        </div>
    </div>

    {{-- Profile Info --}}
    <div class="row g-4">
        <div class="col-lg-8 fade-in-up delay-5">
            <h2 class="section-title">
                <i class="fas fa-info-circle" style="color: var(--color-info);"></i>
                Informations de formation
            </h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="info-badge">
                        <div class="info-icon" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                            <i class="fas fa-book text-white"></i>
                        </div>
                        <div>
                            <div class="info-label">Programme</div>
                            <div class="info-value">{{ $program ?: 'Design Graphique' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-badge">
                        <div class="info-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            <i class="fas fa-layer-group text-white"></i>
                        </div>
                        <div>
                            <div class="info-label">Niveau</div>
                            <div class="info-value">{{ $level ?: 'Débutant' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-badge">
                        <div class="info-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                            <i class="fas fa-id-card text-white"></i>
                        </div>
                        <div>
                            <div class="info-label">Matricule</div>
                            <div class="info-value">{{ $studentId ?: 'N/A' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-badge">
                        <div class="info-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <div>
                            <div class="info-label">Statut</div>
                            <div class="info-value">Actif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 fade-in-up delay-6">
            <h2 class="section-title">
                <i class="fas fa-trophy" style="color: var(--color-warning);"></i>
                Progression globale
            </h2>
            <div class="card-clean text-center">
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
                <div style="font-size: 4.5rem; font-weight: 900; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-success) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1;">
                    {{ round($globalProgress) }}%
                </div>
                <div class="card-subtitle mb-3">De votre formation complétée</div>
                <div class="progress-modern">
                    <div class="progress-fill" style="width: {{ $globalProgress }}%; background: linear-gradient(90deg, var(--color-primary) 0%, var(--color-success) 100%);"></div>
                </div>
                <p class="mt-3 mb-0" style="font-size: 0.875rem; color: var(--text-secondary);">
                    <i class="fas fa-fire" style="color: var(--color-danger);"></i>
                    Continuez comme ça, vous êtes sur la bonne voie !
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
