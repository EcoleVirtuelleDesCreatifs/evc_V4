<!-- Animation CSS pour le bouton Préinscription -->
<style>
@keyframes float-bounce {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-8px);
    }
}

@keyframes glow-pulse {
    0%, 100% {
        box-shadow: 0 8px 25px rgba(255, 152, 0, 0.4),
                    0 4px 15px rgba(255, 152, 0, 0.3),
                    inset 0 -3px 8px rgba(0, 0, 0, 0.2);
    }
    50% {
        box-shadow: 0 12px 35px rgba(255, 152, 0, 0.6),
                    0 6px 20px rgba(255, 152, 0, 0.5),
                    inset 0 -3px 8px rgba(0, 0, 0, 0.2);
    }
}

@keyframes shimmer {
    0% {
        background-position: -200% center;
    }
    100% {
        background-position: 200% center;
    }
}

.preinscription-btn {
    position: relative;
    overflow: hidden;
    border-radius: 50px !important;
    padding: 0.75rem 2rem !important;
    font-weight: 700 !important;
    font-size: 1rem !important;
    background: linear-gradient(135deg, #ff9800 0%, #ff6b00 100%) !important;
    border: 3px solid rgba(255, 255, 255, 0.2) !important;
    box-shadow: 0 8px 25px rgba(255, 152, 0, 0.4),
                0 4px 15px rgba(255, 152, 0, 0.3),
                inset 0 -3px 8px rgba(0, 0, 0, 0.2);
    animation: float-bounce 3s ease-in-out infinite, glow-pulse 2s ease-in-out infinite;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.preinscription-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 255, 255, 0.4),
        transparent
    );
    animation: shimmer 3s infinite;
}

.preinscription-btn::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.preinscription-btn:hover {
    transform: translateY(-4px) scale(1.05) !important;
    box-shadow: 0 15px 40px rgba(255, 152, 0, 0.6),
                0 8px 25px rgba(255, 152, 0, 0.5),
                inset 0 -3px 8px rgba(0, 0, 0, 0.3) !important;
    border-color: rgba(255, 255, 255, 0.4) !important;
}

.preinscription-btn:hover::after {
    width: 300px;
    height: 300px;
}

.preinscription-btn:active {
    transform: translateY(-2px) scale(1.02) !important;
    box-shadow: 0 8px 20px rgba(255, 152, 0, 0.5),
                0 4px 12px rgba(255, 152, 0, 0.4),
                inset 0 -2px 6px rgba(0, 0, 0, 0.3) !important;
}
</style>

<!-- Header -->
<header id="main-header" class="bg-gradient-to-b fixed top-0 left-0 w-full z-50 transition-all duration-300">
    <nav class="mx-auto flex max-w-7xl items-center justify-between p-4 lg:px-8">
        <div class="flex lg:flex-1">
            <a href="{{ url('/') }}">
                <img class="h-20 lg:h-24 w-auto transition-all duration-300" src="{{ asset('assets/img/logo.png') }}" alt="EVC Logo" decoding="async" fetchpriority="high">
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
            <a href="{{ route('laureats') }}" class="text-sm font-semibold leading-6 text-gray-300 hover:text-white transition mr-4 lg:mr-8">Nos Lauréats</a>
        </div>
        <div class="hidden lg:flex lg:flex-1 lg:justify-end lg:items-center gap-x-6">
             <a href="{{ route('preinscription.start') }}" class="preinscription-btn whitespace-nowrap inline-flex items-center px-4 py-2 rounded-full text-white font-semibold bg-gradient-to-r from-orange-500 to-amber-400 hover:from-orange-400 hover:to-amber-300 shadow transition">Préinscription</a>

            <a href="{{ route('login') }}" target="_blank" class="btn btn-secondary whitespace-nowrap">Espace Étudiant</a>
        </div>
    </nav>
</header>

<!-- Mobile menu -->
<div id="mobile-menu" class="lg:hidden hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 z-[9998] bg-black/30" aria-hidden="true"></div>
    <div class="fixed inset-y-0 right-0 z-[9999] w-full overflow-y-auto bg-gradient-to-b from-[#000033] to-[#000066] px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-white/10">
        <div class="flex items-center justify-between">
            <a href="{{ url('/') }}" class="-m-1.5 p-1.5">
                <img class="h-16 w-auto" src="{{ asset('assets/img/logo.png') }}" alt="EVC Logo" decoding="async">
            </a>
            <button type="button" id="mobile-menu-close-button" class="-m-2.5 rounded-md p-2.5 text-gray-400">
                <span class="sr-only">Fermer le menu</span>
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <div class="mt-6 flow-root">
            <div class="-my-6 divide-y divide-gray-500/25">
                <div class="space-y-2 py-6">
                    <a href="{{ route('preinscription.start') }}" class="-mx-3 block rounded-full py-3 px-6 text-base font-bold leading-7 text-white text-center" style="background: linear-gradient(135deg, #ff9800 0%, #ff6b00 100%); box-shadow: 0 8px 25px rgba(255, 152, 0, 0.4), 0 4px 15px rgba(255, 152, 0, 0.3); border: 2px solid rgba(255, 255, 255, 0.2); margin-bottom: 1rem;">
                        <i class="fas fa-edit mr-2"></i>Préinscription
                    </a>
                    <a href="{{ route('presentation') }}" class="-mx-3 block rounded-lg py-2 px-3 text-base font-semibold leading-7 text-white hover:bg-gray-800">Présentation</a>
                    <a href="{{ route('formations') }}" class="-mx-3 block rounded-lg py-2 px-3 text-base font-semibold leading-7 text-white hover:bg-gray-800">Nos Formations</a>
                    <a href="{{ route('travaux') }}" class="-mx-3 block rounded-lg py-2 px-3 text-base font-semibold leading-7 text-white hover:bg-gray-800">Travaux Étudiants</a>
                    <a href="{{ route('laureats') }}" class="-mx-3 block rounded-lg py-2 px-3 text-base font-semibold leading-7 text-white hover:bg-gray-800">Nos Lauréats</a>

                </div>
                <div class="py-6">
                    <a href="{{ route('login') }}" target="_blank" class="-mx-3 block rounded-lg py-2.5 px-3 text-base font-semibold leading-6 text-white hover:bg-gray-800">Espace Étudiant</a>
                </div>
            </div>
        </div>
    </div>
</div>
