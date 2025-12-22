@extends('layouts.app')

@section('title', $actualite->meta_title ?? $actualite->title . ' - EVC')
@section('description', $actualite->meta_description ?? $actualite->excerpt)
@section('keywords', $actualite->meta_keywords ?? 'actualité, blog, EVC')

@push('head')
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ route('actualite.show', $actualite->slug) }}" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{ route('actualite.show', $actualite->slug) }}" />
    <meta property="og:title" content="{{ $actualite->meta_title ?? $actualite->title }}" />
    <meta property="og:description" content="{{ $actualite->meta_description ?? $actualite->excerpt }}" />
    <meta property="og:image" content="{{ $actualite->cover_image ? $actualite->cover_image_url : asset('assets/img/logo.png') }}" />
    <meta property="og:locale" content="fr_FR" />
    <meta property="og:site_name" content="École Virtuelle des Créatifs" />
    @if($actualite->published_at)
    <meta property="article:published_time" content="{{ $actualite->published_at->toIso8601String() }}" />
    @endif

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:url" content="{{ route('actualite.show', $actualite->slug) }}" />
    <meta name="twitter:title" content="{{ $actualite->meta_title ?? $actualite->title }}" />
    <meta name="twitter:description" content="{{ $actualite->meta_description ?? $actualite->excerpt }}" />
    <meta name="twitter:image" content="{{ $actualite->cover_image ? $actualite->cover_image_url : asset('assets/img/logo.png') }}" />

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    @php
        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $actualite->title,
            'description' => $actualite->excerpt,
            'image' => $actualite->cover_image ? $actualite->cover_image_url : asset('assets/img/logo.png'),
            'author' => [
                '@type' => 'Organization',
                'name' => 'École Virtuelle des Créatifs',
                'url' => route('homepage')
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'École Virtuelle des Créatifs',
                'url' => route('homepage'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('assets/img/logo.png')
                ]
            ],
            'datePublished' => $actualite->published_at ? $actualite->published_at->toIso8601String() : $actualite->created_at->toIso8601String(),
            'dateModified' => $actualite->updated_at->toIso8601String()
        ];
    @endphp
    {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@section('content')
<!-- Hero Section avec Image de couverture -->
<div class="relative bg-gradient-to-b from-[#000033] to-[#000066] pt-40 sm:pt-48 lg:pt-56 pb-12">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('homepage') }}" class="inline-flex items-center text-sm font-medium text-gray-300 hover:text-orange-500">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Accueil
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-400 md:ml-2">Blog</span>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 truncate">{{ Str::limit($actualite->title, 40) }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Image de couverture -->
            <div class="relative rounded-3xl overflow-hidden mb-8 shadow-2xl">
                <img src="{{ $actualite->cover_image ? $actualite->cover_image_url : 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=1600&q=80' }}"
                     alt="{{ $actualite->cover_image_alt ?? $actualite->title }}"
                     class="w-full h-[400px] object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            </div>

            <!-- Catégorie et Date -->
            <div class="flex flex-wrap items-center gap-4 mb-6">
                @php
                    $categoryColors = [
                        'general' => ['bg' => 'bg-gray-500/20', 'text' => 'text-gray-400', 'border' => 'border-gray-500/50'],
                        'formation' => ['bg' => 'bg-blue-500/20', 'text' => 'text-blue-400', 'border' => 'border-blue-500/50'],
                        'evenement' => ['bg' => 'bg-cyan-500/20', 'text' => 'text-cyan-400', 'border' => 'border-cyan-500/50'],
                        'partenariat' => ['bg' => 'bg-green-500/20', 'text' => 'text-green-400', 'border' => 'border-green-500/50'],
                        'succes' => ['bg' => 'bg-yellow-500/20', 'text' => 'text-yellow-400', 'border' => 'border-yellow-500/50']
                    ];
                    $colors = $categoryColors[$actualite->category] ?? ['bg' => 'bg-orange-500/20', 'text' => 'text-orange-400', 'border' => 'border-orange-500/50'];

                    $categoryLabels = [
                        'general' => 'Général',
                        'formation' => 'Formation',
                        'evenement' => 'Événement',
                        'partenariat' => 'Partenariat',
                        'succes' => 'Succès'
                    ];
                    $categoryLabel = $categoryLabels[$actualite->category] ?? ucfirst($actualite->category);
                @endphp
                <span class="inline-flex items-center px-4 py-2 rounded-full {{ $colors['bg'] }} {{ $colors['text'] }} border {{ $colors['border'] }} text-sm font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    {{ $categoryLabel }}
                </span>

                <span class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 text-gray-300 border border-white/20 text-sm font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ $actualite->created_at->format('d') }} {{ $actualite->created_at->locale('fr')->translatedFormat('F Y') }}
                </span>

                @if($actualite->is_featured)
                <span class="inline-flex items-center px-4 py-2 rounded-full bg-yellow-500/20 text-yellow-400 border border-yellow-500/50 text-sm font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    À la une
                </span>
                @endif

                <span class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 text-gray-400 border border-white/20 text-sm font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    {{ $actualite->views_count ?? 0 }} vue{{ ($actualite->views_count ?? 0) > 1 ? 's' : '' }}
                </span>
            </div>

            <!-- Titre -->
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">
                {{ $actualite->title }}
            </h1>

            <!-- Description courte -->
            <p class="text-xl text-gray-300 mb-8 leading-relaxed">
                {{ $actualite->excerpt }}
            </p>
        </div>
    </div>
