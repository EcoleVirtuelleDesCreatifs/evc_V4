@extends('layouts.app')

@section('title', $categoryName . ' - WebTV EVC | Vidéos et Tutoriels')
@section('description', 'Découvrez toutes nos vidéos et tutoriels sur ' . $categoryName . '. Formation gratuite en ligne avec l\'École Virtuelle des Créatifs.')

@section('content')

<!-- Hero Section -->
<div class="bg-gradient-to-b from-[#0a1128] via-[#001f54] to-[#0a1128] pt-[150px] pb-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="mb-8" data-aos="fade-right">
            <nav class="flex items-center gap-2 text-sm">
                <a href="{{ route('homepage') }}" class="text-gray-400 hover:text-orange-400 transition-colors">
                    <i class="fas fa-home"></i> Accueil
                </a>
                <i class="fas fa-chevron-right text-gray-600 text-xs"></i>
                <a href="{{ route('webtv') }}" class="text-gray-400 hover:text-orange-400 transition-colors">
                    WebTV
                </a>
                <i class="fas fa-chevron-right text-gray-600 text-xs"></i>
                <span class="text-orange-400 font-semibold">{{ $categoryName }}</span>
            </nav>
        </div>

        <!-- Titre de la thématique -->
        <div class="text-center mb-12" data-aos="zoom-in" data-aos-duration="1000">
            <div class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-orange-500/20 to-orange-600/20 border-2 border-orange-400/50 rounded-full mb-6 backdrop-blur-sm">
                <i class="fas fa-video text-orange-400 text-xl"></i>
                <span class="text-orange-300 font-bold text-sm uppercase tracking-wider">Thématique</span>
            </div>

            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-white mb-6 leading-tight">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 via-orange-500 to-orange-600">
                    {{ $categoryName }}
                </span>
            </h1>

            <p class="text-lg md:text-xl text-gray-300 max-w-3xl mx-auto">
                Découvrez toutes nos vidéos et tutoriels pour maîtriser le {{ $categoryName }}
            </p>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6" data-aos="fade-up" data-aos-delay="200">
            <div class="bg-white/5 backdrop-blur-md rounded-2xl p-4 md:p-6 border border-white/10 text-center">
                <i class="fas fa-video text-orange-500 text-3xl mb-2"></i>
                <div class="text-2xl md:text-3xl font-bold text-white mb-1">{{ $videos->count() }}</div>
                <div class="text-sm text-gray-400">Vidéos</div>
            </div>
            <div class="bg-white/5 backdrop-blur-md rounded-2xl p-4 md:p-6 border border-white/10 text-center">
                <i class="fas fa-clock text-blue-500 text-3xl mb-2"></i>
                <div class="text-2xl md:text-3xl font-bold text-white mb-1">{{ $videos->count() * 15 }}</div>
                <div class="text-sm text-gray-400">Minutes</div>
            </div>
            <div class="bg-white/5 backdrop-blur-md rounded-2xl p-4 md:p-6 border border-white/10 text-center">
                <i class="fas fa-layer-group text-green-500 text-3xl mb-2"></i>
                <div class="text-2xl md:text-3xl font-bold text-white mb-1">Tous</div>
                <div class="text-sm text-gray-400">Niveaux</div>
            </div>
            <div class="bg-white/5 backdrop-blur-md rounded-2xl p-4 md:p-6 border border-white/10 text-center">
                <i class="fas fa-graduation-cap text-purple-500 text-3xl mb-2"></i>
                <div class="text-2xl md:text-3xl font-bold text-white mb-1">100%</div>
                <div class="text-sm text-gray-400">Gratuit</div>
            </div>
        </div>
    </div>
</div>

