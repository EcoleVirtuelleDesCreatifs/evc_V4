<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 24px; color: #ff6b35; }
        .header p { margin: 5px 0; color: #666; }
        .info { margin-bottom: 30px; }
        .info div { margin-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { text-align: left; padding: 10px; border-bottom: 1px solid #ddd; }
        th { background: #f8f8f8; }
        .totals { width: 300px; margin-left: auto; }
        .totals td { border: none; padding: 6px 0; }
        .totals td:last-child { text-align: right; font-weight: bold; }
        .total-row { font-size: 16px; color: #ff6b35; }
        .status { display: inline-block; padding: 6px 12px; border-radius: 20px; background: #eee; font-weight: bold; text-transform: uppercase; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>EVC STORE</h1>
        <p>Facture n° {{ $order->order_number }}</p>
        <p>Date : {{ $order->created_at->format('d/m/Y H:i') }}</p>
        <span class="status">{{ str_replace('_', ' ', $order->status) }}</span>
    </div>

    <div class="info">
        <div><strong>Client :</strong> {{ $order->prenoms }} {{ $order->nom }}</div>
        <div><strong>Téléphone :</strong> {{ $order->numero }}</div>
        <div><strong>Adresse / Lieu :</strong> {{ $order->lieu }}</div>
        <div><strong>Mode de réception :</strong> {{ $order->delivery_mode === 'pickup' ? 'Retrait' : 'Livraison' }}</div>
        <div><strong>Moyen de paiement :</strong> {{ $order->payment_method === 'mobile_money' ? 'Mobile Money' : 'Espèces' }}</div>
        @if($order->autre)
            <div><strong>Instructions :</strong> {{ $order->autre }}</div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Qté</th>
                <th>Prix unit.</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['qty'] }}</td>
                    <td>{{ number_format($item['price'], 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($item['price'] * $item['qty'], 0, ',', ' ') }} FCFA</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>Sous-total</td>
            <td>{{ number_format($order->subtotal, 0, ',', ' ') }} FCFA</td>
        </tr>
        @if($order->delivery_cost > 0)
        <tr>
            <td>Livraison</td>
            <td>{{ number_format($order->delivery_cost, 0, ',', ' ') }} FCFA</td>
        </tr>
        @endif
        @if($order->discount > 0)
        <tr>
            <td>Remise</td>
            <td>-{{ number_format($order->discount, 0, ',', ' ') }} FCFA</td>
        </tr>
        @endif
        <tr class="total-row">
            <td>Total</td>
            <td>{{ number_format($order->final_total ?: $order->total, 0, ',', ' ') }} FCFA</td>
        </tr>
    </table>

    <p style="text-align:center; margin-top:40px; color:#999; font-size:12px;">
        Merci pour votre commande chez EVC Store.
    </p>
</body>
</html>
