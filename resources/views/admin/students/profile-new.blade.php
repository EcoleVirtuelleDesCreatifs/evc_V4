@extends('layouts.admin')

@section('title', 'Profil Étudiant - ' . ($data['student']['prenom'] ?? 'Étudiant') . ' ' . ($data['student']['nom'] ?? ''))

@section('content')
<div class="container-fluid px-4">
    <!-- Header avec photo et infos principales -->
    <div class="profile-header mb-4">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <!-- Photo de profil -->
                            <div class="col-auto">
                                @if(!empty($data['student']['photo_url']))
                                    <img src="{{ $data['student']['photo_url'] }}" alt="Photo" class="rounded-circle" style="width:100px;height:100px;object-fit:cover;border:4px solid #007bff;">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:100px;height:100px;font-size:36px;font-weight:bold;border:4px solid #007bff;">
                                        {{ substr($data['student']['prenom'] ?? 'E', 0, 1) }}{{ substr($data['student']['nom'] ?? 'T', 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Nom et informations principales -->
                            <div class="col">
                                <h2 class="mb-1">{{ $data['student']['prenom'] ?? 'Prénom' }} {{ $data['student']['nom'] ?? 'Nom' }}</h2>
                                <p class="text-muted mb-2">{{ $data['student']['gender'] ?? 'Homme' }}</p>
                                
                                <div class="row mt-3">
                                    <!-- Contact -->
                                    <div class="col-md-4">
                                        <h6 class="text-primary mb-2">Contact</h6>
                                        <p class="mb-1"><strong>{{ $data['student']['email'] }}</strong></p>
                                        <p class="mb-1">Tel: {{ $data['student']['phone'] ?? '-' }}</p>
                                        <p class="mb-0">WhatsApp: {{ $data['student']['whatsapp'] ?? $data['student']['phone'] ?? '-' }}</p>
                                    </div>
                                    
                                    <!-- Localisation -->
                                    <div class="col-md-4">
                                        <h6 class="text-primary mb-2">Localisation</h6>
                                        <p class="mb-1"><strong>{{ $data['student']['pays'] ?? 'Pays' }}</strong></p>
                                        <p class="mb-0">{{ $data['student']['ville'] ?? 'Ville' }}, {{ $data['student']['pays'] ?? 'Pays' }}</p>
                                    </div>
                                    
                                    <!-- Identité -->
                                    <div class="col-md-4">
                                        <h6 class="text-primary mb-2">Identité</h6>
                                        <p class="mb-0">Date de naissance: {{ $data['student']['date_of_birth'] ? date('d/m/Y', strtotime($data['student']['date_of_birth'])) : '-' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="col-auto">
                                <a href="{{ route('admin.students.edit', $data['student']['id']) }}" class="btn btn-warning mb-2">
                                    <i class="fas fa-edit me-1"></i>Modifier
                                </a>
                                <button class="btn btn-success mb-2" onclick="window.print()">
                                    <i class="fas fa-print me-1"></i>Imprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sections organisées -->
    <div class="row">
        <!-- Colonne gauche -->
        <div class="col-lg-6">
            <!-- IDENTITÉ -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>IDENTITÉ</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Prénom</label>
                            <p class="fw-bold mb-0">{{ $data['student']['prenom'] ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Nom</label>
                            <p class="fw-bold mb-0">{{ $data['student']['nom'] ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Date de naissance</label>
                            <p class="fw-bold mb-0">{{ $data['student']['date_of_birth'] ? date('d/m/Y', strtotime($data['student']['date_of_birth'])) : '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Sexe</label>
                            <p class="fw-bold mb-0">{{ $data['student']['gender'] ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ACADÉMIQUE -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>ACADÉMIQUE</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Nombre d'années d'Expérience</label>
                            <p class="fw-bold mb-0">{{ $data['student']['years_experience'] ?? '0' }} an(s)</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Secteur d'activité</label>
                            <p class="fw-bold mb-0">{{ $data['student']['industry_sector'] ?? 'Digital' }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="text-muted small">Niveau d'études</label>
                            <p class="fw-bold mb-0">{{ $data['student']['level'] ?? 'Niveau secondaire' }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="text-muted small">Domaine</label>
                            <p class="fw-bold mb-0">{{ $data['student']['formation'] ?? 'design_graphique' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CONTACT -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-phone me-2"></i>CONTACT</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="text-muted small">Email</label>
                            <p class="fw-bold mb-0">{{ $data['student']['email'] }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Téléphone</label>
                            <p class="fw-bold mb-0">{{ $data['student']['phone'] ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">WhatsApp</label>
                            <p class="fw-bold mb-0">{{ $data['student']['whatsapp'] ?? $data['student']['phone'] ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite -->
        <div class="col-lg-6">
            <!-- ADRESSE -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>ADRESSE</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="text-muted small">Quartier</label>
                            <p class="fw-bold mb-0">{{ $data['student']['quartier'] ?? 'Cocody' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Ville</label>
                            <p class="fw-bold mb-0">{{ $data['student']['ville'] ?? 'Abidjan' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Pays</label>
                            <p class="fw-bold mb-0">{{ $data['student']['pays'] ?? 'Canada' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STATISTIQUES -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>STATISTIQUES</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="stat-box bg-light p-3 rounded">
                                <h3 class="text-primary mb-0">{{ $data['stats']['total_tp'] }}</h3>
                                <small class="text-muted">TPs Soumis</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="stat-box bg-light p-3 rounded">
                                <h3 class="text-success mb-0">{{ $data['stats']['tp_valides'] }}</h3>
                                <small class="text-muted">TPs Validés</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="stat-box bg-light p-3 rounded">
                                <h3 class="text-warning mb-0">{{ $data['stats']['tp_en_cours'] }}</h3>
                                <small class="text-muted">En Attente</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="stat-box bg-light p-3 rounded">
                                <h3 class="text-info mb-0">{{ $data['stats']['progression'] }}%</h3>
                                <small class="text-muted">Progression</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Barre de progression -->
                    <div class="mt-3">
                        <label class="text-muted small">Progression Globale</label>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $data['stats']['progression'] }}%">
                                {{ $data['stats']['progression'] }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FINANCIER -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>FINANCIER</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="text-muted small">Total Factures</label>
                            <p class="fw-bold text-danger mb-0">{{ number_format($data['stats']['total_factures'], 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">Total Payé</label>
                            <p class="fw-bold text-success mb-0">{{ number_format($data['stats']['total_paye'], 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div class="col-12">
                            <div class="alert {{ $data['stats']['solde_restant'] > 0 ? 'alert-danger' : 'alert-success' }} mb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>Solde Restant:</strong>
                                    <span class="h5 mb-0">{{ number_format($data['stats']['solde_restant'], 0, ',', ' ') }} FCFA</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TRAVAUX PRATIQUES -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>TRAVAUX PRATIQUES ({{ $data['stats']['total_tp'] }})</h5>
                </div>
                <div class="card-body">
                    @if(count($data['tps']) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="20%">Titre</th>
                                        <th width="25%">Description</th>
                                        <th width="8%">Lien</th>
                                        <th width="10%">Statut</th>
                                        <th width="10%">Soumis le</th>
                                        <th width="10%">Validé le</th>
                                        <th width="12%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['tps'] as $index => $tp)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="fw-bold">{{ $tp->title ?? 'TP ' . ($index + 1) }}</td>
                                        <td><small>{!! Str::limit(strip_tags($tp->description ?? '-'), 60) !!}</small></td>
                                        <td>
                                            @if($tp->link)
                                                <a href="{{ $tp->link }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($tp->status === 'validated')
                                                <span class="badge bg-success">✓ Validé</span>
                                            @elseif($tp->status === 'pending')
                                                <span class="badge bg-warning">⏳ En attente</span>
                                            @elseif($tp->status === 'rejected')
                                                <span class="badge bg-danger">✗ Rejeté</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $tp->status }}</span>
                                            @endif
                                        </td>
                                        <td><small>{{ $tp->created_at ? date('d/m/Y', strtotime($tp->created_at)) : '-' }}</small></td>
                                        <td><small>{{ $tp->validated_at ? date('d/m/Y', strtotime($tp->validated_at)) : '-' }}</small></td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.tp.view', $tp->id) }}" class="btn btn-outline-primary" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($tp->status !== 'validated')
                                                <button type="button" class="btn btn-outline-success" title="Valider" onclick="validateTP({{ $tp->id }})">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                @endif
                                                <button type="button" class="btn btn-outline-danger" title="Supprimer" onclick="deleteTP({{ $tp->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted py-4">Aucun TP soumis</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- PROJETS DESIGN -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-palette me-2"></i>PROJETS DESIGN ({{ $data['stats']['total_projects'] }})</h5>
                </div>
                <div class="card-body">
                    @if(count($data['projects']) > 0)
                        <div class="row">
                            @foreach($data['projects'] as $project)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 shadow-sm hover-shadow">
                                    @php
                                        $projectFiles = isset($project->project_files) ? $project->project_files : [];
                                        
                                        // Fonction pour détecter si c'est une image
                                        $isImage = function($file) {
                                            // Vérifier d'abord le file_type
                                            if (isset($file->file_type) && $file->file_type === 'image') {
                                                return true;
                                            }
                                            
                                            // Sinon, vérifier l'extension du fichier
                                            $filePath = $file->file_path ?? $file->original_name ?? '';
                                            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg', 'ico'];
                                            
                                            if (in_array($extension, $imageExtensions)) {
                                                return true;
                                            }
                                            
                                            // Vérifier aussi le mime_type si disponible
                                            if (isset($file->mime_type) && strpos($file->mime_type, 'image/') === 0) {
                                                return true;
                                            }
                                            
                                            return false;
                                        };
                                        
                                        $imageFiles = array_filter($projectFiles->toArray(), $isImage);
                                        $otherFiles = array_filter($projectFiles->toArray(), function($file) use ($isImage) {
                                            return !$isImage($file);
                                        });
                                        $hasImages = !empty($imageFiles);
                                    @endphp
                                    
                                    <!-- Galerie d'images en haut de la carte -->
                                    @if($hasImages)
                                    <div class="project-image-gallery" style="height: 200px; overflow: hidden; position: relative; background: #f8f9fa;">
                                        @if(count($imageFiles) === 1)
                                            @php $image = reset($imageFiles); @endphp
                                            <img src="{{ asset($image->file_path) }}" 
                                                 alt="" 
                                                 class="w-100 h-100" 
                                                 style="object-fit: cover; cursor: pointer; background: #f0f0f0;"
                                                 onclick="viewImage('{{ asset($image->file_path) }}', '{{ $image->original_name }}')">
                                            <div class="position-absolute top-50 start-50 translate-middle text-center" style="display: none;" id="placeholder-{{ $project->id }}-0">
                                                <i class="fas fa-image text-muted fa-3x mb-2"></i>
                                                <p class="text-muted small">Image non disponible</p>
                                            </div>
                                        @else
                                            <!-- Grille d'images -->
                                            <div class="row g-1 h-100 p-1">
                                                @foreach(array_slice($imageFiles, 0, 4) as $index => $image)
                                                <div class="{{ count($imageFiles) <= 2 ? 'col-6' : 'col-6' }} h-{{ count($imageFiles) <= 2 ? '100' : '50' }} p-0">
                                                    <div class="position-relative h-100" style="padding: 2px;">
                                                        <div class="w-100 h-100" style="background: #f0f0f0; border-radius: 4px; overflow: hidden;">
                                                            <img src="{{ asset($image->file_path) }}" 
                                                                 alt="" 
                                                                 class="w-100 h-100 project-image" 
                                                                 style="object-fit: cover; cursor: pointer;"
                                                                 onclick="viewImage('{{ asset($image->file_path) }}', '{{ $image->original_name }}')"
                                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                            <div class="d-none w-100 h-100 align-items-center justify-content-center" style="flex-direction: column; background: #f8f9fa;">
                                                                <i class="fas fa-image text-muted fa-2x mb-2"></i>
                                                                <small class="text-muted">Image indisponible</small>
                                                            </div>
                                                        </div>
                                                        @if($index === 3 && count($imageFiles) > 4)
                                                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
                                                             style="background: rgba(0,0,0,0.6); color: white; font-size: 1.5rem; font-weight: bold; cursor: pointer; border-radius: 4px;"
                                                             onclick="showProjectDetails({{ $project->id ?? 0 }})">
                                                            +{{ count($imageFiles) - 4 }}
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        @endif
                                        
                                        <!-- Badge du nombre total d'images -->
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="badge bg-dark bg-opacity-75">
                                                <i class="fas fa-images"></i> {{ count($imageFiles) }}
                                            </span>
                                        </div>
                                    </div>
                                    @else
                                    <!-- Placeholder si pas d'images -->
                                    <div class="project-image-placeholder d-flex align-items-center justify-content-center" 
                                         style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                        <div class="text-center">
                                            <i class="fas fa-palette fa-3x mb-2 opacity-50"></i>
                                            <p class="mb-0 small">Aucune image</p>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0 flex-grow-1">{{ $project->title ?? 'Projet' }}</h6>
                                            @if(($project->status ?? 'en_cours') === 'valide')
                                                <span class="badge bg-success ms-2">Validé</span>
                                            @elseif(($project->status ?? 'en_cours') === 'en_cours' || ($project->status ?? '') === 'active')
                                                <span class="badge bg-warning text-dark ms-2">En cours</span>
                                            @else
                                                <span class="badge bg-info ms-2">{{ $project->status }}</span>
                                            @endif
                                        </div>
                                        
                                        <p class="card-text text-muted small mb-2">{{ Str::limit($project->description ?? '', 100) }}</p>
                                        
                                        <div class="d-flex align-items-center text-muted small mb-3">
                                            <i class="fas fa-calendar me-1"></i>
                                            <span>{{ date('d/m/Y', strtotime($project->created_at)) }}</span>
                                            @if(!empty($otherFiles))
                                            <span class="ms-3">
                                                <i class="fas fa-file me-1"></i>
                                                {{ count($otherFiles) }} fichier(s)
                                            </span>
                                            @endif
                                        </div>
                                        
                                        <!-- Boutons d'action -->
                                        <div class="btn-group btn-group-sm w-100" role="group">
                                            <button class="btn btn-outline-primary" 
                                                    onclick="showProjectDetails({{ $project->id ?? 0 }})" 
                                                    title="Voir les détails">
                                                <i class="fas fa-eye"></i> Voir
                                            </button>
                                            @if(($project->status ?? 'en_cours') !== 'valide')
                                            <button class="btn btn-outline-success" 
                                                    onclick="if(confirm('Êtes-vous sûr de vouloir valider ce projet ?')) validateProject({{ $project->id ?? 0 }})" 
                                                    title="Valider le projet">
                                                <i class="fas fa-check"></i> Valider
                                            </button>
                                            @endif
                                            <button class="btn btn-outline-secondary" 
                                                    onclick="downloadProject({{ $project->id ?? 0 }})" 
                                                    title="Télécharger le projet">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" 
                                                    onclick="if(confirm('⚠️ Supprimer ce projet ?')) deleteProjectFromProfile({{ $project->id ?? 0 }})" 
                                                    title="Supprimer le projet">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-muted py-4">Aucun projet design</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- PAIEMENTS ET FACTURES -->
    <div class="row mt-4 mb-4">
        <!-- Factures -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-file-invoice me-2"></i>FACTURES</h5>
                </div>
                <div class="card-body">
                    @if(isset($data['factures']) && count($data['factures']) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Montant</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['factures'] as $facture)
                                    <tr>
                                        <td>#{{ $facture->id }}</td>
                                        <td class="fw-bold">{{ number_format($facture->montant, 0, ',', ' ') }} FCFA</td>
                                        <td>{{ date('d/m/Y', strtotime($facture->created_at)) }}</td>
                                        <td><span class="badge bg-warning">{{ $facture->statut ?? 'En attente' }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted py-3">Aucune facture</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Paiements -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>PAIEMENTS</h5>
                </div>
                <div class="card-body">
                    @if(isset($data['paiements']) && count($data['paiements']) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Montant</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['paiements'] as $paiement)
                                    <tr>
                                        <td>#{{ $paiement->id }}</td>
                                        <td class="fw-bold text-success">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
                                        <td>{{ date('d/m/Y', strtotime($paiement->created_at)) }}</td>
                                        <td>
                                            @if($paiement->statut === 'validé')
                                                <span class="badge bg-success">✓ Validé</span>
                                            @else
                                                <span class="badge bg-warning">{{ $paiement->statut }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted py-3">Aucun paiement</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .btn, .breadcrumb, nav { display: none !important; }
}

.profile-header .card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.stat-box {
    transition: transform 0.2s;
}

.stat-box:hover {
    transform: translateY(-5px);
}

.card-header {
    font-weight: 600;
    border-radius: 0.375rem 0.375rem 0 0 !important;
}

label.small {
    font-size: 0.85rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Styles pour la galerie d'images des projets */
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
    transform: translateY(-5px);
}

.project-image-gallery img {
    transition: transform 0.3s ease;
}

.project-image-gallery img:hover {
    transform: scale(1.05);
}

/* Modal lightbox pour les images */
#imageLightbox {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    overflow: auto;
}

#imageLightbox img {
    margin: auto;
    display: block;
    max-width: 90%;
    max-height: 90%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    animation: zoomIn 0.3s;
}

@keyframes zoomIn {
    from { transform: translate(-50%, -50%) scale(0.5); }
    to { transform: translate(-50%, -50%) scale(1); }
}

#imageLightbox .close-lightbox {
    position: absolute;
    top: 20px;
    right: 40px;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
    z-index: 10000;
}

#imageLightbox .close-lightbox:hover {
    color: #ccc;
}
</style>
@endpush

@push('scripts')
<script>
// Modal de chargement
/**
 * Fermer et nettoyer complètement le modal de chargement
 */
function closeLoadingModal() {
    // Fermer tous les modals Bootstrap ouverts
    const loadingModal = document.getElementById('loadingModal');
    if (loadingModal) {
        const bsModal = bootstrap.Modal.getInstance(loadingModal);
        if (bsModal) {
            bsModal.hide();
        }
        // Retirer le modal du DOM après l'animation
        setTimeout(() => {
            if (loadingModal.parentNode) {
                loadingModal.parentNode.removeChild(loadingModal);
            }
        }, 500);
    }
    
    // Supprimer tous les backdrops qui pourraient rester
    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
        backdrop.remove();
    });
    
    // Réinitialiser le body
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
}

function showLoadingModal(message = 'Traitement en cours...') {
    // Nettoyer d'abord tout modal de chargement existant
    closeLoadingModal();
    
    // Créer un nouveau modal
    const modal = document.createElement('div');
    modal.id = 'loadingModal';
    modal.className = 'modal fade';
    modal.setAttribute('data-bs-backdrop', 'static');
    modal.setAttribute('data-bs-keyboard', 'false');
    modal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-5">
                    <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <h5 id="loadingMessage">${message}</h5>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    return bsModal;
}

// Notification de succès
function showSuccessNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'alert alert-success alert-dismissible fade show position-fixed';
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 99999; max-width: 400px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
    notification.innerHTML = `
        <i class="fas fa-check-circle me-2"></i>
        <strong>Succès !</strong>
        <div class="mt-1">${message}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    document.body.removeChild(notification);
                }
            }, 150);
        }
    }, 3000);
}

// Notification d'erreur
function showErrorNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'alert alert-danger alert-dismissible fade show position-fixed';
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 99999; max-width: 400px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
    notification.innerHTML = `
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Erreur !</strong>
        <div class="mt-1">${message}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    document.body.removeChild(notification);
                }
            }, 150);
        }
    }, 5000);
}

// Valider un TP
function validateTP(tpId) {
    if (!confirm('✅ Êtes-vous sûr de vouloir valider ce TP ?')) {
        return;
    }
    
    const loadingModal = showLoadingModal('Validation du TP en cours...');
    
    fetch(`/evc/app/admin/tp/validate/${tpId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        loadingModal.hide();
        if (data.success) {
            showSuccessNotification(data.message || 'TP validé avec succès !');
            setTimeout(() => location.reload(), 2000);
        } else {
            showErrorNotification(data.message || 'Impossible de valider le TP');
        }
    })
    .catch(error => {
        loadingModal.hide();
        console.error('Erreur:', error);
        showErrorNotification('Une erreur est survenue lors de la validation.');
    });
}

// Rejeter un TP
function rejectTP(tpId) {
    const reason = prompt('⚠️ Raison du rejet du TP :\n(Cette information sera envoyée à l\'étudiant)');
    
    if (!reason || reason.trim() === '') {
        showErrorNotification('Vous devez fournir une raison pour rejeter le TP.');
        return;
    }
    
    const loadingModal = showLoadingModal('Rejet du TP en cours...');
    
    fetch(`/evc/app/admin/tp/reject/${tpId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ reason: reason.trim() })
    })
    .then(response => response.json())
    .then(data => {
        loadingModal.hide();
        if (data.success) {
            showSuccessNotification(data.message || 'TP rejeté avec succès !');
            setTimeout(() => location.reload(), 2000);
        } else {
            showErrorNotification(data.message || 'Impossible de rejeter le TP');
        }
    })
    .catch(error => {
        loadingModal.hide();
        console.error('Erreur:', error);
        showErrorNotification('Une erreur est survenue lors du rejet.');
    });
}

// Supprimer un TP
function deleteTP(tpId) {
    console.log('Delete TP called with ID:', tpId); // Debug
    
    if (!confirm('⚠️ ATTENTION !\n\nÊtes-vous sûr de vouloir supprimer ce TP ?\nCette action est IRRÉVERSIBLE.')) {
        return;
    }
    
    const loadingModal = showLoadingModal('Suppression du TP en cours...');
    const url = `/evc/compte/design-graphique/tp/supprimer/${tpId}`;
    console.log('DELETE URL:', url); // Debug
    
    fetch(url, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status); // Debug
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Error response:', text);
                throw new Error(`HTTP ${response.status}: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        loadingModal.hide();
        console.log('Success data:', data); // Debug
        if (data.success) {
            showSuccessNotification(data.message || 'TP supprimé avec succès !');
            setTimeout(() => location.reload(), 2000);
        } else {
            showErrorNotification(data.message || 'Impossible de supprimer le TP');
        }
    })
    .catch(error => {
        loadingModal.hide();
        console.error('Erreur complète:', error);
        showErrorNotification('Une erreur est survenue: ' + error.message);
    });
}

// ==================== FONCTIONS POUR PROJETS DESIGN ====================

/**
 * Afficher les détails d'un projet design
 * @param {number} projectId - ID du projet à afficher
 */
function showProjectDetails(projectId) {
    const loadingModal = showLoadingModal('Chargement des détails du projet...');
    
    console.log('Chargement du projet ID:', projectId);
    
    fetch(`/evc/app/admin/projects/${projectId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Réponse reçue, status:', response.status);
        
        // Essayer de lire la réponse comme texte d'abord pour le debug
        return response.text().then(text => {
            console.log('Réponse brute:', text.substring(0, 200));
            
            // Essayer de parser comme JSON
            try {
                const data = JSON.parse(text);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${data.message || 'Erreur serveur'}`);
                }
                return data;
            } catch (e) {
                console.error('Erreur de parsing JSON:', e);
                throw new Error('Réponse invalide du serveur: ' + text.substring(0, 100));
            }
        });
    })
    .then(data => {
        console.log('Données reçues:', data);
        
        if (data.success) {
            // Fermer le modal de chargement complètement avant d'ouvrir le modal des détails
            closeLoadingModal();
            
            // Attendre un court instant pour que le modal de chargement soit complètement fermé
            setTimeout(() => {
                showProjectDetailsModal(data.project);
            }, 300);
        } else {
            closeLoadingModal();
            showErrorNotification(data.message || 'Impossible de charger les détails du projet');
        }
    })
    .catch(error => {
        console.error('Erreur complète:', error);
        closeLoadingModal();
        showErrorNotification('Une erreur est survenue: ' + error.message);
    });
}

/**
 * Afficher la modal des détails du projet
 * @param {object} project - Données du projet
 */
function showProjectDetailsModal(project) {
    // Créer la modal si elle n'existe pas
    let modal = document.getElementById('projectDetailsModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'projectDetailsModal';
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title"><i class="fas fa-palette me-2"></i>Détails du Projet</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="projectDetailsContent">
                        <!-- Le contenu sera inséré ici -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
    
    // Remplir le contenu de la modal
    const content = `
        <div class="row">
            <div class="col-12 mb-4">
                <h4 class="text-primary fw-bold mb-2">${project.title || 'Sans titre'}</h4>
                <p class="text-secondary" style="font-size: 1rem; line-height: 1.6;">${project.description || 'Aucune description'}</p>
            </div>
            <div class="col-md-6 mb-3">
                <div class="d-flex align-items-center">
                    <strong class="text-dark me-2" style="font-size: 0.95rem;">Statut:</strong> 
                    <span class="badge ${project.status === 'valide' ? 'bg-success' : 'bg-warning'}" style="font-size: 0.9rem;">
                        ${project.status === 'valide' ? 'Validé' : 'En cours'}
                    </span>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="d-flex align-items-center">
                    <strong class="text-dark me-2" style="font-size: 0.95rem;">Date de création:</strong>
                    <span class="text-secondary">${project.created_at || '-'}</span>
                </div>
            </div>
            ${project.files && project.files.length > 0 ? `
                <div class="col-12 mt-3">
                    <h6 class="text-dark fw-bold mb-3"><i class="fas fa-paperclip me-2 text-primary"></i>Fichiers associés (${project.files.length})</h6>
                    <ul class="list-group">
                        ${project.files.map(file => `
                            <li class="list-group-item d-flex justify-content-between align-items-center" style="border-left: 3px solid #0d6efd;">
                                <span class="text-dark" style="font-size: 0.95rem;">
                                    <i class="fas fa-file text-primary me-2"></i>${file.name || file}
                                </span>
                                <a href="${file.url || '#'}" class="btn btn-sm btn-primary" download title="Télécharger">
                                    <i class="fas fa-download"></i>
                                </a>
                            </li>
                        `).join('')}
                    </ul>
                </div>
            ` : '<div class="col-12 mt-3"><div class="alert alert-info mb-0"><i class="fas fa-info-circle me-2"></i>Aucun fichier associé à ce projet</div></div>'}
        </div>
    `;
    
    document.getElementById('projectDetailsContent').innerHTML = content;
    
    // Afficher la modal
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}

/**
 * Valider un projet design
 * @param {number} projectId - ID du projet à valider
 */
function validateProject(projectId) {
    const loadingModal = showLoadingModal('Validation du projet en cours...');
    
    fetch(`/evc/app/admin/projects/${projectId}/validate`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        loadingModal.hide();
        if (data.success) {
            showSuccessNotification(data.message || 'Projet validé avec succès !');
            setTimeout(() => location.reload(), 2000);
        } else {
            showErrorNotification(data.message || 'Impossible de valider le projet');
        }
    })
    .catch(error => {
        loadingModal.hide();
        console.error('Erreur:', error);
        showErrorNotification('Une erreur est survenue lors de la validation.');
    });
}

/**
 * Télécharger un projet design
 * @param {number} projectId - ID du projet à télécharger
 */
function downloadProject(projectId) {
    showLoadingModal('Préparation du téléchargement...');
    
    // Ouvrir dans un nouvel onglet pour télécharger
    window.open(`/evc/app/admin/projects/${projectId}/download`, '_blank');
    
    // Fermer le modal de chargement après un court délai
    setTimeout(() => {
        const loadingModal = document.getElementById('loadingModal');
        if (loadingModal) {
            bootstrap.Modal.getInstance(loadingModal)?.hide();
        }
    }, 1000);
}

/**
 * Supprimer un projet depuis le profil étudiant (admin)
 * @param {number} projectId - ID du projet à supprimer
 */
function deleteProjectFromProfile(projectId) {
    if (!confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce projet ?\n\nCette action est irréversible et supprimera tous les fichiers associés.')) {
        return;
    }
    
    const loadingModal = showLoadingModal('Suppression du projet en cours...');
    
    fetch(`/evc/app/admin/projects/${projectId}/delete`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Error response:', text);
                throw new Error(`HTTP ${response.status}: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        loadingModal.hide();
        if (data.success) {
            showSuccessNotification(data.message || 'Projet supprimé avec succès !');
            setTimeout(() => location.reload(), 2000);
        } else {
            showErrorNotification(data.message || 'Impossible de supprimer le projet');
        }
    })
    .catch(error => {
        loadingModal.hide();
        console.error('Erreur complète:', error);
        showErrorNotification('Une erreur est survenue: ' + error.message);
    });
}

/**
 * Afficher une image en plein écran (lightbox)
 * @param {string} imageUrl - URL de l'image
 * @param {string} imageName - Nom de l'image
 */
function viewImage(imageUrl, imageName) {
    // Créer le lightbox s'il n'existe pas
    let lightbox = document.getElementById('imageLightbox');
    if (!lightbox) {
        lightbox = document.createElement('div');
        lightbox.id = 'imageLightbox';
        lightbox.innerHTML = `
            <span class="close-lightbox" onclick="closeLightbox()">&times;</span>
            <img id="lightboxImage" src="" alt="">
            <div style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); color: white; text-align: center; background: rgba(0,0,0,0.7); padding: 10px 20px; border-radius: 5px;">
                <span id="lightboxImageName"></span>
            </div>
        `;
        document.body.appendChild(lightbox);
        
        // Fermer au clic sur le fond
        lightbox.onclick = function(e) {
            if (e.target === lightbox) {
                closeLightbox();
            }
        };
    }
    
    // Afficher l'image
    document.getElementById('lightboxImage').src = imageUrl;
    document.getElementById('lightboxImageName').textContent = imageName;
    lightbox.style.display = 'block';
    document.body.style.overflow = 'hidden'; // Empêcher le scroll
}

/**
 * Fermer le lightbox
 */
function closeLightbox() {
    const lightbox = document.getElementById('imageLightbox');
    if (lightbox) {
        lightbox.style.display = 'none';
        document.body.style.overflow = 'auto'; // Réactiver le scroll
    }
}

// Fermer le lightbox avec la touche Echap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLightbox();
    }
});
</script>
@endpush
@endsection
