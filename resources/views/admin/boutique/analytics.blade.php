@extends('layouts.admin')

@section('title', 'Analytics EVC Store')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-white">
            <i class="fas fa-chart-line me-2"></i>Analytics EVC Store
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.boutique.index') }}" class="btn btn-outline-light">
                <i class="fas fa-box me-2"></i>Produits
            </a>
            <a href="{{ route('admin.boutique.orders') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag me-2"></i>Commandes
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                        <i class="fas fa-eye fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold">{{ $totalVisits }}</h3>
                        <p class="mb-0 opacity-75">Visites boutique</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #2563eb 0%, #f97316 100%);">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold">{{ $uniqueVisitors }}</h3>
                        <p class="mb-0 opacity-75">Visiteurs uniques</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                        <i class="fas fa-calendar-day fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold">{{ $todayVisits }}</h3>
                        <p class="mb-0 opacity-75">Visites aujourd'hui</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold">{{ $abandonedCount }}</h3>
                        <p class="mb-0 opacity-75">Paniers abandonnés</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                    <h5 class="mb-0 text-white"><i class="fas fa-fire me-2"></i>Produits les plus consultés</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-end">Vues</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProducts as $p)
                                    <tr>
                                        <td class="d-flex align-items-center gap-3">
                                            @if($p->product && ($productImages[$p->product->id] ?? null))
                                                <img src="{{ $productImages[$p->product->id] }}" alt="" style="width: 60px; height: 40px; object-fit: cover;" class="rounded">
                                            @endif
                                            <span>{{ $p->product->title ?? 'Produit #' . $p->product_id }}</span>
                                        </td>
                                        <td class="text-end"><span class="badge bg-primary">{{ $p->views }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-4">Aucune vue de produit.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                    <h5 class="mb-0 text-white"><i class="fas fa-exclamation-triangle me-2"></i>Paniers / commandes non finalisés</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Client</th>
                                    <th>Montant</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($abandonedOrders as $o)
                                    <tr>
                                        <td><a href="{{ route('admin.boutique.orders.show', $o) }}" class="text-white fw-bold">{{ $o->order_number }}</a></td>
                                        <td>{{ $o->prenoms }} {{ $o->nom }}</td>
                                        <td>{{ number_format($o->final_total ?: $o->total, 0, ',', ' ') }} FCFA</td>
                                        <td>{{ $o->created_at->format('d/m/Y H:i') }}</td>
                                        <td><span class="badge bg-warning text-dark">{{ $o->status }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">Aucun panier abandonné.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
