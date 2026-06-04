@extends('layouts.admin')

@section('title', 'Modifier — ' . $juryMember->name)

@push('styles')
<style>
    .edit-page { background:#0b1120; min-height:100vh; padding:2rem 1.5rem; }

    .page-header { display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; flex-wrap:wrap; margin-bottom:2rem; }
    .page-header h1 { font-size:1.4rem; font-weight:700; color:#f1f5f9; margin:0 0 .25rem; }
    .page-header p  { color:#64748b; margin:0; font-size:.875rem; }

    .layout { display:grid; grid-template-columns:260px 1fr; gap:1.5rem; max-width:1000px; margin:0 auto; align-items:start; }

    /* Sidebar profil */
    .profile-card {
        background:#1e293b; border:1px solid #334155; border-radius:16px; overflow:hidden; position:sticky; top:1.5rem;
    }
    .profile-card-top {
        background:linear-gradient(135deg,#1e3a5f,#0f172a);
        padding:1.75rem 1.25rem; text-align:center; border-bottom:1px solid #334155;
    }
    .profile-avatar {
        width:90px; height:90px; border-radius:50%; object-fit:cover; object-position:top;
        border:3px solid #f59e0b; margin:0 auto .75rem; display:block;
    }
    .profile-avatar-placeholder {
        width:90px; height:90px; border-radius:50%; background:#334155;
        display:flex; align-items:center; justify-content:center;
        font-size:2rem; margin:0 auto .75rem; border:3px solid #f59e0b;
    }
    .profile-name { font-weight:700; color:#f1f5f9; font-size:1rem; margin-bottom:.2rem; }
    .profile-title { font-size:.78rem; color:#94a3b8; }
    .profile-card-body { padding:1.1rem 1.25rem; }
    .profile-meta { display:flex; flex-direction:column; gap:.6rem; }
    .profile-meta-row { display:flex; justify-content:space-between; align-items:center; font-size:.78rem; }
    .profile-meta-label { color:#64748b; }
    .profile-meta-val { color:#f1f5f9; font-weight:600; }
    .identifier-display {
        font-family:monospace; font-size:.78rem; color:#f59e0b;
        background:#0f172a; border:1px solid #334155; border-radius:6px;
        padding:.35rem .6rem; word-break:break-all; display:flex;
        align-items:center; justify-content:space-between; gap:.5rem; margin-top:.5rem;
    }
    .copy-mini { background:none; border:none; color:#64748b; cursor:pointer; font-size:.8rem; flex-shrink:0; }
    .copy-mini:hover { color:#f59e0b; }
    .badge-active { background:#14532d; color:#4ade80; border:1px solid #166534; border-radius:20px; padding:2px 10px; font-size:.72rem; font-weight:700; }
    .badge-inactive { background:#450a0a; color:#f87171; border:1px solid #7f1d1d; border-radius:20px; padding:2px 10px; font-size:.72rem; font-weight:700; }

    .delete-btn {
        display:flex; align-items:center; justify-content:center; gap:.4rem;
        width:100%; padding:.65rem; margin-top:1rem; border:1px solid #7f1d1d;
        background:transparent; border-radius:8px; color:#f87171; font-size:.8rem;
        font-weight:600; cursor:pointer; transition:all .2s;
    }
    .delete-btn:hover { background:#450a0a; }

    /* Formulaire */
    .form-card { background:#1e293b; border:1px solid #334155; border-radius:16px; overflow:hidden; }
    .form-card-header {
        background:#0f172a; border-bottom:1px solid #334155; padding:1.1rem 1.5rem;
        display:flex; align-items:center; gap:.75rem;
    }
    .form-card-header .hicon {
        width:38px; height:38px; border-radius:9px;
        background:linear-gradient(135deg,#3b82f6,#2563eb);
        display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0;
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

    .f-label { display:block; font-size:.8rem; font-weight:600; color:#94a3b8; margin-bottom:.4rem; }
    .f-hint  { font-size:.73rem; color:#475569; margin-top:.3rem; }

    .f-input {
        width:100%; background:#0f172a; border:1px solid #334155;
        border-radius:8px; padding:.65rem 1rem; font-size:.875rem;
        color:#f1f5f9; outline:none; transition:border-color .2s; font-family:inherit;
    }
    .f-input:focus { border-color:#3b82f6; }
    .f-input::placeholder { color:#475569; }

    .toggle-wrap {
        display:flex; align-items:center; gap:.75rem;
        background:#0f172a; border:1px solid #334155;
        border-radius:8px; padding:.75rem 1rem; cursor:pointer; width:100%;
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
    .toggle-label { font-size:.875rem; color:#cbd5e1; font-weight:500; }

    .photo-drop {
        border:2px dashed #334155; border-radius:10px; padding:1.25rem;
        text-align:center; cursor:pointer; transition:border-color .2s; position:relative;
    }
    .photo-drop:hover { border-color:#3b82f6; }
    .photo-drop input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
    .photo-drop-icon { font-size:1.5rem; margin-bottom:.4rem; }
    .photo-drop-text { font-size:.75rem; color:#64748b; }
    .photo-preview { max-height:100px; border-radius:8px; margin-top:.6rem; display:none; }

    .btn-save {
        background:linear-gradient(135deg,#3b82f6,#2563eb);
        color:#fff; font-weight:700; border:none;
        padding:.75rem 2rem; border-radius:8px; font-size:.95rem;
        cursor:pointer; transition:opacity .2s;
    }
    .btn-save:hover { opacity:.9; }
    .btn-back {
        background:#1e293b; color:#94a3b8;
        border:1px solid #334155; padding:.7rem 1.4rem;
        border-radius:8px; font-size:.875rem; font-weight:600;
        text-decoration:none; transition:all .2s;
    }
    .btn-back:hover { border-color:#3b82f6; color:#3b82f6; }

    .alert-err {
        background:#450a0a; border:1px solid #7f1d1d; border-radius:10px;
        padding:1rem 1.25rem; color:#fca5a5; font-size:.875rem; margin-bottom:1.5rem;
    }
    .alert-err ul { margin:0; padding-left:1.25rem; }
    .alert-ok {
        background:#14532d; border:1px solid #166534; border-radius:10px;
        padding:1rem 1.25rem; color:#4ade80; font-size:.875rem; margin-bottom:1.5rem;
    }

    @media (max-width:768px) {
        .layout { grid-template-columns:1fr; }
        .profile-card { position:static; }
        .edit-page { padding:1rem; }
        .form-card-body { padding:1.25rem; }
        .btn-save { width:100%; }
    }
</style>
@endpush

@section('content')
<div class="edit-page">

    {{-- Header --}}
    <div class="page-header" style="max-width:1000px;margin:0 auto 2rem;">
        <div>
            <h1>✏️ Modifier un membre du jury</h1>
            <p>Mise à jour du profil de <strong style="color:#f1f5f9;">{{ $juryMember->name }}</strong></p>
        </div>
        <a href="{{ route('admin.jury-members.index') }}" class="btn-back">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
    </div>

    @if(session('success'))
        <div class="alert-ok" style="max-width:1000px;margin:0 auto 1.5rem;">✅ {{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-err" style="max-width:1000px;margin:0 auto 1.5rem;">
            <strong>⚠️ Erreurs :</strong>
            <ul class="mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="layout">

        {{-- Sidebar profil --}}
        <div class="profile-card">
            <div class="profile-card-top">
                @if($juryMember->photo_url)
                    <img src="{{ $juryMember->photo_url }}" alt="{{ $juryMember->name }}" class="profile-avatar">
                @else
                    <div class="profile-avatar-placeholder">{{ $juryMember->flag ?? '👤' }}</div>
                @endif
                <div class="profile-name">{{ $juryMember->name }}</div>
                @if($juryMember->title)
                    <div class="profile-title">{{ $juryMember->title }}</div>
                @endif
            </div>
            <div class="profile-card-body">
                <div class="profile-meta">
                    <div class="profile-meta-row">
                        <span class="profile-meta-label">🗳️ Évaluation</span>
                        @if($juryMember->is_active)
                            <span class="badge-active">Actif</span>
                        @else
                            <span class="badge-inactive">Inactif</span>
                        @endif
                    </div>
                    <div class="profile-meta-row">
                        <span class="profile-meta-label">🌐 En ligne</span>
                        @if($juryMember->is_visible ?? true)
                            <span class="badge-active">Visible</span>
                        @else
                            <span class="badge-inactive">Masqué</span>
                        @endif
                    </div>
                    @if($juryMember->country)
                    <div class="profile-meta-row">
                        <span class="profile-meta-label">Pays</span>
                        <span class="profile-meta-val">{{ $juryMember->flag ?? '' }} {{ $juryMember->country }}</span>
                    </div>
                    @endif
                    <div class="profile-meta-row">
                        <span class="profile-meta-label">Ordre</span>
                        <span class="profile-meta-val">#{{ $juryMember->sort_order }}</span>
                    </div>
                    @if($juryMember->unique_identifier)
                    <div>
                        <div class="profile-meta-label" style="margin-bottom:.4rem;">Identifiant jury</div>
                        <div class="identifier-display">
                            <span id="identVal">{{ $juryMember->unique_identifier }}</span>
                            <button type="button" class="copy-mini" id="copyIdBtn" title="Copier">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Suppression --}}
                <form method="POST" action="{{ route('admin.jury-members.destroy', $juryMember) }}" id="deleteForm">
                    @csrf @method('DELETE')
                    <button type="button" class="delete-btn" onclick="confirmDelete()">
                        <i class="fas fa-trash-alt"></i> Supprimer ce membre
                    </button>
                </form>
            </div>
        </div>

        {{-- Formulaire --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="hicon">✏️</div>
                <span>Modifier les informations</span>
            </div>
            <div class="form-card-body">
                <form method="POST" action="{{ route('admin.jury-members.update', $juryMember) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Identité --}}
                    <div class="section-sep">Identité</div>
                    <div class="row g-3 mb-2">
                        <div class="col-12 col-md-6">
                            <label class="f-label" for="name">Nom complet <span style="color:#ef4444;">*</span></label>
                            <input type="text" id="name" name="name" class="f-input"
                                value="{{ old('name', $juryMember->name) }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="f-label" for="title">Fonction / Titre</label>
                            <input type="text" id="title" name="title" class="f-input"
                                value="{{ old('title', $juryMember->title) }}" placeholder="Ex : Directeur Artistique">
                        </div>
                        <div class="col-12 col-md-7">
                            <label class="f-label" for="country">Pays</label>
                            <input type="text" id="country" name="country" class="f-input"
                                value="{{ old('country', $juryMember->country) }}">
                        </div>
                        <div class="col-12 col-md-5">
                            <label class="f-label" for="flag">Drapeau (emoji)</label>
                            <input type="text" id="flag" name="flag" class="f-input"
                                value="{{ old('flag', $juryMember->flag) }}" placeholder="🇨🇮">
                        </div>
                    </div>

                    {{-- Identifiant --}}
                    <div class="section-sep">Identifiant de connexion jury</div>
                    <label class="f-label" for="unique_identifier">Identifiant unique <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="unique_identifier" name="unique_identifier" class="f-input"
                        value="{{ old('unique_identifier', $juryMember->unique_identifier) }}"
                        style="font-family:monospace;font-size:.9rem;" required>
                    <div class="f-hint">🔐 Modifier cet identifiant invalide le lien d'accès actuel du membre.</div>

                    {{-- Photo --}}
                    <div class="section-sep">Photo de profil</div>
                    <div class="row g-3 mb-2">
                        <div class="col-12 col-md-6">
                            <label class="f-label">Remplacer la photo</label>
                            <div class="photo-drop" id="photoDrop">
                                <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png,image/webp">
                                @if($juryMember->photo_url)
                                    <img src="{{ $juryMember->photo_url }}" alt="" class="photo-preview" id="photoPreview" style="display:block;">
                                @else
                                    <div class="photo-drop-icon">📷</div>
                                    <div class="photo-drop-text">Cliquez ou glissez une image<br><small>JPG, PNG, WEBP — max 4 Mo</small></div>
                                    <img class="photo-preview" id="photoPreview" src="" alt="">
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-md-6" style="display:flex;flex-direction:column;justify-content:center;">
                            <label class="f-label" for="image_url">URL image externe</label>
                            <input type="url" id="image_url" name="image_url" class="f-input"
                                value="{{ old('image_url', $juryMember->image_url) }}" placeholder="https://...">
                            <div class="f-hint mt-2">La photo uploadée est prioritaire sur l'URL.</div>
                        </div>
                    </div>

                    {{-- Paramètres --}}
                    <div class="section-sep">Paramètres</div>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-4">
                            <label class="f-label" for="sort_order">Ordre d'affichage</label>
                            <input type="number" id="sort_order" name="sort_order" class="f-input"
                                value="{{ old('sort_order', $juryMember->sort_order) }}" min="0">
                            <div class="f-hint">Plus petit = affiché en premier</div>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="f-label">Statut d'évaluation</label>
                            <label class="toggle-wrap" for="is_active">
                                <input type="checkbox" id="is_active" name="is_active" value="1"
                                    {{ old('is_active', $juryMember->is_active) ? 'checked' : '' }}>
                                <span class="toggle-track"></span>
                                <span class="toggle-label">🗳️ Peut évaluer les groupes</span>
                            </label>
                            <div class="f-hint">Activer pour que ce membre accède au formulaire de notation</div>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="f-label">Visibilité publique</label>
                            <label class="toggle-wrap" for="is_visible">
                                <input type="checkbox" id="is_visible" name="is_visible" value="1"
                                    {{ old('is_visible', $juryMember->is_visible ?? true) ? 'checked' : '' }}>
                                <span class="toggle-track"></span>
                                <span class="toggle-label">🌐 En ligne sur la page jury</span>
                            </label>
                            <div class="f-hint">Afficher ce profil sur la page publique des membres</div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div style="display:flex;gap:1rem;align-items:center;flex-wrap:wrap;padding-top:.5rem;border-top:1px solid #334155;">
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </button>
                        <a href="{{ route('admin.jury-members.index') }}" class="btn-back">Annuler</a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    /* Copier identifiant */
    const copyIdBtn = document.getElementById('copyIdBtn');
    const identVal  = document.getElementById('identVal');
    if (copyIdBtn && identVal) {
        copyIdBtn.addEventListener('click', () => {
            navigator.clipboard.writeText(identVal.textContent.trim()).then(() => {
                copyIdBtn.innerHTML = '<i class="fas fa-check" style="color:#4ade80;"></i>';
                setTimeout(() => copyIdBtn.innerHTML = '<i class="fas fa-copy"></i>', 1500);
            });
        });
    }

    /* Toggle visuel */
    document.querySelectorAll('.toggle-wrap').forEach(wrap => {
        const cb    = wrap.querySelector('input[type=checkbox]');
        const track = wrap.querySelector('.toggle-track');
        function sync() { track.style.background = cb.checked ? '#22c55e' : '#334155'; }
        sync();
        cb.addEventListener('change', sync);
        wrap.addEventListener('click', e => {
            if (e.target !== cb) { cb.checked = !cb.checked; sync(); }
        });
    });

    /* Aperçu photo */
    const photoInput   = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');
    const photoDrop    = document.getElementById('photoDrop');
    if (photoInput) {
        photoInput.addEventListener('change', () => {
            const file = photoInput.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                photoPreview.src = e.target.result;
                photoPreview.style.display = 'block';
                const icon = photoDrop.querySelector('.photo-drop-icon');
                const txt  = photoDrop.querySelector('.photo-drop-text');
                if (icon) icon.style.display = 'none';
                if (txt)  txt.style.display  = 'none';
            };
            reader.readAsDataURL(file);
        });
    }

    /* Confirmation suppression */
    function confirmDelete() {
        if (confirm('Supprimer définitivement « {{ addslashes($juryMember->name) }} » ? Cette action est irréversible.')) {
            document.getElementById('deleteForm').submit();
        }
    }
</script>
@endpush
