<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Webhook CinetPay - Admin EVC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 40px; background: #f8f9fa; }
        .card { box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .alert-info { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; }
        .btn-success { background: linear-gradient(135deg, #10b981, #059669); border: none; }
        .btn-success:hover { background: linear-gradient(135deg, #059669, #047857); }
        code { background: #f3f4f6; padding: 2px 6px; border-radius: 4px; font-size: 13px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">🧪 Simulateur de Webhook CinetPay (Mode TEST)</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>ℹ️ Info :</strong> En développement local, les webhooks CinetPay ne peuvent pas être appelés automatiquement.
                    Utilisez cette page pour simuler un paiement réussi.
                </div>

                <form action="{{ url('/admin/test/simulate-webhook') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">Transaction ID (payment_reference)</label>
                        <input type="text" name="transaction_id" class="form-control"
                               placeholder="Ex: EVC-20251209-668B170C"
                               value="{{ request('ref') }}" required>
                        <small class="text-muted">Récupérez-le depuis les logs ou la base de données</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Montant</label>
                        <input type="number" name="amount" class="form-control"
                               value="100" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Statut</label>
                        <select name="status" class="form-select">
                            <option value="00">✅ Succès (00 / ACCEPTED)</option>
                            <option value="REFUSED">❌ Refusé</option>
                            <option value="FAILED">❌ Échoué</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100">
                        🚀 Simuler le Webhook
                    </button>
                </form>

                <hr class="my-4">

                <h5>📋 Paiements en Attente</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Référence</th>
                                <th>Email</th>
                                <th>Montant</th>
                                <th>Type</th>
                                <th>Tranche</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $pendingPayments = DB::table('payments')
                                ->where('status', 'pending')
                                ->orderBy('created_at', 'desc')
                                ->limit(10)
                                ->get();
                            @endphp

                            @forelse($pendingPayments as $p)
                            <tr>
                                <td><code>{{ $p->payment_reference }}</code></td>
                                <td>{{ $p->payer_email }}</td>
                                <td>{{ number_format($p->amount, 0, ',', ' ') }} FCFA</td>
                                <td>
                                    @if($p->payment_type === 'installment')
                                        <span class="badge bg-info">Par Tranche</span>
                                    @else
                                        <span class="badge bg-secondary">Unique</span>
                                    @endif
                                </td>
                                <td>
                                    @if($p->payment_type === 'installment')
                                        {{ $p->installment_number }}/{{ $p->total_installments }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ url('/admin/test/simulate-webhook') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="transaction_id" value="{{ $p->transaction_id }}">
                                        <input type="hidden" name="amount" value="{{ $p->amount }}">
                                        <input type="hidden" name="status" value="00">
                                        <button type="submit" class="btn btn-sm btn-success">✅ Valider</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Aucun paiement en attente</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(session('success'))
                <div class="alert alert-success mt-3">
                    <strong>✅ Succès :</strong> {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger mt-3">
                    <strong>❌ Erreur :</strong> {{ session('error') }}
                </div>
                @endif
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="{{ url('/evc/app/admin') }}" class="btn btn-outline-secondary">← Retour Admin</a>
        </div>
    </div>
</body>
</html>
