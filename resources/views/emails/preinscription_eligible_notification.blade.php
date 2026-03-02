<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éligibilité - EVC</title>
</head>
<body style="margin:0; padding:0; font-family: Arial, sans-serif; background:#f5f7fb;">
    <div style="max-width: 680px; margin: 0 auto; padding: 24px;">
        <div style="background:#ffffff; border-radius: 14px; padding: 24px; box-shadow: 0 10px 30px rgba(15,23,42,0.08);">
            <div style="font-weight: 900; font-size: 18px; color:#0f172a;">École Virtuelle des Créatifs (EVC)</div>
            <div style="margin-top: 4px; color:#64748b; font-size: 13px;">Notification d’éligibilité</div>

            <div style="margin-top: 18px; font-size: 15px; color:#0f172a;">
                Bonjour <strong>{{ $candidateName }}</strong>,
            </div>

            <div style="margin-top: 12px; font-size: 14px; color:#334155; line-height: 1.6;">
                Nous avons le plaisir de vous informer que votre candidature est <strong>éligible</strong> pour intégrer l’équipe <strong>Futur RéCréatif</strong>.
            </div>

            <div style="margin-top: 12px; font-size: 14px; color:#334155; line-height: 1.6;">
                <strong>Formation :</strong> {{ $formationName }}<br>
                @if(!empty($paymentDate))
                    <strong>Date de paiement souhaitée :</strong> {{ $paymentDate }}
                @endif
            </div>

            <div style="margin-top: 12px; font-size: 14px; color:#334155; line-height: 1.6;">
                Merci de vous préparer à effectuer votre paiement à la date choisie afin de confirmer votre place.
            </div>

            <div style="margin-top: 18px; padding-top: 14px; border-top: 1px solid #e2e8f0; color:#64748b; font-size: 12px; line-height: 1.5;">
                Si vous avez des questions, répondez simplement à cet email.
            </div>
        </div>
    </div>
</body>
</html>
