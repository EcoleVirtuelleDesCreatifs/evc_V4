@php
    $student = $student ?? null;
    $stats = $stats ?? null;
    $notFound = $notFound ?? false;
    $searchedId = $searchedId ?? null;

    $maskStudentId = function (?string $id): string {
        $id = is_string($id) ? trim($id) : '';
        if ($id === '') {
            return '—';
        }
        if (preg_match('/^([A-Za-z]+-\d{4}-)(\d+)$/', $id, $m)) {
            $prefix = $m[1];
            $digits = $m[2];
            if (strlen($digits) <= 2) {
                return $prefix . str_repeat('*', strlen($digits));
            }
            return $prefix . str_repeat('*', strlen($digits) - 2) . substr($digits, -2);
        }
        if (strlen($id) <= 6) {
            return str_repeat('*', strlen($id));
        }
        return substr($id, 0, 4) . str_repeat('*', max(0, strlen($id) - 6)) . substr($id, -2);
    };

    $searchedIdMasked = $maskStudentId($searchedId);
@endphp

@extends('layouts.app')

@section('title', 'Vérifier un ID Étudiant | EVC')

@push('styles')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    @keyframes pulse-border {
        0%, 100% { box-shadow: 0 0 0 0 rgba(255, 152, 0, 0.7); }
        50% { box-shadow: 0 0 0 20px rgba(255, 152, 0, 0); }
    }

    .hero-gradient {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        position: relative;
        overflow: hidden;
    }

    .hero-gradient::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 20% 50%, rgba(255, 152, 0, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
        animation: float 20s ease-in-out infinite;
    }

    .cta-button {
        background: linear-gradient(135deg, #ff9800, #ff6b00);
        box-shadow: 0 10px 30px rgba(255, 152, 0, 0.4);
        transition: all 0.3s ease;
        animation: pulse-border 2s infinite;
    }

    .cta-button:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(255, 152, 0, 0.6);
    }

    .evc-input {
        background: rgba(15, 23, 42, 0.75);
        border: 1px solid rgba(148, 163, 184, 0.25);
        color: #fff;
    }

    .evc-input::placeholder {
        color: rgba(148, 163, 184, 0.85);
    }

    .evc-input:focus {
        border-color: rgba(255, 152, 0, 0.65);
        outline: none;
        box-shadow: 0 0 0 4px rgba(255, 152, 0, 0.15);
    }

    .card-glass {
        background: rgba(15, 23, 42, 0.55);
        border: 1px solid rgba(255,255,255,0.10);
        backdrop-filter: blur(14px);
    }

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

    @media print{
        .verify-overlay{display:none !important;}
        header, footer, #scrollToTop, #preloader { display:none !important; }
    }
</style>
@endpush

@section('content')
<div class="hero-gradient pt-32 pb-16 sm:pt-40 sm:pb-20 relative">
    <div class="mx-auto max-w-7xl px-6 lg:px-8 relative z-10">
        <div class="mx-auto max-w-4xl text-center" style="animation: fadeInUp 1s ease-out">
            <div class="inline-block mb-6">
                <span class="inline-flex items-center gap-2 rounded-full bg-orange-500/10 px-6 py-2 text-sm font-semibold text-orange-400 ring-1 ring-inset ring-orange-500/20">
                    <i class="fas fa-id-card"></i>
                    Vérification publique EVC
                </span>
            </div>
            <h1 class="text-5xl font-black tracking-tight text-white sm:text-7xl mb-6" style="line-height: 1.1;">
                Vérifier un <span class="bg-gradient-to-r from-orange-400 to-orange-600 bg-clip-text text-transparent">ID Étudiant</span>
            </h1>
            <p class="mt-6 text-xl leading-8 text-gray-300 max-w-3xl mx-auto">
                Confirmez un statut officiel EVC, la formation (en cours/terminée) et l'éligibilité à la certification.
            </p>
        </div>
    </div>
</div>

