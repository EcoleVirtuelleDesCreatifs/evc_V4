<!-- Section Fondateur -->
<div class="founder-section-bg relative overflow-hidden py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="relative z-10 mx-auto grid max-w-5xl grid-cols-1 items-center gap-x-16 gap-y-16 sm:gap-y-24 lg:grid-cols-2">
            <div class="flex justify-center lg:justify-end" data-aos="fade-right">
                <div class="relative overflow-hidden rounded-3xl shadow-2xl w-[298px] h-[406px]">
                    <img class="h-full w-full object-cover" src="https://i.pravatar.cc/800?img=4" alt="Photo du fondateur">
                </div>
            </div>
            <div data-aos="fade-left" data-aos-delay="200">
                <div class="text-base leading-7 text-gray-400 lg:max-w-lg">
                    <h2 class="text-base font-semibold leading-7 text-orange-500">Fondateur & Formateur Principal</h2>
                    <p class="font-sans mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">Bilé Bossombra</p>
                </div>
                
                <div id="founder-tabs" class="mt-8">
                    {{-- Tab Navigation --}}
                    <div class="border-b border-gray-700">
                        <div class="-mb-px flex space-x-8" aria-label="Tabs">
                            <button class="tab-button active" data-target="parcours">Parcours</button>
                            <button class="tab-button" data-target="certifications">Certifications</button>
                            <button class="tab-button" data-target="realisations">Réalisations</button>
                        </div>
                    </div>
                    {{-- Tab Content --}}
                    <div class="mt-8">
                        <div class="tab-panel active" id="parcours">
                            <p class="text-gray-300">Expert en communication digitale et entrepreneur passionné, Bilé a fondé l'EVC avec la conviction que la pratique est la clé de la maîtrise. Sa mission : former la prochaine génération de créatifs et de leaders du digital en Afrique. Avec plus de 10 ans d'expérience, il a accompagné des centaines d'étudiants.</p>
                        </div>
                        <div class="tab-panel" id="certifications">
                            <ul class="list-disc list-inside text-gray-300 space-y-2">
                                <li>Certification Google Digital Marketing</li>
                                <li>Certification Meta Blueprint</li>
                                <li>Adobe Certified Expert (ACE) - Photoshop</li>
                                <li>(Ajouter autres certifications ici)</li>
                            </ul>
                        </div>
                        <div class="tab-panel" id="realisations">
                             <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- Realisation 1: Book -->
                                <a href="https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=800&q=80" data-fancybox="realisations" data-caption="Livre: Le Guide du Créatif Digital">
                                    <div class="group relative overflow-hidden rounded-lg">
                                        <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=400&q=80" alt="Aperçu du livre" class="w-full h-40 object-cover transition-transform duration-300 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                                        <div class="absolute bottom-0 left-0 p-4">
                                            <h4 class="font-semibold text-white">Livre</h4>
                                            <p class="text-sm text-gray-300">Le Guide du Créatif</p>
                                        </div>
                                    </div>
                                </a>
                                <!-- Realisation 2: Visual Creation -->
                                <a href="https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?auto=format&fit=crop&w=800&q=80" data-fancybox="realisations" data-caption="Création Visuelle: Identité de Marque">
                                    <div class="group relative overflow-hidden rounded-lg">
                                        <img src="https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?auto=format&fit=crop&w=400&q=80" alt="Aperçu d'une création visuelle" class="w-full h-40 object-cover transition-transform duration-300 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                                        <div class="absolute bottom-0 left-0 p-4">
                                            <h4 class="font-semibold text-white">Design UI/UX</h4>
                                            <p class="text-sm text-gray-300">Identité de Marque</p>
                                        </div>
                                    </div>
                                </a>
                                <!-- Realisation 3: App -->
                                <a href="https://images.unsplash.com/photo-1551650975-87deedd944c3?auto=format&fit=crop&w=800&q=80" data-fancybox="realisations" data-caption="Application Web: Plateforme E-learning">
                                    <div class="group relative overflow-hidden rounded-lg">
                                        <img src="https://images.unsplash.com/photo-1551650975-87deedd944c3?auto=format&fit=crop&w=400&q=80" alt="Aperçu d'une application" class="w-full h-40 object-cover transition-transform duration-300 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                                        <div class="absolute bottom-0 left-0 p-4">
                                            <h4 class="font-semibold text-white">Application Web</h4>
                                            <p class="text-sm text-gray-300">Plateforme E-learning</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
