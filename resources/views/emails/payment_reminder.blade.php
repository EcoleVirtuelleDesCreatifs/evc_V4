<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rappel de Paiement - EVC</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .payment-card {
            background: #fff5f5;
            border-left: 4px solid #f5576c;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .payment-detail {
            margin: 10px 0;
            font-size: 16px;
        }
        .payment-detail strong {
            color: #f5576c;
        }
        .amount-due {
            font-size: 32px;
            font-weight: bold;
            color: #f5576c;
            text-align: center;
            margin: 20px 0;
        }
        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .payment-methods {
            background: #f9f9f9;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .payment-methods h3 {
            color: #333;
            margin-top: 0;
        }
        .method {
            margin: 10px 0;
            padding: 10px;
            background: white;
            border-radius: 5px;
        }
        .footer {
            background: #333;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 14px;
        }
        .footer a {
            color: #f5576c;
            text-decoration: none;
        }
        .urgent {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>⚠️ Rappel de Paiement</h1>
            <p>École Virtuelle des Créatifs</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Bonjour <strong>{{ $student['first_name'] }} {{ $student['last_name'] }}</strong>,
            </div>

            <p>
                Nous espérons que votre formation se déroule bien. Cependant, nous constatons que
                le paiement de vos frais de formation n'a pas encore été effectué.
            </p>

            <!-- Payment Card -->
            <div class="payment-card">
                <div class="payment-detail">
                    <strong>Formation :</strong> {{ $student['formation'] }}
                </div>
                <div class="payment-detail">
                    <strong>Date d'inscription :</strong> {{ \Carbon\Carbon::parse($student['created_at'])->format('d/m/Y') }}
                </div>
                <div class="payment-detail">
                    <strong>Montant déjà payé :</strong> {{ number_format($student['amount_paid'], 0, ',', ' ') }} FCFA
                </div>
            </div>

            <!-- Amount Due -->
            <div class="amount-due">
                {{ number_format($student['remaining'], 0, ',', ' ') }} FCFA
            </div>
            <p style="text-align: center; color: #666; margin-top: -10px;">
                <em>Montant restant à payer</em>
            </p>

            <!-- Urgent Notice -->
            <div class="urgent">
                <strong>⏰ Action requise :</strong> Pour continuer à bénéficier de votre formation
                et accéder à tous les contenus, merci de régulariser votre situation dans les plus brefs délais.
                <br>
                Vous pouvez effectuer votre paiement directement depuis votre espace étudiant : <strong>Gestion de formation &gt; Paiement</strong>.
            </div>

            <!-- Payment Methods -->
            <div class="payment-methods">
                <h3>💳 Nos Moyens de Paiement</h3>

                <div class="method">
                    <strong>📱 Par Mobile Money</strong><br>
                    🟠 Orange Money : <strong>(+225) 07 47 25 95 07</strong><br>
                    💙 Wave : <strong>(+225) 07 17 25 86 02</strong><br>
                    🟡 MTN Mobile Money : <strong>(+225) 05 45 99 62 15</strong><br>
                    🔴 Moov Money : <strong>(+225) 01 52 01 65 70</strong>
                </div>

                <div class="method">
                    <strong>🏦 Par Virement Bancaire (À la demande)</strong><br>
                    💳 VISA<br>
                    💙 PayPal<br>
                    <em>Contactez-nous pour obtenir les coordonnées bancaires</em>
                </div>

                <div class="method">
                    <strong>🌍 Par Transfert Classique (À la demande)</strong><br>
                    💛 Western Union<br>
                    🔴 MoneyGram<br>
                    <em>Contactez-nous pour obtenir les informations de réception</em>
                </div>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <strong>ℹ️ Important :</strong> Après votre paiement, merci de nous envoyer
                le reçu par email à <a href="mailto:info@ecolevirtuelledescreatifs.com">info@ecolevirtuelledescreatifs.com</a>
                ou via WhatsApp au <strong>+225 07 47 25 95 07</strong>.
            </div>

            <!-- CTA Button -->
            <div style="text-align: center;">
                <a href="mailto:info@ecolevirtuelledescreatifs.com?subject=Paiement Formation - {{ $student['first_name'] }} {{ $student['last_name'] }}" class="cta-button">
                    Contacter le Service Paiements
                </a>
            </div>

            <div style="text-align: center; margin-top: 15px;">
                <p style="color: #666;">
                    📞 Ou appelez-nous directement : <strong style="color: #f5576c;">(+225) 07 17 25 86 02</strong>
                </p>
            </div>

            <p style="margin-top: 30px;">
                Pour toute question concernant votre paiement, n'hésitez pas à nous contacter.
            </p>

            <p>
                Cordialement,<br>
                <strong>L'équipe EVC</strong><br>
                École Virtuelle des Créatifs
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                <strong>École Virtuelle des Créatifs (EVC)</strong><br>
                📍 Abidjan, Palmeraie<br>
                📞 (+225) 07 17 25 86 02<br>
                📱 WhatsApp : +225 07 47 25 95 07<br>
                📧 <a href="mailto:info@ecolevirtuelledescreatifs.com">info@ecolevirtuelledescreatifs.com</a> | <a href="mailto:contact@ecolevirtuelledescreatifs.com">contact@ecolevirtuelledescreatifs.com</a><br>
                🌐 <a href="https://www.ecolevirtuelledescreatifs.com">www.ecolevirtuelledescreatifs.com</a>
            </p>
            <p style="margin-top: 10px;">
                <strong>Suivez-nous :</strong><br>
                📘 Facebook | 📷 Instagram | 💼 LinkedIn | 🎵 TikTok | 📺 YouTube<br>
                <strong>@EcoleVirtuelledesCreatifs</strong>
            </p>
            <p style="font-size: 12px; color: #999; margin-top: 15px;">
                Cet email a été envoyé automatiquement. Merci de ne pas y répondre directement.<br>
                Pour toute question, contactez-nous à <a href="mailto:info@ecolevirtuelledescreatifs.com">info@ecolevirtuelledescreatifs.com</a> ou via WhatsApp au +225 07 47 25 95 07
            </p>
        </div>
    </div>
</body>
</html>
