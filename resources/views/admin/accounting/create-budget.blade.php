@extends('layouts.admin')

@section('title', 'Définir un Budget')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Définir un Budget Prévisionnel</h1>
        <a href="{{ route('admin.accounting.budgets') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour aux budgets
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('admin.accounting.budgets.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="expense">

                        <div class="mb-4">
                            <h5 class="card-title text-muted mb-3">Informations Budgétaires</h5>
                            <hr>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Année Fiscale</label>
                                <select class="form-select form-select-lg" name="year" required>
                                    @for($y = date('Y'); $y <= date('Y') + 2; $y++)
                                        <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                                <div class="form-text">L'année pour laquelle ce budget sera appliqué.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Catégorie de Dépense</label>
                                <select class="form-select form-select-lg" name="category" required>
                                    <option value="">Sélectionner une catégorie...</option>
                                    @php
                                        $categories = [
                                            'Loyer', 'Salaires', 'Matériel', 'Logiciels',
                                            'Marketing', 'Impôts', 'Divers'
                                        ];
                                    @endphp
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Montant Alloué (FCFA)</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-coins text-muted"></i>
                                    </span>
                                    <input type="number" class="form-control border-start-0 ps-0" name="amount" required min="0" placeholder="Ex: 5000000">
                                    <span class="input-group-text">FCFA</span>
                                </div>
                                <div class="form-text">Ce montant servira de plafond de référence pour le suivi des dépenses.</div>
                            </div>
                        </div>

                        <div class="alert alert-info d-flex align-items-center mt-4" role="alert">
                            <i class="fas fa-info-circle me-2 fa-lg"></i>
                            <div>
                                Si un budget existe déjà pour cette catégorie et cette année, il sera mis à jour avec le nouveau montant.
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-save me-2"></i>Enregistrer le Budget
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
