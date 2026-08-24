@extends('layouts.admin')

@section('title', 'Produits EVC Store')
@section('page-title', 'Gestion des Produits')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white mb-0">
            <i class="fas fa-box-open me-2"></i>Produits EVC Store
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.boutique.orders') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag me-2"></i>Commandes
            </a>
            <a href="{{ route('admin.boutique.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Ajouter un produit
            </a>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['total'] }}</h3>
                    <p class="stat-label">Produits</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['active'] }}</h3>
                    <p class="stat-label">Actifs</p>
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
                    <p class="stat-label">Inactifs</p>
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

    <!-- Liste des produits -->
    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Prix</th>
                            <th>Livraison</th>
                            <th>Statut</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-image text-white"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $product->title }}</strong>
                                    <p class="text-muted mb-0 small">{{ Str::limit($product->summary, 60) }}</p>
                                </td>
                                <td>{{ $product->category->name ?? '-' }}</td>
                                <td><strong>{{ $product->formatted_price }}</strong></td>
                                <td>
                                    @if($product->delivery_mode === 'deposit')
                                        <span class="badge bg-info">Dépôt confirmation</span>
                                    @else
                                        <span class="badge bg-success">Paiement à la livraison</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.boutique.edit', $product) }}" class="btn btn-sm btn-warning me-1" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.boutique.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce produit ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x mb-3 text-muted"></i>
                                    <p class="text-muted mb-0">Aucun produit pour le moment.</p>
                                </td>
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
@endsection
