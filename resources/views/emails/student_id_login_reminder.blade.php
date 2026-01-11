<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de votre ID Étudiant</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;">
    <div style="max-width:640px;margin:0 auto;padding:24px;">
        <div style="background:linear-gradient(135deg,#1e3c72 0%,#2a5298 100%);border-radius:14px 14px 0 0;padding:22px 24px;color:#fff;">
            <div style="font-size:18px;font-weight:700;letter-spacing:.3px;">EVC - École Virtuelle des Créatifs</div>
            <div style="margin-top:6px;font-size:14px;opacity:.95;">Votre ID Étudiant</div>
        </div>

        <div style="background:#ffffff;border-radius:0 0 14px 14px;padding:24px;border:1px solid #e5e7eb;border-top:none;">
            <p style="margin:0 0 12px 0;color:#111827;font-size:15px;line-height:1.6;">
                Bonjour <strong>{{ $studentName ?? 'Cher(e) étudiant(e)' }}</strong>,
            </p>

            <p style="margin:0 0 16px 0;color:#374151;font-size:14px;line-height:1.6;">
                Vous pouvez vérifier votre <strong>ID Étudiant</strong> via le lien ci-dessous.
            </p>

            <div style="border-left:4px solid #4fc3f7;background:#f8fafc;border-radius:10px;padding:14px 16px;margin:0 0 18px 0;">
                <div style="font-size:13px;color:#6b7280;margin-bottom:6px;">Votre ID Étudiant</div>
                <div style="font-size:20px;color:#111827;font-weight:800;letter-spacing:.6px;">{{ $studentId }}</div>
            </div>

            <div style="text-align:center;margin:18px 0 6px 0;">
                <a href="{{ $verifyUrl }}"
                   style="display:inline-block;background:linear-gradient(135deg,#4fc3f7 0%,#29b6f6 100%);color:#0b1220;text-decoration:none;padding:12px 18px;border-radius:999px;font-weight:700;font-size:14px;">
                    Vérifier mon ID
                </a>
            </div>

            <p style="margin:10px 0 0 0;color:#6b7280;font-size:12px;line-height:1.5;">
                Si le bouton ne fonctionne pas, copiez/collez ce lien :<br>
                <span style="word-break:break-all;">{{ $verifyUrl }}</span>
            </p>

            <hr style="border:none;border-top:1px solid #e5e7eb;margin:18px 0;">

            <p style="margin:0;color:#6b7280;font-size:12px;line-height:1.5;">
                Cet email vous a été envoyé automatiquement lors de votre connexion.
            </p>
        </div>
    </div>
</body>
</html>
