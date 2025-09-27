@extends('layouts.admin')

@section('title')
Détails - Gestion Admins
@endsection

@section('content')
<div class="container-fluid px-4">
    <!-- Header avec breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Gestion Admins</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-secondary">
                <i class="fas fa-user-shield me-2"></i>Analyse Détaillée - Gestion Admins
            </h1>
        </div>
    </div>

    <!-- KPI Principal -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-secondary text-white">
                <div class="card-body text-center py-4">
                    <div class="display-3 fw-bold mb-2">{{ $data['main_kpi'] ?? '12' }}</div>
                    <h4 class="mb-0">Administrateurs Actifs</h4>
                    <div class="mt-2">
                        <span class="badge bg-light text-secondary fs-6">
                            <i class="fas fa-arrow-up me-1"></i>+{{ $data['growth'] ?? '2' }} ce mois
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par rôle -->
    <div class="row mb-4">
        @foreach($data['admin_roles'] ?? [
            ['name' => 'Super Admin', 'count' => 3, 'percentage' => 25, 'color' => 'danger', 'icon' => 'crown'],
            ['name' => 'Admin Principal', 'count' => 4, 'percentage' => 33, 'color' => 'warning', 'icon' => 'user-cog'],
            ['name' => 'Modérateur', 'count' => 5, 'percentage' => 42, 'color' => 'info', 'icon' => 'shield-alt'],
            ['name' => 'Support', 'count' => 8, 'percentage' => 67, 'color' => 'success', 'icon' => 'headset']
        ] as $role)
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-{{ $role['color'] }} mb-3">
                        <i class="fas fa-{{ $role['icon'] }} fa-3x"></i>
                    </div>
                    <div class="h2 text-{{ $role['color'] }} mb-1">{{ $role['count'] }}</div>
                    <h6 class="text-muted mb-2">{{ $role['name'] }}</h6>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-{{ $role['color'] }}" style="width: {{ $role['percentage'] }}%"></div>
                    </div>
                    <small class="text-muted">{{ $role['percentage'] }}% actifs</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Activité et permissions -->
    <div class="row mb-4">
        <!-- Activité des admins -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line text-secondary me-2"></i>Activité des Administrateurs
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="adminActivityChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Répartition des permissions -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie text-secondary me-2"></i>Permissions
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="permissionsChart" height="300"></canvas>
                    
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Dernière connexion</span>
                            <span class="fw-bold text-success">{{ $data['last_login'] ?? '2h' }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Sessions actives</span>
                            <span class="fw-bold text-primary">{{ $data['active_sessions'] ?? '8' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des administrateurs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users-cog text-secondary me-2"></i>Liste des Administrateurs
                    </h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-primary">
                            <i class="fas fa-user-plus me-1"></i>Nouvel Admin
                        </button>
                        <button class="btn btn-info">
                            <i class="fas fa-key me-1"></i>Gérer Permissions
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Admin</th>
                                    <th>Rôle</th>
                                    <th>Permissions</th>
                                    <th>Dernière Connexion</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                    <th>Activité</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['admin_list'] ?? [
                                    ['name' => 'Jean Administrateur', 'email' => 'jean.admin@evc.com', 'role' => 'Super Admin', 'permissions' => 'Toutes', 'last_login' => '2024-01-16 10:30', 'status' => 'En ligne', 'actions_today' => 45],
                                    ['name' => 'Marie Gestionnaire', 'email' => 'marie.gest@evc.com', 'role' => 'Admin Principal', 'permissions' => 'Étudiants, TP', 'last_login' => '2024-01-16 09:15', 'status' => 'En ligne', 'actions_today' => 32],
                                    ['name' => 'Pierre Modérateur', 'email' => 'pierre.mod@evc.com', 'role' => 'Modérateur', 'permissions' => 'Validation', 'last_login' => '2024-01-16 08:45', 'status' => 'Occupé', 'actions_today' => 28],
                                    ['name' => 'Sophie Support', 'email' => 'sophie.support@evc.com', 'role' => 'Support', 'permissions' => 'Messages', 'last_login' => '2024-01-15 17:20', 'status' => 'Hors ligne', 'actions_today' => 15],
                                    ['name' => 'Lucas Technique', 'email' => 'lucas.tech@evc.com', 'role' => 'Admin Principal', 'permissions' => 'Système', 'last_login' => '2024-01-16 07:30', 'status' => 'En ligne', 'actions_today' => 67]
                                ] as $admin)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $admin['name'] }}</div>
                                                <small class="text-muted">{{ $admin['email'] }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($admin['role'] === 'Super Admin')
                                            <span class="badge bg-danger">{{ $admin['role'] }}</span>
                                        @elseif($admin['role'] === 'Admin Principal')
                                            <span class="badge bg-warning">{{ $admin['role'] }}</span>
                                        @elseif($admin['role'] === 'Modérateur')
                                            <span class="badge bg-info">{{ $admin['role'] }}</span>
                                        @else
                                            <span class="badge bg-success">{{ $admin['role'] }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $admin['permissions'] }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ \Carbon\Carbon::parse($admin['last_login'])->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($admin['last_login'])->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($admin['status'] === 'En ligne')
                                            <span class="badge bg-success">
                                                <i class="fas fa-circle me-1" style="font-size: 8px;"></i>En ligne
                                            </span>
                                        @elseif($admin['status'] === 'Occupé')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-circle me-1" style="font-size: 8px;"></i>Occupé
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-circle me-1" style="font-size: 8px;"></i>Hors ligne
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" title="Voir profil">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-info" title="Permissions">
                                                <i class="fas fa-key"></i>
                                            </button>
                                            @if($admin['role'] !== 'Super Admin')
                                            <button class="btn btn-outline-danger" title="Suspendre">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <div class="fw-bold text-primary">{{ $admin['actions_today'] }}</div>
                                            <small class="text-muted">actions</small>
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

    <!-- Logs et sécurité -->
    <div class="row mb-4">
        <!-- Logs d'activité récents -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history text-secondary me-2"></i>Logs d'Activité Récents
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($data['recent_logs'] ?? [
                            ['admin' => 'Jean Administrateur', 'action' => 'Validation de 15 documents', 'time' => '10:30', 'type' => 'success'],
                            ['admin' => 'Marie Gestionnaire', 'action' => 'Ajout nouvel étudiant', 'time' => '09:45', 'type' => 'info'],
                            ['admin' => 'Pierre Modérateur', 'action' => 'Modération forum', 'time' => '09:15', 'type' => 'warning'],
                            ['admin' => 'Lucas Technique', 'action' => 'Mise à jour système', 'time' => '08:30', 'type' => 'primary'],
                            ['admin' => 'Sophie Support', 'action' => 'Réponse ticket support', 'time' => '17:20', 'type' => 'success']
                        ] as $log)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-{{ $log['type'] }}"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <div class="fw-semibold">{{ $log['admin'] }}</div>
                                        <div class="text-muted">{{ $log['action'] }}</div>
                                    </div>
                                    <small class="text-muted">{{ $log['time'] }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Sécurité et actions -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shield-alt text-warning me-2"></i>Sécurité & Actions
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Métriques de sécurité -->
                    <div class="row g-2 mb-4">
                        <div class="col-6">
                            <div class="text-center p-2 bg-light rounded">
                                <div class="h5 text-success mb-1">{{ $data['successful_logins'] ?? '156' }}</div>
                                <small class="text-muted">Connexions OK</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-2 bg-light rounded">
                                <div class="h5 text-danger mb-1">{{ $data['failed_logins'] ?? '3' }}</div>
                                <small class="text-muted">Échecs</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-2 bg-light rounded">
                                <div class="h5 text-info mb-1">{{ $data['active_sessions'] ?? '8' }}</div>
                                <small class="text-muted">Sessions</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-2 bg-light rounded">
                                <div class="h5 text-warning mb-1">{{ $data['permissions_changes'] ?? '12' }}</div>
                                <small class="text-muted">Modif. Perm.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Actions rapides -->
                    <div class="d-grid gap-2">
                        <button class="btn btn-secondary">
                            <i class="fas fa-user-plus me-2"></i>Nouvel Admin
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-key me-2"></i>Audit Permissions
                        </button>
                        <button class="btn btn-warning">
                            <i class="fas fa-history me-2"></i>Logs Complets
                        </button>
                        <button class="btn btn-info">
                            <i class="fas fa-shield-alt me-2"></i>Rapport Sécurité
                        </button>
                        <button class="btn btn-success">
                            <i class="fas fa-file-export me-2"></i>Export Données
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-sm {
    width: 40px;
    height: 40px;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #dee2e6;
}

.timeline::before {
    content: '';
    position: absolute;
    left: -30px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique activité des admins
    const adminActivityCtx = document.getElementById('adminActivityChart').getContext('2d');
    new Chart(adminActivityCtx, {
        type: 'line',
        data: {
            labels: ['00h', '04h', '08h', '12h', '16h', '20h'],
            datasets: [
                {
                    label: 'Connexions',
                    data: [2, 1, 8, 12, 15, 6],
                    borderColor: '#6c757d',
                    backgroundColor: 'rgba(108, 117, 125, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Actions',
                    data: [5, 3, 25, 45, 52, 18],
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

    // Graphique permissions
    const permissionsCtx = document.getElementById('permissionsChart').getContext('2d');
    new Chart(permissionsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Toutes', 'Étudiants', 'Validation', 'Support', 'Système'],
            datasets: [{
                data: [3, 4, 5, 8, 2],
                backgroundColor: ['#dc3545', '#ffc107', '#17a2b8', '#28a745', '#6f42c1'],
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
