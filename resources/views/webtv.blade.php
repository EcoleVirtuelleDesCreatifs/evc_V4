@extends('layouts.app')

@section('title', 'WebTV EVC | Tutoriels Design Graphique & Marketing Digital | Vidéos Formation')
@section('description', 'Accédez gratuitement à notre WebTV : tutoriels design graphique, masterclass marketing digital, conférences tech et interviews d\'experts. Apprenez le design et le digital en vidéo avec l\'EVC Abidjan.')
@section('keywords', 'WebTV EVC, tutoriels design graphique, vidéos formation, masterclass digital, conférences tech, tutoriels Photoshop, formation vidéo gratuite, cours en ligne Abidjan, chaîne YouTube EVC')

@section('content')

<!-- Hero Section -->
<div class="relative pt-32 sm:pt-40 lg:pt-48 pb-20 bg-gradient-to-br from-[#0a1128] via-[#001f54] to-[#034078] overflow-hidden">
    <!-- Animated background elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-orange-500/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-orange-500/5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
        <div class="text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500/10 border border-orange-500/30 rounded-full mb-6" data-aos="fade-down">
                <i class="fas fa-play-circle text-orange-500"></i>
                <span class="text-orange-400 font-semibold text-sm">WebTV EVC</span>
            </div>

            <!-- Titre principal -->
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-6 leading-tight" data-aos="fade-up">
                Apprenez Gratuitement<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">Avec Nos Experts</span>
            </h1>

            <p class="text-xl md:text-2xl text-gray-300 mb-8 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                Tutoriels, masterclass et conférences pour booster vos compétences en design graphique, community management et intelligence artificielle
            </p>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto mt-12" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                    <div class="text-3xl md:text-4xl font-bold text-orange-500 mb-2">50+</div>
                    <div class="text-sm text-gray-400">Vidéos Gratuites</div>
                </div>
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                    <div class="text-3xl md:text-4xl font-bold text-orange-500 mb-2">10K+</div>
                    <div class="text-sm text-gray-400">Vues</div>
                </div>
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                    <div class="text-3xl md:text-4xl font-bold text-orange-500 mb-2">100%</div>
                    <div class="text-sm text-gray-400">Gratuit</div>
                </div>
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                    <div class="text-3xl md:text-4xl font-bold text-orange-500 mb-2">24/7</div>
                    <div class="text-sm text-gray-400">Accessible</div>
                </div>
            </div>

            <!-- CTA -->
            <div class="mt-12" data-aos="fade-up" data-aos-delay="300">
                <a href="https://www.youtube.com/@ecolevirtuelledescreatifs459?sub_confirmation=1" target="_blank" class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full text-white text-lg font-bold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 transform hover:scale-105 shadow-2xl hover:shadow-orange-500/50">
                    <i class="fab fa-youtube text-2xl"></i>
                    <span>S'abonner à la Chaîne</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <i class="fas fa-chevron-down text-orange-500 text-2xl"></i>
    </div>
</div>

<!-- Section Catégories -->
<div class="bg-gradient-to-b from-[#034078] to-[#001233] py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Explorez Nos Contenus Par Thématique
            </h2>
            <p class="text-lg text-gray-400">
                Des tutoriels adaptés à tous les niveaux, du débutant à l'expert
            </p>
        </div>

        <!-- Grille de catégories -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
            @php
                $categories = [
                    ['icon' => 'fa-palette', 'name' => 'Design Graphique', 'count' => '15+ vidéos', 'color' => 'from-orange-500 to-orange-600'],
                    ['icon' => 'fa-bullhorn', 'name' => 'Community Management', 'count' => '12+ vidéos', 'color' => 'from-blue-500 to-blue-600'],
                    ['icon' => 'fa-robot', 'name' => 'Intelligence Artificielle', 'count' => '10+ vidéos', 'color' => 'from-orange-400 to-orange-500'],
                    ['icon' => 'fa-laptop-code', 'name' => 'Gestion Informatique', 'count' => '8+ vidéos', 'color' => 'from-blue-400 to-blue-500'],
                ];
            @endphp

            @foreach($categories as $index => $category)
            <div class="group" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="relative bg-gradient-to-br from-white/5 to-white/10 backdrop-blur-lg rounded-3xl p-6 border border-white/10 h-full transition-all duration-500 hover:border-orange-500/50 hover:transform hover:scale-105 hover:shadow-2xl hover:shadow-orange-500/20">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $category['color'] }} flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas {{ $category['icon'] }} text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2 group-hover:text-orange-400 transition-colors">{{ $category['name'] }}</h3>
                    <p class="text-sm text-gray-400">{{ $category['count'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Section Vidéos -->
<div class="bg-gradient-to-b from-[#001233] to-[#0a1128] py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Nos Dernières Vidéos
            </h2>
            <p class="text-lg text-gray-400">
                Découvrez nos tutoriels et masterclass les plus récents
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
            @foreach ($videos as $video)
                <article class="group" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="relative bg-gradient-to-br from-white/5 to-white/10 backdrop-blur-lg rounded-3xl overflow-hidden border border-white/10 transition-all duration-500 hover:border-orange-500/50 hover:transform hover:scale-[1.02] hover:shadow-2xl hover:shadow-orange-500/20">
                        <!-- Vidéo -->
                        <div class="relative aspect-video">
                            <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $video['id'] }}" title="{{ $video['title'] }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>

                        <!-- Contenu -->
                        <div class="p-6">
                            <!-- Badge catégorie -->
                            <div class="inline-flex items-center gap-2 px-3 py-1 bg-orange-500/10 border border-orange-500/30 rounded-full mb-4">
                                <i class="fas fa-play text-orange-500 text-xs"></i>
                                <span class="text-orange-400 font-semibold text-xs">Tutoriel</span>
                            </div>

                            <h3 class="text-xl font-bold text-white mb-3 group-hover:text-orange-400 transition-colors line-clamp-2">
                                {{ $video['title'] }}
                            </h3>

                            <p class="text-gray-400 text-sm mb-4 line-clamp-2">
                                {{ $video['description'] }}
                            </p>

                            <!-- Footer -->
                            <div class="flex items-center justify-between pt-4 border-t border-white/10">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-white">{{ $video['speaker'] }}</p>
                                        <p class="text-xs text-gray-400">Formateur EVC</p>
                                    </div>
                                </div>
                                <a href="https://www.youtube.com/watch?v={{ $video['id'] }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-orange-500/20 border border-white/10 hover:border-orange-500/50 rounded-full text-white text-sm font-semibold transition-all duration-300">
                                    <span>Regarder</span>
                                    <i class="fas fa-external-link-alt text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
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
