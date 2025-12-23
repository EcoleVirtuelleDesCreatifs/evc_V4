@extends('layouts.admin')

@section('title', 'Supprimer le Projet')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-trash-alt text-danger me-2"></i>
                        Supprimer le Projet
                    </h4>
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>
                        Retour
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Informations du projet -->
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="fas fa-project-diagram me-2"></i>
                                        Détails du Projet à Supprimer
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <strong>Titre :</strong>
                                        </div>
                                        <div class="col-sm-9">
                                            {{ $project->title }}
                                        </div>
                                    </div>

                                    @if($project->description)
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <strong>Description :</strong>
                                        </div>
                                        <div class="col-sm-9">
                                            {{ $project->description }}
                                        </div>
                                    </div>
                                    @endif

                                    @if($project->software_used)
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <strong>Logiciels utilisés :</strong>
                                        </div>
                                        <div class="col-sm-9">
                                            {{ $project->software_used }}
                                        </div>
                                    </div>
                                    @endif

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <strong>Statut :</strong>
                                        </div>
                                        <div class="col-sm-9">
                                            @switch($project->status)
                                                @case('en_cours')
                                                    <span class="badge bg-warning">En cours</span>
                                                    @break
                                                @case('termine')
                                                    <span class="badge bg-info">Terminé</span>
                                                    @break
                                                @case('valide')
                                                    <span class="badge bg-success">Validé</span>
                                                    @break
                                                @case('rejete')
                                                    <span class="badge bg-danger">Rejeté</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $project->status }}</span>
                                            @endswitch
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <strong>Date de création :</strong>
                                        </div>
                                        <div class="col-sm-9">
                                            {{ $project->created_at->format('d/m/Y à H:i') }}
                                        </div>
                                    </div>

                                    @if($project->images && $project->images->count() > 0)
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <strong>Fichiers associés :</strong>
                                        </div>
                                        <div class="col-sm-9">
                                            <span class="badge bg-info">{{ $project->images->count() }} fichier(s)</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Formulaire de suppression -->
                            <div class="card border-danger shadow-sm mt-4">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Confirmation de Suppression
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Attention !</strong> Cette action est irréversible.
                                        Le projet et tous ses fichiers associés seront définitivement supprimés.
                                    </div>

                                    @if($project->images && $project->images->count() > 0)
                                    <div class="alert alert-warning">
                                        <i class="fas fa-file-image me-2"></i>
                                        <strong>{{ $project->images->count() }} fichier(s) sera(ont) également supprimé(s) :</strong>
                                        <ul class="mb-0 mt-2">
                                            @foreach($project->images as $image)
                                                <li>{{ $image->original_name ?? $image->filename }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif

                                    <form action="{{ route('admin.projects.delete', $project->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                                            <label class="form-check-label" for="confirmDelete">
                                                Je confirme vouloir supprimer définitivement ce projet
                                            </label>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-danger" id="deleteBtn" disabled>
                                                <i class="fas fa-trash-alt me-1"></i>
                                                Supprimer Définitivement
                                            </button>
                                            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                                <i class="fas fa-times me-1"></i>
                                                Annuler
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar - Informations étudiant -->
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user me-2"></i>
                                        Informations Étudiant
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        @php
                                            $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($project->user->profile_photo ?? null);
                                        @endphp
                                        @if($photoUrl)
                                            <img src="{{ $photoUrl }}"
                                                 alt="Photo de profil"
                                                 class="rounded-circle mb-2"
                                                 style="width: 80px; height: 80px; object-fit: cover;">
                                        @else
                                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                                 style="width: 80px; height: 80px;">
                                                <i class="fas fa-user text-white fa-2x"></i>
                                            </div>
                                        @endif
                                        <h6 class="mb-1">{{ $project->user->first_name }} {{ $project->user->last_name }}</h6>
                                        <small class="text-muted">{{ $project->user->email }}</small>
                                    </div>

                                    @if($project->user->phone)
                                    <div class="mb-2">
                                        <strong>Téléphone :</strong><br>
                                        <small>{{ $project->user->phone }}</small>
                                    </div>
                                    @endif

                                    @if($project->user->city || $project->user->country)
                                    <div class="mb-2">
                                        <strong>Localisation :</strong><br>
                                        <small>
                                            @if($project->user->city){{ $project->user->city }}@endif
                                            @if($project->user->city && $project->user->country), @endif
                                            @if($project->user->country){{ $project->user->country }}@endif
                                        </small>
                                    </div>
                                    @endif

                                    @if($project->user->formation_souhaitee)
                                    <div class="mb-2">
                                        <strong>Formation :</strong><br>
                                        <small>{{ $project->user->formation_souhaitee }}</small>
                                    </div>
                                    @endif

                                    <div class="mt-3">
                                        <a href="{{ route('admin.students.profile', $project->user->id) }}"
                                           class="btn btn-outline-primary btn-sm w-100">
                                            <i class="fas fa-eye me-1"></i>
                                            Voir le Profil Complet
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const confirmCheckbox = document.getElementById('confirmDelete');
    const deleteBtn = document.getElementById('deleteBtn');

    confirmCheckbox.addEventListener('change', function() {
        deleteBtn.disabled = !this.checked;
    });
});
</script>
@endsection
