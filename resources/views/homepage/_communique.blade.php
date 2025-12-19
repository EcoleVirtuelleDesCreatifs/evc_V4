<section class="relative w-full max-w-7xl mx-auto z-30 overflow-hidden bg-gradient-to-r from-[#c2410c] via-[#f97316] to-[#c2410c] shadow-2xl rounded-2xl border border-orange-900/30 my-6">
    <!-- Pattern overlay subtil -->
    <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 24px 24px;"></div>

    <div class="w-full relative flex flex-col sm:flex-row items-stretch h-auto sm:h-14">

        <!-- Badge "FLASH INFO" -->
        <div class="relative sm:absolute left-0 top-0 bottom-0 z-20 flex items-center drop-shadow-md sm:drop-shadow-2xl w-full sm:w-auto h-12 sm:h-full">
            <div class="h-full w-full sm:w-auto bg-white flex items-center justify-center sm:justify-start px-4 sm:px-10 sm:transform sm:-skew-x-12 sm:-ml-6 border-b-4 sm:border-b-0 sm:border-r-4 border-orange-600 shadow-[0_0_20px_rgba(249,115,22,0.6)] relative overflow-hidden group">

                <!-- Background dynamique avec shimmer -->
                <div class="absolute inset-0 bg-gradient-to-r from-white via-orange-50 to-white bg-[length:200%_100%] animate-shimmer"></div>

                <!-- Effet de brillance qui passe -->
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/80 to-transparent -translate-x-full animate-shine"></div>

                <div class="sm:skew-x-12 ml-0 sm:ml-4 flex items-center gap-2 sm:gap-3 relative z-10">
                    <span class="relative flex h-4 w-4 items-center justify-center">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75 duration-1000"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-gradient-to-br from-red-500 to-red-600 shadow-sm"></span>
                    </span>
                    <span class="font-black uppercase tracking-widest text-sm sm:text-sm md:text-base bg-clip-text text-transparent bg-gradient-to-r from-orange-700 via-red-600 to-orange-700 bg-[length:200%_auto] animate-text-flow">
                        FLASH INFO
                    </span>
                </div>
            </div>
        </div>

        <!-- Gradient fade pour l'entrée du texte (Desktop only) -->
        <div class="hidden sm:block absolute left-[80px] sm:left-[120px] z-10 h-full w-24 bg-gradient-to-r from-[#c2410c] to-transparent pointer-events-none"></div>

        <!-- Flash Content Container -->
        <div class="w-full relative ml-0 sm:ml-48 overflow-hidden flex items-center min-h-[100px] sm:min-h-0 sm:h-full py-2 sm:py-0">
            <div id="flash-container" class="w-full h-full relative flex items-center justify-center sm:justify-start px-4 sm:px-0">
                @php
                    $communiques = \App\Models\Communique::active()
                        ->with([
                            'actualite:id,slug',
                            'evenement:id,slug',
                        ])
                        ->orderBy('order')
                        ->orderBy('created_at', 'desc')
                        ->get();
                @endphp

                @if($communiques->count() > 0)
                    @foreach($communiques as $index => $communique)
                        @php
                            $communiqueUrl = null;
                            $communiqueLabel = null;
                            if (!empty($communique->actualite) && !empty($communique->actualite->slug)) {
                                $communiqueUrl = route('actualite.show', $communique->actualite->slug);
                                $communiqueLabel = "Lire";
                            } elseif (!empty($communique->evenement) && !empty($communique->evenement->slug)) {
                                $communiqueUrl = route('evenement.show', $communique->evenement->slug);
                                $communiqueLabel = "Lire";
                            }
                        @endphp
                        @if(!empty($communiqueUrl))
                            <a href="{{ $communiqueUrl }}"
                               class="communique-item absolute inset-0 flex items-center justify-center sm:justify-start transition-all duration-700 ease-in-out pointer-events-none {{ $index === 0 ? 'opacity-100 translate-y-0 pointer-events-auto' : 'opacity-0 translate-y-4' }} rounded-xl px-2 sm:px-0 hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/70"
                               title="{{ $communiqueLabel }}"
                               aria-label="{{ $communiqueLabel }}">
                                <div class="hidden sm:block bg-white/20 p-1.5 rounded-full mr-3 backdrop-blur-sm shrink-0">
                                    <i class="fas fa-bolt text-white text-xs"></i>
                                </div>
                                <div class="flex items-center w-full">
                                    <span class="flex-1 text-base sm:text-base md:text-lg font-bold text-white tracking-wide text-shadow-sm text-center sm:text-left line-clamp-none sm:truncate pr-0 sm:pr-4 hover:underline cursor-pointer" style="pointer-events: auto;">
                                        {!! $communique->content !!}
                                    </span>
                                    <span class="hidden sm:inline-flex items-center gap-2 shrink-0 bg-white/15 hover:bg-white/25 border border-white/25 text-white text-xs font-bold px-3 py-1.5 rounded-full backdrop-blur-sm">
                                        <i class="fas fa-arrow-right"></i>
                                        {{ $communiqueLabel }}
                                    </span>
                                </div>
                            </a>
                        @else
                            <div class="communique-item absolute inset-0 flex items-center justify-center sm:justify-start transition-all duration-700 ease-in-out pointer-events-none {{ $index === 0 ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4' }}">
                                <div class="hidden sm:block bg-white/20 p-1.5 rounded-full mr-3 backdrop-blur-sm shrink-0">
                                    <i class="fas fa-bolt text-white text-xs"></i>
                                </div>
                                <span class="text-base sm:text-base md:text-lg font-bold text-white tracking-wide text-shadow-sm text-center sm:text-left line-clamp-none sm:truncate pr-0 sm:pr-4">
                                    {!! $communique->content !!}
                                </span>
                            </div>
                        @endif
                    @endforeach
                @else
                    <!-- Default Content (Fallback) -->
                    @php
                        $defaults = [
                            ['icon' => 'fire', 'text' => 'Rentrée Académique 2025-2026 : Les inscriptions sont ouvertes ! Places limitées.'],
                            ['icon' => 'star', 'text' => 'Nouveau : Certification en Intelligence Artificielle disponible dès maintenant.'],
                            ['icon' => 'trophy', 'text' => "95% d'insertion professionnelle pour nos diplômés. Rejoignez l'excellence !"]
                        ];
                    @endphp

                    @foreach($defaults as $index => $item)
                        <div class="communique-item absolute inset-0 flex items-center justify-center sm:justify-start transition-all duration-700 ease-in-out {{ $index === 0 ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4' }}">
                            <div class="hidden sm:block bg-white/20 p-1.5 rounded-full mr-3 backdrop-blur-sm shrink-0">
                                <i class="fas fa-{{ $item['icon'] }} text-white text-xs animate-pulse"></i>
                            </div>
                            <span class="text-base sm:text-base md:text-lg font-bold text-white tracking-wide text-shadow-sm text-center sm:text-left line-clamp-none sm:truncate pr-0 sm:pr-4">
                                {{ $item['text'] }}
                            </span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const items = document.querySelectorAll('.communique-item');
        if (items.length > 1) {
            let currentIndex = 0;

            setInterval(() => {
                // Masquer l'élément courant (vers le haut)
                items[currentIndex].classList.remove('opacity-100', 'translate-y-0');
                items[currentIndex].classList.add('opacity-0', '-translate-y-4');
                items[currentIndex].classList.remove('pointer-events-auto');
                items[currentIndex].classList.add('pointer-events-none');

                const prevIndex = currentIndex;
                currentIndex = (currentIndex + 1) % items.length;

                // Préparer le suivant (en bas, invisible) sans transition
                items[currentIndex].style.transition = 'none';
                items[currentIndex].classList.remove('-translate-y-4');
                items[currentIndex].classList.add('translate-y-4');

                // Forcer le reflow
                void items[currentIndex].offsetWidth;

                // Réactiver la transition et afficher le suivant
                items[currentIndex].style.transition = '';
                items[currentIndex].classList.remove('opacity-0', 'translate-y-4');
                items[currentIndex].classList.add('opacity-100', 'translate-y-0');
                items[currentIndex].classList.remove('pointer-events-none');
                items[currentIndex].classList.add('pointer-events-auto');

                // Reset de l'élément précédent après la transition (pour qu'il revienne en position basse prêt à remonter)
                setTimeout(() => {
                    items[prevIndex].classList.remove('-translate-y-4');
                    items[prevIndex].classList.add('translate-y-4');
                }, 700);

            }, 5000); // Change toutes les 5 secondes
        }
    });
</script>

<style>
    @keyframes shimmer {
        0% { background-position: 100% 0; }
        100% { background-position: -100% 0; }
    }
    .animate-shimmer {
        animation: shimmer 3s infinite linear;
    }

    @keyframes shine {
        0% { transform: translateX(-150%) skewX(-12deg); }
        100% { transform: translateX(150%) skewX(-12deg); }
    }
    .animate-shine {
        animation: shine 3s infinite cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes textFlow {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .animate-text-flow {
        animation: textFlow 3s ease infinite;
    }

    .text-shadow-sm {
        text-shadow: 0 2px 4px rgba(194, 65, 12, 0.5);
    }
</style>
