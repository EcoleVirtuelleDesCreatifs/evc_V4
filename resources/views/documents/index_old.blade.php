@extends('layouts.ki-admin')

@section('title', 'Bibliothèque - EVC 2024')
@section('page-title', 'Bibliothèque')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-inbox me-2"></i>
                    Documents reçus
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Document</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Taille</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <i class="fas fa-file-pdf text-danger me-2"></i>
                                    Guide Laravel Complet
                                </td>
                                <td><span class="badge" style="background-color: var(--primary-color); color: white;">Cours</span></td>
                                <td>25 Juillet 2024</td>
                                <td>2.5 MB</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-file-code text-primary me-2"></i>
                                    Exercices JavaScript
                                </td>
                                <td><span class="badge" style="background-color: var(--warning-color); color: white;">TP</span></td>
                                <td>20 Juillet 2024</td>
                                <td>1.2 MB</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-upload me-2"></i>
                    Documents à soumettre
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Rapport de stage</h6>
                            <small class="text-muted">Format PDF, max 5MB</small>
                        </div>
                        <div>
                            <span class="badge" style="background-color: var(--accent-color); color: white;">Urgent</span>
                            <button class="btn btn-sm btn-primary ms-2">Soumettre</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-certificate me-2"></i>
                    Certificats obtenus
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card border">
                            <div class="card-body text-center">
                                <i class="fas fa-award fa-2x mb-2" style="color: var(--warning-color);"></i>
                                <h6 class="mb-1">PHP Fundamentals</h6>
                                <small class="text-muted">Obtenu le 15 Juillet 2024</small>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-primary">Télécharger</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border">
                            <div class="card-body text-center">
                                <i class="fas fa-award fa-2x mb-2" style="color: var(--success-color);"></i>
                                <h6 class="mb-1">Database Design</h6>
                                <small class="text-muted">Obtenu le 10 Juillet 2024</small>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-primary">Télécharger</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Statistiques
                </h6>
            </div>
            <div class="card-body text-center">
                <div class="row">
                    <div class="col-6">
                        <h4 style="color: var(--primary-color);">15</h4>
                        <small class="text-muted">Reçus</small>
                    </div>
                    <div class="col-6">
                        <h4 style="color: var(--success-color);">2</h4>
                        <small class="text-muted">Certificats</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-cloud-upload-alt me-2"></i>
                    Upload rapide
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <input type="file" class="form-control form-control-sm">
                </div>
                <button class="btn btn-primary btn-sm w-100">Téléverser</button>
            </div>
        </div>
    </div>
</div>
@endsection
