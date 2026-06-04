@extends('layouts.app')

@section('title', $evenement->meta_title ?? $evenement->title . ' - EVC')
@section('description', $evenement->meta_description ?? $evenement->excerpt)
@section('keywords', $evenement->meta_keywords ?? 'événement, formation, EVC')

@push('head')
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ route('evenement.show', $evenement->slug) }}" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="event" />
    <meta property="og:url" content="{{ route('evenement.show', $evenement->slug) }}" />
    <meta property="og:title" content="{{ $evenement->meta_title ?? $evenement->title }}" />
    <meta property="og:description" content="{{ $evenement->meta_description ?? $evenement->excerpt }}" />
    <meta property="og:image" content="{{ $evenement->cover_image ? $evenement->cover_image_url : asset('assets/img/logo.png') }}" />
    <meta property="og:locale" content="fr_FR" />
    <meta property="og:site_name" content="École Virtuelle des Créatifs" />
    @if($evenement->event_date)
    <meta property="event:start_time" content="{{ $evenement->event_date->toIso8601String() }}" />
    @endif
    @if($evenement->event_end_date)
    <meta property="event:end_time" content="{{ $evenement->event_end_date->toIso8601String() }}" />
    @endif
    @if($evenement->location)
    <meta property="event:location" content="{{ $evenement->location }}" />
    @endif

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:url" content="{{ route('evenement.show', $evenement->slug) }}" />
    <meta name="twitter:title" content="{{ $evenement->meta_title ?? $evenement->title }}" />
    <meta name="twitter:description" content="{{ $evenement->meta_description ?? $evenement->excerpt }}" />
    <meta name="twitter:image" content="{{ $evenement->cover_image ? $evenement->cover_image_url : asset('assets/img/logo.png') }}" />

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    @php
        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $evenement->title,
            'description' => $evenement->excerpt,
            'startDate' => $evenement->event_date->toIso8601String(),
            'image' => $evenement->cover_image ? $evenement->cover_image_url : asset('assets/img/logo.png'),
            'organizer' => [
                '@type' => 'Organization',
                'name' => 'École Virtuelle des Créatifs',
                'url' => route('homepage')
            ],
            'eventStatus' => 'https://schema.org/EventScheduled'
        ];

        if ($evenement->event_end_date) {
            $jsonLd['endDate'] = $evenement->event_end_date->toIso8601String();
        }

        if ($evenement->event_type === 'online') {
            $jsonLd['eventAttendanceMode'] = 'https://schema.org/OnlineEventAttendanceMode';
            $jsonLd['location'] = [
                '@type' => 'VirtualLocation',
                'url' => $evenement->registration_link ?? route('evenement.show', $evenement->slug)
            ];
        } elseif ($evenement->location) {
            $jsonLd['eventAttendanceMode'] = $evenement->event_type === 'hybrid'
                ? 'https://schema.org/MixedEventAttendanceMode'
                : 'https://schema.org/OfflineEventAttendanceMode';
            $jsonLd['location'] = [
                '@type' => 'Place',
                'name' => $evenement->location,
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => $evenement->location,
                    'addressCountry' => 'CI'
                ]
            ];
        }

        if ($evenement->registration_link) {
            $jsonLd['offers'] = [
                '@type' => 'Offer',
                'url' => $evenement->registration_link,
                'availability' => 'https://schema.org/InStock'
            ];
        }
    @endphp
    {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@section('content')
<!-- Hero Section avec Image de couverture -->
<div class="relative bg-gradient-to-b from-[#000033] to-[#000066] pt-64 pb-12">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('homepage') }}" class="inline-flex items-center text-sm font-medium text-gray-300 hover:text-orange-500">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Accueil
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-400 md:ml-2">Événements</span>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 truncate">{{ Str::limit($evenement->title, 40) }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Image de couverture -->
            <div class="relative rounded-3xl overflow-hidden mb-8 shadow-2xl">
                <img src="{{ $evenement->cover_image ? $evenement->cover_image_url : 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?auto=format&fit=crop&w=1600&q=80' }}"
                     alt="{{ $evenement->cover_image_alt ?? $evenement->title }}"
                     class="w-full h-[400px] object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            </div>

            <!-- Type et Date -->
            <div class="flex flex-wrap items-center gap-4 mb-6">
                @php
                    $eventTypeColors = [
                        'physical' => ['bg' => 'bg-orange-500/20', 'text' => 'text-orange-400', 'border' => 'border-orange-500/50'],
                        'online' => ['bg' => 'bg-blue-500/20', 'text' => 'text-blue-400', 'border' => 'border-blue-500/50'],
                        'hybrid' => ['bg' => 'bg-green-500/20', 'text' => 'text-green-400', 'border' => 'border-green-500/50']
                    ];
                    $colors = $eventTypeColors[$evenement->event_type] ?? ['bg' => 'bg-gray-500/20', 'text' => 'text-gray-400', 'border' => 'border-gray-500/50'];

                    if ($evenement->event_type === 'physical') {
                        $displayText = $evenement->location ?: 'Présentiel';
                    } elseif ($evenement->event_type === 'online') {
                        $displayText = 'En ligne';
                    } elseif ($evenement->event_type === 'hybrid') {
                        $displayText = 'Hybride';
                    } else {
                        $displayText = ucfirst($evenement->event_type);
                    }
                @endphp
                <span class="inline-flex items-center px-4 py-2 rounded-full {{ $colors['bg'] }} {{ $colors['text'] }} border {{ $colors['border'] }} text-sm font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ $displayText }}
                </span>

                <span class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 text-gray-300 border border-white/20 text-sm font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ $evenement->event_date->format('d') }} {{ $evenement->event_date->locale('fr')->translatedFormat('F Y') }}
                    @if($evenement->event_end_date)
                        - {{ $evenement->event_end_date->format('d') }} {{ $evenement->event_end_date->locale('fr')->translatedFormat('F Y') }}
                    @endif
                </span>

                @if($evenement->is_featured)
                <span class="inline-flex items-center px-4 py-2 rounded-full bg-yellow-500/20 text-yellow-400 border border-yellow-500/50 text-sm font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    À la une
                </span>
                @endif

                <span class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 text-gray-400 border border-white/20 text-sm font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    {{ $evenement->views_count }} vue{{ $evenement->views_count > 1 ? 's' : '' }}
                </span>
            </div>

            <!-- Titre -->
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">
                {{ $evenement->title }}
            </h1>

            <!-- Description courte -->
            <p class="text-xl text-gray-300 mb-8 leading-relaxed">
                {{ $evenement->excerpt }}
            </p>

            <!-- Bouton d'inscription -->
            @if($evenement->registration_link)
            <div class="mb-8">
                <a href="{{ $evenement->registration_link }}"
                   target="_blank"
                   class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold text-lg rounded-full hover:from-orange-600 hover:to-orange-700 transform hover:scale-105 transition duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    S'inscrire à l'événement
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Contenu principal -->
<div class="bg-white py-16">
    <div class="mx-auto max-w-4xl px-6 lg:px-8">
        <!-- Contenu complet -->
        <div class="prose prose-lg max-w-none">
            <div class="text-gray-800 leading-relaxed space-y-4">
                {!! $evenement->content !!}
            </div>
        </div>

        <!-- Informations complémentaires -->
        @if($evenement->location && $evenement->event_type !== 'online')
        <div class="mt-12 p-6 bg-gray-50 rounded-2xl border border-gray-200">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Lieu de l'événement
            </h3>
            <p class="text-gray-700 text-lg">{{ $evenement->location }}</p>
        </div>
        @endif

        <!-- Bouton retour -->
        <div class="mt-12 text-center">
            <a href="{{ route('homepage') }}#evenements"
               class="inline-flex items-center px-6 py-3 bg-gray-800 text-white font-semibold rounded-full hover:bg-gray-900 transition duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour aux événements
            </a>
        </div>
    </div>
</div>

<!-- Section CTA final -->
@include('homepage._cta-final')
@endsection
