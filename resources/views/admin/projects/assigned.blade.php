@extends('layouts.admin')

@section('title', 'Projets attribués - Design Graphique')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
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

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <h5 class="mb-0 text-white"><i class="fas fa-layer-group me-2"></i>Travaux attribués</h5>
        </div>
        <div class="card-body">
            @php
                $formations = [
                    'Design Graphique',
                    'Community Management',
                    'Design Graphique et Community Management',
                ];
            @endphp

            <div class="accordion mb-4" id="formationsAccordion">
                @foreach($formations as $formation)
                    @php
                        $projects = $groupedAssignments[$formation] ?? collect();
                        $formationId = 'formation_' . md5($formation);
                    @endphp

                    <div class="accordion-item" style="background-color: #0f172a; border: 1px solid #334155;">
                        <h2 class="accordion-header" id="heading_{{ $formationId }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $formationId }}" aria-expanded="false" aria-controls="collapse_{{ $formationId }}" style="background-color: #0f172a; color: white;">
                                <i class="fas fa-graduation-cap me-2"></i>
                                {{ $formation }}
                                <span class="badge bg-secondary ms-3">{{ $projects->count() }} projet(s)</span>
                            </button>
                        </h2>
                        <div id="collapse_{{ $formationId }}" class="accordion-collapse collapse" aria-labelledby="heading_{{ $formationId }}" data-bs-parent="#formationsAccordion">
                            <div class="accordion-body" style="background-color: #1e293b;">
                                @if($projects->isEmpty())
                                    <div class="text-center py-3 text-muted">Aucun projet attribué pour cette formation.</div>
                                @else
                                    <div class="accordion" id="projectsAccordion_{{ $formationId }}">
                                        @foreach($projects as $index => $project)
                                            @php
                                                $projectId = $formationId . '_project_' . $index;
                                                $students = collect($project['students'] ?? []);
                                            @endphp

                                            <div class="accordion-item" style="background-color: #0f172a; border: 1px solid #334155;">
                                                <h2 class="accordion-header" id="heading_{{ $projectId }}">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $projectId }}" aria-expanded="false" aria-controls="collapse_{{ $projectId }}" style="background-color: #0f172a; color: white;">
                                                        <i class="fas fa-tasks me-2"></i>
                                                        {{ $project['title'] ?? 'Projet' }}
                                                        <span class="badge bg-primary ms-2">{{ $students->count() }} étudiant(s)</span>
                                                    </button>
                                                </h2>
                                                <div id="collapse_{{ $projectId }}" class="accordion-collapse collapse" aria-labelledby="heading_{{ $projectId }}" data-bs-parent="#projectsAccordion_{{ $formationId }}">
                                                    <div class="accordion-body" style="background-color: #1e293b;">
                                                        <div class="list-group">
                                                            @foreach($students as $studentWork)
                                                                <div class="list-group-item d-flex justify-content-between align-items-center" style="background-color: #0f172a; border: 1px solid #334155; color: white;">
                                                                    <div>
                                                                        <div class="fw-bold">{{ $studentWork->first_name }} {{ $studentWork->last_name }}</div>
                                                                        <div class="text-white-50 small">{{ $studentWork->student_email }}</div>
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
