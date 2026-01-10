@php
    $student = $student ?? null;
    $stats = $stats ?? null;
    $notFound = $notFound ?? false;
    $searchedId = $searchedId ?? null;
@endphp

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vérifier votre ID - EVC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root{--evc-blue:#003366;--evc-sky:#3399ff;--evc-orange:#ff6633;--evc-dark:#0b1220;}
        html, body{height:auto !important;overflow-y:auto !important;}
        body{font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:#0a0a0a;min-height:100vh;color:#f1f5f9;}

        .legal-page{min-height:100vh;background:#0a0a0a;color:#f1f5f9;padding:120px 20px 80px;}
        .legal-container{max-width:980px;margin:0 auto;}
        .legal-header{text-align:center;margin-bottom:34px;}
        .legal-header h1{font-size:44px;font-weight:800;margin-bottom:12px;background:linear-gradient(135deg,#FF9900 0%,#F97316 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
        .legal-header p{color:#94a3b8;font-size:16px;max-width:760px;margin:0 auto;}
        .legal-content{background:#1e293b;border-radius:20px;padding:26px;box-shadow:0 4px 20px rgba(0,0,0,.35);border:1px solid rgba(148,163,184,.12);}

        .legal-info-box{background:rgba(255,153,0,.08);border-left:4px solid #FF9900;padding:16px;border-radius:12px;}
        .legal-info-box p{margin:0;color:#e2e8f0;}

        .form-label{color:#e2e8f0;}
        .form-control{background:#0f172a;border:2px solid #334155;color:#e2e8f0;border-radius:12px;padding:14px 16px;}
        .form-control::placeholder{color:rgba(148,163,184,.75);}
        .form-control:focus{background:#0b1220;border-color:#FF9900;box-shadow:0 0 0 .2rem rgba(255,153,0,.18);color:#fff;}
        .btn-primary{background:linear-gradient(135deg,#FF9900 0%,#F97316 100%);border:none;border-radius:12px;padding:12px 14px;font-weight:800;}
        .btn-primary:hover{filter:brightness(1.02);}
        .page-title{font-weight:900;letter-spacing:-.02em;}
        .subtitle{color:rgba(0,0,0,.6);}
        .verify-header{background:linear-gradient(135deg,var(--evc-orange), #FF9900);color:#fff;text-align:center;padding:28px 18px;border-radius:18px;position:relative;overflow:hidden;}
        .verify-title{font-size:1.85rem;font-weight:900;letter-spacing:-.02em;text-shadow:2px 2px 10px rgba(0,0,0,.28);}
        .verify-subtitle{opacity:.92;font-weight:400;max-width:760px;margin-left:auto;margin-right:auto;line-height:1.55;}
        .verify-body{padding:22px 20px;}
        .info-box{background:linear-gradient(135deg, rgba(51,153,255,.10), rgba(0,51,102,.10));border:1px solid rgba(51,153,255,.22);border-radius:14px;padding:16px;}
        .card-dark{background:linear-gradient(135deg, rgba(6,62,119,.78) 0%, rgba(32,113,195,.62) 55%, rgba(51,153,255,.48) 100%);border:1px solid rgba(255,255,255,.14);border-radius:22px;overflow:hidden;backdrop-filter:blur(18px);box-shadow:0 26px 70px rgba(0,0,0,.35);}
        .card-dark .top{background:radial-gradient(900px 260px at 50% 0%, rgba(255,255,255,.14) 0%, rgba(255,255,255,0) 55%);}
        .avatar{width:128px;height:128px;border-radius:999px;object-fit:cover;border:6px solid rgba(255,255,255,.22);background:rgba(255,255,255,.10);box-shadow:0 18px 55px rgba(0,0,0,.40);}
        .pill{display:inline-flex;align-items:center;gap:.5rem;padding:.5rem .85rem;border-radius:999px;background:rgba(0,0,0,.16);border:1px solid rgba(255,255,255,.16);font-weight:800;font-size:.9rem;line-height:1.1;color:rgba(255,255,255,.95);backdrop-filter:blur(10px);}
        .pill i{opacity:.9;}
        .stat-box{background:rgba(0,0,0,.14);border:1px solid rgba(255,255,255,.14);border-radius:14px;backdrop-filter:blur(10px);}
        .stat-label{color:rgba(255,255,255,.78);font-size:.78rem;}
        .stat-value{font-weight:900;}
        .badge-evc{border-radius:999px;padding:.45rem .8rem;font-weight:900;}
        .badge-evc.ok{background:rgba(40,167,69,.18);color:#b6f7c7;border:1px solid rgba(40,167,69,.35);}
        .badge-evc.warn{background:rgba(255,153,0,.18);color:#ffe0b0;border:1px solid rgba(255,153,0,.35);}
        .badge-evc.danger{background:rgba(220,53,69,.16);color:#ffd0d6;border:1px solid rgba(220,53,69,.34);}
        .status-banner{border-radius:18px;border:1px solid rgba(255,255,255,.16);padding:14px 14px;background:linear-gradient(135deg, rgba(0,0,0,.14) 0%, rgba(51,153,255,.16) 55%, rgba(255,102,51,.12) 100%);box-shadow:0 22px 70px rgba(0,0,0,.28);}
        .status-banner .kicker{letter-spacing:.18em;font-weight:900;opacity:.9;font-size:.75rem;}
        .status-banner .headline{font-weight:950;letter-spacing:-.02em;line-height:1.15;}
        .status-banner .desc{color:rgba(255,255,255,.78);}
        .info-line{display:flex;align-items:flex-start;gap:.65rem;color:rgba(255,255,255,.90);}
        .info-line i{opacity:.9;margin-top:.1rem;}
        .notfound{border-radius:18px;border:1px solid rgba(148,163,184,.18);background:linear-gradient(135deg, rgba(255,153,0,.10) 0%, rgba(30,41,59,.92) 55%, rgba(15,23,42,.92) 100%);}
        .btn-primary{background:linear-gradient(135deg,var(--evc-blue) 0%,var(--evc-sky) 100%);border:none;}
        .btn-primary:hover{filter:brightness(1.05);}
        .soft-anim{transition:transform .25s ease, box-shadow .25s ease;}
        .soft-anim:hover{transform:translateY(-1px);box-shadow:0 12px 40px rgba(0,0,0,.20);}
        .evc-dots{display:inline-block;width:46px;height:10px;background:
            radial-gradient(circle closest-side, rgba(255,255,255,0.95) 92%, transparent) 0% 50%/10px 10px no-repeat,
            radial-gradient(circle closest-side, rgba(255,255,255,0.95) 92%, transparent) 50% 50%/10px 10px no-repeat,
            radial-gradient(circle closest-side, rgba(255,255,255,0.95) 92%, transparent) 100% 50%/10px 10px no-repeat;
            filter: drop-shadow(0 6px 16px rgba(255,255,255,0.18));animation: evcDots 1.05s infinite ease-in-out;}
        @keyframes evcDots{0%,100%{transform:translateY(0);opacity:.55;background-position:0% 55%,50% 50%,100% 45%;}50%{transform:translateY(-1px);opacity:1;background-position:0% 45%,50% 55%,100% 50%;}}

        .verify-overlay{position:fixed;inset:0;z-index:9999;display:none;align-items:center;justify-content:center;background:linear-gradient(135deg,#063E77 0%,#2071C3 50%,#3399ff 100%);}
        .verify-overlay.show{display:flex;}
        .verify-overlay .particles{position:absolute;inset:0;opacity:.45;pointer-events:none;}
        .verify-overlay .particle{position:absolute;width:10px;height:10px;border-radius:999px;background:rgba(255,255,255,.20);filter:blur(.2px);animation: verifyFloat 10s infinite ease-in-out;}
        .verify-overlay .particle:nth-child(1){top:12%;left:10%;animation-duration:12s;}
        .verify-overlay .particle:nth-child(2){top:22%;left:75%;animation-duration:16s;}
        .verify-overlay .particle:nth-child(3){top:68%;left:18%;animation-duration:14s;}
        .verify-overlay .particle:nth-child(4){top:78%;left:82%;animation-duration:18s;}
        .verify-overlay .particle:nth-child(5){top:40%;left:50%;animation-duration:20s;}
        @keyframes verifyFloat{0%,100%{transform:translateY(0) translateX(0) scale(1);}50%{transform:translateY(-20px) translateX(10px) scale(1.2);}}
        .verify-overlay .panel{position:relative;z-index:2;width:min(680px,92vw);background:rgba(0,0,0,.20);backdrop-filter:blur(18px);border:1px solid rgba(255,255,255,.16);border-radius:22px;box-shadow:0 30px 90px rgba(0,0,0,.38);padding:28px 22px;color:#fff;text-align:center;}
        .verify-overlay .panel h2{font-weight:900;letter-spacing:-.02em;margin:0;}
        .verify-overlay .panel p{margin:10px 0 0;opacity:.9;}
        .verify-overlay .badge{display:inline-flex;align-items:center;gap:.5rem;border-radius:999px;padding:.55rem .9rem;background:rgba(0,0,0,.20);border:1px solid rgba(255,255,255,.18);font-weight:700;}
        .verify-overlay .spinner{width:62px;height:62px;border-radius:999px;border:4px solid rgba(255,255,255,.22);border-top-color:#fff;animation: verifySpin .9s linear infinite;margin:18px auto 10px;}
        @keyframes verifySpin{to{transform:rotate(360deg);}}
        .verify-overlay .progress{height:10px;background:rgba(0,0,0,.18);border-radius:999px;overflow:hidden;border:1px solid rgba(255,255,255,.16);}
        .verify-overlay .progress-bar{height:100%;width:0%;background:linear-gradient(90deg,#ff6633 0%,#FF9900 40%,#ffffff 100%);transition:width .25s linear;}
        .verify-overlay .progress-text{margin-top:10px;font-weight:800;opacity:.95;}
    </style>
</head>
<body>
    <div class="legal-page">
        <div class="legal-container">
            <div class="legal-header">
                <h1>Vérifier un ID Étudiant</h1>
                <p>Confirmez un statut officiel EVC, la formation (en cours/terminée) et l'éligibilité à la certification.</p>
            </div>

            <div class="legal-content">
                <div class="legal-info-box" style="margin: 0 0 18px;">
                    <p><strong>Info :</strong> Entrez l'ID étudiant pour afficher les informations officielles et la progression (TP & projets).</p>
                </div>

                <form method="POST" action="{{ route('auth.verify-id.check') }}" class="row g-2 align-items-end">
                    @csrf
                    <div class="col-12 col-md-8">
                        <label class="form-label fw-semibold"><i class="fas fa-id-card me-1"></i>ID Étudiant</label>
                        <input type="text" name="student_id" value="{{ old('student_id', $searchedId) }}" class="form-control @error('student_id') is-invalid @enderror" placeholder="Ex: EVC-2026-050101" required>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-md-4 d-grid">
                        <button type="submit" class="btn btn-primary" id="verifyIdBtn">
                            <span id="verifyIdText"><i class="fas fa-shield-alt me-2"></i>Vérifier</span>
                            <span id="verifyIdSpinner" class="evc-dots" style="display:none;"></span>
                        </button>
                    </div>
                </form>

                <div class="mt-3 text-center">
                    <a href="{{ route('login') }}" class="text-decoration-none" style="color:#94a3b8;">
                        <small><i class="fas fa-arrow-left me-1"></i>Retour connexion</small>
                    </a>
                </div>

                @if($notFound)
                    <div class="notfound text-white mt-4 p-3 p-md-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="flex-shrink-0" style="width:44px;height:44px;border-radius:14px;background:rgba(255,102,51,.22);border:1px solid rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-triangle-exclamation"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold" style="font-size:1.05rem;">ID introuvable</div>
                                <div class="mt-1" style="opacity:.92;">Nous n'avons trouvé aucun(e) étudiant(e) correspondant à <span class="fw-bold">{{ $searchedId }}</span>.</div>
                                <div class="mt-2" style="opacity:.86;">
                                    <div class="info-line"><i class="fas fa-check"></i><span>Vérifie les tirets et les chiffres (ex: <span class="fw-bold">EVC-2026-050101</span>).</span></div>
                                    <div class="info-line mt-1"><i class="fas fa-check"></i><span>Assure-toi de copier/coller l'ID depuis ton espace étudiant.</span></div>
                                    <div class="info-line mt-1"><i class="fas fa-check"></i><span>Si le problème persiste, contacte l'administration EVC pour vérification.</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($student && is_array($stats))
                    <div class="card-dark text-white mt-4 soft-anim">
                        <div class="top p-3 p-md-4">
                            <div class="text-center">
                                <div class="fw-black" style="letter-spacing:.18em;font-weight:900;opacity:.95;font-size:.85rem;">ÉTUDIANT(E) EVC</div>
                                <div class="mt-2">
                                    @if(!empty($stats['photo_url']))
                                        <img src="{{ $stats['photo_url'] }}" alt="Photo" class="avatar">
                                    @else
                                        <div class="avatar d-inline-flex align-items-center justify-content-center text-white fw-bold" style="background:rgba(255,255,255,.12);">EVC</div>
                                    @endif
                                </div>
                                <div class="mt-3 fw-bold" style="font-size:1.35rem;">{{ trim(($student->first_name ?? '').' '.($student->last_name ?? '')) }}</div>

                                <div class="mt-2 d-flex flex-wrap justify-content-center gap-2">
                                    <span class="pill"><i class="fas fa-id-card"></i>ID: {{ $student->student_id ?? '—' }}</span>
                                    <span class="pill"><i class="fas fa-graduation-cap"></i>{{ $student->program ?? 'Formation' }}</span>
                                </div>

                                <div class="mt-3">
                                    @if(!empty($stats['is_active']))
                                        <span class="badge-evc ok"><i class="fas fa-badge-check me-1"></i>Vous êtes officiellement étudiant(e) à EVC</span>
                                    @else
                                        <span class="badge-evc danger"><i class="fas fa-flag-checkered me-1"></i>Vous avez été étudiant(e) à EVC</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-3 status-banner">
                                <div class="kicker">STATUT DE FORMATION</div>
                                @if(!empty($stats['is_active']))
                                    <div class="headline mt-1">Votre formation est en cours.</div>
                                    <div class="desc mt-2">Vous êtes officiellement étudiant(e) à EVC, et votre formation n'est pas encore terminée. Continuez votre progression : TP, projets, et dossier de fin de formation.</div>
                                @else
                                    <div class="headline mt-1">Votre formation est terminée.</div>
                                    <div class="desc mt-2">Vous avez été étudiant(e) à EVC, mais actuellement votre formation est terminée. Si vous souhaitez renouveler votre accès ou reprendre une formation, l'administration peut vous accompagner.</div>
                                @endif

                                <div class="row g-2 mt-3">
                                    <div class="col-12 col-lg-4">
                                        <div class="p-3 stat-box">
                                            <div class="stat-label">Date d'inscription</div>
                                            <div class="stat-value">
                                                @if(!empty($stats['registration_date']))
                                                    {{ \Carbon\Carbon::parse($stats['registration_date'])->format('d/m/Y') }}
                                                @else
                                                    —
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="p-3 stat-box">
                                            <div class="stat-label">Fin estimée / Expiration</div>
                                            <div class="stat-value">
                                                @if(!empty($stats['expiration_date']))
                                                    {{ \Carbon\Carbon::parse($stats['expiration_date'])->format('d/m/Y') }}
                                                @else
                                                    —
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="p-3 stat-box">
                                            <div class="stat-label">Jours restants</div>
                                            <div class="stat-value">
                                                @if(isset($stats['days_remaining']) && $stats['days_remaining'] !== null)
                                                    {{ (int) $stats['days_remaining'] }}
                                                @else
                                                    —
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-2 mt-3">
                                <div class="col-12 col-md-6">
                                    <div class="p-3 stat-box">
                                        <div class="stat-label">Statut</div>
                                        <div class="stat-value">{{ !empty($stats['is_active']) ? 'Étudiant officiel (actif)' : 'Ancien étudiant (formation terminée)' }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="p-3 stat-box">
                                        <div class="stat-label">Certification</div>
                                        <div class="stat-value">
                                            @if(!empty($stats['certified']))
                                                Certifié
                                            @else
                                                Non certifié
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-2 mt-2">
                                <div class="col-12 col-md-4">
                                    <div class="p-3 stat-box">
                                        <div class="stat-label">Projets Design</div>
                                        <div class="stat-value">{{ (int) ($stats['design_projects_total'] ?? 0) }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="p-3 stat-box">
                                        <div class="stat-label">TP (validés / requis)</div>
                                        <div class="stat-value">{{ (int) ($stats['tp_validated'] ?? 0) }} / {{ (int) ($stats['min_tp_required'] ?? 0) }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="p-3 stat-box">
                                        <div class="stat-label">Projets (validés)</div>
                                        <div class="stat-value">{{ (int) (($stats['projects_completed'] ?? 0) + ($stats['design_projects_validated'] ?? 0)) }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 d-flex flex-wrap justify-content-center gap-2">
                                @if(!empty($stats['eligible']))
                                    <span class="badge-evc ok"><i class="fas fa-check-circle me-1"></i>Éligible au certificat</span>
                                    <a class="btn btn-sm btn-warning" target="_blank" href="{{ route('auth.verify-id.certificate.preview', ['student_id' => $student->student_id]) }}">
                                        <i class="fas fa-eye me-1"></i>Voir le certificat
                                    </a>
                                @else
                                    <span class="badge-evc warn"><i class="fas fa-hourglass-half me-1"></i>Certificat non éligible</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="verify-overlay" id="verifyOverlay" aria-hidden="true">
        <div class="particles" aria-hidden="true">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
        <div class="panel">
            <div style="font-weight:900;letter-spacing:.18em;opacity:.92;font-size:.8rem;">VÉRIFICATION EVC</div>
            <h2 class="mt-2">Vérification en cours…</h2>
            <p id="verifyOverlayDesc">Nous contrôlons l'ID et récupérons les informations officielles.</p>
            <div class="mt-3 d-flex justify-content-center">
                <span class="badge"><i class="fas fa-id-card"></i><span id="verifyOverlayId">—</span></span>
            </div>
            <div class="spinner" aria-hidden="true"></div>
            <div class="progress mt-3" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                <div class="progress-bar" id="verifyOverlayBar"></div>
            </div>
            <div class="progress-text" id="verifyOverlayPct">0%</div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const verifyIdBtn = document.getElementById('verifyIdBtn');
        const verifyIdText = document.getElementById('verifyIdText');
        const verifyIdSpinner = document.getElementById('verifyIdSpinner');
        const verifyOverlay = document.getElementById('verifyOverlay');
        const verifyOverlayId = document.getElementById('verifyOverlayId');
        const verifyOverlayBar = document.getElementById('verifyOverlayBar');
        const verifyOverlayPct = document.getElementById('verifyOverlayPct');
        if (verifyIdBtn) {
            const form = verifyIdBtn.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    verifyIdBtn.disabled = true;
                    if (verifyIdText) verifyIdText.style.display = 'none';
                    if (verifyIdSpinner) verifyIdSpinner.style.display = 'inline-block';

                    const input = form.querySelector('input[name="student_id"]');
                    const enteredId = input ? (input.value || '').trim() : '';
                    if (verifyOverlay) {
                        verifyOverlay.classList.add('show');
                        verifyOverlay.setAttribute('aria-hidden', 'false');
                    }
                    if (verifyOverlayId) {
                        verifyOverlayId.textContent = enteredId !== '' ? enteredId : '—';
                    }

                    const durationMs = 10000;
                    const start = Date.now();
                    if (verifyOverlayBar) verifyOverlayBar.style.width = '0%';
                    if (verifyOverlayPct) verifyOverlayPct.textContent = '0%';

                    const timer = window.setInterval(function() {
                        const elapsed = Date.now() - start;
                        const pct = Math.max(0, Math.min(100, Math.round((elapsed / durationMs) * 100)));
                        if (verifyOverlayBar) verifyOverlayBar.style.width = pct + '%';
                        if (verifyOverlayPct) verifyOverlayPct.textContent = pct + '%';
                        if (elapsed >= durationMs) {
                            window.clearInterval(timer);
                        }
                    }, 120);

                    window.setTimeout(function() {
                        form.submit();
                    }, 10000);
                });
            }
        }
    </script>
</body>
</html>
