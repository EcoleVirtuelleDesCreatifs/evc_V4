<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Félicitations ! Créez votre compte EVC</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f7fafc; padding:20px; color:#111827;">
    <div style="max-width:600px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
        <div style="background:#111827;color:#fff;padding:16px 20px;">
            <h2 style="margin:0;font-size:18px;">Félicitations ! Votre candidature a été acceptée</h2>
        </div>
        <div style="padding:20px;">
            <p>Bonjour {{ $pre->prenom }} {{ $pre->nom }},</p>
            <p>Nous sommes heureux de vous annoncer que votre candidature à l’<strong>École Virtuelle des Créatifs (EVC)</strong> a été <strong>acceptée</strong>.</p>

            <p>Pour finaliser votre inscription, veuillez cliquer sur le lien ci-dessous afin de <strong>créer votre compte</strong> et définir votre mot de passe. Ce lien est <strong>valide 24 heures</strong>.</p>

            <p style="margin:16px 0;">
                <a href="{{ $registerUrl }}" style="display:inline-block;background:#10b981;color:#fff;text-decoration:none;padding:12px 18px;border-radius:6px;">Créer mon compte</a>
            </p>

            <p>Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :</p>
            <p style="word-break: break-all;">{{ $registerUrl }}</p>

            <p style="margin-top:24px;">Bienvenue à l’EVC !</p>
            <p><strong>L’équipe EVC – École Virtuelle des Créatifs</strong></p>
        </div>
    </div>
</body>
</html>
