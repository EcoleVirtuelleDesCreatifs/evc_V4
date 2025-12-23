@extends('layouts.ki-admin')

@section('title', ($formation->title ?? $formation->name ?? 'Formation') . ' - EVC 2024')
@section('page-title', '')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #f97316; /* Orange-500 to match WebTV */
        --primary-dark: #ea580c;
        --secondary: #0f172a;
        --text-main: #e2e8f0;
        --text-muted: #94a3b8;
        --bg-body: #0f172a; /* Dark background */
        --surface: #1e293b;
        --border: rgba(255, 255, 255, 0.1);
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--bg-body);
        color: var(--text-main);
    }

    /* WebTV Animations */
    @keyframes gradient-shift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    @keyframes glow-pulse {
      0%, 100% { box-shadow: 0 0 20px rgba(249,115,22,.3); }
      50% { box-shadow: 0 0 40px rgba(249,115,22,.5); }
    }
    @keyframes float-slow {
      0% { transform: translate3d(0,0,0); }
      50% { transform: translate3d(10px,-10px,0); }
      100% { transform: translate3d(0,0,0); }
    }

    /* Layout */
    .webtv-wrapper {
        position: relative;
        padding: 2rem 0;
        overflow: hidden;
        background: linear-gradient(to bottom, #001233, #0a1128);
        min-height: 100vh;
    }

    /* Video Section */
    .video-frame {
        background: #000;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        position: relative;
        z-index: 10;
        /* Glow effect from screenshot */
        box-shadow: 0 0 30px rgba(249, 115, 22, 0.15);
    }

    /* Info Card (Right Side) */
    .info-card-webtv {
        background: linear-gradient(to bottom right, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.02));
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 2rem;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #4ade80;
        border-radius: 9999px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1.5rem;
        width: fit-content;
    }
    .status-dot {
        width: 8px;
        height: 8px;
        background: #4ade80;
        border-radius: 50%;
        box-shadow: 0 0 10px #4ade80;
        animation: pulse 2s infinite;
    }

    /* Buttons */
    .btn-webtv-primary {
        background: #f97316;
        color: white;
        border: none;
        padding: 0.875rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        text-decoration: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .btn-webtv-primary:hover {
        background: #ea580c;
        transform: translateY(-1px);
        box-shadow: 0 10px 15px -3px rgba(249, 115, 22, 0.2);
        color: white;
    }
    .btn-webtv-primary:active {
        transform: translateY(0);
    }

    .btn-webtv-secondary {
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-main);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 0.875rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-webtv-secondary:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.2);
        color: white;
    }

    /* Toast Notification */
    .toast-notification {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 1.25rem 1.75rem;
        border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        z-index: 9999;
        transform: translateY(150%);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        font-weight: 600;
        font-size: 1rem;
        min-width: 300px;
        backdrop-filter: blur(10px);
    }
    .toast-notification.show {
        transform: translateY(0);
    }
    .toast-notification i {
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    .toast-notification span {
        color: white;
        font-weight: 600;
    }

    /* Curriculum */
    .playlist-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        margin-bottom: 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .playlist-item:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(255, 255, 255, 0.1);
    }
    .playlist-item.active {
        background: rgba(249, 115, 22, 0.1);
        border-color: rgba(249, 115, 22, 0.3);
    }
    .playlist-number {
        width: 28px;
        height: 28px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        margin-right: 1rem;
        color: var(--text-muted);
    }
    .playlist-item.active .playlist-number {
        background: #f97316;
        color: white;
    }

    /* Tabs */
    .tab-btn {
        background: transparent;
        border: none;
        color: var(--text-muted);
        font-weight: 600;
        padding: 1rem;
        border-bottom: 2px solid transparent;
        transition: all 0.3s;
    }
    .tab-btn.active {
        color: #f97316;
        border-bottom-color: #f97316;
    }
    .tab-content {
        color: #cbd5e1;
        line-height: 1.7;
    }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #0f172a; }
    ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #475569; }

    /* Utilities */
    .text-orange { color: #f97316 !important; }
    .bg-orange { background-color: #f97316 !important; }
    .text-light-50 { color: rgba(255, 255, 255, 0.5) !important; }
    .text-light-80 { color: rgba(255, 255, 255, 0.8) !important; }
</style>
@endpush

@php
    $routePrefix = 'design-graphique';
    $path = request()->path();
    if (preg_match('#^evc/compte/([^/]+)#', $path, $matches)) {
        $routePrefix = $matches[1];
    }

    // --- Video Logic (Preserved) ---
    $description = strip_tags($formation->short_description ?? $formation->description ?? '');
    $description = preg_replace('/^Description\s*:\s*[^.]*\.?\s*/i', '', $description);
    $formationName = $formation->title ?? $formation->name ?? '';
    if (!empty($formationName) && stripos($description, $formationName) === 0) {
        $description = trim(substr($description, strlen($formationName)));
    }

    $videoContent = null;
    $videoUrl = trim($formation->video_url ?? '');
    $vimeoCode = trim($formation->vimeo_code ?? '');
    // Prioritize vimeo_code as it is the active field in forms
    $source = !empty($vimeoCode) ? $vimeoCode : $videoUrl;

    if ($source) {
        // 1. Direct Iframe Support (Most Robust for Embed Codes)
        // If the input contains an iframe tag, we try to use it directly (after cleaning)
        if (str_contains($source, '<iframe')) {
            // Extract SRC to check safety or re-build
            if (preg_match('/src=["\']([^"\']+)["\']/', $source, $match)) {
                $src = $match[1];
                // Standardize YouTube URLs to nocookie for better privacy/compatibility
                if (str_contains($src, 'youtube.com') || str_contains($src, 'youtu.be')) {
                    $src = str_replace('youtube.com', 'youtube-nocookie.com', $src);
                    // Remove tracking parameters (si=, feature=) that can cause embedding issues
                    $src = preg_replace('/[?&](si|feature)=[^&]*/', '', $src);
                    // Clean up double ? or &
                    $src = preg_replace('/\?&/', '?', $src);
                    $src = preg_replace('/&&/', '&', $src);
                    $src = rtrim($src, '?&');
                }
                $videoContent = '<iframe src="' . $src . '" class="w-100 h-100" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen title=""></iframe>';
            } else {
                // Fallback: Output the raw iframe (cleaned) if we can't parse src but it looks like an iframe
                $videoContent = $source;
            }
        }
        // 2. URL Logic (for plain links)
        else {
            $cleanSource = trim($source);
            $videoId = null;
            $isVimeo = false;

            // YouTube Logic
            if (str_contains($cleanSource, 'youtube') || str_contains($cleanSource, 'youtu.be')) {
                if (preg_match('/(?:[?&]v=|\/v\/|\/embed\/|\/shorts\/|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $cleanSource, $matches)) {
                    $videoId = $matches[1];
                    $videoContent = '<iframe src="https://www.youtube-nocookie.com/embed/' . $videoId . '?rel=0&modestbranding=1&showinfo=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-100 h-100" title=""></iframe>';
                }
            }
            // Vimeo Logic
            elseif (str_contains($cleanSource, 'vimeo')) {
                if (preg_match('/(?:vimeo\.com\/|player\.vimeo\.com\/video\/)([0-9]+)/', $cleanSource, $matches)) {
                    $videoId = $matches[1];
                    $videoContent = '<iframe src="https://player.vimeo.com/video/' . $videoId . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen class="w-100 h-100" title=""></iframe>';
                }
            }
        }
    }

    $imageUrl = null;
    if (!$videoContent && (!empty($formation->image) || !empty($formation->cover) || !empty($formation->thumbnail))) {
        $imageUrl = $formation->image ?? $formation->cover ?? $formation->thumbnail;
        $imageUrl = \App\Models\MediaUrl::fromPath($imageUrl);
    }

    $modules = json_decode($formation->modules ?? '[]', true) ?? [];
    $files = $files ?? collect();
@endphp

@section('content')

<div class="webtv-wrapper">
    <!-- Decorative background -->
    <div class="pointer-events-none absolute inset-0 -z-10">
        <div class="absolute -top-40 -left-40 h-80 w-80 rounded-full bg-orange-500/10 blur-3xl" style="animation: float-slow 15s ease-in-out infinite;"></div>
        <div class="absolute -bottom-40 -right-40 h-80 w-80 rounded-full bg-blue-500/10 blur-3xl" style="animation: float-slow 18s ease-in-out infinite; animation-delay: -5s;"></div>
    </div>

    <div class="toast-notification" id="shareToast">
        <i class="fas fa-check-circle"></i>
        <span>Lien copié dans le presse-papier !</span>
    </div>

    <div class="container-fluid px-4 md:px-8">

        <!-- Breadcrumb -->
        <div class="mb-6">
            <a href="{{ route($routePrefix . '.formations.index') }}" class="text-white-50 hover:text-white text-decoration-none small fw-medium d-flex align-items-center gap-2">
                <i class="fas fa-arrow-left"></i> Retour aux formations
            </a>
        </div>

        <div class="row g-4">

            <!-- Video Section (Col 8) -->
            <div class="col-lg-8">
                <div id="videoPlayerSection" class="video-frame ratio ratio-16x9 mb-4">
                    @if($videoContent)
                        {!! $videoContent !!}
                    @elseif($imageUrl)
                        <img src="{{ $imageUrl }}" alt="Cover" class="w-100 h-100 object-fit-cover">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-black text-white w-100 h-100">
                            <div class="text-center">
                                <i class="fas fa-play-circle fa-4x mb-3 opacity-50"></i>
                                <p>Aperçu non disponible</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Tabs & Content -->
                <div class="mt-5">
                    <div class="d-flex border-bottom border-white-10 mb-4" style="border-color: rgba(255,255,255,0.1) !important;">
                        <button class="tab-btn active" onclick="switchTab('description')">Description</button>
                        <button class="tab-btn" onclick="switchTab('resources')">Ressources ({{ $files->count() }})</button>
                        <button class="tab-btn" onclick="switchTab('reviews')">Avis</button>
                    </div>

                    <div id="tab-description" class="tab-content">
                        <h3 class="h4 fw-bold text-white mb-3">À propos du cours</h3>
                        <div class="text-light-80">
                            {!! nl2br(e(strip_tags($formation->description ?? 'Aucune description disponible.'))) !!}
                        </div>
                    </div>

                    <div id="tab-resources" class="tab-content d-none">
                        @if($files->count() > 0)
                            <div class="row g-3">
                                @foreach($files as $file)
                                <div class="col-md-6">
                                    <a href="{{ \App\Models\MediaUrl::fromPath($file->file_path) }}" download class="playlist-item text-decoration-none">
                                        <div class="playlist-number"><i class="fas fa-file-pdf"></i></div>
                                        <div class="flex-grow-1">
                                            <div class="text-white fw-medium small">{{ $file->original_name }}</div>
                                            <div class="small text-white-50">{{ $file->formatted_size }}</div>
                                        </div>
                                        <i class="fas fa-download text-white-50"></i>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-white-50">Aucune ressource disponible pour ce cours.</p>
                        @endif
                    </div>

                    <div id="tab-reviews" class="tab-content d-none">
                        <div class="text-center py-5 rounded-3 border" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1) !important;">
                            <div class="text-warning display-6 mb-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <h4 class="text-white fw-bold h5">4.8 / 5</h4>
                            <p class="text-white-50 small">Basé sur les avis des étudiants</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info (Col 4) -->
            <div class="col-lg-4">
                <div class="info-card-webtv sticky-top" style="top: 2rem;">

                    <!-- Status Badge -->
                    <div class="status-badge">
                        <div class="status-dot"></div>
                        <span>En Formation</span>
                    </div>

                    <!-- Title -->
                    <h1 class="h2 fw-bold text-white mb-3 leading-tight">
                        {{ $formation->title ?? $formation->name }}
                    </h1>

                    <!-- Meta -->
                    <div class="d-flex flex-wrap gap-3 small text-white-50 mb-4">
                        <span class="d-flex align-items-center gap-2"><i class="fas fa-user"></i> {{ $formation->instructor_name ?? 'EVC' }}</span>
                        <span class="d-flex align-items-center gap-2"><i class="fas fa-clock"></i> {{ $formation->duration_weeks ?? 4 }} semaines</span>
                    </div>

                    <!-- Progress -->
                    <div class="mb-4 p-3 rounded-3 border" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1) !important;">
                        <div class="d-flex justify-content-between small fw-bold text-light mb-2">
                            <span>Progression</span>
                            <span class="text-orange">{{ $formation->completion_rate ?? 0 }}%</span>
                        </div>
                        <div class="progress" style="height: 8px; background-color: #374151;">
                            <div class="progress-bar bg-orange" role="progressbar" style="width: {{ $formation->completion_rate ?? 0 }}%"></div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-auto d-grid gap-3">
                        @if($files->count() > 0)
                            <a href="{{ route($routePrefix . '.formations.download-all', $formation->id) }}" class="btn-webtv-primary">
                                <i class="fas fa-download"></i> Télécharger les ressources
                            </a>
                        @else
                            <button class="btn-webtv-primary" onclick="showNoFilesMessage()">
                                <i class="fas fa-download"></i> Télécharger les ressources
                            </button>
                        @endif

                        @if(!empty($formation->vimeo_code) || !empty($formation->video_url))
                            @php
                                $videoUrl = null;
                                $source = !empty($formation->vimeo_code) ? $formation->vimeo_code : $formation->video_url;
                                // Extract YouTube video ID or URL
                                if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $source, $matches)) {
                                    $videoUrl = 'https://www.youtube.com/watch?v=' . $matches[1];
                                } elseif (str_contains($source, 'vimeo')) {
                                    if (preg_match('/(?:vimeo\.com\/|player\.vimeo\.com\/video\/)([0-9]+)/', $source, $matches)) {
                                        $videoUrl = 'https://vimeo.com/' . $matches[1];
                                    }
                                }
                            @endphp
                            @if($videoUrl)
                                <a href="{{ $videoUrl }}" target="_blank" class="btn-webtv-secondary">
                                    <i class="fab fa-youtube"></i> Voir sur YouTube
                                </a>
                            @endif
                        @endif

                        <button class="btn-webtv-secondary" onclick="sharePage()">
                            <i class="fas fa-share-alt"></i> Partager
                        </button>
                    </div>

                    <!-- Chapitres -->
                    <div class="mt-4 pt-4 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
                        <h4 class="h5 fw-bold text-white mb-3">
                            <i class="fas fa-book-open me-2"></i>LES DIFFÉRENTES PARTIES
                        </h4>
                        <div class="overflow-auto pe-2" style="max-height: 400px;" id="chapters-list-container">
                            @if($chapters && $chapters->count() > 0)
                                @foreach($chapters as $index => $chapter)
                                    <div class="playlist-item chapter-item-clickable {{ $index === 0 ? 'active' : '' }}"
                                         style="cursor: pointer; display: none;"
                                         data-chapter-index="{{ $index }}"
                                         data-chapter-id="{{ $chapter->id }}"
                                         data-video-url="{{ $chapter->video_url ?? '' }}"
                                         onclick="loadChapterVideo({{ $chapter->id }}, '{{ addslashes($chapter->video_url ?? '') }}', '{{ addslashes($chapter->title) }}', this)">
                                        <div class="playlist-number">{{ $chapter->order }}</div>
                                        <div class="flex-grow-1">
                                            <div class="text-white fw-medium small">{{ $chapter->title }}</div>
                                            @if($chapter->duration)
                                                <div class="text-white-50" style="font-size: 0.75rem;">
                                                    <i class="fas fa-clock me-1"></i>{{ $chapter->duration }} min
                                                </div>
                                            @endif
                                            @if($chapter->description)
                                                <div class="text-white-50 mt-1" style="font-size: 0.7rem; line-height: 1.4;">
                                                    {{ Str::limit($chapter->description, 80) }}
                                                </div>
                                            @endif
                                        </div>
                                        @if($chapter->video_url)
                                            <i class="fas fa-play-circle text-orange" style="font-size: 1.2rem;"></i>
                                        @else
                                            <i class="fas fa-chevron-right text-white-50 small"></i>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4" style="color: #6b7280;">
                                    <i class="fas fa-book-open mb-2" style="font-size: 2rem; opacity: 0.3;"></i>
                                    <p class="small mb-0">Aucun chapitre disponible pour le moment.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Pagination -->
                        @if($chapters && $chapters->count() > 5)
                        <div class="mt-3 d-flex justify-content-between align-items-center" id="chapters-pagination">
                            <button class="btn btn-sm btn-outline-light" id="prevChaptersBtn" onclick="changeChaptersPage(-1)" disabled>
                                <i class="fas fa-chevron-left me-1"></i> Précédent
                            </button>
                            <span class="text-white-50 small" id="chaptersPageInfo">Page 1</span>
                            <button class="btn btn-sm btn-outline-light" id="nextChaptersBtn" onclick="changeChaptersPage(1)">
                                Suivant <i class="fas fa-chevron-right ms-1"></i>
                            </button>
                        </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
