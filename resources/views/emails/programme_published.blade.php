<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Programme Disponible</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            margin: 20px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 18px;
            color: #1e3c72;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .programme-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-left: 4px solid #1e3c72;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .programme-title {
            font-size: 20px;
            font-weight: 700;
            color: #1e3c72;
            margin: 0 0 10px 0;
        }
        .programme-formation {
            display: inline-block;
            background: #1e3c72;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .programme-description {
            background: white;
            padding: 15px;
            border-radius: 6px;
            margin-top: 10px;
            color: #555;
        }
        .info-box {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
            transition: transform 0.3s;
        }
        .cta-button:hover {
            transform: translateY(-2px);
        }
        .tip-box {
            background-color: #f1f8e9;
            border-left: 4px solid #4caf50;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .tip-box strong {
            color: #2e7d32;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #666;
            border-top: 1px solid #e0e0e0;
        }
        .footer a {
            color: #1e3c72;
            text-decoration: none;
        }
        .social-links {
            margin: 15px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #1e3c72;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="icon">📚</div>
            <h1>Nouveau Programme Disponible</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <p class="greeting">Bonjour {{ $student->first_name ?? 'Étudiant' }} {{ $student->last_name ?? '' }},</p>

            <p>Nous avons le plaisir de vous informer qu'un nouveau programme de formation vient d'être publié et est maintenant disponible dans votre espace étudiant !</p>

            <!-- Programme Card -->
            <div class="programme-card">
                <h2 class="programme-title">{{ $programme['titre'] }}</h2>

                <span class="programme-formation">📖 {{ $programme['formation'] }}</span>

                @if(!empty($programme['description']))
                <div class="programme-description">
                    {{ $programme['description'] }}
                </div>
                @endif
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <strong>📥 Document PDF disponible</strong><br>
                Le programme complet est disponible en téléchargement au format PDF dans votre espace étudiant.
            </div>

            <!-- CTA Button -->
            <center>
                <a href="{{ $programmeUrl }}" class="cta-button">
                    📖 Accéder au programme
                </a>
            </center>

            <!-- Tip -->
            <div class="tip-box">
                <strong>💡 Astuce :</strong> Consultez régulièrement votre espace étudiant pour ne manquer aucune nouveauté. Les programmes sont des ressources précieuses pour votre apprentissage !
            </div>

            <p style="margin-top: 30px;">
                Bonne formation,<br>
                <strong>L'équipe École Virtuelle des Créatifs</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>École Virtuelle des Créatifs (EVC)</strong></p>
            <p>📞 (+225) 07 17 25 86 02<br>
               📍 Abidjan, Palmeraie<br>
               📧 Email : <a href="mailto:info@ecolevirtuelledescreatifs.com">info@ecolevirtuelledescreatifs.com</a> | <a href="mailto:contact@ecolevirtuelledescreatifs.com">contact@ecolevirtuelledescreatifs.com</a><br>
               🌐 Site web : <a href="https://www.ecolevirtuelledescreatifs.com">www.ecolevirtuelledescreatifs.com</a><br>
               📱 WhatsApp : +225 07 47 25 95 07</p>

            <div class="social-links">
                <a href="#">Facebook</a> |
                <a href="#">LinkedIn</a> |
                <a href="#">Instagram</a>
            </div>

            <p style="font-size: 12px; color: #999; margin-top: 15px;">
                Cet email a été envoyé automatiquement. Merci de ne pas y répondre directement.
            </p>
        </div>
    </div>
</body>
</html>
