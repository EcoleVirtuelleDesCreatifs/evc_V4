@extends('layouts.app')

@section('title', 'Portfolio Étudiants École Numérique EVC | Travaux Design Graphique & Motion Design Abidjan')
@section('description', 'Portfolio des étudiants de l\'EVC, école numérique d\'Abidjan : identités visuelles, logotypes, affiches, motion design. Meilleures créations graphiques de l\'école virtuelle des créatifs en Côte d\'Ivoire.')
@section('keywords', 'portfolio étudiants école numérique Abidjan, travaux design graphique Abidjan, motion design Abidjan, identité visuelle, logotype, affiche design, EVC, école virtuelle des créatifs, création graphique Côte d\'Ivoire')

@push('styles')
<style>
    .pt-\[500px\] {
        padding-top: 390px !important;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="relative pb-16 bg-gradient-to-b from-[#0a1128] via-[#001f54] to-[#034078]" style="padding-top: 390px;">
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
                            <span class="ml-1 text-sm font-medium text-gray-400 md:ml-2">Travaux Étudiants</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 leading-tight">
                Aperçu des Travaux Étudiants EVC
            </h1>
            <p class="text-xl text-gray-300">
                Découvrez les créations exceptionnelles de nos étudiants
            </p>
        </div>
    </div>
</div>

<!-- Statistiques -->
<div class="bg-gradient-to-b from-[#034078] to-[#001233] py-8">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8" data-aos="fade-up">
            @foreach($travaux as $key => $category)
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center border border-white/20">
                <p class="text-3xl font-bold text-orange-500">{{ $category['total'] }}</p>
                <p class="text-sm text-gray-300 mt-1">{{ $category['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Galerie de photos -->
<div class="bg-gradient-to-b from-[#001233] to-[#0a1128] py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <!-- Filtres par catégorie -->
        <div class="mb-12 flex flex-wrap justify-center gap-3" data-aos="fade-up">
            <button class="filter-btn active px-6 py-2 bg-orange-500 text-white rounded-full font-semibold hover:bg-orange-600 transition duration-300" data-filter="all">
                Tous les projets ({{ array_sum(array_column($travaux, 'total')) }})
            </button>
            @foreach($travaux as $key => $category)
            <button class="filter-btn px-6 py-2 bg-white/10 text-white rounded-full font-semibold hover:bg-white/20 transition duration-300" data-filter="{{ $key }}">
                {{ $category['label'] }} ({{ $category['total'] }})
            </button>
            @endforeach
        </div>

        <!-- Grille de photos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="gallery">
            @php
                $globalIndex = 0; // Compteur global pour toutes les images
            @endphp
            @foreach($travaux as $categoryKey => $category)
                @foreach($category['images'] as $index => $image)
                    @php
                        // Afficher seulement les 20 premières images globalement au chargement initial
                        $isInitiallyVisible = $globalIndex < 20;
                        $globalIndex++;
                    @endphp
                    <div class="gallery-item"
                         data-category="{{ $categoryKey }}"
                         data-index="{{ $index }}"
                         data-global-index="{{ $globalIndex - 1 }}"
                         style="{{ !$isInitiallyVisible ? 'display: none;' : '' }}">
                        <a href="{{ asset($image['path']) }}"
                           data-fancybox="gallery"
                           data-caption="{{ $category['label'] }} - {{ $image['filename'] }}">
                            <div class="group relative overflow-hidden rounded-2xl shadow-lg aspect-square bg-gray-800">
                                <img src="{{ asset($image['path']) }}"
                                     alt="{{ $category['label'] }} - {{ $image['filename'] }}"
                                     class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                                     loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="absolute bottom-0 left-0 right-0 p-4">
                                        <p class="text-white font-semibold text-sm">{{ $category['label'] }}</p>
                                        <p class="text-gray-300 text-xs">{{ pathinfo($image['filename'], PATHINFO_FILENAME) }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            @endforeach
        </div>

        <!-- Boutons Charger Plus par catégorie -->
        <div class="mt-12 space-y-4">
            <!-- Bouton Charger Plus pour "Tous les projets" -->
            @php
                $totalImages = array_sum(array_column($travaux, 'total'));
            @endphp
            @if($totalImages > 20)
            <div class="text-center load-more-container" data-category="all" id="load-more-all">
                <button class="load-more-btn inline-flex items-center gap-2 px-8 py-4 text-base font-semibold text-white bg-gradient-to-r from-orange-500 to-orange-600 rounded-full shadow-lg hover:from-orange-600 hover:to-orange-700 transform hover:scale-105 transition-all duration-300"
                        data-category="all"
                        data-loaded="20"
                        data-total="{{ $totalImages }}">
                    <span>Charger plus de travaux</span>
                    <span class="font-bold">(20 / {{ $totalImages }})</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
            @endif

            <!-- Boutons Charger Plus par catégorie spécifique -->
            @foreach($travaux as $categoryKey => $category)
                @if($category['total'] > 20)
                <div class="text-center load-more-container" data-category="{{ $categoryKey }}" style="display: none;">
                    <button class="load-more-btn inline-flex items-center gap-2 px-8 py-4 text-base font-semibold text-white bg-gradient-to-r from-orange-500 to-orange-600 rounded-full shadow-lg hover:from-orange-600 hover:to-orange-700 transform hover:scale-105 transition-all duration-300"
                            data-category="{{ $categoryKey }}"
                            data-loaded="20"
                            data-total="{{ $category['total'] }}">
                        <span>Charger plus de {{ $category['label'] }}</span>
                        <span class="font-bold">(20 / {{ $category['total'] }})</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </div>
                @endif
            @endforeach
        </div>
    </div>
</div>

<!-- Section CTA final -->
@include('homepage._cta-final')

<!-- Script pour le filtrage et la pagination -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');
    const loadMoreContainers = document.querySelectorAll('.load-more-container');
    const loadMoreBtns = document.querySelectorAll('.load-more-btn');

    let currentFilter = 'all';

    // Add transition to gallery items
    galleryItems.forEach(item => {
        item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
    });

    // Fonction pour afficher/masquer les boutons "Charger plus"
    function updateLoadMoreButtons(filter) {
        loadMoreContainers.forEach(container => {
            const category = container.dataset.category;
            if (filter === 'all') {
                // En mode "Tous les projets", afficher uniquement le bouton "all"
                container.style.display = (category === 'all') ? 'block' : 'none';
            } else {
                // En mode catégorie spécifique, afficher uniquement le bouton de cette catégorie
                container.style.display = (filter === category) ? 'block' : 'none';
            }
        });
    }

    // Filtrage
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.dataset.filter;
            currentFilter = filter;

            // Update active button
            filterBtns.forEach(b => {
                b.classList.remove('active', 'bg-orange-500');
                b.classList.add('bg-white/10');
            });
            this.classList.add('active', 'bg-orange-500');
            this.classList.remove('bg-white/10');

            // Filter items
            galleryItems.forEach(item => {
                const category = item.dataset.category;
                const index = parseInt(item.dataset.index);
                const globalIndex = parseInt(item.dataset.globalIndex);

                if (filter === 'all') {
                    // Afficher les 20 premières images globalement
                    if (globalIndex < 20) {
                        item.style.display = 'block';
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'scale(1)';
                        }, 10);
                    } else {
                        item.style.display = 'none';
                    }
                } else if (category === filter) {
                    // Afficher les 20 premiers de la catégorie filtrée
                    if (index < 20) {
                        item.style.display = 'block';
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'scale(1)';
                        }, 10);
                    } else {
                        item.style.display = 'none';
                    }
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.9)';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });

            // Réinitialiser les compteurs des boutons "Charger plus"
            loadMoreBtns.forEach(btn => {
                btn.dataset.loaded = '20';
                const total = btn.dataset.total;
                btn.querySelector('span:nth-child(2)').textContent = `(20 / ${total})`;
            });

            // Mettre à jour la visibilité des boutons "Charger plus"
            updateLoadMoreButtons(filter);

            // Scroll to gallery
            document.getElementById('gallery').scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    // Charger plus d'images
    loadMoreBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;
            let loaded = parseInt(this.dataset.loaded);
            const total = parseInt(this.dataset.total);
            const loadCount = 20;

            if (category === 'all') {
                // Mode "Tous les projets" : charger 20 images supplémentaires toutes catégories confondues
                let count = 0;
                let allItems = Array.from(galleryItems);

                // Afficher les images avec global-index >= loaded et < loaded + loadCount
                allItems.forEach(item => {
                    const globalIndex = parseInt(item.dataset.globalIndex);
                    if (globalIndex >= loaded && globalIndex < loaded + loadCount && count < loadCount) {
                        item.style.display = 'block';
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'scale(1)';
                        }, 10);
                        count++;
                    }
                });

                // Mettre à jour le compteur
                loaded += count;
                this.dataset.loaded = loaded;
                this.querySelector('span:nth-child(2)').textContent = `(${Math.min(loaded, total)} / ${total})`;

                // Masquer le bouton si toutes les images sont chargées
                if (loaded >= total) {
                    this.parentElement.style.display = 'none';
                }
            } else {
                // Mode catégorie spécifique
                let count = 0;
                galleryItems.forEach(item => {
                    if (item.dataset.category === category) {
                        const index = parseInt(item.dataset.index);
                        if (index >= loaded && index < loaded + loadCount && count < loadCount) {
                            item.style.display = 'block';
                            setTimeout(() => {
                                item.style.opacity = '1';
                                item.style.transform = 'scale(1)';
                            }, 10);
                            count++;
                        }
                    }
                });

                // Mettre à jour le compteur
                loaded += loadCount;
                this.dataset.loaded = loaded;
                this.querySelector('span:nth-child(2)').textContent = `(${Math.min(loaded, total)} / ${total})`;

                // Masquer le bouton si toutes les images sont chargées
                if (loaded >= total) {
                    this.parentElement.style.display = 'none';
                }
            }
        });
    });

    // Initialiser l'affichage des boutons "Charger plus"
    updateLoadMoreButtons('all');
});
</script>
@endsection
