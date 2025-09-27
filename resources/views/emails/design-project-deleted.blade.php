<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet Design Supprimé - EVC Formation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
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
            background: linear-gradient(135deg, #dc3545, #ff6b6b);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .warning-badge {
            background: #dc3545;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            display: inline-block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .project-info {
            background: #fff3cd;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
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
            background: #003366;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .support-box {
            background: #e7f3ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ Information Importante</h1>
            <p>Suppression de votre projet design</p>
        </div>
        
        <div class="content">
            <div class="warning-badge">🗑️ PROJET SUPPRIMÉ</div>
            
            <p>Bonjour <strong>{{ $studentName }}</strong>,</p>
            
            <p>Nous vous informons que votre projet design a été <strong>supprimé</strong> de notre plateforme par l'équipe pédagogique.</p>
            
            <div class="project-info">
                <h3>📋 Détails du projet supprimé :</h3>
                <ul>
                    <li><strong>Titre :</strong> {{ $projectTitle }}</li>
                    <li><strong>Type :</strong> {{ $projectType }}</li>
                    <li><strong>Supprimé le :</strong> {{ $deletedAt }}</li>
                </ul>
            </div>
            
            <p><strong>Pourquoi cette suppression ?</strong></p>
            <p>Cette action peut être due à plusieurs raisons :</p>
            <ul>
                <li>📝 Le projet ne respectait pas les consignes données</li>
                <li>🎯 Le contenu n'était pas adapté au niveau de formation</li>
                <li>⚖️ Non-conformité aux règles de la plateforme</li>
                <li>🔄 Demande de resoumission avec corrections</li>
            </ul>
            
            <div class="support-box">
                <h3>💡 Que faire maintenant ?</h3>
                <p>Ne vous découragez pas ! Cette étape fait partie de l'apprentissage :</p>
                <ul>
                    <li>✨ Consultez les commentaires de votre formateur</li>
                    <li>📚 Révisez les ressources de formation</li>
                    <li>🚀 Préparez un nouveau projet en tenant compte des retours</li>
                    <li>💬 Contactez votre formateur pour des conseils personnalisés</li>
                </ul>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.url') }}/evc/compte/design-graphique/espace-etudiant" class="btn">
                    Accéder à mon espace étudiant
                </a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>EVC Formation</strong> - Excellence en Design Graphique</p>
            <p>Notre équipe reste disponible pour vous accompagner dans votre réussite !</p>
            <p>📧 Support : support@evc-formation.com | 📞 Contact : +225 XX XX XX XX</p>
        </div>
    </div>
</body>
</html>
