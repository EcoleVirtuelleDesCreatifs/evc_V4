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

    <!-- Section Webinaires -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-video me-2" style="color: var(--primary-color);"></i>
                        Webinaires
                    </h5>
                    <span class="badge" style="background-color: var(--primary-color); color: white;">4 webinaires</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Webinaire</th>
                                    <th>Catégorie</th>
                                    <th>Date & Heure</th>
                                    <th>Durée</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div>
                                            <strong>Photoshop CC 2024 - Nouvelles fonctionnalités</strong>
                                            <br><small class="text-muted">Découvrez les dernières fonctionnalités</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info">Photoshop</span></td>
                                    <td><small>30 Juillet 2024<br>14:00</small></td>
                                    <td><small>60 min</small></td>
                                    <td><span class="badge" style="background-color: var(--primary-color); color: white;">Inscrit</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-success me-1">
                                            <i class="fas fa-video"></i> Rejoindre
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-calendar"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <strong>Strategy Business - Tendances 2024</strong>
                                            <br><small class="text-muted">Nouvelles tendances en stratégie d'entreprise</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-warning">Strategy</span></td>
                                    <td><small>5 Août 2024<br>16:00</small></td>
                                    <td><small>90 min</small></td>
                                    <td><span class="badge bg-secondary">Disponible</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-1">
                                            <i class="fas fa-user-plus"></i> S'inscrire
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-info"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <strong>InDesign Avancé - Mise en page pro</strong>
                                            <br><small class="text-muted">Techniques avancées de mise en page</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success">InDesign</span></td>
                                    <td><small>12 Août 2024<br>10:00</small></td>
                                    <td><small>75 min</small></td>
                                    <td><span class="badge bg-secondary">Disponible</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-1">
                                            <i class="fas fa-user-plus"></i> S'inscrire
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-info"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <strong>Illustrator - Création vectorielle</strong>
                                            <br><small class="text-muted">Maîtrisez les outils vectoriels</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-warning">Illustrator</span></td>
                                    <td><small>20 Août 2024<br>15:30</small></td>
                                    <td><small>60 min</small></td>
                                    <td><span class="badge bg-secondary">Disponible</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-1">
                                            <i class="fas fa-user-plus"></i> S'inscrire
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-info"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Lives -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-broadcast-tower me-2" style="color: var(--secondary-color);"></i>
                        Lives Programmés
                    </h5>
                    <span class="badge" style="background-color: var(--secondary-color); color: white;">2 lives</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Live</th>
                                    <th>Animateur</th>
                                    <th>Date & Heure</th>
                                    <th>Participants</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-danger">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-danger rounded-circle me-2" style="width: 8px; height: 8px;"></div>
                                            <div>
                                                <strong>Q&A Session - Infographie</strong>
                                                <br><small class="text-muted">Session questions/réponses avec l'expert</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://via.placeholder.com/32" class="rounded-circle me-2" width="32" height="32">
                                            <small>Prof. Martin</small>
                                        </div>
                                    </td>
                                    <td><small>Maintenant<br>En cours</small></td>
                                    <td><small>47 participants</small></td>
                                    <td><span class="badge bg-danger">EN DIRECT</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-danger me-1">
                                            <i class="fas fa-play"></i> Rejoindre
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-share"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <strong>Code Review Collectif</strong>
                                            <br><small class="text-muted">Révision de projets étudiants</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://via.placeholder.com/32" class="rounded-circle me-2" width="32" height="32">
                                            <small>Prof. Sophie</small>
                                        </div>
                                    </td>
                                    <td><small>Demain<br>10:00</small></td>
                                    <td><small>12 inscrits</small></td>
                                    <td><span class="badge" style="background-color: var(--warning-color); color: white;">Programmé</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-1">
                                            <i class="fas fa-bell"></i> Rappel
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-calendar"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Replays -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-play-circle me-2" style="color: var(--success-color);"></i>
                        Replays Disponibles
                    </h5>
                    <span class="badge" style="background-color: var(--success-color); color: white;">15 replays</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Replay</th>
                                    <th>Catégorie</th>
                                    <th>Date</th>
                                    <th>Durée</th>
                                    <th>Vues</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div>
                                            <strong>Photoshop - Retouche Portrait</strong>
                                            <br><small class="text-muted">Techniques avancées de retouche</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info">Photoshop</span></td>
                                    <td><small>20 Juillet 2024</small></td>
                                    <td><small>45 min</small></td>
                                    <td><small>1,234 vues</small></td>
                                    <td>
                                        <button class="btn btn-sm btn-success me-1">
                                            <i class="fas fa-play"></i> Regarder
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <strong>InDesign - Magazine Layout</strong>
                                            <br><small class="text-muted">Création d'un magazine professionnel</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success">InDesign</span></td>
                                    <td><small>15 Juillet 2024</small></td>
                                    <td><small>60 min</small></td>
                                    <td><small>2,156 vues</small></td>
                                    <td>
                                        <button class="btn btn-sm btn-success me-1">
                                            <i class="fas fa-play"></i> Regarder
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <strong>Illustrator - Logo Design</strong>
                                            <br><small class="text-muted">Création de logos vectoriels</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-warning">Illustrator</span></td>
                                    <td><small>10 Juillet 2024</small></td>
                                    <td><small>55 min</small></td>
                                    <td><small>1,789 vues</small></td>
                                    <td>
                                        <button class="btn btn-sm btn-success me-1">
                                            <i class="fas fa-play"></i> Regarder
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Événements Publiés -->
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
                                    <div class="card h-100 shadow-sm">
                                        @if($event->cover_image)
                                            <img src="{{ asset('storage/' . $event->cover_image) }}" 
                                                 class="card-img-top" 
                                                 alt="{{ $event->title }}"
                                                 style="height: 200px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary d-flex align-items-center justify-content-center" 
                                                 style="height: 200px;">
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
                                                    <span class="badge bg-primary">Présentiel</span>
                                                @elseif($event->event_type == 'online')
                                                    <span class="badge bg-success">En ligne</span>
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
                                                <button class="btn btn-sm btn-primary">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Détails
                                                </button>
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
                            <p class="text-muted">Aucun événement disponible pour le moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
