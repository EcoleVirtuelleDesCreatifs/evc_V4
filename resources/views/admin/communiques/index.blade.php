@extends('layouts.admin')

@section('title', 'Gestion des Communiqués')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">

        <a href="{{ route('admin.communiques.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouveau Communiqué
        </a>
    </div>


    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Contenu</th>
                            <th>Cible</th>
                            <th style="width: 150px;">Période</th>
                            <th style="width: 80px;">Vues</th>
                            <th style="width: 80px;">Ordre</th>
                            <th style="width: 100px;">Statut</th>
                            <th style="width: 150px;" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($communiques as $communique)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $communique->content }}</td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ \App\Models\Communique::TARGETS[$communique->target_audience] ?? 'Toutes les classes' }}
                                </span>
                            </td>
                            <td>
                                @if($communique->start_at || $communique->end_at)
                                    <small class="d-block text-muted">
                                        <i class="fas fa-play text-success me-1"></i>
                                        {{ $communique->start_at ? $communique->start_at->format('d/m/Y H:i') : 'Immédiat' }}
                                    </small>
                                    <small class="d-block text-muted">
                                        <i class="fas fa-stop text-danger me-1"></i>
                                        {{ $communique->end_at ? $communique->end_at->format('d/m/Y H:i') : 'Indéfini' }}
                                    </small>
                                @else
                                    <span class="badge bg-light text-dark border">Toujours visible</span>
                                @endif
                            </td>
                            <td class="text-center fw-bold text-primary">
                                {{ number_format($communique->view_count) }}
                            </td>
                            <td>{{ $communique->order }}</td>
                            <td>
                                <form action="{{ route('admin.communiques.toggle-status', $communique) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $communique->is_active ? 'btn-success' : 'btn-secondary' }} rounded-pill px-3">
                                        {{ $communique->is_active ? 'Actif' : 'Inactif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.communiques.edit', $communique) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('admin.communiques.destroy', $communique) }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-bullhorn fa-2x mb-2 opacity-25"></i>
                                <p class="mb-0">Aucun communiqué pour le moment.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <h4 class="mb-3">Êtes-vous sûr ?</h4>
                <p class="text-muted mb-0">Voulez-vous vraiment supprimer ce communiqué ? Cette action est irréversible.</p>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-light px-4" id="cancelDeleteBtn">Annuler</button>
                <form id="deleteForm" action="" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">Oui, supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let deleteModalInstance = null;

    function confirmDelete(url) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = url;

        const modalEl = document.getElementById('deleteModal');

        // Déplacer le modal dans le body pour éviter les problèmes de z-index/superposition
        if (modalEl.parentElement !== document.body) {
            document.body.appendChild(modalEl);
        }

        deleteModalInstance = new bootstrap.Modal(modalEl, {
            backdrop: true,
            keyboard: true
        });
        deleteModalInstance.show();
    }

    // Gérer la fermeture manuelle
    document.getElementById('cancelDeleteBtn').addEventListener('click', function() {
        if (deleteModalInstance) {
            deleteModalInstance.hide();
        }
    });

    // Gérer la fermeture par la croix
    const closeButtons = document.querySelectorAll('#deleteModal .btn-close');
    closeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            if (deleteModalInstance) {
                deleteModalInstance.hide();
            }
        });
    });
</script>
@endpush
@endsection
