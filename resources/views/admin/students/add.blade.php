@extends('layouts.admin')

@section('title', 'Ajouter un Étudiant')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="page-header mb-4">
        <h1 class="page-title text-white">
            <i class="fas fa-user-plus text-primary me-2"></i>
            Ajouter un Étudiant
        </h1>
        <div class="quick-actions">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Retour
            </a>
        </div>
    </div>

    <!-- Formulaire Principal -->
    <div class="card mb-4" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);">
        <div class="card-header" style="background: rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.1);">
            <h5 class="text-white mb-0"><i class="fas fa-user-edit me-2"></i>Inscription d'un étudiant EVC</h5>
        </div>
        <div class="card-body">
            <form id="studentForm" action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Nom et Prénoms -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="prenom" class="text-white fw-bold mb-2">
                                <i class="fas fa-user me-1 text-primary"></i>Prénoms <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg @error('prenom') is-invalid @enderror" 
                                   id="prenom" name="prenom" value="{{ old('prenom') }}" 
                                   placeholder="Saisir les prénoms" required
                                   style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); color: white;">
                            @error('prenom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nom" class="text-white fw-bold mb-2">
                                <i class="fas fa-user me-1 text-primary"></i>Nom <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg @error('nom') is-invalid @enderror" 
                                   id="nom" name="nom" value="{{ old('nom') }}" 
                                   placeholder="Saisir le nom de famille" required
                                   style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); color: white;">
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Email -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="email" class="text-white fw-bold mb-2">
                                <i class="fas fa-envelope me-1 text-success"></i>Adresse Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" 
                                   placeholder="exemple@email.com" required
                                   style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); color: white;">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Numéro -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="telephone" class="text-white fw-bold mb-2">
                                <i class="fas fa-phone me-1 text-info"></i>Numéro de Téléphone <span class="text-danger">*</span>
                            </label>
                            <input type="tel" class="form-control form-control-lg @error('telephone') is-invalid @enderror" 
                                   id="telephone" name="telephone" value="{{ old('telephone') }}" 
                                   placeholder="+225 XX XX XX XX XX" required
                                   style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); color: white;">
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Formations (Sélection multiple) -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="text-white fw-bold mb-3">
                                <i class="fas fa-graduation-cap me-1 text-warning"></i>Formations <span class="text-danger">*</span>
                                <small class="text-muted d-block mt-1">Sélectionnez une ou plusieurs formations</small>
                            </label>
                            
                            <div class="formations-grid">
                                <!-- Design Graphique -->
                                <div class="formation-card mb-3" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; padding: 15px;">
                                    <div class="form-check">
                                        <input class="form-check-input formation-checkbox" type="checkbox" 
                                               name="formation_souhaitee[]" value="design_graphique" 
                                               id="formation_design_graphique"
                                               {{ in_array('design_graphique', old('formation_souhaitee', [])) ? 'checked' : '' }}
                                               style="transform: scale(1.2);">
                                        <label class="form-check-label text-white fw-bold" for="formation_design_graphique">
                                            <i class="fas fa-palette me-2 text-primary"></i>Design Graphique
                                            <span class="badge bg-primary ms-2">4 mois</span>
                                        </label>
                                        <div class="formation-modules mt-2">
                                            <small class="text-muted">Photoshop • Illustrator • InDesign • Business Strategy</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Community Manager -->
                                <div class="formation-card mb-3" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; padding: 15px;">
                                    <div class="form-check">
                                        <input class="form-check-input formation-checkbox" type="checkbox" 
                                               name="formation_souhaitee[]" value="community_management" 
                                               id="formation_community_management"
                                               {{ in_array('community_management', old('formation_souhaitee', [])) ? 'checked' : '' }}
                                               style="transform: scale(1.2);">
                                        <label class="form-check-label text-white fw-bold" for="formation_community_management">
                                            <i class="fas fa-users me-2 text-success"></i>Community Manager
                                            <span class="badge bg-success ms-2">3 mois</span>
                                        </label>
                                        <div class="formation-modules mt-2">
                                            <small class="text-muted">Gestion réseaux sociaux • Stratégie social media • Création de contenu</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Gestion Informatique -->
                                <div class="formation-card mb-3" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; padding: 15px;">
                                    <div class="form-check">
                                        <input class="form-check-input formation-checkbox" type="checkbox" 
                                               name="formation_souhaitee[]" value="gestion_informatique" 
                                               id="formation_gestion_informatique"
                                               {{ in_array('gestion_informatique', old('formation_souhaitee', [])) ? 'checked' : '' }}
                                               style="transform: scale(1.2);">
                                        <label class="form-check-label text-white fw-bold" for="formation_gestion_informatique">
                                            <i class="fas fa-laptop me-2 text-info"></i>Gestion Informatique
                                            <span class="badge bg-info ms-2">2 mois</span>
                                        </label>
                                        <div class="formation-modules mt-2">
                                            <small class="text-muted">Bureautique • Environnement professionnel</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Intelligence Artificielle -->
                                <div class="formation-card mb-3" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; padding: 15px;">
                                    <div class="form-check">
                                        <input class="form-check-input formation-checkbox" type="checkbox" 
                                               name="formation_souhaitee[]" value="intelligence_artificielle" 
                                               id="formation_intelligence_artificielle"
                                               {{ in_array('intelligence_artificielle', old('formation_souhaitee', [])) ? 'checked' : '' }}
                                               style="transform: scale(1.2);">
                                        <label class="form-check-label text-white fw-bold" for="formation_intelligence_artificielle">
                                            <i class="fas fa-robot me-2 text-warning"></i>Intelligence Artificielle
                                            <span class="badge bg-warning text-dark ms-2">1 mois</span>
                                        </label>
                                        <div class="formation-modules mt-2">
                                            <small class="text-muted">Outils IA pertinents • Process et Pratiques</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @error('formation_souhaitee')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                            @error('formation_souhaitee.*')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>



                <!-- Options d'envoi -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.2);">
                            <div class="card-body">
                                <h6 class="text-white mb-3">
                                    <i class="fas fa-envelope me-2 text-info"></i>Options de Communication
                                </h6>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="send_welcome_email" value="1" 
                                           id="send_welcome_email" 
                                           {{ old('send_welcome_email', true) ? 'checked' : '' }}
                                           style="transform: scale(1.3);">
                                    <label class="form-check-label text-white fw-bold ms-2" for="send_welcome_email">
                                        <i class="fas fa-paper-plane me-2 text-success"></i>
                                        Envoyer un Mail de Bienvenue à ce nouveau étudiant
                                    </label>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            L'étudiant recevra un email avec ses identifiants de connexion et les informations sur sa/ses formation(s).
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-center gap-3">
                            <button type="button" id="submitStudentBtn" class="btn btn-success btn-lg px-5" onclick="testButton()">
                                <i class="fas fa-user-plus me-2"></i>Ajouter l'Étudiant
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-lg px-4">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Modale de Chargement Révolutionnaire -->
                <div class="modal fade" id="loadingModal" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content loading-modal-content">
                            <div class="modal-body text-center p-5">
                                <!-- Loader principal avec anneaux -->
                                <div class="main-loader mb-4">
                                    <div class="loader-ring ring-1"></div>
                                    <div class="loader-ring ring-2"></div>
                                    <div class="loader-ring ring-3"></div>
                                    <div class="loader-core">
                                        <i class="fas fa-paper-plane loader-icon"></i>
                                    </div>
                                </div>
                                
                                <!-- Texte dynamique -->
                                <div class="loader-text mb-4">
                                    <h4 id="loaderTitle" class="text-white fw-bold mb-2">Création de l'étudiant...</h4>
                                    <p id="loaderSubtitle" class="text-light mb-0">Préparation des données</p>
                                </div>
                                
                                <!-- Barre de progression fluide -->
                                <div class="progress-container mb-4">
                                    <div class="progress-bar-revolutionary">
                                        <div class="progress-fill" id="progressFill"></div>
                                        <div class="progress-glow"></div>
                                    </div>
                                    <span class="progress-percentage" id="progressPercentage">0%</span>
                                </div>
                                
                                <!-- Étapes d'envoi -->
                                <div class="email-steps">
                                    <div class="step" id="step1">
                                        <div class="step-icon"><i class="fas fa-database"></i></div>
                                        <span>Enregistrement</span>
                                    </div>
                                    <div class="step" id="step2">
                                        <div class="step-icon"><i class="fas fa-envelope"></i></div>
                                        <span>Préparation Email</span>
                                    </div>
                                    <div class="step" id="step3">
                                        <div class="step-icon"><i class="fas fa-paper-plane"></i></div>
                                        <span>Envoi Email</span>
                                    </div>
                                    <div class="step" id="step4">
                                        <div class="step-icon"><i class="fas fa-check-circle"></i></div>
                                        <span>Terminé</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modale de Confirmation d'Activation -->
    <div class="modal fade" id="activationModal" tabindex="-1" aria-labelledby="activationModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content activation-modal-content" style="background: linear-gradient(135deg, #1a237e 0%, #0d47a1 50%, #ff6f00 100%); border: none; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.4); overflow: hidden; position: relative;">
                
                <!-- Overlay animé -->
                <div class="modal-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(45deg, rgba(255,111,0,0.1) 0%, rgba(26,35,126,0.1) 100%); animation: overlayPulse 3s ease-in-out infinite;"></div>
                
                <!-- Header de la modale -->
                <div class="modal-header border-0 pb-0" style="position: relative; z-index: 10;">
                    <h5 class="modal-title text-white fw-bold d-flex align-items-center" id="activationModalLabel" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                        <i class="fas fa-user-check me-2" style="color: #ff6f00; font-size: 1.5rem; animation: iconGlow 2s ease-in-out infinite;"></i>
                        <span style="background: linear-gradient(45deg, #ffffff, #ff6f00); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Confirmation d'Activation</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="position: relative; z-index: 20; filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.5));"></button>
                </div>

                <!-- Corps de la modale -->
                <div class="modal-body text-center py-4" style="position: relative; z-index: 10;">
                    <!-- Phase de confirmation -->
                    <div id="confirmationPhase">
                        <div class="mb-4">
                            <div class="activation-icon mb-4">
                                <div class="icon-container" style="position: relative; display: inline-block;">
                                    <div class="icon-glow" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 80px; height: 80px; background: radial-gradient(circle, rgba(255,111,0,0.3) 0%, transparent 70%); border-radius: 50%; animation: glowPulse 2s ease-in-out infinite;"></div>
                                    <i class="fas fa-graduation-cap" style="font-size: 4rem; color: #ff6f00; text-shadow: 0 0 20px rgba(255,111,0,0.5); animation: iconBounce 3s ease-in-out infinite; position: relative; z-index: 2;"></i>
                                </div>
                            </div>
                            <h4 class="text-white fw-bold mb-4" style="font-size: 1.8rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); line-height: 1.3;">
                                <span style="background: linear-gradient(45deg, #ffffff, #ff6f00, #ffffff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; animation: textShimmer 3s ease-in-out infinite;">
                                    Vous êtes sur le point d'activer le compte de cet étudiant
                                </span>
                            </h4>
                            <p class="text-light mb-4" style="font-size: 1.1rem; opacity: 0.9; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                                Cette action va créer le compte étudiant et envoyer les informations de connexion par email.
                            </p>
                            <div class="student-preview p-4 mb-4" style="background: linear-gradient(135deg, rgba(255,111,0,0.15) 0%, rgba(26,35,126,0.15) 100%); border-radius: 15px; border: 2px solid rgba(255,111,0,0.3); backdrop-filter: blur(10px); box-shadow: 0 8px 32px rgba(0,0,0,0.2); animation: cardFloat 4s ease-in-out infinite;">
                                <div class="row text-start">
                                    <div class="col-md-6 mb-3">
                                        <small class="text-muted" style="color: #ff6f00 !important; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Étudiant :</small>
                                        <div class="text-white fw-bold" id="studentNamePreview" style="font-size: 1.1rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">-</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <small class="text-muted" style="color: #ff6f00 !important; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Email :</small>
                                        <div class="text-white fw-bold" id="studentEmailPreview" style="font-size: 1.1rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">-</div>
                                    </div>
                                </div>
                                <div class="row text-start">
                                    <div class="col-12">
                                        <small class="text-muted" style="color: #ff6f00 !important; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Formation(s) :</small>
                                        <div class="text-white fw-bold" id="studentFormationsPreview" style="font-size: 1.1rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Phase de loading -->
                    <div id="loadingPhase" style="display: none;">
                        <div class="mb-4">
                            <div class="loading-spinner mb-4">
                                <div class="spinner-container" style="position: relative; display: inline-block;">
                                    <div class="spinner-glow" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100px; height: 100px; background: radial-gradient(circle, rgba(255,111,0,0.4) 0%, transparent 70%); border-radius: 50%; animation: spinnerGlow 1.5s ease-in-out infinite;"></div>
                                    <div class="spinner-border" role="status" style="width: 5rem; height: 5rem; border: 4px solid rgba(255,111,0,0.2); border-top: 4px solid #ff6f00; animation: spin 1s linear infinite; position: relative; z-index: 2;">
                                        <span class="visually-hidden">Chargement...</span>
                                    </div>
                                </div>
                            </div>
                            <h4 class="text-white fw-bold mb-4" style="font-size: 1.8rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                                <span style="background: linear-gradient(45deg, #ffffff, #ff6f00, #ffffff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; animation: textShimmer 2s ease-in-out infinite;">
                                    Activation en cours...
                                </span>
                            </h4>
                            <p class="text-light mb-4" id="loadingMessage" style="font-size: 1.2rem; opacity: 0.9; text-shadow: 1px 1px 2px rgba(0,0,0,0.3); color: #ff6f00 !important; font-weight: 600;">
                                Création du compte étudiant en cours...
                            </p>
                            <div class="progress mt-4" style="height: 12px; background: linear-gradient(90deg, rgba(26,35,126,0.3) 0%, rgba(255,111,0,0.3) 100%); border-radius: 10px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: 0%; background: linear-gradient(90deg, #ff6f00 0%, #ffab40 50%, #ff6f00 100%); border-radius: 10px; box-shadow: 0 2px 8px rgba(255,111,0,0.4);" id="activationProgress"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Phase de succès -->
                    <div id="successPhase" style="display: none;">
                        <div class="mb-4">
                            <div class="success-icon mb-4">
                                <div class="success-container" style="position: relative; display: inline-block;">
                                    <div class="success-glow" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100px; height: 100px; background: radial-gradient(circle, rgba(76,175,80,0.4) 0%, transparent 70%); border-radius: 50%; animation: successGlow 2s ease-in-out infinite;"></div>
                                    <i class="fas fa-check-circle" style="font-size: 5rem; color: #4caf50; text-shadow: 0 0 20px rgba(76,175,80,0.5); animation: successBounce 0.8s ease-out; position: relative; z-index: 2;"></i>
                                </div>
                            </div>
                            <h4 class="text-white fw-bold mb-4" style="font-size: 1.8rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                                <span style="background: linear-gradient(45deg, #ffffff, #4caf50, #ffffff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                    Compte activé avec succès !
                                </span>
                            </h4>
                            <p class="text-light mb-0" id="successMessage" style="font-size: 1.1rem; opacity: 0.9; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                                L'étudiant a été inscrit et l'email de bienvenue a été envoyé.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Footer de la modale -->
                <div class="modal-footer border-0 pt-0" style="position: relative; z-index: 15; padding: 1.5rem;">
                    <div id="confirmationButtons" class="w-100 d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-lg px-4 py-3" data-bs-dismiss="modal" 
                                style="background: linear-gradient(135deg, rgba(108,117,125,0.8) 0%, rgba(52,58,64,0.9) 100%); 
                                       border: 2px solid rgba(255,255,255,0.2); 
                                       color: white; 
                                       border-radius: 12px; 
                                       font-weight: 600; 
                                       text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
                                       box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                                       transition: all 0.3s ease;
                                       position: relative;
                                       z-index: 20;"
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(0,0,0,0.3)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.2)';">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                        <button type="button" class="btn btn-lg px-5 py-3" id="confirmActivationBtn"
                                style="background: linear-gradient(135deg, #ff6f00 0%, #ff8f00 50%, #ff6f00 100%); 
                                       border: 2px solid rgba(255,255,255,0.3); 
                                       color: white; 
                                       border-radius: 12px; 
                                       font-weight: 700; 
                                       text-shadow: 1px 1px 2px rgba(0,0,0,0.4);
                                       box-shadow: 0 4px 15px rgba(255,111,0,0.3);
                                       transition: all 0.3s ease;
                                       position: relative;
                                       z-index: 20;
                                       animation: buttonPulse 3s ease-in-out infinite;"
                                onmouseover="this.style.transform='translateY(-3px) scale(1.05)'; this.style.boxShadow='0 8px 25px rgba(255,111,0,0.5)';"
                                onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 4px 15px rgba(255,111,0,0.3)';">
                            <i class="fas fa-rocket me-2"></i>Confirmer l'Activation
                        </button>
                    </div>
                    <div id="loadingButtons" class="w-100 d-flex justify-content-center" style="display: none !important;">
                        <button type="button" class="btn btn-lg px-5 py-3" disabled
                                style="background: linear-gradient(135deg, #1a237e 0%, #0d47a1 100%); 
                                       border: 2px solid rgba(255,111,0,0.3); 
                                       color: #ff6f00; 
                                       border-radius: 12px; 
                                       font-weight: 600; 
                                       text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
                                       box-shadow: 0 4px 15px rgba(26,35,126,0.3);
                                       position: relative;
                                       z-index: 20;">
                            <i class="fas fa-spinner fa-spin me-2"></i>Activation en cours...
                        </button>
                    </div>
                    <div id="successButtons" class="w-100 d-flex justify-content-center" style="display: none !important;">
                        <button type="button" class="btn btn-lg px-5 py-3" data-bs-dismiss="modal" id="closeSuccessBtn"
                                style="background: linear-gradient(135deg, #4caf50 0%, #66bb6a 50%, #4caf50 100%); 
                                       border: 2px solid rgba(255,255,255,0.3); 
                                       color: white; 
                                       border-radius: 12px; 
                                       font-weight: 700; 
                                       text-shadow: 1px 1px 2px rgba(0,0,0,0.4);
                                       box-shadow: 0 4px 15px rgba(76,175,80,0.3);
                                       transition: all 0.3s ease;
                                       position: relative;
                                       z-index: 20;"
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(76,175,80,0.4)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(76,175,80,0.3)';">
                            <i class="fas fa-check-circle me-2"></i>Terminé
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Formation info mapping
    const formationInfo = {
        'design-graphique': {
            title: 'Design Graphique',
            duration: '4 mois',
            price: 75000,
            modules: ['Photoshop', 'Illustrator', 'InDesign', 'Business Strategy'],
            description: 'Formation complète en design graphique et stratégie commerciale'
        },
        'community-management': {
            title: 'Community Manager',
            duration: '3 mois',
            price: 100000,
            modules: ['Gestion des réseaux sociaux', 'Stratégie social media', 'Création de contenu'],
            description: 'Gestion de communautés et stratégie des réseaux sociaux'
        },
        'gestion-informatique': {
            title: 'Gestion Informatique',
            duration: '2 mois',
            price: 150000,
            modules: ['Bureautique', 'Environnement professionnel'],
            description: 'Formation en bureautique et environnement professionnel'
        },
        'intelligence-artificielle': {
            title: 'Intelligence Artificielle',
            duration: '1 mois',
            price: 50000,
            modules: ['Outils IA pertinents', 'Process et Pratiques'],
            description: 'Formation aux outils IA et aux meilleures pratiques'
        }
    };

    // Update formations summary when checkboxes change
    $('.formation-checkbox').change(function() {
        updateFormationsSummary();
    });

    function updateFormationsSummary() {
        const selectedFormations = [];
        const summaryDiv = $('#formations-summary');
        const listDiv = $('#selected-formations-list');
        const countSpan = $('#selected-count');
        const durationSpan = $('#total-duration');
        const costSpan = $('#total-cost');
        
        // Collect selected formations
        $('.formation-checkbox:checked').each(function() {
            const formationValue = $(this).val();
            if (formationInfo[formationValue]) {
                selectedFormations.push({
                    value: formationValue,
                    info: formationInfo[formationValue]
                });
            }
        });
        
        // Update count
        countSpan.text(selectedFormations.length);
        
        if (selectedFormations.length > 0) {
            // Show summary section
            summaryDiv.show();
            
            // Build formations list HTML
            let listHtml = '';
            let totalDuration = 0;
            let totalCost = 0;
            
            selectedFormations.forEach(function(formation) {
                const duration = parseInt(formation.info.duration);
                const price = formation.info.price;
                totalDuration += duration;
                totalCost += price;
                
                const iconClass = getFormationIcon(formation.value);
                const badgeClass = getFormationBadgeClass(formation.value);
                
                listHtml += `
                    <div class="col-md-6 mb-2">
                        <div class="d-flex align-items-center">
                            <i class="${iconClass} me-2"></i>
                            <div>
                                <strong class="text-white">${formation.info.title}</strong>
                                <span class="badge ${badgeClass} ms-2">${formation.info.duration}</span>
                                <br>
                                <small class="text-muted">${formation.info.modules.join(' • ')}</small>
                                <br>
                                <small class="text-warning fw-bold">${formatPrice(price)} FCFA</small>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            listDiv.html(listHtml);
            durationSpan.text(totalDuration + ' mois');
            
            // Display total cost in FCFA
            costSpan.text(formatPrice(totalCost) + ' FCFA');
            
        } else {
            // Hide summary section
            summaryDiv.hide();
        }
    }
    
    function getFormationIcon(formationValue) {
        const icons = {
            'design-graphique': 'fas fa-palette text-primary',
            'community-management': 'fas fa-users text-success',
            'gestion-informatique': 'fas fa-laptop text-info',
            'intelligence-artificielle': 'fas fa-robot text-warning'
        };
        return icons[formationValue] || 'fas fa-graduation-cap';
    }
    
    function getFormationBadgeClass(formationValue) {
        const badges = {
            'design-graphique': 'bg-primary',
            'community-management': 'bg-success',
            'gestion-informatique': 'bg-info',
            'intelligence-artificielle': 'bg-warning text-dark'
        };
        return badges[formationValue] || 'bg-secondary';
    }
    
    // Format price with thousands separator
    function formatPrice(price) {
        return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    }
    
    // ===== FONCTION DE TEST IMMÉDIAT =====
    function testButton() {
        alert('🎯 BOUTON FONCTIONNE ! Le problème est dans le JavaScript avancé.');
        console.log('🎯 TEST BOUTON: SUCCÈS');
        
        // Tester l'affichage de la modale directement
        const loadingModalElement = document.getElementById('loadingModal');
        if (loadingModalElement) {
            loadingModalElement.style.display = 'block';
            loadingModalElement.classList.add('show');
            loadingModalElement.style.backgroundColor = 'rgba(0,0,0,0.8)';
            console.log('✅ Modale affichée en mode test');
            
            // Fermer après 3 secondes
            setTimeout(() => {
                loadingModalElement.style.display = 'none';
                loadingModalElement.classList.remove('show');
                console.log('✅ Modale fermée après test');
            }, 3000);
        }
    }
    
    // ===== SOLUTION SIMPLE ET ROBUSTE =====
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 INITIALISATION DU SYSTÈME DE CHARGEMENT...');
        
        const form = document.getElementById('studentForm');
        const submitBtn = document.getElementById('submitStudentBtn');
        const loadingModalElement = document.getElementById('loadingModal');
        
        console.log('🔍 Diagnostic des éléments:', {
            form: form ? 'TROUVÉ' : 'MANQUANT',
            submitBtn: submitBtn ? 'TROUVÉ' : 'MANQUANT',
            loadingModal: loadingModalElement ? 'TROUVÉ' : 'MANQUANT',
            bootstrap: typeof bootstrap !== 'undefined' ? 'CHARGÉ' : 'MANQUANT'
        });
        
        if (form && submitBtn) {
            console.log('✅ Configuration du bouton de soumission');
            
            // Event listener sur le bouton
            submitBtn.addEventListener('click', function(e) {
                console.log('🎯 BOUTON CLIQUÉ - Démarrage du processus');
                e.preventDefault();
                
                // Afficher la modale (avec fallback)
                showLoadingModal();
                
                // Démarrer les animations
                startLoadingAnimation();
                
                // Soumettre le formulaire
                submitFormWithAjax();
            });
            
            console.log('✅ Event listener ajouté au bouton');
        } else {
            console.error('❌ ERREUR CRITIQUE: Éléments manquants');
            
            // Fallback: soumission normale du formulaire
            if (submitBtn) {
                submitBtn.addEventListener('click', function(e) {
                    console.log('🔄 FALLBACK: Soumission normale du formulaire');
                    // Laisser la soumission normale se faire
                    return true;
                });
            }
        }
    });
    
    // Fonction pour afficher la modale avec fallback
    function showLoadingModal() {
        const loadingModalElement = document.getElementById('loadingModal');
        
        if (typeof bootstrap !== 'undefined' && loadingModalElement) {
            console.log('✨ Affichage de la modale Bootstrap');
            try {
                const loadingModal = new bootstrap.Modal(loadingModalElement, {
                    backdrop: 'static',
                    keyboard: false
                });
                loadingModal.show();
            } catch (error) {
                console.error('❌ Erreur Bootstrap Modal:', error);
                showSimpleLoader();
            }
        } else {
            console.log('🔄 Fallback: Affichage du loader simple');
            showSimpleLoader();
        }
    }
    
    // Loader simple en fallback
    function showSimpleLoader() {
        const loadingModalElement = document.getElementById('loadingModal');
        if (loadingModalElement) {
            loadingModalElement.style.display = 'block';
            loadingModalElement.classList.add('show');
            loadingModalElement.style.backgroundColor = 'rgba(0,0,0,0.8)';
        }
    }
    
    // Fonction pour soumettre le formulaire via AJAX
    function submitFormWithAjax() {
        const form = document.getElementById('studentForm');
        
        if (!form) {
            console.error('❌ Formulaire introuvable pour AJAX');
            return;
        }
        
        // Préparer les données du formulaire
        const formData = new FormData(form);
        
        console.log('📡 Envoi AJAX en cours...');
        
        // Envoi AJAX avec animations
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            console.log('📨 Réponse reçue:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('✅ Données reçues:', data);
            handleAjaxSuccess(data);
        })
        .catch(error => {
            console.error('❌ Erreur AJAX:', error);
            handleAjaxError(error);
        });
    }
    
    // Démarrage des animations de chargement
    function startLoadingAnimation() {
        console.log('🎬 DÉMARRAGE DES ANIMATIONS DE CHARGEMENT');
        
        const submitBtn = document.getElementById('submitStudentBtn');
        
        // Désactiver le bouton
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement...';
        }
        
        // Démarrer la séquence d'animations
        setTimeout(() => updateStep(1, 'Enregistrement de l\'étudiant...', 'Validation des données'), 500);
        setTimeout(() => updateStep(2, 'Préparation de l\'email...', 'Génération du contenu'), 2000);
        setTimeout(() => updateStep(3, 'Envoi de l\'email...', 'Transmission en cours'), 3500);
    }
    
    // Mise à jour des étapes avec animations fluides
    function updateStep(stepNumber, title, subtitle) {
        const loaderTitle = document.getElementById('loaderTitle');
        const loaderSubtitle = document.getElementById('loaderSubtitle');
        const progressFill = document.getElementById('progressFill');
        const progressPercentage = document.getElementById('progressPercentage');
        
        // Mettre à jour le texte avec animation
        loaderTitle.style.opacity = '0';
        loaderSubtitle.style.opacity = '0';
        
        setTimeout(() => {
            loaderTitle.textContent = title;
            loaderSubtitle.textContent = subtitle;
            loaderTitle.style.opacity = '1';
            loaderSubtitle.style.opacity = '1';
        }, 300);
        
        // Mettre à jour la progression
        const progress = stepNumber * 25;
        progressFill.style.width = progress + '%';
        progressPercentage.textContent = progress + '%';
        
        // Activer l'étape courante
        document.querySelectorAll('.step').forEach((step, index) => {
            step.classList.remove('active', 'completed');
            if (index + 1 < stepNumber) {
                step.classList.add('completed');
            } else if (index + 1 === stepNumber) {
                step.classList.add('active');
            }
        });
        
        // Effet sonore virtuel (vibration visuelle)
        if (stepNumber <= 3) {
            const stepElement = document.getElementById('step' + stepNumber);
            stepElement.style.transform = 'scale(1.2)';
            setTimeout(() => {
                stepElement.style.transform = 'scale(1.1)';
            }, 200);
        }
    }
    
    // Gestion du succès AJAX
    function handleAjaxSuccess(data) {
        console.log('🎉 SUCCÈS - Finalisation de la progression');
        
        // Finaliser la progression
        updateStep(4, 'Étudiant créé avec succès !', 'Email envoyé');
        
        setTimeout(() => {
            const progressFill = document.getElementById('progressFill');
            const progressPercentage = document.getElementById('progressPercentage');
            
            if (progressFill && progressPercentage) {
                progressFill.style.width = '100%';
                progressPercentage.textContent = '100%';
            }
            
            // Animation de succès
            showSuccessAnimation();
            
            // Fermer la modale et rediriger après animation
            setTimeout(() => {
                const loadingModal = bootstrap.Modal.getInstance(document.getElementById('loadingModal'));
                if (loadingModal) {
                    loadingModal.hide();
                }
                
                // Redirection
                setTimeout(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.href = '{{ route("admin.dashboard") }}';
                    }
                }, 500);
            }, 2000);
        }, 1000);
    }
    
    // Gestion des erreurs AJAX
    function handleAjaxError(error) {
        console.error('❌ ERREUR AJAX:', error);
        
        const loaderTitle = document.getElementById('loaderTitle');
        const loaderSubtitle = document.getElementById('loaderSubtitle');
        const submitBtn = document.getElementById('submitStudentBtn');
        
        // Afficher l'erreur
        if (loaderTitle && loaderSubtitle) {
            loaderTitle.textContent = 'Erreur lors de la création';
            loaderSubtitle.textContent = 'Veuillez réessayer';
            loaderTitle.style.color = '#f44336';
        }
        
        // Fermer la modale et réactiver le bouton après 3 secondes
        setTimeout(() => {
            const loadingModal = bootstrap.Modal.getInstance(document.getElementById('loadingModal'));
            if (loadingModal) {
                loadingModal.hide();
            }
            
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-user-plus me-2"></i>Ajouter l\'Étudiant';
            }
        }, 3000);
    }
    
    // Animation de succès révolutionnaire
    function showSuccessAnimation() {
        const loaderTitle = document.getElementById('loaderTitle');
        const loaderSubtitle = document.getElementById('loaderSubtitle');
        const mainLoader = document.querySelector('.main-loader');
        
        // Transformer le loader en succès
        mainLoader.innerHTML = `
            <div class="success-explosion">
                <i class="fas fa-check-circle" style="font-size: 4rem; color: #4caf50; animation: successBounce 0.8s ease-out;"></i>
            </div>
        `;
        
        // Effet d'explosion de succès
        const explosion = document.querySelector('.success-explosion');
        explosion.style.animation = 'successExplosion 1s ease-out';
        
        // Confettis virtuels
        createVirtualConfetti();
    }
    
    // Création de confettis virtuels
    function createVirtualConfetti() {
        const container = document.querySelector('.loader-container');
        
        for (let i = 0; i < 20; i++) {
            const confetti = document.createElement('div');
            confetti.style.cssText = `
                position: absolute;
                width: 6px;
                height: 6px;
                background: ${['#ff6f00', '#4caf50', '#2196f3', '#ffeb3b'][Math.floor(Math.random() * 4)]};
                border-radius: 50%;
                pointer-events: none;
                animation: confettiFall 2s ease-out forwards;
                left: ${Math.random() * 100}%;
                top: 50%;
                z-index: 1000;
            `;
            
            container.appendChild(confetti);
            
            // Supprimer après animation
            setTimeout(() => {
                if (confetti.parentNode) {
                    confetti.parentNode.removeChild(confetti);
                }
            }, 2000);
        }
    }
    
    // Ajout des animations CSS dynamiques
    const dynamicStyle = document.createElement('style');
    dynamicStyle.textContent = `
        @keyframes successBounce {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        @keyframes successExplosion {
            0% { transform: scale(1); }
            50% { transform: scale(1.3); }
            100% { transform: scale(1); }
        }
        
        @keyframes confettiFall {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(200px) rotate(360deg);
                opacity: 0;
            }
        }
        
        .loader-text h3, .loader-text p {
            transition: opacity 0.3s ease;
        }
    `;
    document.head.appendChild(dynamicStyle);

    // Photo preview
    $('#photo').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Remove existing preview
                $('.photo-preview').remove();
                
                // Add new preview
                const preview = $('<div class="photo-preview mt-2"><img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 150px; max-height: 150px;"></div>');
                $('#photo').after(preview);
            };
            reader.readAsDataURL(file);
        }
    });

    // Gestion du bouton d'activation avec modale personnalisée
    $('#activateStudentBtn').click(function(e) {
        e.preventDefault();
        
        const email = $('#email').val();
        const selectedFormations = $('.formation-checkbox:checked');
        
        // Validation des formations
        if (selectedFormations.length === 0) {
            alert('Veuillez sélectionner au moins une formation.');
            $('.formations-grid').get(0).scrollIntoView({ behavior: 'smooth' });
            return false;
        }
        
        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Veuillez saisir une adresse email valide.');
            $('#email').focus();
            return false;
        }
        
        // Soumission du bon formulaire (celui avec action students.store)
        $('form[action*="students/store"]')[0].submit();
    });
    
    // Pas de modale - soumission directe du formulaire
});

// Gestion des animations CSS personnalisées pour le design Bleu nuit et Orange
const style = document.createElement('style');
style.textContent = `
    /* Animations principales */
    @keyframes overlayPulse {
        0% { opacity: 0.1; }
        50% { opacity: 0.3; }
        100% { opacity: 0.1; }
    }
    
    @keyframes iconGlow {
        0% { text-shadow: 0 0 10px rgba(255,111,0,0.3); }
        50% { text-shadow: 0 0 30px rgba(255,111,0,0.8), 0 0 40px rgba(255,111,0,0.5); }
        100% { text-shadow: 0 0 10px rgba(255,111,0,0.3); }
    }
    
    @keyframes textShimmer {
        0% { background-position: -200% center; }
        100% { background-position: 200% center; }
    }
    
    @keyframes glowPulse {
        0% { opacity: 0.3; transform: translate(-50%, -50%) scale(1); }
        50% { opacity: 0.8; transform: translate(-50%, -50%) scale(1.2); }
        100% { opacity: 0.3; transform: translate(-50%, -50%) scale(1); }
    }
    
    @keyframes iconBounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }
    
    @keyframes cardFloat {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-5px); }
    }
    
    @keyframes buttonPulse {
        0% { box-shadow: 0 4px 15px rgba(255,111,0,0.3); }
        50% { box-shadow: 0 4px 25px rgba(255,111,0,0.6), 0 0 30px rgba(255,111,0,0.3); }
        100% { box-shadow: 0 4px 15px rgba(255,111,0,0.3); }
    }
    
    /* Animations de loading */
    @keyframes spinnerGlow {
        0% { opacity: 0.4; transform: translate(-50%, -50%) scale(1); }
        50% { opacity: 0.8; transform: translate(-50%, -50%) scale(1.1); }
        100% { opacity: 0.4; transform: translate(-50%, -50%) scale(1); }
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Animations de succès */
    @keyframes successGlow {
        0% { opacity: 0.4; transform: translate(-50%, -50%) scale(1); }
        50% { opacity: 0.8; transform: translate(-50%, -50%) scale(1.2); }
        100% { opacity: 0.4; transform: translate(-50%, -50%) scale(1); }
    }
    
    @keyframes successBounce {
        0% { transform: scale(0.3); opacity: 0; }
        50% { transform: scale(1.1); opacity: 1; }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); opacity: 1; }
    }
    
    /* Animation d'entrée de la modale */
    .activation-modal-content {
        animation: modalSlideIn 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    
    @keyframes modalSlideIn {
        0% { 
            transform: translateY(-100px) scale(0.8); 
            opacity: 0; 
        }
        100% { 
            transform: translateY(0) scale(1); 
            opacity: 1; 
        }
    }
    
    /* Effets hover pour les éléments interactifs */
    .student-preview {
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        cursor: pointer;
    }
    
    .student-preview:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 15px 35px rgba(255,111,0,0.2), 0 5px 15px rgba(0,0,0,0.1);
        border-color: rgba(255,111,0,0.5) !important;
    }
    
    /* CSS nettoyé - plus de styles de modale */
    
    /* ===== MODALE DE CHARGEMENT RÉVOLUTIONNAIRE ===== */
    .loading-modal-content {
        background: linear-gradient(135deg, 
            rgba(26, 35, 126, 0.95) 0%, 
            rgba(13, 71, 161, 0.95) 50%, 
            rgba(255, 111, 0, 0.95) 100%);
        border: none;
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(10px);
        animation: modalPulse 3s ease-in-out infinite;
    }
    
    .loading-modal-content .modal-body {
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .loading-modal-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255,111,0,0.1) 0%, rgba(26,35,126,0.1) 100%);
        animation: overlayPulse 3s ease-in-out infinite;
        pointer-events: none;
    }
    
    /* Loader principal avec anneaux */
    .main-loader {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto 2rem;
    }
    
    .loader-ring {
        position: absolute;
        border-radius: 50%;
        border: 3px solid transparent;
        animation: rotate 3s linear infinite;
    }
    
    .ring-1 {
        width: 120px;
        height: 120px;
        border-top: 3px solid #ff6f00;
        border-right: 3px solid #ff6f00;
        animation-duration: 2s;
    }
    
    .ring-2 {
        width: 90px;
        height: 90px;
        top: 15px;
        left: 15px;
        border-bottom: 3px solid #ffffff;
        border-left: 3px solid #ffffff;
        animation-duration: 1.5s;
        animation-direction: reverse;
    }
    
    .ring-3 {
        width: 60px;
        height: 60px;
        top: 30px;
        left: 30px;
        border-top: 3px solid #ff9800;
        border-right: 3px solid #ff9800;
        animation-duration: 1s;
    }
    
    .loader-core {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #ff6f00, #ff9800);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 20px rgba(255, 111, 0, 0.5);
        animation: pulse 2s ease-in-out infinite;
    }
    
    .loader-icon {
        font-size: 1.2rem;
        color: white;
        animation: iconFloat 2s ease-in-out infinite;
    }
    
    /* Texte dynamique */
    .loader-text {
        margin-bottom: 2rem;
    }
    
    .loader-text h3 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        background: linear-gradient(45deg, #ffffff, #ff6f00, #ffffff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: textShimmer 3s ease-in-out infinite;
    }
    
    .loader-text p {
        font-size: 1.1rem;
        opacity: 0.9;
        color: #ffcc80;
        animation: fadeInOut 2s ease-in-out infinite;
    }
    
    /* Barre de progression révolutionnaire */
    .progress-container {
        margin-bottom: 2rem;
        position: relative;
    }
    
    .progress-bar-revolutionary {
        width: 100%;
        height: 8px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        overflow: hidden;
        position: relative;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #ff6f00, #ff9800, #ffab40);
        border-radius: 10px;
        width: 0%;
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .progress-fill::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        animation: progressShine 2s ease-in-out infinite;
    }
    
    .progress-glow {
        position: absolute;
        top: -2px;
        left: 0;
        width: 100%;
        height: 12px;
        background: linear-gradient(90deg, transparent, #ff6f00, transparent);
        border-radius: 10px;
        opacity: 0.6;
        filter: blur(4px);
        animation: glowPulse 2s ease-in-out infinite;
    }
    
    .progress-percentage {
        position: absolute;
        top: -30px;
        right: 0;
        font-weight: 600;
        color: #ff9800;
        font-size: 0.9rem;
    }
    
    /* Étapes d'envoi */
    .email-steps {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 2rem;
    }
    
    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        opacity: 0.4;
        transition: all 0.5s ease;
    }
    
    .step.active {
        opacity: 1;
        transform: scale(1.1);
    }
    
    .step.completed {
        opacity: 1;
        color: #4caf50;
    }
    
    .step-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.5rem;
        transition: all 0.5s ease;
        border: 2px solid rgba(255, 255, 255, 0.2);
    }
    
    .step.active .step-icon {
        background: linear-gradient(135deg, #ff6f00, #ff9800);
        box-shadow: 0 0 20px rgba(255, 111, 0, 0.5);
        border-color: #ff6f00;
        animation: stepPulse 1s ease-in-out infinite;
    }
    
    .step.completed .step-icon {
        background: linear-gradient(135deg, #4caf50, #66bb6a);
        box-shadow: 0 0 20px rgba(76, 175, 80, 0.5);
        border-color: #4caf50;
    }
    
    .step span {
        font-size: 0.8rem;
        font-weight: 500;
        text-align: center;
    }
    
    /* Animations */
    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    @keyframes pulse {
        0%, 100% { transform: translate(-50%, -50%) scale(1); }
        50% { transform: translate(-50%, -50%) scale(1.1); }
    }
    
    @keyframes iconFloat {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-3px); }
    }
    
    @keyframes textShimmer {
        0%, 100% { background-position: -200% center; }
        50% { background-position: 200% center; }
    }
    
    @keyframes fadeInOut {
        0%, 100% { opacity: 0.9; }
        50% { opacity: 0.6; }
    }
    
    @keyframes progressShine {
        0% { left: -100%; }
        100% { left: 100%; }
    }
    
    @keyframes glowPulse {
        0%, 100% { opacity: 0.6; }
        50% { opacity: 0.9; }
    }
    
    @keyframes stepPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    @keyframes overlayPulse {
        0%, 100% { opacity: 0.9; }
        50% { opacity: 0.95; }
    }
    
    @keyframes modalPulse {
        0%, 100% { 
            transform: scale(1);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
        }
        50% { 
            transform: scale(1.02);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5);
        }
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .loader-container {
            padding: 1rem;
            max-width: 90%;
        }
        
        .main-loader {
            width: 80px;
            height: 80px;
        }
        
        .ring-1 { width: 80px; height: 80px; }
        .ring-2 { width: 60px; height: 60px; top: 10px; left: 10px; }
        .ring-3 { width: 40px; height: 40px; top: 20px; left: 20px; }
        
        .loader-core {
            width: 30px;
            height: 30px;
        }
        
        .email-steps {
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .step {
            flex: 1;
            min-width: 80px;
        }
    }
