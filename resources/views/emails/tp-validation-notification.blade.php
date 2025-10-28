<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $status === 'validated' ? 'TP Validé' : 'TP à Améliorer' }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, 
                {{ $status === 'validated' ? '#1cc88a 0%, #13855c 100%' : '#f6c23e 0%, #dda20a 100%' }});
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .email-header .icon {
            font-size: 60px;
            margin-bottom: 15px;
        }
        .email-body {
            padding: 40px 30px;
            color: #333;
        }
        .status-box {
            background: linear-gradient(135deg, 
                {{ $status === 'validated' ? 'rgba(28, 200, 138, 0.1), rgba(19, 133, 92, 0.1)' : 'rgba(246, 194, 62, 0.1), rgba(221, 162, 10, 0.1)' }});
            border-left: 4px solid {{ $status === 'validated' ? '#1cc88a' : '#f6c23e' }};
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .status-box h2 {
            margin: 0 0 10px 0;
            font-size: 20px;
            color: {{ $status === 'validated' ? '#1cc88a' : '#f6c23e' }};
        }
        .status-box p {
            margin: 0;
            color: #555;
            line-height: 1.6;
        }
        .tp-info {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
        }
        .tp-info h3 {
            margin: 0 0 15px 0;
            font-size: 18px;
            color: #1a202c;
        }
        .info-item {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
        }
        .info-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #4a5568;
            font-size: 13px;
        }
        .info-value {
            color: #1a202c;
            font-size: 15px;
        }
        .rejection-reason {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
        }
        .rejection-reason h3 {
            margin: 0 0 10px 0;
            color: #856404;
            font-size: 18px;
        }
        .rejection-reason p {
            margin: 0;
            color: #856404;
            line-height: 1.8;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, 
                {{ $status === 'validated' ? '#1cc88a 0%, #13855c 100%' : '#f6c23e 0%, #dda20a 100%' }});
            color: white;
            text-decoration: none;
            padding: 18px 40px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 16px;
            text-align: center;
            margin: 30px 0;
            box-shadow: 0 6px 20px {{ $status === 'validated' ? 'rgba(28, 200, 138, 0.4)' : 'rgba(246, 194, 62, 0.4)' }};
            transition: all 0.3s ease;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #e9ecef;
        }
        .email-footer p {
            margin: 5px 0;
        }
        .email-footer a {
            color: {{ $status === 'validated' ? '#1cc88a' : '#f6c23e' }};
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="icon">{{ $status === 'validated' ? '✅' : '📝' }}</div>
            <h1>{{ $status === 'validated' ? 'TP Validé !' : 'TP à Améliorer' }}</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <div class="status-box">
                <h2>{{ $status === 'validated' ? '🎉 Félicitations !' : '💡 Améliorations nécessaires' }}</h2>
                <p>
                    @if($status === 'validated')
                        Votre travail pratique a été évalué et validé par votre formateur. Excellent travail !
                    @else
                        Votre travail pratique a été évalué par votre formateur. Quelques améliorations sont nécessaires avant validation.
                    @endif
                </p>
            </div>

            <!-- Informations du TP -->
            <div class="tp-info">
                <h3>📋 Détails du TP</h3>
                <div class="info-item">
                    <div class="info-label">Titre du TP</div>
                    <div class="info-value">{{ $tp->title }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Formation</div>
                    <div class="info-value">{{ $tp->formation }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Date d'évaluation</div>
                    <div class="info-value">{{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</div>
                </div>
            </div>

            @if($status === 'rejected' && $rejectionReason)
            <!-- Raison du rejet -->
            <div class="rejection-reason">
                <h3>📌 Commentaires du formateur</h3>
                <p>{{ $rejectionReason }}</p>
            </div>
            @endif

            @if($status === 'validated')
            <div style="background: #e7f9f0; border-left: 4px solid #1cc88a; border-radius: 10px; padding: 20px; margin: 25px 0;">
                <p style="margin: 0; color: #13855c; line-height: 1.8;">
                    <strong>🌟 Bravo !</strong> Votre travail démontre une excellente maîtrise des compétences requises. Continuez sur cette lancée !
                </p>
            </div>
            @else
            <div style="background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 10px; padding: 20px; margin: 25px 0;">
                <p style="margin: 0; color: #856404; line-height: 1.8;">
                    <strong>💪 Courage !</strong> Prenez en compte les commentaires de votre formateur et resoumettez votre travail amélioré. Vous êtes sur la bonne voie !
                </p>
            </div>
            @endif

            <!-- CTA Button -->
            <center>
                <a href="{{ $tpUrl }}" class="cta-button">
                    {{ $status === 'validated' ? '🎓 Voir mes TP' : '🔄 Resoumettre le TP' }}
                </a>
            </center>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>École Virtuelle des Créatifs</strong></p>
            <p>Plateforme de gestion des travaux pratiques</p>
            <p style="margin-top: 15px;">
                <a href="{{ url('/') }}">Accéder au site</a> | 
                <a href="{{ $tpUrl }}">Mes TP</a>
            </p>
            <p style="margin-top: 20px; font-size: 12px; color: #999;">
                Cet email a été envoyé automatiquement. Merci de ne pas y répondre directement.
            </p>
        </div>
    </div>
</body>
</html>
