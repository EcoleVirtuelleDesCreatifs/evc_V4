<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de votre mot de passe - EVC 2024</title>
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
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #003366 0%, #3399ff 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #003366;
        }
        
        .message {
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.7;
        }
        
        .reset-button {
            text-align: center;
            margin: 40px 0;
        }
        
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #003366 0%, #3399ff 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 51, 102, 0.3);
        }
        
        .security-info {
            background: #e3f2fd;
            border-left: 4px solid #3399ff;
            padding: 20px;
            margin: 30px 0;
            border-radius: 5px;
        }
        
        .security-info h3 {
            margin: 0 0 10px 0;
            color: #003366;
            font-size: 16px;
        }
        
        .security-info p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }
        
        .footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        
        .footer p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }
        
        .contact-info {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        
        .contact-info a {
            color: #3399ff;
            text-decoration: none;
        }
        
        .expiry-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        
        .expiry-notice strong {
            color: #856404;
        }
        
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            
            .content {
                padding: 30px 20px;
            }
            
            .header {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🔐 Réinitialisation de mot de passe</h1>
            <p>École Virtuelle des Créatifs</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Bonjour {{ $user->first_name ?? 'Étudiant(e)' }},
            </div>

            <div class="message">
                <p>Vous avez demandé la réinitialisation de votre mot de passe pour votre compte EVC 2024.</p>
                
                <p>Pour créer un nouveau mot de passe sécurisé, cliquez sur le bouton ci-dessous :</p>
            </div>

            <div class="reset-button">
                <a href="{{ $resetUrl }}" class="btn">
                    🔑 Réinitialiser mon mot de passe
                </a>
            </div>

            <div class="expiry-notice">
                <strong>⏰ Important :</strong> Ce lien est valide pendant 24 heures seulement.
            </div>

            <div class="security-info">
                <h3>🛡️ Informations de sécurité</h3>
                <p><strong>Si vous n'avez pas demandé cette réinitialisation :</strong></p>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Ignorez cet email - votre mot de passe actuel reste inchangé</li>
                    <li>Vérifiez la sécurité de votre compte</li>
                    <li>Contactez notre support si vous avez des inquiétudes</li>
                </ul>
                
                <p style="margin-top: 15px;"><strong>Conseils pour un mot de passe sécurisé :</strong></p>
                <ul style="margin: 5px 0; padding-left: 20px;">
                    <li>Au moins 8 caractères</li>
                    <li>Mélange de majuscules, minuscules, chiffres et symboles</li>
                    <li>Unique pour votre compte EVC</li>
                </ul>
            </div>

            <div class="message">
                <p><strong>Problème avec le bouton ?</strong><br>
                Copiez et collez ce lien dans votre navigateur :</p>
                <p style="word-break: break-all; background: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace; font-size: 14px;">
                    {{ $resetUrl }}
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>École Virtuelle des Créatifs</strong></p>
            <p>Formation professionnelle en Design Graphique</p>
            
            <div class="contact-info">
                <p><strong>Besoin d'aide ?</strong></p>
                <p>📧 Email : <a href="mailto:info@ecolevirtuelledescreatifs.com">info@ecolevirtuelledescreatifs.com</a></p>
                <p>📱 WhatsApp : <a href="https://wa.me/22507172586">+225 07 17 25 86 02</a></p>
            </div>
            
            <p style="margin-top: 20px; font-size: 12px; color: #999;">
                Cet email a été envoyé automatiquement. Merci de ne pas répondre à cette adresse.
            </p>
        </div>
    </div>
</body>
</html>
