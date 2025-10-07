<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte réactivé</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .alert-box {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .info-box {
            background: white;
            border: 1px solid #dee2e6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .btn-login {
            display: inline-block;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .contact-info {
            background: #e7f3ff;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>✅ Votre compte a été réactivé</h1>
    </div>
    
    <div class="content">
        <p>Bonjour <strong>{{ $studentName }}</strong>,</p>
        
        <div class="alert-box">
            <strong>✅ Bonne nouvelle !</strong><br>
            Votre compte sur la plateforme École Virtuelle des Créatifs (EVC) a été réactivé le <strong>{{ $date }}</strong>.
        </div>
        
        <p>Vous pouvez à nouveau accéder à votre espace étudiant et reprendre vos formations.</p>
        
        <div class="info-box">
            <h4 style="margin-top: 0;">🎓 Vous pouvez maintenant :</h4>
            <ul style="margin-bottom: 0;">
                <li>Accéder à votre tableau de bord</li>
                <li>Consulter vos cours et formations</li>
                <li>Soumettre vos travaux pratiques (TP)</li>
                <li>Participer aux activités de l'école</li>
                <li>Télécharger vos documents</li>
            </ul>
        </div>
        
        <div style="text-align: center;">
            <a href="https://www.ecolevirtuelledescreatifs.com/login" class="btn-login">
                🔓 Se connecter à mon compte
            </a>
        </div>
        
        <div class="contact-info">
            <h4 style="margin-top: 0;">💬 Besoin d'aide ?</h4>
            <p style="margin-bottom: 0;">
                Si vous avez des questions, n'hésitez pas à contacter notre support :
            </p>
            <ul style="margin: 10px 0;">
                <li>Email : <a href="mailto:info@ecolevirtuelledescreatifs.com">info@ecolevirtuelledescreatifs.com</a></li>
                <li>Téléphone : <a href="tel:+2250747259507">+225 07 47 25 95 07</a></li>
            </ul>
        </div>
        
        <p style="margin-top: 20px;">
            <strong>Bienvenue de nouveau parmi nous !</strong><br>
            Nous sommes ravis de vous revoir et vous souhaitons une excellente continuation dans vos études.
        </p>
    </div>
    
    <div class="footer">
        <p>
            <strong>École Virtuelle des Créatifs (EVC)</strong><br>
            Votre avenir créatif commence ici<br>
            <a href="https://www.ecolevirtuelledescreatifs.com/">www.ecolevirtuelledescreatifs.com</a>
        </p>
        <p style="font-size: 12px; color: #999;">
            Ceci est un email automatique, merci de ne pas y répondre directement.
        </p>
    </div>
</body>
</html>
