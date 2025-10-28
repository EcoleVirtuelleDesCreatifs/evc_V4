@extends('layouts.app')

@section('title', 'Nos Lauréats - EVC - École Virtuelle des Créatifs')
@section('description', 'Faites la connaissance de nos anciens étudiants qui transforment aujourd\'hui leur passion en carrière dans le monde du digital.')

@section('content')
<!-- Hero Section -->
<div class="relative pt-32 sm:pt-40 lg:pt-48 pb-16 bg-gradient-to-br from-[#0a1128] via-[#001f54] to-[#034078]">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-4xl text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 leading-tight" data-aos="fade-up">
                Nos Lauréats
            </h1>
            <p class="text-xl text-gray-300" data-aos="fade-up" data-aos-delay="100">
                Ils transforment leur passion en carrière dans le digital
            </p>
        </div>
    </div>
</div>

<!-- Lauréats par Édition -->
<div class="bg-gradient-to-b from-[#034078] to-[#001233] py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        
        @php
            $editions = [
                [
                    'numero' => 4,
                    'annee' => '2024',
                    'laureats' => [
                        ['img' => 'assets/images/testimonials/testimonials-13.jpg', 'name' => 'Kamanri Serge', 'title' => 'Motion Designer @ Video Pro', 'country' => 'Tchad', 'flag' => '🇹🇩'],
                        ['img' => 'assets/images/testimonials/testimonials-14.jpg', 'name' => 'Fatoumata Diarra', 'title' => 'Social Media Manager @ Digital Agency', 'country' => 'Mali', 'flag' => '🇲🇱'],
                        ['img' => 'assets/images/testimonials/testimonials-15.jpg', 'name' => 'Claudine Ngoa', 'title' => 'Community Manager @ Brand Studio', 'country' => 'Cameroun', 'flag' => '🇨🇲'],
                        ['img' => 'assets/images/testimonials/testimonials-16.jpg', 'name' => 'Kevin N’Guessan', 'title' => 'Vidéaste & Monteur @ Freelance', 'country' => 'Côte d’Ivoire', 'flag' => '🇨🇮'],
                    ]
                ],
                [
                    'numero' => 3,
                    'annee' => '2023',
                    'laureats' => [
                        ['img' => 'assets/images/testimonials/testimonials-9.jpg', 'name' => 'Tog-Yenouba Ngarleita', 'title' => 'Web Designer @ Digital Creators', 'country' => 'Tchad', 'flag' => '🇹🇩'],
                        ['img' => 'assets/images/testimonials/testimonials-10.jpg', 'name' => 'Dely Ahileu', 'title' => 'Graphic Designer @ Visual Arts', 'country' => 'Côte d’Ivoire', 'flag' => '🇨🇮'],
                        ['img' => 'assets/images/testimonials/testimonials-11.jpg', 'name' => 'Gossé Eric', 'title' => 'Digital Artist @ Creative Space', 'country' => 'Côte d’Ivoire', 'flag' => '🇨🇮'],
                        ['img' => 'assets/images/testimonials/testimonials-12.jpg', 'name' => 'Yao Marcel', 'title' => 'Community Manager Freelance', 'country' => 'Côte d’Ivoire', 'flag' => '🇨🇮'],
                    ]
                ],
                [
                    'numero' => 2,
                    'annee' => '2022',
                    'laureats' => [
                        ['img' => 'assets/images/testimonials/testimonials-5.jpg', 'name' => 'Yocbé Stella', 'title' => 'Content Creator @ Media Studio', 'country' => 'Belgique', 'flag' => '🇧🇪'],
                        ['img' => 'assets/images/testimonials/testimonials-6.jpg', 'name' => 'Adama Guèye', 'title' => 'Community Manager @ Tech Hub', 'country' => 'Sénégal', 'flag' => '🇸🇳'],
                        ['img' => 'assets/images/testimonials/testimonials-7.jpg', 'name' => 'Coulibaly Bakary', 'title' => 'Marketing Digital @ Start-up CI', 'country' => 'Côte d’Ivoire', 'flag' => '🇨🇮'],
                        ['img' => 'assets/images/testimonials/testimonials-8.jpg', 'name' => 'Soumahoro Hadja', 'title' => 'Social Media Strategist @ Agency Plus', 'country' => 'Côte d’Ivoire', 'flag' => '🇨🇮'],
                    ]
                ],
                [
                    'numero' => 1,
                    'annee' => '2021',
                    'laureats' => [
                        ['img' => 'assets/images/testimonials/testimonials-1.jpg', 'name' => 'Lombi Moïse', 'title' => 'Graphiste Senior @ Creative Corp', 'country' => 'RDC', 'flag' => '🇨🇩'],
                        ['img' => 'assets/images/testimonials/testimonials-2.jpg', 'name' => 'Eddy Marc', 'title' => 'Social Media Manager @ Digital Wave', 'country' => 'France', 'flag' => '🇫🇷'],
                        ['img' => 'assets/images/testimonials/testimonials-3.jpg', 'name' => 'Adingra Eve', 'title' => 'UX/UI Designer @ Tech Solutions', 'country' => 'Ghana', 'flag' => '🇬🇭'],
                        ['img' => 'assets/images/testimonials/testimonials-4.jpg', 'name' => 'Alimasi Abdoullah', 'title' => 'Brand Designer @ Innovation Lab', 'country' => 'Guinée', 'flag' => '🇬🇳'],
                    ]
                ],
            ];
        @endphp

        @foreach($editions as $edition)
        <!-- Édition {{ $edition['numero'] }} -->
        <div class="mb-20" data-aos="fade-up">
            <!-- Header de l'édition -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full mb-4">
                    <span class="text-2xl font-bold text-white">Édition {{ $edition['numero'] }}</span>
                    <span class="text-lg text-white/80">{{ $edition['annee'] }}</span>
                </div>
            </div>
            
            <!-- Grille des lauréats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($edition['laureats'] as $index => $laureat)
                <div class="group" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="bg-white/5 backdrop-blur-lg rounded-2xl p-6 border border-white/10 h-full flex flex-col text-center transition-all duration-300 hover:bg-white/10 hover:border-orange-500/50 hover:transform hover:scale-105">
                        <img class="aspect-square w-full rounded-full object-cover mx-auto shadow-lg mb-6" src="{{ asset($laureat['img']) }}" alt="{{ $laureat['name'] }}">
                        <h3 class="text-lg font-semibold text-white mb-2">{{ $laureat['name'] }}</h3>
                        <p class="text-xs text-gray-300 mb-3">
                            <span class="text-lg">{{ $laureat['flag'] }}</span> {{ $laureat['country'] }}
                        </p>
                        <p class="text-sm text-gray-400 flex-grow">{{ $laureat['title'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
        
    </div>
</div>

<!-- CTA Section -->
@include('homepage._cta-final')
@endsection
