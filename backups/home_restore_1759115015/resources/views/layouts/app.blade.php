<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO -->
    <title>@yield('title', 'EVC - École Virtuelle des Créatifs')</title>
    <meta name="description" content="@yield('description', 'Devenez un professionnel créatif avec EVC. Formations certifiantes en design graphique, marketing digital et plus.')">
    <meta name="keywords" content="@yield('keywords', 'école virtuelle, formation en ligne, design graphique, marketing digital, Abidjan, Côte d\'Ivoire')">
    <meta name="author" content="EVC - École Virtuelle des Créatifs">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og:title', 'EVC - Formations Créatives en Ligne')">
    <meta property="og:description" content="@yield('og:description', 'Formations certifiantes en design et marketing digital à Abidjan.')">
    <meta property="og:image" content="@yield('og:image', asset('assets/img/hero-1.jpg'))">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter:title', 'EVC - Formations Créatives en Ligne')">
    <meta name="twitter:description" content="@yield('twitter:description', 'Formations certifiantes en design et marketing digital à Abidjan.')">
    <meta name="twitter:image" content="@yield('twitter:image', asset('assets/img/hero-1.jpg'))">

    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    <style>
        /* Safety: ensure preloader is hidden by default */
        #preloader { display: none !important; }
        /* Prevent content from being hidden behind fixed header */
        main { padding-top: 5.5rem; }
        @media (min-width: 1024px) { /* lg */
            main { padding-top: 6.5rem; }
        }
    </style>
    <!-- Preloader safety fallback: hide after short delay even if window.load delays -->
    <script>
        (function(){
            document.addEventListener('DOMContentLoaded', function(){
                var pre = document.getElementById('preloader');
                if (pre) {
                    setTimeout(function(){
                        pre.style.opacity = '0';
                        setTimeout(function(){ pre.style.display = 'none'; }, 600);
                    }, 1000);
                }
            });
        })();
    </script>
</head>
<body class="bg-black font-sans antialiased">
    <!-- particles container is provided inside preloader and/or sections -->
    
    @include('homepage._preloader')
    @include('homepage._header')

    <main>
        @yield('content')
    </main>

    @include('homepage._footer')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js" defer></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script src="{{ asset('js/homepage.js') }}?v={{ @filemtime(public_path('js/homepage.js')) }}"></script>
    @stack('scripts')
</body>
</html>
