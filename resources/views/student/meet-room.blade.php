@extends('layouts.ki-admin')

@section('title', 'Salle de Réunion - Google Meet')

@push('styles')
<style>
    .meet-room-container {
        background: linear-gradient(135deg, #0b1220 0%, #0e1d3a 50%, #1e3a8a 100%);
        min-height: 100vh;
        padding: 2rem;
    }

    .meet-header {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
    }

    .meet-title {
        color: #fff;
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .meet-subtitle {
        color: #94a3b8;
        font-size: 0.9rem;
    }

    .meet-iframe-container {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        aspect-ratio: 16/9;
    }

    .meet-iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    .meet-info-panel {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 1.5rem;
        margin-top: 2rem;
    }

    .meet-timer {
        font-size: 2rem;
        font-weight: 700;
        color: #f97316;
        text-align: center;
        margin-bottom: 1rem;
    }

    .meet-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .status-connected {
        background: rgba(34, 197, 94, 0.2);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .status-loading {
        background: rgba(245, 158, 11, 0.2);
        color: #fbbf24;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .meet-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .btn-leave {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-leave:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(239, 68, 68, 0.3);
    }

    .btn-fullscreen {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-fullscreen:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .seance-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .detail-item {
        background: rgba(255, 255, 255, 0.05);
        padding: 1rem;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .detail-label {
        color: #94a3b8;
        font-size: 0.75rem;
        margin-bottom: 0.25rem;
    }

    .detail-value {
        color: #fff;
        font-weight: 600;
        font-size: 0.9rem;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .recording-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .recording-dot {
        width: 8px;
        height: 8px;
        background: #ef4444;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
</style>
@endpush

@section('content')
@php
    $routePrefix = explode('.', \Illuminate\Support\Facades\Route::currentRouteName())[0];
@endphp

<input type="hidden" id="seanceId" value="{{ $seance->id }}">
<input type="hidden" id="routePrefix" value="{{ $routePrefix }}">

<div class="meet-room-container">
    <!-- Header -->
    <div class="meet-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="meet-title">
                    <i class="fas fa-video me-2"></i>Salle de Réunion Jitsi Meet
                </h1>
                <p class="meet-subtitle">{{ $seance->title ?? 'Séance en cours' }}</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="meet-status status-connected">
                    <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                    Connecté
                </span>
                <div class="recording-indicator">
                    <span class="recording-dot"></span>
                    Enregistré
                </div>
            </div>
        </div>
    </div>

    <!-- Jitsi Meet Container -->
    <div class="meet-iframe-container">
        @if($seance->meet_link)
            <iframe
                src="{{ $seance->meet_link }}"
                class="meet-iframe"
                allow="camera; microphone; fullscreen; display-capture; autoplay; screen-wake-lock"
                allowfullscreen>
            </iframe>
        @else
            <div class="d-flex flex-column align-items-center justify-content-center h-100 text-center p-4">
                <i class="fas fa-video-slash text-gray-500 text-5xl mb-4"></i>
                <h3 class="text-white mb-2">Lien de réunion non disponible</h3>
                <p class="text-gray-400">Veuillez contacter votre formateur pour obtenir le lien de connexion.</p>
                <a href="{{ route($routePrefix . '.seances.index') }}" class="btn btn-primary mt-4">
                    <i class="fas fa-arrow-left me-2"></i>Retour aux séances
                </a>
            </div>
        @endif
    </div>

    <!-- Info Panel -->
    <div class="meet-info-panel">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="meet-timer" id="meetTimer">00:00:00</div>
                <p class="text-center text-gray-400 text-sm">Temps de connexion</p>
            </div>
            <div class="col-md-8">
                <div class="seance-details">
                    <div class="detail-item">
                        <div class="detail-label">Formateur</div>
                        <div class="detail-value">{{ $seance->formateur ?? 'Non défini' }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Date</div>
                        <div class="detail-value">{{ $seance->scheduled_at->format('d/m/Y') }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Heure</div>
                        <div class="detail-value">{{ $seance->scheduled_at->format('H:i') }} - {{ $seance->ends_at ? $seance->ends_at->format('H:i') : '--:--' }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Durée</div>
                        <div class="detail-value">{{ $seance->duration_minutes }} min</div>
                    </div>
                    @if($seance->location)
                    <div class="detail-item">
                        <div class="detail-label">Lieu</div>
                        <div class="detail-value">{{ $seance->location }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="meet-actions">
            <a href="{{ route($routePrefix . '.seances.index') }}" class="btn-leave">
                <i class="fas fa-sign-out-alt me-2"></i>Quitter la réunion
            </a>
        </div>
    </div>
</div>

<script>
    // Timer de connexion
    let startTime = new Date();
    let timerInterval;

    function updateTimer() {
        const now = new Date();
        const diff = now - startTime;

        const hours = Math.floor(diff / 3600000);
        const minutes = Math.floor((diff % 3600000) / 60000);
        const seconds = Math.floor((diff % 60000) / 1000);

        const formatted = String(hours).padStart(2, '0') + ':' +
                          String(minutes).padStart(2, '0') + ':' +
                          String(seconds).padStart(2, '0');

        document.getElementById('meetTimer').textContent = formatted;
    }

    function startTimer() {
        timerInterval = setInterval(updateTimer, 1000);
    }

    function stopTimer() {
        clearInterval(timerInterval);
    }

    function recordCheckOut() {
        const seanceId = document.getElementById('seanceId').value;
        const routePrefix = document.getElementById('routePrefix').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/${routePrefix}/seances/${seanceId}/check-out`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({})
        }).then(response => response.json())
        .then(data => {
            console.log('Check-out recorded:', data);
        }).catch(error => {
            console.error('Failed to record check-out:', error);
        });
    }

    // Démarrer le timer
    startTimer();

    // Quand l'utilisateur quitte la page
    window.addEventListener('beforeunload', function() {
        stopTimer();
        recordCheckOut();
    });

    // Quand l'utilisateur ferme l'onglet
    window.addEventListener('unload', function() {
        recordCheckOut();
    });
</script>
@endsection
