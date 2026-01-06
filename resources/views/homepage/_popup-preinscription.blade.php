<!-- Popup Préinscription -->
<div id="popup-preinscription" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-[9999] hidden opacity-0 transition-opacity duration-500 p-4">
    <div class="relative bg-gradient-to-br from-[#001f54] via-[#034078] to-[#001f54] rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto transform scale-95 transition-transform duration-500" id="popup-content">

        <!-- Bouton Fermer -->
        <button id="close-popup" class="absolute top-4 right-4 z-10 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center transition-all duration-300 group">
            <i class="fas fa-times text-white text-xl group-hover:rotate-90 transition-transform duration-300"></i>
        </button>

        <!-- Décoration -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-orange-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-orange-600/10 rounded-full blur-3xl"></div>

        <!-- Contenu -->
        <div class="relative p-6 md:p-12">
            <!-- Icon -->
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg animate-bounce-slow">
                    <i class="fas fa-graduation-cap text-white text-2xl md:text-3xl"></i>
                </div>
            </div>

            <!-- Titre -->
            <h2 class="text-2xl md:text-4xl font-bold text-white text-center mb-3">
                🎓 Transformez Votre Avenir !
            </h2>

            <!-- Sous-titre -->
            <p class="text-base md:text-lg text-gray-300 text-center mb-4 md:mb-6">
                Rejoignez <span class="text-orange-400 font-bold">+1542 étudiants</span> qui ont déjà fait le choix de l'excellence digitale
            </p>

            <!-- Avantages -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-6 md:mb-8">
                <div class="flex items-start gap-2 md:gap-3 bg-white/5 p-3 md:p-4 rounded-xl backdrop-blur-sm">
                    <div class="w-7 h-7 md:w-8 md:h-8 bg-orange-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check text-orange-400 text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold text-sm md:text-base mb-0.5">Formation Certifiante</h3>
                        <p class="text-gray-400 text-xs md:text-sm">Certificat + Lettre de recommandation</p>
                    </div>
                </div>

                <div class="flex items-start gap-2 md:gap-3 bg-white/5 p-3 md:p-4 rounded-xl backdrop-blur-sm">
                    <div class="w-7 h-7 md:w-8 md:h-8 bg-orange-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-laptop text-orange-400 text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold text-sm md:text-base mb-0.5">En Ligne et En Présentiel</h3>
                        <p class="text-gray-400 text-xs md:text-sm">Formation 100% Ultra-pratique</p>
                    </div>
                </div>

                <div class="flex items-start gap-2 md:gap-3 bg-white/5 p-3 md:p-4 rounded-xl backdrop-blur-sm">
                    <div class="w-7 h-7 md:w-8 md:h-8 bg-orange-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-users text-orange-400 text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold text-sm md:text-base mb-0.5">Suivi Personnalisé</h3>
                        <p class="text-gray-400 text-xs md:text-sm">Accompagnement individuel</p>
                    </div>
                </div>

                <div class="flex items-start gap-2 md:gap-3 bg-white/5 p-3 md:p-4 rounded-xl backdrop-blur-sm">
                    <div class="w-7 h-7 md:w-8 md:h-8 bg-orange-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-briefcase text-orange-400 text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold text-sm md:text-base mb-0.5">Insertion Pro</h3>
                        <p class="text-gray-400 text-xs md:text-sm">+54 étudiants embauchés</p>
                    </div>
                </div>
            </div>

            <!-- Urgence -->
            <div class="bg-orange-500/10 border border-orange-500/30 rounded-xl p-3 md:p-4 mb-4 md:mb-6 text-center">
                <p class="text-orange-400 font-semibold text-sm md:text-base mb-1">
                    <i class="fas fa-fire animate-pulse"></i> Places Limitées !
                </p>
                <p class="text-gray-300 text-xs md:text-sm">
                    Ne manquez pas cette opportunité de rejoindre la <strong>1ère École Digitale</strong> d'Afrique Francophone
                </p>
            </div>

            <!-- Boutons CTA -->
            <div class="flex flex-col sm:flex-row gap-3 md:gap-4">
                <a href="{{ route('preinscription.start') }}" class="flex-1 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-3 md:py-4 px-4 md:px-6 rounded-xl text-center text-sm md:text-base transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-orange-500/50">
                    <i class="fas fa-rocket mr-2"></i>
                    Je M'inscris Maintenant
                </a>
                <button id="later-popup" class="flex-1 bg-white/10 hover:bg-white/20 text-white font-semibold py-3 md:py-4 px-4 md:px-6 rounded-xl text-sm md:text-base transition-all duration-300 border border-white/20">
                    Plus Tard
                </button>
            </div>

            <!-- Note -->
            <p class="text-center text-gray-400 text-xs mt-4 md:mt-6">
                <i class="fas fa-lock mr-1"></i> Vos données sont 100% sécurisées
            </p>
        </div>
    </div>
