<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Photo</th>
                <th>Nom Complet</th>
                <th>Email</th>
                <th>Formation</th>
                <th>Titre Professionnel</th>
                <th>Expérience</th>
                <th>Documents</th>
                <th>Complétion</th>
                <th>Visibilité</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
            <tr>
                <td>
                    @if(!empty($student->profile_photo))
                        @php
                            // Gérer différents formats de chemins de photos
                            $photoPath = $student->profile_photo;
                            if (str_starts_with($photoPath, 'uploads/')) {
                                $photoUrl = asset($photoPath);
                            } elseif (str_starts_with($photoPath, 'students/photos/')) {
                                $photoUrl = asset('storage/' . $photoPath);
                            } else {
                                $photoUrl = asset('storage/students/photos/' . $photoPath);
                            }
                        @endphp
                        <img src="{{ $photoUrl }}" 
                             class="rounded-circle" 
                             alt="{{ $student->first_name }}" 
                             style="width: 40px; height: 40px; object-fit: cover;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="rounded-circle bg-primary text-white align-items-center justify-content-center" 
                             style="width: 40px; height: 40px; display: none;">
                            {{ strtoupper(substr($student->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($student->last_name ?? 'S', 0, 1)) }}
                        </div>
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                             style="width: 40px; height: 40px;">
                            {{ strtoupper(substr($student->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($student->last_name ?? 'S', 0, 1)) }}
                        </div>
                    @endif
                </td>
                <td>
                    <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                </td>
                <td>
                    <small class="text-muted">{{ $student->email }}</small>
                </td>
                <td>
                    @php
                        $formation = $student->program ?: $student->specialization;
                        $badgeClass = 'bg-secondary';
                        if (stripos($formation, 'design') !== false) {
                            $badgeClass = 'bg-primary';
                        } elseif (stripos($formation, 'community') !== false) {
                            $badgeClass = 'bg-info';
                        } elseif (stripos($formation, 'gestion') !== false) {
                            $badgeClass = 'bg-warning';
                        } elseif (stripos($formation, 'intelligence') !== false) {
                            $badgeClass = 'bg-success';
                        }
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $formation ?: 'Non défini' }}</span>
                </td>
                <td>
                    @if($student->professional_title)
                        <span class="text-dark">{{ $student->professional_title }}</span>
                    @else
                        <span class="text-muted fst-italic">Non renseigné</span>
                    @endif
                </td>
                <td>
                    @if($student->years_experience)
                        <span class="badge bg-secondary">{{ $student->years_experience }} an(s)</span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-1">
                        @if($student->cv_file)
                            <i class="fas fa-file-pdf text-danger" title="CV"></i>
                        @endif
                        @if($student->motivation_file)
                            <i class="fas fa-envelope text-primary" title="Lettre de motivation"></i>
                        @endif
                        @if($student->pressbook_file)
                            <i class="fas fa-book text-warning" title="Pressbook"></i>
                        @endif
                        @if($student->rapport_file)
                            <i class="fas fa-graduation-cap text-success" title="Rapport"></i>
                        @endif
                        @if(!$student->cv_file && !$student->motivation_file && !$student->pressbook_file && !$student->rapport_file)
                            <span class="text-muted">Aucun</span>
                        @endif
                    </div>
                </td>
                <td>
                    @php
                        $completion = $student->profile_completion_score ?? 0;
                        $progressClass = 'bg-danger';
                        if ($completion >= 75) {
                            $progressClass = 'bg-success';
                        } elseif ($completion >= 50) {
                            $progressClass = 'bg-warning';
                        } elseif ($completion >= 25) {
                            $progressClass = 'bg-info';
                        }
                    @endphp
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar {{ $progressClass }}" 
                             role="progressbar" 
                             style="width: {{ $completion }}%;" 
                             aria-valuenow="{{ $completion }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ $completion }}%
                        </div>
                    </div>
                </td>
                <td>
                    @if(!empty($student->professional_title))
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle"></i> Complet
                        </span>
                    @else
                        <span class="badge bg-warning">
                            <i class="fas fa-exclamation-circle"></i> Incomplet
                        </span>
                    @endif
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ url('/evc/app/admin/students/' . $student->id . '/profile') }}" 
                           class="btn btn-sm btn-primary" 
                           title="Voir le profil">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if(!empty($student->cv_file))
                        <a href="{{ asset('storage/' . $student->cv_file) }}" 
                           class="btn btn-sm btn-success" 
                           target="_blank"
                           download
                           title="Télécharger CV">
                            <i class="fas fa-download"></i>
                        </a>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                    <p class="mb-0">Aucun étudiant trouvé dans cette catégorie</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($students->count() > 0)
<div class="d-flex justify-content-between align-items-center mt-3">
    <div class="text-muted">
        Affichage de {{ $students->count() }} étudiant(s)
    </div>
</div>
@endif
