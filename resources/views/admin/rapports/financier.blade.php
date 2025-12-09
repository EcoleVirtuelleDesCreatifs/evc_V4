@extends('layouts.admin')

@section('title', 'Rapport Financier')

@push('styles')
<style>
    body {
        background: #0f172a;
    }

    .financial-header {
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(255, 193, 7, 0.3);
    }

    .financial-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stat-card-financial {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card-financial::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .stat-card-financial.revenue::before {
        background: linear-gradient(90deg, #56ab2f 0%, #a8e6cf 100%);
    }

    .stat-card-financial.expenses::before {
        background: linear-gradient(90deg, #f093fb 0%, #f5576c 100%);
    }

    .stat-card-financial.pending::before {
        background: linear-gradient(90deg, #ffc107 0%, #ffb300 100%);
    }

    .stat-card-financial.balance::before {
        background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
    }

    .stat-card-financial:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 1rem 0;
    }

    .stat-label {
        color: #94a3b8;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .chart-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
    }

    .transactions-table {
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
        background: rgba(255, 255, 255, 0.05);
    }

    .table tbody tr {
        border-bottom: 1px solid #334155;
        transition: background 0.2s;
    }

    .table tbody tr:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .badge-success {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-weight: 600;
    }

    .badge-pending {
        background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-weight: 600;
    }

    .badge-failed {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
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
    <div class="financial-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="financial-title">
                    <i class="fas fa-chart-pie me-3"></i>Rapport Financier
                </h1>
                <p class="mb-0">Aperçu complet des finances de la plateforme</p>
            </div>
            <div>
                <button class="export-btn" onclick="exportFinancialReport()">
                    <i class="fas fa-download me-2"></i>Exporter PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiques Financières -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card-financial revenue">
                <div class="stat-label">Revenus Totaux</div>
                <div class="stat-value text-success">{{ number_format($financial['total_revenue'], 0, ',', ' ') }} FCFA</div>
                <div class="text-muted">
                    <i class="fas fa-arrow-up text-success me-1"></i>
                    +{{ number_format($financial['monthly_revenue'], 0, ',', ' ') }} FCFA ce mois
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card-financial expenses">
                <div class="stat-label">Factures Émises</div>
                <div class="stat-value text-info">{{ number_format($financial['total_invoices'], 0, ',', ' ') }} FCFA</div>
                <div class="text-muted">
                    <i class="fas fa-file-invoice me-1"></i>
                    Total des facturations
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card-financial pending">
                <div class="stat-value text-warning">{{ number_format($financial['pending_payments'], 0, ',', ' ') }} FCFA</div>
                <div class="stat-label">Paiements en Attente</div>
                <div class="text-muted">
                    <i class="fas fa-clock me-1"></i>
                    À encaisser
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card-financial balance">
                <div class="stat-label">Balance</div>
                <div class="stat-value {{ $financial['balance'] >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($financial['balance'], 0, ',', ' ') }} FCFA
                </div>
                <div class="text-muted">
                    <i class="fas fa-balance-scale me-1"></i>
                    Différentiel
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mb-4">
        <div class="col-md-8 mb-3">
            <div class="chart-card">
                <h5 class="mb-4">
                    <i class="fas fa-chart-line me-2"></i>Évolution des Revenus (12 derniers mois)
                </h5>
                <canvas id="revenueChart" height="80"></canvas>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="chart-card">
                <h5 class="mb-4">
                    <i class="fas fa-chart-pie me-2"></i>Répartition des Paiements
                </h5>
                <canvas id="paymentsChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Dernières Transactions -->
    <div class="transactions-table">
        <div class="p-4">
            <h5 class="mb-4">
                <i class="fas fa-list me-2"></i>Dernières Transactions
            </h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Étudiant</th>
                            <th>Formation</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y') }}</td>
                            <td>{{ $transaction->student_name }}</td>
                            <td>{{ $transaction->formation }}</td>
                            <td><strong>{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</strong></td>
                            <td>
                                @if($transaction->status === 'completed')
                                    <span class="badge-success">Complété</span>
                                @elseif($transaction->status === 'pending')
                                    <span class="badge-pending">En attente</span>
                                @else
                                    <span class="badge-failed">Échoué</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-light" onclick="viewTransaction({{ $transaction->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucune transaction disponible</p>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Graphique d'évolution des revenus
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
        datasets: [{
            label: 'Revenus (FCFA)',
            data: @json($monthlyRevenues),
            borderColor: '#56ab2f',
            backgroundColor: 'rgba(86, 171, 47, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: '#94a3b8'
                },
                grid: {
                    color: '#334155'
                }
            },
            x: {
                ticks: {
                    color: '#94a3b8'
                },
                grid: {
                    color: '#334155'
                }
            }
        }
    }
});

// Graphique de répartition des paiements
const paymentsCtx = document.getElementById('paymentsChart').getContext('2d');
new Chart(paymentsCtx, {
    type: 'doughnut',
    data: {
        labels: ['Complétés', 'En attente', 'Échoués'],
        datasets: [{
            data: @json($paymentDistribution),
            backgroundColor: ['#56ab2f', '#ffc107', '#f5576c']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: '#94a3b8'
                }
            }
        }
    }
});

function exportFinancialReport() {
    window.location.href = '{{ route("admin.rapports.download", "financial") }}';
}

function viewTransaction(id) {
    // Implémenter la visualisation de transaction
    alert('Voir transaction #' + id);
}
</script>
@endpush
