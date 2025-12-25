@extends('layouts.admin')

@section('title', 'Don - Détail')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h1 class="h4 mb-0 text-white">
            <i class="fas fa-hand-holding-heart me-2 text-info"></i>Détail du don
        </h1>
        <a href="{{ route('admin.donations.index') }}" class="btn btn-outline-light btn-sm">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card" style="background: rgba(15, 23, 42, 0.65); border: 1px solid rgba(255,255,255,0.08);">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="text-white fw-semibold">Informations donateur</div>
                        <span class="badge bg-{{ $donation->status === 'new' ? 'warning' : 'success' }}">{{ $donation->status }}</span>
                    </div>

                    <div class="mb-3">
                        <div class="text-white-50 small">Nom complet</div>
                        <div class="text-white fw-bold">{{ $donation->full_name }}</div>
                    </div>

                    <div class="mb-3">
                        <div class="text-white-50 small">Email</div>
                        <div class="text-white fw-semibold"><a class="text-info" href="mailto:{{ $donation->email }}">{{ $donation->email }}</a></div>
                    </div>

                    <div class="mb-3">
                        <div class="text-white-50 small">Téléphone</div>
                        <div class="text-white fw-semibold">{{ $donation->phone ?? '—' }}</div>
                    </div>

                    <hr style="border-color: rgba(255,255,255,0.10);">

                    <div class="mb-3">
                        <div class="text-white-50 small">Montant déclaré</div>
                        <div class="text-white fw-bold" style="font-size: 1.4rem;">
                            @if(!is_null($donation->amount))
                                {{ number_format($donation->amount, 0, ',', ' ') }} {{ $donation->currency }}
                            @else
                                —
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="text-white-50 small">Moyen de paiement souhaité</div>
                        <div class="text-white fw-semibold">{{ $donation->payment_method ?? '—' }}</div>
                    </div>

                    <div class="mb-0">
                        <div class="text-white-50 small">Date de soumission</div>
                        <div class="text-white fw-semibold">{{ optional($donation->created_at)->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card" style="background: rgba(15, 23, 42, 0.65); border: 1px solid rgba(255,255,255,0.08);">
                <div class="card-body">
                    <div class="text-white fw-semibold mb-3">Message</div>

                    @if(!empty($donation->message))
                        <div class="p-3 rounded" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); white-space: pre-line; color: rgba(255,255,255,0.9);">
                            {{ $donation->message }}
                        </div>
                    @else
                        <div class="text-white-50">Aucun message.</div>
                    @endif

                    <div class="mt-4 d-flex flex-wrap gap-2">
                        <a class="btn btn-outline-info btn-sm" href="mailto:{{ $donation->email }}">
                            <i class="fas fa-envelope me-2"></i>Répondre par email
                        </a>
                        @if($donation->phone)
                            <a class="btn btn-success btn-sm" href="https://wa.me/{{ preg_replace('/\D+/', '', $donation->phone) }}" target="_blank" rel="noopener">
                                <i class="fab fa-whatsapp me-2"></i>WhatsApp
                            </a>
                        @endif
                        <button class="btn btn-outline-light btn-sm" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Imprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
