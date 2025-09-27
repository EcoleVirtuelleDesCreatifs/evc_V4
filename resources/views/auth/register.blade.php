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
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            width: 100%;
            position: relative;
            z-index: 10;
            animation: slideInUp 0.8s ease-out;
            margin: auto;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .register-header {
            background: linear-gradient(135deg, #003366, #3399ff);
            color: white;
            text-align: center;
            padding: 30px 20px;
            position: relative;
        }

        .register-header::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #ff6633, #FF9900, #ff6633);
            animation: shimmer 2s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        .logo {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .register-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 300;
        }

        .register-form {
            padding: 40px 30px;
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

        .loading-spinner {
            display: none;
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .row {
            margin: 0 -10px;
        }

        .col-md-6 {
            padding: 0 10px;
        }

        @media (max-width: 768px) {
            .register-container {
                margin: 10px;
            }

            .register-header,
            .register-form {
                padding: 30px 20px;
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
                    <div class="register-header">
                        <div class="logo">
                            <img src="{{ asset('assets/img/logo_white.png') }}" alt="EVC Logo" style="height: 100px; width: auto; margin-right: 10px;">
                        </div>
                        <div class="register-subtitle">
                            Créez votre compte étudiant
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

                        <div id="alert-container"></div>

                        <form id="registerForm" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Prénom *</label>
                            <input type="text" class="form-control" name="prenom" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Nom *</label>
                            <input type="text" class="form-control" name="nom" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Téléphone *</label>
                            <input type="tel" class="form-control" name="telephone" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Pays *</label>
                            <select class="form-select" name="pays" id="pays" required>
                                <option value="">Sélectionnez votre pays</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Ville *</label>
                            <input type="text" class="form-control" name="ville" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Formation souhaitée *</label>
                            <select class="form-select" name="formation" required>
                                <option value="">Choisissez votre formation</option>
                                <option value="design_graphique">Design Graphique</option>
                                <option value="community_management">Community Management</option>
                                <option value="intelligence_artificielle">Intelligence Artificielle</option>
                                <option value="gestion_informatique">Gestion Informatique</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Niveau *</label>
                            <select class="form-select" name="niveau" required>
                                <option value="">Sélectionnez votre niveau</option>
                                <option value="debutant">Débutant - Aucune expérience préalable</option>
                                <option value="intermediaire">Intermédiaire - Quelques notions de base</option>
                                <option value="perfectionnement">Perfectionnement - Expérience confirmée</option>
                            </select>

                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Photo de profil *</label>
                    <div class="photo-upload" onclick="document.getElementById('photo').click()">
                        <i class="fas fa-camera fa-2x text-primary mb-2"></i>
                        <p>Cliquez pour ajouter une photo</p>
                        <small class="text-muted">Formats acceptés: JPG, PNG, GIF (max 2MB) - Obligatoire</small>
                        <img id="photoPreview" class="photo-preview" alt="Aperçu">
                    </div>
                    <input type="file" id="photo" name="photo" accept="image/*" style="display: none;" required>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Mot de passe *</label>
                            <input type="password" class="form-control" name="password" required minlength="6">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Confirmer le mot de passe *</label>
                            <input type="password" class="form-control" name="password_confirmation" required minlength="6">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                        <label class="form-check-label" for="terms">
                            J'accepte les <a href="#" class="text-primary">conditions d'utilisation</a> et la <a href="#" class="text-primary">politique de confidentialité</a> *
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-register">
                    <span class="btn-text">S'inscrire</span>
                    <span class="loading-spinner">
                        <i class="fas fa-spinner fa-spin"></i> Inscription en cours...
                    </span>
                </button>
            </form>

                        <div class="text-center mt-4">
                            <p>Vous avez déjà un compte ? <a href="{{ route('login') }}" class="text-primary">Se connecter</a></p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="register-footer">
                        <p>En vous inscrivant, vous acceptez nos <a href="#">Conditions d'utilisation</a> et notre <a href="#">Politique de confidentialité</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Liste des pays
        const countries = [
            'Afghanistan', 'Afrique du Sud', 'Albanie', 'Algérie', 'Allemagne', 'Andorre', 'Angola', 'Antigua-et-Barbuda',
            'Arabie saoudite', 'Argentine', 'Arménie', 'Australie', 'Autriche', 'Azerbaïdjan', 'Bahamas', 'Bahreïn',
            'Bangladesh', 'Barbade', 'Belgique', 'Belize', 'Bénin', 'Bhoutan', 'Biélorussie', 'Birmanie', 'Bolivie',
            'Bosnie-Herzégovine', 'Botswana', 'Brésil', 'Brunei', 'Bulgarie', 'Burkina Faso', 'Burundi', 'Cambodge',
            'Cameroun', 'Canada', 'Cap-Vert', 'Centrafrique', 'Chili', 'Chine', 'Chypre', 'Colombie', 'Comores',
            'Congo', 'Congo démocratique', 'Corée du Nord', 'Corée du Sud', 'Costa Rica', 'Côte d\'Ivoire', 'Croatie',
            'Cuba', 'Danemark', 'Djibouti', 'Dominique', 'Égypte', 'Émirats arabes unis', 'Équateur', 'Érythrée',
            'Espagne', 'Estonie', 'États-Unis', 'Éthiopie', 'Fidji', 'Finlande', 'France', 'Gabon', 'Gambie',
            'Géorgie', 'Ghana', 'Grèce', 'Grenade', 'Guatemala', 'Guinée', 'Guinée-Bissau', 'Guinée équatoriale',
            'Guyana', 'Haïti', 'Honduras', 'Hongrie', 'Îles Cook', 'Îles Marshall', 'Inde', 'Indonésie', 'Irak',
            'Iran', 'Irlande', 'Islande', 'Israël', 'Italie', 'Jamaïque', 'Japon', 'Jordanie', 'Kazakhstan', 'Kenya',
            'Kirghizistan', 'Kiribati', 'Koweït', 'Laos', 'Lesotho', 'Lettonie', 'Liban', 'Liberia', 'Libye',
            'Liechtenstein', 'Lituanie', 'Luxembourg', 'Macédoine du Nord', 'Madagascar', 'Malaisie', 'Malawi',
            'Maldives', 'Mali', 'Malte', 'Maroc', 'Maurice', 'Mauritanie', 'Mexique', 'Micronésie', 'Moldavie',
            'Monaco', 'Mongolie', 'Monténégro', 'Mozambique', 'Namibie', 'Nauru', 'Népal', 'Nicaragua', 'Niger',
            'Nigeria', 'Niué', 'Norvège', 'Nouvelle-Zélande', 'Oman', 'Ouganda', 'Ouzbékistan', 'Pakistan', 'Palaos',
            'Palestine', 'Panama', 'Papouasie-Nouvelle-Guinée', 'Paraguay', 'Pays-Bas', 'Pérou', 'Philippines',
            'Pologne', 'Portugal', 'Qatar', 'République dominicaine', 'République tchèque', 'Roumanie', 'Royaume-Uni',
            'Russie', 'Rwanda', 'Saint-Christophe-et-Niévès', 'Saint-Marin', 'Saint-Vincent-et-les-Grenadines',
            'Sainte-Lucie', 'Salomon', 'Salvador', 'Samoa', 'São Tomé-et-Principe', 'Sénégal', 'Serbie', 'Seychelles',
            'Sierra Leone', 'Singapour', 'Slovaquie', 'Slovénie', 'Somalie', 'Soudan', 'Soudan du Sud', 'Sri Lanka',
            'Suède', 'Suisse', 'Suriname', 'Swaziland', 'Syrie', 'Tadjikistan', 'Tanzanie', 'Tchad', 'Thaïlande',
            'Timor oriental', 'Togo', 'Tonga', 'Trinité-et-Tobago', 'Tunisie', 'Turkménistan', 'Turquie', 'Tuvalu',
            'Ukraine', 'Uruguay', 'Vanuatu', 'Vatican', 'Venezuela', 'Viêt Nam', 'Yémen', 'Zambie', 'Zimbabwe'
        ];

        // Charger les pays
        const paysSelect = document.getElementById('pays');
        countries.forEach(country => {
            const option = document.createElement('option');
            option.value = country;
            option.textContent = country;
            paysSelect.appendChild(option);
        });

        // Prévisualisation de la photo
        document.getElementById('photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('photoPreview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // Gestion du formulaire
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = document.querySelector('.btn-register');
            const btnText = document.querySelector('.btn-text');
            const loadingSpinner = document.querySelector('.loading-spinner');
            const alertContainer = document.getElementById('alert-container');

            // Afficher le loading
            submitBtn.disabled = true;
            btnText.style.display = 'none';
            loadingSpinner.style.display = 'inline';

            // Vérifier les mots de passe
            const password = document.querySelector('input[name="password"]').value;
            const passwordConfirmation = document.querySelector('input[name="password_confirmation"]').value;

            if (password !== passwordConfirmation) {
                showAlert('Les mots de passe ne correspondent pas.', 'danger');
                resetButton();
                return;
            }

            // Préparer les données
            const formData = new FormData(this);

            try {
                const response = await fetch('{{ url("/inscription.php") }}', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showAlert(result.message, 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route("login") }}';
                    }, 2000);
                } else {
                    showAlert(result.message, 'danger');
                    resetButton();
                }
            } catch (error) {
                showAlert('Erreur de connexion. Veuillez réessayer.', 'danger');
                resetButton();
            }
        });

        function showAlert(message, type) {
            const alertContainer = document.getElementById('alert-container');
            alertContainer.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
        }

        function resetButton() {
            const submitBtn = document.querySelector('.btn-register');
            const btnText = document.querySelector('.btn-text');
            const loadingSpinner = document.querySelector('.loading-spinner');

            submitBtn.disabled = false;
            btnText.style.display = 'inline';
            loadingSpinner.style.display = 'none';
        }
    </script>
</body>
</html>
