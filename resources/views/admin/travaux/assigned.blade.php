@extends('layouts.admin')

@section('title', 'TP Assignés par Formation')

@push('styles')
<style>
    /* Palette Admin */
    :root {
        --blue-dark: #1e3c72;
        --blue-light: #4fc3f7;
        --orange: #ff9800;
        --cyan: #26c6da;
        --violet: #9c27b0;
    }

    .page-container {
        padding: 2rem;
        background: #f5f7fa;
        min-height: 100vh;
    }

    /* Statistiques globales */
    .global-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-box {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: transform 0.3s;
    }

    .stat-box:hover {
        transform: translateY(-5px);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        margin: 0.5rem 0;
    }

    .stat-label {
        color: #666;
        font-size: 0.9rem;
    }

    /* Blocs de formation */
    .formation-section {
        background: white;
        border-radius: 16px;
        margin-bottom: 2rem;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .formation-header {
        padding: 2rem;
        color: white;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s;
    }

    .formation-header:hover {
        opacity: 0.95;
        padding-left: 2.5rem;
    }

    .formation-left {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .formation-icon-big {
        width: 60px;
        height: 60px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
    }

    .formation-info h2 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 700;
    }

    .formation-info p {
        margin: 0.5rem 0 0 0;
        opacity: 0.95;
    }

    .formation-right {
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .mini-stats {
        display: flex;
        gap: 1.5rem;
    }

    .mini-stat {
        background: rgba(255,255,255,0.2);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .chevron {
        font-size: 1.5rem;
        transition: transform 0.3s;
    }

    .chevron.rotated {
        transform: rotate(180deg);
    }

    /* Contenu des TP */
    .formation-content {
        padding: 2rem;
        background: #f8f9fa;
        display: none;
    }

    .formation-content.show {
        display: block;
    }

    .tp-item {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 1rem;
        border-left: 4px solid;
        transition: all 0.3s;
    }

    .tp-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .tp-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }

    .tp-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .status-badge {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-assigned {
        background: #fff3e0;
        color: #ff9800;
    }

    .status-submitted {
        background: #e3f2fd;
        color: #2196f3;
    }

    .status-validated {
        background: #e8f5e9;
        color: #4caf50;
    }

    .tp-meta {
        display: flex;
        gap: 2rem;
        margin: 1rem 0;
        color: #666;
        font-size: 0.9rem;
    }

    .tp-description {
        color: #555;
        line-height: 1.6;
        margin: 1rem 0;
    }

    .students-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin: 1rem 0;
    }

    .student-tag {
        background: #e3f2fd;
        color: #1976d2;
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: all 0.3s ease;
    }

    /* Étudiant qui a soumis le TP */
    .student-tag.submitted {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        border: 2px solid #10b981;
    }

    .student-tag.submitted:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    /* Étudiant qui n'a pas encore soumis */
    .student-tag.pending {
        background: #fff3cd;
        color: #856404;
        border: 2px solid #ffc107;
        font-weight: 500;
    }

    .student-tag.pending:hover {
        background: #ffc107;
        color: white;
    }

    /* Étudiant avec TP validé */
    .student-tag.validated {
        background: linear-gradient(135deg, #4caf50, #388e3c);
        color: white;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
        border: 2px solid #4caf50;
    }

    .student-tag.validated:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
    }

    /* Étudiant avec TP rejeté */
    .student-tag.rejected {
        background: linear-gradient(135deg, #ef5350, #e53935);
        color: white;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(239, 83, 80, 0.3);
        border: 2px solid #ef5350;
    }

    .student-tag.rejected:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 83, 80, 0.4);
    }

    /* Sections séparées pour les étudiants */
    .students-section {
        margin: 1.5rem 0;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 12px;
    }

    .students-section-title {
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .students-section-title.submitted-section {
        color: #10b981;
    }

    .students-section-title.pending-section {
        color: #856404;
    }

    .tp-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .btn-view {
        background: linear-gradient(135deg, var(--blue-dark), var(--blue-light));
        color: white;
        padding: 0.6rem 1.5rem;
        border-radius: 25px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .btn-edit {
        background: linear-gradient(135deg, #ff9800, #fb8c00);
        color: white;
        padding: 0.6rem 1.5rem;
        border-radius: 25px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
    }

    .btn-delete {
        background: linear-gradient(135deg, #e53935, #c62828);
        color: white;
        padding: 0.6rem 1.5rem;
        border-radius: 25px;
        border: none;
        cursor: pointer;
        font-weight: 600;
    }

    .btn-assign {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 1rem 2rem;
        border-radius: 30px;
        border: none;
        cursor: pointer;
        font-weight: 700;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        transition: all 0.3s ease;
    }

    .btn-assign:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
    }

    .empty-state {
        text-align: center;
        padding: 4rem;
        color: #999;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<div class="page-container">
    <!-- En-tête avec bouton -->
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="color: #2c3e50; font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">
                <i class="fas fa-clipboard-list"></i> TP Assignés par Formation
            </h1>
            <p style="color: #666; margin: 0;">Gérez les travaux pratiques organisés par formation</p>
        </div>
        <a href="{{ route('admin.travaux.to-send') }}" class="btn-assign" style="text-decoration: none;">
            <i class="fas fa-plus"></i> Envoyer un nouveau TP
        </a>
    </div>

    <!-- Statistiques globales -->
    <div class="global-stats">
        <div class="stat-box" style="border-left: 4px solid var(--blue-dark);">
            <div class="stat-label">Total TP</div>
            <div class="stat-number" style="color: var(--blue-dark);">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-box" style="border-left: 4px solid var(--orange);">
            <div class="stat-label">En cours</div>
            <div class="stat-number" style="color: var(--orange);">{{ $stats['assigned'] }}</div>
        </div>
        <div class="stat-box" style="border-left: 4px solid var(--cyan);">
            <div class="stat-label">Soumis</div>
            <div class="stat-number" style="color: var(--cyan);">{{ $stats['submitted'] }}</div>
        </div>
        <div class="stat-box" style="border-left: 4px solid #4caf50;">
            <div class="stat-label">Validés</div>
            <div class="stat-number" style="color: #4caf50;">{{ $stats['validated'] }}</div>
        </div>
    </div>

    <!-- Sections par formation -->
    @if($tpAssignmentsByFormation->isEmpty())
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>Aucun TP assigné</h3>
            <p>Commencez par envoyer des TP aux étudiants</p>
            <a href="{{ route('admin.travaux.to-send') }}" class="btn-view" style="display: inline-block; margin-top: 1rem;">
                <i class="fas fa-plus"></i> Envoyer un TP
            </a>
        </div>
    @else
        @php
            $formationColors = [
                'Design Graphique' => ['bg' => 'linear-gradient(135deg, #1e3c72, #2a5298)', 'icon' => 'fas fa-palette', 'color' => '#1e3c72'],
                'Community Management' => ['bg' => 'linear-gradient(135deg, #4fc3f7, #29b6f6)', 'icon' => 'fas fa-users', 'color' => '#4fc3f7'],
                'Gestion Informatique' => ['bg' => 'linear-gradient(135deg, #ff9800, #fb8c00)', 'icon' => 'fas fa-laptop-code', 'color' => '#ff9800'],
                'Intelligence Artificielle' => ['bg' => 'linear-gradient(135deg, #26c6da, #00acc1)', 'icon' => 'fas fa-brain', 'color' => '#26c6da'],
            ];
        @endphp

        @foreach($tpAssignmentsByFormation as $formation => $tps)
            @php
                $style = $formationColors[$formation] ?? ['bg' => 'linear-gradient(135deg, #9c27b0, #7b1fa2)', 'icon' => 'fas fa-graduation-cap', 'color' => '#9c27b0'];
                $stats = $statsByFormation[$formation] ?? ['total' => 0, 'assigned' => 0, 'submitted' => 0, 'validated' => 0];
                $formationId = str_replace(' ', '_', strtolower($formation));
                $tpsByTitle = $tps->groupBy('title');
            @endphp

            <div class="formation-section">
                <!-- Header cliquable -->
                <div class="formation-header" style="background: {{ $style['bg'] }};" onclick="toggleFormation('{{ $formationId }}')">
                    <div class="formation-left">
                        <div class="formation-icon-big">
                            <i class="{{ $style['icon'] }}"></i>
                        </div>
                        <div class="formation-info">
                            <h2>{{ $formation === 'all' ? 'Toutes les classes' : $formation }}</h2>
                            <p>{{ $stats['total'] }} TP • {{ $tps->unique('student_id')->count() }} étudiants</p>
                        </div>
                    </div>
                    <div class="formation-right">
                        <div class="mini-stats">
                            <div class="mini-stat">
                                <i class="fas fa-clock"></i>
                                <span>{{ $stats['assigned'] }}</span>
                            </div>
                            <div class="mini-stat">
                                <i class="fas fa-upload"></i>
                                <span>{{ $stats['submitted'] }}</span>
                            </div>
                            <div class="mini-stat">
                                <i class="fas fa-check"></i>
                                <span>{{ $stats['validated'] }}</span>
                            </div>
                        </div>
                        <div class="chevron" id="chevron-{{ $formationId }}">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                </div>

                <!-- Contenu des TP -->
                <div class="formation-content show" id="content-{{ $formationId }}">
                    @foreach($tpsByTitle as $title => $tpItems)
                        <div class="tp-item" style="border-left-color: {{ $style['color'] }};">
                            <div class="tp-header">
                                <div class="tp-title">
                                    <i class="fas fa-file-alt"></i> {{ $title }}
                                </div>
                                <span class="status-badge status-{{ $tpItems->first()->status }}">
                                    {{ ucfirst($tpItems->first()->status) }}
                                </span>
                            </div>

                            <div class="tp-meta">
                                <span><i class="fas fa-calendar"></i> Envoyé le {{ \Carbon\Carbon::parse($tpItems->first()->created_at)->format('d/m/Y') }}</span>
                                <span><i class="fas fa-clock"></i> Échéance: {{ \Carbon\Carbon::parse($tpItems->first()->deadline)->format('d/m/Y') }}</span>
                                <span><i class="fas fa-users"></i> {{ $tpItems->count() }} étudiant(s)</span>
                            </div>

                            <div class="tp-description">
                                {{ Str::limit(strip_tags($tpItems->first()->description), 200) }}
                            </div>

                            @php
                                // Séparer les étudiants par statut
                                $submittedStudents = $tpItems->whereIn('status', ['submitted', 'validated', 'rejected']);
                                $pendingStudents = $tpItems->where('status', 'assigned');
                            @endphp

                            <!-- Étudiants ayant soumis -->
                            @if($submittedStudents->count() > 0)
                            <div class="students-section">
                                <div class="students-section-title submitted-section">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Étudiants ayant soumis ({{ $submittedStudents->count() }})</span>
                                </div>
                                <div class="students-list">
                                    @foreach($submittedStudents as $tp)
                                        <span class="student-tag {{ $tp->status }}">
                                            @if($tp->status === 'validated')
                                                <i class="fas fa-check-double"></i>
                                            @elseif($tp->status === 'rejected')
                                                <i class="fas fa-times-circle"></i>
                                            @else
                                                <i class="fas fa-upload"></i>
                                            @endif
                                            {{ $tp->student_first_name }} {{ $tp->student_last_name }}
                                            @if($tp->status === 'validated')
                                                <span style="font-size: 0.7rem; opacity: 0.9;">(Validé)</span>
                                            @elseif($tp->status === 'rejected')
                                                <span style="font-size: 0.7rem; opacity: 0.9;">(Rejeté)</span>
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Étudiants n'ayant pas encore soumis -->
                            @if($pendingStudents->count() > 0)
                            <div class="students-section">
                                <div class="students-section-title pending-section">
                                    <i class="fas fa-clock"></i>
                                    <span>En attente de soumission ({{ $pendingStudents->count() }})</span>
                                </div>
                                <div class="students-list">
                                    @foreach($pendingStudents as $tp)
                                        <span class="student-tag pending">
                                            <i class="fas fa-hourglass-half"></i>
                                            {{ $tp->student_first_name }} {{ $tp->student_last_name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="tp-actions">
                                <a href="{{ route('admin.travaux.assignment.detail', ['title' => urlencode($title)]) }}" class="btn-view">
                                    <i class="fas fa-eye"></i> Voir détails
                                </a>
                                <a href="{{ route('admin.travaux.assignment.edit', ['title' => urlencode($title)]) }}" class="btn-edit">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                <form action="{{ route('admin.travaux.assignment.delete', ['title' => urlencode($title)]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer ce TP pour {{ $tpItems->count() }} étudiant(s) ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif
</div>

<script>
function toggleFormation(formationId) {
    const content = document.getElementById('content-' + formationId);
    const chevron = document.getElementById('chevron-' + formationId);

    if (content.classList.contains('show')) {
        content.classList.remove('show');
        chevron.classList.add('rotated');
    } else {
        content.classList.add('show');
        chevron.classList.remove('rotated');
    }
}
</script>
@endsection
