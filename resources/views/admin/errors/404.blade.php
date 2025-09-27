<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Introuvable - Admin EVC</title>

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
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--accent-color) 100%);
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
            background: linear-gradient(135deg, var(--accent-color), var(--warning-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-20px);
            }
            60% {
                transform: translateY(-10px);
            }
        }

        .error-code {
            font-size: 6rem;
            font-weight: 800;
            color: var(--primary-color);
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
            color: var(--accent-color);
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

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #495057);
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
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.2);
        }

        .btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);
            color: white;
            text-decoration: none;
        }

        .error-details {
            background: rgba(0, 51, 102, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            margin: 2rem 0;
            border-left: 4px solid var(--accent-color);
        }

        .error-details h5 {
            color: var(--primary-color);
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

            .btn-admin, .btn-secondary {
                display: block;
                width: 100%;
                margin: 0.5rem 0;
            }
        }

        /* Animation for floating elements */
        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translate(0, 0px); }
            50% { transform: translate(0, -10px); }
            100% { transform: translate(0, 0px); }
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
        <div class="error-icon floating">
            <i class="fas fa-search"></i>
        </div>

        <!-- Error Code -->
        <h1 class="error-code">404</h1>

        <!-- Error Title -->
        <h2 class="error-title">Page Introuvable</h2>

        <!-- Error Message -->
        <p class="error-message">
            Désolé, la page d'administration que vous recherchez n'existe pas ou a été déplacée. 
            Vérifiez l'URL ou retournez au dashboard principal.
        </p>

        <!-- Error Details -->
        <div class="error-details">
            <h5><i class="fas fa-info-circle me-2"></i>Que s'est-il passé ?</h5>
            <ul>
                <li>L'URL demandée n'existe pas dans l'espace admin</li>
                <li>La page a peut-être été supprimée ou déplacée</li>
                <li>Vous n'avez peut-être pas les permissions nécessaires</li>
                <li>Il pourrait y avoir une erreur de frappe dans l'URL</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="mt-4">
            <a href="{{ route('admin.dashboard') }}" class="btn-admin">
                <i class="fas fa-tachometer-alt me-2"></i>
                Retour au Dashboard
            </a>
            
            <a href="{{ route('admin.etudiants') }}" class="btn-secondary">
                <i class="fas fa-users me-2"></i>
                Gestion Étudiants
            </a>
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.documents.index') }}" class="btn-secondary">
                <i class="fas fa-file-check me-2"></i>
                Validation Documents
            </a>
            
            <button onclick="history.back()" class="btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Page Précédente
            </button>
        </div>

        <!-- Support Info -->
        <div class="support-info">
            <h6><i class="fas fa-headset me-2"></i>Besoin d'aide ?</h6>
            <p>
                Si le problème persiste, contactez le support technique à 
                <strong>support@ecolevirtuelledescreatifs.com</strong> ou 
                <strong>+225 07 17 25 86 02</strong>
            </p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add click effect to buttons
            const buttons = document.querySelectorAll('.btn-admin, .btn-secondary');
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

            // Auto-focus on first button for keyboard navigation
            const firstButton = document.querySelector('.btn-admin');
            if (firstButton) {
                firstButton.focus();
            }
        });

        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Press 'D' to go to dashboard
            if (e.key.toLowerCase() === 'd' && !e.ctrlKey && !e.altKey) {
                window.location.href = "{{ route('admin.dashboard') }}";
            }
            // Press 'E' to go to students management
            if (e.key.toLowerCase() === 'e' && !e.ctrlKey && !e.altKey) {
                window.location.href = "{{ route('admin.etudiants') }}";
            }
            // Press 'Escape' to go back
            if (e.key === 'Escape') {
                history.back();
            }
        });
    </script>

    <style>
        /* Ripple effect for buttons */
        .btn-admin, .btn-secondary {
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
