<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation d'inscription - École Virtuelle des Créatifs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #0b1e3a; /* bleu foncé */
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .confirmation-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .confirmation-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #0e2a54 0%, #13386e 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .welcome-icon {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .form-floating {
            margin-bottom: 1rem;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-confirm {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }
        
        .student-info {
            background: linear-gradient(135deg, #e3f2fd, #f3e5f5);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .formation-badge {
            display: inline-block;
            background: linear-gradient(135deg, #0e2a54, #13386e);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            margin: 0.2rem;
        }
        
        .password-strength {
            height: 5px;
            background: #e9ecef;
            border-radius: 3px;
            margin-top: 0.5rem;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 3px;
        }
        
        .strength-weak { background: #dc3545; width: 25%; }
        .strength-medium { background: #ffc107; width: 50%; }
        .strength-good { background: #fd7e14; width: 75%; }
        .strength-strong { background: #28a745; width: 100%; }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <div class="confirmation-card">
            <!-- Header -->
            <div class="card-header">
                <div class="welcome-icon">
                    <i class="fas fa-graduation-cap fa-2x"></i>
                </div>
                <h2 class="mb-2">Bienvenue à l'EVC !</h2>
                <p class="mb-0">Confirmez votre inscription pour commencer votre parcours</p>
            </div>
            
            <!-- Corps -->
            <div class="card-body p-4">
                <!-- Informations de l'étudiant -->
                <div class="student-info">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-user-circle me-2"></i>Vos informations
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Nom :</strong> {{ $student->first_name }} {{ $student->last_name }}</p>
                            <p class="mb-2"><strong>Email :</strong> {{ $student->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Téléphone :</strong> {{ $student->phone }}</p>
                            <p class="mb-2"><strong>Ville :</strong> {{ $student->city ?? 'Non renseignée' }}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <strong>Formation :</strong>
                        <span class="formation-badge">
                            @php
                                $formationNames = [
                                    'design_graphique' => 'Design Graphique',
                                    'community_management' => 'Community Management',
                                    'intelligence_artificielle' => 'Intelligence Artificielle',
                                    'gestion_informatique' => 'Gestion Informatique'
                                ];
                            @endphp
                            {{ $formationNames[$student->formation_souhaitee] ?? $student->formation_souhaitee }}
                        </span>
                    </div>
                </div>

                <!-- Messages d'erreur/succès -->
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Formulaire de confirmation -->
                <form action="{{ route('student.confirm-registration.process', $token) }}" method="POST">
                    @csrf
                    
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-key me-2"></i>Créez votre mot de passe
                    </h5>
                    
                    <!-- Mot de passe -->
                    <div class="form-floating">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" placeholder="Mot de passe" required
                               onkeyup="checkPasswordStrength(this.value)">
                        <label for="password">
                            <i class="fas fa-lock me-2"></i>Nouveau mot de passe
                        </label>
                        <div class="password-strength">
                            <div class="password-strength-bar" id="passwordStrengthBar"></div>
                        </div>
                        <small class="text-muted">Minimum 8 caractères</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Confirmation mot de passe -->
                    <div class="form-floating">
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                               id="password_confirmation" name="password_confirmation" placeholder="Confirmer le mot de passe" required>
                        <label for="password_confirmation">
                            <i class="fas fa-lock me-2"></i>Confirmer le mot de passe
                        </label>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <h5 class="text-primary mb-3 mt-4">
                        <i class="fas fa-user-edit me-2"></i>Complétez votre profil
                    </h5>
                    
                    <!-- Biographie -->
                    <div class="form-floating">
                        <textarea class="form-control @error('biography') is-invalid @enderror" 
                                  id="biography" name="biography" placeholder="Parlez-nous de vous" 
                                  style="height: 100px;">{{ old('biography') }}</textarea>
                        <label for="biography">
                            <i class="fas fa-user me-2"></i>Parlez-nous de vous (optionnel)
                        </label>
                        @error('biography')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Attentes -->
                    <div class="form-floating">
                        <textarea class="form-control @error('expectations') is-invalid @enderror" 
                                  id="expectations" name="expectations" placeholder="Vos attentes" 
                                  style="height: 100px;">{{ old('expectations') }}</textarea>
                        <label for="expectations">
                            <i class="fas fa-star me-2"></i>Vos attentes de cette formation (optionnel)
                        </label>
                        @error('expectations')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Conditions -->
                    <div class="form-check mb-3">
                        <input class="form-check-input @error('accepte_conditions') is-invalid @enderror" 
                               type="checkbox" id="accepte_conditions" name="accepte_conditions" 
                               {{ old('accepte_conditions') ? 'checked' : '' }} required>
                        <label class="form-check-label" for="accepte_conditions">
                            J'accepte les <a href="#" class="text-primary">conditions d'utilisation</a> et la 
                            <a href="#" class="text-primary">politique de confidentialité</a>
                        </label>
                        @error('accepte_conditions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Newsletter -->
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="newsletter_consent" 
                               name="newsletter_consent" {{ old('newsletter_consent') ? 'checked' : '' }}>
                        <label class="form-check-label" for="newsletter_consent">
                            Je souhaite recevoir les actualités et offres spéciales par email
                        </label>
                    </div>
                    
                    <!-- Bouton de confirmation -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-confirm btn-lg">
                            <i class="fas fa-check me-2"></i>Confirmer mon inscription
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function checkPasswordStrength(password) {
            const strengthBar = document.getElementById('passwordStrengthBar');
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            strengthBar.className = 'password-strength-bar';
            
            switch(strength) {
                case 0:
                case 1:
                    strengthBar.classList.add('strength-weak');
                    break;
                case 2:
                case 3:
                    strengthBar.classList.add('strength-medium');
                    break;
                case 4:
                    strengthBar.classList.add('strength-good');
                    break;
                case 5:
                    strengthBar.classList.add('strength-strong');
                    break;
            }
        }
    </script>
</body>
</html>
