<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administrateur - EVC 2024</title>

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
            --dark-color: #2c3e50;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #003366 0%, #3399ff 50%, #ff6633 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .admin-login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
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

        .admin-header {
            background: linear-gradient(135deg, var(--dark-color) 0%, var(--primary-color) 100%);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
        }

        .admin-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .admin-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .admin-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .admin-subtitle {
            opacity: 0.9;
            font-size: 1rem;
            position: relative;
            z-index: 1;
        }

        .admin-body {
            padding: 2.5rem 2rem;
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
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 51, 102, 0.15);
            transform: translateY(-2px);
            background: white;
        }

        .input-group-text {
            background: var(--primary-color);
            border: 2px solid var(--primary-color);
            color: white;
            border-radius: 12px 0 0 12px;
            font-weight: 600;
        }

        .btn-admin-login {
            background: linear-gradient(135deg, var(--dark-color) 0%, var(--primary-color) 100%);
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-admin-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(44, 62, 80, 0.3);
        }

        .btn-admin-login:active {
            transform: translateY(-1px);
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
            border-left: 4px solid var(--danger-color);
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-left: 4px solid var(--success-color);
        }

        .back-to-site {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }

        .back-to-site a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-to-site a:hover {
            color: var(--dark-color);
            text-decoration: underline;
        }

        .security-notice {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.9rem;
            color: #856404;
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

        @media (max-width: 576px) {
            .admin-login-container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .admin-header {
                padding: 2rem 1.5rem;
            }
            
            .admin-body {
                padding: 2rem 1.5rem;
            }
            
            .admin-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <!-- Header -->
        <div class="admin-header">
            <div class="admin-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <h1 class="admin-title">Administration EVC</h1>
            <p class="admin-subtitle">Espace de gestion sécurisé</p>
        </div>

        <!-- Body -->
        <div class="admin-body">
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

            <form action="{{ route('admin.login') }}" method="POST" id="adminLoginForm">
                @csrf
                
                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold">
                        <i class="fas fa-envelope me-1"></i>
                        Adresse email administrateur
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user-shield"></i>
                        </span>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="{{ old('email') }}" 
                               placeholder="admin@ecolevirtuelledescreatifs.com" 
                               required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold">
                        <i class="fas fa-lock me-1"></i>
                        Mot de passe administrateur
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-key"></i>
                        </span>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Entrez votre mot de passe" 
                               required>
                    </div>
                </div>

                <button type="submit" class="btn btn-admin-login mb-3" id="loginBtn">
                    <span id="loginText">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Accéder à l'administration
                    </span>
                    <div class="loading-spinner" id="loadingSpinner"></div>
                </button>
            </form>

            <div class="security-notice">
                <i class="fas fa-shield-alt me-1"></i>
                <strong>Accès sécurisé :</strong> Cet espace est réservé aux administrateurs autorisés uniquement.
            </div>

            <div class="back-to-site">
                <a href="{{ route('login') }}">
                    <i class="fas fa-arrow-left me-1"></i>
                    Retour à la connexion étudiants
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Form submission with loading state
        document.getElementById('adminLoginForm').addEventListener('submit', function(e) {
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

        // Auto-focus on first input
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });
    </script>
</body>
</html>
