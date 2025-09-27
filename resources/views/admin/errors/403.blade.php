<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès Refusé - Admin EVC</title>

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
            --light-color: #f8f9fa;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--warning-color) 0%, var(--accent-color) 50%, var(--primary-color) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .error-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.3);
            padding: 3rem;
            text-align: center;
            max-width: 600px;
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

        .error-icon {
            font-size: 8rem;
            background: linear-gradient(135deg, var(--warning-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
            animation: lockShake 2s infinite;
        }

        @keyframes lockShake {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-5deg); }
            75% { transform: rotate(5deg); }
        }

        .error-code {
            font-size: 6rem;
            font-weight: 800;
            color: var(--warning-color);
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .error-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 1rem 0;
        }

        .error-message {
            font-size: 1.1rem;
            color: #6c757d;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .admin-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .admin-brand i {
            margin-right: 0.5rem;
            color: var(--warning-color);
        }

        .btn-admin {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin: 0.5rem;
            box-shadow: 0 4px 15px rgba(0, 51, 102, 0.2);
        }

        .btn-admin:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 51, 102, 0.3);
            color: white;
            text-decoration: none;
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color), var(--accent-color));
            border: none;
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin: 0.5rem;
            box-shadow: 0 4px 15px rgba(255, 153, 0, 0.2);
        }

        .btn-warning:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 153, 0, 0.3);
            color: white;
            text-decoration: none;
        }

        .error-details {
            background: rgba(255, 153, 0, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            margin: 2rem 0;
            border-left: 4px solid var(--warning-color);
        }

        .error-details h5 {
            color: var(--warning-color);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .error-details ul {
            text-align: left;
            color: #6c757d;
            margin: 0;
        }

        .error-details li {
            margin-bottom: 0.5rem;
        }

        .permission-info {
            background: rgba(0, 51, 102, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            margin: 2rem 0;
            border: 1px solid rgba(0, 51, 102, 0.2);
        }

        .permission-info h6 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .permission-info .permission-badge {
            display: inline-block;
            background: var(--warning-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin: 0.25rem;
        }

        .support-info {
            background: rgba(255, 102, 51, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 2rem;
            border: 1px solid rgba(255, 102, 51, 0.2);
        }

        .support-info h6 {
            color: var(--accent-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .support-info p {
            color: #6c757d;
            margin: 0;
            font-size: 0.95rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .error-container {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }

            .error-code {
                font-size: 4rem;
            }

            .error-title {
                font-size: 1.5rem;
            }

            .error-message {
                font-size: 1rem;
            }

            .btn-admin, .btn-warning {
                display: block;
                width: 100%;
                margin: 0.5rem 0;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <!-- Admin Brand -->
        <div class="admin-brand">
            <i class="fas fa-shield-alt"></i>
            EVC Admin
        </div>

        <!-- Error Icon -->
        <div class="error-icon">
            <i class="fas fa-lock"></i>
        </div>

        <!-- Error Code -->
        <h1 class="error-code">403</h1>

        <!-- Error Title -->
        <h2 class="error-title">Accès Refusé</h2>

        <!-- Error Message -->
        <p class="error-message">
            Vous n'avez pas les permissions nécessaires pour accéder à cette section de l'administration. 
            Vérifiez vos droits d'accès ou contactez un super-administrateur.
        </p>

        <!-- Error Details -->
        <div class="error-details">
            <h5><i class="fas fa-shield-alt me-2"></i>Raisons Possibles</h5>
            <ul>
                <li>Votre rôle administrateur ne permet pas l'accès à cette fonctionnalité</li>
                <li>Vos permissions ont été modifiées récemment</li>
                <li>Cette section nécessite des droits de super-administrateur</li>
                <li>Votre session admin a peut-être expiré</li>
            </ul>
        </div>

        <!-- Permission Info -->
        <div class="permission-info">
            <h6><i class="fas fa-user-shield me-2"></i>Vos Permissions Actuelles</h6>
            <div>
                @if(session('admin_role'))
                    <span class="permission-badge">{{ ucfirst(session('admin_role')) }}</span>
                @endif
                @if(session('admin_permissions'))
                    @foreach(session('admin_permissions', []) as $permission)
                        <span class="permission-badge">{{ $permission }}</span>
                    @endforeach
                @else
                    <span class="permission-badge">Permissions limitées</span>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-4">
            <a href="{{ route('admin.dashboard') }}" class="btn-admin">
                <i class="fas fa-tachometer-alt me-2"></i>
                Retour au Dashboard
            </a>
            
            <a href="{{ route('admin.login') }}" class="btn-warning">
                <i class="fas fa-sign-in-alt me-2"></i>
                Reconnexion
            </a>
        </div>

        <div class="mt-3">
            <button onclick="history.back()" class="btn-admin">
                <i class="fas fa-arrow-left me-2"></i>
                Page Précédente
            </button>
        </div>

        <!-- Support Info -->
        <div class="support-info">
            <h6><i class="fas fa-headset me-2"></i>Demande d'Accès</h6>
            <p>
                Pour obtenir des permissions supplémentaires, contactez le super-administrateur à 
                <strong>admin@ecolevirtuelledescreatifs.com</strong> ou 
                <strong>+225 07 17 25 86 02</strong><br>
                <small>Votre ID admin: {{ session('admin_id', 'Non connecté') }}</small>
            </p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Add interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add click effect to buttons
            const buttons = document.querySelectorAll('.btn-admin, .btn-warning');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Create ripple effect
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });

            // Auto-focus on dashboard button
            const dashboardButton = document.querySelector('.btn-admin');
            if (dashboardButton) {
                dashboardButton.focus();
            }
        });

        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Press 'D' to go to dashboard
            if (e.key.toLowerCase() === 'd' && !e.ctrlKey && !e.altKey) {
                window.location.href = "{{ route('admin.dashboard') }}";
            }
            // Press 'L' to go to login
            if (e.key.toLowerCase() === 'l' && !e.ctrlKey && !e.altKey) {
                window.location.href = "{{ route('admin.login') }}";
            }
            // Press 'Escape' to go back
            if (e.key === 'Escape') {
                history.back();
            }
        });

        // Log access attempt for security
        console.warn('403 Access Denied - Admin area');
        console.warn('URL:', window.location.href);
        console.warn('Admin ID:', '{{ session("admin_id", "Not logged in") }}');
        console.warn('Admin Role:', '{{ session("admin_role", "None") }}');
    </script>

    <style>
        /* Ripple effect for buttons */
        .btn-admin, .btn-warning {
            position: relative;
            overflow: hidden;
        }

        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }
    </style>
</body>
</html>
