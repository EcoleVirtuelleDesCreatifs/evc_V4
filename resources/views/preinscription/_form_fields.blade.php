            <!-- Mini progress bar -->
            <div class="w-full h-1.5 bg-white/10 rounded-full mb-4 overflow-hidden">
                <div id="form-progress-line" class="h-full bg-evc-orange rounded-full" style="width: 0%;"></div>
            </div>
            <!-- Informations personnelles -->
            <h3 class="text-white text-lg font-bold mb-2 sticky top-0 z-10 bg-dark-secondary/90 backdrop-blur px-1 py-2 -mx-1 border-b border-white/5">Informations personnelles</h3>
            <label class="block text-sm text-gray-300">Nom complet :</label>
            <input type="text" name="nom_complet" class="form-input mb-3" required>

            <label class="block text-sm text-gray-300">Prénom :</label>
            <input type="text" name="prenom" class="form-input mb-3" required>

            <label class="block text-sm text-gray-300">Âge :</label>
            <input type="number" name="age" min="10" max="100" class="form-input mb-3" required>

            <label class="block text-sm text-gray-300">Date de naissance :</label>
            <input type="date" name="date_naissance" class="form-input mb-3" required>

            <label class="block text-sm text-gray-300">Sexe :</label>
            <select name="sexe" class="form-input mb-3" required>
                <option value="">-- Sélectionnez --</option>
                <option value="M">Masculin</option>
                <option value="F">Féminin</option>
            </select>

            <label class="block text-sm text-gray-300">Nationalité :</label>
            <input type="text" name="nationalite" class="form-input mb-3" required>

            <label class="block text-sm text-gray-300">Photo de profil :</label>
            <input type="file" name="photo_profil" accept="image/*" class="form-input mb-4" required>

            <hr class="border-gray-700 my-4">

            <!-- Coordonnées -->
            <h3 class="text-white text-lg font-bold mb-2 sticky top-0 z-10 bg-dark-secondary/90 backdrop-blur px-1 py-2 -mx-1 border-b border-white/5">Coordonnées</h3>
            <label class="block text-sm text-gray-300">Email :</label>
            <input type="email" name="email" class="form-input mb-3" required>

            <label class="block text-sm text-gray-300">Numéro WhatsApp :</label>
            <input type="tel" name="whatsapp" placeholder="+225XXXXXXXX" class="form-input mb-3" required>

            <label class="block text-sm text-gray-300">Ville / Pays de résidence :</label>
            <input type="text" name="ville_pays" class="form-input mb-4" required>

            <hr class="border-gray-700 my-4">

            <!-- Informations académiques & professionnelles -->
            <h3 class="text-white text-lg font-bold mb-2 sticky top-0 z-10 bg-dark-secondary/90 backdrop-blur px-1 py-2 -mx-1 border-b border-white/5">Informations académiques & professionnelles</h3>
            <label class="block text-sm text-gray-300">Niveau d’étude :</label>
            <select name="niveau_etude" class="form-input mb-3" required>
                <option value="">-- Sélectionnez --</option>
                <option>Collège</option>
                <option>Lycée</option>
                <option>Bac</option>
                <option>Bac+2</option>
                <option>Licence</option>
                <option>Master</option>
                <option>Doctorat</option>
            </select>

            <label class="block text-sm text-gray-300">Domaine d’étude ou spécialité :</label>
            <input type="text" name="domaine_etude" class="form-input mb-3" required>

            <label class="block text-sm text-gray-300">Compétences déjà acquises :</label>
            <textarea name="competences" class="form-input mb-4" required></textarea>

            <hr class="border-gray-700 my-4">

            <!-- Formation demandée -->
            <h3 class="text-white text-lg font-bold mb-2 sticky top-0 z-10 bg-dark-secondary/90 backdrop-blur px-1 py-2 -mx-1 border-b border-white/5">Formation demandée</h3>
            <label class="block text-sm text-gray-300">Programme souhaité :</label>
            <select name="programme" class="form-input mb-3" required>
                <option value="">-- Sélectionnez --</option>
                <option>Infographie</option>
                <option>Community Management</option>
                <option>Informatique</option>
            </select>

            <label class="block text-sm text-gray-300">Niveau actuel pour cette formation :</label>
            <select name="niveau_formation" class="form-input mb-3" required>
                <option value="">-- Sélectionnez --</option>
                <option>Aucune notion</option>
                <option>Certaines notions</option>
                <option>Monter en compétence</option>
            </select>

            <label class="block text-sm text-gray-300">Motivation pour la formation :</label>
            <textarea name="motivation" class="form-input mb-3" required></textarea>

            <label class="block text-sm text-gray-300">Comment avez-vous connu l’EVC ?</label>
            <select name="origine" class="form-input mb-4" required>
                <option value="">-- Sélectionnez --</option>
                <option>Réseaux sociaux</option>
                <option>Ami</option>
                <option>Publicité</option>
                <option>Autre</option>
            </select>

            <hr class="border-gray-700 my-4">

            <!-- Équipements disponibles -->
            <h3 class="text-white text-lg font-bold mb-2 sticky top-0 z-10 bg-dark-secondary/90 backdrop-blur px-1 py-2 -mx-1 border-b border-white/5">Équipements disponibles</h3>
            <label class="block text-sm text-gray-300">Avez-vous un ordinateur ?</label>
            <select name="ordinateur" class="form-input mb-3" required>
                <option value="">-- Sélectionnez --</option>
                <option>Oui</option>
                <option>Non</option>
            </select>

            <label class="block text-sm text-gray-300">Avez-vous un smartphone ?</label>
            <select name="smartphone" class="form-input mb-4" required>
                <option value="">-- Sélectionnez --</option>
                <option>Oui</option>
                <option>Non</option>
            </select>

            <hr class="border-gray-700 my-4">

            <!-- Validation -->
            <h3 class="text-white text-lg font-bold mb-2 sticky top-0 z-10 bg-dark-secondary/90 backdrop-blur px-1 py-2 -mx-1 border-b border-white/5">Validation</h3>
            <label class="flex items-start gap-2 text-gray-200 mb-2">
                <input type="checkbox" name="veracite" required class="mt-1 h-4 w-4">
                <span>Je certifie que les informations fournies sont exactes.</span>
            </label>
            <label class="flex items-start gap-2 text-gray-200 mb-4">
                <input type="checkbox" name="consentement" required class="mt-1 h-4 w-4">
                <span>J’accepte que mes données soient utilisées dans le cadre du processus de candidature.</span>
            </label>

            <!-- Sticky submit bar -->
            <div class="sticky bottom-0 -mx-8 mt-6 bg-dark-secondary/95 backdrop-blur px-8 py-4 border-t border-white/5 flex items-center justify-between">
                <p id="form-progress-text" class="text-sm text-gray-300">Progression: 0%</p>
                <button type="submit" class="btn btn-primary">Soumettre ma candidature</button>
            </div>
