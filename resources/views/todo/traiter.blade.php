@extends('layouts.ki-admin')

@section('title', 'Traiter le projet')

@push('styles')
<style>.instagram-header {
        background: linear-gradient(135deg, #2563eb 0%, #3b82f6 50%, #f97316 100%);
        border-radius: 24px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(131, 58, 180, 0.3);
        margin-bottom: 1.5rem;
    }

    .instagram-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1) rotate(0deg); }
        50% { transform: scale(1.1) rotate(5deg); }
    }

    .instagram-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(37, 99, 235, 0.1);
        transition: all 0.3s ease;
        border: 2px solid rgba(37, 99, 235, 0.1);
        background: white;
    }

    .instagram-card:hover {
        box-shadow: 0 8px 32px rgba(37, 99, 235, 0.2);
        border-color: rgba(37, 99, 235, 0.3);
    }

    .instagram-card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-bottom: 2px solid transparent;
        border-image: linear-gradient(90deg, #2563eb, #3b82f6, #f97316) 1;
        padding: 1.5rem;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
    }

    .instagram-btn {
        background: linear-gradient(135deg, #2563eb 0%, #f97316 100%);
        border: none;
        color: white;
        font-weight: 700;
        padding: 0.75rem 2rem;
        border-radius: 30px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(37, 99, 235, 0.3);
    }

    .instagram-btn:hover {
        background: linear-gradient(135deg, #3b82f6 0%, #fb923c 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(37, 99, 235, 0.4);
        color: white;
    }

    .btn-outline-secondary {
        border: 2px solid #dee2e6;
        color: #6c757d;
        font-weight: 600;
        border-radius: 30px;
        padding: 0.75rem 2rem;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background: #6c757d;
        border-color: #6c757d;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
    }

    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:hover {
        border-color: rgba(37, 99, 235, 0.3);
    }

    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.15);
    }

    #globalDropZone:hover {
        border-color: #2563eb !important;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(249, 115, 22, 0.1) 100%) !important;
        transform: scale(1.01);
    }

    #globalDropZone.dragover {
        border-color: #f97316 !important;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.15) 0%, rgba(249, 115, 22, 0.15) 100%) !important;
        transform: scale(1.02);
    }.file-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.9rem;
        border-radius: 999px;
        background: rgba(37, 99, 235, 0.08);
        border: 1px solid rgba(37, 99, 235, 0.18);
        color: #0f172a;
        text-decoration: none;
        font-weight: 600;
        margin: 0.35rem 0.35rem 0 0;
    }

    .muted {
        color: #64748b;
    }

    @media (max-width: 768px) {
        .instagram-header .card-body {
            padding: 1.5rem !important;
        }
        .instagram-btn, .btn-outline-secondary {
            padding: 0.6rem 0.8rem !important;
            font-size: 0.75rem !important;
            white-space: nowrap !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="instagram-header">
        <div class="card-body p-5 position-relative">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                <div class="d-flex align-items-center">
                    <div class="me-4">
                        <div style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 50%; display:flex; align-items:center; justify-content:center; backdrop-filter: blur(10px);">
                            <i class="fas fa-upload fa-2x"></i>
                        </div>
                    </div>
                    <div>
                        <h2 class="mb-2 fw-bold" style="font-size: 2rem;">Publier ton projet</h2>
                        <p class="mb-0 opacity-90" style="font-size: 1.05rem;">Ajoute ta description et tes fichiers (images ou PDF)</p>
                    </div>
                </div>

                <a href="{{ route($formationPrefix . '.todo.index') }}" class="btn btn-outline-secondary" style="background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.35); color: #fff;">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    @if(isset($error))
        <div class="alert alert-danger">
            {{ $error }}
        </div>
    @endif

    @if(!$assignedProject)
        <div class="card instagram-card">
            <div class="card-body p-4">
                <div class="muted">Aucun projet à traiter.</div>
            </div>
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card instagram-card h-100">
                    <div class="card-header instagram-card-header">
                        <h5 class="mb-0 fw-bold" style="background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            <i class="fas fa-file-alt me-2" style="background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                            Brief du projet
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="muted mb-3">Pièces jointes envoyées par l'administration</div>

                        @if($briefFiles->isEmpty())
                            <div class="muted">Aucun fichier joint.</div>
                        @else
                            <div>
                                @foreach($briefFiles as $f)
                                    <a class="file-pill" href="{{ $f->url }}" target="_blank">
                                        <i class="fas fa-download" style="color: #2563eb;"></i>
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
            </div>

            <div class="col-lg-7">
                <form id="todo-traiter-form" method="POST" action="{{ route($formationPrefix . '.todo.traiter.store', $assignedProject->id) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="card instagram-card mb-4">
                        <div class="card-header instagram-card-header">
                            <h5 class="mb-0 fw-bold" style="background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                <i class="fas fa-info-circle me-2" style="background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                                Informations du Projet
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" name="title" value="{{ old('title', $assignedProject->title) }}" required maxlength="255" placeholder="Ex: Catalogue produits sur Adobe InDesign">
                                <div class="form-text">Maximum 255 caractères</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Liens du Projet <span class="text-muted">(optionnel)</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-link"></i></span>
                                    <input type="url" class="form-control" name="links[]" placeholder="https://..." value="{{ old('links.0', ($isTpAssignment ?? false) ? ($assignedProject->submission_link ?? '') : ($assignedProject->link ?? '')) }}" maxlength="2000">
                                </div>
                                <div class="form-text mt-2">Ajoute un lien si tu n’as pas de fichiers à envoyer (Drive, Behance, etc.).</div>
                            </div>
                        </div>
                    </div>

                    <div class="card instagram-card mb-4">
                        <div class="card-header instagram-card-header">
                            <h5 class="mb-0 fw-bold" style="background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                <i class="fas fa-paperclip me-2" style="background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                                Fichiers du Projet
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="alert border-0 mb-4" style="background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(249, 115, 22, 0.1) 100%); border-left: 4px solid #2563eb !important;">
                                <div class="d-flex">
                                    <i class="fas fa-info-circle me-3 mt-1" style="color: #2563eb; font-size: 1.2rem;"></i>
                                    <div style="color: #4a5568;">
                                        <strong style="color: #2563eb;">Formats acceptés :</strong> Images (JPG, PNG, GIF, WEBP), PDF<br>
                                        <strong style="color: #2563eb;">Taille maximale :</strong> 10 Mo par fichier
                                    </div>
                                </div>
                            </div>

                            <div
                                id="globalDropZone"
                                class="border-2 border-dashed rounded-4 p-5 text-center mt-4"
                                style="border-color: rgba(37, 99, 235, 0.3); background: linear-gradient(135deg, rgba(37, 99, 235, 0.05) 0%, rgba(249, 115, 22, 0.05) 100%); min-height: 200px; cursor: pointer; transition: all 0.3s ease;"
                            >
                                <i class="fas fa-cloud-upload-alt fa-3x mb-3" style="background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                                <h5 class="fw-bold" style="background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Glissez vos fichiers ici</h5>
                                <p class="text-muted mb-3">ou</p>
                                <button type="button" class="btn instagram-btn" id="globalBrowseBtn">
                                    <i class="fas fa-folder-open me-2"></i>Parcourir les fichiers
                                </button>
                                <input type="file" id="globalFileInput" name="files[]" multiple accept="image/*,application/pdf" style="display: none;">
                            </div>

                            <div id="filesPreview" class="row g-3 mt-4"></div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                        <div class="card-body p-4">
                            <div class="d-flex flex-column-reverse flex-md-row gap-2 justify-content-end">
                                <a href="{{ route($formationPrefix . '.todo.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </a>
                                <button type="submit" class="btn instagram-btn btn-lg" style="font-size: 1.05rem; padding: 1rem 2rem;">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Publier
                                </button>
                            </div>

                            <div class="alert alert-danger" id="todo-traiter-form-error" style="display:none; margin-top: 1rem;"></div>
                        </div>
                    </div>
                </form>

                <script>
                (function todoTraiterClientGuard(){
                    var form = document.getElementById('todo-traiter-form');
                    if(!form) return;

                    var linkInput = form.querySelector('[name="links[]"]') || form.querySelector('[name="project_link"]');
                    var filesInput = form.querySelector('[name="files[]"]') || document.getElementById('globalFileInput');
                    var errorBox = document.getElementById('todo-traiter-form-error');

                    // Browse
                    var dropZone = document.getElementById('globalDropZone');
                    var browseBtn = document.getElementById('globalBrowseBtn');
                    var fileInput = document.getElementById('globalFileInput');


                    function renderPreviews(){
                        var container = document.getElementById('filesPreview');
                        if(!container || !fileInput) return;
                        container.innerHTML = '';
                        var files = fileInput.files ? Array.from(fileInput.files) : [];
                        files.forEach(function(file){
                            var col = document.createElement('div');
                            col.className = 'col-md-6';
                            var card = document.createElement('div');
                            card.className = 'card h-100 border shadow-sm';
                            card.style.borderRadius = '16px';
                            var body = document.createElement('div');
                            body.className = 'card-body p-3';
                            var name = document.createElement('div');
                            name.className = 'fw-bold text-truncate';
                            name.style.fontSize = '0.9rem';
                            name.textContent = file.name;
                            var meta = document.createElement('div');
                            meta.className = 'text-muted';
                            meta.style.fontSize = '0.85rem';
                            meta.textContent = (Math.round((file.size/1024/1024)*100)/100) + ' MB';
                            body.appendChild(name);
                            body.appendChild(meta);

                            if (file.type && file.type.startsWith('image/')) {
                                var wrap = document.createElement('div');
                                wrap.className = 'mt-3';
                                var img = document.createElement('img');
                                img.className = 'img-fluid rounded shadow-sm';
                                img.style.maxHeight = '220px';
                                img.style.width = '100%';
                                img.style.objectFit = 'cover';
                                wrap.appendChild(img);
                                body.appendChild(wrap);

                                var reader = new FileReader();
                                reader.onload = function(e){ img.src = e.target.result; };
                                reader.readAsDataURL(file);
                            }

                            card.appendChild(body);
                            col.appendChild(card);
                            container.appendChild(col);
                        });
                    }

                    if (browseBtn && fileInput) {
                        browseBtn.addEventListener('click', function(e){
                            e.preventDefault();
                            e.stopPropagation();
                            fileInput.click();
                        });
                    }

                    if (fileInput) {
                        fileInput.addEventListener('change', function(){
                            renderPreviews();
                        });
                    }

                    if (dropZone && fileInput) {
                        dropZone.addEventListener('click', function(e){
                            if (!browseBtn || (e.target !== browseBtn && !browseBtn.contains(e.target))) {
                                fileInput.click();
                            }
                        });

                        dropZone.addEventListener('dragover', function(e){
                            e.preventDefault();
                            e.stopPropagation();
                            dropZone.classList.add('dragover');
                        });

                        dropZone.addEventListener('dragleave', function(e){
                            e.preventDefault();
                            e.stopPropagation();
                            dropZone.classList.remove('dragover');
                        });

                        dropZone.addEventListener('drop', function(e){
                            e.preventDefault();
                            e.stopPropagation();
                            dropZone.classList.remove('dragover');
                            if (!fileInput) return;
                            fileInput.files = e.dataTransfer.files;
                            renderPreviews();
                        });
                    }

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
    @endif
</div>
@endsection
