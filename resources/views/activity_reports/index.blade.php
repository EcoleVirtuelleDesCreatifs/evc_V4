@extends('layouts.app')

@section('title', "Rapports d'activité - EVC")
@section('description', "Consultez et téléchargez nos rapports d'activité publiés.")
@section('keywords', "rapports d'activité, EVC, rapports, documents, école virtuelle")

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-b from-[#000033] to-[#000066] pt-[500px] pb-16">
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
                            <span class="ml-1 text-sm font-medium text-gray-400 md:ml-2">Rapports d'activité</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 leading-tight">
                Rapports d'activité
            </h1>
            <p class="text-xl text-gray-300">
                Consultez et téléchargez nos rapports d'activité publiés
            </p>

            <!-- Compteur -->
            <div class="mt-8 inline-flex items-center px-6 py-3 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                <svg class="w-5 h-5 mr-2 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                <span class="text-white font-semibold">{{ $reports->total() }} rapport{{ $reports->total() > 1 ? 's' : '' }} publié{{ $reports->total() > 1 ? 's' : '' }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Liste des rapports -->
<div class="bg-black py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        @if($reports->count() > 0)
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($reports as $index => $report)
            <article class="flex flex-col items-start justify-between rounded-2xl bg-gray-800/80 p-8 ring-1 ring-white/10 hover:ring-orange-500/50 transition duration-300" data-aos="fade-up" data-aos-delay="{{ min(($index % 12) * 50, 600) }}">
                <div class="relative w-full">
                    <a href="{{ route('activity-reports.download', $report) }}" class="block">
                        <div class="aspect-[16/9] w-full rounded-2xl bg-gradient-to-br from-orange-500/20 to-white/5 ring-1 ring-white/10 flex items-center justify-center">
                            <div class="text-center">
                                <div class="mx-auto h-14 w-14 rounded-full bg-orange-500/20 flex items-center justify-center">
                                    <svg class="w-7 h-7 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-sm font-semibold text-white">PDF</div>
                            </div>
                        </div>
                    </a>

                    @if($report->year)
                    <span class="absolute top-4 right-4 inline-flex items-center px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-sm text-white text-xs font-bold border border-white/20">
                        {{ $report->year }}
                    </span>
                    @endif
                </div>

                <div class="max-w-xl">
                    <div class="mt-8 flex items-center gap-x-4 text-xs">
                        <time datetime="{{ optional($report->published_at)->format('Y-m-d') ?? $report->created_at->format('Y-m-d') }}" class="text-gray-400">
                            {{ (optional($report->published_at) ?? $report->created_at)->format('d') }} {{ (optional($report->published_at) ?? $report->created_at)->locale('fr')->translatedFormat('F Y') }}
                        </time>
                        <span class="relative z-10 rounded-full bg-orange-500/10 px-3 py-1.5 font-medium text-orange-400">
                            Rapport d'activité
                        </span>
                    </div>
                    <div class="group relative">
                        <h3 class="mt-3 text-lg font-semibold leading-6 text-white">
                            <a href="{{ route('activity-reports.download', $report) }}">
                                <span class="absolute inset-0"></span>
                                {{ $report->title }}
                            </a>
                        </h3>
                        @if($report->description)
                            <p class="mt-4 text-sm leading-6 text-gray-300">{{ \Illuminate\Support\Str::limit($report->description, 120) }}</p>
                        @else
                            <p class="mt-4 text-sm leading-6 text-gray-400">Téléchargez le rapport pour consulter le contenu.</p>
                        @endif
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('activity-reports.download', $report) }}" class="inline-flex items-center px-6 py-3 bg-orange-500 text-white font-semibold rounded-full hover:bg-orange-600 transition duration-300">
                            Télécharger
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $reports->links() }}
        </div>
        @else
        <!-- Message si aucun rapport -->
        <div class="text-center py-16">
            <div class="mx-auto max-w-md">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                <h3 class="mt-6 text-xl font-medium text-white">Aucun rapport disponible</h3>
                <p class="mt-2 text-gray-400">Les prochains rapports d'activité seront bientôt publiés.</p>
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
