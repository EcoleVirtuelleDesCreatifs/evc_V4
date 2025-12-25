@extends('layouts.admin')

@section('title', 'Dons - Dashboard Admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0 text-white">
            <i class="fas fa-hand-holding-heart me-2 text-info"></i>Dons
        </h1>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card" style="background: linear-gradient(135deg, #1e3c72, #2a5298); border: 1px solid rgba(255,255,255,0.08);">
                <div class="card-body">
                    <div class="text-white-50 small">Total demandes</div>
                    <div class="text-white fw-bold" style="font-size: 1.8rem;">{{ number_format($stats['total_count'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="background: linear-gradient(135deg, #10b981, #059669); border: 1px solid rgba(255,255,255,0.08);">
                <div class="card-body">
                    <div class="text-white-50 small">Montants déclarés (XOF)</div>
                    <div class="text-white fw-bold" style="font-size: 1.8rem;">{{ number_format($stats['total_amount'] ?? 0, 0, ',', ' ') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="background: linear-gradient(135deg, #f59e0b, #d97706); border: 1px solid rgba(255,255,255,0.08);">
                <div class="card-body">
                    <div class="text-white-50 small">En attente</div>
                    <div class="text-white fw-bold" style="font-size: 1.8rem;">{{ number_format($stats['pending_count'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); border: 1px solid rgba(255,255,255,0.08);">
                <div class="card-body">
                    <div class="text-white-50 small">Ce mois</div>
                    <div class="text-white fw-bold" style="font-size: 1.8rem;">{{ number_format($stats['month_count'] ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="background: rgba(15, 23, 42, 0.65); border: 1px solid rgba(255,255,255,0.08);">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Montant</th>
                            <th>Moyen</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donations as $donation)
                            <tr>
                                <td>{{ optional($donation->created_at)->format('d/m/Y H:i') }}</td>
                                <td class="fw-semibold">{{ $donation->full_name }}</td>
                                <td><a class="text-info" href="mailto:{{ $donation->email }}">{{ $donation->email }}</a></td>
                                <td>{{ $donation->phone ?? '—' }}</td>
                                <td>
                                    @if(!is_null($donation->amount))
                                        {{ number_format($donation->amount, 0, ',', ' ') }} {{ $donation->currency }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $donation->payment_method ?? '—' }}</td>
                                <td>
                                    @php
                                        $badge = $donation->status === 'new' ? 'warning' : 'success';
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">{{ $donation->status }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.donations.show', $donation->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye me-1"></i>Voir
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-white-50 py-4">Aucun don enregistré pour le moment.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $donations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
