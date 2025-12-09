@extends('layouts.admin')

@section('title', 'Créer un Administrateur')

@push('styles')
<style>
    body {
        background: #0f172a;
    }

    .admin-create-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        animation: fadeIn 0.5s ease;
    }

    .admin-create-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .admin-create-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .form-modern {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        animation: fadeIn 0.6s ease;
    }

    .form-section-title {
        color: white;
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #334155;
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

    .form-label-modern .required {
        color: #ef4444;
        margin-left: 0.25rem;
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
        border-color: #667eea;
        background: rgba(30, 41, 59, 0.8);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-control-modern::placeholder {
        color: #64748b;
    }

    .form-select-modern {
        background: rgba(30, 41, 59, 0.5);
        border: 1px solid #334155;
        border-radius: 12px;
        padding: 0.875rem 1.25rem;
        color: white;
        font-size: 1rem;
        transition: all 0.3s ease;
        width: 100%;
        cursor: pointer;
    }

    .form-select-modern:focus {
        outline: none;
        border-color: #667eea;
        background: rgba(30, 41, 59, 0.8);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-select-modern option {
        background: #1e293b;
        color: white;
    }

    .password-strength {
        margin-top: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }

    .password-strength.weak {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .password-strength.medium {
        background: rgba(251, 191, 36, 0.1);
        color: #fbbf24;
        border: 1px solid rgba(251, 191, 36, 0.3);
    }

    .password-strength.strong {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .btn-primary-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary-modern {
        background: rgba(100, 116, 139, 0.2);
        color: white;
        border: 1px solid #334155;
        padding: 1rem 2.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    .btn-secondary-modern:hover {
        background: rgba(100, 116, 139, 0.3);
        border-color: #475569;
        color: white;
        text-decoration: none;
    }

    .sidebar-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        animation: fadeIn 0.7s ease;
    }

    .sidebar-card-title {
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .role-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .role-badge.super-admin {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .role-badge.assistant {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .role-badge.comptable {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .permission-item {
        color: #94a3b8;
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(51, 65, 85, 0.5);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .permission-item:last-child {
        border-bottom: none;
    }

    .permission-item i {
        color: #22c55e;
    }

    .security-item {
        display: flex;
        align-items: start;
        gap: 1rem;
        padding: 0.75rem;
        background: rgba(255, 255, 255, 0.02);
        border-radius: 8px;
        margin-bottom: 0.75rem;
    }

    .security-item:last-child {
        margin-bottom: 0;
    }

    .security-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .security-icon.success {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .security-icon.info {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .security-icon.warning {
        background: rgba(251, 191, 36, 0.1);
        color: #fbbf24;
    }

    .security-text {
        flex: 1;
    }

    .security-text strong {
        color: white;
        font-size: 0.95rem;
        display: block;
        margin-bottom: 0.25rem;
    }

    .security-text small {
        color: #94a3b8;
        font-size: 0.85rem;
    }

    .alert-modern {
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.3);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        color: #60a5fa;
        margin-bottom: 1.5rem;
    }

    .alert-modern i {
        margin-right: 0.5rem;
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
        .admin-create-title {
            font-size: 2rem;
        }

        .form-modern {
            padding: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="admin-create-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="admin-create-title">
                    <i class="fas fa-user-plus me-3"></i>Créer un Administrateur
                </h1>
                <p class="admin-create-subtitle">
                    Ajoutez un nouveau compte administrateur à la plateforme
                </p>
            </div>
            <a href="{{ route('admin.admins.index') }}" class="btn-secondary-modern">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Formulaire Principal -->
        <div class="col-lg-8">
            <div class="form-modern">
                <h3 class="form-section-title">
                    <i class="fas fa-user-circle me-2"></i>Informations de l'Administrateur
                </h3>

                <form action="{{ route('admin.admins.store') }}" method="POST" id="adminForm">
                    @csrf

                    <!-- Nom complet -->
                    <div class="form-group-modern">
                        <label for="name" class="form-label-modern">
                            Nom complet<span class="required">*</span>
                        </label>
                        <input type="text"
                               class="form-control-modern @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Ex: Jean Dupont"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group-modern">
                        <label for="email" class="form-label-modern">
                            Adresse email<span class="required">*</span>
                        </label>
                        <input type="email"
                               class="form-control-modern @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="admin@example.com"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Mots de passe -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="password" class="form-label-modern">
                                    Mot de passe<span class="required">*</span>
                                </label>
                                <input type="password"
                                       class="form-control-modern @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       placeholder="Minimum 8 caractères"
                                       required>
                                <div id="password-strength-indicator"></div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="password_confirmation" class="form-label-modern">
                                    Confirmer le mot de passe<span class="required">*</span>
                                </label>
                                <input type="password"
                                       class="form-control-modern"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       placeholder="Retapez le mot de passe"
                                       required>
                                <div id="password-match-indicator"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Rôle -->
                    <div class="form-group-modern">
                        <label for="role" class="form-label-modern">
                            Rôle de l'administrateur<span class="required">*</span>
                        </label>
                        <select class="form-select-modern @error('role') is-invalid @enderror"
                                id="role"
                                name="role"
                                required>
                            <option value="">Sélectionnez un rôle</option>
                            <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin - Accès complet</option>
                            <option value="assistant" {{ old('role') == 'assistant' ? 'selected' : '' }}>Assistant - Gestion quotidienne</option>
                            <option value="comptable" {{ old('role') == 'comptable' ? 'selected' : '' }}>Comptable - Gestion financière</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bio / Description -->
                    <div class="form-group-modern">
                        <label for="bio" class="form-label-modern">
                            Description / Bio (optionnel)
                        </label>
                        <textarea class="form-control-modern @error('bio') is-invalid @enderror"
                                  id="bio"
                                  name="bio"
                                  rows="4"
                                  placeholder="Courte biographie de l'administrateur...">{{ old('bio') }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Info Alert -->
                    <div class="alert-modern">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note importante :</strong> Le compte sera automatiquement activé. L'administrateur pourra se connecter immédiatement avec les identifiants fournis.
                    </div>

                    <!-- Boutons -->
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn-primary-modern">
                            <i class="fas fa-save me-2"></i>Créer l'Administrateur
                        </button>
                        <a href="{{ route('admin.admins.index') }}" class="btn-secondary-modern">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Permissions par rôle -->
            <div class="sidebar-card">
                <h3 class="sidebar-card-title">
                    <i class="fas fa-shield-alt"></i>
                    Permissions du Rôle
                </h3>
                <div id="role-permissions">
                    <p style="color: #94a3b8; text-align: center; padding: 2rem;">
                        Sélectionnez un rôle pour voir les permissions
                    </p>
                </div>
            </div>

            <!-- Consignes de sécurité -->
            <div class="sidebar-card">
                <h3 class="sidebar-card-title">
                    <i class="fas fa-lock"></i>
                    Bonnes Pratiques de Sécurité
                </h3>

                <div class="security-item">
                    <div class="security-icon success">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="security-text">
                        <strong>Mot de passe fort</strong>
                        <small>Minimum 8 caractères avec majuscules, chiffres et symboles</small>
                    </div>
                </div>

                <div class="security-item">
                    <div class="security-icon info">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="security-text">
                        <strong>Principe du moindre privilège</strong>
                        <small>Attribuez uniquement les permissions nécessaires</small>
                    </div>
                </div>

                <div class="security-item">
                    <div class="security-icon warning">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <div class="security-text">
                        <strong>Vérification de l'email</strong>
                        <small>Confirmez l'adresse email avant la création</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Permissions par rôle
    const rolePermissions = {
        'super_admin': [
            'Accès complet à toutes les fonctionnalités',
            'Gestion des administrateurs et utilisateurs',
            'Configuration système et paramètres',
            'Sauvegarde et restauration des données',
            'Consultation des logs système',
            'Gestion de la sécurité'
        ],
        'assistant': [
            'Gestion des étudiants et inscriptions',
            'Validation des documents et projets',
            'Gestion des formations et modules',
            'Génération de rapports basiques',
            'Support technique utilisateurs'
        ],
        'comptable': [
            'Gestion complète des paiements',
            'Génération de factures',
            'Rapports financiers détaillés',
            'Suivi des transactions',
            'Gestion de la trésorerie'
        ]
    };

    // Mettre à jour les permissions quand le rôle change
    document.getElementById('role').addEventListener('change', function() {
        const selectedRole = this.value;
        const permissionsDiv = document.getElementById('role-permissions');

        if (selectedRole && rolePermissions[selectedRole]) {
            const roleName = this.options[this.selectedIndex].text;
            const roleClass = selectedRole.replace('_', '-');

            let html = `<div class="role-badge ${roleClass}">${roleName}</div>`;
            html += '<div style="margin-top: 1rem;">';

            rolePermissions[selectedRole].forEach(function(permission) {
                html += `
                    <div class="permission-item">
                        <i class="fas fa-check-circle"></i>
                        <span>${permission}</span>
                    </div>
                `;
            });

            html += '</div>';
            permissionsDiv.innerHTML = html;
        } else {
            permissionsDiv.innerHTML = '<p style="color: #94a3b8; text-align: center; padding: 2rem;">Sélectionnez un rôle pour voir les permissions</p>';
        }
    });

    // Vérification de la force du mot de passe
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const indicator = document.getElementById('password-strength-indicator');

        if (password.length === 0) {
            indicator.innerHTML = '';
            return;
        }

        const strength = checkPasswordStrength(password);
        let strengthClass = 'weak';
        let strengthText = 'Faible';

        if (strength >= 4) {
            strengthClass = 'strong';
            strengthText = 'Fort';
        } else if (strength >= 2) {
            strengthClass = 'medium';
            strengthText = 'Moyen';
        }

        indicator.innerHTML = `<div class="password-strength ${strengthClass}"><i class="fas fa-shield-alt me-2"></i>Force: ${strengthText}</div>`;
    });

    function checkPasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        return strength;
    }

    // Vérification de correspondance des mots de passe
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmation = this.value;
        const indicator = document.getElementById('password-match-indicator');

        if (confirmation.length === 0) {
            indicator.innerHTML = '';
            return;
        }

        if (password === confirmation) {
            indicator.innerHTML = '<div class="password-strength strong"><i class="fas fa-check-circle me-2"></i>Les mots de passe correspondent</div>';
        } else {
            indicator.innerHTML = '<div class="password-strength weak"><i class="fas fa-times-circle me-2"></i>Les mots de passe ne correspondent pas</div>';
        }
    });

    // Validation du formulaire
    document.getElementById('adminForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmation = document.getElementById('password_confirmation').value;

        if (password !== confirmation) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas.');
            document.getElementById('password_confirmation').focus();
            return false;
        }

        if (password.length < 8) {
            e.preventDefault();
            alert('Le mot de passe doit contenir au moins 8 caractères.');
            document.getElementById('password').focus();
            return false;
        }
    });
});
</script>
@endpush
