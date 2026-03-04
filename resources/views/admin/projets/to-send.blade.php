@extends('layouts.admin')

@section('title', 'Envoyer un Projet')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
<style>
    /* Statistiques en haut */
    .stats-row {
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--form-surface);
        border: 1px solid var(--form-border);
        border-radius: 16px;
        padding: 1.5rem;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        height: 100%;
    }

    .stat-card:hover {
        border-color: var(--form-primary);
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(56, 189, 248, 0.2);
    }

    .stat-card .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
    }

    .stat-card .stat-title {
        font-size: 0.875rem;
        color: var(--form-text-muted);
        margin-bottom: 0.5rem;
    }

    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--form-text);
        margin-bottom: 0;
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
    }

    .stat-card .stat-footer {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: var(--form-text-muted);
        margin-top: 0.75rem;
    }

    /* Zero Projects Panel */
    .zero-tp-toggle {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        user-select: none;
        padding: 0.85rem 1.25rem;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.12), rgba(239, 68, 68, 0.04));
        border: 1px solid rgba(239, 68, 68, 0.3);
        transition: all 0.3s ease;
        margin-bottom: 0;
    }
    .zero-tp-toggle:hover {
        border-color: rgba(239, 68, 68, 0.6);
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.18), rgba(239, 68, 68, 0.08));
    }
    .zero-tp-toggle .form-check-input {
        width: 1.15em; height: 1.15em;
        cursor: pointer;
    }
    .zero-tp-toggle .form-check-input:checked {
        background-color: #ef4444;
        border-color: #ef4444;
    }
    .zero-tp-toggle-label {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--form-text, #e2e8f0);
    }
    .zero-tp-toggle-count {
        margin-left: auto;
        background: #ef4444;
        color: #fff;
        font-weight: 800;
        font-size: 0.8rem;
        padding: 0.2rem 0.65rem;
        border-radius: 999px;
    }
    .zero-tp-panel {
        background: var(--form-surface, #1a1f2e);
        border: 1px solid var(--form-border, #2d3548);
        border-radius: 14px;
        max-height: 420px;
        overflow-y: auto;
        margin-top: 0.75rem;
    }
    .zero-tp-panel .zero-tp-item {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.7rem 1rem;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        transition: background 0.15s;
    }
    .zero-tp-panel .zero-tp-item:last-child { border-bottom: none; }
    .zero-tp-panel .zero-tp-item:hover { background: rgba(255,255,255,0.03); }
    .zero-tp-photo {
        width: 40px; height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255,255,255,0.1);
        flex-shrink: 0;
    }
    .zero-tp-photo-placeholder {
        width: 40px; height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #334155, #475569);
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 0.85rem; color: #94a3b8;
        flex-shrink: 0;
    }
    .zero-tp-info { flex: 1; min-width: 0; }
    .zero-tp-name {
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--form-text, #e2e8f0);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .zero-tp-email {
        font-size: 0.78rem;
        color: var(--form-text-muted, #94a3b8);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .zero-tp-badge {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.2rem 0.55rem;
        border-radius: 6px;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .zero-tp-search {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        color: var(--form-text, #e2e8f0);
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
        width: 100%;
    }
    .zero-tp-search::placeholder { color: #64748b; }
    .zero-tp-search:focus {
        outline: none;
        border-color: rgba(239, 68, 68, 0.5);
        background: rgba(255,255,255,0.08);
    }

    /* Recipients Info */
    .recipients-info {
        background: rgba(56, 189, 248, 0.1);
        border: 1px solid rgba(56, 189, 248, 0.3);
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .recipients-info i {
        color: var(--form-primary);
    }

    .recipients-info strong {
        color: var(--form-text);
    }

    #quill-editor {
        height: 300px;
    }
</style>
@endpush

@section('content')

@if(session('error'))
    <div class="alert alert-danger mx-4 mt-4 mb-0">
        {{ session('error') }}
    </div>
@endif

@if(session('errors_list') && is_array(session('errors_list')) && count(session('errors_list')))
    <div class="alert alert-warning mx-4 mt-3 mb-0">
        <strong>Détails des erreurs :</strong>
        <ul class="mb-0">
            @foreach(session('errors_list') as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('emails_failures') && is_array(session('emails_failures')) && count(session('emails_failures')))
    <div class="alert alert-warning mx-4 mt-3 mb-0">
        <strong>Emails non envoyés :</strong>
        <ul class="mb-0">
            @foreach(session('emails_failures') as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form id="sendProjectForm" action="{{ route('admin.projets.send') }}" method="POST" class="interactive-dashboard-form" enctype="multipart/form-data">
    @csrf

    <!-- Statistiques par formation -->
    <div class="row g-4 stats-row">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Design Graphique</div>
                        <h2 class="stat-value" style="color: #1e3c72;">{{ $stats['design_graphique'] }}</h2>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #1e3c72, #2a5298);">
                        <i class="fas fa-paint-brush"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="fas fa-user-check"></i>
                    <span>Étudiants actifs</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Design Graphique &amp; Community Management</div>
                        <h2 class="stat-value" style="color: #833AB4;">{{ $stats['design_graphique_cm'] ?? 0 }}</h2>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #833AB4, #C13584);">
                        <i class="fas fa-layer-group"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="fas fa-user-check"></i>
                    <span>Étudiants actifs</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Community Management</div>
                        <h2 class="stat-value" style="color: #4fc3f7;">{{ $stats['community_management'] }}</h2>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #4fc3f7, #29b6f6);">
                        <i class="fas fa-share-alt"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="fas fa-user-check"></i>
                    <span>Étudiants actifs</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Gestion Informatique</div>
                        <h2 class="stat-value" style="color: #ff9800;">{{ $stats['gestion_informatique'] }}</h2>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #ff9800, #fb8c00);">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="fas fa-user-check"></i>
                    <span>Étudiants actifs</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Intelligence Artificielle</div>
                        <h2 class="stat-value" style="color: #26c6da;">{{ $stats['intelligence_artificielle'] ?? 0 }}</h2>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #26c6da, #00acc1);">
                        <i class="fas fa-robot"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="fas fa-user-check"></i>
                    <span>Étudiants actifs</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel : Étudiants avec 0 TP/Projet -->
    <div class="mb-4">
        <label class="zero-tp-toggle" for="zeroTpCheckbox">
            <input class="form-check-input" type="checkbox" id="zeroTpCheckbox">
            <i class="fas fa-exclamation-triangle" style="color: #ef4444;"></i>
            <span class="zero-tp-toggle-label">Étudiants avec 0 TP / Projet</span>
            <span class="zero-tp-toggle-count">{{ $stats['zero_projects'] ?? 0 }}</span>
        </label>

        <div id="zeroTpPanel" style="display: none;">
            <div class="zero-tp-panel">
                <div style="padding: 0.65rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.08);">
                    <input type="text" class="zero-tp-search" id="zeroTpSearch" placeholder="Rechercher un étudiant...">
                </div>

                @if(isset($studentsWithoutProjects) && $studentsWithoutProjects->count() > 0)
                    @foreach($studentsWithoutProjects as $stu)
                        @php
                            $initials = strtoupper(mb_substr($stu->first_name ?? '', 0, 1) . mb_substr($stu->last_name ?? '', 0, 1));
                            $photoUrl = null;
                            if (!empty($stu->profile_photo)) {
                                $photoUrl = asset('storage/' . $stu->profile_photo);
                            }
                            $badgeColor = match ($stu->program_normalized ?? '') {
                                'Design Graphique' => 'background:#1e3c72;color:#fff;',
                                'Community Management' => 'background:#0891b2;color:#fff;',
                                'Design Graphique & Community Management' => 'background:#833AB4;color:#fff;',
                                'Gestion Informatique' => 'background:#d97706;color:#fff;',
                                'Intelligence Artificielle' => 'background:#0d9488;color:#fff;',
                                default => 'background:#475569;color:#fff;',
                            };
                        @endphp
                        <div class="zero-tp-item" data-name="{{ strtolower(($stu->first_name ?? '') . ' ' . ($stu->last_name ?? '')) }}" data-formation="{{ strtolower($stu->program_normalized ?? '') }}">
                            @if($photoUrl)
                                <img src="{{ $photoUrl }}" alt="" class="zero-tp-photo">
                            @else
                                <div class="zero-tp-photo-placeholder">{{ $initials ?: '?' }}</div>
                            @endif
                            <div class="zero-tp-info">
                                <div class="zero-tp-name">{{ $stu->first_name }} {{ $stu->last_name }}</div>
                                <div class="zero-tp-email">{{ $stu->email ?? '—' }}</div>
                            </div>
                            <span class="zero-tp-badge" style="{{ $badgeColor }}">{{ $stu->program_normalized ?? 'N/A' }}</span>
                        </div>
                    @endforeach
                @else
                    <div style="padding: 2rem; text-align: center; color: #64748b;">
                        <i class="fas fa-check-circle" style="font-size: 1.5rem; color: #22c55e;"></i>
                        <div style="margin-top: 0.5rem; font-weight: 600;">Tous les étudiants ont au moins un projet</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Informations principales -->
        <div class="col-12">
            <div class="row g-4">
                <!-- Titre et Catégorie -->
                <div class="col-lg-8">
                    <div class="form-card h-100">
                        <div class="form-card-header">
                            <i class="fas fa-file-alt"></i>
                            <h3>Informations du Projet</h3>
                        </div>
                        <div class="form-card-body">
                            <!-- Titre -->
                            <div class="form-group">
                                <label for="title">
                                    Titre du Projet <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control @error('title') is-invalid @enderror"
                                       id="title"
                                       name="title"
                                       value="{{ old('title') }}"
                                       required
                                       placeholder="Ex: Création d'une identité visuelle complète">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Catégorie -->
                            <div class="form-group">
                                <label for="category">
                                    Catégorie <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('category') is-invalid @enderror"
                                        id="category"
                                        name="category"
                                        required>
                                    <option value="">-- Sélectionner --</option>
                                    <option value="Design Graphique">🎨 Design Graphique</option>
                                    <option value="Branding">🏷️ Branding</option>
                                    <option value="UI/UX Design">📱 UI/UX Design</option>
                                    <option value="Social Media">📱 Social Media</option>
                                    <option value="Content Creation">✍️ Content Creation</option>
                                    <option value="Digital Marketing">📊 Digital Marketing</option>
                                    <option value="Web Design">🌐 Web Design</option>
                                    <option value="Motion Design">🎬 Motion Design</option>
                                    <option value="Autre">📂 Autre</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tags -->
                            <div class="form-group mb-0">
                                <label for="tags">
                                    Tags (optionnel)
                                </label>
                                <input type="text"
                                       class="form-control"
                                       id="tags"
                                       name="tags"
                                       value="{{ old('tags') }}"
                                       placeholder="Ex: logo, branding, print (séparés par des virgules)">
                                <small class="text-muted d-block mt-2" style="color: #94a3b8 !important;">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Séparez les tags par des virgules
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ciblage -->
                <div class="col-lg-4">
                    <div class="form-card h-100">
                        <div class="form-card-header">
                            <i class="fas fa-bullseye"></i>
                            <h3>Ciblage</h3>
                        </div>
                        <div class="form-card-body">
                            <!-- Sélection formation -->
                            <div class="form-group">
                                <label for="formation">
                                    Formation cible <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('formation') is-invalid @enderror"
                                        id="formation"
                                        name="formation[]"
                                        multiple
                                        size="6"
                                        required>
                                    <option value="all">📚 Toutes les formations</option>
                                    @php
                                        $oldFormations = old('formation');
                                        if (!is_array($oldFormations)) {
                                            $oldFormations = $oldFormations ? [$oldFormations] : [];
                                        }
                                        if (empty($oldFormations) && !empty($defaultFormation)) {
                                            $oldFormations = [$defaultFormation];
                                        }
                                    @endphp
                                    <option value="Design Graphique" {{ in_array('Design Graphique', $oldFormations, true) ? 'selected' : '' }}>🎨 Design Graphique ({{ $stats['design_graphique'] }})</option>
                                    <option value="Design Graphique & Community Management" {{ in_array('Design Graphique & Community Management', $oldFormations, true) ? 'selected' : '' }}>🎨📱 Design Graphique & Community Management ({{ $stats['design_graphique_cm'] ?? 0 }})</option>
                                    <option value="Community Management" {{ in_array('Community Management', $oldFormations, true) ? 'selected' : '' }}>📱 Community Management ({{ $stats['community_management'] }})</option>
                                    <option value="Gestion Informatique" {{ in_array('Gestion Informatique', $oldFormations, true) ? 'selected' : '' }}>💻 Gestion Informatique ({{ $stats['gestion_informatique'] }})</option>
                                    <option value="Intelligence Artificielle" {{ in_array('Intelligence Artificielle', $oldFormations, true) ? 'selected' : '' }}>🤖 Intelligence Artificielle ({{ $stats['intelligence_artificielle'] ?? 0 }})</option>
                                    @if($stats['sans_formation'] > 0)
                                        <option value="Sans formation" {{ in_array('Sans formation', $oldFormations, true) ? 'selected' : '' }}>❓ Sans formation ({{ $stats['sans_formation'] }})</option>
                                    @endif
                                </select>
                                @error('formation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2" style="color: #94a3b8 !important;">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Maintenez Ctrl/Cmd pour sélectionner plusieurs formations
                                </small>
                            </div>

                            <!-- Sélection étudiants spécifiques -->
                            <div class="form-group" id="studentsSelectContainer" style="display: none;">
                                <label for="students">
                                    Sélectionner les étudiants spécifiques (optionnel)
                                </label>
                                <select class="form-select"
                                        id="students"
                                        name="students[]"
                                        multiple
                                        size="10">
                                    @php
                                        $oldStudents = old('students');
                                        if (!is_array($oldStudents)) {
                                            $oldStudents = $oldStudents ? [$oldStudents] : [];
                                        }
                                        if (empty($oldStudents) && !empty($defaultStudentIds) && is_array($defaultStudentIds)) {
                                            $oldStudents = $defaultStudentIds;
                                        }
                                    @endphp
                                    @php
                                        $groupWithout = isset($studentsWithoutProjects) ? $studentsWithoutProjects : collect();
                                        $groupWith = isset($studentsWithProjects) ? $studentsWithProjects : collect();

                                        if ($groupWithout->count() === 0 && $groupWith->count() === 0) {
                                            $groupWithout = $students;
                                            $groupWith = collect();
                                        }
                                    @endphp

                                    @if($groupWithout->count() > 0)
                                        <optgroup label="Nouveaux inscrits (0 projet)">
                                            @foreach($groupWithout as $student)
                                                <option value="{{ $student->id }}" data-formation="{{ $student->program_normalized }}" {{ in_array((int) $student->id, array_map('intval', $oldStudents), true) ? 'selected' : '' }}>
                                                    {{ $student->first_name }} {{ $student->last_name }}
                                                    @if($student->email)
                                                        ({{ $student->email }})
                                                    @endif
                                                    - {{ $student->program_normalized }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif

                                    @if($groupWith->count() > 0)
                                        <optgroup label="Déjà avec projets">
                                            @foreach($groupWith as $student)
                                                <option value="{{ $student->id }}" data-formation="{{ $student->program_normalized }}" {{ in_array((int) $student->id, array_map('intval', $oldStudents), true) ? 'selected' : '' }}>
                                                    {{ $student->first_name }} {{ $student->last_name }}
                                                    @if($student->email)
                                                        ({{ $student->email }})
                                                    @endif
                                                    - {{ $student->program_normalized }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                </select>
                                <small class="text-muted d-block mt-2" style="color: #94a3b8 !important;">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Maintenez Ctrl/Cmd pour sélectionner plusieurs étudiants
                                </small>
                            </div>

                            <!-- Info destinataires -->
                            <div class="recipients-info" id="recipientsInfo">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong id="recipientsCount">Sélectionnez une formation</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description détaillée -->
        <div class="col-12">
            <div class="form-card">
                <div class="form-card-header">
                    <i class="fas fa-align-left"></i>
                    <h3>Description du Projet</h3>
                </div>
                <div class="form-card-body">
                    <div class="form-group mb-0">
                        <label for="description">
                            Consignes et instructions <span class="text-danger">*</span>
                        </label>
                        <div id="quill-editor">{{ old('description') }}</div>
                        <input type="hidden" id="description" name="description" required>
                        @error('description')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-2" style="color: #94a3b8 !important;">
                            <i class="fas fa-info-circle me-1"></i>
                            Utilisez l'éditeur pour formater votre texte (gras, italique, listes, liens, etc.)
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations complémentaires -->
        <div class="col-12">
            <div class="form-card">
                <div class="form-card-header">
                    <i class="fas fa-info-circle"></i>
                    <h3>Informations Complémentaires</h3>
                </div>
                <div class="form-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="deadline">
                                    Délai (deadline) <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                       class="form-control @error('deadline') is-invalid @enderror"
                                       id="deadline"
                                       name="deadline"
                                       value="{{ old('deadline') }}"
                                       required>
                                @error('deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2" style="color: #94a3b8 !important;">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Date limite de rendu du projet
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="software_used">
                                    Logiciels à Utiliser
                                </label>
                                <input type="text"
                                       class="form-control"
                                       id="software_used"
                                       name="software_used"
                                       value="{{ old('software_used') }}"
                                       placeholder="Ex: Photoshop, Illustrator, Figma">
                                <small class="text-muted d-block mt-2" style="color: #94a3b8 !important;">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Séparez par des virgules
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="reference_link">
                                    Lien de Référence
                                </label>
                                <input type="url"
                                       class="form-control"
                                       id="reference_link"
                                       name="reference_link"
                                       value="{{ old('reference_link') }}"
                                       placeholder="https://example.com/references">
                                <small class="text-muted d-block mt-2" style="color: #94a3b8 !important;">
                                    <i class="fas fa-link me-1"></i>
                                    Lien vers ressources, exemples ou brief
                                </small>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-0">
                                <label for="attachments">Fichiers (images ou PDF)</label>
                                <input type="file"
                                       class="form-control @error('attachments') is-invalid @enderror @error('attachments.*') is-invalid @enderror"
                                       id="attachments"
                                       name="attachments[]"
                                       multiple
                                       accept="image/*,application/pdf">
                                @error('attachments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('attachments.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2" style="color: #94a3b8 !important;">
                                    <i class="fas fa-paperclip me-1"></i>
                                    Vous pouvez joindre plusieurs fichiers (JPG/PNG/WebP/PDF)
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer avec boutons -->
        <div class="col-12">
            <div class="form-footer d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Annuler
                </a>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-paper-plane me-2"></i>
                    Envoyer le Projet
                </button>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
// Initialiser Quill
const quill = new Quill('#quill-editor', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'color': [] }, { 'background': [] }],
            ['link', 'image'],
            ['clean']
        ]
    },
    placeholder: 'Décrivez les consignes du projet en détail...'
});

// Synchroniser avec le champ hidden
quill.on('text-change', function() {
    document.getElementById('description').value = quill.root.innerHTML;
});

document.getElementById('description').value = quill.root.innerHTML;

// Gestion de la sélection de formation
const formationSelect = document.getElementById('formation');
const studentsSelectContainer = document.getElementById('studentsSelectContainer');
const studentsSelect = document.getElementById('students');
const recipientsCount = document.getElementById('recipientsCount');

const stats = {
    'all': {{ $stats['total_students'] }},
    'Design Graphique': {{ $stats['design_graphique'] }},
    'Design Graphique & Community Management': {{ $stats['design_graphique_cm'] ?? 0 }},
    'Community Management': {{ $stats['community_management'] }},
    'Gestion Informatique': {{ $stats['gestion_informatique'] }},
    'Intelligence Artificielle': {{ $stats['intelligence_artificielle'] ?? 0 }},
    'Sans formation': {{ $stats['sans_formation'] ?? 0 }}
};

formationSelect.addEventListener('change', function() {
    const selectedFormations = Array.from(this.selectedOptions).map(o => o.value).filter(Boolean);
    const hasAll = selectedFormations.includes('all');
    const selectedSpecificFormations = selectedFormations.filter(v => v !== 'all');

    if (hasAll) {
        studentsSelectContainer.style.display = 'none';
        // Laisser les étudiants sélectionnés intacts uniquement si on repasse sur 'all'
        recipientsCount.textContent = `Tous les étudiants (${stats['all']} étudiants)`;
        return;
    }

    if (selectedSpecificFormations.length > 0) {
        // Afficher le sélecteur d'étudiants
        studentsSelectContainer.style.display = 'block';

        // Filtrer les options
        const options = studentsSelect.querySelectorAll('option');
        options.forEach(option => {
            const optionFormation = option.getAttribute('data-formation');
            if (!optionFormation || selectedSpecificFormations.includes(optionFormation)) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
                option.selected = false;
            }
        });

        updateRecipientsCount();
    } else {
        studentsSelectContainer.style.display = 'none';
        recipientsCount.textContent = 'Sélectionnez une formation';
    }
});

studentsSelect.addEventListener('change', function() {
    updateRecipientsCount();
});

if (formationSelect.value) {
    formationSelect.dispatchEvent(new Event('change'));
}

function updateRecipientsCount() {
    const selectedFormations = Array.from(formationSelect.selectedOptions).map(o => o.value).filter(Boolean);
    const hasAll = selectedFormations.includes('all');
    const selectedSpecificFormations = selectedFormations.filter(v => v !== 'all');
    const selectedStudents = Array.from(studentsSelect.selectedOptions);

    if (selectedStudents.length > 0) {
        recipientsCount.textContent = `${selectedStudents.length} étudiant(s) sélectionné(s)`;
    } else if (hasAll) {
        recipientsCount.textContent = `Tous les étudiants (${stats['all']} étudiants)`;
    } else if (selectedSpecificFormations.length > 0) {
        const total = selectedSpecificFormations.reduce((sum, f) => sum + (stats[f] || 0), 0);
        if (selectedSpecificFormations.length === 1) {
            const f = selectedSpecificFormations[0];
            recipientsCount.textContent = `Tous les étudiants de ${f} (${stats[f] || 0} étudiants)`;
        } else {
            recipientsCount.textContent = `Étudiants des formations sélectionnées (${total} étudiants)`;
        }
    }
}

// Toggle panel "0 TP/Projet"
const zeroTpCheckbox = document.getElementById('zeroTpCheckbox');
const zeroTpPanel = document.getElementById('zeroTpPanel');
const zeroTpSearch = document.getElementById('zeroTpSearch');

if (zeroTpCheckbox && zeroTpPanel) {
    zeroTpCheckbox.addEventListener('change', function() {
        zeroTpPanel.style.display = this.checked ? 'block' : 'none';
        if (this.checked && zeroTpSearch) {
            setTimeout(() => zeroTpSearch.focus(), 100);
        }
    });
}

if (zeroTpSearch) {
    zeroTpSearch.addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();
        document.querySelectorAll('#zeroTpPanel .zero-tp-item').forEach(item => {
            const name = item.getAttribute('data-name') || '';
            const formation = item.getAttribute('data-formation') || '';
            item.style.display = (q === '' || name.includes(q) || formation.includes(q)) ? 'flex' : 'none';
        });
    });
}

// Validation du formulaire
document.getElementById('sendProjectForm').addEventListener('submit', function(e) {
    // IMPORTANT: Synchroniser le contenu de Quill avec le champ hidden
    const quillContent = quill.root.innerHTML;
    document.getElementById('description').value = quillContent;

    // Vérifier que la description n'est pas vide
    const textContent = quill.getText().trim();
    if (!textContent || textContent.length === 0) {
        e.preventDefault();
        alert('⚠️ Veuillez remplir la description du projet');
        quill.focus();
        return false;
    }

    const formations = Array.from(formationSelect.selectedOptions).map(o => o.value).filter(Boolean);
    if (!formations.length) {
        e.preventDefault();
        alert('⚠️ Veuillez sélectionner une formation');
        formationSelect.focus();
        return false;
    }

    // Confirmation
    const selectedStudents = Array.from(studentsSelect.selectedOptions);
    let message = '';

    if (selectedStudents.length > 0) {
        message = `Envoyer ce projet à ${selectedStudents.length} étudiant(s) sélectionné(s) ?`;
    } else if (formations.includes('all')) {
        message = `Envoyer ce projet à TOUS les étudiants (${stats['all']} étudiants) ?`;
    } else {
        const selectedSpecificFormations = formations.filter(v => v !== 'all');
        const total = selectedSpecificFormations.reduce((sum, f) => sum + (stats[f] || 0), 0);
        if (selectedSpecificFormations.length === 1) {
            const f = selectedSpecificFormations[0];
            const count = stats[f] || 0;
            message = `Envoyer ce projet à tous les étudiants de ${f} (${count} étudiants) ?`;
        } else {
            message = `Envoyer ce projet aux formations sélectionnées (${total} étudiants) ?`;
        }
    }

    if (!confirm(message)) {
        e.preventDefault();
        return false;
    }
});
</script>
@endpush
