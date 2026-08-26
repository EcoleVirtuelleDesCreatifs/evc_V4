<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Essentials -->
    <title>@yield('title', 'EVC - École Numérique Abidjan | Formation Design Graphique & Digital Côte d\'Ivoire')</title>
    <meta name="description" content="@yield('description', 'EVC, école numérique N°1 à Abidjan. Formations certifiantes en Design Graphique (Adobe Photoshop), Community Management, Motion Design, Bureautique. École virtuelle des créatifs en Côte d\'Ivoire. Inscriptions ouvertes !')">
    <meta name="keywords" content="@yield('keywords', 'école numérique Abidjan, ecole numérique, école virtuelle des créatifs, EVC, ecole virtuelle abidjan, formation design graphique Abidjan, centre de formation Adobe Photoshop Abidjan, formation motion design Abidjan, école de community management Abidjan, formation bureautique avancé Abidjan, école d\'infographie Abidjan, centre de formation professionnelle Abidjan, formation certifiante reconnue par l\'État, formation en ligne reconnue par l\'État, formation en ligne reconnue à l\'international, école de formatique, liste des écoles informatique Abidjan, école de communication visuelle Abidjan, école de formation Abidjan, ecole digitale Côte d\'Ivoire, ECV, ECAV')">

    {{-- SEO Géographique : Communes d'Abidjan --}}
    <meta name="geo.keywords" content="Cocody, Plateau, Marcory, Treichville, Adjamé, Yopougon, Koumassi, Port-Bouët, Attécoubé, Abobo, Anyama, Bingerville, Grand-Bassam, Songon, Dabou">

    {{-- SEO Géographique : Villes Côte d'Ivoire --}}
    <meta name="coverage" content="Worldwide">
    <meta name="distribution" content="Global">
    <meta name="target" content="all">
    <meta name="city.keywords" content="Abidjan, Bouaké, Daloa, Yamoussoukro, Korhogo, Man, San-Pédro, Gagnoa, Abengourou, Divo, Agboville, Bondoukou, Duekoué, Odienné, Séguéla, Ferkessédougou, Touba, Danané, Guiglo, Tabou, Tiassalé, Adzopé, Bongouanou, Dimbokro, Issia, Lakota, Oumé, Soubré, Vavoua, Zuenoula">

    <meta name="author" content="EVC - École Virtuelle des Créatifs">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Geo Tags -->
    <meta name="geo.region" content="CI-AB">
    <meta name="geo.placename" content="Abidjan, Côte d'Ivoire">
    <meta name="geo.position" content="5.316667;-4.033333">
    <meta name="ICBM" content="5.316667, -4.033333">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og:type', 'website')">
    <meta property="og:url" content="@yield('og:url', url()->current())">
    <meta property="og:site_name" content="EVC - École Virtuelle des Créatifs">
    <meta property="og:title" content="@yield('og:title', 'EVC - École Numérique Abidjan | Design Graphique, Motion Design, Community Management')">
    <meta property="og:description" content="@yield('og:description', 'École numérique N°1 à Abidjan. Formations certifiées Adobe Photoshop, Motion Design, Community Management. École virtuelle des créatifs en Côte d\'Ivoire. Inscriptions ouvertes !')">
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
    <meta name="twitter:title" content="@yield('twitter:title', 'EVC École Numérique Abidjan | Design Graphique & Motion Design Côte d\'Ivoire')">
    <meta name="twitter:description" content="@yield('twitter:description', 'École virtuelle des créatifs à Abidjan. Adobe Photoshop, Motion Design, Community Management. Formations certifiées Côte d\'Ivoire.')">

    <meta name="twitter:image" content="@yield('twitter:image', asset('assets/img/hero-1.jpg'))">

    <!-- Mobile & PWA -->
    <meta name="theme-color" content="#FF6B00">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="EVC">

    <!-- Favicon -->

    <!-- Facebook Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '3150853591778776');
    fbq('track', 'PageView');
    </script>
    <noscript>
    <img height="1" width="1" src="https://www.facebook.com/tr?id=3150853591778776&ev=PageView&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-TB3PRBDYP0"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-TB3PRBDYP0');
    </script>
    <!-- End Google tag (gtag.js) -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicon/favicon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        (function () {
            const updateCsrfToken = (token) => {
                const meta = document.querySelector('meta[name="csrf-token"]');
                if (meta) meta.setAttribute('content', token);
                document.querySelectorAll('input[name="_token"]').forEach(input => input.value = token);
            };

            const refreshToken = async () => {
                try {
                    const res = await fetch('{{ url('/csrf-token') }}', {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!res.ok) return;
                    const data = await res.json();
                    if (data.token) updateCsrfToken(data.token);
                } catch (e) { /* silencieux */ }
            };

            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) refreshToken();
            });

            window.addEventListener('pageshow', (e) => {
                if (e.persisted) refreshToken();
            });

            setInterval(refreshToken, 4 * 60 * 1000);
        })();
    </script>

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
        "alternateName": ["EVC", "ecole virtuelle des creatifs", "ECV", "ECAV", "École Numérique Abidjan"],
        "url": "https://www.ecolevirtuelledescreatifs.com",
        "logo": "https://www.ecolevirtuelledescreatifs.com/assets/img/logo.png",
        "description": "École numérique N°1 à Abidjan. Formations certifiées en Design Graphique, Motion Design, Community Management, Bureautique. Formation en ligne reconnue à l'international.",
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
        "areaServed": [
            "CI", "SN", "ML", "BF", "GN", "CM", "TG", "BJ", "NE", "CD", "CG", "GA", "MG", "MR", "TD", "DJ", "KM", "BI", "RW",
            "FR", "BE", "CH", "CA", "US", "GB", "DE", "ES", "IT", "PT", "NL", "MA", "TN", "DZ", "EG", "NG", "GH", "ZA",
            "Abidjan", "Cocody", "Plateau", "Marcory", "Treichville", "Adjamé", "Yopougon", "Koumassi", "Port-Bouët", "Attécoubé", "Abobo", "Anyama", "Bingerville", "Grand-Bassam", "Songon",
            "Bouaké", "Daloa", "Yamoussoukro", "Korhogo", "Man", "San-Pédro", "Gagnoa", "Abengourou", "Divo", "Soubré", "Ferkessédougou"
        ],
        "contactPoint": {
            "@@type": "ContactPoint",
            "contactType": "Admissions",
            "areaServed": "Worldwide",
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

        @media (max-width: 768px) {
            :root { --evc-topbar-height: 40px; }
        }

        .evc-topbar-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: rgba(255,255,255,0.92);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-decoration: none;
            padding: 3px 8px;
            border-radius: 20px;
            transition: background 0.18s, color 0.18s;
            white-space: nowrap;
        }
        .evc-topbar-link:hover {
            background: rgba(0,0,0,0.18);
            color: #fff;
        }
        .evc-topbar-link svg { flex-shrink: 0; }
        .evc-topbar-sep {
            width: 1px;
            height: 14px;
            background: rgba(255,255,255,0.25);
            flex-shrink: 0;
        }
        .evc-topbar-don {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(0,0,0,0.22);
            border: 1px solid rgba(255,255,255,0.35);
            color: #fff;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.06em;
            padding: 3px 12px;
            border-radius: 20px;
            text-decoration: none;
            transition: background 0.18s;
            white-space: nowrap;
        }
        .evc-topbar-don:hover {
            background: rgba(0,0,0,0.4);
        }
        .evc-topbar-email {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: rgba(255,255,255,0.75);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.03em;
            text-decoration: none;
            white-space: nowrap;
        }
        .evc-topbar-email:hover { color: #fff; }
        @media (max-width: 900px) {
            .evc-topbar-hide-sm { display: none !important; }
        }
        @media (max-width: 600px) {
            .evc-topbar-hide-xs { display: none !important; }
        }
    </style>
</head>
<body class="bg-black font-sans antialiased">
    <div id="particles-js"></div>

    @include('homepage._preloader')

    <div class="fixed top-0 left-0 w-full z-[60] border-b border-white/10" style="background: linear-gradient(90deg, #0a0a0a 0%, #1a1a2e 40%, #16213e 70%, #0f3460 100%); height:40px;">
        <div class="mx-auto max-w-7xl px-4 lg:px-8 h-full">
            <div class="h-full flex items-center justify-between gap-2">

                {{-- Gauche : Email contact --}}
                <a href="mailto:info@ecolevirtuelledescreatifs.com" class="evc-topbar-email evc-topbar-hide-xs">
                    <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                    info@ecolevirtuelledescreatifs.com
                </a>

                {{-- Séparateur --}}
                <div class="evc-topbar-sep evc-topbar-hide-xs"></div>

                {{-- Centre : Liens navigation --}}
                <nav class="flex items-center gap-1">
                    <a href="{{ route('actualites') }}" class="evc-topbar-link">
                        <svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/><path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/></svg>
                        Actualités
                    </a>
                    <div class="evc-topbar-sep evc-topbar-hide-sm"></div>
                    <a href="{{ route('parcours-formateur') }}" class="evc-topbar-link evc-topbar-hide-sm">
                        <svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path d="M10 2a6 6 0 00-6 6c0 2.173 1.157 4.078 2.89 5.13.29.176.49.48.49.82V16a1 1 0 001 1h3.24a1 1 0 001-1v-2.05c0-.34.2-.644.49-.82A5.994 5.994 0 0016 8a6 6 0 00-6-6z"/><path d="M7 19a1 1 0 001 1h4a1 1 0 001-1H7z"/></svg>
                        Parcours du formateur
                    </a>
                    <div class="evc-topbar-sep evc-topbar-hide-sm"></div>
                    <a href="{{ route('plaquettes.formations') }}" class="evc-topbar-link evc-topbar-hide-sm">
                        <svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 3a2 2 0 012-2h5.586A2 2 0 0113 1.586L16.414 5A2 2 0 0117 6.414V17a2 2 0 01-2 2H6a2 2 0 01-2-2V3zm8 3a1 1 0 001 1h3l-4-4v3z" clip-rule="evenodd"/></svg>
                        Plaquette de formation
                    </a>
                    <div class="evc-topbar-sep evc-topbar-hide-sm"></div>
                    <a href="{{ route('evenements.all') }}" class="evc-topbar-link evc-topbar-hide-sm">
                        <svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                        Événements
                    </a>
                    <div class="evc-topbar-sep evc-topbar-hide-sm"></div>
                    <a href="{{ route('activity-reports.index') }}" class="evc-topbar-link evc-topbar-hide-sm">
                        <svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v2a1 1 0 102 0V9z" clip-rule="evenodd"/></svg>
                        Rapport d'activité
                    </a>
                    <div class="evc-topbar-sep"></div>
                    <a href="{{ route('rejoignez-nous') }}" class="evc-topbar-link">
                        <svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                        Contact
                    </a>
                </nav>

                {{-- Séparateur --}}
                <div class="evc-topbar-sep"></div>

                {{-- Droite : Faire un don (CTA impactant) --}}
                <a href="{{ url('/faire-un-don') }}" class="evc-topbar-don">
                    <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                    ✨ FAIRE UN DON
                </a>

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
        /* ===== SAFARI FIX : maintien de session + refresh CSRF ===== */
        (function() {
            var CSRF_URL = '/csrf-token';

            function refreshCsrf() {
                fetch(CSRF_URL, { credentials: 'same-origin' })
                    .then(function(r) { return r.ok ? r.json() : null; })
                    .then(function(data) {
                        if (!data || !data.token) return;
                        var meta = document.querySelector('meta[name="csrf-token"]');
                        if (meta) meta.setAttribute('content', data.token);
                        document.querySelectorAll('input[name="_token"]').forEach(function(el) {
                            el.value = data.token;
                        });
                    })
                    .catch(function() {});
            }

            /* Heartbeat toutes les 8 minutes pour maintenir la session active */
            setInterval(refreshCsrf, 8 * 60 * 1000);
        })();
    </script>
</body>
</html>
