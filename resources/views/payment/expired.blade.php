<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lien Expiré - EVC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #6c757d, #495057);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .expired-container {
            max-width: 600px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            padding: 50px 30px;
        }
        .expired-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #dc3545, #c82333);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
        }
        .expired-icon i {
            font-size: 3rem;
            color: white;
        }
        h1 {
            color: #dc3545;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="expired-container">
        <div class="expired-icon">
            <i class="fas fa-times-circle"></i>
        </div>

        <h1>Lien Expiré</h1>

        <p class="lead">Ce lien de paiement a expiré.</p>

        <p>Les liens de paiement sont valables pendant 7 jours pour des raisons de sécurité.</p>

        <div class="alert alert-info mt-4">
            <i class="fas fa-envelope me-2"></i>
            <strong>Besoin d'aide ?</strong><br>
            Contactez notre équipe à <a href="mailto:contact@evc.ci">contact@evc.ci</a> pour obtenir un nouveau lien de paiement.
        </div>

        <a href="{{ url('/') }}" class="btn btn-primary mt-3">
            <i class="fas fa-home me-2"></i>Retour à l'accueil
        </a>
    </div>
</body>
</html>
