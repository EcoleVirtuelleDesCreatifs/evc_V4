@extends('layouts.ki-admin')

@section('title', 'Modifier ' . $student->full_name . ' - EVC 2024')
@section('page-title', 'Modifier Étudiant')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-edit me-2"></i>
                        Modifier {{ $student->full_name }}
                    </h5>
                    <div class="btn-group">
                        <a href="{{ route('students.show', $student) }}" class="btn btn-outline-light">
                            <i class="fas fa-eye me-2"></i>
                            Voir le profil
                        </a>
                        <a href="{{ route('students.index') }}" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-2"></i>
                            Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('students.update', $student) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Personal Information -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-user me-2"></i>
                                Informations Personnelles
                            </h6>
                            
                            <!-- Current Photo Display -->
                            @if($student->profile_photo)
                            <div class="mb-3 text-center">
                                <img src="{{ $student->profile_photo_url }}" 
                                     alt="{{ $student->full_name }}" 
                                     class="rounded-circle mb-2" 
                                     width="100" height="100"
                                     style="object-fit: cover;">
                                <p class="text-muted small">Photo actuelle</p>
                            </div>
                            @endif
                            
                            <div class="mb-3">
                                <label for="first_name" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('first_name') is-invalid @enderror" 
                                       id="first_name" 
                                       name="first_name" 
                                       value="{{ old('first_name', $student->first_name) }}" 
                                       required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="last_name" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('last_name') is-invalid @enderror" 
                                       id="last_name" 
                                       name="last_name" 
                                       value="{{ old('last_name', $student->last_name) }}" 
                                       required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $student->email) }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Téléphone</label>
                                <input type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $student->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="date_of_birth" class="form-label">Date de naissance</label>
                                <input type="date" 
                                       class="form-control @error('date_of_birth') is-invalid @enderror" 
                                       id="date_of_birth" 
                                       name="date_of_birth" 
                                       value="{{ old('date_of_birth', $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="gender" class="form-label">Genre</label>
                                <select class="form-select @error('gender') is-invalid @enderror" 
                                        id="gender" 
                                        name="gender">
                                    <option value="">Sélectionner...</option>
                                    <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Masculin</option>
                                    <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Féminin</option>
                                    <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="profile_photo" class="form-label">Nouvelle photo de profil</label>
                                <input type="file" 
                                       class="form-control @error('profile_photo') is-invalid @enderror" 
                                       id="profile_photo" 
                                       name="profile_photo" 
                                       accept="image/*">
                                @error('profile_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Formats acceptés: JPEG, PNG, JPG, GIF. Taille max: 2MB. Laissez vide pour conserver la photo actuelle.</div>
                            </div>
                        </div>

                        <!-- Academic Information -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-graduation-cap me-2"></i>
                                Informations Académiques
                            </h6>

                            <div class="mb-3">
                                <label for="student_id" class="form-label">ID Étudiant <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('student_id') is-invalid @enderror" 
                                       id="student_id" 
                                       name="student_id" 
                                       value="{{ old('student_id', $student->student_id) }}" 
                                       required>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="program" class="form-label">Programme</label>
                                <input type="text" 
                                       class="form-control @error('program') is-invalid @enderror" 
                                       id="program" 
                                       name="program" 
                                       value="{{ old('program', $student->program) }}" 
                                       placeholder="Ex: Informatique">
                                @error('program')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="level" class="form-label">Niveau</label>
                                <select class="form-select @error('level') is-invalid @enderror" 
                                        id="level" 
                                        name="level">
                                    <option value="">Sélectionner...</option>
                                    <option value="L1" {{ old('level', $student->level) == 'L1' ? 'selected' : '' }}>L1</option>
                                    <option value="L2" {{ old('level', $student->level) == 'L2' ? 'selected' : '' }}>L2</option>
                                    <option value="L3" {{ old('level', $student->level) == 'L3' ? 'selected' : '' }}>L3</option>
                                    <option value="M1" {{ old('level', $student->level) == 'M1' ? 'selected' : '' }}>M1</option>
                                    <option value="M2" {{ old('level', $student->level) == 'M2' ? 'selected' : '' }}>M2</option>
                                </select>
                                @error('level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="specialization" class="form-label">Spécialisation</label>
                                <input type="text" 
                                       class="form-control @error('specialization') is-invalid @enderror" 
                                       id="specialization" 
                                       name="specialization" 
                                       value="{{ old('specialization', $student->specialization) }}" 
                                       placeholder="Ex: Développement Web">
                                @error('specialization')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Statut</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status">
                                    <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactive" {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>Inactif</option>
                                    <option value="graduated" {{ old('status', $student->status) == 'graduated' ? 'selected' : '' }}>Diplômé</option>
                                    <option value="suspended" {{ old('status', $student->status) == 'suspended' ? 'selected' : '' }}>Suspendu</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="gpa" class="form-label">GPA</label>
                                <input type="number" 
                                       class="form-control @error('gpa') is-invalid @enderror" 
                                       id="gpa" 
                                       name="gpa" 
                                       value="{{ old('gpa', $student->gpa) }}" 
                                       step="0.01" 
                                       min="0" 
                                       max="4" 
                                       placeholder="Ex: 3.75">
                                @error('gpa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Sur une échelle de 0 à 4</div>
                            </div>

                            <div class="mb-3">
                                <label for="credits_earned" class="form-label">Crédits obtenus</label>
                                <input type="number" 
                                       class="form-control @error('credits_earned') is-invalid @enderror" 
                                       id="credits_earned" 
                                       name="credits_earned" 
                                       value="{{ old('credits_earned', $student->credits_earned) }}" 
                                       min="0" 
                                       placeholder="Ex: 60">
                                @error('credits_earned')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                Adresse
                            </h6>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="address" class="form-label">Adresse complète</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" 
                                          name="address" 
                                          rows="3" 
                                          placeholder="Numéro, rue, quartier...">{{ old('address', $student->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="city" class="form-label">Ville</label>
                                <input type="text" 
                                       class="form-control @error('city') is-invalid @enderror" 
                                       id="city" 
                                       name="city" 
                                       value="{{ old('city', $student->city) }}" 
                                       placeholder="Ex: Paris">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="country" class="form-label">Pays</label>
                                <input type="text" 
                                       class="form-control @error('country') is-invalid @enderror" 
                                       id="country" 
                                       name="country" 
                                       value="{{ old('country', $student->country) }}">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('students.show', $student) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>
                                    Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    Mettre à jour
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .form-label {
        font-weight: 600;
        color: #495057;
    }
    
    .text-danger {
        color: #dc3545 !important;
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    }
    
    .is-invalid {
        border-color: #dc3545;
    }
    
    .invalid-feedback {
        display: block;
    }
</style>
@endsection

@section('scripts')
<script>
    // Preview uploaded image
    document.getElementById('profile_photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // You can add image preview functionality here
                console.log('New image selected:', file.name);
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
