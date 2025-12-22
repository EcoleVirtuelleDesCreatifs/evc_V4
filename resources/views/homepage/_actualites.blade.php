<!-- Section Actualités -->
<div class="bg-black py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl lg:text-center" data-aos="fade-up">
            <h2 class="text-base font-semibold leading-7 evc-orange">Blog</h2>
            <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">Nos Dernières Actualités</p>
            <div class="mt-6">
                <a href="{{ route('actualites') }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-full hover:from-orange-600 hover:to-orange-700 transform hover:scale-105 transition duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    Voir toutes les actualités
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            </div>
        </div>
        <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-8 sm:grid-cols-2 lg:mx-0 lg:max-w-none lg:grid-cols-3">
            @forelse($actualites as $index => $actualite)
            <!-- Article {{ $index + 1 }} -->
            <article class="flex flex-col items-start justify-between rounded-2xl bg-gray-800/80 p-8 ring-1 ring-white/10 hover:ring-orange-500/50 transition duration-300" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                <div class="relative w-full">
                    <a href="{{ route('actualite.show', $actualite->slug) }}" class="block">
                        <img src="{{ $actualite->cover_image ? $actualite->cover_image_url : 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=800&q=80' }}"
                             alt="{{ $actualite->cover_image_alt ?? $actualite->title }}"
                             class="aspect-[16/9] w-full rounded-2xl bg-gray-100 object-cover sm:aspect-[2/1] lg:aspect-[3/2] hover:opacity-90 transition-opacity duration-300">
                    </a>
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
            @empty
            <!-- Message si aucune actualité -->
            <div class="col-span-full text-center py-12">
                <div class="mx-auto max-w-md">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-white">Aucune actualité disponible</h3>
                    <p class="mt-2 text-sm text-gray-400">Les dernières actualités seront bientôt publiées. Restez connecté !</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
