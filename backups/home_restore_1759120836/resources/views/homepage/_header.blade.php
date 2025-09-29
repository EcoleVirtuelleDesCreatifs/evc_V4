<!-- Header -->
<header id="main-header" class="bg-gradient-to-b fixed top-0 left-0 w-full z-50 transition-all duration-300">
    <nav class="mx-auto flex max-w-7xl items-center justify-between p-4 lg:px-8">
        <div class="flex lg:flex-1">
            <a href="#">
                <img class="h-20 lg:h-24 w-auto transition-all duration-300" src="{{ asset('assets/img/logo.png') }}" alt="EVC Logo">
            </a>
        </div>
        <div class="flex lg:hidden">
            <button type="button" id="mobile-menu-open-button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-400">
                <span class="sr-only">Ouvrir le menu principal</span>
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
        <div class="hidden lg:flex lg:gap-x-12">
            <a href="{{ route('presentation') }}" class="text-sm font-semibold leading-6 text-gray-300 hover:text-white transition">Présentation</a>
            <a href="{{ route('formations') }}" class="text-sm font-semibold leading-6 text-gray-300 hover:text-white transition">Nos Formations</a>
            <a href="{{ route('travaux') }}" class="text-sm font-semibold leading-6 text-gray-300 hover:text-white transition">Travaux Étudiants</a>
            <a href="{{ route('laureats') }}" class="text-sm font-semibold leading-6 text-gray-300 hover:text-white transition">Nos Lauréats</a>
        </div>
        <div class="hidden lg:flex lg:flex-1 lg:justify-end lg:items-center gap-x-6 lg:ml-8">

            <a href="#" id="open-form-modal" class="whitespace-nowrap inline-flex items-center px-4 py-2 rounded-full text-white font-semibold bg-gradient-to-r from-orange-500 to-amber-400 hover:from-orange-400 hover:to-amber-300 shadow transition">Préinscription</a>
            <a href="{{ route('login') }}" class="whitespace-nowrap inline-flex items-center rounded-full p-[1px] bg-gradient-to-r from-orange-500 to-amber-400 hover:from-orange-400 hover:to-amber-300 transition">
                <span class="inline-flex items-center px-4 py-2 rounded-full bg-transparent text-white font-semibold">Espace Étudiant</span>
            </a>
        </div>
    </nav>
</header>

<!-- Mobile menu -->
<div id="mobile-menu" class="lg:hidden hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 z-[9998] bg-black/30" aria-hidden="true"></div>
    <div class="fixed inset-y-0 right-0 z-[9999] w-full overflow-y-auto bg-gradient-to-b from-[#000033] to-[#000066] px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-white/10">
        <div class="flex items-center justify-between">
            <a href="#" class="-m-1.5 p-1.5">
                <img class="h-16 w-auto" src="{{ asset('assets/img/logo.png') }}" alt="EVC Logo">
            </a>
            <button type="button" id="mobile-menu-close-button" class="-m-2.5 rounded-md p-2.5 text-gray-400">
                <span class="sr-only">Fermer le menu</span>
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <div class="mt-6 flow-root">
            <div class="-my-6 divide-y divide-gray-500/25">
                <div class="space-y-2 py-6">
                    <a href="#presentation" class="-mx-3 block rounded-lg py-2 px-3 text-base font-semibold leading-7 text-white hover:bg-gray-800">Présentation</a>
                    <a href="#formations" class="-mx-3 block rounded-lg py-2 px-3 text-base font-semibold leading-7 text-white hover:bg-gray-800">Nos Formations</a>
                    <a href="#creations" class="-mx-3 block rounded-lg py-2 px-3 text-base font-semibold leading-7 text-white hover:bg-gray-800">Travaux Étudiants</a>
                    <a href="#laureats" class="-mx-3 block rounded-lg py-2 px-3 text-base font-semibold leading-7 text-white hover:bg-gray-800">Nos Lauréats</a>
                    <a href="{{ route('webtv') }}" class="-mx-3 block rounded-lg py-2 px-3 text-base font-semibold leading-7 text-white hover:bg-gray-800">WebTV</a>
                    <a href="#" class="-mx-3 block rounded-lg py-2 px-3 text-base font-semibold leading-7 text-white hover:bg-gray-800 open-form-modal">Préinscription</a>
                </div>
                <div class="py-6">
                    <a href="{{ route('login') }}" class="-mx-3 block rounded-lg py-2.5 px-3 text-base font-semibold leading-6 text-white hover:bg-gray-800">Espace Étudiant</a>
                </div>
            </div>
        </div>
    </div>
</div>
