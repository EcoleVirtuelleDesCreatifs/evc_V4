@extends('layouts.ki-admin')

@section('title', 'Modifier mon profil')

@push('styles')
<style>
    .step-nav .nav-link { border-radius: 9999px; padding: .45rem .9rem; }
    .step-nav .nav-link.active { background: linear-gradient(135deg, #003366 0%, #3399ff 100%); color: #fff; }
    /* Progress bar removed */
    .dropzone { border: 2px dashed #cfe2ff; border-radius: 12px; padding: 1rem; text-align: center; cursor: pointer; }
    .dropzone.dragover { background: #f0f7ff; }
    .avatar-preview { width: 72px; height: 72px; border-radius: 50%; object-fit: cover; border: 3px solid #FF6B35; }
    .section-title { letter-spacing: .06em; }
    .sticky-summary { position: sticky; top: 1rem; }
    @media (max-width: 991px) { .sticky-summary { position: static; margin-top: 1rem; } }
    /* Card headers: make titles white with a modern gradient header */
    .card .card-header {
        background: linear-gradient(135deg, #003366 0%, #3399ff 100%);
        color: #fff;
        border-radius: .5rem .5rem 0 0;
    }
    .card .card-header .section-title { color: #fff !important; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h3 class="card-title mb-0 fs-2 fw-bold">
                        <i class="fas fa-user-edit text-primary me-2"></i>
                        Modifier mon profil
                    </h3>

                </div>

                <br/>
                <div class="card-body">


                    <!-- Résumé des informations actuelles -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-transparent border-0">
                            <h6 class="mb-0 text-uppercase section-title">Résumé du profil</h6>
                        </div>
                        <div class="card-body">
                            @php
                                $studentPhoto = $student->profile_photo ?? null;
                                $prePhoto = isset($preReg) ? ($preReg->profile_photo ?? ($preReg->photo ?? ($preReg->image ?? ($preReg->image_url ?? ($preReg->avatar ?? null))))) : null;
                                $rawPhoto = $studentPhoto ?: $prePhoto;

                                // Générer l'URL correcte de la photo
                                if ($rawPhoto) {
                                    // Si c'est une URL complète (http/https)
                                    if (preg_match('/^https?:\/\//', $rawPhoto)) {
                                        $photoUrl = $rawPhoto;
                                    }
                                    // Si le chemin commence par 'photos_preregistrations/', ajouter 'storage/'
                                    elseif (str_starts_with($rawPhoto, 'photos_preregistrations/')) {
                                        $photoUrl = asset('storage/' . $rawPhoto);
                                    }
                                    // Si le chemin commence par 'uploads/', c'est déjà dans public/
                                    elseif (str_starts_with($rawPhoto, 'uploads/')) {
                                        $photoUrl = asset($rawPhoto);
                                    }
                                    // Autres cas : supposer que c'est dans storage
                                    else {
                                        $photoUrl = asset('storage/' . $rawPhoto);
                                    }
                                } else {
                                    $photoUrl = asset('assets/img/avatar.png');
                                }
                            @endphp
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <img class="avatar-preview" src="{{ $photoUrl }}" alt="avatar">
                                        <div>
                                            <div class="fw-bold">{{ trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: '—' }}</div>
                                            <div class="text-muted small">{{ $student->gender ?? '—' }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="small text-muted">Contact</div>
                                    <div class="fw-semibold">{{ $student->email ?? '—' }}</div>
                                    <div class="text-muted">Tel: {{ $student->phone ?? '—' }} | WhatsApp: {{ $student->whatsapp ?? '—' }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="small text-muted">Localisation</div>
                                    <div>{{ $student->quartier ?? '—' }}</div>
                                    <div class="text-muted">{{ $student->city ?? '—' }}, {{ $student->country ?? '—' }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="small text-muted">Académique</div>
                                    <div>Niveau(x): {{ $student->level ?? '—' }}</div>
                                    <div>Domaine: {{ $student->specialization ?? '—' }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="small text-muted">Professionnel</div>
                                    <div>Expérience: {{ $student->years_experience !== null ? $student->years_experience.' an(s)' : '—' }}</div>
                                    <div>Secteur: {{ $student->industry_sector ?? '—' }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="small text-muted">Identité</div>
                                    <div>Date de naissance: {{ optional($student->date_of_birth)->format('d/m/Y') ?? '—' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-12">
                    @php
                        $routePrefix = 'design-graphique';
                        $path = request()->path();
                        if (preg_match('#^evc/compte/([^/]+)#', $path, $matches)) {
                            $routePrefix = $matches[1];
                        }
                    @endphp
                    <form action="{{ route($routePrefix . '.profil.update', isset($student->id) ? ['id' => $student->id] : []) }}" method="POST" enctype="multipart/form-data" class="row g-3" id="profileForm">
                        @csrf

                        <!-- Grille 2 colonnes de cartes -->
                        <div class="row">
                            <div class="col-lg-6">
                                <!-- Carte Identité -->
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-header bg-transparent border-0">
                                        <h6 id="identite" class="text-uppercase text-muted mb-0 section-title">Identité</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">Prénom</label>
                            <input type="text" class="form-control <?php if($errors->has('first_name')): ?> is-invalid <?php endif; ?>" id="first_name" name="first_name" value="<?php echo old('first_name', $defaults['first_name'] ?? ''); ?>" placeholder="Ex: Jean" autocomplete="given-name" autofocus>
                            <?php if($errors->has('first_name')): ?><div class="invalid-feedback"><?php echo e($errors->first('first_name')); ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Nom</label>
                            <input type="text" class="form-control <?php if($errors->has('last_name')): ?> is-invalid <?php endif; ?>" id="last_name" name="last_name" value="<?php echo old('last_name', $defaults['last_name'] ?? ''); ?>" placeholder="Ex: Dupont" autocomplete="family-name">
                            <?php if($errors->has('last_name')): ?><div class="invalid-feedback"><?php echo e($errors->first('last_name')); ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label for="date_of_birth" class="form-label">Date de naissance</label>
                            <input type="date" class="form-control <?php if($errors->has('date_of_birth')): ?> is-invalid <?php endif; ?>" id="date_of_birth" name="date_of_birth" value="<?php echo old('date_of_birth', $defaults['date_of_birth'] ?? ''); ?>">
                            <?php if($errors->has('date_of_birth')): ?><div class="invalid-feedback"><?php echo e($errors->first('date_of_birth')); ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label for="gender" class="form-label">Sexe</label>
                            <select class="form-select <?php if($errors->has('gender')): ?> is-invalid <?php endif; ?>" id="gender" name="gender" aria-describedby="genderHelp">
                                <?php ($g = old('gender', $defaults['gender'] ?? '')) ?>
                                <option value="" <?php echo $g=='' ? 'selected' : ''; ?>>—</option>
                                <option value="Homme" <?php echo $g=='Homme' ? 'selected' : ''; ?>>Homme</option>
                                <option value="Femme" <?php echo $g=='Femme' ? 'selected' : ''; ?>>Femme</option>
                            </select>
                            <div id="genderHelp" class="form-text">Sélectionnez votre sexe (optionnel).</div>
                            <?php if($errors->has('gender')): ?><div class="invalid-feedback"><?php echo e($errors->first('gender')); ?></div><?php endif; ?>
                        </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Carte Contact -->
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-header bg-transparent border-0">
                                        <h6 id="contact" class="text-uppercase text-muted mb-0 section-title">Contact</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control <?php if($errors->has('email')): ?> is-invalid <?php endif; ?>" id="email" name="email" value="<?php echo old('email', $defaults['email'] ?? ''); ?>" placeholder="exemple@mail.com" autocomplete="email">
                            <?php if($errors->has('email')): ?><div class="invalid-feedback"><?php echo e($errors->first('email')); ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="text" class="form-control <?php if($errors->has('phone')): ?> is-invalid <?php endif; ?>" id="phone" name="phone" value="<?php echo old('phone', $defaults['phone'] ?? ''); ?>" placeholder="Ex: +2250123456789" inputmode="tel">
                            <?php if($errors->has('phone')): ?><div class="invalid-feedback"><?php echo e($errors->first('phone')); ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="whatsapp" class="form-label">WhatsApp</label>
                            <input type="text" class="form-control <?php if($errors->has('whatsapp')): ?> is-invalid <?php endif; ?>" id="whatsapp" name="whatsapp" value="<?php echo old('whatsapp', $defaults['whatsapp'] ?? ''); ?>" placeholder="Ex: +2250123456789" inputmode="tel">
                            <?php if($errors->has('whatsapp')): ?><div class="invalid-feedback"><?php echo e($errors->first('whatsapp')); ?></div><?php endif; ?>
                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <!-- Carte Académique -->
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-header bg-transparent border-0">
                                        <h6 id="academique" class="text-uppercase text-muted mb-0 section-title">Académique</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                        <!-- Remplacement de "Programme" par nouveaux champs -->
                        <div class="col-md-6">
                            <label for="years_experience" class="form-label">Nombre d'années d'Expérience</label>
                            <input type="number" min="0" class="form-control" id="years_experience" name="years_experience" value="<?php echo old('years_experience', $defaults['years_experience'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="industry_sector" class="form-label">Secteur d'activité</label>
                            <input type="text" class="form-control" id="industry_sector" name="industry_sector" value="<?php echo old('industry_sector', $defaults['industry_sector'] ?? ''); ?>">
                        </div>
                        <div class="col-md-12">
                            <label for="level" class="form-label">Niveau d'études</label>
                            <select class="form-select @error('level') is-invalid @enderror" id="level" name="level">
                                <option value="">-- Sélectionnez votre niveau --</option>
                                <option value="Niveau primaire" {{ old('level', $defaults['level'] ?? '') == 'Niveau primaire' ? 'selected' : '' }}>Niveau primaire</option>
                                <option value="Niveau secondaire" {{ old('level', $defaults['level'] ?? '') == 'Niveau secondaire' ? 'selected' : '' }}>Niveau secondaire</option>
                                <option value="Baccalauréat" {{ old('level', $defaults['level'] ?? '') == 'Baccalauréat' ? 'selected' : '' }}>Baccalauréat</option>
                                <option value="BTS / DUT" {{ old('level', $defaults['level'] ?? '') == 'BTS / DUT' ? 'selected' : '' }}>BTS / DUT</option>
                                <option value="Licence" {{ old('level', $defaults['level'] ?? '') == 'Licence' ? 'selected' : '' }}>Licence</option>
                                <option value="Master" {{ old('level', $defaults['level'] ?? '') == 'Master' ? 'selected' : '' }}>Master</option>
                                <option value="Doctorat" {{ old('level', $defaults['level'] ?? '') == 'Doctorat' ? 'selected' : '' }}>Doctorat</option>
                            </select>
                            <?php if($errors->has('level')): ?><div class="invalid-feedback"><?php echo e($errors->first('level')); ?></div><?php endif; ?>
                        </div>

                                    </div>
                                </div>

                                <!-- Carte Adresse -->
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-header bg-transparent border-0">
                                        <h6 id="adresse" class="text-uppercase text-muted mb-0 section-title">Adresse</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                        <div class="col-md-12">
                            <label for="quartier" class="form-label">Quartier</label>
                            <input type="text" class="form-control @error('quartier') is-invalid @enderror" id="quartier" name="quartier" value="<?php echo old('quartier', $defaults['quartier'] ?? ''); ?>">
                            <?php if($errors->has('quartier')): ?><div class="invalid-feedback"><?php echo e($errors->first('quartier')); ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="city" class="form-label">Ville</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="<?php echo old('city', $defaults['city'] ?? ''); ?>">
                            <?php if($errors->has('city')): ?><div class="invalid-feedback"><?php echo e($errors->first('city')); ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="country" class="form-label">Pays</label>
                            <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="<?php echo old('country', $defaults['country'] ?? ''); ?>">
                            <?php if($errors->has('country')): ?><div class="invalid-feedback"><?php echo e($errors->first('country')); ?></div><?php endif; ?>
                        </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Carte Photo -->
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-header bg-transparent border-0">
                                        <h6 id="statut" class="text-uppercase text-muted mb-0 section-title">Photo</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label">Photo de profil (glisser-déposer)</label>
                                                <div id="dropzone" class="dropzone mb-2" role="button" tabindex="0" onclick="document.getElementById('profile_photo').click();">
                                                    <i class="fas fa-cloud-upload-alt me-2"></i>
                                                    Déposez l'image ici ou cliquez pour choisir
                                                </div>
                                                <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" id="profile_photo" name="profile_photo" accept="image/*" style="display:none;">
                                                @error('profile_photo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                                                <div class="mt-3">
                                                    <small class="text-muted d-block mb-2">Aperçu de la photo:</small>
                                                    @php
                                                        // Générer l'URL correcte de la photo actuelle
                                                        $currentPhoto = $rawPhoto;
                                                        if ($currentPhoto) {
                                                            if (preg_match('/^https?:\/\//', $currentPhoto)) {
                                                                $currentPhotoUrl = $currentPhoto;
                                                            }
                                                            elseif (str_starts_with($currentPhoto, 'photos_preregistrations/')) {
                                                                $currentPhotoUrl = asset('storage/' . $currentPhoto);
                                                            }
                                                            elseif (str_starts_with($currentPhoto, 'uploads/')) {
                                                                $currentPhotoUrl = asset($currentPhoto);
                                                            }
                                                            else {
                                                                $currentPhotoUrl = asset('storage/' . $currentPhoto);
                                                            }
                                                        } else {
                                                            $currentPhotoUrl = asset('assets/img/avatar.png');
                                                        }
                                                    @endphp
                                                    <img id="liveAvatar" src="{{ $currentPhotoUrl }}" alt="Aperçu photo" class="img-thumbnail" style="height:120px;width:120px;object-fit:cover;border-radius:12px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 d-flex gap-2 mt-3">
                            <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Enregistrer
                            </button>
                            <a href="{{ route('dashboard.' . $routePrefix) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Retour à l'espace étudiant
                            </a>
                        </div>
                    </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const navLinks = document.querySelectorAll('.step-nav .nav-link');
  const form = document.getElementById('profileForm');
  const liveName = document.getElementById('liveName');
  const liveProgram = document.getElementById('liveProgram');
  const dropzone = document.getElementById('dropzone');
  const fileInput = document.getElementById('profile_photo');
  const liveAvatar = document.getElementById('liveAvatar');

  // Step navigation
  navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      navLinks.forEach(l => l.classList.remove('active'));
      link.classList.add('active');
      const target = document.querySelector(link.dataset.target);
      if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });

  // Progress removed

  // Live preview name + program
  const fn = document.getElementById('first_name');
  const ln = document.getElementById('last_name');
  const prog = document.getElementById('program');
  function updatePreview() {
    const name = ((fn?.value || '') + ' ' + (ln?.value || '')).trim();
    liveName.textContent = name || '—';
    liveProgram.textContent = (prog?.value || '');
  }
  fn?.addEventListener('input', updatePreview);
  ln?.addEventListener('input', updatePreview);
  prog?.addEventListener('input', updatePreview);
  updatePreview();

  // Dropzone
  function openPicker() { fileInput.click(); }
  dropzone?.addEventListener('click', openPicker);
  dropzone?.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('dragover'); });
  dropzone?.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
  dropzone?.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
      fileInput.files = e.dataTransfer.files;
      previewFile(file);
    }
  });
  fileInput?.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file && file.type.startsWith('image/')) previewFile(file);
  });
  function previewFile(file) {
    const reader = new FileReader();
    reader.onload = (ev) => { liveAvatar.src = ev.target.result; };
    reader.readAsDataURL(file);
  }

  // UX: désactiver le bouton à la soumission et afficher un spinner
  const saveBtn = document.getElementById('saveBtn');
  const saveSpinner = document.getElementById('saveSpinner');
  form.addEventListener('submit', function() {
    if (saveBtn) {
      saveBtn.disabled = true;
      if (saveSpinner) saveSpinner.classList.remove('d-none');
    }
  });
});
</script>
@endpush
