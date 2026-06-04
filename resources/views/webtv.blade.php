@extends('layouts.app')

@section('title', 'WebTV EVC | Tutoriels Design Graphique & Marketing Digital | Vidéos Formation')
@section('description', 'Accédez gratuitement à notre WebTV : tutoriels design graphique, masterclass marketing digital, conférences tech et interviews d\'experts. Apprenez le design et le digital en vidéo avec l\'EVC Abidjan.')
@section('keywords', 'WebTV EVC, tutoriels design graphique, vidéos formation, masterclass digital, conférences tech, tutoriels Photoshop, formation vidéo gratuite, cours en ligne Abidjan, chaîne YouTube EVC')

@section('content')

<style>
@keyframes glow-pulse {
    0%, 100% { box-shadow: 0 0 20px rgba(249,115,22,.3); }
    50% { box-shadow: 0 0 40px rgba(249,115,22,.5); }
}

@keyframes glow-pulse-strong {
    0%, 100% {
        box-shadow: 0 0 40px rgba(34,197,94,0.6), 0 0 80px rgba(34,197,94,0.3);
        transform: scale(1);
    }
    50% {
        box-shadow: 0 0 60px rgba(34,197,94,0.8), 0 0 120px rgba(34,197,94,0.5);
        transform: scale(1.05);
    }
}

@keyframes glow-pulse-red {
    0%, 100% {
        box-shadow: 0 0 40px rgba(239,68,68,0.6), 0 0 80px rgba(239,68,68,0.3);
        transform: scale(1);
    }
    50% {
        box-shadow: 0 0 60px rgba(239,68,68,0.8), 0 0 120px rgba(239,68,68,0.5);
        transform: scale(1.05);
    }
}

@keyframes glow-pulse-orange {
    0%, 100% {
        box-shadow: 0 0 40px rgba(251,146,60,0.6), 0 0 80px rgba(251,146,60,0.3);
        transform: scale(1);
    }
    50% {
        box-shadow: 0 0 60px rgba(251,146,60,0.8), 0 0 120px rgba(251,146,60,0.5);
        transform: scale(1.05);
    }
}

@keyframes pulse-line {
    0%, 100% {
        opacity: 0.5;
        transform: scaleX(0.8);
    }
    50% {
        opacity: 1;
        transform: scaleX(1);
    }
}
</style>

