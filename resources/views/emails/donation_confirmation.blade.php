<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merci - EVC</title>
</head>
<body style="margin:0;padding:0;background:#0f172a;font-family:Arial,sans-serif;">
<div style="max-width:680px;margin:0 auto;padding:24px;">
    <div style="background:#111827;border:1px solid rgba(255,255,255,0.12);border-radius:14px;overflow:hidden;">
        <div style="padding:18px 22px;background:linear-gradient(135deg,#0ea5e9,#10b981);color:#fff;">
            <div style="font-size:18px;font-weight:800;">Merci pour votre intention de don</div>
            <div style="opacity:0.9;font-size:13px;">École Virtuelle des Créatifs (EVC)</div>
        </div>
        <div style="padding:22px;color:#e5e7eb;">
            <p style="margin:0 0 14px;">Bonjour {{ $data['full_name'] ?? '' }},</p>
            <p style="margin:0 0 14px;">Merci pour votre soutien. Notre équipe vous contactera rapidement avec les modalités pour finaliser votre don.</p>

            <div style="padding:14px;border-radius:12px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);">
                <div style="font-weight:800;margin-bottom:8px;">Récapitulatif</div>
                <div style="font-size:14px;color:#e5e7eb;">
                    <div><strong>Nom:</strong> {{ $data['full_name'] ?? 'N/A' }}</div>
                    <div><strong>Email:</strong> {{ $data['email'] ?? 'N/A' }}</div>
                    <div><strong>Téléphone:</strong> {{ $data['phone'] ?? 'N/A' }}</div>
                    <div><strong>Montant:</strong>
                        @if(!empty($data['amount']))
                            {{ $data['amount'] }} {{ $data['currency'] ?? 'XOF' }}
                        @else
                            Non précisé
                        @endif
                    </div>
                    <div><strong>Moyen souhaité:</strong> {{ $data['payment_method'] ?? 'Non précisé' }}</div>
                </div>
            </div>

            <p style="margin:16px 0 0;color:#9ca3af;font-size:12px;">EVC - Abidjan, Côte d'Ivoire</p>
        </div>
    </div>
</div>
</body>
</html>
