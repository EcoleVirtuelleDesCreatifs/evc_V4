@extends('layouts.ki-admin')

@section('title', 'Événements - EVC 2024')
@section('page-title', 'Événements')

@section('content')
<div class="container-fluid">
    <!-- Statistiques dynamiques -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center h-100 stat-card">
                <div class="card-body">
                    <i class="fas fa-calendar-alt fa-2x mb-2" style="color: var(--primary-color);"></i>
                    <h3 style="color: var(--primary-color);">{{ $stats['total'] }}</h3>
                    <small class="text-muted">Total Événements</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center h-100 stat-card">
                <div class="card-body">
                    <i class="fas fa-calendar-check fa-2x mb-2" style="color: var(--success-color);"></i>
                    <h3 style="color: var(--success-color);">{{ $stats['a_venir'] }}</h3>
                    <small class="text-muted">À venir</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center h-100 stat-card">
                <div class="card-body">
                    <i class="fas fa-history fa-2x mb-2" style="color: var(--secondary-color);"></i>
                    <h3 style="color: var(--secondary-color);">{{ $stats['passes'] }}</h3>
                    <small class="text-muted">Passés</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center h-100 stat-card">
                <div class="card-body">
                    <i class="fas fa-star fa-2x mb-2" style="color: var(--warning-color);"></i>
                    <h3 style="color: var(--warning-color);">{{ $stats['a_la_une'] }}</h3>
                    <small class="text-muted">À la une</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par type -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card text-center h-100 stat-card-type">
                <div class="card-body">
                    <i class="fas fa-video fa-2x mb-2" style="color: #4fc3f7;"></i>
                    <h4 style="color: #4fc3f7;">{{ $stats['en_ligne'] }}</h4>
                    <small class="text-muted">En ligne</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center h-100 stat-card-type">
                <div class="card-body">
                    <i class="fas fa-users fa-2x mb-2" style="color: #66bb6a;"></i>
                    <h4 style="color: #66bb6a;">{{ $stats['presentiel'] }}</h4>
                    <small class="text-muted">Présentiel</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center h-100 stat-card-type">
                <div class="card-body">
                    <i class="fas fa-globe fa-2x mb-2" style="color: #ffa726;"></i>
                    <h4 style="color: #ffa726;">{{ $stats['hybride'] }}</h4>
                    <small class="text-muted">Hybride</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Section À la une - Design Ultra Moderne -->
    @if($eventsFeatured->count() > 0)
    <div class="row mb-5">
        <div class="col-12">
            <div class="featured-section-modern">
                <!-- Header avec animation -->
                <div class="featured-header-modern">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="star-icon-container">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="ms-3">
                                <h2 class="mb-0 text-white fw-bold">Événements à la une</h2>
                                <p class="mb-0 text-white-50 small">Ne manquez pas ces événements exceptionnels</p>
                            </div>
                        </div>
                        <span class="badge-count">{{ $eventsFeatured->count() }}</span>
                    </div>
                </div>

                <!-- Contenu des événements -->
                <div class="featured-content-modern">
                    @foreach($eventsFeatured as $index => $event)
                        <div class="featured-event-card" style="animation-delay: {{ $index * 0.1 }}s;">
                            <div class="row g-0 h-100">
                                <!-- Image complète -->
                                <div class="col-md-5">
                                    <div class="featured-image-container">
                                        @if($event->cover_image)
                                            <img src="{{ asset('storage/' . $event->cover_image) }}" 
                                                 class="featured-image" 
                                                 alt="{{ $event->title }}">
                                        @else
                                            <div class="featured-image-placeholder">
                                                <i class="fas fa-calendar-alt fa-5x"></i>
                                            </div>
                                        @endif
                                        <div class="featured-badge">
                                            <i class="fas fa-star me-1"></i>
                                            <span>À LA UNE</span>
                                        </div>
                                        <div class="featured-overlay"></div>
                                    </div>
                                </div>

                                <!-- Contenu -->
                                <div class="col-md-7">
                                    <div class="featured-event-content">
                                        <div class="featured-event-header">
                                            <h3 class="featured-event-title">{{ $event->title }}</h3>
                                            <p class="featured-event-excerpt">{{ $event->excerpt }}</p>
                                        </div>

                                        <div class="featured-event-details">
                                            <div class="detail-item">
                                                <div class="detail-icon calendar-icon">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </div>
                                                <div class="detail-content">
                                                    <span class="detail-label">Date & Heure</span>
                                                    <span class="detail-value">{{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y à H:i') }}</span>
                                                </div>
                                            </div>

                                            @if($event->location)
                                            <div class="detail-item">
                                                <div class="detail-icon location-icon">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                </div>
                                                <div class="detail-content">
                                                    <span class="detail-label">Lieu</span>
                                                    <span class="detail-value">{{ $event->location }}</span>
                                                </div>
                                            </div>
                                            @endif

                                            <div class="detail-item">
                                                <div class="detail-icon type-icon">
                                                    <i class="fas fa-{{ $event->event_type == 'online' ? 'video' : ($event->event_type == 'physical' ? 'users' : 'globe') }}"></i>
                                                </div>
                                                <div class="detail-content">
                                                    <span class="detail-label">Type</span>
                                                    <span class="detail-value">
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
                                        </div>

                                        <div class="featured-event-actions">
                                            <a href="{{ str_replace('/index', '', url()->current()) }}/{{ $event->id }}" class="btn-featured-primary">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Voir les détails
                                            </a>
                                            @if($event->registration_link)
                                                <a href="{{ $event->registration_link }}" 
                                                   target="_blank" 
                                                   class="btn-featured-secondary">
                                                    <i class="fas fa-user-plus me-2"></i>
                                                    S'inscrire maintenant
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Section Événements à venir -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2" style="color: var(--primary-color);"></i>
                        Événements à venir
                    </h5>
                    <span class="badge" style="background-color: var(--primary-color); color: white;">{{ $events->count() }} événements</span>
                </div>
                <div class="card-body">
                    @if($events->count() > 0)
                        <div class="row">
                            @foreach($events as $event)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 shadow-sm event-card">
                                        @if($event->cover_image)
                                            <img src="{{ asset('storage/' . $event->cover_image) }}" 
                                                 class="card-img-top" 
                                                 alt="{{ $event->title }}"
                                                 style="height: 300px; object-fit: contain; background: #f8fafc; padding: 1rem;">
                                        @else
                                            <div class="bg-secondary d-flex align-items-center justify-content-center" 
                                                 style="height: 300px;">
                                                <i class="fas fa-calendar-alt fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $event->title }}</h5>
                                            <p class="card-text text-muted">{{ Str::limit($event->excerpt, 100) }}</p>
                                            
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y à H:i') }}
                                                </small>
                                            </div>
                                            
                                            @if($event->location)
                                                <div class="mb-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-map-marker-alt me-1"></i>
                                                        {{ $event->location }}
                                                    </small>
                                                </div>
                                            @endif
                                            
                                            <div class="mb-3">
                                                @if($event->event_type == 'physical')
                                                    <span class="badge bg-success">Présentiel</span>
                                                @elseif($event->event_type == 'online')
                                                    <span class="badge bg-primary">En ligne</span>
                                                @else
                                                    <span class="badge bg-info">Hybride</span>
                                                @endif
                                                
                                                @if($event->is_featured)
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-star"></i> À la une
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-footer bg-white">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="{{ str_replace('/index', '', url()->current()) }}/{{ $event->id }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Détails
                                                </a>
                                                @if($event->registration_link)
                                                    <a href="{{ $event->registration_link }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-success">
                                                        <i class="fas fa-user-plus me-1"></i>
                                                        S'inscrire
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun événement à venir pour le moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Section Événements passés (Replays) -->
    @if($eventsPasses->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2" style="color: var(--secondary-color);"></i>
                        Événements passés
                    </h5>
                    <span class="badge" style="background-color: var(--secondary-color); color: white;">{{ $eventsPasses->count() }} événements</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($eventsPasses as $event)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 shadow-sm event-card-past">
                                    @if($event->cover_image)
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $event->cover_image) }}" 
                                                 class="card-img-top" 
                                                 alt="{{ $event->title }}"
                                                 style="height: 200px; object-fit: cover; opacity: 0.7;">
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-secondary">Terminé</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-secondary d-flex align-items-center justify-content-center" 
                                             style="height: 200px; opacity: 0.7;">
                                            <i class="fas fa-calendar-alt fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title text-muted">{{ $event->title }}</h5>
                                        <p class="card-text text-muted small">{{ Str::limit($event->excerpt, 80) }}</p>
                                        
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y') }}
                                            </small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            @if($event->event_type == 'physical')
                                                <span class="badge bg-secondary">Présentiel</span>
                                            @elseif($event->event_type == 'online')
                                                <span class="badge bg-secondary">En ligne</span>
                                            @else
                                                <span class="badge bg-secondary">Hybride</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white">
                                        <a href="{{ str_replace('/index', '', url()->current()) }}/{{ $event->id }}" class="btn btn-sm btn-outline-secondary w-100">
                                            <i class="fas fa-eye me-1"></i>
                                            Voir les détails
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.stat-card, .stat-card-type {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    border-radius: 12px;
}

.stat-card:hover, .stat-card-type:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

/* Section À la une - Design Ultra Moderne */
.featured-section-modern {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    margin-bottom: 2rem;
}

.featured-header-modern {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #ea580c 100%);
    padding: 2rem;
    position: relative;
    overflow: hidden;
}

.featured-header-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M 20 0 L 0 0 0 20" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
}

