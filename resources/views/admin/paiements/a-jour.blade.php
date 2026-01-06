@extends('layouts.admin')

@section('title', 'Paiements à Jour')

@push('styles')
<style>
    body {
        background: #0f172a;
    }

    .payments-header {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(16, 185, 129, 0.3);
    }

    .stat-card-payment {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        margin-bottom: 1.5rem;
    }

    .stat-value-payment {
        font-size: 2rem;
        font-weight: 700;
        color: #10b981;
    }

    .students-table {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        overflow: hidden;
    }

    .table {
        color: white;
        margin-bottom: 0;
    }

    .table thead {
        background: rgba(16, 185, 129, 0.1);
    }

    .table tbody tr {
        border-bottom: 1px solid #334155;
        transition: background 0.2s;
    }

    .table tbody tr:hover {
        background: rgba(16, 185, 129, 0.05);
    }

    .badge-paid {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-weight: 600;
    }

    .badge-formation {
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .export-btn {
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .export-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(79, 195, 247, 0.4);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="payments-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-2">
                    <i class="fas fa-check-circle me-3"></i>Paiements à Jour
                </h1>
                <p class="mb-0">Liste des étudiants ayant soldé leurs frais de formation</p>
            </div>
            <div>
                <button class="export-btn" onclick="exportData()">
                    <i class="fas fa-download me-2"></i>Exporter Excel
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card-payment">
                <div class="text-muted mb-1">Total Étudiants à Jour</div>
                <div class="stat-value-payment">{{ $stats['total'] }}</div>
                <div class="text-success mt-2">
                    <i class="fas fa-check-circle me-1"></i>{{ $stats['percentage'] }}% à jour
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-payment">
                <div class="text-muted mb-1">Montant Total Encaissé</div>
                <div class="stat-value-payment">{{ number_format($stats['total_amount'], 0, ',', ' ') }} FCFA</div>
                <div class="text-muted mt-2">
                    <i class="fas fa-coins me-1"></i>Paiements complets
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-payment">
                <div class="text-muted mb-1">Montant par Étudiant</div>
                <div class="stat-value-payment">350 000 FCFA</div>
                <div class="text-muted mt-2">
                    <i class="fas fa-tag me-1"></i>Tarif formation
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des étudiants -->
    <div class="students-table">
        <div class="p-4">
            <h5 class="mb-4 text-white">
                <i class="fas fa-list me-2"></i>Liste des Étudiants ({{ $stats['total'] }})
            </h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Étudiant</th>
                            <th>Email</th>
                            <th>Formation</th>
                            <th>Montant Payé</th>
                            <th>Statut</th>
                            <th>Date d'Inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td>
                                <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                            </td>
                            <td>{{ $student->email }}</td>
                            <td>
                                <span class="badge-formation bg-primary">{{ $student->program }}</span>
                            </td>
                            <td>
                                <strong class="text-success">{{ number_format($student->amount_paid, 0, ',', ' ') }} FCFA</strong>
                            </td>
                            <td>
                                <span class="badge-paid">
                                    <i class="fas fa-check me-1"></i>À jour
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($student->created_at)->format('d/m/Y') }}</td>
                            <td>
                                @if(!empty($student->pre_registration_id))
                                    <a href="{{ route('admin.paiements.receipt', $student->pre_registration_id) }}" class="btn btn-sm btn-secondary" title="Télécharger le reçu" target="_blank">
                                        <i class="fas fa-receipt me-1"></i>Reçu
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-secondary" disabled title="Reçu indisponible">
                                        <i class="fas fa-ban me-1"></i>Reçu
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun étudiant à jour pour le moment</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportData() {
    alert('Export Excel en cours de développement...');
}
</script>
@endpush
