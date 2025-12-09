<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau TP Assigné</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .email-body {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333333;
            margin-bottom: 20px;
        }
        .tp-card {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }
        .tp-title {
            font-size: 20px;
            font-weight: 700;
            color: #667eea;
            margin: 0 0 15px 0;
        }
        .tp-info {
            margin: 15px 0;
        }
        .tp-info-label {
            font-weight: 600;
            color: #555555;
            display: inline-block;
            min-width: 120px;
        }
        .tp-description {
            background: #ffffff;
            padding: 15px;
            border-radius: 6px;
            margin-top: 15px;
            border: 1px solid #e0e0e0;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .deadline-badge {
            display: inline-block;
            background: #ff6b6b;
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .email-footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            color: #666666;
            font-size: 14px;
            border-top: 1px solid #e0e0e0;
        }
        .email-footer a {
            color: #667eea;
            text-decoration: none;
        }
        .divider {
            height: 1px;
            background: #e0e0e0;
            margin: 25px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>📚 Nouveau Travail Pratique</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p class="greeting">Bonjour <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>,</p>

            <p>Un nouveau travail pratique vient de vous être assigné dans le cadre de votre formation <strong>{{ $assignment['formation'] }}</strong>.</p>

            <!-- TP Card -->
            <div class="tp-card">
                <h2 class="tp-title">{{ $assignment['title'] }}</h2>

                <div class="tp-info">
                    <span class="tp-info-label">📅 Date limite :</span>
                    <span class="deadline-badge">{{ \Carbon\Carbon::parse($assignment['deadline'])->format('d/m/Y à H:i') }}</span>
                </div>

                <div class="tp-info">
                    <span class="tp-info-label">📖 Formation :</span>
                    <span>{{ $assignment['formation'] }}</span>
                </div>

                <div class="tp-info">
                    <span class="tp-info-label">📝 Description :</span>
                </div>
                <div class="tp-description">{!! $assignment['description'] !!}</div>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <strong>⏰ Rappel important :</strong> Assurez-vous de soumettre votre travail avant la date limite pour qu'il soit pris en compte.
            </div>

            <!-- CTA Button -->
            <div style="text-align: center;">
                <a href="{{ url('/evc/compte') }}" class="cta-button">
                    Accéder à mon espace étudiant
                </a>
            </div>

            <div class="divider"></div>

            <p style="color: #666666; font-size: 14px;">
                💡 <strong>Astuce :</strong> Connectez-vous à votre espace étudiant pour soumettre votre travail et suivre votre progression.
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
