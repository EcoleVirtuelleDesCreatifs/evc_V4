@extends('layouts.app')

@section('title', 'Devenir Collaborateur - École Virtuelle des Créatifs')
@section('description', 'Rejoignez l\'équipe de l\'École Virtuelle des Créatifs. Postulez pour devenir collaborateur et participez à la transformation de l\'éducation digitale en Côte d\'Ivoire.')
@section('keywords', 'emploi evc, recrutement evc abidjan, collaborateur école virtuelle, carrière formation côte d\'ivoire')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
        :root {
            --primary-orange: #ff9800;
            --primary-orange-dark: #fb8c00;
            --primary-orange-light: #ffb74d;
            --dark-bg: #0f172a;
            --dark-bg-secondary: #1e293b;
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .collaborateur-page {
            font-family: 'Inter', sans-serif;
            background: var(--dark-bg);
            min-height: 100vh;
            color: var(--text-primary);
            padding: 80px 0;
        }

        /* Animated Background */
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .floating-shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.05;
            animation: float 20s infinite ease-in-out;
        }

        .shape-1 {
            top: 10%;
            left: 10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, var(--primary-orange) 0%, transparent 70%);
            animation-delay: 0s;
        }

        .shape-2 {
            top: 60%;
            right: 10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, var(--primary-orange-light) 0%, transparent 70%);
            animation-delay: 5s;
        }

        .shape-3 {
            bottom: 10%;
            left: 50%;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, var(--primary-orange-dark) 0%, transparent 70%);
            animation-delay: 10s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }

        /* Hero Section */
        .page-header {
            padding: 140px 0 80px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .hero-badge {
            display: inline-block;
            background: linear-gradient(135deg, rgba(255, 152, 0, 0.2) 0%, rgba(251, 140, 0, 0.2) 100%);
            border: 1px solid rgba(255, 152, 0, 0.3);
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--primary-orange-light);
            margin-bottom: 2rem;
            animation: fadeInDown 0.8s ease-out;
        }

        .page-title {
            font-size: 4rem;
            font-weight: 900;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #ff9800 0%, #ffb74d 50%, #ffffff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -2px;
            line-height: 1.2;
            animation: fadeInUp 1s ease-out 0.2s both;
        }

        .page-subtitle {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.8);
            max-width: 800px;
            margin: 0 auto;
            font-weight: 300;
            line-height: 1.8;
            animation: fadeInUp 1s ease-out 0.4s both;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
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
            padding: 30px 0 60px;
            position: relative;
            z-index: 1;
        }

        /* Stats Cards */
        .stats-row {
            margin-bottom: 4rem;
        }

        .stat-card-modern {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 152, 0, 0.2);
            border-radius: 20px;
            padding: 2rem 1.5rem;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
        }

        .stat-card-modern:hover {
            transform: translateY(-10px);
            border-color: var(--primary-orange);
            box-shadow: 0 20px 40px rgba(255, 152, 0, 0.2);
        }

        .stat-icon-modern {
            width: 60px;
            height: 60px;
            margin: 0 auto 1rem;
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-orange-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-orange);
            margin-bottom: 0.5rem;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
        }

        .info-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, transparent, var(--primary-orange), transparent);
            opacity: 0;
            transition: opacity 0.4s;
        }

        .info-card:hover::before {
            opacity: 1;
        }

        .info-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary-orange);
            box-shadow: 0 20px 50px rgba(255, 152, 0, 0.2);
        }

        .info-card h3 {
            color: var(--primary-orange);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .info-card h3 i {
            font-size: 2rem;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, rgba(255, 152, 0, 0.2) 0%, rgba(251, 140, 0, 0.2) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
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

        /* Form Section */
        .form-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 3rem;
            border: 1px solid rgba(255, 152, 0, 0.2);
        }

        .form-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-orange);
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .form-description {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: 1rem;
            margin-bottom: 2.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-label .required {
            color: var(--primary-orange);
            margin-left: 0.2rem;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 0.9rem 1.2rem;
            background: rgba(255, 255, 255, 0.08);
            border: 2px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary-orange);
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 4px rgba(255, 152, 0, 0.1);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-select option {
            background: var(--dark-bg-secondary);
            color: white;
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        /* File Upload */
        .file-upload-wrapper {
            position: relative;
            width: 100%;
        }

        .file-upload-area {
            position: relative;
            border: 2px dashed rgba(255, 152, 0, 0.4);
            border-radius: 16px;
            padding: 3rem 2rem;
            text-align: center;
            background: rgba(255, 152, 0, 0.05);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .file-upload-area:hover {
            border-color: var(--primary-orange);
            background: rgba(255, 152, 0, 0.1);
            transform: scale(1.01);
        }

        .file-upload-area.drag-over {
            border-color: var(--primary-orange);
            background: rgba(255, 152, 0, 0.15);
            transform: scale(1.02);
        }

        .file-upload-input {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload-icon {
            font-size: 3rem;
            color: var(--primary-orange);
            margin-bottom: 1rem;
        }

        .file-upload-text {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .file-upload-hint {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.5);
        }

        .file-name {
            margin-top: 1rem;
            padding: 0.8rem 1rem;
            background: rgba(76, 175, 80, 0.2);
            border: 1px solid rgba(76, 175, 80, 0.5);
            border-radius: 10px;
            color: #4caf50;
            font-weight: 600;
            display: none;
        }

        .file-name.show {
            display: block;
        }

        /* Submit Button */
        .submit-button {
            width: 100%;
            padding: 1.3rem 2rem;
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-orange-dark) 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 30px rgba(255, 152, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
        }

        .submit-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(255, 152, 0, 0.4);
        }

        .submit-button:active {
            transform: translateY(-1px);
        }

        /* Alert */
        .alert-custom {
            background: rgba(76, 175, 80, 0.15);
            border: 1px solid rgba(76, 175, 80, 0.5);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            color: #4caf50;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: fadeInDown 0.5s ease-out;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .hero-title, .page-title {
                font-size: 3rem;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .hero-title, .page-title {
                font-size: 2.5rem;
            }

            .hero-subtitle, .page-subtitle {
                font-size: 1.1rem;
            }

            .stat-card-modern {
                padding: 1.5rem 1rem;
            }

            .info-card, .form-container {
                padding: 2rem 1.5rem;
            }

            .back-button {
                top: 15px;
                left: 15px;
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }

            .file-upload-area {
                padding: 2rem 1.5rem;
            }

            .file-upload-icon {
                font-size: 2.5rem;
            }
        }
</style>
@endpush

@section('content')
<div class="collaborateur-page">
    <!-- Animated Background -->
    <div class="animated-bg">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
    </div>

    <!-- Header -->
    <section class="page-header">
        <div class="container">
            <div class="hero-badge">
                <i class="fas fa-briefcase me-2"></i>
                Opportunités de carrière
            </div>
            <h1 class="page-title">
                Rejoignez Notre Équipe
            </h1>
            <p class="page-subtitle">
                Participez à la révolution de l'éducation digitale en Afrique. 
                Ensemble, formons la prochaine génération de créatifs talentueux.
            </p>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="content-section">
        <div class="container">
            <div class="row stats-row justify-content-center">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-card-modern">
                        <div class="stat-icon-modern">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Collaborateurs</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-card-modern">
                        <div class="stat-icon-modern">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <div class="stat-number">200+</div>
                        <div class="stat-label">Projets réalisés</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-card-modern">
                        <div class="stat-icon-modern">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">Étudiants formés</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-card-modern">
                        <div class="stat-icon-modern">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="stat-number">95%</div>
                        <div class="stat-label">Taux de satisfaction</div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row">
                <!-- Left Column: Information -->
                <div class="col-lg-5 mb-4">
                    <!-- Pourquoi nous rejoindre -->
                    <div class="info-card">
                        <h3>
                            <i class="fas fa-star"></i>
                            Pourquoi nous rejoindre ?
                        </h3>
                        <ul>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Contribuez à former la prochaine génération de créatifs africains</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Environnement de travail dynamique et innovant</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Opportunités de développement professionnel</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Équipe passionnée et multiculturelle</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Impact social significatif</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Profils recherchés -->
                    <div class="info-card">
                        <h3>
                            <i class="fas fa-users"></i>
                            Profils recherchés
                        </h3>
                        <ul>
                            <li>
                                <i class="fas fa-laptop-code"></i>
                                <span>Développeurs Web & Mobile</span>
                            </li>
                            <li>
                                <i class="fas fa-palette"></i>
                                <span>Designers Graphiques</span>
                            </li>
                            <li>
                                <i class="fas fa-bullhorn"></i>
                                <span>Community Managers</span>
                            </li>
                            <li>
                                <i class="fas fa-chart-line"></i>
                                <span>Responsables Marketing</span>
                            </li>
                            <li>
                                <i class="fas fa-user-tie"></i>
                                <span>Assistants Administratifs</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div class="info-card">
                        <h2 class="form-title">
                            <i class="fas fa-file-alt me-2"></i>
                            Formulaire de candidature
                        </h2>
                        <p class="form-description">
                            Remplissez ce formulaire pour postuler à un poste au sein de notre équipe
                        </p>
                        <p>
                            <strong>Email :</strong> recrutement@evc.ci<br>
                            <strong>Téléphone :</strong> +225 XX XX XX XX XX<br>
                            <strong>Adresse :</strong> Abidjan, Côte d'Ivoire
                        </p>
                    </div>
                </div>

                <!-- Right Column: Form -->
                <div class="col-lg-7">
                    <div class="form-container">
                        <h2 class="form-title">Formulaire de candidature</h2>

                        @if(session('success'))
                            <div class="alert-custom">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('rejoignez-nous.collaborateur.submit') }}" method="POST" enctype="multipart/form-data">
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
                                    Poste souhaité <span class="required">*</span>
                                </label>
                                <select name="poste" class="form-select" required>
                                    <option value="">Sélectionnez un poste</option>
                                    <option value="developpeur_web">Développeur Web</option>
                                    <option value="developpeur_mobile">Développeur Mobile</option>
                                    <option value="designer_graphique">Designer Graphique</option>
                                    <option value="community_manager">Community Manager</option>
                                    <option value="responsable_marketing">Responsable Marketing</option>
                                    <option value="assistant_administratif">Assistant Administratif</option>
                                    <option value="autre">Autre (précisez dans le message)</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Années d'expérience <span class="required">*</span>
                                </label>
                                <select name="experience" class="form-select" required>
                                    <option value="">Sélectionnez</option>
                                    <option value="0-1">Moins d'1 an</option>
                                    <option value="1-3">1 à 3 ans</option>
                                    <option value="3-5">3 à 5 ans</option>
                                    <option value="5+">Plus de 5 ans</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Lettre de motivation <span class="required">*</span>
                                </label>
                                <textarea name="message" class="form-control" placeholder="Parlez-nous de vous, de vos motivations et de ce que vous pouvez apporter à EVC..." required></textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    CV (PDF, max 2MB) <span class="required">*</span>
                                </label>
                                <div class="file-upload-wrapper">
                                    <div class="file-upload-area" id="file-upload-area">
                                        <input type="file" name="cv" id="cv" class="file-upload-input" accept=".pdf" required>
                                        <div class="file-upload-icon">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </div>
                                        <div class="file-upload-text">Glissez-déposez votre CV ici</div>
                                        <div class="file-upload-hint">ou cliquez pour parcourir (PDF, max 2MB)</div>
                                    </div>
                                    <div class="file-name" id="cv-name"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Portfolio / LinkedIn (optionnel)
                                </label>
                                <input type="url" name="portfolio" class="form-control" placeholder="https://...">
                            </div>

                            <button type="submit" class="submit-button">
                                <i class="fas fa-paper-plane"></i>
                                <span>Envoyer ma candidature</span>
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
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('cv');
        const fileUploadArea = document.getElementById('file-upload-area');
        const fileNameDisplay = document.getElementById('cv-name');

        // File input change handler
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                displayFileName(file.name);
            }
        });

        // Drag and drop handlers
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            fileUploadArea.classList.add('drag-over');
        }

        function unhighlight() {
            fileUploadArea.classList.remove('drag-over');
        }

        fileUploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                fileInput.files = files;
                displayFileName(files[0].name);
            }
        }

        function displayFileName(name) {
            fileNameDisplay.textContent = `✓ Fichier sélectionné : ${name}`;
            fileNameDisplay.classList.add('show');
        }
    });
</script>
@endpush
