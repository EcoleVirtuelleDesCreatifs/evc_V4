<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet Design Validé - EVC Formation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #003366, #0066cc, #3399ff);
            background-attachment: fixed;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #003366, #3399ff);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            margin-bottom: 10px;
        }
        .celebration-icon {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
        }
        .content {
            padding: 30px;
        }
        .success-badge {
            background: linear-gradient(135deg, #003366, #3399ff);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            display: inline-block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .project-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #003366;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #003366, #3399ff);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
            box-shadow: 0 4px 8px rgba(0, 51, 102, 0.3);
            transition: all 0.3s ease;
        }
        .btn:hover {
            background: linear-gradient(135deg, #002244, #2288ee);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 51, 102, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <span class="celebration-icon">🏆</span>
            <h1>🎉 FÉLICITATIONS ! 🎉</h1>
            <p style="font-size: 18px; margin: 0;">Votre projet design a été validé avec excellence !</p>
        </div>
        
        <div class="content">
            <div class="success-badge">✅ PROJET VALIDÉ AVEC SUCCÈS</div>
            
            <p>Bonjour <strong>{{ $studentName }}</strong>,</p>
            
            <p style="font-size: 16px; color: #003366; font-weight: bold;">🌟 BRAVO ! Votre travail exceptionnel a été reconnu ! 🌟</p>
            
            <p>Nous avons le grand plaisir de vous informer que votre projet design a été <strong>validé avec succès</strong> par notre équipe pédagogique. Votre créativité et votre maîtrise technique nous ont impressionnés !</p>
            
            <div class="project-info">
                <h3>📋 Détails du projet validé :</h3>
                <ul>
                    <li><strong>Titre :</strong> {{ $projectTitle }}</li>
                    <li><strong>Type :</strong> {{ $projectType }}</li>
                    <li><strong>Validé le :</strong> {{ $validatedAt }}</li>
                </ul>
            </div>
            
            <p>Ce projet témoigne de votre progression et de la qualité de votre travail. Continuez sur cette excellente voie !</p>
            
            <p>Vous pouvez maintenant :</p>
            <ul>
                <li>✨ Consulter votre projet validé dans votre espace étudiant</li>
                <li>🚀 Travailler sur votre prochain projet design</li>
                <li>📈 Suivre votre progression vers la certification</li>
            </ul>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="http://127.0.0.1:8000/auth/evc/login" class="btn">
                    Accéder à mon espace étudiant
                </a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>EVC Formation</strong> - Excellence en Design Graphique</p>
            <p>Continuez à créer, nous sommes fiers de vous accompagner !</p>
        </div>
    </div>
</body>
</html>
