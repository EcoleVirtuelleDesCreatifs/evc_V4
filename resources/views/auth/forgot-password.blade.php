<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - EVC Platform</title>

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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="%23ffffff" stop-opacity="0.1"/><stop offset="100%" stop-color="%23ffffff" stop-opacity="0"/></radialGradient></defs><circle cx="300" cy="400" r="120" fill="url(%23a)"><animate attributeName="cx" values="300;700;300" dur="18s" repeatCount="indefinite"/></circle><circle cx="700" cy="200" r="100" fill="url(%23a)"><animate attributeName="cy" values="200;600;200" dur="22s" repeatCount="indefinite"/></circle><circle cx="500" cy="700" r="90" fill="url(%23a)"><animate attributeName="r" values="90;130;90" dur="16s" repeatCount="indefinite"/></circle></svg>') center/cover;
            animation: float 25s ease-in-out infinite;
            z-index: 1;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(180deg); }
        }

        .forgot-container {
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

        .forgot-header {
            background: linear-gradient(135deg, #ff6633, #FF9900);
            color: white;
            text-align: center;
            padding: 40px 20px;
            position: relative;
        }

        .forgot-header::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #003366, #3399ff, #003366);
            animation: shimmer 2s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        .forgot-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .forgot-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .forgot-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            font-weight: 300;
            line-height: 1.5;
        }

        .forgot-form {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            font-weight: 600;
            color: #003366;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-control {
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            padding: 15px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus {
            border-color: #ff6633;
            box-shadow: 0 0 0 0.2rem rgba(255, 102, 51, 0.25);
            background: white;
            transform: translateY(-2px);
        }

        .input-group-text {
            background: linear-gradient(135deg, #ff6633, #FF9900);
            border: none;
            color: white;
            border-radius: 12px 0 0 12px;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 12px 12px 0;
        }

        .btn-reset {
            background: linear-gradient(135deg, #ff6633, #FF9900);
            border: none;
            color: white;
            padding: 15px 30px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-reset::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-reset:hover::before {
            left: 100%;
        }

        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 102, 51, 0.3);
        }

        .btn-reset:active {
            transform: translateY(0);
        }

        .info-box {
            background: linear-gradient(135deg, rgba(51, 153, 255, 0.1), rgba(0, 51, 102, 0.1));
            border: 1px solid rgba(51, 153, 255, 0.2);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            text-align: center;
        }

        .info-box i {
            font-size: 2rem;
            color: #3399ff;
            margin-bottom: 10px;
        }

        .info-box h6 {
            color: #003366;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .info-box p {
            color: #6c757d;
            font-size: 0.9rem;
            margin: 0;
            line-height: 1.5;
        }

        .forgot-footer {
            background: #f8f9fa;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e1e5e9;
        }

        .forgot-footer p {
            margin: 0;
            color: #6c757d;
            font-size: 0.95rem;
        }

        .forgot-footer a {
            color: #003366;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
            margin: 0 10px;
        }

        .forgot-footer a:hover {
            color: #3399ff;
        }

        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }

        .alert-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .alert-info {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        /* Success state */
        .success-state {
            text-align: center;
            padding: 40px 20px;
        }

        .success-state .success-icon {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 20px;
            animation: bounceIn 1s ease-out;
        }

        @keyframes bounceIn {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.2); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }

        .success-state h4 {
            color: #003366;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .success-state p {
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .forgot-form {
                padding: 30px 20px;
            }

            .forgot-header {
                padding: 30px 15px;
            }

            .forgot-title {
                font-size: 1.5rem;
            }

            .forgot-icon {
                font-size: 2.5rem;
            }
        }

        /* Loading state */
        .btn-reset.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        /* Form validation */
        .is-invalid {
            border-color: #dc3545 !important;
        }

        .is-valid {
            border-color: #28a745 !important;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 5px;
        }

        .valid-feedback {
            color: #28a745;
            font-size: 0.875rem;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-6 col-xl-6">
                <div class="forgot-container">
                    <!-- Header -->
                    <div class="forgot-header">
                        <div class="forgot-icon">
                            <i class="fas fa-key"></i>
                        </div>
                        <div class="forgot-title">
                            Mot de passe oublié ?
                        </div>
                        <div class="forgot-subtitle">
                            Pas de souci ! Saisissez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.
                        </div>
                    </div>

                    <!-- Form -->
                    <div class="forgot-form" id="forgotForm">
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

                        <div id="resetForm">
                            <div class="info-box">
                                <i class="fas fa-envelope"></i>
                                <h6>Instructions de récupération</h6>
                                <p>
                                    Entrez l'adresse email associée à votre compte. Vous recevrez un email avec les instructions pour créer un nouveau mot de passe.
                                </p>
                            </div>

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf

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
                                               value="{{ old('email') }}" required
                                               placeholder="votre.email@exemple.com">
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-reset" id="resetBtn">
                                    <span class="btn-text">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Envoyer le lien de récupération
                                    </span>
                                    <span class="spinner-border spinner-border-sm d-none" role="status">
                                        <span class="visually-hidden">Envoi en cours...</span>
                                    </span>
                                </button>
                            </form>
                        </div>

                        <!-- Success State (hidden by default) -->
                        <div id="successState" class="success-state d-none">
                            <div class="success-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h4>Email envoyé avec succès !</h4>
                            <p>
                                Nous avons envoyé un lien de récupération à votre adresse email.
                                Vérifiez votre boîte de réception et suivez les instructions pour réinitialiser votre mot de passe.
                            </p>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Conseil :</strong> Si vous ne voyez pas l'email, vérifiez votre dossier spam ou courrier indésirable.
                            </div>
                            <button type="button" class="btn btn-reset" onclick="showResetForm()">
                                <i class="fas fa-redo me-2"></i>
                                Renvoyer l'email
                            </button>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="forgot-footer">
                        <p>
                            <a href="{{ route('login') }}">
                                <i class="fas fa-arrow-left me-1"></i>
                                Retour à la connexion
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
        // Form submission with loading state
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();

            const btn = document.getElementById('resetBtn');
            const btnText = btn.querySelector('.btn-text');
            const spinner = btn.querySelector('.spinner-border');
            const email = document.getElementById('email').value;

            // Validation
            if (!email || !isValidEmail(email)) {
                showAlert('Veuillez saisir une adresse email valide.', 'danger');
                return;
            }

            // Loading state
            btn.classList.add('loading');
            btnText.classList.add('d-none');
            spinner.classList.remove('d-none');

            // Simulate API call (replace with actual form submission)
            setTimeout(() => {
                btn.classList.remove('loading');
                btnText.classList.remove('d-none');
                spinner.classList.add('d-none');

                // Show success state
                showSuccessState(email);
            }, 2000);
        });

        function showSuccessState(email) {
            document.getElementById('resetForm').classList.add('d-none');
            document.getElementById('successState').classList.remove('d-none');

            // Update success message with email
            const successText = document.querySelector('#successState p');
            successText.innerHTML = `
                Nous avons envoyé un lien de récupération à <strong>${email}</strong>.
                Vérifiez votre boîte de réception et suivez les instructions pour réinitialiser votre mot de passe.
            `;
        }

        function showResetForm() {
            document.getElementById('successState').classList.add('d-none');
            document.getElementById('resetForm').classList.remove('d-none');
            document.getElementById('email').focus();
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'danger' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                ${message}
            `;

            const form = document.getElementById('forgotForm');
            form.insertBefore(alertDiv, form.firstChild);

            // Auto-hide after 5 seconds
            setTimeout(() => {
                alertDiv.style.transition = 'opacity 0.5s ease';
                alertDiv.style.opacity = '0';
                setTimeout(() => alertDiv.remove(), 500);
            }, 5000);
        }

        // Email field validation
        document.getElementById('email').addEventListener('blur', function() {
            const email = this.value.trim();

            this.classList.remove('is-valid', 'is-invalid');

            if (email && isValidEmail(email)) {
                this.classList.add('is-valid');
            } else if (email) {
                this.classList.add('is-invalid');
            }
        });

        // Auto-hide existing alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        // Focus on email field when page loads
        window.addEventListener('load', function() {
            document.getElementById('email').focus();
        });
    </script>
</body>
</html>
