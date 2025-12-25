<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau don - EVC</title>
</head>
<body style="margin:0;padding:0;background:#0f172a;font-family:Arial,sans-serif;">
<div style="max-width:680px;margin:0 auto;padding:24px;">
    <div style="background:#111827;border:1px solid rgba(255,255,255,0.12);border-radius:14px;overflow:hidden;">
        <div style="padding:18px 22px;background:linear-gradient(135deg,#10b981,#0ea5e9);color:#fff;">
            <div style="font-size:18px;font-weight:800;">Nouvelle intention de don</div>
            <div style="opacity:0.9;font-size:13px;">Formulaire public - EVC</div>
        </div>
        <div style="padding:22px;color:#e5e7eb;">
            <p style="margin:0 0 14px;">Un utilisateur a soumis une demande pour faire un don.</p>

            <table style="width:100%;border-collapse:collapse;font-size:14px;">
                <tr>
                    <td style="padding:10px 0;color:#9ca3af;width:210px;">Nom complet</td>
                    <td style="padding:10px 0;font-weight:700;">{{ $data['full_name'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0;color:#9ca3af;">Email</td>
                    <td style="padding:10px 0;"><a style="color:#38bdf8;" href="mailto:{{ $data['email'] ?? '' }}">{{ $data['email'] ?? 'N/A' }}</a></td>
                </tr>
                <tr>
                    <td style="padding:10px 0;color:#9ca3af;">Téléphone</td>
                    <td style="padding:10px 0;font-weight:700;">{{ $data['phone'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0;color:#9ca3af;">Montant</td>
                    <td style="padding:10px 0;font-weight:700;">
                        @if(!empty($data['amount']))
                            {{ $data['amount'] }} {{ $data['currency'] ?? 'XOF' }}
                        @else
                            Non précisé
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px 0;color:#9ca3af;">Moyen de paiement souhaité</td>
                    <td style="padding:10px 0;font-weight:700;">{{ $data['payment_method'] ?? 'Non précisé' }}</td>
                </tr>
            </table>

            @if(!empty($data['message']))
                <div style="margin-top:18px;padding:14px;border-radius:12px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);">
                    <div style="font-weight:800;margin-bottom:8px;">Message</div>
                    <div style="color:#e5e7eb;white-space:pre-line;">{{ $data['message'] }}</div>
                </div>
            @endif

            <div style="margin-top:18px;font-size:12px;color:#9ca3af;">
                Conseil: répondre rapidement au donateur pour finaliser le don (Mobile Money / Wave / virement).
            </div>
        </div>
    </div>
</div>
</body>
</html>
