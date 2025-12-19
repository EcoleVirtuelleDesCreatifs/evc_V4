<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion 2ème Tranche - Admin EVC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { padding: 40px; background: #f8f9fa; }
        .card { box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .alert-info { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; }
        .badge-success { background: #10b981; }
        .badge-warning { background: #f59e0b; }
        .badge-secondary { background: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Gestion 2ème Tranche de Paiement</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong><i class="fas fa-info-circle me-2"></i>Rappel :</strong>
                    L'email de la 2ème tranche (27 000 FCFA) doit être envoyé <strong>après 2 mois de formation</strong>,
                    pas automatiquement après le 1er paiement.
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <strong><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <h5 class="mb-3"><i class="fas fa-users me-2"></i>Étudiants éligibles (1ère tranche payée)</h5>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Étudiant</th>
                                <th>Email</th>
                                <th>Formation</th>
                                <th>1ère Tranche</th>
                                <th>2ème Tranche</th>
                                <th>Date 1er Paiement</th>
                                <th>Temps Écoulé</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            // Récupérer les étudiants ayant payé la 1ère tranche
                            $firstPayments = DB::table('payments')
                                ->leftJoin('pre_registrations', 'payments.pre_registration_id', '=', 'pre_registrations.id')
                                ->where('payments.payment_type', 'installment')
                                ->where('payments.installment_number', 1)
                                ->where('payments.status', 'completed')
                                ->select(
                                    'payments.*',
                                    'pre_registrations.prenom',
                                    'pre_registrations.nom',
                                    'pre_registrations.email',
                                    'pre_registrations.choix_formation'
                                )
                                ->orderBy('payments.paid_at', 'desc')
                                ->get();
                            @endphp

                            @forelse($firstPayments as $firstPayment)
                                @php
                                // Récupérer le paiement de la 2ème tranche correspondant
                                $secondPayment = DB::table('payments')
                                    ->where('parent_payment_id', $firstPayment->id)
                                    ->where('installment_number', 2)
                                    ->first();

                                // Calculer le temps écoulé
                                $paidAt = \Carbon\Carbon::parse($firstPayment->paid_at);
                                $now = \Carbon\Carbon::now();
                                $daysElapsed = $paidAt->diffInDays($now);
                                $twoMonthsInDays = 60; // ~2 mois

                                // Statut
                                if ($secondPayment && $secondPayment->status === 'completed') {
                                    $canSendEmail = false;
                                    $statusBadge = '<span class="badge badge-success">✅ Payé</span>';
                                } elseif ($daysElapsed >= $twoMonthsInDays) {
                                    $canSendEmail = true;
                                    $statusBadge = '<span class="badge badge-warning">⚠️ À envoyer (> 2 mois)</span>';
                                } else {
                                    $canSendEmail = false;
                                    $statusBadge = '<span class="badge badge-secondary">⏳ Trop tôt (' . ($twoMonthsInDays - $daysElapsed) . ' jours restants)</span>';
                                }
                                @endphp

                                <tr>
                                    <td><strong>{{ $firstPayment->prenom }} {{ $firstPayment->nom }}</strong></td>
                                    <td>{{ $firstPayment->email }}</td>
                                    <td>{{ $firstPayment->choix_formation }}</td>
                                    <td>
                                        <span class="badge bg-success">✅ Payée</span><br>
                                        <small>{{ number_format($firstPayment->amount, 0, ',', ' ') }} FCFA</small>
                                    </td>
                                    <td>
                                        {!! $statusBadge !!}<br>
                                        @if($secondPayment)
                                            <small>{{ number_format($secondPayment->amount, 0, ',', ' ') }} FCFA</small>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($firstPayment->paid_at)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <strong>{{ $daysElapsed }}</strong> jours<br>
                                        <small class="text-muted">({{ round($daysElapsed / 30, 1) }} mois)</small>
                                    </td>
                                    <td>
                                        @if($canSendEmail && $secondPayment)
                                            <form action="{{ url('/evc/app/admin/send-second-installment-email') }}" method="POST"
                                                  onsubmit="return confirm('📧 Envoyer l\'email de la 2ème tranche à {{ $firstPayment->prenom }} {{ $firstPayment->nom }} ?');">
                                                @csrf
                                                <input type="hidden" name="payment_id" value="{{ $firstPayment->id }}">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-envelope"></i> Envoyer Email
                                                </button>
                                            </form>
                                        @elseif($secondPayment && $secondPayment->status === 'completed')
                                            <span class="text-success"><i class="fas fa-check-double"></i> Terminé</span>
                                        @else
                                            <span class="text-muted"><i class="fas fa-clock"></i> Attendre 2 mois</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                        Aucun étudiant n'a encore payé la 1ère tranche
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="{{ url('/evc/app/admin') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour Admin
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
