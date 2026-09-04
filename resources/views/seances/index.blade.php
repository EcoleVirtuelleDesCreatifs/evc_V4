@extends('layouts.ki-admin')

@section('title', 'Mes séances')
@section('page-title', 'Mes séances')

@push('styles')
<style>
    .seances-page { color: #f8fafc; }
    .seances-hero {
        background: linear-gradient(135deg, #0b1220 0%, #0e1d3a 50%, #1e3a8a 100%);
        border-radius: 18px;
        padding: 2rem 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(191, 219, 254, 0.12);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
    }
    .seances-hero h1 { color: #fff; font-weight: 900; margin: 0 0 0.5rem 0; }
    .seances-hero p { color: #cbd5e1; margin: 0; }
    .seance-card {
        background: #0b1220;
        border: 1px solid rgba(191, 219, 254, 0.12);
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .seance-card:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(0, 0, 0, 0.28); }
    .seance-title { color: #fff; font-weight: 800; margin-bottom: 0.25rem; }
    .seance-formation { color: #60a5fa; letter-spacing: 0.05em; }
    .seance-meta { color: #94a3b8; font-size: 0.92rem; display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem; }
    .seance-meta i { color: #60a5fa; }
    .badge-seance { border-radius: 20px; padding: 0.35rem 0.7rem; font-weight: 700; font-size: 0.78rem; }
    .badge-onsite { background: rgba(16, 185, 129, 0.14); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.25); }
    .badge-online { background: rgba(37, 99, 235, 0.14); color: #60a5fa; border: 1px solid rgba(37, 99, 235, 0.25); }
    .badge-hybrid { background: rgba(139, 92, 246, 0.14); color: #a78bfa; border: 1px solid rgba(139, 92, 246, 0.25); }
    .badge-scheduled { background: rgba(148, 163, 184, 0.14); color: #cbd5e1; border: 1px solid rgba(148, 163, 184, 0.25); }
    .badge-ongoing { background: rgba(245, 158, 11, 0.14); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.25); }
    .badge-completed { background: rgba(16, 185, 129, 0.14); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.25); }
    .badge-cancelled { background: rgba(239, 68, 68, 0.14); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.25); }
    .badge-status { background: rgba(255, 255, 255, 0.08); color: #e2e8f0; border: 1px solid rgba(255, 255, 255, 0.12); }
    .badge-present { background: rgba(16, 185, 129, 0.18); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.35); }
    .badge-absent { background: rgba(239, 68, 68, 0.18); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.35); }
    .badge-late { background: rgba(245, 158, 11, 0.18); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.35); }
    .badge-excused { background: rgba(99, 102, 241, 0.18); color: #a5b4fc; border: 1px solid rgba(99, 102, 241, 0.35); }
    .btn-meet, .btn-qr {
        border: none;
        border-radius: 10px;
        padding: 0.55rem 1rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #fff;
    }
    .btn-meet { background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%); }
    .btn-meet:hover { color: #fff; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.35); }
    .btn-qr { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .btn-qr:hover { color: #fff; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.35); }
    .empty-seances {
        text-align: center; color: #94a3b8; padding: 3rem 1rem;
        background: rgba(11, 18, 32, 0.6); border-radius: 16px;
        border: 1px dashed rgba(191, 219, 254, 0.18);
    }
    .empty-seances i { font-size: 3rem; color: #64748b; margin-bottom: 1rem; }
    .section-title { color: #fff; font-weight: 800; margin: 2rem 0 1rem 0; }
</style>
@endpush

@section('content')
@php
    $routePrefix = explode('.', Route::currentRouteName())[0];
@endphp
<div class="seances-page">
    <div class="seances-hero">
        <h1><i class="fas fa-chalkboard-user me-2"></i>Mes séances</h1>
        <p>Consultez vos séances : en cours, à venir et terminées.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <h2 class="section-title"><i class="fas fa-broadcast-tower me-2"></i>Séance en cours</h2>
    @if($current)
        @include('seances._card', ['seance' => $current, 'routePrefix' => $routePrefix, 'attendances' => $attendances, 'clicks' => $clicks, 'context' => 'current'])
    @else
        <div class="empty-seances">
            <i class="fas fa-circle-notch"></i>
            <h4>Aucune séance en cours</h4>
            <p>Votre prochaine séance apparaîtra dans la section ci-dessous.</p>
        </div>
    @endif

    <h2 class="section-title"><i class="fas fa-arrow-right me-2"></i>Prochaine séance</h2>
    @if($next)
        @include('seances._card', ['seance' => $next, 'routePrefix' => $routePrefix, 'attendances' => $attendances, 'clicks' => $clicks, 'context' => 'next'])
    @else
        <div class="empty-seances">
            <i class="fas fa-calendar-plus"></i>
            <h4>Aucune prochaine séance</h4>
        </div>
    @endif

    <h2 class="section-title"><i class="fas fa-calendar-alt me-2"></i>Séances à venir</h2>
    @if($upcoming->isEmpty())
        <div class="empty-seances">
            <i class="fas fa-calendar-check"></i>
            <h4>Aucune autre séance à venir</h4>
        </div>
    @else
        @foreach($upcoming as $seance)
            @include('seances._card', ['seance' => $seance, 'routePrefix' => $routePrefix, 'attendances' => $attendances, 'clicks' => $clicks, 'context' => 'upcoming'])
        @endforeach
    @endif

    <h2 class="section-title"><i class="fas fa-history me-2"></i>Séances terminées</h2>
    @if($past->isEmpty())
        <div class="empty-seances">
            <i class="fas fa-history"></i>
            <h4>Aucune séance terminée</h4>
        </div>
    @else
        @foreach($past as $seance)
            @include('seances._card', ['seance' => $seance, 'routePrefix' => $routePrefix, 'attendances' => $attendances, 'clicks' => $clicks, 'context' => 'past'])
        @endforeach
    @endif
</div>
@endsection
