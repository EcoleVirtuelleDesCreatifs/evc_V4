@extends('layouts.admin')

@section('title', 'Reste à Payer')

@push('styles')
<style>
    body {
        background: #0f172a;
    }

    .payments-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(245, 87, 108, 0.3);
    }

    .stat-card-payment {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        margin-bottom: 1.5rem;
    }

    .stat-value-payment {
        font-size: 2rem;
        font-weight: 700;
        color: #f5576c;
    }

    .students-table {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        overflow: hidden;
    }

    .table {
        color: white;
        margin-bottom: 0;
    }

    .table thead {
        background: rgba(245, 87, 108, 0.1);
    }

    .table tbody tr {
        border-bottom: 1px solid #334155;
        transition: background 0.2s;
    }

    .table tbody tr:hover {
        background: rgba(245, 87, 108, 0.05);
    }

    .badge-unpaid {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-weight: 600;
    }

    .action-btn {
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 195, 247, 0.4);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="payments-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-2">
                    <i class="fas fa-exclamation-triangle me-3"></i>Reste à Payer
                </h1>
                <p class="mb-0">Étudiants n'ayant effectué aucun paiement</p>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="stat-card-payment">
                <div class="text-muted mb-1">Étudiants Sans Paiement</div>
                <div class="stat-value-payment">{{ $stats['total'] }}</div>
                <div class="text-danger mt-2">
                    <i class="fas fa-exclamation-circle me-1"></i>Nécessite un suivi
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card-payment">
                <div class="text-muted mb-1">Montant Total Dû</div>
                <div class="stat-value-payment">{{ number_format($stats['total_amount_due'], 0, ',', ' ') }} FCFA</div>
                <div class="text-muted mt-2">
                    <i class="fas fa-coins me-1"></i>À encaisser
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau -->
    <div class="students-table">
        <div class="p-4">
            <h5 class="mb-4 text-white">
                <i class="fas fa-list me-2"></i>Liste des Étudiants ({{ $stats['total'] }})
            </h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Étudiant</th>
                            <th>Email</th>
                            <th>Formation</th>
                            <th>Montant Dû</th>
                            <th>Date Inscription</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td>
                                <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                            </td>
                            <td>{{ $student->email }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $student->program }}</span>
                            </td>
                            <td>
                                <strong class="text-danger">{{ number_format($student->remaining, 0, ',', ' ') }} FCFA</strong>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($student->created_at)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge-unpaid">
                                    <i class="fas fa-times me-1"></i>Non payé
                                </span>
                            </td>
                            <td>
                                <button class="action-btn" onclick="sendReminder({{ $student->id }})">
                                    <i class="fas fa-envelope me-1"></i>Relance
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <p class="text-muted">Aucun impayé ! Tous les étudiants sont à jour.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function sendReminder(studentId) {
    if (!confirm('Envoyer un email de relance à cet étudiant ?')) {
        return;
    }

    // Désactiver le bouton pour éviter les doubles clics
    const btn = event.target.closest('button');
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Envoi...';

    // Envoyer la requête AJAX
    fetch(`{{ url('/evc/app/admin/paiements/send-reminder') }}/${studentId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = originalHTML;

        if (data.success) {
            // Afficher un message de succès
            showNotification('success', data.message);
        } else {
            // Afficher un message d'erreur
            showNotification('error', data.message || 'Erreur lors de l\'envoi de l\'email');
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerHTML = originalHTML;
        console.error('Erreur:', error);
        showNotification('error', 'Erreur de connexion. Veuillez réessayer.');
    });
}

function showNotification(type, message) {
    // Créer une notification toast
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 20px 30px;
        background: ${type === 'success' ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)' : 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)'};
        color: white;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        z-index: 10000;
        font-weight: 600;
        animation: slideIn 0.3s ease;
        max-width: 400px;
    `;

    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
        ${message}
    `;

    document.body.appendChild(notification);

    // Supprimer la notification après 5 secondes
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 5000);
}

// Ajouter les animations CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
@endpush
