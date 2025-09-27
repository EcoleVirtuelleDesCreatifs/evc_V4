@extends('layouts.admin')

@section('title', 'Dashboard Admin - EVC')

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
.stat-card.danger { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); }
.stat-card.info { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); }
.stat-card.secondary { background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%); }

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
}

/* Styles spécifiques pour les statistiques */
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 12px;
    font-weight: bold;
}

.card {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

.table-dark {
    --bs-table-bg: rgba(255, 255, 255, 0.05);
}

.table-primary {
    --bs-table-bg: rgba(13, 110, 253, 0.2);
}

.progress {
    background-color: rgba(255, 255, 255, 0.2);
}

.btn-group .btn {
    margin-right: 2px;
}

/* Animation pour les cartes */
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

/* Quick Actions */
.quick-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
    flex-wrap: wrap;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }

    .avatar-sm {
        width: 24px;
        height: 24px;
        font-size: 10px;
    }

    .quick-actions {
        justify-content: center;
        margin-top: 1rem;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="page-header mb-4">
        <h1 class="page-title text-white">
            <i class="fas fa-tachometer-alt text-primary me-2"></i>
            Dashboard Administrateur
        </h1>
        <div class="quick-actions">
            <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-user-plus me-1"></i>Ajouter
            </a>
            <button class="btn btn-success btn-sm" onclick="exportData()">
                <i class="fas fa-download me-1"></i>Exporter
            </button>
            <button class="btn btn-info btn-sm" onclick="generateReport()">
                <i class="fas fa-chart-line me-1"></i>Rapport
            </button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-primary bg-gradient text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x mb-3 opacity-75"></i>
                    <h2 class="fw-bold" data-counter="{{ $stats['total_students'] ?? '1250' }}">{{ $stats['total_students'] ?? '1,250' }}</h2>
                    <p class="mb-0">Étudiants Actifs</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-success bg-gradient text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-graduation-cap fa-3x mb-3 opacity-75"></i>
                    <h2 class="fw-bold" data-counter="{{ $stats['total_formations'] ?? '8' }}">{{ $stats['total_formations'] ?? '8' }}</h2>
                    <p class="mb-0">Formations Disponibles</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-info bg-gradient text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-project-diagram fa-3x mb-3 opacity-75"></i>
                    <h2 class="fw-bold" data-counter="{{ $stats['total_projects'] ?? '456' }}">{{ $stats['total_projects'] ?? '456' }}</h2>
                    <p class="mb-0">Projets Étudiants</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-warning bg-gradient text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-tasks fa-3x mb-3 opacity-75"></i>
                    <h2 class="fw-bold" data-counter="{{ $stats['total_tp'] ?? '2340' }}">{{ $stats['total_tp'] ?? '2,340' }}</h2>
                    <p class="mb-0">Travaux Pratiques</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques Secondaires -->
    <div class="card mb-4" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);">
        <div class="card-header" style="background: rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.1);">
            <h5 class="text-white mb-0"><i class="fas fa-chart-bar me-2"></i>Statistiques Détaillées</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card text-white h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-newspaper fa-3x mb-3 opacity-75"></i>
                            <h3 class="fw-bold mb-2" data-counter="{{ $stats['total_articles'] ?? '89' }}">{{ $stats['total_articles'] ?? '89' }}</h3>
                            <h6 class="mb-3">Articles & Contenus</h6>
                            <a href="{{ route('admin.statistics.detail', 'total-articles') }}" class="btn btn-sm btn-light">
                                <i class="fas fa-eye me-1"></i>Voir plus
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card text-white h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: none;">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-book fa-3x mb-3 opacity-75"></i>
                            <h3 class="fw-bold mb-2" data-counter="{{ $stats['total_resources'] ?? '234' }}">{{ $stats['total_resources'] ?? '234' }}</h3>
                            <h6 class="mb-3">Ressources Pédagogiques</h6>
                            <a href="{{ route('admin.statistics.detail', 'total-resources') }}" class="btn btn-sm btn-light">
                                <i class="fas fa-eye me-1"></i>Voir plus
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card text-white h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none;">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-certificate fa-3x mb-3 opacity-75"></i>
                            <h3 class="fw-bold mb-2" data-counter="{{ $stats['eligible_certificates'] ?? '67' }}">{{ $stats['eligible_certificates'] ?? '67' }}</h3>
                            <h6 class="mb-3">Certificats Éligibles</h6>
                            <a href="{{ route('admin.statistics.detail', 'total-certificates') }}" class="btn btn-sm btn-light">
                                <i class="fas fa-eye me-1"></i>Voir plus
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card text-white h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border: none;">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-file-alt fa-3x mb-3 opacity-75"></i>
                            <h3 class="fw-bold mb-2" data-counter="{{ $stats['pending_documents'] ?? '45' }}">{{ $stats['pending_documents'] ?? '45' }}</h3>
                            <h6 class="mb-3">Documents en Attente</h6>
                            <a href="{{ route('admin.documents.index') }}" class="btn btn-sm btn-light">
                                <i class="fas fa-eye me-1"></i>Voir plus
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Deuxième ligne - Nouvelles statistiques -->
                <div class="row">
                    <div class="col-xl-2 col-md-4 mb-3">
                        <div class="card text-white h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                            <div class="card-body text-center p-3">
                                <i class="fas fa-users fa-2x mb-2 opacity-75"></i>
                                <h4 class="fw-bold mb-1" data-counter="{{ $stats['total_students_evc'] ?? '1247' }}">{{ $stats['total_students_evc'] ?? '1247' }}</h4>
                                <h6 class="mb-2 small">Total Étudiants EVC</h6>
                                <a href="{{ route('admin.students.index') }}" class="btn btn-xs btn-light">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 mb-3">
                        <div class="card text-white h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: none;">
                            <div class="card-body text-center p-3">
                                <i class="fas fa-euro-sign fa-2x mb-2 opacity-75"></i>
                                <h4 class="fw-bold mb-1" data-counter="{{ $stats['total_amount'] ?? '89500' }}">{{ number_format($stats['total_amount'] ?? 89500, 0, ',', ' ') }}€</h4>
                                <h6 class="mb-2 small">Montant Total</h6>
                                <a href="{{ route('admin.statistics.detail', 'total-amount') }}" class="btn btn-xs btn-light">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 mb-3">
                        <div class="card text-white h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none;">
                            <div class="card-body text-center p-3">
                                <i class="fas fa-file-chart-line fa-2x mb-2 opacity-75"></i>
                                <h4 class="fw-bold mb-1" data-counter="{{ $stats['available_reports'] ?? '156' }}">{{ $stats['available_reports'] ?? '156' }}</h4>
                                <h6 class="mb-2 small">Rapports Disponibles</h6>
                                <a href="{{ route('admin.reports.index') }}" class="btn btn-xs btn-light">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 mb-3">
                        <div class="card text-white h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border: none;">
                            <div class="card-body text-center p-3">
                                <i class="fas fa-user-clock fa-2x mb-2 opacity-75"></i>
                                <h4 class="fw-bold mb-1" data-counter="{{ $stats['total_applications'] ?? '342' }}">{{ $stats['total_applications'] ?? '342' }}</h4>
                                <h6 class="mb-2 small">Candidatures Totales</h6>
                                <a href="{{ route('admin.statistics.detail', 'total-applications') }}" class="btn btn-xs btn-light">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 mb-3">
                        <div class="card text-white h-100" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); border: none;">
                            <div class="card-body text-center p-3">
                                <i class="fas fa-user-shield fa-2x mb-2 opacity-75"></i>
                                <h4 class="fw-bold mb-1" data-counter="{{ $stats['total_admins'] ?? '8' }}">{{ $stats['total_admins'] ?? '8' }}</h4>
                                <h6 class="mb-2 small">Total Admins</h6>
                                <a href="{{ route('admin.admins.index') }}" class="btn btn-xs btn-light">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Activités Récentes -->
    <div class="card mb-4" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);">
        <div class="card-header" style="background: rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.1);">
            <h5 class="text-white mb-0"><i class="fas fa-clock me-2"></i>Activités Récentes</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>Heure</th>
                            <th>Utilisateur</th>
                            <th>Action</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><small class="text-muted">{{ date('H:i') }}</small></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2 text-white fw-bold">
                                        AK
                                    </div>
                                    <span class="text-white">Aya Kouassi</span>
                                </div>
                            </td>
                            <td class="text-white">Soumission TP Design</td>
                            <td><span class="badge bg-success">Validé</span></td>
                        </tr>
                        <tr>
                            <td><small class="text-muted">{{ date('H:i', strtotime('-15 minutes')) }}</small></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-info rounded-circle d-flex align-items-center justify-content-center me-2 text-white fw-bold">
                                        MT
                                    </div>
                                    <span class="text-white">Mamadou Traoré</span>
                                </div>
                            </td>
                            <td class="text-white">Upload CV</td>
                            <td><span class="badge bg-warning">En attente</span></td>
                        </tr>
                        <tr>
                            <td><small class="text-muted">{{ date('H:i', strtotime('-30 minutes')) }}</small></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-success rounded-circle d-flex align-items-center justify-content-center me-2 text-white fw-bold">
                                        FD
                                    </div>
                                    <span class="text-white">Fatou Diallo</span>
                                </div>
                            </td>
                            <td class="text-white">Projet IA terminé</td>
                            <td><span class="badge bg-primary">Évaluation</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Animations des compteurs
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initialisation des animations de compteurs...');

    const counters = document.querySelectorAll('[data-counter]');
    console.log('Compteurs trouvés:', counters.length);

    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-counter'));
        console.log('Animation compteur vers:', target);

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

    console.log('Animations des compteurs terminées');
});

