@extends('layouts.admin')

@section('title', 'Détails - Travaux Pratiques')

@section('content')
<div class="container-fluid px-4">
    <!-- Header avec breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Travaux Pratiques</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-warning">
                <i class="fas fa-tasks me-2"></i>Analyse Détaillée - Travaux Pratiques
            </h1>
        </div>
    </div>

    <!-- KPI Principal -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-warning text-white">
                <div class="card-body text-center py-4">
                    <div class="display-3 fw-bold mb-2">{{ $data['main_kpi'] ?? '3,456' }}</div>
                    <h4 class="mb-0">TP Soumis Total</h4>
                    <div class="mt-2">
                        <span class="badge bg-light text-warning fs-6">
                            <i class="fas fa-arrow-up me-1"></i>+{{ $data['growth'] ?? '25' }}% ce mois
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par statut -->
    <div class="row mb-4">
        @foreach($data['tp_stats'] ?? [
            ['label' => 'En Cours', 'count' => 567, 'percentage' => 16, 'color' => 'primary', 'icon' => 'edit'],
            ['label' => 'En Validation', 'count' => 289, 'percentage' => 8, 'color' => 'info', 'icon' => 'hourglass-half'],
            ['label' => 'Validés', 'count' => 2345, 'percentage' => 68, 'color' => 'success', 'icon' => 'check-circle'],
            ['label' => 'Rejetés', 'count' => 255, 'percentage' => 8, 'color' => 'danger', 'icon' => 'times-circle']
        ] as $stat)
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-{{ $stat['color'] }} mb-3">
                        <i class="fas fa-{{ $stat['icon'] }} fa-3x"></i>
                    </div>
                    <div class="h2 text-{{ $stat['color'] }} mb-1">{{ $stat['count'] }}</div>
                    <h6 class="text-muted mb-2">{{ $stat['label'] }}</h6>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-{{ $stat['color'] }}" style="width: {{ $stat['percentage'] }}%"></div>
                    </div>
                    <small class="text-muted">{{ $stat['percentage'] }}% du total</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Graphiques d'analyse -->
    <div class="row mb-4">
        <!-- Évolution des TP -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-area text-warning me-2"></i>Évolution des TP par Mois
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="tpEvolutionChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- TP par formation -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-donut text-warning me-2"></i>Par Formation
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="tpFormationChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Métriques de performance et top étudiants -->
    <div class="row mb-4">
        <!-- Métriques de performance -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar text-warning me-2"></i>Métriques de Performance
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-success mb-1">{{ $data['avg_score'] ?? '15.8' }}/20</div>
                                <small class="text-muted">Score Moyen</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-info mb-1">{{ $data['avg_time'] ?? '8.5' }}j</div>
                                <small class="text-muted">Temps Moyen</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-warning mb-1">{{ $data['success_rate'] ?? '78' }}%</div>
                                <small class="text-muted">Taux de Réussite</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-primary mb-1">{{ $data['retry_rate'] ?? '15' }}%</div>
                                <small class="text-muted">Taux de Reprise</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Graphique de tendance -->
                    <div class="mt-4">
                        <h6 class="text-muted mb-3">Tendance des Scores</h6>
                        <canvas id="scoresTrendChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top étudiants TP -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-medal text-warning me-2"></i>Top 5 Étudiants TP
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($data['top_students'] ?? [
                            ['name' => 'Léa Martinez', 'tp_count' => 28, 'avg_score' => 18.9, 'formation' => 'Design Graphique'],
                            ['name' => 'Kevin Dubois', 'tp_count' => 25, 'avg_score' => 18.5, 'formation' => 'Community Management'],
                            ['name' => 'Amélie Rousseau', 'tp_count' => 24, 'avg_score' => 18.2, 'formation' => 'Intelligence Artificielle'],
                            ['name' => 'Lucas Bernard', 'tp_count' => 23, 'avg_score' => 17.8, 'formation' => 'Gestion Informatique'],
                            ['name' => 'Chloé Moreau', 'tp_count' => 22, 'avg_score' => 17.5, 'formation' => 'Design Graphique']
                        ] as $index => $student)
                        <div class="list-group-item d-flex justify-content-between align-items-start border-0 px-0">
                            <div class="d-flex align-items-start">
                                <div class="badge bg-warning rounded-pill me-3 mt-1">{{ $index + 1 }}</div>
                                <div>
                                    <div class="fw-semibold">{{ $student['name'] }}</div>
                                    <small class="text-muted">{{ $student['tp_count'] }} TP soumis</small><br>
                                    <small class="text-primary">{{ $student['formation'] }}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-success">{{ $student['avg_score'] }}/20</div>
                                <small class="text-muted">Moyenne</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des TP récents -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list text-warning me-2"></i>TP Récents en Validation
                    </h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-success">
                            <i class="fas fa-check-double me-1"></i>Valider Sélection
                        </button>
                        <button class="btn btn-outline-danger">
                            <i class="fas fa-times me-1"></i>Rejeter Sélection
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="30">
                                        <input type="checkbox" class="form-check-input" id="selectAll">
                                    </th>
                                    <th>TP</th>
                                    <th>Étudiant</th>
                                    <th>Formation</th>
                                    <th>Type</th>
                                    <th>Date Soumission</th>
                                    <th>Délai</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['recent_tp'] ?? [
                                    ['title' => 'TP Photoshop - Retouche Portrait', 'student' => 'Marie Leroy', 'formation' => 'Design Graphique', 'type' => 'Pratique', 'date' => '2024-01-15', 'days_ago' => 2],
                                    ['title' => 'TP Instagram - Stratégie Contenu', 'student' => 'Paul Martin', 'formation' => 'Community Management', 'type' => 'Stratégie', 'date' => '2024-01-14', 'days_ago' => 3],
                                    ['title' => 'TP Python - Algorithme ML', 'student' => 'Sophie Chen', 'formation' => 'Intelligence Artificielle', 'type' => 'Code', 'date' => '2024-01-13', 'days_ago' => 4],
                                    ['title' => 'TP SQL - Base de Données', 'student' => 'Antoine Dubois', 'formation' => 'Gestion Informatique', 'type' => 'Technique', 'date' => '2024-01-12', 'days_ago' => 5],
                                    ['title' => 'TP Illustrator - Logo Design', 'student' => 'Emma Rousseau', 'formation' => 'Design Graphique', 'type' => 'Créatif', 'date' => '2024-01-11', 'days_ago' => 6]
                                ] as $tp)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input tp-checkbox">
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $tp['title'] }}</div>
                                    </td>
                                    <td>{{ $tp['student'] }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $tp['formation'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $tp['type'] }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($tp['date'])->format('d/m/Y') }}</td>
                                    <td>
                                        @if($tp['days_ago'] <= 3)
                                            <span class="badge bg-success">{{ $tp['days_ago'] }}j</span>
                                        @elseif($tp['days_ago'] <= 7)
                                            <span class="badge bg-warning">{{ $tp['days_ago'] }}j</span>
                                        @else
                                            <span class="badge bg-danger">{{ $tp['days_ago'] }}j</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" title="Valider">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" title="Rejeter">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <button class="btn btn-outline-info" title="Commentaire">
                                                <i class="fas fa-comment"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Insights et actions -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb text-warning me-2"></i>Insights TP
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-success border-0">
                        <h6 class="alert-heading"><i class="fas fa-trophy me-2"></i>Excellentes Performances</h6>
                        <ul class="mb-0">
                            <li>+25% de soumissions ce mois</li>
                            <li>78% de taux de réussite</li>
                            <li>Score moyen en progression</li>
                        </ul>
                    </div>
                    <div class="alert alert-warning border-0">
                        <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Points d'Attention</h6>
                        <ul class="mb-0">
                            <li>289 TP en attente de validation</li>
                            <li>Délai moyen de correction : 8.5j</li>
                            <li>15% de taux de reprise</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt text-warning me-2"></i>Actions Rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-warning">
                            <i class="fas fa-plus me-2"></i>Nouveau TP Type
                        </button>
                        <button class="btn btn-success">
                            <i class="fas fa-check-double me-2"></i>Validation Rapide
                        </button>
                        <button class="btn btn-info">
                            <i class="fas fa-clock me-2"></i>Rappels Automatiques
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-file-export me-2"></i>Export Rapport
                        </button>
                        <button class="btn btn-secondary">
                            <i class="fas fa-cog me-2"></i>Paramètres TP
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique évolution des TP
    const tpEvolutionCtx = document.getElementById('tpEvolutionChart').getContext('2d');
    new Chart(tpEvolutionCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [
                {
                    label: 'TP Soumis',
                    data: [220, 245, 268, 290, 315, 340, 365, 390, 415, 440, 465, 490],
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'TP Validés',
                    data: [172, 191, 209, 226, 246, 265, 285, 304, 324, 343, 363, 382],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            }
        }
    });

    // Graphique TP par formation
    const tpFormationCtx = document.getElementById('tpFormationChart').getContext('2d');
    new Chart(tpFormationCtx, {
        type: 'doughnut',
        data: {
            labels: ['Design Graphique', 'Community Management', 'Intelligence Artificielle', 'Gestion Informatique'],
            datasets: [{
                data: [1730, 865, 692, 169],
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Graphique tendance des scores
    const scoresTrendCtx = document.getElementById('scoresTrendChart').getContext('2d');
    new Chart(scoresTrendCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
            datasets: [{
                label: 'Score moyen',
                data: [14.8, 15.2, 15.5, 15.7, 15.8, 16.1],
                borderColor: '#17a2b8',
                backgroundColor: 'rgba(23, 162, 184, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    min: 14,
                    max: 17,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Gestion des checkboxes
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.tp-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
});
</script>
@endpush
@endsection
