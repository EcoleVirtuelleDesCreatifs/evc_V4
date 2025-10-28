@extends('layouts.ki-admin')

@section('title', $actualite->title . ' - EVC')
@section('page-title', 'Actualité')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb moderne -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent p-0">
            <li class="breadcrumb-item">
                <a href="{{ url(request()->segment(1) . '/' . request()->segment(2) . '/' . request()->segment(3)) }}" class="text-decoration-none">
                    <i class="fas fa-home me-1"></i>Tableau de bord
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ url(request()->segment(1) . '/' . request()->segment(2) . '/' . request()->segment(3) . '/actualites/index') }}" class="text-decoration-none">
                    Actualités
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($actualite->title, 50) }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Contenu principal -->
        <div class="col-lg-8">
            <!-- Header de l'article -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                <div class="card-body p-4 p-md-5">
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
                    
                    <span class="badge bg-{{ $category['color'] }} mb-3" style="font-size: 0.9rem; padding: 0.6rem 1.2rem; border-radius: 10px;">
                        <i class="fas fa-{{ $category['icon'] }} me-2"></i>{{ $category['label'] }}
                    </span>

                    <h1 class="mb-4" style="color: #1e3a8a; font-weight: 700; line-height: 1.3; font-size: 2rem;">
                        {{ $actualite->title }}
                    </h1>

                    <!-- Métadonnées -->
                    <div class="d-flex flex-wrap gap-4 mb-4 pb-4 border-bottom">
                        @if($actualite->author)
                        <div class="d-flex align-items-center">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                 style="width: 40px; height: 40px; font-size: 1.2rem; color: white;">
                                {{ strtoupper(substr($actualite->author->name, 0, 1)) }}
                            </div>
                            <div>
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Auteur</small>
                                <strong style="font-size: 0.9rem;">{{ $actualite->author->name }}</strong>
                            </div>
                        </div>
                        @endif
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2"
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-calendar text-info"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Publié le</small>
                                <strong style="font-size: 0.9rem;">{{ $actualite->created_at->format('d/m/Y') }}</strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2"
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-eye text-success"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Vues</small>
                                <strong style="font-size: 0.9rem;">{{ number_format($actualite->views_count) }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Description courte -->
                    <div class="alert alert-light border-0 mb-0" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 16px;">
                        <p class="mb-0" style="font-size: 1.15rem; color: #495057; line-height: 1.7;">
                            <i class="fas fa-quote-left me-2 text-primary opacity-50"></i>
                            {{ $actualite->excerpt }}
                            <i class="fas fa-quote-right ms-2 text-primary opacity-50"></i>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Image de couverture -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden;">
                <div class="d-flex align-items-center justify-content-center p-4" style="background-color: #f8f9fa; min-height: 400px;">
                    <img src="{{ asset('storage/' . $actualite->cover_image) }}" 
                         alt="{{ $actualite->cover_image_alt ?? $actualite->title }}" 
                         class="img-fluid"
                         style="max-height: 500px; max-width: 100%; width: auto; object-fit: contain; border-radius: 12px;">
                </div>
                @if($actualite->cover_image_alt)
                <div class="card-footer bg-white border-0 text-center py-3">
                    <small class="text-muted">
                        <i class="fas fa-image me-1"></i>{{ $actualite->cover_image_alt }}
                    </small>
                </div>
                @endif
            </div>

            <!-- Contenu de l'article -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                <div class="card-body p-4 p-md-5">
                    <div class="article-content" style="font-size: 1.05rem; line-height: 1.8; color: #333;">
                        {!! $actualite->content !!}
                    </div>
                </div>
            </div>

            <!-- SEO Keywords -->
            @if($actualite->meta_keywords)
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <h6 class="mb-3" style="color: #1e3a8a; font-weight: 600;">
                        <i class="fas fa-tags me-2"></i>Mots-clés
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(explode(',', $actualite->meta_keywords) as $keyword)
                        <span class="badge bg-light text-dark border" style="font-size: 0.85rem; padding: 0.5rem 1rem; border-radius: 10px;">
                            <i class="fas fa-tag me-1"></i>{{ trim($keyword) }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Partage social -->
            <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px; background: linear-gradient(135deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);">
                <div class="card-body p-4 text-white">
                    <h5 class="mb-3" style="font-weight: 600;">
                        <i class="fas fa-share-alt me-2"></i>Partager cette actualité
                    </h5>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                           target="_blank" 
                           class="btn btn-light flex-fill" style="border-radius: 10px;">
                            <i class="fab fa-facebook-f me-2"></i>Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($actualite->title) }}" 
                           target="_blank" 
                           class="btn btn-light flex-fill" style="border-radius: 10px;">
                            <i class="fab fa-twitter me-2"></i>Twitter
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" 
                           target="_blank" 
                           class="btn btn-light flex-fill" style="border-radius: 10px;">
                            <i class="fab fa-linkedin-in me-2"></i>LinkedIn
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($actualite->title . ' - ' . url()->current()) }}" 
                           target="_blank" 
                           class="btn btn-light flex-fill" style="border-radius: 10px;">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bouton retour -->
            <div class="mb-4">
                <a href="{{ url(request()->segment(1) . '/' . request()->segment(2) . '/' . request()->segment(3) . '/actualites/index') }}" 
                   class="btn btn-outline-primary btn-lg" style="border-radius: 12px;">
                    <i class="fas fa-arrow-left me-2"></i>Retour aux actualités
                </a>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actualités similaires -->
            @if($similaires->count() > 0)
            <div class="card border-0 shadow-sm sticky-top" style="border-radius: 20px; top: 20px;">
                <div class="card-header bg-white border-0 p-4" style="border-radius: 20px 20px 0 0;">
                    <h5 class="mb-0" style="color: #1e3a8a; font-weight: 600;">
                        <i class="fas fa-newspaper me-2"></i>Actualités similaires
                    </h5>
                </div>
                <div class="card-body p-4">
                    @foreach($similaires as $similaire)
                    <a href="{{ url(request()->segment(1) . '/' . request()->segment(2) . '/' . request()->segment(3) . '/actualites/' . $similaire->id) }}" 
                       class="text-decoration-none d-block mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex gap-3 hover-item" style="transition: all 0.3s ease;">
                            <div style="flex-shrink: 0;">
                                <div class="d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; background-color: #f8f9fa; border-radius: 12px; overflow: hidden;">
                                    <img src="{{ asset('storage/' . $similaire->cover_image) }}" 
                                         alt="{{ $similaire->title }}"
                                         class="img-fluid"
                                         style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-2" style="color: #1e3a8a; font-size: 0.95rem; line-height: 1.4; font-weight: 600;">
                                    {{ Str::limit($similaire->title, 60) }}
                                </h6>
                                <div class="d-flex align-items-center gap-3 text-muted" style="font-size: 0.8rem;">
                                    <span>
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $similaire->created_at->format('d/m/Y') }}
                                    </span>
                                    <span>
                                        <i class="fas fa-eye me-1"></i>
                                        {{ number_format($similaire->views_count) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.article-content img {
    max-width: 100%;
    height: auto;
    border-radius: 12px;
    margin: 1.5rem 0;
}

.article-content h2 {
    color: #1e3a8a;
    font-weight: 600;
    margin-top: 2.5rem;
    margin-bottom: 1rem;
    font-size: 1.75rem;
}

.article-content h3 {
    color: #334155;
    font-weight: 600;
    margin-top: 2rem;
    margin-bottom: 0.75rem;
    font-size: 1.4rem;
}

.article-content p {
    margin-bottom: 1.25rem;
}

.article-content ul, .article-content ol {
    margin-bottom: 1.25rem;
    padding-left: 2rem;
}

.article-content li {
    margin-bottom: 0.5rem;
}

.article-content blockquote {
    border-left: 4px solid #667eea;
    padding-left: 1.5rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #64748b;
    background-color: #f8f9fa;
    padding: 1rem 1.5rem;
    border-radius: 8px;
}

.article-content a {
    color: #667eea;
    text-decoration: underline;
}

.article-content a:hover {
    color: #764ba2;
}

.hover-item:hover {
    transform: translateX(5px);
}

.bg-opacity-10 {
    background-color: rgba(0, 0, 0, 0.05) !important;
}
</style>
@endsection
