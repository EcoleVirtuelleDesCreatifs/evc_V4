@extends('layouts.app')

@section('title', 'Devenir Formateur - École Virtuelle des Créatifs')
@section('description', 'Devenez formateur à l\'École Virtuelle des Créatifs. Partagez votre expertise et formez la nouvelle génération de créatifs africains en Côte d\'Ivoire.')
@section('keywords', 'devenir formateur evc, enseigner evc abidjan, formateur design graphique, formateur community management, enseignement côte d\'ivoire')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
        .formateur-page {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0a1628 0%, #1a2942 100%);
            min-height: 100vh;
            color: white;
        }

        /* Header */
        .page-header {
            padding: 150px 0 80px;
            text-align: center;
            position: relative;
        }

        .page-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -1px;
        }

        .page-subtitle {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.85);
            max-width: 750px;
            margin: 0 auto;
            font-weight: 300;
            line-height: 1.7;
        }

        /* Back Button */
        .back-button {
            position: fixed;
            top: 30px;
            left: 30px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
            border: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 1000;
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(-5px);
            color: white;
        }

        /* Content Section */
        .content-section {
            padding: 40px 0;
        }

        .info-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 152, 0, 0.2);
            transition: all 0.3s;
        }

        .info-card:hover {
            border-color: #ff9800;
            box-shadow: 0 10px 40px rgba(255, 152, 0, 0.2);
        }

        .info-card h3 {
            color: #ff9800;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .info-card h3 i {
            font-size: 2rem;
        }

        .info-card p, .info-card li {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.8;
            font-size: 1.05rem;
        }

        .info-card ul {
            list-style: none;
            padding: 0;
        }

        .info-card ul li {
            padding: 0.8rem 0;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .info-card ul li i {
            color: #ff9800;
            margin-top: 0.3rem;
            flex-shrink: 0;
        }

        /* Formations Grid */
        .formations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .formation-badge {
            background: rgba(255, 152, 0, 0.1);
            border: 1px solid rgba(255, 152, 0, 0.3);
            border-radius: 12px;
            padding: 1.5rem 1rem;
            text-align: center;
            transition: all 0.3s;
        }

        .formation-badge:hover {
            background: rgba(255, 152, 0, 0.15);
            border-color: #ff9800;
            transform: translateY(-3px);
        }

        .formation-badge i {
            font-size: 2.5rem;
            color: #ff9800;
            margin-bottom: 0.8rem;
        }

        .formation-badge h5 {
            color: white;
            font-size: 1rem;
            margin: 0;
        }

        /* Form Section */
        .form-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 3rem;
            border: 1px solid rgba(255, 152, 0, 0.2);
        }

        .form-title {
            font-size: 2rem;
            font-weight: 700;
            color: #ff9800;
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-label .required {
            color: #ff9800;
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: white;
            padding: 0.8rem 1.2rem;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #ff9800;
            box-shadow: 0 0 0 0.2rem rgba(255, 152, 0, 0.25);
            color: white;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-select option {
            background: #1a2942;
            color: white;
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.8rem;
            background: rgba(255, 152, 0, 0.1);
            border: 1px solid rgba(255, 152, 0, 0.2);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .checkbox-item:hover {
            background: rgba(255, 152, 0, 0.15);
            border-color: #ff9800;
        }

        .checkbox-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #ff9800;
        }

        .checkbox-item label {
            color: rgba(255, 255, 255, 0.9);
            cursor: pointer;
            margin: 0;
        }

        .file-upload-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-upload-input {
            position: absolute;
            left: -9999px;
        }

        .file-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            padding: 1.5rem;
            background: rgba(255, 152, 0, 0.1);
            border: 2px dashed rgba(255, 152, 0, 0.5);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            color: rgba(255, 255, 255, 0.9);
        }

        .file-upload-label:hover {
            background: rgba(255, 152, 0, 0.2);
            border-color: #ff9800;
        }

        .file-upload-label i {
            font-size: 2rem;
            color: #ff9800;
        }

        .file-name {
            margin-top: 0.5rem;
            color: #ff9800;
            font-size: 0.9rem;
        }

        .submit-button {
            width: 100%;
            padding: 1.2rem;
            font-size: 1.2rem;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%);
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 10px 30px rgba(255, 152, 0, 0.3);
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(255, 152, 0, 0.4);
        }

        .submit-button:active {
            transform: translateY(0);
        }

        /* Alert */
        .alert-custom {
            background: rgba(76, 175, 80, 0.1);
            border: 1px solid rgba(76, 175, 80, 0.5);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            color: #4caf50;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }

            .page-subtitle {
                font-size: 1rem;
            }

            .info-card, .form-container {
                padding: 1.5rem;
            }

            .back-button {
                top: 15px;
                left: 15px;
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }

            .formations-grid {
                grid-template-columns: 1fr;
            }

            .checkbox-group {
                grid-template-columns: 1fr;
            }
        }
</style>
@endpush

