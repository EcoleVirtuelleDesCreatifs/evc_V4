<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue à l'École Virtuelle des Créatifs</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #003366;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #666;
            font-size: 16px;
        }
        .welcome-message {
            background: linear-gradient(135deg, #003366, #3399ff);
            color: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            margin: 30px 0;
        }
        .welcome-message h2 {
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        .credentials-box {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 25px;
            margin: 25px 0;
        }
        .credentials-box h3 {
            color: #003366;
            margin-top: 0;
            font-size: 18px;
        }
        .credential-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            border-left: 4px solid #3399ff;
        }
        .credential-label {
            font-weight: bold;
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
        }
        .credential-value {
            font-size: 16px;
            color: #333;
            font-family: 'Courier New', monospace;
            background: #f8f9fa;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 5px;
        }
        .formations-list {
            background: #e8f5e8;
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
        }
        .formations-list h3 {
            color: #28a745;
            margin-top: 0;
        }
        .formation-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            border-left: 4px solid #28a745;
            display: flex;
            align-items: center;
        }
        .formation-icon {
            font-size: 24px;
            margin-right: 15px;
        }
        .formation-details h4 {
            margin: 0 0 5px 0;
            color: #333;
        }
        .formation-details p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        .login-button {
            text-align: center;
            margin: 30px 0;
        }
        .btn-login {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            font-size: 16px;
            display: inline-block;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }
        .important-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .important-note h4 {
            color: #856404;
            margin-top: 0;
        }
        .support-info {
            background: #e3f2fd;
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        .support-info h4 {
            color: #1976d2;
            margin-top: 0;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">🎓 École Virtuelle des Créatifs</div>
            <div class="subtitle">Votre parcours créatif commence ici</div>
        </div>

        <!-- Welcome Message -->
        <div class="welcome-message">
            <h2>🎉 Bienvenue {{ $student['first_name'] }} {{ $student['last_name'] }} !</h2>
            <p>Félicitations ! Votre inscription a été validée avec succès.</p>
        </div>

        <!-- Credentials -->
        <div class="credentials-box">
            <h3>🔐 Vos Identifiants de Connexion</h3>
            <div class="credential-item">
                <div class="credential-label">Email de connexion</div>
                <div class="credential-value">{{ $student['email'] }}</div>
            </div>
            <div class="credential-item">
                <div class="credential-label">Mot de passe temporaire</div>
                <div class="credential-value">{{ $temporaryPassword }}</div>
            </div>
        </div>

        <!-- Formations -->
        <div class="formations-list">
            <h3>📚 Vos Formations Inscrites</h3>
            @foreach($formations as $formation)
                @php
                    $formationDetails = [
                        'design-graphique' => [
                            'icon' => '🎨',
                            'title' => 'Design Graphique',
                            'description' => '4 mois - Photoshop, Illustrator, InDesign, Business Strategy',
                            'price' => '75 000 FCFA'
                        ],
                        'community-management' => [
                            'icon' => '👥',
                            'title' => 'Community Manager',
                            'description' => '3 mois - Gestion réseaux sociaux, Stratégie social media, Création de contenu',
                            'price' => '100 000 FCFA'
                        ],
                        'gestion-informatique' => [
                            'icon' => '💻',
                            'title' => 'Gestion Informatique',
                            'description' => '2 mois - Bureautique, Environnement professionnel',
                            'price' => '150 000 FCFA'
                        ],
                        'intelligence-artificielle' => [
                            'icon' => '🤖',
                            'title' => 'Intelligence Artificielle',
                            'description' => '1 mois - Outils IA pertinents, Process et Pratiques',
                            'price' => '50 000 FCFA'
                        ]
                    ];
                    $details = $formationDetails[$formation] ?? ['icon' => '📖', 'title' => $formation, 'description' => 'Formation spécialisée'];
                @endphp
                <div class="formation-item">
                    <div class="formation-icon">{{ $details['icon'] }}</div>
                    <div class="formation-details">
                        <h4>{{ $details['title'] }}</h4>
                        <p>{{ $details['description'] }}</p>
                        @if(isset($details['price']))
                            <p><strong>Prix : {{ $details['price'] }}</strong></p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Confirmation Button -->
        <div class="login-button">
            <a href="{{ $confirmationUrl }}" class="btn-login" style="background: linear-gradient(135deg, #ff6b6b, #ee5a24); margin-bottom: 15px;">
                ✅ Confirmer mon Inscription
            </a>
            <p style="margin: 10px 0; color: #666; font-size: 14px;">
                <strong>Important :</strong> Cliquez sur ce bouton pour confirmer votre inscription et compléter votre profil.
            </p>
            <a href="{{ $loginUrl }}" class="btn-login" style="background: linear-gradient(135deg, #28a745, #20c997);">
                🚀 Accéder à ma Plateforme
            </a>
        </div>

        <!-- Important Note -->
        <div class="important-note">
            <h4>⚠️ Important</h4>
            <p><strong>Changez votre mot de passe</strong> dès votre première connexion pour sécuriser votre compte.</p>
            <p>Conservez cet email précieusement, il contient vos identifiants de connexion.</p>
        </div>

        <!-- Support -->
        <div class="support-info">
            <h4>💬 Besoin d'Aide ?</h4>
            <p>Notre équipe support est là pour vous accompagner :</p>
            <p>
                📧 Email : <strong>info@ecolevirtuelledescreatifs.com</strong><br>
                📱 WhatsApp : <strong>+225 07 17 25 86 02</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© {{ date('Y') }} École Virtuelle des Créatifs - Tous droits réservés</p>
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre directement.</p>
        </div>
    </div>
</body>
</html>
