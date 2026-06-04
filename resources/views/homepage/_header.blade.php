<!-- Animation CSS pour le bouton Préinscription -->
<style>
@keyframes float-bounce {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-8px);
    }
}

@keyframes glow-pulse {
    0%, 100% {
        box-shadow: 0 8px 25px rgba(255, 152, 0, 0.4),
                    0 4px 15px rgba(255, 152, 0, 0.3),
                    inset 0 -3px 8px rgba(0, 0, 0, 0.2);
    }
    50% {
        box-shadow: 0 12px 35px rgba(255, 152, 0, 0.6),
                    0 6px 20px rgba(255, 152, 0, 0.5),
                    inset 0 -3px 8px rgba(0, 0, 0, 0.2);
    }
}

@keyframes shimmer {
    0% {
        background-position: -200% center;
    }
    100% {
        background-position: 200% center;
    }
}

.preinscription-btn {
    position: relative;
    overflow: hidden;
    border-radius: 50px !important;
    padding: 0.75rem 2rem !important;
    font-weight: 700 !important;
    font-size: 1rem !important;
    background: linear-gradient(135deg, #ff9800 0%, #ff6b00 100%) !important;
    border: 3px solid rgba(255, 255, 255, 0.2) !important;
    box-shadow: 0 8px 25px rgba(255, 152, 0, 0.4),
                0 4px 15px rgba(255, 152, 0, 0.3),
                inset 0 -3px 8px rgba(0, 0, 0, 0.2);
    animation: float-bounce 3s ease-in-out infinite, glow-pulse 2s ease-in-out infinite;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.preinscription-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 255, 255, 0.4),
        transparent
    );
    animation: shimmer 3s infinite;
}

.preinscription-btn::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.preinscription-btn:hover {
    transform: translateY(-4px) scale(1.05) !important;
    box-shadow: 0 15px 40px rgba(255, 152, 0, 0.6),
                0 8px 25px rgba(255, 152, 0, 0.5),
                inset 0 -3px 8px rgba(0, 0, 0, 0.3) !important;
    border-color: rgba(255, 255, 255, 0.4) !important;
}

.preinscription-btn:hover::after {
    width: 300px;
    height: 300px;
}

.preinscription-btn:active {
    transform: translateY(-2px) scale(1.02) !important;
    box-shadow: 0 8px 20px rgba(255, 152, 0, 0.5),
                0 4px 12px rgba(255, 152, 0, 0.4),
                inset 0 -2px 6px rgba(0, 0, 0, 0.3) !important;
}
</style>

