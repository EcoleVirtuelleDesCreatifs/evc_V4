@extends('layouts.admin')

@section('title', 'Gestion CVThèque')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-text">
                <h1 class="page-title">
                    <i class="fas fa-briefcase me-2"></i>Gestion CVThèque
                </h1>
                <p class="page-subtitle">Gérez les profils et documents des étudiants</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-success me-2" onclick="exportCVTheque()">
                    <i class="fas fa-download me-1"></i>Exporter
                </button>
                <button class="btn btn-warning" onclick="showPendingValidation()">
                    <i class="fas fa-clock me-1"></i>En attente (24)
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-section">
        <div class="quick-actions-grid">
            <button class="quick-action-btn primary" onclick="addNewProfile()">
                <i class="fas fa-user-plus"></i>
                <span>Nouveau Profil</span>
            </button>
            <button class="quick-action-btn success" onclick="validateDocuments()">
                <i class="fas fa-check-circle"></i>
                <span>Valider Documents</span>
            </button>
            <button class="quick-action-btn warning" onclick="generateReport()">
                <i class="fas fa-chart-bar"></i>
                <span>Générer Rapport</span>
            </button>
            <button class="quick-action-btn info" onclick="exportProfiles()">
                <i class="fas fa-download"></i>
                <span>Exporter Profils</span>
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">156</div>
                <div class="stat-label">Profils Complets</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span class="stat-change-value">+12%</span>
                    <span class="stat-change-text">ce mois</span>
                </div>
            </div>
            <div class="stat-action">
                <button class="btn-stat-action" onclick="viewCompleteProfiles()">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">24</div>
                <div class="stat-label">En Validation</div>
                <div class="stat-change neutral">
                    <i class="fas fa-minus"></i>
                    <span class="stat-change-value">Stable</span>
                    <span class="stat-change-text">cette semaine</span>
                </div>
            </div>
            <div class="stat-action">
                <button class="btn-stat-action" onclick="showPendingValidation()">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-file-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">342</div>
                <div class="stat-label">Documents Validés</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span class="stat-change-value">+8%</span>
                    <span class="stat-change-text">ce mois</span>
                </div>
            </div>
            <div class="stat-action">
                <button class="btn-stat-action" onclick="viewValidatedDocuments()">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="stat-card info">
            <div class="stat-icon">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">78%</div>
                <div class="stat-label">Taux de Complétion</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span class="stat-change-value">+5%</span>
                    <span class="stat-change-text">ce mois</span>
                </div>
            </div>
            <div class="stat-action">
                <button class="btn-stat-action" onclick="viewCompletionStats()">
                    </div>
                </div>
                <div class="content-body">
                    <div class="table-container">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Nom</th>
                                    <th>Formation</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <img src="https://via.placeholder.com/40x40" class="profile-avatar" alt="Photo">
                                    </td>
                                    <td class="fw-medium">Sophie Martin</td>
                                    <td>Design Graphique</td>
                                    <td><span class="badge badge-success">Validé</span></td>
                                    <td class="text-muted">2024-01-15</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-primary" onclick="viewProfile(1)" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success" onclick="validateProfile(1)" title="Valider">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-info" onclick="downloadProfile(1)" title="Télécharger">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="https://via.placeholder.com/40x40" class="profile-avatar" alt="Photo">
                                    </td>
                                    <td class="fw-medium">Marc Dubois</td>
                                    <td>Community Management</td>
                                    <td><span class="badge badge-warning">En attente</span></td>
                                    <td class="text-muted">2024-01-14</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-primary" onclick="viewProfile(2)" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success" onclick="validateProfile(2)" title="Valider">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-info" onclick="downloadProfile(2)" title="Télécharger">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="https://via.placeholder.com/40x40" class="profile-avatar" alt="Photo">
                                    </td>
                                    <td class="fw-medium">Julie Moreau</td>
                                    <td>Intelligence Artificielle</td>
                                    <td><span class="badge badge-info">En cours</span></td>
                                    <td class="text-muted">2024-01-13</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-primary" onclick="viewProfile(3)" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success" onclick="validateProfile(3)" title="Valider">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-info" onclick="downloadProfile(3)" title="Télécharger">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="https://via.placeholder.com/40x40" class="profile-avatar" alt="Photo">
                                    </td>
                                    <td class="fw-medium">Pierre Leroy</td>
                                    <td>Gestion Informatique</td>
                                    <td><span class="badge badge-danger">Rejeté</span></td>
                                    <td class="text-muted">2024-01-12</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-primary" onclick="viewProfile(4)" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success" onclick="validateProfile(4)" title="Valider">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-info" onclick="downloadProfile(4)" title="Télécharger">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="https://via.placeholder.com/40x40" class="profile-avatar" alt="Photo">
                                    </td>
                                    <td class="fw-medium">Emma Rousseau</td>
                                    <td>Design Graphique</td>
                                    <td><span class="badge badge-success">Validé</span></td>
                                    <td class="text-muted">2024-01-11</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-primary" onclick="viewProfile(5)" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success" onclick="validateProfile(5)" title="Valider">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-info" onclick="downloadProfile(5)" title="Télécharger">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="pagination-container">
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-dark">
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
            </div>
            
            <!-- Activité Récente -->
            <div class="content-card">
                <div class="content-header">
                    <h3 class="content-title">
                        <i class="fas fa-clock me-2"></i>Activité Récente
                    </h3>
                    <div class="content-actions">
                        <button class="btn btn-sm btn-primary" onclick="refreshActivity()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="content-body">
                    <div class="activity-timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker success">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">CV validé</div>
                                <div class="timeline-text">Sophie Martin - Design Graphique</div>
                                <div class="timeline-time">Il y a 2 heures</div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker warning">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Document en attente</div>
                                <div class="timeline-text">Marc Dubois - Community Management</div>
                                <div class="timeline-time">Il y a 4 heures</div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker info">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Nouveau profil</div>
                                <div class="timeline-text">Julie Moreau - Intelligence Artificielle</div>
                                <div class="timeline-time">Il y a 6 heures</div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker danger">
                                <i class="fas fa-times"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Document rejeté</div>
                                <div class="timeline-text">Pierre Leroy - Gestion Informatique</div>
                                <div class="timeline-time">Il y a 1 jour</div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker success">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Profil complété</div>
                                <div class="timeline-text">Emma Rousseau - Design Graphique</div>
                                <div class="timeline-time">Il y a 2 jours</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Profil CVThèque</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="profileContent">
                    <!-- Profile content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-success" onclick="validateCurrentProfile()">Valider</button>
                    <button type="button" class="btn btn-primary" onclick="downloadCurrentProfile()">Télécharger</button>
