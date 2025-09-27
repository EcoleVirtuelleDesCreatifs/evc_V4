<!-- Section Étudiants Internationaux -->
<div class="bg-black py-20 sm:py-24 relative overflow-hidden" id="international-students-section">
    <!-- Animated Orange Spotlight -->
    <div class="orange-spotlight"></div>

    <!-- Content -->
    <div class="relative z-10 mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-3xl text-center">
            <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Une École à Rayonnement International</h2>
            <p class="mt-4 text-lg leading-8 text-gray-300">Depuis sa création, l'école a attiré des étudiants de divers horizons, témoignant de son attractivité et de la qualité de ses programmes.</p>
        </div>
        <div class="mt-16 flex justify-center">
            <div class="flex flex-wrap justify-center gap-4 sm:gap-6">
                @foreach ([
                    ['code' => 'cd', 'name' => 'R.D. Congo'],
                    ['code' => 'fr', 'name' => 'France'],
                    ['code' => 'be', 'name' => 'Belgique'],
                    ['code' => 'bj', 'name' => 'Bénin'],
                    ['code' => 'ma', 'name' => 'Maroc'],
                    ['code' => 'ae', 'name' => 'Émirats Arabes Unis'],
                    ['code' => 'tg', 'name' => 'Togo'],
                    ['code' => 'sn', 'name' => 'Sénégal'],
                    ['code' => 'ci', 'name' => 'Côte d’Ivoire'],
                    ['code' => 'gh', 'name' => 'Ghana'],
                    ['code' => 'ml', 'name' => 'Mali'],
                    ['code' => 'bf', 'name' => 'Burkina Faso'],
                    ['code' => 'td', 'name' => 'Tchad'],
                    ['code' => 'gn', 'name' => 'Guinée'],
                    ['code' => 'in', 'name' => 'Inde'],
                    ['code' => 'ga', 'name' => 'Gabon'],
                ] as $country)
                    <div class="relative group" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                        <img class="h-10 w-16 sm:h-12 sm:w-20 object-cover rounded-md shadow-lg transition-transform duration-300 group-hover:scale-110" src="https://flagcdn.com/w160/{{ $country['code'] }}.png" alt="Drapeau {{ $country['name'] }}">
                        <div class="absolute bottom-full mb-2 w-max left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                            {{ $country['name'] }}
                            <div class="absolute top-full left-1/2 -translate-x-1/2 w-2 h-2 bg-gray-800 rotate-45"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
