@extends('layouts.ki-admin')

@section('title', 'Paiements - EVC Formation')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête de la page -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h1 class="h3 mb-2 text-primary">
                                <i class="fas fa-credit-card me-2"></i>
                                Gestion des Paiements
                            </h1>
                            <p class="text-muted mb-0">Consultez vos paiements et factures de formation</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success fs-6 px-3 py-2">
                                <i class="fas fa-shield-alt me-1"></i>
                                Paiements sécurisés
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques des paiements -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-euro-sign text-success" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 class="text-success mb-1">{{ session('user_montant_paye', '0') }}€</h4>
                    <p class="text-muted mb-0">Total payé</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-clock text-warning" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 class="text-warning mb-1">{{ session('user_montant_restant', '0') }}€</h4>
                    <p class="text-muted mb-0">Montant restant</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-receipt text-info" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 class="text-info mb-1">3</h4>
                    <p class="text-muted mb-0">Factures</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-check-circle text-success" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 class="text-success mb-1">À jour</h4>
                    <p class="text-muted mb-0">Statut</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des paiements -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2 text-primary"></i>
                        Historique des paiements
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-4 py-3">Date</th>
                                    <th class="border-0 px-4 py-3">Description</th>
                                    <th class="border-0 px-4 py-3">Montant</th>
                                    <th class="border-0 px-4 py-3">Statut</th>
                                    <th class="border-0 px-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-4 py-3">{{ date('d/m/Y') }}</td>
                                    <td class="px-4 py-3">
                                        <div class="fw-bold">Formation Design Graphique</div>
                                        <small class="text-muted">Paiement initial</small>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="fw-bold text-success">{{ session('user_montant_paye', '0') }}€</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>
                                            Validé
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <button class="btn btn-outline-primary btn-sm me-2">
                                            <i class="fas fa-download me-1"></i>
                                            Facture
                                        </button>
                                        <button class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-eye me-1"></i>
                                            Détails
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3" colspan="5">
                                        <div class="text-center text-muted py-4">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Aucun autre paiement enregistré pour le moment
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-plus me-2"></i>
                        Nouveau paiement
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Effectuer un nouveau paiement pour votre formation</p>
                    <button class="btn btn-primary">
                        <i class="fas fa-credit-card me-2"></i>
                        Payer maintenant
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-question-circle me-2"></i>
                        Aide & Support
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Besoin d'aide avec vos paiements ?</p>
                    <button class="btn btn-info">
                        <i class="fas fa-headset me-2"></i>
                        Contacter le support
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.badge {
    font-size: 0.75rem;
}

.btn {
    transition: all 0.2s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>
@endsection
