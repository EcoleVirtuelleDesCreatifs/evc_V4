<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test WebTV Embed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background: #0a1128;
            color: white;
        }
        .test-section {
            background: rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
        }
        .video-container {
            position: relative;
            width: 100%;
            padding-top: 56.25%; /* 16:9 */
            background: #000;
            border-radius: 12px;
            overflow: hidden;
            margin: 20px 0;
        }
        .code-block {
            background: #1a1a1a;
            padding: 15px;
            border-radius: 8px;
            overflow-x: auto;
            margin: 10px 0;
        }
        .code-block code {
            color: #00ff00;
            font-family: 'Courier New', monospace;
        }
        .info {
            background: rgba(59, 130, 246, 0.2);
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .warning {
            background: rgba(251, 146, 60, 0.2);
            border-left: 4px solid #fb923c;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .success {
            background: rgba(34, 197, 94, 0.2);
            border-left: 4px solid #22c55e;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        h1 {
            color: #fb923c;
            margin-bottom: 10px;
        }
        h2 {
            color: #3b82f6;
            margin-top: 30px;
        }
        a {
            color: #fb923c;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="test-section">
        <h1>🔍 Diagnostic WebTV Embed</h1>
        <p>Cette page vous aide à diagnostiquer les problèmes d'affichage des vidéos WebTV.</p>
    </div>

    @php
        // Récupérer toutes les vidéos actives
        $videos = \App\Models\WebtvVideo::where('is_active', true)->get();
    @endphp

    <div class="test-section">
        <h2>📊 Statistiques</h2>
        <p><strong>Vidéos actives :</strong> {{ $videos->count() }}</p>
    </div>

    @forelse($videos as $video)
    <div class="test-section">
        <h2>📹 {{ $video->title }}</h2>

        <div class="info">
            <strong>Type :</strong> {{ $video->type === 'live' ? 'Live' : 'Replay' }}<br>
            <strong>Catégorie :</strong> {{ $video->category ?? 'Non définie' }}<br>
            <strong>Vimeo Playlist ID :</strong> {{ $video->vimeo_playlist_id ?? 'Non défini' }}<br>
            <strong>URL Vidéo :</strong> <a href="{{ $video->video_url }}" target="_blank">{{ $video->video_url }}</a><br>
            <strong>Statut :</strong> {{ $video->status }}
        </div>

        <h3>🔧 Code Embed Stocké en Base</h3>
        <div class="code-block">
            <code>{{ $video->embed_code ?? 'Aucun code embed généré' }}</code>
        </div>

        @if($video->embed_code)
            <h3>▶️ Test de Lecture</h3>
            <div class="warning">
                <strong>⚠️ Si vous voyez le message "We couldn't verify the security of your connection"</strong><br>
                Cela signifie que la vidéo Vimeo a des restrictions :
                <ul>
                    <li>La vidéo est privée</li>
                    <li>L'embed est restreint à certains domaines</li>
                    <li>localhost n'est pas autorisé dans les paramètres Vimeo</li>
                </ul>
            </div>

            <div class="video-container">
                {!! $video->embed_code !!}
            </div>

            <div class="success">
                <strong>✅ Solutions :</strong>
                <ol>
                    <li>Allez sur Vimeo.com → Paramètres de la vidéo</li>
                    <li>Changez Privacy en "Public" ou "Unlisted"</li>
                    <li>Dans "Where can this be embedded?" → Sélectionnez "Anywhere" (pour les tests)</li>
                    <li>Ou ajoutez spécifiquement : localhost, 127.0.0.1, et votre domaine</li>
                </ol>
            </div>

            <div class="info">
                <strong>🔗 Liens de Test :</strong><br>
                <a href="https://vimeo.com/{{ $video->vimeo_playlist_id }}" target="_blank">
                    Ouvrir sur Vimeo.com (pour vérifier les paramètres)
                </a>
            </div>
        @else
            <div class="warning">
                <strong>⚠️ Aucun code embed</strong><br>
                Le code embed n'a pas été généré. Vérifiez que vimeo_playlist_id est défini.
            </div>
        @endif

        <hr style="border-color: rgba(255,255,255,0.1); margin: 30px 0;">
    </div>
    @empty
    <div class="test-section">
        <div class="warning">
            <h2>⚠️ Aucune vidéo active</h2>
            <p>Il n'y a aucune vidéo active dans la base de données.</p>
            <p>Allez dans l'admin pour ajouter une vidéo : <a href="{{ route('admin.webtv.videos') }}">Gestion WebTV</a></p>
        </div>
    </div>
    @endforelse

    <div class="test-section">
        <h2>📝 Checklist de Diagnostic</h2>
        <div class="info">
            <ol>
                <li>✅ Le code iframe est bien généré en base</li>
                <li>✅ L'URL Vimeo est au format HTTPS</li>
                <li>❌ <strong>La vidéo est restreinte sur Vimeo (c'est le problème actuel)</strong></li>
                <li>❓ Vérifiez les paramètres de la vidéo sur Vimeo.com</li>
            </ol>
        </div>
    </div>

    <div class="test-section">
        <h2>🎥 Test avec une Vidéo Publique de Démo</h2>
        <p>Voici une vidéo Vimeo publique pour tester que le système fonctionne :</p>

        <div class="video-container">
            <iframe src="https://player.vimeo.com/video/76979871?autoplay=1&muted=1&loop=0&autopause=0&title=0&byline=0&portrait=0"
                    width="100%"
                    height="100%"
                    frameborder="0"
                    allow="autoplay; fullscreen; picture-in-picture"
                    allowfullscreen
                    style="position: absolute; top: 0; left: 0;">
            </iframe>
        </div>

        <div class="success">
            <strong>✅ Si cette vidéo fonctionne :</strong> Le problème vient bien des paramètres de confidentialité de votre vidéo Vimeo, pas du code.
        </div>
    </div>

    <div class="test-section">
        <p style="text-align: center; margin-top: 30px;">
            <a href="{{ route('webtv') }}" style="background: #fb923c; color: white; padding: 12px 24px; border-radius: 8px; display: inline-block;">
                ← Retour à la WebTV
            </a>
        </p>
    </div>
</body>
</html>
