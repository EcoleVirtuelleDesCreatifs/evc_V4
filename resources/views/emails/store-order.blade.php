<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle commande EVC Store</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; color: #333; background: #f8f9fa; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .body { padding: 30px; }
        .body h2 { color: #1e293b; font-size: 18px; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; border-bottom: 1px solid #eee; text-align: left; }
        th { color: #ff6b35; }
        .total { font-size: 18px; font-weight: bold; color: #ff6b35; text-align: right; margin-top: 20px; }
        .footer { text-align: center; color: #999; font-size: 12px; padding: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>EVC Store</h1>
            <p>Nouvelle commande reçue</p>
        </div>
        <div class="body">
            <h2>Commande n° {{ $order->order_number }}</h2>
            <p><strong>Client :</strong> {{ $order->prenoms }} {{ $order->nom }}</p>
            <p><strong>Téléphone :</strong> {{ $order->numero }}</p>
            <p><strong>Adresse / Lieu :</strong> {{ $order->lieu }}</p>
            <p><strong>Mode de réception :</strong> {{ $order->delivery_mode === 'pickup' ? 'Retrait' : 'Livraison' }}</p>
            <p><strong>Paiement :</strong> {{ $order->payment_method === 'mobile_money' ? 'Mobile Money' : 'Espèces' }}</p>

            <h3>Articles commandés</h3>
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

            @if($order->delivery_cost > 0)
                <p><strong>Livraison :</strong> {{ number_format($order->delivery_cost, 0, ',', ' ') }} FCFA</p>
            @endif
            @if($order->discount > 0)
                <p><strong>Remise :</strong> -{{ number_format($order->discount, 0, ',', ' ') }} FCFA</p>
            @endif
            <p class="total">Total : {{ number_format($order->final_total ?: $order->total, 0, ',', ' ') }} FCFA</p>

            @if($order->autre)
                <p><strong>Instructions :</strong> {{ $order->autre }}</p>
            @endif
        </div>
        <div class="footer">
            Cet email a été envoyé automatiquement par EVC Store.
        </div>
    </div>
</body>
</html>
