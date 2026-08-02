<!-- Animation CSS pour le bouton Préinscription -->
<style>
    @keyframes float-bounce {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-8px);
        }
    }

    @keyframes glow-pulse {

        0%,
        100% {
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
        font-weight: 700 !important;
        font-size: 1rem !important;
        background: linear-gradient(135deg, #ff9800 0%, #ff6b00 100%) !important;
        border: 3px solid rgba(255, 255, 255, 0.2) !important;
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
        background: linear-gradient(90deg,
                transparent,
                rgba(255, 255, 255, 0.4),
                transparent);
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
        border-color: rgba(255, 255, 255, 0.4) !important;
    }

    .preinscription-btn:hover::after {
        width: 300px;
        height: 300px;
    }

    .preinscription-btn:active {
        transform: translateY(-2px) scale(1.02) !important;
    }

    @media (min-width: 1024px) {
        .logo-desktop {
            height: 6rem;
        }
    }
</style>

<!-- Flash Info Bar CSS -->
<style>
    #flash-info-bar {
        position: fixed;
        top: 100px;
        left: 0;
        width: 100%;
        z-index: 49;
        height: 120px;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        transition: transform 0.35s ease, opacity 0.35s ease, visibility 0.35s ease;
    }

    #flash-info-bar.flash-info-hidden {
        transform: translateY(0);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }

    .flash-badge {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-width: 100px;
        height: 100%;
        background: linear-gradient(135deg, #ff6b00 0%, #f97316 100%);
        position: relative;
        flex-shrink: 0;
        padding: 0 16px;
    }

    .flash-badge .pulse-ring {
        width: 24px;
        height: 24px;
        position: relative;
    }

    .flash-badge .pulse-ring::before,
    .flash-badge .pulse-ring::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.4);
        animation: pulse 2s ease-out infinite;
    }

    .flash-badge .pulse-ring::after {
        animation-delay: 1s;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        100% {
            transform: scale(2);
            opacity: 0;
        }
    }

    .flash-badge-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.2em;
        color: #fff;
        text-transform: uppercase;
        text-align: center;
        line-height: 1.1;
    }

    .flash-content-area {
        flex: 1;
        height: 100%;
        position: relative;
        overflow: hidden;
        padding: 0 20px 0 20px;
        display: flex;
        align-items: center;
    }

    .flash-item {
        position: absolute;
        left: 20px;
        right: 20px;
        top: 0;
        bottom: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 8px;
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    .flash-item-icon {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: #f97316;
    }

    .flash-item-icon i {
        font-size: 12px;
    }

    .flash-item-text {
        font-size: clamp(0.95rem, 2vw, 1.25rem);
        font-weight: 600;
        color: #1e293b;
        line-height: 1.4;
        letter-spacing: -0.01em;
    }

    .flash-item-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #f97316;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        width: fit-content;
        transition: color 0.2s;
    }

    .flash-item-link:hover {
        color: #ea580c;
    }

    .flash-dots {
        display: flex;
        flex-direction: column;
        gap: 8px;
        justify-content: center;
        padding: 0 16px;
        height: 100%;
        flex-shrink: 0;
    }

    .flash-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(148, 163, 184, 0.4);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .flash-dot.active {
        background: #f97316;
        transform: scale(1.25);
    }

    .flash-dot:hover {
        background: rgba(249, 115, 22, 0.6);
    }

    @media (max-width: 640px) {
        #flash-info-bar {
            height: auto;
            min-height: 90px;
            top: 100px;
        }

        #flash-info-bar>div {
            flex-direction: row;
            align-items: stretch;
            padding: 0;
            max-width: 100%;
        }

        .flash-badge {
            min-width: 70px;
            width: 70px;
            padding: 10px 8px;
            gap: 4px;
            flex-shrink: 0;
        }

        .flash-badge .pulse-ring {
            width: 18px;
            height: 18px;
        }

        .flash-badge-label {
            font-size: 8px;
            letter-spacing: 0.1em;
        }

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
<header id="main-header" class="bg-white fixed top-0 left-0 w-full z-50 transition-all duration-300 shadow-sm">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-3 py-1 lg:px-8 lg:py-0" style="min-height:90px;">
        <div class="flex lg:flex-1">
            <a href="{{ url('/') }}">
                <img class="h-16 logo-desktop w-auto transition-all duration-300" src="{{ asset('assets/img/logo_evc.png') }}"
                    alt="EVC Logo" decoding="async" fetchpriority="high">
            </a>
        </div>
        <div class="flex lg:hidden">
            <button type="button" id="mobile-menu-open-button"
                class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-900">
                <span class="sr-only">Ouvrir le menu principal</span>
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
        <div class="hidden lg:flex lg:gap-x-12">
            <a href="{{ route('presentation') }}"
                class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700 transition">Présentation</a>
            <a href="{{ route('formations') }}"
                class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700 transition">Nos Formations</a>
            <a href="{{ route('travaux') }}"
                class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700 transition">Travaux Étudiants</a>
            <a href="{{ route('laureats') }}"
                class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700 transition mr-4 lg:mr-8">Nos
                Lauréats</a>
        </div>
        <div class="hidden lg:flex lg:flex-1 lg:justify-end lg:items-center gap-x-6">
            <a href="{{ route('preinscription.start') }}"
                class="preinscription-btn whitespace-nowrap inline-flex items-center px-4 py-2 rounded-full text-white font-semibold bg-gradient-to-r from-orange-500 to-amber-400 hover:from-orange-400 hover:to-amber-300 shadow transition">Préinscription</a>

            <a href="{{ route('login') }}" target="_blank"
                class="whitespace-nowrap inline-flex items-center px-4 py-2 rounded-full text-white font-semibold bg-gradient-to-r from-blue-700 to-blue-900 hover:from-blue-600 hover:to-blue-800 shadow transition">Espace
                Étudiant</a>
        </div>
    </nav>
