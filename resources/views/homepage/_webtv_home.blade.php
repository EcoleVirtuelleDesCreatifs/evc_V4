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
  </style>
  <!-- Decorative background: radial + grid -->
  <div class="pointer-events-none absolute inset-0 -z-10">
    <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-evc-orange/10 blur-3xl" style="animation: float-slow 12s ease-in-out infinite;"></div>
    <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-blue-400/10 blur-3xl" style="animation: float-slow 14s ease-in-out infinite; animation-delay: -3s;"></div>
    <div class="absolute inset-0 bg-[radial-gradient(transparent_1px,rgba(255,255,255,0.04)_1px)] [background-size:24px_24px]"></div>
  </div>

  <div class="mx-auto max-w-6xl px-6 lg:px-8">
    <div class="flex items-end justify-between">
      <h2 class="text-3xl font-extrabold tracking-tight bg-gradient-to-r from-orange-400 via-amber-300 to-orange-400 bg-clip-text text-transparent flex items-center gap-3"
          style="background-size: 200% 200%; animation: gradient-shift 6s ease infinite;">
        WebTV
        <span class="ml-1 inline-flex items-center gap-2 rounded-full px-3 py-1 text-[11px] font-bold text-white ring-1 ring-red-500/30"
              style="background: linear-gradient(90deg,#7f1d1d,#dc2626); animation: glow-pulse 2.2s ease-in-out infinite;">
          <span class="relative flex h-2.5 w-2.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
          </span>
          <span class="en-direct-text" style="animation: blink 1.6s steps(2,end) infinite;">En Direct</span>
        </span>
      </h2>

    </div>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Featured video -->
      <div class="lg:col-span-2 group relative rounded-3xl p-1 bg-gradient-to-br from-orange-500/30 via-amber-300/20 to-transparent transition-transform duration-500 will-change-transform hover:-translate-y-0.5 hover:rotate-[0.2deg]">
        <div class="rounded-2xl bg-black/60 backdrop-blur ring-1 ring-white/10 shadow-2xl overflow-hidden">
          <div class="relative aspect-video">
            <!-- Remplacez VIMEO_VIDEO_ID par l'ID de la vidéo mise en avant -->
            <iframe class="absolute inset-0 w-full h-full" src="https://player.vimeo.com/video/VIMEO_VIDEO_ID?title=0&byline=0&portrait=0" title="EVC WebTV" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
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

      <!-- Playlist / next videos -->
      <div class="space-y-4">
        @php
          $videos = [
            ['title' => "Design Graphique: astuces pro", 'duration' => '12:34', 'url' => 'https://vimeo.com/user186497486'],
            ['title' => "Devenir Community Manager", 'duration' => '08:51', 'url' => 'https://vimeo.com/user186497486'],
            ['title' => "Parcours Lauréat: témoignage", 'duration' => '10:02', 'url' => 'https://vimeo.com/user186497486'],
          ];
        @endphp
        @foreach($videos as $v)
          <a href="{{ $v['url'] }}" target="_blank" rel="noopener" class="group block rounded-2xl p-1 bg-gradient-to-tr from-white/10 to-transparent hover:from-orange-400/20 transition">
            <div class="flex items-center gap-4 rounded-xl bg-gray-900/60 ring-1 ring-white/10 p-3 transition-transform duration-300 group-hover:-translate-y-0.5">
              <div class="relative w-28 aspect-video overflow-hidden rounded-lg bg-black/50 transition-transform duration-300 group-hover:scale-[1.03]">
                <div class="absolute inset-0 grid place-items-center text-white/80">
                  <i class="fas fa-play text-lg"></i>
                </div>
              </div>
              <div class="min-w-0">
                <p class="text-white font-medium truncate">{{ $v['title'] }}</p>
                <p class="text-xs text-white/60">{{ $v['duration'] }}</p>
              </div>
              <div class="ml-auto opacity-0 translate-x-1 group-hover:translate-x-0 group-hover:opacity-100 transition">
                <i class="fas fa-arrow-right text-white/70"></i>
              </div>
            </div>
          </a>
        @endforeach

        <a href="https://vimeo.com/user186497486" target="_blank" rel="noopener" class="relative overflow-hidden mt-4 inline-flex items-center justify-center w-full rounded-2xl px-4 py-3 text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-amber-400 hover:from-orange-400 hover:to-amber-300 shadow transition">
          <span class="relative z-10">Voir toutes les vidéos</span>
          <span class="pointer-events-none absolute inset-0 bg-white/20 translate-x-[-120%] skew-x-[-20deg]" style="transition: transform .6s ease;"></span>
        </a>
        <script>
          document.addEventListener('DOMContentLoaded', function(){
            document.querySelectorAll('#webtv-home a[href][class*="rounded-2xl"]').forEach(btn => {
              btn.addEventListener('mouseenter', () => {
                const sheen = btn.querySelector('span.pointer-events-none');
                if (sheen) sheen.style.transform = 'translateX(120%)';
              });
              btn.addEventListener('mouseleave', () => {
                const sheen = btn.querySelector('span.pointer-events-none');
                if (sheen) sheen.style.transform = 'translateX(-120%)';
              });
            });
          });
        </script>
      </div>
    </div>
  </div>
</section>
