@extends('layouts.ki-admin')

@section('title', 'Mes TP à Faire')

@section('page-title', 'Mes Projets à faire (ToDo List)')

@push('styles')
<style>
    /* Palette Bleu */
    :root {
        --blue-900: #1e3c72;
        --blue-700: #2a5298;
        --blue-500: #4fc3f7;
        --blue-300: #93c5fd;
    }

    /* Header avec dégradé Bleu */
    .instagram-header {
        background: linear-gradient(135deg, var(--blue-900), var(--blue-700), var(--blue-500));
        padding: 2rem;
        border-radius: 20px;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(42, 82, 152, 0.35);
        animation: fadeInDown 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .instagram-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .instagram-header p {
        opacity: 0.95;
        margin: 0;
        font-size: 1rem;
    }

    .icon-circle {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        animation: pulse 2s infinite;
    }

    /* Cartes statistiques */
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        height: 100%;
        border: 2px solid transparent;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(42, 82, 152, 0.2);
        border-color: var(--blue-700);
    }

    .stat-card .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        margin-bottom: 1rem;
    }

    .stat-card .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 0.25rem;
    }

    .stat-card .stat-label {
        font-size: 0.875rem;
        color: #718096;
    }

    /* Carte TP */
    .tp-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        height: 100%;
        min-height: 400px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.5s ease;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .col-md-6.col-lg-3 {
        display: flex;
        flex-direction: column;
    }

    .tp-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--blue-900), var(--blue-700), var(--blue-500));
    }

    .tp-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(42, 82, 152, 0.2);
        border-color: var(--blue-700);
    }

    .tp-card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.75rem;
    }

    .tp-card-icon {
        width: 50px;
        height: 50px;
        flex-shrink: 0;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        background: linear-gradient(135deg, var(--blue-900), var(--blue-500));
        box-shadow: 0 4px 15px rgba(42, 82, 152, 0.3);
    }

    .tp-card-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
    }

    .tp-card.status-assigned::before {
        background: linear-gradient(90deg, var(--blue-700), var(--blue-500));
    }

    .tp-card.status-submitted::before {
        background: linear-gradient(90deg, #fbbf24, #f59e0b);
    }

    .tp-card.status-validated::before {
        background: linear-gradient(90deg, #10b981, #059669);
    }

    .tp-card.status-rejected::before {
        background: linear-gradient(90deg, #ef4444, #dc2626);
    }

    .tp-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 0;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .tp-description {
        color: #4a5568;
        margin-bottom: 1rem;
        line-height: 1.5;
        font-size: 0.85rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .tp-meta {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1rem;
        padding: 0.75rem;
        background: linear-gradient(135deg, rgba(30, 60, 114, 0.06), rgba(79, 195, 247, 0.08));
        border-radius: 10px;
        border: 1px solid rgba(42, 82, 152, 0.15);
    }

    .tp-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        color: #4a5568;
    }

    .tp-meta-item i {
        color: var(--blue-700);
        font-size: 0.85rem;
        width: 16px;
    }

    /* Badges de statut */
    .badge-status {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .badge-assigned {
        background: linear-gradient(135deg, #f59e0b, #f97316);
        color: white;
    }

    .badge-submitted {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: white;
    }

    .badge-validated {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .badge-rejected {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    /* Boutons */
    .btn-instagram {
        background: linear-gradient(135deg, var(--blue-900), var(--blue-500));
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-instagram:hover {
        background: linear-gradient(135deg, var(--blue-700), var(--blue-500));
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(42, 82, 152, 0.3);
        color: white;
    }

    .btn-outline-instagram {
        border: 2px solid var(--blue-700);
        color: var(--blue-700);
        background: white;
        padding: 0.75rem 1.5rem;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-outline-instagram:hover {
        background: var(--blue-700);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(42, 82, 152, 0.3);
    }

    /* Message vide */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .empty-state i {
        font-size: 5rem;
        background: linear-gradient(135deg, var(--blue-900), var(--blue-500));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1.5rem;
    }

    .empty-state h3 {
        color: #1a202c;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: #718096;
        margin-bottom: 0;
    }

    /* Animations */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    /* Deadline urgente */
    .deadline-urgent {
        color: #ef4444;
        font-weight: 600;
        animation: pulse 2s infinite;
    }

    .deadline-soon {
        color: #f59e0b;
        font-weight: 600;
    }

    .deadline-ok {
        color: #10b981;
    }

    /* Fichiers */
    .files-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .file-badge {
        background: rgba(42, 82, 152, 0.1);
        color: var(--blue-900);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: 1px solid rgba(42, 82, 152, 0.2);
    }

    /* Alert personnalisé */
    .alert-instagram {
        background: linear-gradient(135deg, rgba(30, 60, 114, 0.08), rgba(79, 195, 247, 0.12));
        border-left: 4px solid var(--blue-700);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .tp-details-description{background:#f8f9fa;padding:1.5rem;border-radius:12px;line-height:1.95;color:#111827;word-break:break-word;white-space:normal;}
    .tp-details-description h1,.tp-details-description h2,.tp-details-description h3,.tp-details-description h4{margin:1.1rem 0 0.6rem;font-weight:900;color:#0f172a;}
    .tp-details-description h1{font-size:1.2rem;}
    .tp-details-description h2{font-size:1.1rem;}
    .tp-details-description h3,.tp-details-description h4{font-size:1.05rem;}
    .tp-details-description p{margin:0 0 0.95rem;color:#111827;}
    .tp-details-description ul,.tp-details-description ol{margin:0 0 0.95rem;padding-left:1.25rem;}
    .tp-details-description li{margin:0.25rem 0;color:#111827;}
    .tp-details-description strong,.tp-details-description b{font-weight:800;}
    .tp-details-description a{color:var(--blue-700);text-decoration:underline;}
    .tp-details-description blockquote{margin:0 0 0.95rem;padding:0.75rem 1rem;border-left:4px solid rgba(42,82,152,0.25);background:rgba(42,82,152,0.06);border-radius:10px;}

    .tp-details-description .tp-details-heading{margin:0.25rem 0 0.6rem;font-weight:900;letter-spacing:.04em;color:#0f172a;}
    .tp-details-description .tp-details-line{margin:0 0 0.7rem;color:#111827;}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="instagram-header">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-circle">
                    <i class="fas fa-tasks"></i>
                </div>
                <div>
                    <h1>Mes Projets à faire (ToDo List)</h1>
                    <p>
                        @if($student)
                            Formation : {{ $student->program }}
                        @else
                            Gérez vos TP et suivez votre progression
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages -->
    @if(isset($error))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 16px; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.2); margin-bottom: 1.5rem; font-weight: 600;">
            <i class="fas fa-exclamation-circle me-2"></i>{{ $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-dismissible fade show" role="alert" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(5, 150, 105, 0.15)); border-left: 4px solid #10b981; border-radius: 16px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2); margin-bottom: 1.5rem; font-weight: 600; color: #047857; padding: 1.25rem;">
            <i class="fas fa-check-circle me-2" style="color: #10b981; font-size: 1.2rem;"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 16px; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.2); margin-bottom: 1.5rem; font-weight: 600;">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, var(--blue-900), var(--blue-500));">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-number">{{ $stats['total'] }}</div>
                <div class="stat-label">Total TP</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, var(--blue-700), var(--blue-500));">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number">{{ $stats['assigned'] }}</div>
                <div class="stat-label">Traiter</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #fbbf24, #f59e0b);">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div class="stat-number">{{ $stats['submitted'] }}</div>
                <div class="stat-label">Soumis</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number">{{ $stats['validated'] }}</div>
                <div class="stat-label">Validés</div>
            </div>
        </div>
    </div>

    <!-- Liste des TP -->
    <div class="row g-4">
        @if($tpAssignments->isEmpty())
            <div class="col-12">
                <!-- État vide -->
                <div class="empty-state">
                    <i class="fas fa-clipboard-check"></i>
                    <h3>Aucun TP assigné pour le moment</h3>
                    <p>Vous n'avez pas encore de travaux pratiques à réaliser. Votre formateur vous assignera des TP bientôt !</p>
                </div>
            </div>
        @else
            @foreach($tpAssignments as $index => $tp)
            <div class="col-md-6 col-lg-4">
                    @php
                        $deadline = \Carbon\Carbon::parse($tp->deadline);
                        $now = \Carbon\Carbon::now();
                        $daysLeft = $now->diffInDays($deadline, false);

                        $deadlineClass = 'deadline-ok';
                        if ($daysLeft < 0) {
                            $deadlineClass = 'deadline-urgent';
                        } elseif ($daysLeft <= 3) {
                            $deadlineClass = 'deadline-soon';
                        }

                        $statusLabels = [
                            'assigned' => 'À faire',
                            'submitted' => 'Soumis',
                            'validated' => 'Validé',
                            'rejected' => 'Rejeté'
                        ];
                    @endphp

                    <div class="tp-card status-{{ $tp->status }}">
                        <!-- Header avec icône et titre -->
                        <div class="tp-card-header">
                            <div class="tp-card-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="tp-card-content">
                                <div class="tp-title">
                                    {{ $tp->title }}
                                </div>
                            </div>
                        </div>

                        <!-- Badge de statut -->
                        <div class="mb-2">
                            <span class="badge-status badge-{{ $tp->status }}" style="font-size: 0.75rem; padding: 0.35rem 0.75rem;">
                                <i class="fas fa-circle" style="font-size: 0.4rem;"></i>
                                {{ $statusLabels[$tp->status] ?? $tp->status }}
                            </span>
                        </div>

                        <!-- Description -->
                        <div class="tp-description">
                            {!! Str::limit(strip_tags($tp->description), 120) !!}
                        </div>

                        <!-- Métadonnées -->
                        <div class="tp-meta">
                            <div class="tp-meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span><strong>Assigné:</strong> {{ \Carbon\Carbon::parse($tp->created_at)->format('d/m/Y') }}</span>
                            </div>
                            <div class="tp-meta-item {{ $deadlineClass }}">
                                <i class="fas fa-clock"></i>
                                <span>
                                    <strong>Échéance:</strong>
                                    @if($daysLeft < 0)
                                        <strong style="color: #ef4444;">Dépassé</strong>
                                    @elseif($daysLeft == 0)
                                        <strong style="color: #f59e0b;">Aujourd'hui</strong>
                                    @elseif($daysLeft <= 3)
                                        <strong style="color: #f59e0b;">{{ $deadline->format('d/m/Y') }}</strong>
                                    @else
                                        {{ $deadline->format('d/m/Y') }}
                                    @endif
                                </span>
                            </div>
                            @if($tp->files && $tp->files->count() > 0)
                            <div class="tp-meta-item">
                                <i class="fas fa-paperclip"></i>
                                <span>{{ $tp->files->count() }} fichier(s)</span>
                            </div>
                            @endif
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex flex-column gap-2 mt-auto">
                            <button class="btn btn-outline-instagram w-100" onclick="showDetails({{ $tp->id }})" style="padding: 0.6rem;">
                                <i class="fas fa-eye"></i>
                                Voir détails
                            </button>

                            @if($tp->status === 'assigned')
                                <a href="{{ route($formationPrefix . '.todo.traiter', $tp->id) }}" class="btn btn-instagram w-100" style="text-decoration: none; padding: 0.6rem; color: #fff;">
                                    <i class="fas fa-clock"></i>
                                    Traiter
                                </a>
                            @elseif($tp->status === 'submitted')
                                <button class="btn w-100" style="background: linear-gradient(135deg, #fbbf24, #f59e0b); color: white; border-radius: 30px; padding: 0.6rem; font-weight: 600;" disabled>
                                    <i class="fas fa-hourglass-half"></i>
                                    En attente
                                </button>
                            @elseif($tp->status === 'validated')
                                <button class="btn w-100" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 30px; padding: 0.6rem; font-weight: 600;" disabled>
                                    <i class="fas fa-check-circle"></i>
                                    Validé ✓
                                </button>
                            @elseif($tp->status === 'rejected')
                                <button class="btn w-100" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white; border-radius: 30px; padding: 0.6rem; font-weight: 600;" disabled>
                                    <i class="fas fa-times-circle"></i>
                                    Rejeté
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
    </div>
</div>

<!-- Modal pour soumettre un TP -->
<div class="modal fade" id="submitTpModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--blue-900), var(--blue-500)); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title">
                    <i class="fas fa-paper-plane me-2"></i>Soumettre votre TP
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="submitTpForm" method="POST" style="margin: 0;">
                @csrf
                <div class="modal-body" style="padding: 2rem;">
                    <input type="hidden" id="submit_tp_id" name="tp_id">

                    <div class="mb-4">
                    <h6 id="submitTpTitle" style="color: var(--blue-900); font-weight: 600; margin-bottom: 1rem;"></h6>
                </div>

                <div class="alert" style="background: linear-gradient(135deg, rgba(30, 60, 114, 0.08), rgba(79, 195, 247, 0.12)); border-left: 4px solid var(--blue-700); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem;">
                    <i class="fas fa-info-circle" style="color: var(--blue-700);"></i>
                    <strong style="color: var(--blue-900);">Instructions :</strong><br>
                    <span style="color: #555;">Soumettez le lien vers votre travail (Google Drive, Dropbox, GitHub, etc.)</span>
                </div>

                    <div class="mb-3">
                        <label for="submission_link" class="form-label" style="font-weight: 600; color: #2c3e50;">
                            <i class="fas fa-link me-2"></i>Lien de soumission *
                        </label>
                        <input
                            type="url"
                            class="form-control"
                            id="submission_link"
                            name="submission_link"
                            placeholder="https://drive.google.com/..."
                            required
                            style="border-radius: 12px; border: 2px solid #e2e8f0; padding: 0.75rem 1rem;"
                        >
                        <small class="text-muted">
                            <i class="fas fa-lightbulb me-1"></i>
                            Exemple : Lien vers votre dossier Google Drive, Dropbox ou autre
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="submission_files" class="form-label" style="font-weight: 600; color: #2c3e50;">
                            <i class="fas fa-file-upload me-2"></i>Fichiers (optionnel)
                        </label>
                        <div class="file-upload-zone" id="fileUploadZone" style="border: 2px dashed #e2e8f0; border-radius: 12px; padding: 2rem; text-align: center; transition: all 0.3s; cursor: pointer;">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 3rem; color: var(--blue-700); margin-bottom: 1rem;"></i>
                            <p style="margin: 0; color: #666; font-weight: 600;">Cliquez pour sélectionner des fichiers</p>
                            <p style="margin: 0.5rem 0 0 0; color: #999; font-size: 0.85rem;">ou glissez-déposez vos fichiers ici</p>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Formats acceptés : PDF, Images, Zip (max 10 Mo par fichier)
                            </small>
                        </div>
                        <input
                            type="file"
                            class="d-none"
                            id="submission_files"
                            name="files[]"
                            multiple
                            accept=".pdf,.jpg,.jpeg,.png,.gif,.zip,.rar,.doc,.docx,.ppt,.pptx"
                        >
                        <div id="filesList" class="mt-3" style="display: none;">
                            <strong style="color: #2c3e50; display: block; margin-bottom: 0.5rem;">
                                <i class="fas fa-paperclip me-2"></i>Fichiers sélectionnés :
                            </strong>
                            <div id="filesContainer" style="display: flex; flex-direction: column; gap: 0.5rem;"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="submission_comment" class="form-label" style="font-weight: 600; color: #2c3e50;">
                            <i class="fas fa-comment me-2"></i>Commentaire (optionnel)
                        </label>
                        <textarea
                            class="form-control"
                            id="submission_comment"
                            name="comment"
                            rows="3"
                            placeholder="Ajoutez un commentaire sur votre travail..."
                            style="border-radius: 12px; border: 2px solid #e2e8f0; padding: 0.75rem 1rem;"
                        ></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border: none; padding: 1.5rem 2rem;">
                    <button type="button" class="btn btn-outline-instagram" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-instagram">
                        <i class="fas fa-paper-plane me-2"></i>Soumettre
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal pour afficher les détails du TP -->
<div class="modal fade" id="tpDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--blue-900), var(--blue-500)); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title" id="modalTitle"><i class="fas fa-file-alt me-2"></i>Détails du TP</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalBody" style="padding: 2rem;">
                <!-- Le contenu sera injecté par JavaScript -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const tpDataRaw = @json($tpAssignments->values());
const tpData = Array.isArray(tpDataRaw) ? tpDataRaw : Object.values(tpDataRaw || {});
const formationPrefix = '{{ $formationPrefix ?? "community-management" }}';

// Gestion de l'upload de fichiers
let selectedFiles = [];

const fileUploadZone = document.getElementById('fileUploadZone');
const fileInput = document.getElementById('submission_files');
const filesList = document.getElementById('filesList');
const filesContainer = document.getElementById('filesContainer');

// Clic sur la zone d'upload
fileUploadZone.addEventListener('click', () => {
    fileInput.click();
});

// Changement de fichiers
fileInput.addEventListener('change', function(e) {
    handleFiles(this.files);
});

// Drag & Drop
fileUploadZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    fileUploadZone.style.borderColor = 'var(--blue-700)';
    fileUploadZone.style.background = 'rgba(42, 82, 152, 0.05)';
});

fileUploadZone.addEventListener('dragleave', (e) => {
    e.preventDefault();
    fileUploadZone.style.borderColor = '#e2e8f0';
    fileUploadZone.style.background = 'transparent';
});

fileUploadZone.addEventListener('drop', (e) => {
    e.preventDefault();
    fileUploadZone.style.borderColor = '#e2e8f0';
    fileUploadZone.style.background = 'transparent';
    handleFiles(e.dataTransfer.files);
});

function handleFiles(files) {
    selectedFiles = Array.from(files);
    displayFiles();
}

function displayFiles() {
    if (selectedFiles.length === 0) {
        filesList.style.display = 'none';
        return;
    }

    filesList.style.display = 'block';
    filesContainer.innerHTML = '';

    selectedFiles.forEach((file, index) => {
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        const fileItem = document.createElement('div');
        fileItem.style.cssText = 'display: flex; align-items: center; justify-content: space-between; background: #f8f9fa; padding: 0.75rem 1rem; border-radius: 8px;';
        fileItem.innerHTML = `
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-file" style="color: var(--blue-700);"></i>
                <div>
                    <div style="font-weight: 600; color: #2c3e50;">${file.name}</div>
                    <small style="color: #999;">${fileSize} Mo</small>
                </div>
            </div>
            <button type="button" onclick="removeFile(${index})" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 1.2rem;">
                <i class="fas fa-times"></i>
            </button>
        `;
        filesContainer.appendChild(fileItem);
    });
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    displayFiles();
}

// Fonction pour ouvrir la modal de soumission
function openSubmitModal(tpId, tpTitle) {
    document.getElementById('submit_tp_id').value = tpId;
    document.getElementById('submitTpTitle').textContent = tpTitle;
    document.getElementById('submission_link').value = '';
    document.getElementById('submission_comment').value = '';

    // Réinitialiser les fichiers
    selectedFiles = [];
    fileInput.value = '';
    displayFiles();

    // Définir l'action du formulaire
    const form = document.getElementById('submitTpForm');
    form.action = `/evc/compte/${formationPrefix}/tp/${tpId}/submit`;

    const modal = new bootstrap.Modal(document.getElementById('submitTpModal'));
    modal.show();
}

// Gérer la soumission du formulaire
document.getElementById('submitTpForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    // Ajouter les fichiers au FormData
    selectedFiles.forEach((file, index) => {
        formData.append('files[]', file);
    });

    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;

    // Désactiver le bouton et afficher un loader
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Envoi en cours...';

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Fermer la modal
            bootstrap.Modal.getInstance(document.getElementById('submitTpModal')).hide();

            // Afficher un message de succès
            alert('✅ Votre TP a été soumis avec succès !');

            // Recharger la page pour voir les changements
            window.location.reload();
        } else {
            alert('❌ ' + (data.message || 'Erreur lors de la soumission'));
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('❌ Erreur lors de la soumission. Veuillez réessayer.');
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
});

function showDetails(tpId) {
    // Trouver le TP dans les données
    const tp = tpData.find(t => String(t.id) === String(tpId));

    if (!tp) {
        alert('❌ TP non trouvé');
        return;
    }

    // Formater les dates
    const assignedDate = new Date(tp.created_at).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });

    function toPlainText(html) {
        const tmp = document.createElement('div');
        tmp.innerHTML = html || '';

        // Convertir les <br> en retours ligne pour éviter que tout soit collé
        tmp.querySelectorAll('br').forEach((br) => br.replaceWith("\n"));

        // Ajouter des retours ligne après certains blocs
        tmp.querySelectorAll('p,div,li,h1,h2,h3,h4').forEach((el) => {
            el.insertAdjacentText('beforeend', "\n");
        });

        const text = (tmp.textContent || tmp.innerText || '')
            .replace(/\u00a0/g, ' ')
            .replace(/[ \t]+/g, ' ')
            .replace(/\n\s*\n\s*\n+/g, "\n\n")
            .trim();

        return text;
    }

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function decodeHtmlEntities(input) {
        const txt = document.createElement('textarea');
        txt.innerHTML = String(input || '');
        return txt.value;
    }

    function formatDescription(raw) {
        const rawHtml = String(raw || '');
        const decodedHtml = decodeHtmlEntities(rawHtml);
        const looksLikeHtml = /<\s*\w+[^>]*>/i.test(decodedHtml);

        function sanitizeRichHtml(html) {
            const tmp = document.createElement('div');
            tmp.innerHTML = html;

            // Remove scripts/iframes/embeds
            tmp.querySelectorAll('script,iframe,object,embed').forEach((el) => el.remove());

            // Strip inline event handlers + javascript: URLs
            tmp.querySelectorAll('*').forEach((el) => {
                Array.from(el.attributes || []).forEach((attr) => {
                    const name = String(attr.name || '').toLowerCase();
                    const value = String(attr.value || '');

                    if (name.startsWith('on')) {
                        el.removeAttribute(attr.name);
                        return;
                    }

                    if ((name === 'href' || name === 'src') && /^\s*javascript:/i.test(value)) {
                        el.removeAttribute(attr.name);
                    }
                });
            });

            // Ensure links are safe
            tmp.querySelectorAll('a').forEach((a) => {
                a.setAttribute('rel', 'noopener noreferrer');
                a.setAttribute('target', '_blank');
            });

            return tmp.innerHTML;
        }

        if (looksLikeHtml) {
            const cleaned = sanitizeRichHtml(decodedHtml).trim();
            return cleaned !== '' ? cleaned : '<span style="color:#64748b;">Aucune description.</span>';
        }

        let text = toPlainText(decodedHtml);
        if (!text) {
            return '<span style="color:#64748b;">Aucune description.</span>';
        }

        text = text.replace(/\r\n/g, '\n');

        // Forcer des retours à la ligne pour les paires clé/valeur courantes
        // Exemple: "Dimension : ...Mode : ..." => chaque clé sur sa ligne
        const configKeys = [
            'Dimension',
            'Mode',
            'Charte Graphique',
            "Nombre d'Affiches",
            'Nombre d’Affiches',
        ];

        configKeys.forEach((key) => {
            const re = new RegExp('\\s*' + key.replace(/[-/\\^$*+?.()|[\\]{}]/g, '\\$&') + '\\s*:', 'gi');
            text = text.replace(re, '\n' + key + ' :');
        });

        const headings = [
            'CONTEXTE',
            'CONSIGNE',
            'CONFIGURATION',
            'OBJECTIF',
            'OBJECTIFS',
            'LIVRABLE',
            'LIVRABLES',
            'CONTRAINTES',
        ];

        const headingsRegex = new RegExp('(' + headings.join('|') + ')', 'gi');

        // Insérer des retours avant les titres même s'ils sont collés au texte
        text = text
            .replace(headingsRegex, '\n\n$1\n')
            .replace(/\n{3,}/g, '\n\n')
            .trim();

        const lines = text.split('\n').map(l => l.trim()).filter(Boolean);

        const htmlLines = lines.map((line) => {
            const upper = line.toUpperCase();
            if (headings.includes(upper)) {
                return `<div class="tp-details-heading">${escapeHtml(upper)}</div>`;
            }
            return `<div class="tp-details-line">${escapeHtml(line)}</div>`;
        });

        return htmlLines.join('');
    }

    const deadlineDate = new Date(tp.deadline).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

    // Badge de statut
    let statusBadge = '';
    let statusColor = '';
    let statusIcon = '';

    switch(tp.status) {
        case 'assigned':
            statusColor = 'linear-gradient(135deg, var(--blue-700), var(--blue-500))';
            statusIcon = 'fa-clock';
            statusBadge = 'En cours';
            break;
        case 'submitted':
            statusColor = 'linear-gradient(135deg, #fbbf24, #f59e0b)';
            statusIcon = 'fa-upload';
            statusBadge = 'Soumis';
            break;
        case 'validated':
            statusColor = 'linear-gradient(135deg, #10b981, #059669)';
            statusIcon = 'fa-check-circle';
            statusBadge = 'Validé';
            break;
        case 'rejected':
            statusColor = 'linear-gradient(135deg, #ef4444, #dc2626)';
            statusIcon = 'fa-times-circle';
            statusBadge = 'Rejeté';
            break;
    }

    // Construire le contenu du modal
    let content = `
        <div style="margin-bottom: 1.5rem;">
            <span style="background: ${statusColor}; color: white; padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; display: inline-block;">
                <i class="fas ${statusIcon} me-2"></i>${statusBadge}
            </span>
        </div>

        <div style="background: linear-gradient(135deg, rgba(30, 60, 114, 0.06), rgba(79, 195, 247, 0.08)); padding: 1.5rem; border-radius: 15px; margin-bottom: 1.5rem;">
            <h4 style="color: var(--blue-900); margin-bottom: 1rem;">
                <i class="fas fa-info-circle me-2"></i>Informations
            </h4>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div>
                    <strong style="color: #666;"><i class="fas fa-calendar me-2"></i>Assigné le :</strong><br>
                    <span style="color: #2c3e50;">${assignedDate}</span>
                </div>
                <div>
                    <strong style="color: #666;"><i class="fas fa-clock me-2"></i>Échéance :</strong><br>
                    <span style="color: var(--blue-700); font-weight: 600;">${deadlineDate}</span>
                </div>
                <div>
                    <strong style="color: #666;"><i class="fas fa-graduation-cap me-2"></i>Formation :</strong><br>
                    <span style="color: #2c3e50;">${tp.formation}</span>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <h4 style="color: var(--blue-900); margin-bottom: 1rem;">
                <i class="fas fa-align-left me-2"></i>Description
            </h4>
            <div class="tp-details-description">
                ${formatDescription(tp.description)}
            </div>
        </div>
    `;

    if (tp.link) {
        const briefHref = tp.link;
        const briefLabel = escapeHtml(tp.link);
        content += `
            <div style="margin-bottom: 1.5rem;">
                <h4 style="color: var(--blue-900); margin-bottom: 1rem;">
                    <i class="fas fa-link me-2"></i>Lien
                </h4>
                <a href="${briefHref}" target="_blank" rel="noopener noreferrer" style="background: #e3f2fd; color: #1976d2; padding: 1rem; border-radius: 12px; text-decoration: none; display: block; transition: all 0.3s;" onmouseover="this.style.background='#bbdefb'" onmouseout="this.style.background='#e3f2fd'">
                    <i class="fas fa-external-link-alt me-2"></i>${briefLabel}
                </a>
            </div>
        `;
    }

    // Ajouter les fichiers joints si présents
    const files = Array.isArray(tp.files) ? tp.files : Object.values(tp.files || {});
    if (files.length > 0) {
        content += `
            <div style="margin-bottom: 1.5rem;">
                <h4 style="color: var(--blue-900); margin-bottom: 1rem;">
                    <i class="fas fa-paperclip me-2"></i>Fichiers joints (${files.length})
                </h4>
                <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
        `;

        files.forEach(file => {
            content += `
                <a href="${file.file_path}" target="_blank" style="background: linear-gradient(135deg, var(--blue-900), var(--blue-500)); color: white; padding: 0.75rem 1.25rem; border-radius: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-download"></i>
                    ${file.file_name}
                </a>
            `;
        });

        content += `
                </div>
            </div>
        `;
    }

    // Ajouter le lien de soumission si présent
    if (tp.submission_link) {
        content += `
            <div style="margin-bottom: 1.5rem;">
                <h4 style="color: var(--blue-900); margin-bottom: 1rem;">
                    <i class="fas fa-link me-2"></i>Votre soumission
                </h4>
                <a href="${tp.submission_link}" target="_blank" style="background: #e3f2fd; color: #1976d2; padding: 1rem; border-radius: 12px; text-decoration: none; display: block; transition: all 0.3s;" onmouseover="this.style.background='#bbdefb'" onmouseout="this.style.background='#e3f2fd'">
                    <i class="fas fa-external-link-alt me-2"></i>${tp.submission_link}
                </a>
            </div>
        `;
    }

    // Ajouter le commentaire admin si présent
    if (tp.admin_comment) {
        const commentStyle = tp.status === 'validated'
            ? 'background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1)); border-left: 4px solid #10b981;'
            : 'background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1)); border-left: 4px solid #ef4444;';

        content += `
            <div style="${commentStyle} padding: 1.5rem; border-radius: 12px;">
                <h4 style="color: var(--blue-900); margin-bottom: 1rem;">
                    <i class="fas fa-comment-alt me-2"></i>Commentaire du formateur
                </h4>
                <p style="margin: 0; color: #555; line-height: 1.6;">${tp.admin_comment}</p>
            </div>
        `;
    }

    // Injecter le contenu dans le modal
    document.getElementById('modalBody').innerHTML = content;
    document.getElementById('modalTitle').innerHTML = `<i class="fas fa-file-alt me-2"></i>${tp.title}`;

    // Afficher le modal
    const modal = new bootstrap.Modal(document.getElementById('tpDetailsModal'));
    modal.show();
}
</script>
@endpush
@endsection