</header>

<!-- Flash Info Bar -->
<div id="flash-info-bar">
    @php
        $flashCommuniques = \App\Models\Communique::active()
            ->with(['actualite:id,slug', 'evenement:id,slug'])
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get();
        if ($flashCommuniques->isEmpty()) {
            $flashCommuniques = collect([
                (object) [
                    'content' =>
                        'Rentrée 8è promo : 20 juin 2026 — Design Graphique & Community Management. 10 places disponibles.',
                    'actualite' => null,
                    'evenement' => null,
                ],
                (object) [
                    'content' =>
                        'Studio Creative 5 : Phase préparatoire (07 Mai – 07 Juin 2026) · Phase de présentation (13 Juin 2026).',
                    'actualite' => null,
                    'evenement' => null,
                ],
                (object) [
                    'content' => 'Remise de Certifications : 20 juin à Abidjan en présentiel.',
                    'actualite' => null,
                    'evenement' => null,
                ],
            ]);
        }
    @endphp
    <div style="display:flex;height:100%;max-width:1280px;margin:0 auto;padding:0 1.5rem;position:relative;">
        {{-- Badge FLASH INFO --}}
        <div class="flash-badge">
            <div class="pulse-ring"></div>
            <div class="flash-badge-label">FLASH<br>INFO</div>
        </div>

        {{-- Items --}}
        <div class="flash-content-area">
            @foreach ($flashCommuniques as $i => $c)
                @php
                    $url = null;
                    if (!empty($c->actualite?->slug)) {
                        $url = route('actualite.show', $c->actualite->slug);
                    } elseif (!empty($c->evenement?->slug)) {
                        $url = route('evenement.show', $c->evenement->slug);
                    }
                @endphp
                <div class="flash-item {{ $i === 0 ? '' : 'opacity-0 hidden-mobile' }}"
                    style="{{ $i !== 0 ? 'transform:translateY(20px);' : '' }}" data-flash-index="{{ $i }}">
                    <div class="flash-item-icon">
                        <i class="fas fa-bolt"></i>
                        <span>Annonce</span>
                    </div>
                    <div class="flash-item-text">{!! $c->content !!}</div>
                    @if ($url)
                        <a href="{{ $url }}" class="flash-item-link">
                            <i class="fas fa-arrow-right"></i> Lire l'annonce
                        </a>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Dots navigation --}}
        <div class="flash-dots">
            @foreach ($flashCommuniques as $i => $c)
                <div class="flash-dot {{ $i === 0 ? 'active' : '' }}" data-flash-dot="{{ $i }}"></div>
            @endforeach
        </div>
    </div>
</div>

