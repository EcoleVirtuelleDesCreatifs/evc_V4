@extends('layouts.admin')

@section('title', 'Gestion des Étudiants - EVC')

@push('styles')
<style>
/* Styles modernes uniformes pour toutes les pages admin */
.page-header {
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
}

.page-title {
    font-size: 2rem;
    font-weight: 600;
    color: white;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    color: rgba(255,255,255,0.7);
    font-size: 1.1rem;
    margin-bottom: 0;
}

.quick-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-start;
    margin-top: 1.5rem;
    flex-wrap: wrap;
}

.btn-quick {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.btn-quick:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
    color: white;
    text-decoration: none;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    border-radius: 15px;
    padding: 1.5rem;
    color: white;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.stat-card.primary { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); }
.stat-card.success { background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); }
.stat-card.warning { background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); }
.stat-card.info { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); }

.stat-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    opacity: 0.9;
}

.stat-number {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 1rem;
    opacity: 0.9;
    margin-bottom: 1rem;
}

.btn-stat {
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.85rem;
    transition: all 0.3s ease;
}

.btn-stat:hover {
    background: rgba(255,255,255,0.3);
    color: white;
    text-decoration: none;
}

.content-section {
    background: rgba(255,255,255,0.05);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: white;
    margin-bottom: 1rem;
}

.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 12px;
    font-weight: bold;
}

.table-dark {
    --bs-table-bg: rgba(255, 255, 255, 0.05);
}

