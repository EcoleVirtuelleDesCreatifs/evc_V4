@extends('layouts.ki-admin')

@section('title', 'Tous mes TP Réalisés')
@section('page-title', 'Tous mes TP Réalisés')

@section('content')
@php
    // Utiliser formationSlug si disponible, sinon détection automatique depuis l'URL
    if (!isset($formationSlug)) {
        $currentModule = request()->segment(3); // design-graphique, community-management, etc.
        $formationSlug = $currentModule;
    }
    $routePrefix = $formationSlug;
@endphp

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    }

    .modern-table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .modern-table thead th {
        background: linear-gradient(135deg, #343a40 0%, #495057 100%);
        border: none;
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        padding: 1rem 0.75rem;
        position: relative;
    }

    .modern-table thead th:first-child {
        border-top-left-radius: 0.5rem;
    }

    .modern-table thead th:last-child {
        border-top-right-radius: 0.5rem;
    }

    .modern-table tbody tr {
        transition: all 0.3s ease;
        border: none;
    }

    .modern-table tbody tr:hover {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .modern-table tbody td {
        border: none;
        padding: 1.25rem 0.75rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f4;
    }

    .number-badge {
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9rem;
        margin: 0 auto;
        box-shadow: 0 2px 8px rgba(0,123,255,0.3);
    }

    .project-thumbnail img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .project-thumbnail:hover img {
        transform: scale(1.1);
    }

    .thumbnail-placeholder {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 1.5rem;
    }

    .category-badge {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-block;
        box-shadow: 0 2px 8px rgba(23,162,184,0.3);
        transition: all 0.3s ease;
    }

    .category-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(23,162,184,0.4);
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .status-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(40,167,69,0.3);
    }

    .status-warning {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        color: #212529;
        box-shadow: 0 2px 8px rgba(255,193,7,0.3);
    }

    .status-badge:hover {
        transform: translateY(-2px);
    }

    .file-stats {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        align-items: center;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(248,249,250,0.8);
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.3s ease;
        min-width: 60px;
        justify-content: center;
    }

    .stat-item:hover {
        background: rgba(233,236,239,1);
        transform: scale(1.05);
    }

    .date-info {
        text-align: center;
    }

    .date-info .fw-bold {
        font-size: 1.1rem;
        color: #495057;
    }

    .empty-state {
        padding: 3rem 2rem;
    }

    .empty-state i {
        opacity: 0.6;
        margin-bottom: 1.5rem;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .project-row {
        animation: fadeInUp 0.5s ease forwards;
    }

    .project-row:nth-child(1) { animation-delay: 0.1s; }
    .project-row:nth-child(2) { animation-delay: 0.2s; }
    .project-row:nth-child(3) { animation-delay: 0.3s; }
    .project-row:nth-child(4) { animation-delay: 0.4s; }
    .project-row:nth-child(5) { animation-delay: 0.5s; }

    /* Boutons d'action */
    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 0.25rem;
    }

    .action-btn {
        min-width: 35px;
        height: 35px;
        border-radius: 8px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .action-btn i {
        font-size: 0.9rem;
    }

    .btn-text {
        margin-left: 0.5rem;
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* Couleurs spécifiques pour les boutons */
    .btn-outline-info:hover {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        border-color: #17a2b8;
    }

    .btn-outline-warning:hover {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        border-color: #ffc107;
    }

    .btn-outline-danger:hover {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border-color: #dc3545;
    }

    /* Animation pour les projets validés */
    .action-btn.validated {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        border-color: #17a2b8;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .modern-table thead th {
            padding: 0.75rem 0.5rem;
            font-size: 0.75rem;
        }

        .modern-table tbody td {
            padding: 1rem 0.5rem;
        }

        .project-thumbnail img,
        .thumbnail-placeholder {
            width: 45px;
            height: 45px;
        }

        .number-badge {
            width: 30px;
            height: 30px;
            font-size: 0.8rem;
        }

        .file-stats {
            flex-direction: row;
            flex-wrap: wrap;
            gap: 0.125rem;
        }

        .stat-item {
            min-width: 45px;
            padding: 0.125rem 0.5rem;
            font-size: 0.75rem;
        }

        .action-buttons {
            flex-direction: column;
            gap: 0.125rem;
        }

        .action-btn {
            min-width: 30px;
            height: 30px;
            font-size: 0.8rem;
        }

        .btn-text {
            display: none;
        }

        .btn-group {
            flex-direction: column;
        }
    }
</style>

<div class="container-fluid">

    <!-- Header avec statistiques -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
                <div class="card-body text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="h3 mb-2">
                                <i class="fas fa-tasks me-2"></i>
                                Tous mes TP Réalisés
                            </h1>
                            <p class="mb-0 opacity-75">
                                <i class="fas fa-user me-2"></i>
                                {{ $userProfile->full_name ?? 'Utilisateur' }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et actions -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary active" data-filter="all">
                    <i class="fas fa-list me-1"></i> Tous
                </button>
                <button type="button" class="btn btn-outline-success" data-filter="valide">
                    <i class="fas fa-check me-1"></i> Validés
                </button>
                <button type="button" class="btn btn-outline-warning" data-filter="en_cours">
                    <i class="fas fa-clock me-1"></i> En cours
                </button>
            </div>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route($routePrefix . '.tp.ajouter') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Nouveau TP
            </a>
            <a href="{{ route($routePrefix . '.tp.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>

    <!-- Tableau moderne et attractif -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 overflow-hidden">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-th-list me-2"></i>
                            Tous mes TP
                        </h5>
                        <span class="badge bg-white text-primary px-3 py-2">
                            {{ isset($projects) ? count($projects) : 0 }} TP
                        </span>
                    </div>
                </div>

                @if(isset($projects) && count($projects) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 modern-table">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="text-center" width="5%">
                                        <i class="fas fa-hashtag"></i>
                                    </th>
                                    <th scope="col" width="30%">
                                        <i class="fas fa-project-diagram me-2"></i>TP
                                    </th>
                                    <th scope="col" width="15%">
                                        <i class="fas fa-graduation-cap me-2"></i>Formation
                                    </th>
                                    <th scope="col" class="text-center" width="12%">
                                        <i class="fas fa-check-circle me-2"></i>Statut
                                    </th>
                                    <th scope="col" class="text-center" width="10%">
                                        <i class="fas fa-files me-2"></i>Fichiers
                                    </th>
                                    <th scope="col" class="text-center" width="10%">
                                        <i class="fas fa-calendar me-2"></i>Date
                                    </th>
                                    <th scope="col" class="text-center" width="18%">
                                        <i class="fas fa-cogs me-2"></i>Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projects as $index => $project)
                                    <tr class="project-row align-middle" data-status="{{ $project->status }}">
                                        <!-- Numéro -->
                                        <td class="text-center">
                                            <div class="number-badge">
                                                {{ $index + 1 }}
                                            </div>
                                        </td>

                                        <!-- TP avec titre et description -->
                                        <td>
                                            <div>
                                                <h6 class="mb-1 fw-bold text-dark">{!! $project->title !!}</h6>
                                                @if($project->description)
                                                    <p class="mb-0 text-muted small">
                                                        {!! Str::limit(strip_tags($project->description), 60) !!}
                                                    </p>
                                                @endif
                                                @if($project->link)
                                                    <p class="mb-0 mt-1">
                                                        <a href="{{ $project->link }}" target="_blank" class="text-primary small">
                                                            <i class="fas fa-link me-1"></i>Voir le lien
                                                        </a>
                                                    </p>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Formation -->
                                        <td>
                                            @php
                                                $formationColor = 'secondary';
                                                $formationName = $project->formation ?? 'Design Graphique';
                                                if(stripos($formationName, 'design') !== false) {
                                                    $formationColor = 'info';
                                                } elseif(stripos($formationName, 'community') !== false) {
                                                    $formationColor = 'warning';
                                                }
                                            @endphp
                                            <span class="badge bg-{{ $formationColor }} text-white">
                                                {{ $formationName }}
                                            </span>
                                        </td>

                                        <!-- Statut -->
                                        <td class="text-center">
                                            @if($project->status == 'validated')
                                                <span class="status-badge status-success">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    Validé
                                                </span>
                                            @elseif($project->status == 'rejected')
                                                <span class="status-badge" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white;">
                                                    <i class="fas fa-times-circle me-1"></i>
                                                    Rejeté
                                                </span>
                                            @elseif($project->status == 'completed')
                                                <span class="status-badge" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white;">
                                                    <i class="fas fa-check me-1"></i>
                                                    Terminé
                                                </span>
                                            @elseif($project->status == 'in_progress')
                                                <span class="status-badge status-warning">
                                                    <i class="fas fa-spinner me-1"></i>
                                                    En cours
                                                </span>
                                            @else
                                                <span class="status-badge" style="background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%); color: white;">
                                                    <i class="fas fa-clock me-1"></i>
                                                    En attente
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Fichiers -->
                                        <td class="text-center">
                                            <div class="file-stats">
                                                <div class="stat-item">
                                                    <i class="fas fa-file text-primary"></i>
                                                    <span>{{ isset($project->files_count) ? $project->files_count : (isset($project->files) ? $project->files->count() : 0) }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Date -->
                                        <td class="text-center">
                                            <div class="date-info">
                                                <div class="fw-bold text-dark">
                                                    {{ \Carbon\Carbon::parse($project->created_at)->format('d/m') }}
                                                </div>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($project->created_at)->format('Y') }}
                                                </small>
                                            </div>
                                        </td>

                                        <!-- Actions -->
                                        <td class="text-center">
                                            <div class="action-buttons">
                                                @if(isset($project->source_table) && $project->source_table === 'tp_assignments')
                                                    <a class="btn btn-outline-info btn-sm action-btn" title="Voir le TP" href="{{ route($routePrefix . '.tp.voir', ['id' => $project->id, 'source' => 'tp_assignments']) }}">
                                                        <i class="fas fa-eye"></i>
                                                        <span class="btn-text">Voir</span>
                                                    </a>
                                                @else
                                                    @if($project->status == 'validated')
                                                        <!-- Projet validé : seulement voir -->
                                                        <a class="btn btn-outline-info btn-sm action-btn"
                                                                title="Voir le projet"
                                                                href="{{ route($routePrefix . '.tp.voir', ['id' => $project->id, 'source' => 'tp']) }}">
                                                            <i class="fas fa-eye"></i>
                                                            <span class="btn-text">Voir</span>
                                                        </a>
                                                    @else
                                                        <!-- Projet en cours : toutes les actions -->
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <a class="btn btn-outline-info btn-sm action-btn"
                                                                    title="Voir le projet"
                                                                    href="{{ route($routePrefix . '.tp.voir', ['id' => $project->id, 'source' => 'tp']) }}">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a class="btn btn-outline-warning btn-sm action-btn"
                                                                    title="Modifier le projet"
                                                                    href="{{ route($routePrefix . '.tp.modifier', $project->id) }}">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <button class="btn btn-outline-danger btn-sm action-btn"
                                                                    title="Supprimer le projet"
                                                                    onclick="deleteProject({{ $project->id }}, '{{ addslashes($project->title) }}')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- État vide -->
                    <div class="card-body text-center py-5">
                        <div class="empty-state">
                            <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted mb-3">Aucun projet trouvé</h4>
                            <p class="text-muted mb-4">Vous n'avez pas encore créé de projets TP.<br>Commencez dès maintenant !</p>
                            <a href="{{ route($routePrefix . '.tp.ajouter') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>Créer mon premier projet
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Pagination si nécessaire -->
    @if(count($projects) > 12)
        <div class="row mt-4">
            <div class="col-12 text-center">
                <button class="btn btn-outline-primary" id="load-more">
                    <i class="fas fa-chevron-down me-1"></i> Charger plus
                </button>
            </div>
        </div>
    @endif
</div>

<style>
:root {
    --primary-color: #003366;
    --secondary-color: #0066CC;
    --accent-color: #FF6633;
    --success-color: #28a745;
    --warning-color: #ffc107;
}

.project-card {
    transition: transform 0.2s ease;
}

.project-card:hover {
    transform: translateY(-5px);
}

.card {
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
}

.card:hover {
    box-shadow: 0 8px 25px rgba(0, 51, 102, 0.15) !important;
}

.btn-group .btn {
    border-radius: 6px;
}

.btn-group .btn:not(:last-child) {
    margin-right: 5px;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}

.object-fit-cover {
    object-fit: cover;
}

.project-card.hidden {
    display: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtres
    const filterButtons = document.querySelectorAll('[data-filter]');
    const projectRows = document.querySelectorAll('.project-row');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');

            // Mettre à jour les boutons actifs
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Filtrer les lignes
            projectRows.forEach(row => {
                if (filter === 'all') {
                    row.style.display = '';
                } else {
                    const rowStatus = row.getAttribute('data-status');
                    if (rowStatus === filter) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        });
    });

    // Animation d'entrée
    projectRows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';

        setTimeout(() => {
            row.style.transition = 'all 0.5s ease';
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Module de formation détecté depuis PHP
const routePrefix = '{{ $routePrefix }}';

// Fonctions pour les actions des projets
function viewProject(projectId) {
    // Redirection vers la page de visualisation du projet
    window.location.href = `/evc/compte/${routePrefix}/tp/voir/${projectId}`;
}

function editProject(projectId) {
    // Redirection vers la page d'édition du projet
    window.location.href = `/evc/compte/${routePrefix}/tp/modifier/${projectId}`;
}

function deleteProject(projectId, projectTitle) {
    // Confirmation avant suppression
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        html: `Vous êtes sur le point de supprimer le projet :<br><strong>"${projectTitle}"</strong><br><br>Cette action est irréversible.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash me-2"></i>Oui, supprimer',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Annuler',
        reverseButtons: true,
        customClass: {
            popup: 'swal-custom',
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-secondary'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Afficher un loader
            Swal.fire({
                title: 'Suppression en cours...',
                html: 'Veuillez patienter',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Envoyer la requête de suppression
            fetch(`/evc/compte/${routePrefix}/tp/supprimer/${projectId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Supprimé !',
                        text: 'Le projet a été supprimé avec succès.',
                        icon: 'success',
                        confirmButtonColor: '#28a745',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Recharger la page ou supprimer la ligne du tableau
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Erreur !',
                        text: data.message || 'Une erreur est survenue lors de la suppression.',
                        icon: 'error',
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                Swal.fire({
                    title: 'Erreur !',
                    text: 'Une erreur réseau est survenue.',
                    icon: 'error',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}

// Ajouter SweetAlert2 si pas déjà inclus
if (typeof Swal === 'undefined') {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
    document.head.appendChild(script);
}
</script>
@endsection
