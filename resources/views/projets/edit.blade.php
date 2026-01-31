@extends('layouts.ki-admin')

@section('title', 'Modifier le Projet - ' . $project['title'])
@section('page-title', 'Modifier le Projet')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Modifier le Projet</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('design-graphique.projets.index') }}">Projets</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('design-graphique.projets.show', $project['id']) }}">{{ $project['title'] }}</a>
                            </li>
                            <li class="breadcrumb-item active">Modifier</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('design-graphique.projets.show', $project['id']) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Informations du Projet
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('design-graphique.projets.update', $project['id']) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Titre du projet -->
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Titre du projet <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title"
                                       value="{{ old('title', $project['title']) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Type de projet -->
                            <div class="col-md-6 mb-3">
                                <label for="project_type" class="form-label">Type de projet <span class="text-danger">*</span></label>
                                <select class="form-select @error('project_type') is-invalid @enderror"
                                        id="project_type" name="project_type" required>
                                    <option value="">Choisir un type</option>
                                    @foreach($formOptions['project_types'] as $type)
                                        <option value="{{ $type }}"
                                                {{ old('project_type', $project['project_type']) === $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Catégorie de projet -->
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Catégorie de projet <span class="text-danger">*</span></label>
                                <select class="form-select @error('category') is-invalid @enderror"
                                        id="category" name="category" required>
                                    <option value="">Choisir une catégorie</option>
                                    @foreach($formOptions['project_modes'] as $mode)
                                        <option value="{{ $mode }}"
                                                {{ old('category', $project['category'] ?? '') === $mode ? 'selected' : '' }}>
                                            {{ $mode === 'solo' ? 'PROJET Solo' : 'PROJET Groupe' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Statut (lecture seule) -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Statut actuel</label>
                                <p class="form-control-plaintext">
                                    @php
                                        $statusLabels = [
                                            'draft' => 'Brouillon',
                                            'active' => 'En cours',
                                            'pending' => 'En cours de validation',
                                            'completed' => 'Terminé',
                                            'validated' => 'Validé',
                                            'rejected' => 'Rejeté',
                                            'cancelled' => 'Annulé'
                                        ];
                                        $statusColors = [
                                            'draft' => 'bg-secondary',
                                            'active' => 'bg-warning',
                                            'pending' => 'bg-warning',
                                            'completed' => 'bg-info',
                                            'validated' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                            'cancelled' => 'bg-danger'
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusColors[$project['status']] ?? 'bg-secondary' }} fs-6 px-3 py-2">
                                        {{ $statusLabels[$project['status']] ?? ucfirst($project['status']) }}
                                    </span>
                                </p>
                                <small class="text-muted">Le statut ne peut pas être modifié depuis ce formulaire.</small>
                            </div>

                            <!-- Description -->
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description du projet</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="4"
                                          placeholder="Décrivez votre projet...">{{ old('description', $project['description']) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Logiciels utilisés -->
                            <div class="col-12 mb-3">
                                <label class="form-label">Logiciels utilisés</label>
                                <div class="row">
                                    @php
                                        $currentSoftware = isset($project['software_used_array']) ? $project['software_used_array'] : [];
                                        $oldSoftware = old('software_used', $currentSoftware);
                                    @endphp
                                    @foreach($formOptions['software_options'] as $software)
                                        <div class="col-md-4 col-sm-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       name="software_used[]" value="{{ $software }}"
                                                       id="software_{{ $software }}"
                                                       {{ in_array($software, $oldSoftware) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="software_{{ $software }}">
                                                    {{ ucfirst(str_replace('_', ' ', $software)) }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('software_used')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>



                            <!-- Nouveaux fichiers -->
                            <div class="col-12 mb-3">
                                <label for="files" class="form-label">Ajouter de nouveaux fichiers</label>
                                <input type="file" class="form-control @error('files.*') is-invalid @enderror"
                                       id="files" name="files[]" multiple accept="image/*,.pdf,.doc,.docx">
                                <div class="form-text">
                                    Formats acceptés: Images (JPG, PNG, GIF), PDF, DOC, DOCX. Taille max: 10MB par fichier.
                                </div>
                                @error('files.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fichiers existants -->
                            @if(isset($project['files']) && !empty($project['files']))
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-images me-2"></i>
                                    Fichiers actuels ({{ count($project['files']) }})
                                </label>
                                <div class="row">
                                    @foreach($project['files'] as $file)
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <div class="card h-100 shadow-sm">
                                            @php
                                                $filePath = $file['file_path'] ?? ($file['path'] ?? '');
                                                $fileName = $file['original_name'] ?? ($file['name'] ?? 'Fichier');
                                                $fileId = $file['id'] ?? 0;
                                                $extension = $filePath ? strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) : '';
                                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                                                $fileUrl = $filePath ? \App\Models\MediaUrl::fromPath($filePath) : '';
                                            @endphp

                                            @if($isImage && $filePath)
                                                <img src="{{ $fileUrl }}"
                                                     class="card-img-top"
                                                     alt="{{ $fileName }}"
                                                     style="height: 200px; object-fit: cover;"
                                                     onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'200\' height=\'200\'%3E%3Crect fill=\'%23f0f0f0\' width=\'200\' height=\'200\'/%3E%3Ctext fill=\'%23999\' font-family=\'sans-serif\' font-size=\'14\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\'%3EImage non disponible%3C/text%3E%3C/svg%3E';">
                                            @else
                                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                                     style="height: 200px;">
                                                    <i class="fas fa-file fa-4x text-muted"></i>
                                                </div>
                                            @endif

                                            <div class="card-body p-2">
                                                <h6 class="card-title small mb-1 text-truncate" title="{{ $fileName }}">
                                                    {{ Str::limit($fileName, 25) }}
                                                </h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        {{ $extension ? strtoupper($extension) : 'FILE' }}
                                                    </small>
                                                    @if($fileId)
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-danger"
                                                            onclick="removeFile({{ $fileId }}, '{{ addslashes($fileName) }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('design-graphique.projets.show', $project['id']) }}"
                               class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check"></i> Mettre à jour le projet
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fonction pour supprimer un fichier
    function removeFile(fileId, fileName) {
        if (confirm(`Êtes-vous sûr de vouloir supprimer le fichier "${fileName}" ?`)) {
            fetch(`/evc/compte/design-graphique/projets/{{ $project['id'] }}/files/${fileId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Supprimer l'élément du DOM
                    const fileElement = document.querySelector(`button[onclick*="${fileId}"]`).closest('.col-md-6');
                    if (fileElement) {
                        fileElement.remove();
                    }

                    // Afficher un message de succès
                    showAlert(data.message, 'success');
                } else {
                    showAlert(data.error || 'Erreur lors de la suppression du fichier.', 'danger');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showAlert('Erreur lors de la suppression du fichier.', 'danger');
            });
        }
    }

    // Fonction pour afficher les alertes
    function showAlert(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        const container = document.querySelector('.container-fluid');
        container.insertBefore(alertDiv, container.firstChild);

        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    // Validation côté client pour les fichiers
    document.getElementById('files').addEventListener('change', function(e) {
        const files = e.target.files;
        const maxSize = 10 * 1024 * 1024; // 10MB

        for (let file of files) {
            if (file.size > maxSize) {
                alert(`Le fichier "${file.name}" est trop volumineux. Taille maximum: 10MB`);
                e.target.value = '';
                break;
            }
        }
    });
</script>
@endsection
