@extends('layouts.admin')

@section('title', $title)

@push('styles')
<style>
    .chip {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .4rem .75rem;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.16);
        background: rgba(255,255,255,0.06);
        color: rgba(255,255,255,0.92);
        font-weight: 700;
        font-size: .85rem;
        cursor: pointer;
        user-select: none;
        transition: transform .15s ease, border-color .15s ease, background .15s ease;
    }

    .chip:hover {
        transform: translateY(-1px);
        border-color: rgba(79,195,247,0.7);
    }

    .chip.active {
        background: rgba(79,195,247,0.14);
        border-color: rgba(79,195,247,0.9);
    }

    .search-input {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.12);
        color: #fff;
    }

    .search-input::placeholder {
        color: rgba(255,255,255,0.55);
    }

    .student-card {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 16px;
        overflow: hidden;
        height: 100%;
    }

    .student-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 40px rgba(0,0,0,0.35);
        border-color: rgba(79,195,247,0.55);
        transition: all .18s ease;
    }

    .student-card-header {
        background: #0f172a;
        border-bottom: 1px solid #334155;
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    /* Thèmes formation */
    .theme-dg .student-card-header {
        background: linear-gradient(135deg, rgba(79,195,247,0.30) 0%, rgba(30,60,114,0.42) 60%, rgba(15,23,42,1) 100%);
    }

    .theme-cm .student-card-header {
        background: linear-gradient(135deg, rgba(131,58,180,0.42) 0%, rgba(193,53,132,0.42) 38%, rgba(225,48,108,0.32) 70%, rgba(245,96,64,0.24) 100%);
    }

    .theme-dgcm .student-card-header {
        background: linear-gradient(135deg, rgba(79,195,247,0.30) 0%, rgba(30,60,114,0.42) 55%, rgba(245,158,11,0.26) 100%);
    }

    .student-avatar {
        width: 56px;
        height: 56px;
        border-radius: 999px;
        object-fit: cover;
        background: #1d4ed8;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 800;
        flex-shrink: 0;
        overflow: hidden;
    }

    .student-meta {
        min-width: 0;
        flex: 1;
    }

    .student-name {
        color: #fff;
        font-weight: 700;
        margin: 0;
        font-size: 1.05rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .student-sub {
        color: rgba(255,255,255,0.65);
        font-size: 0.9rem;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .student-card-body {
        padding: 1rem;
    }

    .meta-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .75rem;
        margin-bottom: 1rem;
    }

    .meta-pill {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 12px;
        padding: .75rem;
        color: rgba(255,255,255,0.85);
        font-size: .9rem;
    }

    .meta-pill strong {
        display: block;
        font-size: .75rem;
        color: rgba(255,255,255,0.6);
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-bottom: .25rem;
    }

    .actions-row {
        display: flex;
        gap: .5rem;
        flex-wrap: wrap;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-white">{{ $title }}</h1>
            <div class="text-white-50">Liste des étudiants ({{ $status === 'active' ? 'actifs' : 'inactifs' }})</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.badges.students.active') }}" class="btn btn-sm {{ $status === 'active' ? 'btn-primary' : 'btn-outline-light' }}">Actifs</a>
            <a href="{{ route('admin.badges.students.inactive') }}" class="btn btn-sm {{ $status === 'inactive' ? 'btn-primary' : 'btn-outline-light' }}">Inactifs</a>
        </div>
    </div>

    <div class="row g-2 align-items-end mb-3">
        <div class="col-lg-5">
            <label class="form-label text-white-50">Rechercher</label>
            <input id="studentSearch" type="text" class="form-control search-input" placeholder="Nom, email, pays, formation..." />
        </div>
        <div class="col-lg-7">
            <label class="form-label text-white-50">Filtrer par formation</label>
            <div class="d-flex flex-wrap gap-2">
                <span class="chip active" data-filter="all">Tous</span>
                <span class="chip" data-filter="dg">Design Graphique</span>
                <span class="chip" data-filter="cm">Community Management</span>
                <span class="chip" data-filter="dgcm">Design + CM</span>
            </div>
        </div>
    </div>

    <div class="row g-3">
        @forelse($students as $student)
            @php
                $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($student->profile_photo ?? null);
                $initials = substr($student->first_name ?? 'U', 0, 1) . substr($student->last_name ?? 'U', 0, 1);
                $progRaw = (string) ($student->program ?? '');
                $prog = strtolower($progRaw);
                $hasDesign = strpos($prog, 'design') !== false;
                $hasCommunity = (strpos($prog, 'community') !== false) || (strpos($prog, 'manager') !== false) || (strpos($prog, 'management') !== false);
                $theme = $hasDesign && $hasCommunity ? 'theme-dgcm' : ($hasCommunity ? 'theme-cm' : ($hasDesign ? 'theme-dg' : ''));
                $filterKey = $hasDesign && $hasCommunity ? 'dgcm' : ($hasCommunity ? 'cm' : ($hasDesign ? 'dg' : 'other'));
            @endphp
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="student-card {{ $theme }}" data-student-card data-filter="{{ $filterKey }}" data-search="{{ strtolower(($student->first_name ?? '') . ' ' . ($student->last_name ?? '') . ' ' . ($student->email ?? '') . ' ' . ($student->country ?? '') . ' ' . $progRaw) }}">
                    <div class="student-card-header">
                        @if(!empty($student->profile_photo) && !empty($photoUrl))
                            <img src="{{ $photoUrl }}" alt="{{ $student->first_name ?? 'Étudiant' }}" class="student-avatar" />
                        @else
                            <div class="student-avatar">{{ $initials }}</div>
                        @endif

                        <div class="student-meta">
                            <p class="student-name">{{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }}</p>
                            <p class="student-sub">{{ $student->email ?? '' }}</p>
                        </div>

                        @if($status === 'inactive')
                            <div class="ms-auto">
                                <span class="badge bg-danger">Expiré</span>
                            </div>
                        @endif
                    </div>

                    <div class="student-card-body">
                        <div class="meta-row">
                            <div class="meta-pill">
                                <strong>Pays</strong>
                                <div>{{ $student->country ?? 'N/A' }}</div>
                            </div>
                            <div class="meta-pill">
                                <strong>Formation</strong>
                                <div>{{ $student->program ?? 'N/A' }}</div>
                            </div>
                        </div>

                        @if($status === 'inactive')
                            @php
                                $expiresAt = $student->expiration_date ?? ($student->computed_expiration_date ?? null);
                            @endphp
                            <div class="meta-pill mb-3">
                                <strong>Date d'expiration</strong>
                                <div>
                                    @if(!empty($expiresAt))
                                        {{ \Carbon\Carbon::parse($expiresAt)->format('d/m/Y') }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="actions-row">
                            <a href="{{ route('admin.students.profile', $student->id) }}" class="btn btn-sm btn-info">Voir</a>
                            <a href="{{ route('admin.badges.generate', $student->id) }}" class="btn btn-sm btn-primary">Générer un badge</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
                    <div class="card-body text-center text-white-50 py-5">
                        Aucun étudiant à afficher.
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $students->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const searchInput = document.getElementById('studentSearch');
        const chips = document.querySelectorAll('[data-filter]');
        const cards = document.querySelectorAll('[data-student-card]');

        if (!cards || cards.length === 0) return;

        let activeFilter = 'all';

        const applyFilters = () => {
            const q = (searchInput && searchInput.value ? searchInput.value : '').toLowerCase().trim();

            cards.forEach((card) => {
                const key = card.getAttribute('data-filter') || 'other';
                const hay = card.getAttribute('data-search') || '';
                const matchFilter = (activeFilter === 'all') || (key === activeFilter);
                const matchText = !q || hay.indexOf(q) !== -1;
                card.parentElement.style.display = (matchFilter && matchText) ? '' : 'none';
            });
        };

        chips.forEach((chip) => {
            chip.addEventListener('click', () => {
                chips.forEach(c => c.classList.remove('active'));
                chip.classList.add('active');
                activeFilter = chip.getAttribute('data-filter') || 'all';
                applyFilters();
            });
        });

        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
        }

        applyFilters();
    })();
</script>
@endpush
