@extends('layouts.admin')

@section('title', 'Modifier Étudiant - ' . ($student['prenom'] ?? 'Étudiant') . ' ' . ($student['nom'] ?? ''))

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin/formation-create.css') }}">
<style>
body {
    background: var(--form-bg);
    color: var(--form-text);
}
.breadcrumb {
    background: transparent;
}
.breadcrumb-item a {
    color: var(--form-primary);
    text-decoration: none;
}
.breadcrumb-item.active {
    color: var(--form-text-muted);
}
.sidebar-sticky {
    position: sticky;
    top: 20px;
}
.stat-item {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--form-border);
}
.stat-item:last-child {
    border-bottom: none;
}
.stat-label {
    color: var(--form-text-muted);
    font-size: 0.875rem;
}
.stat-value {
    color: var(--form-text);
    font-weight: 600;
}
.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}
.status-badge.active {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}
.status-badge.inactive {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}
.btn-secondary {
    background: rgba(74, 85, 104, 0.3);
    border: 1px solid var(--form-border);
    color: var(--form-text);
}
.btn-secondary:hover {
    background: rgba(74, 85, 104, 0.5);
    border-color: var(--form-text-muted);
    color: var(--form-text);
}
.btn-outline-primary {
    background: transparent;
    border: 1px solid var(--form-primary);
    color: var(--form-primary);
}
.btn-outline-primary:hover {
    background: rgba(56, 189, 248, 0.1);
    border-color: var(--form-primary);
    color: var(--form-primary);
}
.btn-success {
    background: #10b981;
    border: none;
    color: white;
}
.btn-success:hover {
    background: #059669;
    color: white;
    transform: translateY(-1px);
}
.alert-danger {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #fca5a5;
}
.alert-success {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #6ee7b7;
}
.text-danger {
    color: #ef4444 !important;
}
.form-text {
    color: var(--form-text-muted);
    font-size: 0.875rem;
}

