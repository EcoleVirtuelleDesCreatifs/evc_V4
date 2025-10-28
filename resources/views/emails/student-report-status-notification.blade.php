<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mise à jour de votre rapport - EVC</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #374151;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .header {
            background: linear-gradient(135deg, #833AB4 0%, #C13584 50%, #E1306C 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(2deg); }
        }

        .evc-logo {
            position: relative;
            z-index: 2;
            margin-bottom: 20px;
        }

        .evc-brand {
            font-size: 2rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 5px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .status-badge {
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            display: inline-block;
            margin-top: 20px;
            position: relative;
            z-index: 2;
        }

        .status-badge.validated {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(86, 171, 47, 0.3);
        }

        .status-badge.rejected {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(235, 51, 73, 0.3);
        }

        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 1.3rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 20px;
        }

        .message-card {
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            border-left: 4px solid;
        }

        .message-card.validated {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-left-color: #28a745;
        }

        .message-card.rejected {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-left-color: #dc3545;
        }

        .report-details {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
            border: 1px solid #e5e7eb;
        }

        .report-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .report-description {
            color: #6b7280;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .action-button {
            background: linear-gradient(135deg, #833AB4 0%, #E1306C 100%);
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            margin: 20px 0;
            box-shadow: 0 8px 25px rgba(131, 58, 180, 0.3);
            transition: transform 0.2s;
        }

        .action-button:hover {
            transform: translateY(-2px);
        }

        .info-box {
            background: #dbeafe;
            padding: 20px;
            border-radius: 12px;
            margin-top: 20px;
        }

        .footer {
            background: #1f2937;
            color: #9ca3af;
            padding: 30px;
            text-align: center;
        }

        .footer-brand {
            font-size: 1.2rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 10px;
        }

        .footer-text {
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .footer-copyright {
            font-size: 0.8rem;
            color: #6b7280;
            padding-top: 15px;
            border-top: 1px solid #4b5563;
            margin-top: 15px;
        }

        @media (max-width: 600px) {
            body { padding: 10px; }
            .header { padding: 30px 20px; }
            .content { padding: 30px 20px; }
            .footer { padding: 25px 20px; }
            .evc-brand { font-size: 1.5rem; }
            .greeting { font-size: 1.1rem; }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- HEADER -->
        <div class="header">
            <div class="evc-logo">
                <div class="evc-brand">🎓 École Virtuelle des Créatifs</div>
            </div>
            <div class="status-badge {{ $status }}">
                @if($status === 'validated')
                    ✅ Rapport Validé
                @else
                    ❌ Rapport Rejeté
                @endif
            </div>
        </div>

        <!-- CONTENU PRINCIPAL -->
        <div class="content">
            <div class="greeting">
                Bonjour {{ $student->name }},
            </div>

            <div class="message-card {{ $status }}">
                @if($status === 'validated')
                    <p style="font-size: 1.1rem; margin-bottom: 15px;">
                        🎉 <strong>Félicitations !</strong> Votre rapport a été <strong>validé</strong> par l'administration.
                    </p>
                    <p style="color: #374151;">
                        Votre travail a été examiné et approuvé. Continuez ainsi !
                    </p>
                @else
                    <p style="font-size: 1.1rem; margin-bottom: 15px;">
                        Votre rapport a été <strong>rejeté</strong> par l'administration.
                    </p>
                    <p style="color: #374151;">
                        Nous vous encourageons à revoir votre travail et à le soumettre à nouveau après les corrections nécessaires.
                    </p>
                @endif
            </div>

            <!-- DÉTAILS DU RAPPORT -->
            <div class="report-details">
                <h3 style="margin-bottom: 15px; color: #1f2937; font-size: 1.1rem;">
                    📝 Détails de votre rapport
                </h3>
                <div class="report-title">
                    {{ $rapport->title }}
                </div>
                @if($rapport->description)
                    <div class="report-description">
                        {{ Str::limit(strip_tags($rapport->description), 150) }}
                    </div>
                @endif
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e5e7eb;">
                    <small style="color: #6b7280;">
                        <i class="fas fa-calendar"></i> Soumis le : {{ \Carbon\Carbon::parse($rapport->created_at)->format('d/m/Y à H:i') }}
                    </small>
                </div>
            </div>

            <!-- BOUTON D'ACTION -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $viewUrl }}" class="action-button" style="background: linear-gradient(135deg, #833AB4 0%, #E1306C 100%); color: #ffffff !important; padding: 15px 40px; border-radius: 50px; text-decoration: none; font-weight: 600; display: inline-block; box-shadow: 0 8px 25px rgba(131, 58, 180, 0.3);">
                    👁️ Consulter mon rapport
                </a>
            </div>

            @if($status === 'validated')
                <div class="info-box">
                    <p style="font-size: 0.95rem; color: #1f2937; margin: 0; text-align: center;">
                        <strong>🎯 Prochaine étape</strong><br>
                        Continuez à soumettre vos rapports et travaux pour progresser dans votre formation.
                    </p>
                </div>
            @else
                <div class="info-box">
                    <p style="font-size: 0.95rem; color: #1f2937; margin: 0; text-align: center;">
                        <strong>💡 Conseil</strong><br>
                        N'hésitez pas à contacter votre formateur pour obtenir des précisions sur les améliorations à apporter.
                    </p>
                </div>
            @endif
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div class="footer-brand">École Virtuelle des Créatifs</div>
            <div class="footer-text">
                Notification automatique du système de gestion pédagogique EVC.<br>
                Cet email vous informe de l'évolution de vos travaux.
            </div>
            <div class="footer-copyright">
                © {{ date('Y') }} École Virtuelle des Créatifs - Tous droits réservés
            </div>
        </div>
    </div>
</body>
</html>
