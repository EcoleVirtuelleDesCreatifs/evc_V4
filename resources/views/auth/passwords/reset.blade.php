<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe - EVC 2024</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #003366;
            --secondary-color: #3399ff;
            --accent-color: #ff6633;
            --warning-color: #FF9900;
            --success-color: #28a745;
            --danger-color: #dc3545;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #003366 0%, #3399ff 50%, #ff6633 100%);
            min-height: 100vh;
            padding: 20px 0;
        }

        .reset-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 550px;
            margin: 50px auto;
            animation: slideInUp 0.8s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .reset-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .reset-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .reset-body {
            padding: 2rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(51, 153, 255, 0.25);
            transform: translateY(-2px);
        }

        .input-group-text {
            background: var(--secondary-color);
            border: 2px solid var(--secondary-color);
            color: white;
            border-radius: 10px 0 0 10px;
        }

        .btn-reset {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 51, 102, 0.3);
        }

        .btn-back {
            background: transparent;
            border: 2px solid var(--secondary-color);
            color: var(--secondary-color);
            border-radius: 10px;
            padding: 0.5rem 1.5rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }

        .password-strength {
            margin-top: 0.5rem;
        }

        .password-strength-bar {
            height: 4px;
            border-radius: 2px;
            background: #e9ecef;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .password-strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak { background: var(--danger-color); width: 25%; }
        .strength-fair { background: var(--warning-color); width: 50%; }
        .strength-good { background: var(--secondary-color); width: 75%; }
        .strength-strong { background: var(--success-color); width: 100%; }

        .password-requirements {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .requirement {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .requirement:last-child {
            margin-bottom: 0;
        }

        .requirement-icon {
            width: 20px;
            margin-right: 0.5rem;
        }

        .requirement.valid {
            color: var(--success-color);
        }

        .requirement.invalid {
            color: #6c757d;
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
            z-index: 10;
        }

        .password-input-wrapper {
            position: relative;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-12">
                <div class="reset-container">
                    <!-- Header -->
                    <div class="reset-header">
                        <div class="reset-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h1 class="h3 mb-0">Nouveau mot de passe</h1>
                        <p class="mb-0 mt-2 opacity-75">Créez un mot de passe sécurisé</p>
                    </div>

                    <!-- Body -->
                    <div class="reset-body">
                        {{-- Success Message --}}
                        @if(session('success'))
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- Error Message --}}
                        @if(session('error'))
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ session('error') }}
                            </div>
                        @endif

                        {{-- Validation Errors --}}
                        @if($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('password.update') }}" method="POST" id="resetForm">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="fas fa-envelope me-1"></i>
                                    Adresse email
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ $email ?? old('email') }}" 
                                           placeholder="votre.email@exemple.com" 
                                           required readonly>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="fas fa-lock me-1"></i>
                                    Nouveau mot de passe
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <div class="password-input-wrapper flex-grow-1">
                                        <input type="password" class="form-control" id="password" name="password" 
                                               placeholder="Entrez votre nouveau mot de passe" 
                                               required>
                                        <button type="button" class="password-toggle" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Password Strength Indicator -->
                                <div class="password-strength">
                                    <div class="password-strength-bar">
                                        <div class="password-strength-fill" id="strengthBar"></div>
                                    </div>
                                    <small id="strengthText" class="text-muted">Entrez un mot de passe</small>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-semibold">
                                    <i class="fas fa-lock me-1"></i>
                                    Confirmer le mot de passe
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <div class="password-input-wrapper flex-grow-1">
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" 
                                               placeholder="Confirmez votre nouveau mot de passe" 
                                               required>
                                        <button type="button" class="password-toggle" id="togglePasswordConfirm">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Password Requirements -->
                            <div class="password-requirements">
                                <h6 class="fw-semibold mb-3">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    Exigences du mot de passe
                                </h6>
                                <div class="requirement" id="req-length">
                                    <i class="fas fa-times requirement-icon"></i>
                                    Au moins 8 caractères
                                </div>
                                <div class="requirement" id="req-uppercase">
                                    <i class="fas fa-times requirement-icon"></i>
                                    Au moins une lettre majuscule
                                </div>
                                <div class="requirement" id="req-lowercase">
                                    <i class="fas fa-times requirement-icon"></i>
                                    Au moins une lettre minuscule
                                </div>
                                <div class="requirement" id="req-number">
                                    <i class="fas fa-times requirement-icon"></i>
                                    Au moins un chiffre
                                </div>
                                <div class="requirement" id="req-special">
                                    <i class="fas fa-times requirement-icon"></i>
                                    Au moins un caractère spécial (@$!%*?&)
                                </div>
                            </div>

                            <button type="submit" class="btn btn-reset mb-3 mt-4" id="resetBtn" disabled>
                                <span id="resetText">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    Réinitialiser le mot de passe
                                </span>
                                <div class="loading-spinner" id="loadingSpinner"></div>
                            </button>
                        </form>

                        <div class="text-center">
                            <a href="{{ route('login') }}" class="btn-back">
                                <i class="fas fa-arrow-left me-2"></i>
                                Retour à la connexion
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Password visibility toggles
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
            const password = document.getElementById('password_confirmation');
            const icon = this.querySelector('i');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Password strength checker
        function checkPasswordStrength(password) {
            let score = 0;
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[@$!%*?&]/.test(password)
            };

            // Update requirement indicators
            Object.keys(requirements).forEach(req => {
                const element = document.getElementById(`req-${req}`);
                const icon = element.querySelector('i');
                
                if (requirements[req]) {
                    element.classList.add('valid');
                    element.classList.remove('invalid');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-check');
                    score++;
                } else {
                    element.classList.remove('valid');
                    element.classList.add('invalid');
                    icon.classList.remove('fa-check');
                    icon.classList.add('fa-times');
                }
            });

            return { score, requirements };
        }

        function updatePasswordStrength(password) {
            const { score, requirements } = checkPasswordStrength(password);
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            const submitBtn = document.getElementById('resetBtn');

            // Remove all strength classes
            strengthBar.className = 'password-strength-fill';

            if (password.length === 0) {
                strengthText.textContent = 'Entrez un mot de passe';
                strengthText.className = 'text-muted';
            } else if (score < 3) {
                strengthBar.classList.add('strength-weak');
                strengthText.textContent = 'Mot de passe faible';
                strengthText.className = 'text-danger';
            } else if (score < 4) {
                strengthBar.classList.add('strength-fair');
                strengthText.textContent = 'Mot de passe moyen';
                strengthText.className = 'text-warning';
            } else if (score < 5) {
                strengthBar.classList.add('strength-good');
                strengthText.textContent = 'Bon mot de passe';
                strengthText.className = 'text-info';
            } else {
                strengthBar.classList.add('strength-strong');
                strengthText.textContent = 'Mot de passe fort';
                strengthText.className = 'text-success';
            }

            // Enable/disable submit button
            const passwordConfirm = document.getElementById('password_confirmation').value;
            const allRequirementsMet = Object.values(requirements).every(req => req);
            const passwordsMatch = password === passwordConfirm && password.length > 0;
            
            submitBtn.disabled = !(allRequirementsMet && passwordsMatch);
        }

        // Password input event listeners
        document.getElementById('password').addEventListener('input', function() {
            updatePasswordStrength(this.value);
        });

        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            updatePasswordStrength(password);
        });

        // Form submission with loading state
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            const resetBtn = document.getElementById('resetBtn');
            const resetText = document.getElementById('resetText');
            const loadingSpinner = document.getElementById('loadingSpinner');

            // Show loading state
            resetText.style.display = 'none';
            loadingSpinner.style.display = 'inline-block';
            resetBtn.disabled = true;
        });

        // Input animations
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                if (this.closest('.input-group')) {
                    this.closest('.input-group').style.transform = 'translateY(-2px)';
                }
            });

            input.addEventListener('blur', function() {
                if (this.closest('.input-group')) {
                    this.closest('.input-group').style.transform = 'translateY(0)';
                }
            });
        });
    </script>
</body>
</html>
