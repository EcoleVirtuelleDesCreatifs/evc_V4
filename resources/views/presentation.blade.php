@extends('layouts.app')

@section('title', 'À Propos - EVC - École Virtuelle des Créatifs')
@section('description', 'Découvrez notre mission, nos valeurs et notre engagement à former les leaders du digital de demain en Afrique.')

@section('content')
    {{-- Main Header --}}
    <div class="bg-gray-900 py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:text-center">
                <h2 class="text-base font-semibold leading-7 text-orange-500">Qui sommes-nous ?</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">1ère École Virtuelle Ultra-Pratique</p>
                <p class="mt-6 text-lg leading-8 text-gray-300">
                    Bienvenue à l’École Virtuelle des Créatifs (EVC), une institution innovante dédiée à la formation professionnelle et pratique dans le domaine du digital et de l'informatique. Fondée sous la forme d'une SARL, EVC a pour mission de former les professionnels de demain en infographie, marketing digital, community management, et en informatique de gestion.
                </p>
            </div>
        </div>
    </div>

    {{-- Key Advantages --}}
    <div class="py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:grid-cols-2 lg:mx-0 lg:max-w-none lg:grid-cols-4">
                @php
                    $advantages = [
                        ['name' => 'Formations Ultra-Pratiques', 'description' => 'Nos formations sont axées sur des projets d\'entreprises réels et des cas concrets.', 'icon' => 'fa-briefcase'],
                        ['name' => 'Assistance & Coaching', 'description' => 'Nos formations sont accompagnées par un coaching et une assistance hors pair 24h/24.', 'icon' => 'fa-headset'],
                        ['name' => 'Formations Certifiées', 'description' => 'EVC est légalement reconnue par l\'État ivoirien, garantissant la certification officielle de nos formations.', 'icon' => 'fa-certificate'],
                        ['name' => 'Formation Flexible', 'description' => 'Nos formations sont offertes en ligne et en présentiel selon des programmes mensuels.', 'icon' => 'fa-clock']
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

    {{-- Mission --}}
    <div class="bg-gray-900 py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <h2 class="text-base font-semibold leading-7 text-orange-500">Notre Mission</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">Former les Leaders du Digital de Demain</p>
                <p class="mt-6 text-lg leading-8 text-gray-300">
                    À EVC, notre mission est claire : former des professionnels compétents et opérationnels. Nous vous dotons des compétences pratiques et techniques qui répondent aux exigences du marché pour relever les défis du monde numérique.
                </p>
            </div>
        </div>
    </div>

    {{-- Values --}}
    <div class="py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:text-center">
                <h2 class="text-base font-semibold leading-7 text-orange-500">Nos Valeurs</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">L'Excellence, l'Innovation et la Pratique</p>
            </div>
            <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-4xl">
                <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-2 lg:gap-y-16">
                    @php
                        $values = [
                            ['name' => 'Excellence', 'description' => 'Nous offrons des formations de haute qualité, en adéquation avec les standards du marché pour que chaque étudiant se distingue.', 'icon' => 'fa-star'],
                            ['name' => 'Innovation', 'description' => 'Nous intégrons les dernières technologies et tendances pour offrir un enseignement à la pointe du digital.', 'icon' => 'fa-lightbulb'],
                            ['name' => 'Théorie & Pratique', 'description' => 'Nos formations sont axées sur des projets réels pour acquérir des compétences immédiatement applicables.', 'icon' => 'fa-code'],
                            ['name' => 'Accompagnement Personnalisé', 'description' => 'Nous assurons un suivi individualisé pour garantir une expérience d’apprentissage enrichissante et réussie.', 'icon' => 'fa-user-friends']
                        ];
                    @endphp
                    @foreach ($values as $value)
                    <div class="relative pl-16">
                        <dt class="text-base font-semibold leading-7 text-white">
                            <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-orange-500">
                                <i class="fas {{ $value['icon'] }} text-white"></i>
                            </div>
                            {{ $value['name'] }}
                        </dt>
                        <dd class="mt-2 text-base leading-7 text-gray-400">{{ $value['description'] }}</dd>
                    </div>
                    @endforeach
                </dl>
            </div>
        </div>
    </div>
@endsection
