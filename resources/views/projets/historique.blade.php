@extends('layouts.ki-admin')

@section('title', 'Historique des Projets')

@section('content')
<div class="container-fluid">
    @php
        $projectsCollection = collect(is_array($projects ?? null) ? $projects : []);
        $pendingCount = $projectsCollection->where('status', 'pending')->count();
        $validatedCount = $projectsCollection->where('status', 'validated')->count();
        $rejectedCount = $projectsCollection->where('status', 'rejected')->count();

        $formationPrefix = (string) ($userFormation ?? 'design-graphique');
        $projectsIndexRouteName = $formationPrefix . '.projets.index';
        $projectsShowRouteName = $formationPrefix . '.projets.show';
        $projectsEditRouteName = $formationPrefix . '.projets.edit';
        $projectsDestroyRouteName = $formationPrefix . '.projets.destroy';
        $todoTraiterRouteName = $formationPrefix . '.todo.traiter';
        $todoRetirerRouteName = $formationPrefix . '.todo.retirer';

        $formationKey = strtolower($formationPrefix);
        $isCmFormation = str_ends_with($formationKey, '-cm') || in_array($formationKey, ['community-management', 'community-manager'], true);
    @endphp

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h2 class="fw-bold text-white mb-1">
                        <i class="fas fa-history me-2"></i>
                        Historiques Projet
                    </h2>
                    <p class="text-white-50 mb-0">Tous vos TP/Projets : en cours de validation, validés et rejetés</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ \Illuminate\Support\Facades\Route::has($projectsIndexRouteName) ? route($projectsIndexRouteName) : route('design-graphique.projets.index') }}" class="btn btn-outline-light" style="border-radius: 12px; font-weight: 800;">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card" style="background: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%); color: white;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div style="font-weight: 900; font-size: 2rem; line-height: 1;">{{ $pendingCount }}</div>
                            <div style="opacity: 0.95; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; font-size: 0.75rem;">En cours de validation</div>
                        </div>
                        <div style="font-size: 2.2rem; opacity: 0.35;">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div style="font-weight: 900; font-size: 2rem; line-height: 1;">{{ $validatedCount }}</div>
                            <div style="opacity: 0.95; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; font-size: 0.75rem;">Validés</div>
                        </div>
                        <div style="font-size: 2.2rem; opacity: 0.35;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div style="font-weight: 900; font-size: 2rem; line-height: 1;">{{ $rejectedCount }}</div>
                            <div style="opacity: 0.95; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; font-size: 0.75rem;">Rejetés</div>
                        </div>
                        <div style="font-size: 2.2rem; opacity: 0.35;">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0" style="font-weight: 900;">
                        <i class="fas fa-list me-2"></i>
                        Tableau des TP/Projets
                        <span class="badge bg-light text-dark ms-2">{{ is_array($projects ?? null) ? count($projects) : 0 }}</span>
                    </h5>
                </div>

                <div class="card-body">
                    @if(!empty($projects) && is_array($projects) && count($projects) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 90px;">Image</th>
                                        <th style="min-width: 220px;">Titre</th>
                                        <th>Catégorie</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projects as $project)
                                        @php
                                            $status = $project['status'] ?? '';
                                            $statusMap = [
                                                'pending' => ['label' => 'En cours de validation', 'class' => 'bg-warning'],
                                                'validated' => ['label' => 'Validé', 'class' => 'bg-success'],
                                                'rejected' => ['label' => 'Rejeté', 'class' => 'bg-danger'],
                                            ];
                                            $statusCfg = $statusMap[$status] ?? ['label' => ucfirst((string) $status), 'class' => 'bg-secondary'];

                                            $category = $project['category'] ?? '';
                                            $categoryMap = [
                                                'tp' => ['label' => 'TP', 'class' => 'bg-primary', 'icon' => 'fa-file-alt'],
                                                'projet' => ['label' => 'Projet', 'class' => 'bg-info', 'icon' => 'fa-folder'],
                                                'solo' => ['label' => 'Solo', 'class' => 'bg-primary', 'icon' => 'fa-user'],
                                                'groupe' => ['label' => 'Groupe', 'class' => 'bg-info', 'icon' => 'fa-users'],
                                            ];
                                            $categoryCfg = $categoryMap[$category] ?? ['label' => $category ? ucfirst((string) $category) : '-', 'class' => 'bg-secondary', 'icon' => 'fa-tag'];

                                            $createdAt = $project['created_at'] ?? null;

                                            $thumbnailUrl = null;
                                            $files = $project['files'] ?? [];
                                            if (is_array($files) && !empty($files)) {
                                                foreach ($files as $file) {
                                                    $mime = $file['mime_type'] ?? '';
                                                    $path = $file['path'] ?? null;
                                                    if (!$path) {
                                                        continue;
                                                    }

                                                    $isImage = false;

                                                    if (is_string($mime) && stripos($mime, 'image/') === 0) {
                                                        $isImage = true;
                                                    } else {
                                                        $nameForExt = $file['name'] ?? $path;
                                                        $ext = strtolower(pathinfo($nameForExt, PATHINFO_EXTENSION));
                                                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'], true);
                                                    }

                                                    if ($isImage) {
                                                        $thumbnailUrl = \App\Models\MediaUrl::fromPath($path);
                                                        break;
                                                    }
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                @if($thumbnailUrl)
                                                    <img src="{{ $thumbnailUrl }}" alt="Aperçu" style="width: 64px; height: 64px; object-fit: cover; border-radius: 10px; border: 2px solid rgba(255,255,255,0.15);" loading="lazy" onerror="this.style.display='none'">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center" style="width: 64px; height: 64px; border-radius: 10px; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);">
                                                        <i class="fas fa-image" style="opacity: 0.6;"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div style="font-weight: 900;">{{ $project['title'] ?? 'Sans titre' }}</div>
                                                @if(!empty($project['description']))
                                                    <div class="text-muted small">{{ \Illuminate\Support\Str::limit(strip_tags((string) $project['description']), 80) }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $categoryCfg['class'] }}">
                                                    <i class="fas {{ $categoryCfg['icon'] }} me-1"></i>
                                                    {{ $categoryCfg['label'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ $project['project_type'] ?? '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $statusCfg['class'] }}">
                                                    {{ $statusCfg['label'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="small text-muted">
                                                    {{ $createdAt ? date('d/m/Y', strtotime($createdAt)) : '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $itemId = (int) ($project['id'] ?? 0);
                                                    $itemCategory = (string) ($project['category'] ?? '');
                                                    $isTpItem = ($itemCategory === 'tp');
                                                    $isAssignedProject = (($project['source'] ?? '') === 'assigned_project');
                                                    $itemStatus = (string) ($project['status'] ?? '');
                                                    $isValidated = ($itemStatus === 'validated');
                                                    $canShow = $itemId > 0 && \Illuminate\Support\Facades\Route::has($todoTraiterRouteName);
                                                    $canEdit = !$isCmFormation && !$isTpItem && !$isAssignedProject && !$isValidated && $itemId > 0 && \Illuminate\Support\Facades\Route::has($projectsEditRouteName);
                                                    $canDelete = !$isCmFormation && !$isTpItem && !$isAssignedProject && !$isValidated && $itemId > 0 && \Illuminate\Support\Facades\Route::has($projectsDestroyRouteName);
                                                    $canEditAssigned = ($isTpItem || $isCmFormation || $isAssignedProject) && !$isValidated && $itemId > 0 && \Illuminate\Support\Facades\Route::has($todoTraiterRouteName);
                                                    $canWithdrawAssigned = ($isTpItem || $isCmFormation || $isAssignedProject) && !$isValidated && $itemId > 0 && \Illuminate\Support\Facades\Route::has($todoRetirerRouteName);
                                                @endphp

                                                <div class="d-flex flex-wrap gap-2">
                                                    @if($canShow)
                                                        <a href="{{ url('/evc/compte/design-graphique/todo/traiter/' . $itemId) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 10px; font-weight: 800;">
                                                            <i class="fas fa-eye me-1"></i>
                                                            Voir
                                                        </a>
                                                    @endif

                                                    @if($canEditAssigned)
                                                        <a href="{{ route($todoTraiterRouteName, ['projectId' => $itemId]) }}" class="btn btn-sm btn-outline-warning" style="border-radius: 10px; font-weight: 800;">
                                                            <i class="fas fa-edit me-1"></i>
                                                            Éditer
                                                        </a>
                                                    @endif

                                                    @if($canWithdrawAssigned)
                                                        <form action="{{ route($todoRetirerRouteName, ['projectId' => $itemId]) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir retirer cette soumission ?');" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 10px; font-weight: 800;">
                                                                <i class="fas fa-undo me-1"></i>
                                                                Retirer
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if($canEdit)
                                                        <a href="{{ route($projectsEditRouteName, ['id' => $itemId]) }}" class="btn btn-sm btn-outline-warning" style="border-radius: 10px; font-weight: 800;">
                                                            <i class="fas fa-edit me-1"></i>
                                                            Modifier
                                                        </a>
                                                    @endif

                                                    @if($canDelete)
                                                        <form action="{{ route($projectsDestroyRouteName, ['id' => $itemId]) }}" method="POST" onsubmit="return confirmDeleteProject();" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 10px; font-weight: 800;">
                                                                <i class="fas fa-trash me-1"></i>
                                                                Supprimer
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if(!$canShow && !$canEdit && !$canDelete && !$canEditAssigned && !$canWithdrawAssigned)
                                                        <div class="text-muted">-</div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun TP/Projet dans l'historique</h5>
                            <p class="text-muted mb-0">Les projets apparaîtront ici une fois soumis (validation) ou traités.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDeleteProject() {
    return confirm('Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.');
}
</script>
@endpush