function addStudent() {
    console.log('Redirection vers ajout étudiant...');
    window.location.href = "{{ route('admin.students.add') }}";
}

function exportData() {
    console.log('Export des données...');
    alert('Export en cours de développement...');
}

function generateReport() {
    console.log('Génération de rapport...');
    alert('Génération de rapport en cours de développement...');
}
</script>
@endpush
            <div class="stat-actions">
                <a href="{{ route('admin.statistics.detail', 'total-documents') }}" class="btn-stat">
                    <i class="fas fa-eye me-1"></i>Voir plus
                </a>
            </div>
        </div>
    </div>


</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique d'activité
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            datasets: [{
                label: 'Connexions',
                data: [65, 78, 85, 92, 88, 45, 32],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: 'white'
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.7)'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                y: {
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.7)'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                }
            }
        }
    });

    // Graphique formations
    const formationCtx = document.getElementById('formationChart').getContext('2d');
    new Chart(formationCtx, {
        type: 'doughnut',
        data: {
            labels: ['Design Graphique', 'Community Management', 'Intelligence Artificielle', 'Gestion Informatique'],
            datasets: [{
                data: [45, 25, 20, 10],
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: 'white',
                        padding: 20
                    }
                }
            }
        }
    });
});
</script>

<!-- Modale d'ajout d'étudiant -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Ajouter un Étudiant
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data" id="studentForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="prenom" name="prenom" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="telephone" name="telephone" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ville" class="form-label">Ville</label>
                                <input type="text" class="form-control" id="ville" name="ville">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="pays" class="form-label">Pays</label>
                                <input type="text" class="form-control" id="pays" name="pays" value="Côte d'Ivoire">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Formations <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input formation-checkbox" type="checkbox" name="formation_souhaitee[]" value="design_graphique" id="design_graphique">
                                    <label class="form-check-label" for="design_graphique">
                                        Design Graphique
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input formation-checkbox" type="checkbox" name="formation_souhaitee[]" value="community_management" id="community_management">
                                    <label class="form-check-label" for="community_management">
                                        Community Management
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input formation-checkbox" type="checkbox" name="formation_souhaitee[]" value="intelligence_artificielle" id="intelligence_artificielle">
                                    <label class="form-check-label" for="intelligence_artificielle">
                                        Intelligence Artificielle
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input formation-checkbox" type="checkbox" name="formation_souhaitee[]" value="gestion_informatique" id="gestion_informatique">
                                    <label class="form-check-label" for="gestion_informatique">
                                        Gestion Informatique
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="send_welcome_email" value="1" id="send_welcome_email" checked>
                        <label class="form-check-label" for="send_welcome_email">
                            Envoyer un email de bienvenue
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Inscrire l'Étudiant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Validation du formulaire dans la modale
document.getElementById('studentForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value;
    const selectedFormations = document.querySelectorAll('.formation-checkbox:checked');

    // Validation des formations
    if (selectedFormations.length === 0) {
        e.preventDefault();
        alert('Veuillez sélectionner au moins une formation.');
        return false;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Veuillez saisir une adresse email valide.');
        document.getElementById('email').focus();
        return false;
    }

    // Confirmation simple
    if (!confirm('Êtes-vous sûr de vouloir inscrire cet étudiant ?')) {
        e.preventDefault();
        return false;
    }

    return true;
});
</script>
@endsection
