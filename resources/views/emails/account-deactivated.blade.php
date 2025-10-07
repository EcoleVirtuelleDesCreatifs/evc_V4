<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte désactivé</title>
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
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
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
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        .reason-box {
            background: white;
            border: 1px solid #dee2e6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
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
            background: #e9ecef;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>⚠️ Votre compte a été désactivé</h1>
    </div>
    
    <div class="content">
        <p>Bonjour <strong>{{ $studentName }}</strong>,</p>
        
        <div class="alert-box">
            <strong>⚠️ Information importante :</strong><br>
            Votre compte sur la plateforme École Virtuelle des Créatifs (EVC) a été désactivé le <strong>{{ $date }}</strong>.
        </div>
        
        <p>Suite à cette désactivation, vous ne pourrez plus accéder à votre espace étudiant.</p>
        
        <h3>📋 Raison de la désactivation :</h3>
        <div class="reason-box">
            <p style="margin: 0; white-space: pre-wrap;">{{ $reason }}</p>
        </div>
        
        <div class="contact-info">
            <h4 style="margin-top: 0;">💬 Besoin de plus d'informations ?</h4>
            <p style="margin-bottom: 0;">
                Si vous souhaitez obtenir des clarifications ou discuter de la réactivation de votre compte, 
                n'hésitez pas à contacter notre administration :
            </p>
            <ul style="margin: 10px 0;">
                <li>Email : <a href="mailto:info@ecolevirtuelledescreatifs.com">info@ecolevirtuelledescreatifs.com</a></li>
                <li>Téléphone : <a href="tel:+2250747259507">+225 07 47 25 95 07</a></li>
            </ul>
        </div>
        
        <p style="margin-top: 20px;">
            <strong>Note :</strong> Si vous pensez qu'il s'agit d'une erreur, veuillez contacter immédiatement l'administration.
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
