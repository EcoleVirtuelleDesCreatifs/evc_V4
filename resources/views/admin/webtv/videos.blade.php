@extends('layouts.admin')

@section('title', 'Programmer un Live - WebTV')

@section('content')
<style>
    .webtv-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        padding: 30px;
        border-radius: 16px;
        margin-bottom: 30px;
        color: white;
    }

    .webtv-header h1 {
        font-size: 32px;
        font-weight: 700;
        margin: 0 0 10px 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .webtv-header h1 i {
        color: #f97316;
    }

    .webtv-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 16px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        padding: 25px;
        border-radius: 12px;
        color: white;
        box-shadow: 0 4px 15px rgba(249, 115, 22, 0.3);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card.blue {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    }

    .stat-card.green {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .stat-card.purple {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
    }

    .stat-card.yellow {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    }

    .stat-card h3 {
        font-size: 14px;
        font-weight: 600;
        margin: 0 0 10px 0;
        opacity: 0.95;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-card .number {
        font-size: 36px;
        font-weight: 700;
        margin: 0;
    }

    .video-actions {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .btn-webtv {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-webtv.primary {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        color: white;
    }

    .btn-webtv.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(249, 115, 22, 0.4);
    }

    .video-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .video-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .video-card-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        padding: 20px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .video-card-title {
        font-size: 18px;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .video-card-body {
        padding: 20px;
    }

    .video-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }

    .video-info-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .video-info-label {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .video-info-value {
        font-size: 14px;
        font-weight: 500;
        color: #1f2937;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .badge.live {
        background: #dc2626;
        color: white;
        animation: pulse 2s infinite;
    }

    .badge.normal {
        background: #3b82f6;
        color: white;
    }

    .badge.status-active {
        background: #10b981;
        color: white;
    }

    .badge.status-scheduled {
        background: #f59e0b;
        color: white;
    }

    .badge.status-paused {
        background: #6b7280;
        color: white;
    }

    .badge.status-ended {
        background: #ef4444;
        color: white;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }

    .video-card-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        padding-top: 15px;
        border-top: 1px solid #e5e7eb;
    }

    .action-btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .action-btn.start {
        background: #10b981;
        color: white;
    }

    .action-btn.pause {
        background: #f59e0b;
        color: white;
    }

    .action-btn.end {
        background: #6b7280;
        color: white;
    }

    .action-btn.edit {
        background: #3b82f6;
        color: white;
    }

    .action-btn.delete {
        background: #ef4444;
        color: white;
    }

    .action-btn:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    .empty-state {
        background: white;
        border-radius: 12px;
        padding: 60px 30px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .empty-state i {
        font-size: 80px;
        color: #e5e7eb;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 24px;
        color: #1f2937;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #6b7280;
        margin-bottom: 30px;
    }
</style>

<div class="webtv-header">
    <h1>
        <i class="fas fa-video"></i>
        Programmer un Live - WebTV
    </h1>
    <p>Gérez vos vidéos en boucle et vos lives en temps réel</p>
</div>

<!-- Statistiques -->
<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Vidéos</h3>
        <p class="number">{{ $stats['total'] }}</p>
    </div>

    <div class="stat-card green">
        <h3>En Cours</h3>
        <p class="number">{{ $stats['active'] }}</p>
    </div>

    <div class="stat-card yellow">
        <h3>Programmées</h3>
        <p class="number">{{ $stats['scheduled'] }}</p>
    </div>

    <div class="stat-card purple">
        <h3>Lives</h3>
        <p class="number">{{ $stats['lives'] }}</p>
    </div>

    <div class="stat-card blue">
        <h3>Normales</h3>
        <p class="number">{{ $stats['normal'] }}</p>
    </div>
</div>

<!-- Actions -->
<div class="video-actions">
    <div>
        <h2 style="margin: 0; font-size: 20px; color: #1f2937;">Liste des Vidéos</h2>
    </div>
    <div>
        <a href="{{ route('admin.webtv.videos.create') }}" class="btn-webtv primary">
            <i class="fas fa-plus"></i>
            Programmer une Vidéo
        </a>
    </div>
</div>

{{-- Messages de succès/erreur désactivés --}}

<!-- Liste des vidéos -->
@if($videos->count() > 0)
    @foreach($videos as $video)
    <div class="video-card">
        <div class="video-card-header">
            <h3 class="video-card-title">
                @if($video->type === 'live')
                    <i class="fas fa-broadcast-tower"></i>
                @else
                    <i class="fas fa-play-circle"></i>
                @endif
                {{ $video->title }}
            </h3>
            <div style="display: flex; gap: 10px;">
                <span class="badge {{ $video->type }}">
                    {{ $video->getTypeLabel() }}
                </span>
                <span class="badge status-{{ $video->status }}">
                    {{ $video->getStatusLabel() }}
                </span>
            </div>
        </div>

        <div class="video-card-body">
            @if($video->description)
            <p style="color: #6b7280; margin-bottom: 20px;">{{ Str::limit($video->description, 150) }}</p>
            @endif

            <div class="video-info">
                <div class="video-info-item">
                    <span class="video-info-label">URL Vidéo</span>
                    <span class="video-info-value">
                        <a href="{{ $video->video_url }}" target="_blank" style="color: #f97316; text-decoration: none;">
                            <i class="fas fa-external-link-alt"></i> Voir la vidéo
                        </a>
                    </span>
                </div>

                @if($video->scheduled_start)
                <div class="video-info-item">
                    <span class="video-info-label">Début Programmé</span>
                    <span class="video-info-value">{{ $video->scheduled_start->format('d/m/Y à H:i') }}</span>
                </div>
                @endif

                @if($video->scheduled_end)
                <div class="video-info-item">
                    <span class="video-info-label">Fin Programmée</span>
                    <span class="video-info-value">{{ $video->scheduled_end->format('d/m/Y à H:i') }}</span>
                </div>
                @endif

                <div class="video-info-item">
                    <span class="video-info-label">Vues</span>
                    <span class="video-info-value">{{ $video->view_count }}</span>
                </div>

                @if($video->loop_enabled)
                <div class="video-info-item">
                    <span class="video-info-label">Boucles</span>
                    <span class="video-info-value">{{ $video->loop_count }} fois</span>
                </div>
                @endif

                <div class="video-info-item">
                    <span class="video-info-label">Ordre</span>
                    <span class="video-info-value">#{{ $video->order }}</span>
                </div>
            </div>

            <div class="video-card-actions">
                @if($video->status === 'scheduled' || $video->status === 'paused')
                <form method="POST" action="{{ route('admin.webtv.videos.start', $video->id) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="action-btn start">
                        <i class="fas fa-play"></i> Démarrer
                    </button>
                </form>
                @endif

                @if($video->status === 'active')
                <form method="POST" action="{{ route('admin.webtv.videos.pause', $video->id) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="action-btn pause">
                        <i class="fas fa-pause"></i> Pause
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.webtv.videos.end', $video->id) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="action-btn end">
                        <i class="fas fa-stop"></i> Terminer
                    </button>
                </form>
                @endif

                <a href="{{ route('admin.webtv.videos.edit', $video->id) }}" class="action-btn edit">
                    <i class="fas fa-edit"></i> Modifier
                </a>

                <form method="POST" action="{{ route('admin.webtv.videos.destroy', $video->id) }}" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette vidéo ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn delete">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@else
    <div class="empty-state">
        <i class="fas fa-video-slash"></i>
        <h3>Aucune vidéo programmée</h3>
        <p>Commencez par programmer votre première vidéo ou live pour la WebTV</p>
        <a href="{{ route('admin.webtv.videos.create') }}" class="btn-webtv primary">
            <i class="fas fa-plus"></i>
            Programmer une Vidéo
        </a>
    </div>
@endif

@endsection
