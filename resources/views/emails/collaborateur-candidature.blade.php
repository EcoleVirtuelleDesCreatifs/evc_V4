<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Candidature Collaborateur</title>
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
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
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
            color: #1e3c72;
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
            color: #1e3c72;
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
            color: #1e3c72;
        }
        .cv-info {
            background: #e3f2fd;
            border: 1px solid #4fc3f7;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .cv-info i {
            font-size: 24px;
            color: #1e3c72;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Nouvelle Candidature Collaborateur</h1>
            <p style="margin: 10px 0 0 0; font-size: 14px;">École Virtuelle des Créatifs</p>
        </div>

        <div class="content">
            <p>Bonjour,</p>
            <p>Une nouvelle candidature pour un poste de collaborateur vient d'être soumise sur le site EVC.</p>

            <div class="info-section">
                <h3>👤 Informations du candidat</h3>
                <div class="info-row">
                    <span class="label">Nom complet :</span>
                    <span class="value">{{ $data['prenom'] }} {{ $data['nom'] }}</span>
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
                <h3>💼 Informations professionnelles</h3>
                <div class="info-row">
                    <span class="label">Poste souhaité :</span>
                    <span class="value">{{ ucfirst(str_replace('_', ' ', $data['poste'])) }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Expérience :</span>
                    <span class="value">{{ $data['experience'] }}</span>
                </div>
                @if(!empty($data['portfolio']))
                <div class="info-row">
                    <span class="label">Portfolio :</span>
                    <span class="value"><a href="{{ $data['portfolio'] }}" target="_blank">{{ $data['portfolio'] }}</a></span>
                </div>
                @endif
            </div>

            <div class="message-box">
                <h4>📝 Lettre de motivation</h4>
                <p style="white-space: pre-wrap; color: #555;">{{ $data['message'] }}</p>
            </div>

            <div class="cv-info">
                <p style="margin: 0; font-weight: 600; color: #1e3c72;">📄 CV joint</p>
                <p style="margin: 5px 0 0 0; font-size: 13px; color: #666;">
                    Le CV a été téléchargé et est disponible dans le dossier : <br>
                    <code style="background: #fff; padding: 5px 10px; border-radius: 3px; display: inline-block; margin-top: 5px;">
                        storage/app/public/{{ $data['cv_path'] ?? 'candidatures/collaborateurs' }}
                    </code>
                </p>
            </div>

            <p style="margin-top: 30px; color: #666; font-size: 14px;">
                <strong>Action requise :</strong> Veuillez examiner cette candidature et contacter le candidat si son profil correspond à vos besoins.
            </p>
        </div>

        <div class="footer">
            <p style="margin: 0;">
                <strong>École Virtuelle des Créatifs</strong><br>
                Abidjan, Côte d'Ivoire<br>
                Email: recrutement@evc.ci
            </p>
            <p style="margin: 10px 0 0 0; color: #999;">
                Cet email a été généré automatiquement depuis le formulaire de candidature du site web.
            </p>
        </div>
    </div>
</body>
</html>
