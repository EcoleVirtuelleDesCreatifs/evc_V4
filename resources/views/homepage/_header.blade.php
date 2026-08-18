<!-- Header & Navigation Styles -->
<style>
    /* Header moderne et épuré */
    #main-header {
        background-color: rgba(255, 255, 255, 0.98) !important;
        background-image: none !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 4px 20px rgba(0, 0, 0, 0.05);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    /* Empêcher les classes dark de s'appliquer */
    #main-header.bg-gray-900\/90,
    #main-header.backdrop-blur-lg {
        background-color: rgba(255, 255, 255, 0.98) !important;
        backdrop-filter: none !important;
    }

    .evc-header {
        min-height: 72px;
        padding: 0.75rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        max-width: 1280px;
        margin: 0 auto;
        gap: 1.5rem;
    }

    .evc-logo {
        display: flex;
        align-items: center;
        flex-shrink: 0;
    }

    .evc-logo img {
        height: 3.5rem;
        width: auto;
        transition: transform 0.3s ease;
    }

    .evc-logo:hover img {
        transform: scale(1.03);
    }

    .evc-nav {
        display: none;
        align-items: center;
        gap: 0.375rem;
    }

    @media (min-width: 1024px) {
        .evc-nav {
            display: flex;
            flex: 1;
            justify-content: center;
        }
    }

    .evc-nav-link {
        padding: 0.625rem 1rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
        color: #1f2937;
        text-decoration: none;
        transition: all 0.25s ease;
        white-space: nowrap;
    }

    .evc-nav-link:hover {
        color: #ff6b35;
        background-color: rgba(255, 107, 53, 0.08);
    }

    .evc-nav-link.active {
        color: #ff6b35;
        background-color: rgba(255, 107, 53, 0.1);
    }

    .evc-nav-item {
        position: relative;
    }

    .evc-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        margin-top: 0.5rem;
        min-width: 220px;
        background: rgba(255, 255, 255, 0.98);
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 1rem;
        padding: 0.5rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.25s ease;
        z-index: 50;
    }

    .evc-nav-item:hover .evc-dropdown,
    .evc-nav-item:focus-within .evc-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .evc-dropdown-link {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        padding: 0.625rem 1rem;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #1f2937;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .evc-dropdown-link:hover {
        color: #ff6b35;
        background-color: rgba(255, 107, 53, 0.08);
    }

    .evc-dropdown-link i {
        width: 18px;
        text-align: center;
        color: #ff6b35;
        font-size: 0.875rem;
    }

    .evc-nav-item > .evc-nav-link .fa-chevron-down {
        font-size: 0.625rem;
        margin-left: 0.25rem;
        transition: transform 0.25s ease;
    }

    .evc-nav-item:hover > .evc-nav-link .fa-chevron-down {
        transform: rotate(180deg);
    }

    .evc-actions {
        display: none;
        align-items: center;
        gap: 0.75rem;
        flex-shrink: 0;
    }

    @media (min-width: 1024px) {
        .evc-actions {
            display: flex;
        }
    }

    .evc-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.625rem 1.25rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
        white-space: nowrap;
    }

    .evc-btn-primary {
        background: linear-gradient(135deg, #ff7a42 0%, #ff6b35 100%);
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(255, 107, 53, 0.35);
    }

    .evc-btn-primary:hover {
        background: linear-gradient(135deg, #ff8c5a 0%, #ff7a42 100%);
        box-shadow: 0 6px 20px rgba(255, 107, 53, 0.45);
        transform: translateY(-1px);
    }

    .evc-btn-secondary {
        background: #ffffff;
        color: #1f2937;
        border: 1px solid rgba(0, 0, 0, 0.1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .evc-btn-secondary:hover {
        background: #f8fafc;
        border-color: #ff6b35;
        color: #ff6b35;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .evc-mobile-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 0.75rem;
        color: #1f2937;
        border: 1px solid rgba(0, 0, 0, 0.1);
        background: #ffffff;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .evc-mobile-toggle:hover {
        background: rgba(255, 107, 53, 0.08);
        color: #ff6b35;
        border-color: rgba(255, 107, 53, 0.3);
    }

    @media (min-width: 1024px) {
        .evc-mobile-toggle {
            display: none;
        }
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
        top: 130px;
        left: 0;
        width: 100%;
        z-index: 49;
        height: 150px;
        background: linear-gradient(135deg, #0d1b2a 0%, #1b2838 40%, #0d1b2a 100%);
        border-bottom: 2px solid rgba(255, 107, 0, 0.4);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5), 0 2px 0 rgba(255, 107, 0, 0.3);
        overflow: hidden;
        transition: transform 0.35s ease, opacity 0.35s ease, visibility 0.35s ease;
    }

    #flash-info-bar.flash-info-hidden {
        transform: translateY(0);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }

    #flash-info-bar::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle at 1px 1px, rgba(255, 107, 0, 0.06) 1px, transparent 0);
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
        box-shadow: 4px 0 24px rgba(255, 107, 0, 0.5);
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
        background: rgba(255, 255, 255, 0.5);
        animation: flashPing 1.2s cubic-bezier(0, 0, 0.2, 1) infinite;
    }

    .flash-badge .ping-dot span.dot {
        position: relative;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 0 8px rgba(255, 255, 255, 0.8);
    }

    @keyframes flashPing {

        75%,
        100% {
            transform: scale(2);
            opacity: 0;
        }
    }

    .flash-badge-label {
        font-size: 11px;
        font-weight: 900;
        letter-spacing: 0.18em;
        color: #fff;
        text-transform: uppercase;
        text-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
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

    .flash-item-icon i {
        font-size: 13px;
    }

    .flash-item-text {
        font-size: clamp(1rem, 2.2vw, 1.4rem);
        font-weight: 800;
        color: #fff;
        line-height: 1.35;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
        letter-spacing: -0.01em;
    }

    .flash-item-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 107, 0, 0.18);
        border: 1px solid rgba(255, 107, 0, 0.4);
        color: #ff9844;
        font-size: 12px;
        font-weight: 700;
        padding: 5px 16px;
        border-radius: 20px;
        text-decoration: none;
        width: fit-content;
        transition: background 0.2s;
    }

    .flash-item-link:hover {
        background: rgba(255, 107, 0, 0.35);
        color: #fff;
    }

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
        background: rgba(255, 255, 255, 0.25);
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
            top: 130px;
        }

        #flash-info-bar>div {
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

        .flash-badge .ping-dot {
            width: 12px;
            height: 12px;
        }

        .flash-badge .ping-dot span.dot {
            width: 8px;
            height: 8px;
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
<header id="main-header" class="fixed top-0 left-0 w-full z-50">
    <div class="evc-header">
        <a href="{{ url('/') }}" class="evc-logo">
            <img class="logo-desktop" src="{{ asset('assets/img/logo_evc.png') }}" alt="EVC Logo" decoding="async" fetchpriority="high">
        </a>

        <nav class="evc-nav">
            <a href="{{ route('presentation') }}" class="evc-nav-link">Pourquoi EVC ?</a>
            <a href="{{ route('formations') }}" class="evc-nav-link">Nos formations</a>
            <a href="{{ route('travaux') }}" class="evc-nav-link">Projets Étudiants</a>
            <div class="evc-nav-item">
                <a href="{{ route('admissions') }}" class="evc-nav-link">
                    Admissions <i class="fas fa-chevron-down"></i>
                </a>
                <div class="evc-dropdown">
                    <a href="{{ route('laureats') }}" class="evc-dropdown-link"><i class="fas fa-trophy"></i> Nos lauréats</a>
                    <a href="{{ route('studio.creative') }}" class="evc-dropdown-link"><i class="fas fa-paint-brush"></i> Studio Creative</a>
                </div>
            </div>
            <a href="{{ route('evc.store') }}" class="evc-nav-link">EVC STORE</a>
        </nav>

        <div class="evc-actions">
            <a href="{{ route('preinscription.start') }}" class="evc-btn evc-btn-primary">
                <i class="fas fa-user-plus"></i>
                Je m'inscris
            </a>
            <a href="{{ route('login') }}" target="_blank" class="evc-btn evc-btn-secondary">
                <i class="fas fa-user"></i>
                MY EVC
            </a>
        </div>

        <button type="button" id="mobile-menu-open-button" class="evc-mobile-toggle">
            <span class="sr-only">Ouvrir le menu</span>
            <i class="fas fa-bars text-lg"></i>
        </button>
    </div>
</header>

<!-- Flash Info Bar -->
<div id="flash-info-bar">
    @php
        $flashCommuniques = \App\Models\Communique::active()
            ->with(['actualite:id,slug', 'evenement:id,slug'])
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get();
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

<!-- Mobile Menu Styles -->
<style>
    #mobile-menu .mobile-menu-panel {
        background: linear-gradient(180deg, #0d1333 0%, #151a3d 100%);
        border-left: 1px solid rgba(255, 107, 53, 0.15);
        box-shadow: -8px 0 40px rgba(0, 0, 0, 0.4);
    }

    #mobile-menu .mobile-menu-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        border-radius: 1rem;
        font-size: 1rem;
        font-weight: 600;
        color: #ffffff;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    #mobile-menu .mobile-menu-link:hover,
    #mobile-menu .mobile-menu-link:active {
        background: rgba(255, 107, 53, 0.12);
        color: #ff8c5a;
    }

    #mobile-menu .mobile-menu-link i {
        width: 24px;
        text-align: center;
        color: #ff6b35;
    }

    #mobile-menu .mobile-menu-close {
        width: 2.75rem;
        height: 2.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.75rem;
        color: #1f2937;
        background: #ffffff;
        border: none;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    #mobile-menu .mobile-menu-close:hover {
        background: rgba(255, 107, 53, 0.1);
        color: #ff6b35;
    }

    #mobile-menu .mobile-menu-cta {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 1rem 1.5rem;
        border-radius: 9999px;
        background: linear-gradient(135deg, #ff7a42 0%, #ff6b35 100%);
        color: #ffffff;
        font-weight: 700;
        text-align: center;
        text-decoration: none;
        box-shadow: 0 8px 24px rgba(255, 107, 53, 0.35);
        transition: all 0.25s ease;
    }

    #mobile-menu .mobile-menu-cta:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(255, 107, 53, 0.45);
    }

    #mobile-menu .mobile-menu-secondary {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.875rem 1.5rem;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: #ffffff;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    #mobile-menu .mobile-menu-secondary:hover {
        background: rgba(255, 107, 53, 0.12);
        border-color: rgba(255, 107, 53, 0.3);
    }
