@extends('layouts.app')

@section('title', 'Test d\'éligibilité SAOP - École Virtuelle des Créatifs')
@section('description', 'Formulaire officiel du test d\'éligibilité SAOP pour les futurs étudiants EVC.')
@section('keywords', 'test éligibilité EVC, SAOP, admission EVC, orientation pédagogique')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    :root{--primary:#ff9800;--primary-dark:#f57c00;--primary-gradient:linear-gradient(135deg,#ff9800 0%,#fb8c00 100%);--accent:#ffb74d;--bg-dark:#0f172a;--bg-card:#1e293b;--text-primary:#f1f5f9;--text-secondary:#94a3b8;--border:#334155;--glow-orange:rgba(255,152,0,.3)}
    body{background:var(--bg-dark);font-family:'Inter',sans-serif;color:var(--text-primary)}
    .saop-page{min-height:100vh;padding:160px 20px 70px;max-width:1100px;margin:0 auto}
    .saop-hero{text-align:center;padding-top:60px;margin-bottom:34px}.saop-badge{display:inline-flex;align-items:center;gap:8px;padding:8px 18px;background:rgba(255,152,0,.1);border:1px solid rgba(255,152,0,.3);border-radius:999px;color:var(--primary);font-size:13px;font-weight:800;letter-spacing:.6px;box-shadow:0 0 24px var(--glow-orange);margin-bottom:20px}
    .saop-hero h1{font-size:clamp(34px,6vw,58px);font-weight:900;margin:0 0 14px;letter-spacing:-1.5px;background:linear-gradient(135deg,var(--primary) 0%,var(--accent) 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}.saop-hero h2{font-size:clamp(16px,2.5vw,23px);font-weight:800;color:var(--text-primary);margin:0 0 12px;text-transform:uppercase}.saop-hero p{max-width:850px;margin:0 auto;color:var(--text-secondary);font-size:16px;line-height:1.8}
    .saop-card{background:rgba(30,41,59,.94);border:1px solid var(--border);border-radius:18px;padding:30px;box-shadow:0 24px 70px rgba(0,0,0,.24);margin-bottom:22px}.saop-card.highlight{border-color:rgba(255,152,0,.38);background:linear-gradient(135deg,rgba(255,152,0,.11),rgba(30,41,59,.94))}.saop-section-title{display:flex;align-items:center;gap:12px;font-size:22px;font-weight:900;margin:0 0 18px;color:var(--text-primary)}.saop-section-title i{color:var(--primary)}.saop-card p{color:var(--text-secondary);line-height:1.85;font-size:15px}
    .timer-bar{position:sticky;top:105px;z-index:20;display:flex;justify-content:space-between;align-items:center;gap:14px;padding:16px 18px;border-radius:16px;background:rgba(15,23,42,.96);border:1px solid rgba(255,152,0,.35);box-shadow:0 18px 45px rgba(0,0,0,.25);margin-bottom:22px}.timer-label{color:var(--text-secondary);font-weight:700}.timer-value{font-size:24px;font-weight:900;color:var(--primary)}.timer-warning{color:#fca5a5!important}
    .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}.form-group{margin-bottom:18px}.form-label{display:block;font-size:14px;font-weight:800;margin-bottom:8px;color:var(--text-primary)}.required{color:var(--primary)}.form-control,.form-select{width:100%;padding:13px 15px;background:var(--bg-dark);border:1px solid var(--border);border-radius:10px;color:var(--text-primary);font-size:14px;font-family:'Inter',sans-serif;transition:.2s}.form-control:focus,.form-select:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(255,152,0,.12)}textarea.form-control{min-height:145px;resize:vertical}.form-select option{background:var(--bg-dark)}
    .question-item{padding:22px;border-radius:16px;background:rgba(15,23,42,.72);border:1px solid rgba(51,65,85,.9);margin-bottom:16px}.question-head{display:flex;gap:14px;align-items:flex-start;margin-bottom:14px}.question-number{width:44px;height:44px;min-width:44px;border-radius:14px;display:flex;align-items:center;justify-content:center;background:var(--primary-gradient);color:#111827;font-weight:900}.question-text{color:var(--text-primary);line-height:1.7;font-size:16px;margin:0;font-weight:700}
    .alert-success,.alert-error{border-radius:12px;padding:14px 16px;margin-bottom:22px;display:flex;gap:12px;align-items:flex-start;font-size:14px}.alert-success{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3);color:#86efac}.alert-error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#fca5a5}
    .submit-btn{width:100%;padding:16px 24px;background:var(--primary-gradient);border:0;border-radius:999px;color:#111827;font-size:16px;font-weight:900;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px;box-shadow:0 16px 35px rgba(255,152,0,.22);transition:.2s}.submit-btn:hover{transform:translateY(-2px)}.submit-btn:disabled{opacity:.55;cursor:not-allowed;transform:none}.helper-text{font-size:13px;color:var(--text-secondary);margin-top:6px}.progress-note{text-align:center;color:var(--text-secondary);font-size:14px;margin:14px 0 0}
    @media(max-width:760px){.saop-page{padding:140px 16px 50px}.saop-hero{padding-top:38px}.saop-card{padding:22px}.form-grid{grid-template-columns:1fr}.timer-bar{top:92px;align-items:flex-start;flex-direction:column}.timer-value{font-size:22px}}
</style>
@endpush

@section('content')
<div class="saop-page">
    <section class="saop-hero">
        <span class="saop-badge"><i class="fas fa-clipboard-check"></i> SAOP - ADMISSION & ORIENTATION</span>
        <h1>Test d’éligibilité</h1>
        <h2>Direction des Études et de Régulation Pédagogique</h2>
        <p>Vous disposez de <strong>1 heure</strong> pour compléter ce diagnostic obligatoire. Répondez avec précision : vos réponses seront analysées par l’équipe pédagogique EVC.</p>
    </section>

    @if(session('success'))
        <div class="alert-success"><i class="fas fa-check-circle"></i><div>{{ session('success') }}</div></div>
    @endif

    @if($errors->any())
        <div class="alert-error"><i class="fas fa-exclamation-circle"></i><div><strong>Veuillez corriger :</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>
    @endif

    <div class="timer-bar">
        <div>
            <div class="timer-label">Temps imparti pour finaliser le test</div>
            <div class="helper-text">À la fin du délai, les réponses déjà saisies seront enregistrées automatiquement.</div>
        </div>
        <div id="saopTimer" class="timer-value">01:00:00</div>
    </div>

    <article class="saop-card highlight">
        <h3 class="saop-section-title"><i class="fas fa-shield-halved"></i> Parcours officiel d'intégration et d'orientation</h3>
        <p>Le système EVC Admission et d'Orientation est le pilier de notre charte qualité. Il ne s'agit pas d'un simple test, mais d'un diagnostic obligatoire pour chaque futur étudiant.</p>
    </article>

    <form id="saopForm" action="{{ route('eligibilite.saop.store') }}" method="POST">
        @csrf
        <input type="hidden" name="started_at" id="startedAt" value="{{ old('started_at') }}">
        <input type="hidden" name="auto_submit" id="autoSubmit" value="0">

        <article class="saop-card">
            <h3 class="saop-section-title"><i class="fas fa-user"></i> Informations du candidat</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nom complet <span class="required">*</span></label>
                    <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">WhatsApp</label>
                    <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp') }}" placeholder="+225 ...">
                </div>
                <div class="form-group">
                    <label class="form-label">Formation visée</label>
                    <select name="formation" class="form-select">
                        <option value="">Sélectionnez</option>
                        <option value="design_graphique" @selected(old('formation')==='design_graphique')>Design Graphique</option>
                        <option value="community_management" @selected(old('formation')==='community_management')>Community Management</option>
                        <option value="design_graphique_community_manager" @selected(old('formation')==='design_graphique_community_manager')>Design Graphique & Community Manager</option>
                        <option value="gestion_informatique" @selected(old('formation')==='gestion_informatique')>Gestion Informatique</option>
                        <option value="intelligence_artificielle" @selected(old('formation')==='intelligence_artificielle')>Intelligence Artificielle</option>
                    </select>
                </div>
            </div>
        </article>

        <article class="saop-card">
            <h3 class="saop-section-title"><i class="fas fa-list-check"></i> Questionnaire officiel de validation</h3>
            <p>Les 10 réponses sont obligatoires. Soyez concret, honnête et précis.</p>

            @foreach($questions as $index => $question)
                <div class="question-item">
                    <div class="question-head">
                        <div class="question-number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                        <p class="question-text">{{ $question }}</p>
                    </div>
                    <textarea name="answers[{{ $index }}]" class="form-control answer-field" required minlength="10" placeholder="Votre réponse détaillée...">{{ old('answers.' . $index) }}</textarea>
                </div>
            @endforeach

            <button type="submit" id="submitBtn" class="submit-btn"><i class="fas fa-paper-plane"></i> Soumettre mon test d’éligibilité</button>
            <div class="progress-note">Après soumission, vos réponses seront disponibles dans le dashboard administrateur.</div>
        </article>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const limit = 3600;
    const storageKey = 'saop_test_started_at';
    const startedInput = document.getElementById('startedAt');
    const timer = document.getElementById('saopTimer');
    const form = document.getElementById('saopForm');
    const submitBtn = document.getElementById('submitBtn');
    const autoSubmitInput = document.getElementById('autoSubmit');
    let isSubmitting = false;
    let startedAt = startedInput.value || localStorage.getItem(storageKey);

    if (!startedAt) {
        startedAt = new Date().toISOString();
        localStorage.setItem(storageKey, startedAt);
    }
    startedInput.value = startedAt;

    function pad(n) { return String(n).padStart(2, '0'); }
    function tick() {
        const elapsed = Math.floor((Date.now() - new Date(startedAt).getTime()) / 1000);
        const remaining = Math.max(0, limit - elapsed);
        const h = Math.floor(remaining / 3600);
        const m = Math.floor((remaining % 3600) / 60);
        const s = remaining % 60;
        timer.textContent = `${pad(h)}:${pad(m)}:${pad(s)}`;
        if (remaining <= 300) timer.classList.add('timer-warning');
        if (remaining <= 0 && !isSubmitting) {
            isSubmitting = true;
            autoSubmitInput.value = '1';
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-clock"></i> Temps écoulé - enregistrement...';
            form.querySelectorAll('textarea[required]').forEach(el => el.removeAttribute('required'));
            form.submit();
            clearInterval(interval);
        }
    }

    form.addEventListener('submit', function () {
        isSubmitting = true;
        localStorage.removeItem(storageKey);
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
    });

    tick();
    const interval = setInterval(tick, 1000);
});
</script>
@endpush
