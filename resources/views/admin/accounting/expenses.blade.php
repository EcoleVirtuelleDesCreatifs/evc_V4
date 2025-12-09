@extends('layouts.admin')

@section('title', 'Gestion des Dépenses')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Gestion des Dépenses</h1>
        <div>
            <a href="{{ route('admin.accounting.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            <a href="{{ route('admin.accounting.export', ['type' => 'expense']) }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-file-export me-2"></i>Exporter
            </a>
            <a href="{{ route('admin.accounting.expenses.create') }}" class="btn btn-danger">
                <i class="fas fa-plus me-2"></i>Ajouter une dépense
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Description</th>
                        <th class="text-end">Montant</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    <tr>
                        <td>{{ $expense->date->format('d/m/Y') }}</td>
                        <td class="fw-medium">{{ $expense->title }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $expense->category }}</span></td>
                        <td class="text-muted small">{{ Str::limit($expense->description, 50) }}</td>
                        <td class="text-end fw-bold text-danger">
                            - {{ number_format($expense->amount, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="text-center">
                            @if($expense->proof_path)
                                <a href="{{ Storage::url($expense->proof_path) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1" title="Voir le justificatif">
                                    <i class="fas fa-file-alt"></i>
                                </a>
                            @endif
                            <form action="{{ route('admin.accounting.destroy', $expense->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette dépense ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <div class="mb-3"><i class="fas fa-file-invoice fa-3x opacity-25"></i></div>
                            Aucune dépense enregistrée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer py-3">
            {{ $expenses->links() }}
        </div>
    </div>
</div>
@endsection
