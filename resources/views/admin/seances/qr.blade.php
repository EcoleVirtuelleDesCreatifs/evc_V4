@extends('layouts.admin')

@section('title', 'QR Code - ' . $seance->title)
@section('page-title', 'QR Code de pointage')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">QR Code de pointage</h1>
            <p class="text-muted mb-0">{{ $seance->title }} — {{ $seance->scheduled_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.seances.attendance', $seance) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </a>
            <a href="{{ route('admin.seances.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-list me-1"></i> Liste
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4 text-center">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="mb-3">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($qrUrl) }}"
                             alt="QR Code de pointage"
                             class="img-fluid rounded border"
                             style="max-width: 300px;">
                    </div>

                    <p class="text-muted small mb-1">Lien de pointage :</p>
                    <p class="small text-break mb-3"><code>{{ $qrUrl }}</code></p>

                    <p class="mb-1">
                        <span class="badge bg-{{ $qrToken->isValid() ? 'success' : 'danger' }}">
                            {{ $qrToken->isValid() ? 'Ouvert' : 'Fermé' }}
                        </span>
                    </p>
                    <p class="text-muted small">
                        Expire à : <strong>{{ $qrToken->expires_at->format('H:i:s') }}</strong>
                    </p>

                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <form action="{{ route('admin.seances.qr.regenerate', $seance) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-sync-alt me-1"></i> Régénérer
                            </button>
                        </form>

                        <form action="{{ route('admin.seances.qr.close', $seance) }}" method="POST" class="d-inline" onsubmit="return confirm('Fermer le pointage ?')">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-lock me-1"></i> Fermer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
