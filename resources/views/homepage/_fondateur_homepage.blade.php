<!-- Homepage Founder Section -->
<div id="fondateur-section" class="bg-gray-100 py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto grid max-w-5xl grid-cols-1 items-center gap-x-0 gap-y-16 lg:grid-cols-2">
            <!-- Left Column: Image - visible uniquement sur desktop -->
            <div class="hidden lg:block">
                <img class="w-full max-w-sm mx-auto rounded-lg shadow-lg" src="{{ asset('assets/img/founder/Bile_Bossombra.jpg') }}" alt="Photo de Bilé Bossombra, Fondateur & Formateur Principal">
            </div>
            
            <!-- Right Column: Text -->
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Bilé Bossombra</h2>
                <p class="mt-2 text-xl font-semibold text-gray-700">Fondateur & Formateur Principal</p>
                <div class="mt-4 h-1 w-16 bg-orange-500"></div>
                
                <!-- Image mobile - visible uniquement sur mobile, positionnée après la barre orange -->
                <div class="lg:hidden mt-6">
                    <img class="w-full max-w-sm mx-auto rounded-lg shadow-lg" src="{{ asset('assets/img/founder/Bile_Bossombra.jpg') }}" alt="Photo de Bilé Bossombra, Fondateur & Formateur Principal">
                </div>
                
                <p class="mt-6 text-gray-600">
                    Bilé Bossombra, connu sous le nom de « l'Homme à la cravate rouge », est un expert du digital et de la transformation numérique fort de plus de dix ans d'expérience. Visionnaire et stratège, il aide entreprises, marques et personnalités influentes à bâtir une présence en ligne puissante, à accroître leur visibilité et à transformer leur image en succès tangible, en Ivoirien en tant une SARL comme à l'international.
                </p>
                 <div class="mt-8">
                    <a href="{{ route('parcours-formateur') }}" class="text-base font-semibold leading-7 text-orange-500 hover:text-orange-400 transition-colors duration-300">
                        Voir le profil complet <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