@section('content')
<div class="formateur-page">
    <!-- Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="page-title">
                <i class="fas fa-chalkboard-teacher me-3"></i>
                Devenir Formateur
            </h1>
            <p class="page-subtitle">
                Partagez votre expertise et formez la nouvelle génération de créatifs africains
            </p>
        </div>
    </section>

    <!-- Content -->
    <section class="content-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row">
                <!-- Left Column: Information -->
                <div class="col-lg-5 mb-4">
                    <!-- Pourquoi enseigner -->
                    <div class="info-card">
                        <h3>
                            <i class="fas fa-heart"></i>
                            Pourquoi enseigner chez EVC ?
                        </h3>
                        <ul>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Transmettez votre savoir et votre passion</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Rémunération attractive et compétitive</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Flexibilité des horaires d'enseignement</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Réseau professionnel étendu</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Impact social significatif</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Formation continue et développement</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Formations -->
                    <div class="info-card">
                        <h3>
                            <i class="fas fa-graduation-cap"></i>
                            Nos formations
                        </h3>
                        <div class="formations-grid">
                            <div class="formation-badge">
                                <i class="fas fa-palette"></i>
                                <h5>Design Graphique</h5>
                            </div>
                            <div class="formation-badge">
                                <i class="fas fa-bullhorn"></i>
                                <h5>Community Management</h5>
                            </div>
                            <div class="formation-badge">
                                <i class="fas fa-laptop-code"></i>
                                <h5>Gestion Informatique</h5>
                            </div>
                            <div class="formation-badge">
                                <i class="fas fa-brain"></i>
                                <h5>Intelligence Artificielle</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Profil recherché -->
                    <div class="info-card">
                        <h3>
                            <i class="fas fa-user-check"></i>
                            Profil recherché
                        </h3>
                        <ul>
                            <li>
                                <i class="fas fa-award"></i>
                                <span>Expertise avérée dans votre domaine (3+ ans)</span>
                            </li>
                            <li>
                                <i class="fas fa-comments"></i>
                                <span>Excellentes capacités de communication</span>
                            </li>
                            <li>
                                <i class="fas fa-lightbulb"></i>
                                <span>Pédagogie et passion pour l'enseignement</span>
                            </li>
                            <li>
                                <i class="fas fa-clock"></i>
                                <span>Disponibilité et ponctualité</span>
                            </li>
                            <li>
                                <i class="fas fa-laptop"></i>
                                <span>Maîtrise des outils digitaux</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div class="info-card">
                        <h3>
                            <i class="fas fa-envelope"></i>
                            Contact
                        </h3>
                        <p>
                            <strong>Email :</strong> formateurs@evc.ci<br>
                            <strong>Téléphone :</strong> +225 XX XX XX XX XX<br>
                            <strong>Adresse :</strong> Abidjan, Côte d'Ivoire
                        </p>
                    </div>
                </div>

                <!-- Right Column: Form -->
                <div class="col-lg-7">
                    <div class="form-container">
                        <h2 class="form-title">Candidature Formateur</h2>

                        @if(session('success'))
                            <div class="alert-custom">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('rejoignez-nous.formateur.submit') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Prénom <span class="required">*</span>
                                        </label>
                                        <input type="text" name="prenom" class="form-control" placeholder="Votre prénom" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Nom <span class="required">*</span>
                                        </label>
                                        <input type="text" name="nom" class="form-control" placeholder="Votre nom" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Email <span class="required">*</span>
                                        </label>
                                        <input type="email" name="email" class="form-control" placeholder="votre.email@exemple.com" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Téléphone <span class="required">*</span>
                                        </label>
                                        <input type="tel" name="telephone" class="form-control" placeholder="+225 XX XX XX XX XX" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Domaine(s) d'expertise <span class="required">*</span>
                                </label>
                                <div class="checkbox-group">
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="domaines[]" value="design_graphique" id="design">
                                        <label for="design">Design Graphique</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="domaines[]" value="community_management" id="cm">
                                        <label for="cm">Community Management</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="domaines[]" value="gestion_informatique" id="gi">
                                        <label for="gi">Gestion Informatique</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="domaines[]" value="intelligence_artificielle" id="ia">
                                        <label for="ia">Intelligence Artificielle</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Années d'expérience <span class="required">*</span>
                                        </label>
                                        <select name="experience" class="form-select" required>
                                            <option value="">Sélectionnez</option>
                                            <option value="3-5">3 à 5 ans</option>
                                            <option value="5-10">5 à 10 ans</option>
                                            <option value="10+">Plus de 10 ans</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Niveau d'études <span class="required">*</span>
                                        </label>
                                        <select name="niveau_etudes" class="form-select" required>
                                            <option value="">Sélectionnez</option>
                                            <option value="licence">Licence / Bachelor</option>
                                            <option value="master">Master</option>
                                            <option value="doctorat">Doctorat / PhD</option>
                                            <option value="autodidacte">Autodidacte (expérience significative)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Disponibilité <span class="required">*</span>
                                </label>
                                <select name="disponibilite" class="form-select" required>
                                    <option value="">Sélectionnez</option>
                                    <option value="temps_plein">Temps plein</option>
                                    <option value="temps_partiel">Temps partiel</option>
                                    <option value="ponctuel">Interventions ponctuelles</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Présentez votre parcours et votre motivation <span class="required">*</span>
                                </label>
                                <textarea name="message" class="form-control" placeholder="Parlez-nous de votre parcours professionnel, vos compétences, votre expérience en enseignement (si applicable) et pourquoi vous souhaitez rejoindre EVC..." required></textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    CV (PDF, max 2MB) <span class="required">*</span>
                                </label>
                                <div class="file-upload-wrapper">
                                    <input type="file" name="cv" id="cv" class="file-upload-input" accept=".pdf" required>
                                    <label for="cv" class="file-upload-label">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Cliquez pour télécharger votre CV</span>
                                    </label>
                                    <div class="file-name" id="cv-name"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Portfolio / Site web (optionnel)
                                </label>
                                <input type="url" name="portfolio" class="form-control" placeholder="https://...">
                            </div>

                            <button type="submit" class="submit-button">
                                <i class="fas fa-paper-plane me-2"></i>
                                Envoyer ma candidature
                            </button>
                        </form>
                    </div>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
    // File upload display
    document.getElementById('cv').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || '';
        document.getElementById('cv-name').textContent = fileName ? `Fichier sélectionné : ${fileName}` : '';
    });
</script>
@endpush