</div>

@endsection

@push('styles')
<style>
/* Page Header */
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    color: white;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.page-subtitle {
    margin: 0.5rem 0 0 0;
    opacity: 0.9;
    font-size: 1.1rem;
}

.header-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

/* Quick Actions */
.quick-actions-section {
    margin-bottom: 2rem;
}

.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.quick-action-btn {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 1.5rem;
    color: white;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    cursor: pointer;
}

.quick-action-btn:hover {
    transform: translateY(-3px);
    background: rgba(255, 255, 255, 0.2);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    color: white;
}

.quick-action-btn.primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.quick-action-btn.success { background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%); }
.quick-action-btn.warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.quick-action-btn.info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

.quick-action-btn i {
    font-size: 2rem;
}

.quick-action-btn span {
    font-weight: 600;
    font-size: 0.9rem;
}

/* Statistics Grid */
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
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.stat-card.primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-card.success { background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%); }
.stat-card.warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.stat-card.info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

.stat-icon {
    font-size: 2.5rem;
    opacity: 0.8;
    flex-shrink: 0;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 2.5rem;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
    margin-bottom: 0.5rem;
}

.stat-change {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.8rem;
}

.stat-change.positive { color: #a8e6cf; }
.stat-change.negative { color: #ffcccb; }
.stat-change.neutral { color: #f0f0f0; }

.stat-action {
    flex-shrink: 0;
}

.btn-stat-action {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-stat-action:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
}

/* Content Section */
.content-section {
    margin-bottom: 2rem;
}

.content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

.content-card {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    overflow: hidden;
}

.content-header {
    padding: 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.content-title {
    color: white;
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.content-actions {
    display: flex;
    gap: 0.5rem;
}

.content-body {
    padding: 1.5rem;
}

/* Table Styles */
.table-container {
    background: rgba(0, 0, 0, 0.3);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 1rem;
}

.table-dark {
    background: transparent;
    margin: 0;
}

.table-dark th,
.table-dark td {
    border-color: rgba(255, 255, 255, 0.1);
    padding: 1rem 0.75rem;
    vertical-align: middle;
}

.table-dark th {
    background: rgba(255, 255, 255, 0.1);
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
}

.profile-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.action-buttons {
    display: flex;
    gap: 0.25rem;
}

.action-buttons .btn {
    padding: 0.375rem 0.5rem;
    border-radius: 6px;
    border: none;
    transition: all 0.3s ease;
}

.action-buttons .btn:hover {
    transform: scale(1.1);
}

/* Badges */
.badge {
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-success { background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%); }
.badge-warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.badge-info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.badge-danger { background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%); }

/* Pagination */
.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 1rem;
}

.pagination-dark .page-link {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
    margin: 0 2px;
    border-radius: 6px;
}

.pagination-dark .page-link:hover {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.pagination-dark .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: transparent;
}

/* Activity Timeline */
.activity-timeline {
    position: relative;
}

.timeline-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.5rem;
    position: relative;
}

.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 20px;
    top: 40px;
    width: 2px;
    height: calc(100% + 0.5rem);
    background: rgba(255, 255, 255, 0.1);
}

.timeline-marker {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.9rem;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}

.timeline-marker.success { background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%); }
.timeline-marker.warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.timeline-marker.info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.timeline-marker.danger { background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%); }

