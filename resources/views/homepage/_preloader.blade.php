<!-- Preloader -->
<div id="preloader" class="fixed inset-0 bg-gradient-to-b from-[#000033] to-[#000066] flex flex-col items-center justify-center z-[10000] transition-opacity duration-500">
    <div class="relative text-center">
        <!-- Logo avec animation -->
        <div class="logo-container mb-8">
            <img id="preloader-logo" class="h-32 w-auto mx-auto animate-pulse-slow" src="{{ asset('assets/img/logo.png') }}" alt="EVC Logo">
        </div>
        
        <!-- Spinner moderne -->
        <div class="flex justify-center mb-6">
            <div class="relative">
                <!-- Cercle extérieur -->
                <div class="w-16 h-16 border-4 border-orange-200 border-t-orange-500 rounded-full animate-spin"></div>
                <!-- Cercle intérieur -->
                <div class="absolute top-2 left-2 w-12 h-12 border-4 border-orange-100 border-b-orange-400 rounded-full animate-spin-reverse"></div>
            </div>
        </div>
        
        <!-- Texte -->
        <p class="text-white text-lg font-light tracking-wider mb-2">École Virtuelle des Créatifs</p>
        <p class="text-orange-400 text-sm font-medium animate-pulse">Chargement en cours...</p>
        
        <!-- Barre de progression -->
        <div class="w-64 h-1 bg-gray-700 rounded-full overflow-hidden mt-6 mx-auto">
            <div class="h-full bg-gradient-to-r from-orange-500 to-orange-400 animate-progress"></div>
        </div>
    </div>
</div>

<style>
@keyframes pulse-slow {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.8; transform: scale(1.05); }
}

@keyframes spin-reverse {
    from { transform: rotate(360deg); }
    to { transform: rotate(0deg); }
}

@keyframes progress {
    0% { width: 0%; }
    100% { width: 100%; }
}

.animate-pulse-slow {
    animation: pulse-slow 2s ease-in-out infinite;
}

.animate-spin-reverse {
    animation: spin-reverse 1s linear infinite;
}

.animate-progress {
    animation: progress 10s ease-out forwards;
}
</style>
