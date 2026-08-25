@extends('layouts.admin')

@section('title', 'Détail commande #' . $order->id)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-white">
            <i class="fas fa-shopping-bag me-2"></i>Commande #{{ $order->id }}
        </h1>
        <a href="{{ route('admin.boutique.orders') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                    <h5 class="mb-0 text-white"><i class="fas fa-user me-2"></i>Client</h5>
                </div>
                <div class="card-body text-white">
                    <p class="mb-1"><strong>Nom :</strong> {{ $order->nom }} {{ $order->prenoms }}</p>
                    <p class="mb-1"><strong>Contact :</strong> {{ $order->numero }}</p>
                    <p class="mb-1"><strong>Adresse :</strong> {{ $order->lieu }}</p>
                    <p class="mb-0"><strong>Instructions :</strong> {{ $order->autre ?: 'Aucune' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                    <h5 class="mb-0 text-white"><i class="fas fa-truck me-2"></i>Livraison & Paiement</h5>
                </div>
                <div class="card-body text-white">
                    <p class="mb-1"><strong>Mode :</strong> {{ $order->delivery_mode === 'pickup' ? 'Retrait' : 'Livraison' }}</p>
                    <p class="mb-1"><strong>Paiement :</strong> {{ $order->payment_method === 'mobile_money' ? 'Mobile Money' : 'Espèces' }}</p>
                    <p class="mb-1"><strong>Statut :</strong> <span class="status-badge {{ $order->status }}">{{ ucfirst($order->status) }}</span></p>
                    <p class="mb-0"><strong>Date :</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <h5 class="mb-0 text-white"><i class="fas fa-box me-2"></i>Articles</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Article</th>
                            <th>Qté</th>
                            <th>Prix unitaire</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    @php($img = $productImages[$item['id'] ?? null] ?? null)
                                    @if($img)
                                        <img src="{{ $img }}" alt="" class="rounded" style="width: 120px; height: 120px; object-fit: cover;">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $item['name'] ?? ($item['title'] ?? '-') }}</td>
                                <td>{{ $item['qty'] ?? 1 }}</td>
                                <td>{{ number_format($item['price'] ?? 0, 0, ',', ' ') }} FCFA</td>
                                <td>{{ number_format(($item['price'] ?? 0) * ($item['qty'] ?? 1), 0, ',', ' ') }} FCFA</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6 offset-md-6">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Sous-total</span>
                        <span>{{ number_format($order->subtotal, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Livraison</span>
                        <span>{{ number_format($order->delivery_cost, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Remise</span>
                        <span>-{{ number_format($order->discount, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <hr class="border-secondary">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-0">Total</h5>
                        <h5 class="mb-0">{{ number_format($order->final_total ?: $order->total, 0, ',', ' ') }} FCFA</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
