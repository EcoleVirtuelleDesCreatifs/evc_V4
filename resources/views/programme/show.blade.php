@extends('layouts.app')

@section('title', 'Programme - ' . (($programme->titre ?? null) ?: 'Détail') . ' - EVC')
@section('description', 'Consultez le programme et les séances associées.')
@section('keywords', 'programme, séances, EVC')

@section('content')
@php
    $itemsCollection = $items ?? ($programme->items ?? collect());
    if (!($itemsCollection instanceof \Illuminate\Support\Collection)) {
        $itemsCollection = collect($itemsCollection);
    }

    $formationPrefixSafe = $formationPrefix ?? 'design-graphique';

    $dashboardRouteNameByPrefix = [
        'design-graphique' => 'dashboard.design-graphique',
        'design-graphique-cm' => 'dashboard.design-graphique-cm',
        'community-management' => 'dashboard.community-management',
    ];

    $dashboardRouteName = $dashboardRouteNameByPrefix[$formationPrefixSafe] ?? 'dashboard.design-graphique';
@endphp

<div class="relative bg-gradient-to-b from-[#000033] to-[#000066] pt-32 sm:pt-40 pb-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-4xl text-center">
            <nav class="flex justify-center mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route($dashboardRouteName) }}" class="inline-flex items-center text-sm font-medium text-gray-300 hover:text-orange-500">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Espace étudiant
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route($formationPrefixSafe . '.programme.index') }}" class="ml-1 text-sm font-medium text-gray-300 hover:text-orange-500 md:ml-2">Programmes</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-400 md:ml-2">{{ $programme->titre ?? 'Programme' }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 leading-tight">{{ $programme->titre ?? 'Programme' }}</h1>
            <p class="text-xl text-gray-300">{{ $programme->formation ?? '' }}</p>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                <div class="inline-flex items-center px-6 py-3 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                    <svg class="w-5 h-5 mr-2 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <span class="text-white font-semibold">{{ $itemsCollection->count() }} séance{{ $itemsCollection->count() > 1 ? 's' : '' }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route($formationPrefixSafe . '.programme.index') }}" class="inline-flex items-center px-6 py-3 bg-white/10 text-white font-semibold rounded-full hover:bg-white/15 transition duration-300 border border-white/20">Retour</a>
                    @if(!empty($programme->fichier_pdf))
                        <a class="inline-flex items-center px-6 py-3 bg-orange-500 text-white font-semibold rounded-full hover:bg-orange-600 transition duration-300" target="_blank" href="{{ \App\Models\MediaUrl::fromPath($programme->fichier_pdf) }}">Télécharger le programme</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-black py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        @if($itemsCollection->isEmpty())
            <div class="text-center py-16">
                <div class="mx-auto max-w-md">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <h3 class="mt-6 text-xl font-medium text-white">Aucune séance</h3>
                    <p class="mt-2 text-gray-400">Ce programme ne contient pas encore de séances.</p>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($itemsCollection as $index => $item)
                    @php
                        $typeFormation = $item->type_formation ?? null;
                        $downloadPath = $item->piece_jointe ?? null;
                        $isPresentiel = ($typeFormation ?? null) === 'presentielle';
                        $badgeLabel = $typeFormation ? ($isPresentiel ? 'Présentielle' : 'En ligne') : null;
                        $badgeClasses = $isPresentiel ? 'bg-sky-500/10 text-sky-300' : 'bg-orange-500/10 text-orange-400';
                    @endphp

                    <article class="flex flex-col items-start justify-between rounded-2xl bg-gray-800/80 p-8 ring-1 ring-white/10 hover:ring-orange-500/50 transition duration-300" data-aos="fade-up" data-aos-delay="{{ min(($index % 12) * 50, 600) }}">
                        <div class="relative w-full">
                            <div class="aspect-[16/9] w-full rounded-2xl bg-gradient-to-br from-orange-500/20 to-white/5 ring-1 ring-white/10 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="mx-auto h-14 w-14 rounded-full bg-orange-500/20 flex items-center justify-center">
                                        <svg class="w-7 h-7 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-sm font-semibold text-white">Séance</div>
                                </div>
                            </div>

                            @if($badgeLabel)
                                <span class="absolute top-4 right-4 inline-flex items-center px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-sm text-xs font-bold border border-white/20 {{ $badgeClasses }}">
                                    {{ $badgeLabel }}
                                </span>
                            @endif
                        </div>

                        <div class="max-w-xl">
                            <div class="mt-8 flex items-center gap-x-4 text-xs">
                                <span class="text-gray-400">
                                    @if(!empty($item->session_date))
                                        {{ \Carbon\Carbon::parse($item->session_date)->locale('fr')->translatedFormat('d F Y') }}
                                    @else
                                        Date à confirmer
                                    @endif
                                    @if(!empty($item->session_time))
                                        • {{ \Carbon\Carbon::parse($item->session_time)->format('H:i') }}
                                    @else
                                        • Heure à confirmer
                                    @endif
                                    @if($isPresentiel && !empty($item->lieu))
                                        • {{ $item->lieu }}
                                    @endif
                                </span>
                            </div>

                            <div class="group relative">
                                <h3 class="mt-3 text-lg font-semibold leading-6 text-white">{{ $item->thematique ?? 'Séance' }}</h3>
                                @if(!empty($item->description))
                                    <p class="mt-4 text-sm leading-6 text-gray-300">{{ \Illuminate\Support\Str::limit($item->description, 140) }}</p>
                                @endif
                            </div>

                            <div class="mt-6">
                                @if(!empty($downloadPath))
                                    <a class="inline-flex items-center px-6 py-3 bg-orange-500 text-white font-semibold rounded-full hover:bg-orange-600 transition duration-300" target="_blank" href="{{ \App\Models\MediaUrl::fromPath($downloadPath) }}">Télécharger</a>
                                @else
                                    <span class="inline-flex items-center px-6 py-3 bg-white/10 text-gray-300 font-semibold rounded-full border border-white/20">Aucun fichier</span>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</div>

@include('homepage._cta-final')
@endsection