<div class="bg-gradient-to-b from-slate-900 to-slate-800 py-14 sm:py-20">
    <div class="mx-auto max-w-4xl px-6 lg:px-8">
        <div class="card-glass rounded-3xl p-6 sm:p-8">
            <div class="rounded-2xl bg-orange-500/10 border border-orange-500/20 px-5 py-4 text-gray-200">
                <span class="font-bold">Info :</span> Entrez l'ID étudiant pour afficher les informations officielles et la progression (TP & projets).
            </div>

            <form method="POST" action="{{ route('auth.verify-id.check') }}" class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-12 sm:items-end">
                @csrf
                <div class="sm:col-span-8">
                    <label class="block text-sm font-semibold text-gray-200">
                        <i class="fas fa-id-card mr-2 text-orange-400"></i>ID Étudiant
                    </label>
                    <input
                        type="text"
                        name="student_id"
                        value="{{ old('student_id', $searchedId) }}"
                        placeholder="Ex: EVC-2026-050101"
                        required
                        class="evc-input mt-2 w-full rounded-2xl px-4 py-4 text-base @error('student_id') border-red-500 @enderror"
                    >
                    @error('student_id')
                        <div class="mt-2 text-sm text-red-300">{{ $message }}</div>
                    @enderror
                </div>
                <div class="sm:col-span-4">
                    <button type="submit" class="cta-button w-full rounded-full px-8 py-4 text-lg font-bold text-white" id="verifyIdBtn">
                        <span id="verifyIdText"><i class="fas fa-shield-alt mr-2"></i>Vérifier</span>
                        <span id="verifyIdSpinner" class="inline-flex items-center gap-2" style="display:none;">
                            <span class="inline-block h-2 w-2 rounded-full bg-white/90"></span>
                            <span class="inline-block h-2 w-2 rounded-full bg-white/70"></span>
                            <span class="inline-block h-2 w-2 rounded-full bg-white/50"></span>
                        </span>
                    </button>
                    <div class="mt-3 text-center">
                        <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-orange-400">
                            <i class="fas fa-arrow-left mr-1"></i>Retour connexion
                        </a>
                    </div>
                </div>
            </form>

            @if($notFound)
                <div class="mt-6 rounded-3xl border border-orange-500/25 bg-orange-500/10 p-6 text-white">
                    <div class="flex items-start gap-4">
                        <div class="h-12 w-12 shrink-0 rounded-2xl bg-orange-500/20 border border-orange-500/25 flex items-center justify-center">
                            <i class="fas fa-triangle-exclamation"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-black text-lg">ID introuvable</div>
                            <div class="mt-1 text-gray-200">Nous n'avons trouvé aucun(e) étudiant(e) correspondant à <span class="font-bold">{{ $searchedIdMasked }}</span>.</div>
                            <div class="mt-3 text-gray-300 space-y-2">
                                <div><i class="fas fa-check text-orange-400 mr-2"></i>Vérifie les tirets et les chiffres (ex: <span class="font-bold">EVC-2026-****01</span>).</div>
                                <div><i class="fas fa-check text-orange-400 mr-2"></i>Assure-toi de copier/coller l'ID depuis ton espace étudiant.</div>
                                <div><i class="fas fa-check text-orange-400 mr-2"></i>Si le problème persiste, contacte l'administration EVC pour vérification.</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($student && is_array($stats))
                <div class="mt-6 rounded-3xl border border-white/10 bg-gradient-to-br from-slate-800/80 to-slate-900/80 p-6 sm:p-8">
                    <div class="text-center">
                        <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-5 py-2 text-xs font-bold tracking-[0.28em] text-white/90 border border-white/10">
                            ÉTUDIANT(E) EVC
                        </div>
                        <div class="mt-5 flex justify-center">
                            @if(!empty($stats['photo_url']))
                                <img src="{{ $stats['photo_url'] }}" alt="Photo" class="h-28 w-28 rounded-full object-cover border-4 border-white/20 shadow-2xl">
                            @else
                                <div class="h-28 w-28 rounded-full flex items-center justify-center bg-white/10 border-4 border-white/20 text-white font-black">EVC</div>
                            @endif
                        </div>
                        <div class="mt-4 text-2xl font-black text-white">{{ trim(($student->first_name ?? '').' '.($student->last_name ?? '')) }}</div>

                        <div class="mt-3 flex flex-wrap justify-center gap-2">
                            <span class="inline-flex items-center gap-2 rounded-full bg-black/20 border border-white/10 px-4 py-2 text-sm font-bold text-white">
                                <i class="fas fa-id-card text-orange-400"></i>
                                ID: {{ $maskStudentId($student->student_id ?? null) }}
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-full bg-black/20 border border-white/10 px-4 py-2 text-sm font-bold text-white">
                                <i class="fas fa-graduation-cap text-orange-400"></i>
                                {{ $student->program ?? 'Formation' }}
                            </span>
                        </div>

                        <div class="mt-4">
                            @if(!empty($stats['is_active']))
                                <span class="inline-flex items-center gap-2 rounded-full bg-emerald-500/10 border border-emerald-500/25 px-4 py-2 text-sm font-bold text-emerald-200">
                                    <i class="fas fa-badge-check"></i>
                                    Vous êtes officiellement étudiant(e) à EVC
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 rounded-full bg-rose-500/10 border border-rose-500/25 px-4 py-2 text-sm font-bold text-rose-200">
                                    <i class="fas fa-flag-checkered"></i>
                                    Vous avez été étudiant(e) à EVC
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-8 rounded-3xl border border-white/10 bg-black/15 p-5 sm:p-6">
                        <div class="text-xs font-black tracking-[0.16em] text-white/80">
                            <i class="fas fa-layer-group text-orange-400 mr-2"></i>STATUT DE FORMATION
                        </div>
                        @if(!empty($stats['is_active']))
                            <div class="mt-3 text-2xl font-black text-white">Votre formation est en cours.</div>
                            <div class="mt-2 text-white/70">Vous êtes officiellement étudiant(e) à EVC, et votre formation n'est pas encore terminée. Continuez votre progression : TP, projets, et dossier de fin de formation.</div>
                        @else
                            <div class="mt-3 text-2xl font-black text-white">Votre formation est terminée.</div>
                            <div class="mt-2 text-white/70">Vous avez été étudiant(e) à EVC, mais actuellement votre formation est terminée. Si vous souhaitez renouveler votre accès ou reprendre une formation, l'administration peut vous accompagner.</div>
                        @endif

                        <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-3">
                            <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                                <div class="text-xs font-semibold text-white/70">Date d'inscription</div>
                                <div class="mt-1 text-lg font-black text-white">
                                    @if(!empty($stats['registration_date']))
                                        {{ \Carbon\Carbon::parse($stats['registration_date'])->format('d/m/Y') }}
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                                <div class="text-xs font-semibold text-white/70">Fin estimée / Expiration</div>
                                <div class="mt-1 text-lg font-black text-white">
                                    @if(!empty($stats['expiration_date']))
                                        {{ \Carbon\Carbon::parse($stats['expiration_date'])->format('d/m/Y') }}
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                                <div class="text-xs font-semibold text-white/70">Jours restants</div>
                                <div class="mt-1 text-lg font-black text-white">
                                    @if(isset($stats['days_remaining']) && $stats['days_remaining'] !== null)
                                        {{ (int) $stats['days_remaining'] }}
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                            <div class="text-xs font-semibold text-white/70">Statut</div>
                            <div class="mt-1 text-base font-black text-white">{{ !empty($stats['is_active']) ? 'Étudiant officiel (actif)' : 'Ancien étudiant (formation terminée)' }}</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                            <div class="text-xs font-semibold text-white/70">Certification</div>
                            <div class="mt-1 text-base font-black text-white">{{ !empty($stats['certified']) ? 'Certifié' : 'Non certifié' }}</div>
                        </div>
                    </div>

                    <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                            <div class="text-xs font-semibold text-white/70">Projets Design</div>
                            <div class="mt-1 text-lg font-black text-white">{{ (int) ($stats['design_projects_total'] ?? 0) }}</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                            <div class="text-xs font-semibold text-white/70">TP (validés / requis)</div>
                            <div class="mt-1 text-lg font-black text-white">{{ (int) ($stats['tp_validated'] ?? 0) }} / {{ (int) ($stats['min_tp_required'] ?? 0) }}</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                            <div class="text-xs font-semibold text-white/70">Projets (validés)</div>
                            <div class="mt-1 text-lg font-black text-white">{{ (int) (($stats['projects_completed'] ?? 0) + ($stats['design_projects_validated'] ?? 0)) }}</div>
                        </div>
                    </div>

                    @php
                        $tpValidated = (int) ($stats['tp_validated'] ?? 0);
                        $tpTotal = (int) ($stats['tp_total'] ?? 0);
                        $tpAssigned = (int) ($stats['tp_assigned'] ?? 0);
                        $minTpRequired = max(1, (int) ($stats['min_tp_required'] ?? 0));

                        $projectsAssigned = (int) ($stats['projects_assigned'] ?? 0);
                        $projectsCompleted = (int) ($stats['projects_completed'] ?? 0);
                        $designProjectsValidated = (int) ($stats['design_projects_validated'] ?? 0);
                        $projectsValidated = $projectsCompleted + $designProjectsValidated;
                        $minProjectsRequired = max(1, (int) ($stats['min_projects_required'] ?? 0));

                        $reportUploaded = !empty($stats['report_uploaded']);
                        $isCertified = !empty($stats['certified']);

                        $tpProgress = min(1, $tpValidated / $minTpRequired);
                        $projectsProgress = min(1, $projectsValidated / $minProjectsRequired);
                        $reportProgress = $reportUploaded ? 1 : 0;

                        $certProgress = $isCertified ? 1 : (($tpProgress + $projectsProgress + $reportProgress) / 3);
                        $certProgressPct = (int) round($certProgress * 100);
                    @endphp

                    <div class="mt-6 rounded-3xl border border-white/10 bg-black/15 p-5 sm:p-6">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="text-xs font-black tracking-[0.16em] text-white/80">
                                <i class="fas fa-certificate text-orange-400 mr-2"></i>PROGRESSION CERTIFICATION
                            </div>
                            <div class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/10 px-4 py-2 text-sm font-black text-white">
                                <span>{{ $certProgressPct }}%</span>
                                @if($isCertified)
                                    <span class="text-emerald-300">Certifié</span>
                                @else
                                    <span class="text-white/70">en cours</span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 h-3 w-full rounded-full bg-white/10 overflow-hidden border border-white/10">
                            <div class="h-full rounded-full bg-gradient-to-r from-orange-400 via-orange-500 to-blue-500" style="width: {{ $certProgressPct }}%;"></div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
                            <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                                <div class="text-xs font-semibold text-white/70">TP</div>
                                <div class="mt-1 text-base font-black text-white">{{ $tpValidated }} / {{ $minTpRequired }}</div>
                                <div class="mt-1 text-xs text-white/60">TP assignés : {{ $tpAssigned }}</div>
                                <div class="mt-1 text-xs text-white/60">Total TP : {{ $tpTotal }}</div>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                                <div class="text-xs font-semibold text-white/70">Projets</div>
                                <div class="mt-1 text-base font-black text-white">{{ $projectsValidated }} / {{ $minProjectsRequired }}</div>
                                <div class="mt-1 text-xs text-white/60">Projets assignés : {{ $projectsAssigned }}</div>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                                <div class="text-xs font-semibold text-white/70">Rapport</div>
                                <div class="mt-1 text-base font-black {{ $reportUploaded ? 'text-emerald-200' : 'text-orange-200' }}">
                                    {{ $reportUploaded ? 'Uploadé' : 'Non uploadé' }}
                                </div>
                                <div class="mt-1 text-xs text-white/60">Condition obligatoire</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap justify-center gap-3">
                        @if(!empty($stats['eligible']))
                            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-500/10 border border-emerald-500/25 px-4 py-2 text-sm font-bold text-emerald-200">
                                <i class="fas fa-check-circle"></i>
                                Éligible au certificat
                            </span>
                            <a target="_blank" href="{{ route('auth.verify-id.certificate.preview', ['student_id' => $student->student_id]) }}" class="inline-flex items-center justify-center rounded-full bg-white px-6 py-3 text-sm font-black text-orange-600 hover:bg-orange-50">
                                <i class="fas fa-eye mr-2"></i>Voir le certificat
                            </a>
                        @else
                            <span class="inline-flex items-center gap-2 rounded-full bg-orange-500/10 border border-orange-500/25 px-4 py-2 text-sm font-bold text-orange-200">
                                <i class="fas fa-hourglass-half"></i>
                                Certificat non éligible
                            </span>
                        @endif
                    </div>

                    <div class="mt-8 flex justify-center">
                        <button type="button" onclick="window.print()" class="rounded-full bg-white/10 px-8 py-4 text-sm font-black text-white hover:bg-white/15 border border-white/10">
                            <i class="fas fa-print mr-2"></i>Imprimer
                        </button>
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
@endsection

