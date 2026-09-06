@extends('layouts.admin')

@section('title', 'Fiche Éligibilité - ' . $student->full_name)

@push('styles')
<style>
    body { background: #0f172a; }
    .certif-header {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border-radius: 20px;
        padding: 2rem;
        color: #fff;
        margin-bottom: 2rem;
    }
    .card-dark {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        color: #fff;
    }
    .card-dark .card-header {
        background: transparent;
        border-bottom: 1px solid #334155;
        font-weight: 700;
    }
    .criterion-item {
        padding: 1rem 0;
        border-bottom: 1px solid #334155;
    }
    .criterion-item:last-child { border-bottom: none; }
    .badge-c {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-weight: 600;
    }
    .avatar-lg {
        width: 80px; height: 80px; border-radius: 50%; object-fit: cover;
        border: 3px solid #7c3aed;
    }
    .history-item {
        border-left: 3px solid #7c3aed;
        padding-left: 1rem;
        margin-bottom: 1rem;
    }
    .btn-confirm {
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        color: #fff;
    }
    .btn-reject {
        background: linear-gradient(135deg, #f43f5e, #e11d48);
        border: none;
        color: #fff;
    }
    .btn-review {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border: none;
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="certif-header d-flex justify-content-between align-items-start flex-column flex-md-row gap-3">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-user-graduate me-2"></i>Fiche d'éligibilité</h1>
            <p class="mb-0 opacity-75">{{ $student->full_name }} — {{ $student->student_id }}</p>
        </div>
        <a href="{{ route('admin.certification-eligibility.index') }}" class="btn btn-light">
            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card card-dark mb-4">
                <div class="card-body text-center">
                    <img src="{{ $student->profile_photo_url }}" alt="" class="avatar-lg mb-3">
                    <h4 class="mb-1">{{ $student->full_name }}</h4>
                    <p class="text-muted mb-2">{{ $student->email }}</p>
                    <p class="mb-1"><i class="fas fa-phone me-1 text-muted"></i> {{ $student->phone ?? '—' }}</p>
                    <p class="mb-1"><i class="fas fa-id-card me-1 text-muted"></i> {{ $student->student_id }}</p>
                    <p class="mb-0"><i class="fas fa-book me-1 text-muted"></i> {{ $student->program ?? '—' }}</p>
                </div>
            </div>

            <div class="card card-dark">
                <div class="card-header py-3">
                    <i class="fas fa-history me-2"></i>Historique
                </div>
                <div class="card-body">
                    @forelse($histories as $history)
                        <div class="history-item">
                            <div class="small text-muted">{{ $history->created_at?->format('d/m/Y H:i') }}</div>
                            <div class="fw-semibold">
                                @if($history->to_admin_status)
                                    Statut admin : {{ $statusLabels['admin'][$history->to_admin_status] ?? $history->to_admin_status }}
                                @elseif($history->to_studio_status)
                                    Studio Creative : {{ $statusLabels['studio'][$history->to_studio_status] ?? $history->to_studio_status }}
                                @elseif($history->to_system_status)
                                    Système : {{ $statusLabels['system'][$history->to_system_status] ?? $history->to_system_status }}
                                @else
                                    Modification
                                @endif
                            </div>
                            @if($history->admin)
                                <div class="small text-muted">Par : {{ $history->admin->name }}</div>
                            @endif
                            @if($history->comment)
                                <div class="small mt-1 text-light">{{ $history->comment }}</div>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted mb-0">Aucun historique pour le moment.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card card-dark mb-4">
                <div class="card-header py-3">
                    <i class="fas fa-clipboard-check me-2"></i>Critères de certification
                </div>
                <div class="card-body">
                    <div class="criterion-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">Projets / TP</div>
                                <div class="small text-muted">Minimum {{ $eval['projects_required'] }} requis</div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold {{ $eval['projects_ok'] ? 'text-success' : 'text-danger' }}">
                                    {{ $eval['projects_count'] }} / {{ $eval['projects_required'] }}
                                </div>
                                <span class="badge-c {{ $eval['projects_ok'] ? 'bg-success' : 'bg-danger' }}">
                                    {{ $eval['projects_ok'] ? 'Conforme' : 'Non conforme' }}
                                </span>
                            </div>
                        </div>
                        <div class="small text-muted mt-1">
                            {{ $eval['tp_count'] }} TP — {{ $eval['project_count'] }} projets
                            @if($eval['manual_override'])
                                <span class="text-warning ms-1"><i class="fas fa-pen me-1"></i>Compteurs ajustés manuellement</span>
                            @endif
                        </div>
                    </div>

                    <div class="criterion-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">Paiement</div>
                            <div class="small text-muted">Formation soldée</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold {{ $eval['payment']['ok'] ? 'text-success' : 'text-danger' }}">
                                {{ number_format($eval['payment']['amount_paid'], 0, ',', ' ') }} / {{ number_format($eval['payment']['total_amount'], 0, ',', ' ') }} FCFA
                            </div>
                            <span class="badge-c {{ $eval['payment']['ok'] ? 'bg-success' : 'bg-danger' }}">
                                {{ $eval['payment']['ok'] ? 'Soldé' : 'Reste ' . number_format($eval['payment']['remaining'], 0, ',', ' ') . ' FCFA' }}
                            </span>
                        </div>
                    </div>

                    <div class="criterion-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">Rapport de fin de formation</div>
                            <div class="small text-muted">Dépôt requis</div>
                        </div>
                        <div class="text-end">
                            <span class="badge-c {{ $eval['report_ok'] ? 'bg-success' : 'bg-danger' }}">
                                {{ $eval['report_ok'] ? 'Déposé' : 'Manquant' }}
                            </span>
                        </div>
                    </div>

                    <div class="criterion-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">Portfolio</div>
                            <div class="small text-muted">Dépôt requis</div>
                        </div>
                        <div class="text-end">
                            <span class="badge-c {{ $eval['portfolio_ok'] ? 'bg-success' : 'bg-danger' }}">
                                {{ $eval['portfolio_ok'] ? 'Déposé' : 'Manquant' }}
                            </span>
                        </div>
                    </div>

                    <div class="criterion-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">Studio Creative</div>
                            <div class="small text-muted">Validation manuelle obligatoire</div>
                        </div>
                        <div class="text-end">
                            @if($eval['studio_creative_status'] === 'validated')
                                <span class="badge-c bg-success">Confirmé</span>
                            @elseif($eval['studio_creative_status'] === 'rejected')
                                <span class="badge-c bg-danger">Refusé</span>
                            @else
                                <span class="badge-c bg-warning text-dark">À vérifier</span>
                            @endif
                            @if($record->studio_creative_name)
                                <div class="small text-muted mt-1">{{ $record->studio_creative_name }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-dark mb-4">
                <div class="card-header py-3">
                    <i class="fas fa-tools me-2"></i>Validation Studio Creative
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.certification-eligibility.studio', $student) }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="studio_creative_status" class="form-label">Statut</label>
                                <select name="studio_creative_status" id="studio_creative_status" class="form-select">
                                    @foreach(['pending' => 'À vérifier', 'validated' => 'Confirmé', 'rejected' => 'Refusé'] as $key => $label)
                                        <option value="{{ $key }}" {{ $record->studio_creative_status === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="studio_creative_name" class="form-label">Studio concerné</label>
                                <input type="text" name="studio_creative_name" id="studio_creative_name" class="form-control" value="{{ old('studio_creative_name', $record->studio_creative_name) }}" placeholder="Ex : Studio Creative 2025">
                            </div>
                            <div class="col-md-4">
                                <label for="studio_creative_comment" class="form-label">Commentaire</label>
                                <input type="text" name="studio_creative_comment" id="studio_creative_comment" class="form-control" value="{{ old('studio_creative_comment', $record->studio_creative_comment) }}">
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Enregistrer Studio Creative</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card card-dark mb-4">
                <div class="card-header py-3">
                    <i class="fas fa-edit me-2"></i>Ajuster la fiche d'éligibilité
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.certification-eligibility.update-record', $student) }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="system_status" class="form-label">Statut système</label>
                                <select name="system_status" id="system_status" class="form-select">
                                    @foreach(['not_eligible' => 'Non éligible', 'pre_eligible' => 'Pré-éligible'] as $key => $label)
                                        <option value="{{ $key }}" {{ $record->system_status === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="admin_status" class="form-label">Statut administratif</label>
                                <select name="admin_status" id="admin_status" class="form-select">
                                    @foreach(['pending' => 'En attente', 'reviewing' => 'En vérification', 'eligible' => 'Éligible confirmé', 'rejected' => 'Refusé'] as $key => $label)
                                        <option value="{{ $key }}" {{ $record->admin_status === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="adjust_studio_creative_status" class="form-label">Studio Creative</label>
                                <select name="studio_creative_status" id="adjust_studio_creative_status" class="form-select">
                                    @foreach(['pending' => 'À vérifier', 'validated' => 'Confirmé', 'rejected' => 'Refusé'] as $key => $label)
                                        <option value="{{ $key }}" {{ $record->studio_creative_status === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="adjust_studio_creative_name" class="form-label">Studio concerné</label>
                                <input type="text" name="studio_creative_name" id="adjust_studio_creative_name" class="form-control" value="{{ old('studio_creative_name', $record->studio_creative_name) }}">
                            </div>
                            <div class="col-md-6">
                                <label for="adjust_studio_creative_comment" class="form-label">Commentaire Studio Creative</label>
                                <input type="text" name="studio_creative_comment" id="adjust_studio_creative_comment" class="form-control" value="{{ old('studio_creative_comment', $record->studio_creative_comment) }}">
                            </div>
                            <div class="col-md-6">
                                <label for="manual_tp_count" class="form-label">Compteur TP manuel (laisser vide = auto)</label>
                                <input type="number" min="0" name="manual_tp_count" id="manual_tp_count" class="form-control" value="{{ old('manual_tp_count', $record->manual_tp_count) }}" placeholder="Auto : {{ $eval['auto_tp_count'] }}">
                            </div>
                            <div class="col-md-6">
                                <label for="manual_projects_count" class="form-label">Compteur projets manuel (laisser vide = auto)</label>
                                <input type="number" min="0" name="manual_projects_count" id="manual_projects_count" class="form-control" value="{{ old('manual_projects_count', $record->manual_projects_count) }}" placeholder="Auto : {{ $eval['auto_project_count'] }}">
                            </div>
                            <div class="col-12">
                                <label for="admin_comment" class="form-label">Commentaire administratif</label>
                                <textarea name="admin_comment" id="admin_comment" class="form-control" rows="2">{{ old('admin_comment', $record->admin_comment) }}</textarea>
                            </div>
                            <div class="col-12">
                                <label for="history_comment" class="form-label">Motif de la modification (historique)</label>
                                <input type="text" name="history_comment" id="history_comment" class="form-control" placeholder="Ex : Correction manuelle suite vérification">
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-warning text-dark" onclick="return confirm('Confirmer l\\'ajustement manuel de cette fiche ?');">
                                <i class="fas fa-save me-1"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($eval['pre_eligible'])
                <div class="card card-dark">
                    <div class="card-header py-3">
                        <i class="fas fa-gavel me-2"></i>Décision finale
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <form method="POST" action="{{ route('admin.certification-eligibility.review', $student) }}">
                                    @csrf
                                    <div class="mb-2">
                                        <textarea name="admin_comment" class="form-control" rows="2" placeholder="Commentaire (optionnel)"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-review w-100">
                                        <i class="fas fa-clock me-1"></i> Mettre en attente
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <form method="POST" action="{{ route('admin.certification-eligibility.confirm', $student) }}" onsubmit="return confirm('Confirmer définitivement l\\'éligibilité de cet étudiant ?');">
                                    @csrf
                                    <div class="mb-2">
                                        <textarea name="admin_comment" class="form-control" rows="2" placeholder="Commentaire (optionnel)"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-confirm w-100" {{ $eval['studio_creative_validated'] ? '' : 'disabled' }}>
                                        <i class="fas fa-check me-1"></i> Confirmer l'éligibilité
                                    </button>
                                    @if(! $eval['studio_creative_validated'])
                                        <div class="small text-warning mt-1"><i class="fas fa-info-circle me-1"></i> Studio Creative requis</div>
                                    @endif
                                </form>
                            </div>
                            <div class="col-md-4">
                                <form method="POST" action="{{ route('admin.certification-eligibility.reject', $student) }}">
                                    @csrf
                                    <div class="mb-2">
                                        <textarea name="admin_comment" class="form-control" rows="2" placeholder="Motif du refus" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-reject w-100">
                                        <i class="fas fa-times me-1"></i> Refuser
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    L'étudiant n'est pas encore pré-éligible. Les critères automatiques doivent tous être remplis avant validation.
                </div>
            @endif

            <div class="card card-dark mt-4">
                <div class="card-header py-3">
                    <i class="fas fa-certificate me-2"></i>Validation finale
                </div>
                <div class="card-body">
                    <p class="small text-muted">Ce bouton force en une seule action : Système = Pré-éligible, Studio = Confirmé, Admin = Éligible confirmé.</p>
                    <form method="POST" action="{{ route('admin.certification-eligibility.validate-all', $student) }}" onsubmit="return confirm('Confirmer définitivement l\\'éligibilité de cet étudiant ?');">
                        @csrf
                        <div class="mb-3">
                            <textarea name="admin_comment" class="form-control" rows="2" placeholder="Commentaire (optionnel)"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check-double me-1"></i> Tout valider
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
