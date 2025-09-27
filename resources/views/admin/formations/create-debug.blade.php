@extends('layouts.admin')

@section('title', 'Créer une Formation - Test Debug')

@push('styles')
<style>
.debug-container {
    background: rgba(255, 255, 255, 0.1);
    padding: 30px;
    margin: 20px auto;
    max-width: 900px;
    border-radius: 8px;
    border: 2px solid #667eea;
}

.debug-header {
    text-align: center;
    margin-bottom: 30px;
    color: white;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
    border-radius: 8px;
}

.debug-form-group {
    margin-bottom: 20px;
    padding: 15px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.debug-label {
    display: block !important;
    margin-bottom: 8px;
    font-weight: 600;
    color: #fff !important;
    font-size: 14px;
}

.debug-input {
    width: 100% !important;
    padding: 12px !important;
    border: 2px solid #667eea !important;
    border-radius: 6px !important;
    font-size: 14px !important;
    background: white !important;
    color: #333 !important;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.debug-input:focus {
    outline: none !important;
    border-color: #764ba2 !important;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3) !important;
}

.debug-row {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.debug-row > div {
    flex: 1;
}

.debug-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
    padding: 15px 30px !important;
    border: none !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    font-size: 16px !important;
    width: 100% !important;
    margin-top: 20px !important;
}

.debug-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

@media (max-width: 768px) {
    .debug-row {
        flex-direction: column;
        gap: 0;
    }
}
</style>
@endpush

@section('content')
<div class="debug-container">
    <div class="debug-header">
        <h1>🔧 Formulaire de Test - Création Formation</h1>
        <p>Version Debug - Tous les champs visibles</p>
    </div>

    <form method="POST" action="{{ route('admin.formations.store') }}" id="debugFormationForm">
        @csrf
        
        <!-- Informations de Base -->
        <div class="debug-form-group">
            <h3 style="color: #667eea; margin-bottom: 15px;">📚 Informations de Base</h3>
            
            <div class="debug-form-group">
                <label for="name" class="debug-label">Nom de la Formation *</label>
                <input type="text" class="debug-input" id="name" name="name" 
                       value="{{ old('name') }}" required placeholder="Ex: Formation Design Graphique Avancé">
                @error('name')
                    <div style="color: #ff6b6b; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="debug-row">
                <div class="debug-form-group">
                    <label for="category_id" class="debug-label">Catégorie *</label>
                    <select class="debug-input" id="category_id" name="category_id" required>
                        <option value="">Sélectionner une catégorie</option>
                        @if(isset($categories))
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('category_id')
                        <div style="color: #ff6b6b; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="debug-form-group">
                    <label for="level" class="debug-label">Niveau *</label>
                    <select class="debug-input" id="level" name="level" required>
                        <option value="">Sélectionner un niveau</option>
                        <option value="debutant" {{ old('level') == 'debutant' ? 'selected' : '' }}>Débutant</option>
                        <option value="intermediaire" {{ old('level') == 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                        <option value="avance" {{ old('level') == 'avance' ? 'selected' : '' }}>Avancé</option>
                    </select>
                    @error('level')
                        <div style="color: #ff6b6b; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="debug-row">
                <div class="debug-form-group">
                    <label for="duration_weeks" class="debug-label">Durée (semaines) *</label>
                    <input type="number" class="debug-input" id="duration_weeks" name="duration_weeks" 
                           value="{{ old('duration_weeks') }}" min="1" max="52" required placeholder="Ex: 12">
                    @error('duration_weeks')
                        <div style="color: #ff6b6b; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="debug-form-group">
                    <label for="price" class="debug-label">Prix (FCFA) *</label>
                    <input type="number" class="debug-input" id="price" name="price" 
                           value="{{ old('price') }}" min="0" step="1000" required placeholder="Ex: 50000">
                    <small style="color: #ccc; font-size: 12px;">Prix 0 = Formation interne gratuite</small>
                    @error('price')
                        <div style="color: #ff6b6b; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="debug-form-group">
                <label for="image_url" class="debug-label">URL de l'Image</label>
                <input type="url" class="debug-input" id="image_url" name="image_url" 
                       value="{{ old('image_url') }}" placeholder="https://example.com/image.jpg">
                @error('image_url')
                    <div style="color: #ff6b6b; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Description -->
        <div class="debug-form-group">
            <h3 style="color: #667eea; margin-bottom: 15px;">📝 Description</h3>
            
            <div class="debug-form-group">
                <label for="short_description" class="debug-label">Description Courte *</label>
                <textarea class="debug-input" id="short_description" name="short_description" 
                          rows="3" required placeholder="Description courte de la formation (max 300 caractères)">{{ old('short_description') }}</textarea>
                <small style="color: #ccc; font-size: 12px;">Maximum 300 caractères</small>
                @error('short_description')
                    <div style="color: #ff6b6b; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="debug-form-group">
                <label for="long_description" class="debug-label">Description Complète</label>
                <textarea class="debug-input" id="long_description" name="long_description" 
                          rows="6" placeholder="Description détaillée de la formation">{{ old('long_description') }}</textarea>
                @error('long_description')
                    <div style="color: #ff6b6b; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Bouton de soumission -->
        <button type="submit" class="debug-btn">
            <i class="fas fa-save" style="margin-right: 10px;"></i>
            Créer la Formation
        </button>
    </form>

    <!-- Informations de debug -->
    <div style="margin-top: 30px; padding: 20px; background: rgba(0,0,0,0.3); border-radius: 8px;">
        <h4 style="color: #667eea; margin-bottom: 15px;">🔍 Informations de Debug</h4>
        <ul style="color: #ccc; line-height: 1.6;">
            <li><strong>Catégories disponibles :</strong> {{ isset($categories) ? $categories->count() : 'Non définies' }}</li>
            <li><strong>Route actuelle :</strong> {{ Route::currentRouteName() }}</li>
            <li><strong>URL actuelle :</strong> {{ url()->current() }}</li>
            <li><strong>Méthode HTTP :</strong> {{ request()->method() }}</li>
            <li><strong>Erreurs de validation :</strong> {{ $errors->count() > 0 ? $errors->count() . ' erreur(s)' : 'Aucune' }}</li>
        </ul>
        
        @if($errors->any())
            <div style="margin-top: 15px;">
                <h5 style="color: #ff6b6b;">Erreurs de validation :</h5>
                <ul style="color: #ff6b6b;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Debug form loaded successfully');
    
    // Vérifier que tous les champs sont visibles
    const inputs = document.querySelectorAll('.debug-input');
    console.log('Nombre de champs trouvés:', inputs.length);
    
    inputs.forEach((input, index) => {
        const style = window.getComputedStyle(input);
        console.log(`Champ ${index + 1} (${input.name}):`, {
            display: style.display,
            visibility: style.visibility,
            opacity: style.opacity
        });
    });
    
    // Test de soumission
    const form = document.getElementById('debugFormationForm');
    form.addEventListener('submit', function(e) {
        console.log('Formulaire soumis');
        
        // Validation basique
        const name = document.getElementById('name').value;
        const category = document.getElementById('category_id').value;
        const level = document.getElementById('level').value;
        
        if (!name || !category || !level) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires (*)');
            return false;
        }
        
        console.log('Données du formulaire:', {
            name: name,
            category_id: category,
            level: level
        });
    });
});
</script>
@endpush
@endsection
