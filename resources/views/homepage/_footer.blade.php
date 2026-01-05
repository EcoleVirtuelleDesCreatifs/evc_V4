<!-- Footer -->
<footer class="bg-gradient-to-br from-[#0a1128] via-[#001f54] to-[#034078] relative overflow-hidden" aria-labelledby="footer-heading">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <svg class="h-full w-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="footer-pattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                    <circle cx="20" cy="20" r="1" fill="white"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#footer-pattern)"/>
        </svg>
    </div>

    <h2 id="footer-heading" class="sr-only">Footer</h2>
    <div class="relative mx-auto max-w-7xl px-6 pb-8 pt-16 sm:pt-20 lg:px-8 lg:pt-24">
        <!-- Main Footer Content -->
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-4 lg:gap-8">
            <!-- Column 1: Logo & Description -->
            <div class="lg:col-span-1 space-y-8">
                <div>
                    <img class="h-16 w-auto" src="{{ asset('assets/img/logo.png') }}" alt="EVC Logo" loading="lazy" decoding="async">
                </div>
                <p class="text-sm leading-6 text-gray-300">
                    L'École Virtuelle des Créatifs (EVC) est une référence incontournable en formation digitale. Reconnue par l'État ivoirien, cette SARL mise sur une pédagogie 100 % Ultra-pratique, axée sur les besoins réels du marché.
                </p>

                <!-- Social Media -->
                <div>
                    <h4 class="text-sm font-semibold text-white mb-4">Suivez-nous</h4>
                    <div class="flex flex-wrap gap-3">
                        <a href="https://web.facebook.com/bilebossombraofficiel?_rdc=1&_rdr" target="_blank" class="group" title="Facebook - 12.5K followers">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition-all duration-300 group-hover:bg-[#1877F2] group-hover:scale-110">
                                <i class="fab fa-facebook text-white text-lg"></i>
                            </div>
                        </a>
                        <a href="https://instagram.com/evc2024" target="_blank" class="group" title="Instagram - 8.3K followers">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition-all duration-300 group-hover:bg-[#E4405F] group-hover:scale-110">
                                <i class="fab fa-instagram text-white text-lg"></i>
                            </div>
                        </a>
                        <a href="https://www.linkedin.com/company/82489374/" target="_blank" class="group" title="LinkedIn - 3.4K followers">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition-all duration-300 group-hover:bg-[#0A66C2] group-hover:scale-110">
                                <i class="fab fa-linkedin text-white text-lg"></i>
                            </div>
                        </a>
                        <a href="https://www.youtube.com/@ecolevirtuelledescreatifs459" target="_blank" class="group" title="YouTube - 5.8K abonnés">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition-all duration-300 group-hover:bg-[#FF0000] group-hover:scale-110">
                                <i class="fab fa-youtube text-white text-lg"></i>
                            </div>
                        </a>
                        <a href="https://www.tiktok.com/@ecolevirtuelledescreatif" target="_blank" class="group" title="TikTok - 15.2K followers">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition-all duration-300 group-hover:bg-black group-hover:scale-110">
                                <i class="fab fa-tiktok text-white text-lg"></i>
                            </div>
                        </a>
                        <a href="https://whatsapp.com/channel/0029Va9RABj2ER6dFnFLNp2Y" target="_blank" class="group" title="Canal WhatsApp - 1,123 abonnés">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition-all duration-300 group-hover:bg-[#25D366] group-hover:scale-110">
                                <i class="fab fa-whatsapp text-white text-lg"></i>
                            </div>
                        </a>
                        <a href="https://t.me/c/1544085173/1" target="_blank" class="group" title="Canal Telegram - 892 abonnés">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition-all duration-300 group-hover:bg-[#0088cc] group-hover:scale-110">
                                <i class="fab fa-telegram text-white text-lg"></i>
                            </div>
                        </a>
                        <a href="https://fr.pinterest.com/cratifs/" target="_blank" class="group" title="Pinterest">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition-all duration-300 group-hover:bg-[#E60023] group-hover:scale-110">
                                <i class="fab fa-pinterest text-white text-lg"></i>
                            </div>
                        </a>
                        <a href="https://www.behance.net/ecolevirtuelle" target="_blank" class="group" title="Behance">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition-all duration-300 group-hover:bg-[#1769FF] group-hover:scale-110">
                                <i class="fab fa-behance text-white text-lg"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Column 2: Formations -->
            <div>
                <h3 class="text-base font-bold text-white mb-6">Nos Formations</h3>
                <ul role="list" class="space-y-3">
                    <li>
                        <a href="{{ route('formations') }}#design-graphique" class="group flex items-center text-sm text-gray-300 hover:text-orange-400 transition-colors">
                            <i class="fas fa-chevron-right text-orange-500 text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                            Design Graphique
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('formations') }}#community-management" class="group flex items-center text-sm text-gray-300 hover:text-orange-400 transition-colors">
                            <i class="fas fa-chevron-right text-orange-500 text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                            Community Management
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('formations') }}#gestion-informatique" class="group flex items-center text-sm text-gray-300 hover:text-orange-400 transition-colors">
                            <i class="fas fa-chevron-right text-orange-500 text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                            Gestion Informatique
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('formations') }}#intelligence-artificielle" class="group flex items-center text-sm text-gray-300 hover:text-orange-400 transition-colors">
                            <i class="fas fa-chevron-right text-orange-500 text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                            Intelligence Artificielle
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 3: L'école -->
            <div>
                <h3 class="text-base font-bold text-white mb-6">L'école</h3>
                <ul role="list" class="space-y-3">
                    <li>
                        <a href="{{ route('presentation') }}" class="group flex items-center text-sm text-gray-300 hover:text-orange-400 transition-colors">
                            <i class="fas fa-chevron-right text-orange-500 text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                            Présentation
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('parcours-formateur') }}" class="group flex items-center text-sm text-gray-300 hover:text-orange-400 transition-colors">
                            <i class="fas fa-chevron-right text-orange-500 text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                            Parcours du formateur
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('evenements.all') }}" class="group flex items-center text-sm text-gray-300 hover:text-orange-400 transition-colors">
                            <i class="fas fa-chevron-right text-orange-500 text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                            Événements
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('actualites') }}" class="group flex items-center text-sm text-gray-300 hover:text-orange-400 transition-colors">
                            <i class="fas fa-chevron-right text-orange-500 text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                            Actualités
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('travaux') }}" class="group flex items-center text-sm text-gray-300 hover:text-orange-400 transition-colors">
                            <i class="fas fa-chevron-right text-orange-500 text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                            Photothèque
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('laureats') }}" class="group flex items-center text-sm text-gray-300 hover:text-orange-400 transition-colors">
                            <i class="fas fa-chevron-right text-orange-500 text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                            Lauréats
                        </a>
                    </li>
                </ul>

                <!-- Rejoignez-nous -->
                <div class="mt-8 pt-8 border-t border-white/10">
                    <h4 class="text-sm font-semibold text-white mb-4">Rejoignez-nous</h4>
                    <ul role="list" class="space-y-3">
                        <li>
                            <a href="{{ route('rejoignez-nous.collaborateur') }}" class="group flex items-center text-sm text-gray-300 hover:text-orange-400 transition-colors">
                                <i class="fas fa-handshake text-orange-500 text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                                Collaborateur
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rejoignez-nous.partenaire') }}" class="group flex items-center text-sm text-gray-300 hover:text-orange-400 transition-colors">
                                <i class="fas fa-users text-orange-500 text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                                Partenaire
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rejoignez-nous.formateur') }}" class="group flex items-center text-sm text-gray-300 hover:text-orange-400 transition-colors">
                                <i class="fas fa-chalkboard-teacher text-orange-500 text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                                Devenir formateur
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Column 4: Contact & CTA -->
            <div>
                <h3 class="text-base font-bold text-white mb-6">Contact</h3>
                <ul class="space-y-4 mb-8">
                    <li class="flex items-start text-sm text-gray-300">
                        <i class="fas fa-map-marker-alt text-orange-500 mt-1 mr-3"></i>
                        <span>Abidjan, Côte d'Ivoire</span>
                    </li>
                    <li class="flex items-start text-sm text-gray-300">
                        <i class="fas fa-envelope text-orange-500 mt-1 mr-3"></i>
                        <a href="mailto:info@ecolevirtuelledescreatifs.com" class="hover:text-orange-400 transition-colors">info@ecolevirtuelledescreatifs.com</a>
                    </li>
                    <li class="flex items-start text-sm text-gray-300">
                        <i class="fas fa-phone text-orange-500 mt-1 mr-3"></i>
                        <a href="tel:+2250747259507" class="hover:text-orange-400 transition-colors">+225 07 47 25 95 07</a>
                    </li>
                </ul>

                <!-- CTA Button -->
                <a href="{{ route('preinscription.start') }}" class="inline-flex items-center justify-center w-full px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-orange-600 rounded-full hover:from-orange-600 hover:to-orange-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-orange-500/50">
                    <i class="fas fa-user-plus mr-2"></i>
                    Pré-inscription
                </a>

                <a href="{{ route('donation.index') }}" class="mt-4 inline-flex items-center justify-center w-full px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-emerald-500 to-teal-600 rounded-full hover:from-emerald-600 hover:to-teal-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-emerald-500/40">
                    <i class="fas fa-hand-holding-heart mr-2"></i>
                    Faire un don
                </a>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="mt-12 border-t border-white/10 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-gray-400">
                    &copy; {{ date('Y') }} <span class="font-semibold text-white">EVC - École Virtuelle des Créatifs</span>. Tous droits réservés.
                </p>
                <div class="flex gap-6 text-sm">
                    <a href="{{ route('mentions-legales') }}" class="text-gray-400 hover:text-orange-400 transition-colors">Mentions Légales</a>
                    <span class="text-gray-600">|</span>
                    <a href="{{ route('politique-confidentialite') }}" class="text-gray-400 hover:text-orange-400 transition-colors">Politique de Confidentialité</a>
                </div>
            </div>
        </div>
    </div>
</footer>