.progress {
    background-color: rgba(255, 255, 255, 0.2);
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }
    
    .page-header {
        padding: 1.5rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .quick-actions {
        justify-content: center;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .avatar-sm {
        width: 24px;
        height: 24px;
        font-size: 10px;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-users me-3"></i>Gestion des Étudiants
        </h1>
        <p class="page-subtitle">
            Gérez tous les étudiants inscrits à l'École Virtuelle des Créatifs
        </p>
        
        <div class="quick-actions">
            <a href="{{ route('admin.students.add') }}" class="btn-quick">
                <i class="fas fa-user-plus me-2"></i>Ajouter Étudiant
            </a>
            <a href="#" class="btn-quick" onclick="exportStudents()">
                <i class="fas fa-download me-2"></i>Exporter Liste
            </a>
            <a href="#" class="btn-quick" onclick="importStudents()">
                <i class="fas fa-upload me-2"></i>Importer Étudiants
            </a>
        </div>
    </div>

    <!-- Statistiques des étudiants -->
    <div class="stats-grid">
        <!-- Total Étudiants -->
        <div class="stat-card primary">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-number" data-counter="156">156</div>
            <div class="stat-label">Total Étudiants</div>
            <div class="stat-actions">
                <a href="{{ route('admin.statistics.total-students') }}" class="btn-stat">
                    <i class="fas fa-eye me-1"></i>Voir plus
                </a>
            </div>
        </div>

        <!-- Étudiants Actifs -->
        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-number" data-counter="142">142</div>
            <div class="stat-label">Étudiants Actifs</div>
            <div class="stat-actions">
                <a href="#" class="btn-stat" onclick="filterActive()">
                    <i class="fas fa-filter me-1"></i>Filtrer
                </a>
            </div>
        </div>

        <!-- Nouveaux ce Mois -->
        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="stat-number" data-counter="18">18</div>
            <div class="stat-label">Nouveaux ce Mois</div>
            <div class="stat-actions">
                <a href="#" class="btn-stat" onclick="filterNew()">
                    <i class="fas fa-calendar me-1"></i>Voir
                </a>
            </div>
        </div>

        <!-- En Attente -->
        <div class="stat-card info">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-number" data-counter="14">14</div>
            <div class="stat-label">En Attente</div>
            <div class="stat-actions">
                <a href="#" class="btn-stat" onclick="filterPending()">
                    <i class="fas fa-hourglass-half me-1"></i>Traiter
                </a>
            </div>
        </div>
    </div>

    <!-- Liste des étudiants -->
    <div class="content-section">
        <h3 class="section-title">
            <i class="fas fa-list me-2"></i>Liste des Étudiants
        </h3>
        
        <div class="table-responsive">
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>Email</th>
                        <th>Formation</th>
                        <th>Inscription</th>
                        <th>Progression</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                    AK
                                </div>
                                <div>
                                    <div class="fw-bold">Aya Kouassi</div>
                                </div>
                            </div>
                        </td>
                        <td>aya.kouassi@evc.com</td>
                        <td><span class="badge bg-info">Design Graphique</span></td>
                        <td><small class="text-muted">15 Jan 2024</small></td>
                        <td>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: 85%"></div>
                            </div>
                            <small class="text-muted">85%</small>
                        </td>
                        <td><span class="badge bg-success">Actif</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="#" class="btn btn-outline-primary btn-sm" onclick="viewStudent(1)">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-outline-warning btn-sm" onclick="editStudent(1)">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-outline-danger btn-sm" onclick="deleteStudent(1)">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-info rounded-circle d-flex align-items-center justify-content-center me-2">
                                    MT
                                </div>
                                <div>
                                    <div class="fw-bold">Mamadou Traoré</div>
                                </div>
                            </div>
                        </td>
                        <td>mamadou.traore@evc.com</td>
                        <td><span class="badge bg-warning">Community Management</span></td>
                        <td><small class="text-muted">03 Fév 2024</small></td>
                        <td>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-warning" style="width: 72%"></div>
                            </div>
                            <small class="text-muted">72%</small>
                        </td>
                        <td><span class="badge bg-success">Actif</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="#" class="btn btn-outline-primary btn-sm" onclick="viewStudent(2)">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-outline-warning btn-sm" onclick="editStudent(2)">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-outline-danger btn-sm" onclick="deleteStudent(2)">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-success rounded-circle d-flex align-items-center justify-content-center me-2">
                                    FD
                                </div>
                                <div>
                                    <div class="fw-bold">Fatou Diallo</div>
                                </div>
                            </div>
                        </td>
                        <td>fatou.diallo@evc.com</td>
                        <td><span class="badge bg-primary">Intelligence Artificielle</span></td>
                        <td><small class="text-muted">28 Jan 2024</small></td>
                        <td>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-primary" style="width: 91%"></div>
                            </div>
                            <small class="text-muted">91%</small>
                        </td>
                        <td><span class="badge bg-success">Actif</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="#" class="btn btn-outline-primary btn-sm" onclick="viewStudent(3)">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-outline-warning btn-sm" onclick="editStudent(3)">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-outline-danger btn-sm" onclick="deleteStudent(3)">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2">
                                    SB
                                </div>
                                <div>
                                    <div class="fw-bold">Seydou Bamba</div>
                                </div>
                            </div>
                        </td>
                        <td>seydou.bamba@evc.com</td>
                        <td><span class="badge bg-secondary">Gestion Informatique</span></td>
                        <td><small class="text-muted">20 Fév 2024</small></td>
                        <td>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-secondary" style="width: 68%"></div>
                            </div>
                            <small class="text-muted">68%</small>
                        </td>
                        <td><span class="badge bg-success">Actif</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="#" class="btn btn-outline-primary btn-sm" onclick="viewStudent(4)">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-outline-warning btn-sm" onclick="editStudent(4)">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-outline-danger btn-sm" onclick="deleteStudent(4)">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-info rounded-circle d-flex align-items-center justify-content-center me-2">
                                    AK
                                </div>
                                <div>
                                    <div class="fw-bold">Aminata Koné</div>
                                </div>
                            </div>
                        </td>
                        <td>aminata.kone@evc.com</td>
                        <td><span class="badge bg-info">Design Graphique</span></td>
                        <td><small class="text-muted">10 Jan 2024</small></td>
                        <td>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: 92%"></div>
                            </div>
                            <small class="text-muted">92%</small>
                        </td>
                        <td><span class="badge bg-success">Actif</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="#" class="btn btn-outline-primary btn-sm" onclick="viewStudent(5)">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-outline-warning btn-sm" onclick="editStudent(5)">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-outline-danger btn-sm" onclick="deleteStudent(5)">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Animations des compteurs
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('[data-counter]');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-counter'));
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.textContent = target;
                clearInterval(timer);
            } else {
                counter.textContent = Math.ceil(current);
            }
        }, 20);
    });
});

function exportStudents() {
    alert('Export des étudiants en cours de développement...');
}

function importStudents() {
    alert('Import des étudiants en cours de développement...');
}

function filterActive() {
    alert('Filtrage des étudiants actifs...');
}

function filterNew() {
    alert('Filtrage des nouveaux étudiants...');
}

function filterPending() {
    alert('Filtrage des étudiants en attente...');
}

function viewStudent(id) {
    window.location.href = `/evc/app/admin/students/profile/${id}`;
}

function editStudent(id) {
    alert('Édition de l\'étudiant ID: ' + id + ' en cours de développement...');
}

