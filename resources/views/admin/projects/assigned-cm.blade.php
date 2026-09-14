@extends('layouts.admin')

@section('title', 'Projets Community Management - Assignés')

@push('styles')
<style>
    .stat-card {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow:0 10px 30px rgba(30, 60, 114, 0.3);
    }

    .stat-card-primary {
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
    }

    .stat-card-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .stat-card-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .stat-card-info {
        background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%);
    }

    .stat-card-danger {
        background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        line-height: 1;
    }

    .stat-label {
        margin: 0;
        opacity: 0.9;
        font-size: 0.95rem;
    }

    .status-badge {
        display: inline-block;
        padding: .35em .65em;
        font-size: .75em;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25rem;
    }
    .status-badge.en_cours { background-color: #0dcaf0; color: #000; }
    .status-badge.termine { background-color: #ffc107; color: #000; }
    .status-badge.valide { background-color: #198754; }
    .status-badge.rejete { background-color: #dc3545; }
    th, td { vertical-align: middle; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.projets.design-graphique.assigned') }}" class="btn btn-outline-secondary mb-2">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            <h1 class="h3 mb-0 text-white">
                <i class="fas fa-users me-2" style="color: #f5576c;"></i>
                Community Management
            </h1>
            <p class="text-white-50 mb-0">Projets assignés aux étudiants</p>
        </div>
        <div>
            <a href="{{ route('admin.projets.design-graphique.to-send') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Attribuer un projet
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['total'] ?? 0 }}</h3>
                    <p class="stat-label">Total Projets</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-info">
                <div class="stat-icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['en_cours'] ?? 0 }}</h3>
                    <p class="stat-label">En Cours</p>
                </div>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['termine'] ?? 0 }}</h3>
                    <p class="stat-label">Terminés</p>
                </div>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-thumbs-up"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['valide'] ?? 0 }}</h3>
                    <p class="stat-label">Validés</p>
                </div>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="stat-card stat-card-danger">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['rejete'] ?? 0 }}</h3>
                    <p class="stat-label">Rejetés</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Table -->
    @php
        $projects = $groupedAssignments['Community Management'] ?? collect();
    @endphp

    @if($projects->isNotEmpty())
        <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-graduation-cap me-2"></i>Community Management
                </h5>
                <span class="badge bg-primary">{{ $projects->count() }} projet(s)</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Projet</th>
                                <th>Deadline</th>
                                <th>Statut</th>
                                <th>Étudiants</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                                @php
                                    $students = collect($project['students'] ?? []);
                                    $representativeId = $project['representative_id'] ?? ($students->first() ? $students->first()->id : null);
                                    $statusClass = 'status-' . ($project['status'] ?? 'en_cours');
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $project['title'] ?? 'Projet' }}</strong>
                                    </td>
                                    <td>
                                        @if($project['deadline'])
                                            {{ \Carbon\Carbon::parse($project['deadline'])->format('d/m/Y') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $statusClass }}">
                                            {{ $project['status'] ?? 'en_cours' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($students->take(5) as $studentWork)
                                                <span class="badge bg-info">
                                                    {{ $studentWork->first_name }} {{ $studentWork->last_name }}
                                                </span>
                                            @endforeach
                                            @if($students->count() > 5)
                                                <span class="badge bg-secondary">+{{ $students->count() - 5 }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($representativeId)
                                            <a href="{{ route('admin.projects.view', $representativeId) }}" class="btn btn-sm btn-info">Voir</a>
                                            <a href="{{ route('admin.projects.edit', $representativeId) }}?bulk=1" class="btn btn-sm btn-warning">Modifier</a>
                                            <form action="{{ route('admin.projects.delete', $representativeId) }}?bulk=1" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce projet pour tous les étudiants ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
            <div class="card-body text-center py-5">
                <i class="fas fa-folder-open fa-3x mb-3 text-white-50"></i>
                <div class="text-white-50">Aucun projet assigné</div>
                <p class="text-white-50">Commencez par attribuer un projet aux étudiants</p>
            </div>
        </div>
    @endif
</div>
@endsection
