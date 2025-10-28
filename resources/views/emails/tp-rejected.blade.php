<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TP à Améliorer - EVC</title>
    <style>
        /* 🎨 DESIGN MODERNE EVC - Variables CSS */
        :root {
            --evc-primary: #2563eb;
            --evc-secondary: #ea580c;
            --evc-warning: #f59e0b;
            --evc-danger: #ef4444;
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

        /* 🎯 HEADER MODERNE EVC */
        .header {
            background: linear-gradient(135deg, var(--evc-warning), var(--evc-danger));
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

        .warning-badge {
            background: var(--evc-white);
            color: var(--evc-danger);
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            display: inline-block;
            margin-top: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        /* 📋 CONTENU PRINCIPAL */
        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--evc-dark);
            margin-bottom: 20px;
            text-align: center;
        }

        .message-card {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            border-left: 4px solid var(--evc-danger);
            position: relative;
        }

        .tp-info {
            background: var(--evc-light);
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            border: 1px solid #e5e7eb;
        }

        .tp-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--evc-primary);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .tp-description {
            color: var(--evc-text-light);
            line-height: 1.6;
            font-size: 1rem;
        }

        .rejection-reason {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            border-left: 4px solid var(--evc-warning);
        }

        .rejection-reason h3 {
            color: var(--evc-dark);
            font-size: 1.2rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .reason-text {
            background: var(--evc-white);
            padding: 20px;
            border-radius: 8px;
            color: var(--evc-text);
            line-height: 1.8;
            font-size: 1rem;
            border: 1px solid #f59e0b;
        }

        .next-steps {
            background: #dbeafe;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
        }

        .next-steps h3 {
            color: var(--evc-dark);
            font-size: 1.2rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .steps-list {
            list-style: none;
            padding: 0;
        }

        .steps-list li {
            padding: 12px 0 12px 30px;
            position: relative;
            color: var(--evc-text);
            border-bottom: 1px solid #f3f4f6;
        }

        .steps-list li:last-child {
            border-bottom: none;
        }

        .steps-list li::before {
            content: '→';
            position: absolute;
            left: 0;
            top: 10px;
            background: var(--evc-primary);
            color: var(--evc-white);
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: bold;
        }

        /* 🎨 FOOTER MODERNE */
        .footer {
            background: var(--evc-dark);
            color: var(--evc-white);
            padding: 30px;
            text-align: center;
        }

        .footer-brand {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .footer-text {
            font-size: 0.9rem;
            opacity: 0.8;
            line-height: 1.8;
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
            .greeting { font-size: 1.2rem; }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- 🎯 HEADER MODERNE EVC -->
        <div class="header">
            <div class="evc-logo">
                <div class="evc-brand">🎨 École Virtuelle des Créatifs</div>
                <div class="evc-tagline">EVC : On apprend en faisant, on réussit en sachant</div>
            </div>
            <div class="warning-badge">📝 TP à Améliorer</div>
        </div>

        <!-- 📋 CONTENU PRINCIPAL -->
        <div class="content">
            <div class="greeting">
                @php
                    $displayName = $user->name ?? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
                    if (empty($displayName)) {
                        $displayName = $user->email ?? 'Étudiant';
                    }
                @endphp
                Bonjour <strong>{{ $displayName }}</strong>,
            </div>

            <div class="message-card">
                <p style="font-size: 1.1rem; margin-bottom: 15px;">
                    Après examen de votre travail pratique (TP), notre équipe pédagogique a identifié certains points qui nécessitent des <strong>améliorations</strong>.
                </p>
                <p style="color: var(--evc-text-light);">
                    Ne vous découragez pas ! Cette démarche fait partie intégrante du processus d'apprentissage. Nous vous encourageons à réviser votre travail en tenant compte des commentaires ci-dessous.
                </p>
            </div>

            <!-- 📋 INFORMATIONS TP -->
            <div class="tp-info">
                <div class="tp-title">
                    <span>📝</span> {{ $tp->title }}
                </div>
                @if($tp->description)
                    <div class="tp-description">{!! $tp->description !!}</div>
                @endif
            </div>

            <!-- 💬 RAISONS DU REFUS -->
            <div class="rejection-reason">
                <h3>
                    <span>💬</span> Commentaires de l'équipe pédagogique
                </h3>
                <div class="reason-text">
                    {{ $rejectionReason }}
                </div>
            </div>

            <!-- 🚀 PROCHAINES ÉTAPES -->
            <div class="next-steps">
                <h3>
                    <span>🚀</span> Que faire maintenant ?
                </h3>
                <ul class="steps-list">
                    <li>Lisez attentivement les commentaires de l'équipe pédagogique</li>
                    <li>Identifiez les points spécifiques à améliorer</li>
                    <li>Apportez les corrections nécessaires à votre travail</li>
                    <li>Soumettez à nouveau votre TP corrigé</li>
                    <li>N'hésitez pas à contacter vos formateurs pour des clarifications</li>
                </ul>
            </div>

            <div style="text-align: center; margin: 30px 0; padding: 20px; background: linear-gradient(135deg, #dbeafe, #bfdbfe); border-radius: 12px;">
                <p style="font-size: 1.1rem; font-weight: 600; color: var(--evc-dark); margin: 0;">
                    💪 Chaque correction est une opportunité d'apprendre et de progresser ! 💪
                </p>
            </div>

            <div style="background: #fef3c7; padding: 20px; border-radius: 12px; margin-top: 20px;">
                <p style="font-size: 0.95rem; color: var(--evc-text); margin: 0; text-align: center;">
                    <strong>Besoin d'aide ?</strong><br>
                    Notre équipe pédagogique est là pour vous accompagner dans votre apprentissage.
                </p>
            </div>
        </div>

        <!-- 🎨 FOOTER MODERNE -->
        <div class="footer">
            <div class="footer-brand">École Virtuelle des Créatifs</div>
            <div class="footer-text">
                Cet email a été envoyé automatiquement par le système de gestion pédagogique EVC.<br>
                Pour toute question, contactez notre équipe pédagogique.
            </div>
            <div class="footer-copyright">
                © {{ date('Y') }} École Virtuelle des Créatifs - Tous droits réservés
            </div>
        </div>
    </div>
</body>
</html>