.timeline-content {
    flex: 1;
    color: white;
}

.timeline-title {
    font-weight: 600;
    margin-bottom: 0.25rem;
    font-size: 0.95rem;
}

.timeline-text {
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 0.25rem;
    font-size: 0.85rem;
}

.timeline-time {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.75rem;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }
    
    .content-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        align-items: stretch;
        text-align: center;
    }
    
    .quick-actions-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .page-header {
        padding: 1.5rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
}

@media (max-width: 576px) {
    .quick-action-btn {
        padding: 1rem;
    }
    
    .quick-action-btn i {
        font-size: 1.5rem;
    }
    
    .stat-card {
        padding: 1rem;
    }
    
    .stat-value {
        font-size: 2rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.125rem;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Formation Chart
    const ctx = document.getElementById('formationChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Design Graphique', 'Community Management', 'Intelligence Artificielle', 'Gestion Informatique'],
            datasets: [{
                data: [45, 25, 20, 10],
                backgroundColor: [
                    '#4e73df',
                    '#1cc88a',
                    '#36b9cc',
                    '#f6c23e'
                ]
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});

let currentProfileId = null;

function viewProfile(profileId) {
    currentProfileId = profileId;
    
    // Simulate loading profile data
    const profileContent = `
        <div class="row">
            <div class="col-md-4 text-center">
                <img src="https://via.placeholder.com/150x150" class="img-thumbnail mb-3" alt="Photo">
                <h5>Sophie Martin</h5>
                <p class="text-muted">Design Graphique</p>
            </div>
            <div class="col-md-8">
                <h6>Informations Professionnelles</h6>
                <table class="table table-sm">
                    <tr><td><strong>Titre:</strong></td><td>Designer Graphique Junior</td></tr>
                    <tr><td><strong>Expérience:</strong></td><td>2 ans</td></tr>
                    <tr><td><strong>Email:</strong></td><td>sophie.martin@email.com</td></tr>
                    <tr><td><strong>Téléphone:</strong></td><td>+33 6 12 34 56 78</td></tr>
                </table>
                
                <h6>Documents</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-file-pdf fa-2x text-danger mb-2"></i>
                                <p>CV.pdf</p>
                                <span class="badge badge-success">Validé</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-file-pdf fa-2x text-danger mb-2"></i>
                                <p>Lettre_Motivation.pdf</p>
                                <span class="badge badge-success">Validé</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('#profileContent').html(profileContent);
    $('#profileModal').modal('show');
}

function validateProfile(profileId) {
    if (confirm('Valider ce profil CVThèque ?')) {
        $.post(`/evc/app/admin/cvtheque/validate/${profileId}`, {
            _token: '{{ csrf_token() }}'
        }).done(function(response) {
            location.reload();
        });
    }
}

function downloadProfile(profileId) {
    window.open(`/evc/app/admin/cvtheque/download/${profileId}`, '_blank');
}

function validateCurrentProfile() {
    if (currentProfileId) {
        validateProfile(currentProfileId);
    }
}

function downloadCurrentProfile() {
    if (currentProfileId) {
        downloadProfile(currentProfileId);
    }
}

function generateReport() {
    if (confirm('Générer un rapport CVThèque complet ?')) {
        window.open('/evc/app/admin/rapports/generate?type=cvtheque', '_blank');
    }
}
</script>

<style>
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

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -29px;
    top: 17px;
    width: 2px;
    height: calc(100% + 5px);
    background: #e3e6f0;
}

.timeline-title {
    margin-bottom: 5px;
    font-size: 14px;
}

.timeline-text {
    margin-bottom: 5px;
    color: #6c757d;
    font-size: 13px;
}

.btn-block {
    display: block;
    width: 100%;
    text-align: center;
    padding: 1rem;
    height: auto;
}
</style>
@endsection
