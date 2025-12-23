<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $actualite->title }} - École Virtuelle des Créatifs</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            background: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            text-decoration: none;
            color: #667eea;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        .article-container {
            max-width: 900px;
            margin: 80px auto 40px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .article-header {
            padding: 3rem 3rem 2rem;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .category-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .article-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a1a1a;
            line-height: 1.3;
            margin-bottom: 1.5rem;
        }

        .article-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid #dee2e6;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #666;
            font-size: 0.9rem;
        }

        .meta-item i {
            color: #667eea;
        }

        .article-cover {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
        }

        .article-content {
            padding: 3rem;
            font-size: 1.1rem;
            line-height: 1.8;
            color: #333;
        }

        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 2rem 0;
        }

        .article-content h2 {
            color: #1a1a1a;
            font-weight: 600;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            font-size: 1.8rem;
        }

        .article-content h3 {
            color: #333;
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 0.75rem;
            font-size: 1.4rem;
        }

        .article-content p {
            margin-bottom: 1.5rem;
        }

        .article-content ul, .article-content ol {
            margin-bottom: 1.5rem;
            padding-left: 2rem;
        }

        .article-content li {
            margin-bottom: 0.75rem;
        }

        .share-section {
            padding: 2rem 3rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .share-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .share-btn {
            flex: 1;
            min-width: 120px;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            background: white;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .share-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            color: #764ba2;
        }

        @media (max-width: 768px) {
            .article-container {
                margin: 60px 15px 20px;
                border-radius: 16px;
            }

            .article-header,
            .article-content,
            .share-section {
                padding: 2rem 1.5rem;
            }

            .article-title {
                font-size: 1.8rem;
            }

            .article-meta {
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Bouton retour -->
    <a href="javascript:history.back()" class="back-button" title="Retour">
        <i class="fas fa-arrow-left fa-lg"></i>
    </a>

    <div class="article-container">
        <!-- Header de l'article -->
        <div class="article-header">
            @php
                $categoryStyles = [
                    'general' => ['label' => 'Général', 'color' => '#6c757d', 'icon' => 'fa-info-circle'],
                    'formation' => ['label' => 'Formation', 'color' => '#f09433', 'icon' => 'fa-graduation-cap'],
                    'evenement' => ['label' => 'Événement', 'color' => '#dc2743', 'icon' => 'fa-calendar-alt'],
                    'partenariat' => ['label' => 'Partenariat', 'color' => '#28a745', 'icon' => 'fa-handshake'],
                    'succes' => ['label' => 'Succès', 'color' => '#ffc107', 'icon' => 'fa-trophy'],
                ];
                $style = $categoryStyles[$actualite->category] ?? $categoryStyles['general'];
                $publishedDate = \Carbon\Carbon::parse($actualite->published_at);
            @endphp

            <div class="category-badge" style="background: {{ $style['color'] }}20; color: {{ $style['color'] }};">
                <i class="fas {{ $style['icon'] }}"></i>
                {{ $style['label'] }}
            </div>

            <h1 class="article-title">{{ $actualite->title }}</h1>

            <div class="article-meta">
                <div class="meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>{{ $publishedDate->format('d/m/Y') }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    <span>{{ $publishedDate->diffForHumans() }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-eye"></i>
                    <span>{{ number_format($actualite->views) }} vues</span>
                </div>
            </div>
        </div>

        <!-- Image de couverture -->
        @if($actualite->cover_image)
        <img src="{{ $actualite->cover_image_url }}"
             alt="{{ $actualite->title }}"
             class="article-cover">
        @endif

        <!-- Contenu de l'article -->
        <div class="article-content">
            {!! $actualite->content !!}
        </div>

        <!-- Section de partage -->
        <div class="share-section">
            <h4 style="margin-bottom: 0.5rem;">
                <i class="fas fa-share-alt me-2"></i>
                Partager cette actualité
            </h4>
            <p style="opacity: 0.9; font-size: 0.9rem; margin-bottom: 0;">
                Partagez avec vos amis et collègues
            </p>

            <div class="share-buttons">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                   target="_blank"
                   class="share-btn">
                    <i class="fab fa-facebook"></i>
                    Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($actualite->title) }}"
                   target="_blank"
                   class="share-btn">
                    <i class="fab fa-twitter"></i>
                    Twitter
                </a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($actualite->title) }}"
                   target="_blank"
                   class="share-btn">
                    <i class="fab fa-linkedin"></i>
                    LinkedIn
                </a>
                <a href="https://wa.me/?text={{ urlencode($actualite->title . ' - ' . url()->current()) }}"
                   target="_blank"
                   class="share-btn">
                    <i class="fab fa-whatsapp"></i>
                    WhatsApp
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
