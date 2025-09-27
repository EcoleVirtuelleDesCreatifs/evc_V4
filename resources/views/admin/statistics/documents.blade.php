@extends('layouts.admin')

@section('title', 'Détails - Gestion Documents')

@section('content')
<div class="container-fluid px-4">
    <!-- Header avec breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Gestion Documents</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-danger">
                <i class="fas fa-file-alt me-2"></i>Analyse Détaillée - Gestion Documents
            </h1>
        </div>
    </div>

    <!-- KPI Principal -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-danger text-white">
                <div class="card-body text-center py-4">
                    <div class="display-3 fw-bold mb-2">{{ $data['main_kpi'] ?? '4,567' }}</div>
                    <h4 class="mb-0">Documents Gérés</h4>
                    <div class="mt-2">
                        <span class="badge bg-light text-danger fs-6">
                            <i class="fas fa-arrow-up me-1"></i>+{{ $data['growth'] ?? '19' }}% ce mois
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Types de documents -->
    <div class="row mb-4">
        @foreach($data['document_types'] ?? [
            ['name' => 'CV', 'count' => 1234, 'pending' => 45, 'color' => 'primary', 'icon' => 'user-tie'],
            ['name' => 'Lettres Motivation', 'count' => 987, 'pending' => 32, 'color' => 'success', 'icon' => 'envelope'],
            ['name' => 'Réalisations', 'count' => 1567, 'pending' => 78, 'color' => 'warning', 'icon' => 'images'],
            ['name' => 'Rapports', 'count' => 779, 'pending' => 23, 'color' => 'info', 'icon' => 'file-pdf']
        ] as $type)
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-{{ $type['color'] }} mb-3">
                        <i class="fas fa-{{ $type['icon'] }} fa-3x"></i>
                    </div>
                    <div class="h3 text-{{ $type['color'] }} mb-1">{{ $type['count'] }}</div>
                    <h6 class="text-muted mb-2">{{ $type['name'] }}</h6>
                    <div class="text-warning fw-semibold">{{ $type['pending'] }} en attente</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Graphiques et validation -->
    <div class="row mb-4">
        <!-- Évolution des documents -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line text-danger me-2"></i>Évolution des Documents
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="documentsChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Statuts de validation -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie text-danger me-2"></i>Statuts Validation
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="validationChart" height="300"></canvas>
                    
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Temps moyen validation</span>
                            <span class="fw-bold text-primary">{{ $data['avg_validation_time'] ?? '2.5' }}j</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Taux d'acceptation</span>
                            <span class="fw-bold text-success">{{ $data['acceptance_rate'] ?? '89' }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents en attente de validation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock text-warning me-2"></i>Documents en Attente de Validation
                    </h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-success">
                            <i class="fas fa-check-double me-1"></i>Valider Sélection
                        </button>
                        <button class="btn btn-danger">
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
                                    <th>Document</th>
                                    <th>Étudiant</th>
                                    <th>Type</th>
                                    <th>Formation</th>
                                    <th>Taille</th>
                                    <th>Date Upload</th>
                                    <th>Délai</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['pending_documents'] ?? [
                                    ['name' => 'CV_Marie_Dubois.pdf', 'student' => 'Marie Dubois', 'type' => 'CV', 'formation' => 'Design Graphique', 'size' => '2.3MB', 'date' => '2024-01-15', 'days_ago' => 1],
                                    ['name' => 'LM_Pierre_Martin.docx', 'student' => 'Pierre Martin', 'type' => 'Lettre Motivation', 'formation' => 'Community Management', 'size' => '1.8MB', 'date' => '2024-01-14', 'days_ago' => 2],
                                    ['name' => 'Portfolio_Sophie_L.pdf', 'student' => 'Sophie Laurent', 'type' => 'Réalisations', 'formation' => 'Intelligence Artificielle', 'size' => '15.6MB', 'date' => '2024-01-13', 'days_ago' => 3],
                                    ['name' => 'Rapport_Jean_R.pdf', 'student' => 'Jean Rousseau', 'type' => 'Rapport', 'formation' => 'Gestion Informatique', 'size' => '4.2MB', 'date' => '2024-01-12', 'days_ago' => 4],
                                    ['name' => 'CV_Emma_Chen.pdf', 'student' => 'Emma Chen', 'type' => 'CV', 'formation' => 'Design Graphique', 'size' => '1.9MB', 'date' => '2024-01-11', 'days_ago' => 5]
                                ] as $doc)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input doc-checkbox">
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $doc['name'] }}</div>
                                    </td>
                                    <td>{{ $doc['student'] }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $doc['type'] }}</span>
                                    </td>
                                    <td>{{ $doc['formation'] }}</td>
                                    <td>{{ $doc['size'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($doc['date'])->format('d/m/Y') }}</td>
                                    <td>
                                        @if($doc['days_ago'] <= 2)
                                            <span class="badge bg-success">{{ $doc['days_ago'] }}j</span>
                                        @elseif($doc['days_ago'] <= 5)
                                            <span class="badge bg-warning">{{ $doc['days_ago'] }}j</span>
                                        @else
                                            <span class="badge bg-danger">{{ $doc['days_ago'] }}j</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" title="Prévisualiser">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" title="Valider">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" title="Rejeter">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <button class="btn btn-outline-info" title="Télécharger">
                                                <i class="fas fa-download"></i>
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

    <!-- Métriques et insights -->
    <div class="row mb-4">
        <!-- Métriques de performance -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar text-danger me-2"></i>Métriques de Performance
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-success mb-1">{{ $data['validated_today'] ?? '45' }}</div>
                                <small class="text-muted">Validés Aujourd'hui</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-warning mb-1">{{ $data['pending_total'] ?? '178' }}</div>
                                <small class="text-muted">En Attente</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-info mb-1">{{ $data['storage_used'] ?? '2.8GB' }}</div>
                                <small class="text-muted">Stockage Utilisé</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-primary mb-1">{{ $data['avg_size'] ?? '3.2MB' }}</div>
                                <small class="text-muted">Taille Moyenne</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Graphique validation par jour -->
                    <canvas id="dailyValidationChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Insights et actions -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb text-warning me-2"></i>Insights & Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-success border-0 mb-3">
                        <h6 class="alert-heading"><i class="fas fa-thumbs-up me-2"></i>Points Positifs</h6>
                        <ul class="mb-0">
                            <li>89% de taux d'acceptation</li>
                            <li>Temps de validation réduit</li>
                            <li>+19% de documents ce mois</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-warning border-0 mb-4">
                        <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Points d'Attention</h6>
                        <ul class="mb-0">
                            <li>178 documents en attente</li>
                            <li>Stockage à 85% de capacité</li>
                            <li>Délai moyen : 2.5 jours</li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-danger">
                            <i class="fas fa-tachometer-alt me-2"></i>Validation Rapide
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-robot me-2"></i>Auto-Validation IA
                        </button>
                        <button class="btn btn-success">
                            <i class="fas fa-file-export me-2"></i>Export Rapport
                        </button>
                        <button class="btn btn-info">
                            <i class="fas fa-broom me-2"></i>Nettoyer Stockage
                        </button>
                        <button class="btn btn-warning">
                            <i class="fas fa-envelope-bulk me-2"></i>Notifications
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
    // Graphique évolution des documents
    const documentsCtx = document.getElementById('documentsChart').getContext('2d');
    new Chart(documentsCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [
                {
                    label: 'Documents uploadés',
                    data: [320, 365, 340, 410, 385, 450, 475, 460, 520, 545, 580, 565],
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Documents validés',
                    data: [285, 325, 305, 365, 345, 400, 425, 410, 465, 485, 520, 505],
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

    // Graphique statuts validation
    const validationCtx = document.getElementById('validationChart').getContext('2d');
    new Chart(validationCtx, {
        type: 'doughnut',
        data: {
            labels: ['Validés', 'En Attente', 'Rejetés', 'En Révision'],
            datasets: [{
                data: [3890, 178, 234, 265],
                backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#17a2b8'],
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

    // Graphique validation quotidienne
    const dailyValidationCtx = document.getElementById('dailyValidationChart').getContext('2d');
    new Chart(dailyValidationCtx, {
        type: 'bar',
        data: {
            labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            datasets: [{
                label: 'Validations',
                data: [45, 52, 48, 61, 55, 23, 15],
                backgroundColor: '#dc3545',
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
        const checkboxes = document.querySelectorAll('.doc-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
});
</script>
@endpush
@endsection
