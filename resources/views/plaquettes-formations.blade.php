@extends('layouts.app')

@section('title', 'Plaquettes de formation')

@section('content')
    <section class="bg-[#0b1226] text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl">
                <h1 class="text-3xl md:text-4xl font-extrabold mb-3">Plaquettes de formation</h1>
                <p class="text-gray-300">
                    Téléchargez nos plaquettes PDF par formation.
                </p>
            </div>

            <div class="mt-10 bg-white/5 border border-white/10 rounded-2xl p-6 md:p-8">
                @if(empty($plaquettes))
                    <div class="text-gray-300">
                        Aucune plaquette disponible pour le moment.
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($plaquettes as $p)
                            <a href="{{ $p['url'] }}" target="_blank" class="group flex items-center justify-between gap-4 rounded-xl border border-white/10 bg-white/5 px-5 py-4 hover:bg-white/10 transition">
                                <div class="min-w-0">
                                    <div class="font-semibold text-white truncate">{{ $p['title'] }}</div>
                                    <div class="text-sm text-gray-300">PDF</div>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if(!empty($p['size_label']))
                                        <span class="text-xs text-gray-300">{{ $p['size_label'] }}</span>
                                    @endif
                                    <span class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-orange-500/15 text-orange-400 group-hover:bg-orange-500/25 transition">
                                        <i class="fas fa-download"></i>
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
