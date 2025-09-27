@extends('layouts.admin')

@section('title', 'Modifier Étudiant - ' . ($student['prenom'] ?? 'Étudiant') . ' ' . ($student['nom'] ?? ''))

@section('content')
<div class="container-fluid px-4">
    <!-- Header avec breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.statistics.detail', 'total-students') }}">Étudiants</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.students.profile', $student['id']) }}">Profil</a></li>
                    <li class="breadcrumb-item active">Modifier</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-primary">
                <i class="fas fa-user-edit me-2"></i>Modifier Étudiant
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>Informations de l'Étudiant
                    </h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Erreurs de validation</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.students.update', $student['id']) }}" method="POST" id="editStudentForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Informations personnelles -->
                            <div class="col-md-6">
                                <div class="mb-3">
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
                                <div class="mb-3">
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
                                <div class="mb-3">
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
                                <div class="mb-3">
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
                                <div class="mb-3">
                                    <label for="formation_souhaitee" class="form-label">
                                        <i class="fas fa-graduation-cap text-muted me-1"></i>Formation <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('formation_souhaitee') is-invalid @enderror" 
                                            id="formation_souhaitee" name="formation_souhaitee" required>
                                        <option value="">Sélectionner une formation</option>
                                        <option value="design_graphique" {{ old('formation_souhaitee', $student['formation_souhaitee'] ?? '') == 'design_graphique' ? 'selected' : '' }}>
                                            Design Graphique
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
                                <div class="mb-3">
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
                                <div class="mb-3">
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
                        </div>

                        <!-- Informations système (lecture seule) -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="text-muted mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Informations Système
                                </h6>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted">ID Étudiant</label>
                                    <input type="text" class="form-control-plaintext" readonly value="#{{ $student['id'] }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Date d'inscription</label>
                                    <input type="text" class="form-control-plaintext" readonly 
                                           value="{{ isset($student['created_at']) ? date('d/m/Y à H:i', strtotime($student['created_at'])) : '-' }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Dernière modification</label>
                                    <input type="text" class="form-control-plaintext" readonly 
                                           value="{{ isset($student['updated_at']) ? date('d/m/Y à H:i', strtotime($student['updated_at'])) : '-' }}">
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="{{ route('admin.students.profile', $student['id']) }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-1"></i>Retour au profil
                                        </a>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-primary" onclick="resetForm()">
                                            <i class="fas fa-undo me-1"></i>Réinitialiser
                                        </button>
                                        <button type="submit" class="btn btn-success" id="saveBtn">
                                            <i class="fas fa-save me-1"></i>Enregistrer les modifications
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editStudentForm');
    const saveBtn = document.getElementById('saveBtn');
    
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

<style>
.form-control-plaintext {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.375rem;
    padding: 0.375rem 0.75rem;
}

.card-header.bg-primary {
    border-radius: 0.375rem 0.375rem 0 0 !important;
}

.is-valid {
    border-color: #198754;
}

.is-invalid {
    border-color: #dc3545;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.alert {
    border: none;
    border-radius: 0.5rem;
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.text-danger {
    color: #dc3545 !important;
}
</style>
@endpush
@endsection
