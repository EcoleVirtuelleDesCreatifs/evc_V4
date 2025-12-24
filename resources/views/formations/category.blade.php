@extends('layouts.ki-admin')

@section('title', 'Formations ' . ucfirst($category) . ' - EVC 2024')
@section('page-title', 'Formations ' . ucfirst($category))

@section('content')
@php
    $routePrefix = 'design-graphique';
    $path = request()->path();
    if (preg_match('#^evc/compte/([^/]+)#', $path, $matches)) {
        $routePrefix = $matches[1];
    }
@endphp
<div class="row">
    <div class="col-12">
        <!-- En-tête de la catégorie -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            @if($category == 'photoshop')
                                <i class="fas fa-image fa-3x me-4" style="color: var(--primary-color);"></i>
                                <div>
                                    <h3 class="mb-1">Formations Photoshop</h3>
                                    <p class="text-muted mb-0">Maîtrisez la retouche photo et le design graphique avec Adobe Photoshop</p>
                                </div>
                            @elseif($category == 'illustrator')
                                <i class="fas fa-vector-square fa-3x me-4" style="color: var(--secondary-color);"></i>
                                <div>
                                    <h3 class="mb-1">Formations Illustrator</h3>
                                    <p class="text-muted mb-0">Créez des illustrations vectorielles professionnelles avec Adobe Illustrator</p>
                                </div>
                            @elseif($category == 'indesign')
                                <i class="fas fa-file-alt fa-3x me-4" style="color: var(--accent-color);"></i>
                                <div>
                                    <h3 class="mb-1">Formations InDesign</h3>
                                    <p class="text-muted mb-0">Concevez des mises en page professionnelles avec Adobe InDesign</p>
                                </div>
                            @else
                                <i class="fas fa-crown fa-3x me-4" style="color: var(--warning-color);"></i>
                                <div>
                                    <h3 class="mb-1">Master Class</h3>
                                    <p class="text-muted mb-0">Formations avancées et stratégie business pour les professionnels</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route($routePrefix . '.formations.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Retour
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-filter me-1"></i>
                                    Filtrer
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Tous les niveaux</a></li>
                                    <li><a class="dropdown-item" href="#">Débutant</a></li>
                                    <li><a class="dropdown-item" href="#">Intermédiaire</a></li>
                                    <li><a class="dropdown-item" href="#">Avancé</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques de la catégorie -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h4 style="color: var(--primary-color);">
                            {{ $stats['total'] ?? 0 }}
                        </h4>
                        <small class="text-muted">Formations disponibles</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h4 style="color: var(--success-color);">
                            {{ $stats['duration'] ?? 0 }} semaines
                        </h4>
                        <small class="text-muted">Durée totale</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h4 style="color: var(--warning-color);">
                            {{ number_format($stats['completion_rate'] ?? 0, 0) }}%
                        </h4>
                        <small class="text-muted">Taux de complétion</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h4 style="color: var(--accent-color);">
                            {{ $stats['new_this_week'] ?? 0 }}
                        </h4>
                        <small class="text-muted">Nouvelles cette semaine</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des formations -->
        <div class="row">
            @forelse($formations as $formation)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm" style="border-radius: 15px; overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''">

                        <!-- Image de la formation -->
                        <div class="position-relative" style="height: 200px; overflow: hidden;">
                            @if(isset($formation->image_url) && $formation->image_url)
                                <img src="{{ \App\Models\MediaUrl::fromPath($formation->image_url) }}" class="w-100 h-100" alt="{{ $formation->name }}" style="object-fit: cover;">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg,
                                    @if($category == 'photoshop') #667eea 0%, #764ba2 100%
                                    @elseif($category == 'illustrator') #f093fb 0%, #f5576c 100%
                                    @elseif($category == 'indesign') #4facfe 0%, #00f2fe 100%
                                    @else #fa709a 0%, #fee140 100%
                                    @endif);">
                                    <i class="fas fa-graduation-cap fa-4x text-white opacity-75"></i>
                                </div>
                            @endif

                            <!-- Badge du niveau -->
                            <span class="position-absolute top-0 end-0 m-3 badge bg-dark bg-opacity-75 px-3 py-2" style="border-radius: 10px; font-size: 0.85rem;">
                                {{ ucfirst($formation->level ?? 'débutant') }}
                            </span>
                        </div>

                        <!-- Corps de la carte -->
                        <div class="card-body d-flex flex-column p-4">
                            <!-- Titre de la formation -->
                            <h5 class="card-title fw-bold mb-3" style="color: #2c3e50; font-size: 1.1rem;">
                                {{ $formation->name }}
                            </h5>

                            <!-- Durée et informations -->
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <small class="text-muted d-flex align-items-center">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $formation->duration_weeks }} sem.
                                </small>
                                @if(isset($formation->is_featured) && $formation->is_featured)
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-star me-1"></i>Populaire
                                    </span>
                                @endif
                            </div>

                            <!-- Description -->
                            <p class="card-text text-muted flex-grow-1 mb-4" style="font-size: 0.9rem; line-height: 1.6;">
                                {{ Str::limit(strip_tags($formation->short_description ?? $formation->description), 110) }}
                            </p>

                            <!-- Boutons d'action -->
                            <div class="d-grid gap-2">
                                <a href="{{ route($routePrefix . '.formations.show', $formation->id) }}" class="btn btn-primary d-flex align-items-center justify-content-center gap-2" style="border-radius: 10px; padding: 12px; font-weight: 500;">
                                    <i class="fas fa-play-circle"></i>
                                    Voir la formation
                                </a>

                                <div class="d-flex gap-2">
                                    @if(!empty($formation->video_url) || !empty($formation->vimeo_code))
                                        <a href="{{ route($routePrefix . '.formations.download', $formation->id) }}" class="btn btn-outline-secondary flex-fill d-flex align-items-center justify-content-center gap-1" style="border-radius: 10px; font-size: 0.9rem;" title="Télécharger la vidéo" target="_blank">
                                            <i class="fas fa-download"></i>
                                            <span class="d-none d-md-inline">Télécharger</span>
                                        </a>
                                    @else
                                        <button class="btn btn-outline-secondary flex-fill d-flex align-items-center justify-content-center gap-1" style="border-radius: 10px; font-size: 0.9rem;" title="Vidéo non disponible" disabled>
                                            <i class="fas fa-download"></i>
                                            <span class="d-none d-md-inline">Télécharger</span>
                                        </button>
                                    @endif

                                    <button class="btn btn-outline-info flex-fill d-flex align-items-center justify-content-center gap-1" style="border-radius: 10px; font-size: 0.9rem;" title="Favoris">
                                        <i class="fas fa-bookmark"></i>
                                        <span class="d-none d-md-inline">Favoris</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Footer avec statistiques -->
                        <div class="card-footer bg-light border-0 py-3" style="border-radius: 0 0 15px 15px;">
                            <div class="d-flex justify-content-between align-items-center small text-muted">
                                <span>
                                    <i class="fas fa-users me-1"></i>
                                    {{ $formation->max_students ?? 'Illimité' }}
                                </span>
                                @if(isset($formation->satisfaction_rate))
                                    <span>
                                        <i class="fas fa-star me-1 text-warning"></i>
                                        {{ number_format($formation->satisfaction_rate, 1) }}/5
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center py-5" role="alert" style="border-radius: 15px; border: 2px dashed #0dcaf0;">
                        <i class="fas fa-info-circle fa-3x mb-3 text-info opacity-50"></i>
                        <h5 class="text-info">Aucune formation disponible</h5>
                        <p class="mb-0 text-muted">Aucune formation n'est disponible dans cette catégorie pour le moment.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($formations->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $formations->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
