<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - EVC 2024</title>

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
            max-width: 500px;
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

        .info-box {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
            border-left: 4px solid var(--secondary-color);
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
                            <i class="fas fa-key"></i>
                        </div>
                        <h1 class="h3 mb-0">Mot de passe oublié ?</h1>
                        <p class="mb-0 mt-2 opacity-75">Réinitialisez votre mot de passe</p>
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

                        <form action="{{ route('password.email') }}" method="POST" id="resetForm">
                            @csrf
                            
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
                                           value="{{ old('email') }}" 
                                           placeholder="votre.email@exemple.com" 
                                           required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-reset mb-3" id="resetBtn">
                                <span id="resetText">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Envoyer le lien de réinitialisation
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

                        <div class="info-box">
                            <h6 class="fw-semibold mb-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Comment ça marche ?
                            </h6>
                            <p class="small mb-1">1. Saisissez votre adresse email ci-dessus</p>
                            <p class="small mb-1">2. Cliquez sur "Envoyer le lien de réinitialisation"</p>
                            <p class="small mb-1">3. Vérifiez votre boîte email (et vos spams)</p>
                            <p class="small mb-0">4. Cliquez sur le lien pour créer un nouveau mot de passe</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
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
                this.parentElement.style.transform = 'translateY(-2px)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>
