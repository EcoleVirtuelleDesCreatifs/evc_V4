@extends('layouts.admin')

@section('title', 'Rapports et Analytics')

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

.report-card {
    background: rgba(255,255,255,0.08);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border: 1px solid rgba(255,255,255,0.1);
    transition: all 0.3s ease;
}

.report-card:hover {
    background: rgba(255,255,255,0.12);
    transform: translateY(-2px);
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
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-chart-bar me-3"></i>Rapports et Analytics
        </h1>
        <p class="page-subtitle">
            Analysez les performances et générez des rapports détaillés
        </p>
        
        <div class="quick-actions">
            <button class="btn-quick" onclick="generateCustomReport()">
                <i class="fas fa-plus me-2"></i>Nouveau Rapport
            </button>
            <a href="{{ route('admin.rapports.exports') }}" class="btn-quick">
                <i class="fas fa-download me-2"></i>Exports
            </a>
            <button class="btn-quick" onclick="scheduleReport()">
                <i class="fas fa-clock me-2"></i>Programmer
            </button>
        </div>
    </div>

    <!-- Statistiques des rapports -->
    <div class="stats-grid">
        <!-- Rapports Générés -->
        <div class="stat-card primary">
            <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-number" data-counter="47">47</div>
            <div class="stat-label">Rapports Générés</div>
            <div class="stat-change positive">
                <span class="stat-change-value">+12</span>
                <span class="stat-change-label">ce mois</span>
            </div>
            <div class="stat-actions">
                <a href="#" class="btn-stat" onclick="viewAllReports()">
                    <i class="fas fa-eye me-1"></i>Voir Tous
                </a>
            </div>
        </div>

        <!-- Exports ce Mois -->
        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-download"></i>
            </div>
            <div class="stat-number" data-counter="23">23</div>
            <div class="stat-label">Exports ce Mois</div>
            <div class="stat-change positive">
                <span class="stat-change-value">+35%</span>
                <span class="stat-change-label">vs mois dernier</span>
            </div>
            <div class="stat-actions">
                <a href="#" class="btn-stat" onclick="viewExports()">
                    <i class="fas fa-list me-1"></i>Historique
                </a>
            </div>
        </div>

        <!-- Analytics Actives -->
        <div class="stat-card info">
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-number" data-counter="12">12</div>
            <div class="stat-label">Analytics Actives</div>
            <div class="stat-change positive">
                <span class="stat-change-value">100%</span>
                <span class="stat-change-label">opérationnelles</span>
            </div>
            <div class="stat-actions">
                <a href="#" class="btn-stat" onclick="viewAnalytics()">
                    <i class="fas fa-chart-pie me-1"></i>Dashboard
                </a>
            </div>
        </div>

        <!-- Rapports Programmés -->
        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-number" data-counter="8">8</div>
            <div class="stat-label">Rapports Programmés</div>
            <div class="stat-change neutral">
                <span class="stat-change-value">3</span>
                <span class="stat-change-label">en attente</span>
            </div>
            <div class="stat-actions">
                <a href="#" class="btn-stat" onclick="manageScheduled()">
                    <i class="fas fa-cog me-1"></i>Gérer
                </a>
            </div>
        </div>
    </div>

    <!-- Types de Rapports Disponibles -->
    <div class="content-section">
        <h3 class="section-title">
            <i class="fas fa-file-alt me-2"></i>Types de Rapports Disponibles
        </h3>
        
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="report-card text-center">
                    <div class="mb-3">
                        <i class="fas fa-users fa-3x text-primary"></i>
                    </div>
                    <h5 class="text-white mb-2">Rapport Étudiants</h5>
                    <p class="text-muted mb-3">Statistiques complètes sur les étudiants inscrits et leur progression</p>
                    <button class="btn btn-primary btn-sm" onclick="generateReport('students')">
                        <i class="fas fa-play me-1"></i>Générer
                    </button>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="report-card text-center">
                    <div class="mb-3">
                        <i class="fas fa-graduation-cap fa-3x text-success"></i>
                    </div>
                    <h5 class="text-white mb-2">Rapport Formations</h5>
                    <p class="text-muted mb-3">Performance des formations, modules et taux de réussite</p>
                    <button class="btn btn-success btn-sm" onclick="generateReport('formations')">
                        <i class="fas fa-play me-1"></i>Générer
                    </button>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="report-card text-center">
                    <div class="mb-3">
                        <i class="fas fa-chart-pie fa-3x text-info"></i>
                    </div>
                    <h5 class="text-white mb-2">Rapport Financier</h5>
                    <p class="text-muted mb-3">Revenus, paiements et analytics financières détaillées</p>
                    <button class="btn btn-info btn-sm" onclick="generateReport('financial')">
                        <i class="fas fa-play me-1"></i>Générer
                    </button>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="report-card text-center">
                    <div class="mb-3">
                        <i class="fas fa-tasks fa-3x text-warning"></i>
                    </div>
                    <h5 class="text-white mb-2">Rapport Activités</h5>
                    <p class="text-muted mb-3">TP, projets et activités des étudiants par formation</p>
                    <button class="btn btn-warning btn-sm" onclick="generateReport('activities')">
                        <i class="fas fa-play me-1"></i>Générer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Rapports Récents -->
    <div class="content-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="section-title mb-0">
                <i class="fas fa-history me-2"></i>Rapports Récents
            </h3>
            <button class="btn btn-outline-light btn-sm" onclick="refreshReports()">
                <i class="fas fa-sync-alt me-1"></i>Actualiser
            </button>
        </div>
        
        <div class="table-responsive">
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th>Nom du Rapport</th>
                        <th>Type</th>
                        <th>Généré le</th>
                        <th>Taille</th>
                        <th>Statut</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-white">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                <div>
                                    <div class="fw-bold">Rapport Étudiants Janvier 2024</div>
                                    <small class="text-muted">Statistiques mensuelles complètes</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-primary">Étudiants</span></td>
                        <td class="text-white">13/01/2024 14:30</td>
                        <td class="text-white">2.4 MB</td>
                        <td><span class="badge bg-success">Terminé</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary btn-sm" onclick="viewReport(1)" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-success btn-sm" onclick="downloadReport(1)" title="Télécharger">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="deleteReport(1)" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="text-white">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-excel text-success me-2"></i>
                                <div>
                                    <div class="fw-bold">Rapport Financier Q4 2023</div>
                                    <small class="text-muted">Bilan financier trimestriel</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-info">Financier</span></td>
                        <td class="text-white">10/01/2024 09:15</td>
                        <td class="text-white">1.8 MB</td>
                        <td><span class="badge bg-success">Terminé</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary btn-sm" onclick="viewReport(2)" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-success btn-sm" onclick="downloadReport(2)" title="Télécharger">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="deleteReport(2)" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="text-white">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-alt text-warning me-2"></i>
                                <div>
                                    <div class="fw-bold">Rapport Formations Design Graphique</div>
                                    <small class="text-muted">Performance par module</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-success">Formations</span></td>
                        <td class="text-white">08/01/2024 16:45</td>
                        <td class="text-white">3.1 MB</td>
                        <td><span class="badge bg-warning">En cours</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-secondary btn-sm" disabled title="En cours">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="deleteReport(3)" title="Annuler">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Affichage de 1 à 3 sur 47 rapports
            </div>
            <nav>
                <ul class="pagination pagination-sm">
                    <li class="page-item disabled">
                        <span class="page-link">Précédent</span>
                    </li>
                    <li class="page-item active">
                        <span class="page-link">1</span>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">2</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">3</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">Suivant</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Types de Rapports Disponibles</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-left-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                                    <h5>Rapports Étudiants</h5>
                                    <p class="text-muted">Inscriptions, progression, performances</p>
                                    <button class="btn btn-primary btn-sm" onclick="generateReport('students')">
                                        Générer
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-left-success">
                                <div class="card-body text-center">
                                    <i class="fas fa-graduation-cap fa-3x text-success mb-3"></i>
                                    <h5>Rapports Formations</h5>
                                    <p class="text-muted">Programmes, modules, évaluations</p>
                                    <button class="btn btn-success btn-sm" onclick="generateReport('formations')">
                                        Générer
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-left-info">
                                <div class="card-body text-center">
                                    <i class="fas fa-euro-sign fa-3x text-info mb-3"></i>
                                    <h5>Rapports Financiers</h5>
                                    <p class="text-muted">Paiements, revenus, impayés</p>
                                    <button class="btn btn-info btn-sm" onclick="generateReport('financial')">
                                        Générer
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-left-warning">
                                <div class="card-body text-center">
                                    <i class="fas fa-file-alt fa-3x text-warning mb-3"></i>
                                    <h5>Rapports Documents</h5>
                                    <p class="text-muted">Validations, CVThèque, certificats</p>
                                    <button class="btn btn-warning btn-sm" onclick="generateReport('documents')">
                                        Générer
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-left-secondary">
                                <div class="card-body text-center">
                                    <i class="fas fa-project-diagram fa-3x text-secondary mb-3"></i>
                                    <h5>Rapports Projets</h5>
                                    <p class="text-muted">TP, projets, évaluations</p>
                                    <button class="btn btn-secondary btn-sm" onclick="generateReport('projects')">
                                        Générer
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-left-dark">
                                <div class="card-body text-center">
                                    <i class="fas fa-cogs fa-3x text-dark mb-3"></i>
                                    <h5>Rapports Système</h5>
                                    <p class="text-muted">Performance, logs, utilisation</p>
                                    <button class="btn btn-dark btn-sm" onclick="generateReport('system')">
                                        Générer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reports and Analytics -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Rapports Récents</h6>
                    <a href="{{ route('admin.rapports.exports') }}" class="btn btn-sm btn-outline-primary">
                        Voir tous les exports
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Rapport</th>
                                    <th>Type</th>
                                    <th>Généré le</th>
                                    <th>Taille</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">Rapport Étudiants - Janvier 2024</div>
                                        <small class="text-muted">Inscriptions et performances</small>
                                    </td>
                                    <td><span class="badge badge-primary">Étudiants</span></td>
                                    <td>13/01/2024 14:30</td>
                                    <td>2.3 MB</td>
                                    <td><span class="badge badge-success">Terminé</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="downloadReport(1)">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-info" onclick="viewReport(1)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteReport(1)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">Rapport Financier - Q4 2023</div>
                                        <small class="text-muted">Revenus et paiements</small>
                                    </td>
                                    <td><span class="badge badge-info">Financier</span></td>
                                    <td>10/01/2024 09:15</td>
                                    <td>1.8 MB</td>
                                    <td><span class="badge badge-success">Terminé</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="downloadReport(2)">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-info" onclick="viewReport(2)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteReport(2)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">Export CVThèque</div>
                                        <small class="text-muted">Profils complets</small>
                                    </td>
                                    <td><span class="badge badge-warning">Documents</span></td>
                                    <td>08/01/2024 16:45</td>
                                    <td>5.2 MB</td>
                                    <td><span class="badge badge-warning">En cours</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary" disabled>
                                            <i class="fas fa-spinner fa-spin"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Analytics Dashboard -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Analytics en Temps Réel</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Utilisateurs actifs</span>
                            <strong>127</strong>
                        </div>
                        <div class="progress mt-1">
                            <div class="progress-bar bg-success" style="width: 75%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Nouvelles inscriptions</span>
                            <strong>23</strong>
                        </div>
                        <div class="progress mt-1">
                            <div class="progress-bar bg-primary" style="width: 60%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Documents validés</span>
                            <strong>45</strong>
                        </div>
                        <div class="progress mt-1">
                            <div class="progress-bar bg-info" style="width: 85%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Paiements reçus</span>
                            <strong>€12,450</strong>
                        </div>
                        <div class="progress mt-1">
                            <div class="progress-bar bg-warning" style="width: 90%"></div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.rapports.analytics') }}" class="btn btn-primary btn-sm">
                            Voir Analytics Complètes
                        </a>
                    </div>
                </div>
            </div>

            <!-- Scheduled Reports -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Rapports Programmés</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <div class="font-weight-bold">Rapport Mensuel</div>
                                <small class="text-muted">Prochain: 01/02/2024</small>
                            </div>
                            <span class="badge badge-primary">Actif</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <div class="font-weight-bold">Export CVThèque</div>
                                <small class="text-muted">Prochain: 15/01/2024</small>
                            </div>
                            <span class="badge badge-success">Actif</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <div class="font-weight-bold">Rapport Financier</div>
                                <small class="text-muted">Prochain: 31/01/2024</small>
                            </div>
                            <span class="badge badge-secondary">Suspendu</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Report Modal -->
<div class="modal fade" id="customReportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Créer un Rapport Personnalisé</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="customReportForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reportName">Nom du rapport</label>
                                <input type="text" class="form-control" id="reportName" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reportType">Type de rapport</label>
                                <select class="form-control" id="reportType" required>
                                    <option value="">Sélectionner...</option>
                                    <option value="students">Étudiants</option>
                                    <option value="formations">Formations</option>
                                    <option value="financial">Financier</option>
                                    <option value="documents">Documents</option>
                                    <option value="projects">Projets</option>
                                    <option value="system">Système</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dateFrom">Date de début</label>
                                <input type="date" class="form-control" id="dateFrom">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dateTo">Date de fin</label>
                                <input type="date" class="form-control" id="dateTo">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reportFormat">Format d'export</label>
                        <select class="form-control" id="reportFormat">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Sections à inclure</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="includeStats" checked>
                                    <label class="custom-control-label" for="includeStats">Statistiques</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="includeCharts" checked>
                                    <label class="custom-control-label" for="includeCharts">Graphiques</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="includeDetails">
                                    <label class="custom-control-label" for="includeDetails">Détails</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="includeAnalysis">
                                    <label class="custom-control-label" for="includeAnalysis">Analyse</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="submitCustomReport()">Générer Rapport</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function generateReport(type) {
    if (confirm(`Générer un rapport ${type} ?`)) {
        $.post('{{ route("admin.rapports.generate") }}', {
            type: type,
            _token: '{{ csrf_token() }}'
        }).done(function(response) {
            alert('Rapport en cours de génération...');
            location.reload();
        });
    }
}

function generateCustomReport() {
    $('#customReportModal').modal('show');
}

function submitCustomReport() {
    const form = $('#customReportForm');
    if (form[0].checkValidity()) {
        const data = {
            name: $('#reportName').val(),
            type: $('#reportType').val(),
            date_from: $('#dateFrom').val(),
            date_to: $('#dateTo').val(),
            format: $('#reportFormat').val(),
            include_stats: $('#includeStats').is(':checked'),
            include_charts: $('#includeCharts').is(':checked'),
            include_details: $('#includeDetails').is(':checked'),
            include_analysis: $('#includeAnalysis').is(':checked'),
            _token: '{{ csrf_token() }}'
        };
        
        $.post('{{ route("admin.rapports.generate") }}', data)
        .done(function(response) {
            $('#customReportModal').modal('hide');
            alert('Rapport personnalisé en cours de génération...');
            location.reload();
        });
    } else {
        form[0].reportValidity();
    }
}

function downloadReport(reportId) {
    window.open(`{{ route('admin.rapports.download', '') }}/${reportId}`, '_blank');
}

function viewReport(reportId) {
    window.open(`/evc/app/admin/rapports/view/${reportId}`, '_blank');
}

function deleteReport(reportId) {
    if (confirm('Supprimer ce rapport ?')) {
        $.ajax({
            url: `/evc/app/admin/rapports/delete/${reportId}`,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' }
        }).done(function(response) {
            location.reload();
        });
    }
}

// Auto-refresh analytics every 30 seconds
setInterval(function() {
    // Update real-time analytics
    $.get('/evc/app/admin/rapports/analytics/realtime')
    .done(function(data) {
        // Update analytics display
        console.log('Analytics updated:', data);
    });
}, 30000);
</script>
@endsection