</div>

<!-- Contenu principal -->
<div class="bg-white py-16">
    <div class="mx-auto max-w-4xl px-6 lg:px-8">
        <!-- Contenu complet -->
        <div class="prose prose-lg max-w-none">
            <div class="text-gray-800 leading-relaxed space-y-4">
                {!! $actualite->content !!}
            </div>
        </div>

        <!-- Section Partage sur les réseaux sociaux -->
        <div class="mt-12 p-6 bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl border border-gray-200">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-center sm:text-left">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Partager cet article</h3>
                    <p class="text-sm text-gray-600">Faites découvrir cette actualité à votre réseau</p>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-3">
                    <!-- Facebook -->
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('actualite.show', $actualite->slug)) }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="inline-flex items-center px-4 py-2 bg-[#1877F2] text-white rounded-full hover:bg-[#0d65d9] transition duration-300 shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        <span class="ml-2 font-medium">Facebook</span>
                    </a>

                    <!-- Twitter (X) -->
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('actualite.show', $actualite->slug)) }}&text={{ urlencode($actualite->title) }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="inline-flex items-center px-4 py-2 bg-black text-white rounded-full hover:bg-gray-800 transition duration-300 shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                        <span class="ml-2 font-medium">Twitter</span>
                    </a>

                    <!-- LinkedIn -->
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('actualite.show', $actualite->slug)) }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="inline-flex items-center px-4 py-2 bg-[#0A66C2] text-white rounded-full hover:bg-[#004182] transition duration-300 shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                        <span class="ml-2 font-medium">LinkedIn</span>
                    </a>

                    <!-- WhatsApp -->
                    <a href="https://wa.me/?text={{ urlencode($actualite->title . ' - ' . route('actualite.show', $actualite->slug)) }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="inline-flex items-center px-4 py-2 bg-[#25D366] text-white rounded-full hover:bg-[#1da851] transition duration-300 shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        <span class="ml-2 font-medium">WhatsApp</span>
                    </a>

                    <!-- Copier le lien -->
                    <button onclick="copyToClipboard('{{ route('actualite.show', $actualite->slug) }}')"
                            id="copyButton"
                            class="inline-flex items-center px-4 py-2 bg-gray-700 text-white rounded-full hover:bg-gray-600 transition duration-300 shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <span class="ml-2 font-medium">Copier le lien</span>
                    </button>
                </div>
            </div>
        </div>

        <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                const button = document.getElementById('copyButton');
                const originalHTML = button.innerHTML;
                button.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg><span class="ml-2 font-medium">Copié !</span>';
                button.classList.add('bg-green-600');
                button.classList.remove('bg-gray-700');

                setTimeout(function() {
                    button.innerHTML = originalHTML;
                    button.classList.remove('bg-green-600');
                    button.classList.add('bg-gray-700');
                }, 2000);
            }).catch(function(err) {
                console.error('Erreur lors de la copie:', err);
                alert('Impossible de copier le lien');
            });
        }
        </script>

        <!-- Bouton retour -->
        <div class="mt-12 text-center">
            <a href="{{ route('homepage') }}#actualites"
               class="inline-flex items-center px-6 py-3 bg-gray-800 text-white font-semibold rounded-full hover:bg-gray-900 transition duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour aux actualités
            </a>
        </div>
    </div>
</div>

<!-- Section CTA final -->
@include('homepage._cta-final')
@endsection
