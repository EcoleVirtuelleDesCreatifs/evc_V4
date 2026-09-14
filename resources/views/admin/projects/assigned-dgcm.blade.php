@extends('layouts.admin')

@section('title', 'Projets DG + Community Management - Assignés')

@section('content')
<style>
    .assigned-page {
        background: #f8f9fa;
    }

    .formation-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .formation-header {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .project-table {
        width: 100%;
        border-collapse: collapse;
    }

    .project-table th {
        background: #f1f5f9;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #475569;
        border-bottom: 2px solid #e2e8f0;
    }

    .project-table td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .project-table tr:hover {
        background: #f8fafc;
    }

    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-en_cours {
        background: #fef3c7;
        color: #92400e;
    }

    .status-termine {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-valide {
        background: #dcfce7;
        color: #166534;
    }

    .status-rejete {
        background: #fee2e2;
        color: #991b1b;
    }

    .student-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .student-chip {
        background: #e2e8f0;
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        font-size: 0.85rem;
        color: #475569;
    }

    .btn-action {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        margin-right: 0.5rem;
    }

    .btn-view {
        background: #3b82f6;
        color: white;
    }

    .btn-edit {
        background: #f59e0b;
        color: white;
    }

    .btn-delete {
        background: #ef4444;
        color: white;
    }

    .stats-row {
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        text-align: center;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1e293b;
    }

    .stat-label {
        color: #64748b;
        font-size: 0.9rem;
        font-weight: 600;
    }
</style>

<div class="container-fluid py-4 assigned-page">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.projets.design-graphique.assigned') }}" class="btn btn-outline-secondary mb-2">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            <h1 class="mb-1" style="font-weight: 700; color: #1e293b;">
                <i class="fas fa-layer-group me-2" style="color: #4facfe;"></i>
                DG + Community Management
            </h1>
            <p class="text-muted mb-0">Projets assignés aux étudiants</p>
        </div>
        <div>
            <a href="{{ route('admin.projets.design-graphique.to-send') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Attribuer un projet
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row stats-row">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total'] ?? 0 }}</div>
                <div class="stat-label">Total Projets</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['en_cours'] ?? 0 }}</div>
                <div class="stat-label">En Cours</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['termine'] ?? 0 }}</div>
                <div class="stat-label">Terminés</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['valide'] ?? 0 }}</div>
                <div class="stat-label">Validés</div>
            </div>
        </div>
    </div>

    <!-- Projects Table -->
    @php
        $projects = $groupedAssignments['Design Graphique et Community Management'] ?? collect();
    @endphp

    @if($projects->isNotEmpty())
        <div class="formation-section">
            <div class="formation-header">
                <div>
                    <h4 class="mb-0" style="font-weight: 700;">
                        <i class="fas fa-graduation-cap me-2"></i>Design Graphique et Community Management
                    </h4>
                    <small>{{ $projects->count() }} projet(s)</small>
                </div>
                <div>
                    <span class="badge bg-white text-dark">{{ $projects->flatten()->pluck('students')->flatten()->count() }} étudiant(s)</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="project-table">
                    <thead>
                        <tr>
                            <th>Projet</th>
                            <th>Catégorie</th>
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
                                    {{ $project['category'] ?? '—' }}
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
                                    <div class="student-list">
                                        @foreach($students->take(5) as $studentWork)
                                            <span class="student-chip">
                                                {{ $studentWork->first_name }} {{ $studentWork->last_name }}
                                            </span>
                                        @endforeach
                                        @if($students->count() > 5)
                                            <span class="student-chip">+{{ $students->count() - 5 }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($representativeId)
                                        <a href="{{ route('admin.projects.view', $representativeId) }}" class="btn-action btn-view">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.projects.edit', $representativeId) }}?bulk=1" class="btn-action btn-edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <form action="{{ route('admin.projects.delete', $representativeId) }}?bulk=1" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce projet pour tous les étudiants ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <div style="width: 120px; height: 120px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i class="fas fa-inbox" style="font-size: 3rem; color: #94a3b8;"></i>
            </div>
            <h4 class="mb-2" style="color: #1e293b; font-weight: 700;">Aucun projet assigné</h4>
            <p class="text-muted">Commencez par attribuer un projet aux étudiants</p>
        </div>
    @endif
</div>
@endsection
