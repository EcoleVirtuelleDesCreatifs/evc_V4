@extends('layouts.admin')

@section('title', 'Gestion des Administrateurs')

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

.table-dark {
    --bs-table-bg: rgba(255, 255, 255, 0.05);
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
            <i class="fas fa-users-cog me-3"></i>Gestion des Administrateurs
        </h1>
        <p class="page-subtitle">
            Gérez les comptes administrateurs et leurs permissions
        </p>
        
        <div class="quick-actions">
            <a href="{{ route('admin.admins.create') }}" class="btn-quick">
                <i class="fas fa-plus me-2"></i>Nouvel Admin
            </a>
            <a href="#" class="btn-quick" onclick="exportAdmins()">
                <i class="fas fa-download me-2"></i>Exporter Liste
            </a>
            <a href="#" class="btn-quick" onclick="bulkActions()">
                <i class="fas fa-tasks me-2"></i>Actions Groupées
            </a>
        </div>
    </div>

    <!-- Statistiques des administrateurs -->
    <div class="stats-grid">
        <!-- Total Administrateurs -->
        <div class="stat-card primary">
            <div class="stat-icon">
                <i class="fas fa-users-cog"></i>
            </div>
            <div class="stat-number" data-counter="12">12</div>
            <div class="stat-label">Total Administrateurs</div>
            <div class="stat-change positive">
                <span class="stat-change-value">+2</span>
                <span class="stat-change-label">ce mois</span>
            </div>
            <div class="stat-actions">
                <a href="#" class="btn-stat" onclick="filterAll()">
                    <i class="fas fa-eye me-1"></i>Voir Tous
                </a>
            </div>
        </div>

        <!-- Admins Actifs -->
        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-number" data-counter="10">10</div>
            <div class="stat-label">Administrateurs Actifs</div>
            <div class="stat-change positive">
                <span class="stat-change-value">83%</span>
                <span class="stat-change-label">taux d'activité</span>
            </div>
            <div class="stat-actions">
                <a href="#" class="btn-stat" onclick="filterActive()">
                    <i class="fas fa-filter me-1"></i>Filtrer
                </a>
            </div>
        </div>

        <!-- Super Admins -->
        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-crown"></i>
            </div>
            <div class="stat-number" data-counter="3">3</div>
            <div class="stat-label">Super Administrateurs</div>
            <div class="stat-change neutral">
                <span class="stat-change-value">25%</span>
                <span class="stat-change-label">du total</span>
            </div>
            <div class="stat-actions">
                <a href="#" class="btn-stat" onclick="filterSuperAdmins()">
                    <i class="fas fa-crown me-1"></i>Gérer
                </a>
            </div>
        </div>

        <!-- Connexions Aujourd'hui -->
        <div class="stat-card info">
            <div class="stat-icon">
                <i class="fas fa-sign-in-alt"></i>
            </div>
            <div class="stat-number" data-counter="7">7</div>
            <div class="stat-label">Connexions Aujourd'hui</div>
            <div class="stat-change positive">
                <span class="stat-change-value">+15%</span>
                <span class="stat-change-label">vs hier</span>
            </div>
            <div class="stat-actions">
                <a href="#" class="btn-stat" onclick="viewConnections()">
                    <i class="fas fa-chart-line me-1"></i>Détails
                </a>
            </div>
        </div>
    </div>

    <!-- Filtres et Recherche -->
    <div class="content-section">
        <h3 class="section-title">
            <i class="fas fa-search me-2"></i>Filtres et Recherche
        </h3>
        
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="search" class="form-label text-white">Rechercher</label>
                <input type="text" class="form-control" id="search" placeholder="Nom, email, rôle...">
            </div>
            <div class="col-md-3 mb-3">
                <label for="role-filter" class="form-label text-white">Rôle</label>
                <select class="form-control" id="role-filter">
                    <option value="">Tous les rôles</option>
                    <option value="super_admin">Super Admin</option>
                    <option value="admin">Admin</option>
                    <option value="moderator">Modérateur</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="status-filter" class="form-label text-white">Statut</label>
                <select class="form-control" id="status-filter">
                    <option value="">Tous les statuts</option>
                    <option value="active">Actif</option>
                    <option value="inactive">Inactif</option>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <label class="form-label text-white">&nbsp;</label>
                <button class="btn btn-primary w-100" id="apply-filters">
                    <i class="fas fa-search me-1"></i>Filtrer
                </button>
            </div>
        </div>
    </div>

    <!-- Liste des Administrateurs -->
    <div class="content-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="section-title mb-0">
                <i class="fas fa-list me-2"></i>Liste des Administrateurs
            </h3>
            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-v me-1"></i>Actions
                </button>
                <ul class="dropdown-menu dropdown-menu-dark">
                    <li><a class="dropdown-item" href="#" id="bulk-activate"><i class="fas fa-check me-2"></i>Activer sélectionnés</a></li>
                    <li><a class="dropdown-item" href="#" id="bulk-deactivate"><i class="fas fa-times me-2"></i>Désactiver sélectionnés</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('admin.admins.permissions') }}"><i class="fas fa-key me-2"></i>Gérer les permissions</a></li>
                </ul>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-dark table-hover" id="adminsTable">
                <thead>
                    <tr>
                        <th width="50">
                            <input type="checkbox" id="select-all" class="form-check-input">
                        </th>
                        <th>Administrateur</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Dernière Connexion</th>
                        <th>Statut</th>
                        <th>Créé le</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Exemple d'administrateur -->
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input admin-checkbox" value="1">
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white">Jean Dupont</div>
                                    <small class="text-muted">Super Administrateur</small>
                                </div>
                            </div>
                        </td>
                        <td class="text-white">jean.dupont@evc.com</td>
                        <td>
                            <span class="badge bg-danger">Super Admin</span>
                        </td>
                        <td class="text-white">Il y a 2 heures</td>
                        <td>
                            <span class="badge bg-success">Actif</span>
                        </td>
                        <td class="text-white">15/01/2024</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary btn-sm" title="Voir le profil">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-warning btn-sm" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm delete-admin" data-id="1" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input admin-checkbox" value="2">
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-success rounded-circle d-flex align-items-center justify-content-center me-3">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white">Marie Martin</div>
                                    <small class="text-muted">Administrateur</small>
                                </div>
                            </div>
                        </td>
                        <td class="text-white">marie.martin@evc.com</td>
                        <td>
                            <span class="badge bg-primary">Admin</span>
                        </td>
                        <td class="text-white">Hier à 16:30</td>
                        <td>
                            <span class="badge bg-success">Actif</span>
                        </td>
                        <td class="text-white">10/01/2024</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary btn-sm" title="Voir le profil">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-warning btn-sm" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm delete-admin" data-id="2" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input admin-checkbox" value="3">
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-warning rounded-circle d-flex align-items-center justify-content-center me-3">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white">Pierre Durand</div>
                                    <small class="text-muted">Modérateur</small>
                                </div>
                            </div>
                        </td>
                        <td class="text-white">pierre.durand@evc.com</td>
                        <td>
                            <span class="badge bg-warning">Modérateur</span>
                        </td>
                        <td class="text-white">Il y a 3 jours</td>
                        <td>
                            <span class="badge bg-secondary">Inactif</span>
                        </td>
                        <td class="text-white">05/01/2024</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary btn-sm" title="Voir le profil">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-warning btn-sm" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm delete-admin" data-id="3" title="Supprimer">
                                    <i class="fas fa-trash"></i>
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
                Affichage de 1 à 3 sur 12 administrateurs
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
</div>
                    <tbody>
                        <!-- Admin 1 -->
                        <tr>
                            <td><input type="checkbox" class="admin-checkbox" value="1"></td>
                            <td>
                                <img src="https://via.placeholder.com/40x40" class="rounded-circle" alt="Avatar" width="40" height="40">
                            </td>
                            <td>
                                <div class="font-weight-bold">Jean Dupont</div>
                                <small class="text-muted">Créé le 15/01/2024</small>
                            </td>
                            <td>jean.dupont@evc.com</td>
                            <td>
                                <span class="badge badge-danger">Super Admin</span>
                            </td>
                            <td>
                                <span class="badge badge-success">Actif</span>
                            </td>
                            <td>
                                <div>Aujourd'hui 14:30</div>
                                <small class="text-muted">IP: 192.168.1.100</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.admins.edit', 1) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-warning toggle-status" data-id="1" title="Changer statut">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger delete-admin" data-id="1" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Admin 2 -->
                        <tr>
                            <td><input type="checkbox" class="admin-checkbox" value="2"></td>
                            <td>
                                <img src="https://via.placeholder.com/40x40" class="rounded-circle" alt="Avatar" width="40" height="40">
                            </td>
                            <td>
                                <div class="font-weight-bold">Marie Martin</div>
                                <small class="text-muted">Créé le 20/01/2024</small>
                            </td>
                            <td>marie.martin@evc.com</td>
                            <td>
                                <span class="badge badge-primary">Admin</span>
                            </td>
                            <td>
                                <span class="badge badge-success">Actif</span>
                            </td>
                            <td>
                                <div>Hier 16:45</div>
                                <small class="text-muted">IP: 192.168.1.101</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.admins.edit', 2) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-warning toggle-status" data-id="2" title="Changer statut">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger delete-admin" data-id="2" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Admin 3 -->
                        <tr>
                            <td><input type="checkbox" class="admin-checkbox" value="3"></td>
                            <td>
                                <img src="https://via.placeholder.com/40x40" class="rounded-circle" alt="Avatar" width="40" height="40">
                            </td>
                            <td>
                                <div class="font-weight-bold">Pierre Durand</div>
                                <small class="text-muted">Créé le 25/01/2024</small>
                            </td>
                            <td>pierre.durand@evc.com</td>
                            <td>
                                <span class="badge badge-info">Modérateur</span>
                            </td>
                            <td>
                                <span class="badge badge-secondary">Inactif</span>
                            </td>
                            <td>
                                <div>Il y a 3 jours</div>
                                <small class="text-muted">IP: 192.168.1.102</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.admins.edit', 3) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-warning toggle-status" data-id="3" title="Changer statut">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger delete-admin" data-id="3" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Activité Récente des Admins</h6>
        </div>
        <div class="card-body">
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-marker bg-success"></div>
                    <div class="timeline-content">
                        <h6 class="timeline-title">Jean Dupont s'est connecté</h6>
                        <p class="timeline-text">Connexion depuis 192.168.1.100</p>
                        <small class="text-muted">Il y a 2 heures</small>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-marker bg-info"></div>
                    <div class="timeline-content">
                        <h6 class="timeline-title">Marie Martin a validé 5 documents</h6>
                        <p class="timeline-text">Documents CVThèque validés</p>
                        <small class="text-muted">Il y a 4 heures</small>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-marker bg-warning"></div>
                    <div class="timeline-content">
                        <h6 class="timeline-title">Pierre Durand - Compte désactivé</h6>
                        <p class="timeline-text">Désactivé pour inactivité prolongée</p>
                        <small class="text-muted">Il y a 3 jours</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer cet administrateur ? Cette action est irréversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">Supprimer</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#adminsTable').DataTable({
        "pageLength": 10,
        "order": [[ 2, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": [0, 7] }
        ]
    });

    // Select all checkbox
    $('#select-all').change(function() {
        $('.admin-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Toggle status
    $('.toggle-status').click(function() {
        const adminId = $(this).data('id');
        if (confirm('Changer le statut de cet administrateur ?')) {
            $.post(`/evc/app/admin/admins/${adminId}/toggle-status`, {
                _token: '{{ csrf_token() }}'
            }).done(function(response) {
                location.reload();
            });
        }
    });

    // Delete admin
    let deleteId = null;
    $('.delete-admin').click(function() {
        deleteId = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    $('#confirm-delete').click(function() {
        if (deleteId) {
            $.ajax({
                url: `/evc/app/admin/admins/${deleteId}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                }
            }).done(function(response) {
                location.reload();
            });
        }
    });

    // Bulk actions
    $('#bulk-activate').click(function() {
        const selected = $('.admin-checkbox:checked').map(function() {
            return $(this).val();
        }).get();
        
        if (selected.length > 0) {
            // Implement bulk activate
            console.log('Activate:', selected);
        }
    });

    $('#bulk-deactivate').click(function() {
        const selected = $('.admin-checkbox:checked').map(function() {
            return $(this).val();
        }).get();
        
        if (selected.length > 0) {
            // Implement bulk deactivate
            console.log('Deactivate:', selected);
        }
    });

    // Filters
    $('#apply-filters').click(function() {
        const search = $('#search').val();
        const role = $('#role-filter').val();
        const status = $('#status-filter').val();
        
        // Apply filters to DataTable
        const table = $('#adminsTable').DataTable();
        table.search(search).draw();
    });
});
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
</style>
@endsection
