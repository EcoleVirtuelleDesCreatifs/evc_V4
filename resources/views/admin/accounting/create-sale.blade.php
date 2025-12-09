@extends('layouts.admin')

@section('title', 'Ajouter une vente')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Ajouter une vente</h1>
        <a href="{{ route('admin.accounting.sales') }}" class="btn btn-outline-secondary">
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
                <input type="hidden" name="type" value="income">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Titre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="title" required value="{{ old('title') }}" placeholder="Ex: Paiement scolarité">
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
                            <option value="Inscription" {{ old('category') == 'Inscription' ? 'selected' : '' }}>Inscription</option>
                            <option value="Scolarité" {{ old('category') == 'Scolarité' ? 'selected' : '' }}>Scolarité</option>
                            <option value="Vente de supports de formation" {{ old('category') == 'Vente de supports de formation' ? 'selected' : '' }}>Vente de supports de formation</option>
                            <option value="Prestation" {{ old('category') == 'Prestation' ? 'selected' : '' }}>Prestation de service</option>
                            <option value="Vente de matériel" {{ old('category') == 'Vente de matériel' ? 'selected' : '' }}>Vente de matériel</option>
                            <option value="Subvention" {{ old('category') == 'Subvention' ? 'selected' : '' }}>Subvention</option>
                            <option value="Autre" {{ old('category') == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Référence (Facture/Reçu)</label>
                        <input type="text" class="form-control" name="reference" value="{{ old('reference') }}">
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
                        <label class="form-label">Nom de l'étudiant</label>
                        <input type="text" class="form-control" name="student_name" value="{{ old('student_name') }}" placeholder="Ex: Jean Kouassi">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Module de formation</label>
                        <select class="form-select" name="training_module">
                            <option value="">Choisir...</option>
                            <option value="Design Graphique" {{ old('training_module') == 'Design Graphique' ? 'selected' : '' }}>Design Graphique</option>
                            <option value="Community Management" {{ old('training_module') == 'Community Management' ? 'selected' : '' }}>Community Management</option>
                            <option value="Gestion Informatique" {{ old('training_module') == 'Gestion Informatique' ? 'selected' : '' }}>Gestion Informatique</option>
                            <option value="Intelligence Artificielle" {{ old('training_module') == 'Intelligence Artificielle' ? 'selected' : '' }}>Intelligence Artificielle</option>
                            <option value="Bureautique" {{ old('training_module') == 'Bureautique' ? 'selected' : '' }}>Bureautique</option>
                            <option value="Autre" {{ old('training_module') == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="4" placeholder="Détails supplémentaires...">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Justificatif (PDF, Image)</label>
                        <input type="file" class="form-control" name="proof" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="form-text">Formats acceptés: PDF, JPG, PNG. Max: 2Mo.</div>
                    </div>

                    <div class="col-12 mt-4 text-end">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fas fa-save me-2"></i>Enregistrer la vente
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
