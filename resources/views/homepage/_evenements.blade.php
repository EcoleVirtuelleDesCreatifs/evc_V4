<!-- Section Événements -->
<div style="background-color: #003366;" class="py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl lg:text-center" data-aos="fade-up">
            <h2 class="text-base font-semibold leading-7 evc-orange">Communauté</h2>
            <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">Nos Prochains Événements</p>
        </div>
        <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-8 sm:grid-cols-2 lg:mx-0 lg:max-w-none lg:grid-cols-3">
            @forelse($evenements as $index => $evenement)
            <!-- Event {{ $index + 1 }} -->
            <article class="flex flex-col items-start justify-between rounded-2xl bg-gray-800/80 p-8 ring-1 ring-white/10 hover:ring-orange-500/50 transition duration-300" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                <div class="relative w-full">
                    <a href="{{ route('evenement.show', $evenement->slug) }}" class="block">
                        <img src="{{ $evenement->cover_image ? $evenement->cover_image_url : 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?auto=format&fit=crop&w=800&q=80' }}"
                             alt="{{ $evenement->cover_image_alt ?? $evenement->title }}"
                             class="aspect-[16/9] w-full rounded-2xl bg-gray-100 object-cover sm:aspect-[2/1] lg:aspect-[3/2] hover:opacity-90 transition-opacity duration-300">
                    </a>
                </div>
                <div class="max-w-xl">
                    <div class="mt-8 flex items-center gap-x-4 text-xs">
                        <time datetime="{{ $evenement->event_date->format('Y-m-d') }}" class="text-gray-400">
                            {{ $evenement->event_date->format('d') }} {{ $evenement->event_date->locale('fr')->translatedFormat('F Y') }}
                        </time>
                        @php
                            $eventTypeColors = [
                                'physical' => 'orange',
                                'online' => 'blue',
                                'hybrid' => 'green'
                            ];
                            $color = $eventTypeColors[$evenement->event_type] ?? 'gray';

                            // Pour physical, afficher le lieu; sinon afficher le type
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
                        <span class="relative z-10 rounded-full bg-{{ $color }}-500/10 px-3 py-1.5 font-medium text-{{ $color }}-400">
                            {{ $displayText }}
                        </span>
                    </div>
                    <div class="group relative">
                        <h3 class="mt-3 text-lg font-semibold leading-6 text-white">
                            <a href="{{ route('evenement.show', $evenement->slug) }}">
                                <span class="absolute inset-0"></span>
                                {{ $evenement->title }}
                            </a>
                        </h3>
                        <p class="mt-5 text-sm leading-6 text-gray-300">{{ Str::limit($evenement->excerpt, 150) }}</p>
                        @if($evenement->registration_link)
                        <a href="{{ $evenement->registration_link }}"
                           target="_blank"
                           class="mt-4 inline-flex items-center text-sm font-semibold text-orange-400 hover:text-orange-300 transition-colors duration-200">
                            S'inscrire
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>
            </article>
            @empty
            <!-- Message si aucun événement -->
            <div class="col-span-full text-center py-12">
                <div class="mx-auto max-w-md">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-white">Aucun événement à venir</h3>
                    <p class="mt-2 text-sm text-gray-400">Les prochains événements seront bientôt annoncés. Restez connecté !</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Bouton Voir Tous les Événements -->
        <div class="mt-12 text-center" data-aos="fade-up" data-aos-delay="200">
            <a href="{{ route('evenements.all') }}" class="inline-flex items-center gap-2 px-8 py-4 text-base font-semibold text-white bg-gradient-to-r from-orange-500 to-orange-600 rounded-full shadow-lg hover:from-orange-600 hover:to-orange-700 transform hover:scale-105 transition-all duration-300">
                <span>Voir tous les événements</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </a>
        </div>
    </div>
</div>
