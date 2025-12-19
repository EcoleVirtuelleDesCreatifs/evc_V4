@extends('layouts.ki-admin')

@section('title', 'Paramètres - Mon Profil')
@section('page-title', 'Paramètres')

@section('styles')
<style>
    /* PALETTE INSTAGRAM */
    :root {
        --ig-purple: #833AB4;
        --ig-pink: #C13584;
        --ig-red: #E1306C;
        --ig-orange: #F56040;
        --ig-yellow: #FCAF45;
    }

    body {
        background: linear-gradient(135deg, #fef9f8 0%, #fdf4f5 50%, #f8f9fa 100%);
        font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', sans-serif;
        min-height: 100vh;
        overflow-x: hidden;
    }

    .container-fluid {
        overflow-x: hidden;
    }

    /* HEADER PERCUTANT */
    .profile-hero {
        background: linear-gradient(135deg, var(--ig-purple), var(--ig-pink), var(--ig-red));
        border-radius: 30px;
        padding: 3rem;
        color: white;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(131, 58, 180, 0.4);
    }

    .profile-hero::before {
        content: '';
        position: absolute;
        width: 500px;
        height: 500px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        top: -250px;
        right: -150px;
        animation: pulse 8s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1) rotate(0deg); }
        50% { transform: scale(1.1) rotate(180deg); }
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .hero-avatar {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        border: 6px solid white;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        object-fit: cover;
    }

    /* SECTIONS MODERNES */
    .modern-section {
        background: white;
        border-radius: 25px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modern-section:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 50px rgba(131, 58, 180, 0.15);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        margin-bottom: 2rem;
        padding-bottom: 1.25rem;
        border-bottom: 3px solid #f7fafc;
    }

    .section-icon {
        width: 60px;
        height: 60px;
        border-radius: 18px;
        background: linear-gradient(135deg, var(--ig-purple), var(--ig-red));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: white;
        box-shadow: 0 8px 20px rgba(131, 58, 180, 0.35);
    }

    .section-title {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--ig-purple), var(--ig-red));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* GRILLE DE CHAMPS */
    .fields-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.75rem;
    }

    /* INPUTS MODERNES */
    .input-group-modern {
        position: relative;
    }

    .input-label {
        display: block;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .input-field {
        width: 100%;
        padding: 1rem 1.25rem;
        border: 2px solid #e2e8f0;
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #fafafa;
    }

    .input-field:focus {
        outline: none;
        border-color: var(--ig-pink);
        background: white;
        box-shadow: 0 0 0 5px rgba(193, 53, 132, 0.1);
        transform: translateY(-2px);
    }

    .input-field:hover {
        border-color: var(--ig-pink);
        background: white;
    }

    /* BOUTONS PERCUTANTS */
    .btn-instagram {
        background: linear-gradient(135deg, var(--ig-purple), var(--ig-red));
        color: white;
        border: none;
        border-radius: 50px;
        padding: 1rem 2.5rem;
        font-weight: 700;
        font-size: 1.05rem;
        box-shadow: 0 8px 25px rgba(131, 58, 180, 0.4);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
    }

    .btn-instagram:hover {
        background: linear-gradient(135deg, var(--ig-pink), var(--ig-orange));
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(193, 53, 132, 0.5);
        color: white;
    }

    .btn-instagram:active {
        transform: translateY(-1px);
    }

    .btn-outline-instagram {
        background: white;
        color: var(--ig-pink);
        border: 2px solid var(--ig-pink);
        border-radius: 50px;
        padding: 1rem 2.5rem;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .btn-outline-instagram:hover {
        background: var(--ig-pink);
        color: white;
        transform: translateY(-3px);
    }

    /* PHOTO UPLOAD */
    .photo-zone {
        border: 3px dashed #e2e8f0;
        border-radius: 25px;
        padding: 2.5rem;
        text-align: center;
        background: linear-gradient(135deg, rgba(131, 58, 180, 0.03), rgba(225, 48, 108, 0.03));
        transition: all 0.3s ease;
    }

    .photo-zone:hover {
        border-color: var(--ig-pink);
        background: linear-gradient(135deg, rgba(131, 58, 180, 0.08), rgba(225, 48, 108, 0.08));
        transform: scale(1.02);
    }

    .photo-current {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        object-fit: cover;
        border: 6px solid transparent;
        background-image: linear-gradient(white, white),
                          linear-gradient(135deg, var(--ig-purple), var(--ig-red));
        background-origin: border-box;
        background-clip: padding-box, border-box;
        box-shadow: 0 15px 40px rgba(131, 58, 180, 0.3);
        margin: 0 auto 1.5rem;
    }

    /* SIDEBAR */
    .completion-widget {
        background: white;
        border-radius: 25px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        text-align: center;
        position: sticky;
        top: 20px;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }

    .completion-circle {
        width: 160px;
        height: 160px;
        margin: 0 auto 1.5rem;
        position: relative;
    }

    .completion-number {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--ig-purple), var(--ig-red));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 12px;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        background: linear-gradient(135deg, rgba(131, 58, 180, 0.1), rgba(225, 48, 108, 0.1));
        transform: translateX(5px);
    }

    .badge-modern {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .badge-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .badge-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    /* ANIMATIONS */
    .fade-in {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }

    /* ALERT MODERNE */
    .alert-instagram {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border: none;
        border-left: 5px solid var(--ig-orange);
        border-radius: 15px;
        padding: 1.25rem;
        color: #92400e;
        font-weight: 500;
    }

    .alert-success-instagram {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        border-left-color: #10b981;
        color: #065f46;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        /* Layout général */
        .container-fluid {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        .col-lg-8, .col-lg-4 {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        /* Hero Section */
        .profile-hero {
            padding: 1.5rem !important;
            border-radius: 20px;
            margin-bottom: 1.5rem;
        }
        .hero-avatar {
            width: 80px !important;
            height: 80px !important;
            border-width: 3px !important;
        }
        .profile-hero h1 {
            font-size: 1.6rem !important;
        }
        .profile-hero .badge {
            font-size: 0.75rem !important;
            padding: 0.4rem 0.8rem !important;
        }

        /* Cards & Sections */
        .modern-section, .completion-widget {
            padding: 1.25rem !important;
            border-radius: 15px !important;
            margin-bottom: 1rem !important;
        }
        .completion-widget {
            position: static !important;
            width: 100% !important;
        }
        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        .section-icon {
            width: 50px;
            height: 50px;
            font-size: 1.4rem;
        }
        .section-title {
            font-size: 1.3rem;
        }

        /* Forms & Inputs */
        .fields-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        .input-field {
            padding: 0.8rem 1rem;
        }

        /* Buttons & Actions */
        .btn-instagram, .btn-outline-instagram {
            width: 100%;
            justify-content: center;
            padding: 0.8rem 1rem;
            font-size: 0.95rem;
        }
        .d-flex.justify-content-between.align-items-center.flex-wrap.gap-3 {
            flex-direction: column;
            align-items: stretch !important;
            gap: 1rem !important;
        }
        .d-flex.gap-3.flex-wrap {
            flex-direction: column;
            width: 100%;
        }

        /* Photo Zone */
        .photo-zone {
            padding: 1.5rem;
        }
        .photo-current {
            width: 120px;
            height: 120px;
            margin-bottom: 1rem;
        }
        .photo-zone .row {
            flex-direction: column;
            text-align: center;
        }
        .photo-zone .col-md-4, .photo-zone .col-md-8 {
            width: 100%;
        }

        /* Notifications */
        .notification-container {
            left: 10px;
            right: 10px;
            top: 10px;
            max-width: none;
        }
        .notification {
            padding: 1rem;
            margin-bottom: 0.5rem;
        }
    }

    /* Fix pour les boutons dans les widgets */
    .completion-widget .btn-instagram,
    .completion-widget .btn-outline-instagram {
        max-width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* NOTIFICATIONS MODERNES */
    .notification-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
    }

    .notification {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        border-left: 6px solid;
        animation: slideInRight 0.4s ease-out;
        display: flex;
        align-items: start;
        gap: 1rem;
    }

    .notification.success {
        border-left-color: #10b981;
    }

    .notification.error {
        border-left-color: #ef4444;
    }

    .notification.warning {
        border-left-color: #f59e0b;
    }

    .notification-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .notification.success .notification-icon {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .notification.error .notification-icon {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .notification.warning .notification-icon {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .notification-content {
        flex: 1;
    }

    .notification-title {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 0.25rem;
        color: #1e293b;
    }

    .notification-message {
        color: #64748b;
        font-size: 0.95rem;
    }

    .notification-close {
        background: none;
        border: none;
        color: #94a3b8;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .notification-close:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        to {
            opacity: 0;
            transform: translateX(400px);
        }
    }

    .notification.fade-out {
        animation: fadeOut 0.3s ease-out forwards;
    }
</style>
@endsection

@section('content')
<!-- Conteneur de notifications -->
<div class="notification-container" id="notificationContainer"></div>

<div class="container-fluid" style="padding-left: 1.5rem; padding-right: 1.5rem; max-width: 100%; overflow-x: hidden;">

    <!-- HERO HEADER -->
    <div class="profile-hero fade-in">
        <div class="hero-content">
            <div class="row align-items-center">
                <div class="col-auto">
                    @php
                        $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrl($user->profile_photo ?? null);
                    @endphp
                    @if($photoUrl)
                        <img src="{{ $photoUrl }}" alt="Avatar" class="hero-avatar">
                    @else
                        <div class="hero-avatar" style="background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user fa-3x"></i>
                        </div>
                    @endif
                </div>
                <div class="col">
                    <h1 class="mb-2" style="font-size: 2.5rem; font-weight: 800;">
                        @if(isset($user) && property_exists($user, 'first_name') && $user->first_name)
                            {{ $user->first_name }} {{ $user->last_name ?? '' }}
                        @else
                            Mon Profil
                        @endif
                    </h1>
                    <p class="mb-3" style="font-size: 1.15rem; opacity: 0.95;">✨ Personnalisez votre espace d'apprentissage</p>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge" style="background: rgba(255,255,255,0.25); padding: 0.6rem 1.2rem; border-radius: 50px; font-size: 0.9rem;">
                            <i class="fas fa-calendar me-1"></i>
                            Depuis {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('M Y') : 'N/A' }}
                        </span>
                        <span class="badge" style="background: rgba(255,255,255,0.25); padding: 0.6rem 1.2rem; border-radius: 50px; font-size: 0.9rem;">
                            <i class="fas fa-graduation-cap me-1"></i>
                            {{ ucfirst(str_replace('-', ' ', session('user_formation_raw', 'community-management'))) }}
                        </span>
                        <span class="badge" style="background: rgba(16, 185, 129, 0.9); padding: 0.6rem 1.2rem; border-radius: 50px; font-size: 0.9rem;">
                            <i class="fas fa-check-circle me-1"></i>
                            {{ $user->status ?? 'Actif' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-left: 0; margin-right: 0;">
        <div class="col-lg-8" style="padding-left: 15px; padding-right: 15px;">

            <!-- PHOTO DE PROFIL -->
            <div class="modern-section fade-in delay-1">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    <div>
                        <h2 class="section-title">Photo de Profil</h2>
                        <p class="text-muted mb-0">Ajoutez votre photo pour personnaliser votre profil</p>
                    </div>
                </div>

                <div class="photo-zone">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            @php
                                $currentPhoto = \App\Helpers\ProfilePhotoHelper::getUrl($user->profile_photo);
                            @endphp
                            @if($currentPhoto)
                                <img id="photoPreview" src="{{ $currentPhoto }}" alt="Photo" class="photo-current">
                            @else
                                <div id="photoPreview" class="photo-current" style="background: linear-gradient(135deg, rgba(131, 58, 180, 0.1), rgba(225, 48, 108, 0.1)); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user fa-4x" style="color: var(--ig-pink);"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4 class="mb-3" style="color: #1e293b; font-weight: 700;">
                                <i class="fas fa-sparkles me-2" style="color: var(--ig-orange);"></i>
                                Changez votre photo
                            </h4>
                            <div class="alert-instagram mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Formats:</strong> JPG, PNG, GIF • <strong>Max:</strong> 5MB
                            </div>
                            <input type="file" id="photoInput" name="photo" accept="image/*" class="input-field mb-3">
                            <button type="button" class="btn-instagram" onclick="uploadPhoto()">
                                <i class="fas fa-cloud-upload-alt"></i>
                                Uploader la photo
                            </button>
                            <div id="uploadProgress" class="mt-3" style="display: none;">
                                <div class="progress" style="height: 10px; border-radius: 10px;">
                                    <div class="progress-bar" role="progressbar" style="width: 100%; background: linear-gradient(90deg, var(--ig-purple), var(--ig-red));"></div>
                                </div>
                                <small class="text-muted mt-2 d-block"><i class="fas fa-spinner fa-spin me-1"></i>Upload en cours...</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FORMULAIRE PRINCIPAL -->
            <form id="profileForm" method="POST" action="{{ route(session('user_formation_raw', 'design-graphique') . '.parametres.update') }}">
            @csrf

            <!-- INFORMATIONS PERSONNELLES -->
            <div class="modern-section fade-in delay-2">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div>
                        <h2 class="section-title">Informations Personnelles</h2>
                        <p class="text-muted mb-0">Vos données d'identité</p>
                    </div>
                </div>

                <div class="fields-grid">
                    <div class="input-group-modern">
                        <label class="input-label">
                            <i class="fas fa-id-badge" style="color: var(--ig-purple);"></i>
                            Prénom <span style="color: var(--ig-red);">*</span>
                        </label>
                        <input type="text" name="firstName" class="input-field"
                               value="{{ $user->first_name ?? session('user_prenom') ?? '' }}">
                    </div>

                    <div class="input-group-modern">
                        <label class="input-label">
                            <i class="fas fa-id-badge" style="color: var(--ig-purple);"></i>
                            Nom <span style="color: var(--ig-red);">*</span>
                        </label>
                        <input type="text" name="lastName" class="input-field"
                               value="{{ $user->last_name ?? session('user_nom') ?? '' }}">
                    </div>

                    <div class="input-group-modern">
                        <label class="input-label">
                            <i class="fas fa-calendar-alt" style="color: var(--ig-pink);"></i>
                            Date de naissance
                        </label>
                        <input type="date" name="date_of_birth" class="input-field"
                               value="{{ $user->date_of_birth ?? '' }}"
                               max="{{ date('Y-m-d') }}">
                    </div>

                    <div class="input-group-modern">
                        <label class="input-label">
                            <i class="fas fa-venus-mars" style="color: var(--ig-purple);"></i>
                            Sexe
                        </label>
                        <select name="gender" class="input-field">
                            <option value="">Sélectionner...</option>
                            <option value="Homme" {{ ($user->gender ?? '') === 'Homme' ? 'selected' : '' }}>Homme</option>
                            <option value="Femme" {{ ($user->gender ?? '') === 'Femme' ? 'selected' : '' }}>Femme</option>
                            <option value="Autre" {{ ($user->gender ?? '') === 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>

                    <div class="input-group-modern">
                        <label class="input-label">
                            <i class="fas fa-globe" style="color: var(--ig-red);"></i>
                            Pays <span style="color: var(--ig-red);">*</span>
                        </label>
                        @php
                            $userCountry = $user->country ?? session('user_pays') ?? 'Côte d\'Ivoire';
                        @endphp
                        <select name="country" class="input-field">
                            <option value="Côte d'Ivoire" {{ $userCountry === 'Côte d\'Ivoire' ? 'selected' : '' }}>Côte d'Ivoire</option>
                            <option value="France" {{ $userCountry === 'France' ? 'selected' : '' }}>France</option>
                            <option value="Sénégal" {{ $userCountry === 'Sénégal' ? 'selected' : '' }}>Sénégal</option>
                            <option value="Cameroun" {{ $userCountry === 'Cameroun' ? 'selected' : '' }}>Cameroun</option>
                            <option value="Canada" {{ $userCountry === 'Canada' ? 'selected' : '' }}>Canada</option>
                            <option value="Belgique" {{ $userCountry === 'Belgique' ? 'selected' : '' }}>Belgique</option>
                            <option value="Maroc" {{ $userCountry === 'Maroc' ? 'selected' : '' }}>Maroc</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>

                    <div class="input-group-modern">
                        <label class="input-label">
                            <i class="fas fa-city" style="color: var(--ig-orange);"></i>
                            Ville <span style="color: var(--ig-red);">*</span>
                        </label>
                        <input type="text" name="city" class="input-field"
                               value="{{ $user->city ?? session('user_ville') ?? '' }}">
                    </div>

                    <div class="input-group-modern">
                        <label class="input-label">
                            <i class="fas fa-map-marker-alt" style="color: var(--ig-yellow);"></i>
                            Quartier
                        </label>
                        <input type="text" name="district" class="input-field"
                               value="{{ $user->quartier ?? '' }}" placeholder="Votre quartier">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="input-label">
                        <i class="fas fa-pen-fancy" style="color: var(--ig-purple);"></i>
                        Biographie
                    </label>
                    <textarea name="biography" class="input-field" rows="4"
                              placeholder="Parlez-nous de vous, votre parcours, vos aspirations...">{{ $user->biography ?? '' }}</textarea>
                    <small class="text-muted mt-2 d-block">
                        <i class="fas fa-lightbulb me-1"></i>
                        Une bonne bio aide à créer des connexions professionnelles
                    </small>
                </div>
            </div>

            <!-- CONTACT -->
            <div class="modern-section fade-in delay-3">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div>
                        <h2 class="section-title">Contact</h2>
                        <p class="text-muted mb-0">Comment vous joindre</p>
                    </div>
                </div>

                <div class="fields-grid">
                    <div class="input-group-modern">
                        <label class="input-label">
                            <i class="fas fa-mobile-alt" style="color: var(--ig-purple);"></i>
                            Téléphone
                        </label>
                        <input type="tel" name="phone" class="input-field"
                               value="{{ $user->phone ?? session('user_telephone') ?? '' }}"
                               placeholder="+225 07 12 34 56 78">
                    </div>

                    <div class="input-group-modern">
                        <label class="input-label">
                            <i class="fab fa-whatsapp" style="color: #25D366;"></i>
                            WhatsApp
                        </label>
                        <input type="tel" name="whatsapp" class="input-field"
                               value="{{ $user->whatsapp ?? session('user_whatsapp') ?? '' }}"
                               placeholder="+225 07 12 34 56 78">
                    </div>
                </div>
            </div>

            <!-- FORMATION -->
            <div class="modern-section fade-in delay-4">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h2 class="section-title">Parcours Académique</h2>
                        <p class="text-muted mb-0">Votre formation et diplômes</p>
                    </div>
                </div>

                <div class="fields-grid">
                    <div class="input-group-modern">
                        <label class="input-label">
                            <i class="fas fa-award" style="color: var(--ig-orange);"></i>
                            Niveau d'étude
                        </label>
                        @php
                            $userLevel = $user->Level_education ?? '';
                        @endphp
                        <select name="educationLevel" class="input-field">
                            <option value="">Sélectionner</option>
                            <option value="college" {{ $userLevel === 'college' ? 'selected' : '' }}>Collège</option>
                            <option value="lycee" {{ $userLevel === 'lycee' ? 'selected' : '' }}>Lycée</option>
                            <option value="bac" {{ $userLevel === 'bac' ? 'selected' : '' }}>Baccalauréat</option>
                            <option value="bac+2" {{ $userLevel === 'bac+2' ? 'selected' : '' }}>Bac+2</option>
                            <option value="bac+3" {{ $userLevel === 'bac+3' ? 'selected' : '' }}>Bac+3 (Licence)</option>
                            <option value="bac+5" {{ $userLevel === 'bac+5' ? 'selected' : '' }}>Bac+5 (Master)</option>
                            <option value="doctorat" {{ $userLevel === 'doctorat' ? 'selected' : '' }}>Doctorat</option>
                        </select>
                    </div>

                    <div class="input-group-modern">
                        <label class="input-label">
                            <i class="fas fa-certificate" style="color: var(--ig-yellow);"></i>
                            Dernier diplôme
                        </label>
                        <input type="text" name="lastDiploma" class="input-field"
                               value="{{ $user->degree ?? '' }}"
                               placeholder="Ex: Bac ES, BTS Communication...">
                    </div>
                </div>
            </div>

            <!-- BOUTONS D'ACTION -->
            <div class="modern-section" style="background: white; border: 3px solid transparent; background-image: linear-gradient(white, white), linear-gradient(135deg, var(--ig-purple), var(--ig-red)); background-origin: border-box; background-clip: padding-box, border-box;">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="mb-1" style="font-weight: 700; font-size: 1.3rem; background: linear-gradient(135deg, var(--ig-purple), var(--ig-red)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            💾 Sauvegarder vos modifications
                        </h5>
                        <p class="mb-0" style="color: #475569; font-size: 0.95rem;">Vos changements seront appliqués immédiatement</p>
                    </div>
                    <div class="d-flex gap-3 flex-wrap">
                        <button type="button" class="btn-outline-instagram" onclick="location.reload()">
                            <i class="fas fa-undo"></i>
                            Annuler
                        </button>
                        <button type="submit" class="btn-instagram">
                            <i class="fas fa-save"></i>
                            Enregistrer
                        </button>
                    </div>
                </div>
            </div>

            </form>

            <!-- SÉCURITÉ -->
            <div class="modern-section fade-in" style="background: linear-gradient(135deg, #fff5f5, #fef2f2); border-left: 6px solid var(--ig-red);">
                <div class="section-header">
                    <div class="section-icon" style="background: linear-gradient(135deg, var(--ig-red), var(--ig-orange));">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <h2 class="section-title">Sécurité & Connexion</h2>
                        <p class="text-muted mb-0">Gérez votre email et mot de passe</p>
                    </div>
                </div>

                <form method="POST" action="{{ route(session('user_formation_raw', 'design-graphique') . '.parametres.update-login') }}">
                    @csrf
                    <div class="alert-instagram mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Section sensible</strong> - Votre mot de passe actuel est requis pour toute modification
                    </div>

                    <div class="fields-grid">
                        <div class="input-group-modern">
                            <label class="input-label">
                                <i class="fas fa-envelope" style="color: var(--ig-purple);"></i>
                                Email de connexion
                            </label>
                            <input type="email" name="email" class="input-field"
                                   value="{{ $user->email ?? session('user_email') ?? '' }}" required>
                        </div>

                        <div class="input-group-modern">
                            <label class="input-label">
                                <i class="fas fa-user-tag" style="color: var(--ig-pink);"></i>
                                Pseudo
                            </label>
                            <input type="text" name="username" class="input-field"
                                   value="{{ $user->name ?? session('user_nom') ?? '' }}">
                        </div>

                        <div class="input-group-modern">
                            <label class="input-label">
                                <i class="fas fa-lock" style="color: var(--ig-red);"></i>
                                Mot de passe actuel <span style="color: var(--ig-red);">*</span>
                            </label>
                            <input type="password" name="current_password" class="input-field"
                                   placeholder="Requis pour valider" required>
                        </div>

                        <div class="input-group-modern">
                            <label class="input-label">
                                <i class="fas fa-key" style="color: var(--ig-orange);"></i>
                                Nouveau mot de passe
                            </label>
                            <input type="password" name="new_password" class="input-field"
                                   placeholder="Min 8 caractères (optionnel)">
                        </div>

                        <div class="input-group-modern">
                            <label class="input-label">
                                <i class="fas fa-check-double" style="color: var(--ig-yellow);"></i>
                                Confirmer le mot de passe
                            </label>
                            <input type="password" name="new_password_confirmation" class="input-field"
                                   placeholder="Répétez le nouveau mot de passe">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn-instagram w-100" style="background: linear-gradient(135deg, var(--ig-red), var(--ig-orange));">
                            <i class="fas fa-lock me-2"></i>
                            Mettre à jour les informations de connexion
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- SIDEBAR -->
        <div class="col-lg-4" style="padding-left: 15px; padding-right: 15px;">
            @php
                $totalFields = 15;
                $completedFields = 0;
                if (!empty($user->first_name)) $completedFields++;
                if (!empty($user->last_name)) $completedFields++;
                if (!empty($user->email)) $completedFields++;
                if (!empty($user->phone)) $completedFields++;
                if (!empty($user->whatsapp)) $completedFields++;
                if (!empty($user->age)) $completedFields++;
                if (!empty($user->date_of_birth)) $completedFields++;
                if (!empty($user->gender)) $completedFields++;
                if (!empty($user->country)) $completedFields++;
                if (!empty($user->city)) $completedFields++;
                if (!empty($user->quartier)) $completedFields++;
                if (!empty($user->biography)) $completedFields++;
                if (!empty($user->Level_education)) $completedFields++;
                if (!empty($user->degree)) $completedFields++;
                if (!empty($user->profile_photo)) $completedFields++;
                $completionPercentage = round(($completedFields / $totalFields) * 100);
                $circumference = 2 * pi() * 60;
                $dashOffset = $circumference - ($circumference * $completionPercentage / 100);
            @endphp

            <!-- WIDGET COMPLÉTION -->
            <div class="completion-widget fade-in delay-1">
                <div class="completion-circle">
                    <svg width="160" height="160">
                        <circle cx="80" cy="80" r="60" fill="none" stroke="#e2e8f0" stroke-width="12"></circle>
                        <circle cx="80" cy="80" r="60" fill="none"
                                stroke="url(#gradient)" stroke-width="12"
                                stroke-dasharray="{{ $circumference }}"
                                stroke-dashoffset="{{ $dashOffset }}"
                                stroke-linecap="round"
                                style="transform: rotate(-90deg); transform-origin: center; transition: stroke-dashoffset 1s ease;">
                        </circle>
                        <defs>
                            <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="var(--ig-purple)" />
                                <stop offset="100%" stop-color="var(--ig-red)" />
                            </linearGradient>
                        </defs>
                    </svg>
                    <div class="completion-number">{{ $completionPercentage }}%</div>
                </div>

                <h4 style="font-weight: 700; color: #1e293b; margin-bottom: 1rem;">Profil Complété</h4>

                @if($completionPercentage < 100)
                    <p class="text-muted mb-4">Complétez votre profil pour débloquer toutes les fonctionnalités</p>
                @else
                    <div class="alert-success-instagram mb-4">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Félicitations!</strong> Votre profil est complet
                    </div>
                @endif

                <div class="stat-item">
                    <span><i class="fas fa-user me-2" style="color: var(--ig-purple);"></i>Infos personnelles</span>
                    <span class="badge-modern {{ (!empty($user->first_name) && !empty($user->last_name)) ? 'badge-success' : 'badge-warning' }}">
                        {{ (!empty($user->first_name) && !empty($user->last_name)) ? '✓ OK' : '⚠ Incomplet' }}
                    </span>
                </div>

                <div class="stat-item">
                    <span><i class="fas fa-phone me-2" style="color: var(--ig-pink);"></i>Contact</span>
                    <span class="badge-modern {{ (!empty($user->email) && !empty($user->phone)) ? 'badge-success' : 'badge-warning' }}">
                        {{ (!empty($user->email) && !empty($user->phone)) ? '✓ OK' : '⚠ Incomplet' }}
                    </span>
                </div>

                <div class="stat-item">
                    <span><i class="fas fa-camera me-2" style="color: var(--ig-red);"></i>Photo</span>
                    <span class="badge-modern {{ !empty($user->profile_photo) ? 'badge-success' : 'badge-warning' }}">
                        {{ !empty($user->profile_photo) ? '✓ OK' : '⚠ Manquant' }}
                    </span>
                </div>

                <div class="stat-item">
                    <span><i class="fas fa-graduation-cap me-2" style="color: var(--ig-orange);"></i>Formation</span>
                    <span class="badge-modern {{ !empty($user->Level_education) ? 'badge-success' : 'badge-warning' }}">
                        {{ !empty($user->Level_education) ? '✓ OK' : '⚠ Incomplet' }}
                    </span>
                </div>
            </div>

            <!-- SUPPORT -->
            <div class="completion-widget fade-in delay-2" style="margin-top: 2rem; background: linear-gradient(135deg, #833AB4, #C13584, #E1306C); color: white; padding: 1.75rem;">
                <div class="mb-3">
                    <i class="fas fa-headset fa-3x mb-3" style="color: white;"></i>
                    <h5 style="font-weight: 700; color: white; font-size: 1.4rem; margin-bottom: 0.5rem;">Besoin d'aide?</h5>
                    <p style="color: rgba(255, 255, 255, 0.95); font-size: 0.95rem; margin-bottom: 0;">Notre équipe est à votre écoute</p>
                </div>

                <a href="mailto:info@ecolevirtuelledescreatifs.com" class="btn-instagram mb-2" style="background: white; color: var(--ig-purple); font-weight: 700; width: 100%; display: flex; justify-content: center; padding: 0.85rem 1.5rem;">
                    <i class="fas fa-envelope me-2"></i>
                    <span>Contacter le support</span>
                </a>

                <a href="https://wa.me/2250717258602" target="_blank" class="btn-outline-instagram" style="background: rgba(255, 255, 255, 0.2); border: 2px solid white; color: white; font-weight: 700; width: 100%; display: flex; justify-content: center; padding: 0.85rem 1.5rem;">
                    <i class="fab fa-whatsapp me-2"></i>
                    <span>WhatsApp</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Fonction de notification moderne
function showNotification(title, message, type = 'success') {
    const container = document.getElementById('notificationContainer');

    const notification = document.createElement('div');
    notification.className = `notification ${type}`;

    const iconMap = {
        success: 'fas fa-check-circle',
        error: 'fas fa-times-circle',
        warning: 'fas fa-exclamation-triangle'
    };

    notification.innerHTML = `
        <div class="notification-icon">
            <i class="${iconMap[type] || iconMap.success}"></i>
        </div>
        <div class="notification-content">
            <div class="notification-title">${title}</div>
            <div class="notification-message">${message}</div>
        </div>
        <button class="notification-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;

    container.appendChild(notification);

    // Auto-fermeture après 5 secondes
    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Upload photo
function uploadPhoto() {
    const input = document.getElementById('photoInput');
    const preview = document.getElementById('photoPreview');
    const progress = document.getElementById('uploadProgress');

    if (!input.files || !input.files[0]) {
        showNotification('Attention', 'Veuillez sélectionner une photo', 'warning');
        return;
    }

    const file = input.files[0];

    if (!file.type.match('image.*')) {
        showNotification('Erreur', 'Veuillez sélectionner une image valide', 'error');
        return;
    }

    if (file.size > 5242880) {
        showNotification('Erreur', 'L\'image ne doit pas dépasser 5MB', 'error');
        return;
    }

    // Prévisualisation
    const reader = new FileReader();
    reader.onload = function(e) {
        if (preview.tagName === 'DIV') {
            const img = document.createElement('img');
            img.id = 'photoPreview';
            img.className = 'photo-current';
            img.src = e.target.result;
            preview.parentNode.replaceChild(img, preview);
        } else {
            preview.src = e.target.result;
        }
    };
    reader.readAsDataURL(file);

    // Upload
    progress.style.display = 'block';

    const formData = new FormData();
    formData.append('photo', file);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route(session("user_formation_raw", "design-graphique") . ".parametres.upload-photo") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        progress.style.display = 'none';
        if (data.success) {
            showNotification('Succès', 'Photo uploadée avec succès!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Erreur', data.message || 'Une erreur est survenue', 'error');
        }
    })
    .catch(error => {
        progress.style.display = 'none';
        showNotification('Erreur', 'Erreur lors de l\'upload: ' + error.message, 'error');
    });
}

// Loading state on form submit
document.getElementById('profileForm').addEventListener('submit', function(e) {
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sauvegarde...';
});
</script>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showNotification('Succès', '{{ session("success") }}', 'success');
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showNotification('Erreur', '{{ session("error") }}', 'error');
    });
</script>
@endif

@if(session('warning'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showNotification('Attention', '{{ session("warning") }}', 'warning');
    });
</script>
@endif

@endsection
