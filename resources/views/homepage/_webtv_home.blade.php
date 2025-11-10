<section id="webtv-home" class="relative py-12 md:py-20 overflow-hidden bg-gradient-to-b from-[#001233] to-[#0a1128]">
  <style>
    @keyframes gradient-shift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    @keyframes glow-pulse {
      0%, 100% { box-shadow: 0 0 20px rgba(249,115,22,.3); }
      50% { box-shadow: 0 0 40px rgba(249,115,22,.5); }
    }
    @keyframes blink {
      0%, 49% { opacity: 1; }
      50%, 100% { opacity: .6; }
    }
    @keyframes float-slow {
      0% { transform: translate3d(0,0,0); }
      50% { transform: translate3d(10px,-10px,0); }
      100% { transform: translate3d(0,0,0); }
    }
  </style>
  
  <!-- Decorative background -->
  <div class="pointer-events-none absolute inset-0 -z-10">
    <div class="absolute -top-40 -left-40 h-80 w-80 rounded-full bg-orange-500/10 blur-3xl" style="animation: float-slow 15s ease-in-out infinite;"></div>
    <div class="absolute -bottom-40 -right-40 h-80 w-80 rounded-full bg-blue-500/10 blur-3xl" style="animation: float-slow 18s ease-in-out infinite; animation-delay: -5s;"></div>
  </div>

  <div class="mx-auto max-w-7xl px-4 md:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="flex flex-col items-center text-center mb-8 md:mb-12" data-aos="fade-up">
      <!-- Badge -->
      <div class="inline-flex items-center gap-2 px-3 py-1.5 md:px-4 md:py-2 bg-orange-500/10 border border-orange-500/30 rounded-full mb-4 md:mb-6">
        <span class="relative flex h-2.5 w-2.5 md:h-3 md:w-3">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2.5 w-2.5 md:h-3 md:w-3 bg-red-500"></span>
        </span>
        <span class="text-orange-400 font-semibold text-xs md:text-sm" style="animation: blink 1.6s steps(2,end) infinite;">En Direct</span>
      </div>

      <!-- Titre -->
      <h2 class="relative flex items-center justify-center gap-2 md:gap-3 mb-4 md:mb-6">
        <span class="text-5xl sm:text-6xl md:text-6xl lg:text-7xl font-extrabold tracking-tight bg-gradient-to-r from-orange-400 via-orange-500 to-orange-400 bg-clip-text text-transparent"
              style="background-size: 200% 200%; animation: gradient-shift 6s ease infinite;">WEB</span>
        <span class="inline-flex items-center justify-center px-5 py-2.5 md:px-6 md:py-3 text-4xl sm:text-5xl md:text-4xl lg:text-5xl font-extrabold text-white rounded-full bg-gradient-to-r from-orange-500 to-orange-600 shadow-2xl"
              style="animation: glow-pulse 2.5s ease-in-out infinite;">
          TV
        </span>
      </h2>

      <p class="text-base md:text-lg lg:text-xl text-gray-300 max-w-3xl mb-6 md:mb-8 px-4">
        Découvrez nos tutoriels exclusifs, masterclass et interviews d'experts pour booster vos compétences en design et digital
      </p>

      <!-- Stats -->
      <div class="grid grid-cols-3 gap-4 md:gap-6 max-w-2xl">
        <div class="text-center">
          <div class="text-2xl md:text-3xl font-bold text-orange-500 mb-1">50+</div>
          <div class="text-sm md:text-sm text-gray-400">Vidéos</div>
        </div>
        <div class="text-center">
          <div class="text-2xl md:text-3xl font-bold text-orange-500 mb-1">10K+</div>
          <div class="text-sm md:text-sm text-gray-400">Vues</div>
        </div>
        <div class="text-center">
          <div class="text-2xl md:text-3xl font-bold text-orange-500 mb-1">100%</div>
          <div class="text-sm md:text-sm text-gray-400">Gratuit</div>
        </div>
      </div>
    </div>

    <!-- Featured Video -->
    <div class="mt-8 md:mt-12" data-aos="fade-up">
      <div id="webtv-featured-wrap" class="group relative rounded-3xl overflow-hidden bg-gradient-to-br from-white/5 to-white/10 backdrop-blur-lg border border-white/10 transition-all duration-500 hover:border-orange-500/50 hover:shadow-2xl hover:shadow-orange-500/20">
        <div class="relative aspect-video">
          <iframe id="webtv-featured-player" class="absolute inset-0 w-full h-full rounded-3xl" src="https://player.vimeo.com/video/000000000?title=0&byline=0&portrait=0" title="EVC WebTV" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
          
          <!-- Overlay info -->
          <div class="absolute left-3 md:left-6 bottom-3 md:bottom-6 flex items-center gap-2 md:gap-4 bg-black/60 backdrop-blur-md rounded-xl md:rounded-2xl px-3 py-2 md:px-6 md:py-4 border border-white/10">
            <div class="relative">
              <span class="absolute inset-0 rounded-full bg-orange-500/40 blur-lg animate-ping"></span>
              <span class="relative inline-flex h-8 w-8 md:h-12 md:w-12 items-center justify-center rounded-full bg-gradient-to-br from-orange-500 to-orange-600 text-white shadow-lg">
                <i class="fas fa-play text-xs md:text-base"></i>
              </span>
            </div>
            <div>
              <p class="text-white font-bold text-base md:text-lg">Épisode à la Une</p>
              <p class="text-gray-300 text-sm md:text-sm">Les coulisses de l'EVC</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Autres Vidéos -->
    <div class="relative mt-8 md:mt-12" data-aos="fade-up" data-aos-delay="100">
      <div class="flex items-center justify-between mb-4 md:mb-6">
        <h3 class="text-xl md:text-2xl font-bold text-white">Autres Épisodes</h3>
        <div class="flex gap-2">
          <button id="webtv-rail-prev" class="flex h-8 w-8 md:h-10 md:w-10 items-center justify-center rounded-full bg-white/5 hover:bg-orange-500/20 border border-white/10 hover:border-orange-500/50 text-white transition-all duration-300" aria-label="Précédent">
            <i class="fas fa-chevron-left text-xs md:text-base"></i>
          </button>
          <button id="webtv-rail-next" class="flex h-8 w-8 md:h-10 md:w-10 items-center justify-center rounded-full bg-white/5 hover:bg-orange-500/20 border border-white/10 hover:border-orange-500/50 text-white transition-all duration-300" aria-label="Suivant">
            <i class="fas fa-chevron-right text-xs md:text-base"></i>
          </button>
        </div>
      </div>
      
      <div id="webtv-rail" class="flex gap-3 md:gap-6 overflow-x-auto overscroll-x-contain snap-x snap-mandatory scroll-smooth px-1 py-2 hide-scrollbar">
        <!-- Items rendus dynamiquement -->
      </div>
    </div>

    <!-- CTA Section -->
    <div class="mt-10 md:mt-16 text-center" data-aos="fade-up" data-aos-delay="200">
      <div class="bg-gradient-to-r from-orange-500/10 to-blue-500/10 backdrop-blur-lg rounded-2xl md:rounded-3xl p-6 md:p-12 border border-orange-500/30">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 md:px-4 md:py-2 bg-orange-500/10 border border-orange-500/30 rounded-full mb-4 md:mb-6">
          <i class="fas fa-bell text-orange-500 text-sm md:text-base"></i>
          <span class="text-orange-400 font-semibold text-xs md:text-sm">Ne Manquez Rien</span>
        </div>
        
        <h3 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-3 md:mb-4">
          Abonnez-vous à la WEBTV
        </h3>
        <p class="text-base md:text-lg text-gray-300 mb-6 md:mb-8 max-w-2xl mx-auto px-4">
          Recevez une notification à chaque nouvelle vidéo et accédez à des contenus exclusifs pour développer vos compétences
        </p>
        
        <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center items-center">
          <button onclick="openSubscriptionModal()" class="inline-flex items-center gap-2 md:gap-3 px-8 py-4 md:px-8 md:py-4 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full text-white text-base md:text-lg font-bold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 transform hover:scale-105 shadow-2xl hover:shadow-orange-500/50">
            <i class="fas fa-bell text-xl md:text-2xl"></i>
            <span>S'abonner Maintenant</span>
          </button>
          <a href="{{ route('webtv') }}" class="inline-flex items-center gap-2 md:gap-3 px-8 py-4 md:px-8 md:py-4 bg-white/5 backdrop-blur-sm border border-white/20 rounded-full text-white text-base md:text-lg font-semibold hover:bg-white/10 transition-all duration-300">
            <i class="fas fa-video"></i>
            <span>Voir Toutes les Vidéos</span>
          </a>
        </div>
      </div>
    </div>
  </div>

      <script>
          document.addEventListener('DOMContentLoaded', async function(){
            const featured = document.getElementById('webtv-featured-player');
            const rail = document.getElementById('webtv-rail');
            const btnPrev = document.getElementById('webtv-rail-prev');
            const btnNext = document.getElementById('webtv-rail-next');
            const userFeed = 'https://vimeo.com/api/v2/user186497486/videos.json';
            let loadedVideos = [];
            let currentIdx = 0;
            try {
              const res = await fetch(userFeed, {headers: {'Accept': 'application/json'}});
              const videos = Array.isArray(await res.json()) ? await res.json() : [];
              if (!videos.length) throw new Error('No videos');
              loadedVideos = videos;
              // Set featured to first video
              const first = videos[0];
              const firstId = (first && first.id) ? first.id : null;
              if (firstId) featured.src = `https://player.vimeo.com/video/${firstId}?title=0&byline=0&portrait=0`;

              // Render up to 10 items in horizontal rail
              const maxItems = Math.min(10, videos.length);
              const listFrag = document.createDocumentFragment();
              for (let i = 0; i < maxItems; i++) {
                const v = videos[i];
                if (i === 0) currentIdx = 0;
                const card = document.createElement('a');
                card.href = `https://vimeo.com/${v.id}`;
                card.target = '_blank';
                card.rel = 'noopener';
                card.className = 'group min-w-[280px] md:min-w-[320px] snap-start';
                card.innerHTML = `
                  <div class="relative bg-gradient-to-br from-white/5 to-white/10 backdrop-blur-lg rounded-3xl overflow-hidden border border-white/10 transition-all duration-500 hover:border-orange-500/50 hover:transform hover:scale-[1.02] hover:shadow-2xl hover:shadow-orange-500/20">
                    <div class="relative w-full aspect-video overflow-hidden bg-black">
                      <img src="${v.thumbnail_large || v.thumbnail_medium || v.thumbnail_small}" alt="${v.title}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                      <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                      <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="w-16 h-16 rounded-full bg-orange-500/90 flex items-center justify-center">
                          <i class="fas fa-play text-white text-xl"></i>
                        </div>
                      </div>
                    </div>
                    <div class="p-4">
                      <p class="text-white text-base font-semibold line-clamp-2 mb-2 group-hover:text-orange-400 transition-colors">${v.title}</p>
                      <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-400"><i class="fas fa-clock mr-1"></i>${v.duration ? new Date(v.duration * 1000).toISOString().substring(14,19) : ''}</span>
                        <span class="text-xs text-orange-500 font-semibold">Regarder <i class="fas fa-arrow-right ml-1"></i></span>
                      </div>
                    </div>
                  </div>`;
                // Clicking the card (thumbnail/title area) swaps featured inline without opening new tab (unless arrow or ctrl click)
                card.addEventListener('click', (e) => {
                  // If user holds meta/ctrl or clicks middle button, let default behavior open new tab
                  if (e.metaKey || e.ctrlKey || e.button === 1) return;
                  e.preventDefault();
                  if (featured) featured.src = `https://player.vimeo.com/video/${v.id}?title=0&byline=0&portrait=0`;
                  currentIdx = i;
                });
                listFrag.appendChild(card);
              }
              rail.appendChild(listFrag);
              if (btnPrev && btnNext) { btnPrev.classList.remove('hidden'); btnNext.classList.remove('hidden'); }
            } catch (err) {
              console.warn('Vimeo API fetch failed or returned no data:', err);
              // Fallback: leave CTA only; featured will stay placeholder
            }

            // Keyboard navigation (← / →)
            window.addEventListener('keydown', (ev) => {
              if (!loadedVideos.length) return;
              if (ev.key === 'ArrowRight') {
                currentIdx = (currentIdx + 1) % loadedVideos.length;
                const id = loadedVideos[currentIdx].id;
                featured.src = `https://player.vimeo.com/video/${id}?title=0&byline=0&portrait=0`;
              } else if (ev.key === 'ArrowLeft') {
                currentIdx = (currentIdx - 1 + loadedVideos.length) % loadedVideos.length;
                const id = loadedVideos[currentIdx].id;
                featured.src = `https://player.vimeo.com/video/${id}?title=0&byline=0&portrait=0`;
              }
            });

            // Swipe navigation on featured (mobile)
            const wrap = document.getElementById('webtv-featured-wrap');
            if (wrap && 'ontouchstart' in window) {
              let startX = 0, endX = 0;
              wrap.addEventListener('touchstart', (e) => { startX = e.changedTouches[0].screenX; });
              wrap.addEventListener('touchend', (e) => {
                endX = e.changedTouches[0].screenX;
                const dx = endX - startX;
                if (Math.abs(dx) > 40 && loadedVideos.length) {
                  if (dx < 0) {
                    // swipe left -> next
                    currentIdx = (currentIdx + 1) % loadedVideos.length;
                  } else {
                    // swipe right -> prev
                    currentIdx = (currentIdx - 1 + loadedVideos.length) % loadedVideos.length;
                  }
                  const id = loadedVideos[currentIdx].id;
                  featured.src = `https://player.vimeo.com/video/${id}?title=0&byline=0&portrait=0`;
                }
              });
            }

            // Rail navigation buttons
            const scrollByAmount = () => Math.max(rail.clientWidth * 0.9, 240);
            if (btnPrev && btnNext) {
              btnPrev.addEventListener('click', () => { rail.scrollBy({left: -scrollByAmount(), behavior: 'smooth'}); });
              btnNext.addEventListener('click', () => { rail.scrollBy({left: scrollByAmount(), behavior: 'smooth'}); });
            }
          });
      </script>
      </div>
    </div>
  </div>
</section>

<!-- Modal d'abonnement WebTV -->
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
                @csrf
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
