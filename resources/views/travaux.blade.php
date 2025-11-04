@extends('layouts.app')

@section('title', 'Portfolio Étudiants EVC | Travaux Design Graphique & Identité Visuelle Abidjan')
@section('description', 'Découvrez les créations exceptionnelles de nos étudiants : identités visuelles, logotypes, affiches, plaquettes. Portfolio des meilleurs projets de design graphique réalisés à l\'EVC Abidjan.')
@section('keywords', 'portfolio étudiants, travaux design graphique, identité visuelle, logotype, affiche design, plaquette commerciale, projets étudiants EVC, création graphique Abidjan, portfolio design Côte d\'Ivoire')

@section('content')
<!-- Hero Section -->
<div class="relative pt-32 sm:pt-40 lg:pt-48 pb-16 bg-gradient-to-b from-[#0a1128] via-[#001f54] to-[#034078]">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-4xl text-center">
            <!-- Breadcrumb -->
            <nav class="flex justify-center mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('homepage') }}" class="inline-flex items-center text-sm font-medium text-gray-300 hover:text-orange-500">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Accueil
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-400 md:ml-2">Photothèque</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 leading-tight">
                Photothèque des Travaux Étudiants
            </h1>
            <p class="text-xl text-gray-300">
                Découvrez les créations exceptionnelles de nos étudiants
            </p>
        </div>
    </div>
</div>

