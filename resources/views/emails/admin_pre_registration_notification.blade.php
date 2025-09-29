<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle pré-inscription</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f7fafc; padding:20px; color:#111827;">
    <div style="max-width:600px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
        <div style="background:#111827;color:#fff;padding:16px 20px;">
            <h2 style="margin:0;font-size:18px;">Nouvelle candidature reçue</h2>
        </div>
        <div style="padding:20px;">
            <p>Bonjour,</p>
            <p>Une nouvelle candidature vient d’être soumise via le formulaire en ligne.</p>

            <p><strong>Informations principales :</strong></p>
            <ul>
                <li><strong>Nom du candidat :</strong> {{ $pre->prenom }} {{ $pre->nom }}</li>
                <li><strong>Email :</strong> {{ $pre->email }}</li>
                <li><strong>Date de soumission :</strong> {{ $pre->created_at->format('Y-m-d H:i') }}</li>
            </ul>

            <p>Nous vous invitons à consulter l’espace d’administration pour examiner les détails complets de cette candidature.</p>

            <p style="margin-top:24px;">Cordialement,<br>
            <strong>Le système de notification automatique</strong></p>
        </div>
    </div>
</body>
</html>
