<section id="webtv-home" class="relative py-16 overflow-hidden">
  <style>
    @keyframes gradient-shift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    @keyframes glow-pulse {
      0%, 100% { box-shadow: 0 0 0px rgba(220,38,38,.4), 0 0 0px rgba(251,191,36,.25); }
      50% { box-shadow: 0 0 18px rgba(220,38,38,.35), 0 0 28px rgba(251,191,36,.2); }
    }
    @keyframes blink {
      0%, 49% { opacity: 1; }
      50%, 100% { opacity: .55; }
    }
    @keyframes float-slow {
      0% { transform: translate3d(0,0,0) scale(1); }
      50% { transform: translate3d(6px,-8px,0) scale(1.03); }
      100% { transform: translate3d(0,0,0) scale(1); }
    }
    @keyframes marquee-left {
      0% { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }
  </style>
  <!-- Decorative background: radial + grid -->
  <div class="pointer-events-none absolute inset-0 -z-10">
    <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-evc-orange/10 blur-3xl" style="animation: float-slow 12s ease-in-out infinite;"></div>
    <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-blue-400/10 blur-3xl" style="animation: float-slow 14s ease-in-out infinite; animation-delay: -3s;"></div>
    <div class="absolute inset-0 bg-[radial-gradient(transparent_1px,rgba(255,255,255,0.04)_1px)] [background-size:24px_24px]"></div>
  </div>

  <div class="mx-auto max-w-6xl px-6 lg:px-8">
    <div class="flex flex-col items-center text-center">
      <h2 class="relative flex items-center justify-center gap-5">
        <span class="text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight bg-gradient-to-r from-orange-400 via-amber-300 to-orange-400 bg-clip-text text-transparent"
              style="background-size: 200% 200%; animation: gradient-shift 6s ease infinite;">WEB</span>
        <span class="relative inline-flex">
          <span class="inline-flex items-center justify-center px-5 md:px-6 py-2 md:py-3 text-2xl md:text-3xl lg:text-4xl font-extrabold text-white rounded-full"
                style="
                  border: 2px solid transparent;
                  border-radius: 9999px;
                  background:
                    linear-gradient(#0000,#0000) padding-box,
                    linear-gradient(90deg,#f97316,#fbbf24,#f97316) border-box;
                  background-size: 200% 100%;
                  background-position: 0% 0%;
                "
                onmouseover="this.style.backgroundPosition='100% 0%';"
                onmouseout="this.style.backgroundPosition='0% 0%';">
            TV
          </span>
          <!-- En direct badge -->
          <span class="absolute -right-4 -top-2 inline-flex items-center gap-2 rounded-full px-3 py-1 text-[12px] font-bold text-white ring-1 ring-red-500/30"
                style="background: linear-gradient(90deg,#7f1d1d,#dc2626); animation: glow-pulse 2.2s ease-in-out infinite;">
            <span class="relative flex h-3 w-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
            </span>
            <span class="en-direct-text" style="animation: blink 1.6s steps(2,end) infinite;">En Direct</span>
          </span>
        </span>
        <!-- Underline sheen -->
        <span class="pointer-events-none absolute -bottom-3 h-0.5 w-[220px] md:w-[280px] bg-gradient-to-r from-transparent via-white/40 to-transparent rounded-full"></span>
      </h2>
      <a href="https://vimeo.com/user186497486" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-orange-300 hover:text-orange-200 transition">
        Voir toutes les vidéos <span aria-hidden="true">→</span>
      </a>
    </div>

    <div class="mt-8 grid grid-cols-1 gap-8">
      <!-- Featured video -->
      <div id="webtv-featured-wrap" class="group relative rounded-3xl p-1 bg-gradient-to-br from-orange-500/30 via-amber-300/20 to-transparent transition-transform duration-500 will-change-transform hover:-translate-y-0.5 hover:rotate-[0.2deg]">
        <div class="rounded-2xl bg-black/60 backdrop-blur ring-1 ring-white/10 shadow-2xl overflow-hidden">
          <div class="relative aspect-video">
            <iframe id="webtv-featured-player" class="absolute inset-0 w-full h-full" src="https://player.vimeo.com/video/000000000?title=0&byline=0&portrait=0" title="EVC WebTV" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/40 via-black/10 to-transparent"></div>
            <!-- Floating play pulse -->
            <div class="absolute left-4 bottom-4 flex items-center gap-3">
              <div class="relative">
                <span class="absolute inset-0 rounded-full bg-evc-orange/40 blur-xl animate-ping"></span>
                <span class="relative inline-flex h-10 w-10 items-center justify-center rounded-full bg-evc-orange text-black shadow-lg"><i class="fas fa-play"></i></span>
              </div>
              <div>
                <p class="text-white font-semibold leading-5">Épisode à la Une</p>
                <p class="text-white/70 text-xs">Les coulisses de l’EVC</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Card Rail / next videos -->
      <div class="relative">
        <button id="webtv-rail-prev" class="hidden md:flex absolute left-2 top-1/2 -translate-y-1/2 z-10 h-10 w-10 items-center justify-center rounded-full bg-black/40 ring-1 ring-white/10 text-white hover:bg-black/60 transition" aria-label="Précédent">
          <i class="fas fa-chevron-left"></i>
        </button>
        <div id="webtv-rail" class="flex gap-4 overflow-x-auto overscroll-x-contain snap-x snap-mandatory scroll-smooth px-1 py-2 hide-scrollbar">
          <!-- Items rendus dynamiquement -->
        </div>
        <button id="webtv-rail-next" class="hidden md:flex absolute right-2 top-1/2 -translate-y-1/2 z-10 h-10 w-10 items-center justify-center rounded-full bg-black/40 ring-1 ring-white/10 text-white hover:bg-black/60 transition" aria-label="Suivant">
          <i class="fas fa-chevron-right"></i>
        </button>
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
                card.className = 'group min-w-[260px] snap-start rounded-2xl p-1 bg-gradient-to-tr from-white/10 to-transparent hover:from-orange-400/20 transition';
                card.innerHTML = `
                  <div class="rounded-xl bg-gray-900/60 ring-1 ring-white/10 transition-transform duration-300 group-hover:-translate-y-0.5">
                    <div class="relative w-full aspect-video overflow-hidden rounded-t-xl bg-black/50 transition-transform duration-300 group-hover:scale-[1.01]">
                      <img src="${v.thumbnail_large || v.thumbnail_medium || v.thumbnail_small}" alt="${v.title}" class="absolute inset-0 w-full h-full object-cover" />
                    </div>
                    <div class="p-3">
                      <p class="text-white text-sm font-medium line-clamp-2">${v.title}</p>
                      <p class="text-xs text-white/60 mt-1">${v.duration ? new Date(v.duration * 1000).toISOString().substring(14,19) : ''}</p>
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
