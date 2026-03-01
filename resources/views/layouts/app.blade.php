<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Essentials -->
    <title>@yield('title', 'EVC - École Virtuelle des Créatifs | Formation Design & Marketing Digital Abidjan')</title>
    <meta name="description" content="@yield('description', 'Première école digitale en Afrique francophone. Formations certifiantes en Design Graphique, Community Management, Intelligence Artificielle. Rejoignez +1000 étudiants à travers le monde.')">
    <meta name="keywords" content="@yield('keywords', 'école virtuelle abidjan, formation design graphique côte d&rsquo;ivoire, formation marketing digital abidjan, community management formation, intelligence artificielle formation, école en ligne afrique, certification adobe abidjan, formation professionnelle ivoirienne')">
    <meta name="author" content="EVC - École Virtuelle des Créatifs">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Geo Tags -->
    <meta name="geo.region" content="CI-AB">
    <meta name="geo.placename" content="Abidjan">
    <meta name="geo.position" content="5.316667;-4.033333">
    <meta name="ICBM" content="5.316667, -4.033333">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og:type', 'website')">
    <meta property="og:url" content="@yield('og:url', url()->current())">
    <meta property="og:site_name" content="EVC - École Virtuelle des Créatifs">
    <meta property="og:title" content="@yield('og:title', 'EVC - Première École Virtuelle de Côte d&rsquo;Ivoire | Formations Certifiantes')">
    <meta property="og:description" content="@yield('og:description', 'Formations en Design Graphique, Community Management et Intelligence Artificielle. +500 étudiants formés à Abidjan. Inscriptions ouvertes !')">
    <meta property="og:image" content="@yield('og:image', asset('assets/img/hero-1.jpg'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="fr_CI">
    <meta property="og:locale:alternate" content="fr_FR">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@EcoleVirtuelleCi">
    <meta name="twitter:creator" content="@EcoleVirtuelleCi">
    <meta name="twitter:url" content="@yield('twitter:url', url()->current())">
    <meta name="twitter:title" content="@yield('twitter:title', 'EVC - Formations Professionnelles en Ligne | Abidjan')">
    <meta name="twitter:description" content="@yield('twitter:description', 'Design, Marketing Digital, IA. Formations certifiantes avec suivi personnalisé à Abidjan.')">
    <meta name="twitter:image" content="@yield('twitter:image', asset('assets/img/hero-1.jpg'))">

    <!-- Mobile & PWA -->
    <meta name="theme-color" content="#FF6B00">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="EVC">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicon/favicon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        (function () {
            var shouldShowForLink = function (a) {
                if (!a) return false;
                var href = a.getAttribute('href');
                if (!href) return false;
                if (href.startsWith('#')) return false;
                if (href.startsWith('javascript:')) return false;
                if (href.startsWith('mailto:')) return false;
                if (href.startsWith('tel:')) return false;
                if (a.getAttribute('target')) return false;
                if (a.hasAttribute('download')) return false;
                if (a.classList && a.classList.contains('no-loader')) return false;
                if (a.hasAttribute('data-fancybox')) return false;
                return true;
            };

            document.addEventListener('click', function (e) {
                if (e.defaultPrevented) return;
                if (e.button && e.button !== 0) return;
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
                var a = e.target && e.target.closest ? e.target.closest('a') : null;
                if (!shouldShowForLink(a)) return;
                var preloader = document.getElementById('preloader');
                if (!preloader) return;
                preloader.style.display = 'flex';
                preloader.style.opacity = '1';

                var href = a.href;
                if (!href) return;
                // Laisser le temps au navigateur d'afficher le loader avant de naviguer
                e.preventDefault();
                setTimeout(function () {
                    window.location.href = href;
                }, 50);
            }, true);
        })();
    </script>

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "EducationalOrganization",
        "name": "École Virtuelle des Créatifs",
        "alternateName": "EVC",
        "url": "http://127.0.0.1:8000",
        "logo": "http://127.0.0.1:8000/assets/img/logo.png",
        "description": "Première école virtuelle de Côte d'Ivoire spécialisée dans les formations créatives et digitales",
        "address": {
            "@@type": "PostalAddress",
            "streetAddress": "Abidjan",
            "addressLocality": "Abidjan",
            "addressRegion": "Abidjan",
            "addressCountry": "CI"
        },
        "geo": {
            "@@type": "GeoCoordinates",
            "latitude": "5.316667",
            "longitude": "-4.033333"
        },
        "contactPoint": {
            "@@type": "ContactPoint",
            "telephone": "+225-XX-XX-XX-XX",
            "contactType": "Admissions",
            "areaServed": "CI",
            "availableLanguage": ["French"]
        },
        "sameAs": [
            "https://www.facebook.com/EcoleVirtuelleCi",
            "https://www.instagram.com/ecolevirtuelle_ci",
            "https://www.linkedin.com/company/ecolevirtuelledescreatifs"
        ],
        "hasOfferCatalog": {
            "@@type": "OfferCatalog",
            "name": "Formations EVC",
            "itemListElement": [
                {
                    "@@type": "Course",
                    "name": "Design Graphique",
                    "description": "Formation complète en design graphique avec Adobe Creative Suite",
                    "provider": {
                        "@@type": "Organization",
                        "name": "EVC"
                    }
                },
                {
                    "@@type": "Course",
                    "name": "Community Management",
                    "description": "Formation en gestion de communautés et marketing digital",
                    "provider": {
                        "@@type": "Organization",
                        "name": "EVC"
                    }
                },
                {
                    "@@type": "Course",
                    "name": "Intelligence Artificielle",
                    "description": "Formation aux fondamentaux de l'IA et ses applications",
                    "provider": {
                        "@@type": "Organization",
                        "name": "EVC"
                    }
                }
            ]
        }
    }
    </script>

    @stack('head')

    <!-- Styles and Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css">
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">

    @stack('styles')

    <style>
        :root { --evc-topbar-height: 40px; }
        #main-header { top: var(--evc-topbar-height) !important; }

        @media (max-width: 640px) {
            :root { --evc-topbar-height: 64px; }
        }

        @keyframes evcTopbarMarquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .evc-topbar-marquee {
            display: inline-flex;
            white-space: nowrap;
            animation: evcTopbarMarquee 120s linear infinite;
        }

        .evc-topbar-partner-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .evc-topbar-flash {
            transition: opacity 280ms ease-in-out;
            opacity: 1;
        }

        .evc-topbar-flash.is-fading {
            opacity: 0;
        }
    </style>
