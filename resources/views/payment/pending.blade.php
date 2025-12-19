<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement en Attente - EVC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .pending-container {
            max-width: 600px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            padding: 50px 30px;
        }
        .pending-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
        }
        .pending-icon i {
            font-size: 3rem;
            color: white;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        h1 {
            color: #f59e0b;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .info-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            border-radius: 10px;
            margin: 30px 0;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="pending-container">
        <div class="pending-icon">
            <i class="fas fa-hourglass-half"></i>
        </div>

        <h1>Paiement en Attente</h1>

        <p class="lead">Bonjour <strong>{{ $candidate->prenom }} {{ $candidate->nom }}</strong>,</p>

        <p>Votre paiement de <strong>{{ number_format($payment->amount, 0, ',', ' ') }} XOF</strong> est en cours de traitement.</p>

        <div class="info-box">
            <h5><i class="fas fa-clock me-2 text-warning"></i>Que se passe-t-il ?</h5>
            <p class="mb-0">
                Le traitement du paiement peut prendre quelques minutes. Vous recevrez un email de confirmation dès que le paiement sera validé.
            </p>
        </div>

        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <small><strong>Important :</strong> Ne rafraîchissez pas cette page et ne fermez pas votre navigateur.</small>
        </div>

        <div class="d-flex gap-2 justify-content-center mt-4">
            <a href="{{ url('/') }}" class="btn btn-secondary">
                <i class="fas fa-home me-2"></i>Retour à l'accueil
            </a>
            <button class="btn btn-warning" onclick="location.reload()">
                <i class="fas fa-sync me-2"></i>Actualiser
            </button>
        </div>
    </div>
</body>
</html>
