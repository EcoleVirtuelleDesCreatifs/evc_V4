@extends('layouts.admin')

@section('title', 'Étudiants en Design Graphique')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="text-white">Étudiants - Design Graphique</h1>
        {{-- <a href="#" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Ajouter un étudiant</a> --}}
    </div>

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom & Prénom</th>
                            <th>Pays</th>
                            <th>Inscription</th>
                            <th>TP Réalisés</th>
                            <th>Progression</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($student->profile_photo)
                                            <img src="{{ asset('storage/' . $student->profile_photo) }}" 
                                                 alt="{{ $student->name }}" 
                                                 class="rounded-circle me-2"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 40px; height: 40px; font-weight: 600;">
                                                {{ strtoupper(substr($student->first_name ?? $student->name, 0, 1)) }}{{ strtoupper(substr($student->last_name ?? '', 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <strong>{{ $student->first_name ?? $student->name }} {{ $student->last_name ?? '' }}</strong>
                                            @if($student->status == 'active')
                                                <span class="badge bg-success ms-2" style="font-size: 0.7rem;">Actif</span>
                                            @endif
                                            <br>
                                            <small class="text-muted">ID: {{ $student->student_id ?? $student->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $student->country ?? 'N/A' }}</td>
                                <td>{{ $student->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $student->tp_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: {{ $student->progress ?? 5 }}%;" 
                                             aria-valuenow="{{ $student->progress ?? 5 }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100"></div>
                                    </div>
                                    <small>{{ $student->progress ?? 5 }}%</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="#" class="btn btn-sm btn-info" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-secondary" title="Historique">
                                            <i class="fas fa-history"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-danger" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Aucun étudiant trouvé pour cette formation.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
