@extends('layouts.admin')

@section('title', 'Créer un Communiqué')

@push('styles')
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
<style>
    .form-footer .btn-secondary {
        background-color: #4A5568 !important;
        border-color: #4A5568 !important;
        color: white !important;
    }
    .form-footer .btn-warning {
        background-color: #FBBF24 !important;
        border-color: #FBBF24 !important;
        color: #1F2937 !important;
    }
    .form-footer .btn-success {
        background-color: #10B981 !important;
        border-color: #10B981 !important;
        color: white !important;
    }

    /* Custom styles for communique preview */
    .communique-preview-box {
        background: linear-gradient(to right, #c2410c, #f97316, #c2410c);
        color: white;
        padding: 1rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        font-weight: 500;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        border: 1px solid rgba(255,255,255,0.2);
    }
</style>
@endpush

@section('content')

<form action="{{ route('admin.communiques.store') }}" method="POST" class="interactive-dashboard-form">
    @csrf

    <div class="row g-4">
        <!-- Main Content & Preview Row -->
        <div class="col-12">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="form-card h-100">
                        <div class="form-card-header">
                            <i class="fas fa-bullhorn"></i>
                            <h3>Contenu du Message</h3>
                        </div>
                        <div class="form-card-body">
                            <div class="form-group">
                                <label for="content">Message <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-pen"></i></span>
                                    <textarea class="form-control" id="content" name="content" rows="4" required placeholder="Ex: 🚀 Rentrée Académique 2025-2026 : Les inscriptions sont ouvertes !"></textarea>
                                </div>
                                <div class="form-text mt-2" style="color: #6c757d !important;">
                                    <i class="fas fa-info-circle me-1"></i> Ce texte défilera horizontalement sur la page d'accueil. Soyez concis et percutant (Max 150 caractères).
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-card h-100">
                        <div class="form-card-header">
                            <i class="fas fa-eye"></i>
                            <h3>Aperçu</h3>
                        </div>
                        <div class="form-card-body">
                            <div class="communique-preview-box mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-white text-primary me-2 font-bold">FLASH INFO</span>
                                </div>
                                <p id="preview-text" class="mb-0 fst-italic opacity-90">Votre message s'affichera ici...</p>
                            </div>
                            <div class="alert alert-info text-sm">
                                <i class="fas fa-lightbulb me-1"></i> Le design réel sur la page d'accueil inclut des animations dynamiques et un style "Flash Info".
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Targeting & Scheduling Row -->
        <div class="col-12">
            <div class="form-card">
                <div class="form-card-header">
                    <i class="fas fa-cogs"></i>
                    <h3>Paramètres et Diffusion</h3>
                </div>
                <div class="form-card-body row">
                    <div class="col-md-4 form-group">
                        <label for="target_audience">Cible <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-users"></i></span>
                            <select class="form-select" id="target_audience" name="target_audience" required>
                                @foreach(\App\Models\Communique::TARGETS as $key => $label)
                                    <option value="{{ $key }}">
                                        {{ $label }} 
                                        @if($key === 'all')
                                            (Total : {{ $studentCounts[$key] ?? 0 }} étudiants)
                                        @else
                                            ({{ $studentCounts[$key] ?? 0 }} étudiant{{ ($studentCounts[$key] ?? 0) > 1 ? 's' : '' }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <small class="form-text mt-2" style="color: #6c757d !important; display: block;">Les étudiants ciblés recevront une notification par email.</small>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="start_at">Date de début (Optionnel)</label>
                        <input type="datetime-local" class="form-control" id="start_at" name="start_at">
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="end_at">Date de fin (Optionnel)</label>
                        <input type="datetime-local" class="form-control" id="end_at" name="end_at">
                    </div>
                </div>
                <div class="form-card-body row border-top pt-3 mt-0">
                    <div class="col-md-6 form-group">
                        <label for="order">Ordre d'affichage</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-sort-numeric-down"></i></span>
                            <input type="number" class="form-control" id="order" name="order" value="0" min="0">
                        </div>
                        <small class="form-text mt-2" style="color: #6c757d !important; display: block;">0 = Priorité la plus haute.</small>
                    </div>
                    <div class="col-md-6 form-group d-flex align-items-center pt-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActiveCreate" checked style="width: 3em; height: 1.5em;">
                            <label class="form-check-label ms-3 fw-bold" for="isActiveCreate">Publier immédiatement</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-footer mt-4 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.communiques.index') }}" class="btn btn-light"><i class="fas fa-arrow-left me-2"></i>Retour à la liste</a>
        <button type="submit" class="btn btn-success px-5" id="submitBtn">
            <span class="normal-state"><i class="fas fa-save me-2"></i>Enregistrer et Diffuser</span>
            <span class="loading-state d-none"><i class="fas fa-spinner fa-spin me-2"></i>Envoi en cours...</span>
        </button>
    </div>
</form>

@endsection

@push('scripts')
<script>
    // Live preview update
    document.getElementById('content').addEventListener('input', function(e) {
        const previewText = document.getElementById('preview-text');
        previewText.textContent = e.target.value || "Votre message s'affichera ici...";
        previewText.classList.toggle('fst-italic', !e.target.value);
        previewText.classList.toggle('opacity-75', !e.target.value);
    });

    // Loading state
    document.querySelector('form').addEventListener('submit', function(e) {
        const btn = document.getElementById('submitBtn');
        const normalState = btn.querySelector('.normal-state');
        const loadingState = btn.querySelector('.loading-state');

        if (!this.checkValidity()) return;

        btn.disabled = true;
        normalState.classList.add('d-none');
        loadingState.classList.remove('d-none');
    });
</script>
@endpush
