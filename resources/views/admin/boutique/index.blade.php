@extends('layouts.admin')

@section('title', 'Commandes EVC Store')

@push('styles')
<style>
    /* Cartes de statistiques modernes */
    .stat-card {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(30, 60, 114, 0.3);
    }

    .stat-card-primary { background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%); }
    .stat-card-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .stat-card-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .stat-card-info    { background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%); }

    .stat-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .stat-content { flex: 1; }
    .stat-number { font-size: 2.5rem; font-weight: 700; margin: 0; }
    .stat-label { margin: 0; opacity: 0.9; font-size: 0.95rem; }

    .status-badge {
        display: inline-block;
        padding: .35em .65em;
        font-size: .75em;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25rem;
    }
    .status-badge.payment_pending { background-color: #ffc107; color: #000; }
    .status-badge.payment_confirmed { background-color: #0d6efd; }
    .status-badge.preparing { background-color: #fd7e14; }
    .status-badge.ready_for_pickup { background-color: #6f42c1; }
    .status-badge.in_delivery { background-color: #0dcaf0; color: #000; }
    .status-badge.delivered { background-color: #198754; }
    .status-badge.cancelled { background-color: #6c757d; }

    .status-filter-card {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .status-filter-card:hover {
        transform: scale(1.03);
    }

    th, td { vertical-align: middle; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-white">
            <i class="fas fa-shopping-bag me-2"></i>Commandes EVC Store
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.boutique.index') }}" class="btn btn-primary">
                <i class="fas fa-box-open me-2"></i>Produits
            </a>
            <a href="{{ route('evc.store') }}" target="_blank" class="btn btn-info">
                <i class="fas fa-external-link-alt me-2"></i>Voir la boutique
            </a>
        </div>
    </div>

    <!-- Statistiques Globales -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['total'] }}</h3>
                    <p class="stat-label">Total Commandes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
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
        <div class="col-md-3 mb-3">
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
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-info">
                <div class="stat-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ number_format($stats['revenue'], 0, ',', ' ') }}</h3>
                    <p class="stat-label">Chiffre d'affaires (FCFA)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par Statut -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                    <h5 class="mb-0 text-white"><i class="fas fa-filter me-2"></i>Statistiques par Statut</h5>
                </div>
                <div class="card-body">
                    @php
                        $statusStats = [
                            'all' => ['label' => 'Toutes', 'count' => $stats['total'], 'icon' => 'fa-shopping-bag', 'gradient' => 'linear-gradient(135deg, #1e3c72 0%, #2a5298 100%)'],
                            'pending' => ['label' => 'En attente', 'count' => $stats['pending'], 'icon' => 'fa-clock', 'gradient' => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)'],
                            'completed' => ['label' => 'Traitées', 'count' => $stats['completed'], 'icon' => 'fa-check-circle', 'gradient' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)'],
                        ];
                    @endphp
                    <div class="row g-3">
                        @foreach($statusStats as $status => $s)
                            <div class="col-md-4">
                                <div class="card h-100 border-0 status-filter-card"
                                     data-status="{{ $status }}"
                                     style="background: {{ $s['gradient'] }}; color: white;"
                                     onclick="filterByStatus('{{ $status }}', '{{ $s['label'] }}')">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="text-white-50 text-uppercase mb-2" style="font-size: 0.75rem;">{{ $s['label'] }}</h6>
                                                <h2 class="mb-0 fw-bold">{{ $s['count'] }}</h2>
                                                <p class="mb-0 mt-1" style="font-size: 0.85rem;">Commande(s)</p>
                                            </div>
                                            <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                                <i class="fas {{ $s['icon'] }} fa-2x"></i>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-end">
                                            <small class="text-white-50"><i class="fas fa-mouse-pointer me-1"></i>Cliquer pour filtrer</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des commandes -->
    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <h5 class="mb-0 text-white"><i class="fas fa-list me-2"></i>Liste des Commandes</h5>
            <button class="btn btn-sm btn-outline-light" onclick="resetFilters()" id="resetFiltersBtn" style="display: none;">
                <i class="fas fa-redo me-1"></i>Réinitialiser les filtres
            </button>
        </div>
        <div class="card-body">
            <div id="filterInfo" class="alert alert-info mb-3" style="display: none; background-color: #1e40af; border-color: #1e40af; color: white;">
                <i class="fas fa-filter me-2"></i><span id="filterText"></span>
            </div>
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Contact</th>
                            <th>Adresse</th>
                            <th>Article</th>
                            <th>Image</th>
                            <th>Prix</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($productImages = $productImages ?? [])
                        @forelse ($orders as $order)
                            @php($firstItem = $order->items[0] ?? null)
                            @php($otherItems = count($order->items ?? []) - 1)
                            @php($productImage = $firstItem ? ($productImages[$firstItem['id']] ?? null) : null)
                            <tr class="order-row" data-status="{{ $order->status }}">
                                <td>{{ $order->id }}</td>
                                <td><strong>{{ $order->nom }}</strong> {{ $order->prenoms }}</td>
                                <td>{{ $order->numero }}</td>
                                <td>{{ $order->lieu }}</td>
                                <td>
                                    @if($firstItem)
                                        {{ $firstItem['name'] ?? ($firstItem['title'] ?? '-') }} x{{ $firstItem['qty'] ?? 1 }}
                                        @if($otherItems > 0)
                                            <span class="badge bg-secondary ms-1">+{{ $otherItems }} article(s)</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($productImage)
                                        <img src="{{ asset('storage/' . $productImage) }}" alt="" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <span class="text-white-50">-</span>
                                    @endif
                                </td>
                                <td><strong>{{ number_format($order->final_total ?: $order->total, 0, ',', ' ') }} FCFA</strong></td>
                                <td>
                                    <span class="status-badge {{ $order->status }}">
                                        {{ match($order->status) {
                                            'payment_pending' => 'En attente de paiement',
                                            'payment_confirmed' => 'Paiement confirmé',
                                            'preparing' => 'En préparation',
                                            'ready_for_pickup' => 'Prête pour retrait',
                                            'in_delivery' => 'En livraison',
                                            'delivered' => 'Livrée',
                                            'cancelled' => 'Annulée',
                                            default => ucfirst($order->status),
                                        } }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <form action="{{ route('admin.boutique.orders.status', $order) }}" method="POST" class="d-flex gap-1">
                                            @csrf
                                            <select name="status" class="form-select form-select-sm w-auto" style="min-width: 140px;" onchange="this.form.submit()">
                                                <option value="payment_pending" {{ $order->status === 'payment_pending' ? 'selected' : '' }}>En attente de paiement</option>
                                                <option value="payment_confirmed" {{ $order->status === 'payment_confirmed' ? 'selected' : '' }}>Paiement confirmé</option>
                                                <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>En préparation</option>
                                                <option value="ready_for_pickup" {{ $order->status === 'ready_for_pickup' ? 'selected' : '' }}>Prête pour retrait</option>
                                                <option value="in_delivery" {{ $order->status === 'in_delivery' ? 'selected' : '' }}>En livraison</option>
                                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Livrée</option>
                                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Annulée</option>
                                            </select>
                                        </form>
                                        <a href="{{ route('admin.boutique.orders.show', $order) }}" class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.boutique.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Supprimer cette commande ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">Aucune commande trouvée.</td>
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

@push('scripts')
<script>
let currentStatusFilter = null;

function filterByStatus(status, label) {
    currentStatusFilter = status;

    const rows = document.querySelectorAll('.order-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const rowStatus = row.getAttribute('data-status');
        if (status === 'all' || rowStatus === status) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const filterInfo = document.getElementById('filterInfo');
    const filterText = document.getElementById('filterText');
    const resetBtn = document.getElementById('resetFiltersBtn');

    filterText.innerHTML = `Filtré par statut : <strong>${label}</strong> (${visibleCount} commande(s))`;
    filterInfo.style.display = 'block';
    resetBtn.style.display = 'inline-block';

    highlightActiveFilters();
}

function resetFilters() {
    currentStatusFilter = null;

    const rows = document.querySelectorAll('.order-row');
    rows.forEach(row => {
        row.style.display = '';
    });

    document.getElementById('filterInfo').style.display = 'none';
    document.getElementById('resetFiltersBtn').style.display = 'none';

    document.querySelectorAll('.status-filter-card').forEach(card => {
        card.style.transform = 'scale(1)';
        card.style.boxShadow = 'none';
        card.style.border = '';
    });
}

function highlightActiveFilters() {
    document.querySelectorAll('.status-filter-card').forEach(card => {
        card.style.transform = 'scale(1)';
        card.style.boxShadow = 'none';
        card.style.border = '';
    });

    if (currentStatusFilter) {
        const card = document.querySelector(`.status-filter-card[data-status="${currentStatusFilter}"]`);
        if (card) {
            card.style.transform = 'scale(1.05)';
            card.style.boxShadow = '0 10px 30px rgba(255, 255, 255, 0.3)';
            card.style.border = '2px solid #60a5fa';
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.status-filter-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            if (this.getAttribute('data-status') !== currentStatusFilter) {
                this.style.transform = 'scale(1.03)';
                this.style.boxShadow = '0 8px 20px rgba(0, 0, 0, 0.3)';
            }
        });

        card.addEventListener('mouseleave', function() {
            if (this.getAttribute('data-status') !== currentStatusFilter) {
                this.style.transform = 'scale(1)';
                this.style.boxShadow = 'none';
            }
        });
    });
});
</script>
@endpush
@endsection
