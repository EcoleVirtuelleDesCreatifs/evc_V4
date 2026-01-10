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
        body{background:#0b1220;}
        .hero{background:linear-gradient(135deg,#003366 0%,#3399ff 50%,#ff6633 100%);}
        .shell{background:rgba(255,255,255,.96);border-radius:18px;box-shadow:0 20px 60px rgba(0,0,0,.35);}
        .card-dark{background:rgba(15,23,42,.96);border:1px solid rgba(255,255,255,.10);border-radius:18px;}
        .avatar{width:110px;height:110px;border-radius:999px;object-fit:cover;border:5px solid rgba(255,255,255,.18);background:rgba(255,255,255,.08);}
        .pill{display:inline-flex;align-items:center;gap:.5rem;padding:.45rem .7rem;border-radius:999px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.14);font-weight:800;font-size:.86rem;line-height:1.1;color:rgba(255,255,255,.92);}
    </style>
</head>
<body>
    <div class="hero py-4">
        <div class="container">
            <div class="shell p-3 p-md-4 mx-auto" style="max-width:980px;">
                <div class="text-center mb-3">
                    <h1 class="h4 fw-bold mb-1">Vérifier votre ID Étudiant</h1>
                    <div class="text-muted">Vérifiez un statut officiel EVC + certification.</div>
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
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-shield-alt me-2"></i>Vérifier
                        </button>
                    </div>
                </form>

                <div class="mt-3 text-center">
                    <a href="{{ route('login') }}" class="text-decoration-none text-muted">
                        <small><i class="fas fa-arrow-left me-1"></i>Retour connexion</small>
                    </a>
                </div>

                @if($notFound)
                    <div class="alert alert-warning mt-4 mb-0">
                        <strong>ID introuvable.</strong> Vérifiez l'orthographe et réessayez.
                    </div>
                @endif

                @if($student && is_array($stats))
                    <div class="card-dark text-white mt-4">
                        <div class="p-3 p-md-4">
                            <div class="text-center">
                                <div class="fw-black" style="letter-spacing:.18em;font-weight:900;opacity:.9;font-size:.85rem;">ÉTUDIANT(E) EVC</div>
                                <div class="mt-2">
                                    @if(!empty($stats['photo_url']))
                                        <img src="{{ $stats['photo_url'] }}" alt="Photo" class="avatar">
                                    @else
                                        <div class="avatar d-inline-flex align-items-center justify-content-center text-white fw-bold" style="background:rgba(255,255,255,.12);">EVC</div>
                                    @endif
                                </div>
                                <div class="mt-3 fw-bold" style="font-size:1.25rem;">{{ trim(($student->first_name ?? '').' '.($student->last_name ?? '')) }}</div>
                                <div class="mt-2"><span class="pill"><i class="fas fa-id-card"></i>ID: {{ $student->student_id ?? '—' }}</span></div>
                                <div class="mt-2"><span class="pill"><i class="fas fa-graduation-cap"></i>{{ $student->program ?? 'Formation' }}</span></div>
                            </div>

                            <div class="row g-2 mt-3">
                                <div class="col-12 col-md-6">
                                    <div class="p-2 rounded" style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.10);">
                                        <div class="small" style="color:rgba(255,255,255,.72);">Date d'inscription</div>
                                        <div class="fw-bold">
                                            @if(!empty($stats['registration_date']))
                                                {{ \Carbon\Carbon::parse($stats['registration_date'])->format('d/m/Y') }}
                                            @else
                                                —
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="p-2 rounded" style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.10);">
                                        <div class="small" style="color:rgba(255,255,255,.72);">Statut</div>
                                        <div class="fw-bold">
                                            @if(!empty($stats['certified']))
                                                Certifié
                                            @else
                                                Étudiant officiel
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-2 mt-2">
                                <div class="col-12 col-md-4">
                                    <div class="p-2 rounded" style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.10);">
                                        <div class="small" style="color:rgba(255,255,255,.72);">Projets Design</div>
                                        <div class="fw-bold">{{ (int) ($stats['design_projects_total'] ?? 0) }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="p-2 rounded" style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.10);">
                                        <div class="small" style="color:rgba(255,255,255,.72);">TP (validés)</div>
                                        <div class="fw-bold">{{ (int) ($stats['tp_validated'] ?? 0) }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="p-2 rounded" style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.10);">
                                        <div class="small" style="color:rgba(255,255,255,.72);">Projets (validés)</div>
                                        <div class="fw-bold">{{ (int) (($stats['projects_completed'] ?? 0) + ($stats['design_projects_validated'] ?? 0)) }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 d-flex flex-wrap justify-content-center gap-2">
                                @if(!empty($stats['eligible']))
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Éligible au certificat</span>
                                    <a class="btn btn-sm btn-warning" target="_blank" href="{{ route('auth.verify-id.certificate.preview', ['student_id' => $student->student_id]) }}">
                                        <i class="fas fa-eye me-1"></i>Voir le certificat
                                    </a>
                                @else
                                    <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half me-1"></i>Certificat non éligible</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
