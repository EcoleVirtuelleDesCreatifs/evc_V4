@extends('layouts.admin')

@section('title', 'Gestion des Événements')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white mb-0">
            <i class="fas fa-calendar-alt me-2"></i>Gestion des Événements
        </h1>
        <a href="{{ route('admin.articles.evenements.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Créer un Événement
        </a>
    </div>


    <!-- Cartes de statistiques -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['total'] }}</h3>
                    <p class="stat-label">Total Événements</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['publies'] }}</h3>
                    <p class="stat-label">Publiés</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['brouillons'] }}</h3>
                    <p class="stat-label">Brouillons</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des événements -->
    @php
        $now = \Carbon\Carbon::now();
    @endphp
    
    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th style="width: 180px;">Image</th>
                            <th>Titre</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th style="width: 100px;">À la une</th>
                            <th>Destinataires</th>
                            <th>Auteur</th>
                            <th style="width: 80px;">Vues</th>
                            <th>Créé le</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($evenements as $evenement)
                            <tr>
                                <td>
                                    @if($evenement->cover_image)
                                        <img src="{{ asset('storage/' . $evenement->cover_image) }}"
                                             alt="{{ $evenement->title }}"
                                             class="img-thumbnail"
                                             style="width: 160px; height: 100px; object-fit: cover; border-radius: 8px;">
                                    @else
                                        <div class="bg-secondary d-flex align-items-center justify-content-center"
                                             style="width: 160px; height: 100px; border-radius: 8px;">
                                            <i class="fas fa-image text-muted fa-3x"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $evenement->title ?? $evenement->titre ?? 'Sans titre' }}</td>
                                <td>{{ isset($evenement->event_date) ? \Carbon\Carbon::parse($evenement->event_date)->format('d/m/Y') : 'N/A' }}</td>
                                <td>
                                    @if(isset($evenement->status))
                                        @if($evenement->status === 'published')
                                            <span class="badge bg-success">Publié</span>
                                        @elseif($evenement->status === 'draft')
                                            <span class="badge bg-warning">Brouillon</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $evenement->status }}</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($evenement->is_featured)
                                        <span class="badge bg-success" style="font-size: 1rem;">
                                            <i class="fas fa-star"></i>
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            <i class="far fa-star"></i>
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($evenement->visibility == 'public')
                                        <span class="badge bg-info">
                                            <i class="fas fa-globe me-1"></i>Visiteurs
                                        </span>
                                    @elseif($evenement->visibility == 'all')
                                        <span class="badge bg-primary">
                                            <i class="fas fa-users me-1"></i>Toutes formations
                                        </span>
                                    @else
                                        @php
                                            // Handle both JSON string and array
                                            $formationsIds = is_array($evenement->formations) 
                                                ? $evenement->formations 
                                                : (json_decode($evenement->formations, true) ?? []);
                                            $formationsNames = [];
                                            foreach($formationsIds as $id) {
                                                switch($id) {
                                                    case 1: $formationsNames[] = 'Design Graphique'; break;
                                                    case 2: $formationsNames[] = 'Community Management'; break;
                                                    case 3: $formationsNames[] = 'Gestion Informatique'; break;
                                                    case 4: $formationsNames[] = 'Intelligence Artificielle'; break;
                                                }
                                            }
                                        @endphp
                                        <span class="badge bg-warning text-dark" title="{{ implode(', ', $formationsNames) }}">
                                            <i class="fas fa-user-graduate me-1"></i>{{ count($formationsNames) }} formation(s)
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($evenement->author)
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                 style="width: 32px; height: 32px; font-size: 0.75rem;">
                                                {{ strtoupper(substr($evenement->author->name, 0, 1)) }}
                                            </div>
                                            <span>{{ $evenement->author->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">
                                        <i class="fas fa-eye me-1"></i>{{ $evenement->views_count ?? 0 }}
                                    </span>
                                </td>
                                <td>{{ isset($evenement->created_at) ? \Carbon\Carbon::parse($evenement->created_at)->format('d/m/Y H:i') : 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('admin.articles.evenements.show', $evenement->id) }}" class="btn btn-sm btn-outline-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.articles.evenements.edit', $evenement->id) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.articles.evenements.toggle-featured', $evenement->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $evenement->is_featured ? 'success' : 'secondary' }}" title="{{ $evenement->is_featured ? 'Retirer de la une' : 'Mettre à la une' }}">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.articles.evenements.destroy', $evenement->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <p class="text-white mb-0">Aucun événement trouvé.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Historique des événements passés -->
    @php
        $eventsPasses = $evenements->filter(function($event) use ($now) {
            return \Carbon\Carbon::parse($event->event_date)->isPast() && 
                   !\Carbon\Carbon::parse($event->event_date)->isToday();
        });
    @endphp
    
    @if($eventsPasses->count() > 0)
    <div class="card mt-4" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header" style="background: linear-gradient(135deg, #64748b 0%, #475569 100%); border-bottom: 2px solid #94a3b8;">
            <h5 class="mb-0 text-white">
                <i class="fas fa-history me-2"></i>Historique des événements passés
                <span class="badge bg-secondary ms-2">{{ $eventsPasses->count() }}</span>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th style="width: 180px;">Image</th>
                            <th>Titre</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Destinataires</th>
                            <th>Auteur</th>
                            <th style="width: 80px;">Vues</th>
                            <th>Créé le</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eventsPasses as $evenement)
                            <tr style="opacity: 0.7;">
                                <td>
                                    @if($evenement->cover_image)
                                        <img src="{{ asset('storage/' . $evenement->cover_image) }}"
                                             alt="{{ $evenement->title }}"
                                             class="img-thumbnail"
                                             style="width: 160px; height: 100px; object-fit: cover; border-radius: 8px; filter: grayscale(30%);">
                                    @else
                                        <div class="bg-secondary d-flex align-items-center justify-content-center"
                                             style="width: 160px; height: 100px; border-radius: 8px;">
                                            <i class="fas fa-image text-muted fa-3x"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-white">{{ $evenement->title }}</div>
                                    <small class="text-muted">{{ Str::limit($evenement->excerpt, 50) }}</small>
                                </td>
                                <td>
                                    <span class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($evenement->event_date)->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-check-circle me-1"></i>Terminé
                                    </span>
                                </td>
                                <td>
                                    @if($evenement->visibility == 'public')
                                        <span class="badge bg-info">
                                            <i class="fas fa-globe me-1"></i>Visiteurs
                                        </span>
                                    @elseif($evenement->visibility == 'all')
                                        <span class="badge bg-primary">
                                            <i class="fas fa-users me-1"></i>Toutes formations
                                        </span>
                                    @else
                                        @php
                                            $formationsIds = is_array($evenement->formations) 
                                                ? $evenement->formations 
                                                : (json_decode($evenement->formations, true) ?? []);
                                            $formationsNames = [];
                                            foreach($formationsIds as $id) {
                                                switch($id) {
                                                    case 1: $formationsNames[] = 'Design Graphique'; break;
                                                    case 2: $formationsNames[] = 'Community Management'; break;
                                                    case 3: $formationsNames[] = 'Gestion Informatique'; break;
                                                    case 4: $formationsNames[] = 'Intelligence Artificielle'; break;
                                                }
                                            }
                                        @endphp
                                        <span class="badge bg-warning text-dark" title="{{ implode(', ', $formationsNames) }}">
                                            <i class="fas fa-user-graduate me-1"></i>{{ count($formationsNames) }} formation(s)
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($evenement->author)
                                        <div class="d-flex align-items-center">
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                 style="width: 32px; height: 32px; font-size: 0.75rem;">
                                                {{ strtoupper(substr($evenement->author->name, 0, 1)) }}
                                            </div>
                                            <span class="text-white">{{ $evenement->author->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-dark">
                                        <i class="fas fa-eye me-1"></i>{{ $evenement->views_count ?? 0 }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $evenement->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.articles.evenements.show', $evenement->id) }}" class="btn btn-sm btn-outline-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.articles.evenements.edit', $evenement->id) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.articles.evenements.destroy', $evenement->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement passé ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
    /* Utiliser les mêmes styles que la bibliothèque */
    .stat-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 2px solid #334155;
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
    }

    .stat-card-primary { color: #4fc3f7; }
    .stat-card-primary:hover { border-color: #4fc3f7; }

    .stat-card-success { color: #66bb6a; }
    .stat-card-success:hover { border-color: #66bb6a; }

    .stat-card-warning { color: #ffa726; }
    .stat-card-warning:hover { border-color: #ffa726; }

    .stat-icon {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: currentColor;
        flex-shrink: 0;
    }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin: 0;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.95rem;
        color: #94a3b8;
        margin: 0.5rem 0 0 0;
        font-weight: 500;
    }
</style>
@endpush
@endsection
