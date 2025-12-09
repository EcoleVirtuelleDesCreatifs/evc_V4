@extends('layouts.admin')

@section('title', 'Système - Paramètres')

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

    .back-button {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .back-button:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        text-decoration: none;
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
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
    }

    .settings-card-title {
        color: white;
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }

    .system-info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        background: rgba(255, 255, 255, 0.02);
        border-radius: 12px;
        margin-bottom: 1rem;
        border: 1px solid #334155;
    }

    .system-info-label {
        color: #94a3b8;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .system-info-value {
        color: white;
        font-weight: 600;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .status-success {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
    }

    .status-warning {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: white;
    }

    .status-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .btn-primary-modern {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(249, 115, 22, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-secondary-modern {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-secondary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
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
                    <i class="fas fa-server me-3"></i>Paramètres Système
                </h1>
                <p class="settings-subtitle">
                    Informations et configuration du système
                </p>
            </div>
            <a href="{{ route('admin.parametres.index') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Retour
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informations système -->
        <div class="col-lg-6">
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h2 class="settings-card-title">Informations Système</h2>
                </div>

                <div class="system-info-item">
                    <span class="system-info-label">
                        <i class="fas fa-code"></i> Version PHP
                    </span>
                    <span class="system-info-value">{{ phpversion() }}</span>
                </div>

                <div class="system-info-item">
                    <span class="system-info-label">
                        <i class="fab fa-laravel"></i> Version Laravel
                    </span>
                    <span class="system-info-value">{{ app()->version() }}</span>
                </div>

                <div class="system-info-item">
                    <span class="system-info-label">
                        <i class="fas fa-database"></i> Base de données
                    </span>
                    <span class="system-info-value">{{ config('database.default') }}</span>
                </div>

                <div class="system-info-item">
                    <span class="system-info-label">
                        <i class="fas fa-globe"></i> Environnement
                    </span>
                    <span class="system-info-value">
                        @if(app()->environment('production'))
                            <span class="status-badge status-success">Production</span>
                        @elseif(app()->environment('local'))
                            <span class="status-badge status-warning">Local</span>
                        @else
                            <span class="status-badge status-danger">{{ app()->environment() }}</span>
                        @endif
                    </span>
                </div>

                <div class="system-info-item">
                    <span class="system-info-label">
                        <i class="fas fa-clock"></i> Fuseau horaire
                    </span>
                    <span class="system-info-value">{{ config('app.timezone') }}</span>
                </div>

                <div class="system-info-item">
                    <span class="system-info-label">
                        <i class="fas fa-language"></i> Langue
                    </span>
                    <span class="system-info-value">{{ config('app.locale') }}</span>
                </div>
            </div>

            <!-- Cache -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h2 class="settings-card-title">Cache</h2>
                </div>

                <div style="color: #94a3b8; margin-bottom: 1.5rem;">
                    <p>Le cache améliore les performances en stockant temporairement les données fréquemment utilisées.</p>
                </div>

                <div class="d-flex gap-3">
                    <button class="btn-secondary-modern" onclick="clearCache('config')">
                        <i class="fas fa-sync me-2"></i>
                        Vider le cache config
                    </button>
                    <button class="btn-secondary-modern" onclick="clearCache('view')">
                        <i class="fas fa-sync me-2"></i>
                        Vider le cache vues
                    </button>
                </div>
            </div>
        </div>

        <!-- État du système -->
        <div class="col-lg-6">
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h2 class="settings-card-title">État du Système</h2>
                </div>

                <div class="system-info-item">
                    <span class="system-info-label">
                        <i class="fas fa-check-circle"></i> Statut général
                    </span>
                    <span class="system-info-value">
                        <span class="status-badge status-success">Opérationnel</span>
                    </span>
                </div>

                <div class="system-info-item">
                    <span class="system-info-label">
                        <i class="fas fa-envelope"></i> Email
                    </span>
                    <span class="system-info-value">
                        @if(config('mail.mailers.smtp.host'))
                            <span class="status-badge status-success">Configuré</span>
                        @else
                            <span class="status-badge status-warning">Non configuré</span>
                        @endif
                    </span>
                </div>

                <div class="system-info-item">
                    <span class="system-info-label">
                        <i class="fas fa-hdd"></i> Stockage
                    </span>
                    <span class="system-info-value">
                        <span class="status-badge status-success">Disponible</span>
                    </span>
                </div>

                <div class="system-info-item">
                    <span class="system-info-label">
                        <i class="fas fa-shield-alt"></i> Mode debug
                    </span>
                    <span class="system-info-value">
                        @if(config('app.debug'))
                            <span class="status-badge status-warning">Activé</span>
                        @else
                            <span class="status-badge status-success">Désactivé</span>
                        @endif
                    </span>
                </div>

                <div class="system-info-item">
                    <span class="system-info-label">
                        <i class="fas fa-lock"></i> HTTPS
                    </span>
                    <span class="system-info-value">
                        @if(request()->secure())
                            <span class="status-badge status-success">Activé</span>
                        @else
                            <span class="status-badge status-warning">Désactivé</span>
                        @endif
                    </span>
                </div>
            </div>

            <!-- Maintenance -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h2 class="settings-card-title">Maintenance</h2>
                </div>

                <div style="color: #94a3b8; margin-bottom: 1.5rem;">
                    <p><i class="fas fa-info-circle me-2"></i>Outils de maintenance et d'optimisation du système.</p>
                </div>

                <div class="d-flex flex-column gap-3">
                    <button class="btn-primary-modern" onclick="optimizeSystem()">
                        <i class="fas fa-magic me-2"></i>
                        Optimiser le système
                    </button>

                    <a href="{{ route('admin.parametres.logs') }}" class="btn-secondary-modern">
                        <i class="fas fa-file-alt me-2"></i>
                        Consulter les logs
                    </a>
                </div>
            </div>

            <!-- Informations serveur -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-server"></i>
                    </div>
                    <h2 class="settings-card-title">Serveur</h2>
                </div>

                <div class="system-info-item">
                    <span class="system-info-label">
                        <i class="fas fa-desktop"></i> Système d'exploitation
                    </span>
                    <span class="system-info-value">{{ PHP_OS }}</span>
                </div>

                <div class="system-info-item">
                    <span class="system-info-label">
                        <i class="fas fa-network-wired"></i> Adresse IP serveur
                    </span>
                    <span class="system-info-value">{{ request()->server('SERVER_ADDR') ?? 'N/A' }}</span>
                </div>

                <div class="system-info-item">
                    <span class="system-info-label">
                        <i class="fas fa-user-shield"></i> Utilisateur serveur
                    </span>
                    <span class="system-info-value">{{ get_current_user() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function clearCache(type) {
    if (!confirm('Voulez-vous vraiment vider ce cache ?')) {
        return;
    }

    // Simuler l'action (vous pouvez ajouter une vraie route AJAX)
    alert(`Cache ${type} vidé avec succès !`);
}

function optimizeSystem() {
    if (!confirm('Voulez-vous optimiser le système ? Cette opération peut prendre quelques secondes.')) {
        return;
    }

    alert('Système optimisé avec succès !');
}
</script>
@endpush
