<!-- Section Flash Info - Design Graphique Espace Étudiant -->
<section class="flash-info-modern relative w-full max-w-7xl mx-auto z-30 my-6">
    @php
        $communiques = \App\Models\Communique::active()
            ->with(['actualite:id,slug', 'evenement:id,slug'])
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $flashItems = [];
        foreach ($communiques as $c) {
            $url = null; 
            $label = null;
            if (!empty($c->actualite) && !empty($c->actualite->slug)) { 
                $url = route('actualite.show', $c->actualite->slug); 
                $label = 'Lire l\'article';
            } elseif (!empty($c->evenement) && !empty($c->evenement->slug)) { 
                $url = route('evenement.show', $c->evenement->slug); 
                $label = 'Voir l\'événement';
            }
            $flashItems[] = [
                'content' => $c->content, 
                'url' => $url, 
                'label' => $label,
                'date' => $c->created_at ? $c->created_at->format('d/m/Y') : null
            ];
        }
        
        if (empty($flashItems)) {
            $flashItems = [
                [
                    'content' => 'Bienvenue sur votre espace étudiant Design Graphique ! Consultez vos projets et TPs.',
                    'url' => null, 
                    'label' => null,
                    'date' => null
                ],
            ];
        }
    @endphp

    <!-- Card Container avec Glassmorphism -->
    <div class="flash-card relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 rounded-2xl shadow-2xl border border-slate-700/50">
        
        <!-- Background Effects -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Gradient Orbs -->
            <div class="absolute -top-20 -right-20 w-60 h-60 bg-gradient-to-br from-cyan-500/20 to-blue-600/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-60 h-60 bg-gradient-to-tr from-purple-500/20 to-pink-600/20 rounded-full blur-3xl"></div>
            <!-- Grid Pattern -->
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 40px 40px;"></div>
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row items-stretch">
            
            <!-- Header Section - Flash Info Badge -->
            <div class="flash-header lg:w-72 flex-shrink-0 bg-gradient-to-br from-cyan-600 via-cyan-700 to-blue-800 p-6 flex flex-col justify-center items-center lg:items-start relative overflow-hidden">
                <!-- Diagonal Pattern -->
                <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,0.1) 10px, rgba(255,255,255,0.1) 20px);"></div>
                
                <!-- Animated Glow -->
                <div class="absolute inset-0 bg-gradient-to-r from-cyan-400/0 via-white/10 to-cyan-400/0 animate-shimmer-slow"></div>
                
                <div class="relative z-10 text-center lg:text-left">
                    <!-- Icon Badge -->
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-sm border border-white/30 mb-4 shadow-lg">
                        <span class="relative flex h-6 w-6">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-5 w-5 bg-white shadow-lg"></span>
                        </span>
                    </div>
                    
                    <!-- Title -->
                    <h2 class="text-white font-black text-xl tracking-wider uppercase mb-1">
                        Flash Info
                    </h2>
                    <p class="text-cyan-200/80 text-sm font-medium">
                        Restez informé
                    </p>
                    
                    <!-- News Counter -->
                    @if(count($flashItems) > 1)
                        <div class="mt-4 inline-flex items-center gap-2 px-3 py-1.5 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                            <i class="fas fa-newspaper text-cyan-200 text-xs"></i>
                            <span class="text-white text-xs font-semibold">{{ count($flashItems) }} actualités</span>
                        </div>
                    @endif
                </div>

                <!-- Decorative Corner -->
                <div class="absolute bottom-0 right-0 w-20 h-20 bg-gradient-to-tl from-white/10 to-transparent"></div>
            </div>

            <!-- Content Section -->
            <div class="flex-1 p-6 lg:p-8">
                <div id="flash-content-wrapper" class="relative min-h-[120px] flex items-center">
                    
                    @foreach($flashItems as $index => $item)
                        <div class="flash-slide w-full {{ $index === 0 ? 'active' : 'hidden' }}" data-index="{{ $index }}">
                            <div class="flex flex-col lg:flex-row lg:items-center gap-4 lg:gap-6">
                                
                                <!-- Icon Indicator -->
                                <div class="hidden lg:flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-500/20 to-blue-600/20 border border-cyan-500/30 flex-shrink-0">
                                    <i class="fas fa-bolt text-cyan-400 text-lg"></i>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1">
                                    @if($item['date'])
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-cyan-500/10 rounded-full border border-cyan-500/20">
                                                <i class="fas fa-calendar-alt text-cyan-400 text-xs"></i>
                                                <span class="text-cyan-300 text-xs font-medium">{{ $item['date'] }}</span>
                                            </span>
                                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                                            <span class="text-slate-400 text-xs uppercase tracking-wider font-semibold">Nouveau</span>
                                        </div>
                                    @endif
                                    
                                    <p class="text-slate-100 text-base lg:text-lg font-medium leading-relaxed">
                                        {!! $item['content'] !!}
                                    </p>
                                </div>
                                
                                <!-- Action Button -->
                                <div class="flex-shrink-0">
                                    @if(!empty($item['url']))
                                        <a href="{{ $item['url'] }}" 
                                           class="group inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg shadow-cyan-500/25 hover:shadow-cyan-500/40 transform hover:-translate-y-0.5">
                                            <span>{{ $item['label'] ?? 'En savoir plus' }}</span>
                                            <i class="fas fa-arrow-right text-sm transition-transform duration-300 group-hover:translate-x-1"></i>
                                        </a>
                                    @else
                                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-slate-700/50 rounded-lg border border-slate-600/50">
                                            <i class="fas fa-info-circle text-slate-400"></i>
                                            <span class="text-slate-400 text-sm">Information</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Navigation Controls -->
                @if(count($flashItems) > 1)
                    <div class="flex items-center justify-between mt-6 pt-4 border-t border-slate-700/50">
                        <!-- Dots -->
                        <div class="flex items-center gap-2">
                            @foreach($flashItems as $index => $item)
                                <button class="flash-dot w-2.5 h-2.5 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-cyan-400 w-8' : 'bg-slate-600 hover:bg-slate-500' }}" 
                                        data-index="{{ $index }}"
                                        onclick="goToSlide({{ $index }})">
                                </button>
                            @endforeach
                        </div>
                        
                        <!-- Arrows -->
                        <div class="flex items-center gap-2">
                            <button onclick="prevSlide()" 
                                    class="w-10 h-10 rounded-xl bg-slate-700/50 hover:bg-slate-600/50 border border-slate-600/50 text-slate-300 hover:text-white transition-all duration-300 flex items-center justify-center">
                                <i class="fas fa-chevron-left text-sm"></i>
                            </button>
                            <button onclick="nextSlide()" 
                                    class="w-10 h-10 rounded-xl bg-slate-700/50 hover:bg-slate-600/50 border border-slate-600/50 text-slate-300 hover:text-white transition-all duration-300 flex items-center justify-center">
                                <i class="fas fa-chevron-right text-sm"></i>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<script>