<!-- Section des vidéos -->
<div class="bg-gradient-to-b from-[#0a1128] to-[#001233] py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        @if($videos->count() > 0)
        <!-- Grille de vidéos -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @foreach($videos as $index => $video)
            <div class="group" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="bg-gradient-to-br from-white/5 to-white/10 backdrop-blur-lg rounded-3xl overflow-hidden border border-white/10 h-full transition-all duration-500 hover:border-orange-500/50 hover:transform hover:scale-105 hover:shadow-2xl hover:shadow-orange-500/20">
                    <!-- Vignette vidéo / Placeholder -->
                    <div class="relative aspect-video bg-gradient-to-br from-orange-500/20 to-orange-600/20 flex items-center justify-center overflow-hidden">
                        <div class="absolute inset-0 bg-black/50"></div>
                        <div class="relative z-10">
                            <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-gradient-to-r from-orange-500 to-orange-600 flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-play text-white text-2xl md:text-3xl ml-1"></i>
                            </div>
                        </div>
                        <!-- Badge type -->
                        @if($video->type === 'live')
                        <div class="absolute top-4 left-4 inline-flex items-center gap-2 px-3 py-1.5 bg-red-500/90 backdrop-blur-sm rounded-full">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-300 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                            </span>
                            <span class="text-white text-xs font-bold uppercase">Live</span>
                        </div>
                        @else
                        <div class="absolute top-4 left-4 inline-flex items-center gap-2 px-3 py-1.5 bg-orange-500/90 backdrop-blur-sm rounded-full">
                            <i class="fas fa-video text-white text-xs"></i>
                            <span class="text-white text-xs font-bold uppercase">Replay</span>
                        </div>
                        @endif
                    </div>

                    <!-- Contenu -->
                    <div class="p-6">
                        <!-- Titre -->
                        <h3 class="text-xl font-bold text-white mb-3 line-clamp-2 group-hover:text-orange-400 transition-colors">
                            {{ $video->title }}
                        </h3>

                        <!-- Description -->
                        <p class="text-sm text-gray-400 mb-4 line-clamp-2">
                            {{ $video->description ?? 'Découvrez cette vidéo exclusive sur notre WebTV' }}
                        </p>

                        <!-- Meta info -->
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                            <span class="flex items-center gap-1">
                                <i class="fas fa-calendar"></i>
                                {{ $video->created_at->format('d/m/Y') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="fas fa-eye"></i>
                                {{ number_format($video->view_count) }} vues
                            </span>
                        </div>

                        <!-- Bouton voir -->
                        <button onclick="openVideoModal({{ $video->id }}, '{{ addslashes($video->title) }}', '{{ $video->embed_code }}')" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-orange-500/50 flex items-center justify-center gap-2">
                            <i class="fas fa-play"></i>
                            <span>Regarder</span>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- État vide -->
        <div class="text-center py-16" data-aos="fade-up">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-orange-500/10 border-2 border-orange-500/30 mb-6">
                <i class="fas fa-video text-orange-500 text-4xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-4">Aucune vidéo disponible</h3>
            <p class="text-gray-400 mb-8 max-w-md mx-auto">
                Les vidéos pour cette thématique seront bientôt disponibles. Revenez régulièrement ou abonnez-vous pour être notifié.
            </p>
            <a href="{{ route('webtv') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-orange-500/50">
                <i class="fas fa-arrow-left"></i>
                <span>Retour à la WebTV</span>
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Section CTA Abonnement -->
<div class="bg-gradient-to-b from-[#001233] to-black py-20">
    <div class="mx-auto max-w-4xl px-6 lg:px-8 text-center">
        <div data-aos="zoom-in">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500/10 border border-orange-500/30 rounded-full mb-6">
                <i class="fas fa-bell text-orange-500"></i>
                <span class="text-orange-400 font-semibold text-sm">Ne Manquez Aucune Nouveauté</span>
            </div>

            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                Restez Informé des Nouvelles Vidéos
            </h2>
            <p class="text-lg text-gray-400 mb-8 max-w-2xl mx-auto">
                Abonnez-vous pour recevoir une notification à chaque nouvelle vidéo sur {{ $categoryName }}
            </p>

            <button onclick="openSubscriptionModal()" class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-2xl hover:shadow-orange-500/50">
                <i class="fas fa-bell"></i>
                <span>S'abonner Gratuitement</span>
            </button>
        </div>
    </div>
</div>

<!-- Modale d'Abonnement -->
<div id="subscriptionModal" class="fixed inset-0 z-[9998] hidden opacity-0 transition-all duration-500">
    <!-- Background -->
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeSubscriptionModal()"></div>

    <!-- Container -->
    <div class="relative w-full h-full flex items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl shadow-2xl border border-slate-700/50 transform scale-95" id="subscriptionContent">

            <!-- Header -->
            <div class="relative px-6 py-5 border-b border-slate-700/50">
                <button onclick="closeSubscriptionModal()" class="absolute top-4 right-4 w-8 h-8 bg-slate-800/60 hover:bg-slate-700 rounded-lg flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-slate-400"></i>
                </button>

                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-bell text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">S'abonner</h3>
                        <p class="text-sm text-slate-400">Restez informé des nouveautés</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <form id="subscriptionForm" action="{{ route('webtv.subscribe') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="category" value="{{ $category }}">

                <div class="space-y-4">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                            <i class="fas fa-envelope text-slate-500 mr-2"></i>
                            Adresse e-mail
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            required
                            placeholder="votre@email.com"
                            class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all"
                        >
                    </div>

                    <!-- Prénom (optionnel) -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-slate-300 mb-2">
                            <i class="fas fa-user text-slate-500 mr-2"></i>
                            Prénom <span class="text-slate-500 text-xs">(optionnel)</span>
                        </label>
                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            placeholder="Votre prénom"
                            class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all"
                        >
                    </div>

                    <!-- Info -->
                    <div class="bg-blue-500/10 border border-blue-500/20 rounded-lg p-4">
                        <div class="flex gap-3">
                            <i class="fas fa-info-circle text-blue-400 mt-1 flex-shrink-0"></i>
                            <div class="text-sm text-blue-200">
                                <p class="font-medium mb-1">Notifications par e-mail</p>
                                <p class="text-blue-300/80">Vous recevrez un e-mail à chaque nouvelle vidéo publiée dans la catégorie <strong>{{ $categoryName }}</strong>.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex gap-3 mt-6">
                    <button
                        type="button"
                        onclick="closeSubscriptionModal()"
                        class="flex-1 px-4 py-3 bg-slate-800/60 hover:bg-slate-700 text-slate-300 hover:text-white rounded-lg transition-colors font-medium"
                    >
                        Annuler
                    </button>
                    <button
                        type="submit"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-lg transition-all font-bold shadow-lg shadow-orange-500/30 flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-bell"></i>
                        <span>S'abonner</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Modale Vidéo Réaliste Dynamique - Bleu Sombre -->
<div id="videoModal" class="fixed inset-0 z-[9999] hidden opacity-0 transition-all duration-500">
    <!-- Background bleu très sombre réaliste -->
    <div class="absolute inset-0 bg-gradient-to-br from-[#020617] via-[#0c1222] to-[#030712] backdrop-blur-xl"></div>

    <!-- Particules subtiles bleu sombre -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-600/8 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-slate-600/10 rounded-full blur-[100px] animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/2 w-48 h-48 bg-blue-700/6 rounded-full blur-[90px] animate-pulse" style="animation-delay: 0.5s;"></div>
    </div>

    <!-- Container principal avec taille moyenne optimisée -->
    <div class="relative w-full h-full flex items-center justify-center p-3 md:p-6">
        <div class="relative w-full max-w-4xl transform transition-all duration-700 scale-95" id="modalContent">
            <!-- Bouton Fermer Minimaliste Révolutionnaire -->
            <div class="absolute -top-14 right-0 z-50 flex items-center gap-3">
                <div class="hidden md:flex items-center gap-2 text-white/50 text-xs">
                    <kbd class="px-2 py-1 bg-white/10 rounded border border-white/20 font-mono text-xs">ESC</kbd>
                </div>
                <button onclick="closeVideoModal()" class="group relative w-11 h-11 bg-slate-800/60 hover:bg-slate-700 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110 border border-slate-700/50">
                    <i class="fas fa-times text-slate-300 group-hover:text-white text-lg relative z-10"></i>
                </button>
            </div>

            <!-- Card Réaliste Sombre -->
            <div class="relative rounded-2xl overflow-hidden" style="box-shadow: 0 20px 60px rgba(15, 23, 42, 0.6), 0 0 0 1px rgba(71, 85, 105, 0.2);">
                <!-- Bordure subtile réaliste -->
                <div class="absolute inset-0 rounded-2xl border border-slate-700/50"></div>

                <!-- Background bleu très sombre -->
                <div class="relative bg-gradient-to-br from-[#0f172a]/98 via-[#1e293b]/95 to-[#0f172a]/98 backdrop-blur-xl">

                    <!-- Header Réaliste avec Contrôles -->
                    <div class="relative px-4 md:px-6 py-3 border-b border-slate-700/50 bg-gradient-to-r from-slate-800/40 via-slate-900/20 to-slate-800/40">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <!-- Titre + Badge inline pour gagner de l'espace -->
                                <div class="flex items-center gap-3 mb-2">
                                    <!-- Badge Live réaliste -->
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-red-600 rounded backdrop-blur-sm flex-shrink-0">
                                        <span class="relative flex h-1.5 w-1.5">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-white"></span>
                                        </span>
                                        <span class="text-white text-[10px] font-bold uppercase tracking-wider">En Direct</span>
                                    </div>

                                    <!-- Compteur durée dynamique -->
                                    <div class="flex items-center gap-1.5 text-xs text-slate-400">
                                        <i class="fas fa-clock text-slate-400 text-[10px]"></i>
                                        <span id="videoDuration" class="font-medium text-slate-300 tabular-nums">00:00</span>
                                    </div>
                                </div>

                                <!-- Titre réaliste -->
                                <h3 id="videoModalTitle" class="text-base md:text-xl font-bold text-white leading-tight truncate">
                                    <!-- Titre inséré par JS -->
                                </h3>
                            </div>

                            <!-- Contrôles réalistes -->
                            <div class="hidden sm:flex items-center gap-1 flex-shrink-0">
                                <button onclick="toggleQuality()" class="w-8 h-8 bg-slate-800/60 hover:bg-slate-700 rounded flex items-center justify-center transition-all duration-200 group" title="Qualité">
                                    <span class="text-slate-300 group-hover:text-white text-[10px] font-bold">HD</span>
                                </button>
                                <button onclick="toggleSubtitles()" class="w-8 h-8 bg-slate-800/60 hover:bg-slate-700 rounded flex items-center justify-center transition-all duration-200 group" title="Sous-titres">
                                    <i class="fas fa-closed-captioning text-slate-300 group-hover:text-white text-xs"></i>
                                </button>
                                <button onclick="toggleSettings()" class="w-8 h-8 bg-slate-800/60 hover:bg-slate-700 rounded flex items-center justify-center transition-all duration-200 group" title="Paramètres">
                                    <i class="fas fa-cog text-slate-300 group-hover:text-white text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Player vidéo avec effet de chargement -->
                    <div class="relative aspect-video bg-black">
                        <div id="videoPlayerContainer" class="w-full h-full relative z-10">
                            <!-- Le code embed sera inséré ici -->
                        </div>

                        <!-- Loader réaliste -->
                        <div id="videoLoader" class="absolute inset-0 flex items-center justify-center bg-[#0f172a] z-20">
                            <div class="text-center">
                                <div class="relative inline-block">
                                    <div class="w-12 h-12 border-3 border-slate-700 border-t-slate-400 rounded-full animate-spin"></div>
                                </div>
                                <p class="mt-3 text-slate-300 font-medium text-sm">Chargement...</p>
                            </div>
                        </div>

                        <!-- Barre de Contrôle Vidéo Dynamique -->
                        <div id="videoControls" class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 to-transparent p-4 opacity-0 hover:opacity-100 transition-opacity duration-300 z-30">
                            <!-- Barre de progression -->
                            <div class="mb-3 cursor-pointer group" onclick="seekVideo(event)">
                                <div class="h-1 bg-slate-700 rounded-full overflow-hidden">
                                    <div id="progressBar" class="h-full bg-blue-500 rounded-full transition-all duration-100" style="width: 0%"></div>
                                </div>
                                <div class="flex justify-between text-[10px] text-slate-400 mt-1 tabular-nums">
                                    <span id="currentTime">0:00</span>
                                    <span id="totalTime">0:00</span>
                                </div>
                            </div>

                            <!-- Contrôles principaux -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <button onclick="togglePlay()" class="w-9 h-9 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center transition-colors" title="Play/Pause">
                                        <i id="playIcon" class="fas fa-play text-white text-sm ml-0.5"></i>
                                    </button>

                                    <div class="flex items-center gap-1.5 px-2">
                                        <button onclick="changeVolume(-0.1)" class="text-white/70 hover:text-white transition-colors">
                                            <i class="fas fa-volume-down text-xs"></i>
                                        </button>
                                        <div class="w-16 h-1 bg-slate-700 rounded-full overflow-hidden cursor-pointer" onclick="setVolume(event)">
                                            <div id="volumeBar" class="h-full bg-white rounded-full" style="width: 100%"></div>
                                        </div>
                                        <button onclick="changeVolume(0.1)" class="text-white/70 hover:text-white transition-colors">
                                            <i class="fas fa-volume-up text-xs"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1.5">
                                    <button onclick="toggleTheater()" class="w-8 h-8 bg-slate-800/60 hover:bg-slate-700 rounded flex items-center justify-center transition-colors" title="Mode Théâtre">
                                        <i class="fas fa-expand text-white text-xs"></i>
                                    </button>
                                    <button onclick="toggleFullscreen()" class="w-8 h-8 bg-slate-800/60 hover:bg-slate-700 rounded flex items-center justify-center transition-colors" title="Plein écran">
                                        <i class="fas fa-expand-arrows-alt text-white text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Réaliste -->
                    <div class="px-4 md:px-6 py-3 bg-slate-900/60 border-t border-slate-700/50">
                        <div class="flex items-center justify-between gap-3">
                            <!-- Infos vidéo -->
                            <div class="flex items-center gap-3 text-xs text-slate-400">
                                <div class="flex items-center gap-1.5">
                                    <i class="fas fa-users text-slate-500 text-[10px]"></i>
                                    <span id="viewCount" class="text-slate-300 font-medium">0</span>
                                    <span>spectateurs</span>
                                </div>
                                <div class="hidden md:flex items-center gap-1.5">
                                    <i class="fas fa-thumbs-up text-slate-500 text-[10px]"></i>
                                    <span class="text-slate-300 font-medium" id="likeCount">0</span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2">
                                <button onclick="toggleLike()" class="px-3 py-1.5 bg-slate-800/60 hover:bg-slate-700 rounded flex items-center gap-1.5 text-slate-300 hover:text-white text-xs transition-colors" title="J'aime">
                                    <i id="likeIcon" class="far fa-thumbs-up text-[10px]"></i>
                                    <span class="hidden sm:inline">J'aime</span>
                                </button>
                                <button onclick="shareVideo()" class="px-3 py-1.5 bg-slate-800/60 hover:bg-slate-700 rounded flex items-center gap-1.5 text-slate-300 hover:text-white text-xs transition-colors" title="Partager">
                                    <i class="fas fa-share text-[10px]"></i>
                                    <span class="hidden sm:inline">Partager</span>
                                </button>
                                <button onclick="subscribeToChannel()" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 rounded flex items-center gap-1.5 text-white text-xs font-medium transition-colors">
                                    <i class="fas fa-bell text-[10px]"></i>
                                    <span>S'abonner</span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Badge Stats Réaliste -->
            <div class="absolute -bottom-11 left-0 right-0 flex justify-center">
                <div class="flex items-center gap-2.5 px-3 py-1.5 bg-slate-900/90 backdrop-blur-md rounded-lg border border-slate-700/50 shadow-xl">
                    <div class="flex items-center gap-1 text-xs">
                        <span class="relative flex h-1.5 w-1.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-red-500"></span>
                        </span>
                        <span class="text-red-400 font-medium text-[10px]">LIVE</span>
                    </div>
                    <div class="w-px h-3 bg-slate-700"></div>
                    <div class="flex items-center gap-1 text-xs">
                        <i class="fas fa-eye text-slate-400 text-[10px]"></i>
                        <span class="text-white font-semibold tabular-nums" id="viewCountBadge">0</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
/* Animation gradient de la bordure */
@keyframes gradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Animation d'entrée révolutionnaire */
@keyframes modalEntry {
    from {
        transform: scale(0.9) translateY(20px);
        opacity: 0;
    }
    to {
        transform: scale(1) translateY(0);
        opacity: 1;
    }
}

/* Animation de flottement subtil */
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

/* Animation de respiration */
@keyframes breathe {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 0.6; }
}

/* Appliquer l'animation d'entrée */
#modalContent {
    animation: modalEntry 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

/* Style des touches clavier */
kbd {
    font-family: 'SF Mono', 'Monaco', 'Inconsolata', 'Fira Mono', monospace;
    text-shadow: 0 1px 2px rgba(0,0,0,0.5);
}

/* Animation du loader révolutionnaire */
#videoLoader {
    backdrop-filter: blur(10px);
}

