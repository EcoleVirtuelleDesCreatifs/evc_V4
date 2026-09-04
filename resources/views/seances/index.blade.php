@extends('layouts.ki-admin')

@section('title', 'Mes séances')
@section('page-title', 'Mes séances')

@push('styles')
<style>
    .seances-page { color: #f8fafc; }
    .seances-hero {
        background: linear-gradient(135deg, #0b1220 0%, #0e1d3a 50%, #1e3a8a 100%);
        border-radius: 18px;
        padding: 2rem 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(191, 219, 254, 0.12);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
    }
    .seances-hero h1 {
        color: #fff;
        font-weight: 900;
        margin: 0 0 0.5rem 0;
    }
    .seances-hero p { color: #cbd5e1; margin: 0; }
    .seance-card {
        background: #0b1220;
        border: 1px solid rgba(191, 219, 254, 0.12);
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .seance-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.28);
    }
    .seance-title { color: #fff; font-weight: 800; margin-bottom: 0.75rem; }
    .seance-meta { color: #94a3b8; font-size: 0.92rem; display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem; }
    .seance-meta i { color: #60a5fa; }
    .badge-seance { border-radius: 20px; padding: 0.35rem 0.7rem; font-weight: 700; font-size: 0.78rem; }
    .badge-presentiel { background: rgba(16, 185, 129, 0.14); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.25); }
    .badge-online { background: rgba(37, 99, 235, 0.14); color: #60a5fa; border: 1px solid rgba(37, 99, 235, 0.25); }
    .badge-status { background: rgba(255, 255, 255, 0.08); color: #e2e8f0; border: 1px solid rgba(255, 255, 255, 0.12); }
    .badge-present { background: rgba(16, 185, 129, 0.18); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.35); }
    .badge-absent { background: rgba(239, 68, 68, 0.18); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.35); }
    .badge-late { background: rgba(245, 158, 11, 0.18); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.35); }
    .badge-excused { background: rgba(99, 102, 241, 0.18); color: #a5b4fc; border: 1px solid rgba(99, 102, 241, 0.35); }
    .btn-meet {
        background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 0.55rem 1rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-meet:hover { color: #fff; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.35); }
    .empty-seances {
        text-align: center;
        color: #94a3b8;
        padding: 3rem 1rem;
        background: rgba(11, 18, 32, 0.6);
        border-radius: 16px;
        border: 1px dashed rgba(191, 219, 254, 0.18);
    }
    .empty-seances i { font-size: 3rem; color: #64748b; margin-bottom: 1rem; }
    .section-title { color: #fff; font-weight: 800; margin: 2rem 0 1rem 0; }
</style>
@endpush

@section('content')
<div class="seances-page">
    <div class="seances-hero">
        <h1><i class="fas fa-chalkboard-user me-2"></i>Mes séances</h1>
        <p>Consultez les séances de votre formation : présentiel ou Google Meet.</p>
    </div>

    @php
        $now = now();
        $upcoming = $seances->where('scheduled_at', '>=', $now)->sortBy('scheduled_at')->values();
        $past = $seances->where('scheduled_at', '<', $now)->sortByDesc('scheduled_at')->values();
    @endphp

    <h2 class="section-title"><i class="fas fa-calendar-day me-2"></i>Séances à venir</h2>
    @if($upcoming->isEmpty())
        <div class="empty-seances">
            <i class="fas fa-calendar-check"></i>
            <h4>Aucune séance à venir</h4>
            <p>Votre formateur publiera les prochaines séances ici.</p>
        </div>
    @else
        @foreach($upcoming as $seance)
            @php $attendance = $attendances[$seance->id] ?? null; @endphp
            <div class="seance-card">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div class="seance-title">{{ $seance->title }}</div>
                    <div>
                        @if($seance->type === 'online')
                            <span class="badge-seance badge-online"><i class="fas fa-video me-1"></i>En ligne</span>
                        @else
                            <span class="badge-seance badge-presentiel"><i class="fas fa-map-marker-alt me-1"></i>Présentiel</span>
                        @endif
                    </div>
                </div>
                <div class="seance-meta">
                    <span><i class="far fa-clock me-1"></i>{{ $seance->scheduled_at->format('d/m/Y H:i') }}</span>
                    <span><i class="fas fa-hourglass-half me-1"></i>{{ $seance->duration_minutes }} min</span>
                    @if($seance->type === 'presentiel' && $seance->location)
                        <span><i class="fas fa-map-pin me-1"></i>{{ $seance->location }}</span>
                    @endif
                </div>
                @if($seance->description)
                    <p class="text-light-emphasis mb-3">{{ $seance->description }}</p>
                @endif
                <div class="d-flex gap-2 flex-wrap align-items-center">
                    @if($seance->type === 'online' && !empty($seance->meet_link))
                        <a href="{{ $seance->meet_link }}" target="_blank" rel="noopener" class="btn-meet">
                            <i class="fas fa-video"></i> Rejoindre le Meet
                        </a>
                    @endif
                    @if($attendance)
                        <span class="badge-seance badge-status badge-{{ $attendance->status }}">
                            @if($attendance->status === 'present') Présent
                            @elseif($attendance->status === 'absent') Absent
                            @elseif($attendance->status === 'late') En retard
                            @elseif($attendance->status === 'excused') Excusé
                            @endif
                        </span>
                    @endif
                </div>
            </div>
        @endforeach
    @endif

    <h2 class="section-title"><i class="fas fa-history me-2"></i>Séances passées</h2>
    @if($past->isEmpty())
        <div class="empty-seances">
            <i class="fas fa-history"></i>
            <h4>Aucune séance passée</h4>
            <p>Retrouvez ici l'historique de vos séances une fois qu'elles auront lieu.</p>
        </div>
    @else
        @foreach($past as $seance)
            @php $attendance = $attendances[$seance->id] ?? null; @endphp
            <div class="seance-card">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div class="seance-title">{{ $seance->title }}</div>
                    <div>
                        @if($seance->type === 'online')
                            <span class="badge-seance badge-online"><i class="fas fa-video me-1"></i>En ligne</span>
                        @else
                            <span class="badge-seance badge-presentiel"><i class="fas fa-map-marker-alt me-1"></i>Présentiel</span>
                        @endif
                    </div>
                </div>
                <div class="seance-meta">
                    <span><i class="far fa-clock me-1"></i>{{ $seance->scheduled_at->format('d/m/Y H:i') }}</span>
                    <span><i class="fas fa-hourglass-half me-1"></i>{{ $seance->duration_minutes }} min</span>
                    @if($seance->type === 'presentiel' && $seance->location)
                        <span><i class="fas fa-map-pin me-1"></i>{{ $seance->location }}</span>
                    @endif
                </div>
                <div class="d-flex gap-2 flex-wrap align-items-center mt-3">
                    @if($attendance)
                        <span class="badge-seance badge-{{ $attendance->status }}">
                            @if($attendance->status === 'present') Présent
                            @elseif($attendance->status === 'absent') Absent
                            @elseif($attendance->status === 'late') En retard
                            @elseif($attendance->status === 'excused') Excusé
                            @endif
                        </span>
                        @if($attendance->recorded_at)
                            <span class="text-muted small">marqué le {{ $attendance->recorded_at->format('d/m/Y H:i') }}</span>
                        @endif
                    @else
                        <span class="badge-seance badge-status">Pas encore marqué</span>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