`;
document.head.appendChild(style);

// ===== SOLUTION FONCTIONNELLE GARANTIE =====
console.log('🚀 CHARGEMENT DU SCRIPT PRINCIPAL');

// Attendre que tout soit chargé
window.addEventListener('load', function() {
    console.log('✅ PAGE ENTIÈREMENT CHARGÉE');
    initializeStudentForm();
});

// Fonction d'initialisation principale
function initializeStudentForm() {
    console.log('🎯 INITIALISATION DU FORMULAIRE ÉTUDIANT');
    
    const form = document.getElementById('studentForm');
    const submitBtn = document.getElementById('submitStudentBtn');
    
    console.log('🔍 DIAGNOSTIC:', {
        form: form ? 'TROUVÉ' : 'MANQUANT',
        submitBtn: submitBtn ? 'TROUVÉ' : 'MANQUANT'
    });
    
    if (submitBtn) {
        // Supprimer l'ancien onclick et ajouter le nouveau
        submitBtn.removeAttribute('onclick');
        
        submitBtn.addEventListener('click', function(e) {
            console.log('🎯 BOUTON CLIQUÉ - DÉMARRAGE');
            e.preventDefault();
            
            // Afficher immédiatement la modale
            showLoadingModalNow();
            
            // Simuler le processus pendant 30 secondes puis soumettre
            setTimeout(() => {
                if (form) {
                    console.log('📤 SOUMISSION DU FORMULAIRE APRÈS 30 SECONDES');
                    form.submit();
                } else {
                    console.error('❌ FORMULAIRE INTROUVABLE');
                }
            }, 30000);
        });
        
        console.log('✅ EVENT LISTENER AJOUTÉ AU BOUTON');
    } else {
        console.error('❌ BOUTON INTROUVABLE');
    }
}

// Fonction pour afficher la modale immédiatement
function showLoadingModalNow() {
    console.log('🎬 AFFICHAGE DE LA MODALE');
    
    const modal = document.getElementById('loadingModal');
    if (modal) {
        // Affichage direct sans Bootstrap
        modal.style.display = 'block';
        modal.style.position = 'fixed';
        modal.style.top = '0';
        modal.style.left = '0';
        modal.style.width = '100%';
        modal.style.height = '100%';
        modal.style.backgroundColor = 'rgba(0,0,0,0.8)';
        modal.style.zIndex = '9999';
        modal.classList.add('show');
        
        console.log('✅ MODALE AFFICHÉE');
        
        // Démarrer les animations
        startProgressAnimation();
    } else {
        console.error('❌ MODALE INTROUVABLE');
    }
}

// Animation de progression ultra-fluide sur 30 secondes
function startProgressAnimation() {
    console.log('🎬 DÉMARRAGE DE L\'ANIMATION ULTRA-FLUIDE (30 SECONDES)');
    
    const detailedSteps = [
        { time: 0, title: 'Initialisation du processus...', subtitle: 'Préparation des données', progress: 0 },
        { time: 2, title: 'Validation des champs...', subtitle: 'Vérification des informations', progress: 5 },
        { time: 4, title: 'Contrôle de sécurité...', subtitle: 'Authentification admin', progress: 10 },
        { time: 6, title: 'Génération de l\'identifiant...', subtitle: 'Création du compte étudiant', progress: 15 },
        { time: 8, title: 'Hashage du mot de passe...', subtitle: 'Sécurisation des données', progress: 20 },
        { time: 10, title: 'Insertion en base de données...', subtitle: 'Enregistrement étudiant', progress: 30 },
        { time: 12, title: 'Vérification de l\'insertion...', subtitle: 'Contrôle d\'intégrité', progress: 40 },
        { time: 14, title: 'Attribution des formations...', subtitle: 'Liaison étudiant-formations', progress: 50 },
        { time: 16, title: 'Génération du template email...', subtitle: 'Préparation du message', progress: 60 },
        { time: 18, title: 'Personnalisation du contenu...', subtitle: 'Ajout des informations', progress: 65 },
        { time: 20, title: 'Configuration SMTP...', subtitle: 'Connexion au serveur mail', progress: 70 },
        { time: 22, title: 'Envoi de l\'email...', subtitle: 'Transmission en cours', progress: 80 },
        { time: 24, title: 'Vérification de l\'envoi...', subtitle: 'Confirmation de réception', progress: 90 },
        { time: 26, title: 'Mise à jour des logs...', subtitle: 'Enregistrement des actions', progress: 95 },
        { time: 28, title: 'Finalisation...', subtitle: 'Nettoyage et optimisation', progress: 98 },
        { time: 30, title: 'Processus terminé !', subtitle: 'Étudiant créé avec succès', progress: 100 }
    ];
    
    let currentStepIndex = 0;
    let startTime = Date.now();
    
    // Animation fluide continue
    const smoothInterval = setInterval(() => {
        const elapsed = (Date.now() - startTime) / 1000; // Temps écoulé en secondes
        
        // Trouver l'étape actuelle basée sur le temps
        while (currentStepIndex < detailedSteps.length - 1 && 
               elapsed >= detailedSteps[currentStepIndex + 1].time) {
            currentStepIndex++;
        }
        
        if (currentStepIndex < detailedSteps.length) {
            const currentStep = detailedSteps[currentStepIndex];
            const nextStep = detailedSteps[currentStepIndex + 1];
            
            // Interpolation fluide de la progression
            let progress = currentStep.progress;
            if (nextStep && elapsed > currentStep.time) {
                const stepDuration = nextStep.time - currentStep.time;
                const stepProgress = (elapsed - currentStep.time) / stepDuration;
                progress = currentStep.progress + (nextStep.progress - currentStep.progress) * stepProgress;
            }
            
            // Mise à jour fluide de l'interface
            updateUISmooth(currentStep.title, currentStep.subtitle, progress, elapsed);
            
            // Activer les étapes visuelles
            updateVisualSteps(progress);
        }
        
        // Arrêter après 30 secondes
        if (elapsed >= 30) {
            clearInterval(smoothInterval);
            console.log('✅ ANIMATION ULTRA-FLUIDE TERMINÉE');
        }
    }, 50); // Mise à jour toutes les 50ms pour une fluidité maximale
}

// Mise à jour fluide de l'interface
function updateUISmooth(title, subtitle, progress, elapsed) {
    // Mettre à jour le titre avec effet de transition
    const titleElement = document.getElementById('loaderTitle');
    const subtitleElement = document.getElementById('loaderSubtitle');
    
    if (titleElement && titleElement.textContent !== title) {
        titleElement.style.opacity = '0.7';
        setTimeout(() => {
            titleElement.textContent = title;
            titleElement.style.opacity = '1';
        }, 100);
    }
    
    if (subtitleElement && subtitleElement.textContent !== subtitle) {
        subtitleElement.style.opacity = '0.7';
        setTimeout(() => {
            subtitleElement.textContent = subtitle;
            subtitleElement.style.opacity = '1';
        }, 150);
    }
    
    // Mise à jour fluide de la progression
    const progressFill = document.getElementById('progressFill');
    const progressPercentage = document.getElementById('progressPercentage');
    
    if (progressFill) {
        progressFill.style.width = Math.round(progress) + '%';
    }
    if (progressPercentage) {
        progressPercentage.textContent = Math.round(progress) + '%';
    }
    
    // Log périodique (toutes les 2 secondes)
    if (Math.floor(elapsed) % 2 === 0 && elapsed > 0) {
        console.log(`📊 ${Math.round(elapsed)}s: ${title} (${Math.round(progress)}%)`);
    }
}

// Mise à jour des étapes visuelles
function updateVisualSteps(progress) {
    const steps = ['step1', 'step2', 'step3', 'step4'];
    
    steps.forEach((stepId, index) => {
        const stepElement = document.getElementById(stepId);
        if (stepElement) {
            const stepProgress = (index + 1) * 25;
            
            if (progress >= stepProgress) {
                stepElement.classList.add('completed');
                stepElement.classList.remove('active');
            } else if (progress >= stepProgress - 25) {
                stepElement.classList.add('active');
                stepElement.classList.remove('completed');
            } else {
                stepElement.classList.remove('active', 'completed');
            }
        }
    });
}

console.log('🎉 SCRIPT CHARGÉ AVEC SUCCÈS');
</script>
@endsection
