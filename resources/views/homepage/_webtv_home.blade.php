<section id="webtv-home" class="bg-gray-900/60 border-y border-gray-800 py-12">
    <div class="mx-auto max-w-6xl px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            <div class="w-full lg:w-2/3">
                <div class="aspect-video rounded-2xl overflow-hidden ring-1 ring-white/10 shadow-lg bg-black">
                    <!-- Replace with real embed or featured video -->
                    <iframe class="w-full h-full" src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="EVC WebTV" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
            </div>
            <div class="w-full lg:w-1/3">
                <h2 class="text-2xl font-bold text-white">WebTV</h2>
                <p class="mt-3 text-gray-300">Conférences, tutoriels, interviews et coulisses de l'EVC. Découvre nos dernières vidéos et reste inspiré.</p>
                <a href="{{ route('webtv') }}" class="mt-6 inline-flex items-center gap-2 btn btn-secondary">
                    Voir toutes les vidéos <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </div>
</section>
