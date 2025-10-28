<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau TP soumis</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #4fc3f7 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .email-header .icon {
            font-size: 60px;
            margin-bottom: 15px;
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .email-body {
            padding: 40px 30px;
            color: #333;
        }
        .alert-box {
            background: linear-gradient(135deg, rgba(30, 60, 114, 0.1), rgba(79, 195, 247, 0.1));
            border-left: 4px solid #2a5298;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .alert-box h2 {
            margin: 0 0 10px 0;
            font-size: 20px;
            color: #1e3c72;
        }
        .alert-box p {
            margin: 0;
            color: #555;
            line-height: 1.6;
        }
        .student-info {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
        }
        .student-info h3 {
            margin: 0 0 15px 0;
            font-size: 18px;
            color: #1e3c72;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e9ecef;
        }
        .info-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        .info-icon {
            font-size: 18px;
            margin-right: 12px;
            min-width: 25px;
            text-align: center;
            color: #2a5298;
        }
        .info-content {
            flex: 1;
        }
        .info-label {
            font-weight: 600;
            color: #1e3c72;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        .info-value {
            color: #333;
            font-size: 15px;
        }
        .tp-details {
            background: linear-gradient(135deg, rgba(30, 60, 114, 0.05), rgba(79, 195, 247, 0.05));
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
        }
        .tp-details h3 {
            margin: 0 0 15px 0;
            font-size: 18px;
            color: #1e3c72;
        }
        .tp-description {
            color: #555;
            line-height: 1.8;
            margin-bottom: 15px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #1e3c72 0%, #4fc3f7 100%);
            color: white;
            text-decoration: none;
            padding: 18px 40px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 16px;
            text-align: center;
            margin: 30px 0;
            box-shadow: 0 6px 20px rgba(30, 60, 114, 0.4);
            transition: all 0.3s ease;
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 60, 114, 0.5);
        }
        .stats-box {
            display: flex;
            gap: 15px;
            margin: 20px 0;
        }
        .stat-item {
            flex: 1;
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
        }
        .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: #1e3c72;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .submission-link {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
            word-break: break-all;
        }
        .submission-link a {
            color: #1976d2;
            text-decoration: none;
            font-weight: 600;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #e9ecef;
        }
        .email-footer p {
            margin: 5px 0;
        }
        .email-footer a {
            color: #1e3c72;
            text-decoration: none;
            font-weight: 600;
        }
        .badge {
            display: inline-block;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="icon">🎓</div>
            <h1>Nouveau TP Soumis</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <div class="alert-box">
                <h2>⚠️ Action requise</h2>
                <p>Un étudiant vient de soumettre un travail pratique qui nécessite votre validation. Veuillez consulter et évaluer le travail soumis.</p>
            </div>

            <!-- Informations étudiant -->
            <div class="student-info">
                <h3>👤 Informations de l'étudiant</h3>
                <div class="info-item">
                    <div class="info-icon">📝</div>
                    <div class="info-content">
                        <div class="info-label">Nom complet</div>
                        <div class="info-value">{{ $student->first_name }} {{ $student->last_name }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">📧</div>
                    <div class="info-content">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $student->email ?? 'Non renseigné' }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">🎓</div>
                    <div class="info-content">
                        <div class="info-label">Formation</div>
                        <div class="info-value">{{ $formation }}</div>
                    </div>
                </div>
            </div>

            <!-- Détails du TP -->
            <div class="tp-details">
                <h3>📚 Détails du TP soumis</h3>
                <h4 style="color: #1e3c72; margin-bottom: 10px;">{{ $tpTitle }}</h4>
                <div class="tp-description">
                    {!! Str::limit(strip_tags($tpDescription), 200) !!}
                </div>

                <!-- Statistiques -->
                <div class="stats-box">
                    <div class="stat-item">
                        <div class="stat-number">{{ $filesCount }}</div>
                        <div class="stat-label">Fichier(s)</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</div>
                        <div class="stat-label">Date soumission</div>
                    </div>
                </div>

                @if($submissionLink)
                <div class="submission-link">
                    <strong>🔗 Lien de soumission :</strong><br>
                    <a href="{{ $submissionLink }}" target="_blank">{{ $submissionLink }}</a>
                </div>
                @endif
            </div>

            <!-- CTA Button -->
            <center>
                <a href="{{ $tpUrl }}" class="cta-button">
                    📋 Accéder à la gestion des TP
                </a>
            </center>

            <div style="margin-top: 30px; padding: 20px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 10px;">
                <p style="margin: 0; color: #856404; font-size: 14px;">
                    <strong>💡 Rappel :</strong> Pensez à évaluer ce TP dans les meilleurs délais pour permettre à l'étudiant de progresser dans sa formation.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>École Virtuelle des Créatifs</strong></p>
            <p>Plateforme de gestion des travaux pratiques</p>
            <p style="margin-top: 15px;">
                <a href="{{ url('/') }}">Accéder au site</a> | 
                <a href="{{ $tpUrl }}">Tableau de bord admin</a>
            </p>
            <p style="margin-top: 20px; font-size: 12px; color: #999;">
                Cet email a été envoyé automatiquement. Merci de ne pas y répondre directement.
            </p>
        </div>
    </div>
</body>
</html>
