@extends('layouts.ki-admin')

@section('title', 'Traiter le projet')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #833AB4, #C13584, #E1306C);
        padding: 1.75rem;
        border-radius: 20px;
        color: white;
        margin-bottom: 1.5rem;
        box-shadow: 0 10px 30px rgba(131, 58, 180, 0.35);
    }

    .card-soft {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(15, 23, 42, 0.06);
    }

    .muted {
        color: #64748b;
    }

    .file-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.9rem;
        border-radius: 999px;
        background: rgba(225, 48, 108, 0.08);
        border: 1px solid rgba(225, 48, 108, 0.18);
        color: #0f172a;
        text-decoration: none;
        font-weight: 600;
        margin: 0.35rem 0.35rem 0 0;
    }

    .btn-primary-night {
        background: linear-gradient(135deg, #833AB4, #E1306C);
        border: none;
        color: #fff;
        border-radius: 999px;
        padding: 0.75rem 1.25rem;
        font-weight: 700;
    }

    .btn-primary-night:hover {
        background: linear-gradient(135deg, #C13584, #F56040);
        color: #fff;
    }

    .btn-outline-night {
        border-radius: 999px;
        border: 2px solid rgba(131, 58, 180, 0.25);
        color: #833AB4;
        background: transparent;
        padding: 0.75rem 1.25rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-outline-night:hover {
        background: rgba(131, 58, 180, 0.06);
        color: #833AB4;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <div>
                <h3 class="mb-1" style="font-weight: 800;">Traiter le projet</h3>
                <div class="muted" style="color: rgba(255,255,255,0.85);">
                    Publie ton travail (titre, description, images/PDF)
                </div>
            </div>
            <a href="{{ route($formationPrefix . '.todo.index') }}" class="btn-outline-night" style="border-color: rgba(255,255,255,0.35); color: #fff;">
                <i class="fas fa-arrow-left"></i>
                Retour
            </a>
        </div>
    </div>

    @if(isset($error))
        <div class="alert alert-danger">
            {{ $error }}
        </div>
    @endif

    @if(!$assignedProject)
        <div class="card-soft p-4">
            <div class="muted">Aucun projet à traiter.</div>
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card-soft p-4 h-100">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="fas fa-file-alt" style="color: #C13584;"></i>
                        <div style="font-weight: 800; font-size: 1.05rem;">Brief du projet</div>
                    </div>
                    <div class="muted mb-3">Pièces jointes envoyées par l'administration</div>

                    @if($briefFiles->isEmpty())
                        <div class="muted">Aucun fichier joint.</div>
                    @else
                        <div>
                            @foreach($briefFiles as $f)
                                <a class="file-pill" href="{{ $f->url }}" target="_blank">
                                    <i class="fas fa-download" style="color: #E1306C;"></i>
                                    <span>{{ $f->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <hr style="margin: 1.25rem 0;">

                    <div class="muted" style="font-size: 0.95rem;">
                        <strong style="color: #0f172a;">Deadline :</strong>
                        @if(!empty($assignedProject->deadline))
                            @php
                                try { $d = \Carbon\Carbon::parse($assignedProject->deadline)->format('d/m/Y'); } catch (\Exception $e) { $d = (string) $assignedProject->deadline; }
                            @endphp
                            {{ $d }}
                        @else
                            N/A
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card-soft p-4">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="fas fa-upload" style="color: #f97316;"></i>
                        <div style="font-weight: 800; font-size: 1.05rem;">Publier ton projet</div>
                    </div>
                    <div class="muted mb-3">Ajoute ta description et tes fichiers (images ou PDF)</div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="todo-traiter-form" method="POST" action="{{ route($formationPrefix . '.todo.traiter.store', $assignedProject->id) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 700;">Titre *</label>
                            <input type="text" class="form-control" name="title" value="{{ old('title', $assignedProject->title) }}" required maxlength="255">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 700;">Liens du Projet</label>
                            <input type="url" class="form-control" name="project_link" placeholder="https://..." value="{{ old('project_link', ($isTpAssignment ?? false) ? ($assignedProject->submission_link ?? '') : ($assignedProject->link ?? '')) }}" maxlength="2000">
                            <div class="muted" style="margin-top: 0.5rem; font-size: 0.9rem;">Ajoute un lien si tu n’as pas de fichiers à envoyer (Drive, Behance, etc.).</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 700;">Fichiers (images / PDF)</label>
                            <input type="file" class="form-control" name="files[]" multiple accept="image/*,application/pdf">
                            <div class="muted" style="margin-top: 0.5rem; font-size: 0.9rem;">Max 10 Mo par fichier.</div>
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn-primary-night">
                                <i class="fas fa-paper-plane me-2"></i>
                                Publier
                            </button>
                            <a href="{{ route($formationPrefix . '.todo.index') }}" class="btn-outline-night">
                                Annuler
                            </a>
                        </div>
                        <div class="alert alert-danger" id="todo-traiter-form-error" style="display:none; margin-top: 1rem;"></div>
                    </form>

                    <script>
                    (function todoTraiterClientGuard(){
                        var form = document.getElementById('todo-traiter-form');
                        if(!form) return;
                        var linkInput = form.querySelector('[name="project_link"]');
                        var filesInput = form.querySelector('[name="files[]"]');
                        var errorBox = document.getElementById('todo-traiter-form-error');
                        form.addEventListener('submit', function(e){
                            var link = (linkInput && linkInput.value ? linkInput.value.trim() : '');
                            var hasFiles = !!(filesInput && filesInput.files && filesInput.files.length);
                            if(!hasFiles && !link){
                                e.preventDefault();
                                if(errorBox){
                                    errorBox.textContent = "Veuillez ajouter au moins un fichier OU un lien du projet.";
                                    errorBox.style.display = "block";
                                }
                            }
                        });
                    })();
                    </script>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
