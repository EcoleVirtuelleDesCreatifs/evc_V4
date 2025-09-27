<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test - Créer Formation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Test - Formulaire de Création de Formation</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.formations.store') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom de la Formation *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Catégorie *</label>
                                <select class="form-control" id="category_id" name="category_id" required>
                                    <option value="">Sélectionner une catégorie</option>
                                    @if(isset($categories))
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @else
                                        <option value="1">Design Graphique</option>
                                        <option value="2">Community Management</option>
                                        <option value="3">Intelligence Artificielle</option>
                                        <option value="4">Gestion Informatique</option>
                                    @endif
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description détaillée *</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="short_description" class="form-label">Description Courte</label>
                                <textarea class="form-control" id="short_description" name="short_description" rows="3"></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="duration_weeks" class="form-label">Durée (semaines)</label>
                                        <input type="number" class="form-control" id="duration_weeks" name="duration_weeks" min="1" max="52">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Prix (FCFA)</label>
                                        <input type="number" class="form-control" id="price" name="price" min="0" step="1000">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="level" class="form-label">Niveau</label>
                                <select class="form-control" id="level" name="level">
                                    <option value="beginner">Débutant</option>
                                    <option value="intermediate">Intermédiaire</option>
                                    <option value="advanced">Avancé</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="is_internal" name="is_internal" value="1">
                                        <label class="form-check-label" for="is_internal">Formation interne EVC</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1">
                                        <label class="form-check-label" for="is_featured">Mettre en avant (featured)</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Publics ciblés</label>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="target_student_types[]" id="tst_design" value="design_graphique">
                                            <label class="form-check-label" for="tst_design">Design Graphique</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="target_student_types[]" id="tst_cm" value="community_management">
                                            <label class="form-check-label" for="tst_cm">Community Management</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="target_student_types[]" id="tst_gi" value="gestion_informatique">
                                            <label class="form-check-label" for="tst_gi">Gestion Informatique</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="target_student_types[]" id="tst_ia" value="intelligence_artificielle">
                                            <label class="form-check-label" for="tst_ia">Intelligence Artificielle</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="student_restriction" class="form-label">Restriction d'inscription</label>
                                        <select class="form-control" id="student_restriction" name="student_restriction">
                                            <option value="all">Tous les étudiants</option>
                                            <option value="active_only">Étudiants actifs uniquement</option>
                                            <option value="registration_period">Période d'inscription spécifique</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="registration_start" class="form-label">Début inscription</label>
                                        <input type="date" class="form-control" id="registration_start" name="registration_start">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="registration_end" class="form-label">Fin inscription</label>
                                        <input type="date" class="form-control" id="registration_end" name="registration_end">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="image_url" class="form-label">URL de l'image</label>
                                <input type="url" class="form-control" id="image_url" name="image_url" placeholder="https://example.com/image.jpg">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Statut</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="draft">Brouillon</option>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                            <option value="archived">Archivée</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="format" class="form-label">Format</label>
                                        <select class="form-control" id="format" name="format">
                                            <option value="online">En ligne</option>
                                            <option value="offline">Présentiel</option>
                                            <option value="hybrid">Hybride</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label">Date de début</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label">Date de fin</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="instructor_name" class="form-label">Nom du formateur</label>
                                        <input type="text" class="form-control" id="instructor_name" name="instructor_name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_students" class="form-label">Nombre max d'étudiants</label>
                                        <input type="number" class="form-control" id="max_students" name="max_students" min="1">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="instructor_bio" class="form-label">Bio du formateur</label>
                                <textarea class="form-control" id="instructor_bio" name="instructor_bio" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="location" class="form-label">Lieu</label>
                                <input type="text" class="form-control" id="location" name="location" placeholder="Abidjan, Plateau ...">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="satisfaction_rate" class="form-label">Taux de satisfaction (%)</label>
                                        <input type="number" step="0.1" min="0" max="100" class="form-control" id="satisfaction_rate" name="satisfaction_rate">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="completion_rate" class="form-label">Taux de complétion (%)</label>
                                        <input type="number" min="0" max="100" class="form-control" id="completion_rate" name="completion_rate">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="skills" class="form-label">Compétences (JSON ou liste séparée par virgule)</label>
                                <textarea class="form-control" id="skills" name="skills" rows="2" placeholder='["Photoshop","Illustrator"]'></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="prerequisites" class="form-label">Prérequis (JSON ou liste)</label>
                                <textarea class="form-control" id="prerequisites" name="prerequisites" rows="2" placeholder='["Ordinateur","Connexion Internet"]'></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="modules" class="form-label">Modules (JSON)</label>
                                <textarea class="form-control" id="modules" name="modules" rows="3" placeholder='[{"title":"Photoshop","weeks":4}]'></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="schedule" class="form-label">Horaires (JSON)</label>
                                <textarea class="form-control" id="schedule" name="schedule" rows="2" placeholder='{"Mon":"09:00-12:00"}'></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="resources" class="form-label">Ressources (JSON)</label>
                                <textarea class="form-control" id="resources" name="resources" rows="2" placeholder='["PDF cours","Liens utiles"]'></textarea>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Créer la Formation</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="mt-3">
                    <h5>Informations de Debug :</h5>
                    <ul>
                        <li>Categories disponibles : {{ isset($categories) ? $categories->count() : 'Non définies' }}</li>
                        <li>Route actuelle : {{ Route::currentRouteName() }}</li>
                        <li>URL actuelle : {{ url()->current() }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
