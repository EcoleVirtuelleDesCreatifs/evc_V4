@extends('layouts.admin')

@section('title', 'Profil CV - ' . $profile->first_name . ' ' . $profile->last_name)

@push('styles')
 <link href="{{ asset('css/admin/formation-create.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid px-4 py-4 cvt-show">
    @php
        $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrl($profile->profile_photo);

        $badgeColor = match($profile->formation) {
            'Design Graphique' => 'primary',
            'Community Management' => 'info',
            'Gestion Informatique' => 'warning',
            'Intelligence Artificielle' => 'success',
            default => 'secondary'
        };

        $score = (int)($profile->profile_completion_score ?? 0);
        $progressColor = $score >= 80 ? 'success' : ($score >= 50 ? 'warning' : 'danger');

        $skills = [];
        if (!empty($profile->skills)) {
            if (is_array($profile->skills)) {
                $skills = $profile->skills;
            } elseif (is_string($profile->skills)) {
                $decoded = json_decode($profile->skills, true);
                $skills = is_array($decoded) ? $decoded : [];
            }
        }

        $hasPortfolioFiles = !empty($portfolioFiles) && is_array($portfolioFiles) && count($portfolioFiles) > 0;
        $hasAnyDocument = (bool)($profile->cv_file_path || $profile->motivation_letter_path || $hasPortfolioFiles || $profile->pressbook_file_path || $profile->report_file_path);
    @endphp

    <div class="cvt-hero mb-4">
        <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.cvtheque.profiles') }}" class="btn btn-light btn-sm cvt-hero-back">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>

                <div class="cvt-hero-title">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <h1 class="h4 mb-0 text-white fw-bold">
                            {{ $profile->first_name }} {{ $profile->last_name }}
                        </h1>
                        <span class="badge bg-white text-dark">Profil CV</span>
                        <span class="badge bg-{{ $badgeColor }}">{{ $profile->formation }}</span>
                    </div>
                    <div class="mt-1 text-white-50">
                        @if($profile->professional_title)
                            <span class="me-2">{{ $profile->professional_title }}</span>
                        @endif
                        @if($profile->specialization)
                            <span class="cvt-dot">•</span>
                            <span class="ms-2">{{ $profile->specialization }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                @if($profile->user_email)
                    <a class="btn btn-outline-light btn-sm" href="mailto:{{ $profile->user_email }}">
                        <i class="fas fa-envelope me-2"></i>Contacter
                    </a>
                @endif
                @if($profile->phone)
                    <a class="btn btn-outline-light btn-sm" href="tel:{{ $profile->phone }}">
                        <i class="fas fa-phone me-2"></i>Appeler
                    </a>
                @endif
                @if($profile->whatsapp)
                    <a class="btn btn-success btn-sm" href="https://wa.me/{{ $profile->whatsapp }}" target="_blank" rel="noopener">
                        <i class="fab fa-whatsapp me-2"></i>WhatsApp
                    </a>
                @endif
                <button onclick="window.print()" class="btn btn-light btn-sm">
                    <i class="fas fa-print me-2"></i>Imprimer
                </button>
            </div>
        </div>

        <div class="mt-3">
            <div class="cvt-progress">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="text-white-50 small">Complétion du profil</div>
                    <div class="text-white small fw-semibold">{{ $score }}%</div>
                </div>
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar bg-{{ $progressColor }}" role="progressbar" style="width: {{ $score }}%" aria-valuenow="{{ $score }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="cvt-sticky">
                <div class="card cvt-card shadow-sm mb-4">
                    <div class="card-body text-center">
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}" alt="{{ $profile->first_name }}" class="rounded-circle mb-3 cvt-avatar">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3 cvt-avatar-placeholder">
                                {{ strtoupper(substr($profile->first_name ?? 'U', 0, 1)) }}
                            </div>
                        @endif

                        <div class="fw-bold">{{ $profile->first_name }} {{ $profile->last_name }}</div>
                        @if($profile->professional_title)
                            <div class="text-muted small mt-1">{{ $profile->professional_title }}</div>
                        @endif

                        <div class="d-flex justify-content-center gap-2 mt-3 flex-wrap">
                            <span class="badge bg-{{ $badgeColor }}">{{ $profile->formation }}</span>
                            <span class="badge bg-light text-dark">Inscrit le {{ \Carbon\Carbon::parse($profile->created_at)->format('d/m/Y') }}</span>
                        </div>

                        <div class="mt-3">
                            <div class="cvt-mini-kpis">
                                <div class="cvt-mini-kpi">
                                    <div class="cvt-mini-kpi-label">Expérience</div>
                                    <div class="cvt-mini-kpi-value">{{ $profile->experience_years ?? 0 }} an(s)</div>
                                </div>
                                <div class="cvt-mini-kpi">
                                    <div class="cvt-mini-kpi-label">Disponibilité</div>
                                    <div class="cvt-mini-kpi-value">{{ $profile->availability ?? 'N/A' }}</div>
                                </div>
                                <div class="cvt-mini-kpi">
                                    <div class="cvt-mini-kpi-label">Documents</div>
                                    <div class="cvt-mini-kpi-value">{{ $hasAnyDocument ? 'Oui' : 'Non' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card cvt-card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="fw-bold">
                                <i class="fas fa-address-card me-2 text-primary"></i>Coordonnées
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="cvt-field">
                            <div class="cvt-field-label">Email</div>
                            <div class="cvt-field-value">
                                <a href="mailto:{{ $profile->user_email }}">{{ $profile->user_email }}</a>
                            </div>
                        </div>

                        @if($profile->phone)
                            <div class="cvt-field">
                                <div class="cvt-field-label">Téléphone</div>
                                <div class="cvt-field-value">
                                    <a href="tel:{{ $profile->phone }}">{{ $profile->phone }}</a>
                                </div>
                            </div>
                        @endif

                        @if($profile->whatsapp)
                            <div class="cvt-field">
                                <div class="cvt-field-label">WhatsApp</div>
                                <div class="cvt-field-value">
                                    <a href="https://wa.me/{{ $profile->whatsapp }}" target="_blank" rel="noopener">{{ $profile->whatsapp }}</a>
                                </div>
                            </div>
                        @endif

                        @if($profile->country || $profile->city)
                            <div class="cvt-field">
                                <div class="cvt-field-label">Localisation</div>
                                <div class="cvt-field-value">
                                    {{ $profile->city }}@if($profile->country), {{ $profile->country }}@endif
                                </div>
                            </div>
                        @endif

                        @if($profile->portfolio_url)
                            <div class="cvt-field">
                                <div class="cvt-field-label">Portfolio URL</div>
                                <div class="cvt-field-value">
                                    <a href="{{ $profile->portfolio_url }}" target="_blank" rel="noopener">{{ $profile->portfolio_url }}</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card cvt-kpi-card shadow-sm">
                        <div class="card-body">
                            <div class="cvt-kpi-label">Score de profil</div>
                            <div class="cvt-kpi-value text-{{ $progressColor }}">{{ $score }}%</div>
                            <div class="cvt-kpi-subtext">Qualité du dossier</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card cvt-kpi-card shadow-sm">
                        <div class="card-body">
                            <div class="cvt-kpi-label">Expérience</div>
                            <div class="cvt-kpi-value">{{ $profile->experience_years ?? 0 }} ans</div>
                            <div class="cvt-kpi-subtext">Années déclarées</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card cvt-kpi-card shadow-sm">
                        <div class="card-body">
                            <div class="cvt-kpi-label">Disponibilité</div>
                            <div class="cvt-kpi-value">{{ $profile->availability ?? 'N/A' }}</div>
                            <div class="cvt-kpi-subtext">Donnée par l'étudiant</div>
                        </div>
                    </div>
                </div>
            </div>

            @if($profile->summary)
                <div class="card cvt-card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <div class="fw-bold"><i class="fas fa-quote-left me-2 text-primary"></i>Résumé professionnel</div>
                    </div>
                    <div class="card-body">
                        <div class="cvt-summary">{{ $profile->summary }}</div>
                    </div>
                </div>
            @endif

            @if(!empty($skills))
                <div class="card cvt-card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <div class="fw-bold"><i class="fas fa-tools me-2 text-primary"></i>Compétences</div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($skills as $skill)
                                <span class="badge bg-light text-dark border px-3 py-2 cvt-skill">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <div class="card cvt-card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="fw-bold"><i class="fas fa-folder-open me-2 text-primary"></i>Documents & fichiers</div>
                        <div class="text-muted small">
                            {{ $hasAnyDocument ? 'Dossier documenté' : 'Aucun document disponible' }}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="cvt-doc">
                                <div class="cvt-doc-icon text-danger"><i class="fas fa-file-pdf"></i></div>
                                <div class="cvt-doc-body">
                                    <div class="cvt-doc-title">Curriculum Vitae</div>
                                    <div class="cvt-doc-meta">
                                        @if($profile->cv_file_path)
                                            <span class="text-success"><i class="fas fa-check-circle me-1"></i>Disponible</span>
                                        @else
                                            <span class="text-muted">Non téléchargé</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="cvt-doc-actions">
                                    @if($profile->cv_file_path)
                                        <a href="{{ route('admin.cvtheque.download', ['id' => $profile->id, 'type' => 'cv']) }}" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-download me-2"></i>Télécharger
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary" disabled>Indisponible</button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="cvt-doc">
                                <div class="cvt-doc-icon text-info"><i class="fas fa-envelope"></i></div>
                                <div class="cvt-doc-body">
                                    <div class="cvt-doc-title">Lettre de motivation</div>
                                    <div class="cvt-doc-meta">
                                        @if($profile->motivation_letter_path)
                                            <span class="text-success"><i class="fas fa-check-circle me-1"></i>Disponible</span>
                                        @else
                                            <span class="text-muted">Non téléchargé</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="cvt-doc-actions">
                                    @if($profile->motivation_letter_path)
                                        <a href="{{ route('admin.cvtheque.download', ['id' => $profile->id, 'type' => 'motivation']) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-download me-2"></i>Télécharger
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary" disabled>Indisponible</button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="cvt-doc">
                                <div class="cvt-doc-icon text-warning"><i class="fas fa-images"></i></div>
                                <div class="cvt-doc-body">
                                    <div class="cvt-doc-title">Portfolio / réalisations</div>
                                    <div class="cvt-doc-meta">
                                        @if($hasPortfolioFiles)
                                            <span class="text-success"><i class="fas fa-check-circle me-1"></i>{{ count($portfolioFiles) }} fichier(s)</span>
                                        @else
                                            <span class="text-muted">Non téléchargé</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="cvt-doc-actions">
                                    @if($hasPortfolioFiles)
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-warning dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-eye me-2"></i>Voir
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                @foreach($portfolioFiles as $file)
                                                    @php
                                                        $filePath = null;
                                                        if (is_string($file)) {
                                                            $filePath = $file;
                                                        } elseif (is_array($file)) {
                                                            $filePath = $file['path'] ?? $file['file_path'] ?? null;
                                                        } elseif (is_object($file)) {
                                                            $filePath = $file->path ?? $file->file_path ?? null;
                                                        }
                                                    @endphp
                                                    @if(!empty($filePath))
                                                        <li>
                                                            <a class="dropdown-item" href="{{ \App\Models\MediaUrl::fromPath($filePath) }}" target="_blank" rel="noopener">
                                                                <i class="fas fa-external-link-alt me-2"></i>Fichier {{ $loop->iteration }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary" disabled>Indisponible</button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="cvt-doc">
                                <div class="cvt-doc-icon text-success"><i class="fas fa-book"></i></div>
                                <div class="cvt-doc-body">
                                    <div class="cvt-doc-title">Pressbook</div>
                                    <div class="cvt-doc-meta">
                                        @if($profile->pressbook_file_path)
                                            <span class="text-success"><i class="fas fa-check-circle me-1"></i>Disponible</span>
                                        @else
                                            <span class="text-muted">Non téléchargé</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="cvt-doc-actions">
                                    @if($profile->pressbook_file_path)
                                        <a href="{{ route('admin.cvtheque.download', ['id' => $profile->id, 'type' => 'pressbook']) }}" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-download me-2"></i>Télécharger
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary" disabled>Indisponible</button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="cvt-doc">
                                <div class="cvt-doc-icon text-primary"><i class="fas fa-file-alt"></i></div>
                                <div class="cvt-doc-body">
                                    <div class="cvt-doc-title">Rapport de fin de formation</div>
                                    <div class="cvt-doc-meta">
                                        @if($profile->report_file_path)
                                            <span class="text-success"><i class="fas fa-check-circle me-1"></i>Disponible</span>
                                        @else
                                            <span class="text-muted">Non téléchargé</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="cvt-doc-actions">
                                    @if($profile->report_file_path)
                                        <a href="{{ route('admin.cvtheque.download', ['id' => $profile->id, 'type' => 'report']) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download me-2"></i>Télécharger
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary" disabled>Indisponible</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card cvt-card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <div class="fw-bold"><i class="fas fa-graduation-cap me-2 text-primary"></i>Informations académiques</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @if($profile->education_level)
                            <div class="col-md-6">
                                <div class="cvt-field">
                                    <div class="cvt-field-label">Niveau d'études</div>
                                    <div class="cvt-field-value">{{ $profile->education_level }}</div>
                                </div>
                            </div>
                        @endif

                        @if($profile->last_diploma)
                            <div class="col-md-6">
                                <div class="cvt-field">
                                    <div class="cvt-field-label">Dernier diplôme</div>
                                    <div class="cvt-field-value">{{ $profile->last_diploma }}</div>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-6">
                            @php
                                $statusBadge = match($profile->student_status) {
                                    'active' => 'success',
                                    'inactive' => 'secondary',
                                    'suspended' => 'warning',
                                    default => 'secondary'
                                };
                            @endphp
                            <div class="cvt-field">
                                <div class="cvt-field-label">Statut étudiant</div>
                                <div class="cvt-field-value">
                                    <span class="badge bg-{{ $statusBadge }}">{{ ucfirst($profile->student_status ?? 'Inconnu') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="cvt-field">
                                <div class="cvt-field-label">Date d'inscription</div>
                                <div class="cvt-field-value">{{ \Carbon\Carbon::parse($profile->created_at)->format('d/m/Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
 .cvt-show {
     color: var(--form-text);
 }
 .cvt-show a { color: var(--form-primary); }
 .cvt-show a:hover { color: #7dd3fc; }

 .cvt-show .cvt-hero {
     background: linear-gradient(135deg, rgba(56, 189, 248, 0.24) 0%, rgba(30, 41, 59, 0.75) 55%, rgba(15, 23, 42, 0.85) 100%);
     border-radius: 18px;
     padding: 18px 18px;
     border: 1px solid var(--form-border);
     backdrop-filter: blur(10px);
     box-shadow: 0 18px 45px rgba(0, 0, 0, 0.35);
 }
 .cvt-show .cvt-hero .badge.bg-white { background: rgba(226, 232, 240, 0.95) !important; }
 .cvt-show .cvt-hero-back { border: 1px solid rgba(226,232,240,0.25); background: rgba(226,232,240,0.08); color: #e2e8f0; }
 .cvt-show .cvt-hero-back:hover { border-color: rgba(56, 189, 248, 0.45); background: rgba(56, 189, 248, 0.12); color: #e2e8f0; }
 .cvt-show .cvt-dot { opacity: 0.8; }
 .cvt-show .cvt-progress { max-width: 520px; }

 .cvt-show .cvt-avatar {
     width: 140px;
     height: 140px;
     object-fit: cover;
     border: 4px solid rgba(56, 189, 248, 0.35);
     box-shadow: 0 12px 25px rgba(0,0,0,0.35);
 }
 .cvt-show .cvt-avatar-placeholder {
     width: 140px;
     height: 140px;
     font-size: 3rem;
 }

 .cvt-show .cvt-card,
 .cvt-show .cvt-kpi-card {
     background: var(--form-surface);
     border: 1px solid var(--form-border);
     border-radius: 16px;
     backdrop-filter: blur(10px);
 }
 .cvt-show .card-header.bg-white {
     background: transparent !important;
     border-bottom: 1px solid var(--form-border) !important;
     color: var(--form-text);
 }

 .cvt-show .cvt-sticky { position: sticky; top: 16px; }
 .cvt-show .cvt-field { padding: 10px 0; border-bottom: 1px solid var(--form-border); }
 .cvt-show .cvt-field:last-child { border-bottom: 0; padding-bottom: 0; }
 .cvt-show .cvt-field-label { font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--form-text-muted); }
 .cvt-show .cvt-field-value { font-weight: 600; color: var(--form-text); }
 .cvt-show .cvt-summary { color: var(--form-text); line-height: 1.65; }

 .cvt-show .cvt-mini-kpis {
     display: grid;
     grid-template-columns: repeat(3, minmax(0, 1fr));
     gap: 10px;
 }
 .cvt-show .cvt-mini-kpi {
     background: rgba(15, 23, 42, 0.55);
     border: 1px solid var(--form-border);
     border-radius: 12px;
     padding: 10px;
 }
 .cvt-show .cvt-mini-kpi-label { font-size: 0.75rem; color: var(--form-text-muted); }
 .cvt-show .cvt-mini-kpi-value { font-weight: 700; color: var(--form-text); font-size: 0.95rem; }

 .cvt-show .cvt-kpi-label { color: var(--form-text-muted); font-size: 0.85rem; }
 .cvt-show .cvt-kpi-value { font-size: 1.65rem; font-weight: 800; color: var(--form-text); line-height: 1.1; margin-top: 4px; }
 .cvt-show .cvt-kpi-subtext { color: var(--form-text-muted); font-size: 0.8rem; margin-top: 4px; }

 .cvt-show .badge.bg-light,
 .cvt-show .badge.bg-white {
     background: rgba(226, 232, 240, 0.12) !important;
     color: var(--form-text) !important;
     border: 1px solid var(--form-border) !important;
 }
 .cvt-show .cvt-skill { border-radius: 999px; }

 .cvt-show .cvt-doc {
     display: flex;
     align-items: center;
     gap: 12px;
     padding: 12px;
     border-radius: 14px;
     border: 1px solid var(--form-border);
     background: rgba(15, 23, 42, 0.55);
 }
 .cvt-show .cvt-doc-icon {
     width: 42px;
     height: 42px;
     border-radius: 12px;
     display: flex;
     align-items: center;
     justify-content: center;
     background: rgba(56, 189, 248, 0.08);
     border: 1px solid rgba(56, 189, 248, 0.18);
     font-size: 1.2rem;
 }
 .cvt-show .cvt-doc-body { flex: 1; min-width: 0; }
 .cvt-show .cvt-doc-title { font-weight: 800; color: var(--form-text); }
 .cvt-show .cvt-doc-meta { font-size: 0.85rem; margin-top: 2px; color: var(--form-text-muted); }
 .cvt-show .cvt-doc-actions { flex-shrink: 0; }

 .cvt-show .btn-outline-light { border-color: rgba(226,232,240,0.28); color: #e2e8f0; }
 .cvt-show .btn-outline-light:hover { border-color: rgba(56,189,248,0.55); background: rgba(56,189,248,0.12); color: #e2e8f0; }
 .cvt-show .btn-light { background: rgba(226,232,240,0.10); border-color: rgba(226,232,240,0.18); color: #e2e8f0; }
 .cvt-show .btn-light:hover { background: rgba(226,232,240,0.14); border-color: rgba(56,189,248,0.55); color: #e2e8f0; }

 @media (max-width: 991.98px) {
     .cvt-show .cvt-sticky { position: static; }
 }

 @media print {
     .btn, nav, .sidebar, .cvt-hero-back {
         display: none !important;
     }
     .cvt-show .cvt-hero {
         box-shadow: none !important;
     }
 }
</style>
@endsection
