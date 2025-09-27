<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVC - École Virtuelle des Créatifs | Formation en Ligne et en présentiel à Abidjan</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="Devenez un professionnel créatif avec EVC, la première école digitale en Côte d'Ivoire et en Afrique francophone. Formations certifiantes en design graphique, marketing digital et plus. Basé à Abidjan, nous formons les talents de demain pour toute l'Afrique.">
    <meta name="keywords" content="école virtuelle, formation en ligne, formation en présentiel, design graphique, marketing digital, Abidjan, Côte d'Ivoire, formation Afrique, certification professionnelle, créatifs, EVC, Adobe Photoshop, Illustrator, InDesign, web design">
    <meta name="author" content="EVC - École Virtuelle des Créatifs">
    <meta name="robots" content="index, follow">

    <!-- Geolocation Meta Tags -->
    <meta name="geo.placename" content="Abidjan">
    <meta name="geo.region" content="CI-AB">
    <meta name="geo.position" content="5.345317;-4.024429">
    <meta name="ICBM" content="5.345317, -4.024429">

    <!-- Open Graph / Facebook Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="EVC - Formations Créatives en Ligne et en présentiel à Abidjan">
    <meta property="og:description" content="Formations certifiantes en design et marketing digital à Abidjan, destinées à former les professionnels créatifs de demain en Côte d'Ivoire et en Afrique.">
    <meta property="og:image" content="{{ asset('assets/img/hero-1.jpg') }}">
    <meta property="og:site_name" content="EVC - École Virtuelle des Créatifs">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="EVC - Formations Créatives en Ligne et en présentiel à Abidjan">
    <meta name="twitter:description" content="Formations certifiantes en design et marketing digital à Abidjan, destinées à former les professionnels créatifs de demain en Côte d'Ivoire et en Afrique.">
    <meta name="twitter:image" content="{{ asset('assets/img/hero-1.jpg') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS -->
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

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Custom Homepage CSS -->
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
</head>
<body class="bg-black font-sans antialiased">
    <div id="particles-js"></div>
    @include('homepage._preloader')

    @include('homepage._header')

    @include('homepage._hero')

    @include('homepage._presentation')

    @include('homepage._fondateur')

    @include('homepage._international')

    @include('homepage._avantages')

    @include('homepage._formations')

    @include('homepage._travaux')

    @include('homepage._evenements')

    @include('homepage._actualites')

    @include('homepage._laureats')

    @include('homepage._temoignages')

    @include('homepage._chiffres')

    @include('homepage._preinscription')

    @include('homepage._cta-final')

    @include('homepage._footer')


    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="{{ asset('js/homepage.js') }}"></script>
</body>
</html>
