<!-- Section Fondateur -->
<div class="py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto grid max-w-5xl grid-cols-1 items-center gap-x-16 gap-y-16 sm:gap-y-24 lg:grid-cols-2">
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
                             <p class="text-gray-300">Auteur du livre "Le Guide du Créatif Digital" et créateur de multiples projets web innovants.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
