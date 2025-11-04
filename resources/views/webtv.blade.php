@extends('layouts.app')

@section('title', 'WebTV EVC | Tutoriels Design Graphique & Marketing Digital | Vidéos Formation')
@section('description', 'Accédez gratuitement à notre WebTV : tutoriels design graphique, masterclass marketing digital, conférences tech et interviews d\'experts. Apprenez le design et le digital en vidéo avec l\'EVC Abidjan.')
@section('keywords', 'WebTV EVC, tutoriels design graphique, vidéos formation, masterclass digital, conférences tech, tutoriels Photoshop, formation vidéo gratuite, cours en ligne Abidjan, chaîne YouTube EVC')

@section('content')
<div class="py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl lg:text-center" data-aos="fade-up">
            <h2 class="text-base font-semibold leading-7 evc-orange">WebTV</h2>
            <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">Conférences, Tutoriels & Replays</p>
            <p class="mt-6 text-lg leading-8 text-gray-300">
                Plongez au cœur de l'innovation avec notre sélection de contenus vidéo. Apprenez des meilleurs, découvrez les dernières tendances et inspirez-vous.
            </p>
        </div>

        <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2">
            @foreach ($videos as $video)
                <article class="flex flex-col items-start justify-between" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="relative w-full">
                        <div class="aspect-w-16 aspect-h-9">
                            <iframe class="w-full h-full rounded-2xl shadow-lg" src="https://www.youtube.com/embed/{{ $video['id'] }}" title="{{ $video['title'] }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="max-w-xl">
                        <div class="group relative">
                            <h3 class="mt-6 text-lg font-semibold leading-6 text-white group-hover:text-gray-300">
                                {{ $video['title'] }}
                            </h3>
                            <p class="mt-4 line-clamp-3 text-sm leading-6 text-gray-400">{{ $video['description'] }}</p>
                        </div>
                        <div class="relative mt-4 flex items-center gap-x-4">
                            <div class="text-sm leading-6">
                                <p class="font-semibold text-gray-200">
                                    Par <span class="text-orange-500">{{ $video['speaker'] }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</div>
@endsection
