<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet Validé - EVC</title>
    <style>
        /* 🎨 DESIGN MODERNE EVC - Variables CSS */
        :root {
            --evc-primary: #2563eb;
            --evc-secondary: #ea580c;
            --evc-success: #10b981;
            --evc-accent: #f59e0b;
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
            background: linear-gradient(135deg, var(--evc-primary), var(--evc-secondary));
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

        .success-badge {
            background: var(--evc-success);
            color: var(--evc-white);
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            display: inline-block;
            margin-top: 20px;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
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
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            border-left: 4px solid var(--evc-primary);
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
            color: var(--evc-primary);
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

        .validation-info {
            background: linear-gradient(135deg, var(--evc-success), #059669);
            color: var(--evc-white);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            margin: 25px 0;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.2);
        }

        .next-steps {
            background: var(--evc-white);
            border: 2px solid #f3f4f6;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
        }

        .next-steps h3 {
            color: var(--evc-primary);
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
            padding: 10px 0;
            padding-left: 30px;
            position: relative;
            color: var(--evc-text);
            border-bottom: 1px solid #f3f4f6;
        }

        .steps-list li:last-child {
            border-bottom: none;
        }

        .steps-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            top: 10px;
            background: var(--evc-success);
            color: var(--evc-white);
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: bold;
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
            <div class="success-badge">✨ Projet Validé avec Succès ✨</div>
        </div>

        <!-- 📋 CONTENU PRINCIPAL -->
        <div class="content">
            <div class="greeting">
                Félicitations <strong>{{ $user->name ?? $user->first_name ?? 'Étudiant' }}</strong> ! 🎉
            </div>

            <div class="message-card">
                <p style="font-size: 1.1rem; margin-bottom: 15px;">
                    Nous avons le plaisir de vous informer que votre projet a été <strong>validé avec succès</strong> par notre équipe pédagogique EVC.
                </p>
                <p style="color: var(--evc-text-light);">
                    Cette validation confirme que votre travail créatif répond parfaitement aux standards d'excellence de l'École Virtuelle des Créatifs.
                </p>
            </div>

            <!-- 📋 INFORMATIONS PROJET -->
            <div class="project-info">
                <div class="project-title">
                    <span>🎨</span> {{ $projectTitle }}
                </div>
                @if($projectDescription)
                    <div class="project-description">{{ $projectDescription }}</div>
                @endif
            </div>

            <!-- ✅ VALIDATION INFO -->
            <div class="validation-info">
                <div style="font-size: 1.2rem; font-weight: 600; margin-bottom: 8px;">
                    📅 Validé le {{ $validationDate }}
                </div>
                <div style="opacity: 0.9;">
                    Par l'équipe pédagogique EVC
                </div>
            </div>

            <!-- 🚀 PROCHAINES ÉTAPES -->
            <div class="next-steps">
                <h3>
                    <span>🚀</span> Prochaines étapes
                </h3>
                <ul class="steps-list">
                    <li>Votre projet est officiellement validé dans votre dossier EVC</li>
                    <li>Cette validation compte pour votre évaluation finale</li>
                    <li>Vous pouvez poursuivre avec vos prochains projets créatifs</li>
                    <li>Votre progression est automatiquement mise à jour</li>
                    <li>Accès aux ressources avancées de la plateforme EVC</li>
                </ul>
            </div>

            <div style="text-align: center; margin: 30px 0; padding: 20px; background: linear-gradient(135deg, #fef3c7, #fde68a); border-radius: 12px;">
                <p style="font-size: 1.1rem; font-weight: 600; color: var(--evc-dark); margin: 0;">
                    🌟 Continuez sur cette excellente voie créative ! 🌟
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
