@extends('layouts.admin')

@section('title', 'Créer une Formation - Administration')

@push('styles')
<style>
.form-container {
    background: white;
    padding: 30px;
    margin: 20px auto;
    max-width: 900px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    box-sizing: border-box;
}

.form-header {
    text-align: center;
    margin-bottom: 30px;
    color: #2d3748;
}

.form-header h1 {
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 10px;
    color: #1a202c;
}

.form-step {
    display: none;
    animation: fadeIn 0.3s ease-in;
}

.form-step.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.step-header {
    text-align: center;
    margin-bottom: 30px;
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
}

.step-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #374151;
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 2px solid #e5e7eb;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.2s;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-row {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.form-row > div {
    flex: 1;
}

.btn-group {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.progress-bar {
    width: 100%;
    height: 6px;
    background: #e5e7eb;
    border-radius: 3px;
    margin-bottom: 30px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #667eea, #764ba2);
    border-radius: 3px;
    transition: width 0.3s ease;
    width: 20%;
}

.step-indicators {
    display: flex;
    justify-content: center;
    margin-bottom: 30px;
    gap: 10px;
}

.step-indicator {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: #6b7280;
    transition: all 0.2s;
}

.step-indicator.active {
    background: #667eea;
    color: white;
}

.step-indicator.completed {
    background: #10b981;
    color: white;
}

@media (max-width: 768px) {
    .form-container {
        margin: 10px;
        padding: 20px;
    }
    
    .form-row {
        flex-direction: column;
        gap: 0;
    }
    
    .btn-group {
        flex-direction: column;
        gap: 10px;
    }
    
    .step-indicators {
        flex-wrap: wrap;
    }
}
</style>
@endpush

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1>Créer une Nouvelle Formation</h1>
        <p>Assistant de création multi-étapes</p>
    </div>

    <div class="progress-bar">
        <div class="progress-fill" id="progressFill"></div>
    </div>

    <div class="step-indicators">
        <div class="step-indicator active" data-step="1">1</div>
        <div class="step-indicator" data-step="2">2</div>
        <div class="step-indicator" data-step="3">3</div>
        <div class="step-indicator" data-step="4">4</div>
        <div class="step-indicator" data-step="5">5</div>
    </div>

    <form method="POST" action="{{ route('admin.formations.store') }}" id="formationForm">
        @csrf

        <!-- Étape 1: Informations de base -->
        <div class="form-step active" data-step="1">
            <div class="step-header">
                <h2 class="step-title">📚 Informations de Base</h2>
            </div>

            <div class="form-group">
                <label for="name" class="form-label">Nom de la formation *</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="category_id" class="form-label">Catégorie *</label>
                    <select class="form-control @error('category_id') is-invalid @enderror" 
                            id="category_id" name="category_id" required>
                        <option value="">Sélectionner une catégorie</option>
                        <option value="1" {{ old('category_id') == '1' ? 'selected' : '' }}>Design Graphique</option>
                        <option value="2" {{ old('category_id') == '2' ? 'selected' : '' }}>Développement Web</option>
                        <option value="3" {{ old('category_id') == '3' ? 'selected' : '' }}>Marketing Digital</option>
                    </select>
                    @error('category_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="level" class="form-label">Niveau *</label>
                    <select class="form-control @error('level') is-invalid @enderror" 
                            id="level" name="level" required>
                        <option value="">Sélectionner un niveau</option>
                        <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>Débutant</option>
                        <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>Intermédiaire</option>
                        <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>Avancé</option>
                    </select>
                    @error('level')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="duration_weeks" class="form-label">Durée (semaines) *</label>
                    <input type="number" class="form-control @error('duration_weeks') is-invalid @enderror" 
                           id="duration_weeks" name="duration_weeks" value="{{ old('duration_weeks') }}" 
                           min="1" max="52" required>
                    @error('duration_weeks')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price" class="form-label">Prix (€) *</label>
                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                           id="price" name="price" value="{{ old('price') }}" 
                           step="0.01" min="0" required>
                    @error('price')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Étape 2: Description -->
        <div class="form-step" data-step="2">
            <div class="step-header">
                <h2 class="step-title">📝 Description et Contenu</h2>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="6" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="objectives" class="form-label">Objectifs pédagogiques</label>
                <textarea class="form-control @error('objectives') is-invalid @enderror" 
                          id="objectives" name="objectives" rows="4">{{ old('objectives') }}</textarea>
                @error('objectives')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Étape 3: Configuration -->
        <div class="form-step" data-step="3">
            <div class="step-header">
                <h2 class="step-title">⚙️ Configuration</h2>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="format" class="form-label">Format *</label>
                    <select class="form-control @error('format') is-invalid @enderror" 
                            id="format" name="format" required>
                        <option value="">Sélectionner un format</option>
                        <option value="online" {{ old('format') == 'online' ? 'selected' : '' }}>En ligne</option>
                        <option value="offline" {{ old('format') == 'offline' ? 'selected' : '' }}>Présentiel</option>
                        <option value="hybrid" {{ old('format') == 'hybrid' ? 'selected' : '' }}>Hybride</option>
                    </select>
                    @error('format')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="max_students" class="form-label">Nombre max d'étudiants</label>
                    <input type="number" class="form-control @error('max_students') is-invalid @enderror" 
                           id="max_students" name="max_students" value="{{ old('max_students') }}" 
                           min="1">
                    @error('max_students')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Statut *</label>
                <select class="form-control @error('status') is-invalid @enderror" 
                        id="status" name="status" required>
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Publié</option>
                    <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archivé</option>
                </select>
                @error('status')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Étape 4: Ciblage -->
        <div class="form-step" data-step="4">
            <div class="step-header">
                <h2 class="step-title">🎯 Ciblage des Étudiants</h2>
            </div>

            <div class="form-group">
                <label for="student_restriction" class="form-label">Restriction d'accès *</label>
                <select class="form-control @error('student_restriction') is-invalid @enderror" 
                        id="student_restriction" name="student_restriction" required>
                    <option value="all" {{ old('student_restriction') == 'all' ? 'selected' : '' }}>Tous les étudiants</option>
                    <option value="active_only" {{ old('student_restriction') == 'active_only' ? 'selected' : '' }}>Étudiants actifs uniquement</option>
                    <option value="registration_period" {{ old('student_restriction') == 'registration_period' ? 'selected' : '' }}>Période d'inscription</option>
                </select>
                @error('student_restriction')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div id="registration_period_fields" style="display: none;">
                <div class="form-row">
                    <div class="form-group">
                        <label for="registration_start" class="form-label">Début inscription</label>
                        <input type="datetime-local" class="form-control @error('registration_start') is-invalid @enderror" 
                               id="registration_start" name="registration_start" value="{{ old('registration_start') }}">
                        @error('registration_start')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="registration_end" class="form-label">Fin inscription</label>
                        <input type="datetime-local" class="form-control @error('registration_end') is-invalid @enderror" 
                               id="registration_end" name="registration_end" value="{{ old('registration_end') }}">
                        @error('registration_end')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Étape 5: Finalisation -->
        <div class="form-step" data-step="5">
            <div class="step-header">
                <h2 class="step-title">✅ Finalisation</h2>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="start_date" class="form-label">Date de début</label>
                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                           id="start_date" name="start_date" value="{{ old('start_date') }}">
                    @error('start_date')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_date" class="form-label">Date de fin</label>
                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                           id="end_date" name="end_date" value="{{ old('end_date') }}">
                    @error('end_date')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="btn-group">
            <button type="button" class="btn btn-secondary" id="prevBtn" disabled>
                ← Précédent
            </button>
            <button type="button" class="btn btn-primary" id="nextBtn">
                Suivant →
            </button>
            <button type="submit" class="btn btn-primary" id="submitBtn" style="display: none;">
                Créer la Formation
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 5;
    
    const steps = document.querySelectorAll('.form-step');
    const indicators = document.querySelectorAll('.step-indicator');
    const progressFill = document.getElementById('progressFill');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const restrictionSelect = document.getElementById('student_restriction');
    const registrationFields = document.getElementById('registration_period_fields');

    function updateProgress() {
        const progress = (currentStep / totalSteps) * 100;
        progressFill.style.width = progress + '%';
    }

    function updateIndicators() {
        indicators.forEach((indicator, index) => {
            const stepNumber = index + 1;
            indicator.classList.remove('active', 'completed');
            
            if (stepNumber < currentStep) {
                indicator.classList.add('completed');
                indicator.innerHTML = '✓';
            } else if (stepNumber === currentStep) {
                indicator.classList.add('active');
                indicator.innerHTML = stepNumber;
            } else {
                indicator.innerHTML = stepNumber;
            }
        });
    }

    function showStep(step) {
        steps.forEach(stepEl => {
            stepEl.classList.remove('active');
        });
        
        const targetStep = document.querySelector(`[data-step="${step}"]`);
        if (targetStep) {
            targetStep.classList.add('active');
        }
        
        prevBtn.disabled = step === 1;
        
        if (step === totalSteps) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'block';
        } else {
            nextBtn.style.display = 'block';
            submitBtn.style.display = 'none';
        }
        
        updateProgress();
        updateIndicators();
    }

    nextBtn.addEventListener('click', function() {
        if (currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
        }
    });

    prevBtn.addEventListener('click', function() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });

    // Registration period toggle
    if (restrictionSelect) {
        restrictionSelect.addEventListener('change', function() {
            if (this.value === 'registration_period') {
                registrationFields.style.display = 'block';
            } else {
                registrationFields.style.display = 'none';
            }
        });
    }

    // Initialize
    showStep(1);
});
</script>
@endpush
@endsection
                            <input type="checkbox" name="target_student_types[]" value="community_management" 
                                   {{ in_array('community_management', old('target_student_types', [])) ? 'checked' : '' }}>
                            <div class="level-card">
                                <div class="level-icon">📱</div>
                                <span>Community Management</span>
                            </div>
                        </label>
                        <label class="level-option">
                            <input type="checkbox" name="target_student_types[]" value="gestion_informatique" 
                                   {{ in_array('gestion_informatique', old('target_student_types', [])) ? 'checked' : '' }}>
                            <div class="level-card">
                                <div class="level-icon">💻</div>
                                <span>Gestion IT</span>
                            </div>
                        </label>
                        <label class="level-option">
                            <input type="checkbox" name="target_student_types[]" value="intelligence_artificielle" 
                                   {{ in_array('intelligence_artificielle', old('target_student_types', [])) ? 'checked' : '' }}>
                            <div class="level-card">
                                <div class="level-icon">🤖</div>
                                <span>Intelligence Artificielle</span>
                            </div>
                        </label>
                    </div>

                    <div id="registration_period_fields" style="display: none;">
                        <div class="form-row">
                            <div class="input-group">
                                <input type="datetime-local" 
                                       class="form-input @error('registration_start') error @enderror" 
                                       id="registration_start" 
                                       name="registration_start" 
                                       value="{{ old('registration_start') }}">
                                <label class="floating-label" for="registration_start">Début d'Inscription</label>
                                <div class="input-border"></div>
                                @error('registration_start')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="input-group">
                                <input type="datetime-local" 
                                       class="form-input @error('registration_end') error @enderror" 
                                       id="registration_end" 
                                       name="registration_end" 
                                       value="{{ old('registration_end') }}">
                                <label class="floating-label" for="registration_end">Fin d'Inscription</label>
                                <div class="input-border"></div>
                                @error('registration_end')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 5: Finalisation -->
            <div class="wizard-step" data-step="5">
                <div class="step-animation">
                    <div class="step-header">
                        <div class="step-icon">🎯</div>
                        <h2>Finalisation</h2>
                        <p>Définissez les dates et finalisez votre formation</p>
                    </div>

                    <div class="form-row">
                        <div class="input-group">
                            <input type="datetime-local" 
                                   class="form-input @error('start_date') error @enderror" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ old('start_date') }}">
                            <label class="floating-label" for="start_date">Date de Début</label>
                            <div class="input-border"></div>
                            @error('start_date')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-group">
                            <input type="datetime-local" 
                                   class="form-input @error('end_date') error @enderror" 
                                   id="end_date" 
                                   name="end_date" 
                                   value="{{ old('end_date') }}">
                            <label class="floating-label" for="end_date">Date de Fin</label>
                            <div class="input-border"></div>
                            @error('end_date')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="wizard-navigation">
                <button type="button" class="nav-btn prev-btn" id="prevBtn" disabled>
                    <i class="fas fa-arrow-left"></i>
                    Précédent
                </button>
                <button type="button" class="nav-btn next-btn" id="nextBtn">
                    Suivant
                    <i class="fas fa-arrow-right"></i>
                </button>
                <button type="submit" class="nav-btn submit-btn" id="submitBtn" style="display: none;">
                    <i class="fas fa-rocket"></i>
                    Créer la Formation
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 5;
    
    const steps = document.querySelectorAll('.step');
    const wizardSteps = document.querySelectorAll('.wizard-step');
    const progressFill = document.getElementById('progressFill');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const priceBadge = document.getElementById('priceBadge');
    const priceInput = document.getElementById('price');
    const restrictionSelect = document.getElementById('student_restriction');
    const registrationFields = document.getElementById('registration_period_fields');

    // Update progress bar
    function updateProgress() {
        const progress = (currentStep / totalSteps) * 100;
        progressFill.style.width = progress + '%';
    }

    // Update step indicators
    function updateStepIndicators() {
        steps.forEach((step, index) => {
            const stepNumber = index + 1;
            step.classList.remove('active', 'completed');
            
            if (stepNumber < currentStep) {
                step.classList.add('completed');
                step.querySelector('.step-circle').innerHTML = '<i class="fas fa-check"></i>';
            } else if (stepNumber === currentStep) {
                step.classList.add('active');
                step.querySelector('.step-circle').innerHTML = stepNumber;
            } else {
                step.querySelector('.step-circle').innerHTML = stepNumber;
            }
        });
    }

    // Show current step
    function showStep(step) {
        wizardSteps.forEach(wizardStep => {
            wizardStep.classList.remove('active');
        });
        
        const targetStep = document.querySelector(`[data-step="${step}"]`);
        if (targetStep) {
            targetStep.classList.add('active');
        }
        
        // Update navigation buttons
        prevBtn.disabled = step === 1;
        
        if (step === totalSteps) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'flex';
        } else {
            nextBtn.style.display = 'flex';
            submitBtn.style.display = 'none';
        }
        
        updateProgress();
        updateStepIndicators();
    }

    // Next button click
    nextBtn.addEventListener('click', function() {
        if (currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
        }
    });

    // Previous button click
    prevBtn.addEventListener('click', function() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });

    // Step click navigation
    steps.forEach((step, index) => {
        step.addEventListener('click', function() {
            const targetStep = index + 1;
            currentStep = targetStep;
            showStep(currentStep);
        });
    });

    // Price indicator update
    if (priceInput) {
        priceInput.addEventListener('input', function() {
            const price = parseFloat(this.value) || 0;
            if (price === 0) {
                priceBadge.className = 'price-badge internal';
                priceBadge.innerHTML = '<i class="fas fa-graduation-cap"></i> Formation Interne EVC';
            } else {
                priceBadge.className = 'price-badge external';
                priceBadge.innerHTML = '<i class="fas fa-euro-sign"></i> Formation Externe Payante';
            }
        });
    }

    // Registration period toggle
    if (restrictionSelect) {
        restrictionSelect.addEventListener('change', function() {
            if (this.value === 'registration_period') {
                registrationFields.style.display = 'block';
            } else {
                registrationFields.style.display = 'none';
            }
        });
    }

    // Initialize
    showStep(1);
});
</script>
@endpush 
                                           placeholder="Ex: 299.99"
                                           required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">0€ = Formation interne EVC</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="format" class="form-label">Format *</label>
                                    <select class="form-control @error('format') is-invalid @enderror" 
                                            id="format" 
                                            name="format" 
                                            required>
                                        <option value="">-- Sélectionner un format --</option>
                                        <option value="online" {{ old('format') == 'online' ? 'selected' : '' }}>
                                            💻 En ligne
                                        </option>
                                        <option value="offline" {{ old('format') == 'offline' ? 'selected' : '' }}>
                                            👥 Présentiel
                                        </option>
                                        <option value="hybrid" {{ old('format') == 'hybrid' ? 'selected' : '' }}>
                                            🔄 Hybride
                                        </option>
                                    </select>
                                    @error('format')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label">Statut *</label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" 
                                            name="status" 
                                            required>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                                            📝 Brouillon
                                        </option>
                                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                                            ✅ Publié
                                        </option>
                                        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>
                                            📦 Archivé
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Section 2: Contenu -->
                        <div class="row mb-4 mt-5">
                            <div class="col-12">
                                <h4 class="text-primary mb-3">
                                    <i class="fas fa-edit"></i>
                                    Contenu et Description
                                </h4>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description" class="form-label">Description Complète *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="6" 
                                      placeholder="Décrivez en détail le contenu, les objectifs et les compétences acquises..."
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="short_description" class="form-label">Description Courte</label>
                            <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                      id="short_description" 
                                      name="short_description" 
                                      rows="3" 
                                      placeholder="Résumé en quelques lignes pour les aperçus...">{{ old('short_description') }}</textarea>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
            <!-- Navigation -->
            <div class="wizard-navigation">
                <button type="button" class="nav-btn prev-btn" id="prevBtn" disabled>
                    <i class="fas fa-arrow-left"></i>
                    Précédent
                </button>
                <button type="button" class="nav-btn next-btn" id="nextBtn">
                    Suivant
                    <i class="fas fa-arrow-right"></i>
                </button>
                <button type="submit" class="nav-btn submit-btn" id="submitBtn" style="display: none;">
                    <i class="fas fa-rocket"></i>
                    Créer la Formation
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 5;
    
    const steps = document.querySelectorAll('.step');
    const wizardSteps = document.querySelectorAll('.wizard-step');
    const progressFill = document.getElementById('progressFill');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const priceBadge = document.getElementById('priceBadge');
    const priceInput = document.getElementById('price');
    const restrictionSelect = document.getElementById('student_restriction');
    const registrationFields = document.getElementById('registration_period_fields');

    // Update progress bar
    function updateProgress() {
        const progress = (currentStep / totalSteps) * 100;
        progressFill.style.width = progress + '%';
    }

    // Update step indicators
    function updateStepIndicators() {
        steps.forEach((step, index) => {
            const stepNumber = index + 1;
            step.classList.remove('active', 'completed');
            
            if (stepNumber < currentStep) {
                step.classList.add('completed');
                step.querySelector('.step-circle').innerHTML = '<i class="fas fa-check"></i>';
            } else if (stepNumber === currentStep) {
                step.classList.add('active');
                step.querySelector('.step-circle').innerHTML = stepNumber;
            } else {
                step.querySelector('.step-circle').innerHTML = stepNumber;
            }
        });
    }

    // Show current step
    function showStep(step) {
        wizardSteps.forEach(wizardStep => {
            wizardStep.classList.remove('active');
        });
        
        const targetStep = document.querySelector(`[data-step="${step}"]`);
        if (targetStep) {
            targetStep.classList.add('active');
        }
        
        // Update navigation buttons
        prevBtn.disabled = step === 1;
        
        if (step === totalSteps) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'flex';
        } else {
            nextBtn.style.display = 'flex';
            submitBtn.style.display = 'none';
        }
        
        updateProgress();
        updateStepIndicators();
    }

    // Next button click
    nextBtn.addEventListener('click', function() {
        if (currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
        }
    });

    // Previous button click
    prevBtn.addEventListener('click', function() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });

    // Price indicator update
    if (priceInput) {
        priceInput.addEventListener('input', function() {
            const price = parseFloat(this.value) || 0;
            if (price === 0) {
                priceBadge.className = 'price-badge internal';
                priceBadge.innerHTML = '<i class="fas fa-graduation-cap"></i> Formation Interne EVC';
            } else {
                priceBadge.className = 'price-badge external';
                priceBadge.innerHTML = '<i class="fas fa-euro-sign"></i> Formation Externe Payante';
            }
        });
    }

    // Registration period toggle
    if (restrictionSelect) {
        restrictionSelect.addEventListener('change', function() {
            if (this.value === 'registration_period') {
                registrationFields.style.display = 'block';
            } else {
                registrationFields.style.display = 'none';
            }
        });
    }

    // Initialize
    showStep(1);
});
</script>
@endpush
@endsection
