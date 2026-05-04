@extends('layouts.admin')

@section('title', 'Profil Étudiant - ' . ($data['student']['prenom'] ?? 'Étudiant') . ' ' . ($data['student']['nom'] ?? ''))

@push('styles')
<style>
    /* Styles modernes et minimalistes */
    body {
        background: #0f172a;
    }

    .profile-header-card {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 20px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255,255,255,0.3);
        box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    }

    .profile-avatar-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 700;
        color: white;
        border: 4px solid rgba(255,255,255,0.3);
        box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    }

    .stat-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        transition: all 0.3s ease;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        border-color: #4fc3f7;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-label {
        color: #94a3b8;
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }

    .info-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .info-card:hover {
        border-color: #4fc3f7;
        box-shadow: 0 8px 25px rgba(79, 195, 247, 0.2);
    }

    .info-card-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        padding: 1rem 1.5rem;
        color: white;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-card-body {
        padding: 1.5rem;
        color: white;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #334155;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #94a3b8;
        font-size: 0.9rem;
    }

    .info-value {
        color: white;
        font-weight: 600;
    }

    .progress-modern {
        height: 8px;
        border-radius: 10px;
        background: #334155;
    }

    .progress-bar-modern {
        background: linear-gradient(90deg, #56ab2f 0%, #a8e6cf 100%);
        border-radius: 10px;
        transition: width 1s ease;
    }

    .badge-modern {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .badge-success-modern {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        color: white;
    }

    .badge-warning-modern {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .badge-danger-modern {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }

    .badge-info-modern {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .table-modern {
        color: white;
    }

    .table-modern thead {
        background: #1e293b;
        border-bottom: 2px solid #4fc3f7;
    }

    .table-modern tbody tr {
        border-bottom: 1px solid #334155;
        transition: background 0.2s ease;
    }

    .table-modern tbody tr:hover {
        background: rgba(79, 195, 247, 0.1);
    }

    .btn-modern {
        border-radius: 10px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary-modern {
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
        color: white;
    }

    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(79, 195, 247, 0.4);
        color: white;
    }

    .btn-success-modern {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        color: white;
    }

    .btn-success-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(86, 171, 47, 0.4);
        color: white;
    }

    .btn-warning-modern {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .btn-warning-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(240, 147, 251, 0.4);
        color: white;
    }

    .project-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        cursor: pointer;
    }

    .project-card:hover {
        transform: translateY(-8px);
        border-color: #4fc3f7;
        box-shadow: 0 12px 40px rgba(79, 195, 247, 0.3);
    }

    .project-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #0f172a;
    }

    .project-placeholder {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .project-gallery {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        padding: 12px;
    }

    .project-gallery-main {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        background: #0b1220;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .project-gallery-main img {
        width: 100%;
        max-height: 460px;
        object-fit: contain;
        display: block;
    }

    .gallery-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 42px;
        height: 42px;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.22);
        background: rgba(0, 0, 0, 0.35);
        color: #fff;
        font-size: 26px;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 2;
    }

    .gallery-nav-btn.prev { left: 10px; }
    .gallery-nav-btn.next { right: 10px; }

    .project-gallery-thumbs {
        display: flex;
        gap: 8px;
        margin-top: 10px;
        overflow-x: auto;
        padding-bottom: 4px;
    }

    .gallery-thumb {
        border: 2px solid transparent;
        background: rgba(255, 255, 255, 0.06);
        border-radius: 10px;
        padding: 0;
        width: 88px;
        height: 64px;
        overflow: hidden;
        flex: 0 0 auto;
        cursor: pointer;
    }

    .gallery-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .gallery-thumb.active { border-color: #4fc3f7; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .fade-in {
        animation: fadeIn 0.5s ease;
    }

    /* Mobile Optimizations */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        .profile-header-card {
            padding: 1rem !important;
            border-radius: 15px;
        }
        .info-card {
            border-radius: 15px;
        }
        .info-card-body {
            padding: 1rem !important;
        }
        .stat-card {
            padding: 1rem !important;
            border-radius: 15px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header Profil -->
    <div class="profile-header-card fade-in">
        <div class="row align-items-center">
            <div class="col-auto">
                @if(!empty($data['student']['photo_url']))
                    <img src="{{ $data['student']['photo_url'] }}" alt="Photo" class="profile-avatar">
                @else
                    <div class="profile-avatar-placeholder">
                        {{ strtoupper(substr($data['student']['prenom'] ?? 'E', 0, 1)) }}{{ strtoupper(substr($data['student']['nom'] ?? 'T', 0, 1)) }}
                    </div>
                @endif
            </div>
            <div class="col">
                @php
                    $prenom = !empty($data['student']['prenom']) && $data['student']['prenom'] !== '—' ? $data['student']['prenom'] : 'Étudiant';
                    $nom = !empty($data['student']['nom']) && $data['student']['nom'] !== '—' ? $data['student']['nom'] : '';
                    $fullName = trim($prenom . ' ' . $nom);
                @endphp
                <h1 class="h2 mb-2">{{ $fullName }}</h1>
                <div class="d-flex flex-wrap gap-2 mb-2">
                    @if(!empty($data['student']['formation']) && $data['student']['formation'] !== '—')
                    <span class="badge badge-modern badge-info-modern">
                        <i class="fas fa-graduation-cap me-1"></i>
                        {{ $data['student']['formation'] }}
                    </span>
                    @endif
                    <span class="badge badge-modern {{ $data['student']['status'] === 'active' ? 'badge-success-modern' : 'badge-danger-modern' }}">
                        <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                        {{ ucfirst($data['student']['status'] ?? 'active') }}
                    </span>
                    @if(!empty($data['student']['student_id']))
                    <span class="badge badge-modern" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <i class="fas fa-id-card me-1"></i>
                        {{ $data['student']['student_id'] }}
                    </span>
                    @endif
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <small class="text-white-50">Email</small>
                        <p class="mb-0">
                            <i class="fas fa-envelope me-2"></i>
                            {{ $data['student']['email'] }}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-white-50">Téléphone</small>
                        <p class="mb-0">
                            <i class="fas fa-phone me-2"></i>
                            {{ !empty($data['student']['phone']) && $data['student']['phone'] !== '—' ? $data['student']['phone'] : 'Non renseigné' }}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-white-50">Localisation</small>
                        <p class="mb-0">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            @php
                                $ville = !empty($data['student']['ville']) && $data['student']['ville'] !== '—' ? $data['student']['ville'] : null;
                                $pays = !empty($data['student']['pays']) && $data['student']['pays'] !== '—' ? $data['student']['pays'] : null;
                            @endphp
                            @if($ville && $pays)
                                {{ $ville }}, {{ $pays }}
                            @elseif($ville)
                                {{ $ville }}
                            @elseif($pays)
                                {{ $pays }}
                            @else
                                Non renseigné
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('admin.students.edit', $data['student']['id']) }}" class="btn btn-modern btn-warning-modern">
                        <i class="fas fa-edit me-1"></i>Modifier
                    </a>
                    <button class="btn btn-modern btn-success-modern" onclick="window.print()">
                        <i class="fas fa-print me-1"></i>Imprimer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4 fade-in" style="animation-delay: 0.1s;">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="width: 60px; height: 60px; background: rgba(79, 195, 247, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #4fc3f7; font-size: 1.8rem;">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div>
                        <h3 class="stat-number">{{ $data['stats']['total_tp'] }}</h3>
                        <p class="stat-label mb-0">TPs Soumis</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="width: 60px; height: 60px; background: rgba(86, 171, 47, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #56ab2f; font-size: 1.8rem;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <h3 class="stat-number">{{ $data['stats']['tp_valides'] }}</h3>
                        <p class="stat-label mb-0">TPs Validés</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="width: 60px; height: 60px; background: rgba(240, 147, 251, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #f093fb; font-size: 1.8rem;">
                        <i class="fas fa-palette"></i>
                    </div>
                    <div>
                        <h3 class="stat-number">{{ $data['stats']['total_projects'] }}</h3>
                        <p class="stat-label mb-0">Projets</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="width: 60px; height: 60px; background: rgba(255, 193, 7, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #ffc107; font-size: 1.8rem;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <h3 class="stat-number">{{ $data['stats']['progression'] }}%</h3>
                        <p class="stat-label mb-0">Progression</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Colonne Gauche -->
        <div class="col-lg-4">
            <!-- Informations Personnelles -->
            <div class="info-card fade-in" style="animation-delay: 0.2s;">
                <div class="info-card-header">
                    <i class="fas fa-user"></i>
                    <span>Informations Personnelles</span>
                </div>
                <div class="info-card-body">
                    <div class="info-item">
                        <span class="info-label">Prénom</span>
                        <span class="info-value">{{ $data['student']['prenom'] !== '—' ? $data['student']['prenom'] : '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Nom</span>
                        <span class="info-value">{{ $data['student']['nom'] !== '—' ? $data['student']['nom'] : '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date de naissance</span>
                        <span class="info-value">
                            @if(!empty($data['student']['date_of_birth']) && $data['student']['date_of_birth'] !== '—')
                                {{ date('d/m/Y', strtotime($data['student']['date_of_birth'])) }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Genre</span>
                        <span class="info-value">
                            @if(!empty($data['student']['gender']) && $data['student']['gender'] !== '—')
                                @if($data['student']['gender'] === 'Homme')
                                    <i class="fas fa-mars text-primary me-2"></i>Homme
                                @elseif($data['student']['gender'] === 'Femme')
                                    <i class="fas fa-venus text-danger me-2"></i>Femme
                                @else
                                    {{ $data['student']['gender'] }}
                                @endif
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    @if(!empty($data['student']['student_id']))
                    <div class="info-item">
                        <span class="info-label">ID Étudiant</span>
                        <span class="info-value">{{ $data['student']['student_id'] }}</span>
                    </div>
                    @endif
                    @if(!empty($data['student']['created_at']))
                    <div class="info-item">
                        <span class="info-label">Inscription</span>
                        <span class="info-value">{{ date('d/m/Y', strtotime($data['student']['created_at'])) }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Contact -->
            <div class="info-card fade-in" style="animation-delay: 0.3s;">
                <div class="info-card-header">
                    <i class="fas fa-phone"></i>
                    <span>Contact</span>
                </div>
                <div class="info-card-body">
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $data['student']['email'] }}</span>
                    </div>
                    @if(!empty($data['student']['phone']) && $data['student']['phone'] !== '—')
                    <div class="info-item">
                        <span class="info-label">Téléphone</span>
                        <span class="info-value">
                            <i class="fas fa-phone text-info me-2"></i>
                            {{ $data['student']['phone'] }}
                        </span>
                    </div>
                    @endif
                    @if(!empty($data['student']['whatsapp']) && $data['student']['whatsapp'] !== '—' && $data['student']['whatsapp'] !== $data['student']['phone'])
                    <div class="info-item">
                        <span class="info-label">WhatsApp</span>
                        <span class="info-value">
                            <i class="fab fa-whatsapp text-success me-2"></i>
                            {{ $data['student']['whatsapp'] }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Biographie -->
            @if(!empty($data['student']['biography']))
            <div class="info-card fade-in" style="animation-delay: 0.35s;">
                <div class="info-card-header" style="background: linear-gradient(135deg, #833AB4 0%, #C13584 100%);">
                    <i class="fas fa-pen-fancy"></i>
                    <span>Biographie</span>
                </div>
                <div class="info-card-body">
                    <p class="text-white-50 mb-0" style="line-height: 1.8; white-space: pre-line;">{{ $data['student']['biography'] }}</p>
                </div>
            </div>
            @endif

            <!-- Formation & Académique -->
            <div class="info-card fade-in" style="animation-delay: 0.4s;">
                <div class="info-card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Formation & Académique</span>
                </div>
                <div class="info-card-body">
                    @php
                        $hasAcademicInfo = (!empty($data['student']['formation']) && $data['student']['formation'] !== '—')
                                        || (!empty($data['student']['specialization']) && $data['student']['specialization'] !== '—')
                                        || (!empty($data['student']['level']) && $data['student']['level'] !== '—')
                                        || (!empty($data['student']['years_experience']) && $data['student']['years_experience'] !== '—')
                                        || (!empty($data['student']['industry_sector']) && $data['student']['industry_sector'] !== '—');
                    @endphp

                    @if($hasAcademicInfo)
                        @if(!empty($data['student']['formation']) && $data['student']['formation'] !== '—')
                        <div class="info-item">
                            <span class="info-label">Formation</span>
                            <span class="info-value">
                                <i class="fas fa-book text-primary me-2"></i>
                                {{ $data['student']['formation'] }}
                            </span>
                        </div>
                        @endif
                        @if(!empty($data['student']['specialization']) && $data['student']['specialization'] !== '—')
                        <div class="info-item">
                            <span class="info-label">Spécialisation</span>
                            <span class="info-value">{{ $data['student']['specialization'] }}</span>
                        </div>
                        @endif
                        @if(!empty($data['student']['level']) && $data['student']['level'] !== '—')
                        <div class="info-item">
                            <span class="info-label">Niveau</span>
                            <span class="info-value">{{ $data['student']['level'] }}</span>
                        </div>
                        @endif
                        @if(!empty($data['student']['years_experience']) && $data['student']['years_experience'] !== '—')
                        <div class="info-item">
                            <span class="info-label">Expérience</span>
                            <span class="info-value">{{ $data['student']['years_experience'] }} an(s)</span>
                        </div>
                        @endif
                        @if(!empty($data['student']['industry_sector']) && $data['student']['industry_sector'] !== '—')
                        <div class="info-item">
                            <span class="info-label">Secteur</span>
                            <span class="info-value">{{ $data['student']['industry_sector'] }}</span>
                        </div>
                        @endif
                    @else
                        <p class="text-center text-white-50 py-3 mb-0">Aucune information académique supplémentaire</p>
                    @endif
                </div>
            </div>

            <!-- Localisation -->
            <div class="info-card fade-in" style="animation-delay: 0.45s;">
                <div class="info-card-header">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Localisation</span>
                </div>
                <div class="info-card-body">
                    @php
                        $hasLocation = (!empty($data['student']['quartier']) && $data['student']['quartier'] !== '—')
                                    || (!empty($data['student']['ville']) && $data['student']['ville'] !== '—')
                                    || (!empty($data['student']['pays']) && $data['student']['pays'] !== '—');
                    @endphp

                    @if($hasLocation)
                        @if(!empty($data['student']['quartier']) && $data['student']['quartier'] !== '—')
                        <div class="info-item">
                            <span class="info-label">Quartier</span>
                            <span class="info-value">
                                <i class="fas fa-location-arrow text-info me-2"></i>
                                {{ $data['student']['quartier'] }}
                            </span>
                        </div>
                        @endif
                        @if(!empty($data['student']['ville']) && $data['student']['ville'] !== '—')
                        <div class="info-item">
                            <span class="info-label">Ville</span>
                            <span class="info-value">
                                <i class="fas fa-city text-primary me-2"></i>
                                {{ $data['student']['ville'] }}
                            </span>
                        </div>
                        @endif
                        @if(!empty($data['student']['pays']) && $data['student']['pays'] !== '—')
                        <div class="info-item">
                            <span class="info-label">Pays</span>
                            <span class="info-value">
                                <i class="fas fa-flag text-success me-2"></i>
                                {{ $data['student']['pays'] }}
                            </span>
                        </div>
                        @endif
                    @else
                        <p class="text-center text-white-50 py-3 mb-0">Aucune information de localisation</p>
                    @endif
                </div>
            </div>

            <!-- Score CVthèque -->
            @if(isset($data['cvtheque']) && $data['cvtheque'])
            <div class="info-card fade-in" style="animation-delay: 0.5s;">
                <div class="info-card-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <i class="fas fa-briefcase"></i>
                    <span>Score CVthèque</span>
                </div>
                <div class="info-card-body">
                    <div class="text-center mb-3">
                        @php
                            $score = $data['cvtheque']['profile_completion_score'] ?? 0;
                            $scoreColor = $score >= 75 ? '#56ab2f' : ($score >= 50 ? '#ffc107' : '#dc3545');
                        @endphp
                        <div style="width: 120px; height: 120px; margin: 0 auto; position: relative;">
                            <svg width="120" height="120" style="transform: rotate(-90deg);">
                                <circle cx="60" cy="60" r="50" fill="none" stroke="#334155" stroke-width="8"/>
                                <circle cx="60" cy="60" r="50" fill="none" stroke="{{ $scoreColor }}" stroke-width="8"
                                        stroke-dasharray="{{ 2 * 3.14159 * 50 }}"
                                        stroke-dashoffset="{{ 2 * 3.14159 * 50 * (1 - $score / 100) }}"
                                        style="transition: stroke-dashoffset 1s ease;"/>
                            </svg>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                <h2 class="mb-0" style="color: {{ $scoreColor }}; font-size: 2rem; font-weight: 700;">{{ $score }}%</h2>
                            </div>
                        </div>
                        <small class="text-white-50 mt-2 d-block">Profil complet</small>
                    </div>
                    <div class="row g-2">
                        @if(!empty($data['cvtheque']['professional_title']))
                        <div class="col-6">
                            <div class="text-center p-2 rounded" style="background: rgba(86, 171, 47, 0.1);">
                                <i class="fas fa-check-circle text-success mb-1"></i>
                                <small class="d-block text-white-50">Titre pro</small>
                            </div>
                        </div>
                        @endif
                        @if(!empty($data['cvtheque']['professional_summary']) || !empty($data['cvtheque']['bio']))
                        <div class="col-6">
                            <div class="text-center p-2 rounded" style="background: rgba(86, 171, 47, 0.1);">
                                <i class="fas fa-check-circle text-success mb-1"></i>
                                <small class="d-block text-white-50">Bio</small>
                            </div>
                        </div>
                        @endif
                        @if(!empty($data['cvtheque']['cv_file_path']))
                        <div class="col-6">
                            <div class="text-center p-2 rounded" style="background: rgba(86, 171, 47, 0.1);">
                                <i class="fas fa-check-circle text-success mb-1"></i>
                                <small class="d-block text-white-50">CV</small>
                            </div>
                        </div>
                        @endif
                        @if(!empty($data['cvtheque']['motivation_letter_path']))
                        <div class="col-6">
                            <div class="text-center p-2 rounded" style="background: rgba(86, 171, 47, 0.1);">
                                <i class="fas fa-check-circle text-success mb-1"></i>
                                <small class="d-block text-white-50">Lettre</small>
                            </div>
                        </div>
                        @endif
                        @php
                            $allSkillsCheck = [];
                            if (!empty($data['cvtheque']['technical_skills'])) {
                                $techSkills = is_string($data['cvtheque']['technical_skills']) ? json_decode($data['cvtheque']['technical_skills'], true) : $data['cvtheque']['technical_skills'];
                                if (is_array($techSkills)) $allSkillsCheck = array_merge($allSkillsCheck, $techSkills);
                            }
                            if (!empty($data['cvtheque']['software_skills'])) {
                                $softSkills = is_string($data['cvtheque']['software_skills']) ? json_decode($data['cvtheque']['software_skills'], true) : $data['cvtheque']['software_skills'];
                                if (is_array($softSkills)) $allSkillsCheck = array_merge($allSkillsCheck, $softSkills);
                            }
                        @endphp
                        @if(!empty($allSkillsCheck))
                        <div class="col-6">
                            <div class="text-center p-2 rounded" style="background: rgba(86, 171, 47, 0.1);">
                                <i class="fas fa-check-circle text-success mb-1"></i>
                                <small class="d-block text-white-50">Compétences</small>
                            </div>
                        </div>
                        @endif
                        @php
                            $linkedinCheck = $data['cvtheque']['linkedin_url'] ?? $data['cvtheque']['linkedin_profile'] ?? '';
                        @endphp
                        @if(!empty($linkedinCheck))
                        <div class="col-6">
                            <div class="text-center p-2 rounded" style="background: rgba(86, 171, 47, 0.1);">
                                <i class="fas fa-check-circle text-success mb-1"></i>
                                <small class="d-block text-white-50">LinkedIn</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Paiements Formation -->
            <div class="info-card fade-in" style="animation-delay: 0.55s;">
                <div class="info-card-header" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Paiements Formation</span>
                </div>
                <div class="info-card-body">
                    <!-- Résumé Financier - Toujours affiché -->
                    <div class="row g-3 mb-4">
                        <!-- Total Formation -->
                        <div class="col-12">
                            <div class="p-3 rounded text-center" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);">
                                <small class="d-block text-white opacity-75 mb-1">
                                    <i class="fas fa-graduation-cap me-1"></i>Montant Total Formation
                                </small>
                                <h2 class="mb-0 text-white fw-bold" style="font-size: 2rem;">
                                    {{ number_format($data['stats']['total_factures'], 0, ',', ' ') }} <small>FCFA</small>
                                </h2>
                            </div>
                        </div>

                        @php
                        $totalFacturesSafe = (float) ($data['stats']['total_factures'] ?? 0);
                        $totalPayeSafe = (float) ($data['stats']['total_paye'] ?? 0);
                        $soldeRestantSafe = (float) ($data['stats']['solde_restant'] ?? 0);
                        if ($soldeRestantSafe < 0) {
                            $soldeRestantSafe = 0;
                        }
                        $pourcentagePaye = $totalFacturesSafe > 0 ? round(($totalPayeSafe / $totalFacturesSafe) * 100) : 0;
                        if ($pourcentagePaye < 0) $pourcentagePaye = 0;
                        if ($pourcentagePaye > 100) $pourcentagePaye = 100;
                    @endphp

                        <!-- Total Payé -->
                        <div class="col-6">
                            <div class="p-3 rounded text-center" style="background: rgba(34, 197, 94, 0.15); border: 2px solid #22c55e;">
                                <small class="d-block text-white-50 mb-1">
                                    <i class="fas fa-check-circle me-1"></i>Total Payé
                                </small>
                                <h4 class="mb-0 text-success fw-bold">
                                    {{ number_format($data['stats']['total_paye'], 0, ',', ' ') }}
                                </h4>
                                <small class="text-success">FCFA</small>
                            </div>
                        </div>

                        <!-- Reste à Payer -->
                        <div class="col-6">
                            <div class="p-3 rounded text-center" style="background: {{ $data['stats']['solde_restant'] > 0 ? 'rgba(239, 68, 68, 0.15)' : 'rgba(34, 197, 94, 0.15)' }}; border: 2px solid {{ $data['stats']['solde_restant'] > 0 ? '#ef4444' : '#22c55e' }};">
                                <small class="d-block text-white-50 mb-1">
                                    <i class="fas {{ $data['stats']['solde_restant'] > 0 ? 'fa-exclamation-circle' : 'fa-check-circle' }} me-1"></i>Reste à Payer
                                </small>
                                <h4 class="mb-0 fw-bold {{ $data['stats']['solde_restant'] > 0 ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($soldeRestantSafe, 0, ',', ' ') }}
                                </h4>
                                <small class="{{ $data['stats']['solde_restant'] > 0 ? 'text-danger' : 'text-success' }}">FCFA</small>
                            </div>
                        </div>
                    </div>

                    <!-- Barre de progression du paiement -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-white-50">Progression Paiement</small>
                            <strong style="color: {{ $pourcentagePaye >= 100 ? '#22c55e' : '#10b981' }};">{{ $pourcentagePaye }}%</strong>
                        </div>
                        <div class="progress-modern">
                            <div class="progress-bar-modern" style="width: {{ $pourcentagePaye }}%; background: linear-gradient(90deg, #10b981 0%, {{ $pourcentagePaye >= 100 ? '#22c55e' : '#059669' }} 100%);"></div>
                        </div>
                    </div>

                    <!-- Détail des tranches -->
                    @if(isset($data['paiements']) && count($data['paiements']) > 0)
                        <hr style="border-color: #334155; margin: 1.5rem 0;">
                        <h6 class="text-white mb-3">
                            <i class="fas fa-list-ul me-2"></i>Détail des Paiements ({{ count($data['paiements']) }})
                        </h6>
                        @foreach($data['paiements'] as $paiement)
                            @php
                                $isCompleted = isset($paiement->status) ? $paiement->status === 'completed' : (isset($paiement->statut) && $paiement->statut === 'validé');
                                $isPending = isset($paiement->status) ? $paiement->status === 'pending' : (isset($paiement->statut) && $paiement->statut === 'en attente');
                                $amount = $paiement->amount ?? $paiement->montant ?? 0;
                                $tranche = $paiement->installment_number ?? null;
                                $date = isset($paiement->created_at) ? \Carbon\Carbon::parse($paiement->created_at)->format('d/m/Y') : '-';
                            @endphp
                            <div class="p-3 mb-2 rounded" style="background: rgba(30, 41, 59, 0.5); border: 1px solid #334155;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($tranche)
                                            <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-size: 0.8rem; padding: 0.4rem 0.8rem;">
                                                <i class="fas fa-layer-group me-1"></i>Tranche {{ $tranche }}
                                            </span>
                                        @endif
                                        @if($isCompleted)
                                            <span class="badge badge-modern badge-success-modern">
                                                <i class="fas fa-check-circle"></i> Payé
                                            </span>
                                        @elseif($isPending)
                                            <span class="badge badge-modern badge-warning-modern">
                                                <i class="fas fa-clock"></i> En attente
                                            </span>
                                        @else
                                            <span class="badge badge-modern badge-danger-modern">
                                                <i class="fas fa-times-circle"></i> Annulé
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold {{ $isCompleted ? 'text-success' : 'text-warning' }}" style="font-size: 1.1rem;">
                                            {{ number_format($amount, 0, ',', ' ') }} FCFA
                                        </div>
                                        <small class="text-white-50">
                                            <i class="fas fa-calendar me-1"></i>{{ $date }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Lien vers page complète des paiements -->
                        <div class="mt-3 text-center">
                            <a href="{{ route('admin.payments.index') }}" class="btn btn-modern btn-primary-modern w-100">
                                <i class="fas fa-file-invoice-dollar me-2"></i>Voir tous les paiements de la plateforme
                            </a>
                        </div>
                    @else
                        <hr style="border-color: #334155; margin: 1.5rem 0;">
                        <div class="text-center py-4" style="background: rgba(239, 68, 68, 0.1); border-radius: 12px;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 2.5rem; color: #ef4444; margin-bottom: 1rem;"></i>
                            <p class="text-white mb-2"><strong>Aucun paiement enregistré</strong></p>
                            <p class="text-white-50 mb-0 small">Cet étudiant n'a pas encore effectué de paiement pour sa formation.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne Droite -->
        <div class="col-lg-8">
            <!-- Progression -->
            <div class="info-card fade-in" style="animation-delay: 0.6s;">
                <div class="info-card-header" style="background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);">
                    <i class="fas fa-chart-line"></i>
                    <span>Progression Académique</span>
                </div>
                <div class="info-card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Progression globale</span>
                        <span class="fw-bold">{{ $data['stats']['progression'] }}%</span>
                    </div>
                    <div class="progress-modern">
                        <div class="progress-bar-modern" style="width: {{ $data['stats']['progression'] }}%"></div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-3 text-center">
                            <div class="p-3 rounded" style="background: rgba(79, 195, 247, 0.1);">
                                <h4 class="mb-0" style="color: #4fc3f7;">{{ $data['stats']['total_tp'] }}</h4>
                                <small class="text-white-50">TPs Total</small>
                            </div>
                        </div>
                        <div class="col-3 text-center">
                            <div class="p-3 rounded" style="background: rgba(86, 171, 47, 0.1);">
                                <h4 class="mb-0" style="color: #56ab2f;">{{ $data['stats']['tp_valides'] }}</h4>
                                <small class="text-white-50">Validés</small>
                            </div>
                        </div>
                        <div class="col-3 text-center">
                            <div class="p-3 rounded" style="background: rgba(255, 193, 7, 0.1);">
                                <h4 class="mb-0" style="color: #ffc107;">{{ $data['stats']['tp_en_cours'] }}</h4>
                                <small class="text-white-50">En attente</small>
                            </div>
                        </div>
                        <div class="col-3 text-center">
                            <div class="p-3 rounded" style="background: rgba(220, 53, 69, 0.1);">
                                <h4 class="mb-0" style="color: #dc3545;">{{ $data['stats']['tp_rejetes'] }}</h4>
                                <small class="text-white-50">Rejetés</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bouton Voir les travaux -->
            <div class="info-card fade-in" style="animation-delay: 0.7s;">
                <div class="info-card-header" style="background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);">
                    <i class="fas fa-briefcase"></i>
                    <span>Travaux de l'étudiant</span>
                </div>
                <div class="info-card-body text-center py-4">
                    <div class="row mb-4">
                        <div class="col-4">
                            <div class="p-3 rounded" style="background: rgba(79, 195, 247, 0.1);">
                                <h4 class="mb-0" style="color: #4fc3f7;">{{ $data['stats']['total_tp'] ?? 0 }}</h4>
                                <small class="text-white-50">TPs</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 rounded" style="background: rgba(245, 158, 11, 0.1);">
                                <h4 class="mb-0" style="color: #f59e0b;">{{ $data['stats']['todos_non_traites'] ?? 0 }}</h4>
                                <small class="text-white-50">Non traités</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 rounded" style="background: rgba(16, 185, 129, 0.1);">
                                <h4 class="mb-0" style="color: #10b981;">{{ $data['stats']['todos_traites'] ?? 0 }}</h4>
                                <small class="text-white-50">Traités</small>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.students.works', !empty($data['student']['student_row_id']) ? $data['student']['student_row_id'] : ['id' => ($data['student']['user_id'] ?? $data['student']['id']), 'source' => 'user']) }}"
                       class="btn btn-lg btn-modern"
                       style="background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%); color: white; padding: 0.75rem 2.5rem; font-size: 1.1rem; border-radius: 14px; box-shadow: 0 6px 20px rgba(79,195,247,0.3); transition: all 0.3s ease;"
                       onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 30px rgba(79,195,247,0.4)';"
                       onmouseout="this.style.transform='none'; this.style.boxShadow='0 6px 20px rgba(79,195,247,0.3)';">
                        <i class="fas fa-eye me-2"></i>Voir tous les travaux
                    </a>
                </div>
            </div>

            <!-- Formations Attribuées -->
            <div class="info-card fade-in" style="animation-delay: 0.72s;">
                <div class="info-card-header" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Formations Attribuées ({{ isset($data['student_programs']) ? $data['student_programs']->count() : 0 }})</span>
                </div>
                <div class="info-card-body">
                    @if(isset($data['student_programs']) && $data['student_programs']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-modern table-sm">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Catégorie</th>
                                        <th>Niveau</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['student_programs'] as $program)
                                    <tr>
                                        <td>{{ $program->name ?? 'Sans nom' }}</td>
                                        <td><span class="badge badge-modern badge-info-modern">{{ $program->category_id ?? 'N/A' }}</span></td>
                                        <td>{{ $program->level ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-modern {{ $program->status === 'active' ? 'badge-success-modern' : 'badge-warning-modern' }}">
                                                {{ $program->status ?? 'N/A' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-white-50 py-3 mb-0">Aucune formation attribuée</p>
                    @endif
                </div>
            </div>

            <!-- Profil CVthèque -->
            @if(isset($data['cvtheque']) && $data['cvtheque'])
            <div class="info-card fade-in" style="animation-delay: 0.75s;">
                <div class="info-card-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <i class="fas fa-briefcase"></i>
                    <span>Profil CVthèque</span>
                </div>
                <div class="info-card-body">
                    @if(!empty($data['cvtheque']['professional_title']))
                    <div class="mb-4">
                        <h5 class="text-white mb-2">
                            <i class="fas fa-user-tie text-primary me-2"></i>
                            {{ $data['cvtheque']['professional_title'] }}
                        </h5>
                        @if(!empty($data['cvtheque']['current_position']) || !empty($data['cvtheque']['current_company']))
                        <p class="text-white-50 mb-0">
                            @if(!empty($data['cvtheque']['current_position']))
                                {{ $data['cvtheque']['current_position'] }}
                            @endif
                            @if(!empty($data['cvtheque']['current_company']))
                                @if(!empty($data['cvtheque']['current_position'])) chez @endif
                                <strong>{{ $data['cvtheque']['current_company'] }}</strong>
                            @endif
                        </p>
                        @endif
                    </div>
                    @endif

                    @if(!empty($data['cvtheque']['professional_summary']) || !empty($data['cvtheque']['bio']))
                    <div class="mb-4">
                        <h6 class="text-white-50 mb-2">
                            <i class="fas fa-quote-left me-2"></i>
                            Résumé professionnel
                        </h6>
                        <p class="text-white-50 mb-0" style="line-height: 1.6;">
                            {{ $data['cvtheque']['professional_summary'] ?? $data['cvtheque']['bio'] }}
                        </p>
                    </div>
                    @endif

                    @php
                        $allSkills = [];
                        if (!empty($data['cvtheque']['technical_skills'])) {
                            $techSkills = is_string($data['cvtheque']['technical_skills']) ? json_decode($data['cvtheque']['technical_skills'], true) : $data['cvtheque']['technical_skills'];
                            if (is_array($techSkills)) $allSkills = array_merge($allSkills, $techSkills);
                        }
                        if (!empty($data['cvtheque']['software_skills'])) {
                            $softSkills = is_string($data['cvtheque']['software_skills']) ? json_decode($data['cvtheque']['software_skills'], true) : $data['cvtheque']['software_skills'];
                            if (is_array($softSkills)) $allSkills = array_merge($allSkills, $softSkills);
                        }
                        if (!empty($data['cvtheque']['skills']) && empty($allSkills)) {
                            $skills = is_string($data['cvtheque']['skills']) ? json_decode($data['cvtheque']['skills'], true) : $data['cvtheque']['skills'];
                            if (!is_array($skills)) {
                                $skills = explode(',', $data['cvtheque']['skills']);
                            }
                            $allSkills = $skills;
                        }
                    @endphp

                    @if(!empty($allSkills))
                    <div class="mb-4">
                        <h6 class="text-white-50 mb-2">
                            <i class="fas fa-cogs me-2"></i>
                            Compétences
                        </h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($allSkills as $skill)
                                <span class="badge badge-modern badge-info-modern">{{ trim($skill) }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if(!empty($data['cvtheque']['languages']))
                    <div class="mb-4">
                        <h6 class="text-white-50 mb-2">
                            <i class="fas fa-language me-2"></i>
                            Langues
                        </h6>
                        <div class="d-flex flex-wrap gap-2">
                            @php
                                $languages = is_string($data['cvtheque']['languages']) ? json_decode($data['cvtheque']['languages'], true) : $data['cvtheque']['languages'];
                                if (!is_array($languages)) {
                                    $languages = explode(',', $data['cvtheque']['languages']);
                                }
                            @endphp
                            @foreach($languages as $language)
                                <span class="badge badge-modern badge-success-modern">{{ trim($language) }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="row mb-4">
                        @php
                            $expYears = $data['cvtheque']['years_experience'] ?? $data['cvtheque']['experience_years'] ?? 0;
                        @endphp
                        @if($expYears > 0)
                        <div class="col-6">
                            <div class="p-3 rounded text-center" style="background: rgba(79, 195, 247, 0.1);">
                                <i class="fas fa-calendar-alt d-block mb-2" style="color: #4fc3f7; font-size: 1.5rem;"></i>
                                <h5 class="mb-0" style="color: #4fc3f7;">{{ $expYears }} ans</h5>
                                <small class="text-white-50">Expérience</small>
                            </div>
                        </div>
                        @endif
                        @if(!empty($data['cvtheque']['availability']))
                        <div class="col-6">
                            <div class="p-3 rounded text-center" style="background: rgba(86, 171, 47, 0.1);">
                                <i class="fas fa-clock d-block mb-2" style="color: #56ab2f; font-size: 1.5rem;"></i>
                                <h6 class="mb-0" style="color: #56ab2f;">{{ ucfirst($data['cvtheque']['availability']) }}</h6>
                                <small class="text-white-50">Disponibilité</small>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if(!empty($data['cvtheque']['job_type']) || !empty($data['cvtheque']['salary_expectation']) || $data['cvtheque']['remote_work'] || $data['cvtheque']['willing_to_relocate'])
                    <div class="mb-4">
                        <h6 class="text-white-50 mb-2">
                            <i class="fas fa-briefcase me-2"></i>
                            Préférences d'emploi
                        </h6>
                        <div class="info-item">
                            @if(!empty($data['cvtheque']['job_type']))
                            <span class="info-label">Type de poste</span>
                            <span class="info-value">{{ ucfirst($data['cvtheque']['job_type']) }}</span>
                        </div>
                        @endif
                        @if(!empty($data['cvtheque']['salary_expectation']))
                        <div class="info-item">
                            <span class="info-label">Salaire souhaité</span>
                            <span class="info-value">{{ $data['cvtheque']['salary_expectation'] }}</span>
                        </div>
                        @endif
                        @if($data['cvtheque']['remote_work'])
                        <div class="info-item">
                            <span class="info-label">Télétravail</span>
                            <span class="info-value text-success">✓ Accepté</span>
                        </div>
                        @endif
                        @if($data['cvtheque']['willing_to_relocate'])
                        <div class="info-item">
                            <span class="info-label">Mobilité</span>
                            <span class="info-value text-success">✓ Ouvert à la relocalisation</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    @php
                        $linkedinUrl = $data['cvtheque']['linkedin_url'] ?? $data['cvtheque']['linkedin_profile'] ?? '';
                        $portfolioUrl = $data['cvtheque']['portfolio_url'] ?? $data['cvtheque']['website'] ?? '';
                        $behanceUrl = $data['cvtheque']['behance_url'] ?? $data['cvtheque']['behance_profile'] ?? '';
                        $dribbleUrl = $data['cvtheque']['dribbble_profile'] ?? '';
                        $hasLinks = !empty($linkedinUrl) || !empty($portfolioUrl) || !empty($data['cvtheque']['github_url']) || !empty($behanceUrl) || !empty($dribbleUrl);
                    @endphp

                    @if($hasLinks)
                    <div class="mb-4">
                        <h6 class="text-white-50 mb-2">
                            <i class="fas fa-link me-2"></i>
                            Liens professionnels
                        </h6>
                        <div class="d-flex flex-wrap gap-2">
                            @if(!empty($linkedinUrl))
                            <a href="{{ $linkedinUrl }}" target="_blank" class="btn btn-sm btn-modern btn-primary-modern">
                                <i class="fab fa-linkedin me-1"></i>LinkedIn
                            </a>
                            @endif
                            @if(!empty($portfolioUrl))
                            <a href="{{ $portfolioUrl }}" target="_blank" class="btn btn-sm btn-modern btn-success-modern">
                                <i class="fas fa-globe me-1"></i>{{ !empty($data['cvtheque']['website']) ? 'Site Web' : 'Portfolio' }}
                            </a>
                            @endif
                            @if(!empty($data['cvtheque']['github_url']))
                            <a href="{{ $data['cvtheque']['github_url'] }}" target="_blank" class="btn btn-sm btn-modern" style="background: linear-gradient(135deg, #24292e 0%, #0d1117 100%); color: white;">
                                <i class="fab fa-github me-1"></i>GitHub
                            </a>
                            @endif
                            @if(!empty($behanceUrl))
                            <a href="{{ $behanceUrl }}" target="_blank" class="btn btn-sm btn-modern" style="background: linear-gradient(135deg, #1769ff 0%, #0057ff 100%); color: white;">
                                <i class="fab fa-behance me-1"></i>Behance
                            </a>
                            @endif
                            @if(!empty($dribbleUrl))
                            <a href="{{ $dribbleUrl }}" target="_blank" class="btn btn-sm btn-modern" style="background: linear-gradient(135deg, #ea4c89 0%, #c32361 100%); color: white;">
                                <i class="fab fa-dribbble me-1"></i>Dribbble
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if(!empty($data['cvtheque']['professional_email']) || !empty($data['cvtheque']['phone']))
                    <div class="mb-4">
                        <h6 class="text-white-50 mb-2">
                            <i class="fas fa-address-book me-2"></i>
                            Contact professionnel
                        </h6>
                        @if(!empty($data['cvtheque']['professional_email']))
                        <div class="info-item">
                            <span class="info-label">Email pro</span>
                            <span class="info-value">{{ $data['cvtheque']['professional_email'] }}</span>
                        </div>
                        @endif
                        @if(!empty($data['cvtheque']['phone']))
                        <div class="info-item">
                            <span class="info-label">Téléphone pro</span>
                            <span class="info-value">{{ $data['cvtheque']['phone'] }}</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    @php
                        $rapportPath = $data['cvtheque']['rapport_file_path'] ?? $data['cvtheque']['report_file_path'] ?? '';
                        $hasDocuments = !empty($data['cvtheque']['cv_file_path'])
                                     || !empty($data['cvtheque']['motivation_letter_path'])
                                     || !empty($data['cvtheque']['pressbook_file_path'])
                                     || !empty($rapportPath)
                                     || !empty($data['cvtheque']['portfolio_files']);
                    @endphp

                    @if($hasDocuments)
                    <div class="mb-0">
                        <h6 class="text-white-50 mb-2">
                            <i class="fas fa-file-download me-2"></i>
                            Documents uploadés
                        </h6>
                        <div class="d-flex flex-wrap gap-2">
                            @if(!empty($data['cvtheque']['cv_file_path']))
                            <a href="{{ \App\Models\MediaUrl::fromPath($data['cvtheque']['cv_file_path']) }}" target="_blank" class="btn btn-sm btn-modern" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white;">
                                <i class="fas fa-file-pdf me-1"></i>CV
                            </a>
                            @endif
                            @if(!empty($data['cvtheque']['motivation_letter_path']))
                            <a href="{{ \App\Models\MediaUrl::fromPath($data['cvtheque']['motivation_letter_path']) }}" target="_blank" class="btn btn-sm btn-modern" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); color: white;">
                                <i class="fas fa-envelope me-1"></i>Lettre de motivation
                            </a>
                            @endif
                            @if(!empty($data['cvtheque']['portfolio_files']))
                            <a href="{{ \App\Models\MediaUrl::fromPath($data['cvtheque']['portfolio_files']) }}" target="_blank" class="btn btn-sm btn-modern" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                <i class="fas fa-images me-1"></i>Portfolio
                            </a>
                            @endif
                            @if(!empty($data['cvtheque']['pressbook_file_path']))
                            <a href="{{ \App\Models\MediaUrl::fromPath($data['cvtheque']['pressbook_file_path']) }}" target="_blank" class="btn btn-sm btn-modern" style="background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%); color: white;">
                                <i class="fas fa-book me-1"></i>Pressbook
                            </a>
                            @endif
                            @if(!empty($rapportPath))
                            <a href="{{ \App\Models\MediaUrl::fromPath($rapportPath) }}" target="_blank" class="btn btn-sm btn-modern" style="background: linear-gradient(135deg, #198754 0%, #157347 100%); color: white;">
                                <i class="fas fa-graduation-cap me-1"></i>Rapport de fin de formation
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Animations au scroll
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    });

    document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
});

document.addEventListener('click', function(e) {
    const card = e.target.closest('[data-project-open]');
    if (!card) return;

    const files = (() => {
        try {
            const raw = card.getAttribute('data-project-files') || '';
            if (raw) {
                const parsed = JSON.parse(raw);
                return Array.isArray(parsed) ? parsed.filter(f => f && f.url) : [];
            }
        } catch (err) {
            // ignore
        }

        try {
            const rawImages = card.getAttribute('data-project-images') || '[]';
            const parsedImages = JSON.parse(rawImages);
            const imgs = Array.isArray(parsedImages) ? parsedImages.filter(i => i && i.url) : [];
            return imgs.map(i => ({
                url: i.url,
                original_name: i.original_name,
                is_image: true,
            }));
        } catch (err2) {
            return [];
        }
    })();

    const images = files.filter(f => f && f.is_image === true);
    const documents = files.filter(f => f && f.is_image !== true);

    const title = card.getAttribute('data-project-title') || 'Projet';
    const description = card.getAttribute('data-project-description') || '';
    const status = card.getAttribute('data-project-status') || '';
    const createdAt = card.getAttribute('data-project-created') || '';

    const modalId = 'projectGalleryModal';
    const existingModal = document.getElementById(modalId);
    if (existingModal) existingModal.remove();

    const galleryHTML = images.length > 0 ? `
        <div class="project-gallery" data-gallery>
            <div class="project-gallery-main">
                <button type="button" class="gallery-nav-btn prev" data-gallery-prev>‹</button>
                <a href="#" data-gallery-open style="display:block;">
                    <img src="${images[0].url}" alt="${(images[0].original_name || 'Image')}" data-gallery-main-img />
                </a>
                <button type="button" class="gallery-nav-btn next" data-gallery-next>›</button>
            </div>
            <div class="project-gallery-thumbs" data-gallery-thumbs>
                ${images.map((img, idx) => `
                    <button type="button" class="gallery-thumb ${idx === 0 ? 'active' : ''}" data-gallery-thumb data-index="${idx}">
                        <img src="${img.url}" alt="${img.original_name || 'Image'}" />
                    </button>
                `).join('')}
            </div>
        </div>
    ` : `<p class="text-white-50 mb-0">Aucune image disponible pour ce projet.</p>`;

    const documentsHTML = documents.length > 0 ? `
        <div class="mt-3">
            <h6 class="text-white-50 mb-2">Documents</h6>
            <div class="d-flex flex-wrap gap-2">
                ${documents.map((doc) => `
                    <a href="${doc.url}" target="_blank" class="btn btn-sm btn-outline-light">${doc.original_name || 'Document'}</a>
                `).join('')}
            </div>
        </div>
    ` : '';

    const modalHTML = `
        <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content bg-dark text-white">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title">${title}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 text-white-50">${description || ''}</div>
                        <div class="d-flex gap-2 flex-wrap mb-3">
                            ${status ? `<span class="badge bg-info">${status}</span>` : ''}
                            ${createdAt ? `<span class="badge bg-secondary">${createdAt}</span>` : ''}
                        </div>
                        ${galleryHTML}${documentsHTML}
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);
    const modal = new bootstrap.Modal(document.getElementById(modalId));
    modal.show();

    if (images.length > 0) {
        initProjectGalleryModal(images, modalId);
    }
});

document.addEventListener('click', function(e) {
    const btn = e.target.closest('[data-tp-open]');
    if (!btn) return;

    const images = (() => {
        try {
            const raw = btn.getAttribute('data-tp-images') || '[]';
            const parsed = JSON.parse(raw);
            return Array.isArray(parsed) ? parsed.filter(i => i && i.url) : [];
        } catch (err) {
            return [];
        }
    })();

    const title = btn.getAttribute('data-tp-title') || 'TP';
    const status = btn.getAttribute('data-tp-status') || '';
    const createdAt = btn.getAttribute('data-tp-created') || '';

    const modalId = 'tpGalleryModal';
    const existingModal = document.getElementById(modalId);
    if (existingModal) existingModal.remove();

    const galleryHTML = images.length > 0 ? `
        <div class="project-gallery" data-gallery>
            <div class="project-gallery-main">
                <button type="button" class="gallery-nav-btn prev" data-gallery-prev>‹</button>
                <a href="#" data-gallery-open style="display:block;">
                    <img src="${images[0].url}" alt="${(images[0].original_name || 'Image')}" data-gallery-main-img />
                </a>
                <button type="button" class="gallery-nav-btn next" data-gallery-next>›</button>
            </div>
            <div class="project-gallery-thumbs" data-gallery-thumbs>
                ${images.map((img, idx) => `
                    <button type="button" class="gallery-thumb ${idx === 0 ? 'active' : ''}" data-gallery-thumb data-index="${idx}">
                        <img src="${img.url}" alt="${img.original_name || 'Image'}" />
                    </button>
                `).join('')}
            </div>
        </div>
    ` : `<p class="text-white-50 mb-0">Aucune image disponible pour ce TP.</p>`;

    const modalHTML = `
        <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content bg-dark text-white">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title">${title}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex gap-2 flex-wrap mb-3">
                            ${status ? `<span class="badge bg-info">${status}</span>` : ''}
                            ${createdAt ? `<span class="badge bg-secondary">${createdAt}</span>` : ''}
                        </div>
                        ${galleryHTML}
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);
    const modal = new bootstrap.Modal(document.getElementById(modalId));
    modal.show();

    if (images.length > 0) {
        initProjectGalleryModal(images, modalId);
    }
});

function initProjectGalleryModal(images, modalId) {
    const root = document.querySelector(`#${modalId} [data-gallery]`);
    if (!root) return;

    let currentIndex = 0;
    const mainImg = root.querySelector('[data-gallery-main-img]');
    const thumbs = root.querySelectorAll('[data-gallery-thumb]');
    const btnPrev = root.querySelector('[data-gallery-prev]');
    const btnNext = root.querySelector('[data-gallery-next]');
    const btnOpen = root.querySelector('[data-gallery-open]');

    const setIndex = (idx) => {
        if (!mainImg) return;
        if (idx < 0) idx = images.length - 1;
        if (idx >= images.length) idx = 0;

        currentIndex = idx;
        const img = images[currentIndex];
        mainImg.src = img.url;
        mainImg.alt = img.original_name || 'Image';

        thumbs.forEach(t => t.classList.remove('active'));
        const active = root.querySelector(`[data-gallery-thumb][data-index="${currentIndex}"]`);
        if (active) active.classList.add('active');
    };

    thumbs.forEach((t) => {
        t.addEventListener('click', () => {
            const idx = parseInt(t.getAttribute('data-index') || '0', 10);
            setIndex(idx);
        });
    });

    if (btnPrev) btnPrev.addEventListener('click', () => setIndex(currentIndex - 1));
    if (btnNext) btnNext.addEventListener('click', () => setIndex(currentIndex + 1));

    if (btnOpen) {
        btnOpen.addEventListener('click', (e) => {
            e.preventDefault();
            openGalleryLightboxModal(images, currentIndex);
        });
    }

    document.addEventListener('keydown', function onKey(e) {
        const modalEl = document.getElementById(modalId);
        if (!modalEl || !modalEl.classList.contains('show')) return;
        if (e.key === 'ArrowLeft') setIndex(currentIndex - 1);
        if (e.key === 'ArrowRight') setIndex(currentIndex + 1);
    });
}

function openGalleryLightboxModal(images, startIndex) {
    const existing = document.getElementById('galleryLightbox');
    if (existing) existing.remove();

    const overlay = document.createElement('div');
    overlay.id = 'galleryLightbox';
    overlay.style.position = 'fixed';
    overlay.style.inset = '0';
    overlay.style.background = 'rgba(0,0,0,0.85)';
    overlay.style.zIndex = '999999';
    overlay.style.display = 'flex';
    overlay.style.alignItems = 'center';
    overlay.style.justifyContent = 'center';
    overlay.innerHTML = `
        <div style="position: relative; width: min(1100px, 92vw);">
            <button type="button" id="galleryLbClose" style="position:absolute; top:-44px; right:0; background:transparent; border:0; color:#fff; font-size:32px;">×</button>
            <button type="button" id="galleryLbPrev" style="position:absolute; left:-10px; top:50%; transform:translate(-100%,-50%); background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.25); color:#fff; width:44px; height:44px; border-radius:999px;">‹</button>
            <button type="button" id="galleryLbNext" style="position:absolute; right:-10px; top:50%; transform:translate(100%,-50%); background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.25); color:#fff; width:44px; height:44px; border-radius:999px;">›</button>
            <img id="galleryLbImg" src="" alt="" style="width:100%; max-height:80vh; object-fit:contain; border-radius:12px; background:#111;" />
            <div id="galleryLbCaption" style="color:rgba(255,255,255,0.8); margin-top:10px; font-size:14px;"></div>
        </div>
    `;

    document.body.appendChild(overlay);

    let idx = startIndex || 0;
    const imgEl = overlay.querySelector('#galleryLbImg');
    const captionEl = overlay.querySelector('#galleryLbCaption');

    const render = () => {
        if (!images[idx]) return;
        imgEl.src = images[idx].url;
        imgEl.alt = images[idx].original_name || 'Image';
        captionEl.textContent = images[idx].original_name || '';
    };

    const close = () => overlay.remove();
    overlay.querySelector('#galleryLbClose').addEventListener('click', close);
    overlay.addEventListener('click', (e) => { if (e.target === overlay) close(); });

    overlay.querySelector('#galleryLbPrev').addEventListener('click', () => { idx = (idx - 1 + images.length) % images.length; render(); });
    overlay.querySelector('#galleryLbNext').addEventListener('click', () => { idx = (idx + 1) % images.length; render(); });

    document.addEventListener('keydown', function onKey(e) {
        const lb = document.getElementById('galleryLightbox');
        if (!lb) return;
        if (e.key === 'Escape') close();
        if (e.key === 'ArrowLeft') { idx = (idx - 1 + images.length) % images.length; render(); }
        if (e.key === 'ArrowRight') { idx = (idx + 1) % images.length; render(); }
    });

    render();
}
</script>
@endpush
