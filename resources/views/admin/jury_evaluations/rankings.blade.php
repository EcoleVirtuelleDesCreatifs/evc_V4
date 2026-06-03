@extends('layouts.admin')

@section('title', 'Classements par catégorie')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white h4 mb-0">🏆 Classements par catégorie</h1>
        <a href="{{ route('admin.jury-members.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Retour Jury
        </a>
    </div>

    @foreach($rankings as $categoryKey => $categoryRankings)
        <div class="card mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
            <div class="card-header d-flex align-items-center gap-2" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                <span style="font-size:1.2rem;">
                    @if($loop->index === 0) 🎨
                    @elseif($loop->index === 1) 📱
                    @elseif($loop->index === 2) 🎤
                    @else ❤️
                    @endif
                </span>
                <h5 class="mb-0 text-white">{{ $categories[$categoryKey] }}</h5>
                <span class="badge bg-primary ms-auto">{{ $categoryRankings->count() }} évaluation(s)</span>
            </div>
            <div class="card-body p-0">
                @if($categoryRankings->isEmpty())
                    <div class="text-center py-4" style="color:#94a3b8;">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        Aucune évaluation pour cette catégorie
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0">
                            <thead>
                                <tr style="border-color:#334155;">
                                    <th style="width:60px;">Rang</th>
                                    <th>Groupe</th>
                                    <th>Note catégorie</th>
                                    <th>Note totale</th>
                                    <th>Membre du jury</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categoryRankings as $index => $ranking)
                                    <tr style="border-color:#334155; {{ $index === 0 ? 'background-color:#1a2f1a;' : '' }}">
                                        <td>
                                            @if($index === 0)
                                                <span class="badge" style="background-color:#f59e0b;color:#000;font-size:.85rem;">🥇 1</span>
                                            @elseif($index === 1)
                                                <span class="badge bg-secondary" style="font-size:.85rem;">🥈 2</span>
                                            @elseif($index === 2)
                                                <span class="badge" style="background-color:#cd7f32;font-size:.85rem;">🥉 3</span>
                                            @else
                                                <span style="color:#94a3b8;">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong class="text-white">{{ $ranking['group_name'] }}</strong>
                                        </td>
                                        <td>
                                            @php $pct = round($ranking['category_score'] / 80 * 100); @endphp
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-primary">{{ $ranking['category_score'] }} / 80</span>
                                                <div style="width:80px;height:6px;background:#334155;border-radius:3px;overflow:hidden;">
                                                    <div style="width:{{ $pct }}%;height:100%;background:#3b82f6;border-radius:3px;"></div>
                                                </div>
                                                <small style="color:#94a3b8;">{{ $pct }}%</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span style="color:#94a3b8;">{{ $ranking['total_score'] }} / 320</span>
                                        </td>
                                        <td>
                                            <span class="badge" style="background-color:#1e3a5f;color:#93c5fd;">
                                                <i class="fas fa-user-tie me-1"></i>{{ $ranking['jury_name'] }}
                                            </span>
                                        </td>
                                        <td style="color:#94a3b8;">
                                            {{ $ranking['evaluation_date'] ? $ranking['evaluation_date']->format('d/m/Y') : '-' }}
                                        </td>
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
