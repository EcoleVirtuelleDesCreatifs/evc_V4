@extends('layouts.admin')

@section('title', 'Membres du jury')

@section('content')
@php
    $total   = $members->count();
    $actifs  = $members->where('is_active', true)->count();
    $voted   = $members->filter(fn($m) => $m->evaluations->isNotEmpty())->count();
    $pending = $total - $voted;
@endphp
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="text-white mb-1" style="font-size:1.4rem;font-weight:700;">👔 Membres du jury</h1>
            <p style="color:#64748b;margin:0;font-size:.875rem;">Studio Créatif — Gestion des profils et suivi des évaluations</p>
        </div>
        <a href="{{ route('admin.jury-members.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Ajouter un membre
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0" style="background:#14532d;color:#4ade80;">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.1rem 1.3rem;display:flex;align-items:center;gap:.9rem;">
                <div style="width:40px;height:40px;border-radius:10px;background:rgba(59,130,246,.15);display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">👥</div>
                <div>
                    <div style="font-size:.7rem;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Total</div>
                    <div style="font-size:1.5rem;font-weight:700;color:#f1f5f9;line-height:1;">{{ $total }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.1rem 1.3rem;display:flex;align-items:center;gap:.9rem;">
                <div style="width:40px;height:40px;border-radius:10px;background:rgba(16,185,129,.15);display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">✅</div>
                <div>
                    <div style="font-size:.7rem;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Actifs</div>
                    <div style="font-size:1.5rem;font-weight:700;color:#f1f5f9;line-height:1;">{{ $actifs }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.1rem 1.3rem;display:flex;align-items:center;gap:.9rem;">
                <div style="width:40px;height:40px;border-radius:10px;background:rgba(245,158,11,.15);display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">🏅</div>
                <div>
                    <div style="font-size:.7rem;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Ont noté</div>
                    <div style="font-size:1.5rem;font-weight:700;color:#f1f5f9;line-height:1;">{{ $voted }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.1rem 1.3rem;display:flex;align-items:center;gap:.9rem;">
                <div style="width:40px;height:40px;border-radius:10px;background:rgba(244,63,94,.15);display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">⏳</div>
                <div>
                    <div style="font-size:.7rem;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">En attente</div>
                    <div style="font-size:1.5rem;font-weight:700;color:#f1f5f9;line-height:1;">{{ $pending }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div style="background:#1e293b;border:1px solid #334155;border-radius:16px;overflow:hidden;">
        <div style="background:#0f172a;border-bottom:1px solid #334155;padding:.9rem 1.4rem;display:flex;align-items:center;justify-content:space-between;">
            <span class="text-white fw-600" style="font-size:.9rem;font-weight:600;">Liste des membres</span>
            @if(\Illuminate\Support\Facades\Route::has('admin.jury-evaluations.rankings'))
            <a href="{{ route('admin.jury-evaluations.rankings') }}" class="btn btn-sm" style="background:#0f172a;border:1px solid #334155;color:#94a3b8;font-size:.78rem;">
                <i class="fas fa-trophy me-1" style="color:#f59e0b;"></i> Voir classements
            </a>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0" style="--bs-table-bg:transparent;--bs-table-hover-bg:rgba(255,255,255,.03);">
                <thead>
                    <tr style="border-color:#334155;">
                        <th style="padding:.75rem 1.2rem;font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;color:#64748b;font-weight:600;"></th>
                        <th style="padding:.75rem 1.2rem;font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;color:#64748b;font-weight:600;">Membre</th>
                        <th style="padding:.75rem 1.2rem;font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;color:#64748b;font-weight:600;">Identifiant</th>
                        <th style="padding:.75rem 1.2rem;font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;color:#64748b;font-weight:600;">Pays</th>
                        <th style="padding:.75rem 1.2rem;font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;color:#64748b;font-weight:600;">Groupe noté</th>
                        <th style="padding:.75rem 1.2rem;font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;color:#64748b;font-weight:600;">Statut</th>
                        <th style="padding:.75rem 1.2rem;font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;color:#64748b;font-weight:600;text-align:right;">Actions</th>
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
                        <tr style="border-color:#1e3347;">
                            <td style="padding:.85rem 1.2rem;width:60px;">
                                @if($photoSrc)
                                    <img src="{{ $photoSrc }}" alt="{{ $member->name }}"
                                         class="rounded-circle"
                                         style="width:46px;height:46px;object-fit:cover;object-position:top;border:2px solid #334155;"
                                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                    <div style="display:none;width:46px;height:46px;border-radius:50%;background:#1e3347;align-items:center;justify-content:center;font-size:1.3rem;color:#475569;">👤</div>
                                @else
                                    <div style="width:46px;height:46px;border-radius:50%;background:#1e3347;display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:#475569;">👤</div>
                                @endif
                            </td>
                            <td style="padding:.85rem 1.2rem;">
                                <div style="font-weight:600;color:#f1f5f9;">{{ $member->name }}</div>
                                <div style="font-size:.78rem;color:#64748b;margin-top:2px;">{{ $member->title ?: 'Fonction non renseignée' }}</div>
                            </td>
                            <td style="padding:.85rem 1.2rem;">
                                <code style="background:#0f172a;color:#93c5fd;padding:3px 9px;border-radius:6px;border:1px solid #1e3a5f;font-size:.78rem;letter-spacing:.03em;">{{ $member->unique_identifier ?? '—' }}</code>
                            </td>
                            <td style="padding:.85rem 1.2rem;color:#94a3b8;font-size:.88rem;">
                                @if($member->flag)<span style="margin-right:4px;">{{ $member->flag }}</span>@endif{{ $member->country ?: '—' }}
                            </td>
                            <td style="padding:.85rem 1.2rem;">
                                @if($votedEval)
                                    <span style="display:inline-flex;align-items:center;gap:5px;background:#14532d;color:#4ade80;border:1px solid #166534;border-radius:20px;padding:3px 10px;font-size:.78rem;font-weight:600;">
                                        ✅ {{ $votedEval->group_name }}
                                    </span>
                                @else
                                    <span style="color:#475569;font-size:.82rem;font-style:italic;">Pas encore noté</span>
                                @endif
                            </td>
                            <td style="padding:.85rem 1.2rem;">
                                @if($member->is_active)
                                    <span style="background:rgba(16,185,129,.15);color:#10b981;border:1px solid rgba(16,185,129,.3);border-radius:20px;padding:3px 10px;font-size:.75rem;font-weight:600;">Actif</span>
                                @else
                                    <span style="background:rgba(100,116,139,.15);color:#64748b;border:1px solid rgba(100,116,139,.3);border-radius:20px;padding:3px 10px;font-size:.75rem;font-weight:600;">Inactif</span>
                                @endif
                            </td>
                            <td style="padding:.85rem 1.2rem;text-align:right;white-space:nowrap;">
                                @if(\Illuminate\Support\Facades\Route::has('admin.jury-members.evaluations.index'))
                                    <a href="{{ route('admin.jury-members.evaluations.index', $member) }}"
                                       class="btn btn-sm me-1"
                                       style="background:#1e3a5f;color:#93c5fd;border:1px solid #1e40af;"
                                       title="Évaluations">
                                        <i class="fas fa-chart-bar"></i>
                                    </a>
                                @endif
                                <a href="{{ route('admin.jury-members.edit', $member) }}"
                                   class="btn btn-sm btn-primary me-1" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.jury-members.destroy', $member) }}"
                                      class="d-inline"
                                      onsubmit="return confirm('Supprimer {{ addslashes($member->name) }} ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:3.5rem;color:#475569;">
                                <i class="fas fa-user-tie" style="font-size:2rem;display:block;margin-bottom:.75rem;"></i>
                                Aucun membre du jury enregistré.
                                <a href="{{ route('admin.jury-members.create') }}" class="d-block mt-2" style="color:#3b82f6;">Ajouter le premier membre →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
