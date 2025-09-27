<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - EVC Platform</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6.4 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
            background: linear-gradient(135deg, #003366 0%, #3399ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 800px;
            margin: 20px;
        }

        .register-header {
            background: linear-gradient(135deg, #003366, #3399ff);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .logo img {
            height: 80px;
            width: auto;
            margin-bottom: 20px;
        }

        .register-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .register-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .register-form {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #003366;
            margin-bottom: 8px;
        }

        .form-control {
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #3399ff;
            box-shadow: 0 0 0 0.2rem rgba(51, 153, 255, 0.25);
        }

        .btn-register {
            background: linear-gradient(135deg, #3399ff, #003366);
            border: none;
            color: white;
            padding: 15px 30px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(51, 153, 255, 0.3);
        }

        .register-footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e1e5e9;
        }

        .register-footer a {
            color: #3399ff;
            text-decoration: none;
            font-weight: 600;
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .photo-upload {
            border: 2px dashed #e1e5e9;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .photo-upload:hover {
            border-color: #3399ff;
            background: rgba(51, 153, 255, 0.05);
        }

        @media (max-width: 768px) {
            .register-container {
                margin: 10px;
            }
            
            .register-header {
                padding: 30px 20px;
            }
            
            .register-form {
                padding: 30px 20px;
            }
            
            .register-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="register-container">
                    <!-- Header -->
                    <div class="register-header">
                        <div class="logo">
                            <img src="{{ asset('assets/img/logo_white.png') }}" alt="EVC Logo">
                        </div>
                        <h1 class="register-title">Inscription EVC</h1>
                        <p class="register-subtitle">Créez votre compte étudiant</p>
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

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- FORMULAIRE SIMPLIFIÉ - MÊME LOGIQUE QUE DEBUG_FORM.HTML -->
                        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="prenom" class="form-label">
                                            <i class="fas fa-user me-1"></i>
                                            Prénom *
                                        </label>
                                        <input type="text" class="form-control" id="prenom" name="prenom" 
                                               value="{{ old('prenom') }}" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nom" class="form-label">
                                            <i class="fas fa-user me-1"></i>
                                            Nom *
                                        </label>
                                        <input type="text" class="form-control" id="nom" name="nom" 
                                               value="{{ old('nom') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>
                                    Adresse email *
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="telephone" class="form-label">
                                    <i class="fas fa-phone me-1"></i>
                                    Numéro de téléphone *
                                </label>
                                <input type="tel" class="form-control" id="telephone" name="telephone" 
                                       value="{{ old('telephone') }}" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pays" class="form-label">
                                            <i class="fas fa-globe me-1"></i>
                                            Pays *
                                        </label>
                                        <select class="form-control" id="pays" name="pays" required>
                                            <option value="">Choisissez votre pays</option>
                                            <option value="France" {{ old('pays') == 'France' ? 'selected' : '' }}>France</option>
                                            <option value="Belgique" {{ old('pays') == 'Belgique' ? 'selected' : '' }}>Belgique</option>
                                            <option value="Suisse" {{ old('pays') == 'Suisse' ? 'selected' : '' }}>Suisse</option>
                                            <option value="Canada" {{ old('pays') == 'Canada' ? 'selected' : '' }}>Canada</option>
                                            <option value="Maroc" {{ old('pays') == 'Maroc' ? 'selected' : '' }}>Maroc</option>
                                            <option value="Tunisie" {{ old('pays') == 'Tunisie' ? 'selected' : '' }}>Tunisie</option>
                                            <option value="Algérie" {{ old('pays') == 'Algérie' ? 'selected' : '' }}>Algérie</option>
                                            <option value="Sénégal" {{ old('pays') == 'Sénégal' ? 'selected' : '' }}>Sénégal</option>
                                            <option value="Côte d'Ivoire" {{ old('pays') == "Côte d'Ivoire" ? 'selected' : '' }}>Côte d'Ivoire</option>
                                            <option value="Cameroun" {{ old('pays') == 'Cameroun' ? 'selected' : '' }}>Cameroun</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ville" class="form-label">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            Ville *
                                        </label>
                                        <input type="text" class="form-control" id="ville" name="ville" 
                                               value="{{ old('ville') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="niveau" class="form-label">
                                            <i class="fas fa-chart-line me-1"></i>
                                            Niveau *
                                        </label>
                                        <select class="form-control" id="niveau" name="niveau" required>
                                            <option value="">Choisissez votre niveau</option>
                                            <option value="debutant" {{ old('niveau') == 'debutant' ? 'selected' : '' }}>Débutant</option>
                                            <option value="intermediaire" {{ old('niveau') == 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                                            <option value="perfectionnement" {{ old('niveau') == 'perfectionnement' ? 'selected' : '' }}>Perfectionnement</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="formation" class="form-label">
                                            <i class="fas fa-graduation-cap me-1"></i>
                                            Formation souhaitée *
                                        </label>
                                        <select class="form-control" id="formation" name="formation" required>
                                            <option value="">Choisissez votre formation</option>
                                            <option value="design_graphique" {{ old('formation') == 'design_graphique' ? 'selected' : '' }}>Design Graphique</option>
                                            <option value="community_management" {{ old('formation') == 'community_management' ? 'selected' : '' }}>Community Management</option>
                                            <option value="intelligence_artificielle" {{ old('formation') == 'intelligence_artificielle' ? 'selected' : '' }}>Intelligence Artificielle</option>
                                            <option value="gestion_informatique" {{ old('formation') == 'gestion_informatique' ? 'selected' : '' }}>Gestion Informatique</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="photo" class="form-label">
                                    <i class="fas fa-camera me-1"></i>
                                    Photo de profil (optionnel)
                                </label>
                                <div class="photo-upload">
                                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                    <small class="text-muted">Formats acceptés: JPG, PNG, GIF (Max: 2MB)</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="form-label">
                                            <i class="fas fa-lock me-1"></i>
                                            Mot de passe *
                                        </label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation" class="form-label">
                                            <i class="fas fa-lock me-1"></i>
                                            Confirmer le mot de passe *
                                        </label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" name="terms" value="1" required>
                                    <label class="form-check-label" for="terms">
                                        J'accepte les <a href="#" target="_blank">conditions d'utilisation</a> 
                                        et la <a href="#" target="_blank">politique de confidentialité</a> *
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn-register">
                                <i class="fas fa-user-plus me-2"></i>
                                Créer mon compte
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
</body>
</html>
