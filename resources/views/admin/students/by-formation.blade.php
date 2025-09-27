@extends('layouts.admin')

@section('title', 'Étudiants - ' . $data['formation_name'])

@section('content')
<div class="container-fluid px-4">
    <!-- Header avec breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.statistics.detail', 'total-students') }}">Étudiants</a></li>
                    <li class="breadcrumb-item active">{{ $data['formation_name'] }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-primary">
                <i class="fas fa-graduation-cap me-2"></i>Étudiants - {{ $data['formation_name'] }}
            </h1>
        </div>
    </div>

    <!-- Statistiques de la formation -->
    <div class="row mb-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-gradient-primary text-white">
                <div class="card-body text-center">
                    <div class="h2 fw-bold mb-1">{{ $data['stats']['total'] }}</div>
                    <small>Total Étudiants</small>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-gradient-success text-white">
                <div class="card-body text-center">
                    <div class="h2 fw-bold mb-1">{{ $data['stats']['active'] }}</div>
                    <small>Étudiants Actifs</small>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-gradient-info text-white">
                <div class="card-body text-center">
                    <div class="h2 fw-bold mb-1">{{ $data['stats']['avg_progression'] }}%</div>
                    <small>Progression Moyenne</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des étudiants -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users text-primary me-2"></i>Liste des Étudiants - {{ $data['formation_name'] }}
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                            <i class="fas fa-sync-alt me-1"></i>Actualiser
                        </button>
                        <button class="btn btn-success btn-sm" onclick="exportFormationStudents()">
                            <i class="fas fa-file-excel me-1"></i>Exporter
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="formationStudentsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="60">#</th>
                                    <th>Nom & Prénom</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Ville</th>
                                    <th>Inscription</th>
                                    <th>TP Réalisés</th>
                                    <th>Progression</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['students'] as $index => $student)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-2">
                                                {{ substr($student['prenom'] ?? 'E', 0, 1) }}{{ substr($student['nom'] ?? 'T', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $student['prenom'] ?? 'Prénom' }} {{ $student['nom'] ?? 'Nom' }}</div>
                                                <small class="text-muted">ID: {{ $student['id'] }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $student['email'] }}</td>
                                    <td>{{ $student['phone'] ?? '-' }}</td>
                                    <td>{{ $student['ville'] ?? '-' }}</td>
                                    <td>{{ date('d/m/Y', strtotime($student['created_at'])) }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ $student['tp_count'] ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-success" style="width: {{ $student['progression'] ?? 0 }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $student['progression'] ?? 0 }}%</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.students.profile', $student['id']) }}" class="btn btn-outline-primary" title="Voir profil">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.students.edit', $student['id']) }}" class="btn btn-outline-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-users fa-2x mb-2"></i>
                                            <p>Aucun étudiant trouvé pour cette formation</p>
                                            <a href="{{ route('admin.students.add') }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus me-1"></i>Ajouter un étudiant
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser DataTable
    if (document.getElementById('formationStudentsTable')) {
        $('#formationStudentsTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[0, 'asc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
            },
            columnDefs: [
                { orderable: false, targets: [8] }
            ]
        });
    }
});

function exportFormationStudents() {
    const formation = '{{ $data["formation"] }}';
    const url = `{{ route("admin.students.export-excel") }}?formation=${formation}`;
    window.open(url, '_blank');
}
</script>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 12px;
    font-weight: bold;
}

.progress {
    background-color: #e9ecef;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}
</style>
@endpush
@endsection
