@extends('layouts.admin')

@section('title', 'Gestion des Salaires')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gestion des Salaires</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Retour Dashboard</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">Forfait mensuel</div>
                    <div class="fs-3 fw-bold">{{ number_format($baseAmount, 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">Statut délais</div>
                    <div class="fs-4 fw-bold">{{ $isCompliant ? 'Délais respectés' : 'Délais non respectés (/2)' }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">Gagné ce mois</div>
                    <div class="fs-3 fw-bold">{{ number_format($earnedThisMonth, 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Tâche</th>
                            <th class="text-center">Objectif (mois)</th>
                            <th class="text-center">Réalisé</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($taskTypes as $task)
                            @php
                                $done = (int)($logsByType[$task->id] ?? 0);
                                $expected = (int)($task->expected_per_month ?? 0);
                            @endphp
                            <tr>
                                <td>{{ $task->label }}</td>
                                <td class="text-center">{{ $expected }}</td>
                                <td class="text-center">{{ $done }}</td>
                                <td class="text-end">
                                    <form action="{{ route('admin.assistant.tasks.store') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="task_type_id" value="{{ $task->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-sm btn-primary">Marquer fait</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