/* Styles optimisés pour la section Durée de Formation */
.expiration-status-card {
    padding: 1.5rem;
    border-radius: 12px;
    border: 2px solid;
    transition: all 0.3s ease;
}
.expiration-status-card.alert-success {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.05) 100%);
    border-color: rgba(16, 185, 129, 0.4);
}
.expiration-status-card.alert-warning {
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.1) 0%, rgba(245, 158, 11, 0.05) 100%);
    border-color: rgba(251, 191, 36, 0.4);
}
.expiration-status-card.alert-danger {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.05) 100%);
    border-color: rgba(239, 68, 68, 0.4);
}
.expiration-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 1.5rem;
}
.alert-success .expiration-icon {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}
.alert-warning .expiration-icon {
    background: rgba(251, 191, 36, 0.2);
    color: #f59e0b;
}
.alert-danger .expiration-icon {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}
.alert-success h4 { color: #10b981; }
.alert-warning h4 { color: #f59e0b; }
.alert-danger h4 { color: #ef4444; }

.btn-duration {
    padding: 0.75rem 1.25rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    border: 2px solid;
}
.btn-duration-success {
    background: rgba(16, 185, 129, 0.1);
    border-color: rgba(16, 185, 129, 0.4);
    color: #10b981;
}
.btn-duration-success:hover {
    background: rgba(16, 185, 129, 0.2);
    border-color: #10b981;
    color: #059669;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}
.btn-duration-primary {
    background: rgba(56, 189, 248, 0.1);
    border-color: rgba(56, 189, 248, 0.4);
    color: #38bdf8;
}
.btn-duration-primary:hover {
    background: rgba(56, 189, 248, 0.2);
    border-color: #38bdf8;
    color: #0ea5e9;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
}
.btn-duration-info {
    background: rgba(99, 102, 241, 0.1);
    border-color: rgba(99, 102, 241, 0.4);
    color: #6366f1;
}
.btn-duration-info:hover {
    background: rgba(99, 102, 241, 0.2);
    border-color: #6366f1;
    color: #4f46e5;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}
.form-control:focus {
    border-color: var(--form-primary);
    box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.1);
}
.border-success {
    animation: successPulse 0.5s ease-in-out;
}
@keyframes successPulse {
    0%, 100% { border-color: var(--form-border); }
    50% {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }
}
</style>
@endsection

@section('content')
<div class="container-fluid px-4 interactive-dashboard-form">
    <!-- Header -->
    <div class="form-header">
        <div>
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.statistics.detail', 'total-students') }}">Étudiants</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.students.profile', $student['id']) }}">Profil</a></li>
                    <li class="breadcrumb-item active">Modifier</li>
                </ol>
            </nav>
            <h1 class="h2 mb-0" style="color: var(--form-text);">
                <i class="fas fa-user-edit me-2" style="color: var(--form-primary);"></i>Modifier Étudiant
            </h1>
            <p class="mt-2 mb-0" style="color: var(--form-text-muted);">
                <i class="fas fa-info-circle me-1"></i>Modifiez les informations de l'étudiant
            </p>
        </div>
        <a href="{{ route('admin.students.profile', $student['id']) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour au profil
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="form-card mb-4">
                <div class="form-card-header">
                    <i class="fas fa-user"></i>
                    <h3>Informations de l'Étudiant</h3>
                </div>
                <div class="form-card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <h6 class="mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Erreurs de validation</h6>
                            <ul class="mb-0" style="padding-left: 1.5rem;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success mb-4">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.students.update', $student['id']) }}" method="POST" id="editStudentForm">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- Informations personnelles -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prenom" class="form-label">
                                        <i class="fas fa-user text-muted me-1"></i>Prénom <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror"
                                           id="prenom" name="prenom" value="{{ old('prenom', $student['prenom'] ?? '') }}" required>
                                    @error('prenom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nom" class="form-label">
                                        <i class="fas fa-user text-muted me-1"></i>Nom <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                           id="nom" name="nom" value="{{ old('nom', $student['nom'] ?? '') }}" required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope text-muted me-1"></i>Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email', $student['email'] ?? '') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label">
                                        <i class="fas fa-phone text-muted me-1"></i>Téléphone
                                    </label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone', $student['phone'] ?? '') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="registration_date" class="form-label">
                                        <i class="fas fa-calendar-plus text-muted me-1"></i>Date d'inscription
                                    </label>
                                    <input type="date" class="form-control @error('registration_date') is-invalid @enderror"
                                           id="registration_date" name="registration_date"
                                           value="{{ old('registration_date', isset($student['registration_date']) ? \Carbon\Carbon::parse($student['registration_date'])->format('Y-m-d') : '') }}">
                                    @error('registration_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="formation_souhaitee" class="form-label">
                                        <i class="fas fa-graduation-cap text-muted me-1"></i>Formation <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('formation_souhaitee') is-invalid @enderror"
                                            id="formation_souhaitee" name="formation_souhaitee" required>
                                        <option value="">Sélectionner une formation</option>
                                        <option value="design_graphique" {{ old('formation_souhaitee', $student['formation_souhaitee'] ?? '') == 'design_graphique' ? 'selected' : '' }}>
                                            Design Graphique
                                        </option>
                                        <option value="design_graphique_community_management" {{ old('formation_souhaitee', $student['formation_souhaitee'] ?? '') == 'design_graphique_community_management' ? 'selected' : '' }}>
                                            Design Graphique & Community Management
                                        </option>
                                        <option value="community_management" {{ old('formation_souhaitee', $student['formation_souhaitee'] ?? '') == 'community_management' ? 'selected' : '' }}>
                                            Community Management
                                        </option>
                                        <option value="intelligence_artificielle" {{ old('formation_souhaitee', $student['formation_souhaitee'] ?? '') == 'intelligence_artificielle' ? 'selected' : '' }}>
                                            Intelligence Artificielle
                                        </option>
                                        <option value="gestion_informatique" {{ old('formation_souhaitee', $student['formation_souhaitee'] ?? '') == 'gestion_informatique' ? 'selected' : '' }}>
                                            Gestion Informatique
                                        </option>
                                    </select>
                                    @error('formation_souhaitee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ville" class="form-label">
                                        <i class="fas fa-map-marker-alt text-muted me-1"></i>Ville
                                    </label>
                                    <input type="text" class="form-control @error('ville') is-invalid @enderror"
                                           id="ville" name="ville" value="{{ old('ville', $student['ville'] ?? '') }}">
                                    @error('ville')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pays" class="form-label">
                                        <i class="fas fa-globe text-muted me-1"></i>Pays
                                    </label>
                                    <select class="form-select @error('pays') is-invalid @enderror" id="pays" name="pays">
                                        <option value="">Sélectionner un pays</option>
                                        <option value="Côte d'Ivoire" {{ old('pays', $student['pays'] ?? '') == "Côte d'Ivoire" ? 'selected' : '' }}>Côte d'Ivoire</option>
                                        <option value="Burkina Faso" {{ old('pays', $student['pays'] ?? '') == 'Burkina Faso' ? 'selected' : '' }}>Burkina Faso</option>
                                        <option value="Mali" {{ old('pays', $student['pays'] ?? '') == 'Mali' ? 'selected' : '' }}>Mali</option>
                                        <option value="Sénégal" {{ old('pays', $student['pays'] ?? '') == 'Sénégal' ? 'selected' : '' }}>Sénégal</option>
                                        <option value="Ghana" {{ old('pays', $student['pays'] ?? '') == 'Ghana' ? 'selected' : '' }}>Ghana</option>
                                        <option value="Togo" {{ old('pays', $student['pays'] ?? '') == 'Togo' ? 'selected' : '' }}>Togo</option>
                                        <option value="Bénin" {{ old('pays', $student['pays'] ?? '') == 'Bénin' ? 'selected' : '' }}>Bénin</option>
                                        <option value="Niger" {{ old('pays', $student['pays'] ?? '') == 'Niger' ? 'selected' : '' }}>Niger</option>
                                        <option value="Guinée" {{ old('pays', $student['pays'] ?? '') == 'Guinée' ? 'selected' : '' }}>Guinée</option>
                                        <option value="Cameroun" {{ old('pays', $student['pays'] ?? '') == 'Cameroun' ? 'selected' : '' }}>Cameroun</option>
                                        <option value="France" {{ old('pays', $student['pays'] ?? '') == 'France' ? 'selected' : '' }}>France</option>
                                        <option value="Canada" {{ old('pays', $student['pays'] ?? '') == 'Canada' ? 'selected' : '' }}>Canada</option>
                                        <option value="Autre" {{ old('pays', $student['pays'] ?? '') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('pays')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @php
                                // Calculer la date d'expiration actuelle
                                $expirationDate = null;
                                $daysRemaining = null;

                                $durationMonths = 4;
                                $formationKey = $student['formation_souhaitee'] ?? null;
                                if (in_array($formationKey, ['design_graphique_community_management', 'design_graphique_community_manager', 'design-graphique-community-manager'], true)) {
                                    $durationMonths = 7;
                                }

                                if (isset($student['expiration_date'])) {
                                    $expirationDate = \Carbon\Carbon::parse($student['expiration_date'])->format('Y-m-d');
                                    $daysRemaining = (int) \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($student['expiration_date']), false);
                                } elseif (isset($student['registration_date']) || isset($student['created_at'])) {
                                    $baseDate = isset($student['registration_date']) ? $student['registration_date'] : $student['created_at'];
                                    $createdAt = \Carbon\Carbon::parse($baseDate);
                                    $expirationDate = $createdAt->copy()->addMonths($durationMonths)->format('Y-m-d');
                                    $daysRemaining = (int) \Carbon\Carbon::now()->diffInDays($createdAt->copy()->addMonths($durationMonths), false);
                                }
                            @endphp

                            <div class="col-12">
                                <div class="form-card mt-4">
                                    <div class="form-card-header">
                                        <i class="fas fa-calendar-alt"></i>
                                        <h3>Durée de Formation</h3>
                                    </div>
                                    <div class="form-card-body">
                                        @if($daysRemaining !== null)
                                            <div class="expiration-status-card mb-4 alert-{{ $daysRemaining < 0 ? 'danger' : ($daysRemaining <= 30 ? 'warning' : 'success') }}">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        <div class="expiration-icon me-3">
                                                            <i class="fas fa-{{ $daysRemaining < 0 ? 'times-circle' : 'clock' }}"></i>
                                                        </div>
                                                        <div>
                                                            <h4 class="mb-1" style="font-weight: 700; font-size: 1.5rem;">
                                                                @if($daysRemaining < 0)
                                                                    Expiré depuis {{ abs($daysRemaining) }} jour{{ abs($daysRemaining) > 1 ? 's' : '' }}
                                                                @else
                                                                    {{ $daysRemaining }} jour{{ $daysRemaining > 1 ? 's' : '' }} restant{{ $daysRemaining > 1 ? 's' : '' }}
                                                                @endif
                                                            </h4>
                                                            <p class="mb-0" style="font-size: 0.875rem; opacity: 0.9;">
                                                                <i class="fas fa-calendar-day me-1"></i>Date d'expiration : {{ \Carbon\Carbon::parse($expirationDate)->format('d/m/Y') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    @if($daysRemaining < 0)
                                                        <span class="badge bg-danger" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>EXPIRÉ
                                                        </span>
                                                    @elseif($daysRemaining <= 7)
                                                        <span class="badge bg-danger" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>URGENT
                                                        </span>
                                                    @elseif($daysRemaining <= 30)
                                                        <span class="badge bg-warning text-dark" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                                                            <i class="fas fa-clock me-1"></i>ATTENTION
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="expiration_date" class="form-label" style="font-weight: 600; color: var(--form-text);">
                                                        <i class="fas fa-calendar-check me-2"></i>Date d'Expiration
                                                    </label>
                                                    <input type="date"
                                                           class="form-control @error('expiration_date') is-invalid @enderror"
                                                           id="expiration_date"
                                                           name="expiration_date"
                                                           value="{{ old('expiration_date', $expirationDate) }}"
                                                           style="padding: 0.75rem; font-size: 1rem;">
                                                    @error('expiration_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text" style="color: var(--form-text-muted); margin-top: 0.5rem; display: block;">
                                                        <i class="fas fa-info-circle me-1"></i>Modifiez cette date pour ajuster la durée
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label" style="font-weight: 600; color: var(--form-text);">
                                                    <i class="fas fa-bolt me-2"></i>Actions Rapides
                                                </label>
                                                <div class="d-grid gap-2">
                                                    <button type="button" class="btn btn-duration btn-duration-success" onclick="addMonths(1)">
                                                        <i class="fas fa-plus-circle me-2"></i>Prolonger de 1 mois
                                                    </button>
                                                    <button type="button" class="btn btn-duration btn-duration-primary" onclick="addMonths(3)">
                                                        <i class="fas fa-plus-circle me-2"></i>Prolonger de 3 mois
                                                    </button>
                                                    <button type="button" class="btn btn-duration btn-duration-info" onclick="addMonths(6)">
                                                        <i class="fas fa-plus-circle me-2"></i>Prolonger de 6 mois
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Informations système -->
            <div class="form-card mb-4 sidebar-sticky">
                <div class="form-card-header">
                    <i class="fas fa-info-circle"></i>
                    <h3>Informations Système</h3>
                </div>
                <div class="form-card-body">
                    <div class="stat-item">
                        <span class="stat-label">ID Étudiant</span>
                        <span class="stat-value">#{{ $student['id'] }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Date d'inscription</span>
                        <span class="stat-value">{{ isset($student['registration_date']) ? date('d/m/Y', strtotime($student['registration_date'])) : (isset($student['created_at']) ? date('d/m/Y', strtotime($student['created_at'])) : '-') }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Dernière modification</span>
                        <span class="stat-value">{{ isset($student['updated_at']) ? date('d/m/Y', strtotime($student['updated_at'])) : '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions Rapides -->
            <div class="form-card">
                <div class="form-card-header">
                    <i class="fas fa-bolt"></i>
                    <h3>Actions</h3>
                </div>
                <div class="form-card-body">
                    <p class="mb-3" style="color: var(--form-text-muted); font-size: 0.875rem;">
                        <i class="fas fa-info-circle me-1"></i>Cliquez sur Enregistrer pour sauvegarder vos modifications
                    </p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary" onclick="resetForm()">
                            <i class="fas fa-undo me-2"></i>Réinitialiser le formulaire
                        </button>
                        <button type="submit" class="btn btn-success" id="saveBtn" form="editStudentForm">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Fonction pour ajouter des mois à la date d'expiration
function addMonths(months) {
    const dateInput = document.getElementById('expiration_date');
    const currentDate = dateInput.value ? new Date(dateInput.value) : new Date();

    currentDate.setMonth(currentDate.getMonth() + months);

    // Format YYYY-MM-DD
    const year = currentDate.getFullYear();
    const month = String(currentDate.getMonth() + 1).padStart(2, '0');
    const day = String(currentDate.getDate()).padStart(2, '0');

    dateInput.value = `${year}-${month}-${day}`;

    // Visual feedback
    dateInput.classList.add('border-success');
    setTimeout(() => {
        dateInput.classList.remove('border-success');
    }, 1000);

    updateDynamicCountdown();
}

function parseDateInput(value) {
    if (!value) return null;
    const d = new Date(value);
    if (Number.isNaN(d.getTime())) return null;
    return d;
}

function formatDateYmd(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function getDurationMonths() {
    const formationEl = document.getElementById('formation_souhaitee');
    const formation = formationEl ? formationEl.value : '';
    if (['design_graphique_community_management', 'design_graphique_community_manager', 'design-graphique-community-manager'].includes(formation)) {
        return 7;
    }
    return 4;
}

function computeExpirationDate() {
    const expirationEl = document.getElementById('expiration_date');
    const registrationEl = document.getElementById('registration_date');
    if (!expirationEl || !registrationEl) return null;

    const reg = parseDateInput(registrationEl.value);
    if (!reg) return null;

    const months = getDurationMonths();
    const computed = new Date(reg.getTime());
    computed.setMonth(computed.getMonth() + months);

    const exp = parseDateInput(expirationEl.value);
    if (exp) {
        return exp.getTime() > computed.getTime() ? exp : computed;
    }

    return computed;
}

function computeRemainingParts(expirationDate) {
    if (!expirationDate) return null;
    const now = new Date();
    const diffMs = expirationDate.getTime() - now.getTime();
    if (Number.isNaN(diffMs)) return null;

    if (diffMs <= 0) {
        return { expired: true, days: 0, hours: 0, minutes: 0, seconds: 0 };
    }

    const totalSeconds = Math.floor(diffMs / 1000);
    const days = Math.floor(totalSeconds / 86400);
    const hours = Math.floor((totalSeconds % 86400) / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = totalSeconds % 60;

    return { expired: false, days, hours, minutes, seconds };
}

function updateDynamicCountdown() {
    const card = document.querySelector('.expiration-status-card');
    if (!card) return;

    const expirationEl = document.getElementById('expiration_date');
    const registrationEl = document.getElementById('registration_date');
    if (!expirationEl || !registrationEl) return;

    const expDate = computeExpirationDate();
    const remaining = computeRemainingParts(expDate);
    if (!remaining) return;

    const titleEl = card.querySelector('h4');
    const dateTextEl = card.querySelector('p');
    const iconWrapper = card.querySelector('.expiration-icon i');
    const badgeEl = card.querySelector('.badge');

    const expYmd = expDate ? formatDateYmd(expDate) : '';
    const expFr = expDate ? expDate.toLocaleDateString('fr-FR') : '';

    // Mettre à jour la date affichée
    if (dateTextEl) {
        dateTextEl.innerHTML = `<i class="fas fa-calendar-day me-1"></i>Date d'expiration : ${expFr}`;
    }

    // Si expiration_date est vide, on propose la date calculée (sans forcer si déjà rempli)
    if (!expirationEl.value && expYmd) {
        expirationEl.value = expYmd;
    }

    // Mettre à jour le contenu principal
    if (titleEl) {
        const days = remaining.days;
        const hh = String(remaining.hours).padStart(2, '0');
        const mm = String(remaining.minutes).padStart(2, '0');
        const ss = String(remaining.seconds).padStart(2, '0');
        const timeLabel = `${hh}h ${mm}m ${ss}s`;

        if (remaining.expired) {
            titleEl.textContent = `Expiré`;
        } else {
            titleEl.textContent = `${days} jour${days > 1 ? 's' : ''} restant${days > 1 ? 's' : ''} : ${timeLabel}`;
        }
    }

    // Mettre à jour les classes / badge
    card.classList.remove('alert-danger', 'alert-warning', 'alert-success');
    if (remaining.expired) {
        card.classList.add('alert-danger');
        if (iconWrapper) iconWrapper.className = 'fas fa-times-circle';
        if (badgeEl) {
            badgeEl.className = 'badge bg-danger';
            badgeEl.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>EXPIRÉ';
        }
    } else if (remaining.days <= 7) {
        card.classList.add('alert-warning');
        if (iconWrapper) iconWrapper.className = 'fas fa-clock';
        if (badgeEl) {
            badgeEl.className = 'badge bg-danger';
            badgeEl.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>URGENT';
        }
    } else if (remaining.days <= 30) {
        card.classList.add('alert-warning');
        if (iconWrapper) iconWrapper.className = 'fas fa-clock';
        if (badgeEl) {
            badgeEl.className = 'badge bg-warning text-dark';
            badgeEl.innerHTML = '<i class="fas fa-clock me-1"></i>ATTENTION';
        }
    } else {
        card.classList.add('alert-success');
        if (iconWrapper) iconWrapper.className = 'fas fa-clock';
        if (badgeEl) {
            badgeEl.className = 'badge bg-success';
            badgeEl.innerHTML = '<i class="fas fa-check-circle me-1"></i>OK';
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editStudentForm');
    const saveBtn = document.getElementById('saveBtn');

    const registrationEl = document.getElementById('registration_date');
    const formationEl = document.getElementById('formation_souhaitee');
    const expirationEl = document.getElementById('expiration_date');

    if (registrationEl) registrationEl.addEventListener('change', updateDynamicCountdown);
    if (formationEl) formationEl.addEventListener('change', function() {
        const exp = document.getElementById('expiration_date');
        if (exp && !exp.value) {
            updateDynamicCountdown();
        } else {
            updateDynamicCountdown();
        }
    });
    if (expirationEl) expirationEl.addEventListener('change', updateDynamicCountdown);

    updateDynamicCountdown();
    setInterval(updateDynamicCountdown, 1000);

    // Validation en temps réel
    const inputs = form.querySelectorAll('input[required], select[required]');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            validateField(this);
        });
    });

    // Soumission du formulaire
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        if (validateForm()) {
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Enregistrement...';
            saveBtn.disabled = true;

            // Soumettre le formulaire
            this.submit();
        }
    });
});

function validateField(field) {
    const value = field.value.trim();
    const isRequired = field.hasAttribute('required');

    if (isRequired && !value) {
        field.classList.add('is-invalid');
        return false;
    } else {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        return true;
    }
}

function validateForm() {
    const form = document.getElementById('editStudentForm');
    const requiredFields = form.querySelectorAll('input[required], select[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });

    // Validation email
    const emailField = document.getElementById('email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (emailField.value && !emailRegex.test(emailField.value)) {
        emailField.classList.add('is-invalid');
        isValid = false;
    }

    return isValid;
}

function resetForm() {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser le formulaire ? Toutes les modifications non sauvegardées seront perdues.')) {
        document.getElementById('editStudentForm').reset();

        // Supprimer les classes de validation
        const fields = document.querySelectorAll('.is-valid, .is-invalid');
        fields.forEach(field => {
            field.classList.remove('is-valid', 'is-invalid');
        });
    }
}
</script>

@endpush
@endsection