@push('scripts')
<script>
    const verifyIdBtn = document.getElementById('verifyIdBtn');
    const verifyIdText = document.getElementById('verifyIdText');
    const verifyIdSpinner = document.getElementById('verifyIdSpinner');
    const verifyOverlay = document.getElementById('verifyOverlay');
    const verifyOverlayId = document.getElementById('verifyOverlayId');
    const verifyOverlayBar = document.getElementById('verifyOverlayBar');
    const verifyOverlayPct = document.getElementById('verifyOverlayPct');

    const maskStudentIdClient = function(id) {
        const v = (id || '').toString().trim();
        if (!v) return '—';
        const m = v.match(/^([A-Za-z]+-\d{4}-)(\d+)$/);
        if (m) {
            const prefix = m[1];
            const digits = m[2];
            if (digits.length <= 2) return prefix + '*'.repeat(digits.length);
            return prefix + '*'.repeat(digits.length - 2) + digits.slice(-2);
        }
        if (v.length <= 6) return '*'.repeat(v.length);
        return v.slice(0, 4) + '*'.repeat(Math.max(0, v.length - 6)) + v.slice(-2);
    };

    if (verifyIdBtn) {
        const form = verifyIdBtn.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                verifyIdBtn.disabled = true;
                if (verifyIdText) verifyIdText.style.display = 'none';
                if (verifyIdSpinner) verifyIdSpinner.style.display = 'inline-flex';

                const input = form.querySelector('input[name="student_id"]');
                const enteredId = input ? (input.value || '').trim() : '';
                if (verifyOverlay) {
                    verifyOverlay.classList.add('show');
                    verifyOverlay.setAttribute('aria-hidden', 'false');
                }
                if (verifyOverlayId) {
                    verifyOverlayId.textContent = enteredId !== '' ? maskStudentIdClient(enteredId) : '—';
                }

                const durationMs = 1500;
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
                        form.submit();
                    }
                }, 30);
            });
        }
    }
</script>
@endpush
