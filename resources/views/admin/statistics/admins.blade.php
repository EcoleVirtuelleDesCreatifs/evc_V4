@extends('layouts.admin')

@section('title')
Gestion Admins
@endsection

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-secondary">
                <i class="fas fa-user-shield me-2"></i>Gestion des Administrateurs
            </h1>
            <p class="text-muted mt-2">Tableau de bord pour la gestion des administrateurs</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-danger mb-3">
                        <i class="fas fa-crown fa-3x"></i>
                    </div>
                    <div class="h2 text-danger mb-1">3</div>
                    <h6 class="text-muted mb-2">Super Admin</h6>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-danger" style="width: 25%"></div>
                    </div>
                    <small class="text-muted">25% actifs</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-warning mb-3">
                        <i class="fas fa-user-cog fa-3x"></i>
                    </div>
                    <div class="h2 text-warning mb-1">4</div>
                    <h6 class="text-muted mb-2">Admin Principal</h6>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-warning" style="width: 33%"></div>
                    </div>
                    <small class="text-muted">33% actifs</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-info mb-3">
                        <i class="fas fa-shield-alt fa-3x"></i>
                    </div>
                    <div class="h2 text-info mb-1">5</div>
                    <h6 class="text-muted mb-2">Modérateur</h6>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-info" style="width: 42%"></div>
                    </div>
                    <small class="text-muted">42% actifs</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-success mb-3">
                        <i class="fas fa-headset fa-3x"></i>
                    </div>
                    <div class="h2 text-success mb-1">8</div>
                    <h6 class="text-muted mb-2">Support</h6>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: 67%"></div>
                    </div>
                    <small class="text-muted">67% actifs</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users text-secondary me-2"></i>Liste des Administrateurs
                    </h5>
                    <div>
                        <button class="btn btn-primary">
                            <i class="fas fa-user-plus me-1"></i>Nouvel Admin
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
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://via.placeholder.com/40x40" class="rounded-circle me-3" style="width: 40px; height: 40px;" alt="Avatar">
                                            <div>
                                                <div class="fw-bold">Admin Principal</div>
                                                <small class="text-muted">admin@evc.com</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">Super Admin</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            <i class="fas fa-circle me-1"></i>En ligne
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://via.placeholder.com/40x40" class="rounded-circle me-3" style="width: 40px; height: 40px;" alt="Avatar">
                                            <div>
                                                <div class="fw-bold">Sophie Martin</div>
                                                <small class="text-muted">sophie@evc.com</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">Admin Principal</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            <i class="fas fa-circle me-1"></i>En ligne
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
