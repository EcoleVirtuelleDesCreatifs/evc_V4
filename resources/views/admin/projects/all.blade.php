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

    .profile-accordion {
        background: white;
        border-radius: 16px;
        margin-bottom: 1.5rem;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .profile-accordion:hover {
        box-shadow: 0 8px 30px rgba(131, 58, 180, 0.15);
    }

    .profile-header {
        background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-pink));
        color: white;
        padding: 1.5rem 2rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s ease;
        position: relative;
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--instagram-yellow);
    }

    .profile-header:hover {
        background: linear-gradient(135deg, #9d4edd, #d946a6);
    }

    .profile-header.active {
        background: linear-gradient(135deg, #6a28a3, #a02c71);
    }

    .project-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
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

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 20px;
        color: white;
    }

    .status-badge.en_cours {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .status-badge.termine {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }

    .status-badge.valide {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .status-badge.rejete {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .section-container {
        background: white;
        border-radius: 16px;
        margin-bottom: 3rem;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .section-body {
        padding: 2rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #718096;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }

    .student-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .student-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--instagram-purple);
    }

    .student-avatar-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-pink));
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: white;
        font-size: 1.25rem;
    }

    .project-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 0.5rem 1.25rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-view {
        background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-pink));
        color: white;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(131, 58, 180, 0.3);
    }

    .btn-validate {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .btn-reject {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-pink));
        color: white;
        padding: 1.5rem;
        border-radius: 16px;
        text-align: center;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.875rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">{{ $title }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.projets.cm-smm.to-send') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Assigner un projet
            </a>
            <a href="{{ route('admin.projets.cm-smm.pending') }}" class="btn btn-warning">
                <i class="fas fa-clock"></i> Projets en attente
            </a>
        </div>
    </div>

    {{-- Statistiques globales --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Projets</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
            <div class="stat-number">{{ $stats['assigned'] }}</div>
            <div class="stat-label">Assignés</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
            <div class="stat-number">{{ $stats['submitted'] }}</div>
            <div class="stat-label">Soumis</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #059669);">
            <div class="stat-number">{{ $stats['validated'] }}</div>
            <div class="stat-label">Validés</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
            <div class="stat-number">{{ $stats['rejected'] }}</div>
            <div class="stat-label">Rejetés</div>
        </div>
    </div>

    {{-- Section Projets Assignés --}}
    <div class="section-container">
        <div class="section-header" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
            <h3 class="section-title">
                <i class="fas fa-folder-open"></i>
                Projets Assignés
            </h3>
            <span class="count-badge">{{ $projectsAssigned->count() }}</span>
        </div>
        <div class="section-body">
            @forelse($projectsAssigned as $project)
                <div class="project-card">
                    <div class="student-info">
                        @php
                            $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($project->profile_photo ?? null);
                        @endphp
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}" alt="Photo" class="student-avatar">
                        @else
                            <div class="student-avatar-placeholder">
                                @if($project->prenom && $project->nom)
                                    {{ strtoupper(substr($project->prenom, 0, 1)) }}{{ strtoupper(substr($project->nom, 0, 1)) }}
                                @else
                                    {{ strtoupper(substr(explode('@', $project->user_email)[0], 0, 2)) }}
                                @endif
                            </div>
                        @endif
                        <div>
                            <strong>
                                @if($project->prenom && $project->nom)
                                    {{ $project->prenom }} {{ $project->nom }}
                                @else
                                    {{ explode('@', $project->user_email)[0] }}
                                @endif
                            </strong>
                            <br>
                            <small class="text-muted">{{ $project->user_email }}</small>
                        </div>
                    </div>

                    <h4 class="mb-2">{{ $project->title }}</h4>
                    <div class="text-muted mb-3">{!! Str::limit(strip_tags($project->description, '<br><strong><em>'), 150) !!}</div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="status-badge en_cours">
                            <i class="fas fa-clock"></i> En cours
                        </span>
                        <div class="project-actions">
                            <a href="{{ route('admin.tp.view', $project->id) }}" class="btn btn-action btn-view">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Aucun projet assigné pour le moment</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Section Projets Soumis --}}
    <div class="section-container">
        <div class="section-header" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
            <h3 class="section-title">
                <i class="fas fa-paper-plane"></i>
                Projets Soumis
            </h3>
            <span class="count-badge">{{ $projectsSubmitted->count() }}</span>
        </div>
        <div class="section-body">
            @forelse($projectsSubmitted as $project)
                <div class="project-card">
                    <div class="student-info">
                        @php
                            $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($project->profile_photo ?? null);
                        @endphp
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}" alt="Photo" class="student-avatar">
                        @else
                            <div class="student-avatar-placeholder">
                                @if($project->prenom && $project->nom)
                                    {{ strtoupper(substr($project->prenom, 0, 1)) }}{{ strtoupper(substr($project->nom, 0, 1)) }}
                                @else
                                    {{ strtoupper(substr(explode('@', $project->user_email)[0], 0, 2)) }}
                                @endif
                            </div>
                        @endif
                        <div>
                            <strong>
                                @if($project->prenom && $project->nom)
                                    {{ $project->prenom }} {{ $project->nom }}
                                @else
                                    {{ explode('@', $project->user_email)[0] }}
                                @endif
                            </strong>
                            <br>
                            <small class="text-muted">{{ $project->user_email }}</small>
                        </div>
                    </div>

                    <h4 class="mb-2">{{ $project->title }}</h4>
                    <div class="text-muted mb-3">{!! Str::limit(strip_tags($project->description, '<br><strong><em>'), 150) !!}</div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="status-badge termine">
                            <i class="fas fa-check-circle"></i> Soumis
                        </span>
                        <div class="project-actions">
                            <a href="{{ route('admin.tp.view', $project->id) }}" class="btn btn-action btn-view">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Aucun projet soumis pour le moment</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Section Projets Validés --}}
    <div class="section-container">
        <div class="section-header" style="background: linear-gradient(135deg, #10b981, #059669);">
            <h3 class="section-title">
                <i class="fas fa-check-double"></i>
                Projets Validés
            </h3>
            <span class="count-badge">{{ $projectsValidated->count() }}</span>
        </div>
        <div class="section-body">
            @forelse($projectsValidated as $project)
                <div class="project-card">
                    <div class="student-info">
                        @php
                            $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($project->profile_photo ?? null);
                        @endphp
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}" alt="Photo" class="student-avatar">
                        @else
                            <div class="student-avatar-placeholder">
                                @if($project->prenom && $project->nom)
                                    {{ strtoupper(substr($project->prenom, 0, 1)) }}{{ strtoupper(substr($project->nom, 0, 1)) }}
                                @else
                                    {{ strtoupper(substr(explode('@', $project->user_email)[0], 0, 2)) }}
                                @endif
                            </div>
                        @endif
                        <div>
                            <strong>
                                @if($project->prenom && $project->nom)
                                    {{ $project->prenom }} {{ $project->nom }}
                                @else
                                    {{ explode('@', $project->user_email)[0] }}
                                @endif
                            </strong>
                            <br>
                            <small class="text-muted">{{ $project->user_email }}</small>
                        </div>
                    </div>

                    <h4 class="mb-2">{{ $project->title }}</h4>
                    <div class="text-muted mb-3">{!! Str::limit(strip_tags($project->description, '<br><strong><em>'), 150) !!}</div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="status-badge valide">
                            <i class="fas fa-trophy"></i> Validé
                        </span>
                        <div class="project-actions">
                            <a href="{{ route('admin.tp.view', $project->id) }}" class="btn btn-action btn-view">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Aucun projet validé pour le moment</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Section Projets Rejetés --}}
    @if($projectsRejected->count() > 0)
    <div class="section-container">
        <div class="section-header" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
            <h3 class="section-title">
                <i class="fas fa-times-circle"></i>
                Projets Rejetés
            </h3>
            <span class="count-badge">{{ $projectsRejected->count() }}</span>
        </div>
        <div class="section-body">
            @foreach($projectsRejected as $project)
                <div class="project-card">
                    <div class="student-info">
                        @php
                            $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($project->profile_photo ?? null);
                        @endphp
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}" alt="Photo" class="student-avatar">
                        @else
                            <div class="student-avatar-placeholder">
                                @if($project->prenom && $project->nom)
                                    {{ strtoupper(substr($project->prenom, 0, 1)) }}{{ strtoupper(substr($project->nom, 0, 1)) }}
                                @else
                                    {{ strtoupper(substr(explode('@', $project->user_email)[0], 0, 2)) }}
                                @endif
                            </div>
                        @endif
                        <div>
                            <strong>
                                @if($project->prenom && $project->nom)
                                    {{ $project->prenom }} {{ $project->nom }}
                                @else
                                    {{ explode('@', $project->user_email)[0] }}
                                @endif
                            </strong>
                            <br>
                            <small class="text-muted">{{ $project->user_email }}</small>
                        </div>
                    </div>

                    <h4 class="mb-2">{{ $project->title }}</h4>
                    <div class="text-muted mb-3">{!! Str::limit(strip_tags($project->description, '<br><strong><em>'), 150) !!}</div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="status-badge rejete">
                            <i class="fas fa-ban"></i> Rejeté
                        </span>
                        <div class="project-actions">
                            <a href="{{ route('admin.tp.view', $project->id) }}" class="btn btn-action btn-view">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
