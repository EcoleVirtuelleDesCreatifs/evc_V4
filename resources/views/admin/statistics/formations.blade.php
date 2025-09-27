@extends('layouts.admin')

@section('title', 'Détails - Programmes de Formation')

@section('content')
<div class="container-fluid px-4">
    <!-- Header avec breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Programmes de Formation</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-success">
                <i class="fas fa-graduation-cap me-2"></i>Analyse Détaillée - Programmes de Formation
            </h1>
        </div>
    </div>

    <!-- KPI Principal -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-success text-white">
                <div class="card-body text-center py-4">
                    <div class="display-3 fw-bold mb-2">{{ $data['main_kpi'] ?? '4' }}</div>
                    <h4 class="mb-0">Programmes Actifs</h4>
                    <div class="mt-2">
                        <span class="badge bg-light text-success fs-6">
                            <i class="fas fa-check me-1"></i>100% Opérationnels
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par formation -->
    <div class="row mb-4">
        @foreach($data['formations'] ?? [
            ['name' => 'Design Graphique', 'students' => 562, 'completion' => 87, 'color' => 'primary'],
            ['name' => 'Community Management', 'students' => 298, 'completion' => 92, 'color' => 'success'],
            ['name' => 'Intelligence Artificielle', 'students' => 234, 'completion' => 78, 'color' => 'warning'],
            ['name' => 'Gestion Informatique', 'students' => 156, 'completion' => 85, 'color' => 'danger']
        ] as $formation)
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-{{ $formation['color'] }} text-white">
                    <h6 class="card-title mb-0">{{ $formation['name'] }}</h6>
                </div>
                <div class="card-body text-center">
                    <div class="h3 text-{{ $formation['color'] }} mb-2">{{ $formation['students'] }}</div>
                    <p class="text-muted mb-2">Étudiants inscrits</p>
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-{{ $formation['color'] }}" style="width: {{ $formation['completion'] }}%"></div>
                    </div>
                    <small class="text-muted">{{ $formation['completion'] }}% de réussite</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Graphiques d'analyse -->
    <div class="row mb-4">
        <!-- Performance par formation -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar text-success me-2"></i>Performance par Formation
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="performanceChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Évolution des inscriptions -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line text-success me-2"></i>Évolution Mensuelle
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="evolutionChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Détails des formations -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list text-success me-2"></i>Détails des Formations
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Formation</th>
                                    <th>Étudiants</th>
                                    <th>Durée</th>
                                    <th>Taux de Réussite</th>
                                    <th>Score Moyen</th>
                                    <th>Certificats Délivrés</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['detailed_formations'] ?? [
                                    ['name' => 'Design Graphique', 'students' => 562, 'duration' => '6 mois', 'success_rate' => 87, 'avg_score' => 16.2, 'certificates' => 489],
                                    ['name' => 'Community Management', 'students' => 298, 'duration' => '4 mois', 'success_rate' => 92, 'avg_score' => 17.8, 'certificates' => 274],
                                    ['name' => 'Intelligence Artificielle', 'students' => 234, 'duration' => '8 mois', 'success_rate' => 78, 'avg_score' => 15.6, 'certificates' => 182],
                                    ['name' => 'Gestion Informatique', 'students' => 156, 'duration' => '5 mois', 'success_rate' => 85, 'avg_score' => 16.8, 'certificates' => 132]
                                ] as $formation)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $formation['name'] }}</div>
                                        <small class="text-muted">Programme complet</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $formation['students'] }}</span>
                                    </td>
                                    <td>{{ $formation['duration'] }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress me-2" style="width: 60px; height: 6px;">
                                                <div class="progress-bar bg-success" style="width: {{ $formation['success_rate'] }}%"></div>
                                            </div>
                                            <span class="text-success fw-semibold">{{ $formation['success_rate'] }}%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ $formation['avg_score'] }}/20</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ $formation['certificates'] }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" title="Modifier">
                                                <i class="fas fa-edit"></i>
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

    <!-- Insights et recommandations -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb text-warning me-2"></i>Insights & Recommandations
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert alert-success border-0">
                                <h6 class="alert-heading"><i class="fas fa-trophy me-2"></i>Performances Excellentes</h6>
                                <ul class="mb-0">
                                    <li>Community Management : 92% de réussite</li>
                                    <li>Design Graphique : Formation la plus populaire</li>
                                    <li>Taux global de satisfaction élevé</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-info border-0">
                                <h6 class="alert-heading"><i class="fas fa-chart-line me-2"></i>Axes d'Amélioration</h6>
                                <ul class="mb-0">
                                    <li>Renforcer le programme IA (78% de réussite)</li>
                                    <li>Développer plus de contenu pratique</li>
                                    <li>Améliorer l'accompagnement personnalisé</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt text-success me-2"></i>Actions Rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Nouvelle Formation
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-file-export me-2"></i>Rapport PDF
                        </button>
                        <button class="btn btn-info">
                            <i class="fas fa-file-excel me-2"></i>Export Excel
                        </button>
                        <button class="btn btn-warning">
                            <i class="fas fa-chart-bar me-2"></i>Analyse Avancée
                        </button>
                        <button class="btn btn-secondary">
                            <i class="fas fa-cog me-2"></i>Configuration
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
    // Graphique performance par formation
    const performanceCtx = document.getElementById('performanceChart').getContext('2d');
    new Chart(performanceCtx, {
        type: 'bar',
        data: {
            labels: ['Design Graphique', 'Community Management', 'Intelligence Artificielle', 'Gestion Informatique'],
            datasets: [
                {
                    label: 'Étudiants inscrits',
                    data: [562, 298, 234, 156],
                    backgroundColor: 'rgba(0, 123, 255, 0.7)',
                    borderColor: '#007bff',
                    borderWidth: 1
                },
                {
                    label: 'Taux de réussite (%)',
                    data: [87, 92, 78, 85],
                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                    borderColor: '#28a745',
                    borderWidth: 1,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Nombre d\'étudiants'
                    }
                },
                y1: {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Taux de réussite (%)'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });

    // Graphique évolution mensuelle
    const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
    new Chart(evolutionCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
            datasets: [{
                label: 'Nouvelles inscriptions',
                data: [45, 52, 48, 61, 55, 67],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
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
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
@endsection