<!-- Section Chaîne YouTube / Player WebTV -->
<div class="bg-gradient-to-b from-[#0a1128] via-[#001f54] to-[#0a1128] pt-[300px] pb-8 md:pb-12">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="text-center mb-8 md:mb-12 relative" data-aos="zoom-in" data-aos-duration="1000">
            <!-- Titre principal ultra-stylisé -->
            <div class="relative mb-6">
                <h1 class="relative flex items-center justify-center gap-2 md:gap-3 mb-6">
                    <span class="text-5xl sm:text-6xl md:text-6xl lg:text-7xl font-extrabold tracking-tight bg-gradient-to-r from-orange-400 via-orange-500 to-orange-400 bg-clip-text text-transparent"
                          style="background-size: 200% 200%; animation: gradient-shift 6s ease infinite;">WEB</span>
                    <span class="inline-flex items-center justify-center px-5 py-2.5 md:px-6 md:py-3 text-4xl sm:text-5xl md:text-4xl lg:text-5xl font-extrabold text-white rounded-full bg-gradient-to-r from-orange-500 to-orange-600 shadow-2xl"
                          style="animation: glow-pulse 2.5s ease-in-out infinite;">
                        TV
                    </span>
                    <span class="text-5xl sm:text-6xl md:text-6xl lg:text-7xl font-extrabold tracking-tight bg-gradient-to-r from-orange-400 via-orange-500 to-orange-400 bg-clip-text text-transparent"
                          style="background-size: 200% 200%; animation: gradient-shift 6s ease infinite;">EVC</span>
                </h1>

                <!-- Badge EN DIRECT / EN BOUCLE ultra-percutant -->
                @if($activePlaylist && $activePlaylist->type === 'live')
                <div class="inline-flex items-center gap-2 px-4 py-2 sm:gap-4 sm:px-8 sm:py-4 bg-gradient-to-r from-red-500/40 to-red-600/40 backdrop-blur-md border-2 border-red-400 rounded-full shadow-2xl hover:scale-110 transition-all duration-300" style="box-shadow: 0 0 40px rgba(239,68,68,0.6), 0 0 80px rgba(239,68,68,0.3);">
                    <span class="text-red-500 font-black text-xs sm:text-xl uppercase tracking-tight sm:tracking-widest whitespace-nowrap" style="text-shadow: 0 0 20px rgba(239,68,68,0.8);">
                        EN DIRECT MAINTENANT
                    </span>
                </div>
                @else
                <div class="inline-flex items-center gap-2 px-4 py-2 sm:gap-4 sm:px-8 sm:py-4 bg-gradient-to-r from-orange-500/40 to-orange-600/40 backdrop-blur-md border-2 border-orange-400 rounded-full shadow-2xl transform hover:scale-110 transition-all duration-300" style="animation: glow-pulse-orange 2s ease-in-out infinite; box-shadow: 0 0 40px rgba(251,146,60,0.6), 0 0 80px rgba(251,146,60,0.3);">
                    <span class="relative flex h-3 w-3 sm:h-5 sm:w-5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-300 opacity-75" style="animation-duration: 2s;"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 sm:h-5 sm:w-5 bg-orange-400 shadow-lg shadow-orange-400/50"></span>
                    </span>
                    <span class="text-orange-200 font-black text-xs sm:text-xl uppercase tracking-tight sm:tracking-widest whitespace-nowrap" style="text-shadow: 0 0 20px rgba(251,146,60,0.8);">
                        <i class="fas fa-infinity animate-pulse"></i> EN BOUCLE 24/7
                    </span>
                </div>
                @endif
            </div>
        </div>

