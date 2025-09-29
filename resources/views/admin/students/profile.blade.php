@extends('layouts.admin')

@section('title', 'Profil Étudiant - ' . ($data['student']['prenom'] ?? 'Étudiant') . ' ' . ($data['student']['nom'] ?? ''))

@section('content')
<div class="container-fluid px-4">
    <!-- Header avec breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.statistics.detail', 'total-students') }}">Étudiants</a></li>
                    <li class="breadcrumb-item active">Profil Étudiant</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-primary">
                    <i class="fas fa-user-graduate me-2"></i>Profil Étudiant
                </h1>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.students.edit', $data['student']['id']) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>Modifier
                    </a>
                    <button class="btn btn-success" onclick="exportStudentProfile()">
                        <i class="fas fa-file-pdf me-1"></i>Exporter PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informations personnelles -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>Informations Personnelles
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if(!empty($data['student']['photo_url']))
                            <img src="{{ $data['student']['photo_url'] }}" alt="Photo de profil" class="rounded-circle mb-3" style="width:120px;height:120px;object-fit:cover;">
                        @else
                            <div class="avatar-lg bg-primary rounded-circle text-white d-flex align-items-center justify-content-center mx-auto mb-3">
                                {{ substr($data['student']['prenom'] ?? 'E', 0, 1) }}{{ substr($data['student']['nom'] ?? 'T', 0, 1) }}
                            </div>
                        @endif
                        <h4 class="mb-1">{{ $data['student']['prenom'] ?? 'Prénom' }} {{ $data['student']['nom'] ?? 'Nom' }}</h4>
                        <span class="badge bg-primary fs-6">{{ $data['student']['formation_souhaitee'] ?? 'Formation' }}</span>
                    </div>

                    <div class="info-group">
                        <div class="info-item">
                            <i class="fas fa-envelope text-muted me-2"></i>
                            <strong>Email:</strong> {{ $data['student']['email'] ?? '-' }}
                        </div>
                        <div class="info-item">
                            <i class="fas fa-phone text-muted me-2"></i>
                            <strong>Téléphone:</strong> {{ $data['student']['phone'] ?? '-' }}
                        </div>
                        <div class="info-item">
                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                            <strong>Ville:</strong> {{ $data['student']['ville'] ?? '-' }}
                        </div>
                        <div class="info-item">
                            <i class="fas fa-globe text-muted me-2"></i>
                            <strong>Pays:</strong> {{ $data['student']['pays'] ?? '-' }}
                        </div>
                        <div class="info-item">
                            <i class="fas fa-calendar text-muted me-2"></i>
                            <strong>Inscription:</strong> {{ isset($data['student']['created_at']) ? date('d/m/Y', strtotime($data['student']['created_at'])) : '-' }}
                        </div>
                        <div class="info-item">
                            <i class="fas fa-id-card text-muted me-2"></i>
                            <strong>ID Étudiant:</strong> #{{ $data['student']['id'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques et progression -->
        <div class="col-lg-8 mb-4">
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-gradient-primary text-white">
                        <div class="card-body text-center">
                            <div class="h3 fw-bold mb-1">{{ $data['stats']['total_tp'] }}</div>
                            <small>TP Total</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-gradient-success text-white">
                        <div class="card-body text-center">
                            <div class="h3 fw-bold mb-1">{{ $data['stats']['tp_valides'] }}</div>
                            <small>TP Validés</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-gradient-warning text-white">
                        <div class="card-body text-center">
                            <div class="h3 fw-bold mb-1">{{ $data['stats']['tp_en_cours'] }}</div>
                            <small>TP En Cours</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-gradient-info text-white">
                        <div class="card-body text-center">
                            <div class="h3 fw-bold mb-1">{{ $data['stats']['progression'] }}%</div>
                            <small>Progression</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progression détaillée -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line text-primary me-2"></i>Progression de Formation
                    </h5>
                </div>
                <div class="card-body">
                    <div class="progress mb-3" style="height: 20px;">
                        <div class="progress-bar bg-success" style="width: {{ $data['stats']['progression'] }}%">
                            {{ $data['stats']['progression'] }}%
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="text-success">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <div class="fw-bold">{{ $data['stats']['tp_valides'] }}</div>
                                <small class="text-muted">TP Validés</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-warning">
                                <i class="fas fa-clock fa-2x mb-2"></i>
                                <div class="fw-bold">{{ $data['stats']['tp_en_cours'] }}</div>
                                <small class="text-muted">En Validation</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-info">
                                <i class="fas fa-file-alt fa-2x mb-2"></i>
                                <div class="fw-bold">{{ $data['stats']['total_files_size'] }} MB</div>
                                <small class="text-muted">Fichiers Stockés</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des TP/Projets -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tasks text-primary me-2"></i>Travaux Pratiques & Projets
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="projectsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Titre</th>
                                    <th>Type</th>
                                    <th>Statut</th>
                                    <th>Fichiers</th>
                                    <th>Taille</th>
                                    <th>Date Création</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['projects'] as $index => $project)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $project['title'] ?? 'TP ' . ($index + 1) }}</div>
                                        <small class="text-muted">{{ $project['description'] ?? 'Description non disponible' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $project['project_type'] ?? 'TP' }}</span>
                                    </td>
                                    <td>
                                        @if(($project['status'] ?? 'en_cours') === 'valide')
                                            <span class="badge bg-success">Validé</span>
                                        @elseif(($project['status'] ?? 'en_cours') === 'en_cours')
                                            <span class="badge bg-warning">En Validation</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $project['status'] ?? 'En Cours' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $project['files_count'] ?? 0 }}</span>
                                    </td>
                                    <td>{{ round(($project['total_size'] ?? 0) / 1024 / 1024, 2) }} MB</td>
                                    <td>{{ isset($project['created_at']) ? date('d/m/Y', strtotime($project['created_at'])) : '-' }}</td>
                                    <td>
                                        <!-- ACTIONS SIMPLES - UN SEUL CLIC -->
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-primary" 
                                                    onclick="showProjectDetails({{ $project['id'] ?? 0 }})" 
                                                    title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if(($project['status'] ?? 'en_cours') === 'en_cours')
                                            <button class="btn btn-sm btn-success" 
                                                    onclick="if(confirm('Valider ce projet ?')) validateProject({{ $project['id'] ?? 0 }})" 
                                                    title="Valider">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            @endif
                                            <button class="btn btn-sm btn-outline-secondary" 
                                                    onclick="downloadProject({{ $project['id'] ?? 0 }})" 
                                                    title="Télécharger">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-tasks fa-2x mb-2"></i>
                                            <p>Aucun TP ou projet trouvé</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser DataTable
    if (document.getElementById('projectsTable')) {
        $('#projectsTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[6, 'desc']], // Trier par date de création
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
            },
            columnDefs: [
                { orderable: false, targets: [7] }
            ]
        });
    }
});

