<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue - École Virtuelle des Créatifs</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            padding: 40px 20px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(30, 64, 175, 0.4);
        }

        .email-header {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            padding: 50px 30px;
            text-align: center;
            color: white;
            position: relative;
        }

        .email-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #f97316 0%, #fb923c 100%);
        }

        .logo {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 50px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            border: 4px solid #f97316;
        }

        .email-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .email-header p {
            font-size: 16px;
            opacity: 0.95;
        }

        .email-body {
            padding: 40px 30px;
        }

        .welcome-text {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .info-section {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            border: 2px solid #bfdbfe;
        }

        .info-section h2 {
            font-size: 20px;
            color: #2563eb;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .credential-item {
            background: white;
            border-left: 4px solid #f97316;
            padding: 15px 20px;
            margin-bottom: 15px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .credential-item:last-child {
            margin-bottom: 0;
        }

        .credential-label {
            font-size: 12px;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .credential-value {
            font-size: 16px;
            color: #212529;
            font-weight: 600;
            word-break: break-all;
        }

        .role-badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 700;
            margin-top: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .role-super-admin {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
        }

        .role-assistant {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .role-comptable {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        }

        .login-button {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: white;
            text-align: center;
            padding: 20px 30px;
            border-radius: 15px;
            text-decoration: none;
            font-weight: 700;
            font-size: 18px;
            margin: 30px 0;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(249, 115, 22, 0.3);
        }

        .login-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(249, 115, 22, 0.5);
        }

        .security-notice {
            background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%);
            border-left: 5px solid #f97316;
            padding: 18px 24px;
            border-radius: 12px;
            margin: 20px 0;
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.15);
        }

        .security-notice h3 {
            color: #c2410c;
            font-size: 16px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
        }

        .security-notice p {
            color: #9a3412;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
        }

        .features-list {
            margin: 30px 0;
        }

        .feature-item {
            display: flex;
            align-items: start;
            gap: 15px;
            margin-bottom: 15px;
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .feature-text {
            flex: 1;
        }

        .feature-text h4 {
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
        }

        .feature-text p {
            font-size: 14px;
            color: #6c757d;
            margin: 0;
        }

        .email-footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        .email-footer p {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .email-footer a {
            color: #f97316;
            text-decoration: none;
            font-weight: 600;
        }

        .email-footer a:hover {
            color: #ea580c;
        }

        .divider {
            height: 2px;
            background: linear-gradient(to right, transparent, #3b82f6, #f97316, transparent);
            margin: 35px 0;
            border-radius: 2px;
        }

        .highlight-text {
            color: #f97316;
            font-weight: 700;
        }

        @media only screen and (max-width: 600px) {
            .email-header {
                padding: 40px 20px;
            }

            .email-header h1 {
                font-size: 24px;
            }

            .email-body {
                padding: 30px 20px;
            }

            .logo {
                width: 80px;
                height: 80px;
                font-size: 40px;
            }

            .login-button {
                padding: 16px 24px;
                font-size: 16px;
            }

            .feature-icon {
                width: 45px;
                height: 45px;
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="logo">
                🎓
            </div>
            <h1>Bienvenue sur la Plateforme</h1>
            <p>École Virtuelle des Créatifs</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p class="welcome-text">
                Bonjour <strong style="color: #2563eb;">{{ $adminName }}</strong>,
            </p>

            <p class="welcome-text">
                Votre compte administrateur a été créé avec <span class="highlight-text">succès</span> sur la plateforme de l'<strong>École Virtuelle des Créatifs</strong>. Vous pouvez maintenant accéder à l'espace d'administration avec les identifiants ci-dessous.
            </p>

            <!-- Credentials Section -->
            <div class="info-section">
                <h2>
                    🔐 Vos Identifiants de Connexion
                </h2>

                <div class="credential-item">
                    <div class="credential-label">Adresse Email</div>
                    <div class="credential-value">{{ $adminEmail }}</div>
                </div>

                <div class="credential-item">
                    <div class="credential-label">Mot de Passe</div>
                    <div class="credential-value">{{ $adminPassword }}</div>
                </div>

                <div class="credential-item">
                    <div class="credential-label">Rôle Attribué</div>
                    <div class="credential-value">
                        @if($adminRole === 'super_admin')
                            <span class="role-badge role-super-admin">Super Administrateur</span>
                        @elseif($adminRole === 'assistant')
                            <span class="role-badge role-assistant">Assistant</span>
                        @elseif($adminRole === 'comptable')
                            <span class="role-badge role-comptable">Comptable</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Login Button -->
            <a href="{{ $loginUrl }}" class="login-button">
                <span style="font-size: 20px; margin-right: 8px;">🚀</span>
                Accéder à la Plateforme Maintenant
            </a>

            <!-- Security Notice -->
            <div class="security-notice">
                <h3>
                    ⚠️ Recommandations de Sécurité
                </h3>
                <p>
                    Pour la sécurité de votre compte, nous vous recommandons fortement de changer votre mot de passe lors de votre première connexion. Ne partagez jamais vos identifiants avec qui que ce soit.
                </p>
            </div>

            <div class="divider"></div>

            <!-- Features -->
            <div class="features-list">
                <h3 style="color: #333; font-size: 20px; margin-bottom: 20px;">
                    ✨ Ce que vous pouvez faire
                </h3>

                <div class="feature-item">
                    <div class="feature-icon">📊</div>
                    <div class="feature-text">
                        <h4>Tableau de Bord Complet</h4>
                        <p>Visualisez toutes les statistiques et métriques importantes en temps réel</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">👥</div>
                    <div class="feature-text">
                        <h4>Gestion des Étudiants</h4>
                        <p>Gérez les inscriptions, validations et suivi des étudiants</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">📚</div>
                    <div class="feature-text">
                        <h4>Gestion des Formations</h4>
                        <p>Créez et administrez les formations, modules et contenus pédagogiques</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">💰</div>
                    <div class="feature-text">
                        <h4>Suivi Financier</h4>
                        <p>Gérez les paiements, factures et rapports financiers</p>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            <p style="color: #6c757d; font-size: 14px; line-height: 1.6;">
                Si vous avez des questions ou besoin d'assistance, n'hésitez pas à contacter le support technique. Nous sommes là pour vous aider à démarrer.
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>© {{ date('Y') }} École Virtuelle des Créatifs. Tous droits réservés.</p>
            <p>
                <a href="{{ url('/') }}">Visiter le site web</a> •
                <a href="{{ $loginUrl }}">Se connecter</a>
            </p>
            <p style="font-size: 12px; margin-top: 15px;">
                Cet email a été envoyé automatiquement. Merci de ne pas y répondre.
            </p>
        </div>
    </div>
</body>
</html>
