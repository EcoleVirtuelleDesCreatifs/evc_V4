@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="mb-1" style="font-weight: 800; color: #0f172a;">Pré-inscriptions</h3>
            <div class="text-muted">Suivi et gestion des candidatures.</div>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
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
                    <button class="btn btn-primary" style="border-radius: 12px;"><i class="fas fa-filter me-2"></i>Filtrer</button>
                </div>
            </form>
            <a href="{{ route('admin.preinscriptions.export', request()->only(['q','formation','status'])) }}" class="btn btn-success" style="border-radius: 12px;"><i class="fas fa-file-export me-2"></i>Exporter CSV</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                <div class="stat-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total'] ?? 0 }}</h3>
                    <p>Total</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%);">
                <div class="stat-icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['pending'] ?? 0 }}</h3>
                    <p>En attente</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%);">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['accepted'] ?? 0 }}</h3>
                    <p>Acceptées</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['rejected'] ?? 0 }}</h3>
                    <p>Rejetées</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="d-flex align-items-center gap-2">
            <select name="action" required class="form-select w-auto" id="bulkAction">
                <option value="">-- Sélectionner une action --</option>
                <option value="accepted">✅ Marquer comme Accepté</option>
                <option value="rejected">❌ Marquer comme Rejeté</option>
                <option value="pending">⏳ Remettre en attente</option>
                <option value="delete" style="color: #dc3545; font-weight: bold;">🗑️ Supprimer définitivement</option>
            </select>
            <button type="button" onclick="submitBulkAction()" class="btn btn-primary" id="bulkActionBtn">
                <i class="fas fa-check me-2"></i>Appliquer à <span id="selectedCount">0</span> élément(s)
            </button>
        </div>

        <div class="card shadow-sm border-0" style="border-radius: 16px;">
            <div class="card-header bg-white border-0 py-3" style="border-radius: 16px 16px 0 0;">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="fas fa-user-plus me-2" style="color: #1e3c72;"></i>
                    Liste des Pré-inscriptions
                </h5>
            </div>
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
                                    <a href="{{ route('admin.preinscriptions.payment', $pre->id) }}"
                                       class="btn btn-sm btn-primary btn-icon"
                                       title="Gérer les paiements"
                                       aria-label="Paiement">
                                        <i class="fas fa-coins"></i>
                                    </a>

                                    <a href="{{ route('admin.preinscriptions.devis', $pre->id) }}"
                                       class="btn btn-sm btn-outline-primary btn-icon"
                                       title="Télécharger le devis"
                                       aria-label="Devis">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                    @if(!in_array($pre->status, ['accepted','Validé','Actif']))
                                        {{-- Bouton Accepter --}}
                                        <form action="{{ route('admin.preinscriptions.accept', $pre->id) }}" method="POST" class="d-inline" onsubmit="return confirm('✅ Accepter cette candidature ?\n\nLe candidat recevra un email avec le lien de paiement (50 000 FCFA).');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success btn-icon" title="Accepter et envoyer lien paiement" aria-label="Accepter">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>

                                        {{-- Bouton Rejeter --}}
                                        <form action="{{ route('admin.preinscriptions.reject', $pre->id) }}" method="POST" class="d-inline" onsubmit="return confirm('❌ Rejeter cette candidature ?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Rejeter" aria-label="Rejeter">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Bouton Voir --}}
                                    <a href="{{ route('admin.preinscriptions.show', $pre->id) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="Voir détails" aria-label="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if(\Illuminate\Support\Facades\Route::has('admin.preinscriptions.edit'))
                                        <a href="{{ route('admin.preinscriptions.edit', $pre->id) }}" class="btn btn-sm btn-outline-warning btn-icon" title="Éditer" aria-label="Éditer">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif

                                    {{-- Bouton Supprimer --}}
                                    <form action="{{ route('admin.preinscriptions.destroy', $pre->id) }}" method="POST" onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer définitivement cette pré-inscription ?\n\nNom: {{ $pre->nom }} {{ $pre->prenom }}\nEmail: {{ $pre->email }}\n\nCette action est irréversible.');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger btn-icon" title="Supprimer définitivement" aria-label="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted py-4">Aucune pré-inscription pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer bg-white border-0 py-3" style="border-radius: 0 0 16px 16px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="pagination-info text-muted mb-2 mb-md-0">
                @if($pres->total() > 0)
                    Affichage de <strong>{{ $pres->firstItem() }}</strong> à <strong>{{ $pres->lastItem() }}</strong> sur <strong>{{ $pres->total() }}</strong> pré-inscriptions
                @else
                    Aucun résultat
                @endif
            </div>
            <div>
                {{ $pres->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    border-radius: 16px;
    padding: 1.5rem;
    color: white;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.3s, box-shadow 0.3s;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.btn-icon {
    width: 36px;
    height: 36px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.2);
}

.stat-icon {
    font-size: 2rem;
    opacity: 0.9;
}

.stat-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    line-height: 1;
}

.stat-content p {
    margin: 0.5rem 0 0 0;
    font-size: 0.9rem;
    opacity: 0.9;
}

.pagination-info {
    font-size: 0.9rem;
}

.pagination {
    margin: 0;
}

.pagination .page-link {
    color: #1e3c72;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin: 0 0.25rem;
    padding: 0.5rem 0.75rem;
    font-weight: 500;
    transition: all 0.3s;
}

.pagination .page-link:hover {
    background-color: #1e3c72;
    color: white;
    border-color: #1e3c72;
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(30, 60, 114, 0.3);
}

.pagination .page-item.active .page-link {
    background-color: #1e3c72;
    border-color: #1e3c72;
    box-shadow: 0 2px 8px rgba(30, 60, 114, 0.3);
}

.pagination .page-item.disabled .page-link {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #6c757d;
}

@media (max-width: 768px) {
    .stat-card {
        margin-bottom: 1rem;
    }

    .pagination-info {
        font-size: 0.8rem;
        text-align: center;
    }

    .card-footer .d-flex {
        flex-direction: column;
        gap: 1rem;
    }

    .pagination {
        display: flex;
        justify-content: center;
    }
}
</style>

<script>
// Soumettre l'action groupée
function submitBulkAction() {
    const action = document.getElementById('bulkAction').value;
    const checked = document.querySelectorAll('.row-check:checked');

    if (!action) {
        alert('⚠️ Veuillez sélectionner une action');
        return;
    }

    if (checked.length === 0) {
        alert('⚠️ Veuillez sélectionner au moins une préinscription');
        return;
    }

    if (!confirmBulkAction()) {
        return;
    }

    // Créer et soumettre le formulaire dynamiquement
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route('admin.preinscriptions.bulk-status') }}';

    // Token CSRF
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';
    form.appendChild(csrf);

    // Action
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = action;
    form.appendChild(actionInput);

    // IDs sélectionnés
    checked.forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = checkbox.value;
        form.appendChild(input);
    });

    // Paramètres de filtre
    const params = ['q', 'formation', 'status'];
    params.forEach(param => {
        const value = new URLSearchParams(window.location.search).get(param);
        if (value) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = param;
            input.value = value;
            form.appendChild(input);
        }
    });

    document.body.appendChild(form);
    form.submit();
}

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