// ============================================================================
// RÉÉCRITURE COMPLÈTE - APPROCHE DÉVELOPPEUR SENIOR
// Code simple, clair, structuré et professionnel
// ============================================================================

/**
 * Export PDF du profil étudiant
 */
function exportStudentPDF() {
    const studentId = {{ $data['student']['id'] }};
    const url = `{{ route("admin.students.export-pdf") }}?student_id=${studentId}`;
    window.open(url, '_blank');
}

/**
 * Afficher les détails d'un projet - VERSION SENIOR SIMPLE
 * @param {number} projectId - ID du projet à afficher
 */
function showProjectDetails(projectId) {
    // Validation de l'ID
    if (!projectId || projectId <= 0) {
        alert('ID de projet invalide');
        return;
    }
    
    // Création de la modal simple
    const modalId = 'projectDetailsModal';
    
    // Supprimer l'ancienne modal si elle existe
    const existingModal = document.getElementById(modalId);
    if (existingModal) {
        existingModal.remove();
    }
    
    // HTML de la modal
    const modalHTML = `
        <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content bg-dark text-white">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title">
                            <i class="fas fa-eye me-2 text-primary"></i>
                            Détails du Projet
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="projectDetailsContent">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary mb-3" role="status"></div>
                            <p>Chargement des détails du projet...</p>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Ajouter la modal au DOM
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Afficher la modal
    const modal = new bootstrap.Modal(document.getElementById(modalId));
    modal.show();
    
    // Appel AJAX pour récupérer les données
    loadProjectData(projectId);
}

/**
 * Charger les données du projet via AJAX
 * @param {number} projectId - ID du projet
 */
function loadProjectData(projectId) {
    const url = `/evc/app/admin/projects/view/${projectId}`;
    
    fetch(url, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.project) {
            displayProjectData(data.project);
        } else {
            showProjectError('Erreur lors du chargement des données');
        }
    })
    .catch(error => {
        console.error('Erreur AJAX:', error);
        showProjectError('Erreur de connexion au serveur');
    });
}

/**
 * Afficher les données du projet dans la modal
 * @param {Object} project - Données du projet
 */
function displayProjectData(project) {
    // Traitement des logiciels
    let softwareList = 'Non spécifié';
    if (project.software_used) {
        if (Array.isArray(project.software_used)) {
            softwareList = project.software_used.join(', ');
        } else if (typeof project.software_used === 'string') {
            softwareList = project.software_used;
        }
    }
    
    // Traitement des fichiers
    let filesHTML = '<p class="text-muted">Aucun fichier</p>';
    if (project.images && project.images.length > 0) {
        filesHTML = `
            <div class="row">
                ${project.images.map(file => `
                    <div class="col-md-6 mb-2">
                        <div class="card bg-secondary border-0">
                            <div class="card-body p-2">
                                <h6 class="card-title text-truncate mb-1">${file.original_name}</h6>
                                <small class="text-muted">${file.file_size || 'Taille inconnue'}</small>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }
    
    // HTML du contenu
    const contentHTML = `
        <div class="row">
            <div class="col-12">
                <div class="mb-4">
                    <h4 class="text-primary mb-3">${project.title}</h4>
                    <p class="mb-3">${project.description || 'Aucune description disponible'}</p>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-primary">Informations</h6>
                        <p><strong>Statut:</strong> <span class="badge bg-info">${project.status_label || project.status}</span></p>
                        <p><strong>Étudiant:</strong> ${project.user.name}</p>
                        <p><strong>Email:</strong> ${project.user.email}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Détails</h6>
                        <p><strong>Créé le:</strong> ${project.created_at}</p>
                        <p><strong>Logiciels:</strong> ${softwareList}</p>
                        <p><strong>Fichiers:</strong> ${project.images ? project.images.length : 0}</p>
                    </div>
                </div>
                
                <div>
                    <h6 class="text-primary mb-3">Fichiers associés</h6>
                    ${filesHTML}
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('projectDetailsContent').innerHTML = contentHTML;
}

/**
 * Afficher une erreur dans la modal
 * @param {string} message - Message d'erreur
 */
function showProjectError(message) {
    const errorHTML = `
        <div class="text-center py-4">
            <div class="mb-3">
                <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
            </div>
            <h5 class="text-danger">Erreur</h5>
            <p>${message}</p>
        </div>
    `;
    document.getElementById('projectDetailsContent').innerHTML = errorHTML;
}

/**
 * Valider un projet
 * @param {number} projectId - ID du projet à valider
 */
function validateProject(projectId) {
    // TODO: Implémenter la validation du projet
    alert(`Validation du projet ID: ${projectId}`);
}

/**
 * Télécharger un projet
 * @param {number} projectId - ID du projet à télécharger
 */
function downloadProject(projectId) {
    // TODO: Implémenter le téléchargement du projet
    window.open(`/evc/app/admin/projects/${projectId}/download`, '_blank');
}
</script>

<style>
.avatar-lg {
    width: 80px;
    height: 80px;
    font-size: 24px;
    font-weight: bold;
}

.info-group {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.info-item {
    display: flex;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.info-item:last-child {
    border-bottom: none;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}

.progress {
    background-color: #e9ecef;
}

.card-header.bg-primary {
    border-radius: 0.375rem 0.375rem 0 0 !important;
}
</style>
@endpush

<!-- L'ancienne modal a été supprimée - Le module ProjectViewer Senior crée sa propre modal dynamiquement -->

@endsection
