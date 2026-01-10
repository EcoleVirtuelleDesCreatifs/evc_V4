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
    <style>
        :root{--evc-blue:#003366;--evc-sky:#3399ff;--evc-orange:#ff6633;--evc-dark:#0b1220;}
        body{background:var(--evc-dark);}
        .hero{position:relative;background:linear-gradient(135deg,var(--evc-blue) 0%,var(--evc-sky) 45%,var(--evc-orange) 100%);}
        .hero::before{content:'';position:absolute;inset:0;background:radial-gradient(900px 300px at 20% 10%, rgba(255,255,255,.18) 0%, rgba(255,255,255,0) 60%),radial-gradient(800px 280px at 80% 30%, rgba(255,255,255,.14) 0%, rgba(255,255,255,0) 60%);opacity:.85;pointer-events:none;}
        .shell{position:relative;z-index:1;background:rgba(255,255,255,.92);border-radius:22px;box-shadow:0 30px 80px rgba(0,0,0,.45);border:1px solid rgba(255,255,255,.35);backdrop-filter:blur(16px);}
        .page-title{font-weight:900;letter-spacing:-.02em;}
        .subtitle{color:rgba(0,0,0,.6);}
        .card-dark{background:rgba(10,16,30,.92);border:1px solid rgba(255,255,255,.12);border-radius:22px;overflow:hidden;}
        .card-dark .top{background:radial-gradient(900px 260px at 50% 0%, rgba(255,255,255,.10) 0%, rgba(255,255,255,0) 55%);}
        .avatar{width:128px;height:128px;border-radius:999px;object-fit:cover;border:6px solid rgba(255,255,255,.18);background:rgba(255,255,255,.08);box-shadow:0 18px 65px rgba(0,0,0,.55);}
        .pill{display:inline-flex;align-items:center;gap:.5rem;padding:.5rem .85rem;border-radius:999px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.16);font-weight:800;font-size:.9rem;line-height:1.1;color:rgba(255,255,255,.92);}
        .pill i{opacity:.9;}
        .stat-box{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.10);border-radius:14px;}
        .stat-label{color:rgba(255,255,255,.72);font-size:.78rem;}
        .stat-value{font-weight:900;}
        .badge-evc{border-radius:999px;padding:.45rem .8rem;font-weight:900;}
        .badge-evc.ok{background:rgba(40,167,69,.18);color:#b6f7c7;border:1px solid rgba(40,167,69,.35);}
        .badge-evc.warn{background:rgba(255,153,0,.18);color:#ffe0b0;border:1px solid rgba(255,153,0,.35);}
        .badge-evc.danger{background:rgba(220,53,69,.16);color:#ffd0d6;border:1px solid rgba(220,53,69,.34);}
        .status-banner{border-radius:18px;border:1px solid rgba(255,255,255,.14);padding:14px 14px;background:linear-gradient(135deg, rgba(51,153,255,.16) 0%, rgba(0,51,102,.10) 35%, rgba(255,102,51,.14) 100%);box-shadow:0 22px 70px rgba(0,0,0,.42);}
        .status-banner .kicker{letter-spacing:.18em;font-weight:900;opacity:.9;font-size:.75rem;}
        .status-banner .headline{font-weight:950;letter-spacing:-.02em;line-height:1.15;}
        .status-banner .desc{color:rgba(255,255,255,.78);}
        .info-line{display:flex;align-items:flex-start;gap:.65rem;color:rgba(255,255,255,.90);}
        .info-line i{opacity:.9;margin-top:.1rem;}
        .notfound{border-radius:18px;border:1px solid rgba(255,255,255,.18);background:linear-gradient(135deg, rgba(255,102,51,.14) 0%, rgba(0,51,102,.10) 55%, rgba(51,153,255,.12) 100%);}
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
    </style>
</head>
<body>
    <div class="hero py-4">
        <div class="container">
            <div class="shell p-3 p-md-4 mx-auto" style="max-width:980px;">
                <div class="text-center mb-3">
                    <h1 class="h4 page-title mb-1">Vérifier votre ID Étudiant</h1>
                    <div class="subtitle">Vérifiez un statut officiel EVC + certification.</div>
                </div>

                <form method="POST" action="{{ route('auth.verify-id.check') }}" class="row g-2 align-items-end">
                    @csrf
                    <div class="col-12 col-md-8">
                        <label class="form-label fw-semibold">ID Étudiant</label>
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
                    <a href="{{ route('login') }}" class="text-decoration-none text-muted">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const verifyIdBtn = document.getElementById('verifyIdBtn');
        const verifyIdText = document.getElementById('verifyIdText');
        const verifyIdSpinner = document.getElementById('verifyIdSpinner');
        if (verifyIdBtn) {
            const form = verifyIdBtn.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    verifyIdBtn.disabled = true;
                    if (verifyIdText) verifyIdText.style.display = 'none';
                    if (verifyIdSpinner) verifyIdSpinner.style.display = 'inline-block';
                    window.setTimeout(function() {
                        form.submit();
                    }, 10000);
                });
            }
        }
    </script>
</body>
</html>
