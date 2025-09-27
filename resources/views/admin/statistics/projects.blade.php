@extends('layouts.admin')

@section('title', 'Détails - Projets Étudiants')

@section('content')
<div class="container-fluid px-4">
    <!-- Header avec breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Projets Étudiants</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-info">
                <i class="fas fa-project-diagram me-2"></i>Analyse Détaillée - Projets Étudiants
            </h1>
        </div>
    </div>

    <!-- KPI Principal -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-info text-white">
                <div class="card-body text-center py-4">
                    <div class="display-3 fw-bold mb-2">{{ $data['main_kpi'] ?? '2,847' }}</div>
                    <h4 class="mb-0">Projets Soumis Total</h4>
                    <div class="mt-2">
                        <span class="badge bg-light text-info fs-6">
                            <i class="fas fa-arrow-up me-1"></i>+{{ $data['growth'] ?? '18' }}% ce mois
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par statut -->
    <div class="row mb-4">
        @foreach($data['project_stats'] ?? [
            ['label' => 'En Cours', 'count' => 456, 'percentage' => 16, 'color' => 'warning', 'icon' => 'clock'],
            ['label' => 'En Validation', 'count' => 234, 'percentage' => 8, 'color' => 'info', 'icon' => 'hourglass-half'],
            ['label' => 'Validés', 'count' => 1987, 'percentage' => 70, 'color' => 'success', 'icon' => 'check-circle'],
            ['label' => 'Rejetés', 'count' => 170, 'percentage' => 6, 'color' => 'danger', 'icon' => 'times-circle']
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
        <!-- Évolution des soumissions -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line text-info me-2"></i>Évolution des Soumissions
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="submissionsChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Répartition par formation -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie text-info me-2"></i>Par Formation
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="formationProjectsChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top projets et statistiques détaillées -->
    <div class="row mb-4">
        <!-- Top 5 projets -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star text-warning me-2"></i>Top 5 Projets du Mois
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($data['top_projects'] ?? [
                            ['title' => 'Identité Visuelle Startup Tech', 'student' => 'Marie Dubois', 'score' => 19.5, 'formation' => 'Design Graphique'],
                            ['title' => 'Campagne Social Media Restaurant', 'student' => 'Jean Martin', 'score' => 18.8, 'formation' => 'Community Management'],
                            ['title' => 'Chatbot IA Service Client', 'student' => 'Sophie Laurent', 'score' => 18.2, 'formation' => 'Intelligence Artificielle'],
                            ['title' => 'Site E-commerce Responsive', 'student' => 'Pierre Durand', 'score' => 17.9, 'formation' => 'Gestion Informatique'],
                            ['title' => 'Logo et Charte Graphique ONG', 'student' => 'Emma Rousseau', 'score' => 17.5, 'formation' => 'Design Graphique']
                        ] as $index => $project)
                        <div class="list-group-item d-flex justify-content-between align-items-start border-0 px-0">
                            <div class="d-flex align-items-start">
                                <div class="badge bg-info rounded-pill me-3 mt-1">{{ $index + 1 }}</div>
                                <div>
                                    <div class="fw-semibold">{{ $project['title'] }}</div>
                                    <small class="text-muted">Par {{ $project['student'] }}</small><br>
                                    <small class="text-primary">{{ $project['formation'] }}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-success">{{ $project['score'] }}/20</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Métriques de performance -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar text-info me-2"></i>Métriques de Performance
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-success mb-1">{{ $data['avg_score'] ?? '16.8' }}/20</div>
                                <small class="text-muted">Score Moyen</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-info mb-1">{{ $data['avg_time'] ?? '12.5' }}j</div>
                                <small class="text-muted">Temps Moyen</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-warning mb-1">{{ $data['revision_rate'] ?? '23' }}%</div>
                                <small class="text-muted">Taux Révision</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-primary mb-1">{{ $data['excellence_rate'] ?? '34' }}%</div>
                                <small class="text-muted">Taux Excellence</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des projets récents -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list text-info me-2"></i>Projets Récents
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Projet</th>
                                    <th>Étudiant</th>
                                    <th>Formation</th>
                                    <th>Statut</th>
                                    <th>Score</th>
                                    <th>Date Soumission</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['recent_projects'] ?? [
                                    ['title' => 'Logo Entreprise Tech', 'student' => 'Alice Bernard', 'formation' => 'Design Graphique', 'status' => 'validated', 'score' => 18.5, 'date' => '2024-01-15'],
                                    ['title' => 'Stratégie Instagram Café', 'student' => 'Marc Petit', 'formation' => 'Community Management', 'status' => 'pending', 'score' => null, 'date' => '2024-01-14'],
                                    ['title' => 'Système de Recommandation', 'student' => 'Julie Moreau', 'formation' => 'Intelligence Artificielle', 'status' => 'in_progress', 'score' => null, 'date' => '2024-01-13'],
                                    ['title' => 'Application Mobile Fitness', 'student' => 'Tom Leroy', 'formation' => 'Gestion Informatique', 'status' => 'validated', 'score' => 17.2, 'date' => '2024-01-12'],
                                    ['title' => 'Identité Visuelle Restaurant', 'student' => 'Sarah Cohen', 'formation' => 'Design Graphique', 'status' => 'rejected', 'score' => 12.8, 'date' => '2024-01-11']
                                ] as $project)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $project['title'] }}</div>
                                    </td>
                                    <td>{{ $project['student'] }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $project['formation'] }}</span>
                                    </td>
                                    <td>
                                        @if($project['status'] === 'validated')
                                            <span class="badge bg-success">Validé</span>
                                        @elseif($project['status'] === 'pending')
                                            <span class="badge bg-info">En Validation</span>
                                        @elseif($project['status'] === 'in_progress')
                                            <span class="badge bg-warning">En Cours</span>
                                        @else
                                            <span class="badge bg-danger">Rejeté</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($project['score'])
                                            <span class="fw-semibold text-success">{{ $project['score'] }}/20</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($project['date'])->format('d/m/Y') }}</td>
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
                        <i class="fas fa-lightbulb text-warning me-2"></i>Insights
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-success border-0">
                        <h6 class="alert-heading"><i class="fas fa-check-circle me-2"></i>Points Forts</h6>
                        <ul class="mb-0">
                            <li>70% des projets sont validés</li>
                            <li>Score moyen en hausse (+0.8 points)</li>
                            <li>Temps de validation réduit</li>
                        </ul>
                    </div>
                    <div class="alert alert-info border-0">
                        <h6 class="alert-heading"><i class="fas fa-chart-line me-2"></i>Recommandations</h6>
                        <ul class="mb-0">
                            <li>Réduire le taux de révision (23%)</li>
                            <li>Améliorer les consignes projets</li>
                            <li>Renforcer l'accompagnement</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt text-info me-2"></i>Actions Rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-info">
                            <i class="fas fa-plus me-2"></i>Nouveau Projet Type
                        </button>
                        <button class="btn btn-success">
                            <i class="fas fa-check-double me-2"></i>Validation en Lot
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-file-export me-2"></i>Export Rapport
                        </button>
                        <button class="btn btn-warning">
                            <i class="fas fa-envelope me-2"></i>Relance Étudiants
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
    // Graphique évolution des soumissions
    const submissionsCtx = document.getElementById('submissionsChart').getContext('2d');
    new Chart(submissionsCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [
                {
                    label: 'Projets soumis',
                    data: [180, 195, 210, 225, 240, 255, 270, 285, 300, 315, 330, 345],
                    borderColor: '#17a2b8',
                    backgroundColor: 'rgba(23, 162, 184, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Projets validés',
                    data: [126, 137, 147, 158, 168, 179, 189, 200, 210, 221, 231, 242],
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

    // Graphique répartition par formation
    const formationProjectsCtx = document.getElementById('formationProjectsChart').getContext('2d');
    new Chart(formationProjectsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Design Graphique', 'Community Management', 'Intelligence Artificielle', 'Gestion Informatique'],
            datasets: [{
                data: [1420, 710, 468, 249],
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
});
</script>
@endpush
@endsection
