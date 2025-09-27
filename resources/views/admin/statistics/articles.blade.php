@extends('layouts.admin')

@section('title', 'Détails - Articles & Contenu')

@section('content')
<div class="container-fluid px-4">
    <!-- Header avec breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Articles & Contenu</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-purple">
                <i class="fas fa-newspaper me-2"></i>Analyse Détaillée - Articles & Contenu
            </h1>
        </div>
    </div>

    <!-- KPI Principal -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-center py-4 text-white">
                    <div class="display-3 fw-bold mb-2">{{ $data['main_kpi'] ?? '1,234' }}</div>
                    <h4 class="mb-0">Articles Publiés</h4>
                    <div class="mt-2">
                        <span class="badge bg-light text-purple fs-6">
                            <i class="fas fa-arrow-up me-1"></i>+{{ $data['growth'] ?? '15' }}% ce mois
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par catégorie -->
    <div class="row mb-4">
        @foreach($data['article_categories'] ?? [
            ['name' => 'Tutoriels', 'count' => 456, 'views' => '125K', 'color' => 'primary', 'icon' => 'play-circle'],
            ['name' => 'Actualités', 'count' => 289, 'views' => '89K', 'color' => 'info', 'icon' => 'newspaper'],
            ['name' => 'Conseils', 'count' => 234, 'views' => '67K', 'color' => 'success', 'icon' => 'lightbulb'],
            ['name' => 'Ressources', 'count' => 255, 'views' => '78K', 'color' => 'warning', 'icon' => 'download']
        ] as $category)
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-{{ $category['color'] }} mb-3">
                        <i class="fas fa-{{ $category['icon'] }} fa-3x"></i>
                    </div>
                    <div class="h3 text-{{ $category['color'] }} mb-1">{{ $category['count'] }}</div>
                    <h6 class="text-muted mb-2">{{ $category['name'] }}</h6>
                    <div class="text-success fw-semibold">{{ $category['views'] }} vues</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Graphiques d'analyse -->
    <div class="row mb-4">
        <!-- Publications par mois -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line text-purple me-2"></i>Publications par Mois
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="publicationsChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Top auteurs -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-edit text-purple me-2"></i>Top Auteurs
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($data['top_authors'] ?? [
                            ['name' => 'Sarah Martin', 'articles' => 45, 'views' => '89K'],
                            ['name' => 'Pierre Dubois', 'articles' => 38, 'views' => '76K'],
                            ['name' => 'Marie Rousseau', 'articles' => 32, 'views' => '65K'],
                            ['name' => 'Jean Bernard', 'articles' => 28, 'views' => '52K'],
                            ['name' => 'Sophie Chen', 'articles' => 25, 'views' => '48K']
                        ] as $index => $author)
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div class="d-flex align-items-center">
                                <div class="badge bg-purple rounded-pill me-3">{{ $index + 1 }}</div>
                                <div>
                                    <div class="fw-semibold">{{ $author['name'] }}</div>
                                    <small class="text-muted">{{ $author['articles'] }} articles</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-success">{{ $author['views'] }}</div>
                                <small class="text-muted">vues</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Articles populaires et métriques -->
    <div class="row mb-4">
        <!-- Articles les plus populaires -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-fire text-danger me-2"></i>Articles les Plus Populaires
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Article</th>
                                    <th>Catégorie</th>
                                    <th>Auteur</th>
                                    <th>Vues</th>
                                    <th>Likes</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['popular_articles'] ?? [
                                    ['title' => 'Guide Complet Photoshop 2024', 'category' => 'Tutoriel', 'author' => 'Sarah Martin', 'views' => '15.2K', 'likes' => 456, 'date' => '2024-01-10'],
                                    ['title' => 'Tendances Design Graphique 2024', 'category' => 'Actualité', 'author' => 'Pierre Dubois', 'views' => '12.8K', 'likes' => 389, 'date' => '2024-01-08'],
                                    ['title' => '10 Astuces Instagram Marketing', 'category' => 'Conseil', 'author' => 'Marie Rousseau', 'views' => '11.5K', 'likes' => 342, 'date' => '2024-01-05'],
                                    ['title' => 'Pack Ressources Illustrator Gratuit', 'category' => 'Ressource', 'author' => 'Jean Bernard', 'views' => '9.8K', 'likes' => 298, 'date' => '2024-01-03'],
                                    ['title' => 'Formation IA : Par où Commencer ?', 'category' => 'Conseil', 'author' => 'Sophie Chen', 'views' => '8.9K', 'likes' => 267, 'date' => '2024-01-01']
                                ] as $article)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $article['title'] }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $article['category'] }}</span>
                                    </td>
                                    <td>{{ $article['author'] }}</td>
                                    <td>
                                        <span class="fw-semibold text-primary">{{ $article['views'] }}</span>
                                    </td>
                                    <td>
                                        <span class="text-danger">
                                            <i class="fas fa-heart me-1"></i>{{ $article['likes'] }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($article['date'])->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Métriques d'engagement -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie text-purple me-2"></i>Métriques d'Engagement
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-primary mb-1">{{ $data['avg_views'] ?? '2.8K' }}</div>
                                <small class="text-muted">Vues Moyennes</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-success mb-1">{{ $data['engagement_rate'] ?? '12.5' }}%</div>
                                <small class="text-muted">Taux Engagement</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-warning mb-1">{{ $data['avg_likes'] ?? '156' }}</div>
                                <small class="text-muted">Likes Moyens</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-info mb-1">{{ $data['share_rate'] ?? '8.2' }}%</div>
                                <small class="text-muted">Taux Partage</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Graphique engagement -->
                    <canvas id="engagementChart" height="200"></canvas>
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
                        <i class="fas fa-lightbulb text-warning me-2"></i>Insights Contenu
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-success border-0">
                        <h6 class="alert-heading"><i class="fas fa-thumbs-up me-2"></i>Performances Excellentes</h6>
                        <ul class="mb-0">
                            <li>Tutoriels : catégorie la plus consultée</li>
                            <li>Taux d'engagement en hausse (+12.5%)</li>
                            <li>Sarah Martin : auteure la plus productive</li>
                        </ul>
                    </div>
                    <div class="alert alert-info border-0">
                        <h6 class="alert-heading"><i class="fas fa-chart-line me-2"></i>Opportunités</h6>
                        <ul class="mb-0">
                            <li>Développer plus de contenu IA</li>
                            <li>Créer des séries d'articles</li>
                            <li>Optimiser SEO des articles</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt text-purple me-2"></i>Actions Rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-purple">
                            <i class="fas fa-plus me-2"></i>Nouvel Article
                        </button>
                        <button class="btn btn-success">
                            <i class="fas fa-calendar me-2"></i>Planifier Publication
                        </button>
                        <button class="btn btn-info">
                            <i class="fas fa-chart-bar me-2"></i>Analyse SEO
                        </button>
                        <button class="btn btn-warning">
                            <i class="fas fa-bullhorn me-2"></i>Promouvoir Article
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-file-export me-2"></i>Export Rapport
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.text-purple { color: #667eea !important; }
.bg-purple { background-color: #667eea !important; }
.btn-purple { 
    background-color: #667eea; 
    border-color: #667eea; 
    color: white; 
}
.btn-purple:hover { 
    background-color: #5a67d8; 
    border-color: #5a67d8; 
    color: white; 
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique publications par mois
    const publicationsCtx = document.getElementById('publicationsChart').getContext('2d');
    new Chart(publicationsCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [
                {
                    label: 'Tutoriels',
                    data: [35, 42, 38, 45, 52, 48, 55, 62, 58, 65, 72, 68],
                    backgroundColor: '#007bff'
                },
                {
                    label: 'Actualités',
                    data: [22, 28, 25, 32, 29, 35, 31, 38, 34, 41, 37, 44],
                    backgroundColor: '#17a2b8'
                },
                {
                    label: 'Conseils',
                    data: [18, 22, 19, 25, 21, 28, 24, 31, 27, 34, 30, 37],
                    backgroundColor: '#28a745'
                },
                {
                    label: 'Ressources',
                    data: [15, 19, 16, 22, 18, 25, 21, 28, 24, 31, 27, 34],
                    backgroundColor: '#ffc107'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true,
                    beginAtZero: true
                }
            }
        }
    });

    // Graphique engagement
    const engagementCtx = document.getElementById('engagementChart').getContext('2d');
    new Chart(engagementCtx, {
        type: 'doughnut',
        data: {
            labels: ['Vues', 'Likes', 'Partages', 'Commentaires'],
            datasets: [{
                data: [65, 20, 10, 5],
                backgroundColor: ['#667eea', '#764ba2', '#f093fb', '#f5576c'],
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