/* Effet de pulsation bleu nuit sur le badge Live */
@keyframes pulse-glow-cyan {
    0%, 100% {
        box-shadow: 0 0 15px rgba(34, 211, 238, 0.4);
    }
    50% {
        box-shadow: 0 0 30px rgba(34, 211, 238, 0.7);
    }
}

/* Animation de lueur bleu glacé */
@keyframes glow-blue {
    0%, 100% {
        filter: drop-shadow(0 0 10px rgba(59, 130, 246, 0.3));
    }
    50% {
        filter: drop-shadow(0 0 20px rgba(59, 130, 246, 0.6));
    }
}

/* Effet hover élégant bleu nuit */
button:hover {
    filter: brightness(1.15);
}

/* Animation de shimmer bleu nuit */
@keyframes shimmer {
    0% {
        background-position: -100% 0;
    }
    100% {
        background-position: 200% 0;
    }
}

/* Effet de vague lumineuse */
@keyframes wave {
    0%, 100% {
        transform: translateX(-50%) translateY(0);
        opacity: 0.3;
    }
    50% {
        transform: translateX(-50%) translateY(-10px);
        opacity: 0.6;
    }
}

/* Bordure bleu nuit premium */
.modal-border-glow {
    box-shadow:
        0 0 20px rgba(34, 211, 238, 0.3),
        0 0 40px rgba(59, 130, 246, 0.2),
        0 0 60px rgba(99, 102, 241, 0.1);
}

