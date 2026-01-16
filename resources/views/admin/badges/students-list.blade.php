@extends('layouts.admin')

@section('title', $title)

@push('styles')
<style>
    .page-card {
        background-color: #1e293b;
        border: 1px solid #334155;
        border-radius: 14px;
    }
    .page-card .card-header {
        background-color: #0f172a;
        border-bottom: 1px solid #334155;
        border-top-left-radius: 14px;
        border-top-right-radius: 14px;
    }

    .search-input {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.12);
        color: #fff;
    }

    .search-input::placeholder {
        color: rgba(255,255,255,0.55);
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-white">{{ $title }}</h1>
            <div class="text-white-50">Liste complète des étudiants actifs (vue dédiée)</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.badges.students.active') }}" class="btn btn-sm btn-outline-light">Retour</a>
        </div>
    </div>

    <div class="card page-card mb-3">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white"><i class="fas fa-list me-2"></i>Étudiants</h5>
                <div class="d-flex gap-2 align-items-center">
                    <span class="badge bg-secondary">{{ $students->total() }} total</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3 align-items-end mb-3">
                <div class="col-lg-7">
                    <label class="form-label text-white-50">Rechercher</label>
                    <input id="studentSearch" type="text" class="form-control search-input" placeholder="Nom, ID, pays, formation..." />
                </div>
                <div class="col-lg-5">
                    <label class="form-label text-white-50">Filtrer par formation</label>
                    <select id="formationFilter" class="form-select search-input">
                        <option value="all">Toutes</option>
                        <option value="dg">Design Graphique</option>
                        <option value="cm">Community Management</option>
                        <option value="dgcm">Design + CM</option>
                        <option value="other">Autres</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>ID</th>
                            <th>Formation</th>
                            <th>Pays</th>
                            <th>Projets</th>
                            <th>Inscription</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $idx => $student)
                            @php
                                $progRaw = (string) ($student->program ?? '');
                                $specRaw = (string) ($student->specialization ?? '');
                                $formationLabel = trim($progRaw) !== '' ? $progRaw : (trim($specRaw) !== '' ? $specRaw : 'Formation EVC');

                                $prog = strtolower($progRaw);
                                $hasDesign = strpos($prog, 'design') !== false;
                                $hasCommunity = (strpos($prog, 'community') !== false) || (strpos($prog, 'manager') !== false) || (strpos($prog, 'management') !== false);
                                $filterKey = $hasDesign && $hasCommunity ? 'dgcm' : ($hasCommunity ? 'cm' : ($hasDesign ? 'dg' : 'other'));

                                $searchHaystack = strtolower(
                                    trim(
                                        ($student->first_name ?? '') . ' ' .
                                        ($student->last_name ?? '') . ' ' .
                                        ($student->student_id ?? '') . ' ' .
                                        ($student->country ?? '') . ' ' .
                                        $formationLabel
                                    )
                                );
                            @endphp
                            <tr data-student-row data-filter="{{ $filterKey }}" data-search="{{ $searchHaystack }}">
                                <td>{{ $students->firstItem() + $idx }}</td>
                                <td class="fw-semibold">{{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }}</td>
                                <td>{{ $student->student_id ?? '—' }}</td>
                                <td>{{ $formationLabel }}</td>
                                <td>{{ $student->country ?? '—' }}</td>
                                <td>{{ (int) ($student->projects_count ?? 0) }}</td>
                                <td>
                                    @if(!empty($student->registration_sort_date))
                                        {{ \Carbon\Carbon::parse($student->registration_sort_date)->format('d/m/Y') }}
                                    @elseif(!empty($student->created_at))
                                        {{ \Carbon\Carbon::parse($student->created_at)->format('d/m/Y') }}
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-white-50 py-4">Aucun étudiant à afficher.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
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
        const formationFilter = document.getElementById('formationFilter');
        const rows = document.querySelectorAll('[data-student-row]');

        if (!rows || rows.length === 0) return;

        const applyFilters = () => {
            const q = (searchInput && searchInput.value ? searchInput.value : '').toLowerCase().trim();
            const f = (formationFilter && formationFilter.value) ? formationFilter.value : 'all';

            rows.forEach((row) => {
                const key = row.getAttribute('data-filter') || 'other';
                const hay = row.getAttribute('data-search') || '';
                const matchFilter = (f === 'all') || (key === f);
                const matchText = !q || hay.indexOf(q) !== -1;
                row.style.display = (matchFilter && matchText) ? '' : 'none';
            });
        };

        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
        }
        if (formationFilter) {
            formationFilter.addEventListener('change', applyFilters);
        }

        applyFilters();
    })();
</script>
@endpush
