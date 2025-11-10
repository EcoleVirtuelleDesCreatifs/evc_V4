<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmez votre abonnement à la WebTV EVC</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #f97316 0%, #fb923c 100%); padding: 40px 20px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px;">WebTV EVC</h1>
                            <p style="color: #ffffff; margin: 10px 0 0 0; font-size: 16px;">École Virtuelle des Créatifs</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #1f2937; margin: 0 0 20px 0; font-size: 24px;">Confirmez votre abonnement</h2>
                            
                            <p style="color: #4b5563; line-height: 1.6; margin: 0 0 20px 0;">
                                Bonjour{{ $subscriber->name ? ' ' . $subscriber->name : '' }},
                            </p>
                            
                            <p style="color: #4b5563; line-height: 1.6; margin: 0 0 20px 0;">
                                Merci de vous être abonné à la WebTV de l'École Virtuelle des Créatifs ! 
                            </p>
                            
                            <p style="color: #4b5563; line-height: 1.6; margin: 0 0 30px 0;">
                                Pour finaliser votre abonnement et recevoir des notifications lors de nos prochaines diffusions, veuillez confirmer votre adresse email en cliquant sur le bouton ci-dessous :
                            </p>
                            
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 20px 0;">
                                        <a href="{{ $verificationUrl }}" style="display: inline-block; padding: 15px 40px; background: linear-gradient(135deg, #f97316 0%, #fb923c 100%); color: #ffffff; text-decoration: none; border-radius: 50px; font-weight: bold; font-size: 16px;">
                                            Confirmer mon abonnement
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="color: #6b7280; font-size: 14px; line-height: 1.6; margin: 30px 0 0 0;">
                                Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :<br>
                                <a href="{{ $verificationUrl }}" style="color: #f97316; word-break: break-all;">{{ $verificationUrl }}</a>
                            </p>
                            
                            <div style="margin-top: 40px; padding-top: 30px; border-top: 1px solid #e5e7eb;">
                                <h3 style="color: #1f2937; margin: 0 0 15px 0; font-size: 18px;">Ce que vous recevrez :</h3>
                                <ul style="color: #4b5563; line-height: 1.8; margin: 0; padding-left: 20px;">
                                    <li>Notifications pour les nouvelles vidéos et tutoriels</li>
                                    <li>Alertes pour les diffusions en direct</li>
                                    <li>Accès anticipé à des contenus exclusifs</li>
                                    <li>Invitations à des masterclass et webinaires</li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="color: #6b7280; font-size: 14px; margin: 0 0 10px 0;">
                                École Virtuelle des Créatifs<br>
                                Abidjan, Côte d'Ivoire
                            </p>
                            <p style="color: #9ca3af; font-size: 12px; margin: 0;">
                                Vous recevez cet email car vous vous êtes abonné à la WebTV EVC.<br>
                                Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
