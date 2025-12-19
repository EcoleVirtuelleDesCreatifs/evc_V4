@extends('layouts.admin')

@section('title', 'Étudiants en Design Graphique & Community Manager')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="text-white">Étudiants - Design Graphique & Community Manager</h1>
        {{-- <a href="#" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Ajouter un étudiant</a> --}}
    </div>

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Statut</th>
                            <th>Inscrit le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            <tr>
                                <td>{{ $student->id }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->phone ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-success">{{ $student->status ?? 'Actif' }}</span>
                                </td>
                                <td>{{ $student->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info">Voir</a>
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
