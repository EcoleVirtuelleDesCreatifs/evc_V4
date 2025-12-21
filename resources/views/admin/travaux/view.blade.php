@extends('layouts.admin')
@section('title', 'Détails du TP')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="modern-tp-container">
    <!-- Header moderne -->
    <div class="tp-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    @php
                        // Déterminer la route de retour selon le programme de l'étudiant
                        $backRoute = 'admin.travaux.pending';
                        $program = strtolower($student->program ?? '');

                        // Design Graphique & Community Management combiné
                        if ((str_contains($program, 'design') || str_contains($program, 'graph')) && (str_contains($program, 'community') || str_contains($program, 'cm'))) {
                            $backRoute = 'admin.projets.design-cm.all';
                        }
                        // Community Management seul
                        elseif (str_contains($program, 'community') || str_contains($program, 'cm')) {
                            $backRoute = 'admin.projets.cm-smm.pending';
                        }
                        // Design Graphique seul
                        elseif (str_contains($program, 'design') && str_contains($program, 'graph')) {
                            $backRoute = 'admin.projets.design-graphique.pending';
                        }
                    @endphp
                    <a href="{{ route($backRoute) }}" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    <h1 class="tp-title">{{ $tp->title }}</h1>
                    <div class="tp-meta">
                        <span><i class="fas fa-user"></i> {{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }}</span>
                        <span><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($tp->created_at)->format('d/m/Y à H:i') }}</span>
                        <span><i class="fas fa-graduation-cap"></i> {{ $student->program ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex align-items-center justify-content-end gap-3">
                        @if($tp->status === 'assigned')
                            <span class="status-badge assigned"><i class="fas fa-tasks"></i> À faire</span>
                        @elseif($tp->status === 'submitted')
                            <span class="status-badge submitted"><i class="fas fa-check-circle"></i> Déjà fait</span>
                        @elseif($tp->status === 'pending')
                            <span class="status-badge pending"><i class="fas fa-clock"></i> En attente</span>
                        @elseif($tp->status === 'validated')
                            <span class="status-badge validated"><i class="fas fa-check-circle"></i> Validé</span>
                        @elseif($tp->status === 'rejected')
                            <span class="status-badge rejected"><i class="fas fa-times-circle"></i> Rejeté</span>
                        @else
                            <span class="status-badge pending"><i class="fas fa-question"></i> {{ ucfirst($tp->status) }}</span>
                        @endif

                        <a href="{{ route($backRoute) }}" class="btn-back-list">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-4">
        <div class="row g-4">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Description -->
                @if($tp->description)
                <div class="content-card">
                    <h5><i class="fas fa-align-left"></i> Description</h5>
                    <div class="description-text">{!! nl2br($tp->description) !!}</div>
                </div>
                @endif

                <!-- Fichiers -->
                @if($files && $files->count() > 0)
                <div class="content-card">
                    <h5><i class="fas fa-paperclip"></i> Fichiers joints ({{ $files->count() }})</h5>
                    <div class="files-grid">
                        @foreach($files as $file)
                            @php
                                $fileName = $file->file_name ?? $file->original_name ?? 'fichier';
                                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);

                                $url = $file->file_path;
                                if (!str_starts_with($url, 'http')) {
                                    // Nettoyer le chemin
                                    $path = str_replace(['public/', 'storage/'], '', $url);
                                    $path = ltrim($path, '/');

                                    // Préparer le chemin pour l'URL (encodage des espaces, accents, etc)
                                    $parts = explode('/', $path);
                                    $filename = array_pop($parts);
                                    $urlPath = implode('/', $parts) . ($parts ? '/' : '') . rawurlencode($filename);

                                    // Forcer l'utilisation de storage/ pour tous les fichiers locaux
                                    // y compris ceux dans uploads/
                                    $url = asset('storage/' . $urlPath);
                                }
                            @endphp
                            <div class="file-item">
                                @if($isImage)
                                    <div class="file-preview">
                                        <img src="{{ $url }}" alt="{{ $fileName }}" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'200\' height=\'200\'%3E%3Crect fill=\'%23ddd\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23999\'%3EImage%3C/text%3E%3C/svg%3E">
                                        <div class="image-overlay" onclick="openLightbox('{{ $url }}', '{{ $fileName }}')">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                @else
                                    <div class="file-icon">
                                        <i class="fas fa-file-{{ in_array($ext, ['pdf']) ? 'pdf' : (in_array($ext, ['doc','docx']) ? 'word' : 'alt') }}"></i>
                                        <span>{{ strtoupper($ext) }}</span>
                                    </div>
                                @endif
                                <div class="file-info">
                                    <div class="file-name" title="{{ $fileName }}">{{ Str::limit($fileName, 25) }}</div>
                                    <div class="file-actions">
                                        @if($isImage)
                                            <button onclick="openLightbox('{{ $url }}', '{{ $fileName }}')" class="btn-view" title="Voir l'image">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        @endif
                                        <a href="{{ $url }}" download class="btn-download" title="Télécharger">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Info étudiant -->
                <div class="sidebar-card">
                    <h5><i class="fas fa-user-circle"></i> Étudiant</h5>

                    <!-- Photo et nom de l'étudiant -->
                    <div class="student-profile">
                        <div class="student-avatar">
                            @php
                                $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($student->profile_photo ?? null);

                                // Si l'URL par défaut est retournée (pas de photo), on utilise ui-avatars
                                if (str_contains($photoUrl, 'default-avatar') || str_contains($photoUrl, 'avatar.png')) {
                                    $photoUrl = 'https://ui-avatars.com/api/?name=' . urlencode(($student->first_name ?? 'E') . ' ' . ($student->last_name ?? 'T')) . '&background=833AB4&color=fff&size=120';
                                }
                            @endphp
                            <img src="{{ $photoUrl }}" alt="{{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }}">
                        </div>
                        <div class="student-name">{{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }}</div>
                    </div>

                    <div class="info-list">
                        <div class="info-item">
                            <span class="label">Email</span>
                            <span class="value">{{ $student->email ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Formation</span>
                            <span class="value">{{ $student->program ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Soumis le</span>
                            <span class="value">{{ \Carbon\Carbon::parse($tp->created_at)->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                @if($tp->status === 'submitted' || $tp->status === 'pending')
                <div class="sidebar-card">
                    <h5><i class="fas fa-cog"></i> Actions</h5>
                    <div class="actions-btns">
                        <form action="{{ route('admin.tp.validate', $tp->id) }}" method="POST" onsubmit="return confirm('Valider ce TP ?')">
                            @csrf
                            <button type="submit" class="action-btn validate"><i class="fas fa-check"></i> Valider</button>
                        </form>
                        <button class="action-btn reject" onclick="document.getElementById('rejectForm').style.display='block'; this.style.display='none'">
                            <i class="fas fa-times"></i> Rejeter
                        </button>
                        <form action="{{ route('admin.tp.delete', $tp->id) }}" method="POST" onsubmit="return confirm('Supprimer ce TP ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete"><i class="fas fa-trash"></i> Supprimer</button>
                        </form>
                    </div>

                    <div id="rejectForm" style="display:none;margin-top:1rem">
                        <form action="{{ route('admin.tp.reject', $tp->id) }}" method="POST">
                            @csrf
                            <textarea name="reason" class="form-control mb-2" rows="3" placeholder="Raison du rejet (minimum 10 caractères)..." required minlength="10"></textarea>
                            <button type="submit" class="btn btn-danger btn-sm w-100">Confirmer le rejet</button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Lightbox pour afficher les images -->
<div id="lightboxModal" class="lightbox-modal" onclick="closeLightbox()">
    <span class="lightbox-close">&times;</span>
    <img class="lightbox-content" id="lightboxImage">
    <div class="lightbox-caption" id="lightboxCaption"></div>
</div>

<style>
* { font-family: 'Inter', sans-serif; }
.modern-tp-container { background: #f5f7fa; min-height: 100vh; padding-bottom: 3rem; }
.tp-header { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #4fc3f7 100%); padding: 2.5rem 0; margin-bottom: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
.back-btn { color: rgba(255,255,255,0.8); text-decoration: none; display: inline-block; margin-bottom: 1rem; transition: 0.3s; }
.back-btn:hover { color: white; transform: translateX(-5px); }
.tp-title { color: white; font-size: 2rem; font-weight: 700; margin: 0.5rem 0; }
.tp-meta { color: rgba(255,255,255,0.8); }
.tp-meta span { margin-right: 1.5rem; font-size: 0.9rem; }
.tp-meta i { margin-right: 0.5rem; }
.status-badge { display: inline-block; padding: 0.6rem 1.5rem; border-radius: 50px; font-weight: 600; font-size: 0.9rem; }
.status-badge.assigned { background: linear-gradient(135deg, #17a2b8, #138496); color: white; }
.status-badge.submitted { background: linear-gradient(135deg, #28a745, #20c997); color: white; }
.status-badge.pending { background: linear-gradient(135deg, #ff9800, #fb8c00); color: white; }
.status-badge.validated { background: linear-gradient(135deg, #28a745, #20c997); color: white; }
.status-badge.rejected { background: linear-gradient(135deg, #dc3545, #c82333); color: white; }
.content-card, .sidebar-card { background: white; border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 12px rgba(0,0,0,0.08); transition: 0.3s; }
.content-card:hover, .sidebar-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.12); transform: translateY(-2px); }
.content-card h5, .sidebar-card h5 { font-weight: 700; color: #1e3c72; margin-bottom: 1rem; font-size: 1.1rem; }
.content-card h5 i, .sidebar-card h5 i { margin-right: 0.5rem; color: #4fc3f7; }
.description-text { color: #495057; line-height: 1.8; }
.external-link { color: #1e3c72; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; transition: 0.3s; }
.external-link:hover { color: #4fc3f7; transform: translateX(5px); }
.external-link i { margin-right: 0.5rem; }
.files-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem; }
.file-item { background: #f8f9fa; border-radius: 12px; overflow: hidden; transition: 0.3s; }
.file-item:hover { transform: translateY(-5px); box-shadow: 0 4px 15px rgba(0,0,0,0.15); }
.file-preview { height: 140px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #000; }
.file-preview img { width: 100%; height: 100%; object-fit: cover; }
.file-icon { height: 140px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #f0f2f5, #e9ecef); color: #6c757d; font-size: 3rem; }
.file-icon span { font-size: 0.8rem; font-weight: 700; margin-top: 0.5rem; }
.file-info { padding: 0.8rem; display: flex; justify-content: space-between; align-items: center; background: white; }
.file-name { font-size: 0.85rem; font-weight: 600; color: #495057; flex: 1; }
.file-actions { display: flex; gap: 0.5rem; }
.btn-view { background: linear-gradient(135deg, #833AB4, #C13584); color: white; border: none; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: 0.3s; cursor: pointer; }
.btn-view:hover { transform: scale(1.1); box-shadow: 0 4px 12px rgba(131, 58, 180, 0.4); }
.btn-download { background: linear-gradient(135deg, #1e3c72, #4fc3f7); color: white; border: none; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: 0.3s; text-decoration: none; }
.btn-download:hover { transform: scale(1.1) rotate(10deg); }

/* Student Profile */
.student-profile { display: flex; flex-direction: column; align-items: center; padding: 1.5rem 0; margin-bottom: 1rem; border-bottom: 2px solid #f0f2f5; }
.student-avatar { width: 100px; height: 100px; border-radius: 50%; overflow: hidden; border: 4px solid transparent; background: linear-gradient(135deg, #833AB4, #C13584, #E1306C); padding: 3px; margin-bottom: 1rem; box-shadow: 0 4px 15px rgba(131, 58, 180, 0.3); transition: 0.3s; }
.student-avatar:hover { transform: scale(1.05); box-shadow: 0 6px 20px rgba(131, 58, 180, 0.5); }
.student-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; background: white; }
.student-name { font-size: 1.2rem; font-weight: 700; color: #1e3c72; text-align: center; background: linear-gradient(135deg, #833AB4, #C13584); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

.info-list { }
.info-item { display: flex; justify-content: space-between; padding: 0.8rem 0; border-bottom: 1px solid #e9ecef; }
.info-item:last-child { border-bottom: none; }
.info-item .label { color: #6c757d; font-size: 0.85rem; font-weight: 600; }
.info-item .value { color: #212529; font-weight: 600; }
.actions-btns { display: flex; flex-direction: column; gap: 0.75rem; }
.action-btn { width: 100%; padding: 0.8rem; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
.action-btn.validate { background: linear-gradient(135deg, #28a745, #20c997); color: white; }
.action-btn.reject { background: linear-gradient(135deg, #ffc107, #ff9800); color: white; }
.action-btn.delete { background: linear-gradient(135deg, #dc3545, #c82333); color: white; }
.action-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.2); }

/* Bouton Retour à la liste */
.btn-back-list {
    background: linear-gradient(135deg, #4fc3f7, #29b6f6);
    color: white;
    padding: 0.6rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: 0.3s;
    box-shadow: 0 2px 8px rgba(79, 195, 247, 0.3);
}
.btn-back-list:hover {
    background: linear-gradient(135deg, #29b6f6, #039be5);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(79, 195, 247, 0.5);
    color: white;
}

/* Lightbox Modal */
.lightbox-modal { display: none; position: fixed; z-index: 9999; padding-top: 50px; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.95); }
.lightbox-content { margin: auto; display: block; max-width: 90%; max-height: 85vh; object-fit: contain; animation: zoom 0.3s; }
@keyframes zoom { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
.lightbox-close { position: absolute; top: 20px; right: 40px; color: #fff; font-size: 45px; font-weight: bold; cursor: pointer; transition: 0.3s; z-index: 10000; }
.lightbox-close:hover, .lightbox-close:focus { color: #bbb; }
.lightbox-caption { margin: auto; display: block; width: 80%; max-width: 700px; text-align: center; color: #ccc; padding: 15px 0; font-size: 1rem; }
.image-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; opacity: 0; transition: 0.3s; cursor: pointer; }
.image-overlay i { color: white; font-size: 2rem; }
.file-preview:hover .image-overlay { opacity: 1; }

@media (max-width: 768px) {
    .tp-header { padding: 1.5rem 0; }
    .tp-title { font-size: 1.5rem; }
    .tp-meta span { display: block; margin: 0.3rem 0; }
    .files-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); }
    .lightbox-content { max-width: 95%; }
    .lightbox-close { top: 10px; right: 20px; font-size: 35px; }
}
</style>

<script>
// Fonction pour ouvrir le lightbox
function openLightbox(imageUrl, imageName) {
    const modal = document.getElementById('lightboxModal');
    const modalImg = document.getElementById('lightboxImage');
    const caption = document.getElementById('lightboxCaption');

    modal.style.display = 'block';
    modalImg.src = imageUrl;
    caption.textContent = imageName;

    // Empêcher le scroll du body
    document.body.style.overflow = 'hidden';
}

// Fonction pour fermer le lightbox
function closeLightbox() {
    const modal = document.getElementById('lightboxModal');
    modal.style.display = 'none';

    // Réactiver le scroll du body
    document.body.style.overflow = 'auto';
}

// Fermer avec la touche Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeLightbox();
    }
});

// Empêcher la fermeture quand on clique sur l'image
document.getElementById('lightboxImage').addEventListener('click', function(event) {
    event.stopPropagation();
});
</script>
@endsection
