@extends('layouts.admin')

@section('title', 'Notifications - Paramètres')

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

    .notification-item {
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

    .notification-item:hover {
        background: rgba(255, 255, 255, 0.04);
        border-color: #475569;
    }

    .notification-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
    }

    .notification-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .notification-text {
        flex: 1;
    }

    .notification-text h4 {
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .notification-text p {
        color: #94a3b8;
        font-size: 0.9rem;
        margin: 0;
    }

    .toggle-switch {
        position: relative;
        width: 60px;
        height: 30px;
        background: #334155;
        border-radius: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .toggle-switch.active {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    }

    .toggle-switch-handle {
        position: absolute;
        top: 3px;
        left: 3px;
        width: 24px;
        height: 24px;
        background: white;
        border-radius: 50%;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .toggle-switch.active .toggle-switch-handle {
        left: 33px;
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
                    <i class="fas fa-bell me-3"></i>Paramètres de Notifications
                </h1>
                <p class="settings-subtitle">
                    Gérez vos préférences de notifications et alertes
                </p>
            </div>
            <a href="{{ route('admin.parametres.index') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Retour
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Notifications par email -->
        <div class="col-lg-6">
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h2 class="settings-card-title">Notifications par Email</h2>
                </div>

                <div class="notification-item">
                    <div class="notification-content">
                        <div class="notification-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="notification-text">
                            <h4>Nouvelles inscriptions</h4>
                            <p>Recevez un email lors de nouvelles inscriptions d'étudiants</p>
                        </div>
                    </div>
                    <div class="toggle-switch {{ $notifications['new_registrations'] ? 'active' : '' }}"
                         data-preference="new_registrations"
                         onclick="toggleNotification(this)">
                        <div class="toggle-switch-handle"></div>
                    </div>
                </div>

                <div class="notification-item">
                    <div class="notification-content">
                        <div class="notification-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="notification-text">
                            <h4>Nouveaux paiements</h4>
                            <p>Être notifié lors de la réception de nouveaux paiements</p>
                        </div>
                    </div>
                    <div class="toggle-switch {{ $notifications['new_payments'] ? 'active' : '' }}"
                         data-preference="new_payments"
                         onclick="toggleNotification(this)">
                        <div class="toggle-switch-handle"></div>
                    </div>
                </div>

                <div class="notification-item">
                    <div class="notification-content">
                        <div class="notification-icon">
                            <i class="fas fa-file-upload"></i>
                        </div>
                        <div class="notification-text">
                            <h4>Documents soumis</h4>
                            <p>Notification quand un étudiant soumet un document</p>
                        </div>
                    </div>
                    <div class="toggle-switch {{ $notifications['documents_submitted'] ? 'active' : '' }}"
                         data-preference="documents_submitted"
                         onclick="toggleNotification(this)">
                        <div class="toggle-switch-handle"></div>
                    </div>
                </div>

                <div class="notification-item">
                    <div class="notification-content">
                        <div class="notification-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div class="notification-text">
                            <h4>Projets complétés</h4>
                            <p>Recevoir un email quand un projet est terminé</p>
                        </div>
                    </div>
                    <div class="toggle-switch {{ $notifications['projects_completed'] ? 'active' : '' }}"
                         data-preference="projects_completed"
                         onclick="toggleNotification(this)">
                        <div class="toggle-switch-handle"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications système -->
        <div class="col-lg-6">
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <h2 class="settings-card-title">Notifications Système</h2>
                </div>

                <div class="notification-item">
                    <div class="notification-content">
                        <div class="notification-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="notification-text">
                            <h4>Alertes système</h4>
                            <p>Notifications importantes sur l'état du système</p>
                        </div>
                    </div>
                    <div class="toggle-switch {{ $notifications['system_alerts'] ? 'active' : '' }}"
                         data-preference="system_alerts"
                         onclick="toggleNotification(this)">
                        <div class="toggle-switch-handle"></div>
                    </div>
                </div>

                <div class="notification-item">
                    <div class="notification-content">
                        <div class="notification-icon">
                            <i class="fas fa-database"></i>
                        </div>
                        <div class="notification-text">
                            <h4>Sauvegardes</h4>
                            <p>Notifications lors des sauvegardes automatiques</p>
                        </div>
                    </div>
                    <div class="toggle-switch {{ $notifications['backups'] ? 'active' : '' }}"
                         data-preference="backups"
                         onclick="toggleNotification(this)">
                        <div class="toggle-switch-handle"></div>
                    </div>
                </div>

                <div class="notification-item">
                    <div class="notification-content">
                        <div class="notification-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="notification-text">
                            <h4>Rapports hebdomadaires</h4>
                            <p>Recevoir un résumé hebdomadaire des statistiques</p>
                        </div>
                    </div>
                    <div class="toggle-switch {{ $notifications['weekly_reports'] ? 'active' : '' }}"
                         data-preference="weekly_reports"
                         onclick="toggleNotification(this)">
                        <div class="toggle-switch-handle"></div>
                    </div>
                </div>

                <div class="notification-item">
                    <div class="notification-content">
                        <div class="notification-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="notification-text">
                            <h4>Activités d'équipe</h4>
                            <p>Notifications des actions des autres administrateurs</p>
                        </div>
                    </div>
                    <div class="toggle-switch {{ $notifications['team_activities'] ? 'active' : '' }}"
                         data-preference="team_activities"
                         onclick="toggleNotification(this)">
                        <div class="toggle-switch-handle"></div>
                    </div>
                </div>
            </div>

            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h2 class="settings-card-title">À propos des Notifications</h2>
                </div>

                <div style="color: #94a3b8;">
                    <p style="margin-bottom: 1rem;">
                        <i class="fas fa-check text-success me-2"></i>
                        Les notifications par email sont envoyées à votre adresse enregistrée
                    </p>
                    <p style="margin-bottom: 1rem;">
                        <i class="fas fa-check text-success me-2"></i>
                        Vous pouvez activer/désactiver chaque type de notification
                    </p>
                    <p style="margin-bottom: 1rem;">
                        <i class="fas fa-check text-success me-2"></i>
                        Les notifications critiques sont toujours activées
                    </p>
                    <p style="margin-bottom: 0;">
                        <i class="fas fa-check text-success me-2"></i>
                        Vos préférences sont sauvegardées automatiquement
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let saveTimeout;

function toggleNotification(element) {
    element.classList.toggle('active');

    const isActive = element.classList.contains('active');
    const preference = element.getAttribute('data-preference');

    console.log('Toggle cliqué:', preference, 'Actif:', isActive);

    // Vérifier si le data-preference existe
    if (!preference) {
        console.error('data-preference manquant sur l\'élément:', element);
        showToast('✗ Erreur de configuration', 'error');
        return;
    }

    // Afficher indicateur de chargement
    const icon = element.parentElement.querySelector('.notification-icon i');
    if (!icon) {
        console.error('Icône non trouvée');
        return;
    }

    const originalClass = icon.className;
    icon.className = 'fas fa-spinner fa-spin';

    // Sauvegarder via AJAX
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => {
        saveNotificationPreference(preference, isActive, icon, originalClass);
    }, 300);
}

function saveNotificationPreference(preference, isActive, icon, originalClass) {
    const data = {};
    data[preference] = isActive ? 1 : 0;

    const url = '{{ route("admin.parametres.notifications.update") }}';
    const csrfToken = '{{ csrf_token() }}';

    console.log('=== DÉBUT SAUVEGARDE ===');
    console.log('URL:', url);
    console.log('Préférence:', preference);
    console.log('Valeur:', isActive ? 1 : 0);
    console.log('CSRF Token:', csrfToken ? 'Présent' : 'MANQUANT');
    console.log('Données envoyées:', JSON.stringify(data));

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('Statut réponse:', response.status);
        console.log('Headers réponse:', response.headers);

        // Cloner la réponse pour pouvoir lire le texte et le JSON
        return response.text().then(text => {
            console.log('Réponse brute:', text);

            if (!response.ok) {
                throw new Error(`Erreur HTTP ${response.status}: ${text.substring(0, 200)}`);
            }

            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Erreur parsing JSON:', e);
                throw new Error('Réponse invalide du serveur');
            }
        });
    })
    .then(data => {
        console.log('Données parsées:', data);
        icon.className = originalClass;

        if (data.success) {
            showToast('✓ Préférence enregistrée', 'success');
            console.log('✅ Sauvegarde réussie');
        } else {
            showToast('✗ ' + (data.message || 'Erreur'), 'error');
            console.error('❌ Échec:', data.message);
        }
        console.log('=== FIN SAUVEGARDE ===');
    })
    .catch(error => {
        icon.className = originalClass;
        console.error('❌ ERREUR CRITIQUE:', error);
        console.error('Type:', error.name);
        console.error('Message:', error.message);
        console.error('Stack:', error.stack);
        showToast('✗ ' + error.message, 'error');
        console.log('=== FIN SAUVEGARDE (ERREUR) ===');
    });
}

