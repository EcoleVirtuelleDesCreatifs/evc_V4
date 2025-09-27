@extends('layouts.ki-admin')

@section('title', 'Liste des Étudiants - EVC 2024')
@section('page-title', 'Liste des Étudiants')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>
                    Gestion des Étudiants
                </h5>
                <a href="{{ route('students.create') }}" class="btn btn-light">
                    <i class="fas fa-plus me-2"></i>
                    Nouvel Étudiant
                </a>
            </div>
            <div class="card-body">
                @if($students->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Photo</th>
                                    <th>Nom Complet</th>
                                    <th>ID Étudiant</th>
                                    <th>Email</th>
                                    <th>Programme</th>
                                    <th>Niveau</th>
                                    <th>Statut</th>
                                    <th>GPA</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                <tr>
                                    <td>
                                        <img src="{{ $student->profile_photo_url }}" 
                                             alt="{{ $student->full_name }}" 
                                             class="rounded-circle" 
                                             width="50" height="50"
                                             style="object-fit: cover;">
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <strong>{{ $student->full_name }}</strong>
                                            <small class="text-muted">{{ $student->phone }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: var(--primary-color); color: white;">{{ $student->student_id }}</span>
                                    </td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ $student->program ?? 'Non défini' }}</td>
                                    <td>
                                        @if($student->level)
                                            <span class="badge" style="background-color: var(--secondary-color); color: white;">{{ $student->level }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $student->status }}">
                                            {{ ucfirst($student->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($student->gpa)
                                            <strong class="text-success">{{ number_format($student->gpa, 2) }}</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('students.show', $student) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Voir le profil">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('students.edit', $student) }}" 
                                               class="btn btn-sm btn-outline-warning" 
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('students.destroy', $student) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $students->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucun étudiant trouvé</h5>
                        <p class="text-muted">Commencez par ajouter votre premier étudiant.</p>
                        <a href="{{ route('students.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Ajouter un Étudiant
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-users fa-2x mb-3" style="color: var(--primary-color);"></i>
                <h4 style="color: var(--primary-color);">{{ $students->total() }}</h4>
                <p class="text-muted mb-0">Total Étudiants</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-user-check fa-2x mb-3" style="color: var(--success-color);"></i>
                <h4 style="color: var(--success-color);">{{ $students->where('status', 'active')->count() }}</h4>
                <p class="text-muted mb-0">Actifs</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-graduation-cap fa-2x mb-3" style="color: var(--warning-color);"></i>
                <h4 style="color: var(--warning-color);">{{ $students->where('status', 'graduated')->count() }}</h4>
                <p class="text-muted mb-0">Diplômés</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-chart-line fa-2x mb-3" style="color: var(--accent-color);"></i>
                <h4 style="color: var(--accent-color);">{{ number_format($students->whereNotNull('gpa')->avg('gpa'), 2) }}</h4>
                <p class="text-muted mb-0">GPA Moyen</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .table th {
        border-top: none;
        font-weight: 600;
        color: #6c757d;
    }
    
    .btn-group .btn {
        margin-right: 2px;
    }
    
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
    }
</style>
@endsection
