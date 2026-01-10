@extends('layouts.admin')

@section('title', $title)

@push('styles')
<style>
    .student-card {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 16px;
        overflow: hidden;
        height: 100%;
    }

    .student-card-header {
        background: #0f172a;
        border-bottom: 1px solid #334155;
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .student-avatar {
        width: 56px;
        height: 56px;
        border-radius: 999px;
        object-fit: cover;
        background: #1d4ed8;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 800;
        flex-shrink: 0;
        overflow: hidden;
    }

    .student-meta {
        min-width: 0;
        flex: 1;
    }

    .student-name {
        color: #fff;
        font-weight: 700;
        margin: 0;
        font-size: 1.05rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .student-sub {
        color: rgba(255,255,255,0.65);
        font-size: 0.9rem;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .student-card-body {
        padding: 1rem;
    }

    .meta-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .75rem;
        margin-bottom: 1rem;
    }

    .meta-pill {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 12px;
        padding: .75rem;
        color: rgba(255,255,255,0.85);
        font-size: .9rem;
    }

    .meta-pill strong {
        display: block;
        font-size: .75rem;
        color: rgba(255,255,255,0.6);
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-bottom: .25rem;
    }

    .actions-row {
        display: flex;
        gap: .5rem;
        flex-wrap: wrap;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-white">{{ $title }}</h1>
            <div class="text-white-50">Liste des étudiants ({{ $status === 'active' ? 'actifs' : 'inactifs' }})</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.badges.students.active') }}" class="btn btn-sm {{ $status === 'active' ? 'btn-primary' : 'btn-outline-light' }}">Actifs</a>
            <a href="{{ route('admin.badges.students.inactive') }}" class="btn btn-sm {{ $status === 'inactive' ? 'btn-primary' : 'btn-outline-light' }}">Inactifs</a>
        </div>
    </div>

    <div class="row g-3">
        @forelse($students as $student)
            @php
                $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($student->profile_photo ?? null);
                $initials = substr($student->first_name ?? 'U', 0, 1) . substr($student->last_name ?? 'U', 0, 1);
            @endphp
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="student-card">
                    <div class="student-card-header">
                        @if(!empty($student->profile_photo) && !empty($photoUrl))
                            <img src="{{ $photoUrl }}" alt="{{ $student->first_name ?? 'Étudiant' }}" class="student-avatar" />
                        @else
                            <div class="student-avatar">{{ $initials }}</div>
                        @endif

                        <div class="student-meta">
                            <p class="student-name">{{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }}</p>
                            <p class="student-sub">{{ $student->email ?? '' }}</p>
                        </div>
                    </div>

                    <div class="student-card-body">
                        <div class="meta-row">
                            <div class="meta-pill">
                                <strong>Pays</strong>
                                <div>{{ $student->country ?? 'N/A' }}</div>
                            </div>
                            <div class="meta-pill">
                                <strong>Formation</strong>
                                <div>{{ $student->program ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="actions-row">
                            <a href="{{ route('admin.students.profile', $student->id) }}" class="btn btn-sm btn-info">Voir</a>
                            <a href="{{ route('admin.badges.generate', $student->id) }}" class="btn btn-sm btn-primary">Générer un badge</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
                    <div class="card-body text-center text-white-50 py-5">
                        Aucun étudiant à afficher.
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $students->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
