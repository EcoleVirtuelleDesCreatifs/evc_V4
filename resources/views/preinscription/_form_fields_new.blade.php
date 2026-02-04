<h3 style="font-size: 18px; font-weight: 600; color: var(--text-primary); margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid var(--border); display: flex; align-items: center; gap: 10px;">
    <i class="fas fa-user" style="color: var(--accent);"></i>
    Informations personnelles
</h3>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Nom complet <span class="required">*</span></label>
        <input type="text" name="nom_complet" class="form-control" placeholder="Votre nom complet" value="{{ old('nom_complet') }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Prénom <span class="required">*</span></label>
        <input type="text" name="prenom" class="form-control" placeholder="Votre prénom" value="{{ old('prenom') }}" required>
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Âge <span class="required">*</span></label>
        <input type="number" name="age" min="10" max="100" class="form-control" placeholder="Votre âge" value="{{ old('age') }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Date de naissance <span class="required">*</span></label>
        <input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance') }}" required>
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Sexe <span class="required">*</span></label>
        <select name="sexe" class="form-select" required>
            <option value="">Sélectionnez</option>
            <option value="M" {{ old('sexe') === 'M' ? 'selected' : '' }}>Masculin</option>
            <option value="F" {{ old('sexe') === 'F' ? 'selected' : '' }}>Féminin</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Nationalité <span class="required">*</span></label>
        <input type="text" name="nationalite" class="form-control" placeholder="Ex: Ivoirienne" value="{{ old('nationalite') }}" required>
    </div>
</div>

<div class="form-group">
    <label class="form-label">Photo de profil <span class="required">*</span></label>
    <input type="file" name="photo_profil" accept="image/*" class="form-control" required>
</div>

<h3 style="font-size: 18px; font-weight: 600; color: var(--text-primary); margin: 32px 0 20px; padding-bottom: 12px; border-bottom: 2px solid var(--border); display: flex; align-items: center; gap: 10px;">
    <i class="fas fa-address-book" style="color: var(--accent);"></i>
    Coordonnées
</h3>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Email <span class="required">*</span></label>
        <input type="email" name="email" class="form-control" placeholder="votre@email.com" value="{{ old('email') }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Numéro WhatsApp <span class="required">*</span></label>
        <input type="tel" name="whatsapp" class="form-control" placeholder="+225 XX XX XX XX" value="{{ old('whatsapp') }}" required>
    </div>
</div>

<div class="form-group">
    <label class="form-label">Ville / Pays de résidence <span class="required">*</span></label>
    <input type="text" name="ville_pays" class="form-control" placeholder="Ex: Abidjan, Côte d'Ivoire" value="{{ old('ville_pays') }}" required>
</div>

<h3 style="font-size: 18px; font-weight: 600; color: var(--text-primary); margin: 32px 0 20px; padding-bottom: 12px; border-bottom: 2px solid var(--border); display: flex; align-items: center; gap: 10px;">
    <i class="fas fa-graduation-cap" style="color: var(--accent);"></i>
    Informations académiques & professionnelles
</h3>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Niveau d'étude <span class="required">*</span></label>
        <select name="niveau_etude" class="form-select" required>
            <option value="">Sélectionnez</option>
            @php $niv = old('niveau_etude'); @endphp
            <option {{ ($niv==='Collège') ? 'selected' : '' }}>Collège</option>
            <option {{ ($niv==='Lycée') ? 'selected' : '' }}>Lycée</option>
            <option {{ ($niv==='Bac') ? 'selected' : '' }}>Bac</option>
            <option {{ ($niv==='Bac+2') ? 'selected' : '' }}>Bac+2</option>
            <option {{ ($niv==='Licence') ? 'selected' : '' }}>Licence</option>
            <option {{ ($niv==='Master') ? 'selected' : '' }}>Master</option>
            <option {{ ($niv==='Doctorat') ? 'selected' : '' }}>Doctorat</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Domaine d'étude ou spécialité <span class="required">*</span></label>
        <input type="text" name="domaine_etude" class="form-control" placeholder="Ex: Informatique, Commerce, etc." value="{{ old('domaine_etude') }}" required>
    </div>
</div>

<div class="form-group">
    <label class="form-label">Compétences déjà acquises <span class="required">*</span></label>
    <textarea name="competences" class="form-control" placeholder="Décrivez vos compétences actuelles..." required>{{ old('competences') }}</textarea>
</div>

<h3 style="font-size: 18px; font-weight: 600; color: var(--text-primary); margin: 32px 0 20px; padding-bottom: 12px; border-bottom: 2px solid var(--border); display: flex; align-items: center; gap: 10px;">
    <i class="fas fa-book-open" style="color: var(--accent);"></i>
    Formation demandée