function deleteStudent(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?')) {
        alert('Suppression de l\'étudiant ID: ' + id + ' en cours de développement...');
    }
}
</script>
@endpush
            border: none;
        }

        .content-card .card-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .content-card .card-body {
            padding: 1.5rem;
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            text-align: center;
            border-left: 4px solid var(--secondary-color);
        }

        .stats-card h3 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            color: var(--dark-color);
        }

        .stats-card p {
            margin: 0;
            color: #6c757d;
            font-weight: 500;
        }

        /* Tables */
        .table {
            margin: 0;
        }

        .table th {
            border: none;
            font-weight: 600;
            color: var(--dark-color);
            background: var(--light-color);
        }

        .table td {
            border: none;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        /* Badges */
        .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
        }

        /* Buttons */
        .btn-admin {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 51, 102, 0.3);
            color: white;
        }

        /* Search and Filters */
        .search-filters {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(51, 153, 255, 0.15);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
                padding: 1rem;
            }

            .admin-header {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="admin-sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-shield-alt me-2"></i>EVC Admin</h3>
            <p>Espace de gestion</p>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('admin.etudiants') }}">
                    <i class="fas fa-users"></i>
                    Gestion Étudiants
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.documents.index') }}">
                    <i class="fas fa-file-check"></i>
                    Validation Documents
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    Déconnexion
                </a>
            </li>
        </ul>
        
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </nav>

    <!-- Main Content -->
    <main class="admin-main">
        <!-- Header -->
        <div class="admin-header">
            <div>
                <h1><i class="fas fa-users me-2"></i>Gestion des Étudiants</h1>
                <p class="text-muted mb-0">Administration des comptes étudiants</p>
            </div>
            <div>
                <button class="btn btn-admin">
                    <i class="fas fa-user-plus me-1"></i>
                    Nouvel Étudiant
                </button>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <h3>{{ number_format($userStats['total'] ?? 0) }}</h3>
                    <p>Total Étudiants</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3>{{ number_format($userStats['new_this_month'] ?? 0) }}</h3>
                    <p>Nouveaux ce Mois</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3>{{ number_format($userStats['active_today'] ?? 0) }}</h3>
                    <p>Actifs Aujourd'hui</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3>{{ count($userStats['by_formation'] ?? []) }}</h3>
                    <p>Formations</p>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="search-filters">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Rechercher</label>
                    <input type="text" class="form-control" placeholder="Nom, email..." id="searchInput">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Formation</label>
                    <select class="form-control" id="formationFilter">
                        <option value="">Toutes les formations</option>
                        <option value="design-graphique">Design Graphique</option>
                        <option value="community-management">Community Management</option>
                        <option value="intelligence-artificielle">Intelligence Artificielle</option>
                        <option value="gestion-informatique">Gestion Informatique</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Période d'inscription</label>
                    <select class="form-control" id="periodFilter">
                        <option value="">Toutes les périodes</option>
                        <option value="today">Aujourd'hui</option>
                        <option value="week">Cette semaine</option>
                        <option value="month">Ce mois</option>
                        <option value="year">Cette année</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button class="btn btn-admin w-100" onclick="applyFilters()">
                        <i class="fas fa-search"></i>
                        Filtrer
                    </button>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="content-card">
            <div class="card-header">
                <h5><i class="fas fa-list me-2"></i>Liste des Étudiants</h5>
            </div>
            <div class="card-body">
                @if($users && $users->count() > 0)
                    <div class="table-responsive">
                        <table class="table" id="usersTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom Complet</th>
                                    <th>Email</th>
                                    <th>Formation</th>
                                    <th>Inscription</th>
                                    <th>Dernière Activité</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td><span class="badge bg-secondary">#{{ $user->id }}</span></td>
                                    <td>
                                        <div class="fw-semibold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->formation_souhaitee)
                                            <span class="badge bg-primary">{{ $user->formation_souhaitee }}</span>
                                        @else
                                            <span class="badge bg-secondary">Non spécifiée</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($user->updated_at)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" title="Voir le profil">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" title="Suspendre">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Affichage de {{ $users->firstItem() }} à {{ $users->lastItem() }} sur {{ $users->total() }} étudiants
                        </div>
                        <div>
                            {{ $users->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Aucun étudiant trouvé</h4>
                        <p class="text-muted">Il n'y a pas encore d'étudiants inscrits sur la plateforme.</p>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#usersTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Filter functionality
        function applyFilters() {
            const formation = document.getElementById('formationFilter').value;
            const period = document.getElementById('periodFilter').value;
            const search = document.getElementById('searchInput').value.toLowerCase();
            
            const rows = document.querySelectorAll('#usersTable tbody tr');
            
            rows.forEach(row => {
                let show = true;
                
                // Formation filter
                if (formation) {
                    const formationCell = row.cells[3].textContent.toLowerCase();
                    if (!formationCell.includes(formation)) {
                        show = false;
                    }
                }
                
                // Search filter
                if (search) {
                    const text = row.textContent.toLowerCase();
                    if (!text.includes(search)) {
                        show = false;
                    }
                }
                
                row.style.display = show ? '' : 'none';
            });
        }

        // Mobile sidebar toggle
        function toggleSidebar() {
            document.querySelector('.admin-sidebar').classList.toggle('show');
        }
    </script>
</body>
</html>