(function() {
    const slides = document.querySelectorAll('.flash-slide');
    const dots = document.querySelectorAll('.flash-dot');
    let currentIndex = 0;
    let intervalId = null;
    
    if (slides.length <= 1) return;
    
    function showSlide(index) {
        // Hide all
        slides.forEach((slide, i) => {
            slide.classList.add('hidden');
            slide.classList.remove('active', 'animate-fade-in-up');
        });
        dots.forEach((dot, i) => {
            dot.classList.remove('bg-cyan-400', 'w-8');
            dot.classList.add('bg-slate-600');
        });
        
        // Show current
        slides[index].classList.remove('hidden');
        slides[index].classList.add('active', 'animate-fade-in-up');
        dots[index].classList.remove('bg-slate-600');
        dots[index].classList.add('bg-cyan-400', 'w-8');
        
        currentIndex = index;
    }
    
    window.nextSlide = function() {
        const next = (currentIndex + 1) % slides.length;
        showSlide(next);
        resetInterval();
    };
    
    window.prevSlide = function() {
        const prev = (currentIndex - 1 + slides.length) % slides.length;
        showSlide(prev);
        resetInterval();
    };
    
    window.goToSlide = function(index) {
        showSlide(index);
        resetInterval();
    };
    
    function resetInterval() {
        if (intervalId) clearInterval(intervalId);
        intervalId = setInterval(() => {
            const next = (currentIndex + 1) % slides.length;
            showSlide(next);
        }, 6000);
    }
    
    // Auto-advance
    resetInterval();
})();
</script>

<style>
/* Animations */
@keyframes shimmer-slow {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}
.animate-shimmer-slow {
    animation: shimmer-slow 4s infinite;
}

@keyframes fade-in-up {
    0% {
        opacity: 0;
        transform: translateY(15px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fade-in-up {
    animation: fade-in-up 0.5s ease-out forwards;
}

/* Hover Effects */
.flash-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.flash-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 25px 50px -12px rgba(6, 182, 212, 0.15);
}

/* Slide Transition */
.flash-slide {
    transition: opacity 0.5s ease, transform 0.5s ease;
}
.flash-slide.hidden {
    display: none;
}
.flash-slide.active {
    display: block;
}
</style>
