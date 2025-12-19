<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Réussi - EVC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #10b981, #059669);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .success-container {
            max-width: 600px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            padding: 50px 30px;
            animation: bounceIn 0.8s;
        }
        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }
        .success-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: checkAnim 0.8s 0.5s both;
        }
        @keyframes checkAnim {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        .success-icon i {
            font-size: 3rem;
            color: white;
        }
        h1 {
            color: #10b981;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .info-box {
            background: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 20px;
            border-radius: 10px;
            margin: 30px 0;
            text-align: left;
        }
        .btn-primary {
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>

        <h1>Paiement Réussi ! 🎉</h1>

        <p class="lead">Félicitations <strong>{{ $candidate->prenom }} {{ $candidate->nom }}</strong> !</p>

        <p>Votre paiement de <strong>{{ number_format($payment->amount, 0, ',', ' ') }} XOF</strong> pour la formation <strong>{{ $candidate->choix_formation }}</strong> a été confirmé avec succès.</p>

        <div class="info-box">
            <h5><i class="fas fa-envelope me-2 text-success"></i>Prochaine étape</h5>
            <p class="mb-0">
                Vous allez recevoir un <strong>email dans quelques instants</strong> avec un lien pour créer votre compte étudiant et choisir votre mot de passe.
            </p>
        </div>

        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <small>Si vous ne recevez pas l'email dans les 5 minutes, vérifiez vos spams ou contactez notre support.</small>
        </div>

        <a href="{{ url('/') }}" class="btn btn-primary mt-3">
            <i class="fas fa-home me-2"></i>Retour à l'accueil
        </a>
    </div>
</body>
</html>
