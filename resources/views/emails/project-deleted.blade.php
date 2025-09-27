<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet Supprimé - EVC</title>
    <style>
        /* 🎨 DESIGN MODERNE EVC - Variables CSS */
        :root {
            --evc-primary: #2563eb;
            --evc-secondary: #ea580c;
            --evc-danger: #ef4444;
            --evc-warning: #f59e0b;
            --evc-dark: #1f2937;
            --evc-light: #f8fafc;
            --evc-white: #ffffff;
            --evc-text: #374151;
            --evc-text-light: #6b7280;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: var(--evc-text);
            background: linear-gradient(135deg, var(--evc-primary) 0%, var(--evc-secondary) 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: var(--evc-white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
        }

        /* 🚨 HEADER ALERTE EVC */
        .header {
            background: linear-gradient(135deg, var(--evc-danger), #dc2626);
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.3; }
            50% { transform: scale(1.1) rotate(1deg); opacity: 0.6; }
        }

        .evc-logo {
            position: relative;
            z-index: 2;
            margin-bottom: 20px;
        }

        .evc-brand {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--evc-white);
            margin-bottom: 5px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .evc-tagline {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            font-weight: 300;
            letter-spacing: 1px;
        }

        .alert-badge {
            background: rgba(255, 255, 255, 0.2);
            color: var(--evc-white);
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            display: inline-block;
            margin-top: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        /* 📋 CONTENU PRINCIPAL */
        .content {
            padding: 40px 30px;
        }

        .alert-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--evc-danger);
            margin-bottom: 20px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .alert-card {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            border-left: 4px solid var(--evc-danger);
            position: relative;
        }

        .project-info {
            background: var(--evc-light);
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            border: 1px solid #e5e7eb;
        }

        .project-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--evc-danger);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .project-description {
            color: var(--evc-text-light);
            line-height: 1.6;
            font-size: 1rem;
        }

        .deletion-info {
            background: linear-gradient(135deg, var(--evc-danger), #dc2626);
            color: var(--evc-white);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            margin: 25px 0;
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.2);
        }

        .important-notice {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border: 2px solid var(--evc-warning);
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
        }

        .important-notice h3 {
            color: var(--evc-warning);
            font-size: 1.2rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .notice-list {
            list-style: none;
            padding: 0;
        }

        .notice-list li {
            padding: 8px 0;
            padding-left: 25px;
            position: relative;
            color: #92400e;
        }

        .notice-list li::before {
            content: '⚠️';
            position: absolute;
            left: 0;
            top: 8px;
            font-size: 0.9rem;
        }

        /* 🎨 FOOTER MODERNE */
        .footer {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .footer-brand {
            font-weight: 700;
            color: var(--evc-primary);
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .footer-text {
            color: var(--evc-text-light);
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .footer-copyright {
            font-size: 0.8rem;
            color: var(--evc-text-light);
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
        }

        /* 📱 RESPONSIVE */
        @media (max-width: 600px) {
            body { padding: 10px; }
            .header { padding: 30px 20px; }
            .content { padding: 30px 20px; }
            .footer { padding: 25px 20px; }
            .evc-brand { font-size: 1.8rem; }
            .alert-title { font-size: 1.2rem; }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- 🚨 HEADER ALERTE EVC -->
        <div class="header">
            <div class="evc-logo">
                <div class="evc-brand">🎨 École Virtuelle des Créatifs</div>
                <div class="evc-tagline">EVC : On apprend en faisant, on réussit en sachant</div>
            </div>
            <div class="alert-badge">🚨 Notification de Suppression 🚨</div>
        </div>

        <!-- 📋 CONTENU PRINCIPAL -->
        <div class="content">
            <div class="alert-title">
                <span>⚠️</span> Projet Supprimé - {{ $user->name ?? $user->first_name ?? 'Étudiant' }}
            </div>

            <div class="alert-card">
                <p style="font-size: 1.1rem; margin-bottom: 15px;">
                    Nous vous informons que votre projet a été <strong>supprimé</strong> de la plateforme EVC par notre équipe administrative.
                </p>
                <p style="color: var(--evc-text-light);">
                    Cette action a été effectuée conformément aux politiques de l'École Virtuelle des Créatifs.
                </p>
            </div>

            <!-- 📋 INFORMATIONS PROJET SUPPRIMÉ -->
            <div class="project-info">
                <div class="project-title">
                    <span>🗑️</span> {{ $projectTitle }}
                </div>
                @if($projectDescription)
                    <div class="project-description">{{ $projectDescription }}</div>
                @endif
            </div>

            <!-- 🚨 INFO SUPPRESSION -->
            <div class="deletion-info">
                <div style="font-size: 1.2rem; font-weight: 600; margin-bottom: 8px;">
                    📅 Supprimé le {{ $deletionDate }}
                </div>
                <div style="opacity: 0.9;">
                    Par l'équipe administrative EVC
                </div>
            </div>

            <!-- ⚠️ AVIS IMPORTANT -->
            <div class="important-notice">
                <h3>
                    <span>⚠️</span> Raisons possibles de suppression
                </h3>
                <ul class="notice-list">
                    <li>Non-conformité aux critères pédagogiques EVC</li>
                    <li>Contenu inapproprié ou non autorisé</li>
                    <li>Violation des règles de l'établissement</li>
                    <li>Demande de suppression de l'étudiant</li>
                    <li>Maintenance technique ou réorganisation</li>
                </ul>
            </div>

            <div style="text-align: center; margin: 30px 0; padding: 20px; background: linear-gradient(135deg, #e0f2fe, #b3e5fc); border-radius: 12px; border: 1px solid #0ea5e9;">
                <p style="font-size: 1.1rem; font-weight: 600; color: var(--evc-primary); margin: 0;">
                    💬 Des questions ? Contactez notre équipe administrative
                </p>
                <p style="color: var(--evc-text-light); margin-top: 8px; font-size: 0.95rem;">
                    Si vous pensez qu'il s'agit d'une erreur, n'hésitez pas à nous contacter
                </p>
            </div>
        </div>

        <!-- 🎨 FOOTER MODERNE -->
        <div class="footer">
            <div class="footer-brand">École Virtuelle des Créatifs</div>
            <div class="footer-text">
                Cet email a été envoyé automatiquement par le système de gestion pédagogique EVC.<br>
                Pour toute question, contactez notre équipe administrative.
            </div>
            <div class="footer-copyright">
                {{ date('Y') }} École Virtuelle des Créatifs - Tous droits réservés
            </div>
        </div>
    </div>
</body>
</html>
