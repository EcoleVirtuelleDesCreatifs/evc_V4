@extends('layouts.ki-admin')

@section('title', ($formation->title ?? $formation->name ?? 'Formation') . ' - EVC 2024')
@section('page-title', '')

@push('styles')
<style>
    .formation-hero {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #4facfe 100%);
        padding: 60px 0;
        margin: -20px -20px 30px -20px;
        color: white;
    }
    
    .video-player {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        background: #000;
    }
    
    .nav-tabs-custom .nav-link {
        border: none;
        color: #6c757d;
        padding: 15px 25px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .nav-tabs-custom .nav-link:hover {
        color: #667eea;
        background: #f8f9fa;
    }
    
    .nav-tabs-custom .nav-link.active {
        color: #667eea;
        background: #fff;
        border-bottom: 3px solid #667eea;
    }
    
    .info-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        box-shadow: 0 5px 20px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    
    .btn-action {
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .chapter-item {
        padding: 15px;
        border-left: 3px solid #e9ecef;
        margin-bottom: 10px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .chapter-item:hover {
        background: #f8f9fa;
        border-left-color: #667eea;
    }
    
    .chapter-item.active {
        background: #f0f4ff;
        border-left-color: #667eea;
    }
    
    .resource-item {
        padding: 15px;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }
    
    .resource-item:hover {
        border-color: #667eea;
        background: #f8f9fa;
    }
</style>
@endpush

@section('content')

<!-- Hero Section -->
<div class="formation-hero">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="mb-3">
                    <a href="{{ route('design-graphique.formations.index') }}" class="text-white text-decoration-none opacity-75 hover-opacity-100">
                        <i class="fas fa-arrow-left me-2"></i>Retour aux formations
                    </a>
                </div>
                <div class="d-flex align-items-center gap-2 mb-3">
                    @if(!empty($formation->category))
                        <span class="badge bg-white bg-opacity-25 px-3 py-2">{{ $formation->category }}</span>
                    @endif
                    @if(!empty($formation->level))
                        <span class="badge bg-white bg-opacity-25 px-3 py-2">{{ ucfirst($formation->level) }}</span>
                    @endif
                    @if(!empty($formation->duration_weeks))
                        <span class="badge bg-white bg-opacity-25 px-3 py-2">
                            <i class="fas fa-clock me-1"></i>{{ $formation->duration_weeks }} semaine(s)
                        </span>
                    @endif
                </div>
                <h1 class="display-5 fw-bold mb-3">{{ $formation->title ?? $formation->name ?? 'Formation' }}</h1>
                @php
                    $description = strip_tags($formation->short_description ?? $formation->description ?? '');
                    // Retirer le préfixe "Description :" ou "Description : NomFormation" si présent
                    $description = preg_replace('/^Description\s*:\s*[^.]*\.?\s*/i', '', $description);
                    // Retirer aussi le nom de la formation si répété au début
                    $formationName = $formation->title ?? $formation->name ?? '';
                    if (!empty($formationName) && stripos($description, $formationName) === 0) {
                        $description = trim(substr($description, strlen($formationName)));
                    }
                @endphp
                <p class="lead mb-0 opacity-90">{{ Str::limit($description, 200) }}</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <button class="btn btn-light btn-lg">
                        <i class="fas fa-bookmark me-2"></i>Enregistrer
                    </button>
                    <button class="btn btn-light btn-lg">
                        <i class="fas fa-share-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Video Player -->
            <div class="video-player mb-4">
                <div class="ratio ratio-16x9">
                    @if(!empty($formation->video_url))
                        @php
                            $videoUrl = $formation->video_url;
                            
                            // Convertir les URLs YouTube en URLs d'embed
                            if (str_contains($videoUrl, 'youtube.com/watch')) {
                                preg_match('/[?&]v=([^&]+)/', $videoUrl, $matches);
                                if (isset($matches[1])) {
                                    $videoUrl = 'https://www.youtube.com/embed/' . $matches[1];
                                }
                            } elseif (str_contains($videoUrl, 'youtu.be/')) {
                                $videoUrl = str_replace('youtu.be/', 'youtube.com/embed/', $videoUrl);
                            }
                            
                            // Convertir les URLs Vimeo en URLs d'embed
                            if (str_contains($videoUrl, 'vimeo.com/') && !str_contains($videoUrl, 'player.vimeo.com')) {
                                preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $matches);
                                if (isset($matches[1])) {
                                    $videoUrl = 'https://player.vimeo.com/video/' . $matches[1];
                                }
                            }
                        @endphp
                        <iframe src="{{ $videoUrl }}" 
                                title="{{ $formation->title ?? $formation->name }}" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                        </iframe>
                    @elseif(!empty($formation->vimeo_code))
                        @php
                            $vimeoContent = $formation->vimeo_code;
                            
                            // Si vimeo_code contient un div wrapper, extraire uniquement l'iframe
                            if (str_contains($vimeoContent, '<div') && str_contains($vimeoContent, '<iframe')) {
                                // Extraire uniquement la partie iframe
                                preg_match('/<iframe[^>]*>.*?<\/iframe>/s', $vimeoContent, $matches);
                                if (isset($matches[0])) {
                                    $vimeoContent = $matches[0];
                                }
                            }
                            
                            // Si vimeo_code contient un iframe
                            if (str_contains($vimeoContent, '<iframe')) {
                                // Rendre l'iframe responsive en supprimant width, height et style inline
                                $vimeoContent = preg_replace('/width="[^"]*"/', '', $vimeoContent);
                                $vimeoContent = preg_replace('/height="[^"]*"/', '', $vimeoContent);
                                $vimeoContent = preg_replace('/style="[^"]*"/', '', $vimeoContent);
                                // Ajouter les classes nécessaires pour le ratio
                                $vimeoContent = str_replace('<iframe', '<iframe class="w-100 h-100"', $vimeoContent);
                            }
                        @endphp
                        {!! $vimeoContent !!}
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-dark text-white">
                            <div class="text-center p-5">
                                <i class="fas fa-video fa-5x mb-4 opacity-50"></i>
                                <h4>Vidéo non disponible</h4>
                                <p class="text-muted">Cette formation ne contient pas encore de vidéo</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Content Tabs -->
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-0">
                    <ul class="nav nav-tabs nav-tabs-custom border-bottom" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#description" type="button">
                                <i class="fas fa-info-circle me-2"></i>Description
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#content" type="button">
                                <i class="fas fa-list me-2"></i>Contenu
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#resources" type="button">
                                <i class="fas fa-download me-2"></i>Ressources
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content p-4">
                        <!-- Description Tab -->
                        <div class="tab-pane fade show active" id="description">
                            <h5 class="fw-bold mb-3">À propos de cette formation</h5>
                            <div class="text-muted" style="line-height: 1.8;">
                                {!! nl2br(e(strip_tags($formation->description ?? 'Description à venir.'))) !!}
                            </div>
                            
                            @if(!empty($formation->skills))
                                <h6 class="fw-bold mt-4 mb-3">Compétences acquises :</h6>
                                <div class="row">
                                    @foreach(json_decode($formation->skills, true) ?? [] as $skill)
                                        <div class="col-md-6 mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>{{ $skill }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if(!empty($formation->prerequisites))
                                <h6 class="fw-bold mt-4 mb-3">Prérequis :</h6>
                                <ul class="text-muted">
                                    @foreach(json_decode($formation->prerequisites, true) ?? [] as $prereq)
                                        <li>{{ $prereq }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <!-- Content Tab -->
                        <div class="tab-pane fade" id="content">
                            <h5 class="fw-bold mb-4">Contenu de la formation</h5>
                            
                            @if(!empty($formation->modules))
                                @foreach(json_decode($formation->modules, true) ?? [] as $index => $module)
                                    <div class="chapter-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1 fw-bold">{{ $index + 1 }}. {{ $module['title'] ?? $module }}</h6>
                                                @if(isset($module['duration']))
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock me-1"></i>{{ $module['duration'] }}
                                                    </small>
                                                @endif
                                            </div>
                                            <div>
                                                <i class="fas fa-play-circle text-primary fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted py-5">
                                    <i class="fas fa-list fa-3x mb-3 opacity-25"></i>
                                    <p>Le contenu détaillé sera bientôt disponible</p>
                                </div>
                            @endif
                        </div>

                        <!-- Resources Tab -->
                        <div class="tab-pane fade" id="resources">
                            <h5 class="fw-bold mb-4">Ressources téléchargeables</h5>
                            
                            @if(isset($files) && $files->count() > 0)
                                @foreach($files as $file)
                                    <div class="resource-item mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-danger bg-opacity-10 p-3 rounded">
                                                    <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $file->original_name }}</h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-hdd me-1"></i>{{ $file->formatted_size }}
                                                        <span class="mx-2">•</span>
                                                        <i class="fas fa-calendar me-1"></i>Ajouté le {{ $file->created_at->format('d/m/Y') }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="btn-group">
                                                <a href="{{ asset($file->file_path) }}" target="_blank" class="btn btn-outline-primary" title="Voir le PDF">
                                                    <i class="fas fa-eye me-1"></i>Voir
                                                </a>
                                                <a href="{{ asset($file->file_path) }}" download="{{ $file->original_name }}" class="btn btn-primary" title="Télécharger">
                                                    <i class="fas fa-download me-1"></i>Télécharger
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted py-5">
                                    <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                                    <p>Aucune ressource disponible pour le moment</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Progress Card -->
            <div class="info-card card mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-chart-line me-2 text-primary"></i>Votre progression
                    </h6>
                    <div class="progress mb-2" style="height: 10px; border-radius: 10px;">
                        <div class="progress-bar bg-primary" style="width: {{ $formation->completion_rate ?? 0 }}%"></div>
                    </div>
                    <small class="text-muted">{{ $formation->completion_rate ?? 0 }}% complété</small>
                </div>
            </div>

            <!-- Info Card -->
            <div class="info-card card mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-info-circle me-2 text-primary"></i>Informations
                    </h6>
                    
                    @if(!empty($formation->duration_weeks))
                        <div class="mb-3">
                            <small class="text-muted d-block">Durée</small>
                            <strong>{{ $formation->duration_weeks }} semaine(s)</strong>
                        </div>
                    @endif

                    @if(!empty($formation->instructor_name))
                        <div class="mb-3">
                            <small class="text-muted d-block">Instructeur</small>
                            <strong>{{ $formation->instructor_name }}</strong>
                        </div>
                    @endif

                    @if(!empty($formation->max_students))
                        <div class="mb-3">
                            <small class="text-muted d-block">Places</small>
                            <strong>{{ $formation->max_students }} étudiants max</strong>
                        </div>
                    @endif

                    @if(!empty($formation->format))
                        <div class="mb-3">
                            <small class="text-muted d-block">Format</small>
                            <strong>{{ ucfirst($formation->format) }}</strong>
                        </div>
                    @endif

                    @if(!empty($formation->satisfaction_rate))
                        <div>
                            <small class="text-muted d-block">Satisfaction</small>
                            <div class="d-flex align-items-center gap-2">
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $formation->satisfaction_rate ? '' : '-o' }}"></i>
                                    @endfor
                                </div>
                                <strong>{{ number_format($formation->satisfaction_rate, 1) }}/5</strong>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions Card -->
            <div class="info-card card">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-cog me-2 text-primary"></i>Actions
                    </h6>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary btn-action" data-bs-toggle="modal" data-bs-target="#videoDownloadModal">
                            <i class="fas fa-video me-2"></i>Télécharger la vidéo
                        </button>
                        <button class="btn btn-outline-secondary btn-action">
                            <i class="fas fa-flag me-2"></i>Signaler un problème
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour le téléchargement de vidéo -->
<div class="modal fade" id="videoDownloadModal" tabindex="-1" aria-labelledby="videoDownloadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="videoDownloadModalLabel">
                    <i class="fas fa-video me-2"></i>Accès à la vidéo de formation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Information importante</strong>
                </div>
                
                <p class="mb-3">La vidéo de cette formation est hébergée sur Vimeo et peut être visionnée directement sur la plateforme.</p>
                
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-play-circle me-2 text-primary"></i>Comment accéder à la vidéo :</h6>
                        <ol class="mb-0">
                            <li class="mb-2">Cliquez sur l'onglet <strong>"Vidéo"</strong> en haut de la page</li>
                            <li class="mb-2">La vidéo se chargera automatiquement dans le lecteur</li>
                            <li>Vous pouvez la regarder en plein écran pour une meilleure expérience</li>
                        </ol>
                    </div>
                </div>

                <div class="alert alert-warning mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <small><strong>Note :</strong> Pour des raisons de droits d'auteur et de protection du contenu, les vidéos ne peuvent pas être téléchargées directement. Elles restent disponibles en streaming sur la plateforme 24h/24.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Fermer
                </button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="document.querySelector('a[href=\'#video\']').click()">
                    <i class="fas fa-play me-1"></i>Aller à la vidéo
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
