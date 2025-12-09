<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TP À Revoir</title>
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
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .warning-icon {
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
        .warning-card {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }
        .tp-title {
            font-size: 20px;
            font-weight: 700;
            color: #d97706;
            margin: 0 0 15px 0;
        }
        .rejection-reason {
            background: #ffffff;
            padding: 15px;
            border-radius: 6px;
            margin-top: 15px;
            border: 1px solid #fbbf24;
        }
        .reason-label {
            font-weight: 700;
            color: #d97706;
            margin-bottom: 10px;
        }
        .info-box {
            background: #dbeafe;
            border: 1px solid #93c5fd;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .info-box strong {
            color: #1e40af;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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
            color: #f59e0b;
            text-decoration: none;
        }
        .tips-list {
            background: #f0fdfa;
            border: 1px solid #5eead4;
            padding: 15px 15px 15px 35px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .tips-list li {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="warning-icon">📝</div>
            <h1>Votre TP nécessite des améliorations</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p class="greeting">Bonjour <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>,</p>

            <p>Votre formateur a examiné votre travail pratique et souhaite que vous y apportiez quelques améliorations avant validation.</p>

            <!-- TP Card -->
            <div class="warning-card">
                <h2 class="tp-title">📝 {{ $tp->title }}</h2>

                <div class="rejection-reason">
                    <div class="reason-label">💬 Commentaires du formateur :</div>
                    <p style="margin: 0; color: #333; white-space: pre-wrap;">{{ $rejectionReason }}</p>
                </div>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <strong>💡 Ne vous découragez pas !</strong><br>
                Ces retours sont une opportunité d'apprentissage. Prenez en compte les commentaires de votre formateur pour améliorer votre travail.
            </div>

            <!-- Tips -->
            <div class="tips-list">
                <strong style="color: #0d9488;">✨ Conseils pour réussir :</strong>
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                    <li>Lisez attentivement les commentaires du formateur</li>
                    <li>Apportez les modifications demandées</li>
                    <li>Vérifiez que vous respectez toutes les consignes</li>
                    <li>Soumettez à nouveau votre travail amélioré</li>
                </ul>
            </div>

            <!-- CTA Button -->
            <div style="text-align: center;">
                <a href="{{ url('/evc/compte') }}" class="cta-button">
                    Améliorer mon travail
                </a>
            </div>

            <p style="color: #666666; font-size: 14px; margin-top: 30px;">
                🎯 <strong>Rappel :</strong> N'hésitez pas à contacter votre formateur si vous avez des questions sur les améliorations à apporter.
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
