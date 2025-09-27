@extends('layouts.admin')

@section('title', 'Détails - Ressources Pédagogiques')

@section('content')
<div class="container-fluid px-4">
    <!-- Header avec breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Ressources Pédagogiques</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-success">
                <i class="fas fa-book-open me-2"></i>Analyse Détaillée - Ressources Pédagogiques
            </h1>
        </div>
    </div>

    <!-- KPI Principal -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-success text-white">
                <div class="card-body text-center py-4">
                    <div class="display-3 fw-bold mb-2">{{ $data['main_kpi'] ?? '2,856' }}</div>
                    <h4 class="mb-0">Ressources Disponibles</h4>
                    <div class="mt-2">
                        <span class="badge bg-light text-success fs-6">
                            <i class="fas fa-arrow-up me-1"></i>+{{ $data['growth'] ?? '22' }}% ce mois
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Types de ressources -->
    <div class="row mb-4">
        @foreach($data['resource_types'] ?? [
            ['name' => 'Vidéos', 'count' => 1245, 'size' => '2.8TB', 'color' => 'danger', 'icon' => 'play'],
            ['name' => 'Documents PDF', 'count' => 856, 'size' => '12.5GB', 'color' => 'primary', 'icon' => 'file-pdf'],
            ['name' => 'Templates', 'count' => 432, 'size' => '5.2GB', 'color' => 'warning', 'icon' => 'layer-group'],
            ['name' => 'Assets', 'count' => 323, 'size' => '8.9GB', 'color' => 'info', 'icon' => 'images']
        ] as $type)
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-{{ $type['color'] }} mb-3">
                        <i class="fas fa-{{ $type['icon'] }} fa-3x"></i>
                    </div>
                    <div class="h3 text-{{ $type['color'] }} mb-1">{{ $type['count'] }}</div>
                    <h6 class="text-muted mb-2">{{ $type['name'] }}</h6>
                    <div class="text-success fw-semibold">{{ $type['size'] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Graphiques et statistiques -->
    <div class="row mb-4">
        <!-- Utilisation des ressources -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-area text-success me-2"></i>Utilisation des Ressources
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="usageChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Top ressources -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star text-warning me-2"></i>Top Ressources
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($data['top_resources'] ?? [
                            ['name' => 'Pack Brushes Photoshop Pro', 'downloads' => 2456, 'type' => 'Assets'],
                            ['name' => 'Formation Complète Illustrator', 'downloads' => 1987, 'type' => 'Vidéo'],
                            ['name' => 'Templates Instagram Stories', 'downloads' => 1654, 'type' => 'Template'],
                            ['name' => 'Guide Marketing Digital PDF', 'downloads' => 1432, 'type' => 'Document'],
                            ['name' => 'Icônes UI/UX Premium', 'downloads' => 1289, 'type' => 'Assets']
                        ] as $index => $resource)
                        <div class="list-group-item d-flex justify-content-between align-items-start border-0 px-0">
                            <div class="d-flex align-items-start">
                                <div class="badge bg-success rounded-pill me-3 mt-1">{{ $index + 1 }}</div>
                                <div>
                                    <div class="fw-semibold">{{ $resource['name'] }}</div>
                                    <small class="text-muted">{{ $resource['type'] }}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-primary">{{ $resource['downloads'] }}</div>
                                <small class="text-muted">téléchargements</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des ressources récentes -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock text-success me-2"></i>Ressources Récemment Ajoutées
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Ressource</th>
                                    <th>Type</th>
                                    <th>Formation</th>
                                    <th>Taille</th>
                                    <th>Téléchargements</th>
                                    <th>Date Ajout</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['recent_resources'] ?? [
                                    ['name' => 'Masterclass After Effects 2024', 'type' => 'Vidéo', 'formation' => 'Design Graphique', 'size' => '2.5GB', 'downloads' => 156, 'date' => '2024-01-15'],
                                    ['name' => 'Templates LinkedIn Posts', 'type' => 'Template', 'formation' => 'Community Management', 'size' => '45MB', 'downloads' => 89, 'date' => '2024-01-14'],
                                    ['name' => 'Dataset Machine Learning', 'type' => 'Document', 'formation' => 'Intelligence Artificielle', 'size' => '156MB', 'downloads' => 67, 'date' => '2024-01-13'],
                                    ['name' => 'Code Source API REST', 'type' => 'Code', 'formation' => 'Gestion Informatique', 'size' => '12MB', 'downloads' => 43, 'date' => '2024-01-12'],
                                    ['name' => 'Pack Fonts Premium 2024', 'type' => 'Assets', 'formation' => 'Design Graphique', 'size' => '234MB', 'downloads' => 198, 'date' => '2024-01-11']
                                ] as $resource)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $resource['name'] }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $resource['type'] }}</span>
                                    </td>
                                    <td>{{ $resource['formation'] }}</td>
                                    <td>{{ $resource['size'] }}</td>
                                    <td>
                                        <span class="fw-semibold text-primary">{{ $resource['downloads'] }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($resource['date'])->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" title="Télécharger">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" title="Modifier">
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

    <!-- Métriques et insights -->
    <div class="row mb-4">
        <!-- Métriques de stockage -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-hdd text-success me-2"></i>Métriques de Stockage
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-primary mb-1">{{ $data['total_size'] ?? '3.2TB' }}</div>
                                <small class="text-muted">Espace Total</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-success mb-1">{{ $data['used_space'] ?? '2.8TB' }}</div>
                                <small class="text-muted">Espace Utilisé</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-warning mb-1">{{ $data['avg_download'] ?? '156' }}</div>
                                <small class="text-muted">Téléch./Jour</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-info mb-1">{{ $data['bandwidth'] ?? '45GB' }}</div>
                                <small class="text-muted">Bande Passante</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Graphique utilisation stockage -->
                    <canvas id="storageChart" height="200"></canvas>
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
                        <h6 class="alert-heading"><i class="fas fa-thumbs-up me-2"></i>Performances</h6>
                        <ul class="mb-0">
                            <li>+22% de nouvelles ressources</li>
                            <li>Vidéos : type le plus populaire</li>
                            <li>Taux de satisfaction : 94%</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-info border-0 mb-4">
                        <h6 class="alert-heading"><i class="fas fa-chart-line me-2"></i>Recommandations</h6>
                        <ul class="mb-0">
                            <li>Optimiser la compression vidéo</li>
                            <li>Créer plus de templates</li>
                            <li>Améliorer la recherche</li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Ajouter Ressource
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>Upload en Lot
                        </button>
                        <button class="btn btn-info">
                            <i class="fas fa-chart-bar me-2"></i>Analyse Détaillée
                        </button>
                        <button class="btn btn-warning">
                            <i class="fas fa-broom me-2"></i>Nettoyer Stockage
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
    // Graphique utilisation des ressources
    const usageCtx = document.getElementById('usageChart').getContext('2d');
    new Chart(usageCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [
                {
                    label: 'Téléchargements',
                    data: [1200, 1350, 1280, 1450, 1520, 1680, 1750, 1820, 1900, 2050, 2180, 2250],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Nouvelles ressources',
                    data: [45, 52, 48, 61, 55, 67, 73, 69, 78, 85, 92, 89],
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
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

    // Graphique utilisation stockage
    const storageCtx = document.getElementById('storageChart').getContext('2d');
    new Chart(storageCtx, {
        type: 'doughnut',
        data: {
            labels: ['Vidéos', 'Documents', 'Templates', 'Assets', 'Libre'],
            datasets: [{
                data: [60, 15, 10, 8, 7],
                backgroundColor: ['#dc3545', '#007bff', '#ffc107', '#17a2b8', '#e9ecef'],
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
