@extends('layouts.admin')

@section('title', 'Classements par catégorie')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h4 mb-1">Classements par catégorie</h1>
            <div class="text-muted small">Résultats des évaluations par catégorie</div>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
        </div>
    </div>

    @foreach($rankings as $categoryKey => $categoryRankings)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ $categories[$categoryKey] }}</h5>
            </div>
            <div class="card-body">
                @if($categoryRankings->isEmpty())
                    <div class="text-muted">Aucune évaluation pour cette catégorie</div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th width="50">Rang</th>
                                    <th>Groupe</th>
                                    <th>Note catégorie</th>
                                    <th>Note totale</th>
                                    <th>Jury</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categoryRankings as $index => $ranking)
                                    <tr>
                                        <td>
                                            @if($index === 0)
                                                <span class="badge bg-warning text-dark">🥇 1</span>
                                            @elseif($index === 1)
                                                <span class="badge bg-secondary">🥈 2</span>
                                            @elseif($index === 2)
                                                <span class="badge bg-danger">🥉 3</span>
                                            @else
                                                <span class="text-muted">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td><strong>{{ $ranking['group_name'] }}</strong></td>
                                        <td><span class="badge bg-primary">{{ $ranking['category_score'] }} / 80</span></td>
                                        <td>{{ $ranking['total_score'] }} / 320</td>
                                        <td>{{ $ranking['jury_name'] }}</td>
                                        <td>{{ $ranking['evaluation_date'] ? $ranking['evaluation_date']->format('d/m/Y') : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
