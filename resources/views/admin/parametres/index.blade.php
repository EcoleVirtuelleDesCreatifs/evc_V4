@extends('layouts.admin')

@section('title', 'Paramètres')

@push('styles')
<style>
    body {
        background: #0f172a;
    }

    .settings-header {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(37, 99, 235, 0.3);
        animation: fadeIn 0.5s ease;
        position: relative;
        overflow: hidden;
    }

    .settings-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #f97316 0%, #fb923c 100%);
    }

    .settings-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .settings-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .settings-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        animation: fadeIn 0.6s ease;
    }

    .settings-card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #334155;
    }

    .settings-card-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .settings-card-title {
        color: white;
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }

    .form-group-modern {
        margin-bottom: 1.5rem;
    }

    .form-label-modern {
        color: #94a3b8;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control-modern {
        background: rgba(30, 41, 59, 0.5);
        border: 1px solid #334155;
        border-radius: 12px;
        padding: 0.875rem 1.25rem;
        color: white;
        font-size: 1rem;
        transition: all 0.3s ease;
        width: 100%;
    }

    .form-control-modern:focus {
        outline: none;
        border-color: #f97316;
        background: rgba(30, 41, 59, 0.8);
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
    }

    .btn-primary-modern {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        color: white;
        border: none;
        padding: 1rem 2.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(249, 115, 22, 0.4);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .stat-box {
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.3);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
    }

    .stat-box h3 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #3b82f6;
    }

    .stat-box p {
        color: #94a3b8;
        font-size: 0.9rem;
        margin: 0;
    }

    .menu-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.5rem;
        background: rgba(255, 255, 255, 0.02);
        border-radius: 12px;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
        text-decoration: none;
        color: #94a3b8;
        border: 1px solid transparent;
    }

    .menu-item:hover {
        background: rgba(249, 115, 22, 0.1);
        border-color: #f97316;
        color: white;
        text-decoration: none;
        transform: translateX(5px);
    }

    .menu-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .menu-text {
        flex: 1;
    }

    .menu-text h4 {
        color: white;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .menu-text p {
        font-size: 0.85rem;
        margin: 0;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 4px solid #f97316;
        object-fit: cover;
        margin-bottom: 1rem;
    }

    .alert-modern {
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #22c55e;
    }

    .alert-error {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .settings-title {
            font-size: 2rem;
        }

        .settings-card {
            padding: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="settings-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="settings-title">
                    <i class="fas fa-cog me-3"></i>Paramètres
                </h1>
                <p class="settings-subtitle">
                    Gérez votre profil et les paramètres de votre compte administrateur
                </p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-modern alert-success">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-modern alert-error">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <div class="row">
        <!-- Profil -->
        <div class="col-lg-8">
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2 class="settings-card-title">Informations du Profil</h2>
                </div>

                <div class="text-center mb-4">
                    @if(isset($admin->photo) && $admin->photo)
                        <img src="{{ asset('storage/' . $admin->photo) }}" alt="Photo de profil" class="profile-avatar">
                    @else
                        <div class="profile-avatar" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: white;">
                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <form action="{{ route('admin.parametres.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group-modern">
                        <label for="name" class="form-label-modern">
                            <i class="fas fa-user me-2"></i>Nom complet
                        </label>
                        <input type="text"
                               class="form-control-modern"
                               id="name"
                               name="name"
                               value="{{ old('name', $admin->name) }}"
                               required>
                    </div>

                    <div class="form-group-modern">
                        <label for="email" class="form-label-modern">
                            <i class="fas fa-envelope me-2"></i>Adresse email
                        </label>
                        <input type="email"
                               class="form-control-modern"
                               id="email"
                               name="email"
                               value="{{ old('email', $admin->email) }}"
                               required>
                    </div>

                    <div class="form-group-modern">
                        <label for="phone" class="form-label-modern">
                            <i class="fas fa-phone me-2"></i>Téléphone
                        </label>
                        <input type="tel"
                               class="form-control-modern"
                               id="phone"
                               name="phone"
                               value="{{ old('phone', $admin->phone ?? '') }}">
                    </div>

                    <div class="form-group-modern">
                        <label for="bio" class="form-label-modern">
                            <i class="fas fa-info-circle me-2"></i>Biographie
                        </label>
                        <textarea class="form-control-modern"
                                  id="bio"
                                  name="bio"
                                  rows="4">{{ old('bio', $admin->bio ?? '') }}</textarea>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn-primary-modern">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>

            <!-- Statistiques Système -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h2 class="settings-card-title">Statistiques Système</h2>
                </div>

                <div class="stats-grid">
                    <div class="stat-box">
                        <h3>{{ $systemStats['total_users'] }}</h3>
                        <p>Utilisateurs</p>
                    </div>
                    <div class="stat-box">
                        <h3>{{ $systemStats['total_students'] }}</h3>
                        <p>Étudiants</p>
                    </div>
                    <div class="stat-box">
                        <h3>{{ $systemStats['total_admins'] }}</h3>
                        <p>Administrateurs</p>
                    </div>
                    <div class="stat-box">
                        <h3>{{ $systemStats['database_size'] }}</h3>
                        <p>Base de données</p>
                    </div>
                    <div class="stat-box">
                        <h3>{{ $systemStats['storage_used'] }}</h3>
                        <p>Stockage utilisé</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu de paramètres -->
        <div class="col-lg-4">
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h2 class="settings-card-title">Paramètres Avancés</h2>
                </div>

                <a href="{{ route('admin.parametres.security') }}" class="menu-item">
                    <div class="menu-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="menu-text">
                        <h4>Sécurité</h4>
                        <p>Mot de passe et authentification</p>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </a>

                <a href="{{ route('admin.parametres.notifications') }}" class="menu-item">
                    <div class="menu-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="menu-text">
                        <h4>Notifications</h4>
                        <p>Gérer vos notifications</p>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </a>

                <a href="{{ route('admin.parametres.system') }}" class="menu-item">
                    <div class="menu-icon">
                        <i class="fas fa-server"></i>
                    </div>
                    <div class="menu-text">
                        <h4>Système</h4>
                        <p>Configuration système</p>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </a>

                <a href="{{ route('admin.parametres.backup') }}" class="menu-item">
                    <div class="menu-icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <div class="menu-text">
                        <h4>Sauvegardes</h4>
                        <p>Gestion des sauvegardes</p>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </a>

                <a href="{{ route('admin.parametres.logs') }}" class="menu-item">
                    <div class="menu-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="menu-text">
                        <h4>Logs</h4>
                        <p>Consulter les logs système</p>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>

            <!-- Info compte -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h2 class="settings-card-title">Informations du Compte</h2>
                </div>

                <div style="color: #94a3b8;">
                    <p style="margin-bottom: 1rem;">
                        <strong style="color: white;">Rôle :</strong>
                        @if($admin->role === 'super_admin')
                            <span style="color: #f97316;">Super Administrateur</span>
                        @elseif($admin->role === 'assistant')
                            <span style="color: #3b82f6;">Assistant</span>
                        @elseif($admin->role === 'comptable')
                            <span style="color: #22c55e;">Comptable</span>
                        @endif
                    </p>
                    <p style="margin-bottom: 1rem;">
                        <strong style="color: white;">Statut :</strong>
                        @if(isset($admin->is_active) && $admin->is_active)
                            <span style="color: #22c55e;"><i class="fas fa-check-circle me-1"></i>Actif</span>
                        @else
                            <span style="color: #ef4444;"><i class="fas fa-times-circle me-1"></i>Inactif</span>
                        @endif
                    </p>
                    <p style="margin-bottom: 1rem;">
                        <strong style="color: white;">Membre depuis :</strong>
                        {{ \Carbon\Carbon::parse($admin->created_at)->format('d/m/Y') }}
                    </p>
                    <p style="margin-bottom: 0;">
                        <strong style="color: white;">Dernière connexion :</strong>
                        {{ isset($admin->last_login_at) && $admin->last_login_at ? \Carbon\Carbon::parse($admin->last_login_at)->format('d/m/Y à H:i') : 'Jamais' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
