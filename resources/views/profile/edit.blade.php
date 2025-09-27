@extends('layouts.ki-admin')

@section('title', 'Modifier mon Profil')

@section('content')
<div class="container-fluid px-4">
    <!-- Header de la page -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-user-edit me-2 text-primary"></i>
                        Modifier mon Profil
                    </h1>
                    <p class="text-muted mb-0">Mettez à jour vos informations personnelles</p>
                </div>
                <div>
                    <a href="{{ route('dashboard.design-graphique') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour au Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>



    <!-- Formulaire d'édition du profil -->
    <form action="{{ route('design-graphique.profil.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
        @csrf

        <div class="row">
            <!-- Colonne principale - Informations personnelles -->
            <div class="col-lg-8">
                <!-- Informations de base -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-primary bg-gradient text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-user me-2"></i>
                            Informations Personnelles
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    Prénom <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                       value="@if(isset($user) && $user->first_name){{ $user->first_name }}@elseif(session('user_prenom')){{ session('user_prenom') }}@endif" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    Nom <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                       value="@if(isset($user) && $user->last_name){{ $user->last_name }}@elseif(session('user_nom')){{ session('user_nom') }}@endif" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="@if(isset($user) && $user->email){{ $user->email }}@elseif(session('user_email')){{ session('user_email') }}@endif" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="age" class="form-label">
                                    <i class="fas fa-birthday-cake me-1"></i>
                                    Âge
                                </label>
                                <input type="number" class="form-control" id="age" name="age"
                                       value="@if(isset($user) && $user->age){{ $user->age }}@endif" min="16" max="100">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Localisation -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-info bg-gradient text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Localisation
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label">
                                    <i class="fas fa-globe me-1"></i>
                                    Pays <span class="text-danger">*</span>
                                </label>
                                @php
                                    $userCountry = '';
                                    if(isset($user) && isset($user->country) && !empty($user->country)) {
                                        $userCountry = trim($user->country);
                                    } elseif(session('user_pays') && !empty(session('user_pays'))) {
                                        $userCountry = trim(session('user_pays'));
                                    }
                                    
                                    // Normalize country value to match dropdown options
                                    $countryOptions = [
                                        'France' => 'France',
                                        'Belgique' => 'Belgique', 
                                        'Suisse' => 'Suisse',
                                        'Canada' => 'Canada',
                                        'Côte d\'Ivoire' => 'Côte d\'Ivoire',
                                        'Cote d\'Ivoire' => 'Côte d\'Ivoire', // Handle variations
                                        'Sénégal' => 'Sénégal',
                                        'Senegal' => 'Sénégal',
                                        'Mali' => 'Mali',
                                        'Burkina Faso' => 'Burkina Faso',
                                        'Niger' => 'Niger',
                                        'Guinée' => 'Guinée',
                                        'Guinee' => 'Guinée',
                                        'Bénin' => 'Bénin',
                                        'Benin' => 'Bénin',
                                        'Togo' => 'Togo',
                                        'Ghana' => 'Ghana',
                                        'Cameroun' => 'Cameroun',
                                        'Gabon' => 'Gabon',
                                        'République du Congo' => 'République du Congo',
                                        'République démocratique du Congo' => 'République démocratique du Congo',
                                        'Maroc' => 'Maroc',
                                        'Algérie' => 'Algérie',
                                        'Algerie' => 'Algérie',
                                        'Tunisie' => 'Tunisie',
                                        'Madagascar' => 'Madagascar',
                                        'Autre' => 'Autre'
                                    ];
                                    
                                    // Normalize the user country to match our options
                                    if(isset($countryOptions[$userCountry])) {
                                        $userCountry = $countryOptions[$userCountry];
                                    }
                                @endphp
                                <select class="form-select" id="country" name="country" required>
                                    <option value="">Sélectionnez votre pays</option>
                                    <option value="France" {{ $userCountry === 'France' ? 'selected' : '' }}>France</option>
                                    <option value="Belgique" {{ $userCountry === 'Belgique' ? 'selected' : '' }}>Belgique</option>
                                    <option value="Suisse" {{ $userCountry === 'Suisse' ? 'selected' : '' }}>Suisse</option>
                                    <option value="Canada" {{ $userCountry === 'Canada' ? 'selected' : '' }}>Canada</option>
                                    <option value="Côte d'Ivoire" {{ $userCountry === "Côte d'Ivoire" ? 'selected' : '' }}>Côte d'Ivoire</option>
                                    <option value="Sénégal" {{ $userCountry === 'Sénégal' ? 'selected' : '' }}>Sénégal</option>
                                    <option value="Mali" {{ $userCountry === 'Mali' ? 'selected' : '' }}>Mali</option>
                                    <option value="Burkina Faso" {{ $userCountry === 'Burkina Faso' ? 'selected' : '' }}>Burkina Faso</option>
                                    <option value="Niger" {{ $userCountry === 'Niger' ? 'selected' : '' }}>Niger</option>
                                    <option value="Guinée" {{ $userCountry === 'Guinée' ? 'selected' : '' }}>Guinée</option>
                                    <option value="Bénin" {{ $userCountry === 'Bénin' ? 'selected' : '' }}>Bénin</option>
                                    <option value="Togo" {{ $userCountry === 'Togo' ? 'selected' : '' }}>Togo</option>
                                    <option value="Ghana" {{ $userCountry === 'Ghana' ? 'selected' : '' }}>Ghana</option>
                                    <option value="Cameroun" {{ $userCountry === 'Cameroun' ? 'selected' : '' }}>Cameroun</option>
                                    <option value="Gabon" {{ $userCountry === 'Gabon' ? 'selected' : '' }}>Gabon</option>
                                    <option value="République du Congo" {{ $userCountry === 'République du Congo' ? 'selected' : '' }}>République du Congo</option>
                                    <option value="République démocratique du Congo" {{ $userCountry === 'République démocratique du Congo' ? 'selected' : '' }}>République démocratique du Congo</option>
                                    <option value="Maroc" {{ $userCountry === 'Maroc' ? 'selected' : '' }}>Maroc</option>
                                    <option value="Algérie" {{ $userCountry === 'Algérie' ? 'selected' : '' }}>Algérie</option>
                                    <option value="Tunisie" {{ $userCountry === 'Tunisie' ? 'selected' : '' }}>Tunisie</option>
                                    <option value="Madagascar" {{ $userCountry === 'Madagascar' ? 'selected' : '' }}>Madagascar</option>
                                    <option value="Autre" {{ $userCountry === 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">
                                    <i class="fas fa-city me-1"></i>
                                    Ville <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="city" name="city"
                                       value="@if(isset($user) && $user->city){{ $user->city }}@elseif(session('user_ville')){{ session('user_ville') }}@endif" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="district" class="form-label">
                                <i class="fas fa-map me-1"></i>
                                Quartier
                            </label>
                            <input type="text" class="form-control" id="district" name="district"
                                   value="@if(isset($user) && $user->district){{ $user->district }}@endif">
                        </div>
                    </div>
                </div>

                <!-- Contact -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-success bg-gradient text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-phone me-2"></i>
                            Informations de Contact
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone me-1"></i>
                                    Numéro de téléphone
                                </label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                       value="{{ old('phone', $user->phone) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="whatsapp" class="form-label">
                                    <i class="fab fa-whatsapp me-1"></i>
                                    WhatsApp
                                </label>
                                <input type="tel" class="form-control" id="whatsapp" name="whatsapp"
                                       value="{{ old('whatsapp', $user->whatsapp) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formation et Niveau -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-warning bg-gradient text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-graduation-cap me-2"></i>
                            Formation et Niveau
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="current_level" class="form-label">
                                    <i class="fas fa-chart-line me-1"></i>
                                    Niveau actuel <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="current_level" name="current_level" required>
                                    <option value="debutant" {{ old('current_level', $user->current_level ?? session('user_niveau', '')) == 'debutant' ? 'selected' : '' }}>
                                        Débutant
                                    </option>
                                    <option value="intermediaire" {{ old('current_level', $user->current_level ?? session('user_niveau', '')) == 'intermediaire' ? 'selected' : '' }}>
                                        Intermédiaire
                                    </option>
                                    <option value="perfectionnement" {{ old('current_level', $user->current_level ?? session('user_niveau', '')) == 'perfectionnement' ? 'selected' : '' }}>
                                        Perfectionnement
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="education_level" class="form-label">
                                    <i class="fas fa-school me-1"></i>
                                    Niveau d'étude
                                </label>
                                @php
                                    $userEducationLevel = '';
                                    if(isset($user) && $user->education_level) {
                                        $userEducationLevel = $user->education_level;
                                    }
                                @endphp
                                <select class="form-control" id="education_level" name="education_level">
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
                        </div>

                        <div class="mb-3">
                            <label for="last_diploma" class="form-label">
                                <i class="fas fa-certificate me-1"></i>
                                Dernier diplôme obtenu
                            </label>
                            <input type="text" class="form-control" id="last_diploma" name="last_diploma"
                                   value="@if(isset($user) && $user->last_diploma){{ $user->last_diploma }}@else{{ 'Baccalauréat Scientifique' }}@endif"
                                   placeholder="Ex: Baccalauréat, BTS, Licence...">
                        </div>
                    </div>
                </div>

                <!-- Biographie et Attentes -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-secondary bg-gradient text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-user-circle me-2"></i>
                            À propos de vous
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="biography" class="form-label">
                                <i class="fas fa-user-edit me-1"></i>
                                Biographie
                            </label>
                            <textarea class="form-control" id="biography" name="biography" rows="4"
                                      placeholder="Parlez-nous de vous, votre parcours, vos passions...">{{ old('biography', $user->biography) }}</textarea>
                            <div class="form-text">Maximum 1000 caractères</div>
                        </div>

                        <div class="mb-3">
                            <label for="expectations" class="form-label">
                                <i class="fas fa-bullseye me-1"></i>
                                Vos attentes pour cette formation
                            </label>
                            <textarea class="form-control" id="expectations" name="expectations" rows="4"
                                      placeholder="Quels sont vos objectifs ? Que souhaitez-vous apprendre ?">{{ old('expectations', $user->expectations) }}</textarea>
                            <div class="form-text">Maximum 1000 caractères</div>
                        </div>
                    </div>
                </div>

                <!-- Sécurité -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-danger bg-gradient text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-lock me-2"></i>
                            Sécurité
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Laissez ces champs vides si vous ne souhaitez pas changer votre mot de passe.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-key me-1"></i>
                                    Nouveau mot de passe
                                </label>
                                <input type="password" class="form-control" id="password" name="password"
                                       minlength="6">
                                <div class="form-text">Minimum 6 caractères</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-key me-1"></i>
                                    Confirmer le mot de passe
                                </label>
                                <input type="password" class="form-control" id="password_confirmation"
                                       name="password_confirmation">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Photo et actions -->
            <div class="col-lg-4">
                <!-- Photo de profil -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-primary bg-gradient text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-camera me-2"></i>
                            Photo de Profil
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="profile-photo-container mb-3">
                            @if($user->profile_photo && $user->profile_photo != '')
                                <img src="{{ asset('uploads/photos/' . basename($user->profile_photo)) }}" alt="Photo de profil"
                                     class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;"
                                     id="photoPreview">
                            @else
                                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mb-3 mx-auto"
                                     style="width: 150px; height: 150px;" id="photoPreview">
                                    <i class="fas fa-user fa-4x text-primary"></i>
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">
                                <i class="fas fa-upload me-1"></i>
                                Changer la photo
                            </label>
                            <input type="file" class="form-control" id="photo" name="photo"
                                   accept="image/jpeg,image/png,image/jpg,image/gif">
                            <div class="form-text">JPG, PNG, GIF - Max 2MB</div>
                        </div>
                    </div>
                </div>



                <!-- Actions rapides -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-success bg-gradient text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-bolt me-2"></i>
                            Actions Rapides
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg save-button-animated position-relative overflow-hidden">
                                <div class="wave-effect"></div>
                                <div class="button-content position-relative">
                                    <i class="fas fa-save me-2 pulse-icon"></i>
                                    <span class="save-text">Enregistrer les modifications</span>
                                </div>
                            </button>

                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="fas fa-undo me-2"></i>
                                Réinitialiser
                            </button>

                            <a href="{{ route('dashboard.design-graphique') }}" class="btn btn-outline-info">
                                <i class="fas fa-eye me-2"></i>
                                Voir mon profil
                            </a>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chargement dynamique des pays
    const paysSelect = document.getElementById('country');
    const currentPays = '{{ old("country", $user->country) }}';

    // Liste des pays
    const pays = [
        'Afghanistan', 'Afrique du Sud', 'Albanie', 'Algérie', 'Allemagne', 'Andorre', 'Angola', 'Antigua-et-Barbuda',
        'Arabie saoudite', 'Argentine', 'Arménie', 'Australie', 'Autriche', 'Azerbaïdjan', 'Bahamas', 'Bahreïn',
        'Bangladesh', 'Barbade', 'Belgique', 'Belize', 'Bénin', 'Bhoutan', 'Biélorussie', 'Birmanie', 'Bolivie',
        'Bosnie-Herzégovine', 'Botswana', 'Brésil', 'Brunei', 'Bulgarie', 'Burkina Faso', 'Burundi', 'Cambodge',
        'Cameroun', 'Canada', 'Cap-Vert', 'Centrafrique', 'Chili', 'Chine', 'Chypre', 'Colombie', 'Comores',
        'Congo', 'Congo (RDC)', 'Corée du Nord', 'Corée du Sud', 'Costa Rica', 'Côte d\'Ivoire', 'Croatie', 'Cuba',
        'Danemark', 'Djibouti', 'Dominique', 'Égypte', 'Émirats arabes unis', 'Équateur', 'Érythrée', 'Espagne',
        'Estonie', 'États-Unis', 'Éthiopie', 'Fidji', 'Finlande', 'France', 'Gabon', 'Gambie', 'Géorgie', 'Ghana',
        'Grèce', 'Grenade', 'Guatemala', 'Guinée', 'Guinée-Bissau', 'Guinée équatoriale', 'Guyana', 'Haïti',
        'Honduras', 'Hongrie', 'Îles Marshall', 'Îles Salomon', 'Inde', 'Indonésie', 'Irak', 'Iran', 'Irlande',
        'Islande', 'Israël', 'Italie', 'Jamaïque', 'Japon', 'Jordanie', 'Kazakhstan', 'Kenya', 'Kirghizistan',
        'Kiribati', 'Koweït', 'Laos', 'Lesotho', 'Lettonie', 'Liban', 'Liberia', 'Libye', 'Liechtenstein',
        'Lituanie', 'Luxembourg', 'Macédoine du Nord', 'Madagascar', 'Malaisie', 'Malawi', 'Maldives', 'Mali',
        'Malte', 'Maroc', 'Maurice', 'Mauritanie', 'Mexique', 'Micronésie', 'Moldavie', 'Monaco', 'Mongolie',
        'Monténégro', 'Mozambique', 'Namibie', 'Nauru', 'Népal', 'Nicaragua', 'Niger', 'Nigeria', 'Norvège',
        'Nouvelle-Zélande', 'Oman', 'Ouganda', 'Ouzbékistan', 'Pakistan', 'Palaos', 'Palestine', 'Panama',
        'Papouasie-Nouvelle-Guinée', 'Paraguay', 'Pays-Bas', 'Pérou', 'Philippines', 'Pologne', 'Portugal',
        'Qatar', 'République dominicaine', 'République tchèque', 'Roumanie', 'Royaume-Uni', 'Russie', 'Rwanda',
        'Saint-Kitts-et-Nevis', 'Saint-Marin', 'Saint-Vincent-et-les-Grenadines', 'Sainte-Lucie', 'Salvador',
        'Samoa', 'São Tomé-et-Principe', 'Sénégal', 'Serbie', 'Seychelles', 'Sierra Leone', 'Singapour',
        'Slovaquie', 'Slovénie', 'Somalie', 'Soudan', 'Soudan du Sud', 'Sri Lanka', 'Suède', 'Suisse',
        'Suriname', 'Swaziland', 'Syrie', 'Tadjikistan', 'Tanzanie', 'Tchad', 'Thaïlande', 'Timor oriental',
        'Togo', 'Tonga', 'Trinité-et-Tobago', 'Tunisie', 'Turkménistan', 'Turquie', 'Tuvalu', 'Ukraine',
        'Uruguay', 'Vanuatu', 'Vatican', 'Venezuela', 'Viêt Nam', 'Yémen', 'Zambie', 'Zimbabwe'
    ];

    // Ajout des options
    pays.forEach(function(country) {
        const option = document.createElement('option');
        option.value = country;
        option.textContent = country;
        if (country === currentPays) {
            option.selected = true;
        }
        paysSelect.appendChild(option);
    });

    // Prévisualisation de la photo
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photoPreview');

    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.innerHTML = `<img src="${e.target.result}" alt="Aperçu" class="rounded-circle" id="photoPreview">`;
            };
            reader.readAsDataURL(file);
        }
    });

    // Validation du formulaire
    const form = document.getElementById('profileForm');
    form.addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirmation').value;

        if (password && password !== passwordConfirm) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas.');
            return false;
        }
    });
});
</script>

