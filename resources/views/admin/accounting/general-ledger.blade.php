@extends('layouts.admin')

@section('title', 'Grand Livre')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Grand Livre</h1>
        <div class="d-flex align-items-center gap-3">
            <form action="{{ route('admin.accounting.general-ledger') }}" method="GET" class="d-flex align-items-center gap-2">
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                <span class="text-muted">à</span>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                <button type="submit" class="btn btn-primary">Filtrer</button>
            </form>
            <a href="{{ route('admin.accounting.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-striped">
                <thead class="table-light sticky-top">
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Libellé</th>
                        <th>Catégorie</th>
                        <th class="text-end">Débit (Dépense)</th>
                        <th class="text-end">Crédit (Recette)</th>
                        <th class="text-end">Solde Cumulé</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $balance = 0;
                    @endphp
                    @forelse($transactions as $transaction)
                        @php
                            if ($transaction->type === 'income') {
                                $balance += $transaction->amount;
                            } else {
                                $balance -= $transaction->amount;
                            }
                        @endphp
                        <tr>
                            <td class="text-nowrap">{{ $transaction->date->format('d/m/Y') }}</td>
                            <td>
                                @if($transaction->type === 'income')
                                    <span class="badge bg-success bg-opacity-10 text-success">REC</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger">DEP</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-medium">{{ $transaction->title }}</div>
                                @if($transaction->description)
                                    <div class="small text-muted">{{ Str::limit($transaction->description, 50) }}</div>
                                @endif
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $transaction->category }}</span></td>
                            <td class="text-end text-danger font-monospace">
                                {{ $transaction->type === 'expense' ? number_format($transaction->amount, 0, ',', ' ') : '-' }}
                            </td>
                            <td class="text-end text-success font-monospace">
                                {{ $transaction->type === 'income' ? number_format($transaction->amount, 0, ',', ' ') : '-' }}
                            </td>
                            <td class="text-end font-monospace fw-bold {{ $balance >= 0 ? 'text-primary' : 'text-danger' }}">
                                {{ number_format($balance, 0, ',', ' ') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                Aucune transaction trouvée pour cette période.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="4" class="text-end">Totaux</td>
                        <td class="text-end text-danger">{{ number_format($transactions->where('type', 'expense')->sum('amount'), 0, ',', ' ') }}</td>
                        <td class="text-end text-success">{{ number_format($transactions->where('type', 'income')->sum('amount'), 0, ',', ' ') }}</td>
                        <td class="text-end {{ $balance >= 0 ? 'text-primary' : 'text-danger' }}">{{ number_format($balance, 0, ',', ' ') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