</style>

<!-- Mobile menu -->
<div id="mobile-menu" class="lg:hidden hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 z-[9998] bg-black/40" aria-hidden="true"></div>
    <div class="mobile-menu-panel fixed inset-y-0 right-0 z-[9999] w-full overflow-y-auto px-6 py-6 sm:max-w-sm">
        <div class="flex items-center justify-between">
            <a href="{{ url('/') }}" class="-m-1.5 p-1.5">
                <img class="h-12 w-auto" src="{{ asset('assets/img/logo_evc.png') }}" alt="EVC Logo" decoding="async">
            </a>
            <button type="button" id="mobile-menu-close-button" class="mobile-menu-close">
                <span class="sr-only">Fermer le menu</span>
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <div class="mt-8">
            <nav class="space-y-2">
                <a href="{{ route('presentation') }}" class="mobile-menu-link"><i class="fas fa-question-circle"></i> Pourquoi EVC ?</a>
                <a href="{{ route('formations') }}" class="mobile-menu-link"><i class="fas fa-graduation-cap"></i> Nos formations</a>
                <a href="{{ route('travaux') }}" class="mobile-menu-link"><i class="fas fa-project-diagram"></i> Projets Étudiants</a>
                <div class="mobile-menu-link mobile-menu-group-toggle" style="cursor: pointer;" onclick="this.nextElementSibling.classList.toggle('hidden')">
                    <i class="fas fa-door-open"></i> Admissions <i class="fas fa-chevron-down ml-auto"></i>
                </div>
                <div class="hidden pl-8 space-y-2 pb-2">
                    <a href="{{ route('admissions') }}" class="mobile-menu-link text-sm"><i class="fas fa-info-circle"></i> Admissions</a>
                    <a href="{{ route('laureats') }}" class="mobile-menu-link text-sm"><i class="fas fa-trophy"></i> Nos lauréats</a>
                    <a href="{{ route('studio.creative') }}" class="mobile-menu-link text-sm"><i class="fas fa-paint-brush"></i> Studio Creative</a>
                </div>
                <a href="{{ route('evc.store') }}" class="mobile-menu-link"><i class="fas fa-store"></i> EVC STORE</a>
            </nav>
            <div class="mt-8 pt-6 border-t border-white/10 space-y-4">
                <a href="{{ route('preinscription.start') }}" class="mobile-menu-cta">
                    <i class="fas fa-user-plus"></i> Je m'inscris
                </a>
                <a href="{{ route('login') }}" target="_blank" class="mobile-menu-secondary">
                    <i class="fas fa-user"></i> MY EVC
                </a>
            </div>
        </div>
    </div>
</div>
