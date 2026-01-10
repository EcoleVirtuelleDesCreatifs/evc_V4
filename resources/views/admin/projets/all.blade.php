@extends('layouts.admin')

@section('title', 'Tous les Projets')

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
        box-shadow: 0 10px 30px rgba(30, 60, 114, 0.3);
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

    .stat-card-cyan {
        background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%);
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-white">Gestion des Projets</h1>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['total'] }}</h3>
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
                    <h3 class="stat-number">{{ $stats['en_cours'] }}</h3>
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
                    <h3 class="stat-number">{{ $stats['termine'] }}</h3>
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
                    <h3 class="stat-number">{{ $stats['valide'] }}</h3>
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
                    <h3 class="stat-number">{{ $stats['rejete'] }}</h3>
                    <p class="stat-label">Rejetés</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 mb-2">
            <div class="text-white-50" style="font-weight: 700; letter-spacing: 0.6px; text-transform: uppercase; font-size: 0.8rem;">
                Créations
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="stat-card stat-card-cyan">
                <div class="stat-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['created_today'] ?? 0 }}</h3>
                    <p class="stat-label">Créés aujourd'hui</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-calendar-week"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['created_week'] ?? 0 }}</h3>
                    <p class="stat-label">Créés cette semaine</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['created_month'] ?? 0 }}</h3>
                    <p class="stat-label">Créés ce mois</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-6 mb-3">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                    <h5 class="mb-0 text-white"><i class="fas fa-user-plus me-2"></i>Nouveaux étudiants (0 projet)</h5>
                    <span class="badge bg-primary">{{ $studentsWithoutProjects->count() }}</span>
                </div>
                <div class="card-body">
                    @if($studentsWithoutProjects->count() === 0)
                        <div class="text-center py-3 text-white-50">Aucun étudiant.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Étudiant</th>
                                        <th>Formation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($studentsWithoutProjects as $student)
                                        @php
                                            $studentPhotoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($student->profile_photo ?? null);
                                            $studentInitials = substr($student->first_name ?? 'U', 0, 1) . substr($student->last_name ?? 'U', 0, 1);
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if(!empty($student->profile_photo) && !empty($studentPhotoUrl))
                                                        <img src="{{ $studentPhotoUrl }}" alt="{{ $student->first_name ?? 'Étudiant' }}" class="rounded-circle me-2" style="width: 36px; height: 36px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px; font-weight: 700;">
                                                            {{ $studentInitials }}
                                                        </div>
                                                    @endif
                                                    <div class="fw-medium">{{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $student->formation ?? 'N/A' }}</span>
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

        <div class="col-lg-6 mb-3">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                    <h5 class="mb-0 text-white"><i class="fas fa-hourglass me-2"></i>En attente d'un nouveau projet</h5>
                    <span class="badge bg-primary">{{ $waitingForNewProjectStudents->count() }}</span>
                </div>
                <div class="card-body">
                    @if($waitingForNewProjectStudents->count() === 0)
                        <div class="text-center py-3 text-white-50">Aucun étudiant.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Étudiant</th>
                                        <th>Formation</th>
                                        <th>Dernier projet</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($waitingForNewProjectStudents as $student)
                                        @php
                                            $studentPhotoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($student->profile_photo ?? null);
                                            $studentInitials = substr($student->first_name ?? 'U', 0, 1) . substr($student->last_name ?? 'U', 0, 1);
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if(!empty($student->profile_photo) && !empty($studentPhotoUrl))
                                                        <img src="{{ $studentPhotoUrl }}" alt="{{ $student->first_name ?? 'Étudiant' }}" class="rounded-circle me-2" style="width: 36px; height: 36px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px; font-weight: 700;">
                                                            {{ $studentInitials }}
                                                        </div>
                                                    @endif
                                                    <div class="fw-medium">{{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $student->formation ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                @if(!empty($student->last_project_at))
                                                    {{ \Carbon\Carbon::parse($student->last_project_at)->format('d/m/Y') }}
                                                @else
                                                    N/A
                                                @endif
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
    </div>

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <h5 class="mb-0 text-white"><i class="fas fa-list me-2"></i>Liste Complète des Projets</h5>
            <span class="badge bg-primary">{{ method_exists($projects, 'total') ? $projects->total() : $projects->count() }} projet(s)</span>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ url()->current() }}" class="mb-3">
                <div class="row g-2 align-items-end">
                    <div class="col-lg-5 col-md-12">
                        <label class="form-label text-white-50">Rechercher un étudiant</label>
                        <input
                            type="text"
                            name="q"
                            class="form-control"
                            placeholder="Nom, prénom, email..."
                            value="{{ request('q') }}"
                        />
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label text-white-50">Statut</label>
                        <select name="status" class="form-select">
                            <option value="">Tous</option>
                            <option value="en_cours" @selected(request('status') === 'en_cours')>En cours</option>
                            <option value="termine" @selected(request('status') === 'termine')>Terminé</option>
                            <option value="valide" @selected(request('status') === 'valide')>Validé</option>
                            <option value="rejete" @selected(request('status') === 'rejete')>Rejeté</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label class="form-label text-white-50">Période</label>
                        <select name="period" class="form-select">
                            <option value="">Toutes</option>
                            <option value="today" @selected(request('period') === 'today')>Aujourd'hui</option>
                            <option value="week" @selected(request('period') === 'week')>Semaine</option>
                            <option value="month" @selected(request('period') === 'month')>Mois</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i>Filtrer
                        </button>
                        <a href="{{ url()->current() }}" class="btn btn-outline-light w-100">
                            <i class="fas fa-redo me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>

            @if($projects->count() === 0)
                <div class="text-center py-4 text-white-50">
                    <i class="fas fa-folder-open fa-3x mb-3"></i>
                    <div>Aucun projet pour le moment.</div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Étudiant</th>
                                <th>Titre du Projet</th>
                                <th>Formation</th>
                                <th>Statut</th>
                                <th>Images</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $studentPhotoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($project->profile_photo ?? null);
                                                $studentInitials = substr($project->first_name ?? 'U', 0, 1) . substr($project->last_name ?? 'U', 0, 1);
                                            @endphp

                                            @if(!empty($project->profile_photo) && !empty($studentPhotoUrl))
                                                <img src="{{ $studentPhotoUrl }}" alt="{{ $project->first_name ?? 'Étudiant' }}" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px; font-weight: 700;">
                                                    {{ $studentInitials }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-medium">{{ $project->first_name ?? 'Inconnu' }} {{ $project->last_name ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $project->title }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $project->formation ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $project->status }}">{{ $project->status }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $project->images_count ?? 0 }}</span>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($project->created_at)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.projets.pending.show', $project->id) }}" class="btn btn-sm btn-info">Voir</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $projects->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
