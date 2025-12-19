@extends('layouts.admin')

@section('title', 'Gestion des Paiements - Admin EVC')

@push('styles')
<style>
    body {
        background: #0f172a;
    }

    .payments-header {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 24px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 20px 60px rgba(16, 185, 129, 0.3);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        border-color: #10b981;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.2);
    }

    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #10b981;
        margin-bottom: 0.5rem;
    }

    .stat-card .stat-label {
        color: #94a3b8;
        font-size: 0.9rem;
    }

    .payments-table-container {
        background: #1e293b;
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid #334155;
    }

    .payments-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .payments-table thead th {
        background: #0f172a;
        color: #94a3b8;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid #334155;
    }

    .payments-table tbody tr {
        background: #1e293b;
        transition: all 0.2s ease;
    }

    .payments-table tbody tr:hover {
        background: #2d3748;
        transform: scale(1.01);
    }

    .payments-table tbody td {
        padding: 1rem;
        color: #e2e8f0;
        border-bottom: 1px solid #334155;
    }

    .badge {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
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

    .btn-view {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .btn-view:hover {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    /* Pagination Styles */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        padding: 0;
        margin: 0;
    }

    .pagination .page-item {
        list-style: none;
    }

    .pagination .page-link {
        background: #0f172a;
        border: 1px solid #334155;
        color: #e2e8f0;
        padding: 0.6rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .pagination .page-link:hover {
        background: #1e293b;
        border-color: #10b981;
        color: #10b981;
        transform: translateY(-2px);
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #10b981, #059669);
        border-color: #10b981;
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .pagination .page-item.disabled .page-link {
        background: #0f172a;
        border-color: #1e293b;
        color: #475569;
        cursor: not-allowed;
    }

    .pagination .page-item.disabled .page-link:hover {
        transform: none;
        border-color: #1e293b;
        color: #475569;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="payments-header">
        <h1 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem;">
            <i class="fas fa-money-bill-wave me-2"></i>
            Gestion des Paiements
        </h1>
        <p style="opacity: 0.9; font-size: 1.1rem;">Suivi et gestion de tous les paiements étudiants</p>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ number_format($stats['completed'], 0, ',', ' ') }} XOF</div>
            <div class="stat-label">
                <i class="fas fa-check-circle me-1"></i>
                Paiements Reçus ({{ $stats['count_completed'] }})
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-value" style="color: #f59e0b;">{{ number_format($stats['pending'], 0, ',', ' ') }} XOF</div>
            <div class="stat-label">
                <i class="fas fa-clock me-1"></i>
                En Attente ({{ $stats['count_pending'] }})
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-value" style="color: #ef4444;">{{ number_format($stats['cancelled'], 0, ',', ' ') }} XOF</div>
            <div class="stat-label">
                <i class="fas fa-times-circle me-1"></i>
                Annulés ({{ $stats['count_cancelled'] }})
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-value" style="color: #8b5cf6;">{{ number_format($stats['total'], 0, ',', ' ') }} XOF</div>
            <div class="stat-label">
                <i class="fas fa-chart-line me-1"></i>
                Total
            </div>
        </div>
    </div>

    <!-- Table des paiements -->
    <div class="payments-table-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="color: white; font-size: 1.5rem; margin: 0;">
                <i class="fas fa-list me-2"></i>
                Liste des Paiements
            </h2>
            <span style="color: #94a3b8;">{{ $payments->total() }} paiements</span>
        </div>

        <div style="overflow-x: auto;">
            <table class="payments-table">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Étudiant</th>
                        <th>Formation</th>
                        <th>Montant</th>
                        <th>Tranche</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td>
                            <code style="background: #0f172a; padding: 0.3rem 0.6rem; border-radius: 6px; color: #10b981;">
                                {{ $payment->payment_reference }}
                            </code>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $payment->prenom }} {{ $payment->nom }}</div>
                            <div style="font-size: 0.85rem; color: #94a3b8;">{{ $payment->email }}</div>
                            @if($payment->student_id)
                                <div style="font-size: 0.8rem; color: #10b981;">
                                    <i class="fas fa-id-card me-1"></i>{{ $payment->student_id }}
                                </div>
                            @endif
                        </td>
                        <td>{{ $payment->choix_formation ?? 'Non définie' }}</td>
                        <td>
                            <span style="font-weight: 700; color: #10b981;">
                                {{ number_format($payment->amount, 0, ',', ' ') }} XOF
                            </span>
                        </td>
                        <td>
                            <span style="background: #0f172a; padding: 0.3rem 0.6rem; border-radius: 6px;">
                                Tranche {{ $payment->installment_number }}
                            </span>
                        </td>
                        <td>
                            @if($payment->status === 'completed')
                                <span class="badge badge-completed">
                                    <i class="fas fa-check-circle me-1"></i>Complété
                                </span>
                            @elseif($payment->status === 'pending')
                                <span class="badge badge-pending">
                                    <i class="fas fa-clock me-1"></i>En attente
                                </span>
                            @else
                                <span class="badge badge-cancelled">
                                    <i class="fas fa-times-circle me-1"></i>Annulé
                                </span>
                            @endif
                        </td>
                        <td>
                            <div>{{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') }}</div>
                            <div style="font-size: 0.85rem; color: #94a3b8;">
                                {{ \Carbon\Carbon::parse($payment->created_at)->format('H:i') }}
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn-view">
                                <i class="fas fa-eye me-1"></i>Voir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 3rem; color: #64748b;">
                            <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                            <p>Aucun paiement trouvé</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>sur cett
        </div>

        <!-- Pagination -->
        @if($payments->hasPages())
        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #334155;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <div style="color: #94a3b8; font-size: 0.9rem;">
                    <i class="fas fa-info-circle me-1"></i>
                    Affichage de {{ $payments->firstItem() }} à {{ $payments->lastItem() }} sur {{ $payments->total() }} paiements
                </div>
            </div>
            {{ $payments->links('vendor.pagination.custom') }}
        </div>
        @endif
    </div>
</div>
@endsection
