@extends('layouts.admin')

@section('title', 'Paiements à Solder')

@push('styles')
<style>
    body {
        background: #0f172a;
    }

    .payments-header {
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(255, 193, 7, 0.3);
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
        color: #ffc107;
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
        background: rgba(255, 193, 7, 0.1);
    }

    .table tbody tr {
        border-bottom: 1px solid #334155;
        transition: background 0.2s;
    }

    .table tbody tr:hover {
        background: rgba(255, 193, 7, 0.05);
    }

    .badge-partial {
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-weight: 600;
    }

    .progress-payment {
        height: 8px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .progress-bar-payment {
        height: 100%;
        background: linear-gradient(90deg, #ffc107 0%, #ff9800 100%);
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
                    <i class="fas fa-hourglass-half me-3"></i>Paiements à Solder
                </h1>
                <p class="mb-0">Étudiants ayant effectué un paiement partiel</p>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card-payment">
                <div class="text-muted mb-1">Étudiants Partiels</div>
                <div class="stat-value-payment">{{ $stats['total'] }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-payment">
                <div class="text-muted mb-1">Total Déjà Payé</div>
                <div class="stat-value-payment">{{ number_format($stats['total_paid'], 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-payment">
                <div class="text-muted mb-1">Reste à Encaisser</div>
                <div class="stat-value-payment">{{ number_format($stats['total_remaining'], 0, ',', ' ') }} FCFA</div>
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
                            <th>Payé</th>
                            <th>Reste</th>
                            <th>Progression</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td><strong>{{ $student->first_name }} {{ $student->last_name }}</strong></td>
                            <td>{{ $student->email }}</td>
                            <td><span class="badge bg-primary">{{ $student->program }}</span></td>
                            <td class="text-success"><strong>{{ number_format($student->amount_paid, 0, ',', ' ') }} FCFA</strong></td>
                            <td class="text-warning"><strong>{{ number_format($student->remaining, 0, ',', ' ') }} FCFA</strong></td>
                            <td>
                                @php $percentage = ($student->amount_paid / $student->total_amount) * 100; @endphp
                                <div>{{ number_format($percentage, 0) }}%</div>
                                <div class="progress-payment">
                                    <div class="progress-bar-payment" style="width: {{ $percentage }}%"></div>
                                </div>
                            </td>
                            <td><span class="badge-partial"><i class="fas fa-hourglass-half me-1"></i>Partiel</span></td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-secondary me-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#paymentRecapModal"
                                    data-student-name="{{ trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) }}"
                                    data-student-email="{{ $student->email }}"
                                    data-student-program="{{ $student->program }}"
                                    data-total-amount="{{ (int) ($student->total_amount ?? 0) }}"
                                    data-amount-paid="{{ (int) ($student->amount_paid ?? 0) }}"
                                    data-remaining="{{ (int) ($student->remaining ?? 0) }}"
                                >
                                    <i class="fas fa-eye me-1"></i>Voir
                                </button>
                                @if(!empty($student->pre_registration_id))
                                    <a href="{{ route('admin.paiements.a-solder.edit-restant', $student->pre_registration_id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit me-1"></i>Modifier reste
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-secondary" disabled>
                                        <i class="fas fa-ban me-1"></i>Indisponible
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun paiement partiel</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Récap Paiement -->
<div class="modal fade" id="paymentRecapModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: #0f172a; border: 1px solid #334155; border-radius: 16px; color: #fff;">
            <div class="modal-header" style="border-bottom: 1px solid #334155;">
                <h5 class="modal-title">
                    <i class="fas fa-receipt me-2" style="color: #ffc107;"></i>Récap paiement
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2"><strong>Étudiant :</strong> <span id="recapStudentName">-</span></div>
                <div class="mb-2"><strong>Email :</strong> <span id="recapStudentEmail">-</span></div>
                <div class="mb-3"><strong>Formation :</strong> <span id="recapStudentProgram">-</span></div>

                <div class="row g-3">
                    <div class="col-4">
                        <div class="p-3" style="background: rgba(255, 193, 7, 0.08); border: 1px solid rgba(255, 193, 7, 0.2); border-radius: 12px;">
                            <div class="text-muted" style="font-size: 0.85rem;">Total</div>
                            <div style="font-weight: 700; color: #ffc107;" id="recapTotalAmount">0 FCFA</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3" style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 12px;">
                            <div class="text-muted" style="font-size: 0.85rem;">Payé</div>
                            <div style="font-weight: 700; color: #10b981;" id="recapAmountPaid">0 FCFA</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3" style="background: rgba(245, 158, 11, 0.08); border: 1px solid rgba(245, 158, 11, 0.2); border-radius: 12px;">
                            <div class="text-muted" style="font-size: 0.85rem;">Reste</div>
                            <div style="font-weight: 700; color: #f59e0b;" id="recapRemaining">0 FCFA</div>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="d-flex justify-content-between" style="font-size: 0.9rem;">
                        <span class="text-muted">Progression</span>
                        <strong id="recapProgress">0%</strong>
                    </div>
                    <div class="progress-payment" style="margin-top: 0.5rem;">
                        <div class="progress-bar-payment" id="recapProgressBar" style="width: 0%"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #334155;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('paymentRecapModal');
    if (!modalEl) return;

    const formatFcfa = (value) => {
        try {
            const n = Number(value || 0);
            return new Intl.NumberFormat('fr-FR').format(n) + ' FCFA';
        } catch (e) {
            return (value || 0) + ' FCFA';
        }
    };

    modalEl.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        if (!btn) return;

        const name = btn.getAttribute('data-student-name') || '-';
        const email = btn.getAttribute('data-student-email') || '-';
        const program = btn.getAttribute('data-student-program') || '-';
        const total = Number(btn.getAttribute('data-total-amount') || 0);
        const paid = Number(btn.getAttribute('data-amount-paid') || 0);
        const remaining = Number(btn.getAttribute('data-remaining') || 0);

        const percentage = total > 0 ? Math.min(100, Math.max(0, (paid / total) * 100)) : 0;

        document.getElementById('recapStudentName').textContent = name;
        document.getElementById('recapStudentEmail').textContent = email;
        document.getElementById('recapStudentProgram').textContent = program;
        document.getElementById('recapTotalAmount').textContent = formatFcfa(total);
        document.getElementById('recapAmountPaid').textContent = formatFcfa(paid);
        document.getElementById('recapRemaining').textContent = formatFcfa(remaining);
        document.getElementById('recapProgress').textContent = Math.round(percentage) + '%';

        const bar = document.getElementById('recapProgressBar');
        if (bar) {
            bar.style.width = percentage + '%';
        }
    });
});
</script>
@endpush
