<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éligibilité - EVC</title>
</head>
<body style="margin:0; padding:0; font-family: Arial, sans-serif; background:#f5f7fb;">
    <div style="max-width: 680px; margin: 0 auto; padding: 24px;">
        <div style="background:#ffffff; border-radius: 14px; padding: 0; box-shadow: 0 10px 30px rgba(15,23,42,0.08); overflow:hidden;">
            <div style="padding: 20px 24px; background: linear-gradient(135deg, #0b3a6e 0%, #0a2b50 100%); border-bottom: 4px solid #f97316;">
                <div style="font-weight: 900; font-size: 18px; color:#ffffff; letter-spacing: 0.2px;">École Virtuelle des Créatifs (EVC)</div>
                <div style="margin-top: 6px;">
                    <span style="display:inline-block; padding: 6px 10px; border-radius: 999px; background:#f97316; color:#ffffff; font-size: 12px; font-weight: 800; letter-spacing: 0.4px;">ÉLIGIBLE</span>
                    <span style="display:inline-block; margin-left: 8px; color:rgba(255,255,255,0.85); font-size: 13px;">Notification d’éligibilité</span>
                </div>
            </div>

            <div style="padding: 24px;">

            <div style="margin-top: 18px; font-size: 15px; color:#0f172a;">
                Bonjour <strong>{{ $candidateName }}</strong>,
            </div>

            <div style="margin-top: 12px; font-size: 14px; color:#334155; line-height: 1.7;">
                Suite à l’étude attentive de votre dossier et à votre test d’éligibilité, nous avons le plaisir de vous informer que votre candidature a été déclarée <strong style="color:#0b3a6e;">éligible</strong> pour intégrer l’École Virtuelle des Créatifs (EVC).
            </div>

            <div style="margin-top: 12px; font-size: 14px; color:#334155; line-height: 1.7;">
                Votre profil répond aux critères requis pour rejoindre notre programme de formation et développer des compétences professionnelles concrètes au sein de notre communauté de créatifs.
            </div>

            <div style="margin-top: 16px; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
                <div style="padding: 12px 14px; background:#fff7ed; border-bottom: 1px solid #fed7aa;">
                    <div style="font-size: 12px; color:#9a3412; font-weight: 800; letter-spacing: 0.3px;">VOS INFORMATIONS</div>
                </div>
                <div style="padding: 12px 14px; font-size: 14px; color:#0f172a; line-height: 1.7;">
                    <div><strong>Formation :</strong> {{ $formationName }}</div>
                    @if(!empty($paymentDate))
                        <div style="margin-top: 6px;"><strong>Date de paiement souhaitée :</strong> {{ $paymentDate }}</div>
                    @endif
                </div>
            </div>

            <div style="margin-top: 12px; font-size: 14px; color:#334155; line-height: 1.7;">
                Afin de confirmer définitivement votre admission et réserver votre place, nous vous invitons à effectuer votre paiement à la date indiquée ou avant.
            </div>

            <div style="margin-top: 12px; padding: 12px 14px; border-radius: 12px; background:#f8fafc; border: 1px solid #e2e8f0;">
                <div style="font-size: 13px; color:#0f172a; font-weight: 800;">Prochaine étape</div>
                <div style="margin-top: 8px; font-size: 13px; color:#334155; line-height: 1.7;">
                    <div style="margin-bottom: 4px;">1. Préparez votre paiement</div>
                    <div style="margin-bottom: 4px;">2. Effectuez le paiement à la date indiquée (ou avant)</div>
                    <div>3. Confirmez votre admission et réservez votre place</div>
                </div>
            </div>

            <div style="margin-top: 12px; font-size: 14px; color:#334155; line-height: 1.7;">
                Les paiements peuvent se faire via les différents moyens de paiement disponibles.
                Pour toute information complémentaire ou pour recevoir les modalités de paiement, contactez-nous via WhatsApp : <strong style="color:#0b3a6e;">+225 07 47 25 95 07</strong>.
            </div>

            @if(!empty($plaquetteUrl))
                <div style="margin-top: 14px;">
                    <a href="{{ $plaquetteUrl }}" target="_blank" style="display:inline-block; background:#0b3a6e; color:#ffffff; text-decoration:none; padding: 12px 16px; border-radius: 12px; font-size: 14px; font-weight: 800;">
                        Télécharger la plaquette{{ !empty($plaquetteTitle) ? ' : ' . $plaquetteTitle : '' }}
                    </a>
                </div>
            @endif

            <div style="margin-top: 14px;">
                <a href="https://wa.me/2250747259507" target="_blank" style="display:inline-block; background:#f97316; color:#ffffff; text-decoration:none; padding: 12px 16px; border-radius: 12px; font-size: 14px; font-weight: 800;">
                    Contacter sur WhatsApp
                </a>
            </div>

            <div style="margin-top: 12px; font-size: 14px; color:#334155; line-height: 1.7;">
                Passé ce délai de paiement, votre place sera automatiquement attribuée à un autre candidat en attente.
            </div>

            <div style="margin-top: 12px; font-size: 14px; color:#334155; line-height: 1.7;">
                Nous sommes heureux de vous compter parmi les futurs apprenants de l’EVC et impatients de vous accompagner dans votre montée en compétences vers un avenir professionnel prometteur.
            </div>

            <div style="margin-top: 18px; padding-top: 14px; border-top: 1px solid #e2e8f0; color:#64748b; font-size: 12px; line-height: 1.6;">
                Si vous avez des questions, vous pouvez également répondre directement à cet email.
                <div style="margin-top: 10px; color:#0f172a; font-size: 13px;">
                    Cordialement,<br>
                    <strong>L’équipe de l’École Virtuelle des Créatifs (EVC)</strong>
                </div>
            </div>
            </div>
        </div>
    </div>
</body>
</html>
