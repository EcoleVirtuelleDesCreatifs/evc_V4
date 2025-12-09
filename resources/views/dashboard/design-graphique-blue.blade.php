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
        /* Blue Professional Palette */
        --color-primary: #2D4A7C;
        --color-primary-light: #3D5A8C;
        --color-primary-lighter: #4D6A9C;
        --color-accent: #5B7FC7;
        --color-success: #10b981;
        --color-warning: #f59e0b;
        --color-danger: #ef4444;

        /* Backgrounds */
        --bg-primary: #0f1419;
        --bg-secondary: #1a1f2e;
        --bg-card: rgba(45, 74, 124, 0.05);
        --bg-hover: rgba(45, 74, 124, 0.1);

        /* Text */
        --text-primary: #ffffff;
        --text-secondary: rgba(255, 255, 255, 0.75);
        --text-muted: rgba(255, 255, 255, 0.5);

        /* Borders */
        --border: rgba(45, 74, 124, 0.15);
        --border-hover: rgba(45, 74, 124, 0.3);

        /* Radius */
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --radius-xl: 20px;

        /* Shadows */
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.4);
        --shadow-blue: 0 8px 16px rgba(45, 74, 124, 0.3);
    }

    body {
        background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 100%);
        min-height: 100vh;
        color: var(--text-primary);
    }

    .container-pro {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1.5rem;
    }

    /* Header Professional */
    .header-pro {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-lighter) 100%);
        border-radius: var(--radius-lg);
        padding: 2.5rem 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-blue);
    }

    .header-pro::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
        pointer-events: none;
    }

    .avatar-pro {
        width: 100px;
        height: 100px;
        border-radius: var(--radius-lg);
        object-fit: cover;
        border: 3px solid rgba(255, 255, 255, 0.25);
        box-shadow: var(--shadow-lg);
    }

    .name-pro {
        font-size: 1.875rem;
        font-weight: 700;
        line-height: 1.2;
        margin: 0 0 0.375rem 0;
    }

    .role-pro {
        font-size: 1rem;
        opacity: 0.9;
        font-weight: 500;
    }

    .badge-pro {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(255, 255, 255, 0.12);
        border-radius: 100px;
        font-size: 0.875rem;
        font-weight: 500;
        border: 1px solid rgba(255, 255, 255, 0.15);
        transition: all 0.2s;
        text-decoration: none;
        color: white;
    }

    .badge-pro:hover {
        background: rgba(255, 255, 255, 0.18);
        transform: translateY(-1px);
        color: white;
    }

    /* Card Professional */
    .card-pro {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        transition: all 0.2s;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .card-pro::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--color-primary) 0%, var(--color-accent) 100%);
        transform: scaleX(0);
        transition: transform 0.3s;
    }

    .card-pro:hover::before {
        transform: scaleX(1);
    }

    .card-pro:hover {
        background: var(--bg-hover);
        border-color: var(--border-hover);
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }

    .card-icon-pro {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-accent) 100%);
    }

    .card-title-pro {
        font-size: 0.8125rem;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.5rem;
    }

    .card-value-pro {
        font-size: 2.25rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    .card-subtitle-pro {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    /* Progress Bar Professional */
    .progress-pro {
        height: 6px;
        background: rgba(45, 74, 124, 0.15);
        border-radius: 100px;
        overflow: hidden;
        margin-top: 0.75rem;
    }

    .progress-fill-pro {
        height: 100%;
        border-radius: 100px;
        background: linear-gradient(90deg, var(--color-primary) 0%, var(--color-accent) 100%);
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Countdown Card Professional */
    .countdown-pro {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-lighter) 100%);
        border-radius: var(--radius-lg);
        padding: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-blue);
    }

    .countdown-pro::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    }

    .countdown-number-pro {
        font-size: 3.5rem;
        font-weight: 900;
        line-height: 1;
        color: white;
    }

    .countdown-label-pro {
        font-size: 1rem;
        opacity: 0.9;
        margin-top: 0.5rem;
        font-weight: 500;
        color: white;
    }

    /* Button Professional */
    .btn-pro {
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

    .btn-pro:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        color: var(--color-primary);
    }

    .btn-outline-pro {
        background: transparent;
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .btn-outline-pro:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
    }

    /* Action Card Professional */
    .action-pro {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        text-align: center;
        transition: all 0.2s;
        cursor: pointer;
        text-decoration: none;
        display: block;
        color: inherit;
    }

    .action-pro:hover {
        background: var(--bg-hover);
        border-color: var(--color-primary);
        transform: translateY(-4px);
        box-shadow: var(--shadow-blue);
    }

    .action-icon-pro {
        width: 64px;
        height: 64px;
        margin: 0 auto 1rem;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-accent) 100%);
        transition: all 0.3s;
    }

    .action-pro:hover .action-icon-pro {
        transform: scale(1.08);
    }

    .action-title-pro {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.375rem;
        color: var(--text-primary);
    }

    .action-subtitle-pro {
        font-size: 0.8125rem;
        color: var(--text-muted);
    }

    /* Stats Grid */
    .stats-grid-pro {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    /* Info Badge Professional */
    .info-badge-pro {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        transition: all 0.2s;
    }

    .info-badge-pro:hover {
        background: var(--bg-hover);
        border-color: var(--border-hover);
    }

    .info-icon-badge {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.125rem;
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-accent) 100%);
        flex-shrink: 0;
    }

    .info-label-badge {
        font-size: 0.6875rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.25rem;
        font-weight: 600;
    }

    .info-value-badge {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.9375rem;
    }

    /* Section Title Professional */
    .section-title-pro {
        font-size: 1.375rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-subtitle-pro {
        font-size: 0.9375rem;
        color: var(--text-secondary);
        margin-bottom: 1.75rem;
    }

    /* Alert Professional */
    .alert-pro {
        background: rgba(45, 74, 124, 0.12);
        border: 1px solid rgba(45, 74, 124, 0.3);
        border-left: 4px solid var(--color-primary);
        border-radius: var(--radius-lg);
        padding: 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .alert-icon-pro {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-md);
        background: var(--color-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .alert-content-pro h6 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.375rem;
    }

    .alert-content-pro p {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
        line-height: 1.5;
    }

    /* Divider */
    .divider-pro {
        height: 1px;
        background: var(--border);
        margin: 2rem 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container-pro {
            padding: 1rem;
        }

        .header-pro {
            padding: 1.75rem 1.25rem;
        }

        .avatar-pro {
            width: 80px;
            height: 80px;
        }

        .name-pro {
            font-size: 1.5rem;
        }

        .card-value-pro {
            font-size: 1.875rem;
        }

        .countdown-number-pro {
            font-size: 2.5rem;
        }

        .stats-grid-pro {
            grid-template-columns: 1fr;
        }
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
    }

    .delay-1 { animation-delay: 0.05s; opacity: 0; }
    .delay-2 { animation-delay: 0.1s; opacity: 0; }
    .delay-3 { animation-delay: 0.15s; opacity: 0; }
    .delay-4 { animation-delay: 0.2s; opacity: 0; }
    .delay-5 { animation-delay: 0.25s; opacity: 0; }
    .delay-6 { animation-delay: 0.3s; opacity: 0; }
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

<div class="container-pro">

    {{-- Header Professional --}}
    <div class="header-pro fade-in-up">
        <div class="row align-items-center position-relative" style="z-index: 1;">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-4 flex-column flex-lg-row text-center text-lg-start">
                    <img src="{{ $photoUrl }}" alt="{{ $fullName }}" class="avatar-pro"
                         onerror="this.src='{{ asset('assets/img/avatar.png') }}'">
                    <div>
                        <h1 class="name-pro text-white">
                            {{ $fullName ?: 'Étudiant EVC' }}
                        </h1>
                        <p class="role-pro text-white mb-3">
                            {{ $program ?: 'Design Graphique' }}
                            @if($level)
                                <span class="mx-2">•</span>{{ $level }}
                            @endif
                        </p>
                        <div class="d-flex gap-2 flex-wrap justify-content-center justify-content-lg-start">
                            @if($email)
                            <a href="mailto:{{ $email }}" class="badge-pro">
                                <i class="fas fa-envelope"></i>
                                <span>{{ $email }}</span>
                            </a>
                            @endif
                            @if($phone)
                            <a href="tel:{{ $phone }}" class="badge-pro">
                                <i class="fas fa-phone"></i>
                                <span>{{ $phone }}</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center text-lg-end mt-4 mt-lg-0">
                <a href="{{ route('design-graphique.profil.editer') }}" class="btn-pro">
                    <i class="fas fa-edit"></i>
                    <span>Modifier mon profil</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Statistics Grid --}}
    <div class="stats-grid-pro">
        <div class="card-pro fade-in-up delay-1">
            <div class="card-icon-pro">
                <i class="fas fa-graduation-cap text-white"></i>
            </div>
            <div class="card-title-pro">Formations</div>
            <div class="card-value-pro">{{ $stats['formations_disponibles'] ?? 0 }}</div>
            <div class="card-subtitle-pro">Programmes disponibles</div>
        </div>

        <div class="card-pro fade-in-up delay-2">
            <div class="card-icon-pro">
                <i class="fas fa-tasks text-white"></i>
            </div>
            <div class="card-title-pro">Travaux Pratiques</div>
            <div class="card-value-pro">
                {{ $stats['tp_realises'] ?? 0 }}<span style="font-size: 1.25rem; color: var(--text-muted);">/{{ $stats['tp_total'] ?? 0 }}</span>
            </div>
            @if(($stats['tp_total'] ?? 0) > 0)
            <div class="progress-pro">
                <div class="progress-fill-pro" style="width: {{ round(($stats['tp_realises'] / $stats['tp_total']) * 100) }}%;"></div>
            </div>
            <div class="card-subtitle-pro mt-2">{{ round(($stats['tp_realises'] / $stats['tp_total']) * 100) }}% complétés</div>
            @endif
        </div>

        <div class="card-pro fade-in-up delay-3">
            <div class="card-icon-pro">
                <i class="fas fa-project-diagram text-white"></i>
            </div>
            <div class="card-title-pro">Projets</div>
            <div class="card-value-pro">
                {{ $stats['projets_realises'] ?? 0 }}<span style="font-size: 1.25rem; color: var(--text-muted);">/{{ $stats['projets_total'] ?? 0 }}</span>
            </div>
            @if(($stats['projets_total'] ?? 0) > 0)
            <div class="progress-pro">
                <div class="progress-fill-pro" style="width: {{ round(($stats['projets_realises'] / $stats['projets_total']) * 100) }}%;"></div>
            </div>
            <div class="card-subtitle-pro mt-2">{{ round(($stats['projets_realises'] / $stats['projets_total']) * 100) }}% réalisés</div>
            @endif
        </div>

        <div class="card-pro fade-in-up delay-4">
            <div class="card-icon-pro">
                <i class="fas fa-calendar-alt text-white"></i>
            </div>
            <div class="card-title-pro">Événements</div>
            <div class="row g-0 mt-3">
                <div class="col-6 text-center">
                    <div style="font-size: 1.625rem; font-weight: 700;">{{ $stats['webinaires_en_cours'] ?? 0 }}</div>
                    <div class="card-subtitle-pro">Webinaires</div>
                </div>
                <div class="col-6 text-center" style="border-left: 1px solid var(--border);">
                    <div style="font-size: 1.625rem; font-weight: 700;">{{ $stats['actualites_en_cours'] ?? 0 }}</div>
                    <div class="card-subtitle-pro">Actualités</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Countdown Section --}}
    @if(!$isExpired)
    <div class="countdown-pro fade-in-up delay-5 mb-4">
        <div class="row align-items-center position-relative" style="z-index: 1;">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span style="font-size: 2.25rem;">⏰</span>
                    <div>
                        <h3 class="text-white mb-1" style="font-size: 1.5rem; font-weight: 700;">
                            Votre formation expire bientôt
                        </h3>
                        <p class="text-white mb-0" style="opacity: 0.9; font-size: 0.9375rem;">
                            Maximisez votre apprentissage avant la fin de votre accès
                        </p>
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-md-4 text-center text-lg-start">
                        <div class="countdown-number-pro">{{ $daysRemaining }}</div>
                        <div class="countdown-label-pro">jours restants</div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="text-white" style="font-size: 0.8125rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; font-weight: 600;">Expire le</div>
                        <div class="text-white" style="font-size: 1.375rem; font-weight: 700;">{{ $expirationDate->format('d/m/Y') }}</div>
                        <div class="text-white" style="opacity: 0.8;">{{ $expirationDate->format('H:i') }}</div>
                    </div>
                    <div class="col-md-4">
                        @php
                            $totalDays = 120;
                            $progress = ($daysRemaining / $totalDays) * 100;
                        @endphp
                        <div class="text-white" style="font-size: 0.8125rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; font-weight: 600;">Progression</div>
                        <div class="progress-pro" style="background: rgba(255, 255, 255, 0.15); height: 8px;">
                            <div class="progress-fill-pro" style="width: {{ $progress }}%; background: white;"></div>
                        </div>
                        <div class="text-white mt-2" style="font-weight: 600; font-size: 0.9375rem;">{{ round($progress) }}% de votre formation</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center mt-4 mt-lg-0">
                <div class="alert-pro">
                    <div class="alert-icon-pro">
                        <span style="font-size: 1.25rem;">💡</span>
                    </div>
                    <div class="alert-content-pro">
                        <h6 class="text-white">Conseil Pro</h6>
                        <p class="text-white" style="opacity: 0.9;">Complétez vos TP et projets pour valider vos compétences</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Quick Actions --}}
    <h2 class="section-title-pro fade-in-up delay-6">
        <i class="fas fa-bolt" style="color: var(--color-accent);"></i>
        Actions rapides
    </h2>
    <p class="section-subtitle-pro fade-in-up delay-6">
        Accédez rapidement à vos fonctionnalités essentielles
    </p>

    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6 fade-in-up delay-1">
            <a href="{{ route('design-graphique.profil.editer') }}" class="action-pro">
                <div class="action-icon-pro">
                    <i class="fas fa-user-edit text-white"></i>
                </div>
                <div class="action-title-pro">Modifier Profil</div>
                <div class="action-subtitle-pro">Mettre à jour vos informations</div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 fade-in-up delay-2">
            <a href="{{ route('design-graphique.documents.index') }}" class="action-pro">
                <div class="action-icon-pro">
                    <i class="fas fa-folder-open text-white"></i>
                </div>
                <div class="action-title-pro">Documents</div>
                <div class="action-subtitle-pro">CV, lettres, réalisations</div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 fade-in-up delay-3">
            <a href="{{ route('design-graphique.parametres.index') }}" class="action-pro">
                <div class="action-icon-pro">
                    <i class="fas fa-cog text-white"></i>
                </div>
                <div class="action-title-pro">Paramètres</div>
                <div class="action-subtitle-pro">Configuration du compte</div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 fade-in-up delay-4">
            <a href="#" class="action-pro">
                <div class="action-icon-pro">
                    <i class="fas fa-chart-line text-white"></i>
                </div>
                <div class="action-title-pro">Statistiques</div>
                <div class="action-subtitle-pro">Suivi de progression</div>
            </a>
        </div>
    </div>

    <div class="divider-pro"></div>

    {{-- Profile Info --}}
    <div class="row g-4">
        <div class="col-lg-8 fade-in-up delay-5">
            <h2 class="section-title-pro">
                <i class="fas fa-info-circle" style="color: var(--color-accent);"></i>
                Informations de formation
            </h2>
            <p class="section-subtitle-pro">
                Détails de votre parcours académique
            </p>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="info-badge-pro">
                        <div class="info-icon-badge">
                            <i class="fas fa-book text-white"></i>
                        </div>
                        <div>
                            <div class="info-label-badge">Programme</div>
                            <div class="info-value-badge">{{ $program ?: 'Design Graphique' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-badge-pro">
                        <div class="info-icon-badge">
                            <i class="fas fa-layer-group text-white"></i>
                        </div>
                        <div>
                            <div class="info-label-badge">Niveau</div>
                            <div class="info-value-badge">{{ $level ?: 'Débutant' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-badge-pro">
                        <div class="info-icon-badge">
                            <i class="fas fa-id-card text-white"></i>
                        </div>
                        <div>
                            <div class="info-label-badge">Matricule</div>
                            <div class="info-value-badge">{{ $studentId ?: 'N/A' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-badge-pro">
                        <div class="info-icon-badge">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <div>
                            <div class="info-label-badge">Statut</div>
                            <div class="info-value-badge">Actif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 fade-in-up delay-6">
            <h2 class="section-title-pro">
                <i class="fas fa-trophy" style="color: var(--color-warning);"></i>
                Progression globale
            </h2>
            <p class="section-subtitle-pro">
                Votre avancement total
            </p>
            <div class="card-pro text-center">
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
                <div style="font-size: 4rem; font-weight: 900; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-accent) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1;">
                    {{ round($globalProgress) }}%
                </div>
                <div class="card-subtitle-pro mb-3">De votre formation complétée</div>
                <div class="progress-pro" style="height: 8px;">
                    <div class="progress-fill-pro" style="width: {{ $globalProgress }}%;"></div>
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
