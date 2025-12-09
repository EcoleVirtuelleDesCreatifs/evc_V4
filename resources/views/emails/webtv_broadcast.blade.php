<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification WebTV - EVC</title>
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
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
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

        .message-box {
            background: #f9fafb;
            border-left: 4px solid #f97316;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }

        .message-box p {
            color: #4b5563;
            line-height: 1.7;
            font-size: 15px;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: white;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            transition: transform 0.3s ease;
        }

        .cta-button:hover {
            transform: translateY(-2px);
        }

        .info-box {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            border-radius: 8px;
            margin: 25px 0;
        }

        .info-box p {
            color: #1e40af;
            font-size: 14px;
            line-height: 1.6;
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

        .unsubscribe {
            margin-top: 20px;
            font-size: 12px;
            color: #6b7280;
        }

        .unsubscribe a {
            color: #9ca3af;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-icon">
                📺
            </div>
            <h1>WebTV - École Virtuelle des Créatifs</h1>
            <p>Notification importante</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p class="greeting">
                Bonjour {{ $subscriber->name ?? 'Cher abonné' }},
            </p>

            <div class="message-box">
                <p>{!! nl2br(e($customMessage)) !!}</p>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('webtv') }}" class="cta-button">
                    Voir la WebTV
                </a>
            </div>

            <div class="info-box">
                <p>
                    <strong>💡 Conseil :</strong> Ajoutez notre adresse email à vos contacts pour ne manquer aucune notification.
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

            <div class="unsubscribe">
                <p>
                    Vous recevez cet email car vous êtes abonné à notre WebTV.<br>
                    <a href="{{ route('webtv') }}">Se désabonner</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
