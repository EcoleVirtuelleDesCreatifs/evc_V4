@extends('layouts.admin')

@section('title', 'Paiements')

@push('styles')
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
@endpush

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="form-card mb-4">
        <div class="form-card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-coins"></i>
                <div>
                    <h3 class="mb-0">Paiements - Préinscription #{{ $pre->id }}</h3>
                    <div class="text-muted" style="font-size: 0.95rem;">
                        {{ $pre->prenom }} {{ $pre->nom }} — {{ $pre->email }}
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.preinscriptions.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
                <a href="{{ route('admin.preinscriptions.show', $pre->id) }}" class="btn btn-secondary">
                    <i class="fas fa-eye me-2"></i>Voir la fiche
                </a>
            </div>
        </div>
        <div class="form-card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="form-card h-100">
                        <div class="form-card-header">
                            <i class="fas fa-graduation-cap"></i>
                            <h3>Formation</h3>
                        </div>
                        <div class="form-card-body">
                            <div class="fw-bold">{{ $formationName }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-card h-100">
                        <div class="form-card-header">
                            <i class="fas fa-receipt"></i>
                            <h3>Total à payer</h3>
                        </div>
                        <div class="form-card-body">
                            <div class="fw-bold">{{ number_format($totalAmount, 0, ',', ' ') }} FCFA</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-card h-100">
                        <div class="form-card-header">
                            <i class="fas fa-chart-pie"></i>
                            <h3>Payé / Reste</h3>
                        </div>
                        <div class="form-card-body">
                            <div class="fw-bold">
                                {{ number_format($amountPaid, 0, ',', ' ') }} FCFA
                                <span class="text-muted">/</span>
                                <span class="{{ $remaining > 0 ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($remaining, 0, ',', ' ') }} FCFA
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-12">
            <div class="form-card h-100">
                <div class="form-card-header">
                    <i class="fas fa-tags"></i>
                    <h3>Remise sur la formation</h3>
                </div>
                <div class="form-card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <div class="text-muted">Montant formation</div>
                            <div class="fw-bold">{{ number_format($grossTotalAmount ?? $totalAmount, 0, ',', ' ') }} FCFA</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted">Remise actuelle</div>
                            <div class="fw-bold text-warning">{{ number_format($discountAmount ?? 0, 0, ',', ' ') }} FCFA</div>
                        </div>
                        <div class="col-md-6">
                            <form method="POST" action="{{ url('/evc/app/admin/preinscriptions/' . $pre->id . '/discount') }}" class="d-flex gap-2">
                                @csrf
                                <input type="number" name="discount_amount" class="form-control" min="0" step="1" value="{{ old('discount_amount', $discountAmount ?? 0) }}" placeholder="Montant de la remise">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-2"></i>Appliquer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-12">
            <div class="form-card h-100">
                <div class="form-card-header">
                    <i class="fas fa-coins"></i>
                    <h3>Paiement manuel</h3>
                </div>
                <div class="form-card-body">
                    <form method="POST" action="{{ route('admin.preinscriptions.manual-payment', $pre->id) }}" onsubmit="return confirm('Confirmer l\'enregistrement de ce paiement manuel ?');">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Montant payé (FCFA)</label>
                            <input type="number" name="amount" class="form-control" min="1" step="1" required value="{{ old('amount') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tranche (optionnel)</label>
                            <select name="installment_number" class="form-select">
                                <option value="">-- Paiement libre --</option>
                                <option value="1" {{ old('installment_number') == '1' ? 'selected' : '' }}>Tranche 1</option>
                                <option value="2" {{ old('installment_number') == '2' ? 'selected' : '' }}>Tranche 2</option>
                            </select>
                            <div class="form-text" style="color: var(--form-text-muted) !important;">Si tu sélectionnes une tranche, le système tentera de marquer la tranche correspondante comme payée.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Méthode (optionnel)</label>
                            <select name="method" class="form-select">
                                <option value="">-- Sélectionner --</option>
                                <option value="cash" {{ old('method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="orange_money" {{ old('method') == 'orange_money' ? 'selected' : '' }}>Orange Money</option>
                                <option value="mtn_mobile" {{ old('method') == 'mtn_mobile' ? 'selected' : '' }}>MTN Mobile Money</option>
                                <option value="wave" {{ old('method') == 'wave' ? 'selected' : '' }}>Wave</option>
                                <option value="virement" {{ old('method') == 'virement' ? 'selected' : '' }}>Virement</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Référence / N° reçu (optionnel)</label>
                            <input type="text" name="reference" class="form-control" maxlength="191" placeholder="Ex: REC-2025-001" value="{{ old('reference') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date du paiement (optionnel)</label>
                            <input type="datetime-local" name="paid_at" class="form-control" value="{{ old('paid_at') }}">
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Enregistrer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
