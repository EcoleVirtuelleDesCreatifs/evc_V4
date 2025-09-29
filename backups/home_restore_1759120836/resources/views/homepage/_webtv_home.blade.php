<section id="webtv-home" class="relative py-10 lg:py-14 bg-gradient-to-b from-[#0a0a1a] to-[#0f1733]">
  <div class="max-w-7xl mx-auto px-4 lg:px-8">
    <div class="flex flex-col lg:flex-row items-start lg:items-center gap-6">
      <div class="flex-1">
        <h2 class="text-2xl lg:text-3xl font-bold text-white mb-2">EVC WebTV</h2>
        <p class="text-gray-300">Découvre nos émissions, replays, interviews et contenus pédagogiques en vidéo.</p>
      </div>
      <div class="shrink-0">
        <a href="{{ route('webtv') }}" class="relative inline-flex items-center gap-3 rounded-full p-[2px] bg-gradient-to-r from-orange-500 via-amber-400 to-red-500 hover:from-orange-400 hover:via-amber-300 hover:to-red-400 transition shadow-md hover:shadow-lg hover:shadow-orange-400/30" aria-label="Accéder à la WebTV">
          <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-black/60 text-white font-semibold">
            <span class="relative flex h-4 w-4" aria-hidden="true">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-90"></span>
              <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500"></span>
            </span>
            <i class="fas fa-tv" aria-hidden="true"></i>
            Accéder à la WebTV
          </span>
          <span class="absolute -top-1 -right-1 h-3 w-3 rounded-full bg-red-500 animate-pulse" aria-hidden="true"></span>
        </a>
      </div>
    </div>

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-4">
      <a href="{{ route('webtv') }}" class="group rounded-xl overflow-hidden ring-1 ring-white/10 hover:ring-evc-orange/40 transition">
        <div class="aspect-video bg-black/40 flex items-center justify-center text-white/70">
          <i class="fas fa-tv text-3xl"></i>
        </div>
        <div class="p-4 bg-black/30">
          <h3 class="text-white font-semibold group-hover:text-evc-orange transition">Découvrir les programmes</h3>
          <p class="text-gray-400 text-sm">Explore nos playlists et contenus récents.</p>
        </div>
      </a>
      <a href="{{ route('webtv') }}" class="group rounded-xl overflow-hidden ring-1 ring-white/10 hover:ring-evc-orange/40 transition">
        <div class="aspect-video bg-black/40 flex items-center justify-center text-white/70">
          <i class="fas fa-video text-3xl"></i>
        </div>
        <div class="p-4 bg-black/30">
          <h3 class="text-white font-semibold group-hover:text-evc-orange transition">Replays et interviews</h3>
          <p class="text-gray-400 text-sm">Les meilleurs moments à revoir à tout moment.</p>
        </div>
      </a>
      <a href="{{ route('webtv') }}" class="group rounded-xl overflow-hidden ring-1 ring-white/10 hover:ring-evc-orange/40 transition">
        <div class="aspect-video bg-black/40 flex items-center justify-center text-white/70">
          <i class="fas fa-film text-3xl"></i>
        </div>
        <div class="p-4 bg-black/30">
          <h3 class="text-white font-semibold group-hover:text-evc-orange transition">Contenus pédagogiques</h3>
          <p class="text-gray-400 text-sm">Apprends en vidéo avec nos experts.</p>
        </div>
      </a>
    </div>
  </div>
</section>
