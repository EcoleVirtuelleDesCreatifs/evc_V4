<!-- Section Flash Info - Design Professionnel -->
<section class="flash-pro relative w-full max-w-7xl mx-auto z-30 my-6 px-4">
    @php
        $items = \App\Models\Communique::active()->with(['actualite:id,slug','evenement:id,slug'])->orderBy('order')->orderBy('created_at','desc')->get();
        $flash = [];
        foreach ($items as $c) {
            $url = null;
            if (!empty($c->actualite) && !empty($c->actualite->slug)) $url = route('actualite.show', $c->actualite->slug);
            elseif (!empty($c->evenement) && !empty($c->evenement->slug)) $url = route('evenement.show', $c->evenement->slug);
            $flash[] = ['content'=>$c->content, 'url'=>$url, 'date'=>$c->created_at?$c->created_at->format('d/m/Y'):null];
        }
        if (empty($flash)) $flash[] = ['content'=>'Bienvenue sur votre espace ! Découvrez vos projets.','url'=>null,'date'=>null];
    @endphp

    <div class="flash-card bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
        <div class="flex flex-col lg:flex-row">
            <!-- Header -->
            <div class="lg:w-72 bg-gradient-to-br from-gray-900 to-gray-800 p-6 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-500 flex items-center justify-center">
                    <i class="fas fa-bolt text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-white font-bold text-xl">Flash Info</h2>
                    @if(count($flash)>1)
                        <span class="text-gray-400 text-sm">{{ count($flash) }} actualités</span>
                    @endif
                </div>
            </div>

            <!-- Content -->
            <div class="flex-1 p-6">
                <div id="flash-carousel">
                    @foreach($flash as $i=>$f)
                        <div class="flash-item {{ $i===0?'':'hidden' }}" data-index="{{ $i }}">
                            @if($f['date'])
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                                        {{ $f['date'] }}
                                    </span>
                                    <span class="px-2 py-1 bg-red-500 text-white rounded-full text-xs font-bold">
                                        NEW
                                    </span>
                                </div>
                            @endif
                            <p class="text-gray-800 text-lg font-medium mb-4">{!! $f['content'] !!}</p>
                            <div class="flex items-center justify-between">
                                @if($f['url'])
                                    <a href="{{ $f['url'] }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">
                                        Lire <i class="fas fa-arrow-right text-sm"></i>
                                    </a>
                                @endif
                                @if(count($flash)>1)
                                    <span class="text-gray-400 text-sm">{{ $i+1 }}/{{ count($flash) }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if(count($flash)>1)
                    <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-100">
                        <div class="flex gap-2">
                            @foreach($flash as $i=>$f)
                                <button class="flash-dot w-2.5 h-2.5 rounded-full {{ $i===0?'bg-gray-900 w-6':'bg-gray-300' }}" data-index="{{ $i }}" onclick="goToSlide({{ $i }})"></button>
                            @endforeach
                        </div>
                        <div class="flex gap-2">
                            <button onclick="prevSlide()" class="w-10 h-10 rounded-full border border-gray-300 hover:bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-chevron-left text-gray-600"></i>
                            </button>
                            <button onclick="nextSlide()" class="w-10 h-10 rounded-full bg-gray-900 text-white hover:bg-gray-800 flex items-center justify-center">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<script>
(function(){
    const items=document.querySelectorAll('.flash-item'), dots=document.querySelectorAll('.flash-dot');
    let idx=0, interval;
    if(items.length<=1)return;
    function show(i){
        items.forEach((el,j)=>{el.classList.toggle('hidden',j!==i);});
        dots.forEach((d,j)=>{d.className=j===i?'flash-dot w-2.5 h-2.5 rounded-full bg-gray-900 w-6':'flash-dot w-2.5 h-2.5 rounded-full bg-gray-300';});
        idx=i;
    }
    window.nextSlide=function(){show((idx+1)%items.length); reset();};
    window.prevSlide=function(){show((idx-1+items.length)%items.length); reset();};
    window.goToSlide=function(i){show(i); reset();};
    function reset(){clearInterval(interval); interval=setInterval(()=>show((idx+1)%items.length),7000);}
    reset();
})();
</script>
