@extends('layouts.admin')

@section('title', 'Détails du TP Assigné')

@push('styles')
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
<style>
    .detail-card {
        background: var(--form-surface);
        border: 1px solid var(--form-border);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        backdrop-filter: blur(10px);
    }
    
    .detail-header {
        border-bottom: 1px solid var(--form-border);
        padding-bottom: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .detail-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--form-text);
        margin-bottom: 0.5rem;
    }
    
    .detail-meta {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
        color: var(--form-text-muted);
    }
    
    .meta-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(56, 189, 248, 0.1);
        border: 1px solid rgba(56, 189, 248, 0.3);
        border-radius: 8px;
        font-size: 0.9rem;
    }
    
    .meta-badge i {
        color: var(--form-primary);
    }
    
    .detail-section {
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--form-text);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .section-title i {
        color: var(--form-primary);
    }
    
    .description-content {
        color: var(--form-text);
        line-height: 1.8;
        padding: 1.5rem;
        background: rgba(15, 23, 42, 0.5);
        border-radius: 12px;
        border: 1px solid var(--form-border);
    }
    
    .students-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
    }
    
    .student-card {
        background: rgba(15, 23, 42, 0.5);
        border: 1px solid var(--form-border);
        border-radius: 12px;
        padding: 1rem;
        transition: all 0.3s ease;
    }
    
    .student-card:hover {
        border-color: var(--form-primary);
        transform: translateY(-3px);
    }
    
    .student-name {
        font-weight: 600;
        color: var(--form-text);
        margin-bottom: 0.5rem;
    }
    
    .student-info {
        font-size: 0.875rem;
        color: var(--form-text-muted);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.35rem;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .status-assigned {
        background: rgba(56, 189, 248, 0.2);
        color: #38bdf8;
    }
    
    .status-submitted {
        background: rgba(251, 191, 36, 0.2);
        color: #fbbf24;
    }
    
    .status-validated {
        background: rgba(16, 185, 129, 0.2);
        color: #10b981;
    }
    
    .status-rejected {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }
</style>
@endpush

@section('content')

<div class="interactive-dashboard-form">
    <!-- Bouton retour -->
    <div class="mb-4">
        <a href="{{ route('admin.travaux.assigned') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Retour à la liste
        </a>
    </div>

    <!-- En-tête du TP -->
    <div class="detail-card">
        <div class="detail-header">
            <h1 class="detail-title">{{ $assignment->title }}</h1>
            <div class="detail-meta">
                <div class="meta-badge">
                    <i class="fas fa-calendar"></i>
                    <span>Assigné le {{ \Carbon\Carbon::parse($assignment->created_at)->format('d/m/Y') }}</span>
                </div>
                <div class="meta-badge">
                    <i class="fas fa-clock"></i>
                    <span>Échéance: {{ \Carbon\Carbon::parse($assignment->deadline)->format('d/m/Y à H:i') }}</span>
                </div>
                <div class="meta-badge">
                    <i class="fas fa-graduation-cap"></i>
                    <span>{{ $assignment->formation === 'all' ? 'Toutes les classes' : $assignment->formation }}</span>
                </div>
                <span class="status-badge status-{{ $assignment->status }}">
                    @if($assignment->status === 'assigned')
                        <i class="fas fa-clock"></i>En cours
                    @elseif($assignment->status === 'submitted')
                        <i class="fas fa-upload"></i>Soumis
                    @elseif($assignment->status === 'validated')
                        <i class="fas fa-check-circle"></i>Validé
                    @elseif($assignment->status === 'rejected')
                        <i class="fas fa-times-circle"></i>Rejeté
                    @endif
                </span>
            </div>
        </div>

        <!-- Description -->
        <div class="detail-section">
            <h2 class="section-title">
                <i class="fas fa-align-left"></i>
                Description du TP
            </h2>
            <div class="description-content">
                {!! $assignment->description !!}
            </div>
        </div>

        <!-- Fichiers rattachés -->
        @if($files->isNotEmpty())
            <div class="detail-section">
                <h2 class="section-title">
                    <i class="fas fa-paperclip"></i>
                    Fichiers rattachés ({{ $files->count() }})
                </h2>
                <div class="students-grid">
                    @foreach($files as $file)
                        <div class="student-card" style="cursor: pointer;" onclick="window.open('{{ asset('storage/' . $file->file_path) }}', '_blank')">
                            <div style="text-align: center; margin-bottom: 1rem;">
                                @if(str_contains($file->file_type ?? '', 'image'))
                                    <!-- Afficher l'image réelle -->
                                    <img src="{{ asset('storage/' . $file->file_path) }}" 
                                         alt="{{ $file->file_name }}" 
                                         style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <i class="fas fa-file-image" style="font-size: 3rem; color: #10b981; display: none;"></i>
                                @elseif(str_contains($file->file_type ?? '', 'pdf'))
                                    <i class="fas fa-file-pdf" style="font-size: 3rem; color: #ef4444;"></i>
                                @else
                                    <i class="fas fa-file" style="font-size: 3rem; color: var(--form-primary);"></i>
                                @endif
                            </div>
                            <div class="student-name" style="text-align: center; font-size: 0.9rem;">
                                {{ $file->file_name }}
                            </div>
                            <div class="student-info" style="justify-content: center;">
                                <i class="fas fa-hdd"></i>
                                <span>{{ number_format($file->file_size / 1024, 2) }} Ko</span>
                            </div>
                            <div class="student-info" style="justify-content: center;">
                                <i class="fas fa-download"></i>
                                <span style="color: var(--form-primary);">Cliquer pour télécharger</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Étudiants assignés -->
    <div class="detail-card">
        <div class="detail-section">
            <h2 class="section-title">
                <i class="fas fa-users"></i>
                Étudiants assignés ({{ $students->count() }})
            </h2>
            
            <div class="students-grid">
                @foreach($students as $student)
                    <div class="student-card">
                        <div class="student-name">
                            {{ $student->student_first_name }} {{ $student->student_last_name }}
                        </div>
                        <div class="student-info">
                            <i class="fas fa-graduation-cap"></i>
                            <span>{{ $student->program }}</span>
                        </div>
                        <div class="student-info">
                            <i class="fas fa-circle {{ $student->status === 'assigned' ? 'text-info' : ($student->status === 'submitted' ? 'text-warning' : 'text-success') }}"></i>
                            <span>
                                @if($student->status === 'assigned')
                                    Non soumis
                                @elseif($student->status === 'submitted')
                                    Soumis
                                @elseif($student->status === 'validated')
                                    Validé
                                @else
                                    {{ $student->status }}
                                @endif
                            </span>
                        </div>
                        @if($student->submitted_at)
                            <div class="student-info">
                                <i class="fas fa-calendar-check"></i>
                                <span>Soumis le {{ \Carbon\Carbon::parse($student->submitted_at)->format('d/m/Y') }}</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Statistiques de soumission -->
    <div class="row g-4">
        <div class="col-md-4">
            <div class="detail-card text-center">
                <div style="font-size: 3rem; color: #38bdf8; margin-bottom: 0.5rem;">
                    {{ $stats['assigned'] }}
                </div>
                <div style="color: var(--form-text-muted); font-size: 0.9rem;">
                    <i class="fas fa-clock me-2"></i>En attente
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="detail-card text-center">
                <div style="font-size: 3rem; color: #fbbf24; margin-bottom: 0.5rem;">
                    {{ $stats['submitted'] }}
                </div>
                <div style="color: var(--form-text-muted); font-size: 0.9rem;">
                    <i class="fas fa-upload me-2"></i>Soumis
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="detail-card text-center">
                <div style="font-size: 3rem; color: #10b981; margin-bottom: 0.5rem;">
                    {{ $stats['validated'] }}
                </div>
                <div style="color: var(--form-text-muted); font-size: 0.9rem;">
                    <i class="fas fa-check-circle me-2"></i>Validés
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
