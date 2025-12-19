@extends('layouts.admin')

@section('title', 'CVthèque - Étudiants')

@section('content')
<div class="container-fluid px-4 py-4" style="overflow-x: hidden;">

    <!-- Header -->
    <div class="page-header mb-4" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #4fc3f7 100%); padding: 2rem; border-radius: 20px; color: white;">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-circle" style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <h1 class="mb-1" style="font-size: 2rem; font-weight: 700;">CVthèque</h1>
                    <p class="mb-0" style="opacity: 0.9;">Liste de tous les étudiants actifs</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card" style="border: none; border-radius: 16px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; box-shadow: 0 4px 15px rgba(30, 60, 114, 0.3);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1" style="opacity: 0.9; font-size: 0.9rem;">Total Étudiants</p>
                            <h3 class="mb-0" style="font-weight: 700;">{{ $stats['total'] }}</h3>
                        </div>
                        <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-users" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card" style="border: none; border-radius: 16px; background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%); color: white; box-shadow: 0 4px 15px rgba(79, 195, 247, 0.3);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1" style="opacity: 0.9; font-size: 0.9rem;">Design Graphique</p>
                            <h3 class="mb-0" style="font-weight: 700;">{{ $stats['design_graphique'] }}</h3>
                        </div>
                        <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-palette" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card" style="border: none; border-radius: 16px; background: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%); color: white; box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1" style="opacity: 0.9; font-size: 0.9rem;">Community Management</p>
                            <h3 class="mb-0" style="font-weight: 700;">{{ $stats['community_management'] }}</h3>
                        </div>
                        <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-share-alt" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card" style="border: none; border-radius: 16px; background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%); color: white; box-shadow: 0 4px 15px rgba(38, 198, 218, 0.3);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1" style="opacity: 0.9; font-size: 0.9rem;">Gestion Info & IA</p>
                            <h3 class="mb-0" style="font-weight: 700;">{{ $stats['gestion_informatique'] + $stats['intelligence_artificielle'] }}</h3>
                        </div>
                        <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-laptop-code" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grille des étudiants -->
    <div class="row">
        @forelse($students as $student)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card" style="border: none; border-radius: 20px; background: #1e293b; box-shadow: 0 4px 20px rgba(0,0,0,0.1); transition: transform 0.3s ease, box-shadow 0.3s ease; overflow: hidden;">
                <div class="card-body p-4">
                    <!-- Photo et nom -->
                    <div class="text-center mb-3">
                        @php
                            $photoUrl = null;
                            if (!empty($student->profile_photo)) {
                                $filename = basename($student->profile_photo);
                                if (file_exists(public_path('uploads/photos/' . $filename))) {
                                    $photoUrl = asset('uploads/photos/' . $filename);
                                } elseif (file_exists(public_path($student->profile_photo))) {
                                    $photoUrl = asset($student->profile_photo);
                                } elseif (file_exists(public_path('storage/' . $student->profile_photo))) {
                                    $photoUrl = asset('storage/' . $student->profile_photo);
                                }
                            }
                        @endphp
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}"
                                 alt="{{ $student->first_name }}"
                                 style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 4px solid #4fc3f7; margin-bottom: 1rem;">
                        @else
                            <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 2.5rem; margin: 0 auto 1rem;">
                                {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                            </div>
                        @endif
                        <h5 style="color: white; font-weight: 700; margin-bottom: 0.5rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $student->first_name }} {{ $student->last_name }}</h5>
                        <span class="badge" style="background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%); padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.85rem; max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: inline-block;">
                            {{ $student->formation }}
                        </span>
                    </div>

                    <!-- Informations -->
                    <div style="background: rgba(255,255,255,0.05); padding: 1rem; border-radius: 12px; margin-bottom: 1rem;">
                        <div class="mb-2" style="color: rgba(255,255,255,0.9); font-size: 0.9rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <i class="fas fa-envelope me-2" style="color: #4fc3f7;"></i><span style="vertical-align: middle;">{{ $student->email }}</span>
                        </div>
                        @if($student->phone)
                        <div class="mb-2" style="color: rgba(255,255,255,0.9); font-size: 0.9rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <i class="fas fa-phone me-2" style="color: #4fc3f7;"></i><span style="vertical-align: middle;">{{ $student->phone }}</span>
                        </div>
                        @endif
                        @if($student->specialization)
                        <div style="color: rgba(255,255,255,0.7); font-size: 0.85rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <i class="fas fa-graduation-cap me-2" style="color: #4fc3f7;"></i><span style="vertical-align: middle;">{{ $student->specialization }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Bouton Voir le profil -->
                    <button class="btn w-100"
                            onclick='viewStudentProfile(@json($student))'
                            style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; border: none; border-radius: 12px; padding: 0.75rem; font-weight: 600; transition: all 0.3s ease;">
                        <i class="fas fa-eye me-2"></i>Voir le profil
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-users" style="font-size: 4rem; color: rgba(255,255,255,0.3); margin-bottom: 1rem;"></i>
                <p style="color: rgba(255,255,255,0.6); font-size: 1.2rem;">Aucun étudiant trouvé</p>
            </div>
        </div>
        @endforelse
    </div>

</div>

<!-- Modal Profil Étudiant -->
<div class="modal fade" id="studentProfileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="background: #1e293b; border: 1px solid #334155; border-radius: 20px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border: none; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title text-white" style="font-weight: 700;">
                    <i class="fas fa-user-circle me-2"></i>Profil de l'étudiant
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="studentProfileContent">
                <!-- Contenu dynamique -->
            </div>
        </div>
    </div>
</div>

<style>
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(79, 195, 247, 0.3) !important;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(30, 60, 114, 0.5);
}
</style>

<script>
function viewStudentProfile(student) {
    const formattedDate = new Date(student.created_at).toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    });

    const photoHtml = student.profile_photo
        ? `<img src="{{ asset('storage') }}/${student.profile_photo}" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #4fc3f7;">`
        : `<div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 3rem; margin: 0 auto;">
            ${student.first_name.charAt(0).toUpperCase()}${student.last_name.charAt(0).toUpperCase()}
        </div>`;

    const content = `
        <div class="text-center mb-4">
            ${photoHtml}
            <h4 class="text-white mt-3 mb-1" style="font-weight: 700;">${student.first_name} ${student.last_name}</h4>
            <span class="badge" style="background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%); padding: 0.5rem 1.5rem; border-radius: 20px;">
                ${student.formation}
            </span>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <div style="background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 12px;">
                    <h6 class="text-white mb-3"><i class="fas fa-envelope me-2" style="color: #4fc3f7;"></i>Email</h6>
                    <p class="text-white mb-0">${student.email}</p>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div style="background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 12px;">
                    <h6 class="text-white mb-3"><i class="fas fa-phone me-2" style="color: #4fc3f7;"></i>Téléphone</h6>
                    <p class="text-white mb-0">${student.phone || 'Non renseigné'}</p>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div style="background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 12px;">
                    <h6 class="text-white mb-3"><i class="fas fa-graduation-cap me-2" style="color: #4fc3f7;"></i>Spécialisation</h6>
                    <p class="text-white mb-0">${student.specialization || 'Non renseignée'}</p>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div style="background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 12px;">
                    <h6 class="text-white mb-3"><i class="fas fa-calendar me-2" style="color: #4fc3f7;"></i>Inscription</h6>
                    <p class="text-white mb-0">${formattedDate}</p>
                </div>
            </div>
        </div>
    `;

    document.getElementById('studentProfileContent').innerHTML = content;
    new bootstrap.Modal(document.getElementById('studentProfileModal')).show();
}
</script>

@endsection