<!-- Flash Info Bar CSS -->
<style>
    #flash-info-bar {
        position: fixed;
        top: calc(var(--evc-topbar-height, 40px) + 90px);
        left: 0;
        width: 100%;
        z-index: 49;
        height: 150px;
        background: linear-gradient(135deg, #0d1b2a 0%, #1b2838 40%, #0d1b2a 100%);
        border-bottom: 2px solid rgba(255,107,0,0.4);
        box-shadow: 0 8px 32px rgba(0,0,0,0.5), 0 2px 0 rgba(255,107,0,0.3);
        overflow: hidden;
        transition: transform 0.35s ease, opacity 0.35s ease, visibility 0.35s ease;
    }
    #flash-info-bar.flash-info-hidden {
        transform: translateY(-110%);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }
    #flash-info-bar::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle at 1px 1px, rgba(255,107,0,0.06) 1px, transparent 0);
        background-size: 28px 28px;
        pointer-events: none;
    }
    .flash-badge {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-width: 130px;
        height: 100%;
        background: linear-gradient(180deg, #ff6b00 0%, #e65000 100%);
        box-shadow: 4px 0 24px rgba(255,107,0,0.5);
        position: relative;
        flex-shrink: 0;
        padding: 0 20px;
    }
    .flash-badge::after {
        content: '';
        position: absolute;
        right: -18px;
        top: 0;
        bottom: 0;
        width: 36px;
        background: linear-gradient(90deg, #e65000, transparent);
    }
    .flash-badge .ping-dot {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
    }
    .flash-badge .ping-dot span.ping {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: rgba(255,255,255,0.5);
        animation: flashPing 1.2s cubic-bezier(0,0,0.2,1) infinite;
    }
    .flash-badge .ping-dot span.dot {
        position: relative;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 0 8px rgba(255,255,255,0.8);
    }
    @keyframes flashPing {
        75%, 100% { transform: scale(2); opacity: 0; }
    }
    .flash-badge-label {
        font-size: 11px;
        font-weight: 900;
        letter-spacing: 0.18em;
        color: #fff;
        text-transform: uppercase;
        text-shadow: 0 1px 4px rgba(0,0,0,0.3);
        writing-mode: horizontal-tb;
        text-align: center;
        line-height: 1.2;
    }
    .flash-content-area {
        flex: 1;
        height: 100%;
        position: relative;
        overflow: hidden;
        padding: 0 24px 0 48px;
        display: flex;
        align-items: center;
    }
    .flash-item {
        position: absolute;
        left: 48px;
        right: 24px;
        top: 0;
        bottom: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 10px;
        transition: opacity 0.7s ease, transform 0.7s ease;
    }
    .flash-item-icon {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #ff9844;
    }
    .flash-item-icon i { font-size: 13px; }
    .flash-item-text {
        font-size: clamp(1rem, 2.2vw, 1.4rem);
        font-weight: 800;
        color: #fff;
        line-height: 1.35;
        text-shadow: 0 2px 8px rgba(0,0,0,0.4);
        letter-spacing: -0.01em;
    }
    .flash-item-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,107,0,0.18);
        border: 1px solid rgba(255,107,0,0.4);
        color: #ff9844;
        font-size: 12px;
        font-weight: 700;
        padding: 5px 16px;
        border-radius: 20px;
        text-decoration: none;
        width: fit-content;
        transition: background 0.2s;
    }
    .flash-item-link:hover { background: rgba(255,107,0,0.35); color: #fff; }
    .flash-dots {
        display: flex;
        flex-direction: column;
        gap: 6px;
        justify-content: center;
        padding: 0 16px;
        height: 100%;
        flex-shrink: 0;
    }
    .flash-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: rgba(255,255,255,0.25);
        transition: background 0.3s, transform 0.3s;
        cursor: pointer;
    }
    .flash-dot.active {
        background: #ff6b00;
        transform: scale(1.4);
    }
    @media (max-width: 640px) {
        #flash-info-bar {
            height: auto;
            min-height: 80px;
            top: calc(var(--evc-topbar-height, 40px) + 68px);
        }
        #flash-info-bar > div {
            flex-direction: row;
            align-items: stretch;
            padding: 0;
            max-width: 100%;
        }
        .flash-badge {
            min-width: 60px;
            width: 60px;
            padding: 12px 8px;
            gap: 4px;
            flex-shrink: 0;
        }
        .flash-badge .ping-dot { width: 12px; height: 12px; }
        .flash-badge .ping-dot span.dot { width: 8px; height: 8px; }
        .flash-badge-label { font-size: 8px; letter-spacing: 0.1em; }
        .flash-content-area {
            padding: 12px 10px 12px 24px;
            height: auto;
            min-height: 80px;
            overflow: visible;
            display: flex;
            align-items: center;
        }
        .flash-item {
            position: relative;
            left: auto;
            right: auto;
            top: auto;
            bottom: auto;
            inset: unset;
            width: 100%;
            gap: 6px;
            transition: none;
        }
        .flash-item.hidden-mobile {
            display: none;
        }
        .flash-item-icon {
            font-size: 10px;
            gap: 6px;
        }
        .flash-item-text {
            font-size: 0.82rem;
            font-weight: 700;
            line-height: 1.45;
            white-space: normal;
            overflow: visible;
            text-overflow: unset;
        }
        .flash-item-link {
            display: inline-flex;
            font-size: 11px;
            padding: 4px 12px;
        }
        .flash-dots {
            padding: 12px 10px 12px 4px;
            flex-direction: column;
            gap: 5px;
            align-items: center;
            justify-content: center;
            height: auto;
            align-self: stretch;
            flex-shrink: 0;
        }
        .flash-dot {
            width: 5px;
            height: 5px;
        }
    }
</style>