<script>
    (function() {
        const items = document.querySelectorAll('#flash-info-bar .flash-item');
        const dots = document.querySelectorAll('#flash-info-bar .flash-dot');
        if (items.length === 0) return;
        let cur = 0,
            timer;

        function isMobileFlash() {
            return window.matchMedia('(max-width: 640px)').matches;
        }

        function goTo(next) {
            if (items.length === 1) {
                // Single item: subtle pulse animation
                items[0].style.transition = 'transform 0.5s ease, opacity 0.5s ease';
                items[0].style.transform = 'scale(1.02)';
                items[0].style.opacity = '0.8';
                setTimeout(() => {
                    items[0].style.transform = 'scale(1)';
                    items[0].style.opacity = '1';
                }, 500);
                return;
            }

            if (isMobileFlash()) {
                // Mobile: simple fade
                items[cur].classList.add('hidden-mobile');
                items[cur].style.opacity = '0';
                dots[cur].classList.remove('active');
                cur = next;
                items[cur].classList.remove('hidden-mobile');
                items[cur].style.opacity = '1';
                dots[cur].classList.add('active');
                return;
            }

            // Desktop: smooth slide + fade
            items[cur].style.transition = 'opacity 0.6s ease, transform 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
            items[cur].style.opacity = '0';
            items[cur].style.transform = 'translateX(-30px)';
            dots[cur].classList.remove('active');
            cur = next;
            items[cur].style.transition = 'none';
            items[cur].style.opacity = '0';
            items[cur].style.transform = 'translateX(30px)';
            void items[cur].offsetWidth;
            items[cur].style.transition = 'opacity 0.6s ease, transform 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
            items[cur].style.opacity = '1';
            items[cur].style.transform = 'translateX(0)';
            dots[cur].classList.add('active');
        }

        function startTimer() {
            timer = setInterval(() => goTo((cur + 1) % items.length), 6000);
        }
        startTimer();

        dots.forEach((dot, i) => dot.addEventListener('click', () => {
            clearInterval(timer);
            goTo(i);
            startTimer();
        }));

        // Pause on hover
        const flashBar = document.getElementById('flash-info-bar');
        flashBar.addEventListener('mouseenter', () => clearInterval(timer));
        flashBar.addEventListener('mouseleave', startTimer);
    })();

    (function() {
        const flashBar = document.getElementById('flash-info-bar');
        if (!flashBar) return;

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
        }, {
            passive: true
        });

        toggleFlashOnScroll();
    })();

</script>

<!-- Mobile menu -->
<div id="mobile-menu" class="lg:hidden hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 z-[9998] bg-black/30" aria-hidden="true"></div>
    <div
        class="fixed inset-y-0 right-0 z-[9999] w-full overflow-y-auto bg-gradient-to-b from-[#000033] to-[#000066] px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-white/10">
        <div class="flex items-center justify-between">
            <a href="{{ url('/') }}" class="-m-1.5 p-1.5">
                <img class="h-16 w-auto" src="{{ asset('assets/img/logo_evc.png') }}" alt="EVC Logo" decoding="async">
            </a>
            <button type="button" id="mobile-menu-close-button" class="-m-2.5 rounded-md p-2.5 text-gray-400">
                <span class="sr-only">Fermer le menu</span>
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <div class="mt-6 flow-root">
            <div class="-my-6 divide-y divide-gray-500/25">
                <div class="space-y-2 py-6">
                    <a href="{{ route('preinscription.start') }}"
                        class="-mx-3 block rounded-full py-3 px-6 text-base font-bold leading-7 text-white text-center"
                        style="background: linear-gradient(135deg, #ff9800 0%, #ff6b00 100%); box-shadow: 0 8px 25px rgba(255, 152, 0, 0.4), 0 4px 15px rgba(255, 152, 0, 0.3); border: 2px solid rgba(255, 255, 255, 0.2); margin-bottom: 1rem;">
                        <i class="fas fa-edit mr-2"></i>Préinscription
                    </a>
                    <a href="{{ route('presentation') }}"
                        class="-mx-3 block rounded-lg py-2 px-3 text-base font-semibold leading-7 text-white hover:bg-gray-800">Présentation</a>
                    <a href="{{ route('formations') }}"
                        class="-mx-3 block rounded-lg py-2 px-3 text-base font-semibold leading-7 text-white hover:bg-gray-800">Nos
                        Formations</a>
                    <a href="{{ route('travaux') }}"
                        class="-mx-3 block rounded-lg py-2 px-3 text-base font-semibold leading-7 text-white hover:bg-gray-800">Travaux
                        Étudiants</a>
                    <a href="{{ route('laureats') }}"
                        class="-mx-3 block rounded-lg py-2 px-3 text-base font-semibold leading-7 text-white hover:bg-gray-800">Nos
                        Lauréats</a>

                </div>
                <div class="py-6">
                    <a href="{{ route('login') }}" target="_blank"
                        class="-mx-3 block rounded-lg py-2.5 px-3 text-base font-semibold leading-6 text-white hover:bg-gray-800">Espace
                        Étudiant</a>
                </div>
            </div>
        </div>
    </div>
</div>
