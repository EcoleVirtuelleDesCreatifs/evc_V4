@extends('layouts.app')

@section('title', 'Devenir Partenaire - École Virtuelle des Créatifs')
@section('description', 'Devenez partenaire de l\'École Virtuelle des Créatifs. Collaborez avec nous pour développer des synergies et créer de la valeur ensemble en Côte d\'Ivoire.')
@section('keywords', 'partenariat evc, partenaire école virtuelle, collaboration formation abidjan, partenariat éducation côte d\'ivoire')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
        .partenaire-page {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0a1628 0%, #1a2942 100%);
            min-height: 100vh;
            color: white;
        }

        /* Header */
        .page-header {
            padding: 100px 0 60px;
            text-align: center;
            position: relative;
        }

        .page-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.8);
            max-width: 700px;
            margin: 0 auto;
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
            border: 1px solid rgba(79, 195, 247, 0.2);
            transition: all 0.3s;
        }

        .info-card:hover {
            border-color: #4fc3f7;
            box-shadow: 0 10px 40px rgba(79, 195, 247, 0.2);
        }

        .info-card h3 {
            color: #4fc3f7;
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
            color: #4fc3f7;
            margin-top: 0.3rem;
            flex-shrink: 0;
        }

        /* Partnership Types */
        .partnership-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .partnership-type {
            background: rgba(79, 195, 247, 0.1);
            border: 1px solid rgba(79, 195, 247, 0.3);
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s;
        }

        .partnership-type:hover {
            background: rgba(79, 195, 247, 0.15);
            border-color: #4fc3f7;
            transform: translateY(-5px);
        }

        .partnership-type i {
            font-size: 3rem;
            color: #4fc3f7;
            margin-bottom: 1rem;
        }

        .partnership-type h4 {
            color: white;
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }

        .partnership-type p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
        }

        /* Form Section */
        .form-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 3rem;
            border: 1px solid rgba(79, 195, 247, 0.2);
        }

        .form-title {
            font-size: 2rem;
            font-weight: 700;
            color: #4fc3f7;
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
            color: #4fc3f7;
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
            border-color: #4fc3f7;
            box-shadow: 0 0 0 0.2rem rgba(79, 195, 247, 0.25);
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

        .submit-button {
            width: 100%;
            padding: 1.2rem;
            font-size: 1.2rem;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 10px 30px rgba(79, 195, 247, 0.3);
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(79, 195, 247, 0.4);
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

            .partnership-grid {
                grid-template-columns: 1fr;
            }
        }
</style>
@endpush

@section('content')
<div class="partenaire-page">
    <!-- Back Button -->
    <a href="{{ route('rejoignez-nous') }}" class="back-button">
        <i class="fas fa-arrow-left"></i>
        <span>Retour</span>
    </a>

    <!-- Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="page-title">
                <i class="fas fa-users me-3"></i>
                Devenir Partenaire
            </h1>
            <p class="page-subtitle">
                Collaborez avec l'École Virtuelle des Créatifs pour développer des synergies et créer de la valeur ensemble
            </p>
        </div>
    </section>

    <!-- Content -->
    <section class="content-section">
        <div class="container">
            <!-- Types de partenariats -->
            <div class="info-card mb-5">
                <h3>
                    <i class="fas fa-handshake"></i>
                    Types de partenariats
                </h3>
                <div class="partnership-grid">
                    <div class="partnership-type">
                        <i class="fas fa-building"></i>
                        <h4>Entreprises</h4>
                        <p>Recrutement de talents, stages, projets collaboratifs</p>
                    </div>
                    <div class="partnership-type">
                        <i class="fas fa-graduation-cap"></i>
                        <h4>Institutions</h4>
                        <p>Partenariats académiques, échanges de compétences</p>
                    </div>
                    <div class="partnership-type">
                        <i class="fas fa-lightbulb"></i>
                        <h4>Startups</h4>
                        <p>Innovation, co-création, mentorat</p>
                    </div>
                    <div class="partnership-type">
                        <i class="fas fa-globe"></i>
                        <h4>ONG / Associations</h4>
                        <p>Projets sociaux, formation inclusive</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Left Column: Information -->
                <div class="col-lg-5 mb-4">
                    <!-- Avantages -->
                    <div class="info-card">
                        <h3>
                            <i class="fas fa-star"></i>
                            Avantages du partenariat
                        </h3>
                        <ul>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Accès à un vivier de talents formés et qualifiés</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Visibilité auprès de notre communauté</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Participation à des projets innovants</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Contribution à la transformation digitale africaine</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Réseau professionnel étendu</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Modalités -->
                    <div class="info-card">
                        <h3>
                            <i class="fas fa-clipboard-list"></i>
                            Modalités de collaboration
                        </h3>
                        <ul>
                            <li>
                                <i class="fas fa-briefcase"></i>
                                <span>Offres de stages et d'emploi</span>
                            </li>
                            <li>
                                <i class="fas fa-project-diagram"></i>
                                <span>Projets collaboratifs avec les étudiants</span>
                            </li>
                            <li>
                                <i class="fas fa-chalkboard-teacher"></i>
                                <span>Interventions et masterclasses</span>
                            </li>
                            <li>
                                <i class="fas fa-donate"></i>
                                <span>Parrainage et sponsoring</span>
                            </li>
                            <li>
                                <i class="fas fa-tools"></i>
                                <span>Mise à disposition d'outils et ressources</span>
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
                            <strong>Email :</strong> partenariats@evc.ci<br>
                            <strong>Téléphone :</strong> +225 XX XX XX XX XX<br>
                            <strong>Adresse :</strong> Abidjan, Côte d'Ivoire
                        </p>
                    </div>
                </div>

                <!-- Right Column: Form -->
                <div class="col-lg-7">
                    <div class="form-container">
                        <h2 class="form-title">Demande de partenariat</h2>

                        @if(session('success'))
                            <div class="alert-custom">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('rejoignez-nous.partenaire.submit') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label class="form-label">
                                    Type de structure <span class="required">*</span>
                                </label>
                                <select name="type_structure" class="form-select" required>
                                    <option value="">Sélectionnez</option>
                                    <option value="entreprise">Entreprise</option>
                                    <option value="institution">Institution académique</option>
                                    <option value="startup">Startup</option>
                                    <option value="ong">ONG / Association</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Nom de la structure <span class="required">*</span>
                                </label>
                                <input type="text" name="nom_structure" class="form-control" placeholder="Nom de votre entreprise/organisation" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Nom du contact <span class="required">*</span>
                                        </label>
                                        <input type="text" name="nom_contact" class="form-control" placeholder="Nom complet" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Fonction <span class="required">*</span>
                                        </label>
                                        <input type="text" name="fonction" class="form-control" placeholder="Votre fonction" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Email <span class="required">*</span>
                                        </label>
                                        <input type="email" name="email" class="form-control" placeholder="contact@exemple.com" required>
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
                                    Site web (optionnel)
                                </label>
                                <input type="url" name="site_web" class="form-control" placeholder="https://...">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Type de partenariat souhaité <span class="required">*</span>
                                </label>
                                <select name="type_partenariat" class="form-select" required>
                                    <option value="">Sélectionnez</option>
                                    <option value="recrutement">Recrutement de talents</option>
                                    <option value="stages">Offres de stages</option>
                                    <option value="projets">Projets collaboratifs</option>
                                    <option value="masterclass">Interventions / Masterclass</option>
                                    <option value="sponsoring">Parrainage / Sponsoring</option>
                                    <option value="ressources">Mise à disposition de ressources</option>
                                    <option value="autre">Autre (précisez dans le message)</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Secteur d'activité <span class="required">*</span>
                                </label>
                                <input type="text" name="secteur" class="form-control" placeholder="Ex: Technologie, Marketing, Design..." required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Décrivez votre projet de partenariat <span class="required">*</span>
                                </label>
                                <textarea name="message" class="form-control" placeholder="Présentez votre structure, vos objectifs et ce que vous attendez de ce partenariat..." required></textarea>
                            </div>

                            <button type="submit" class="submit-button">
                                <i class="fas fa-paper-plane me-2"></i>
                                Envoyer la demande
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
