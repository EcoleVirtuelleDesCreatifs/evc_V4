@extends('layouts.admin')

@section('title', 'Gestion de la Boutique')
@section('page-title', 'Gestion de la Boutique')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white mb-0">
            <i class="fas fa-store me-2"></i>Commandes EVC Store
        </h1>
        <a href="{{ route('evc.store') }}" target="_blank" class="btn btn-primary">
            <i class="fas fa-external-link-alt me-2"></i>Voir la boutique
        </a>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['total'] }}</h3>
                    <p class="stat-label">Commandes</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['pending'] }}</h3>
                    <p class="stat-label">En attente</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['completed'] }}</h3>
                    <p class="stat-label">Traitées</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des commandes -->
    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Contact</th>
                            <th>Lieu de livraison</th>
                            <th>Articles</th>
                            <th>Total</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th style="width: 120px;">Infos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>
                                    <strong>{{ $order->nom }}</strong> {{ $order->prenoms }}
                                </td>
                                <td>{{ $order->numero }}</td>
                                <td>{{ $order->lieu }}</td>
                                <td>
                                    @foreach ($order->items as $item)
                                        <span class="badge bg-info me-1">
                                            {{ $item['name'] ?? ($item['title'] ?? '-') }} x{{ $item['qty'] ?? 1 }}
                                        </span>
                                    @endforeach
                                </td>
                                <td><strong>{{ number_format($order->total, 0, ',', ' ') }} FCFA</strong></td>
                                <td>
                                    @if($order->status === 'pending')
                                        <span class="badge bg-warning text-dark">En attente</span>
                                    @else
                                        <span class="badge bg-success">Traitée</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $order->autre ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x mb-3 text-muted"></i>
                                    <p class="text-muted mb-0">Aucune commande pour le moment.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
