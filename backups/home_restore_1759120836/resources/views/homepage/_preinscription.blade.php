<!-- Section Préinscription -->
<div id="preinscription" class="bg-gray-900 py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl lg:text-center" data-aos="fade-up">
            <h2 class="section-subtitle">Admissions</h2>
            <p class="section-title mt-2 text-white">Réservez votre place pour la prochaine rentrée.</p>
            <div class="mt-6 text-lg leading-8 text-gray-300 space-y-4">
                <p>À l’EVC, nous plaçons l’excellence au-dessus de tout : chaque préinscription est étudiée avec la plus grande attention.</p>
                <p>L’accès n’est pas garanti : seuls les candidats qui répondent à nos critères de motivation et de sérieux sont retenus.</p>
                <p class="font-semibold text-white">Si ton dossier est validé, tu recevras une confirmation officielle avec la marche à suivre.</p>
            </div>
            <div class="mt-10">
                <a href="{{ route('preinscription.start') }}" class="btn btn-primary text-lg py-4 px-8">
                    Démarrer ma pré-inscription
                </a>
            </div>

            <!-- Formulaire Modal Plein Écran -->
            <div id="form-modal" class="fixed inset-0 z-[10000] bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 hidden opacity-0 transition-opacity duration-300">
                <div id="preinscription-form-container" class="relative bg-dark-secondary p-8 rounded-2xl shadow-lg border border-gray-700 w-full max-w-2xl transform scale-95 transition-all duration-300">
                    <button id="close-form-modal" class="absolute top-4 right-4 text-gray-400 hover:text-white transition-colors z-10">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                    
                    <form id="preRegistrationForm" novalidate>
                        @csrf
                        <!-- Étape 1: Infos personnelles -->
                        <div class="form-step active" data-step="1">
                            <h4 class="text-xl font-semibold text-white mb-6">Étape 1: Informations Personnelles</h4>
                            <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                                <input type="text" name="nom" placeholder="Nom" required class="form-input">
                                <input type="text" name="prenom" placeholder="Prénom" required class="form-input">
                                <div class="sm:col-span-2"><input type="email" name="email" placeholder="Adresse Email" required class="form-input"></div>
                                <div class="sm:col-span-2"><input type="tel" name="whatsapp" placeholder="Numéro WhatsApp" required class="form-input"></div>
                            </div>
                        </div>

                        <!-- Étape 2: Infos académiques -->
                        <div class="form-step" data-step="2">
                            <h4 class="text-xl font-semibold text-white mb-6">Étape 2: Votre Parcours</h4>
                            <div class="space-y-4">
                                <input type="text" name="niveau_etude" placeholder="Votre plus haut niveau d'étude" required class="form-input">
                                <select name="choix_formation" required class="form-input">
                                    <option selected disabled value="">Quelle formation vous intéresse ?</option>
                                    <option value="design_graphique">Design Graphique</option>
                                    <option value="community_management">Community Management</option>
                                    <option value="gestion_informatique">Gestion Informatique</option>
                                    <option value="intelligence_artificielle">Intelligence Artificielle</option>
                                </select>
                            </div>
                        </div>

                        <!-- Étape 3: Motivation -->
                        <div class="form-step" data-step="3">
                            <h4 class="text-xl font-semibold text-white mb-6">Étape 3: Vos Motivations</h4>
                            <textarea name="motivation" rows="5" placeholder="Pourquoi souhaitez-vous apprendre ce métier ? Décrivez vos attentes..." required class="form-input"></textarea>
                        </div>

                        <!-- Barre de Progression & Boutons -->
                        <div class="mt-8 pt-5 border-t border-gray-700">
                            <div class="flex justify-between items-center">
                                <div class="w-1/4">
                                    <button type="button" id="prevBtn" class="btn btn-secondary" style="display: none;">Précédent</button>
                                </div>
                                <div class="w-1/2 px-4">
                                    <div class="w-full bg-gray-700 rounded-full h-2.5">
                                        <div id="progressBar" class="bg-evc-orange h-2.5 rounded-full transition-all duration-500" style="width: 33.33%;"></div>
                                    </div>
                                </div>
                                <div class="w-1/4 text-right">
                                    <button type="button" id="nextBtn" class="btn btn-primary">Suivant</button>
                                    <button type="submit" id="submitBtn" class="btn btn-primary" style="display: none;">Envoyer</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Overlay d'envoi (optionnel si pas déjà global) -->
                <div id="mail-loading-overlay" class="fixed inset-0 z-[10010] bg-black/70 backdrop-blur-sm hidden items-center justify-center" style="display:none;">
                  <div class="flex flex-col items-center gap-3 text-white">
                    <svg class="animate-spin h-10 w-10 text-evc-orange" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <div class="text-sm">Envoi en cours... Merci de patienter</div>
                  </div>
                  <span class="sr-only">Envoi des emails en cours</span>
                </div>
            </div>
        </div>
    </div>
</div>