</div>

<style>
@keyframes bounce-slow {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.animate-bounce-slow {
    animation: bounce-slow 2s ease-in-out infinite;
}

#popup-preinscription.show {
    opacity: 1;
}

#popup-preinscription.show #popup-content {
    transform: scale(1);
}
</style>

<script>
(function() {
    'use strict';

    console.log('🚀 Script popup chargé');

    // Attendre que le DOM soit prêt
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPopup);
    } else {
        initPopup();
    }

    function initPopup() {
        console.log('📋 Initialisation du popup');

        const popup = document.getElementById('popup-preinscription');
        const closeBtn = document.getElementById('close-popup');
        const laterBtn = document.getElementById('later-popup');

        if (!popup) {
            console.error('❌ Popup non trouvé dans le DOM');
            return;
        }

        console.log('✅ Popup trouvé:', popup);

        // Vérifier si on est sur la page d'accueil
        const isHomePage = window.location.pathname === '/' || window.location.pathname === '';
        console.log('🏠 Page d\'accueil:', isHomePage, 'Path:', window.location.pathname);

        if (!isHomePage) {
            console.log('⚠️ Pas sur la page d\'accueil, popup désactivé');
            return;
        }

        // Vérifier si le popup a déjà été affiché
        const popupShown = sessionStorage.getItem('popupShown');
        console.log('💾 Popup déjà affiché:', popupShown);

        if (popupShown === 'true') {
            console.log('⏭️ Popup déjà affiché dans cette session');
            return;
        }

        // Fonction pour afficher le popup
        function showPopup() {
            console.log('🎉 Affichage du popup...');
            popup.classList.remove('hidden');
            setTimeout(() => {
                popup.classList.add('show');
                console.log('✨ Popup visible');
            }, 100);
            sessionStorage.setItem('popupShown', 'true');
        }

        // Fonction pour fermer le popup
        function closePopup() {
            console.log('❌ Fermeture du popup');
            popup.classList.remove('show');
            setTimeout(() => {
                popup.classList.add('hidden');
            }, 500);
        }

        // Chercher la section fondateur
        const fondateurSection = document.getElementById('fondateur-section');
        console.log('👤 Section fondateur:', fondateurSection);

        if (fondateurSection) {
            console.log('✅ Section fondateur trouvée, activation de l\'observer');

            // Observer le scroll
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    console.log('👁️ Intersection:', {
                        isIntersecting: entry.isIntersecting,
                        intersectionRatio: entry.intersectionRatio,
                        boundingClientRect: entry.boundingClientRect
                    });

                    if (entry.isIntersecting && sessionStorage.getItem('popupShown') !== 'true') {
                        console.log('🎯 Section visible, affichage du popup dans 0.5s');
                        setTimeout(() => {
                            showPopup();
                            observer.disconnect();
                        }, 500);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px'
            });

            observer.observe(fondateurSection);
            console.log('👀 Observer activé');
        } else {
            console.warn('⚠️ Section fondateur non trouvée, fallback à 30 secondes');
            setTimeout(() => {
                if (sessionStorage.getItem('popupShown') !== 'true') {
                    console.log('⏰ Fallback: affichage après 30 secondes');
                    showPopup();
                }
            }, 30000);
        }

        // Event listeners pour fermer
        if (closeBtn) {
            closeBtn.addEventListener('click', closePopup);
            console.log('✅ Bouton fermer configuré');
        }

        if (laterBtn) {
            laterBtn.addEventListener('click', closePopup);
            console.log('✅ Bouton "Plus tard" configuré');
        }

        popup.addEventListener('click', function(e) {
            if (e.target === popup) {
                closePopup();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !popup.classList.contains('hidden')) {
                closePopup();
            }
        });

        console.log('✅ Popup complètement initialisé');
    }
})();
</script>
