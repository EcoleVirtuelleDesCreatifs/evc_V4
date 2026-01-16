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
                            @endphp
                            <tr>
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
