@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Messages Flash -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h4 mb-0">Pré-inscriptions</h1>
        <div class="d-flex align-items-center gap-2">
            <form method="GET" action="{{ route('admin.preinscriptions.index') }}" class="row g-2 align-items-center">
                <div class="col-auto">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Recherche (nom, prénom, email, whatsapp)" class="form-control" />
                </div>
                <div class="col-auto">
                    <select name="formation" class="form-select">
                        <option value="">Toutes formations</option>
                        <option value="design_graphique" @selected(request('formation')==='design_graphique')>Design Graphique</option>
                        <option value="community_management" @selected(request('formation')==='community_management')>Community Management</option>
                        <option value="design_graphique_community_manager" @selected(request('formation')==='design_graphique_community_manager')>Design Graphique & Community Manager</option>
                        <option value="gestion_informatique" @selected(request('formation')==='gestion_informatique')>Gestion Informatique</option>
                        <option value="intelligence_artificielle" @selected(request('formation')==='intelligence_artificielle')>Intelligence Artificielle</option>
                    </select>
                </div>
                <div class="col-auto">
                    <select name="status" class="form-select">
                        <option value="">Tous statuts</option>
                        <option value="pending" @selected(request('status')==='pending')>En attente</option>
                        <option value="accepted" @selected(request('status')==='accepted')>Accepté</option>
                        <option value="rejected" @selected(request('status')==='rejected')>Rejeté</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary"><i class="fas fa-filter me-2"></i>Filtrer</button>
                </div>
            </form>
            <a href="{{ route('admin.preinscriptions.export', request()->only(['q','formation','status'])) }}" class="btn btn-success"><i class="fas fa-file-export me-2"></i>Exporter CSV</a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.preinscriptions.bulk-status') }}" class="mb-3" id="bulkActionForm" onsubmit="return confirmBulkAction()">
        @csrf
        <div class="d-flex align-items-center gap-2">
            <select name="action" required class="form-select w-auto" id="bulkAction">
                <option value="">-- Sélectionner une action --</option>
                <option value="accepted">✅ Marquer comme Accepté</option>
                <option value="rejected">❌ Marquer comme Rejeté</option>
                <option value="pending">⏳ Remettre en attente</option>
                <option value="delete" style="color: #dc3545; font-weight: bold;">🗑️ Supprimer définitivement</option>
            </select>
            <button type="submit" class="btn btn-primary" id="bulkActionBtn">
                <i class="fas fa-check me-2"></i>Appliquer à <span id="selectedCount">0</span> élément(s)
            </button>
        </div>
        <input type="hidden" name="q" value="{{ request('q') }}">
        <input type="hidden" name="formation" value="{{ request('formation') }}">
        <input type="hidden" name="status" value="{{ request('status') }}">

        <div class="card mt-3 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-sm align-middle mb-0 text-body">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th class="text-center"><input type="checkbox" onclick="document.querySelectorAll('.row-check').forEach(cb=>cb.checked=this.checked)"></th>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>WhatsApp</th>
                                <th>Pays</th>
                                <th>Formation</th>
                                <th>Niveau</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                    @forelse($pres as $pre)
                        <tr>
                            <td class="text-center"><input type="checkbox" class="row-check" name="ids[]" value="{{ $pre->id }}"></td>
                            <td>{{ $pre->id }}</td>
                            <td>{{ $pre->prenom }} {{ $pre->nom }}</td>
                            <td>{{ $pre->whatsapp }}</td>
                            <td>{{ $pre->pays }}</td>
                            <td>{{ $pre->choix_formation }}</td>
                            <td>{{ $pre->niveau_dans_formation }}</td>
                            <td>
                                @php
                                    $map = [
                                        'en cours' => ['class' => 'bg-warning text-dark', 'text' => 'En cours'],
                                        'pending' => ['class' => 'bg-warning text-dark', 'text' => 'En cours'],
                                        'accepted' => ['class' => 'bg-success', 'text' => 'Validé'],
                                        'Validé' => ['class' => 'bg-success', 'text' => 'Validé'],
                                        'Actif' => ['class' => 'bg-info text-dark', 'text' => 'Actif'],
                                        'rejected' => ['class' => 'bg-danger', 'text' => 'Rejeté'],
                                    ];
                                    $current = $map[$pre->status] ?? ['class' => 'bg-light text-dark', 'text' => ucfirst($pre->status)];
                                @endphp
                                <span class="badge {{ $current['class'] }}">{{ $current['text'] }}</span>
                            </td>
                            <td>{{ $pre->created_at->format('Y-m-d H:i') }}</td>
                            <td class="text-nowrap">
                                <div class="btn-group" role="group" aria-label="Actions">

                                    <form action="{{ route('admin.preinscriptions.destroy', $pre->id) }}" method="POST" onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer définitivement cette pré-inscription ?\n\nNom: {{ $pre->nom }} {{ $pre->prenom }}\nEmail: {{ $pre->email }}\n\nCette action est irréversible.');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer définitivement" aria-label="Supprimer">
                                            <i class="fas fa-trash-alt"></i> Supprimer
                                        </button>
                                    </form>
                                    @if(!in_array($pre->status, ['accepted','Validé','Actif']))
                                        <form action="{{ route('admin.preinscriptions.validate', $pre->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Valider" aria-label="Valider">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.preinscriptions.show', $pre->id) }}" class="btn btn-sm btn-outline-secondary" title="Voir" aria-label="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted py-4">Aucune pré-inscription pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>

    <div class="mt-3">{{ $pres->links() }}</div>
</div>

<script>
// Mettre à jour le compteur d'éléments sélectionnés
function updateSelectedCount() {
    const checked = document.querySelectorAll('.row-check:checked');
    const count = checked.length;
    document.getElementById('selectedCount').textContent = count;
    document.getElementById('bulkActionBtn').disabled = count === 0;
}

// Ajouter des listeners sur toutes les checkboxes
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.row-check').forEach(cb => {
        cb.addEventListener('change', updateSelectedCount);
    });

    // Désactiver le bouton au départ
    document.getElementById('bulkActionBtn').disabled = true;
});

// Confirmer l'action groupée
function confirmBulkAction() {
    const checked = document.querySelectorAll('.row-check:checked');
    const action = document.getElementById('bulkAction').value;

    if (checked.length === 0) {
        alert('⚠️ Veuillez sélectionner au moins un élément.');
        return false;
    }

    if (!action) {
        alert('⚠️ Veuillez sélectionner une action.');
        return false;
    }

    let actionText = '';
    let warningMessage = '';

    switch(action) {
        case 'accepted':
            actionText = 'accepter';
            warningMessage = `✅ Marquer ${checked.length} pré-inscription(s) comme ACCEPTÉE(S) ?`;
            break;
        case 'rejected':
            actionText = 'rejeter';
            warningMessage = `❌ Marquer ${checked.length} pré-inscription(s) comme REJETÉE(S) ?`;
            break;
        case 'pending':
            actionText = 'remettre en attente';
            warningMessage = `⏳ Remettre ${checked.length} pré-inscription(s) EN ATTENTE ?`;
            break;
        case 'delete':
            actionText = 'supprimer DÉFINITIVEMENT';
            warningMessage = `🗑️ ⚠️ ATTENTION ⚠️\n\nVous êtes sur le point de SUPPRIMER DÉFINITIVEMENT ${checked.length} pré-inscription(s).\n\nCette action est IRRÉVERSIBLE !\n\nÊtes-vous absolument sûr de vouloir continuer ?`;
            break;
    }

    return confirm(warningMessage);
}
</script>
@endsection
