@extends('layouts.admin')

@section('title', 'Tous les Projets')

@push('styles')
<style>
    :root {
        --admin-blue-dark: #1e3c72;
        --admin-blue-light: #4fc3f7;
        --admin-blue-mid: #2a5298;
    }

    .page-header {
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-mid), var(--admin-blue-light));
        padding: 2.5rem 2rem;
        border-radius: 20px;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(30, 60, 114, 0.3);
        animation: fadeInDown 0.6s ease;
    }

    .page-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-header .icon-circle {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Cartes statistiques */
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        animation: fadeInUp 0.5s ease;
        animation-fill-mode: both;
    }

    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    .stat-card:nth-child(4) { animation-delay: 0.4s; }
    .stat-card:nth-child(5) { animation-delay: 0.5s; }

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

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(30, 60, 114, 0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .stat-card-primary .stat-icon {
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-mid));
    }

    .stat-card-info .stat-icon {
        background: linear-gradient(135deg, #36b9cc, #258391);
    }

    .stat-card-warning .stat-icon {
        background: linear-gradient(135deg, #f6c23e, #dda20a);
    }

    .stat-card-success .stat-icon {
        background: linear-gradient(135deg, #1cc88a, #13855c);
    }

    .stat-card-danger .stat-icon {
        background: linear-gradient(135deg, #e74a3b, #be2617);
    }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #1a202c;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #718096;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Liste des projets */
    .projets-list-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        animation: fadeInUp 0.6s ease 0.6s both;
    }

    .projets-list-header {
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-mid));
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
    }

    .projets-list-header h5 {
        font-weight: 700;
        margin: 0;
    }

    .badge-count {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .projets-list-body {
        padding: 2rem;
    }

    /* Table moderne */
    .modern-table {
        margin: 0;
    }

    .modern-table thead th {
        background: linear-gradient(135deg, rgba(30, 60, 114, 0.05), rgba(79, 195, 247, 0.05));
        border: none;
        padding: 1rem;
        font-weight: 600;
        color: var(--admin-blue-dark);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modern-table tbody tr {
        border-bottom: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .modern-table tbody tr:hover {
        background: linear-gradient(135deg, rgba(30, 60, 114, 0.03), rgba(79, 195, 247, 0.03));
        transform: translateX(5px);
    }

    .modern-table tbody td {
        padding: 1rem;
        vertical-align: middle;
    }

    .avatar-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 16px;
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-light));
        color: white;
        box-shadow: 0 4px 10px rgba(30, 60, 114, 0.2);
    }

    /* Badges de statut */
    .badge-status {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .badge-en-cours {
        background: linear-gradient(135deg, #36b9cc, #258391);
        color: white;
    }

    .badge-termine {
        background: linear-gradient(135deg, #f6c23e, #dda20a);
        color: white;
    }

    .badge-valide {
        background: linear-gradient(135deg, #1cc88a, #13855c);
        color: white;
    }

    .badge-rejete {
        background: linear-gradient(135deg, #e74a3b, #be2617);
        color: white;
    }

    /* Boutons d'action */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.875rem;
        text-decoration: none;
    }

    .btn-view {
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-mid));
        color: white;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 60, 114, 0.3);
        color: white;
    }

    /* État vide */
    .text-center.py-5 {
        padding: 4rem 2rem !important;
    }

    .text-center.py-5 i {
        color: #36b9cc;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="page-header">
        <div class="d-flex align-items-center gap-3">
            <div class="icon-circle">
                📁
            </div>
            <div>
                <h1 class="mb-0">Tous les Projets</h1>
                <p class="mb-0" style="opacity: 0.95;">Vue complète de tous les projets étudiants</p>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row g-4 mb-4">
        <div class="col-md col-sm-6">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['total'] }}</div>
                    <div class="stat-label">Total Projets</div>
                </div>
            </div>
        </div>

        <div class="col-md col-sm-6">
            <div class="stat-card stat-card-info">
                <div class="stat-icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['en_cours'] }}</div>
                    <div class="stat-label">En Cours</div>
                </div>
            </div>
        </div>

        <div class="col-md col-sm-6">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['termine'] }}</div>
                    <div class="stat-label">Terminés</div>
                </div>
            </div>
        </div>

        <div class="col-md col-sm-6">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-thumbs-up"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['valide'] }}</div>
                    <div class="stat-label">Validés</div>
                </div>
            </div>
        </div>

        <div class="col-md col-sm-6">
            <div class="stat-card stat-card-danger">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['rejete'] }}</div>
                    <div class="stat-label">Rejetés</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des projets -->
    <div class="projets-list-card">
        <div class="projets-list-header">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Liste Complète des Projets</h5>
            <span class="badge-count">{{ $projects->count() }} projet(s)</span>
        </div>
        <div class="projets-list-body">
            @if($projects->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h5>Aucun projet pour le moment</h5>
                    <p class="text-muted">Les projets apparaîtront ici une fois créés par les étudiants.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table modern-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user me-2"></i>Étudiant</th>
                                <th><i class="fas fa-file-alt me-2"></i>Titre du Projet</th>
                                <th><i class="fas fa-graduation-cap me-2"></i>Formation</th>
                                <th><i class="fas fa-tag me-2"></i>Catégorie</th>
                                <th><i class="fas fa-info-circle me-2"></i>Statut</th>
                                <th><i class="fas fa-images me-2"></i>Images</th>
                                <th><i class="fas fa-calendar me-2"></i>Date</th>
                                <th><i class="fas fa-cog me-2"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary text-white me-2">
                                                {{ substr($project->first_name ?? 'U', 0, 1) }}{{ substr($project->last_name ?? 'U', 0, 1) }}
                                            </div>
                                            <div>
                                                <strong>{{ $project->first_name ?? 'Inconnu' }} {{ $project->last_name ?? '' }}</strong><br>
                                                <small class="text-muted">{{ $project->student_email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $project->title }}</strong><br>
                                        <small class="text-muted">{{ Str::limit(strip_tags($project->description ?? ''), 50) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $project->formation ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <small>{{ $project->category ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        @if($project->status === 'en_cours')
                                            <span class="badge-status badge-en-cours">
                                                <i class="fas fa-spinner me-1"></i>En Cours
                                            </span>
                                        @elseif($project->status === 'termine')
                                            <span class="badge-status badge-termine">
                                                <i class="fas fa-check me-1"></i>Terminé
                                            </span>
                                        @elseif($project->status === 'valide')
                                            <span class="badge-status badge-valide">
                                                <i class="fas fa-thumbs-up me-1"></i>Validé
                                            </span>
                                        @elseif($project->status === 'rejete')
                                            <span class="badge-status badge-rejete">
                                                <i class="fas fa-times me-1"></i>Rejeté
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ $project->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <i class="fas fa-images me-1"></i>{{ $project->images_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ \Carbon\Carbon::parse($project->created_at)->format('d/m/Y') }}</small><br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($project->created_at)->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.projets.pending.show', $project->id) }}" class="btn-action btn-view" title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
