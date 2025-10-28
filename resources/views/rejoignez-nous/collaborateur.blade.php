<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devenir Collaborateur - École Virtuelle des Créatifs</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Rejoignez l'équipe de l'École Virtuelle des Créatifs. Postulez pour devenir collaborateur et participez à la transformation de l'éducation digitale en Côte d'Ivoire.">
    <meta name="keywords" content="emploi evc, recrutement evc abidjan, collaborateur école virtuelle, carrière formation côte d'ivoire">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
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
            background: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%);
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
        }
    </style>
</head>
<body>
    <!-- Back Button -->
    <a href="{{ route('rejoignez-nous') }}" class="back-button">
        <i class="fas fa-arrow-left"></i>
        <span>Retour</span>
    </a>

    <!-- Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="page-title">
                <i class="fas fa-handshake me-3"></i>
                Devenir Collaborateur
            </h1>
            <p class="page-subtitle">
                Rejoignez notre équipe et participez à la transformation de l'éducation digitale en Afrique
            </p>
        </div>
    </section>

    <!-- Content -->
    <section class="content-section">
        <div class="container">
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
                        <h3>
                            <i class="fas fa-envelope"></i>
                            Contact
                        </h3>
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
                                    Portfolio / LinkedIn (optionnel)
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
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // File upload display
        document.getElementById('cv').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || '';
            document.getElementById('cv-name').textContent = fileName ? `Fichier sélectionné : ${fileName}` : '';
        });
    </script>
</body>
</html>
