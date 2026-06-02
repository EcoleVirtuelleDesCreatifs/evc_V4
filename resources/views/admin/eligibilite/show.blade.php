@extends('layouts.admin')

@section('title', 'Détail test SAOP')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="mb-1" style="font-weight:800;color:#0f172a;">Test SAOP - {{ $test->full_name }}</h3>
            <div class="text-muted">Soumis le {{ optional($test->submitted_at)->format('d/m/Y à H:i') }}</div>
        </div>
        <a href="{{ route('admin.eligibilite.index') }}" class="btn btn-outline-secondary" style="border-radius:12px;"><i class="fas fa-arrow-left me-2"></i>Retour</a>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><div class="text-muted small">Nom</div><strong>{{ $test->full_name }}</strong></div>
                <div class="col-md-3"><div class="text-muted small">Email</div><strong>{{ $test->email }}</strong></div>
                <div class="col-md-3"><div class="text-muted small">WhatsApp</div><strong>{{ $test->whatsapp ?: '—' }}</strong></div>
                <div class="col-md-3"><div class="text-muted small">Formation</div><strong>{{ $test->formation ? ucwords(str_replace('_', ' ', $test->formation)) : '—' }}</strong></div>
                <div class="col-md-3"><div class="text-muted small">Durée</div><strong>{{ gmdate('H\hi\ms\s', (int) $test->duration_seconds) }}</strong></div>
                <div class="col-md-3"><div class="text-muted small">IP</div><strong>{{ $test->ip_address ?: '—' }}</strong></div>
            </div>
        </div>
    </div>

    @foreach(($test->answers ?? []) as $index => $item)
        <div class="card border-0 shadow-sm mb-3" style="border-radius:16px;">
            <div class="card-body">
                <div class="d-flex gap-3 align-items-start">
                    <div class="badge bg-warning text-dark" style="font-size:14px;border-radius:10px;padding:10px 12px;">Q{{ $index + 1 }}</div>
                    <div class="flex-grow-1">
                        <h6 class="mb-3" style="font-weight:800;color:#0f172a;">{{ $item['question'] ?? '' }}</h6>
                        <div style="white-space:pre-wrap;color:#334155;line-height:1.7;">{{ $item['answer'] ?? '' }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
