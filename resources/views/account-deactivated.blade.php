<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte Désactivé - EVC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .deactivated-container {
            max-width: 600px;
            margin: 20px;
        }
        .deactivated-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        .card-header-custom {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .icon-warning {
            font-size: 80px;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        .card-body-custom {
            padding: 40px 30px;
        }
        .reason-box {
            background: #f8f9fa;
            border-left: 4px solid #dc3545;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .contact-box {
            background: #e7f3ff;
            border: 2px solid #0d6efd;
            padding: 20px;
            margin-top: 30px;
            border-radius: 10px;
            text-align: center;
        }
        .btn-logout {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            border: none;
            padding: 12px 30px;
            color: white;
            font-weight: bold;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            color: white;
        }
        .info-item {
            margin: 15px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="deactivated-container">
        <div class="deactivated-card">
            <div class="card-header-custom">
                <div class="icon-warning">
                    <i class="fas fa-ban"></i>
                </div>
                <h1 class="mb-0">Compte Désactivé</h1>
            </div>
            
            <div class="card-body-custom">
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Accès Bloqué</strong>
                </div>
                
                <p class="lead text-center mb-4">
                    Votre compte a été désactivé par l'administration de l'École Virtuelle des Créatifs.
                </p>
                
                @if(isset($reason) && !empty($reason))
                <div class="reason-box">
                    <h5 class="mb-3">
                        <i class="fas fa-info-circle me-2"></i>Raison de la désactivation :
                    </h5>
                    <p class="mb-0" style="white-space: pre-wrap;">{{ $reason }}</p>
                </div>
                @endif
                
                @if(isset($deactivatedAt))
                <div class="info-item">
                    <strong><i class="fas fa-calendar me-2"></i>Date de désactivation :</strong> 
                    {{ \Carbon\Carbon::parse($deactivatedAt)->format('d/m/Y à H:i') }}
                </div>
                @endif
                
                <div class="contact-box">
                    <h5 class="mb-3">
                        <i class="fas fa-headset me-2"></i>Besoin d'aide ?
                    </h5>
                    <p class="mb-3">
                        Pour toute question concernant la désactivation de votre compte ou pour demander sa réactivation, 
                        veuillez contacter notre service administratif :
                    </p>
                    <div class="d-grid gap-2">
                        <a href="mailto:info@ecolevirtuelledescreatifs.com" class="btn btn-primary">
                            <i class="fas fa-envelope me-2"></i>Envoyer un email
                        </a>
                        <a href="tel:+2250747259507" class="btn btn-outline-primary">
                            <i class="fas fa-phone me-2"></i>Appeler le support
                        </a>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-logout">
                            <i class="fas fa-sign-out-alt me-2"></i>Se déconnecter
                        </button>
                    </form>
                </div>
                
                <div class="text-center mt-4">
                    <small class="text-muted">
                        Un email avec plus de détails vous a été envoyé à l'adresse associée à votre compte.
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
