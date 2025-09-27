@extends('layouts.admin')

@section('title', 'Gestion des Documents - EVC')

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

.btn-quick.success {
    background: linear-gradient(45deg, #28a745, #1e7e34);
}

.btn-quick.success:hover {
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
}

.btn-quick.danger {
    background: linear-gradient(45deg, #dc3545, #c82333);
}

.btn-quick.danger:hover {
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
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

.document-preview {
    max-width: 60px;
    max-height: 40px;
    object-fit: cover;
    border-radius: 5px;
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
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-file-alt me-3"></i>Gestion des Documents
        </h1>
        <p class="page-subtitle">
            Validez et gérez tous les documents soumis par les étudiants
        </p>
        
        <div class="quick-actions">
            <a href="#" class="btn-quick success" onclick="validateAllSelected()">
                <i class="fas fa-check me-2"></i>Valider Sélection
            </a>
            <a href="#" class="btn-quick danger" onclick="rejectAllSelected()">
                <i class="fas fa-times me-2"></i>Rejeter Sélection
            </a>
            <a href="#" class="btn-quick" onclick="exportDocuments()">
                <i class="fas fa-download me-2"></i>Exporter Liste
            </a>
        </div>
    </div>

    <!-- Statistiques des documents -->
    <div class="stats-grid">
        <!-- Documents en Attente -->
        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-number" data-counter="45">45</div>
            <div class="stat-label">En Attente de Validation</div>
            <div class="stat-actions">
                <a href="#" class="btn-stat" onclick="filterPending()">
                    <i class="fas fa-filter me-1"></i>Filtrer
                </a>
            </div>
        </div>

        <!-- Documents Validés -->
        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number" data-counter="234">234</div>
            <div class="stat-label">Documents Validés</div>
            <div class="stat-actions">
                <a href="#" class="btn-stat" onclick="filterValidated()">
                    <i class="fas fa-eye me-1"></i>Voir
                </a>
            </div>
        </div>

        <!-- Documents Rejetés -->
        <div class="stat-card danger">
            <div class="stat-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-number" data-counter="12">12</div>
            <div class="stat-label">Documents Rejetés</div>
            <div class="stat-actions">
                <a href="#" class="btn-stat" onclick="filterRejected()">
                    <i class="fas fa-exclamation me-1"></i>Revoir
                </a>
            </div>
        </div>

        <!-- Total Documents -->
        <div class="stat-card primary">
            <div class="stat-icon">
                <i class="fas fa-file"></i>
            </div>
            <div class="stat-number" data-counter="291">291</div>
            <div class="stat-label">Total Documents</div>
            <div class="stat-actions">
                <a href="#" class="btn-stat" onclick="showAll()">
                    <i class="fas fa-list me-1"></i>Tout Voir
                </a>
            </div>
        </div>
    </div>

    <!-- Liste des documents -->
    <div class="content-section">
        <h3 class="section-title">
            <i class="fas fa-folder-open me-2"></i>Documents en Attente de Validation
        </h3>
        
        <div class="table-responsive">
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                        </th>
                        <th>Aperçu</th>
                        <th>Étudiant</th>
                        <th>Type</th>
                        <th>Nom du Fichier</th>
                        <th>Taille</th>
                        <th>Date Soumission</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="checkbox" class="document-checkbox" value="1">
                        </td>
                        <td>
                            <div class="document-preview bg-secondary d-flex align-items-center justify-content-center">
                                <i class="fas fa-file-pdf text-danger"></i>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                    AK
                                </div>
                                <div>
                                    <div class="fw-bold">Aya Kouassi</div>
                                    <small class="text-muted">Design Graphique</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-info">CV</span></td>
                        <td>
                            <div>
                                <div class="fw-bold">CV_Aya_Kouassi.pdf</div>
                                <small class="text-muted">Document professionnel</small>
                            </div>
                        </td>
                        <td><small class="text-muted">2.3 MB</small></td>
                        <td><small class="text-muted">{{ date('d/m/Y H:i', strtotime('-2 hours')) }}</small></td>
                        <td><span class="badge bg-warning">En attente</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="#" class="btn btn-outline-primary btn-sm" onclick="previewDocument(1)">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-outline-success btn-sm" onclick="validateDocument(1)">
                                    <i class="fas fa-check"></i>
                                </a>
                                <a href="#" class="btn btn-outline-danger btn-sm" onclick="rejectDocument(1)">
                                    <i class="fas fa-times"></i>
                                </a>
                                <a href="#" class="btn btn-outline-info btn-sm" onclick="downloadDocument(1)">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" class="document-checkbox" value="2">
                        </td>
                        <td>
                            <div class="document-preview bg-secondary d-flex align-items-center justify-content-center">
                                <i class="fas fa-file-word text-primary"></i>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-info rounded-circle d-flex align-items-center justify-content-center me-2">
                                    MT
                                </div>
                                <div>
                                    <div class="fw-bold">Mamadou Traoré</div>
                                    <small class="text-muted">Community Management</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-warning">Lettre Motivation</span></td>
                        <td>
                            <div>
                                <div class="fw-bold">LM_Mamadou_Traore.docx</div>
                                <small class="text-muted">Lettre de motivation</small>
                            </div>
                        </td>
                        <td><small class="text-muted">1.8 MB</small></td>
                        <td><small class="text-muted">{{ date('d/m/Y H:i', strtotime('-4 hours')) }}</small></td>
                        <td><span class="badge bg-warning">En attente</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="#" class="btn btn-outline-primary btn-sm" onclick="previewDocument(2)">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-outline-success btn-sm" onclick="validateDocument(2)">
                                    <i class="fas fa-check"></i>
                                </a>
                                <a href="#" class="btn btn-outline-danger btn-sm" onclick="rejectDocument(2)">
                                    <i class="fas fa-times"></i>
                                </a>
                                <a href="#" class="btn btn-outline-info btn-sm" onclick="downloadDocument(2)">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" class="document-checkbox" value="3">
                        </td>
                        <td>
                            <div class="document-preview bg-secondary d-flex align-items-center justify-content-center">
                                <i class="fas fa-file-image text-success"></i>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-success rounded-circle d-flex align-items-center justify-content-center me-2">
                                    FD
                                </div>
                                <div>
                                    <div class="fw-bold">Fatou Diallo</div>
                                    <small class="text-muted">Intelligence Artificielle</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-success">Réalisation</span></td>
                        <td>
                            <div>
                                <div class="fw-bold">Projet_IA_Fatou.png</div>
                                <small class="text-muted">Portfolio - Réalisation</small>
                            </div>
                        </td>
                        <td><small class="text-muted">5.2 MB</small></td>
                        <td><small class="text-muted">{{ date('d/m/Y H:i', strtotime('-1 day')) }}</small></td>
                        <td><span class="badge bg-warning">En attente</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="#" class="btn btn-outline-primary btn-sm" onclick="previewDocument(3)">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-outline-success btn-sm" onclick="validateDocument(3)">
                                    <i class="fas fa-check"></i>
                                </a>
                                <a href="#" class="btn btn-outline-danger btn-sm" onclick="rejectDocument(3)">
                                    <i class="fas fa-times"></i>
                                </a>
                                <a href="#" class="btn btn-outline-info btn-sm" onclick="downloadDocument(3)">
                                    <i class="fas fa-download"></i>
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

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.document-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function validateAllSelected() {
    const selected = document.querySelectorAll('.document-checkbox:checked');
    if (selected.length === 0) {
        alert('Veuillez sélectionner au moins un document.');
        return;
    }
    
    if (confirm(`Valider ${selected.length} document(s) sélectionné(s) ?`)) {
        alert('Validation en cours de développement...');
    }
}

function rejectAllSelected() {
    const selected = document.querySelectorAll('.document-checkbox:checked');
    if (selected.length === 0) {
        alert('Veuillez sélectionner au moins un document.');
        return;
    }
    
    if (confirm(`Rejeter ${selected.length} document(s) sélectionné(s) ?`)) {
        alert('Rejet en cours de développement...');
    }
}

function exportDocuments() {
    alert('Export des documents en cours de développement...');
}

function filterPending() {
    alert('Filtrage des documents en attente...');
}

function filterValidated() {
    alert('Filtrage des documents validés...');
}

function filterRejected() {
    alert('Filtrage des documents rejetés...');
}

function showAll() {
    alert('Affichage de tous les documents...');
}

function previewDocument(id) {
    alert('Prévisualisation du document ID: ' + id);
}

function validateDocument(id) {
    if (confirm('Valider ce document ?')) {
        alert('Validation du document ID: ' + id + ' en cours de développement...');
    }
}

function rejectDocument(id) {
    const reason = prompt('Raison du rejet (optionnel):');
    if (reason !== null) {
        alert('Rejet du document ID: ' + id + ' avec raison: ' + (reason || 'Aucune raison spécifiée'));
    }
}

function downloadDocument(id) {
    alert('Téléchargement du document ID: ' + id + ' en cours...');
}
</script>
@endpush

        .admin-sidebar .sidebar-header p {
            color: rgba(255, 255, 255, 0.8);
            margin: 0.5rem 0 0 0;
            font-size: 0.9rem;
        }

        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.875rem 1.5rem;
            border-radius: 0;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            border-left-color: var(--accent-color);
        }

        .admin-sidebar .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
        }

        /* Main Content */
        .admin-main {
            margin-left: 280px;
            padding: 2rem;
        }

        /* Header */
        .admin-header {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-header h1 {
            margin: 0;
            color: var(--dark-color);
            font-weight: 700;
        }

        /* Content Cards */
        .content-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .content-card .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.25rem 1.5rem;
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
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .stats-card.pending { border-left: 4px solid var(--warning-color); }
        .stats-card.validated { border-left: 4px solid var(--success-color); }
        .stats-card.rejected { border-left: 4px solid var(--danger-color); }
        .stats-card.total { border-left: 4px solid var(--secondary-color); }

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

        .badge.bg-warning { background-color: var(--warning-color) !important; }
        .badge.bg-success { background-color: var(--success-color) !important; }
        .badge.bg-danger { background-color: var(--danger-color) !important; }

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

        /* Document Preview */
        .document-preview {
            max-width: 100px;
            max-height: 80px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .document-preview:hover {
            transform: scale(1.05);
        }

        /* Status Filters */
        .status-filter {
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

        /* Action Buttons */
        .action-buttons .btn {
            margin: 0 2px;
            transition: all 0.3s ease;
        }

        .action-buttons .btn:hover {
            transform: translateY(-2px);
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
                <a class="nav-link" href="{{ route('admin.etudiants') }}">
                    <i class="fas fa-users"></i>
                    Gestion Étudiants
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('admin.documents.index') }}">
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
                <h1><i class="fas fa-file-check me-2"></i>Validation des Documents</h1>
                <p class="text-muted mb-0">Gestion et validation des documents étudiants</p>
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
                <div class="stats-card pending">
                    <h3>{{ number_format($documentStats['pending'] ?? 0) }}</h3>
                    <p>En Attente</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card validated">
                    <h3>{{ number_format($documentStats['validated'] ?? 0) }}</h3>
                    <p>Validés</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card rejected">
                    <h3>{{ number_format($documentStats['rejected'] ?? 0) }}</h3>
                    <p>Rejetés</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card total">
                    <h3>{{ number_format($documentStats['total'] ?? 0) }}</h3>
                    <p>Total</p>
                </div>
            </div>
        </div>

        <!-- Status Filters -->
        <div class="status-filter">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Statut</label>
                    <select class="form-control" id="statusFilter">
                        <option value="">Tous les statuts</option>
                        <option value="en_cours">En cours d'analyse</option>
                        <option value="valide">Validé</option>
                        <option value="rejete">Rejeté</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Type de Document</label>
                    <select class="form-control" id="typeFilter">
                        <option value="">Tous les types</option>
                        <option value="cv">CV</option>
                        <option value="lettre-motivation">Lettre de Motivation</option>
                        <option value="diplome">Diplôme</option>
                        <option value="certificat">Certificat</option>
                        <option value="portfolio">Portfolio</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Rechercher Étudiant</label>
                    <input type="text" class="form-control" placeholder="Nom, email..." id="searchInput">
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

        <!-- Documents Table -->
        <div class="content-card">
            <div class="card-header">
                <h5><i class="fas fa-list me-2"></i>Documents à Valider</h5>
            </div>
            <div class="card-body">
                @if($documents && $documents->count() > 0)
                    <div class="table-responsive">
                        <table class="table" id="documentsTable">
                            <thead>
                                <tr>
                                    <th>Aperçu</th>
                                    <th>Étudiant</th>
                                    <th>Type</th>
                                    <th>Fichier</th>
                                    <th>Date de Soumission</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $doc)
                                <tr>
                                    <td>
                                        @if(in_array(pathinfo($doc->file_name, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                            <img src="{{ asset('storage/uploads/' . $doc->file_path) }}" 
                                                 class="document-preview" 
                                                 alt="Aperçu"
                                                 onclick="showPreview('{{ asset('storage/uploads/' . $doc->file_path) }}', '{{ $doc->file_name }}')">
                                        @else
                                            <div class="document-preview d-flex align-items-center justify-content-center bg-light">
                                                <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $doc->first_name }} {{ $doc->last_name }}</div>
                                        <div class="text-muted small">{{ $doc->email }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $doc->document_type }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $doc->file_name }}</div>
                                        <div class="text-muted small">{{ number_format($doc->file_size / 1024, 2) }} KB</div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($doc->created_at)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @switch($doc->status)
                                            @case('en_cours')
                                                <span class="badge bg-warning">En cours d'analyse</span>
                                                @break
                                            @case('valide')
                                                <span class="badge bg-success">Validé</span>
                                                @break
                                            @case('rejete')
                                                <span class="badge bg-danger">Rejeté</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">Inconnu</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            @if($doc->status === 'en_cours')
                                                <button class="btn btn-sm btn-success" 
                                                        onclick="validateDocument({{ $doc->id }}, 'valide')"
                                                        title="Valider">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" 
                                                        onclick="validateDocument({{ $doc->id }}, 'rejete')"
                                                        title="Rejeter">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-sm btn-primary" 
                                                    onclick="downloadDocument('{{ asset('storage/uploads/' . $doc->file_path) }}', '{{ $doc->file_name }}')"
                                                    title="Télécharger">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-sm btn-info" 
                                                    onclick="showDocumentDetails({{ $doc->id }})"
                                                    title="Détails">
                                                <i class="fas fa-info-circle"></i>
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
                            Affichage de {{ $documents->firstItem() }} à {{ $documents->lastItem() }} sur {{ $documents->total() }} documents
                        </div>
                        <div>
                            {{ $documents->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-check fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Aucun document trouvé</h4>
                        <p class="text-muted">Il n'y a pas de documents correspondant aux critères sélectionnés.</p>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Aperçu du Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="previewImage" src="" alt="Aperçu" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Filter functionality
        function applyFilters() {
            const status = document.getElementById('statusFilter').value;
            const type = document.getElementById('typeFilter').value;
            const search = document.getElementById('searchInput').value.toLowerCase();
            
            const rows = document.querySelectorAll('#documentsTable tbody tr');
            
            rows.forEach(row => {
                let show = true;
                
                // Status filter
                if (status) {
                    const statusCell = row.cells[5].textContent.toLowerCase();
                    if (!statusCell.includes(status.replace('_', ' '))) {
                        show = false;
                    }
                }
                
                // Type filter
                if (type) {
                    const typeCell = row.cells[2].textContent.toLowerCase();
                    if (!typeCell.includes(type)) {
                        show = false;
                    }
                }
                
                // Search filter
                if (search) {
                    const studentCell = row.cells[1].textContent.toLowerCase();
                    if (!studentCell.includes(search)) {
                        show = false;
                    }
                }
                
                row.style.display = show ? '' : 'none';
            });
        }

        // Document validation
        function validateDocument(documentId, status) {
            if (confirm(`Êtes-vous sûr de vouloir ${status === 'valide' ? 'valider' : 'rejeter'} ce document ?`)) {
                // Here you would make an AJAX call to update the document status
                fetch(`/admin/documents/${documentId}/validate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Erreur lors de la validation du document');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de la validation du document');
                });
            }
        }

        // Show preview modal
        function showPreview(imageSrc, fileName) {
            document.getElementById('previewImage').src = imageSrc;
            document.querySelector('#previewModal .modal-title').textContent = `Aperçu - ${fileName}`;
            new bootstrap.Modal(document.getElementById('previewModal')).show();
        }

        // Download document
        function downloadDocument(filePath, fileName) {
            const link = document.createElement('a');
            link.href = filePath;
            link.download = fileName;
            link.click();
        }

        // Show document details
        function showDocumentDetails(documentId) {
            // Here you would show detailed information about the document
            alert(`Détails du document ID: ${documentId}`);
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            applyFilters();
        });

        // Auto-apply filters on select change
        document.getElementById('statusFilter').addEventListener('change', applyFilters);
        document.getElementById('typeFilter').addEventListener('change', applyFilters);

        // Mobile sidebar toggle
        function toggleSidebar() {
            document.querySelector('.admin-sidebar').classList.toggle('show');
        }
    </script>
</body>
</html>
