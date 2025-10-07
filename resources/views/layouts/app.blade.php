<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Essentials -->
    <title>@yield('title', 'EVC - École Virtuelle des Créatifs | Formation Design & Marketing Digital Abidjan')</title>
    <meta name="description" content="@yield('description', 'Première école virtuelle de Côte d&rsquo;Ivoire. Formations certifiantes en Design Graphique, Community Management, Intelligence Artificielle. Rejoignez +500 étudiants à Abidjan.')">
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
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
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
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    
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
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
    
    @stack('styles')
</head>
<body class="bg-black font-sans antialiased">
    <div id="particles-js"></div>
    
    @include('homepage._preloader')
    @include('homepage._header')

    <main>
        @yield('content')
    </main>

    @include('homepage._footer')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script src="{{ asset('js/homepage.js') }}"></script>
    @stack('scripts')
</body>
</html>
