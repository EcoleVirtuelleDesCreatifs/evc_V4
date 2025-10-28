@extends('layouts.admin')

@section('title', 'Modifier un Administrateur - EVC')

@push('styles')
<style>
.page-header {
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
}

.form-card {
    background: rgba(255,255,255,0.05);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
}

.permission-preview {
    background: rgba(255,255,255,0.08);
    border-radius: 10px;
    padding: 1rem;
    margin-top: 1rem;
}

.permission-badge {
    background: rgba(255,255,255,0.15);
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.85rem;
    margin: 0.25rem;
    display: inline-block;
    border: 1px solid rgba(255,255,255,0.2);
}

.role-info-box {
    background: rgba(255,255,255,0.05);
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid rgba(255,255,255,0.1);
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="text-white mb-2">
                    <i class="fas fa-user-edit me-2"></i>
                    Modifier l'Administrateur
                </h1>
                <p class="text-white-50 mb-0">Mise à jour des informations et permissions</p>
            </div>
            <div>
                <a href="{{ route('admin.statistics.total-admins') }}" class="btn btn-secondary" style="background: linear-gradient(45deg, #6c757d, #5a6268); border: none; padding: 0.75rem 1.5rem; border-radius: 10px;">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>
    </div>



    <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-8">
                <!-- Informations de Base -->
                <div class="form-card text-white">
                    <h4 class="mb-4"><i class="fas fa-id-card me-2"></i>Informations de Base</h4>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name', $admin->name) }}"
                               required
                               placeholder="Ex: Jean Dupont">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email', $admin->email) }}"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Téléphone</label>
                        <input type="text"
                               class="form-control @error('phone') is-invalid @enderror"
                               id="phone"
                               name="phone"
                               value="{{ old('phone', $admin->phone ?? '') }}"
                               placeholder="Ex: +225 07 00 00 00 00">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="bio" class="form-label">Description / Bio</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror"
                                  id="bio"
                                  name="bio"
                                  rows="3"
                                  placeholder="Courte biographie de l'administrateur...">{{ old('bio', $admin->bio ?? '') }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-white-50">Maximum 500 caractères</small>
                    </div>
                </div>

                <!-- Rôle et Permissions -->
                <div class="form-card text-white">
                    <h4 class="mb-4"><i class="fas fa-user-shield me-2"></i>Rôle et Permissions</h4>

                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror"
                                id="role"
                                name="role"
                                required>
                            <option value="">Sélectionnez un rôle</option>
                            @foreach($roles as $roleKey => $roleData)
                                <option value="{{ $roleKey }}"
                                        {{ old('role', $admin->role) == $roleKey ? 'selected' : '' }}>
                                    {{ $roleData['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Aperçu des permissions -->
                    <div id="permissions-preview" class="permission-preview" style="display: none;">
                        <h6 class="mb-2"><i class="fas fa-key me-2"></i>Permissions associées :</h6>
                        <div id="permissions-list"></div>
                    </div>
                </div>

                <!-- Sécurité -->
                <div class="form-card text-white">
                    <h4 class="mb-4"><i class="fas fa-lock me-2"></i>Sécurité et Accès</h4>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note :</strong> Laissez les champs de mot de passe vides si vous ne souhaitez pas le modifier.
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password">
                            <small class="form-text text-white-50">Minimum 6 caractères</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                            <input type="password"
                                   class="form-control"
                                   id="password_confirmation"
                                   name="password_confirmation">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="is_active" class="form-label">Statut du compte <span class="text-danger">*</span></label>
                        <select class="form-select @error('is_active') is-invalid @enderror"
                                id="is_active"
                                name="is_active"
                                required>
                            <option value="1" {{ old('is_active', $admin->is_active) == 1 ? 'selected' : '' }}>
                                Actif
                            </option>
                            <option value="0" {{ old('is_active', $admin->is_active) == 0 ? 'selected' : '' }}>
                                Désactivé
                            </option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-white-50">Un compte désactivé ne pourra pas se connecter</small>
                    </div>

                    @if($admin->id == $currentAdminId)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Attention :</strong> Vous modifiez votre propre compte. Soyez prudent lors de la modification du rôle ou de la désactivation.
                    </div>
                    @endif
                </div>

                <!-- Boutons d'action -->
                <div class="text-end">
                    <button type="submit" class="btn btn-success btn-lg" style="background: linear-gradient(45deg, #28a745, #20c997); border: none; padding: 0.75rem 2rem; border-radius: 10px;">
                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                    </button>
                    <a href="{{ route('admin.statistics.total-admins') }}" class="btn btn-secondary btn-lg ms-2" style="padding: 0.75rem 2rem; border-radius: 10px;">
                        <i class="fas fa-times me-2"></i>Annuler
                    </a>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Informations du compte -->
                <div class="role-info-box text-white">
                    <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Informations du Compte</h5>
                    <p class="mb-2"><strong>ID :</strong> #{{ $admin->id }}</p>
                    <p class="mb-2"><strong>Créé le :</strong> {{ \Carbon\Carbon::parse($admin->created_at)->format('d/m/Y') }}</p>
                    @if($admin->last_login_at)
                    <p class="mb-2"><strong>Dernière connexion :</strong> {{ \Carbon\Carbon::parse($admin->last_login_at)->diffForHumans() }}</p>
                    @else
                    <p class="mb-2"><strong>Dernière connexion :</strong> <span class="text-muted">Jamais</span></p>
                    @endif
                </div>

                <!-- Guide des rôles -->
                <div class="role-info-box text-white">
                    <h5 class="mb-3"><i class="fas fa-users-cog me-2"></i>Guide des Rôles</h5>

                    @foreach($roles as $roleKey => $roleData)
                    <div class="mb-3 p-2" style="background: rgba({{ $roleKey == 'super_admin' ? '30, 60, 114' : ($roleKey == 'assistant' ? '79, 195, 247' : '156, 39, 176') }}, 0.2); border-left: 3px solid {{ $roleData['color'] }}; border-radius: 5px;">
                        <h6 class="mb-1">{{ $roleData['label'] }}</h6>
                        <small style="opacity: 0.8;">{{ $roleData['description'] }}</small>
                    </div>
                    @endforeach
                </div>

                <!-- Consignes de sécurité -->
                <div class="role-info-box text-white">
                    <h5 class="mb-3"><i class="fas fa-shield-alt me-2"></i>Consignes de Sécurité</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Vérifiez les permissions avant modification
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Utilisez un mot de passe fort (6+ caractères)
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Ne désactivez pas votre propre compte
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const permissionsPreview = document.getElementById('permissions-preview');
    const permissionsList = document.getElementById('permissions-list');

    const rolesData = @json($roles);

    function updatePermissionsPreview() {
        const selectedRole = roleSelect.value;

        if (selectedRole && rolesData[selectedRole]) {
            const roleData = rolesData[selectedRole];
            permissionsPreview.style.display = 'block';

            let html = '';
            roleData.access.forEach(permission => {
                html += `<span class="permission-badge text-white">
                    <i class="fas fa-check-circle me-1"></i>${permission}
                </span>`;
            });

            permissionsList.innerHTML = html;
        } else {
            permissionsPreview.style.display = 'none';
        }
    }

    // Afficher les permissions au chargement
    updatePermissionsPreview();

    // Mettre à jour lors du changement de rôle
    roleSelect.addEventListener('change', updatePermissionsPreview);
});
</script>
@endpush
@endsection
