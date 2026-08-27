<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Devis {{ $quote_number }}</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 13px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 26px; color: #1a56db; }
        .header p { margin: 5px 0; color: #666; }
        .info { margin-bottom: 30px; }
        .info div { margin-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid #ddd; }
        th { background: #f0f4f8; }
        .totals { width: 300px; margin-left: auto; }
        .totals td { border: none; padding: 5px 0; }
        .totals td:last-child { text-align: right; font-weight: bold; }
        .total-row { font-size: 15px; color: #1a56db; }
    </style>
</head>
<body>
    <div class="header">
        <h1>EVC - École Virtuelle des Créatifs</h1>
        <p>Devis de formation n° {{ $quote_number }}</p>
        <p>Établi le : {{ $issued_at }} — Valable jusqu'au : {{ $valid_until }}</p>
    </div>

    <div class="info">
        <div><strong>Candidat :</strong> {{ $candidate_name }}</div>
        <div><strong>Email :</strong> {{ $candidate_email }}</div>
        <div><strong>Téléphone :</strong> {{ $candidate_phone ?? '—' }}</div>
        <div><strong>Formation :</strong> {{ $formation }}</div>
        @if($level)
            <div><strong>Niveau :</strong> {{ $level }}</div>
        @endif
        <div><strong>Durée :</strong> {{ $duration }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Désignation</th>
                <th>Détail</th>
                <th style="text-align: right;">Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item['label'] }}</td>
                    <td>{{ $item['detail'] }}</td>
                    <td style="text-align: right;">{{ number_format($item['amount'], 0, ',', ' ') }} FCFA</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr class="total-row">
            <td>Total TTC</td>
            <td>{{ number_format($total_amount, 0, ',', ' ') }} FCFA</td>
        </tr>
    </table>

    <p style="font-size: 11px; color: #666; margin-top: 30px;">
        Ce devis est valable 30 jours à compter de sa date d'établissement. Pour toute question, contactez l'administration EVC.
    </p>
</body>
</html>
