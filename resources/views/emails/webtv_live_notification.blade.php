<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live en cours - WebTV EVC</title>
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
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
            position: relative;
        }

        .live-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: white;
            color: #dc2626;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            animation: pulse 2s infinite;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.9;
            }
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
            animation: rotate 4s linear infinite;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
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

        .live-announcement {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-left: 4px solid #dc2626;
            padding: 25px;
            border-radius: 12px;
            margin: 25px 0;
        }

        .live-announcement h2 {
            color: #991b1b;
            font-size: 20px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .live-announcement p {
            color: #7f1d1d;
            line-height: 1.7;
            font-size: 15px;
        }

        .video-info {
            background: #f9fafb;
            padding: 25px;
            border-radius: 12px;
            margin: 25px 0;
        }

        .video-info h3 {
            color: #1f2937;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 14px;
        }

        .info-value {
            color: #1f2937;
            font-weight: 500;
            font-size: 14px;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            color: white;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 18px;
            margin: 20px 0;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.5);
        }

        .cta-container {
            text-align: center;
            margin: 30px 0;
        }

        .reminder-box {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }

        .reminder-box p {
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
            <div class="live-badge">
                🔴 EN DIRECT
            </div>
            <div class="header-icon">
                📡
            </div>
            <h1>Live WebTV en Cours !</h1>
            <p>Ne manquez pas cette diffusion</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p class="greeting">
                Bonjour {{ $subscriber->name ?? 'Cher abonné' }},
            </p>

            <div class="live-announcement">
                <h2>
                    🎬 Un live vient de démarrer !
                </h2>
                <p>
                    Nous avons le plaisir de vous informer qu'un nouveau live est actuellement en cours sur notre WebTV.
                    Rejoignez-nous dès maintenant pour ne rien manquer de cette diffusion exceptionnelle !
                </p>
            </div>

            <div class="video-info">
                <h3>📺 Informations du Live</h3>
                <div class="info-row">
                    <span class="info-label">Titre :</span>
                    <span class="info-value">{{ $video->title }}</span>
                </div>
                @if($video->description)
                <div class="info-row">
                    <span class="info-label">Description :</span>
                    <span class="info-value">{{ Str::limit($video->description, 100) }}</span>
                </div>
                @endif
                @if($video->scheduled_start)
                <div class="info-row">
                    <span class="info-label">Heure de début :</span>
                    <span class="info-value">{{ $video->scheduled_start->format('d/m/Y à H:i') }}</span>
                </div>
                @endif
                @if($video->scheduled_end)
                <div class="info-row">
                    <span class="info-label">Durée estimée :</span>
                    <span class="info-value">{{ $video->scheduled_start->diffForHumans($video->scheduled_end, true) }}</span>
                </div>
                @endif
            </div>

            <div class="cta-container">
                <a href="{{ route('webtv') }}" class="cta-button">
                    🎥 Rejoindre le Live Maintenant
                </a>
            </div>

            <div class="reminder-box">
                <p>
                    <strong>💡 Astuce :</strong> Assurez-vous d'avoir une bonne connexion internet pour profiter pleinement de la qualité de diffusion.
                    Le live est accessible depuis n'importe quel appareil connecté.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-logo">EVC</div>
            <p>École Virtuelle des Créatifs</p>
            <p>Votre plateforme de formation en ligne</p>
            <p style="margin-top: 15px;">
                <a href="{{ route('homepage') }}">Visiter le site</a> |
                <a href="{{ route('webtv') }}">WebTV</a>
            </p>

            <div class="unsubscribe">
                <p>
                    Vous recevez cet email car vous êtes abonné à notre WebTV.<br>
                    <a href="{{ route('webtv') }}">Gérer mes préférences</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
