<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test WebTV - EVC</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .header-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 16px;
            opacity: 0.95;
        }

        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 18px;
            color: #1f2937;
            margin-bottom: 20px;
        }

        .success-box {
            background: #ecfdf5;
            border-left: 4px solid #10b981;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }

        .success-box p {
            color: #065f46;
            line-height: 1.7;
            font-size: 15px;
        }

        .info-list {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }

        .info-list h3 {
            color: #1f2937;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .info-list ul {
            list-style: none;
            padding: 0;
        }

        .info-list li {
            color: #4b5563;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }

        .info-list li:last-child {
            border-bottom: none;
        }

        .info-list li strong {
            color: #1f2937;
        }

        .footer {
            background: #1f2937;
            padding: 30px;
            text-align: center;
            color: #9ca3af;
        }

        .footer-logo {
            font-size: 24px;
            font-weight: 700;
            color: #f97316;
            margin-bottom: 15px;
        }

        .footer p {
            font-size: 14px;
            margin: 5px 0;
        }

        .footer a {
            color: #f97316;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-icon">
                🧪
            </div>
            <h1>Email de Test WebTV</h1>
            <p>Configuration email validée !</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p class="greeting">
                Bonjour {{ $subscriber->name ?? 'Cher abonné' }},
            </p>

            <div class="success-box">
                <p>
                    <strong>✅ Félicitations !</strong><br>
                    Vous recevez cet email de test, ce qui signifie que votre système de notification WebTV fonctionne parfaitement !
                </p>
            </div>

            <div class="info-list">
                <h3>📋 Informations de votre abonnement :</h3>
                <ul>
                    <li><strong>Email :</strong> {{ $subscriber->email }}</li>
                    <li><strong>Nom :</strong> {{ $subscriber->name ?? 'Non renseigné' }}</li>
                    <li><strong>Statut :</strong> {{ $subscriber->is_active ? 'Actif' : 'Inactif' }}</li>
                    <li><strong>Vérifié :</strong> {{ $subscriber->verified_at ? 'Oui' : 'Non' }}</li>
                    <li><strong>Date d'inscription :</strong> {{ $subscriber->created_at->format('d/m/Y à H:i') }}</li>
                </ul>
            </div>

            <div class="success-box">
                <p>
                    <strong>💡 Information :</strong><br>
                    Ceci est un email de test envoyé depuis l'administration. Vous recevrez des notifications similaires lors de nouvelles diffusions sur la WebTV.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-logo">EVC</div>
            <p>École Virtuelle des Créatifs</p>
            <p>Votre plateforme de formation en ligne</p>
            <p style="margin-top: 15px;">
                <a href="{{ route('homepage') }}">Visiter le site</a>
            </p>
        </div>
    </div>
</body>
</html>