<!-- Header -->
<header id="main-header" class="bg-gradient-to-b fixed top-0 left-0 w-full z-50 transition-all duration-300">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-3 py-1 lg:px-8 lg:py-0" style="min-height:90px;">
        <div class="flex lg:flex-1">
            <a href="{{ url('/') }}">
                <img class="h-16 lg:h-20 w-auto transition-all duration-300" src="{{ asset('assets/img/logo.png') }}" alt="EVC Logo" decoding="async" fetchpriority="high">
            </a>
        </div>
        <div class="flex lg:hidden">
            <button type="button" id="mobile-menu-open-button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-400">
                <span class="sr-only">Ouvrir le menu principal</span>
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
        <div class="hidden lg:flex lg:gap-x-12">
            <a href="{{ route('presentation') }}" class="text-sm font-semibold leading-6 text-gray-300 hover:text-white transition">Présentation</a>
            <a href="{{ route('formations') }}" class="text-sm font-semibold leading-6 text-gray-300 hover:text-white transition">Nos Formations</a>
            <a href="{{ route('travaux') }}" class="text-sm font-semibold leading-6 text-gray-300 hover:text-white transition">Travaux Étudiants</a>
            <a href="{{ route('laureats') }}" class="text-sm font-semibold leading-6 text-gray-300 hover:text-white transition mr-4 lg:mr-8">Nos Lauréats</a>
        </div>
        <div class="hidden lg:flex lg:flex-1 lg:justify-end lg:items-center gap-x-6">
             <a href="{{ route('preinscription.start') }}" class="preinscription-btn whitespace-nowrap inline-flex items-center px-4 py-2 rounded-full text-white font-semibold bg-gradient-to-r from-orange-500 to-amber-400 hover:from-orange-400 hover:to-amber-300 shadow transition">Préinscription</a>

            <a href="{{ route('login') }}" target="_blank" class="btn btn-secondary whitespace-nowrap">Espace Étudiant</a>
        </div>
    </nav>
</header>

<!-- Flash Info Bar -->
<div id="flash-info-bar">
    @php
        $flashCommuniques = \App\Models\Communique::active()
            ->with(['actualite:id,slug','evenement:id,slug'])
            ->orderBy('order')->orderBy('created_at','desc')
            ->get();
        if ($flashCommuniques->isEmpty()) {
            $flashCommuniques = collect([
                (object)['content' => 'Rentrée 8è promo : 20 juin 2026 — Design Graphique & Community Management. 10 places disponibles.', 'actualite' => null, 'evenement' => null],
                (object)['content' => 'Studio Creative 5 : Phase préparatoire (07 Mai – 07 Juin 2026) · Phase de présentation (13 Juin 2026).', 'actualite' => null, 'evenement' => null],
                (object)['content' => 'Remise de Certifications : 20 juin à Abidjan en présentiel.', 'actualite' => null, 'evenement' => null],
            ]);
        }
    @endphp
    <div style="display:flex;height:100%;max-width:1280px;margin:0 auto;padding:0 1.5rem;position:relative;">
        {{-- Badge FLASH INFO --}}
        <div class="flash-badge">
            <div class="ping-dot">
                <span class="ping"></span>
                <span class="dot"></span>
            </div>
            <div class="flash-badge-label">FLASH<br>INFO</div>
        </div>

        {{-- Items --}}
        <div class="flash-content-area">
            @foreach($flashCommuniques as $i => $c)
                @php
                    $url = null;
                    if (!empty($c->actualite?->slug)) $url = route('actualite.show', $c->actualite->slug);
                    elseif (!empty($c->evenement?->slug)) $url = route('evenement.show', $c->evenement->slug);
                @endphp
                <div class="flash-item {{ $i === 0 ? '' : 'opacity-0 hidden-mobile' }}" style="{{ $i !== 0 ? 'transform:translateY(20px);' : '' }}" data-flash-index="{{ $i }}">
                    <div class="flash-item-icon">
                        <i class="fas fa-bolt"></i>
                        <span>Annonce</span>
                    </div>
                    <div class="flash-item-text">{!! $c->content !!}</div>
                    @if($url)
                        <a href="{{ $url }}" class="flash-item-link">
                            <i class="fas fa-arrow-right"></i> Lire l'annonce
                        </a>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Dots navigation --}}
        <div class="flash-dots">
            @foreach($flashCommuniques as $i => $c)
                <div class="flash-dot {{ $i === 0 ? 'active' : '' }}" data-flash-dot="{{ $i }}"></div>
            @endforeach
        </div>
    </div>
</div>

