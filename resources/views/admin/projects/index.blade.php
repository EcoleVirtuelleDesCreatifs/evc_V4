@extends('layouts.admin')

@section('title', $title)

@push('styles')
<style>
    :root {
        --instagram-purple: #833AB4;
        --instagram-pink: #C13584;
        --instagram-red: #E1306C;
        --instagram-orange: #FD1D1D;
        --instagram-yellow: #FCAF45;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        border-radius: 20px;
    }
    .status-badge.pending {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }
    .status-badge.to_send {
        background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-pink));
    }
    .status-badge.validated {
        background: linear-gradient(135deg, #10b981, #059669);
    }
    .status-badge.rejected {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }
    th, td { vertical-align: middle; }

    /* Modal styles - z-index et pointer-events fixes */
    .modal-backdrop {
        z-index: 9998 !important;
        background-color: rgba(0, 0, 0, 0.7) !important;
        pointer-events: none !important;
    }

    .modal {
        z-index: 9999 !important;
        pointer-events: none !important;
    }

    .modal-dialog {
        z-index: 10000 !important;
        pointer-events: auto !important;
    }

    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        z-index: 10001 !important;
        pointer-events: auto !important;
    }

    .modal-header,
    .modal-body,
    .modal-footer {
        pointer-events: auto !important;
    }

    .modal-body input,
    .modal-body textarea,
    .modal-body select,
    .modal-body button,
    .modal-header button,
    .modal-footer button {
        position: relative;
        z-index: 10002 !important;
        pointer-events: auto !important;
    }

    .btn-close {
        pointer-events: auto !important;
    }

    .modal-header {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        border-bottom: none;
        border-radius: 16px 16px 0 0;
        padding: 1.5rem;
    }

    .modal-title {
        color: white;
        font-weight: 700;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-body label {
        color: #1a202c;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .modal-body textarea {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.75rem;
        font-size: 0.95rem;
    }

    .modal-body textarea:focus {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        outline: none;
    }

    .modal-footer {
        border-top: 1px solid #e5e7eb;
        padding: 1rem 2rem;
        background-color: #f9fafb;
        border-radius: 0 0 16px 16px;
    }

    .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    .btn-close:hover {
        opacity: 1;
    }

    /* Cartes de projets modernes */
    .project-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .project-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--instagram-purple), var(--instagram-pink), var(--instagram-red));
    }

    .project-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(131, 58, 180, 0.2);
        border-color: var(--instagram-pink);
    }

    .project-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .project-id {
        background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-pink));
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.875rem;
    }

    .project-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 0.75rem;
        line-height: 1.4;
    }

    .project-title a {
        color: var(--instagram-purple);
        text-decoration: none;
        transition: color 0.2s;
    }

    .project-title a:hover {
        color: var(--instagram-pink);
    }

    .student-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: linear-gradient(135deg, rgba(131, 58, 180, 0.05), rgba(193, 53, 132, 0.05));
        border-radius: 12px;
        margin-bottom: 1rem;
        border: 1px solid rgba(131, 58, 180, 0.1);
    }

    .student-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--instagram-pink);
    }

    .student-avatar-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-pink));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
    }

    .student-details {
        flex: 1;
    }

    .student-name {
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 0.25rem;
    }

    .student-email {
        color: #718096;
        font-size: 0.875rem;
    }

    .project-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        color: #4a5568;
        font-size: 0.875rem;
    }

    .project-meta i {
        color: var(--instagram-pink);
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn-action {
        flex: 1;
        min-width: 40px;
        padding: 0.6rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid;
    }

    .btn-view {
        background: white;
        color: var(--instagram-purple);
        border-color: var(--instagram-purple);
    }

    .btn-view:hover {
        background: var(--instagram-purple);
        color: white;
    }

    .btn-validate {
        background: white;
        color: #10b981;
        border-color: #10b981;
    }

    .btn-validate:hover {
        background: #10b981;
        color: white;
    }

    .btn-reject {
        background: white;
        color: #ef4444;
        border-color: #ef4444;
    }

    .btn-reject:hover {
        background: #ef4444;
        color: white;
    }

    .btn-delete {
        background: white;
        color: #6b7280;
        border-color: #6b7280;
    }

    .btn-delete:hover {
        background: #6b7280;
        color: white;
    }

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

    .project-card {
        animation: fadeInUp 0.5s ease;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div></div>
        <div>
            @if($type === 'design-graphique' && $status === 'all' && !empty($selectedUserId))
                <a href="{{ route('admin.projets.' . $type . '.all') }}" class="btn btn-sm btn-outline-light me-2">
                    <i class="fas fa-arrow-left me-2"></i>Retour aux profils
                </a>
            @endif
            @if($status === 'pending')
                <a href="{{ route('admin.projets.' . $type . '.to-send') }}" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-paper-plane me-2"></i>À envoyer
                </a>
                <a href="{{ route('admin.projets.' . $type . '.all') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-list me-2"></i>Tous
                </a>
            @elseif($status === 'to_send')
                <a href="{{ route('admin.projets.' . $type . '.pending') }}" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-clock me-2"></i>À valider
                </a>
                <a href="{{ route('admin.projets.' . $type . '.all') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-list me-2"></i>Tous
                </a>
            @else
                <a href="{{ route('admin.projets.' . $type . '.pending') }}" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-clock me-2"></i>À valider
                </a>
                <a href="{{ route('admin.projets.' . $type . '.to-send') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-paper-plane me-2"></i>À envoyer
                </a>
            @endif
        </div>
    </div>

    @if(isset($stats))
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border-radius: 16px; padding: 1.5rem; color: white; display: flex; align-items: center; gap: 1rem;">
                    <div class="stat-icon" style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div style="flex: 1;">
                        <h3 style="font-size: 2.2rem; font-weight: 700; margin: 0;">{{ $stats['total'] ?? 0 }}</h3>
                        <p style="margin: 0; opacity: 0.9; font-size: 0.95rem;">Total Projets</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 16px; padding: 1.5rem; color: white; display: flex; align-items: center; gap: 1rem;">
                    <div class="stat-icon" style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div style="flex: 1;">
                        <h3 style="font-size: 2.2rem; font-weight: 700; margin: 0;">{{ $stats['pending'] ?? 0 }}</h3>
                        <p style="margin: 0; opacity: 0.9; font-size: 0.95rem;">À valider</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border-radius: 16px; padding: 1.5rem; color: white; display: flex; align-items: center; gap: 1rem;">
                    <div class="stat-icon" style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div style="flex: 1;">
                        <h3 style="font-size: 2.2rem; font-weight: 700; margin: 0;">{{ $stats['to_send'] ?? 0 }}</h3>
                        <p style="margin: 0; opacity: 0.9; font-size: 0.95rem;">À envoyer</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 16px; padding: 1.5rem; color: white; display: flex; align-items: center; gap: 1rem;">
                    <div class="stat-icon" style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div style="flex: 1;">
                        <h3 style="font-size: 2.2rem; font-weight: 700; margin: 0;">{{ $stats['validated'] ?? 0 }}</h3>
                        <p style="margin: 0; opacity: 0.9; font-size: 0.95rem;">Validés</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Filtres et recherche -->
    <div class="card mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <h5 class="mb-0 text-white"><i class="fas fa-filter me-2"></i>Filtres</h5>
        </div>
        <div class="card-body">
            <form action="{{ request()->url() }}" method="GET" class="row g-3">
                @if(request('user_id'))
                    <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                @endif
                <div class="col-md-4">
                    <label for="search" class="form-label text-white">Rechercher</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Rechercher par titre, étudiant...">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label text-white">Statut</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En cours de validation</option>
                        <option value="to_send" {{ request('status') === 'to_send' ? 'selected' : '' }}>À envoyer</option>
                        <option value="validated" {{ request('status') === 'validated' ? 'selected' : '' }}>Validé</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejeté</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date" class="form-label text-white">Date</label>
                    <select class="form-select" id="date" name="date">
                        <option value="">Toutes les dates</option>
                        <option value="today" {{ request('date') === 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                        <option value="week" {{ request('date') === 'week' ? 'selected' : '' }}>Cette semaine</option>
                        <option value="month" {{ request('date') === 'month' ? 'selected' : '' }}>Ce mois-ci</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des projets -->
    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <h5 class="mb-0 text-white"><i class="fas fa-list me-2"></i>Liste des Projets</h5>
        </div>
        <div class="card-body">
            @if($type === 'design-graphique' && $status === 'all' && isset($profiles) && $profiles)
                <div class="row">
                    @forelse($profiles as $profile)
                        @php
                            $fullName = trim(($profile->first_name ?? '') . ' ' . ($profile->last_name ?? ''));
                            if ($fullName === '') {
                                $fullName = $profile->user_name ?? 'Étudiant';
                            }
                        @endphp
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100" style="background-color: #0f172a; border: 1px solid #334155;">
                                <div class="card-body d-flex gap-3">
                                    @if(($profile->profile_photo ?? null))
                                        <img src="{{ asset('storage/' . ltrim($profile->profile_photo, '/')) }}" alt="{{ $fullName }}" class="rounded-circle" style="width: 56px; height: 56px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; font-weight: 700;">
                                            @php
                                                $initials = trim((($profile->first_name ?? '') ? mb_substr($profile->first_name, 0, 1) : '') . (($profile->last_name ?? '') ? mb_substr($profile->last_name, 0, 1) : ''));
                                                if ($initials === '') {
                                                    $initials = mb_substr($profile->user_name ?? 'E', 0, 1);
                                                }
                                            @endphp
                                            {{ mb_strtoupper($initials) }}
                                        </div>
                                    @endif

                                    <div class="flex-grow-1">
                                        <div class="text-white fw-bold">{{ $fullName }}</div>
                                        <div class="text-white-50 small">{{ $profile->user_email ?? '' }}</div>
                                        @if(($profile->program ?? null))
                                            <div class="text-white-50 small">{{ $profile->program }}</div>
                                        @endif
                                        <div class="mt-2 d-flex align-items-center justify-content-between">
                                            <span class="badge bg-secondary">{{ (int)($profile->projects_count ?? 0) }} projet(s)</span>
                                            <a href="{{ route('admin.projets.' . $type . '.all', ['user_id' => $profile->user_id]) }}" class="btn btn-sm btn-primary">
                                                Voir ses projets
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-4 text-muted">Aucun profil trouvé</div>
                        </div>
                    @endforelse
                </div>
            @else
            <div class="row g-4">
                        @forelse($projects as $project)
                            @php
                                // Gérer les deux cas: relations Eloquent OU colonnes plates (tp_assignments)
                                $isDesignProject = $project instanceof \App\Models\DesignProject;

                                // Si colonnes plates (tp_assignments, design_projects via DB::table)
                                if (isset($project->prenom) || isset($project->nom)) {
                                    $studentName = trim(($project->prenom ?? '') . ' ' . ($project->nom ?? '')) ?: 'Étudiant';
                                    $studentEmail = $project->user_email ?? '';
                                    $studentPhoto = $project->profile_photo ?? null;
                                    $studentProgram = $project->formation ?? null;
                                    $firstName = $project->prenom ?? '';
                                    $lastName = $project->nom ?? '';
                                } else {
                                    // Sinon, relations Eloquent
                                    $student = $project->student ?? $project->user->student ?? null;
                                    $user = $project->user ?? $project->student->user ?? null;
                                    $studentName = $user->name ?? 'Étudiant';
                                    $studentEmail = $user->email ?? '';
                                    $studentPhoto = $student->profile_photo ?? null;
                                    $studentProgram = $student->program ?? null;
                                    $firstName = $student->first_name ?? '';
                                    $lastName = $student->last_name ?? '';
                                }

                                $initials = trim((($firstName ? mb_substr($firstName, 0, 1) : '') . ($lastName ? mb_substr($lastName, 0, 1) : '')));
                                if ($initials === '') {
                                    $initials = mb_substr($studentName, 0, 1);
                                }

                                $statusLabels = [
                                    'pending' => 'En cours de validation',
                                    'submitted' => 'Soumis',
                                    'to_send' => 'À envoyer',
                                    'validated' => 'Validé',
                                    'rejected' => 'Rejeté',
                                ];
                                $statusClass = $project->status === 'submitted' ? 'pending' : $project->status;

                                try {
                                    $dateFormatted = \Carbon\Carbon::parse($project->created_at)->format('d/m/Y H:i');
                                } catch (\Exception $e) {
                                    $dateFormatted = is_string($project->created_at) ? $project->created_at : 'N/A';
                                }
                            @endphp
                            <div class="col-md-6 col-lg-4">
                                <div class="project-card">
                                    <!-- Header avec ID et statut -->
                                    <div class="project-header">
                                        <span class="project-id">#{{ $project->id }}</span>
                                        <span class="status-badge {{ $statusClass }}">
                                            <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                                            {{ $statusLabels[$project->status] ?? $project->status }}
                                        </span>
                                    </div>

                                    <!-- Titre du projet -->
                                    <div class="project-title">
                                        <a href="{{ $isDesignProject ? route('admin.design-projects.view', $project->id) : route('admin.tp.view', $project->id) }}">
                                            {{ $project->title }}
                                        </a>
                                    </div>

                                    <!-- Informations étudiant -->
                                    <div class="student-info">
                                        @if($studentPhoto)
                                            <img src="{{ asset('storage/' . ltrim($studentPhoto, '/')) }}" alt="{{ $studentName }}" class="student-avatar">
                                        @else
                                            <div class="student-avatar-placeholder">
                                                {{ mb_strtoupper($initials) }}
                                            </div>
                                        @endif
                                        <div class="student-details">
                                            <div class="student-name">{{ $studentName }}</div>
                                            <div class="student-email">{{ $studentEmail }}</div>
                                            @if($studentProgram)
                                                <div class="student-email">{{ $studentProgram }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Métadonnées -->
                                    <div class="project-meta">
                                        <i class="fas fa-calendar"></i>
                                        <span>{{ $dateFormatted }}</span>
                                    </div>

                                    <!-- Boutons d'action -->
                                    <div class="action-buttons">
                                        <a href="{{ $isDesignProject ? route('admin.design-projects.view', $project->id) : route('admin.tp.view', $project->id) }}" class="btn btn-action btn-view" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($project->status === 'pending' || $project->status === 'submitted' || $project->status === 'rejected')
                                            @if($isDesignProject)
                                                <form action="{{ route('admin.design-projects.validate', $project->id) }}" method="POST" style="flex: 1;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-action btn-validate w-100" title="Valider">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                @if($project->status === 'pending')
                                                    <form action="{{ route('admin.design-projects.reject', $project->id) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Rejeter ce projet ?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-action btn-reject w-100" title="Rejeter">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                <form action="{{ route('admin.tp.validate', $project->id) }}" method="POST" style="flex: 1;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-action btn-validate w-100" title="Valider">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                @if($project->status === 'pending' || $project->status === 'submitted')
                                                    <button type="button" class="btn btn-action btn-reject" data-bs-toggle="modal" data-bs-target="#rejectModal" data-id="{{ $project->id }}" data-type="{{ $type }}" title="Rejeter">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                                <form action="{{ route('admin.tp.delete', $project->id) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-action btn-delete w-100" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center py-5" style="background: white; border-radius: 16px; padding: 3rem;">
                                    <i class="fas fa-inbox" style="font-size: 4rem; color: var(--instagram-pink); margin-bottom: 1rem;"></i>
                                    <h4 style="color: #1a202c; font-weight: 700; margin-bottom: 0.5rem;">Aucun projet trouvé</h4>
                                    <p style="color: #718096; margin: 0;">Il n'y a pas de projets correspondant à vos critères pour le moment.</p>
                                </div>
                            </div>
                        @endforelse
            </div>
            @endif
        </div>
        @if($projects->hasPages())
            <div class="card-footer">
                {{ $projects->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal de rejet -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Rejeter le projet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Raison du rejet</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" required minlength="10"></textarea>
                        <small class="text-muted">Minimum 10 caractères</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Confirmer le rejet</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rejectForm = document.getElementById('rejectForm');
    const rejectModal = document.getElementById('rejectModal');

    // Quand le modal s'ouvre, définir l'URL du formulaire
    rejectModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const projectId = button.getAttribute('data-id');
        const projectType = button.getAttribute('data-type');

        const actionUrl = `/evc/app/admin/tp/reject/${projectId}`;
        rejectForm.action = actionUrl;
    });

    // Réinitialiser le formulaire quand le modal est fermé
    rejectModal.addEventListener('hidden.bs.modal', function () {
        rejectForm.reset();
    });
});
</script>
@endpush
