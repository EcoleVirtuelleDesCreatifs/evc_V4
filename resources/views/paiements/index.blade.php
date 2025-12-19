@extends('layouts.ki-admin')

@section('title', 'Paiements - EVC Formation')

@section('content')
<style>
    .ig-text-gradient {
        background: linear-gradient(135deg, #833AB4 0%, #E1306C 55%, #FCAF45 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .ig-badge {
        background: linear-gradient(135deg, #833AB4 0%, #E1306C 55%, #FCAF45 100%);
        color: #fff;
    }

    .ig-icon {
        background: linear-gradient(135deg, #833AB4 0%, #E1306C 55%, #FCAF45 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .ig-card-header {
        background: linear-gradient(135deg, #833AB4 0%, #C13584 40%, #E1306C 70%, #FCAF45 100%);
        color: #fff;
    }
</style>
<div class="container-fluid py-4">
    <!-- En-tête de la page -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h1 class="h3 mb-2 ig-text-gradient">
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

    <!-- Barre de progression globale -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Progression du paiement</h6>
                        <span class="badge ig-badge">{{ round($paymentProgress) }}%</span>
                    </div>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated {{ $paymentRemaining <= 0 ? 'bg-success' : 'bg-warning' }}"
                             role="progressbar"
                             style="width: {{ $paymentProgress }}%;"
                             aria-valuenow="{{ $paymentProgress }}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                            <strong>{{ number_format($paymentPaid, 0, ',', ' ') }} / {{ number_format($paymentAmount, 0, ',', ' ') }} FCFA</strong>
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
                    <h4 class="text-success mb-1">{{ number_format($paymentPaid, 0, ',', ' ') }} FCFA</h4>
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
                    <h4 class="text-warning mb-1">{{ number_format($paymentRemaining, 0, ',', ' ') }} FCFA</h4>
                    <p class="text-muted mb-0">Montant restant</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-receipt ig-icon" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 class="mb-1" style="color: #C13584;">{{ $payments->count() }}</h4>
                    <p class="text-muted mb-0">Tranches</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-check-circle text-success" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 class="{{ $paymentRemaining <= 0 ? 'text-success' : 'text-warning' }} mb-1">{{ $paymentRemaining <= 0 ? 'Soldé' : 'En cours' }}</h4>
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
                        <i class="fas fa-history me-2 ig-icon"></i>
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
                                @forelse($payments as $payment)
                                <tr>
                                    <td class="px-4 py-3">
                                        @if($payment->paid_at)
                                            {{ \Carbon\Carbon::parse($payment->paid_at)->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-muted">En attente</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="fw-bold">Tranche {{ $payment->installment_number }} / {{ $payment->total_installments }}</div>
                                        <small class="text-muted">
                                            @if($payment->payment_reference)
                                                Ref: {{ $payment->payment_reference }}
                                            @endif
                                        </small>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="fw-bold {{ $payment->status === 'completed' ? 'text-success' : 'text-warning' }}">
                                            {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($payment->status === 'completed')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>
                                                Payé
                                            </span>
                                        @elseif($payment->status === 'pending')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>
                                                En attente
                                            </span>
                                        @elseif($payment->status === 'failed')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>
                                                Échoué
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($payment->status === 'completed')
                                            @php($invoiceRouteName = \Illuminate\Support\Str::replaceLast('.paiements.index', '.paiements.invoice', Route::currentRouteName()))
                                            <a href="{{ route($invoiceRouteName, $payment->id) }}"
                                               class="btn btn-outline-primary btn-sm me-2"
                                               title="Télécharger la facture"
                                               target="_blank">
                                                <i class="fas fa-download me-1"></i>
                                                Facture
                                            </a>
                                        @else
                                            <span class="text-muted small">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Facture disponible après paiement
                                            </span>
                                        @endif
                                        @if($payment->transaction_id)
                                            <small class="text-muted d-block mt-1">Trans: {{ $payment->transaction_id }}</small>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="px-4 py-3" colspan="5">
                                        <div class="text-center text-muted py-4">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Aucun paiement enregistré pour le moment
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
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
                <div class="card-header ig-card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-plus me-2"></i>
                        Nouveau paiement
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Effectuer un nouveau paiement pour votre formation</p>
                    @php($formationChoice = $preRegistration->choix_formation ?? null)
                    @php($formationLabel = $formationChoice ? (new \App\Http\Controllers\Admin\PreRegistrationAdminController())->getFormationLabel($formationChoice) : null)
                    @php($payLink = config("chariow.payment_links.{$formationChoice}.tranche_1") ?: config("chariow.payment_links.{$formationLabel}.tranche_1"))
                    @php($payLink = $payLink ?: (str_contains(Route::currentRouteName(), 'community-management.') ? 'https://ecolevirtuelle.mychariow.shop/prd_fgcdnb/checkout' : null))

                    @if(!empty($payLink))
                        <a class="btn btn-primary" href="{{ $payLink }}" target="_blank" rel="noopener noreferrer">
                            <i class="fas fa-credit-card me-2"></i>
                            Payer maintenant
                        </a>
                    @else
                        <button class="btn btn-primary" disabled>
                            <i class="fas fa-credit-card me-2"></i>
                            Payer maintenant
                        </button>
                    @endif
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
