@extends('layouts.ki-admin')

@section('title', 'Fin de Formation - EVC 2024')

@section('page-title')
    Fin de Formation - {{ $student->program ?? "Formation" }}
@endsection

@section('content')
<!-- En-tête avec statut global -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #ff9800 100%); color: white;">
            <div class="card-body py-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2">
                            <i class="fas fa-graduation-cap me-3"></i>
                            Formation {{ $student->program ?? 'Infographie' }} - Bilan de fin de formation
                        </h3>
                        <p class="mb-0 opacity-75">Suivi de votre progression et critères d'éligibilité à la certification</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="progress-circle-large mb-2" style="position: relative; width: 100px; height: 100px; margin: 0 auto;">
                            <svg width="100" height="100" style="transform: rotate(-90deg);">
                                <circle cx="50" cy="50" r="40" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="6"></circle>
                                <circle cx="50" cy="50" r="40" fill="none" stroke="white" stroke-width="6"
                                        stroke-dasharray="251" stroke-dashoffset="{{ 251 - (251 * $globalProgress / 100) }}" stroke-linecap="round"></circle>
                            </svg>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 1.2rem; font-weight: bold;">{{ $globalProgress }}%</div>
                        </div>
                        <small class="opacity-75">Progression globale</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Progression des TP -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-laptop-code me-2" style="color: #28a745;"></i>
                    Travaux Pratiques (TP)
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="text-center p-3" style="background-color: #e8f5e8; border-radius: 10px;">
                            <h2 class="mb-1" style="color: #28a745;">{{ $tpValidated }} / {{ $minTPRequired }}</h2>
                            <p class="mb-0 text-muted">TP réalisés</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center p-3" style="background-color: #fff3e0; border-radius: 10px;">
                            <h2 class="mb-1" style="color: #FF9900;">{{ $tpPending }}</h2>
                            <p class="mb-0 text-muted">TP restants</p>
                        </div>
                    </div>
                </div>

                <div class="progress mb-3" style="height: 15px;">
                    <div class="progress-bar bg-success" style="width: {{ $tpProgress }}%;" role="progressbar">
                        <span class="fw-bold">{{ $tpProgress }}% complétés</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-success mb-2">
                            <i class="fas fa-check-circle me-1"></i>
                            TP validés ({{ $tpValidated }})
                        </h6>
                        <ul class="list-unstyled small">
                            @forelse($tpValidatedList->take(5) as $tp)
                                <li class="mb-1"><i class="fas fa-check text-success me-2"></i>{{ $tp->title }}</li>
                            @empty
                                <li class="mb-1 text-muted"><i class="fas fa-info-circle me-2"></i>Aucun TP validé</li>
                            @endforelse
                            @if($tpValidated > 5)
                                <li class="mb-1 text-muted"><i class="fas fa-ellipsis-h me-2"></i>Et {{ $tpValidated - 5 }} autres...</li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-warning mb-2">
                            <i class="fas fa-clock me-1"></i>
                            TP en attente ({{ $tpPending }})
                        </h6>
                        <ul class="list-unstyled small">
                            @forelse($tpPendingList->take(5) as $tp)
                                <li class="mb-1"><i class="fas fa-hourglass-half text-warning me-2"></i>{{ $tp->title }}</li>
                            @empty
                                <li class="mb-1 text-muted"><i class="fas fa-info-circle me-2"></i>Aucun TP en attente</li>
                            @endforelse
                            @if($tpPending > 5)
                                <li class="mb-1 text-muted"><i class="fas fa-ellipsis-h me-2"></i>Et {{ $tpPending - 5 }} autres...</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progression des Projets -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-project-diagram me-2" style="color: #C13584;"></i>
                    Projets de Formation
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="text-center p-3" style="background-color: #fff1f7; border-radius: 10px;">
                            <h2 class="mb-1" style="color: #C13584;">{{ $projectsCompleted }} / {{ $minProjectsRequired }}</h2>
                            <p class="mb-0 text-muted">Projets réalisés</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center p-3" style="background-color: #fff3e0; border-radius: 10px;">
                            <h2 class="mb-1" style="color: #FF9900;">{{ $projectsInProgress }}</h2>
                            <p class="mb-0 text-muted">Projet(s) en cours</p>
                        </div>
                    </div>
                </div>

                <div class="progress mb-3" style="height: 15px;">
                    <div class="progress-bar" style="width: {{ $projectProgress }}%; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #ff9800 100%);" role="progressbar">
                        <span class="fw-bold">{{ $projectProgress }}% complétés</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Projet</th>
                                <th>Module</th>
                                <th>Note</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($projects as $project)
                                <tr>
                                    <td><strong>{{ $project->title }}</strong></td>
                                    <td><span class="badge" style="background-color: #ff6633;">{{ $project->category }}</span></td>
                                    <td><span class="text-muted">-</span></td>
                                    <td>
                                        @if($project->status === 'valide')
                                            <span class="badge bg-success">Validé</span>
                                        @elseif($project->status === 'termine')
                                            <span class="badge bg-info">Terminé</span>
                                        @elseif($project->status === 'en_cours')
                                            <span class="badge bg-warning">En cours</span>
                                        @else
                                            <span class="badge bg-danger">Rejeté</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Aucun projet enregistré</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Rapport de fin de formation -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt me-2" style="color: #ff6633;"></i>
                    Rapport de fin de formation
                </h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-times-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($reportUploaded && $report)
                    <!-- Rapport déjà uploadé -->
                    <div class="alert alert-success">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fa-2x me-3"></i>
                                <div>
                                    <h6 class="mb-1">Rapport déjà soumis</h6>
                                    <p class="mb-0 small">
                                        Fichier : <strong>{{ $report->original_filename }}</strong><br>
                                        Uploadé le : {{ \Carbon\Carbon::parse($report->submitted_at)->format('d/m/Y à H:i') }}<br>
                                        Statut :
                                        @if(in_array($report->status, ['approved', 'validated', 'valide']))
                                            <span class="badge bg-success">Validé</span>
                                        @elseif(in_array($report->status, ['rejected', 'rejete']))
                                            <span class="badge bg-danger">Rejeté</span>
                                        @else
                                            <span class="badge bg-warning">En attente de validation</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div>
                                @if(Route::has(($currentModule ?? 'design-graphique') . '.fin-formation.download-report'))
                                    <a href="{{ route(($currentModule ?? 'design-graphique') . '.fin-formation.download-report', $report->id) }}"
                                       class="btn btn-sm btn-primary mb-2">
                                        <i class="fas fa-download me-1"></i> Télécharger
                                    </a>
                                @else
                                    <a href="{{ route(($currentModule ?? 'design-graphique') . '.documents.index') }}"
                                       class="btn btn-sm btn-primary mb-2">
                                        <i class="fas fa-folder-open me-1"></i> Voir dans Documents
                                    </a>
                                @endif
                                @if(Route::has(($currentModule ?? 'design-graphique') . '.fin-formation.upload-report'))
                                    <button type="button" class="btn btn-sm btn-warning" onclick="showReplaceForm()">
                                        <i class="fas fa-sync-alt me-1"></i> Remplacer
                                    </button>
                                @else
                                    <a href="{{ route(($currentModule ?? 'design-graphique') . '.tp.modifier', $report->id) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-pen me-1"></i> Modifier
                                    </a>
                                @endif
                            </div>
                        </div>
                        @if($report->admin_comment)
                            <div class="mt-3 p-2" style="background-color: #f8f9fa; border-left: 3px solid #ff6633;">
                                <strong>Commentaire administrateur :</strong><br>
                                {{ $report->admin_comment }}
                            </div>
                        @endif
                    </div>

                    <!-- Formulaire de remplacement (masqué par défaut) -->
                    <div id="replaceFormContainer" style="display: none;">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Vous pouvez remplacer votre rapport actuel en uploadant un nouveau fichier.
                        </div>
                        @if(Route::has(($currentModule ?? 'design-graphique') . '.fin-formation.upload-report'))
                            <form action="{{ route(($currentModule ?? 'design-graphique') . '.fin-formation.upload-report') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="text-center">
                                    <input type="file" name="report_file" id="reportFileReplace" accept=".pdf" class="form-control mb-3" required>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-upload me-1"></i> Remplacer le rapport
                                        </button>
                                        <button type="button" class="btn btn-secondary" onclick="hideReplaceForm()">
                                            Annuler
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <div class="text-center">
                                <a href="{{ route(($currentModule ?? 'design-graphique') . '.documents.index') }}" class="btn btn-primary">
                                    <i class="fas fa-folder-open me-1"></i> Aller dans Documents
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <!-- Aucun rapport uploadé -->
                    <div class="alert alert-warning">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">Rapport en cours de rédaction</h6>
                                <p class="mb-0 small">Le rapport de fin de formation doit être rédigé et soumis avant la certification</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="mb-3">Contenu requis du rapport :</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-square-check text-muted me-2"></i>
                                    Bilan de compétences acquises
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-square-check text-muted me-2"></i>
                                    Analyse des projets réalisés
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-square-check text-muted me-2"></i>
                                    Perspectives professionnelles
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-square-check text-muted me-2"></i>
                                    Retour d'expérience sur la formation
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <form action="{{ route(($currentModule ?? 'design-graphique') . '.fin-formation.upload-report') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-upload" style="font-size: 3rem; color: #ff6633;"></i>
                                    </div>

                                    <div class="mb-3">
                                        <input type="file" name="report_file" id="reportFile" accept=".pdf" class="form-control" required>
                                        @error('report_file')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 mb-2">
                                        <i class="fas fa-upload me-1"></i> Uploader le rapport
                                    </button>

                                    <small class="text-muted">Format PDF - Max 10 MB</small>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar - Critères d'éligibilité -->
    <div class="col-md-4">
        <!-- Critères d'éligibilité à la certification -->
        <div class="card mb-4">
            <div class="card-header" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #ff9800 100%); color: white;">
                <h6 class="mb-0">
                    <i class="fas fa-certificate me-2"></i>
                    Critères d'éligibilité à la certification
                </h6>
            </div>
            <div class="card-body">
                <div class="eligibility-criteria">
                    <!-- Paiement -->
                    <div class="criteria-item mb-3 p-3" style="border: 1px solid #e9ecef; border-radius: 8px;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-credit-card text-warning me-2"></i>
                                <strong>Paiement intégral</strong>
                            </div>
                            @if($paymentComplete)
                                <span class="badge bg-success">Soldé</span>
                            @else
                                <span class="badge bg-warning">En attente</span>
                            @endif
                        </div>
                        <div class="text-center mb-2" style="font-size: 1.5rem; font-weight: 600; color: #FF9900;">
                            {{ number_format($paymentRemaining, 0, ',', ' ') }} FCFA restants à régler
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: {{ $paymentProgress }}%;" role="progressbar"></div>
                        </div>
                    </div>

                    <!-- TP obligatoires -->
                    <div class="criteria-item mb-3 p-3" style="border: 1px solid #e9ecef; border-radius: 8px;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-laptop-code text-warning me-2"></i>
                                <strong>{{ $minTPRequired }} TP obligatoires</strong>
                            </div>
                            @if($tpEligible)
                                <span class="badge bg-success">{{ $tpValidated }}/{{ $minTPRequired }}</span>
                            @else
                                <span class="badge bg-warning">{{ $tpValidated }}/{{ $minTPRequired }}</span>
                            @endif
                        </div>
                        <small class="text-muted">{{ $minTPRequired - $tpValidated }} TP restants à valider</small>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-success" style="width: {{ $tpProgress }}%;" role="progressbar"></div>
                        </div>
                    </div>

                    <!-- Projets obligatoires -->
                    <div class="criteria-item mb-3 p-3" style="border: 1px solid #e9ecef; border-radius: 8px;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-project-diagram text-warning me-2"></i>
                                <strong>4 projets obligatoires</strong>
                            </div>
                            @if($projectsEligible)
                                <span class="badge bg-success">{{ $projectsCompleted }}/{{ $minProjectsRequired }}</span>
                            @else
                                <span class="badge bg-warning">{{ $projectsCompleted }}/{{ $minProjectsRequired }}</span>
                            @endif
                        </div>
                        <small class="text-muted">{{ $minProjectsRequired - $projectsCompleted }} projet(s) restant(s)</small>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar" style="width: {{ $projectProgress }}%; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #ff9800 100%);" role="progressbar"></div>
                        </div>
                    </div>

                    <!-- Rapport de fin de formation -->
                    <div class="criteria-item mb-3 p-3" style="border: 1px solid #e9ecef; border-radius: 8px;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-alt text-danger me-2"></i>
                                <strong>Rapport de fin de formation</strong>
                            </div>
                            @if($reportUploaded)
                                @if(!empty($report) && in_array($report->status, ['approved', 'validated', 'valide']))
                                    <span class="badge bg-success">Validé</span>
                                @else
                                    <span class="badge bg-warning">En attente</span>
                                @endif
                            @else
                                <span class="badge bg-danger">Non rédigé</span>
                            @endif
                        </div>
                        <small class="text-muted">À rédiger et soumettre</small>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-danger" style="width: {{ $reportUploaded ? 100 : 0 }}%;" role="progressbar"></div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Statut global d'éligibilité -->
                @if($isEligible)
                    <div class="text-center p-3" style="background-color: #e8f5e8; border-radius: 8px; border: 2px solid #28a745;">
                        <div class="mb-2">
                            <i class="fas fa-check-circle" style="color: #28a745; font-size: 2rem;"></i>
                        </div>
                        <h6 class="fw-bold" style="color: #28a745;">ÉLIGIBLE</h6>
                        <small class="text-muted">Félicitations ! Vous remplissez tous les critères</small>
                    </div>

                    <!-- Boutons d'actions pour le certificat -->
                    <div class="mt-3">
                        <a href="{{ route('certificate.preview') }}" target="_blank" class="btn btn-lg w-100 text-white mb-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(102, 126, 234, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(102, 126, 234, 0.3)'">
                            <i class="fas fa-eye me-2"></i>
                            Voir mon certificat
                        </a>
                        <a href="{{ route('certificate.download') }}" class="btn btn-lg w-100 text-white" style="background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); border: none; box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(255, 215, 0, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(255, 215, 0, 0.3)'">
                            <i class="fas fa-download me-2"></i>
                            Télécharger mon certificat
                        </a>
                        <div class="text-center mt-2">
                            <small class="text-muted">
                                <i class="fas fa-award me-1"></i>
                                Votre certificat est prêt
                            </small>
                        </div>
                    </div>
                @else
                    <div class="text-center p-3" style="background-color: #fff3e0; border-radius: 8px; border: 2px solid #FF9900;">
                        <div class="mb-2">
                            <i class="fas fa-hourglass-half" style="color: #FF9900; font-size: 2rem;"></i>
                        </div>
                        <h6 class="fw-bold" style="color: #e65100;">NON ÉLIGIBLE</h6>
                        <small class="text-muted">Finalisez tous les critères pour obtenir votre certification</small>
                    </div>
                @endif
            </div>
        </div>

        <!-- Résumé des exigences -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-list-check me-2"></i>
                    Résumé des exigences
                </h6>
            </div>
            <div class="card-body">
                <div class="requirement-summary">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">TP minimum requis :</span>
                        <strong style="color: #28a745;">{{ $minTPRequired }} TP</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Projets minimum requis :</span>
                        <strong style="color: #3399ff;">{{ $minProjectsRequired }} projets</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Note minimum :</span>
                        <strong style="color: #FF9900;">12/20</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Assiduité minimum :</span>
                        <strong style="color: #6f42c1;">80%</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Formation soldée :</span>
                        <strong style="color: #ff6633;">Obligatoire</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Actions rapides
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-success btn-sm" onclick="viewTP()">
                        <i class="fas fa-laptop-code me-1"></i>
                        Voir les TP restants
                    </button>
                    <button class="btn btn-outline-warning btn-sm" onclick="scrollToReportUpload()">
                        <i class="fas fa-upload me-1"></i>
                        Uploader le rapport
                    </button>
                    <button class="btn btn-outline-info btn-sm" onclick="payRemaining()">
                        <i class="fas fa-credit-card me-1"></i>
                        Finaliser le paiement
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary-color: #833AB4;
    --secondary-color: #E1306C;
    --accent-color: #ff6633;
    --warning-color: #FF9900;
    --success-color: #28a745;
}

