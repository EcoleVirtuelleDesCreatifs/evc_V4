<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de pré-inscription</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f7fafc; padding:20px; color:#111827;">
    <div style="max-width:600px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
        <div style="background:#111827;color:#fff;padding:16px 20px;">
            <h2 style="margin:0;font-size:18px;">Confirmation de votre candidature à l’EVC</h2>
        </div>
        <div style="padding:20px;">
            <p>Bonjour {{ $pre->prenom }} {{ $pre->nom }},</p>
            <p>Merci d’avoir choisi l’École Virtuelle des Créatifs (EVC) pour votre formation.<br>
            Nous vous confirmons que nous avons bien reçu votre candidature.</p>

            <p>Notre équipe pédagogique analysera votre dossier avec attention et vous fera un retour dans un délai compris entre <strong>1h et 24h</strong>.</p>

            <p><strong>⚠️ Veuillez noter que votre candidature peut être acceptée ou rejetée :</strong></p>
            <p><strong>En cas de validation</strong>, vous recevrez un email contenant toutes les informations relatives aux modalités de paiement ainsi que les étapes pour démarrer votre formation.</p>
            <p><strong>En cas de refus</strong>, nous vous communiquerons également notre décision avec les raisons associées.</p>

            <p>Nous vous remercions encore pour votre confiance et restons à votre disposition pour toute question.</p>

            <p style="margin-top:24px;">Cordialement,<br>
            <strong>L’équipe EVC – École Virtuelle des Créatifs</strong></p>
        </div>
    </div>
</body>
</html>
