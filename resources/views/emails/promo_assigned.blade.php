<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle réduction sur votre compte</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 24px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .content {
            padding: 32px 24px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 16px;
            color: #111;
        }
        .card {
            border-left: 5px solid #667eea;
            background: #f8f9ff;
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
        }
        .card h2 {
            margin: 0 0 12px 0;
            font-size: 20px;
            color: #764ba2;
        }
        .detail {
            margin: 8px 0;
            font-size: 15px;
        }
        .detail strong {
            color: #667eea;
        }
        .badge {
            display: inline-block;
            background: #ff4757;
            color: #fff;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-top: 12px;
        }
        .cta {
            text-align: center;
            margin: 32px 0 16px;
        }
        .cta a {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 14px 32px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
        }
        .footer {
            background: #f0f2f5;
            padding: 24px;
            text-align: center;
            font-size: 13px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Vous avez reçu une réduction !</h1>
        </div>

        <div class="content">
            <p class="greeting">Bonjour {{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }},</p>

            <p>Une réduction vient de vous être attribuée sur votre compte étudiant. Vous pouvez l'utiliser dès maintenant dans notre boutique en ligne.</p>

            <div class="card">
                <h2>Détails de votre réduction</h2>
                <p class="detail"><strong>ID étudiant :</strong> {{ $promo->student_id }}</p>
                <p class="detail"><strong>Code promo :</strong> {{ $promo->code ?? 'Automatique' }}</p>
                <p class="detail"><strong>Réduction :</strong>
                    @if($promo->type === 'percent')
                        {{ $promo->value }} %
                    @else
                        {{ number_format($promo->value, 0, ',', ' ') }} FCFA
                    @endif
                </p>
                @if($promo->expires_at)
                    <span class="badge">Valable jusqu'au {{ $promo->expires_at->format('d/m/Y') }}</span>
                @else
                    <span class="badge">Valable dès maintenant</span>
                @endif
            </div>

            <p>Renseignez simplement votre <strong>ID étudiant</strong> lors de votre prochaine commande pour bénéficier automatiquement de la réduction.</p>

            <div class="cta">
                <a href="{{ url('/evc-store') }}">Découvrir la boutique</a>
            </div>
        </div>

        <div class="footer">
            <p>École Virtuelle des Créatifs — <a href="{{ url('/') }}">ecolevirtuelledescreatifs.com</a></p>
            <p>Si vous avez des questions, contactez notre équipe.</p>
        </div>
    </div>
</body>
</html>
