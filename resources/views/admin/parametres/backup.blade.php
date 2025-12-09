@extends('layouts.admin')

@section('title', 'Sauvegardes - Paramètres')

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

    .backup-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.02);
        border-radius: 12px;
        margin-bottom: 1rem;
        border: 1px solid #334155;
        transition: all 0.3s ease;
    }

    .backup-item:hover {
        background: rgba(255, 255, 255, 0.04);
        border-color: #475569;
    }

    .backup-info {
        flex: 1;
    }

    .backup-name {
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .backup-meta {
        color: #94a3b8;
        font-size: 0.9rem;
        display: flex;
        gap: 1.5rem;
    }

    .backup-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-download {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        color: white;
    }

    .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }

    .btn-primary-modern {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        color: white;
        border: none;
        padding: 1.2rem 2.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        cursor: pointer;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(249, 115, 22, 0.4);
    }

    .info-box {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-left: 4px solid #3b82f6;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }

    .info-box h4 {
        color: #1e40af;
        margin-bottom: 0.75rem;
        font-size: 1.1rem;
    }

    .info-box p {
        color: #475569;
        margin: 0;
        line-height: 1.6;
    }

    .warning-box {
        background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%);
        border-left: 4px solid #f97316;
        padding: 1.5rem;
        border-radius: 12px;
    }

    .warning-box h4 {
        color: #c2410c;
        margin-bottom: 0.75rem;
        font-size: 1.1rem;
    }

    .warning-box ul {
        color: #9a3412;
        margin: 0;
        padding-left: 1.5rem;
    }

    .warning-box li {
        margin-bottom: 0.5rem;
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

        .backup-meta {
            flex-direction: column;
            gap: 0.5rem;
        }

        .backup-actions {
            flex-direction: column;
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
                    <i class="fas fa-database me-3"></i>Sauvegardes
                </h1>
                <p class="settings-subtitle">
                    Gérez les sauvegardes de votre base de données
                </p>
            </div>
            <a href="{{ route('admin.parametres.index') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Retour
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Créer une sauvegarde -->
        <div class="col-lg-4">
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-plus"></i>
                    </div>
                    <h2 class="settings-card-title">Nouvelle Sauvegarde</h2>
                </div>

                <div class="info-box">
                    <h4><i class="fas fa-info-circle me-2"></i>À propos</h4>
                    <p>Créez une sauvegarde complète de votre base de données. Cette opération peut prendre quelques minutes.</p>
                </div>

                <button class="btn-primary-modern" onclick="createBackup()">
                    <i class="fas fa-save"></i>
                    Créer une sauvegarde maintenant
                </button>
            </div>

            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h2 class="settings-card-title">Recommandations</h2>
                </div>

                <div class="warning-box">
                    <h4><i class="fas fa-lightbulb me-2"></i>Bonnes pratiques</h4>
                    <ul>
                        <li>Effectuez des sauvegardes régulières (quotidiennes ou hebdomadaires)</li>
                        <li>Conservez plusieurs versions de sauvegarde</li>
                        <li>Stockez les sauvegardes dans un emplacement sécurisé</li>
                        <li>Testez périodiquement la restauration</li>
                        <li>Gardez au moins 3 sauvegardes récentes</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Liste des sauvegardes -->
        <div class="col-lg-8">
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <h2 class="settings-card-title">Sauvegardes Disponibles</h2>
                </div>

                <!-- Exemple de sauvegarde -->
                <div class="backup-item">
                    <div class="backup-info">
                        <div class="backup-name">
                            <i class="fas fa-database me-2" style="color: #3b82f6;"></i>
                            backup_{{ date('Y_m_d_His') }}.sql
                        </div>
                        <div class="backup-meta">
                            <span><i class="fas fa-calendar me-1"></i>{{ date('d/m/Y à H:i') }}</span>
                            <span><i class="fas fa-hdd me-1"></i>2.5 MB</span>
                            <span><i class="fas fa-table me-1"></i>Tables complètes</span>
                        </div>
                    </div>
                    <div class="backup-actions">
                        <button class="btn-action btn-download" onclick="downloadBackup('backup_example')">
                            <i class="fas fa-download"></i>
                            Télécharger
                        </button>
                        <button class="btn-action btn-delete" onclick="deleteBackup('backup_example')">
                            <i class="fas fa-trash"></i>
                            Supprimer
                        </button>
                    </div>
                </div>

                <!-- Autres exemples -->
                <div class="backup-item">
                    <div class="backup-info">
                        <div class="backup-name">
                            <i class="fas fa-database me-2" style="color: #3b82f6;"></i>
                            backup_{{ date('Y_m_d', strtotime('-1 day')) }}_120000.sql
                        </div>
                        <div class="backup-meta">
                            <span><i class="fas fa-calendar me-1"></i>{{ date('d/m/Y', strtotime('-1 day')) }} à 12:00</span>
                            <span><i class="fas fa-hdd me-1"></i>2.4 MB</span>
                            <span><i class="fas fa-table me-1"></i>Tables complètes</span>
                        </div>
                    </div>
                    <div class="backup-actions">
                        <button class="btn-action btn-download" onclick="downloadBackup('backup_yesterday')">
                            <i class="fas fa-download"></i>
                            Télécharger
                        </button>
                        <button class="btn-action btn-delete" onclick="deleteBackup('backup_yesterday')">
                            <i class="fas fa-trash"></i>
                            Supprimer
                        </button>
                    </div>
                </div>

                <div class="backup-item">
                    <div class="backup-info">
                        <div class="backup-name">
                            <i class="fas fa-database me-2" style="color: #3b82f6;"></i>
                            backup_{{ date('Y_m_d', strtotime('-7 days')) }}_080000.sql
                        </div>
                        <div class="backup-meta">
                            <span><i class="fas fa-calendar me-1"></i>{{ date('d/m/Y', strtotime('-7 days')) }} à 08:00</span>
                            <span><i class="fas fa-hdd me-1"></i>2.2 MB</span>
                            <span><i class="fas fa-table me-1"></i>Tables complètes</span>
                        </div>
                    </div>
                    <div class="backup-actions">
                        <button class="btn-action btn-download" onclick="downloadBackup('backup_week')">
                            <i class="fas fa-download"></i>
                            Télécharger
                        </button>
                        <button class="btn-action btn-delete" onclick="deleteBackup('backup_week')">
                            <i class="fas fa-trash"></i>
                            Supprimer
                        </button>
                    </div>
                </div>

                <div style="text-align: center; padding: 2rem; color: #64748b;">
                    <p><i class="fas fa-info-circle me-2"></i>Les sauvegardes sont stockées de manière sécurisée et peuvent être téléchargées à tout moment.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function createBackup() {
    if (!confirm('Voulez-vous créer une nouvelle sauvegarde de la base de données ?\n\nCette opération peut prendre quelques minutes.')) {
        return;
    }

    // Afficher un indicateur de chargement
    const originalButton = event.target;
    originalButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Création en cours...';
    originalButton.disabled = true;

    // Simuler la création (vous pouvez remplacer par un appel AJAX réel)
    setTimeout(() => {
        originalButton.innerHTML = '<i class="fas fa-save me-2"></i>Créer une sauvegarde maintenant';
        originalButton.disabled = false;
        alert('✅ Sauvegarde créée avec succès !');
        location.reload();
    }, 3000);
}

function downloadBackup(backupName) {
    alert(`Téléchargement de ${backupName}.sql...`);
    // Implémenter la logique de téléchargement réelle ici
}

function deleteBackup(backupName) {
    if (!confirm(`Voulez-vous vraiment supprimer cette sauvegarde ?\n\n${backupName}.sql\n\nCette action est irréversible.`)) {
        return;
    }

    alert(`✅ Sauvegarde ${backupName}.sql supprimée`);
    // Implémenter la logique de suppression réelle ici
    event.target.closest('.backup-item').remove();
}
</script>
@endpush
