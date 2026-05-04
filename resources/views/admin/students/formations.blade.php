@extends('layouts.admin')

@section('title', 'Formations de l\'étudiant')

@section('content')
<div class="container-fluid">
    <!-- Header avec breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.statistics.detail', 'total-students') }}">Étudiants</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.students.profile', $student->id ?? $user->id) }}">Profil Étudiant</a></li>
                    <li class="breadcrumb-item active">Formations</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-primary">
                    <i class="fas fa-graduation-cap me-2"></i>Formations de {{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }}
                </h1>
                <a href="{{ route('admin.students.profile', $student->id ?? $user->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Retour au profil
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center p-3 rounded" style="background: rgba(79, 195, 247, 0.1);">
                                <h3 class="mb-0" style="color: #4fc3f7;">{{ $student_programs->count() }}</h3>
                                <small class="text-muted">Total Formations</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 rounded" style="background: rgba(79, 195, 247, 0.1);">
                                <h3 class="mb-0" style="color: #4fc3f7;">{{ $formations_by_category->count() }}</h3>
                                <small class="text-muted">Catégories</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formations par catégorie -->
    @if($formations_by_category->count() > 0)
        @foreach($formations_by_category as $categoryName => $categoryFormations)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-folder me-2"></i>{{ $categoryName ?? 'Sans catégorie' }} ({{ $categoryFormations->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Description</th>
                                        <th>Niveau</th>
                                        <th>Durée</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categoryFormations as $formation)
                                    <tr>
                                        <td>{{ $formation->name ?? 'Sans nom' }}</td>
                                        <td>{{ Str::limit($formation->description ?? '-', 100) }}</td>
                                        <td><span class="badge bg-info">{{ $formation->level ?? 'N/A' }}</span></td>
                                        <td>{{ $formation->duration_weeks ?? 'N/A' }} sem</td>
                                        <td>
                                            <span class="badge {{ $formation->status === 'active' ? 'bg-success' : 'bg-warning' }}">
                                                {{ $formation->status ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('formations.show', $formation->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Voir détails
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Aucune formation disponible pour cet étudiant.
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
