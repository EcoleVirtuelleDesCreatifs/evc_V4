@extends('layouts.admin')

@section('title', $title)

@push('styles')
<style>
    :root {
        --instagram-purple: #833AB4;
        --instagram-pink: #C13584;
    }

    .profile-accordion {
        background: white;
        border-radius: 16px;
        margin-bottom: 1.5rem;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .profile-header {
        background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-pink));
        color: white;
        padding: 1.5rem 2rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .profile-info {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex: 1;
    }

    .profile-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.3);
    }

    .profile-stats {
        display: flex;
        gap: 2rem;
    }

    .profile-stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: white !important;
    }

    .profile-header h3,
    .profile-header p,
    .profile-header small,
    .profile-header div {
        color: white !important;
    }

    .profile-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease;
    }

    .profile-content.active {
        max-height: 5000px;
    }

    .profile-body {
        padding: 2rem;
        background: #f8f9fa;
    }

    .project-item {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-left: 4px solid;
    }

    .project-item.en_cours { border-left-color: #f59e0b; }
    .project-item.termine { border-left-color: #3b82f6; }
    .project-item.valide { border-left-color: #10b981; }
    .project-item.rejete { border-left-color: #ef4444; }

    .profile-body h4 {
        color: #1a202c !important;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .project-item h5 {
        color: #1a202c !important;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .project-item p {
        color: #6b7280 !important;
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
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">{{ $title }} - {{ $profiles->count() }} Étudiants</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.projets.cm-smm.to-send') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Assigner
            </a>
        </div>
    </div>

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total'] }}</div>
            <small>Total Projets</small>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
            <div class="stat-number">{{ $stats['assigned'] }}</div>
            <small>À Faire</small>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
            <div class="stat-number">{{ $stats['submitted'] }}</div>
            <small>Soumis</small>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #059669);">
            <div class="stat-number">{{ $stats['validated'] }}</div>
            <small>Validés</small>
        </div>
    </div>

    @foreach($profiles as $profile)
        <div class="profile-accordion">
            <div class="profile-header" onclick="toggleProfile({{ $loop->index }})">
                <div class="profile-info">
                    @php
                        $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($profile['profile_photo'] ?? null);
                    @endphp
                    @if($photoUrl)
                        <img src="{{ $photoUrl }}" class="profile-avatar">
                    @else
                        <div style="width: 70px; height: 70px; border-radius: 50%; background: rgba(255,255,255,0.3); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700;">
                            {{ strtoupper(substr($profile['email'], 0, 2)) }}
                        </div>
                    @endif
                    <div>
                        <h3 style="margin: 0; color: white !important;">
                            @if($profile['prenom'])
                                {{ $profile['prenom'] }} {{ $profile['nom'] }}
                            @else
                                {{ explode('@', $profile['email'])[0] }}
                            @endif
                        </h3>
                        <p style="margin: 0; opacity: 0.9; color: white !important;">{{ $profile['email'] }}</p>
                    </div>
                </div>
                <div class="profile-stats">
                    <div style="text-align: center;">
                        <div class="profile-stat-number" style="color: white !important;">{{ $profile['total_projets'] }}</div>
                        <small style="color: white !important;">Total</small>
                    </div>
                    <div style="text-align: center;">
                        <div class="profile-stat-number" style="color: #fbbf24 !important;">{{ $profile['projets_en_cours'] }}</div>
                        <small style="color: #fbbf24 !important;">À faire</small>
                    </div>
                    <div style="text-align: center;">
                        <div class="profile-stat-number" style="color: #60a5fa !important;">{{ $profile['projets_soumis'] }}</div>
                        <small style="color: #60a5fa !important;">Soumis</small>
                    </div>
                    <div style="text-align: center;">
                        <div class="profile-stat-number" style="color: #34d399 !important;">{{ $profile['projets_valides'] }}</div>
                        <small style="color: #34d399 !important;">Validés</small>
                    </div>
                    <div style="text-align: center;">
                        <div class="profile-stat-number" style="color: #f87171 !important;">{{ $profile['projets_rejetes'] }}</div>
                        <small style="color: #f87171 !important;">Rejetés</small>
                    </div>
                </div>
                <i class="fas fa-chevron-down" style="font-size: 1.5rem; color: white !important;"></i>
            </div>

            <div class="profile-content" id="profile-{{ $loop->index }}">
                <div class="profile-body">
                    @if(isset($profile['projects']['en_cours']))
                        <h4 style="color: #1a202c !important;"><i class="fas fa-folder-open" style="color: #f59e0b;"></i> À Faire ({{ $profile['projects']['en_cours']->count() }})</h4>
                        @foreach($profile['projects']['en_cours'] as $project)
                            <div class="project-item en_cours">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 style="color: #1a202c !important; font-weight: 600;">{{ $project->title }}</h5>
                                        <p class="text-muted" style="color: #6b7280 !important;">{!! Str::limit(strip_tags($project->description), 120) !!}</p>
                                    </div>
                                    <a href="{{ route('admin.tp.view', $project->id) }}" class="btn btn-sm btn-primary">Voir</a>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if(isset($profile['projects']['termine']))
                        <h4 class="mt-4" style="color: #1a202c !important;"><i class="fas fa-paper-plane" style="color: #3b82f6;"></i> Soumis ({{ $profile['projects']['termine']->count() }})</h4>
                        @foreach($profile['projects']['termine'] as $project)
                            <div class="project-item termine">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 style="color: #1a202c !important; font-weight: 600;">{{ $project->title }}</h5>
                                        <p class="text-muted" style="color: #6b7280 !important;">{!! Str::limit(strip_tags($project->description), 120) !!}</p>
                                    </div>
                                    <a href="{{ route('admin.tp.view', $project->id) }}" class="btn btn-sm btn-primary">Voir</a>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if(isset($profile['projects']['valide']))
                        <h4 class="mt-4" style="color: #1a202c !important;"><i class="fas fa-check-circle" style="color: #10b981;"></i> Validés ({{ $profile['projects']['valide']->count() }})</h4>
                        @foreach($profile['projects']['valide'] as $project)
                            <div class="project-item valide">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 style="color: #1a202c !important; font-weight: 600;">{{ $project->title }}</h5>
                                        <p class="text-muted" style="color: #6b7280 !important;">{!! Str::limit(strip_tags($project->description), 120) !!}</p>
                                    </div>
                                    <a href="{{ route('admin.tp.view', $project->id) }}" class="btn btn-sm btn-primary">Voir</a>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if(isset($profile['projects']['rejete']))
                        <h4 class="mt-4" style="color: #1a202c !important;"><i class="fas fa-times-circle" style="color: #ef4444;"></i> Rejetés ({{ $profile['projects']['rejete']->count() }})</h4>
                        @foreach($profile['projects']['rejete'] as $project)
                            <div class="project-item rejete">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 style="color: #1a202c !important; font-weight: 600;">{{ $project->title }}</h5>
                                        <p class="text-muted" style="color: #6b7280 !important;">{!! Str::limit(strip_tags($project->description), 120) !!}</p>
                                    </div>
                                    <a href="{{ route('admin.tp.view', $project->id) }}" class="btn btn-sm btn-primary">Voir</a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
function toggleProfile(index) {
    const content = document.getElementById('profile-' + index);
    content.classList.toggle('active');
}
</script>
@endsection
