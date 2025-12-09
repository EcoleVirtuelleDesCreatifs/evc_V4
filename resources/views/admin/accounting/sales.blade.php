@extends('layouts.admin')

@section('title', 'Gestion des Ventes')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <a href="{{ route('admin.accounting.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            <a href="{{ route('admin.accounting.export', ['type' => 'income']) }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-file-export me-2"></i>Exporter
            </a>
            <a href="{{ route('admin.accounting.sales.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Ajouter une vente
            </a>
        </div>
    </div>



    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Référence</th>
                        <th class="text-end">Montant</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <td>{{ $sale->date->format('d/m/Y') }}</td>
                        <td class="fw-medium">
                            {{ $sale->title }}
                            @if($sale->student_name)
                                <div class="small text-muted mt-1"><i class="fas fa-user-graduate me-1"></i>{{ $sale->student_name }}</div>
                            @endif
                            @if($sale->training_module)
                                <div class="small text-muted"><i class="fas fa-book me-1"></i>{{ $sale->training_module }}</div>
                            @endif
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $sale->category }}</span></td>
                        <td class="text-muted small">{{ $sale->reference ?? '-' }}</td>
                        <td class="text-end fw-bold text-success">
                            + {{ number_format($sale->amount, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="text-center">
                            @if($sale->proof_path)
                                <a href="{{ Storage::url($sale->proof_path) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1" title="Voir le justificatif">
                                    <i class="fas fa-file-alt"></i>
                                </a>
                            @endif
                            <form action="{{ route('admin.accounting.destroy', $sale->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette vente ?');">
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
                            <div class="mb-3"><i class="fas fa-cash-register fa-3x opacity-25"></i></div>
                            Aucune vente enregistrée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white py-3">
            {{ $sales->links() }}
        </div>
    </div>
</div>
@endsection
