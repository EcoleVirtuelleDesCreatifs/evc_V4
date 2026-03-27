<!-- Flash Info Section - Fixed Professional Design -->
<section class="flash-section w-full max-w-7xl mx-auto my-6 px-4" style="font-family: system-ui, -apple-system, sans-serif;">
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

    <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 1px solid #e5e7eb; overflow: hidden;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 20px 24px; display: flex; align-items: center; gap: 16px;">
            <div style="width: 44px; height: 44px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                </svg>
            </div>
            <div>
                <h2 style="color: white; font-size: 18px; font-weight: 700; margin: 0;">FLASH INFO</h2>
                @if(count($items) > 1)
                    <span style="color: #94a3b8; font-size: 12px;">{{ count($items) }} actualites</span>
                @endif
            </div>
        </div>

        <!-- Content -->
        <div style="padding: 24px; background: #fafafa;">
            <div id="flash-slides">
                @foreach($items as $index => $item)
                    <div class="flash-slide" data-slide="{{ $index }}" style="display: {{ $index === 0 ? 'block' : 'none' }};">
                        @if($item['date'])
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                                <span style="background: #dbeafe; color: #1e40af; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                    {{ $item['date'] }}
                                </span>
                                @if($index === 0)
                                    <span style="background: #ef4444; color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">
                                        NOUVEAU
                                    </span>
                                @endif
                            </div>
                        @endif

                        <p style="color: #1f2937; font-size: 16px; line-height: 1.6; margin: 0 0 16px 0; font-weight: 500;">
                            {!! $item['content'] !!}
                        </p>

                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            @if($item['url'])
                                <a href="{{ $item['url'] }}" style="display: inline-flex; align-items: center; gap: 6px; background: #0f172a; color: white; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none;">
                                    Lire
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </a>
                            @endif
                            @if(count($items) > 1)
                                <span style="color: #9ca3af; font-size: 13px; font-weight: 600;">
                                    {{ $index + 1 }} / {{ count($items) }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if(count($items) > 1)
                <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 20px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
                    <div style="display: flex; gap: 6px;">
                        @foreach($items as $index => $item)
                            <button onclick="goToSlide({{ $index }})" class="flash-dot" data-dot="{{ $index }}" style="width: {{ $index === 0 ? '20px' : '6px' }}; height: 6px; border-radius: 3px; border: none; cursor: pointer; background: {{ $index === 0 ? '#0f172a' : '#d1d5db' }};"></button>
                        @endforeach
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <button onclick="prevSlide()" style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid #d1d5db; background: white; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </button>
                        <button onclick="nextSlide()" style="width: 32px; height: 32px; border-radius: 50%; border: none; background: #0f172a; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
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
            d.style.width = i === index ? '20px' : '6px';
            d.style.background = i === index ? '#0f172a' : '#d1d5db';
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