/* Animation du loader premium */
#videoLoader {
    backdrop-filter: blur(15px);
    animation: breathe 3s ease-in-out infinite;
}

/* Animations modale d'abonnement */
#subscriptionContent {
    transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
}

#subscriptionModal.opacity-100 #subscriptionContent {
    transform: scale(1);
}
</style>

<script>
// Variables globales
let currentVideoId = null;
let viewCountInterval = null;

// Fonction pour ouvrir la modale vidéo avec animations
function openVideoModal(videoId, title, embedCode) {
    currentVideoId = videoId;
    const modal = document.getElementById('videoModal');
    const modalTitle = document.getElementById('videoModalTitle');
    const playerContainer = document.getElementById('videoPlayerContainer');
    const loader = document.getElementById('videoLoader');
    const modalContent = document.getElementById('modalContent');

    // Définir le titre
    modalTitle.textContent = title;

    // Reset de l'animation du contenu
    modalContent.style.animation = 'none';
    setTimeout(() => {
        modalContent.style.animation = 'modalEntry 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards';
    }, 10);

    // Afficher le loader
    if (loader) loader.classList.remove('hidden');

    // Insérer le code embed avec délai pour effet de chargement
    setTimeout(() => {
        if (embedCode) {
            playerContainer.innerHTML = embedCode;
        } else {
            playerContainer.innerHTML = `
                <div class="flex items-center justify-center h-full bg-gradient-to-br from-red-900/20 to-orange-900/20">
                    <div class="text-center p-8">
                        <i class="fas fa-exclamation-triangle text-orange-500 text-5xl mb-4"></i>
                        <p class="text-white text-xl font-semibold mb-2">Vidéo non disponible</p>
                        <p class="text-gray-400">Cette vidéo ne peut pas être chargée pour le moment</p>
                    </div>
                </div>
            `;
        }

        // Masquer le loader après le chargement
        if (loader) {
            setTimeout(() => {
                loader.style.opacity = '0';
                loader.style.transition = 'opacity 0.3s';
                setTimeout(() => loader.classList.add('hidden'), 300);
            }, 800);
        }
    }, 500);

    // Afficher la modale avec animation
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modal.classList.add('opacity-100');
    }, 10);

    // Bloquer le scroll du body
    document.body.style.overflow = 'hidden';

    // Démarrer le compteur de vues (simulation)
    startViewCounter();

    // Mettre à jour le timestamp (ancienne fonction, sera remplacée par la durée)
    // updateTimestamp();

    // Démarrer le compteur de durée
    updateDuration();

    // Initialiser les likes
    likeCountValue = Math.floor(Math.random() * 50) + 10;
    const likeCountEl = document.getElementById('likeCount');
    if (likeCountEl) likeCountEl.textContent = likeCountValue;
}