<style>
@keyframes gradient-shift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}
</style>

        <!-- Featured Video - Grid Layout (même design que homepage) -->
        <div class="mt-6 md:mt-8" data-aos="fade-up">
            @if($activePlaylist)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8">
                <!-- Vidéo - 8 colonnes -->
                <div class="lg:col-span-8">
                    <div id="webtv-featured-wrap"
                         class="group relative rounded-3xl overflow-hidden bg-gradient-to-br from-white/5 to-white/10 backdrop-blur-lg border border-white/10 transition-all duration-500 hover:border-orange-500/50 hover:shadow-2xl hover:shadow-orange-500/20"
                         data-next-video="{{ (isset($nextVideo) && $nextVideo) ? route('webtv', ['video' => $nextVideo->id]) : '' }}"
                         data-current-title="{{ $activePlaylist->title }}">
                        <div class="relative aspect-video">
                            {!! $activePlaylist->generateEmbedCode() !!}
                        </div>
                    </div>
                </div>

                <!-- Informations - 4 colonnes -->
                <div class="lg:col-span-4 flex flex-col justify-center">
                    <div class="bg-gradient-to-br from-white/5 to-white/10 backdrop-blur-lg rounded-3xl p-6 md:p-8 border border-white/10">
                        <!-- Badge type -->
                        <div id="webtv-badge-container">
                        @if($activePlaylist->type === 'live')
                        <div class="inline-flex items-center gap-3 px-5 py-2.5 bg-gradient-to-r from-green-500/30 to-emerald-500/30 border-2 border-green-400 rounded-full mb-4 shadow-lg shadow-green-500/50" style="animation: glow-pulse 2s ease-in-out infinite;">
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-400"></span>
                            </span>
                            <span class="text-green-300 font-extrabold text-sm uppercase tracking-wider">EN DIRECT</span>
                        </div>
                        @else
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500/20 border border-orange-500/40 rounded-full mb-4">
                            <i class="fas fa-infinity text-orange-500"></i>
                            <span class="text-orange-400 font-bold text-xs uppercase">En Boucle</span>
                        </div>
                        @endif
                        </div>

                        <!-- Titre -->
                        <h3 id="webtv-title" class="text-2xl md:text-3xl font-bold text-white mb-4 leading-tight">
                            {{ $activePlaylist->title }}
                        </h3>

                        <!-- Description -->
                        <p id="webtv-description" class="text-gray-300 text-base md:text-lg mb-6 leading-relaxed">
                            @if($activePlaylist->description)
                                {{ $activePlaylist->description }}
                            @else
                                Découvrez ce contenu exclusif conçu pour développer vos compétences et propulser votre carrière dans le digital.
                            @endif
                        </p>

                        <!-- CTA -->
                        <div class="space-y-3">
                            <button onclick="openSubscriptionModal()" class="w-full inline-flex items-center justify-center gap-3 px-6 py-4 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full text-white text-base font-bold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 transform hover:scale-105 shadow-xl">
                                <i class="fas fa-bell text-xl"></i>
                                <span>Ne Manquez Rien</span>
                            </button>
                        </div>

                        <!-- Stats rapides -->
                        <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-white/10">
                            <div class="text-center">
                                <div id="webtv-view-count" class="text-xl font-bold text-orange-500">{{ number_format($activePlaylist->view_count) }}+</div>
                                <div class="text-xs text-gray-400">Vues</div>
                            </div>
                            <div class="text-center">
                                <div id="webtv-loop-icon" class="text-xl font-bold text-orange-500">
                                    @if($activePlaylist->loop_enabled)
                                        <i class="fas fa-infinity"></i>
                                    @else
                                        24/7
                                    @endif
                                </div>
                                <div class="text-xs text-gray-400">Disponible</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <!-- Message si aucune playlist -->
            <div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-white/5 to-white/10 backdrop-blur-lg border border-white/10 p-12 text-center">
                <i class="fas fa-tv text-gray-600 text-6xl mb-6"></i>
                <h3 class="text-2xl font-bold text-white mb-4">Aucune diffusion en cours</h3>
                <p class="text-gray-400 mb-8">Revenez bientôt pour découvrir nos prochaines vidéos et lives !</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Section Thématiques -->
<div class="bg-gradient-to-b from-[#0a1128] to-[#001233] py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Explorez Nos Contenus Par Thématique
            </h2>
            <p class="text-lg text-gray-400">
                Des tutoriels adaptés à tous les niveaux, du débutant à l'expert
            </p>
        </div>

        <!-- Grille de catégories (DYNAMIQUE) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($categories as $index => $category)
            <div class="group" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="relative bg-gradient-to-br from-white/5 to-white/10 backdrop-blur-lg rounded-3xl p-6 border border-white/10 h-full transition-all duration-500 hover:border-orange-500/50 hover:transform hover:scale-105 hover:shadow-2xl hover:shadow-orange-500/20 flex flex-col">
                    <!-- Icon -->
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $category['color'] }} flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas {{ $category['icon'] }} text-white text-2xl"></i>
                    </div>

                    <!-- Titre -->
                    <h3 class="text-xl font-bold text-white mb-2 group-hover:text-orange-400 transition-colors">
                        {{ $category['name'] }}
                    </h3>

                    <!-- Description -->
                    <p class="text-sm text-gray-400 mb-3">
                        {{ $category['description'] }}
                    </p>

                    <!-- Count (VRAI compteur depuis la base) -->
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-orange-500/10 border border-orange-500/30 rounded-full mb-4">
                        <i class="fas fa-play-circle text-orange-500 text-xs"></i>
                        <span class="text-orange-400 text-xs font-semibold">{{ $category['count'] }}</span>
                    </div>

                    <!-- Bouton Voir les vidéos -->
                    <a href="{{ route('webtv.thematique', $category['slug']) }}" class="mt-auto w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-orange-500/50 flex items-center justify-center gap-2">
                        <i class="fas fa-play"></i>
                        <span>Voir les vidéos</span>
                    </a>
                </div>
            </div>
            @empty
            <!-- Message si aucune catégorie -->
            <div class="col-span-full text-center py-12">
                <i class="fas fa-folder-open text-gray-600 text-5xl mb-4"></i>
                <p class="text-gray-400 text-lg">Aucune catégorie disponible pour le moment</p>
                <p class="text-gray-500 text-sm mt-2">Les vidéos seront bientôt ajoutées !</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Section CTA Final -->
