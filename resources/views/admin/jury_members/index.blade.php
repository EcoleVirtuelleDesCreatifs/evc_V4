@extends('layouts.admin')

@section('title', 'Membres du jury')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="text-white h4 mb-1">👔 Membres du jury</h1>
            <div class="small" style="color:#64748b;">Gestion des profils et suivi des évaluations</div>
        </div>
        <a href="{{ route('admin.jury-members.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Ajouter
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <div class="card" style="background:#1e293b;border:1px solid #334155;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead>
                        <tr style="background:#0f172a;border-color:#334155;">
                            <th style="width:50px;"></th>
                            <th>Membre</th>
                            <th>Identifiant</th>
                            <th>Pays</th>
                            <th>Groupe noté</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                            @php
                                $votedEval = $member->evaluations->first();
                                $photoSrc  = null;
                                if (!empty($member->image_url)) {
                                    $photoSrc = $member->image_url;
                                } elseif (!empty($member->image_path)) {
                                    $photoSrc = asset('storage/' . ltrim($member->image_path, '/'));
                                }
                            @endphp
                            <tr style="border-color:#334155;">
                                <td>
                                    @if($photoSrc)
                                        <img src="{{ $photoSrc }}"
                                             alt="{{ $member->name }}"
                                             class="rounded-circle"
                                             style="width:46px;height:46px;object-fit:cover;object-position:top;border:2px solid #334155;"
                                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                        <div style="display:none;width:46px;height:46px;border-radius:50%;background:#334155;align-items:center;justify-content:center;font-size:1.2rem;">👤</div>
                                    @else
                                        <div style="width:46px;height:46px;border-radius:50%;background:#334155;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">👤</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-white">{{ $member->name }}</div>
                                    <div class="small" style="color:#64748b;">{{ $member->title ?: 'Fonction non renseignée' }}</div>
                                </td>
                                <td>
                                    <span class="font-monospace small" style="background:#0f172a;color:#93c5fd;padding:3px 8px;border-radius:6px;border:1px solid #1e3a5f;">
                                        {{ $member->unique_identifier ?? '—' }}
                                    </span>
                                </td>
                                <td style="color:#94a3b8;">
                                    @if($member->flag) <span>{{ $member->flag }}</span> @endif
                                    {{ $member->country ?: '—' }}
                                </td>
                                <td>
                                    @if($votedEval)
                                        <span class="badge" style="background:#14532d;color:#4ade80;border:1px solid #166534;font-size:.8rem;">
                                            ✅ {{ $votedEval->group_name }}
                                        </span>
                                    @else
                                        <span style="color:#475569;font-size:.85rem;">— pas encore noté</span>
                                    @endif
                                </td>
                                <td>
                                    @if($member->is_active)
                                        <span class="badge" style="background:#14532d;color:#4ade80;">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if(\Illuminate\Support\Facades\Route::has('admin.jury-members.evaluations.index'))
                                        <a href="{{ route('admin.jury-members.evaluations.index', $member) }}" class="btn btn-sm" style="background:#1e3a5f;color:#93c5fd;border:1px solid #1e40af;" title="Voir évaluations">
                                            <i class="fas fa-chart-bar"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.jury-members.edit', $member) }}" class="btn btn-sm btn-primary" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.jury-members.destroy', $member) }}" class="d-inline" onsubmit="return confirm('Supprimer {{ addslashes($member->name) }} ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5" style="color:#475569;">
                                    <i class="fas fa-user-tie fa-2x mb-2 d-block"></i>
                                    Aucun membre du jury enregistré
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
