@extends('layouts.admin')

@section('title', 'Projets attribués - Design Graphique')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --dark-bg: #0f172a;
        --card-bg: rgba(30, 41, 59, 0.7);
        --border-color: rgba(148, 163, 184, 0.1);
    }

    .assigned-page {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        min-height: 100vh;
    }

    .stat-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 1.5rem;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: white;
    }

    .tab-btn {
        background: transparent;
        border: 2px solid var(--border-color);
        color: rgba(255, 255, 255, 0.7);
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .tab-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .tab-btn.active {
        background: var(--primary-gradient);
        border-color: transparent;
        color: white;
    }

    .project-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.25rem;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .project-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    }

    .student-chip {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.9);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-en_cours {
        background: rgba(245, 158, 11, 0.2);
        color: #fbbf24;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .status-termine {
        background: rgba(59, 130, 246, 0.2);
        color: #60a5fa;
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .status-valide {
        background: rgba(34, 197, 94, 0.2);
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .status-rejete {
        background: rgba(239, 68, 68, 0.2);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .filter-section {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.25rem;
        backdrop-filter: blur(10px);
    }

    .search-input {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 0.75rem 1rem;
        color: white;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(102, 126, 234, 0.5);
        outline: none;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .search-input::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }

    @media (max-width: 768px) {
        .stat-card {
            padding: 1rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }
    }
</style>

<div class="container-fluid py-5 assigned-page">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="text-white mb-2" style="font-weight: 800; font-size: 2rem;">
                <i class="fas fa-layer-group me-3" style="color: #667eea;"></i>
                Projets Design Graphique
            </h1>
            <p class="text-white-50">Gestion des projets assignés aux étudiants</p>
        </div>
        <div>
            <a href="{{ route('admin.projets.design-graphique.to-send') }}" class="btn btn-lg" style="background: var(--primary-gradient); border: none; border-radius: 12px; color: white; font-weight: 700; padding: 0.75rem 1.5rem;">
                <i class="fas fa-plus me-2"></i>Attribuer un projet
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-5">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: var(--primary-gradient);">
                        <i class="fas fa-database"></i>
                    </div>
                    <div>
                        <h3 class="text-white mb-0" style="font-size: 2rem; font-weight: 800;">{{ $stats['total'] ?? 0 }}</h3>
                        <p class="text-white-50 mb-0" style="font-size: 0.9rem;">Total Projets</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: var(--warning-gradient);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h3 class="text-white mb-0" style="font-size: 2rem; font-weight: 800;">{{ $stats['en_cours'] ?? 0 }}</h3>
                        <p class="text-white-50 mb-0" style="font-size: 0.9rem;">En Cours</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: var(--info-gradient);">
                        <i class="fas fa-flag-checkered"></i>
                    </div>
                    <div>
                        <h3 class="text-white mb-0" style="font-size: 2rem; font-weight: 800;">{{ $stats['termine'] ?? 0 }}</h3>
                        <p class="text-white-50 mb-0" style="font-size: 0.9rem;">Terminés</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: var(--success-gradient);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <h3 class="text-white mb-0" style="font-size: 2rem; font-weight: 800;">{{ $stats['valide'] ?? 0 }}</h3>
                        <p class="text-white-50 mb-0" style="font-size: 0.9rem;">Validés</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-section mb-4">
        <div class="row align-items-center">
            <div class="col-md-4 mb-3">
                <input type="text" class="form-control search-input" placeholder="Rechercher un projet ou un étudiant..." id="searchInput">
            </div>
            <div class="col-md-3 mb-3">
                <select class="form-select search-input" id="statusFilter">
                    <option value="">Tous les statuts</option>
                    <option value="en_cours">En Cours</option>
                    <option value="termine">Terminé</option>
                    <option value="valide">Validé</option>
                    <option value="rejete">Rejeté</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <select class="form-select search-input" id="formationFilter">
                    <option value="">Toutes les formations</option>
                    <option value="Design Graphique">Design Graphique</option>
                    <option value="Community Management">Community Management</option>
                    <option value="Design Graphique et Community Management">Design Graphique et Community Management</option>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <button class="btn w-100" style="background: var(--primary-gradient); border: none; border-radius: 12px; color: white; font-weight: 600;" onclick="resetFilters()">
                    <i class="fas fa-sync-alt me-2"></i>Réinitialiser
                </button>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="d-flex gap-2 mb-4 flex-wrap">
        <button class="tab-btn active" onclick="switchTab('all')" id="tab-all">
            <i class="fas fa-th-large me-2"></i>Tous
        </button>
        <button class="tab-btn" onclick="switchTab('todo')" id="tab-todo">
            <i class="fas fa-hourglass-half me-2"></i>À Faire
        </button>
        <button class="tab-btn" onclick="switchTab('done')" id="tab-done">
            <i class="fas fa-check-circle me-2"></i>Faits
        </button>
    </div>

    <!-- Projects List -->
    <div id="projectsContainer">
        @php
            $formations = [
                'Design Graphique',
                'Community Management',
                'Design Graphique et Community Management',
            ];
        @endphp

        @foreach($formations as $formation)
            @php
                $allProjects = $groupedAssignments[$formation] ?? collect();
                $doneProjects = $groupedAssignmentsDone[$formation] ?? collect();
                $todoProjects = $groupedAssignmentsTodo[$formation] ?? collect();
                $formationTheme = $formation === 'Design Graphique'
                    ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'
                    : ($formation === 'Community Management'
                        ? 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)'
                        : 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)');
            @endphp

            @if($allProjects->isNotEmpty())
                <div class="mb-5" data-formation="{{ $formation }}">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div style="width: 50px; height: 50px; border-radius: 12px; background: {{ $formationTheme }}; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-graduation-cap text-white" style="font-size: 1.2rem;"></i>
                        </div>
                        <div>
                            <h3 class="text-white mb-0" style="font-weight: 700;">{{ $formation }}</h3>
                            <p class="text-white-50 mb-0" style="font-size: 0.9rem;">{{ $allProjects->count() }} projet(s)</p>
                        </div>
                    </div>

                    <div class="row g-4">
                        @foreach($allProjects as $project)
                            @php
                                $students = collect($project['students'] ?? []);
                                $representativeId = $project['representative_id'] ?? ($students->first() ? $students->first()->id : null);
                                $statusClass = 'status-' . ($project['status'] ?? 'en_cours');
                            @endphp

                            <div class="col-md-6 col-lg-4" data-status="{{ $project['status'] ?? 'en_cours' }}">
                                <div class="project-card">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="flex-grow-1">
                                            <h5 class="text-white mb-2" style="font-weight: 700; font-size: 1.1rem;">
                                                {{ $project['title'] ?? 'Projet' }}
                                            </h5>
                                            @if($project['category'])
                                                <span class="student-chip">
                                                    <i class="fas fa-tag"></i>
                                                    {{ $project['category'] }}
                                                </span>
                                            @endif
                                        </div>
                                        <span class="status-badge {{ $statusClass }}">
                                            {{ $project['status'] ?? 'en_cours' }}
                                        </span>
                                    </div>

                                    <div class="mb-3">
                                        <p class="text-white-50 mb-1" style="font-size: 0.85rem;">
                                            <i class="fas fa-users me-2"></i>{{ $students->count() }} étudiant(s)
                                        </p>
                                        @if($project['deadline'])
                                            <p class="text-white-50 mb-0" style="font-size: 0.85rem;">
                                                <i class="fas fa-calendar me-2"></i>{{ \Carbon\Carbon::parse($project['deadline'])->format('d/m/Y') }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        @foreach($students->take(3) as $studentWork)
                                            @php
                                                $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($studentWork->profile_photo ?? null);
                                                $initials = strtoupper(substr($studentWork->first_name ?? '', 0, 1)) . strtoupper(substr($studentWork->last_name ?? '', 0, 1));
                                            @endphp
                                            <div class="student-chip">
                                                @if($photoUrl)
                                                    <img src="{{ $photoUrl }}" alt="" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover;">
                                                @else
                                                    <span style="width: 24px; height: 24px; border-radius: 50%; background: var(--primary-gradient); display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700;">{{ $initials }}</span>
                                                @endif
                                                {{ $studentWork->first_name }} {{ $studentWork->last_name }}
                                            </div>
                                        @endforeach
                                        @if($students->count() > 3)
                                            <span class="student-chip">+{{ $students->count() - 3 }}</span>
                                        @endif
                                    </div>

                                    <div class="d-flex gap-2">
                                        @if($representativeId)
                                            <a href="{{ route('admin.projects.view', $representativeId) }}" class="btn btn-sm flex-grow-1" style="background: rgba(59, 130, 246, 0.2); border: 1px solid rgba(59, 130, 246, 0.3); color: #60a5fa; border-radius: 10px; font-weight: 600;">
                                                <i class="fas fa-eye me-1"></i>Voir
                                            </a>
                                            <a href="{{ route('admin.projects.edit', $representativeId) }}?bulk=1" class="btn btn-sm flex-grow-1" style="background: rgba(245, 158, 11, 0.2); border: 1px solid rgba(245, 158, 11, 0.3); color: #fbbf24; border-radius: 10px; font-weight: 600;">
                                                <i class="fas fa-pen me-1"></i>Modifier
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        @if($groupedAssignments->flatten()->isEmpty())
            <div class="text-center py-5">
                <div style="width: 100px; height: 100px; border-radius: 50%; background: rgba(255, 255, 255, 0.05); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                    <i class="fas fa-inbox text-white-50" style="font-size: 3rem;"></i>
                </div>
                <h4 class="text-white mb-2" style="font-weight: 700;">Aucun projet assigné</h4>
                <p class="text-white-50">Commencez par attribuer un projet aux étudiants</p>
            </div>
        @endif
    </div>
</div>

<script>
    function switchTab(tab) {
        // Update tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.add('active');

        // Filter projects
        const projects = document.querySelectorAll('[data-status]');
        projects.forEach(project => {
            const status = project.getAttribute('data-status');
            const doneStatuses = ['termine', 'valide', 'rejete'];

            if (tab === 'all') {
                project.style.display = 'block';
            } else if (tab === 'done') {
                project.style.display = doneStatuses.includes(status) ? 'block' : 'none';
            } else if (tab === 'todo') {
                project.style.display = doneStatuses.includes(status) ? 'none' : 'block';
            }
        });
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('formationFilter').value = '';
        switchTab('all');
    }

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const projects = document.querySelectorAll('.project-card');

        projects.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.parentElement.style.display = text.includes(searchTerm) ? 'block' : 'none';
        });
    });

    // Status filter
    document.getElementById('statusFilter').addEventListener('change', function(e) {
        const status = e.target.value;
        const projects = document.querySelectorAll('[data-status]');

        projects.forEach(project => {
            if (status === '') {
                project.style.display = 'block';
            } else {
                project.style.display = project.getAttribute('data-status') === status ? 'block' : 'none';
            }
        });
    });

    // Formation filter
    document.getElementById('formationFilter').addEventListener('change', function(e) {
        const formation = e.target.value;
        const formations = document.querySelectorAll('[data-formation]');

        formations.forEach(div => {
            if (formation === '') {
                div.style.display = 'block';
            } else {
                div.style.display = div.getAttribute('data-formation') === formation ? 'block' : 'none';
            }
        });
    });
</script>
@endsection