<!-- Galerie de photos -->
<div class="bg-gradient-to-b from-[#034078] to-[#001233] py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <!-- Filtres par catégorie -->
        <div class="mb-12 flex flex-wrap justify-center gap-3" data-aos="fade-up">
            <button class="filter-btn active px-6 py-2 bg-orange-500 text-white rounded-full font-semibold hover:bg-orange-600 transition duration-300" data-filter="all">
                Tous les projets
            </button>
            <button class="filter-btn px-6 py-2 bg-white/10 text-white rounded-full font-semibold hover:bg-white/20 transition duration-300" data-filter="identite-visuelle">
                Identité visuelle
            </button>
            <button class="filter-btn px-6 py-2 bg-white/10 text-white rounded-full font-semibold hover:bg-white/20 transition duration-300" data-filter="affiche">
                Affiche
            </button>
            <button class="filter-btn px-6 py-2 bg-white/10 text-white rounded-full font-semibold hover:bg-white/20 transition duration-300" data-filter="plaquette">
                Plaquette
            </button>
            <button class="filter-btn px-6 py-2 bg-white/10 text-white rounded-full font-semibold hover:bg-white/20 transition duration-300" data-filter="logotypes">
                LogoTypes
            </button>
            <button class="filter-btn px-6 py-2 bg-white/10 text-white rounded-full font-semibold hover:bg-white/20 transition duration-300" data-filter="autres">
                Autres
            </button>
        </div>

        <!-- Grille de photos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="gallery">
            <!-- Design Graphique -->
            <div class="gallery-item" data-category="design-graphique" data-aos="fade-up" data-aos-delay="100">
                <a href="https://images.unsplash.com/photo-1558655146-d09347e92766?auto=format&fit=crop&w=1200&q=80" data-fancybox="gallery" data-caption="Affiche événementiel">
                    <div class="group relative overflow-hidden rounded-2xl shadow-lg aspect-square bg-gray-800">
                        <img src="https://images.unsplash.com/photo-1558655146-d09347e92766?auto=format&fit=crop&w=600&q=80" alt="Design Graphique" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <p class="text-white font-semibold">Affiche événementiel</p>
                                <p class="text-gray-300 text-sm">Design Graphique</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="gallery-item" data-category="design-graphique" data-aos="fade-up" data-aos-delay="150">
                <a href="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=1200&q=80" data-fancybox="gallery" data-caption="Logo Design">
                    <div class="group relative overflow-hidden rounded-2xl shadow-lg aspect-square bg-gray-800">
                        <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=600&q=80" alt="Logo Design" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <p class="text-white font-semibold">Logo Design</p>
                                <p class="text-gray-300 text-sm">Design Graphique</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Web Design -->
            <div class="gallery-item" data-category="web-design" data-aos="fade-up" data-aos-delay="200">
                <a href="https://images.unsplash.com/photo-1547658719-da2b51169166?auto=format&fit=crop&w=1200&q=80" data-fancybox="gallery" data-caption="Site E-commerce">
                    <div class="group relative overflow-hidden rounded-2xl shadow-lg aspect-square bg-gray-800">
                        <img src="https://images.unsplash.com/photo-1547658719-da2b51169166?auto=format&fit=crop&w=600&q=80" alt="Web Design" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <p class="text-white font-semibold">Site E-commerce</p>
                                <p class="text-gray-300 text-sm">Web Design</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="gallery-item" data-category="web-design" data-aos="fade-up" data-aos-delay="250">
                <a href="https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?auto=format&fit=crop&w=1200&q=80" data-fancybox="gallery" data-caption="Interface Mobile">
                    <div class="group relative overflow-hidden rounded-2xl shadow-lg aspect-square bg-gray-800">
                        <img src="https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?auto=format&fit=crop&w=600&q=80" alt="Interface Mobile" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <p class="text-white font-semibold">Interface Mobile</p>
                                <p class="text-gray-300 text-sm">Web Design</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Branding -->
            <div class="gallery-item" data-category="branding" data-aos="fade-up" data-aos-delay="300">
                <a href="https://images.unsplash.com/photo-1600132806370-bf17e65e942f?auto=format&fit=crop&w=1200&q=80" data-fancybox="gallery" data-caption="Identité Visuelle">
                    <div class="group relative overflow-hidden rounded-2xl shadow-lg aspect-square bg-gray-800">
                        <img src="https://images.unsplash.com/photo-1600132806370-bf17e65e942f?auto=format&fit=crop&w=600&q=80" alt="Branding" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <p class="text-white font-semibold">Identité Visuelle</p>
                                <p class="text-gray-300 text-sm">Branding</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="gallery-item" data-category="branding" data-aos="fade-up" data-aos-delay="350">
                <a href="https://images.unsplash.com/photo-1521185496955-15097b20c5fe?auto=format&fit=crop&w=1200&q=80" data-fancybox="gallery" data-caption="Packaging Design">
                    <div class="group relative overflow-hidden rounded-2xl shadow-lg aspect-square bg-gray-800">
                        <img src="https://images.unsplash.com/photo-1521185496955-15097b20c5fe?auto=format&fit=crop&w=600&q=80" alt="Packaging" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <p class="text-white font-semibold">Packaging Design</p>
                                <p class="text-gray-300 text-sm">Branding</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- UI/UX -->
            <div class="gallery-item" data-category="ui-ux" data-aos="fade-up" data-aos-delay="400">
                <a href="https://images.unsplash.com/photo-1557804506-669a67965ba0?auto=format&fit=crop&w=1200&q=80" data-fancybox="gallery" data-caption="Dashboard App">
                    <div class="group relative overflow-hidden rounded-2xl shadow-lg aspect-square bg-gray-800">
                        <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?auto=format&fit=crop&w=600&q=80" alt="UI/UX" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <p class="text-white font-semibold">Dashboard App</p>
                                <p class="text-gray-300 text-sm">UI/UX</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="gallery-item" data-category="ui-ux" data-aos="fade-up" data-aos-delay="450">
                <a href="https://images.unsplash.com/photo-1512486130939-2c4f79935e4f?auto=format&fit=crop&w=1200&q=80" data-fancybox="gallery" data-caption="Maquette Mobile">
                    <div class="group relative overflow-hidden rounded-2xl shadow-lg aspect-square bg-gray-800">
                        <img src="https://images.unsplash.com/photo-1512486130939-2c4f79935e4f?auto=format&fit=crop&w=600&q=80" alt="Maquette" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <p class="text-white font-semibold">Maquette Mobile</p>
                                <p class="text-gray-300 text-sm">UI/UX</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Plus d'images pour remplir la galerie -->
            <div class="gallery-item" data-category="design-graphique" data-aos="fade-up" data-aos-delay="500">
                <a href="https://images.unsplash.com/photo-1572044162444-ad60f128bdea?auto=format&fit=crop&w=1200&q=80" data-fancybox="gallery" data-caption="Flyer Commercial">
                    <div class="group relative overflow-hidden rounded-2xl shadow-lg aspect-square bg-gray-800">
                        <img src="https://images.unsplash.com/photo-1572044162444-ad60f128bdea?auto=format&fit=crop&w=600&q=80" alt="Flyer" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <p class="text-white font-semibold">Flyer Commercial</p>
                                <p class="text-gray-300 text-sm">Design Graphique</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="gallery-item" data-category="web-design" data-aos="fade-up" data-aos-delay="550">
                <a href="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1200&q=80" data-fancybox="gallery" data-caption="Landing Page">
                    <div class="group relative overflow-hidden rounded-2xl shadow-lg aspect-square bg-gray-800">
                        <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80" alt="Landing Page" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <p class="text-white font-semibold">Landing Page</p>
                                <p class="text-gray-300 text-sm">Web Design</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="gallery-item" data-category="branding" data-aos="fade-up" data-aos-delay="600">
                <a href="https://images.unsplash.com/photo-1615529162924-f8605388461d?auto=format&fit=crop&w=1200&q=80" data-fancybox="gallery" data-caption="Brand Identity">
                    <div class="group relative overflow-hidden rounded-2xl shadow-lg aspect-square bg-gray-800">
                        <img src="https://images.unsplash.com/photo-1615529162924-f8605388461d?auto=format&fit=crop&w=600&q=80" alt="Brand" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <p class="text-white font-semibold">Brand Identity</p>
                                <p class="text-gray-300 text-sm">Branding</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="gallery-item" data-category="ui-ux" data-aos="fade-up" data-aos-delay="650">
                <a href="https://images.unsplash.com/photo-1541462608143-67571c6738dd?auto=format&fit=crop&w=1200&q=80" data-fancybox="gallery" data-caption="User Interface">
                    <div class="group relative overflow-hidden rounded-2xl shadow-lg aspect-square bg-gray-800">
                        <img src="https://images.unsplash.com/photo-1541462608143-67571c6738dd?auto=format&fit=crop&w=600&q=80" alt="UI" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <p class="text-white font-semibold">User Interface</p>
                                <p class="text-gray-300 text-sm">UI/UX</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Section CTA final -->
@include('homepage._cta-final')

<!-- Script pour le filtrage -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Update active button
            filterBtns.forEach(b => {
                b.classList.remove('active', 'bg-orange-500');
                b.classList.add('bg-white/10');
            });
            this.classList.add('active', 'bg-orange-500');
            this.classList.remove('bg-white/10');
            
            // Filter items
            galleryItems.forEach(item => {
                if (filter === 'all' || item.dataset.category === filter) {
                    item.style.display = 'block';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'scale(1)';
                    }, 10);
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.9)';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
    
    // Add transition to gallery items
    galleryItems.forEach(item => {
        item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
    });
});
</script>
@endsection
