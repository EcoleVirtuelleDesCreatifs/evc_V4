@extends('layouts.admin')

@section('title', 'Produits EVC Store')

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
    .status-badge.active { background-color: #198754; }
    .status-badge.inactive { background-color: #ffc107; color: #000; }

    .category-filter-card {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .category-filter-card:hover {
        transform: scale(1.03);
    }

    th, td { vertical-align: middle; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-white">
            <i class="fas fa-store me-2"></i>Gestion des Produits EVC Store
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.boutique.orders') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag me-2"></i>Commandes
            </a>
            <a href="{{ route('admin.boutique.categories.create') }}" class="btn btn-warning">
                <i class="fas fa-folder-plus me-2"></i>Ajouter une catégorie
            </a>
            <a href="{{ route('admin.boutique.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Ajouter un produit
            </a>
        </div>
    </div>

    <!-- Statistiques Globales -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['total'] }}</h3>
                    <p class="stat-label">Total Produits</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['active'] }}</h3>
                    <p class="stat-label">Produits Actifs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-eye-slash"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['inactive'] }}</h3>
                    <p class="stat-label">Produits Inactifs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-info">
                <div class="stat-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['orders'] }}</h3>
                    <p class="stat-label">Commandes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques Boutique -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['realtime_visitors'] }}</h3>
                    <p class="stat-label">Visiteurs temps réel</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['daily_visitors'] }}</h3>
                    <p class="stat-label">Visiteurs aujourd'hui</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['monthly_visitors'] }}</h3>
                    <p class="stat-label">Visiteurs ce mois</p>
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

    @if($mostViewed->isNotEmpty())
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                    <h5 class="mb-0 text-white"><i class="fas fa-eye me-2"></i>Produits les plus consultés</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($mostViewed as $p)
                            <div class="col-md-4">
                                <div class="d-flex align-items-center gap-3 text-white">
                                    <span class="badge bg-primary fs-6">{{ $loop->iteration }}</span>
                                    <div>
                                        <div class="fw-bold">{{ $p->title }}</div>
                                        <div class="text-white-50 small">{{ $p->view_count }} vues</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistiques par Catégorie -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                    <h5 class="mb-0 text-white"><i class="fas fa-tags me-2"></i>Statistiques par Catégorie</h5>
                </div>
                <div class="card-body">
                    @if($categories->isEmpty())
                        <p class="text-white-50 mb-0">Aucune catégorie pour le moment.</p>
                    @else
                        @php
                            $gradients = [
                                'linear-gradient(135deg, #1e3c72 0%, #2a5298 100%)',
                                'linear-gradient(135deg, #2563eb 0%, #f97316 100%)',
                                'linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%)',
                                'linear-gradient(135deg, #ff9800 0%, #fb8c00 100%)',
                                'linear-gradient(135deg, #26c6da 0%, #00acc1 100%)',
                                'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                            ];
                            $icons = ['fa-box', 'fa-tag', 'fa-shopping-bag', 'fa-gift', 'fa-book', 'fa-star'];
                        @endphp
                        <div class="row g-3">
                            @foreach($categories as $category)
                                <div class="col-md-6 col-lg-3">
                                    <div class="card h-100 border-0 category-filter-card"
                                         data-category="{{ $category->slug }}"
                                         style="background: {{ $gradients[$loop->index % count($gradients)] }}; color: white;"
                                         onclick="filterByCategory('{{ $category->slug }}', '{{ $category->name }}')">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="text-white-50 text-uppercase mb-2" style="font-size: 0.75rem;">{{ $category->name }}</h6>
                                                    <h2 class="mb-0 fw-bold">{{ $category->products_count }}</h2>
                                                    <p class="mb-0 mt-1" style="font-size: 0.85rem;">Produit(s)</p>
                                                </div>
                                                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                                    <i class="fas {{ $icons[$loop->index % count($icons)] }} fa-2x"></i>
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
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des produits -->
    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <h5 class="mb-0 text-white"><i class="fas fa-list me-2"></i>Liste des Produits</h5>
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
                            <th>Image</th>
                            <th style="min-width: 200px;">Titre</th>
                            <th>Catégorie</th>
                            <th>Prix</th>
                            <th>Livraison</th>
                            <th>Statut</th>
                            <th>Stock</th>
                            <th>Date de Création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="product-row" data-category="{{ $product->category->slug ?? '' }}">
                                <td>
                                    @if($product->image)
                                        <img src="{{ \App\Models\MediaUrl::fromPath($product->image) }}" alt="{{ $product->title }}" style="width: 120px; height: 80px; object-fit: cover;" class="rounded shadow-sm">
                                    @else
                                        <div style="width: 60px; height: 40px; background-color: #334155;" class="rounded shadow-sm d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $product->title }}</strong>
                                    <p class="text-muted mb-0 small">{{ Str::limit($product->summary, 60) }}</p>
                                </td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                <td><strong>{{ $product->formatted_price }}</strong></td>
                                <td>
                                    @if($product->delivery_mode === 'deposit')
                                        <span class="badge bg-info">Dépôt confirmation</span>
                                    @else
                                        <span class="badge bg-success">Paiement à la livraison</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge {{ $product->is_active ? 'active' : 'inactive' }}">
                                        {{ $product->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td>{{ $product->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.boutique.edit', $product) }}" class="btn btn-sm btn-warning">Modifier</a>
                                    <form action="{{ route('admin.boutique.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">Aucun produit trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentCategoryFilter = null;

function filterByCategory(slug, name) {
    currentCategoryFilter = slug;

    const rows = document.querySelectorAll('.product-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const rowCategory = row.getAttribute('data-category');
        if (rowCategory === slug) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const filterInfo = document.getElementById('filterInfo');
    const filterText = document.getElementById('filterText');
    const resetBtn = document.getElementById('resetFiltersBtn');

    filterText.innerHTML = `Filtré par catégorie : <strong>${name}</strong> (${visibleCount} produit(s))`;
    filterInfo.style.display = 'block';
    resetBtn.style.display = 'inline-block';

    highlightActiveFilters();
}

function resetFilters() {
    currentCategoryFilter = null;

    const rows = document.querySelectorAll('.product-row');
    rows.forEach(row => {
        row.style.display = '';
    });

    document.getElementById('filterInfo').style.display = 'none';
    document.getElementById('resetFiltersBtn').style.display = 'none';

    document.querySelectorAll('.category-filter-card').forEach(card => {
        card.style.transform = 'scale(1)';
        card.style.boxShadow = 'none';
        card.style.border = '';
    });
}

function highlightActiveFilters() {
    document.querySelectorAll('.category-filter-card').forEach(card => {
        card.style.transform = 'scale(1)';
        card.style.boxShadow = 'none';
        card.style.border = '';
    });

    if (currentCategoryFilter) {
        const card = document.querySelector(`.category-filter-card[data-category="${currentCategoryFilter}"]`);
        if (card) {
            card.style.transform = 'scale(1.05)';
            card.style.boxShadow = '0 10px 30px rgba(255, 255, 255, 0.3)';
            card.style.border = '2px solid #60a5fa';
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.category-filter-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            if (this.getAttribute('data-category') !== currentCategoryFilter) {
                this.style.transform = 'scale(1.03)';
                this.style.boxShadow = '0 8px 20px rgba(0, 0, 0, 0.3)';
            }
        });

        card.addEventListener('mouseleave', function() {
            if (this.getAttribute('data-category') !== currentCategoryFilter) {
                this.style.transform = 'scale(1)';
                this.style.boxShadow = 'none';
            }
        });
    });
});
</script>
@endpush
@endsection
