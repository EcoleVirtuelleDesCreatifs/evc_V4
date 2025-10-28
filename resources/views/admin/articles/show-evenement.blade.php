@extends('layouts.admin')

@section('title', 'Détails de l\'Événement')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="text-white mb-1">
                <i class="fas fa-calendar-alt me-2"></i>{{ $evenement->title }}
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-user me-1"></i>Par {{ $evenement->author->name ?? 'Administrateur' }} 
                • <i class="fas fa-clock me-1"></i>{{ $evenement->created_at->format('d/m/Y à H:i') }}
            </p>
        </div>
        <div>
            <a href="{{ route('admin.articles.evenements.edit', $evenement->id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Modifier
            </a>
            <a href="{{ route('admin.articles.evenements') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Colonne principale -->
        <div class="col-lg-8">
            <!-- Image de couverture -->
            @if($evenement->cover_image)
            <div class="card modern-card mb-4">
                <div class="card-body p-0">
                    <img src="{{ asset('storage/' . $evenement->cover_image) }}" 
                         alt="{{ $evenement->cover_image_alt ?? $evenement->title }}" 
                         class="img-fluid w-100" style="border-radius: 12px; object-fit: contain; max-height: 500px;">
                </div>
            </div>
            @endif

            <!-- Contenu -->
            <div class="card modern-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-align-left me-2"></i>Description</h5>
                </div>
                <div class="card-body">
                    <div class="event-excerpt mb-4">
                        <p class="lead text-white">{{ $evenement->excerpt }}</p>
                    </div>
                    <hr style="border-color: #334155;">
                    <div class="event-content text-white">
                        {!! $evenement->content !!}
                    </div>
                </div>
            </div>

            <!-- Détails de l'événement -->
            <div class="card modern-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Détails de l'événement</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Date de début</label>
                            <p class="text-white mb-0">
                                <i class="fas fa-calendar me-2"></i>{{ $evenement->event_date->format('d/m/Y') }}
                            </p>
                        </div>
                        @if($evenement->event_end_date)
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Date de fin</label>
                            <p class="text-white mb-0">
                                <i class="fas fa-calendar-check me-2"></i>{{ $evenement->event_end_date->format('d/m/Y') }}
                            </p>
                        </div>
                        @endif
                        @if($evenement->location)
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Lieu</label>
                            <p class="text-white mb-0">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $evenement->location }}
                            </p>
                        </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Type</label>
                            <p class="text-white mb-0">
                                @if($evenement->event_type === 'online')
                                    <i class="fas fa-laptop me-2"></i>En ligne
                                @elseif($evenement->event_type === 'physical')
                                    <i class="fas fa-building me-2"></i>Présentiel
                                @else
                                    <i class="fas fa-globe me-2"></i>Hybride
                                @endif
                            </p>
                        </div>
                        @if($evenement->registration_link)
                        <div class="col-12 mb-3">
                            <label class="text-muted small">Lien d'inscription</label>
                            <p class="text-white mb-0">
                                <a href="{{ $evenement->registration_link }}" target="_blank" class="text-info">
                                    <i class="fas fa-external-link-alt me-2"></i>{{ $evenement->registration_link }}
                                </a>
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- SEO -->
            @if($evenement->meta_title || $evenement->meta_description || $evenement->meta_keywords)
            <div class="card modern-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-search me-2"></i>Informations SEO</h5>
                </div>
                <div class="card-body">
                    @if($evenement->meta_title)
                    <div class="mb-3">
                        <label class="text-muted small">Meta Title</label>
                        <p class="text-white mb-0">{{ $evenement->meta_title }}</p>
                    </div>
                    @endif
                    @if($evenement->meta_description)
                    <div class="mb-3">
                        <label class="text-muted small">Meta Description</label>
                        <p class="text-white mb-0">{{ $evenement->meta_description }}</p>
                    </div>
                    @endif
                    @if($evenement->meta_keywords)
                    <div class="mb-3">
                        <label class="text-muted small">Mots-clés</label>
                        <p class="text-white mb-0">{{ $evenement->meta_keywords }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Colonne latérale -->
        <div class="col-lg-4">
            <!-- Statut -->
            <div class="card modern-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Statut</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Publication</label>
                        <p class="mb-0">
                            @if($evenement->status === 'published')
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Publié
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="fas fa-file-alt me-1"></i>Brouillon
                                </span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">À la une</label>
                        <p class="mb-0">
                            @if($evenement->is_featured)
                                <span class="badge bg-success">
                                    <i class="fas fa-star me-1"></i>Oui
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-star me-1"></i>Non
                                </span>
                            @endif
                        </p>
                    </div>

                    @if($evenement->published_at)
                    <div class="mb-3">
                        <label class="text-muted small">Date de publication</label>
                        <p class="text-white mb-0">{{ $evenement->published_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="text-muted small">Vues</label>
                        <p class="text-white mb-0">
                            <i class="fas fa-eye me-2"></i>{{ number_format($evenement->views_count) }}
                        </p>
                    </div>

                    <!-- Actions rapides -->
                    <hr style="border-color: #334155;">
                    <div class="d-grid gap-2">
                        <form action="{{ route('admin.articles.evenements.toggle-status', $evenement->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-outline-{{ $evenement->status === 'published' ? 'warning' : 'success' }} w-100">
                                @if($evenement->status === 'published')
                                    <i class="fas fa-file-alt me-1"></i>Mettre en brouillon
                                @else
                                    <i class="fas fa-check-circle me-1"></i>Publier
                                @endif
                            </button>
                        </form>

                        <form action="{{ route('admin.articles.evenements.toggle-featured', $evenement->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-outline-{{ $evenement->is_featured ? 'secondary' : 'success' }} w-100">
                                @if($evenement->is_featured)
                                    <i class="fas fa-star me-1"></i>Retirer de la une
                                @else
                                    <i class="fas fa-star me-1"></i>Mettre à la une
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Visibilité -->
            <div class="card modern-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Visibilité</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Destinataires</label>
                        <p class="text-white mb-0">
                            @if($evenement->visibility === 'all')
                                <i class="fas fa-globe me-2"></i>Toutes les formations
                            @else
                                <i class="fas fa-users me-2"></i>Formations spécifiques
                            @endif
                        </p>
                    </div>

                    @if($evenement->visibility === 'specific' && $evenement->formations)
                    <div class="mb-3">
                        <label class="text-muted small">Formations concernées</label>
                        <ul class="list-unstyled mb-0">
                            @foreach($evenement->formations as $formationId)
                                @php
                                    $formation = \App\Models\Formation::find($formationId);
                                @endphp
                                @if($formation)
                                    <li class="text-white">
                                        <i class="fas fa-check-circle text-success me-2"></i>{{ $formation->name }}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informations techniques -->
            <div class="card modern-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Informations techniques</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Slug</label>
                        <p class="text-white mb-0"><code>{{ $evenement->slug }}</code></p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">ID</label>
                        <p class="text-white mb-0">{{ $evenement->id }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Créé le</label>
                        <p class="text-white mb-0">{{ $evenement->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Dernière modification</label>
                        <p class="text-white mb-0">{{ $evenement->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .modern-card {
        background-color: #1e293b;
        border: 1px solid #334155;
        border-radius: 16px;
        overflow: hidden;
    }

    .modern-card .card-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-bottom: 2px solid #4fc3f7;
        padding: 1rem 1.5rem;
    }

    .modern-card .card-header h5 {
        color: white;
        font-weight: 600;
    }

    .modern-card .card-body {
        padding: 1.5rem;
    }

    .event-content {
        line-height: 1.8;
        font-size: 1.05rem;
    }

    .btn-warning {
        background: linear-gradient(135deg, #ffa726 0%, #ff9800 100%);
        border: none;
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 167, 38, 0.4);
    }

    .btn-outline-secondary {
        border: 2px solid #334155;
        color: #cbd5e1;
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: #4fc3f7;
        color: #4fc3f7;
    }

    code {
        background-color: #0f172a;
        color: #4fc3f7;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.9rem;
    }
</style>
@endpush
@endsection
