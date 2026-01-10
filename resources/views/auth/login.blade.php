<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - EVC 2024 Formation Infographie</title>

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
            --dark-color: #2c3e50;
        }

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
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%"><stop offset="0%" stop-color="rgba(255,255,255,0.1)"/><stop offset="100%" stop-color="rgba(255,255,255,0)"/></radialGradient></defs><circle cx="200" cy="200" r="100" fill="url(%23a)"><animate attributeName="cx" values="200;800;200" dur="20s" repeatCount="indefinite"/></circle><circle cx="800" cy="300" r="150" fill="url(%23a)"><animate attributeName="cy" values="300;700;300" dur="25s" repeatCount="indefinite"/></circle><circle cx="400" cy="600" r="80" fill="url(%23a)"><animate attributeName="r" values="80;120;80" dur="15s" repeatCount="indefinite"/></circle></svg>');
            opacity: 0.3;
            animation: float 30s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .login-container {
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
                transform: translateY(50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .logo {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .logo a:hover img {
            transform: scale(1.1);
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .login-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .login-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            font-weight: 300;
        }

        .login-body {
            padding: 2.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(51, 153, 255, 0.25);
            background: white;
            transform: translateY(-2px);
        }

        .input-group {
            position: relative;
        }

        .input-group-text {
            background: var(--secondary-color);
            border: 2px solid var(--secondary-color);
            color: white;
            border-radius: 12px 0 0 12px;
            padding: 0.875rem 1rem;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 12px 12px 0;
            padding-left: 0.5rem;
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
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--secondary-color);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(51, 153, 255, 0.4);
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .remember-me {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-check-input {
            width: 1.2rem;
            height: 1.2rem;
            border-radius: 4px;
            border: 2px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .forgot-password {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .forgot-password:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        .login-footer {
            background: #f8f9fa;
            padding: 1.5rem 2.5rem;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        .signup-link {
            color: var(--dark-color);
            text-decoration: none;
        }

        .signup-link a {
            color: var(--secondary-color);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .signup-link a:hover {
            color: var(--primary-color);
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            animation: slideInDown 0.5s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Demo credentials box */
        .demo-credentials {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 1px solid #ffeaa7;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .demo-credentials h6 {
            color: #856404;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .demo-credentials p {
            color: #856404;
            margin: 0;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-container {
                margin: 1rem;
                max-width: none;
            }

            .login-header {
                padding: 1.5rem;
            }

            .login-body {
                padding: 2rem 1.5rem;
            }

            .login-footer {
                padding: 1.5rem;
            }

            .login-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-6 col-xl-6">
                <div class="login-container">
        <!-- Header -->
        <div class="login-header">
            <div class="logo">
                <a href="{{ route('homepage') }}" style="display: inline-block; cursor: pointer;">
                    <img src="{{ asset('assets/img/logo_white.png') }}" alt="EVC Logo" style="height: 100px; width: auto; transition: transform 0.3s ease;">
                </a>
            </div>
            <h1 class="login-title">Espace de connexion</h1>
        </div>

        <div class="login-body">

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" id="loginForm">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i>
                        Adresse email
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="votre.email@exemple.com"
                               required>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i>
                        Mot de passe
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-key"></i>
                        </span>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               placeholder="Votre mot de passe"
                               required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="passwordIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="remember-me">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Se souvenir de moi
                        </label>
                    </div>
                    <a href="{{ route('password.request') }}" class="forgot-password">
                        Mot de passe oublié ?
                    </a>
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn btn-login" id="loginBtn">
                    <span id="loginText">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Se connecter
                    </span>
                    <div class="loading-spinner" id="loadingSpinner"></div>
                </button>

                <button type="button" class="btn btn-outline-secondary w-100 mt-3" data-bs-toggle="modal" data-bs-target="#verifyStudentIdModal">
                    <i class="fas fa-id-card me-2"></i>
                    Vérifier mon ID
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="login-footer">

            <p class="mt-2 text-muted">
                <small>
                    <i class="fas fa-shield-alt me-1"></i>
                    Connexion sécurisée SSL
                </small>
            </p>
        </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Modal: Vérification ID Étudiant -->
    <div class="modal fade" id="verifyStudentIdModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white;">
                    <h5 class="modal-title" style="font-weight: 700;">
                        <i class="fas fa-shield-alt me-2"></i>
                        Vérifier votre ID
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 1.25rem;">
                    <form method="POST" action="{{ route('auth.verify-id.check') }}" class="d-grid gap-3">
                        @csrf
                        <div>
                            <label class="form-label fw-semibold">ID Étudiant</label>
                            <input type="text" name="student_id" class="form-control" placeholder="Ex: EVC-2026-050101" required>
                            <small class="text-muted">Vous serez redirigé vers la page de vérification officielle.</small>
                        </div>
                        <button type="submit" class="btn btn-login" style="padding: .9rem 1.25rem;">
                            <i class="fas fa-check-circle me-2"></i>
                            Confirmer votre ID
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }

        // Form submission with loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const loginBtn = document.getElementById('loginBtn');
            const loginText = document.getElementById('loginText');
            const loadingSpinner = document.getElementById('loadingSpinner');

            // Show loading state
            loginText.style.display = 'none';
            loadingSpinner.style.display = 'inline-block';
            loginBtn.disabled = true;
        });

        // Input animations
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Alt + L to focus email
            if (e.altKey && e.key === 'l') {
                e.preventDefault();
                document.getElementById('email').focus();
            }

            // Alt + P to focus password
            if (e.altKey && e.key === 'p') {
                e.preventDefault();
                document.getElementById('password').focus();
            }
        });
    </script>
</body>
</html>
