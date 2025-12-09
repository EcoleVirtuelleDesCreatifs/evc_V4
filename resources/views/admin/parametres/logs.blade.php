@extends('layouts.admin')

@section('title', 'Logs Système - Paramètres')

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

    /* Table Styles for Logs */
    .logs-table-container {
        overflow-x: auto;
    }

    .table-dark {
        background-color: transparent;
        color: #e2e8f0;
        --bs-table-bg: transparent;
        --bs-table-border-color: #334155;
    }

    .table-dark th {
        background-color: rgba(30, 41, 59, 0.5);
        border-bottom-width: 2px;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.05em;
    }

    .table-dark td {
        vertical-align: middle;
        border-color: #334155;
    }

    .log-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .log-badge-info { background: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
    .log-badge-error { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
    .log-badge-warning { background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
    .log-badge-success { background: rgba(34, 197, 94, 0.2); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); }

    .code-snippet {
        background: rgba(0, 0, 0, 0.3);
        padding: 0.2rem 0.4rem;
        border-radius: 4px;
        font-family: monospace;
        font-size: 0.9em;
        color: #e2e8f0;
    }

    .btn-secondary-modern {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-secondary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        color: white;
        text-decoration: none;
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
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="settings-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="settings-title">
                    <i class="fas fa-file-alt me-3"></i>Logs Système
                </h1>
                <p class="settings-subtitle">
                    Journal des événements et erreurs du système
                </p>
            </div>
            <a href="{{ route('admin.parametres.system') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="settings-card">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary">
                    <div class="d-flex align-items-center gap-3">
                        <div class="settings-card-icon">
                            <i class="fas fa-list"></i>
                        </div>
                        <h2 class="settings-card-title">Journal d'activité</h2>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn-secondary-modern" onclick="refreshLogs()">
                            <i class="fas fa-sync-alt"></i> Actualiser
                        </button>
                        <button class="btn-secondary-modern" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);" onclick="clearLogs()">
                            <i class="fas fa-trash-alt"></i> Vider
                        </button>
                    </div>
                </div>

                <div class="logs-table-container">
                    <table class="table table-dark table-hover mb-0">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 180px;">Date & Heure</th>
                                <th scope="col" style="width: 100px;">Niveau</th>
                                <th scope="col">Message</th>
                                <th scope="col" style="width: 150px;">Contexte</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Exemple de logs statiques pour l'instant car le contrôleur ne fournit pas de données -->
                            <tr>
                                <td>{{ now()->format('d/m/Y H:i:s') }}</td>
                                <td><span class="log-badge log-badge-info">INFO</span></td>
                                <td>Consultation de la page des logs système</td>
                                <td><span class="code-snippet">System</span></td>
                            </tr>
                            <tr>
                                <td>{{ now()->subMinutes(5)->format('d/m/Y H:i:s') }}</td>
                                <td><span class="log-badge log-badge-success">SUCCESS</span></td>
                                <td>Sauvegarde de la base de données effectuée avec succès</td>
                                <td><span class="code-snippet">Backup</span></td>
                            </tr>
                            <tr>
                                <td>{{ now()->subMinutes(12)->format('d/m/Y H:i:s') }}</td>
                                <td><span class="log-badge log-badge-warning">WARNING</span></td>
                                <td>Tentative de connexion échouée (IP: 192.168.1.15)</td>
                                <td><span class="code-snippet">Auth</span></td>
                            </tr>
                            <tr>
                                <td>{{ now()->subHours(1)->format('d/m/Y H:i:s') }}</td>
                                <td><span class="log-badge log-badge-info">INFO</span></td>
                                <td>Mise à jour des paramètres système par l'administrateur</td>
                                <td><span class="code-snippet">Settings</span></td>
                            </tr>
                             <tr>
                                <td>{{ now()->subHours(2)->format('d/m/Y H:i:s') }}</td>
                                <td><span class="log-badge log-badge-error">ERROR</span></td>
                                <td>Erreur lors de l'envoi de l'email de bienvenue à user@example.com</td>
                                <td><span class="code-snippet">Mail</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-center text-muted">
                    <small>Affichage des 5 derniers événements système</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function refreshLogs() {
        window.location.reload();
    }

    function clearLogs() {
        if(confirm('Voulez-vous vraiment effacer tous les logs ? Cette action est irréversible.')) {
            alert('Logs effacés avec succès (Simulation).');
            // Ici on pourrait faire un appel AJAX pour vider les logs réels
            window.location.reload();
        }
    }
</script>
@endpush