</h3>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Programme souhaité <span class="required">*</span></label>
        <select name="programme" class="form-select" required>
            <option value="">Sélectionnez</option>
            @php $prog = old('programme'); @endphp
            <option value="design-graphique" {{ ($prog==='design-graphique') ? 'selected' : '' }}>Design Graphique</option>
            <option value="community-manager" {{ ($prog==='community-manager') ? 'selected' : '' }}>Community Manager</option>
            <option value="design-graphique-community-manager" {{ ($prog==='design-graphique-community-manager') ? 'selected' : '' }}>Design Graphique & Community Manager</option>
            <option value="intelligence-artificielle" {{ ($prog==='intelligence-artificielle') ? 'selected' : '' }}>Intelligence Artificielle</option>
            <option value="gestion-informatique" {{ ($prog==='gestion-informatique') ? 'selected' : '' }}>Gestion Informatique</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Niveau actuel pour cette formation <span class="required">*</span></label>
        <select name="niveau_formation" class="form-select" required>
            <option value="">Sélectionnez</option>
            @php $nivf = old('niveau_formation'); @endphp
            <option {{ ($nivf==='Aucune notion') ? 'selected' : '' }}>Aucune notion</option>
            <option {{ ($nivf==='Certaines notions') ? 'selected' : '' }}>Certaines notions</option>
            <option {{ ($nivf==='Monter en compétence') ? 'selected' : '' }}>Monter en compétence</option>
        </select>
    </div>
</div>

<div class="form-group">
    <label class="form-label">Motivation pour la formation <span class="required">*</span></label>
    <textarea name="motivation" class="form-control" placeholder="Parlez-nous de votre motivation..." required>{{ old('motivation') }}</textarea>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Date à laquelle vous souhaitez procéder au paiement <span class="required">*</span></label>
        <input type="date" name="date_inscription_souhaitee" class="form-control" value="{{ old('date_inscription_souhaitee') }}" required>
    </div>
</div>

<div class="form-group">
    <label class="form-label">Comment avez-vous connu l'EVC ? <span class="required">*</span></label>
    <select name="origine" class="form-select" required>
        <option value="">Sélectionnez</option>
        @php $orig = old('origine'); @endphp
        <option {{ ($orig==='Réseaux sociaux') ? 'selected' : '' }}>Réseaux sociaux</option>
        <option {{ ($orig==='Ami') ? 'selected' : '' }}>Ami</option>
        <option {{ ($orig==='Publicité') ? 'selected' : '' }}>Publicité</option>
        <option {{ ($orig==='Autre') ? 'selected' : '' }}>Autre</option>
    </select>
</div>

<h3 style="font-size: 18px; font-weight: 600; color: var(--text-primary); margin: 32px 0 20px; padding-bottom: 12px; border-bottom: 2px solid var(--border); display: flex; align-items: center; gap: 10px;">
    <i class="fas fa-laptop" style="color: var(--accent);"></i>
    Équipements disponibles
</h3>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Avez-vous un ordinateur ? <span class="required">*</span></label>
        <select name="ordinateur" class="form-select" required>
            <option value="">Sélectionnez</option>
            @php $ordi = old('ordinateur'); @endphp
            <option {{ ($ordi==='Oui') ? 'selected' : '' }}>Oui</option>
            <option {{ ($ordi==='Non') ? 'selected' : '' }}>Non</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Avez-vous un smartphone ? <span class="required">*</span></label>
        <select name="smartphone" class="form-select" required>
            <option value="">Sélectionnez</option>
            @php $sp = old('smartphone'); @endphp
            <option {{ ($sp==='Oui') ? 'selected' : '' }}>Oui</option>
            <option {{ ($sp==='Non') ? 'selected' : '' }}>Non</option>
        </select>
    </div>
</div>

<div class="form-group">
    <label class="form-label">Disponibilités <span class="required">*</span></label>
    <select name="disponibilite" class="form-select" required>
        <option value="">Sélectionnez</option>
        @php $disp = old('disponibilite'); @endphp
        <option value="semaine_soir" {{ ($disp==='semaine_soir') ? 'selected' : '' }}>Semaine (soir)</option>
        <option value="weekend" {{ ($disp==='weekend') ? 'selected' : '' }}>Week-end</option>
        <option value="flexible" {{ ($disp==='flexible') ? 'selected' : '' }}>Flexible</option>
    </select>
</div>

<h3 style="font-size: 18px; font-weight: 600; color: var(--text-primary); margin: 32px 0 20px; padding-bottom: 12px; border-bottom: 2px solid var(--border); display: flex; align-items: center; gap: 10px;">
    <i class="fas fa-check-circle" style="color: var(--accent);"></i>
    Validation
</h3>

<div class="form-group">
    <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer;">
        <input type="checkbox" name="veracite" {{ old('veracite') ? 'checked' : '' }} required style="margin-top: 4px;">
        <span style="color: var(--text-secondary); font-size: 14px;">Je certifie que les informations fournies sont exactes.</span>
    </label>
</div>

<div class="form-group">
    <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer;">
        <input type="checkbox" name="consentement" {{ old('consentement') ? 'checked' : '' }} required style="margin-top: 4px;">
        <span style="color: var(--text-secondary); font-size: 14px;">J'accepte que mes données soient utilisées dans le cadre du processus de candidature.</span>
    </label>
</div>

<button type="submit" class="submit-btn">
    <i class="fas fa-paper-plane"></i>
    <span>Soumettre ma candidature</span>
</button>
