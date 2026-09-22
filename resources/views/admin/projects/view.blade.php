@extends('layouts.admin')

@section('title', 'Détails du Projet - ' . $project->title)

@push('styles')
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
<style>
    .pv-header {
        background: linear-gradient(135deg, rgba(139,92,246,0.15) 0%, rgba(59,130,246,0.10) 100%);
        border: 1px solid rgba(139,92,246,0.3);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .pv-header-icon {
        width: 56px; height: 56px; border-radius: 14px;
        background: linear-gradient(135deg, #8b5cf6, #6366f1);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.4rem; flex-shrink: 0;
    }
    .pv-meta-badge {
        display: inline-flex; align-items: center; gap: 0.35rem;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.1);
        color: #cbd5e1; font-size: 0.78rem;
        padding: 0.3rem 0.7rem; border-radius: 20px;
    }
    .pv-section-title {
        font-size: 0.72rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.6px;
        color: #94a3b8; margin-bottom: 0.6rem;
    }
    .pv-rich {
        color: rgba(255,255,255,0.9);
        background: rgba(255,255,255,0.04);
        border-left: 3px solid #8b5cf6;
        border-radius: 0 10px 10px 0;
        padding: 1rem 1.25rem;
    }
    .pv-rich h1, .pv-rich h2, .pv-rich h3, .pv-rich h4 { color: #fff; margin: 0.75rem 0 0.4rem; font-weight: 700; }
    .pv-rich h1 { font-size: 1.3rem; } .pv-rich h2 { font-size: 1.15rem; } .pv-rich h3 { font-size: 1rem; }
    .pv-rich p { margin-bottom: 0.5rem; }
    .pv-rich ul, .pv-rich ol { padding-left: 1.25rem; margin-bottom: 0.5rem; }
    .pv-rich a { color: #8b5cf6; word-break: break-all; }
    .pv-rich img { max-width: 100%; border-radius: 8px; }

    .pv-sw-badge {
        background: rgba(59,130,246,0.15); color: #60a5fa;
        border: 1px solid rgba(59,130,246,0.25);
        padding: 0.35rem 0.8rem; border-radius: 20px; font-size: 0.8rem;
    }

    .pv-file-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 12px; overflow: hidden;
        transition: all 0.2s ease; height: 100%;
    }
    .pv-file-card:hover { border-color: rgba(139,92,246,0.4); transform: translateY(-2px); }
    .pv-file-thumb {
        height: 110px; display: flex; align-items: center; justify-content: center;
        background: rgba(15,23,42,0.5); overflow: hidden; position: relative;
    }
    .pv-file-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .pv-file-ext {
        position: absolute; top: 6px; right: 6px;
        background: rgba(0,0,0,0.7); color: #fff;
        font-size: 0.62rem; font-weight: 700;
        padding: 0.15rem 0.45rem; border-radius: 6px;
    }
    .pv-file-actions { display: flex; gap: 4px; padding: 0.5rem; }

    .pv-student-card {
        display: flex; align-items: center; gap: 0.85rem;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 12px; padding: 1rem;
    }
    .pv-avatar {
        width: 48px; height: 48px; border-radius: 50%;
        object-fit: cover; flex-shrink: 0;
        border: 2px solid rgba(139,92,246,0.4);
    }
    .pv-avatar-fallback {
        width: 48px; height: 48px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg, #8b5cf6, #6366f1);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 700; font-size: 1.1rem;
        border: 2px solid rgba(139,92,246,0.4);
    }
    .pv-action-btn {
        display: flex; align-items: center; gap: 0.6rem;
        width: 100%; padding: 0.7rem 1rem;
        border-radius: 10px; border: 1px solid rgba(255,255,255,0.1);
        background: rgba(255,255,255,0.04); color: #e2e8f0;
        font-size: 0.88rem; font-weight: 500; text-decoration: none;
        transition: all 0.2s ease;
    }
    .pv-action-btn:hover { transform: translateX(3px); color: #fff; }
    .pv-action-btn.pv-edit:hover { border-color: #f59e0b; background: rgba(245,158,11,0.1); }
    .pv-action-btn.pv-validate:hover { border-color: #10b981; background: rgba(16,185,129,0.1); }
    .pv-action-btn.pv-student:hover { border-color: #38bdf8; background: rgba(56,189,248,0.1); }
    .pv-action-btn.pv-delete { border-color: rgba(239,68,68,0.25); color: #f87171; }
    .pv-action-btn.pv-delete:hover { border-color: #ef4444; background: rgba(239,68,68,0.12); color: #fca5a5; }

    .pv-info-row { display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid rgba(255,255,255,0.06); }
    .pv-info-row:last-child { border-bottom: none; }
    .pv-info-row .lbl { font-size: 0.78rem; color: #94a3b8; }
    .pv-info-row .val { font-size: 0.85rem; color: #e2e8f0; font-weight: 500; }

    /* Modale loading email */
    .email-loading-modal { position: fixed; inset: 0; z-index: 10000; opacity: 0; visibility: hidden; transition: all 0.3s ease; }
    .email-loading-modal.show { opacity: 1; visibility: visible; }
    .email-loading-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.8); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; }
    .email-loading-content { background: #fff; padding: 2rem; border-radius: 20px; text-align: center; min-width: 340px; max-width: 460px; transform: scale(0.85); transition: transform 0.3s ease; }
    .email-loading-modal.show .email-loading-content { transform: scale(1); }
    .email-step { display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 0; color: #94a3b8; }
    .email-step.active { color: #1e293b; font-weight: 600; }
    .email-step.completed { color: #10b981; }
    .email-loading-progress { height: 5px; background: #e2e8f0; border-radius: 10px; margin-top: 1rem; overflow: hidden; }
    .email-loading-progress .progress-bar { height: 100%; background: linear-gradient(90deg,#8b5cf6,#6366f1); width: 0%; transition: width 0.4s ease; }
</style>
@endpush

@section('content')
@php
    $statusMeta = [
        'valide'   => ['label' => 'Validé',           'class' => 'bg-success',           'icon' => 'check-circle'],
        'en_cours' => ['label' => 'Pas encore fait',  'class' => 'bg-warning text-dark', 'icon' => 'hourglass-half'],
        'termine'  => ['label' => 'Terminé',          'class' => 'bg-info text-dark',    'icon' => 'flag-checkered'],
        'rejete'   => ['label' => 'Rejeté',           'class' => 'bg-danger',            'icon' => 'times-circle'],
    ];
    $sm = $statusMeta[$project->status] ?? ['label' => $project->status ?? '—', 'class' => 'bg-secondary', 'icon' => 'question'];

    $student = $project->user->student ?? null;
    $studentName = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
    if ($studentName === '') { $studentName = $project->user->name ?? 'Utilisateur'; }
    $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($student?->profile_photo ?? null);

    $swList = is_array($project->software_used)
        ? $project->software_used
        : (is_string($project->software_used) && $project->software_used !== ''
            ? (json_decode($project->software_used, true) ?? explode(',', $project->software_used))
            : []);
@endphp

<div class="container-fluid py-4" style="max-width: 1400px;">

    {{-- ═══ Header ═══ --}}
    <div class="pv-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div class="d-flex align-items-start gap-3">
                <div class="pv-header-icon"><i class="fas fa-project-diagram"></i></div>
                <div>
                    <h2 class="text-white mb-1" style="font-size:1.4rem; font-weight:700;">{{ $project->title }}</h2>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge {{ $sm['class'] }}"><i class="fas fa-{{ $sm['icon'] }} me-1"></i>{{ $sm['label'] }}</span>
                        <span class="pv-meta-badge"><i class="fas fa-tag"></i>{{ $project->category ?? '—' }}</span>
                        <span class="pv-meta-badge"><i class="fas fa-hashtag"></i>#{{ $project->id }}</span>
                        @if($project->deadline)
                        <span class="pv-meta-badge"><i class="fas fa-clock text-warning"></i>Deadline {{ $project->deadline->format('d/m/Y') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit me-1"></i>Modifier
                </a>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Retour
                </a>
            </div>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        {{-- ═══ Colonne principale ═══ --}}
        <div class="col-lg-8">

            {{-- Description --}}
            @if($project->description)
            <div class="form-card mb-4">
                <div class="form-card-header">
                    <i class="fas fa-align-left"></i>
                    <h3>Description / Brief</h3>
                </div>
                <div class="form-card-body">
                    <div class="pv-rich">{!! html_entity_decode($project->description, ENT_QUOTES | ENT_HTML5, 'UTF-8') !!}</div>
                </div>
            </div>
            @endif

            {{-- Fichiers --}}
            <div class="form-card mb-4">
                <div class="form-card-header">
                    <i class="fas fa-folder-open"></i>
                    <h3>Fichiers du projet ({{ $project->images->count() }})</h3>
                </div>
                <div class="form-card-body">
                    @if($project->images->count() > 0)
                        <div class="row g-3">
                            @foreach($project->images as $image)
                                @php
                                    $filePath = $image->file_path ?? $image->path ?? '';
                                    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                                    $fileUrl = \App\Models\MediaUrl::fromPath($filePath);
                                @endphp
                                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-6">
                                    <div class="pv-file-card">
                                        <div class="pv-file-thumb">
                                            @if($isImage)
                                                <a href="{{ $fileUrl }}" target="_blank"><img src="{{ $fileUrl }}" alt="{{ $image->filename ?? 'Fichier' }}" loading="lazy"></a>
                                            @elseif($extension === 'pdf')
                                                <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                            @else
                                                <i class="fas fa-file fa-2x text-secondary"></i>
                                            @endif
                                            <span class="pv-file-ext">{{ strtoupper($extension) }}</span>
                                        </div>
                                        <div class="px-2 pt-2">
                                            <div class="text-white text-truncate" style="font-size:0.75rem;" title="{{ $image->filename ?? 'Fichier' }}">{{ $image->filename ?? 'Fichier' }}</div>
                                            <div class="text-muted" style="font-size:0.68rem;">{{ $image->created_at->format('d/m/Y') }}</div>
                                        </div>
                                        <div class="pv-file-actions">
                                            <a href="{{ $fileUrl }}" target="_blank" class="btn btn-outline-primary btn-sm flex-fill" style="font-size:0.72rem;" title="Voir"><i class="fas fa-eye"></i></a>
                                            <a href="{{ $fileUrl }}" download class="btn btn-outline-success btn-sm flex-fill" style="font-size:0.72rem;" title="Télécharger"><i class="fas fa-download"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3 d-block"></i>
                            <span class="text-white-50">Aucun fichier associé à ce projet.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ═══ Sidebar ═══ --}}
        <div class="col-lg-4">

            {{-- Étudiant --}}
            <div class="form-card mb-4">
                <div class="form-card-header">
                    <i class="fas fa-user-graduate"></i>
                    <h3>Étudiant</h3>
                </div>
                <div class="form-card-body">
                    <div class="pv-student-card mb-3">
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}" alt="{{ $studentName }}" class="pv-avatar">
                        @else
                            <div class="pv-avatar-fallback">{{ strtoupper(substr($studentName, 0, 1)) }}</div>
                        @endif
                        <div style="min-width:0;">
                            <div class="text-white fw-semibold text-truncate">{{ $studentName }}</div>
                            <small class="text-muted text-truncate d-block">{{ $project->user->email ?? '' }}</small>
                            @if($student?->program)
                            <span class="badge mt-1" style="background:rgba(139,92,246,0.15); color:#a78bfa; font-size:0.68rem;">{{ $student->program }}</span>
                            @endif
                        </div>
                    </div>
                    @if(!empty($student?->id))
                        <a href="{{ route('admin.students.profile', $student->id) }}" class="pv-action-btn pv-student">
                            <i class="fas fa-user" style="color:#38bdf8;"></i>Voir le profil étudiant
                        </a>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="form-card mb-4">
                <div class="form-card-header">
                    <i class="fas fa-bolt"></i>
                    <h3>Actions</h3>
                </div>
                <div class="form-card-body d-flex flex-column gap-2">
                    <a href="{{ route('admin.projects.edit', $project->id) }}" class="pv-action-btn pv-edit">
                        <i class="fas fa-edit" style="color:#f59e0b;"></i>Modifier le projet
                    </a>

                    @if($project->status !== 'valide')
                    <form action="{{ route('admin.projects.validate', $project->id) }}" method="POST" onsubmit="return handleValidationSubmit(event)">
                        @csrf
                        <button type="submit" class="pv-action-btn pv-validate">
                            <i class="fas fa-check-circle" style="color:#10b981;"></i>Valider le projet
                        </button>
                    </form>
                    @endif

                    <form action="{{ route('admin.projects.assigned.delete', $project->id) }}" method="POST" onsubmit="return handleDeleteSubmit(event)">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="pv-action-btn pv-delete">
                            <i class="fas fa-trash" style="color:#f87171;"></i>Supprimer le projet
                        </button>
                    </form>
                </div>
            </div>

            {{-- Informations --}}
            <div class="form-card">
                <div class="form-card-header">
                    <i class="fas fa-info-circle"></i>
                    <h3>Informations</h3>
                </div>
                <div class="form-card-body">
                    <div class="pv-info-row"><span class="lbl">Statut</span><span class="badge {{ $sm['class'] }}">{{ $sm['label'] }}</span></div>
                    <div class="pv-info-row"><span class="lbl">Catégorie</span><span class="val">{{ $project->category ?? '—' }}</span></div>
                    @if($project->deadline)
                    <div class="pv-info-row"><span class="lbl">Deadline</span><span class="val">{{ $project->deadline->format('d/m/Y') }}</span></div>
                    @endif
                    <div class="pv-info-row"><span class="lbl">Créé le</span><span class="val">{{ $project->created_at->format('d/m/Y à H:i') }}</span></div>
                    <div class="pv-info-row"><span class="lbl">Modifié le</span><span class="val">{{ $project->updated_at->format('d/m/Y à H:i') }}</span></div>
                    <div class="pv-info-row"><span class="lbl">Fichiers</span><span class="val">{{ $project->images->count() }}</span></div>
                </div>
            </div>

            {{-- Logiciels + Lien --}}
            @if(count($swList) > 0 || $project->link)
            <div class="form-card mt-4">
                <div class="form-card-header">
                    <i class="fas fa-tools"></i>
                    <h3>Ressources</h3>
                </div>
                <div class="form-card-body">
                    @if(count($swList) > 0)
                    <div class="mb-3">
                        <div class="pv-section-title">Logiciels utilisés</div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($swList as $sw)
                                <span class="pv-sw-badge"><i class="fas fa-cog me-1"></i>{{ trim($sw) }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($project->link)
                    <div>
                        <div class="pv-section-title">Lien externe</div>
                        <a href="{{ $project->link }}" target="_blank" class="pv-action-btn" style="border-color:rgba(139,92,246,0.3);">
                            <i class="fas fa-external-link-alt" style="color:#8b5cf6;"></i>
                            <span class="text-truncate">{{ $project->link }}</span>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Modale de loading pour les actions avec envoi d'email (validation / suppression)
function showEmailLoadingModal(action) {
    hideEmailLoadingModal();
    const isValidation = action === 'validation';
    const loadingModal = document.createElement('div');
    loadingModal.id = 'emailLoadingModal';
    loadingModal.className = 'email-loading-modal';
    loadingModal.innerHTML = `
        <div class="email-loading-overlay">
            <div class="email-loading-content">
                <div style="font-size:2.5rem; color:${isValidation ? '#10b981' : '#ef4444'};" class="mb-3">${isValidation ? '✅' : '🗑️'}</div>
                <h5 class="mb-3" style="color:#1e293b;">${isValidation ? 'Validation' : 'Suppression'} en cours...</h5>
                <div class="email-step active" id="step1"><span>⚡</span><span>Traitement du projet</span></div>
                <div class="email-step" id="step2"><span>📧</span><span>Envoi de l'email</span></div>
                <div class="email-step" id="step3"><span>✨</span><span>Finalisation</span></div>
                <div class="email-loading-progress"><div class="progress-bar" id="emailProgressBar"></div></div>
                <small class="text-muted d-block mt-2">L'étudiant sera notifié par email...</small>
            </div>
        </div>`;
    document.body.appendChild(loadingModal);
    setTimeout(() => {
        loadingModal.classList.add('show');
        const steps = ['step1', 'step2', 'step3'];
        let i = 0;
        (function next() {
            if (i > 0) {
                const prev = document.getElementById(steps[i - 1]);
                if (prev) { prev.classList.remove('active'); prev.classList.add('completed'); }
            }
            if (i < steps.length) {
                document.getElementById(steps[i])?.classList.add('active');
                const bar = document.getElementById('emailProgressBar');
                if (bar) bar.style.width = ((i + 1) / steps.length * 100) + '%';
                i++;
                setTimeout(next, 800);
            }
        })();
    }, 10);
}

function hideEmailLoadingModal() {
    const m = document.getElementById('emailLoadingModal');
    if (m) { m.classList.remove('show'); setTimeout(() => m.remove(), 300); }
}

function handleValidationSubmit(event) {
    if (!confirm('Voulez-vous valider ce projet et envoyer un email à l\'étudiant ?')) {
        event.preventDefault();
        return false;
    }
    showEmailLoadingModal('validation');
    return true;
}

function handleDeleteSubmit(event) {
    if (!confirm('ATTENTION : action irréversible !\n\nSupprimer définitivement ce projet et tous ses fichiers ?\nL\'étudiant sera notifié par email.')) {
        event.preventDefault();
        return false;
    }
    showEmailLoadingModal('suppression');
    return true;
}
</script>
@endpush
@endsection
