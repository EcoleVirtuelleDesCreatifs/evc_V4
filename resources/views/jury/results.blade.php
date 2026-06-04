@extends('layouts.app')

@section('title', 'Mes résultats — ' . $member->name . ' | EVC Jury')

@section('content')
<style>
    :root {
        --jr: #ff9800;
        --jr-dark: #f57c00;
        --bg: #0f172a;
        --card: #1e293b;
        --border: #334155;
        --text: #f1f5f9;
        --muted: #94a3b8;
    }
    .jr-page { background:var(--bg); min-height:100vh; padding:160px 20px 80px; }
    @media(max-width:768px){ .jr-page{ padding-top:120px; } }
    @media(max-width:400px){ .jr-page{ padding-top:90px; } }

    .jr-wrap { max-width:820px; margin:0 auto; }

    /* Header membre */
    .jr-header {
        background:linear-gradient(135deg,#1e3a5f,#0f172a);
        border:1px solid var(--border); border-radius:16px;
        padding:2rem; display:flex; align-items:center; gap:1.5rem;
        margin-bottom:2rem; flex-wrap:wrap;
    }
    .jr-avatar {
        width:80px; height:80px; border-radius:50%;
        object-fit:cover; object-position:top;
        border:3px solid var(--jr); flex-shrink:0;
    }
    .jr-avatar-ph {
        width:80px; height:80px; border-radius:50%; background:#334155;
        display:flex; align-items:center; justify-content:center;
        font-size:2rem; border:3px solid var(--jr); flex-shrink:0;
    }
    .jr-name { font-size:1.4rem; font-weight:800; color:var(--text); margin-bottom:.2rem; }
    .jr-title { color:var(--muted); font-size:.9rem; }
    .jr-country { color:var(--muted); font-size:.85rem; margin-top:.3rem; }
    .jr-badge {
        margin-left:auto; background:rgba(255,152,0,.15);
        border:1px solid rgba(255,152,0,.4); border-radius:12px;
        padding:.5rem 1.1rem; text-align:center; flex-shrink:0;
    }
    .jr-badge-val { font-size:1.6rem; font-weight:800; color:var(--jr); line-height:1; }
    .jr-badge-lbl { font-size:.7rem; color:var(--muted); text-transform:uppercase; letter-spacing:.07em; }

    /* Section titre */
    .jr-section-title {
        font-size:.7rem; font-weight:700; text-transform:uppercase;
        letter-spacing:.1em; color:#475569;
        border-bottom:1px solid var(--border);
        padding-bottom:.5rem; margin-bottom:1.25rem;
    }

    /* Card groupe */
    .jr-group-card {
        background:var(--card); border:1px solid var(--border);
        border-radius:14px; overflow:hidden; margin-bottom:1.25rem;
    }
    .jr-group-head {
        background:#0f172a; padding:1rem 1.4rem;
        display:flex; align-items:center; justify-content:space-between;
        gap:1rem; flex-wrap:wrap;
    }
    .jr-group-name { font-weight:700; color:var(--text); font-size:1rem; }
    .jr-group-date { font-size:.78rem; color:var(--muted); }
    .jr-group-score {
        background:linear-gradient(135deg,#f59e0b,#d97706);
        color:#fff; font-weight:800; font-size:1rem;
        border-radius:8px; padding:.35rem .9rem; white-space:nowrap;
    }
    .jr-group-body { padding:1.25rem 1.4rem; }

    /* Catégorie */
    .jr-cat { margin-bottom:1rem; }
    .jr-cat:last-child { margin-bottom:0; }
    .jr-cat-label {
        font-size:.75rem; font-weight:600; color:var(--muted);
        margin-bottom:.35rem; display:flex; align-items:center; gap:.4rem;
    }
    .jr-cat-bar-wrap { display:flex; align-items:center; gap:.75rem; }
    .jr-cat-bar-bg {
        flex:1; height:8px; background:#0f172a;
        border-radius:99px; overflow:hidden;
    }
    .jr-cat-bar-fill {
        height:100%; border-radius:99px;
        background:linear-gradient(90deg,#f59e0b,#ff9800);
        transition:width .4s;
    }
    .jr-cat-score { font-size:.85rem; font-weight:700; color:var(--text); width:40px; text-align:right; flex-shrink:0; }
    .jr-comment {
        background:#0f172a; border:1px solid var(--border);
        border-radius:8px; padding:.85rem 1rem;
        font-size:.85rem; color:var(--muted); line-height:1.6;
        margin-top:1rem;
    }
    .jr-comment strong { color:var(--text); display:block; margin-bottom:.3rem; font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; }

    /* Total global */
    .jr-total {
        background:linear-gradient(135deg,#1e3a5f,#0f172a);
        border:1px solid rgba(255,152,0,.3);
        border-radius:14px; padding:1.5rem 2rem;
        display:flex; align-items:center; justify-content:space-between;
        flex-wrap:wrap; gap:1rem; margin-top:2rem;
    }
    .jr-total-label { font-size:.9rem; color:var(--muted); font-weight:600; }
    .jr-total-val { font-size:2rem; font-weight:900; color:var(--jr); }

    /* Back button */
    .jr-back {
        display:inline-flex; align-items:center; gap:.5rem;
        color:var(--muted); text-decoration:none;
        font-size:.875rem; font-weight:600;
        border:1px solid var(--border); background:#1e293b;
        border-radius:8px; padding:.55rem 1.1rem;
        transition:all .2s; margin-bottom:1.5rem;
    }
    .jr-back:hover { border-color:var(--jr); color:var(--jr); }

    .jr-empty {
        text-align:center; padding:4rem 2rem;
        background:var(--card); border:1px solid var(--border);
        border-radius:16px; color:var(--muted);
    }
</style>

<div class="jr-page">
    <div class="jr-wrap">

        {{-- Retour --}}
        @php $isEvc = request()->is('evc/*'); @endphp
        <a href="{{ $isEvc ? '/evc/jury/evaluation' : '/jury/evaluation' }}" class="jr-back">
            ← Retour à la page d'évaluation
        </a>

        {{-- Header membre --}}
        <div class="jr-header">
            @if($member->photo_url && !str_ends_with($member->photo_url, 'default-avatar.png'))
                <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="jr-avatar">
            @else
                <div class="jr-avatar-ph">{{ $member->flag ?? '👤' }}</div>
            @endif
            <div>
                <div class="jr-name">{{ $member->name }}</div>
                @if($member->title)
                    <div class="jr-title">{{ $member->title }}</div>
                @endif
                @if($member->country)
                    <div class="jr-country">{{ $member->flag ?? '' }} {{ $member->country }}</div>
                @endif
            </div>
            <div class="jr-badge">
                <div class="jr-badge-val">{{ $evaluations->count() }}</div>
                <div class="jr-badge-lbl">Groupe(s) noté(s)</div>
            </div>
        </div>

        @if($evaluations->isEmpty())
            <div class="jr-empty">
                <div style="font-size:2.5rem;margin-bottom:1rem;">📋</div>
                <div style="font-size:1.1rem;font-weight:600;color:#f1f5f9;margin-bottom:.5rem;">Aucune évaluation soumise</div>
                <div>Vos évaluations apparaîtront ici une fois soumises.</div>
            </div>
        @else
            <div class="jr-section-title">Évaluations soumises</div>

            @php
                $grandTotal = 0;
                $categoryLabels = [
                    'best_logo'                 => ['label' => 'Meilleur Logo',                         'icon' => '🏅', 'max' => 20],
                    'best_graphic_charter'      => ['label' => 'Meilleure Charte Graphique',             'icon' => '🎨', 'max' => 20],
                    'professional_presentation' => ['label' => 'Meilleure Présentation Professionnelle', 'icon' => '🎤', 'max' => 20],
                    'jury_favorite'             => ['label' => 'Prix Coup de Cœur',                     'icon' => '❤️', 'max' => 20],
                ];
            @endphp

            @foreach($evaluations as $eval)
                @php $grandTotal += $eval->total_score; @endphp
                <div class="jr-group-card">
                    <div class="jr-group-head">
                        <div>
                            <div class="jr-group-name">{{ $eval->group_name }}</div>
                            <div class="jr-group-date">{{ \Carbon\Carbon::parse($eval->evaluation_date)->format('d/m/Y') }}</div>
                        </div>
                        <div class="jr-group-score">{{ $eval->total_score }} pts</div>
                    </div>
                    <div class="jr-group-body">
                        @foreach($categoryLabels as $key => $meta)
                            @php
                                $score = $eval->scores->where('category_key', $key)->sum('score');
                                $pct   = $meta['max'] > 0 ? min(100, round($score / $meta['max'] * 100)) : 0;
                            @endphp
                            @if($score > 0)
                            <div class="jr-cat">
                                <div class="jr-cat-label">{{ $meta['icon'] }} {{ $meta['label'] }}</div>
                                <div class="jr-cat-bar-wrap">
                                    <div class="jr-cat-bar-bg">
                                        <div class="jr-cat-bar-fill" style="width:{{ $pct }}%;"></div>
                                    </div>
                                    <span class="jr-cat-score">{{ $score }}/{{ $meta['max'] }}</span>
                                </div>
                            </div>
                            @endif
                        @endforeach

                        @if($eval->global_comment)
                            <div class="jr-comment">
                                <strong>💬 Commentaire global</strong>
                                {{ $eval->global_comment }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            {{-- Total --}}
            <div class="jr-total">
                <div class="jr-total-label">🏆 Score total cumulé</div>
                <div class="jr-total-val">{{ $grandTotal }} pts</div>
            </div>
        @endif

    </div>
</div>
@endsection