// Fonction pour fermer la modale vidéo
function closeVideoModal() {
    const modal = document.getElementById('videoModal');
    const playerContainer = document.getElementById('videoPlayerContainer');
    const loader = document.getElementById('videoLoader');

    // Animation de fermeture
    modal.classList.remove('opacity-100');
    modal.classList.add('opacity-0');

    // Arrêter les compteurs
    if (viewCountInterval) {
        clearInterval(viewCountInterval);
        viewCountInterval = null;
    }
    if (durationInterval) {
        clearInterval(durationInterval);
        durationInterval = null;
    }

    setTimeout(() => {
        modal.classList.add('hidden');
        // Vider le player pour arrêter la vidéo
        playerContainer.innerHTML = '';
        currentVideoId = null;

        // Réinitialiser le loader
        if (loader) {
            loader.classList.remove('hidden');
            loader.style.opacity = '1';
        }

        // Débloquer le scroll
        document.body.style.overflow = '';
    }, 500);
}

// Simuler un compteur de vues dynamique
function startViewCounter() {
    const viewCountEl = document.getElementById('viewCount');
    const viewCountBadgeEl = document.getElementById('viewCountBadge');

    if (!viewCountEl) return;

    let count = Math.floor(Math.random() * 50) + 10;
    viewCountEl.textContent = count;
    if (viewCountBadgeEl) viewCountBadgeEl.textContent = count;

    viewCountInterval = setInterval(() => {
        count += Math.floor(Math.random() * 3);
        viewCountEl.textContent = count;
        if (viewCountBadgeEl) viewCountBadgeEl.textContent = count;
    }, 5000);
}

