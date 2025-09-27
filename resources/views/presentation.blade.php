@extends('layouts.app')

@section('title', 'À Propos - EVC - École Virtuelle des Créatifs')
@section('description', 'Découvrez notre mission, nos valeurs et notre engagement à former les leaders du digital de demain en Afrique.')

@section('content')
<div class="bg-gradient-to-b from-[#000033] to-[#000066] pt-40 pb-24 sm:pt-48 sm:pb-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        {{-- Header --}}
        <div class="mx-auto max-w-3xl lg:text-center">
            <h2 class="text-base font-semibold leading-7 text-orange-500">Qui sommes-nous ?</h2>
            <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">Façonner l'Avenir du Digital en Afrique</p>
            <p class="mt-6 text-lg leading-8 text-gray-300">
                Bienvenue à l’École Virtuelle des Créatifs (EVC), une institution innovante dédiée à la formation professionnelle et pratique. Notre mission est de former les professionnels de demain en infographie, marketing digital, et informatique de gestion.
            </p>
        </div>

        {{-- Tab Navigation --}}
        <div class="mt-20 border-b border-gray-700">
            <div class="-mb-px flex flex-wrap justify-center gap-x-6 sm:gap-x-8" aria-label="Tabs">
                <button class="tab-button active" data-target="mission">
                    <i class="fas fa-rocket mr-2"></i> Notre Mission
                </button>
                <button class="tab-button" data-target="valeurs">
                    <i class="fas fa-gem mr-2"></i> Nos Valeurs
                </button>
                <button class="tab-button" data-target="atouts">
                    <i class="fas fa-star mr-2"></i> Nos Atouts
                </button>
            </div>
        </div>

        {{-- Tab Content --}}
        <div class="mt-12">
            {{-- Mission Panel --}}
            <div class="tab-panel active" id="mission">
                <div class="mx-auto max-w-3xl text-center">
                    <p class="text-2xl font-bold tracking-tight text-white sm:text-3xl">Former les Leaders du Digital de Demain</p>
                    <p class="mt-6 text-lg leading-8 text-gray-300">
                        À EVC, notre mission est claire : former des professionnels compétents et opérationnels. Nous vous dotons des compétences pratiques et techniques qui répondent aux exigences du marché pour relever les défis du monde numérique.
                    </p>
                </div>
            </div>

            {{-- Valeurs Panel --}}
            <div class="tab-panel" id="valeurs">
                <dl class="mx-auto grid max-w-none grid-cols-1 gap-x-8 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 lg:gap-y-16">
                    @php
                        $values = [
                            ['name' => 'Excellence', 'description' => 'Nous offrons des formations de haute qualité, en adéquation avec les standards du marché.', 'icon' => 'fa-star'],
                            ['name' => 'Innovation', 'description' => 'Nous intégrons les dernières technologies et tendances pour un enseignement à la pointe.', 'icon' => 'fa-lightbulb'],
                            ['name' => 'Pratique', 'description' => 'Nos formations sont axées sur des projets réels pour des compétences immédiatement applicables.', 'icon' => 'fa-code'],
                            ['name' => 'Accompagnement', 'description' => 'Nous assurons un suivi individualisé pour une expérience d’apprentissage réussie.', 'icon' => 'fa-user-friends']
                        ];
                    @endphp
                    @foreach ($values as $value)
                    <div class="relative text-center">
                        <dt class="text-base font-semibold leading-7 text-white">
                            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-orange-500 mx-auto">
                                <i class="fas {{ $value['icon'] }} text-white text-xl"></i>
                            </div>
                            {{ $value['name'] }}
                        </dt>
                        <dd class="mt-2 text-base leading-7 text-gray-400">{{ $value['description'] }}</dd>
                    </div>
                    @endforeach
                </dl>
            </div>

            {{-- Atouts Panel --}}
            <div class="tab-panel" id="atouts">
                <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:grid-cols-2 lg:mx-0 lg:max-w-none lg:grid-cols-4">
                    @php
                        $advantages = [
                            ['name' => 'Ultra-Pratiques', 'description' => 'Nos formations sont axées sur des projets d\'entreprises réels et des cas concrets.', 'icon' => 'fa-briefcase'],
                            ['name' => 'Coaching', 'description' => 'Un coaching et une assistance hors pair 24h/24 avec une équipe professionnelle.', 'icon' => 'fa-headset'],
                            ['name' => 'Certifiées', 'description' => 'EVC est reconnue par l\'État, garantissant la certification officielle de nos formations.', 'icon' => 'fa-certificate'],
                            ['name' => 'Flexible', 'description' => 'Formations en ligne et en présentiel selon des programmes mensuels.', 'icon' => 'fa-clock']
                        ];
                    @endphp
                    @foreach ($advantages as $advantage)
                    <div class="flex flex-col items-start text-center">
                        <div class="rounded-md bg-white/5 p-4 ring-1 ring-white/10 mx-auto">
                            <i class="fas {{ $advantage['icon'] }} h-8 w-8 text-white flex items-center justify-center" aria-hidden="true"></i>
                        </div>
                        <h3 class="mt-6 font-semibold text-white">{{ $advantage['name'] }}</h3>
                        <p class="mt-2 leading-7 text-gray-400">{{ $advantage['description'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
    @include('homepage._fondateur')
    @include('homepage._events')
@endsection