<script>
(function(){
    const items = document.querySelectorAll('#flash-info-bar .flash-item');
    const dots  = document.querySelectorAll('#flash-info-bar .flash-dot');
    if (items.length < 2) return;
    let cur = 0, timer;

    function isMobileFlash() {
        return window.matchMedia('(max-width: 640px)').matches;
    }

    function goTo(next) {
        if (isMobileFlash()) {
            items[cur].classList.add('hidden-mobile');
            items[cur].style.opacity = '0';
            items[cur].style.transform = 'translateY(0)';
            dots[cur].classList.remove('active');
            cur = next;
            items[cur].classList.remove('hidden-mobile');
            items[cur].style.opacity = '1';
            items[cur].style.transform = 'translateY(0)';
            dots[cur].classList.add('active');
            return;
        }

        items[cur].style.transition = 'opacity 0.7s ease, transform 0.7s ease';
        items[cur].style.opacity = '0';
        items[cur].style.transform = 'translateY(-20px)';
        dots[cur].classList.remove('active');
        cur = next;
        items[cur].style.transition = 'none';
        items[cur].style.transform = 'translateY(20px)';
        items[cur].style.opacity = '0';
        void items[cur].offsetWidth;
        items[cur].style.transition = 'opacity 0.7s ease, transform 0.7s ease';
        items[cur].style.opacity = '1';
        items[cur].style.transform = 'translateY(0)';
        dots[cur].classList.add('active');
    }

    function startTimer() { timer = setInterval(() => goTo((cur + 1) % items.length), 5000); }
    startTimer();

    dots.forEach((dot, i) => dot.addEventListener('click', () => { clearInterval(timer); goTo(i); startTimer(); }));
})();

(function(){
    const flashBar = document.getElementById('flash-info-bar');
    if (!flashBar || window.location.pathname !== '/') return;

    let ticking = false;

    function toggleFlashOnScroll() {
        const shouldHide = window.scrollY > 120;
        flashBar.classList.toggle('flash-info-hidden', shouldHide);
        ticking = false;
    }

    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(toggleFlashOnScroll);
            ticking = true;
        }
    }, { passive: true });

    toggleFlashOnScroll();
})();
</script>

<!-- Mobile menu -->
<div id="mobile-menu" class="lg:hidden hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 z-[9998] bg-black/30" aria-hidden="true"></div>
    <div class="fixed inset-y-0 right-0 z-[9999] w-full overflow-y-auto bg-gradient-to-b from-[#000033] to-[#000066] px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-white/10">
        <div class="flex items-center justify-between">
            <a href="{{ url('/') }}" class="-m-1.5 p-1.5">
                <img class="h-16 w-auto" src="{{ asset('assets/img/logo.png') }}" alt="EVC Logo" decoding="async">
            </a>
            <button type="button" id="mobile-menu-close-button" class="-m-2.5 rounded-md p-2.5 text-gray-400">
                <span class="sr-only">Fermer le menu</span>
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <div class="mt-6 flow-root">
            <div class="-my-6 divide-y divide-gray-500/25">
                <div class="space-y-2 py-6">
                    <a href="{{ route('preinscription.start') }}" class="-mx-3 block rounded-full py-3 px-6 text-base font-bold leading-7 text-white text-center" style="background: linear-gradient(135deg, #ff9800 0%, #ff6b00 100%); box-shadow: 0 8px 25px rgba(255, 152, 0, 0.4), 0 4px 15px rgba(255, 152, 0, 0.3); border: 2px solid rgba(255, 255, 255, 0.2); margin-bottom: 1rem;">
                        <i class="fas fa-edit mr-2"></i>Préinscription
                    </a>
                    <a href="{{ route('presentation') }}" class="-mx-3 block rounded-lg py-2 px-3 text-base font-semibold leading-7 text-white hover:bg-gray-800">Présentation</a>
                    <a href="{{ route('formations') }}" class="-mx-3 block rounded-lg py-2 px-3 text-base font-semibold leading-7 text-white hover:bg-gray-800">Nos Formations</a>
                    <a href="{{ route('travaux') }}" class="-mx-3 block rounded-lg py-2 px-3 text-base font-semibold leading-7 text-white hover:bg-gray-800">Travaux Étudiants</a>
                    <a href="{{ route('laureats') }}" class="-mx-3 block rounded-lg py-2 px-3 text-base font-semibold leading-7 text-white hover:bg-gray-800">Nos Lauréats</a>

                </div>
                <div class="py-6">
                    <a href="{{ route('login') }}" target="_blank" class="-mx-3 block rounded-lg py-2.5 px-3 text-base font-semibold leading-6 text-white hover:bg-gray-800">Espace Étudiant</a>
                </div>
            </div>
        </div>
    </div>
</div>