function switchTab(tabName) {
    // Buttons
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');

    // Content
    document.querySelectorAll('.tab-content').forEach(content => content.classList.add('d-none'));
    document.getElementById('tab-' + tabName).classList.remove('d-none');
}

function sharePage() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $formation->title ?? "Formation EVC" }}',
            url: window.location.href
        }).catch(console.error);
    } else {
        navigator.clipboard.writeText(window.location.href).then(() => {
            const toast = document.getElementById('shareToast');
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        });
    }
}

function scrollToVideo() {
    const videoSection = document.getElementById('videoPlayerSection');
    if (videoSection) {
        videoSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

function loadChapterVideo(chapterId, videoUrl, chapterTitle, element) {
    // Si pas de vidéo, juste scroller
    if (!videoUrl || videoUrl.trim() === '') {
        scrollToVideo();
        return;
    }

    // Retirer la classe active de tous les chapitres
    document.querySelectorAll('.chapter-item-clickable').forEach(item => {
        item.classList.remove('active');
    });

    // Ajouter la classe active au chapitre cliqué
    element.classList.add('active');

    // Extraire l'iframe ou créer un nouvel iframe
    let iframeHtml = '';

    // 1. Si c'est déjà un iframe, l'utiliser directement
    if (videoUrl.includes('<iframe')) {
        const div = document.createElement('div');
        div.innerHTML = videoUrl;
        const iframe = div.querySelector('iframe');
        if (iframe) {
            iframe.className = 'w-100 h-100';
            iframe.setAttribute('frameborder', '0');
            iframe.setAttribute('allow', 'autoplay; fullscreen; picture-in-picture');
            iframe.setAttribute('allowfullscreen', 'true');

            // Nettoyer l'URL YouTube
            let src = iframe.src;
            if (src.includes('youtube.com')) {
                src = src.replace('youtube.com', 'youtube-nocookie.com');
                src = src.replace(/[?&](si|feature)=[^&]*/g, '');
                src = src.replace(/\?&/, '?').replace(/&&/, '&');
                iframe.src = src;
            }

            iframeHtml = iframe.outerHTML;
        }
    }
    // 2. Si c'est une URL YouTube
    else if (videoUrl.includes('youtube') || videoUrl.includes('youtu.be')) {
        const regExp = /(?:[?&]v=|\/v\/|\/embed\/|\/shorts\/|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
        const match = videoUrl.match(regExp);
        if (match && match[1]) {
            const videoId = match[1];
            iframeHtml = `<iframe src="https://www.youtube-nocookie.com/embed/${videoId}?rel=0&modestbranding=1&showinfo=0"
                                  class="w-100 h-100"
                                  frameborder="0"
                                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                  allowfullscreen
                                  title=""></iframe>`;
        }
    }
    // 3. Si c'est une URL Vimeo
    else if (videoUrl.includes('vimeo')) {
        const vimeoRegExp = /(?:vimeo\.com\/|player\.vimeo\.com\/video\/)([0-9]+)/;
        const match = videoUrl.match(vimeoRegExp);
        if (match && match[1]) {
            const videoId = match[1];
            iframeHtml = `<iframe src="https://player.vimeo.com/video/${videoId}"
                                  class="w-100 h-100"
                                  frameborder="0"
                                  allow="autoplay; fullscreen; picture-in-picture"
                                  allowfullscreen
                                  title=""></iframe>`;
        }
    }

    // Remplacer le contenu de la vidéo si on a généré un iframe
    if (iframeHtml) {
        const videoSection = document.getElementById('videoPlayerSection');
        if (videoSection) {
            videoSection.innerHTML = iframeHtml;
            scrollToVideo();
        }
    } else {
        // Si aucun format reconnu, juste scroller
        scrollToVideo();
    }
}

// Gestion de la pagination des chapitres
let currentChaptersPage = 1;
const chaptersPerPage = 5;
const totalChapters = {{ $chapters ? $chapters->count() : 0 }};
const totalPages = Math.ceil(totalChapters / chaptersPerPage);

function displayChaptersPage(pageNumber) {
    const allChapters = document.querySelectorAll('.chapter-item-clickable');
    const startIndex = (pageNumber - 1) * chaptersPerPage;
    const endIndex = startIndex + chaptersPerPage;

    // Masquer tous les chapitres
    allChapters.forEach((chapter, index) => {
        if (index >= startIndex && index < endIndex) {
            chapter.style.display = 'flex';
        } else {
            chapter.style.display = 'none';
        }
    });

    // Mettre à jour les boutons de pagination
    const prevBtn = document.getElementById('prevChaptersBtn');
    const nextBtn = document.getElementById('nextChaptersBtn');
    const pageInfo = document.getElementById('chaptersPageInfo');

    if (prevBtn) prevBtn.disabled = (pageNumber === 1);
    if (nextBtn) nextBtn.disabled = (pageNumber === totalPages);
    if (pageInfo) pageInfo.textContent = `Page ${pageNumber} / ${totalPages}`;

    currentChaptersPage = pageNumber;
}

function changeChaptersPage(direction) {
    const newPage = currentChaptersPage + direction;
    if (newPage >= 1 && newPage <= totalPages) {
        displayChaptersPage(newPage);
    }
}

// Charger automatiquement la vidéo du premier chapitre au chargement
document.addEventListener('DOMContentLoaded', function() {
    // Afficher la première page de chapitres
    if (totalChapters > 0) {
        displayChaptersPage(1);
    }

    @if($chapters && $chapters->count() > 0 && $chapters->first()->video_url)
        const firstChapter = document.querySelector('.chapter-item-clickable[data-chapter-id="{{ $chapters->first()->id }}"]');
        if (firstChapter) {
            loadChapterVideo(
                {{ $chapters->first()->id }},
                '{{ addslashes($chapters->first()->video_url) }}',
                '{{ addslashes($chapters->first()->title) }}',
                firstChapter
            );
        }
    @endif
});

function showNoFilesMessage() {
    const toast = document.getElementById('shareToast');
    const toastIcon = toast.querySelector('i');
    const toastText = toast.querySelector('span');

    // Sauvegarder l'état original
    const originalIcon = toastIcon.className;
    const originalText = toastText.textContent;

    // Changer pour un message d'info
    toastIcon.className = 'fas fa-info-circle';
    toastText.textContent = 'Aucune ressource disponible pour cette formation.';

    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
        // Restaurer l'état original après la fermeture
        setTimeout(() => {
            toastIcon.className = originalIcon;
            toastText.textContent = originalText;
        }, 300);
    }, 3000);
}

</script>

@endsection