// Mettre à jour le timestamp
function updateTimestamp() {
    const timestampEl = document.getElementById('videoTimestamp');
    if (!timestampEl) return;

    const now = new Date();
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    timestampEl.textContent = `${hours}:${minutes}`;
}

// Fonction de partage (simulation)
function shareVideo() {
    if (navigator.share && currentVideoId) {
        navigator.share({
            title: document.getElementById('videoModalTitle').textContent,
            text: 'Regardez cette vidéo sur la WebTV de l\'EVC',
            url: window.location.href
        }).catch(err => console.log('Erreur de partage:', err));
    } else {
        // Copier l'URL dans le presse-papier
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            alert('✅ Lien copié dans le presse-papier !');
        }).catch(() => {
            alert('❌ Impossible de copier le lien');
        });
    }
}

// Fonction d'abonnement (redirection)
function subscribeToChannel() {
    // Fermer la modale vidéo et ouvrir la modale d'abonnement
    closeVideoModal();
    setTimeout(() => openSubscriptionModal(), 600);
}

// ===== GESTION MODALE D'ABONNEMENT =====

// Ouvrir la modale d'abonnement
function openSubscriptionModal() {
    const modal = document.getElementById('subscriptionModal');
    const content = document.getElementById('subscriptionContent');

    if (!modal) return;

    // Afficher la modale
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modal.classList.add('opacity-100');
        if (content) {
            content.style.transform = 'scale(1)';
        }
    }, 10);

    // Bloquer le scroll
    document.body.style.overflow = 'hidden';
}

