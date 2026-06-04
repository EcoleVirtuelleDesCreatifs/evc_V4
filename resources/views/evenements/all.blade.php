@extends('layouts.app')

@section('title', 'Tous les Événements - École Virtuelle des Créatifs')
@section('description', 'Découvrez tous nos événements : conférences, ateliers, rencontres professionnelles et bien plus encore.')

@section('content')

    <!-- Header -->
    <div class="bg-gradient-to-r from-[#0a1128] via-[#001f54] to-[#034078] pt-[200px] pb-24 sm:pb-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center" data-aos="fade-up">
                <h2 class="text-base font-semibold leading-7 evc-orange">Communauté</h2>
                <p class="mt-2 text-4xl font-bold tracking-tight text-white sm:text-5xl">Tous nos Événements</p>
                <p class="mt-6 text-lg leading-8 text-gray-300">
                    Participez à nos événements pour développer vos compétences, élargir votre réseau et rester à la pointe du digital.
                </p>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-b from-[#034078] to-[#001233] py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">

        <!-- Événements à venir -->
        <section class="mb-20">
            <div class="flex items-center gap-3 mb-8" data-aos="fade-right">
                <div class="h-12 w-12 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-white">Événements à venir</h2>
                    <p class="text-sm text-gray-300">{{ $evenementsAvenir->count() }} événement(s) planifié(s)</p>
                </div>
            </div>

            @if($evenementsAvenir->count() > 0)
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($evenementsAvenir as $index => $evenement)
                        <article class="event-card flex flex-col items-start justify-between rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-200 hover:ring-orange-500/50"
                                 data-aos="fade-up"
                                 data-aos-delay="{{ ($index + 1) * 100 }}">
                            <div class="relative w-full">
                                <a href="{{ route('evenement.show', $evenement->slug) }}" class="block">
                                    <img src="{{ $evenement->cover_image ? $evenement->cover_image_url : 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?auto=format&fit=crop&w=800&q=80' }}"
                                         alt="{{ $evenement->cover_image_alt ?? $evenement->title }}"
                                         class="aspect-[16/9] w-full rounded-xl bg-gray-100 object-cover hover:opacity-90 transition-opacity duration-300">
                                </a>
                            </div>

                            <div class="w-full mt-6">
                                <div class="flex items-center gap-x-4 text-xs">
                                    <time datetime="{{ $evenement->event_date->format('Y-m-d') }}" class="text-gray-600 font-medium">
                                        <i class="far fa-clock mr-1"></i>
                                        {{ $evenement->event_date->format('d') }} {{ $evenement->event_date->locale('fr')->translatedFormat('F Y') }}
                                    </time>

                                    @php
                                        $eventTypeColors = [
                                            'physical' => 'orange',
                                            'online' => 'blue',
                                            'hybrid' => 'green'
                                        ];
                                        $color = $eventTypeColors[$evenement->event_type] ?? 'gray';

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

                                    <span class="rounded-full bg-{{ $color }}-100 px-3 py-1 text-{{ $color }}-600 font-medium">
                                        {{ $displayText }}
                                    </span>
                                </div>

                                <div class="mt-4">
                                    <h3 class="text-xl font-semibold text-gray-900 hover:text-orange-600 transition-colors">
                                        <a href="{{ route('evenement.show', $evenement->slug) }}">
                                            {{ $evenement->title }}
                                        </a>
                                    </h3>
                                    <p class="mt-3 text-sm text-gray-600 line-clamp-3">{{ $evenement->excerpt }}</p>
                                </div>

                                <div class="mt-6 flex items-center justify-between gap-4">
                                    <a href="{{ route('evenement.show', $evenement->slug) }}"
                                       class="text-sm font-semibold text-orange-600 hover:text-orange-700 transition-colors">
                                        En savoir plus <span aria-hidden="true">→</span>
                                    </a>

                                    @if($evenement->registration_link)
                                        <a href="{{ $evenement->registration_link }}"
                                           target="_blank"
                                           class="inline-flex items-center gap-1 px-4 py-2 text-xs font-semibold text-white bg-gradient-to-r from-orange-500 to-orange-600 rounded-full hover:from-orange-600 hover:to-orange-700 transition-all">
                                            <i class="fas fa-ticket-alt"></i>
                                            S'inscrire
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-white rounded-2xl shadow-sm">
                    <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Aucun événement à venir</h3>
                    <p class="text-gray-600">Les prochains événements seront bientôt annoncés.</p>
                </div>
            @endif
        </section>

        <!-- Événements passés -->
        <section>
            <div class="flex items-center gap-3 mb-8" data-aos="fade-right">
                <div class="h-12 w-12 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-history text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-white">Événements passés</h2>
                    <p class="text-sm text-gray-300">{{ $evenementsPasses->count() }} événement(s) réalisé(s)</p>
                </div>
            </div>

            @if($evenementsPasses->count() > 0)
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($evenementsPasses as $index => $evenement)
                        <article class="event-card flex flex-col items-start justify-between rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-200 hover:ring-gray-400"
                                 data-aos="fade-up"
                                 data-aos-delay="{{ ($index + 1) * 100 }}">
                            <div class="relative w-full">
                                <a href="{{ route('evenement.show', $evenement->slug) }}" class="block">
                                    <div class="relative">
                                        <img src="{{ $evenement->cover_image ? $evenement->cover_image_url : 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?auto=format&fit=crop&w=800&q=80' }}"
                                             alt="{{ $evenement->cover_image_alt ?? $evenement->title }}"
                                             class="aspect-[16/9] w-full rounded-xl bg-gray-100 object-cover hover:opacity-90 transition-opacity duration-300 grayscale opacity-75">
                                        <div class="absolute top-2 right-2 bg-gray-800/80 px-3 py-1 rounded-full">
                                            <span class="text-xs text-white font-medium">
                                                <i class="fas fa-check-circle mr-1"></i>Terminé
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="w-full mt-6">
                                <div class="flex items-center gap-x-4 text-xs">
                                    <time datetime="{{ $evenement->event_date->format('Y-m-d') }}" class="text-gray-500 font-medium">
                                        <i class="far fa-clock mr-1"></i>
                                        {{ $evenement->event_date->format('d') }} {{ $evenement->event_date->locale('fr')->translatedFormat('F Y') }}
                                    </time>

                                    @php
                                        $eventTypeColors = [
                                            'physical' => 'orange',
                                            'online' => 'blue',
                                            'hybrid' => 'green'
                                        ];
                                        $color = $eventTypeColors[$evenement->event_type] ?? 'gray';

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

                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-gray-600 font-medium">
                                        {{ $displayText }}
                                    </span>
                                </div>

                                <div class="mt-4">
                                    <h3 class="text-xl font-semibold text-gray-900 hover:text-gray-700 transition-colors">
                                        <a href="{{ route('evenement.show', $evenement->slug) }}">
                                            {{ $evenement->title }}
                                        </a>
                                    </h3>
                                    <p class="mt-3 text-sm text-gray-600 line-clamp-3">{{ $evenement->excerpt }}</p>
                                </div>

                                <div class="mt-6">
                                    <a href="{{ route('evenement.show', $evenement->slug) }}"
                                       class="text-sm font-semibold text-gray-700 hover:text-gray-900 transition-colors">
                                        Voir les détails <span aria-hidden="true">→</span>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-white rounded-2xl shadow-sm">
                    <i class="fas fa-calendar-check text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Aucun événement passé</h3>
                    <p class="text-gray-600">L'historique des événements apparaîtra ici.</p>
                </div>
            @endif
        </section>

        <!-- Bouton retour -->
        <div class="mt-16 text-center" data-aos="fade-up">
            <a href="{{ url('/') }}"
               class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-white bg-white/10 border-2 border-white/20 rounded-full hover:bg-white hover:text-gray-900 hover:border-white transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Retour à l'accueil</span>
            </a>
        </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .event-card {
        transition: all 0.3s ease;
    }

    .event-card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush
