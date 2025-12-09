@extends('layouts.admin')

@section('title', 'Ajouter une dépense')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Ajouter une dépense</h1>
        <a href="{{ route('admin.accounting.expenses') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.accounting.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="expense">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Titre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="title" required value="{{ old('title') }}" placeholder="Ex: Achat matériel bureau">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Montant (FCFA) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="amount" min="0" required value="{{ old('amount') }}" placeholder="Ex: 50000">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                        <select class="form-select" name="category" required>
                            <option value="">Choisir...</option>
                            <option value="Loyer" {{ old('category') == 'Loyer' ? 'selected' : '' }}>Loyer</option>
                            <option value="Salaires" {{ old('category') == 'Salaires' ? 'selected' : '' }}>Salaires</option>
                            <option value="Matériel" {{ old('category') == 'Matériel' ? 'selected' : '' }}>Matériel</option>
                            <option value="Logiciels" {{ old('category') == 'Logiciels' ? 'selected' : '' }}>Logiciels</option>
                            <option value="Marketing" {{ old('category') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                            <option value="Impôts" {{ old('category') == 'Impôts' ? 'selected' : '' }}>Impôts & Taxes</option>
                            <option value="Divers" {{ old('category') == 'Divers' ? 'selected' : '' }}>Divers</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="4" placeholder="Détails supplémentaires...">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Mode de paiement</label>
                        <select class="form-select" name="payment_method">
                            <option value="Virement" {{ old('payment_method') == 'Virement' ? 'selected' : '' }}>Virement Bancaire</option>
                            <option value="Espèces" {{ old('payment_method') == 'Espèces' ? 'selected' : '' }}>Espèces</option>
                            <option value="Chèque" {{ old('payment_method') == 'Chèque' ? 'selected' : '' }}>Chèque</option>
                            <option value="Mobile Money" {{ old('payment_method') == 'Mobile Money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="Carte Bancaire" {{ old('payment_method') == 'Carte Bancaire' ? 'selected' : '' }}>Carte Bancaire</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Justificatif (PDF, Image)</label>
                        <input type="file" class="form-control" name="proof" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="form-text">Formats acceptés: PDF, JPG, PNG. Max: 2Mo.</div>
                    </div>

                    <div class="col-12 mt-4 text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Enregistrer la dépense
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
