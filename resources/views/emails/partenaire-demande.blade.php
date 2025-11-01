<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Demande de Partenariat</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
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
        .info-section {
            background: #f8f9fa;
            border-left: 4px solid #4fc3f7;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .info-section h3 {
            margin-top: 0;
            color: #4fc3f7;
            font-size: 16px;
        }
        .info-row {
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: 600;
            color: #4fc3f7;
            display: inline-block;
            width: 150px;
        }
        .value {
            color: #555;
        }
        .message-box {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .message-box h4 {
            margin-top: 0;
            color: #4fc3f7;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🤝 Nouvelle Demande de Partenariat</h1>
            <p style="margin: 10px 0 0 0; font-size: 14px;">École Virtuelle des Créatifs</p>
        </div>

        <div class="content">
            <p>Bonjour,</p>
            <p>Une nouvelle demande de partenariat vient d'être soumise sur le site EVC.</p>

            <div class="info-section">
                <h3>🏢 Informations de l'organisation</h3>
                <div class="info-row">
                    <span class="label">Organisation :</span>
                    <span class="value">{{ $data['organisation'] }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Secteur d'activité :</span>
                    <span class="value">{{ $data['secteur'] }}</span>
                </div>
                @if(!empty($data['site_web']))
                <div class="info-row">
                    <span class="label">Site web :</span>
                    <span class="value"><a href="{{ $data['site_web'] }}" target="_blank">{{ $data['site_web'] }}</a></span>
                </div>
                @endif
            </div>

            <div class="info-section">
                <h3>👤 Contact</h3>
                <div class="info-row">
                    <span class="label">Nom du contact :</span>
                    <span class="value">{{ $data['nom_contact'] }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Email :</span>
                    <span class="value">{{ $data['email'] }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Téléphone :</span>
                    <span class="value">{{ $data['telephone'] }}</span>
                </div>
            </div>

            <div class="info-section">
                <h3>🎯 Type de partenariat</h3>
                <div class="info-row">
                    <span class="value">{{ ucfirst(str_replace('_', ' ', $data['type_partenariat'])) }}</span>
                </div>
            </div>

            <div class="message-box">
                <h4>📝 Description du projet</h4>
                <p style="white-space: pre-wrap; color: #555;">{{ $data['message'] }}</p>
            </div>

            <p style="margin-top: 30px; color: #666; font-size: 14px;">
                <strong>Action requise :</strong> Veuillez examiner cette demande et contacter l'organisation pour discuter des opportunités de collaboration.
            </p>
        </div>

        <div class="footer">
            <p style="margin: 0;">
                <strong>École Virtuelle des Créatifs</strong><br>
                Abidjan, Côte d'Ivoire<br>
                Email: partenariats@evc.ci
            </p>
            <p style="margin: 10px 0 0 0; color: #999;">
                Cet email a été généré automatiquement depuis le formulaire de partenariat du site web.
            </p>
        </div>
    </div>
</body>
</html>
