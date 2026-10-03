@extends('layouts.admin')

@section('title', 'Modifier le montant restant')

@push('styles')
<style>
    body {
        background: #0f172a;
    }

    .page-header {
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(79, 195, 247, 0.3);
    }

    .card-surface {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        color: white;
        overflow: hidden;
    }

    .card-surface .card-header {
        background: rgba(79, 195, 247, 0.08);
        border-bottom: 1px solid #334155;
    }

    .form-control {
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid #334155;
        color: #f8fafc;
    }

    .form-control:focus {
        background: rgba(15, 23, 42, 0.9);
        border-color: #4fc3f7;
        box-shadow: 0 0 0 0.2rem rgba(79, 195, 247, 0.25);
        color: #f8fafc;
    }

    .stat-line {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(51, 65, 85, 0.6);
    }

    .stat-line:last-child {
        border-bottom: none;
    }

    .stat-label {
        color: #94a3b8;
        font-weight: 600;
    }

    .stat-value {
        font-weight: 700;
        color: #f8fafc;
        text-align: right;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-2"><i class="fas fa-pen me-3"></i>Modifier le montant restant</h1>
                <p class="mb-0">Ajuster le solde restant à payer pour cet étudiant</p>
            </div>
            <div>
                <a href="{{ route('admin.paiements.a-solder') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card card-surface">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Nouvelle valeur</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.paiements.a-solder.update-restant', $preReg->id) }}" method="POST" id="editRestantForm">
                        @csrf

                        <div class="mb-3">
                            <label for="total_amount" class="form-label">Montant total (FCFA)</label>
                            <input
                                type="number"
                                min="0"
                                class="form-control @error('total_amount') is-invalid @enderror"
                                id="total_amount"
                                name="total_amount"
                                value="{{ old('total_amount', $totalAmount) }}"
                                required
                            >
                            @error('total_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="amount_paid" class="form-label">Montant déjà payé (FCFA)</label>
                            <input
                                type="number"
                                min="0"
                                class="form-control @error('amount_paid') is-invalid @enderror"
                                id="amount_paid"
                                name="amount_paid"
                                value="{{ old('amount_paid', $amountPaid) }}"
                                required
                            >
                            @error('amount_paid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info" style="background: rgba(79,195,247,0.12); border-color: rgba(79,195,247,0.3); color: #e2e8f0;">
                            <i class="fas fa-info-circle me-2"></i>
                            Reste calculé : <strong id="preview_remaining">{{ number_format($remaining, 0, ',', ' ') }} FCFA</strong>.
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Enregistrer
                            </button>
                            <a href="{{ route('admin.paiements.a-solder') }}" class="btn btn-secondary">
                                Annuler
                            </a>
                        </div>
                    </form>

                    <script>
                        (function () {
                            const form = document.getElementById('editRestantForm');
                            if (!form) return;
                            const totalInput = document.getElementById('total_amount');
                            const paidInput = document.getElementById('amount_paid');
                            const preview = document.getElementById('preview_remaining');
                            function formatNumber(n) {
                                return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
                            }
                            function updatePreview() {
                                const total = parseInt(totalInput.value, 10) || 0;
                                const paid = parseInt(paidInput.value, 10) || 0;
                                const remaining = Math.max(0, total - paid);
                                preview.textContent = formatNumber(remaining) + ' FCFA';
                            }
                            totalInput.addEventListener('input', updatePreview);
                            paidInput.addEventListener('input', updatePreview);
                        })();
                    </script>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card card-surface">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Récapitulatif</h5>
                </div>
                <div class="card-body">
                    <div class="stat-line">
                        <div class="stat-label">Étudiant</div>
                        <div class="stat-value">{{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }}</div>
                    </div>
                    <div class="stat-line">
                        <div class="stat-label">Email</div>
                        <div class="stat-value">{{ $email }}</div>
                    </div>
                    <div class="stat-line">
                        <div class="stat-label">Formation</div>
                        <div class="stat-value">{{ $student->program ?? ($preReg->choix_formation ?? 'N/A') }}</div>
                    </div>
                    <div class="stat-line">
                        <div class="stat-label">Déjà payé</div>
                        <div class="stat-value text-success">{{ number_format($amountPaid, 0, ',', ' ') }} FCFA</div>
                    </div>
                    <div class="stat-line">
                        <div class="stat-label">Total actuel</div>
                        <div class="stat-value">{{ number_format($totalAmount, 0, ',', ' ') }} FCFA</div>
                    </div>
                    <div class="stat-line">
                        <div class="stat-label">Reste actuel</div>
                        <div class="stat-value text-warning">{{ number_format($remaining, 0, ',', ' ') }} FCFA</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
