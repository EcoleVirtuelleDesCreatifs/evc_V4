@extends('layouts.ki-admin')

@section('title', 'Historique des Projets')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h2 class="fw-bold text-white mb-1">
                        <i class="fas fa-history me-2"></i>
                        Historiques Projet
                    </h2>
                    <p class="text-white-50 mb-0">Tous vos projets : en cours de validation, validés et rejetés</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('design-graphique.projets.index') }}" class="btn btn-outline-light" style="border-radius: 12px; font-weight: 800;">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour
                    </a>
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
                        Tableau des projets
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
                                                    if ($path && is_string($mime) && str_starts_with($mime, 'image/')) {
                                                        $thumbnailUrl = asset('storage/' . ltrim($path, '/'));
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
                                                    <div class="text-muted small">{{ \Illuminate\Support\Str::limit($project['description'], 80) }}</div>
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
                                                <a href="{{ route('design-graphique.projets.show', $project['id']) }}" class="btn btn-sm btn-success" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun projet dans l'historique</h5>
                            <p class="text-muted mb-0">Les projets apparaîtront ici une fois soumis (validation) ou traités.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
