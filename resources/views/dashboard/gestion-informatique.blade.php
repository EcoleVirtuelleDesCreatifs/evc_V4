@extends('layouts.ki-admin')

@section('title', 'Espace Étudiant - Gestion Informatique')

@section('content')
<div class="container-fluid">
    <!-- Header spécialisé Gestion Informatique -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #6C5CE7 0%, #A29BFE 100%);">
                <div class="card-body text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="mb-2 fw-bold">
                                <i class="fas fa-server me-3"></i>
                                Espace Étudiant - Gestion Informatique
                            </h1>
                            <p class="mb-0 opacity-90">
                                Formation complète en IT : Réseaux, Sécurité, Administration Système & Cloud Computing
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <span class="badge bg-light text-dark px-3 py-2 fs-6">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    IT Administrator
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-primary bg-opacity-10 text-primary rounded">
                                <i class="fas fa-chart-line fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-5">{{ $stats['progression_globale'] }}%</div>
                            <div class="text-muted small">Progression Globale</div>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar" style="width: {{ $stats['progression_globale'] }}%; background: #6C5CE7;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-warning bg-opacity-10 text-warning rounded">
                                <i class="fas fa-terminal fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-5">{{ $stats['tp_a_faire'] }}</div>
                            <div class="text-muted small">Labs Système</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-success bg-opacity-10 text-success rounded">
                                <i class="fas fa-network-wired fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-5">{{ $stats['projets_en_cours'] }}</div>
                            <div class="text-muted small">Infra Projets</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-info bg-opacity-10 text-info rounded">
                                <i class="fas fa-certificate fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-5">
                                @if($stats['eligible_certificat'])
                                    <span class="text-success">Éligible</span>
                                @else
                                    <span class="text-warning">En cours</span>
                                @endif
                            </div>
                            <div class="text-muted small">Certification IT</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modules de formation spécialisés -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs text-primary me-2"></i>
                        Modules Gestion Informatique
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #2C3E50 0%, #3498DB 100%);">
                                <i class="fas fa-network-wired fs-1 text-white mb-2"></i>
                                <h6 class="text-white mb-1">Réseaux</h6>
                                <small class="text-white opacity-75">TCP/IP, Routing, VPN</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-light" style="width: 75%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #E74C3C 0%, #C0392B 100%);">
                                <i class="fas fa-shield-alt fs-1 text-white mb-2"></i>
                                <h6 class="text-white mb-1">Sécurité</h6>
                                <small class="text-white opacity-75">Firewall, Antivirus, Audit</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-light" style="width: 60%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #27AE60 0%, #2ECC71 100%);">
                                <i class="fas fa-server fs-1 text-white mb-2"></i>
                                <h6 class="text-white mb-1">Administration</h6>
                                <small class="text-white opacity-75">Windows, Linux, Active Directory</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-light" style="width: 50%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #9B59B6 0%, #8E44AD 100%);">
                                <i class="fas fa-cloud fs-1 text-white mb-2"></i>
                                <h6 class="text-white mb-1">Cloud Computing</h6>
                                <small class="text-white opacity-75">AWS, Azure, Docker</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-light" style="width: 40%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formation de la semaine -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-week text-primary me-2"></i>
                        Formation de la Semaine
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-lg bg-primary bg-opacity-10 text-primary rounded me-3">
                            <i class="fas fa-server"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $stats['formation_semaine'] }}</h6>
                            <small class="text-muted">Configuration et maintenance des serveurs Windows/Linux</small>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-terminal text-success me-2"></i>
                                    <span class="fw-semibold">Lab Pratique</span>
                                </div>
                                <small class="text-muted">Mardi 10h00 - 13h00</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-chalkboard-teacher text-info me-2"></i>
                                    <span class="fw-semibold">Cours Théorique</span>
                                </div>
                                <small class="text-muted">Jeudi 14h00 - 16h00</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Alertes Système
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-sm bg-success bg-opacity-10 text-success rounded me-2">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="small">Serveur Web: OK</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-sm bg-warning bg-opacity-10 text-warning rounded me-2">
                            <i class="fas fa-exclamation"></i>
                        </div>
                        <span class="small">Backup: Attention</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm bg-danger bg-opacity-10 text-danger rounded me-2">
                            <i class="fas fa-times"></i>
                        </div>
                        <span class="small">Firewall: Config</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Outils et technologies -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-toolbox text-primary me-2"></i>
                        Outils & Technologies IT
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="text-center p-3 border rounded-3 h-100">
                                <i class="fab fa-windows text-primary fs-2 mb-2"></i>
                                <h6 class="mb-1">Windows Server</h6>
                                <small class="text-muted">Administration</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="text-center p-3 border rounded-3 h-100">
                                <i class="fab fa-linux text-dark fs-2 mb-2"></i>
                                <h6 class="mb-1">Linux</h6>
                                <small class="text-muted">Ubuntu/CentOS</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="text-center p-3 border rounded-3 h-100">
                                <i class="fab fa-aws text-warning fs-2 mb-2"></i>
                                <h6 class="mb-1">AWS</h6>
                                <small class="text-muted">Cloud Platform</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="text-center p-3 border rounded-3 h-100">
                                <i class="fab fa-docker text-info fs-2 mb-2"></i>
                                <h6 class="mb-1">Docker</h6>
                                <small class="text-muted">Containerization</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="text-center p-3 border rounded-3 h-100">
                                <i class="fas fa-database text-success fs-2 mb-2"></i>
                                <h6 class="mb-1">MySQL</h6>
                                <small class="text-muted">Database</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="text-center p-3 border rounded-3 h-100">
                                <i class="fas fa-shield-alt text-danger fs-2 mb-2"></i>
                                <h6 class="mb-1">pfSense</h6>
                                <small class="text-muted">Firewall</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides spécialisées -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt text-primary me-2"></i>
                        Actions Rapides - Gestion Informatique
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-primary w-100 py-3">
                                <i class="fas fa-terminal d-block mb-2"></i>
                                <small>SSH Console</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-success w-100 py-3">
                                <i class="fas fa-chart-line d-block mb-2"></i>
                                <small>Monitoring</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-warning w-100 py-3">
                                <i class="fas fa-shield-alt d-block mb-2"></i>
                                <small>Sécurité</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-info w-100 py-3">
                                <i class="fas fa-cloud d-block mb-2"></i>
                                <small>Cloud Panel</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-danger w-100 py-3">
                                <i class="fas fa-database d-block mb-2"></i>
                                <small>Base de Données</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-secondary w-100 py-3">
                                <i class="fas fa-book d-block mb-2"></i>
                                <small>Documentation</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-lg {
    width: 56px;
    height: 56px;
}

.avatar-sm {
    width: 32px;
    height: 32px;
}

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.btn-outline-primary:hover,
.btn-outline-success:hover,
.btn-outline-warning:hover,
.btn-outline-info:hover,
.btn-outline-danger:hover,
.btn-outline-secondary:hover {
    transform: translateY(-1px);
}
</style>
@endsection
