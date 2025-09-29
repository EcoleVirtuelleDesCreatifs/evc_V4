<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Un étudiant a finalisé la création de son compte</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f7fafc; padding:20px; color:#111827;">
    <div style="max-width:600px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
        <div style="background:#111827;color:#fff;padding:16px 20px;">
            <h2 style="margin:0;font-size:18px;">Création de compte finalisée</h2>
        </div>
        <div style="padding:20px;">
            <p>Bonjour,</p>
            <p>Un étudiant vient de finaliser la création de son compte sur la plateforme EVC.</p>

            <p><strong>Informations:</strong></p>
            <ul>
                <li><strong>Nom:</strong> {{ ($user->first_name ?? '') . ' ' . ($user->last_name ?? '') }}</li>
                <li><strong>Email:</strong> {{ $user->email }}</li>
                <li><strong>Date:</strong> {{ now()->format('Y-m-d H:i') }}</li>
            </ul>

            <p>Vous pouvez maintenant lui attribuer l'accès à son espace et poursuivre le processus d'onboarding si nécessaire.</p>

            <p style="margin-top:24px;">Cordialement,<br>
            <strong>Notifications EVC</strong></p>
        </div>
    </div>
</body>
</html>
