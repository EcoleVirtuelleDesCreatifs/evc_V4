@extends('layouts.ki-admin')

@section('title', 'Mon Profil Professionnel - CVThèque')
@section('page-title', 'Mon Profil CVThèque')

@php
    // Détecter la formation pour les routes
    $formation = session('user_formation', 'design-graphique');
    $routePrefix = match(strtolower($formation)) {
        'community management' => 'community-management',
        'gestion informatique' => 'gestion-informatique',
        'intelligence artificielle' => 'intelligence-artificielle',
        default => 'design-graphique'
    };
@endphp

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 50%, #fbcfe8 100%);
        min-height: 100vh;
    }

    .profile-wrapper {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    /* Carte principale avec effet glassmorphism */
    .profile-main-card {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border-radius: 30px;
        box-shadow: 0 20px 60px rgba(131, 58, 180, 0.15);
        overflow: hidden;
        margin-bottom: 2rem;
        border: 1px solid rgba(131, 58, 180, 0.1);
    }

    /* Header avec dégradé Instagram */
    .profile-hero {
        background: linear-gradient(135deg, #833AB4 0%, #C13584 50%, #E1306C 100%);
        padding: 3rem 2rem;
        position: relative;
        overflow: hidden;
    }

    .profile-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
        animation: pulse 4s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    .profile-hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .profile-avatar-large {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 5px solid white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        object-fit: cover;
    }

    .profile-avatar-placeholder {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 5px solid white;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: white;
        font-weight: 700;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .profile-hero-info h1 {
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .profile-hero-title {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.3rem;
        margin-bottom: 1rem;
    }

    .profile-hero-badges {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .hero-badge {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        color: white;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Grille de sections */
    .profile-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        padding: 2rem;
    }

    /* Carte de section */
    .section-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .section-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .section-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #833AB4 0%, #C13584 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 15px rgba(131, 58, 180, 0.3);
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    /* Liste de compétences avec badges */
    .skills-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .skill-badge {
        background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);
        padding: 0.6rem 1.2rem;
        border-radius: 15px;
        color: #4f46e5;
        font-weight: 600;
        border: 2px solid #e0e7ff;
        transition: all 0.3s ease;
    }

    .skill-badge:hover {
        background: linear-gradient(135deg, #833AB4 0%, #E1306C 100%);
        color: white;
        border-color: #833AB4;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(131, 58, 180, 0.3);
    }

    /* Informations de contact */
    .contact-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #f9fafb;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .contact-item:hover {
        background: #f0f4ff;
        transform: translateX(5px);
    }

    .contact-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #C13584 0%, #E1306C 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        flex-shrink: 0;
        box-shadow: 0 3px 10px rgba(193, 53, 132, 0.3);
    }

    .contact-info {
        flex: 1;
    }

    .contact-label {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }

    .contact-value {
        font-weight: 600;
        color: #1f2937;
        word-break: break-all;
    }

    .contact-value a {
        color: #C13584;
        text-decoration: none;
        font-weight: 600;
    }

    .contact-value a:hover {
        text-decoration: underline;
    }

    /* Documents */
    .documents-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }

    .document-item {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        padding: 1.5rem;
        border-radius: 15px;
        text-align: center;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .document-item:hover {
        border-color: #833AB4;
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(131, 58, 180, 0.2);
    }

    .document-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.8rem;
        margin: 0 auto 1rem;
    }

    .document-name {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .document-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }

    .badge-available {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-missing {
        background: #fee2e2;
        color: #991b1b;
    }

    .download-link {
        display: inline-block;
        margin-top: 0.75rem;
        padding: 0.5rem 1.5rem;
        background: linear-gradient(135deg, #833AB4 0%, #E1306C 100%);
        color: white;
        border-radius: 20px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .download-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(131, 58, 180, 0.4);
        color: white;
        background: linear-gradient(135deg, #C13584 0%, #F56040 100%);
    }

    /* Résumé professionnel */
    .professional-summary {
        background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%);
        padding: 1.5rem;
        border-radius: 15px;
        border-left: 4px solid #C13584;
        line-height: 1.8;
        color: #374151;
        box-shadow: 0 2px 10px rgba(193, 53, 132, 0.1);
    }

    /* Préférences */
    .preferences-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }

    .preference-item {
        background: #f9fafb;
        padding: 1rem;
        border-radius: 12px;
        text-align: center;
    }

    .preference-label {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .preference-value {
        font-weight: 700;
        color: #1f2937;
        font-size: 1.1rem;
    }

    .preference-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-yes {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .badge-no {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
    }

    /* Actions flottantes */
    .floating-actions {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        z-index: 1000;
    }

    .fab-button {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        text-decoration: none;
    }

    .fab-button:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4);
    }

    .fab-back {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .fab-edit {
        background: linear-gradient(135deg, #833AB4 0%, #E1306C 100%);
    }

    .fab-print {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-hero-content {
            flex-direction: column;
            text-align: center;
        }

        .profile-hero-info h1 {
            font-size: 2rem;
        }

        .profile-grid {
            grid-template-columns: 1fr;
            padding: 1rem;
        }

        .floating-actions {
            bottom: 1rem;
            right: 1rem;
        }

        .fab-button {
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
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

    .animate-in {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Print styles - CV Professionnel Classique */
    @media print {
        /* Reset et configuration de base */
        * {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        @page {
            size: A4;
            margin: 0;
        }

        body {
            background: white !important;
            margin: 0;
            padding: 0;
            font-family: 'Georgia', 'Times New Roman', serif;
            color: #2c3e50;
            font-size: 11pt;
            line-height: 1.6;
        }

        /* Cacher les éléments non nécessaires */
        .floating-actions,
        .profile-hero-badges,
        .section-icon,
        .fab-button,
        nav,
        .navbar,
        header,
        footer,
        .breadcrumb {
            display: none !important;
        }

        .profile-wrapper {
            max-width: 100%;
            padding: 0;
            background: white;
        }

        .profile-main-card {
            box-shadow: none !important;
            border-radius: 0 !important;
            background: white !important;
            padding: 0 !important;
            margin: 0 !important;
            page-break-after: avoid;
        }

        /* Header professionnel avec bande latérale */
        .profile-hero {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
            color: white !important;
            padding: 30pt 40pt !important;
            margin: 0 !important;
            border-radius: 0 !important;
            page-break-inside: avoid;
            position: relative;
        }

        .profile-hero::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 8pt;
            background: #3498db;
        }

        .profile-hero-content {
            display: flex !important;
            align-items: center !important;
            gap: 20pt !important;
        }

        .profile-avatar-large,
        .profile-avatar-placeholder {
            width: 90pt !important;
            height: 90pt !important;
            border-radius: 50% !important;
            border: 4pt solid white !important;
            flex-shrink: 0;
            object-fit: cover;
        }

        .profile-avatar-placeholder {
            background: #3498db !important;
            color: white !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 32pt !important;
            font-weight: bold !important;
        }

        .profile-hero-info h1 {
            font-size: 24pt !important;
            font-weight: 700 !important;
            margin: 0 0 6pt 0 !important;
            color: white !important;
            letter-spacing: 0.5pt;
            text-transform: uppercase;
        }

        .profile-hero-title {
            font-size: 14pt !important;
            font-weight: 400 !important;
            color: #ecf0f1 !important;
            margin: 0 !important;
            font-style: italic;
        }

        /* Grid layout professionnel */
        .profile-grid {
            display: block !important;
            padding: 30pt 40pt !important;
            background: white !important;
        }

        .section-card {
            page-break-inside: avoid;
            margin-bottom: 20pt !important;
            padding: 0 !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            background: white !important;
            border-left: 3pt solid #3498db !important;
            padding-left: 15pt !important;
        }

        .section-header {
            margin-bottom: 12pt !important;
            padding: 0 !important;
            background: none !important;
            border-bottom: 1pt solid #ecf0f1 !important;
            padding-bottom: 8pt !important;
        }

        .section-title {
            font-size: 16pt !important;
            font-weight: 700 !important;
            color: #2c3e50 !important;
            text-transform: uppercase !important;
            letter-spacing: 1pt !important;
            margin: 0 !important;
        }

        /* À propos section */
        .professional-summary {
            font-size: 11pt !important;
            line-height: 1.8 !important;
            color: #34495e !important;
            text-align: justify !important;
            margin: 12pt 0 !important;
        }

        /* Compétences - affichage élégant */
        .skills-grid {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 8pt !important;
            margin-top: 12pt !important;
        }

        .skill-item {
            background: #f8f9fa !important;
            padding: 6pt 10pt !important;
            border-radius: 4pt !important;
            border-left: 2pt solid #3498db !important;
            font-size: 10pt !important;
            color: #2c3e50 !important;
            box-shadow: none !important;
        }

        /* Documents */
        .documents-grid {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10pt !important;
        }

        .document-item {
            background: #f8f9fa !important;
            padding: 10pt !important;
            border-radius: 4pt !important;
            border-left: 3pt solid #3498db !important;
            page-break-inside: avoid;
        }

        .document-name {
            font-weight: 600 !important;
            color: #2c3e50 !important;
            font-size: 10pt !important;
        }

        .document-badge {
            font-size: 9pt !important;
            margin-top: 4pt !important;
        }

        .download-link {
            display: none !important;
        }

        /* Coordonnées et infos */
        .contact-grid,
        .preferences-grid {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 12pt !important;
            margin-top: 12pt !important;
        }

        .contact-item,
        .preference-item {
            padding: 8pt !important;
            background: #f8f9fa !important;
            border-radius: 4pt !important;
            border-left: 2pt solid #3498db !important;
        }

        .contact-label,
        .preference-label {
            font-size: 9pt !important;
            color: #7f8c8d !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5pt !important;
            margin-bottom: 4pt !important;
        }

        .contact-value,
        .preference-value {
            font-size: 11pt !important;
            color: #2c3e50 !important;
            font-weight: 600 !important;
        }

        /* Footer avec date de génération */
        .profile-main-card::after {
            content: "CV généré le " attr(data-date) " - École Virtuelle des Créatifs";
            display: block;
            text-align: center;
            font-size: 8pt;
            color: #95a5a6;
            padding: 15pt 0;
            border-top: 1pt solid #ecf0f1;
            margin-top: 20pt;
        }

        /* Icônes dans le texte */
        .fas, .fab {
            font-weight: normal !important;
            margin-right: 6pt;
            color: #3498db !important;
        }

        /* Links */
        a {
            color: #3498db !important;
            text-decoration: none !important;
        }

        a:after {
            content: " (" attr(href) ")";
            font-size: 9pt;
            color: #7f8c8d;
        }

        /* Page breaks */
        .section-card {
            page-break-inside: avoid;
        }

        h1, h2, h3 {
            page-break-after: avoid;
        }
    }
</style>
@endpush

@section('content')
<div class="profile-wrapper">
    <!-- Carte principale -->
    <div class="profile-main-card animate-in" data-date="{{ date('d/m/Y') }}">
        <!-- Hero Section -->
        <div class="profile-hero">
            <div class="profile-hero-content">
                <div>
                    @if(!empty($userInfo->profile_photo))
                        @php
                            // Vérifier si le chemin commence déjà par 'students/photos/'
                            $photoPath = $userInfo->profile_photo;
                            if (!str_starts_with($photoPath, 'students/photos/')) {
                                $photoPath = 'students/photos/' . $photoPath;
                            }
                        @endphp
                        <img src="{{ \App\Models\MediaUrl::fromPath($photoPath) }}"
                             alt="{{ $userInfo->first_name }}"
                             class="profile-avatar-large"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="profile-avatar-placeholder" style="display: none;">
                            {{ strtoupper(substr($userInfo->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($userInfo->last_name ?? 'S', 0, 1)) }}
                        </div>
                    @else
                        <div class="profile-avatar-placeholder">
                            {{ strtoupper(substr($userInfo->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($userInfo->last_name ?? 'S', 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="profile-hero-info">
                    <h1>{{ $userInfo->first_name ?? 'Prénom' }} {{ $userInfo->last_name ?? 'Nom' }}</h1>
                    <p class="profile-hero-title">{{ $cvthequeProfile->professional_title ?? $userInfo->program ?? 'Étudiant' }}</p>

                    <div class="profile-hero-badges">
                        <div class="hero-badge">
                            <i class="fas fa-briefcase"></i>
                            {{ $cvthequeProfile->experience_years ?? 0 }} ans d'expérience
                        </div>
                        <div class="hero-badge">
                            <i class="fas fa-chart-line"></i>
                            Profil {{ $completionScore }}% complet
                        </div>
                        <div class="hero-badge">
                            <i class="fas fa-graduation-cap"></i>
                            {{ $userInfo->program ?? 'Formation' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grille de sections -->
        <div class="profile-grid">
            <!-- À propos -->
            @if($cvthequeProfile->summary)
            <div class="section-card" style="grid-column: 1 / -1;">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2 class="section-title">À propos de moi</h2>
                </div>
                <div class="professional-summary">
                    {{ $cvthequeProfile->summary }}
                </div>
            </div>
            @elseif($userInfo->biography)
            <div class="section-card" style="grid-column: 1 / -1;">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2 class="section-title">À propos de moi</h2>
                </div>
                <div class="professional-summary">
                    {{ $userInfo->biography }}
                </div>
            </div>
            @endif

            <!-- Compétences -->
            @if(!empty($cvthequeProfile->skills))
            <div class="section-card" style="grid-column: 1 / -1;">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h2 class="section-title">Mes compétences</h2>
                </div>
                <div class="skills-list">
                    @php
                        $skillsArray = is_string($cvthequeProfile->skills)
                            ? json_decode($cvthequeProfile->skills, true)
                            : $cvthequeProfile->skills;
                    @endphp
                    @if(is_array($skillsArray))
                        @foreach($skillsArray as $skill)
                        <span class="skill-badge">
                            <i class="fas fa-check-circle me-1"></i>
                            {{ is_string($skill) ? $skill : ($skill['name'] ?? 'Compétence') }}
                        </span>
                        @endforeach
                    @endif
                </div>
            </div>
            @endif

            <!-- Informations académiques -->
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h2 class="section-title">Formation</h2>
                </div>
                <div class="skills-list">
                    @if($userInfo->program)
                    <span class="skill-badge">
                        <i class="fas fa-book me-1"></i>
                        {{ $userInfo->program }}
                    </span>
                    @endif
                    @if($userInfo->current_level)
                    <span class="skill-badge">
                        <i class="fas fa-layer-group me-1"></i>
                        Niveau {{ $userInfo->current_level }}
                    </span>
                    @endif
                    @if($userInfo->education_level)
                    <span class="skill-badge">
                        <i class="fas fa-certificate me-1"></i>
                        {{ $userInfo->education_level }}
                    </span>
                    @endif
                </div>
            </div>

            <!-- Contact -->
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-address-card"></i>
                    </div>
                    <h2 class="section-title">Coordonnées</h2>
                </div>
                <div class="contact-list">
                    @if($userInfo->email)
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-info">
                            <div class="contact-label">Email</div>
                            <div class="contact-value">
                                <a href="mailto:{{ $userInfo->email }}">
                                    {{ $userInfo->email }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($userInfo->phone)
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-info">
                            <div class="contact-label">Téléphone</div>
                            <div class="contact-value">{{ $userInfo->phone }}</div>
                        </div>
                    </div>
                    @endif

                    @if($userInfo->whatsapp)
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="contact-info">
                            <div class="contact-label">WhatsApp</div>
                            <div class="contact-value">{{ $userInfo->whatsapp }}</div>
                        </div>
                    </div>
                    @endif

                    @if($userInfo->city || $userInfo->country)
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-info">
                            <div class="contact-label">Localisation</div>
                            <div class="contact-value">
                                {{ $userInfo->city }}{{ $userInfo->city && $userInfo->country ? ', ' : '' }}{{ $userInfo->country }}
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($cvthequeProfile->portfolio_url)
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="contact-info">
                            <div class="contact-label">Portfolio</div>
                            <div class="contact-value">
                                <a href="{{ $cvthequeProfile->portfolio_url }}" target="_blank">Voir mon portfolio</a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Documents -->
            <div class="section-card" style="grid-column: 1 / -1;">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h2 class="section-title">Mes documents</h2>
                </div>
                <div class="documents-grid">
                    @php
                        $documentsToShow = [
                            [
                                'name' => 'CV',
                                'icon' => 'fa-file-pdf',
                                'color' => 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
                                'file' => $cvthequeProfile->cv_file_path ?? null
                            ],
                            [
                                'name' => 'Lettre de motivation',
                                'icon' => 'fa-envelope',
                                'color' => 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
                                'file' => $cvthequeProfile->motivation_letter_path ?? null
                            ],
                            [
                                'name' => 'Pressbook',
                                'icon' => 'fa-book',
                                'color' => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                                'file' => $cvthequeProfile->pressbook_file_path ?? null
                            ],
                            [
                                'name' => 'Rapport de formation',
                                'icon' => 'fa-graduation-cap',
                                'color' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                                'file' => $cvthequeProfile->report_file_path ?? null
                            ]
                        ];
                    @endphp

                    @foreach($documentsToShow as $doc)
                    <div class="document-item">
                        <div class="document-icon" style="background: {{ $doc['color'] }};">
                            <i class="fas {{ $doc['icon'] }}"></i>
                        </div>
                        <div class="document-name">{{ $doc['name'] }}</div>
                        @if(!empty($doc['file']))
                            <span class="document-badge badge-available">✓ Disponible</span>
                            <a href="{{ \App\Models\MediaUrl::fromPath($doc['file']) }}"
                               class="download-link"
                               target="_blank"
                               download>
                                <i class="fas fa-download me-1"></i>Télécharger
                            </a>
                        @else
                            <span class="document-badge badge-missing">Non ajouté</span>
                            <a href="{{ route($routePrefix . '.cvtheque.index') }}"
                               class="download-link"
                               style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);">
                                <i class="fas fa-plus me-1"></i>Ajouter
                            </a>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Disponibilité et Expérience -->
            @if($cvthequeProfile->availability || $cvthequeProfile->experience_years > 0)
            <div class="section-card" style="grid-column: 1 / -1;">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h2 class="section-title">Informations professionnelles</h2>
                </div>
                <div class="preferences-grid">
                    @if($cvthequeProfile->experience_years > 0)
                    <div class="preference-item">
                        <div class="preference-label">Expérience</div>
                        <div class="preference-value">{{ $cvthequeProfile->experience_years }} {{ $cvthequeProfile->experience_years > 1 ? 'ans' : 'an' }}</div>
                    </div>
                    @endif
                    @if($cvthequeProfile->availability)
                    <div class="preference-item">
                        <div class="preference-label">Disponibilité</div>
                        <div class="preference-value">{{ $cvthequeProfile->availability }}</div>
                    </div>
                    @endif
                    @if(isset($userInfo->age) && $userInfo->age)
                    <div class="preference-item">
                        <div class="preference-label">Âge</div>
                        <div class="preference-value">{{ $userInfo->age }} ans</div>
                    </div>
                    @endif
                    @if(isset($userInfo->gender) && $userInfo->gender)
                    <div class="preference-item">
                        <div class="preference-label">Genre</div>
                        <div class="preference-value">{{ ucfirst($userInfo->gender) }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Actions flottantes -->
<div class="floating-actions">
    <a href="{{ route($routePrefix . '.cvtheque.index') }}" class="fab-button fab-back" title="Retour">
        <i class="fas fa-arrow-left"></i>
    </a>
    <button onclick="window.print()" class="fab-button fab-print" title="Imprimer">
        <i class="fas fa-print"></i>
    </button>
    <a href="{{ route($routePrefix . '.cvtheque.index') }}" class="fab-button fab-edit" title="Modifier">
        <i class="fas fa-edit"></i>
    </a>
</div>
@endsection

@push('scripts')
<script>
    // Animation au scroll
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.section-card');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeInUp 0.6s ease-out';
                }
            });
        }, { threshold: 0.1 });

        cards.forEach(card => observer.observe(card));
    });

    // Style d'impression
    window.addEventListener('beforeprint', function() {
        document.querySelector('.floating-actions').style.display = 'none';
    });

    window.addEventListener('afterprint', function() {
        document.querySelector('.floating-actions').style.display = 'flex';
    });
</script>
@endpush