<div class="bg-gradient-to-b from-[#0a1128] to-black py-20">
    <div class="mx-auto max-w-5xl px-6 lg:px-8 text-center">
        <div data-aos="zoom-in">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500/10 border border-orange-500/30 rounded-full mb-6">
                <i class="fas fa-bell text-orange-500"></i>
                <span class="text-orange-400 font-semibold text-sm">Ne Manquez Rien</span>
            </div>

            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Abonnez-vous à la WEBTV
            </h2>
            <p class="text-xl text-gray-400 mb-12 max-w-3xl mx-auto">
                Recevez une notification à chaque nouvelle vidéo et accédez à des contenus exclusifs pour développer vos compétences
            </p>

            <!-- Avantages -->
            <div class="grid md:grid-cols-3 gap-6 mb-12">
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                    <i class="fas fa-video text-orange-500 text-3xl mb-4"></i>
                    <h3 class="text-lg font-bold text-white mb-2">Nouveaux Tutoriels</h3>
                    <p class="text-sm text-gray-400">Chaque semaine</p>
                </div>
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                    <i class="fas fa-users text-orange-500 text-3xl mb-4"></i>
                    <h3 class="text-lg font-bold text-white mb-2">Communauté Active</h3>
                    <p class="text-sm text-gray-400">Échangez avec d'autres apprenants</p>
                </div>
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                    <i class="fas fa-gift text-orange-500 text-3xl mb-4"></i>
                    <h3 class="text-lg font-bold text-white mb-2">Contenus Exclusifs</h3>
                    <p class="text-sm text-gray-400">Accès à des ressources gratuites</p>
                </div>
            </div>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <button onclick="openSubscriptionModal()" class="inline-flex items-center gap-3 px-10 py-5 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full text-white text-lg font-bold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 transform hover:scale-105 shadow-2xl hover:shadow-orange-500/50">
                    <i class="fas fa-bell text-2xl"></i>
                    <span>S'abonner Maintenant</span>
                </button>
                <a href="{{ route('preinscription.start') }}" class="inline-flex items-center gap-3 px-10 py-5 bg-white/5 backdrop-blur-sm border border-white/20 rounded-full text-white text-lg font-semibold hover:bg-white/10 transition-all duration-300">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Rejoindre la Formation</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'abonnement -->
<div id="subscriptionModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm transition-opacity duration-300 opacity-0">
    <div class="relative w-full max-w-md mx-4 transform transition-all duration-300 scale-95">
        <div class="relative bg-gradient-to-br from-[#0a1128] to-[#001f54] rounded-3xl p-8 border border-orange-500/30 shadow-2xl">
            <!-- Close button -->
            <button onclick="closeSubscriptionModal()" class="absolute top-4 right-4 text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-times text-2xl"></i>
            </button>

            <!-- Icon -->
            <div class="flex justify-center mb-6">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center">
                    <i class="fas fa-bell text-white text-3xl"></i>
                </div>
            </div>

            <!-- Title -->
            <h3 class="text-2xl font-bold text-white text-center mb-2">
                Abonnez-vous à la WEBTV
            </h3>
            <p class="text-gray-300 text-center mb-6">
                Recevez une notification à chaque nouvelle vidéo
            </p>

            <!-- Form -->
            <form id="subscriptionForm" onsubmit="handleSubscription(event)">
                <div class="space-y-4">
                    <div>
                        <label for="subscriber_name" class="block text-sm font-medium text-gray-300 mb-2">
                            Nom (optionnel)
                        </label>
                        <input type="text" id="subscriber_name" name="name"
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all"
                            placeholder="Votre nom">
                    </div>

                    <div>
                        <label for="subscriber_email" class="block text-sm font-medium text-gray-300 mb-2">
                            Email <span class="text-orange-500">*</span>
                        </label>
                        <input type="email" id="subscriber_email" name="email" required
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all"
                            placeholder="votre@email.com">
                    </div>

                    <!-- Error message -->
                    <div id="subscriptionError" class="hidden p-4 bg-red-500/10 border border-red-500/30 rounded-xl">
                        <p class="text-red-400 text-sm"></p>
                    </div>

                    <!-- Success message -->
                    <div id="subscriptionSuccess" class="hidden p-4 bg-green-500/10 border border-green-500/30 rounded-xl">
                        <p class="text-green-400 text-sm"></p>
                    </div>

                    <button type="submit" id="subscribeBtn"
                        class="w-full py-4 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full text-white font-bold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 transform hover:scale-105">
                        <span id="subscribeBtnText">S'abonner</span>
                        <span id="subscribeBtnLoading" class="hidden">
                            <i class="fas fa-spinner fa-spin"></i> Envoi en cours...
                        </span>
                    </button>
                </div>
            </form>

            <p class="text-xs text-gray-400 text-center mt-4">
                En vous abonnant, vous acceptez de recevoir des notifications par email.
            </p>
        </div>
    </div>
