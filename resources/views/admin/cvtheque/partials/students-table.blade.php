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
                            if (str_starts_with($photoPath, 'photos_preregistrations/')) {
                                $photoUrl = asset('storage/' . $photoPath);
                            } elseif (str_starts_with($photoPath, 'uploads/')) {
                                $photoUrl = asset($photoPath);
                            } elseif (str_starts_with($photoPath, 'storage/')) {
                                $photoUrl = asset($photoPath);
                            } elseif (str_contains($photoPath, '/')) {
                                $photoUrl = asset('storage/' . $photoPath);
                            } else {
                                $photoUrl = asset('uploads/photos/' . $photoPath);
                            }
                        @endphp
                        <div style="display: inline-block; position: relative;">
                            <img src="{{ $photoUrl }}"
                                 class="rounded-circle"
                                 alt="{{ $student->first_name }} {{ $student->last_name }}"
                                 style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #667eea;"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                 style="width: 45px; height: 45px; display: none; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 0.85rem;">
                                {{ strtoupper(substr($student->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($student->last_name ?? 'S', 0, 1)) }}
                            </div>
                        </div>
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                             style="width: 45px; height: 45px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 0.85rem;">
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
                        $badgeStyle = 'background: linear-gradient(135deg, #6c757d 0%, #495057 100%);';
                        $badgeIcon = 'fa-graduation-cap';

                        if (stripos($formation, 'design') !== false) {
                            $badgeStyle = 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);';
                            $badgeIcon = 'fa-palette';
                        } elseif (stripos($formation, 'community') !== false) {
                            $badgeStyle = 'background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);';
                            $badgeIcon = 'fa-users';
                        } elseif (stripos($formation, 'gestion') !== false) {
                            $badgeStyle = 'background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);';
                            $badgeIcon = 'fa-laptop-code';
                        } elseif (stripos($formation, 'intelligence') !== false) {
                            $badgeStyle = 'background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);';
                            $badgeIcon = 'fa-brain';
                        }
                    @endphp
                    <span class="badge text-white px-3 py-2" style="{{ $badgeStyle }} border-radius: 20px; font-size: 0.85rem;">
                        <i class="fas {{ $badgeIcon }} me-1"></i>{{ $formation ?: 'Non défini' }}
                    </span>
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
                    <div class="d-flex gap-2 flex-wrap">
                        @if($student->cv_file)
                            <span class="badge text-white px-2 py-1" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); border-radius: 12px; font-size: 0.75rem;" title="CV">
                                <i class="fas fa-file-pdf"></i>
                            </span>
                        @endif
                        @if($student->motivation_file)
                            <span class="badge text-white px-2 py-1" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); border-radius: 12px; font-size: 0.75rem;" title="Lettre de motivation">
                                <i class="fas fa-envelope"></i>
                            </span>
                        @endif
                        @if($student->pressbook_file)
                            <span class="badge text-white px-2 py-1" style="background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%); border-radius: 12px; font-size: 0.75rem;" title="Pressbook">
                                <i class="fas fa-book"></i>
                            </span>
                        @endif
                        @if($student->rapport_file)
                            <span class="badge text-white px-2 py-1" style="background: linear-gradient(135deg, #198754 0%, #157347 100%); border-radius: 12px; font-size: 0.75rem;" title="Rapport">
                                <i class="fas fa-graduation-cap"></i>
                            </span>
                        @endif
                        @if(!$student->cv_file && !$student->motivation_file && !$student->pressbook_file && !$student->rapport_file)
                            <span class="text-muted small">-</span>
                        @endif
                    </div>
                </td>
                <td>
                    @php
                        $completion = $student->profile_completion_score ?? 0;
                        $progressStyle = 'background: linear-gradient(90deg, #dc3545 0%, #c82333 100%);';
                        $textColor = 'text-danger';

                        if ($completion >= 75) {
                            $progressStyle = 'background: linear-gradient(90deg, #56ab2f 0%, #a8e6cf 100%);';
                            $textColor = 'text-success';
                        } elseif ($completion >= 50) {
                            $progressStyle = 'background: linear-gradient(90deg, #ffc107 0%, #ffb300 100%);';
                            $textColor = 'text-warning';
                        } elseif ($completion >= 25) {
                            $progressStyle = 'background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);';
                            $textColor = 'text-info';
                        }
                    @endphp
                    <div style="min-width: 100px;">
                        <div class="progress" style="height: 8px; border-radius: 10px; background: rgba(0,0,0,0.1);">
                            <div class="progress-bar"
                                 role="progressbar"
                                 style="width: {{ $completion }}%; {{ $progressStyle }} border-radius: 10px;"
                                 aria-valuenow="{{ $completion }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <small class="{{ $textColor }} fw-bold mt-1 d-block text-center">{{ $completion }}%</small>
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
                    <div class="d-flex gap-1">
                        <a href="{{ url('/evc/app/admin/students/' . $student->user_id . '/profile') }}"
                           class="btn btn-sm text-white"
                           style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none; border-radius: 8px; padding: 0.4rem 0.8rem;"
                           title="Voir le profil CVthèque">
                            <i class="fas fa-eye"></i>
                        </a>
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
