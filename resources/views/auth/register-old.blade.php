<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - EVC Platform</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6.4 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #003366 0%, #3399ff 50%, #ff6633 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            padding: 20px 0;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="%23ffffff" stop-opacity="0.1"/><stop offset="100%" stop-color="%23ffffff" stop-opacity="0"/></radialGradient></defs><circle cx="200" cy="200" r="100" fill="url(%23a)"><animate attributeName="cx" values="200;800;200" dur="20s" repeatCount="indefinite"/></circle><circle cx="800" cy="300" r="150" fill="url(%23a)"><animate attributeName="cy" values="300;700;300" dur="25s" repeatCount="indefinite"/></circle><circle cx="400" cy="600" r="80" fill="url(%23a)"><animate attributeName="r" values="80;120;80" dur="15s" repeatCount="indefinite"/></circle></svg>') center/cover;
            animation: float 30s ease-in-out infinite;
            z-index: 1;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 800px;
            width: 100%;
        }

        .register-header {
            background: linear-gradient(135deg, #003366 0%, #3399ff 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo img {
            width: 50px;
            height: auto;
        }

        .register-form {
            padding: 40px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #3399ff;
            box-shadow: 0 0 0 0.2rem rgba(51, 153, 255, 0.25);
        }

        .form-select {
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 16px;
        }

        .photo-upload {
            border: 2px dashed #3399ff;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            background: #f8f9ff;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .photo-upload:hover {
            background: #e6f0ff;
        }

        .photo-preview {
            max-width: 150px;
            max-height: 150px;
            border-radius: 10px;
            margin: 10px auto;
            display: none;
        }

        .btn-register {
            background: linear-gradient(135deg, #3399ff 0%, #003366 100%);
            border: none;
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(51, 153, 255, 0.3);
        }

        .btn-register:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e1e5e9;
        }

        .divider span {
            background: white;
            padding: 0 20px;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .register-footer {
            background: #f8f9fa;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e1e5e9;
        }

        .register-footer p {
            margin: 0;
            color: #6c757d;
            font-size: 0.95rem;
        }

        .register-footer a {
            color: #003366;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .register-footer a:hover {
            color: #3399ff;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            z-index: 5;
        }

        .password-toggle:hover {
            color: #003366;
        }

        .form-check-input:checked {
            background-color: #003366;
            border-color: #003366;
        }

        .form-check-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: linear-gradient(135deg, #ff6633, #FF9900);
            color: white;
        }

        .alert-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .register-form {
                padding: 30px 20px;
            }

            .register-header {
                padding: 25px 15px;
            }

            .logo {
                font-size: 2rem;
            }
        }

        /* Loading state */
        .btn-register.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        /* Form validation */
        .is-invalid {
            border-color: #ff6633 !important;
        }

        .is-valid {
            border-color: #28a745 !important;
        }

        .invalid-feedback {
            color: #ff6633;
            font-size: 0.875rem;
            margin-top: 5px;
        }

        .valid-feedback {
            color: #28a745;
            font-size: 0.875rem;
            margin-top: 5px;
        }

        /* Photo upload styles */
        .photo-upload-container {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin-top: 10px;
        }

        .photo-preview {
            width: 120px;
            height: 120px;
            border: 2px dashed #e1e5e9;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .photo-preview:hover {
            border-color: #3399ff;
            background: rgba(51, 153, 255, 0.05);
        }

        .photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .photo-placeholder {
            text-align: center;
            color: #6c757d;
        }

        .photo-placeholder i {
            color: #dee2e6;
            margin-bottom: 8px;
        }

        .photo-placeholder p {
            font-size: 0.8rem;
            margin: 0;
        }

        .photo-upload-controls {
            flex: 1;
        }

        .photo-upload-controls .form-control {
            margin-bottom: 8px;
        }

        @media (max-width: 768px) {
            .photo-upload-container {
                flex-direction: column;
                align-items: center;
            }

            .photo-preview {
                width: 100px;
                height: 100px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-6 col-xl-6">
                <div class="register-container">
                    <!-- Header -->
                    <div class="register-container">
        <div class="register-header">
            <div class="logo">
                <img src="{{ asset('assets/img/logo_white.png') }}" alt="EVC Logo">
            </div>
            <h2>Rejoignez EVC Formation</h2>
            <p>Créez votre compte pour accéder à nos formations professionnelles</p>
        </div>
                    </div>

                    <!-- Form -->
                    <div class="register-form">
                        @if(session('error'))
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ session('error') }}
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" id="registerForm" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="prenom" class="form-label">
                                            <i class="fas fa-user me-1"></i>
                                            Prénom
                                        </label>
                                        <input type="text" class="form-control" id="prenom" name="prenom"
                                               value="{{ old('prenom') }}" required>
                                        @error('prenom')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nom" class="form-label">
                                            <i class="fas fa-user me-1"></i>
                                            Nom
                                        </label>
                                        <input type="text" class="form-control" id="nom" name="nom"
                                               value="{{ old('nom') }}" required>
                                        @error('nom')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>
                                    Adresse email
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-at"></i>
                                    </span>
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="{{ old('email') }}" required>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="telephone" class="form-label">
                                    <i class="fas fa-phone me-1"></i>
                                    Numéro de téléphone
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input type="tel" class="form-control" id="telephone" name="telephone"
                                           value="{{ old('telephone') }}" required>
                                </div>
                                @error('telephone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pays" class="form-label">
                                            <i class="fas fa-globe me-1"></i>
                                            Pays
                                        </label>
                                        <select class="form-control" id="pays" name="pays" required>
                                            <option value="">Chargement des pays...</option>
                                        </select>
                                        @error('pays')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ville" class="form-label">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            Ville
                                        </label>
                                        <input type="text" class="form-control" id="ville" name="ville"
                                               value="{{ old('ville') }}" required
                                               placeholder="Votre ville de résidence">
                                        @error('ville')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="niveau" class="form-label">
                                    <i class="fas fa-chart-line me-1"></i>
                                    Niveau actuel
                                </label>
                                <select class="form-control" id="niveau" name="niveau" required>
                                    <option value="">Évaluez votre niveau</option>
                                    <option value="debutant" {{ old('niveau') == 'debutant' ? 'selected' : '' }}>
                                        <i class="fas fa-seedling me-2"></i>Débutant - Je commence tout juste
                                    </option>
                                    <option value="intermediaire" {{ old('niveau') == 'intermediaire' ? 'selected' : '' }}>
                                        <i class="fas fa-chart-bar me-2"></i>Intermédiaire - J'ai quelques bases
                                    </option>
                                    <option value="perfectionnement" {{ old('niveau') == 'perfectionnement' ? 'selected' : '' }}>
                                        <i class="fas fa-trophy me-2"></i>Perfectionnement - Je veux me perfectionner
                                    </option>
                                </select>
                                @error('niveau')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="photo" class="form-label">
                                    <i class="fas fa-camera me-1"></i>
                                    Photo de profil (optionnel)
                                </label>
                                <div class="photo-upload-container">
                                    <div class="photo-preview" id="photoPreview">
                                        <div class="photo-placeholder">
                                            <i class="fas fa-user fa-3x"></i>
                                            <p class="mt-2">Aucune photo</p>
                                        </div>
                                    </div>
                                    <div class="photo-upload-controls">
                                        <input type="file" class="form-control" id="photo" name="photo"
                                               accept="image/*" id="photoInput">
                                        <small class="form-text text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Formats acceptés: JPG, PNG, GIF (Max: 2MB)
                                        </small>
                                    </div>
                                </div>
                                @error('photo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="form-label">
                                            <i class="fas fa-lock me-1"></i>
                                            Mot de passe
                                        </label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control" id="password" name="password" required>
                                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                                <i class="fas fa-eye" id="password-eye"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation" class="form-label">
                                            <i class="fas fa-lock me-1"></i>
                                            Confirmer le mot de passe
                                        </label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                                <i class="fas fa-eye" id="password_confirmation-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="formation" class="form-label">
                                    <i class="fas fa-graduation-cap me-1"></i>
                                    Formation souhaitée
                                </label>
                                <select class="form-control" id="formation" name="formation" required>
                                    <option value="">Choisissez votre formation</option>
                                    <option value="design_graphique" {{ old('formation') == 'design_graphique' ? 'selected' : '' }}>
                                        <i class="fas fa-palette me-2"></i>Design Graphique
                                    </option>
                                    <option value="community_management" {{ old('formation') == 'community_management' ? 'selected' : '' }}>
                                        <i class="fas fa-users me-2"></i>Community Management
                                    </option>
                                    <option value="intelligence_artificielle" {{ old('formation') == 'intelligence_artificielle' ? 'selected' : '' }}>
                                        <i class="fas fa-robot me-2"></i>Intelligence Artificielle
                                    </option>
                                    <option value="gestion_informatique" {{ old('formation') == 'gestion_informatique' ? 'selected' : '' }}>
                                        <i class="fas fa-server me-2"></i>Gestion Informatique
                                    </option>
                                </select>
                                @error('formation')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    J'accepte les <a href="#" class="text-decoration-none">conditions d'utilisation</a>
                                    et la <a href="#" class="text-decoration-none">politique de confidentialité</a>
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-register" id="registerBtn">
                                <span class="btn-text">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Créer mon compte
                                </span>
                                <span class="spinner-border spinner-border-sm d-none" role="status">
                                    <span class="visually-hidden">Chargement...</span>
                                </span>
                            </button>
                        </form>
                    </div>

                    <!-- Footer -->
                    <div class="register-footer">
                        <p>
                            Vous avez déjà un compte ?
                            <a href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>
                                Se connecter
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eye = document.getElementById(fieldId + '-eye');

            if (field.type === 'password') {
                field.type = 'text';
                eye.classList.remove('fa-eye');
                eye.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
            }
        }

        // Form submission simplifié - laisser le navigateur gérer la soumission
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('registerBtn');
            const btnText = btn.querySelector('.btn-text');
            const spinner = btn.querySelector('.spinner-border');

            // Afficher l'état de chargement
            if (btn && btnText && spinner) {
                btn.classList.add('loading');
                btnText.classList.add('d-none');
                spinner.classList.remove('d-none');
            }
            
            // Laisser le formulaire se soumettre normalement
            // PAS de e.preventDefault() - soumission native du navigateur
        });

        // Real-time form validation
        const form = document.getElementById('registerForm');
        const inputs = form.querySelectorAll('input, select');

        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
        });

        function validateField(field) {
            const value = field.value.trim();

            // Remove existing validation classes
            field.classList.remove('is-valid', 'is-invalid');

            if (field.hasAttribute('required') && !value) {
                field.classList.add('is-invalid');
                return false;
            }

            // Email validation
            if (field.type === 'email' && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    field.classList.add('is-invalid');
                    return false;
                }
            }

            // Password confirmation
            if (field.name === 'password_confirmation' && value) {
                const password = document.getElementById('password').value;
                if (value !== password) {
                    field.classList.add('is-invalid');
                    return false;
                }
            }

            if (value) {
                field.classList.add('is-valid');
            }

            return true;
        }

        // Photo preview function avec event listener (évite CSP)
        document.addEventListener('DOMContentLoaded', function() {
            const photoInput = document.getElementById('photo');
            if (photoInput) {
                photoInput.addEventListener('change', function() {
                    const preview = document.getElementById('photoPreview');
                    const input = this;

                    if (input.files && input.files[0]) {
                        const file = input.files[0];

                        // Vérifier la taille du fichier (2MB max)
                        if (file.size > 2 * 1024 * 1024) {
                            alert('La taille du fichier ne doit pas dépasser 2MB.');
                            input.value = '';
                            return;
                        }

                        // Vérifier le type de fichier
                        if (!file.type.match('image.*')) {
                            alert('Veuillez sélectionner un fichier image valide.');
                            input.value = '';
                            return;
                        }

                        const reader = new FileReader();

                        reader.onload = function(e) {
                            preview.innerHTML = `<img src="${e.target.result}" alt="Photo de profil">`;
                            preview.style.border = '2px solid #28a745';
                        };

                        reader.readAsDataURL(file);
                    } else {
                        // Remettre le placeholder si aucun fichier
                        preview.innerHTML = `
                            <div class="photo-placeholder">
                                <i class="fas fa-camera"></i>
                                <span>Cliquez pour ajouter une photo</span>
                            </div>
                        `;
                        preview.style.border = '2px dashed #ddd';
                    }
                });
            }
        });

        // Liste complète des pays du monde
        const countries = [
            'Afghanistan', 'Afrique du Sud', 'Albanie', 'Algérie', 'Allemagne', 'Andorre', 'Angola',
            'Antigua-et-Barbuda', 'Arabie saoudite', 'Argentine', 'Arménie', 'Australie', 'Autriche',
            'Azerbaïdjan', 'Bahamas', 'Bahreïn', 'Bangladesh', 'Barbade', 'Biélorussie', 'Belgique',
            'Belize', 'Bénin', 'Bhoutan', 'Bolivie', 'Bosnie-Herzégovine', 'Botswana', 'Brésil',
            'Brunei', 'Bulgarie', 'Burkina Faso', 'Burundi', 'Cambodge', 'Cameroun', 'Canada',
            'Cap-Vert', 'Chili', 'Chine', 'Chypre', 'Colombie', 'Comores', 'Congo',
            'République démocratique du Congo', 'Corée du Nord', 'Corée du Sud', 'Costa Rica',
            'Côte d\'Ivoire', 'Croatie', 'Cuba', 'Danemark', 'Djibouti', 'Dominique',
            'République dominicaine', 'Égypte', 'Émirats arabes unis', 'Équateur', 'Érythrée',
            'Espagne', 'Estonie', 'États-Unis', 'Éthiopie', 'Fidji', 'Finlande', 'France',
            'Gabon', 'Gambie', 'Géorgie', 'Ghana', 'Grèce', 'Grenade', 'Guatemala', 'Guinée',
            'Guinée-Bissau', 'Guinée équatoriale', 'Guyana', 'Haïti', 'Honduras', 'Hongrie',
            'Inde', 'Indonésie', 'Irak', 'Iran', 'Irlande', 'Islande', 'Israël', 'Italie',
            'Jamaïque', 'Japon', 'Jordanie', 'Kazakhstan', 'Kenya', 'Kirghizistan', 'Kiribati',
            'Koweït', 'Laos', 'Lesotho', 'Lettonie', 'Liban', 'Libéria', 'Libye',
            'Liechtenstein', 'Lituanie', 'Luxembourg', 'Macédoine du Nord', 'Madagascar',
            'Malaisie', 'Malawi', 'Maldives', 'Mali', 'Malte', 'Maroc', 'Marshall', 'Maurice',
            'Mauritanie', 'Mexique', 'Micronésie', 'Moldavie', 'Monaco', 'Mongolie',
            'Monténégro', 'Mozambique', 'Myanmar', 'Namibie', 'Nauru', 'Népal', 'Nicaragua',
            'Niger', 'Nigéria', 'Norvège', 'Nouvelle-Zélande', 'Oman', 'Ouganda', 'Ouzbékistan',
            'Pakistan', 'Palaos', 'Panama', 'Papouasie-Nouvelle-Guinée', 'Paraguay', 'Pays-Bas',
            'Pérou', 'Philippines', 'Pologne', 'Portugal', 'Qatar', 'République centrafricaine',
            'République tchèque', 'Roumanie', 'Royaume-Uni', 'Russie', 'Rwanda',
            'Saint-Christophe-et-Niévès', 'Saint-Marin', 'Saint-Vincent-et-les-Grenadines',
            'Sainte-Lucie', 'Salomon', 'Salvador', 'Samoa', 'São Tomé-et-Principe', 'Sénégal',
            'Serbie', 'Seychelles', 'Sierra Leone', 'Singapour', 'Slovaquie', 'Slovénie',
            'Somalie', 'Soudan', 'Soudan du Sud', 'Sri Lanka', 'Suède', 'Suisse', 'Suriname',
            'Syrie', 'Tadjikistan', 'Tanzanie', 'Tchad', 'Thaïlande', 'Timor oriental', 'Togo',
            'Tonga', 'Trinité-et-Tobago', 'Tunisie', 'Turkménistan', 'Turquie', 'Tuvalu',
            'Ukraine', 'Uruguay', 'Vanuatu', 'Vatican', 'Venezuela', 'Viêt Nam', 'Yémen',
            'Zambie', 'Zimbabwe'
        ];

        // Charger la liste des pays au chargement de la page
        function loadCountries() {
            const paysSelect = document.getElementById('pays');
            const oldValue = '{{ old("pays") }}';

            // Vider le select et ajouter l'option par défaut
            paysSelect.innerHTML = '<option value="">Choisissez votre pays</option>';

            // Ajouter tous les pays
            countries.forEach(country => {
                const option = document.createElement('option');
                option.value = country;
                option.textContent = country;

                // Sélectionner la valeur précédente si elle existe
                if (oldValue && oldValue === country) {
                    option.selected = true;
                }

                paysSelect.appendChild(option);
            });
        }

        // Charger les pays au chargement de la page
        window.addEventListener('load', loadCountries);

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>
