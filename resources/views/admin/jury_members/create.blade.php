@extends('layouts.admin')

@section('title', 'Ajouter un membre du jury')

@push('styles')
<style>
    .create-page { background:#0b1120; min-height:100vh; padding:2rem 1.5rem; }

    .page-header { display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; flex-wrap:wrap; margin-bottom:2rem; }
    .page-header h1 { font-size:1.4rem; font-weight:700; color:#f1f5f9; margin:0 0 .25rem; }
    .page-header p  { color:#64748b; margin:0; font-size:.875rem; }

    .form-card {
        background:#1e293b;
        border:1px solid #334155;
        border-radius:16px;
        overflow:hidden;
        max-width:860px;
        margin:0 auto;
    }
    .form-card-header {
        background:#0f172a;
        border-bottom:1px solid #334155;
        padding:1.1rem 1.5rem;
        display:flex;
        align-items:center;
        gap:.75rem;
    }
    .form-card-header .icon {
        width:38px; height:38px; border-radius:9px;
        background:linear-gradient(135deg,#f59e0b,#d97706);
        display:flex; align-items:center; justify-content:center;
        font-size:1.1rem; flex-shrink:0;
    }
    .form-card-header span { font-weight:700; color:#f1f5f9; font-size:1rem; }
    .form-card-body { padding:1.75rem; }

    .section-sep {
        font-size:.7rem; font-weight:700; text-transform:uppercase;
        letter-spacing:.1em; color:#475569;
        border-bottom:1px solid #334155;
        padding-bottom:.5rem; margin-bottom:1.25rem; margin-top:1.75rem;
    }
    .section-sep:first-child { margin-top:0; }

    .f-label {
        display:block; font-size:.8rem; font-weight:600;
        color:#94a3b8; margin-bottom:.4rem;
    }
    .f-hint { font-size:.73rem; color:#475569; margin-top:.3rem; }

    .f-input {
        width:100%; background:#0f172a; border:1px solid #334155;
        border-radius:8px; padding:.65rem 1rem; font-size:.875rem;
        color:#f1f5f9; outline:none; transition:border-color .2s;
        font-family:inherit;
    }
    .f-input:focus { border-color:#f59e0b; }
    .f-input::placeholder { color:#475569; }

    .f-input-group { display:flex; gap:0; }
    .f-input-group .f-input { border-radius:8px 0 0 8px; flex:1; }
    .f-input-group .icon-btn {
        background:#1e293b; border:1px solid #334155; border-left:none;
        color:#94a3b8; padding:.65rem .9rem; cursor:pointer;
        transition:all .2s; font-size:.85rem;
    }
    .f-input-group .icon-btn:last-child { border-radius:0 8px 8px 0; }
    .f-input-group .icon-btn:hover { background:#334155; color:#f1f5f9; }

    .identifier-box {
        background:#0f172a; border:1px solid #334155; border-radius:10px;
        padding:1rem 1.25rem; display:flex; align-items:center;
        justify-content:space-between; gap:1rem; flex-wrap:wrap;
    }
    .identifier-val {
        font-family:monospace; font-size:1.15rem; font-weight:700;
        color:#f59e0b; letter-spacing:.05em; word-break:break-all;
    }
    .identifier-actions { display:flex; gap:.5rem; flex-shrink:0; }

    .toggle-wrap {
        display:flex; align-items:center; gap:.75rem;
        background:#0f172a; border:1px solid #334155;
        border-radius:8px; padding:.75rem 1rem; cursor:pointer;
    }
    .toggle-wrap input[type=checkbox] { display:none; }
    .toggle-track {
        width:44px; height:24px; border-radius:12px;
        background:#334155; position:relative; transition:background .2s; flex-shrink:0;
    }
    .toggle-track::after {
        content:''; position:absolute; top:3px; left:3px;
        width:18px; height:18px; border-radius:9px;
        background:#fff; transition:transform .2s;
    }
    input[type=checkbox]:checked ~ .toggle-track { background:#22c55e; }
    input[type=checkbox]:checked ~ .toggle-track::after { transform:translateX(20px); }
    .toggle-label { font-size:.875rem; color:#cbd5e1; font-weight:500; }

    .photo-drop {
        border:2px dashed #334155; border-radius:10px;
        padding:1.5rem; text-align:center; cursor:pointer;
        transition:border-color .2s; position:relative;
    }
    .photo-drop:hover { border-color:#f59e0b; }
    .photo-drop input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
    .photo-drop-icon { font-size:2rem; margin-bottom:.5rem; }
    .photo-drop-text { font-size:.8rem; color:#64748b; }
    .photo-preview { max-height:120px; border-radius:8px; margin-top:.75rem; display:none; }

    .btn-create {
        background:linear-gradient(135deg,#f59e0b,#d97706);
        color:#000; font-weight:700; border:none;
        padding:.75rem 2rem; border-radius:8px; font-size:.95rem;
        cursor:pointer; transition:opacity .2s;
    }
    .btn-create:hover { opacity:.9; }
    .btn-back {
        background:#1e293b; color:#94a3b8;
        border:1px solid #334155; padding:.7rem 1.4rem;
        border-radius:8px; font-size:.875rem; font-weight:600;
        text-decoration:none; transition:all .2s;
    }
    .btn-back:hover { border-color:#f59e0b; color:#f59e0b; }

    .alert-err {
        background:#450a0a; border:1px solid #7f1d1d;
        border-radius:10px; padding:1rem 1.25rem;
        color:#fca5a5; font-size:.875rem; margin-bottom:1.5rem;
        max-width:860px; margin-left:auto; margin-right:auto;
    }
    .alert-err ul { margin:0; padding-left:1.25rem; }

    @media (max-width:600px) {
        .create-page { padding:1rem; }
        .form-card-body { padding:1.25rem; }
        .btn-create { width:100%; }
    }
</style>
@endpush

@section('content')
<div class="create-page">

    {{-- Header --}}
    <div class="page-header">
        <div>
            <h1>👔 Ajouter un membre du jury</h1>
            <p>Studio Créatif — Nouveau profil jury</p>
        </div>
        <a href="{{ route('admin.jury-members.index') }}" class="btn-back">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
    </div>

    @if($errors->any())
        <div class="alert-err">
            <strong>⚠️ Erreurs de validation :</strong>
            <ul class="mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <div class="form-card-header">
            <div class="icon">👤</div>
            <span>Informations du membre</span>
        </div>
        <div class="form-card-body">
            <form method="POST" action="{{ route('admin.jury-members.store') }}" enctype="multipart/form-data" id="createForm">
                @csrf

                {{-- Identité --}}
                <div class="section-sep">Identité</div>
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <label class="f-label" for="name">Nom complet <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="name" name="name" class="f-input"
                            value="{{ old('name') }}" placeholder="Ex : Jean Dupont" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="f-label" for="title">Fonction / Titre</label>
                        <input type="text" id="title" name="title" class="f-input"
                            value="{{ old('title') }}" placeholder="Ex : Directeur Artistique">
                    </div>
                    <div class="col-12 col-md-7">
                        <label class="f-label" for="country">Pays</label>
                        <input type="text" id="country" name="country" class="f-input"
                            value="{{ old('country') }}" placeholder="Ex : Côte d'Ivoire">
                    </div>
                    <div class="col-12 col-md-5">
                        <label class="f-label" for="flag">Drapeau (emoji)</label>
                        <input type="text" id="flag" name="flag" class="f-input"
                            value="{{ old('flag') }}" placeholder="🇨🇮">
                    </div>
                </div>

                {{-- Identifiant --}}
                <div class="section-sep">Identifiant de connexion jury</div>
                <label class="f-label">Identifiant unique <span style="color:#ef4444;">*</span></label>
                <div class="identifier-box mb-1">
                    <div class="identifier-val" id="identDisplay">—</div>
                    <div class="identifier-actions">
                        <button type="button" class="icon-btn" id="regenBtn" title="Regénérer" style="background:#1e293b;border:1px solid #334155;border-radius:6px;color:#94a3b8;padding:.4rem .7rem;cursor:pointer;">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button type="button" class="icon-btn" id="copyBtn" title="Copier" style="background:#1e293b;border:1px solid #334155;border-radius:6px;color:#94a3b8;padding:.4rem .7rem;cursor:pointer;">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                <input type="hidden" name="unique_identifier" id="unique_identifier" value="{{ old('unique_identifier') }}" required>
                <div class="f-hint">🔐 Partagez cet identifiant avec le membre du jury pour qu'il accède au formulaire de notation.</div>

                {{-- Photo --}}
                <div class="section-sep">Photo de profil</div>
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <label class="f-label">Uploader une photo</label>
                        <div class="photo-drop" id="photoDrop">
                            <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png,image/webp">
                            <div class="photo-drop-icon">📷</div>
                            <div class="photo-drop-text">Cliquez ou glissez une image ici<br><small>JPG, PNG, WEBP — max 4 Mo</small></div>
                            <img class="photo-preview" id="photoPreview" src="" alt="Aperçu">
                        </div>
                    </div>
                    <div class="col-12 col-md-6" style="display:flex;flex-direction:column;justify-content:center;">
                        <label class="f-label" for="image_url">Ou URL image externe</label>
                        <input type="url" id="image_url" name="image_url" class="f-input"
                            value="{{ old('image_url') }}" placeholder="https://exemple.com/photo.jpg">
                        <div class="f-hint mt-2">Si les deux sont renseignés, la photo uploadée est prioritaire.</div>
                    </div>
                </div>

                {{-- Paramètres --}}
                <div class="section-sep">Paramètres</div>
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-4">
                        <label class="f-label" for="sort_order">Ordre d'affichage</label>
                        <input type="number" id="sort_order" name="sort_order" class="f-input"
                            value="{{ old('sort_order', 0) }}" min="0" placeholder="0">
                        <div class="f-hint">Plus petit = affiché en premier</div>
                    </div>
                    <div class="col-12 col-md-8" style="display:flex;align-items:flex-end;">
                        <label class="toggle-wrap" for="is_active" style="width:100%;">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}>
                            <span class="toggle-track"></span>
                            <span class="toggle-label">Afficher sur la page publique du jury</span>
                        </label>
                    </div>
                </div>

                {{-- Actions --}}
                <div style="display:flex;gap:1rem;align-items:center;flex-wrap:wrap;padding-top:.5rem;border-top:1px solid #334155;">
                    <button type="submit" class="btn-create">
                        <i class="fas fa-plus me-2"></i>Créer le membre
                    </button>
                    <a href="{{ route('admin.jury-members.index') }}" class="btn-back">Annuler</a>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    /* ---- Génération identifiant ---- */
    function generateId() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        const part = (n) => Array.from({length:n}, () => chars[Math.floor(Math.random()*chars.length)]).join('');
        return `EVC-JURY-${part(4)}-${part(4)}`;
    }

    const identHidden  = document.getElementById('unique_identifier');
    const identDisplay = document.getElementById('identDisplay');
    const regenBtn     = document.getElementById('regenBtn');
    const copyBtn      = document.getElementById('copyBtn');

    function setIdent(val) {
        identHidden.value   = val;
        identDisplay.textContent = val;
    }

    setIdent(identHidden.value || generateId());

    regenBtn.addEventListener('click', () => {
        setIdent(generateId());
        identDisplay.style.color = '#4ade80';
        setTimeout(() => identDisplay.style.color = '#f59e0b', 700);
    });

    copyBtn.addEventListener('click', () => {
        navigator.clipboard.writeText(identHidden.value).then(() => {
            copyBtn.innerHTML = '<i class="fas fa-check" style="color:#4ade80;"></i>';
            setTimeout(() => copyBtn.innerHTML = '<i class="fas fa-copy"></i>', 1500);
        });
    });

    /* ---- Toggle visuel checkbox ---- */
    document.querySelectorAll('.toggle-wrap').forEach(wrap => {
        const cb    = wrap.querySelector('input[type=checkbox]');
        const track = wrap.querySelector('.toggle-track');
        function sync() { track.style.background = cb.checked ? '#22c55e' : '#334155'; }
        sync();
        cb.addEventListener('change', sync);
        wrap.addEventListener('click', (e) => {
            if (e.target !== cb) { cb.checked = !cb.checked; sync(); }
        });
    });

    /* ---- Aperçu photo ---- */
    const photoInput   = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');
    const photoDrop    = document.getElementById('photoDrop');

    photoInput.addEventListener('change', () => {
        const file = photoInput.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            photoPreview.src = e.target.result;
            photoPreview.style.display = 'block';
            photoDrop.querySelector('.photo-drop-icon').style.display = 'none';
            photoDrop.querySelector('.photo-drop-text').style.display = 'none';
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush

