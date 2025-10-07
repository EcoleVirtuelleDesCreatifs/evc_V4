@extends('layouts.ki-admin')

@section('title', 'Paramètres - Profil Utilisateur - EVC 2024')
@section('page-title', 'Paramètres du Profil')

@section('content')

<!-- Messages Flash Laravel -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Erreurs de validation :</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- En-tête avec photo de profil -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="background: linear-gradient(135deg, #003366 0%, #3399ff 100%); color: white;">
            <div class="card-body py-4">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <div class="profile-photo-container" style="position: relative; width: 120px; height: 120px; margin: 0 auto;">
                            @if(isset($user) && property_exists($user, 'profile_photo') && $user->profile_photo)
                                <img id="profilePhoto" src="{{ asset('uploads/photos/' . basename($user->profile_photo)) }}"
                                     alt="Photo de profil" class="rounded-circle"
                                     style="width: 120px; height: 120px; object-fit: cover; border: 4px solid white;">
                            @elseif(session('user_photo'))
                                <img id="profilePhoto" src="{{ asset('uploads/photos/' . basename(session('user_photo'))) }}"
                                     alt="Photo de profil" class="rounded-circle"
                                     style="width: 120px; height: 120px; object-fit: cover; border: 4px solid white;">
                            @else
                                <div id="profilePhoto" class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                                     style="width: 120px; height: 120px; border: 4px solid white;">
                                    <i class="fas fa-user fs-1 text-primary"></i>
                                </div>
                            @endif
                            <div class="photo-overlay"
                                 style="position: absolute; top: 0; left: 0; width: 120px; height: 120px; background: rgba(0,0,0,0.5); border-radius: 50%; display: none; align-items: center; justify-content: center; cursor: pointer;"
                                 onclick="document.getElementById('photoFile').click()">
                                <i class="fas fa-camera" style="font-size: 1.5rem; color: white;"></i>
                            </div>
                            <input type="file" id="photoFile" accept="image/*" style="display: none;" onchange="handlePhotoUpload(this)">
                        </div>
                    </div>
                    <div class="col-md-10">
                        <h3 class="mb-2">
                            <i class="fas fa-user-cog me-3"></i>
                            @if(isset($user) && property_exists($user, 'first_name') && property_exists($user, 'last_name') && $user->first_name && $user->last_name)
                                {{ $user->first_name }} {{ $user->last_name }}
                            @elseif(session('user_prenom') && session('user_nom'))
                                {{ session('user_prenom') }} {{ session('user_nom') }}
                            @else
                                Mon Profil
                            @endif - Formation Infographie EVC
                        </h3>
                        <p class="mb-0 opacity-75">Gérez vos informations personnelles et préférences de formation</p>
                        <div class="mt-2">
                            <span class="badge bg-light text-dark me-2">
                                <i class="fas fa-calendar me-1"></i>
                                Inscrit depuis {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('F Y') : 'Date inconnue' }}
                            </span>
                            <span class="badge bg-warning">
                                <i class="fas fa-graduation-cap me-1"></i>
                                {{ $user->status ?? 'Étudiant actif' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="profileForm" method="POST" action="{{ route('design-graphique.parametres.update') }}">
@csrf
<div class="row">
    <div class="col-md-8">
        <!-- Informations personnelles -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-id-card me-2" style="color: #003366;"></i>
                    Informations personnelles
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstName" class="form-label"><strong>Prénom</strong> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="firstName" name="firstName"
                               value="@if(isset($user) && property_exists($user, 'first_name') && $user->first_name){{ $user->first_name }}@elseif(session('user_prenom')){{ session('user_prenom') }}@endif">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastName" class="form-label"><strong>Nom</strong> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="lastName" name="lastName"
                               value="@if(isset($user) && property_exists($user, 'last_name') && $user->last_name){{ $user->last_name }}@elseif(session('user_nom')){{ session('user_nom') }}@endif">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="age" class="form-label"><strong>Âge</strong></label>
                        <input type="number" class="form-control" id="age" name="age" value="{{ $user->age ?? '' }}" min="16" max="99">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="country" class="form-label"><strong>Pays</strong> <span class="text-danger">*</span></label>
                        @php
                            $userCountry = '';
                            if(isset($user) && property_exists($user, 'country') && $user->country) {
                                $userCountry = $user->country;
                            } elseif(session('user_pays')) {
                                $userCountry = session('user_pays');
                            }
                        @endphp
                        <select class="form-select" id="country" name="country">
                            <option value="France" {{ $userCountry === 'France' ? 'selected' : '' }}>France</option>
                            <option value="Belgique" {{ $userCountry === 'Belgique' ? 'selected' : '' }}>Belgique</option>
                            <option value="Suisse" {{ $userCountry === 'Suisse' ? 'selected' : '' }}>Suisse</option>
                            <option value="Canada" {{ $userCountry === 'Canada' ? 'selected' : '' }}>Canada</option>
                            <option value="Maroc" {{ $userCountry === 'Maroc' ? 'selected' : '' }}>Maroc</option>
                            <option value="Tunisie" {{ $userCountry === 'Tunisie' ? 'selected' : '' }}>Tunisie</option>
                            <option value="Algérie" {{ $userCountry === 'Algérie' ? 'selected' : '' }}>Algérie</option>
                            <option value="Sénégal" {{ $userCountry === 'Sénégal' ? 'selected' : '' }}>Sénégal</option>
                            <option value="Côte d'Ivoire" {{ $userCountry === 'Côte d\'Ivoire' ? 'selected' : '' }}>Côte d'Ivoire</option>
                            <option value="Cameroun" {{ $userCountry === 'Cameroun' ? 'selected' : '' }}>Cameroun</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="city" class="form-label"><strong>Ville</strong> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="city" name="city"
                               value="@if(isset($user) && property_exists($user, 'city') && $user->city){{ $user->city }}@elseif(session('user_ville')){{ session('user_ville') }}@endif">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="district" class="form-label"><strong>Quartier</strong></label>
                    <input type="text" class="form-control" id="district" name="district" value="{{ $user->district ?? '' }}" placeholder="Quartier ou arrondissement">
                </div>
                <div class="mb-3">
                    <label for="biography" class="form-label"><strong>Biographie</strong></label>
                    <textarea class="form-control" id="biography" name="biography" rows="4" placeholder="Parlez-nous de vous, votre parcours, vos passions...">{{ $user->biography ?? 'Passionné de design graphique depuis mon plus jeune âge, je souhaite développer mes compétences en infographie pour évoluer professionnellement dans le domaine créatif.' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-address-book me-2" style="color: #3399ff;"></i>
                    Informations de contact
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label"><strong>Adresse email</strong> <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email"
                               value="@if(isset($user) && property_exists($user, 'email') && $user->email){{ $user->email }}@elseif(session('user_email')){{ session('user_email') }}@endif">
                        <div class="form-text">Votre email principal pour les communications</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label"><strong>Numéro de téléphone</strong></label>
                        <input type="tel" class="form-control" id="phone" name="phone"
                               value="@if(isset($user) && property_exists($user, 'phone') && $user->phone){{ $user->phone }}@elseif(session('user_telephone')){{ session('user_telephone') }}@endif"
                               placeholder="+33 6 12 34 56 78">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="whatsapp" class="form-label"><strong>WhatsApp</strong></label>
                    <input type="tel" class="form-control" id="whatsapp" name="whatsapp"
                           value="@if(isset($user) && property_exists($user, 'whatsapp') && $user->whatsapp){{ $user->whatsapp }}@elseif(session('user_whatsapp')){{ session('user_whatsapp') }}@endif">
                    <div class="form-text">
                        <i class="fab fa-whatsapp text-success me-1"></i>
                        Numéro WhatsApp pour les communications rapides
                    </div>
                </div>
            </div>
        </div>

        <!-- Formation et niveau -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-graduation-cap me-2" style="color: #FF9900;"></i>
                    Parcours et formation
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="educationLevel" class="form-label"><strong>Niveau d'étude</strong></label>
                        @php
                            $userEducationLevel = '';
                            if(isset($user) && property_exists($user, 'education_level') && $user->education_level) {
                                $userEducationLevel = $user->education_level;
                            }
                        @endphp
                        <select class="form-control" id="educationLevel" name="educationLevel">
                            <option value="">Sélectionner votre niveau</option>
                            <option value="college" {{ $userEducationLevel === 'college' ? 'selected' : '' }}>Collège</option>
                            <option value="lycee" {{ $userEducationLevel === 'lycee' ? 'selected' : '' }}>Lycée</option>
                            <option value="bac" {{ $userEducationLevel === 'bac' ? 'selected' : '' }}>Baccalauréat</option>
                            <option value="bac+2" {{ $userEducationLevel === 'bac+2' ? 'selected' : '' }}>Bac+2 (BTS, DUT, etc.)</option>
                            <option value="bac+3" {{ $userEducationLevel === 'bac+3' ? 'selected' : '' }}>Bac+3 (Licence, Bachelor)</option>
                            <option value="bac+5" {{ $userEducationLevel === 'bac+5' ? 'selected' : '' }}>Bac+5 (Master, Ingénieur)</option>
                            <option value="doctorat" {{ $userEducationLevel === 'doctorat' ? 'selected' : '' }}>Doctorat</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastDiploma" class="form-label"><strong>Dernier diplôme obtenu</strong></label>
                        <input type="text" class="form-control" id="lastDiploma" name="lastDiploma" value="{{ $user->last_diploma ?? 'Baccalauréat Scientifique' }}" placeholder="Ex: Bac ES, BTS Communication...">
                    </div>
                </div>

            </div>
        </div>

        <!-- Sécurité -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-shield-alt me-2" style="color: #ff6633;"></i>
                    Sécurité et mot de passe
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Modification du mot de passe</strong><br>
                    Laissez vide si vous ne souhaitez pas changer votre mot de passe actuel.
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="currentPassword" class="form-label"><strong>Mot de passe actuel</strong></label>
                        <input type="password" class="form-control" id="currentPassword" placeholder="Saisissez votre mot de passe actuel">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="newPassword" class="form-label"><strong>Nouveau mot de passe</strong></label>
                        <input type="password" class="form-control" id="newPassword" placeholder="Minimum 8 caractères">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label"><strong>Confirmer le nouveau mot de passe</strong></label>
                    <input type="password" class="form-control" id="confirmPassword" placeholder="Répétez le nouveau mot de passe">
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <button type="submit" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-save me-2"></i>
                            Sauvegarder les modifications
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                            <i class="fas fa-undo me-2"></i>
                            Annuler
                        </button>
                    </div>
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            Dernière modification : Aujourd'hui à 14:30
                        </small>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Résumé du profil -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-circle me-2" style="color: #003366;"></i>
                    Résumé du profil
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="progress-circle" style="width: 100px; height: 100px; margin: 0 auto; position: relative;">
                        <svg width="100" height="100" style="transform: rotate(-90deg);">
                            <circle cx="50" cy="50" r="40" fill="none" stroke="#e9ecef" stroke-width="8"></circle>
                            <circle cx="50" cy="50" r="40" fill="none" stroke="#3399ff" stroke-width="8"
                                    stroke-dasharray="251.2" stroke-dashoffset="62.8"
                                    style="transition: stroke-dashoffset 0.5s ease;"></circle>
                        </svg>
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: bold; color: #003366;">
                            75%
                        </div>
                    </div>
                    <p class="mt-2 mb-0"><strong>Profil complété</strong></p>
                    <small class="text-muted">Ajoutez plus d'informations pour atteindre 100%</small>
                </div>

                <div class="profile-stats">
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-check-circle text-success me-1"></i> Infos personnelles</span>
                        <span class="badge bg-success">Complété</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-check-circle text-success me-1"></i> Contact</span>
                        <span class="badge bg-success">Complété</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-exclamation-circle text-warning me-1"></i> Photo de profil</span>
                        <span class="badge bg-warning">Manquant</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-check-circle text-success me-1"></i> Formation</span>
                        <span class="badge bg-success">Complété</span>
                    </div>
                </div>
            </div>
        </div>



        <!-- Support -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-headset me-2" style="color: #003366;"></i>
                    Besoin d'aide ?
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-3">Notre équipe est là pour vous accompagner !</p>
                <div class="d-grid gap-2">
                    <a href="mailto:info@ecolevirtuelledescreatifs.com" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-envelope me-2"></i>
                        info@ecolevirtuelledescreatifs.com
                    </a>
                    <a href="https://wa.me/2250717258602" class="btn btn-sm btn-outline-success" target="_blank">
                        <i class="fab fa-whatsapp me-2"></i>
                        07 17 25 86 02
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

<style>
/* Styles personnalisés pour la page paramètres */
.profile-photo-container:hover .photo-overlay {
    display: flex !important;
}

.form-check-label {
    cursor: pointer;
    padding: 15px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: block;
    width: 100%;
}

.form-check-input:checked + .form-check-label {
    border-color: #3399ff;
    background-color: rgba(51, 153, 255, 0.1);
}

.form-check-label:hover {
    border-color: #3399ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.card:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.progress-circle {
    animation: fadeInScale 0.8s ease-out;
}

@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.profile-stats > div {
    animation: slideInLeft 0.6s ease-out;
    animation-fill-mode: both;
}

.profile-stats > div:nth-child(1) { animation-delay: 0.1s; }
.profile-stats > div:nth-child(2) { animation-delay: 0.2s; }
.profile-stats > div:nth-child(3) { animation-delay: 0.3s; }
.profile-stats > div:nth-child(4) { animation-delay: 0.4s; }

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@keyframes slideOutRight {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(100%); opacity: 0; }
}

/* Responsive */
@media (max-width: 768px) {
    .profile-photo-container {
        width: 80px !important;
        height: 80px !important;
    }

    .profile-photo-container img,
    .profile-photo-container .photo-overlay {
        width: 80px !important;
        height: 80px !important;
    }

    .progress-circle {
        width: 80px !important;
        height: 80px !important;
    }

    .progress-circle svg {
        width: 80px !important;
        height: 80px !important;
    }
}
</style>

<script>
// Gestion de l'upload de photo
function handlePhotoUpload(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Validation du fichier
        if (!file.type.startsWith('image/')) {
            showNotification('Erreur', 'Veuillez sélectionner un fichier image valide.', 'error');
            return;
        }

        if (file.size > 5 * 1024 * 1024) { // 5MB max
            showNotification('Erreur', 'La taille de l\'image ne doit pas dépasser 5MB.', 'error');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePhoto').src = e.target.result;
            updateProfileCompletion();
            showNotification('Succès', 'Photo de profil mise à jour avec succès !', 'success');
        };
        reader.readAsDataURL(file);
    }
}

// Fonction de notification
function showNotification(title, message, type = 'info') {
    // Créer la notification
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 500px;';
    
    notification.innerHTML = `
        <strong>${title}</strong><br>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove après 5 secondes
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Mettre à jour la date de dernière modification
function updateLastModified() {
    const now = new Date();
    const dateStr = now.toLocaleDateString('fr-FR') + ' à ' + now.toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'});
    
    // Chercher et mettre à jour l'élément de dernière modification s'il existe
    const lastModifiedElement = document.querySelector('.last-modified');
    if (lastModifiedElement) {
        lastModifiedElement.textContent = `Dernière modification: ${dateStr}`;
    }
}

// Mettre à jour le score de complétion du profil
function updateProfileCompletion() {
    // Cette fonction peut être étendue pour calculer dynamiquement le score
    console.log('Profile completion updated');
}

// Form submission avec loading state
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profileForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function() {
        // Disable button and show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sauvegarde en cours...';
    });
});

// Réinitialiser le formulaire
function resetForm() {
    if (confirm('Êtes-vous sûr de vouloir annuler toutes les modifications ?')) {
        document.getElementById('profileForm').reset();
        showNotification('Info', 'Modifications annulées.', 'info');
    }
}

// Faire défiler vers une section
function scrollToSection(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'center' });

        // Effet de surbrillance
        element.style.transition = 'all 0.3s ease';
        element.style.transform = 'scale(1.05)';
        element.style.boxShadow = '0 0 20px rgba(51, 153, 255, 0.3)';

        setTimeout(() => {
            element.style.transform = 'scale(1)';
            element.style.boxShadow = 'none';
        }, 1000);
    }
}

// Exporter le profil
function exportProfile() {
    showNotification('Info', 'Export du profil en cours...', 'info');

    setTimeout(() => {
        // Simulation du téléchargement
        const link = document.createElement('a');
        link.href = 'data:text/plain;charset=utf-8,' + encodeURIComponent('Profil EVC - Jean Dupont\n\nInformations exportées le ' + new Date().toLocaleDateString());
        link.download = 'profil-evc-jean-dupont.txt';
        link.click();

        showNotification('Succès', 'Profil exporté avec succès !', 'success');
    }, 1500);
}

// Prévisualiser le profil
function previewProfile() {
    const firstName = document.getElementById('firstName').value;
    const lastName = document.getElementById('lastName').value;
    const email = document.getElementById('email').value;
    const level = document.querySelector('input[name="currentLevel"]:checked')?.value || 'Non spécifié';

    const previewContent = `
        <div class="text-center mb-3">
            <img src="${document.getElementById('profilePhoto').src}" class="rounded-circle" width="80" height="80">
            <h5 class="mt-2">${firstName} ${lastName}</h5>
            <p class="text-muted">${email}</p>
                <h6 class="mb-0">
                    <i class="fas fa-question-circle me-2"></i>
                    Support
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary btn-sm">Centre d'aide</button>
                    <button class="btn btn-outline-primary btn-sm">Contacter le support</button>
                    <button class="btn btn-outline-primary btn-sm">Signaler un problème</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
