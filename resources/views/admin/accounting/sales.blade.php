@extends('layouts.admin')

@section('title', 'Gestion des Ventes')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="mb-1" style="font-weight: 800; color: #0f172a;">Gestion des Ventes</h3>
            <div class="text-muted">Suivi des ventes et justificatifs.</div>
        </div>
        <div>
            <a href="{{ route('admin.accounting.index') }}" class="btn btn-outline-secondary me-2" style="border-radius: 12px;">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            <a href="{{ route('admin.accounting.export', ['type' => 'income']) }}" class="btn btn-outline-secondary me-2" style="border-radius: 12px;">
                <i class="fas fa-file-export me-2"></i>Exporter
            </a>
            <a href="{{ route('admin.accounting.sales.create') }}" class="btn btn-success" style="border-radius: 12px;">
                <i class="fas fa-plus me-2"></i>Ajouter une vente
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                <div class="stat-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total'] ?? 0 }}</h3>
                    <p>Ventes (total)</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%);">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format((float) ($stats['this_month'] ?? 0), 0, ',', ' ') }}</h3>
                    <p>Ce mois (FCFA)</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%);">
                <div class="stat-icon">
                    <i class="fas fa-calendar-week"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format((float) ($stats['last_7d'] ?? 0), 0, ',', ' ') }}</h3>
                    <p>7 jours (FCFA)</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format((float) ($stats['this_year'] ?? 0), 0, ',', ' ') }}</h3>
                    <p>Cette année (FCFA)</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0" style="border-radius: 16px;">
        <div class="card-header bg-white border-0 py-3" style="border-radius: 16px 16px 0 0;">
            <h5 class="mb-0 d-flex align-items-center">
                <i class="fas fa-file-invoice-dollar me-2" style="color: #1e3c72;"></i>
                Journal des Ventes
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Référence</th>
                            <th class="text-end">Montant</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                        <tr>
                            <td>{{ $sale->date->format('d/m/Y') }}</td>
                            <td class="fw-medium">
                                {{ $sale->title }}
                                @if($sale->student_name)
                                    <div class="small text-muted mt-1"><i class="fas fa-user-graduate me-1"></i>{{ $sale->student_name }}</div>
                                @endif
                                @if($sale->training_module)
                                    <div class="small text-muted"><i class="fas fa-book me-1"></i>{{ $sale->training_module }}</div>
                                @endif
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $sale->category }}</span></td>
                            <td class="text-muted small">{{ $sale->reference ?? '-' }}</td>
                            <td class="text-end fw-bold text-success">
                                + {{ number_format($sale->amount, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="text-center">
                                @if($sale->proof_path)
                                    <a href="{{ Storage::url($sale->proof_path) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1" title="Voir le justificatif">
                                        <i class="fas fa-file-alt"></i>
                                    </a>
                                @endif
                                <form action="{{ route('admin.accounting.destroy', $sale->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette vente ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="mb-3"><i class="fas fa-cash-register fa-3x opacity-25"></i></div>
                                Aucune vente enregistrée.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3" style="border-radius: 0 0 16px 16px;">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="pagination-info text-muted mb-2 mb-md-0">
                    @if($sales->total() > 0)
                        Affichage de <strong>{{ $sales->firstItem() }}</strong> à <strong>{{ $sales->lastItem() }}</strong> sur <strong>{{ $sales->total() }}</strong> ventes
                    @else
                        Aucun résultat
                    @endif
                </div>
                <div>
                    {{ $sales->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    border-radius: 16px;
    padding: 1.5rem;
    color: white;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.3s, box-shadow 0.3s;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.2);
}

.stat-icon {
    font-size: 2rem;
    opacity: 0.9;
}

.stat-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    line-height: 1;
}

.stat-content p {
    margin: 0.5rem 0 0 0;
    font-size: 0.9rem;
    opacity: 0.9;
}

.pagination-info {
    font-size: 0.9rem;
}

.pagination {
    margin: 0;
}

.pagination .page-link {
    color: #1e3c72;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin: 0 0.25rem;
    padding: 0.5rem 0.75rem;
    font-weight: 500;
    transition: all 0.3s;
}

.pagination .page-link:hover {
    background-color: #1e3c72;
    color: white;
    border-color: #1e3c72;
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(30, 60, 114, 0.3);
}

.pagination .page-item.active .page-link {
    background-color: #1e3c72;
    border-color: #1e3c72;
    box-shadow: 0 2px 8px rgba(30, 60, 114, 0.3);
}

.pagination .page-item.disabled .page-link {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #6c757d;
}

@media (max-width: 768px) {
    .stat-card {
        margin-bottom: 1rem;
    }

    .pagination-info {
        font-size: 0.8rem;
        text-align: center;
    }

    .card-footer .d-flex {
        flex-direction: column;
        gap: 1rem;
    }

    .pagination {
        display: flex;
        justify-content: center;
    }
}
</style>
@endsection
