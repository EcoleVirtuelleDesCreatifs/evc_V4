@php
    $attendance = $attendances[$seance->id] ?? null;
    $clicked = $clicks[$seance->id] ?? null;
    $canJoin = in_array($seance->type, ['online', 'hybrid']) && !empty($seance->meet_link);
    $canQr = in_array($seance->type, ['onsite', 'hybrid']) && $seance->qrToken && $seance->qrToken->isValid();
@endphp
<div class="seance-card">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
        <div>
            <div class="seance-formation text-uppercase small fw-bold text-muted mb-1">{{ $seance->formation }}</div>
            <div class="seance-title">{{ $seance->title }}</div>
            @if($seance->module)
                <div class="text-muted small mb-1"><i class="fas fa-book me-1"></i>{{ $seance->module }}</div>
            @endif
        </div>
        <div class="d-flex flex-wrap gap-2">
            @if($seance->type === 'online')
                <span class="badge-seance badge-online"><i class="fas fa-video me-1"></i>En ligne</span>
            @elseif($seance->type === 'hybrid')
                <span class="badge-seance badge-hybrid"><i class="fas fa-layer-group me-1"></i>Hybride</span>
            @else
                <span class="badge-seance badge-onsite"><i class="fas fa-map-marker-alt me-1"></i>Présentiel</span>
            @endif

            @if($seance->status === 'scheduled')
                <span class="badge-seance badge-scheduled">Planifiée</span>
            @elseif($seance->status === 'ongoing')
                <span class="badge-seance badge-ongoing"><i class="fas fa-circle text-danger me-1 small" style="font-size: 0.5rem;"></i>En cours</span>
            @elseif($seance->status === 'completed')
                <span class="badge-seance badge-completed">Terminée</span>
            @else
                <span class="badge-seance badge-cancelled">Annulée</span>
            @endif
        </div>
    </div>
    <div class="seance-meta">
        <span><i class="far fa-user me-1"></i>{{ $seance->formateur ?: 'Formateur non défini' }}</span>
        <span><i class="far fa-calendar me-1"></i>{{ $seance->scheduled_at->format('d/m/Y') }}</span>
        <span><i class="far fa-clock me-1"></i>{{ $seance->scheduled_at->format('H:i') }} - {{ $seance->ends_at ? $seance->ends_at->format('H:i') : '--:--' }}</span>
        <span><i class="fas fa-hourglass-half me-1"></i>{{ $seance->duration_minutes }} min</span>
        @if($seance->location)
            <span><i class="fas fa-map-pin me-1"></i>{{ $seance->location }}</span>
        @endif
    </div>
    @if($seance->description)
        <p class="text-light-emphasis mb-3">{{ $seance->description }}</p>
    @endif
    <div class="d-flex gap-2 flex-wrap align-items-center mt-2">
        @if($canJoin)
            <form method="POST" action="{{ route($routePrefix . '.seances.meet-click', $seance->id) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn-meet" {{ $clicked ? 'disabled' : '' }}>
                    <i class="fas fa-video"></i> Rejoindre Google Meet
                </button>
            </form>
            @if($clicked)
                <span class="text-muted small">cliqué le {{ $clicked->clicked_at->format('d/m/Y H:i') }}</span>
            @endif
        @endif

        @if($canQr)
            <a href="{{ route('pointage-qr', ['token' => $seance->qrToken->token]) }}" class="btn-qr" target="_blank">
                <i class="fas fa-qrcode"></i> Scanner ma présence
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
            @if($attendance->recorded_at)
                <span class="text-muted small">marqué le {{ $attendance->recorded_at->format('d/m/Y H:i') }}</span>
            @endif
        @endif
    </div>
</div>
