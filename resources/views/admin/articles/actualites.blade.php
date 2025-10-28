@extends('layouts.admin')

@section('title', 'Gestion des Actualités')
@section('page-title', 'Gestion des Actualités')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white mb-0">
            <i class="fas fa-newspaper me-2"></i>Gestion des Actualités
        </h1>
        <a href="{{ route('admin.articles.actualites.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Créer une Actualité
        </a>
    </div>


    <!-- Cartes de statistiques -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="stat-card stat-card-primary">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-newspaper me-2"></i>Actualités récentes
                </h5>
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
                    <h3 class="stat-number">{{ $stats['published'] }}</h3>
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
                    <h3 class="stat-number">{{ $stats['draft'] }}</h3>
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
                            <th>Catégorie</th>
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
                        @forelse ($actualites as $actualite)
                            <tr>
                                <td>
                                    @if($actualite->cover_image)
                                        <img src="{{ asset('storage/' . $actualite->cover_image) }}"
                                             alt="{{ $actualite->title }}"
                                             class="img-thumbnail"
                                             style="width: 160px; height: 100px; object-fit: cover; border-radius: 8px;">
                                    @else
                                        <div class="bg-secondary d-flex align-items-center justify-content-center"
                                             style="width: 160px; height: 100px; border-radius: 8px;">
                                            <i class="fas fa-image text-muted fa-3x"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $actualite->title ?? $actualite->titre ?? 'Sans titre' }}</td>
                                <td>
                                    @php
                                        $categoryLabels = [
                                            'general' => ['label' => 'Général', 'color' => 'secondary', 'icon' => 'newspaper'],
                                            'formation' => ['label' => 'Formation', 'color' => 'primary', 'icon' => 'graduation-cap'],
                                            'evenement' => ['label' => 'Événement', 'color' => 'info', 'icon' => 'calendar-alt'],
                                            'partenariat' => ['label' => 'Partenariat', 'color' => 'success', 'icon' => 'handshake'],
                                            'succes' => ['label' => 'Succès', 'color' => 'warning', 'icon' => 'trophy'],
                                        ];
                                        $category = $categoryLabels[$actualite->category] ?? ['label' => 'N/A', 'color' => 'secondary', 'icon' => 'tag'];
                                    @endphp
                                    <span class="badge bg-{{ $category['color'] }}">
                                        <i class="fas fa-{{ $category['icon'] }} me-1"></i>{{ $category['label'] }}
                                    </span>
                                </td>
                                <td>
                                    @if(isset($actualite->status))
                                        @if($actualite->status === 'published')
                                            <span class="badge bg-success">Publié</span>
                                        @elseif($actualite->status === 'draft')
                                            <span class="badge bg-warning">Brouillon</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $actualite->status }}</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($actualite->is_featured)
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
                                    @if($actualite->visibility == 'public')
                                        <span class="badge bg-info">
                                            <i class="fas fa-globe me-1"></i>Visiteurs
                                        </span>
                                    @elseif($actualite->visibility == 'all')
                                        <span class="badge bg-primary">
                                            <i class="fas fa-users me-1"></i>Toutes formations
                                        </span>
                                    @else
                                        @php
                                            // Handle both JSON string and array
                                            $formationsIds = is_array($actualite->formations) 
                                                ? $actualite->formations 
                                                : (json_decode($actualite->formations, true) ?? []);
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
                                    @if($actualite->author)
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                 style="width: 32px; height: 32px; font-size: 0.75rem;">
                                                {{ strtoupper(substr($actualite->author->name, 0, 1)) }}
                                            </div>
                                            <span>{{ $actualite->author->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">
                                        <i class="fas fa-eye me-1"></i>{{ $actualite->views_count ?? 0 }}
                                    </span>
                                </td>
                                <td>{{ isset($actualite->created_at) ? \Carbon\Carbon::parse($actualite->created_at)->format('d/m/Y H:i') : 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('admin.articles.actualites.show', $actualite->id) }}" class="btn btn-sm btn-outline-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.articles.actualites.edit', $actualite->id) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.articles.actualites.toggle-featured', $actualite->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $actualite->is_featured ? 'success' : 'secondary' }}" title="{{ $actualite->is_featured ? 'Retirer de la une' : 'Mettre à la une' }}">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.articles.actualites.destroy', $actualite->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');">
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
                                <td colspan="10" class="text-center py-4">
                                    <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                                    <p class="text-white mb-0">Aucune actualité trouvée.</p>
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
        $actualitesPasses = $actualites->filter(function($event) use ($now) {
            return \Carbon\Carbon::parse($event->event_date)->isPast() && 
                   !\Carbon\Carbon::parse($event->event_date)->isToday();
        });
    @endphp
    
    @if($actualitesPasses->count() > 0)
    <div class="card mt-4" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header" style="background: linear-gradient(135deg, #64748b 0%, #475569 100%); border-bottom: 2px solid #94a3b8;">
            <h5 class="mb-0 text-white">
                <i class="fas fa-archive me-2"></i>Actualités archivées
                <span class="badge bg-secondary ms-2">{{ $actualitesPasses->count() }}</span>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th style="width: 180px;">Image</th>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Statut</th>
                            <th>Destinataires</th>
                            <th>Auteur</th>
                            <th style="width: 80px;">Vues</th>
                            <th>Créé le</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($actualitesPasses as $actualite)
                            <tr style="opacity: 0.7;">
                                <td>
                                    @if($actualite->cover_image)
                                        <img src="{{ asset('storage/' . $actualite->cover_image) }}"
                                             alt="{{ $actualite->title }}"
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
                                    <div class="text-white">{{ $actualite->title }}</div>
                                    <small class="text-muted">{{ Str::limit($actualite->excerpt, 50) }}</small>
                                </td>
                                <td>
                                    @php
                                        $categoryLabels = [
                                            'general' => ['label' => 'Général', 'color' => 'secondary', 'icon' => 'newspaper'],
                                            'formation' => ['label' => 'Formation', 'color' => 'primary', 'icon' => 'graduation-cap'],
                                            'evenement' => ['label' => 'Événement', 'color' => 'info', 'icon' => 'calendar-alt'],
                                            'partenariat' => ['label' => 'Partenariat', 'color' => 'success', 'icon' => 'handshake'],
                                            'succes' => ['label' => 'Succès', 'color' => 'warning', 'icon' => 'trophy'],
                                        ];
                                        $category = $categoryLabels[$actualite->category] ?? ['label' => 'N/A', 'color' => 'secondary', 'icon' => 'tag'];
                                    @endphp
                                    <span class="badge bg-{{ $category['color'] }}">
                                        <i class="fas fa-{{ $category['icon'] }} me-1"></i>{{ $category['label'] }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-check-circle me-1"></i>Terminé
                                    </span>
                                </td>
                                <td>
                                    @if($actualite->visibility == 'public')
                                        <span class="badge bg-info">
                                            <i class="fas fa-globe me-1"></i>Visiteurs
                                        </span>
                                    @elseif($actualite->visibility == 'all')
                                        <span class="badge bg-primary">
                                            <i class="fas fa-users me-1"></i>Toutes formations
                                        </span>
                                    @else
                                        @php
                                            $formationsIds = is_array($actualite->formations) 
                                                ? $actualite->formations 
                                                : (json_decode($actualite->formations, true) ?? []);
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
                                    @if($actualite->author)
                                        <div class="d-flex align-items-center">
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                 style="width: 32px; height: 32px; font-size: 0.75rem;">
                                                {{ strtoupper(substr($actualite->author->name, 0, 1)) }}
                                            </div>
                                            <span class="text-white">{{ $actualite->author->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-dark">
                                        <i class="fas fa-eye me-1"></i>{{ $actualite->views_count ?? 0 }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $actualite->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.articles.actualites.show', $actualite->id) }}" class="btn btn-sm btn-outline-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.articles.actualites.edit', $actualite->id) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.articles.actualites.destroy', $actualite->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement passé ?');">
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
