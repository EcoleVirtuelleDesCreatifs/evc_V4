@extends('layouts.admin')

@section('title', 'Modifier la Vidéo - WebTV')

@section('content')
<style>
    .form-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .page-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        padding: 30px;
        border-radius: 16px;
        margin-bottom: 30px;
        color: white;
    }

    .page-header h1 {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 10px 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-header p {
        margin: 0;
        opacity: 0.9;
    }

    .form-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .form-section {
        margin-bottom: 30px;
    }

    .form-section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f97316;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-label.required::after {
        content: " *";
        color: #ef4444;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
    }

    .form-text {
        font-size: 12px;
        color: #6b7280;
        margin-top: 6px;
        display: block;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .form-check-input {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .form-check-label {
        font-size: 14px;
        color: #374151;
        cursor: pointer;
    }

    .btn-submit {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        color: white;
        padding: 14px 32px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(249, 115, 22, 0.4);
    }

    .btn-cancel {
        background: #6b7280;
        color: white;
        padding: 14px 32px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #4b5563;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        padding-top: 30px;
        border-top: 1px solid #e5e7eb;
    }

    .radio-group {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
    }

    .radio-option {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .radio-option input[type="radio"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .radio-option label {
        font-size: 14px;
        color: #374151;
        cursor: pointer;
    }

    .current-thumbnail {
        margin-top: 10px;
        padding: 15px;
        background: #f9fafb;
        border-radius: 8px;
    }

    .current-thumbnail img {
        max-width: 200px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="form-container">
    <div class="page-header">
        <h1>
            <i class="fas fa-edit"></i>
            Modifier la Vidéo
        </h1>
        <p>Modifiez les paramètres de votre vidéo ou live</p>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong><i class="fas fa-exclamation-triangle"></i> Erreurs de validation :</strong>
        <ul style="margin: 10px 0 0 0;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="form-card">
        <form method="POST" action="{{ route('admin.webtv.videos.update', $video->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Informations de base -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-info-circle"></i>
                    Informations de Base
                </h3>

                <div class="form-group">
                    <label for="title" class="form-label required">Titre de la vidéo</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $video->title) }}" required>
                    <span class="form-text">Le titre qui sera affiché aux abonnés</span>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $video->description) }}</textarea>
                    <span class="form-text">Une brève description de la vidéo ou du live</span>
                </div>

                <div class="form-group">
                    <label for="video_url" class="form-label required">URL Vimeo (Vidéo, Playlist ou Événement Live)</label>
                    <input type="url" class="form-control" id="video_url" name="video_url" value="{{ old('video_url', $video->video_url) }}" placeholder="https://vimeo.com/showcase/XXXX ou https://vimeo.com/event/XXXX" required>
                    <span class="form-text">
                        <strong>Formats acceptés :</strong><br>
                        - Playlist/Showcase : https://vimeo.com/showcase/123456<br>
                        - Événement Live : https://vimeo.com/event/123456<br>
                        - Vidéo simple : https://vimeo.com/123456
                        @if($video->vimeo_playlist_id)
                            <br><strong style="color: #10b981;">✓ ID Vimeo détecté : {{ $video->vimeo_playlist_id }}</strong>
                        @endif
                    </span>
                </div>

                <div class="form-group">
                    <label for="thumbnail" class="form-label">Miniature</label>
                    <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
                    <span class="form-text">Image d'aperçu (JPEG, PNG, GIF - Max 2MB)</span>

                    @if($video->thumbnail)
                    <div class="current-thumbnail">
                        <p style="margin: 0 0 10px 0; font-weight: 600; color: #374151;">Miniature actuelle :</p>
                        <img src="{{ \App\Models\MediaUrl::fromPath($video->thumbnail) }}" alt="Miniature">
                    </div>
                    @endif
                </div>
            </div>

            <!-- Type et Programmation -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-cog"></i>
                    Type et Programmation
                </h3>

                <div class="form-group">
                    <label class="form-label required">Type de diffusion</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="type_normal" name="type" value="normal" {{ old('type', $video->type) === 'normal' ? 'checked' : '' }}>
                            <label for="type_normal">
                                <i class="fas fa-play-circle" style="color: #3b82f6;"></i> Normal (Vidéo en boucle)
                            </label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="type_live" name="type" value="live" {{ old('type', $video->type) === 'live' ? 'checked' : '' }}>
                            <label for="type_live">
                                <i class="fas fa-broadcast-tower" style="color: #dc2626;"></i> Live (En direct)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="category" class="form-label">Thématique</label>
                    <select class="form-control" id="category" name="category">
                        <option value="">-- Aucune thématique --</option>
                        <option value="design-graphique" {{ old('category', $video->category) === 'design-graphique' ? 'selected' : '' }}>
                            🎨 Design Graphique
                        </option>
                        <option value="community-management" {{ old('category', $video->category) === 'community-management' ? 'selected' : '' }}>
                            📢 Community Management
                        </option>
                        <option value="design-graphique-community-manager" {{ old('category', $video->category) === 'design-graphique-community-manager' ? 'selected' : '' }}>
                            🎨📢 Design Graphique & Community Manager
                        </option>
                        <option value="intelligence-artificielle" {{ old('category', $video->category) === 'intelligence-artificielle' ? 'selected' : '' }}>
                            🤖 Intelligence Artificielle
                        </option>
                        <option value="gestion-informatique" {{ old('category', $video->category) === 'gestion-informatique' ? 'selected' : '' }}>
                            💻 Gestion Informatique
                        </option>
                    </select>
                    <span class="form-text">Choisissez la thématique de cette vidéo (optionnel)</span>
                </div>

                <div class="form-group">
                    <label for="scheduled_start" class="form-label">Date/Heure de début</label>
                    <input type="datetime-local" class="form-control" id="scheduled_start" name="scheduled_start" value="{{ old('scheduled_start', $video->scheduled_start?->format('Y-m-d\TH:i')) }}">
                    <span class="form-text">Quand la vidéo doit commencer</span>
                </div>

                <div class="form-group">
                    <label for="scheduled_end" class="form-label">Date/Heure de fin</label>
                    <input type="datetime-local" class="form-control" id="scheduled_end" name="scheduled_end" value="{{ old('scheduled_end', $video->scheduled_end?->format('Y-m-d\TH:i')) }}">
                    <span class="form-text">Quand la vidéo doit se terminer</span>
                </div>

                <div class="form-group">
                    <label for="order" class="form-label">Ordre dans la playlist</label>
                    <input type="number" class="form-control" id="order" name="order" value="{{ old('order', $video->order) }}" min="0">
                    <span class="form-text">Position dans la liste de lecture</span>
                </div>
            </div>

            <!-- Options -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-sliders-h"></i>
                    Options
                </h3>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="loop_enabled" name="loop_enabled" value="1" {{ old('loop_enabled', $video->loop_enabled) ? 'checked' : '' }}>
                    <label for="loop_enabled" class="form-check-label">
                        Lecture en boucle (la vidéo redémarre automatiquement)
                    </label>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $video->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="form-check-label">
                        Activer
                    </label>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i>
                    Enregistrer les Modifications
                </button>
                <a href="{{ route('admin.webtv.videos') }}" class="btn-cancel">
                    <i class="fas fa-times"></i>
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
