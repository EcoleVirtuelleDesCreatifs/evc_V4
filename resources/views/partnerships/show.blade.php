@extends('layouts.app')

@section('content')
    <div class="bg-black pt-[500px] pb-12 sm:pb-16">
        <div class="mx-auto max-w-5xl px-6 lg:px-8">
            <div class="rounded-3xl border border-white/10 bg-gradient-to-b from-white/5 to-black/40 p-6 sm:p-10">
                <div class="flex flex-col gap-2">
                    <div class="text-sm font-extrabold tracking-wide text-orange-300">{{ $partnership->prefix }}</div>
                    <h1 class="text-2xl sm:text-3xl font-black text-white">{{ $partnership->name }}</h1>
                    @if(!empty($partnership->subtitle))
                        <div class="text-sm sm:text-base font-semibold text-white/70">{{ $partnership->subtitle }}</div>
                    @endif
                </div>

                <div class="mt-8">
                    @php
                        $documentPath = (string) ($partnership->document_path ?? '');
                        $documentUrl = $documentPath !== '' ? url('/storage/app/public/' . ltrim($documentPath, '/')) : '';
                        $lower = strtolower($documentPath);
                        $isPdf = $documentPath !== '' && str_ends_with($lower, '.pdf');
                        $isImage = $documentPath !== '' && (str_ends_with($lower, '.png') || str_ends_with($lower, '.jpg') || str_ends_with($lower, '.jpeg') || str_ends_with($lower, '.webp'));
                    @endphp

                    @if($documentUrl === '')
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-6 text-white/70">
                            Le courrier de partenariat n'est pas encore disponible.
                        </div>
                    @else
                        <div class="rounded-2xl border border-white/10 bg-black/40 overflow-hidden">
                            @if($isPdf)
                                <iframe src="{{ $documentUrl }}" class="w-full" style="height: 80vh;"></iframe>
                            @elseif($isImage)
                                <img src="{{ $documentUrl }}" alt="Courrier de partenariat" class="w-full h-auto">
                            @else
                                <div class="p-6 text-white/70">
                                    <a href="{{ $documentUrl }}" target="_blank" class="inline-flex items-center gap-2 rounded-full bg-orange-500 px-5 py-2 text-sm font-extrabold text-white">
                                        Télécharger le courrier
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4">
                            <a href="{{ $documentUrl }}" target="_blank" class="text-sm font-bold text-orange-300 hover:text-orange-200">
                                Ouvrir dans un nouvel onglet
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