.criteria-item {
    transition: all 0.3s ease;
}

.criteria-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: var(--primary-color) !important;
}

.requirement-summary {
    font-size: 0.9rem;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.btn:hover {
    transform: translateY(-1px);
    transition: all 0.3s ease;
}

.progress {
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
}

@media (max-width: 768px) {
    .criteria-item {
        margin-bottom: 1rem;
    }

    .requirement-summary {
        font-size: 0.8rem;
    }
}
</style>

<script>
// Fonction pour voir les TP restants
function viewTP() {
    window.location.href = "{{ route(($currentModule ?? 'design-graphique-cm') . '.tp.index') }}";
}

// Fonction pour faire défiler vers le rapport
function scrollToReportUpload() {
    const reportSection = document.querySelector('.card-header h5 i.fa-file-alt')?.closest('.card');
    if (reportSection) {
        reportSection.scrollIntoView({ behavior: 'smooth' });
    }
}

// Afficher le formulaire de remplacement du rapport
function showReplaceForm() {
    document.getElementById('replaceFormContainer').style.display = 'block';
}

// Masquer le formulaire de remplacement du rapport
function hideReplaceForm() {
    document.getElementById('replaceFormContainer').style.display = 'none';
}

// Fonction pour finaliser le paiement
function payRemaining() {
    window.location.href = "{{ route(($currentModule ?? 'design-graphique-cm') . '.paiements.index') }}";
}

// Animation des cartes au chargement
document.addEventListener('DOMContentLoaded', function() {
    // Animation des critères d'éligibilité
    const criteriaItems = document.querySelectorAll('.criteria-item');
    criteriaItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(20px)';

        setTimeout(() => {
            item.style.transition = 'all 0.5s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateX(0)';
        }, index * 200);
    });
});
</script>
@endsection
