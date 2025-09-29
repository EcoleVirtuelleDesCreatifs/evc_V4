            <!-- Mini progress bar -->
            <div class="w-full h-1.5 bg-white/10 rounded-full mb-4 overflow-hidden">
                <div id="form-progress-line" class="h-full bg-evc-orange rounded-full" style="width: 0%;"></div>
            </div>
            <!-- Informations personnelles -->
            <h3 class="text-white text-lg font-bold mb-2 sticky top-0 z-10 bg-dark-secondary/90 backdrop-blur px-1 py-2 -mx-1 border-b border-white/5">Informations personnelles</h3>
            <label class="block text-sm text-gray-300">Nom complet :</label>
            <input type="text" name="nom_complet" class="form-input mb-3" value="{{ old('nom_complet') }}" required>

            <label class="block text-sm text-gray-300">Prénom :</label>
            <input type="text" name="prenom" class="form-input mb-3" value="{{ old('prenom') }}" required>

            <label class="block text-sm text-gray-300">Âge :</label>
            <input type="number" name="age" min="10" max="100" class="form-input mb-3" value="{{ old('age') }}" required>

            <label class="block text-sm text-gray-300">Date de naissance :</label>
            <input type="date" name="date_naissance" class="form-input mb-3" value="{{ old('date_naissance') }}" required>

            <label class="block text-sm text-gray-300">Sexe :</label>
            <select name="sexe" class="form-input mb-3" required>
                <option value="">-- Sélectionnez --</option>
                <option value="M" {{ old('sexe') === 'M' ? 'selected' : '' }}>Masculin</option>
                <option value="F" {{ old('sexe') === 'F' ? 'selected' : '' }}>Féminin</option>
            </select>

            <label class="block text-sm text-gray-300">Nationalité :</label>
            <input type="text" name="nationalite" class="form-input mb-3" value="{{ old('nationalite') }}" required>

            <label class="block text-sm text-gray-300">Photo de profil :</label>
            <input type="file" name="photo_profil" accept="image/*" class="form-input mb-4" required>

            <hr class="border-gray-700 my-4">

            <!-- Coordonnées -->
            <h3 class="text-white text-lg font-bold mb-2 sticky top-0 z-10 bg-dark-secondary/90 backdrop-blur px-1 py-2 -mx-1 border-b border-white/5">Coordonnées</h3>
            <label class="block text-sm text-gray-300">Email :</label>
            <input type="email" name="email" class="form-input mb-3" value="{{ old('email') }}" required>

            <label class="block text-sm text-gray-300">Numéro WhatsApp :</label>
            <input type="tel" name="whatsapp" placeholder="+225XXXXXXXX" class="form-input mb-3" value="{{ old('whatsapp') }}" required>

            <label class="block text-sm text-gray-300">Ville / Pays de résidence :</label>
            <input type="text" name="ville_pays" class="form-input mb-4" value="{{ old('ville_pays') }}" required>

            <hr class="border-gray-700 my-4">

            <!-- Informations académiques & professionnelles -->
            <h3 class="text-white text-lg font-bold mb-2 sticky top-0 z-10 bg-dark-secondary/90 backdrop-blur px-1 py-2 -mx-1 border-b border-white/5">Informations académiques & professionnelles</h3>
            <label class="block text-sm text-gray-300">Niveau d’étude :</label>
            <select name="niveau_etude" class="form-input mb-3" required>
                <option value="">-- Sélectionnez --</option>
                @php $niv = old('niveau_etude'); @endphp
                <option {{ ($niv==='Collège') ? 'selected' : '' }}>Collège</option>
                <option {{ ($niv==='Lycée') ? 'selected' : '' }}>Lycée</option>
                <option {{ ($niv==='Bac') ? 'selected' : '' }}>Bac</option>
                <option {{ ($niv==='Bac+2') ? 'selected' : '' }}>Bac+2</option>
                <option {{ ($niv==='Licence') ? 'selected' : '' }}>Licence</option>
                <option {{ ($niv==='Master') ? 'selected' : '' }}>Master</option>
                <option {{ ($niv==='Doctorat') ? 'selected' : '' }}>Doctorat</option>
            </select>

            <label class="block text-sm text-gray-300">Domaine d’étude ou spécialité :</label>
            <input type="text" name="domaine_etude" class="form-input mb-3" value="{{ old('domaine_etude') }}" required>

            <label class="block text-sm text-gray-300">Compétences déjà acquises :</label>
            <textarea name="competences" class="form-input mb-4" required>{{ old('competences') }}</textarea>

            <hr class="border-gray-700 my-4">

            <!-- Formation demandée -->
            <h3 class="text-white text-lg font-bold mb-2 sticky top-0 z-10 bg-dark-secondary/90 backdrop-blur px-1 py-2 -mx-1 border-b border-white/5">Formation demandée</h3>
            <label class="block text-sm text-gray-300">Programme souhaité :</label>
            <select name="programme" class="form-input mb-3" required>
                <option value="">-- Sélectionnez --</option>
                @php $prog = old('programme'); @endphp
                <option {{ ($prog==='Infographie') ? 'selected' : '' }}>Infographie</option>
                <option {{ ($prog==='Community Management') ? 'selected' : '' }}>Community Management</option>
                <option {{ ($prog==='Informatique') ? 'selected' : '' }}>Informatique</option>
            </select>

            <label class="block text-sm text-gray-300">Niveau actuel pour cette formation :</label>
            <select name="niveau_formation" class="form-input mb-3" required>
                <option value="">-- Sélectionnez --</option>
                @php $nivf = old('niveau_formation'); @endphp
                <option {{ ($nivf==='Aucune notion') ? 'selected' : '' }}>Aucune notion</option>
                <option {{ ($nivf==='Certaines notions') ? 'selected' : '' }}>Certaines notions</option>
                <option {{ ($nivf==='Monter en compétence') ? 'selected' : '' }}>Monter en compétence</option>
            </select>

            <label class="block text-sm text-gray-300">Motivation pour la formation :</label>
            <textarea name="motivation" class="form-input mb-3" required>{{ old('motivation') }}</textarea>

            <label class="block text-sm text-gray-300">Comment avez-vous connu l’EVC ?</label>
            <select name="origine" class="form-input mb-4" required>
                <option value="">-- Sélectionnez --</option>
                @php $orig = old('origine'); @endphp
                <option {{ ($orig==='Réseaux sociaux') ? 'selected' : '' }}>Réseaux sociaux</option>
                <option {{ ($orig==='Ami') ? 'selected' : '' }}>Ami</option>
                <option {{ ($orig==='Publicité') ? 'selected' : '' }}>Publicité</option>
                <option {{ ($orig==='Autre') ? 'selected' : '' }}>Autre</option>
            </select>

            <hr class="border-gray-700 my-4">

            <!-- Équipements disponibles -->
            <h3 class="text-white text-lg font-bold mb-2 sticky top-0 z-10 bg-dark-secondary/90 backdrop-blur px-1 py-2 -mx-1 border-b border-white/5">Équipements disponibles</h3>
            <label class="block text-sm text-gray-300">Avez-vous un ordinateur ?</label>
            <select name="ordinateur" class="form-input mb-3" required>
                <option value="">-- Sélectionnez --</option>
                @php $ordi = old('ordinateur'); @endphp
                <option {{ ($ordi==='Oui') ? 'selected' : '' }}>Oui</option>
                <option {{ ($ordi==='Non') ? 'selected' : '' }}>Non</option>
            </select>

            <label class="block text-sm text-gray-300">Avez-vous un smartphone ?</label>
            <select name="smartphone" class="form-input mb-4" required>
                <option value="">-- Sélectionnez --</option>
                @php $sp = old('smartphone'); @endphp
                <option {{ ($sp==='Oui') ? 'selected' : '' }}>Oui</option>
                <option {{ ($sp==='Non') ? 'selected' : '' }}>Non</option>
            </select>

            <label class="block text-sm text-gray-300">Disponibilités</label>
            <select name="disponibilite" class="form-input mb-4" required>
                <option value="">-- Sélectionnez --</option>
                @php $disp = old('disponibilite'); @endphp
                <option value="semaine_soir" {{ ($disp==='semaine_soir') ? 'selected' : '' }}>Semaine (soir)</option>
                <option value="weekend" {{ ($disp==='weekend') ? 'selected' : '' }}>Week‑end</option>
                <option value="flexible" {{ ($disp==='flexible') ? 'selected' : '' }}>Flexible</option>
            </select>

            <hr class="border-gray-700 my-4">

            <!-- Validation -->
            <h3 class="text-white text-lg font-bold mb-2 sticky top-0 z-10 bg-dark-secondary/90 backdrop-blur px-1 py-2 -mx-1 border-b border-white/5">Validation</h3>
            <label class="flex items-start gap-2 text-gray-200 mb-2">
                <input type="checkbox" name="veracite" class="mt-1 h-4 w-4" {{ old('veracite') ? 'checked' : '' }} required>
                <span>Je certifie que les informations fournies sont exactes.</span>
            </label>
            <label class="flex items-start gap-2 text-gray-200 mb-4">
                <input type="checkbox" name="consentement" class="mt-1 h-4 w-4" {{ old('consentement') ? 'checked' : '' }} required>
                <span>J’accepte que mes données soient utilisées dans le cadre du processus de candidature.</span>
            </label>

            <!-- Sticky submit bar -->
            <div class="sticky bottom-0 -mx-8 mt-6 bg-dark-secondary/95 backdrop-blur px-8 py-4 border-t border-white/5 flex items-center justify-between">
                <p id="form-progress-text" class="text-sm text-gray-300">Progression: 0%</p>
                <button type="submit" class="btn btn-primary">Soumettre ma candidature</button>
            </div>
