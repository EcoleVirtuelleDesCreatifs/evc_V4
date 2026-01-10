@extends('layouts.admin')

@section('title', 'Détails du Projet Soumis')

@push('styles')
<style>
    :root {
        --admin-blue-dark: #1e3c72;
        --admin-blue-light: #4fc3f7;
        --admin-blue-mid: #2a5298;
    }

    .page-header {
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-mid), var(--admin-blue-light));
        padding: 2.5rem 2rem;
        border-radius: 20px;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(30, 60, 114, 0.3);
        animation: fadeInDown 0.6s ease;
    }

    .page-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .back-button {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .back-button:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateX(-5px);
        color: white;
    }

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

    .info-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
        animation: fadeInUp 0.5s ease;
        animation-fill-mode: both;
    }

    .info-card:nth-child(1) { animation-delay: 0.1s; }
    .info-card:nth-child(2) { animation-delay: 0.2s; }
    .info-card:nth-child(3) { animation-delay: 0.3s; }
    .info-card:nth-child(4) { animation-delay: 0.4s; }

    .card-header-custom {
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-mid));
        color: white;
        padding: 1.5rem;
        border-radius: 16px 16px 0 0;
        margin: -2rem -2rem 2rem -2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .card-header-custom h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .card-header-custom .icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .status-badge {
        padding: 0.5rem 1.5rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-submitted {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: white;
    }

    .status-validated {
        background: linear-gradient(135deg, #1cc88a, #13855c);
        color: white;
    }

    .status-rejected {
        background: linear-gradient(135deg, #e74a3b, #be2617);
        color: white;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-item {
        background: linear-gradient(135deg, rgba(30, 60, 114, 0.05), rgba(79, 195, 247, 0.05));
        padding: 1.25rem;
        border-radius: 12px;
        border-left: 4px solid var(--admin-blue-mid);
    }

    .info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #718096;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1a202c;
    }

    .description-box {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid #e9ecef;
        line-height: 1.8;
        color: #4a5568;
    }

    .file-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
    }

    .file-item {
        background: linear-gradient(135deg, rgba(30, 60, 114, 0.05), rgba(79, 195, 247, 0.05));
        padding: 1.25rem;
        border-radius: 12px;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .file-item:hover {
        border-color: var(--admin-blue-light);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(30, 60, 114, 0.1);
    }

    .file-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-mid));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        flex-shrink: 0;
    }

    .file-info {
        flex: 1;
        min-width: 0;
    }

    .file-name {
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .file-size {
        font-size: 0.875rem;
        color: #718096;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 1rem 2rem;
        border-radius: 30px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1rem;
        text-decoration: none;
    }

    .btn-validate {
        background: linear-gradient(135deg, #1cc88a, #13855c);
        color: white;
    }

    .btn-validate:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(28, 200, 138, 0.3);
        color: white;
    }

    .btn-reject {
        background: linear-gradient(135deg, #e74a3b, #be2617);
        color: white;
    }

    .btn-reject:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(231, 74, 59, 0.3);
        color: white;
    }

    .btn-download {
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-mid));
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 60, 114, 0.3);
        color: white;
    }

    .student-avatar {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-light));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 700;
        color: white;
        box-shadow: 0 8px 20px rgba(30, 60, 114, 0.3);
    }

    .timeline-item {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 19px;
        top: 40px;
        bottom: -20px;
        width: 2px;
        background: linear-gradient(180deg, var(--admin-blue-mid), transparent);
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-mid));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
        z-index: 1;
    }

    .timeline-content {
        flex: 1;
        background: #f8f9fa;
        padding: 1rem 1.5rem;
        border-radius: 12px;
    }

    .timeline-date {
        font-size: 0.875rem;
        color: #718096;
        margin-bottom: 0.25rem;
    }

    .timeline-text {
        font-weight: 600;
        color: #1a202c;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('admin.projets.pending') }}" class="back-button mb-3">
                    <i class="fas fa-arrow-left"></i>
                    Retour à la liste
                </a>
                <h1 class="mb-2">{{ $tp->title }}</h1>
                <p class="mb-0" style="opacity: 0.95;">
                    Soumis par {{ $tp->first_name }} {{ $tp->last_name }} • {{ $tp->formation }}
                </p>
            </div>
            <div>
                @if($tp->status === 'termine')
                    <span class="status-badge status-submitted">
                        <i class="fas fa-hourglass-half"></i>
                        En attente
                    </span>
                @elseif($tp->status === 'valide')
                    <span class="status-badge status-validated">
                        <i class="fas fa-check-circle"></i>
                        Validé
                    </span>
                @elseif($tp->status === 'rejete')
                    <span class="status-badge status-rejected">
                        <i class="fas fa-times-circle"></i>
                        Rejeté
                    </span>
                @elseif($tp->status === 'en_cours')
                    <span class="status-badge" style="background: linear-gradient(135deg, #36b9cc, #258391); color: white;">
                        <i class="fas fa-spinner"></i>
                        En cours
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Colonne principale -->
        <div class="col-lg-8">
            <!-- Informations du Projet -->
            <div class="info-card">
                <div class="card-header-custom">
                    <div class="icon">📋</div>
                    <h3>Détails du Projet</h3>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-calendar-alt"></i>
                            Date de création
                        </div>
                        <div class="info-value">
                            {{ \Carbon\Carbon::parse($tp->created_at)->format('d/m/Y à H:i') }}
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-clock"></i>
                            Dernière modification
                        </div>
                        <div class="info-value">
                            {{ \Carbon\Carbon::parse($tp->updated_at)->format('d/m/Y à H:i') }}
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-tag"></i>
                            Catégorie
                        </div>
                        <div class="info-value">
                            {{ $tp->category ?? 'Non spécifiée' }}
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-graduation-cap"></i>
                            Formation
                        </div>
                        <div class="info-value">
                            {{ $tp->formation }}
                        </div>
                    </div>
                </div>

                <h4 class="mb-3" style="color: var(--admin-blue-dark);">
                    <i class="fas fa-align-left me-2"></i>Description du Projet
                </h4>
                <div class="description-box">
                    {!! $tp->description !!}
                </div>

                @if($assignmentFiles && $assignmentFiles->count() > 0)
                <h4 class="mt-4 mb-3" style="color: var(--admin-blue-dark);">
                    <i class="fas fa-paperclip me-2"></i>Fichiers joints par l'admin ({{ $assignmentFiles->count() }})
                </h4>
                <div class="file-list">
                    @foreach($assignmentFiles as $file)
                        @php
                            $fileUrl = \App\Models\MediaUrl::fromPath($file->file_path ?? null);
                        @endphp
                        <div class="file-item">
                            <div class="file-icon">
                                <i class="fas fa-file"></i>
                            </div>
                            <div class="file-info">
                                <div class="file-name" title="{{ $file->file_name }}">{{ $file->file_name }}</div>
                                <div class="file-size">{{ $file->file_size ? number_format($file->file_size / 1024, 2) . ' KB' : 'N/A' }}</div>
                            </div>
                            <a href="{{ $fileUrl }}" target="_blank" class="btn-download">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Fichiers soumis par l'étudiant -->
            <div class="info-card">
                <div class="card-header-custom">
                    <div class="icon">📁</div>
                    <h3>Fichiers Soumis par l'Étudiant</h3>
                </div>

                <div class="info-item mb-3">
                    <div class="info-label">
                        <i class="fas fa-file-alt"></i>
                        Projet soumis
                    </div>
                    <div class="info-value">{{ $tp->title ?? 'N/A' }}</div>
                    @if(!empty($tp->category))
                        <div class="mt-2">
                            <span class="badge bg-info">{{ $tp->category }}</span>
                        </div>
                    @endif
                </div>

                @if(!empty($tp->description))
                    <div class="description-box mb-3">
                        {!! $tp->description !!}
                    </div>
                @endif

                @if($submittedFiles && $submittedFiles->count() > 0)
                    <div class="file-list">
                        @foreach($submittedFiles as $file)
                            @php
                                $fileUrl = \App\Models\MediaUrl::fromPath($file->file_path ?? null);
                                $mime = strtolower((string) ($file->mime_type ?? ''));
                                $isImage = str_starts_with($mime, 'image/');
                                $isPdf = $mime === 'application/pdf';
                            @endphp
                            <div class="file-item">
                                <div class="file-icon">
                                    <i class="fas fa-file-upload"></i>
                                </div>
                                <div class="file-info">
                                    <div class="file-name" title="{{ $file->file_name }}">{{ $file->file_name }}</div>
                                    <div class="file-size">{{ number_format($file->file_size / 1024, 2) }} KB</div>
                                </div>
                                <div class="d-flex gap-2 align-items-center">
                                    @if($isImage)
                                        <a href="{{ $fileUrl }}" target="_blank" class="btn-download">Voir</a>
                                    @elseif($isPdf)
                                        <a href="{{ $fileUrl }}" target="_blank" class="btn-download">Ouvrir</a>
                                    @endif
                                    <a href="{{ $fileUrl }}" target="_blank" class="btn-download">
                                        <i class="fas fa-download"></i>
                                        Télécharger
                                    </a>
                                </div>
                            </div>

                            @if($isImage)
                                <div class="mt-3" style="border-radius: 12px; overflow: hidden; border: 1px solid #e9ecef;">
                                    <a href="{{ $fileUrl }}" target="_blank" style="display: block;">
                                        <img src="{{ $fileUrl }}" alt="{{ $file->file_name }}" style="width: 100%; max-height: 320px; object-fit: cover; display: block;" />
                                    </a>
                                </div>
                            @elseif($isPdf)
                                <div class="mt-3" style="border-radius: 12px; overflow: hidden; border: 1px solid #e9ecef;">
                                    <iframe src="{{ $fileUrl }}" style="width: 100%; height: 420px; border: 0;"></iframe>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucun fichier soumis</p>
                    </div>
                @endif

                @if(isset($tp->link) && $tp->link)
                    <h4 class="mt-4 mb-3" style="color: var(--admin-blue-dark);">
                        <i class="fas fa-link me-2"></i>Lien du projet
                    </h4>
                    <div class="description-box">
                        <a href="{{ $tp->link }}" target="_blank" style="color: var(--admin-blue-mid); font-weight: 600;">
                            <i class="fas fa-external-link-alt me-2"></i>{{ $tp->link }}
                        </a>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            @if($tp->status === 'termine')
            <div class="info-card">
                <h4 class="mb-3" style="color: var(--admin-blue-dark);">
                    <i class="fas fa-cog me-2"></i>Actions
                </h4>
                <div class="action-buttons">
                    <form action="{{ route('admin.projets.pending.validate', $tp->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-action btn-validate" onclick="return confirm('Êtes-vous sûr de vouloir valider ce projet ?')">
                            <i class="fas fa-check-circle"></i>
                            Valider ce Projet
                        </button>
                    </form>
                    <button class="btn-action btn-reject" onclick="openRejectModal()">
                        <i class="fas fa-times-circle"></i>
                        Rejeter ce Projet
                    </button>
                </div>
            </div>
            @endif
        </div>

        <!-- Colonne latérale -->
        <div class="col-lg-4">
            <!-- Informations étudiant -->
            <div class="info-card">
                <div class="card-header-custom">
                    <div class="icon">👤</div>
                    <h3>Étudiant</h3>
                </div>

                <div class="text-center mb-4">
                    @php
                        $profilePhotoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($tp->profile_photo ?? null);
                        $avatarUrl = null;

                        if (!empty($tp->profile_photo) && !empty($profilePhotoUrl)) {
                            $avatarUrl = $profilePhotoUrl;
                        } elseif (!empty($submittedFiles) && $submittedFiles->count() > 0) {
                            $firstFile = $submittedFiles->first();
                            $mime = strtolower((string) ($firstFile->mime_type ?? ''));
                            if (str_starts_with($mime, 'image/')) {
                                $avatarUrl = \App\Models\MediaUrl::fromPath($firstFile->file_path ?? null);
                            }
                        }

                        $initials = substr($tp->first_name ?? 'U', 0, 1) . substr($tp->last_name ?? 'U', 0, 1);
                    @endphp

                    @if(!empty($avatarUrl))
                        <img src="{{ $avatarUrl }}" alt="{{ $tp->first_name ?? 'Étudiant' }}" class="rounded-circle mx-auto mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                        <div class="student-avatar mx-auto mb-3">
                            {{ $initials }}
                        </div>
                    @endif
                    <h4 style="color: var(--admin-blue-dark);">{{ $tp->first_name }} {{ $tp->last_name }}</h4>
                    <p class="text-muted mb-0">{{ $tp->formation }}</p>
                </div>

                <div class="info-item mb-3">
                    <div class="info-label">
                        <i class="fas fa-envelope"></i>
                        Email
                    </div>
                    <div class="info-value" style="font-size: 0.95rem;">
                        {{ $tp->student_email ?? 'Non renseigné' }}
                    </div>
                </div>

                @if($tp->student_phone)
                <div class="info-item mb-3">
                    <div class="info-label">
                        <i class="fas fa-phone"></i>
                        Téléphone
                    </div>
                    <div class="info-value" style="font-size: 0.95rem;">
                        {{ $tp->student_phone }}
                    </div>
                </div>
                @endif

                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-id-card"></i>
                        Numéro étudiant
                    </div>
                    <div class="info-value" style="font-size: 0.95rem;">
                        {{ $tp->student_number ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="info-card">
                <div class="card-header-custom">
                    <div class="icon">⏱️</div>
                    <h3>Historique</h3>
                </div>

                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">{{ \Carbon\Carbon::parse($tp->created_at)->format('d/m/Y à H:i') }}</div>
                        <div class="timeline-text">Projet créé par l'étudiant</div>
                    </div>
                </div>

                @if($tp->updated_at != $tp->created_at)
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">{{ \Carbon\Carbon::parse($tp->updated_at)->format('d/m/Y à H:i') }}</div>
                        <div class="timeline-text">Dernière modification</div>
                    </div>
                </div>
                @endif

                @if($tp->status === 'valide' || $tp->status === 'rejete')
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-{{ $tp->status === 'valide' ? 'check' : 'times' }}"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">{{ \Carbon\Carbon::parse($tp->updated_at)->format('d/m/Y à H:i') }}</div>
                        <div class="timeline-text">Projet {{ $tp->status === 'valide' ? 'validé' : 'rejeté' }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de rejet -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg, #e74a3b, #be2617); color: white; border: none;">
                <h5 class="modal-title" id="rejectModalLabel">
                    <i class="fas fa-times-circle me-2"></i>Rejeter le Projet
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.projets.pending.reject', $tp->id) }}" method="POST">
                @csrf
                <div class="modal-body" style="padding: 2rem;">
                    <div class="alert alert-warning" style="border-left: 4px solid #ffc107;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Attention :</strong> L'étudiant recevra un email avec votre commentaire.
                    </div>

                    <div class="mb-3">
                        <label for="rejectionReason" class="form-label" style="font-weight: 600; color: #1a202c;">
                            <i class="fas fa-comment-alt me-2"></i>Raison du rejet <span class="text-danger">*</span>
                        </label>
                        <textarea
                            class="form-control"
                            id="rejectionReason"
                            name="reason"
                            rows="5"
                            required
                            minlength="10"
                            placeholder="Expliquez clairement les points à améliorer pour aider l'étudiant..."
                            style="border-radius: 12px; border: 2px solid #e9ecef; padding: 1rem;"
                        ></textarea>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>Minimum 10 caractères
                        </small>
                    </div>

                    <div style="background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 10px; padding: 1rem; margin-top: 1rem;">
                        <p style="margin: 0; color: #856404; font-size: 0.9rem;">
                            <strong>💡 Conseil :</strong> Soyez constructif dans vos commentaires pour aider l'étudiant à progresser.
                        </p>
                    </div>
                </div>
                <div class="modal-footer" style="border: none; padding: 1.5rem 2rem;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 20px; padding: 0.75rem 1.5rem;">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn" style="background: linear-gradient(135deg, #e74a3b, #be2617); color: white; border: none; border-radius: 20px; padding: 0.75rem 1.5rem; font-weight: 600;">
                        <i class="fas fa-paper-plane me-2"></i>Rejeter et Notifier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Modal styling - Simple and effective */
    .modal {
        z-index: 1055 !important;
    }

    .modal-backdrop {
        z-index: 1050 !important;
    }

    .modal-dialog-centered {
        display: flex;
        align-items: center;
        min-height: calc(100% - 1rem);
    }

    .modal-content {
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        border: none;
    }

    .modal-header {
        padding: 1.5rem 2rem;
    }

    .modal-title {
        font-weight: 700;
        font-size: 1.25rem;
    }

    #rejectionReason:focus {
        border-color: #e74a3b;
        box-shadow: 0 0 0 0.2rem rgba(231, 74, 59, 0.25);
    }

    .modal-body {
        max-height: calc(100vh - 300px);
        overflow-y: auto;
    }

    /* Ensure button is clickable */
    .btn-action {
        cursor: pointer;
        z-index: 1;
    }
</style>

<script>
    // Ensure Bootstrap modal works correctly
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize modal if Bootstrap is loaded
        if (typeof bootstrap !== 'undefined') {
            const rejectModal = document.getElementById('rejectModal');
            if (rejectModal) {
                // Clear textarea when modal is closed
                rejectModal.addEventListener('hidden.bs.modal', function () {
                    document.getElementById('rejectionReason').value = '';
                });
            }
        }
    });
</script>
@endsection
