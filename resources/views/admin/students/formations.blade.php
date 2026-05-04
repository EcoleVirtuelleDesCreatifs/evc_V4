@extends('layouts.admin')

@section('title', 'Formations - ' . ($student->first_name ?? 'Étudiant') . ' ' . ($student->last_name ?? ''))

@push('styles')
<style>
    body { background: #0f172a; }

    .works-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    }
    .works-header-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255,255,255,0.3);
    }
    .works-header-avatar-placeholder {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        border: 3px solid rgba(255,255,255,0.3);
    }

    .info-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .info-card-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        padding: 1rem 1.5rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
    }
    .info-card-body { padding: 1.5rem; }

    .stat-mini {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
    }
    .stat-mini h4 { margin-bottom: 0.25rem; }

    .table-modern { color: rgba(255,255,255,0.8); }
    .table-modern th {
        border-bottom: 1px solid rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.5);
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.75rem;
    }
    .table-modern td {
        border-bottom: 1px solid rgba(255,255,255,0.05);
        padding: 0.75rem;
        vertical-align: middle;
    }
    .table-modern tr:hover { background: rgba(255,255,255,0.02); }

    .badge-modern {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-success-modern { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }
    .badge-warning-modern { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
    .badge-danger-modern { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; }
    .badge-info-modern { background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff; }

    .btn-modern { border: none; border-radius: 10px; padding: 0.4rem 0.75rem; font-weight: 600; transition: all 0.3s ease; }
    .btn-primary-modern { background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%); color: white; }
    .btn-primary-modern:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(79,195,247,0.3); color: white; }

    .fade-in {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.6s ease forwards;
    }
    @keyframes fadeInUp {
        to { opacity: 1; transform: translateY(0); }
    }

    /* Tabs navigation */
    .works-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .works-tab {
        padding: 0.6rem 1.2rem;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.1);
        background: rgba(255,255,255,0.03);
        color: rgba(255,255,255,0.6);
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        font-size: 0.9rem;
    }
    .works-tab:hover { background: rgba(255,255,255,0.06); color: #fff; }
    .works-tab.active {
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
        color: #fff;
        border-color: transparent;
    }
    .works-tab .tab-count {
        display: inline-block;
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
        padding: 0.1rem 0.5rem;
        font-size: 0.75rem;
        margin-left: 0.4rem;
    }
    .works-panel { display: none; }
    .works-panel.active { display: block; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4" style="max-width: 1400px;">

    {{-- Header --}}
    <div class="works-header fade-in">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.students.profile', $student->id ?? $user->id) }}" class="text-white text-decoration-none" title="Retour au profil">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                @if(!empty($student->profile_photo))
                    <img src="{{ \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($student->profile_photo) }}" alt="" class="works-header-avatar">
                @else
                    <div class="works-header-avatar-placeholder">
                        {{ strtoupper(substr($student->first_name ?? '', 0, 1)) }}{{ strtoupper(substr($student->last_name ?? '', 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h4 class="mb-0 fw-bold">{{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }}</h4>
                    <small class="opacity-75">{{ $student->program ?? '—' }} &mdash; {{ $student->email ?? '—' }}</small>
                </div>
            </div>
            <a href="{{ route('admin.students.profile', $student->id ?? $user->id) }}" class="btn btn-modern btn-primary-modern">
                <i class="fas fa-user me-1"></i>Retour au profil
            </a>
        </div>
    </div>

    {{-- Stats rapides --}}
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3">
            <div class="stat-mini fade-in" style="animation-delay: 0.1s;">
                <h4 style="color: #4fc3f7;">{{ $student_programs->count() }}</h4>
                <small class="text-white-50">Total Formations</small>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="stat-mini fade-in" style="animation-delay: 0.15s;">
                <h4 style="color: #10b981;">{{ $formations_by_category->count() }}</h4>
                <small class="text-white-50">Catégories</small>
            </div>
        </div>
    </div>

    {{-- Formations par catégorie --}}
    @if($formations_by_category->count() > 0)
        @foreach($formations_by_category as $categoryName => $categoryFormations)
        <div class="info-card fade-in" style="animation-delay: 0.2s;">
            <div class="info-card-header">
                <i class="fas fa-folder"></i>
                <span>{{ $categoryName ?? 'Sans catégorie' }} ({{ $categoryFormations->count() }})</span>
            </div>
            <div class="info-card-body">
                <div class="row g-3">
                    @foreach($categoryFormations as $formation)
                    <div class="col-md-6 col-lg-4">
                        <div class="group" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(79,195,247,0.25); border-radius: 16px; padding: 0; height: 100%; display: flex; flex-direction: column; transition: all 0.5s ease; position: relative; overflow: hidden;" onmouseover="this.style.borderColor='#4fc3f7'; this.style.transform='translateY(-3px) scale(1.02)'; this.style.boxShadow='0 8px 25px rgba(79,195,247,0.15)';" onmouseout="this.style.borderColor='rgba(79,195,247,0.25)'; this.style.transform='none scale(1)'; this.style.boxShadow='none';">
                            <div style="position:absolute;top:0;left:0;width:100%;height:3px;background:linear-gradient(90deg,#4fc3f7,#29b6f6);"></div>

                            {{-- Image de couverture avec bouton play --}}
                            @php
                                // Déterminer l'URL de la thumbnail vidéo
                                $thumbnailUrl = $formation->image_url ?? null;
                                $hasVideo = false;

                                if (!empty($formation->video_url)) {
                                    $hasVideo = true;
                                    // YouTube thumbnail
                                    if (str_contains($formation->video_url, 'youtube.com') || str_contains($formation->video_url, 'youtu.be')) {
                                        $youtubeId = '';
                                        if (str_contains($formation->video_url, 'youtu.be/')) {
                                            $youtubeId = substr($formation->video_url, strpos($formation->video_url, 'youtu.be/') + 9);
                                            $youtubeId = explode('?', $youtubeId)[0];
                                        } elseif (str_contains($formation->video_url, 'v=')) {
                                            $youtubeId = substr($formation->video_url, strpos($formation->video_url, 'v=') + 2);
                                            $youtubeId = explode('&', $youtubeId)[0];
                                        }
                                        if ($youtubeId) {
                                            $thumbnailUrl = 'https://img.youtube.com/vi/' . $youtubeId . '/maxresdefault.jpg';
                                        }
                                    }
                                    // Vimeo thumbnail (nécessite l'API, utiliser l'image par défaut pour l'instant)
                                    elseif (str_contains($formation->video_url, 'vimeo.com')) {
                                        $thumbnailUrl = $formation->image_url ?? null;
                                    }
                                } elseif (!empty($formation->vimeo_code)) {
                                    $hasVideo = true;
                                    $thumbnailUrl = $formation->image_url ?? null;
                                }
                            @endphp
                            @if(!empty($thumbnailUrl))
                            <div style="width: 100%; height: 200px; background: #0f172a; position: relative; overflow: hidden;">
                                <div class="absolute inset-0 bg-black/50"></div>
                                <img src="{{ $thumbnailUrl }}" alt="{{ $formation->name ?? 'Formation' }}" style="width: 100%; height: 100%; object-fit: cover; position: relative; z-1;">
                                <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; z-10;">
                                    <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%); display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(79,195,247,0.4); transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.1)';" onmouseout="this.style.transform='none';">
                                        <i class="fas fa-play text-white text-2xl" style="margin-left: 4px;"></i>
                                    </div>
                                </div>
                                {{-- Badge Formation --}}
                                <div style="position: absolute; top: 12px; left: 12px; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: rgba(79,195,247,0.9); backdrop-filter: blur(4px); border-radius: 20px;">
                                    <i class="fas fa-video text-white text-xs"></i>
                                    <span class="text-white text-xs font-bold uppercase">Formation</span>
                                </div>
                            </div>
                            @else
                            <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
                                <div class="absolute inset-0 bg-black/50"></div>
                                <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; z-10;">
                                    <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%); display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(79,195,247,0.4);">
                                        <i class="fas fa-play text-white text-2xl" style="margin-left: 4px;"></i>
                                    </div>
                                </div>
                                <i class="fas fa-graduation-cap text-white" style="font-size: 3rem; opacity: 0.5; position: relative; z-0;"></i>
                                <div style="position: absolute; top: 12px; left: 12px; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: rgba(79,195,247,0.9); backdrop-filter: blur(4px); border-radius: 20px;">
                                    <i class="fas fa-video text-white text-xs"></i>
                                    <span class="text-white text-xs font-bold uppercase">Formation</span>
                                </div>
                            </div>
                            @endif

                            <div style="padding: 1.25rem;">
                                {{-- Titre --}}
                                <h6 class="text-white mb-2" style="font-weight:700; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $formation->name ?? 'Sans nom' }}</h6>

                                {{-- Description --}}
                                <p class="text-white-50 small mb-3" style="display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; line-height:1.5;">{{ Str::limit($formation->description ?? '-', 100) }}</p>

                                {{-- Meta info --}}
                                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 1rem;">
                                    <span style="display: flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-calendar"></i>
                                        {{ $formation->created_at ? date('d/m/Y', strtotime($formation->created_at)) : '—' }}
                                    </span>
                                    <span style="display: flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-clock"></i>
                                        {{ $formation->duration_weeks ?? 'N/A' }} sem
                                    </span>
                                </div>

                                {{-- Bouton Regarder --}}
                                @php
                                    // Utiliser video_url ou vimeo_code pour la vidéo
                                    $videoUrl = null;
                                    if (!empty($formation->video_url)) {
                                        $videoUrl = $formation->video_url;
                                    } elseif (!empty($formation->vimeo_code)) {
                                        $videoUrl = 'https://vimeo.com/' . $formation->vimeo_code;
                                    }
                                    // Log pour débogage
                                    \Log::info('Formation video check', [
                                        'formation_id' => $formation->id,
                                        'video_url' => $formation->video_url ?? null,
                                        'vimeo_code' => $formation->vimeo_code ?? null,
                                        'final_video_url' => $videoUrl
                                    ]);
                                @endphp
                                <button type="button" class="btn btn-modern w-100" style="background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%); color: white; padding: 0.6rem; font-weight: 600; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.02)'; this.style.boxShadow='0 4px 15px rgba(79,195,247,0.3)';" onmouseout="this.style.transform='none'; this.style.boxShadow='none';" onclick="openVideoModal('{{ $videoUrl ?? '' }}', '{{ e($formation->name ?? 'Formation') }}')">
                                    <i class="fas fa-play me-1"></i>Regarder
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="info-card fade-in">
            <div class="info-card-body">
                <p class="text-center text-white-50 py-4 mb-0">
                    <i class="fas fa-info-circle me-2"></i>Aucune formation disponible pour cet étudiant.
                </p>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function openVideoModal(videoUrl, formationName) {
    const modal = document.createElement('div');
    modal.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.9);z-index:9999;display:flex;align-items:center;justify-content:center;padding:2rem;';

    // Déterminer le type de vidéo
    let videoHtml = '';

    if (!videoUrl || videoUrl === '') {
        videoHtml = `
            <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;color:white;text-align:center;padding:2rem;">
                <i class="fas fa-video-slash" style="font-size:4rem;margin-bottom:1rem;opacity:0.5;"></i>
                <h3 style="font-size:1.5rem;margin-bottom:0.5rem;">Aucune vidéo disponible</h3>
                <p style="color:rgba(255,255,255,0.7);">Cette formation ne dispose pas encore de vidéo.</p>
            </div>
        `;
    }
    // Vérifier si c'est une vidéo YouTube
    else if (videoUrl.includes('youtube.com') || videoUrl.includes('youtu.be')) {
        let youtubeId = '';
        if (videoUrl.includes('youtu.be/')) {
            youtubeId = videoUrl.split('youtu.be/')[1].split('?')[0];
        } else if (videoUrl.includes('v=')) {
            youtubeId = videoUrl.split('v=')[1].split('&')[0];
        }
        videoHtml = `<iframe src="https://www.youtube.com/embed/${youtubeId}?autoplay=1" style="width:100%;height:100%;border:none;" allowfullscreen allow="autoplay"></iframe>`;
    }
    // Vérifier si c'est une vidéo Vimeo
    else if (videoUrl.includes('vimeo.com')) {
        const vimeoId = videoUrl.split('vimeo.com/')[1].split('?')[0];
        videoHtml = `<iframe src="https://player.vimeo.com/video/${vimeoId}?autoplay=1" style="width:100%;height:100%;border:none;" allowfullscreen allow="autoplay"></iframe>`;
    }
    // Sinon, utiliser le player vidéo HTML5
    else {
        videoHtml = `<video src="${videoUrl}" controls style="width:100%;height:100%;" autoplay></video>`;
    }

    modal.innerHTML = `
        <div style="background:#1e293b;border-radius:16px;max-width:1000px;width:100%;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.5);">
            <div style="padding:1.5rem;border-bottom:1px solid rgba(255,255,255,0.1);display:flex;justify-content:space-between;align-items:center;">
                <h5 class="text-white mb-0">${formationName}</h5>
                <button onclick="this.closest('div').parentElement.parentElement.remove()" style="background:none;border:none;color:white;font-size:1.5rem;cursor:pointer;">&times;</button>
            </div>
            <div style="padding:0;">
                <div style="width:100%;height:600px;background:#000;">
                    ${videoHtml}
                </div>
            </div>
        </div>
    `;
    modal.onclick = function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    };
    document.body.appendChild(modal);
}

// Fermer le modal avec la touche Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.querySelector('[style*="position:fixed"][style*="z-index:9999"]');
        if (modal) {
            modal.remove();
        }
    }
});
</script>
@endpush
