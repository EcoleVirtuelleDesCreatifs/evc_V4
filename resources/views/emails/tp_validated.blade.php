<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TP Validé</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .email-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .success-icon {
            font-size: 64px;
            margin-bottom: 15px;
        }
        .email-body {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333333;
            margin-bottom: 20px;
        }
        .success-card {
            background: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }
        .tp-title {
            font-size: 20px;
            font-weight: 700;
            color: #10b981;
            margin: 0 0 15px 0;
        }
        .info-box {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .info-box strong {
            color: #0066cc;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            margin: 25px 0;
            text-align: center;
            transition: transform 0.2s;
        }
        .cta-button:hover {
            transform: translateY(-2px);
        }
        .email-footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            color: #666666;
            font-size: 14px;
            border-top: 1px solid #e0e0e0;
        }
        .email-footer a {
            color: #10b981;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="success-icon">✅</div>
            <h1>TP Validé avec Succès !</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p class="greeting">Félicitations <strong>{{ $student->first_name }} {{ $student->last_name }}</strong> !</p>

            <p>Nous sommes heureux de vous informer que votre travail pratique a été validé par votre formateur.</p>

            <!-- TP Card -->
            <div class="success-card">
                <h2 class="tp-title">✅ {{ $tp->title }}</h2>
                <p style="margin: 0; color: #059669;">
                    <strong>Statut :</strong> Validé<br>
                    <strong>Date de validation :</strong> {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}
                </p>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <strong>🎉 Excellent travail !</strong><br>
                Continuez ainsi et n'hésitez pas à consulter les prochains travaux pratiques qui vous seront assignés.
            </div>

            <!-- CTA Button -->
            <div style="text-align: center;">
                <a href="{{ url('/evc/compte') }}" class="cta-button">
                    Accéder à mon espace étudiant
                </a>
            </div>

            <p style="color: #666666; font-size: 14px; margin-top: 30px;">
                💡 <strong>Conseil :</strong> Continuez à soumettre vos travaux dans les délais pour maximiser vos chances de réussite.
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>École Virtuelle des Créatifs</strong></p>
            <p>
                Cet email est envoyé automatiquement, merci de ne pas y répondre.<br>
                Pour toute question, contactez votre formateur ou l'administration.
            </p>
            <p style="margin-top: 20px;">
                <a href="{{ url('/') }}">Visitez notre site</a>
            </p>
        </div>
    </div>
</body>
</html>