.star-icon-container {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    animation: pulse-star 2s ease-in-out infinite;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.badge-count {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-size: 1.25rem;
    font-weight: 700;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.featured-content-modern {
    padding: 2rem;
}

.featured-event-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    animation: slideInUp 0.6s ease-out forwards;
    opacity: 0;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.featured-event-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 50px rgba(251, 191, 36, 0.4);
}

.featured-image-container {
    position: relative;
    height: 100%;
    min-height: 400px;
    overflow: hidden;
}

.featured-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    background: #f8fafc;
    transition: transform 0.6s ease;
}

.featured-event-card:hover .featured-image {
    transform: scale(1.05);
}

.featured-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.featured-badge {
    position: absolute;
    top: 20px;
    left: 20px;
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: #1f2937;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.9rem;
    box-shadow: 0 4px 15px rgba(251, 191, 36, 0.5);
    z-index: 10;
    animation: bounce 2s ease-in-out infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

.featured-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 50%;
    background: linear-gradient(to top, rgba(0,0,0,0.3), transparent);
    opacity: 0;
    transition: opacity 0.4s ease;
}

.featured-event-card:hover .featured-overlay {
    opacity: 1;
}

.featured-event-content {
    padding: 2.5rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}

.featured-event-header {
    margin-bottom: 2rem;
}

