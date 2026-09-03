<!-- Flash Info Section - Fixed Professional Design -->
<section class="flash-section w-full max-w-7xl mx-auto my-8 mb-20 px-4" style="font-family: system-ui, -apple-system, sans-serif;">
    @php
        $communiques = \App\Models\Communique::active()
            ->with(['actualite:id,slug', 'evenement:id,slug'])
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get();

        $items = [];
        foreach ($communiques as $c) {
            $url = null;
            if (!empty($c->actualite) && !empty($c->actualite->slug)) {
                $url = route('actualite.show', $c->actualite->slug);
            } elseif (!empty($c->evenement) && !empty($c->evenement->slug)) {
                $url = route('evenement.show', $c->evenement->slug);
            }
            $items[] = [
                'content' => $c->content,
                'url' => $url,
                'date' => $c->created_at ? $c->created_at->format('d/m/Y') : null
            ];
        }

        if (empty($items)) {
            $items[] = [
                'content' => 'Bienvenue sur votre espace etudiant !',
                'url' => null,
                'date' => null
            ];
        }
    @endphp

    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#0d1b2a] via-[#162536] to-[#0d1b2a] shadow-2xl border border-white/10">
        <!-- Top accent -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#ff6b00] via-[#ff9d4d] to-[#ff6b00]"></div>

        <!-- Header -->
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4 px-6 py-5 md:px-8 md:py-6 border-b border-white/10 bg-white/5 backdrop-blur-sm">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-14 h-14 rounded-xl bg-gradient-to-br from-[#ff6b00] to-[#ff9d4d] shadow-lg shadow-orange-500/25">
                    <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                    </svg>
                </div>
                <div>
                    <h2 class="text-white text-2xl md:text-3xl font-black uppercase tracking-[0.15em]">FLASH INFO</h2>
                    <p class="text-slate-400 text-sm font-medium mt-0.5">Restez informé des actualités de l'école</p>
                </div>
            </div>
            @if(count($items) > 1)
                <span class="self-start md:self-auto inline-flex items-center px-3 py-1 rounded-full bg-white/10 text-white/80 text-xs font-bold border border-white/10">
                    {{ count($items) }} annonces
                </span>
            @endif
        </div>

        <!-- Content -->
        <div class="relative z-10 p-6 md:p-8 bg-gradient-to-b from-white/[0.03] to-transparent">
            <div id="flash-slides" class="min-h-[160px]">
                @foreach($items as $index => $item)
                    <div class="flash-slide" data-slide="{{ $index }}" style="display: {{ $index === 0 ? 'block' : 'none' }};">
                        <div class="flex flex-col h-full justify-between gap-6">
                            <div>
                                @if($item['date'])
                                    <div class="flex items-center flex-wrap gap-3 mb-4">
                                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#ff6b00]/15 text-[#ff9d4d] text-sm font-bold border border-[#ff6b00]/25">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                            {{ $item['date'] }}
                                        </span>
                                        @if($index === 0)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#ff6b00] text-white text-xs font-black uppercase tracking-wider shadow-sm">
                                                Nouveau
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <p class="text-white/90 text-lg md:text-xl leading-relaxed font-medium">
                                    {!! $item['content'] !!}
                                </p>
                            </div>

                            <div class="flex items-center justify-between pt-2">
                                @if($item['url'])
                                    <a href="{{ $item['url'] }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-[#ff6b00] hover:bg-[#ff8533] text-white text-sm font-bold transition-all duration-200 shadow-lg shadow-orange-600/25">
                                        Lire l'annonce
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="9 18 15 12 9 6"></polyline>
                                        </svg>
                                    </a>
                                @else
                                    <span></span>
                                @endif
                                @if(count($items) > 1)
                                    <span class="text-white/40 font-mono text-sm font-bold">
                                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }} / {{ str_pad(count($items), 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if(count($items) > 1)
                <div class="flex items-center justify-between mt-8 pt-6 border-t border-white/10">
                    <div class="flex items-center gap-2">
                        @foreach($items as $index => $item)
                            <button onclick="goToSlide({{ $index }})" class="flash-dot h-2 rounded-full transition-all duration-300" data-dot="{{ $index }}" style="width: {{ $index === 0 ? '24px' : '6px' }}; background: {{ $index === 0 ? '#f97316' : 'rgba(255,255,255,0.25)' }};"></button>
                        @endforeach
                    </div>
                    <div class="flex items-center gap-3">
                        <button onclick="prevSlide()" class="w-10 h-10 rounded-full border border-white/20 bg-white/5 hover:bg-white/10 hover:border-[#ff6b00] transition flex items-center justify-center group">
                            <svg class="w-4 h-4 text-white/70 group-hover:text-[#ff9d4d] transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </button>
                        <button onclick="nextSlide()" class="w-10 h-10 rounded-full bg-[#ff6b00] hover:bg-[#ff8533] transition flex items-center justify-center shadow-lg shadow-orange-600/30">
                            <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<script>
(function() {
    const slides = document.querySelectorAll('.flash-slide');
    const dots = document.querySelectorAll('.flash-dot');
    let current = 0;

    if (slides.length <= 1) return;

    function show(index) {
        slides.forEach((s, i) => s.style.display = i === index ? 'block' : 'none');
        dots.forEach((d, i) => {
            d.style.width = i === index ? '24px' : '6px';
            d.style.background = i === index ? '#f97316' : 'rgba(255,255,255,0.25)';
        });
        current = index;
    }

    window.nextSlide = function() { show((current + 1) % slides.length); reset(); };
    window.prevSlide = function() { show((current - 1 + slides.length) % slides.length); reset(); };
    window.goToSlide = function(index) { show(index); reset(); };

    let timer = setInterval(() => show((current + 1) % slides.length), 6000);
    function reset() { clearInterval(timer); timer = setInterval(() => show((current + 1) % slides.length), 6000); }
})();
</script>
