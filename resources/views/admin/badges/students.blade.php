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
        background: linear-gradient(180deg, rgba(255,255,255,0.06) 0%, rgba(255,255,255,0.03) 100%);
        border: 1px solid rgba(255,255,255,0.10);
        border-radius: 18px;
        overflow: hidden;
        height: 100%;
        box-shadow: 0 10px 26px rgba(0,0,0,0.28);
    }

    .student-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 40px rgba(0,0,0,0.35);
        border-color: rgba(79,195,247,0.55);
        transition: all .18s ease;
    }

    .student-card-header {
        background: rgba(15,23,42,0.9);
        border-bottom: 1px solid rgba(255,255,255,0.10);
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
        width: 62px;
        height: 62px;
        border-radius: 999px;
        object-fit: cover;
        background: rgba(79,195,247,0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 900;
        flex-shrink: 0;
        overflow: hidden;
        border: 2px solid rgba(255,255,255,0.16);
    }

    .student-meta {
        min-width: 0;
        flex: 1;
    }

    .student-name {
        color: #fff;
        font-weight: 800;
        margin: 0;
        font-size: 1.05rem;
        white-space: normal;
        overflow: visible;
    }

    .student-sub {
        color: rgba(255,255,255,0.65);
        font-size: 0.9rem;
        margin: 0;
        white-space: normal;
        overflow: visible;
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

    @if($status === 'active')
        <div class="stats-grid">
            <div class="stat-card">
                <p class="stat-title">Étudiants actifs</p>
                <p class="stat-value">{{ $stats['total'] ?? 0 }}</p>
            </div>
            <div class="stat-card">
                <p class="stat-title">Design Graphique</p>
                <p class="stat-value">{{ $stats['design_graphique'] ?? 0 }}</p>
            </div>
            <div class="stat-card">
                <p class="stat-title">Community Management</p>
                <p class="stat-value">{{ $stats['community_management'] ?? 0 }}</p>
            </div>
            <div class="stat-card">
                <p class="stat-title">Design + CM</p>
                <p class="stat-value">{{ $stats['design_graphique_cm'] ?? 0 }}</p>
            </div>
        </div>

        <div class="row g-2 align-items-end mb-3">
            <div class="col-lg-5">
                <label class="form-label text-white-50">Rechercher</label>
                <input id="studentSearch" type="text" class="form-control search-input" placeholder="Nom, email, pays, formation..." />
            </div>
            <div class="col-lg-4">
                <label class="form-label text-white-50">Trier</label>
                <div class="d-flex gap-2">
                    @php
                        $sortValue = $sort ?? request('sort', 'date');
                        $dirValue = $dir ?? request('dir', 'desc');
                    @endphp
                    <select id="sortSelect" class="form-select search-input">
                        <option value="date" {{ $sortValue === 'date' ? 'selected' : '' }}>Date d'inscription</option>
                        <option value="projects" {{ $sortValue === 'projects' ? 'selected' : '' }}>Nombre de projets</option>
                    </select>
                    <select id="dirSelect" class="form-select search-input" style="max-width:160px;">
                        <option value="desc" {{ $dirValue === 'desc' ? 'selected' : '' }}>Décroissant</option>
                        <option value="asc" {{ $dirValue === 'asc' ? 'selected' : '' }}>Croissant</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-3">
                <label class="form-label text-white-50">Filtrer par formation</label>
                <div class="d-flex flex-wrap gap-2">
                    <span class="chip active" data-filter="all">Tous</span>
                    <span class="chip" data-filter="dg">DG</span>
                    <span class="chip" data-filter="cm">CM</span>
                    <span class="chip" data-filter="dgcm">DG+CM</span>
                </div>
            </div>
        </div>
    @else
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
    @endif

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
            <div class="col-xl-4 col-lg-4 col-md-6">
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

                        @if($status === 'active')
                            <div class="meta-row">
                                <div class="meta-pill">
                                    <strong>Inscription</strong>
                                    <div>
                                        @if(!empty($student->registration_sort_date))
                                            {{ \Carbon\Carbon::parse($student->registration_sort_date)->format('d/m/Y') }}
                                        @elseif(!empty($student->created_at))
                                            {{ \Carbon\Carbon::parse($student->created_at)->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                                <div class="meta-pill">
                                    <strong>Projets réalisés</strong>
                                    <div>{{ (int) ($student->projects_count ?? 0) }}</div>
                                </div>
                            </div>
                        @endif

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
                            @if($status === 'active')
                                @php
                                    $exportPhoto = (!empty($student->profile_photo) && !empty($photoUrl)) ? $photoUrl : null;
                                    $exportPayload = [
                                        'id' => (int) ($student->id ?? 0),
                                        'full_name' => trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')),
                                        'email' => (string) ($student->email ?? ''),
                                        'country' => (string) ($student->country ?? ''),
                                        'program' => (string) ($student->program ?? ''),
                                        'photo' => $exportPhoto,
                                        'projects_count' => (int) ($student->projects_count ?? 0),
                                        'registered_at' => (string) ($student->registration_sort_date ?? $student->created_at ?? ''),
                                        'theme' => $filterKey,
                                    ];
                                @endphp
                                <button type="button" class="btn btn-sm export-btn" data-export-card data-export='@json($exportPayload)'>Télécharger la carte</button>
                            @endif
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
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
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

        const sortSelect = document.getElementById('sortSelect');
        const dirSelect = document.getElementById('dirSelect');
        const applySort = () => {
            if (!sortSelect || !dirSelect) return;
            const url = new URL(window.location.href);
            url.searchParams.set('sort', sortSelect.value);
            url.searchParams.set('dir', dirSelect.value);
            window.location.href = url.toString();
        };
        if (sortSelect && dirSelect) {
            sortSelect.addEventListener('change', applySort);
            dirSelect.addEventListener('change', applySort);
        }

        applyFilters();
    })();

    (function () {
        const buttons = document.querySelectorAll('[data-export-card]');
        if (!buttons || buttons.length === 0) return;

        const renderCard1080x1350 = (data) => {
            const wrap = document.createElement('div');
            wrap.style.width = '1080px';
            wrap.style.height = '1350px';
            wrap.style.position = 'fixed';
            wrap.style.left = '-99999px';
            wrap.style.top = '0';
            wrap.style.background = 'linear-gradient(135deg, #0b1220 0%, #111c34 50%, #0b1220 100%)';
            wrap.style.borderRadius = '38px';
            wrap.style.overflow = 'hidden';
            wrap.style.border = '2px solid rgba(255,255,255,0.12)';

            const theme = data.theme || 'dg';
            const themeBg = theme === 'cm'
                ? 'linear-gradient(135deg, rgba(131,58,180,0.75) 0%, rgba(193,53,132,0.75) 40%, rgba(225,48,108,0.55) 70%, rgba(245,96,64,0.45) 100%)'
                : (theme === 'dgcm'
                    ? 'linear-gradient(135deg, rgba(79,195,247,0.70) 0%, rgba(30,60,114,0.75) 60%, rgba(245,158,11,0.55) 100%)'
                    : 'linear-gradient(135deg, rgba(79,195,247,0.75) 0%, rgba(30,60,114,0.80) 80%)');

            wrap.innerHTML = `
                <div style="padding:70px 70px 52px; height:100%; display:flex; flex-direction:column;">
                    <div style="background:${themeBg}; border-radius:30px; padding:42px; display:flex; align-items:center; gap:34px; border:1px solid rgba(255,255,255,0.18); box-shadow:0 22px 60px rgba(0,0,0,0.35);">
                        <div style="width:180px; height:180px; border-radius:999px; overflow:hidden; border:4px solid rgba(255,255,255,0.22); background:rgba(255,255,255,0.12); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:900; font-size:54px;">
                            ${data.photo ? `<img src="${data.photo}" style="width:100%; height:100%; object-fit:cover;" />` : `${(data.full_name || 'ET').split(' ').map(p => p[0]).slice(0,2).join('').toUpperCase()}`}
                        </div>
                        <div style="min-width:0;">
                            <div style="color:#fff; font-weight:900; font-size:56px; line-height:1.05;">${data.full_name || ''}</div>
                            <div style="color:rgba(255,255,255,0.9); font-size:26px; margin-top:10px; word-break:break-word;">${data.email || ''}</div>
                        </div>
                    </div>

                    <div style="height:26px;"></div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">
                        <div style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.10); border-radius:26px; padding:28px;">
                            <div style="color:rgba(255,255,255,0.7); font-size:22px; text-transform:uppercase; letter-spacing:.08em; font-weight:800;">Pays</div>
                            <div style="color:#fff; font-size:32px; font-weight:900; margin-top:10px;">${data.country || ''}</div>
                        </div>
                        <div style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.10); border-radius:26px; padding:28px;">
                            <div style="color:rgba(255,255,255,0.7); font-size:22px; text-transform:uppercase; letter-spacing:.08em; font-weight:800;">Formation</div>
                            <div style="color:#fff; font-size:30px; font-weight:900; margin-top:10px; line-height:1.1;">${data.program || ''}</div>
                        </div>
                        <div style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.10); border-radius:26px; padding:28px;">
                            <div style="color:rgba(255,255,255,0.7); font-size:22px; text-transform:uppercase; letter-spacing:.08em; font-weight:800;">Projets réalisés</div>
                            <div style="color:#fff; font-size:54px; font-weight:900; margin-top:6px;">${data.projects_count ?? 0}</div>
                        </div>
                        <div style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.10); border-radius:26px; padding:28px;">
                            <div style="color:rgba(255,255,255,0.7); font-size:22px; text-transform:uppercase; letter-spacing:.08em; font-weight:800;">Inscription</div>
                            <div style="color:#fff; font-size:32px; font-weight:900; margin-top:10px;">${data.registered_at ? (new Date(data.registered_at).toLocaleDateString('fr-FR')) : ''}</div>
                        </div>
                    </div>

                    <div style="margin-top:auto; display:flex; justify-content:space-between; align-items:center; padding-top:40px;">
                        <div style="color:rgba(255,255,255,0.7); font-size:22px; font-weight:800;">Ecole Virtuelle Des Creatifs</div>
                        <div style="color:rgba(255,255,255,0.6); font-size:20px;">Badge étudiant</div>
                    </div>
                </div>
            `;

            document.body.appendChild(wrap);
            return wrap;
        };

        const downloadBlobUrl = (blobUrl, filename) => {
            const a = document.createElement('a');
            a.href = blobUrl;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            a.remove();
        };

        buttons.forEach((btn) => {
            btn.addEventListener('click', async () => {
                if (!window.html2canvas) {
                    alert('html2canvas non chargé');
                    return;
                }

                let data = {};
                try {
                    data = JSON.parse(btn.getAttribute('data-export') || '{}');
                } catch (e) {
                    data = {};
                }

                const card = renderCard1080x1350(data);
                try {
                    const canvas = await window.html2canvas(card, {
                        backgroundColor: null,
                        scale: 1,
                        width: 1080,
                        height: 1350,
                        useCORS: true,
                    });

                    canvas.toBlob((blob) => {
                        if (!blob) return;
                        const url = URL.createObjectURL(blob);
                        const safeName = (data.full_name || 'etudiant').toLowerCase().replace(/[^a-z0-9]+/g, '_');
                        downloadBlobUrl(url, `badge_${safeName}_1080x1350.png`);
                        setTimeout(() => URL.revokeObjectURL(url), 8000);
                    }, 'image/png');
                } catch (err) {
                    console.error(err);
                    alert('Impossible de générer l\'image.');
                } finally {
                    card.remove();
                }
            });
        });
    })();
</script>
@endpush
