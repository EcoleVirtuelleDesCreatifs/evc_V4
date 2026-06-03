@extends('layouts.admin')

@section('title', 'Évaluations de ' . $juryMember->name)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h4 mb-1">Évaluations de {{ $juryMember->name }}</h1>
            <div class="text-muted small">Historique des évaluations de ce membre du jury</div>
        </div>
        <div>
            <a href="{{ route('admin.jury-members.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
        </div>
    </div>

    @if($evaluations->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="text-muted">Aucune évaluation pour ce membre du jury</div>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Groupe</th>
                                <th>Note totale</th>
                                <th>Statut</th>
                                <th>Commentaire</th>
                                <th class="text-end">Détails</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($evaluations as $evaluation)
                                <tr>
                                    <td>{{ $evaluation->evaluation_date ? $evaluation->evaluation_date->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $evaluation->group_name }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $evaluation->total_score }} / 320</span>
                                    </td>
                                    <td>
                                        @if($evaluation->status === 'submitted')
                                            <span class="badge bg-success">Soumis</span>
                                        @else
                                            <span class="badge bg-secondary">Brouillon</span>
                                        @endif
                                    </td>
                                    <td class="text-truncate" style="max-width: 200px;">
                                        {{ $evaluation->global_comment ?: '-' }}
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-{{ $evaluation->id }}">
                                            Voir détails
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>

@foreach($evaluations as $evaluation)
    <div class="modal fade" id="modal-{{ $evaluation->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Évaluation de {{ $evaluation->group_name }}
                        <small class="text-muted">- {{ $evaluation->evaluation_date ? $evaluation->evaluation_date->format('d/m/Y') : '-' }}</small>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Note totale :</strong> {{ $evaluation->total_score }} / 320
                    </div>
                    @if($evaluation->global_comment)
                        <div class="mb-3">
                            <strong>Commentaire :</strong>
                            <p>{{ $evaluation->global_comment }}</p>
                        </div>
                    @endif
                    <h6>Détails par catégorie</h6>
                    @foreach($evaluation->scores->groupBy('category_key') as $categoryKey => $categoryScores)
                        <div class="card mb-2">
                            <div class="card-body py-2">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $categoryScores->first()->category_label }}</strong>
                                    <span>{{ $categoryScores->sum('score') }} / 80</span>
                                </div>
                                <ul class="mb-0 mt-1 ps-3">
                                    @foreach($categoryScores as $score)
                                        <li>{{ $score->criterion_label }} : {{ $score->score }} / 20</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
