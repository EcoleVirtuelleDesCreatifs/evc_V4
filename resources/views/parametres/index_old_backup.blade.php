@extends('layouts.ki-admin')

@section('title', 'Paramètres - Profil Utilisateur - EVC 2024')
@section('page-title', 'Paramètres du Profil')

@section('styles')
<style>
    /* Palette Instagram pour cohérence avec dashboard CM */
    :root {
        --instagram-purple: #833AB4;
        --instagram-pink: #C13584;
        --instagram-red: #E1306C;
        --instagram-orange: #F56040;
        --instagram-yellow: #FCAF45;
    }

    body {
        background: linear-gradient(135deg, #fdf4f5 0%, #fef9f8 50%, #f8f9fa 100%);
        font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', sans-serif;
    }

    /* Header moderne avec dégradé Instagram */
    .modern-header {
        background: linear-gradient(135deg, #833AB4 0%, #C13584 50%, #E1306C 100%);
        border-radius: 25px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 20px 60px rgba(131, 58, 180, 0.3);
        position: relative;
        overflow: hidden;
    }

    .modern-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 8s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-30px) rotate(180deg); }
    }

    .header-content {
        position: relative;
        z-index: 1;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 5px solid white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        object-fit: cover;
        position: relative;
        z-index: 1;
    }

    /* Cartes modernes */
    .param-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .param-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(131, 58, 180, 0.15);
    }

    .param-card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f7fafc;
    }

    .param-card-icon {
        width: 50px;
        height: 50px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        background: linear-gradient(135deg, #833AB4, #E1306C);
        box-shadow: 0 5px 15px rgba(131, 58, 180, 0.3);
    }

    .param-card-title {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        background: linear-gradient(135deg, #833AB4 0%, #E1306C 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Champs de formulaire modernes */
    .modern-input {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .modern-input:focus {
        border-color: #C13584;
        box-shadow: 0 0 0 4px rgba(193, 53, 132, 0.1);
        outline: none;
    }

    .modern-input:hover {
        border-color: #C13584;
    }

    .modern-label {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    /* Boutons Instagram */
    .instagram-btn {
        background: linear-gradient(135deg, #833AB4, #E1306C);
        color: white;
        border: none;
        border-radius: 50px;
        padding: 0.85rem 2rem;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(131, 58, 180, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .instagram-btn:hover {
        background: linear-gradient(135deg, #C13584, #F56040);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(193, 53, 132, 0.4);
        color: white;
    }

    .btn-outline-instagram {
        background: white;
        color: #C13584;
        border: 2px solid #C13584;
        border-radius: 50px;
        padding: 0.85rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-outline-instagram:hover {
        background: #C13584;
        color: white;
        transform: translateY(-2px);
    }

    /* Badge de statut */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
    }

    /* Progress circle */
    .completion-circle {
        width: 140px;
        height: 140px;
        margin: 0 auto;
        position: relative;
    }

    .completion-circle svg {
        transform: rotate(-90deg);
    }

    .completion-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #833AB4, #E1306C);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Section infos */
    .info-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    /* Photo upload zone */
    .photo-upload-zone {
        border: 3px dashed #e2e8f0;
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, rgba(131, 58, 180, 0.05), rgba(225, 48, 108, 0.05));
    }

    .photo-upload-zone:hover {
        border-color: #C13584;
        background: linear-gradient(135deg, rgba(131, 58, 180, 0.1), rgba(225, 48, 108, 0.1));
        transform: scale(1.02);
    }

    .current-photo {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid transparent;
        background-image: linear-gradient(white, white),
                          linear-gradient(135deg, #833AB4 0%, #E1306C 100%);
        background-origin: border-box;
        background-clip: padding-box, border-box;
        box-shadow: 0 10px 30px rgba(131, 58, 180, 0.3);
        margin-bottom: 1rem;
    }

    /* Animations */
    .fadeInUp {
        animation: fadeInUp 0.6s ease-out;
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

    /* Responsive */
    @media (max-width: 768px) {
        .modern-header {
            padding: 1.5rem;
        }
        .profile-avatar {
            width: 80px;
            height: 80px;
        }
        .param-card {
            padding: 1.5rem;
        }
        .info-group {
            grid-template-columns: 1fr;
        }
    }

    /* Alert personnalisé */
    .custom-alert {
        border-radius: 15px;
        border: none;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .custom-alert-success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
    }

    .custom-alert-warning {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
    }
</style>
@endsection

@section('content')

<!-- Header moderne avec gradient Instagram -->
<div class="modern-header fadeInUp">
    <div class="header-content">
        <div class="row align-items-center">
            <div class="col-auto">
                @php
                    $photoUrl = null;
                    if(isset($user) && property_exists($user, 'profile_photo') && $user->profile_photo) {
                        if (strpos($user->profile_photo, 'photos_preregistrations/') !== false) {
                            $photoUrl = \App\Models\MediaUrl::fromPath($user->profile_photo);
                        } elseif (strpos($user->profile_photo, '/') === false) {
                            $photoUrl = asset('uploads/photos/' . $user->profile_photo);
                        } else {
                            $photoUrl = asset($user->profile_photo);
                        }
                    }
                @endphp
                @if($photoUrl)
                    <img src="{{ $photoUrl }}" alt="Photo de profil" class="profile-avatar">
                @else
                    <div class="profile-avatar" style="background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user fa-3x"></i>
                    </div>
                @endif
            </div>
            <div class="col">
                <h1 class="mb-2" style="font-size: 2rem; font-weight: 700;">
                    @if(isset($user) && property_exists($user, 'first_name') && property_exists($user, 'last_name') && $user->first_name && $user->last_name)
                        {{ $user->first_name }} {{ $user->last_name }}
                    @else
                        Mon Profil
                    @endif
                </h1>
                <p class="mb-3" style="opacity: 0.9; font-size: 1.1rem;">Personnalisez votre espace et gérez vos informations</p>
                <div class="d-flex gap-3 flex-wrap">
                    <span class="status-badge">
                        <i class="fas fa-calendar"></i>
                        Membre depuis {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('M Y') : 'N/A' }}
                    </span>
                    <span class="status-badge">
                        <i class="fas fa-graduation-cap"></i>
                        {{ ucfirst(session('user_formation_raw', 'community-management')) }}
                    </span>
                    <span class="status-badge">
                        <i class="fas fa-check-circle"></i>
                        {{ $user->status ?? 'Actif' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section Photo de Profil Moderne -->
<div class="param-card fadeInUp" style="animation-delay: 0.1s;">
    <div class="param-card-header">
        <div class="param-card-icon">
            <i class="fas fa-camera"></i>
        </div>
        <div>
            <h2 class="param-card-title">Photo de Profil</h2>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Ajoutez ou modifiez votre photo de profil</p>
        </div>
    </div>

    <div class="photo-upload-zone">
        <div class="row align-items-center">
            <div class="col-md-4 text-center">
                @php
                    $photoDisplay = null;
                    if(isset($user) && property_exists($user, 'profile_photo') && $user->profile_photo) {
                        if (strpos($user->profile_photo, 'photos_preregistrations/') !== false) {
                            $photoDisplay = \App\Models\MediaUrl::fromPath($user->profile_photo);
                        } elseif (strpos($user->profile_photo, '/') === false) {
                            $photoDisplay = asset('uploads/photos/' . $user->profile_photo);
                        } else {
                            $photoDisplay = asset($user->profile_photo);
                        }
                    } elseif(session('user_photo')) {
                        $photoDisplay = asset(session('user_photo'));
                    }
                @endphp

                @if($photoDisplay)
                    <img id="photoPreview" src="{{ $photoDisplay }}" alt="Photo de profil" class="current-photo">
                @else
                    <div id="photoPreview" class="current-photo" style="background: linear-gradient(135deg, rgba(131, 58, 180, 0.1), rgba(225, 48, 108, 0.1)); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user fa-3x" style="color: #C13584;"></i>
                    </div>
                @endif
            </div>
            <div class="col-md-8">
                <h5 class="mb-3" style="color: #1e293b; font-weight: 600;">
                    <i class="fas fa-sparkles me-2" style="color: #F56040;"></i>
                    Personnalisez votre profil
                </h5>
                <div class="alert custom-alert-warning mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Formats acceptés:</strong> JPG, PNG, GIF | <strong>Taille max:</strong> 5MB
                </div>
                <input type="file" id="photoInput" name="photo" accept="image/*" class="modern-input mb-3" style="width: 100%;">
                <button type="button" class="instagram-btn" onclick="uploadPhoto()">
                    <i class="fas fa-upload"></i>
                    Uploader la photo
                </button>
                <div id="uploadProgress" class="mt-3" style="display: none;">
                    <div class="progress" style="height: 8px; border-radius: 10px;">
                        <div class="progress-bar" role="progressbar" style="width: 100%; background: linear-gradient(90deg, #833AB4, #E1306C);"></div>
                    </div>
                    <small class="text-muted mt-2 d-block"><i class="fas fa-spinner fa-spin me-1"></i>Upload en cours...</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FORMULAIRE PRINCIPAL DE MISE À JOUR DU PROFIL -->
<form id="profileForm" method="POST" action="{{ route(session('user_formation_raw', 'design-graphique') . '.parametres.update') }}">
@csrf
<div class="row">
    <div class="col-md-8">
        <!-- Informations personnelles -->
        <div class="param-card fadeInUp" style="animation-delay: 0.2s;">
            <div class="param-card-header">
                <div class="param-card-icon">
                    <i class="fas fa-id-card"></i>
                </div>
                <div>
                    <h2 class="param-card-title">Informations Personnelles</h2>
                    <p class="text-muted mb-0" style="font-size: 0.9rem;">Vos données personnelles</p>
                </div>
            </div>
            <div class="info-group">
                <div>
                    <label for="firstName" class="modern-label">Prénom <span style="color: #E1306C;">*</span></label>
                    <input type="text" class="form-control modern-input" id="firstName" name="firstName"
                           value="@if(isset($user) && property_exists($user, 'first_name') && $user->first_name){{ $user->first_name }}@elseif(session('user_prenom')){{ session('user_prenom') }}@endif">
                </div>
                <div>
                    <label for="lastName" class="modern-label">Nom <span style="color: #E1306C;">*</span></label>
                    <input type="text" class="form-control modern-input" id="lastName" name="lastName"
                           value="@if(isset($user) && property_exists($user, 'last_name') && $user->last_name){{ $user->last_name }}@elseif(session('user_nom')){{ session('user_nom') }}@endif">
                </div>
            </div>
            <div class="info-group">
                <div>
                    <label for="age" class="modern-label">Âge</label>
                    <input type="number" class="form-control modern-input" id="age" name="age" value="{{ $user->age ?? '' }}" min="16" max="99">
                </div>
                <div>
                    <label for="country" class="modern-label">Pays <span style="color: #E1306C;">*</span></label>
                    @php
                        $userCountry = '';
                        if(isset($user) && property_exists($user, 'country') && $user->country) {
                            $userCountry = $user->country;
                        } elseif(session('user_pays')) {
                            $userCountry = session('user_pays');
                        }
                    @endphp
                    <select class="form-select modern-input" id="country" name="country">
                        <option value="France" {{ $userCountry === 'France' ? 'selected' : '' }}>France</option>
                        <option value="Belgique" {{ $userCountry === 'Belgique' ? 'selected' : '' }}>Belgique</option>
                        <option value="Suisse" {{ $userCountry === 'Suisse' ? 'selected' : '' }}>Suisse</option>
                        <option value="Canada" {{ $userCountry === 'Canada' ? 'selected' : '' }}>Canada</option>
                        <option value="Maroc" {{ $userCountry === 'Maroc' ? 'selected' : '' }}>Maroc</option>
                        <option value="Tunisie" {{ $userCountry === 'Tunisie' ? 'selected' : '' }}>Tunisie</option>
                        <option value="Algérie" {{ $userCountry === 'Algérie' ? 'selected' : '' }}>Algérie</option>
                        <option value="Sénégal" {{ $userCountry === 'Sénégal' ? 'selected' : '' }}>Sénégal</option>
                        <option value="Côte d'Ivoire" {{ $userCountry === 'Côte d\'Ivoire' ? 'selected' : '' }}>Côte d'Ivoire</option>
                        <option value="Cameroun" {{ $userCountry === 'Cameroun' ? 'selected' : '' }}>Cameroun</option>
                    </select>
                </div>
                <div>
                    <label for="city" class="modern-label">Ville <span style="color: #E1306C;">*</span></label>
                    <input type="text" class="form-control modern-input" id="city" name="city"
                           value="@if(isset($user) && property_exists($user, 'city') && $user->city){{ $user->city }}@elseif(session('user_ville')){{ session('user_ville') }}@endif">
                </div>
                <div>
                    <label for="district" class="modern-label">Quartier</label>
                    <input type="text" class="form-control modern-input" id="district" name="district" value="{{ $user->quartier ?? '' }}" placeholder="Quartier ou arrondissement">
                </div>
            </div>
            <div class="mt-3">
                <label for="biography" class="modern-label">Biographie</label>
                <textarea class="form-control modern-input" id="biography" name="biography" rows="4" placeholder="Parlez-nous de vous, votre parcours, vos passions...">{{ $user->biography ?? '' }}</textarea>
                <small class="text-muted mt-2 d-block"><i class="fas fa-info-circle me-1"></i>Présentez-vous en quelques lignes (optionnel)</small>
            </div>
        </div>

        <!-- Contact -->
        <div class="param-card fadeInUp" style="animation-delay: 0.3s;">
            <div class="param-card-header">
                <div class="param-card-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div>
                    <h2 class="param-card-title">Informations de Contact</h2>
                    <p class="text-muted mb-0" style="font-size: 0.9rem;">Comment vous joindre</p>
                </div>
            </div>
            <div class="info-group">
                <div>
                    <label for="phone" class="modern-label">
                        <i class="fas fa-phone-alt me-1" style="color: #833AB4;"></i>
                        Téléphone
                    </label>
                    <input type="tel" class="form-control modern-input" id="phone" name="phone"
                           value="@if(isset($user) && property_exists($user, 'phone') && $user->phone){{ $user->phone }}@elseif(session('user_telephone')){{ session('user_telephone') }}@endif"
                           placeholder="+225 07 12 34 56 78">
                </div>
                <div>
                    <label for="whatsapp" class="modern-label">
                        <i class="fab fa-whatsapp me-1" style="color: #25D366;"></i>
                        WhatsApp
                    </label>
                    <input type="tel" class="form-control modern-input" id="whatsapp" name="whatsapp"
                           value="@if(isset($user) && property_exists($user, 'whatsapp') && $user->whatsapp){{ $user->whatsapp }}@elseif(session('user_whatsapp')){{ session('user_whatsapp') }}@endif"
                           placeholder="+225 07 12 34 56 78">
                </div>
            </div>
        </div>

        <!-- Formation et niveau -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-graduation-cap me-2" style="color: #FF9900;"></i>
                    Parcours et formation
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="educationLevel" class="form-label"><strong>Niveau d'étude</strong></label>
                        @php
                            $userEducationLevel = '';
                            if(isset($user) && property_exists($user, 'Level_education') && $user->Level_education) {
                                $userEducationLevel = $user->Level_education;
                            }
                        @endphp
                        <select class="form-control" id="educationLevel" name="educationLevel">
                            <option value="">Sélectionner votre niveau</option>
                            <option value="college" {{ $userEducationLevel === 'college' ? 'selected' : '' }}>Collège</option>
                            <option value="lycee" {{ $userEducationLevel === 'lycee' ? 'selected' : '' }}>Lycée</option>
                            <option value="bac" {{ $userEducationLevel === 'bac' ? 'selected' : '' }}>Baccalauréat</option>
                            <option value="bac+2" {{ $userEducationLevel === 'bac+2' ? 'selected' : '' }}>Bac+2 (BTS, DUT, etc.)</option>
                            <option value="bac+3" {{ $userEducationLevel === 'bac+3' ? 'selected' : '' }}>Bac+3 (Licence, Bachelor)</option>
                            <option value="bac+5" {{ $userEducationLevel === 'bac+5' ? 'selected' : '' }}>Bac+5 (Master, Ingénieur)</option>
                            <option value="doctorat" {{ $userEducationLevel === 'doctorat' ? 'selected' : '' }}>Doctorat</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastDiploma" class="form-label"><strong>Dernier diplôme obtenu</strong></label>
                        <input type="text" class="form-control" id="lastDiploma" name="lastDiploma" value="{{ $user->degree ?? '' }}" placeholder="Ex: Bac ES, BTS Communication...">
                    </div>
                </div>

            </div>
        </div>

        <!-- Boutons d'action du formulaire principal -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <button type="submit" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-save me-2"></i>
                            Sauvegarder les modifications
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                            <i class="fas fa-undo me-2"></i>
                            Annuler
                        </button>
                    </div>
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            Dernière modification : Aujourd'hui à 14:30
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Le contenu de la sidebar reste ici -->
    </div>
</div>
</form>
<!-- FIN DU FORMULAIRE PRINCIPAL -->

<!-- DÉBUT DU FORMULAIRE SÉPARÉ POUR LES INFORMATIONS DE CONNEXION -->
<div class="row">
    <div class="col-md-8">
        <!-- Informations de connexion (Table USERS) -->
        <div class="card mb-4">
            <div class="card-header" style="background: linear-gradient(135deg, #ff6633 0%, #ff9966 100%); color: white;">
                <h5 class="mb-0">
                    <i class="fas fa-key me-2"></i>
                    Informations de connexion
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Section sensible</strong><br>
                    Ces informations sont utilisées pour votre connexion. Toute modification nécessite votre mot de passe actuel.
                </div>

                <!-- Formulaire séparé pour les informations de connexion -->
                <form id="loginInfoForm" method="POST" action="{{ route(session('user_formation_raw', 'design-graphique') . '.parametres.update-login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="loginEmail" class="form-label">
                            <i class="fas fa-envelope me-1" style="color: #ff6633;"></i>
                            <strong>Adresse Email</strong>
                        </label>
                        <input type="email" class="form-control" id="loginEmail" name="email"
                               value="{{ $user->email ?? session('user_email') }}" required>
                        <small class="text-muted">Utilisé pour la connexion à votre compte</small>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">
                            <i class="fas fa-user me-1" style="color: #ff6633;"></i>
                            <strong>Pseudonyme</strong>
                        </label>
                        <input type="text" class="form-control" id="username" name="username"
                               value="{{ $user->name ?? session('user_nom') }}"
                               placeholder="Votre nom d'utilisateur">
                        <small class="text-muted">Nom affiché sur votre profil</small>
                    </div>

                    <hr class="my-4">

                    <h6 class="mb-3">
                        <i class="fas fa-lock me-2" style="color: #ff6633;"></i>
                        Modification du mot de passe
                    </h6>
                    <p class="text-muted small mb-3">Laissez vide si vous ne souhaitez pas changer votre mot de passe</p>

                    <div class="mb-3">
                        <label for="currentPassword" class="form-label"><strong>Mot de passe actuel</strong> <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="currentPassword" name="current_password"
                               placeholder="Requis pour toute modification" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="newPassword" class="form-label"><strong>Nouveau mot de passe</strong></label>
                            <input type="password" class="form-control" id="newPassword" name="new_password"
                                   placeholder="Minimum 8 caractères">
                            <small class="text-muted">Laissez vide pour ne pas changer</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="confirmPassword" class="form-label"><strong>Confirmer le nouveau mot de passe</strong></label>
                            <input type="password" class="form-control" id="confirmPassword" name="new_password_confirmation"
                                   placeholder="Répétez le nouveau mot de passe">
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-warning btn-lg">
                            <i class="fas fa-shield-alt me-2"></i>
                            Mettre à jour les informations de connexion
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar pour le formulaire de connexion -->
    <div class="col-md-4">
        <!-- Résumé du profil -->
        @php
            // Calculer le pourcentage de complétion du profil
            $totalFields = 13; // Nombre total de champs importants
            $completedFields = 0;

            // Vérifier les champs obligatoires/importants
            if (!empty($user->first_name)) $completedFields++;
            if (!empty($user->last_name)) $completedFields++;
            if (!empty($user->email)) $completedFields++;
            if (!empty($user->phone)) $completedFields++;
            if (!empty($user->whatsapp)) $completedFields++;
            if (!empty($user->age)) $completedFields++;
            if (!empty($user->country)) $completedFields++;
            if (!empty($user->city)) $completedFields++;
            if (!empty($user->quartier)) $completedFields++;
            if (!empty($user->biography)) $completedFields++;
            if (!empty($user->Level_education)) $completedFields++;
            if (!empty($user->degree)) $completedFields++;
            if (!empty($user->profile_photo)) $completedFields++;

            $completionPercentage = round(($completedFields / $totalFields) * 100);

            // Calculer le stroke-dashoffset pour le cercle de progression
            $circumference = 2 * pi() * 40; // 2πr où r=40
            $dashOffset = $circumference - ($circumference * $completionPercentage / 100);

            // Déterminer la couleur selon le pourcentage
            $progressColor = $completionPercentage >= 80 ? '#28a745' : ($completionPercentage >= 50 ? '#3399ff' : '#ffc107');

            // Vérifier les sections
            $hasPersonalInfo = !empty($user->first_name) && !empty($user->last_name) && !empty($user->age);
            $hasContact = !empty($user->email) && !empty($user->phone);
            $hasPhoto = !empty($user->profile_photo);
            $hasEducation = !empty($user->Level_education) || !empty($user->degree);
        @endphp

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-circle me-2" style="color: #003366;"></i>
                    Résumé du profil
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="progress-circle" style="width: 100px; height: 100px; margin: 0 auto; position: relative;">
                        <svg width="100" height="100" style="transform: rotate(-90deg);">
                            <circle cx="50" cy="50" r="40" fill="none" stroke="#e9ecef" stroke-width="8"></circle>
                            <circle cx="50" cy="50" r="40" fill="none" stroke="{{ $progressColor }}" stroke-width="8"
                                    stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $dashOffset }}"
                                    style="transition: stroke-dashoffset 0.5s ease;"></circle>
                        </svg>
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: bold; color: #003366;">
                            {{ $completionPercentage }}%
                        </div>
                    </div>
                    <p class="mt-2 mb-0"><strong>Profil complété</strong></p>
                    @if($completionPercentage < 100)
                        <small class="text-muted">Ajoutez plus d'informations pour atteindre 100%</small>
                    @else
                        <small class="text-success">🎉 Votre profil est complet !</small>
                    @endif
                </div>

                <div class="profile-stats">
                    <div class="d-flex justify-content-between mb-2">
                        <span>
                            <i class="fas {{ $hasPersonalInfo ? 'fa-check-circle text-success' : 'fa-exclamation-circle text-warning' }} me-1"></i>
                            Infos personnelles
                        </span>
                        <span class="badge {{ $hasPersonalInfo ? 'bg-success' : 'bg-warning' }}">
                            {{ $hasPersonalInfo ? 'Complété' : 'Incomplet' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>
                            <i class="fas {{ $hasContact ? 'fa-check-circle text-success' : 'fa-exclamation-circle text-warning' }} me-1"></i>
                            Contact
                        </span>
                        <span class="badge {{ $hasContact ? 'bg-success' : 'bg-warning' }}">
                            {{ $hasContact ? 'Complété' : 'Incomplet' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>
                            <i class="fas {{ $hasPhoto ? 'fa-check-circle text-success' : 'fa-exclamation-circle text-warning' }} me-1"></i>
                            Photo de profil
                        </span>
                        <span class="badge {{ $hasPhoto ? 'bg-success' : 'bg-warning' }}">
                            {{ $hasPhoto ? 'Complété' : 'Manquant' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>
                            <i class="fas {{ $hasEducation ? 'fa-check-circle text-success' : 'fa-exclamation-circle text-warning' }} me-1"></i>
                            Formation
                        </span>
                        <span class="badge {{ $hasEducation ? 'bg-success' : 'bg-warning' }}">
                            {{ $hasEducation ? 'Complété' : 'Incomplet' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>



        <!-- Support & Aide -->
        <div class="card border-0 shadow-sm">
            <div class="card-header" style="background: linear-gradient(135deg, #003366 0%, #3399ff 100%); color: white; border: none;">
                <h5 class="mb-0">
                    <i class="fas fa-headset me-2"></i>
                    Besoin d'aide ?
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-3 text-muted small">
                    <i class="fas fa-info-circle me-1 text-primary"></i>
                    Notre équipe est là pour vous accompagner !
                </p>

                <!-- Boutons d'aide -->
                <div class="d-grid gap-2 mb-3">
                    <a href="#" class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-start"
                       onclick="alert('Redirection vers le centre d\'aide...'); return false;">
                        <i class="fas fa-book me-2"></i>
                        Centre d'aide
                    </a>
                    <a href="mailto:info@ecolevirtuelledescreatifs.com"
                       class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-start">
                        <i class="fas fa-envelope me-2"></i>
                        Contacter le support
                    </a>
                    <a href="#" class="btn btn-sm btn-outline-danger d-flex align-items-center justify-content-start"
                       onclick="alert('Formulaire de signalement...'); return false;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Signaler un problème
                    </a>
                </div>

                <!-- Contact direct -->
                <div class="border-top pt-3">
                    <p class="small text-muted mb-2">
                        <i class="fas fa-phone-alt me-1"></i>
                        Contact direct :
                    </p>
                    <div class="d-grid gap-2">
                        <a href="mailto:info@ecolevirtuelledescreatifs.com"
                           class="btn btn-sm btn-light text-start"
                           style="font-size: 0.85rem;">
                            <i class="fas fa-envelope me-2 text-primary"></i>
                            info@ecolevirtuelledescreatifs.com
                        </a>
                        <a href="https://wa.me/2250717258602"
                           class="btn btn-sm btn-light text-start"
                           style="font-size: 0.85rem;"
                           target="_blank">
                            <i class="fab fa-whatsapp me-2 text-success"></i>
                            07 17 25 86 02
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- FIN DU FORMULAIRE INFORMATIONS DE CONNEXION -->

<style>
/* Styles personnalisés pour la page paramètres */
.profile-photo-container:hover .photo-overlay {
    display: flex !important;
}

.form-check-label {
    cursor: pointer;
    padding: 15px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: block;
    width: 100%;
}

.form-check-input:checked + .form-check-label {
    border-color: #3399ff;
    background-color: rgba(51, 153, 255, 0.1);
}

.form-check-label:hover {
    border-color: #3399ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.card:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.progress-circle {
    animation: fadeInScale 0.8s ease-out;
}

@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.profile-stats > div {
    animation: slideInLeft 0.6s ease-out;
    animation-fill-mode: both;
}

.profile-stats > div:nth-child(1) { animation-delay: 0.1s; }
.profile-stats > div:nth-child(2) { animation-delay: 0.2s; }
.profile-stats > div:nth-child(3) { animation-delay: 0.3s; }
.profile-stats > div:nth-child(4) { animation-delay: 0.4s; }

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@keyframes slideOutRight {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(100%); opacity: 0; }
}

/* Responsive */
@media (max-width: 768px) {
    .profile-photo-container {
        width: 80px !important;
        height: 80px !important;
    }

    .profile-photo-container img,
    .profile-photo-container .photo-overlay {
        width: 80px !important;
        height: 80px !important;
    }

    .progress-circle {
        width: 80px !important;
        height: 80px !important;
    }

    .progress-circle svg {
        width: 80px !important;
        height: 80px !important;
    }
}
</style>

<script>
// UPLOAD DE PHOTO - VERSION ULTRA SIMPLE
function uploadPhoto() {
    console.log('🚀 Fonction uploadPhoto() appelée');

    var input = document.getElementById('photoInput');
    var preview = document.getElementById('photoPreview');
    var progress = document.getElementById('uploadProgress');

    if (!input.files || !input.files[0]) {
        alert('⚠️ Veuillez d\'abord sélectionner une photo');
        return;
    }

    var file = input.files[0];
    console.log('📁 Fichier:', file.name, file.type, file.size);

    // Validation
    if (!file.type.match('image.*')) {
        alert('⚠️ Veuillez sélectionner une image');
        return;
    }

    if (file.size > 5242880) { // 5MB
        alert('⚠️ L\'image ne doit pas dépasser 5MB');
        return;
    }

    console.log('✅ Validation OK');

    // Prévisualisation
    var reader = new FileReader();
    reader.onload = function(e) {
        console.log('🖼️ Prévisualisation...');
        if (preview.tagName === 'DIV') {
            var img = document.createElement('img');
            img.id = 'photoPreview';
            img.className = 'rounded-circle';
            img.style.cssText = 'width: 150px; height: 150px; object-fit: cover; border: 3px solid #3399ff;';
            img.src = e.target.result;
            preview.parentNode.replaceChild(img, preview);
        } else {
            preview.src = e.target.result;
        }
    };
    reader.readAsDataURL(file);

    // Afficher progression
    if (progress) progress.style.display = 'block';

    // Upload
    var formData = new FormData();
    formData.append('photo', file);
    formData.append('_token', '{{ csrf_token() }}');

    var url = '{{ route(session("user_formation_raw", "design-graphique") . ".parametres.upload-photo") }}';
    console.log('📡 Upload vers:', url);

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(function(response) {
        console.log('📥 Réponse:', response.status);
        return response.json();
    })
    .then(function(data) {
        console.log('📦 Données:', data);
        if (progress) progress.style.display = 'none';

        if (data.success) {
            alert('✅ Photo uploadée avec succès!');
            setTimeout(function() {
                location.reload();
            }, 500);
        } else {
            alert('❌ ' + (data.message || 'Erreur'));
        }
    })
    .catch(function(error) {
        console.error('❌ Erreur:', error);
        if (progress) progress.style.display = 'none';
        alert('❌ Erreur: ' + error.message);
    });
}

// Fonction de notification
function showNotification(title, message, type = 'info') {
    // Créer la notification
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 500px;';

    notification.innerHTML = `
        <strong>${title}</strong><br>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    // Auto-remove après 5 secondes
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Mettre à jour la date de dernière modification
function updateLastModified() {
    const now = new Date();
    const dateStr = now.toLocaleDateString('fr-FR') + ' à ' + now.toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'});

    // Chercher et mettre à jour l'élément de dernière modification s'il existe
    const lastModifiedElement = document.querySelector('.last-modified');
    if (lastModifiedElement) {
        lastModifiedElement.textContent = `Dernière modification: ${dateStr}`;
    }
}

// Mettre à jour le score de complétion du profil
function updateProfileCompletion() {
    // Cette fonction peut être étendue pour calculer dynamiquement le score
    console.log('Profile completion updated');
}

// Form submission avec loading state
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profileForm');
    const submitBtn = form.querySelector('button[type="submit"]');

    form.addEventListener('submit', function() {
        // Disable button and show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sauvegarde en cours...';
    });
});

// Réinitialiser le formulaire
function resetForm() {
    if (confirm('Êtes-vous sûr de vouloir annuler toutes les modifications ?')) {
        // Recharger la page pour restaurer les valeurs d'origine
        window.location.reload();
    }
}

// Faire défiler vers une section
function scrollToSection(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'center' });

        // Effet de surbrillance
        element.style.transition = 'all 0.3s ease';
        element.style.transform = 'scale(1.05)';
        element.style.boxShadow = '0 0 20px rgba(51, 153, 255, 0.3)';

        setTimeout(() => {
            element.style.transform = 'scale(1)';
            element.style.boxShadow = 'none';
        }, 1000);
    }
}

// Exporter le profil
function exportProfile() {
    showNotification('Info', 'Export du profil en cours...', 'info');

    setTimeout(() => {
        // Simulation du téléchargement
        const link = document.createElement('a');
        link.href = 'data:text/plain;charset=utf-8,' + encodeURIComponent('Profil EVC - Jean Dupont\n\nInformations exportées le ' + new Date().toLocaleDateString());
        link.download = 'profil-evc-jean-dupont.txt';
        link.click();

        showNotification('Succès', 'Profil exporté avec succès !', 'success');
    }, 1500);
}

// Prévisualiser le profil
function previewProfile() {
    const firstName = document.getElementById('firstName').value;
    const lastName = document.getElementById('lastName').value;
    const email = document.getElementById('email').value;
    const level = document.querySelector('input[name="currentLevel"]:checked')?.value || 'Non spécifié';

    const previewContent = `
        <div class="text-center mb-3">
            <img src="${document.getElementById('photoPreview').src}" class="rounded-circle" width="80" height="80">
            <h5 class="mt-2">${firstName} ${lastName}</h5>
            <p class="text-muted">${email}</p>
            <p class="text-muted">Niveau: ${level}</p>
        </div>
    `;

    alert('Prévisualisation du profil:\n\n' + previewContent.replace(/<[^>]*>/g, ''));
}
</script>
@endsection
