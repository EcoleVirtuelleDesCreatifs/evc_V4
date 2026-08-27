<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu de paiement {{ $receipt_number }}</title>
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
        .status { display: inline-block; padding: 4px 10px; border-radius: 20px; background: #eee; font-weight: bold; text-transform: uppercase; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>EVC - École Virtuelle des Créatifs</h1>
        <p>Reçu de paiement n° {{ $receipt_number }}</p>
        <p>Établi le : {{ $issued_at }}</p>
    </div>

    <div class="info">
        <div><strong>Nom :</strong> {{ $student_name }}</div>
        <div><strong>Email :</strong> {{ $student_email }}</div>
        <div><strong>Formation :</strong> {{ $formation }}</div>
        <div><strong>ID étudiant :</strong> {{ $student_id ?? '—' }}</div>
        <div><strong>Référence :</strong> {{ $payment_reference ?? '—' }}</div>
        <div><strong>Date d'inscription :</strong> {{ $registration_date ?? '—' }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Libellé</th>
                <th>Référence</th>
                <th style="text-align: right;">Montant</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $p)
                <tr>
                    <td>{{ $p['paid_at'] ?? $p['created_at'] }}</td>
                    <td>{{ $p['installment_label'] }}</td>
                    <td>{{ $p['payment_reference'] ?? '—' }}</td>
                    <td style="text-align: right;">{{ number_format($p['amount'], 0, ',', ' ') }} FCFA</td>
                    <td>{{ $p['status_label'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>Coût formation</td>
            <td>{{ number_format($gross_total_amount, 0, ',', ' ') }} FCFA</td>
        </tr>
        @if($discount_amount > 0)
        <tr>
            <td>Remise</td>
            <td>-{{ number_format($discount_amount, 0, ',', ' ') }} FCFA</td>
        </tr>
        @endif
        <tr>
            <td>Total dû</td>
            <td>{{ number_format($total_amount, 0, ',', ' ') }} FCFA</td>
        </tr>
        <tr>
            <td>Montant payé</td>
            <td>{{ number_format($amount_paid, 0, ',', ' ') }} FCFA</td>
        </tr>
        <tr class="total-row">
            <td>Reste à payer</td>
            <td>{{ number_format($remaining, 0, ',', ' ') }} FCFA</td>
        </tr>
    </table>

    <p style="font-size: 11px; color: #666; margin-top: 30px;">
        Ce reçu atteste des paiements enregistrés dans le système EVC. En cas de contestation, veuillez contacter EVC avec la référence indiquée.
    </p>
</body>
</html>