// Fermer la modale d'abonnement
function closeSubscriptionModal() {
    const modal = document.getElementById('subscriptionModal');
    const content = document.getElementById('subscriptionContent');

    if (!modal) return;

    // Animation de fermeture
    modal.classList.remove('opacity-100');
    modal.classList.add('opacity-0');
    if (content) {
        content.style.transform = 'scale(0.95)';
    }

    setTimeout(() => {
        modal.classList.add('hidden');
        // Débloquer le scroll
        document.body.style.overflow = '';

        // Réinitialiser le formulaire
        const form = document.getElementById('subscriptionForm');
        if (form) form.reset();
    }, 500);
}

// ===== NOUVELLES FONCTIONNALITÉS DYNAMIQUES =====

// Variables pour les contrôles vidéo
let isPlaying = false;
let currentVolume = 1;
let isLiked = false;
let likeCountValue = 0;
let durationInterval = null;

// Toggle Play/Pause
function togglePlay() {
    isPlaying = !isPlaying;
    const icon = document.getElementById('playIcon');
    if (icon) {
        icon.className = isPlaying ? 'fas fa-pause text-white text-sm' : 'fas fa-play text-white text-sm ml-0.5';
    }
    // Note: Pour contrôler vraiment la vidéo, il faudrait accéder à l'API du player (Vimeo, YouTube, etc.)
}

