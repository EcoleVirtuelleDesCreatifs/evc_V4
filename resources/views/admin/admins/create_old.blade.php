@extends('layouts.admin')

@section('title', 'Créer un Administrateur')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-plus me-2"></i>Créer un Administrateur
            </h1>
            <p class="text-muted mb-0">Ajouter un nouveau compte administrateur</p>
        </div>
        <div>
            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Main Form -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations de l'Administrateur</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.admins.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group">
                            <label for="name">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required 
                                   placeholder="Ex: Jean Dupont">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    <small class="form-text text-muted">Minimum 8 caractères</small>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="role">Rôle <span class="text-danger">*</span></label>
                            <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="">Sélectionnez un rôle</option>
                                <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                <option value="assistant" {{ old('role') == 'assistant' ? 'selected' : '' }}>Assistant</option>
                                <option value="comptable" {{ old('role') == 'comptable' ? 'selected' : '' }}>Comptable</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="bio">Description / Bio</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                      id="bio" name="bio" rows="3" 
                                      placeholder="Courte biographie de l'administrateur...">{{ old('bio') }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note :</strong> Le compte sera automatiquement activé. L'administrateur pourra se connecter immédiatement avec les identifiants fournis.
                        </div>

                        <hr>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Créer l'Administrateur
                            </button>
                            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-times me-1"></i>Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Permissions Preview -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Permissions par Rôle</h6>
                </div>
                <div class="card-body">
                    <div id="role-permissions">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Sélectionnez un rôle pour voir les permissions associées
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Guidelines -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Consignes de Sécurité</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-shield-alt text-success me-2"></i>
                            Utilisez un mot de passe fort (8+ caractères)
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-user-shield text-info me-2"></i>
                            Attribuez le rôle minimum nécessaire
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            Vérifiez l'adresse email avant création
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-clock text-warning me-2"></i>
                            Révisez les accès régulièrement
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Recent Admins -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Derniers Admins Créés</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex align-items-center px-0">
                            <img src="https://via.placeholder.com/32x32" class="rounded-circle me-2" width="32" height="32">
                            <div class="flex-grow-1">
                                <div class="font-weight-bold">Marie Martin</div>
                                <small class="text-muted">Admin - Il y a 2 jours</small>
                            </div>
                        </div>
                        <div class="list-group-item d-flex align-items-center px-0">
                            <img src="https://via.placeholder.com/32x32" class="rounded-circle me-2" width="32" height="32">
                            <div class="flex-grow-1">
                                <div class="font-weight-bold">Pierre Durand</div>
                                <small class="text-muted">Modérateur - Il y a 5 jours</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Role permissions mapping
    const rolePermissions = {
        'super_admin': [
            'Accès complet à toutes les fonctionnalités',
            'Gestion des administrateurs',
            'Configuration système',
            'Sauvegarde et restauration',
            'Logs système',
            'Paramètres de sécurité'
        ],
        'admin': [
            'Gestion des étudiants',
            'Validation des documents',
            'Gestion des formations',
            'Génération de rapports',
            'Gestion des paiements',
            'Support technique'
        ],
        'moderator': [
            'Consultation des données',
            'Validation des contenus',
            'Support utilisateurs',
            'Modération des commentaires',
            'Rapports de base'
        ]
    };

    // Update permissions preview when role changes
    $('#role').change(function() {
        const selectedRole = $(this).val();
        const permissionsDiv = $('#role-permissions');
        
        if (selectedRole && rolePermissions[selectedRole]) {
            let html = '<div class="alert alert-success"><strong>Permissions pour ' + 
                      $(this).find('option:selected').text() + ':</strong></div>';
            html += '<ul class="list-unstyled mb-0">';
            
            rolePermissions[selectedRole].forEach(function(permission) {
                html += '<li class="mb-1"><i class="fas fa-check text-success me-2"></i>' + permission + '</li>';
            });
            
            html += '</ul>';
            permissionsDiv.html(html);
        } else {
            permissionsDiv.html('<div class="alert alert-info"><i class="fas fa-info-circle"></i> Sélectionnez un rôle pour voir les permissions associées</div>');
        }
    });

    // Password strength indicator
    $('#password').on('input', function() {
        const password = $(this).val();
        const strength = checkPasswordStrength(password);
        
        // Remove existing feedback
        $(this).removeClass('is-valid is-invalid');
        $(this).siblings('.password-strength').remove();
        
        if (password.length > 0) {
            let strengthClass = 'text-danger';
            let strengthText = 'Faible';
            
            if (strength >= 3) {
                strengthClass = 'text-success';
                strengthText = 'Fort';
                $(this).addClass('is-valid');
            } else if (strength >= 2) {
                strengthClass = 'text-warning';
                strengthText = 'Moyen';
            } else {
                $(this).addClass('is-invalid');
            }
            
            $(this).after('<small class="password-strength ' + strengthClass + '">Force: ' + strengthText + '</small>');
        }
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

    // Confirm password validation
    $('#password_confirmation').on('input', function() {
        const password = $('#password').val();
        const confirmation = $(this).val();
        
        $(this).removeClass('is-valid is-invalid');
        
        if (confirmation.length > 0) {
            if (password === confirmation) {
                $(this).addClass('is-valid');
            } else {
                $(this).addClass('is-invalid');
            }
        }
    });

    // Photo preview
    $('#photo').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Remove existing preview
                $('.photo-preview').remove();
                
                // Add new preview
                const preview = $('<div class="photo-preview mt-2"><img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 150px; max-height: 150px;"></div>');
                $('#photo').after(preview);
            };
            reader.readAsDataURL(file);
        }
    });

    // Form validation
    $('form').submit(function(e) {
        const password = $('#password').val();
        const confirmation = $('#password_confirmation').val();
        
        if (password !== confirmation) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas.');
            $('#password_confirmation').focus();
            return false;
        }
        
        if (password.length < 8) {
            e.preventDefault();
            alert('Le mot de passe doit contenir au moins 8 caractères.');
            $('#password').focus();
            return false;
        }
    });
});
</script>
@endsection
