@extends('layouts.admin')

@section('title', 'Projets attribués - Design Graphique')

@section('content')
<style>
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

    <div class="card assigned-card">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-layer-group"></i>
                    <h5 class="mb-0">Travaux attribués</h5>
                </div>
                <div class="text-white-50 small">Clique une formation, puis un projet</div>
            </div>
        </div>
        <div class="card-body">
            @php
                $formations = [
                    'Design Graphique',
                    'Community Management',
                    'Design Graphique et Community Management',
                ];
            @endphp

            <div class="accordion assigned-accordion" id="formationsAccordion">
                @foreach($formations as $formation)
                    @php
                        $projects = $groupedAssignments[$formation] ?? collect();
                        $formationId = 'formation_' . md5($formation);
                    @endphp

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading_{{ $formationId }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $formationId }}" aria-expanded="false" aria-controls="collapse_{{ $formationId }}">
                                <span class="assigned-pill"><i class="fas fa-graduation-cap"></i></span>
                                <span>{{ $formation }}</span>
                                <span class="assigned-badges">
                                    <span class="assigned-badge soft">{{ $projects->count() }} projet(s)</span>
                                </span>
                            </button>
                        </h2>
                        <div id="collapse_{{ $formationId }}" class="accordion-collapse collapse" aria-labelledby="heading_{{ $formationId }}" data-bs-parent="#formationsAccordion">
                            <div class="accordion-body">
                                @if($projects->isEmpty())
                                    <div class="text-center py-3 text-muted">Aucun projet attribué pour cette formation.</div>
                                @else
                                    <div class="accordion assigned-accordion" id="projectsAccordion_{{ $formationId }}">
                                        @foreach($projects as $index => $project)
                                            @php
                                                $projectId = $formationId . '_project_' . $index;
                                                $students = collect($project['students'] ?? []);
                                            @endphp

                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="heading_{{ $projectId }}">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $projectId }}" aria-expanded="false" aria-controls="collapse_{{ $projectId }}">
                                                        <span class="assigned-pill"><i class="fas fa-tasks"></i></span>
                                                        <span>{{ $project['title'] ?? 'Projet' }}</span>
                                                        <span class="assigned-badges">
                                                            <span class="assigned-badge primary">{{ $students->count() }} étudiant(s)</span>
                                                        </span>
                                                    </button>
                                                </h2>
                                                <div id="collapse_{{ $projectId }}" class="accordion-collapse collapse" aria-labelledby="heading_{{ $projectId }}" data-bs-parent="#projectsAccordion_{{ $formationId }}">
                                                    <div class="accordion-body">
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
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
