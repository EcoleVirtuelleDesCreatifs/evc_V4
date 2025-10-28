@extends('layouts.ki-admin')

@section('title', $event->title . ' - EVC 2024')
@section('page-title', 'Détails de l\'événement')

@section('content')
<div class="container-fluid">
    <!-- Bouton retour -->
    <div class="mb-4">
        <a href="{{ str_replace('/' . $event->id, '/index', url()->current()) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Retour aux événements
        </a>
    </div>

    <!-- Image de couverture -->
    @if($event->cover_image)
    <div class="row mb-4">
        <div class="col-12">
            <div class="event-cover-image">
                <img src="{{ asset('storage/' . $event->cover_image) }}" 
                     alt="{{ $event->title }}"
                     class="w-100">
                @if($event->is_featured)
                    <div class="featured-badge-large">
                        <i class="fas fa-star me-2"></i>
                        À LA UNE
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Contenu principal -->
    <div class="row">
        <!-- Colonne principale -->
        <div class="col-lg-8">
            <!-- Titre et description -->
            <div class="card mb-4">
                <div class="card-body">
                    <h1 class="event-title">{{ $event->title }}</h1>
                    <p class="event-excerpt">{{ $event->excerpt }}</p>
                    
                    <div class="event-meta">
                        <span class="meta-item">
                            <i class="fas fa-eye me-1"></i>
                            {{ $event->views_count }} vues
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-calendar me-1"></i>
                            Publié le {{ \Carbon\Carbon::parse($event->published_at)->format('d/m/Y') }}
                        </span>
                        @if($event->author)
                            <span class="meta-item">
                                <i class="fas fa-user me-1"></i>
                                Par {{ $event->author->name }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contenu de l'événement -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-align-left me-2"></i>Description complète</h5>
                </div>
                <div class="card-body event-content">
                    {!! $event->content !!}
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Informations de l'événement -->
            <div class="card mb-4 event-info-card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations</h5>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <div class="info-icon calendar-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Date de début</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y à H:i') }}</span>
                        </div>
                    </div>

                    @if($event->event_end_date)
                    <div class="info-item">
                        <div class="info-icon calendar-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Date de fin</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($event->event_end_date)->format('d/m/Y à H:i') }}</span>
                        </div>
                    </div>
                    @endif

                    @if($event->location)
                    <div class="info-item">
                        <div class="info-icon location-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Lieu</span>
                            <span class="info-value">{{ $event->location }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="info-item">
                        <div class="info-icon type-icon">
                            <i class="fas fa-{{ $event->event_type == 'online' ? 'video' : ($event->event_type == 'physical' ? 'users' : 'globe') }}"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Type d'événement</span>
                            <span class="info-value">
                                @if($event->event_type == 'physical')
                                    Présentiel
                                @elseif($event->event_type == 'online')
                                    En ligne
                                @else
                                    Hybride
                                @endif
                            </span>
                        </div>
                    </div>

                    @if($event->registration_link)
                    <div class="mt-4">
                        <a href="{{ $event->registration_link }}" 
                           target="_blank" 
                           class="btn btn-success w-100 btn-lg">
                            <i class="fas fa-user-plus me-2"></i>
                            S'inscrire à l'événement
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Partage -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-share-alt me-2"></i>Partager</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" onclick="shareOnFacebook()">
                            <i class="fab fa-facebook me-2"></i>Facebook
                        </button>
                        <button class="btn btn-outline-info" onclick="shareOnTwitter()">
                            <i class="fab fa-twitter me-2"></i>Twitter
                        </button>
                        <button class="btn btn-outline-success" onclick="shareOnWhatsApp()">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp
                        </button>
                        <button class="btn btn-outline-secondary" onclick="copyLink()">
                            <i class="fas fa-link me-2"></i>Copier le lien
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.event-cover-image {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.event-cover-image img {
    max-height: 500px;
    object-fit: contain;
    background: #f8fafc;
}

.featured-badge-large {
    position: absolute;
    top: 20px;
    left: 20px;
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: #1f2937;
    padding: 1rem 2rem;
    border-radius: 50px;
    font-weight: 700;
    font-size: 1.1rem;
    box-shadow: 0 4px 15px rgba(251, 191, 36, 0.5);
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.event-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 1rem;
}

.event-excerpt {
    font-size: 1.25rem;
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.event-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    padding-top: 1rem;
    border-top: 2px solid #e2e8f0;
}

.meta-item {
    color: #64748b;
    font-size: 0.95rem;
}

.meta-item i {
    color: var(--primary-color);
}

.event-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #374151;
}

.event-content h1, .event-content h2, .event-content h3 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: #1f2937;
}

.event-content ul, .event-content ol {
    margin: 1rem 0;
    padding-left: 2rem;
}

.event-content a {
    color: var(--primary-color);
    text-decoration: underline;
}

.event-info-card .info-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 12px;
}

.info-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    margin-right: 1rem;
    flex-shrink: 0;
}

.calendar-icon {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
}

.location-icon {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.type-icon {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.info-content {
    display: flex;
    flex-direction: column;
}

.info-label {
    font-size: 0.75rem;
    color: #94a3b8;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
}

.info-value {
    font-size: 1rem;
    color: #1f2937;
    font-weight: 600;
}
</style>

<script>
function shareOnFacebook() {
    window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(window.location.href), '_blank');
}

function shareOnTwitter() {
    window.open('https://twitter.com/intent/tweet?url=' + encodeURIComponent(window.location.href) + '&text=' + encodeURIComponent('{{ $event->title }}'), '_blank');
}

function shareOnWhatsApp() {
    window.open('https://wa.me/?text=' + encodeURIComponent('{{ $event->title }} - ' + window.location.href), '_blank');
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        alert('Lien copié dans le presse-papiers !');
    });
}
</script>
@endsection