function showToast(message, type = 'success') {
    // Supprimer les anciens toasts
    const oldToasts = document.querySelectorAll('.toast-notification');
    oldToasts.forEach(toast => toast.remove());

    // Créer le conteneur du toast
    const toast = document.createElement('div');
    toast.className = 'toast-notification ' + type;

    // Ajouter le contenu
    toast.innerHTML = `
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="flex: 1;">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()"
                    style="background: none; border: none; color: white; font-size: 20px; cursor: pointer; opacity: 0.8; padding: 0; line-height: 1;">
                ×
            </button>
        </div>
        <div style="position: absolute; bottom: 0; left: 0; height: 3px; background: rgba(255,255,255,0.3); width: 100%; border-radius: 0 0 12px 12px;">
            <div class="toast-progress" style="height: 100%; background: rgba(255,255,255,0.6); width: 100%; animation: progressBar 5s linear;"></div>
        </div>
    `;

    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? 'linear-gradient(135deg, #22c55e 0%, #16a34a 100%)' : 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)'};
        color: white;
        padding: 1rem 1.5rem 1.3rem;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        z-index: 9999;
        font-weight: 600;
        animation: slideIn 0.3s ease;
        min-width: 300px;
        position: relative;
    `;

    document.body.appendChild(toast);

    // Supprimer après 5 secondes
    setTimeout(() => {
        if (document.body.contains(toast)) {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                if (document.body.contains(toast)) {
                    toast.remove();
                }
            }, 300);
        }
    }, 5000);
}

// Ajouter les animations CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    @keyframes progressBar {
        from {
            width: 100%;
        }
        to {
            width: 0%;
        }
    }
    .toast-notification button:hover {
        opacity: 1 !important;
        transform: scale(1.2);
    }
`;
document.head.appendChild(style);
</script>
@endpush
