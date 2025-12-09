@extends('layouts.admin')

@section('title', 'Paiements à Solder')

@push('styles')
<style>
    body {
        background: #0f172a;
    }

    .payments-header {
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(255, 193, 7, 0.3);
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
        color: #ffc107;
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
        background: rgba(255, 193, 7, 0.1);
    }

    .table tbody tr {
        border-bottom: 1px solid #334155;
        transition: background 0.2s;
    }

    .table tbody tr:hover {
        background: rgba(255, 193, 7, 0.05);
    }

    .badge-partial {
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-weight: 600;
    }

    .progress-payment {
        height: 8px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .progress-bar-payment {
        height: 100%;
        background: linear-gradient(90deg, #ffc107 0%, #ff9800 100%);
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
                    <i class="fas fa-hourglass-half me-3"></i>Paiements à Solder
                </h1>
                <p class="mb-0">Étudiants ayant effectué un paiement partiel</p>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card-payment">
                <div class="text-muted mb-1">Étudiants Partiels</div>
                <div class="stat-value-payment">{{ $stats['total'] }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-payment">
                <div class="text-muted mb-1">Total Déjà Payé</div>
                <div class="stat-value-payment">{{ number_format($stats['total_paid'], 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-payment">
                <div class="text-muted mb-1">Reste à Encaisser</div>
                <div class="stat-value-payment">{{ number_format($stats['total_remaining'], 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
    </div>

    <!-- Tableau -->
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
                            <th>Payé</th>
                            <th>Reste</th>
                            <th>Progression</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td><strong>{{ $student->first_name }} {{ $student->last_name }}</strong></td>
                            <td>{{ $student->email }}</td>
                            <td><span class="badge bg-primary">{{ $student->program }}</span></td>
                            <td class="text-success"><strong>{{ number_format($student->amount_paid, 0, ',', ' ') }} FCFA</strong></td>
                            <td class="text-warning"><strong>{{ number_format($student->remaining, 0, ',', ' ') }} FCFA</strong></td>
                            <td>
                                @php $percentage = ($student->amount_paid / $student->total_amount) * 100; @endphp
                                <div>{{ number_format($percentage, 0) }}%</div>
                                <div class="progress-payment">
                                    <div class="progress-bar-payment" style="width: {{ $percentage }}%"></div>
                                </div>
                            </td>
                            <td><span class="badge-partial"><i class="fas fa-hourglass-half me-1"></i>Partiel</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun paiement partiel</p>
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
