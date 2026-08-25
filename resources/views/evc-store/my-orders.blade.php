@extends('layouts.app')

@section('title', 'Mes commandes - EVC Store')

@section('content')
<div class="container py-5">
    <h1 class="h2 mb-4">Mes commandes EVC Store</h1>

    @if($orders->isEmpty())
        <div class="alert alert-info">Vous n'avez pas encore passé de commande.</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>N° commande</th>
                        <th>Date</th>
                        <th>Produits</th>
                        <th>Total</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong></td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @foreach($order->items as $item)
                                    <span class="badge bg-secondary me-1">
                                        {{ $item['name'] ?? ($item['title'] ?? '-') }} x{{ $item['qty'] ?? 1 }}
                                    </span>
                                @endforeach
                            </td>
                            <td>{{ number_format($order->total, 0, ',', ' ') }} FCFA</td>
                            <td>
                                @php
                                    $statusClass = match($order->status) {
                                        'payment_pending' => 'bg-warning text-dark',
                                        'payment_confirmed' => 'bg-primary',
                                        'preparing' => 'bg-warning',
                                        'ready_for_pickup' => 'bg-info',
                                        'in_delivery' => 'bg-info text-dark',
                                        'delivered' => 'bg-success',
                                        'cancelled' => 'bg-secondary',
                                        default => 'bg-light text-dark',
                                    };
                                    $statusLabel = match($order->status) {
                                        'payment_pending' => 'En attente de paiement',
                                        'payment_confirmed' => 'Paiement confirmé',
                                        'preparing' => 'En préparation',
                                        'ready_for_pickup' => 'Prête pour retrait',
                                        'in_delivery' => 'En livraison',
                                        'delivered' => 'Livrée',
                                        'cancelled' => 'Annulée',
                                        default => ucfirst($order->status),
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