<style>
.profile-photo-container {
    position: relative;
    display: inline-block;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.btn {
    transition: all 0.2s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
}

.form-control:focus, .form-select:focus {
    border-color: #3399ff;
    box-shadow: 0 0 0 0.2rem rgba(51, 153, 255, 0.25);
}

.alert {
    border: none;
    border-radius: 10px;
}

.badge {
    font-size: 0.75em;
    padding: 0.5em 0.75em;
}

/* Effets spectaculaires pour le bouton Enregistrer */
.save-button-animated {
    background: linear-gradient(45deg, #003366, #3399ff, #ff6633, #FF9900);
    background-size: 400% 400%;
    border: none;
    box-shadow: 0 0 20px rgba(0, 51, 102, 0.6), 0 0 30px rgba(255, 102, 51, 0.4);
    animation: gradientShift 3s ease infinite, buttonPulse 2s ease-in-out infinite;
    transform: scale(1.05);
    font-weight: bold;
    letter-spacing: 0.5px;
    color: white;
}

.save-button-animated:hover {
    transform: scale(1.08) translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 51, 102, 0.8), 0 8px 35px rgba(255, 102, 51, 0.6);
}

/* Animation de gradient qui bouge */
@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Animation de pulsation du bouton */
@keyframes buttonPulse {
    0% {
        box-shadow: 0 0 20px rgba(0, 51, 102, 0.6), 0 0 30px rgba(255, 102, 51, 0.4);
        transform: scale(1.05);
    }
    50% {
        box-shadow: 0 0 30px rgba(0, 51, 102, 0.8), 0 0 40px rgba(255, 102, 51, 0.6), 0 0 50px rgba(51, 153, 255, 0.3);
        transform: scale(1.07);
    }
    100% {
        box-shadow: 0 0 20px rgba(0, 51, 102, 0.6), 0 0 30px rgba(255, 102, 51, 0.4);
        transform: scale(1.05);
    }
}

/* Effet de vague d'eau */
.wave-effect {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 153, 0, 0.3), rgba(255, 255, 255, 0.4), rgba(51, 153, 255, 0.3), transparent);
    animation: waveMove 2.5s linear infinite;
    z-index: 1;
}