// Gestion du volume
function changeVolume(delta) {
    currentVolume = Math.max(0, Math.min(1, currentVolume + delta));
    updateVolumeBar();
}

function setVolume(event) {
    const bar = event.currentTarget;
    const rect = bar.getBoundingClientRect();
    const x = event.clientX - rect.left;
    currentVolume = x / rect.width;
    updateVolumeBar();
}

function updateVolumeBar() {
    const volumeBar = document.getElementById('volumeBar');
    if (volumeBar) {
        volumeBar.style.width = (currentVolume * 100) + '%';
    }
}

// Navigation dans la vidéo
function seekVideo(event) {
    const bar = event.currentTarget.querySelector('.h-1');
    if (!bar) return;
    const rect = bar.getBoundingClientRect();
    const x = event.clientX - rect.left;
    const percent = (x / rect.width) * 100;
    const progressBar = document.getElementById('progressBar');
    if (progressBar) {
        progressBar.style.width = percent + '%';
    }
}

// Toggle plein écran
function toggleFullscreen() {
    const modal = document.getElementById('videoModal');
    if (!document.fullscreenElement) {
        modal.requestFullscreen().catch(err => {
            console.log('Erreur fullscreen:', err);
        });
    } else {
        document.exitFullscreen();
    }
}

// Toggle mode théâtre
function toggleTheater() {
    const modalContent = document.getElementById('modalContent');
    if (modalContent.classList.contains('max-w-4xl')) {
        modalContent.classList.remove('max-w-4xl');
        modalContent.classList.add('max-w-6xl');
    } else {
        modalContent.classList.remove('max-w-6xl');
        modalContent.classList.add('max-w-4xl');
    }
}

// Toggle qualité (simulation)
function toggleQuality() {
    const qualities = ['HD', '4K', 'SD', 'Auto'];
    const btn = event.currentTarget;
    const currentQuality = btn.querySelector('span').textContent;
    const currentIndex = qualities.indexOf(currentQuality);
    const nextQuality = qualities[(currentIndex + 1) % qualities.length];
    btn.querySelector('span').textContent = nextQuality;
}

// Toggle sous-titres (simulation)
function toggleSubtitles() {
    const btn = event.currentTarget;
    const icon = btn.querySelector('i');
    if (icon.classList.contains('fa-closed-captioning')) {
        icon.style.color = '#3b82f6'; // Activé
    } else {
        icon.style.color = ''; // Désactivé
    }
}

// Toggle paramètres (simulation)
function toggleSettings() {
    alert('⚙️ Panneau de paramètres\n\n• Vitesse de lecture\n• Qualité vidéo\n• Sous-titres\n• Langue audio');
}

// Toggle Like
function toggleLike() {
    isLiked = !isLiked;
    const icon = document.getElementById('likeIcon');
    const likeCount = document.getElementById('likeCount');

    if (isLiked) {
        likeCountValue++;
        if (icon) icon.className = 'fas fa-thumbs-up text-[10px]'; // Rempli
    } else {
        likeCountValue--;
        if (icon) icon.className = 'far fa-thumbs-up text-[10px]'; // Vide
    }

    if (likeCount) {
        likeCount.textContent = likeCountValue;
    }
}

// Mettre à jour la durée de la vidéo
function updateDuration() {
    const durationEl = document.getElementById('videoDuration');
    if (!durationEl) return;

    let seconds = 0;
    durationInterval = setInterval(() => {
        seconds++;
        const minutes = Math.floor(seconds / 60);
        const secs = seconds % 60;
        durationEl.textContent = `${minutes}:${secs.toString().padStart(2, '0')}`;
    }, 1000);
}

// Fermer avec la touche Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        // Fermer la modale vidéo si ouverte
        const videoModal = document.getElementById('videoModal');
        if (videoModal && !videoModal.classList.contains('hidden')) {
            closeVideoModal();
        }
        // Fermer la modale d'abonnement si ouverte
        const subscriptionModal = document.getElementById('subscriptionModal');
        if (subscriptionModal && !subscriptionModal.classList.contains('hidden')) {
            closeSubscriptionModal();
        }
    }
});

// Fermer en cliquant en dehors
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('videoModal');
    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === this) {
                closeVideoModal();
            }
        });
    }
});
</script>

@endsection
