@extends('layouts.admin')

@section('title', 'Projets attribués - Design Graphique')

@section('content')
<style>
    :root {
        --dg-start: #1e3c72;
        --dg-end: #2a5298;
        --cm-start: #ff9800;
        --cm-end: #fb8c00;
        --dgc-start: #2a5298;
        --dgc-end: #ff9800;
        --zone-done-start: #22c55e;
        --zone-done-end: #16a34a;
        --zone-todo-start: #ef4444;
        --zone-todo-end: #b91c1c;
    }

    .assigned-page .top-actions .btn {
        border-radius: 14px;
        font-weight: 700;
        padding: 0.6rem 0.9rem;
    }

    .assigned-card {
        background: rgba(15, 23, 42, 0.65);
        border: 1px solid rgba(148, 163, 184, 0.18);
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 18px 60px rgba(0, 0, 0, 0.35);
    }

    .assigned-card .card-header {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(30, 41, 59, 0.75));
        border-bottom: 1px solid rgba(148, 163, 184, 0.16);
        color: white;
        padding: 1rem 1.25rem;
    }

    .assigned-card.zone-done .card-header {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.95), rgba(22, 163, 74, 0.85));
        border-bottom-color: rgba(34, 197, 94, 0.25);
    }

    .assigned-card.zone-todo .card-header {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.95), rgba(185, 28, 28, 0.85));
        border-bottom-color: rgba(239, 68, 68, 0.25);
    }

    .assigned-card .card-body {
        padding: 1.25rem;
    }

    .assigned-accordion .accordion-item {
        border: 1px solid rgba(148, 163, 184, 0.14);
        border-radius: 16px;
        overflow: hidden;
        background: rgba(2, 6, 23, 0.35);
        margin-bottom: 12px;
    }

    .assigned-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .assigned-card-tile {
        background: rgba(15, 23, 42, 0.88);
        border: 1px solid rgba(148, 163, 184, 0.16);
        border-radius: 16px;
        padding: 14px;
        color: rgba(255, 255, 255, 0.92);
        text-decoration: none;
        cursor: pointer;
        transition: transform 0.15s ease, background 0.15s ease, border-color 0.15s ease;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .assigned-card-tile.formation-dg {
        border-color: rgba(42, 82, 152, 0.45);
        background: linear-gradient(135deg, rgba(30, 60, 114, 0.55), rgba(15, 23, 42, 0.90));
    }

    .assigned-card-tile.formation-cm {
        border-color: rgba(251, 140, 0, 0.45);
        background: linear-gradient(135deg, rgba(251, 140, 0, 0.35), rgba(15, 23, 42, 0.90));
    }

    .assigned-card-tile.formation-dgcm {
        border-color: rgba(255, 152, 0, 0.35);
        background: linear-gradient(135deg, rgba(42, 82, 152, 0.55), rgba(255, 152, 0, 0.28));
    }

    .assigned-card-tile:hover {
        transform: translateY(-1px);
        border-color: rgba(148, 163, 184, 0.28);
        background: rgba(15, 23, 42, 0.95);
    }

    .assigned-card-tile.active {
        border-color: rgba(42, 82, 152, 0.55);
        box-shadow: 0 0 0 1px rgba(42, 82, 152, 0.28) inset;
    }

    .assigned-tile-top {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .assigned-tile-title {
        font-weight: 900;
        letter-spacing: -0.2px;
        line-height: 1.2;
    }

    .assigned-tile-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: auto;
    }

    .assigned-chevron {
        margin-left: auto;
        opacity: 0.85;
    }

    .assigned-panel {
        border: 1px solid rgba(148, 163, 184, 0.14);
        border-radius: 16px;
        background: rgba(2, 6, 23, 0.25);
        padding: 14px;
        margin-top: 14px;
    }

    .projects-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .students-panel {
        border: 1px solid rgba(148, 163, 184, 0.14);
        border-radius: 14px;
        background: rgba(30, 41, 59, 0.22);
        padding: 12px;
        margin-top: 12px;
    }

    .assigned-accordion .accordion-header {
        margin: 0;
    }

    .assigned-accordion .accordion-button {
        background: rgba(15, 23, 42, 0.92);
        color: rgba(255, 255, 255, 0.92);
        border: none;
        box-shadow: none;
        padding: 1rem 1rem;
        font-weight: 800;
        letter-spacing: -0.2px;
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .assigned-accordion .accordion-button:focus {
        box-shadow: none;
    }

    .assigned-accordion .accordion-button:not(.collapsed) {
        background: rgba(15, 23, 42, 0.98);
    }

    .assigned-accordion .accordion-button::after {
        filter: invert(1);
        opacity: 0.85;
    }

    .assigned-accordion .accordion-body {
        background: rgba(30, 41, 59, 0.35);
        padding: 1rem;
    }

    .assigned-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: rgba(255, 255, 255, 0.9);
        flex: 0 0 auto;
    }

    .assigned-pill.formation-dg {
        background: linear-gradient(135deg, var(--dg-start), var(--dg-end));
        border-color: rgba(42, 82, 152, 0.35);
    }

    .assigned-pill.formation-cm {
        background: linear-gradient(135deg, var(--cm-start), var(--cm-end));
        border-color: rgba(251, 140, 0, 0.35);
    }

    .assigned-pill.formation-dgcm {
        background: linear-gradient(135deg, var(--dgc-start), var(--dgc-end));
        border-color: rgba(255, 152, 0, 0.25);
    }

    .assigned-badges {
        margin-left: auto;
        display: inline-flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .assigned-badge {
        border-radius: 999px;
        padding: 0.25rem 0.55rem;
        font-weight: 800;
        font-size: 0.78rem;
        letter-spacing: 0.02em;
    }

    .assigned-badge.soft {
        background: rgba(148, 163, 184, 0.16);
        color: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(148, 163, 184, 0.20);
    }

    .assigned-badge.primary {
        background: linear-gradient(135deg, rgba(30, 60, 114, 0.9), rgba(42, 82, 152, 0.9));
        border: 1px solid rgba(42, 82, 152, 0.35);
        color: white;
    }

    .assigned-students .list-group-item {
        background: rgba(15, 23, 42, 0.88);
        border: 1px solid rgba(148, 163, 184, 0.14);
        border-radius: 14px;
        color: rgba(255, 255, 255, 0.92);
        padding: 0.85rem 0.9rem;
        margin-bottom: 10px;
    }

    .assigned-students .student-avatar {
        width: 36px;
        height: 36px;
        border-radius: 999px;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.16);
    }

    .assigned-students .student-avatar-fallback {
        width: 36px;
        height: 36px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(30, 60, 114, 0.9), rgba(255, 138, 0, 0.6));
        font-weight: 900;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.95);
    }

    @media (max-width: 768px) {
        .assigned-card .card-body {
            padding: 1rem;
        }

        .assigned-accordion .accordion-button {
            padding: 0.9rem;
        }

        .assigned-grid {
            grid-template-columns: 1fr;
        }

        .projects-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-fluid py-4 assigned-page">
    <div class="d-flex justify-content-between align-items-center mb-4 top-actions">
        <div></div>
        <div>
            <a href="{{ route('admin.projets.design-graphique.to-send') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-paper-plane me-2"></i>Attribuer un projet
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border-radius: 16px; padding: 1.5rem; color: white; display: flex; align-items: center; gap: 1rem;">
                <div class="stat-icon" style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-tasks"></i>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 2.2rem; font-weight: 700; margin: 0;">{{ $stats['total'] ?? 0 }}</h3>
                    <p style="margin: 0; opacity: 0.9; font-size: 0.95rem;">Total</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 16px; padding: 1.5rem; color: white; display: flex; align-items: center; gap: 1rem;">
                <div class="stat-icon" style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-clock"></i>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 2.2rem; font-weight: 700; margin: 0;">{{ $stats['en_cours'] ?? 0 }}</h3>
                    <p style="margin: 0; opacity: 0.9; font-size: 0.95rem;">Pas encore Fait</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border-radius: 16px; padding: 1.5rem; color: white; display: flex; align-items: center; gap: 1rem;">
                <div class="stat-icon" style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-flag-checkered"></i>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 2.2rem; font-weight: 700; margin: 0;">{{ $stats['termine'] ?? 0 }}</h3>
                    <p style="margin: 0; opacity: 0.9; font-size: 0.95rem;">Terminés</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 16px; padding: 1.5rem; color: white; display: flex; align-items: center; gap: 1rem;">
                <div class="stat-icon" style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 2.2rem; font-weight: 700; margin: 0;">{{ $stats['valide'] ?? 0 }}</h3>
                    <p style="margin: 0; opacity: 0.9; font-size: 0.95rem;">Validés</p>
                </div>
            </div>
        </div>
    </div>

    @php
        $formations = [
            'Design Graphique',
            'Community Management',
            'Design Graphique et Community Management',
        ];
    @endphp

    <div class="card assigned-card zone-done mb-4">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    <h5 class="mb-0">Déjà fait</h5>
                </div>
                <div class="text-white-50 small">Clique une formation, puis un projet</div>
            </div>
        </div>
        <div class="card-body">
            <div class="assigned-grid">
                @foreach($formations as $formation)
                    @php
                        $projects = $groupedAssignmentsDone[$formation] ?? collect();
                        $formationId = 'done_formation_' . md5($formation);
                        $formationTheme = $formation === 'Design Graphique'
                            ? 'formation-dg'
                            : ($formation === 'Community Management'
                                ? 'formation-cm'
                                : 'formation-dgcm');
                    @endphp

                    <button class="assigned-card-tile {{ $formationTheme }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $formationId }}" aria-expanded="false" aria-controls="collapse_{{ $formationId }}">
                        <div class="assigned-tile-top">
                            <span class="assigned-pill {{ $formationTheme }}"><i class="fas fa-graduation-cap"></i></span>
                            <div class="assigned-tile-title">{{ $formation }}</div>
                            <span class="assigned-chevron"><i class="fas fa-chevron-down"></i></span>
                        </div>
                        <div class="assigned-tile-meta">
                            <span class="assigned-badge soft">{{ $projects->count() }} projet(s)</span>
                            <span class="text-white-50 small">Voir les projets terminés</span>
                        </div>
                    </button>
                @endforeach
            </div>

            @foreach($formations as $formation)
                @php
                    $projects = $groupedAssignmentsDone[$formation] ?? collect();
                    $formationId = 'done_formation_' . md5($formation);
                    $formationTheme = $formation === 'Design Graphique'
                        ? 'formation-dg'
                        : ($formation === 'Community Management'
                            ? 'formation-cm'
                            : 'formation-dgcm');
                @endphp

                <div class="collapse" id="collapse_{{ $formationId }}">
                    <div class="assigned-panel">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                            <div class="text-white fw-bold">
                                <span class="assigned-pill {{ $formationTheme }} me-2" style="width: 32px; height: 32px;"><i class="fas fa-graduation-cap"></i></span>{{ $formation }}
                            </div>
                            <span class="assigned-badge soft">{{ $projects->count() }} projet(s)</span>
                        </div>

                        @if($projects->isEmpty())
                            <div class="text-center py-3 text-muted">Aucun projet attribué pour cette formation.</div>
                        @else
                            <div class="projects-grid">
                                @foreach($projects as $index => $project)
                                    @php
                                        $projectId = $formationId . '_project_' . $index;
                                        $students = collect($project['students'] ?? []);
                                    @endphp

                                    <div>
                                        <div class="assigned-card-tile" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $projectId }}" aria-expanded="false" aria-controls="collapse_{{ $projectId }}" role="button" tabindex="0">
                                            <div class="assigned-tile-top">
                                                <span class="assigned-pill"><i class="fas fa-tasks"></i></span>
                                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                                    <div class="assigned-tile-title">{{ $project['title'] ?? 'Projet' }}</div>
                                                    @php($representativeId = $project['representative_id'] ?? ($students->first()->id ?? null))
                                                    @if($representativeId)
                                                        <a href="{{ route('admin.projects.view', $representativeId) }}" class="btn btn-sm btn-outline-info" onclick="event.stopPropagation();">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.projects.edit', $representativeId) }}?bulk=1" class="btn btn-sm btn-outline-warning" onclick="event.stopPropagation();">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                        <form action="{{ route('admin.projects.delete', $representativeId) }}?bulk=1" method="POST" class="d-inline" onsubmit="event.stopPropagation(); return confirm('Supprimer ce projet pour tous les étudiants ?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                                <span class="assigned-chevron"><i class="fas fa-chevron-down"></i></span>
                                            </div>
                                            <div class="assigned-tile-meta">
                                                <span class="assigned-badge primary">{{ $students->count() }} étudiant(s)</span>
                                                <span class="text-white-50 small">Voir la liste</span>
                                            </div>
                                        </div>

                                        <div class="collapse" id="collapse_{{ $projectId }}">
                                            <div class="students-panel">
                                                <div class="list-group assigned-students">
                                                    @foreach($students as $studentWork)
                                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                                            <div class="d-flex align-items-center gap-3">
                                                                @php
                                                                    $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($studentWork->profile_photo ?? null);
                                                                @endphp
                                                                @if($photoUrl)
                                                                    <img src="{{ $photoUrl }}" alt="{{ $studentWork->first_name }}" class="student-avatar">
                                                                @else
                                                                    <div class="student-avatar-fallback">
                                                                        {{ strtoupper(substr($studentWork->first_name ?? 'E', 0, 1)) }}{{ strtoupper(substr($studentWork->last_name ?? '', 0, 1)) }}
                                                                    </div>
                                                                @endif
                                                                <div>
                                                                    <div class="fw-bold">{{ $studentWork->first_name }} {{ $studentWork->last_name }}</div>
                                                                    <div class="text-white-50 small">{{ $studentWork->student_email }}</div>
                                                                </div>
                                                            </div>
                                                            <a href="{{ route('admin.projects.view', $studentWork->id) }}" class="btn btn-sm btn-outline-info">
                                                                <i class="fas fa-eye me-1"></i>Voir
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card assigned-card zone-todo">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-clock"></i>
                    <h5 class="mb-0">Pas encore fait</h5>
                </div>
                <div class="text-white-50 small">Clique une formation, puis un projet</div>
            </div>
        </div>
        <div class="card-body">
            <div class="assigned-grid">
                @foreach($formations as $formation)
                    @php
                        $projects = $groupedAssignmentsTodo[$formation] ?? collect();
                        $formationId = 'todo_formation_' . md5($formation);
                        $formationTheme = $formation === 'Design Graphique'
                            ? 'formation-dg'
                            : ($formation === 'Community Management'
                                ? 'formation-cm'
                                : 'formation-dgcm');
                    @endphp

                    <button class="assigned-card-tile {{ $formationTheme }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $formationId }}" aria-expanded="false" aria-controls="collapse_{{ $formationId }}">
                        <div class="assigned-tile-top">
                            <span class="assigned-pill {{ $formationTheme }}"><i class="fas fa-graduation-cap"></i></span>
                            <div class="assigned-tile-title">{{ $formation }}</div>
                            <span class="assigned-chevron"><i class="fas fa-chevron-down"></i></span>
                        </div>
                        <div class="assigned-tile-meta">
                            <span class="assigned-badge soft">{{ $projects->count() }} projet(s)</span>
                            <span class="text-white-50 small">Voir les projets à faire</span>
                        </div>
                    </button>
                @endforeach
            </div>

            @foreach($formations as $formation)
                @php
                    $projects = $groupedAssignmentsTodo[$formation] ?? collect();
                    $formationId = 'todo_formation_' . md5($formation);
                    $formationTheme = $formation === 'Design Graphique'
                        ? 'formation-dg'
                        : ($formation === 'Community Management'
                            ? 'formation-cm'
                            : 'formation-dgcm');
                @endphp

                <div class="collapse" id="collapse_{{ $formationId }}">
                    <div class="assigned-panel">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                            <div class="text-white fw-bold">
                                <span class="assigned-pill {{ $formationTheme }} me-2" style="width: 32px; height: 32px;"><i class="fas fa-graduation-cap"></i></span>{{ $formation }}
                            </div>
                            <span class="assigned-badge soft">{{ $projects->count() }} projet(s)</span>
                        </div>

                        @if($projects->isEmpty())
                            <div class="text-center py-3 text-muted">Aucun projet attribué pour cette formation.</div>
                        @else
                            <div class="projects-grid">
                                @foreach($projects as $index => $project)
                                    @php
                                        $projectId = $formationId . '_project_' . $index;
                                        $students = collect($project['students'] ?? []);
                                    @endphp

                                    <div>
                                        <div class="assigned-card-tile" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $projectId }}" aria-expanded="false" aria-controls="collapse_{{ $projectId }}" role="button" tabindex="0">
                                            <div class="assigned-tile-top">
                                                <span class="assigned-pill"><i class="fas fa-tasks"></i></span>
                                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                                    <div class="assigned-tile-title">{{ $project['title'] ?? 'Projet' }}</div>
                                                    @php($representativeId = $project['representative_id'] ?? ($students->first()->id ?? null))
                                                    @if($representativeId)
                                                        <a href="{{ route('admin.projects.view', $representativeId) }}" class="btn btn-sm btn-outline-info" onclick="event.stopPropagation();">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.projects.edit', $representativeId) }}?bulk=1" class="btn btn-sm btn-outline-warning" onclick="event.stopPropagation();">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                        <form action="{{ route('admin.projects.delete', $representativeId) }}?bulk=1" method="POST" class="d-inline" onsubmit="event.stopPropagation(); return confirm('Supprimer ce projet pour tous les étudiants ?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                                <span class="assigned-chevron"><i class="fas fa-chevron-down"></i></span>
                                            </div>
                                            <div class="assigned-tile-meta">
                                                <span class="assigned-badge primary">{{ $students->count() }} étudiant(s)</span>
                                                <span class="text-white-50 small">Voir la liste</span>
                                            </div>
                                        </div>

                                        <div class="collapse" id="collapse_{{ $projectId }}">
                                            <div class="students-panel">
                                                <div class="list-group assigned-students">
                                                    @foreach($students as $studentWork)
                                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                                            <div class="d-flex align-items-center gap-3">
                                                                @php
                                                                    $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($studentWork->profile_photo ?? null);
                                                                @endphp
                                                                @if($photoUrl)
                                                                    <img src="{{ $photoUrl }}" alt="{{ $studentWork->first_name }}" class="student-avatar">
                                                                @else
                                                                    <div class="student-avatar-fallback">
                                                                        {{ strtoupper(substr($studentWork->first_name ?? 'E', 0, 1)) }}{{ strtoupper(substr($studentWork->last_name ?? '', 0, 1)) }}
                                                                    </div>
                                                                @endif
                                                                <div>
                                                                    <div class="fw-bold">{{ $studentWork->first_name }} {{ $studentWork->last_name }}</div>
                                                                    <div class="text-white-50 small">{{ $studentWork->student_email }}</div>
                                                                </div>
                                                            </div>
                                                            <a href="{{ route('admin.projects.view', $studentWork->id) }}" class="btn btn-sm btn-outline-info">
                                                                <i class="fas fa-eye me-1"></i>Voir
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
