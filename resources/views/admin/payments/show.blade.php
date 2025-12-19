@extends('layouts.admin')

@section('title', 'Détails du Paiement - Admin EVC')

@push('styles')
<style>
    body {
        background: #0f172a;
    }

    .payment-detail-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .detail-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .detail-header {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 16px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
    }

    .detail-row {
        display: flex;
        padding: 1rem 0;
        border-bottom: 1px solid #334155;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: #94a3b8;
        font-weight: 600;
        width: 250px;
        flex-shrink: 0;
    }

    .detail-value {
        color: #e2e8f0;
        font-weight: 500;
    }

    .badge {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-block;
    }

    .badge-completed {
        background: rgba(16, 185, 129, 0.2);
        color: #10b981;
    }

    .badge-pending {
        background: rgba(245, 158, 11, 0.2);
        color: #f59e0b;
    }

    .badge-cancelled {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }

    .btn-back {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: linear-gradient(135deg, #4f46e5, #4338ca);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
    }
</style>
@endpush

@section('content')
<div class="payment-detail-container">
    <!-- En-tête -->
    <div class="detail-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">
                    <i class="fas fa-file-invoice-dollar me-2"></i>
                    Détails du Paiement
                </h1>
                <p style="opacity: 0.9; margin: 0;">
                    Référence : <code style="background: rgba(255,255,255,0.2); padding: 0.3rem 0.6rem; border-radius: 6px;">{{ $payment->payment_reference }}</code>
                </p>
            </div>
            <div>
                @if($payment->status === 'completed')
                    <span class="badge badge-completed" style="font-size: 1rem;">
                        <i class="fas fa-check-circle me-1"></i>Paiement Complété
                    </span>
                @elseif($payment->status === 'pending')
                    <span class="badge badge-pending" style="font-size: 1rem;">
                        <i class="fas fa-clock me-1"></i>En Attente
                    </span>
                @else
                    <span class="badge badge-cancelled" style="font-size: 1rem;">
                        <i class="fas fa-times-circle me-1"></i>Annulé
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Informations de paiement -->
    <div class="detail-card">
        <h2 style="color: white; font-size: 1.5rem; margin-bottom: 1.5rem;">
            <i class="fas fa-money-bill-wave me-2" style="color: #10b981;"></i>
            Informations de Paiement
        </h2>

        <div class="detail-row">
            <div class="detail-label">Référence de paiement :</div>
            <div class="detail-value">
                <code style="background: #0f172a; padding: 0.4rem 0.8rem; border-radius: 6px; color: #10b981;">
                    {{ $payment->payment_reference }}
                </code>
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Montant :</div>
            <div class="detail-value" style="color: #10b981; font-size: 1.5rem; font-weight: 700;">
                {{ number_format($payment->amount, 0, ',', ' ') }} XOF
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Tranche :</div>
            <div class="detail-value">
                Tranche {{ $payment->installment_number }} / 2
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Transaction ID :</div>
            <div class="detail-value">
                @if($payment->transaction_id)
                    <code style="background: #0f172a; padding: 0.4rem 0.8rem; border-radius: 6px;">
                        {{ $payment->transaction_id }}
                    </code>
                @else
                    <span style="color: #64748b;">Non disponible</span>
                @endif
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Date de création :</div>
            <div class="detail-value">
                {{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y à H:i') }}
            </div>
        </div>

        @if($payment->status === 'completed')
        <div class="detail-row">
            <div class="detail-label">Date de confirmation :</div>
            <div class="detail-value">
                {{ $payment->updated_at ? \Carbon\Carbon::parse($payment->updated_at)->format('d/m/Y à H:i') : 'N/A' }}
            </div>
        </div>
        @endif
    </div>

    <!-- Informations de l'étudiant -->
    <div class="detail-card">
        <h2 style="color: white; font-size: 1.5rem; margin-bottom: 1.5rem;">
            <i class="fas fa-user-graduate me-2" style="color: #3b82f6;"></i>
            Informations de l'Étudiant
        </h2>

        <div class="detail-row">
            <div class="detail-label">Nom complet :</div>
            <div class="detail-value" style="font-weight: 700; font-size: 1.1rem;">
                {{ $payment->prenom }} {{ $payment->nom }}
            </div>
        </div>

        @if($payment->student_id)
        <div class="detail-row">
            <div class="detail-label">Matricule étudiant :</div>
            <div class="detail-value">
                <code style="background: #0f172a; padding: 0.4rem 0.8rem; border-radius: 6px; color: #10b981;">
                    {{ $payment->student_id }}
                </code>
            </div>
        </div>
        @endif

        <div class="detail-row">
            <div class="detail-label">Email :</div>
            <div class="detail-value">
                <a href="mailto:{{ $payment->email }}" style="color: #3b82f6; text-decoration: none;">
                    <i class="fas fa-envelope me-1"></i>{{ $payment->email }}
                </a>
            </div>
        </div>

        @if($payment->whatsapp)
        <div class="detail-row">
            <div class="detail-label">WhatsApp :</div>
            <div class="detail-value">
                <i class="fab fa-whatsapp me-1" style="color: #10b981;"></i>
                {{ $payment->whatsapp }}
            </div>
        </div>
        @endif

        <div class="detail-row">
            <div class="detail-label">Formation :</div>
            <div class="detail-value" style="color: #f59e0b; font-weight: 600;">
                {{ $payment->choix_formation ?? 'Non définie' }}
            </div>
        </div>

        @if($payment->ville || $payment->pays)
        <div class="detail-row">
            <div class="detail-label">Localisation :</div>
            <div class="detail-value">
                <i class="fas fa-map-marker-alt me-1"></i>
                {{ $payment->ville ?? '' }}{{ $payment->ville && $payment->pays ? ', ' : '' }}{{ $payment->pays ?? '' }}
            </div>
        </div>
        @endif

        @if($payment->student_status)
        <div class="detail-row">
            <div class="detail-label">Statut étudiant :</div>
            <div class="detail-value">
                @if($payment->student_status === 'active')
                    <span class="badge badge-completed">
                        <i class="fas fa-check-circle me-1"></i>Actif
                    </span>
                @else
                    <span class="badge badge-cancelled">
                        <i class="fas fa-times-circle me-1"></i>Inactif
                    </span>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Token de création de compte (si existe) -->
    @if($payment->account_creation_token)
    <div class="detail-card">
        <h2 style="color: white; font-size: 1.5rem; margin-bottom: 1.5rem;">
            <i class="fas fa-key me-2" style="color: #8b5cf6;"></i>
            Token de Création de Compte
        </h2>

        <div class="detail-row">
            <div class="detail-label">Token :</div>
            <div class="detail-value" style="word-break: break-all;">
                <code style="background: #0f172a; padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.85rem;">
                    {{ $payment->account_creation_token }}
                </code>
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Lien de création :</div>
            <div class="detail-value">
                <a href="{{ url('/student/confirm-registration/' . $payment->account_creation_token) }}"
                   target="_blank"
                   style="color: #3b82f6; text-decoration: none;">
                    <i class="fas fa-external-link-alt me-1"></i>
                    Ouvrir le lien de création de compte
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Boutons d'action -->
    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
        <a href="{{ route('admin.payments.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            Retour à la liste
        </a>
    </div>
</div>
@endsection