</div>

<script src="https://player.vimeo.com/api/player.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var wrapper = document.getElementById('webtv-featured-wrap');
        var iframe = wrapper ? wrapper.querySelector('iframe') : null;

        if (iframe && wrapper) {
            console.log('WebTV: Player initialisé (Mode SPA)');
            var player = new Vimeo.Player(iframe);
            var isRedirecting = false;
            var nextVideoUrl = wrapper.dataset.nextVideo;

            // Gestionnaires d'événements
            function handleTimeUpdate(data) {
                // Si URL suivante existe ET qu'on est proche de la fin (3s)
                if (nextVideoUrl && (data.duration > 0) && (data.duration - data.seconds < 3)) {
                    triggerRedirect();
                }
            }

            function handleEnded() {
                console.log('Vidéo terminée (event ended)');
                triggerRedirect();
            }

            // Fonction de redirection fluide (AJAX)
            function triggerRedirect() {
                if (!isRedirecting && nextVideoUrl) {
                    isRedirecting = true;
                    console.log('>>> CHARGEMENT AJAX DE :', nextVideoUrl);

                    fetch(nextVideoUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Données reçues:', data);

                        // 1. Mettre à jour l'URL navigateur
                        if (history.pushState) {
                            var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?video=' + data.id;
                            window.history.pushState({path: newUrl}, data.title, newUrl);
                        }

                        // 2. Mettre à jour le DOM
                        document.getElementById('webtv-title').textContent = data.title;
                        document.getElementById('webtv-description').textContent = data.description || 'Découvrez ce contenu exclusif...';
                        document.getElementById('webtv-view-count').textContent = data.view_count + '+';

                        // Icone Loop
                        var loopIconContainer = document.getElementById('webtv-loop-icon');
                        if (data.loop_enabled) {
                            loopIconContainer.innerHTML = '<i class="fas fa-infinity"></i>';
                        } else {
                            loopIconContainer.innerHTML = '24/7';
                        }

                        // Badge (Live vs Boucle)
                        var badgeContainer = document.getElementById('webtv-badge-container');
                        if (data.type === 'live') {
                            badgeContainer.innerHTML = `
                            <div class="inline-flex items-center gap-3 px-5 py-2.5 bg-gradient-to-r from-green-500/30 to-emerald-500/30 border-2 border-green-400 rounded-full mb-4 shadow-lg shadow-green-500/50" style="animation: glow-pulse 2s ease-in-out infinite;">
                                <span class="relative flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-400"></span>
                                </span>
                                <span class="text-green-300 font-extrabold text-sm uppercase tracking-wider">EN DIRECT</span>
                            </div>`;
                        } else {
                            badgeContainer.innerHTML = `
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500/20 border border-orange-500/40 rounded-full mb-4">
                                <i class="fas fa-infinity text-orange-500"></i>
                                <span class="text-orange-400 font-bold text-xs uppercase">En Boucle</span>
                            </div>`;
                        }

                        // 3. Remplacer l'iframe
                        var embedContainer = wrapper.querySelector('.aspect-video');
                        embedContainer.innerHTML = data.embed_code;

                        // 4. Mettre à jour les variables d'état
                        wrapper.dataset.nextVideo = data.next_video_url || '';
                        wrapper.dataset.currentTitle = data.title;
                        nextVideoUrl = data.next_video_url;

                        // 5. Réinitialiser le player Vimeo
                        var newIframe = embedContainer.querySelector('iframe');
                        player = new Vimeo.Player(newIframe);

                        // Réattacher les écouteurs
                        player.on('timeupdate', handleTimeUpdate);
                        player.on('ended', handleEnded);

                        // Reset flag pour permettre la prochaine transition
                        isRedirecting = false;

                        console.log('Transition terminée vers:', data.title);
                    })
                    .catch(error => {
                        console.error('Erreur chargement AJAX:', error);
                        // Fallback : rechargement classique
                        window.location.href = nextVideoUrl;
                    });
                }
            }

            // Attacher les écouteurs initiaux
            player.on('timeupdate', handleTimeUpdate);
            player.on('ended', handleEnded);

        } else {
            console.warn('WebTV: Impossible de trouver l\'iframe ou le wrapper');
        }
    });
