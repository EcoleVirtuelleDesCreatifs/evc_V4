@extends('layouts.admin')

@section('content')
<style>
    .modern-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 20px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(30, 60, 114, 0.3);
    }

    .modern-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .candidate-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 5px solid white;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        object-fit: cover;
    }

    .status-timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 2rem;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.5rem;
        top: 0.5rem;
        width: 2px;
        height: calc(100% - 0.5rem);
        background: #e2e8f0;
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-dot {
        position: absolute;
        left: -1.75rem;
        top: 0;
        width: 0.75rem;
        height: 0.75rem;
        border-radius: 50%;
        background: #cbd5e0;
    }

    .timeline-dot.active {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        box-shadow: 0 0 0 4px rgba(30, 60, 114, 0.2);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { box-shadow: 0 0 0 4px rgba(30, 60, 114, 0.2); }
        50% { box-shadow: 0 0 0 8px rgba(30, 60, 114, 0.1); }
    }

    .info-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .info-card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f7fafc;
    }

    .info-card-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
    }

    .icon-blue {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
    }

    .icon-cyan {
        background: linear-gradient(135deg, #4fc3f7, #29b6f6);
    }

    .icon-orange {
        background: linear-gradient(135deg, #ff9800, #fb8c00);
    }

    .icon-purple {
        background: linear-gradient(135deg, #9c27b0, #7b1fa2);
    }

    .info-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .info-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 1rem;
        color: #1e293b;
        font-weight: 500;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .status-pending {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: white;
    }

    .status-accepted {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .status-active {
        background: linear-gradient(135deg, #4fc3f7, #29b6f6);
        color: white;
    }

    .status-rejected {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .action-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-gradient-blue {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color: white;
    }

    .btn-gradient-blue:hover {
        background: linear-gradient(135deg, #2a5298, #1e3c72);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(30, 60, 114, 0.4);
        color: white;
    }

    .btn-gradient-cyan {
        background: linear-gradient(135deg, #4fc3f7, #29b6f6);
        color: white;
    }

    .btn-gradient-cyan:hover {
        background: linear-gradient(135deg, #29b6f6, #4fc3f7);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(79, 195, 247, 0.4);
        color: white;
    }

    .btn-gradient-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .btn-gradient-success:hover {
        background: linear-gradient(135deg, #059669, #10b981);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .btn-gradient-warning {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: white;
    }

    .btn-gradient-warning:hover {
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(251, 191, 36, 0.4);
        color: white;
    }

    .btn-outline-modern {
        background: white;
        border: 2px solid #e2e8f0;
        color: #64748b;
    }

    .btn-outline-modern:hover {
        border-color: #1e3c72;
        color: #1e3c72;
        background: #f8fafc;
        transform: translateY(-2px);
    }

    .photo-container {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .photo-container img {
        width: 100%;
        height: auto;
        display: block;
    }

    .quick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .skill-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 500;
        color: #475569;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .equipment-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .equipment-yes {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
    }

    .equipment-no {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
    }

    @media (max-width: 768px) {
        .modern-header {
            padding: 1.5rem;
        }
        .candidate-avatar {
            width: 80px;
            height: 80px;
        }
        .info-row {
            grid-template-columns: 1fr;
        }
        .quick-actions {
            flex-direction: column;
        }
        .action-btn {
            width: 100%;
            justify-content: center;
        }
    }

    .fadeIn {
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="container-fluid py-4">
    <!-- Modern Header -->
    <div class="modern-header fadeIn">
        <div class="row align-items-center position-relative" style="z-index: 1;">
            <div class="col-auto">
                @if($pre->photo)
                    <img src="{{ asset('storage/'.$pre->photo) }}" alt="Photo candidat" class="candidate-avatar">
                @else
                    <div class="candidate-avatar d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.2);">
                        <i class="fas fa-user fa-2x"></i>
                    </div>
                @endif
            </div>
            <div class="col">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <h1 class="h3 mb-0">{{ $pre->prenom }} {{ $pre->nom }}</h1>
                    @php
                        $statusMap = [
                            'en cours' => ['class' => 'status-pending', 'icon' => 'fa-clock', 'text' => 'En cours'],
                            'pending' => ['class' => 'status-pending', 'icon' => 'fa-clock', 'text' => 'En cours'],
                            'accepted' => ['class' => 'status-accepted', 'icon' => 'fa-check-circle', 'text' => 'Validé'],
                            'Validé' => ['class' => 'status-accepted', 'icon' => 'fa-check-circle', 'text' => 'Validé'],
                            'Actif' => ['class' => 'status-active', 'icon' => 'fa-user-check', 'text' => 'Actif'],
                            'rejected' => ['class' => 'status-rejected', 'icon' => 'fa-times-circle', 'text' => 'Rejeté'],
                        ];
                        $currentStatus = $statusMap[$pre->status] ?? ['class' => 'status-pending', 'icon' => 'fa-info-circle', 'text' => ucfirst($pre->status)];
                    @endphp
                    <span class="status-badge {{ $currentStatus['class'] }}">
                        <i class="fas {{ $currentStatus['icon'] }}"></i>
                        {{ $currentStatus['text'] }}
                    </span>
                </div>
                <div class="d-flex align-items-center gap-3 text-white-50">
                    <span><i class="fas fa-hashtag me-1"></i>{{ $pre->id }}</span>
                    <span><i class="fas fa-envelope me-1"></i>{{ $pre->email }}</span>
                    <span><i class="fas fa-graduation-cap me-1"></i>{{ $pre->choix_formation }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="{{ route('admin.preinscriptions.index') }}" class="action-btn btn-outline-modern">
                <i class="fas fa-arrow-left"></i>
                Retour à la liste
            </a>
            @if($pre->photo)
                <a href="{{ route('admin.preinscriptions.download-photo', $pre->id) }}" class="action-btn btn-gradient-cyan">
                    <i class="fas fa-download"></i>
                    Télécharger photo
                </a>
            @endif
            @if(!in_array($pre->status, ['accepted','Validé','Actif']))
                <form action="{{ route('admin.preinscriptions.validate', $pre->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="action-btn btn-gradient-success">
                        <i class="fas fa-check-circle"></i>
                        Valider la candidature
                    </button>
                </form>
            @endif
            @if($pre->status !== 'Actif')
                <form action="{{ route('admin.preinscriptions.resend-link', $pre->id) }}" method="POST" onsubmit="return confirm('Renvoyer le lien d\'inscription au candidat ?');" class="d-inline">
                    @csrf
                    <button type="submit" class="action-btn btn-gradient-warning">
                        <i class="fas fa-paper-plane"></i>
                        Renvoyer le lien
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-12 col-lg-8">
            <!-- Informations Personnelles -->
            <div class="info-card fadeIn" style="animation-delay: 0.1s;">
                <div class="info-card-header">
                    <div class="info-card-icon icon-blue">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Informations Personnelles</h5>
                        <small class="text-muted">Données personnelles du candidat</small>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-item">
                        <span class="info-label">Nom complet</span>
                        <span class="info-value">{{ $pre->prenom }} {{ $pre->nom }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Âge</span>
                        <span class="info-value">{{ $pre->age }} ans</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date de naissance</span>
                        <span class="info-value">{{ optional($pre->date_naissance)->format('d/m/Y') ?? $pre->date_naissance }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Sexe</span>
                        <span class="info-value">{{ $pre->sexe }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Nationalité</span>
                        <span class="info-value">{{ $pre->nationalite }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value">
                            <a href="mailto:{{ $pre->email }}" style="color: #1e3c72; text-decoration: none;">
                                <i class="fas fa-envelope me-1"></i>{{ $pre->email }}
                            </a>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">WhatsApp</span>
                        <span class="info-value">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pre->whatsapp) }}" target="_blank" style="color: #25D366; text-decoration: none;">
                                <i class="fab fa-whatsapp me-1"></i>{{ $pre->whatsapp }}
                            </a>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Ville</span>
                        <span class="info-value">{{ $pre->ville }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Pays</span>
                        <span class="info-value">{{ $pre->pays }}</span>
                    </div>
                </div>
            </div>

            <!-- Parcours Académique -->
            <div class="info-card fadeIn" style="animation-delay: 0.2s;">
                <div class="info-card-header">
                    <div class="info-card-icon icon-cyan">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Parcours Académique</h5>
                        <small class="text-muted">Formation et niveau d'études</small>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-item">
                        <span class="info-label">Niveau d'étude</span>
                        <span class="info-value">{{ $pre->niveau_etude }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Domaine d'étude</span>
                        <span class="info-value">{{ $pre->domaine_etude ?? 'Non renseigné' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Formation choisie</span>
                        <span class="info-value">{{ $pre->choix_formation }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Niveau dans la formation</span>
                        <span class="info-value">{{ $pre->niveau_dans_formation }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Disponibilités</span>
                        <span class="info-value">{{ $pre->disponibilite }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Comment nous a connu</span>
                        <span class="info-value">{{ $pre->how_known ?? $pre->origine ?? 'Non renseigné' }}</span>
                    </div>
                </div>
            </div>

            <!-- Équipement -->
            <div class="info-card fadeIn" style="animation-delay: 0.3s;">
                <div class="info-card-header">
                    <div class="info-card-icon icon-orange">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Équipement Technique</h5>
                        <small class="text-muted">Matériel disponible pour la formation</small>
                    </div>
                </div>
                <div class="d-flex gap-3 flex-wrap">
                    <div class="equipment-indicator {{ $pre->has_computer ? 'equipment-yes' : 'equipment-no' }}">
                        <i class="fas fa-{{ $pre->has_computer ? 'check-circle' : 'times-circle' }}"></i>
                        <span>Ordinateur: {{ $pre->has_computer ? 'Oui' : 'Non' }}</span>
                    </div>
                    <div class="equipment-indicator {{ $pre->has_smartphone ? 'equipment-yes' : 'equipment-no' }}">
                        <i class="fas fa-{{ $pre->has_smartphone ? 'check-circle' : 'times-circle' }}"></i>
                        <span>Smartphone: {{ $pre->has_smartphone ? 'Oui' : 'Non' }}</span>
                    </div>
                </div>
            </div>

            <!-- Compétences -->
            @if($pre->competences)
            <div class="info-card fadeIn" style="animation-delay: 0.4s;">
                <div class="info-card-header">
                    <div class="info-card-icon icon-purple">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Compétences</h5>
                        <small class="text-muted">Compétences et expériences</small>
                    </div>
                </div>
                <div style="white-space: pre-line; line-height: 1.8; color: #475569;">{{ $pre->competences }}</div>
            </div>
            @endif

            <!-- Motivation -->
            @if($pre->motivation)
            <div class="info-card fadeIn" style="animation-delay: 0.5s;">
                <div class="info-card-header">
                    <div class="info-card-icon icon-blue">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Motivation</h5>
                        <small class="text-muted">Pourquoi rejoindre l'EVC</small>
                    </div>
                </div>
                <div style="white-space: pre-line; line-height: 1.8; color: #475569;">{{ $pre->motivation }}</div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-12 col-lg-4">
            <!-- Photo -->
            @if($pre->photo)
            <div class="info-card fadeIn" style="animation-delay: 0.1s;">
                <div class="info-card-header">
                    <div class="info-card-icon icon-cyan">
                        <i class="fas fa-camera"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Photo du candidat</h5>
                        <small class="text-muted">Photo d'identité</small>
                    </div>
                </div>
                <div class="photo-container">
                    <img src="{{ asset('storage/'.$pre->photo) }}" alt="Photo du candidat">
                </div>
            </div>
            @endif

            <!-- Timeline de statut -->
            <div class="info-card fadeIn" style="animation-delay: 0.2s;">
                <div class="info-card-header">
                    <div class="info-card-icon icon-blue">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Progression</h5>
                        <small class="text-muted">Timeline du candidat</small>
                    </div>
                </div>
                <div class="status-timeline">
                    <div class="timeline-item">
                        <div class="timeline-dot {{ in_array($pre->status, ['en cours', 'pending', 'accepted', 'Validé', 'Actif', 'rejected']) ? 'active' : '' }}"></div>
                        <strong style="color: #1e293b;">Candidature soumise</strong>
                        <div class="text-muted small">{{ $pre->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-dot {{ in_array($pre->status, ['accepted', 'Validé', 'Actif']) ? 'active' : '' }}"></div>
                        <strong style="color: #1e293b;">Candidature validée</strong>
                        <div class="text-muted small">
                            @if(in_array($pre->status, ['accepted', 'Validé', 'Actif']))
                                {{ $pre->updated_at->format('d/m/Y H:i') }}
                            @else
                                En attente
                            @endif
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-dot {{ $pre->status === 'Actif' ? 'active' : '' }}"></div>
                        <strong style="color: #1e293b;">Compte activé</strong>
                        <div class="text-muted small">
                            @if($pre->status === 'Actif')
                                Actif
                            @else
                                En attente d'activation
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestion du statut -->
            <div class="info-card fadeIn" style="animation-delay: 0.3s;">
                <div class="info-card-header">
                    <div class="info-card-icon icon-orange">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Modifier le statut</h5>
                        <small class="text-muted">Gestion administrative</small>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.preinscriptions.bulk-status') }}">
                    @csrf
                    <input type="hidden" name="ids[]" value="{{ $pre->id }}">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nouveau statut</label>
                        <select name="action" class="form-select" style="border-radius: 12px; border: 2px solid #e2e8f0;">
                            <option value="accepted" @selected($pre->status==='accepted')>Accepté</option>
                            <option value="rejected" @selected($pre->status==='rejected')>Rejeté</option>
                            <option value="pending" @selected($pre->status==='pending' || $pre->status==='en cours')>En cours</option>
                        </select>
                    </div>
                    <button type="submit" class="action-btn btn-gradient-blue w-100">
                        <i class="fas fa-save"></i>
                        Mettre à jour le statut
                    </button>
                </form>
            </div>

            <!-- Métadonnées -->
            <div class="info-card fadeIn" style="animation-delay: 0.4s;">
                <div class="info-card-header">
                    <div class="info-card-icon icon-purple">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Informations système</h5>
                        <small class="text-muted">Métadonnées</small>
                    </div>
                </div>
                <div class="info-item mb-3">
                    <span class="info-label">Créée le</span>
                    <span class="info-value">{{ $pre->created_at->format('d/m/Y à H:i') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Dernière modification</span>
                    <span class="info-value">{{ $pre->updated_at->format('d/m/Y à H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
