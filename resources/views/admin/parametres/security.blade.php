@extends('layouts.admin')

@section('title', 'Sécurité - Paramètres')

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

    .security-feature {
        display: flex;
        align-items: start;
        gap: 1rem;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.02);
        border-radius: 12px;
        margin-bottom: 1rem;
        border: 1px solid #334155;
    }

    .security-feature-icon {
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

    .security-feature-content {
        flex: 1;
    }

    .security-feature-content h4 {
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .security-feature-content p {
        color: #94a3b8;
        font-size: 0.9rem;
        margin: 0;
    }

    .password-strength {
        margin-top: 0.5rem;
    }

    .password-strength-bar {
        height: 8px;
        background: #334155;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }

    .password-strength-fill {
        height: 100%;
        transition: all 0.3s ease;
        border-radius: 4px;
    }

    .password-strength-text {
        font-size: 0.85rem;
        font-weight: 600;
    }

    .alert-modern {
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
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

    .alert-warning {
        background: rgba(251, 191, 36, 0.1);
        border: 1px solid rgba(251, 191, 36, 0.3);
        color: #fbbf24;
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
                    <i class="fas fa-shield-alt me-3"></i>Paramètres de Sécurité
                </h1>
                <p class="settings-subtitle">
                    Gérez la sécurité de votre compte et l'authentification
                </p>
            </div>
            <a href="{{ route('admin.parametres.index') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Retour
            </a>
        </div>
    </div>


    <div class="row">
        <!-- Formulaire de changement de mot de passe -->
        <div class="col-lg-8">
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <h2 class="settings-card-title">Changer le Mot de Passe</h2>
                </div>

                <form action="{{ route('admin.parametres.update-password') }}" method="POST" id="passwordForm">
                    @csrf

                    <div class="form-group-modern">
                        <label for="current_password" class="form-label-modern">
                            <i class="fas fa-lock me-2"></i>Mot de passe actuel
                        </label>
                        <input type="password"
                               class="form-control-modern"
                               id="current_password"
                               name="current_password"
                               required
                               placeholder="Entrez votre mot de passe actuel">
                    </div>

                    <div class="form-group-modern">
                        <label for="new_password" class="form-label-modern">
                            <i class="fas fa-lock-open me-2"></i>Nouveau mot de passe
                        </label>
                        <input type="password"
                               class="form-control-modern"
                               id="new_password"
                               name="new_password"
                               required
                               placeholder="Minimum 8 caractères">
                        <div class="password-strength" id="passwordStrength" style="display: none;">
                            <div class="password-strength-bar">
                                <div class="password-strength-fill" id="strengthBar"></div>
                            </div>
                            <span class="password-strength-text" id="strengthText"></span>
                        </div>
                    </div>

                    <div class="form-group-modern">
                        <label for="new_password_confirmation" class="form-label-modern">
                            <i class="fas fa-check-circle me-2"></i>Confirmer le nouveau mot de passe
                        </label>
                        <input type="password"
                               class="form-control-modern"
                               id="new_password_confirmation"
                               name="new_password_confirmation"
                               required
                               placeholder="Retapez le nouveau mot de passe">
                    </div>

                    <div class="alert-modern alert-warning">
                        <i class="fas fa-info-circle"></i>
                        <span>Assurez-vous d'utiliser un mot de passe fort avec au moins 8 caractères, incluant des majuscules, minuscules, chiffres et symboles.</span>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn-primary-modern">
                            <i class="fas fa-save me-2"></i>Mettre à jour le mot de passe
                        </button>
                    </div>
                </form>
            </div>

            <!-- Sessions actives -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h2 class="settings-card-title">Sessions Actives</h2>
                </div>

                <div class="security-feature">
                    <div class="security-feature-icon">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <div class="security-feature-content">
                        <h4>Session actuelle</h4>
                        <p><strong>Navigateur:</strong> {{ $_SERVER['HTTP_USER_AGENT'] ?? 'Inconnu' }}</p>
                        <p><strong>IP:</strong> {{ request()->ip() }}</p>
                        <p><strong>Dernière activité:</strong> Maintenant</p>
                    </div>
                    <span style="color: #22c55e; font-weight: 600;">
                        <i class="fas fa-circle" style="font-size: 0.5rem;"></i> Actif
                    </span>
                </div>
            </div>
        </div>

        <!-- Fonctionnalités de sécurité -->
        <div class="col-lg-4">
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h2 class="settings-card-title">Sécurité du Compte</h2>
                </div>

                <div class="security-feature">
                    <div class="security-feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="security-feature-content">
                        <h4>Authentification forte</h4>
                        <p>Votre compte est protégé par un mot de passe sécurisé</p>
                    </div>
                    <i class="fas fa-check-circle" style="color: #22c55e; font-size: 1.5rem;"></i>
                </div>

                <div class="security-feature">
                    <div class="security-feature-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="security-feature-content">
                        <h4>Email vérifié</h4>
                        <p>Votre adresse email est confirmée et sécurisée</p>
                    </div>
                    <i class="fas fa-check-circle" style="color: #22c55e; font-size: 1.5rem;"></i>
                </div>

                <div class="security-feature">
                    <div class="security-feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="security-feature-content">
                        <h4>Session sécurisée</h4>
                        <p>Votre session expire automatiquement après inactivité</p>
                    </div>
                    <i class="fas fa-check-circle" style="color: #22c55e; font-size: 1.5rem;"></i>
                </div>
            </div>

            <!-- Conseils de sécurité -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h2 class="settings-card-title">Conseils de Sécurité</h2>
                </div>

                <div style="color: #94a3b8;">
                    <p style="margin-bottom: 1rem;">
                        <i class="fas fa-check text-success me-2"></i>
                        Utilisez un mot de passe unique et complexe
                    </p>
                    <p style="margin-bottom: 1rem;">
                        <i class="fas fa-check text-success me-2"></i>
                        Changez votre mot de passe régulièrement
                    </p>
                    <p style="margin-bottom: 1rem;">
                        <i class="fas fa-check text-success me-2"></i>
                        Ne partagez jamais vos identifiants
                    </p>
                    <p style="margin-bottom: 1rem;">
                        <i class="fas fa-check text-success me-2"></i>
                        Déconnectez-vous sur les appareils partagés
                    </p>
                    <p style="margin-bottom: 0;">
                        <i class="fas fa-check text-success me-2"></i>
                        Vérifiez l'URL avant de vous connecter
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const newPassword = document.getElementById('new_password');
    const strengthContainer = document.getElementById('passwordStrength');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    const confirmPassword = document.getElementById('new_password_confirmation');

    // Vérification de la force du mot de passe
    newPassword.addEventListener('input', function() {
        const password = this.value;

        if (password.length === 0) {
            strengthContainer.style.display = 'none';
            return;
        }

        strengthContainer.style.display = 'block';

        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;

        let width, color, text;

        if (strength <= 2) {
            width = '33%';
            color = '#ef4444';
            text = 'Faible';
        } else if (strength <= 3) {
            width = '66%';
            color = '#fbbf24';
            text = 'Moyen';
        } else {
            width = '100%';
            color = '#22c55e';
            text = 'Fort';
        }

        strengthBar.style.width = width;
        strengthBar.style.background = `linear-gradient(135deg, ${color} 0%, ${color} 100%)`;
        strengthText.textContent = `Force du mot de passe: ${text}`;
        strengthText.style.color = color;
    });

    // Validation du formulaire
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        const newPass = newPassword.value;
        const confirmPass = confirmPassword.value;

        if (newPass !== confirmPass) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas');
            confirmPassword.focus();
            return false;
        }

        if (newPass.length < 8) {
            e.preventDefault();
            alert('Le mot de passe doit contenir au moins 8 caractères');
            newPassword.focus();
            return false;
        }
    });
});
</script>
@endpush