.featured-event-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 1rem;
    line-height: 1.3;
}

.featured-event-excerpt {
    color: #64748b;
    font-size: 1rem;
    line-height: 1.6;
    margin: 0;
}

.featured-event-details {
    margin-bottom: 2rem;
}

.detail-item {
    display: flex;
    align-items: center;
    margin-bottom: 1.25rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.detail-item:hover {
    background: #f1f5f9;
    transform: translateX(5px);
}

.detail-icon {
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

.detail-content {
    display: flex;
    flex-direction: column;
}

.detail-label {
    font-size: 0.75rem;
    color: #94a3b8;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
}

.detail-value {
    font-size: 1rem;
    color: #1f2937;
    font-weight: 600;
}

.featured-event-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn-featured-primary,
.btn-featured-secondary {
    flex: 1;
    min-width: 200px;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-featured-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
}

.btn-featured-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.6);
    color: white;
}

.btn-featured-secondary {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
}

.btn-featured-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.6);
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .featured-image-container {
        min-height: 300px;
    }
    
    .featured-event-content {
        padding: 1.5rem;
    }
    
    .featured-event-title {
        font-size: 1.5rem;
    }
    
    .btn-featured-primary,
    .btn-featured-secondary {
        min-width: 100%;
    }
}

/* Événements réguliers */
.event-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
}

.event-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.event-card .card-img-top {
    height: 300px;
    object-fit: contain;
    background: #f8fafc;
    padding: 1rem;
    width: 100%;
}

/* Événements passés */
.event-card-past {
    transition: transform 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
    opacity: 0.85;
}

.event-card-past:hover {
    opacity: 1;
    transform: translateY(-3px);
}

.card-img-top {
    border-radius: 12px 12px 0 0;
}

/* Animation pour les icônes */
@keyframes pulse-star {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

.featured-header i {
    animation: pulse-star 2s ease-in-out infinite;
}
</style>
@endsection