</head>
<body class="bg-black font-sans antialiased">
    <div id="particles-js"></div>

    @include('homepage._preloader')

    <div class="fixed top-0 left-0 w-full z-[60] border-b border-white/20 bg-gradient-to-r from-[#ff6b00] via-[#ff9800] to-[#ff6b00]">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="min-h-[40px] sm:h-[40px] py-1 sm:py-0 flex flex-col sm:flex-row sm:items-center justify-center gap-2 sm:gap-3 text-xs sm:text-sm font-extrabold tracking-wide text-white">
                @if(!empty($activePartnerships) && $activePartnerships->count() > 0)
                    <span class="w-full sm:w-auto text-center sm:text-left shrink-0 whitespace-nowrap">Nos partenaires officiels :</span>
                    @php
                        $topbarPartners = $activePartnerships->map(function ($p) {
                            $subtitle = !empty($p->subtitle) ? ' ' . $p->subtitle : '';
                            return [
                                'text' => $p->prefix . ' ' . $p->name . $subtitle,
                                'url' => route('partnerships.show', $p->slug),
                            ];
                        })->values();

                        $firstTopbarPartner = $topbarPartners->first();
                    @endphp

                    <div class="w-full flex items-center gap-2 sm:gap-3">
                        <button type="button" id="topbar-partner-prev" class="shrink-0 inline-flex items-center justify-center rounded-full bg-black/20 px-2 py-1 text-[11px] sm:text-xs font-black text-white ring-1 ring-inset ring-white/20 hover:bg-black/30" aria-label="Partenaire précédent">‹</button>

                        <div class="flex-1 overflow-hidden min-w-0 evc-topbar-flash" id="topbar-partners" data-topbar-partners='@json($topbarPartners)'>
                            <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                                <span id="topbar-partner-text" class="evc-topbar-partner-text flex-1 min-w-0">{{ $firstTopbarPartner['text'] ?? '' }}</span>
                                <a id="topbar-partner-link" href="{{ $firstTopbarPartner['url'] ?? '#' }}" class="shrink-0 inline-flex items-center rounded-full bg-black/20 px-2 sm:px-3 py-1 text-[11px] sm:text-xs font-black text-white ring-1 ring-inset ring-white/20 hover:bg-black/30 whitespace-nowrap">En savoir plus</a>
                            </div>
                        </div>

                        <button type="button" id="topbar-partner-next" class="shrink-0 inline-flex items-center justify-center rounded-full bg-black/20 px-2 py-1 text-[11px] sm:text-xs font-black text-white ring-1 ring-inset ring-white/20 hover:bg-black/30" aria-label="Partenaire suivant">›</button>
                    </div>
                @else
                    <div class="flex-1 text-center">Partenaire</div>
                @endif
            </div>
        </div>
    </div>

    @include('homepage._header')

    <main>
        @yield('content')
    </main>

    @include('homepage._footer')
    @include('homepage._popup-preinscription')

    <!-- Bouton Retour en Haut -->
    <button id="scrollToTop" class="scroll-to-top" aria-label="Retour en haut">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script defer src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script defer src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script defer src="{{ asset('js/homepage.js') }}"></script>
    @stack('scripts')
    <script>
        (function () {
            const container = document.getElementById('topbar-partners');
            if (!container) return;

            let items = [];
            try {
                items = JSON.parse(container.getAttribute('data-topbar-partners') || '[]');
            } catch (e) {
                items = [];
            }
            if (!Array.isArray(items) || items.length === 0) return;

            const textEl = document.getElementById('topbar-partner-text');
            const linkEl = document.getElementById('topbar-partner-link');
            const prevBtn = document.getElementById('topbar-partner-prev');
            const nextBtn = document.getElementById('topbar-partner-next');

            let index = 0;
            const flashRender = () => {
                const item = items[index];
                const text = item && item.text ? item.text : '';
                const url = item && item.url ? item.url : '#';
                container.classList.add('is-fading');
                window.setTimeout(() => {
                    if (textEl) textEl.textContent = text;
                    if (linkEl) linkEl.setAttribute('href', url);
                    container.classList.remove('is-fading');
                }, 140);
            };

            const prev = () => {
                index = (index - 1 + items.length) % items.length;
                flashRender();
            };

            const next = () => {
                index = (index + 1) % items.length;
                flashRender();
            };

            if (prevBtn) prevBtn.addEventListener('click', prev);
            if (nextBtn) nextBtn.addEventListener('click', next);

            flashRender();

            if (items.length > 1) {
                let timer = window.setInterval(next, 4000);

                container.addEventListener('mouseenter', () => {
                    if (timer) {
                        window.clearInterval(timer);
                        timer = null;
                    }
                });

                container.addEventListener('mouseleave', () => {
                    if (!timer) timer = window.setInterval(next, 4000);
                });

                if (prevBtn) {
                    prevBtn.addEventListener('click', () => {
                        if (timer) {
                            window.clearInterval(timer);
                            timer = window.setInterval(next, 4000);
                        }
                    });
                }
                if (nextBtn) {
                    nextBtn.addEventListener('click', () => {
                        if (timer) {
                            window.clearInterval(timer);
                            timer = window.setInterval(next, 4000);
                        }
                    });
                }
            }
        })();
    </script>
</body>
</html>
