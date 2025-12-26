<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rappel - Finaliser votre don</title>
</head>
<body style="margin:0;padding:0;background:#f5f7fb;font-family:Arial,Helvetica,sans-serif;">
    <div style="max-width:680px;margin:0 auto;padding:24px;">
        <div style="background:#0f172a;color:#fff;border-radius:14px;padding:20px 22px;">
            <div style="font-size:18px;font-weight:700;">Rappel - Finaliser votre don</div>
            <div style="opacity:.9;margin-top:6px;">École Virtuelle des Créatifs (EVC)</div>
        </div>

        <div style="background:#ffffff;border-radius:14px;padding:22px;margin-top:14px;box-shadow:0 10px 30px rgba(15,23,42,.08);">
            <p style="margin:0 0 12px 0;color:#0f172a;">Bonjour <strong>{{ $donation->full_name ?? 'Cher donateur' }}</strong>,</p>

            <p style="margin:0 0 12px 0;color:#334155;line-height:1.6;">
                Nous vous contactons pour faire suite à votre intention de don enregistrée sur notre site.
                Si vous souhaitez finaliser votre don, notre équipe peut vous accompagner et vous indiquer les modalités.
            </p>

            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:14px 16px;margin:16px 0;">
                <div style="color:#0f172a;font-weight:700;margin-bottom:8px;">Récapitulatif</div>
                <div style="color:#334155;line-height:1.6;">
                    <div><strong>Référence :</strong> #{{ $donation->id }}</div>
                    <div><strong>Date :</strong> {{ optional($donation->created_at)->format('d/m/Y H:i') }}</div>
                    <div><strong>Montant :</strong>
                        @if(!is_null($donation->amount))
                            {{ number_format($donation->amount, 0, ',', ' ') }} {{ $donation->currency ?? 'XOF' }}
                        @else
                            —
                        @endif
                    </div>
                    <div><strong>Moyen :</strong> {{ $donation->payment_method ?? '—' }}</div>
                </div>
            </div>

            <p style="margin:0 0 12px 0;color:#334155;line-height:1.6;">
                Répondez simplement à cet email ou contactez-nous pour finaliser votre don.
            </p>

            <p style="margin:0;color:#334155;line-height:1.6;">
                Merci pour votre soutien,
                <br>
                <strong>L'équipe EVC</strong>
            </p>
        </div>

        <div style="text-align:center;color:#64748b;font-size:12px;margin-top:14px;">
            © {{ date('Y') }} École Virtuelle des Créatifs
        </div>
    </div>
</body>
</html>
