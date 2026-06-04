@extends('layouts.app')

@section('title', 'Actualités École Numérique EVC Abidjan | Blog Design Graphique & Digital Côte d\'Ivoire')
@section('description', 'Actualités et articles de l\'EVC, école numérique d\'Abidjan : formations design graphique, motion design, community management, Adobe Photoshop. Toutes les nouvelles de l\'école virtuelle des créatifs.')
@section('keywords', 'actualités école numérique Abidjan, blog design graphique, EVC, école virtuelle des créatifs, formation motion design Abidjan, Adobe Photoshop Abidjan, community management Côte d\'Ivoire')

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-b from-[#000033] to-[#000066] pt-[200px] pb-16">
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
                            <span class="ml-1 text-sm font-medium text-gray-400 md:ml-2">Blog</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 leading-tight">
                Nos Actualités
            </h1>
            <p class="text-xl text-gray-300">
                Découvrez toutes nos actualités, articles et dernières nouvelles
            </p>

            <!-- Compteur -->
            <div class="mt-8 inline-flex items-center px-6 py-3 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                <svg class="w-5 h-5 mr-2 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                <span class="text-white font-semibold">{{ $actualites->total() }} article{{ $actualites->total() > 1 ? 's' : '' }} publié{{ $actualites->total() > 1 ? 's' : '' }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Liste des actualités -->
<div class="bg-black py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        @if($actualites->count() > 0)
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($actualites as $index => $actualite)
            <!-- Article {{ $index + 1 }} -->
            <article class="flex flex-col items-start justify-between rounded-2xl bg-gray-800/80 p-8 ring-1 ring-white/10 hover:ring-orange-500/50 transition duration-300" data-aos="fade-up" data-aos-delay="{{ min(($index % 12) * 50, 600) }}">
                <div class="relative w-full">
                    <a href="{{ route('actualite.show', $actualite->slug) }}" class="block">
                        <img src="{{ $actualite->cover_image ? $actualite->cover_image_url : 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=800&q=80' }}"
                             alt="{{ $actualite->cover_image_alt ?? $actualite->title }}"
                             class="aspect-[16/9] w-full rounded-2xl bg-gray-100 object-cover sm:aspect-[2/1] lg:aspect-[3/2] hover:opacity-90 transition-opacity duration-300">
                    </a>

                    @if($actualite->is_featured)
                    <span class="absolute top-4 right-4 inline-flex items-center px-3 py-1.5 rounded-full bg-yellow-500/90 backdrop-blur-sm text-yellow-900 text-xs font-bold">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        À la une
                    </span>
                    @endif
                </div>
                <div class="max-w-xl">
                    <div class="mt-8 flex items-center gap-x-4 text-xs">
                        <time datetime="{{ $actualite->created_at->format('Y-m-d') }}" class="text-gray-400">
                            {{ $actualite->created_at->format('d') }} {{ $actualite->created_at->locale('fr')->translatedFormat('F Y') }}
                        </time>
                        @php
                            $categoryColors = [
                                'general' => ['bg' => 'bg-gray-500/10', 'text' => 'text-gray-400'],
                                'formation' => ['bg' => 'bg-blue-500/10', 'text' => 'text-blue-400'],
                                'evenement' => ['bg' => 'bg-cyan-500/10', 'text' => 'text-cyan-400'],
                                'partenariat' => ['bg' => 'bg-green-500/10', 'text' => 'text-green-400'],
                                'succes' => ['bg' => 'bg-yellow-500/10', 'text' => 'text-yellow-400']
                            ];
                            $colors = $categoryColors[$actualite->category] ?? ['bg' => 'bg-orange-500/10', 'text' => 'text-orange-400'];

                            $categoryLabels = [
                                'general' => 'Général',
                                'formation' => 'Formation',
                                'evenement' => 'Événement',
                                'partenariat' => 'Partenariat',
                                'succes' => 'Succès'
                            ];
                            $categoryLabel = $categoryLabels[$actualite->category] ?? ucfirst($actualite->category);
                        @endphp
                        <span class="relative z-10 rounded-full {{ $colors['bg'] }} px-3 py-1.5 font-medium {{ $colors['text'] }}">
                            {{ $categoryLabel }}
                        </span>
                    </div>
                    <div class="group relative">
                        <h3 class="mt-3 text-lg font-semibold leading-6 text-white">
                            <a href="{{ route('actualite.show', $actualite->slug) }}">
                                <span class="absolute inset-0"></span>
                                {{ $actualite->title }}
                            </a>
                        </h3>
                        <p class="mt-4 text-sm leading-6 text-gray-300">{{ Str::limit($actualite->excerpt, 120) }}</p>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $actualites->links() }}
        </div>
        @else
        <!-- Message si aucune actualité -->
        <div class="text-center py-16">
            <div class="mx-auto max-w-md">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                <h3 class="mt-6 text-xl font-medium text-white">Aucune actualité disponible</h3>
                <p class="mt-2 text-gray-400">Les dernières actualités seront bientôt publiées. Restez connecté !</p>
                <div class="mt-8">
                    <a href="{{ route('homepage') }}"
                       class="inline-flex items-center px-6 py-3 bg-orange-500 text-white font-semibold rounded-full hover:bg-orange-600 transition duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Section CTA final -->
@include('homepage._cta-final')
@endsection
