@extends('layouts.admin')

@section('title', 'Promos & réductions EVC Store')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-white">
            <i class="fas fa-tags me-2"></i>Codes promo & réductions étudiants
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.boutique.index') }}" class="btn btn-outline-light">
                <i class="fas fa-box me-2"></i>Produits
            </a>
            <a href="{{ route('admin.boutique.promos.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Ajouter
            </a>
        </div>
    </div>

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <h5 class="mb-0 text-white"><i class="fas fa-list me-2"></i>Liste</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Code promo</th>
                            <th>ID étudiant</th>
                            <th>Type</th>
                            <th>Valeur</th>
                            <th>Utilisations</th>
                            <th>Expiration</th>
                            <th>Actif</th>
                            <th style="width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($promos as $promo)
                            <tr>
                                <td>{{ $promo->code ?? '—' }}</td>
                                <td>{{ $promo->student_id ?? '—' }}</td>
                                <td>{{ $promo->type === 'percent' ? 'Pourcentage' : 'Montant fixe' }}</td>
                                <td>
                                    {{ $promo->value }}{{ $promo->type === 'percent' ? '%' : ' FCFA' }}
                                </td>
                                <td>{{ $promo->used_count }}{{ $promo->max_uses ? ' / '.$promo->max_uses : '' }}</td>
                                <td>{{ $promo->expires_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $promo->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $promo->is_active ? 'Oui' : 'Non' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.boutique.promos.edit', $promo) }}" class="btn btn-sm btn-warning">Modifier</a>
                                    <form action="{{ route('admin.boutique.promos.destroy', $promo) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette réduction ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Aucun code promo / réduction.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $promos->links() }}
    </div>
</div>
@endsection