@keyframes waveMove {
    0% { left: -100%; }
    100% { left: 100%; }
}

/* Contenu du bouton au-dessus de la vague */
.button-content {
    z-index: 2;
}

/* Animation de l'icône */
.pulse-icon {
    animation: iconPulse 1.5s ease-in-out infinite;
}

@keyframes iconPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.2); }
}

/* Animation du texte avec clignotement subtil */
.save-text {
    animation: textGlow 2s ease-in-out infinite;
}

@keyframes textGlow {
    0%, 100% {
        text-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
        opacity: 1;
    }
    50% {
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.8), 0 0 15px rgba(255, 255, 255, 0.6);
        opacity: 0.9;
    }
}

/* Effet de particules scintillantes */
.save-button-animated::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.3) 2px, transparent 2px),
                radial-gradient(circle at 80% 50%, rgba(255, 255, 255, 0.3) 1px, transparent 1px),
                radial-gradient(circle at 40% 80%, rgba(255, 255, 255, 0.2) 1px, transparent 1px);
    animation: sparkle 3s linear infinite;
    z-index: 1;
}

@keyframes sparkle {
    0%, 100% { opacity: 0; }
    50% { opacity: 1; }
}

/* Effet de bordure animée */
.save-button-animated::after {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(45deg, #003366, #3399ff, #ff6633, #FF9900, #003366, #3399ff);
    background-size: 400% 400%;
    border-radius: inherit;
    z-index: -1;
    animation: borderGlow 4s ease infinite;
}

@keyframes borderGlow {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

/* Effet au focus */
.save-button-animated:focus {
    outline: none;
    animation: focusFlash 0.5s ease;
}

@keyframes focusFlash {
    0% { transform: scale(1.05); }
    50% { transform: scale(1.12); }
    100% { transform: scale(1.05); }
}
</style>
@endsection

@section('styles')
<!-- Profile CSS -->
<link href="{{ asset('assets/css/profile.css') }}" rel="stylesheet">
<!-- Summernote WYSIWYG Editor CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.css" rel="stylesheet">
@endsection

@section('scripts')
<!-- Summernote WYSIWYG Editor JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/lang/summernote-fr-FR.min.js"></script>
<!-- Profile JavaScript -->
<script src="{{ asset('assets/js/profile.js') }}"></script>
@endsection