</script>

<script>
function openSubscriptionModal() {
    const modal = document.getElementById('subscriptionModal');
    const modalContent = modal.querySelector('.transform');

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';

    // Trigger animation
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modal.classList.add('opacity-100');
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }, 10);
}

function closeSubscriptionModal() {
    const modal = document.getElementById('subscriptionModal');
    const modalContent = modal.querySelector('.transform');

    // Trigger closing animation
    modal.classList.remove('opacity-100');
    modal.classList.add('opacity-0');
    modalContent.classList.remove('scale-100');
    modalContent.classList.add('scale-95');

    // Wait for animation to complete before hiding
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';

        // Reset form
        document.getElementById('subscriptionForm').reset();
        document.getElementById('subscriptionError').classList.add('hidden');
        document.getElementById('subscriptionSuccess').classList.add('hidden');
    }, 300);
}

async function handleSubscription(event) {
    event.preventDefault();

    const form = event.target;
    const btn = document.getElementById('subscribeBtn');
    const btnText = document.getElementById('subscribeBtnText');
    const btnLoading = document.getElementById('subscribeBtnLoading');
    const errorDiv = document.getElementById('subscriptionError');
    const successDiv = document.getElementById('subscriptionSuccess');

    // Hide messages
    errorDiv.classList.add('hidden');
    successDiv.classList.add('hidden');

    // Show loading
    btn.disabled = true;
    btnText.classList.add('hidden');
    btnLoading.classList.remove('hidden');

    try {
        const formData = new FormData(form);
        const response = await fetch('{{ route("webtv.subscribe") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            successDiv.querySelector('p').textContent = data.message;
            successDiv.classList.remove('hidden');
            form.reset();

            // Close modal after 3 seconds
            setTimeout(() => {
                closeSubscriptionModal();
            }, 3000);
        } else {
            const errorMessage = data.errors ? Object.values(data.errors).flat().join(', ') : data.message;
            errorDiv.querySelector('p').textContent = errorMessage;
            errorDiv.classList.remove('hidden');
        }
    } catch (error) {
        errorDiv.querySelector('p').textContent = 'Une erreur est survenue. Veuillez réessayer.';
        errorDiv.classList.remove('hidden');
    } finally {
        btn.disabled = false;
        btnText.classList.remove('hidden');
        btnLoading.classList.add('hidden');
    }
}

// Close modal on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeSubscriptionModal();
    }
});

// Close modal on outside click
document.getElementById('subscriptionModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeSubscriptionModal();
    }
});
</script>

@endsection
