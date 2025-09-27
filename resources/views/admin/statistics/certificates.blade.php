@extends('layouts.admin')

@section('title', 'Détails - Certificats Éligibles')

@section('content')
<div class="container-fluid px-4">
    <!-- Header avec breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Certificats Éligibles</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-warning">
                <i class="fas fa-certificate me-2"></i>Analyse Détaillée - Certificats Éligibles
            </h1>
        </div>
    </div>

    <!-- KPI Principal -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-warning text-white">
                <div class="card-body text-center py-4">
                    <div class="display-3 fw-bold mb-2">{{ $data['main_kpi'] ?? '1,456' }}</div>
                    <h4 class="mb-0">Étudiants Éligibles</h4>
                    <div class="mt-2">
                        <span class="badge bg-light text-warning fs-6">
                            <i class="fas fa-arrow-up me-1"></i>+{{ $data['growth'] ?? '28' }}% ce mois
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par statut -->
    <div class="row mb-4">
        @foreach($data['certificate_stats'] ?? [
            ['label' => 'Éligibles', 'count' => 1456, 'percentage' => 65, 'color' => 'success', 'icon' => 'check-circle'],
            ['label' => 'En Attente', 'count' => 234, 'percentage' => 10, 'color' => 'warning', 'icon' => 'clock'],
            ['label' => 'Délivrés', 'count' => 1987, 'percentage' => 88, 'color' => 'primary', 'icon' => 'award'],
            ['label' => 'En Cours', 'count' => 567, 'percentage' => 25, 'color' => 'info', 'icon' => 'hourglass-half']
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
        <!-- Évolution des certificats -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line text-warning me-2"></i>Évolution des Certificats Délivrés
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="certificatesChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Répartition par formation -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie text-warning me-2"></i>Par Formation
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="formationCertificatesChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top étudiants et métriques -->
    <div class="row mb-4">
        <!-- Top étudiants certifiés -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-trophy text-warning me-2"></i>Top Étudiants Certifiés
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($data['top_certified'] ?? [
                            ['name' => 'Marie Dubois', 'formation' => 'Design Graphique', 'score' => 19.2, 'date' => '2024-01-15'],
                            ['name' => 'Pierre Martin', 'formation' => 'Community Management', 'score' => 18.8, 'date' => '2024-01-14'],
                            ['name' => 'Sophie Laurent', 'formation' => 'Intelligence Artificielle', 'score' => 18.5, 'date' => '2024-01-13'],
                            ['name' => 'Jean Rousseau', 'formation' => 'Gestion Informatique', 'score' => 18.2, 'date' => '2024-01-12'],
                            ['name' => 'Emma Chen', 'formation' => 'Design Graphique', 'score' => 17.9, 'date' => '2024-01-11']
                        ] as $index => $student)
                        <div class="list-group-item d-flex justify-content-between align-items-start border-0 px-0">
                            <div class="d-flex align-items-start">
                                <div class="badge bg-warning rounded-pill me-3 mt-1">{{ $index + 1 }}</div>
                                <div>
                                    <div class="fw-semibold">{{ $student['name'] }}</div>
                                    <small class="text-muted">{{ $student['formation'] }}</small><br>
                                    <small class="text-primary">{{ \Carbon\Carbon::parse($student['date'])->format('d/m/Y') }}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-success">{{ $student['score'] }}/20</div>
                                <small class="text-muted">Score final</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Métriques de certification -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar text-warning me-2"></i>Métriques de Certification
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-success mb-1">{{ $data['success_rate'] ?? '87' }}%</div>
                                <small class="text-muted">Taux de Réussite</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-primary mb-1">{{ $data['avg_score'] ?? '16.8' }}/20</div>
                                <small class="text-muted">Score Moyen</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-info mb-1">{{ $data['avg_time'] ?? '5.2' }} mois</div>
                                <small class="text-muted">Durée Moyenne</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-warning mb-1">{{ $data['excellence_rate'] ?? '42' }}%</div>
                                <small class="text-muted">Mentions Excellent</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Graphique scores -->
                    <canvas id="scoresChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Étudiants éligibles en attente -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock text-warning me-2"></i>Étudiants Éligibles en Attente
                    </h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-success">
                            <i class="fas fa-certificate me-1"></i>Délivrer Certificats
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-envelope me-1"></i>Notifier Étudiants
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
                                    <th>Étudiant</th>
                                    <th>Formation</th>
                                    <th>Score Final</th>
                                    <th>TP Validés</th>
                                    <th>Projets Validés</th>
                                    <th>Date Éligibilité</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['eligible_students'] ?? [
                                    ['name' => 'Alice Bernard', 'email' => 'alice.bernard@email.com', 'formation' => 'Design Graphique', 'score' => 17.5, 'tp_count' => 28, 'project_count' => 5, 'date' => '2024-01-15'],
                                    ['name' => 'Marc Petit', 'email' => 'marc.petit@email.com', 'formation' => 'Community Management', 'score' => 16.8, 'tp_count' => 25, 'project_count' => 4, 'date' => '2024-01-14'],
                                    ['name' => 'Julie Moreau', 'email' => 'julie.moreau@email.com', 'formation' => 'Intelligence Artificielle', 'score' => 18.2, 'tp_count' => 30, 'project_count' => 6, 'date' => '2024-01-13'],
                                    ['name' => 'Tom Leroy', 'email' => 'tom.leroy@email.com', 'formation' => 'Gestion Informatique', 'score' => 16.5, 'tp_count' => 22, 'project_count' => 4, 'date' => '2024-01-12'],
                                    ['name' => 'Sarah Cohen', 'email' => 'sarah.cohen@email.com', 'formation' => 'Design Graphique', 'score' => 17.8, 'tp_count' => 26, 'project_count' => 5, 'date' => '2024-01-11']
                                ] as $student)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input student-checkbox">
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $student['name'] }}</div>
                                        <small class="text-muted">{{ $student['email'] }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $student['formation'] }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">{{ $student['score'] }}/20</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $student['tp_count'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $student['project_count'] }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($student['date'])->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" title="Voir profil">
                                                <i class="fas fa-user"></i>
                                            </button>
                                            <button class="btn btn-outline-success" title="Délivrer certificat">
                                                <i class="fas fa-certificate"></i>
                                            </button>
                                            <button class="btn btn-outline-info" title="Envoyer email">
                                                <i class="fas fa-envelope"></i>
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
                        <i class="fas fa-lightbulb text-warning me-2"></i>Insights Certificats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-success border-0">
                        <h6 class="alert-heading"><i class="fas fa-medal me-2"></i>Excellents Résultats</h6>
                        <ul class="mb-0">
                            <li>87% de taux de réussite global</li>
                            <li>42% de mentions excellent</li>
                            <li>Score moyen en progression</li>
                        </ul>
                    </div>
                    <div class="alert alert-info border-0">
                        <h6 class="alert-heading"><i class="fas fa-chart-line me-2"></i>Recommandations</h6>
                        <ul class="mb-0">
                            <li>Automatiser la délivrance</li>
                            <li>Créer des badges numériques</li>
                            <li>Améliorer le suivi post-certification</li>
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
                            <i class="fas fa-certificate me-2"></i>Délivrer en Lot
                        </button>
                        <button class="btn btn-success">
                            <i class="fas fa-envelope-bulk me-2"></i>Email de Félicitations
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-file-export me-2"></i>Export Certificats
                        </button>
                        <button class="btn btn-info">
                            <i class="fas fa-chart-bar me-2"></i>Rapport Détaillé
                        </button>
                        <button class="btn btn-secondary">
                            <i class="fas fa-cog me-2"></i>Paramètres
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
    // Graphique évolution des certificats
    const certificatesCtx = document.getElementById('certificatesChart').getContext('2d');
    new Chart(certificatesCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [
                {
                    label: 'Certificats délivrés',
                    data: [45, 52, 48, 61, 55, 67, 73, 69, 78, 85, 92, 89],
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Étudiants éligibles',
                    data: [52, 58, 54, 68, 62, 75, 82, 78, 87, 95, 103, 98],
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
    const formationCertificatesCtx = document.getElementById('formationCertificatesChart').getContext('2d');
    new Chart(formationCertificatesCtx, {
        type: 'doughnut',
        data: {
            labels: ['Design Graphique', 'Community Management', 'Intelligence Artificielle', 'Gestion Informatique'],
            datasets: [{
                data: [892, 456, 367, 272],
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

    // Graphique distribution des scores
    const scoresCtx = document.getElementById('scoresChart').getContext('2d');
    new Chart(scoresCtx, {
        type: 'bar',
        data: {
            labels: ['10-12', '12-14', '14-16', '16-18', '18-20'],
            datasets: [{
                label: 'Nombre d\'étudiants',
                data: [45, 156, 387, 542, 298],
                backgroundColor: [
                    '#dc3545',
                    '#fd7e14', 
                    '#ffc107',
                    '#28a745',
                    '#20c997'
                ],
                borderWidth: 0
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

    // Gestion des checkboxes
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.student-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
});
</script>
@endpush
@endsection
