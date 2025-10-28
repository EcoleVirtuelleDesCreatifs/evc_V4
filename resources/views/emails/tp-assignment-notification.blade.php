<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau TP assigné</title>
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
            background: linear-gradient(135deg, #833AB4 0%, #C13584 50%, #E1306C 100%);
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
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        .email-body {
            padding: 40px 30px;
            color: #333;
        }
        .greeting {
            font-size: 20px;
            font-weight: 600;
            color: #833AB4;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            line-height: 1.8;
            color: #555;
            margin-bottom: 30px;
        }
        .tp-details {
            background: linear-gradient(135deg, rgba(131, 58, 180, 0.05) 0%, rgba(225, 48, 108, 0.05) 100%);
            border-left: 4px solid #C13584;
            border-radius: 10px;
            padding: 25px;
            margin: 30px 0;
        }
        .tp-details h2 {
            margin: 0 0 20px 0;
            font-size: 22px;
            color: #833AB4;
            font-weight: 700;
        }
        .detail-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(131, 58, 180, 0.1);
        }
        .detail-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        .detail-icon {
            font-size: 20px;
            margin-right: 15px;
            min-width: 30px;
            text-align: center;
        }
        .detail-content {
            flex: 1;
        }
        .detail-label {
            font-weight: 600;
            color: #833AB4;
            font-size: 14px;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .detail-value {
            color: #333;
            font-size: 16px;
            line-height: 1.6;
        }
        .deadline-highlight {
            background: linear-gradient(135deg, #F56040 0%, #FCAF45 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            text-align: center;
            margin: 25px 0;
            font-weight: 700;
            font-size: 18px;
            box-shadow: 0 4px 15px rgba(245, 96, 64, 0.3);
        }
        .deadline-highlight .icon {
            font-size: 24px;
            margin-right: 10px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #833AB4 0%, #E1306C 100%);
            color: white;
            text-decoration: none;
            padding: 18px 40px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 16px;
            text-align: center;
            margin: 30px 0;
            box-shadow: 0 6px 20px rgba(131, 58, 180, 0.4);
            transition: all 0.3s ease;
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(131, 58, 180, 0.5);
        }
        .info-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
            border: 1px solid #e9ecef;
        }
        .info-box p {
            margin: 0;
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }
        .info-box .icon {
            color: #C13584;
            margin-right: 8px;
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
            color: #833AB4;
            text-decoration: none;
            font-weight: 600;
        }
        .social-links {
            margin-top: 20px;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #833AB4;
            font-size: 20px;
            transition: transform 0.3s ease;
        }
        .social-links a:hover {
            transform: scale(1.2);
        }
        .badge {
            display: inline-block;
            background: linear-gradient(135deg, #833AB4 0%, #C13584 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="icon">📚</div>
            <h1>Nouveau TP Assigné</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <div class="greeting">
                Bonjour {{ $student->first_name }} {{ $student->last_name }},
            </div>

            <div class="message">
                Un nouveau travail pratique vient de vous être assigné dans le cadre de votre formation 
                <strong>{{ $formation }}</strong>. Veuillez consulter les détails ci-dessous et soumettre votre travail avant la date limite.
            </div>

            <!-- TP Details -->
            <div class="tp-details">
                <h2>{{ $tpTitle }}</h2>

                <div class="detail-item">
                    <div class="detail-icon">📝</div>
                    <div class="detail-content">
                        <div class="detail-label">Description</div>
                        <div class="detail-value">{!! $tpDescription !!}</div>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon">🎓</div>
                    <div class="detail-content">
                        <div class="detail-label">Formation</div>
                        <div class="detail-value">{{ $formation }}</div>
                    </div>
                </div>

                @if($filesCount > 0)
                <div class="detail-item">
                    <div class="detail-icon">📎</div>
                    <div class="detail-content">
                        <div class="detail-label">Fichiers joints</div>
                        <div class="detail-value">{{ $filesCount }} fichier(s) disponible(s) à télécharger</div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Deadline Highlight -->
            <div class="deadline-highlight">
                <span class="icon">⏰</span>
                Date limite : {{ \Carbon\Carbon::parse($deadline)->locale('fr')->isoFormat('dddd D MMMM YYYY à HH:mm') }}
            </div>

            <!-- CTA Button -->
            <center>
                <a href="{{ $tpUrl }}" class="cta-button">
                    📖 Consulter et Soumettre le TP
                </a>
            </center>

            <!-- Info Box -->
            <div class="info-box">
                <p>
                    <span class="icon">💡</span>
                    <strong>Conseil :</strong> N'attendez pas la dernière minute pour soumettre votre travail. 
                    Assurez-vous de bien lire toutes les consignes et de télécharger les fichiers joints si disponibles.
                </p>
            </div>

            <div class="message" style="margin-top: 30px;">
                Si vous avez des questions concernant ce TP, n'hésitez pas à contacter votre formateur ou l'administration.
            </div>

            <div class="message" style="font-size: 14px; color: #888;">
                Bonne chance dans la réalisation de votre travail ! 🚀
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>École Virtuelle des Créatifs</strong></p>
            <p>Votre partenaire pour une formation d'excellence</p>
            <p style="margin-top: 15px;">
                <a href="{{ url('/') }}">Visiter notre site web</a> | 
                <a href="{{ url('/contact') }}">Nous contacter</a>
            </p>
            <div class="social-links">
                <a href="#" title="Facebook">📘</a>
                <a href="#" title="Instagram">📷</a>
                <a href="#" title="LinkedIn">💼</a>
                <a href="#" title="Twitter">🐦</a>
            </div>
            <p style="margin-top: 20px; font-size: 12px; color: #999;">
                Cet email a été envoyé automatiquement. Merci de ne pas y répondre directement.
            </p>
        </div>
    </div>
</body>
</html>
