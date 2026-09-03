<!-- Flash Info Section - Fixed Professional Design -->
<section class="flash-section" style="width: 100%; max-width: 1280px; margin: 32px auto 80px; padding: 0 16px; font-family: 'Inter', system-ui, -apple-system, sans-serif;">
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

    <div style="position: relative; overflow: hidden; border-radius: 16px; background: linear-gradient(135deg, #0d1b2a 0%, #162536 50%, #0d1b2a 100%); box-shadow: 0 20px 50px rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.1);">
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 6px; background: linear-gradient(90deg, #ff6b00, #ff9d4d, #ff6b00);"></div>

        <!-- Header -->
        <div style="position: relative; z-index: 1; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; padding: 20px 24px; border-bottom: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.05);">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 56px; height: 56px; border-radius: 12px; background: linear-gradient(135deg, #ff6b00, #ff9d4d); display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 8px 24px rgba(255,107,0,0.25);">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                    </svg>
                </div>
                <div>
                    <h2 style="color: white; margin: 0; font-size: 22px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em;">FLASH INFO</h2>
                    <p style="color: #94a3b8; margin: 4px 0 0; font-size: 13px; font-weight: 500;">Restez inform&eacute; des actualit&eacute;s de l'&eacute;cole</p>
                </div>
            </div>
            @if(count($items) > 1)
                <span style="display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 9999px; background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.85); border: 1px solid rgba(255,255,255,0.1); font-size: 12px; font-weight: 700;">
                    {{ count($items) }} annonces
                </span>
            @endif
        </div>

        <!-- Content -->
        <div style="position: relative; z-index: 1; padding: 24px; background: linear-gradient(180deg, rgba(255,255,255,0.03), transparent);">
            <div id="flash-slides" style="min-height: 160px;">
                @foreach($items as $index => $item)
                    <div class="flash-slide" data-slide="{{ $index }}" style="display: {{ $index === 0 ? 'block' : 'none' }};">
                        <div style="display: flex; flex-direction: column; height: 100%; justify-content: space-between; gap: 20px;">
                            <div>
                                @if($item['date'])
                                    <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 16px;">
                                        <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 9999px; background: rgba(255,107,0,0.15); color: #ff9d4d; border: 1px solid rgba(255,107,0,0.25); font-size: 13px; font-weight: 700;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                            {{ $item['date'] }}
                                        </span>
                                        @if($index === 0)
                                            <span style="display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 9999px; background: #ff6b00; color: white; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.05em; box-shadow: 0 4px 12px rgba(255,107,0,0.3);">
                                                Nouveau
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <p style="color: rgba(255,255,255,0.9); font-size: 17px; line-height: 1.7; margin: 0; font-weight: 500;">
                                    {!! $item['content'] !!}
                                </p>
                            </div>

                            <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 8px;">
                                @if($item['url'])
                                    <a href="{{ $item['url'] }}" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 8px; background: #ff6b00; color: white; font-size: 13px; font-weight: 700; text-decoration: none; box-shadow: 0 8px 24px rgba(255,107,0,0.25);">
                                        Lire l'annonce
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="9 18 15 12 9 6"></polyline>
                                        </svg>
                                    </a>
                                @else
                                    <span></span>
                                @endif
                                @if(count($items) > 1)
                                    <span style="color: rgba(255,255,255,0.4); font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-size: 13px; font-weight: 700;">
                                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }} / {{ str_pad(count($items), 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if(count($items) > 1)
                <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 24px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        @foreach($items as $index => $item)
                            <button onclick="goToSlide({{ $index }})" class="flash-dot" data-dot="{{ $index }}" style="height: 8px; border-radius: 9999px; border: none; cursor: pointer; padding: 0; width: {{ $index === 0 ? '24px' : '6px' }}; background: {{ $index === 0 ? '#f97316' : 'rgba(255,255,255,0.25)' }}; transition: width 0.3s, background 0.3s;"></button>
                        @endforeach
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <button onclick="prevSlide()" style="width: 40px; height: 40px; border-radius: 50%; border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.05); cursor: pointer; display: flex; align-items: center; justify-content: center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.7)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </button>
                        <button onclick="nextSlide()" style="width: 40px; height: 40px; border-radius: 50%; border: none; background: #ff6b00; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 24px rgba(255,107,0,0.3);">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
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
